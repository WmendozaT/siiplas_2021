<?php
class Creportejecucion_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null){
        $this->load->model('Users_model','',true);
        if($this->rolfun($this->rol)){ 
          $this->load->library('pdf2');
          $this->load->model('menu_modelo');
          $this->load->model('consultas/model_consultas');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('programacion/model_faseetapa');
          $this->load->model('programacion/model_actividad');
          $this->load->model('programacion/model_producto');
          $this->load->model('programacion/model_componente');
          $this->load->model('mantenimiento/model_ptto_sigep');
          $this->pcion = $this->session->userData('pcion');
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->dep_id = $this->session->userData('dep_id');
          $this->tmes = $this->session->userData('trimestre');
          $this->fun_id = $this->session->userData('fun_id');
          $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
          $this->ppto= $this->session->userData('verif_ppto'); 
          $this->verif_mes=$this->session->userData('mes_actual');
          $this->tp_adm = $this->session->userData('tp_adm');
          $this->load->library('ejecucion_finpi');
        }else{
            redirect('admin/dashboard');
        }
    }
    else{
        redirect('/','refresh');
    }
  }


/*------- menu Proyectos de Inversion -------*/
public function menu_pi(){
  $data['menu']=$this->menu(7);
  $data['list']=$this->menu_nacional();

  $tabla='
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
    <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">
    <div id="update_eval">
      <div class="jumbotron">
        <h1>Seguimiento a Proyectos de Inversión '.$this->gestion.'</h1>
        <p>
          Muestra el avance de ejecución Presupuestaria de Proyectos de Inversion al mes de <b>'.$this->verif_mes[2].' / '.$this->gestion.'</b>, a nivel Nacional, Regional y Distrital.
        </p>
      </div>
    </div>';
   
    $data['titulo_modulo']=$tabla;

 //   $this->load->view('admin/reportes_cns/repejecucion_pi/menu_pi', $data);

    $regionales=$this->model_proyecto->list_departamentos();
      $tabla.='
      <table border=1>
        <tr>
          <td></td>
          <td>REGIONAL</td>
          <td>DISTRITAL</td>
          <td>N DE PROYECTOS</td>
          <td>PPTO. INICIAL</td>
          <td>PPTO. MODIFICADO</td>
          <td>PPTO. VIGENTE</td>
          <td>PPTO. EJECUTADO</td>
          <td>% EJECUTADO</td>
        </tr>';
      foreach($regionales as $row){

        $nro_proy=0;
        if(count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']))!=0){
          $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']));
        }

        $modificacion_partida=$this->ejecucion_finpi->detalle_modificacion_ppto_x_regional($row['dep_id']); //// Modificacion de partidas

        $ejecucion=$this->model_ptto_sigep->get_ppto_ejecutado_regional($row['dep_id']); //// ejecucion de Presupuesto
        $ejec_ppto=0;
        if(count($ejecucion)!=0){
          $ejec_ppto=$ejecucion[0]['ejecutado_total'];
        }

        $avance_financiero=0;
        if($modificacion_partida[3]!=0){
          $avance_financiero=round((($ejec_ppto/$modificacion_partida[3])*100),2);
        }
        
        $tabla.='
        <tr>
          <td>'.$row['dep_id'].'</td>
          <td>'.$row['dep_departamento'].'</td>
          <td></td>
          <td>'.$nro_proy.'</td>
          <td>'.$modificacion_partida[1].'</td>
          <td>'.$modificacion_partida[2].'</td>
          <td>'.$modificacion_partida[3].'</td>
          <td>'.$ejec_ppto.'</td>
          <td>'.$avance_financiero.'</td>
        </tr>';
      }

      $tabla.='</table>';

  echo $tabla;
}

  //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
  public function menu_nacional(){
  $tabla='';
  $regionales=$this->model_proyecto->list_departamentos();
    $tabla.='
    <article class="col-sm-12">
      <div class="well">
        <form class="smart-form">
            <header><b>SEGUIMIENTO A PROYECTOS DE INVERSIÓN '.$this->gestion.'</b></header>
            <fieldset>          
              <div class="row">
                <section class="col col-3">
                  <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                  <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                  <option value="">SELECCIONE REGIONAL</option>
                  <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                  foreach($regionales as $row){
                    if($row['dep_id']!=0){
                      $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                    }
                  }
                  $tabla.='
                  </select>
                </section>
              </div>
            </fieldset>
        </form>
        </div>
      </article>';
  return $tabla;
}


/*--- GET DETALLE DE EJECUCION PRESUPUESTARIA DE PROYECTOS DE INVERSION REGIONAL, INSTITUCIONAL---*/
public function get_detalle_ejecucion_ppto_pi_regional_institucional(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $dep_id = $this->security->xss_clean($post['dep_id']);
    $regional=$this->model_proyecto->get_departamento($dep_id);

    if($dep_id==0){

      $regionales=$this->model_proyecto->list_departamentos();
      foreach($regionales as $row){
        $modificacion_partida=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

      }















      $tabla='trabajando';

      $result = array(
        'respuesta' => 'correcto',
        'lista_reporte' => $tabla,
      );

    }
    else{
       /// s1
      $lista_detalle=$this->ejecucion_finpi->detalle_avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero

      //// s2
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id));
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_regional($dep_id); /// Matriz consolidado de partidas
      $consolidado=$this->ejecucion_finpi->tabla_consolidado_partidas_regional($matriz_partidas,$dep_id,0); /// Tabla Clasificacion de partidas asignados por regional
      $grafico_consolidado_partidas='<div id="container" style="width: 1000px; height: 680px; margin: 0 auto"></div>';

      //// s3
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_regional($dep_id); /// ejecutado mensual
      $vector_meses_acumulado=$this->ejecucion_finpi->vector_consolidado_ppto_acumulado_mensual_regional($dep_id); /// ejecutado mensual Acumulado
      $tabla1=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,$dep_id);
      $grafico_mes='<div id="ejec_mensual" style="width: 900px; height: 680px; margin: 2 auto"></div>';
      $grafico_mes_acumulado='<div id="ejec_acumulado_mensual" style="width: 900px; height: 680px; margin: 2 auto"></div>';

      $tabla='
      <h2>Ejecucion Presupuestaria - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'</h2>
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
            <h2>Ejecucion Presupuestaria</h2>
        </header>
        <div>
            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body">
                <p>
                <div style="font-size: 25px;font-family: Arial¨;"><b></b></div>
                </p>
                <hr class="simple">
                <ul id="myTab1" class="nav nav-tabs bordered">
                  <li class="active">
                      <a href="#s1" data-toggle="tab"> Detalle Proyectos</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> Consolidado por Partidas</a>
                  </li>
                  <li>
                      <a href="#s3" data-toggle="tab"> Consolidado por Meses</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                  <div class="tab-pane fade in active" id="s1">
                      <div class="row">
                        <div class="table-responsive" align=center>
                          <table style="width:100%;" border=0>
                            <tr>
                              <td style="width:100%;" align=right>
                                <a href="javascript:abreVentana(\''.site_url("").'/reporte_detalle_ppto_pi/'.$dep_id.'/3\');" title="GENERAR REPORTE" class="btn btn-default">
                                  <img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="23" HEIGHT="24"/>&nbsp;GENERAR REPORTE (PDF)
                                </a>
                                <a href="'.site_url("").'/xls_rep_ejec_fin_pi/'.$dep_id.'/3" target=black title="EXPORTAR DETALLE" class="btn btn-default">
                                  <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;EXPORTAR DETALLE (EXCEL)
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td><hr></td>
                            </tr>
                            <tr>
                              <td>
                                <form class="smart-form" method="post">
                                  <section class="col col-3">
                                    <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/>
                                  </section>
                                </form>
                              </td>
                            </tr>
                          </table>
                        </div>
                        '.$lista_detalle.'
                      </div>
                  </div>
                  
                  <div class="tab-pane fade" id="s2">
                    <div class="row">
                      <article class="col-sm-12">
                        '.$grafico_consolidado_partidas.'<hr>'.$consolidado.'
                      </article>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="s3">
                    <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes.'
                      </div>
                    </article>
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes_acumulado.'
                      </div>
                    </article>
                     <hr>
                     '.$tabla1.'
                    </div>
                  </div>

                </div>
            </div>
          </div>
      </div>';


      $result = array(
        'respuesta' => 'correcto',
        'nro'=>$nro,
        'matriz'=>$matriz_partidas,
        'vector_meses'=>$vector_meses,
        'vector_meses_acumulado'=>$vector_meses_acumulado,
        'lista_reporte' => $tabla,
      );
    }

    echo json_encode($result);
  }else{
      show_404();
  }
}



  /*---- GET DETALLE DE LOS REPORTES GENERADOS  ----*/
 




























  /*========= GENERAR MENU ==========*/
  function menu($mod){
    $enlaces=$this->menu_modelo->get_Modulos($mod);
    for($i=0;$i<count($enlaces);$i++) {
      $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
    }

    $tabla ='';
    for($i=0;$i<count($enlaces);$i++){
        if(count($subenlaces[$enlaces[$i]['o_child']])>0){
            $tabla .='<li>';
                $tabla .='<a href="#">';
                    $tabla .='<i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>';    
                    $tabla .='<ul>';    
                        foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
                        $tabla .='<li><a href="'.base_url($item['o_url']).'">'.$item['o_titulo'].'</a></li>';
                    }
                    $tabla .='</ul>';
            $tabla .='</li>';
        }
    }
    return $tabla;
  }
  /*-----------------------------------*/

  /*----------------------------------------*/
  function rolfun($rol){
    $valor=false;
    for ($i=1; $i <=count($rol) ; $i++) { 
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
      if(count($data)!=0){
        $valor=true;
        break;
      }
    }
    return $valor;
  }

}