<?php
class cajuste_crequerimiento extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;

    function __construct(){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
        $this->load->model('modificacion/model_modrequerimiento');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->conf_form5 = $this->session->userData('conf_form5');
        $this->load->library('programacionpoa');
        }else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }

    /*---- LISTA DE REQUERIMIENTOS POR SERVICIO ----*/
    function list_requerimientos_total($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion);
      $data['menu']=$this->genera_menu($data['componente'][0]['proy_id']);
      if(count($data['componente'])!=0){
        $fase = $this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['proyecto']=$this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
        $data['titulo']=$this->programacionpoa->titulo_ajuste($data['proyecto'],$data['componente']);
        $data['part_padres'] = $this->model_partidas->lista_padres();//partidas padres
        $data['part_hijos'] = $this->model_partidas->lista_partidas();//partidas hijos
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

        $data['datos_proyecto']= $data['proyecto'][0]['tipo_adm'].' : '.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].'-'.$data['proyecto'][0]['abrev'];


        $data['lista']=$this->tipo_lista_ope_act($com_id);
        $data['requerimientos']=$this->lista_requerimientos($com_id);

        $this->load->view('admin/programacion/requerimiento/list_requerimientos_ajuste_total', $data);
      }
      else{
        echo "Error !!!";
      }
    }

    /*---- tipo lista : Operacion-Actividad ----*/
    public function tipo_lista_ope_act($com_id){
      $tabla='';
      $operaciones=$this->model_producto->lista_operaciones($com_id);
        $tabla.='
          <section class="col col-4">
            <label class="label"><b>ALINEACI&Oacute;N ACTIVIDAD '.$this->gestion.'</b></label>
            <label class="input">
              <select class="form-control" id="prod_id" name="prod_id" title="SELECCIONE ACTIVIDAD">
                <option value="">Seleccione Actividad</option>';
                foreach($operaciones as $row){ 
                  $tabla.='<option value="'.$row['prod_id'].'">ACT. '.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
                } 
                $tabla.='      
              </select>
            </label>
          </section>';

      return $tabla;
    }

    /*----- LISTA REQUERIMIENTOS COMPLETO (2020) ------*/
    public function lista_requerimientos($com_id){
     // $lista_insumos=$this->model_modrequerimiento->lista_requerimientos($com_id);
      $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($com_id); /// Lista requerimientos

      $tabla='';
      $total=0;
      $tabla.='<input name="base" type="hidden" value="'.base_url().'">
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
                  $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  $cont++;
                    $tabla .='<tr>';
                    $tabla .='<td title='.$row['ins_id'].'>'.$cont.'</td>';
                    $tabla .='<td align=center bgcolor="#ecf9f7" title="CODIGO ACTIVIDAD"><font size=5 color=blue><br><b>'.$row['prod_cod'].'</b></font></td>';
                    $tabla .='<td align=center>
                                <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>
                                <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" >
                                  <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                                </a>
                              </td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';

                    if(count($prog)!=0){
                      $tabla.='
                      <td style="width:5%;">'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td> 
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                    }
                    else{
                      $tabla.='
                      <td style="width:5%;">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>
                      <td style="width:5%;" bgcolor="#ffeeeb">0</td>';
                    }
                    
                    $tabla .= ' 
                      <td style="width:8%;">'.$row['ins_observacion'].'</td>
                      <td style="width:2%;" bgcolor="#f3cbcb">
                        <center><input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/></center>
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
                    <td colspan="8"> TOTAL </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="16"></td>
                  </tr>
              </table>';

      return $tabla;
    }


    /*----- GET DATOS REQUERIMIENTO PARA AJUSTE ------*/
    public function get_requerimiento_ajuste(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']);
        $com_id = $this->security->xss_clean($post['com_id']);

        $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos productos

        $monto_asig=$this->model_ptto_sigep->get_partida_asignado_sigep($insumo[0]['aper_id'],$insumo[0]['par_id']);
        $monto_prog=$this->model_ptto_sigep->get_partida_accion($insumo[0]['aper_id'],$insumo[0]['par_id']);

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
        $lista_prod_act=$this->programacionpoa->list_prod_actividad($com_id,$insumo); /// Lista de Productos, Actividades

        if(count($insumo)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'insumo' => $insumo,
            'monto_saldo' => $saldo+$insumo[0]['ins_costo_total'],
            'lista_partidas'=> $lista_partidas,
            'lista_umedida'=> $lista_umedida,
            'lista_prod_act'=> $lista_prod_act,
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


    /*--- VALIDA UPDATE REQUERIMIENTO - AJUSTE POA ---*/
     public function valida_update_insumo_ajuste(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tp : 0 Nuevo, 1 Update

        if($tp==0){
          $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
          $prod_id = $this->security->xss_clean($post['prod_id']); /// prod/act id
          $com_id = $this->security->xss_clean($post['com_id']); /// Com id
          $detalle = $this->security->xss_clean($post['ins_detalle']); /// detalle
          $cantidad = $this->security->xss_clean($post['ins_cantidad']); /// cantidad
          $costo_unitario = $this->security->xss_clean($post['ins_costo_u']); /// costo unitario
          $costo_total = $this->security->xss_clean($post['costo']); /// costo Total
          $um_id = $this->security->xss_clean($post['um_id']); /// Unidad de medida
          $partida = $this->security->xss_clean($post['partida_id']); /// partida
          $observacion = $this->security->xss_clean($post['ins_observacion']); /// Observacion

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
          'aper_id' => $aper_id, /// aper id
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          //// --------
          $data_to_store = array( 
          'ins_id' => $ins_id, /// ins id
          'prod_id' => $prod_id, /// prod id
          'tp_ins' => 4, /// tipo 
          );
          $this->db->insert('_insumoproducto', $data_to_store); ///// Guardar en Tabla Insumos 

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

        }
        else{
          $act_id = $this->security->xss_clean($post['act_id']); /// prod/act id
          $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
          $com_id = $this->security->xss_clean($post['com_id']); /// Proy id
          $detalle = $this->security->xss_clean($post['detalle']); /// detalle
          $cantidad = $this->security->xss_clean($post['cantidad']); /// cantidad
          $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario
          $costo_total = $this->security->xss_clean($post['costot']); /// costo Total
          $um_id = $this->security->xss_clean($post['iumedida']); /// Unidad de medida
          $partida = $this->security->xss_clean($post['par_hijo']); /// costo unitario
          $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
        /*------------ UPDATE INSUMOPRODUCTO -------*/
          $update_insprod= array(
            'prod_id' => $act_id
          );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('_insumoproducto', $this->security->xss_clean($update_insprod));
        /*-----------------------------------------*/

        /*------------ UPDATE REQUERIMIENTO -------*/
          $update_ins= array(
            'ins_cant_requerida' => $cantidad,
            'ins_costo_unitario' => $costo_unitario,
            'ins_costo_total' => $costo_total,
            'ins_detalle' => $detalle,
            'par_id' => $partida, /// Partidas
            'ins_unidad_medida' => $um_id,
            'ins_observacion' => $observacion,
            'fun_id' => $this->fun_id,
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
        }

        $this->session->set_flashdata('success','LOS DATOS DEL REQUERIMIENTO SE MODIFICARON CORRECTAMENTE :)');
        redirect(site_url("").'/prog/list_requerimiento/'.$com_id.'');

      } else {
          show_404();
      }
    }

    /*------ CAMBIA CODIGO DE ACTIVIDAD ---------*/
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

        $combog = pg_query('
            select pg.par_id,pg.partida as par_codigo,p.par_nombre,p.par_depende,pg.importe
            from ptto_partidas_sigep pg
            Inner Join partidas as p On p.par_id=pg.par_id
            where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and p.par_depende='.$id_pais.'
            order by pg.partida asc
        ');
        $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE PARTIDA', 'cp1252', 'UTF-8') . "</option>";
        while ($sql_p = pg_fetch_row($combog)) {
            $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[1]." - ".$sql_p[2] . "</option>";
        }
        echo $salida;
    }

    /*----- ELIMINAR VARIOS REQUERIMIENTOS SELECCIONADOS -----*/
    public function delete_requerimientos_ajustes(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);

        if (!empty($_POST["ins"]) && is_array($_POST["ins"]) ) {
          foreach ( array_keys($_POST["ins"]) as $como){
            /*-------- DELETE INSUMO PROGRAMADO --------*/  
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->delete('temporalidad_prog_insumo');
            /*------------------------------------------*/

            $prod=$this->minsumos->relacion_ins_ope($_POST["ins"][$como]);

            /*---- DELETE INSUMO PRODUCTO ----*/  
              $this->db->where('ins_id', $_POST["ins"][$como]);
              $this->db->where('prod_id', $prod[0]['prod_id']);
              $this->db->delete('_insumoproducto');
            /*--------------------------------*/

            /*-------- DELETE INSUMO  --------*/  
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->delete('insumos');
            /*--------------------------------*/
          }

          $this->session->set_flashdata('success','LOS REQUERIMIENTOS SELECCIONADOS FUERON ELIMINARON CORRECTAMENTE');
          redirect(site_url("").'/prog/list_requerimiento/'.$com_id);

        }
        else{
          $this->session->set_flashdata('danger','SELECCIONE REQUERIMIENTOS');
          redirect(site_url("").'/prog/list_requerimiento/'.$com_id);
        }
      }
      else{
        $this->session->set_flashdata('danger','ERROR AL ELIMINAR REQUERIMIENTOS');
        redirect(site_url("").'/prog/list_requerimiento/'.$com_id);
      }
    }


    /*--- MIGRACION DE OPERACIONES (2020) Y REQUERIMIENTOS  ---*/
    function importar_operaciones_requerimientos_ajustes(){
      if ($this->input->post()) {
        $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']); /// com id
          $componente = $this->model_componente->get_componente_pi($com_id);
          $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
          $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);

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
            $guardado=0;
            $no_guardado=0;

            foreach ($lineas as $linea_num => $linea){
              if($i != 0){
                $datos = explode(";",$linea);
                //echo count($datos).'<br>';
                if(count($datos)==20){
                 
                    $prod_cod = intval(trim($datos[0])); //// Codigo Actividad
                    $cod_partida = intval(trim($datos[1])); //// Codigo partida
                    $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                    $detalle = strval(utf8_encode(trim($datos[2]))); //// descripcion form5
                    $unidad = strval(utf8_encode(trim($datos[3]))); //// Unidad
                    $cantidad = intval(trim($datos[4])); //// Cantidad
                    $unitario = floatval(trim($datos[5])); //// Costo Unitario
                    
                    $p_total=($cantidad*$unitario);
                    $total = floatval(trim($datos[6])); //// Costo Total

                    $var=7; $sum_temp=0;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=floatval(trim($datos[$var])); //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $var++;
                      $sum_temp=$sum_temp+$m[$i];
                    }

                    $observacion = strval(utf8_encode(trim($datos[19]))); //// Observacion
                    $verif_cod=$this->model_producto->verif_componente_operacion($com_id,$prod_cod);
                   // echo count($verif_cod).'--'.count($par_id).'--'.$cod_partida.'--'.round($sum_temp,2).'=='.round($total,2)."<br>";

                    if(count($verif_cod)!=0 & count($par_id)!=0 & $cod_partida!=0 & round($sum_temp,2)==round($total,2)){ /// Verificando si existe Codigo de Actividad, par id, Codigo producto
                        $producto=$this->model_producto->get_producto_id($verif_cod[0]['prod_id']); /// Get producto
                        $guardado++;
                        /*-------- INSERTAR DATOS REQUERIMIENTO ---------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array( 
                        'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                        'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                        'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                        'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                        'ins_costo_unitario' => $unitario, /// Costo Unitario
                        'ins_costo_total' => $total, /// Costo Total
                        'ins_unidad_medida' => $unidad, /// Unidad de Medida
                        'ins_gestion' => $this->gestion, /// Insumo gestion
                        'par_id' => $par_id[0]['par_id'], /// Partidas
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
                            'prod_id' => $verif_cod[0]['prod_id'], /// prod id
                            'ins_id' => $ins_id, /// ins_id
                          );
                          $this->db->insert('_insumoproducto', $data_to_store2);
                        /*----------------------------------------------------------*/

                        for ($p=1; $p <=12 ; $p++) { 
                          if($m[$p]!=0 & is_numeric($unitario)){
                            $data_to_store4 = array(
                              'ins_id' => $ins_id, /// Id Insumo
                              'mes_id' => $p, /// Mes 
                              'ipm_fis' => $m[$p], /// Valor mes
                            );
                            $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                          }
                        }
                    }

                } /// end dimension (22)
              } /// i!=0

              $i++;

            }

            /// --- ACTUALIZANDO MONEDA PARA CARGAR PRESUPUESTO
          //  $this->update_ptto_operaciones($com_id);

            $this->session->set_flashdata('success','SE REGISTRARON '.$guardado.' REQUERIMIENTOS');
            redirect('prog/list_requerimiento/'.$com_id.'');
          }
          else{
            $this->session->set_flashdata('danger','SELECCIONE ARCHIVO ');
            redirect('prog/list_requerimiento/'.$com_id.'');
          }
      }
      else{
        echo "Error !!";
      }
    }

    /*------ ACTUALIZA PRESUPUESTO EXISTENTE DE LAS OPERACIONES -------*/
/*    public function update_ptto_operaciones($com_id){
      $operaciones=$this->model_producto->list_producto_programado($com_id,$this->gestion);
      foreach($operaciones as $rowp){
        $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
        if(count($monto)==0){
          $update_act= array(
            'prod_ppto' => 0,
            'fun_id' => $this->fun_id
          );
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->update('_productos', $update_act);
        }
      }
    } */

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