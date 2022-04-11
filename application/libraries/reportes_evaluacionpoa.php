<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class reportes_evaluacionpoa extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('modificacion/model_modrequerimiento');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('programacion/model_producto');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas

        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('ejecucion/model_certificacion');

        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        //$this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        //$this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
       // $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->mes = $this->mes_nombre();
    }



    /*-- LISTA % CUMPLIMIENTO POR UNIDAD - para REGIONAL, DISTRITAL --*/
    public function pdf_lista_parametro_cumplimiento_unidad($tip_reg,$id){
      $tabla='';
      $unidades=$this->model_evalinstitucional->list_unidades_organizacionales($tip_reg,$id);

      $tabla.='
      <div style="font-size: 13px;font-family: Arial;height:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLE DE (%) CUMPLIMIENTO POR UNIDADES</div>
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
         <thead>
            <tr style="font-size: 9px;" align=center bgcolor="#f8f2f2">
              <th style="width:2%;height:15px;">#</th>
              <th style="width:15%;">DISTRITAL</th>
              <th style="width:20%;">GASTO CORRIENTE / PROY. INV.</th>
              <th style="width:8%;">METAS. PROGR.</th>
              <th style="width:8%;">METAS CUMP.</th>
              <th style="width:10%;">METAS NO CUMP.</th>
              <th style="width:10%;">% CUMPLIMIENTO</th>
              <th style="width:10%;">% NO CUMPLIDAS</th>
              <th style="width:10%;">% EJEC. PPTO.</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($unidades as $row){
            $eficacia=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
            $economia=$this->economia_por_unidad($row['aper_id'],$row['proy_id']); /// Economia

            $nro++;
            $tabla.='<tr style="font-size: 9px;" >';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:15%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:20%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
            $tabla.='<td style="width:8%;" align=right><b>'.$eficacia[2][$this->tmes].'</b></td>';
            $tabla.='<td style="width:8%;" align=right><b>'.$eficacia[3][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[4][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.(100-$eficacia[5][$this->tmes]).'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          </tbody>
        </table>';

      return $tabla;
    }


    /*-- LISTA % CUMPLIMIENTO POR REGIONAL para la INSTITUCIONAL--*/
    public function pdf_lista_parametro_cumplimiento_regional(){
      $tabla='';
      $regionales=$this->model_proyecto->list_departamentos();
      $eficacia_nacional=$this->tabla_regresion_lineal_nacional(); /// Eficacia
       $tabla.='
        <div style="font-size: 13px;font-family: Arial;height:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLE DE (%) CUMPLIMIENTO POR D.A.</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
         <thead>
            <tr style="font-size: 10px;" align=center bgcolor="#f8f2f2">
              <th style="width:2%;height:15px;">#</th>
              <th style="width:20%;">DIRECCIÓN ADMINISTRATIVA</th>
              <th style="width:12%;">METAS PROGRAMADAS</th>
              <th style="width:12%;">METAS CUMPLIDAS</th>
              <th style="width:12%;">METAS NO CUMPLIDAS</th>
              <th style="width:12%;">% CUMPLIMIENTO</th>
              <th style="width:12%;">% NO CUMPLIDO</th>
              <th style="width:12%;">% EJEC. PPTO.</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($regionales as $row){
            $eficacia=$this->eficacia_por_regional($row['dep_id']); /// Eficacia
            $economia=$this->economia_por_regional($row['dep_id']); /// Economia
          //  $eficiencia=$this->eficiencia_por_regional($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia
            $nro++;
            $tabla.='<tr style="font-size: 10px;">';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:20%;">'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[2][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[3][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[4][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:12%;" align=right><b>'.(100-$eficacia[5][$this->tmes]).'%</b></td>';
            $tabla.='<td style="width:12%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          </tbody>
        </table><br><br>
        <div style="font-size: 22px;font-family: Arial;height:20px;"><b>&nbsp;&nbsp;(%) CUMPLIMIENTO A NIVEL INSTITUCIONAL : '.$eficacia_nacional[5][$this->tmes].'%</b></div>';

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
        $tr[8][$i]=0; /// en proceso %
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

        $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
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



    /*--- TABLA APERTURA PROGRAMATICAS VISTA ---*/
    public function tabla_apertura_programatica($matriz,$nro){
      $tabla='';
      $tabla='
      <style type="text/css">
      table{font-size: 9.5px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
      }
      th{
        padding: 1.4px;
        text-align: center;
        font-size: 9.5px;
      }
      </style>';

      $tabla.='
        <div style="font-size: 25px;font-family: Arial;height:20px;"><b>CUADRO DE % CUMPLIMIENTO POR PROGRAMAS</b></div><br>
        <table class="table table-bordered" align=center style="width:90%;">
          <thead>
              <tr align=center bgcolor="#e9edec">
                <th>#</th>
                <th>APERTURA PROGRAM&Aacute;TICA</th>
                <th>DESCRIPCI&Oacute;N</th>
                <th>NRO. PROGRAMADAS</th>
                <th>NRO. EVALUADAS</th>
                <th>NRO. CUMP.</th>
                <th>NRO. NO CUMP.</th>
                <th>% CUMPLIMIENTO</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>';
              for ($i=1; $i <=$nro+1; $i++) { 
                $tabla.='<tr>';
                if($i==$nro+1){
                  $tabla.='<tr bgcolor=#e5ecef>';
                }
                
                for ($j=1; $j <=9; $j++) {
                  if($j==8 || $j==9){
                    if($j==8){
                      $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[$i][$j].'%</b></button></td>';
                    }
                    else{
                      $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$matriz[$i][$j].'%</b></button></td>';
                    }
                    
                  }
                  elseif($j==1 || $j==2 || $j==3){
                    if($j==3){
                      $tabla.='<td><b>'.$matriz[$i][$j].'</b></td>';
                    }
                    else{
                      $tabla.='<td align=center><b>'.$matriz[$i][$j].'</b></td>';
                    }
                  }
                  else{
                    $tabla.='<td align=right><b>'.$matriz[$i][$j].'</b></td>';
                  }
                  
                }
                $tabla.='</tr>';
              }
          $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }


  /*--- TABLA APERTURA PROGRAMATICAS REPORTE ---*/
    public function tabla_apertura_programatica_reporte($matriz,$nro){
      $tabla='';

      $tabla.='
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="font-size: 13px;font-family: Arial;height:20px;">&nbsp;DETALLE DE (%) CUMPLIMIENTO POR PROGRAMAS</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center">
            <thead>
              <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                <th style="width:1%;height:15px;">#</th>
                <th style="width:10%;">APERTURA PROGRAM&Aacute;TICA</th>
                <th style="width:23%;">DESCRIPCI&Oacute;N</th>
                <th style="width:10%;">TOTAL PROGRAMADAS</th>
                <th style="width:10%;">TOTAL EVALUADAS</th>
                <th style="width:10%;">CUMPLIDAS</th>
                <th style="width:10%;">NO CUMPLIDAS</th>
                <th style="width:10%;">% CUMPLIDAS</th>
                <th style="width:10%;">% NO CUMPLIDAS</th>
              </tr>
            </thead>
            <tbody>';
              for ($i=1; $i <=$nro+1; $i++) { 
                
                if($i==$nro+1){
                  $tabla.='<tr bgcolor=#e5ecef>';
                }
                else{
                  $tabla.='<tr>';
                }
                
                for ($j=1; $j <=9; $j++) {
                  if($j==8 || $j==9){
                    if($j==8){
                      $tabla.='<td style="width:1%;height:13px;" align=right><b>'.$matriz[$i][$j].'%</b></td>';
                    }
                    else{
                      $tabla.='<td align=right><b>'.$matriz[$i][$j].'%</b></td>';
                    }
                    
                  }
                  elseif($j==1 || $j==2 || $j==3){
                    if($j==3){
                      $tabla.='<td ><b>'.$matriz[$i][$j].'</b></td>';
                    }
                    else{
                      $tabla.='<td align=center><b>'.$matriz[$i][$j].'</b></td>';
                    }
                  }
                  else{
                    $tabla.='<td align=right><b>'.$matriz[$i][$j].'</b></td>';
                  }
                  
                }
                $tabla.='</tr>';
              }
          $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }



    //// Cabecera Evaluacion Trimestral
    public function cabecera_evaluacion_trimestral($id,$tp,$titulo){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);

      if($tp==0){
        $departamento=$this->model_proyecto->get_departamento($id);
        $tit='CONSOLIDADO REGIONAL '.strtoupper($departamento[0]['dep_departamento']);
      }
      elseif($tp==1) {
        $distrital=$this->model_proyecto->dep_dist($id);
        $tit=''.strtoupper($distrital[0]['dep_departamento']).' / '.strtoupper($distrital[0]['dist_distrital']);
      }
      else{
        $tit='CONSOLIDADO INSTITUCIONAL C.N.S.';
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
                        <tr style="font-size: 20px;font-family: Arial;">
                            <td style="height: 3%;"><b>'.$titulo.'</b>  <br> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
            <td style="width:1%;"></td>
            <td style="width:98%;height: 2%;">
              <div style="font-family: Arial;font-size: 13px;">'.$tit.'</div>
            </td>
            <td style="width:1%;"></td>
          </tr>
        </table><<hr>';
      return $tabla;
    }

  //// Pie de Evaluacion POA Trimestral
  public function pie_evaluacionpoa(){
    $tabla='';
    $tabla.='

      <table border=0 style="width:100%;">
        <tr>
          <td style="width:1%;"></td>
          <td style="width:98%;">
          <hr>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>
                  <td style="height:18px;width:33.3%">'.$this->session->userdata('rd_poa').'</td>
                  <td style="height:18px;width:33.3%">'.$this->session->userdata('sistema').'</td>
                  <td style="height:18px;width:33.3%" align=right>'.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]</td>
                </tr>
              </table>
          </td>
          <td style="width:1%;"></td>
        </tr>
      </table>';

    return $tabla;
  }   


    /*----- Parametros de Eficacia Concolidado por Unidad -----*/
    public function parametros_eficacia($matriz,$tp_rep){
      $insatisfactorio='0% a 75%';
      $regular='75% a 90%';
      $bueno='90% a 99%';

      if($this->gestion>2021){
        $insatisfactorio='0% a 50%';
        $regular='51% a 75%';
        $bueno='76% a 99%';
      }

      $tabla='';
      $tabla .='
                <div style="font-size: 25px;font-family: Arial;height:20px;">&nbsp;<b>PARAMETROS DE CUMPLIMIENTO</b></div><br>
                <table class="table table-bordered" align=center style="width:60%;">
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
                      <td>'.$insatisfactorio.'</td>
                      <td align="center"><a class="btn btn-danger" style="width: 100%" align="left" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                    </tr>
                    <tr>
                      <td>REGULAR</td>
                      <td>'.$regular.'</td>
                      <td align="center"><a class="btn btn-warning" style="width: 100%" align="left" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                    </tr>
                    <tr>
                      <td>BUENO</td>
                      <td>'.$bueno.'</td>
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
                </table>';

      return $tabla;
    }
    //// ========= INSTITUCIONAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - INSTITUCIONAL ---*/
    public function matriz_programas_institucional($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_institucional($row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]=' TOTAL INSTITUCIONAL : '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_institucional($aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_institucional($aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_institucional_trimestre($aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_institucional_trimestre($aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 

    //// ========= REGIONAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - REGIONAL ---*/
    public function matriz_programas_regional($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_regional($row['dep_id'],$row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]=' TOTAL REGIONAL : '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_regional($dep_id,$aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_regional($dep_id,$aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_regional_trimestre($dep_id,$aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_regional_trimestre($dep_id,$aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 


    //// ========= DISTRITAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - DISTRITAL ---*/
    public function matriz_programas_distrital($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_distrital($row['dist_id'],$row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]='TOTAL DISTRITAL '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_distrital($dist_id,$aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_distrital($dist_id,$aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_distrital_trimestre($dist_id,$aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_distrital_trimestre($dist_id,$aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 


    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia,$tp_rep){
      //tp_rep : 0 -> Normal, 1 : Impresion
      $tabla='';
      if($this->gestion>2021){
        if($eficacia<=50){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 50%)';} /// Insatisfactorio - Rojo
        if($eficacia > 50 & $eficacia <= 75){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (51% - 75%)';} /// Regular - Amarillo
        if($eficacia > 75 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (76% - 99%)';} /// Bueno - Azul
        if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde
      }
      else{ /// Gestiones Anteriores
        if($eficacia<=75){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 75%)';} /// Insatisfactorio - Rojo
        if($eficacia > 75 & $eficacia <= 90){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (75% - 90%)';} /// Regular - Amarillo
        if($eficacia > 90 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (90% - 99%)';} /// Bueno - Azul
        if($eficacia > 99 & $eficacia <= 102){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde
      }

      if($tp_rep==0){
        $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';
      }
      else{
        $tabla.='<center><font size=4.5><b>'.$titulo.'</b></font></center>';
      }
      
      return $tabla;
    }






    /////  PARAMETROS DE EFICACIA 
    /*---- Tabla Parametros -----*/ 
    public function matriz_parametros($matriz,$nro){

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      for ($i=1; $i <=$nro; $i++) { 
        $eficacia=$matriz[$i][8];
        
        if($this->gestion>2021){
          if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
        }
        else{
          if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
        }
        
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/$nro)*100),2);
      }

      return $par;
    }

  /*================*/
    /*------ NOMBRE MES -------*/
    public function mes_nombre_completo(){
        $mes[1] = 'ENERO';
        $mes[2] = 'FEBRERO';
        $mes[3] = 'MARZO';
        $mes[4] = 'ABRIL';
        $mes[5] = 'MAYO';
        $mes[6] = 'JUNIO';
        $mes[7] = 'JULIO';
        $mes[8] = 'AGOSTO';
        $mes[9] = 'SEPTIEMBRE';
        $mes[10] = 'OCTUBRE';
        $mes[11] = 'NOVIEMBRE';
        $mes[12] = 'DICIEMBRE';

      return $mes;
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



    /*------- TIPO DE RESPONSABLE ----------*/
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
}
?>