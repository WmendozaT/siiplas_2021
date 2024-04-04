<?php
class Cevaluacion_form2 extends CI_Controller {
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
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/insumos/model_insumo');
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
        $this->fecha_plazo_actualizacion = strtotime(date('2022-04-30'));
        $this->load->library('eval_oregional');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*-- Menu Regional 2024 FORMULARIO N° 2 --*/
    public function menu_regional(){
      $data['menu']=$this->eval_oregional->menu(4); //// genera menu
      $data['titulo']=$this->eval_oregional->titulo();

      if($this->tp_adm==1){
        $data['tabla']=$this->eval_oregional->regionales();
      }
      else{
        $data['tabla']=$this->eval_oregional->ver_relacion_ogestion($this->dep_id);
      }

      $this->load->view('admin/evaluacion/evaluacion_form2/menu_regionales', $data);

      
    }


    /*---- FUNCION GET LISTA DE OPERACIONES POR REGIONAL --------*/
    public function get_lista_operaciones_x_regionales(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $departamento=$this->model_proyecto->get_departamento($dep_id);
        $date_actual = strtotime(date('Y-m-d')); //// fecha Actual

        $tabla='';
        if(($date_actual<=$this->fecha_plazo_actualizacion) || $this->tp_adm==1) {
          $tabla.='
          <a href="#" class="btn btn-lg btn-default" onclick="update_temp('.$dep_id.');" style="width:35%;" title="ACTUALIZAR TEMPORALIDAD DE EVALUACION OBJETIVO REGIONAL"><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;</a>';
        }

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$this->eval_oregional->ver_relacion_ogestion($dep_id),
            'rep'=>'<a href="javascript:abreVentana(\''.site_url("").'/rep_eval_oregional/'.$dep_id.'\');" title="REPORTE EVALUACIÓN META REGIONAL" class="btn btn-lg btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EVALUACIÓN POA</b></a>',
            'btn_update'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE --------*/
    public function update_temporalidad_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $departamento=$this->model_proyecto->get_departamento($dep_id);

        $this->eval_oregional->create_temporalidad_oregional($dep_id); /// creando la temporalidad de los Objetivos REgioanles

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$this->eval_oregional->ver_relacion_ogestion($dep_id),
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


  /*---- FUNCION EVALUACION POA (OBJETIVOS REGIONALES) --------*/
  public function update_evaluacion_oregional(){
    if($this->input->is_ajax_request()){
      $regionales=$this->model_proyecto->list_departamentos();
      $return=false;
      foreach($regionales as $reg){
        $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($reg['dep_id']);
        foreach($lista_ogestion as $row){
          if($row['indi_id']==1){
            $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
            $denominador=1;
          }
          else{
            $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional_recurrentes($row['or_id'],$row['tp_meta']);
            $denominador=$metas_prior[0]['nro']*3;
          }

          if(count($metas_prior)!=0){
              /// --- eliminando ejecucion de Objetivos Regionales
                $this->db->where('or_id', $row['or_id']);
                $this->db->delete('temp_trm_ejec_objetivos_regionales');
              /// ---->

            if(round($row['or_meta'],2)==round($metas_prior[0]['meta_prog_actividades'],2)) { /// META == META ACUMULADO FORN 4
              /// creamos registro ejecucion
              for ($i=1; $i <=4 ; $i++) { 
                $get_dato_trimestre=$this->model_objetivoregion->get_suma_trimestre_ejecucion_oregional($row['or_id'],$i);
                if(count($get_dato_trimestre)!=0){
                    /*--------------------------------------------------------*/
                    $data_to_store2 = array( ///// Tabla temp prog oregional
                      'or_id' => $row['or_id'], /// or id
                      'trm_id' => $i, /// trimestre
                      'ejec_fis' => ($get_dato_trimestre[0]['trimestre']/$denominador), /// valor
                      'g_id' => $this->gestion, /// gestion                
                    );
                    $this->db->insert('temp_trm_ejec_objetivos_regionales', $data_to_store2);
                    /*----------------------------------------------------------*/
                }
              }
            }
          }

        }

        $return=true;
      }

      echo $return;
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
        $or_tp = $this->security->xss_clean($post['or_tp']); /// or tp (1 inversion), (0 gasto corriente) 
        $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($post['or_id']); /// Objetivo Regional
        $regional=$this->model_proyecto->get_departamento($dep_id);
      //  $calificacion=$this->eval_oregional->calificacion_trimestral_acumulado_x_oregional($or_id,$this->tmes);
        $matriz=$this->eval_oregional->tabla_trimestral_acumulado_x_oregional($or_id,$or_tp,$detalle_oregional[0]['tp_meta']); /// Matriz de Metas Trimestrales
        $tab=$this->eval_oregional->get_temporalidad_objetivo_regional($or_id,0,$or_tp,$detalle_oregional[0]['tp_meta']);
        $tab_acumulado=$this->eval_oregional->get_temporalidad_acumulado_objetivo_regional($or_id,0,$or_tp,$detalle_oregional[0]['tp_meta']);

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
    $data['cuadro_consolidado']=false;
    $data['titulo_pie']='EVALUACION_FORM2_'.$regional[0]['dep_departamento'].'_'.$this->gestion.'';
    $data['dimension_inferior']='26mm';

    $this->load->view('admin/evaluacion/evaluacion_form2/reporte_eval_form2', $data);
  }



  //// Reporte Lista de Actividades Priorizados por Objetivos Regioanles
  public function reporte_act_priorizados_oregional($or_id){
    $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
    if(count($detalle_oregional)!=0){
      $regional=$this->model_proyecto->get_departamento($detalle_oregional[0]['dep_id']);
      $data['mes'] = $this->eval_oregional->mes_nombre();
      $data['cabecera']=$this->eval_oregional->cabecera_form2($regional);
      $data['oregional']=$this->eval_oregional->rep_lista_form4_priorizados($or_id,1);
      $data['pie']=$this->eval_oregional->pie_form4_priorizados();
      $data['titulo_pie']='LIST_FORM4_PRIORIZADOS_'.$regional[0]['dep_departamento'].'_'.$detalle_oregional[0]['og_codigo'].'.'.$detalle_oregional[0]['or_codigo'].'_'.$this->gestion.'';
      $data['cuadro_consolidado']=false;
      
      $verif_temp=$this->model_objetivoregion->verif_temporalidad_oregional($or_id);
      if(count($verif_temp)!=0){
        $data['cuadro_consolidado']=true;
        $data['temporalidad']=$this->eval_oregional->reporte_avance_trimestral_acumulado($or_id);
      }

      $data['dimension_inferior']='10mm';
      
      $this->load->view('admin/evaluacion/evaluacion_form2/reporte_eval_form2', $data);
    }
    else{
      echo "Error !!!!";
    }
  }




    /*---- GRAFICO CONSOLIDADO DE OPERACIONES POR REGIONAL --------*/
    public function get_cumplimiento_operaciones_grafico(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $trm_id = $this->security->xss_clean($post['trm_id']); /// Trimestre
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $tabla='<iframe id="ipdf" width="100%" height="950px;" src="'.base_url().'index.php/rep_meta_oregional_grafico/'.$dep_id.'"></iframe>';
      //  

          $result = array(
            'respuesta' => 'correcto',
            'titulo_graf'=> '<b>REGIONAL : '.strtoupper($regional[0]['dep_departamento']).'</b>',
            'tabla' => $tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*----- CUADRO EVALUACION OPERACIONES REGIONALES (GRAFICO FORM 2) ----*/
    public function cuadro_evaluacion_grafico_form2($dep_id){
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      $data['trimestre']=$this->model_evaluacion->trimestre();
      $data['nro']=count($this->model_objetivogestion->get_list_ogestion_por_regional($dep_id));
      $data['eval']=$this->eval_oregional->matriz_cumplimiento_operaciones_regional($dep_id);      
      $data['cabecera']=$this->eval_oregional->cabecera_reporte_grafico($data['regional']);
      $data['calificacion']=$this->eval_oregional->calificacion_total_form2_regional($dep_id,1);

      
      $tabla='';
      $tabla.='<div style="font-family: Arial;">DETALLE DE OPERACIONES REGIONALES '.$this->gestion.'</div>
              <table>';
                for ($i=0; $i <$data['nro'] ; $i++) { 
                  $tabla.='<tr><td style="font-family: Arial;font-size: 11px;height: 1%;">OPE. '.$data['eval'][$i][0].'.'.$data['eval'][$i][1].'.- '.$data['eval'][$i][2].' - <b>'.$data['eval'][$i][4].' %</b></td></tr>';
                }
                $tabla.='
              </table>
              <hr>';
      $data['tabla_detalle']=$tabla;

      $this->load->view('admin/evaluacion/evaluacion_form2/reporte_grafico_meta_form2', $data);

    }

}