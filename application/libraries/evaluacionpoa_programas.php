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
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="height:20px;font-size: 10px;font-family: Arial;"><b>DETALLE DE EVALUACIÓN POR APERTURA PROGRAMATICA</b></div>
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

    /*--- BOTON IMPRIME REPORTE POR CATEGORIA PROGRANMTICA---*/
    function button_rep_catprogramatica($id,$tp){
      $tabla='';
        $tabla.='
                <a href="javascript:abreVentana(\''.site_url("").'/rep_cat_programatica/'.$id.'/'.$tp.'\');" class="btn btn-default" title="IMPRIMIR EVALUACIÓN POA POR PROGRAMA">
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
        $tit='INSTITUCIONAL C.N.S.';
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
              <td style="width:96%;height: 3%;font-size: 15px;font-family: Arial">
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
        
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
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