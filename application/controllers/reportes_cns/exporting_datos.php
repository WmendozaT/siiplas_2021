<?php
  class Exporting_datos extends CI_Controller { 
  public function __construct (){ 
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->tmes = $this->session->userData('trimestre');
      }else{
          redirect('/','refresh');
      }
    }
    
    /*----------- TIPO DE REGISTRO ---------*/
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

    /*----- exportar COMPARATIVO PRESUPUESTO ASIG-POA (EXCEL) -----*/
    public function comparativo_presupuesto_xls($dep_id,$tp){
      $data['dep']=$this->model_proyecto->get_departamento($dep_id);
      if(count($data['dep'])!=0){
        $data['tp']=$tp;

        if($tp==1){ /// Unidad Organizacional
          $data['lista']=$this->lista_uo($dep_id,$data['dep']);
        }
        else{ /// Proyecto de Inversion
          $data['lista']=$this->lista_partidas($dep_id);
        }
      }
      else{
        echo "Error!! no existe Region";
      }
    }

    /*------ LISTA UNIDADES ORGANIZACIONALES (EXCEL) -----*/
    public function lista_uo($dep_id,$dep){
      $tabla='';
        $unidades=$this->mrep_operaciones->list_unidades_regional($dep_id); /// Unidades, Proyectos de la Regional
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

        $tabla.='<table><tr><td colspan=7 align=center style="height:50px;font-size: 15pt;"><b>CUADRO COMPARATIVO DE PRESUPUESTO ASIGNADO Vs PROGRAMADO POA '.$this->gestion.'<br>REGIONAL : '.strtoupper($dep[0]['dep_departamento']).'</b></td></tr></table>';
        $tabla.='<table  border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:2%;height:20px;color:#FFF;">#</th>
                    <th style="width:10%;color:#FFF;">CATEGORIA PROGRAM&Aacute;TICA</th>
                    <th style="width:30%;color:#FFF;">UNIDAD, ESTABLECIMIENTO/PROYECTO DE INVERSI&Oacute;N</th>
                    <th style="width:15%;color:#FFF;">TIPO DE OPERACI&Oacute;N</th>
                    <th style="width:10%; color:#FFF;">PRESUPUESTO ASIGNADO '.$this->gestion.'</th> 
                    <th style="width:10%; color:#FFF;">PRESUPUESTO PROGRAMADO '.$this->gestion.'</th>
                    <th style="width:10%; color:#FFF;">DIFERENCIA</th>  
                  </tr>
                </thead>
                <tbody>';
                $nro=0; $monto_total_asig=0; $monto_total_poa=0; $monto_total_dif=0;
                foreach($unidades as $row){
                  $m_asig=0;$m_poa=0;$dif=0;$color='';
                  $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($row['aper_id'],1);
                  if(count($monto_asig)!=0){
                    $m_asig=$monto_asig[0]['monto'];
                  }
                  
                  if($row['tp_id']==1){ /// Proyecto de Inversion
                    $monto_poa=$this->model_ptto_sigep->suma_ptto_pinversion($row['proy_id']);
                  }
                  else{ /// Gasto Corriente
                    $monto_poa=$this->model_ptto_sigep->suma_ptto_accion($row['aper_id'],2);
                  }

                  if(count($monto_poa)!=0){
                    $m_poa=$monto_poa[0]['monto'];
                  }

                  $dif=$m_asig-$m_poa;
                  if($dif<0){
                    $color='#f9cdcd';
                  }

                  $nro++;
                  $monto_total_asig=$monto_total_asig+$m_asig;
                  $monto_total_poa=$monto_total_poa+$m_poa;
                  $monto_total_dif=$monto_total_asig-$monto_total_poa;
                  $tabla.='<tr bgcolor="'.$color.'">';
                    $tabla.='<td style="width: 2%; text-align: center; height:15px;"><b>'.$nro.'</b></td>
                              <td style="width: 10%; text-align: center;">\''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'\'</td>';
                              if($row['tp_id']==1){
                                $tabla.='<td style="width: 30%; text-align: left;">'.mb_convert_encoding(strtoupper($row['proy_nombre']), 'cp1252', 'UTF-8').'</td>';
                              }
                              else{
                                $tabla.='<td style="width: 30%; text-align: left;">'.mb_convert_encoding(strtoupper($row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev']), 'cp1252', 'UTF-8').'</td>';
                              }
                              $tabla.='
                              
                              <td style="width: 15%; text-align: left;">'.mb_convert_encoding(strtoupper($row['tp_tipo']), 'cp1252', 'UTF-8').'</td>
                              <td style="width: 10%; text-align: right;">'.round($m_asig,2).'</td>
                              <td style="width: 10%; text-align: right;">'.round($m_poa,2).'</td>
                              <td style="width: 10%; text-align: right;">'.round($dif,2).'</td>';
                  $tabla.='</tr>';
                }
        $tabla.='</tbody>
                  <tr>
                    <td colspan="4"><b>PRESUPUESTO TOTAL : </b></td>
                    <td style="text-align: right;height:15px;">'.round($monto_total_asig,2).'</td>
                    <td style="text-align: right;">'.round($monto_total_poa,2).'</td>
                    <td style="text-align: right;">'.round($monto_total_dif,2).'</td>
                  </tr>
              </table>';

          date_default_timezone_set('America/Lima');
          header('Content-type: application/vnd.ms-excel');
          header("Content-Disposition: attachment; filename=Ptto_Comparativo_Regional.xls"); //Indica el nombre del archivo resultante
          header("Pragma: no-cache");
          header("Expires: 0");
          echo "";
          echo $tabla;
    }



    /*------ EXPORTAR FORM 2 LISTA DE OPERACIONES POR DISTRITAL (2020-2021) -------*/
    public function operaciones_distrital($dist_id,$tp_id){

      $dist=$this->model_proyecto->dep_dist($dist_id);
      $distrital = strtoupper($dist[0]['dist_distrital']);
      if(count($dist)!=0){
        if($this->gestion==2019){
          //$tabla=$this->lista_operaciones_por_regional_distrital($dist_id,2,$tp_id); // Distrital Operaciones 2019
          $tabla='No disponible';
        }
        else{
          $dist=$this->model_proyecto->dep_dist($dist_id);
          $titulo='DISTRITAL : '.mb_convert_encoding($dist[0]['dist_distrital'], 'cp1252', 'UTF-8').' - '.$this->gestion.'';
          $operaciones=$this->mrep_operaciones->operaciones_por_distritales($dist_id,$tp_id); /// Operaciones a Nivel de distritales
          $tabla=$this->lista_operaciones_regional_distrital($operaciones,$titulo); // Regional Operaciones Distrital 2020-2021
        }
        
        date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=CONSOLIDADO_OPERACIONES_DISTRITAL_".$distrital."_$fecha.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $tabla;
      }
      else{
        echo "Error !!! ";
      }
    }

     /*----- LISTA DE OPERACIONES POR DISTRITAL (2020-2021) ----*/
    public function lista_operaciones_regional_distrital($operaciones,$titulo){
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
                  <td colspan=45 align=center style="height:50px;"><b> CONSOLIDADO OPERACIONES '.strtoupper($titulo).'</b></td>
                </tr>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%; height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">ACTIVIDAD</th>
                  <th style="width:3%;background-color: #eceaea;">COD. SUBACT.</th>
                  <th style="width:15%;background-color: #eceaea;">SUBACTIVIDAD</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACP.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OR.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:25%;background-color: #eceaea;">OPERACI&Oacute;N</th>
                  <th style="width:15%;background-color: #eceaea;">RESULTADO</th>
                  <th style="width:15%;background-color: #eceaea;">INDICADOR</th>
                  <th style="width:5%;background-color: #eceaea;">LINEA BASE</th>
                  <th style="width:5%;background-color: #eceaea;">META</th>
                  <th style="width:15%;background-color: #eceaea;">MEDIO DE VERIFICACIÓN</th>
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
                  <th style="width:6%;background-color: #eceaea;">PRESUPUESTO POA</th>
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
            foreach ($operaciones as $row){
              $monto=$this->model_producto->monto_insumoproducto($row['prod_id']);
              $programado=$this->model_producto->producto_programado($row['prod_id'],$this->gestion);
              $programado=$this->model_producto->producto_programado($row['prod_id'],$this->gestion);
              $ejec=$this->model_producto->producto_ejecutado($row['prod_id'],$this->gestion);
              
              $ptto=0;
              if(count($monto)!=0){
                $ptto=$monto[0]['total'];
              }
              $nro++;

               $tabla.='<tr>';
                $tabla.='<td style="height:50px;">\''.strtoupper($row['dep_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['dist_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['aper_prog']).'\'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.='\''.strtoupper($row['aper_proy']).'\'';
                }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['aper_act']).'\'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['serv_cod']).'\'</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                $tabla.='<td>'.$row['acc_codigo'].'</td>';
                $tabla.='<td>'.$row['og_codigo'].'</td>';
                $tabla.='<td>'.$row['or_codigo'].'</td>';
                $tabla.='<td>'.$row['prod_cod'].'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_producto'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_resultado'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_indicador'], 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.round($row['prod_linea_base'],2).'</td>';
                $tabla.='<td>'.round($row['prod_meta'],2).'</td>';
                $tabla.='<td>'.mb_convert_encoding($row['prod_fuente_verificacion'], 'cp1252', 'UTF-8').'</td>';
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
                        $tabla.='<td bgcolor="#f5cace">0.00</td>';
                      }
                    }

                $tabla.='<td style="width: 5%; text-align: right;">'.round($ptto,2).'</td>';

                if(count($ejec)!=0){
                  $tabla.='
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['enero'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['febrero'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['marzo'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['abril'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['mayo'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['junio'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['julio'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['agosto'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['septiembre'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['octubre'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['noviembre'].'</b></td>
                  <td bgcolor="#d2f5f0"><b>'.$ejec[0]['diciembre'].'</b></td>';
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

    /*------ EXPORTAR FORM 2 LISTA DE OPERACIONES POR REGIONAL (2020-2021) -------*/
    public function operaciones_regional($dep_id,$tp_id){

      $dep=$this->model_proyecto->get_departamento($dep_id);
      if(count($dep)!=0){
        $titulo='REGIONAL : '.mb_convert_encoding($dep[0]['dep_departamento'], 'cp1252', 'UTF-8').' - '.$this->gestion.'';
        $operaciones=$this->mrep_operaciones->consolidado_operaciones_regionales($dep_id,$tp_id); /// Operaciones a Nivel de Regionales
        $tabla=$this->lista_operaciones_regional_regional($operaciones,$titulo,$tp_id); // Regional Operaciones Distrital 2020-2021

        date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=CONSOLIDADO_OPERACIONES_REGIONAL_".$dep[0]['dep_departamento']."_$fecha.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $tabla;
      }
      else{
        echo "Error !!! ";
      }
    }

     /*----- LISTA DE OPERACIONES POR REGIONAL (2020-2021) ----*/
    public function lista_operaciones_regional_regional($operaciones,$titulo,$tp_id){
        $titulo_sub='UNIDAD RESPONSABLE';
        if($tp_id==4){
          $titulo_sub='SUBACTIVIDAD';
        }

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
                  <td colspan=31 align=center style="height:50px;"><b> CONSOLIDADO OPERACIONES '.strtoupper($titulo).'</b><br></td>
                </tr>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%; height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">ACTIVIDAD</th>
                  <th style="width:3%;background-color: #eceaea;">COD. SUBACT.</th>
                  <th style="width:15%;background-color: #eceaea;">'.$titulo_sub.'</th>';
                  if($tp_id==1){
                    $tabla.='<th style="width:10%;background-color: #eceaea;">COMPONENTE</th>';
                  }
                  $tabla.='
                  <th style="width:3%;background-color: #eceaea;">COD. ACE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACP.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OR.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:25%;background-color: #eceaea;">OPERACI&Oacute;N</th>
                  <th style="width:15%;background-color: #eceaea;">RESULTADO</th>
                  <th style="width:15%;background-color: #eceaea;">INDICADOR</th>
                  <th style="width:5%;background-color: #eceaea;">LINEA BASE</th>
                  <th style="width:5%;background-color: #eceaea;">META</th>
                  <th style="width:15%;background-color: #eceaea;">MEDIO DE VERIFICACIÓN</th>
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
                  <th style="width:6%;background-color: #eceaea;">PRESUPUESTO POA</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($operaciones as $row){
              $monto=$this->model_producto->monto_insumoproducto($row['prod_id']);
              $programado=$this->model_producto->producto_programado($row['prod_id'],$this->gestion);
              
              $ptto=0;
              if(count($monto)!=0){
                $ptto=$monto[0]['total'];
              }
              $nro++;

               $tabla.='<tr>';
                $tabla.='<td style="height:50px;">\''.strtoupper($row['dep_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['dist_cod']).'\'</td>';
                $tabla.='<td>\''.strtoupper($row['aper_prog']).'\'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.='\''.strtoupper($row['aper_proy']).'\'';
                }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['aper_act']).'\'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
                $tabla.='<td>\''.strtoupper($row['serv_cod']).'\'</td>';
                $tabla.='<td>'.$row['serv_cod'].' '.strtoupper($row['tipo_subactividad']).' '.mb_convert_encoding($row['serv_descripcion'], 'cp1252', 'UTF-8').'</td>';
                if($tp_id==1){
                  $tabla.='<td>'.$row['com_componente'].'</td>';
                }
                $tabla.='<td>'.$row['acc_codigo'].'</td>';
                $tabla.='<td>'.$row['og_codigo'].'</td>';
                $tabla.='<td>'.$row['or_codigo'].'</td>';
                $tabla.='<td>'.$row['prod_cod'].'</td>';
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
                $tabla.='<td style="width: 5%; text-align: right;">'.round($ptto,2).'</td>';
            $tabla.='</tr>';
            }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }

    /// --------------------------------------------------------

    /*--- FORM 3 CONSOLIDADO REQUERIMIENTOS (PROG) POR DISTRITAL (2020 - 2021) ---*/
    public function requerimientos_distrital($dist_id,$tp_id){
      $dist=$this->model_proyecto->dep_dist($dist_id);
      date_default_timezone_set('America/Lima');
      $fecha = date("d-m-Y H:i:s");

      if($this->gestion==2019){
        //$requerimientos=$this->mis_requerimientos_regionales_distritales($dist_id,2,$tp_id); /// Gestion 2019
        $tabla='No disponible';
      }
      else{
        $titulo='CONSOLIDADO REQUERIMIENTOS : DISTRITAL - '.mb_convert_encoding(strtoupper($dist[0]['dist_distrital']), 'cp1252', 'UTF-8').' '.$this->gestion.'';
        $requerimientos=$this->mrep_operaciones->consolidado_requerimientos_regional_distrital(1, $dist_id, $tp_id); /// Consolidado Requerimientos 2020-2021
        $tabla=$this->lista_requerimientos_regional_distrital($requerimientos,$titulo); // Requerimientos Distrital 2020-2021
      }


      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Consolidado_Requerimiento_".$dist[0]['dist_distrital']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      ini_set('max_execution_time', 0); 
      ini_set('memory_limit','3072M');
      echo $tabla;
    }

     /*----- LISTA DE REQUERIMIENTOS DISTRITAL (2020-2021) ----*/
    public function lista_requerimientos_regional_distrital($requerimientos,$titulo){
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
                  <td colspan=29 align=center style="height:50px;"><b> CONSOLIDADO REQUERIMIENTO '.strtoupper($titulo).'</b></td>
                </tr>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%;height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">DESCRIPCI&Oacute;N</th>
                  <th style="width:3%;background-color: #eceaea;">COD. SUBACT.</th>
                  <th style="width:15%;background-color: #eceaea;">SUBACTIVIDAD</th>
                  <th style="width:3%;background-color: #eceaea;">COD. OPE.</th>
                  <th style="width:25%;background-color: #eceaea;">OPERACI&Oacute;N</th>
                  <th style="width:15%;background-color: #eceaea;">PARTIDA</th>
                  <th style="width:25%;background-color: #eceaea;">REQUERIMIENTO</th>
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
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($requerimientos as $row){
              $prog="'".$row['aper_prog']."'";
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                $tabla.='<td>'.$row['dist_cod'].'</td>';
                $tabla.='<td>'.$prog.'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=$row['proy_sisin'];
                }
                else{
                  $tabla.="'".$row['aper_proy']."'";
                }
                $tabla.='</td>';
                $tabla.='<td>'."'".$row['aper_act']."'".'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'."'".$row['serv_cod']."'".'</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.mb_convert_encoding(strtoupper($row['serv_descripcion']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.$row['prod_cod'].'</td>';
                $tabla.='<td>'.mb_convert_encoding(strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.$row['par_codigo'].'</td>';
                $tabla.='<td>'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td align="right">'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td align="right">'.round($row['ins_costo_unitario'],2).'</td>';
                $tabla.='<td align="right">'.round($row['ins_costo_total'],2).'</td>';
                $tabla.='<td align="right" bgcolor="#c1f5ee"><b>'.round($row['ins_monto_certificado'],2).'</b></td>';
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:3%;">'.round($row['mes'.$i],2).'</td>';
                }
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>';
            $tabla.='</tr>';
          }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }


     /*--- FORM 3 CONSOLIDADO REQUERIMIENTOS (PROG) POR REGIONAL (2020 - 2021) ---*/
    public function requerimientos_regional($dep_id,$tp_id){
      $tipo='GASTO CORRIENTE';
      if($tipo==4){
        $tipo='PROYECTO DE INVERSION';
      }
      $dep=$this->model_proyecto->get_departamento($dep_id);
      date_default_timezone_set('America/Lima');
      $fecha = date("d-m-Y H:i:s");

      $titulo='CONSOLIDADO REQUERIMIENTOS : REGIONAL - '.mb_convert_encoding(strtoupper($dep[0]['dep_departamento']), 'cp1252', 'UTF-8').' '.$this->gestion.' ('.$tipo.')';
      $requerimientos=$this->mrep_operaciones->consolidado_directo_requerimientos_regional($dep_id, $tp_id); /// Consolidado Requerimientos 2020-2021
      $tabla=$this->lista_requerimientos_regional($requerimientos,$titulo,$tp_id); // Requerimientos Regional 2020-2021

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Consolidado_Requerimiento_".$dep[0]['dep_departamento']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      ini_set('max_execution_time', 0); 
      ini_set('memory_limit','3072M');
      echo $tabla;
    }

     /*----- LISTA DE REQUERIMIENTOS REGIONAL (2020-2021) ----*/
    public function lista_requerimientos_regional($requerimientos,$titulo,$tp_id){
        $tit='ACTIVIDAD';
        if($tp_id==1){
          $tit='PROYECTO DE INVERSION';
        }

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
                  <td colspan=25 align=center style="height:50px;"><b>'.strtoupper($titulo).'</b></td>
                </tr>
                <tr style="background-color: #66b2e8">
                  <th style="width:3%;height:50px;background-color: #eceaea;">COD. DA.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. UE.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. PROG.</th>
                  <th style="width:10%;background-color: #eceaea;">COD. PROY.</th>
                  <th style="width:3%;background-color: #eceaea;">COD. ACT.</th>
                  <th style="width:35%;background-color: #eceaea;">'.$tit.'</th>
                  <th style="width:15%;background-color: #eceaea;">PARTIDA</th>
                  <th style="width:25%;background-color: #eceaea;">REQUERIMIENTO</th>
                  <th style="width:10%;background-color: #eceaea;">UNIDAD DE MEDIDA</th>
                  <th style="width:5%;background-color: #eceaea;">CANTIDAD</th>
                  <th style="width:5%;background-color: #eceaea;">PRECIO</th>
                  <th style="width:15%;background-color: #eceaea;">COSTO TOTAL</th>
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
                  <th style="width:10%;background-color: #eceaea;">OBSERVACIÓN</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach ($requerimientos as $row){
              $prog=$row['aper_programa'];
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                $tabla.='<td>'.$row['dist_cod'].'</td>';
                $tabla.='<td>'.$prog.'</td>';
                $tabla.='<td>';
                if($row['tp_id']==1){
                  $tabla.=$row['proy_sisin'];
                }
                else{
                  $tabla.="'".$row['aper_proyecto']."'";
                }
                $tabla.='</td>';
                $tabla.='<td>'."'".$row['aper_actividad']."'".'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['par_codigo'].'</td>';
                $tabla.='<td>'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td>'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td>'.round($row['ins_costo_unitario'],2).'</td>';
                $tabla.='<td>'.round($row['ins_costo_total'],2).'</td>';

                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes1'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes2'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes3'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes4'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes5'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes6'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes7'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes8'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes9'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes10'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes11'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mes12'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>';
            $tabla.='</tr>';
          }

            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }
    /// ---------------------------------------------------

    /*--- FORM 4 EJECUCION PRESUPUESTARIA (PROG-CERT) POR SUBACTIVIDAD (2020 - 2021) EXCEL ---*/
    public function requerimientos_servicio($com_id){
      date_default_timezone_set('America/Lima');
      $fecha = date("d-m-Y H:i:s");

      if($this->gestion==2019){
        $tabla='No disponible';
      }
      else{
        $requerimientos=$this->mrep_operaciones->lista_insumo_subactividad($com_id); /// Lista de requerimientos certificados por Subactividad (2020-2021)
        $tabla=$this->lista_ejecucion_requerimientos_subactividad($requerimientos,$com_id); // Requerimientos Distrital 2020-2021
      }


      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Consolidado_Ejecucion_Requerimiento_".$distrital."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      ini_set('max_execution_time', 0); 
      ini_set('memory_limit','3072M');
      echo $tabla;
    }

     /*----- LISTA DE REQUERIMIENTOS SUBACTIVIDAD (2020-2021) ----*/
    public function lista_ejecucion_requerimientos_subactividad($requerimientos,$com_id){
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto=$this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
        $tit='PROYECTO DE INVERSI&Oacute;N';
        $tit_proy=''.$proyecto[0]['aper_prog'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_act'].' '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $tit_proy=''.$proyecto[0]['aper_prog'].' '.$proyecto[0]['aper_proy'].' '.$proyecto[0]['aper_act'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
          $tit='ACTIVIDAD';
        }

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
          <table border="0" cellpadding="0" cellspacing="0" class="tabla">
            <tr class="modo1">
              <td colspan=2 align=left style="height:50px;">
                <b> DA : </b> '.$proyecto[0]['dep_cod'].' .-'.strtoupper($proyecto[0]['dep_departamento']).'<br>
                <b> UE : </b> '.$proyecto[0]['dist_cod'].' .-'.strtoupper($proyecto[0]['dist_distrital']).'<br>
                <b> '.$tit.' : </b> '.$tit_proy.'<br>
                <b> SUBACTIVIDAD : </b> '.$componente[0]['serv_cod'].' '.$componente[0]['serv_descripcion'].'<br>
              </td>
            </tr>
          </table><br>
          <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                 <tr style="background-color: #66b2e8">
                    <th style="width:2%;height:50px;background-color: #eceaea;">COD. OPE.</th>
                    <th style="width:2%;background-color: #eceaea;">PARTIDA</th>
                    <th style="width:20%;background-color: #eceaea;">REQUERIMIENTO</th>
                    <th style="width:5%;background-color: #eceaea;">UNIDAD DE MEDIDA</th>
                    <th style="width:3%;background-color: #eceaea;">CANTIDAD</th>
                    <th style="width:5%;background-color: #eceaea;">PRECIO</th>
                    <th style="width:5%;background-color: #eceaea;">COSTO TOTAL</th>
                    <th style="width:5%;background-color: #eceaea;">TOTAL CERTIFICADO</th>
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
                    <th style="width:5%;background-color: #eceaea;">OBSERVACI&Oacute;N</th>
                  </tr>
              </thead>
            <tbody>';
            $nro=0;$sum_programado=0;$sum_certificado=0;
            foreach ($requerimientos as $row){
              $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
              $monto_certificado=0;$color='';
              $m_cert=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']); /// Monto Certificado
                if(count($m_cert)!=0){
                  $monto_certificado=$m_cert[0]['certificado'];
                }
           
              $nro++;
              $tabla.='<tr>';
                  $tabla.='<td style="height:50px;" align=center><b>'.$row['prod_cod'].'</b></td>';
                  $tabla.='<td>'.$row['par_codigo'].'</td>';
                  $tabla.='<td>'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                  $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                  $tabla.='<td>'.round($row['ins_cant_requerida'],2).'</td>';
                  $tabla.='<td>'.round($row['ins_costo_unitario'],2).'</td>';
                  $tabla.='<td>'.round($row['ins_costo_total'],2).'</td>';
                  $tabla.='<td style="width:5%;" bgcolor="#c1f5ee" align=right><b>'.round($monto_certificado,2).'</b></td>';
                        if(count($prog)!=0){
                          if($monto_certificado==$prog[0]['programado_total']){
                            for ($i=1; $i<=12 ; $i++) {
                            $tabla.='<td style="width:4%;" align=right bgcolor="#ddf7dd">'.round($prog[0]['mes'.$i],2).'</td>';
                            }
                          }
                          else{
                            for ($i=1; $i<=12 ; $i++) {
                              $color_td='';
                              $mes_cert=$this->model_certificacion->get_insumo_programado_certificado_mes($row['ins_id'],$i);
                              if(count($mes_cert)!=0){
                                $color_td='#ddf7dd';
                              }
                              $tabla.='<td style="width:4%;" align=right bgcolor='.$color_td.'>'.round($prog[0]['mes'.$i],2).'</td>';
                            }
                          }
                        }
                        else{
                          for ($i=1; $i<=12 ; $i++) {
                            $tabla.='<td style="width:4%;" align=right><font color=red><b>0</b></font></td>';
                          }
                        }
                  $tabla.='
                    <td style="width:5%;">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>
                  </tr>';
                  $sum_programado=$sum_programado+$prog[0]['programado_total'];
                  $sum_certificado=$sum_certificado+$monto_certificado;
            }

            $tabla.='
            </tbody>
            <tr>
              <td colspan=6 style="height:30px;"></td>
              <td align=right>'.round($sum_programado,2).'</td>
              <td align=right>'.round($sum_certificado,2).'</td>
              <td colspan=13></td>
            </tr>
        </table>';

      return $tabla;
    }

  /// ---------------------------------------------


    /*--- FORM 4 EJECUCION PRESUPUESTARIA (PROG-CERT) POR SUBACTIVIDAD (2020 - 2021) PDF ---*/
    public function rep_ejecucion_requerimientos_servicio($com_id){
      if($this->gestion==2019){
        echo 'No disponible';
      }
      else{
        $requerimientos=$this->mrep_operaciones->lista_insumo_subactividad($com_id); /// Lista de requerimientos certificados por Subactividad (2020-2021)
        $data['componente']=$this->model_componente->get_componente($com_id,$this->gestion);
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto']=$this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
        $data['mes'] = $this->mes_nombre();
        $data['cabecera']=$this->cabecera($data['componente'],$data['proyecto'],1); /// Cabecera
        $data['requerimientos']=$this->rep_lista_ejecucion_requerimientos_subactividad($requerimientos,$com_id); // Requerimientos Distrital 2020-2021
       // $data['ejecucion']=$this->ejecucion_presupuestaria_acumulado_total($com_id);
        //$data['ejecucion']=$this->ejecucion_presupuestaria_acumulado($com_id); /// anterior
        $this->load->view('admin/reportes_cns/programacion_poa/reporte_poa_form5', $data);
      
      }
    }

    /*----- EJECUCION POA REQUERIMIENTOS SUBACTIVIDAD (2020-2021) PDF----*/
    public function rep_lista_ejecucion_requerimientos_subactividad($requerimientos,$com_id){
        $tabla='';
        $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
              <thead>
               <tr style="font-size: 7px;" bgcolor="#1c7368" align=center>
                    <th style="width:2%;height:18px;color:#FFF;">COD. OPE.</th>
                    <th style="width:3.5%;color:#FFF;">PARTIDA</th>
                    <th style="width:15%;color:#FFF;">DETALLE REQUERIMIENTO</th>
                    <th style="width:5%;color:#FFF;">UNIDAD</th>
                    <th style="width:4.3%;color:#FFF;">CANTIDAD</th>
                    <th style="width:4.5%;color:#FFF;">UNITARIO</th>
                    <th style="width:5.2%;color:#FFF;">TOTAL PROGRAMADO</th>
                    <th style="width:5.2%;color:#FFF;">MONTO CERTIFICADO</th>
                    <th style="width:4%;color:#FFF;">ENE.</th>
                    <th style="width:4%;color:#FFF;">FEB.</th>
                    <th style="width:4%;color:#FFF;">MAR.</th>
                    <th style="width:4%;color:#FFF;">ABR.</th>
                    <th style="width:4%;color:#FFF;">MAY.</th>
                    <th style="width:4%;color:#FFF;">JUN.</th>
                    <th style="width:4%;color:#FFF;">JUL.</th>
                    <th style="width:4%;color:#FFF;">AGO.</th>
                    <th style="width:4%;color:#FFF;">SEPT.</th>
                    <th style="width:4%;color:#FFF;">OCT.</th>
                    <th style="width:4%;color:#FFF;">NOV.</th>
                    <th style="width:4%;color:#FFF;">DIC.</th>
                    <th style="width:8%;color:#FFF;">OBSERVACI&Oacute;N</th>  
                </tr>
              </thead>
            <tbody>';
            $nro=0;$sum_programado=0;$sum_certificado=0;
            if(count($requerimientos)>150){ /// items mayores a 150 sin color de marcado

                  foreach ($requerimientos as $row){
                    $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                    $nro++;
                    $tabla.='<tr>';
                      $tabla.='<td style="width: 2%; font-size: 8px; text-align: center;height:13px;"><b>'.$row['prod_cod'].'</b></td>';
                      $tabla.='<td style="width: 3.5%; text-align: center;font-size: 8px;" bgcolor="#eceaea">'.$row['par_codigo'].'</td>';
                      $tabla.='<td style="width: 15%; text-align: left;font-size: 7.2px;">'.strtoupper($row['ins_detalle']).'DDDD</td>';
                      $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                      $tabla.='<td style="width: 4.3%; text-align: right;font-size: 7.5px;">'.round($row['ins_cant_requerida'],2).'</td>';
                      $tabla.='<td style="width: 4.5%; text-align: right;font-size: 7.5px;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                      $tabla.='<td style="width: 5.2%; text-align: right;font-size: 7.5px;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                      $tabla.='<td style="width: 5.2%;" bgcolor="#c1f5ee" align=right><b>'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</b></td>';
                      if(count($prog)!=0){
                        for ($i=1; $i<=12 ; $i++) {
                            $tabla.='<td style="width:4%;" align=right >'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                          }
                      }
                      else{
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:4%;" align=right bgcolor="red">'.number_format(0, 2, ',', '.').'</td>';
                        }
                      }

                      $tabla.='
                        <td style="width:5%;">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>
                      </tr>';
                      $sum_programado=$sum_programado+$prog[0]['programado_total'];
                      $sum_certificado=$sum_certificado+$row['ins_monto_certificado'];  
                  }

            }
            else{
              
                foreach ($requerimientos as $row){
                  $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  $nro++;
                  $tabla.='<tr>';
                    $tabla.='<td style="width: 2%; font-size: 8px; text-align: center;height:13px;"><b>'.$row['prod_cod'].'</b></td>';
                    $tabla.='<td style="width: 3.5%; text-align: center;font-size: 8px;" bgcolor="#eceaea">'.$row['par_codigo'].'</td>';
                    $tabla.='<td style="width: 15%; text-align: left;font-size: 7.2px;">'.strtoupper($row['ins_detalle']).'DDDD</td>';
                    $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                    $tabla.='<td style="width: 4.3%; text-align: right;font-size: 7.5px;">'.round($row['ins_cant_requerida'],2).'</td>';
                    $tabla.='<td style="width: 4.5%; text-align: right;font-size: 7.5px;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla.='<td style="width: 5.2%; text-align: right;font-size: 7.5px;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                    $tabla.='<td style="width: 5.2%;" bgcolor="#c1f5ee" align=right><b>'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</b></td>';
                    if(count($prog)!=0){
                      if($prog[0]['programado_total']==$row['ins_monto_certificado']){
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:4%;" align=right bgcolor="#ddf7dd">'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                        }
                      }
                      elseif($prog[0]['programado_total']>$row['ins_monto_certificado']){
                          for ($i=1; $i<=12 ; $i++) {
                            $mes=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                            $color='';
                            if(count($mes)==1){
                              if($mes[0]['estado_cert']==1){
                                $color='#ddf7dd';
                              }
                            }
                            
                            $tabla.='<td style="width:4%;" align=right bgcolor="'.$color.'">'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                          }
                      }
                      elseif($row['ins_monto_certificado']==0){
                        $tabla.='<td style="width:4%;" align=right>'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                      }
                    }
                    else{
                      for ($i=1; $i<=12 ; $i++) {
                        $tabla.='<td style="width:4%;" align=right bgcolor="red">'.number_format(0, 2, ',', '.').'</td>';
                      }
                    }

                    $tabla.='
                      <td style="width:5%;">'.mb_convert_encoding(strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>
                    </tr>';
                    $sum_programado=$sum_programado+$prog[0]['programado_total'];
                    $sum_certificado=$sum_certificado+$row['ins_monto_certificado'];  
                }
            }


            $ejecucion=0;
            if($sum_certificado!=0){
              $ejecucion=round((($sum_certificado/$sum_programado)*100),2);
            }

            $tabla.='
            </tbody>
            <tr>
              <td colspan=6 style="height:20px;"></td>
              <td align=right>'.number_format($sum_programado, 2, ',', '.').'</td>
              <td align=right>'.number_format($sum_certificado, 2, ',', '.').'</td>
              <td colspan=13> &nbsp;&nbsp;EJECUCI&Oacute;N POA '.$this->gestion.' : <b>'.$ejecucion.'%</b></td>
            </tr>
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

      if($this->gestion>=2020){
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
      }

      return $tabla;
    }






    /*------- Ejecucion presupuestaria al trimestre (a borrar) --------*/
    public function ejecucion_presupuestaria_acumulado($com_id){
      $tabla='';
      $monto_total=0;
      $ppto_total=$this->model_evaluacion->suma_ppto_programado_trimestre($com_id); // Prog. al trimestre
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

      if($this->gestion>=2020){
      $tabla.='
        <div align=center>
        programado al trimestre : '.$monto_total.'-- monto partida defecto '.$monto_partida.'--- monto certificado '.$monto_certificado.'
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
          <thead>
          <tr>
            <th style="width:10%; height:18px;"></th>';
            for ($i=1; $i <=$this->tmes ; $i++) {
              $trimestre=$this->model_evaluacion->get_trimestre($i);
              $tabla.='<th style="width:12%;">'.$trimestre[0]['trm_descripcion'].'</th>';
            }
          
        $tabla.='
            <th style="width:12%;">TOTAL N° CERT. POA</th>
            <th style="width:12%;">MONTO EJECUTADO</th>
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

    /*----- TITULO DEL REPORTE tp:1 (pdf), 2:(Excel) 2021 -----*/
    public function cabecera($componente,$proyecto,$tp){
      $tabla='';
      $tabla.=' <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
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
                            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
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
    /*----------------------------------------------------------------------------------------*/






  /*---- FORMULARIO 5 VER PROGRAMADO POA POR OBJETIVO REGIONAL (DISTRITAL) 2020-2021 ----*/
  public function ver_poa_oregional_distrital($dist_id,$tp_id){
    $distrital=$this->model_proyecto->dep_dist($dist_id);
    $unidades=$this->model_proyecto->lista_operaciones_oregional_distrital($dist_id,$tp_id);
    $tabla='';
    $sum_ope=0;

    $titulo=mb_convert_encoding('PROYECTOS DE INVERSI&Oacute;N', 'cp1252', 'UTF-8');
    if($tp_id==4){
      $titulo=mb_convert_encoding('GASTO CORRIENTE', 'cp1252', 'UTF-8');
    }

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

    $tabla.='<table table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:50%;">
                <tr style="height:50px;">
                  <th align=center colspan=7><b>CONSOLIDADO DISTRITAL DE OPERACIONES POR OBJETIVO REGIONAL ('.$titulo.' - '.$this->gestion.')<br>'.strtoupper($distrital[0]['dist_distrital']).'</b></th>
                </tr>
                <tr align=center>
                  <th style="width:1%; height:30px; background-color: #eceaea;">N°</th>
                  <th style="width:5%; background-color: #eceaea;">COD. OBJ. REGIONAL</th>
                  <th style="width:20%; background-color: #eceaea;">DESCRIPCI&Oacute;N OBJ. REGIONAL</th>
                  <th style="width:10%; background-color: #eceaea;">TIPO DE ADMINISTRACI&Oacute;N</th>
                  <th style="width:30%; background-color: #eceaea;">'.$titulo.'</th>
                  <th style="width:5%; background-color: #eceaea;">Nro. OPERACIONES</th>
                  <th style="width:10%; background-color: #eceaea;">PPTO. PROGRAMADO</th>
                </tr>';
                $nro=0;$suma_monto=0;
                foreach($unidades as $uni){
                  $ppto=$this->model_ptto_sigep->suma_ptto_accion($uni['aper_id'],2);
                  $monto=0;
                  if(count($ppto)!=0){
                    $monto=$ppto[0]['monto'];
                  }

                $nro++;
                $tabla.='<tr>
                          <td style="width:1%; height:25px;" align=center>'.$nro.'</td>
                          <td align=center><b>'.strtoupper($uni['or_codigo']).'<b></td>
                          <td><b>'.mb_convert_encoding(strtoupper($uni['or_objetivo']), 'cp1252', 'UTF-8').'<b></td>
                          <td>'.$uni['dist_cod'].' .-'.strtoupper($uni['dist_distrital']).'</td>';
                            if($tp_id==1){
                              $tabla.='<td>'.$uni['aper_programa'].' '.$uni['proy_sisin'].' '.$uni['aper_proyecto'].' - '.mb_convert_encoding(strtoupper($uni['proy_nombre']), 'cp1252', 'UTF-8').'</td>';
                            }
                            else{
                              $tabla.='<td>'.mb_convert_encoding(strtoupper($uni['tipo'].' '.$uni['act_descripcion'].' '.$uni['abrev']), 'cp1252', 'UTF-8').'</td>';  
                            }
                            
                          $tabla.='
                          <td align=right>'.$uni['operaciones'].'</td>
                          <td align=right>'.round($monto,2).'</td>
                        </tr>';
                $sum_ope=$sum_ope+$uni['operaciones'];
                $suma_monto=$suma_monto+$monto;
                }

        $tabla.='
          <tr>
            <td colspan=5 style="height:25px;"><b>TOTAL</b></td>
            <td align=right>'.$sum_ope.'</td>
            <td align=right>'.$suma_monto.'</td>
          </tr>
        </table>';


      date_default_timezone_set('America/Lima');
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Alineacion_poa_or_".$distrital[0]['dist_distrital'].".xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo $tabla;
  }
  /// ----------------------------------------------------------------
















    /*-- MIS REQUERIMIENTOS PROGRAMADOS - CERTIFICADOS POR UNIDAD (2019) --*/
    /*public function mis_requerimientos_unidad2019($proyecto){
      $tabla='';
      $requerimientos=$this->mrep_operaciones->list_requerimientos($proyecto[0]['proy_id'],$proyecto[0]['tp_id']);
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
      $tabla .='
        <table border="1" cellpadding="0" cellspacing="0" class="tabla">
          <thead>
              <tr class="modo1">
                <th align=center colspan=31 style="height:25px;">'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</th>
              </tr>
              <tr class="modo1">
                <th style="width:7%; height:25px; background-color: #1c7368; color: #FFFFFF" title="#">#</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF" title="SERVICIO">SERVICIO / COMPONENTE</th>
                <th style="width:2%; background-color: #1c7368; color: #FFFFFF" title="">COD.OE.</th>
                <th style="width:2%; background-color: #1c7368; color: #FFFFFF" title="">COD.ACP.</th>
                <th style="width:2%; background-color: #1c7368; color: #FFFFFF" title="">COD.OPE.</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF" title="">OPERACI&Oacute;N</th>
                <th style="width:15%; background-color: #1c7368; color: #FFFFFF" title="RESULTADO">RESULTADO</th>
                <th style="width:15%; background-color: #1c7368; color: #FFFFFF" title="INDICADOR">INDICADOR</th>
                <th style="width:5%; background-color: #1c7368; color: #FFFFFF" title="LINEA BASE">LINEA BASE '.($this->gestion-1).'</th>
                <th style="width:5%; background-color: #1c7368; color: #FFFFFF" title="META">META</th>
                <th style="width:8%; background-color: #1c7368; color: #FFFFFF" title="MEDIO DE VERIFICACI&Oacute;N">MEDIO DE VERIFICACI&Oacute;N</th>
                
                <th style="width:5%; background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                <th style="width:17%; background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">UNIDAD DE MEDIDA</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">COSTO UNITARIO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">PRESUPUESTO TOTAL</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">MONTO CERTIFICADO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">ENERO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">FEBRERO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">MARZO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">ABRIL</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">MAYO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">JUNIO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">JULIO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">AGOSTO</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">SEPTIEMBRE</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">OCTUBRE</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">NOVIEMBRE</th>
                <th style="width:7%; background-color: #1c7368; color: #FFFFFF">DICIEMBRE</th>
                <th style="width:15%; background-color: #1c7368; color: #FFFFFF">OBSERVACI&Oacute;N</th>
              </tr>
              </thead>
            <tbody>';
            $nro=0;
            $costo_total=0; $monto_certificado=0;
              foreach ($requerimientos as $rowr){
              $prog = $this->minsumos->get_list_insumo_financiamiento($rowr['insg_id']);
              if(count($prog)!=0){
                $monto=0; $color_tr='';
                $cert=$this->model_ejecucion->get_ins_certificado($rowr['ins_id'],$prog[0]['ifin_id']);
                  if(count($cert)!=0){
                    $monto=$cert[0]['certificado'];
                  }
                  
                  $nro++;
                  $tabla.='
                  <tr>
                    <td style="width:1%; height:50px;">'.$nro.'</td>
                    <td>'.mb_convert_encoding($rowr['com_componente'], 'cp1252', 'UTF-8').'</td>
                    <td>OE.- '.$rowr['obj_codigo'].'</td>
                    <td>ACP.- '.$rowr['acc_codigo'].'</td>
                    <td>'.$rowr['prod_cod'].'</td>
                    <td style="width:10%;">'.mb_convert_encoding($rowr['prod_producto'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding($rowr['prod_resultado'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding($rowr['prod_indicador'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding($rowr['prod_linea_base'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding($rowr['prod_meta'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding(strtoupper($rowr['prod_fuente_verificacion']), 'cp1252', 'UTF-8').'</td>
                    <td style="width:5%;">'.$rowr['par_codigo'].'</td>
                    <td style="width:17%;">'.mb_convert_encoding($rowr['ins_detalle'], 'cp1252', 'UTF-8').'</td>
                    <td style="width:7%;">'.$rowr['ins_unidad_medida'].'</td>
                    <td style="width:5%;">'.$rowr['ins_cant_requerida'].'</td>
                    <td style="width:7%;">'.round($rowr['ins_costo_unitario'],2).'</td>
                    <td style="width:7%;">'.round($rowr['ins_costo_total'],2).'</td>
                    <td style="width:7%;" bgcolor="#c1f5ee">'.$monto.'</td>';
                    if(count($cert)!=0){
                      if($monto==$prog[0]['programado_total']){
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:7%;" bgcolor="#cde0c4">'.$prog[0]['mes'.$i].'</td>';
                        }
                      }
                      else{
                        for ($i=1; $i<=12 ; $i++) {
                          if(count($this->model_ejecucion->get_item($prog[0]['ifin_id'],$i))==1){ 
                            $tabla.='<td style="width:7%;" bgcolor="#cde0c4">'.$prog[0]['mes'.$i].'</td>';
                          }
                          else{
                            $tabla.='<td style="width:7%;">'.$prog[0]['mes'.$i].'</td>';
                          }
                        }
                      }
                    }
                    else{
                      for ($i=1; $i<=12 ; $i++) {
                        $tabla.='<td style="width:7%;">'.$prog[0]['mes'.$i].'</td>';
                      }
                    }

                    $tabla.='
                    <td style="width:10%;">'.mb_convert_encoding($rowr['ins_observacion'], 'cp1252', 'UTF-8').'</td>
                  </tr>';
                }
              }

          $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }*/


    

    /*--- MIS REQUERIMIENTOS PROGRAMADOS - CERTIFICADOS POR REGIONAL,DISTRITAL (2019) ---*/
   /* public function mis_requerimientos_regionales_distritales($id,$tp,$tp_id){
      $unidades=$this->mrep_operaciones->unidades_proyectos($id,$tp,$tp_id); /// Unidades por regional, distrital
      if($tp==1){
        $dep=$this->model_proyecto->get_departamento($id);
        $titulo_req='CONSOLIDADO REQUERIMIENTOS : REGIONAL - '.mb_convert_encoding(strtoupper($dep[0]['dep_departamento']), 'cp1252', 'UTF-8').' '.$this->gestion.'';
      }
      else{
        $dist=$this->model_proyecto->dep_dist($id);
        $titulo_req='CONSOLIDADO REQUERIMIENTOS : DISTRITAL - '.mb_convert_encoding(strtoupper($dist[0]['dist_distrital']), 'cp1252', 'UTF-8').' '.$this->gestion.'';
      }

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
      $tabla .='<table border="1" cellpadding="0" cellspacing="0" class="tabla">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1">
                        <th align=center colspan=25 style="height:25px;">'.$titulo_req.'</th>
                      </tr>';
              $tabla.='<tr class="modo1">';
                $tabla.='<th style="width:1%;height:25px;" bgcolor="#1c7368"><font color="#ffffff">#</font></th>';
                $tabla.='<th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff">CATEGORIA PROGRAM&Aacute;TICA</font></th>';
                $tabla.='<th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff">UNIDAD / PROYECTO</font></th>';
                $tabla.='<th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff">SERVICIO / COMPONENTE</font></th>';
                $tabla.='<th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff">OPERACI&Oacute;N</font></th>';
                $tabla.='<th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">PARTIDA</font></th>';
                $tabla.='<th style="width:17%;" bgcolor="#1c7368"><font color="#ffffff">DETALLE REQUERIMIENTO</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">UNIDAD DE MEDIDA</font></th>';
                $tabla.='<th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">CANTIDAD</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">COSTO UNITARIO</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">COSTO TOTAL</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">TOTAL CERTIFICADO</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">ENE.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">FEB.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">MAR.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">ABR.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">MAY.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">JUN.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">JUL.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">AGO.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">SEPT.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">OCT.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">NOV.</font></th>';
                $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">DIC.</font></th>';
                $tabla.='<th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff">OBSERVACI&Oacute;N</font></th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              foreach ($unidades as $row){
                $tabla.=''.$this->list_requerimientos($row['proy_id'],$row['tp_id']); /// Gestion 2019
              }
      $tabla.='
        </tbody>
      </table>';

      return $tabla;
    }

    /*------ LISTA DE REQUERIMIENTOS (2019) ------*/
    public function list_requerimientos($proy_id,$tp_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS FASE ACTIVA
      $tabla ='';
          $req=$this->mrep_operaciones->list_requerimientos($proy_id,$tp_id);
          $nro=0; 
          if(count($req)!=0){
              $costo_total=0; $monto_certificado=0;
              foreach ($req as $rowr){
                $prog = $this->minsumos->get_list_insumo_financiamiento($rowr['insg_id']);
                if(count($prog)!=0){
                  $monto=0; $color_tr='';
                    $nro++;
                    $tabla.='<tr>
                              <td style="width:1%;" style="height:23px;">'.$nro.'</td>
                              <td style="width:10%;">'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].'</td>
                              <td style="width:10%;">'.mb_convert_encoding($proyecto[0]['proy_nombre'], 'cp1252', 'UTF-8').'</td>
                              <td style="width:10%;">'.mb_convert_encoding($rowr['com_componente'], 'cp1252', 'UTF-8').'</td>
                              <td style="width:10%;">'.mb_convert_encoding($rowr['prod_producto'], 'cp1252', 'UTF-8').'</td>
                              <td style="width:5%;">'.$rowr['par_codigo'].'</td>
                              <td style="width:17%;">'.mb_convert_encoding($rowr['ins_detalle'], 'cp1252', 'UTF-8').'</td>
                              <td style="width:7%;">'.$rowr['ins_unidad_medida'].'</td>
                              <td style="width:5%;">'.$rowr['ins_cant_requerida'].'</td>
                              <td style="width:7%;">'.round($rowr['ins_costo_unitario'],2).'</td>
                              <td style="width:7%;">'.round($rowr['ins_costo_total'],2).'</td>
                              <td style="width:7%;" bgcolor="#c1f5ee"></td>';
                              for ($i=1; $i<=12 ; $i++) {
                                  $tabla.='<td style="width:7%;">'.$prog[0]['mes'.$i].'</td>';
                                }

                              $tabla.='
                              <td style="width:10%;">'.mb_convert_encoding($rowr['ins_observacion'], 'cp1252', 'UTF-8').'</td>';
                    $tabla.='</tr>';
                }
                
              }
          }
  
      return $tabla;
    }




 

    /*----- Consolidado por partidas ------*/
    function list_consolidado_partidas($tp){
      $unidades=$this->minsumos->list_consolidado_partidas($tp);
      $tabla='';
      $tabla.='
          <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                <tr class="modo1">
                  <th style="width:1%; height:35px;" style="background-color: #1c7368; color: #FFFFFF">#</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="REGIONAL">REGIONAL</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DISTRITAL">DISTRITAL</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="TIPO">TIPO</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="APERTURA PROGRAMATICA">APERTURA PROGRAM&Aacute;TICA</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="UNIDAD ORGANIZACIONAL">UNIDAD</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="SERVICIO, PARTIDA">PARTIDA</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MONTO ASIGNADO">MONTO ASIGNADO</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MONTO PROGRAMADO">MONTO PROGRAMADO</th>
                  <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MONTO DIFERENCIA">MONTO DIFERENCIA</th>
                </tr>
              </thead>
            <tbody>';
            $nro=0;
            foreach($unidades as $row){
              $nro++;
              $part=$this->model_ptto_sigep->get_partida_accion_regional($row['dep_id'],$row['aper_id'],$row['par_id']);
                
                $prog=0;
                if(count($part)!=0){
                  $prog=$part[0]['monto'];
                }
                $dif=($row['monto']-$prog);
                $color='#f1f1f1';
                if($dif<0){
                  $color='#f9cdcd';
                }

                $tabla.='<tr >';
                  $tabla.='<td>'.$nro.'-'.$row['aper_id'].'-'.$row['dep_id'].'-'.$row['par_id'].'</td>';
                  $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
                  $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
                  $tabla.='<td>'.$this->tip_serv($row['aper_programa'],$tp).'</td>';
                  $tabla.='<td>\''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'\'</td>';
                  $tabla.='<td>'.$row['proy_nombre'].'</td>';

                  $tabla.='<td>'.$row['partida'].'</td>';
                  $tabla.='<td align=right>'.round($row['monto'],2).'</td>';
                  $tabla.='<td align=right>'.round($prog,2).'</td>
                          <td align=right>'.round($dif,2).'</td>';
                $tabla.='</tr>';
            }
        $tabla.'</tbody>
              </table>';
      return $tabla;
    }


   

    /*-------- GET LISTA DE REQ. CERTIFICADOS ------------*/
    public function get_requerimientos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $tabla=$this->list_req_cert_unidad($proyecto[0]['proy_id'],$proyecto[0]['tp_id']);
        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

   
    /*-------- GET LISTA DE DISTRITALES --------*/
    public function get_distritales(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $regional=$this->model_proyecto->get_departamento($dep_id);

        $tabla=$this->list_distritales($dep_id);
        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
            'caratula'=>$regional,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*-------- TABLA LISTA DE DISTRITALES --------*/
    public function list_distritales($dep_id){
      $distritales=$this->model_proyecto->list_distritales($dep_id);
      $tabla='';
      $tabla.='
       <table class="table table-bordered">
        <thead>
        <tr>
          <th style="width:1%;">#</th>
          <th style="width:10%;">ADMINISTRACI&Oacute;N DISTRITAL</th>
          <th style="width:5%;">CONSOLIDADO OPERACIONES/ACTIVIDADES '.$this->gestion.'</th>
          <th style="width:5%;">CONSOLIDADO REQUERIMIENTOS '.$this->gestion.'</th>
          <th style="width:5%;"></th>
        </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach ($distritales as $row){
          $nro++;
          $tabla.='
          <tr>
            <td style="width:1%; height:35px;" align=center><b>'.$nro.'</b></td>
            <td><b>'.strtoupper($row['dist_distrital']).'</b></td>
            <td align=center>
              <div class="btn-group">
                <button class="btn btn-default">
                  VER DETALLE
                </button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a href="'.site_url("").'/rep/exportar_operaciones_distrital/'.$row['dist_id'].'/4" target="_blank" title="EXPORTAR OPERACIONES, DISTRITAL - '.$row['dist_distrital'].'">GASTO CORRIENTE</a>
                  </li>
                  <li>
                    <a href="'.site_url("").'/rep/exportar_operaciones_distrital/'.$row['dist_id'].'/1" target="_blank" title="EXPORTAR OPERACIONES, DISTRITAL - '.$row['dist_distrital'].'">PROYECTO DE INVERSI&Oacute;N</a>
                  </li>
                </ul>
              </div>
            </td>
            <td align=center>
              <div class="btn-group">
                <button class="btn btn-default">
                  VER DETALLE
                </button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$row['dist_id'].'/4" target="_blank" title="EXPORTAR REQUERIMIENTOS (PROGRAMADO - CERTIFICADO) DISTRITAL - '.$row['dist_distrital'].'">GASTO CORRIENTE</a>
                  </li>
                  <li>
                    <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$row['dist_id'].'/1" target="_blank" title="EXPORTAR REQUERIMIENTOS (PROGRAMADO - CERTIFICADO) DISTRITAL - '.$row['dist_distrital'].'">PROYECTO DE INVERSI&Oacute;N</a>
                  </li>
                </ul>
              </div>
            </td>
            <td align="center" style="width: 3%;">
              <a href="'.site_url("").'/rep/xls_unidades/'.$row['dist_id'].'" target="_blank" class="btn btn-success" title="MIS UNIDADES">MIS UNIDADES</a>
            </td>
          </tr>';
        }
        $tabla.='
        </tbody>
      </table>';
      return $tabla;
    }


    /*--- LISTA DE UNIDADES, PROYECTOS DE INVERSION ---*/
    public function list_unidades($dist_id,$tp_id){
      $unidades=$this->mrep_operaciones->list_unidades($dist_id,$tp_id);
      $tabla='';

      if($this->gestion!=2020){
        $titulo='MIS OPERACIONES.xls';
      }
      else{
        $titulo='MIS ACTIVIDADES.xls';
      }

      $nro=0;
      if($tp_id==4){ /// Gasto Corriente
        foreach ($unidades as $row){
          $nro++;
          $tabla.='
          <tr style="height:35px;">
            <td>'.$nro.'</td>
            <td align=center>
              <a href="'.site_url("").'/rep/exportar_operaciones_unidad/'.$row['proy_id'].'" class="btn btn-default" target="_blank" title="EXPORTAR TODAS LAS OPERACIONES DE LA UNIDAD">'.$titulo.'</a>
            </td>

            <td>
              <a href="'.site_url("").'/rep/exportar_requerimientos_unidad/'.$row['proy_id'].'" class="btn btn-default" target="_blank" title="EXPORTAR TODAS REQUERIMIENTOS DE LA UNIDAD">MIS REQUERIMIENTOS.xls</a>
            </td>
            <td><center><a href="#" data-toggle="modal" data-target="#modal_servicios" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' - '.strtoupper($row['abrev']).'">MIS SERVICIOS</a></center></td>
            <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
            <td>';
              if($this->gestion==2020){
              $tabla.='<b>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</b>';
            }
            else{
              $tabla.='<b>'.$row['proy_nombre'].'</b>';
            }
            $tabla.='</td>
            <td>'.$row['nivel'].'</td>
            <td>'.$row['tipo_adm'].'</td>
            <td>'.strtoupper($row['dep_departamento']).'</td>
            <td>'.strtoupper($row['dist_distrital']).'</td>
          </tr>';
        } 
      }
      else{ /// Proyecto de Inversion
        foreach ($unidades as $row){
          $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
          $nro++;
          $tabla.='
          <tr style="height:35px;">
            <td>'.$nro.'</td>
            <td></td>
            <td></td>
            <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
            <td><b>'.$row['proy_nombre'].'</b></td>
            <td>'.$row['proy_sisin'].'</td>
            <td>'.strtoupper($row['dep_departamento']).'</td>
            <td>'.strtoupper($row['dist_distrital']).'</td>
            <td>* '.$fase[0]['fase'].'<br>* '.$fase[0]['etapa'].'</td>
            <td>'.$this->model_faseetapa->calcula_nc($fase[0]['pfec_fecha_inicio']).'</td>
            <td>'.$this->model_faseetapa->calcula_ap($fase[0]['pfec_fecha_inicio'],$fase[0]['pfec_fecha_fin']).'</td>
          </tr>';
        }
      }
      return $tabla;
    }


    /*----------------------------------- PRODUCTOS ----------------------------*/
    public function temporalizacion_prod($prod_id,$gestion){
        $prod=$this->model_producto->get_producto_id($prod_id); /// Producto Id
        $programado=$this->model_producto->producto_programado($prod_id,$gestion); /// Producto Programado

        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=0;
            $prog[2][$i]=0;
            $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
                $prog[1][$i]=$programado[0][$m[$i]];
               /* $pa=$pa+$prog[1][$i];
                $prog[2][$i]=$pa+$prod[0]['prod_linea_base'];

              if($prod[0]['prod_meta']!=0){
                $prog[3][$i]=round(((($pa+$prod[0]['prod_linea_base'])/$prod[0]['prod_meta'])*100),1);
              }  */
            } 
        }
        
        $tr_return = '';
        for($i = 1 ;$i<=12 ;$i++) {
          $tr_return .= '<td>'.$prog[1][$i].'</td>';
        }
        return $tr_return;
    }

    public function actividades($prod_id){
       $actividad=$this->model_actividad->list_act_anual($prod_id); /// Actividad
       $tabla='';
       $nro_a=0;
       if(count($actividad)!=0){
            foreach ($actividad as $row){
                $nro_a++;
                $tabla.='<tr class="modo1" bgcolor="#e5f3f1">';
                    $tabla.='<td>'.$nro_a.'</td>';
                    $tabla.='<td></td>';
                    $tabla.='<td>'.mb_convert_encoding(''.$row['act_actividad'], 'cp1252', 'UTF-8').'</td>';
                    $tabla.='<td>'.$row['indi_abreviacion'].'</td>';
                    $tabla.='<td>'.mb_convert_encoding(''.$row['act_indicador'], 'cp1252', 'UTF-8').'</td>';
                    $tabla.='<td>'.round($row['act_linea_base'],2).'</td>';
                    $tabla.='<td>'.round($row['act_meta'],2).'</td>';
                    $tabla.='<td>'.$row['act_ponderacion'].' %</td>';
                    $tabla.='<td>'.mb_convert_encoding(''.$row['act_fuente_verificacion'], 'cp1252', 'UTF-8').'</td>';
                    $tabla.='<td>'.$this->temporalizacion_act($row['act_id'],$this->session->userdata('gestion')).'</td>';
                $tabla.='</tr>';
            }
       }

       return $tabla;
    }

    /*----------------------------------- ACTIVIDADES ----------------------------*/
    public function temporalizacion_act($act_id,$gestion){
        $act=$this->model_actividad->get_actividad_id($act_id); /// programado
        $programado=$this->model_actividad->actividad_programado($act_id,$gestion); /// Actividad Programado

        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=0;
            $prog[2][$i]=0;
            $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
                $prog[1][$i]=$programado[0][$m[$i]];
               /* $pa=$pa+$prog[1][$i];
                $prog[2][$i]=$pa+$act[0]['act_linea_base'];

              if($act[0]['act_meta']!=0){
                $prog[3][$i]=round(((($pa+$act[0]['act_linea_base'])/$act[0]['act_meta'])*100),2);
              }  */
            } 
        }
        
        $tr_return = '';
        for($i = 1 ;$i<=12 ;$i++){
            $tr_return .= '<td>'.$prog[1][$i].'</td>';
        }
        return $tr_return;
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

}