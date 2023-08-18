<?php
class Cobjetivo_gestion extends CI_Controller {
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
          $this->load->model('programacion/model_producto');
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->fun_id = $this->session->userData('fun_id');
          $this->dep_id = $this->session->userData('dep_id');
          $this->tp_adm = $this->session->userData('tp_adm');
          $this->load->library('acortoplazo');
        }else{
            redirect('/','refresh');
        }
    }

 
    
    /*----- LISTA ACCION DE CORTO PLAZO 2024 ----*/
    public function list_objetivos_gestion(){
      $data['menu']=$this->acortoplazo->menu(1);
      $data['titulo']=$this->acortoplazo->titulo();
      $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['programas']=$this->model_proyecto->list_prog(); /// Aperturas Programatica
      $data['ogestion']=$this->acortoplazo->mis_ogestion_gral();
      $this->load->view('admin/mestrategico/objetivos_gestion/list_ogestion_general', $data);
    }

    /*----- REPORTE DE ACCION CORTO PLAZO 2022 ----*/
    public function reporte_ogestion(){
      // tp : 1 distribucion Regional
      // tp : 2 distribuacion mensual
      $data['mes'] = $this->acortoplazo->mes_nombre();

      $data['gestion']=$this->gestion;
      $data['cabecera']=$this->acortoplazo->cabecera_acp();
      $data['lista1']= $this->acortoplazo->distribucion_regional(); /// lista 1
      $data['lista2']= $this->acortoplazo->distribucion_mensual(); /// lista 2
      $data['pie']=$this->acortoplazo->pie_form1();
      $this->load->view('admin/mestrategico/objetivos_gestion/reporte_form1', $data);
    }



   /*------- Verifica Codigo Operacion ------*/ 
  function verif_codigo(){
    if($this->input->is_ajax_request()){
      $post = $this->input->post();

      $codigo= $this->security->xss_clean($post['codigo']); /// Codigo

      $verif_cod=$this->model_objetivogestion->get_cod_objetivosgestion($codigo);
      if(count($verif_cod)!=0){
        echo "true"; ///// no existe un CI registrado
      }
      else{
        echo "false"; //// existe el CI ya registrado
      } 
    }else{
      show_404();
    }
  }


    /*============================= POR ACCION ESTRATEGICA =========================*/

    /*----- LISTA OBJETIVOS DE GESTION SEGUN ACCION ESTRATEGICO ----*/
    public function objetivos_gestion($acc_id){
      $data['menu']=$this->acortoplazo->menu(1);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['ogestion']=$this->mis_ogestion($acc_id);
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $this->load->view('admin/mestrategico/objetivos_gestion/list_ogestion', $data);
    }

    /*------- VALIDA ACP -------*/
    public function valida_ogestion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tipo id
        $form = $this->security->xss_clean($post['form']); /// from 1: por accion estrategica, 0: lista de Objetivos de gestion 
        
        if($tp==1){
          //$acc_id = $this->security->xss_clean($post['acc_id']); /// acc id
          $acc_id = 0; /// acc id
          $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
          $oe_id = $this->security->xss_clean($post['oe_id']); /// id Objetivo Estrategico
          $objetivo = $this->security->xss_clean($post['ogestion']); /// Objetivo
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $producto = $this->security->xss_clean($post['producto']); /// Producto
          $formula = $this->security->xss_clean($post['formula']); /// formula
          $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
          $tp_indi = $this->security->xss_clean($post['tp_indi']); /// Tipo de Indicador
          $indicador = $this->security->xss_clean($post['indicador']); /// Indicador
          $lbase = $this->security->xss_clean($post['lbase']); /// Linea Base
          $meta = $this->security->xss_clean($post['meta']); /// Meta
          $verificacion = $this->security->xss_clean($post['verificacion']); /// Verificacion
          $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
          $observacion = $this->security->xss_clean($post['observacion']); /// Observacion

          $data_to_store = array( 
            'acc_id' => $acc_id,
            'aper_id' => $aper_id,
            'oe_id' => $oe_id,
            'og_codigo' => $codigo,
            'og_objetivo' => strtoupper($objetivo),
            'og_formula' => strtoupper($formula),
            'og_producto' => strtoupper($producto),
            'og_resultado' => strtoupper($resultado),
            'indi_id' => $tp_indi,
            'og_indicador' => strtoupper($indicador),
            'og_linea_base' => $lbase,
            'og_meta' => $meta,
            'og_verificacion' => $verificacion,
            'og_unidad' => strtoupper($unidad),
            'og_observacion' => strtoupper($observacion),
            'g_id' => $this->gestion,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('objetivo_gestion', $data_to_store);
          $og_id=$this->db->insert_id();

          for ($i=1; $i <=10 ; $i++) {
            $pfis=$this->security->xss_clean($post['m'.$i]);
            $estado=0;
            if($pfis!=0){
              $estado=1;
            }

            $data_to_store4 = array( 
              'og_id' => $og_id, /// obj id
              'dep_id' => $i, /// dep id 
              'prog_fis' => $pfis, /// Valor mes
              'g_id' => $this->gestion, /// Gestion
              'estado' => $estado, /// Estado
            );
            $this->db->insert('objetivo_programado_mensual', $data_to_store4);
          }

          for ($i=1; $i <=12 ; $i++) {
            if($this->security->xss_clean($post['mes'.$i])!=0){
                $data_to_store5 = array( 
                'og_id' => $og_id, /// og id
                'mes_id' => $i, /// mes id 
                'fis' => $this->security->xss_clean($post['mes'.$i]), /// Valor prog
                'g_id' => $this->gestion, /// Gestion
              );
              $this->db->insert('temporalidad_ogestion', $data_to_store5);
            }
          }
        }
        else{
          $og_id = $this->security->xss_clean($post['mog_id']); /// Obj id
          //$acc_id = $this->security->xss_clean($post['macc_id']); /// acc id
          $acc_id = 0; /// acc id
          $aper_id = $this->security->xss_clean($post['maper_id']); /// aper
          $oe_id = $this->security->xss_clean($post['moe_id']); /// oe_id
          $codigo = $this->security->xss_clean($post['mcod']); /// codigo
          $objetivo = $this->security->xss_clean($post['mogestion']); /// Objetivo
          $formula = $this->security->xss_clean($post['mformula']); /// Formula
          $producto = $this->security->xss_clean($post['mproducto']); /// Producto
          $resultado = $this->security->xss_clean($post['mresultado']); /// Resultado
          $tp_indi = $this->security->xss_clean($post['mtp_indi']); /// Tipo de Indicador
          $indicador = $this->security->xss_clean($post['mindicador']); /// Indicador
          $lbase = $this->security->xss_clean($post['mlbase']); /// Linea Base
          $meta = $this->security->xss_clean($post['mmeta']); /// Meta
          $verificacion = $this->security->xss_clean($post['mverif']); /// Verificacion
          $unidad = $this->security->xss_clean($post['munidad']); /// Unidad
          $observacion = $this->security->xss_clean($post['mobservacion']); /// Observacion

          $update_og= array(
            'aper_id' => $aper_id,
            'oe_id' => $oe_id,
            'og_codigo' => $codigo,
            'og_objetivo' => strtoupper($objetivo),
            'og_producto' => strtoupper($producto),
            'og_formula' => strtoupper($formula),
            'og_resultado' => strtoupper($resultado),
            'indi_id' => $tp_indi,
            'og_indicador' => strtoupper($indicador),
            'og_linea_base' => $lbase,
            'og_meta' => $meta,
            'og_verificacion' => strtoupper($verificacion),
            'og_unidad' => strtoupper($unidad),
            'og_observacion' => strtoupper($observacion),
            'estado' => 2,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
          );
          $this->db->where('og_id', $og_id);
          $this->db->update('objetivo_gestion', $update_og);

          for ($i=1; $i <=10 ; $i++) {
            $estado=0;
            $prog_fis=0;

            $pfis=$this->security->xss_clean($post['mm'.$i]);
            if($pfis!=0 & $pfis!=''){
              $estado=1;
              $prog_fis=$pfis;
            }

            $verif=$this->model_objetivogestion->get_ogestion_regional($og_id,$i);
            if(count($verif)!=0){
             // echo $og_id.'--'.$i.'--'.count($verif).'-- estado '.$estado.'--'.$pfis.'<br>';
              $update_ogp= array(
                'prog_fis' => $prog_fis,
                'estado' => $estado
              );
              $this->db->where('pog_id', $verif[0]['pog_id']);
              $this->db->update('objetivo_programado_mensual', $update_ogp);

              /*------- Actualizando meta objetivo regional ----------*/
              if($this->model_objetivoregion->get_oregional_por_progfis($verif[0]['pog_id'])!=0){
                  $update_ogp= array(
                   // 'or_meta' => $prog_fis,
                    'fun_id' => $this->fun_id
                  );
                  $this->db->where('pog_id', $verif[0]['pog_id']);
                  $this->db->update('objetivos_regionales', $update_ogp);
              }
              /*-------------------------------------------------------*/
            }
            else{
              $data_to_store4 = array( 
                'og_id' => $og_id, /// og id
                'dep_id' => $i, /// dep id 
                'prog_fis' => $prog_fis, /// Valor prog
                'g_id' => $this->gestion, /// Gestion
                'estado' => $estado, /// Estado
              );
              $this->db->insert('objetivo_programado_mensual', $data_to_store4);
            }
          }

          /// Update Temporelidad

          $this->db->where('og_id', $og_id);
          $this->db->delete('temporalidad_ogestion');

          for ($i=1; $i <=12 ; $i++) {
            if($this->security->xss_clean($post['mmes'.$i])!=0){
                $data_to_store5 = array( 
                'og_id' => $og_id, /// og id
                'mes_id' => $i, /// mes id 
                'fis' => $this->security->xss_clean($post['mmes'.$i]), /// Valor prog
                'g_id' => $this->gestion, /// Gestion
              );
              $this->db->insert('temporalidad_ogestion', $data_to_store5);
            }
          }

        }

        $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE)');
        if($form==1){
          redirect(site_url("").'/me/objetivos_gestion/'.$acc_id.'');
        }
        else{
          redirect(site_url("").'/me/mis_ogestion');
        }
        
      } else {
          show_404();
      }
    }

    /*------- GET OBJETIVO GESTION -------*/
    public function get_ogestion(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $og_id = $this->security->xss_clean($post['og_id']); /// Obj id
        $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 
        $ogestion_programado=$this->model_objetivogestion->get_objetivosgestion_temporalidad($og_id);
        $ogestion_programado_mes=$this->model_objetivogestion->get_objetivosgestion_temporalidad_mensual($og_id);

        $apertura='';
        $programas=$this->model_proyecto->list_prog();
        $apertura.='
          <select class="form-control" id="maper_id" name="maper_id" title="SELECCIONE CATEGORIA PROGRAMATICA">
            <option value="">Seleccione Programa</option>';
            foreach($programas as $row){
              if($row['aper_id']==$ogestion[0]['aper_id']){
                $apertura.='<option value="'.$row['aper_id'].'" selected>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.$row['aper_descripcion'].'</option>';
              }
              else{
                $apertura.='<option value="'.$row['aper_id'].'">'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.$row['aper_descripcion'].'</option>';
              }
            }
            $apertura.='
          </select>';

        $tab_oe='';
        $oestrategicos = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
        $tab_oe.='
          <select class="form-control" id="moe_id" name="moe_id" title="SELECCIONE OBJETIVOS ESTRATEGICOS INSTITUCIONALES">
            <option value="">Seleccione Objetivos Estrategicos</option>';
            foreach($oestrategicos as $row){
              if($row['obj_id']==$ogestion[0]['oe_id']){
                $tab_oe.='<option value="'.$row['obj_id'].'" selected>'.$row['obj_codigo'].'.- '.$row['obj_descripcion'].'</option>';
              }
              else{
                $tab_oe.='<option value="'.$row['obj_id'].'">'.$row['obj_codigo'].'.- '.$row['obj_descripcion'].'</option>';
              }
            }
            $tab_oe.='
          </select>';


        $suma=0;$suma_mes=0;
        for ($i=1; $i <=10; $i++) { 
          $dep['reg'.$i.'']=0;
          $dep_verif['verif'.$i.'']=false;
          $titulo['tit'.$i.'']='A PROGRAMAR';
        }

        if(count($ogestion_programado)!=0){
          for ($i=1; $i <=10 ; $i++) { 
            $dep['reg'.$i.'']=round($ogestion_programado[0]['reg'.$i.''],2);
            if(count($this->model_objetivogestion->get_ogestion_oregional_temporalidad($og_id,$i))!=0){
              $dep_verif['verif'.$i.'']=true;
              $titulo['tit'.$i.'']='REGIONAL YA PROGRAMADO';
            }
          }
          $suma=$ogestion_programado[0]['programado_total']+$ogestion[0]['og_linea_base'];
        }

        for ($i=1; $i <=12 ; $i++) { 
          $temp_mes[$i]=0;
        }

        if(count($ogestion_programado_mes)!=0){
          for ($i=1; $i <=12 ; $i++) { 
            $temp_mes[$i]=round($ogestion_programado_mes[0]['m'.$i],2);
          }
          $suma_mes=$ogestion_programado_mes[0]['programado_total'];
        }


        if(count($ogestion)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'apertura' => $apertura,
            'tab_oe' => $tab_oe,
            'ogestion' => $ogestion,
            'oprogramado' => $dep,
            'temporalidad' => $temp_mes,
            'verif_programado' => $dep_verif,
            'titulo' => $titulo,
            'suma' => round($suma,2),
            'suma_mes' => round($suma_mes,2),
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

    /*-------- ELIMINAR OBJETIVO DE GESTION --------*/
    function delete_ogestion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $og_id = $this->security->xss_clean($post['og_id']);
          /*----- UPDATE O. GESTION ----*/
          $update_og= array(
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'estado' => 3
          );
          $this->db->where('og_id', $og_id);
          $this->db->update('objetivo_gestion', $update_og);

          //// Eliminando Temporalidad mensual
          $this->db->where('og_id', $og_id);
          $this->db->delete('temporalidad_ogestion');
          //// ----------------------------------

          $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 
          if(count($ogestion)==0){
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



  ///// ===== REPORTE CONSOLIDADO ALINEACION ACP A ACTIVIDADES

  public function rep_alineacion_acp_act($og_id){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id);
    $data['titulo']='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input type="hidden" name="base" value="'.base_url().'">
          <div class="well">
            <h2>ACP. : '.$ogestion[0]['og_codigo'].' .- '.$ogestion[0]['og_objetivo'].'</h2>
            <a href="'.site_url("").'/me/exportar_alineacion_ope_acp/'.$og_id.'" title="EXPORTAR EN EXCEL" class="btn btn-default">
              <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;EXPORTAR ALINEACION EN EXCEL
            </a>
            <div class="btn-group">
              <button class="btn btn-default">
                <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR REPORTE DE ALINEACION POA
              </button>
              <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/1\');" title="ALINEACION CHUQUISACA">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - CHUQUISACA</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/2\');" title="ALINEACION LA PAZ">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - LA PAZ</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/3\');" title="ALINEACION COCHABAMBA">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - COCHABAMBA</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/4\');" title="ALINEACION ORURO">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - ORURO</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/5\');" title="ALINEACION POTOSI">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - POTOSI</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/6\');" title="ALINEACION TARIJA">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - TARIJA</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/7\');" title="ALINEACION SANTA CRUZ">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - SANTA CRUZ</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/8\');" title="ALINEACION BENI">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - BENI</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/9\');" title="ALINEACION PANDO">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - PANDO</a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/10\');" title="ALINEACION OFICINA CENTRAL">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - OFICINA NACIONAL</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/reporte_alineacion_ope_acp/'.$og_id.'/0\');" title="ALINEACION CONSOLIDADO">ALINEACIÓN ACP '.$ogestion[0]['og_codigo'].' / '.$this->gestion.' - CONSOLIDADO INSITUCIONAL</a>
                </li>
              </ul>
            </div>
          </div>
        </article>';

        if($this->fun_id==399 || $this->fun_id==401  || $this->fun_id==418){
          $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades($og_id); /// ALINEACION ACP a ACT. (Todos)
        }
        else{
          $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades_regional($og_id,$this->dep_id); /// ALINEACION ACP a ACT.
        }
      
      foreach($alienacion_acp  as $row){
        $por=''; 
        if($row['indi_id']==2){
          $por='%';
        
        }
        $nombre=strtoupper($row['tipo']).' '.strtoupper($row['act_descripcion']).' '.$row['abrev'];
        if($row['tp_id']==1){
          $nombre=strtoupper($row['proy_nombre']);
        }
        $tabla.='
        <tr>
          <td style="font-size: 20px;font-family: Arial;" align=center><b>'.$row['og_codigo'].'</b></td>
          <td style="font-size: 13px;font-family: Arial;"><b>'.strtoupper($row['dep_departamento']).'</b></td>
          <td style="font-size: 20px;font-family: Arial;" align=center><b>'.$row['or_codigo'].'</b></td>
          <td>'.strtoupper($row['or_objetivo']).'</td>
          <td>'.strtoupper($row['or_indicador']).'</td>
          <td>'.strtoupper($row['or_producto']).'</td>
          <td>'.strtoupper($row['or_resultado']).'</td>
          <td style="font-size: 17px;font-family: Arial;" align=right>'.round($row['or_meta'],2).'</td>
          <td bgcolor="#eaf2fd">'.$row['proy_sisin'].'</td>
          <td bgcolor="#eaf2fd">'.$nombre.'</td>
          <td bgcolor="#eaf2fd">'.$row['serv_cod'].' '.strtoupper($row['tipo_subactividad']).' '.strtoupper($row['serv_descripcion']).'</td>
          <td>';
            if($this->dep_id==$row['dep_id'] || $this->fun_id==399 || $this->fun_id==401 || $this->fun_id==418){
              $tabla.='<a href="'.site_url("").'/admin/prog/list_prod/'.$row['com_id'].'" target="_blank" class="btn btn-default" target title="MIS ACTIVIDADES"><img src="'.base_url().'assets/Iconos/folder_go.png" WIDTH="30" HEIGHT="30"/></a>';
            }
          $tabla.='
          </td>
          <td>
            <select class="form-control" onchange="doSelectAlert(event,this.value,'.$row['prod_id'].');">';
              if($row['prod_priori']==1){
                $tabla .="
                <option value=1 selected>PRIORIDAD</option>
                <option value=0>NO</option>";
              }
              else{
                $tabla .="
                <option value=1>PRIORIDAD</option>
                <option value=0 selected>NO</option>";
              }
            $tabla.='</select>
          </td>
          <td style="font-size: 20px;font-family: Arial;" bgcolor="#e7f3f1" align=center><b>'.strtoupper($row['prod_cod']).'</b></td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_producto']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_indicador']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_unidades']).'</td>
          <td bgcolor="#e7f3f1" align=right><b>'.round($row['prod_linea_base'],2).'</b></td>
          <td style="font-size: 17px;font-family: Arial;" bgcolor="#e7f3f1" align=right><b>'.round($row['prod_meta'],2).' '.$por.'</b></td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['mt_tipo']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_fuente_verificacion']).'</td>';
        $tabla.='
        </tr>';
      }

    if($this->tp_adm==1){
      $data['tabla']=$tabla;
      $this->load->view('admin/mestrategico/objetivos_gestion/ver_alineacion_acp_act', $data); 
    }
    else{
      redirect(site_url("").'/admin/dashboard');
    }

  }





    /*------ EXPORTAR ALINEACION ACP-FORM 2 Y 3-----*/
    public function exportar_alineacion_acp_act($og_id){
      $acp=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades_nacional_completo($og_id); /// ALINEACION ACP a ACT.
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
          <tr style="height:50px;">
            <th>CODIGO DA</th>
            <th>UNIDAD ADMINISTRATIVA</th>
            <th>COD. ACP.</th>
            <th>COD. OPE.</th>
            <th>DESCRIPCION OPERACION '.$this->gestion.'</th>
            <th>INDICADOR OPERACION</th>
            <th>PRODUCTO OPERACION</th>
            <th>RESULTADO OPERACION</th>
            <th>META OPERACION</th>


            <th>DESCRIPCION GASTO CORRIENTE</th>
            <th>UNIDAD RESPONSABLE</th>

            <th>COD. ACT.</th>
            <th>DESCRIPCION ACTIVIDAD</th>
            <th>INDICADOR</th>
            <th>UNIDADES RESPONSABLES</th>
            <th>MEDIO DE VERIFICACION</th>
            <th>TIPO DE META</th>
            <th>LINEA BASE</th>
            <th>META</th>
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
        </thead>
      <tbody>';
      foreach($alienacion_acp  as $row){
        $por=''; 
        if($row['indi_id']==2){
          $por='%';
        }

        $tabla.='
        <tr style="height:40px;">
          <td style="font-family: Arial;" align=center><b>'.$row['og_codigo'].'</b></td>
          <td style="font-family: Arial;">'.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
          <td style="font-size: 15px;font-family: Arial;" align=center>'.$row['og_codigo'].'</td>
          <td style="font-size: 15px;font-family: Arial;" align=center>'.$row['or_codigo'].'</td>
          <td style="font-family: Arial;">'.mb_convert_encoding(strtoupper($row['or_objetivo']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;">'.mb_convert_encoding(strtoupper($row['or_indicador']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;">'.mb_convert_encoding(strtoupper($row['or_producto']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;">'.mb_convert_encoding(strtoupper($row['or_resultado']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" align=right>'.round($row['or_meta'],2).'</td>

          <td style="font-family: Arial;" bgcolor="#eaf2fd">'.mb_convert_encoding(strtoupper($row['tipo']).' '.strtoupper($row['act_descripcion']).' '.$row['abrev'], 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" bgcolor="#eaf2fd">'.mb_convert_encoding($row['serv_cod'].' '.strtoupper($row['tipo_subactividad']).' '.strtoupper($row['serv_descripcion']), 'cp1252', 'UTF-8').'</td>

          <td style="font-size: 15px;font-family: Arial;" bgcolor="#e7f3f1" align=center>'.strtoupper($row['prod_cod']).'</td>
          <td style="font-family: Arial;" bgcolor="#e7f3f1">'.mb_convert_encoding(strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" bgcolor="#e7f3f1">'.mb_convert_encoding(strtoupper($row['prod_indicador']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" bgcolor="#e7f3f1">'.mb_convert_encoding(strtoupper($row['prod_unidades']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" bgcolor="#e7f3f1">'.mb_convert_encoding(strtoupper($row['prod_fuente_verificacion']), 'cp1252', 'UTF-8').'</td>
          <td style="font-family: Arial;" bgcolor="#e7f3f1">'.mb_convert_encoding(strtoupper($row['mt_tipo']), 'cp1252', 'UTF-8').'</td>
          <td style="font-size: 12px;font-family: Arial;" bgcolor="#e7f3f1" align=right><b>'.round($row['prod_linea_base'],2).'</b></td>
          <td style="font-size: 12px;font-family: Arial;" bgcolor="#e7f3f1" align=right><b>'.round($row['prod_meta'],2).' '.$por.'</b></td>';
          
          for ($i=1; $i <=12 ; $i++) { 
            $tabla.='
            <td style="font-size: 12px;font-family: Arial;" bgcolor="#e7f3f1" align=right><b>'.round($row['mes'.$i]).' '.$por.'</b></td>';
          }

        $tabla.='
        </tr>';
      }
      $tabla.='</tbody>
      </table>';

      date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=ACP_".$acp[0]['og_codigo']." ".$this->gestion."_$fecha.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");

    echo $tabla;

    }


    /*------ REPORTE ALINEACION ACP-FORM 2 Y 4-----*/
    public function reporte_alineacion_acp_act($og_id,$dep_id){
      $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $data['cabecera']=$this->acortoplazo->cabecera_alineacion_acp($ogestion);
      $data['pie']=$this->acortoplazo->pie_rep_alineacion_acp();
      $data['alineacion']=$this->acortoplazo->rep_lista_alineacion_poa($og_id,$dep_id);
    
     // echo $data['alineacion'];
      $this->load->view('admin/mestrategico/objetivos_gestion/reporte_alineacion_acp_act', $data); 
    }


}