<?php
class Cevaluacion_oregional extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dep_id = $this->session->userData('dep_id');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->conf_estado = $this->session->userData('conf_estado'); /// conf estado Gestion (1: activo, 0: no activo)
        $this->load->library('eval_oregional');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*-- Menu Regional 2022 --*/
    public function menu_regional(){
      $data['menu']=$this->eval_oregional->menu(4); //// genera menu
      $data['titulo']=$this->eval_oregional->titulo();
      
      if($this->tp_adm==1){
        $data['tabla']=$this->eval_oregional->regionales();
      }
      else{
        $data['tabla']=$this->eval_oregional->ver_relacion_ogestion($this->dep_id);
      }

      $this->load->view('admin/evaluacion/evaluacion_oregional/menu_regionales', $data);
    }



    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE --------*/
    public function update_temporalidad_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $departamento=$this->model_proyecto->get_departamento($dep_id);

        $this->eval_oregional->create_temporalidad_oregional($dep_id); /// creando la temporalidad de los Objetivos REgioanles

        $tabla='';
        $tabla.='
          <hr><h3><b>&nbsp;&nbsp;OPERACIONES '.$this->gestion.': REGIONAL '.strtoupper($departamento[0]['dep_departamento']).'</b></h3><hr>
          <div class="alert alert-success alert-block" align=center>
            <h2> LA TEMPORALIDAD DE OBJETIVOS DE GESTIÓN FUE ACTUALIZADO EXITOSAMENTE !!!</2> 
          </div>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*---- FUNCION GET LISTA DE ACTIVIDADES PRIORIZADOS --------*/
    public function ver_actividades_priorizados(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $or_id = $this->security->xss_clean($post['or_id']); /// or id
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($post['or_id']); /// Objetivo Regional
        $regional=$this->model_proyecto->get_departamento($dep_id);

        $titulo='
        <b style="font-family:Verdana;font-size: 16px;">
          OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['or_codigo'].' '.$detalle_oregional[0]['or_objetivo'].'<br>
          META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).'
        </b>';


          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$this->eval_oregional->get_mis_form4_priorizados_x_oregional($or_id),
            'titulo'=>$titulo,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*---- FUNCION GET NIVEL DE CUMPLIMIENTO DE LA OPERACION (GRAFICOS) --------*/
    public function ver_datos_avance_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $or_id = $this->security->xss_clean($post['or_id']); /// or id
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($post['or_id']); /// Objetivo Regional
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $calificacion=$this->eval_oregional->calificacion_trimestral_acumulado_x_oregional($or_id,$this->tmes);

        $titulo='
        <b style="font-family:Verdana;font-size: 16px;">
          OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['or_codigo'].' '.$detalle_oregional[0]['or_objetivo'].'<br>
          META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).'
        </b>';


          $result = array(
            'respuesta' => 'correcto',
            //'tabla'=>$this->eval_oregional->get_mis_form4_priorizados_x_oregional($or_id),
            'titulo'=>$titulo,
            'datos'=>$calificacion,
            'trimestre'=>$this->tmes,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


   //// Reporte de Evaluacion formulario N° 2
  public function reporte_evaluacion_form2($dep_id){
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $data['mes'] = $this->eval_oregional->mes_nombre();
    $data['cabecera']=$this->eval_oregional->cabecera_form2($regional);
    $data['oregional']=$this->eval_oregional->rep_lista_form2($dep_id);
    $data['pie']=$this->eval_oregional->pie_form2($regional);
    $data['titulo_pie']='EVALUACION_FORM2_'.$regional[0]['dep_departamento'].'_'.$this->gestion.'';

    $this->load->view('admin/evaluacion/evaluacion_oregional/reporte_eval_form2', $data);
  }



























































    /*-------- GET CUADRO EVALUACION --------*/
    public function get_cuadro_evaluacion(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $departamento=$this->model_proyecto->get_departamento($dep_id);
        $tabla='';
        if(count($departamento)!=0){
          $tabla='<hr><iframe id="ipdf" width="100%" height="850px;" src="'.base_url().'index.php/eval_obj/rep_meta_oregional_grafico/'.$dep_id.'"></iframe>';
          $result = array(
            'respuesta' => 'correcto',
            'regional'=>$departamento,
            'nro'=>count($this->model_objetivogestion->get_list_ogestion_por_regional($dep_id)),
            'tabla'=>$tabla,
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

    /*----- CUADRO EVALUACION METAS REGIONALES  ----*/
    public function cuadro_evaluacion_grafico($dep_id){
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      $data['trimestre']=$this->model_evaluacion->trimestre();
      $data['nro']=count($this->model_objetivogestion->get_list_ogestion_por_regional($dep_id));
      $data['eval']=$this->tabla_evaluacion_meta($dep_id);
      $data['print_evaluacion']=$this->print_evaluacion_objetivos($data['nro'],$data['eval']);
     
      //echo $data['print_evaluacion'];
      $this->load->view('admin/evaluacion/objetivo_regional/reporte_grafico_meta_oregion', $data);
    }

    /*--- Imprimir Evaluación Objetivos Regionales ---*/
    public function print_evaluacion_objetivos($nro,$eval){
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);

      ?>
       <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
        <?php
        $tabla='';
        $tabla .='
        <div class="verde"></div>
        <div class="blanco"></div>
          <table class="page_header" border="0" style="width: 100%;">
            <tr>
              <td style="width: 100%; text-align: left">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.3%;">
                      <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                        <td width=20%; text-align:center;"">
                          <img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'"  alt="" style="width:35%;">
                        </td>
                        <td width=60%; align=center>
                          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 17pt;" align="center"><b>'.$this->session->userdata('entidad').'</b></td>
                            </tr>
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">PLAN OPERATIVO ANUAL - '.$this->session->userdata('gestion').'</td>
                            </tr>
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 13pt;" align="center"><b>EVALUACI&Oacute;N DE OPERACIONES </b></td>
                            </tr>
                          </table>
                        </td>
                        <td width=20%; align=left style="font-size: 8px;">
                              &nbsp; <b style="font-size: 4.5pt;">EVAL. FORMULARIO POA N° 2<br>
                              &nbsp; '.$trimestre[0]['trm_descripcion'].'</b>
                        </td>
                      </tr>
                </table>
              </td>
            </tr>
          </table><hr>

          <table class="change_order_items" border=0 style="width:100%;">
            <tr>
              <td>
                <div id="container_print" style="width: 600px; height: 500px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td>
                <hr>
                <ul>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<li style="font-size: 8px;">'.$eval[$i][3].'.- '.$eval[$i][5].'</li>';
                }
                $tabla.='
                </ul>
              </td>
            </tr>
            <tr>
              <td><hr>
                <table class="change_order_items" border=1 align=center style="width:100%;">
                    <thead>
                      <tr align=center bgcolor="#f1eeee">
                        <th></th>';
                        for ($i=1; $i <=$nro ; $i++) { 
                          $tabla.='<th>'.$eval[$i][3].'</th>';
                        }
                        $tabla.='
                        </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td align=left><b>META</b></td>';
                        for ($i=1; $i <=$nro ; $i++) { 
                          $tabla.='<td align=right>'.$eval[$i][8].'</td>';
                        }
                        $tabla.='
                      </tr>
                      <tr>
                        <td align=left><b>EVAL.</b></td>';
                        for ($i=1; $i <=$nro ; $i++) { 
                          $tabla.='<td align=right>'.$eval[$i][9].'</td>';
                        }
                        $tabla.='
                      </tr>
                      <tr>
                        <td align=left><b>% EFI.</b></td>';
                        for ($i=1; $i <=$nro ; $i++) { 
                          $tabla.='<td align=right>'.$eval[$i][10].'</td>';
                        }
                        $tabla.='
                      </tr>
                    </tbody>
                </table>
              </td>
            </tr>
          </table>';
        ?>
      </html>
      <?php
      return $tabla;
    }

    /*--- Tabla Evaluacion Meta ---*/
    public function tabla_evaluacion_meta($dep_id){
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      $nro=0;
      foreach($lista_ogestion as $row){
        $evaluado=$this->model_evaluacion->get_meta_oregional($row['pog_id'],$this->tmes);
        $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
        $nro++;
        $tab[$nro][1]=$row['pog_id'];
        $tab[$nro][2]=$row['acc_codigo'];
        $tab[$nro][3]='OPE '.$row['og_codigo'];
        $tab[$nro][4]=$row['or_codigo'];
        $tab[$nro][5]=$row['or_objetivo'];
        $tab[$nro][6]=$row['or_resultado'];
        $tab[$nro][7]=$row['or_indicador'];
        $tab[$nro][8]=round($row['or_meta'],2);

        $tab[$nro][9]=round($suma_mevaluado,2);
        $tab[$nro][10]=round((($suma_mevaluado/$row['or_meta'])*100),2);

        if(count($evaluado)!=0){
          $tab[$nro][11]=$evaluado[0]['tpeval_descripcion'];
          $tab[$nro][12]=$evaluado[0]['tmed_verif'];
          $tab[$nro][13]=$evaluado[0]['tprob'];
          $tab[$nro][14]=$evaluado[0]['tacciones'];
        }
        else{
          $tab[$nro][11]='';
          $tab[$nro][12]='';
          $tab[$nro][13]='';
          $tab[$nro][14]='';
        }
      }

      return $tab;
    }


    /*--- Valida Evaluacion Objetivos (2020) ---*/
    public function valida_evalmeta(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $tmes=$this->model_evaluacion->trimestre();
        $pog_id = $this->security->xss_clean($post['pog_id']); /// id meta regional
        $tp_eval = $this->security->xss_clean($post['tp']); /// Tipo de evaluacion 1: Cumplido, 2: En Proceso
        $ejec_meta = $this->security->xss_clean($post['ejec_meta']); /// Valor ejecutado

        if($tp_eval==1){
          $medio_verificacion=$this->security->xss_clean($post['mverif']);
          $problemas='';
          $acciones='';
        }
        else{
          $medio_verificacion=$this->security->xss_clean($post['mverif']);
          $problemas=$this->security->xss_clean($post['prob']);
          $acciones=$this->security->xss_clean($post['acc']);
        }

        $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($pog_id); /// Meta Regional
       
        $data = array(
          'pog_id' => $pog_id,
          'ejec_fis' => $ejec_meta, 
          'trm_id' => $this->tmes,
          'tp_eval' => $tp_eval, 
          'tmed_verif' => strtoupper($medio_verificacion),
          'tprob' => strtoupper($problemas),
          'tacciones' => strtoupper($acciones),
        );
        $this->db->insert('objetivo_programado_gestion_evaluado',$data);
        $epog_id=$this->db->insert_id();


        if(count($this->model_evaluacion->get_meta_oregional($pog_id,$this->tmes))!=0){
          $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA EVALUACIÓN');
        }
        else{
          $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DE LA EVALUACIÓN');
        }

        redirect(site_url("").'/eval_obj/objetivos_regionales');

      } else {
          show_404();
      }
    }

    /*--- Valida Modificación Evaluacion Objetivos (2020) ---*/
    public function valida_update_evalmeta(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $tmes=$this->model_evaluacion->trimestre();
          $epog_id = $this->security->xss_clean($post['epog_id']); /// id meta regional
          $tp_eval = $this->security->xss_clean($post['mtp']); /// Tipo de evaluacion 1: Cumplido, 2: En Proceso
          $ejec_meta = $this->security->xss_clean($post['mejec_meta']); /// Valor ejecutado

          $meta_evaluado=$this->model_evaluacion->get_evaluacion_meta_oregional($epog_id);

          if($tp_eval==1){
            $medio_verificacion=$this->security->xss_clean($post['mmverif']);
            $problemas='';
            $acciones='';
          }
          else{
            $medio_verificacion=$this->security->xss_clean($post['mmverif']);
            $problemas=$this->security->xss_clean($post['mprob']);
            $acciones=$this->security->xss_clean($post['macc']);
          }


          $this->db->where('epog_id', $epog_id);
          $this->db->delete('objetivo_programado_gestion_evaluado');

          $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($meta_evaluado[0]['pog_id']);
         
          $data = array(
            'pog_id' => $meta_evaluado[0]['pog_id'],
            'ejec_fis' => $ejec_meta, 
            'trm_id' => $this->tmes,
            'tp_eval' => $tp_eval, 
            'tmed_verif' => strtoupper($medio_verificacion),
            'tprob' => strtoupper($problemas),
            'tacciones' => strtoupper($problemas),
          );
          $this->db->insert('objetivo_programado_gestion_evaluado',$data);
          $epog_id=$this->db->insert_id();


          if(count($this->model_evaluacion->get_meta_oregional($meta_evaluado[0]['pog_id'],$this->tmes))!=0){
            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA EVALUACIÓN');
          }
          else{
            $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DE LA EVALUACIÓN');
          }

          redirect(site_url("").'/eval_obj/objetivos_regionales');

      } else {
          show_404();
      }
    }

    /*------- GET OBJETIVO REGIONAL -------*/
    public function get_objetivo_regional(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $pog_id = $this->security->xss_clean($post['pog_id']);
          
          $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($pog_id);
          if(count($meta_regional)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'meta_regional' => $meta_regional, /// Datos Meta Regional
              'trimestre' => $this->model_evaluacion->trimestre(), /// Datos Trimestre
              'evaluado' => $this->get_suma_evaluado($pog_id,$this->tmes), /// Valor Evaluado 
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

    /*--- GET SUMA EVALUADO ANTES DEL TRIMESTRE ACTUAL ---*/
    public function get_suma_evaluado($pog_id,$trimestre){
      $sum=0;
      for ($i=1; $i <$trimestre ; $i++) { 
        $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
        if(count($obj_gestion_evaluado)!=0){
          $sum=$sum+$obj_gestion_evaluado[0]['ejec_fis'];
        }
      }

      return $sum;
    }


    /*------- GET UPDATE OBJETIVO REGIONAL -------*/
    public function get_update_objetivo_regional(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $epog_id = $this->security->xss_clean($post['epog_id']); /// id evaluacion regional
          
          $meta_evaluado=$this->model_evaluacion->get_evaluacion_meta_oregional($epog_id);
          $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($meta_evaluado[0]['pog_id']);
          
          if(count($meta_evaluado)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'meta_regional' => $meta_regional, /// Datos Meta Regional
              'trimestre' => $meta_evaluado[0]['trm_id'], /// Datos Trimestre
              'datos_meta_evaluado' => $meta_evaluado, /// Datos evaluado Trimestre
              'total_evaluado' => $this->get_suma_evaluado($meta_evaluado[0]['pog_id'],$meta_evaluado[0]['trm_id']), /// Valor Evaluado 
              'total_evaluado' => ($this->get_suma_evaluado($meta_evaluado[0]['pog_id'],$meta_evaluado[0]['trm_id'])+$meta_evaluado[0]['ejec_fis']), /// Valor Evaluado 

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


    /*--- GET SUMA TOTAL EVALUADO ---*/
    public function get_suma_total_evaluado($pog_id){
      $sum=0;
      for ($i=1; $i <=$this->tmes; $i++) { 
        $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
        if(count($obj_gestion_evaluado)!=0){
          $sum=$sum+$obj_gestion_evaluado[0]['ejec_fis'];
        }
      }

      return $sum;
    }


     //// REPORTE EVALUACION META OBJETIVO REGIONAL
    public function reporte_meta_oregional($dep_id){
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

      $tabla='';
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);

      $tabla.='
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
        <thead>
        <tr style="font-size: 7px;" bgcolor="#1c7368" align=center>
          <th style="width:1%;height:20px;color:#FFF;">N°</th>
          <th style="width:2.5%;color:#FFF;"><b>COD. ACE.</b></th>
          <th style="width:2.5%;color:#FFF;"><b>COD. ACP.</b></th>
          <th style="width:2.5%;color:#FFF;"><b>COD. OPE.</b></th>
          <th style="width:15%;color:#FFF;">OPERACI&Oacute;N</th>
          <th style="width:15%;color:#FFF;">RESULTADO</th>
          <th style="width:15%;color:#FFF;">INDICADOR</th>
          <th style="width:5%;color:#FFF;">META/EVAL</th>
          <th style="width:5%;color:#FFF;">EFICACIA</th>
          <th style="width:4%;color:#FFF;"></th>
          <th style="width:10%;color:#FFF;">MEDIO DE VERIFICACI&Oacute;N</th>
          <th style="width:10%;color:#FFF;">PROBLEMAS PRESENTADOS</th>
          <th style="width:10%;color:#FFF;">ACCIONES REALIZADAS</th>
        </tr>
        </thead>
        <tbody>';
      $nro=0;
      foreach($lista_ogestion as $row){
        $evaluado=$this->model_evaluacion->get_meta_oregional($row['pog_id'],$this->tmes);
        $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
        $nro++;
        $tabla.='
        <tr style="font-size: 7px;">
          <td style="width:1%; height:15px;" align=center>'.$nro.'</td>
          <td style="width:2.5%;" align="center">'.$row['acc_codigo'].'</td>
          <td style="width:2.5%;" align="center">'.$row['og_codigo'].'</td>
          <td style="width:2.5%; font-size: 8px;" align="center" bgcolor="#f1eeee"><b>'.$row['or_codigo'].'</b></td>
          <td style="width:15%;">'.$row['or_objetivo'].'</td>
          <td style="width:15%;">'.$row['or_resultado'].'</td>
          <td style="width:15%;">'.$row['or_indicador'].'</td>';
          if(count($evaluado)!=0){
            $tabla.='
            <td style="width:4%; font-size: 8px;" align=center><b>'.round($row['or_meta'],2).' / '.$suma_mevaluado.'</b></td>
            <td style="width:4%; font-size: 8px;" align=right><b>'.round((($suma_mevaluado/$row['or_meta'])*100),2).'%</b></td>
            <td style="width:5%; font-size: 5px;" align=center>'.$evaluado[0]['tpeval_descripcion'].'</td>
            <td style="width:10%;" bgcolor="#dfefe4">'.$evaluado[0]['tmed_verif'].'</td>
            <td style="width:10%;" bgcolor="#dfefe4">'.$evaluado[0]['tprob'].'</td>
            <td style="width:10%;" bgcolor="#dfefe4">'.$evaluado[0]['tacciones'].'</td>';
          }
          else{
            $tabla.='
            <td style="width:4%; font-size: 8px;" align=center ><b>'.round($row['or_meta'],2).' / '.$suma_mevaluado.'</b></td>
            <td style="width:4%; font-size: 8px;" align=right><b>'.round((($suma_mevaluado/$row['or_meta'])*100),2).'%</b></td>';
              if ($suma_mevaluado==$row['or_meta']) {
                $get_ultimo=$this->model_evaluacion->get_ultimo_eval_oregional($row['pog_id']);
                $tabla.='<td style="width:5%; font-size: 5px;" align=center>'.$get_ultimo[0]['tpeval_descripcion'].'</td>
                <td style="width:10%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tmed_verif'].'</td>
                <td style="width:10%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tprob'].'</td>
                <td style="width:10%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tacciones'].'</td>';
              }
              else{
                $tabla.=' <td style="width:4%;"></td>
                <td style="width:10%;" bgcolor="#dfefe4"></td>
                <td style="width:10%;" bgcolor="#dfefe4"></td>
                <td style="width:10%;" bgcolor="#dfefe4"></td>';
              }
          }
          $tabla.='
        </tr>';
      }
      $tabla.='
        </tbody>
      </table>';
      
      $data['oregional']=$tabla;
      $this->load->view('admin/evaluacion/objetivo_regional/reporte_meta_oregion', $data);
    }


}