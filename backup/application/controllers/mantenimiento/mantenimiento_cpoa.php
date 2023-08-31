<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Mantenimiento_cpoa extends CI_Controller {  

  public function __construct ()
    {
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('mantenimiento/mpoa');
        $this->load->model('mantenimiento/mapertura_programatica');
        $this->load->model('mantenimiento/munidad_organizacional');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mestrategico/model_mestrategico');
        //$this->load->model('resultados/model_resultado');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        }else{
            redirect('/','refresh');
        }
    }

    /*------------- RED DE ACCIONES (PROGRAMACION) ----------------*/
    public function red_acciones(){
      //-----------------------------------------------------------------------
      $data['menu']=$this->menu(1);
      $data['lista_poa'] = $this->mpoa->lista_poa();
      $this->load->view('admin/red_objetivos/vred_acciones', $data);
    }

    //LISTA RED DE ACCIONES DE MEDIANO PLAZO PARA LA ASIGNACION A PROGRAMA - PROGRAMAS PADRES MANTENIMIENTO
    public function red_objetivos(){
      //-----------------------------------------------------------------------
      $enlaces=$this->menu_modelo->get_Modulos(9);
      $data['enlaces'] = $enlaces;
      for($i=0;$i<count($enlaces);$i++){
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }
      $data['subenlaces'] = $subenlaces;
      $data['lista_poa'] = $this->mpoa->lista_poa();
      $this->load->view('admin/mantenimiento/mnt_cpoa/vred_objetivos', $data);
    }

    /*------------- ASIGNAR ACCION DE MEDIANO PLAZO A CARPETA POA ----------------*/
    public function asignar_obj_poa($poa_id){
      $data['menu']=$this->menu(9);
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['acciones'] = $this->mis_acciones_mp($poa_id);

      $this->load->view('admin/mantenimiento/mnt_cpoa/vasignar_obje_poa', $data);
    }


    /*------------------------- LISTA DE MEDIANO PLAZO --------------------*/
    public function mis_acciones_mp($poa_id){
      $acciones_mp=$this->model_mestrategico->acciones_mediano_plazo(); //// Acciones de Mediano Plazo
      $configuracion=$this->model_proyecto->configuracion();
      $dato_poa = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>ACCIONES DE MEDIANO PLAZO : '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'</strong></h2>  
                    </header>
                <div>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th>NRO</th>
                          <th>A/D</th>
                          <th>ACCI&Oacute;N DE MEDIANO PLAZO</th>
                          <th>VINCULACI&Oacute;N AL PEDES</th>
                          <th>OBJETIVO ESTRATEGICO</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($acciones_mp  as $row){
                          $pdes=$this->model_proyecto->datos_pedes($row['pdes_id']);
                          $verif=$this->mpoa->verif_poaaccion($dato_poa[0]['poa_id'],$row['acc_id']);
                          if($verif==1){$color='#D8FAF';}else{$color='';}
                          $nro++;
                          $tabla .='<tr bgcolor='.$color.'>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                            if($this->session->userdata('rol_id')==1){
                              if($verif==1){
                                $tabla .='<button class="btn btn-labeled btn-danger quitar" name="'.$row['acc_id'].'" id="'.$dato_poa[0]['poa_id'].'" title="QUITAR ACCION DE MEDIANO PLAZO DE CARPETA POA">QUITAR</button>';
                              }
                              else{
                              $tabla .='<button class="btn btn-labeled btn-success asignar" name="'.$row['acc_id'].'" id="'.$dato_poa[0]['poa_id'] .'" title="ASIGNAR ACCION DE MEDIANO PLAZO A CARPETA POA">ASIGNAR</button><br>';
                              } 
                            }
                            $tabla .='</td>';
                            $tabla .='<td>'.$row['acc_descripcion'].'</td>';
                            $tabla .='<td>';
                              $tabla.=' <b>PILAR :</b> '.$pdes[0]['pilar'].'<br>
                              <b>META :</b> '.$pdes[0]['meta'].'<br>
                              <b>RESULTADO :</b> '.$pdes[0]['resultado'].'<br>
                              <b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'<br>';
                            $tabla .='</td>';
                            $tabla .='<td>'.$row['obj_descripcion'].'</td>';
                            $tabla .='<td>';
                            if($verif==1){ 
                              $tabla .='<br><img src="'.base_url().'assets/ifinal/ok.png" WIDTH="45" HEIGHT="35" title="RESULTADO ASIGNADO A CARPETA POA"/>';
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

    /*----------------------- ASIGNAR ACCION A CARPETA POA ------------------------*/
    function asignar_accion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $poa_id = $this->security->xss_clean($post['poa_id']);

          $this->db->where('poa_id', $poa_id);
          $this->db->where('acc_id', $acc_id);
          $this->db->delete('poa_accionmplazo');
          
         /*-------------------------------------------------------------*/
          $data = array(
                'poa_id' => $poa_id,
                'acc_id' => $acc_id,
            );
            $this->db->insert('poa_accionmplazo',$data);
          /*-------------------------------------------------------------*/

          $result = array(
            'respuesta' => 'correcto'
            );

          echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }

    /*----------------------- QUITAR ACCION DE CARPETA POA ------------------------*/
    function quitar_accion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $acc_id = $this->security->xss_clean($post['acc_id']);
          $poa_id = $this->security->xss_clean($post['poa_id']);

          $this->db->where('poa_id', $poa_id);
          $this->db->where('acc_id', $acc_id);
          $this->db->delete('poa_accionmplazo');
          
          $result = array(
            'respuesta' => 'correcto'
            );

          echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
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
}