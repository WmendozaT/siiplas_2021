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
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_certificacion');
/*            $this->load->model('reporte_eval/model_evalnacional');
            $this->load->model('reporte_eval/model_evalregional');
            $this->load->model('mantenimiento/mapertura_programatica');*/
          //  $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
          //  $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');

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

      $this->load->view('admin/consultas_internas/menu_consultas_poa', $data);
    }

  /*-----  OPCIONES 2020-2021 -----*/
    public function get_opciones($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {

        case 'reporte':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo Reporte....</option>";
          $salida.= "<option value='1'>LISTA DE UNIDADES / PROYECTOS DE INVERSIÓN</option>";
          $salida.= "<option value='2'>CONSOLIDADO FORMULARIO 4 (ACTIVIDADES)</option>";
          $salida.= "<option value='3'>CONSOLIDADO FORMULARIO 5 (REQUERIMEINTOS)</option>";
          $salida.= "<option value='4'>LISTA DE CERTIFICACIONES POA</option>";
          $salida.= "<option value='5'>EVALUACION POA REGIONAL</option>";

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
              <header><b>SEGUIMIENTO POA '.$this->gestion.'</b></header>
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