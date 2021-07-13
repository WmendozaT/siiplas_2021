<?php
class Cppto_comparativo extends CI_Controller {  
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
      $this->load->library('pdf2');
      $this->load->model('menu_modelo');
      $this->load->model('Users_model','',true);
      $this->load->model('programacion/model_faseetapa');
      $this->load->model('programacion/model_proyecto');
      $this->load->model('programacion/model_componente');
      $this->load->model('programacion/model_producto');
      $this->load->model('programacion/model_actividad');
      $this->load->model('mantenimiento/mapertura_programatica');
      $this->load->model('mantenimiento/munidad_organizacional');
      $this->load->model('mantenimiento/model_estructura_org');
      $this->load->model('programacion/insumos/minsumos');
      $this->load->model('programacion/insumos/model_insumo');
      $this->load->model('mestrategico/model_objetivoregion');
      $this->load->model('mantenimiento/model_ptto_sigep');
      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm'); // 1: Nacional, 2: Regional, Distrital
      $this->dist = $this->session->userData('dist');
      $this->rol = $this->session->userData('rol_id');
      $this->fun_id = $this->session->userdata("fun_id");
      $this->tp_adm = $this->session->userdata("tp_adm");
      $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
      $this->verif_ppto = $this->session->userData('verif_ppto'); /// AnteProyecto Ptto POA : 0, Ptto Aprobado Sigep : 1
      }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }

    /*----- REPORTE COMPARATIVO DE PARTIDAS (ASIG - PROG) ----*/
    public function reporte_presupuesto_consolidado_comparativo($proy_id){
      $data['mes'] = $this->mes_nombre();
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
      //$data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      if(count($data['proyecto'])!=0){
          $monto_asignado=0;$monto_programado=0;
          $cod_part_asig=$this->model_ptto_sigep->sum_codigos_partidas_asig_prog($data['proyecto'][0]['aper_id'],1);  //// ppto asignado Anteproyecto
          
          if(count($cod_part_asig)!=0){
            $monto_asignado=$cod_part_asig[0]['sum_cod_partida'];
          }

          if($data['proyecto'][0]['tp_id']==1){
            $cod_part_prog=$this->model_ptto_sigep->sum_codigos_partidas_asig_prog_pi($data['proyecto'][0]['proy_id']); //// ppto Programado POA - PI
          }
          else{
            $cod_part_prog=$this->model_ptto_sigep->sum_codigos_partidas_asig_prog($data['proyecto'][0]['aper_id'],2); //// ppto Programado POA - G. corriente
          }


          ///----- Genera lista de Partidas Asignadas y Programadas
          $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id'],1); // Asig
          if($data['proyecto'][0]['tp_id']==1){
            $partidas_prog=$this->model_ptto_sigep->partidas_pi_prog_region($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['proy_id']);
          }
          else{
            $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id'],2); // Prog
          }
          


          if($monto_asignado==$cod_part_prog[0]['sum_cod_partida']){ //// if (monto asignado = monto programado)
            $data['tabla'] = $this->comparativo_partidas_normal($partidas_asig,$partidas_prog,$data['proyecto']);
          }
          else{ /// Cuando existen diferencias en las partidas asignadas con las programas
            $data['tabla'] = $this->comparativo_update_partidas_normal($partidas_asig,$partidas_prog,$data['proyecto']);
          }

            $data['titulo']='<div align="center">PLAN OPERATIVO ANUAL '.$this->gestion.' - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO <br><b>CONSOLIDADO CUADRO COMPARATIVO DE PRESUPUESTO (ANTEPROYECTO - POA)</b></div>';
            if($data['proyecto'][0]['proy_estado']==4){
              $data['titulo']='<div align="center">PLAN OPERATIVO ANUAL '.$this->gestion.' - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO <br><b>CONSOLIDADO CUADRO COMPARATIVO DE PRESUPUESTO FINAL (APROBADO - ANTEPROYECTO)</b></div>';
            }

          $this->load->view('admin/programacion/reportes/reporte_consolidado_presupuesto_comparativo', $data);
      }
      else{
          echo "<b>ERROR !!!!!</b>";
      }
    }

    /*---- COMPARATIVO DE PARTIDAS A NIVEL DE UNIDAD / ESTABLECIMIENTO  (las partidas cambian)---*/
    public function comparativo_update_partidas_normal($partidas_asig,$partidas_prog,$proyecto){
      if($this->verif_ppto==0){
        $titulo='PRESUPUESTO ANTEPROYECTO';
      }
      else{
        $titulo='PRESUPUESTO APROBADO';
      }

      $tabla ='';
      $tabla .='
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:95%;font-size: 8px;" align=center>
        <thead>
          <tr style="height:11px;" bgcolor="#1c7368" align=center>
            <th style="width:3%;color:#FFF;" align=center>NRO.</th>
            <th style="width:10%;color:#FFF;">C&Oacute;DIGO PARTIDA</th>
            <th style="width:35%; color:#FFF;">DETALLE PARTIDA</th>
            <th style="width:12%; color:#FFF;">'.$titulo.'</th>
            <th style="width:12%; color:#FFF;">PRESUPUESTO POA</th>
            <th style="width:12%; color:#FFF;">SALDO POA</th>';
            if($this->tp_adm==1){
              $tabla.='<th style="width:10%; color:#FFF; font-size:7px">SALDO PPTO. DE ADJUDICACIONES</th>';
            }
            $tabla.='
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        $monto_asig=0;
        $monto_prog=0;

        foreach($partidas_asig  as $row){
          if($proyecto[0]['tp_id']==1){
            $part=$this->model_ptto_sigep->get_partida_programado_pi($proyecto[0]['proy_id'],$row['par_id']);
          }
          else{
            $part=$this->model_ptto_sigep->get_partida_accion_regional($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
          }
          
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=(($row['monto']+$row['saldo'])-$prog);
           
           $color='';
            $sig='';
            if($dif!=0){
              if($dif<0){
                $color='#f9cdcd';
              }
              else{
                $color='#e5efd7';
                $sig='+';
              }
            }

            $nro++;
            $tabla .='
              <tr class="modo1" bgcolor='.$color.'>
                <td style="width: 3%;height:11px; text-align: center">'.$nro.'</td>
                <td style="width: 10%; text-align: center;">'.$row['codigo'].'</td>
                <td style="width: 35%; text-align: left;">'.$row['nombre'].'</td>
                <td style="width: 12%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                <td style="width: 12%; text-align: right;">'.number_format($prog, 2, ',', '.').'</td>
                <td style="width: 12%; text-align: right;">'.$sig.''.number_format($dif, 2, ',', '.').'</td>';
                if($this->fun_id==399){
                  $tabla.='<td style="width: 10%; text-align: right;">'.number_format($row['saldo'], 2, ',', '.').'</td>';
                }
                $tabla.='
              </tr>';
            $monto_asig=$monto_asig+($row['monto']+$row['saldo']);
            $monto_prog=$monto_prog+$prog; 
        }

        foreach($partidas_prog as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_accion($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
           if(count($part)==0){ 
            $asig=0;
            if(count($part)!=0){
              $asig=($part[0]['monto']-$part[0]['saldo']);
            }
            $dif=($asig-$row['monto']);
            $color='';
            $sig='';
            if($dif!=0){
              if($dif<0){
                $color='#f9cdcd';
              }
              else{
                $color='#e5efd7';
                $sig='+';
              }
            }
            
          $nro++;
          $tabla .='<tr class="modo1" bgcolor='.$color.'>
                      <td style="width: 3%;height:11px; text-align: center">'.$nro.'</td>
                      <td style="width: 10%; text-align: center;">'.$row['codigo'].'</td>
                      <td style="width: 35%; text-align: left;">'.$row['nombre'].'</td>
                      <td style="width: 15%; text-align: right;">'.number_format($asig, 2, ',', '.').'</td>
                      <td style="width: 15%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                      <td style="width: 15%; text-align: right;">'.$sig.''.number_format($dif, 2, ',', '.').'</td>';
                if($this->fun_id==399){
                  $tabla.='<td style="width: 10%; text-align: right;">'.number_format($row['saldo'], 2, ',', '.').'</td>';
                }
                $tabla.='
                    </tr>';
          $monto_asig=$monto_asig+$asig;
          $monto_prog=$monto_prog+$row['monto'];
          }  
        }


        $dif=($monto_asig-$monto_prog);
        $color='#f1f1f1';
        $sig='';
        if($dif!=0){
          if($dif<0){
            $color='#f9cdcd';
          }
          else{
            $color='#e5efd7';
            $sig='+';
          }
        }

       $tabla.='
        </tbody>
          <tr class="modo1" bgcolor='.$color.'>
              <td colspan=3 style="height:11px;"><strong>TOTAL</strong></td>
              <td align=right><b>'.number_format($monto_asig, 2, ',', '.').'</b></td>
              <td align=right><b>'.number_format($monto_prog, 2, ',', '.').'</b></td>
              <td align=right><b>'.$sig.''.number_format($dif, 2, ',', '.').'</b></td>
              <td align=right></td>
              <td align=right></td>
            </tr>
        </table>';

      return $tabla;
    }

    /*---- COMPARATIVO DE PARTIDAS A NIVEL DE UNIDAD / ESTABLECIMIENTO  (las partidas no cambian)---*/
    public function comparativo_partidas_normal($partidas_asig,$partidas_prog,$proyecto){ 
      if($this->verif_ppto==0){
        $titulo='PRESUPUESTO ANTEPROYECTO';
      }
      else{
        $titulo='PRESUPUESTO APROBADO';
      }

      $tabla ='';
      $tabla .='
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:95%;font-size: 8px;" align=center>
          <thead>
            <tr style="height:11px;" bgcolor="#1c7368" align=center>
              <th style="width:3%;color:#FFF;" align=center>NRO.</th>
              <th style="width:10%;color:#FFF;">C&Oacute;DIGO PARTIDA</th>
              <th style="width:35%; color:#FFF;">DETALLE PARTIDA</th>
              <th style="width:15%; color:#FFF;">'.$titulo.'</th>
              <th style="width:15%; color:#FFF;">PRESUPUESTO POA</th>
              <th style="width:15%; color:#FFF;">SALDO POA</th>';
            if($this->fun_id==399){
              $tabla.='<th style="width:10%; color:#FFF; font-size:7px">SALDO PPTO. DE ADJUDICACIONES</th>';
            }
            $tabla.='
            </tr>
          </thead>
          <tbody>';

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      foreach($partidas_prog  as $row){
        $part=$this->model_ptto_sigep->get_partida_asig_accion($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
          $asig=0;
          if(count($part)!=0){
            $asig=$part[0]['monto'];
          }
          $dif=($asig-$row['monto']);

          $color='';
            $sig='';
            if($dif!=0){
              if($dif<0){
                $color='#f9cdcd';
              }
              else{
                $color='#e5efd7';
                $sig='+';
              }
            }

        $nro++;
        $tabla .='<tr class="modo1" bgcolor='.$color.'>
                    <td style="width: 3%;height:11px; text-align: center">'.$nro.'</td>
                    <td style="width: 10%; text-align: center;">'.$row['codigo'].'</td>
                    <td style="width: 35%; text-align: left;">'.$row['nombre'].'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($asig, 2, ',', '.').'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                    <td style="width: 15%; text-align: right;">'.$sig.''.number_format($dif, 2, ',', '.').'</td>';
                if($this->fun_id==399){
                  $tabla.='<td style="width: 10%; text-align: right;">'.number_format($part[0]['saldo'], 2, ',', '.').'</td>';
                }
                $tabla.='</tr>';
        $monto_asig=$monto_asig+$asig;
        $monto_prog=$monto_prog+$row['monto'];
      }

      $dif=($monto_asig-$monto_prog);
      $color='#f1f1f1';
      $sig='';
      if($dif!=0){
        if($dif<0){
          $color='#f9cdcd';
        }
        else{
          $color='#e5efd7';
          $sig='+';
        }
      }

      $tabla .='
        </tbody>
          <tr class="modo1" bgcolor="'.$color.'">
              <td colspan=3 style="height:11px;"><strong>TOTAL</strong></td>
              <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
              <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
              <td align=right>'.$sig.''.number_format($dif, 2, ',', '.').'</td>
              <td></td>
            </tr>
        </table>';

      return $tabla;
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


}