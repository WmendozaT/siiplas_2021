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
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->fun_id = $this->session->userData('fun_id');
          $this->load->library('acortoplazo');
        }else{
            redirect('/','refresh');
        }
    }

 
    
    /*----- LISTA ACCION DE CORTO PLAZO 2022 ----*/
    public function list_objetivos_gestion(){
      $data['menu']=$this->acortoplazo->menu(1);
      $data['titulo']=$this->acortoplazo->titulo();
      $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
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

    /*------- VALIDA OBJETIVO DE GESTION -------*/
    public function valida_ogestion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tipo id
        $form = $this->security->xss_clean($post['form']); /// from 1: por accion estrategica, 0: lista de Objetivos de gestion 
        
        if($tp==1){
          $acc_id = $this->security->xss_clean($post['acc_id']); /// acc id
          $objetivo = $this->security->xss_clean($post['ogestion']); /// Objetivo
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $producto = $this->security->xss_clean($post['producto']); /// Producto
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
            'og_codigo' => $codigo,
            'og_objetivo' => strtoupper($objetivo),
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
          $acc_id = $this->security->xss_clean($post['macc_id']); /// acc id
          $objetivo = $this->security->xss_clean($post['mogestion']); /// Objetivo
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
            'acc_id' => $acc_id,
            'og_objetivo' => strtoupper($objetivo),
            'og_producto' => strtoupper($producto),
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
                    'or_meta' => $prog_fis,
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


    /*----- Reporte objetivo de Gestion segun Accion estrategica -----*/
/*    public function reporte_objetivos_gestion($acc_id){
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      if(count($data['accion_estrategica'])!=0){
        $data['mes'] = $this->acortoplazo->mes_nombre();
        $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
        $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
        $data['ogestion']=$this->rep_list_ogestion($acc_id);

        $this->load->view('admin/mestrategico/objetivos_gestion/reporte_ogestion', $data); 
      }
      else{
        echo "Error !!!";
      }
    }*/

    /*----- Reporte Lista de objetivo de Gestion -----*/
  /*  public function rep_list_ogestion($acc_id){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion($acc_id); /// OBJETIVOS DE GESTION
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($acc_id); // ACCIONES ESTRATEGICAS
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']); /// OBJETIVOS ESTRATEGICOS
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
        <thead>
          <tr style="font-size: 8px;" bgcolor="#d8d8d8" align=center>
            <th style="width:2%;height:20px;">#</th>
            <th style="width:3%;">COD. O.E.</th>
            <th style="width:3%;">COD. A.E.</th>
            <th style="width:10%;">ACCIÓN DE CORTO PLAZO</th>
            <th style="width:10%;">PRODUCTO</th>
            <th style="width:10%;">RESULTADO</th>
            <th style="width:5%;">TP. INDI.</th>
            <th style="width:9%;">INDICADOR</th>
            <th style="width:3.3%;">LINEA BASE</th>
            <th style="width:3.3%;">META</th>
            <th style="width:3.3%;" title="CHUQUISACA">CH.</th>
            <th style="width:3.3%;" title="LA PAZ">LPZ.</th>
            <th style="width:3.3%;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3.3%;" title="ORURO">OR.</th>
            <th style="width:3.3%;" title="POTOSI">POT.</th>
            <th style="width:3.3%;" title="TARIJA">TJA.</th>
            <th style="width:3.3%;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3.3%;" title="BENI">BE.</th>
            <th style="width:3.3%;" title="PANDO">PN</th>
            <th style="width:3.3%;" title="OFICINA NACIONAL">OFN</th>
            <th style="width:8%;">MEDIO VERIFICACI&Oacute;N</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($ogestion  as $row){
          $nro++;
          $tabla .='<tr style="font-size: 7px;">';
            $tabla .='<td style="width:2%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:3%;" align="center">'.$objetivos[0]['obj_codigo'].'</td>';
            $tabla .='<td style="width:3%;" align="center">'.$acciones[0]['acc_codigo'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:5%;">'.strtoupper($row['indi_descripcion']).'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:3.3%;">'.$row['og_linea_base'].'</td>';
            $tabla .='<td style="width:3.3%;">'.$row['og_meta'].'</td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5">'.$dep[0]['prog_fis'].'</td>';
              }
              else{
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5">0</td>';
              }
            }
            $tabla.='<td style="width:8%;">'.$row['og_verificacion'].'</td>';
          $tabla.='</tr>';
        }
        $tabla.='
        </tbody>
       </table>';

       return $tabla;
    }*/


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
    $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id);
    $data['titulo']='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="well">
            <h2>ACP. : '.$ogestion[0]['og_codigo'].' .- '.$ogestion[0]['og_objetivo'].'</h2>
            <a href="javascript:abreVentana(\''.site_url("").'/me/rep_ogestion\');" title="IMPRIMIR" class="btn btn-default">
              <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. A.C.P. - FORM 4
            </a>
          </div>
        </article>';

    $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades($og_id); /// ALINEACION ACP a ACT.
    
      foreach($alienacion_acp  as $row){
        $tabla.='
        <tr>
          <td align=center><b>'.$row['og_codigo'].'</b></td>
          <td>'.strtoupper($row['dep_departamento']).'</td>
          <td align=center>'.$row['og_codigo'].'.'.$row['or_codigo'].'.</td>
          <td>'.strtoupper($row['or_objetivo']).'</td>
          <td>'.strtoupper($row['or_indicador']).'</td>
          <td>'.strtoupper($row['or_producto']).'</td>
          <td>'.strtoupper($row['or_resultado']).'</td>
          <td align=right>'.round($row['or_linea_base'],2).'</td>
          <td align=right>'.round($row['or_meta'],2).'</td>

          <td bgcolor="#e7f3f1" align=center>'.strtoupper($row['prod_cod']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_producto']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_indicador']).'</td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_unidades']).'</td>
          <td bgcolor="#e7f3f1" align=right><b>'.round($row['prod_linea_base'],2).'</b></td>
          <td bgcolor="#e7f3f1" align=right><b>'.round($row['prod_meta'],2).'</b></td>
          <td bgcolor="#e7f3f1">'.strtoupper($row['prod_fuente_verificacion']).'</td>';
          for ($i=1; $i <=12 ; $i++) { 
            $tabla.='
            <td bgcolor="#e7f3f1" align=right><b>'.round($row['mes'.$i]).'</b></td>';
          }

        $tabla.='
        </tr>';
      }

    $data['tabla']=$tabla;
    $this->load->view('admin/mestrategico/objetivos_gestion/ver_alineacion_acp_act', $data); 
  }
}