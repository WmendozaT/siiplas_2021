<?php
class Crep_evalobjetivos extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->load->model('mestrategico/model_objetivogestion');
            $this->load->model('mestrategico/model_objetivoregion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
        }
        else{
            redirect('/','refresh');
        }
    }

    /// MENU EVALUACIÓN POA 
    public function menu_eval_objetivos(){
      if($this->gestion>2019){
        $data['menu']=$this->menu(7); //// genera menu
        $data['regional']=$this->regionales();
      //  $eval=$this->tabla_evaluacion_meta_institucional();
        $this->load->view('admin/reportes_cns/repevaluacion_objetivos/rep_menu', $data);
      }
      else{
        redirect('regionales'); // Rediccionando a Evaluacion anterior 2019
      }
    }


    //// LISTA DE REGIONALES
    public function regionales(){
      $regiones=$this->model_evalinstitucional->regiones();
      $nro=0;
      $tabla ='';
      $tabla.='
          <article class="col-sm-12 col-md-12 col-lg-2">
            <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                    <h2>Accordions </h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">

                        <div class="panel-group smart-accordion-default" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></a></h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body no-padding"><br>
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size: 10pt;">INSTITUCIONAL</td>
                                                    <td align=center><a href="#" class="btn btn-info enlace" name="0">VER</a></td>
                                                </tr>
                                            </tbody>
                                        </table><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
              <header>
                <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                <h2><b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></h2>
              </header>
              <div>

                <div class="widget-body no-padding">
                  <div class="panel-group smart-accordion-default" id="accordion-2">';
                
                  foreach($regiones as $rowd){
                    $departamento=$this->model_proyecto->get_departamento($rowd['dep_id']);
                    $tabla.='
                    <div class="panel panel-default">
                      <div class="panel-heading">';
                      if($rowd['dep_id']!=10){
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> REGIONAL '.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }
                      else{
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>'.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }
                      $tabla.='
                      </div>
                      <div id="collapse'.$rowd['dep_id'].'" class="panel-collapse collapse">
                        <div class="panel-body">
                          <hr><table class="table table-bordered">
                          <tr>
                            <td>'.$nro.'</td>
                            <td><b>CONSOLIDADO - '.strtoupper($departamento[0]['dep_departamento']).'</b></td>
                            <td align=center><a href="#" class="btn btn-info enlace" name="'.$departamento[0]['dep_id'].'">VER</a></td>
                          </tr>
                          </table>
                        </div>
                      </div>
                    </div>';
                  }
                $tabla.='
                  </div>
      
                </div>
              </div>
            </div>
          </article>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div id="content1"></div>
          </article>';
      return $tabla;
    }

    /*-------- GET CUADRO EVALUACION REGIONALES OBJETIVOS--------*/
    public function get_cuadro_evaluacion_objetivos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $id = $this->security->xss_clean($post['id']); // dep id, dist id , 0: Nacional
        
        $tabla='<iframe id="ipdf" width="100%" height="1000px;" src="'.base_url().'index.php/rep_eval_obj/evaluacion_objetivos/'.$id.'"></iframe>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }

    //// EVALUACIÓN OBJETIVOS - REGIONAL -DISTRITAL  - IFRAME
    public function evaluacion_objetivos($id){
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $dep_id=$id;
      if($id!=0){ //// REGIONAL
        $data['regional']=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N OBJETIVOS -  REGIONAL '.strtoupper($data['regional'][0]['dep_departamento']).'</b></h1>
        <h2><b>EVALUACI&Oacute;N OBJETIVOS AL '.$data['trimestre'][0]['trm_descripcion'].'</b></h2>';

        /*------- Primer cuadro ------- */
        $data['tipo_regional']='REGIONAL : '.strtoupper($data['regional'][0]['dep_departamento']);
        $data['nro']=count($this->model_objetivogestion->get_list_ogestion_por_regional($dep_id));
        $data['eval']=$this->tabla_evaluacion_meta($dep_id);
      }
      else{ ///// INSTITUCIONAL
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N OBJETIVOS - INSTITUCIONAL</b></h1>
        <h2><b>EVALUACI&Oacute;N OBJETIVOS AL '.$data['trimestre'][0]['trm_descripcion'].'</b></h2>';
        $data['tipo_regional']='INSTITUCIONAL';
        $data['nro']=count($this->model_objetivogestion->list_objetivosgestion_general());
        $data['eval']=$this->tabla_evaluacion_meta_institucional();
      }
      
      $data['detalle']=$this->detalle_objetivos($data['eval'],$data['nro'],1);
      $data['print_objetivos']=$this->print_evaluacion_objetivos($data['nro'],$data['eval']);
      
    
      /*-------Segundo Cuadro cuadro ------- */
      $data['matriz']=$this->matriz_gcumplimiento($data['eval'],$data['nro']); /// matriz grado de cumplimiento
      $data['tabla_pastel']=$this->tabla_gcumplimiento($data['matriz'],1,1);
      $data['tabla_pastel_todo']=$this->tabla_gcumplimiento($data['matriz'],2,1);

      $data['print_gcumplimiento']=$this->print_gcumplimiento($this->tabla_gcumplimiento($data['matriz'],1,2),$this->tabla_gcumplimiento($data['matriz'],2,2));

      $this->load->view('admin/reportes_cns/repevaluacion_objetivos/reporte_grafico_eval_consolidado_regional_objetivos', $data);
    }


    ///==================== PRIMER CUADRO
    /*--- Tabla Evaluacion Meta a nivel Institucional ---*/
    public function tabla_evaluacion_meta_institucional(){
      $lista_ogestion=$this->model_objetivogestion->list_objetivosgestion_general();
      $nro=0;
      foreach($lista_ogestion as $row){
        $suma_mevaluado=$this->get_suma_total_evaluado_institucional($row['og_id']);
        $nro++;
        $tab[$nro][1]=$row['og_id'];
        $tab[$nro][2]=$row['acc_codigo'];
        $tab[$nro][3]='OPE '.$row['og_codigo'];
        $tab[$nro][4]=$row['og_codigo'];
        $tab[$nro][5]=$row['og_objetivo'];
        $tab[$nro][6]=$row['og_resultado'];
        $tab[$nro][7]=$row['og_indicador'];
        $tab[$nro][8]=round($row['og_meta'],2);

        
        if($row['indi_id']==1){
          $tab[$nro][9]=round($suma_mevaluado,2);
          $tab[$nro][10]=round((($suma_mevaluado/$row['og_meta'])*100),2);
        }
        else{
          $sum_prog=$this->model_objetivogestion->get_suma_temporalidad_ogestion($row['og_id']);
          $tab[$nro][10]=round((($suma_mevaluado/$sum_prog[0]['meta_relativo'])*100),2);
          $tab[$nro][9]=round($tab[$nro][10],2);
        }
        
      }

      return $tab;
    }

    /*--- GET SUMA TOTAL EVALUADO INSTITUCIONAL ---*/
    public function get_suma_total_evaluado_institucional($og_id){
      $sum=0;
        for ($i=1; $i <=$this->tmes; $i++) { 
          $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral_institucional($i,$og_id);
          if(count($obj_gestion_evaluado)!=0){
            $sum=$sum+$obj_gestion_evaluado[0]['evaluado'];
          }
        }

      return $sum;
    }


    /*--- Tabla Evaluacion Meta Regional---*/
    public function tabla_evaluacion_meta($dep_id){
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      $nro=0;
      foreach($lista_ogestion as $row){
        $evaluado=$this->model_evaluacion->get_meta_oregional($row['pog_id'],$this->tmes);
        $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
        $nro++;
        $tab[$nro][1]=$row['pog_id'];
        $tab[$nro][2]=$row['acc_codigo'];
        $tab[$nro][3]='OPE '.$row['og_codigo'].'.'.$row['or_codigo'].'.';
        $tab[$nro][4]=$row['or_codigo'];
        $tab[$nro][5]=$row['or_objetivo'];
        $tab[$nro][6]=$row['or_resultado'];
        $tab[$nro][7]=$row['or_indicador'];
        $tab[$nro][8]=round($row['or_meta'],2);

        $tab[$nro][9]=round($suma_mevaluado,2);
        $tab[$nro][10]=0;

        if($row['or_meta']!=0){
          $tab[$nro][10]=round((($suma_mevaluado/$row['or_meta'])*100),2);
        }
        

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

    /*--- DETALLE OBJETIVOS REGIONALES ---*/
    public function detalle_objetivos($eval,$nro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// normal
        $font_size='style="font-size: 9px;"';
        $tab='class="table table-bordered" align=center style="width:90%;"';
      }
      else{ /// impresion
        $font_size='style="font-size: 8px;"';
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }

      $tabla.='
          <table>
            <tr>
              <td>
                <hr>
                <ul>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<li '.$font_size.'>'.$eval[$i][3].'.- '.$eval[$i][5].'</li>';
                }
                $tabla.='
                </ul>
              </td>
            </tr>
            <tr>
              <td><hr>
                <table '.$tab.'>
                    <thead>
                      <tr align=center bgcolor="#f1eeee">
                        <th></th>';
                        for ($i=1; $i <=$nro ; $i++) { 
                          $tabla.='<th><b>'.$eval[$i][3].'</b></th>';
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

      return $tabla;
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
                              <td style="width:100%; height: 1.2%; font-size: 13pt;" align="center"><b>EVALUACI&Oacute;N DE OBJETIVOS REGIONALES </b></td>
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
    ///==================================

    /// ============== SEGUNDO CUADRO
    /*--- Matriz Grado de cumplimiento ---*/
    public function matriz_gcumplimiento($objetivos,$nro){
      $cumplido=0;$proceso=0;$ncumplido=0;
      for ($i=1; $i <=$nro ; $i++) {
        if($objetivos[$i][8]==$objetivos[$i][9]){
          $cumplido++;
        }
        elseif(($objetivos[$i][9]<$objetivos[$i][8]) & $objetivos[$i][9]!=0){
          $proceso++;
        }
        elseif ($objetivos[$i][9]==0) {
          $ncumplido++;
        }
      }

      $matriz[1]=$nro;
      $matriz[2]=$cumplido;
      $matriz[3]=$proceso;
      $matriz[4]=$ncumplido;
      $matriz[5]=round((($cumplido/$nro)*100),2); // % cumplidos
      $matriz[6]=round((($proceso/$nro)*100),2); // % proceso
      $matriz[7]=round((($ncumplido/$nro)*100),2); // % no cumplido

      return $matriz;
    }

    /*--- Tabla cuadro de evaluacion ---*/
    public function tabla_gcumplimiento($matriz,$tp_cuadro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

      if($tp_cuadro==1){ /// Cuadro cumplido,no cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>TOTAL PROGRAMADAS</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.($matriz[3]+$matriz[4]).'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }
      else{ /// cuadro completo
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>TOTAL PROGRAMADAS</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>EN PROCESO</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.$matriz[3].'</b></td>
                <td><b>'.$matriz[4].'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }

     /*--- Imprimir Evaluación Objetivos Regionales ---*/
    public function print_gcumplimiento($tabla_pastel,$tabla_pastel_todo){
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
                              <td style="width:100%; height: 1.2%; font-size: 13pt;" align="center"><b>EVALUACI&Oacute;N DE OBJETIVOS REGIONALES </b></td>
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

          <table class="change_order_items" border=1>
            <tr>
              <td>
                <div id="pastel_print" style="width: 550px; height: 300px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td>
                '.$tabla_pastel.'
              </td>
            </tr>
          </table>

          <table class="change_order_items" border=1>
            <tr>
              <td>
                <div id="pastel_todos_print" style="width: 550px; height: 300px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td>
                '.$tabla_pastel_todo.'
              </td>
            </tr>
          </table>';
        ?>
      </html>
      <?php
      return $tabla;
    }
    /// =============================



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

    /*
    /*================================= GENERAR MENU ====================================*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
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
    /*--------------------------------------------------------------------------------*/
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
    /*======================================================================================*/

}