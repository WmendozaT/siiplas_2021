<?php
class Cevaluacion_pei extends CI_Controller {
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

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*-- Mis Objetivos Regionales (Operaciones) --*/
    public function objetivos_regionales(){
      if($this->gestion>2021){
          redirect(site_url("").'/eval_oregionales');
      }
      else{ ///// 2020-2021
        $data['menu']=$this->menu(4); //// genera menu
        $data['departamento']=$this->model_proyecto->get_departamento($this->dep_id);
        $data['trimestre']=$this->model_evaluacion->trimestre();
        if($this->tp_adm==1){
          $data['tabla']=$this->regionales();
        }
        else{
          $data['tabla']=$this->ver_relacion_ogestion($this->dep_id);
        }

        $this->load->view('admin/evaluacion/objetivo_regional/objetivos_regionales', $data);
      }
    }


    /*-------- LISTA DE REGIONALES ----------*/
    public function regionales(){
      $regionales=$this->model_proyecto->list_departamentos();
      $tabla='
          <div>
            <div id="tabs">
              <ul>
                <li>
                  <a href="#tabs-1" style="width:100%;">CHUQUISACA</a>
                </li>
                <li>
                  <a href="#tabs-2">LA PAZ</a>
                </li>
                <li>
                  <a href="#tabs-3">COCHABAMBA</a>
                </li>
                <li>
                  <a href="#tabs-4">ORURO</a>
                </li>
                <li>
                  <a href="#tabs-5">POTOSI</a>
                </li>
                <li>
                  <a href="#tabs-6">TARIJA</a>
                </li>
                <li>
                  <a href="#tabs-7">SANTA CRUZ</a>
                </li>
                <li>
                  <a href="#tabs-8">BENI</a>
                </li>
                <li>
                  <a href="#tabs-9">PANDO</a>
                </li>
                <li>
                  <a href="#tabs-10">OFICINA NACIONAL</a>
                </li>
              </ul>';
              for ($i=1; $i <=10 ; $i++) { 
                $tabla.='
                <div id="tabs-'.$i.'">
                  <div class="row">
                    '.$this->ver_relacion_ogestion($i).'
                  </div>
                </div>';
              }
              $tabla.='
            </div>
          </div>';

      return $tabla;
    }

    //// REGIONAL ALINEADO A OBJETIVOS REGIONALES 2020-2021
    public function ver_relacion_ogestion($dep_id){
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      $tabla.='
      <div align="right">
        <a href="javascript:abreVentana(\''.site_url("").'/eval_obj/rep_meta_oregional/'.$dep_id.'\');" title="REPORTE EVALUACIÓN META REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>EVALUACI&Oacute;N METAS REGIONALES (.PDF)</b></a>&nbsp;&nbsp;
        <a href="#" data-toggle="modal" data-target="#modal_evaluacion" name="'.$dep_id.'" class="btn btn-default evaluacion" title="MOSTRAR CUADRO DE EVALUACIÓN DE METAS"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>CUADRO DE EVALUACI&Oacute;N (GRAFICO)</b></a>
      </div><br>
      <table class="table table-bordered" border=0.2 style="width:100%;" align=center>
        <thead>
        <tr style="font-size: 11px;" align=center>
          <th style="width:1%;height:10px;color:#FFF;" bgcolor="#1c7368">N° '.$this->conf_estado.'</th>
          <th style="width:2%;color:#FFF;" bgcolor="#1c7368"><b>COD. ACE.</b></th>
          <th style="width:2%;color:#FFF;" bgcolor="#1c7368"><b>COD. ACP.</b></th>
          <th style="width:2%;color:#FFF;" bgcolor="#1c7368"><b>COD. OPE.</b></th>
          <th style="width:11%;color:#FFF;" bgcolor="#1c7368">OPERACI&Oacute;N</th>
          <th style="width:11%;color:#FFF;" bgcolor="#1c7368">RESULTADO</th>
          <th style="width:10%;color:#FFF;" bgcolor="#1c7368">INDICADOR</th>
          <th style="width:10%;color:#FFF;" bgcolor="#1c7368">MEDIO VERIFICACI&Oacute;N</th>
          <th style="width:4%;color:#FFF;" bgcolor="#1c7368">META</th>
          <th style="width:4%;color:#FFF;" bgcolor="#1c7368">EVALUADO</th>
          <th style="width:4%;color:#FFF;" bgcolor="#1c7368">%EFICACIA</th>
          <th style="width:4%;color:#FFF;" bgcolor="#1c7368"></th>
          <th style="width:15%;color:#FFF;" bgcolor="#1c7368">MEDIO DE VERIFICACI&Oacute;N</th>
          <th style="width:15%;color:#FFF;" bgcolor="#1c7368">PROBLEMAS</th>
          <th style="width:15%;color:#FFF;" bgcolor="#1c7368">ACCIONES</th>
          <th style="width:3%;color:#FFF;" bgcolor="#1c7368">EVALUAR</th>
        </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($lista_ogestion as $row){
          $evaluado=$this->model_evaluacion->get_meta_oregional($row['pog_id'],$this->tmes);
          $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
          $color='';

          if(count($evaluado)!=0){
            $color='#eef5f0';
          }

          $nro++;
          $tabla.='
          <tr style="font-size: 10px;" bgcolor='.$color.'>
            <td style="width:1%; height:10px;" align=center title='.$row['pog_id'].'>'.$nro.'</td>
            <td style="width:2%;" align="center">'.$row['acc_codigo'].'</td>
            <td style="width:2%;" align="center">'.$row['og_codigo'].'</td>
            <td style="width:2%; font-size: 11px;" align="center" bgcolor="#f1eeee"><b>'.$row['or_codigo'].'</b></td>
            <td style="width:11%;">'.$row['or_objetivo'].'</td>
            <td style="width:11%;">'.$row['or_resultado'].'</td>
            <td style="width:10%;">'.$row['or_indicador'].'</td>
            <td style="width:10%;">'.$row['or_verificacion'].'</td>
            <td style="width:4%; font-size: 11px;" align=center><b>'.round($row['or_meta'],2).'</b></td>';
            
            if(count($evaluado)!=0){
                $but='btn btn-default';
                if($evaluado[0]['tpeval_id']==1){
                  $but='btn btn-success';
                }
              $tabla.='
              <td style="width:4%; font-size: 11px;" align=center><b>'.round($suma_mevaluado,2).'</b></td>
              <td style="width:4%; font-size: 11px;" align=right bgcolor="#dfefe4"><b>'.round((($suma_mevaluado/$row['or_meta'])*100),2).'%</b></td>
              <td style="width:4%; font-size: 5px;" bgcolor="#dfefe4" align=center>
                <button type="button" style="font-size: 10px;" class="'.$but.'"><b>'.$evaluado[0]['tpeval_descripcion'].'</b></button>
              </td>
              <td style="width:15%;" bgcolor="#dfefe4">'.$evaluado[0]['tmed_verif'].'</td>
              <td style="width:15%;" bgcolor="#dfefe4">'.$evaluado[0]['tprob'].'</td>
              <td style="width:15%;" bgcolor="#dfefe4">'.$evaluado[0]['tacciones'].'</td>
              <td style="width:3%;" align=center>';
              if($this->conf_estado==1){ /// Habilitado
                if($suma_mevaluado<round($row['or_meta'],2) || $this->tp_adm==1) {
                  $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N META OPERACIÓN" name="'.$evaluado[0]['epog_id'].'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV.OPE.</a>';
                }
                else{
                  $tabla.='<b>EVALUADO</b>';
                }
              }
              $tabla.='
              </td>';
            }
            else{

              $tabla.='
              <td style="width:4%; font-size: 11px;" align=center><b>'.round($suma_mevaluado,2).'</b></td>
              <td style="width:4%; font-size: 11px;" align=right bgcolor="#dfefe4"><b>'.round((($suma_mevaluado/$row['or_meta'])*100),2).'%</b></td>';
              if($suma_mevaluado==$row['or_meta']){
                $get_ultimo=$this->model_evaluacion->get_ultimo_eval_oregional($row['pog_id']);
                $tabla.='
                <td style="width:4%;" bgcolor="#dfefe4" align=center> 
                  <button type="button" style="font-size: 10px;" class="btn btn-success"><b>'.$get_ultimo[0]['tpeval_descripcion'].'</b></button>
                </td>
                <td style="width:15%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tmed_verif'].'</td>
                <td style="width:15%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tprob'].'</td>
                <td style="width:15%;" bgcolor="#dfefe4">'.$get_ultimo[0]['tacciones'].'</td>
                <td style="width:3%;" align=center>';
                  if($this->conf_estado==1 || $this->tp_adm==1){
                    $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N META OPERACIÓN" name="'.$get_ultimo[0]['epog_id'].'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV.OPE.</a>';
                  }
                  else{
                    $tabla.='<b>EVALUADO</b>';
                  }
                $tabla.='
                </td>';
              }
              else{
                $tabla.='
                <td style="width:4%;" bgcolor="#dfefe4"></td>
                <td style="width:15%;" bgcolor="#dfefe4"></td>
                <td style="width:15%;" bgcolor="#dfefe4"></td>
                <td style="width:15%;" bgcolor="#dfefe4"></td>
                <td style="width:3%;" align=center>';
                if($this->conf_estado==1 || $this->tp_adm==1){
                  $tabla.='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR META OPERACIÓN" name="'.$row['pog_id'].'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. OPE.</a>';
                }
                else{
                  $tabla.='<b>EVALUADO</b>';
                }
                $tabla.='
                </td>';
              }
            }
            $tabla.='
          </tr>';
        }
        $tabla.='
        </tbody>
      </table> ';

      return $tabla;
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

    /*--- Valida Modificación Evaluacion Objetivos (2020-2021) ---*/
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


    /*--- Valida Modificación Evaluacion Objetivos de Gestion (2022) ---*/
    public function valida_update_evaluacion_acp(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $tmes=$this->model_evaluacion->trimestre();
        $tipo = $this->security->xss_clean($post['tp']); /// tipo
        $pog_id = $this->security->xss_clean($post['pog_id']); /// id meta regional
        $ejec_meta = $this->security->xss_clean($post['ejec']); /// meta ejecutado
        $mverificacion = $this->security->xss_clean($post['mverificacion']); /// medio de verificacion

        $this->db->where('pog_id', $pog_id);
        $this->db->where('trm_id', $this->tmes);
        $this->db->delete('objetivo_programado_gestion_evaluado');

        $suma_ejec=$this->get_suma_evaluado($pog_id,$this->tmes); ///suma de ejecucion registrado al trimestre anterior
        $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($pog_id); /// Meta Regional

        $tp_eval=0;
        if($meta_regional[0]['prog_fis']==($suma_ejec+$ejec_meta)){
          $tp_eval=1;
        }


        //// ------ insert evaluado
        $data = array(
          'pog_id' => $pog_id,
          'ejec_fis' => $ejec_meta, 
          'trm_id' => $this->tmes,
          'tp_eval' => $tp_eval, 
          'tmed_verif' => strtoupper($mverificacion),
        );
        $this->db->insert('objetivo_programado_gestion_evaluado',$data);
        $epog_id=$this->db->insert_id();
        //// --------- End

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
        $obj_gestion_evaluado=$this->model_evaluacion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
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

    /*-------------------------- MENU -------------------*/
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
    /*=============================================================================================*/
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

    public function get_mes($mes_id){
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

    /*------------------------------------- ROLES DE USUARIOS ------------------------------*/
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
    /*-------------------------------------------------------------------------------------*/

}