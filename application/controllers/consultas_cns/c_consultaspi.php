<?php
class C_consultaspi extends CI_Controller {  
    public $rol = array('1' => '1','2' => '10'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->ppto= $this->session->userData('verif_ppto'); 
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('ejecucion_finpi');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /// Menu Principal Ejecucion de Proyectos Inversion 
    public function ejecucion_proyectos(){
      $data['menu']=$this->menu(10);
      $data['style']=$this->style();

      //// matriz de proyectos
      $data['nro_reg']=count($this->model_ptto_sigep->list_regionales());
      $data['matriz_reg']=$this->ejecucion_finpi->matriz_detalle_proyectos_clasificado_regional();

      //// matriz consolidado de partidas
      $data['nro_part']=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_institucional());
      $data['matriz_partidas']=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_institucional(); /// Matriz consolidado de partidas Nacional
      //$data['tabla_partidas']=$this->ejecucion_finpi->tabla_consolidado_de_partidas($data['matriz_partidas'],$data['nro_part'],0); /// Tabla Clasificacion de partidas asignados por regional
      //$tabla_consolidado_grafico=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico


      $data['principal']=$this->menu_principal($data['nro_reg'],$data['matriz_reg'],$data['nro_part'],$data['matriz_partidas']); /// Menu principal

      $this->load->view('admin/reportes_cns/repejecucion_pi/menu_consultas_pi', $data);
    }

    //// menu principal de ejecucion Institucional
    public function menu_principal($nro,$regional,$nro_part,$partidas){
        $tabla='';

        $tabla.='<article class="col-sm-12">
                <input name="base" type="hidden" value="'.base_url().'">
                <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
                <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
                <input name="gestion" type="hidden" value="'.$this->gestion.'">

                <!-- new widget -->
                <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                        <h2>Ejecucion Presupuestaria INSTITUCIONAL '.$this->gestion.'</h2>
                        <div id="cabecera" style="display: none">'.$this->ejecucion_finpi->cabecera_reporte_grafico('CONSOLIDADO INSTITUCIONAL').'</div>
                        <div id="tabla_impresion_ejecucion" style="display: none">
                          '.$this->tabla_detalle_institucional_impresion($regional,$nro,0).'
                        </div>

                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CONSOLIDADO REGIONAL</span></a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">DISTRIBUCIÓN</span></a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EJECUCION MENSUAL</span></a>
                            </li>
                        </ul>

                    </header>

                    <!-- widget div-->
                    <div class="no-padding">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            test
                        </div>
                        <!-- end widget edit box -->

                        <div class="widget-body">
                            <!-- content -->
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
                                    <div class="row no-space">
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                           <div id="graf_proyectos"><div id="proy_institucional" style="width: 1100px; height: 700px; margin: 0 auto"></div></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 show-stats">
                                            <div class="row">';
                                                for ($i=0; $i <$nro ; $i++) { 
                                                    $tabla.='
                                                    <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> '.$regional[$i][1].' <span class="pull-right"> <b>'.$regional[$i][9].'</b>% <a href="#" data-toggle="modal" data-target="#modal_cumplimiento_pi_regional" class="btn btn-default" name="'.$regional[$i][0].'"  onclick="nivel_cumplimiento_pi_regional('.$regional[$i][0].');" title="NIVEL DE CUMPLIMIENTOACCION DE LA REGIONAL '.$regional[$i][1].'"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="25" HEIGHT="20"/></a></span> </span>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-color-blueDark" style="width: '.$regional[$i][9].'%; height:100%"></div>
                                                        </div>
                                                    </div>';
                                                }
                                            $tabla.='
                                                <span class="show-stat-buttons"> 
                                                    <span class="col-xs-12 col-sm-6 col-md-4 col-lg-4"> <a onClick="imprimir_proyectos()" style="font-size: 9.5px;font-family: Arial;" class="btn btn-default btn-block hidden-xs"><b>IMPRIMIR EJECUCION</b></a></span> 
                                                    <span class="col-xs-12 col-sm-6 col-md-4 col-lg-4"> <a href="javascript:abreVentana(\''.site_url("").'/reporte_ejecucion_pi_institucional/0\');" style="font-size: 9.5px;font-family: Arial;" class="btn btn-default btn-block hidden-xs"><b>IMPRIMIR DETALLE</b></a></span> 
                                                    <span class="col-xs-12 col-sm-6 col-md-4 col-lg-4"> <a href="javascript:abreVentana(\''.site_url("").'/reporte_ejecucion_pi_institucional/1\');" style="font-size: 9.5px;font-family: Arial;" class="btn btn-default btn-block hidden-xs"><b>EXPORTAR</b></a></span> 
                                                </span>

                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                    '.$this->tabla_detalle_proyectos_clasificado_institucional($regional,$nro,0,0).'
                                </div>
                                <!-- end s1 tab pane -->

                                <div class="tab-pane fade" id="s2">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div id="graf_proyectos1"><div id="distribucion_proyectos" style="width: 900px; height: 600px; margin: 0 auto"></div></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div id="graf_proyectos2"><div id="distribucion_ppto" style="width: 900px; height: 600px; margin: 0 auto"></div></div>
                                    </div>
                                    <hr>
                                    '.$this->tabla_detalle_proyectos_clasificado_institucional($regional,$nro,0,1).'
                                </div>
                                <!-- end s2 tab pane -->

                                <div class="tab-pane fade" id="s3">
                                    <div id="partidas" style="width: 1000px; height: 750px; margin: 0 auto"></div>
                                     <div class="row">
                                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="jarviswidget jarviswidget-color-darken" >
                                          <header>
                                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                              <h2 class="font-md"><strong>DETALLE DE EJECUCION PRESUPUESTARIA '.$this->gestion.' </strong></h2>  
                                          </header>
                                            <div>

                                            <div class="widget-body no-padding">
                                            '.$this->ejecucion_finpi->tabla_consolidado_de_partidas($partidas,$nro_part,0).'
                                            </div>
                                        </div>
                                        </article>
                                      </div>
                                    </div>
                                </div>
                                <!-- end s3 tab pane -->

                                
                            </div>
                                
                            <!-- end content -->
                        </div>

                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->

            </article>';
        return $tabla;
    }






/*--- GET TABLA DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL VISTA---*/
public function tabla_detalle_proyectos_clasificado_institucional($matriz,$nro,$tp_rep,$graf){
    /// tp_rep : 0 normal
    /// tp_rep : 1 impresion

    /// graf : 0 ejecucion
    /// graf : 1 Distribucion (proyectos y presupuesto)

    if($graf==0){
        $columna_ejec='bgcolor=#e4e8fd';
        $columna_distribucion='';
    }
    else{
        $columna_ejec='';
        $columna_distribucion='bgcolor=#e4e8fd';
    }

  $tabla='';
  $tabla.='
      <style>
        table{font-size: 10px;
        width: 100%;
        max-width:1550px;;
        overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>
      <center>

        <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" >
              <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md"><strong>DETALLE DE EJECUCION PRESUPUESTARIA '.$this->gestion.' </strong></h2>  
              </header>
                <div>

                <div class="widget-body no-padding">
                  <table class="table table-bordered" style="width:99%;">
                    <thead>
                      <tr>
                        <th style="width:10%;">DEPARTAMENTO</th>
                        <th style="width:7%;">N° DE PROYECTOS</th>
                        <th style="width:7%;">PORCENTAJE DISTRIBUCIÓN DE PROYECTOS</th>
                        <th style="width:10%;">PPTO. INICIAL '.$this->gestion.'</th>
                        <th style="width:10%;">PPTO. MODIFICADO '.$this->gestion.'</th>
                        <th style="width:10%;">PPTO. VIGENTE '.$this->gestion.'</th>
                        <th style="width:10%;">PPTO. EJECUTADO '.$this->gestion.'</th>
                        <th style="width:5%;">PORCENTAJE EJECUCION '.$this->gestion.'</th>
                        <th style="width:5%;">PORCENTAJE DISTRIBUCIÓN DE PRESUPUESTO</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $sum_asignado=0;
                    $sum_ejecutado=0;
                      for ($i=0; $i <$nro ; $i++) { 
                        $tabla.='
                          <tr>
                            <td style="font-size: 12px;font-family: Arial;"><b>'.$matriz[$i][1].'</b></td>
                            <td align=right>'.$matriz[$i][3].'</td>
                            <td style="font-size: 12px;font-family: Arial;" align=right '.$columna_distribucion.'><b>'.$matriz[$i][4].' %</b></td>
                            <td align=right>Bs. '.number_format($matriz[$i][5], 2, ',', '.').'</td>
                            <td align=right>Bs. '.number_format($matriz[$i][6], 2, ',', '.').'</td>
                            <td align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                            <td align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                            <td style="font-size: 12px;font-family: Arial;" align=right '.$columna_ejec.'><b>'.round($matriz[$i][9],1).' %</b></td>
                            <td style="font-size: 12px;font-family: Arial;" align=right '.$columna_distribucion.'><b>'.$matriz[$i][10].' %</b></td>
                          </tr>';   
                          $sum_asignado=$sum_asignado+$matriz[$i][7];
                          $sum_ejecutado=$sum_ejecutado+$matriz[$i][8];
                      }
                      $cumplimiento=0;
                      if($sum_asignado!=0){
                        $cumplimiento=round(($sum_ejecutado/$sum_asignado)*100,2);
                      }
                      $tabla.='
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-size: 12px;font-family: Arial;" align=right><b>Bs. '.number_format($sum_asignado, 2, ',', '.').'</b></td>
                        <td style="font-size: 12px;font-family: Arial;" align=right><b>Bs. '.number_format($sum_ejecutado, 2, ',', '.').'</b></td>
                        <td style="font-size: 12px;font-family: Arial;" align=right><b>'.$cumplimiento.'%</b></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  </div>

                </div>
            </div>
            </article>
        </div>
      </center>';

  return $tabla;
}


/*--- GET DETALLE DE EJECUCION PRESUPUESTARIA DE PROYECTOS DE INVERSION REGIONAL, INSTITUCIONAL---*/
public function get_detalle_ejecucion_ppto_pi_regional(){
    if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $regional=$this->model_proyecto->get_departamento($dep_id);
    
        $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('REGIONAL '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion);

        $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($dep_id)); /// nro de proyectos
        $matriz_proyectos=$this->ejecucion_finpi->matriz_proyectos_inversion_regional($dep_id); /// proyectos
        $tabla_detalle_ejec_impresion=$this->ejecucion_finpi->tabla_detalle_proyectos_impresion($matriz_proyectos,$nro_proy,1); /// Tabla Impresion tabla


        $tabla='';
        $tabla.='
        <article class="col-sm-12">
            <!-- new widget -->
            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <header>
                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                    <h2>Ejecucion Presupuestaria - REGIONAL : '.strtoupper($regional[0]['dep_departamento']).'</h2>
                    <div id="cabecera_consulta" style="display: none">'.$cabecera_grafico.'</div>
                    <div id="tabla_impresion_ejecucion_consulta" style="display: none">
                      '.$tabla_detalle_ejec_impresion.'
                    </div>
                    <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">
                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CONSOLIDADO REGIONAL</span></a>
                        </li>
                    </ul>

                </header>

                <!-- widget div-->
                <div class="no-padding">
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        test
                    </div>
                    <!-- end widget edit box -->

                    <div class="widget-body">
                        <!-- content -->
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
                                <div class="row no-space">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                       <div id="graf_proyectos_consulta"><div id="proyectos" style="width: 1100px; height: 700px; margin: 0 auto"></div></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 show-stats">
                                        <div class="row">';
                                            for ($i=0; $i <$nro_proy; $i++) { 
                                                $tabla.='
                                                <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> '.$matriz_proyectos[$i][10].' <span class="pull-right"> <b>'.$matriz_proyectos[$i][15].'</b>%  <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$matriz_proyectos[$i][2].'\');" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></span> </span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-color-blueDark" style="width: '.$matriz_proyectos[$i][15].'%; height:100%"></div>
                                                    </div>
                                                </div>';
                                            }
                                        $tabla.='
                                            <span class="show-stat-buttons"> 
                                                <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a onClick="imprimir_proyectos_consultas()" class="btn btn-default btn-block hidden-xs">IMPRIMIR EJECUCIÓN</a></span> 
                                                <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a href="javascript:abreVentana(\''.site_url("").'/reporte_detalle_ppto_pi/'.$dep_id.'/3\');" class="btn btn-default btn-block hidden-xs">IMPRIMIR DETALLE</a></span> 
                                            </span>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <!-- end content -->
                    </div>

                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

        </article>';

        $result = array(
        'respuesta' => 'correcto',
        'tabla' => $tabla,
        'regional' => strtoupper($regional[0]['dep_departamento']),

        'nro_proy'=>$nro_proy,
        'matriz_proy'=>$matriz_proyectos,
      );

        echo json_encode($result);
    }else{
    show_404();
    }
}





/*--- GET TABLA DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL IMPRESION ---*/
  public function tabla_detalle_institucional_impresion($matriz,$nro,$tipo){
    /// tipo : 0 Ejecucion de ppto de proyectos
    /// tipo : 1 Distribucion nro de proyectos
    /// tipo : 2 Distribucion presupuesto

    $tabla='';
    if($tipo==0){
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:30%;">DEPARTAMENTO</th>
            <th style="width:15%;">PRESUPUESTO <br> ASIGNADO '.$this->gestion.'</th>
            <th style="width:15%;">PRESUPUESTO <br> EJECUTADO '.$this->gestion.'</th>
            <th style="width:10%;">(%) EJECUCIÓN '.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_asig=0;
        $ppto_ejec=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:30%;">'.$matriz[$i][1].'</td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                <td style="width:10%;" align=right><b>'.$matriz[$i][9].' %</b></td>
              </tr>';
              $ppto_asig=$ppto_asig+$matriz[$i][7];
              $ppto_ejec=$ppto_ejec+$matriz[$i][8];
          }

          $cum_inst=0;
          if($ppto_asig!=0){
            $cum_inst=round((($ppto_ejec/$ppto_asig)*100),2);
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td align=right>Bs. '.number_format($ppto_asig, 2, ',', '.').'</td>
            <td align=right>Bs. '.number_format($ppto_ejec, 2, ',', '.').'</td>
            <td align=right><b>'.$cum_inst.'%</b></td>
          </tr>
      </table>
      </center>';
    }
    elseif($tipo==1){
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:40%;">DEPARTAMENTO</th>
            <th style="width:10%;">N° DE PROYECTOS</th>
            <th style="width:10%;">PORCENTAJE DISTRIBUCIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro_proy=0;
        $disribucion=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:40%;">'.$matriz[$i][1].'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][3].'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][4].' %</td>
              </tr>';
              $nro_proy=$nro_proy+$matriz[$i][3];
              $disribucion=$disribucion+$matriz[$i][4];
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td align=right>'.$nro_proy.'</td>
            <td align=right>'.$disribucion.' %</td>
          </tr>
      </table>
      </center>';
    }
    else{
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:40%;">DEPARTAMENTO</th>
            <th style="width:10%;">PPTO. ASIGNADO</th>
            <th style="width:10%;">PORCENTAJE DISTRIBUCIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_total=0;
        $disribucion=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:40%;">'.$matriz[$i][1].'</td>
                <td style="width:10%;" align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][10].' %</td>
              </tr>';
              $ppto_total=$ppto_total+$matriz[$i][7];
              $disribucion=$disribucion+$matriz[$i][10];
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td align=right>'.$ppto_total.'</td>
            <td align=right>'.$disribucion.' %</td>
          </tr>
      </table>
      </center>';
    }

    return $tabla;
  } 


/*--- REPORTE DETALLE EJECUCION PRESUPUESTARIA PROY INVERSION INSTITUCIONAL ---*/
public function reporte_detalle_ejec_ppto_pi_institucional($tp_reporte){
/// tp_reporte : 0 pdf
/// tp_reporte : 1 excel
$nro=count($this->model_ptto_sigep->list_regionales());
$matriz=$this->ejecucion_finpi->matriz_detalle_proyectos_clasificado_regional();

if($tp_reporte==0){ /// pdf
    $data['titulo_pie_rep']='Ficha_Tecnica_PI_INSTITUCIONAL '.$this->gestion;
    $titulo_reporte='DETALLE EJECUCIÓN PRESUPUESTARIA - INSTITUCIONAL';
    $data['cabecera']=$this->ejecucion_finpi->cabecera_ficha_tecnica($titulo_reporte);
    $data['pie']=$this->ejecucion_finpi->pie_ficha_tecnica();

    $tabla='';
    $tabla.='
        <table border="0.2" cellpadding="0" cellspacing="0" width:100%; class="tabla">
        <thead>
          <tr>
            <th style="height:15px; width:14%; text-align:center">DEPARTAMENTO</th>
            <th style="width:8%; text-align:center">N° DE PROYECTOS</th>
            <th style="width:8%; text-align:center">PORCENTAJE DISTRIBUCIÓN</th>
            <th style="width:12%; text-align:center">PPTO. INICIAL '.$this->gestion.'</th>
            <th style="width:12%; text-align:center">PPTO. MODIFICADO '.$this->gestion.'</th>
            <th style="width:12%; text-align:center">PPTO. VIGENTE '.$this->gestion.'</th>
            <th style="width:12%; text-align:center">PPTO. EJECUTADO '.$this->gestion.'</th>
            <th style="width:11%; text-align:center">PORCENTAJE EJECUCION '.$this->gestion.'</th>
            <th style="width:11%; text-align:center">PORCENTAJE DISTRIBUCIÓN PPTO.</th>
          </tr>
        </thead>
        <tbody>';
        $sum_asignado=0;
        $sum_ejecutado=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="height:14px;"><b>'.$matriz[$i][1].'</b></td>
                <td align=right>'.$matriz[$i][3].'</td>
                <td style="font-size: 10px;font-family: Arial;" align=right><b>'.$matriz[$i][4].' %</b></td>
                <td align=right>Bs. '.number_format($matriz[$i][5], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][6], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                <td style="font-size: 10px;font-family: Arial;" align=right><b>'.round($matriz[$i][9],1).' %</b></td>
                <td style="font-size: 10px;font-family: Arial;" align=right><b>'.$matriz[$i][10].' %</b></td>
              </tr>';   
              $sum_asignado=$sum_asignado+$matriz[$i][7];
              $sum_ejecutado=$sum_ejecutado+$matriz[$i][8];
          }
          $cumplimiento=0;
          if($sum_asignado!=0){
            $cumplimiento=round(($sum_ejecutado/$sum_asignado)*100,2);
          }
          $tabla.='
          <tr>
            <td style="height:12px;"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-size: 9px;font-family: Arial;" align=right><b>Bs. '.number_format($sum_asignado, 2, ',', '.').'</b></td>
            <td style="font-size: 9px;font-family: Arial;" align=right><b>Bs. '.number_format($sum_ejecutado, 2, ',', '.').'</b></td>
            <td style="font-size: 9px;font-family: Arial;" align=right><b>'.$cumplimiento.'%</b></td>
            <td></td>
          </tr>
        </tbody>
        </table>';

        $data['datos_proyecto']=$tabla;

        $this->load->view('admin/ejecucion_pi/reporte_ficha_tecnica_pi', $data);
    }
    else{ /// Excel

        




    }

}



/// ---- STYLE -----
public function style(){
  $tabla='';

  $tabla.='   
  <style>
    table{font-size: 10px;
        width: 100%;
        max-width:1550px;;
        overflow-x: scroll;
        .
    }
    th{
        padding: 1.4px;
        text-align: center;
        font-size: 10px;
    }
    #mdialTamanio_regional{
        width: 90% !important;
    }
</style>';

  return $tabla;
}

//// Genera Menu
public function menu($mod){
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

/*----------------------------------------*/
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

}