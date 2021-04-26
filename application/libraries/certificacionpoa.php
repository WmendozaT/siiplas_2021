<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Certificacionpoa extends CI_Controller{
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
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
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
    }


    //// SELECCION DE OPERACIONES 
  public function select_mis_productos($com_id,$titulo){
    $productos=$this->model_certificacion->get_operaciones_x_subactividad_ppto($com_id);
    $tabla='';
    $tabla='
      <form class="form-horizontal">
        <input name="base" type="hidden" value="'.base_url().'">
        <fieldset>
          <legend><b>'.$titulo.'</b></legend>
          <div class="form-group">
            <label class="col-md-2 control-label">SELECCIONE OPERACI&Oacute;N</label>
            <div class="col-md-6">
              <select class="form-control" name="prod_id" id="prod_id">
                <option value="0">Seleccione Operación</option>';
               foreach($productos as $row){
                  $tabla.='<option value="'.$row['prod_id'].'">'.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
                }
               $tabla.='
              </select> 
            </div>
          </div>
        </fieldset>
      </form>';
    return $tabla;
  }





/*------- LISTA DE REQUERIMIENTOS PRE LISTA ------*/
  public function list_requerimientos_prelista($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
          }

          if($monto_certificado!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                }
                else{
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                }
                $tabla.='
                <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>';
                if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                  for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                }
                else{
                  $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  for ($j=1; $j <=12 ; $j++) {
                    $bgcolor='';
                    if($temp[0]['mes'.$j.'']!=0){
                      $bgcolor='#d5f5f0';
                    }
                    $tabla.='
                    <td align="right" bgcolor='.$bgcolor.'>
                      '.number_format($temp[0]['mes'.$j.''], 2, ',', '.').'
                    </td>';
                  }
                }

                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }


/*------- LISTA DE REQUERIMIENTOS NORMAL ------*/
  public function list_requerimientos($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
            

            if($monto_certificado==$row['ins_costo_total']){
              $verif=1;
              $color_tr="#f7d6dc";
            }
            elseif($monto_certificado<$row['ins_costo_total']){
              $color_tr="#f6f7cb";
            }
          }

          if($monto_certificado!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr bgcolor="'.$color_tr.'" title='.$row['ins_id'].'>
                <td>'.$nro.'</td>
                <td>';
                  if($verif==0){
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>
                            <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">';
                  }
                $tabla.='
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>
                <td align=right bgcolor="#e7f5f3">'.number_format($monto_certificado, 2, ',', '.').'</td>';

                for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right >';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>
                        </tr>
                      </table>
                    </td>';
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



/*-- DATOS CITE --*/
  public function datos_cite($solicitud){
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="font-size: 13px;font-family: Arial;">
              <td colspan="2" style="width:100%;height: 30%;text-align:right;"><b>FORMULARIO CERT. N° 10&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          </tr>
          <tr style="font-size: 10px;font-family: Arial;">
              <td style="width:50%;height: 30%;"><b>CITE : </b>'.$solicitud[0]['cite'].'</td>
              <td style="width:50%;height: 30%"><b>FECHA : </b>'.date('d-m-Y',strtotime($solicitud[0]['fecha'])).'</td>
          </tr>
      </table>';

    return $tabla;
  }

  /*-- I y II UNIDAD ORGANIZACIONAL SOLICITANTE y ARTICULACION --*/
  public function datos_unidad_organizacional($solicitud){
    $tabla='';
    $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>I. UNIDAD ORGANIZACIONAL SOLICITANTE</b></td>
                        </tr>
                    </table><br>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.strtoupper ($solicitud[0]['dep_departamento']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.strtoupper ($solicitud[0]['dist_distrital']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.$solicitud[0]['aper_actividad'].' '.strtoupper ($solicitud[0]['act_descripcion']).' '.$solicitud[0]['abrev'].'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>SUBACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 1.5%;">&nbsp;'.$solicitud[0]['tipo_subactividad'].' '.$solicitud[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>        
        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>II. ARTICULACI&Oacute;N POA 2021 Y PEI 2016-2020</b></td>
                        </tr>
                    </table><br>
                    <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <thead>
                            <tr style="font-size: 8px; font-family: Arial;" align="center" >
                                <th style="width:5%;height: 1.5%;">COD. OPE.</th>
                                <th style="width:30%;">OPERACI&Oacute;N</th>
                                <th style="width:5%;">COD. OR.</th>
                                <th style="width:30%;">OBJETIVO REGIONAL</th>
                                <th style="width:30%;">ACCIÓN ESTRATEGICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:5%;height: 4%;font-size: 13px;" align="center"><b>'.$solicitud[0]['prod_cod'].'</b></td>
                                <td style="width:30%;">'.$solicitud[0]['prod_producto'].'</td>
                                <td style="width:5%;font-size: 13px;" align="center"><b>'.$solicitud[0]['or_codigo'].'</b></td>
                                <td style="width:30%;">'.$solicitud[0]['or_objetivo'].'</td>
                                <td style="width:30%;"><b>'.$solicitud[0]['acc_codigo'].'</b> '.$solicitud[0]['acc_descripcion'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>III. DETALLE DE ITEM PARA CERTIFICACIÓN POA DEL FORMULARIO POA N°5</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>';

    return $tabla;
  }




  /*-- III DETALLE DE REQUERIMIENTOS A SOLICITUD --*/
  public function lista_solicitud_requerimientos($sol_id){
    $tabla='';

    $requerimientos=$this->model_certificacion->get_lista_requerimientos_solicitados($sol_id);
    $tabla.='
      <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <thead>
              <tr style="font-size: 8px; font-family: Arial;" align="center" >
                <th style="width:3%;height: 1.5%;">N°</th>
                <th style="width:7%;">PARTIDA</th>
                <th style="width:28.9%;">DETALLE REQUERIMIENTO</th>
                <th style="width:10%;">UNIDAD DE MEDIDA</th>
                <th style="width:9%;">CANTIDAD</th>
                <th style="width:9%;">PRECIO UNITARIO</th>
                <th style="width:9%;">PRECIO TOTAL</th>
                <th style="width:9%;">MONTO SOLICITADO</th>
                <th style="width:15%;">TEMPORALIDAD SELECCIONADO</th>
              </tr>
          </thead>
          <tbody>';
            $nro=0;$suma_monto=0;
            foreach($requerimientos as $row){
              $nro++;
              $suma_monto=$suma_monto+$row['monto_solicitado'];
              $tabla.='
              <tr style="font-size: 8px; font-family: Arial;">
                <td style="width:3%;height: 3%;" align=center>'.$nro.'</td>
                <td style="width:7%;" align=center>'.$row['par_codigo'].'</td>
                <td style="width:28.9%;">'.$row['ins_detalle'].'</td>
                <td style="width:10%;">'.$row['ins_unidad_medida'].'</td>
                <td style="width:9%;" align=right>'.round($row['ins_cant_requerida'],2).'</td>
                <td style="width:9%;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="width:9%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                <td style="width:9%;" align=right>'.number_format($row['monto_solicitado'], 2, ',', '.').'</td>
                <td style="width:15%;" align=center>'.$this->temporalidad_solicitado($row['req_id']).'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
            <tr>
              <td style="height: 3%;"></td>
              <td colspan=6 align=right><b>MONTO A CERTIFICAR : </b></td>
              <td style="font-size: 9px;" align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
              <td></td>
            </tr>
      </table>';

    return $tabla;
  }

  /*-- TEMPORALIDAD REQUERIMIENTO SOLICITADO --*/
  public function temporalidad_solicitado($req_id){
    $tabla='';
    $temporalidad=$this->model_certificacion->get_lista_temporalidad_solicitados($req_id);
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align=center>';
        foreach($temporalidad as $row){
          $tabla.='
          <tr>
            <td style="width:50%;height: 0.60%;" align=left>'.$row['m_descripcion'].' : </td>
            <td style="width:50%;" align=left><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
          </tr>';
        }
      $tabla.='
      </table>';

    return $tabla;
  }


  /*-- IV CONFORMIDAD DE LA UNIDAD --*/
  public function conformidad_solicitud($solicitud){
    $tabla='';
    $tabla.='
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
      <tr style="border: solid 0px;">              
        <td style="width:1%;"></td>
        <td style="width:98%;">
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr>
              <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>IV. CONFORMIDAD DE LA SOLICITUD</b></td>
            </tr>
          </table>
          <br>
          <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="font-size: 10px; font-family: Arial;" align="center" >
              <th style="width:100%;height: 5%;">JEFATURA '.$solicitud[0]['tipo_subactividad'].' '.$solicitud[0]['serv_descripcion'].' - '.$solicitud[0]['abrev'].'</th>
            </tr>
          </table>
        </td>
        <td style="width:1%;"></td>
      </tr>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
      <tr style="border: solid 0px;">              
        <td style="width:1%;"></td>
        <td style="width:98%;">
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr>
              <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>V. ESTADO DE LA SOLICITUD</b></td>
            </tr>
          </table>
          <br>
          <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="font-size: 10px; font-family: Arial;" align="center">';
                if($solicitud[0]['estado']==0){
                  $tabla.='<td style="width:100%;height: 2%;color: red;"><b>LA SOLICITUD SE ENCUENTRA EN PROCESO DE APROBACIÓN</b></td>';
                }
                else{
                  $tabla.='<td style="width:100%;height: 2%;color: green;"><b>SOLICITUD APROBADO</b></td>';
                }
              $tabla.='
            </tr>
          </table>
        </td>
        <td style="width:1%;"></td>
      </tr>
    </table>';

    return $tabla;
  }






  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_segpoa($com_id){
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
              <a href="#" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
              </li>
              <li class="text-center">
                  <a href="#" title="REGISTRO DE SEGUIMIENTO, EVALUACIÓN Y CERTIFICACIÓN POA"> <span class="menu-item-parent">SEG. EVAL. POA</span></a>
              </li>
              <li>
                <a href="'.site_url("").'/seguimiento_poa"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Seg. y eval. POA</span></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Certificación POA</span></a>
                <ul>
                  <li>
                    <a href="'.site_url("").'/solicitar_certpoa/'.$com_id.'">Solicitar Certificación POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                  <li>
                    <a href="image-editor.html">Mis Certificaciones POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                </ul>
              </li>
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }
}
?>