<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cresultado_mplazo extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');  
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
          $this->load->library('pdf');
          $this->load->library('pdf2');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('resultados/model_resultado');
          $this->load->model('mestrategico/model_mestrategico');
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
    
    /*------------------------- LISTA RESULTADOS MEDIANO PLAZO ---------------------*/
    public function list_resultado_mediano_plazo($acc_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['pdes'] = $this->model_proyecto->datos_pedes($data['accion_estrategica'][0]['pdes_id']);
      $data['resultado_mplazo']=$this->mis_resultados_mplazo($acc_id);
      $data['list'] = $this->model_mestrategico->list_resultados_mplazo($acc_id);
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $this->load->view('admin/mestrategico/resultado_mplazo/list_resultados', $data);
    }

    /*------------------------- NUEVO RESULTADOS MEDIANO PLAZO ---------------------*/
    public function new_resultado_mediano_plazo($acc_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['pdes'] = $this->model_proyecto->datos_pedes($data['accion_estrategica'][0]['pdes_id']);
      
      $this->load->view('admin/mestrategico/resultado_mplazo/new_resultado', $data);
    }

    /*------------------------- UPDATE RESULTADOS MEDIANO PLAZO ---------------------*/
    public function update_resultado_mediano_plazo($rm_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['resultado']=$this->model_mestrategico->get_resultado_mplazo($rm_id);
      $data['pdes'] = $this->model_proyecto->datos_pedes($data['resultado'][0]['pdes_id']);
      $data['unidad'] = $this->model_proyecto->get_unidad($data['resultado'][0]['uni_id']);//get unidad
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['resultado'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);

      $data['temporalidad']=$this->temporalidad($rm_id);
    //  echo $data['temporalidad'][2][1].'-'.$data['temporalidad'][2][2].'-'.$data['temporalidad'][2][3].'-'.$data['temporalidad'][2][4].'-'.$data['temporalidad'][2][5];
      $this->load->view('admin/mestrategico/resultado_mplazo/update_resultado', $data);
    }

    function temporalidad($rm_id){
      $resultado=$this->model_mestrategico->get_resultado_mplazo($rm_id); 
      $programado=$this->model_mestrategico->get_resultado_mplazo_programado($rm_id);
      $configuracion=$this->model_proyecto->configuracion_session();
      $tabla = '';
      $nro=0;
      foreach($programado as $row) {
        $nro++;
        $matriz [1][$nro]=$row['g_id'];
        $matriz [2][$nro]=$row['rmp_prog'];
      }

      $g=$configuracion[0]['conf_gestion_desde'];
      for($j = 1; $j<=5; $j++){
        $matriz_r[1][$j]=$g;
        $matriz_r[2][$j]='0';  //// P
        $matriz_r[3][$j]='0';  //// PA
        $matriz_r[4][$j]='0';  //// %PA
        $g++;
      }

      for($i = 1 ;$i<=$nro ;$i++){
        for($j = 1 ;$j<=5 ;$j++){
          if($matriz[1][$i]==$matriz_r[1][$j]){
              $matriz_r[2][$j]=round($matriz[2][$i],2);
          }
        }
      }

      $pa=0;
      for($j = 1 ;$j<=5 ;$j++){
        $pa=$pa+$matriz_r[2][$j];
        $matriz_r[3][$j]=$pa+$resultado[0]['rm_linea_base'];
        if($resultado[0]['rm_meta']!=0){
          $matriz_r[4][$j]=round(((($pa+$resultado[0]['rm_linea_base'])/$resultado[0]['rm_meta'])*100),2);
        }
      }
      return $matriz_r;
    }

    function tabla_temporalidad($matriz){ 
      $tabla = '';
      $tabla .='<table class="table table-bordered">';
        $tabla .='<tr bgcolor=#1c7368>';
            $tabla .='<th></th>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<th><font color=white>'.$matriz[1][$j].'</font></th>';
          }
        $tabla .='</tr>';
        $tabla .='<tr>';
            $tabla .='<td>PROG.</td>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<td>'.$matriz[2][$j].'</td>';
          }
        $tabla .='</tr>';
        $tabla .='<tr>';
            $tabla .='<td>PROG. AC.</th>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<td>'.$matriz[3][$j].'</td>';
          }
        $tabla .='</tr>';
        $tabla .='<tr>';
            $tabla .='<td>%PROG. AC.</td>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<td>'.$matriz[4][$j].'%</td>';
          }
        $tabla .='</tr>';
      $tabla .='</table>';

      return $tabla;
    }

    /*---------- Valida Resultado Intermedio ------------*/
    public function valida_resultado_mediano_plazo(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $codigo = $this->security->xss_clean($post['codigo']);
          $descripcion = $this->security->xss_clean($post['descripcion']);
          $configuracion=$this->model_proyecto->configuracion_session();

          /*--------------- GUARDANDO OBJETIVO ESTRATEGICO ----------------*/
          $data_to_store = array(
              'acc_id' => $acc_id,
              'rm_codigo' => strtoupper($codigo),
              'rm_resultado' => strtoupper($descripcion),
              'gestion_desde' => $configuracion[0]['conf_gestion_desde'],
              'gestion_hasta' => $configuracion[0]['conf_gestion_hasta'],
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('_resultado_mplazo',$data_to_store);
            $rm_id=$this->db->insert_id();
          /*---------------------------------------------------------------*/

        if(count($this->model_mestrategico->get_resultado_mplazo($rm_id))==1){
          $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR');
        }

        redirect(site_url("").'/me/resultados_mplazo/'.$acc_id.'');

      } else {
          show_404();
      }
    }

    /*-------------- Update Resultado Intermedio ------------------*/
    public function valida_update_resultado_mediano_plazo(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $rm_id = $this->security->xss_clean($post['rm_id']);
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $codigo = $this->security->xss_clean($post['codigo']);
          $descripcion = $this->security->xss_clean($post['descripcion']);

         $update_form= array(
            'rm_codigo' => $codigo,
            'rm_resultado' => $descripcion,
            'fun_id' => $this->fun_id,
            'rm_estado' => 2
          );

        $this->db->where('rm_id', $rm_id);
        $this->db->update('_resultado_mplazo', $this->security->xss_clean($update_form));

        $rmplazo=$this->model_mestrategico->get_resultado_mplazo($rm_id);
          if($rmplazo[0]['rm_estado']==2){
            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR');
          }

        redirect(site_url("").'/me/resultados_mplazo/'.$acc_id);

      } else {
          show_404();
      }
    }

    /*------------------------- LISTA DE RESULTADOS DE MEDIANO PLAZO --------------------*/
    public function mis_resultados_mplazo($acc_id){
      $resultados = $this->model_mestrategico->list_resultados_mplazo($acc_id); /// RESULTADO DE MEDIANO PLAZO
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($acc_id);
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']);
      $configuracion=$this->model_proyecto->configuracion_session();
      $pdes = $this->model_proyecto->datos_pedes($acciones[0]['pdes_id']);
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>RESULTADOS INTERMEDIOS</strong></h2>  
                    </header>
                <div>
                  <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - RESULTADO INTERMEDIO">REGISTRO - RESULTADO INTERMEDIO</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th style="width:1%;">NRO</th>
                          <th style="width:2%;">M/E</th>
                          <th style="width:25%;">RESULTADOS INTERMEDIOS</th>
                          <th style="width:2%;">NUEVO INDICADOR</th>
                          <th style="width:70%;">INDICADORES DE PROCESO</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($resultados  as $row){
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff"  title="MODIFICAR RESULTADO INTERMEDIO" name="'.$row['rm_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR RESULTADO DE MEDIANO PLAZO"  name="'.$row['rm_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                            $tabla .='</td>';
                            $tabla .='<td>'.$row['rm_resultado'].'</td>';
                            $tabla .='<td align=center><a href="#" data-toggle="modal" data-target="#modal_add_rf" class="btn btn-xs add_rf"  title="AGREGAR INDICADOR" name="'.$row['rm_id'].'"><img src="'.base_url().'assets/ifinal/add.jpg" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td align=center>';
                              $indicadores = $this->model_mestrategico->get_list_indicadores($row['rm_id']); 
                              if(count($indicadores)!=0){
                                $tabla.='<table class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width:1%;">NRO</th>
                                            <th style="width:1%;"></th>
                                            <th style="width:2%;">C&Oacute;DIGO</th>
                                            <th style="width:15%;">INDICADOR DE PROCESO</th>
                                            <th style="width:3%;">LINEA BASE</th>
                                            <th style="width:3%;">META</th>';
                                            if($this->gestion<=2022){
                                              $tabla.='
                                              <th style="width:3%;">2016</th>
                                              <th style="width:3%;">2017</th>
                                              <th style="width:3%;">2018</th>
                                              <th style="width:3%;">2019</th>
                                              <th style="width:3%;">2020</th>';
                                            }
                                            else{
                                              $tabla.='
                                              <th style="width:3%;">2021</th>
                                              <th style="width:3%;">2022</th>
                                              <th style="width:3%;">2023</th>
                                              <th style="width:3%;">2024</th>
                                              <th style="width:3%;">2025</th>';
                                            }
                                            $tabla.='
                                          </tr>
                                        </thead>
                                        <tbody>';
                                        $nro_i=0;
                                        foreach($indicadores  as $rowi){
                                          $nro_i++;
                                          $tabla.='<tr>';
                                            $tabla.='
                                            <td title='.$rowi['ptm_id'].'>'.$nro_i.'</td>
                                            <td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_rf" class="btn btn-xs mod_rf"  title="MODIFICAR INDICADOR" name="'.$rowi['ptm_id'].'"><img src="'.base_url().'assets/img/mod_icon.png" WIDTH="35" HEIGHT="35"/></a></td>
                                            <td>'.$rowi['ptm_codigo'].'</td>
                                            <td>'.$rowi['ptm_indicador'].'</td>
                                            <td>'.$rowi['ptm_linea_base'].'</td>
                                            <td>'.$rowi['ptm_meta'].'</td>
                                            <td>'.round($rowi['mes1'],2).'</td>
                                            <td>'.round($rowi['mes2'],2).'</td>
                                            <td>'.round($rowi['mes3'],2).'</td>
                                            <td>'.round($rowi['mes4'],2).'</td>
                                            <td>'.round($rowi['mes5'],2).'</td>';
                                          $tabla.='</tr>';
                                        }
                                        $tabla.='
                                        </tbody>
                                      </table>';
                            $tabla .='</td>';
                            }
                            else{
                              $tabla .='<br>Sin Indicadores Registrados';
                            }
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

    /*-------------------- DELETE RESULTADO DE MEDIANO PLAZO -----------------------*/
    function delete_resultado_mplazo(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $rm_id = $this->security->xss_clean($post['rm_id']);
        
          $update_rmp = array(
            'rm_estado' => 3,
            'fun_id' => $this->fun_id
            );
          $this->db->where('rm_id', $rm_id);
          $this->db->update('_resultado_mplazo', $update_rmp);

          $resultado=$this->model_mestrategico->get_resultado_mplazo($rm_id);
          
          if($resultado[0]['rm_estado']==3){
            $result = array(
            'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
            'respuesta' => 'error'
            );
          }

          echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }

    /*-------------- GET RESULTADO INTERMEDIO ---------------*/
    public function get_resultado_intermedio(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $rm_id = $this->security->xss_clean($post['rm_id']);

        $resultado = $this->model_mestrategico->get_resultado_mplazo($rm_id);
        
        $result = array(
          'resultado' => $resultado
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }
  /*==========================================================*/

   /*-- INDICADOR --*/
   /*------ Adicionar Indicador ------*/
    function valida_add_indicador(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $rm_id = $this->security->xss_clean($post['rm_id']);
          //$codigo = $this->security->xss_clean($post['cod']);
          $indicador = $this->security->xss_clean($post['indicador']);
          $lbase = $this->security->xss_clean($post['lbase']);
          $meta = $this->security->xss_clean($post['meta']);
          $tp_indi = $this->security->xss_clean($post['tipo_i']);
          $conf=$this->model_proyecto->configuracion_session();

          $data_to_store = array( 
            'rm_id' => $rm_id,
            'ptm_codigo' => 0,
            'indi_id' => $tp_indi,
            'ptm_indicador' => strtoupper($indicador),
            'ptm_linea_base' => $lbase,
            'ptm_meta' => $meta,
            'indi_id' => $tp_indi,
            'gestion_desde' => $conf[0]['conf_gestion_desde'],
            'gestion_hasta' => $conf[0]['conf_gestion_hasta'],
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_pterminal_mplazo', $data_to_store);
          $ind_id = $this->db->insert_id();

          for ($i=$conf[0]['conf_gestion_desde']; $i <=$conf[0]['conf_gestion_hasta'] ; $i++) { 
            if($this->security->xss_clean($post[$i])!=0){
              $data_to_store2 = array( 
                'ptm_id' => $ind_id,
                'g_id' => $i,
                'ptmp_prog' => $this->security->xss_clean($post[$i]),
              );
              $this->db->insert('_pterminal_mplazo_programado', $data_to_store2);
            }
          }

          if(count($this->model_mestrategico->get_pterminal_mplazo($ind_id,$rm_id))==1){
            $this->session->set_flashdata('success','EL INDICADOR SE REGISTRO CORRECTAMENTE');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR INDICADOR');
          }

          redirect(site_url("").'/me/resultados_mplazo/'.$acc_id);
      } else {
          show_404();
      }
    }

    /*-------------- GET INDICADOR ---------------*/
    public function get_indicador(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ptm_id = $this->security->xss_clean($post['ptm_id']);

        $indicador = $this->model_mestrategico->get_indicador($ptm_id);
        
        $result = array(
          'indicador' => $indicador
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- Modificar Indicador ----*/
    function valida_update_indicador(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $ptm_id = $this->security->xss_clean($post['ptm_id']);
        //  $rf_id = $this->security->xss_clean($post['rf_id']);
         // $codigo = $this->security->xss_clean($post['mcod']);
          $indicador = $this->security->xss_clean($post['mindicador']);
          $lbase = $this->security->xss_clean($post['lbase']);
          $meta = $this->security->xss_clean($post['mmeta']);
          $tp_indi = $this->security->xss_clean($post['mtipo_i']);
          $conf=$this->model_proyecto->configuracion_session();

          $update_rf = array( 
            //'ptm_codigo' => $codigo,
            'ptm_indicador' => strtoupper($indicador),
            'ptm_meta' => $meta,
            'ptm_linea_base' => $lbase,
            'indi_id' => $tp_indi,
            'fun_id' => $this->fun_id,
            'ptm_estado' => 2
            );

          $this->db->where('ptm_id', $ptm_id);
          $this->db->update('_pterminal_mplazo', $update_rf);

          $this->db->where('ptm_id', $ptm_id);
          $this->db->delete('_pterminal_mplazo_programado');

          for ($i=$conf[0]['conf_gestion_desde']; $i <=$conf[0]['conf_gestion_hasta'] ; $i++) { 
            if($this->security->xss_clean($post[$i])!=0){
              $data_to_store2 = array( 
                'ptm_id' => $ptm_id,
                'g_id' => $i,
                'ptmp_prog' => $this->security->xss_clean($post[$i]),
              );
              $this->db->insert('_pterminal_mplazo_programado', $data_to_store2);
            }
          }

          $pterminal=$this->model_mestrategico->get_indicador($ptm_id);
          $res=$this->model_mestrategico->get_resultado_mplazo($pterminal[0]['rm_id']);
          if(count($pterminal)==1){
              $this->session->set_flashdata('success','Se Modifico correctamente !!!'); 
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR'); 
          }
          redirect(site_url("").'/me/resultados_mplazo/'.$res[0]['acc_id'].'');

      } else {
          show_404();
      }
    }

   /*-------------------------------- REPORTE RESULTADOS DE MEDIANO PLAZO -----------------------------*/
    public function reporte_resultado_mediano_plazo($acc_id){
      $html = $this->list_resultados_mediano_plazo($acc_id);// Lista de Resultados de Mediano Plazo

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("RESULTADOS DE MEDIANO PLAZO.pdf", array("Attachment" => false));
    }

    function list_resultados_mediano_plazo($acc_id){
      $gestion = $this->session->userdata('gestion');
      $configuracion=$this->model_proyecto->configuracion_session();
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
                          <b>REPORTE : </b>RESULTADOS DE MEDIANO PLAZO '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'<br>
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
           <p><div>'.$this->resultado_mplazo($acc_id).'</div></p>
         </div>
       </body>
       </html>';
      return $html;
    }

    public function resultado_mplazo($acc_id){
      $resultados = $this->model_mestrategico->list_resultados_mplazo($acc_id); /// RESULTADO DE MEDIANO PLAZO
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($acc_id);  /// ACCIONES
      $pdes = $this->model_proyecto->datos_pedes($acciones[0]['pdes_id']);
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']);  /// OBJETIVOS
      $configuracion=$this->model_resultado->configuracion_session(); /// Configuracion
      
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
              <b>RESULTADOS DE MEDIANO PLAZO: </b>'.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'
          </div>';
        if(count($resultados)!=0){
            $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro</th>';
                  $tabla.='<th style="width:10%;">RESULTADO DE MEDIANO PLAZO</th>';
                  $tabla.='<th style="width:5%;">TIPO DE INDICADOR</th>';
                  $tabla.='<th style="width:7%;">INDICADOR</th>';
                  $tabla.='<th style="width:5%;">LINEA BASE</th>';
                  $tabla.='<th style="width:5%;">META</th>';
                  $tabla.='<th style="width:7%;">FUENTE DE VERIFICACI&Oacute;N</th>';
                  $tabla.='<th style="width:10%;">RESPONSABLE</th>';
                  $tabla.='<th style="width:30%;">TEMPORALIDAD</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro_r=0;
                foreach($resultados as $row){
                $pdes=$this->model_proyecto->datos_pedes($row['pdes_id']);
                $nro_r++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td>'.$nro_r.'</td>';
                  $tabla.='<td>'.$row['rm_resultado'].'</td>';
                  $tabla .='<td>'.$row['indi_descripcion'].'</td>';
                  $tabla .='<td>'.$row['rm_indicador'].'</td>';
                  $tabla .='<td>'.$row['rm_linea_base'].'</td>';
                  $tabla .='<td>'.$row['rm_meta'].'</td>';
                  $tabla .='<td>'.$row['rm_fuente_verificacion'].'</td>';
                  $tabla .='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                  $tabla .='<td>'.$this->tabla_temporalidad($this->temporalidad($row['rm_id'])).'</td>';
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