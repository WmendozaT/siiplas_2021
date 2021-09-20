<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class Modificacionpoa extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('modificacion/model_modrequerimiento');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('programacion/model_producto');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('modificacion/model_modfisica'); /// Gestion 2020

        $this->load->model('modificacion/model_modificacion');

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





    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_unidades_es($proy_estado){
        $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
        $tabla='';
        
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr >
              <th style="width:1%;" bgcolor="#fafafa">#</th>
              <th style="width:5%;" bgcolor="#fafafa" title="SELECCIONAR"></th>
              <th style="width:5%;" bgcolor="#fafafa" title="LISTA DE CITES GENERADOS"></th>
              <th style="width:10%;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#fafafa" title="DESCRIPCI&Oacute;N">GASTO CORRIENTE</th>
              <th style="width:10%;" bgcolor="#fafafa" title="NIVEL">ESCALON</th>
              <th style="width:10%;" bgcolor="#fafafa" title="NIVEL">NIVEL</th>
              <th style="width:10%;" bgcolor="#fafafa" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#fafafa" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#fafafa" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:5%;" bgcolor="#fafafa" title="ESTADO"></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($unidades as $row){
              $color='#ccefcc';
              $estado='APROBADO';

              if($row['proy_estado']==4){
                $nro++;
                $tabla.='
                  <tr style="height:40px;">
                    <td align=center><b>'.$nro.'</b></td>
                    <td align=center>
                      <a data-toggle="modal" data-target="#'.$row['proy_id'].'" title="SELECCIONAR OPCI&Oacute;N" class="btn btn-info"><font size=1>SELECCIONAR OPCI&Oacute;N</font></a>
                        <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$row['proy_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                          <div class="modal-dialog modal-lg" id="mdialTamanio">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                  &times;
                                </button>
                              </div>
                              <div class="modal-body no-padding">
                                  <div class="row">
                                    <h2 class="alert alert-success"><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].' - '.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</center></h2>
                                    
                                    <a onclick="mod_operacion'.$row['proy_id'].'()" class="ruta" title="MODIFICAR FORMULARIO N° 4">
                                      <div class="well well-sm col-sm-4">
                                          <div class="well well-sm bg-color-teal txt-color-white text-center">
                                            <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR FORMULARIO N° 4 - '.$this->gestion.'</h5>
                                            <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                          </div>
                                      </div>
                                    </a>
                                    
                                    <a onclick="mod_insumo'.$row['proy_id'].'()"  class="ruta" title="MODIFICAR FORMULARIO N° 5">
                                      <div class="well well-sm col-sm-4">
                                          <div class="well well-sm bg-color-teal txt-color-white text-center">
                                            <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR FORMULARIO N° 5 - '.$this->gestion.'</h5>
                                            <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                          </div>
                                      </div>
                                    </a>';

                                    $link=''; $color='red'; $titulo='OPCI&Oacute;N BLOQUEADA';
                                      if($this->tp_adm==1){
                                      $link='onclick="mod_techo'.$row['proy_id'].'()"';
                                      $color='teal';
                                      $titulo='MODIFICAR TECHO PRESUPUESTARIO';
                                    }

                                    $tabla.='
                                    <a '.$link.' class="ruta" title="'.$titulo.'" >
                                      <div class="well well-sm col-sm-4">
                                          <div class="well well-sm bg-color-'.$color.' txt-color-white text-center">
                                            <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR TECHO PRESUPUESTARIO</h5>
                                            <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                          </div>
                                      </div>
                                    </a>
                                    
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div class="row"><br>
                            <img id="load1'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                            <img id="load2'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                            <img id="load3'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                          </div>
                        </div>';
                        $tabla.='
                            <script>
                              function mod_operacion'.$row['proy_id'].'(){
                                document.getElementById("load1'.$row['proy_id'].'").style.display = "block";
                                window.location="'.site_url("").'/mod/list_componentes/'.$row['proy_id'].'"
                              }
                              
                              function mod_insumo'.$row['proy_id'].'(){
                                document.getElementById("load2'.$row['proy_id'].'").style.display = "block";
                                window.location="'.site_url("").'/mod/procesos/'.$row['proy_id'].'"
                              }

                              function mod_techo'.$row['proy_id'].'(){
                                document.getElementById("load3'.$row['proy_id'].'").style.display = "block";
                                window.location="'.site_url("").'/mod/cite_techo/'.$row['proy_id'].'"
                              }
                            </script>';
                $tabla .= '
                    </td>
                    <td align=center><a href="'.site_url("").'/mod/list_cites/'.$row['proy_id'].'" title="LISTA DE CITES GENERADOS POR MODIFICACI&Oacute;N" target="_blank" class="btn btn-default"><font size=1>LISTA DE CITES</font></a></td>
                    <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                    <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                    <td>'.$row['escalon'].'</td>
                    <td>'.$row['nivel'].'</td>
                    <td>'.$row['tipo_adm'].'</td>
                    <td>'.strtoupper($row['dep_departamento']).'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>
                    <td><center><b>'.$estado.'</b></center></td>
                  </tr>';
              }
            }
          $tabla.='
          </tbody>
        </table>';
      return $tabla;
    }

    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($proy_estado){
      $proyectos=$this->model_proyecto->list_pinversion(1,$proy_estado);
      $tabla='';
      $tabla.='
        <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%; height:40px;" bgcolor="#fafafa">#</th>
              <th style="width:5%;" bgcolor="#fafafa" title="SELECCIONAR"></th>
              <th style="width:5%;" bgcolor="#fafafa" title="LISTA DE CITES GENERADOS"></th>
              <th style="width:10%;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#fafafa" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#fafafa" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#fafafa" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#fafafa" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#fafafa" title="FASE - ETAPA">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='<tr>';
               $tabla .= '<td style="height:40px;" title='.$row['proy_id'].'><center>'.$nro.'</center></td>
                          <td align=center>';
                          if($row['pfec_estado']==1){
                              $tabla.='
                          <a data-toggle="modal" data-target="#'.$row['proy_id'].'" title="SELECCIONAR OPCI&Oacute;N" class="btn btn-info"><font size=1>SELECCIONAR</font></a>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$row['proy_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" id="mdialTamanio">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                  </div>
                                  <div class="modal-body no-padding">
                                      <div class="row">
                                        <h2 class="alert alert-success"><center>'.$row['aper_programa'].' '.$row['proy_sisin'].' 000 - '.$row['proy_nombre'].'</center></h2>
                                        
                                        <a onclick="mod_operacion'.$row['proy_id'].'()" class="ruta" title="MODIFICAR ACTIVIDADES">
                                          <div class="well well-sm col-sm-4">
                                              <div class="well well-sm bg-color-teal txt-color-white text-center">
                                                <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR ACTIVIDADES '.$this->gestion.'</h5>
                                                <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                              </div>
                                          </div>
                                        </a>
                                        
                                        <a onclick="mod_insumo'.$row['proy_id'].'()"  class="ruta" title="MODIFICAR REQUERIMIENTOS">
                                          <div class="well well-sm col-sm-4">
                                              <div class="well well-sm bg-color-teal txt-color-white text-center">
                                                <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR REQUERIMIENTOS '.$this->gestion.'</h5>
                                                <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                              </div>
                                          </div>
                                        </a>';

                                        $link=''; $color='red'; $titulo='OPCI&Oacute;N BLOQUEADA';
                                          if($this->tp_adm==1){
                                          $link='onclick="mod_techo'.$row['proy_id'].'()"';
                                          $color='teal';
                                          $titulo='MODIFICAR TECHO PRESUPUESTARIO';
                                        }

                                        $tabla.='
                                        <a '.$link.' class="ruta" title="'.$titulo.'" >
                                          <div class="well well-sm col-sm-4">
                                              <div class="well well-sm bg-color-'.$color.' txt-color-white text-center">
                                                <h5 style="font-weight: bold;font-style: italic;color: white">MODIFICAR TECHO PRESUPUESTARIO</h5>
                                                <i class="glyphicon glyphicon-list-alt" aria-hidden="true" id="graf"></i>
                                              </div>
                                          </div>
                                        </a>
                                        
                                      </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row"><br>
                                <img id="load1'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                                <img id="load2'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                                <img id="load3'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="65" height="65" title="CARGANDO INFORMACI&Oacute;N..">
                              </div>
                            </div>';
                            $tabla.='
                                <script>
                                  function mod_operacion'.$row['proy_id'].'(){
                                    document.getElementById("load1'.$row['proy_id'].'").style.display = "block";
                                    window.location="'.site_url("").'/mod/list_componentes/'.$row['proy_id'].'"
                                  }
                                  
                                  function mod_insumo'.$row['proy_id'].'(){
                                    document.getElementById("load2'.$row['proy_id'].'").style.display = "block";
                                    window.location="'.site_url("").'/mod/procesos/'.$row['proy_id'].'"
                                  }

                                  function mod_techo'.$row['proy_id'].'(){
                                    document.getElementById("load3'.$row['proy_id'].'").style.display = "block";
                                    window.location="'.site_url("").'/mod/cite_techo/'.$row['proy_id'].'"
                                  }
                                </script>';
                          }
                          else{
                            $tabla.='FASE NO ACTIVA';
                          }
                $tabla .= '
                    </td>
                    <td align=center><a href="'.site_url("").'/mod/list_cites/'.$row['proy_id'].'" title="LISTA DE CITES GENERADOS POR MODIFICACI&Oacute;N" target="_blank" class="btn btn-default"><font size=1>LISTA DE CITES</font></a></td>
                    <td><center>'.$row['aper_programa'].' '.$row['proy_sisin'].' '.$row['aper_actividad'].'</center></td>';
                $tabla.='<td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla.='<td>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      
      return $tabla;
    }

//////////////// FORMULARIO N° 4 
        /*--- VERIFICA SI SE TIENE ALGUN REGISTRO (ABM) ---*/
    public function verif_cite($cite_id){
      $cite=$this->model_modfisica->get_cite_fis($cite_id); // CITE
      $proyecto=$this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// PROYECTO

      $ca=$this->model_modfisica->operaciones_adicionados($cite_id);
      $cm=$this->model_modfisica->operaciones_modificados($cite_id);
      $cd=$this->model_modfisica->operaciones_eliminados($cite_id);

      $sw=0;
      if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
        $sw=1;
      }

      return $sw;
    }

    /*------ VERIFICANDO CODIGO DE MODIFICACION POA (2020)-----*/
    public function datos_cite($cite){
      $tabla='';

      if($cite[0]['cite_estado']!=0){
        $tit='<font color=blue><b>'.$cite[0]['cite_codigo'].'</b></font>';
      }
      else{
        $tit=' <font color=#a87830><b>DEBE CERRAR LA MODIFICACI&Oacute;N DEL REQUERIMIENTO !!</b></font>';
      }

      $tabla.='<h1><b> CITE Nro. : <small>'.$cite[0]['cite_nota'].'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;FECHA : <small>'.date('d/m/Y',strtotime($cite[0]['cite_fecha'])).'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;C&Oacute;DIGO : '.$tit.'</b></h1>';
      return $tabla;
    }


    /*------ TITULO CABECERA (2020) (FORMULARIO N° 4)-----*/
    public function titulo_cabecera($cite){
      $tabla='';
      if($cite[0]['tp_id']==1){ /// Proyecto de Inversion
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Proyecto de Inversion
        $tabla.=' <h1> <b>PROYECTO : </b><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</small>
                  <h1> <b>UNIDAD RESPONSABLE : </b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cite[0]['proy_id']);
        $tabla.=' <h1><b> '.$proyecto[0]['tipo_adm'].' : <b><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</small></h1>
                  <h1><b> UNIDAD RESPONSABLE : <b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }

      //// ------ Monto Presupuesto Programado-Asignado POA
        $monto=$this->ppto($proyecto);
        $tabla.='<h1><b> PPTO. ASIGNADO : <small>'.number_format($monto[1], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;PPTO PROGRAMADO : <small>'.number_format($monto[2], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;SALDO : <small>'.number_format($monto[3], 2, ',', '.').'</small></b></h1>';
        
      return $tabla;
    }

    /*--- MONTO PRESUPUESTO (2020) ---*/
    public function ppto($proyecto){
      $monto_a=0;$monto_p=0;$monto_saldo=0;
      $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      
      if($proyecto[0]['tp_id']==1){
        $monto_prog=$this->model_ptto_sigep->suma_ptto_pinversion($proyecto[0]['proy_id']);
      }
      else{
        $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
      }

      if(count($monto_asig)!=0){
        $monto_a=$monto_asig[0]['monto'];
      }
      if(count($monto_prog)!=0){
        $monto_p=$monto_prog[0]['monto'];
      }

      $monto[1]=$monto_a; /// Monto Asignado
      $monto[2]=$monto_p; /// Monto Programado
      $monto[3]=($monto_a-$monto_p); /// Saldo

      return $monto;
    }


     /*------ LISTA FORMULARIO N° 4 (2020) ------*/
    public function mis_formulario4($cite){
      $proy_id=$cite[0]['proy_id'];
      $productos = $this->model_producto->lista_operaciones($cite[0]['com_id'],$this->gestion); // Lista de Operaciones
      $tabla ='';
      $tabla .='
        <input type="hidden" name="base" value="'.base_url().'">
        <table id="dt_basic" class="table table-bordered">
          <thead>
            <tr class="modo1">
              <th style="width:1%; text-align=center"><b>#</b></th>
              <th style="width:1%; text-align=center"><b>E/B</b></th>
              <th style="width:2%;"><b>COD. ACP.</b></th>
              <th style="width:2%;"><b>COD. OPE.</b></th>
              <th style="width:2%;"><b>COD. ACT.</b></th>
              <th style="width:10%;"><b>DESCRIPCI&Oacute;N ACTIVIDAD</b></th>
              <th style="width:10%;"><b>RESULTADO</b></th>
              <th style="width:5%;"><b>TIP. IND.</b></th>
              <th style="width:10%;"><b>INDICADOR</b></th>
              <th style="width:5%;"><b>LINEA BASE '.($this->gestion-1).'</b></th>
              <th style="width:5%;"><b>META</b></th>
              <th style="width:2.5%;"><b>ENE.</b></th>
              <th style="width:2.5%;"><b>FEB.</b></th>
              <th style="width:2.5%;"><b>MAR.</b></th>
              <th style="width:2.5%;"><b>ABR.</b></th>
              <th style="width:2.5%;"><b>MAY.</b></th>
              <th style="width:2.5%;"><b>JUN.</b></th>
              <th style="width:2.5%;"><b>JUL.</b></th>
              <th style="width:2.5%;"><b>AGO.</b></th>
              <th style="width:2.5%;"><b>SEP.</b></th>
              <th style="width:2.5%;"><b>OCT.</b></th>
              <th style="width:2.5%;"><b>NOV.</b></th>
              <th style="width:2.5%;"><b>DIC.</b></th>
              <th style="width:8%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
              <th style="width:6%;"><b>PTTO..</b></th>
              <th style="width:5%;"><b>NRO. REQ.</b></th>
            </tr>
          </thead>
          <tbody>';
          $cont = 0;
          foreach($productos as $rowp){
            $cont++;
            $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
            $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
            $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
            $ptto=0;
            if(count($monto)!=0){
              $ptto=$monto[0]['total'];
            }

            $color=''; $titulo=''; $por='';
            if($cite[0]['tp_id']==1){
              if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta'] || $rowp['or_id']==0){
                $color='#fbd5d5';
                $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
              }
            }
            else{
              if($rowp['indi_id']==2){ // Relativo
                $por='%';
                if($rowp['mt_id']==3){
                  if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                    $color='#fbd5d5';
                    $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                  }
                }
              }
              else{ // Absoluto
                if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                  $color='#fbd5d5';
                  $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                }
              }
            }

            $tabla .='
              <tr bgcolor="'.$color.'" class="modo1" title='.$titulo.'>
                <td align="center" title='.$rowp['prod_id'].'><font color="blue" size="2"><b>'.$rowp['prod_cod'].'</b></font></td>
                <td align="center">
                  <a href="'.site_url("").'/mod/update_ope/'.$rowp['prod_id'].'/'.$cite[0]['cite_id'].'" title="MODIFICAR ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a><br>
                  <a href="#" data-toggle="modal" data-target="#modal_mod_form4" class="btn btn-default mod_form4" name="'.$rowp['prod_id'].'" title="MODIFICAR ACTIVIDAD"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                  
                  /*if(count($monto)==0){
                    $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" id="'.$cite[0]['cite_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                  }*/
                  $tabla.='
                </td>
                <td style="width:2%;text-align=center" bgcolor="#c1e1fb"><b><font size=5 color=blue>'.$rowp['og_codigo'].'</font></b></td>
                <td style="width:2%;text-align=center" bgcolor="#c1e1fb"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>
                <td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>
                <td style="width:10%;">'.$rowp['prod_producto'].'</td>
                <td style="width:10%;">'.$rowp['prod_resultado'].'</td>
                <td style="width:5%;">'.$rowp['indi_abreviacion'].'</td>
                <td style="width:10%;">'.$rowp['prod_indicador'].'</td>
                <td style="width:5%;" align=right>'.round($rowp['prod_linea_base'],2).'</td>
                <td style="width:5%;" align=right><b>'.round($rowp['prod_meta'],2).''.$por.'</b></td>';
              if(count($programado)!=0){
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['enero'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['febrero'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['marzo'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['abril'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['mayo'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['junio'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['julio'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['agosto'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['septiembre'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['octubre'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['noviembre'],2).' '.$por.'</td>';
                $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['diciembre'],2).' '.$por.'</td>';
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:2.5%;" bgcolor="#f1bac6" align=right>0</td>';
                }
              }
              $tabla.='<td style="width:8%;" bgcolor="#e5fde5" >'.$rowp['prod_fuente_verificacion'].'</td>';
              $tabla.='<td style="width:6%;"  align=right><b>'.number_format($ptto, 2, ',', '.').'</b></td>';
              $tabla.='<td style="width:5%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
            $tabla .='</tr>';
          }
          $tabla.='</tbody>
          </table>';

      return $tabla;
    }











//////////////// FORMULARIO N5

 /*------ Lista de Servicios para modificacion de Requerimientos --------*/
    public function lista_servicio_componentes($proyecto){
      $tabla='';
      $tabla.=$this->servicios($proyecto);

      return $tabla;
    }

    /*------ Lista de Servicios (Gasto Corriente) ------*/
    public function servicios($proyecto){
      $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']);
      $tabla='';
        if(count($fase)!=0){
            $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);
            $tabla.='<table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                    <tr style="height:45px;">
                        <th style="width:1%;">#</th>
                        <th style="width:5%;">COD. SUBACTIVIDAD</th>
                        <th style="width:20%;">SUBACTIVIDAD</th>
                        <th style="width:15%;">RESPONSABLE</th>
                        <th style="width:5%;">PONDERACI&Oacute;N</th>
                        <th style="width:5%;">OPERACIONES PROGRAMADOS</th>
                        <th style="width:5%;">PRESUPUESTO POA</th>
                        <th style="width:5%;"></th>
                    </tr>
                </thead>
                <tbody>';
                $num=0; $ponderacion=0; $sum=0;
                foreach($componente as $row){
                  $monto=$this->model_modrequerimiento->prespuesto_servicio_componente($row['com_id'],$proyecto[0]['tp_id']);
                  $ppto=number_format(0, 2, '.', ',');
                  
                  if(count($monto)!=0){
                    $ppto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $num++;
                  $tabla.='
                  <tr>
                      <td align=center>'.$num.'</td><td bgcolor="#d4f1fb" align="center" title="C&Oacute;DIGO SERVICIO : '.$row["serv_descripcion"].'"><font color="blue" size=3><b>'.$row['serv_cod'].'</b></font></td>
                      <td title='.$row['com_id'].'>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>
                      <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                      <td align=center>'.$row['com_ponderacion'].' %</td>
                      <td align=center bgcolor="#bee6e1"><font size=2 color=blue>'.count($this->model_producto->list_prod($row['com_id'])).'</font></td>
                      <td align=right>'.$ppto.'</td>
                      <td>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff" style="width:100%; color: green; background-color: #eeeeee;border-bottom-width: 5px;" title="MODIFICAR REQUERIMIENTOS" name="'.$row['com_id'].'"><i class="glyphicon glyphicon-file"></i> INGRESAR CITE</a>
                      </td>
                  </tr>';
                  $sum=$sum+count($this->model_producto->list_prod($row['com_id']));
                  $ponderacion=$ponderacion+$row['com_ponderacion'];
                }
                $tabla.='    
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align=center>'.$ponderacion.'%</td>
                    <td align=center>'.$sum.'</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>';
        }
        else{
          $tabla.='<hr>
                  <div class="alert alert-danger" role="alert">
                    EL PROYECTO NO TIENE FASE ACTIVA PARA ESTA GESTIÓN '.$this->gestion.'  
                  </div>';
        }

      return $tabla;
    }












  //// REPORTE MODIFICACION POA
  //// Cabecera Modifcacion poa
    public function cabecera_modpoa($cite,$tp){
      $titulo_mod='ACTIVIDADES';
      if($tp==2){
        $titulo_mod='REQUERIMIENTOS';
      }

      $tabla='';
      $codigo='Sin Codigo ... debe cerrar la modificación poa ';
      if($cite[0]['cite_codigo']!=''){
        $codigo=$cite[0]['cite_codigo'];
      }

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
                '.strtoupper($cite[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <td style="height: 30%;"><b>MODIFICACIÓN POA '.$this->gestion.' - '.$titulo_mod.'</b></td>
                        </tr>
                        <tr style="font-size: 20px;font-family: Arial;">
                          <td style="height: 5%;">'.$codigo.'</td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:50%;">
                </td>
                <td style="width:50%; height: 3%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 15px;font-family: Arial;">
                          <td colspan=2 align=center style="width:100%;height: 40%;"><b>FORMULARIO MOD. N° 8 </b></td>
                      </tr>
                      <tr style="font-size: 10px;font-family: Arial;">
                          <td style="width:47%;height: 30%;"><b>CITE : '.$cite[0]['cite_nota'].'</b></td>
                          <td style="width:47%;height: 30%"><b>FECHA : '.date('d-m-Y',strtotime($cite[0]['cite_fecha'])).'</b></td>
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
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($cite[0]['dep_departamento']).'</td></tr>
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
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($cite[0]['dist_distrital']).'</td></tr>
                                </table>
                            </td>
                        </tr>';

                          if($cite[0]['tp_id']==1){
                            $tabla.='
                            <tr>
                              <td style="width:20%;">
                                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>PROY. INVERSI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['proy_sisin'].' '.strtoupper ($cite[0]['proy_nombre']).'</td></tr>
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
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</td>
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
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['aper_actividad'].' '.strtoupper ($cite[0]['act_descripcion']).' '.$cite[0]['abrev'].'</td></tr>
                                  </table>
                              </td>
                            </tr>
                            <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>SUBACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</td>
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

    /*------- GENERAR MENU --------*/
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