<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class red_objetivos extends CI_Controller {  

  public function __construct ()
    {
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('resultados/model_resultado');
        $this->load->model('mantenimiento/mpoa');
        $this->load->model('programacion/model_proyecto');
      //  $this->load->model('programacion/model_resultado');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        }else{
            redirect('/','refresh');
        }
    }

    /*---------------------- LISTA DE ACCIONES DE MEDIANO PLAZO ------------------------*/
    public function list_acciones_mediano_plazo($poa_id){
      $data['menu']=$this->menu(1);

      $conf=$this->model_resultado->configuracion();
      $data['gestiones_resultados']=''.$conf[0]['conf_gestion_desde'].' - '.$conf[0]['conf_gestion_hasta'];
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $lista_acciones = $this->mpoa->lista_acciones_mediano_plazo_a_poa($poa_id);

      $tabla = ''; $nro_r=1;
      foreach($lista_acciones  as $row){
        $pdes=$this->model_proyecto->datos_pedes($row['pdes_id']);
        $tabla .= '<tr>';
          $tabla .= '<td>'.$nro_r.'</td>'; //// Nro
          $tabla .= '<td align=center>
                      <a href="' . site_url("") . '/prog/resultado_cplazo/'.$data['dato_poa'][0]['poa_id'].'/'.$row['acc_id'].'" >
                        <img src="' . base_url() . 'assets/ifinal/archivo.png" width="45" height="45" class="img-responsive" title="RESULTADOS DE CORTO PLAZO"><br>REGISTRO DE RESULTADOS DE CORTO PLAZO 
                      </a>
                    </td>';
         $tabla .= '<td>
                      <P>
                          <u><b>PILAR</b></u>
                          :'.$pdes[0]['id1'] . ' - ' . $pdes[0]['pilar'].'
                          <br>
                          <u><b>META</b></u>
                          :'.$pdes[0]['id2'] . ' - ' . $pdes[0]['meta'].'
                          <br>
                          <u><b>RESULTADO</b></u>
                          :'.$pdes[0]['id3'] . ' - ' . $pdes[0]['resultado'].'
                          <br>
                          <u><b>ACCION</b></u>
                          :'.$pdes[0]['id4'] . ' - ' . $pdes[0]['accion'].'
                      </P>
                    </td>'; 
         $tabla .= '<td>'.$row['acc_codigo'].'</td>'; //// CODIGO ACCION DE MEDIANO PLAZO
         $tabla .= '<td>'.$row['acc_descripcion'].'</td>'; //// ACCION DE MEDIANO PLAZO
         $tabla .= '<td>'.$row['obj_descripcion'].'</td>'; //// CODIGO
        $tabla .= '</tr>';

        $nro_r++;
      } 
      
      $data['acciones_mediano_plazo'] = $tabla;
      $this->load->view('admin/red_objetivos/lista_acciones', $data);
    }

    /*-------------------- Lista de Resultados de Corto Plazo ------------------*/
    public function lista_resultados_corto_plazo($poa_id,$r_id){
      $data['menu']=$this->menu(1);
      $conf=$this->model_resultado->configuracion();
      $data['gestiones_resultados']=''.$conf[0]['conf_gestion_desde'].' - '.$conf[0]['conf_gestion_hasta'];
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['resultado'] = $this->model_resultado->get_resultado($r_id);
      $data['list_resultado_cp'] = $this->model_resultado->list_resultados_cp($r_id);
 
      $this->load->view('admin/red_objetivos/resultados_corto_plazo/lista_resultados_corto_plazo', $data);
    }

    /*-------------------- Nuevo REsultado de Corto Plazo ------------------*/
    public function nuevo_resultado_cp($poa_id,$r_id){
      $enlaces=$this->menu_modelo->get_Modulos(1);
      $data['enlaces'] = $enlaces;
      for($i=0;$i<count($enlaces);$i++) 
      {
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }
      $data['subenlaces'] = $subenlaces;
      $conf=$this->model_resultado->configuracion();
      $data['gestiones_resultados']=''.$conf[0]['conf_gestion_desde'].' - '.$conf[0]['conf_gestion_hasta'];
      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['resultado'] = $this->model_resultado->get_resultado($r_id);
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $this->load->view('admin/red_objetivos/resultados_corto_plazo/form1_resultado', $data);
    }
    /*---------------------- Valida Guardar el Resultado de corto plazo ------------------------*/
    function valida_add_resultado_cp(){ 
        if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('fun_id', 'Id del responsable ', 'required|trim');
          $this->form_validation->set_rules('uni_id', 'Unidad Responsable', 'required|trim');

          $conf=$this->model_resultado->configuracion();
          $nro_obj=$conf[0]['conf_obj_gestion']+1;

          $dato_poa = $this->mpoa->dato_poa($this->input->post('poa_id'),$this->session->userdata('gestion'));

          if ($this->form_validation->run()){
                $query=$this->db->query('set datestyle to DMY');
                $data_to_store = array( 
                  'rc_resultado' => strtoupper($this->input->post('resultado')),
                  'resp_id' => $this->input->post('fun_id'),
                  'uni_id' => $this->input->post('uni_id'),
                  'nro_ind' => $this->input->post('nro'),
                  'rc_ponderacion' => $this->input->post('pn_cion'),
                  'rc_codigo' => 'ACP/'.$this->session->userdata('gestion').'/'.$nro_obj,
                  'r_id' => $this->input->post('r_id'),
                  'aper_id' => $dato_poa[0]['aper_id'],
                  'fun_id' => $this->session->userdata("fun_id"),
              );
              $this->db->insert('resultado_corto_plazo', $data_to_store); ///// inserta a resultados 
              $id_res = $this->db->insert_id();

              $update_conf = array('conf_obj_gestion' => $nro_obj);
              $this->db->where('ide', $this->session->userdata("gestion"));
              $this->db->update('configuracion', $update_conf);

              redirect('prog/rcp_form2/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$id_res.'/1');
              
          }
          else{  
            redirect('prog/rcp_nuevo/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/false');
          }
        }
    }

    /*-------------------- Nuevo REsultado de Corto Plazo (Modificar)------------------*/
    public function modificar_resultado_cp($poa_id,$r_id,$rc_id){
      $enlaces=$this->menu_modelo->get_Modulos(1);
      $data['enlaces'] = $enlaces;
      for($i=0;$i<count($enlaces);$i++){
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }
      $data['subenlaces'] = $subenlaces;

      $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->session->userdata('gestion'));
      $data['rmp'] = $this->model_resultado->get_resultado($r_id);
      $data['resultado'] = $this->model_resultado->get_resultado_cp($rc_id);// get resultado corto plazo
      $data['responsables'] = $this->model_resultado->responsables();//lista de responsables
      $data['unidad'] = $this->model_proyecto->get_unidad($data['resultado'][0]['uni_id']);//get unidad
      $this->load->view('admin/red_objetivos/resultados_corto_plazo/form1_resultado_update', $data);
    }

    /*---------------------- Valida Update el Resultado de corto plazo ------------------------*/
    function valida_update_resultado_cp(){ 
        if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('fun_id', 'Id del responsable ', 'required|trim');
          $this->form_validation->set_rules('uni_id', 'Unidad Responsable', 'required|trim');

          if ($this->form_validation->run())
          {
              $query=$this->db->query('set datestyle to DMY');
              $update_res = array( 
                'rc_resultado' => strtoupper($this->input->post('resultado')),
                'resp_id' => $this->input->post('fun_id'),
                'uni_id' => $this->input->post('uni_id'),
                'rc_ponderacion' => $this->input->post('pn_cion'),
                'r_estado' =>2,
                'fun_id' => $this->session->userdata("fun_id")
                );

              $this->db->where('rc_id', $this->input->post('rc_id'));
              $this->db->update('resultado_corto_plazo', $update_res);  

            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA ACCIÓN DE CORTO PLAZO');
            redirect('prog/resultado_gestion/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/true');
              
          }
          else{  
            redirect('prog/rcp_mod1/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$this->input->post('rc_id').'/false');
          }
        }
    }


    /*-------------------- Nuevo REsultado de Corto Plazo Formulario 2 ------------------*/
    public function form2_resultado_cp($poa_id,$r_id,$rc_id,$nro)
    {
        $enlaces=$this->menu_modelo->get_Modulos(1);
        $data['enlaces'] = $enlaces;
        for($i=0;$i<count($enlaces);$i++) 
        {
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }
        $data['subenlaces'] = $subenlaces;
       
        $data['indi']= $this->model_proyecto->indicador(); /// indicador
        $data['poa_id'] = $poa_id;
        $data['r_id'] = $r_id;
        $data['resultado'] = $this->model_resultado->get_resultado_cp($rc_id);// get resultado corto plazo
        $data['nro'] = $nro;
        
        $this->load->view('admin/red_objetivos/resultados_corto_plazo/form2_resultado', $data);
    }

    /*--------------- Valida Formulario 2 (Indicadores) ---------------------------------*/
    function valida_add2_resultado_cp()
    { 
         if($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('r_id', 'Id resultado ', 'required|trim');
            $this->form_validation->set_rules('nro', 'nro de indicador', 'required|trim');
            $this->form_validation->set_rules('tipo_i', 'Indicador', 'required|trim');

            if ($this->form_validation->run()){
              $r[1]='ms1';
              $r[2]='ms2';
              $r[3]='ms3';
              $r[4]='ms4';
              $r[5]='ms5';
              $r[6]='ms6';
              $r[7]='ms7';
              $r[8]='ms8';
              $r[9]='ms9';
              $r[10]='ms10';
              $r[11]='ms11';
              $r[12]='ms12';

              if($this->input->post('tp_medida')==1){
                $linea_base=$this->input->post('lb');
                $meta=$this->input->post('met');
              }
              else{
                $linea_base=$this->input->post('lb2');
                $meta=$this->input->post('met2');
              }

              $query=$this->db->query('set datestyle to DMY');
              $data_to_store = array( 
                'rc_id' => $this->input->post('rc_id'), //// Id resultado de corto plazo
                'indi_id' => $this->input->post('tipo_i'),
                'in_indicador' => strtoupper($this->input->post('indicador')),
                'in_formula' => strtoupper($this->input->post('formula')),
                'in_linea_base' => $linea_base,
                'in_meta' => $meta,
                'in_denominador' => $this->input->post('den'),
                'in_fuente' => strtoupper($this->input->post('verificacion')),
                'in_ponderacion' => $this->input->post('pn_cion'),
                'in_supuestos' => strtoupper($this->input->post('supuestos')),
                'in_denominador' => strtoupper($this->input->post('c_a')),
                'in_numerador' => strtoupper($this->input->post('c_b')),
                'tp_med' => $this->input->post('tp_medida'),
                'valor_ind' => $this->input->post('valor_i'),
                'fun_id' => $this->session->userdata("fun_id"),
                );
                $this->db->insert('indi_resultados_cp', $data_to_store); ///// inserta a indicador del resultado 
                $id_ir = $this->db->insert_id();


                if($this->input->post('tp_medida')==1){
                  for($i=1;$i<=12;$i++){
                    if($this->input->post($r[$i])!=0){
                      $data_to_store2 = array( 
                      'incp_id' => $id_ir,
                      'mes_id' => $i,
                      'in_prog' => $this->input->post($r[$i]),
                      );
                      $this->db->insert('resultados_prog_cp', $data_to_store2);
                    }
                  }
                }
                else{
                  for($i=1;$i<=12;$i++){
                      $data_to_store2 = array( 
                      'incp_id' => $id_ir,
                      'mes_id' => $i,
                      'in_prog' => $meta,
                      );
                      $this->db->insert('resultados_prog_cp', $data_to_store2);
                    $g1++;
                  }
                }

                if($this->input->post('nro')==$this->input->post('nro_ind')){
                  $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA ACCIÓN DE CORTO PLAZO');
                  redirect('prog/resultado_gestion/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').''); 
                }
                else{
                  $nro=$this->input->post('nro')+1;
                  redirect('prog/rcp_form2/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$this->input->post('rc_id').'/'.$nro.'');
                }
                
            }
            else
            {  
              redirect('prog/rcp_form2/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$this->input->post('rc_id').'/'.$this->input->post('nro').'/false'); 
            }
        }
    }

    /*-------------------- Nuevo Indicador de registro ------------------*/
    public function form_indicador_cp($poa_id,$r_id,$rc_id,$nro){
      $enlaces=$this->menu_modelo->get_Modulos(1);
      $data['enlaces'] = $enlaces;
      for($i=0;$i<count($enlaces);$i++){
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }
      $data['subenlaces'] = $subenlaces;
     
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['poa_id'] = $poa_id;
      $data['r_id'] = $r_id;
      $data['resultado'] = $this->model_resultado->get_resultado_cp($rc_id);// get resultado corto plazo
      $data['nro'] = $nro;
      
      $this->load->view('admin/red_objetivos/resultados_corto_plazo/new_indicador', $data);
    }

    /*--------------- Valida Nuevos Indicadores ---------------------------------*/
    function valida_add_indicador_cp(){ 
       if($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('r_id', 'Id resultado ', 'required|trim');
          $this->form_validation->set_rules('nro', 'nro de indicador', 'required|trim');
          $this->form_validation->set_rules('tipo_i', 'Indicador', 'required|trim');

          if ($this->form_validation->run()){
            /*----- Actualizando nro de Indicadores en la tabla resultados cp ---------*/
            $query=$this->db->query('set datestyle to DMY');
            $update_res = array( 
                            'nro_ind' => $this->input->post('nro'),
                            'fun_id' => $this->session->userdata("fun_id"),
                            'r_estado' => 2
                            );

            $this->db->where('r_id', $this->input->post('rc_id'));
            $this->db->update('resultado_corto_plazo', $update_res);
            /*--------------------------------------------------------------------------*/

            $r[1]='ms1';
            $r[2]='ms2';
            $r[3]='ms3';
            $r[4]='ms4';
            $r[5]='ms5';
            $r[6]='ms6';
            $r[7]='ms7';
            $r[8]='ms8';
            $r[9]='ms9';
            $r[10]='ms10';
            $r[11]='ms11';
            $r[12]='ms12';

            if($this->input->post('tp_medida')==1){
              $linea_base=$this->input->post('lb');
              $meta=$this->input->post('met');
            }
            else{
              $linea_base=$this->input->post('lb2');
              $meta=$this->input->post('met2');
            }

            $query=$this->db->query('set datestyle to DMY');
            $data_to_store = array( 
              'rc_id' => $this->input->post('rc_id'), //// Id resultado de corto plazo
              'indi_id' => $this->input->post('tipo_i'),
              'in_indicador' => strtoupper($this->input->post('indicador')),
              'in_formula' => strtoupper($this->input->post('formula')),
              'in_linea_base' => $linea_base,
              'in_meta' => $meta,
              'in_denominador' => $this->input->post('den'),
              'in_fuente' => strtoupper($this->input->post('verificacion')),
              'in_ponderacion' => $this->input->post('pn_cion'),
              'in_supuestos' => strtoupper($this->input->post('supuestos')),
              'in_denominador' => strtoupper($this->input->post('c_a')),
              'in_numerador' => strtoupper($this->input->post('c_b')),
              'tp_med' => $this->input->post('tp_medida'),
              'valor_ind' => $this->input->post('valor_i'),
              'fun_id' => $this->session->userdata("fun_id"),
              );
              $this->db->insert('indi_resultados_cp', $data_to_store); ///// inserta a indicador del resultado 
              $id_ir = $this->db->insert_id();


              if($this->input->post('tp_medida')==1){
                for($i=1;$i<=12;$i++){
                  if($this->input->post($r[$i])!=0){
                    $data_to_store2 = array( 
                    'incp_id' => $id_ir,
                    'mes_id' => $i,
                    'in_prog' => $this->input->post($r[$i]),
                    );
                    $this->db->insert('resultados_prog_cp', $data_to_store2);
                  }
                }
              }
              else{
                for($i=1;$i<=12;$i++){
                    $data_to_store2 = array( 
                    'incp_id' => $id_ir,
                    'mes_id' => $i,
                    'in_prog' => $meta,
                    );
                    $this->db->insert('resultados_prog_cp', $data_to_store2);
                  $g1++;
                }
              }

              $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE EL INDICADOR');
              redirect('prog/resultado_gestion/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/true'); 
              
          }
          else
          {  
            redirect('prog/rcp_new_indicador/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$this->input->post('rc_id').'/'.$this->input->post('nro').'/false'); 
          }
      }
    }

    /*-------------------------------------- Valida Update Indicador -----------------------------------*/
    function valida_update_indicador_cp()
    { 
         if($this->input->server('REQUEST_METHOD') === 'POST') 
          {
            $this->form_validation->set_rules('rc_id', 'Id resultado ', 'required|trim');
            $this->form_validation->set_rules('incp_id', 'Id indicador', 'required|trim');
            $this->form_validation->set_rules('tipo_i', 'Indicador', 'required|trim');

            if ($this->form_validation->run())
            {
                $r[1]='ms1';
                $r[2]='ms2';
                $r[3]='ms3';
                $r[4]='ms4';
                $r[5]='ms5';
                $r[6]='ms6';
                $r[7]='ms7';
                $r[8]='ms8';
                $r[9]='ms9';
                $r[10]='ms10';
                $r[11]='ms11';
                $r[12]='ms12';

              if($this->input->post('tp_medida')==1)
              {
                $linea_base=$this->input->post('lb');
                $meta=$this->input->post('met');
              }
              else
              {
                $linea_base=$this->input->post('lb2');
                $meta=$this->input->post('met2');
              }

              $query=$this->db->query('set datestyle to DMY');
              $update_ind = array( 
                              'indi_id' => $this->input->post('tipo_i'),
                              'in_indicador' => strtoupper($this->input->post('indicador')),
                              'in_formula' => strtoupper($this->input->post('formula')),
                              'in_linea_base' => $linea_base,
                              'in_meta' => $meta,
                              'in_denominador' => $this->input->post('den'),
                              'in_ponderacion' => $this->input->post('pn_cion'),
                              'tp_med' => $this->input->post('tp_medida'),
                              'in_fuente' => strtoupper($this->input->post('verificacion')),
                              'in_supuestos' => strtoupper($this->input->post('supuestos')),
                              'in_denominador' => strtoupper($this->input->post('c_a')),
                              'in_numerador' => strtoupper($this->input->post('c_b')),
                              'valor_ind' => $this->input->post('valor_i'),
                              'fun_id' => $this->session->userdata("fun_id"),
                              'in_estado' => 2
                              );

                $this->db->where('incp_id', $this->input->post('incp_id'));
                $this->db->update('indi_resultados_cp', $update_ind);


                $this->model_resultado->delete_prog_res_cp($this->input->post('incp_id')); //// Eliminando Programado cp

                if($this->input->post('tp_medida')==1)
                  {
                    for($i=1;$i<=12;$i++)
                    {
                      if($this->input->post($r[$i])!=0)
                      {
                        $data_to_store2 = array( 
                        'incp_id' => $this->input->post('incp_id'),
                        'mes_id' => $i,
                        'in_prog' => $this->input->post($r[$i]),
                        );
                        $this->db->insert('resultados_prog_cp', $data_to_store2);
                      }
                    }
                  }
                  else
                  {
                    for($i=1;$i<=12;$i++)
                    {
                        $data_to_store2 = array( 
                        'incp_id' => $this->input->post('incp_id'),
                        'mes_id' => $i,
                        'in_prog' => $meta,
                        );
                        $this->db->insert('resultados_prog_cp', $data_to_store2);
                      $g1++;
                    }
                  }

                  redirect('prog/resultado_gestion/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/true'); 
            }
            else
            { 
              redirect('prog/rcp_update_indicador/'.$this->input->post('poa_id').'/'.$this->input->post('r_id').'/'.$this->input->post('rc_id').'/'.$this->input->post('incp_id').'/'.$this->input->post('nro').'/false');
            }
        }
    }

    /*-------------------- Update Indicador de registro ------------------*/
    public function update_indicador_cp($poa_id,$r_id,$rc_id,$incp_id,$nro){
      $enlaces=$this->menu_modelo->get_Modulos(1);
      $data['enlaces'] = $enlaces;
      for($i=0;$i<count($enlaces);$i++){
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }
      $data['subenlaces'] = $subenlaces;
     
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['poa_id'] = $poa_id;
      $data['r_id'] = $r_id;
      $data['resultado'] = $this->model_resultado->get_resultado_cp($rc_id);// get resultado corto plazo
      $data['indicador'] = $this->model_resultado->get_indicador_cp($incp_id);// get Indicador
      $data['nro_indicador'] = $nro;
      
      if($data['indicador'][0]['valor_ind']==0){$valor='';}
      elseif($data['indicador'][0]['valor_ind']==1){$valor='%';}
      elseif($data['indicador'][0]['valor_ind']==2){$valor='/1.000';}
      elseif($data['indicador'][0]['valor_ind']==3){$valor='/10.000';}
      elseif($data['indicador'][0]['valor_ind']==4){$valor='/100.000';}

      $data['valor'] = $valor;
      $this->load->view('admin/red_objetivos/resultados_corto_plazo/update_indicador', $data);
    }
    /*------------------ Eliminar Resultado de Corto Plazo ----------------*/
    public function delete_resultado_cp(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();

            $rc_id = $post['rc_id'];

            $conf=$this->model_resultado->configuracion();
            $nro_obj=$conf[0]['conf_obj_estrategico']-1;

            $update_conf = array('conf_obj_gestion' => $nro_obj);
            $this->db->where('ide', $this->session->userdata("gestion"));
            $this->db->update('configuracion', $update_conf);

           /*-------------------------------------------------------------*/
            $update_res = array(
                    'r_estado' => '3',
                    'fun_id' => $this->session->userdata("fun_id"),
                    );
            $this->db->where('rc_id', $rc_id);
            $this->db->update('resultado_corto_plazo', $update_res);
            /*-------------------------------------------------------------*/

            $sql = $this->db->get();
           
            if($this->db->query($sql)){
                echo $rc_id;
            }else{
                echo false;
            }
        }else{
            show_404();
        }
    }

    /*----------------- Delete Indicador Corto Plazo ------------------------*/
    public function delete_indicador_cp(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $incp_id = $post['incp_id'];
            $rc_id = $post['rc_id'];

            $resultado = $this->model_resultado->get_resultado_cp($rc_id);// get resultado rcp
            $nro=$resultado[0]['nro_ind']-1;

           /*-------------------------------------------------------------*/
            $update_res = array(
                    'r_estado' => '2',
                    'nro_ind' => $nro,
                    'fun_id' => $this->session->userdata("fun_id"),
                    );
            $this->db->where('rc_id', $rc_id);
            $this->db->update('resultado_corto_plazo', $update_res);
            /*-------------------------------------------------------------*/

            /*------------ ELIMINAR  PROGRAMADO INDICADOR --------------*/
            $this->db->where('incp_id', $incp_id);
            $this->db->delete('resultados_prog_cp');

            /*------------------ ELIMINAR INDICADOR -----------------*/
            $this->db->where('incp_id', $incp_id);
            $this->db->delete('indi_resultados_cp');
            /*-------------------------------------------------------------*/

            $sql = $this->db->get();
           
            if($this->db->query($sql)){
                echo $incp_id;
            }else{
                echo false;
            }
        }else{
            show_404();
        }
    }


    /*----------- TEMPORALIZACION PROGRAMADO RESULTADO ----------------*/
    public function temporalizacion_indicador_rcp($incp_id){
      $ind=$this->model_resultado->get_indicador_cp($incp_id); /// get indicador rcp
      $programado=$this->model_resultado->resultado_programado_cp($incp_id); /// programado

      $nro=0;
      $tr_return = '';
      foreach($programado as $row){
        $nro++;
        $matriz [1][$nro]=$row['mes_id'];
        $matriz [2][$nro]=$row['in_prog'];
      }
      /*---------------- llenando la matriz vacia --------------*/
      for($j = 1; $j<=12; $j++){
          $matriz_r[1][$j]=$j;
          $matriz_r[2][$j]='0';  //// P
          $matriz_r[3][$j]='0';  //// PA
          $matriz_r[4][$j]='0';  //// %PA
      }
      /*--------------------------------------------------------*/
      /*------- asignando en la matriz P, PA, %PA ----------*/
      for($i = 1 ;$i<=$nro ;$i++){
        for($j = 1 ;$j<=12 ;$j++){
          if($matriz[1][$i]==$matriz_r[1][$j])
          {
            $matriz_r[2][$j]=round($matriz[2][$i],2);
          }
        }
      }

        $pa=0;
        for($j = 1 ;$j<=12 ;$j++){
            if($ind[0]['tp_med']==1){
              $pa=$pa+$matriz_r[2][$j];
              $matriz_r[3][$j]=$pa+$ind[0]['in_linea_base'];
            }
            else{
              $matriz_r[3][$j]=$ind[0]['in_meta'];
            }
            if($ind[0]['in_meta']!=0){
              $matriz_r[4][$j]=round(((($pa+$ind[0]['in_linea_base'])/$ind[0]['in_meta'])*100),2);
            }
        }  
      /*------------------------------------------------------------*/

          $tr_return .= '<table border="0" cellpadding="0" cellspacing="0" class="tabla">
                          <tr bgcolor="#474544" class="modo1">
                          <th></th>
                          <th>ENE.</th>
                          <th>FEB.</th>
                          <th>MAR.</th>
                          <th>ABR.</th>
                          <th>MAY.</th>
                          <th>JUN.</th>
                          <th>JUL.</th>
                          <th>AGO.</th>
                          <th>SEPT.</th>
                          <th>OCT.</th>
                          <th>NOV.</th>
                          <th>DIC.</th>
                          </tr>
                          <tr bgcolor="#F5F5F5" class="modo1">
                          <td>P</td>';
                          for($i = 1 ;$i<=12 ;$i++){
                            $tr_return .= '<td>'.$matriz_r[2][$i].'</td>';
                          }
                          $tr_return .= '
                          </tr>
                          <tr bgcolor="#F5F5F5" class="modo1">
                          <td>PA</td>';
                          for($i = 1 ;$i<=12 ;$i++){
                            $tr_return .= '<td>'.$matriz_r[3][$i].'</td>';
                          }
                          $tr_return .= '
                          </tr>
                          <tr bgcolor="#F5F5F5" class="modo1">
                          <td>%PA</td>';
                          for($i = 1 ;$i<=12 ;$i++){
                            $tr_return .= '<td>'.$matriz_r[4][$i].' %</td>';
                          }
                          $tr_return .= '
                          </tr>
                        </table>';
                  
      return $tr_return;
    }

    /*-------------------------- REPORTE DE RESULTADOS DE CORTO PLAZO ---------------------------*/
    public function reporte_accion_cp($poa_id,$r_id){
      $html = $this->list_acciones_corto_plazo($poa_id,$r_id);// Lista de Acciones de Corto Plazo

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("ACCIONES DE CORTO PLAZO.pdf", array("Attachment" => false));
    }

    function list_acciones_corto_plazo($poa_id,$r_id){
      $gestion = $this->session->userdata('gestion');
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
                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                      </td>
                      <td width=60%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                          <b>REPORTE : </b>ACCIONES DE CORTO PLAZO<br>
                          </FONT>
                      </td>
                      <td width=20%; text-align:center;"">
                      </td>
                  </tr>
              </table>
         </div>
         <div id="footer">
           <table border="0" cellpadding="0" cellspacing="0" class="tabla">
              <tr class="modo1" bgcolor=#DDDEDE>
                  <td width=33%;>Jefatura de Unidad o Area / Direcci&oacute;n de Establecimiento / Responsable de Area Regionales / Administraci&oacute;n Central</td>
                  <td width=33%;>Jefaturas de Departamento / Servicios Generales Regional / Medica Regional</td>
                  <td width=33%;>Gerencia General / Gerencias de Area /Administraci&oacute;n Regional</td>
              </tr>
              <tr class="modo1">
                  <td><br><br><br><br><br><br><br></td>
                  <td><br><br><br><br><br><br><br></td>
                  <td><br><br><br><br><br><br><br></td>
              </tr>
              <tr>
                  <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td><p class="page">Pagina </p></td>
              </tr>
          </table>
         </div>
         <div id="content">
           <p><div>'.$this->acciones_cplazo($poa_id,$r_id).'</div></p>
         </div>
       </body>
       </html>';
      return $html;
    }

    public function acciones_cplazo($poa_id,$r_id){
      $gestion=$this->session->userdata('gestion');
      $dato_poa = $this->mpoa->dato_poa($poa_id,$gestion);
      $resultado = $this->model_resultado->get_resultado($r_id);
      $list_resultado_cp = $this->model_resultado->list_resultados_cp($r_id);
      
      $tabla = '';
        $tabla .= '
          <div class="mv" style="text-align:justify">
              <b>PROGRAMA: </b><b>PROGRAMA : </b>'.$dato_poa[0]['aper_programa'] . $dato_poa[0]['aper_proyecto'] . $dato_poa[0]['aper_actividad'] . " - " . $dato_poa[0]['aper_descripcion'].'
          </div>
          <div class="mv" style="text-align:justify">
              <b>ACCI&Oacute;N DE MEDIANO PLAZO: </b>'.$resultado[0]['r_resultado'].'
          </div><br>';

          if(count($list_resultado_cp)!=0){
            $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro</th>';
                  $tabla.='<th style="width:18%;">ACCI&Oacute;N DE CORTO PLAZO</th>';
                  $tabla.='<th style="width:10%;">RESPONSABLE</th>';
                  $tabla.='<th style="width:10%;">UNIDAD ORGANIZACIONAL</th>';
                  $tabla.='<th style="width:70%;">INDICADORES DE LA ACCI&Oacute;N DE MEDIANO PLAZO</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro_r=0;
                foreach($list_resultado_cp as $row){
                $nro_r++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td>'.$nro_r.'</td>';
                  $tabla.='<td>'.$row['rc_resultado'].'</td>';
                  $tabla.='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                  $tabla.='<td>'.$row['uni_unidad'].'</td>';
                  $tabla.='<td>'.$this->indicadores_resultados_cp($row['rc_id']).'</td>';
                $tabla.='</tr>';
                }
                $tabla.='</tbody>';
            $tabla.='</table>';
          }
      return $tabla;
    }



    public function reporte_indicador_cp($poa_id,$r_id)
    {
        $gestion=$this->session->userdata('gestion');
        $dato_poa = $this->mpoa->dato_poa($poa_id,$gestion);
        $resultado = $this->model_resultado->get_resultado($r_id);
        $list_resultado_cp = $this->model_resultado->list_resultados_cp($r_id);

        $html = '';
        $html .= '
        <html>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <head>'.$this->estilo_vertical.'</head>
            <body>
                <header>
                </header>
                <div class="rojo"></div>
                <div class="verde"></div>
                <table width="100%">
                    <tr>
                        <td width=20%;> 
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>POA - PLAN OPERATIVO POR RESULTADOS : </b> '.$gestion.'<br>
                            <b>'.$this->session->userdata('sistema').'</b><br>
                            <b>REPORTE : </b> RESULTADOS DE CORTO PLAZO<br>
                        </td>
                        <td width=20%;>
                            <img src="'.base_url().'assets/ifinal/cns.png" alt="" width="100px">
                        </td>
                    </tr>
                </table>
                 <div class="mv" style="text-align:justify">
                    <b>PROGRAMA : </b>'.$dato_poa[0]['aper_programa'] . $dato_poa[0]['aper_proyecto'] . $dato_poa[0]['aper_actividad'] . " - " . $dato_poa[0]['aper_descripcion'].'
                </div>
                <div class="mv" style="text-align:justify">
                    <b>RESULTADO DE MEDIANO PLAZO : </b>'.$resultado[0]['r_resultado'].'
                </div>
                <footer>
                    <table class="table table-bordered" width="100%">
                        <tr>
                            <td><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                            <td><p class="page">Pagina </p></td>
                        </tr>
                    </table>
                </footer>
                <br>
                <div class="contenedor_principal">
                  <table>
                        <tr bgcolor="#696969">
                            <td style="width:2%;" class="header_table">Nro.</td>
                            <td style="width:5%;" class="header_table"> C&Oacute;DIGO </td>
                            <td style="width:10%;" class="header_table">RESULTADO DE CORTO PLAZO</td>
                            <td style="width:8%;" class="header_table">RESPONSABLE</td>
                            <td style="width:10%;" class="header_table">UNIDAD ORGANIZACIONAL</td>
                            <td class="header_table">INDICADORES DEL RESULTADO</td>
                        </tr>
                  </table>';
                      $nro_rcp=1;
                      foreach($list_resultado_cp  as $rowr)
                      {
                         $html .='
                         <table>
                          <tr bgcolor="#F5F5F5" style="width:100%;">            
                            <td style="width:2%;" font-size:8px;">'.$nro_rcp.'</td>
                            <td style="width:5%;" font-size:8px;">'.$rowr['rc_codigo'].'</td>
                            <td style="width:10%;" font-size:8px;">'.$rowr['rc_resultado'].'</td>
                            <td style="width:10%;">'.$rowr['fun_nombre'].' '.$rowr['fun_paterno'].' '.$rowr['fun_materno'].'</td>
                            <td style="width:10%;">'.$rowr['uni_unidad'].'</td>
                            <td>'.$this->indicadores_resultados_cp($rowr['rc_id']).'</td>
                          </tr>
                        </table>';
                      }
                    $html .= '                   
                    
                  <br>
                
                    <table class="table_contenedor" style="margin-top: 5px;>
                        <tr>
                            <td class="fila_unitaria">FIRMAS:</td>
                        </tr>
                    </table>
                    <table style="width: 50%;margin: 0 auto;margin-top: 50px;margin-bottom: 10px;">
                        <tr>
                            <td style="width:30%">
                                <hr>
                            </td>
                            <td style="width:3%"></td>
                            <td style="width:30%">
                                <hr>
                            </td>
                            <td style="width:3%"></td>
                            <td style="width:30%">
                                <hr>
                            </td>
                        </tr>
                    </table>

                </div>
            </body>
        </html>';
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        $dompdf->render();
        $dompdf->stream("Resultados_Mediano_plazo.pdf", array("Attachment" => false));
    }

    /*--------------------------- INDICADORES --------------------------------------*/
    public function indicadores_resultados_cp($rc_id)
    {
      $indicadores = $this->model_resultado->list_indicadores_cp($rc_id);// lista de indicadores de corto plazo
      $ind ='';
      if(count($indicadores)!=0)
      {
        $ind .= '
        <table border="0" cellpadding="0" cellspacing="0" class="tabla">
          <tr bgcolor="#e8e8e8" class="modo1" align="center">
              <td style="width:3.7%;">Nro.</td>
              <td style="width:10%;">INDICADOR</td>
              <td style="width:10%;">TIPO INDICADOR</td>
              <td style="width:5%;">LINEA BASE</td>
              <td style="width:5%;">META</td>
              <td>TEMPORALIDAD</td>
          </tr>';

          $nro_i=1;
          foreach ($indicadores as $rowi){
            $ind .= '
              <tr class="modo1">
                <td>'.$nro_i.'</td>
                <td>'.$rowi['in_indicador'].'</td>
                <td>'.$rowi['indi_descripcion'].'</td>
                <td>'.$rowi['in_linea_base'].'</td>
                <td>'.$rowi['in_meta'].'</td>
                <td>'.$this->temporalizacion_indicador_rcp($rowi['incp_id']).'</td>
              </tr>';
            $nro_i++;
          }
          $ind .='
        </table>';
      }
      else
      {
        $ind .=
        'SIN INDICADORES REGISTRADOS';
      }  
      return $ind;
    }

    
    function estilo_vertical()
    {
        $estilo_vertical = '<style>
        body{
            font-family: sans-serif;
            }
        table{
            font-size: 8px;
            width: 100%;
            background-color:#fff;
        }
        .mv{font-size:10px;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}

        .titulo_pdf {
            text-align: left;
            font-size: 8px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 8px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 6px;
        background-color: #1c7368;
        background-repeat: repeat-x;
        color: #FFFFFF;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #558FA6;
        border-bottom-color: #558FA6;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 6px;
        font-weight:bold;
       
        background-image: url(fondo_tr01.png);
        background-repeat: repeat-x;
        color: #34484E;
       
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

    public function get_mes($mes_id)
    {
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
}