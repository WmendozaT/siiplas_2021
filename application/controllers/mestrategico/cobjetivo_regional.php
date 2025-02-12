<?php
class Cobjetivo_regional extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');  
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('resultados/model_resultado');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userData('fun_id');
        $this->dep_id = $this->session->userData('dep_id');

        $this->load->library('oregional');
        //$this->load->CI_Controller('reporte_evaluacion/crep_evalunidad');
      }else{
          redirect('/','refresh');
      }
    }

    
    /*----- LISTA DE OBJETIVOS REGIONALES (OPERACIONES)----*/
    public function objetivos_regional($og_id){
      $data['menu']=$this->oregional->menu(1);
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($og_id);
      if(count($data['ogestion'])!=0){
        $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['ogestion'][0]['oe_id']);
        $data['regionales']=$this->oregional->regionales_seleccionados($og_id);

        $this->load->view('admin/mestrategico/objetivos_region/list_oregion', $data);
      }
      else{
        redirect(site_url("").'/me/mis_ogestion');
      }
      
    }



    /*---------- FORMULARIO ADD OBJ. REGIONAL ------------*/
    public function form_oregional($dep_id,$og_id){
      $data['menu']=$this->oregional->menu(1);
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($og_id);
      //$data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['ogestion'][0]['oe_id']);
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      
      $data['formulario']=$this->oregional->formulario_add($dep_id,$og_id);
      $this->load->view('admin/mestrategico/objetivos_region/form_oregional', $data);
    }

    /*------ CAMBIA ALINEACION A ACP 2024---------*/
    function cambia_alineacion_acp(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('select_og_id', 'Objetivo Regional', 'required|trim');
          $this->form_validation->set_message('required', 'El campo es es obligatorio');
        
          $post = $this->input->post();
          $select_og_id= $this->security->xss_clean($post['select_og_id']);
          $or_id= $this->security->xss_clean($post['or_id']);
          $dep_id= $this->security->xss_clean($post['dep_id']);
          
          $get_form2=$this->model_objetivoregion->get_form2_oregional($or_id); /// get operacion (formulario 2)
          $get_form1=$this->model_objetivoregion->list_oregional_regional($select_og_id,$dep_id); /// get acp donde se va a cambiar


          $update_form2 = array(
            'pog_id' => $get_form1[0]['pog_id'],
          );
          $this->db->where('or_id', $or_id);
          $this->db->update('objetivos_regionales', $update_form2);
    
      }else{
          show_404();
      }
    }

    /*------ CAMBIAR PRIORIZACION---------*/
    function update_priorizar_form2(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('select_og_id', 'Objetivo Regional', 'required|trim');
          $this->form_validation->set_message('required', 'El campo es es obligatorio');
        
          $post = $this->input->post();
          $select_og_id= $this->security->xss_clean($post['select_og_id']); // priori
          $or_id= $this->security->xss_clean($post['or_id']); /// or_id
          $dep_id= $this->security->xss_clean($post['dep_id']); /// dep_id
          

          $update_form2 = array(
            'or_priorizado' => $select_og_id,
          );
          $this->db->where('or_id', $or_id);
          $this->db->update('objetivos_regionales', $update_form2);
    
      }else{
          show_404();
      }
    }

    /*---------- FORMULARIO UPDATE OBJ. REGIONAL ------------*/
    public function form_update_oregional($or_id){
      $data['menu']=$this->oregional->menu(1);
      $data['oregion']=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($data['oregion'][0]['og_id']); /// Objetivo de Gestion
      //$data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['ogestion'][0]['oe_id']);
      $data['regional']=$this->model_proyecto->get_departamento($data['oregion'][0]['dep_id']);
      
      $data['formulario']=$this->oregional->formulario_update($data['oregion']);
      $this->load->view('admin/mestrategico/objetivos_region/form_update_oregional', $data);
    }



    /*--- VALIDA ADD / UPDATE OBJETIVO REGIONAL ---*/
    public function add_ogestion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tipo id

       // $objetivo = $this->security->xss_clean($post['oregional']); /// Objetivo
       // $observacion = $this->security->xss_clean($post['observaciones']); /// Observacion
        $meta_reg = $this->security->xss_clean($post['meta_reg']); /// Meta regional

        if($tp==1){ //// INSERT
          $pog_id = $this->security->xss_clean($post['pog_id']); /// pog id
          $dep_id = $this->security->xss_clean($post['dep_id']); /// dep id
          $ogestion=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id);
          $data_to_store = array(
            'pog_id' => $pog_id,
            'or_objetivo' => strtoupper($this->security->xss_clean($post['oregional'])),
            'or_producto' => strtoupper($this->security->xss_clean($post['producto'])),
            'or_codigo' => $this->security->xss_clean($post['codigo']),
            'or_resultado' => strtoupper($this->security->xss_clean($post['resultado'])),
            'indi_id' => 1,
            'or_indicador' => strtoupper($this->security->xss_clean($post['indicador'])),
            'or_linea_base' => $this->security->xss_clean($post['lbase']),
            'or_meta' => $this->security->xss_clean($post['meta']),
            'or_verificacion' => strtoupper($this->security->xss_clean($post['mverificacion'])),
            'or_observacion' => strtoupper($this->security->xss_clean($post['observaciones'])),
            'g_id' => $this->gestion,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('objetivos_regionales', $data_to_store);
          $or_id=$this->db->insert_id();

          /*-------- REGISTRANDO UNIDADES, ESTABLECIMIENTOS -----*/
          if (!empty($_POST["act_id"]) && is_array($_POST["act_id"])) {
            foreach ( array_keys($_POST["act_id"]) as $como){
              $estado=0;
              $prog_fis=0;
              if($_POST["uni_id"][$como]!=0){
                if($meta_reg!=0){
                  $estado=1;
                }
                $prog_fis=$_POST["uni_id"][$como];
              }
              
              $data_to_store4 = array( 
                'or_id' => $or_id, /// or id
                'act_id' => $_POST["act_id"][$como], /// act id 
                'prog_fis' => $prog_fis, /// Valor prog
                'g_id' => $this->gestion, /// Gestion
                'or_estado' => $estado, /// Estado
              );
              $this->db->insert('objetivo_regional_programado', $data_to_store4);
            }
          }
          /*----------------------------------------------------*/

        }
        else{ //// UPDATE
          $or_id = $this->security->xss_clean($post['or_id']); /// or id

          $update_or= array(
            'or_objetivo' => strtoupper($this->security->xss_clean($post['oregional'])),
            'or_producto' => strtoupper($this->security->xss_clean($post['producto'])),
            'or_indicador' => strtoupper($this->security->xss_clean($post['indicador'])),
            'or_resultado' => strtoupper($this->security->xss_clean($post['resultado'])),
            'or_linea_base' => $this->security->xss_clean($post['lbase']),
            'indi_id' => $this->security->xss_clean($post['indi_id']),
            'or_meta' => $this->security->xss_clean($post['meta']),
            'or_verificacion' => strtoupper($this->security->xss_clean($post['mverificacion'])),
            'or_observacion' => strtoupper($this->security->xss_clean($post['observaciones'])),
            'or_codigo' => $this->security->xss_clean($post['codigo']),
            'estado' => 2,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
          );
          $this->db->where('or_id', $or_id);
          $this->db->update('objetivos_regionales', $update_or);


          if (!empty($_POST["act_id"]) && is_array($_POST["act_id"])) {
            foreach ( array_keys($_POST["act_id"]) as $como){
              
              $estado=0;
              $prog_fis=0;
              if($_POST["uni_id"][$como]!=0){
                if($meta_reg!=0){
                  $estado=1;
                }
                $prog_fis=$_POST["uni_id"][$como];
              }

              $verif=$this->model_objetivoregion->get_unidad_programado($or_id,$_POST["act_id"][$como]);
              if(count($verif)!=0){ // Update

                $update_orp= array(
                  'prog_fis' => $prog_fis,
                  'or_estado' => $estado
                );
                $this->db->where('por_id', $verif[0]['por_id']);
                $this->db->update('objetivo_regional_programado', $update_orp);
              }
              else{ // Add
                //echo "add : ".$_POST["act_id"][$como]." - ".$_POST["tp_id"][$como]."<br>";
                $data_to_store4 = array( 
                  'or_id' => $or_id, /// or id
                  'act_id' => $_POST["act_id"][$como], /// act id 
                  'prog_fis' => $prog_fis, /// Valor prog
                  'g_id' => $this->gestion, /// Gestion
                  'or_estado' => $estado, /// Estado
                  'tp_id' => $_POST["tp_id"][$como], /// Estado
                );
                $this->db->insert('objetivo_regional_programado', $data_to_store4);
              }
            }
          }
        }

        $get_or=$this->model_objetivoregion->get_objetivosregional($or_id);

       // $obj_gestion=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id);
        $this->session->set_flashdata('success','REGISTRO CORRECTO !!! ');
        redirect(site_url("").'/me/objetivos_regionales/'.$get_or[0]['og_id'].'#tabs-'.$get_or[0]['dep_id'].'');

      } else {
          show_404();
      }
    }

    /*---- ELIMINAR OBJETIVO REGIONAL ----*/
    function delete_oregional(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $or_id = $this->security->xss_clean($post['or_id']);

          $list_act_prog=$this->model_objetivoregion->list_actividades_oregional($or_id);
          foreach($list_act_prog  as $row){
            /*----- UPDATE TABLA PROYECTO ----*/
            $update_proy= array(
              'por_id' =>0
            );
            $this->db->where('por_id', $row['por_id']);
            $this->db->update('_proyectos', $update_proy);


            $this->db->where('por_id', $row['por_id']);
            $this->db->delete('proy_oregional');
          }

          // -----------------------
          $update_prod= array(
            'or_id' =>0
          );
          $this->db->where('or_id', $or_id);
          $this->db->update('_productos', $update_prod);
          // ----------------------


          $this->db->where('or_id', $or_id);
          $this->db->delete('objetivo_regional_programado');

          $this->db->where('or_id', $or_id);
          $this->db->delete('objetivos_regionales');

          $oregion=$this->model_objetivoregion->get_objetivosregional($or_id); 
          if(count($oregion)==0){
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



    /*---- REPORTE - LISTA DE OBJETIVOS REGIONALES SEGUN OBJETIVO DE GESTION ----*/
    public function reporte_objetivos_regionales($og_id){
      $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 
      if(count($ogestion)!=0){
        $data['gestion']=$this->gestion;
        $data['lista_operaciones']=$this->reporte_lista_oregionales($og_id);
        $this->load->view('admin/mestrategico/objetivos_gestion/reporte_objetivos_regionales', $data); 
      }
      else{
        echo "Error !!!";
      }
    }

    /// --- Lista de Operaciones por Objetivo de Gestion 2022
    public function reporte_lista_oregionales($og_id){
      $regionales=$this->model_objetivogestion->list_temporalidad_regional($og_id);
      $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 

      $tabla='';
        $nro_pag=0;
        foreach($regionales as $row){ 
          $oregional=$this->model_objetivoregion->list_oregional_regional($og_id,$row['dep_id']);
          $nro_pag++;
          $tabla.='<page backtop="66mm" backbottom="20mm" backleft="5mm" backright="5mm" pagegroup="new">
                    <page_header>
                        <br><div class="verde"></div>
                        '.$this->oregional->cabecera_rep_operaciones($ogestion).'
                    </page_header>
                    <page_footer>
                        '.$this->oregional->pie_rep_operaciones($ogestion).'
                    </page_footer>';

          $tabla.='
          <div style="font-size: 12px;font-family: Arial; height:20px;"><b>REGIONAL : </b>'.strtoupper($row['dep_departamento']).' |<b> META REGIONAL : </b>'.round($row['prog_fis'],2).'</div>';

          $nro=0;
          if(count($oregional)!=0){
            $tabla.=$this->reporte_datos_objetivo_regional_por_regional($oregional,$row['dep_id']);
          }
          else{
            $tabla.='<div style="font-size: 9px;font-family: Arial; height:20px;">SIN REGISTROS</div>';
          }

          $tabla.='</page>';
        }

      return $tabla;
    }


    //// Reporte muestra el reporte de operaciones por regional 2022
    public function reporte_datos_objetivo_regional_por_regional($oregional,$dep_id){
      $tabla='';
        $nro=0;
        foreach($oregional as $row_or){
         $nro++;

         $tabla.='
         <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
            <thead>
              <tr style="font-size: 8px;" bgcolor="#d2d2d2" align=center>
                <th style="width:2%;height:15px;">#</th>
                <th style="width:5%;">COD. OPE.</th>
                <th style="width:15%;">OPERACI&Oacute;N '.$this->gestion.'</th>
                <th style="width:15%;">PRODUCTO</th>
                <th style="width:14%;">RESULTADO (LOGROS)</th>
                <th style="width:13%;">INDICADOR</th>
                <th style="width:5%;">LINEA BASE</th>
                <th style="width:5%;">META</th>
                <th style="width:13%;">MEDIO DE VERIFICACI&Oacute;N</th>
                <th style="width:13%;">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</th>
              </tr>
            </thead>
          <tbody>
            <tr style="font-size: 7px;">
            <td style="width:2%; height:20px;" align=center>'.$nro.'</td>
            <td style="width:5%; font-size: 15px; text-align: center"><b>'.$row_or['or_codigo'].'</b></td>
            <td style="width:15%;">'.$row_or['or_objetivo'].'</td>
            <td style="width:15%;">'.$row_or['or_producto'].'</td>
            <td style="width:14%;">'.$row_or['or_resultado'].'</td>
            <td style="width:13%;">'.$row_or['or_indicador'].'</td>
            <td style="width:5%;font-size: 10px;text-align:right">'.round($row_or['or_linea_base'],2).'</td>
            <td style="width:5%;font-size: 10px;text-align:right">'.round($row_or['or_meta'],2).'</td>
            <td style="width:13%;">'.$row_or['or_verificacion'].'</td>
            <td style="width:13%;">'.$row_or['or_observacion'].'</td>
          </tr>
          </tbody>
        </table><br>';
        $num=0;
        $distritales=$this->model_proyecto->list_distritales($dep_id);
        foreach($distritales as $rowd){
          $niveles=$this->model_objetivoregion->list_niveles();
          $tabla.='
             <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
                <thead>
                <tr>
                  <th colspan=4 bgcolor="#e4e2e2" style="height:12px;" align=center>DISTRIBUCI&Oacute;N - '.strtoupper($rowd['dist_distrital']).'</th>
                </tr>
                <tr>
                  <th style="width:25%; height:12px;" bgcolor="#e4e2e2" align=center>REGIONAL / DISTRITAL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>PRIMER NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>SEGUNDO NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>TERCER NIVEL</th>
                </tr>
                </thead>
                <tbody>
                  <tr style="text-align: center;">';
                  
                  foreach($niveles as $rown){
                    $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($rowd['dist_id'],$rown['tn_id']);
                    $tabla.='
                    <td style="width:25%;">
                      <table class="tabla" cellpadding="0" cellspacing="0" border=0.1 style="width:100%; font-size: 6.3px;">
                        <thead>
                        <tr>
                          <th style="width:10px; height:10px;">#</th>
                          <th style="width:30px;">CAT. PROG.</th>
                          <th style="width:135px;">UNIDAD / ESTABLECIMIENTO</th>
                          <th style="width:50px;">PROG.</th>
                        </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($nivel as $rowu){
                          $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$rowu['act_id']);
                          $color='';$valor_prog=0;
                          if(count($uni)!=0){
                              if($uni[0]['or_estado']==1){
                                  $color='#cbf7cb';      
                              }
                            $valor_prog=$uni[0]['prog_fis'];
                          }
                          $nro++;
                          $tabla.='
                          <tr bgcolor='.$color.'>
                            <td style="width:10px;">'.$nro.'</td>
                            <td style="width:30px;">'.$rowu['aper_programa'].'</td>
                            <td style="width:135px;text-align: left;">'.$rowu['tipo'].' '.$rowu['act_descripcion'].'</td>
                            <td style="width:50px;">'.round($valor_prog,2).'</td>
                          </tr>';
                        }

                        $tabla.='
                        </tbody>
                      </table>
                    </td>';
                  }
              $tabla.='
                </tr>
              </tbody>
            </table><br>';
            }
        }

      return $tabla;
    }

    /*------------------------- COMBO RESPONSABLES ----------------------*/
    public function combo_funcionario_unidad_organizacional($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'unidad':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT u.*
          from funcionario f
          Inner Join unidadorganizacional as u On u."uni_id"=f."uni_id"
          where  f."fun_id"='.$id_pais.'');
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }

  ///// ===== REPORTE FORMULARIO N° 2

  public function reporte_form2($dep_id){
    $data['regional']=$this->model_proyecto->get_departamento($dep_id);
    //$data['mes'] = $this->mes_nombre();
    $data['cabecera']=$this->oregional->cabecera_form2($data['regional']);
    $data['oregional']=$this->oregional->rep_lista_form2($dep_id);
    $data['pie']=$this->oregional->pie_form2($data['regional']);

    $this->load->view('admin/mestrategico/objetivos_region/reporte_form2', $data);
  }

  //////// MIGRAR OPERACIONES REGIONALES
    function valida_add_operaciones_regionales(){
      if ($this->input->post()) {
          $post = $this->input->post();

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);
             
              $i=0;

              foreach ($lineas as $linea_num => $linea){ /// A
                if($i != 0){ /// B
                  $datos = explode(";",$linea);
                  //$nro++;
                  $acp_id=intval(trim($datos[0])); /// Acp id
                  $dep_id=intval(trim($datos[1])); /// dep id
                  $codigo=intval(trim($datos[2])); /// codigo
                  $operacion=trim($datos[3]); /// operacion
                  $producto=trim($datos[4]); /// producto
                  $resultado=trim($datos[5]); /// resultado
                  $indicador=trim($datos[6]); /// indicador
                  $linea_base=0;
                  $meta=0;
                  $mverificacion=trim($datos[7]); /// indicador
                  $observacion=trim($datos[8]); /// observacion

                  $acp=$this->model_objetivogestion->get_objetivosgestion($acp_id);
                  if(count($acp)!=0){
                    $get_meta_prog=$this->model_objetivogestion->get_temporalidad_regional($acp_id,$dep_id);
                    $pog_id = $this->security->xss_clean($get_meta_prog[0]['pog_id']); /// pog id
                    $data_to_store = array(
                      'pog_id' => $pog_id,
                      'or_objetivo' =>  strtoupper(utf8_encode($operacion)),
                      'or_producto' =>  strtoupper(utf8_encode($producto)),
                      'or_codigo' => $codigo,
                      'or_resultado' =>  strtoupper(utf8_encode($resultado)),
                      'indi_id' => 1,
                      'or_indicador' =>  strtoupper(utf8_encode($indicador)),
                      'or_linea_base' => $linea_base,
                      'or_meta' => $meta,
                      'or_verificacion' =>  strtoupper(utf8_encode($mverificacion)),
                      'or_observacion' => strtoupper(utf8_encode($observacion)),
                      'g_id' => $this->gestion,
                      'fun_id' => $this->fun_id,
                      'num_ip' => $this->input->ip_address(), 
                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    );
                    $this->db->insert('objetivos_regionales', $data_to_store);
                    $or_id=$this->db->insert_id();
                  }

                } /// B
                $i++;
              } /// A

              $this->session->set_flashdata('success','SE REGISTRARON '.$i.' OPERACIONES');
              redirect(site_url("").'/me/mis_ogestion');
          }
          else{
            echo "Error !!!";
          }
      }
      else{
        echo "Error !!!!";
      }
    }




}