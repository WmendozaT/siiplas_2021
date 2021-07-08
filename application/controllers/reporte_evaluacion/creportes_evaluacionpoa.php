<?php
class Creportes_evaluacionpoa extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('reportes_evaluacionpoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_indicadores_unidades($dep_id,$dist_id,$tp_id){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
      $titulo_rep='PARAMETROS DE CUMPLIMIENTO POR UNIDAD';
      if($dep_id==0){ /// Institucional
          $tabla='Institucional';
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dep_id,0,$titulo_rep);
          $lista=$this->reportes_evaluacionpoa->pdf_lista_parametro_cumplimiento_unidad(0,$dep_id);
        }
        elseif($dep_id!=0 & $dist_id!=0){ /// Distrital
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dep_id,1,$titulo_rep);
          $lista=$this->reportes_evaluacionpoa->pdf_lista_parametro_cumplimiento_unidad(1,$dist_id);
        }

      $data['pie']=$this->reportes_evaluacionpoa->pie_evaluacionpoa();
      $data['operaciones']=$lista;
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_indicadores_parametros', $data);
    }


    

        /*-- GET CUADRO DE EFICIENCIA Y EFICACIA por UNIDAD NACIONA, REGIONAL, DISTRITAL --*/
    public function get_programas_parametros(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $tp_id = $this->security->xss_clean($post['tp_id']); /// tipo id
        
        $matriz='No encontrado !!';
        $tabla='No encontrado !!';

        if($dep_id==0){ /// Institucional
          $matriz='Institucional';
          $tabla='';
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($dep_id,4);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_regional($lista_programas);
        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($dist_id,4);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_distrital($lista_programas);
        }

        $tabla_programa=$this->reportes_evaluacionpoa->tabla_apertura_programatica($matriz_programas,count($lista_programas),1);
        

        $matriz_parametros_prog=$this->reportes_evaluacionpoa->matriz_parametros($matriz_programas,count($lista_programas));
        $parametros_prog=$this->reportes_evaluacionpoa->parametros_eficacia($matriz_parametros_prog,1);


        $result = array(
          'respuesta' => 'correcto',
          'tabla_prog'=>$tabla_programa,
          'parametro_eficacia_prog'=>$parametros_prog,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_categoria_programatica($id,$tp){


     /* if($tp==0){
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($id,4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_regional($lista_programas);
      }
      elseif($tp==1) {
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($id,4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_distrital($lista_programas);
      }
      else{
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional(4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_institucional($lista_programas);
      }

      $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($id,$tp);
      $data['pie']=$this->reportes_evaluacionpoa->pie_evaluacionpoa();
      $data['operaciones']=$this->reportes_evaluacionpoa->tabla_apertura_programatica_reporte($matriz_programas,count($lista_programas));
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_indicadores_parametros', $data);*/
    }

}