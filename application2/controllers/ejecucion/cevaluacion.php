<?php
class Cevaluacion extends CI_Controller {
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
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('reporte_eval/model_evalregional');
        $this->load->model('mantenimiento/model_configuracion');
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
        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
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

    /*----- Lista de Operaciones Aprobadas ------*/
    public function operaciones_aprobadas(){
      if($this->rol==1){
        $data['menu']=$this->menu(4); //// genera menu
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep']=$this->tp_resp(); 

        if($this->gestion>2020){
          redirect('seg/seguimiento_poa');
        }
        else{ //// Gestion 2020
          $data['proyectos']=$this->list_pinversion(4);
          $data['operacion']=$this->list_unidades_es(4);
          $this->load->view('admin/evaluacion/operaciones/list_poas_aprobados', $data);
        }
      }
      else{
        redirect('admin/dashboard');
      }
    }


    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_unidades_es($proy_estado){
        $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
        $tabla='';
        
        $tabla.='
        <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
          <thead>
            <tr style="height:35px;">
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">UNIDAD / ESTABLECIMIENTO DE SALUD</th>
              <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
              <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
              <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($unidades as $row){
              if($this->gestion==2019){
                $nro++;
                  $tabla.='
                    <tr style="height:45px;">
                      <td align=center><b>'.$nro.'</b></td>
                      <td align=center>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'">
                        <i class="glyphicon glyphicon-list"></i> MIS SERVICIOS</a>
                      </td>
                      <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                      <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td>'.$row['escalon'].'</td>
                      <td>'.$row['nivel'].'</td>
                      <td>'.$row['tipo_adm'].'</td>
                      <td>'.strtoupper($row['dep_departamento']).'</td>
                      <td>'.strtoupper($row['dist_distrital']).'</td>
                    </tr>';
              }
              else{
                if($row['proy_estado']==4){
                  $nro++;
                  $tabla.='
                    <tr style="height:45px;">
                      <td align=center><b>'.$nro.'</b></td>
                      <td align=center>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'">
                        <i class="glyphicon glyphicon-list"></i> MIS SERVICIOS</a>
                      </td>
                      <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                      <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td>'.$row['escalon'].'</td>
                      <td>'.$row['nivel'].'</td>
                      <td>'.$row['tipo_adm'].'</td>
                      <td>'.strtoupper($row['dep_departamento']).'</td>
                      <td>'.strtoupper($row['dist_distrital']).'</td>
                    </tr>';
                }
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
              <th style="width:5%;" bgcolor="#474544" title="MIS COMPONENTES"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:25%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:15%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                <tr style="height:35px;">
                  <td><center>'.$nro.'</center></td>
                  <td align=center>';
                  if($row['pfec_estado']==1){
                    $tabla.='<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                    <i class="glyphicon glyphicon-list"></i> MIS COMPONENTES</a>';
                  }
                  else{
                    $tabla.='FASE NO ACTIVA';
                  }
                  $tabla.='
                    
                </td>
                <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                <td>'.$row['proy_nombre'].'</td>';
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


    /*------ EVALUAR OPERACION (Gasto Corriente-Proyecto de Inversion)2020 ------*/
    public function mi_evaluacion($com_id){
      $componente=$this->model_componente->get_componente($com_id,$this->gestion);
      if(count($componente)!=0){
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); ////// DATOS DEL PROYECTO

        if($proyecto[0]['tp_id']==1){ //// Proyecto de Inversion
          redirect('eval/eval_pinversion/'.$com_id.'');
        }
        else{ //// Gasto Corriente
          redirect('eval/eval_gcorriente/'.$com_id.'');
        }
      }
      else{
        echo "Error !!!";
      }
    }

    /*------ EVALUAR OPERACION 2020 ------*/
    public function evaluar_gastocorriente($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      $data['menu']=$this->menu(4); //// genera menu
      $data['list_trimestre']=$this->list_trimestre();
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
      $titulo=
      '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>'.$data['proyecto'][0]['tipo_adm'].' : </small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].' - '.$data['proyecto'][0]['abrev'].'</h1>
      <h1><small>SERVICIO : </small> '.$data['componente'][0]['serv_descripcion'].'</h1>';

      $tmes=$this->model_evaluacion->trimestre();
      $data['productos']='<div class="alert alert-danger alert-block">NO EXISTE TRIMESTRE SELECCIONADO POR EL ADMINISTRADOR NACIONAL, POR FAVOR CONTACTESE CON EL DPTO. NACIONAL DE PLANIFICACI&Oacute;N</div>';
      if(count($tmes)!=0){
        $this->pondera_poa_operaciones($com_id);
        $this->verif_update_tpmeta($com_id);
        $data['verif_eval_ncum']=$this->model_evaluacion->verif_com_eval($com_id,$this->tmes);
        $data['productos']=$this->lista_productos($data['proyecto'][0]['proy_id'],$com_id); /// Lista de Operaciones
        $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      }

      $data['titulo']=$titulo; /// Titulo de la cabecera
      $data['tr']=($this->tmes*3); /// datos del mes
      
      $data['tabla']=$this->tabla_regresion_lineal_servicio($com_id); /// Tabla para el grafico al trimestre
      $data['tabla_gestion']=$this->tabla_regresion_lineal_servicio_total($com_id); /// Tabla para el grafico Total Gestion

      $data['tabla_pastel']=$this->tabla_acumulada_evaluacion_servicio($data['tabla'],1,1); /// Tabla que muestra el acumulado por trimestres Pastel
      $data['tabla_pastel_todo']=$this->tabla_acumulada_evaluacion_servicio($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
      
      $data['tabla_regresion']=$this->tabla_acumulada_evaluacion_servicio($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
      $data['tabla_regresion_total']=$this->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
      $data['calificacion']=$this->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// calificacion
      $data['ppto_cert']=$this->ejecucion_presupuestaria_acumulado($com_id,1); /// Ejecucion Presupuestaria

      $data['print_tabla']=$this->print_proyectos_servicio($data['componente'],$this->tabla_acumulada_evaluacion_servicio($data['tabla'],2,2),$this->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],3,2),$data['tabla'][5][$this->tmes],$data['calificacion'],$this->ejecucion_presupuestaria_acumulado($com_id,2)); /// imprimir Grafic

      $this->load->view('admin/evaluacion/operaciones/mis_productos', $data);
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

      $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';

      return $tabla;
    }


    /*------- Tabla Ejecucion Presupuestaria --------*/
    public function ejecucion_presupuestaria_acumulado($com_id,$tp_rep){
      $tabla='';
      $monto_total=0;
      $ppto_total=$this->model_evaluacion->suma_ppto_programado_trimestre($com_id);
      if (count($ppto_total)!=0) {
        $monto_total=$ppto_total[0]['total_ppto'];
      }

      $monto_partida=0;
      $suma_partida=$this->model_evaluacion->suma_grupo_partida_programado($com_id,10000);
      if(count($suma_partida)!=0){
        $monto_partida=$suma_partida[0]['suma_partida'];
      }

      $monto_certificado=0;
      $suma_certificado=$this->model_evaluacion->suma_monto_certificado_servicio($com_id);
      if(count($suma_certificado)!=0){
        $monto_certificado=$suma_certificado[0]['ppto_certificado'];
      }

      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:50%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 style="width:100%;"';
      }

      if($this->gestion!=2019){
      $tabla.='
        <div align=center>
        <table '.$tab.'>
          <thead>
          <tr>
            <th style="width:5%;"></th>';
            for ($i=1; $i <=$this->tmes ; $i++) {
              $trimestre=$this->model_evaluacion->get_trimestre($i);
              $tabla.='<th style="width:10%;">'.$trimestre[0]['trm_descripcion'].'</th>';
            }
          
        $tabla.='
            <th style="width:10%;">TOTAL ITEMS</th>
            <th style="width:10%;">MONTO EJECUTADO</th>
            <th style="width:5%;">% EJECUTADO</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td><b>ITEMS CERTIFICADOS</b></td>';
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
      }

      return $tabla;
    }

    /*---------------- Imprime Evaluacion Consolidado Servicio --------------*/
    public function print_proyectos_servicio($componente,$regresion,$regresion_total,$eficacia,$calificacion,$ejecucion_presupuesto){
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      //$dist=$this->model_evalregional->get_dist($dist_id);
      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>
              <table class="page_header" border="0" style="width: 100%;>
              <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">';
                                $tabla.='<td style="height: 2.5%"><b>';
                                  if($proyecto[0]['tp_id']==1){
                                    $tabla.='PROY. INV. ';
                                    $comp='COMPONENTE';
                                  }
                                  else{
                                    $tabla.=''.$proyecto[0]['tipo_adm'].' ';
                                    $comp='SERVICIO';
                                  }
                                  $tabla.='</b></td>';
                                  if($proyecto[0]['tp_id']==1){
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                                  }
                                  else{
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                                  }
                              $tabla.='
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>'.$comp.'</b></td>
                                <td style="width:80%;">: '.strtoupper($componente[0]['com_componente']).'</td>
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4.5pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        '.$calificacion.'
          <table class="change_order_items" border=1 style="width:100%;">
            <tr>
              <td>
                <div id="regresion_impresion" style="width: 500px; height: 220px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td>
                '.$regresion.'
              </td>
            </tr>
            </table>

            <table class="change_order_items" border=1 style="width:100%;">
            <tr>
              <td>
                <div id="regresion_gestion_print" style="width: 500px; height: 220px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td colspan=2>
                '.$regresion_total.'
              </td>
            </tr>
          </table>

          <div class="saltopagina"></div>

          <div class="verde"></div>
          <div class="blanco"></div>

          <table class="page_header" border="0" style="width: 100%;>
              <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">';
                                $tabla.='<td style="height: 2.5%"><b>';
                                  if($proyecto[0]['tp_id']==1){
                                    $tabla.='PROY. INV. ';
                                    $comp='COMPONENTE';
                                  }
                                  else{
                                    $tabla.=''.$proyecto[0]['tipo_adm'].' ';
                                    $comp='SERVICIO';
                                  }
                                  $tabla.='</b></td>';
                                  if($proyecto[0]['tp_id']==1){
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                                  }
                                  else{
                                      $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                                  }
                              $tabla.='
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>'.$comp.'</b></td>
                                <td style="width:80%;">: '.strtoupper($componente[0]['com_componente']).'</td>
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4.5pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        <div align=center style="font-size: 20px;"><b>EJECUCI&Oacute;N PRESUPUESTARIA - '.$this->gestion.'</b></div><br>
        '.$ejecucion_presupuesto.'';
        ?>
          </html>
        <?php
    return $tabla;
    }  
      
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
      }

      for ($i=1; $i <=$this->tmes; $i++) {
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

    /*------ TABLA ACUMULADA EVALUACIÓN 2020 -------*/
    public function tabla_acumulada_evaluacion_servicio($regresion,$tp_graf,$tip_rep){
      $tabla='';
      $tit[2]='TOTAL ACT. PROGRAMADO';
      $tit[3]='TOTAL ACT. CUMPLIDOS';
      $tit[4]='ACT. NO CUMPLIDOS';
      $tit[5]='% ACT. CUMPLIDOS';
      $tit[6]='% ACT. NO CUMPLIDOS';

      $tit_total[2]='TOTAL ACT. PROGRAMADO';
      $tit_total[3]='TOTAL ACT. CUMPLIDOS';
      $tit_total[4]='% ACT. PROGRAMADO';
      $tit_total[5]='% ACT. CUMPLIDO';

      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
      }



      if($tp_graf==1){ // pastel : Programado-Cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center>
                <th>ACTIVIDADES PROGRAMADAS</th>
                <th>ACTIVIDADES EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. NO CUMPLIDAS</th>
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
              <tr>
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
              <tr align=center>
                <th>ACT. PROGRAMADAS</th>
                <th>ACT. EVALUADAS</th>
                <th>ACT. CUMPLIDAS</th>
                <th>ACT. EN PROCESO</th>
                <th>ACT. NO CUMPLIDAS</th>
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


    /*-- ACTUALIZA EVALUACION DE OPERACIONES NO CUMPLIDAS --*/
    function update_evaluacion(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']);
          $productos=$this->model_producto->list_prod($com_id);

          $com=$this->model_evaluacion->get_componente($com_id,$this->gestion);

          foreach($productos as $rowp){
            $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Programado
            $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Ejecutado
            $trimestre=$this->model_evaluacion->get_trimestral_prod($rowp['prod_id'],$this->gestion,$this->tmes);

            $prog_actual=0;
              if(count($trimestre_prog)!=0){
                $prog_actual=$trimestre_prog[0]['trimestre'];
              }
            $ejec_actual=0; 
              if(count($trimestre_ejec)!=0){
                $ejec_actual=$trimestre_ejec[0]['trimestre'];
              }

            if(count($trimestre)==0){
              if($prog_actual!=0){
                if($ejec_actual==0){
                  /*------------ insert Evaluacion -------------*/
                  $data = array(
                    'prod_id' => $rowp['prod_id'],
                    'trm_id' => $this->tmes,
                    'tp_eval' => 3, //// 3: No cumplido, 2: En Proceso, 1 : Cumplido
                    'g_id' => $this->gestion,
                    'prog' => $prog_actual,
                    'fun_id' => $this->fun_id,
                  );
                  $this->db->insert('_productos_trimestral',$data);
                  $tprod_id=$this->db->insert_id();
                  /*--------------------------------------------*/
                }
              }
            }
          }

          if(count($this->model_evaluacion->verif_com_eval($com_id,$this->tmes))==0){
              /*------------ insert componente evaluado -------------*/
              $data = array(
                'com_id' => $com_id,
                'trm_id' => $this->tmes,
              );
              $this->db->insert('eval_comp',$data);
              $tpe_id=$this->db->insert_id();
              /*--------------------------------------------*/
          }

          if(count($this->model_evaluacion->verif_com_eval($com_id,$this->tmes))==1){
            $this->session->set_flashdata('success','SE REALIZO LA ACTUALIZACIÓN DE OPERACIONES NO CUMPLIDAS');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL ACTUALIZAR OPERACIONES NO CUMPLIDAS');
          }

          redirect('eval/eval_productos/'.$com_id.'');
      }
      else{

      }
    }

    /*----- VERIFICA-ACTUALIZA TIPO DE META ------*/
    function verif_update_tpmeta($com_id){
      $productos=$this->model_producto->list_prod($com_id);
      foreach($productos as $row){
        $suma=$this->model_producto->suma_programado_producto($row['prod_id'],$this->gestion);
        
        if(count($suma)!=0){
          if($suma[0]['prog']==1200 & $row['indi_id']==2){
            $update_prod = array(
            'mt_id' => 1
            );
            $this->db->where('prod_id', $row['prod_id']);
            $this->db->update('_productos', $update_prod);
          }
        }
        
      }
    }

    /*----- PONDERACION OPERACIONES ------*/
    function pondera_poa_operaciones($com_id){
      $productos=$this->model_producto->list_prod($com_id);
      $pcion=0;
      if(count($productos)!=0){
        $pcion=(100/count($productos));
        foreach($productos as $row){
          $update_prod = array(
            'prod_ponderacion' => $pcion
          );
          $this->db->where('prod_id', $row['prod_id']);
          $this->db->update('_productos', $update_prod);
        }
      }
    }


    /*---- REFORMULAR DATOS DE EVALUACION (2019) -------*/
    public function reformular_evaluacion($prod_id){
      $data['producto']=$this->model_producto->get_producto_id($prod_id);
      if(count($data['producto'])!=0){
        $data['menu']=$this->menu(4); //// genera menu
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep']=$this->tp_resp();
        $data['componente'] = $this->model_componente->get_componente($data['producto'][0]['com_id'],$this->gestion); ///// DATOS DEL COMPONENTE
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']);

        $data['tmes']=$this->model_evaluacion->trimestre();
        $data['tr']=($this->tmes*3);

        $data['reform']=$this->vista_reformular($prod_id);
        $this->load->view('admin/evaluacion/operaciones/reformular_evaluacion',$data);
      }
      else{
        redirect('eval/mis_operaciones');
      }
    }

    /*---- VISTA REFORMULAR DATOS DE EVALUACION (2019) -------*/
    function vista_reformular($prod_id){
      $tabla ='';
      $temp=$this->temporalizacion_productos($prod_id);
      
      for ($i=1; $i <=4 ; $i++) {
        $ev=$this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i); /// Datos Evaluacion Trimestral
        $tmes=$this->model_evaluacion->get_trimestre($i); /// Datos de Trimestre
        $tabla .=' 
          <div class="col-sm-3">
            <div class="well">';
              if (count($ev)!=0) {
                $tabla .='<div class="alert alert-success" align="center">'.$tmes[0]['trm_descripcion'].' EVALUADO </div>';
              }
              else{
                $tabla .='<div class="alert alert-danger" align="center">'.$tmes[0]['trm_descripcion'].' NO EVALUADO </div>';
              }
              
                $verif_prog_eval=$this->model_evaluacion->ejecutado_trimestral_productos($i,$prod_id); /// Datos Temporalidad Evaluacion
                if($i==1){
                  $tabla.='
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"><font color="#fff">ENE.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">FEB.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">MAR.</font></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>PROG.</th>
                        <td>'.$temp[1][1].'</td>
                        <td>'.$temp[1][2].'</td>
                        <td>'.$temp[1][3].'</td>
                        <td></td>
                      </tr>
                      <tr>
                        <th>EVAL.</th>
                        <td>'.$temp[4][1].'</td>
                        <td>'.$temp[4][2].'</td>
                        <td>'.$temp[4][3].'</td>
                        <td align=center>';
                        if(count($verif_prog_eval)!=0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR EVALUACIÓN PRIMER TRIMESTRE" name="'.$prod_id.'" id="1"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="25" HEIGHT="25"/></a>';
                        }
                        $tabla.='
                        </td>
                      </tr>
                    </tbody>
                  </table><hr>';
                }
                elseif ($i==2) {
                  $tabla.='
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"><font color="#fff">ABR.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">MAY.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">JUN.</font></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>PROG.</th>
                        <td>'.$temp[1][4].'</td>
                        <td>'.$temp[1][5].'</td>
                        <td>'.$temp[1][6].'</td>
                        <td></td>
                      </tr>
                      <tr>
                        <th>EVAL.</th>
                        <td>'.$temp[4][4].'</td>
                        <td>'.$temp[4][5].'</td>
                        <td>'.$temp[4][6].'</td>
                        <td align=center>';
                        if(count($verif_prog_eval)!=0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR EVALUACIÓN PRIMER TRIMESTRE" name="'.$prod_id.'" id="1"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="25" HEIGHT="25"/></a>';
                        }
                        $tabla.='
                        </td>
                      </tr>
                    </tbody>
                  </table><hr>';
                }
                elseif ($i==3) {
                  $tabla.='
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"><font color="#fff">JUL.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">AGO.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">SEP.</font></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>PROG.</th>
                        <td>'.$temp[1][7].'</td>
                        <td>'.$temp[1][8].'</td>
                        <td>'.$temp[1][9].'</td>
                        <td></td>
                      </tr>
                      <tr>
                        <th>EVAL.</th>
                        <td>'.$temp[4][7].'</td>
                        <td>'.$temp[4][8].'</td>
                        <td>'.$temp[4][9].'</td>
                        <td align=center>';
                        if(count($verif_prog_eval)!=0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR EVALUACIÓN PRIMER TRIMESTRE" name="'.$prod_id.'" id="1"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="25" HEIGHT="25"/></a>';
                        }
                        $tabla.='
                        </td>
                      </tr>
                    </tbody>
                  </table><hr>';
                }
                elseif ($i==4) {
                  $tabla.='
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"><font color="#fff">OCT.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">NOV.</font></th>
                        <th bgcolor="#1c7368"><font color="#fff">DIC.</font></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>PROG.</th>
                        <td>'.$temp[1][10].'</td>
                        <td>'.$temp[1][11].'</td>
                        <td>'.$temp[1][12].'</td>
                        <td></td>
                      </tr>
                      <tr>
                        <th>EVAL.</th>
                        <td>'.$temp[4][10].'</td>
                        <td>'.$temp[4][11].'</td>
                        <td>'.$temp[4][12].'</td>
                        <td align=center>';
                        if(count($verif_prog_eval)!=0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR EVALUACIÓN PRIMER TRIMESTRE" name="'.$prod_id.'" id="1"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="25" HEIGHT="25"/></a>';
                        }
                        $tabla.='
                        </td>
                      </tr>
                    </tbody>
                  </table><hr>';
                }

                foreach($ev as $row){

                  if($row['tp_eval']==1){

                    $tabla.='<font color="#8dbd76"><b>EVALUACI&Oacute;N : CUMPLIDO</b></font>';
                    $tabla .='<table class="table table-bordered" border="1">
                              <tr bgcolor="#d6d5d5">
                                <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                <td>'.$row['tmed_verif'].'</td>
                              </tr>
                              </table>';
                  }
                  elseif ($row['tp_eval']==2) {
                    $tabla.='<font color="#ece5a2"><b>EVALUACI&Oacute;N : EN PROCESO</b></font>';
                    $tabla .='<table class="table table-bordered" border="1">
                              <tr bgcolor="#d6d5d5">
                                <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                <td>'.$row['tmed_verif'].'</td>
                              </tr>
                              <tr bgcolor="#d6d5d5">
                                <td><b>PROBLEMAS PRESENTADOS</b></td>
                                <td>'.$row['tprob'].'</td>
                              </tr>
                              <tr bgcolor="#d6d5d5">
                                <td><b>ACCIONES REALIZADAS</b></td>
                                <td>'.$row['tacciones'].'</td>
                              </tr>
                              </table>';
                  }
                  elseif ($row['tp_eval']==3) {
                    $tabla.='<font color="#d24d4d"><b>EVALUACI&Oacute;N : NO CUMPLIDO</b></font>';
                    $tabla .='<table class="table table-bordered" border="1">
                              <tr bgcolor="#d6d5d5">
                                <td><b>PROBLEMAS PRESENTADOS</b></td>
                                <td>'.$row['tprob'].'</td>
                              </tr>
                              <tr bgcolor="#d6d5d5">
                                <td><b>ACCIONES REALIZADAS</b></td>
                                <td>'.$row['tacciones'].'</td>
                              </tr>
                              </table>';
                  }

                }
            $tabla .='
            
            </div>
          </div>';     
      }
      return $tabla;        
    }

    /*------- ELIMINAR DATOS DE EVALUACION -------*/
    public function eliminar_evaluacion(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']); /// Producto id
        $tr = $this->security->xss_clean($post['tr']); /// Trimestre

        $mes_i=0;$mes_f=0;
        if($tr==1){
          $mes_i=1;$mes_f=3;
        }
        elseif ($tr==2) {
          $mes_i=4;$mes_f=6;
        }
        elseif ($tr==3) {
          $mes_i=7;$mes_f=9;
        }
        elseif ($tr==4) {
          $mes_i=10;$mes_f=12;
        }

        if($mes_i!=0){
          for ($i=$mes_i; $i <=$mes_f ; $i++) { 
            $this->db->where('m_id', $i);
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('prod_ejecutado_mensual');
          }

          $eval=$this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$tr); /// Evaluacion

          $update_trimestre = array(
            'tmed_verif' => $eval[0]['tmed_verif'].' '.'(ANULADO)',
            'tprob' => $eval[0]['tprob'].' '.'(ANULADO)',
            'tacciones' => $eval[0]['tacciones'].' '.'(ANULADO)',
            'fun_id' => $this->fun_id,
            'testado' => 3
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->where('trm_id', $tr);
          $this->db->update('_productos_trimestral', $update_trimestre);

          $result = array(
            'respuesta' => 'correcto',
          );
        }
        else{
          $result = array(
            'respuesta' => 'error',
          );
        }
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*----------- Valida Evaluacion Productos (2019) ---------------*/
    public function valida_evaluacion_producto(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $tmes=$this->model_evaluacion->trimestre();
          $prod_id = $this->security->xss_clean($post['prod_id']);
          $com_id = $this->security->xss_clean($post['com_id']);
          $tp = $this->security->xss_clean($post['tp']);
          $producto=$this->model_producto->get_producto_id($prod_id); /// Get Datos Productos

          $vi=0; $vf=0;
          if($this->tmes==1){ $vi = 1;$vf = 3; }
          elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
          elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
          elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

          $vfinal=0;
          if($this->tmes==1){$vfinal=3;}
          elseif ($this->tmes==2) {$vfinal=6;}
          elseif ($this->tmes==3) {$vfinal=9;}
          elseif ($this->tmes==4) {$vfinal=12;}

          if($prod_id!='' & $com_id!='' & $tp!=''){
            if($tp==1){
              $mverif = $this->security->xss_clean($post['mverif']);
              $prob = '';
              $acc = '';
            }
            elseif($tp==2){
              $mverif = $this->security->xss_clean($post['mverif']);
              $prob = $this->security->xss_clean($post['prob']);
              $acc = $this->security->xss_clean($post['acciones']);
            }
            elseif($tp==3){
              $mverif = '';
              $prob = $this->security->xss_clean($post['prob']);
              $acc = $this->security->xss_clean($post['acciones']);
            }

            /*---- Verificando Valores del trimestre Anterior -----*/
            if($this->get_suma_meta_anterior($prod_id)){
              /*----- Eliminando Datos de temporalidad evaluado en el trimestre ----*/
              $this->model_evaluacion->delete_ejec_temporalizacion($vi,$vf,$prod_id,$this->gestion);
              if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$this->tmes))==0){
                for ($i=$vi; $i <=$vf ; $i++) { 
                  $ejec=$this->security->xss_clean($post['e'.$i]);
                  if($ejec!=0){
                    if(count($this->model_producto->verif_ope_evaluado_mes($prod_id,$i))==0){
                      $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$i,$ejec,0,0);
                    }
                  }
                }

                $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,1,''); /// Insertando Datos de Evaluacion, 1 Registro
              }

            }
            else{/*------ ACTUALIZANDO ACTIVIDADES ------*/
                /*----- Eliminando Datos de temporalidad evaluado en el trimestre ----*/
              $this->model_evaluacion->delete_ejec_temporalizacion($vi,$vf,$prod_id,$this->gestion);
                /*--------------- Insertando valores Ejecutado --------------*/
                for ($i=$vi; $i <=$vf; $i++) { 
                  $ejec=$this->security->xss_clean($post['e'.$i]);
                  if($ejec!=0){
                    if(count($this->model_producto->verif_ope_evaluado_mes($prod_id,$i))==0){
                        $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$i,$ejec,0,0);
                      }
                  }
                }

                /*----------------------------------------------------------*/
                /*--- Obteniendo Metas programas y Evaluadas al Trimestre ---*/
                $meta_acumulado_programado=$this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$vfinal); //// Suma meta al trimestre programado
                $meta_acumulado_evaluado=$this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$vfinal); //// Suma meta al trimestre evaluado

                $meta_prog=0;$meta_eval=0;
                if(count($meta_acumulado_programado)!=0){
                  $meta_prog=$meta_acumulado_programado[0]['trimestre'];
                }

                if(count($meta_acumulado_evaluado)!=0){
                  $meta_eval=$meta_acumulado_evaluado[0]['trimestre'];
                }
                /*-----------------------------------------------------------*/

                /*-----------------------------------------------------------*/
                  if($producto[0]['mt_id']==3){ //// Indicador normal
                      /*----------------------------------*/
                      if($meta_prog==$meta_eval){
                         $nro_act=$this->verif_nro_actividades_por_actualizar($prod_id); /// vector de trimestre
                         $suma_act=0;
                         for ($i=1; $i <=$this->tmes; $i++) { 
                           $suma_act=$suma_act+$nro_act[$i];
                         }
                         
                         $this->model_evaluacion->delete_prod_trimestre($prod_id,$this->tmes,$this->gestion); /// eliminando datos de evaluacion 
                         if($suma_act==1){
                          $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,1,'Trimestre Cumplido'); /// Insertando Datos de Evaluacion, 1 Registro
                         }
                         else{ //// Actualizando actividades no cumplidas
                            for ($i=1; $i <=$this->tmes ; $i++) { 
                              $activo=0;
                              $observacion='Trimestre Actualizado Cumplido '.$i;
                              if($i==$this->tmes){
                                $activo=1;
                                $observacion='Trimestre Cumplido';
                              }

                              if($nro_act[$i]==1){
                                $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,$activo,$observacion); /// Insertando Datos de Evaluacion, N Registros
                              
                              }
                            }
                         }

                         /////]----- Verificando si hay Evaluacion activa del trimestre
                              
                          if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$this->tmes))==0){
                            $ultimo=$this->model_evaluacion->get_trimestral_prod_ultimo($prod_id,$this->gestion,$this->tmes);

                              $update_prod = array(
                                'activo' => 1,
                              );
                              $this->db->where('tprod_id', $ultimo[0]['tprod_id']);
                              $this->db->update('_productos_trimestral', $update_prod);
                          }
                      }
                      else{ /// Insertar Actividad
                        $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,1,''); /// Insertando Datos de Evaluacion, 1 Registro
                      }
                  }
                  else{ /// Indicador Recurrente
                    $observacion='';
                    if($tp==1){
                      $observacion='Trimestre Cumplido';
                    }

                    $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,1,$observacion); /// Insertando Datos de Evaluacion, 1 Registro
                  }
              }

              $this->session->set_flashdata('success','EVALUCIACI&Oacute;N SE REGISTRO CORRECTAMENTE DEL PRODUCTO RESPECTO AL TRIMESTRE '.$tmes[0]['trm_descripcion'].'');
              redirect(site_url("").'/eval/eval_gcorriente/'.$com_id.'');

          }
          else{
            $this->session->set_flashdata('danger','ERROR EN LA EVALUACI&Oacute;N');
            redirect(site_url("").'/eval/eval_productos/'.$com_id.'');
          }


      } else {
          show_404();
      }
    }

    /*---- Valida Modificacion Evaluacion Operaciones ----*/
    public function valida_mod_evaluar_productos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $tmes=$this->model_evaluacion->trimestre();
          $prod_id = $this->security->xss_clean($post['id_prod']);
          $tprod_id = $this->security->xss_clean($post['tprod_id']);
          $com_id = $this->security->xss_clean($post['com_id']);
          $tp = $this->security->xss_clean($post['mtp']);
          $producto=$this->model_producto->get_producto_id($prod_id); /// Get Datos Productos

          $vi=0; $vf=0;
          if($this->tmes==1){ $vi = 1;$vf = 3; }
          elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
          elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
          elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

          $vfinal=0;
          if($this->tmes==1){$vfinal=3;}
          elseif ($this->tmes==2) {$vfinal=6;}
          elseif ($this->tmes==3) {$vfinal=9;}
          elseif ($this->tmes==4) {$vfinal=12;}

          if($prod_id!='' & $tprod_id!='' & $com_id!='' & $tp!=''){
              if($tp==1){ //// Cumplido
                $mverif = $this->security->xss_clean($post['mmverif']);
                $prob = '';
                $acc = '';
              }
              elseif($tp==2){ /// En proceso
                $mverif = $this->security->xss_clean($post['mmverif']);
                $prob = $this->security->xss_clean($post['mprob']);
                $acc = $this->security->xss_clean($post['macciones']);
              }
              elseif($tp==3){ /// No cumplido
                $mverif = '';
                $prob = $this->security->xss_clean($post['mprob']);
                $acc = $this->security->xss_clean($post['macciones']);
              }

              /*----- Eliminando Datos de temporalidad evaluado en el trimestre ----*/
              $this->model_evaluacion->delete_ejec_temporalizacion($vi,$vf,$prod_id,$this->gestion);
              
              /*---- Verificando Valores del trimestre Anterior -----*/
              if($this->get_suma_meta_anterior($prod_id)){
                /*--------------- Insertando valores Ejecutado --------------*/
                  for ($i=$vi; $i <=$vf; $i++) { 
                    $ejec=$this->security->xss_clean($post['me'.$i]);
                    if($ejec!=0){
                      if(count($this->model_producto->verif_ope_evaluado_mes($prod_id,$i))==0){
                          $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$i,$ejec,0,0);
                        }
                    }
                  }
                  /*----------------------------------------------------------*/
                  $this->update_datos($tprod_id,$tp,$mverif,$prob,$acc,'');
              }
              else{
                 /*------ ACTUALIZANDO ACTIVIDADES ------*/
                  /*--------------- Insertando valores Ejecutado --------------*/
                  for ($i=$vi; $i <=$vf; $i++) { 
                    $ejec=$this->security->xss_clean($post['me'.$i]);
                    if($ejec!=0){
                      if(count($this->model_producto->verif_ope_evaluado_mes($prod_id,$i))==0){
                          $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$i,$ejec,0,0);
                        }
                    }
                  }
                  /*----------------------------------------------------------*/

                  /*--- Obteniendo Metas programas y Evaluadas al Trimestre ---*/
                  $meta_acumulado_programado=$this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$vfinal); //// Suma meta al trimestre programado
                  $meta_acumulado_evaluado=$this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$vfinal); //// Suma meta al trimestre evaluado

                  $meta_prog=0;$meta_eval=0;
                  if(count($meta_acumulado_programado)!=0){
                    $meta_prog=$meta_acumulado_programado[0]['trimestre'];
                  }

                  if(count($meta_acumulado_evaluado)!=0){
                    $meta_eval=$meta_acumulado_evaluado[0]['trimestre'];
                  }
                  /*-----------------------------------------------------------*/
                  if($producto[0]['mt_id']==3){ //// Indicador normal
                      /*----------------------------------*/
                      if($meta_prog==$meta_eval){
                         $nro_act=$this->verif_nro_actividades_por_actualizar($prod_id); /// vector de trimestre
                         $suma_act=0;
                         for ($i=1; $i <=$this->tmes; $i++) { 
                           $suma_act=$suma_act+$nro_act[$i];
                         }
                         
                         $this->model_evaluacion->delete_prod_trimestre($prod_id,$this->tmes,$this->gestion); /// eliminando datos de evaluacion 
                         if($suma_act==1){
                          $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,1,'Trimestre Cumplido'); /// Insertando Datos de Evaluacion, 1 Registro
                         }
                         else{ //// Actualizando actividades no cumplidas
                            for ($i=1; $i <=$this->tmes ; $i++) { 
                              $activo=0;
                              $observacion='Trimestre Actualizado Cumplido '.$i;
                              if($i==$this->tmes){
                                $activo=1;
                                $observacion='Trimestre Cumplido';
                              }

                              if($nro_act[$i]==1){
                                $tprod_id=$this->adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,$activo,$observacion); /// Insertando Datos de Evaluacion, N Registros
                              }
                            }
                         }
                      }
                      else{ /// Actualizando Datos de Proceso
                        $this->update_datos($tprod_id,$tp,$mverif,$prob,$acc,$observacion);
                      }
                  }
                  else{ /// Indicador Recurrente
                    $observacion='';
                    if($tp==1){
                      $observacion='Trimestre Cumplido';
                    }

                    $this->update_datos($tprod_id,$tp,$mverif,$prob,$acc,$observacion);
                  }

              }

              $this->session->set_flashdata('success','EVALUCIACI&Oacute;N SE MODIFICO CORRECTAMENTE DEL PRODUCTO RESPECTO AL TRIMESTRE '.$tmes[0]['trm_descripcion'].'');
              redirect(site_url("").'/eval/eval_gcorriente/'.$com_id.'');

          }
          else{
            $this->session->set_flashdata('danger','ERROR EN LA EVALUACI&Oacute;N');
            redirect(site_url("").'/eval/eval_gcorriente/'.$com_id.'');
          }

      } else {
          show_404();
      }
    }

    


        /*--- Insertando datos de Evaluacion ---*/
    function update_datos($tprod_id,$tp,$mverif,$prob,$acc,$observacion){
        $update_prod = array(
          'tp_eval' => $tp,
          'tmed_verif' => strtoupper($mverif),
          'tprob' => strtoupper($prob),
          'tacciones' => strtoupper($acc),
          'fun_id' => $this->fun_id,
          'testado' => 2,
          'eval_observacion' => $observacion,
        );
        $this->db->where('tprod_id', $tprod_id);
        $this->db->update('_productos_trimestral', $update_prod);

    }

    /*--- Insertando datos de Evaluacion ---*/
    function adicionando_datos($prod_id,$tp,$mverif,$prob,$acc,$activo,$observacion){
      $data = array(
        'prod_id' => $prod_id,
        'trm_id' => $this->tmes,
        'tp_eval' => $tp,
        'tmed_verif' => strtoupper($mverif),
        'tprob' => strtoupper($prob),
        'tacciones' => strtoupper($acc),
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

    /*--- Obtiene Datos Meta programado, Evaluado del trimestre anterior ---*/
    function get_suma_meta_anterior($prod_id){
        $meta_anterior=false;

        if($this->tmes!=1){
          $mes_anterior=0;
          if($this->tmes==2) {$mes_anterior=3;}
          elseif ($this->tmes==3) {$mes_anterior=6;}
          elseif ($this->tmes==4) {$mes_anterior=9;}

          $meta_acumulado_programado_tanterior=$this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$mes_anterior); //// Suma meta al trimestre programado
          $meta_acumulado_evaluado_tanterior=$this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$mes_anterior); //// Suma meta al trimestre evaluado

          $meta_prog_anterior=0;$meta_eval_anterior=0;
          if(count($meta_acumulado_programado_tanterior)!=0){
            $meta_prog_anterior=$meta_acumulado_programado_tanterior[0]['trimestre'];
          }

          if(count($meta_acumulado_evaluado_tanterior)!=0){
            $meta_eval_anterior=$meta_acumulado_evaluado_tanterior[0]['trimestre'];
          }

          if($meta_eval_anterior==$meta_prog_anterior){
            $meta_anterior=true;
          }
        }

        return $meta_anterior;
    }


    function verif_nro_actividades_por_actualizar($prod_id){
      for ($i=1; $i<=$this->tmes; $i++) { 
        $nro_actividades[$i]=0;
      }

      for ($i=1; $i <=$this->tmes ; $i++) { 
        $programado_trimestre=$this->model_evaluacion->programado_trimestral_productos($i,$prod_id); /// Prog
        $ejecutado_trimestre=$this->model_evaluacion->ejecutado_trimestral_productos($i,$prod_id); /// Ejec
        if(count($programado_trimestre)!=0){
            $nro_actividades[$i]=1;
            if (count($ejecutado_trimestre)!=0){
              if($programado_trimestre[0]['trimestre']==$ejecutado_trimestre[0]['trimestre']){
                $nro_actividades[$i]=0;
              }
            }
        }
      }

      return $nro_actividades;
    }



    /*------- LISTA DE  OPERACIONES 2020 ------*/
    function lista_productos($proy_id,$com_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $productos=$this->model_producto->list_prod($com_id); /// lISTA DE ACTIVIDADES
      $tabla='';

      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $vfinal=0;
      if($this->tmes==1){$vfinal=3;}
      elseif ($this->tmes==2) {$vfinal=6;}
      elseif ($this->tmes==3) {$vfinal=9;}
      elseif ($this->tmes==4) {$vfinal=12;}

      $tabla.=' <div class="table-responsive">
                  <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>                             
                        <tr>
                          <th style="width:5%;"></th>
                          <th style="width:2%;"><b>COD. OR.</b></th>
                          <th style="width:2%;"><b>COD. ACT.</b></th>
                          <th style="width:15%;">ACTIVIDAD</th>
                          <th style="width:5%;">TIPO DE INDICADOR</th>
                          <th style="width:5%;">INDICADOR</th>
                          <th style="width:5%;">LINEA BASE</th>
                          <th style="width:5%;">META</th>
                          <th style="width:10%;">VERIFICACI&Oacute;N</th>
                          <th style="width:5%;">%</th>
                          <th style="width:5%;">PROG. TRIMESTRAL</th>
                          <th style="width:5%;">EJEC. TRIMESTRAL</th>
                          <th style="width:10%;">EVALUACI&Oacute;N</th>
                          <th style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</th>
                          <th style="width:10%;">PROBLEMAS PRESENTADOS</th>
                          <th style="width:10%;">ACCIONES REALIZADAS</th>';
                          if($this->tp_adm==1){
                            $tabla.='<th style="width:10%;">RESPONSABLE</th>';
                          }
                          $tabla.='
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0; $pcion=0;
                      foreach($productos as $rowp){
                        $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Programado
                        $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Ejecutado

                        $trimestre=$this->model_evaluacion->get_trimestral_prod($rowp['prod_id'],$this->gestion,$this->tmes);
                        $prog_actual=0;  $pcion=$pcion+$rowp['prod_ponderacion'];
                        
                        if(count($trimestre_prog)!=0){
                          $prog_actual=$trimestre_prog[0]['trimestre'];
                        }
                        $ejec_actual=0; 
                        if(count($trimestre_ejec)!=0){
                          $ejec_actual=$trimestre_ejec[0]['trimestre'];
                        }

                        $prog=$this->model_evaluacion->rango_programado_trimestral_productos($rowp['prod_id'],$vfinal);
                        $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($rowp['prod_id'],$vfinal);

                        $acu_prog=0;
                        $acu_ejec=0;
                        if(count($prog)!=0){
                          $acu_prog=$prog[0]['trimestre'];
                        }
                        if(count($eval)!=0){
                          $acu_ejec=$eval[0]['trimestre'];
                        }

                        $bg_color=''; $btn='';
                        if($rowp['prod_priori']==1){
                          $bg_color='#d9f3fd';
                          $btn='<button class="btn btn-primary btn-xs">Prioridad</button>';
                        }

                        //$tabla.=''.$nro.'.- '.$acu_prog.'-'.$acu_ejec.'----'.$rowp['prod_id'].' -> '.$rowp['prod_producto'].'<br>';
                        if(($prog_actual!=0 || $ejec_actual!=0) || ($prog_actual!=0 || ($acu_prog-$acu_ejec)!=0)){
                          $nro++;
                          $tabla .='<tr bgcolor='.$bg_color.'>';
                          $tabla .='<td align=center title='.$rowp['prod_id'].'>';
                            $tabla .=$nro.'<br>'.$btn.'<br>';
                            if($this->tp_adm==1){
                              $tabla.='<a href="'.site_url("").'/eval/reformular/'.$rowp['prod_id'].'" title="REFORMULAR EVALUACION" class="btn btn-default" target="_blank"><img src="'.base_url().'assets/img/ifinal/nodoc.png" WIDTH="30" HEIGHT="30"/></a>';
                            }
                          $tabla .='</td>';
                          $tabla.='<td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
                          $tabla.='<td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
                          $tabla .='<td>'.$rowp['prod_producto'].'</td>';
                          $tabla .='<td>'.$rowp['indi_descripcion'].'</td>';
                          $tabla .='<td>'.$rowp['prod_indicador'].'</td>';
                          $tabla .='<td>'.$rowp['prod_linea_base'].'</td>';
                          $tabla .='<td>'.$rowp['prod_meta'].'</td>';
                          $tabla .='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
                          $tabla .='<td>'.$rowp['prod_ponderacion'].'%</td>';
                          $tabla .='<td>'.$prog_actual.'</td>';
                          $tabla .='<td bgcolor="#daf3da">'.$ejec_actual.'</td>';
                          
                          if(count($trimestre)!=0){
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tpeval_descripcion'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tmed_verif'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tprob'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tacciones'].'</td>';
                            if($this->tp_adm==1){
                              $tabla.='<td bgcolor="#d5d2d2">'.date('d-m-Y',strtotime($trimestre[0]['fecha'])).'<br>'.$trimestre[0]['fun_nombre'].' '.$trimestre[0]['fun_paterno'].'</td>';
                            }
                            $tabla .='<td align=center>';
                            $tabla.=$this->verif_btn_eval($this->fun_id,1,$rowp['prod_id'],$proy_id);
                           //   $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. ACT.</a>';
                              /*if($this->tp_adm==1 || $this->fun_id==721 || $this->fun_id==598 || $this->fun_id==689 || $this->fun_id==690 || $this->fun_id==719 || $this->fun_id==460){
                                $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. PROD.</a>';
                              }*/
                            $tabla .='</td>'; 
                          }
                          else{
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            if($this->tp_adm==1){
                              $tabla.='<td bgcolor="#e9f7e9">--</td>';
                            }
                            $tabla .='<td align="center">';
                             $tabla.=$this->verif_btn_eval($this->fun_id,0,$rowp['prod_id'],$proy_id);
                             // $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. ACT.</a>';
                              
                              /*if($this->tp_adm==1 || $this->fun_id==721 || $this->fun_id==598 || $this->fun_id==689 || $this->fun_id==690 || $this->fun_id==719 || $this->fun_id==460){
                                $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. PROD.</a>';
                              }*/
                            $tabla .='</td>';
                          }
                          $temp=$this->temporalizacion_productos($rowp['prod_id']);
                          $tabla .='<td>
                          <center><a data-toggle="modal" data-target="#'.$rowp['prod_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="38" HEIGHT="38"/></a></center>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowp['prod_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_update">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                    <h4 class="modal-title">
                                        <b>ACTIVIDAD</b> : '.$rowp['prod_producto'].'
                                    </h4>
                                    <font color=blue><b>INDICADOR : '.$rowp['mt_tipo'].'</b></font>
                                  </div>
                                  <div class="modal-body no-padding">
                                    <div class="well">
                                      <div class="table-responsive">
                                      <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                          <th bgcolor="#1c7368"><font color="#fff">P/E</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">ENE.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">FEB.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">MAR.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">ABR.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">MAY.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">JUN.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">JUL.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">AGOS.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">SEPT.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">OCT.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">NOV.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">DIC.</font></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                          <td title="PROGRAMADO">P.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[1][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[1][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="PROGRAMADO ACUMULADO">PA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[2][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[2][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="%PROGRAMADO ACUMULADO">%PA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[3][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[3][$i].'%</td>';
                                            } 
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="EJECUTADO">E.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[4][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[4][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="EJECUTADO ACUMULADO">EA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[5][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[5][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="%EJECUTADO ACUMULADO">%EA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[6][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[6][$i].'%</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr bgcolor="#daf3da">
                                          <td title="EFICACIA">EFI.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[7][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[7][$i].'%</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        </tbody>
                                      </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td>

                            <center><a data-toggle="modal" data-target="#p'.$rowp['prod_id'].'" title="HISTORIAL EVALUACIONES PRODUCTOS " class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/history.png" WIDTH="35" HEIGHT="35"/></a></center>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="p'.$rowp['prod_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" id="mdialTamanio">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                    <h4 class="modal-title">
                                        <b>ACTIVIDAD : </b>'.$rowp['prod_cod'].'.- '.$rowp['prod_producto'].'
                                    </h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="row">
                                        '.$this->historial_operaciones($rowp['prod_id'],1).'
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </td>';
                          $tabla.='<td>';
                            //$tabla.='<a href="#" data-toggle="modal" data-target="#modal_update" class="btn btn-default btn-lg" name="'.$rowp['prod_id'].'" title="ARCHIVOS ADJUNTOS A LA OPERACI&Oacute;N"><img src="'.base_url().'assets/ifinal/update.png" WIDTH="35" HEIGHT="35"/></a>';
                          $tabla.='</td>';
                        $tabla .='</tr>';
                        }
                      }
                      $tabla.='
                      </tbody>
                      <tr>
                        <td colspan=9></td>
                        <td>'.round($pcion,0).'%</td>
                        <td colspan=9></td>
                      </tr>
                  </table>
                </div>';

      return $tabla;
    }


    /*-------- Verif Boton Evaluacion ---------*/
    function verif_btn_eval($fun_id,$tp,$prod_id,$proy_id){
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
                if($tp==0){ /// Evaluar Actividad
                  $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$prod_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. ACT.</a>';
                }
                else{ /// Modificar Evaluacion Actividad
                  $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$prod_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. ACT.</a>';
                }
            }
          }
      }

      return $tabla;
    }


    /*-------- Historial Productos Evaluados ---------*/
    function historial_operaciones($prod_id,$tp){
      $tabla ='';
    //  $tabla .=''.$prod_id.'<br>';
      $temp=$this->temporalizacion_productos($prod_id);
      
      for ($i=1; $i <=4 ; $i++) {
        $ev=$this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i);
        
        $tmes=$this->model_evaluacion->get_trimestre($i);
        $tabla .=' <div class="col-sm-3">
                    <div class="well">';
                      if (count($ev)!=0) {
                        $tabla .='<div class="alert alert-success" align="center">'.$tmes[0]['trm_descripcion'].' EVALUADO </div>';
                      }
                      else{
                        $tabla .='<div class="alert alert-danger" align="center">'.$tmes[0]['trm_descripcion'].' NO EVALUADO </div>';
                      }
                      if($i==1){
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">ENE.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">FEB.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">MAR.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][1].'</td>
                              <td>'.$temp[1][2].'</td>
                              <td>'.$temp[1][3].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][1].'</td>
                              <td>'.$temp[4][2].'</td>
                              <td>'.$temp[4][3].'</td>
                            </tr>
                          </tbody>
                        </table>
                        <hr>';
                      }
                      elseif ($i==2) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">ABR.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">MAY.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">JUN.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][4].'</td>
                              <td>'.$temp[1][5].'</td>
                              <td>'.$temp[1][6].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][4].'</td>
                              <td>'.$temp[4][5].'</td>
                              <td>'.$temp[4][6].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }
                      elseif ($i==3) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">JUL.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">AGO.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">SEP.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][7].'</td>
                              <td>'.$temp[1][8].'</td>
                              <td>'.$temp[1][9].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][7].'</td>
                              <td>'.$temp[4][8].'</td>
                              <td>'.$temp[4][9].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }
                      elseif ($i==4) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">OCT.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">NOV.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">DIC.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][10].'</td>
                              <td>'.$temp[1][11].'</td>
                              <td>'.$temp[1][12].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][10].'</td>
                              <td>'.$temp[4][11].'</td>
                              <td>'.$temp[4][12].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }

                      if (count($ev)!=0) {
                        if($ev[0]['tp_eval']==1){
                          $tabla.='<font color="#8dbd76"><b>EVALUACI&Oacute;N : CUMPLIDO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                      <td>'.$ev[0]['tmed_verif'].'</td>
                                    </tr>
                                    </table>
                                    <hr>
                                    <b>RESPONSABLE DEL REGISTRO :</b> 
                                    '.$ev[0]['fun_nombre'].' '.$ev[0]['fun_paterno'].' '.$ev[0]['fun_materno'].'<br>
                                    <b>FECHA DEL REGISTRO :</b> 
                                    '.date('d-m-Y',strtotime($ev[0]['fecha'])).'<hr>';
                        }
                        elseif ($ev[0]['tp_eval']==2) {
                          $tabla.='<font color="#ece5a2"><b>EVALUACI&Oacute;N : EN PROCESO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                      <td>'.$ev[0]['tmed_verif'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>PROBLEMAS PRESENTADOS</b></td>
                                      <td>'.$ev[0]['tprob'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>ACCIONES REALIZADAS</b></td>
                                      <td>'.$ev[0]['tacciones'].'</td>
                                    </tr>
                                    </table>
                                    <hr>
                                    <b>RESPONSABLE DEL REGISTRO :</b> 
                                    '.$ev[0]['fun_nombre'].' '.$ev[0]['fun_paterno'].' '.$ev[0]['fun_materno'].'<br>
                                    <b>FECHA DEL REGISTRO :</b> 
                                    '.date('d-m-Y',strtotime($ev[0]['fecha'])).'<hr>';
                        }
                        elseif ($ev[0]['tp_eval']==3) {
                          $tabla.='<font color="#d24d4d"><b>EVALUACI&Oacute;N : NO CUMPLIDO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>PROBLEMAS PRESENTADOS</b></td>
                                      <td>'.$ev[0]['tprob'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>ACCIONES REALIZADAS</b></td>
                                      <td>'.$ev[0]['tacciones'].'</td>
                                    </tr>
                                    </table>
                                    <hr>
                                    <b>RESPONSABLE DEL REGISTRO :</b> 
                                    '.$ev[0]['fun_nombre'].' '.$ev[0]['fun_paterno'].' '.$ev[0]['fun_materno'].'<br>
                                    <b>FECHA DEL REGISTRO :</b> 
                                    '.date('d-m-Y',strtotime($ev[0]['fecha'])).'<hr>';
                        }
                        
                      }
                    $tabla .='
                    
                    </div>
                  </div>';
              
      }
      return $tabla;        
    }


    /*-------- GET LISTA DE REQUERIMIENTOS --------*/
    public function get_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);

        $tabla=$this->mi_poa($proy_id);
        $evaluacion='
            <a href="'.site_url("").'/eval/eval_unidad/'.$proy_id.'" title="REPORTE DE EVALUACION" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/img/impresora.png" WIDTH="50" HEIGHT="50"/><br>VER EVALUACIÓN</a>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'evaluacion'=>$evaluacion,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ GET POA -----*/
    public function mi_poa($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $listar_trimestre= $this->model_configuracion->get_mes_trimestre();
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th style="width:3%;" bgcolor="#474544"> Nro.</th>
                    <th style="width:30%;" bgcolor="#474544"> SERVICIO / COMPONENTE</th>
                    <th style="width:10%;" bgcolor="#474544">PONDERACI&Oacute;N</th>
                    <th style="width:10%;" bgcolor="#474544"></th>
                    <th style="width:1%;" bgcolor="#474544"></th>
                    <th style="width:30%;" bgcolor="#474544">REPORTE TRIMESTRAL POA</th>
                    <th style="width:1%;" bgcolor="#474544"></th>
                    
                  </tr>
                  </thead>
                  <tbody>';
                  $nro_c=0;
                    $componentes=$this->model_componente->proyecto_componente($proy_id);
                    foreach($componentes as $rowc){
                      $this->ponderacion_componente($rowc['com_id'],count($componentes));
                      $nro_c++;
                      $tabla.='
                      <tr>
                        <td>'.$nro_c.'</td>
                        <td>'.$rowc['com_componente'].'</td>
                        <td>'.$rowc['com_ponderacion'].'%</td>
                        <td>
                          <a href="'.site_url("").'/eval/eval_productos/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-primary" title="EVALUAR OPERACIONES">
                            EVALUAR
                          </a>
                        </td>
                        <td align=center><a href="'.site_url("").'/eval/rep_eval_productos/'.$rowc['com_id'].'" target="_blank" title="REPORTE EVALUACI&Oacute;N"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="30" HEIGHT="30"/></a></td>
                        <td align=center>
                          <div class="btn-group">
                            <a class="btn btn-default">EVALUACI&Oacute;N TRIMESTRAL</a>
                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span></a>
                            <ul class="dropdown-menu">';
                              foreach($listar_trimestre as $rowm){
                                if($rowm['trm_id']!=0){
                                  $tabla.='
                                  <li>
                                    <a href="'.site_url("").'/eval/rep_eval_productos_trimestral/'.$rowc['com_id'].'/'.$rowm['trm_id'].'" target="_blank">REPORTE SEG. '.$rowm['trm_descripcion'].'</a>
                                  </li>';
                                }                  
                            }
                            $tabla.='
                            </ul>
                          </div>
                        </td>
                        <td align=center><img id="load'.$rowc['com_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
                        
                      </tr>';
                      $tabla.=' <script>
                                  document.getElementById("myBtn'.$rowc['com_id'].'").addEventListener("click", function(){
                                  this.disabled = true;
                                  document.getElementById("load'.$rowc['com_id'].'").style.display = "block";
                                  });
                                </script>';

                    }
                  $tabla.='
                  </tbody>
                </table>';

      return $tabla;
    }

    /*------ PONDERACION SERVICIOS/COMPONENTES ------*/
    public function ponderacion_componente($com_id,$total){
      $pcion=(100/$total);

      $update_com = array(
        'com_ponderacion' => $pcion
      );
      $this->db->where('com_id', $com_id);
      $this->db->update('_componentes', $update_com);
    }


    /*--- TEMPORALIZACION DE PRODUCTOS (nose esta tomando encuenta lb) ---*/
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

    /*------------------------ Get Producto ----------------------*/
    public function get_productos(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']);

          $producto = $this->model_producto->get_producto_id($prod_id);
          $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
          $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado
          $sum_prod_prog= $this->model_producto->suma_programado_producto($prod_id,$this->gestion);//// Suma Temporalidad Programado
          $verif='si';
          if(count($prod_ejec)==0){
            $prod_ejec = array('enero' => '0','febrero' => '0','marzo' => '0','abril' => '0','mayo' => '0','junio' => '0','julio' => '0','agosto' => '0','septiembre' => '0','octubre' => '0','noviembre' => '0','diciembre' => '0');
            $verif='no';
          }

          $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($this->tmes,$prod_id); /// Trimestre Programado
          $prog_actual=0; 
          if(count($trimestre_prog)!=0){
            $prog_actual=$trimestre_prog[0]['trimestre'];
          }

          $vfinal=0;
          if($this->tmes==2){
            $vfinal=3;
          }
          elseif ($this->tmes==3) {
           $vfinal=6; 
          }
          elseif ($this->tmes==4) {
            $vfinal=9;
          }

          $ejec_anterior=0;$prog_anterior=0;
          $trimestre_prog = $this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$vfinal); /// Trimestre Programado
          if(count($trimestre_prog)!=0){
            $prog_anterior=$trimestre_prog[0]['trimestre'];
          }

          $trimestre_ejec = $this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$vfinal); /// Trimestre Ejecutado
          if(count($trimestre_ejec)!=0){
            $ejec_anterior=$trimestre_ejec[0]['trimestre'];
          }

          $diferencia=($prog_anterior-$ejec_anterior);
          if($producto[0]['mt_id']==1){
            $diferencia=0;
          }

          $suma_evaluado=0;
          $evaluado=$this->model_producto->suma_total_evaluado($prod_id);
          if(count($evaluado)!=0){
            $suma_evaluado=$evaluado[0]['suma_total'];
          }

          if(count($producto)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'producto' => $producto, /// producto
              'tmes' => $this->tmes,
              'sprog' => $sum_prod_prog[0]['prog'], //// Suma Programado (Relativos)
              'temp_prog' => $prod_prog, //// Programado
              'temp_ejec' => $prod_ejec, //// Ejecutado
              'verif' => $verif,  //// verifica ejecutado
              'tprog_actual' => $prog_actual, //// Total Programado Trimestre Actual
              'tdif' => $diferencia,  /// Total de saldo no programado del trimestre anterior
              'sum_total_evaluar' => ($producto[0]['prod_meta']-$suma_evaluado)  /// Total por evaluar 
            );
          }
          else{
            $result = array(
                'respuesta' => 'error'
            );
          }
          echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ Get Modificar Evaluacion Operaciones -------*/
    public function get_mod_productos(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']);

          $producto = $this->model_producto->get_producto_id($prod_id);
          $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
          $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado
          $verif='si';
          if(count($prod_ejec)==0){
            $prod_ejec = array('enero' => '0','febrero' => '0','marzo' => '0','abril' => '0','mayo' => '0','junio' => '0','julio' => '0','agosto' => '0','septiembre' => '0','octubre' => '0','noviembre' => '0','diciembre' => '0');
            $verif='no';
          }
          
          /*----------------------------------- Trimestre Actual Programado -----------------------------*/
          $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($this->tmes,$prod_id);
          $prog_actual=0; 
          if(count($trimestre_prog)!=0){
            $prog_actual=$trimestre_prog[0]['trimestre'];
          }
          /*--------------------------------------------------------------------------------------------*/
          /*----------------------------------- Trimestre Actual Ejecutado -----------------------------*/
          $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($this->tmes,$prod_id);
          $ejec_actual=0; 
          if(count($trimestre_ejec)!=0){
            $ejec_actual=$trimestre_ejec[0]['trimestre'];
          }
          /*--------------------------------------------------------------------------------------------*/
          $vfinal=0;
          if($this->tmes==2){
            $vfinal=3;
          }
          elseif ($this->tmes==3) {
           $vfinal=6; 
          }
          elseif ($this->tmes==4) {
            $vfinal=9;
          }

          $ejec_anterior=0;$prog_anterior=0;
          $trimestre_prog = $this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$vfinal); /// Trimestre Ejecutado
          if(count($trimestre_prog)!=0){
            $prog_anterior=$trimestre_prog[0]['trimestre'];
          }

          $trimestre_ejec = $this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$vfinal); /// Trimestre Ejecutado
          if(count($trimestre_ejec)!=0){
            $ejec_anterior=$trimestre_ejec[0]['trimestre'];
          }

          $dato_trimestre=$this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$this->tmes);
          if(count($producto)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'producto' => $producto,
              'tmes' => $this->tmes,
              'temp_prog' => $prod_prog, //// Programado
              'temp_ejec' => $prod_ejec, //// Ejecutado
              'verif' => $verif,  //// verifica ejecutado
              'tprog_actual' => $prog_actual, //// Total Programado Trimestre Actual
              'tejec_actual' => $ejec_actual, //// Total Ejecutado Trimestre Actual
              'tdif' => $prog_anterior-$ejec_anterior,
              'dato_trimestre'=> $dato_trimestre
            );
          }
          else{
            $result = array(
                'respuesta' => 'error'
            );
          }
          echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--------- REPORTE EVALUACION POA 2020 --------*/
    public function reporte_evaluar_operaciones($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE 
      if(count($data['componente'])!=0){
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); ////// DATOS DEL PROYECTO
        $data['cabecera']=$this->cabecera($com_id); /// Cabecera
        $data['tmes']=$this->model_evaluacion->trimestre();
        $data['trimestre']=$this->model_evaluacion->get_trimestre($this->tmes);
        $data['operaciones']=$this->lista_operaciones_evaluar($com_id,$this->tmes); /// Lista de Operaciones a evaluar en el trimestre
        $data['mes'] = $this->mes_nombre();
        $this->load->view('admin/evaluacion/operaciones/reporte_evaluacion_trimestral', $data);
      }
      else{
        echo "Error !!!!";
      }
    }


    /*--------- REPORTE EVALUACION POA 2020 --------*/
    public function reporte_evaluar_operaciones_trimestral($com_id,$trimestre){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE 
      if(count($data['componente'])!=0){
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); ////// DATOS DEL PROYECTO
        $data['cabecera']=$this->cabecera($com_id); /// Cabecera
        $data['tmes']=$this->model_evaluacion->trimestre();
        $data['trimestre']=$this->model_evaluacion->get_trimestre($trimestre);
        $data['operaciones']=$this->lista_operaciones_evaluar($com_id,$trimestre); /// Lista de Operaciones a evaluar en el trimestre
        $data['mes'] = $this->mes_nombre();
        $this->load->view('admin/evaluacion/operaciones/reporte_evaluacion_trimestral', $data);
      }
      else{
        echo "Error !!!!";
      }
    }

    /*--------- REPORTE EVALUACION POA 2020 (CONSOLIDADO) --------*/
    public function reporte_evaluar_operaciones_consolidado($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE 
      if(count($data['componente'])!=0){
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); ////// DATOS DEL PROYECTO
        $data['cabecera']=$this->cabecera($com_id); /// Cabecera
        $data['tmes']=$this->model_evaluacion->trimestre();
        $data['operaciones']=$this->lista_operaciones_consolidado($com_id); /// Lista de Operaciones a evaluar en el trimestre
        $data['mes'] = $this->mes_nombre();
        $this->load->view('admin/evaluacion/operaciones/reporte_evaluacion_trimestral', $data);
      }
      else{
        echo "Error !!!!";
      }
    }


    /*------ LISTA DE OPERACIONES A EVALUAR 2020 -------*/
    function lista_operaciones_evaluar($com_id,$tmes){
      //$productos=$this->model_producto->list_prod($com_id);
      $tabla='';

      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $vfinal=0;
      if($this->tmes==1){$vfinal=3;}
      elseif ($this->tmes==2) {$vfinal=6;}
      elseif ($this->tmes==3) {$vfinal=9;}
      elseif ($this->tmes==4) {$vfinal=12;}

      $operaciones=$this->model_producto->list_operaciones($com_id);
      $tabla.=' <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:7%;color:#FFF;">ACTIVIDAD</th>
                    <th style="width:7%;color:#FFF;">RESULTADO</th>
                    <th style="width:7%;color:#FFF;">INDICADOR</th>
                    <th style="width:2%;color:#FFF;">L.B.</th> 
                    
                    <th style="width:2%;color:#FFF;">META</th>
                    <th style="width:2.5%;color:#FFF;">PROG.</th>
                    <th style="width:2.5%;color:#FFF;">EVAL.</th>
                    <th style="width:5%;color:#FFF;">TP. EVAL</th>

                    <th style="width:7%;color:#FFF;">MEDIO DE VERIFICACIÓN</th>
                    <th style="width:7%;color:#FFF;">PROBLEMAS PRESENTADOS</th>
                    <th style="width:7%;color:#FFF;">ACCIONES REALIZADAS</th>
                    
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

                </tr>
                </thead>
                <tbody>';
                  $nro=0;
                  foreach($operaciones as $rowp){
                    $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($tmes,$rowp['prod_id']); /// Trimestre Programado
                    $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($tmes,$rowp['prod_id']); /// Trimestre Ejecutado
                    $trimestre=$this->model_evaluacion->get_trimestral_prod($rowp['prod_id'],$this->gestion,$tmes);
                    $prog_actual=0; 
                    if(count($trimestre_prog)!=0){
                      $prog_actual=$trimestre_prog[0]['trimestre'];
                    }
                    $ejec_actual=0; 
                    if(count($trimestre_ejec)!=0){
                      $ejec_actual=$trimestre_ejec[0]['trimestre'];
                    }

                    $prog=$this->model_evaluacion->rango_programado_trimestral_productos($rowp['prod_id'],$vfinal);
                    $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($rowp['prod_id'],$vfinal);

                    $acu_prog=0;
                    $acu_ejec=0;
                    if(count($prog)!=0){
                      $acu_prog=$prog[0]['trimestre'];
                    }
                    if(count($eval)!=0){
                      $acu_ejec=$eval[0]['trimestre'];
                    }


                    if(($prog_actual!=0 || $ejec_actual!=0) || ($prog_actual!=0 || ($acu_prog-$acu_ejec)!=0)){
                      $nro++;
                      $tabla .='
                      <tr >
                        <td style="width: 1%; text-align: center; height:50px; font-size: 3px;" title='.$rowp['prod_id'].'>'.$nro.' - '.$rowp['prod_id'].'</td>
                        <td style="width: 2%; text-align: center;">'.$rowp['or_codigo'].'</td>
                        <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_producto'].'</td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                        <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                        <td style="width: 2%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>
                        <td style="width: 2.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($prog_actual,2).'</b></td>
                        <td style="width: 2.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($ejec_actual,2).'</b></td>';
                        if(count($trimestre)!=0){
                          $tabla .='<td style="width: 5%; text-align: left;" bgcolor="#f0f7f0">'.$trimestre[0]['tpeval_descripcion'].'</td>';
                          $tabla .='<td style="width: 9%; text-align: left;" bgcolor="#f0f7f0; font-size: 6px;">'.$trimestre[0]['tmed_verif'].'</td>';
                          $tabla .='<td style="width: 7%; text-align: left;" bgcolor="#f0f7f0; font-size: 6px;">'.$trimestre[0]['tprob'].'</td>';
                          $tabla .='<td style="width: 7%; text-align: left;" bgcolor="#f0f7f0; font-size: 6px;">'.$trimestre[0]['tacciones'].'</td>';
                        }
                        else{
                          $tabla .='<td style="width: 5%; text-align: left;" bgcolor="#f0f7f0"></td>';
                          $tabla .='<td style="width: 7%; text-align: left;" bgcolor="#f0f7f0"></td>';
                          $tabla .='<td style="width: 7%; text-align: left;" bgcolor="#f0f7f0"></td>';
                          $tabla .='<td style="width: 7%; text-align: left;" bgcolor="#f0f7f0"></td>';
                        }
                        $temp=$this->temporalizacion_productos($rowp['prod_id']);

                        for ($i=1; $i <=12 ; $i++) { 
                          $tabla.='
                          <td style="width: 3%; text-align: center;font-size: 7px;">
                            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:90%;" align=center>
                              <tr><td style="width:50%;"><b>P:</b></td><td style="width:50%;">'.round($temp[1][$i],2).'</td></tr>
                              <tr><td style="width:50%;"><b>E:</b></td><td style="width:50%;">'.round($temp[4][$i],2).'</td></tr>
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


    /*------ LISTA DE OPERACIONES CONSOLIDADO 2020 -------*/
    function lista_operaciones_consolidado($com_id){
      $tabla='';
      $operaciones=$this->model_producto->list_operaciones($com_id);
      $tabla.=' <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:10%;color:#FFF;">ACTIVIDAD</th>
                    <th style="width:10%;color:#FFF;">RESULTADO</th>
                    <th style="width:10%;color:#FFF;">INDICADOR</th>
                    <th style="width:3%;color:#FFF;">L.B.</th>
                    <th style="width:3%;color:#FFF;">META</th>
                    <th style="width:3.5%;color:#FFF;">PROG.</th>
                    <th style="width:3.5%;color:#FFF;">EJEC.</th>
                    <th style="width:3.5%;color:#FFF;">%EFI</th>
                    <th style="width:5%;color:#FFF;"></th>

                    
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

                </tr>
                </thead>
                <tbody>';
                  $nro=0;
                  foreach($operaciones as $rowp){
                    $programado=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                    $ejecutado=$this->model_producto->suma_total_evaluado($rowp['prod_id']);
                    $prog=0;
                    if(count($programado)!=0){
                      $prog=$programado[0]['meta_gest'];
                    }

                    $ejec=0;
                    if(count($ejecutado)!=0){
                      $ejec=$ejecutado[0]['suma_total'];
                    }

                    $tit='<p style="color:red"><b>NO CUMPLIDO</b></p>';
                    if($ejec==$prog){
                      $tit='<p style="color:green"><b>CUMPLIDO</b></p>';
                    }
                    elseif ($ejec<$prog & $ejec!=0) {
                      $tit='<p style="color:orange"><b>EN PROCESO</b></p>';
                    }

                      $nro++;
                      $tabla .='
                      <tr >
                        <td style="width: 1%; text-align: center; height:50px; font-size: 3px;" title='.$rowp['prod_id'].'>'.$nro.' - '.$rowp['prod_id'].'</td>
                        <td style="width: 2%; text-align: center;">'.$rowp['or_codigo'].'</td>
                        <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_producto'].'</td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                        <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                        <td style="width: 2%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                        <td style="width: 2%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>
                        <td style="width: 2.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($prog,2).'</b></td>
                        <td style="width: 2.5%; text-align: center; font-size: 9px;" bgcolor="#eceaea"><b>'.round($ejec,2).'</b></td>
                        <td style="width: 2.5%; text-align: center; font-size: 9px;" bgcolor="#e9f7e9"><b>'.round((($ejec/$prog)*100),2).'%</b></td>
                        <td style="width: 7%; text-align: left;">'.$tit.'</td>';
                        $temp=$this->temporalizacion_productos($rowp['prod_id']);

                        for ($i=1; $i <=12 ; $i++) { 
                          $tabla.='
                          <td style="width: 3%; text-align: center;font-size: 7px;">
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

    /*----- TITULO SERVICIO OPERACION (2020 - Operaciones) tp:1 (pdf), 2:(Excel) -----*/
    public function cabecera($com_id){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      //$proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $tabla='';
      $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                    <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                  </tr>
                  <tr style="font-size: 8pt;">
                    <td style="width:10%; height: 1.2%"><b>DIR. ADM.</b></td>
                    <td style="width:90%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">
                    <td style="width:10%; height: 1.2%"><b>UNI. EJEC.</b></td>
                    <td style="width:90%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">';
                    $tabla.='<td style="height: 1.2%"><b>';
                      if($proyecto[0]['tp_id']==1){
                        $tabla.='PROY. INV. ';
                      }
                      else{
                        $tabla.=''.$proyecto[0]['tipo_adm'].' ';
                      }
                      $tabla.='</b></td>';
                      if($proyecto[0]['tp_id']==1){
                          $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                      }
                      else{
                          $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                      }
                  $tabla.='
                  </tr>
                  <tr style="font-size: 8pt;">
                    <td style="width:10%; height: 1.2%"><b>';
                      if($proyecto[0]['tp_id']==1){
                        $tabla.='COMPONENTE ';
                      }
                      else{
                        $tabla.='SERVICIO';
                      }
                    $tabla.='
                    </b></td>
                    <td style="width:90%;">: '.strtoupper($componente[0]['com_componente']).'</td>
                  </tr>
              </table>';
      return $tabla;
    }

  
    public function temporalidad_productos_programado($prod_id){
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
          //$matriz[2][$i]=$pa+$producto[0]['prod_linea_base'];
          $matriz[2][$i]=$pa;
          if($producto[0]['prod_meta']!=0){
            $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];
          $ea=$ea+$prod_ejec[0][$mp[$i]];
          //$matriz[5][$i]=$ea+$producto[0]['prod_linea_base'];
          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }

      return $matriz;
    }
    /*--------------------------------------------------------------------------------*/

    /*--- Lista de Gestiones Disponibles ---*/
    public function list_trimestre(){
        $listar_trimestre= $this->model_configuracion->get_mes_trimestre();
        $tmes=$this->model_evaluacion->trimestre();
        $tabla='';

        $tabla.='
                <input type="hidden" name="tmes" id="tmes" value="'.$this->tmes.'">
                <select name="trimestre_usu" id="trimestre_usu" class="form-control" required>
                <option value="0">seleccionar Trimestre</option>'; 
        foreach ($listar_trimestre as $row) {
                if($row['trm_id']!=0 & $row['trm_id']<4){
                    if($row['trm_id']==$tmes[0]['trm_id']){
                        $tabla.='<option value="'.$row['trm_id'].'" select>'.$row['trm_descripcion'].'</option>';
                    }
                    else{
                        $tabla.='<option value="'.$row['trm_id'].'" >'.$row['trm_descripcion'].'</option>';
                    }
                }
        };
        $tabla.='</select>';
        return $tabla;
    }

    /*-------- Valida Cambio trimestre Session -----------*/
    public function valida_update_trimestre(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']); // com id
        $conf=$this->model_proyecto->get_configuracion($this->gestion);

        $data = array(
          'gestion' => $conf[0]['ide'],
          'trimestre' => $this->input->post('trimestre_usu'), /// Trimestre 1,2,3,4
          'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
          'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
          'verif_ppto' => $conf[0]['ppto_poa'] /// Ppto poa : 0 (Vigente), 1: (Aprobado)
        );
        $this->session->set_userdata($data);

        //$this->session->set_flashdata('success','TRIMESTRE CAMBIADO');
        redirect(site_url("").'/eval/eval_gcorriente/'.$com_id.'');
      }
      else{
        redirect('admin/dashboard','refresh');
      }
    }

    /*------------------------------------- MENU -----------------------------------*/
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
    /*============================================================================*/
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

    public function mes_texto($mes){
        switch ($mes) {
            case '1':
                $texto = 'Enero';
                break;
            case '2':
                $texto = 'Febrero';
                break;
            case '3':
                $texto = 'Marzo';
                break;
            case '4':
                $texto = 'Abril';
                break;
            case '5':
                $texto = 'Mayo';
                break;
            case '6':
                $texto = 'Junio';
                break;
            case '7':
                $texto = 'Julio';
                break;
            case '8':
                $texto = 'Agosto';
                break;
            case '9':
                $texto = 'Septiembre';
                break;
            case '10':
                $texto = 'Octubre';
                break;
            case '11':
                $texto = 'Noviembre';
                break;
            case '12':
                $texto = 'Diciembre';
                break;
            default:
                $texto = 'Sin Mes asignado';
                break;
        }
        return $texto;
    }
    /*------------------------------------- ROLES DE USUARIOS ------------------------------*/
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
    /*-------------------------------------------------------------------------------------*/

}