<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cpterminal extends CI_Controller {
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
    
    /*------------------- LISTA PRODUCTOS TERMINALES DE MEDIANO PLAZO ---------------------*/
    public function list_pterminal_mp($rm_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['resultado']=$this->model_mestrategico->get_resultado_mplazo($rm_id);

      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['resultado'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['pdes'] = $this->model_proyecto->datos_pedes($data['accion_estrategica'][0]['pdes_id']);
      
      $data['pterminal_mp']=$this->mis_pterminales_mp($rm_id);
      $this->load->view('admin/mestrategico/pterminal_mplazo/list_pterminal', $data);
    }

    /*------------------------- NUEVO RESULTADOS MEDIANO PLAZO ---------------------*/
    public function new_pterminal_mplazo($rm_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['resultado']=$this->model_mestrategico->get_resultado_mplazo($rm_id);
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['resultado'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['pdes'] = $this->model_proyecto->datos_pedes($data['accion_estrategica'][0]['pdes_id']);
      
      $this->load->view('admin/mestrategico/pterminal_mplazo/new_pterminal', $data);
    }

    /*------------------------- UPDATE PRODUCTO TERMINAL MEDIANO PLAZO ---------------------*/
    public function update_pterminal_mediano_plazo($ptm_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['pterminal']=$this->model_mestrategico->get_pterminal_mplazo($ptm_id);
      $data['unidad'] = $this->model_proyecto->get_unidad($data['pterminal'][0]['uni_id']);//get unidad
     
      $data['temporalidad']=$this->temporalidad($ptm_id);
      $this->load->view('admin/mestrategico/pterminal_mplazo/update_pterminal', $data);
    }

    function temporalidad($ptm_id){
      $pterminal=$this->model_mestrategico->get_pterminal_mplazo($ptm_id); 
      $programado=$this->model_mestrategico->get_pterminal_mplazo_programado($ptm_id);
      $configuracion=$this->model_proyecto->configuracion_session();
      $tabla = '';
      $nro=0;
      foreach($programado as $row) {
        $nro++;
        $matriz [1][$nro]=$row['g_id'];
        $matriz [2][$nro]=$row['ptmp_prog'];
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
        $matriz_r[3][$j]=$pa+$pterminal[0]['ptm_linea_base'];
        if($pterminal[0]['ptm_meta']!=0){
          $matriz_r[4][$j]=round(((($pa+$pterminal[0]['ptm_linea_base'])/$pterminal[0]['ptm_meta'])*100),2);
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
    /*------------------------ VALIDA RESULTADO DE MEDIANO PLAZO -----------------------*/
    function valida_pterminal_mediano_plazo(){ 
       if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('rm_id', 'Id del resultado de mediano plazo', 'required|trim');
          $this->form_validation->set_rules('pterminal', 'Producto terminal de Mediano Plazo', 'required|trim');
          $this->form_validation->set_rules('tipo_i', 'Tipo de Indicador', 'required|trim');

          $conf=$this->model_proyecto->configuracion_session();
          if ($this->form_validation->run()){
            $r[1]='g1';
            $r[2]='g2';
            $r[3]='g3';
            $r[4]='g4';
            $r[5]='g5';

            $query=$this->db->query('set datestyle to DMY');
            $data_to_store = array( 
              'rm_id' => $this->input->post('rm_id'),
              'ptm_codigo' => 'PTC - '.($conf[0]['conf_producto_terminal']+1).'',
              'ptm_producto' => strtoupper($this->input->post('pterminal')),
              'indi_id' => $this->input->post('tipo_i'),
              'ptm_indicador' => strtoupper($this->input->post('indicador')),
              'ptm_formula' => strtoupper($this->input->post('formula')),
              'ptm_linea_base' => $this->input->post('lb'),
              'ptm_meta' => $this->input->post('met'),
              'ptm_denominador' => $this->input->post('den'),
              'ptm_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
              'ptm_ponderacion' => $this->input->post('pn_cion'),
              'ptm_supuestos' => strtoupper($this->input->post('supuestos')),
              'ptm_casos_favorables' => strtoupper($this->input->post('c_a')),
              'ptm_casos_desfavorables' => strtoupper($this->input->post('c_b')),
              'gestion_desde' => $conf[0]['conf_gestion_desde'],
              'gestion_hasta' => $conf[0]['conf_gestion_hasta'],
              'resp_id' => $this->input->post('fun_id'),
              'uni_id' => $this->input->post('uni_id'),
              'fun_id' => $this->session->userdata("fun_id"),
              );
              $this->db->insert('_pterminal_mplazo', $data_to_store);
              $ptm_id = $this->db->insert_id();

              $update_conf = array('conf_producto_terminal' => ($conf[0]['conf_producto_terminal']+1));
              $this->db->where('ide', $this->session->userdata("gestion"));
              $this->db->update('configuracion', $update_conf);

              $g1=$conf[0]['conf_gestion_desde'];
              for($i=1;$i<=5;$i++){
                if($this->input->post($r[$i])!=0){
                  $data_to_store2 = array( 
                  'ptm_id' => $ptm_id,
                  'g_id' => $g1,
                  'ptmp_prog' => $this->input->post($r[$i]),
                  );
                  $this->db->insert('_pterminal_mplazo_programado', $this->security->xss_clean($data_to_store2));
                }
                $g1++;
              }

              if(count($this->model_mestrategico->get_pterminal_mplazo($ptm_id,$this->input->post('rm_id')))==1){
                $this->session->set_flashdata('success','EL PRODUCTO SE REGISTRO CORRECTAMENTE');
              }
              else{
                $this->session->set_flashdata('danger','ERROR AL REGISTRAR PRODUCTO TERMINAL');
              }

              redirect(site_url("").'/me/pterminales_mp/'.$this->input->post('rm_id'));
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR');  
            redirect(site_url("").'/me/new_ptmplazo/'.$this->input->post('rm_id'));
          }
      }
    }


    /*------------------------ VALIDA UPDATE PRODUCTO TERMINAL DE MEDIANO PLAZO -----------------------*/
    function valida_update_pterminal_mediano_plazo(){ 
       if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('ptm_id', 'Id del Producto Terminal ', 'required|trim');
          $this->form_validation->set_rules('pterminal', 'Producto Terminal de Mediano Plazo', 'required|trim');
          $this->form_validation->set_rules('tipo_i', 'Tipo de Indicador', 'required|trim');
          $this->form_validation->set_rules('fun_id', 'Id del responsable ', 'required|trim');
          $this->form_validation->set_rules('uni_id', 'Unidad Responsable', 'required|trim');

          $conf=$this->model_proyecto->configuracion_session();
          if ($this->form_validation->run()){

              $r[1]='g1';
              $r[2]='g2';
              $r[3]='g3';
              $r[4]='g4';
              $r[5]='g5';

              $query=$this->db->query('set datestyle to DMY');
              $update_rmp = array( 
                'ptm_producto' => strtoupper($this->input->post('pterminal')),
                'indi_id' => $this->input->post('tipo_i'),
                'ptm_indicador' => strtoupper($this->input->post('indicador')),
                'ptm_formula' => strtoupper($this->input->post('formula')),
                'ptm_linea_base' => $this->input->post('lb'),
                'ptm_meta' => $this->input->post('met'),
                'ptm_denominador' => $this->input->post('den'),
                'ptm_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
                'ptm_ponderacion' => $this->input->post('pn_cion'),
                'ptm_supuestos' => strtoupper($this->input->post('supuestos')),
                'ptm_casos_favorables' => strtoupper($this->input->post('c_a')),
                'ptm_casos_desfavorables' => strtoupper($this->input->post('c_b')),
                'resp_id' => $this->input->post('fun_id'),
                'uni_id' => $this->input->post('uni_id'),
                'fun_id' => $this->session->userdata("fun_id"),
                'ptm_estado' => 2
                );

              $this->db->where('ptm_id', $this->input->post('ptm_id'));
              $this->db->update('_pterminal_mplazo', $this->security->xss_clean($update_rmp));


                $this->model_mestrategico->delete_prog_pt($this->input->post('ptm_id')); //// Eliminando Programado Producto Terminal
                $g1=$conf[0]['conf_gestion_desde'];
                for($i=1;$i<=5;$i++){
                  if($this->input->post($r[$i])!=0){
                    $data_to_store2 = array( 
                    'ptm_id' => $this->input->post('ptm_id'),
                    'g_id' => $g1,
                    'ptmp_prog' => $this->input->post($r[$i]),
                    );
                    $this->db->insert('_pterminal_mplazo_programado', $this->security->xss_clean($data_to_store2));
                  }
                  $g1++;
                }

                $pterminal=$this->model_mestrategico->get_pterminal_mplazo($this->input->post('ptm_id'));

                if($pterminal[0]['ptm_estado']==2){
                  $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE');
                }
                else{
                  $this->session->set_flashdata('danger','ERROR AL MODIFICAR');
                }

              redirect(site_url("").'/me/pterminales_mp/'.$this->input->post('rm_id'));
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR');  
            redirect(site_url("").'/me/update_pterminal_mp/'.$this->input->post('ptm_id'));
          }
      }
    }


    /*------------------------- LISTA DE PRODUCTOS DE MEDIANO PLAZO --------------------*/
    public function mis_pterminales_mp($rm_id){
      $pterminal = $this->model_mestrategico->list_pterminal_mplazo($rm_id); /// PRODUCTO TERMINAL DE MEDIANO PLAZO
      
      $configuracion=$this->model_proyecto->configuracion_session();

      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>PRODUCTOS TERMINALES DE MEDIANO PLAZO</strong></h2>  
                    </header>
                <div>
                  <a href="'.site_url("").'/me/new_ptmplazo/'.$rm_id.'" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - PRODUCTO TERMINAL A MEDIANO PLAZO">NUEVO REGISTRO</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th>NRO</th>
                          <th>M/E</th>
                          <th>PRODUCTO TERMINAL</th>
                          <th>TIPO DE INDICADOR</th>
                          <th>INDICADOR</th>
                          <th>LINEA BASE</th>
                          <th>META</th>
                          <th>FUENTE DE VERIFICACI&Oacute;N</th>
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
                              $tabla .='<a href="'.site_url("").'/me/update_pterminal_mp/'.$row['ptm_id'].'" title="MODIFICAR PRODUCTO TERMINAL DE MEDIANO PLAZO"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/><br>MODIFICAR</a><br>';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR PRODUCTO TERMINAL DE MEDIANO PLAZO"  name="'.$row['ptm_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/><br>ELIMINAR</a><br>';
                              $tabla .='</td>';
                            $tabla .='<td>'.$row['ptm_producto'].'</td>';
                            $tabla .='<td>'.$row['indi_descripcion'].'</td>';
                            $tabla .='<td>'.$row['ptm_indicador'].'</td>';
                            $tabla .='<td>'.$row['ptm_linea_base'].'</td>';
                            $tabla .='<td>'.$row['ptm_meta'].'</td>';
                            $tabla .='<td>'.$row['ptm_fuente_verificacion'].'</td>';
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
    /*-------------------- GET OBJETIVO ESTRATEGICO -----------------------*/
    public function get_objetivos_estrategicos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $obj_id = $post['obj_id'];
        $obj_id = $this->security->xss_clean($obj_id);

        $dato_obj = $this->model_mestrategico->get_objetivos_estrategicos($obj_id);
        //caso para modificar el codigo de proyecto y actividades
        foreach($dato_obj as $row){
            $result = array(
              'codigo' => $row['obj_codigo'],
              "descripcion" =>$row['obj_descripcion']
            );
        }
        echo json_encode($result);
      }else{
          show_404();
      }
    }
  /*======================================================================================================================*/

   /*-------------------------------- REPORTE PRODUCTOS TERMINAL DE MEDIANO PLAZO -----------------------------*/
    public function reporte_pterminal_mediano_plazo($rm_id){
      $html = $this->list_pterminal_mediano_plazo($rm_id);// Lista de Resultados de Mediano Plazo

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("PRODUCTOS TERMINAL.pdf", array("Attachment" => false));
    }

    function list_pterminal_mediano_plazo($rm_id){
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
                          <b>REPORTE : </b>PRODUCTOS TERMINALES DE MEDIANO PLAZO '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'<br>
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
           <p><div>'.$this->pterminal_mplazo($rm_id).'</div></p>
         </div>
       </body>
       </html>';
      return $html;
    }

    public function pterminal_mplazo($rm_id){
      $pterminal = $this->model_mestrategico->list_pterminal_mplazo($rm_id); /// PRODUCTOS TERMINALES DE MEDIANO PLAZO
      $resultados = $this->model_mestrategico->get_resultado_mplazo($rm_id); /// RESULTADO DE MEDIANO PLAZO
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($resultados[0]['acc_id']);  /// ACCIONES ESTRATEGICAS
      $pdes = $this->model_proyecto->datos_pedes($acciones[0]['pdes_id']); //// PEDES
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
              <b>RESULTADO DE MEDIANO PLAZO: </b>'.$resultados[0]['rm_resultado'].'
          </div><br>
          <div class="mv" style="text-align:justify">
              <b>PRODUCTOS TERMINALES DE MEDIANO PLAZO: </b>'.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'
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

  public function get_mes($mes_id){
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