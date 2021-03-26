<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cpterminal_cplazo extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');  
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
          $this->load->library('pdf');
          $this->load->library('pdf2');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('resultados/model_resultado');
          $this->load->model('mestrategico/model_mestrategico');
          $this->load->model('mantenimiento/mpoa');
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->fun_id = $this->session->userData('fun_id');
        }else{
            redirect('/','refresh');
        }
    }

    /*------------------------- TIPO DE RESPONSABLE ---------------------*/
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
    
    /*------------------- LISTA PRODUCTOS TERMINALES DE CORTO PLAZO ---------------------*/
    public function list_pterminal_cp($poa_id,$rm_id){
      $data['menu']=$this->menu(1);
      $data['configuracion']=$this->model_proyecto->configuracion();
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion();
      $data['resultado']=$this->model_mestrategico->get_resultado_mplazo($rm_id);

      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['resultado'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      
      $data['pterminal_cp']=$this->mis_pterminales_cp($poa_id,$rm_id);
      $this->load->view('admin/red_objetivos/pterminal_cplazo/list_pterminal', $data);
    }

    /*------------------------- TEMPORALIDAD DE PRODUCTO TERMINAL DE CORTO PLAZO ---------------------*/
    public function ptcplazo_temporalidad($poa_id,$ptm_id){
      $data['menu']=$this->menu(1);
      $data['configuracion']=$this->model_proyecto->configuracion();
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['pterminal']=$this->model_mestrategico->get_pterminal_mplazo($ptm_id);
      $data['resultado']=$this->model_mestrategico->get_resultado_mplazo($data['pterminal'][0]['rm_id']);
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['resultado'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['prog']=$this->model_mestrategico->get_pterminal_cplazo_programado_gestion($ptm_id);
      $data['temporalidad']=$this->temporalidad($ptm_id);

      $this->load->view('admin/red_objetivos/pterminal_cplazo/temp_cplazo', $data);
    }

    function temporalidad($ptm_id){
      $pterminal=$this->model_mestrategico->get_pterminal_mplazo($ptm_id); 
      $programado=$this->model_mestrategico->get_pterminal_mplazo_programado_gestion($ptm_id);
      $programado_mensual=$this->model_mestrategico->get_pterminal_cplazo_programado($programado[0]['ptmp_id']);

      for ($i=0; $i <=12 ; $i++) { 
        $mes[$i]='mes'.$i.'';
        $prog[$i]=0;
      }

      if(count($programado_mensual)!=0){

        for ($i=1; $i <=12 ; $i++) { 
          $prog[$i]=$programado_mensual[0][$mes[$i]];
        }
        $prog[0]=$programado_mensual[0]['programado_total'];
      }
      return $prog;
    }

    function tabla_temporalidad($matriz){ 
      $tabla = '';
      $tabla .='<table class="table table-bordered">';
        $tabla .='<tr bgcolor=#1c7368>';
          $tabla .='<th></th>';
          $tabla .='<th><font color=white>ENE.</font></th>';
          $tabla .='<th><font color=white>FEB.</font></th>';
          $tabla .='<th><font color=white>MAR.</font></th>';
          $tabla .='<th><font color=white>ABR.</font></th>';
          $tabla .='<th><font color=white>MAY.</font></th>';
          $tabla .='<th><font color=white>JUN.</font></th>';
          $tabla .='<th><font color=white>JUL.</font></th>';
          $tabla .='<th><font color=white>AGOS.</font></th>';
          $tabla .='<th><font color=white>SEPT.</font></th>';
          $tabla .='<th><font color=white>OCT.</font></th>';
          $tabla .='<th><font color=white>NOV.</font></th>';
          $tabla .='<th><font color=white>DIC.</font></th>';
        $tabla .='</tr>';
        $tabla .='<tr>';
            $tabla .='<td>PROG.</td>';
            for ($j=1; $j <=12 ; $j++) { 
              $tabla .='<td>'.$matriz[$j].'</td>';
            }
        $tabla .='</tr>';
      $tabla .='</table>';

      return $tabla;
    }

    /*------------------------- VALIDA TEMPORALIDAD PRODUCTOS TERMINAL DE CORTO PLAZO ---------------------*/
    function valida_ptcplazo_temporalidad(){ 
       if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('ptm_id', 'Id Producto terminal de corto plazo ', 'required|trim');
          $this->form_validation->set_rules('rm_id', 'Id Resultado de Mediano Plazo Programado', 'required|trim');
        //  $this->form_validation->set_rules('poa_id', 'Id Poa', 'required|trim');
        //  $this->form_validation->set_rules('acc_id', 'Accion Id', 'required|trim');
        //  $this->form_validation->set_rules('ptmp_id', 'Ptmp Id', 'required|trim');
        //  $this->form_validation->set_rules('g_id', 'Gestion programado', 'required|trim');

          if ($this->form_validation->run()){
            for ($i=1; $i <=12 ; $i++) { 
              $mes[$i]='m'.$i.'';
            }

              $this->model_mestrategico->delete_prog_pt_cplazo($this->input->post('ptmp_id')); //// Eliminando Programado corto plazo

              for($i=1;$i<=12;$i++){
                if($this->input->post($mes[$i])!=0){
                  $data_to_store = array( 
                  'ptmp_id' => $this->input->post('ptmp_id'),
                  'm_id' => $i,
                  'ptcp_prog' => $this->input->post($mes[$i]),
                  );
                  $this->db->insert('_pterminal_cplazo_programado', $this->security->xss_clean($data_to_store));
                }
              }

              if(count($this->model_mestrategico->get_pterminal_cplazo_programado($this->input->post('ptmp_id')))!=0){
                $this->session->set_flashdata('success','LA TEMPORALIDAD SE REGISTRO CORRECTAMENTE');
                redirect(site_url("").'/prog/pterminal_cp/'.$this->input->post('poa_id').'/'.$this->input->post('rm_id'));
              }
              else{
                $this->session->set_flashdata('danger','ERROR AL REGISTRAR');
                redirect(site_url("").'/prog/temporalidad_pt_cplazo/'.$this->input->post('poa_id').'/'.$this->input->post('ptm_id'));
              }
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR');  
            redirect(site_url("").'/prog/temporalidad_pt_cplazo/'.$this->input->post('poa_id').'/'.$this->input->post('ptm_id'));
          }
      }
    }

    /*------------------------- LISTA DE PRODUCTOS DE MEDIANO PLAZO --------------------*/
    public function mis_pterminales_cp($poa_id,$rm_id){
      $pterminal = $this->model_mestrategico->list_pterminal_cplazo($rm_id); /// PRODUCTO TERMINAL DE MEDIANO PLAZO
      $dato_poa = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $configuracion=$this->model_proyecto->configuracion();

      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>PRODUCTOS TERMINALES DE CORTO PLAZO</strong></h2>  
                    </header>
                <div>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th>NRO</th>
                          <th></th>
                          <th>PRODUCTO TERMINAL</th>
                          <th>TIPO DE INDICADOR</th>
                          <th>INDICADOR</th>
                          <th>LINEA BASE</th>
                          <th>META</th>
                          <th>FUENTE DE VERIFICACI&Oacute;N</th>
                          <th>PROG. GESTI&Oacute;N '.$this->gestion.'</th>
                          <th>TEMPORALIDAD '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($pterminal  as $row){
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                              $tabla .='<a href="'.site_url("").'/prog/temporalidad_pt_cplazo/'.$dato_poa[0]['poa_id'].'/'.$row['ptm_id'].'" title="PROGRAMAR TEMPORALIDAD DE PRODUCTO TERMINAL DE CORTO PLAZO"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/><br>PROGRAMAR<br>TEMPORALIDAD</a><br>';
                            $tabla .='</td>';
                            $tabla .='<td>'.$row['ptm_producto'].'</td>';
                            $tabla .='<td>'.$row['indi_descripcion'].'</td>';
                            $tabla .='<td>'.$row['ptm_indicador'].'</td>';
                            $tabla .='<td>'.$row['ptm_linea_base'].'</td>';
                            $tabla .='<td>'.$row['ptm_meta'].'</td>';
                            $tabla .='<td>'.$row['ptm_fuente_verificacion'].'</td>';
                            $tabla .='<td>'.$row['ptmp_prog'].'%</td>';
                            $tabla .='<td>'.$this->tabla_temporalidad($this->temporalidad($row['ptm_id'])).'</td>';
                          $tabla .='</tr>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';

      return $tabla;
    }

  /*======================================================================================================================*/

   /*-------------------------------- REPORTE PRODUCTOS TERMINAL DE CORTO PLAZO -----------------------------*/
    public function reporte_pterminal_corto_plazo($poa_id,$rm_id){
      $html = $this->list_pterminal_corto_plazo($poa_id,$rm_id);// Lista de Resultados de Corto Plazo

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("PRODUCTOS TERMINAL.pdf", array("Attachment" => false));
    }

    function list_pterminal_corto_plazo($poa_id,$rm_id){
      $gestion = $this->session->userdata('gestion');
      $configuracion=$this->model_proyecto->configuracion();
      $html = '
      <html>
        <head>' . $this->estilo_vertical() . '
         <style>
           @page { margin: 130px 20px; }
           #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
           #footer { position: fixed; left: 0px; bottom: -125px; right: 0px; height: 110px;}
           #footer .page:after { content: counter(page, upper-roman); }
         </style>
        <body>
         <div id="header">
              <div class="verde"></div>
              <div class="blanco"></div>
              <table width="100%">
                  <tr>
                      <td width=20%; text-align:center;"">
                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                      </td>
                      <td width=60%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                          <b>REPORTE : </b>PRODUCTOS TERMINALES DE CORTO PLAZO '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'<br>
                          </FONT>
                      </td>
                      <td width=20%; text-align:center;"">
                      </td>
                  </tr>
              </table>
         </div>
         <div id="footer">
           <table border="0" cellpadding="0" cellspacing="0" class="tabla">
              <tr class="modo1" bgcolor=#DDDEDE>
                  <td width=33%;>Jefatura de Unidad o Area / Direcci&oacute;n de Establecimiento / Responsable de Area Regionales / Administraci&oacute;n Central</td>
                  <td width=33%;>Jefaturas de Departamento / Servicios Generales Regional / Medica Regional</td>
                  <td width=33%;>Gerencia General / Gerencias de Area /Administraci&oacute;n Regional</td>
              </tr>
              <tr class="modo1">
                  <td><br><br><br><br><br><br><br></td>
                  <td><br><br><br><br><br><br><br></td>
                  <td><br><br><br><br><br><br><br></td>
              </tr>
              <tr>
                  <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td><p class="page">Pagina </p></td>
              </tr>
          </table>
         </div>
         <div id="content">
           <p><div>'.$this->pterminal_cplazo($poa_id,$rm_id).'</div></p>
         </div>
       </body>
       </html>';
      return $html;
    }

    public function pterminal_cplazo($poa_id,$rm_id){
      $pterminal = $this->model_mestrategico->list_pterminal_cplazo($rm_id); /// PRODUCTOS TERMINALES DE MEDIANO PLAZO
      $resultados = $this->model_mestrategico->get_resultado_mplazo($rm_id); /// RESULTADO DE MEDIANO PLAZO
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($resultados[0]['acc_id']);  /// ACCIONES ESTRATEGICAS
      $pdes = $this->model_proyecto->datos_pedes($acciones[0]['pdes_id']); //// PEDES
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']);  /// OBJETIVOS
      $configuracion=$this->model_resultado->configuracion(); /// Configuracion
      
      $tabla = '';
        $tabla .= '
          <div class="mv" style="text-align:justify">
              <b>OBJETIVO ESTRAT&Eacute;GICO: </b>'.$objetivos[0]['obj_descripcion'].'
          </div>
          <div class="mv" style="text-align:justify">
              <b>ACCI&Oacute;N ESTRAT&Eacute;GICA: </b>'.$acciones[0]['acc_descripcion'].'
          </div>
          <div class="mv" style="text-align:justify">
            <b>VINCULACI&Oacute;N AL PEDES</b><br>
              <ul class="list-group">
                <li class="list-group-item"><b>PILAR : </b> '.$pdes[0]['id1'] . ' - ' . $pdes[0]['pilar'].'</li>
                <li class="list-group-item"><b>META : </b> '.$pdes[0]['id2'] . ' - ' . $pdes[0]['meta'].'</li>
                <li class="list-group-item"><b>RESULTADO : </b> '.$pdes[0]['id3'] . ' - ' . $pdes[0]['resultado'].'</li>
                <li class="list-group-item"><b>ACCI&Oacute;N : </b> '.$pdes[0]['id4'] . ' - ' . $pdes[0]['accion'].'</li>
              </ul>
          </div>
          <div class="mv" style="text-align:justify">
              <b>RESULTADO DE CORTO PLAZO: </b>'.$resultados[0]['rm_resultado'].'
          </div><br>
          <div class="mv" style="text-align:justify">
              <b>PRODUCTOS TERMINALES DE CORTO PLAZO: </b>'.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'
          </div>';
        if(count($pterminal)!=0){
            $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro</th>';
                  $tabla.='<th style="width:10%;">PRODUCTOS TERMINALES</th>';
                  $tabla.='<th style="width:5%;">TIPO DE INDICADOR</th>';
                  $tabla.='<th style="width:7%;">INDICADOR</th>';
                  $tabla.='<th style="width:5%;">LINEA BASE</th>';
                  $tabla.='<th style="width:5%;">META</th>';
                  $tabla.='<th style="width:7%;">FUENTE DE VERIFICACI&Oacute;N</th>';
                  $tabla.='<th style="width:10%;">RESPONSABLE</th>';
                  $tabla.='<th style="width:5%;">PROG. GESTI&Oacute;N '.$this->gestion.'</th>';
                  $tabla.='<th style="width:30%;">TEMPORALIDAD</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro_pt=0;
                foreach($pterminal as $row){
                $nro_pt++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td>'.$nro_pt.'</td>';
                  $tabla.='<td>'.$row['ptm_producto'].'</td>';
                  $tabla .='<td>'.$row['indi_descripcion'].'</td>';
                  $tabla .='<td>'.$row['ptm_indicador'].'</td>';
                  $tabla .='<td>'.$row['ptm_linea_base'].'</td>';
                  $tabla .='<td>'.$row['ptm_meta'].'</td>';
                  $tabla .='<td>'.$row['ptm_fuente_verificacion'].'</td>';
                  $tabla .='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                  $tabla .='<td>'.$row['ptmp_prog'].'%</td>';
                  $tabla .='<td>'.$this->tabla_temporalidad($this->temporalidad($row['ptm_id'])).'</td>';
                $tabla.='</tr>';
                }
                $tabla.='</tbody>';
            $tabla.='</table>';
          }
          
      return $tabla;
    }
  function estilo_vertical(){
      $estilo_vertical = '<style>
      body{
          font-family: sans-serif;
          }
      table{
          font-size: 8px;
          width: 100%;
          background-color:#fff;
      }
      .mv{font-size:10px;}
      .verde{ width:100%; height:5px; background-color:#1c7368;}
      .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      .siipp{width:120px;}

      .titulo_pdf {
          text-align: left;
          font-size: 8px;
      }
      .tabla {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 8px;
      width: 100%;

      }
      .tabla th {
      padding: 2px;
      font-size: 6px;
      background-color: #1c7368;
      background-repeat: repeat-x;
      color: #FFFFFF;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #558FA6;
      border-bottom-color: #558FA6;
      text-transform: uppercase;
      }
      .tabla .modo1 {
      font-size: 6px;
      font-weight:bold;
     
      background-image: url(fondo_tr01.png);
      background-repeat: repeat-x;
      color: #34484E;
     
      }
      .tabla .modo1 td {
      padding: 1px;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #A4C4D0;
      border-bottom-color: #A4C4D0;
      }
    </style>';
    return $estilo_vertical;
  }

  public function get_mes($mes_id)
  {
    $mes[1]='ENERO';
    $mes[2]='FEBRERO';
    $mes[3]='MARZO';
    $mes[4]='ABRIL';
    $mes[5]='MAYO';
    $mes[6]='JUNIO';
    $mes[7]='JULIO';
    $mes[8]='AGOSTO';
    $mes[9]='SEPTIEMBRE';
    $mes[10]='OCTUBRE';
    $mes[11]='NOVIEMBRE';
    $mes[12]='DICIEMBRE';

    $dias[1]='31';
    $dias[2]='28';
    $dias[3]='31';
    $dias[4]='30';
    $dias[5]='31';
    $dias[6]='30';
    $dias[7]='31';
    $dias[8]='31';
    $dias[9]='30';
    $dias[10]='31';
    $dias[11]='30';
    $dias[12]='31';

    $valor[1]=$mes[$mes_id];
    $valor[2]=$dias[$mes_id];

    return $valor;
  }

    /*------------------------------------- MENU -----------------------------------*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++){
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

    /*------------------------- COMBO RESPONSABLES ----------------------*/
    public function combo_funcionario_unidad_organizacional($accion='') 
    { 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'unidad':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT u.*
          from funcionario f
          Inner Join unidadorganizacional as u On u."uni_id"=f."uni_id"
          where  f."fun_id"='.$id_pais.'');
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }

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

    function rolfunn($tp_rol){
      $valor=false;
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$tp_rol);
      if(count($data)!=0){
        $valor=true;
      }
      return $valor;
    }
}