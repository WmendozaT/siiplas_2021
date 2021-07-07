<?php
class Crep_evalinstitucional extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('ejecucion/model_seguimientopoa');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas

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
            $this->trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
            $this->load->library('evaluacionpoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    /// MENU EVALUACIÓN POA 
    public function menu_eval_poa(){
      if($this->gestion>2019){
        $data['menu']=$this->menu(7); //// genera menu
        $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        $data['regional']=$this->evaluacionpoa->listado_regionales();
        
        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_menu', $data);
      }
      else{
        redirect('regionales'); // Rideccionando a Evaluacion anterior 2019
      }
    }


    /*-------- GET CUADRO EVALUACION INTITUCIONAL REGIONAL DISTRITAL --------*/
    public function get_cuadro_evaluacion_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id=$this->security->xss_clean($post['dep_id']); // dep id
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $tp_id = $this->security->xss_clean($post['tp_id']); // 0 : Consolidado Regional, 1: distrital, 2 : Nacional

        if($dep_id==0){ /// Institucional
          $tabla='Institucional';
        }
        elseif($dep_id!=0 & $dist_id==0){
          $tabla='<center><iframe id="ipdf" width="99%" height="1000px;" src="'.base_url().'index.php/rep_eval_poa/evaluacion_poa_regional/'.$dep_id.'/'.$tp_id.'"></iframe></center>';
        }
        elseif($dep_id!=0 & $dist_id!=0){
          $tabla='<center><iframe id="ipdf" width="99%" height="1000px;" src="'.base_url().'index.php/rep_eval_poa/evaluacion_poa_distrital/'.$dist_id.'/'.$tp_id.'"></iframe></center>';
        }

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    //// IFRAME DISTRITAL EVAL POA 
    public function evaluacion_poa_regional($dep_id,$tp_id){
      $data['trimestre']=$this->trimestre;
      $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
      $data['url']=$dep_id.'/0/'.$tp_id;
      $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_regional($dep_id); /// Tabla para el grafico al trimestre
      $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_regional_total($dep_id); /// Tabla para el grafico Total Gestion
      
      $data['titulo_indicador']='UNIDADES DEPENDIENTES';
      $data['boton']='CARGAR CUADRO DE INDICADORES Y PARAMETROS DE CUMPLIMIENTO';
      $data['titulo']=
        '<h2><b>CONSOLIDADO REGIONAL '.strtoupper($data['departamento'][0]['dep_departamento']).' - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

      $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
      $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
      $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],3);
      $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],4);

      $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['departamento'][0]['dep_departamento']);

      $data['tabla_regresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
      $data['tabla_regresion_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion
      

      $data['tabla_regresion_total']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
      $data['tabla_regresion_total_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion
      

      $data['tabla_pastel_todo']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
      $data['tabla_pastel_todo_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion


      $data['base']='
        <input name="base" type="hidden" value="'.base_url().'">
        <input name="tabla2" type="hidden" value="'.$data['tabla'][2][$this->session->userData('trimestre')].'">
        <input name="tabla3" type="hidden" value="'.$data['tabla'][3][$this->session->userData('trimestre')].'">
        <input name="tabla4" type="hidden" value="'.$data['tabla'][4][$this->session->userData('trimestre')].'">
        <input name="tabla5" type="hidden" value="'.$data['tabla'][5][$this->session->userData('trimestre')].'">
        <input name="tabla6" type="hidden" value="'.$data['tabla'][6][$this->session->userData('trimestre')].'">
        <input name="tabla7" type="hidden" value="'.$data['tabla'][7][$this->session->userData('trimestre')].'">
        <input name="tabla8" type="hidden" value="'.$data['tabla'][8][$this->session->userData('trimestre')].'">

        <input name="tit" type="hidden" value="'.$tit.'">
        <input name="dep_id" type="hidden" value="'.$dep_id.'">
        <input name="dist_id" type="hidden" value="0">
        <input name="tp_id" type="hidden" value="'.$tp_id.'">';
      $data['calificacion']=$this->evaluacionpoa->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// Parametros de Eficacia


      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);

    }



    //// IFRAME DISTRITAL EVAL POA 
    public function evaluacion_poa_distrital($dist_id,$tp_id){
      $data['trimestre']=$this->trimestre;
      $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
      $data['url']='1/'.$dist_id;
      //$data['url']=$data['distrital'][0]['dep_id'].'/'.$dist_id.'/'.$tp_id;
      $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_distrital($dist_id); /// Tabla para el grafico al trimestre
      $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_distrital_total($dist_id); /// Tabla para el grafico Total Gestion
      $data['titulo_indicador']='UNIDADES DEPENDIENTES';
      $data['boton1']='CARGAR % CUMPLIMIENTO POR UNIDAD';
      $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';
      $data['titulo']=
        '<h2>'.strtoupper($data['distrital'][0]['dist_distrital']).' <b>- '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

      $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
      $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
      $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],3);
      $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],4);

      $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['distrital'][0]['dist_distrital']);

      $data['tabla_regresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
      $data['tabla_regresion_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion
      

      $data['tabla_regresion_total']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
      $data['tabla_regresion_total_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion
      

      $data['tabla_pastel_todo']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
      $data['tabla_pastel_todo_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

      $data['boton_parametros_unidad']='
          <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_unidad/'.$dist_id.'/1\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
          <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

      $data['boton_parametros_prog']='
          <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_programa/'.$dist_id.'/1\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
          <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

      $data['base']='
        <input name="base" type="hidden" value="'.base_url().'">
        <input name="tabla2" type="hidden" value="'.$data['tabla'][2][$this->session->userData('trimestre')].'">
        <input name="tabla3" type="hidden" value="'.$data['tabla'][3][$this->session->userData('trimestre')].'">
        <input name="tabla4" type="hidden" value="'.$data['tabla'][4][$this->session->userData('trimestre')].'">
        <input name="tabla5" type="hidden" value="'.$data['tabla'][5][$this->session->userData('trimestre')].'">
        <input name="tabla6" type="hidden" value="'.$data['tabla'][6][$this->session->userData('trimestre')].'">
        <input name="tabla7" type="hidden" value="'.$data['tabla'][7][$this->session->userData('trimestre')].'">
        <input name="tabla8" type="hidden" value="'.$data['tabla'][8][$this->session->userData('trimestre')].'">

        <input name="tit" type="hidden" value="'.$tit.'">
        <input name="dep_id" type="hidden" value="'.$data['distrital'][0]['dep_id'].'">
        <input name="dist_id" type="hidden" value="'.$dist_id.'">
        <input name="tp_id" type="hidden" value="'.$tp_id.'">';
      $data['calificacion']=$this->evaluacionpoa->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// Parametros de Eficacia


      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);

    }






    //// EVALUACIÓN POA - REGIONAL -DISTRITAL  - IFRAME (ANTERIOR)
    public function evaluacion_poa($id,$tp){
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      //$data['base']='<input name="base" type="hidden" value="'.base_url().'">';

      if($tp==0){ //// CONSOLIDADO REGIONAL
        $dep_id=$id;
        $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo_indicador']='DISTRITALES DEPENDIENTES';
        $data['boton']='CARGAR INDICADORES';
        $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_regional($dep_id); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_regional_total($dep_id); /// Tabla para el grafico Total Gestion
         
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO REGIONAL '.strtoupper($data['departamento'][0]['dep_departamento']).'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['departamento'],1);
        $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['departamento'],2);
        $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['departamento'],3);
        $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['departamento'],4);
      //  $data['matriz']=$this->evaluacionpoa->matriz_eficacia_regional($dep_id);

        $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['departamento'][0]['dep_departamento']);
      /*for ($i=1; $i <=$this->tmes ; $i++) { 
        for ($j=1; $j <=8 ; $j++) { 
            echo "[".$data['tabla'][$j][$i]."]";
          }  
          echo "<br>";
      };*/
      
      }
      elseif($tp==1){ //// CONSOLIDADO DISTRITAL
        $dist_id=$id;
        $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
        $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_distrital($dist_id); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_distrital_total($dist_id); /// Tabla para el grafico Total Gestion
        $data['titulo_indicador']='UNIDADES DEPENDIENTES';
        $data['boton']='CARGAR INDICADORES';
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - '.strtoupper($data['distrital'][0]['dist_distrital']).'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['distrital'],1);
        $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['distrital'],2);
        $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['distrital'],3);
        $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,$data['distrital'],4);
      //  $data['matriz']=$this->evaluacionpoa->matriz_eficacia_distrital($id);

        $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['distrital'][0]['dist_distrital']);
     
      }
      else{ /// NACIONAL tp:2
        $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_nacional(); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_nacional_total(); /// Tabla para el grafico Total Gestion
       // $data['boton']='MOSTRAR CUADRO DE REGIONALES';
        $data['titulo_indicador']='REGIONALES';
        $data['boton']='CARGAR INDICADORES';
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO INSTITUCIONAL</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,'',1);
        $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,'',2);
        $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,'',3);
        $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa($tp,'',4);
      //  $data['matriz']=$this->evaluacionpoa->matriz_eficacia_institucional();
     
        $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_INSTITUCIONAL';
      }

      $data['tabla_regresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
      $data['tabla_regresion_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion
      

      $data['tabla_regresion_total']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
      $data['tabla_regresion_total_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion
      

      $data['tabla_pastel_todo']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
      $data['tabla_pastel_todo_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion
      
    //  $data['parametro_eficacia']=$this->evaluacionpoa->parametros_eficacia($data['matriz'],1);

      $data['id']=$id;
      $data['tp']=$tp;

      $data['base']='
        <input name="base" type="hidden" value="'.base_url().'">
        <input name="tabla2" type="hidden" value="'.$data['tabla'][2][$this->session->userData('trimestre')].'">
        <input name="tabla3" type="hidden" value="'.$data['tabla'][3][$this->session->userData('trimestre')].'">
        <input name="tabla4" type="hidden" value="'.$data['tabla'][4][$this->session->userData('trimestre')].'">
        <input name="tabla5" type="hidden" value="'.$data['tabla'][5][$this->session->userData('trimestre')].'">
        <input name="tabla6" type="hidden" value="'.$data['tabla'][6][$this->session->userData('trimestre')].'">
        <input name="tabla7" type="hidden" value="'.$data['tabla'][7][$this->session->userData('trimestre')].'">
        <input name="tabla8" type="hidden" value="'.$data['tabla'][8][$this->session->userData('trimestre')].'">

        <input name="tit" type="hidden" value="'.$tit.'">
        ';
      $data['calificacion']=$this->evaluacionpoa->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// Parametros de Eficacia


      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);
    }



    /*-- GET CUADRO DE EFICIENCIA Y EFICACIA por UNIDAD NACIONA, REGIONAL, DISTRITAL --*/
    public function get_unidades_eficiencia(){
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
          $matriz='';
          $tabla='';
        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
          $matriz=$this->evaluacionpoa->matriz_eficacia_distrital($dist_id); /// matriz de parametros
          $tabla=$this->evaluacionpoa->unidades_dist_reg(1,$dist_id,$tp_id); //// Lista de Unidades - Distrital

          $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($dist_id,4);
         // $matriz_programas=$this->evaluacionpoa->matriz_programas_distrital($lista_programas);
        }

        $parametro_eficacia=$this->evaluacionpoa->parametros_eficacia($matriz,1);

       /* $parametro_eficacia=$this->evaluacionpoa->parametros_eficacia($matriz,1);
        $tabla_programa=$this->evaluacionpoa->tabla_apertura_programatica($matriz_programas,count($lista_programas),1);


        $matriz_parametros_prog=$this->evaluacionpoa->matriz_parametros($matriz_programas,count($lista_programas));
        $parametros_prog=$this->evaluacionpoa->parametros_eficacia($matriz_parametros_prog,1);*/



        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'parametro_eficacia'=>$parametro_eficacia,
/*          'lista_prog'=>$tabla_programa,
          'parametros_prog'=>$parametros_prog,*/
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


/*public function reporte_parametros($tp_regional,$id){
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      
      if($tp_regional==0){ //// CONSOLIDADO REGIONAL
        $regional=$this->model_proyecto->get_departamento($id);
        $titulo='
                <tr style="font-size: 15pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>';
      }
      elseif($tp_regional==1){ //// CONSOLIDADO DISTRITAL
        $regional=$this->model_proyecto->dep_dist($id);
        $titulo='
                <tr style="font-size: 15pt;">
                  <td style="width:100%;" align=center><b>REGIONAL '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>
                <tr style="font-size: 12pt;">
                  <td style="width:100%;" align=center>'.strtoupper($regional[0]['dist_distrital']).'</td>
                </tr>';
      }
      else{ //// CONSOLIDADO NACIONAL
        $titulo='
                <tr style="font-size: 15pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO NACIONAL INSTITUCIONAL</b></td>
                </tr>';
      }

      $data['titulo']=$titulo;
      if($tp_regional==2){
        $data['tit']='CUADRO DE INDICADORES'; //// Nacional
        $data['tabla']=$this->evaluacionpoa->eficacia_regionales(2);  
      }
      elseif($tp_regional==1){
        $data['tit']='CUADRO DE INDICADORES'; //// Distrital
        $data['tabla']=$this->evaluacionpoa->unidades_dist_reg(2,$tp_regional,$id);  
      }
      else{
        $data['tit']='CUADRO DE INDICADORES'; //// Regional
        if($id==10){
          $data['tabla']=$this->evaluacionpoa->unidades_dist_reg(2,0,$id);  
        }
        else{
          $data['tabla']=$this->evaluacionpoa->list_distritales(2,$id);  
        }
        
      }
    // echo $data['tabla'];
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_eficiencia', $data);

    }*/

    /*---- REPORTE PARAMETROS DE CUMPLIMIENTO ----*/
/*    public function reporte_parametros($dep_id,$dist_id,$tp_id){
      $distrital=$this->model_proyecto->dep_dist($dist_id);

      $tr=($this->tmes*3);
      
      if($dep_id==0){ /// Institucional
          $titulo='INSTITUCIONAL CONSOLIDADO';
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $titulo='REGIONAL '.strtoupper($distrital[0]['dep_departamento']);

        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
          $titulo=strtoupper($distrital[0]['dep_departamento']).' / '.strtoupper($distrital[0]['dist_distrital']);
         // $lista1=$this->unidades_dist_reg(1,$dist_id,4); //// Lista de Unidades - Distrital
        }

        $lista1=$this->unidades_dist_reg(1,$dist_id,4); //// Lista de Unidades - Distrital

      $data['cabecera']=$this->evaluacionpoa->cabecera_evaluacion_trimestral($titulo);
      $data['pie']=$this->evaluacionpoa->pie_evaluacionpoa();
      $data['tabla']=$this->unidades_dist_reg(1,$dist_id,4);

     $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_eficiencia', $data);
    }*/
    /*========================*/






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

    /*
    /*================================= GENERAR MENU ====================================*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
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
    /*--------------------------------------------------------------------------------*/
/*    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }*/
    /*======================================================================================*/

}