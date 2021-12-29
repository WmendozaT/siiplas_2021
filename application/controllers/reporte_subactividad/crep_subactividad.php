<?php
class Crep_subactividad extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_seguimientopoa');

            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('seguimientopoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    //// REPORTE POR POR CADA SUBACTIVIDAD
    public function menu_reporte_subactividad($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion);
      if(count($data['componente'])!=0){
        $data['menu'] = $this->seguimientopoa->menu_segpoa($com_id,3);
        $data['resp']=$this->session->userdata('funcionario');
        $trimestre=$this->model_evaluacion->trimestre();
        $data['select']=' 
        <div class="well">
          <form class="smart-form">
            <input name="base" type="hidden" value="'.base_url().'">
            <input name="com_id" type="hidden" value="'.$com_id.'">
            <input name="m_id" type="hidden" value="'.$this->session->userdata('mes').'">
            <input name="trm_id" type="hidden" value="'.$this->session->userdata('trimestre').'">
            <header>
                <b><div style="font-size: x-large; font-variant: small-caps;">'.$data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion'].' - GESTI&Oacute;N '.$this->gestion.'</div></b>
            </header>
            <fieldset>          
              <div class="row">
                <section class="col col-3">
                  <label class="label"></label>
                  <select class="form-control" id="rep_id" name="rep_id" title="SELECCIONE TIPO DE REPORTE">
                    <option value="0">SELECCIONE TIPO DE REPORTE POA '.$this->gestion.'</option>
                    <option value="1">1.- FORMULARIO N° 4 - ACTIVIDADES</option>
                    <option value="2">2.- FORMULARIO N° 5 - REQUERIMIENTOS</option>
                    <option value="3">3.- EJECUCIÓN POA - REQUERIMIENTOS</option>
                    <option value="4">4.- NOTIFICACIÓN POA - '.$this->verif_mes[2].'/'.$this->gestion.'</option>
                    <option value="5">5.- SEGUIMIENTO MENSUAL POA - '.$this->gestion.'</option>
                    <option value="6">6.- EVALUACIÓN TRIMESTRAL POA - '.$this->gestion.'</option>
                  </select>
                </section>

                  <div id="seg_poa" style="display:none;">
                    <section class="col col-3">
                      <label class="label"></label>
                      <select class="form-control" id="mes_id" name="mes_id" title="SELECCIONE MES DE SEGUIMIENTO POA">
                        <option value="0">Seleccione mes del Seguimiento POA ..</option>
                      </select>
                    </section>
                  </div>

                  <div id="eval_poa" style="display:none;">
                    <section class="col col-2">
                      <label class="label"></label>
                      <select class="form-control" id="trimestre_id" name="trimestre_id" title="SELECCIONE EVALUACION POA">
                      </select>
                    </section>
                  </div>
              </div>
            </fieldset>
          </form>
        </div>';

        $this->load->view('admin/reportes_cns/rep_subactividad/menu_principal', $data);
      }
      else{
        echo "Error !!";
      }
    }


    /*--- GET LISTA DE REPORTES ---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $rep_id = $this->security->xss_clean($post['rep_id']);
        $com_id = $this->security->xss_clean($post['com_id']);

        $salida='';
        if($rep_id==1){
          $salida='
          <form class="smart-form">
            <header><b>FORMULARIO N° 4 - ACTIVIDADES '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/prog/reporte_form4/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==2) {
          $salida='
          <form class="smart-form">
            <header><b>FORMULARIO N° 5 - REQUERIMIENTOS '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/proy/orequerimiento_proceso/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==3) {
          $salida='
          <form class="smart-form">
            <header><b>CERTIFICACIÓN POA, FORMULARIO N° 5 - REQUERIMIENTOS '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/rep/rep_requerimientos_ejecucion_servicio/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==4) {
          $salida='
          <form class="smart-form">
            <header><b>NOTIFICACIÓN POA - MES '.$this->verif_mes[2].' / '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/get_notificacion_subactividad_mensual/'.$com_id.'"></iframe>
          </form>';
        }

        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- GET LISTA DE REPORTES SEGUIMIENTO POA ---*/
    public function get_rep_seguimientopoa(){
      //$data['tmes']=$this->model_evaluacion->trimestre();
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $mes_id = $this->security->xss_clean($post['mes_id']);
        $com_id = $this->security->xss_clean($post['com_id']);

        $mes=$this->model_evaluacion->get_mes($mes_id);
        $salida='';
        $salida='
          <form class="smart-form">
            <header><b>FORMULARIO SEGUIMIENTO POA - '.$mes['0']['m_descripcion'].' / '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$mes_id.'"></iframe>
          </form>';

        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*----- REPORTE NOTIFICACION POA MENSUAL POR GASTO CORRIENTE 2021 -----*/
    public function get_notificacion_subactividad($com_id){
      $componente = $this->model_componente->get_componente($com_id,$this->gestion);
      if(count($componente)!=0){
        $data['cuerpo']=$this->seguimientopoa->get_notificacion_subactividad($com_id);
        $data['titulo_pie']='NOTIFICACION_POA_'.$this->verif_mes[2].'_'.$componente[0]['tipo_subactividad'].'_'.$componente[0]['serv_descripcion'];
        $this->load->view('admin/reportes_cns/rep_subactividad/reporte_notificacion_seguimiento_subactividad', $data); 
      }
      else{
        echo "Error !!!";
      }

    }



    /* GET MES DE SEGUIMIENTO POA ---*/
    public function get_seguimiento_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $m_id = $this->security->xss_clean($post['m_id']);
        $meses = $this->model_configuracion->get_mes();
        $mes=$this->model_evaluacion->get_mes($m_id);
        $tabla='';

          foreach($meses as $rowm){
            if($rowm['m_id']<=$this->verif_mes[1]){

              if($rowm['m_id']==$m_id){
                $tabla.='<option value="'.$rowm['m_id'].'" selected>SEGUIMIENTO POA - '.$rowm['m_descripcion'].' / '.$this->gestion.'</option>';
              }
              else{
                $tabla.='<option value="'.$rowm['m_id'].'">SEGUIMIENTO POA - '.$rowm['m_descripcion'].' / '.$this->gestion.'</option>';
              }
              
            }
          }

          $salida='';
          $salida.='
          <form class="smart-form">
            <header><b>FORMULARIO SEGUIMIENTO POA - '.$mes[0]['m_descripcion'].' / '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$m_id.'"></iframe>
          </form>';

        $result = array(
          'respuesta' => 'correcto',
          'lista' => $tabla,
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /* GET EVALUACION POA TRIMESTRAL ---*/
    public function get_evaluacion_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $trm_id = $this->security->xss_clean($post['trm_id']);
        $trimestre=$this->model_evaluacion->trimestre();
        $listar_trimestre= $this->model_configuracion->get_mes_trimestre();

        $tabla='';
          $tabla.='<option value="0">Seleccione Trimestre de Evaluación POA ..</option>';
          foreach($listar_trimestre as $row){
            if($row['trm_id']!=0){
              if($row['trm_id']<=$trm_id){
                if($row['trm_id']==$trm_id){
                  $tabla.='<option value="'.$row['trm_id'].'" selected>EVALUACION POA - '.$row['trm_descripcion'].' / '.$this->gestion.'</option>';
                }
                else{
                  $tabla.='<option value="'.$row['trm_id'].'">EVALUACION POA - '.$row['trm_descripcion'].' / '.$this->gestion.'</option>';
                }
              }
            }
          }


          $salida='';
          $salida.='
          <form class="smart-form">
            <header><b>EVALUACION POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/iframe_rep_evaluacionpoa_subactividad/'.$com_id.'/'.$trm_id.'"></iframe>
          </form>';

        $result = array(
          'respuesta' => 'correcto',
          'lista_evaluacion' => $tabla,
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    //// IFRAME EVAL SUBACTIVIDAD
    public function iframe_evaluacion_poa_subactividad($com_id,$trm_id){
      $data['componente']=$this->model_componente->get_componente($com_id,$this->gestion);
      $data['base']='<input name="base" type="hidden" value="'.base_url().'">';
      $data['trimestre']=$this->model_evaluacion->get_trimestre($trm_id);
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);


      if(count($data['componente'])!=0){
        if($this->session->userdata('tp_usuario')==0){ /// Unidad Administrativa
           $data['titulo']=
            '<h2><b>'.strtoupper($data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion']).' - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';
        }
        else{
          $establecimiento=$this->model_seguimientopoa->get_unidad_programado_gestion($this->session->userData('act_id'));
          $data['titulo']=
            '<h2><b>'.strtoupper($establecimiento[0]['tipo'].' '.$establecimiento[0]['act_descripcion'].' '.$establecimiento[0]['abrev']).' - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';
        }

        $data['cabecera_regresion']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],2,$trm_id);
        $data['cabecera_pastel']='';
        $data['cabecera_regresion_total']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],3,$trm_id);


       
        $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($com_id,$trm_id); /// Tabla para el grafico por trimestre
        $data['calificacion']=$this->seguimientopoa->calificacion_eficacia($data['tabla'][5][$trm_id]);


        $data['tabla_regresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$trm_id,2,1); /// Tabla que muestra el acumulado por trimestres Regresion Vista
        $data['tabla_regresion_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$trm_id,2,0); /// Tabla que muestra el acumulado por trimestres Regresion Impresion
        
        /*--- grafico Pastel trimestral ---*/
        $data['tabla_pastel_todo']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$trm_id,4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo Vista
        $data['tabla_pastel_todo_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$trm_id,4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion


        $data['tabla_gestion']=$this->seguimientopoa->tabla_regresion_lineal_servicio_total($com_id); /// Matriz para el grafico Total Gestion
        $data['tabla_regresion_total']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],$trm_id,3,1); /// Tabla que muestra el acumulado Gestion Vista
        $data['tabla_regresion_total_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],$trm_id,3,0); /// Tabla que muestra el acumulado Gestion Impresion

        //echo $data['trimestre'][0]['trm_id'];
        $this->load->view('admin/reportes_cns/rep_subactividad/iframe_evaluacion_subactividad', $data); 
      }
      else{
        echo "Error !!!";
      }

    }



    /*--- GET LISTA DE REPORTES SEGUIMIENTO POA ---*/
    public function get_rep_evaluacionpoa(){
      //$data['tmes']=$this->model_evaluacion->trimestre();
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $trm_id = $this->security->xss_clean($post['trm_id']);
        $com_id = $this->security->xss_clean($post['com_id']);

        $trimestre=$this->model_evaluacion->get_trimestre($trm_id);
        $salida='';
          $salida.='
          <form class="smart-form">
            <header><b>EVALUACION POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/iframe_rep_evaluacionpoa_subactividad/'.$com_id.'/'.$trm_id.'"></iframe>
          </form>';

        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }






}