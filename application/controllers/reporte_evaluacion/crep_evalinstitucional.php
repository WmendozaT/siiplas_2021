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

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
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
        $data['base']='<input name="base" type="hidden" value="'.base_url().'">';
        $data['regional']=$this->evaluacionpoa->regionales();
        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_menu', $data);
      }
      else{
        redirect('regionales'); // Rideccionando a Evaluacion anterior 2019
      }
    }




    /*-------- GET CUADRO EVALUACION REGIONALES --------*/
    public function get_cuadro_evaluacion_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $id = $this->security->xss_clean($post['id']); // dep id, dist id , 0: Nacional
        $tp = $this->security->xss_clean($post['tp']); // 0 : Consolidado Regional, 1: distrital, 2 : Nacional

        $tabla='<iframe id="ipdf" width="100%" height="1000px;" src="'.base_url().'index.php/rep_eval_poa/evaluacion_poa/'.$id.'/'.$tp.'"></iframe>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }

    //// EVALUACIÓN POA - REGIONAL -DISTRITAL  - IFRAME
    public function evaluacion_poa($id,$tp){
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $data['base']='<input name="base" type="hidden" value="'.base_url().'">';
      
      if($tp==0){ //// CONSOLIDADO REGIONAL
        $dep_id=$id;
        $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo_indicador']='DISTRITALES DEPENDIENTES';
        $data['boton']='MOSTRAR INDICADORES POR DISTRITALES DEPENDIENTES';
        $data['tabla']=$this->tabla_regresion_lineal_regional($dep_id); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->tabla_regresion_lineal_regional_total($dep_id); /// Tabla para el grafico Total Gestion
         
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO REGIONAL '.strtoupper($data['departamento'][0]['dep_departamento']).'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['matriz']=$this->matriz_eficacia_regional($dep_id);
        $data['parametro_eficacia']=$this->parametros_eficacia($data['matriz'],1); /// Parametro de Eficacia
      //  $data['print_parametros']=$this->print_parametros($tp,$data['departamento'],$this->parametros_eficacia($data['matriz'],2));

       // $data['print_evaluacion']=$this->print_evaluacion(0,$data['departamento'],$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,2),$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,2),$data['tabla'][5][$this->tmes]);
      }
      elseif($tp==1){ //// CONSOLIDADO DISTRITAL
        $dist_id=$id;
        $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
        $data['tabla']=$this->tabla_regresion_lineal_distrital($dist_id); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->tabla_regresion_lineal_distrital_total($dist_id); /// Tabla para el grafico Total Gestion
        $data['titulo_indicador']='UNIDADES DEPENDIENTES';
        $data['boton']='MOSTRAR INDICADORES POR UNIDADES DEPENDIENTES';
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - '.strtoupper($data['distrital'][0]['dist_distrital']).'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['matriz']=$this->matriz_eficacia_distrital($id);
        $data['parametro_eficacia']=$this->parametros_eficacia($data['matriz'],1); /// Parametro de Eficacia
       // $data['print_parametros']=$this->print_parametros($tp,$data['distrital'],$this->parametros_eficacia($data['matriz'],2));

       // $data['print_evaluacion']=$this->print_evaluacion(1,$data['distrital'],$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,2),$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,2),$data['tabla'][5][$this->tmes]);
      }
      else{ /// NACIONAL tp:2
        $data['tabla']=$this->tabla_regresion_lineal_nacional(); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->tabla_regresion_lineal_nacional_total(); /// Tabla para el grafico Total Gestion
        $data['boton']='MOSTRAR CUADRO DE REGIONALES';
        $data['titulo_indicador']='REGIONALES';
        $data['boton']='MOSTRAR INDICADORES POR REGIONALES';
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO INSTITUCIONAL</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['matriz']=$this->matriz_eficacia_institucional();
        $data['parametro_eficacia']=$this->parametros_eficacia($data['matriz'],1); /// Parametro de Eficacia
      //  $data['print_parametros']=$this->print_parametros($tp,'',$this->parametros_eficacia($data['matriz'],2));

      //  $data['print_evaluacion']=$this->print_evaluacion(2,'',$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,2),$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,2),$data['tabla'][5][$this->tmes]);
      }

      $data['tabla_regresion']=$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
      $data['tabla_regresion_total']=$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
    //  $data['tabla_pastel']=$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],1,1); /// Tabla que muestra el acumulado por trimestres Pastel
      $data['tabla_pastel_todo']=$this->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
      $data['id']=$id;
      $data['tp']=$tp;

      $data['calificacion']=$this->calificacion_eficacia($data['tabla'][5][$this->tmes],0); /// Parametros de Eficacia

      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);
    }


    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia,$tp_rep){
      //tp_rep : 0 -> Normal, 1 : Impresion
      $tabla='';

      if($eficacia<=75){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 75%)';} /// Insatisfactorio - Rojo
      if ($eficacia > 75 & $eficacia <= 90){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (75% - 90%)';} /// Regular - Amarillo
      if($eficacia > 90 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (90% - 99%)';} /// Bueno - Azul
      if($eficacia > 99 & $eficacia <= 102){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

      if($tp_rep==0){
        $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';
      }
      else{
        $tabla.='<center><font size=4.5><b>'.$titulo.'</b></font></center>';
      }
      
      return $tabla;
    }



    /*-------- GET CUADRO DE EFICIENCIA Y EFICACIA ------------*/
    public function get_unidades_eficiencia(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']);
        $id = $this->security->xss_clean($post['id']);
        
        if($tp==2){
          $tabla=$this->eficacia_regionales(1,$tp,$id); /// Lista de Regionales - Institucional
        }
        elseif($tp==1){
          $tabla=$this->unidades(1,$tp,$id); //// Lista de Unidades - Distrital
        }
        else{
          if($id==10){
            $tabla=$this->unidades(1,0,$id); //// Lista de Unidades - Distrital
          }
          else{
            $tabla=$this->list_distritales(1,$id); //// Lista de Distritales - Regional
          }
          
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


    /*---- EFICACIA INSTITUCIONAL -----*/
    public function eficacia_regionales($tp_rep){
    $regionales=$this->model_proyecto->list_departamentos();
    $eficacia_nacional=$this->tabla_regresion_lineal_nacional(); /// Eficacia
    $economia_nacional=$this->economia_institucional_nacional(); /// Economia
    $eficiencia_nacional=$this->eficiencia_por_regional($eficacia_nacional[5][$this->tmes],$economia_nacional[3]); /// Eficiencia

    $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:50%;"';
        //$tabla.='<h2 class="alert alert-success" align="center">CUADRO DE EFICACIA Y EFICIENCIA A NIVEL DE REGIONALES </h2>';
        $color='';
      } 
      else{ /// Impresion
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
        $color='#e9edec';
      }

      $tabla.='
        
        <table '.$tab.'>
         <thead>
            <tr style="font-size: 10px;" align=center bgcolor='.$color.'>
              <th style="width:2%;height:15px;">#</th>
              <th style="width:20%;">REGIONAL</th>
              <th style="width:10%;">ACT. PROGRAMADAS</th>
              <th style="width:10%;">ACT. CUMPLIDAS</th>
              <th style="width:10%;">ACT. NO CUMPLIDAS</th>
              <th style="width:10%;">% EFICACIA</th>
              <th style="width:10%;">% ECONOMIA</th>
              <th style="width:10%;"> EFICIENCIA</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($regionales as $row){
            $eficacia=$this->eficacia_por_regional($row['dep_id']); /// Eficacia
            $economia=$this->economia_por_regional($row['dep_id']); /// Economia
            $eficiencia=$this->eficiencia_por_regional($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia
            $nro++;
            $tabla.='<tr style="font-size: 10px;">';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:20%;">'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td style="width:10%;" align=right>'.$eficacia[2][$this->tmes].'</td>';
            $tabla.='<td style="width:10%;" align=right>'.$eficacia[3][$this->tmes].'</td>';
            $tabla.='<td style="width:10%;" align=right>'.$eficacia[4][$this->tmes].'</td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficiencia.'</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 10px;" bgcolor="#d3f8c5">
            <td></td>
            <td ><b>CONSOLIDADO INSTITUCIONAL</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_nacional[2][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_nacional[3][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_nacional[4][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_nacional[5][$this->tmes].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$economia_nacional[3].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficiencia_nacional.'</b></td>
          </tr>
          </tbody>
        </table>';

        return $tabla;
    }

    /*---- UNIDADES ORGANIZACIONAL -----*/
    public function unidades($tp_rep,$tp_uni,$id){
    $unidades=$this->model_evalinstitucional->list_unidades_organizacionales($tp_uni,$id);
    $distrital=$this->model_proyecto->dep_dist($id);
    
    if($tp_uni==0){
      $titulo_consolidado='CONSOLIDADO OFICINA NACIONAL';
      $eficacia_distrital=$this->tabla_regresion_lineal_regional($id); /// Eficacia
      $economia_distrital=$this->economia_por_regional($id); /// Economia
      $eficiencia_distrital=$this->eficiencia_por_regional($eficacia_distrital[5][$this->tmes],$economia_distrital[3]); /// Eficiencia
    }
    else{
      $distrital=$this->model_proyecto->dep_dist($id);
      $titulo_consolidado='CONSOLIDADO '.strtoupper($distrital[0]['dist_distrital']).'';
      $eficacia_distrital=$this->tabla_regresion_lineal_distrital($id); /// Eficacia
      $economia_distrital=$this->economia_por_distrital($id); /// Economia
      $eficiencia_distrital=$this->eficiencia_por_distrital($eficacia_distrital[5][$this->tmes],$economia_distrital[3]); /// Eficiencia
    }


    $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $tabla.='<h2 align=center>CUADRO DE INDICADORES </h2>';
        $color='';
      } 
      else{ /// Impresion
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
        $color='#e9edec';
      }

      $tabla.='
        <table '.$tab.'>
         <thead>
            <tr style="font-size: 9px;" align=center bgcolor='.$color.'>
              <th style="width:2%;height:15px;">#</th>
              <th style="width:13%;">DISTRITO</th>
              <th style="width:13%;">UNIDAD</th>
              <th style="width:10%;">ACT. PROGRAMADO</th>
              <th style="width:10%;">ACT. CUMPLIDO</th>
              <th style="width:10%;">ACT. NO CUMPLIDO</th>
              <th style="width:10%;">% EFICACIA</th>
              <th style="width:10%;">% ECONOMIA</th>
              <th style="width:10%;">EFICIENCIA</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($unidades as $row){
            $eficacia=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
            $economia=$this->economia_por_unidad($row['aper_id'],$row['proy_id']); /// Economia
            $eficiencia=$this->eficiencia_unidad($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia
            $nro++;
            $tabla.='<tr style="font-size: 9px;">';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:13%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:13%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[2][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[3][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[4][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficiencia.'</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 9px;" bgcolor="#d3f8c5">
            <td></td>
            <td colspan=2><b>'.$titulo_consolidado.'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_distrital[2][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_distrital[3][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_distrital[4][$this->tmes].'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_distrital[5][$this->tmes].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$economia_distrital[3].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficiencia_distrital.'</b></td>
          </tr>
          </tbody>
        </table>';

        return $tabla;
    }



    /*============ EFICACIA REGIONAL =============*/
    /*---- DISTRITALES -----*/
    public function list_distritales($tp_rep,$dep_id){
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $distritales=$this->model_evalinstitucional->get_distritales($dep_id);
    $eficacia_regional=$this->tabla_regresion_lineal_regional($dep_id); /// Eficacia
    $economia_regional=$this->economia_por_regional($dep_id); /// Economia
    $eficiencia_regional=$this->eficiencia_por_regional($eficacia_regional[5][$this->tmes],$economia_regional[3]); /// Eficiencia

    $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:90%;"';
        $tabla.='<h2 align=center>CUADRO DE INDICADORES</h2>';
        $color='';
      } 
      else{ /// Impresion
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
        $color='#e9edec';
      }

      $tabla.='
        <table '.$tab.'>
         <thead>
            <tr style="font-size: 9px;" align=center bgcolor='.$color.'>
              <th style="width:5%;height:15px;">#</th>
              <th style="width:40%;">DISTRITAL</th>
              <th style="width:15%;">% EFICACIA</th>
              <th style="width:15%;">% ECONOMIA</th>
              <th style="width:15%;">EFICIENCIA</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($distritales as $row){
            $eficacia=$this->tabla_regresion_lineal_distrital($row['dist_id']); /// Eficacia
            $economia=$this->economia_por_distrital($row['dist_id']); /// Eficiencia
            $eficiencia=$this->eficiencia_por_distrital($eficacia[5][$this->tmes],$economia[3]);
            $nro++;
            $tabla.='<tr style="font-size: 9px;">';
            $tabla.='<td style="width:5%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:40%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$eficiencia.'</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 9px;" bgcolor="#d3f8c5">
            <td></td>
            <td><b>CONSOLIDADO REGIONAL '.strtoupper($regional[0]['dep_departamento']).'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_regional[5][$this->tmes].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$economia_regional[3].'%</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficiencia_regional.'</b></td>
          </tr>
          </tbody>
        </table>';

        return $tabla;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function eficacia_por_regional($dep_id){

      for ($i=0; $i <=$this->tmes; $i++){ 

        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %

      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_por_regional($dep_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($tr[2][$i]-$tr[3][$i]); /// No cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 - REGIONAL -------*/
    public function obtiene_datos_evaluacíon_por_regional($dep_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalinstitucional->nro_operaciones_programadas_regional($dep_id,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_regional($dep_id,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_regional($dep_id,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }

    /*========================*/


    /*============ EFICACIA UNIDAD =============*/
    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function eficacia_por_unidad($proy_id){

      for ($i=0; $i <=$this->tmes; $i++){ 

        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %

      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_por_unidad($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($tr[2][$i]-$tr[3][$i]); /// No cumplidas

        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }

        $tr[6][$i]=(100-$tr[5][$i]);
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 - UNIDAD -------*/
    public function obtiene_datos_evaluacíon_por_unidad($proy_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalunidad->nro_operaciones_programadas($proy_id,$i);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalunidad->list_operaciones_evaluadas_unidad_trimestre_tipo($proy_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalunidad->list_operaciones_evaluadas_unidad_trimestre_tipo($proy_id,$i,$tipo_evaluacion));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }

  /*========================*/



  /*====== ECONOMIA UNIDAD/PROYECTO ======*/
    public function economia_por_unidad($aper_id,$proy_id){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado($aper_id,10000); /// Partidas por defecto
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_unidad($proy_id); //// Presupuesto Certificado
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_certificacion->monto_total_programado_trimestre($aper_id); //// Presupuesto Asignado POA por trimestre
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0; /// % Eficiencia
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }

    /*------ EFICIENCIA POR UNIDAD ------*/
    public function eficiencia_unidad($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }


    /*====== ECONOMIA NACIONAL ======*/
    public function economia_institucional_nacional(){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado_institucional(4,10000); /// Partidas por defecto
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_institucional(4); //// Presupuesto Certificado
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_evalinstitucional->monto_total_programado_trimestre_institucional(4); //// Presupuesto Asignado POA
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0; /// % Eficiencia
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }


    /*====== ECONOMIA REGIONAL ======*/
    public function economia_por_regional($dep_id){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado_por_regional(4,$dep_id,10000); /// Partidas por defecto al trimestre
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_por_regional(4,$dep_id); //// Presupuesto Certificado al trimestre
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_evalinstitucional->monto_total_programado_trimestre_por_regional(4,$dep_id); //// Presupuesto Asignado POA al trimestre
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0; /// % Eficiencia
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }

    /*====== ECONOMIA DISTRITAL ======*/
    public function economia_por_distrital($dist_id){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado_por_distrital(4,$dist_id,10000); /// Partidas por defecto suma al trimestre
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_por_distrital(4,$dist_id); //// Presupuesto Certificado al trimestre
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_evalinstitucional->monto_total_programado_trimestre_por_distrital(4,$dist_id); //// Presupuesto Asignado POA al trimestre
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0; /// % Eficiencia
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }

    /*------ EFICIENCIA POR DISTRITAL ------*/
    public function eficiencia_por_distrital($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }

    /*------ EFICIENCIA POR REGIONAL ------*/
    public function eficiencia_por_regional($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }

    /*------------- REPORTE EFICIENCIA-EFICACIA -------------*/
    public function reporte_eficacia($tp_regional,$id){
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
        $data['tabla']=$this->eficacia_regionales(2);  
      }
      elseif($tp_regional==1){
        $data['tit']='CUADRO DE INDICADORES'; //// Distrital
        $data['tabla']=$this->unidades(2,$tp_regional,$id);  
      }
      else{
        $data['tit']='CUADRO DE INDICADORES'; //// Regional
        if($id==10){
          $data['tabla']=$this->unidades(2,0,$id);  
        }
        else{
          $data['tabla']=$this->list_distritales(2,$id);  
        }
        
      }
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_eficiencia', $data);

    }
    /*========================*/


    /*---- Imprime Evaluacion Consolidado Regional-Distrital -----*/
/*    public function print_evaluacion($tp_regional,$regional,$regresion,$regresion_total,$eficacia){
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      $calificacion=$this->calificacion_eficacia($eficacia,1); /// Parametros de Eficacia

      if($tp_regional==0){
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>';
      }
      elseif($tp_regional==1){
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>REGIONAL '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>
                <tr style="font-size: 12pt;">
                  <td style="width:100%;" align=center>'.strtoupper($regional[0]['dist_distrital']).'</td>
                </tr>';
      }
      else{
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO INSTITUCIONAL</b></td>
                </tr>';
      }

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table class="page_header" border="0" style="width: 100%;>
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 18pt;" align=center><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              '.$titulo.'
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;">
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>';
        $tabla.=$calificacion;
        $tabla .='<table class="change_order_items" border=1 style="width:100%;">
                  <tr>
                    <td>
                      <div id="regresion_impresion" style="width: 500px; height: 220px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      '.$regresion.'
                    </td>
                  </tr>
                  </table>
                  <table class="change_order_items" border=1 style="width:100%;">
                  <tr>
                    <td>
                      <div id="regresion_gestion_print" style="width: 500px; height: 220px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      '.$regresion_total.'
                    </td>
                  </tr>
                </table>';
                ?>
                </html>
                <?php
    return $tabla;
    } */

    /*---- Imprime Evaluacion Consolidado Regional-Distrital -----*/
/*    public function print_parametros($tp_regional,$regional,$matriz){
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);

      if($tp_regional==0){
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>';
      }
      elseif($tp_regional==1){
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>REGIONAL '.strtoupper($regional[0]['dep_departamento']).'</b></td>
                </tr>
                <tr style="font-size: 12pt;">
                  <td style="width:100%;" align=center>'.strtoupper($regional[0]['dist_distrital']).'</td>
                </tr>';
      }
      else{
        $titulo='<tr style="font-size: 14pt;">
                  <td style="width:100%;" align=center><b>CONSOLIDADO INSTITUCIONAL</b></td>
                </tr>';
      }

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table class="page_header" border="0" style="width: 100%;>
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 18pt;" align=center><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              '.$titulo.'
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;">
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        '.$matriz.'';
        ?>
        </html>
        <?php
    return $tabla;
    } */


    /*--- TABLA ACUMULADA EVALUACIÓN 2020 - REGIONAL, DISTRITAL ---*/
    public function tabla_acumulada_evaluacion_regional_distrital($regresion,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>TOTAL ACT. PROGRAMADO</b>';
      $tit[3]='<b>TOTAL ACT. CUMPLIDOS</b>';
      $tit[4]='<b>ACT. NO CUMPLIDOS</b>';
      $tit[5]='<b>% ACT. CUMPLIDOS</b>';
      $tit[6]='<b>% NO ACT. CUMPLIDOS</b>';

      $tit_total[2]='<b>TOTAL ACT. PROGRAMADO</b>';
      $tit_total[3]='<b>TOTAL ACT. CUMPLIDOS</b>';
      $tit_total[4]='<b>% ACT. PROGRAMADO</b>';
      $tit_total[5]='<b>% ACT. CUMPLIDO</b>';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

      if($tp_graf==1){ // pastel : Programado-Cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>TOTAL ACT. PROGRAMADAS</th>
                <th>TOTAL ACT. EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. NO CUMPLIDAS</th>
                <th>% ACT. CUMPLIDAS</th>
                <th>% ACT. NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[3][$this->tmes].'</b></td>
                <td><b>'.$regresion[4][$this->tmes].'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$this->tmes].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$this->tmes].'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }
      elseif($tp_graf==2){ /// Regresion Acumulado al Trimestre
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr bgcolor='.$color.'>
                <th></th>';
                for ($i=1; $i <=$this->tmes; $i++) { 
                  $tabla.='<th align=center><b>'.$regresion[1][$i].'</b></th>';
                }
              $tabla.='
              </tr>
            </thead>
            <tbody>';
              $color=''; $por='';
              for ($i=2; $i <=6; $i++) {
                if($i==5){
                  $por='%';
                  $color='#9de9f3';
                }
                elseif ($i==6) {
                  $por='%';
                  $color='#f7d3d0';
                }
                $tabla.='<tr bgcolor='.$color.'>
                  <td>'.$tit[$i].'</td>';
                  for ($j=1; $j <=$this->tmes; $j++) { 
                    $tabla.='<td align=right><b>'.$regresion[$i][$j].''.$por.'</b></td>';
                  }
                $tabla.='</tr>';
              }
            $tabla.='
            </tbody>
        </table>';
      }
      elseif($tp_graf==3){ /// Regresion Gestion
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr bgcolor='.$color.'>
                <th></th>';
                for ($i=1; $i <=4; $i++) { 
                  $tabla.='<th align=center><b>'.$regresion[1][$i].'</b></th>';
                }
              $tabla.='
              </tr>
              </thead>
            <tbody>';
              $color=''; $por='';
              for ($i=2; $i <=5; $i++) {
                if($i==4 || $i==5){
                  $por='%';
                  $color='#9de9f3';
                }
                $tabla.='<tr bgcolor='.$color.'>
                  <td>'.$tit_total[$i].'</td>';
                  for ($j=1; $j <=4; $j++) { 
                    $tabla.='<td align=right><b>'.$regresion[$i][$j].''.$por.'</b></td>';
                  }
                $tabla.='</tr>';
              }
            $tabla.='
            </tbody>
        </table>';
      }
      else{
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>TOTAL ACT. PROGRAMADAS</th>
                <th>TOTAL ACT. EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. EN PROCESO</th>
                <th>ACT. NO CUMPLIDAS</th>
                <th>% ACT. CUMPLIDAS</th>
                <th>% ACT. NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[3][$this->tmes].'</b></td>
                <td><b>'.$regresion[7][$this->tmes].'</b></td>
                <td><b>'.($regresion[2][$this->tmes]-($regresion[7][$this->tmes]+$regresion[3][$this->tmes])).'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$this->tmes].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$this->tmes].'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }

    /*===== CONSOLIDADO NACIONAL =====*/
    /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN - REGIONAL---*/
    public function tabla_regresion_lineal_nacional_total(){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0;
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evalinstitucional->nro_operaciones_programadas_nacional($i,4);
        if(count($programado)!=0){
          $total=$total+$programado[0]['total'];
        }
      }

      for ($i=0; $i <=4; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// % prog 
        $tr[5][$i]=0; /// % cump 
      }

      for ($i=1; $i <=4; $i++) {
        $valor=$valor=$this->obtiene_datos_evaluacíon_nacional($i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Prog
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Cumplidas
        }
      }

    return $tr;
    }

    /*--- REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE - REGIONAL ---*/
    public function tabla_regresion_lineal_nacional(){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$this->tmes; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_nacional($i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_nacional($i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }


    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - REGIONAL ---*/
    public function obtiene_datos_evaluacíon_nacional($trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalinstitucional->nro_operaciones_programadas_nacional($i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_nacional($i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_nacional($i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
  /*================*/


    /*===== CONSOLIDADO REGIONAL =====*/
    /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN - REGIONAL---*/
    public function tabla_regresion_lineal_regional_total($dep_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0;
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evalinstitucional->nro_operaciones_programadas_regional($dep_id,$i,4);
        if(count($programado)!=0){
          $total=$total+$programado[0]['total'];
        }
      }

      for ($i=0; $i <=4; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// % prog 
        $tr[5][$i]=0; /// % cump 
      }

      for ($i=1; $i <=4; $i++) {
        $valor=$valor=$this->obtiene_datos_evaluacíon_regional($dep_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Prog
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Cumplidas
        }
      }

    return $tr;
    }

    /*--- REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE - REGIONAL ---*/
    public function tabla_regresion_lineal_regional($dep_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$this->tmes; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_regional($dep_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_regional($dep_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }


    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - REGIONAL ---*/
    public function obtiene_datos_evaluacíon_regional($dep_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalinstitucional->nro_operaciones_programadas_regional($dep_id,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_regional($dep_id,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_regional($dep_id,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
  /*================*/

    /*===== CONSOLIDADO DISTRITAL =====*/
    /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN - DISTRITAL---*/
    public function tabla_regresion_lineal_distrital_total($dist_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0;
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evalinstitucional->nro_operaciones_programadas_distrital($dist_id,$i,4);
        if(count($programado)!=0){
          $total=$total+$programado[0]['total'];
        }
      }

      for ($i=0; $i <=4; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// % prog 
        $tr[5][$i]=0; /// % cump 
      }

      for ($i=1; $i <=4; $i++) {
        $valor=$valor=$this->obtiene_datos_evaluacíon_distrital($dist_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Prog
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Cumplidas
        }
      }

    return $tr;
    }

    /*--- REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE - DISTRITAL ---*/
    public function tabla_regresion_lineal_distrital($dist_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$this->tmes; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_distrital($dist_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_distrital($dist_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }


    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - DISTRITAL ---*/
    public function obtiene_datos_evaluacíon_distrital($dist_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;$total_programado=0; $total_ejecutado=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalinstitucional->nro_operaciones_programadas_distrital($dist_id,$i,4);
        $suma_programado=$this->model_evalinstitucional->suma_operaciones_programadas_distrital($dist_id,$i); /// suma meta trimestral

        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($suma_programado)!=0){
          $total_programado=$total_programado+$suma_programado[0]['suma_programado'];
        }

        if(count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_distrital($dist_id,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalinstitucional->list_operaciones_evaluadas_unidad_trimestre_tipo_distrital($dist_id,$i,$tipo_evaluacion,4));
        }

        $suma_evaluado=$this->model_evalinstitucional->suma_operaciones_ejecutadas_distrital($dist_id,$i);

        if(count($suma_evaluado)!=0){
          $total_ejecutado=$total_ejecutado+$suma_evaluado[0]['suma_evaluado'];
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      $vtrimestre[3]=$total_programado; /// suma meta trimestre Programado
      $vtrimestre[4]=$total_ejecutado; /// suma meta trimestre Ejecutado

      return $vtrimestre;
    }
  /*================*/


  ////================== PARAMETROS DE EFICACIA 

    /*---- matriz parametros de eficacia Institucional ----*/
    public function matriz_eficacia_institucional(){
      $regionales=$this->model_proyecto->list_departamentos();
      
      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($regionales as $row){
        $eval=$this->eficacia_por_regional($row['dep_id']); /// Eficacia
        $eficacia=$eval[5][$this->tmes];
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($regionales))*100),2);
      }

      return $par;
    }

  /*---- matriz parametros de eficacia Distrito ----*/
    public function matriz_eficacia_distrital($dist_id){
      $unidades=$this->model_evalinstitucional->list_unidades_organizacionales(1,$dist_id);
      
      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($unidades as $row){
        $eval=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
        $eficacia=$eval[5][$this->tmes];
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($unidades))*100),2);
      }

      return $par;
    }

    /*---- matriz parametros de eficacia Regional ----*/
    public function matriz_eficacia_regional($dep_id){
      $distritales=$this->model_evalinstitucional->list_unidades_organizacionales(0,$dep_id);
     // $distritales=$this->model_evalinstitucional->get_distritales($dep_id);
      
      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($distritales as $row){
        $eval=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
        //$eval=$this->tabla_regresion_lineal_distrital($row['dist_id']); /// Eficacia
        $eficacia=$eval[5][$this->tmes];
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($distritales))*100),2);
      }

      return $par;
    }

    /*----- Parametros de Eficacia Concolidado por Unidad -----*/
    public function parametros_eficacia($matriz,$tp_rep){
      if($tp_rep==1){ //// Normal
        $class='class="table table-bordered" align=center style="width:60%;"';
        $div='<div id="parametro_efi" style="width: 600px; height: 400px; margin: 0 auto"></div>';

      }
      else{ /// Impresion
        $class='class="change_order_items" border=1 align=center style="width:100%;"';
        $div='<div id="parametro_efi_print" style="width: 650px; height: 330px; margin: 0 auto"></div>';
      }
     // $nro=$matriz;
      $tabla='';
      $tabla .='<table '.$class.'>
                  <tr>
                    <td>
                      '.$div.'
                    </td>
                  </tr>
                  <tr>
                  <td>
                      <table '.$class.'>
                        <thead>
                          <tr>
                            <th style="width: 33%"><center><b>TIPO DE CALIFICACI&Oacute;N</b></center></th>
                            <th style="width: 33%"><center><b>PARAMETRO</b></center></th>
                            <th style="width: 33%"><center><b>NRO DE UNIDADES</b></center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>INSATISFACTORIO</td>
                            <td>0% a 75%</td>
                            <td align="center"><a class="btn btn-danger" style="width: 100%" align="left" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                          </tr>
                          <tr>
                            <td>REGULAR</td>
                            <td>75% a 90% </td>
                            <td align="center"><a class="btn btn-warning" style="width: 100%" align="left" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                          </tr>
                          <tr>
                            <td>BUENO</td>
                            <td>90% a 99%</td>
                            <td align="center"><a class="btn btn-info" style="width: 100%" align="left" title="'.$matriz[3][2].' Unidades/Proyectos">'.$matriz[3][2].'</a></td>
                          </tr>
                          <tr>
                            <td>OPTIMO </td>
                            <td>100%</td>
                            <td align="center"><a class="btn btn-success" style="width: 100%" align="left" title="'.$matriz[4][2].' Unidades/Proyectos">'.$matriz[4][2].'</a></td>
                          </tr>
                          <tr>
                            <td colspan=2 align="left"><b>TOTAL: </b></td>
                            <td align="center"><b>'.($matriz[1][2]+$matriz[2][2]+$matriz[3][2]+$matriz[4][2]).'</b></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
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
    /*======================================================================================*/

}