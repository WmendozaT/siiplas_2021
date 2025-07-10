<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Genera_informacion extends CI_Controller{

    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
        $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_componente');
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->rol = $this->session->userData('rol_id');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tmes = $this->session->userData('trimestre');
        $this->ppto= $this->session->userData('verif_ppto');
        $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema

    }

    ////// LIBRERIAS PARA REPORTES GERENCIALES

 /*-- REPORTE 1 (LISTA DE UNIDADES/PROYECTOS DE INVERSIÓN)--*/
    public function lista_gastocorriente_pinversion($dep_id,$dist_id,$tp_id){
      
        if($dist_id!=0){
          $unidades=$this->mrep_operaciones->list_unidades($dist_id,$tp_id); /// unidades de la distrital
          $distrital=$this->model_proyecto->dep_dist($dist_id);
          $tit_reg=$distrital[0]['dist_distrital'];
        }
        else{
          $unidades=$this->mrep_operaciones->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id); /// unidades de la Regional
          $regional=$this->model_proyecto->get_departamento($dep_id);
          $tit_reg=$regional[0]['dep_departamento'];
        }
      
        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

        $titulo_ppto='PPTO. ASIGNADO '.$this->gestion.'';
        if($this->ppto==1){
          $titulo_ppto='PPTO. APROBADO '.$this->gestion.'';
        }

      $tabla='';

      if($this->rol!=10){ /// evitar audotira
        $tabla.='
        <br>
        <div align=lefth>
          <a href="'.site_url("").'/admin/dashboard" class="btn btn-success" title="VOLVER ATRAS"><img src="'.base_url().'assets/Iconos/arrow_rotate_clockwise.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;SALIR</a>&nbsp;&nbsp;';
          if($dep_id!=0 & $dist_id==0){
            $tabla.='<a href="'.site_url("").'/me/rep_form2/'.$dep_id.'" target=_blank class="btn btn-default" title="FORMULARIO N° 2 (OPERACIONES)"><img src="'.base_url().'assets/Iconos/page_white_acrobat.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;FORMULARIO N°2 (OPERACIONES)</a>&nbsp;&nbsp;';
          }
        $tabla.='
          <a href="'.site_url("").'/rep/comparativo_unidad_ppto/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="POA (ADMINISTRATIVO)"><img src="'.base_url().'assets/Iconos/page_white_acrobat.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR DETALLE POA</a>&nbsp;&nbsp;
          <a href="'.site_url("").'/rep/establecimientos/'.$dep_id.'/'.$dist_id.'" target=_blank class="btn btn-default" title="ESTABLECIMIENTOS DE SALUD"><img src="'.base_url().'assets/Iconos/page_white_acrobat.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR (EST. DE SALUD)</a>&nbsp;&nbsp;
          <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;CONSOLIDADO POA (FORM. N° 5)</a>&nbsp;&nbsp;
        </div>';
      }
      
        $tabla.='
        <br>
      <div class="alert alert-warning">
        <a href="#" class="alert-link" align=center><center><b>LISTA DE '.$titulo.' '.$this->gestion.' - '.strtoupper($tit_reg).'</b></center></a>
      </div>
      <section class="col col-6">
            <input id="searchTerm_lista" type="text" onkeyup="doSearch_lista()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
      </section>
      <table id="datos_lista" class="table table-bordered" style="width:100%;" border=1>
        <thead>
          <tr style="background-color: #66b2e8">
            <th style="width:1%;"></th>
            <th style="width:3%;">NOTIFICACIÓN POA '.$this->verif_mes[2].' / '.$this->gestion.'</th>
            <th style="width:5%;">REPORTE POA <br>'.$this->gestion.'</th>
            <th style="width:5%;">PROGRAMACIÓN POA '.$this->gestion.'</th>
            <th style="width:5%;">MODIFICACIÓN POA '.$this->gestion.'</th>
            <th style="width:5%;">CERTIFICACIÓN POA '.$this->gestion.'</th>
            <th style="width:5%;">EVALUACIÓN POA '.$this->gestion.'</th>
            <th style="width:5%;">COD. DA.</th>
            <th style="width:5%;">COD. UE.</th>
            <th style="width:5%;">COD. PROG.</th>
            <th style="width:5%;">COD. PROY.</th>
            <th style="width:5%;">COD. ACT.</th>
            <th style="width:20%;">'.$titulo.'</th>
            <th style="width:10%;"></th>
            <th style="width:10%;" title="">UNIDAD ADMINISTRATIVA</th>
            <th style="width:10%;" title="">UNIDAD EJECUTORA</th>
            <th style="width:8%;" title="">'.$titulo_ppto.'</th>
            <th style="width:8%;" title="">PPTO. POA '.$this->gestion.'</th>
            <th style="width:8%;" title="">SALDO</th>
            <th style="width:10%;" title=""></th>
          </tr>
        </thead>
        <tbody id="bdi">';
        $nro=0;
        foreach ($unidades as $row){
          $ppto=$this->ppto_actividad($row,$tp_id);
          $color='';
          if($ppto[3]<0){
            $color='#f3d8d7';
          }
          elseif($ppto[3]>0){
            $color='#e4f7f4'; 
          }
          
          $rep='';
          $estado='<font color="red"><b>NO APROBADO</b></font>';
          if($row['aper_proy_estado']==4){
            //$rep='<center><a href="javascript:abreVentana(\''.site_url("").'/prog/reporte_form4_consolidado/'.$row['proy_id'].'\');" title="REPORTE POA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="30" HEIGHT="30"/></a></center>';
            $rep='<center><a href="'.site_url("").'/prog/reporte_form4_consolidado/'.$row['proy_id'].'" target=_blank title="REPORTE POA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="30" HEIGHT="30"/></a></center>';
            $estado='<font color="#1c7368"><b>APROBADO</b></font>'; 
          }

          $nro++;
          $tabla.='
          <tr style="height:35px;" bgcolor="'.$color.'" title="'.$row['aper_id'].'">
            <td>'.$nro.'</td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.=' <a href="'.site_url("").'/seg/notificacion_operaciones_mensual/'.$row['proy_id'].'" class="btn btn-default" target="_blank" title="NOTIFICACIÓN POA">NOTIFICACIÓN POA</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='
            </td>
            <td>'.$rep.'</td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_poa" class="btn btn-default" name="'.$row['proy_id'].'"  onclick="ver_poa('.$row['proy_id'].');" title="FORMULARIO POA"><i class="fa fa-gear fa-2x fa-spin"></i> VER</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='
            </td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mpoa" class="btn bg-color-green txt-color-white" name="'.$row['proy_id'].'"  onclick="ver_mpoa('.$row['proy_id'].');" title="MODIFICACIONES POA"><i class="fa fa-gear fa-2x fa-spin"></i> VER</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='</td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_certpoa" class="btn bg-color-blue txt-color-white" name="'.$row['proy_id'].'"  onclick="ver_certpoa('.$row['proy_id'].');" title="CERTIFICACIONES POA"><i class="fa fa-gear fa-2x fa-spin"></i> VER</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='</td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_eval" class="btn bg-color-orange txt-color-white" name="'.$row['proy_id'].'"  onclick="ver_evaluacionpoa('.$row['proy_id'].');" title="EVALUACION POA"><i class="fa fa-gear fa-2x fa-spin"></i> VER</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='</td>
            <td align=center>'.$row['da'].'</td>
            <td align=center>'.$row['ue'].'</td>
            <td align=center>'.$row['prog'].'</td>
            <td align=center>'.$row['proy'].'</td>
            <td align=center>'.$row['act'].'</td>
            <td>';
              if($tp_id==1){
              $tabla.='<b>'.$row['proyecto'].'</b>';
            }
            else{
              $tabla.='<b>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</b>';
            }
            $tabla.='
            </td>
            <td>'.$titulo.'</td>
            <td>'.strtoupper($row['dep_departamento']).'</td>
            <td>'.strtoupper($row['dist_distrital']).'</td>
            <td align=right>'.number_format($ppto[1], 2, ',', '.').'</td>
            <td align=right>'.number_format($ppto[2], 2, ',', '.').'</td>
            <td align=right>'.number_format($ppto[3], 2, ',', '.').'</td>
            <td align=center>'.$estado.'</td>
          </tr>';
        }
        $tabla.='</tbody>
        </table>';
      return $tabla;
    }



      /// ==== Presupuesto Por unidad/ Proyecto
      public function ppto_actividad($proyecto,$tp_id){
        $salida[1]=0;$salida[2]=0;$salida[3]=0;

        $ppto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto['aper_id'],1); /// Asignado
        if($tp_id==1){
          $ppto_prog=$this->model_ptto_sigep->suma_ptto_pinversion($proyecto['proy_id']); /// Programado Proyecto Inversion
        }
        else{
          $ppto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto['aper_id'],2); /// Programado Gasto Corriente
        }

        $monto_asignado=0;$monto_programado=0;$saldo=0;
        if(count($ppto_asig)!=0){
          $monto_asignado=$ppto_asig[0]['monto'];
        }

        if(count($ppto_prog)!=0){
          $monto_programado=$ppto_prog[0]['monto'];
        }

        $saldo=($monto_asignado-$monto_programado);
        $salida[1]=$monto_asignado; /// asignado
        $salida[2]=$monto_programado; /// Programado
        $salida[3]=$saldo; /// Saldo

        return $salida;
    }





    /*-----REPORTE COMPARATIVO PRESUPUESTO ASIG-POA (DISTRITAL) 2020-2021-----*/
    public function comparativo_presupuesto_distrital($dep_id,$dist_id,$tp_id){
      $data['mes'] = $this->mes_nombre();
      if($dist_id==0){ // Nacional
        
        $unidades=$this->mrep_operaciones->list_poa_gastocorriente_pinversion($tp_id);
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo']='CONSOLIDADO - '.strtoupper($regional[0]['dep_departamento']).'';
        $unidades=$this->mrep_operaciones->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
        $data['titulo_reporte_pie']=$regional[0]['dep_departamento'];

      }
      else{ /// Distrital
        $distrital=$this->model_proyecto->dep_dist($dist_id);
        $data['titulo']=''.strtoupper($distrital[0]['dist_distrital']).'';
        $unidades=$this->mrep_operaciones->list_unidades($dist_id,$tp_id);
        $data['titulo_reporte_pie']=$distrital[0]['dist_distrital'];
      }

      
          $titulo='GASTO CORRIENTE';
          if($tp_id==1){
            $titulo='PROYECTO DE INVERSI&Oacute;N';
          }

        $titulo_ppto='PPTO. ASIGNADO '.$this->gestion.'';
        if($this->ppto==1){
          $titulo_ppto='PPTO. APROBADO '.$this->gestion.'';
        }

        $tabla='';
        $tabla.='
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" align=center bgcolor="#f5f7f8">
                    <th style="width:1%;">#</th>
                    <th style="width:5%;">COD. DA.</th>
                    <th style="width:5%;">COD. UE.</th>
                    <th style="width:5%;">COD. PROG.</th>
                    <th style="width:10%;">COD. PROY.</th>
                    <th style="width:5%;">COD. ACT.</th>
                    <th style="width:30%;">'.$titulo.'</th>
                    <th style="width:8%;" title="">'.$titulo_ppto.'</th>
                    <th style="width:8%;" title="">PPTO. POA '.$this->gestion.'</th>
                    <th style="width:8%;" title="">SALDO</th>
                  </tr>
                </thead>
                <tbody>';
                 $nro=0;
                 $suma_ppto_asig=0;$suma_ppto_prog=0;
                  foreach ($unidades as $row){
                    $ppto=$this->ppto_actividad($row,$tp_id);
                    $color='';
                    if($ppto[3]<0){
                      $color='#f3d8d7';
                    }
                    elseif($ppto[3]>0){
                      $color='#e4f7f4'; 
                    }
                    
                    $nro++;
                    $tabla.='
                    <tr bgcolor="'.$color.'" title="'.$row['aper_id'].'">
                      <td style="height:1px;" align=center>'.$nro.'</td>
                      <td style="height:15px;" align=center>'.$row['da'].'</td>
                      <td style="width:5%;" align=center>'.$row['ue'].'</td>
                      <td style="width:5%;" align=center>'.$row['prog'].'</td>
                      <td style="width:10%;" align=center>';
                      if($tp_id==1){
                        $tabla.=''.$row['proy'].'';
                      }
                      else{
                        $tabla.='0000';
                      }
                      $tabla.='
                      </td>
                      <td style="width:5%;" align=center>'.$row['act'].'</td>
                      <td style="width:30%;">';
                        if($tp_id==1){
                        $tabla.=''.$row['proyecto'].'';
                      }
                      else{
                        $tabla.=''.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'';
                      }
                      $tabla.='</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[1], 2, ',', '.').'</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[2], 2, ',', '.').'</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[3], 2, ',', '.').'</td>
                    </tr>';

                    $suma_ppto_asig=$suma_ppto_asig+$ppto[1];
                    $suma_ppto_prog=$suma_ppto_prog+$ppto[2];
                  }
                  $tabla.='
                  <tr>
                    <td colspan=7 align=right style="height:2px; font-size:8px;">TOTAL</td>
                    <td style="width:8%;font-size:8px;" align=right><b>'.number_format($suma_ppto_asig, 2, ',', '.').'</b></td>
                    <td style="width:8%;font-size:8px;" align=right><b>'.number_format($suma_ppto_prog, 2, ',', '.').'</b></td>
                    <td style="width:8%;font-size:8px;" align=right></td>
                  </tr>
                </tbody>
              </table>';

          $data['titulo_ppto']=$titulo_ppto;
          $data['titulo_reporte']='CUADRO COMPARATIVO - '.$titulo_ppto.' '.$this->gestion.' Vs MONTO PROGRAMADO '.$this->gestion.'';
          
          $data['lista']=$tabla;
        
          $this->load->view('admin/reportes_cns/resumen_operaciones/reporte_comparativo', $data);
    }



    /*-----REPORTE ESTABLECIMIENTOS DE SALUD (DISTRITAL) 2020-2021-----*/
    public function establecimientos_salud($dep_id,$dist_id){
      if($dist_id==0){
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $establecimientos=$this->mrep_operaciones->establecimientos_salud_regional($dep_id);
        $data['titulo_reporte_pie']=$regional[0]['dep_departamento'];
        $data['titulo']='REGIONAL : '.strtoupper($regional[0]['dep_departamento']);
      }
      else{
        $distrital=$this->model_proyecto->dep_dist($dist_id);
        $establecimientos=$this->mrep_operaciones->establecimientos_salud_distrital($dist_id);
        $data['titulo_reporte_pie']=$distrital[0]['dist_distrital'];
        $data['titulo']='DISTRITAL : '.strtoupper($distrital[0]['dist_distrital']);
      }


        $tabla='';
        $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
              <tr style="font-size: 7px;" align=center bgcolor="#f5f7f8">
                <th style="width:1%;height:15px;">#</th>
                <th style="width:8%;">COD. DA.</th>
                <th style="width:8%;">COD. ACT.</th>
                <th style="width:50%;">ESTABLECIMIENTO DE SALUD</th>
                <th style="width:15%;" title="">PPTO. ASIGNADO '.$this->gestion.'</th>
              </tr>
            </thead>
            <tbody>';
             $nro=0;
              foreach ($establecimientos as $row){
                $ppto=$this->ppto_actividad($row,4);
                $color='';
                if($ppto[3]<0){
                  $color='#f3d8d7';
                }
                elseif($ppto[3]>0){
                  $color='#e4f7f4'; 
                }
                
                $nro++;
                $tabla.='
                <tr bgcolor="'.$color.'" >
                  <td style="width:1%;height:15px;" align=center>'.$nro.'</td>
                  <td style="width:8; font-size:12px;" align=center>'.$row['dep_cod'].'</td>
                  <td style="width:8%; font-size:12px;" align=center>'.$row['act_cod'].'</td>
                  <td style="width:50%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                  <td style="width:15%;" align=right>'.number_format($ppto[1], 2, ',', '.').'</td>
                </tr>';
              }
              $tabla.='
            </tbody>
          </table>';

          $data['titulo_reporte']='ESTABLECIMIENTOS DE SALUD / '.$this->gestion;
          $data['mes'] = $this->mes_nombre();
          $data['lista']=$tabla;
          $this->load->view('admin/reportes_cns/resumen_operaciones/reporte_comparativo', $data);
    }


    ////========================================= CONSOLIDADO FORMULARIO N 4
    /*-- REPORTE 2 (CONSOLIDADO FORMULARIO N 4 REGIONAL O DIST) 2020-2021 - 2022 --*/
    public function consolidado_operaciones_distrital($dep_id,$dist_id,$tp_id){
      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

      if($this->gestion==2019){
        $tabla='No disponible';
      }
      else{
        if($dist_id==0){
          $regional=$this->model_proyecto->get_departamento($dep_id);
          $operaciones=$this->mrep_operaciones->consolidado_operaciones_regionales($dep_id,$tp_id); /// Actividades a Nivel de Regional
          $tit='CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']);
        }
        else{
          $dist=$this->model_proyecto->dep_dist($dist_id);
          $operaciones=$this->mrep_operaciones->operaciones_por_distritales($dist_id,$tp_id); /// Actividades a Nivel de distritales
          $tit=strtoupper($dist[0]['dist_distrital']);
        }
        
        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

        $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/admin/dashboard" class="btn btn-success" title="VOLVER ATRAS"><img src="'.base_url().'assets/Iconos/book_previous.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;VOLVER ATRAS</a>&nbsp;&nbsp;&nbsp;
          <a href="'.site_url("").'/rep/exportar_operaciones_distrital/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO FORMULARIO n° 4"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR CONSOLIDADO FORMULARIO N° 4</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div class="alert alert-success">
          <a href="#" class="alert-link" align=center><center><b>CONSOLIDADO DE FORMULARIO N° 4 '.$this->gestion.' - '.$tit.' ('.$titulo.')</b></center></a>
        </div>
        <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
          <thead>
            <tr style="background-color: #66b2e8">
              <th style="width:3%;">COD. DA.</th>
              <th style="width:3%;">COD. UE.</th>
              <th style="width:3%;">COD. PROGRAMA</th>
              <th style="width:10%;">COD. PROYECTO</th>
              <th style="width:3%;">COD. ACTIVIDAD</th>
              <th style="width:35%;">'.$titulo.'</th>
              <th style="width:3%;">COD. UNIDAD RESP.</th>
              <th style="width:15%;">UNIDAD RESPONSABLE</th>
              <th style="width:3%;">COD. ACP.</th>
              <th style="width:3%;">COD. OPE.</th>
              <th style="width:3%;">COD. ACT.</th>
              <th style="width:3%;">PRIORIDAD</th>
              <th style="width:25%;">DESCRIPCION ACTIVIDAD</th>
              <th style="width:15%;">RESULTADO</th>
              <th style="width:15%;">INDICADOR</th>
              <th style="width:5%;">RESPONSABLE</th>
              <th style="width:5%;">META</th>
              <th style="width:15%;">MEDIO DE VERIFICACIÓN</th>
              <th style="width:4%;">P. ENE.</th>
              <th style="width:4%;">P. FEB.</th>
              <th style="width:4%;">P. MAR.</th>
              <th style="width:4%;">P. ABR.</th>
              <th style="width:4%;">P. MAY.</th>
              <th style="width:4%;">P. JUN.</th>
              <th style="width:4%;">P. JUL.</th>
              <th style="width:4%;">P. AGOS.</th>
              <th style="width:4%;">P. SEPT.</th>
              <th style="width:4%;">P. OCT.</th>
              <th style="width:4%;">P. NOV.</th>
              <th style="width:4%;">P. DIC.</th>
             
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;
          foreach ($operaciones as $row){
            //$monto=$this->model_producto->monto_insumoproducto($row['prod_id']);
            $programado=$this->model_producto->producto_programado($row['prod_id'],$this->gestion);
            //$ejec=$this->model_producto->producto_ejecutado($row['prod_id'],$this->gestion);
              
            /*$ptto=0;
            if(count($monto)!=0){
              $ptto=$monto[0]['total'];
            }*/

            $priori='';
            if($row['prod_priori']==1){
              $priori='<b>SI</b>';
            }

            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.strtoupper($row['dep_cod']).'</td>';
                $tabla.='<td>'.strtoupper($row['dist_cod']).'</td>';
                $tabla.='<td>'.$row['prog'].'</td>';
                $tabla.='<td>';
                if($tp_id==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.=''.$row['proy'].'';
                }
                $tabla.='</td>';
                $tabla.='<td>'.$row['act'].'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.$row['proy_nombre'].'';
                  }
                  else{
                    $tabla.=''.$row['tipo'].' '.$row['actividad'].' - '.$row['abrev'].'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['serv_cod'].'</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                $tabla.='<td>'.$row['og_codigo'].'</td>';
                $tabla.='<td>'.$row['or_codigo'].'</td>';
                $tabla.='<td>'.$row['prod_cod'].'</td>';
                $tabla.='<td align=center><font size=5>'.$priori.'</font></td>';
                $tabla.='<td>'.$row['prod_producto'].'</td>';
                $tabla.='<td>'.$row['prod_resultado'].'</td>';
                $tabla.='<td>'.$row['prod_indicador'].'</td>';
                $tabla.='<td>'.$row['prod_unidades'].'</td>';
                $tabla.='<td>'.$row['prod_meta'].'</td>';
                $tabla.='<td>'.$row['prod_fuente_verificacion'].'</td>';
                if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['enero'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['febrero'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['marzo'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['abril'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['mayo'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['junio'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['julio'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['agosto'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['septiembre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['octubre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['noviembre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['diciembre'],2).'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace">0</td>';
                      }
                    }

               /* $tabla.='<td style="width: 5%; text-align: right;">'.round($ptto,2).'</td>';

                if(count($ejec)!=0){
                  $tabla.='
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['enero'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['febrero'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['marzo'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['abril'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['mayo'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['junio'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['julio'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['agosto'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['septiembre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['octubre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['noviembre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['diciembre'],2).'</b></td>';
                }
                else{
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td bgcolor="#d2f5f0">0</td>';
                  }
                }*/

            $tabla.='</tr>';
          }
          $tabla.='
          </tbody>
        </table>';
      }

      return $tabla;
    }

     /*-----EXCEL LISTA DE ACTIVIDADES (REGIONAL-DISTRITAL) ----*/
   public function lista_operaciones_regional_distrital($formularioN4,$titulo,$tip_rep){
        $tabla='';
        $tabla .='
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
          </style>';

        $tabla.='
          <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                <tr class="modo1">
                  <td colspan=45 align=center style="height:50px;"><b> FORMULARIO N° 4 - '.strtoupper($titulo).'</b></td>
                </tr>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%; height:50px;background-color: #eceaea;"></th>
                  <th style="width:3%; height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">GASTO CORRIENTE / INVERSION</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UNI. RESP.</th>
                  <th style="width:15%;background-color: #eceaea;">UNIDAD RESPONSABLE</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACP.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:3%;background-color: #eceaea;">PRIORIDAD</th>
                  <th style="width:25%;background-color: #eceaea;">DESCRIPCION ACTIVIDAD</th>
                  <th style="width:15%;background-color: #eceaea;">RESULTADO</th>
                  <th style="width:15%;background-color: #eceaea;">INDICADOR</th>
                  <th style="width:5%;background-color: #eceaea;">LINEA BASE</th>
                  <th style="width:5%;background-color: #eceaea;">META</th>
                  <th style="width:15%;background-color: #eceaea;">MEDIO DE VERIFICACION</th>
                  <th style="width:4%;background-color: #eceaea;">P. ENE.</th>
                  <th style="width:4%;background-color: #eceaea;">P. FEB.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. ABR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAY.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUN.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUL.</th>
                  <th style="width:4%;background-color: #eceaea;">P. AGOS.</th>
                  <th style="width:4%;background-color: #eceaea;">P. SEPT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. OCT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. NOV.</th>
                  <th style="width:4%;background-color: #eceaea;">P. DIC.</th>
                  <th style="width:6%;background-color: #eceaea;"></th>
                  <th style="width:4%;background-color: #eceaea;">E. ENE.</th>
                  <th style="width:4%;background-color: #eceaea;">E. FEB.</th>
                  <th style="width:4%;background-color: #eceaea;">E. MAR.</th>
                  <th style="width:4%;background-color: #eceaea;">E. ABR.</th>
                  <th style="width:4%;background-color: #eceaea;">E. MAY.</th>
                  <th style="width:4%;background-color: #eceaea;">E. JUN.</th>
                  <th style="width:4%;background-color: #eceaea;">E. JUL.</th>
                  <th style="width:4%;background-color: #eceaea;">E. AGOS.</th>
                  <th style="width:4%;background-color: #eceaea;">E. SEPT.</th>
                  <th style="width:4%;background-color: #eceaea;">E. OCT.</th>
                  <th style="width:4%;background-color: #eceaea;">E. NOV.</th>
                  <th style="width:4%;background-color: #eceaea;">E. DIC.</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($formularioN4 as $row){
              $ejec=$this->model_producto->producto_ejecutado($row['prod_id'],$this->gestion);

              $priori='';
              if($row['prod_priori']==1){
                $priori='<b>SI</b>';
              }

                $nro++;
                $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['prod_id'].'</td>';
                $tabla.='<td style="height:50px;">\''.strtoupper($row['dep_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['dist_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['prog']).'\'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.='\''.strtoupper($row['proy']).'\'';
                }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['act']).'\'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=''.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'';
                }
                else{
                  $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['actividad'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['serv_cod']).'\'</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                $tabla.='<td align=center><font size=4>'.$row['og_codigo'].'</font></td>';
                $tabla.='<td align=center><font size=4>'.$row['or_codigo'].'</font></td>';
                $tabla.='<td align=center><font size=4>'.$row['prod_cod'].'</font></td>';
                $tabla.='<td align=center><font size=5>'.$priori.'</font></td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_producto'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_resultado'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_indicador'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.round($row['prod_linea_base'],2).'</td>';
                $tabla.='<td>'.round($row['prod_meta'],2).'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_fuente_verificacion'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['enero'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['febrero'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['marzo'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['abril'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mayo'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['junio'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['julio'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['agosto'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['septiembre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['octubre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['noviembre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['diciembre'],2).'</td>';
                $tabla.='<td style="width: 5%; text-align: right;"></td>';

                if(count($ejec)!=0){
                  $tabla.='
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['enero'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['febrero'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['marzo'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['abril'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['mayo'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['junio'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['julio'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['agosto'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['septiembre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['octubre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['noviembre'],2).'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.round($ejec[0]['diciembre'],2).'</b></td>';
                }
                else{
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td bgcolor="#d2f5f0">0.00</td>';
                  }
                }

            $tabla.='</tr>';
            }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }
    ////========================================= END CONSOLIDADO FORMULARIO N 4

    /////========================================== CONSOLIDADO FORMULARIO N5 
    /*-- REPORTE 3 (CONSOLIDADO FORMULARIO N° 5) 2023 relacion directa --*/
    public function genera_consolidado_form5_regional_distrital($titulo_reporte,$requerimientos,$dep_id,$dist_id,$tp_id){
      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';
        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

        $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/admin/dashboard" class="btn btn-default" title="VOLVER ATRAS"><img src="'.base_url().'assets/Iconos/book_previous.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;SALIR</a>&nbsp;&nbsp;
          <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;DESCARGAR CONSOLIDADO FORM. N° 5</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div class="table-toolbar mb-3">
    <button class="btn btn-primary" onclick="imprimirFiltrados()">
        <i class="fas fa-print"></i> Imprimir Registros Filtrados
    </button>
    <small class="text-muted ml-2">Mostrando '.count($requerimientos).' registros totales</small>
</div>


<BR>
        <div class="alert alert-warning">
          <a href="#" class="alert-link" align=center><center><b>CONSOLIDADO FORMULARIO N° 5 '.$this->gestion.' - '.$titulo_reporte.' ('.$titulo.')</b></center></a>
        </div>
          <table id="datatable_fixed_column" class="table table-bordered" width="100%">
            <thead>
                  <tr>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="REGIONAL"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COD. UNIDAD EJECUTORA"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COD. PROG"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COD. PROY."/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COD. ACT."/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="GASTO CORRIENTE / INVERSION"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COD. ACT."/>
                    </th>
                    <th></th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="PARTIDA"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="REQUERIMIENTO"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="UNIDAD MEDIDA"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="CANTIDAD"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="PRECIO UNITARIO"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="COSTO TOTAL"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="PPTO CERTIFICADO"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="OBSERVACION"/>
                    </th>
                    <th class="hasinput">
                        <input type="text" class="form-control" placeholder="TIPO DE REGISTRO"/>
                    </th>
                  </tr>                          
                  <tr style="background-color: #66b2e8">
                    <th style="width:4%;">COD. DA.</th>
                    <th style="width:4%;">COD. UE.</th>
                    <th style="width:4%;">COD. PROG.</th>
                    <th style="width:4%;">COD. PROY.</th>
                    <th style="width:4%;">COD. ACT.</th>
                    <th style="width:10%;">'.$titulo.'</th>
                    <th style="width:5%;">COD. ACT.</th>
                    <th style="width:1%;"></th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:20%;">REQUERIMIENTO</th>
                    <th style="width:5%;">UNIDAD DE MEDIDA</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">PRECIO</th>
                    <th style="width:5%;">COSTO TOTAL</th>
                    <th style="width:5%;">PPTO. CERTIFICADO</th>
                    <th style="width:10%;">OBSERVACI&Oacute;N</th>
                    <th style="width:10%;">TIPO MOD.</th>
                  </tr>
            </thead>
              <tbody id="bdi">';
                $nro=0;
                foreach ($requerimientos as $row){
                  $tipo_modificacion='<b style="color:blue">REG. POA</b>';
                  if($row['ins_tipo_modificacion']==1){
                    $tipo_modificacion='<b style="color:green">REG. REV. POA</b>';
                  }
                  $nro++;
                  $tabla.='<tr>';
                      $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                      $tabla.='<td>'.$row['dist_cod'].'</td>';
                      $tabla.='<td>'.$row['aper_programa'].'</td>';
                      $tabla.='<td>';
                      if($tp_id==1){
                        $tabla.=''.$row['proy_sisin'].'';
                      }
                      else{
                        $tabla.=''.$row['aper_proyecto'].'';
                      }
                      $tabla.='</td>';
                      $tabla.='<td>'.$row['aper_actividad'].'</td>';
                      $tabla.='<td>';
                        if($row['tp_id']==1){
                          $tabla.=''.$row['proy_nombre'].'';
                        }
                        else{
                          $tabla.=''.$row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'].'';
                        }
                      $tabla.='</td>';
                      $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#e4f3dc"><b>'.$row['form4_cod'].'</b></td>';
                      $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#f4f5f3">';
                        if($row['ins_ejec_cpoa']==1){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_getcpoas" class="btn btn-default" name="'.$row['ins_id'].'" onclick="ver_getcertpoa('.$row['ins_id'].');" title="VER MIS CERTIFICACIONES POA - '.$row['ins_id'].'"><img src="'.base_url().'assets/img/ifinal/doc.jpg" WIDTH="35" HEIGHT="35"/></a>';
                        }
                      $tabla.='</td>';
                      $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#f4f5f3"><b>'.$row['par_codigo'].'</b></td>';
                      $tabla.='<td bgcolor="#f4f5f3" title="'.$row['ins_id'].'">'.strtoupper($row['ins_detalle']).'</td>';
                      $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_unidad_medida']).'</td>';
                      $tabla.='<td bgcolor="#f4f5f3" align="right">'.round($row['ins_cant_requerida'],2).'</td>';
                      $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                      $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                      $tabla.='<td style="font-size: 13px;" align="right" bgcolor="#c1f5ee"><b>'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</b></td>';
                      $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_observacion']).'</td>';
                      $tabla.='<td align="center">'.$tipo_modificacion.'</td>';
                  $tabla.='</tr>';
                }
          $tabla.='
          </tbody>
        </table>';

      return $tabla;
    }

 /*----- GENERA EXCEL LISTA DE REQUERIMIENTOS DISTRITAL (2023) ----*/
     public function lista_requerimientos_regional_distrital_excel($requerimientos,$titulo,$tp_id){
        $tabla='';
        $tabla .='
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
          </style>';

        $tabla.='
          <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%;height:50px;background-color: #eceaea;"></th>
                  <th style="width:3%;height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">GASTO CORRIENTE / PROY. INVERSION</th>';
                  if($tp_id==4){
                    $tabla.='
                    <th style="width:3%;background-color: #eceaea;">COD. U.RESP..</th>
                    <th style="width:15%;background-color: #eceaea;">UNIDAD RESPONSABLE</th>';
                  }
                  $tabla.='
                  <th style="width:3%;background-color: #eceaea;">COD. ACP.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:15%;background-color: #eceaea;">DESCRIPCION OPERACION '.$this->gestion.'</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:25%;background-color: #eceaea;">DESCRIPCION ACTIVIDAD</th>
                  <th style="width:15%;background-color: #eceaea;">PARTIDA</th>
                  <th style="width:25%;background-color: #eceaea;">DETALLE REQUERIMIENTO</th>
                  <th style="width:10%;background-color: #eceaea;">UNIDAD DE MEDIDA</th>
                  <th style="width:5%;background-color: #eceaea;">CANTIDAD</th>
                  <th style="width:5%;background-color: #eceaea;">PRECIO</th>
                  <th style="width:15%;background-color: #eceaea;">COSTO TOTAL</th>
                  <th style="width:15%;background-color: #eceaea;">MONTO CERTIFICADO</th>
                  <th style="width:4%;background-color: #eceaea;">P. ENE.</th>
                  <th style="width:4%;background-color: #eceaea;">P. FEB.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. ABR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAY.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUN.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUL.</th>
                  <th style="width:4%;background-color: #eceaea;">P. AGOS.</th>
                  <th style="width:4%;background-color: #eceaea;">P. SEPT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. OCT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. NOV.</th>
                  <th style="width:4%;background-color: #eceaea;">P. DIC.</th>
                  <th style="width:10%;background-color: #eceaea;">OBSERVACION</th>
                  <th style="width:5%;background-color: #eceaea;">TIPO MOD.</th>
                  <th style="width:5%;background-color: #eceaea;">RESPONSABLE POA NACIONAL</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($requerimientos as $row){
            $tipo_modificacion='<b style="color:blue">REG. POA</b>';
            if($row['ins_tipo_modificacion']==1){
              $tipo_modificacion='<b style="color:green">REG. REV. POA</b>';
            }

            $resp='';
            if($row['dep_cod']==1){
              $resp='LIC. JUAN JOSE TOVAR';
            }
            elseif($row['dep_cod']==2){
              $resp='LIC. MARIA CRISTINA LIENDO';
            }
            elseif($row['dep_cod']==3){
              $resp='DR. JUAN CARLOS SOLIZ';
            }
            elseif($row['dep_cod']==4){
              $resp='SR. JESUS RAMOS ANGULO';
            }
            elseif($row['dep_cod']==5){
              $resp='LIC. LUIS RIVAS MICHEL';
            }
            elseif($row['dep_cod']==6){
              $resp='LIC. RITHA VIADURRE';
            }
            elseif($row['dep_cod']==7){
              $resp='DRA. CARMEN MICHEL';
            }
            elseif($row['dep_cod']==8){
              $resp='DR. RAMIRO CARRASCO';
            }
            elseif($row['dep_cod']==9){
              $resp='WILMER MENDOZA';
            }
            elseif($row['dep_cod']==10){
              $resp='GG: JUAN JOSE TOVAR - GAF: WILMER MENDOZA - GSS: DRA. CARMEN MICHEL';
            }



            $prog="'".$row['aper_programa']."'";
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td>'.$row['ins_id'].'</td>';
                $tabla.='<td style="height:70px;"><b>'."'".$row['dep_cod']."'".' - '.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</b></td>';
                $tabla.='<td><b>'."'".$row['dist_cod']."'".' - '.mb_convert_encoding(strtoupper($row['dist_distrital']), 'cp1252', 'UTF-8').'</b></td>';
                $tabla.='<td>'.$prog.'</td>';
                $tabla.='<td>'."'".$row['aper_proyecto']."'".'</td>';
                $tabla.='<td>'."'".$row['aper_actividad']."'".'</td>';
                $tabla.='<td>';
                  if($tp_id==1){
                    $tabla.=''.mb_convert_encoding($row['proyecto'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['actividad'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
              
                  if($tp_id==4){
                    $tabla.='<td>'."'".$row['serv_cod']."'".'</td>';
                    $tabla.='<td>'.$row['tipo_subactividad'].' '.mb_convert_encoding(strtoupper($row['serv_descripcion']), 'cp1252', 'UTF-8').'</td>';
                  }
                
                $tabla.='<td style="font-size: 15px;" bgcolor="#d9f5c9" align=center><b>'.$row['og_codigo'].'</b></td>';
                $tabla.='<td style="font-size: 15px;" bgcolor="#d9f5c9" align=center><b>'.$row['or_codigo'].'</b></td>';
                $tabla.='<td bgcolor="#d9f5c9">'.mb_convert_encoding(strtoupper($row['or_objetivo']), 'cp1252', 'UTF-8').'</td>';

                $tabla.='<td bgcolor="#e4f3dc" align=center><b>'.$row['prod_cod'].'</b></td>';
                $tabla.='<td style="font-family: Arial;" bgcolor="#e4f3dc">'.mb_convert_encoding(strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td style="font-size: 15px;" bgcolor="#f4f5f3" align=center>'.$row['par_codigo'].'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_costo_unitario'],2).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_costo_total'],2).'</td>';
                $tabla.='<td style="font-size: 15px;" align="right" bgcolor="#c1f5ee"><b>'.round($row['ins_monto_certificado'],2).'</b></td>';
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.round($row['mes'.$i],2).'</td>';
                }
                $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td align="center">'.$tipo_modificacion.'</td>';
                $tabla.='<td align="center">'.$resp.'</td>';
                
            $tabla.='</tr>';
          }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }

    public function lista_requerimientos_regional_distrital_excel2($requerimientos,$titulo,$tp_id){
        $tabla='';
        $tabla .='
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
          </style>';

        $tabla.='
          <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%;height:50px;background-color: #eceaea;"></th>
                  <th style="width:3%;height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">GASTO CORRIENTE / PROY. INVERSION</th>';
                  if($tp_id==4){
                    $tabla.='
                    <th style="width:3%;background-color: #eceaea;">COD. U.RESP..</th>
                    <th style="width:15%;background-color: #eceaea;">UNIDAD RESPONSABLE</th>';
                  }
                  $tabla.='
                  <th style="width:3%;background-color: #eceaea;">COD. ACP.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:15%;background-color: #eceaea;">DESCRIPCION OPERACION '.$this->gestion.'</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:25%;background-color: #eceaea;">DESCRIPCION ACTIVIDAD</th>
                  <th style="width:15%;background-color: #eceaea;">PARTIDA</th>
                  <th style="width:25%;background-color: #eceaea;">DETALLE REQUERIMIENTO</th>
                  <th style="width:10%;background-color: #eceaea;">UNIDAD DE MEDIDA</th>
                  <th style="width:5%;background-color: #eceaea;">CANTIDAD</th>
                  <th style="width:5%;background-color: #eceaea;">PRECIO</th>
                  <th style="width:15%;background-color: #eceaea;">COSTO TOTAL</th>
                  <th style="width:15%;background-color: #eceaea;">MONTO CERTIFICADO</th>
                  <th style="width:4%;background-color: #eceaea;">P. ENE.</th>
                  <th style="width:4%;background-color: #eceaea;">P. FEB.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. ABR.</th>
                  <th style="width:4%;background-color: #eceaea;">P. MAY.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUN.</th>
                  <th style="width:4%;background-color: #eceaea;">P. JUL.</th>
                  <th style="width:4%;background-color: #eceaea;">P. AGOS.</th>
                  <th style="width:4%;background-color: #eceaea;">P. SEPT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. OCT.</th>
                  <th style="width:4%;background-color: #eceaea;">P. NOV.</th>
                  <th style="width:4%;background-color: #eceaea;">P. DIC.</th>
                  <th style="width:10%;background-color: #eceaea;">OBSERVACION</th>
                  <th style="width:5%;background-color: #eceaea;">TIPO MOD.</th>
                  <th style="width:5%;background-color: #eceaea;">COD. CERT.POA.</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($requerimientos as $row){
            $ins_certificado=$this->model_certificacion->verif_insumo_certificados($row['ins_id']);
            $tipo_modificacion='<b style="color:blue">REG. POA</b>';
            if($row['ins_tipo_modificacion']==1){
              $tipo_modificacion='<b style="color:green">REG. REV. POA</b>';
            }


            $prog="'".$row['aper_programa']."'";
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td>'.$row['ins_id'].'</td>';
                $tabla.='<td style="height:70px;">'."'".$row['dep_cod']."'".'</td>';
                $tabla.='<td>'."'".$row['dist_cod']."'".'</td>';
                $tabla.='<td>'.$prog.'</td>';
                $tabla.='<td>'."'".$row['aper_proyecto']."'".'</td>';
                $tabla.='<td>'."'".$row['aper_actividad']."'".'</td>';
                $tabla.='<td>';
                  if($tp_id==1){
                    $tabla.=''.mb_convert_encoding($row['proyecto'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['actividad'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
              
                  if($tp_id==4){
                    $tabla.='<td>'."'".$row['serv_cod']."'".'</td>';
                    $tabla.='<td>'.$row['tipo_subactividad'].' '.mb_convert_encoding(strtoupper($row['serv_descripcion']), 'cp1252', 'UTF-8').'</td>';
                  }
                
                $tabla.='<td style="font-size: 15px;" bgcolor="#d9f5c9" align=center><b>'.$row['og_codigo'].'</b></td>';
                $tabla.='<td style="font-size: 15px;" bgcolor="#d9f5c9" align=center><b>'.$row['or_codigo'].'</b></td>';
                $tabla.='<td bgcolor="#d9f5c9">'.mb_convert_encoding(strtoupper($row['or_objetivo']), 'cp1252', 'UTF-8').'</td>';

                $tabla.='<td bgcolor="#e4f3dc" align=center><b>'.$row['prod_cod'].'</b></td>';
                $tabla.='<td style="font-family: Arial;" bgcolor="#e4f3dc">'.mb_convert_encoding(strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td style="font-size: 15px;" bgcolor="#f4f5f3" align=center>'.$row['par_codigo'].'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_costo_unitario'],2).'</td>';
                $tabla.='<td align="right" bgcolor="#f4f5f3">'.round($row['ins_costo_total'],2).'</td>';
                $tabla.='<td style="font-size: 15px;" align="right" bgcolor="#c1f5ee"><b>'.round($row['ins_monto_certificado'],2).'</b></td>';
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.round($row['mes'.$i],2).'</td>';
                }
                $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td align="center">'.$tipo_modificacion.'</td>';
                $tabla.='<td align="center">';
                if(count($ins_certificado)!=0){
                $tabla.='

                  <center>
                    <table>
                      <tr>';
                      foreach ($ins_certificado as $row){
                        $tabla.='
                        <b>'.$row['cpoa_codigo'].'</b><br>';
                      }
                  $tabla.='
                      </tr>
                    </table>
                  </center>';
              }
                $tabla.='</td>';
            $tabla.='</tr>';
          }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }
    /////// ============================================= EN FORMULARIO N 5



    /*-- FORMULARIO 4 LISTA DE CERTIFICACIONES POAS 2022 POR REGIONAL --*/
    public function lista_certificaciones_poa($dep_id,$tp_id){
      $tabla='';
          $certificados = $this->model_certificacion->lista_certificaciones_regional($dep_id,$tp_id,$this->gestion);
          $tabla.='
            <br>
            <div align=right>
              <a href="'.site_url("").'/admin/dashboard" class="btn btn-success" title="VOLVER ATRAS"><img src="'.base_url().'assets/Iconos/book_previous.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;VOLVER ATRAS</a>&nbsp;&nbsp;&nbsp;
            </div>
            <br>
            <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
            <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
            <thead>
              <tr style="background-color: #66b2e8">
                  <th style="width:1%;"></th>
                  <th style="width:15%;">C&Oacute;DIGO</th>
                  <th style="width:5%;">FECHA</th>
                  <th style="width:10%;">UNIDAD EJECUTORA</th>
                  <th style="width:10%;">APERTURA PROGRAM&Aacute;TICA</th>
                  <th style="width:30%;">UNIDAD, ESTABLECIMIENTO, PROYECTO DE INVERSI&Oacute;N</th>
                  <th style="width:15%;">SUBACTIVIDAD / UNIDAD RESPONSABLE</th>';
                  if($tp_id==1){
                    $tabla.='<th style="width:10%;">COMPONENTE</th>';
                  }
                  $tabla.='
                  <th style="width:5%;">GESTI&Oacute;N</th>
                  <th style="width:5%;">VER CERTIFICADO POA</th>
              </tr>
            </thead>
            <tbody id="bdi">';
            $nro=0;
            foreach ($certificados as $row){
              $nro++; $color='';$codigo=$row['cpoa_codigo'];
              if($row['cpoa_estado']==0){
                $color='#fddddd';
                $codigo='<font color=red>SIN CÓDIGO</font>';
              }
              elseif($row['cpoa_ref']){
                $color='#dcf7f3';
              }

              if($row['cpoa_estado']==1){
                $tabla .='<tr bgcolor='.$color.'>';
                  $tabla .='<td title='.$row['cpoa_id'].'>'.$nro.'</td>';
                  $tabla .='<td>'.$codigo.'</td>';
                  $tabla .='<td>'.date('d-m-Y',strtotime($row['cpoa_fecha'])).'</td>';
                  $tabla .='<td>'.strtoupper($row['dist_distrital']).'</td>';
                  $tabla .='<td>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>';
                  $tabla .='<td>';
                    if($row['tp_id']==1){
                      $tabla.=$row['proy_nombre'];
                    }
                    else{
                      $tabla.=$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'];
                    }
                  $tabla .='</td>';
                  $tabla .='<td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
                  if($tp_id==1){
                    $tabla .='<td>'.$row['com_componente'].'</td>';
                  }
                  $tabla .='<td align=center><b>'.$row['cpoa_gestion'].'</b></td>';
                  $tabla .='<td align=center><a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$row['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="40" HEIGHT="40"/></a></td>';
                $tabla .='</tr>';
              }
              
            }
            
            $tabla.='
            </tbody>
        </table>';

      return $tabla;
    }



    /*--- LISTA DE CERTIFICACIONES POA 2022 ---*/
    public function list_certificacionpoa($proy_id,$tp_id){
      $tabla='';
      $certificacionespoa=$this->model_certificacion->list_certpoa_unidad($proy_id);

      $tabla.='<script src = "'.base_url().'mis_js/programacion/programacion/tablas1.js"></script>';
      $tabla.='
      <hr>
      <table id="dt_basic1" class="table table-bordered" border=0.2 style="width:100%;">
        <thead>
          <tr>
            <th style="width:1%;height:20px">#</th>
            <th style="width:10%;">C&Oacute;DIGO</th>
            <th style="width:5%;">FECHA</th>
            <th style="width:10%;">UNIDAD RESPONSABLE</th>';
            if($tp_id==1){
              $tabla.='<th style="width:10%;">COMPONENTE</th>';
            }
            $tabla.='
            <th style="width:5%;">VER CERTIFICADO POA</th>
          </tr>
        </thead>
         <tbody>';
          $nro=0;
          foreach ($certificacionespoa as $row){
            $nro++; $color='';$codigo=$row['cpoa_codigo'];
            if($row['cpoa_estado']==0){
              $color='#fddddd';
              $codigo='<font color=red>SIN CÓDIGO</font>';
            }

            $tabla .='<tr>';
              $tabla .='<td title='.$row['cpoa_id'].' align="center">'.$nro.'</td>';
              $tabla .='<td><b>'.$codigo.'</b></td>';
              $tabla .='<td>'.date('d-m-Y',strtotime($row['cpoa_fecha'])).'</td>';
              $tabla .='<td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
              if($tp_id==1){
                $tabla .='<td>'.$row['com_componente'].'</td>';
              }
              $tabla .='<td align=center><a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$row['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="30" HEIGHT="30"/></a></td>';
            $tabla .='</tr>';
          }
          
          $tabla.='
          </tbody>
        </table>';

      return $tabla;
    }



    /*--- LISTA DE CERTIFICACIONES POA 2022 POR COMPONENTE ---*/
    public function list_certificacionpoa_componente($com_id){
      $tabla='';
      $certificacionespoa=$this->model_certificacion->list_certpoa_componente($com_id);

      $tabla.='<script src = "'.base_url().'mis_js/programacion/programacion/tablas1.js"></script>';
      $tabla.='
      <hr>
      <table id="dt_basic1" class="table table-bordered" border=0.2 style="width:100%;">
        <thead>
          <tr>
            <th style="width:1%;height:20px">#</th>
            <th style="width:10%;">C&Oacute;DIGO</th>
            <th style="width:5%;">FECHA</th>
            <th style="width:10%;">UNIDAD RESPONSABLE</th>
            <th style="width:5%;">VER CERTIFICADO POA</th>
          </tr>
        </thead>
         <tbody>';
          $nro=0;
          foreach ($certificacionespoa as $row){
            $nro++; $color='';$codigo=$row['cpoa_codigo'];
            if($row['cpoa_estado']==0){
              $color='#fddddd';
              $codigo='<font color=red>SIN CÓDIGO</font>';
            }

            $tabla .='<tr>';
              $tabla .='<td title='.$row['cpoa_id'].' align="center">'.$nro.'</td>';
              $tabla .='<td><b>'.$codigo.'</b></td>';
              $tabla .='<td>'.date('d-m-Y',strtotime($row['cpoa_fecha'])).'</td>';
              $tabla .='<td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
              $tabla .='<td align=center><a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$row['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="30" HEIGHT="30"/></a></td>';
            $tabla .='</tr>';
          }
          
          $tabla.='
          </tbody>
        </table>';

      return $tabla;
    }


    /*--- REPORTE EVALUACION POA POR UNIDAD 2022 ---*/
    public function detalle_evaluacionpoa($evaluacion,$proy_id){
        $tabla='';
        //$evaluacion=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
       // $calificacion=$evaluacion[5][$this->tmes];
        //$tabla_evaluacion=$this->tabla_acumulada_evaluacion_unidad($evaluacion,2,1); /// Tabla que muestra el acumulado por trimestres Regresion
     //   $unidades_responsables=$this->mis_servicios(1,$proy_id); /// Lista de Subactividades

        $tabla.='
        <hr>
        '.$this->calificacion_eficacia($evaluacion[5][$this->tmes]).'
        <hr>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid">
            <b>CUADRO DE CUMPLIMIENTO POA</b>
            <div class="well">
                <center><div id="parametro_efi" style="width: 750px; height: 390px; margin: 0 auto"></div></center>
            </div>
            '.$this->tabla_acumulada_evaluacion_unidad($evaluacion,2,1).'
          </section>
        </article>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid">
            <b>CUADRO DE CUMPLIMIENTO POR UNIDAD RESPONSABLE</b>
            '.$this->mis_servicios(1,$proy_id).'
          </section>
        </article>';

      //  $tabla=$unidades_responsables;


        return $tabla;
    }


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
        if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde
      }

      $tabla.='<h4 class="alert alert-'.$tp.'" style="font-family: Arial;" align="center"><b>'.$titulo.'</b></h4>';

      return $tabla;
    }


/*------ TABLA ACUMULADA EVALUACIÓN 2020 -------*/
    public function tabla_acumulada_evaluacion_unidad($regresion,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit[3]='<b>NRO. ACT. CUMPLIDAS</b>';
      $tit[4]='<b>NRO. ACT. NO CUMPLIDAS</b>';
      $tit[5]='<b>% CUMPLIDOS</b>';
      $tit[6]='<b>% NO CUMPLIDOS</b>';

      $tit_total[2]='<b>NRO. ACT. PROGRAMADAS</b>';
      $tit_total[3]='<b>NRO. ACT. CUMPLIDOS</b>';
      $tit_total[4]='<b>% ACT. PROGRAMADOS</b>';
      $tit_total[5]='<b>% ACT. CUMPLIDOS</b>';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

        if($tp_graf==2){ /// Regresion Acumulado al Trimestre
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
    }


    /*--------- Mis Servicios -------------*/
    public function mis_servicios($tp_rep,$proy_id){
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); 
      $componentes=$this->model_componente->proyecto_componente($proy_id);           
      $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $det='';
      } 
      else{ /// Impresion
        $tab='border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align=center';
        $det='
        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.- DETALLE (%) DE CUMPLIMIENTO DE UNIDADES DEPENDIENTES</b>
        </div>';
      }

      $tabla.='
        '.$det.'
        <table '.$tab.'>
          <thead>
          <tr align=center bgcolor=#f4f4f4>
            <th style="width:3%;height:2%;">#</th>
            <th style="width:20%;">UNIDAD RESPONSABLE</th>
            <th style="width:6%;">TOTAL PROG.</th>
            <th style="width:6%;">TOTAL EVAL.</th>
            <th style="width:6%;">TOTAL CUMP.</th>
            <th style="width:6%;">EN PROCESO</th>
            <th style="width:6%;">NO CUMP.</th>
            <th style="width:6%;">% CUMP.</th>
            <th style="width:6%;">% NO CUMP.</th>
            <th style="width:12%;">REP. EVAL. POA</th>
          </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($componentes as $rowc){
            $eval=$this->tabla_regresion_lineal_servicio($rowc['com_id']);
            $nro++;
            $tabla.='<tr>';
              $tabla.='<td style="height:2%;" align=center>'.$nro.'</td>';
              $tabla.='<td>'.$rowc['com_componente'].'</td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[3][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[7][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.($eval[2][$this->tmes]-($eval[7][$this->tmes]+$eval[3][$this->tmes])).'</b></td>';
              if($tp_rep==1){
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eval[5][$this->tmes].'%</b></button></td>';
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$eval[6][$this->tmes].'%</b></button></td>';
              }
              else{
                $tabla.='<td align=right style="font-size: 6px;"><b>'.$eval[5][$this->tmes].'%</b></td>';
                $tabla.='<td align=right style="font-size: 6px;"><b>'.$eval[6][$this->tmes].'%</b></td>';
              }
              $tabla.='
              <td style="height:12%;" align=center>
                <div class="btn-group">
                  <a class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_cascade.png" WIDTH="25" HEIGHT="25"/></a>
                  <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">';
                    for ($i=1; $i <$this->tmes; $i++) { 
                      $trimestre=$this->model_evaluacion->get_trimestre($i); /// Datos del Trimestre
                      $tabla.='
                      <li>
                        <a href="javascript:abreVentana(\''.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$rowc['com_id'].'/'.$i.'\');" >'.$trimestre[0]['trm_descripcion'].'</a>
                      </li>';
                    }
                  
                  $tabla.='
                  </ul>
                </div>
              </td>';
            $tabla.='</tr>';
          }
        $tabla.='
          </tbody>
        </table>';
      return $tabla;
    }


    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_unidad($proy_id){
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
        $valor=$this->obtiene_datos_evaluacíon($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon($proy_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
        if($tr[2][$i]!=0){
          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 -------*/
    public function obtiene_datos_evaluacíon($proy_id,$trimestre,$tipo_evaluacion){
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



 /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_servicio($com_id){
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
        $valor=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
        if($tr[2][$i]!=0){
          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
        
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 - SERVICIO -------*/
    public function obtiene_datos_evaluacíon_servicio($com_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evaluacion->nro_operaciones_programadas($com_id,$i);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }


    /////========================================

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

  /*=====================================================================*/
    public function get_mes($mes_id){
      $mes[1]='ENERO';
      $mes[2]='FEBRERO';
      $mes[3]='MARZO';
      $mes[4]='ABRIL';
      $mes[5]='MAYO';
      $mes[6]='JUNIO';
      $mes[7]='JULIO';
      $mes[8]='AGOSTO';
      $mes[9]='SEPTIEMBRE';
      $mes[10]='OCTUBRE';
      $mes[11]='NOVIEMBRE';
      $mes[12]='DICIEMBRE';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }


        /// ---- STYLE -----
    public function style(){
      $tabla='';

      $tabla.='   
      <style>
        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
        }
        th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
        }
            #mdialTamanio{
            width: 80% !important;
        }
        #modificacion{
          width: 80% !important;
        }
        #programacion{
          width: 50% !important;
        }
        #certificacion{
          width: 40% !important;
        }
        #evaluacion{
          width: 85% !important;
        }
          input[type="checkbox"] {
          display:inline-block;
          width:25px;
          height:25px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
    </style>';

      return $tabla;
    }
}
?>