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

        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $data['titulo']=
          '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>'.$data['proyecto'][0]['tipo_adm'].' : </small><b>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</b></h1>
          <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>';

        if($data['proyecto'][0]['tp_id']==1){
          $data['titulo']=
          '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>PROYECTO : </small><b>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' 000 - '.$data['proyecto'][0]['proy_nombre'].'</b></h1>
          <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>';
        }
        
        $data['tit']='<li>Evaluaci&oacute;n POA</li><li>Actividad</li>';

        $data['tabla']=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
        
        $data['no_cumplido']=0;
        $data['en_proceso']=0;

        if($data['tabla'][2][$this->session->userData('trimestre')]!=0){
          $data['no_cumplido']=(100-($data['tabla'][5][$this->session->userData('trimestre')]+round((($data['tabla'][7][$this->session->userData('trimestre')]/$data['tabla'][2][$this->session->userData('trimestre')])*100),2)));
          $data['en_proceso']=round((($data['tabla'][7][$this->session->userData('trimestre')]/$data['tabla'][2][$this->session->userData('trimestre')])*100),2);
        }
        
        
        $data['tabla_gestion']=$this->tabla_regresion_lineal_unidad_total($proy_id); /// Tabla para el grafico Total Gestion

        $data['tabla_regresion']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
        $data['tabla_regresion_total']=$this->tabla_acumulada_evaluacion_unidad($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
        $data['tabla_pastel']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],1,1); /// Tabla que muestra el acumulado por trimestres Pastel
        $data['tabla_pastel_todo']=$this->tabla_acumulada_evaluacion_unidad($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo

        /// SERVICIOS
        $data['mis_servicios']=$this->mis_servicios(1,$proy_id);
        $data['economia']=$this->economia($data['proyecto']); /// Economia
        $data['eficiencia']=$this->eficiencia($data['tabla'][5][$this->tmes],$data['economia'][3]); /// Eficiencia
        $data['calificacion']=$this->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// calificacion
        $data['matriz']=$this->matriz_eficacia_unidad($proy_id);
        $data['parametro_eficacia']=$this->parametros_eficacia_unidad($data['matriz'],$proy_id,1); /// Parametro de Eficacia

        $data['print_tabla']=$this->print_proyectos_unidad($proy_id,$this->tabla_acumulada_evaluacion_unidad($data['tabla'],2,2),$this->tabla_acumulada_evaluacion_unidad($data['tabla_gestion'],3,2),$this->mis_servicios(2,$proy_id),$this->economia($data['proyecto']),$data['tabla'][5][$this->tmes],$data['eficiencia'],$data['calificacion'],$this->parametros_eficacia_unidad($data['matriz'],$proy_id,2));

        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_unidad', $data);
      }
      else{
        redirect('eval/mis_operaciones');
      }
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

      $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';

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
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
      }

      $tit='SUBACTIVIDAD';
      if($proyecto[0]['tp_id']==1){
        $tit='UNIDAD RESPONSABLE';
      }
      $tabla.='
        <table '.$tab.'>
          <thead>
          <tr>
            <th style="width:3%;">#</th>
            <th style="width:50%;">'.$tit.'</th>
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
              $tabla.='<td>'.$nro.'</td>';
              $tabla.='<td>'.$rowc['com_componente'].'</td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[3][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[7][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.($eval[2][$this->tmes]-($eval[7][$this->tmes]+$eval[3][$this->tmes])).'</b></td>';
              $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eval[5][$this->tmes].'%</b></button></td>';
              $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$eval[6][$this->tmes].'%</b></button></td>';
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


    /*--------- Imprime Evaluacion Consolidado Unidad -------------*/
    public function print_proyectos_unidad($proy_id,$regresion,$regresion_total,$mis_servicios,$economia,$eficacia,$eficiencia,$calificacion,$parametro){
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);

      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      //$dist=$this->model_evalregional->get_dist($dist_id);
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
                                <td colspan="2" style="width:100%; height: 2.8%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.8%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.8%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">';
                                $tabla.='<td style="height: 2.8%"><b>';
                                  if($proyecto[0]['tp_id']==1){
                                    $tabla.='PROY. INV. ';
                                  }
                                  else{
                                    $tabla.='ACTIVIDAD';
                                  }
                                  $tabla.='</b></td>';
                                  if($proyecto[0]['tp_id']==1){
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                                  }
                                  else{
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                                  }
                              $tabla.='
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4.5pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>';

        $tabla.=$calificacion;

        $tabla .='<table class="change_order_items" border=0.5 style="width:100%;">
                    <tr>
                      <td>
                        <div id="regresion_impresion" style="width: 500px; height: 235px; margin: 0 auto"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        '.$regresion.'
                      </td>
                    </tr>
                  </table>';
        $tabla .='<table class="change_order_items" border=0.5 style="width:100%;">
                    <tr>
                      <td>
                        <div id="regresion_gestion_print" style="width: 500px; height: 235px; margin: 0 auto"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        '.$regresion_total.'
                      </td>
                    </tr>
                </table>

                <div class="saltopagina"></div>';
      $tabla .='
            <div class="verde"></div>
            <div class="blanco"></div>
            <table class="page_header" border="0" style="width: 100%;>
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
                                <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">';
                                $tabla.='<td style="height: 2.5%"><b>';
                                  if($proyecto[0]['tp_id']==1){
                                    $tabla.='PROY. INV. ';
                                  }
                                  else{
                                    $tabla.='ACTIVIDAD';
                                  }
                                  $tabla.='</b></td>';
                                  if($proyecto[0]['tp_id']==1){
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                                  }
                                  else{
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                                  }
                              $tabla.='
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>';

        $tabla .='
          <table class="change_order_items" border=0 style="width:100%;">
            <tr><td align=center><b><br><div style="font-size: 10pt;">MIS SERVICIOS</div><br>EVALUACI&Oacute;N ACUMULADO AL '.$trimestre[0]['trm_descripcion'].'</b></td></tr>
            <tr>
              <td>
                '.$mis_servicios.'
              </td>
            </tr>
          </table><br>

          <b>CUADRO DE INDICADORES</b>
          
          <table class="change_order_items" border=1 style="width:100%;">
            <thead>
              <tr>
                <th style="width:33%;">(%) EFICACIA</th>
                <th style="width:33%;">(%) ECONOMIA</th>
                <th style="width:33%;">(%) EFICIENCIA</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div style="font-size: 30pt;" align=center><b>'.$eficacia.' % </b></div></td>
                <td><div style="font-size: 30pt;" align=center><b>'.$economia[3].' % </b></div></td>
                <td><div style="font-size: 30pt;" align=center><b>'.$eficiencia.' % </b></div></td>
              </tr>
            </tbody>
          </table>


            <div class="saltopagina"></div>


            <div class="verde"></div>
            <div class="blanco"></div>
            <table class="page_header" border="0" style="width: 100%;>
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
                                <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">';
                                $tabla.='<td style="height: 2.5%"><b>';
                                  if($proyecto[0]['tp_id']==1){
                                    $tabla.='PROY. INV. ';
                                  }
                                  else{
                                    $tabla.='ACTIVIDAD';
                                  }
                                  $tabla.='</b></td>';
                                  if($proyecto[0]['tp_id']==1){
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                                  }
                                  else{
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                                  }
                              $tabla.='
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        '.$parametro.'';
          ?>
          </html>
          <?php
    return $tabla;
    } 


    /*------ TABLA ACUMULADA EVALUACIÓN 2020 -------*/
    public function tabla_acumulada_evaluacion_unidad($regresion,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>NRO. ACT. PROGRAMADOS EN EL TRIMESTRE</b>';
      $tit[3]='<b>NRO. ACT. CUMPLIDOS EN EL TRIMESTRE</b>';
      $tit[4]='<b>NRO. ACT. NO CUMPLIDOS</b>';
      $tit[5]='<b>% CUMPLIDOS</b>';
      $tit[6]='<b>% NO CUMPLIDOS</b>';

      $tit_total[2]='<b>NRO. ACT. PROGRAMADOS AL TRIMESTRE</b>';
      $tit_total[3]='<b>NRO. ACT. CUMPLIDOS AL TRIMESTRE</b>';
      $tit_total[4]='<b>% ACT. PROGRAMADOS AL TRIMESTRE</b>';
      $tit_total[5]='<b>% ACT. CUMPLIDOS AL TRIMESTRE</b>';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
      }

      if($tp_graf==1){ // pastel : Programado-Cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center>
                <th>NRO. ACT. PROGRAMADAS</th>
                <th>METAS EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. NO CUMPLIDAS</th>
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
              <tr>
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
                <th>NRO. ACT. PROGRAMADAS</th>
                <th>NRO. ACT. EVALUADAS</th>
                <th>NRO. ACT. CUMPLIDAS</th>
                <th>NRO. ACT. EN PROCESO</th>
                <th>NRO. ACT. NO CUMPLIDAS</th>
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
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$this->tmes].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$this->tmes].'%</b></button></td>
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