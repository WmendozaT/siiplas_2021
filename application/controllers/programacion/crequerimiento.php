<?php
class crequerimiento extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;

    function __construct(){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('programacion/insumos/minsumos'); /// gestion 2019
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
      //  $this->load->model('programacion/insumos/minsumos_delegado');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->conf_form5 = $this->session->userData('conf_form5');
        $this->load->library('programacionpoa');
        }else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }

    /*---- LISTA DE COMPONENTES SEGUN EL TIPO DE EJECUCION ----*/
    function list_requerimientos($prod_id){
      $data['producto']=$this->model_producto->get_producto_id($prod_id); // Producto
      $data['stylo']=$this->programacionpoa->estilo_tabla_form5();
      if(count($data['producto'])!=0){
        $data['componente']=$this->model_componente->get_componente($data['producto'][0]['com_id'],$this->gestion); // Componente
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']);
        $data['menu']=$this->genera_menu($data['proyecto'][0]['proy_id']);
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
        $tit='<small>PROYECTO : </small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'';
        /*--------- Gasto Corriente ----------*/
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['proyecto'][0]['proy_id']);
          $tit='<small>'.$data['proyecto'][0]['establecimiento'].' : </small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'';

        }

        $data['datos']=
                '<h1>'.$tit.'</h1>
                <h1><small>ACTIVIDAD : </small>COD - '.$data['producto'][0]['prod_cod'].'. '.$data['producto'][0]['prod_producto'].'</h1>';

        $data['prog_especial']='';
        
        if($data['proyecto'][0]['por_id']==1){
          $unidad=$this->model_componente->get_componente($data['producto'][0]['uni_resp'],$this->gestion);
          $data['prog_especial']='<h1><font color=blue>UNIDAD RESP. : <b>'.$unidad[0]['tipo_subactividad'].' '.$unidad[0]['serv_descripcion'].'</b></font></h1>';
        }
        
        $data['part_padres'] = $this->model_partidas->lista_padres();//partidas padres
        $data['part_hijos'] = $this->model_partidas->lista_partidas();//partidas hijos
        
        $data['requerimientos'] = $this->mis_requerimientos($prod_id,$data['componente']); /// Lista de requerimientos 2020 
        $data['button']=$this->programacionpoa->button_form5();

        $this->load->view('admin/programacion/requerimiento/list_requerimientos', $data);
      }
      else{
        echo "Error !!!";
      }
    }



    /*--------- VALIDA ADD REQUERIMIENTO ----------*/
     public function valida_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod
        $detalle = $this->security->xss_clean($post['ins_detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['ins_cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['ins_costo_u']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costo']); /// costo Total
        $um_id = $this->security->xss_clean($post['um_id']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['partida_id']); /// costo unitario
        $observacion = $this->security->xss_clean($post['ins_observacion']); /// Observacion

        $producto=$this->model_producto->get_producto_id($prod_id); // Producto
        $componente=$this->model_componente->get_componente($producto[0]['com_id'],$this->gestion); // Componente
        $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); /// DATOS DEL PROYECTO
        
        $umedida=$this->model_insumo->get_unidadmedida($um_id);

          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
          'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
          'ins_costo_unitario' => $costo_unitario, /// Costo Unitario
          'ins_costo_total' => $costo_total, /// Costo Total
          'ins_unidad_medida' => $umedida[0]['um_descripcion'], /// Insumo Unidad de Medida
          'ins_gestion' => $this->gestion, /// Insumo gestion
          'par_id' => $partida, /// Partidas
          'ins_tipo' => 1, /// Ins Tipo
          'ins_observacion' => strtoupper($observacion), /// Observacion
          'fun_id' => $this->fun_id, /// Funcionario
          'aper_id' => $proyecto[0]['aper_id'], /// aper id
          'com_id' => $producto[0]['com_id'], /// com id 
          'form4_cod' => $producto[0]['prod_cod'], /// aper id
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          /*--------------------------------------------------------*/
            $data_to_store2 = array( ///// Tabla InsumoProducto
                'prod_id' => $prod_id, /// prod id
                'ins_id' => $ins_id, /// ins_id
                'tp_ins' => $proyecto[0]['tp_id'], /// tp id                
              );
              $this->db->insert('_insumoproducto', $data_to_store2);
            /*----------------------------------------------------------*/
          

            /*------------ PARA LA GESTION 2020 ---------*/
            for ($i=1; $i <=12 ; $i++) {
              $pfin=$this->security->xss_clean($post['m'.$i]);
              if($pfin!=0){
                  $data_to_store4 = array( 
                    'ins_id' => $ins_id, /// Id Insumo
                    'mes_id' => $i, /// Mes 
                    'ipm_fis' => $pfin, /// Valor mes
                    'g_id' => $this->gestion, /// Gestion
                    );
                  $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
              }
            }

          $get_ins=$this->model_insumo->get_insumo_producto($ins_id);
            if(count($get_ins)==1){
              $this->session->set_flashdata('success','EL REQUERIMIENTO SE REGISTRO CORRECTAMENTE :)');
            }
            else{
              $this->session->set_flashdata('danger','EL REQUERIMIENTO NOSE REGISTRO CORRECTAMENTE, VERIFIQUE DATOS :(');
            }

        redirect(site_url("").'/prog/requerimiento/'.$prod_id.'');
            
      } else {
          show_404();
      }
    }

    /*--- VALIDA UPDATE REQUERIMIENTO A NIVEL DE OPERACIONES ---*/
     public function valida_update_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
        $detalle = $this->security->xss_clean($post['detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costot']); /// costo Total
        $umedida = $this->security->xss_clean($post['iumedida']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['par_hijo']); /// costo unitario
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion

        $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos productos
        $producto=$this->model_producto->get_producto_id($insumo[0]['prod_id']); /// Get producto
        $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion); /// Get Componente
        $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); ////// DATOS DEL PROYECTO

      
        /*------------ UPDATE REQUERIMIENTO -------*/
          $update_ins= array(
            'ins_cant_requerida' => $cantidad,
            'ins_costo_unitario' => $costo_unitario,
            'ins_costo_total' => $costo_total,
            'ins_detalle' => strtoupper($detalle),
            'par_id' => $partida, /// Partidas
            'ins_unidad_medida' => strtoupper($umedida),
            'ins_observacion' => strtoupper($observacion),
            'fun_id' => $this->fun_id,
            'com_id' => $producto[0]['com_id'], /// com id 
            'form4_cod' => $producto[0]['prod_cod'], /// aper id
            'ins_estado' => 2,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
          );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('insumos', $this->security->xss_clean($update_ins));
        /*-----------------------------------------*/

        /*-------- DELETE INSUMO PROGRAMADO --------*/  
          $this->db->where('ins_id', $ins_id);
          $this->db->delete('temporalidad_prog_insumo');
          /*------------------------------------------*/ 

          for ($i=1; $i <=12 ; $i++) {
            $pfin=$this->security->xss_clean($post['mm'.$i]);
            if($pfin!=0){
              $data_to_store4 = array( 
                'ins_id' => $ins_id, /// Id Insumo
                'mes_id' => $i, /// Mes 
                'ipm_fis' => $pfin, /// Valor mes
                'g_id' => $this->gestion, /// Gestion
                );
              $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
            }
          }

        $this->session->set_flashdata('success','EL REQUERIMIENTO SE MODIFICO CORRECTAMENTE :)');
        redirect(site_url("").'/prog/requerimiento/'.$producto[0]['prod_id']);

      } else {
          show_404();
      }
    }

    /*----------- LISTA DE REQUERIMIENTOS (2020) --------------*/
    public function mis_requerimientos($prod_id,$componente){
      $lista_insumos = $this->model_insumo->lista_insumos_prod($prod_id);
      $tabla='';
      $total=0;
      $tabla.='
      <input type="hidden" name="prod_id" id="prod_id" value="'.$prod_id.'">
      <input type="hidden" name="base" value="'.base_url().'">
      <table id="dt_basic" class="table table table-bordered" width="100%">
          <thead>
            <tr class="modo1">
              <th></th>
              <th>PARTIDA</th>
              <th>DETALLE REQUERIMIENTO</th>
              <th>UNIDAD</th>
              <th>CANTIDAD</th>
              <th>UNITARIO</th>
              <th>TOTAL</th>
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
              <th>ELIMINAR</th>
              <th>COD. ACT.</th>';
              $tabla.='
            </tr>
          </thead>
          <tbody>';
          $cont = 0;
          foreach ($lista_insumos as $row) {
            $color='';
            //$prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
            $ins_cert=0;
            if($row['ins_monto_certificado']!=0){
              $ins_cert=1;
            }

           /* if(count($prog)!=0){
              if(($row['ins_costo_total'])!=$prog[0]['programado_total']){
                $color='#f5bfb6';
              }
            }*/      
            $cont++;
            $tabla .= '<tr class="modo1" bgcolor="'.$color.'" title='.$row['ins_id'].'>';
              $tabla .= '<td align="center">';
              if($this->tp_adm==1 || $this->conf_form5==1){
                if($ins_cert==0){
                  $tabla.='
                  <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                }
                else{
                  $tabla.='<font color=red><b>CERTIFICADO</b></font>';
                }
                
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
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td>0</td>';
              }
              /*if(count($prog)!=0){
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
              }*/
              $tabla .= '<td>'.$row['ins_observacion'].'</td>
              <td>';
              if($this->tp_adm==1 || $this->conf_form5==1){
                if($ins_cert==0){
                  $tabla.='
                  <center>
                    <input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
                  </center>';
                }
              }
              $tabla.='
                </td>
                <td>';
                  if($this->tp_adm==1 || $this->conf_form5==1){
                  $productos = $this->model_producto->list_prod($componente[0]['com_id']); // Lista de productos
                    $tabla .='<select class="form-control" onchange="doSelectAlert(event,this.value,'.$row['ins_id'].');">';
                      foreach($productos as $pr){
                   
                          if($pr['prod_id']==$row['prod_id']){
                            $tabla .="<option value=".$pr['prod_id']." selected>".$pr['prod_cod']."</option>";
                          }
                          else{
                            $tabla .="<option value=".$pr['prod_id'].">".$pr['prod_cod']."</option>"; 
                          } 
                     
                      }
                    $tabla.='</select>';
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
              <td colspan="6"> TOTAL </td>
              <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
              <td colspan="15"></td>
            </tr>
        </table>';

      return $tabla;
    }


    /*--- ELIMINAR TODOS LOS REQUERIMIENTOS DE LA OPERACION/ACTIVIDAD ---*/
    function eliminar_todos_insumos($prod_id){
      $insumos = $this->model_insumo->lista_insumos_prod($prod_id); //// Insumos Operacion

      foreach ($insumos as $row) {
        /*-------- DELETE INSUMO PROGRAMADO --------*/  
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->delete('temporalidad_prog_insumo');
        /*------------------------------------------*/

        /*-------- DELETE INSUMO --------*/
          $this->db->where('prod_id', $prod_id);
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->delete('_insumoproducto');
          /*--------------------------------*/

        /*-------- DELETE INSUMO  --------*/  
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->delete('insumos');
        /*--------------------------------*/
      }
      
      redirect(site_url("").'/prog/requerimiento/'.$prod_id.'');    
    }
   
    /*------ CAMBIA ALINEACION A ACTIVIDAD 2022---------*/
    function cambia_actividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('prod_id', 'id producto', 'required|trim');
          $this->form_validation->set_message('required', 'El campo es es obligatorio');
        
          $post = $this->input->post();
          $prod_id= $this->security->xss_clean($post['prod_id']);
          $ins_id= $this->security->xss_clean($post['ins_id']);
           
          $update_proy = array(
            'prod_id' => $prod_id,
          );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('_insumoproducto', $update_proy);
              
      }else{
          show_404();
      }
    }

    /*---- GET DATOS REQUERIMIENTO (Vigente)-----*/
    public function get_requerimiento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']);
        $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos productos
        $producto=$this->model_producto->get_producto_id($insumo[0]['prod_id']); /// Get producto
        $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion); /// Get Componente
        $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); ////// DATOS DEL PROYECTO

        $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
        $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
        

        $m_asig=0;$m_prog=0;
        if(count($monto_asig)!=0){
          $m_asig=$monto_asig[0]['monto'];
        }
        if(count($monto_prog)!=0){
          $m_prog=$monto_prog[0]['monto'];
        }

        $saldo=($m_asig-$m_prog);
        
        $par_padre=$this->model_partidas->get_partida_padre($insumo[0]['par_depende']); /// lista de partidas padres
        $lista_partidas=$this->programacionpoa->partidas_dependientes($insumo); /// Lista de Insumos dependientes
        $temporalidad=$this->programacionpoa->distribucion_financiera($insumo); /// Distribucion Financiera
        $lista_umedida=$this->programacionpoa->unidades_medida($insumo); /// Lista de Unidad de medida

        if(count($insumo)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'insumo' => $insumo,
            'monto_saldo' => $saldo+$insumo[0]['ins_costo_total'],
            'lista_partidas'=> $lista_partidas,
            'lista_umedida'=> $lista_umedida,
            'ppdre' => $par_padre,
            'prog' => $temporalidad,
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


    /*------ ELIMINAR GET REQUERIMIENTO ------*/
    function delete_get_requerimiento(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $ins_id = $this->security->xss_clean($post['ins_id']); // ins id

          /*-------- DELETE INSUMO PROGRAMADO --------*/  
          $this->db->where('ins_id', $ins_id);
          $this->db->delete('temporalidad_prog_insumo');
          /*------------------------------------------*/

          /*---- DELETE INSUMO PRODUCTO ----*/  
            $this->db->where('ins_id', $ins_id);
            $this->db->delete('_insumoproducto');
          /*--------------------------------*/
          
          /*-------- DELETE INSUMO  --------*/  
          $this->db->where('ins_id', $ins_id);
          $this->db->delete('insumos');
          /*--------------------------------*/

          $insumo=$this->model_insumo->get_requerimiento($ins_id);
          if(count($insumo)==0){
            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }
          
          echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }

    /*---- CAMBIA EL ID DEL INSUMO Y LO LLEVA A INSUMOPRODUCTO ----*/
    function update_id_requerimientos_pi($proy_id){
      $productos=$this->model_producto->list_productos_proyecto($proy_id);
      foreach($productos as $rowp){
        //echo "prod_id : ".$rowp['prod_id']." - DESC. ".$rowp['prod_producto']."<br>";
          $lista_insumos=$this->model_producto->lista_insumos_por_producto($rowp['prod_id']);
          if(count($lista_insumos)!=0){
            foreach($lista_insumos as $rowi){
              //echo "ins_id : ".$rowi['ins_id']." - ".$rowi['ins_detalle']."<br>";
              //----- Inserrta el id insumo a insumoproducto
              $data_to_store = array( 
                'prod_id' => $rowp['prod_id'],
                'ins_id' => $rowi['ins_id'],
                'tp_ins' => 1,
              );
              $this->db->insert('_insumoproducto', $data_to_store);
              //--------------------------------------------

              //----- Elimina la relacion Insumoactividad
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->where('act_id', $rowi['act_id']);
              $this->db->delete('_insumoactividad');
              //--------------------------------------------
            }
          }
          else{
            redirect('admin/proy/list_proy#tabs-a'); ///// Lista de Proyectos de Inversion
          }
      }

      redirect('admin/proy/list_proy#tabs-a'); ///// Lista de Proyectos de Inversion
    }



    /*--------- Lista Partidas Hijos -----------*/
    public function combo_partidas_hijos(){
      //echo "urbanizaciones";
      $salida = "";
      $id_pais = $_POST["elegido"];
      // construimos el combo de ciudades deacuerdo al pais seleccionado
      $combog = pg_query("SELECT * FROM partidas WHERE par_depende=$id_pais");
      $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE PARTIDA', 'cp1252', 'UTF-8') . "</option>";
      while ($sql_p = pg_fetch_row($combog)) {
          $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[4]." - ".$sql_p[1] . "</option>";
      }
      echo $salida;
    }

    /*--------- Lista Unidades de Medida -----------*/
    public function combo_unidad_medida(){
      //echo "urbanizaciones";
      $salida = "";
      $par_id = $_POST["elegido"];
      // construimos el combo de ciudades deacuerdo al pais seleccionado
      $combog = pg_query('select *
              from par_umedida pum
              Inner Join insumo_unidadmedida as ium on ium.um_id = pum.um_id
              where pum.par_id='.$par_id.'
              order by ium.um_id asc');
      $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE UNIDAD DE MEDIDA', 'cp1252', 'UTF-8') . "</option>";
      while ($sql_p = pg_fetch_row($combog)) {
          $salida .= "<option value='" . $sql_p[3] . "'>" .$sql_p[4]. "</option>";
      }
      echo $salida;
    }

    /*--------- Lista Partidas Hijos Asignados-----------*/
    public function combo_partidas_hijos_asignados(){
        $salida = "";
        $id_pais = $_POST["elegido"]; /// codigo Partida
        $aper_id = $_POST["aper"]; /// aper id
        $tp=$_POST["tp"]; /// tp
        $id = $_POST["id"]; /// cite id | ins id

        if($tp==0){
          $cite=$this->model_modrequerimiento->get_cite_insumo($id); /// Datos cite
          $tipo_mod=$cite[0]['tipo_modificacion'];
        }
        else{
          $insumo= $this->model_insumo->get_requerimiento($id); /// Datos requerimientos productos
          $tipo_mod=$insumo[0]['ins_tipo_modificacion'];
        }
        

        if($tipo_mod==0){
          $combog = pg_query('
            select pg.par_id,pg.partida as par_codigo,p.par_nombre,p.par_depende,pg.importe
            from ptto_partidas_sigep pg
            Inner Join partidas as p On p.par_id=pg.par_id
            where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and p.par_depende='.$id_pais.'
            order by pg.partida asc');
        }
        else{
          $combog = pg_query('
            select par.par_id,par.par_codigo,par.par_nombre,par.par_depende,SUM(pr.presupuesto_revertido) ppto_revertido
            from lista_partidas_revertidas('.$this->gestion.') pr
            Inner Join partidas as par On par.par_id=pr.par_id
            where pr.aper_id='.$aper_id.' and par.par_depende='.$id_pais.'
            group by par.par_id,par.par_codigo,par.par_nombre,par.par_depende
            order by par.par_codigo asc');
        }

        $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE PARTIDA', 'cp1252', 'UTF-8') . "</option>";
        while ($sql_p = pg_fetch_row($combog)) {
            $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[1]." - ".$sql_p[2] . "</option>";
        }
        echo $salida;
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

    /*--------------- GENERA MENU -------------*/
    public function genera_menu($proy_id){
      $id_f = $this->model_faseetapa->get_id_fase($proy_id);
      $enlaces=$this->menu_modelo->get_Modulos_programacion(2);
      $tabla='';
      $tabla.='<nav>
              <ul>
                  <li>
                      <a href='.site_url("admin").'/dashboard'.' title="MENU PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                  </li>
                  <li class="text-center">
                      <a href='.base_url().'index.php/admin/proy/mis_proyectos/1'.' title="PROGRAMACI&Oacute;N POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N POA</span></a>
                  </li>';
                  if(count($id_f)!=0){
                      for($i=0;$i<count($enlaces);$i++){ 
                          $tabla.='
                          <li>
                              <a href="#" >
                                  <i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>
                              <ul >';
                              $submenu= $this->menu_modelo->get_Modulos_sub($enlaces[$i]['o_child']);
                              foreach($submenu as $row) {
                                 $tabla.='<li><a href='.base_url($row['o_url'])."/".$id_f[0]['proy_id'].'>'.$row['o_titulo'].'</a></li>';
                              }
                          $tabla.='</ul>
                          </li>';
                      }
                  }
              $tabla.='
              </ul>
          </nav>';

      return $tabla;
    }

}