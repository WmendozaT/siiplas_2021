<?php
class C_consultas extends CI_Controller {  
    public $rol = array('1' => '1','2' => '10'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('consultas/model_consultas');
            $this->load->model('programacion/model_proyecto');
          //  $this->load->model('programacion/model_faseetapa');
          //  $this->load->model('programacion/model_actividad');
          //  $this->load->model('programacion/model_producto');
          //  $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->ppto= $this->session->userData('verif_ppto'); 
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('genera_informacion');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }


    /*---------- TIPO DE RESPONSABLE ----------*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='RESPONSABLE NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }


    //// CONSOLIDADO POA POR REGIONALES (2020-2021)
    public function mis_operaciones(){
      $data['menu']=$this->genera_informacion->menu(10);
      $data['list']=$this->menu_nacional();
      $data['style']=$this->genera_informacion->style();
      $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      
      $data['mensaje']='<div class="jumbotron"><h1>RESUMEN POA '.$this->gestion.'</h1><p>Reporte Resumen consolidado de Programación POA a nivel Regional, segun la siguiente Clasificación :</p><ol style="font-size:16px;"><li>Genera informacion de Programación, Modificacion, Evaluación y Certificacion POA, segun el tipo de Gasto</li><li>Genera Reporte Consolidado del Fornulario N° 4 (Actividades) por Regional.</li><li>Genera Reporte Consolidado del Fornulario N° 5 (requerimientos) por Regional.</li><li>Genera el listado de Certificaciones POA por Regional.</li><li>Genera Informacion sobre la Evaluación POA a nivel Regional</li></ol></div>';
      $this->load->view('admin/consultas_internas/menu_consultas_poa', $data);
     // echo $this->list_certificacionpoa(2361,4);
    //  echo $this->genera_informacion->mis_servicios(1,2361);
    }

  /*-----  OPCIONES 2020-2021 -----*/
    public function get_opciones($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {

        case 'reporte':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo Reporte....</option>";
          $salida.= "<option value='1'>1.- CONSOLIDADO POA SEGUN TIPO DE GASTO</option>";
          $salida.= "<option value='2'>2.- CONSOLIDADO FORMULARIO 4 (ACTIVIDADES)</option>";
          $salida.= "<option value='3'>3.- CONSOLIDADO FORMULARIO 5 (REQUERIMEINTOS)</option>";
          $salida.= "<option value='4'>4.- LISTA DE CERTIFICACIONES POA</option>";
          $salida.= "<option value='5'>5.- EVALUACION POA REGIONAL</option>";

        echo $salida; 
        //return $salida;
        break;

        case 'tipo':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo ....</option>";
          $salida.= "<option value='4'>GASTO CORRIENTE</option>";
          $salida.= "<option value='1'>PROYECTO DE INVERSIÓN</option>";

        echo $salida; 
        //return $salida;
        break;
      }

    }

    //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
      $tabla.='
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <input type="hidden" name="base" value="'.base_url().'">
              <header><b>PLAN OPERATIVO ANUAL - POA '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-3">
                    <label class="label"><b>DIRECCIÓN ADMINISTRATIVA</b></label>
                    <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                    <option value="">SELECCIONE REGIONAL</option> ';
                    foreach($regionales as $row){
                      if($row['dep_id']!=0){
                        $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                      }
                    }
                    $tabla.='
                    </select>
                  </section>

                  <section class="col col-3" id="tprep">
                    <label class="label"><b>TIPO DE REPORTE</b></label>
                    <select class="form-control" id="tp_rep" name="tp_rep" title="SELECCIONE TIPO DE REPORTE">
                    </select>
                  </section>

                  <section class="col col-3" id="tp">
                    <label class="label"><b>TIPO DE GASTO</b></label>
                    <select class="form-control" id="tipo" name="tipo" title="SELECCIONE TIPO DE GASTO">
                    </select>
                  </section>
                </div>
              </fieldset>
          </form>
          </div>
        </article>';
      return $tabla;
    }


    /*--- GET TIPO DE REPORTE (2020 - 2021)---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tp_rep = $this->security->xss_clean($post['tp_rep']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        
        $salida='';
        if($tp_rep==1){
          $salida=$this->genera_informacion->lista_gastocorriente_pinversion($dep_id,0,$tp_id);
        }
        elseif ($tp_rep==2) {
          $salida=$this->genera_informacion->consolidado_operaciones_distrital($dep_id,0,$tp_id); /// Consolidado Formulario N° 4 
        }
        elseif ($tp_rep==3) {
          $salida=$this->genera_informacion->consolidado_requerimientos_distrital($dep_id,0,$tp_id); /// Consolidado formulario N° 5
        }
        elseif ($tp_rep==4) {
          $salida=$this->genera_informacion->lista_certificaciones_poa($dep_id,$tp_id);
        }

        //$lista=$this->lista_certificaciones_poa($dist_id,$tp_id);
        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*-------- GET DATOS MODIFICACION POA --------*/
    public function get_mpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }

        $tabla=$this->list_cites_generados($proy_id); /// Mis Modificaciones POA
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- LISTA DE MODIFCACIONES FORMULARIO 4 Y 5 ---*/
    public function list_cites_generados($proy_id){
      $tabla='';
      // === LIST CITES REQUERIMIENTOS 
        $cites_form5=$this->model_modrequerimiento->list_cites_requerimientos_proy($proy_id);
        $cites_form4=$this->model_modfisica->list_cites_Operaciones_proy($proy_id);

        $tabla.='
        <hr>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid" >
            <section class="col col-6">
              <input id="searchTerm_form4" type="text" onkeyup="doSearch_form4()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
            </section>
            <b>FORMULARIO N° 4 - ACTIVIDADES</b>
            <table class="table table-bordered" id="datos_form4">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CITE</th>
                  <th scope="col">FECHA CITE</th>
                  <th scope="col">COD. MOD. POA.</th>
                  <th scope="col">UNIDAD RESPONSABLE</th>
                  <th scope="col">REPORTE</th>
                </tr>
              </thead>
              <tbody>';
                $nro=0;
                foreach($cites_form4 as $cit){
                  $ca=$this->model_modfisica->operaciones_adicionados($cit['cite_id']);
                  $cm=$this->model_modfisica->operaciones_modificados($cit['cite_id']);
                  $cd=$this->model_modfisica->operaciones_eliminados($cit['cite_id']);

                  if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                    $nro++;
                    $tabla .='<tr>';
                      $tabla .='<td align="center">'.$nro.'</td>';
                      $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                      $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                      $tabla .='<td></td>';
                      $tabla .='<td>'.$cit['com_componente'].'</td>';
                      $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/reporte_modfis/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE ACTIVIDADES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                    $tabla .='</tr>';
                  }
                }
              $tabla.='
              </tbody>
            </table>
          </section>
        </article>

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid">
            <section class="col col-6">
              <input id="searchTerm_form5" type="text" onkeyup="doSearch_form5()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
            </section>
            <b>FORMULARIO N° 5 - REQUERIMIENTOS</b>
            <table class="table table-bordered" id="datos_form5">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CITE</th>
                  <th scope="col">FECHA CITE</th>
                  <th scope="col">COD. MOD. POA.</th>
                  <th scope="col">UNIDAD RESPONSABLE</th>
                  <th scope="col">REPORTE</th>
                </tr>
              </thead>
              <tbody>';
               $nro=0;
                foreach($cites_form5 as $cit){
                  $color='';
                  $codigo='<font color=blue><b>'.$cit['cite_codigo'].'</b></font>';
                  if($cit['cite_estado']==0){
                    $color='#fbdfdf';
                    $codigo='<font color=red><b>SIN CÓDIGO</b></font>';
                  }

                    $nro++;
                    $tabla .='<tr bgcolor='.$color.'>';
                      $tabla .='<td align="center">'.$nro.'</td>';
                      $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                      $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                      $tabla .='<td>'.$codigo.'</td>';
                      $tabla .='<td>'.$cit['com_componente'].'</td>';
                      $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_financiera/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE REQUERIMIENTOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                    $tabla .='</tr>';
                  }
              $tabla.=' 
              </tbody>
            </table>
          </section>
        </article>';

      return $tabla;
    }



    /*--- GET DATOS CERTIFICACION POA POR UNIDAD 2022 ---*/
    public function get_evalpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }

        $tabla=$this->genera_informacion->detalle_evaluacionpoa($proy_id); /// Mi evaluacion
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }









    //// cambiar gestion 
    public function cambiar_gestion(){
      $nueva_gestion = strtoupper($this->input->post('gestion_usu'));
      $this->session->set_userdata('gestion', $nueva_gestion);

      redirect('consulta/mis_operaciones','refresh');
    }


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