<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cprog_insumos_delegado extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;

   public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('programacion/insumos/minsumos_delegado');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        }else{
            redirect('/','refresh');
        }
    }

    //PROGRAMACION DE INSUMOS A NIVEL DE COMPONENTES - DELEGADO
    function prog_isumos_com($com_id){
        $data['menu']=$this->menu(2);
        $data['componente']=$this->model_componente->get_componente($com_id);

       if(count($data['componente'])!=0){
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        $data['fase'] = $this->model_faseetapa->get_id_fase($data['proyecto'][0]['proy_id']); //// DATOS DE LA FASE ACTIVA
        $data['part_padres'] = $this->model_partidas->lista_padres();//partidas padres
        $data['part_hijos'] = $this->model_partidas->lista_partidas();//partidas hijos
        $data['fuentes'] = $this->asignacion_fuentes($data['proyecto'],1); /// Lista de fuentes
        $data['requerimientos'] = $this->list_requerimientos($com_id,1); /// Lista de requerimientos
        $data['partidas_del']=$this->consolidado_partidas_delegado($com_id,1); /// consolidado por partidas - Delegado
        $data['partidas_pi']=$this->consolidado_partidas_proyecto($data['proyecto'],1); /// consolidado por partidas - actividad
        /*------ verif fuentes de financiamiento ------*/
        $data['verif']=0;
        $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($data['fase'][0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
        $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);
        if(count($fuentes)==1){
          $data['verif']=1;
        }
        /*----------------------------------------------*/
        $this->load->view('admin/programacion/insumos/insumo_componente/insumo_componente', $data);
       }
       else{
        redirect('admin/dashboard');
       }

    }

    /*----------------- TABLA - FUENTES --------------*/
    public function asignacion_fuentes($proyecto,$tp){
      $fuente = $this->model_faseetapa->presupuesto_asignados($proyecto[0]['proy_id'],$this->gestion);
      $monto_prog=$this->minsumos->monto_total_programado($proyecto[0]['aper_id'],$this->gestion);
      $color='';
      if($tp==1){
        $tab='class="table table-bordered table-sm"'; 
      }
      elseif($tp==2){
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" align="center"';
      }

      $monto=0;
      if(count($monto_prog)!=0){
        $monto=$monto_prog[0]['monto'];
      }
      if(($fuente[0]['presupuesto_asignado']+0.5)<$monto){
        $color='red';
      }
      
      $tabla='';
      $tabla.='<table '.$tab.'>
                  <thead>
                    <tr class="modo1">
                      <th>FUENTE DE FINANCIAMIENTO</th>
                      <th>ORGANISMO FINANCIADOR</th>
                      <th>PRESUPUESTO ASIGNADO</th>
                      <th>PRESUPUESTO PROGRAMADO</th>
                      <th>SALDO POR PROGRAMAR</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="modo1">
                      <td>'.$fuente[0]['ff_codigo'].' - '.$fuente[0]['ff_descripcion'].'</td>
                      <td>'.$fuente[0]['of_codigo'].' - '.$fuente[0]['of_descripcion'].'</td>
                      <td>'.number_format($fuente[0]['presupuesto_asignado'], 2, ',', '.').'</td>
                      <td>'.number_format($monto, 2, ',', '.').'</td>
                      <td><font color='.$color.' size="1"><b>'.number_format(($fuente[0]['presupuesto_asignado']-$monto), 2, ',', '.').'</b></font></td>
                    </tr>
                  </tbody>
                </table>';
      return $tabla;
    }

    /*----------------- TABLA - LISTA DE REQUERIMIENTOS --------------*/
    public function list_requerimientos($com_id,$tp){
      $lista_insumos = $this->minsumos->lista_insumos_com($com_id);
      $tabla='';
      if($tp==1){
        $tab='id="dt_basic" class="table table table-bordered" width="100%"'; 
      }
      elseif($tp==2){
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" align="center"';
      }
      $total=0;
      $tabla.='<table '.$tab.'>
                <thead>
                  <tr class="modo1">
                    <th></th>
                    <th>PARTIDA</th>
                    <th>DETALLE REQUERIMIENTO</th>
                    <th>UNIDAD</th>
                    <th>CANTIDAD</th>
                    <th>UNITARIO</th>
                    <th>TOTAL</th>
                    <th>TOTAL PROG.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th>OBSERVACIONES</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $prog = $this->minsumos->get_list_insumo_financiamiento($row['insg_id']);
                  $color='';
                  if(count($prog)!=0){
                    if(($row['ins_costo_total'])!=$prog[0]['programado_total']){
                      $color='#f5bfb6';
                    }
                  }
         
                  $cont++;
                  $tabla .= '<tr class="modo1" bgcolor="'.$color.'">';
                    $tabla .= '<td align="center">';
                    if($tp==1){
                      $tabla.='
                          <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                          <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                    }
                    else{
                      $tabla.=''.$cont.'';
                    }
                    $tabla .='</td>';
                    $tabla .='<td>'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td>'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td>'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td>'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';

                    if(count($prog)!=0){
                      $tabla.='
                      <td>'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td> 
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                      <td bgcolor="#dcfbf8">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                    }
                    else{
                      $tabla.='
                      <td>0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>
                      <td bgcolor="#f9d4ce">0</td>';
                    }
                    
                    $tabla .= '<td>'.$row['ins_observacion'].'</td>';
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="6"> TOTAL </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="14"></td>
                  </tr>
              </table>';

      return $tabla;
    }

    /*----------------- VALIDA REQUERIMIENTO DELEGADO-----------------*/
     public function valida_insumo_delegado(){
      if($this->input->post()) {
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']); /// com id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $detalle = $this->security->xss_clean($post['ins_detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['ins_cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['ins_costo_u']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costo']); /// costo Total
        $unidad = $this->security->xss_clean($post['ins_u_medida']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['partida_id']); /// costo unitario
        $observacion = $this->security->xss_clean($post['ins_observacion']); /// Observacion

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
        $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
        $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

        if(count($fuentes)==1){

          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
          'ins_codigo' => $this->session->userdata("name").'/REQ-DEL/'.$this->gestion, /// Codigo Insumo
          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
          'ins_costo_unitario' => $costo_unitario, /// Costo Unitario
          'ins_costo_total' => $costo_total, /// Costo Total
          'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
          'par_id' => $partida, /// Partidas
          'ins_observacion' => strtoupper($observacion), /// Observacion
          'fun_id' => $this->fun_id, /// Funcionario
          'aper_id' => $proyecto[0]['aper_id'], /// aper id
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          /*----------------------------------------------------------*/
          $data_to_store2 = array( ///// Tabla InsumoComponente
            'com_id' => $com_id, /// com id
            'ins_id' => $ins_id, /// ins_id
          );
          $this->db->insert('insumocomponente', $data_to_store2);
         /*----------------------------------------------------------*/

          $data_to_store = array( 
          'ins_id' => $ins_id, /// Id Insumo
          'g_id' => $this->gestion, /// Gestion
          'insg_monto_prog' => $costo_total, /// Monto programado
          );
          $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
          $insg_id=$this->db->insert_id();

          /*------------------- Guardando Fuente Financiamiento ------*/
          $query=$this->db->query('set datestyle to DMY');
          $data_to_store3 = array( 
          'insg_id' => $insg_id, /// Id Insumo gestion
          'ifin_monto' => $costo_total, /// Monto programado
          'ifin_gestion' => $this->gestion, /// Gestion
          'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
          'ff_id' => $fuentes[0]['ff_id'], /// ff id
          'of_id' => $fuentes[0]['of_id'], /// ff id
          'nro_if' => 1, /// Nro if
          );
          $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
          $ifin_id=$this->db->insert_id();

          for ($i=1; $i <=12 ; $i++) {
            $pfin=$this->security->xss_clean($post['m'.$i]);
            if($pfin!=0){
                $data_to_store4 = array( 
                  'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                  'mes_id' => $i, /// Mes 
                  'ipm_fis' => $pfin, /// Valor mes
                  );
                $this->db->insert('ifin_prog_mes', $data_to_store4);
            }
          }
          /*-----------------------------------------------------------*/
            $get_ins=$this->minsumos->get_insumo_producto($ins_id);
            if(count($get_ins)==1){
              $this->session->set_flashdata('success','EL REQUERIMIENTO SE REGISTRO CORRECTAMENTE :)');
            }
            else{
              $this->session->set_flashdata('danger','EL REQUERIMIENTO NOSE REGISTRO CORRECTAMENTE, VERIFIQUE DATOS :(');
            }
        }
        else{
          $this->session->set_flashdata('danger','ERROR !!, VERIFIQUE DATOS :(');
        }

        redirect(site_url("").'/prog/ins_com/'.$com_id.'');
            
      } else {
          show_404();
      }
    }


    /*--------- VALIDA UPDATE REQUERIMIENTO DELEGADO ----------*/
     public function valida_update_insumo_delegado(){
      if($this->input->post()) {
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $detalle = $this->security->xss_clean($post['detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costot']); /// costo Total
        $unidad = $this->security->xss_clean($post['umedida']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['par_hijo']); /// costo unitario
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion

        $fase = $this->model_faseetapa->get_id_fase($proy_id); /// FASE ACTIVA
        $insumo_gestion=$this->minsumos->get_insumo_gestion($ins_id,$this->gestion); /// INSUMO GESTIÓN
        $insumo_fin=$this->minsumos->list_insumo_financiamiento($insumo_gestion[0]['insg_id']); /// INSUMO FINANCIAMIENTO
      
        /*------------ UPDATE REQUERIMIENTO -------*/
          $update_ins= array(
            'ins_cant_requerida' => $cantidad,
            'ins_costo_unitario' => $costo_unitario,
            'ins_costo_total' => $costo_total,
            'ins_detalle' => $detalle,
            'par_id' => $partida, /// Partidas
            'ins_unidad_medida' => $unidad,
            'ins_observacion' => $observacion,
            'fun_id' => $this->fun_id,
            'ins_estado' => 2
          );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('insumos', $this->security->xss_clean($update_ins));
        /*-----------------------------------------*/

        /*------ UPDATE REQUERIMIENTO GESTION -----*/
          $update_insg= array(
            'insg_monto_prog' => $costo_total,
            'insg_estado' => 2
          );
          $this->db->where('insg_id', $insumo_gestion[0]['insg_id']);
          $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
        /*-----------------------------------------*/

        /*------ UPDATE INSUMO FINANCIAMIENTO -----*/
          $update_infin= array(
            'ifin_monto' => $costo_total
          );
          $this->db->where('insg_id', $insumo_gestion[0]['insg_id']);
          $this->db->update('insumo_financiamiento', $this->security->xss_clean($update_infin));
        /*-----------------------------------------*/

        /*-------- DELETE INSUMO PROGRAMADO --------*/  
          $this->db->where('ifin_id', $insumo_fin[0]['ifin_id']);
          $this->db->delete('ifin_prog_mes');
        /*------------------------------------------*/  

          for ($i=1; $i <=12 ; $i++) {
            $pfin=$this->security->xss_clean($post['mm'.$i]);
            if($pfin!=0){
                $data_to_store4 = array( 
                  'ifin_id' => $insumo_fin[0]['ifin_id'], /// Id Insumo Financiamiento
                  'mes_id' => $i, /// Mes 
                  'ipm_fis' => $pfin, /// Valor mes
                  );
                $this->db->insert('ifin_prog_mes', $data_to_store4);
            }
            
          }
        
          $get_ins=$this->minsumos->get_requerimiento_delegado($ins_id);
          $this->session->set_flashdata('success','EL REQUERIMIENTO SE MODIFICO CORRECTAMENTE :)');
          redirect(site_url("").'/prog/ins_com/'.$get_ins[0]['com_id'].'');

      } else {
          show_404();
      }
    }

    /*----------------- TABLA - CONSOLIDADO PARTIDAS PROYECTO DE INVERSION --------------*/
    public function consolidado_partidas_proyecto($proyecto,$tp){
      $tabla='';
      
      if($tp==1){
        $tab='id="dt_basic3" class="table table-bordered" align="center"';
      }
      elseif($tp==2){
        $tabla ='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center"';
      }

      $partidas=$this->model_ptto_sigep->partidas_accion($proyecto[0]['aper_id'],2); /// lista de partidas programas
      if(count($partidas)!=0){
        $tabla .=' 
          <table '.$tab.'>
            <thead>
                <tr class="modo1" align=center>
                  <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                  <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>PARTIDA</font></th>
                  <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                  <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO ASIGNADO</font></th>
                  <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO PROGRAMADO</font></th>
                  <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>DIFERENCIA</font></th>
                </tr>
              </thead>
              <tbody>';
              $nro=0; $sum_monto_asig=0;$sum_monto_prog=0;
              foreach($partidas  as $row){
              $nro++;
              $part_asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$row['par_id']);
              if(count($part_asig)!=0){
                $monto_asig=$part_asig[0]['monto'];
              }
              else{
                $monto_asig=0;
              }
              $color='';
              $tit='';
              if(($monto_asig+0.50)<$row['monto']){
                $color='#f5d3ce';
                $tit='SOBREGIRO';
              }
                $tabla.='<tr bgcolor='.$color.'>';
                  $tabla.='<td>'.$nro.'</td>';
                  $tabla.='<td>'.$row['codigo'].'</td>';
                  $tabla.='<td>'.$row['nombre'].'</td>';
                  $tabla.='<td align="center">'.number_format($monto_asig, 2, ',', '.').'</td>';
                  $tabla.='<td align="center">'.number_format($row['monto'], 2, ',', '.').'</td>';
                  $tabla.='<td title='.$tit.' align="center">'.number_format(($monto_asig-$row['monto']), 2, ',', '.').'</td>';
                $tabla.='</tr>';
                $sum_monto_asig=$sum_monto_asig+$monto_asig;
                $sum_monto_prog=$sum_monto_prog+$row['monto'];
              }
            $tabla .='</tbody>
                <tr>
                  <td colspan=3>TOTAL</td>
                  <td align="center"><font color="blue"><b>'.number_format($sum_monto_asig, 2, ',', '.').'</b></font></td>
                  <td align="center"><font color="blue"><b>'.number_format($sum_monto_prog, 2, ',', '.').'</b></font></td>
                  <td></td>
                </tr>
              </table>';
      }

      return $tabla;
    }

    /*----------------- TABLA - CONSOLIDADO OPERACION --------------*/
    public function consolidado_partidas_delegado($com_id,$tp){
      $tabla='';
      $partidas = $this->minsumos->consolidado_partidas_delegado($com_id);
      if($tp==1){
        $tab='class="table table-bordered" style="width:70%;"';
      }
      elseif($tp==2){
        $tabla ='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center"';
      }
      
      $nro=0;
      $tabla.='<center>
      <table '.$tab.'>
        <thead>
          <tr class="modo1">
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">#</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">C&Oacute;DIGO PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">DESCRIPCI&Oacute;N PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">MONTO PROGRAMADO</font></th>
          </tr>
        </thead>
        <tbody>';
        $monto_total=0;
      foreach ($partidas as $row) {
        $monto_total=$monto_total+$row['total'];
        $nro++;
        $tabla.='<tr class="modo1">';

          $tabla.='<td>'.$nro.'</td>';
          $tabla.='<td>'.$row['par_codigo'].'</td>';
          
          if($tp==1){
            $tabla.='<td>'.$row['par_nombre'].'</td>';
            $tabla.='<td>' . number_format($row['total'], 2, ',', '.') . '</td>';
          }
          else{
            $tabla.='<td>'.mb_convert_encoding(''.$row['par_nombre'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='<td>'.$row['total'].'</td>';
          }
        $tabla.='</tr>';
      }
      $tabla.='<tr>';
          $tabla.='<td></td>';
          $tabla.='<td colspan="2">TOTAL</td>';
          if($tp==1){
            $tabla.='<td>'.number_format($monto_total, 2, ',', '.').'</td>';
          }
          else{
            $tabla.='<td>'.$monto_total.'</td>';
          }
          
        $tabla.='</tr>';
      $tabla.='</tbody>
      </table></center>';

      return $tabla;
    } 


    /*----- MIGRACION DE REQUERIMIENTOS A UN COMPONENTE-------*/
    function importar_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $post['proy_id']; /// proy id
          $com_id = $post['com_id']; /// com id
          $componente=$this->model_componente->get_componente($com_id);
          
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
               
          /*------------------- Migrando ---------------*/
          $lineas = file($archivotmp);
          $i=0;
          $nro=0;
          //Recorremos el bucle para leer línea por línea
          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){
                $datos = explode(";",$linea);
                
                if(count($datos)==21){
                  $cod_ope = (int)$datos[0]; //// Codigo Componente
                  $cod_partida = (int)$datos[1]; //// Codigo partida
                //  $verif_com_ope=$this->model_producto->verif_componente_operacion($com_id,$cod_ope);
                  $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                  $detalle = utf8_encode(trim($datos[3])); //// descripcion
                  $unidad = utf8_encode(trim($datos[4])); //// Unidad
                  $cantidad = (int)$datos[5]; //// Cantidad
                  $unitario = (float)$datos[6]; //// Costo Unitario
                  $total = (float)$datos[7]; //// Costo Total
                  if(!is_numeric($unitario)){
                    if($cantidad!=0){
                      $unitario=round(($total/$unitario),2); 
                    }
                  }

                  $var=8;
                  for ($i=1; $i <=12 ; $i++) {
                    $m[$i]=(float)$datos[$var]; //// Mes i
                    if($m[$i]==''){
                      $m[$i]=0;
                    }
                    $var++;
                  }

                  $observacion = utf8_encode(trim($datos[20])); //// Observacion

                  if(count($par_id)!=0 & $cod_ope==$componente[0]['com_nro'] & $cod_partida!=0){
                     $nro++;
                     $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/REQ-DEL/'.$this->gestion, /// Codigo Insumo
                      'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                      'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                      'ins_costo_unitario' => $unitario, /// Costo Unitario
                      'ins_costo_total' => $total, /// Costo Total
                      'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                      'par_id' => $par_id[0]['par_id'], /// Partidas
                      'ins_observacion' => strtoupper($observacion), /// Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'aper_id' => $proyecto[0]['aper_id'], /// aper id
                      );
                      $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                      $ins_id=$this->db->insert_id();

                      /*----------------------------------------------------------*/
                      $data_to_store2 = array( ///// Tabla insumocomponente
                        'com_id' => $com_id, /// com_id
                        'ins_id' => $ins_id, /// ins_id
                      );
                      $this->db->insert('insumocomponente', $data_to_store2);
                     /*----------------------------------------------------------*/

                      $data_to_store = array( 
                        'ins_id' => $ins_id, /// Id Insumo
                        'g_id' => $this->gestion, /// Gestion
                        'insg_monto_prog' => $total, /// Monto programado
                      );
                      $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                      $insg_id=$this->db->insert_id();

                      $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
                      $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                      if(count($fuentes)==1){
                        /*------------------- Guardando Fuente Financiamiento ------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store3 = array( 
                        'insg_id' => $insg_id, /// Id Insumo gestion
                        'ifin_monto' => $total, /// Monto programado
                        'ifin_gestion' => $this->gestion, /// Gestion
                        'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
                        'ff_id' => $fuentes[0]['ff_id'], /// ff id
                        'of_id' => $fuentes[0]['of_id'], /// ff id
                        'nro_if' => 1, /// Nro if
                        );
                        $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
                        $ifin_id=$this->db->insert_id();

                        for ($p=1; $p <=12 ; $p++) { 
                          if($m[$p]!=0 & is_numeric($unitario)){
                            $data_to_store4 = array( 
                              'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                              'mes_id' => $p, /// Mes 
                              'ipm_fis' => $m[$p], /// Valor mes
                            );
                            $this->db->insert('ifin_prog_mes', $data_to_store4); ///// Guardar en Tabla Insumo Financiamiento Programado Mes
                          }
                        }
                        /*-----------------------------------------------------------*/ 
                      }

                  }

                }

              }
              $i++;
            }

            $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
            redirect('prog/ins_com/'.$com_id.'');
            /*--------------------------------------------*/
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect('prog/ins_com/'.$com_id.'/false');
          } 
          elseif ($filesize > 100000000) {
              //redirect('');
          } 
          else {
              $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
              echo '<script>alert("' . $mensaje . '")</script>';
          }

      } else {
          show_404();
      }
    }


    /*----------- REPORTE CONSOLIDADO PARTIDAS (DELEGADO)--------------*/
    public function reporte_partida($com_id){
        $html = $this->consolidado_partidas($com_id,2);
        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        ini_set('memory_limit','556M');
        ini_set('max_execution_time', 90000);
        $dompdf->render();
        $dompdf->stream("reporte_partida_consolidado.pdf", array("Attachment" => false));
    }

    /*----------- REPORTE REQUERIMIENTOS - OPERACION (PRODUCTO)--------------*/
    public function reporte_requerimientos_delegado($com_id){
        $html = $this->consolidado_partidas($com_id,1);
        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        ini_set('memory_limit','556M');
        ini_set('max_execution_time', 9000000);
        $dompdf->render();
        $dompdf->stream("reporte_requerimientos.pdf", array("Attachment" => false));
    }

    /*------------- CABECERA REPORTE -----------------*/
    function consolidado_partidas($com_id,$tp){
      //  $producto = $this->model_producto->get_producto_id($prod_id); ///// DATOS DEL PRODUCTO
        $componente = $this->model_componente->get_componente($com_id); /// COMPONENTE 
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
        if($tp==1){
          $tabla=$this->list_requerimientos($com_id,2);
          $tit='REQUERIMIENTOS DEL PROYECTO';
        }
        else{
          $tabla=$this->consolidado_partidas_delegado($com_id,2);
          $tit='CONSOLIDADO PARTIDAS DEL PROYECTO';
        }

        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -125px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"><</center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                            <b>REPORTE : </b> '.$tit.' - EJECUCI&Oacute;N DELEGADA<br>
                            <b>PROYECTO DE INVERSI&Oacute;N : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                            <b>COMPONENTE : </b>'.$componente[0]['com_componente'].'<br>
                            </font>
                        </td>
                        <td width=20%; text-align:center;"">
                          FECH. IMP. : '.date('d/m/Y').'<br>
                          RESP. : '.$this->session->userdata('funcionario').'
                        </td>
                    </tr>
                </table><hr>
           </div>
           <div id="footer">
             <hr>
             <table>
                <tr>
                    <td width=33%;>
                      <table border=1>
                        <tr>
                          <td><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>JEFATURAS DE DEPARTAMENTOS / SERV. GENERALES REGIONAL </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>GERENCIA GENERAL / GENERANCIAS DE AREA </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                  <td><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td></td>
                  <td align=right><p class="page">Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p>
             <div style="page-break-after;">
              <br>'.$tabla.'
             </div>
             </p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*--------------- EXPORTAR REQUERIMIENTOS DE COMPONENTES (Delegado)--------------*/
    public function xcel_reporte_partida($com_id){
      $componente = $this->model_componente->get_componente($com_id); /// COMPONENTE 
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
      $req=$this->consolidado_partidas_delegado($com_id,2);
      date_default_timezone_set('America/Lima');
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");

      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel;charset=UTF-8');
      header("Content-Disposition: attachment; filename=Reporte_requerimiento_delegado_".$componente[0]['com_componente']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");

      echo '
      <table border="0" cellpadding="0" cellspacing="0" class="tabla">
        <tr class="modo1">
          <td colspan="4"></td>
        </tr>
        <tr class="modo1">
          <td colspan="4">
            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                            <b>REPORTE : </b> CONSOLIDADO PARTIDAS DEL PROYECTO DE INVERSI&Oacute;N - EJECUCI&Oacute;N DELEGADO<br>
                            <b>PROYECTO DE INVERSI&Oacute;N : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(''.$proyecto[0]['proy_nombre'], 'cp1252', 'UTF-8').'<br>
                            <b>COMPONENTE : </b>'.mb_convert_encoding(''.$componente[0]['com_componente'], 'cp1252', 'UTF-8').'
                            </font>
          </td>
        </tr>
      </table><br>';
      echo "".$req."";
    }







    function importar_archivo_requerimiento(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $proy_id = $post['proy_id'];
            $pfec_id = $post['pfec_id'];
            $com_id = $post['com_id'];
          
            $tipo = $_FILES['archivo']['type'];
            $tamanio = $_FILES['archivo']['size'];
            $archivotmp = $_FILES['archivo']['tmp_name'];

            $filename = $_FILES["archivo"]["name"];
            $file_basename = substr($filename, 0, strripos($filename, '.'));
            $file_ext = substr($filename, strripos($filename, '.'));
            $allowed_file_types = array('.csv');
            if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
                 
                $lineas=$this->subir_requerimientos($proy_id,$com_id,$archivotmp);

                $this->session->set_flashdata('success','SE IMPORTARON Y SE REGISTRARON '.$lineas.' REQUERIMIENTOS (ACTIVOS FIJOS)');
                redirect(site_url("") . '/prog/ins_com/'.$proy_id.'/'.$com_id.'/true');
            } 
            elseif (empty($file_basename)) {
                echo "<script>alert('SELECCIONE ARCHIVO .CSV')</script>";
            } 
            elseif ($filesize > 100000000) {
                //redirect('');
            } 
            else {
                $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
                echo '<script>alert("' . $mensaje . '")</script>';
            }

        } else {
            show_404();
        }
    }


    public function subir_requerimientos($proy_id,$com_id,$archivotmp){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO  
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
        $conf=$this->model_proyecto->configuracion(); /// Confirmacion
        $lineas = file($archivotmp);
        
        $i=0;
        $nro=0;
        //Recorremos el bucle para leer línea por línea
        foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
                $datos = explode(";",$linea);
                if(count($datos)==27){
                        $tp = (int)$datos[0]; //// Tipo de Insumo
                       $veriff = $this->verif_fecha($datos[5]);
                       if($tp>=is_numeric(1) & $tp<=is_numeric(9) & $datos[5]!='' & $veriff=='true')
                       {
                       $cod_partida = (int)$datos[1]; //// Codigo Partida
                       $desc_partida = utf8_encode($datos[2]); //// Descripcion Partida
                       $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA
                       $detalle = utf8_encode($datos[3]); //$datos[0]// detalle 
                       $unidad = utf8_encode($datos[4]); ///// unidad de medida
                       $f_requerida = $datos[5]; //// Fecha requerida
                       

                       $obj_perfil = utf8_encode($datos[6]); //// Objetivo , Perfil / consultorias
                       $duracion = (int)$datos[7]; //// Duracion / consultorias
                       if($datos[7]==''){
                        $duracion = 0; //// Duracion / consultorias
                       }
                       
                       $fecha_inicio = $datos[8]; //// Fecha Inicio / consultorias
                       $veriff = $this->verif_fecha($fecha_inicio); //// verifica de Inicio
                       if($datos[8]=='' || $veriff=='false'){
                        $fecha_inicio =date('d/m/Y');
                       }

                       $fecha_final = $datos[9]; //// Fecha Inicio / consultorias
                       $veriff = $this->verif_fecha($fecha_final); //// verifica de Inicio
                       if($datos[9]=='' || $veriff=='false'){
                        $fecha_final =date('d/m/Y');
                       }

                       $evaluador = utf8_encode($datos[10]); //// Evaluador / consultorias
                       $cantidad = (int)$datos[11]; //// Cantidad
                       $unitario = (float)$datos[12]; //// Costo Unitario
                       $total = (float)$datos[13]; //// Costo Total
                       $observacion = utf8_encode($datos[26]); //// Observacion insumo
                       if(!is_numeric($unitario)){
                        if($cantidad!=0){
                           $unitario=round(($total/$unitario),2); 
                        }
                       }
                        $var=14;
                       for ($i=1; $i <=12 ; $i++) { 
                           
                           $m[$i]=(float)$datos[$var]; //// Mes i
                           if($m[$i]==''){
                            $m[$i]=0;
                           }

                        $var++;
                       }

                        $valores_f_requerida = explode ("/", $f_requerida);
                        $gestion_requerimiento   = $valores_f_requerida[2]; //// Gestion del Requerimiento

                        $veriff = $this->verif_fecha($f_requerida); //// verifica de requerimiento
                        if($veriff=='true' & count($par_id)!=0) //// verif fecha de requerimiento
                        {
                            
                            if($tp==8){
                                $nro_ins=$conf[0]['conf_activos']+1;
                                $nro++;
                                $update_conf = array('conf_activos' => $nro_ins);
                                $this->db->where('ide', $this->gestion);
                                $this->db->update('configuracion', $update_conf);

                                $query=$this->db->query('set datestyle to DMY');
                                $data_to_store = array( 
                                'ins_codigo' => $this->session->userdata("name").'/INS/AF/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                                'ins_fecha_requerimiento' => $f_requerida, /// Fecha de Requerimiento
                                'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                                'ins_cant_requerida' => $cantidad, /// Cantidad Requerida
                                'ins_costo_unitario' => $unitario, /// Costo Unitario
                                'ins_costo_total' => $total, /// Costo Total
                                'ins_tipo' => $tp, /// Ins Tipo
                                'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                                'par_id' => $par_id[0]['par_id'], /// Partidas
                                'ins_observacion' => strtoupper($observacion), /// Observacion
                                'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                                'aper_id' => $proyecto[0]['aper_id'], /// aper id
                                );
                                $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                                $ins_id=$this->db->insert_id();

                                /*----------------------------------------------------------*/
                                $data_to_store2 = array( ///// Tabla InsumoComponente
                                    'com_id' => $com_id, /// com_id
                                    'ins_id' => $ins_id, /// ins_id
                                );
                                $this->db->insert('insumocomponente', $data_to_store2);
                               /*----------------------------------------------------------*/

                                $gestion_fase=$fase[0]['pfec_fecha_inicio'];

                                /*---------------- Recorriendo Gestiones de la Fase -----------------------*/
                                for ($g=$fase[0]['pfec_fecha_inicio']; $g <=$fase[0]['pfec_fecha_fin'] ; $g++)
                                {
                                    $gest=$g;
                                    $gest=($gest%100);
                                    if($g==$gestion_requerimiento || $gest==$gestion_requerimiento){
                                        
                                        $data_to_store = array( 
                                            'ins_id' => $ins_id, /// Id Insumo
                                            'g_id' => $g, /// Gestion
                                            'insg_monto_prog' => $total, /// Monto programado
                                        );
                                        $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                                        $insg_id=$this->db->insert_id();

                                        $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$g); //// DATOS DE LA FASE GESTION
                                        $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                                        if(count($fuentes)==1)
                                        {
                                            /*------------------- Guardando Fuente Financiamiento ------*/
                                            $query=$this->db->query('set datestyle to DMY');
                                            $data_to_store3 = array( 
                                            'insg_id' => $insg_id, /// Id Insumo gestion
                                            'ifin_monto' => $total, /// Monto programado
                                            'ifin_gestion' => $g, /// Gestion
                                            'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
                                            'ff_id' => $fuentes[0]['ff_id'], /// ff id
                                            'of_id' => $fuentes[0]['of_id'], /// ff id
                                            'nro_if' => 1, /// Nro if
                                            );
                                            $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
                                            $ifin_id=$this->db->insert_id();

                                            for ($p=1; $p <=12 ; $p++) { 
                                                if($m[$p]!=0 & is_numeric($unitario))
                                                {
                                                    $data_to_store4 = array( 
                                                    'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                                                    'mes_id' => $p, /// Mes 
                                                    'ipm_fis' => $m[$p], /// Valor mes
                                                    );
                                                    $this->db->insert('ifin_prog_mes', $data_to_store4); ///// Guardar en Tabla Insumo Financiamiento Programado Mes
                                                }
                                            }
                                            $valor=1; //// se insertaron por completo
                                            /*-----------------------------------------------------------*/ 
                                        }
                                        else{
                                            $valor=0; //// no se insertaron insumos fin y programados
                                        }
                                    }
                                    else{
                                        $data_to_store = array( 
                                            'ins_id' => $ins_id, /// Id Insumo
                                            'g_id' => $g, /// Gestion
                                            'insg_monto_prog' => 0, /// Monto programado
                                        );
                                        $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                                    }
                                }
                                /*------------------------------------------------------------------------*/
                            }
                        }
                   }    
                    
 
                }
            }
            $i++;
        }
        return $nro;
     }

       /*-------- ELIMINAR TODOS LOS REQUERIMIENTOS ---------*/
    function eliminar_insumos($com_id){
      $insumos = $this->model_componente->imsumo_componente($com_id); //// Insumo Componente

      foreach ($insumos as $row) {
        /*------------ UPDATE REQUERIMIENTO -------*/
        $update_ins= array(
          'fun_id' => $this->fun_id,
          'aper_id' => 0,
          'ins_estado' => 3
        );
        $this->db->where('ins_id', $row['ins_id']);
        $this->db->update('insumos', $this->security->xss_clean($update_ins));
        /*-----------------------------------------*/
       
        $update_insg= array(
          'insg_estado' => 3
        );
        $this->db->where('ins_id', $row['ins_id']);
        $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
      }
      
      redirect(site_url("") . '/prog/ins_com/'.$com_id.'');    
    }

    /*---------- MENU -----------*/
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

    /*=========================================================================================================================*/
    public function get_mes($mes_id){
      $mes[1]='Enero';
      $mes[2]='Febrero';
      $mes[3]='Marzo';
      $mes[4]='Abril';
      $mes[5]='Mayo';
      $mes[6]='Junio';
      $mes[7]='Julio';
      $mes[8]='Agosto';
      $mes[9]='Septiembre';
      $mes[10]='Octubre';
      $mes[11]='Noviembre';
      $mes[12]='Diciembre';

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

    function estilo_vertical(){
        $estilo_vertical = '<style>
        .saltopagina{page-break-after:always;}
        body{
            font-family: sans-serif;
            }
        table{
            font-size: 7px;
            width: 100%;
            background-color:#fff;
        }
        .mv{font-size:10px;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}

        .titulo_pdf {
            text-align: left;
            font-size: 7px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 7px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 7px;
        background-color: #1c7368;
        background-repeat: repeat-x;
        color: #FFFFFF;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #558FA6;
        border-bottom-color: #558FA6;
        font-family: "Trebuchet MS", Arial;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 7px;
        font-weight:bold;
       
        background-image: url(fondo_tr01.png);
        background-repeat: repeat-x;
        color: #34484E;
        font-family: "Trebuchet MS", Arial;
        }
        .tabla .modo1 td {
        padding: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #A4C4D0;
        border-bottom-color: #A4C4D0;
        }
    </style>';
        return $estilo_vertical;
    }
}