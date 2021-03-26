<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cacciones_estrategicas extends CI_Controller {
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
    
    /*------------------------- LISTA ACCIONES ESTRATEGICAS ---------------------*/
    public function acciones_estrategicas($obj_id){
      $data['menu']=$this->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);
      $data['resultado_final']=$this->model_mestrategico->list_resultados_final($obj_id);
      $data['acciones_estrategicas']=$this->mis_acciones_estrategicas($obj_id);

      $this->load->view('admin/mestrategico/a_estrategicas/acciones_estrategicas', $data);
    }


    /*------------------------- LISTA DE ACCIONES ESTRATEGICAS --------------------*/
    public function mis_acciones_estrategicas($obj_id){
      $acciones = $this->model_mestrategico->list_acciones_estrategicas($obj_id); /// ACCIONES ESTRATEGICAS
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>'.count($acciones).'. ACCIONES ESTRAT&Eacute;GICAS</strong></h2>  
                    </header>
                <div>
                  <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - ACCIONES ESTRATEGICAS">NUEVO REGISTRO</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th>NRO</th>
                          <th>RESULTADOS DE MEDIANO PLAZO</th>
                          <th>OBJETIVOS DE GESTI&Oacute;N</th>
                          <th>C&Oacute;DIGO</th>
                          <th>DESCRIPCION</th>
                          <th>VINCULACI&Oacute;N AL PDES </th>
                          <th>MODIFICAR</th>
                          <th>ELIMINAR</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($acciones as $row){
                          $pdes=$this->model_proyecto->datos_pedes($row['pdes_id']);
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td title='.$row['acc_id'].'>'.$nro.'</td>';
                            $tabla .='<td align="center"><a href="'.site_url("").'/me/resultados_mplazo/'.$row['acc_id'].'" class="btn btn-default" title="RESULTADOS DE MEDIANO PLAZO"><img src="'.base_url().'assets/img/folder.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td align="center" bgcolor="#cef3ee"><a href="'.site_url("").'/me/objetivos_gestion/'.$row['acc_id'].'" class="btn btn-default" title="OBJETIVOS DE GESTI&Oacute;N"><img src="'.base_url().'assets/img/folder.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td>'.$row['acc_codigo'].'</td>';
                            $tabla .='<td>'.$row['acc_descripcion'].'</td>';
                            $tabla .='<td>';
                              $tabla.=' <b>PILAR :</b> '.$pdes[0]['pilar'].'<br>
                              <b>META :</b> '.$pdes[0]['meta'].'<br>
                              <b>RESULTADO :</b> '.$pdes[0]['resultado'].'<br>
                              <b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'<br>';
                            $tabla .='</td>';
                            $tabla .='<td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff"  title="MODIFICAR ACCI&Oacute;N ESTRATEGICA" name="'.$row['acc_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR ACCI&Oacute;N ESTRATEGICA"  name="'.$row['acc_id'].'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>';
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

    /*-------------- Valida Acciones Estrategicas ---------------------*/
    public function valida_acciones_estrategicas(){
      if ($this->input->post()) {
          $post = $this->input->post();

          $rf_id = $this->security->xss_clean($post['resf']);
          $codigo = $this->security->xss_clean($post['codigo']);
          $descripcion = $this->security->xss_clean($post['descripcion']);
          $obj_id = $this->security->xss_clean($post['obj_id']);
          $pedes1 = $this->security->xss_clean($post['pedes1']);  /// Pilar 
          $pedes2 = $this->security->xss_clean($post['pedes2']);  /// Meta
          $pedes3 = $this->security->xss_clean($post['pedes3']);  /// Resultado
          $pedes4 = $this->security->xss_clean($post['pedes4']);  /// Accion
          $configuracion=$this->model_proyecto->configuracion_session();

          if($pedes4==''){
            $pdes_id = $this->model_proyecto->get_id_pdes($pedes3);  // devuelve id pedes    
          }
          elseif ($pedes4!=''){
            $pdes_id = $this->model_proyecto->get_id_pdes($pedes4);  // devuelve id pedes  
          }

          $cod_ae = str_replace('.', '', $codigo);
          /*------- GUARDANDO ACCIONES ESTRATEGICAS --------*/
          $data_to_store = array(
            'rf_id' => $rf_id,
            'acc_codigo' => strtoupper($codigo),
            'obj_id' => $obj_id,
            'acc_descripcion' => strtoupper($descripcion),
            'pdes_id' => $pdes_id[0]['pdes_id'],
            'fun_id' => $this->fun_id,
            'g_id' => $this->gestion,
            'ae' => $cod_ae,
          );
          $this->db->insert('_acciones_estrategicas',$data_to_store);
          $acc_id=$this->db->insert_id();
          /*---------------------------------------------------------------*/

          if(count($this->model_mestrategico->get_acciones_estrategicas($acc_id))==1){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE');
            redirect(site_url("").'/me/acciones_estrategicas/'.$obj_id);
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR');
            redirect(site_url("").'/me/acciones_estrategicas/'.$obj_id);
          }

      } else {
          show_404();
      }
    }

    /*----------------------- Update Acciones Estrategicas -----------------------------*/
    public function update_acciones_estrategicas(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $rf_id = $this->security->xss_clean($post['rf_id']);
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $obj_id = $this->security->xss_clean($post['obj_id']);
          $codigo = $this->security->xss_clean($post['codigo']);
          $descripcion = $this->security->xss_clean($post['descripcion']);
          $pedes1 = $this->security->xss_clean($post['pdes1']);  /// Pilar 
          $pedes2 = $this->security->xss_clean($post['pdes2']);  /// Meta
          $pedes3 = $this->security->xss_clean($post['pdes3']);  /// Resultado
          $pedes4 = $this->security->xss_clean($post['pdes4']);  /// Accion

          $cod_ae = str_replace('.', '', $codigo);

          if($pedes4==''){
            $pdes_id = $this->model_proyecto->get_id_pdes($pedes3);  // devuelve id pedes    
          }
          elseif ($pedes4!=''){
            $pdes_id = $this->model_proyecto->get_id_pdes($pedes4);  // devuelve id pedes  
          }

         $update_form= array(
            'rf_id' => $rf_id,
            'acc_codigo' => strtoupper($codigo),
            'acc_descripcion' => strtoupper($descripcion),
            'fun_id' => $this->fun_id,
            'ae' => $cod_ae,
            'pdes_id' => $pdes_id[0]['pdes_id'],
            'acc_estado' => 2
          );

        $this->db->where('acc_id', $acc_id);
        $this->db->update('_acciones_estrategicas', $this->security->xss_clean($update_form));

        $form=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
        if($form[0]['acc_estado']==2){
          $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE');
          redirect(site_url("").'/me/acciones_estrategicas/'.$obj_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL MODIFICAR');
          redirect(site_url("").'/me/acciones_estrategicas/'.$obj_id);
        }

      } else {
          show_404();
      }
    }

    /*-------------------- DELETE ACCION ESTRATEGICA -----------------------*/
    function delete_acciones_estrategicas(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $acc_id = $this->security->xss_clean($post['acc_id']);

          $resultado=$this->model_mestrategico->list_resultados_mplazo($acc_id);
          // echo count($resultado);
           foreach($resultado as $rowr) {
            $pterminal=$this->model_mestrategico->list_pterminal_cplazo($rowr['rm_id']);
            foreach($pterminal as $rowt) {
              $group_productos=$this->model_mestrategico->group_pterminal($rowt['ptm_id']);
                foreach($group_productos as $rowpr) {
                  $list_prod=$this->model_mestrategico->list_vin_pterminal($rowpr['proy_id'],$rowt['ptm_id']);
                  foreach($list_prod as $rowprod) {
                    $update_prod= array(
                      'pt_id' => 0,
                      'estado' => 2
                    );
                  $this->db->where('prod_id', $rowprod['prod_id']);
                  $this->db->update('_productos', $this->security->xss_clean($update_prod));
                  }
                }

                $update_ptm= array(
                  'ptm_estado' => 3
                );
                $this->db->where('ptm_id', $rowt['ptm_id']);
                $this->db->update('_pterminal_mplazo', $this->security->xss_clean($update_ptm));
            }

              $update_rm= array(
                'rm_estado' => 3
              );
              $this->db->where('rm_id', $rowr['rm_id']);
              $this->db->update('_resultado_mplazo', $this->security->xss_clean($update_rm));
           }

            $update_am= array(
              'acc_estado' => 3
            );
            $this->db->where('acc_id', $acc_id);
            $this->db->update('_acciones_estrategicas', $this->security->xss_clean($update_am));


          $ae=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
          if($ae[0]['acc_estado']==3){
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

    /*-------------------- GET ACCIONES ESTRATEGICAS -----------------------*/
    public function get_acciones_estrategicas(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $acc_id = $post['acc_id'];
        $acc_id = $this->security->xss_clean($acc_id);

        $dato_acc = $this->model_mestrategico->get_acciones_estrategicas($acc_id);
        $dato_pdes = $this->model_proyecto->datos_pedes($dato_acc[0]['pdes_id']);

        $pdes_id1 = $this->model_proyecto->get_id_pdes($dato_pdes[0]['id1']);
        $pdes_id2 = $this->model_proyecto->get_id_pdes($dato_pdes[0]['id2']);
        $pdes_id3 = $this->model_proyecto->get_id_pdes($dato_pdes[0]['id3']);
        $pdes_id4 = $this->model_proyecto->get_id_pdes($dato_pdes[0]['id4']);

        $result = array(
          'rf_id' => $dato_acc[0]['rf_id'],
          'codigo' => $dato_acc[0]['acc_codigo'],
          'descripcion' =>$dato_acc[0]['acc_descripcion'],
          'pdes_id' =>$dato_acc[0]['pdes_id'],
          'id1' =>$dato_pdes[0]['id1'],
          'id2' =>$dato_pdes[0]['id2'],
          'id3' =>$dato_pdes[0]['id3'],
          'id4' =>$dato_pdes[0]['id4'],
        );

        echo json_encode($result);
      }else{
          show_404();
      }
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