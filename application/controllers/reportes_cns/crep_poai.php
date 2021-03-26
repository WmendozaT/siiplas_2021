<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_poai extends CI_Controller { 
  public function __construct (){ 
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');

        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
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
            redirect('/','refresh');
        }
    }
    
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='RESPONSABLE NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }

    /*---------------  REGIONALES (2019) -------------------*/
    public function list_regionales(){
      $data['menu']=$this->menu(7);
      $data['institucional']=$this->institucional(1);
      $data['onacional']=$this->region(10);
     /* 
      $data['lpz']=$this->region(2);
      $data['cba']=$this->region(3);
      $data['ch']=$this->region(1);
      $data['oru']=$this->region(4);
      $data['pts']=$this->region(5);
      $data['tj']=$this->region(6);
      $data['scz']=$this->region(7);
      $data['bn']=$this->region(8);
      $data['pnd']=$this->region(9);*/
      $this->load->view('admin/reportes_cns/poa_pei/regional_poai', $data);
    //  echo $this->print_region(9);
    }


    /*----- REPORTE DE OPERACIONES - EVALUACION A NIVEL INSTITUCIONAL - OBJETIVOS ESTRATEGICOS -----*/
    public function reporte_detalle_alineacion_operaciones_evaluacion_oestrategicos(){
      ?>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      /*$total=$this->mrep_operaciones->total_nro_ope_oe_institucional();
      $ope_total=0;
      if(count($total)!=0){
        $ope_total=$total[0]['total'];
      }*/
      $tabla='';

      $tabla.='<table class="change_order_items" border="1" align=center style="width:100%;"><thead>';
        $tabla.='<tr align=center style="height:45px;">
                      <th style="width:1%;"><font color="#000">

                        <table width="90%" align=center>
                          <tr>
                            <td width=20%; text-align:center;"">
                                <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                            </td>
                            <td width=80%; class="titulo_pdf">
                                <FONT FACE="courier new" size="1">
                                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                <b>PLAN OPERATIVO ANUAL : </b>'.$this->gestion.'<br>
                                <b>REPORTE </b>POA '.$this->gestion.' ARTICULADO AL PEI 2016 - 2020<br>
                                <b>INSTITUCIONAL - OBJETIVOS ESTRAT&Eacute;GICOS</b>
                                </FONT>
                            </td>
                          </tr>
                        </table>
                        <hr>
                          <div align="left"><b>DETALLE DE OPERACIONES ARTICULADO A LAS ACCIONES DE CORTO PLAZO Y OBJETIVOS ESTRATEGICOS A NIVEL INSTITUCIONAL</b></div>
                        <hr>
                      </th>
                    </tr>
                  </thead>
                    '.$this->institucional(3).'
                  </table>';
                       
      echo $tabla;

    }



    /*---------- LISTA DE OBJETIVOS A NIVEL INSTITUCIONAL ------------*/
    public function institucional($tp){
      $tabla='';
      $list_obj=$this->mrep_operaciones->list_nro_ope_oe_institucional();
      $tmes=$this->model_evaluacion->trimestre();
      if($tp==1){
        $tabla.='
        <tbody>
          <tr>
            <td>';
        $tabla.='<div class="container">
            <h2>ARTICULADO AL PEI 2016 - 2020 A NIVEL INSTITUCIONAL</h2>';

            foreach($list_obj as $rowo){
              $tabla.='          
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th colspan="7">OE.'.$rowo['obj_codigo'].' .- '.$rowo['obj_descripcion'].'</th>
                    </tr>
                    <tr align="center">
                      <th style="width:5%;"><center>COD. PILAR</center></th>
                      <th style="width:5%;"><center>COD. META</center></th>
                      <th style="width:5%;"><center>COD. RESULTADO</center></th>
                      <th style="width:5%;"><center>COD. ACCI&Oacute;N</center></th>
                      <th style="width:5%;"><center>COD.ACP.</th>
                      <th style="width:60%;">ACCI&Oacute;N DE CORTO PLAZO</th>
                      <th style="width:5%;"><center>NRO. OPERACIONES</center></th>
                    </tr>
                  </thead>
                  <tbody>';
                  $oe_ope=$this->mrep_operaciones->get_nro_ope_oe_institucional($rowo['obj_id']);
                  $nro_oe=0;
                  foreach($oe_ope as $row){
                    $tabla.='
                    <tr>
                      <td align="center">'.$row['cod_pilar'].'</td>
                      <td align="center">'.$row['cod_meta'].'</td>
                      <td align="center">'.$row['cod_resultado'].'</td>
                      <td align="center">'.$row['cod_accion'].'</td>
                      <td align="center">'.$row['acc_codigo'].'</td>
                      <td>'.$row['acc_descripcion'].'</td>
                      <td align="center">'.$row['total'].'</td>
                    </tr>';
                    $nro_oe=$nro_oe+$row['total'];
                  }
                  $tabla.='
                  </tbody>
                  <tr style="height:15px;">
                    <td colspan="6" align="right"><b>SUB TOTAL</b></td>
                    <td align="center"><b>'.$nro_oe.'</b></td>
                  </tr>
                </table>';
            }
            
          $tabla.='
          </div>
            </td>
              </tr>
                </tbody>';
      }
      elseif($tp==2){
        /*------------------------- print -----------------------*/
        foreach($list_obj as $rowo){
            $tabla.='
            <tbody>
              <tr>
                <td>
              <center>
              <table class="change_order_items" border=1  align=center style="width:100%;">
                <thead>  
                  <tr style="height:15px;">
                    <th colspan="12" align="left" bgcolor="#1c7368"><font color="#fff">OE.'.$rowo['obj_codigo'].' .- '.$rowo['obj_descripcion'].'</font></th>
                  </tr>
                  <tr style="height:15px;">
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">COD. PILAR</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">COD. META</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">COD. RESULTADO</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">COD. ACCI&Oacute;N</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">COD. ACP.</font></center></th>
                    <th style="width:30%;" bgcolor="#1c7368"><center><font color="#fff">ACCI&Oacute;N DE CORTO PLAZO</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">NRO DE OPERACIONES</font></center></th>

                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS PROG.</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS EVAL.</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS CUMP.</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS NO CUMP.</font></center></th>
                    <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">% CUMP.</font></center></th>
                  </tr>
                </thead>
                <tbody>';
                $oe_ope=$this->mrep_operaciones->get_nro_ope_oe_institucional($rowo['obj_id']);
                $monto_oe_ope=$this->mrep_operaciones->get_monto_ope_oe_institucional($rowo['obj_id']);
                $nro_oe=0;$monto_oe=0;
                if(count($monto_oe_ope)!=0){
                  $monto_oe=$monto_oe_ope[0]['monto'];
                }
                $tprog=0;$teval=0;$tcum=0;$tncum=0;
                foreach($oe_ope as $row){
                  $eval=$this->matriz_evaluacion_Accion_cplazo($row['ae']); /// Evaluado
                  $tabla.=
                  ' <tr class="modo1" style="height:15px;">
                      <td align="center">'.$row['cod_pilar'].'</td>
                      <td align="center">'.$row['cod_meta'].'</td>
                      <td align="center">'.$row['cod_resultado'].'</td>
                      <td align="center">'.$row['cod_accion'].'</td>
                      <td align="center">'.$row['acc_codigo'].'</td>
                      <td>'.$row['acc_descripcion'].'</td>
                      <td align="center">'.$row['total'].'</td>

                      <td align="center">'.$eval[7].'</td>
                      <td align="center">'.$eval[4].'</td>
                      <td align="center">'.$eval[1].'</td>
                      <td align="center">'.($eval[2]+$eval[3]).'</td>
                      <td align="center">'.$eval[5].'%</td>
                    </tr>';
                  $nro_oe=$nro_oe+$row['total'];
                  $tprog=$tprog+$eval[7];
                  $teval=$teval+$eval[4];
                  $tcum=$tcum+$eval[1];
                  $tncum=$tncum+($eval[2]+$eval[3]);
                }
                $pcion=0;
                  if($eval[7]!=0){
                    $pcion=round((($tcum/$tprog)*100),2);
                  }
                $tabla.='
                </tbody>
                <tr style="height:15px;">
                  <td colspan="6" align="right"><b>SUB TOTAL OPERACIONES </b></td>
                  <td align="center"><b>'.$nro_oe.'</b></td>

                  <td align="center"><b>'.$tprog.'</b></td>
                  <td align="center"><b>'.$teval.'</b></td>
                  <td align="center"><b>'.$tcum.'</b></td>
                  <td align="center"><b>'.$tncum.'</b></td>
                  <td align="center"><b>'.$pcion.'%</b></td>
                </tr>';
                /*<tr style="height:15px;">
                  <td colspan="6" align="right"><b>MONTO TOTAL PRESUPUESTO</b></td>
                  <td align="center"><b>'.$monto_oe.' Bs.</b></td>
                </tr>*/
                $tabla.='
              </table>
              </center>
              </td>
              </tr>
            </tbody>';
          }
      }
      else{
        $oe=$this->mrep_operaciones->list_obj_estrategicos_evaluados();
        $nro_oe=count($oe);
        $tabla.='
        <tbody>
              <tr>
                <td>
                <table class="change_order_items" border=1  align=center style="width:80%;">
                  <thead>
                    <tr align="center" style="height:19px;">
                      <th style="width:1%;" bgcolor="#1c7368"><center><font color="#fff">Nro.</font></center></th>
                      <th style="width:20%;" bgcolor="#1c7368"><center><font color="#fff">OBJETIVO ESTRAT&Eacute;GICO</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">NRO DE OPERACIONES</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS PROG.</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS EVAL.</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS CUMP.</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">TAREAS NO CUMP.</font></center></th>
                      <th style="width:5%;" bgcolor="#1c7368"><center><font color="#fff">% CUMP.</font></center></th>
                    </tr>
                  </thead>
                  <tbody>';
                    $moe=$this->tabla_matriz_institucional_oe($oe);
                    for ($i=1; $i <=count($oe) ; $i++) { 
                      $tabla.='<tr style="height:15px;">';
                      $p='';
                      for ($j=1; $j <=8 ; $j++) { 
                        if($j==8){$p='%';}
                        if($j==2){
                          $tabla.='<td>'.$moe[$i][$j].'</td>';
                        }
                        else{
                          $tabla.='<td align=center>'.$moe[$i][$j].''.$p.'</td>';  
                        }
                      }
                      $tabla.='</tr>';
                    }
                  $tabla.='
                    </tbody>
                    <tr>
                      <td colspan=2>'.$moe[count($oe)+1][2].'</td>
                      <td align=center><b>'.$moe[count($oe)+1][3].'</b></td>
                      <td align=center><b>'.$moe[count($oe)+1][4].'</b></td>
                      <td align=center><b>'.$moe[count($oe)+1][5].'</b></td>
                      <td align=center><b>'.$moe[count($oe)+1][6].'</b></td>
                      <td align=center><b>'.$moe[count($oe)+1][7].'</b></td>
                      <td align=center><b>'.$moe[count($oe)+1][8].'%</b></td>
                    </tr> 
                  </table>

                  </td>
                    </tr>
                    <tr>
                        <td>
                          <table table class="change_order_items" border=1 align=center style="width:100%;">
                            <thead>
                              <tr bgcolor="#1c7368" style="height:15px;">
                                <th><font color="#fff">GR&Aacute;FICO DE EVALUACI&Oacute;N DE OPERACIONES ACUMULADO AL '.$tmes[0]['trm_descripcion'].' DE '.$this->gestion.'</font></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><div id="container" style="width: 950px; height: 340px; margin: 1 auto"></div> </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      <tr>
                    </tbody>';

      ?>
      <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
      <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
      <!-- <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script>  -->

      <script type="text/javascript">
      var chart;

      $(document).ready(function() {
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container',
            defaultSeriesType: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: [
              <?php ;
              for ($i=1; $i <=$nro_oe; $i++){ 
                if($i==$nro_oe){
                  ?>
                  '<?php echo $moe[$i][1];?>'
                  <?php
                }
                else{
                  ?>
                  '<?php echo $moe[$i][1];?>',
                  <?php
                }
              } 
            ?>
            ]
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Rainfall (Tareas)'
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
                this.x +': '+ this.y +' Tareas';
            }
          },
          plotOptions: {
            column: {
              pointPadding: 0.2,
              borderWidth: 0
            }
          },
          series: [{
            name: 'TAREAS PROGRAMAS',color: '#9e9b98',
            data: [
           <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][4]?>, color: '#9e9b98'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][4]?>, color: '#9e9b98'},
                  <?php
                }
              }
            ?>
            ]
        
          }, {
            name: 'TAREAS EVALUADAS',color: '#7cb5ec',
            data: [
            <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][5]?>, color: '#7cb5ec'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][5]?>, color: '#7cb5ec'},
                  <?php
                }
              }
            ?>
            ]
          }, {
            name: 'TAREAS CUMPLIDAS',color: '#04B404',
            data: [
            <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][6]?>, color: '#04B404'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][6]?>, color: '#04B404'},
                  <?php
                }
              }
            ?>
            ]
          }]
        });
      });
    </script>
      <?php
      }

      ?>
      </html>
      <?php
      return $tabla;
    }

    /*----- TABLA MATRIZ - OBJETIVO ESTRATEGICO POR REGIONAL ------*/
    public function tabla_matriz_institucional_oe($oe){
      $moe='';
      for($i=1; $i <=count($oe) ; $i++) { 
        $moe[1][$i]=0; // Nro
        $moe[2][$i]=0; // Objetivo Estrategico
        $moe[3][$i]=0; // Nro de Operaciones 
        $moe[4][$i]=0; // Tareas Programadas
        $moe[5][$i]=0; // Tareas Evaluadas
        $moe[6][$i]=0; // Tareas Cumplidas
        $moe[7][$i]=0; // Tareas No cumplidas
        $moe[8][$i]=0; // $ cumplimiento
      }

      $nro=0;$nro_oe=0;$tprog=0;$teval=0;$tcum=0;$tncum=0;
        foreach($oe as $rowo){
          $eval=$this->matriz_evaluacion_oe($rowo['obj_id']); /// Evaluado
          $nro++;
          $moe[$nro][1]='OE'.$rowo['obj_codigo'];
          $moe[$nro][2]=$rowo['obj_descripcion'];
          $moe[$nro][3]=$rowo['total'];
          $moe[$nro][4]=$eval[7];
          $moe[$nro][5]=$eval[4];
          $moe[$nro][6]=$eval[1];
          $moe[$nro][7]=($eval[2]+$eval[3]);
          $moe[$nro][8]=$eval[5];

          $nro_oe=$nro_oe+$rowo['total'];
          $tprog=$tprog+$eval[7];
          $teval=$teval+$eval[4];
          $tcum=$tcum+$eval[1];
          $tncum=$tncum+($eval[2]+$eval[3]);

        }
        $pcion=0;
        if($eval[7]!=0){
          $pcion=round((($tcum/$tprog)*100),2);
        }

        $nro++;
        $moe[$nro][2]='TOTAL';
        $moe[$nro][3]=$nro_oe;
        $moe[$nro][4]=$tprog;
        $moe[$nro][5]=$teval;
        $moe[$nro][6]=$tcum;
        $moe[$nro][7]=$tncum;
        $moe[$nro][8]=$pcion;

      return $moe;
    }

    /*--- REPORTE DE OPERACIONES A NIVEL INSTITUCIONAL --*/
    public function reporte_detalle_alineacion_operaciones_institucional(){
      ?>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $total=$this->mrep_operaciones->total_nro_ope_oe_institucional();
      $ope_total=0;
      if(count($total)!=0){
        $ope_total=$total[0]['total'];
      }
      $tabla='';

      $tabla.='<table class="change_order_items" border="1" align=center style="width:100%;"><thead>';
        $tabla.='<tr align=center style="height:45px;">
                      <th style="width:1%;"><font color="#000">

                        <table width="90%" align=center>
                          <tr>
                            <td width=20%; text-align:center;"">
                                <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                            </td>
                            <td width=80%; class="titulo_pdf">
                                <FONT FACE="courier new" size="1">
                                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                <b>PLAN OPERATIVO ANUAL : </b>'.$this->gestion.'<br>
                                <b>REPORTE </b>POA '.$this->gestion.' ARTICULADO AL PEI 2016 - 2020
                                </FONT>
                            </td>
                          </tr>
                        </table>
                        <hr>
                          <div align="left"><b>DETALLE DE OPERACIONES ARTICULADO A LAS ACCIONES DE CORTO PLAZO Y OBJETIVOS ESTRATEGICOS A NIVEL INSTITUCIONAL</b></div>
                        <hr>
                      </th>
                    </tr></thead>
                    '.$this->institucional(2).'
                  </table>';
                       
      echo $tabla;
    }

    /*------------- Region Id ----------------*/
    public function region($dep_id){
      $oe=$this->model_mestrategico->list_objetivos_estrategicos();
      $act=$this->mrep_operaciones->operaciones_por_regionales($dep_id);
      $tabla='';
      $nro=0;
      $tabla.='<tbody>';
      $nro_1=0;$nro_2=0;$nro_2=0;$nro_3=0;$nro_4=0;$nro_5=0;$nro_6=0;$nro_7=0;$nro_8=0;$nro_9=0;
      foreach($act as $row){
        $nro++;
        $list_obj=$this->mrep_operaciones->list_obj_vinculados($row['proy_id']);
        if(count($list_obj)!=0){
          $tabla.='<tr class="modo1" style="height:40px;" title='.$row['proy_id'].'>';
            $tabla.='<td>'.$nro.'</td>';
            $tabla.='<td>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>';
            $tabla.='<td>'.$row['proy_nombre'].'</td>';
            $nro_obj=0;
            foreach($oe as $rowo){
              $noe=$this->mrep_operaciones->get_ope_vinculados_oe_por_actividad($row['proy_id'],$rowo['obj_id']);
              if(count($noe)!=0){
                $tabla.='<td bgcolor="#cff5c7" align="center"><b>'.$noe[0]['total'].'</b></td>';
                $nro_obj=$nro_obj+$noe[0]['total'];
              }
              else{
                $tabla.='<td bgcolor="#cff5c7" align="center"><b> - </b></td>';
              }
            }
            $tabla.='<td>'.$nro_obj.'</td>';
            $tabla.='<td></td>';
          $tabla.='</tr>';
        }
      }
      $tabla.='</tbody>';
      $tabla.=
      '<tr>
        <td colspan="3" align="right"><b>TOTAL  :  </b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>';
      return $tabla;
    }

    /*-------------------print region-actividad ----------------------------*/
    public function reporte_detalle_alineacion_operaciones_acciones($dep_id){
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
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $total=$this->mrep_operaciones->total_ope_oe_regional($dep_id);
      $ope_total=0;
      if(count($total)!=0){
        $ope_total=$total[0]['total'];
      }
      $tabla='';

      $act=$this->mrep_operaciones->operaciones_por_regionales($dep_id);
      $tabla.='<table class="change_order_items" border=1 align=center style="width:100%;"><thead>';
        $tabla.='<tr align=center style="height:45px;">
                      <th style="width:1%;"><font color="#000">

                        <table width="90%" align=center>
                          <tr>
                            <td width=20%; text-align:center;"">
                                <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                            </td>
                            <td width=80%; class="titulo_pdf">
                                <FONT FACE="courier new" size="1">
                                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                <b>PLAN OPERATIVO ANUAL : </b>'.$this->gestion.'<br>
                                <b>REGIONAL </b>'.strtoupper($dep[0]['dep_departamento']).'<br>
                                <b>REPORTE </b>POA '.$this->gestion.' ARTICULADO AL PEI 2016 - 2020
                                </FONT>
                            </td>
                          </tr>
                        </table>
                        <hr>
                          <div align="left"><b>DETALLE DE OPERACIONES ARTICULADO A LAS ACCIONES DE CORTO PLAZO Y OBJETIVOS ESTRATEGICOS</b></div>
                        <hr>

                      </th>
                    </tr></thead>
                    <tbody>
                      <tr>
                        <td>';
                        foreach($act as $row){
                          $list_obj=$this->mrep_operaciones->list_obj_vinculados($row['proy_id']);
                          if(count($list_obj)!=0){
                              $total=$this->mrep_operaciones->total_operaciones_act($row['proy_id']);
                              $tabla.='<table class="change_order_items" border=1 align=center style="width:100%;">
                                        <thead>
                                          <tr bgcolor="#1c7368" style="height:20px;" colspan="5">
                                            <th style="width:1%;" colspan="10"><font color="#fff">'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].' - '.$row['proy_nombre'].'</font></th>
                                          </tr>
                                          <tr>
                                            <th style="width:1%;">COD. OE.</th>
                                            <th style="width:20%;">ARTICULACI&Oacute;N CON EL OBJETIVO ESTRATEGICO</th>
                                            <th style="width:5%;">COD. ACP..</th>
                                            <th style="width:30%;">ACCI&Oacute;N DE CORTO PLAZO</th>
                                            <th style="width:5%;">NRO DE OPERACIONES</th>
                                            <th style="width:7%;">TAREAS. PROG.</th>
                                            <th style="width:7%;">TAREAS. EVAL.</th>
                                            <th style="width:7%;">TAREAS CUMP.</th>
                                            <th style="width:7%;">TAREAS NO CUMP.</th>
                                            <th style="width:7%;">% CUMP.</th>
                                          </tr>
                                      </thead>';
                              $tabla.='<tbody>';
                              $nro_ope=0;
                              $tprog=0;$teval=0;$tcum=0;$tncum=0;
                              foreach($list_obj as $rowo){
                                $ope=$this->mrep_operaciones->list_obj_vinculados_ope($rowo['obj_id'],$row['proy_id']);
                                $nr=1;
                                foreach($ope as $rowope){
                                  $eval=$this->matriz_evaluado_proyecto($row['proy_id'],$rowope['ae']); /// Evaluado
                                  $tabla.='<tr>';
                                  $tabla.='<td>OE.'.$rowo['obj_codigo'].'</td>';
                                    $tabla.='<td>'.$rowo['obj_descripcion'].'</td>';
                                    $tabla.='<td>'.$rowope['acc_codigo'].'</td>';
                                    $tabla.='<td>'.$rowope['acc_descripcion'].'</td>';
                                    $tabla.='<td>'.$rowope['total'].'</td>';
                                    $tabla.='<td>'.$eval[7].'</td>';
                                    $tabla.='<td>'.$eval[4].'</td>';
                                    $tabla.='<td>'.$eval[1].'</td>';
                                    $tabla.='<td>'.($eval[2]+$eval[3]).'</td>';
                                    $tabla.='<td>'.$eval[5].'%</td>';
                                  $nr++;
                                  $tabla.='</tr>';

                                  $tprog=$tprog+$eval[7];
                                  $teval=$teval+$eval[4];
                                  $tcum=$tcum+$eval[1];
                                  $tncum=$tncum+($eval[2]+$eval[3]);
                                }
                              }
                              $pcion=0;
                              if($eval[7]!=0){
                                $pcion=round((($tcum/$tprog)*100),2);
                              }
                              $tabla.='</tbody>
                              <tr>
                                <td colspan="4" align="right"><b>TOTAL : </b></td>
                                <td>'.$total[0]['total'].'</td>
                                <td>'.$tprog.'</td>
                                <td>'.$teval.'</td>
                                <td>'.$tcum.'</td>
                                <td>'.$tncum.'</td>
                                <td>'.$pcion.'%</td>
                              </tr>';
                              $tabla.='</table>';
                          }
                          
                        }
              $tabla.='</td>
                      </tr>
                    </tbody>
                    <tr>
                      <td align="right"><b>TOTAL OPERACIONES  : '.$ope_total.'</b></td>
                    </tr>
                  </table>';
                       
      echo $tabla;
      ?>
      </html>
      <?php
    }

    /*-------- print total operaciones por regional (Objetivos Estrategicos)---------*/
    public function reporte_total_alineacion_operaciones_oe($dep_id){
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
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $total=$this->mrep_operaciones->total_ope_oe_regional($dep_id);
      $monto=$this->mrep_operaciones->monto_total_ope_oe_regional($dep_id);
      $ope_total=0;$monto_total=0;
      if(count($total)!=0){
        $ope_total=$total[0]['total'];
      }
      if(count($monto)!=0){
        $monto_total=$monto[0]['monto'];
      }
      $tabla='';

      $oe=$this->mrep_operaciones->list_nro_ope_oe_regional($dep_id);
      $nro_oe=count($oe);
      $tabla.='<table class="change_order_items" border=1 align=center style="width:100%;"><thead>';
        $tabla.='<tr align=center style="height:45px;">
                      <th style="width:1%;"><font color="#000">

                        <table width="90%" align=center>
                          <tr>
                            <td width=20%; text-align:center;"">
                                <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                            </td>
                            <td width=80%; class="titulo_pdf">
                                <FONT FACE="courier new" size="1">
                                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                <b>PLAN OPERATIVO ANUAL : </b>'.$this->gestion.'<br>
                                <b>REGIONAL </b>'.strtoupper($dep[0]['dep_departamento']).'<br>
                                <b>REPORTE </b>POA '.$this->gestion.' ARTICULADO AL PEI 2016 - 2020
                                </FONT>
                            </td>
                          </tr>
                        </table>
                        <hr>
                          <div align="left"><b>DETALLE DE OPERACIONES ARTICULADO A LAS ACCIONES DE CORTO PLAZO Y OBJETIVOS ESTRATEGICOS A NIVEL REGIONAL</b></div>
                        <hr>

                      </th>
                    </tr></thead>
                    <tbody>
                      <tr>
                        <td>';
                        if(count($oe)!=0){
                          $tabla.='
                            <table class="change_order_items" border=1 align=center style="width:100%;">
                              <thead>
                                <tr bgcolor="#1c7368" style="height:15px;">
                                    <th style="width:1%;"><font color="#fff">#</font></th>
                                    <th style="width:15%;"><font color="#fff">OBJETIVO ESTRAT&Eacute;GICO</font></th>
                                    <th style="width:7%;"><font color="#fff">NRO DE OPERACIONES</font></th>

                                    <th style="width:7%;"><font color="#fff">TAREAS. PROG.</font></th>
                                    <th style="width:7%;"><font color="#fff">TAREAS. EVAL.</font></th>
                                    <th style="width:7%;"><font color="#fff">TAREAS CUMP.</font></th>
                                    <th style="width:7%;"><font color="#fff">TAREAS NO CUMP.</font></th>
                                    <th style="width:7%;"><font color="#fff">% CUMP.</font></th>
                                </tr>
                            </thead>
                            <tbody>';
                            $moe=$this->tabla_matriz_region_oe($dep_id);
                            for ($i=1; $i <=count($oe) ; $i++) { 
                              $tabla.='<tr style="height:15px;">';
                              $p='';
                              for ($j=1; $j <=8 ; $j++) { 
                                if($j==8){$p='%';}
                                if($j==2){
                                  $tabla.='<td>'.$moe[$i][$j].'</td>';
                                }
                                else{
                                  $tabla.='<td align=center>'.$moe[$i][$j].''.$p.'</td>';  
                                }
                              }
                              $tabla.='</tr>';
                            }
                          $tabla.='
                            </tbody>
                            <tr>
                              <td colspan=2>'.$moe[count($oe)+1][2].'</td>
                              <td align=center><b>'.$moe[count($oe)+1][3].'</b></td>
                              <td align=center><b>'.$moe[count($oe)+1][4].'</b></td>
                              <td align=center><b>'.$moe[count($oe)+1][5].'</b></td>
                              <td align=center><b>'.$moe[count($oe)+1][6].'</b></td>
                              <td align=center><b>'.$moe[count($oe)+1][7].'</b></td>
                              <td align=center><b>'.$moe[count($oe)+1][8].'%</b></td>
                            </tr> 
                          </table>';
                        }
                $tabla.='</td>
                      </tr>
                      <tr>
                        <td>
                          <table table class="change_order_items" border=1 align=center style="width:100%;">
                            <thead>
                              <tr bgcolor="#1c7368" style="height:15px;">
                                <th><font color="#fff">GR&Aacute;FICO DE EVALUACI&Oacute;N DE OPERACIONES - '.$this->gestion.'</font></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><div id="container" style="width: 950px; height: 340px; margin: 1 auto"></div> </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      <tr>
                    </tbody>
                  </table>';
                       
      echo $tabla;
      ?>
      <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
      <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
      <!-- <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script>  -->

      <script type="text/javascript">
      var chart;

      $(document).ready(function() {
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container',
            defaultSeriesType: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: [
              <?php ;
              for ($i=1; $i <=$nro_oe; $i++){ 
                if($i==$nro_oe){
                  ?>
                  '<?php echo $moe[$i][1];?>'
                  <?php
                }
                else{
                  ?>
                  '<?php echo $moe[$i][1];?>',
                  <?php
                }
              } 
            ?>
            ]
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Rainfall (Tareas)'
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
                this.x +': '+ this.y +' Tareas';
            }
          },
          plotOptions: {
            column: {
              pointPadding: 0.2,
              borderWidth: 0
            }
          },
          series: [{
            name: 'TAREAS PROGRAMAS',color: '#9e9b98',
            data: [
           <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][4]?>, color: '#9e9b98'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][4]?>, color: '#9e9b98'},
                  <?php
                }
              }
            ?>
            ]
        
          }, {
            name: 'TAREAS EVALUADAS',color: '#7cb5ec',
            data: [
            <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][5]?>, color: '#7cb5ec'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][5]?>, color: '#7cb5ec'},
                  <?php
                }
              }
            ?>
            ]
          }, {
            name: 'TAREAS CUMPLIDAS',color: '#04B404',
            data: [
            <?php 
              for ($i=1; $i <=$nro_oe ; $i++){
                if($i==$nro_oe){
                  ?>
                  {y: <?php echo $moe[$i][6]?>, color: '#04B404'}
                  <?php
                }
                else{
                  ?>
                  {y: <?php echo $moe[$i][6]?>, color: '#04B404'},
                  <?php
                }
              }
            ?>
            ]
          }]
        });
      });
    </script>
      </html>
      <?php
    }

    /*----- TABLA MATRIZ - OBJETIVO ESTRATEGICO POR REGIONAL ------*/
    public function tabla_matriz_region_oe($dep_id){
      $moe='';
      $oe=$this->mrep_operaciones->list_nro_ope_oe_regional($dep_id);
      
      for($i=1; $i <=count($oe) ; $i++) { 
        $moe[1][$i]=0; // Nro
        $moe[2][$i]=0; // Objetivo Estrategico
        $moe[3][$i]=0; // Nro de Operaciones 
        $moe[4][$i]=0; // Tareas Programadas
        $moe[5][$i]=0; // Tareas Evaluadas
        $moe[6][$i]=0; // Tareas Cumplidas
        $moe[7][$i]=0; // Tareas No cumplidas
        $moe[8][$i]=0; // $ cumplimiento
      }

      $nro=0;$nro_oe=0;$tprog=0;$teval=0;$tcum=0;$tncum=0;
        foreach($oe as $rowo){
          $eval=$this->matriz_evaluacion_oe_regional($rowo['obj_id'],$dep_id); /// Evaluado
          $nro++;
          $moe[$nro][1]='OE'.$rowo['obj_codigo'];
          $moe[$nro][2]=$rowo['obj_descripcion'];
          $moe[$nro][3]=$rowo['total'];
          $moe[$nro][4]=$eval[7];
          $moe[$nro][5]=$eval[4];
          $moe[$nro][6]=$eval[1];
          $moe[$nro][7]=($eval[2]+$eval[3]);
          $moe[$nro][8]=$eval[5];

          $nro_oe=$nro_oe+$rowo['total'];
          $tprog=$tprog+$eval[7];
          $teval=$teval+$eval[4];
          $tcum=$tcum+$eval[1];
          $tncum=$tncum+($eval[2]+$eval[3]);

        }
        $pcion=0;
        if($eval[7]!=0){
          $pcion=round((($tcum/$tprog)*100),2);
        }

        $nro++;
        $moe[$nro][2]='TOTAL';
        $moe[$nro][3]=$nro_oe;
        $moe[$nro][4]=$tprog;
        $moe[$nro][5]=$teval;
        $moe[$nro][6]=$tcum;
        $moe[$nro][7]=$tncum;
        $moe[$nro][8]=$pcion;

      return $moe;
    }

    /*-------- print total operaciones por regional (Accion de Corto Plazo)---------*/
    public function reporte_total_alineacion_operaciones_acciones($dep_id){
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
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $total=$this->mrep_operaciones->total_ope_oe_regional($dep_id);
      $monto=$this->mrep_operaciones->monto_total_ope_oe_regional($dep_id);
      $ope_total=0;$monto_total=0;
      if(count($total)!=0){
        $ope_total=$total[0]['total'];
      }
      if(count($monto)!=0){
        $monto_total=$monto[0]['monto'];
      }
      $tabla='';

      $oe=$this->mrep_operaciones->list_nro_ope_oe_regional($dep_id);
      $tabla.='<table class="change_order_items" border=1 align=center style="width:100%;"><thead>';
        $tabla.='<tr align=center style="height:45px;">
                      <th style="width:1%;"><font color="#000">

                        <table width="90%" align=center>
                          <tr>
                            <td width=20%; text-align:center;"">
                                <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                            </td>
                            <td width=80%; class="titulo_pdf">
                                <FONT FACE="courier new" size="1">
                                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                <b>PLAN OPERATIVO ANUAL : </b>'.$this->gestion.'<br>
                                <b>REGIONAL </b>'.strtoupper($dep[0]['dep_departamento']).'<br>
                                <b>REPORTE </b>POA '.$this->gestion.' ARTICULADO AL PEI 2016 - 2020
                                </FONT>
                            </td>
                          </tr>
                        </table>
                        <hr>
                          <div align="left"><b>DETALLE DE OPERACIONES ARTICULADO A LAS ACCIONES DE CORTO PLAZO Y OBJETIVOS ESTRATEGICOS A NIVEL REGIONAL</b></div>
                        <hr>

                      </th>
                    </tr></thead>
                    <tbody>
                      <tr>
                        <td>';
                        if(count($oe)!=0){
                          foreach($oe as $rowo){
                            $tabla.='<table class="change_order_items" border=1 align=center style="width:100%;">
                                        <thead>
                                          <tr bgcolor="#1c7368">
                                              <th colspan="12" align="left"><font color="#fff">OE.'.$rowo['obj_codigo'].' .- '.$rowo['obj_descripcion'].'</font></th>
                                          </tr>
                                          <tr bgcolor="#1c7368">
                                              <th style="width:5%;"><font color="#fff">COD. PILAR</font></th>
                                              <th style="width:5%;"><font color="#fff">COD. META</font></th>
                                              <th style="width:5%;"><font color="#fff">COD. RESULTADO</font></th>
                                              <th style="width:5%;"><font color="#fff">COD. ACCI&Oacute;N</font></th>
                                              <th style="width:5%;"><font color="#fff">COD. ACP.</font></th>
                                              <th style="width:40%;"><font color="#fff">ACCI&Oacute;N DE CORTO PLAZO</font></th>
                                              <th style="width:5%;"><font color="#fff">NRO DE OPERACIONES</font></th>

                                              <th style="width:7%;"><font color="#fff">TAREAS. PROG.</font></th>
                                              <th style="width:6%;"><font color="#fff">TAREAS. EVAL.</font></th>
                                              <th style="width:6%;"><font color="#fff">TAREAS CUMP.</font></th>
                                              <th style="width:6%;"><font color="#fff">TAREAS NO CUMP.</font></th>
                                              <th style="width:6%;"><font color="#fff">% CUMP.</font></th>
                                          </tr>
                                      </thead>
                                      <tbody>';
                                      $oe_ope=$this->mrep_operaciones->get_nro_ope_oe_regional($dep_id,$rowo['obj_id']);
                                      $monto_oe_ope=$this->mrep_operaciones->get_monto_ope_oe_regional($dep_id,$rowo['obj_id']);
                                      $monto_obj=0;
                                      if(count($monto_oe_ope)!=0){
                                        $monto_obj=$monto_oe_ope[0]['monto'];
                                      }
                                      $nro_oe=0;$tprog=0;$teval=0;$tcum=0;$tncum=0;
                                      foreach($oe_ope as $row){
                                        $eval=$this->matriz_evaluacion_Acumulado($dep_id,$row['ae']); /// Evaluado
                                        $tabla.=
                                        ' <tr>
                                            <td align="center">'.$row['cod_pilar'].'</td>
                                            <td align="center">'.$row['cod_meta'].'</td>
                                            <td align="center">'.$row['cod_resultado'].'</td>
                                            <td align="center">'.$row['cod_accion'].'</td>
                                            <td align="center">'.$row['acc_codigo'].'</td>
                                            <td>'.$row['acc_descripcion'].'</td>
                                            <td align="center">'.$row['total'].'</td>

                                            <td align="center">'.$eval[7].'</td>
                                            <td align="center">'.$eval[4].'</td>
                                            <td align="center">'.$eval[1].'</td>
                                            <td align="center">'.($eval[2]+$eval[3]).'</td>
                                            <td align="center">'.$eval[5].'%</td>
                                          </tr>';
                                        $nro_oe=$nro_oe+$row['total'];
                                        $tprog=$tprog+$eval[7];
                                        $teval=$teval+$eval[4];
                                        $tcum=$tcum+$eval[1];
                                        $tncum=$tncum+($eval[2]+$eval[3]);
                                      }
                                      $pcion=0;
                                      if($eval[7]!=0){
                                        $pcion=round((($tcum/$tprog)*100),2);
                                      }
                              $tabla.='</tbody>
                                        <tr>
                                          <td colspan="6" align="right"><b>SUB TOTAL NRO DE OPERACIONES </b></td>
                                          <td align="center"><b>'.$nro_oe.'</b></td>

                                          <td align="center"><b>'.$tprog.'</b></td>
                                          <td align="center"><b>'.$teval.'</b></td>
                                          <td align="center"><b>'.$tcum.'</b></td>
                                          <td align="center"><b>'.$tncum.'</b></td>
                                          <td align="center"><b>'.$pcion.'%</b></td>
                                        </tr>';
                              $tabla.='</table>';
                          }
                          
                        }
                $tabla.='</td>
                      </tr>
                    </tbody>
                      <tr>';
                        /*<td align="right"><b>TOTAL OPERACIONES : '.$ope_total.'   ||   MONTO PRESUPUESTO TOTAL : '.$monto_total.' Bs.</b></td>*/
                      $tabla.='
                        <td align="right"><b>TOTAL OPERACIONES : '.$ope_total.'</b></td>
                      </tr>
                  </table>';
                       
      echo $tabla;
      ?>
      </html>
      <?php
    }

    /*-------- REPORTE INSTITUCIONAL PEI-POA --------*/
    public function institucional_pei_poa(){
      $data['menu']=$this->menu(7);
      $data['pei']=$this->pei(1);
      $data['poa']=$this->poa(1);

      $this->load->view('admin/reportes_cns/poa_pei/institucional_pei_poa', $data);
    }

    /*-------- REPORTE PDF INSTITUCIONAL PEI --------*/
    public function reporte_institucional_pei_poa($tp){
      if($tp==1){
        $data['pei_poa']=$this->pei(2);
        $data['titulo']='RESUMEN PEI '.$this->gestion.'';
      }
      else{
        $data['pei_poa']=$this->poa(2);
        $data['titulo']='RESUMEN POA '.$this->gestion.'';
      }
      
      $data['mes'] = $this->mes_nombre();
      $data['conf']=$this->model_proyecto->configuracion();
      
     $this->load->view('admin/reportes_cns/poa_pei/reporte_pei_poa', $data);
    }


    /*-------- PEI --------*/
    public function pei($tp){
      $tabla='';
      $list_obj=$this->mrep_operaciones->list_nro_ope_oe_institucional();
      if($tp==1){
        $tab='<table class="table table-bordered" border=1>';
      }
      else{
        $tab='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
      }
      foreach($list_obj as $rowo){
        $tabla.='          
            '.$tab.'
            <thead>
              <tr class="modo1">
                <th colspan="12">OE.'.$rowo['obj_codigo'].' .- '.$rowo['obj_descripcion'].'</th>
              </tr>
              <tr align="center" class="modo1">
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. PILAR</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. META</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. RESULTADO</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACCI&Oacute;N</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD.ACP.</th>
                <th style="width:18%;" style="background-color: #1c7368; color: #FFFFFF">ACCI&Oacute;N DE CORTO PLAZO</th>
                <th style="width:18%;" style="background-color: #1c7368; color: #FFFFFF">RESULTADO FINAL</th>
                <th style="width:17%;" style="background-color: #1c7368; color: #FFFFFF">INDICADOR DE IMPACTO</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">META</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">GESTI&Oacute;N : '.$this->gestion.'</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">NRO. OPERACIONES</th>
              </tr>
            </thead>
            <tbody>';
            $oe_ope=$this->mrep_operaciones->get_nro_ope_oe_institucional($rowo['obj_id']);
            $nro_oe=0;
            foreach($oe_ope as $row){
              $rf=$this->model_mestrategico->get_resultado_final($row['rf_id']);
              
              $v=1;
              for ($i=2016; $i <=2020 ; $i++) { 
                $mes[$i]=$rf[0]['mes'.$v.''];
                $v++;
              }
              
              $tabla.='
              <tr class="modo1">
                <td style="width: 5%; text-align: center;">'.$row['cod_pilar'][0].'</td>
                <td style="width: 5%; text-align: center;">'.substr($row['cod_meta'], 0, 2).'</td>
                <td style="width: 5%; text-align: center;">'.substr($row['cod_meta'], 0, 4).'</td>
                <td style="width: 5%; text-align: center;">'.$row['cod_accion'].'</td>
                <td style="width: 5%; text-align: center;">'.$row['acc_codigo'].'</td>
                <td style="width: 18%; text-align: left;">'.$row['acc_descripcion'].'</td>
                <td style="width: 18%; text-align: left;">'.$rf[0]['rf_resultado'].'</td>
                <td style="width: 17%; text-align: left;">'.$rf[0]['rf_indicador'].'</td>
                <td style="width: 5%; text-align: right;">'.$rf[0]['rf_meta'].'</td>
                <td style="width: 5%; text-align: right;">'.$mes[$this->gestion].'</td>
                <td style="width: 5%; text-align: right;">'.$row['total'].'</td>
              </tr>';
              $nro_oe=$nro_oe+$row['total'];
            }
            $tabla.='
            </tbody>
          </table><br>';
      }

      return $tabla;
    }

    /*-------- POA --------*/
    public function poa($tp){
      $tabla='';
      $list_obj=$this->mrep_operaciones->list_nro_ope_oe_institucional();
      if($tp==1){
        $tab='<table class="table table-bordered" border=1>';
      }
      else{
        $tab='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
      }
      foreach($list_obj as $rowo){
        $tabla.='          
            '.$tab.'
            <thead>
              <tr class="modo1">
                <th colspan="11">OE.'.$rowo['obj_codigo'].' .- '.$rowo['obj_descripcion'].'</th>
              </tr>
              <tr align="center" class="modo1">
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. PILAR</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. META</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. RESULTADO</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACCI&Oacute;N</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD.ACP.</th>
                <th style="width:17%;" style="background-color: #1c7368; color: #FFFFFF">ACCI&Oacute;N DE CORTO PLAZO</th>
                <th style="width:17%;" style="background-color: #1c7368; color: #FFFFFF">RESULTADO INTERMEDIO</th>
                <th style="width:17%;" style="background-color: #1c7368; color: #FFFFFF">INDICADOR DE PROCESO</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">META GESTI&Oacute;N '.$this->gestion.'</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">NRO. OPERACIONES</th>
                <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">PRESUPUESTO</th>
              </tr>
            </thead>
            <tbody>';
            $oe_ope=$this->mrep_operaciones->get_nro_ope_poa_institucional($rowo['obj_id']);
            $nro_oe=0;
            foreach($oe_ope as $row){
              $monto=$this->mrep_operaciones->suma_monto_indicador_poa($row['ptm_id']);
              $presupuesto=0;
              if(count($monto)!=0){
                $presupuesto=$monto[0]['monto'];
              }
              $v=1;
              for ($i=2016; $i <=2020; $i++){
                $mes[$i]=$row['mes'.$v.''];
                $v++;
              }

              $tabla.='
              <tr class="modo1">
                <td style="width: 5%; text-align: center;">'.$row['cod_pilar'][0].'</td>
                <td style="width: 5%; text-align: center;">'.substr($row['cod_meta'], 0, 2).'</td>
                <td style="width: 5%; text-align: center;">'.substr($row['cod_meta'], 0, 4).'</td>
                <td style="width: 5%; text-align: center;">'.$row['cod_accion'].'</td>
                <td style="width: 5%; text-align: center;">'.$row['acc_codigo'].'</td>
                <td style="width: 17%; text-align: left;">'.$row['acc_id'].'.- '.$row['acc_descripcion'].'</td>
                <td style="width: 17%; text-align: left;">'.$row['rm_resultado'].'</td>
                <td style="width: 17%; text-align: left;">'.$row['ptm_id'].'.- '.$row['ptm_indicador'].'</td>
                <td style="width: 5%; text-align: right;">'.$mes[$this->gestion].'</td>
                <td style="width: 5%; text-align: right;">'.$row['total_ope'].'</td>
                <td style="width: 5%; text-align: right;">'.number_format($presupuesto, 2, ',', '.').'</td>
              </tr>';
            }
            $tabla.='
            </tbody>
          </table><br>';
      }

      return $tabla;
    }

    /*----- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO INSTITUCIONAL (OBJ. ESTRATEGICOS)----*/
    public function matriz_evaluacion_oe($oe_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->mrep_operaciones->evaluacion_oe_nacional($oe_id,1,$i);
        $proc=$this->mrep_operaciones->evaluacion_oe_nacional($oe_id,2,$i);
        $ncum=$this->mrep_operaciones->evaluacion_oe_nacional($oe_id,3,$i);

        $total_prog=$this->mrep_operaciones->total_programado_oe_nacional($oe_id,$i); // total programado prod

        $cum_a=$this->mrep_operaciones->evaluacion_oe_nacional_actividad($oe_id,1,$i);
        $proc_a=$this->mrep_operaciones->evaluacion_oe_nacional_actividad($oe_id,2,$i);
        $ncum_a=$this->mrep_operaciones->evaluacion_oe_nacional_actividad($oe_id,3,$i);
        $total_prog_a=$this->mrep_operaciones->total_programado_oe_nacional_actividad($oe_id,$i); // total programado act

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

        /*------------- Acumulado Actividades -------*/
        if(count($cum_a)!=0){
          $nro_cum_a=$nro_cum_a+$cum_a[0]['total'];
        }
        if(count($proc_a)!=0){
          $nro_proc_a=$nro_proc_a+$proc_a[0]['total'];
        }
        if(count($ncum_a)!=0){
          $nro_ncum_a=$nro_ncum_a+$ncum_a[0]['total'];
        }
        if(count($total_prog_a)!=0){
          $nro_total_prog_a=$nro_total_prog_a+$total_prog_a[0]['total'];
        }

        $nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
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


    /*----- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO REGIONAL (OBJ. ESTRATEGICOS)----*/
    public function matriz_evaluacion_oe_regional($oe_id,$dep_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->mrep_operaciones->evaluacion_oe_regional($oe_id,$dep_id,1,$i);
        $proc=$this->mrep_operaciones->evaluacion_oe_regional($oe_id,$dep_id,2,$i);
        $ncum=$this->mrep_operaciones->evaluacion_oe_regional($oe_id,$dep_id,3,$i);

        $total_prog=$this->mrep_operaciones->total_programado_oe_regional($oe_id,$dep_id,$i); // total programado prod

        $cum_a=$this->mrep_operaciones->evaluacion_oe_regional_actividad($oe_id,$dep_id,1,$i);
        $proc_a=$this->mrep_operaciones->evaluacion_oe_regional_actividad($oe_id,$dep_id,2,$i);
        $ncum_a=$this->mrep_operaciones->evaluacion_oe_regional_actividad($oe_id,$dep_id,3,$i);
        $total_prog_a=$this->mrep_operaciones->total_programado_oe_regional_actividad($oe_id,$dep_id,$i); // total programado act

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

        /*------------- Acumulado Actividades -------*/
        if(count($cum_a)!=0){
          $nro_cum_a=$nro_cum_a+$cum_a[0]['total'];
        }
        if(count($proc_a)!=0){
          $nro_proc_a=$nro_proc_a+$proc_a[0]['total'];
        }
        if(count($ncum_a)!=0){
          $nro_ncum_a=$nro_ncum_a+$ncum_a[0]['total'];
        }
        if(count($total_prog_a)!=0){
          $nro_total_prog_a=$nro_total_prog_a+$total_prog_a[0]['total'];
        }

        $nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
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

    /*---------- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO ---------*/
    public function matriz_evaluacion_Accion_cplazo($acc_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->mrep_operaciones->evaluacion_acplazo_nacional($acc_id,1,$i);
        $proc=$this->mrep_operaciones->evaluacion_acplazo_nacional($acc_id,2,$i);
        $ncum=$this->mrep_operaciones->evaluacion_acplazo_nacional($acc_id,3,$i);

        $total_prog=$this->mrep_operaciones->total_programado_acplazo_nacional($acc_id,$i); // total programado prod

        $cum_a=$this->mrep_operaciones->evaluacion_acplazo_nacional_actividad($acc_id,1,$i);
        $proc_a=$this->mrep_operaciones->evaluacion_acplazo_nacional_actividad($acc_id,2,$i);
        $ncum_a=$this->mrep_operaciones->evaluacion_acplazo_nacional_actividad($acc_id,3,$i);
        $total_prog_a=$this->mrep_operaciones->total_programado_acplazo_nacional_nacional_actividad($acc_id,$i); // total programado act

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

        /*------------- Acumulado Actividades -------*/
        if(count($cum_a)!=0){
          $nro_cum_a=$nro_cum_a+$cum_a[0]['total'];
        }
        if(count($proc_a)!=0){
          $nro_proc_a=$nro_proc_a+$proc_a[0]['total'];
        }
        if(count($ncum_a)!=0){
          $nro_ncum_a=$nro_ncum_a+$ncum_a[0]['total'];
        }
        if(count($total_prog_a)!=0){
          $nro_total_prog_a=$nro_total_prog_a+$total_prog_a[0]['total'];
        }

        $nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
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

    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO ACP REGIONAL --------------*/
    public function matriz_evaluacion_Acumulado($dep_id,$acc_id){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->mrep_operaciones->evaluacion_acplazo_regional($dep_id,$acc_id,1,$i);
        $proc=$this->mrep_operaciones->evaluacion_acplazo_regional($dep_id,$acc_id,2,$i);
        $ncum=$this->mrep_operaciones->evaluacion_acplazo_regional($dep_id,$acc_id,3,$i);
        $total_prog=$this->mrep_operaciones->total_programado_acplazo_regional($dep_id,$acc_id,$i); // total programado prod

        $cum_a=$this->mrep_operaciones->evaluacion_acplazo_regional_actividad($dep_id,$acc_id,1,$i);
        $proc_a=$this->mrep_operaciones->evaluacion_acplazo_regional_actividad($dep_id,$acc_id,2,$i);
        $ncum_a=$this->mrep_operaciones->evaluacion_acplazo_regional_actividad($dep_id,$acc_id,3,$i);
        $total_prog_a=$this->mrep_operaciones->total_programado_acplazo_regional_actividad($dep_id,$acc_id,$i); // total programado act

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

        /*------------- Acumulado Actividades -------*/
        if(count($cum_a)!=0){
          $nro_cum_a=$nro_cum_a+$cum_a[0]['total'];
        }
        if(count($proc_a)!=0){
          $nro_proc_a=$nro_proc_a+$proc_a[0]['total'];
        }
        if(count($ncum_a)!=0){
          $nro_ncum_a=$nro_ncum_a+$ncum_a[0]['total'];
        }
        if(count($total_prog_a)!=0){
          $nro_total_prog_a=$nro_total_prog_a+$total_prog_a[0]['total'];
        }

        $nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
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


    /*------------- Evaluacion Operaciones (Unidades)--------------*/
    public function matriz_evaluado_proyecto($proy_id,$ae){
      for ($i=1; $i <=7 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;

        for ($i=1; $i <=$this->tmes ; $i++) { 
          /*------ Trimestral Productos Acumulado -----------*/
          $cum=$this->mrep_operaciones->evaluacion_proyecto($proy_id,1,$i,$ae); // cumplido - prod
          if(count($cum)!=0){
            $nro_cum=$nro_cum+$cum[0]['total'];
          }

          $proc=$this->mrep_operaciones->evaluacion_proyecto($proy_id,2,$i,$ae); // en proceso - prod
          if(count($proc)!=0){
            $nro_proc=$nro_proc+$proc[0]['total'];
          }

          $ncum=$this->mrep_operaciones->evaluacion_proyecto($proy_id,3,$i,$ae); // no cumplido - prod
          if(count($ncum)!=0){
            $nro_ncum=$nro_ncum+$ncum[0]['total'];
          }

          $total_prog=$this->mrep_operaciones->total_programado_accion($proy_id,$i,$ae); // total programado - prod
          if(count($total_prog)!=0){
            $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
          }
          /*--------------------------------------*/
          /*------ Trimestral Actividad Acumulado -----------*/
          $cum_a=$this->mrep_operaciones->evaluacion_proyecto_actividad($proy_id,1,$i,$ae); // cumplido - prod
          if(count($cum_a)!=0){
            $nro_cum_a=$nro_cum_a+$cum_a[0]['total'];
          }

          $proc_a=$this->mrep_operaciones->evaluacion_proyecto_actividad($proy_id,2,$i,$ae); // en proceso - prod
          if(count($proc_a)!=0){
            $nro_proc_a=$nro_proc_a+$proc_a[0]['total'];
          }

          $ncum_a=$this->mrep_operaciones->evaluacion_proyecto_actividad($proy_id,3,$i,$ae); // no cumplido - prod
          if(count($ncum_a)!=0){
            $nro_ncum_a=$nro_ncum_a+$ncum_a[0]['total'];
          }

          $total_prog_a=$this->mrep_operaciones->total_programado_accion_actividad($proy_id,$i,$ae); // total programado - prod
          if(count($total_prog_a)!=0){
            $nro_total_prog_a=$nro_total_prog_a+$total_prog_a[0]['total'];
          }
          /*--------------------------------------*/
        }
        
        $nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;

        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
        
        $cat[1]=$nro_1; // cumplidos
        $cat[2]=$nro_2; // en proceso
        $cat[3]=$nro_3; // no cumplido
        $cat[4]=$total; // Total Evaluado
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*------------------------------------- MENU -----------------------------------*/
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
  /*=========================================================================================================================*/
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

    function estilo_vertical(){
      $estilo_vertical = '<style>
      body{
          font-family: sans-serif;
          }
      table{
          font-size: 8px;
          width: 100%;
          background-color:#fff;
      }
      .mv{font-size:10px;}
      .verde{ width:100%; height:5px; background-color:#1c7368;}
      .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      .siipp{width:120px;}

      .titulo_pdf {
          text-align: left;
          font-size: 8px;
      }
      .tabla {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 8px;
      width: 100%;

      }
      .tabla th {
      padding: 2px;
      font-size: 6px;
      background-color: #1c7368;
      background-repeat: repeat-x;
      color: #FFFFFF;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #558FA6;
      border-bottom-color: #558FA6;
      text-transform: uppercase;
      }
      .tabla .modo1 {
      font-size: 6px;
      font-weight:bold;
     
      background-image: url(fondo_tr01.png);
      background-repeat: repeat-x;
      color: #34484E;
     
      }
      .tabla .modo1 td {
      padding: 1px;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #A4C4D0;
      border-bottom-color: #A4C4D0;
      }
    </style>';
      return $estilo_vertical;
    }
}