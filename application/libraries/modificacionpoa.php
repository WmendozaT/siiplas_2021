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
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020

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
        $this->dep_id = $this->session->userData('dep_id');
        //$this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
       // $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->conf_mod_ope = $this->session->userData('conf_mod_ope');
        $this->conf_mod_req = $this->session->userData('conf_mod_req');
        $this->mes = $this->mes_nombre();
    }


    /*---- Lista de Unidades / Establecimientos de Salud (2023) -----*/
    public function list_unidades_es($proy_estado){
        $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
        
        $titulo_ppto='TECHO PPTO.'; /// Administrador
        if($this->tp_adm!=1){
          $titulo_ppto='REV. PPTO.'; /// Responsables POA
        }

        $tabla='';
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr style="font-size:10.5px;">
              <th style="width:1%;" bgcolor="#fafafa">#</th>
              <th style="width:3%; text-align:center;" bgcolor="#fafafa" title="MODIFICACION FORMULARIO N° 4">MOD. FORM. N° 4</th>
              <th style="width:3%; text-align:center;" bgcolor="#fafafa" title="MODIFICACION FORMULARIO N° 5">MOD. FORM. N° 5</th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="HISTORIAL DE CITES"></th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="REVERSION DE SALDOS CERTIFICADOS">REVERSION DE SALDOS</th>';
              if($this->tp_adm==1){
                $tabla.='<th style="width:5%; text-align:center;" bgcolor="#fafafa" title="TECHO PRESUPUESTARIO"></th>';
              }
              $tabla.='
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%; text-align:center;" bgcolor="#fafafa" title="DESCRIPCI&Oacute;N">GASTO CORRIENTE</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="NIVEL">ESCALON</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="NIVEL">NIVEL</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="ESTADO"></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($unidades as $row){
              $color='#ccefcc';
              $estado='APROBADO';

              if($row['proy_estado']==4){
                
                /*$link=site_url("").'/mod/cite_techo/'.$row['proy_id']; /// link de modificacion presupuestaria
                if($this->tp_adm!=1){
                  $link=site_url("").'/mod/add_ppto_reversion/'.$row['proy_id']; /// link de reversion de presupuestos
                }*/

                $nro++;
                $tabla.='
                <tr style="font-size:10px;">
                  <td align=center><b>'.$nro.'</b></td>
                  <td align=center>';
                    if($this->conf_mod_ope==1){
                      $tabla.='<a href="'.site_url("").'/mod/list_componentes/'.$row['proy_id'].'" title="MODIFICAR ACTIVIDADES" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_edit.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px;"><b>FORM. N°4</b></div></a>';
                    }
                    else{
                      $tabla.='<div style="color:red; font-size:9px"><b>NO DISPONIBLE</b></div>';
                    }
                    $tabla.='
                  </td>
                  <td align=center>';
                    if($this->conf_mod_req==1){
                      $tabla.='<a href="'.site_url("").'/mod/form5/'.$row['proy_id'].'" title="MODIFICAR REQUERIMIENTOS" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_edit.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px;"><b>FORM. N°5</b></div></a>';
                    }
                    else{
                      $tabla.='<div style="color:red; font-size:9px"><b>NO DISPONIBLE</b></div>';
                    }
                  $tabla.='
                  </td>
                  <td align=center>
                    <a href="'.site_url("").'/mod/list_cites/'.$row['proy_id'].'" title="LISTA DE CITES GENERADOS POR MODIFICACI&Oacute;N" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_side_list.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:blue"><b>LISTA CITES</b></div></a>
                  </td>
                  <td align=center>
                    <a href="'.site_url("").'/mod/add_ppto_reversion/'.$row['proy_id'].'" title="REVERSION DE SALDOS" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/money_add.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:green"><b>SALDOS POA</b></div></a>
                  </td>';
                  if($this->tp_adm==1){
                    $tabla.='
                    <td align=center bgcolor="green">
                      <a href="'.site_url("").'/mod/cite_techo/'.$row['proy_id'].'" title="TECHO PRESUPUESTARIO" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/money_dollar.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:green"><b>TECHO PPTO.</b></div></a>
                    </td>';
                  }
                  $tabla.='
                  <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                  <td><b>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</b></td>
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

    /*---- Lista de Proyectos de Inversion Aprobados -----*/
    public function list_pinversion_aprobados(){
      $proyectos=$this->model_proyecto->listado_proyectos_inversion_aprobados_segun_tipo_responsable();
        $titulo_ppto='TECHO PPTO.'; /// Administrador
        if($this->tp_adm!=1){
          $titulo_ppto='REV. PPTO.'; /// Responsables POA
        }

      $tabla='';
      $tabla.='
        <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
          <thead>
            <tr style="font-size:10.5px;">
              <th style="width:1%; height:70px;" bgcolor="#fafafa">#</th>
              <th style="width:4%; text-align:center;" bgcolor="#fafafa" title="MODIFICACION FORMULARIO N° 4">MOD. FORM. N° 4</th>
              <th style="width:4%; text-align:center;" bgcolor="#fafafa" title="MODIFICACION FORMULARIO N° 5">MOD. FORM. N° 5</th>
              <th style="width:4%; text-align:center;" bgcolor="#fafafa" title="HISTORIAL DE CITES"></th>
              <th style="width:4%; text-align:center;" bgcolor="#fafafa" title="REVERSION DE SALDOS">REVERSION DE SALDOS</th>';
              if($this->tp_adm==1){
                $tabla.='<th style="width:4%; text-align:center;" bgcolor="#fafafa" title="TECHO PRESUPUESTARIO"></th>';
              }
              $tabla.='
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="LISTA DE CITES GENERADOS">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:10%; text-align:center;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:5%; text-align:center;" bgcolor="#fafafa" title="FASE - ETAPA">DESCRIPCI&Oacute;N FASE</th>
              <th style="width:15%;" bgcolor="#fafafa"></th>
              <th style="width:15%;" bgcolor="#fafafa"></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              /*$link=site_url("").'/mod/cite_techo/'.$row['proy_id']; /// link de modificacion presupuestaria
                if($this->tp_adm!=1){
                  $link=site_url("").'/mod/add_ppto_reversion/'.$row['proy_id']; /// link de reversion de presupuestos
                }*/
              $nro++;
              $tabla.='
              <tr style="font-size:10px;">
                <td style="height:70px;width:1%;" title='.$row['proy_id'].'></td>
                <td style="width:4%;" align=center>';
                  if($this->conf_mod_ope==1){
                    $tabla.='<a href="'.site_url("").'/mod/list_componentes/'.$row['proy_id'].'" title="MODIFICAR ACTIVIDADES" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_edit.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px;"><b>FORM. N°4</b></div></a>';
                  }
                  else{
                    $tabla.='<div style="color:red; font-size:9px"><b>NO DISPONIBLE</b></div>';
                  }
                  $tabla.='
                </td>
                <td style="width:4%;" align=center>';
                  if($this->conf_mod_req==1){
                    $tabla.='<a href="'.site_url("").'/mod/form5/'.$row['proy_id'].'" title="MODIFICAR REQUERIMIENTOS" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_edit.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px;"><b>FORM. N°5</b></div></a>';
                  }
                  else{
                    $tabla.='<div style="color:red; font-size:9px"><b>NO DISPONIBLE</b></div>';
                  }
                $tabla.='
                </td>
                <td style="width:4%;" align=center>
                  <a href="'.site_url("").'/mod/list_cites/'.$row['proy_id'].'" title="LISTA DE CITES GENERADOS POR MODIFICACI&Oacute;N" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_side_list.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:blue"><b>LISTA CITES</b></div></a>
                </td>
                <td style="width:4%;" align=center>
                  <a href="'.site_url("").'/mod/add_ppto_reversion/'.$row['proy_id'].'" title="REVERSION DE SALDOS" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/money_add.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:green"><b>SALDOS POA</b></div></a>
                </td>';
                  if($this->tp_adm==1){
                    $tabla.='
                    <td style="width:4%;" align=center bgcolor="green">
                      <a href="'.site_url("").'/mod/cite_techo/'.$row['proy_id'].'" title="TECHO PRESUPUESTARIO" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/Iconos/money_dollar.png" WIDTH="30" HEIGHT="30"/><br><div style="font-size: 9px; color:green"><b>TECHO PPTO.</b></div></a>
                    </td>';
                  }
                  $tabla.='
                <td style="width:5%;"><center>'.$row['proy'].'</center></td>
                <td style="width:10%;">'.$row['proyecto'].'</td>
                <td style="width:5%;">'.strtoupper($row['dep_departamento']).'</td>
                <td style="width:5%;">'.strtoupper($row['dist_distrital']).'</td>
                <td style="width:5%;">'.strtoupper($row['pfec_descripcion']).'</td>
                <td style="width:15%;">'.strtoupper($row['proy_obj_general']).'</td>
                <td style="width:15%;">'.strtoupper($row['proy_obj_especifico']).'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      
      return $tabla;
    }

//////////////// FORMULARIO N° 4 

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


    /*------ TITULO CABECERA (2023) (FORMULARIO N° 4)-----*/
    public function titulo_cabecera($cite,$tp){
      $tabla='';
      if($cite[0]['tp_id']==1){ /// Proyecto de Inversion
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Proyecto de Inversion
        $tabla.=' <h1> <b>PROYECTO : </b><small>'.$proyecto[0]['proy_sisin'].' - '.$proyecto[0]['proy_nombre'].'</small> / <b>UNIDAD RESPONSABLE : </b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cite[0]['proy_id']);
        $tabla.='<h1 title='.$proyecto[0]['aper_id'].'><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</small> / <small>'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }

      //// ------ Monto Presupuesto Programado-Asignado POA
        if($cite[0]['tipo_modificacion']==0){
          $monto=$this->ppto($proyecto);
          $tabla.='<h1><b> PPTO. ASIGNADO : <small>'.number_format($monto[1], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;PPTO PROGRAMADO : <small>'.number_format($monto[2], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;SALDO : <small>'.number_format($monto[3], 2, ',', '.').'</small></b></h1>';
        }
        else{
          $monto=$this->ppto_revertido($proyecto);
          $tabla.='<h1><b> PPTO. ASIGNADO (REVERTIDO) : <small>'.number_format($monto[1], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;PPTO PROGRAMADO (REVERTIDO) : <small>'.number_format($monto[2], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;SALDO : <small>'.number_format($monto[3], 2, ',', '.').'</small></b></h1>';
        }

        if($tp==1){
          if($monto[3]>1){
            $tabla.='
            <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO">
              <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>NUEVO REGISTRO (FORM. N 5)</b>
            </a>
            <a href="#" data-toggle="modal" data-target="#modal_importar" class="btn btn-default importar_ff" title="SUBIR ARCHIVO EXCEL">
              <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="25" HEIGHT="20"/>&nbsp;<b>SUBIR REQUERIMIENTOS.CSV </b>
            </a>';
          }
        }
        
      return $tabla;
    }

    /*--- MONTO PRESUPUESTO ASIGNADO - PROGRAMADO (TOTAL UNIDAD)(2023) ---*/
    public function ppto($proyecto){
      $monto_a=0;$monto_p=0;$monto_saldo=0;
      $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
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

    /*--- MONTO PRESUPUESTO ASIGNADO - PROGRAMADO (TOTAL UNIDAD REVERTIDO)(2023) ---*/
    public function ppto_revertido($proyecto){
      $monto_a=0;$monto_p=0;$monto_saldo=0;
      $monto_asig=$this->model_ptto_sigep->suma_ptto_revertido_total_unidad($proyecto[0]['aper_id'],1); /// asig revertido
      $monto_prog=$this->model_ptto_sigep->suma_ptto_revertido_total_unidad($proyecto[0]['aper_id'],2); /// prog revertido
      if(count($monto_asig)!=0){
        $monto_a=$monto_asig[0]['ppto_revertido'];
      }
      if(count($monto_prog)!=0){
        $monto_p=$monto_prog[0]['poa_revertido'];
      }

      $monto[1]=$monto_a; /// Monto Asignado Revertido
      $monto[2]=$monto_p; /// Monto Programado Revertido
      $monto[3]=($monto_a-$monto_p); /// Saldo

      return $monto;
    }


     /*------ LISTA FORMULARIO N° 4 (2020) (VISTA) ------*/
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
              <th style="width:5%;"><b>NRO. REQ.</b></th>
            </tr>
          </thead>
          <tbody>';
          $cont = 0;
          foreach($productos as $rowp){
            $cont++;
            $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
            //$monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
            $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
            /*$ptto=0;
            if(count($monto)!=0){
              $ptto=$monto[0]['total'];
            }*/

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
                <td align="center">';
                  if($rowp['prod_priori']==0){
                    $tabla.='
                    <a href="#" data-toggle="modal" data-target="#modal_mod_form4" class="btn btn-default mod_form4" name="'.$rowp['prod_id'].'" title="MODIFICAR ACTIVIDAD"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                    if($this->tmes==1){
                      if(count($this->model_producto->insumo_producto($rowp['prod_id']))==0){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mdel_ff" class="btn btn-default mdel_ff" title="ELIMINAR FORM 4"  name="'.$rowp['prod_id'].'" id="'.$cite[0]['cite_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                      }
                    }
                    elseif($this->fun_id==399){
                      $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mdel_ff" class="btn btn-default mdel_ff" title="ELIMINAR FORM 4"  name="'.$rowp['prod_id'].'" id="'.$cite[0]['cite_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/><br>Adm.</a>';
                    }
                    
                  }
                  else{
                    if($this->fun_id==399){
                      $tabla.='
                      <a href="#" data-toggle="modal" data-target="#modal_mod_form4" class="btn btn-default mod_form4" name="'.$rowp['prod_id'].'" title="MODIFICAR ACTIVIDAD"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                    }
                    $tabla.='<br><img src="'.base_url().'assets/ifinal/ok.png" WIDTH="37" HEIGHT="30"/><br><font size=1 color=green><b>PRIORIZADO</b></font>';
                  }
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
              $tabla.='<td style="width:5%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
            $tabla .='</tr>';
          }
          $tabla.='</tbody>
          </table>';

      return $tabla;
    }

  //// Lista de Items MODIFICADOS (Nuevo) para el reporte 2023
  public function items_modificados_form4_historial($cite_id,$tp_rep){
    /// tp_rep : 0 update
    /// tp_rep : 1 reporte
    $tabla='';
    $form4_add = $this->model_modfisica->list_form4_historial_modificados($cite_id,1); /// Add
    $form4_mod = $this->model_modfisica->list_form4_historial_modificados($cite_id,2); /// Mod
    $form4_del = $this->model_modfisica->list_form4_historial_modificados($cite_id,3); /// Del
    
      if(count($form4_add)!=0){
        $tabla.=$this->tabla_form4($form4_add,'ITEMS NUEVOS ('.count($form4_add).')');
      }
      if(count($form4_mod)!=0){
        $tabla.=$this->tabla_form4($form4_mod,'ITEMS MODIFICADOS ('.count($form4_mod).')');
      }
      if(count($form4_del)!=0){
        $tabla.=$this->tabla_form4($form4_del,'ITEMS ELIMINADOS ('.count($form4_del).')');
      }
    
    $tabla.='
            <div style="font-size: 7.5px;font-family: Arial;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
            </div>';
    return $tabla;
  }


  //// Lista de Items MODIFICADOS PARA EL REPORTE (listado nuevo 2023) FORM 4
  public function tabla_form4($listado,$detalle){
    $tabla='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$detalle.'</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.2%;">COD.<br>ACE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACP.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>OPE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:14%;">ACTIVIDAD</th>';
                $tabla.='<th style="width:14%;">RESULTADO</th>';
                $tabla.='<th style="width:7%;">UNIDAD RESPONSABLE</th>';
                $tabla.='<th style="width:8%;">INDICADOR</th>';
                $tabla.='<th style="width:2%;">L.B.</th>';
                $tabla.='<th style="width:2%;">META</th>';
                $tabla.='<th style="width:2.5%;">ENE.</th>';
                $tabla.='<th style="width:2.5%;">FEB.</th>';
                $tabla.='<th style="width:2.5%;">MAR.</th>';
                $tabla.='<th style="width:2.5%;">ABR.</th>';
                $tabla.='<th style="width:2.5%;">MAY.</th>';
                $tabla.='<th style="width:2.5%;">JUN.</th>';
                $tabla.='<th style="width:2.5%;">JUL.</th>';
                $tabla.='<th style="width:2.5%;">AGO.</th>';
                $tabla.='<th style="width:2.5%;">SEPT.</th>';
                $tabla.='<th style="width:2.5%;">OCT.</th>';
                $tabla.='<th style="width:2.5%;">NOV.</th>';
                $tabla.='<th style="width:2.5%;">DIC.</th>';
                $tabla.='<th style="width:10%;">MEDIO DE VERIFICACIÓN</th>';
             
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';

              $nro=0;
              foreach($listado as $rowp){
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                $color=''; $tp='';
                if($rowp['indi_id']==1){
                  if(($sum[0]['meta_gest'])!=$rowp['prodh_meta']){
                    $color='#fbd5d5';
                  }
                }
                elseif ($rowp['indi_id']==2) {
                  $tp='%';
                  if($rowp['mt_id']==3){
                    if(($sum[0]['meta_gest'])!=$rowp['prodh_meta']){
                      $color='#fbd5d5';
                    }
                  }
                }

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $nro++;
                $tabla.=
                '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                  <td style="width: 1%; height:12px;text-align: center;" bgcolor='.$color_or.'>'.$nro.'</td>
                  <td style="width: 2.2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 14%; text-align: left;font-size: 7px;">'.$rowp['prodh_producto'].'</td>
                  <td style="width: 14%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prodh_unidades']).'</td>
                  <td style="width: 8%; text-align: left;">'.$rowp['prodh_indicador'].'</td>
                  <td style="width: 2%; text-align: right;">'.round($rowp['prodh_linea_base'],2).'</td>
                  <td style="width: 3%; text-align: right;" bgcolor="#eceaea"><b>'.round($rowp['prodh_meta'],2).' '.$tp.'</b></td>';

                  if(count($programado)!=0){
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width:2.5%;" bgcolor="#f5cace" align=right>0.00</td>';
                    }
                  }

                  $tabla.='
                  <td style="width: 10%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                 
                </tr>';

              }
              $tabla.='</tbody>
              </table><br>';

    return $tabla;
  }


  //// Lista de Items Modificados en la Edicion (Reporte PDF) 2022 (reporte antiguo)
  public function items_modificados_form4($cite_id){
    $tabla='';
            $ope_adicionados=$this->model_modfisica->operaciones_adicionados($cite_id);
            if(count($ope_adicionados)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTIVIDADES AGREGADOS ('.count($ope_adicionados).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.2%;">COD.<br>ACE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACP.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>OPE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:14%;">ACTIVIDAD</th>';
                $tabla.='<th style="width:14%;">RESULTADO</th>';
                $tabla.='<th style="width:7%;">UNIDAD RESPONSABLE</th>';
                $tabla.='<th style="width:8%;">INDICADOR</th>';
                $tabla.='<th style="width:2%;">L.B.</th>';
                $tabla.='<th style="width:2%;">META</th>';
                $tabla.='<th style="width:2.5%;">ENE.</th>';
                $tabla.='<th style="width:2.5%;">FEB.</th>';
                $tabla.='<th style="width:2.5%;">MAR.</th>';
                $tabla.='<th style="width:2.5%;">ABR.</th>';
                $tabla.='<th style="width:2.5%;">MAY.</th>';
                $tabla.='<th style="width:2.5%;">JUN.</th>';
                $tabla.='<th style="width:2.5%;">JUL.</th>';
                $tabla.='<th style="width:2.5%;">AGO.</th>';
                $tabla.='<th style="width:2.5%;">SEPT.</th>';
                $tabla.='<th style="width:2.5%;">OCT.</th>';
                $tabla.='<th style="width:2.5%;">NOV.</th>';
                $tabla.='<th style="width:2.5%;">DIC.</th>';
                $tabla.='<th style="width:10%;">MEDIO DE VERIFICACIÓN</th>';
             
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              foreach($ope_adicionados as $rowp){
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
              //  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
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

                /*$ptto=number_format(0, 2, '.', ',');
                if(count($monto)!=0){
                  $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                }*/

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $nro++;
                $tabla.=
                '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                  <td style="width: 1%; height:12px;text-align: center;" bgcolor='.$color_or.'>'.$nro.'</td>
                  <td style="width: 2.2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 14%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                  <td style="width: 14%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                  <td style="width: 8%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                  <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                  <td style="width: 3%; text-align: right;" bgcolor="#eceaea"><b>'.round($rowp['prod_meta'],2).' '.$tp.'</b></td>';

                  if(count($programado)!=0){
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width:2.5%;" bgcolor="#f5cace" align=right>0.00</td>';
                    }
                  }

                  $tabla.='
                  <td style="width: 10%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                 
                </tr>';

              }
              $tabla.='</tbody>
              </table><br>';
            }

            $ope_modificados=$this->model_modfisica->operaciones_modificados($cite_id);
            if(count($ope_modificados)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTIVIDADES MODIFICADOS ('.count($ope_modificados).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.2%;">COD.<br>ACE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACP.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>OPE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:14%;">ACTIVIDAD</th>';
                $tabla.='<th style="width:14%;">RESULTADO</th>';
                $tabla.='<th style="width:7%;">UNIDAD RESPONSABLE</th>';
                $tabla.='<th style="width:8%;">INDICADOR</th>';
                $tabla.='<th style="width:2%;">L.B.</th>';
                $tabla.='<th style="width:2%;">META</th>';
                $tabla.='<th style="width:2.5%;">ENE.</th>';
                $tabla.='<th style="width:2.5%;">FEB.</th>';
                $tabla.='<th style="width:2.5%;">MAR.</th>';
                $tabla.='<th style="width:2.5%;">ABR.</th>';
                $tabla.='<th style="width:2.5%;">MAY.</th>';
                $tabla.='<th style="width:2.5%;">JUN.</th>';
                $tabla.='<th style="width:2.5%;">JUL.</th>';
                $tabla.='<th style="width:2.5%;">AGO.</th>';
                $tabla.='<th style="width:2.5%;">SEPT.</th>';
                $tabla.='<th style="width:2.5%;">OCT.</th>';
                $tabla.='<th style="width:2.5%;">NOV.</th>';
                $tabla.='<th style="width:2.5%;">DIC.</th>';
                $tabla.='<th style="width:10%;">MEDIO DE VERIFICACIÓN</th>';
              //  $tabla.='<th style="width:5%;">PPTO.</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              foreach($ope_modificados as $rowp){
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
               // $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
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

               /* $ptto=number_format(0, 2, '.', ',');
                if(count($monto)!=0){
                  $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                }*/

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $nro++;
                $tabla.=
                '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                  <td style="width: 1%; height:12px;text-align: center;" bgcolor='.$color_or.'>'.$nro.'</td>
                  <td style="width: 2.2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 14%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                  <td style="width: 14%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                  <td style="width: 8%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                  <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                  <td style="width: 3%; text-align: right;" bgcolor="#eceaea"><b>'.round($rowp['prod_meta'],2).' '.$tp.'</b></td>';

                  if(count($programado)!=0){
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width:2.5%;" bgcolor="#f5cace" align=right>0.00</td>';
                    }
                  }

                  $tabla.='
                  <td style="width: 10%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                 
                </tr>';

              }
              $tabla.='</tbody>
              </table><br>';
            }

            $ope_eliminados=$this->model_modfisica->operaciones_eliminados($cite_id);
            if(count($ope_eliminados)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTIVIDADES ELIMINADOS ('.count($ope_eliminados).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.2%;">COD.<br>ACE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACP.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>OPE.</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:14%;">ACTIVIDAD</th>';
                $tabla.='<th style="width:14%;">RESULTADO</th>';
                $tabla.='<th style="width:7%;">UNIDAD RESPONSABLE</th>';
                $tabla.='<th style="width:8%;">INDICADOR</th>';
                $tabla.='<th style="width:2%;">L.B.</th>';
                $tabla.='<th style="width:2%;">META</th>';
                $tabla.='<th style="width:2.5%;">ENE.</th>';
                $tabla.='<th style="width:2.5%;">FEB.</th>';
                $tabla.='<th style="width:2.5%;">MAR.</th>';
                $tabla.='<th style="width:2.5%;">ABR.</th>';
                $tabla.='<th style="width:2.5%;">MAY.</th>';
                $tabla.='<th style="width:2.5%;">JUN.</th>';
                $tabla.='<th style="width:2.5%;">JUL.</th>';
                $tabla.='<th style="width:2.5%;">AGO.</th>';
                $tabla.='<th style="width:2.5%;">SEPT.</th>';
                $tabla.='<th style="width:2.5%;">OCT.</th>';
                $tabla.='<th style="width:2.5%;">NOV.</th>';
                $tabla.='<th style="width:2.5%;">DIC.</th>';
                $tabla.='<th style="width:10%;">MEDIO DE VERIFICACIÓN</th>';
              //  $tabla.='<th style="width:5%;">PPTO.</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              foreach($ope_eliminados as $rowp){
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
              //  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
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

              /*  $ptto=number_format(0, 2, '.', ',');
                if(count($monto)!=0){
                  $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                }*/

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $nro++;
                $tabla.=
                '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                  <td style="width: 1%; height:12px;text-align: center;" bgcolor='.$color_or.'>'.$nro.'</td>
                  <td style="width: 2.2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                  <td style="width: 2.1%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 14%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                  <td style="width: 14%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                  <td style="width: 8%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                  <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                  <td style="width: 3%; text-align: right;" bgcolor="#eceaea"><b>'.round($rowp['prod_meta'],2).' '.$tp.'</b></td>';

                  if(count($programado)!=0){
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" bgcolor="#e5fde5" align=right>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width:2.5%;" bgcolor="#f5cace" align=right>0.00</td>';
                    }
                  }

                  $tabla.='
                  <td style="width: 10%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                 
                </tr>';

              }
              $tabla.='</tbody>
              </table><br>';
            }

            $tabla.='
            <div style="font-size: 7.5px;font-family: Arial;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
            </div>';
    
    return $tabla;
  }

  





//////////////// FORMULARIO N5

 /*------ Lista de Servicios para modificacion de Requerimientos --------*/
    public function lista_unidades_responsables($proyecto){
      $tabla='';
      $tabla.=$this->unidades_responsables($proyecto);

      return $tabla;
    }

    /*------ Lista de Servicios (Gasto Corriente) ------*/
    public function unidades_responsables($proyecto){
      $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']);
      $saldos_revertidos_partidas=$this->model_ptto_sigep->lista_monto_partidas_revertidos_unidad($proyecto[0]['proy_id']);
      $tabla='';
        if(count($fase)!=0){
            $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);
            $tabla.='
              <div class="well">
              <table class="table table-bordered" width="100%">
                <thead>
                  <tr style="height:45px;">
                    <th style="width:1%; text-align:center;">'.count($saldos_revertidos_partidas).'</th>
                    <th style="width:5%; text-align:center;">CODIGO</th>
                    <th style="width:20%; text-align:center;">UNIDAD RESPONSABLE</th>
                    <th style="width:5%; text-align:center;">REG. CITE<br>MODIFICACION POA</th>';
                    if(count($saldos_revertidos_partidas)!=0){
                      $tabla.='<th style="width:5%; text-align:center;"><b>REG. CITE<br>REVERSION POA</b></th>';
                    }

                    $tabla.='
                    <th style="width:2%;"></th>
                  </tr>
                </thead>
                <tbody>';
                $num=0; $ponderacion=0; $sum=0;
                foreach($componente as $row){
                  $num++;
                  $tabla.='
                  <tr>
                    <td align=center title="'.$row['com_id'].'">'.$num.'</td><td bgcolor="#d4f1fb" align="center" title="C&Oacute;DIGO UNIDAD : '.$row["serv_descripcion"].'"><font color="blue" size=3><b>'.$row['serv_cod'].'</b></font></td>
                    <td title='.$row['com_id'].'>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>
                    <td align=center>';
                      if($this->conf_mod_req==1 || $this->tp_adm==1){
                        $tabla.='
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff"  title="MODIFICAR REQUERIMIENTOS" name="'.$row['com_id'].'" id="0">
                          <img src="'.base_url().'assets/Iconos/application_form_add.png" WIDTH="30" HEIGHT="30"/>&nbsp;
                          <b style="font-size:10px;">INGRESAR DATOS CITE</b>
                        </a>';
                      }
                    $tabla.='
                    </td>';
                      if(count($saldos_revertidos_partidas)!=0){
                        $tabla.='
                        <td align=center>
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-warning nuevo_ff"  title="MODIFICAR REQUERIMIENTOS POR REVERSION DE SALDOS" name="'.$row['com_id'].'" id="1">
                            <img src="'.base_url().'assets/Iconos/application_form_magnify.png" WIDTH="30" HEIGHT="30"/>&nbsp;
                          <b style="font-size:10px;">INGRESAR DATOS CITE</b>
                        </a>
                        </td>';
                      }
                    $tabla.='
                    
                    <td align=center>';
                      if($this->fun_id==399){
                        $tabla.='
                        <a href="'.site_url("").'/mod/delete_insumos_eliminados/'.$row['com_id'].'" title="LIMPIAR" class="btn btn-default">
                        <img src="'.base_url().'assets/ifinal/registrono.png" WIDTH="34" HEIGHT="30"/>
                        </a>';
                      }
                    $tabla.='
                    </td>
                  </tr>';
                }
                $tabla.='    
                </tbody>
              </table>
            </div>';
        }
        else{
          $tabla.='<hr>
                  <div class="alert alert-danger" role="alert">
                    EL PROYECTO NO TIENE FASE ACTIVA PARA ESTA GESTIÓN '.$this->gestion.'  
                  </div>';
        }

      return $tabla;
    }



    /*----- LISTA REQUERIMIENTOS POR SUBACTIVIDAD AUXILIAR (2022) en casos de que sean muchos requerimientos ------*/
    public function modificar_requerimientos_auxiliar($cite){
      $lista_insumos=$this->model_modrequerimiento->lista_requerimientos($cite[0]['com_id'],$cite[0]['tipo_modificacion']);
      $tabla='';
      $total=0;
      $tabla.=' <input type="hidden" name="base" value="'.base_url().'">
                <input type="hidden" name="proy_id" value="'.$cite[0]['proy_id'].'">
                <input type="hidden" name="aper_id" value="'.$cite[0]['aper_id'].'">
                <input type="hidden" name="cite_id" value="'.$cite[0]['cite_id'].'">
                <table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                  <tr class="modo1">
                    <th style="width:2%;">'.$cite[0]['com_id'].'</th>
                    <th style="width:2%;">COD. ACT.</th>
                    <th style="width:2%;"></th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:15%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD DE MEDIDA</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">UNITARIO</th>
                    <th style="width:5%;">TOTAL</th>
                    <th style="width:5%;">TOTAL CERT.</th>
                    <th style="width:5%;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th style="width:8%;">OBSERVACIONES</th>';
                    if($this->tp_adm==1 & $cite[0]['proy_id']==2651){
                      $tabla.='
                      <th style="width:10%;"></th>';
                    }
                    $tabla.='
                    <th style="width:2%;">DELETE</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $color_tr=''; $dis=''; $title='title="REQUERIMIENTO"';
                  $monto_cert=0;$valor_mod=0; $valor_delete=0;
                  $tp_mod_registro='<div style="color:blue"><b>REG. x POA</b></div>';
                  $tp_mod_color='';
                  if($row['ins_tipo_modificacion']==1){
                    $tp_mod_registro='<div style="color:F5AB39"><b>REG. x REV.</b></div>';
                    $tp_mod_color='';
                  }


                  if($row['ins_monto_certificado']!=0){
                    if($row['ins_monto_certificado']==$row['ins_costo_total']){
                      $color_tr='#f9d8e0';
                      $valor_mod=1;
                      $valor_delete=1;
                    }
                    elseif ($row['ins_monto_certificado']<$row['ins_costo_total']) {
                      $valor_delete=1;
                    }
                  }

                  $cont++;
                    $tabla .='<tr bgcolor='.$color_tr.'>';
                    $tabla .='<td title='.$row['ins_id'].'>'.$tp_mod_registro.'</td>';
                    $tabla .='<td align=center bgcolor="#ecf9f7" title="CODIGO ACTIVIDAD"><font size=5 color=blue><br><b>'.$row['prod_cod'].'</b></font></td>';
                    $tabla .='<td align=center>';
                      if($valor_mod==0 & $valor_delete==0){
                        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>
                                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" >
                                    <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                                  </a>';
                      }
                      elseif($valor_mod==0 & $valor_delete==1){
                        $tabla.='
                            <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                            <a href="#" data-toggle="modal" data-target="#modal_certpoas" class="btn btn-default certpoas" name="'.$row['ins_id'].'" title="VER MIS CERTIFICACIONES POA- '.$row['ins_id'].'"><img src="'.base_url().'assets/img/ifinal/doc.jpg" WIDTH="35" HEIGHT="35"/></a>';
                      }
                      else{
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_certpoas" class="btn btn-default certpoas" name="'.$row['ins_id'].'" title="VER MIS CERTIFICACIONES POA- '.$row['ins_id'].'"><img src="'.base_url().'assets/img/ifinal/doc.jpg" WIDTH="35" HEIGHT="35"/></a><br>';
                      }

                      /*$ins_certificado=$this->model_certificacion->verif_insumo_certificados($row['ins_id']);
                      if(count($ins_certificado)!=0){
                        $tabla.='<a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$ins_certificado[0]['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="30" HEIGHT="30"/><br>CERT. POA</a>';
                      } */
                    $tabla.='</td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;" bgcolor="#f1dfb9">'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</td>';
                    
                    $tabla.='
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>';
                    $tabla .= ' 
                      <td style="width:8%;">'.$row['ins_observacion'].'</td>';
                      if($this->tp_adm==1 & $cite[0]['proy_id']==2651){
                        $tabla.='
                        <td style="width:10%;">';
                          $uresponsables = $this->model_modrequerimiento->list_uresponsables(); // Lista de productos
                            $tabla .='<select class="form-control" style="width:100%;" onchange="doSelectAlert(event,this.value,'.$row['ins_id'].');">
                              <option value="0">Seleccione unidad ..</option>';
                              foreach($uresponsables as $pr){
                                if($pr['com_id']==$row['serv_id']){
                                  $tabla .="<option value=".$pr['com_id']." selected>".$pr['tipo_subactividad']." ".$pr['serv_descripcion']."</option>";
                                }
                                else{
                                  $tabla .="<option value=".$pr['com_id'].">".$pr['tipo_subactividad']." ".$pr['serv_descripcion']."</option>"; 
                                }
                              }
                            $tabla.='</select>';
                        $tabla.='
                        </td>';
                      }
                      $tabla.='
                      <td style="width:2%;" bgcolor="#f3cbcb">';
                        if($valor_mod==0 & $valor_delete==0){
                          $tabla.='<center><input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/></center>';
                        }
                      $tabla.='
                      </td>';
                        
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                  ?>
                  <script>
                    function scheck<?php echo $cont;?>(estaChequeado) {
                      val = parseInt($('[name="tot"]').val());
                      if (estaChequeado == true) {
                        val = val + 1;
                      } else {
                        val = val - 1;
                      }
                      $('[name="tot"]').val((val).toFixed(0));
                    }
                  </script>
                  <?php
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="8">proy: '.$cite[0]['proy_id'].' | aper: '.$cite[0]['aper_id'].' <b>TOTAL</b> </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="16"></td>
                  </tr>
              </table>';

      return $tabla;
    }


    /*----- LISTA REQUERIMIENTOS POR SUBACTIVIDAD COMPLETO (2022) ------*/
    public function modificar_requerimientos($cite){
      $lista_insumos=$this->model_modrequerimiento->lista_requerimientos($cite[0]['com_id'],$cite[0]['tipo_modificacion']);

      $tabla='';
      $total=0;
      $tabla.=' <input type="hidden" name="proy_id" value="'.$cite[0]['proy_id'].'">
                <input type="hidden" name="aper_id" value="'.$cite[0]['aper_id'].'">
                <input type="hidden" name="cite_id" value="'.$cite[0]['cite_id'].'">
                <input type="hidden" name="base" value="'.base_url().'">
                
                <table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                  <tr class="modo1">
                    <th style="width:2%;">#</th>
                    <th style="width:2%;">COD. ACT.</th>
                    <th style="width:2%;"></th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:15%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">UNITARIO</th>
                    <th style="width:5%;">TOTAL</th>
                    <th style="width:5%;">TOTAL CERT.</th>
                    <th style="width:5%;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th style="width:8%;">OBSERVACIONES</th>
                    <th style="width:2%;">DELETE</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $color_tr=''; $dis=''; $title='title="REQUERIMIENTO"';
                  $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  $monto_cert=0;$valor_mod=0; $valor_delete=0;
                  if($row['ins_monto_certificado']!=0){
                    if($row['ins_monto_certificado']==$prog[0]['programado_total']){
                      $color_tr='#f9d8e0';
                      $valor_mod=1;
                      $valor_delete=1;
                    }
                    elseif ($row['ins_monto_certificado']<$prog[0]['programado_total']) {
                      $color_tr='#f7ebd3';
                      $valor_delete=1;
                    }
                  }

                  $tp_mod_registro='<div style="color:blue"><b>REG. x POA</b></div>';
                  if($row['ins_tipo_modificacion']==1){
                    $tp_mod_registro='<div style="color:#2BD6C7"><b>REG. x REV.</b></div>';
                  }

                  $cont++;
                    $tabla .='<tr bgcolor='.$color_tr.'>';
                    $tabla .='<td title='.$row['ins_id'].'>'.$tp_mod_registro.'</td>';
                    $tabla .='<td align=center bgcolor="#ecf9f7" title="CODIGO ACTIVIDAD"><font size=3 color=blue><br>'.$row['prod_cod'].'</font></td>';
                    $tabla .='<td align=center>';
                      if($valor_mod==0 & $valor_delete==0){
                        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" >
                                    <img src="'.base_url().'assets/img/delete.png" width="35" height="35"/>
                                  </a>';
                      }
                      elseif($valor_mod==0 & $valor_delete==1){
                        $tabla.='
                            <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                            <a href="#" data-toggle="modal" data-target="#modal_certpoas" class="btn btn-default certpoas" name="'.$row['ins_id'].'" title="VER MIS CERTIFICACIONES POA- '.$row['ins_id'].'"><img src="'.base_url().'assets/img/ifinal/doc.jpg" WIDTH="35" HEIGHT="35"/></a>';
                      }
                      else{
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_certpoas" class="btn btn-default certpoas" name="'.$row['ins_id'].'" title="VER MIS CERTIFICACIONES POA- '.$row['ins_id'].'"><img src="'.base_url().'assets/img/ifinal/doc.jpg" WIDTH="35" HEIGHT="35"/></a><br>';
                      }

                     /* $ins_certificado=$this->model_certificacion->verif_insumo_certificados($row['ins_id']);
                      if(count($ins_certificado)!=0){
                        $tabla.='<a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$ins_certificado[0]['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="30" HEIGHT="30"/><br>CERT. POA</a>';
                      }*/
                    $tabla.='</td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;" bgcolor="#f1dfb9">'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</td>';

                    if(count($prog)!=0){
                      $tabla.='<td style="width:5%;">'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td> ';
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                      }
                    }
                    else{
                      $tabla.='<td style="width:5%;">0</td>';
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td style="width:5%;" bgcolor="#ffeeeb">0</td>';
                      }
                    }
                    
                    $tabla .= ' 
                      <td style="width:8%;">'.$row['ins_observacion'].'</td>
                      <td style="width:2%;" bgcolor="#f3cbcb">';
                        if($valor_mod==0 & $valor_delete==0){
                          $tabla.='<center><input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/></center>';
                        }
                      $tabla.='
                      </td>';
                        
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                  ?>
                  <script>
                    function scheck<?php echo $cont;?>(estaChequeado) {
                      val = parseInt($('[name="tot"]').val());
                      if (estaChequeado == true) {
                        val = val + 1;
                      } else {
                        val = val - 1;
                      }
                      $('[name="tot"]').val((val).toFixed(0));
                    }
                  </script>
                  <?php
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="8">proy: '.$cite[0]['proy_id'].' | aper: '.$cite[0]['aper_id'].' <b>TOTAL</b> </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="16"></td>
                  </tr>
              </table>';

      return $tabla;
    }


  //// Lista de Items MODIFICADOS PARA EL REPORTE (listado nuevo 2023)
  public function tabla($tipo_mod,$listado,$detalle){
    $tabla='';

    if($tipo_mod==2){ /// MODIFICACION
       $tabla.='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$detalle.'</b></div>
            <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
              <thead>
              <tr class="modo1" style="text-align: center;" bgcolor="#efefef">
                <th style="width:1%;height:20px;">#</th>
                <th style="width:2.1%;">COD.<br>ACT.</th>
                <th style="width:3.5%;">PARTIDA</th>
                <th style="width:16.5%;">DETALLE REQUERIMIENTO</th>
                <th style="width:4.6%;">UNIDAD MEDIDA</th>
                <th style="width:4%;">CANT.</th>
                <th style="width:4%;">PRECIO UNI.</th>
                <th style="width:4%;">COSTO TOTAL</th>
                <th style="width:4.4%;">ENE.</th>
                <th style="width:4.4%;">FEB.</th>
                <th style="width:4.4%;">MAR.</th>
                <th style="width:4.4%;">ABR.</th>
                <th style="width:4.4%;">MAY.</th>
                <th style="width:4.4%;">JUN.</th>
                <th style="width:4.4%;">JUL.</th>
                <th style="width:4.4%;">AGO.</th>
                <th style="width:4.4%;">SEPT.</th>
                <th style="width:4.4%;">OCT.</th>
                <th style="width:4.4%;">NOV.</th>
                <th style="width:4.4%;">DIC.</th>
                <th style="width:6%;">OBSERVACIÓN</th>
              </tr>
              </thead>
              <tbody>';
              $nro=0;
              $monto=0;
              foreach ($listado as $row){
                $item_mod=$this->model_modrequerimiento->get_item_insumo_modificado_ultimo($row['cite_id'],2,$row['ins_id']);
                $prog = $this->model_modrequerimiento->list_temporalidad_insumo_historial($item_mod[0]['insh_id']);
                $nro++;
                $tabla.='<tr class="modo1">
                  <td style="width: 1%;height:11px; text-align: center;font-size: 6px;">'.$nro.'</td>
                  <td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$item_mod[0]['prod_cod'].'</b></td>
                  <td style="width: 3.5%; text-align: center;">'.$item_mod[0]['par_codigo'].'</td>
                  <td style="width: 16.5%; text-align: left;text-align: justify;">'.$item_mod[0]['ins_detalle'].'</td>
                  <td style="width: 4.6%; text-align: left;">'.$item_mod[0]['ins_unidad_medida'].'</td>
                  <td style="width: 4%; text-align: right;">'.$item_mod[0]['ins_cant_requerida'].'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($item_mod[0]['ins_costo_unitario'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($item_mod[0]['ins_costo_total'], 2, ',', '.').'</td>';
                  if(count($prog)!=0){
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                    }
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;" bgcolor=red>-</td>';
                    }
                  }
                  $tabla.='<td style="width: 6%; text-align: left;text-align: justify;font-size: 6px;">'.$item_mod[0]['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$item_mod[0]['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
    }
    else{ /// ADICION Y ELIMINACION
      $tabla.='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$detalle.'</b></div>
            <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
              <thead>
              <tr class="modo1" style="text-align: center;" bgcolor="#efefef">
                <th style="width:1%;height:20px;">#</th>
                <th style="width:2.1%;">COD.<br>ACT.</th>
                <th style="width:3.5%;">PARTIDA</th>
                <th style="width:16.5%;">DETALLE REQUERIMIENTO</th>
                <th style="width:4.6%;">UNIDAD MEDIDA</th>
                <th style="width:4%;">CANT.</th>
                <th style="width:4%;">PRECIO UNI.</th>
                <th style="width:4%;">COSTO TOTAL</th>
                <th style="width:4.4%;">ENE.</th>
                <th style="width:4.4%;">FEB.</th>
                <th style="width:4.4%;">MAR.</th>
                <th style="width:4.4%;">ABR.</th>
                <th style="width:4.4%;">MAY.</th>
                <th style="width:4.4%;">JUN.</th>
                <th style="width:4.4%;">JUL.</th>
                <th style="width:4.4%;">AGO.</th>
                <th style="width:4.4%;">SEPT.</th>
                <th style="width:4.4%;">OCT.</th>
                <th style="width:4.4%;">NOV.</th>
                <th style="width:4.4%;">DIC.</th>
                <th style="width:6%;">OBSERVACIÓN</th>
              </tr>
              </thead>
              <tbody>';
              $nro=0;
              $monto=0;
              foreach ($listado as $row){
                $prog = $this->model_modrequerimiento->list_temporalidad_insumo_historial($row['insh_id']);
                $nro++;
                $tabla.='<tr class="modo1">
                  <td style="width: 1%;height:11px; text-align: center;font-size: 6px;">'.$nro.'</td>
                  <td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>
                  <td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>
                  <td style="width: 16.5%; text-align: left;text-align: justify;">'.$row['ins_detalle'].'</td>
                  <td style="width: 4.6%; text-align: left;">'.$row['ins_unidad_medida'].'</td>
                  <td style="width: 4%; text-align: right;">'.$row['ins_cant_requerida'].'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                  if(count($prog)!=0){
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                    }
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;" bgcolor=red>-</td>';
                    }
                  }
                  $tabla.='<td style="width: 6%; text-align: left;text-align: justify;font-size: 6px;">'.$row['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$row['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
    }


    

    return $tabla;
  }




  //// Lista de Items MODIFICADOS PARA EL EDITADO (listado nuevo 2023)
  public function tabla_update($tipo_mod,$listado,$detalle,$table){
    $tabla='';
    $tabla.='
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="jarviswidget jarviswidget-color-darken">
        <header>
          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
            <h2 class="font-md"><strong>'.$detalle.'</strong></h2>  
        </header>
        <div>
          <div class="widget-body no-padding">';
                if($tipo_mod==1 || $tipo_mod==3){ /// Adicion y Eliminacion
                    $tabla.='
                    '.$table.'
                      <thead>
                      <tr class="modo1" style="text-align: center;" bgcolor="#efefef">
                        <th style="width:1%;height:20px;background-color: #1c7368; color: #FFFFFF"">#</th>
                        <th style="width:2.1%;background-color: #1c7368; color: #FFFFFF"">COD.<br>ACT.</th>
                        <th style="width:3.8%;background-color: #1c7368; color: #FFFFFF"">PARTIDA</th>
                        <th style="width:16%;background-color: #1c7368; color: #FFFFFF"">DETALLE REQUERIMIENTO</th>
                        <th style="width:4.6%;background-color: #1c7368; color: #FFFFFF"">UNIDAD MEDIDA</th>
                        <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">CANT.</th>
                        <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">PRECIO UNI.</th>
                        <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">COSTO TOTAL</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">ENE.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">FEB.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">MAR.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">ABR.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">MAY.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">JUN.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">JUL.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">AGO.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">SEPT.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">OCT.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">NOV.</th>
                        <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">DIC.</th>
                        <th style="width:6%;background-color: #1c7368; color: #FFFFFF"">OBSERVACIÓN</th>
                        <th style="width:2%;background-color: #1c7368; color: #FFFFFF"></th>
                      </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                      $monto=0;
                      foreach ($listado as $row){
                        $prog = $this->model_modrequerimiento->list_temporalidad_insumo_historial($row['insh_id']);
                        $nro++;
                        $tabla.='<tr class="modo1">
                          <td style="width: 1%;height:11px; text-align: center;font-size: 6px;" title='.$row['ins_id'].'>'.$nro.'</td>
                          <td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>
                          <td style="width: 3.8%; text-align: center;">'.$row['par_codigo'].'</td>
                          <td style="width: 16%; text-align: left;">'.$row['ins_detalle'].'</td>
                          <td style="width: 4.6%; text-align: left;">'.$row['ins_unidad_medida'].'</td>
                          <td style="width: 4%; text-align: right;">'.$row['ins_cant_requerida'].'</td>
                          <td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                          <td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                          if(count($prog)!=0){
                            for ($i=1; $i <=12 ; $i++) { 
                              $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                            }
                          }
                          else{
                            for ($i=1; $i <=12 ; $i++) { 
                              $tabla .= '<td style="width: 4.4%; text-align: right;" bgcolor=red>-</td>';
                            }
                          }
                          $tabla.='
                          <td style="width: 6%; text-align: left;">'.$row['ins_observacion'].'</td>
                          <td style="width: 2%; text-align: left;">
                            <a href="#" data-toggle="modal" data-target="#modal_anular_mod" class="btn btn-default anular_mod" title="NO MOSTRAR MODIFICACIÓN"  name="'.$row['insh_id'].'"><img src="'.base_url().'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></a>
                          </td>';
                        $tabla.='</tr>';
                        $monto=$monto+$row['ins_costo_total'];
                      }
                      $tabla.='</tbody>
                        <tr class="modo1">
                          <td style="height:11px;" colspan=7></td>
                          <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                          <td colspan=13></td>
                        </tr>
                      </table>
                      </div>';
                }
                else{ /// Modificacion
                $tabla.='
                '.$table.'
                  <thead>
                  <tr class="modo1" style="text-align: center;" bgcolor="#efefef">
                    <th style="width:1%;height:20px;background-color: #1c7368; color: #FFFFFF"">#</th>
                    <th style="width:2.1%;background-color: #1c7368; color: #FFFFFF"">COD.<br>ACT.</th>
                    <th style="width:3.8%;background-color: #1c7368; color: #FFFFFF"">PARTIDA</th>
                    <th style="width:16%;background-color: #1c7368; color: #FFFFFF"">DETALLE REQUERIMIENTO</th>
                    <th style="width:4.6%;background-color: #1c7368; color: #FFFFFF"">UNIDAD MEDIDA</th>
                    <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">CANT.</th>
                    <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">PRECIO UNI.</th>
                    <th style="width:4%;background-color: #1c7368; color: #FFFFFF"">COSTO TOTAL</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">ENE.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">FEB.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">MAR.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">ABR.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">MAY.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">JUN.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">JUL.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">AGO.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">SEPT.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">OCT.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">NOV.</th>
                    <th style="width:4.4%;background-color: #1c7368; color: #FFFFFF"">DIC.</th>
                    <th style="width:6%;background-color: #1c7368; color: #FFFFFF"">OBSERVACIÓN</th>
                    <th style="width:2%;background-color: #1c7368; color: #FFFFFF"></th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  $monto=0;
                  foreach ($listado as $row){
                    $item_mod=$this->model_modrequerimiento->get_item_insumo_modificado_ultimo($row['cite_id'],2,$row['ins_id']);
                    $prog = $this->model_modrequerimiento->list_temporalidad_insumo_historial($item_mod[0]['insh_id']);

                    $nro++;
                    $tabla.='<tr class="modo1">
                      <td style="width: 1%;height:11px; text-align: center;font-size: 6px;">'.$nro.'</td>
                      <td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$item_mod[0]['prod_cod'].'</b></td>
                      <td style="width: 3.8%; text-align: center;">'.$item_mod[0]['par_codigo'].'</td>
                      <td style="width: 16%; text-align: left;">'.$item_mod[0]['ins_detalle'].'</td>
                      <td style="width: 4.6%; text-align: left;">'.$item_mod[0]['ins_unidad_medida'].'</td>
                      <td style="width: 4%; text-align: right;">'.$item_mod[0]['ins_cant_requerida'].'</td>
                      <td style="width: 4%; text-align: right;">'.number_format($item_mod[0]['ins_costo_unitario'], 2, ',', '.').'</td>
                      <td style="width: 4%; text-align: right;">'.number_format($item_mod[0]['ins_costo_total'], 2, ',', '.').'</td>';
                      if(count($prog)!=0){
                        for ($i=1; $i <=12 ; $i++) { 
                          $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                        }
                      }
                      else{
                        for ($i=1; $i <=12 ; $i++) { 
                          $tabla .= '<td style="width: 4.4%; text-align: right;" bgcolor=red>-</td>';
                        }
                      }
                      $tabla.='
                      <td style="width: 6%; text-align: left;">'.$item_mod[0]['ins_observacion'].'</td>
                      <td style="width: 2%; text-align: left;">
                        <a href="#" data-toggle="modal" data-target="#modal_anular_mod" class="btn btn-default anular_mod" title="NO MOSTRAR MODIFICACIÓN"  name="'.$item_mod[0]['insh_id'].'"><img src="'.base_url().'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></a>
                      </td>';
                    $tabla.='</tr>';
                    $monto=$monto+$item_mod[0]['ins_costo_total'];
                  }
                  $tabla.='</tbody>
                    <tr class="modo1">
                      <td style="height:11px;" colspan=7></td>
                      <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                      <td colspan=13></td>
                    </tr>
                  </table>
                  </div>';
                }
          
              $tabla.='
            </div>
          </div>
        </article>
        <br>';

    return $tabla;
  }

  //// Lista de Items MODIFICADOS (Nuevo) para el reporte
  public function items_modificados_form5_historial($cite_id,$tp_rep){
    /// tp_rep : 0 update
    /// tp_rep : 1 reporte
    $cite=$this->model_modrequerimiento->get_cite_insumo($cite_id);
    $tabla='';
    $requerimientos_add = $this->model_modrequerimiento->list_form5_historial_modificados($cite_id,1); /// Add
    $requerimientos_mod = $this->model_modrequerimiento->get_list_form5_historial_modificados($cite_id,2); /// Mod
    $requerimientos_del = $this->model_modrequerimiento->list_form5_historial_modificados($cite_id,3); /// Del
    
    if($tp_rep==0){
      if(count($requerimientos_add)!=0){
        $tabla.=$this->tabla_update(1,$requerimientos_add,'ITEMS AGREGADOS ('.count($requerimientos_add).')','<table id="dt_basic1" class="table1 table-bordered" style="width:100%;" border="0.2">');
      }
      if(count($requerimientos_mod)!=0){
        $tabla.=$this->tabla_update(2,$requerimientos_mod,'ITEMS MODIFICADOS ('.count($requerimientos_mod).')','<table id="dt_basic" class="table table-bordered" style="width:100%;" border="0.2">',0);
      }
      if(count($requerimientos_del)!=0){
        $tabla.=$this->tabla_update(3,$requerimientos_del,'ITEMS ELIMINADOS ('.count($requerimientos_del).')','<table id="dt_basic3" class="table1 table-bordered" style="width:100%;" border="0.2">');
      }
    }
    else{

      if(count($requerimientos_add)!=0){
        $tabla.=$this->tabla(1,$requerimientos_add,'ITEMS AGREGADOS ('.count($requerimientos_add).')');
      }
      if(count($requerimientos_mod)!=0){
        $tabla.=$this->tabla(2,$requerimientos_mod,'ITEMS MODIFICADOS ('.count($requerimientos_mod).')');
      }
      if(count($requerimientos_del)!=0){
        $tabla.=$this->tabla(3,$requerimientos_del,'ITEMS ELIMINADOS ('.count($requerimientos_del).')');
      }


      
      $tabla.='
            <div style="font-size: 7.5px;font-family: Arial;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
              <br><br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>OBSERVACION :</b> '.$cite[0]['cite_observacion'].'
            </div>';
    }
    
    return $tabla;
  }


  //// Lista de Items MODIFICADOS (Nuevo) Nacional
  public function items_modificados_form5_historial_nacional(){
    $items_modificados=$this->model_modrequerimiento->lista_requerimientos_modificados_nacional();
    $tabla='';
    $tabla.='
      <style>
        table{font-size: 9px;
          width: 100%;
          max-width:1550px;
          overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>
    <table border="1" cellpadding="0" cellspacing="0" class="tabla">
      <thead>
        <tr style="background-color: #66b2e8">
          <th style="width:1%;height:20px;background-color: #eceaea;">#</th>
          <th style="width:5%;background-color: #eceaea;">COD. DEP.</th>
          <th style="width:10%;background-color: #eceaea;">REGIONAL</th>
          <th style="width:5%;background-color: #eceaea;">COD. DIST.</th>
          <th style="width:10%;background-color: #eceaea;">DISTRITAL</th>
          <th style="width:10%;background-color: #eceaea;">GASTO CORRIENTE / PROYECTO DE INVERSION</th>
          <th style="width:10%;background-color: #eceaea;">CITE CODIGO</th>
          <th style="width:10%;background-color: #eceaea;">CITE NOTA</th>
          <th style="width:10%;background-color: #eceaea;">PARTIDA</th>
          <th style="width:2.1%;background-color: #eceaea;">COD.<br>ACT.</th>
          <th style="width:3.8%;background-color: #eceaea;">PARTIDA</th>
          <th style="width:16%;background-color: #eceaea;">DETALLE REQUERIMIENTO</th>
          <th style="width:5%;background-color: #eceaea;">UNIDAD MEDIDA</th>
          <th style="width:5%;background-color: #eceaea;">CANTIDAD</th>
          <th style="width:5%;background-color: #eceaea;">PRECIO UNITARIO</th>
          <th style="width:5%;background-color: #eceaea;">COSTO TOTAL</th>
          <th style="width:5%;background-color: #eceaea;">ENE.</th>
          <th style="width:5%;background-color: #eceaea;">FEB.</th>
          <th style="width:5%;background-color: #eceaea;">MAR.</th>
          <th style="width:5%;background-color: #eceaea;">ABR.</th>
          <th style="width:5%;background-color: #eceaea;">MAY.</th>
          <th style="width:5%;background-color: #eceaea;">JUN.</th>
          <th style="width:5%;background-color: #eceaea;">JUL.</th>
          <th style="width:5%;background-color: #eceaea;">AGO.</th>
          <th style="width:5%;background-color: #eceaea;">SEPT.</th>
          <th style="width:5%;background-color: #eceaea;">OCT.</th>
          <th style="width:5%;background-color: #eceaea;">NOV.</th>
          <th style="width:5%;background-color: #eceaea;">DIC.</th>
          <th style="width:6%;background-color: #eceaea;">OBSERVACION</th>
          <th style="width:8%;background-color: #eceaea;">TIPO MODIFICACION</th>
          <th style="width:8%;background-color: #eceaea;">FECHA MODIFICACION</th>
          <th style="width:8%;background-color: #eceaea;">RESPONSABLE</th>
        </tr>
      </thead>
      <tbody>';
      $nro=0;
      foreach($items_modificados as $row){
        $nro++;
        $tabla.='
        <tr>
          <td>'.$nro.'</td>
          <td>'.$row['dep_id'].'</td>
          <td>'.$row['da'].'</td>
          <td>'.$row['dep_departamento'].'</td>
          <td>'.$row['ue'].'</td>
          <td>'.$row['dist_distrital'].'</td>
          <td>'.mb_convert_encoding(strtoupper($row['tipo'].' '.$row['actividad'].' '.$row['abrev']), 'cp1252', 'UTF-8').'</td>
          <td>'.$row['cite_codigo'].'</td>
          <td style="width:3.8%; font-size: 15px;">'.$row['cite_nota'].'</td>
          <td style="width:2.1%; font-size: 15px;" align=center>'.$row['prod_cod'].'</td>
          <td style="width:3.8%; font-size: 15px;" align=center><b>'.$row['par_codigo'].'</b></td>
          <td>'.$row['ins_detalle'].'</td>
          <td>'.round($row['ins_unidad_medida'],2).'</td>
          <td>'.round($row['ins_cant_requerida'],2).'</td>
          <td>'.round($row['ins_costo_unitario'],2).'</td>
          <td>'.round($row['ins_costo_total'],2).'</td>';
          for ($i=1; $i <=12 ; $i++) { 
            $tabla.='<td align="left">'.round($row['mes'.$i]).'</td>';
          }
          $tabla.='
          <td>'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>
          <td>';
            $tipo='AGREGADO';
            if($row['tipo_mod']==2){
              $tipo='MODIFICADO';
            }
            else{
              $tipo='ELIMINADO'; 
            }
          $tabla.='
          </td>
          <td bgcolor="#dbfbf6">'.date('d/m/Y',strtotime($row['fecha_creacion'])).'</td>
          <td bgcolor="#dbfbf6">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
        </tr>';
      }
      $tabla.='
      </tbody>
    </table>';


    return $tabla;
  }


















  //// Lista de Items MODIFICADOS (listado anterior vigente)
  public function items_modificados_form5($cite_id){
    $tabla='';
            $requerimientos_add = $this->model_modrequerimiento->list_requerimientos_adicionados($cite_id);
            if(count($requerimientos_add)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>ITEMS AGREGADOS ('.count($requerimientos_add).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:3.8%;">PARTIDA</th>';
                $tabla.='<th style="width:16%;">DETALLE REQUERIMIENTO</th>';
                $tabla.='<th style="width:4.6%;">UNIDAD MEDIDA</th>';
                $tabla.='<th style="width:4%;">CANTIDAD</th>';
                $tabla.='<th style="width:4%;">PRECIO UNITARIO</th>';
                $tabla.='<th style="width:4%;">COSTO TOTAL</th>';
                $tabla.='<th style="width:4.4%;">ENE.</th>';
                $tabla.='<th style="width:4.4%;">FEB.</th>';
                $tabla.='<th style="width:4.4%;">MAR.</th>';
                $tabla.='<th style="width:4.4%;">ABR.</th>';
                $tabla.='<th style="width:4.4%;">MAY.</th>';
                $tabla.='<th style="width:4.4%;">JUN.</th>';
                $tabla.='<th style="width:4.4%;">JUL.</th>';
                $tabla.='<th style="width:4.4%;">AGO.</th>';
                $tabla.='<th style="width:4.4%;">SEPT.</th>';
                $tabla.='<th style="width:4.4%;">OCT.</th>';
                $tabla.='<th style="width:4.4%;">NOV.</th>';
                $tabla.='<th style="width:4.4%;">DIC.</th>';
                $tabla.='<th style="width:6%;">OBSERVACIÓN</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              $monto=0;
              foreach ($requerimientos_add as $row){
                $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                $nro++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td style="width: 1%;height:11px; text-align: center;font-size: 6px;">'.$nro.'</td>';
                  $tabla.='<td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
                  $tabla.='<td style="width: 3.8%; text-align: center;">'.$row['par_codigo'].'</td>';
                  $tabla.='<td style="width: 16%; text-align: left;">'.$row['ins_detalle'].'</td>';
                  $tabla.='<td style="width: 4.6%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                  if(count($prog)!=0){
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                    }
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width: 4.4%; text-align: right;">-</td>';
                    }
                  }
                  $tabla.='<td style="width: 6%; text-align: left;">'.$row['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$row['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
            }

            $requerimientos_mod = $this->model_modrequerimiento->list_requerimientos_modificados($cite_id);
            if(count($requerimientos_mod)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>ITEMS MODIFICADOS ('.count($requerimientos_mod).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:3.8%;">PARTIDA</th>';
                $tabla.='<th style="width:16%;">DETALLE REQUERIMIENTO</th>';
                $tabla.='<th style="width:4.6%;">UNIDAD MEDIDA</th>';
                $tabla.='<th style="width:4%;">CANTIDAD</th>';
                $tabla.='<th style="width:4%;">PRECIO UNITARIO</th>';
                $tabla.='<th style="width:4%;">COSTO TOTAL</th>';
                $tabla.='<th style="width:4.4%;">ENE.</th>';
                $tabla.='<th style="width:4.4%;">FEB.</th>';
                $tabla.='<th style="width:4.4%;">MAR.</th>';
                $tabla.='<th style="width:4.4%;">ABR.</th>';
                $tabla.='<th style="width:4.4%;">MAY.</th>';
                $tabla.='<th style="width:4.4%;">JUN.</th>';
                $tabla.='<th style="width:4.4%;">JUL.</th>';
                $tabla.='<th style="width:4.4%;">AGO.</th>';
                $tabla.='<th style="width:4.4%;">SEPT.</th>';
                $tabla.='<th style="width:4.4%;">OCT.</th>';
                $tabla.='<th style="width:4.4%;">NOV.</th>';
                $tabla.='<th style="width:4.4%;">DIC.</th>';
                $tabla.='<th style="width:6%;">OBSERVACIÓN</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              $monto=0;
              foreach ($requerimientos_mod as $row){
                $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                $nro++;
                  $tabla.='<tr class="modo1">';
                  $tabla.='<td style="width: 1%;height:11px; text-align: center;font-size: 6px;">'.$nro.'</td>';
                  $tabla.='<td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
                  $tabla.='<td style="width: 3.8%; text-align: center;">'.$row['par_codigo'].'</td>';
                  $tabla.='<td style="width: 16%; text-align: left;">'.$row['ins_detalle'].'</td>';
                  $tabla.='<td style="width: 4.6%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                  if(count($prog)!=0){
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
                    }
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width: 4.4%; text-align: right;">-</td>';
                    }
                  }
                  $tabla.='<td style="width: 6%; text-align: left;">'.$row['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$row['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
            }

            $requerimientos_del = $this->model_modrequerimiento->list_requerimientos_eliminados($cite_id);
            if(count($requerimientos_del)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>ITEMS ELIMINADOS ('.count($requerimientos_del).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1%;height:20px;">#</th>';
                $tabla.='<th style="width:2.1%;">COD.<br>ACT.</th>';
                $tabla.='<th style="width:3.8%;">PARTIDA</th>';
                $tabla.='<th style="width:16%;">DETALLE REQUERIMIENTO</th>';
                $tabla.='<th style="width:4.6%;">UNIDAD MEDIDA</th>';
                $tabla.='<th style="width:4%;">CANTIDAD</th>';
                $tabla.='<th style="width:4%;">PRECIO UNITARIO</th>';
                $tabla.='<th style="width:4%;">COSTO TOTAL</th>';
                $tabla.='<th style="width:4.4%;">ENE.</th>';
                $tabla.='<th style="width:4.4%;">FEB.</th>';
                $tabla.='<th style="width:4.4%;">MAR.</th>';
                $tabla.='<th style="width:4.4%;">ABR.</th>';
                $tabla.='<th style="width:4.4%;">MAY.</th>';
                $tabla.='<th style="width:4.4%;">JUN.</th>';
                $tabla.='<th style="width:4.4%;">JUL.</th>';
                $tabla.='<th style="width:4.4%;">AGO.</th>';
                $tabla.='<th style="width:4.4%;">SEPT.</th>';
                $tabla.='<th style="width:4.4%;">OCT.</th>';
                $tabla.='<th style="width:4.4%;">NOV.</th>';
                $tabla.='<th style="width:4.4%;">DIC.</th>';
                $tabla.='<th style="width:6%;">OBSERVACIÓN</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              $monto=0;
              foreach ($requerimientos_del as $row){
                $nro++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td style="width: 1%; height:11px;text-align: center;font-size: 6px;">'.$nro.'</td>';
                  $tabla.='<td style="width: 2.1%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
                  $tabla.='<td style="width: 3.8%; text-align: center;">'.$row['par_codigo'].'</td>';
                  $tabla.='<td style="width: 16%; text-align: left;">'.$row['ins_detalle'].'</td>';
                  $tabla.='<td style="width: 4.6%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                  $tabla.='<td style="width: 4%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla .= '<td style="width: 4.4%; text-align: right;">' . number_format($row['mes'.$i], 2, ',', '.') . '</td>';
                  }
                $tabla.='<td style="width: 6%; text-align: left;">'.$row['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$row['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
            }

          $tabla.='
            <div style="font-size: 8px;font-family: Arial;">
              &nbsp;&nbsp;&nbsp;&nbsp;En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
            </div>';
    
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

      $tipo_mod='';
      if($cite[0]['tipo_modificacion']==1){
        $tipo_mod='(Rev. POA)';
      }

      $comp='';
      if($cite[0]['por_id']==0){
        $comp='
        <tr>
          <td style="width:20%;">
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
              <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>&nbsp;UNIDAD RESPONSABLE</b></td><td style="width:5%;"></td></tr>
            </table>
          </td>
          <td style="width:80%;">
            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
              <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</td></tr>
            </table>
          </td>
        </tr>';
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
                '.strtoupper($cite[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")].' de '.date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                    <td style="height: 5%;font-family: Arial;">'.$codigo.'</td>
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
                          <td colspan=2 align=center style="width:100%;height: 40%;"><b>FORMULARIO MOD. N° 8 </b> '.$tipo_mod.'</td>
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
                              <tr><td style="width:95%;height: 40%;" bgcolor="#eceaea"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
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
                              <tr><td style="width:95%;height: 40%;" bgcolor="#eceaea"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
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
                                <tr><td style="width:95%;height: 40%;" bgcolor="#eceaea"><b>PROY. INVERSI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                            </table>
                        </td>
                        <td style="width:80%;">
                            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['proy_sisin'].' '.strtoupper ($cite[0]['proy_nombre']).'</td></tr>
                            </table>
                        </td>
                      </tr>';
                    }
                    else{
                      $tabla.='
                      <tr>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                <tr><td style="width:95%;height: 40%;" bgcolor="#eceaea"><b>CAT. PROGRAMATICA '.$this->gestion.'</b></td><td style="width:5%;"></td></tr>
                            </table>
                        </td>
                        <td style="width:80%;">
                            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['aper_programa'].''.$cite[0]['aper_proyecto'].''.$cite[0]['aper_actividad'].' '.strtoupper ($cite[0]['act_descripcion']).' '.$cite[0]['abrev'].'</td></tr>
                            </table>
                        </td>
                      </tr>';
                    }

                  $tabla.='
                  '.$comp.'
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


//// Pie de Modificacion POA
  public function pie_modpoa($cite,$codigo){
    $tabla='';
/*    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
          <tr>
            <td style="width: 1%;"></td>
            <td style="width: 75%;">
                <b>OBSERVACIÓN</b><hr>
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr bgcolor="#cae4fb">
                    <td style="width: 100%;height: 2%; font-size:5px">
                      <b>'.$cite[0]['cite_observacion'].'</b>
                    </td>
                  </tr>
                </table>
            </td>
          </tr>
        </table>';*/
      $tabla.='
      <table border=0 style="width:100%;">
        <tr>
          <td style="width:1%;"></td>
          <td style="width:98%;">
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
            <tr>';
            
            if($cite[0]['dep_id']==10){ /// Ritha
              $tabla.='
              <td style="width:30%;">
                  <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 8px;font-family: Arial;">
                        <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                    </tr>
                   
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><br><br>
                          <table border=0>
                            <tr style="font-size: 7px;font-family: Arial; height:65px;">
                                <td><b>RESPONSABLE : </b></td>
                                <td>'.$cite[0]['fun_nombre'].' '.$cite[0]['fun_paterno'].' '.$cite[0]['fun_materno'].'</td>
                            </tr>
                            <tr style="font-size: 7px;font-family: Arial; height:65px;">
                                <td><b>CARGO : </b></td>
                                <td><b>'.$cite[0]['fun_cargo'].'</b></td>
                            </tr>
                          </table>
                        </td>
                    </tr>
                  </table>
                </td>
                
                <td style="width:30%;">
                  <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                      <tr style="font-size: 8px;font-family: Arial;">
                          <td style="width:100%;height:13px;"><b>APROBADO POR</b></td>
                      </tr>
                     
                      <tr style="font-size: 8px;font-family: Arial; height:65px;" align="center">
                          <td><b><br><br><br><br>FIRMA</b></td>
                      </tr>
                  </table>
                </td>

                <td style="width:30%;">
                  <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                      <tr style="font-size: 8px;font-family: Arial;">
                          <td style="width:100%;height:13px;"><b>FIRMA / SELLO DE RECEPCION DE LA UNIDAD SOLICITANTE (FECHA)<br></b></td>
                      </tr>
                     
                      <tr style="font-size: 8px;font-family: Arial; height:65px;" align="center">
                          <td><b><br><br><br><br>FIRMA</b></td>
                      </tr>
                  </table>
                </td>';
            }
            else{
              $tabla.='
              <td style="width:45%;">
                <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr style="font-size: 9px;font-family: Arial;">
                      <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                  </tr>
                 
                  <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                      <td><br><br>
                        <table border=0>
                          <tr style="font-size: 7px;font-family: Arial; height:65px;">
                              <td><b>RESPONSABLE : </b></td>
                              <td>'.$cite[0]['fun_nombre'].' '.$cite[0]['fun_paterno'].' '.$cite[0]['fun_materno'].'</td>
                          </tr>
                          <tr style="font-size: 7px;font-family: Arial; height:65px;">
                              <td><b>CARGO : </b></td>
                              <td><b>'.$cite[0]['fun_cargo'].'</b></td>
                          </tr>
                        </table>
                      </td>
                  </tr>
                </table>
              </td>
              <td style="width:45%;">

                <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 9px;font-family: Arial;">
                        <td style="width:100%;height:13px;"><b>FIRMA / SELLO DE RECEPCION DE LA UNIDAD SOLICITANTE (FECHA)<br></b></td>
                    </tr>
                   
                    <tr style="font-size: 8px;font-family: Arial; height:65px;" align="center">
                        <td><b><br><br><br><br>FIRMA</b></td>
                    </tr>
                </table>

            </td>';
            }
            $tabla.='
                <td style="width:10%;" align=center>';
                  $cod='<div style="color: red;width:30%;"><b>Sin Codigo</b></div>';
                  if($codigo!=''){
                    $cod='<qrcode value="'.$codigo.'" style="border: none; width: 18mm;"></qrcode>';
                  }
                $tabla.=' '.$cod.'
                </td>
              </tr>
              <tr>
                <td colspan=2 style="height:18px;">'.$this->session->userdata('sistema').'</td>
                <td align=right>'.$cite[0]['fun_paterno'].' - pag. [[page_cu]]/[[page_nb]]</td>
              </tr>
            </table>
          </td>
         
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