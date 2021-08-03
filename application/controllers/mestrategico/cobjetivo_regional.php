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
          $this->load->library('oregional');
        }else{
            redirect('/','refresh');
        }
    }

    
    /*----- LISTA DE OBJETIVOS REGIONALES (OPERACIONES)----*/
    public function objetivos_regional($og_id){
      $data['menu']=$this->oregional->menu(1);
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['regionales']=$this->oregional->regionales_seleccionados($og_id);

      $this->load->view('admin/mestrategico/objetivos_region/list_oregion', $data);
    }



    /*---------- FORMULARIO ADD OBJ. REGIONAL ------------*/
    public function form_oregional($dep_id,$og_id){
      $data['menu']=$this->menu();
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->oregional->tp_resp();
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      
      $data['formulario']=$this->formulario_add($dep_id,$og_id);
      $this->load->view('admin/mestrategico/objetivos_region/form_oregional', $data);
    }

    /*---------- FORMULARIO UPDATE OBJ. REGIONAL ------------*/
    public function form_update_oregional($or_id){
      $data['menu']=$this->menu();
      $data['oregion']=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($data['oregion'][0]['og_id']); /// Objetivo de Gestion
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['regional']=$this->model_proyecto->get_departamento($data['oregion'][0]['dep_id']);
      
      $data['formulario']=$this->oregional->formulario_update($data['oregion']);
      $this->load->view('admin/mestrategico/objetivos_region/form_update_oregional', $data);
    }


    /*------------ FORMULARIO DE REGISTRO ----------*/
    public function formulario_add($dep_id,$og_id){
      $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $indi= $this->model_proyecto->indicador(); /// indicador
      $get_meta_prog=$this->model_objetivogestion->get_temporalidad_regional($og_id,$dep_id);
      $tabla='';
      $tabla.='
            <article class="col-sm-12">
                <form action="'.site_url("").'/mestrategico/cobjetivo_regional/add_ogestion'.'" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="pog_id" id="pog_id" value="'.$get_meta_prog[0]['pog_id'].'">
                  <input type="hidden" name="dep_id" id="dep_id" value="'.$dep_id.'">
                  <input type="hidden" name="nro" id="nro" value="'.count($this->model_objetivoregion->list_unidades_total($dep_id)).'">
                  <input type="hidden" name="meta_reg" id="meta_reg" value="'.$get_meta_prog[0]['prog_fis'].'">
                  <input type="hidden" name="tp" id="tp" value="1">
                  <input type="hidden" name="ogestion" id="ogestion" value="'.$ogestion[0]['og_objetivo'].'">
                  <div class="col-sm-12">
                    <h2 class="alert alert-success"><center>DETALLE - OPERACIÓN</center></h2>
                    <div class="well">
                    <header><b>DECRIPCI&Oacute;N OPERACIÓN -  COD: '.$ogestion[0]['og_codigo'].'</b></header>
                      <fieldset>          
                        <div class="row">
                        <section class="col col-3">
                          <label class="label">OPERACIÓN</label>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" name="oregional" id="oregional" title="REGISTRE OBJETIVO REGIONAL" >'.$ogestion[0]['og_objetivo'].'</textarea>
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">PRODUCTO</label>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" name="producto" id="producto" title="REGISTRE PRODUCTO"  disabled>'.$ogestion[0]['og_producto'].'</textarea>
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">RESULTADO</label>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" name="resultado" id="resultado" title="REGISTRE RESULTADO" disabled>'.$ogestion[0]['og_resultado'].'</textarea>
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">INDICADOR</label>
                          <label class="textarea">
                            <i class="icon-append fa fa-tag"></i>
                            <textarea rows="3" name="indicador" id="indicador" title="REGISTRE INDICADOR" disabled>'.$ogestion[0]['og_indicador'].'</textarea>
                          </label>
                        </section>
                        </div>

                        <div class="row">
                          <section class="col col-3">
                          <label class="label">LINEA BASE</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="lbase" id="lbase" title="LINEA BASE" value='.round($ogestion[0]['og_linea_base'],2).' onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" disabled>
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">META</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="meta" id="meta" title="META" value="'.round($get_meta_prog[0]['prog_fis'],2).'" onkeyup="verif_meta()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" disabled>
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="mverificacion" id="mverificacion" title="REGISTRE MEDIO DE VERIFICACION" disabled>'.$ogestion[0]['og_verificacion'].'</textarea>
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">OBSERVACIONES</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="observaciones" id="observaciones" title="REGISTRE OBSERVACIONES"></textarea>
                          </label>
                          </section>
                        </div>
                        <div id="atit"></div>
                      </fieldset>
                    </div>
                    <br>
                    <h2 class="alert alert-success"><center>DISTRIBUCI&Oacute;N</center></h2>';
                    $num=0;
                    $distritales=$this->model_proyecto->list_distritales($dep_id);
                    foreach($distritales as $row){
                      $tabla.='
                      <div class="well">
                      <header><b>'.$row['dist_id'].'.- '.strtoupper($row['dist_distrital']).'</b></header>
                        <fieldset>          
                          <div class="row">';
                          $niveles=$this->model_objetivoregion->list_niveles();
                          foreach($niveles as $rown){
                            $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($row['dist_id'],$rown['tn_id']);
                            if(count($nivel)!=0){
                              $tabla.='
                                <table class="table table-bordered" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th style="width:100%;">'.$rown['descripcion'].'</th>
                                    <tr>
                                  </thead>
                                  <tbody>
                                  <tr>
                                  <td>';
                                  foreach($nivel as $rowu){
                                    $num++;
                                    $tabla.='
                                    <section class="col col-2">
                                      <label class="label">(<b>'.$rowu['aper_programa'].'</b>) '.$rowu['tipo'].' '.$rowu['act_descripcion'].'</label>
                                      <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="hidden" name="act_id[]" value="'.$rowu['act_id'].'">
                                        <input type="text" name="uni_id[]" id="uni'.$num.'" title="UNIDAD" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                                      </label>
                                    </section>';
                                  }
                                  $tabla.='
                                  </td>
                                  </tr>
                                  </tbody>
                                </table><br>';
                            }
                          }
                    }
                    $tabla.='
                          
                          </div>
                        </fieldset>
                      </div><br>
                  </div> 
                  <input type="hidden" name="total" id="total" value="0">
                  <br><hr>
                  <div class="row">
                    <section class="col col-2">
                    <label class="label"><font color=blue><b>META REGIONAL</b></font></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="met" id="met" title="META REGIONAL" value="'.$get_meta_prog[0]['prog_fis'].'" disabled=true>
                      </label>
                    </section>
                    <section class="col col-2">
                    <label class="label"><font color=blue><b>SUMA TOTAL PROGRAMADO</b></font></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="sum" id="sum" title="LINEA BASE + PROGRAMADO" value="0" disabled=true>
                      </label>
                    </section>
                  </div>';
                 /* $styl='style="display:none;"';
                  if($get_meta_prog[0]['prog_fis']==0){
                    $styl='';
                  }*/

        $tabla.='<div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_fregional" id="subir_fregional" class="btn btn-info" >GUARDAR OBJETIVO REGIONAL</button>
                      <a href="'.base_url().'index.php/me/objetivos_regionales/'.$og_id.'" title="SALIR" class="btn btn-default">CANCELAR</a>
                    </footer>
                    <div id="loadp" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO OPERACI&Oacute;N</b>
                    </div>
                  </div>
                </form>
            </article>';

      return $tabla;
    }

   

    /*--- VALIDA ADD / UPDATE OBJETIVO REGIONAL ---*/
    public function add_ogestion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tipo id

        $objetivo = $this->security->xss_clean($post['oregional']); /// Objetivo
        $observacion = $this->security->xss_clean($post['observaciones']); /// Observacion
        $meta_reg = $this->security->xss_clean($post['meta_reg']); /// Meta regional


        if($tp==1){ //// INSERT
          $pog_id = $this->security->xss_clean($post['pog_id']); /// pog id
          $dep_id = $this->security->xss_clean($post['dep_id']); /// dep id
          $ogestion=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id);
          $data_to_store = array(
            'pog_id' => $pog_id,
            'or_objetivo' => strtoupper($objetivo),
            'or_producto' => $ogestion[0]['og_producto'],
            'or_codigo' => $ogestion[0]['og_codigo'],
            'or_resultado' => $ogestion[0]['og_resultado'],
            'indi_id' => 1,
            'or_indicador' => $ogestion[0]['og_indicador'],
            'or_linea_base' => $ogestion[0]['og_linea_base'],
            'or_meta' => $ogestion[0]['prog_fis'],
            'or_verificacion' => $ogestion[0]['og_verificacion'],
            'or_observacion' => strtoupper($observacion),
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
            'or_meta' => $this->security->xss_clean($post['meta']),
            'or_verificacion' => strtoupper($this->security->xss_clean($post['verificacion'])),
            'or_observacion' => strtoupper($this->security->xss_clean($post['observaciones'])),
           // 'or_codigo' => $oregion[0]['og_codigo'],
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

        $obj_gestion=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id);
        $this->session->set_flashdata('success','REGISTRO CORRECTO !!! ');
        redirect(site_url("").'/me/objetivos_regionales/'.$obj_gestion[0]['og_id'].'#tabs-'.$obj_gestion[0]['dep_id'].'');

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
          }

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
      $data['ogestion']=$this->model_objetivogestion->get_objetivosgestion($og_id); 
      if(count($data['ogestion'])!=0){
        $data['mes'] = $this->mes_nombre();
        $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($data['ogestion'][0]['acc_id']);
        $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
        $data['regionales']=$this->model_objetivogestion->list_temporalidad_regional($og_id);
        $data['og_id']=$og_id;
        //$data['oregionales']=$this->reporte_lista_oregionales($og_id);
        $this->load->view('admin/mestrategico/objetivos_gestion/reporte_objetivos_regionales', $data); 
      }
      else{
        echo "Error !!!";
      }
    }

    public function reporte_lista_oregionales($og_id){
      $tabla='';
      $regionales=$this->model_objetivogestion->list_temporalidad_regional($og_id);

      foreach($regionales as $row){
        $oregional=$this->model_objetivoregion->list_oregional_regional($og_id,$row['dep_id']);
        
        $tabla.='REGIONAL : '.$row['dep_departamento'].' - '.$row['prog_fis'].'<br>';
        $nro=0;
        if(count($oregional)!=0){
          foreach($oregional as $row_or){
          $nro++;
           $tabla.='
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                <thead>
                  <tr style="font-size: 8px;" bgcolor="#d8d8d8" align=center>
                    <th style="width:2%;height:25px;">#</th>
                    <th style="width:5%;">COD. O.G.</th>
                    <th style="width:15%;">OPERACIÓN '.$this->gestion.'</th>
                    <th style="width:15%;">PRODUCTO</th>
                    <th style="width:14%;">RESULTADO (LOGROS)</th>
                    <th style="width:13%;">INDICADOR</th>
                    <th style="width:5%;">LINEA BASE</th>
                    <th style="width:5%;">META</th>
                    <th style="width:13%;">MEDIO DE VERIFICACI&Oacute;N</th>
                    <th style="width:13%;">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</th>
                  </tr>
                </thead>
              <tbody>';
              $tabla.='
              <tr style="font-size: 7px;">
                <td style="width:2%; height:17px;" align=center>'.$nro.'</td>
                <td style="width:5%;">'.$row_or['og_codigo'].'</td>
                <td style="width:15%;">'.$row_or['or_objetivo'].'</td>
                <td style="width:15%;">'.$row_or['or_producto'].'</td>
                <td style="width:14%;">'.$row_or['or_resultado'].'</td>
                <td style="width:13%;">'.$row_or['or_indicador'].'</td>
                <td style="width:5%;">'.$row_or['or_linea_base'].'</td>
                <td style="width:5%;">'.$row_or['or_meta'].'</td>
                <td style="width:13%;">'.$row_or['or_verificacion'].'</td>
                <td style="width:13%;">'.$row_or['or_observacion'].'</td>
              </tr>
              </tbody>
              </table><br>';

              $tabla.=
              '<table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                <thead>
                <tr>
                  <th colspan=4 bgcolor="#e4e2e2" style="height:20px;" align=center>DISTRIBUCI&Oacute;N</th>
                </tr>
                <tr>
                  <th style="width:25%; height:20px;" bgcolor="#e4e2e2" align=center>REGIONAL / DISTRITAL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>PRIMER NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>SEGUNDO NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>TERCER NIVEL</th>
                </tr>
                </thead>
                <tbody>
                  <tr style="text-align: center;">
                    <td style="width: 25%;">';
                      $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],0);
                      if(count($nivel)!=0){
                        $tabla.=
                        '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                          <thead>
                          <tr>
                            <th style="width:1%; height:16px;" align=center>#</th>
                            <th style="width:9%;" align=center>TIPO</th>
                            <th style="width:48%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                            <th style="width:30%;" align=center>DISTRITAL</th>
                            <th style="width:10%;" align=center>PROG.</th>
                          </tr>
                          </thead>
                          <tbody>';
                          $nro_nivel=0;
                          foreach($nivel as $row_n){
                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                            $prog='-'; $bgcolor='';
                            if(count($uni)!=0){
                              $prog=$uni[0]['prog_fis'];
                              $bgcolor='#dcebf9';
                            }
                            $nro_nivel++;
                            $tabla.=
                            '<tr bgcolor='.$bgcolor.'>
                              <td style="width:1%;height:14px; text-align: center;">'.$nro_nivel.'</td>
                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                              <td style="width:48%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                              <td style="width:30%; text-align: left;">'.strtoupper($row_n['dist_distrital']).'</td>
                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                            </tr>';
                          }
                          $tabla.='
                          </tbody>
                        </table><br>';
                      }
                    $tabla.='
                    </td>
                    <td style="width: 25%;">';
                      $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],1);
                      if(count($nivel)!=0){
                        $tabla.=
                        '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                          <thead>
                          <tr>
                            <th style="width:1%; height:16px;" align=center>#</th>
                            <th style="width:9%;" align=center>TIPO</th>
                            <th style="width:48%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                            <th style="width:30%;" align=center>DISTRITAL</th>
                            <th style="width:10%;" align=center>PROG.</th>
                          </tr>
                          </thead>
                          <tbody>';
                          $nro_nivel=0;
                          foreach($nivel as $row_n){
                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                            $prog='-'; $bgcolor='';
                            if(count($uni)!=0){
                              $prog=$uni[0]['prog_fis'];
                              $bgcolor='#dcebf9';
                            }
                            $nro_nivel++;
                            $tabla.=
                            '<tr bgcolor='.$bgcolor.'>
                              <td style="width:1%;height:14px; text-align: center;">'.$nro_nivel.'</td>
                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                              <td style="width:48%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                              <td style="width:30%; text-align: left;">'.strtoupper($row_n['dist_distrital']).'</td>
                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                            </tr>';
                          }
                          $tabla.='
                          </tbody>
                        </table><br>';
                      }
                    $tabla.='
                    </td>
                    <td style="width: 25%;">';
                      $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],2);
                      if(count($nivel)!=0){
                        $tabla.=
                        '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                          <thead>
                          <tr>
                            <th style="width:1%; height:16px;" align=center>#</th>
                            <th style="width:9%;" align=center>TIPO</th>
                            <th style="width:48%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                            <th style="width:30%;" align=center>DISTRITAL</th>
                            <th style="width:10%;" align=center>PROG.</th>
                          </tr>
                          </thead>
                          <tbody>';
                          $nro_nivel=0;
                          foreach($nivel as $row_n){
                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                            $prog='-'; $bgcolor='';
                            if(count($uni)!=0){
                              $prog=$uni[0]['prog_fis'];
                              $bgcolor='#dcebf9';
                            }
                            $nro_nivel++;
                            $tabla.=
                            '<tr bgcolor='.$bgcolor.'>
                              <td style="width:1%;height:14px; text-align: center;">'.$nro_nivel.'</td>
                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                              <td style="width:48%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                              <td style="width:30%; text-align: left;">'.strtoupper($row_n['dist_distrital']).'</td>
                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                            </tr>';
                          }
                          $tabla.='
                          </tbody>
                        </table><br>';
                      }
                    $tabla.='
                    </td>
                    <td style="width: 25%;">';
                      $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],3);
                      if(count($nivel)!=0){
                        $tabla.=
                        '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                          <thead>
                          <tr>
                            <th style="width:1%; height:16px;" align=center>#</th>
                            <th style="width:9%;" align=center>TIPO</th>
                            <th style="width:48%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                            <th style="width:30%;" align=center>DISTRITAL</th>
                            <th style="width:10%;" align=center>PROG.</th>
                          </tr>
                          </thead>
                          <tbody>';
                          $nro_nivel=0;
                          foreach($nivel as $row_n){
                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                            $prog='-'; $bgcolor='';
                            if(count($uni)!=0){
                              $prog=$uni[0]['prog_fis'];
                              $bgcolor='#dcebf9';
                            }
                            $nro_nivel++;
                            $tabla.=
                            '<tr bgcolor='.$bgcolor.'>
                              <td style="width:1%;height:14px; text-align: center;">'.$nro_nivel.'</td>
                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                              <td style="width:48%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                              <td style="width:30%; text-align: left;">'.strtoupper($row_n['dist_distrital']).'</td>
                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                            </tr>';
                          }
                          $tabla.='
                          </tbody>
                        </table><br>';
                      }
                    $tabla.='
                    </td>
                  </tr>
                </tbody>
              </table>';

              
          }
        }
        else{
          $tabla.='Sin Registro';
        }

        
      }

      return $tabla;
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

    /*------------------------------------- MENU -----------------------------------*/
    function menu(){
      $enlaces=$this->menu_modelo->get_Modulos(1);
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

    /*------------------------- COMBO RESPONSABLES ----------------------*/
    public function combo_funcionario_unidad_organizacional($accion='') 
    { 
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
}