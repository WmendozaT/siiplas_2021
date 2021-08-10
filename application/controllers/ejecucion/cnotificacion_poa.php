<?php
class Cnotificacion_poa extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->verif_mes_gestion();
        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*------- TIPO DE RESPONSABLE ----*/
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


    /*----- Lista de Poas Aprobados -----*/
    public function list_poas_aprobados(){
      $data['menu']=$this->menu(4); //// genera menu
      $data['proyectos']='No Disponible';
      $data['operaciones']='No Disponible';
      
      $data['proyectos']=$this->list_pinversion(4); // Aprobados
      $data['operaciones']=$this->list_unidades_es(4); // Aprobados
      
      $this->load->view('admin/notificacion_poa/list_poa_aprobados',$data);
    }


 /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_unidades_es($proy_estado){
      $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
      $tabla='';
      
      $tabla.='
      <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
        <thead>
          <tr style="height:65px;">
            <th style="width:1%;" bgcolor="#474544">#</th>
            <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
            <th style="width:5%;" bgcolor="#474544" title="LISTA DE CITES GENERADOS"></th>
            <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
            <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">UNIDAD / ESTABLECIMIENTO DE SALUD</th>
            <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
            <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
            <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
            <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
            <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
            <th style="width:5%;" bgcolor="#474544" title="ESTADO"></th>
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
                <tr style="height:41px;" bgcolor="'.$color.'">
                  <td align=center><b>'.$nro.'</b></td>
                  <td align=center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' '.strtoupper($row['abrev']).'">MIS SERVICIOS</a></td>
                  <td align=center><a href="javascript:abreVentana(\''.site_url("").'/ejec/ver_notificacion_unidad/'.$row['proy_id'].'\');" class="btn btn-default" name="'.$row['proy_id'].'"><i class="fa fa-cog"></i> GENERAR NOTIFICACI&Oacute;N</a></td>
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
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:5%;" bgcolor="#474544" title="LISTA DE CITES GENERADOS"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#474544" title="FASE - ETAPA">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='<tr>';
               $tabla .= '
                    <td><center>'.$nro.'</center></td>
                    <td align=center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">MIS COMPONENTES</a></td>
                    <td align=center><a href="javascript:abreVentana(\''.site_url("").'/ejec/ver_notificacion_unidad/'.$row['proy_id'].'\');" class="btn btn-default" name="'.$row['proy_id'].'"><i class="fa fa-cog"></i> GENERAR NOTIFICACI&Oacute;N</a></td>
                    <td><center>'.$row['aper_programa'].''.$row['proy_sisin'].'000</center></td>
                    <td>'.$row['proy_nombre'].'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>
                    <td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>
                    <td>'.strtoupper($row['pfec_descripcion']).'</td>
                  </tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      
      return $tabla;
    }


    /*-------- MIS SERVICIOS -------*/
    public function get_servicios(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO

        $tabla=$this->mis_servicios($proy_id);
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ SERVICIOS POA -----*/
    public function mis_servicios($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th bgcolor="#1c7368">NRO.</th>
                    <th bgcolor="#1c7368">SERVICIO / COMPONENTE </th>
                    <th bgcolor="#1c7368">PONDERACI&Oacute;N</th>
                    <th bgcolor="#1c7368"></th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nroc=0; $nro_ppto=0;
                    $procesos=$this->model_componente->proyecto_componente($proy_id);
                    foreach($procesos as $pr){
                      if(count($this->model_notificacion->list_requerimiento_mes($pr['com_id'],$this->verif_mes[1]))!=0){
                        $nroc++;
                        $tabla.=
                        '<tr>
                          <td>'.$nroc.'</td>
                          <td>'.$pr['com_componente'].'</td>
                          <td align=center>'.round($pr['com_ponderacion'],2).'%</td>
                          <td align=center>
                            <a href="javascript:abreVentana(\''.site_url("").'/ejec/ver_notificacion/'.$pr['com_id'].'\');" class="btn btn-default"><i class="fa fa-cog"></i> GENERAR NOTIFICACI&Oacute;N</a>
                          </td>
                        </tr>';
                      }
                    }
                  $tabla.='
                  </tbody>
                </table>';

      return $tabla;
    }

    /*-------- LISTA DE REQUERIMIENTOS NOTIFICADOS - SERVICIO -------*/
    public function lista_requerimientos_notificados_servicio($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion);
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['mes'] = $this->mes_nombre();
      $data['datos_mes'] = $this->verif_mes;
      $data['titulo']=$this->cabecera_reporte($data['fase'][0]['proy_id'],$data['componente']);
      $data['proyecto']=$this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']);
      
      if($data['proyecto'][0]['tp_id']==1){
        $data['titulo_pie']=' MES : '.$data['datos_mes'][2].', COMP.-'.$data['componente'][0]['com_componente'].' - '.$data['proyecto'][0]['proy_nombre'];
      }
      else{
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']);
        $data['titulo_pie']=' MES : '.$data['datos_mes'][2].', SERV.-'.$data['componente'][0]['com_componente'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'];
      }

      $data['monto_pendiente']=$this->montos_pendientes_cpoa_servicio($com_id);
      $data['requerimientos']=$this->mis_notificados_al_mes($com_id);  // Lista de Requerimientos a notificar al siguiente mes

      $this->load->view('admin/notificacion_poa/reporte_notificacion_servicio', $data);
    }


    /*-------- MONTOS PENDIENTES - SERVICIO AL MES -------*/
    public function montos_pendientes_cpoa_servicio($com_id){
      $tabla='';
      if($this->verif_mes[1]!=1){
        if($this->verif_mes[1]==12){
          $tabla.='Mes Diciembre';
        }
        else{
          $tabla.='
            <div style="font-size: 12px;font-family: Arial;height:20px;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              MONTOS PENDIENTES
            </div>';

          $tabla.='
          <table style="width: 100%;">
            <tr>
              <td style="width: 5%;"></td>
              <td style="width: 95%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <thead>
                      <tr class="modo1" align="center">
                        <th style="width:15%;background-color: #1c7368; color: #FFFFFF;height:15px">MES</th>
                        <th style="width:10%;background-color: #1c7368; color: #FFFFFF">NRO DE ITEMS</th>
                        <th style="width:20%;background-color: #1c7368; color: #FFFFFF">MONTO NO CERTIFICADO</th>
                      </tr>
                    </thead>';
                    $monto=0;
                    for ($i=1; $i <$this->verif_mes[1]; $i++) { 
                      $monto_mes=$this->mis_requerimientos_al_mes($com_id,$i);
                      $mes=$this->model_modificacion->get_mes($i);
                      $tabla.='<tr class="modo1">
                                <td style="width: 15%;" style="height:11px;">'.$mes[0]['m_descripcion'].'</td>
                                <td align="right">'.$monto_mes[1].'</td>
                                <td align="right">'.number_format($monto_mes[2], 2, ',', '.').'</td>
                              </tr>';
                      $monto=$monto+$monto_mes[2];
                    }
                $tabla.='
                  <tr>
                    <td colspan=2 align="right" style="height:11px;"><b>TOTAL</b></td>
                    <td align="right"><b>'.number_format($monto, 2, ',', '.').'</b></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>';
        }
      }

      return $tabla;
    }


    /*----- MIS REQUERIMIENTOS - SERVICIO AL MES -----*/
    public function mis_requerimientos_al_mes($com_id,$mes_id){
      $suma_monto=0;$nro=0;
      $lista_insumos=$this->model_notificacion->list_requerimiento_mes($com_id,$mes_id); /// Lista requerimientos al mes
      $vvalor[1]=0;
      $vvalor[2]=0;

      foreach($lista_insumos as $row){
        if(count($this->model_certificacion->get_mes_certificado($row['tins_id']))==0){
          $nro++;
          $suma_monto=$suma_monto+$row['ipm_fis'];
        }
      }

      $vvalor[1]=$nro; /// nro de Item
      $vvalor[2]=$suma_monto; /// suma monto
      return $vvalor;
    }



    /*----- MIS NOTIFICACIONES - SERVICIO AL MES -----*/
    public function mis_notificados_al_mes($com_id){
      $tabla='';
      $requerimientos=$this->model_notificacion->list_requerimiento_mes($com_id,$this->verif_mes[1]);
      $dato_mes = $this->verif_mes;

      $tabla.='<div style="font-size: 12px;font-family: Arial;height:20px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ITEMS PROGRAMADOS A CERTIFICAR
              </div>';
      $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <thead>
                <tr class="modo1" align="center">
                  <th style="width:2%;background-color: #1c7368; color: #FFFFFF">#</th>
                  <th style="width:5%;background-color: #1c7368; color: #FFFFFF">COD. ACT.</th>
                  <th style="width:5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                  <th style="width:56%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                  <th style="width:15%;background-color: #1c7368; color: #FFFFFF">OBSERVACI&Oacute;N</th>
                  <th style="width:7%;background-color: #1c7368; color: #FFFFFF">MONTO PROGRAMADO</th>
                </tr>
                </thead>
                <tbody>';
                $nro=0;$sum=0;
                foreach($requerimientos as $pr){
                  $verif_cert=$this->model_certificacion->get_mes_certificado($pr['tins_id']);
                  if(count($verif_cert)==0){
                    $nro++;
                    $tabla.=
                    '<tr class="modo1">
                      <td style="width: 2%;" style="height:14px;" align="center">'.$nro.'</td>
                      <td style="width: 5%; font-size: 9.5px;" align="center"><b>'.$pr['prod_cod'].'</b></td>
                      <td style="width: 5%; font-size: 9.5px;" align="center"><b>'.$pr['par_codigo'].'</b></td>
                      <td style="width: 56%;">'.$pr['ins_detalle'].'</td>
                      <td style="width: 15%;">'.$pr['ins_observacion'].'</td>
                      <td style="width: 7%;"align="right">'.number_format($pr['ipm_fis'], 2, ',', '.').'</td>
                    </tr>';
                    $sum=$sum+$pr['ipm_fis'];
                  }
                }
      $tabla.=' </tbody>
                  <tr>
                    <td colspan=5 align="right"><b>TOTAL</b></td>
                    <td align="right" style="height:11px;"><b>'.number_format($sum, 2, ',', '.').'</b></td>
                  </tr>
              </table>';

      return $tabla;
    }

    /*------ Cabecera Reporte - Servicio -----*/
    public function cabecera_reporte($proy_id,$componente){
      $tabla='';
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $tabla.='
          <tr>
              <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
          </tr>
          <tr style="font-size: 8pt;">
              <td style="width:17.5%; height: 1.2%"><b>DIR. ADM.</b></td>
              <td style="width:82.5%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-size: 8pt;">
              <td style="width:17.5%; height: 1.2%"><b>UNI. EJEC.</b></td>
              <td style="width:82.5%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>';
      

      if($proyecto[0]['tp_id']==1){
        $tabla.='   
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>PROYECTO</b></td>
            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</td>
          </tr>
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>COMPONENTE</b></td>
            <td style="width:90%;">: '.$componente[0]['com_componente'].'</td>
          </tr>';
      }
      else{
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $tabla.='
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>'.$proyecto[0]['tipo_adm'].'</b></td>
            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>
          </tr>
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>SERVICIO</b></td>
            <td style="width:90%;">: '.$componente[0]['com_componente'].'</td>
          </tr>';
      }

      return $tabla;
    }


    /*-------- LISTA DE REQUERIMIENTOS A NOTIFICAR - UNIDAD ORGANIZACIONAL -------*/
    public function lista_requerimientos_notificados_unidad($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); 
      if(count($data['proyecto'])!=0){
        $data['mes'] = $this->mes_nombre();
        $data['datos_mes'] = $this->verif_mes;
        $data['titulo']=$this->cabecera_reporte_unidad($proy_id);

        if($data['proyecto'][0]['tp_id']==1){
          $data['titulo_pie']=' MES : '.$data['datos_mes'][2].', - '.$data['proyecto'][0]['proy_nombre'];
        }
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $data['titulo_pie']=' MES : '.$data['datos_mes'][2].', - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'];
        }


        $data['monto_pendiente']=$this->montos_pendientes_cpoa_servicio_unidad($proy_id);
        $data['requerimientos']=$this->mis_notificados_al_mes_unidad($proy_id);

        $this->load->view('admin/notificacion_poa/reporte_notificacion_unidad', $data);
      }
      else{
        echo "Error !!!";
      }

    }

    /*-------- MONTOS PENDIENTES - SERVICIO AL MES - UNIDAD ORGANIZACIONAL -------*/
    public function montos_pendientes_cpoa_servicio_unidad($proy_id){
      $tabla='';
      if($this->verif_mes[1]!=1){
        if($this->verif_mes[1]==12){
          $tabla.='Mes Diciembre';
        }
        else{
          $tabla.='
            <div style="font-size: 12px;font-family: Arial;height:20px;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              MONTOS PENDIENTES
            </div>';

          $tabla.='
          <table style="width: 100%;" border=0>
            <tr>
              <td style="width: 3%;"></td>
              <td style="width: 97%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <thead>
                      <tr class="modo1" align="center">
                        <th style="width:15%;background-color: #1c7368; color: #FFFFFF;height:15px">MES</th>
                        <th style="width:10%;background-color: #1c7368; color: #FFFFFF">NRO DE ITEMS</th>
                        <th style="width:20%;background-color: #1c7368; color: #FFFFFF">MONTO NO CERTIFICADO</th>
                      </tr>
                    </thead>';
                    $monto=0;
                    for ($i=1; $i <$this->verif_mes[1]; $i++) { 
                      $monto_mes=$this->mis_requerimientos_al_mes_unidad($proy_id,$i);
                      $mes=$this->model_modificacion->get_mes($i);
                      $tabla.='<tr class="modo1">
                                <td style="width: 15%;" style="height:11px;">'.$mes[0]['m_descripcion'].'</td>
                                <td align="right">'.$monto_mes[1].'</td>
                                <td align="right">'.number_format($monto_mes[2], 2, ',', '.').'</td>
                              </tr>';
                      $monto=$monto+$monto_mes[2];
                    }
                $tabla.='
                  <tr>
                    <td colspan=2 align="right" style="height:11px;"><b>TOTAL</b></td>
                    <td align="right"><b>'.number_format($monto, 2, ',', '.').'</b></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>';
        }
      }

      return $tabla;
    }

    /*----- MIS REQUERIMIENTOS - SERVICIO AL MES -----*/
    public function mis_requerimientos_al_mes_unidad($proy_id,$mes_id){
      $suma_monto=0;$nro=0;
      $lista_insumos=$this->model_notificacion->list_requerimiento_mes_unidad($proy_id,$mes_id); /// Lista requerimientos al mes por unidad
      $vvalor[1]=0;
      $vvalor[2]=0;

      foreach($lista_insumos as $row){
        if(count($this->model_certificacion->get_mes_certificado($row['tins_id']))==0){
          $nro++;
          $suma_monto=$suma_monto+$row['ipm_fis'];
        }
      }

      $vvalor[1]=$nro; /// nro de Item
      $vvalor[2]=$suma_monto; /// suma monto
      return $vvalor;
    }



    /*----- MIS NOTIFICACIONES - UNIDAD ORGANIZACIONAL AL MES -----*/
    public function mis_notificados_al_mes_unidad($proy_id){
      $tabla='';
      $requerimientos=$this->model_notificacion->list_requerimiento_mes_unidad($proy_id,$this->verif_mes[1]);
      $dato_mes = $this->verif_mes;

      $tabla.='<div style="font-size: 12px;font-family: Arial;height:20px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ITEMS PROGRAMADOS A CERTIFICAR
              </div>';
      $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <thead>
                <tr class="modo1" align="center">
                  <th style="width:2%; background-color: #1c7368; color: #FFFFFF">#</th>
                  <th style="width:17%; background-color: #1c7368; color: #FFFFFF">SERVICIO / COMPONENTE</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. ACT.</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                  <th style="width:40%; background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                  <th style="width:15%; background-color: #1c7368; color: #FFFFFF">OBSERVACI&Oacute;N</th>
                  <th style="width:10%; background-color: #1c7368; color: #FFFFFF">MONTO PROGRAMADO</th>
                </tr>
                </thead>
                <tbody>';
                $nro=0;$sum=0;
                foreach($requerimientos as $pr){
                  $verif_cert=$this->model_certificacion->get_mes_certificado($pr['tins_id']);
                  if(count($verif_cert)==0){
                    $nro++;
                    $tabla.=
                    '<tr class="modo1">
                      <td style="width: 2%;" style="height:11px;" align="center">'.$nro.'</td>
                      <td style="width: 17%; font-size: 8px;" align="left"><b>'.$pr['com_componente'].'</b></td>
                      <td style="width: 5%; font-size: 9.5px;" align="center"><b>'.$pr['prod_cod'].'</b></td>
                      <td style="width: 5%; font-size: 9.5px;" align="center"><b>'.$pr['par_codigo'].'</b></td>
                      <td style="width: 40%;">'.$pr['ins_detalle'].'</td>
                      <td style="width: 15%;">'.$pr['ins_observacion'].'</td>
                      <td style="width: 10%;"align="right">'.number_format($pr['ipm_fis'], 2, ',', '.').'</td>
                    </tr>';
                    $sum=$sum+$pr['ipm_fis'];
                  }
                }
      $tabla.=' </tbody>
                  <tr>
                    <td colspan=6 align="right" style="height:11px;"><b>TOTAL</b></td>
                    <td align="right"><b>'.number_format($sum, 2, ',', '.').'</b></td>
                  </tr>
              </table>';

      return $tabla;
    }


    /*------ Cabecera Reporte - unidad -----*/
    public function cabecera_reporte_unidad($proy_id){
      $tabla='';
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $tabla.='
          <tr>
              <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
          </tr>
          <tr style="font-size: 8pt;">
              <td style="width:17.5%; height: 1.2%"><b>DIR. ADM.</b></td>
              <td style="width:82.5%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-size: 8pt;">
              <td style="width:17.5%; height: 1.2%"><b>UNI. EJEC.</b></td>
              <td style="width:82.5%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>';
      

      if($proyecto[0]['tp_id']==1){
        $tabla.='   
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>PROYECTO</b></td>
            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</td>
          </tr>';
      }
      else{
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $tabla.='
          <tr style="font-size: 8pt;">
            <td style="height: 1.2%"><b>'.$proyecto[0]['tipo_adm'].'</b></td>
            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>
          </tr>';
      }

      return $tabla;
    }



    /*=========== FUNCIONES EXTRAS ==========*/
    /*------------ MENU -----------*/
    function menu($mod){
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

    /*--- verifica datos del mes y año ---*/
    public function verif_mes_gestion(){
      $valor=ltrim(date("m"), "0"); // numero mes
      $mes=$this->mes_nombre_completo($valor);
      if($valor!=12){
        $valor=$valor+1;
        $gestion=$this->gestion;
      }
      else{
        $valor=1;
        $gestion=$this->gestion+1;
      }

      $datos[1]=$valor; // numero del mes
      $datos[2]=$mes[$valor]; // mes
      $datos[3]=$gestion; // Gestion

      return $datos;
    }

    /*==================================*/
    
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

    /*------ NOMBRE MES -------*/
    function mes_nombre_completo(){

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

    /*---------- ROLES DE USUARIOS ---------*/
    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }

    function rolfunn($tp_rol){
      $valor=false;
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$tp_rol);
      if(count($data)!=0){
        $valor=true;
      }
      return $valor;
    }
    /*-------------------------------------*/

}