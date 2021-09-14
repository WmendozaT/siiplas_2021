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


    /*------ VERIFICANDO CODIGO DE MODIFICACION POA (2020)-----*/
    public function datos_cite($cite){
      $tabla='';

      if($cite[0]['cite_estado']!=0){
        $tit='<font color=blue><b>'.$cite[0]['cite_codigo'].'</b></font>';
      }
      else{
        $tit=' <font color=#e60d25><b>DEBE CERRAR LA MODIFICACI&Oacute;N DEL REQUERIMIENTO !!</b></font>';
      }

      $tabla.='<h1 title="'.$cite[0]['com_id'].'"><b> CITE Nro. : <small>'.$cite[0]['cite_nota'].'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;FECHA : <small>'.date('d/m/Y',strtotime($cite[0]['cite_fecha'])).'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;C&Oacute;DIGO : '.$tit.'</b></h1>';
      return $tabla;
    }

    /*------ TITULO CABECERA (2020)-----*/
    public function titulo_cabecera($cite){
      $tabla='';
      if($cite[0]['tp_id']==1){ /// Proyecto de Inversion
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Proyecto de Inversion
        $tabla.=' <h1> <b>PROYECTO : </b><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</small>
                  <h1> <b>UNIDAD RESP. : </b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cite[0]['proy_id']);
        $tabla.=' <h1><b> '.$proyecto[0]['tipo_adm'].' : <b><small>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</small></h1>
                  <h1><b> UNIDAD RESP. : <b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }

      //// ------ Monto Presupuesto Programado-Asignado POA
        $monto=$this->ppto($proyecto);
        $tabla.='<h1><b> PPTO. ASIGNADO : <small>'.number_format($monto[1], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;PPTO PROGRAMADO : <small>'.number_format($monto[2], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;SALDO : <small>'.number_format($monto[3], 2, ',', '.').'</small></b></h1>';
        
      return $tabla;
    }

    /*----- MONTO PRESUPUESTO (2020) ------*/
    public function ppto($proyecto){
      $monto_a=0;$monto_p=0;$monto_saldo=0;
      $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
      if($proyecto[0]['tp_id']==1){
        $monto_prog=$this->model_ptto_sigep->suma_ptto_pinversion($proyecto[0]['proy_id']);
      }
      else{
        $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
      }

      if(count($monto_asig)!=0){
        $monto_a=($monto_asig[0]['monto']+$monto_asig[0]['saldo']);
      }
      if(count($monto_prog)!=0){
        $monto_p=$monto_prog[0]['monto'];
      }

      $monto[1]=$monto_a; /// Monto Asignado
      $monto[2]=$monto_p; /// Monto Programado
      $monto[3]=($monto_a-$monto_p); /// Saldo

      return $monto;
    }











    //// REPORTE CABECERA MODIFICACION POA
    public function cabecera_modpoa(){
      $tabla='';

      $tabla.='
      <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:91.8%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=14%; text-align:center;"">
                     
                          </td>
                          <td width=76%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:93%;" align="center">
                                <tr>
                                    <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b></b></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>DIR. ADM.</b></td>
                                    <td style="width:82.5%;">: </td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>UNI. EJEC.</b></td>
                                    <td style="width:82.5%;">:</td>
                                </tr>
                                <?php echo $titulo;?>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>CITE FORM. MOD. N°8</b></td>
                                    <td style="width:82.5%;">: </td>
                                </tr>
                            </table>
                          </td>
                          <td style="width:19%; font-size: 8.5px;" align="left">
                            <b style="font-size: 11px;">CÓDIGO N°: </b><br>
                            <b>FECHA DE IMP. : </b><br>
                            <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>';
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