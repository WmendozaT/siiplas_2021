<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Seguimientopoa extends CI_Controller{
        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_notificacion');
            $this->load->model('programacion/model_producto');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('programacion/model_faseetapa');
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

    /// Cabecera Reporte de Seguimiento POA Mensual 2021
    public function cabecera($componente,$proyecto){
      $tabla='';
      $tabla.=' 
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
          <tr>
            <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
          </tr>
          <tr style="font-size: 8pt;">
            <td style="width:10%; height: 1.2%;"><b>DIR. ADM.</b></td>
            <td style="width:90%;">: '.$proyecto[0]['dep_cod'].' '.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-size: 8pt;">
            <td style="width:10%; height: 1.2%;"><b>UNI. EJEC.</b></td>
            <td style="width:90%;">: '.$proyecto[0]['dist_cod'].' '.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>
          <tr style="font-size: 8pt;">';
            if($proyecto[0]['tp_id']==1){ /// Proyecto de Inversion
                $tabla.='
                <td style="width:10%;"><b>PROY. INV.</b></td>
                <td style="width:90%;">: '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'].'</td>';
            }
            else{ /// Gasto Corriente
                $tabla.='
                <td style="width:10%;"><b>ACTIVIDAD</b></td>
                <td style="width:90%;">: '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</td>';
            }
          $tabla.='
          </tr>
          <tr style="font-size: 8pt;">
              <td style="height: 1.2%; width:10%;"><b>';
                if($proyecto[0]['tp_id']==1){
                  $tabla.='UNI. RESP. ';
                }
                else{
                  $tabla.='SUBACT. ';
                }
              $tabla.='</b></td>
              <td style="width:90%;">: '.strtoupper($componente[0]['serv_cod']).' '.strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_descripcion']).'</td>
          </tr>
        </table>';
      return $tabla;
    }

      /*------- CABECERA REPORTE SEGUIMIENTO POA (GRAFICO)------*/
    function cabecera_seguimiento($establecimiento,$subactividad,$tipo_titulo,$trm_id){
      $fase=$this->model_faseetapa->get_fase($subactividad[0]['pfec_id']);
      $proyecto=$this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
      $trimestre=$this->model_evaluacion->get_trimestre($trm_id);
      /// tipo_titulo 1 : Seguimiento Mensual
      /// tipo_titulo 2 : Evaluacion por Trimestre
      /// tipo_titulo 3 : Evaluacion POA Gestion
      
      $tit='';
      if($tipo_titulo==1){
        $tit='<td style="height: 35px;font-size: 23px;"><center><b>CUADRO DE SEGUIMIENTO POA</b> - '.$this->verif_mes[2].' / '.$this->gestion.'</center></td>';
      }
      elseif($tipo_titulo==2){
        $tit='<td style="height: 35px;font-size: 18px;"><center><b>EVALUACIÓN POA ACUMULADO AL </b> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</center></td>';
      }
      else{
        $tit='<td style="height: 35px;font-size: 23px;"><center><b>EVALUACI&Oacute;N POA GESTI&Oacute;N '.$this->gestion.'</b></center></td>';
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
              '.strtoupper($establecimiento[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
              <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%; font-size: 10px;font-family: Arial;">
                <tr>
                    <td style="width:25%;height: 14px;">
                      <b>UNIDAD EJECUTORA</b>
                    </td>
                    <td style="width:75%;">';
                      if($proyecto[0]['tp_id']==4){
                        $tabla.='&nbsp;'.$establecimiento[0]['dist_cod'].' '.strtoupper($establecimiento[0]['dist_distrital']).'';
                      }
                      else{
                        $tabla.='&nbsp;'.$proyecto[0]['dist_cod'].' '.strtoupper($proyecto[0]['dist_distrital']).'';
                      }
                    $tabla.='
                    </td>
                </tr>
                <tr>
                    <td style="width:25%;height: 14px;">';
                      if($proyecto[0]['tp_id']==4){
                        $tabla.='<b>GASTO CORRIENTE<b>';
                      }
                      else{
                        $tabla.='<b>PROYECTO INVERSIÓN</b>';
                      }
                    $tabla.='
                      
                    </td>
                    <td style="width:75%;">';
                      if($proyecto[0]['tp_id']==4){
                        $tabla.='&nbsp;'.$establecimiento[0]['aper_actividad'].' - '.$establecimiento[0]['tipo'].' '.strtoupper ($establecimiento[0]['act_descripcion']).' '.$establecimiento[0]['abrev'].'';
                      }
                      else{
                        $tabla.='&nbsp;'.$proyecto[0]['proy_sisin'].' '.strtoupper($proyecto[0]['proy_nombre']).'';
                      }
                    $tabla.='
                        
                    </td>
                </tr>
                <tr>
                    <td style="width:25%;height: 14px;">
                      <b>UNIDAD RESPONSABLE</b>
                    </td>
                    <td style="width:75%;">
                        &nbsp;'.$subactividad[0]['tipo_subactividad'].' '.$subactividad[0]['serv_descripcion'].'
                    </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>';

      return $tabla;
    }


    //// Cabecera Evaluacion Trimestral
    public function cabecera_evaluacion_trimestral($componente,$proyecto,$trimestre){
      $trimestre=$this->model_evaluacion->get_trimestre($trimestre);
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
                '.strtoupper($proyecto[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <td style="height: 3%;"><b>EVALUACI&Oacute;N POA</b> - '.$trimestre[0]['trm_descripcion'].'</td>
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
              <td style="width:96%;height: 3%;">
               
                      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($proyecto[0]['dep_departamento']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($proyecto[0]['dist_distrital']).'</td></tr>
                                </table>
                            </td>
                        </tr>';

                          if($proyecto[0]['tp_id']==1){
                            $tabla.='
                            <tr>
                              <td style="width:20%;">
                                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>PROY. INVERSI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['proy_sisin'].' '.strtoupper ($proyecto[0]['proy_nombre']).'</td></tr>
                                  </table>
                              </td>
                            </tr>
                            <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD RESP.</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>';
                          }
                          else{
                            $tabla.='
                            <tr>
                              <td style="width:20%;">
                                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>'.$proyecto[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_actividad'].' '.strtoupper ($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</td></tr>
                                  </table>
                              </td>
                            </tr>
                            <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD RESP.</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>';
                          }
                        $tabla.='
                    </table>
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



    /// Reporte formulario de Seguimiento POA Mensual
    public function tabla_form_seguimientopoa_subactividad($com_id,$mes_id){
      $verif_mes=$this->update_mes_gestion($mes_id);
      $tabla='';
        $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES
        $tabla='';
        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
                <thead>
                  <tr bgcolor=#f8f2f2 align=center>
                    <th style="font-size: 7px; height:17px;" colspan=9>DATOS POA (FORMULARIO N° 4 - ACTIVIDADES)</th>
                    <th colspan=3>DATOS SEGUIMIENTO</th>
                  </tr>   
                  <tr style="font-size: 7px; height:17px;" bgcolor=#f8f2f2 align=center>
                    <th style="width:1%;"></th>
                    <th style="width:3%;"><b>COD. OPE.</b></th>
                    <th style="width:3%;"><b>COD. ACT.</b></th>
                    <th style="width:20%;">DESCRIPCI&Oacute;N ACTIVIDAD</th>
                    <th style="width:16%;">MEDIO DE VERIFICACI&Oacute;N</th>
                    <th style="width:4%;">META ANUAL</th>
                    <th style="width:4%;">PROG. MES '.$verif_mes[2].'</th>
                    <th style="width:4%;">EJEC. MES '.$verif_mes[2].'</th>
                    <th style="width:4%;">%EFI.</th>
                    <th style="width:13%;">MEDIO DE VERIFICACIÓN</th> 
                    <th style="width:13%;">PROBLEMAS PRESENTADOS</th>
                    <th style="width:13%;">ACCIONES REALIZADOS</th> 
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($operaciones as $row){
                  $indi_id='';
                  if($row['indi_id']==2 & $row['mt_id']==1){
                    $indi_id='%';
                  }
                  $diferencia=$this->verif_valor_no_ejecutado($row['prod_id'],$verif_mes[1]);
                  
                  if($diferencia[1]!=0 || $diferencia[2]!=0){
                    $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($row['prod_id'],$verif_mes[1]);
                    $efi=0;
                    if($diferencia[1]!=0){
                      $efi=round((($diferencia[3]/($diferencia[1]+$diferencia[2]))*100),2);
                    }
                    else{
                      $efi=round((($diferencia[3]/$diferencia[2])*100),2);
                    }

                    $color='';
                    $nro++;
                    $tabla.=
                    '<tr style="height:15px; font-size: 6.5px;" bgcolor="'.$color.'">
                        <td style="height:15px;  width: 1%; text-align: center;" >'.$nro.'</td>
                        <td style="width: 3%; text-align: center; font-size: 9px;"><b>'.$row['or_codigo'].'</b></td>
                        <td style="width: 3%; text-align: center; font-size: 9px;"><b>'.$row['prod_cod'].'</b></td>
                        <td style="width: 20%;">'.$row['prod_producto'].'</td>
                        <td style="width: 16%;">'.$row['prod_fuente_verificacion'].'</td>
                        <td align=right>'.round($row['prod_meta'],2).''.$indi_id.'</td>
                        <td style="width: 4%; text-align: right;">'.$diferencia[2].''.$indi_id.'</td>';
                        if(count($ejec)!=0){
                            $tabla.='
                            <td style="width: 4%; text-align: right;">'.$diferencia[3].''.$indi_id.'</td>
                            <td style="width: 4%; text-align: right;">'.$efi.' %</td>
                            <td style="width: 13%;">'.$ejec[0]['medio_verificacion'].'</td>
                            <td style="width: 13%;">'.$ejec[0]['observacion'].'</td>
                            <td style="width: 13%;">'.$ejec[0]['acciones'].'</td>';
                          }
                        else{
                          $tabla.='<td style="width: 3%; text-align: right;">0</td>';
                          $no_ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($row['prod_id'],$verif_mes[1]);
                          if(count($no_ejec)!=0){
                            $tabla.='
                            <td style="width: 4%; text-align: right;"></td>
                            <td style="width: 13%;">'.$no_ejec[0]['medio_verificacion'].'</td>
                            <td style="width: 13%;">'.$no_ejec[0]['observacion'].'</td>
                            <td style="width: 13%;">'.$no_ejec[0]['acciones'].'</td>';
                          }
                          else{
                            $tabla.='
                            <td style="width: 4%; text-align: right;"></td>
                            <td style="width: 13%;"></td>
                            <td style="width: 13%;"></td>
                            <td style="width: 13%;"></td>';
                          }
                        }
                        $tabla.='
                    </tr>';
                  }

                }
                $tabla.='
                </tbody>
              </table>';

      return $tabla;
    }

  /// Temporalidad de todas las operaciones de la Subactividad 2021 (Vista)
  public function temporalidad_operacion($com_id){
    $tabla='';
    $operaciones=$this->model_producto->list_operaciones_subactividad($com_id);

    $tabla.=' <hr>
              <div class="table-responsive">
              <table class="table table-bordered" width="100%" align=center>
                <thead>
                 <tr style="font-size: 7px;" align=center>
                    <th style="width:1%;height:15px;">#</th>
                    <th style="width:1%;">COD.<br>OPE.</th>
                    <th style="width:1%;">COD.<br>ACT.</th> 
                    <th style="width:7%;">DESCRIPCIÓN ACTIVIDAD</th>
                    <th style="width:7%;">RESULTADO</th>
                    <th style="width:7%;">INDICADOR</th>
                    <th style="width:2%;">META</th>
                    <th style="width:2.5%;">ENE.</th>
                    <th style="width:2.5%;">FEB.</th>
                    <th style="width:2.5%;">MAR.</th>
                    <th style="width:2.5%;">ABR.</th>
                    <th style="width:2.5%;">MAY.</th>
                    <th style="width:2.5%;">JUN.</th>
                    <th style="width:2.5%;">JUL.</th>
                    <th style="width:2.5%;">AGO.</th>
                    <th style="width:2.5%;">SEPT.</th>
                    <th style="width:2.5%;">OCT.</th>
                    <th style="width:2.5%;">NOV.</th>
                    <th style="width:2.5%;">DIC.</th>
                </tr>
                </thead>
                <tbody>';
                  $nro=0;
                  foreach($operaciones as $rowp){
                    $indi_id='';
                    if($rowp['indi_id']==2 & $rowp['mt_id']==1){
                      $indi_id='%';
                    }
                    $temp=$this->temporalizacion_productos($rowp['prod_id']);
                    $nro++;
                    $tabla .='
                    <tr >
                      <td style="width: 1%; text-align: center; height:50px;" title='.$rowp['prod_id'].'>'.$nro.'</td>
                      <td style="width: 1%; text-align: center;"><b>'.$rowp['or_codigo'].'</b></td>
                      <td style="width: 1%; text-align: center;"><b>'.$rowp['prod_cod'].'</b></td>
                      <td style="width: 7%; text-align: left;">'.$rowp['prod_producto'].'</td>
                      <td style="width: 7%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                      <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                      <td style="width: 2%; text-align: right;">'.round($rowp['prod_meta'],2).''.$indi_id.'</td>';
                      
                      for ($i=1; $i <=12 ; $i++) { 
                        $color='';
                        if($i<=$this->verif_mes[1]){
                          $color='#e9f5e3';
                        }

                        $tabla.='
                        <td style="width: 2.5%; text-align: center;font-size: 7px;" bgcolor='.$color.'>
                          <table class="table table-bordered" align=center>
                            <tr><td style="width:50%;"><b>P:</b></td><td style="width:50%;">'.round($temp[1][$i],2).''.$indi_id.'</td></tr>
                            <tr><td style="width:50%;"><b>E:</b></td><td style="width:50%;">'.round($temp[4][$i],2).''.$indi_id.'</td></tr>
                          </table>
                        </td>';
                      }
                      $tabla.='
                    </tr>';
                  }
                $tabla.='
                </tbody>
              </table>
              </div>';

    return $tabla;
  }


  /// Temporalidad por Operacion
  public function get_temporalidad_operacion($producto){
    $tabla='';
    $tabla.=' <hr>
              <div class="table-responsive">
              <table class="table table-bordered" width="100%" align=center>
                <thead>
                 <tr style="font-size: 7px;" align=center>
                    <th style="width:1%;">COD.<br>ACT.</th> 
                    <th style="width:7%;">DESCRIPCIÓN ACTIVIDAD</th>
                    <th style="width:2%;">META</th>
                    <th style="width:2.5%;">ENE.</th>
                    <th style="width:2.5%;">FEB.</th>
                    <th style="width:2.5%;">MAR.</th>
                    <th style="width:2.5%;">ABR.</th>
                    <th style="width:2.5%;">MAY.</th>
                    <th style="width:2.5%;">JUN.</th>
                    <th style="width:2.5%;">JUL.</th>
                    <th style="width:2.5%;">AGO.</th>
                    <th style="width:2.5%;">SEPT.</th>
                    <th style="width:2.5%;">OCT.</th>
                    <th style="width:2.5%;">NOV.</th>
                    <th style="width:2.5%;">DIC.</th>
                </tr>
                </thead>
                <tbody>';
                  $nro=0;
                    $indi_id='';
                    if($producto[0]['indi_id']==2 & $producto[0]['mt_id']==1){
                      $indi_id='%';
                    }
                    $temp=$this->temporalizacion_productos($producto[0]['prod_id']);
                    $nro++;
                    $tabla .='
                    <tr>
                      <td style="width: 1%; text-align: center;"><b>'.$producto[0]['prod_cod'].'</b></td>
                      <td style="width: 7%; text-align: left;">'.$producto[0]['prod_producto'].'</td>
                      <td style="width: 2%; text-align: right;">'.round($producto[0]['prod_meta'],2).''.$indi_id.'</td>';
                      
                      for ($i=1; $i <=12 ; $i++) { 
                        $color='';
                        if($i<=$this->verif_mes[1]){
                          $color='#e9f5e3';
                        }

                        $tabla.='
                        <td style="width: 2.5%; text-align: center;font-size: 7px;" bgcolor='.$color.'>
                          <table class="table table-bordered" align=center>
                            <tr><td style="width:50%;"><b>P:</b></td><td style="width:50%;">'.round($temp[1][$i],2).''.$indi_id.'</td></tr>
                            <tr><td style="width:50%;"><b>E:</b></td><td style="width:50%;">'.round($temp[4][$i],2).''.$indi_id.'</td></tr>
                          </table>
                        </td>';
                      }
                      $tabla.='
                    </tr>
                  </tbody>
              </table>
              </div>';

    return $tabla;
  }

  /// Nivel de cumplimiento de meta al mes actual
  public function get_grado_cumplimiento_meta_mensual($producto){
    $tabla='';
    $programado=$this->model_evaluacion->rango_programado_trimestral_productos($producto[0]['prod_id'],$this->verif_mes[1]); /// Suma programado al mes vigente
    $ejecutado=$this->model_evaluacion->rango_ejecutado_trimestral_productos($producto[0]['prod_id'],$this->verif_mes[1]); /// Suma ejecutado al mes vigente

    $mes_programado=$this->model_evaluacion->get_meta_mensual_programado_operacion($producto[0]['prod_id'],$this->verif_mes[1]);
    $mes_ejecutado=$this->model_evaluacion->get_meta_mensual_ejecutado_operacion($producto[0]['prod_id'],$this->verif_mes[1]);

    
    $meta_prog=0;
    $meta_ejec=0;

    if($producto[0]['indi_id']==1){ /// Absoluto
      if(count($programado)!=0){
        $meta_prog=$programado[0]['trimestre'];
      }

      if(count($ejecutado)!=0){
        $meta_ejec=$ejecutado[0]['trimestre'];
      }
    }
    else{ /// Relativo
      if(count($mes_programado)!=0){
        $meta_prog=$mes_programado[0]['meta_mensual'];
      }

      if(count($mes_ejecutado)!=0){
        $meta_ejec=$mes_ejecutado[0]['meta_mensual'];
      }
    }

    $cumplimiento_mensual=0;
    if($meta_ejec==$meta_prog){
      $tabla.='<h2 class="alert alert-success"><center>% CUMPLIMIENTO DE ACTIVIDAD AL MES '.$this->verif_mes[2].' : 100 %</center></h2>';
    }
    elseif($meta_ejec<$meta_prog & $meta_ejec!=0){
      $cumplimiento_mensual=round((($meta_ejec/$meta_prog)*100),2);
      $tabla.='<h2 class="alert alert-warning"><center>% CUMPLIMIENTO DE ACTIVIDAD AL MES '.$this->verif_mes[2].' : '.$cumplimiento_mensual.' %</center></h2>';
    }
    else{
      $tabla.='<h2 class="alert alert-danger"><center>% CUMPLIMIENTO DE ACTIVIDAD AL MES '.$this->verif_mes[2].' : '.$cumplimiento_mensual.' %</center></h2>';
    }

    return $tabla;
  }



  /// Temporalidad de todas las operaciones de la SUbactividad 2021 (Reporte)
  public function tabla_reporte_consolidado_temporalidad($com_id){
    $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES
    $tabla='';
    $tabla.=' 
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
             <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                <th style="width:1%;height:15px;">#</th>
                <th style="width:2%;">COD.<br>OR.</th>
                <th style="width:2%;">COD.<br>OPE.</th> 
                <th style="width:10%;">OPERACI&Oacute;N</th>
                <th style="width:10%;">RESULTADO</th>
                <th style="width:10%;">INDICADOR</th>
                <th style="width:2%;">L.B.</th>
                <th style="width:3%;">META</th>
                <th style="width:3.5%;">PROG. '.$this->verif_mes[2].'</th>
                <th style="width:3.5%;">EJEC. '.$this->verif_mes[2].'</th>
                <th style="width:3.5%;">%EFI. '.$this->verif_mes[2].'</th>
                <th style="width:5%;"></th>

                
                <th style="width:3.5%;">ENE.</th>
                <th style="width:3.5%;">FEB.</th>
                <th style="width:3.5%;">MAR.</th>
                <th style="width:3.5%;">ABR.</th>
                <th style="width:3.5%;">MAY.</th>
                <th style="width:3.5%;">JUN.</th>
                <th style="width:3.5%;">JUL.</th>
                <th style="width:3.5%;">AGO.</th>
                <th style="width:3.5%;">SEPT.</th>
                <th style="width:3.5%;">OCT.</th>
                <th style="width:3.5%;">NOV.</th>
                <th style="width:3.5%;">DIC.</th>
            </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($operaciones as $rowp){
                $programado=$this->model_producto->suma_prog_trimestre($rowp['prod_id'],$this->verif_mes[1]);
                $ejecutado=$this->model_producto->suma_ejec_trimestre($rowp['prod_id'],$this->verif_mes[1]);
                $prog=0;
                if(count($programado)!=0){
                  $prog=$programado[0]['meta'];
                }

                $ejec=0;
                if(count($ejecutado)!=0){
                  $ejec=$ejecutado[0]['meta'];
                }

                $efi=0;
                $tit='';
                if($prog!=0){
                  $tit='<p style="color:red"><b>NO CUMPLIDO</b></p>';
                  if($ejec==$prog){
                    $tit='<p style="color:green"><b>CUMPLIDO</b></p>';
                  }
                  elseif ($ejec<$prog & $ejec!=0) {
                    $tit='<p style="color:orange"><b>EN PROCESO</b></p>';
                  }

                  $efi=(($ejec/$prog)*100);
                }

                  $indi_id='';
                  if($rowp['indi_id']==2 & $rowp['mt_id']==1){
                      $indi_id='%';
                  }

                  $nro++;
                  $tabla .='
                  <tr >
                    <td style="width: 1%; text-align: center; height:50px; font-size: 3px;" title='.$rowp['prod_id'].'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;">'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 2%; text-align: right;">'.round($rowp['prod_meta'],2).''.$indi_id.'</td>
                    <td style="width: 3.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($prog,2).''.$indi_id.'</b></td>
                    <td style="width: 3.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($ejec,2).''.$indi_id.'</b></td>
                    <td style="width: 3.5%; text-align: center; font-size: 9px;" bgcolor="#e9f7e9"><b>'.round($efi,2).'%</b></td>
                    <td style="width: 5%; text-align: left;">'.$tit.'</td>';
                    $temp=$this->temporalizacion_productos($rowp['prod_id']);

                    for ($i=1; $i <=12 ; $i++) { 
                      $color='';
                        if($i<=$this->verif_mes[1]){
                          $color='#f0fffd';
                      }

                      $tabla.='
                      <td style="width: 3.5%; text-align: center;font-size: 7px;" bgcolor='.$color.'>
                        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:90%;" align=center>
                          <tr><td style="width:50%;"><b>P:</b></td><td style="width:50%;">'.round($temp[1][$i],2).'</td></tr>
                          <tr><td style="width:50%;"><b>E:</b></td><td style="width:50%;">'.round($temp[4][$i],2).'</td></tr>
                        </table>
                      </td>';
                    }
                    $tabla.='
                  </tr>';
              }
            $tabla.='
            </tbody>
          </table>';
      return $tabla;
  }
    

  /// Evaluación POA por Trimestre 2021
  public function tabla_reporte_evaluacion_poa($com_id,$trimestre){
    $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES
    $tabla='';

    $tabla.=' 
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
             <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                <th style="width:1%;height:15px;"># '.$trimestre.'</th>
                <th style="width:2%;">COD.<br>OR.</th>
                <th style="width:2%;">COD.<br>OPE.</th> 
                <th style="width:10%;">OPERACI&Oacute;N</th>
                <th style="width:10%;">RESULTADO</th>
                <th style="width:10%;">INDICADOR</th>
                <th style="width:3%;">META TOTAL</th>
                <th style="width:3.5%;">META PROG.</th>
                <th style="width:3.5%;">META EJEC.</th>
                <th style="width:15.5%;">MEDIO DE VERIFICACIÓN</th>
                <th style="width:15.5%;">PROBLEMAS PRESENTADOS</th>
                <th style="width:15.5%;">ACCIONES REALIZADOS</th>
                <th style="width:6%;"></th>
            </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($operaciones as $rowp){
                $verif=$this->verif_operacion_trimestral($rowp['prod_id'],$trimestre);

                if(($verif[1]!=0 || $verif[2]!=0) || $verif[3]!=0){
                  $nro++;
                  $tabla .='
                  <tr>
                    <td style="width: 1%; text-align: center; height:50px; font-size: 4px;" title='.$rowp['prod_id'].'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;">'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>
                    <td style="width: 3.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($verif[1],2).'</b></td>
                    <td style="width: 3.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($verif[2],2).'</b></td>
                    <td style="width: 15.5%; text-align: left;">
                      '.$this->verif_medios_verificacion($rowp['prod_id'],1,$trimestre).'
                    </td>
                    <td style="width: 15.5%; text-align: left;">
                      '.$this->verif_medios_verificacion($rowp['prod_id'],2,$trimestre).'
                    </td>
                    <td style="width: 15.5%; text-align: left;">
                      '.$this->verif_medios_verificacion($rowp['prod_id'],3,$trimestre).'
                    </td>
                    <td style="width: 6%; text-align: left;">'.$verif[4].'</td>
                  </tr>';
                }
              }
            $tabla.='
            </tbody>
          </table>';
      return $tabla;
  }


   /*------- Ejecucion presupuestaria al total programado (Nuevo) --------*/
    public function ejecucion_presupuestaria_acumulado_total($com_id){
      $tabla='';
      $monto_total=0;
      $ppto_total=$this->model_componente->componente_ppto_total($com_id);
      if (count($ppto_total)!=0) {
        $monto_total=$ppto_total[0]['total_ppto'];
      }

      $monto_partida=0;
      $suma_partida=$this->model_evaluacion->suma_grupo_partida_programado($com_id,10000); /// total partida 10000
      if(count($suma_partida)!=0){
        $monto_partida=$suma_partida[0]['suma_partida'];
      }

      $monto_certificado=0;
      $suma_certificado=$this->model_evaluacion->suma_monto_certificado_servicio($com_id); // Ejecutado al trimestre
      if(count($suma_certificado)!=0){
        $monto_certificado=$suma_certificado[0]['ppto_certificado'];
      }

      $tabla.='
        <div align=center>
       
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
          <thead>
          <tr>
            <th style="width:10%; height:18px;"></th>';
            for ($i=1; $i <=$this->tmes ; $i++) {
              $trimestre=$this->model_evaluacion->get_trimestre($i);
              $tabla.='<th style="width:12%;">'.$trimestre[0]['trm_descripcion'].'</th>';
            }
          
        $tabla.='
            <th style="width:10%;">TOTAL N° CERT. POA</th>
            <th style="width:10%;">MONTO PROGRAMADO TOTAL</th>
            <th style="width:10%;">MONTO EJECUTADO</th>
            <th style="width:5%;">% EJECUTADO</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td style="width: 10%; font-size: 7px; text-align: left;height:13px;"><b>CERTIFICACIONES POA</b></td>';
              $nro_total=0;
              for ($i=1; $i <= $this->tmes; $i++) {
                $nro=0;
                $cert=$this->model_evaluacion->nro_certificaciones_trimestre($com_id,$i);
                if(count($cert)!=0){
                  $nro=$cert[0]['numero_certificaciones'];
                  $nro_total=$nro_total+$nro;
                }
                
                $tabla.='<td align=right><b>'.$nro.'</b></td>';
              }
          $tabla.='
              <td align=right bgcolor="#d9f9f5"><b>'.$nro_total.'</b></td>
              <td align=right bgcolor="#d9f9f5"><b>'.number_format($monto_total, 2, ',', '.').'</b></td>
              <td align=right bgcolor="#d9f9f5"><b>'.number_format(($monto_partida+$monto_certificado), 2, ',', '.').'</b></td>';
                if($monto_total!=0){
                  $tabla.='<td align=right bgcolor="#d9f9f5"><b>'.(round(((($monto_partida+$monto_certificado)/$monto_total)*100),2)).' %</b></td>';
                }
                else{
                  $tabla.='<td align=right bgcolor="#d9f9f5"><b>0 %</b></td>';
                }
              $tabla.='
            </tr>
          </tbody>
        </table>
        </div>';

      return $tabla;
    }








    /*---- VERIFICA OPERACION TRIMESTRAL -----*/
    public function verif_medios_verificacion($prod_id,$tipo_medio,$trimestre){
      $tabla='';
      $mes_inicio=0;
      $mes_final=0;
      if($trimestre==1){$mes_inicio=1;$mes_final=3;}
      if($trimestre==2){$mes_inicio=4;$mes_final=6;}
      if($trimestre==3){$mes_inicio=7;$mes_final=9;}
      if($trimestre==4){$mes_inicio=10;$mes_final=12;}

      $tabla.='<ul>';
      for ($i=$mes_inicio; $i <=$mes_final ; $i++) { 
        $medio=$this->model_evaluacion->get_meta_mensual_ejecutado_operacion($prod_id,$i);
        $mes=$this->model_evaluacion->get_mes($i);
        if(count($medio)!=0){
            if($tipo_medio==1){
              if($medio[0]['medio_verificacion']!=''){
                $tabla.='<li>'.$medio[0]['medio_verificacion'].'</li>';
              }
            }
            elseif($tipo_medio==2){
              if($medio[0]['observacion']!=''){
                $tabla.='<li>'.$medio[0]['observacion'].'</li>';
              }
            }
            else{
              if($medio[0]['acciones']!=''){
                $tabla.='<li>'.$medio[0]['acciones'].'</li>';
              }
            }
        }
        else{
          $nmedio=$this->model_evaluacion->get_meta_mensual_no_ejecutado_operacion($prod_id,$i);
          if(count($nmedio)!=0){
            if($tipo_medio==1){
              if($nmedio[0]['medio_verificacion']!=''){
                $tabla.='<li>'.$nmedio[0]['medio_verificacion'].'</li>';
              }
            }
            elseif($tipo_medio==2){
              if($nmedio[0]['observacion']!=''){
                $tabla.='<li>'.$nmedio[0]['observacion'].'</li>';
              }
            }
            else{
              if($nmedio[0]['acciones']!=''){
                $tabla.='<li>'.$nmedio[0]['acciones'].'</li>';
              }
            }
          }
        }
        
      }
      $tabla.='</ul>';

      return $tabla;
    }

   /*---- VERIFICA OPERACION TRIMESTRAL -----*/
    public function verif_operacion_trimestral($prod_id,$trimestre){
      for ($i=1; $i <=4 ; $i++) { 
        $datos[$i]=0;
      }

      $mes_final=0;
      if($trimestre==1){$mes_final=3;}
      elseif ($trimestre==2) {$mes_final=6;}
      elseif ($trimestre==3) {$mes_final=9;}
      elseif ($trimestre==4) {$mes_final=12;}

      $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($trimestre,$prod_id); /// Trimestre Programado
      $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($trimestre,$prod_id); /// Trimestre Ejecutado

      $prog_trimestre=0; 
        if(count($trimestre_prog)!=0){
          $prog_trimestre=$trimestre_prog[0]['trimestre'];
        }
                
      $ejec_trimestre=0; 
        if(count($trimestre_ejec)!=0){
          $ejec_trimestre=$trimestre_ejec[0]['trimestre'];
        }


      $prog=$this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$mes_final); /// meta programado al mes 
      $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$mes_final); /// meta ejecutado al mes

      $acu_prog=0;
      $acu_ejec=0;
      if(count($prog)!=0){
        $acu_prog=$prog[0]['trimestre'];
      }
      
      if(count($eval)!=0){
        $acu_ejec=$eval[0]['trimestre'];
      }

      ///------------------------------
      $datos[1]=$prog_trimestre; /// PROGRAMADO
      $datos[2]=$ejec_trimestre; /// EJECUTADO
      $datos[3]=($acu_prog-$acu_ejec); /// DIFERENCIA PROG-EJEC

      
      if(($datos[1]==$datos[2]) || $datos[3]==0){
        $datos[4]='TRIMESTRE CUMPLIDO';
      }
      elseif ($datos[1]!=0 & $datos[2]==0) {
        $datos[4]='TRIMESTRE NO CUMPLIDO';
      }
      else{
       $datos[4]='TRIMESTRE EN PROCESO'; 
      }

      return $datos;
    }


 /*------ TABLA TEMPORALIDAD COMPONENTE -----*/
    public function tabla_temporalidad_componente($matriz,$tip_rep){
      $tabla='';
      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 style="width:100%;"';
      }

      $tabla.='
      <table '.$tab.'>
        <thead>
          <tr style="font-size: 10px;">
            <th></th>';
            for ($i=1; $i <=12 ; $i++) { 
              $tabla.='<th>'.$matriz[1][$i].'</th>';
            }
          $tabla.='
          </tr>
        </thead>
        <tbody>
          <tr style="font-size: 10px;">
            <td ><b>SUMA ACT. PROGRAMADO</b></td>';
          for ($i=1; $i <=12 ; $i++) { 
              $color='';
              if($i<=$this->verif_mes[1]){
                $color='#e4f0fd';
              }

            $tabla.='<td align="right" bgcolor='.$color.'>'.$matriz[2][$i].'</td>';
          }
        $tabla.='
          </tr>
          <tr style="font-size: 10px;">
            <td><b>SUMA ACT. EJECUTADOS</b></td>';
          for ($i=1; $i <=12 ; $i++) { 
            $color='';
              if($i<=$this->verif_mes[1]){
                $color='#e4f0fd';
              }

            $tabla.='<td align="right" bgcolor='.$color.'>'.$matriz[3][$i].'</td>';
          }
        $tabla.='
          </tr>
          <tr style="font-size: 10px;">
            <td><b>CUMPLIMIENTO (%)</b></td>';
          for ($i=1; $i <=12 ; $i++) { 
            $color='';
              if($i<=$this->verif_mes[1]){
                $color='#e4f0fd';
              }

            $tabla.='<td align="right" bgcolor='.$color.'><b>'.$matriz[4][$i].'%</b></td>';
          }
        $tabla.='
          </tr>';
      $tabla.='
        </tbody>
      </table><br>
      <b><font color=blue size=1.5 >GRADO DE CUMPLIMIENTO DE ACT. AL MES DE '.$this->verif_mes[2].' : '.$matriz[4][$this->verif_mes[1]].'%</font></b>';

      return $tabla;
    }  


    /// Matrix temporalidad componente
  public function temporalizacion_x_componente($com_id){
      $mes=$this->mes_nombre();
      $programado=$this->model_componente->componente_temporalidad_programado($com_id);
      $ejecutado=$this->model_componente->componente_temporalidad_ejecutado($com_id);

      for ($i=1; $i <=12 ; $i++) { 
        $temp[1][$i]=0; /// mes
        $temp[2][$i]=0; /// Programado
        $temp[3][$i]=0; /// Ejecutado
        $temp[4][$i]=0; /// Eficacia
      }

     if(count($programado)!=0){
        if(count($ejecutado)!=0){
          for ($i=1; $i <=12 ; $i++) { 
            $temp[3][$i]=round($ejecutado[0]['m'.$i],2);
          }
       }

        for ($i=1; $i <=12 ; $i++) { 
          $temp[1][$i]=$mes[$i];
          $temp[2][$i]=round($programado[0]['m'.$i],2);
          
/*          if($temp[2][$i]!=0){
            $temp[4][$i]=round((($temp[3][$i]/$temp[2][$i])*100),2);
          }*/
        }
     }
     
     $sum_prog=0; $sum_eval=0;
     for ($i=1; $i <=12 ; $i++) { 
       $sum_prog=$sum_prog+$temp[2][$i]; // sum programado
       $sum_eval=$sum_eval+$temp[3][$i]; // sum evaluado

       if($sum_prog!=0){
        $temp[4][$i]=round((($sum_eval/$sum_prog)*100),2);
       }
     }

      return $temp;
    }






    ///// SEGUIMIENTO POA 
    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_servicio($com_id,$trm_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$trm_id; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
        $tr[8][$i]=0; /// en proceso %
      }

      for ($i=1; $i <=$trm_id; $i++) {
        $valor=$this->obtiene_datos_evaluacíon($com_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon($com_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO POR TRIMESTRE -------*/
/*    public function tabla_regresion_lineal_servicio_trimestre($com_id,$trm_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$trm_id; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
        $tr[8][$i]=0; /// en proceso %
      }

      for ($i=1; $i <=$trm_id; $i++) {
        $valor=$this->obtiene_datos_evaluacíon($com_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon($com_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
      }

    return $tr;
    }*/



    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 -------*/
    public function obtiene_datos_evaluacíon($com_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0; $total_programado=0; $total_ejecutado=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evaluacion->nro_operaciones_programadas($com_id,$i); //// Nro de Operaciones
        $suma_programado=$this->model_evaluacion->suma_operaciones_programadas($com_id,$i); /// suma meta trimestral
        
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($suma_programado)!=0){
          $total_programado=$total_programado+$suma_programado[0]['suma_programado'];
        }

        if(count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion));
        }
      
        $suma_evaluado=$this->model_evaluacion->suma_operaciones_ejecutadas($com_id,$i);

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




 /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN ---*/
    public function tabla_regresion_lineal_servicio_total($com_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0; //// total Actividades
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evaluacion->nro_operaciones_programadas($com_id,$i);
        if(count($programado)!=0){
          $total=$total+$programado[0]['total'];
        }
      }

      for ($i=0; $i <=4; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas

        /* Numero de Act. Prog y Evaluados */
        $tr[4][$i]=0; /// % Act. Programado 
        $tr[5][$i]=0; /// % Act. Cumplido
      }

      for ($i=1; $i <=4; $i++) {
        $valor=$this->obtiene_datos_evaluacíon($com_id,$i,1);
        
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas

        /* Numero de Act. Prog y Evaluados */
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Act. Programado 
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Act. Cumplido
        }
      }

    return $tr;
    }




    /// GRAFICOS DE EVALUACION POA 
 public function tabla_acumulada_evaluacion_servicio($regresion,$trm_id,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit[3]='<b>NRO. ACT. CUMPLIDAS</b>';
      $tit[4]='<b>NRO. ACT. NO CUMPLIDAS</b>';
      $tit[5]='<b>% CUMPLIMIENTO</b>';
      $tit[6]='<b>% NO CUMPLIDOS</b>';

      $tit_total[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit_total[3]='<b>NRO. ACT. CUMPLIDAS</b>';
      $tit_total[4]='<b>% ACT. PROGRAMADAS AL TRIMESTRE</b>';
      $tit_total[5]='<b>% ACT. CUMPLIDAS AL TRIMESTRE</b>';

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
                <th>NRO. ACT. PROGRAMADAS</th>
                <th>ACT. EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
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
        </table>';
      }
      elseif($tp_graf==2){ /// Regresion Acumulado al Trimestre
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr >
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
                <td><b>'.$regresion[2][$trm_id].'</b></td>
                <td><b>'.$regresion[2][$trm_id].'</b></td>
                <td><b>'.$regresion[3][$trm_id].'</b></td>
                <td><b>'.$regresion[7][$trm_id].'</b></td>
                <td><b>'.($regresion[2][$trm_id]-($regresion[7][$trm_id]+$regresion[3][$trm_id])).'</b></td>
                <td><b>'.$regresion[5][$trm_id].'%</b></td>
                <td><b>'.$regresion[6][$trm_id].'%</b></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }



    //// Cabecera Notificacion
    public function cuerpo_nota_notificacion($proy_id){
      $mes = $this->mes_nombre();//    $this->mes_nombre();
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
      $tabla='';
      $tabla.='
      <page backtop="40mm" backbottom="35.5mm" backleft="5mm" backright="5mm" pagegroup="new">
      <page_header>
          <br><div class="verde"></div>
          <table class="page_header" border="0" style="width:100%;">
              <tr>
                <td style="width:15%; text-align:center;">
                  
                </td>
                  <td style="width: 70%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr>
                        <td style="width:100%; font-size:30px;" align=center>
                          <b>'.$this->session->userdata('entidad').'</b>
                        </td>
                      </tr>
                      <tr>
                        <td style="width:100%; font-size:15px;" align=center>
                          DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N
                        </td>
                      </tr>
                    </table>
                  </td>
                <td style="width:15%;font-size: 8px;" align=center>
                </td>
              </tr>
          </table>
      </page_header>
      
      <page_footer>
          <hr>
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
              <tr>
                  <td colspan="3"><br><br></td>
              </tr>
              <tr>
                  <td style="width: 33%; text-align: left">
                    POA - '.$this->gestion.' '.$this->resolucion.'
                  </td>
                  <td style="width: 33%; text-align: center">
                  
                  </td>
                  <td style="width: 33%; text-align: right">
                  '.$this->session->userdata('sistema').'    
                  </td>
              </tr>
              <tr>
                  <td colspan="3"><br><br></td>
              </tr>
          </table>
      </page_footer>

        <table border=0 style="width:100%;" align=center>
          <tr>
            <td style="width:91%;font-size: 10px;" align=right>'.strtoupper($proyecto[0]['dep_departamento']).' '.$mes[ltrim(date("m"), "0")]. " de " . date("Y").'</td>
          </tr>
        </table><br><br>

        <table border=0 style="width:96%;" align=center>
            <tr>
                <td style="width:95%;"><b>Señor : </b><br>'.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</td>
            </tr>
            <tr>
                <td style="width:95%;font-size: 13px;">Presente.-</td>
            </tr>
            <tr>
                <td style="width:95%;"><br><br></td>
            </tr>
            <tr>
                <td style="width:95%; font-size: 17px;font-family: Arial;" align=right><b>REF. NOTIFICACI&Oacute;N PARA SEGUIMIENTO POA '.$this->verif_mes[2].' '.$this->gestion.'</b></td>
            </tr>
            <tr>
                <td style="width:95%;"><br></td>
            </tr>
            <tr>
                <td style="width:95%;font-size: 15px;font-family: Arial;text-align: justify;">
                El Departamento Nacional de Planificaci&oacute;n en el marco de sus competencias viene fortaleciendo las tareas de monitoreo y supervisi&oacute;n 
                a traves del Sistema de Planificaci&oacute;n <b>SIIPLAS</b>, en este sentido recordamos a usted efectuar el seguimiento al cumplimiento del POA <b>'.$this->verif_mes[2].' '.$this->gestion.'</b>, de la 
                <b>'.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</b> a su cargo, haciendo enfasis en la programaci&oacute;n mensual y periodo de ejecuci&oacute;n de cada operaci&oacute;n.
                </td>
            </tr>
        </table><br>

        <table border=0 style="width:96%;" align=center>
              <tr>
                  <td style="width:95%;font-size: 15px;font-family: Arial;text-align: justify;">
                  En el mismo sentido, efectuar las gestiones en el plazo programado para la ejecuci&oacute;n de la Solicitud de CERTIFICACI&Oacute;N POA
                  <b>'.$this->verif_mes[2].' '.$this->gestion.'</b>. Recordar que en ambos casos para fines de control y gestión
                  por resultados la responsabilidad del cumplimiento corresponde a su autoridad.
                  </td>
              </tr>
          </table>
  </page>';

      return $tabla;
    }

    /// ACTUALIZAR INFORMACION DE EVALUACION POA (TRIMESTRAL)
    /*---- FUNCION PARA ACTUALIZAR EVALUACION POA POR REGIONAL ----*/
    public function update_evaluacion_poa_regional($dep_id,$tp_id){
      $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
      foreach($unidades as $row){
        $componentes=$this->model_componente->lista_subactividad($row['proy_id']);
        foreach($componentes as $rowc){
          $this->update_evaluacion_operaciones($rowc['com_id']);
        }
      }
    }




  /*---- FUNCION PARA ACTUALIZAR EVALUACION POA POR SERVICIO ----*/
    public function update_evaluacion_operaciones($com_id){
      $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES

      foreach($operaciones as $row){
        ///------- Eliminamos el registro anterior
        $this->eliminando_registro_evaluacion($row['prod_id'],$this->tmes);
        /// ----------
        $temporalidad=$this->obtiene_suma_temporalidad_prog_ejec($row['prod_id']);

        if($row['indi_id']==2 & $row['mt_id']==1){ /// ==== RELATIVO RECURRENTE
          
          if($temporalidad[1]==$temporalidad[2]){ /// Cumplido
            $this->insertando_datos($row['prod_id'],$this->tmes,1,1,'Trimestre Cumplido');
          }
          elseif($temporalidad[1]>$temporalidad[2]){ /// En proceso
            $this->insertando_datos($row['prod_id'],$this->tmes,2,0,'');
          }

        }
        else{ /// ==== ABSOLUTO

        //$vector[1]=$acu_prog_actual; /// Suma Programado al trimestre Actual 
        //$vector[2]=$acu_ejec_actual; /// Suma Ejecutado al trimestre Actual
        //$vector[3]=$acu_prog_anterior; /// Suma Programado al trimestre Anterior
        //$vector[4]=$acu_ejec_anterior; /// Suma Ejecutado al trimestre Anterior

            /*----- Temporalidad Programado / Ejecutado -----*/
            if($temporalidad[1]!=0 & $temporalidad[4]<$row['prod_meta'] & $temporalidad[2]>0 & ($temporalidad[2]<=$temporalidad[1]) & (/*$temporalidad[1]!=$temporalidad[3] & */$temporalidad[2]!=$temporalidad[4])){
                if($temporalidad[3]==$temporalidad[4]){
                  $tp=2;
                  $activo=0;
                  $obs='';
                  if($temporalidad[1]==$temporalidad[2]){
                    $tp=1;
                    $activo=1;
                    $obs='Trimestre Cumplido';
                  }
                 // echo "prod id : ".$row['prod_id']." --> ".$row['prod_producto']." ----Solo un registro<br><br>";
                  $this->insertando_datos($row['prod_id'],$this->tmes,$tp,$activo,$obs);
                }
                elseif($temporalidad[1]==$temporalidad[2]){
                  for ($i=1; $i <=$this->tmes; $i++) { 
                    //$verif_prog=$this->model_seguimientopoa->programado_trimestral_productos($i,$row['prod_id']);
                    if(count($this->model_evaluacion->programado_trimestral_productos($i,$row['prod_id']))!=0){
                      
                      ///------- Eliminamos el registro anterior
                      $this->eliminando_registro_evaluacion($row['prod_id'],$i);
                      /// ----------

                      //// recorrer trimestres anteriores
                      if($i==$this->tmes){
                        $this->insertando_datos($row['prod_id'],$i,1,1,'Trimestre Cumplido');
                      }
                      else{
                        $this->insertando_datos($row['prod_id'],$i,1,0,'Actualizado Trimestre Cumplido '.$i);  
                      }
                      
                    }
                  }

                }
                else{
                  $this->insertando_datos($row['prod_id'],$this->tmes,2,0,'');
                }
              
            }  

        }  
      }
    
    }



    /*--- Obtiene Sumatoria de temporalidad Programado/ejecutado ---*/
    function obtiene_suma_temporalidad_prog_ejec($prod_id){
        /*----- Temporalidad Programado / Ejecutado -----*/
        $prog_actual=$this->model_seguimientopoa->rango_programado_trimestral_productos($prod_id,$this->tmes); /// Suma rango trimestre - Programado Actual
        $eval_actual=$this->model_seguimientopoa->rango_ejecutado_trimestral_productos($prod_id,$this->tmes); /// Suma rango trimestre - Ejecutado Actual

        $acu_prog_actual=0;
        $acu_ejec_actual=0;
        if(count($prog_actual)!=0){
          $acu_prog_actual=$prog_actual[0]['trimestre'];
        }
        if(count($eval_actual)!=0){
          $acu_ejec_actual=$eval_actual[0]['trimestre'];
        }

        /*----- Temporalidad Programado / Ejecutado (Trimestre anterior)-----*/
        $prog_anterior=$this->model_seguimientopoa->rango_programado_trimestral_productos($prod_id,($this->tmes-1)); /// Suma rango trimestre - Programado trimestre anterior
        $eval_anterior=$this->model_seguimientopoa->rango_ejecutado_trimestral_productos($prod_id,($this->tmes-1)); /// Suma rango trimestre - Ejecutado trimestre anterior

        $acu_prog_anterior=0;
        $acu_ejec_anterior=0;
        if(count($prog_anterior)!=0){
          $acu_prog_anterior=$prog_anterior[0]['trimestre'];
        }
        if(count($eval_anterior)!=0){
          $acu_ejec_anterior=$eval_anterior[0]['trimestre'];
        }

        $vector[1]=$acu_prog_actual; /// Suma Programado al trimestre Actual 
        $vector[2]=$acu_ejec_actual; /// Suma Ejecutado al trimestre Actual
        $vector[3]=$acu_prog_anterior; /// Suma Programado al trimestre Anterior
        $vector[4]=$acu_ejec_anterior; /// Suma Ejecutado al trimestre Anterior

      return $vector;
    }



    /*--- eliminando Registro de Evaluacion ---*/
    function eliminando_registro_evaluacion($prod_id,$trimestre){
      $this->db->where('prod_id', $prod_id);
      $this->db->where('trm_id',$trimestre );
      $this->db->delete('_productos_trimestral');
    }


    /*--- Insertando datos de Evaluacion ---*/
    function insertando_datos($prod_id,$trimestre,$tp,$activo,$observacion){
      $data = array(
        'prod_id' => $prod_id,
        'trm_id' => $trimestre,
        'tp_eval' => $tp,
        'g_id' => $this->gestion,
        'fun_id' => $this->fun_id,
        'testado' => 2,
        'activo' => $activo,
        'eval_observacion' => $observacion,
      );
      $this->db->insert('_productos_trimestral',$data);
      $tprod_id=$this->db->insert_id();

      return $tprod_id;
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



    /*-- LISTA DE FORMULARIOS REPORTE DE SEGUIMIENTO Y EVALUACION POA --*/
    public function formularios_mensual($com_id){
      $tabla='';
      $meses = $this->model_configuracion->get_mes();

      $tabla.='
          <div class="btn-group">
            <a class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_cascade.png" WIDTH="19" HEIGHT="18"/>&nbsp; FORM. SEGUIMIENTO Y EVALUACIÓN POA </a>
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
            <ul class="dropdown-menu">';
              foreach($meses as $rowm){
              if($rowm['m_id']<=$this->verif_mes[1]){
                $tabla.='
                <li>
                  <a href="'.site_url("").'/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$rowm['m_id'].'" target="_blank">REPORTE SEGUIMIENTO POA - '.$rowm['m_descripcion'].'</a>
                </li>';
              }                     
            }
            $tabla.='
            <hr>';
              for ($i=1; $i <=$this->tmes; $i++) { 
                $trimestre=$this->model_evaluacion->get_trimestre($i); /// Datos del Trimestre
                $tabla.='
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$com_id.'/'.$i.'\');" >REP. EVAL. POA - '.$trimestre[0]['trm_descripcion'].'</a>
                </li>';
              }
            
            $tabla.='
            </ul>
          </div>';

      return $tabla;
    }



    /*--- UPDATE DATOS DE EVALUACION POA 2021 planificadores ---*/
    function button_update_($com_id){
      $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      $tabla='';

      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");

      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

          if (($date_actual >= $date_inicio) && ($date_actual <= $date_final) || $this->tp_adm==1){
            if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0 || $this->tp_adm==1){

              $tabla.='   
                <div id="row">
                  <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-info" role="alert">
                      <a href="#" data-toggle="modal" data-target="#modal_update_eval" class="btn btn-primary update_eval" style="width:20%;" name="'.$com_id.'" id="'.strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_cod']).' - '.strtoupper($componente[0]['serv_descripcion']).'" title="ACTUALIZAR EVALUACION POA" ><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="30"/>&nbsp;ACTUALIZAR DATOS PARA EVALUACI&Oacute;N POA</a>    
                    </div>
                  </article>
                </div>';
            }
          }
      }

      return $tabla;
    }


    /*--- UPDATE DATOS DE EVALUACION POA 2021 Unidades, Establecimientos ---*/
    function button_update_sa($com_id){
      $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      $tabla='';

      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");

      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

          if (($date_actual >= $date_inicio) && ($date_actual <= $date_final)){
            $tabla.='   
              <div id="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="alert alert-info" role="alert">
                    <a href="#" data-toggle="modal" data-target="#modal_update_eval" class="btn btn-primary update_eval" style="width:20%;" name="'.$com_id.'" id="'.strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_cod']).' - '.strtoupper($componente[0]['serv_descripcion']).'" title="ACTUALIZAR EVALUACION POA" ><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="25"/>&nbsp;ACTUALIZAR DATOS PARA EVALUACI&Oacute;N POA</a>    
                  </div>
                </article>
              </div>';
          }
      }

      return $tabla;
    }

    /*--- BOTON REPORTE SEGUIMIENTO POA (MES VIGENTE)---*/
    function button_rep_seguimientopoa($com_id){
      $tabla='';
        $tabla.='
                <a href="javascript:abreVentana(\''.site_url("").'/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$this->verif_mes[1].'\');" class="btn btn-default" title="IMPRIMIR SEGUIMIENTO POA">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;&nbsp;<b>IMPRIMIR SEGUIMIENTO POA ('.$this->verif_mes[2].')</b>
                </a>';
      return $tabla;
    }


    /*------ TABLA TEMPORALIDAD COMPONENTE -----*/
    public function aviso_seguimiento_evaluacion_poa(){
      $tabla='';
      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

          if (($date_actual >= $date_inicio) && ($date_actual <= $date_final) || $this->tp_adm==1){
            $tabla.='<h2 class="alert alert-success"><center>PROCESO DE EVALUACIÓN POA - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</center></h2>';
          }
          else{
            $tabla.='<h2 class="alert alert-info"><center>SEGUIMIENTO POA - MES '.$this->verif_mes[2].' / '.$this->gestion.'</center></h2>';
          }
      }
      else{
        $tabla.='<h2 class="alert alert-info"><center>SEGUIMIENTO POA - MES '.$this->verif_mes[2].' / '.$this->gestion.'</center></h2>';
      }


      return $tabla;
    }


    /*--- BOTON REPORTE EVALUACION POA (TRIMESTRE VIGENTE)---*/
    function button_rep_evaluacion($com_id){
      $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      $tabla='';

      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");

      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

          if (($date_actual >= $date_inicio) && ($date_actual <= $date_final) || $this->tp_adm==1){
            if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0 || $this->tp_adm==1){

              $tabla.='
                <a href="javascript:abreVentana(\''.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$com_id.'/'.$this->tmes.'\');" class="btn btn-default" title="IMPRIMIR EVALUACIÓN POA">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>IMPRIMIR EVALUACI&Oacute;N POA '.$this->gestion.'</b>
                </a>';
            }
          }
      }

      return $tabla;
    }



    /*-- FORMULARIOS POA ACTUALIZADOS FORM 4, FORM 5 --*/
    public function formularios_poa($com_id,$proy_id){
      $tabla='';
      $meses = $this->model_configuracion->get_mes();

      $tabla.='<div class="btn-group" >
                  <a class="btn btn-default">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORMULARIOS POA GESTIÓN - '.$this->gestion.'&nbsp;&nbsp;&nbsp;&nbsp;</a>
                  <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" ><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="javascript:abreVentana(\''.site_url("").'/prog/reporte_form4/'.$com_id.'\');" >FORMULARIO N°4 (OPERACIONES)</a>
                    </li>
                    <li>
                      <a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$com_id.'\');">FORMULARIO N°5 (REQUERIMIENTOS)</a>
                    </li>
                    <hr>
                    <li>
                      <a href="javascript:abreVentana(\''.site_url("").'/rep/rep_requerimientos_ejecucion_servicio/'.$com_id.'\');">FORMULARIO N°5 (EJECUCIÓN POA)</a>
                    </li>
                  </ul>
                </div>';

      return $tabla;
    }


    /*-------- Verif Boton Evaluacion ---------*/
    function verif_btn_evaluacionpoa(){
      $tabla='';

      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");

      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final
          $date_trimestre = $configuracion[0]['conf_mes_otro']; /// Trimestre
          $meses=$this->model_configuracion->list_mes_trimestre($date_trimestre);
        //  $tabla.='Datos : '.$date_actual.'--'.$date_inicio.'--'.$date_actual.'--'.$date_final;

          /*if (($date_actual >= $date_inicio) && ($date_actual <= $date_final) || $this->tp_adm==1){
            if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0 || $this->tp_adm==1){*/
            if (($date_actual >= $date_inicio) && ($date_actual <= $date_final)|| $this->tp_adm==1){
              if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0){

              $tabla.='
              <section class="col col-3">
                <select class="form-control" id="mes_id" name="mes_id" title="SELECCIONE MES A EVALUAR">
                  <option value="0" selected>Seleccione mes para Evaluacion POA ...</option>';
                foreach($meses as $row){
                  //if($this->verif_mes[1]<=$row['m_id']){
                    if($row['m_id']==$this->verif_mes[1]){ 
                      $tabla.='<option value="'.$row['m_id'].'" selected>'.$row['m_descripcion'].'</option>';
                    }
                    else{ 
                      $tabla.='<option value="'.$row['m_id'].'" >'.$row['m_descripcion'].'</option>';
                    } 
                  //}                     
                }
               $tabla.='
                </select>
              </section>';
            }
          }
      }

      return $tabla;
    }





    /*--- LISTA DE  OPERACIONES PROGRAMADOS EN EL MES ACTUAL 2021 ---*/
    function lista_operaciones_programados($com_id,$mes_id,$regresion){
      $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES
      $tabla='';
      $tabla.=' 
      <form class="smart-form" method="post">
        <input name="mes_activo" type="hidden" value='.$this->verif_mes[1].'>
        <input name="base" type="hidden" value="'.base_url().'">
        <fieldset><div id="eficacia" style="font-family: Arial;font-size: 10%;">'.$this->calificacion_eficacia($regresion[5][$this->tmes]).'</div></fieldset>
        <div id="efi"></div> 
        <fieldset>          
          <div class="row">
            <section class="col col-3">
              <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/>
            </section>
            '.$this->verif_btn_evaluacionpoa().''; /// Meses del trimestre
/*            if($this->tp_adm==1){
              $meses = $this->model_configuracion->get_mes();
              $tabla.='
              <section class="col col-3">
                <select class="form-control" id="mes_id" name="mes_id" title="SELECCIONE MES">';
                foreach($meses as $row){
                  if($row['m_id']<=ltrim(date("m"), "0")){
                    if($row['m_id']==$this->verif_mes[1]){ 
                      $tabla.='<option value="'.$row['m_id'].'" selected>'.$row['m_descripcion'].'</option>';
                    }
                    else{ 
                      $tabla.='<option value="'.$row['m_id'].'" >'.$row['m_descripcion'].'</option>';
                    } 
                  }                     
                }
               $tabla.='
                </select>
              </section>';
            }*/
            $tabla.='
            <div id="loading" style="display:none;" style="width:20%;">
              <img src="'.base_url().'/assets/img/cargando-loading-039.gif" width="40%" height="30%">
            </div>
          </div>
          
        </fieldset>
        <fieldset>
          <div class="table-responsive">
          <table class="table table-bordered"width="100%" id="datos">
              <thead>                 
                <tr>
                  <th style="width:1%;"></th>
                  <th style="width:1%;"><b>COD. OPE.</b></th>
                  <th style="width:1%;"><b>COD. ACT.</b></th>
                  <th style="width:13%;">ACTIVIDAD</th>
                  <th style="width:8%;">INDICADOR</th>
                  <th style="width:3%;">META TOTAL</th>
                  <th style="width:5%;">META NO EJECUTADO</th>
                  <th style="width:5%;">PROGRAMADO MES '.$this->verif_mes[2].'</th>
                  <th style="width:5%;">EJECUTADO MES '.$this->verif_mes[2].'</th>
                  <th style="width:8%;">MEDIO DE VERIFICACI&Oacute;N</th>
                  <th style="width:8%;">PROBLEMAS PRESENTADOS</th>
                  <th style="width:8%;">ACCIONES REALIZADOS</th>
                  <th style="width:2%;"></th>
                  <th style="width:2%;">ELIMINAR</th>
                  <th style="width:3%;"></th>
                </tr>
              </thead>
              <tbody>';
              $nro=0;
              foreach($operaciones as $row){
                $indi_id='';
                if($row['indi_id']==2 & $row['mt_id']==1){
                  $indi_id='%';
                }
                $diferencia=$this->verif_valor_no_ejecutado($row['prod_id'],$mes_id);
                if($diferencia[1]!=0 || $diferencia[2]!=0){
                  $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($row['prod_id'],$mes_id); // Ejecutado
                //  $nejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($row['prod_id'],$mes_id); // no Ejec
                  $nro++;
                  $tabla.='
                  <tr>
                    <td align=center bgcolor="#f6fbf4">
                      '.$nro.'
                    </td>
                    <td style="width:1%;" align=center bgcolor="#f6fbf4"><b>'.$row['or_codigo'].'</b></td>
                    <td style="width:1%;" align=center bgcolor="#f6fbf4" title="'.$row['prod_id'].'"><b>'.$row['prod_cod'].'</b></td>
                    <td bgcolor="#f6fbf4">'.$row['prod_producto'].'</td>
                    <td bgcolor="#f6fbf4">'.$row['prod_indicador'].'</td>
                    <td align=right bgcolor="#f6fbf4"><b>'.round($row['prod_meta'],2).''.$indi_id.'</b></td>
                    <td align=center bgcolor="#f7e1e2">'.$diferencia[1].'</td>
                    <td align=center bgcolor="#f6fbf4">'.$diferencia[2].''.$indi_id.' <input type="hidden" name="pg_fis[]" value="'.($diferencia[1]+$diferencia[2]).'"></td>';
                    if(count($ejec)!=0){
                      $tabla.='
                      <td>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" id=ejec'.$nro.' value="'.round($ejec[0]['pejec_fis'],2).'" onkeyup="verif_valor('.($diferencia[1]+$diferencia[2]).',this.value,'.$nro.');" title="'.($diferencia[1]+$diferencia[2]).'" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                        </label>
                      </td>
                      <td>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" id=mv'.$nro.' title="MEDIO DE VERIFICACIÓN">'.$ejec[0]['medio_verificacion'].'</textarea>
                        </label>
                      </td>
                      <td>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" id=obs'.$nro.' title="PROBLEMAS PRESENTADOS">'.$ejec[0]['observacion'].'</textarea>
                        </label>
                      </td>
                      <td>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" id=acc'.$nro.' title="ACCIONES REALIZADOS">'.$ejec[0]['acciones'].'</textarea>
                        </label>
                      </td>
                      <td align=center title="MODIFICAR SEGUIMIENTO POA">
                        <div id="but'.$nro.'"><button type="button" name="'.$row['prod_id'].'" id="'.$nro.'" onclick="guardar('.$row['prod_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/drive_disk.png" WIDTH="40" HEIGHT="40"/><br>MODIFICAR</button></div>
                      </td>
                      <td align="center">
                        <br>
                        <a href="#" data-toggle="modal" data-target="#modal_del_ope" class="btn btn-default del_ope" title="ELIMINAR EVALUACIÓN POA"  name="'.$row['prod_id'].'" id="'.$mes_id.'">
                          <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                        </a>
                      </td>';
                    }
                    else{
                      $tabla.='
                      <td>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" id=ejec'.$nro.' value="0" onkeyup="verif_valor('.($diferencia[1]+$diferencia[2]).',this.value,'.$nro.');" title="'.($diferencia[1]+$diferencia[2]).'"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                        </label>
                      </td>';
                      $no_ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($row['prod_id'],$mes_id);
                      if(count($no_ejec)!=0){
                        $tabla.='
                          <td>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" id=mv'.$nro.' title="MEDIO DE VERIFICACIÓN">'.$no_ejec[0]['medio_verificacion'].'</textarea>
                            </label>
                          </td>
                          <td>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" id=obs'.$nro.' title="PROBLEMAS PRESENTADOS">'.$no_ejec[0]['observacion'].'</textarea>
                            </label>
                          </td>
                          <td>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" id=acc'.$nro.' title="ACCIONES REALIZADOS">'.$no_ejec[0]['acciones'].'</textarea>
                            </label>
                          </td>
                          <td align=center title="MODIFICAR SEGUIMIENTO POA">
                            <div id="but'.$nro.'"><button type="button" name="'.$row['prod_id'].'" id="'.$nro.'" onclick="guardar('.$row['prod_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/drive_disk.png" WIDTH="40" HEIGHT="40"/><br>MODIFICAR</button></div>
                          </td>
                          <td align="center">
                            <br>
                            <a href="#" data-toggle="modal" data-target="#modal_del_ope" class="btn btn-default del_ope" title="ELIMINAR EVALUACIÓN POA"  name="'.$row['prod_id'].'" id="'.$mes_id.'">
                              <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                            </a>
                          </td>';
                      }
                      else{
                        $tabla.='
                        <td>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" id=mv'.$nro.' title="MEDIO DE VERIFICACIÓN"></textarea>
                          </label>
                        </td>
                        <td>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" id=obs'.$nro.' title="PROBLEMAS PRESENTADOS"></textarea>
                          </label>
                        </td>
                        <td>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" id=acc'.$nro.' title="ACCIONES REALIZADOS"></textarea>
                          </label>
                        </td>';
                        $tabla.='
                        <td align=center title="GUARDAR SEGUIMIENTO POA">
                          <div id="but'.$nro.'" style="display:none;"><button type="button" name="'.$row['prod_id'].'" id="'.$nro.'" onclick="guardar('.$row['prod_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="37" HEIGHT="37"/><br>GUARDAR</button></div>
                        </td>
                        <td align="center">
                        </td>';
                      }
                      
                    }
                    $tabla.='
                    <td align="center"><br>'.$this->calificacion_operacion($row['prod_id'],$diferencia).'</td>
                  </tr>';
                }
              }
              $tabla.='
              </tbody>
          </table>
          </div>
        </fieldset>   
        </form>';

      return $tabla;
    }


    /*---- CALIFICACIÓN POR CADA OPERACION ----*/
    public function calificacion_operacion($prod_id,$valor){
      $programado=($valor[1]+$valor[2]);
      $ejecutado=$valor[3];

      $titulo='<button type="button" data-toggle="modal" data-target="#modal_nuevo_ff2" class="btn btn-danger enlace" name="'.$prod_id.'" style="width:80%; height:30px">No Cumplido...</button>';
      if($ejecutado==$programado){
        $titulo='<button type="button" data-toggle="modal" data-target="#modal_nuevo_ff2" class="btn btn-success enlace" name="'.$prod_id.'" style="width:80%; height:30px">Cumplido....</button>';
      }
      elseif($ejecutado<$programado & $ejecutado!=0){
        $titulo='<button type="button" data-toggle="modal" data-target="#modal_nuevo_ff2" class="btn btn-warning enlace" name="'.$prod_id.'" style="width:80%; height:30px">En Proceso...</button>';
      }

      return $titulo;

    }



    ///// PARA LA NOTIFICACION POA POR PROYECTO
    public function lista_subactividades_a_notificar($subactividades){
      $tabla='';
      $nro_pag=0;
      foreach($subactividades as $rowu){ $nro_pag++; 
        $tabla.=$this->get_notificacion_subactividad($rowu['com_id']); //// Get Notificacion por Subactividad
      }
      return $tabla;
    }


    //// NOTIFICACION DE LA UNIDAD RESPONSABLE
    public function get_notificacion_subactividad($com_id){
      $componente=$this->model_componente->get_componente($com_id,$this->gestion);
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']); /// PROYECTO
      $mes = $this->mes_nombre();

      $tabla='';
      $tabla.='
        <page backtop="55mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
        <page_header>
            <br><div class="verde"></div>
            <table class="page_header" border="0" style="width:100%;">
              <tr>
                <td style="width:15%; text-align:center;">
                  
                </td>
                  <td style="width: 70%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr>
                        <td style="width:100%; font-size:30px;" align=center>
                          <b>'.$this->session->userdata('entidad').'</b>
                        </td>
                      </tr>
                      <tr>
                        <td style="width:100%; font-size:15px;" align=center>
                          DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N
                        </td>
                      </tr>
                    </table>
                  </td>
                <td style="width:15%;font-size: 8px;" align=center>
                </td>
              </tr>
            </table><br>
            <table border=0 style="width:92.5%;" align=center>
              <tr>
                <td style="width:95%;font-size: 10px;" align=right>'.strtoupper($proyecto[0]['dep_departamento']).' '.$mes[ltrim(date("m"), "0")]. " de " . date("Y").'</td>
              </tr>
            </table><br>
        </page_header>
        <page_footer>
        <hr>
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33%; text-align: left">
                  '.$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa').'
                </td>
                <td style="width: 33%; text-align: center">
                  '.$this->session->userdata('sistema').'
                </td>
                <td style="width: 33%; text-align: right">
                  '.$mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
              <td colspan="3"><br></td>
            </tr>
          </table>
        </page_footer>';

        $titulo1=strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_descripcion']).' - '.$proyecto[0]['abrev'];
        $titulo2=strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_descripcion']).' - '.$proyecto[0]['abrev'];
        if($proyecto[0]['ta_id']==2){ /// Establecimiento de salud
            $titulo1=$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'];
            $titulo2=$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'];
        }

        /// Formulario N 4
        $operaciones=$this->model_seguimientopoa->operaciones_programados_x_mes($com_id,$this->verif_mes[1]); /// lISTA DE OPERACIONES
        $tabla.='
        <table border=0 style="width:97%;" align=center>
          <tr>
              <td style="width:95%;"><b>Señor(es) : </b> <br>'.$titulo1.'<br>Presente .-</td>
          </tr>
          <tr>
              <td style="width:95%;"><br><br></td>
          </tr>
          <tr>
              <td style="width:95%; font-size: 16px;font-family: Arial;" align=right><b>REF. NOTIFICACI&Oacute;N PARA SEGUIMIENTO POA '.$this->verif_mes[2].' '.$this->session->userdata('gestion').'</b></td>
          </tr>
          <tr>
              <td style="width:95%;"><br></td>
          </tr>
          <tr>
              <td style="width:95%;text-align: justify;">
              El Departamento Nacional de Planificaci&oacute;n en el marco de sus competencias viene fortaleciendo las tareas de monitoreo y supervisi&oacute;n 
              a traves del Sistema de Planificaci&oacute;n <b>SIIPLAS</b>, en este sentido recordamos a usted efectuar el seguimiento al cumplimiento del POA <b>'.$this->verif_mes[2].'</b> '.$this->session->userdata('gestion').', de 
              <b>'.$titulo2.'</b> a su cargo, haciendo enfasis en la programaci&oacute;n mensual y periodo de ejecuci&oacute;n de cada operaci&oacute;n.
              </td>
          </tr>
        </table>
        <br>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:98%;" align=center>
            <thead>
              <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                <th style="width:2%; height:20px;"></th>
                <th style="width:3%;"><b>COD. OPE.</b></th>
                <th style="width:3%;"><b>COD. ACT.</b></th>
                <th style="width:30%;">ACTIVIDAD</th>
                <th style="width:20%;">INDICADOR</th>
                <th style="width:20%;">MEDIO DE VERIFICACI&Oacute;N</th>
                <th style="width:5%;">META ANUAL</th>
                <th style="width:5%;">PROG. MES</th>
                <th style="width:5%;">EJEC.</th>
              </tr>
            </thead>
            <tbody>';
            $nro_ope=0;
            foreach ($operaciones as $row) {
              $ejec=$this->model_producto->verif_ope_evaluado_mes($row['prod_id'],$this->verif_mes[1]);
              $evaluado=0;
                if(count($ejec)!=0){
                  $evaluado=$ejec[0]['pejec_fis'];
                }

              $nro_ope++;
              $tabla.= '
                <tr>
                  <td align=center style="height:20px; width:2%;">'.$nro_ope.'</td>
                  <td align=center style="font-size: 10px; width:3%;">'.$row['or_codigo'].'</td>
                  <td align=center style="font-size: 10px; width:3%;">'.$row['prod_cod'].'</td>
                  <td style="width:30%;">'.$row['prod_producto'].'</td>
                  <td style="width:20%;">'.$row['prod_indicador'].'</td>
                  <td style="width:20%;">'.$row['prod_fuente_verificacion'].'</td>
                  <td style="width:5%;font-size: 9px; text-align:right">'.round($row['prod_meta'],2).'</td>
                  <td style="width:5%;font-size: 9px; text-align:right"><b>'.round($row['pg_fis'],2).'</b></td>
                  <td style="width:5%;font-size: 9px; text-align:right"><b>'.round($evaluado,2).'</b></td>
                </tr>';
            }
        $tabla.= '
            </tbody>
          </table>';

        $requerimientos=$this->model_notificacion->list_requerimiento_mes($proyecto[0]['proy_id'],$com_id,$this->verif_mes[1]);
        if(count($requerimientos)!=0){

        $tabla.= '
            <br>
            <table border=0 style="width:97%;" align=center>
                <tr>
                    <td style="width:95%;text-align: justify;">
                    En el mismo sentido, efectuar las gestiones en el plazo programado para la ejecuci&oacute;n de la Solicitud de CERTIFICACIÓN POA
                    <b>'.$this->verif_mes[2].' '.$this->gestion.'</b>. Recordar que en ambos casos para fines de control y gestión por resultados la 
                    responsabilidad del cumplimiento corresponde a su autoridad.
                    </td>
                </tr>
            </table>
            <br>

            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:98%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                    <th style="width:1.5%; height:20px;">#</th>
                    <th style="width:3%;"><b>COD. ACT.</b></th>
                    <th style="width:7%;"><b>PARTIDA</b></th>
                    <th style="width:25%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD DE MEDIDA</th>
                    <th style="width:7%;">CANTIDAD</th>
                    <th style="width:8%;">PRECIO UNITARIO</th>
                    <th style="width:8%;">PRECIO TOTAL</th>
                    <th style="width:8%;">PROG. MES</th>
                    <th style="width:15%;">OBSERVACI&Oacute;N</th>
                  </tr>
                </thead>
                <tbody>';
                $nro_req=0;$suma=0;
                foreach ($requerimientos as $row) {
                    $suma=$suma+$row['ipm_fis'];
                    $nro_req++;
                    $tabla.= '
                    <tr>
                        <td align=center style="height:20px; width:1.5%;">'.$nro_req.'</td>
                        <td align=center style="font-size: 10px; width:3%;">'.$row['prod_cod'].'</td>
                        <td align=center style="font-size: 10px; width:7%;">'.$row['par_codigo'].'</td>
                        <td style="width:25%;">'.$row['ins_detalle'].'</td>
                        <td style="width:10%;">'.$row['ins_unidad_medida'].'</td>
                        <td style="width:7%;" align=right>'.round($row['ins_cant_requerida'],2).'</td>
                        <td style="width:8%;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                        <td style="width:8%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                        <td style="width:8%;font-size: 9px;" align=right><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
                        <td style="width:15%;" align=left>'.$row['ins_observacion'].'</td>
                    </tr>';
                }
        $tabla.= '  
                <tr>
                    <td colspan=8 style="height:15px;" align=right><b>TOTAL MONTO A CERTIFICAR </b></td>
                    <td align=right>'.number_format($suma, 2, ',', '.').'</td>
                    <td></td>
                </tr>
                </tbody>
            </table>';
        }
    $tabla.='
        </page>';

      return $tabla;
    }











    /// TEMPORALIDAD OPERACIONES (PROGRAMADO-EJECUTADO) (Antiguo)
    public function temporalizacion_productos($prod_id){

      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
          $pa=$pa+$prod_prog[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $matriz[2][$i]=$pa;
          }
          else{
            $matriz[2][$i]=$matriz[1][$i];
          }

          
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[3][$i]=round(((($matriz[2][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
          }
          else{
            $ea=$matriz[4][$i];
          }

          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[6][$i]=round(((($matriz[5][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }
      
      return $matriz;
    }



    /*---VERIFICANDO VALORES NO EJECUTADOS EN MESES ANTERIORES (OPERACIONES)--*/
    function verif_valor_no_ejecutado($prod_id,$mes){
      $producto=$this->model_producto->get_producto_id($prod_id);
      $diferencia[1]=0;$diferencia[2]=0;$diferencia[3]=0;
      $sum_prog=0;
      $sum_ejec=0;
      for ($i=1; $i <=$mes-1; $i++) { 
        $prog=$this->model_seguimientopoa->get_programado_poa_mes($prod_id,$i); /// Programado meses anteriores
        if(count($prog)!=0){
          $sum_prog=$sum_prog+$prog[0]['pg_fis'];
        }

        $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i); /// Ejecutado meses anteriores
        if(count($ejec)!=0){
          $sum_ejec=$sum_ejec+$ejec[0]['pejec_fis'];
        }
      }



      $prog=$this->model_seguimientopoa->get_programado_poa_mes($prod_id,$mes); /// Programado mes actual
      $diferencia[2]=0;
      if(count($prog)!=0){
        $diferencia[2]=round($prog[0]['pg_fis'],2);
      }

      $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$mes); /// Ejecutado mes actual
      $diferencia[3]=0;
      if(count($ejec)!=0){
        $diferencia[3]=round($ejec[0]['pejec_fis'],2);
      }

      $diferencia[1]=($sum_prog-$sum_ejec); /// no ejecutado en el mes anterior
      if($producto[0]['indi_id']==2 & $producto[0]['mt_id']==1){
        $diferencia[1]=0;
      }
      
      return $diferencia;
    }

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

    /*--- Actualiza mes ---*/
    public function update_mes_gestion($mes_id){
      $valor=$mes_id;
      
      $mes=$this->mes_nombre_completo($valor);
      $datos[1]=$valor; // numero del mes
      $datos[2]=$mes[$valor]; // mes
      $datos[3]=$this->gestion; // Gestion

      return $datos;
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

    //// Menu Administrador Normal
    public function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
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

  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_segpoa($com_id,$tp){
      $tabla='';
      $tabla.='
      <aside id="left-panel">
        <div class="login-info">
          <span>
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
              <span>
                <i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;'.$this->session->userdata("user_name").'
              </span>
              <i class="fa fa-angle-down"></i>
            </a>
          </span>
        </div>
        <nav>
          <ul>
            <li class="">
            <a href="'.site_url("").'/dashboar_seguimiento_poa" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
            </li>';
              if($tp==1){
                $tabla.='
                <li class="text-center">
                  <a href="#" title="REGISTRO DE SEGUIMIENTO"> <span class="menu-item-parent">SEG. EVAL. POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Seg. y eval. POA</span></a>
                </li>';
              }
              elseif ($tp==2) {
                $tabla.='
                <li class="text-center">
                  <a href="#" title="SOLICITUD DE CERTIFICACION POA"> <span class="menu-item-parent">CERTIFICACIÓN POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Certificación POA</span></a>
                  <ul>
                    <li>
                      <a href="'.site_url("").'/solicitar_certpoa/'.$com_id.'">Solicitar Certificación POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                    </li>
                    <li>
                      <a href="'.site_url("").'/mis_solicitudes_cpoa/'.$com_id.'">Mis Solicitudes POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                    </li>
                  </ul>
                </li>';
              }
              elseif ($tp==3) {
                $tabla.='
                <li class="text-center">
                  <a href="#" title="REPORTE POA"> <span class="menu-item-parent">REPORTES POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Reportes POA</span></a>
                </li>';
              }
            $tabla.='
            
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }

    
}

?>