<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalregionalpei extends CI_Controller {  
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
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('mestrategico/model_mestrategico');

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


    /*--- MENU CONSOLIDADO REGIONAL PEI - OBJETIVOS ESTRATEGICOS ---*/
    public function consolidado_regional_pei($dep_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dep']=$this->model_evalregional->get_dpto($dep_id);
      if(count($data['dep'])!=0){
        $data['dist']=$this->model_evalregional->get_distrital($dep_id);
        $data['obj_estrategicos']=$this->mis_objetivos_estrategicos_reg($dep_id);
        $tmes=$this->model_evaluacion->trimestre();
        $data['tmes']='TRIMESTRE NO DEFINIDO';
        if(count($tmes)!=0){
          $data['tmes']=$this->model_evaluacion->trimestre();
        }

        $data['tr']=($this->tmes*3);
        $data['trimestre']=$this->model_evaluacion->trimestre();
        $this->load->view('admin/reportes_cns/eval_regional/reporte_eval_pei/eval_consolidado_regional_pei', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*--- CONSOLIDADO DE ACCIONES POR REGIONAL---*/
    public function list_consolidado_acc($acc_id,$dep_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dep']=$this->model_evalregional->get_dpto($dep_id);
      if(count($data['dep'])!=0){

        $data['configuracion']=$this->model_proyecto->configuracion_session();
        $data['acc'] = $this->model_mestrategico->get_acciones_estrategicas($acc_id);
        $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['acc'][0]['obj_id']);
        $operaciones = $this->model_evaluacion->list_operaciones_alineados_regional($acc_id,$dep_id);
        $matriz=$this->matriz_operaciones_regional($acc_id,$dep_id);

        $data['graf_acc']=$this->graf_acc_regional($matriz,$operaciones);
        $data['list_operaciones']=$this->list_acc_oregional($matriz,$operaciones);
        $this->load->view('admin/reportes_cns/eval_regional/reporte_eval_pei/mis_operaciones', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*---- GRAFICO EVALUACION POR ACCION X REGIONAL ---*/ 
    public function graf_acc_regional($matriz,$operaciones){
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }
      
      $graf_c=0;$graf_av=0;$graf_nc=0;

      if ($matriz[count($operaciones)+1][13]!=0) {
        $graf_c=round((($matriz[count($operaciones)+1][10]/$matriz[count($operaciones)+1][13])*100),2); // Cumplido 
        $graf_av=round((($matriz[count($operaciones)+1][11]/$matriz[count($operaciones)+1][13])*100),2); // Avance 
        $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido
      }

      $pcion_cum=$graf_c;
      $pcion_ncum=(100-$pcion_cum);

      $class='class="table table-bordered" align=center style="width:100%;"';
      $graf='<div id="container" style="width: 600px; height: 300px; margin: 0 auto"></div>';
      $graf_priori='<div id="container_priori" style="width: 600px; height: 300px; margin: 0 auto"></div>';

      $tabla ='';
      $tabla .='
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <table class="change_order_items" border=1>
                <tr>
                  <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N ACUMULADA DE OPERACIONES TRIMESTRAL</b></font><br>
                    <font FACE="courier new" size="1"><b>'.$trimestre.'</b></font>
                  </center>
                    '.$graf.'
                  </td>
                </tr>
                <tr>
                  <td>
                  <div class="table-responsive">
                    <table '.$class.'>
                      <thead>
                      <tr class="modo1">
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">CUMPLIDO</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"">EN AVANCE</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">NO CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% CUMPLIDO</th>
                        <th style="width:15%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">% NO CUMPLIDO</th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr align=center class="modo1">
                          <td>'.$matriz[count($operaciones)+1][10].'</td>
                          <td>'.$matriz[count($operaciones)+1][11].'</td>
                          <td>'.$matriz[count($operaciones)+1][12].'</td>
                          <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion_cum.' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$pcion_ncum.' %</button></td>
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
                    <font FACE="courier new" size="4"><b>OPERACIONES SEGUN TIPO DE PRIORIDAD</b></font><br><br><br>
                  </center>
                  '.$graf_priori.'
                </td>
              </tr>
              <tr>
                <td>
                <div class="table-responsive">
                  <table '.$class.'>
                    <thead>
                      <tr bgcolor="#1c7368" class="modo1">
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">SIN PRIORIDAD</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;"">CON PRIORIDAD</th>
                        <th style="width:14%; background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">TOTAL OPERACIONES</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr align=center class="modo1">
                        <td>'.$matriz[count($operaciones)+1][7].'</td>
                        <td>'.$matriz[count($operaciones)+1][8].'</td>
                        <td>'.$matriz[count($operaciones)+1][9].'</td>
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
            <script >
            $(document).ready(function() {  
               Highcharts.chart('container', {
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
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_priori',
                defaultSeriesType: 'column'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: ['', '', '']
              },
              yAxis: {
                min: 0,
                title: {
                  text: 'Operaciones'
                }
              },
              legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
              },
              tooltip: {
                formatter: function() {
                  return ''+
                    this.x +': '+ this.y +' (%).';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.04,
                  borderWidth: 0
                }
              },
              series: [
                {
                  name: 'SIN PRIORIDAD',
                  data: [<?php echo $matriz[count($operaciones)+1][7];?>],
                  color: '#ef5318',
                },
                {
                  name: 'CON PRIORIDAD',
                  data: [<?php echo $matriz[count($operaciones)+1][8];?>],
                  color: '#97e097',
                },
                {
                  name: 'TOTAL OPERACIONES',
                  data: [<?php echo $matriz[count($operaciones)+1][9];?>]
                }
              ]
            });
          });
        </script>
        <?php
      return $tabla;
    }

    /*------ LISTA DE ACCIONES ESTRATEGICAS - REGIONAL -------*/
    public function list_acc_oregional($matriz,$operaciones){
      $tabla='';
      $tabla .='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="jarviswidget jarviswidget-color-darken" >
                <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md"><strong>'.count($operaciones).'.- OPERACIONES ALINEADOS</strong></h2>  
                </header>
            <div>
              <div class="widget-body no-padding">
                <table id="dt_basic" class="table table table-bordered" width="100%" border=1>
                  <thead>
                    <tr>
                      <th style="width:1%;">NRO</th>
                      <th style="width:5%;">APERTURA PROGRAMATICA</th>
                      <th style="width:3%;">TP</th>
                      <th style="width:5%;">COMPONENTE/SERVICIO</th>
                      <th style="width:20%;">OPERACI&Oacute;N</th>
                      <th style="width:3%;">TIPO</th>
                      <th style="width:3%;">META</th>
                      <th style="width:15%;">INDICADOR</th>
                      <th style="width:5%;">PRIORIDAD</th>
                      <th style="width:5%;">PONDERACI&Oacute;N</th>
                      <th style="width:5%;">PROG.</th>
                      <th style="width:5%;">EVAL.</th>
                      <th style="width:5%;">EFI.%</th>
                    </tr>
                  </thead>
                  <tbody>';
                  //$nro_prio=0;$nro_nprio=0;
                  for ($i=1; $i <=count($operaciones) ; $i++) { 
                    $tabla.='<tr>';
                    for ($j=1; $j <=13 ; $j++) {
                      if($j==9){
                        if($matriz[$i][$j]==1){
                        //  $nro_prio++;
                          $tabla.='<td bgcolor="#dff1af"><span class="badge bg-color-greenLight">Con Prioridad</span></td>';
                        }
                        else{
                        //  $nro_nprio++;
                          $tabla.='<td bgcolor="#f9eed7"><span class="badge bg-color-orange">Sin Prioridad</span></td>';
                        }
                      }
                      elseif($j==13){
                        $tabla.='<td>'.$matriz[$i][$j].' %</td>';
                      }
                      else{
                        $tabla.='<td>'.$matriz[$i][$j].'</td>';
                      }
                      
                    }
                    $tabla.='</tr>';
                  }
        $tabla.=' </tbody>
                </table>
                <hr>
                  <div class="col-sm-4">
                    <div class="well well-sm bg-color-orange txt-color-white text-center">
                      <h5>OPERACIONES SIN PRIORIDAD</h5>
                      <code><b>'.$matriz[count($operaciones)+1][7].'</b></code>
                    </div>
                  </div>
    
                  <div class="col-sm-4">
                    <div class="well well-sm bg-color-teal txt-color-white text-center">
                      <h5>OPERACIONES CON PRIORIDAD</h5>
                      <code><b>'.$matriz[count($operaciones)+1][8].'</b></code>
                    </div>
                  </div>
    
                  <div class="col-sm-4">
                    <div class="well well-sm bg-color-darken txt-color-white text-center">
                      <h5>TOTAL OPERACIONES</h5>
                      <code><b>'.$matriz[count($operaciones)+1][9].'</b></code>
                    </div>
                  </div>

                <hr><br>
              </div>
            </div>
          </div>
        </article>';

      return $tabla;
    }

    /*------ Matriz Operaciones - Regional -------*/
    public function matriz_operaciones_regional($acc_id,$dep_id){
      $operaciones = $this->model_evaluacion->list_operaciones_alineados_regional($acc_id,$dep_id); /// MIS OPERACIONES
      $ope_prioritarios = $this->model_evaluacion->list_operaciones_alineados_prioridad_regional($acc_id,$dep_id,1); /// MIS OPERACIONES PRIORITARIOS 1
      $ope_nprioritarios = $this->model_evaluacion->list_operaciones_alineados_prioridad_regional($acc_id,$dep_id,0); /// MIS OPERACIONES NO PRIORITARIOS 0
      
      for ($i=1; $i <=count($operaciones)+1 ; $i++) { 
        for ($j=1; $j <=13 ; $j++) { 
          $mope[$i][$j]=0;
        }
      }

      $vfinal=0;
      if($this->tmes==1){
        $vfinal=3;
      }
      elseif ($this->tmes==2) {
       $vfinal=6; 
      }
      elseif ($this->tmes==3) {
        $vfinal=9;
      }
      elseif ($this->tmes==4) {
        $vfinal=12;
      }

      $nro=0;$suma_pcion=0; $sum_prog=0;$sum_eval=0;
      $sum_cumplidos=0;$sum_avance=0;$sum_ncumplidos=0;$nro_prog=0;
      foreach($operaciones as $row){
        $prog=$this->model_evaluacion->rango_programado_trimestral_productos($row['prod_id'],$vfinal);
        $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($row['prod_id'],$vfinal);
        $total_prog=0;
        $total_eval=0;
        if(count($prog)!=0){
          $total_prog=$prog[0]['trimestre'];
        }
        if(count($eval)!=0){
          $total_eval=$eval[0]['trimestre'];
        }
        $efi=0;
        if($total_prog!=0){
          $efi=round((($total_eval/$total_prog)*100),2);
        }

        $nro++;
        $mope[$nro][1]=$nro;
        $mope[$nro][2]=''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'';
        $mope[$nro][3]=''.strtoupper($row['tp_sigla']).'';
        $mope[$nro][4]=''.strtoupper($row['com_componente']).'';
        $mope[$nro][5]=''.strtoupper($row['prod_producto']).'';
        $mope[$nro][6]=''.strtoupper($row['indi_descripcion']).'';
        $mope[$nro][7]=''.$row['prod_meta'].'';
        $mope[$nro][8]=''.strtoupper($row['prod_indicador']).'';
        $mope[$nro][9]=''.$row['prod_priori'].'';
        $mope[$nro][10]=''.$row['prod_ponderacion_pei'].'';
        $mope[$nro][11]=$total_prog;
        $mope[$nro][12]=$total_eval;
        $mope[$nro][13]=$efi;

        if($total_prog!=0){
          $nro_prog++;
          if($efi==100){
            $sum_cumplidos++;
          }elseif($efi==0){
            $sum_ncumplidos++;
          }
          else{
            $sum_avance++; 
          }
        }
      }

      $nro++;

      $mope[$nro][7]=count($ope_nprioritarios);
      $mope[$nro][8]=count($ope_prioritarios);
      $mope[$nro][9]=count($operaciones);

      $mope[$nro][10]=$sum_cumplidos;
      $mope[$nro][11]=$sum_avance;
      $mope[$nro][12]=$sum_ncumplidos;
      $mope[$nro][13]=$nro_prog;

      return $mope;
    }

    /*-------- LISTA DE OBJETIVOS ESTRATEGICOS - REGIONAL ----------*/
    public function mis_objetivos_estrategicos_reg($dep_id){
      $objetivos = $this->model_mestrategico->list_objetivos_estrategicos(); /// OBJETIVOS ESTRATEGICOS
      $tabla ='';
      $nro=0;
      $tabla.=' <div class="widget-body no-padding">
                  <div class="panel-group smart-accordion-default" id="accordion-2">';
                    foreach($objetivos  as $row){
                      $acciones = $this->model_mestrategico->list_acciones_estrategicas($row['obj_id']); /// ACCIONES ESTRATEGICAS
                      $nro++;
                      $panel='panel-collapse collapse';
                      $colapsed='collapsed';
                      if($nro==1){
                        $panel='panel-collapse collapse in';
                        $colapsed='';
                      }
                      $tabla.='<div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#'.$nro.'" class="'.$colapsed.'"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>'.$row['obj_codigo'].'.- '.$row['obj_descripcion'].'</a></h4>
                                </div>
                                <div id="'.$nro.'" class="'.$panel.'">
                                  <div class="panel-body">
                                    ACCI&Oacute;N ESTRATEGICA DE CORTO PLAZO<br> 
                                    <table class="table table-bordered table-condensed">
                                      <thead>
                                        <tr>
                                          <th style="width:1%;">Nro</th>
                                          <th style="width:40%;">ACCI&Oacute;N ESTRATEGICA</th>
                                          <th style="width:5%;">PONDERACI&Oacute;N</th>
                                          <th style="width:5%;">NRO. OPE.</th>
                                          <th style="width:35%;">VINCULACI&Oacute;N AL PDES</th>
                                          <th style="width:5%;"></th>
                                        </tr>
                                      </thead>
                                      <tbody>';
                                      $nro_a=0;
                                        foreach($acciones  as $rowa){
                                          $pdes=$this->model_proyecto->datos_pedes($rowa['pdes_id']);
                                          $nro_a++;
                                          $tabla.=
                                          '<tr>
                                            <td>'.$nro_a.'</td>
                                            <td>'.$rowa['acc_codigo'].'.- '.$rowa['acc_descripcion'].'</td>
                                            <td>'.$rowa['acc_pcion'].'%</td>
                                            <td align=center><br><span class="badge bg-color-blueLight">'.count($this->model_evaluacion->list_operaciones_alineados_regional($rowa['acc_id'],$dep_id)).'</span></td>
                                            <td>
                                              <b>PILAR :</b> '.$pdes[0]['pilar'].'<br>
                                              <b>META :</b> '.$pdes[0]['meta'].'<br>
                                              <b>RESULTADO :</b> '.$pdes[0]['resultado'].'<br>
                                              <b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'<br>
                                            </td>
                                            <td>
                                              <br><a href="'.site_url("").'/rep_eval_pei/dep_operaciones/'.$rowa['acc_id'].'/'.$dep_id.'" class="btn btn-primary" title="MIS OPERACIONES ALINEADAS">MIS OPERACIONES</a>
                                            </td>
                                          </tr>';
                                        }
                                        $tabla.='
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>';
                    }
                $tabla.='
                  </div>
                </div>';
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