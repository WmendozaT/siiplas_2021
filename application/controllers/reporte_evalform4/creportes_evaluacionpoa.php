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
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020

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
            $this->mes_sistema=$this->session->userData('mes'); /// mes sistema
            $this->mes = $this->mes_nombre();
            $this->load->library('reportes_evaluacionpoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_indicadores_unidades($dep_id,$dist_id,$tp_id){
      
      if($dep_id==0){ /// Institucional
          $titulo_rep='PARAMETROS DE CUMPLIMIENTO POR REGIONAL';
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral('',2,$titulo_rep);
          $lista=$this->reportes_evaluacionpoa->pdf_lista_parametro_cumplimiento_regional();
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $titulo_rep='PARAMETROS DE CUMPLIMIENTO POR UNIDAD';
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dep_id,0,$titulo_rep);
          $lista=$this->reportes_evaluacionpoa->pdf_lista_parametro_cumplimiento_unidad(0,$dep_id);
        }
        elseif($dep_id!=0 & $dist_id!=0){ /// Distrital
          $titulo_rep='PARAMETROS DE CUMPLIMIENTO POR UNIDAD';
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dist_id,1,$titulo_rep);
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
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional($tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_institucional($lista_programas);
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($dep_id,$tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_regional($lista_programas);
        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($dist_id,$tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_distrital($lista_programas);
        }

        $tabla_programa=$this->reportes_evaluacionpoa->tabla_apertura_programatica($matriz_programas,count($lista_programas));
        

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
    public function reporte_categoria_programatica($dep_id,$dist_id,$tp_id){
      $titulo_rep='PARAMETROS DE CUMPLIMIENTO POR PROGRAMAS';
      if($dep_id==0){ /// Institucional
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral(0,2,$titulo_rep);
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional($tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_institucional($lista_programas);
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dep_id,0,$titulo_rep);
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($dep_id,$tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_regional($lista_programas);
        }
        elseif($dep_id!=0 & $dist_id!=0){ /// Distrital
          $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($dist_id,1,$titulo_rep);
          $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($dist_id,$tp_id);
          $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_distrital($lista_programas);
        }

        $data['operaciones']=$this->reportes_evaluacionpoa->tabla_apertura_programatica_reporte($matriz_programas,count($lista_programas));
        $data['pie']=$this->reportes_evaluacionpoa->pie_evaluacionpoa();

      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_indicadores_parametros', $data);
    }



  /*-- GET EJECUCION CERTPOA A NIVEL NACIONAL, REGIONAL DISTRITAL --*/
    public function get_ejecucion_certpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $tp_id = $this->security->xss_clean($post['tp_id']); /// tipo id
        
        $matriz='No encontrado !!';
        $tabla='No encontrado !!';

        if($dep_id==0){ /// Institucional
          $titulo_regional='INSTITUCIONAL - CNS /'.$this->gestion.'';

          $ins_programado = $this->model_insumo->get_mes_programado_insumo_institucional_menos10000(); /// INSUMO PROGRAMADO CONSOLIDADO
          $ins_certificado = $this->model_insumo->get_mes_certificado_insumo_institucional_menos10000(); /// INSUMO CERTIFICADO  

          $form4_programado = $this->model_proyecto->temporalidad_prog_form4_institucional(); /// FORM4 PROGRAMADO CONSOLIDADO
          $form4_ejec = $this->model_proyecto->temporalidad_ejec_form4_institucional(); /// FORM 4 EJECUTADO

          $consolidado_partidas=$this->model_certificacion->get_ppto_certpoa_partidas_institucional($tp_id);
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
          $regional=$this->model_proyecto->get_departamento($dep_id);
          $titulo_regional=strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';

          $ins_programado = $this->model_insumo->get_mes_programado_insumo_regional_menos10000($dep_id); /// INSUMO PROGRAMADO CONSOLIDADO
          $ins_certificado = $this->model_insumo->get_mes_certificado_insumo_regional_menos10000($dep_id); /// INSUMO CERTIFICADO  

          $form4_programado = $this->model_proyecto->temporalidad_prog_form4_regional($dep_id); /// FORM4 PROGRAMADO CONSOLIDADO
          $form4_ejec = $this->model_proyecto->temporalidad_ejec_form4_regional($dep_id); /// FORM 4 EJECUTADO

          $consolidado_partidas=$this->model_certificacion->get_ppto_certpoa_partidas_regional($dep_id,$tp_id);
        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
          $distrital=$this->model_proyecto->dep_dist($dist_id);
          $titulo_regional=strtoupper($distrital[0]['dist_distrital']).' / '.$this->gestion.'';

          $ins_programado = $this->model_insumo->get_mes_programado_insumo_distrital_menos10000($dist_id); /// INSUMO PROGRAMADO CONSOLIDADO
          $ins_certificado = $this->model_insumo->get_mes_certificado_insumo_distrital_menos10000($dist_id); /// INSUMO CERTIFICADO        

          $form4_programado = $this->model_proyecto->temporalidad_prog_form4_distrital($dist_id); /// FORM4 PROGRAMADO CONSOLIDADO
          $form4_ejec = $this->model_proyecto->temporalidad_ejec_form4_distrital($dist_id); /// FORM 4 EJECUTADO

          $consolidado_partidas=$this->model_certificacion->get_ppto_certpoa_partidas_distrital($dist_id,$tp_id);
        }


          for ($i=0; $i <=12 ; $i++) { 
            if($i==0){
              $prog_vector[$i]=$ins_programado[0]['total_programado'];
            }
            else{
              $prog_vector[$i]=$ins_programado[0]['prog_mes'.$i]; 
            }
          }

          if(count($ins_certificado)!=0){
            for ($i=0; $i <=12 ; $i++) { 
              if($i==0){
                $ejec_vector[$i]=$ins_certificado[0]['total_certificado'];
              }
              else{
                $ejec_vector[$i]=$ins_certificado[0]['ejec_mes'.$i]; 
              }
            }
          }
          else{
            for ($i=0; $i <=12 ; $i++) { 
              $ejec_vector[$i]=0;
            }
          }


          //// ----- Ejecucion de Certificacion POA
          $matriz_ppto=$this->matriz_consolidado_mensual($prog_vector,$ejec_vector); /// genera matriz
          $tabla_normal=$this->genera_tabla_temporalidad_prog_ejec($matriz_ppto,0,$dist_id,5); /// normal
          $tabla_impresion=$this->genera_tabla_temporalidad_prog_ejec($matriz_ppto,1,$dist_id,5); /// impresion
          //// ------------------------------------

          $suma_total_meta=0;
            for ($i=1; $i <=12 ; $i++) { 
              $suma_total_meta=$suma_total_meta+$form4_programado[0]['prog_mes'.$i];
            }
            //--
            for ($i=0; $i <=12 ; $i++) { 
              if($i==0){
                $prog_vector_form4[$i]=round($suma_total_meta,2);
              }
              else{
                $prog_vector_form4[$i]=round($form4_programado[0]['prog_mes'.$i],2); 
              }
            }

            if(count($form4_ejec)!=0){
              for ($i=0; $i <=12 ; $i++) { 
                if($i==0){
                  $ejec_vector_form4[$i]=0;
                }
                else{
                  $ejec_vector_form4[$i]=round($form4_ejec[0]['ejec_mes'.$i],2); 
                }
              }
            }
            else{
              for ($i=0; $i <=12 ; $i++) { 
                $ejec_vector_form4[$i]=0;
              }
            }

            //// ----- Ejecucion de Certificacion POA
            $matriz_form4=$this->matriz_consolidado_mensual($prog_vector_form4,$ejec_vector_form4); /// genera matriz form 4
            $tabla_normal_form4=$this->genera_tabla_temporalidad_prog_ejec($matriz_form4,0,$dist_id,4); /// normal
            $tabla_impresion_form4=$this->genera_tabla_temporalidad_prog_ejec($matriz_form4,1,$dist_id,4); /// impresion
            //// ------------------------------------


            $tabla='
            <div class="row">
            <div class="col-sm-12">
              <div id="cabecera_ejec" style="display: none">'.$this->cabecera_reporte_grafico($titulo_regional).'</div>
              <!-- well -->
              <div class="well">
                <!-- row -->
                <div class="row">
                  <!-- col -->
                  <div class="col-sm-12">
                    <!-- row -->
                    <div class="row">
        
                      <div class="col-md-6">
                        <div id="grafico_form5">
                          <center><div id="graf_form5" style="width: 880px; height: 500px; margin: 0 auto; text-align:center"></div></center>
                        </div>
                      </div>
                      <div class="col-md-6">

                      </div>
        
                      <div class="col-md-12">
                        '.$tabla_normal.'
                      </div>
                      
                      <div id="tabla_impresion_form5" style="display: none">
                        '.$tabla_impresion.'
                      </div>

                    </div>
                      <div align="right">
                        <button  onClick="imprimir_form5()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                    <!-- end row -->
                  </div>
                  <!-- end col -->
                </div>
                <!-- end row -->
              </div>
              <!-- end well -->
        
              <hr>
              <div class="well">
                <!-- row -->
                <div class="row">
                  <!-- col -->
                  <div class="col-sm-12">
                    <!-- row -->
                    <div class="row">
        
                      <div class="col-md-12">
                        <div id="grafico_form4">
                          <center><div id="graf_form4" style="width: 900px; height: 500px; margin: 0 auto; text-align:center"></div></center>
                        </div>
                      </div>
        
                      <div class="col-md-12">
                        '.$tabla_normal_form4.'
                      </div>
                      
                      <div id="tabla_impresion_form4" style="display: none">
                        '.$tabla_impresion_form4.'
                      </div>  

                    </div>

                      <div align="right">
                        <button  onClick="imprimir_form4()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>

                    <!-- end row -->
                  </div>
                  <!-- end col -->
                </div>
                <!-- end row -->
              </div>
              <!-- end well -->
            </div>
            </div>';

        $result = array(
          'respuesta' => 'correcto',
          'titulo_rep'=>$titulo_regional,
          'tabla'=>$tabla,
          'matriz_form5'=>$matriz_ppto,
          'matriz_form4'=>$matriz_form4,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


   /*---- CABECERA REPORTE OPERACIONES POR REGIONALES (GRAFICO)----*/
  function cabecera_reporte_grafico($titulo){
    $tabla='';

    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                        <td style="width:45%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                <table align="center" border="0" style="width:100%;">
                  <tr style="font-size: 23px;font-family: Arial;">
                    <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }

  /*---- Matriz consolidado mensual ----*/
  public function matriz_consolidado_mensual($vector_prog,$vector_ejec){

    /// Matriz Vacia
    for ($i=0; $i <=12 ; $i++) { 
      for ($j=0; $j <=6 ; $j++) { 
        $matriz[$i][$j]=0;
      }
    }
    /// ----------

      $suma_acumulado_prog=0; /// acumulado prog
      $suma_acumulado_ejec=0; /// acumulado ejec
      for ($i=0; $i <=12 ; $i++) { 
        $matriz[0][$i]=$vector_prog[$i]; /// Programado
        $matriz[1][$i]=$vector_ejec[$i]; /// Ejecutado
        $matriz[2][$i]=0; /// % Ejecucion mes
        /*if($matriz[0][$i]!=0){
          $matriz[2][$i]=round((($matriz[1][$i]/$matriz[0][$i])*100),2); /// % Ejecucion Mes
        }*/
        
        if($i!=0){
          $suma_acumulado_prog=$suma_acumulado_prog+$matriz[0][$i];
          $suma_acumulado_ejec=$suma_acumulado_ejec+$matriz[1][$i];

          $matriz[2][$i]=$suma_acumulado_prog; /// Acumulado Mensual Programado
          $matriz[3][$i]=$suma_acumulado_ejec; /// Acumulado Mensual Ejecutado

          $matriz[4][$i]=0;

          if($matriz[2][$i]!=0){
            $matriz[4][$i]=round((($matriz[3][$i]/$matriz[2][$i])*100),2); /// % Cumplimiento Mes
          }


          $matriz[5][$i]=0;
          $matriz[6][$i]=0;

          if($matriz[0][0]!=0){
            $matriz[5][$i]=round((($matriz[2][$i]/$matriz[0][0])*100),2); /// % Acumulado Mensual Programado
            $matriz[6][$i]=round((($matriz[3][$i]/$matriz[0][0])*100),2); /// % Acumulado Mensual Ejecutado
          }
          
        }
      }

      return $matriz;
  }

  /*---- Genera Tabla (Vista e impresion), distribucion de meses prog. y cert. ----*/
  public function genera_tabla_temporalidad_prog_ejec($matriz,$tipo_reporte,$aper_id,$formulario){
    //// tipo_reporte : 0 normal
    //// tipo_reporte : 1 impresion

    $tit1='PROGRAMADO';
    $tit2='EJECUTADO';
    $tit3='PROG. ACUMULADO';
    $tit4='EJEC. ACUMULADO';
    $tit5='% CUMP. MENSUAL';
    $tit6='% PROG. ACUMULADO';
    $tit7='% EJEC. ACUMULADO';
    if($formulario==5){
      $tit1='PPTO. PROGRAMADO';
      $tit2='PPTO. CERTIFICADO';
      $tit3='PPTO. PROG. ACUMULADO';
      $tit4='PPTO. CERT. ACUMULADO';
      $tit5='% CUMP. MENSUAL';
      $tit6='% PROG. ACUMULADO';
      $tit7='% EJEC. ACUMULADO';
    }


    $tabla='';
    $class='class="table table-bordered" style="width:100%;"';
    if($tipo_reporte==1){
      $class='class="change_order_items" border=1 style="width:100%;"';
      
    }

    $tabla.='
      <center>
      <table '.$class.'>
        <thead>
        <tr>
          <th style="width:1%;">'.$aper_id.'</th>
          <th style="width:6%;">ENE..</th>
          <th style="width:6%;">FEB.</th>
          <th style="width:6%;">MAR.</th>
          <th style="width:6%;">ABR.</th>
          <th style="width:6%;">MAY.</th>
          <th style="width:6%;">JUN.</th>
          <th style="width:6%;">JUL.</th>
          <th style="width:6%;">AGO.</th>
          <th style="width:6%;">SEPT.</th>
          <th style="width:6%;">OCT.</th>
          <th style="width:6%;">NOV.</th>
          <th style="width:6%;">DIC.</th>
        </tr>
        </thead>
        <tbody>
          <tr>
          <td title="PROGRAMADO MENSUAL">'.$tit1.'</td>';
          for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[0][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr>
          <td title="EJECUTADO MENSUAL">'.$tit2.'</td>';
         for ($i=1; $i <=12 ; $i++) { 
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[1][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          
          <tr>
          <td title="PROGRAMADO ACUMULADO MENSUAL">'.$tit3.'</td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[2][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr>
          <td title="EJECUTADO ACUMULADO MENSUAL">'.$tit4.'</td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[3][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor="#fcfde9">
          <td title="(%) CUMPLIMIENTO MENSUAL"><b>'.$tit5.'</b></td>';
          for ($i=1; $i <=12 ; $i++) { 
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[4][$i].'%</b></td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor=#e7f7f6>
          <td title="(%) PROGRAMADO ACUMULADO"><b>'.$tit6.'</b></td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[5][$i].'%</b></td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor=#e7f7f6>
          <td title="(%) EJECUTADO ACUMULADO"><b>'.$tit7.'</b></td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            } 
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[6][$i].'%</b></td>';
          }
          $tabla.='
            </tr>
          <tbody>
        </table>';

      return $tabla;
  }

    /*------ NOMBRE MES -------*/
    public function mes_nombre(){
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