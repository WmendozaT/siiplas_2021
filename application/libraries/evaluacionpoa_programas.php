<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class Evaluacionpoa_programas extends CI_Controller{
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



    /*--- TABLA APERTURA PROGRAMATICAS VISTA ---*/
    public function tabla_apertura_programatica($matriz,$nro,$tip_rep){
      $tabla='';
      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

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
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>#</th>
                <th>APERTURA PROGRAM&Aacute;TICA</th>
                <th>DESCRIPCI&Oacute;N</th>
                <th>TOTAL PROGRAMADAS</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
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
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center">
            <thead>
              <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                <th style="width:1%;height:15px;">#</th>
                <th style="width:10%;">APERTURA PROGRAM&Aacute;TICA</th>
                <th style="width:10%;">DESCRIPCI&Oacute;N</th>
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

    /*--- BOTON IMPRIME REPORTE POR CATEGORIA PROGRANMTICA---*/
    function button_rep_catprogramatica($id){
      $tabla='';
        $tabla.='
                <a href="javascript:abreVentana(\''.site_url("").'/rep_cat_programatica/'.$id.'\');" class="btn btn-default" title="IMPRIMIR EVALUACIÓN POA POR PROGRAMA">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;&nbsp;<b>IMPRIMIR EVALUACIÓN POA (PROGRAMAS)</b>
                </a>';
      return $tabla;
    }



    //// Cabecera Evaluacion Trimestral
    public function cabecera_evaluacion_trimestral($id,$tp){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);

      if($tp==0){
        $departamento=$this->model_proyecto->get_departamento($id);
        $tit='REGIONAL : '.strtoupper($departamento[0]['dep_departamento']);
      }
      elseif($tp==1) {
        $distrital=$this->model_proyecto->dep_dist($id);
        $tit=''.strtoupper($distrital[0]['dep_departamento']).' / '.strtoupper($distrital[0]['dist_distrital']);
      }
      else{
        $tit='';
      }




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
               '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                        <tr style="font-size: 23px;font-family: Arial;">
                            <td style="height: 3%;"><b>EVALUACI&Oacute;N POA POR CATEGORIA PROGRAMATICA </b>  <br> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;">
                <hr>
              </td>
              <td style="width:2%;"></td>
          </tr>
          <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 3%;font-size: 20px;font-family: Arial">
               '.$tit.'
              </td>
              <td style="width:2%;"></td>
          </tr>
          <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;">
                <hr>
              </td>
              <td style="width:2%;"></td>
          </tr>
        </table>';
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
                  <td style="width:33.3%;">
                    <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="font-size: 7.5px;width:100%;height:10px;font-family: Arial;"><b>JEFATURA DE UNIDAD O AREA / RESP. DE AREA REGIONAL<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td><b><br><br><br><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                  </td>
                  <td style="width:33.3%;">
                    <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="font-size: 7.5px;width:100%;height:10px;font-family: Arial;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td><b><br><br><br><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                  </td>
                  <td style="width:33.3%;">
                    <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="font-size: 7.5px;width:100%;height:10px;font-family: Arial;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td><b><br><br><br><br><br>FIRMA</b></td>
                        </tr>
                    </table>  
                  </td>

                </tr>
                <tr>
                  <td style="height:18px;">'.$this->session->userdata('rd_poa').'</td>
                  <td style="height:18px;">'.$this->session->userdata('sistema').'</td>
                  <td align=right>'.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]</td>
                </tr>
              </table>
          </td>
          <td style="width:1%;"></td>
        </tr>
      </table>';

    return $tabla;
  }   

















    //// LISTA DE REGIONALES
    public function regionales(){
      $regiones=$this->model_evalinstitucional->regiones();
      $nro=0;
      $tabla ='';
      $tabla.='
          <article class="col-sm-12 col-md-12 col-lg-2">
            <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                    <h2>Accordions </h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">

                        <div class="panel-group smart-accordion-default" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></a></h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body no-padding"><br>
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size: 10pt;">INSTITUCIONAL</td>
                                                    <td align=center><a href="#" class="btn btn-info enlace" name="0" id="2">VER</a></td>
                                                </tr>
                                            </tbody>
                                        </table><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
              <header>
                <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                <h2><b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></h2>
              </header>
              <div>

                <div class="widget-body no-padding">
                  <div class="panel-group smart-accordion-default" id="accordion-2">';
                
                  foreach($regiones as $rowd){
                    $tabla.='
                    <div class="panel panel-default">
                      <div class="panel-heading">';
                      /// Distritales Cabecera
                      if($rowd['dep_id']!=10){
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> REGIONAL '.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }

                      /// Oficina nacional Cabecera
                      else{
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>'.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }
                      $tabla.='
                      </div>
                      <div id="collapse'.$rowd['dep_id'].'" class="panel-collapse collapse">';
                      /// Lista de Distritales
                      if($rowd['dep_id']!=10){
                        $tabla.='<div class="panel-body">'.$this->list_distrital($rowd['dep_id']).'</div>'; /// Distritales
                      }

                      /// Lista de Gerencias
                      else{
                        $tabla.='<div class="panel-body">'.$this->list_gerencias($rowd['dep_id']).'</div>'; /// Gerencia General
                      }
                      $tabla.='
                      </div>
                    </div>';
                  }
                $tabla.='
                  </div>
      
                </div>
              </div>
            </div>
          </article>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div id="content1"></div>
          </article>';
      return $tabla;
    }



    /* ---- Lista de Distritales ---*/
    public function list_distrital($dep_id){
      $tabla='';
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $distritales=$this->model_evalinstitucional->get_distritales($dep_id);

      $nro=1;
      $tabla.='<hr><table class="table table-bordered">
        <tr >
          <td>'.$nro.'</td>
          <td style="font-size: 7.8pt;"><b>CONSOLIDADO - '.strtoupper($departamento[0]['dep_departamento']).'</b></td>
          <td align=center><a href="#" class="btn btn-info enlace" name="'.$departamento[0]['dep_id'].'" id="0">VER</a></td>
          </tr>';
        //  if(count($distritales)>1){
            foreach($distritales as $row){
              $nro++;
              $tabla.='
              <tr>
                <td>'.$nro.'</td>
                <td style="font-size: 7.3pt;">'.strtoupper($row['dist_distrital']).'</td>
                <td align=center><a href="#" class="btn btn-info enlace" name="'.$row['dist_id'].'" id="1">VER</a></td>
              </tr>';
            }
        //  }
          
      $tabla.='</table>';
      return $tabla;
    }

    /* ---- Lista Gerencias ---*/
    public function list_gerencias($dep_id){
      $tabla='';
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $distritales=$this->model_evalinstitucional->get_distritales($dep_id);

      $nro=1;
      $tabla.='
        <hr>
        <table class="table table-bordered">
        <tr>
          <td bgcolor=#efefef></td>
          <td bgcolor=#efefef><b>CONSOLIDADO OFN</b></td>
          <td align=center bgcolor=#efefef><a href="#" class="btn btn-info enlace" name="'.$dep_id.'" id="0">VER</a></td>
        </tr>';
          if($this->gestion==2020){
            $tabla.='
            <tr>
              <td>'.($nro+1).'</td>
              <td>GERENCIA GENERAL</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="1781" id="1">VER</a></td>
            </tr>
            <tr>
              <td>'.($nro+2).'</td>
              <td>GERENCIA ADMINISTRATIVA FINANCIERA</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="1783" id="2">VER</a></td>
            </tr>
            <tr>
              <td>'.($nro+3).'</td>
              <td>GERENCIA SERVICIOS DE SALUD</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="1801" id="3">VER</a></td>
            </tr>
            </table>';
          }
          elseif($this->gestion==2021){
            $tabla.='
            <tr>
              <td>'.($nro+1).'</td>
              <td>GERENCIA GENERAL</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="2301" id="1">VER</a></td>
            </tr>
            <tr>
              <td>'.($nro+2).'</td>
              <td>GERENCIA ADMINISTRATIVA FINANCIERA</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="2300" id="2">VER</a></td>
            </tr>
            <tr>
              <td>'.($nro+3).'</td>
              <td>GERENCIA SERVICIOS DE SALUD</td>
              <td align=center><a href="#" class="btn btn-info enlaceg" name="2308" id="3">VER</a></td>
            </tr>
            </table>';
          }
        
      return $tabla;
    }


    /*--- TABLA ACUMULADA EVALUACIÓN 2020 - REGIONAL, DISTRITAL ---*/
    public function tabla_acumulada_evaluacion_regional_distrital($regresion,$tp_graf,$tip_rep){
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
        <h4><b>'.$regresion[5][$this->tmes].'%</b> CUMPLIMIENTO DE '.$regresion[1][$this->tmes].' CON RESPECTO A LA GESTIÓN '.$this->gestion.'</h4>
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
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$regresion[5][$this->tmes].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$regresion[6][$this->tmes].'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

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

      $tabla.='<h4 style="font-family: Arial;" class="alert alert-'.$tp.'" align="center">'.$titulo.'</h4>';

      return $tabla;
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



      /*------- CABECERA REPORTE EVALUACION POA ------*/
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
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>CUADRO DE AVANCE EVALUACI&Oacute;N POA AL '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>';
      }
      elseif($tp_titulo==2){
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>CUADRO DETALLE EVALUACI&Oacute;N POA AL '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>';
      }
      elseif($tp_titulo==3){
        $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>CUADRO DE EVALUACI&Oacute;N POA GESTIÓN : '.$this->gestion.'</b></div>';
      }
      else{
       $tit_rep='<div style="height: 30px;font-size: 18px;font-family: Arial;"><b>CUADRO DE EFICACIA '.$trimestre['0']['trm_descripcion'].' / '.$this->gestion.'</b></div>'; 
      }


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
              <th style="width:15%;">OPE. PROGRAMADAS AL TRIMESTRE</th>
              <th style="width:15%;">OPE. CUMPLIDAS AL TRIMESTRE</th>
              <th style="width:15%;">OPE. NO CUMPLIDAS AL TRIMESTRE</th>
              <th style="width:15%;">% EFICACIA</th>
              <th style="width:15%;">% ECONOMIA</th>';
            // $tabla.=' <th style="width:10%;">EFICIENCIA</th>';
             $tabla.='
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
            $tabla.='<td style="width:15%;" align=right>'.$eficacia[2][$this->tmes].'</td>';
            $tabla.='<td style="width:15%;" align=right>'.$eficacia[3][$this->tmes].'</td>';
            $tabla.='<td style="width:15%;" align=right>'.$eficacia[4][$this->tmes].'</td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:15%;" align=right><b>'.$economia[3].'%</b></td>';
            /*$tabla.='<td style="width:10%;" align=right><b>'.$eficiencia.'</b></td>';*/
            $tabla.='</tr>';
          }
      $tabla.='
          <tr style="font-size: 10px;" bgcolor="#d3f8c5">
            <td></td>
            <td ><b>CONSOLIDADO INSTITUCIONAL</b></td>
            <td style="font-size: 15px;" align=right><b>'.$eficacia_nacional[2][$this->tmes].'</b></td>
            <td style="font-size: 15px;" align=right><b>'.$eficacia_nacional[3][$this->tmes].'</b></td>
            <td style="font-size: 15px;" align=right><b>'.$eficacia_nacional[4][$this->tmes].'</b></td>
            <td style="font-size: 15px;" align=right><b>'.$eficacia_nacional[5][$this->tmes].'%</b></td>
            <td style="font-size: 15px;" align=right><b>'.$economia_nacional[3].'%</b></td>';
         // $tabla.='  <td style="font-size: 10px;" align=right><b>'.$eficiencia_nacional.'</b></td>';
          $tabla.='
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

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;$nro_unidades=0;
      foreach($unidades as $row){
        if(count($this->model_evalunidad->nro_operaciones_programadas_acumulado($row['proy_id'],$this->tmes))!=0){
          $nro_unidades++;
          $eval=$this->eficacia_por_unidad($row['proy_id']); /// Eficacia
          $eficacia=$eval[5][$this->tmes];
          if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
        }
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/$nro_unidades)*100),2);
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
          if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
        }
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/$nro_dist)*100),2);
      }

      return $par;
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