<?php
class Crep_evalregional extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('reporte_eval/model_evalnacional');
            $this->load->model('reporte_eval/model_evalregional');
            $this->load->model('mantenimiento/mapertura_programatica');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /*-------- Actualiza Ponderaciones por Cada Unidad ------*/
    public function update_pcion_regional($dep_id){
      $proyectos=$this->model_evalregional->list_consolidado_regional_todos($dep_id);
      foreach($proyectos  as $row){
        $update_proy = array(
          'proy_pcion_reg' => round((100/count($proyectos)),2)
        );
        $this->db->where('proy_id', $row['proy_id']);
        $this->db->update('_proyectos', $update_proy);
      }
    }

    /*------------------- menu Regional  -------------------*/
    public function menu_regionales(){
      $data['menu']=$this->menu(7); //// genera menu
      $data['regionales']=$this->list_departamento();
      

     $this->load->view('admin/reportes_cns/eval_regional/menu_regional', $data);
    }

    /*------------------- get Regional  -------------------*/
    public function get_regional($dep_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dep']=$this->model_evalregional->get_dpto($dep_id);
      if(count($data['dep'])!=0){
        $data['titulo_reg']='';
        $data['datos']='';
        $data['ubi']='';
        if($data['dep'][0]['dep_id']==10){
          $data['titulo_reg']=$data['dep'][0]['dep_departamento'];
        }
        else{
          $data['titulo_reg']='REGIONAL DEPARTAMENTO DE '.strtoupper($data['dep'][0]['dep_departamento']);
          $data['datos']='<strong>POBLACI&Oacute;N ASEGURADA SEGUN CENSO 2012 : '.$data['dep'][0]['pob_aseg_censo'].'</strong><br/>
          LATITUD : '.$data['dep'][0]['lat'].' - LONGITUD : '.$data['dep'][0]['lng'];
        }
        
        $distritales=$this->model_evalregional->get_distrital($dep_id);
        $tabla='';
        $tabla .='<p><a class="button" href="'.site_url("").'/rep_cregional/'.$data['dep'][0]['dep_id'].'" style="width:40%;" title="REPORTE CONSOLIDADO '.$data['dep'][0]['dep_departamento'].'" target="_new">CONSOLIDADO REGIONAL '.$data['dep'][0]['dep_departamento'].'</a></p>';
        foreach($distritales  as $row){
          $tabla .='<p><a class="button" href="'.site_url("").'/rep_distrital/'.$row['dist_id'].'" style="width:40%;" target="_new" title="REPORTES '.strtoupper($row['dist_distrital']).'">'.strtoupper($row['dist_distrital']).'</a></p>';
        }
        $data['dist']=$tabla;
        $this->load->view('admin/reportes_cns/eval_regional/regional', $data);
      }
      else{
        redirect('admin/dashboard');
      }
      
    }

    /*----------- LISTA DE REGIONALES -------------*/
    public function list_departamento(){
      $tabla='';
      $dep=$this->model_proyecto->list_departamentos();
      foreach($dep  as $row){
        $this->update_pcion_regional($row['dep_id']);
        $distritales=$this->model_evalregional->get_distrital($row['dep_id']);
        if($row['dep_estado']!=0){
          $tabla.='
            <div class="row">
              <div class="col-sm-1">
              </div>
              <div class="col-sm-1">
                <div class="well well-sm" align=center>';
                if($row['dep_id']==5 || $row['dep_id']==6){
                  $tabla.='<img src="'.base_url().'assets/img/mapas/'.$row['dep_id'].'.jpg" style="width:110px; height:130px;" title='.strtoupper($row['dep_departamento']).'/>';
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/img/mapas/'.$row['dep_id'].'.png" style="width:110px; height:130px;" title='.strtoupper($row['dep_departamento']).'/>';
                }
                $tabla.='
                </div>
              </div>

              <div class="col-sm-9">
                <div class="well well-sm">
                  <h3 class="text-primary">'.strtoupper($row['dep_departamento']).'</h3>
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td style="width:60%;">
                        <p style="font-size: 14px; font-family: Arial;">
                          Poblaci&oacute;n Asegurada segun Censo 2012 : '.$row['pob_aseg_censo'].', Latitud : '.$row['lat'].' - Longitud : '.$row['lng'].'
                        </p>
                        </td>
                        <td style="width:20%;" align=center>
                          <div class="btn-group">
                            <button class="btn btn-primary">
                              CONSOLIDADO REGIONAL
                            </button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                              <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">';
                            /*
                              <li>
                                <a href="'.site_url("").'/rep_cregional_pr/'.$row['dep_id'].'" id="myBtn_dep'.$row['dep_id'].'" title="Consolidado de Evaluaci&oacute;n de Operaciones Con / sin Prioridad">Operaciones c/s Prioridad</a>
                              </li>
                            */
                            $tabla.='
                              <li class="divider"></li>
                              <li>
                                <a href="'.site_url("").'/rep_cregional/'.$row['dep_id'].'" id="myBtn_dep_pr'.$row['dep_id'].'" title="Consolidado de Evaluacion de Operaciones">Consolidado Poa - '.$this->gestion.'</a>
                              </li>
                              <li class="divider"></li>
                              <li>
                                <a href="'.site_url("").'/rep_tpcregional/'.$row['dep_id'].'/4" id="myBtn_tpdep_tp'.$row['dep_id'].'" title="Consolidado Poa - Gasto Corriente">Poa - Gasto Corriente</a>
                              </li>
                              <li>
                                <a href="'.site_url("").'/rep_tpcregional/'.$row['dep_id'].'/1" id="myBtn_tpdep_tp'.$row['dep_id'].'" title="Consolidado Poa - Proyecto de Inversión">Poa - Proyecto Inversi&oacute;n</a>
                              </li>
                              <li class="divider"></li>
                              <li>
                                <a href="'.site_url("").'/rep_cregional_pei/'.$row['dep_id'].'" id="myBtn_dep_pei'.$row['dep_id'].'" title="Consolidado de Evaluacion de Operacione PEI">Consolidado Pei - '.$this->gestion.'</a>
                              </li>
                            </ul>
                          </div>
                        </td>
                        <td style="width:20%;">';
                           $tabla .='
                              <center>
                                <a data-toggle="modal" data-target="#'.$row['dep_id'].'" class="btn btn-primary" style="width:100%;" title="DISTRITALES">DISTRITALES</a>
                              </center>
                              <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$row['dep_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                      </button>
                                      <h4 class="modal-title">
                                        '.strtoupper($row['dep_departamento']).'
                                      </h4>
                                    </div>
                                    <div class="modal-body no-padding">
                                      <div class="row">
                                        <div class="col-sm-3">
                                        <div class="well well-sm" align=center>';
                                        if($row['dep_id']==5 || $row['dep_id']==6){
                                            $tabla.='<img src="'.base_url().'assets/img/mapas/'.$row['dep_id'].'.jpg" style="width:165px; height:165px;" title='.strtoupper($row['dep_departamento']).'/>';
                                          }
                                          else{
                                            $tabla.='<img src="'.base_url().'assets/img/mapas/'.$row['dep_id'].'.png" style="width:165px; height:165px;" title='.strtoupper($row['dep_departamento']).'/>';
                                          }
                                        $tabla.='
                                        </div>
                                      </div>
                                      <div class="col-sm-9"><br>
                                        <table class="table table-bordered" style="width:95%;">
                                          <thead>
                                          <tr>
                                            <th style="width:1%;"><center>NRO.</center></th>
                                            <th style="width:80%;"><center>DISTRITAL</center></th>
                                            <th style="width:19%;"></th>
                                            <th style="width:1%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>';
                                          $nroc=0;
                                            foreach($distritales  as $row_d){
                                              $nroc++;
                                             $tabla.=
                                             '<tr>
                                                <td>'.$nroc.'</td>
                                                <td>'.strtoupper($row_d['dist_distrital']).'</td>
                                                <td><a href="'.site_url("").'/rep_distrital/'.$row_d['dist_id'].'" style="width:100%;" id="myBtn'.$row_d['dist_id'].'" class="btn btn-primary">VER REPORTE DE EVALUACI&Oacute;N</a></td>
                                                <td align=center><img id="load'.$row_d['dist_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
                                              </tr>';
                                              $tabla.='
                                              <script>
                                                  document.getElementById("myBtn'.$row_d['dist_id'].'").addEventListener("click", function(){
                                                  document.getElementById("load'.$row_d['dist_id'].'").style.display = "block";
                                                });
                                              </script>';
                                            }
                                          $tabla .='</tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>';
                        $tabla.='
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div id="load_dep'.$row['dep_id'].'" style="display: none" align=center><img src="'.base_url().'/assets/img_v1.1/preloader.gif"  width="100"><br><b>GENERANDO DATOS DE EVALUACI&Oacute;N REGIONAL ... </b></div>
                </div>
              </div>
              <div class="col-sm-1">
              </div>
            </div>';

            $tabla.='
            <script>
                document.getElementById("myBtn_dep'.$row['dep_id'].'").addEventListener("click", function(){
                document.getElementById("load_dep'.$row['dep_id'].'").style.display = "block";
              });
              document.getElementById("myBtn_dep_pr'.$row['dep_id'].'").addEventListener("click", function(){
                document.getElementById("load_dep'.$row['dep_id'].'").style.display = "block";
              });
            </script>';
        }
      }
      
      return $tabla;
    }


    /*-------- MENU CONSOLIDADO REGIONAL --------*/
    public function consolidado_regional($dep_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dep']=$this->model_evalregional->get_dpto($dep_id);
      if(count($data['dep'])!=0){
        $data['dist']=$this->model_evalregional->get_distrital($dep_id);
        $data['tabla']=$this->proyectos_regional($dep_id);
        $data['print_regional']=$this->print_proyectos_distrital($dep_id,$data['tabla']); /// print Eficacia Regional

        /*--------- Cuadro Evaluacion de operaciones por Programas -----------------*/
        $data['eval_trimestral']=$this->cuadro_comparativo_programas_evaluado($dep_id)[0];
        $data['evaluacion']=$this->evaluacion_operaciones_regional($dep_id);
        $data['imprimir_evaluacion']=$this->print_evaluacion_operaciones_regional($data['dep']);
        //$data['evaluacion']=$this->cuadro_comparativo_programas_evaluado($dep_id,1)[1];
        /*--------- Grafico (torta) parametros de eficacia -----------------*/
        $data['nro']=$this->nro_list_distrital($dep_id);
        $data['eficacia']=$this->eficacia_distrital($data['nro'],$dep_id,1); //// Grafico de Parametros de eficacia
        $data['print_eficacia']='Trabajando';

        $tmes=$this->model_evaluacion->trimestre();
        $data['tmes']='TRIMESTRE NO DEFINIDO';
        if(count($tmes)!=0){
          $data['tmes']=$this->model_evaluacion->trimestre();
        }

        $data['tr']=($this->tmes*3);
        $data['trimestre']=$this->model_evaluacion->trimestre();

        $puntaje=$data['tabla'][3][$this->tmes*3];
        $color='';
        if($puntaje<=75){$color='#f95b4f';} /// Insatisfactorio
        if ($puntaje > 75 & $puntaje <= 90){$color='#c79121';} /// Regular
        if($puntaje > 90 & $puntaje <= 99){$color='#57889c';} /// Bueno
        if($puntaje > 99 & $puntaje <= 102){$color='#6d966d';} /// Optimo

        $data['color']=$color;
        $this->load->view('admin/reportes_cns/eval_regional/reporte_eval/eval_consolidado_regional', $data);
      }
      else{
        redirect('admin/dashboard');
      }
      
    }


    /*---- IMPRIMIR EVALUACION ACUMULADO DE OPERACIONES POR REGIONAL ---*/ 
    public function print_evaluacion_operaciones_regional($dep){
      $tr=($this->tmes*3);
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();

      $eval=$this->cuadro_comparativo_programas_evaluado($dep[0]['dep_id'])[1];
      $graf_c_a=round((($eval[8]/$eval[11]*100)),2); // Cumplido Acumulado
      $graf_av_a=round((($eval[9]/$eval[11]*100)),2); // Avance Acumulado
      $graf_nc_a=round((100-($graf_c_a+$graf_av_a)),2); // No cumplido Acumulado

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
        .circulo, .ovalo {
          border: 2px solid #888888;
          margin: 2%;
          height: 42px;
          border-radius: 11px;
        }
        .circulo {
          width: 100px;      
        }
        .ovalo {
          width: 150px;
        }
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="90%" align=center border=0>
                  <tr>
                    <td width=22%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="50px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b>'.$this->session->userdata('entidad').'</b></td>
                          </tr>
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">REGIONAL - '.strtoupper($dep[0]['dep_departamento']).'</td>
                          </tr>
                        </table>
                    </td>
                    <td width=22%; align=left style="font-size: 8px;">
                      <div class="circulo" style="width:99%;"><br>
                      &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                      &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                      </div>
                    </td>
                  </tr>
                </table>
                <hr>';
        $tabla .='<center><FONT FACE="courier new" size="2"><b>CUADRO DE EVALUACI&Oacute;N ACUMULADA DE OPERACIONES AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>';
        $tabla.=''.$this->print_evaluacion_acu_operaciones($dep[0]['dep_id']).'<br>
                    <table class="change_order_items" border=1>
                    <tr>
                      <td>
                        <div id="container_acu2" style="width: 650px; height: 350px; margin: 0 auto"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      <div class="table-responsive">
                        <table class="change_order_items" border=1>
                          <thead>
                            <tr bgcolor="#1c7368" class="modo1">
                              <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">CUMPLIDO</th>
                              <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"">EN AVANCE</th>
                              <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NO CUMPLIDO</th>
                              <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL PROG.</th>
                              <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL EVAL.</th>
                              <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% CUMPLIDO</th>
                              <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% NO CUMPLIDO</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr align=center class="modo1">
                              <td>'.$eval[8].'</td>
                              <td>'.$eval[9].'</td>
                              <td>'.$eval[10].'</td>
                              <td>'.$eval[11].'</td>
                              <td>'.$eval[12].'</td>
                              <td>'.$eval[13].' %</td>
                              <td>'.$eval[14].' %</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      </td>
                    </tr>
                    </table>';
    return $tabla;
    }


    public function print_evaluacion_acu_operaciones($dep_id){
      $programas=$this->model_evalregional->categorias_programaticas_regional($dep_id);
      $tabla='';
      $tabla.='<table class="change_order_items" border=1>
                  <thead>
                    <tr align=center>
                      <th style="width:1%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">#</th>
                      <th style="width:10%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">PROGRAMA</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">CUMPLIDO</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">EN AVANCE</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NO CUMPLIDO</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL PROGRAMADO</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL EVALUADO</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% CUMPLIDO</th>
                      <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% NO CUMPLIDO</th>
                    </tr>
                  </thead>
                    <tbody>';
                    $nro=0;
                    $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0;
                    foreach($programas  as $rowp){
                      if($rowp['aper_programa']!='97' & $rowp['aper_programa']!='98'){
                        $eval_acu=$this->matriz_evaluacion_Acumulado($dep_id,$rowp['aper_programa']); /// Evalucion Trimestral Acumulado
                        $nro++;
                        $tabla.='<tr class="modo1">';
                          $tabla.='<td style="width: 1%; text-align: center; height:14px;">'.$nro.'</td>
                                  <td style="width: 10%; text-align: left;">'.$rowp['aper_programa'].' - '.$rowp['aper_descripcion'].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[1].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[2].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[3].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[7].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[4].'</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[5].' %</td>
                                  <td style="width: 7%; text-align: right;">'.$eval_acu[6].' %</td>
                                  </tr>';

                        $nro_cum_a=$nro_cum_a+$eval_acu[1];
                        $nro_pro_a=$nro_pro_a+$eval_acu[2];
                        $nro_ncum_a=$nro_ncum_a+$eval_acu[3];
                        $total_prog_a=$total_prog_a+$eval_acu[7];
                        $total_eval_a=$total_eval_a+$eval_acu[4];

                        $pcion_a=0;
                        $npcion_a=0;
                        if($total_prog_a!=0){
                          $pcion_a=round((($nro_cum_a/$total_prog_a)*100),2);
                          $npcion_a=(100-$pcion_a);
                        }
                      }
                    }
                    $tabla.='
                    </tbody>
                      <tr bgcolor="#f7f7f7" class="modo1">
                        <td colspan="2" style="text-align: center; height:13px;">TOTAL : </td>
                        <td style="text-align: right;">'.$nro_cum_a.'</td>
                        <td style="text-align: right;">'.$nro_pro_a.'</td>
                        <td style="text-align: right;">'.$nro_ncum_a.'</td>
                        <td style="text-align: right;">'.$total_prog_a.'</td>
                        <td style="text-align: right;">'.$total_eval_a.'</td>
                        <td style="text-align: right;">'.$pcion_a.' %</td>
                        <td style="text-align: right;">'.$npcion_a.' %</td>
                      </tr>
                  </table>';

        return $tabla;
    }

    /*---- GRAFICO EVALUACION TRIMESTRAL Y ACUMULADO DE OPERACIONES POR REGIONAL ---*/ 
    public function evaluacion_operaciones_regional($dep_id){
      $dep=$this->model_evalregional->get_dpto($dep_id);
      $eval=$this->cuadro_comparativo_programas_evaluado($dep_id)[1];
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }

      $graf_c=round((($eval[1]/$eval[4]*100)),2); // Cumplido 
      $graf_av=round((($eval[2]/$eval[4]*100)),2); // Avance 
      $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido

      $graf_c_a=round((($eval[8]/$eval[11]*100)),2); // Cumplido Acumulado
      $graf_av_a=round((($eval[9]/$eval[11]*100)),2); // Avance Acumulado
      $graf_nc_a=round((100-($graf_c_a+$graf_av_a)),2); // No cumplido Acumulado

      $graf='<div id="container_tri" style="width: 600px; height: 300px; margin: 0 auto"></div>';
      $graf_acu='<div id="container_acu" style="width: 600px; height: 300px; margin: 0 auto"></div>';
      $tabla ='';
      $tabla .='
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <table class="change_order_items" border=1>
                <tr>
                  <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL</b></font><br>
                    <font FACE="courier new" size="1"><b>'.$trimestre.'</b></font>
                  </center>
                    '.$graf.'
                  </td>
                </tr>
                <tr>
                  <td>
                  <div class="table-responsive">
                    <table class="table table-bordered" width="100%" align="center">
                      <thead>
                      <tr class="modo1">
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">CUMPLIDO</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"">EN AVANCE</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NO CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL PROG.</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL EVAL.</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% NO CUMPLIDO</th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr align=center class="modo1">
                          <td>'.$eval[1].'</td>
                          <td>'.$eval[2].'</td>
                          <td>'.$eval[3].'</td>
                          <td>'.$eval[4].'</td>
                          <td>'.$eval[5].'</td>
                          <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[6].' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[7].' %</button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <table class="change_order_items" border=1>
              <tr>
                <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL ACUMULADO</b></font><br>
                    <font FACE="courier new" size="1"><b> AL '.$trimestre.'</b></font>
                  </center>
                '.$graf_acu.'
                </td>
              </tr>
              <tr>
                <td>
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" align="center">
                    <thead>
                      <tr bgcolor="#1c7368" class="modo1">
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">CUMPLIDO</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"">EN AVANCE</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NO CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL PROG.</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL EVAL.</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% NO CUMPLIDO</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr align=center class="modo1">
                        <td>'.$eval[8].'</td>
                        <td>'.$eval[9].'</td>
                        <td>'.$eval[10].'</td>
                        <td>'.$eval[11].'</td>
                        <td>'.$eval[12].'</td>
                        <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[13].' %</button></td>
                        <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[14].' %</button></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </td>
              </tr>
              </table>
            </div>';
            ?>
            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container_tri', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $graf_nc;?>%',
                          y: <?php echo $graf_nc;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av;?>%',
                          y: <?php echo $graf_av;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c; ?>%',
                          y: <?php echo $graf_c;?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
          </script>
          <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container_acu', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $graf_nc_a;?>%',
                          y: <?php echo $graf_nc_a;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av_a;?>%',
                          y: <?php echo $graf_av_a;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c_a; ?>%',
                          y: <?php echo $graf_c_a;?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });

            $(document).ready(function() {  
               Highcharts.chart('container_acu2', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $graf_nc_a;?>%',
                          y: <?php echo $graf_nc_a;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av_a;?>%',
                          y: <?php echo $graf_av_a;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c_a; ?>%',
                          y: <?php echo $graf_c_a;?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
          </script>
          <?php
      return $tabla;
    }


    /*----------- Consolidado Regional cuadro de eficacia -----------------*/
    public function proyectos_regional($dep_id){
      $m[1]='Ene.';
      $m[2]='Feb.';
      $m[3]='Mar.';
      $m[4]='Abr.';
      $m[5]='May.';
      $m[6]='Jun.';
      $m[7]='Jul.';
      $m[8]='Agos.';
      $m[9]='Sept.';
      $m[10]='Oct.';
      $m[11]='Nov.';
      $m[12]='Dic.';

      for($i=0; $i <=12 ; $i++) { 
        $p[1][$i]=0; // Prog. // Cumplidos
        $p[2][$i]=0; // Ejec. // En Proceso
        $p[3][$i]=0; // Efi.  // No cumplido 
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo
      }

      $proyectos=$this->model_evalregional->list_consolidado_regional($dep_id);
      foreach($proyectos  as $rowp){
        $tabla=$this->componentes($rowp['proy_id']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['proy_pcion_reg'])/100),2);
          if(($p[1][$i]>=100 & $p[1][$i]<=102) || $p[1][$i]>=99.90){
            $p[1][$i]=100;
          }
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['proy_pcion_reg'])/100),2);
          if($p[1][$i]!=0){
            $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
          }
          $p[4][$i]=$m[$i];
          
          if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
          if ($p[3][$i] > 75 & $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
          if($p[3][$i] > 90 & $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
          if($p[3][$i] > 99 & $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
        }
      }
      
      $cum=$this->model_evalregional->evaluacion_productos_regional($dep_id,1); // Cumplido
      $pro=$this->model_evalregional->evaluacion_productos_regional($dep_id,2); // En Proceso
      $ncum=$this->model_evalregional->evaluacion_productos_regional($dep_id,3); // No Cumplido
      $nro_1=0;$nro_2=0;$nro_3=0;
      if(count($cum)!=0){
        $nro_1=$cum[0]['total'];
      }
      if(count($pro)!=0){
        $nro_2=$pro[0]['total'];
      }
      if(count($ncum)!=0){
        $nro_3=$ncum[0]['total'];
      }

      $p[1][0]=$nro_1; // Prog. // Cumplidos
      $p[2][0]=$nro_2; // Ejec. // En Proceso
      $p[3][0]=$nro_3; // Efi.  // No cumplido 

      return $p;
    }

    /*--------- Imprime Evaluacion Consolidado Regional ---------*/
    public function print_proyectos_distrital($dep_id,$p){
      $dep=$this->model_evalregional->get_dpto($dep_id);
      $tr=($this->tmes*3);
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
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
        .circulo, .ovalo {
          border: 2px solid #888888;
          margin: 2%;
          height: 42px;
          border-radius: 11px;
        }
        .circulo {
          width: 100px;      
        }
        .ovalo {
          width: 150px;
        }
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="90%" align=center border=0>
                  <tr>
                    <td width=22%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="50px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b>'.$this->session->userdata('entidad').'</b></td>
                          </tr>
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">REGIONAL - '.strtoupper($dep[0]['dep_departamento']).'</td>
                          </tr>
                        </table>
                    </td>
                    <td width=22%; align=left style="font-size: 8px;">
                      <div class="circulo" style="width:99%;"><br>
                      &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                      &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                      </div>
                    </td>
                  </tr>
                </table>
                <hr>';
        $tabla .='<center><FONT FACE="courier new" size="2"><b>CUADRO DE EJECUCI&Oacute;N DE RESULTADOS AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>';
        $tabla .='<table class="change_order_items" border=1 style="width:100%;">
                  <tr>
                    <td>
                      <div id="regresion2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div id="container2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan=2>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr align=center>
                              <th style="width:1%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"></th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">ENE.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">FEB.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">MAR.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">ABR.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">MAY.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">JUN.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">JUL.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">AGO.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">SEPT.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">OCT.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NOV.</th>
                              <th style="width:7%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">DIC.</th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#e9f9f8">'.$p[1][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#e9f9f8">'.$p[2][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[2][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr bgcolor="#e9f9f8">
                                <td>%EJECUCI&Oacute;N</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td><b>'.$p[3][$i].'%</b></td>';
                                  }
                                  else{
                                    $tabla .='<td><b>'.$p[3][$i].'%</b></td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>';
        $tabla .='</table>';
      ?>
        <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script type="text/javascript">
        var chart;
        $(document).ready(function() {
        chart = new Highcharts.chart('graf_eficacia_print', {
              chart: {
                  type: 'column'
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Agos.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
              },
              yAxis: {
                  min: 0,
                  title: {
                      text: 'PORCENTAJES (%)'
                  },
                  stackLabels: {
                      enabled: true,
                      style: {
                          fontWeight: 'bold',
                          color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                      }
                  }
              },
              legend: {
                  align: 'right',
                  x: -30,
                  verticalAlign: 'top',
                  y: 25,
                  floating: true,
                  backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                  borderColor: '#CCC',
                  borderWidth: 1,
                  shadow: false
              },
              tooltip: {
                  headerFormat: '<b>{point.x}</b><br/>',
                  pointFormat: '{series.name}: <br/> TOTAL:   {point.y} %'
              },
              plotOptions: {
                  column: {
                      stacking: 'normal',
                      dataLabels: {
                          enabled: false,
                          color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                      }
                  }
              },
              series: [{
                  color: '#f95b4f',
                  name: '<b style="color: #f95b4f;" size=1>MENOR A 75%</b>',
                  data: [{y: <?php echo $p[5][1]?>, color: 'red'},{y: <?php echo $p[5][2]?>, color: 'red'},{y: <?php echo $p[5][3]?>, color: 'red'},{y: <?php echo $p[5][4]?>, color: 'red'},{y: <?php echo $p[5][5]?>, color: 'red'},{y: <?php echo $p[5][6]?>, color: 'red'},{y: <?php echo $p[5][7]?>, color: 'red'},{y: <?php echo $p[5][8]?>, color: 'red'},{y: <?php echo $p[5][9]?>, color: 'red'},{y: <?php echo $p[5][10]?>, color: 'red'},{y: <?php echo $p[5][11]?>, color: 'red'},{y: <?php echo $p[5][12]?>, color: 'red'}]
              }, {
                  color: '#f3d375',
                  name: '<b style="color: #f3d375;" size=1>ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $p[6][1]?>, color: 'yellow'},{y: <?php echo $p[6][2]?>, color: 'yellow'},{y: <?php echo $p[6][3]?>, color: 'yellow'},{y: <?php echo $p[6][4]?>, color: 'yellow'},{y: <?php echo $p[6][5]?>, color: 'yellow'},{y: <?php echo $p[6][6]?>, color: 'yellow'},{y: <?php echo $p[6][7]?>, color: 'yellow'},{y: <?php echo $p[6][8]?>, color: 'yellow'},{y: <?php echo $p[6][9]?>, color: 'yellow'},{y: <?php echo $p[6][10]?>, color: 'yellow'},{y: <?php echo $p[6][11]?>, color: 'yellow'},{y: <?php echo $p[6][12]?>, color: 'yellow'}] 
              }, {
                  color: '#03a9f4',
                  name: '<b style="color: #03a9f4;" size=1>ENTRE 91% Y 99%</b>',
                  data: [{y: <?php echo $p[7][1]?>, color: '#0592d2'},{y: <?php echo $p[7][2]?>, color: '#0592d2'},{y: <?php echo $p[7][3]?>, color: '#0592d2'},{y: <?php echo $p[7][4]?>, color: '#0592d2'},{y: <?php echo $p[7][5]?>, color: '#0592d2'},{y: <?php echo $p[7][6]?>, color: '#0592d2'},{y: <?php echo $p[7][7]?>, color: '#0592d2'},{y: <?php echo $p[7][8]?>, color: '#0592d2'},{y: <?php echo $p[7][9]?>, color: '#0592d2'},{y: <?php echo $p[7][10]?>, color: '#0592d2'},{y: <?php echo $p[7][11]?>, color: '#0592d2'},{y: <?php echo $p[7][12]?>, color: '#0592d2'}] 
              }, {
                  color: '#4caf50',
                  name: '<b style="color: #4caf50;" size=1>IGUAL A 100%</b>',
                  data: [{y: <?php echo $p[8][1]?>, color: 'green'},{y: <?php echo $p[8][2]?>, color: 'green'},{y: <?php echo $p[8][3]?>, color: 'green'},{y: <?php echo $p[8][4]?>, color: 'green'},{y: <?php echo $p[8][5]?>, color: 'green'},{y: <?php echo $p[8][6]?>, color: 'green'},{y: <?php echo $p[8][7]?>, color: 'green'},{y: <?php echo $p[8][8]?>, color: 'green'},{y: <?php echo $p[8][9]?>, color: 'green'},{y: <?php echo $p[8][10]?>, color: 'green'},{y: <?php echo $p[8][11]?>, color: 'green'},{y: <?php echo $p[8][12]?>, color: 'green'}] 
              }],

          });
      });
    </script>
    <?php

    return $tabla;
    }

    /*--------- Cuadro comparativo por programas de Evaluacion Trimestral ---------*/
    public function cuadro_comparativo_programas_evaluado($dep_id){
      $tabla ='';
      $programas=$this->model_evalregional->categorias_programaticas_regional($dep_id);
        $tabla.='<table class="table table-bordered" width="100%" align="center">
              <thead>
                <tr>
                  <th style="width: 5%; text-align: center;" colspan="2"><center>DATOS DEL PROGRAMA</center></th>
                  <th style="width: 45%; text-align: center;" colspan="7"><center>EVALUACI&Oacute;N TRIMESTRAL</center></th>
                  <th style="width: 45%; text-align: center;" colspan="7"><center>EVALUACI&Oacute;N TRIMESTRAL ACUMULADO</center></th>
                </tr>
                <tr>
                  <th>#</th>
                  <th style="width: 9%; text-align: center;">DESCRIPCI&Oacute;N PROGRAMA</th>
                  <th style="width: 5%; text-align: center;">CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">EN AVANCE</th>
                  <th style="width: 5%; text-align: center;">NO CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">TOTAL PROG.</th>
                  <th style="width: 5%; text-align: center;">TOTAL EVAL.</th>
                  <th style="width: 5%; text-align: center;">% CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">% NO CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">EN AVANCE</th>
                  <th style="width: 5%; text-align: center;">NO CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">TOTAL PROG.</th>
                  <th style="width: 5%; text-align: center;">TOTAL EVAL.</th>
                  <th style="width: 5%; text-align: center;">% CUMPLIDO</th>
                  <th style="width: 5%; text-align: center;">% NO CUMPLIDO</th>
                </tr>
              </thead>
              <tbody>';
            $nro=0;
            $nro_cum=0;$nro_pro=0;$nro_ncum=0;$total_prog=0;$total_eval=0;
            $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0;
            foreach($programas  as $rowp){
              if($rowp['aper_programa']!='97' & $rowp['aper_programa']!='98'){

                $eval=$this->matriz_evaluacion_trimestre($dep_id,$rowp['aper_programa']); /// Evaluacion Trimestral
                $eval_acu=$this->matriz_evaluacion_Acumulado($dep_id,$rowp['aper_programa']); /// Evalucion Trimestral Acumulado
                $nro++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td style="width: 1%; text-align: center; height:13px;">'.$nro.'</td>';
                  $tabla.='<td style="width: 9%; text-align: left;">'.$rowp['aper_programa'].' - '.$rowp['aper_descripcion'].'</td>';
                  $tabla.='<td style="width: 5%; text-align: right;" bgcolor="#cff7f2">'.$eval[1].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#cff7f2">'.$eval[2].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#cff7f2">'.$eval[3].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#cff7f2">'.$eval[7].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#cff7f2">'.$eval[4].'</td>
                          <td style="width: 5%; text-align: center;" bgcolor="#cff7f2" title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[5].' %</button></td>
                          <td style="width: 5%; text-align: center;" bgcolor="#cff7f2" title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[6].' %</button></td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad">'.$eval_acu[1].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad">'.$eval_acu[2].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad">'.$eval_acu[3].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad">'.$eval_acu[7].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad">'.$eval_acu[4].'</td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad" title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval_acu[5].' %</button></td>
                          <td style="width: 5%; text-align: right;" bgcolor="#a2f9ad" title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval_acu[6].' %</button></td>
                          </tr>';
                $nro_cum=$nro_cum+$eval[1];
                $nro_pro=$nro_pro+$eval[2];
                $nro_ncum=$nro_ncum+$eval[3];
                $total_prog=$total_prog+$eval[7];
                $total_eval=$total_eval+$eval[4];

                $nro_cum_a=$nro_cum_a+$eval_acu[1];
                $nro_pro_a=$nro_pro_a+$eval_acu[2];
                $nro_ncum_a=$nro_ncum_a+$eval_acu[3];
                $total_prog_a=$total_prog_a+$eval_acu[7];
                $total_eval_a=$total_eval_a+$eval_acu[4];

                $pcion=0;
                $npcion=0;
                $pcion_a=0;
                $npcion_a=0;
                if($total_prog!=0){
                  $pcion=round((($nro_cum/$total_prog)*100),2);
                  $npcion=(100-$pcion);
                  //$npcion=round(((($nro_pro+$nro_ncum)/$total_prog)*100),2);

                  $pcion_a=round((($nro_cum_a/$total_prog_a)*100),2);
                  //$npcion_a=round(((($nro_pro_a+$nro_ncum_a)/$total_prog_a)*100),2);
                  $npcion_a=(100-$pcion_a);
                }
                
              }
            }
            $tabla.='</tbody>
                      <tr bgcolor="#f7f7f7" class="modo1">
                        <td colspan="2" style="width: 10%; text-align: center; height:13px;">TOTAL : </td>
                        <td style="text-align: right;">'.$nro_cum.'</td>
                        <td style="text-align: right;">'.$nro_pro.'</td>
                        <td style="text-align: right;">'.$nro_ncum.'</td>
                        <td style="text-align: right;">'.$total_prog.'</td>
                        <td style="text-align: right;">'.$total_eval.'</td>
                        <td title="OPERACIONES CUMPLIDOS A NIVEL REGIONAL"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion.' %</button></td>
                        <td title="OPERACIONES NO CUMPLIDOS A NIVEL REGIONAL"><button type="button" style="width:100%;" class="btn btn-danger">'.$npcion.' %</button></td>
                        <td style="text-align: right;">'.$nro_cum_a.'</td>
                        <td style="text-align: right;">'.$nro_pro_a.'</td>
                        <td style="text-align: right;">'.$nro_ncum_a.'</td>
                        <td style="text-align: right;">'.$total_prog_a.'</td>
                        <td style="text-align: right;">'.$total_eval_a.'</td>
                        <td title="OPERACIONES CUMPLIDOS A NIVEL REGIONAL ACUMULADO"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion_a.' %</button></td>
                        <td title="OPERACIONES NO CUMPLIDOS A NIVEL REGIONAL ACUMULADO"><button type="button" style="width:100%;" class="btn btn-danger">'.$npcion_a.' %</button></td>
                      </tr>
                  </table>';
      $eval[1]=$nro_cum;
      $eval[2]=$nro_pro;
      $eval[3]=$nro_ncum;
      $eval[4]=$total_prog;
      $eval[5]=$total_eval;
      $eval[6]=$pcion;
      $eval[7]=$npcion;

      $eval[8]=$nro_cum_a;
      $eval[9]=$nro_pro_a;
      $eval[10]=$nro_ncum_a;
      $eval[11]=$total_prog_a;
      $eval[12]=$total_eval_a;
      $eval[13]=$pcion_a;
      $eval[14]=$npcion_a;

      return array($tabla,$eval);
    }

    /*----------- Parametros de Eficacia Concolidado Regional ----------------*/
    public function eficacia_distrital($matriz,$dep_id,$tp){
        if($tp==1){
          $class='class="table table-bordered" align=center style="width:60%;"';
          $div='<div id="parametro_efi" style="width: 600px; height: 400px; margin: 0 auto"></div>';
        }
        else{
          $class='';
          $div='<div id="parametro_efi_print" style="width: 650px; height: 330px; margin: 0 auto"></div>';
        }
      //  $nro=$this->nro_list_distrital($dep_id);
        $nro=$matriz;
        $tabla='';
        $tabla .='<table '.$class.'>
                    <tr>
                      <td>
                        '.$div.'
                      </td>
                    </tr>
                    <tr>
                    <td>
                        <table '.$class.'>
                          <thead>
                            <tr>
                              <th style="width: 33%"><center><b>TIPO DE CALIFICACI&Oacute;N</b></center></th>
                              <th style="width: 33%"><center><b>PARAMETRO</b></center></th>
                              <th style="width: 33%"><center><b>NRO DE UNIDADES/PROYECTOS DE INVERSI&Oacute;N</b></center></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>INSATISFACTORIO</td>
                              <td>0% a 75%</td>
                              <td><a class="btn btn-danger" style="width: 100%" align="left" title="'.$nro[1].' Unidades/Proyectos">'.$nro[1].'</a></td>
                            </tr>
                            <tr>
                              <td>REGULAR</td>
                              <td>75% a 90% </td>
                              <td><a class="btn btn-warning" style="width: 100%" align="left" title="'.$nro[2].' Unidades/Proyectos">'.$nro[2].'</a></td>
                            </tr>
                            <tr>
                              <td>BUENO</td>
                              <td>90% a 99%</td>
                              <td><a class="btn btn-info" style="width: 100%" align="left" title="'.$nro[3].' Unidades/Proyectos">'.$nro[3].'</a></td>
                            </tr>
                            <tr>
                              <td>OPTIMO </td>
                              <td>100%</td>
                              <td><a class="btn btn-success" style="width: 100%" align="left" title="'.$nro[4].' Unidades/Proyectos">'.$nro[4].'</a></td>
                            </tr>
                            <tr>
                              <td colspan=2 align="center"><b>TOTAL : </b></td>
                              <td align="center"><b>'.$nro[5].'</b></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </table>';

        
        return $tabla;
    }

    /*------------------------- Lista de nro Acciones Operativas a nivel Distrital ----------------------*/
    public function nro_list_distrital($dep_id){
    //  $dist=$this->model_evalregional->get_dist($dist_id);
      $acciones=$this->model_evalregional->list_consolidado_regional($dep_id);
      
      for ($i=1; $i <=9 ; $i++) { 
        $nro[$i]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($acciones  as $row){
        $p=$this->eficacia_evaluacion($row['proy_id'],1); /// Eficacia
        if($p[4][$this->tr_id]==1 || $p[4][$this->tr_id]==0){$nro[1]++;}
        elseif($p[4][$this->tr_id]==2 ){$nro[2]++;}
        elseif($p[4][$this->tr_id]==3 ){$nro[3]++;}
        elseif($p[4][$this->tr_id]==4 ){$nro[4]++;}
      }

      $nro_acciones=count($acciones);
      $nro[5]=$nro_acciones;
      $nro[6]=round((($nro[1]/$nro_acciones)*100),2); /// % insatisfactorio
      $nro[7]=round((($nro[2]/$nro_acciones)*100),2); /// % regular
      $nro[8]=round((($nro[3]/$nro_acciones)*100),2); /// % Bueno
      $nro[9]=round((($nro[4]/$nro_acciones)*100),2); /// % optimo

      return $nro;
    }

    public function eficacia_evaluacion($proy_id,$tp){
      $tab=$this->componentes($proy_id);
      for ($i=1; $i <=12 ; $i++) { 
        $ev[1][$i]=0;$ev[2][$i]=0;$ev[3][$i]=0;$ev[4][$i]=0;
        if($tp==1){
          $ev[5][$i]='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
        }
        else{
          $ev[5][$i]='INSATISFACTORIO';
        }
        $ev[6][$i]='#f5dcdb';
      }

        for ($i=1; $i <=12 ; $i++) { 
            $ev[1][$i]=$tab[1][$i]; // Programado Acumulado
            $ev[2][$i]=$tab[2][$i]; // Ejecutado Acumulado
          if($tab[1][$i]!=0){
            $ev[3][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($ev[3][$i]>=0 & $ev[3][$i]<=75){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
              }
              else{
                $enlace='INSATISFACTORIO';
              }
              $ev[4][$i] = 1;$ev[5][$i] = $enlace;$ev[6][$i]='#f5dcdb';
            }

            elseif($ev[3][$i]>=75 & $ev[3][$i]<=90){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA REGULAR" target="_blank" style="width:100%;" class="btn btn-warning">REGULAR</a>';
              }
              else{
                $enlace='REGULAR';
              }

              $ev[4][$i] = 2;$ev[5][$i] = $enlace;$ev[6][$i]='#efe8b2';
            }
            
            elseif($ev[3][$i]>=90 & $ev[3][$i]<=99){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA BUENO" target="_blank" style="width:100%;" class="btn btn-info">BUENO</a>';
              }
              else{
                $enlace='BUENO';
              }

              $ev[4][$i] = 3;$ev[5][$i] = $enlace;$ev[6][$i]='#cbe8f5';
            }

            elseif($ev[3][$i]>=99 & $ev[3][$i]<=102){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA OPTIMO" target="_blank" style="width:100%;" class="btn btn-success">OPTIMO</a>';
              }
              else{
                $enlace='OPTIMO';
              }

              $ev[4][$i] = 4;$ev[5][$i] = $enlace; $ev[6][$i]='#a6eaa9';
            }
            else{
              $ev[4][$i] = 1;$ev[5][$i] = 'INSATISFACTORIO';
            }
          }
        }

        return $ev;
    }

    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACTUAL --------------*/
    public function matriz_evaluacion_trimestre($dep_id,$aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        
        $cum=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,1,$this->tmes);
        $proc=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,2,$this->tmes);
        $ncum=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,3,$this->tmes);
        $total_prog=$this->model_evalregional->total_programado_programas_regional($dep_id,$aper_programa,$this->tmes); // total programado prod

        /*------------- Acumulado Producto -------*/
        if(count($cum)!=0){
          $nro_cum=$nro_cum+$cum[0]['total'];
        }
        if(count($proc)!=0){
          $nro_proc=$nro_proc+$proc[0]['total'];
        }
        if(count($ncum)!=0){
          $nro_ncum=$nro_ncum+$ncum[0]['total'];
        }
        if(count($total_prog)!=0){
          $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
        }

        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;

        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }

      $cat[1]=$nro_1; // cumplidos
      $cat[2]=$nro_2; // en proceso
      $cat[3]=$nro_3; // no cumplido
      $cat[4]=$total; // Total Evaluacion
      $cat[5]=$pcion; // % cumplido
      $cat[6]=$npcion; // % no cumplido
      $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO --------------*/
    public function matriz_evaluacion_Acumulado($dep_id,$aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,1,$i);
        $proc=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,2,$i);
        $ncum=$this->model_evalregional->evaluacion_programas_regional($dep_id,$aper_programa,3,$i);
        $total_prog=$this->model_evalregional->total_programado_programas_regional($dep_id,$aper_programa,$i); // total programado prod

        /*------------- Acumulado Producto -------*/
        if(count($cum)!=0){
          $nro_cum=$nro_cum+$cum[0]['total'];
        }
        if(count($proc)!=0){
          $nro_proc=$nro_proc+$proc[0]['total'];
        }
        if(count($ncum)!=0){
          $nro_ncum=$nro_ncum+$ncum[0]['total'];
        }
        if(count($total_prog)!=0){
          $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
        }

        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
      }

      $cat[1]=$nro_1; // cumplidos
      $cat[2]=$nro_2; // en proceso
      $cat[3]=$nro_3; // no cumplido
      $cat[4]=$total; // Total Evaluacion
      $cat[5]=$pcion; // % cumplido
      $cat[6]=$npcion; // % no cumplido
      $cat[7]=$total_programado; // Total Programado

      return $cat;
    }


    /*--------------- Componentes --------------------*/
    public function componentes($proy_id){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id);;
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyectos[0]['tp_id']);

      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      foreach($componente  as $rowc){
        if($rowc['com_ponderacion']!=0){
        //  echo "-- COMPONENTE : ".$rowc['com_id']." : ---".$rowc['com_componente']." -> ".$rowc['com_ponderacion']."%<br>";
          $productos = $this->model_producto->list_prod($rowc['com_id']);
          if(count($productos)!=0){
            $tabla=$this->productos($rowc['com_id'],$proyectos[0]['proy_act']);
            
            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowc['com_ponderacion'])/100),2);
              $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowc['com_ponderacion'])/100),2);
            }
          }
        }
      }

      return $p;
    }

    /*------------------------ Productos -------------------------*/
    public function productos($com_id,$act){
      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $productos = $this->model_producto->list_prod($com_id);
      foreach($productos  as $rowp){
        if($rowp['prod_ponderacion']!=0){
        //  echo "---------- Productos : ".$rowp['prod_id']." : ".$rowp['prod_producto']." -> ".$rowp['prod_ponderacion']."%<br>";
          $tabla=$this->temporalidad_productos($rowp['prod_id']);
          for ($i=1; $i <=12 ; $i++) { 
            $p[1][$i]=$p[1][$i]+$tabla[1][$i];
            $p[2][$i]=$p[2][$i]+$tabla[2][$i];
          }
        }
      }

    return $p;
    }

    /*==== DISTRITALES===*/
    public function list_distritales($dep_id){
      $tmes=$this->model_evaluacion->trimestre();
      $dist=$this->model_evalregional->get_distrital($dep_id);
      $tabla ='';
                 
      foreach($dist  as $row){
        $tabla .='<hr><h2 class="alert alert-success" align="center">'.strtoupper($row['dist_distrital']).'</h2>';

        $p=$this->proyectos_distrital($dep_id,$row['dist_id']);
        $tabla .='<div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                          <div class="well padding-10">
                              <div id="graf_eficacia'.$row['dist_id'].'" style="width: 610px; height: 360px; margin: 0 auto"></div>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                          <div class="well padding-10">
                              <div id="container'.$row['dist_id'].'" style="width: 610px; height: 360px; margin: 0 auto"></div>
                          </div>
                      </div>
                  </div>';
          $tabla .='<br><div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>';
                                    for ($i=1; $i <=12 ; $i++) { 
                                      $tabla .='<th><center>'.$p[4][$i].'</center></th>';    
                                      }
                                $tabla .='
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                    for ($i=1; $i <=12 ; $i++) { 
                                      $tabla .='<td>'.$p[1][$i].'</td>';    
                                    }
                                $tabla .='
                                </tr>
                                <tr>
                                    <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                    for ($i=1; $i <=12 ; $i++) { 
                                      $tabla .='<td>'.$p[2][$i].'</td>';    
                                    }
                                $tabla .='
                                </tr>
                                <tr bgcolor="#e9f9f8">
                                    <td>EFICACIA</td>';
                                    for ($i=1; $i <=12 ; $i++) { 
                                      $tabla .='<td>'.$p[3][$i].'</td>';    
                                    }
                                $tabla .='
                                </tr>
                            </tbody>
                        </table>
                    </div>';
              ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                  <script type="text/javascript">
                  var chart2;
                  $(document).ready(function() {
                  chart2 = new Highcharts.chart('graf_eficacia'+<?php echo $row['dist_id'];?>, {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'EFICACIA INSTITUCIONAL A NIVEL REGIONAL'
                        },
                        xAxis: {
                            categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Agos.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'PORCENTAJES (%)'
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                                }
                            }
                        },
                        legend: {
                            align: 'right',
                            x: -30,
                            verticalAlign: 'top',
                            y: 25,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                            borderColor: '#CCC',
                            borderWidth: 1,
                            shadow: false
                        },
                        tooltip: {
                            headerFormat: '<b>{point.x}</b><br/>',
                            pointFormat: '{series.name}: <br/> TOTAL:   {point.y} %'
                        },
                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                dataLabels: {
                                    enabled: false,
                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                                }
                            }
                        },
                        series: [{
                            color: '#f95b4f',
                            name: '<b style="color: #f95b4f;" size=1>MENOR A 75%</b>',
                            data: [{y: <?php echo $p[5][1]?>, color: 'red'},{y: <?php echo $p[5][2]?>, color: 'red'},{y: <?php echo $p[5][3]?>, color: 'red'},{y: <?php echo $p[5][4]?>, color: 'red'},{y: <?php echo $p[5][5]?>, color: 'red'},{y: <?php echo $p[5][6]?>, color: 'red'},{y: <?php echo $p[5][7]?>, color: 'red'},{y: <?php echo $p[5][8]?>, color: 'red'},{y: <?php echo $p[5][9]?>, color: 'red'},{y: <?php echo $p[5][10]?>, color: 'red'},{y: <?php echo $p[5][11]?>, color: 'red'},{y: <?php echo $p[5][12]?>, color: 'red'}]
                        }, {
                            color: '#f3d375',
                            name: '<b style="color: #f3d375;" size=1>ENTRE 76% Y 90%</b>',
                            data: [{y: <?php echo $p[6][1]?>, color: 'yellow'},{y: <?php echo $p[6][2]?>, color: 'yellow'},{y: <?php echo $p[6][3]?>, color: 'yellow'},{y: <?php echo $p[6][4]?>, color: 'yellow'},{y: <?php echo $p[6][5]?>, color: 'yellow'},{y: <?php echo $p[6][6]?>, color: 'yellow'},{y: <?php echo $p[6][7]?>, color: 'yellow'},{y: <?php echo $p[6][8]?>, color: 'yellow'},{y: <?php echo $p[6][9]?>, color: 'yellow'},{y: <?php echo $p[6][10]?>, color: 'yellow'},{y: <?php echo $p[6][11]?>, color: 'yellow'},{y: <?php echo $p[6][12]?>, color: 'yellow'}] 
                        }, {
                            color: '#03a9f4',
                            name: '<b style="color: #03a9f4;" size=1>ENTRE 91% Y 99%</b>',
                            data: [{y: <?php echo $p[7][1]?>, color: '#0592d2'},{y: <?php echo $p[7][2]?>, color: '#0592d2'},{y: <?php echo $p[7][3]?>, color: '#0592d2'},{y: <?php echo $p[7][4]?>, color: '#0592d2'},{y: <?php echo $p[7][5]?>, color: '#0592d2'},{y: <?php echo $p[7][6]?>, color: '#0592d2'},{y: <?php echo $p[7][7]?>, color: '#0592d2'},{y: <?php echo $p[7][8]?>, color: '#0592d2'},{y: <?php echo $p[7][9]?>, color: '#0592d2'},{y: <?php echo $p[7][10]?>, color: '#0592d2'},{y: <?php echo $p[7][11]?>, color: '#0592d2'},{y: <?php echo $p[7][12]?>, color: '#0592d2'}] 
                        }, {
                            color: '#4caf50',
                            name: '<b style="color: #4caf50;" size=1>IGUAL A 100%</b>',
                            data: [{y: <?php echo $p[8][1]?>, color: 'green'},{y: <?php echo $p[8][2]?>, color: 'green'},{y: <?php echo $p[8][3]?>, color: 'green'},{y: <?php echo $p[8][4]?>, color: 'green'},{y: <?php echo $p[8][5]?>, color: 'green'},{y: <?php echo $p[8][6]?>, color: 'green'},{y: <?php echo $p[8][7]?>, color: 'green'},{y: <?php echo $p[8][8]?>, color: 'green'},{y: <?php echo $p[8][9]?>, color: 'green'},{y: <?php echo $p[8][10]?>, color: 'green'},{y: <?php echo $p[8][11]?>, color: 'green'},{y: <?php echo $p[8][12]?>, color: 'green'}] 
                        }],

                    });
                });
              </script>
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $row['dist_id'];?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: 'NIVEL DE PROGRAMACIÓN VS EVALUACIÓN'
                      },
                      subtitle: {
                        text: 'A Nivel Regional'
                      },
                      xAxis: {
                        categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
                      },
                      yAxis: {
                        title: {
                          text: 'Promedio (%)'
                        }
                      },
                      tooltip: {
                        enabled: false,
                        formatter: function() {
                          return '<b>'+ this.series.name +'</b><br/>'+
                            this.x +': '+ this.y +'%';
                        }
                      },
                      plotOptions: {
                        line: {
                          dataLabels: {
                            enabled: true
                          },
                          enableMouseTracking: false
                        }
                      },
                       series: [
                                {
                                    name: 'PROGRAMACIÓN ACUMULADA EN %',
                                    data: [ <?php echo $p[1][3];?>, <?php echo $p[1][3];?>, <?php echo $p[1][3];?>, <?php echo $p[1][4];?>, <?php echo $p[1][5];?>, <?php echo $p[1][6];?>, <?php echo $p[1][7];?>, <?php echo $p[1][8];?>, <?php echo $p[1][9];?>, <?php echo $p[1][10];?>, <?php echo $p[1][11];?>, <?php echo $p[1][12];?>]
                                },
                                {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $p[2][1];?>, <?php echo $p[2][2];?>, <?php echo $p[2][3];?>, <?php echo $p[2][4];?>, <?php echo $p[2][5];?>, <?php echo $p[2][6];?>, <?php echo $p[2][7];?>, <?php echo $p[2][8];?>, <?php echo $p[2][9];?>, <?php echo $p[2][10];?>, <?php echo $p[2][11];?>, <?php echo $p[2][12];?>]
                                }
                            ]
                    });
                  });
                </script>
                <?php
        
      }

      return $tabla;
    }

    /*------------ Consolidado por Distritales ----------------*/
    public function proyectos_distrital($dep_id,$dist_id){
      $m[1]='Ene.';
      $m[2]='Feb.';
      $m[3]='Mar.';
      $m[4]='Abr.';
      $m[5]='May.';
      $m[6]='Jun.';
      $m[7]='Jul.';
      $m[8]='Agos.';
      $m[9]='Sept.';
      $m[10]='Oct.';
      $m[11]='Nov.';
      $m[12]='Dic.';

      for($i=0; $i <=12 ; $i++) { 
        $p[1][$i]=0; // Prog. // Cumplidos
        $p[2][$i]=0; // Ejec. // En Proceso
        $p[3][$i]=0; // Efi.  // No cumplido 
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo

        $p[9][$i]=0; // Total Programado

      }

      $proyectos=$this->model_evalregional->list_consolidado_distrital($dep_id,$dist_id);
      foreach($proyectos  as $rowp){
        $tabla=$this->componentes($rowp['proy_id']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['proy_pcion_reg'])/100),2);
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['proy_pcion_reg'])/100),2);
          if($p[1][$i]!=0){
            $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
          }
          $p[4][$i]=$m[$i];

          if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
          if ($p[3][$i] > 75 && $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
          if($p[3][$i] > 90 && $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
          if($p[3][$i] > 99 && $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
        }
      }
      
      $cum=$this->model_evalregional->evaluacion_distrital_acumulado($dist_id,1); // Cumplido
      $pro=$this->model_evalregional->evaluacion_distrital_acumulado($dist_id,2); // En Proceso
      $ncum=$this->model_evalregional->evaluacion_distrital_acumulado($dist_id,3); // No Cumplido
      $total_prog=$this->model_evalregional->total_programado_distrital_acumulado($dist_id); // total programado
      $nro_1=0;$nro_2=0;$nro_3=0;
      if(count($cum)!=0){
        $nro_1=$cum[0]['total'];
      }
      if(count($pro)!=0){
        $nro_2=$pro[0]['total'];
      }
      if(count($ncum)!=0){
        $nro_3=$ncum[0]['total'];
      }

      $total=0;
      if(count($total_prog)!=0){
        $total=$total_prog[0]['total'];
      }
      $p[1][0]=$nro_1; // Cumplidos
      $p[2][0]=$nro_2; // En Proceso
      $p[3][0]=$nro_3; // No cumplido 
      $p[9][0]=$nro_3; // Total Programado

      return $p;
    }
    /*===================================================================================*/
    /*------------Sumatoria Temporalidad Productos ------------*/
    public function temporalidad_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado Acumulado %
        $matriz[2][$i]=0; /// Ejecutado Acumulado %
        $matriz[3][$i]=0; /// Eficacia %
      }
      
      $pa=0; $ea=0;$pm=0; $em=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) {

          if($producto[0]['mt_id']==3){
            $pa=$pa+$prod_prog[0][$mp[$i]];
          }
          else{
            $pa=$producto[0]['prod_meta'];
          }

          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $pm=round(((($pa+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %pa
            }
            else{
              $pm=round((($pa/$producto[0]['prod_meta'])*100),2); // %pa
            }
            
          }

          $matriz[1][$i]=round((($pm*$producto[0]['prod_ponderacion'])/100),2); // %
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
         // $ea=$ea+$prod_ejec[0][$mp[$i]];
          if($producto[0]['prod_meta']!=0){

            if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
            }
            else{
              $ea=$prod_ejec[0][$mp[$i]];
            }

            if($producto[0]['tp_id']==1){
              $em=round(((($ea+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %ea
            }
            else{
              $em=round((($ea/$producto[0]['prod_meta'])*100),2); // %ea
            }
            
          }
          $matriz[2][$i]=round((($em*$producto[0]['prod_ponderacion'])/100),2); // %

        }
      }
      
      return $matriz;
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