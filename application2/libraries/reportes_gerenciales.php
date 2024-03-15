<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Reportes_gerenciales extends CI_Controller{
        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
           
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/model_producto');
          //  $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mestrategico/model_objetivoregion');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('menu_modelo');
         
            $this->gestion = $this->session->userData('gestion');
          
    }

  //// ======== CABECERA Y PIE PARA LOS REPORTES POA 2022
  //// Cabecera Reporte form 3, 4 y 5
  public function cabecera($tp_id,$tp_rep,$proyecto,$com_id){
    /// tp_rep : 3 (Foda), 4 (Actividades), 5 (requerimientos)
    
    if($tp_rep==3){
      $titulo_rep='ANALISIS DE PROBLEMAS Y CAUSAS';
      $titulo_form='FORMULARIO SPO N° 3';
      $comp='';
    }
    else{
      $componente=$this->model_componente->get_componente($com_id,$this->gestion);
      if($tp_rep==4){
        $titulo_rep='ACTIVIDADES';
        $titulo_form='FORMULARIO SPO N° 4';
      }
      elseif($tp_rep==5){
        $titulo_rep='REQUERIMIENTOS';
        $titulo_form='FORMULARIO SPO N° 5';
      }
      $comp='
        <tr>
          <td style="width:20%;">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                  <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD REPONSABLE</b></td><td style="width:5%;"></td></tr>
              </table>
          </td>
          <td style="width:80%;">
              <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                  <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$componente[0]['serv_cod'].' '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</td></tr>
              </table>
          </td>
        </tr>';
    }

    
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 13px;font-family: Arial;">
                        <td style="width:40%;height: 20%;">&nbsp;&nbsp;<b> '.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                          <td style="height: 30%;"><b>PLAN OPERATIVO ANUAL GESTION - '.$this->gestion.'</b></td>
                      </tr>
                      <tr style="font-size: 20px;font-family: Arial;">
                        <td style="height: 5%;">'.$titulo_rep.'</td>
                      </tr>
                  </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
              <td style="width:70%;">
              </td>
              <td style="width:30%; height: 3%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                      <td align=center style="width:100%;height: 40%;"><b>'.$titulo_form.'</b></td>
                    </tr>
                </table>
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
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dep_cod'].' '.strtoupper ($proyecto[0]['dep_departamento']).'</td></tr>
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
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dist_cod'].' '.strtoupper ($proyecto[0]['dist_distrital']).'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>';
                  if($tp_id==4){
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>'.$proyecto[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper ($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</td></tr>
                        </table>
                    </td>';
                  }
                  else{
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>PROYECTO</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['proy_sisin'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper ($proyecto[0]['proy_nombre']).'</td></tr>
                        </table>
                    </td>';
                  }
                $tabla.='
                </tr>
                '.$comp.'
            </table>
          </td>
          <td style="width:2%;"></td>
        </tr>
        <tr>
          <td style="width:2%;"></td>
          <td style="width:96%;height: 1%;">
            <hr>
            <br><b style="font-size: 8px;font-family: Arial;">DETALLE : </b>
          </td>
          <td style="width:2%;"></td>
        </tr>
      </table>';
    return $tabla;
  }


  /*------ PIE FODA - REPORTE -----*/
  public function pie_foda(){
    $tabla='';
    $tabla.='    
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
          <td style="width: 50%;">
              <table border="0.3" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                  </tr>
                 
                  <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                      <td><b><br><br><br><br>FIRMA</b></td>
                  </tr>
              </table>
          </td>
          <td style="width: 50%;">
              <table border="0.3" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:100%;height:13px;"><b>APROBADO POR<br></b></td>
                  </tr>
                 
                  <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                      <td><b><br><br><br><br>FIRMA</b></td>
                  </tr>
              </table>
          </td>
        </tr>
        <tr>
          <td colspan="2"><br></td>
        </tr>
        <tr style="font-size: 7px;font-family: Arial;">
          <td style="text-align: left" >
            '.$this->session->userdata('sistema').'
          </td>
          <td style="width: 20%; text-align: right">
                pag. [[page_cu]]/[[page_nb]]
          </td>
        </tr>
        <tr>
            <td colspan="2"><br><br></td>
        </tr>
      </table>';

    return $tabla;
  }


  /*------ PIE FORM - REPORTE -----*/
  public function pie_form($proyecto){
    $tabla='';
    $tabla.='
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:98%;" align="center">
          <tr>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                        <td style="width:100%;height:12px;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                    </tr>
                    <tr>
                        <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td style="width:100%;height:12px;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                    </tr>
                    <tr>
                      <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td style="width:100%;height:12px;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
                    </tr>
                    <tr>
                      <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td style="width: 33%; text-align: left; height:18px;">';
              if($proyecto[0]['proy_estado']==1){
                $tabla.='POA - '.$this->session->userdata('gestion');
              }
              else{
                $tabla.='POA - '.$this->session->userdata('gestion').' '.$this->session->userdata('rd_poa');
              } 
            $tabla.='
            </td>
            <td style="width: 33%; text-align: center">
              '.$this->session->userdata('sistema').'
            </td>
            <td style="width: 33%; text-align: right">
                pag. [[page_cu]]/[[page_nb]]
            </td>
          </tr>
      </table>';

    return $tabla;
  }



/*----- REPORTE FORMULARIO 4 (2021 - Operaciones, Proyectos de Inversion) ----*/
    public function operaciones_form4($componente,$proyecto){
      $tabla='';
      
      if($proyecto[0]['tp_id']==1){ /// Proyectos de Inversion
        $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OR.</th>
                    <th style="width:2%; color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:9%; color:#FFF;">COMPONENTE</th>
                    <th style="width:11%; color:#FFF;">OPERACI&Oacute;N</th>
                    <th style="width:11%; color:#FFF;">RESULTADO</th>
                    <th style="width:11%; color:#FFF;">INDICADOR</th>
                    <th style="width:2%; color:#FFF;">LB.</th>
                    <th style="width:2.5%; color:#FFF;">META</th>
                    <th style="width:2.5%; color:#FFF;">ENE.</th>
                    <th style="width:2.5%; color:#FFF;">FEB.</th>
                    <th style="width:2.5%; color:#FFF;">MAR.</th>
                    <th style="width:2.5%; color:#FFF;">ABR.</th>
                    <th style="width:2.5%; color:#FFF;">MAY.</th>
                    <th style="width:2.5%; color:#FFF;">JUN.</th>
                    <th style="width:2.5%; color:#FFF;">JUL.</th>
                    <th style="width:2.5%; color:#FFF;">AGO.</th>
                    <th style="width:2.5%; color:#FFF;">SEPT.</th>
                    <th style="width:2.5%; color:#FFF;">OCT.</th>
                    <th style="width:2.5%; color:#FFF;">NOV.</th>
                    <th style="width:2.5%; color:#FFF;">DIC.</th>
                    <th style="width:8.5%; color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%; color:#FFF;">PPTO.</th>   
                  </tr>
                </thead>
                <tbody>';
                $operaciones=$this->model_producto->list_operaciones_pi($componente[0]['com_id']);  /// 2020
                $nro=0;
                foreach($operaciones as $rowp){
                  $nro++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $tp='';
                  if($rowp['indi_id']==2){
                    $tp='%';
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }


                  $tabla.='
                  <tr>
                    <td style="height:12px;">'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 9%; text-align: left;">'.$componente[0]['com_componente'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width:11%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width:2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width:2.5%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['enero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['febrero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['marzo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['abril'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['mayo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['junio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['julio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['agosto'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['septiembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['octubre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['noviembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['diciembre'],2).''.$tp.'</td>
                    <td style="width:8.5%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';            
                }
          $tabla.='
                </tbody>
              </table>';

      }
      else{ //// Gasto Corriente

         $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OR.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th> 
                    <th style="width:10%;color:#FFF;">OPERACI&Oacute;N</th>
                    <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                    <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                    <th style="width:9%;color:#FFF;">INDICADOR</th>
                    <th style="width:2%;color:#FFF;">LB.</th>
                    <th style="width:3%;color:#FFF;">META</th>
                    <th style="width:3%;color:#FFF;">ENE.</th>
                    <th style="width:3%;color:#FFF;">FEB.</th>
                    <th style="width:3%;color:#FFF;">MAR.</th>
                    <th style="width:3%;color:#FFF;">ABR.</th>
                    <th style="width:3%;color:#FFF;">MAY.</th>
                    <th style="width:3%;color:#FFF;">JUN.</th>
                    <th style="width:3%;color:#FFF;">JUL.</th>
                    <th style="width:3%;color:#FFF;">AGO.</th>
                    <th style="width:3%;color:#FFF;">SEPT.</th>
                    <th style="width:3%;color:#FFF;">OCT.</th>
                    <th style="width:3%;color:#FFF;">NOV.</th>
                    <th style="width:3%;color:#FFF;">DIC.</th>
                    <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%;color:#FFF;">PPTO.</th>   
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                $operaciones=$this->model_producto->lista_operaciones($componente[0]['com_id']);
                
                foreach($operaciones as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;"><b>'.round($rowp['prod_meta'],2).'</b></td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
                $tabla.='
                </tbody>
              </table>';
      }
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
}
?>