<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cprog_insumos_directo extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;

   public function __construct (){
        parent::__construct();
        
        if($this->session->userdata('fun_id')!=null){
        $this->load->model('menu_modelo');
        $this->load->library('pdf2');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('programacion/insumos/minsumos_delegado');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/model_componente');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tp_adm = $this->session->userData('tp_adm');

        }else{
            redirect('/','refresh');
        }
    }

    //PROGRAMACION DE INSUMOS A NIVEL DE ACTIVIDADES
    function prog_isumos_act($act_id){
        $data['menu']=$this->menu(2);
        $data['actividad']=$this->model_actividad->get_actividad_gestion($act_id,$this->gestion);

        if(count($data['actividad'])!=0){
            $data['producto'] = $this->model_producto->get_producto_id($data['actividad'][0]['prod_id']); ///// DATOS DEL PRODUCTO
            $data['componente'] = $this->model_componente->get_componente_pi($data['producto'][0]['com_id']); /// COMPONENTE 
            $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
            $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
            $data['titulo_proy'] = strtoupper($data['proyecto'][0]['tipo']);
            $data['fase'] = $this->model_faseetapa->get_id_fase($data['proyecto'][0]['proy_id']); //// FASE ACTIVA
            $data['part_padres'] = $this->model_partidas->lista_padres();//partidas padres
            $data['part_hijos'] = $this->model_partidas->lista_partidas();//partidas hijos
           // $data['fuente']=$this->model_faseetapa->presupuesto_asignados($data['proyecto'][0]['proy_id'],$this->gestion);

            $data['monto_asig']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],1);
            $data['monto_prog']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],2);
            $monto_a=0;$monto_p=0;$monto_saldo=0;
            
            if(count($data['monto_asig'])!=0){
                $monto_a=$data['monto_asig'][0]['monto'];
            }
            if(count($data['monto_prog'])){
                $monto_p=$data['monto_prog'][0]['monto'];
            }

            $data['monto_a']=$monto_a;
            $data['monto_p']=$monto_p;
            $data['monto_saldo']=round(($monto_a-$monto_p),2);

            $data['requerimientos'] = $this->list_requerimientos($act_id,1); /// Lista de requerimientos

            $data['partidas_ope']=$this->consolidado_partidas_directo($act_id,1); /// consolidado por partidas - Actividad
            $data['partidas_act']=$this->comparativo_partidas_acciones($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id']);
            //$data['partidas_act']=$this->consolidado_partidas_actividad($data['proyecto'],1); /// consolidado por partidas - actividad
        
            $this->load->view('admin/programacion/insumos/insumo_actividades/ins_actividad', $data);

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
    public function list_requerimientos($act_id,$tp){
      $lista_insumos = $this->minsumos->lista_insumos_act($act_id);
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
                    $tabla .= '<td align="center" style="height:15px;">';
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

    /*--------------- Comparativo Partidas A nivel De Acciones Operativas -------------------*/
    public function comparativo_partidas_acciones($dep_id,$aper_id){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Asig
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,2); // Prog

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $tabla .='<table id="dt_basic1" class="table table-bordered">
                  <thead>
                    <tr class="modo1" align=center>
                      <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                      <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                    </tr>
                  </thead>
                  <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
          $tabla .='<tr class="modo1" bgcolor='.$color.'>
                      <td align=center>'.$nro.'</td>
                      <td align=center>'.$row['codigo'].'</td>
                      <td align=left>'.$row['nombre'].'</td>
                      <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                      <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                    </tr>';
          $monto_asig=$monto_asig+$asig;
          $monto_prog=$monto_prog+$row['monto'];
        }

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.$row['nombre'].'</td>
                          <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }

      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';

      return $tabla;
    }
    /*----------------- VALIDA REQUERIMIENTO ACTIVIDAD -----------------*/
     public function valida_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']); /// Actividad id
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
          'ins_codigo' => $this->session->userdata("name").'/REQ-ACT/'.$this->gestion, /// Codigo Insumo
          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
          'ins_costo_unitario' => $costo_unitario, /// Costo Unitario
          'ins_costo_total' => $costo_total, /// Costo Total
          'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
          'par_id' => $partida, /// Partidas
          'ins_tipo' => 1, /// Ins Tipo
          'ins_observacion' => strtoupper($observacion), /// Observacion
          'fun_id' => $this->fun_id, /// Funcionario
          'aper_id' => $proyecto[0]['aper_id'], /// aper id
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          /*----------------------------------------------------------*/
          $data_to_store2 = array( ///// Tabla InsumoActividad
            'act_id' => $act_id, /// act id
            'ins_id' => $ins_id, /// ins_id
          );
          $this->db->insert('_insumoactividad', $data_to_store2);
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

        redirect(site_url("").'/prog/ins_act/'.$act_id.'');
            
      } else {
          show_404();
      }
    }


    /*----------------- VALIDA UPDATE REQUERIMIENTO ------------------*/
    public function valida_update_insumo(){
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
            'ins_estado' => 2,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
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
        
          $get_ins=$this->minsumos->get_requerimiento_actividad($ins_id);
          $this->session->set_flashdata('success','EL REQUERIMIENTO SE MODIFICO CORRECTAMENTE :)');
          redirect(site_url("").'/prog/ins_act/'.$get_ins[0]['act_id'].'');

      } else {
          show_404();
      }
    }


    /*----------------- TABLA - CONSOLIDADO OPERACION --------------*/
    public function consolidado_partidas_directo($act_id,$tp){
      $tabla='';
      $partidas = $this->minsumos->consolidado_partidas_directo($act_id);
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

    /*----------------- TABLA - CONSOLIDADO PARTIDAS ACTIVIDAD --------------*/
    public function consolidado_partidas_actividad($proyecto,$tp){
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
                  <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>'.$proyecto[0]['aper_id'].'NRO.</font></th>
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

    /*-------- ELIMINAR TODOS LOS REQUERIMIENTOS ACTIVIDAD ---------*/
    function eliminar_insumos_act($act_id){
      $insumos = $this->model_actividad->insumo_actividad($act_id); //// Insumo actividad

      foreach ($insumos as $row) {
        /*------------ UPDATE REQUERIMIENTO -------*/
        $update_ins= array(
          'fun_id' => $this->fun_id,
          'aper_id' => 0,
          'ins_estado' => 3,
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
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
      
      redirect(site_url("") . '/prog/ins_act/'.$act_id.'');    
    }

    
    /*----- MIGRACION DE REQUERIMIENTOS A UNA ACTIVIDAD -------*/
    function importar_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $post['proy_id']; /// proy id
          $act_id = $post['act_id']; /// act id
          
          $actividad = $this->model_actividad->get_actividad_id($act_id); ///// DATOS DE LA ACTIVIDAD
          
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

            $lineas = file($archivotmp);
            if($this->suma_monto_total($lineas)<=$saldo){
              /*------------------- Migrando ---------------*/
              $lineas = file($archivotmp);
              $i=0;
              $nro=0;
              //Recorremos el bucle para leer línea por línea
              foreach ($lineas as $linea_num => $linea){ 
                if($i != 0){
                    $datos = explode(";",$linea);
                    
                    if(count($datos)==21){
                    //  $cod_ope = (int)$datos[0]; //// Nro
                      $cod_partida = (int)$datos[1]; //// Codigo partida
                      $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                      $detalle = utf8_encode(trim($datos[3])); //// descripcion
                      $unidad = utf8_encode(trim($datos[4])); //// Unidad
                      $cantidad = (int)$datos[5]; //// Cantidad
                      $unitario = (float)$datos[6]; //// Costo Unitario
                      $total = (float)$datos[7]; //// Costo Total
                      if(!is_numeric($unitario)){
                        if($cantidad!=0){
                          $unitario=round(($total/$cantidad),2); 
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

                      if(count($par_id)!=0 & $cod_partida!=0){
                         $nro++;
                         $query=$this->db->query('set datestyle to DMY');
                          $data_to_store = array( 
                          'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                          'ins_costo_unitario' => $unitario, /// Costo Unitario
                          'ins_costo_total' => $total, /// Costo Total
                          'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                          'par_id' => $par_id[0]['par_id'], /// Partidas
                          'ins_tipo' => 1, /// Ins Tipo
                          'ins_observacion' => strtoupper($observacion), /// Observacion
                          'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                          'aper_id' => $proyecto[0]['aper_id'], /// aper id
                          'num_ip' => $this->input->ip_address(), 
                          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                          );
                          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                          $ins_id=$this->db->insert_id();

                          /*----------------------------------------------------------*/
                          $data_to_store2 = array( ///// Tabla InsumoActividad
                            'act_id' => $act_id, /// act_id
                            'ins_id' => $ins_id, /// ins_id
                          );
                          $this->db->insert('_insumoactividad', $data_to_store2);
                         /*----------------------------------------------------------*/
                          $gestion_fase=$fase[0]['pfec_fecha_inicio'];

                          /*---------------- Recorriendo Gestiones de la Fase -----------------------*/
                          for ($g=$fase[0]['pfec_fecha_inicio']; $g <=$fase[0]['pfec_fecha_fin'] ; $g++){
                              $data_to_store = array( 
                                'ins_id' => $ins_id, /// Id Insumo
                                'g_id' => $g, /// Gestion
                                'insg_monto_prog' => $total, /// Monto programado
                              );
                              $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                              $insg_id=$this->db->insert_id();

                              $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$g); //// DATOS DE LA FASE GESTION
                              $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                              if(count($fuentes)==1){
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

                  }
                  $i++;
                }

                $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
                redirect('prog/ins_act/'.$act_id.'');
                /*--------------------------------------------*/
            
            }
            else{
              $this->session->set_flashdata('danger','COSTO PROGRAMADO A SUBIR ES MAYOR AL SALDO POR PROGRAMAR. VERIFIQUE PLANTILLA A MIGRAR');
              redirect('prog/ins_act/'.$act_id.'/false');
            }

          }
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect('prog/ins_act/'.$act_id.'/false');
          } 
          elseif ($filesize > 100000000) {
            $this->session->set_flashdata('danger','TAMAÑO DEL ARCHIVO');
            redirect('prog/ins_act/'.$act_id.'/false');
          } 
          else {
              $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
              $this->session->set_flashdata('danger',$mensaje);
              redirect('prog/ins_prod/'.$prod_id.'/false');
          }


        }
        else{
          show_404();
      }
    }
    

    /*----- SUMA TOTAL MONTO REQUERIMIENTOS A IMPORTAR -------*/
    function suma_monto_total($requerimientos){
      $i=0; $suma=0;
      foreach ($requerimientos as $linea_num => $linea){ 
        if($i != 0){
            $datos = explode(";",$linea);
            
            if(count($datos)==21){
                $suma=$suma+(float)$datos[7];
            }
          }

          $i++;
      }

      return $suma;
    }

    /*----- MIGRACION DE REQUERIMIENTOS A UNA ACTIVIDAD -------*/
    function importar_requerimientos2(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $post['proy_id']; /// proy id
          $act_id = $post['act_id']; /// act id

          $actividad = $this->model_actividad->get_actividad_id($act_id); ///// DATOS DE LA ACTIVIDAD
          
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
                //  $cod_ope = (int)$datos[0]; //// Nro
                  $cod_partida = (int)$datos[1]; //// Codigo partida
                  $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                  $detalle = utf8_encode(trim($datos[3])); //// descripcion
                  $unidad = utf8_encode(trim($datos[4])); //// Unidad
                  $cantidad = (int)$datos[5]; //// Cantidad
                  $unitario = (float)$datos[6]; //// Costo Unitario
                  $total = (float)$datos[7]; //// Costo Total
                  if(!is_numeric($unitario)){
                    if($cantidad!=0){
                      $unitario=round(($total/$cantidad),2); 
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

                  if(count($par_id)!=0 & $cod_partida!=0){
                     $nro++;
                     $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                      'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                      'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                      'ins_costo_unitario' => $unitario, /// Costo Unitario
                      'ins_costo_total' => $total, /// Costo Total
                      'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                      'par_id' => $par_id[0]['par_id'], /// Partidas
                      'ins_tipo' => 1, /// Ins Tipo
                      'ins_observacion' => strtoupper($observacion), /// Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'aper_id' => $proyecto[0]['aper_id'], /// aper id
                      'num_ip' => $this->input->ip_address(), 
                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                      );
                      $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                      $ins_id=$this->db->insert_id();

                      /*----------------------------------------------------------*/
                      $data_to_store2 = array( ///// Tabla InsumoActividad
                        'act_id' => $act_id, /// act_id
                        'ins_id' => $ins_id, /// ins_id
                      );
                      $this->db->insert('_insumoactividad', $data_to_store2);
                     /*----------------------------------------------------------*/
                      $gestion_fase=$fase[0]['pfec_fecha_inicio'];

                      /*---------------- Recorriendo Gestiones de la Fase -----------------------*/
                      for ($g=$fase[0]['pfec_fecha_inicio']; $g <=$fase[0]['pfec_fecha_fin'] ; $g++){
                          $data_to_store = array( 
                            'ins_id' => $ins_id, /// Id Insumo
                            'g_id' => $g, /// Gestion
                            'insg_monto_prog' => $total, /// Monto programado
                          );
                          $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                          $insg_id=$this->db->insert_id();

                          $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$g); //// DATOS DE LA FASE GESTION
                          $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                          if(count($fuentes)==1){
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

              }
              $i++;
            }

            $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
            redirect('prog/ins_act/'.$act_id.'');
            /*--------------------------------------------*/
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect('prog/ins_act/'.$act_id.'/false');
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


    /*----------- REPORTE CONSOLIDADO PARTIDAS --------------*/
    public function reporte_partida($act_id){

        $html = $this->consolidado_partidas($act_id,2);
        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        ini_set('memory_limit','556M');
        ini_set('max_execution_time', 90000);
        $dompdf->render();
        $dompdf->stream("reporte_productos_consolidados.pdf", array("Attachment" => false));
    }

    /*----------- REPORTE REQUERIMIENTOS - OPERACION (DOMPDF) (ACTIVIDAD)--------------*/
    public function reporte_requerimientos_actividad2($prod_id){
        $html = $this->consolidado_partidas($prod_id,1);
        
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        ini_set('memory_limit','556M');
        ini_set('max_execution_time', 9000000);
        $dompdf->render();
        $dompdf->stream("reporte_requerimientos.pdf", array("Attachment" => false));
    }

    /*----------- REPORTE REQUERIMIENTOS - OPERACION (ACTIVIDAD)--------------*/
    public function reporte_requerimientos_actividad($act_id){
      $data['act_id']=$act_id;
      $data['actividad'] = $this->model_actividad->get_actividad_id($act_id); ///// DATOS DE LA ACTIVIDAD
      $data['producto'] = $this->model_producto->get_producto_id($data['actividad'][0]['prod_id']); ///// DATOS DEL PRODUCTO
      $data['componente'] = $this->model_componente->get_componente($data['producto'][0]['com_id']); /// COMPONENTE 
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); /// PROYECTO

      $data['mes'] = $this->mes_nombre();
      $data['lista_insumos'] = $this->minsumos->lista_insumos_act($act_id);

      $this->load->view('admin/programacion/insumos/insumo_actividades/requerimientos_actividad', $data);
    }

    /*------------- CABECERA REPORTE -----------------*/
    function consolidado_partidas($act_id,$tp){
        $actividad = $this->model_actividad->get_actividad_id($act_id); ///// DATOS DE LA ACTIVIDAD
        $producto = $this->model_producto->get_producto_id($actividad[0]['prod_id']); ///// DATOS DEL PRODUCTO
        $componente = $this->model_componente->get_componente_pi($producto[0]['com_id']); /// COMPONENTE 
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
        $mes = $this->mes_nombre();
        if($tp==1){
          $tabla=$this->list_requerimientos($act_id,2);
          $tit='<FONT FACE="courier new" size="2"><b>PLAN OPERATIVO ANUAL '.$this->gestion.' - PROGRAMACI&Oacute;N FISICO FINANCIERO</b></font>';
        }
        else{
          $tabla=$this->consolidado_partidas_directo($act_id,2);
          $tit='<FONT FACE="courier new" size="2"><b>PLAN OPERATIVO ANUAL '.$this->gestion.' - CONSOLIDADO POR PARTIDAS</b></font>';
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
                <table width="100%" border="0">
                    <tr>
                      <td width=20%; text-align:center;"">
                      <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="47px"></center>
                      </td>
                      <td width=80%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>'.$this->session->userdata('entidad').'</b><br>
                          <b>DIR. ADM. : </b> '.strtoupper($proyecto[0]['dep_departamento']).'<br>
                          <b>PROYECTO DE INVERSI&Oacute;N : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                          <b>COMPONENTE: </b>'.$componente[0]['com_nro'].'-'.$componente[0]['com_componente'].'<br>
                          <b>OPERACI&Oacute;N : </b> '.$producto[0]['prod_producto'].'<br>
                          <b>ACTIVIDAD : </b> '.$actividad[0]['act_actividad'].'
                          </font>
                      </td>
                    </tr>
                </table>'.$tit.'
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
                          <td><b>GERENCIA GENERAL / GERENCIAS DE AREA </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                  <td><p class="izq">POA - '.$this->gestion.', Aprobado mediante RD. Nro 116/18 de 05.09.2018</p></td>
                  <td></td>
                  <td align=right><p class="page">' .$mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario').' - Pagina </p></td>
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

    /*--------------- EXPORTAR REQUERIMIENTOS DE ACTIVIDADES --------------*/
    public function xcel_reporte_partida($act_id){
      $actividad = $this->model_actividad->get_actividad_id($act_id); ///// DATOS DE LA ACTIVIDAD
      $producto = $this->model_producto->get_producto_id($actividad[0]['prod_id']); ///// DATOS DEL PRODUCTO
      $componente = $this->model_componente->get_componente_pi($producto[0]['com_id']); /// COMPONENTE 
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
      $req=$this->consolidado_partidas_directo($act_id,2);
      date_default_timezone_set('America/Lima');
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");

      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel;charset=UTF-8');
      header("Content-Disposition: attachment; filename=Reporte_Operación_".$componente[0]['com_componente']."_$fecha.xls"); //Indica el nombre del archivo resultante
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
              <b>REPORTE : </b> CONSOLIDADO PARTIDAS DE LA ACTIVIDAD<br>
              <b>PROYECTO DE INVERSI&Oacute;N : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(''.$proyecto[0]['proy_nombre'], 'cp1252', 'UTF-8').'<br>
              <b>COMPONENTE : </b>'.$componente[0]['com_nro'].'-'.mb_convert_encoding(''.$componente[0]['com_componente'], 'cp1252', 'UTF-8').'<br>
              <b>OPERACI&Oacute;N : </b>'.mb_convert_encoding(''.$producto[0]['prod_producto'], 'cp1252', 'UTF-8').'<br>
              <b>ACTIVIDAD : </b>'.mb_convert_encoding(''.$actividad[0]['act_actividad'], 'cp1252', 'UTF-8').'
            </font>
          </td>
        </tr>
      </table><br>';
      echo "".$req."";
    }

    /*=========================================================================================================================*/
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