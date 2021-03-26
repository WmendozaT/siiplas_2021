<?php
class Actividades extends CI_Controller { 
  public function __construct (){ 
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf2');
        $this->load->model('registro_ejec/mejec_sigep'); ///// a borrar
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
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


  /*--------- LISTA ACTIVIDADES (2019) ---------*/
  public function lista_actividades($prod_id){
    $data['producto'] = $this->model_producto->get_producto_id($prod_id);
    if(count($data['producto'])!=0){
        $data['componente'] = $this->model_componente->get_componente_pi($data['producto'][0]['com_id']); ///// DATOS DEL COMPONENTE
        $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $data['menu']=$this->genera_menu($fase[0]['proy_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); 
        $data['indi']= $this->model_proyecto->indicador(); /// indicador
        $data['act']=$this->actividades($data['proyecto'][0]['proy_id'],$prod_id);
        $this->load->view('admin/programacion/actividad/list_actividades', $data); 
    }
    else{
        redirect('admin/dashboard');
    }

  }

    /*----------------- LISTA OPERACIONES (2019) ------------------*/
    public function actividades($proy_id,$prod_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $actividades = $this->model_actividad->list_actividad_gestion($prod_id,$this->gestion); /// actividades de la gestion actual
      $tabla ='';
      $tabla .='<thead>
                  <tr class="modo1">
                    <th style="width:1%;"><b>#</b></th>
                    <th style="width:1%;"><b>E/B</b></th>
                    <th style="width:10%;"><b>TAREA</b></th>
                    <th style="width:1%;"><b>TIP. IND.</b></th>
                    <th style="width:5%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:5%;"><b>PONDERACI&Oacute;N</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:7%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach($actividades as $row){
                  $cont++;
                  $color='';
                  if(count($this->model_actividad->insumo_actividad($row['act_id']))!=0){
                    $color='bgcolor="#d8fbd8"';    
                  }

                  $tabla .='<tr class="modo1" '.$color.'>';
                    $tabla.='<td align="center">'.$cont.'</td>';
                    $tabla.='<td align="center">
                          <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$row['act_id'].'" title="MODIFICAR TAREA" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                          <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR TAREA"  name="'.$row['act_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                          if(count($this->model_faseetapa->fase_etapa_gestion($fase[0]['id'],$this->gestion))!=0){
                            $tabla.='<a href="'.site_url("").'/prog/requerimiento/'.$proy_id.'/'.$row['act_id'].'" target="_blank" title="REQUERIMIENTOS DE LA TAREA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a>';
                          }
                    $tabla.='
                    </td>';
                    $tabla.='<td>'.$row['act_actividad'].'</td>';
                    $tabla.='<td>'.$row['indicador'].'</td>';
                    $tabla.='<td>'.$row['act_indicador'].'</td>';
                    $tabla.='<td>'.$row['act_linea_base'].'</td>';
                    $tabla.='<td>'.$row['act_meta'].'</td>';
                    $tabla.='<td>'.$row['act_ponderacion'].'%</td>';
                    $tabla.='<td bgcolor="#e5fde5">'.$row['enero'].'</td>
                            <td bgcolor="#e5fde5">'.$row['febrero'].'</td>
                            <td bgcolor="#e5fde5">'.$row['marzo'].'</td>
                            <td bgcolor="#e5fde5">'.$row['abril'].'</td>
                            <td bgcolor="#e5fde5">'.$row['mayo'].'</td>
                            <td bgcolor="#e5fde5">'.$row['junio'].'</td>
                            <td bgcolor="#e5fde5">'.$row['julio'].'</td>
                            <td bgcolor="#e5fde5">'.$row['agosto'].'</td>
                            <td bgcolor="#e5fde5">'.$row['septiembre'].'</td>
                            <td bgcolor="#e5fde5">'.$row['octubre'].'</td>
                            <td bgcolor="#e5fde5">'.$row['noviembre'].'</td>
                            <td bgcolor="#e5fde5">'.$row['diciembre'].'</td>';
                    $tabla.='<td>'.$row['act_fuente_verificacion'].'</td>';
                    
                  $tabla .='</tr>';
                }
                $tabla.='</tbody>';

      return $tabla;
    }


    /*----------------- GET ACTIVIDAD -------------------*/
    public function get_actividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']);
        $proy_id = $this->security->xss_clean($post['proy_id']);

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); /// FASE ACTIVA
        $actividad=$this->model_actividad->get_actividad_gestion($act_id,$this->gestion);
        $suma=$this->model_actividad->suma_programado($act_id,$this->gestion);

        if(count($actividad)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'actividad' => $actividad,
            'suma' => $suma[0]['suma']+$actividad[0]['act_linea_base'],
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


    /*----- VALIDA ACTIVIDAD (2019) -----*/
     public function valida_actividad(){
      if($this->input->post()) {
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        
        $actividad = $this->security->xss_clean($post['actividad']); /// actividad
        $tp_id = $this->security->xss_clean($post['tp_indi']); /// tipo de indicador
        $indicador = $this->security->xss_clean($post['indicador']); /// Indicador
        $verificacion = $this->security->xss_clean($post['verificacion']); /// Verificacion
        $lbase = $this->security->xss_clean($post['lbase']); /// Linea Base
        $meta = $this->security->xss_clean($post['meta']); /// Meta
        $pcion = $this->security->xss_clean($post['pcion']); /// Ponderacion

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

        $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'prod_id' => $prod_id,
            'act_actividad' => $actividad,
            'indi_id' => $tp_id,
            'act_indicador' => $indicador,
            'act_linea_base' => $lbase,
            'act_meta' => $meta,
            'act_fuente_verificacion' => $verificacion,
            'act_ponderacion' => $pcion,
            'act_fecha_inicio' => $fase[0]['inicio'],
            'act_fecha_final' => $fase[0]['final'],
            'fun_id' => $this->fun_id,
          );
          $this->db->insert('_actividades', $data_to_store); /// Guardar en Tabla Actividad
          $act_id=$this->db->insert_id();

          for ($i=1; $i <=12 ; $i++) {
            $pfis=$this->security->xss_clean($post['m'.$i]);
            if($pfis!=0){
              $data_to_store4 = array( 
                'act_id' => $act_id, /// actividad id
                'm_id' => $i, /// Mes 
                'pg_fis' => $pfis, /// Valor mes
                'g_id' => $this->gestion, /// Gestion
                );
              $this->db->insert('act_programado_mensual', $data_to_store4);
            }
          }

          $act=$this->model_actividad->get_actividad_gestion($act_id,$this->gestion);
          if(count($act)==1){
            $this->session->set_flashdata('success','LA ACTIVIDAD SE REGISTRO CORRECTAMENTE :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR, VERIFIQUE DATOS DE LA ACTIVIDAD :)'); 
          }

          redirect(site_url("").'/prog/list_act/'.$prod_id.'');

      } else {
          show_404();
      }
    }

    /*----------------- VALIDA UPDATE ACTIVIDAD ------------------*/
     public function valida_update_actividad(){
      if($this->input->post()) {
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']); /// act id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $fase = $this->model_faseetapa->get_id_fase($proy_id); /// FASE ACTIVA
        $actividad = $this->security->xss_clean($post['mactividad']); /// detalle actividad
        $tp_id = $this->security->xss_clean($post['tp_mindi']); /// tipo de indicador
        $indicador = $this->security->xss_clean($post['mindicador']); /// Indicador
        $verificacion = $this->security->xss_clean($post['mverificacion']); /// Medio de verificacion
        $lbase = $this->security->xss_clean($post['mlbase']); /// linea base
        $meta = $this->security->xss_clean($post['mmeta']); /// Meta
        $pcion = $this->security->xss_clean($post['mpcion']); /// ponderacion
       
        /*------------ UPDATE ACTIVIDAD -------*/
          $update_act= array(
            'act_actividad' => $actividad,
            'indi_id' => $tp_id,
            'act_indicador' => $indicador,
            'act_linea_base' => $lbase,
            'act_meta' => $meta, 
            'act_fuente_verificacion' => $verificacion,
            'act_ponderacion' => $pcion,
            'act_fecha_inicio' => $fase[0]['inicio'],
            'act_fecha_final' => $fase[0]['final'],
            'fun_id' => $this->fun_id,
            'estado' => 2
          );
          $this->db->where('act_id', $act_id);
          $this->db->update('_actividades', $this->security->xss_clean($update_act));
        /*-----------------------------------------*/

          $this->model_actividad->delete_act_gest($act_id);
          for ($i=1; $i <=12 ; $i++) {
            $pfis=$this->security->xss_clean($post['mm'.$i]);
            if($pfis!=0){
                $data_to_store4 = array( 
                  'act_id' => $act_id, /// actividad id
                  'm_id' => $i, /// Mes 
                  'pg_fis' => $pfis, /// Valor mes
                  'g_id' => $this->gestion, /// Gestion
                  );
                $this->db->insert('act_programado_mensual', $data_to_store4);
            }
          }

          $act=$this->model_actividad->get_actividad_gestion($act_id,$this->gestion);
          if($act[0]['estado']==2){
            $this->session->set_flashdata('success','LA ACTIVIDAD SE MODIFICO CORRECTAMENTE :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR, VERIFIQUE DATOS DE LA ACTIVIDAD :)'); 
          }

          redirect(site_url("").'/prog/list_act/'.$act[0]['prod_id'].'');

      } else {
          show_404();
      }
    }

    /*--- ELIMINAR ACTIVIDAD (PROYECTO DE INVERSION) ---*/
    function delete_actividad(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $act_id = $this->security->xss_clean($post['act_id']);
          $insumos = $this->model_actividad->insumo_actividad($act_id); /// Insumo de la Actividad

          $nro=0; $nro_ins=0;
          foreach ($insumos as $rowi) {
            /*--------- delete temporalidad --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('temporalidad_prog_insumo');

            $this->db->where('act_id', $act_id);
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('_insumoactividad');

            /*--------- delete Insumos --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('insumos');

            if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
              $nro_ins++;
            }
          }

          /*------ delete Temporalidad Actividad -----*/
          $this->db->where('act_id', $act_id);
          $this->db->delete('act_programado_mensual');

          /*------ delete Actividad -----*/
          $this->db->where('act_id', $act_id);
          $this->db->delete('_actividades');
          
          
          $act=$this->model_actividad->get_actividad_id($act_id);
          if(count($act)==0){
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


    /*--------------- VALIDA ADICIONAR ACTIVIDAD POR MODIFICACION -----------------*/
     public function valida_add_actividad(){
      if ($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('proy_id', 'Proyecto Id', 'required|trim');
          $this->form_validation->set_rules('cite_id', 'Cite Id', 'required|trim');
          $this->form_validation->set_rules('com_id', 'Componente Id', 'required|trim');
          $this->form_validation->set_rules('prod_id', 'Producto ID', 'required|trim');
          $this->form_validation->set_rules('act', 'actividad', 'required|trim');

          $fase = $this->model_faseetapa->get_id_fase($this->input->post('proy_id'));
          for ($i=1; $i <=12 ; $i++) { 
            $m[$i]='m'.$i;
          }
          if ($this->form_validation->run()){
            /*------------------ Adiciona Producto -------------------*/
            $data_to_store = array(
                'prod_id' => $this->input->post('prod_id'),
                'act_actividad' => strtoupper($this->input->post('act')),
                'indi_id' => $this->input->post('tipo_i'),
                'act_indicador' => strtoupper($this->input->post('indicador')),
                'act_formula' => strtoupper($this->input->post('formula')),
                'act_linea_base' => $this->input->post('lb'),
                'act_meta' => $this->input->post('met'),
                'act_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
                'act_supuestos' => strtoupper($this->input->post('supuestos')),
                'act_presupuesto' => $this->input->post('costo'),
                'act_fecha_inicio' => $this->input->post('f_ini'),
                'act_fecha_final' => $this->input->post('f_final'),
                'act_denominador' => $this->input->post('den'),
                'nro_act' => 0,
                'act_costo_uni' => $this->input->post('cost_uni'),
                'act_total_casos' => strtoupper($this->input->post('c_a')), 
                'act_casos_favorables' => strtoupper($this->input->post('c_b')),
                'fun_id' => $this->session->userdata("fun_id"),
                'act_mod' => 2,
            );
            $this->db->insert('_actividades', $data_to_store); 
            /*-------------------------------------------------------*/
            $act_id=$this->db->insert_id();

            $gestion=$fase[0]['pfec_fecha_inicio'];
            if ( !empty($_POST["m1"]) && is_array($_POST["m1"]) ){
                foreach ( array_keys($_POST["m1"]) as $como ){
                  
                  for ($i=1; $i <=12 ; $i++) { 
                    if($_POST[$m[$i]][$como]!=0 || $_POST[$m[$i]][$como]!=''){
                        $this->model_actividad->add_act_gest($act_id,$gestion,$i,$_POST[$m[$i]][$como],($_POST[$m[$i]][$como]*$this->input->post('cost_uni')));
                    }
                  }
                $gestion++;        
              }
            }

                $conf=$this->model_proyecto->configuracion(); //// configuracion gestion
                $nro_a=$conf[0]['conf_proceso']+1;

                $update_conf = array('conf_proceso' => $nro_a);
                $this->db->where('ide', $this->session->userdata("gestion"));
                $this->db->update('configuracion', $update_conf);

            /*--------------------- iNSERT AUDI ADICIONAR PRODUCTOS -------------*/
              $data_to_store2 = array(
                'act_id' => $act_id, /// prod_id
                'ope_id' => $this->input->post('cite_id'), /// cite_id
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'fun_id' => $this->session->userdata("fun_id"),
                );
              $this->db->insert('_actividad_add', $data_to_store2);
              $acta_id=$this->db->insert_id();

              if(count($this->model_modificacion->get_add_actividad($acta_id))==1){
                $this->session->set_flashdata('success','LA ACTIVIDAD SE AGREGO CORRECTAMENTE');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
              else{
                $this->session->set_flashdata('danger','NO SE GUARDO CORRECTAMENTE, VERIFIQUE DATOS');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
          }
          else{
            redirect('admin/mod/add_actividad/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id')."/".$this->input->post('com_id').'/'.$this->input->post('prod_id').'');
          }
      }
   }



    public function actualiza_ponderacion($pfec_id,$com_id,$prod_id) /////// GENERA TABLA DE FUENTES , ORGANISMOS
    {
        /*================================ ACTUALIZANDO PONDERACIONES ACTIVIDADES ===============================*/
        $suma = $this->model_actividad->suma_monto_ponderado_total($prod_id);
        $act = $this->model_actividad->list_act_anual($prod_id);
        $ponderacion=0;
        foreach ($act as $row)
        {
            $ponderacion=round((($row['act_pres_p']/$suma[0]['monto_total'])*100),2);
            $update_act = array(
                'act_ponderacion' => $ponderacion
            );

            $this->db->where('act_id', $row['act_id']);
            $this->db->update('_actividades', $update_act);

        }
        /*===========================================================================================*/
        /*========================================= ACTUALIZANDO PONDERACIONES PRODUCTOS ========================*/
        $productos= $this->model_producto->list_prod($com_id);
        $sumatoria_total=0;
        foreach ($productos as $rowp)
        {
            $suma_pa = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
            $sumatoria_total=$sumatoria_total+$suma_pa[0]['monto_total'];
        }

        $ponderacion=0;
        foreach ($productos as $rowp)
        {
            $suma_prod = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
            $ponderacion=round((($suma_prod[0]['monto_total']/$sumatoria_total)*100),2);
                    
                    $update_prod = array(
                        'prod_ponderacion' => $ponderacion
                    );
                    $this->db->where('prod_id', $rowp['prod_id']);
                    $this->db->update('_productos', $update_prod);
        }
        /*============================================================================================================*/

        /*========================================= ACTUALIZANDO PONDERACIONES COMPONENTES =============================*/
        $componente= $this->model_componente->componentes_id($pfec_id);
        $sumatoria_comp=0;
        foreach ($componente as $rowc)
        {
            $productos= $this->model_producto->list_prod($rowc['com_id']);
            $sumatoria_p=0;
            foreach ($productos as $rowp)
            {
                $suma_pa = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
                $sumatoria_p=$sumatoria_p+$suma_pa[0]['monto_total'];
            }
            $sumatoria_comp=$sumatoria_comp+$sumatoria_p;
        }

        $ponderacion=0;
        foreach ($componente as $rowc)
        {
            $productos= $this->model_producto->list_prod($rowc['com_id']);
            $sumatoria_p=0;
            foreach ($productos as $rowp)
            {
                $suma_pa = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
                $sumatoria_p=$sumatoria_p+$suma_pa[0]['monto_total'];
            }
            $ponderacion=round((($sumatoria_p/$sumatoria_comp)*100),2);
            $update_comp = array(
                        'com_ponderacion' => $ponderacion
                    );
                    $this->db->where('com_id', $rowc['com_id']);
                    $this->db->update('_componentes', $update_comp);

        }
        /*============================================================================================================*/

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