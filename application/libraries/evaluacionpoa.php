<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class Evaluacionpoa extends CI_Controller{
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
        $this->load->model('mantenimiento/model_estructura_org');

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


    //// LISTADO DE REGIONALES
    public function listado_regionales(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $unidades=$this->model_estructura_org->list_unidades_apertura();
    $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

      $tabla.='
          <input name="base" type="hidden" value="'.base_url().'">
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>CONSOLIDADO EVALUACI&Oacute;N POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                        <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                        <option value="">SELECCIONE REGIONAL</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-3" id="ue">
                        <label class="label">UNIDAD EJECUTORA</label>
                        <select class="form-control" id="dist_id" name="dist_id" title="SELECCIONE DISTRITAL">
                        </select>
                      </section>

                      <section class="col col-3" id="tp">
                        <label class="label">TIPO DE GASTO</label>
                        <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO">
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
              </div>
            </article>';
/*    if($this->fun_id==1289){
      $tabla.='
          <input name="base" type="hidden" value="'.base_url().'">
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>CONSOLIDADO EVALUACI&Oacute;N POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                        <select class="form-control" id="depp_id" name="depp_id" title="SELECCIONE REGIONAL">
                        <option value="">SELECCIONE REGIONAL</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-3" id="ue">
                        <label class="label">UNIDAD EJECUTORA</label>
                        <select class="form-control" id="dist_id" name="dist_id" title="SELECCIONE DISTRITAL">
                        </select>
                      </section>

                      <section class="col col-3" id="tp">
                        <label class="label">TIPO DE GASTO</label>
                        <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO">
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
              </div>
            </article>';
    }
    else{
      $tabla.='
          <input name="base" type="hidden" value="'.base_url().'">
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>CONSOLIDADO EVALUACI&Oacute;N POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                        <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                        <option value="">SELECCIONE REGIONAL</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-3" id="ue">
                        <label class="label">UNIDAD EJECUTORA</label>
                        <select class="form-control" id="dist_id" name="dist_id" title="SELECCIONE DISTRITAL">
                        </select>
                      </section>

                      <section class="col col-3" id="tp">
                        <label class="label">TIPO DE GASTO</label>
                        <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO">
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
              </div>
            </article>';
    }*/

      
    return $tabla;
  }

    /*--- TABLA ACUMULADA EVALUACIÓN 2020 - REGIONAL, DISTRITAL ---*/
    public function tabla_acumulada_evaluacion_regional_distrital($regresion,$trm_id,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit[3]='<b>NRO. ACT. CUMPLIDAS</b>';
      $tit[4]='<b>NRO. ACT. NO CUMPLIDAS</b>';
      $tit[5]='<b>(%) CUMPLIMIENTO</b>';
      $tit[6]='<b>(%) INCUMPLIMIENTO</b>';

      $tit_total[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit_total[3]='<b>NRO. ACT. CUMPLIDAS</b>';
      $tit_total[4]='<b>(%) PROGRAMACION AL TRIMESTRE</b>';
      $tit_total[5]='<b>(%) CUMPLIMIENTO AL TRIMESTRE</b>';

      $tabla.='
        <style>
          .tabla-impresion {
            width: 95%;
            margin: 0 auto;
            font-size: 9pt;
            border-collapse: collapse;
            page-break-inside: avoid;
            
            th {
              background: #11574e;
              color: black;
              padding: 10px;
              position: sticky;
              top: 0;
            }
            
            td {
              padding: 8px;
              border: 1px solid #e0e0e0;
            }
          }
        </style>';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
      } 
      else{ /// Impresion
        $tab='class="tabla-impresion" border=1 style="width:100%;"';
      }

      

      if($tp_graf==1){ // pastel : Programado-Cumplido
/*        $tabla.='
        <br>
        <table '.$tab.'>
          <thead>
              <tr align=center>
                <th><b>ACT. PROGRAMADAS</b></th>
                <th><b>ACT. EVALUADAS</b></th>
                <th><b>ACT. CUMPLIDAS</b></th>
                <th><b>ACT. NO CUMPLIDAS</b></th>
                <th><b>(%) CUMPLIMIENTO POA</b></th>
                <th><b>(%) INCUMPLIMIENTO</b></th>
              </tr>
            </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$regresion[2][$trm_id].'</b></td>
                <td><b>'.$regresion[2][$trm_id].'</b></td>
                <td><b>'.$regresion[3][$trm_id].'</b></td>
                <td><b>'.$regresion[4][$trm_id].'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$trm_id].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$trm_id].'%</b></button></td>
              </tr>
            </tbody>
        </table>';*/
      }
      elseif($tp_graf==2){ /// Regresion Acumulado al Trimestre
        $tabla.='
        <br><br>
        <b>DETALLE : </b><br>
        <table '.$tab.'>
            <thead>
              <tr>
                <th></th>';
                for ($i=1; $i <=$trm_id; $i++) { 
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
                  for ($j=1; $j <=$trm_id; $j++) { 
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
        <br><br>
        <h4><b>'.$regresion[5][$trm_id].'%</b> CUMPLIMIENTO DE '.$regresion[1][$trm_id].' CON RESPECTO A LA GESTIÓN '.$this->gestion.'</h4>
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
        <br><br>
        <b>DETALLE : </b><br>
          <ul>
            <li> Actividades Programadas:&nbsp;&nbsp;<b>'.$regresion[2][$trm_id].'</b></li>
            <li> Actividades Evaluadas:&nbsp;&nbsp;<b>'.$regresion[2][$trm_id].'</b></li>
            <li> Actividades Cumplidas:&nbsp;&nbsp;<b>'.$regresion[3][$trm_id].'</b></li>
            <li> Actividades en Proceso:&nbsp;&nbsp;<b>'.$regresion[7][$trm_id].'</b></li>
            <li> Actividades <b>NO </b>Cumplidas:&nbsp;&nbsp;<b>'.($regresion[2][$trm_id]-($regresion[7][$trm_id]+$regresion[3][$trm_id])).'</b></li>
            <li> <b>(%) DE CUMPLIMIENTO AL POA:&nbsp;&nbsp;'.$regresion[5][$trm_id].'%</b></li>
            <li> <b>(%) DE INCUMPLIMIENTO AL POA:&nbsp;&nbsp;'.$regresion[6][$trm_id].'%</b></li>
          </ul>';
      }

      return $tabla;
    }
/*    public function tabla_acumulada_evaluacion_regional_distrital($regresion,$tp_graf,$tip_rep){
      $tabla='';

      $tit[2]='<b>NRO. ACT. PROG.</b>';
      $tit[3]='<b>NRO. ACT. CUMP.</b>';
      $tit[4]='<b>NRO. ACT. NO CUMP.</b>';
      $tit[5]='<b>% CUMP.</b>';
      $tit[6]='<b>% NO CUMP.</b>';

      $tit_total[2]='<b>NRO. ACT. PROG.</b>';
      $tit_total[3]='<b>NRO. ACT. CUMP.</b>';
      $tit_total[4]='<b>% ACT. PROG.</b>';
      $tit_total[5]='<b>% ACT. CUMP.</b>';

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
              <tr align=center bgcolor='.$color.' style="font-family: Arial;">
                <th>NRO. ACT. PROG.</th>
                <th>ACT. EVAL.</th>
                <th>ACT. CUMP.</th>
                <th>ACT. NO CUMP.</th>
                <th>% CUMP. POA</th>
                <th>% NO CUMP.</th>
              </tr>
              </thead>
            <tbody>
              <tr align=right >
                <td style="font-family: Arial;"><b>'.$regresion[2][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[2][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[3][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[4][$this->tmes].'</b></td>
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
                  $tabla.='<th align=center style="font-family: Arial;"><b>'.$regresion[1][$i].'</b></th>';
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
                  <td style="font-family: Arial;">'.$tit[$i].'</td>';
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
              <tr bgcolor='.$color.' >
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
                $tabla.='<tr bgcolor='.$color.' >
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
              <tr align=center style="font-family: Arial;" >
                <th>NRO. ACT. PROG.</th>
                <th>NRO. ACT. EVAL.</th>
                <th>NRO. ACT. CUMP.</th>
                <th>NRO. ACT. EN PROC.</th>
                <th>NRO. ACT. NO CUMP.</th>
                <th>% CUMP. POA</th>
                <th>% NO CUMP.</th>
              </tr>
            </thead>
            <tbody>
              <tr align=right >
                <td style="font-family: Arial;"><b>'.$regresion[2][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[2][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[3][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.$regresion[7][$this->tmes].'</b></td>
                <td style="font-family: Arial;"><b>'.($regresion[2][$this->tmes]-($regresion[7][$this->tmes]+$regresion[3][$this->tmes])).'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$this->tmes].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$this->tmes].'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }*/




    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia){
      $tabla='';
      $tp='danger';
      $titulo='ERROR EN LOS VALORES';
      
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
      
      $tabla.='<h4 class="alert alert-'.$tp.'" style="font-family: Arial;" align="center"><b>'.$titulo.'</b></h4>';

      return $tabla;
    }


        /*----- Parametros de Eficacia Concolidado por Unidad  -----*/
    public function parametros_eficacia($matriz){
      $insatisfactorio='0% a 50%';
      $regular='51% a 75%';
      $bueno='76% a 99%';

      $tabla='';
      $tabla .='
            <div id="parametro_efi" style="width: 600px; height: 400px; margin: 0 auto"></div>
            <hr>
            <table class="table table-bordered">
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
                  <td align="center" ><a class="btn btn-danger" style="width: 100%" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                </tr>
                <tr>
                  <td>REGULAR</td>
                  <td>'.$regular.'</td>
                  <td align="center" ><a class="btn btn-warning" style="width: 100%" align="center" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                </tr>
                <tr>
                  <td>BUENO</td>
                  <td>'.$bueno.'</td>
                  <td align="center" ><a class="btn btn-info" style="width: 100%" align="center" title="'.$matriz[3][2].' Unidades/Proyectos">'.$matriz[3][2].'</a></td>
                </tr>
                <tr>
                  <td>OPTIMO </td>
                  <td>100%</td>
                  <td align="center" ><a class="btn btn-success" style="width: 100%" align="center" title="'.$matriz[4][2].' Unidades/Proyectos">'.$matriz[4][2].'</a></td>
                </tr>
                <tr>
                  <td colspan=2 align="center"><b>TOTAL UNIDADES : </b></td>
                  <td align="center"><b>'.($matriz[1][2]+$matriz[2][2]+$matriz[3][2]+$matriz[4][2]).'</b></td>
                </tr>
              </tbody>
            </table>';

      return $tabla;
    }


      /*------- CABECERA REPORTE EVALUACION POA GRAFICO------*/
    function cabecera_evaluacionpoa($tp_reg,$dato,$tp_titulo){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      /// tp_reg 0 : Regional
      /// tp_reg 1 : distrital
      /// tp_reg 2 : Nacional

      /// tp_titulo 1 : CUADRO DE EVALUACION POA AL TRIMESTRE VIGENTE 
      /// tp_titulo 2 : CUADRO DE EVALUACION POA ANUAL
      /// tp_titulo 3 : CUADRO DE EFICACIA

      $tit_rep='';

      if($tp_titulo==1){
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>EVALUACI&Oacute;N POA AL '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>';
      }
/*      elseif($tp_titulo==2){
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>EVALUACI&Oacute;N POA AL '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>';
      }*/
      elseif($tp_titulo==3){
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>EVALUACI&Oacute;N POA GESTIÓN : '.$this->gestion.'</b></div>';
      }
/*      else{
       $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>CUADRO DE EFICACIA '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>'; 
      }*/


      $titulo='';
      $dat='';
      if($tp_reg==0){
        $titulo='<tr style="font-size: 12pt;font-family: Arial;">
                  <td colspan="2" style="width:10%; height: 15%;">REGIONAL '.strtoupper ($dato[0]['dep_departamento']).'</td>
                </tr>';
        $dat=strtoupper($dato[0]['dep_departamento']);
      }
      elseif ($tp_reg==1) {
        $titulo='<tr style="font-size: 12pt;font-family: Arial;">
                  <td colspan="2" style="width:10%; height: 15%;">'.strtoupper ($dato[0]['dep_departamento']).' / '.strtoupper ($dato[0]['dist_distrital']).'</td>
                </tr>';
        $dat=strtoupper($dato[0]['dist_distrital']);
      }
      else{
        $titulo='<tr style="font-size: 12pt;font-family: Arial;">
                  <td colspan="2" style="width:10%; height: 15%;">CONSOLIDADO NACIONAL</td>
                </tr>';
        $dat='';
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
               '.$dat.' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            '.$tit_rep.'
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
              <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%; font-size: 10px;font-family: Arial;">
                '.$titulo.'
              </table>
            </td>
          </tr>
        </table>';

      return $tabla;
    }


    //// Cabecera Evaluacion Trimestral
    public function cabecera_evaluacion_trimestral($titulo){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
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
                            <td style="height: 3%;"><b>PARAMETROS DE CUMPLIMIENTO </b>  <br> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</td>
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
              <div style="font-family: Arial;font-size: 13px;">'.$titulo.'</div>
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



 /*---- CUADRO DE CUMPLIMIENTO POR REGIONAL - INSTITUCIONAL -----*/
    public function eficacia_regionales(){
    $regionales=$this->model_proyecto->list_departamentos();
    $eficacia_nacional=$this->tabla_regresion_lineal_nacional(); /// Eficacia
    //$economia_nacional=$this->economia_institucional_nacional(); /// Economia
    //$eficiencia_nacional=$this->eficiencia_por_regional($eficacia_nacional[5][$this->tmes],$economia_nacional[3]); /// Eficiencia

    $tabla='';
      $tabla.='
        <div style="font-size: 25px;font-family: Arial;"><b>CUADRO DE % CUMPLIMIENTO POR REGIONALES</b></div><br>
        <table class="table table-bordered" align=center style="width:80%;">
         <thead>
            <tr style="font-size: 10px;" align=center>
              <th style="width:2%;height:15px;">#</th>
              <th style="width:20%;">REGIONAL</th>
              <th style="width:12%;">ACT. PROG.</th>
              <th style="width:12%;">ACT. CUMP.</th>
              <th style="width:12%;">ACT. NO CUMP.</th>
              <th style="width:12%;">% CUMP. POA</th>
              <th style="width:12%;">% NO CUMP.</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($regionales as $row){
            $eficacia=$this->eficacia_por_regional($row['dep_id']); /// Eficacia
            //$economia=$this->economia_por_regional($row['dep_id']); /// Economia
          //  $eficiencia=$this->eficiencia_por_regional($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia
            $nro++;
            $tabla.='<tr style="font-size: 10px;">';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:20%;">'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[2][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[3][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right>'.$eficacia[4][$this->tmes].'</td>';
            $tabla.='<td style="width:12%;" align=right><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eficacia[5][$this->tmes].'%</b></button></td>';
            $tabla.='<td style="width:12%;" align=right><button type="button" style="width:100%;" class="btn btn-danger"><b>'.(100-$eficacia[5][$this->tmes]).'%</b></button></td>';

            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 10px;" bgcolor="#d3f8c5">
            <td></td>
            <td ><b>CONSOLIDADO INSTITUCIONAL</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_nacional[2][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_nacional[3][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_nacional[4][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_nacional[5][$this->tmes].'%</b></td>
            <td style="font-size: 13px;" align=right><b>'.(100-$eficacia_nacional[5][$this->tmes]).'%</b></td>
';
          $tabla.='
          </tr>
          </tbody>
        </table>';

        return $tabla;
    }




    /*---- LISTA EFICACIA UNIDADES ORGANIZACIONAL -----*/
    public function unidades_dist_reg($tp_uni,$id,$tp_id){
    $unidades=$this->model_evalinstitucional->list_unidades_organizacionales($tp_uni,$id);
    $distrital=$this->model_proyecto->dep_dist($id);
    
    if($tp_uni==0){ //// Regional
      $titulo_consolidado='% CONSOLIDADO REGIONAL';
      $eficacia_distrital=$this->tabla_regresion_lineal_regional($id); /// Datos de Cumplimiento
      //$economia_distrital=$this->economia_por_regional($id); /// Economia
      //$eficiencia_distrital=$this->eficiencia_por_regional($eficacia_distrital[5][$this->tmes],$economia_distrital[3]); /// Eficiencia
    }
    else{ ///// Distrital
      $distrital=$this->model_proyecto->dep_dist($id);
      $titulo_consolidado='% CONSOLIDADO DISTRITAL';
      $eficacia_distrital=$this->tabla_regresion_lineal_distrital($id); /// Datos de Cumplimiento
      //$economia_distrital=$this->economia_por_distrital($id); /// Economia
      //$eficiencia_distrital=$this->eficiencia_por_distrital($eficacia_distrital[5][$this->tmes],$economia_distrital[3]); /// Eficiencia
    }


    $tabla='';
    $tabla.='
        <div style="font-size: 25px;font-family: Arial;"><b>CUADRO DE % CUMPLIMIENTO POA POR UNIDADES</b></div><br>
            <section class="col col-3">
              <input id="searchTerm" type="text" style="width:50%;" onkeyup="doSearchuni()" class="form-control" placeholder="Buscador...."/><br>
            </section>
        <table class="table table-bordered" align=center style="width:80%;" id="tab_uni">
         <thead>
            <tr style="font-size: 10px;" align=center>
              <th style="width:1%;height:15px;">#</th>
              <th style="width:10%;">REGIONAL / DISTRITAL</th>
              <th style="width:10%;">PROGRAMA '.$this->gestion.'</th>
              <th style="width:5%;"></th>
              <th style="width:5%;">ACT. PROG.</th>
              <th style="width:5%;">ACT. CUMP.</th>
              <th style="width:5%;">ACT NO CUMP.</th>
              <th style="width:5%;">% CUMP. POA</th>
              <th style="width:5%;">% NO CUMP.</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($unidades as $row){
            $eficacia=$this->eficacia_por_unidad($row['proy_id']); /// cumplimiento poa
            //$economia=$this->economia_por_unidad($row['aper_id'],$row['proy_id']); /// Economia
            //$eficiencia=$this->eficiencia_unidad($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia

            $color='';
            if($this->gestion>2021){
              if($eficacia[5][$this->tmes]<=50){$color='#f9e1e7';} /// Insatisfactorio - Rojo (1)
              if($eficacia[5][$this->tmes] > 50 & $eficacia[5][$this->tmes] <= 75){$color='#f3e8d2';} /// Regular - Amarillo (2)
              if($eficacia[5][$this->tmes] > 75 & $eficacia[5][$this->tmes] <= 99){$color='#def0f7';} /// Bueno - Azul (3)
              if($eficacia[5][$this->tmes] > 99 & $eficacia[5][$this->tmes] <= 100){$color='#d1f5d1';} /// Optimo - verde (4)
            }
            else{
              if($eficacia[5][$this->tmes]<=75){$color='#f9e1e7';} /// Insatisfactorio - Rojo (1)
              if($eficacia[5][$this->tmes] > 75 & $eficacia[5][$this->tmes] <= 90){$color='#f3e8d2';} /// Regular - Amarillo (2)
              if($eficacia[5][$this->tmes] > 90 & $eficacia[5][$this->tmes] <= 99){$color='#def0f7';} /// Bueno - Azul (3)
              if($eficacia[5][$this->tmes] > 99 & $eficacia[5][$this->tmes] <= 100){$color='#d1f5d1';} /// Optimo - verde (4)
            }
            
            $nro++;
            $tabla.='<tr style="font-size: 10px;" bgcolor='.$color.'>';
            $tabla.='<td style="width:1;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:10%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:10%;">'.$row['prog'].' - '.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</td>';
            $tabla.='<td style="width:5%;" align=center>';
            $procesos=$this->model_componente->lista_subactividad($row['proy_id']);
              $tabla.=' <div class="btn-group">
                          <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span> VER EVAL.</a>
                          <ul class="dropdown-menu">';
                          foreach($procesos as $pr){
                            if(count($this->model_producto->list_prod($pr['com_id']))!=0){
                              if($row['ta_id']==2){
                                $tabla.='
                                <li>
                                  <a href="'.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$pr['com_id'].'/'.$this->tmes.'"  target="_blank" title="VER EVALUACIÓN POA">'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</a>
                                </li>';
                              }
                              else{
                                $tabla.='
                              <li>
                                <a href="'.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$pr['com_id'].'/'.$this->tmes.'"  target="_blank" title="VER EVALUACIÓN POA">'.$pr['serv_cod'].' '.$pr['tipo_subactividad'].' '.$pr['serv_descripcion'].'</a>
                              </li>';
                              }
                            }
                          }
                          $tabla.='
                          </ul>
                        </div>';
            $tabla.='</td>';
            $tabla.='<td style="width:5%;" align=right><b>'.$eficacia[2][$this->tmes].'</b></td>';
            $tabla.='<td style="width:5%;" align=right><b>'.$eficacia[3][$this->tmes].'</b></td>';
            $tabla.='<td style="width:5%;" align=right><b>'.$eficacia[4][$this->tmes].'</b></td>';
            $tabla.='<td style="width:5%;" align=right><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:5%;" align=right><button type="button" style="width:100%;" class="btn btn-danger"><b>'.(100-$eficacia[5][$this->tmes]).'%</b></td>';
            //$tabla.='<td style="width:5%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 10px;" >
            <td></td>
            <td colspan=2><b>'.$titulo_consolidado.'</b></td>
            <td></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_distrital[2][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_distrital[3][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_distrital[4][$this->tmes].'</b></td>
            <td style="font-size: 13px;" align=right><b>'.$eficacia_distrital[5][$this->tmes].'%</b></td>
            <td style="font-size: 13px;" align=right><b>'.(100-$eficacia_distrital[5][$this->tmes]).'%</b></td>

            
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
    $eficacia_regional=$this->tabla_regresion_lineal_regional($dep_id); /// Cumplimiento poa
    //$economia_regional=$this->economia_por_regional($dep_id); /// Economia
    //$eficiencia_regional=$this->eficiencia_por_regional($eficacia_regional[5][$this->tmes],$economia_regional[3]); /// Eficiencia

    $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:90%;"';
        $tabla.='<h2><b>CUADRO DE INDICADORES</b></h2>';
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
              <th style="width:15%;">% CUMP. POA.</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($distritales as $row){
            $eficacia=$this->tabla_regresion_lineal_distrital($row['dist_id']); /// Eficacia
            //$economia=$this->economia_por_distrital($row['dist_id']); /// Eficiencia
            //$eficiencia=$this->eficiencia_por_distrital($eficacia[5][$this->tmes],$economia[3]);
            $nro++;
            $tabla.='<tr style="font-size: 9px;">';
            $tabla.='<td style="width:5%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:40%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            //$tabla.='<td style="width:15%;" align=right><b>'.$economia[3].'%</b></td>';
            //$tabla.='<td style="width:15%;" align=right><b>'.$eficiencia.'</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 9px;" bgcolor="#d3f8c5">
            <td></td>
            <td><b>CONSOLIDADO REGIONAL '.strtoupper($regional[0]['dep_departamento']).'</b></td>
            <td style="font-size: 10px;" align=right><b>'.$eficacia_regional[5][$this->tmes].'%</b></td>
          </tr>
          </tbody>
        </table>';

        return $tabla;
    }






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
        
          if($this->gestion>2021){
            if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
            if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
            if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
            if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
          }
          else{ /// Gestiones Anteriores
            if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
            if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
            if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
            if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
          }

      }

      if(count($regionales)!=0){
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=round((($par[$i][2]/count($regionales))*100),2);
        }
      }
      else{
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=0;
        }
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

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;$nro_unidades=0;
      foreach($unidades as $row){
        if(count($this->model_evalunidad->nro_operaciones_programadas_acumulado($row['proy_id'],$this->tmes))!=0){
          $nro_unidades++;
          $eval=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
          $eficacia=$eval[5][$this->tmes];
          //if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
            if($this->gestion>2021){
              if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
              if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
              if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
              if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
            }
            else{ /// Gestiones Anteriores
              if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
              if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
              if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
              if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
            }
        }
      }

      if($nro_unidades!=0){
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=round((($par[$i][2]/$nro_unidades)*100),2);
        }
      }
      else{
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=0;
        }
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

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;$nro_dist=0;
      foreach($distritales as $row){
        if(count($this->model_evalunidad->nro_operaciones_programadas_acumulado($row['proy_id'],$this->tmes))!=0){
          $nro_dist++;
          $eval=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
          $eficacia=$eval[5][$this->tmes];

          if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)

        }
      }

      if($nro_dist!=0){
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=round((($par[$i][2]/$nro_dist)*100),2);
        }
      }
      else{
        for ($i=1; $i <=4 ; $i++) { 
          $par[$i][3]=0;
        }
      }

      return $par;
    }





/*    public function matriz_eficacia_unidad($proy_id){
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
          if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($componentes))*100),2);
      }

      return $par;
    }*/



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



  /*====== EJECUCION DE CERTIFICACION POA POR UNIDAD/PROYECTO ======*/
/*    public function economia_por_unidad($aper_id,$proy_id){
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
    }*/

    /*------ EFICIENCIA POR UNIDAD ------*/
/*    public function eficiencia_unidad($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }*/


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
/*    public function eficiencia_por_regional($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }*/





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
        $tr[8][$i]=0; /// en proceso %
      }

    //  if($tr[2][$i]!=0){
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
          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
    //  }

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
        $tr[8][$i]=0; /// en proceso %
      }

    //  if($tr[2][$i]!=0){
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

          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
    //  }

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