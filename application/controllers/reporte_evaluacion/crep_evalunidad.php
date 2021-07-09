<?php
class Crep_evalunidad extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('ejecucion/model_seguimientopoa');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad

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
            $this->verif_mes=$this->session->userdata('mes_actual');
            $this->mes = $this->mes_nombre();
            $this->load->library('seguimientopoa');
        }
        else{
            redirect('/','refresh');
        }
    }


    // Modulo Evaluacion POA
    public function evaluacion_poa_unidad($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($proyecto)!=0){
        redirect('eval/eval_unidad_gcorriente/'.$proy_id.'');
/*        if($proyecto[0]['tp_id']==1){ //// Proyecto de Inversion
          redirect('eval/eval_unidad_pinversion/'.$proy_id.'');
        }
        else{ //// Gasto Corriente
          redirect('eval/eval_unidad_gcorriente/'.$proy_id.'');
        }*/
      }
      else{
        echo "Error !!!";
      }
    }


    // Modulo Evaluacion POA - Gasto Corriente
    public function evaluacion_unidad_gcorriente($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($data['proyecto'])!=0){
        $data['menu']=$this->menu(4); //// genera menu  
        $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

        $data['tit_menu']='EVALUACI&oacute;N POA';
        $data['tit']='<li>Evaluaci&oacute;n POA</li><li>Actividad</li>';
        
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        
        /*------ titulo ------*/
        $data['titulo']=
          '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>'.$data['proyecto'][0]['tipo_adm'].' : </small><b>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</b></h1>
          <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>';

        if($data['proyecto'][0]['tp_id']==1){
          $data['titulo']=
          '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>PROYECTO : </small><b>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' 000 - '.$data['proyecto'][0]['proy_nombre'].'</b></h1>
          <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>';
        }
        /*-------------------*/
        

         /*--- Regresion lineal trimestral ---*/
        $data['cabecera2']=$this->cabecera_seguimiento($data['proyecto'],2);
        $data['tabla']=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
        $data['tabla_regresion']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
        $data['tabla_regresion_impresion']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion Impresion

        /*--- grafico Pastel trimestral ---*/
        $data['tabla_pastel_todo']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
        $data['tabla_pastel_todo_impresion']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

        /*--- Regresion lineal Gestion */
        $data['cabecera3']=$this->cabecera_seguimiento($data['proyecto'],3);
        $data['tabla_gestion']=$this->tabla_regresion_lineal_unidad_total($proy_id); /// Matriz para el grafico Total Gestion
        $data['tabla_regresion_total']=$this->tabla_acumulada_evaluacion_unidad($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion Vista
        $data['tabla_regresion_total_impresion']=$this->tabla_acumulada_evaluacion_unidad($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion

        $data['calificacion']='<div id="eficacia">'.$this->calificacion_eficacia($data['tabla'][5][$this->tmes]).'</div><div id="efi"></div>'; /// calificacion

        /// SERVICIOS
        $data['mis_servicios']=$this->mis_servicios(1,$proy_id); /// Lista de Subactividades
        $data['economia']=$this->economia($data['proyecto']); /// Economia
        $data['eficiencia']=$this->eficiencia($data['tabla'][5][$this->tmes],$data['economia'][3]); /// Eficiencia
        $data['matriz']=$this->matriz_eficacia_unidad($proy_id);
        $data['parametro_eficacia']=$this->parametros_eficacia_unidad($data['matriz'],$proy_id,1); /// Parametro de Eficacia

        $data['boton_reporte_indicadores']='
                <a href="javascript:abreVentana(\''.site_url("").'/rep_eficacia_unidad/'.$proy_id.'\');" class="btn btn-default" title="IMPRIMIR EVALUACIÓN POA">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>IMPRIMIR EVALUACI&Oacute;N PLAN OPERATIVO ANUAL POA</b>
                </a>'; /// Reporte Evaluacion (Trimestre vigente) POA

        $data['no_cumplido']=0;
        $data['en_proceso']=0;

        if($data['tabla'][2][$this->session->userData('trimestre')]!=0){
          $data['no_cumplido']=(100-($data['tabla'][5][$this->session->userData('trimestre')]+round((($data['tabla'][7][$this->session->userData('trimestre')]/$data['tabla'][2][$this->session->userData('trimestre')])*100),2)));
          $data['en_proceso']=round((($data['tabla'][7][$this->session->userData('trimestre')]/$data['tabla'][2][$this->session->userData('trimestre')])*100),2);
        }

        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_unidad', $data);
      }
      else{
        redirect('eval/mis_operaciones');
      }
    }


    /*--- REPORTE EVALUACION POR UNIDAD 2021---*/
    public function reporte_indicadores_unidad($proy_id){
      $proyecto=$this->model_proyecto->get_id_proyecto($proy_id);
      $nombre_proyecto=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
      
      if($proyecto[0]['tp_id']==4){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $nombre_proyecto=$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'];
      }

      $tabla=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
      $data['cabecera']=$this->cabecera_eficiencia($nombre_proyecto);
      $data['pie']='<hr>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->session->userData('sistema').'';
      $data['lista']=$this->mis_servicios(0,$proy_id);
      $data['eficacia']=$tabla[5][$this->tmes];
      $data['economia']=$this->economia($proyecto); /// Economia
      $data['eficiencia']=$this->eficiencia($tabla[5][$this->tmes],$data['economia'][3]); /// Eficiencia
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
     
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_evaluacion_eficiencia_por_unidad', $data);
    }


    /// Cabecera Reporte de eficiencia
    function cabecera_eficiencia($nombre_proyecto){
      $tabla='';
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
    
      $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:45%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                  </tr>
                  <tr>
                      <td style="width:50%;font-size: 7px;">&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
            <td style="width:80%;height: 5%;font-family: Arial;font-size: 20px;">
              <b>DETALLE DE AVANCE DE EVALUACI&Oacute;N POA</b><br>
              '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'
            </td>
            <td style="width:10%; text-align:center;">
            </td>
          </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
            <td style="width:1%;"></td>
            <td style="width:98%;height: 2%;">
              <div style="font-family: Arial;font-size: 13px;">'.$nombre_proyecto.'</div>
            </td>
            <td style="width:1%;"></td>
          </tr>
        </table>
        <hr>';

      return $tabla;
    }

    /*------- CABECERA REPORTE SEGUIMIENTO POA (GRAFICO)------*/
    function cabecera_seguimiento($proyecto,$tipo_titulo){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      /// tipo_titulo 1 : Seguimiento Mensual
      /// tipo_titulo 2 : Evaluacion por Trimestre
      /// tipo_titulo 3 : Evaluacion POA Gestion
      
      $nombre_proyecto=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];
      if($proyecto[0]['tp_id']==4){
        $nombre_proyecto=$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'];
      }

      $tit='';
      if($tipo_titulo==2){
        $tit='<td style="height: 35px;font-size: 18px;"><center><b>CUADRO EVALUACIÓN POA ACUMULADO</b> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</center></td>';
      }
      elseif($tipo_titulo==3){
        $tit='<td style="height: 35px;font-size: 23px;"><center><b>CUADRO EVALUACI&Oacute;N POA - GESTI&Oacute;N '.$this->gestion.'</b></center></td>';
      }

      $tabla='';
      $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:45%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                  </tr>
                  <tr>
                      <td style="width:50%;font-size: 7px;">&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                <td style="width:80%;">
                    <table align="center" border="0" style="width:100%;">
                        <tr style="font-family: Arial;">
                            '.$tit.'
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
            <td style="width:100%;height: 100%;">
              <div style="font-family: Arial;font-size: 15px;">'.$nombre_proyecto.'</div>
            </td>
          </tr>
        </table>';

      return $tabla;
    }

      


    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE 2021 POR UNIDAD -----*/
    public function update_evaluacion_trimestral(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        
        $componentes=$this->model_componente->lista_subactividad($proy_id);
        foreach($componentes as $rowc){
          $this->seguimientopoa->update_evaluacion_operaciones($rowc['com_id']);
        }
        
        $tabla='';
        $tabla.='
              <hr><h3><b>&nbsp;&nbsp;'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</b></h3><hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> EVALUACI&Oacute;N POA '.$trimestre[0]['trm_descripcion'].' '.$this->gestion.' ACTUALIZADO !!!</2> 
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


    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE 2021 INSTITUCIONAL -----*/
    public function update_evaluacion_trimestral_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        $this->seguimientopoa->update_evaluacion_poa_regional($dep_id,$tp_id);
        
        $tabla='';
        $tabla.='
              <hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> EVALUACI&Oacute;N POA '.$trimestre[0]['trm_descripcion'].' '.$this->gestion.' ACTUALIZADO !!!</2> 
              </div>
              <hr>
              <p>
                <div id="butt" align="right">
                  <a href="'.site_url("").'/menu_eval_poa" class="btn btn-default" title="SALIR">
                  <img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="25" HEIGHT="25"/> SALIR</a>

                  <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_unidad/'.$dep_id.'/0/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/> VER CUMPLIMIENTO POR UNIDADES</a>
                </div>
              </p>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


 public function reporte_indicadores_unidadd($proy_id){
   $tp_id = 4;
        $regionales=$this->model_proyecto->list_departamentos();
        echo count($regionales)."<br>";
        foreach($regionales as $row){
          $this->seguimientopoa->update_evaluacion_poa_regional($row['dep_id'],$tp_id);
          echo $row['dep_id'].'--'.$row['dep_departamento'].'<br>';
        }

       /* $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_regional(7,4);
      foreach($unidades as $row){
        $componentes=$this->model_componente->lista_subactividad($row['proy_id']);
        foreach($componentes as $rowc){
          $this->seguimientopoa->update_evaluacion_operaciones($rowc['com_id']);
        }
        echo $row['proy_id'].'--'.$row['actividad']."<br>";
      }*/
 }




    /*---- matriz parametros de eficacia Unidad ----*/
    public function matriz_eficacia_unidad($proy_id){
      $componentes=$this->model_componente->proyecto_componente($proy_id); 
      
      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($componentes as $rowc){
        $eval=$this->tabla_regresion_lineal_servicio($rowc['com_id']);
        $eficacia=$eval[5][$this->tmes];
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($componentes))*100),2);
      }

      return $par;
    }

    /*----- Parametros de Eficacia Concolidado por Unidad -----*/
    public function parametros_eficacia_unidad($matriz,$proy_id,$tp_rep){
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
                            <td align="center" ><a class="btn btn-danger" style="width: 100%" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                          </tr>
                          <tr>
                            <td>REGULAR</td>
                            <td>75% a 90% </td>
                            <td align="center" ><a class="btn btn-warning" style="width: 100%" align="center" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                          </tr>
                          <tr>
                            <td>BUENO</td>
                            <td>90% a 99%</td>
                            <td align="center" ><a class="btn btn-info" style="width: 100%" align="center" title="'.$matriz[3][2].' Unidades/Proyectos">'.$matriz[3][2].'</a></td>
                          </tr>
                          <tr>
                            <td>OPTIMO </td>
                            <td>100%</td>
                            <td align="center" ><a class="btn btn-success" style="width: 100%" align="center" title="'.$matriz[4][2].' Unidades/Proyectos">'.$matriz[4][2].'</a></td>
                          </tr>
                          <tr>
                            <td colspan=2 align="center"><b>TOTAL SERVICIOS : </b></td>
                            <td align="center"><b>'.($matriz[1][2]+$matriz[2][2]+$matriz[3][2]+$matriz[4][2]).'</b></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';

      return $tabla;
    }


    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia){
      $tabla='';
      $tp='danger';
      $titulo='ERROR EN LOS VALORES';
      if($eficacia<=75){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 75%)';} /// Insatisfactorio - Rojo
      if ($eficacia > 75 & $eficacia <= 90){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (75% - 90%)';} /// Regular - Amarillo
      if($eficacia > 90 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (90% - 99%)';} /// Bueno - Azul
      if($eficacia > 99 & $eficacia <= 102){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

      $tabla.='<h4 class="alert alert-'.$tp.'" style="font-family: Arial;" align="center"><b>'.$titulo.'</b></h4>';

      return $tabla;
    }

    
    /*------ eficiencia ------*/
    public function eficiencia($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }

    /*------ Economia ------*/
    public function economia($proyecto){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado($proyecto[0]['aper_id'],10000); /// suma de Partidas por defecto al trimeste actual
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_unidad($proyecto[0]['proy_id']); //// Presupuesto Certificado al trimestre vigente
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_certificacion->monto_total_programado_trimestre($proyecto[0]['aper_id']); //// Presupuesto Asignado POA por trimestre
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      
      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0;
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }

    /*--------- Mis Servicios -------------*/
    public function mis_servicios($tp_rep,$proy_id){
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); 
      $componentes=$this->model_componente->proyecto_componente($proy_id);           
      $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $det='';
      } 
      else{ /// Impresion
        $tab='border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align=center';
        $det='
        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.- DETALLE (%) DE CUMPLIMIENTO DE UNIDADES DEPENDIENTES</b>
        </div>';
      }

      $tit='SUBACTIVIDAD';
      if($proyecto[0]['tp_id']==1){
        $tit='UNIDAD RESPONSABLE';
      }
      $tabla.='
        '.$det.'
        <table '.$tab.'>
          <thead>
          <tr align=center bgcolor=#f4f4f4>
            <th style="width:3%;height:2%;">#</th>
            <th style="width:25%;">'.$tit.'</th>
            <th style="width:8%;">TOTAL PROGRAMADO</th>
            <th style="width:8%;">TOTAL EVALUADO</th>
            <th style="width:8%;">TOTAL CUMPLIDOS</th>
            <th style="width:8%;">EN PROCESO</th>
            <th style="width:8%;">NO CUMPLIDOS</th>
            <th style="width:10%;">% CUMPLIDO</th>
            <th style="width:10%;">% NO CUMPLIDO</th>
          </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($componentes as $rowc){
            $eval=$this->tabla_regresion_lineal_servicio($rowc['com_id']);
            $nro++;
            $tabla.='<tr>';
              $tabla.='<td style="height:2%;" align=center>'.$nro.'</td>';
              $tabla.='<td>'.$rowc['com_componente'].'</td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[3][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[7][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.($eval[2][$this->tmes]-($eval[7][$this->tmes]+$eval[3][$this->tmes])).'</b></td>';
              if($tp_rep==1){
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eval[5][$this->tmes].'%</b></button></td>';
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$eval[6][$this->tmes].'%</b></button></td>';
              }
              else{
                $tabla.='<td align=right style="font-size: 8px;"><b>'.$eval[5][$this->tmes].'%</b></td>';
                $tabla.='<td align=right style="font-size: 8px;"><b>'.$eval[6][$this->tmes].'%</b></td>';
              }
              
            $tabla.='</tr>';
          }
        $tabla.='
          </tbody>
        </table>';
      return $tabla;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_servicio($com_id){
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
        $valor=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 - SERVICIO -------*/
    public function obtiene_datos_evaluacíon_servicio($com_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evaluacion->nro_operaciones_programadas($com_id,$i);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }


    /*------ TABLA ACUMULADA EVALUACIÓN 2020 -------*/
    public function tabla_acumulada_evaluacion_unidad($regresion,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>NRO. OPE. PROGRAMADOS EN EL TRIMESTRE</b>';
      $tit[3]='<b>NRO. OPE. CUMPLIDOS EN EL TRIMESTRE</b>';
      $tit[4]='<b>NRO. OPE. NO CUMPLIDOS</b>';
      $tit[5]='<b>% CUMPLIDOS</b>';
      $tit[6]='<b>% NO CUMPLIDOS</b>';

      $tit_total[2]='<b>NRO. OPE. PROGRAMADOS AL TRIMESTRE</b>';
      $tit_total[3]='<b>NRO. OPE. CUMPLIDOS AL TRIMESTRE</b>';
      $tit_total[4]='<b>% OPE. PROGRAMADOS AL TRIMESTRE</b>';
      $tit_total[5]='<b>% OPE. CUMPLIDOS AL TRIMESTRE</b>';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 style="width:100%;"';
      }



      if($tp_graf==1){ // pastel : Programado-Cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center>
                <th>NRO. OPE. PROGRAMADAS</th>
                <th>METAS EVALUADAS</th>
                <th>OPE. CUMPLIDAS</th>
                <th>OPE. NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
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
              <tr >
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
                $tabla.='<tr bgcolor='.$color.' >
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
        <h4><b>'.$regresion[5][$this->tmes].'%</b> CUMPLIMIENTO DE '.$regresion[1][$this->tmes].' CON RESPECTO A LA GESTIÓN '.$this->gestion.'</h4>
        <table '.$tab.'>
          <thead>
              <tr>
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
              <tr align=center >
                <th>NRO. OPE. PROGRAMADAS</th>
                <th>NRO. OPE. EVALUADAS</th>
                <th>NRO. OPE. CUMPLIDAS</th>
                <th>NRO. OPE. EN PROCESO</th>
                <th>NRO. OPE. NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
              </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[2][$this->tmes].'</b></td>
                <td><b>'.$regresion[3][$this->tmes].'</b></td>
                <td><b>'.$regresion[7][$this->tmes].'</b></td>
                <td><b>'.($regresion[2][$this->tmes]-($regresion[7][$this->tmes]+$regresion[3][$this->tmes])).'</b></td>
                <td><b>'.$regresion[5][$this->tmes].'%</b></td>
                <td><b>'.$regresion[6][$this->tmes].'%</b></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }

    /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN ---*/
    public function tabla_regresion_lineal_unidad_total($proy_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0;
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evalunidad->nro_operaciones_programadas($proy_id,$i);
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
        $valor=$this->obtiene_datos_evaluacíon($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Prog
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Cumplidas
        }
      }

    return $tr;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_unidad($proy_id){
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
        $valor=$this->obtiene_datos_evaluacíon($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon($proy_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 -------*/
    public function obtiene_datos_evaluacíon($proy_id,$trimestre,$tipo_evaluacion){
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