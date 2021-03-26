<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalinstitucional extends CI_Controller {  
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


    /*------ MENU INSTITUCIONAL EVALUACIÓN-EFICACIA -------*/
    public function nacional_institucional(){
      $data['menu']=$this->menu(7); //// genera menu
      $data['trimestre']=$this->model_evaluacion->trimestre();
      $data['tr']=($this->tmes*3);

      /*---------- Lista de Evaluación por Programas -----------*/ 
      $data['eval_programas']=$this->cuadro_comparativo_programas_evaluado()[0];
      $data['graf_eval_programas']=$this->evaluacion_operaciones_institucional();
      $data['print_eval_institucional']=$this->print_evaluacion_operaciones_institucional($this->cuadro_comparativo_programas_evaluado()[1]);

      /*---------- Lista de Regionales -----------*/ 
      $data['eval_regional']=$this->evaluacion_regionales();
      $data['matriz']=$this->matriz_evaluacion_regionales();

      $data['print_eval_regional']=$this->print_evaluacion_operaciones_regionales($data['matriz'],$this->cuadro_comparativo_programas_evaluado()[1]);

      $this->load->view('admin/reportes_cns/eval_nacional/poa/eval_consolidado_institucional', $data);
    }


    /*------- MATRIZ CUADRO DE EVALUACION POR REGIONALES -------*/
    public function matriz_evaluacion_regionales(){
      $regionales=$this->model_proyecto->list_departamentos();
      for ($i=1; $i <=count($regionales)-1 ; $i++) { 
        for ($j=1; $j <=6 ; $j++) { 
          $m[$i][$j]=0;
        }
      }

      $nro=0;
      foreach($regionales as $row){
        if($row['dep_estado']!=0){
          $nro++;
          $eval_acu=$this->matriz_evaluacion_regional_acumulado($row['dep_id']); /// Evalucion Trimestral Acumulado
          $m[$nro][1]=strtoupper($row['dep_departamento']); // Regional

          $m[$nro][2]=$eval_acu[1]; // cumplido
          $m[$nro][3]=$eval_acu[2]; // Avance
          $m[$nro][4]=$eval_acu[3]; // No cumplido
          $m[$nro][5]=$eval_acu[7]; // Total Prog
          $m[$nro][6]=$eval_acu[4]; // Total Eval

          $m[$nro][7]=$eval_acu[5]; // % cum
          $m[$nro][8]=$eval_acu[6]; // % ncum

        }
      }
      
      return $m;
    }

    /*--------------- EVALUACION DE REGIONALES ----------------*/
    public function evaluacion_regionales(){
      $regionales=$this->model_proyecto->list_departamentos();
      $nroc=0;
      $tabla =''; 
          $nro_cum=0;$nro_pro=0;$nro_ncum=0;$total_prog=0;$total_eval=0;
          $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0; $pcion_reg=0;
          $tabla .='
              <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                  <thead>
                    <tr class="modo1">
                    <th colspan="3" style="background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">DATOS DE LA REGIONAL</th>
                    <th colspan="7" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EVALUACI&Oacute;N TRIMESTRAL</th>
                    <th colspan="7" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EVALUACI&Oacute;N TRIMESTRAL ACUMULADO</th>
                  </tr>
                  <tr class="modo1">
                    <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF; height:12px; text-align: center;">#</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">REGIONAL</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">PONDERACI&Oacute;N</th>

                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EN AVANCE</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">NO CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL EVAL.</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% NO CUMPLIDO</th>

                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EN AVANCE</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">NO CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL EVAL.</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% CUMPLIDO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% NO CUMPLIDO</th>
                  </tr>
                  </thead>
                  <tbody>';
          foreach($regionales as $row){
            if($row['dep_estado']!=0){
              $eval=$this->matriz_evaluacion_regional_trimestre($row['dep_id']); /// Evaluacion Trimestral
              $eval_acu=$this->matriz_evaluacion_regional_acumulado($row['dep_id']); /// Evalucion Trimestral Acumulado

              $nroc++;
                $tabla .='<tr class="modo1" title="COMPONENTE UNIDAD ORGANIZACIONAL ('.$nroc.') - '.$row['dep_id'].'">';
                  $tabla .='<td style="width: 1%; text-align: center; height:12px;">'.$nroc.'</td>';
                  $tabla .='<td style="width: 5%; text-align: left;"><b>'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</b></td>';
                  $tabla .='<td style="width: 5%; text-align: right;">'.$row['dep_pcion'].' %</td>';
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

                    $pcion_a=round((($nro_cum_a/$total_prog_a)*100),2);
                    $npcion_a=(100-$pcion_a);
                  }

                $pcion_reg=$pcion_reg+$row['dep_pcion'];
            }
        }
        $tabla .='</tbody>
            <tr class="modo1" align=right>
              <td colspan="2" style="height:12px;">TOTAL : </td>
              <td>'.$pcion_reg.' %</td>
              <td>'.$nro_cum.'</td>
              <td>'.$nro_pro.'</td>
              <td>'.$nro_ncum.'</td>
              <td>'.$total_prog.'</td>
              <td>'.$total_eval.'</td>
              <td></td>
              <td></td>
              <td>'.$nro_cum_a.'</td>
              <td>'.$nro_pro_a.'</td>
              <td>'.$nro_ncum_a.'</td>
              <td>'.$total_prog_a.'</td>
              <td>'.$total_eval_a.'</td>
              <td></td>
              <td></td>
            </tr>
            </table>
          </div>';

      return $tabla;
    }


    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACTUAL --------------*/
    public function matriz_evaluacion_regional_trimestre($dep_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        
        $cum=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,1,$this->tmes);
        $proc=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,2,$this->tmes);
        $ncum=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,3,$this->tmes);
        $total_prog=$this->model_evalnacional->total_institucional_regional($dep_id,$this->tmes); // total programado prod

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
    public function matriz_evaluacion_regional_acumulado($dep_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,1,$i);
        $proc=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,2,$i);
        $ncum=$this->model_evalnacional->evaluacion_institucional_regional($dep_id,3,$i);
        $total_prog=$this->model_evalnacional->total_institucional_regional($dep_id,$i); // total programado prod

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

    /*---- IMPRIMIR EVALUACION TRIMESTRAL Y ACUMULADO DE OPERACIONES POR REGIONAL ---*/ 
    public function print_evaluacion_operaciones_regionales($matriz,$eval){
      $trimestre=$this->model_evaluacion->trimestre();
      $mes = $this->mes_nombre();
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
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="55px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b>'.$this->session->userdata('entidad').'</b></td>
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
                $tabla .='<center><FONT FACE="courier new" size="2"><b>CUADRO DE EVALUACI&Oacute;N ACUMULADA DE OPERACIONES POR REGIONALES <br>AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>';
                $tabla.=''.$this->print_evaluacion_acu_operaciones_regionales($matriz).'

                <table class="change_order_items" border=1>
                  <tr>
                    <td><center><FONT FACE="courier new" size="2"><b>CUADRO CONSOLIDADO DE CUMPLIMIENTO POR REGIONAL<b/></FONT></center></td>
                  </tr>
                  <tr>
                    <td><div id="container_reg_cum_print" style="width: 900px; height: 350px; margin: 1 auto"></div></td>
                  </tr>
                </table>
                <table class="change_order_items" border=1>
                  <tr>
                    <td><div id="container_acumulado2" style="width: 750px; height: 350px; margin: 0 auto"></div></td>
                  </tr>
                </table>
                ';

        ?>
          <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
          <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container_acumulado2', {
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

    /*------- PARA IMPRIMIR OPERACIONES POR REGIONALES ------*/
    public function print_evaluacion_acu_operaciones_regionales($matriz){
      $regionales=$this->model_proyecto->list_departamentos();
      $tabla='';
      $tabla.='<table class="change_order_items" border=1>
                  <thead>
                    <tr align=center>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center; height:14px;">#</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">REGIONAL</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EN AVANCE</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">NO CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL PROG.</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL EVAL.</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% NO CUMPLIDO</th>
                    </tr>
                  </thead>
                    <tbody>';
                    $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0;
                    for ($i=1; $i <=count($regionales) ; $i++) { 
                      $tabla.='<tr>
                                <td align="center">'.$i.'</td>';
                      for ($j=1; $j <=8 ; $j++) {
                        if($j==1){
                          $tabla.='<td align="left">'.$matriz[$i][$j].'</td>';
                        }
                        else{
                          $tabla.='<td align="right">'.$matriz[$i][$j].'</td>';
                        }
                        
                        if($j==2){
                          $nro_cum_a=$nro_cum_a+$matriz[$i][$j];
                        }
                        elseif ($j==3) {
                          $nro_pro_a=$nro_pro_a+$matriz[$i][$j];
                        }
                        elseif ($j==4) {
                          $nro_ncum_a=$nro_ncum_a+$matriz[$i][$j];
                        }
                        elseif ($j==5) {
                          $total_prog_a=$total_prog_a+$matriz[$i][$j];
                        }
                        elseif ($j==6) {
                          $total_eval_a=$total_eval_a+$matriz[$i][$j];
                        }
                      }
                      $tabla.='</tr>';
                    }
                    $pcion=0;
                    $npcion=0;
                    if($total_prog_a!=0){
                      $pcion=round((($nro_cum_a/$total_prog_a)*100),2);
                    }
                    $npcion=(100-$pcion);
                    $tabla.='
                    </tbody>
                      <tr>
                        <td colspan=2 align="center">TOTAL</td>
                        <td align="right">'.$nro_cum_a.'</td>
                        <td align="right">'.$nro_pro_a.'</td>
                        <td align="right">'.$nro_ncum_a.'</td>
                        <td align="right">'.$total_prog_a.'</td>
                        <td align="right">'.$total_eval_a.'</td>
                        <td align="right">'.$pcion.'%</td>
                        <td align="right">'.$npcion.'%</td>
                      </tr>
                  </table>';

        return $tabla;
    }

    /*---- IMPRIMIR EVALUACION TRIMESTRAL Y ACUMULADO DE OPERACIONES POR REGIONAL ---*/ 
    public function print_evaluacion_operaciones_institucional($eval){
      $programas=$this->model_evalregional->categorias_programaticas_nacional();
      $trimestre=$this->model_evaluacion->trimestre();
      $mes = $this->mes_nombre();

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
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="55px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b>'.$this->session->userdata('entidad').'</b></td>
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
                $tabla .='<center><FONT FACE="courier new" size="2"><b>CUADRO DE EVALUACI&Oacute;N ACUMULADA DE OPERACIONES POR CATEGORIA PROGRAM&Aacute;TICA <br>AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>';
                $tabla.=''.$this->print_evaluacion_acu_operaciones().'<br>
                <center><FONT FACE="courier new" size="2"><b>CUADRO CONSOLIDADO DE EVALUACI&Oacute;N ACUMULADA DE OPERACIONES<b/></FONT></center>
                <table class="change_order_items" border=1>
                  <tr>
                    <td>
                      <div id="container_acu2" style="width: 550px; height: 300px; margin: 0 auto"></div>
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
              ?>
          <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
          <script type="text/javascript">
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

    /*------- PARA IMPRIMIR OPERACIONES POR PROGRAMAS ------*/
    public function print_evaluacion_acu_operaciones(){
      $programas=$this->model_evalregional->categorias_programaticas_nacional();
      $tabla='';
      $tabla.='<table class="change_order_items" border=1>
                  <thead>
                    <tr align=center>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center; height:14px;">#</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">PROGRAMA</th>
                      <th style="width:20%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">DESCRIPCI&Oacute;N PROGRAMA</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">EN AVANCE</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">NO CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL PROG.</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">TOTAL EVAL.</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% CUMPLIDO</th>
                      <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF; text-align: center;">% NO CUMPLIDO</th>
                    </tr>
                  </thead>
                    <tbody>';
                    $nro=0;
                    $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0;
                    foreach($programas  as $rowp){
                      if($rowp['aper_programa']!='97' & $rowp['aper_programa']!='98'){
                        $eval_acu=$this->matriz_evaluacion_Acumulado($rowp['aper_programa']); /// Evalucion Trimestral Acumulado
                        $nro++;
                        $tabla.='<tr class="modo1">';
                          $tabla.='<td style="width: 3%; text-align: center; height:13px;">'.$nro.'</td>';
                          $tabla.='<td style="width: 5%; text-align: center;">'.$rowp['aper_programa'].'</td>';
                          $tabla.='<td style="width: 20%; text-align: left;">'.$rowp['aper_descripcion'].'</td>';
                          $tabla.='<td style="width: 8%; text-align: center;">'.$eval_acu[1].'</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[2].'</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[3].'</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[7].'</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[4].'</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[5].' %</td>
                                  <td style="width: 8%; text-align: center;">'.$eval_acu[6].' %</td>';
                        $tabla.='</tr>';

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
                      <tr class="modo1">
                        <td colspan="3" align="right" style="height:13px;">TOTAL : </td>
                        <td align="center">'.$nro_cum_a.'</td>
                        <td align="center">'.$nro_pro_a.'</td>
                        <td align="center">'.$nro_ncum_a.'</td>
                        <td align="center">'.$total_prog_a.'</td>
                        <td align="center">'.$total_eval_a.'</td>
                        <td align="center">'.$pcion_a.' %</td>
                        <td align="center">'.$npcion_a.' %</td>
                      </tr>
                  </table>';

        return $tabla;
    }

    /*---- Cuadro comparativo por programas de Evaluacion Trimestral Institucional ----*/
    public function cuadro_comparativo_programas_evaluado(){
      $tabla ='';
      $programas=$this->model_evalregional->categorias_programaticas_nacional();

      $tabla.='<table class="table table-bordered">
              <thead>
                <tr class="modo1">
                  <th colspan="3"><center>DATOS DEL PROGRAMA</center></th>
                  <th colspan="7"><center>EVALUACI&Oacute;N TRIMESTRAL</center></th>
                  <th colspan="7"><center>EVALUACI&Oacute;N TRIMESTRAL ACUMULADO</center></th>
                </tr>
                <tr class="modo1">
                  <th style="width: 1%">#</th>
                  <th align="center" style="width: 2%">PROGRAMA</th>
                  <th align="center" style="width: 8%">DESCRIPCI&Oacute;N PROGRAMA</th>

                  <th align="center" style="width: 6%">CUMPLIDO</th>
                  <th align="center" style="width: 6%">EN AVANCE</th>
                  <th align="center" style="width: 6%">NO CUMPLIDO</th>
                  <th align="center" style="width: 6%">TOTAL PROG.</th>
                  <th align="center" style="width: 6%">TOTAL EVAL.</th>
                  <th align="center" style="width: 6%">% CUMPLIDO</th>
                  <th align="center" style="width: 6%">% NO CUMPLIDO</th>

                  <th align="center" style="width: 6%">CUMPLIDO</th>
                  <th align="center" style="width: 6%">EN AVANCE</th>
                  <th align="center" style="width: 6%">NO CUMPLIDO</th>
                  <th align="center" style="width: 6%">TOTAL PROG.</th>
                  <th align="center" style="width: 6%">TOTAL EVAL.</th>
                  <th align="center" style="width: 6%">% CUMPLIDO</th>
                  <th align="center" style="width: 6%">% NO CUMPLIDO</th>
                </tr>
              </thead>
              <tbody>';
              $nro=0;

              $nro_cum=0;$nro_pro=0;$nro_ncum=0;$total_prog=0;$total_eval=0;
              $nro_cum_a=0;$nro_pro_a=0;$nro_ncum_a=0;$total_prog_a=0;$total_eval_a=0;
              foreach($programas  as $rowp){
                if($rowp['aper_programa']!='96' & $rowp['aper_programa']!='97' & $rowp['aper_programa']!='98'){
                    $eval=$this->matriz_evaluacion_trimestre($rowp['aper_programa']); /// Evaluacion Trimestral
                    $eval_acu=$this->matriz_evaluacion_Acumulado($rowp['aper_programa']); /// Evalucion Trimestral Acumulado
                    $nro++;
                    $tabla.='<tr class="modo1">';
                      $tabla.='<td>'.$nro.'</td>';
                      $tabla.='<td align="center">'.$rowp['aper_programa'].'</td>';
                      $tabla.='<td>'.$rowp['aper_descripcion'].'</td>';
                      $tabla.='<td bgcolor="#cff7f2">'.$eval[1].'</td>
                              <td bgcolor="#cff7f2">'.$eval[2].'</td>
                              <td bgcolor="#cff7f2">'.$eval[3].'</td>
                              <td bgcolor="#cff7f2">'.$eval[7].'</td>
                              <td bgcolor="#cff7f2">'.$eval[4].'</td>
                              <td bgcolor="#cff7f2" title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[5].' %</button></td>
                              <td bgcolor="#cff7f2" title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[6].' %</button></td>';

                      $tabla.='<td bgcolor="#a2f9ad">'.$eval_acu[1].'</td>
                              <td bgcolor="#a2f9ad">'.$eval_acu[2].'</td>
                              <td bgcolor="#a2f9ad">'.$eval_acu[3].'</td>
                              <td bgcolor="#a2f9ad">'.$eval_acu[7].'</td>
                              <td bgcolor="#a2f9ad">'.$eval_acu[4].'</td>
                              <td bgcolor="#a2f9ad" title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval_acu[5].' %</button></td>
                              <td bgcolor="#a2f9ad" title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval_acu[6].' %</button></td>';
                    $tabla.='</tr>';
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

                      $pcion_a=round((($nro_cum_a/$total_prog_a)*100),2);
                      $npcion_a=(100-$pcion_a);
                    }
                }
                
              }
              $tabla.='<tbody>
                        <tr>
                          <td colspan="3">TOTAL : </td>
                          <td>'.$nro_cum.'</td>
                          <td>'.$nro_pro.'</td>
                          <td>'.$nro_ncum.'</td>
                          <td>'.$total_prog.'</td>
                          <td>'.$total_eval.'</td>
                          <td title="OPERACIONES CUMPLIDOS A NIVEL INSTITUCIONAL"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion.' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDOS A NIVEL INSTITUCIONAL"><button type="button" style="width:100%;" class="btn btn-danger">'.$npcion.' %</button></td>
                          <td>'.$nro_cum_a.'</td>
                          <td>'.$nro_pro_a.'</td>
                          <td>'.$nro_ncum_a.'</td>
                          <td>'.$total_prog_a.'</td>
                          <td>'.$total_eval_a.'</td>
                          <td title="OPERACIONES CUMPLIDOS A NIVEL INSTITUCIONAL ACUMULADO"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion_a.' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDOS A NIVEL INSTITUCIONAL ACUMULADO"><button type="button" style="width:100%;" class="btn btn-danger">'.$npcion_a.' %</button></td>
                        </tr>
                    </table>';

        $teval[1]=$nro_cum;
        $teval[2]=$nro_pro;
        $teval[3]=$nro_ncum;
        $teval[4]=$total_prog;
        $teval[5]=$total_eval;
        $teval[6]=$pcion;
        $teval[7]=$npcion;

        $teval[8]=$nro_cum_a;
        $teval[9]=$nro_pro_a;
        $teval[10]=$nro_ncum_a;
        $teval[11]=$total_prog_a;
        $teval[12]=$total_eval_a;
        $teval[13]=$pcion_a;
        $teval[14]=$npcion_a;

      return array($tabla,$teval);
    }

    /*---- GRAFICO EVALUACION TRIMESTRAL Y ACUMULADO DE OPERACIONES INSITUCIONAL ---*/ 
    public function evaluacion_operaciones_institucional(){
      $eval=$this->cuadro_comparativo_programas_evaluado()[1];

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

      $class='class="table table-bordered" align=center style="width:100%;"';
      $graf='<div id="container" style="width: 600px; height: 300px; margin: 0 auto"></div>';
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
                    <table '.$class.'>
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
                  <table '.$class.'>
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
                          y: <?php echo $graf_nc;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av_a;?>%',
                          y: <?php echo $graf_av;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c_a; ?>%',
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
          <?php
      return $tabla;
    }

    /*------------ Parametros de Eficacia Insitucional --------------*/
    public function eficacia_institucional($tp){
        if($tp==1){
          $class='class="table table-bordered" align=center style="width:60%;"';
          $div='<div id="parametro_efi" style="width: 650px; height: 330px; margin: 0 auto"></div>';
        }
        else{
          $class='';
          $div='<div id="parametro_efi_print" style="width: 650px; height: 330px; margin: 0 auto"></div>';
        }
        $nro=$this->nro_list_institucional();
        $tabla='';
        $tabla .='<table '.$class.'>
                    <tr>
                      <td>
                        '.$div.'
                      </td>
                    </tr>
                  </table>';
        $tabla .='<center>
                    <table '.$class.'>
                      <thead>
                        <tr>
                          <th><b>EFICACIA</b></th>
                          <th><b>PARAMETRO</b></th>
                          <th><b>NRO DE ACCIONES</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>INSATISFACTORIO</td>
                          <td>0% a 75%</td>
                          <td bgcolor="#f7c5c1"><b>'.$nro[1].'</b></td>
                        </tr>
                        <tr>
                          <td>REGULAR</td>
                          <td>75% a 90% </td>
                          <td bgcolor="#f5e5b9"><b>'.$nro[2].'</b></td>
                        </tr>
                        <tr>
                          <td>BUENO</td>
                          <td>90% a 99%</td>
                          <td bgcolor="#b1e0f5"><b>'.$nro[3].'</b></td>
                        </tr>
                        <tr>
                          <td>OPTIMO </td>
                          <td>100%</td>
                          <td bgcolor="#b2f1b4"><b>'.$nro[4].'</b></td>
                        </tr>
                        <tr>
                          <td colspan=2><b>TOTAL</b></td>
                          <td><b>'.$nro[5].'</b></td>
                        </tr>
                      </tbody>
                    </table>
                  </center>';
        
        return $tabla;
    }

    /*------------------------- Lista de nro Acciones Operativas a nivel Distrital ----------------------*/
    public function nro_list_institucional(){
    //  $dist=$this->model_evalregional->get_dist($dist_id);
      $acciones=$this->model_evalregional->list_consolidado_institucional();
      
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
    /*----------------- Eficacia Evaluacion -------------*/
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
    public function matriz_evaluacion_trimestre($aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        
        $cum=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,1,$this->tmes);
        $proc=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,2,$this->tmes);
        $ncum=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,3,$this->tmes);
        $total_prog=$this->model_evalregional->total_programado_programas_nacional($aper_programa,$this->tmes); // total programado prod

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
    public function matriz_evaluacion_Acumulado($aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,1,$i);
        $proc=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,2,$i);
        $ncum=$this->model_evalregional->evaluacion_programas_nacional($aper_programa,3,$i);
        $total_prog=$this->model_evalregional->total_programado_programas_nacional($aper_programa,$i); // total programado prod

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