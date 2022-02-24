<?php
class Cevaluacion_oregional extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dep_id = $this->session->userData('dep_id');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->conf_estado = $this->session->userData('conf_estado'); /// conf estado Gestion (1: activo, 0: no activo)
        $this->load->library('eval_oregional');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*-- Menu Regional 2022 --*/
    public function menu_regional(){
      $data['menu']=$this->eval_oregional->menu(4); //// genera menu
      $data['titulo']=$this->eval_oregional->titulo();
      
      if($this->tp_adm==1){
        $data['tabla']=$this->eval_oregional->regionales();
      }
      else{
        $data['tabla']=$this->eval_oregional->ver_relacion_ogestion($this->dep_id);
      }

      $this->load->view('admin/evaluacion/evaluacion_oregional/menu_regionales', $data);
    }



    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE --------*/
    public function update_temporalidad_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $departamento=$this->model_proyecto->get_departamento($dep_id);

        $this->eval_oregional->create_temporalidad_oregional($dep_id); /// creando la temporalidad de los Objetivos REgioanles

        $tabla='';
        $tabla.='
          <hr><h3><b>&nbsp;&nbsp;OPERACIONES '.$this->gestion.': REGIONAL '.strtoupper($departamento[0]['dep_departamento']).'</b></h3><hr>
          <div class="alert alert-success alert-block" align=center>
            <h2> LA TEMPORALIDAD DE OBJETIVOS DE GESTIÓN FUE ACTUALIZADO EXITOSAMENTE !!!</2> 
          </div>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*---- FUNCION GET LISTA DE ACTIVIDADES PRIORIZADOS --------*/
    public function ver_actividades_priorizados(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $or_id = $this->security->xss_clean($post['or_id']); /// or id
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $meta='';
        if($detalle_oregional[0]['indi_id']==2){
          $meta='%';
        }

        $titulo='
        <b style="font-family:Verdana;font-size: 16px;">
          OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['or_codigo'].' '.$detalle_oregional[0]['or_objetivo'].'<br>
          META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).' '.$meta.'
        </b>';

        $boton_imprimir='
          <hr>
            <div align=right>
              <a href="javascript:abreVentana(\''.site_url("").'/rep_list_form4_priori_oregional/'.$or_id.'\');" title="IMPRIMIR ACP DISTRIBUCION REGIONAL" class="btn btn-default">
                <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR ACTIVIDADES PRIORITARIOS
              </a>
            </div>
          </hr>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$this->eval_oregional->get_mis_form4_priorizados_x_oregional($or_id,0),
            'titulo'=>$titulo,
            'imprimir_act_priori'=>$boton_imprimir,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*---- FUNCION GET NIVEL DE CUMPLIMIENTO DE LA OPERACION (GRAFICOS) --------*/
    public function ver_datos_avance_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $or_id = $this->security->xss_clean($post['or_id']); /// or id
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($post['or_id']); /// Objetivo Regional
        $regional=$this->model_proyecto->get_departamento($dep_id);
      //  $calificacion=$this->eval_oregional->calificacion_trimestral_acumulado_x_oregional($or_id,$this->tmes);
        $matriz=$this->eval_oregional->tabla_trimestral_acumulado_x_oregional($or_id); /// Matriz de Metas Trimestrales
        $tab=$this->eval_oregional->get_temporalidad_objetivo_regional($or_id,0);
        $tab_acumulado=$this->eval_oregional->get_temporalidad_acumulado_objetivo_regional($or_id,0);

        $titulo='
        <b style="font-family:Verdana;font-size: 16px;">
          OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['or_codigo'].' '.$detalle_oregional[0]['or_objetivo'].'<br>
          META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).'
        </b>';

          $result = array(
            'respuesta' => 'correcto',
            'titulo'=>$titulo,
            'tab'=>$tab, /// temporalidad trimestral
            'tab_acu'=>$tab_acumulado,
            'matriz_acumulado'=>$matriz,
            'trimestre'=>$this->tmes, /// Trimestre
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


   //// Reporte de Evaluacion formulario N° 2
  public function reporte_evaluacion_form2($dep_id){
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $data['mes'] = $this->eval_oregional->mes_nombre();
    $data['cabecera']=$this->eval_oregional->cabecera_form2($regional);
    $data['oregional']=$this->eval_oregional->rep_lista_form2($dep_id);
    $data['pie']=$this->eval_oregional->pie_form2($regional);
    $data['titulo_pie']='EVALUACION_FORM2_'.$regional[0]['dep_departamento'].'_'.$this->gestion.'';

    $this->load->view('admin/evaluacion/evaluacion_oregional/reporte_eval_form2', $data);
  }



  //// Reporte Lista de Actividades Priorizados por Objetivos Regioanles
  public function reporte_act_priorizados_oregional($or_id){
    $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
    if(count($detalle_oregional)!=0){
      $regional=$this->model_proyecto->get_departamento($detalle_oregional[0]['dep_id']);
      $data['mes'] = $this->eval_oregional->mes_nombre();
      $data['cabecera']=$this->eval_oregional->cabecera_form2($regional);
      //$data['oregional']=$this->eval_oregional->rep_lista_form2($detalle_oregional[0]['dep_id']);
      $data['oregional']=$this->eval_oregional->rep_lista_form4_priorizados($or_id,1);

      $data['pie']=$this->eval_oregional->pie_form4_priorizados();

      $data['titulo_pie']='LIST_FORM4_PRIORIZADOS_'.$regional[0]['dep_departamento'].'_'.$detalle_oregional[0]['og_codigo'].'.'.$detalle_oregional[0]['or_codigo'].'_'.$this->gestion.'';

    //  echo $data['oregional'];
      $this->load->view('admin/evaluacion/evaluacion_oregional/reporte_eval_form2', $data);
    }
    else{
      echo "Error !!!!";
    }
  }

}