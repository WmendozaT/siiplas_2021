<?php
class Consulta_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('gestion')!=null){
        $this->load->model('Users_model','',true);
          $this->load->model('menu_modelo');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('programacion/model_faseetapa');
          $this->load->model('programacion/model_producto');
          $this->load->model('programacion/model_componente');
          $this->load->model('mantenimiento/model_ptto_sigep');
          $this->gestion = $this->session->userData('gestion');
    }
    else{
        redirect('/','refresh');
    }
  }


/*------- menu Proyectos de Inversion -------*/
  public function menu_pi(){
    $data['menu']=$this->menu_regional();
    $this->load->view('admin/consultas_internas/vista_cns_proyectos', $data);
  }

  /// Menu Regional
  public function menu_regional(){
    $regionales=$this->model_proyecto->list_departamentos();
    $tabla='';
    $tabla.='
    <input name="base" type="hidden" value="'.base_url().'">
    <ul class="nav flex-column" id="nav_accordion">
      <li class="nav-item">
        <a class="nav-link" href="#"> Proyecto de Inversi&oacute;n / '.$this->gestion.' </a>
      </li>';

      foreach($regionales as $row){
        $regional=$this->model_proyecto->get_departamento($row['dep_id']);
        $proyectos=$this->model_proyecto->list_proy_inversion_regional($row['dep_id']); // Lista de Proyectos
        $tabla.='
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" id="reg'.$row['dep_id'].'" data-bs-target="#menu_item'.$row['dep_id'].'" href="#"> '.$row['dep_cod'].' - '.$row['dep_departamento'].' <i class="bi small bi-caret-down-fill"></i> </a>
          <ul id="menu_item'.$row['dep_id'].'" class="submenu collapse" data-bs-parent="#nav_accordion">';
            foreach($proyectos as $rowp){
              $tabla.='<li style="font-size:11px"><a class="nav-link" href="#" onclick="generar_reporte('.$rowp['proy_id'].');">'.$rowp['proyecto'].'</a></li>';
            }
            $tabla.='
          </ul>
        </li>';
      }
      $tabla.='
      <li class="nav-item">
        <a class="nav-link" href="#"> Other link </a>
      </li>
    </ul>';


    return $tabla;
  }

    /*------ GET CUADRO PROYECTO-----*/
  public function get_reporte_proyecto(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']);

      $tabla='<iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/reporte_ficha_tecnica_pi/'.$proy_id.'"></iframe>';

      $result = array(
        'respuesta' => 'correcto',
        'iframe' => $tabla,
      );
        
      echo json_encode($result);
    }else{
        show_404();
    }
  }
}