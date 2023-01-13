<?php
class Consulta_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();

      $this->load->model('Users_model','',true);
      $this->load->model('menu_modelo');
      $this->load->model('reportes/model_pi/model_pinversion');
      $this->load->model('programacion/model_proyecto');
      $this->load->model('programacion/model_faseetapa');
      $this->load->model('programacion/model_producto');
      $this->load->model('programacion/model_componente');
      $this->load->model('mantenimiento/model_ptto_sigep');
      $this->load->model('mantenimiento/model_configuracion');
      $this->gestion = $this->Users_model->obtener_gestion()[0]['ide'];
      $this->mes = $this->mes_nombre();
      $this->entidad = $this->model_configuracion->get_configuracion()[0]['conf_nombre_entidad'];
  }


/*------- menu Proyectos de Inversion -------*/
  public function menu_pi(){
    $data['menu']=$this->menu_regional();
    $data['img']='<h2><img src="'.base_url().'assets/ifinal/cnslogo.png" class="img-responsive" style="width:100px; height:100px;text-align:center"/> CAJA NACIONAL DE SALUD </h2>';
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
        $regional=$this->model_pinversion->get_departamento($row['dep_id']);
        $proyectos=$this->model_pinversion->list_proy_inversion_regional($row['dep_id'],$this->gestion); // Lista de Proyectos
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

      $tabla='<iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/reporte_ficha_tecnica_pinversion/'.$proy_id.'"></iframe>';

      $result = array(
        'respuesta' => 'correcto',
        'iframe' => $tabla,
      );
        
      echo json_encode($result);
    }else{
        show_404();
    }
  }


  /*--- REPORTE FICHA TECNICA PROY INVERSION ---*/
  public function ficha_tecnica_pinversion($proy_id){
    //$regional=$this->model_proyecto->get_departamento($this->dep_id);
    $proyecto=$this->model_pinversion->get_pinversion($proy_id,$this->gestion);
    $data['titulo_pie_rep']='Ficha_Tecnica_PI'.strtoupper($proyecto[0]['proy']).' '.$this->gestion;
    $titulo_reporte='FICHA TÉCNICA';
    $data['cabecera']=$this->cabecera_ficha_tecnica($titulo_reporte); /// Cabecera ficha tecnica
    $data['pie']=$this->pie_ficha_tecnica(); /// Pie ficha tecnica
    $data['datos_proyecto']=$this->datos_proyecto_inversion($proyecto); /// Datos detalle 

    $this->load->view('admin/ejecucion_pi/reporte_ficha_tecnica_pi', $data);
  }

  /// Cabecera Reporte Ficha Tecnica
  public function cabecera_ficha_tecnica($titulo_reporte){
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr>
                    <td style="width:50%;height: 20%;font-size: 8px;font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</b></td>
                  </tr>
                </table>
            </td>
            <td style="width:30%; height: 2%; font-size: 8px;text-align:right;">
              '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px black; text-align: center;">
              <td style="width:10%; text-align:center;">
              </td>
              <td style="width:80%; height: 5%">
                  <table align="center" border="0" style="width:100%; text-align:center">
                      <tr style="font-size: 35px;font-family: Arial;">
                        <td style="height: 40%;"><b>'.$this->entidad.'</b></td>
                      </tr>
                      <tr style="font-size: 20px;font-family: Arial; text-align:center">
                        <td style="height: 5%;">PROYECTOS DE INVERSIÓN</td>
                      </tr>
                  </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>
      
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
         <tr>
            <td style="width:2%;"></td>
            <td style="width:96%;height: 1%;">
              <hr>
            </td>
            <td style="width:2%;"></td>
        </tr>
      </table>';

    return $tabla;
  }

  //// Pie Ficha Tecnica
  public function pie_ficha_tecnica(){ 
    $tabla='';
    $tabla.='
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
      <tr>
        <td style="width: 33%; height:18px;text-align: left">
          POA - '.$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa').'
        </td>
        <td style="width: 33%; text-align: center">
          '.$this->session->userdata('sistema').'
        </td>
        <td style="width: 33%; text-align: right">
          '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
        </td>
      </tr>
    </table>';

    return $tabla;
  }

   /// Datos Generales - Proyectos de Inversion
  public function datos_proyecto_inversion($proyecto){
    $imagen=$this->model_proyecto->get_img_ficha_tecnica($proyecto[0]['proy_id']);
    $tabla='';
     $tabla.='
      <table cellpadding="0" cellspacing="0" class="tabla" border=0 style="width:100%;text-align:center">
        <tbody>
          <tr style="font-family: Arial; font-size: 9.5px; text-align:center">
            <td style="width:75%; font-family: Arial; font-size: 15px;height:20px;"><b>'.$proyecto[0]['proyecto'].'</b></td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px; text-align:center">
            <td style="width:100%;text-align:center">';
              if(count($imagen)!=0){
                if($imagen[0]['tp']==1){
                  $tabla.='<img src="'.base_url().'fotos_proyectos/'.$imagen[0]['imagen'].'" class="img-responsive" style="width:450px; height:300px;"/>';
                }
                else{
                  $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:300px; height:200px;text-align:center"/>';
                }
              }
              else{
                $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:300px; height:200px;text-align:center"/>';
              }
            $tabla.='
            </td>
          </tr>
        </tbody>
      </table>

      <div style="height:20px;"><b>DATOS GENERALES</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr>
            <td style="width:25%; height:15px; font-family: Arial; font-size: 10px;height:20px;" bgcolor="#e8e7e7"><b>PROYECTO DE INVERSIÓN</b></td>
            <td style="width:75%; font-family: Arial; font-size: 9px;height:20px;">'.$proyecto[0]['proyecto'].'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>C&Oacute;DIGO SISIN</b></td>
            <td style="width:75%; font-size: 9px;">'.$proyecto[0]['proy'].'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>CATEGORIA PROGRAMATICA</b></td>
            <td style="width:75%; font-size: 9px;">'.$proyecto[0]['prog'].' '.$proyecto[0]['proy'].' 000</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>REGIONAL</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>DISTRITAL</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>COSTO TOTAL DEL PROYECTO</b></td>
            <td style="width:75%; font-size: 9px;">Bs. '.number_format($proyecto[0]['proy_ppto_total'], 2, ',', '.').'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FASE</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['pfec_descripcion']).'</td>
          </tr>
        </tbody>
       </table><br>
        <div style="height:20px;"><b>OBJETIVOS DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO GENERAL</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['proy_obj_general']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO ESPECIFICO</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['proy_obj_especifico']).'</td>
          </tr>
        </tbody>
       </table><br>
       <div style="height:20px;"><b>DETALLE DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>ESTADO DEL PROYECTO</b></td>
            <td style="width:55%; font-size: 9px;">'.strtoupper($proyecto[0]['ep_descripcion']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FÍSICO PROYECTO</b></td>
            <td style="width:75%; font-size: 9px;">'.round($proyecto[0]['avance_fisico'],2).' % &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; de fecha : '.date("d").'/'.date("m"). "/" . date("Y").'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FINANCIERO TOTAL</b></td>
            <td style="width:75%; font-size: 9px;">'.round($proyecto[0]['avance_financiero'],2).' % &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; de fecha : '.date("d").'/'.date("m"). "/" . date("Y").'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FINANCIERO GESTIÓN '.$this->gestion.'</b></td>
            <td style="width:75%; font-size: 9px;"> %</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FISCAL DE OBRA</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['fiscal_obra']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROBLEMA IDENTIFICADO</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['proy_desc_problema']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROPUESTA DE SOLUCIÓN</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['proy_desc_solucion']).'</td>
          </tr>
        </tbody>
       </table>';

    return $tabla;
  }


  /*------ NOMBRE MES -------*/
  function mes_nombre(){
    $mes[1] = 'ENE.';
    $mes[2] = 'FEB.';
    $mes[3] = 'MAR.';
    $mes[4] = 'ABR.';
    $mes[5] = 'MAY.';
    $mes[6] = 'JUN.';
    $mes[7] = 'JUL.';
    $mes[8] = 'AGOS.';
    $mes[9] = 'SEPT.';
    $mes[10] = 'OCT.';
    $mes[11] = 'NOV.';
    $mes[12] = 'DIC.';
    return $mes;
  }
}