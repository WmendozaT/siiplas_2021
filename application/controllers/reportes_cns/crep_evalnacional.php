<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalnacional extends CI_Controller {  
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
            $this->load->model('mantenimiento/mapertura_programatica');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }


    /*------------------- Evaluacion A nivel Institucional  -------------------*/
    public function nacional_institucional(){
      $data['menu']=$this->menu(7); //// genera menu
      $lista_aper_padres = $this->model_proyecto->list_prog();//lista de aperturas padres 
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

      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0; // Prog.
        $p[2][$i]=0; // Ejec.
        $p[3][$i]=0; // Efi.
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Menor
        $p[6][$i]=0; // Entre
        $p[7][$i]=0; // Mayor
      }

      foreach($lista_aper_padres  as $rowa){
      //  echo "PADRE : ".$rowa['aper_programa']."-".$rowa['aper_proyecto']."-".$rowa['aper_actividad']." -> ".$rowa['aper_ponderacion']."%<br>";
        $tabla=$this->proyectos($rowa['aper_programa']);
        
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowa['aper_ponderacion'])/100),2);
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowa['aper_ponderacion'])/100),2);
          if($p[1][$i]!=0){
            $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
          }
          $p[4][$i]=$m[$i];

          if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;}
          if ($p[3][$i] >= 76 && $p[3][$i] <= 90.9) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;}
          if($p[3][$i] >= 91){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;}
        }

        /*for ($i=1; $i <=2 ; $i++) { 
          for ($j=1; $j <=12 ; $j++) { 
            echo "[".$tabla[$i][$j]."]";
          }
          echo "<br>";
        }*/
      }

        /*echo "A NIVEL INSTITUCIONAL <br>";
        for ($i=1; $i <=4 ; $i++) { 
          for ($j=1; $j <=12 ; $j++) { 
            echo "[".$p[$i][$j]."]";
          }
          echo "<br>";
        }*/
      $data['tabla']=$p;
      $data['print_nal']=$this->get_print_institucion($p);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/einstitucional', $data);
    }

    /*----------------------------- Imprime Evaluacion Nivel Institucion --------------------------*/
    public function get_print_institucion($p){
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
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="90%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL
                        </FONT>
                    </td>
                  </tr>
                </table>
                <hr>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL NACIONAL<b/></FONT><br>
                      <div id="graf_eficacia_print" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT>
                      <div id="regresion2" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$p[2][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div>
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="90%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL NACIONAL<br> 
                        </FONT>
                    </td>
                  </tr>
                </table>
                <hr>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL NACIONAL<b/></FONT><br>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL NACIONAL<b/></FONT><br>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $p[5][1]?>, color: 'red'},{y: <?php echo $p[5][2]?>, color: 'red'},{y: <?php echo $p[5][3]?>, color: 'red'},{y: <?php echo $p[5][4]?>, color: 'red'},{y: <?php echo $p[5][5]?>, color: 'red'},{y: <?php echo $p[5][6]?>, color: 'red'},{y: <?php echo $p[5][7]?>, color: 'red'},{y: <?php echo $p[5][8]?>, color: 'red'},{y: <?php echo $p[5][9]?>, color: 'red'},{y: <?php echo $p[5][10]?>, color: 'red'},{y: <?php echo $p[5][11]?>, color: 'red'},{y: <?php echo $p[5][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $p[6][1]?>, color: 'yellow'},{y: <?php echo $p[6][2]?>, color: 'yellow'},{y: <?php echo $p[6][3]?>, color: 'yellow'},{y: <?php echo $p[6][4]?>, color: 'yellow'},{y: <?php echo $p[6][5]?>, color: 'yellow'},{y: <?php echo $p[6][6]?>, color: 'yellow'},{y: <?php echo $p[6][7]?>, color: 'yellow'},{y: <?php echo $p[6][8]?>, color: 'yellow'},{y: <?php echo $p[6][9]?>, color: 'yellow'},{y: <?php echo $p[6][10]?>, color: 'yellow'},{y: <?php echo $p[6][11]?>, color: 'yellow'},{y: <?php echo $p[6][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $p[7][1]?>, color: 'green'},{y: <?php echo $p[7][2]?>, color: 'green'},{y: <?php echo $p[7][3]?>, color: 'green'},{y: <?php echo $p[7][4]?>, color: 'green'},{y: <?php echo $p[7][5]?>, color: 'green'},{y: <?php echo $p[7][6]?>, color: 'green'},{y: <?php echo $p[7][7]?>, color: 'green'},{y: <?php echo $p[7][8]?>, color: 'green'},{y: <?php echo $p[7][9]?>, color: 'green'},{y: <?php echo $p[7][10]?>, color: 'green'},{y: <?php echo $p[7][11]?>, color: 'green'},{y: <?php echo $p[7][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion2',
            defaultSeriesType: 'line'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
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
                  data: [ <?php echo $p[1][1];?>, <?php echo $p[1][2];?>, <?php echo $p[1][3];?>, <?php echo $p[1][4];?>, <?php echo $p[1][5];?>, <?php echo $p[1][6];?>, <?php echo $p[1][7];?>, <?php echo $p[1][8];?>, <?php echo $p[1][9];?>, <?php echo $p[1][10];?>, <?php echo $p[1][11];?>, <?php echo $p[1][12];?>]
                },
                {
                  name: 'EJECUCIÓN ACUMULADA EN %',
                  data: [ <?php echo $p[2][1];?>, <?php echo $p[2][2];?>, <?php echo $p[2][3];?>, <?php echo $p[2][4];?>, <?php echo $p[2][5];?>, <?php echo $p[2][6];?>, <?php echo $p[2][7];?>, <?php echo $p[2][8];?>, <?php echo $p[2][9];?>, <?php echo $p[2][10];?>, <?php echo $p[2][11];?>, <?php echo $p[2][12];?>]
                }
            ]
        });
      });
    </script>
    <script type="text/javascript">
    var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Programación',
          data = [{ 
              y: <?php echo $p[1][1]?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][2];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][3];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][4];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][5];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][6];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][7];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][8];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][9];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][10];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][11];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $p[1][12];?>,
              color: '#8f8fde',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_prog_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <script type="text/javascript">
      var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Ejecución',
          data = [{ 
              y: <?php echo $p[2][1]?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][2];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][3];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][4];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][5];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][6];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][7];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][8];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][9];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][10];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][11];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $p[2][12];?>,
              color: '#61d1e4',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_ejec_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <?php

    return $tabla;
    }    
    /*---------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------*/

    /*------------------------ Proyectos -------------------------*/
    public function proyectos($aper_programa){
      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $proyectos=$this->model_proyecto->list_proyectos(1,$aper_programa,4,1);
     // $proyectos=$this->model_evalnacional->proyecto2($aper_programa,4);
      foreach($proyectos  as $rowp){
      //  echo ">>> PROYECTO : ".$rowp['proy_act']." : ".$rowp['proy_id']."---".$rowp['proy_nombre']." -> ".$rowp['proy_ponderacion']."%<br>";
        $tabla=$this->componentes($rowp['proy_id']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['proy_ponderacion'])/100),2);
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['proy_ponderacion'])/100),2);
        }

          /*for ($i=1; $i <=2 ; $i++) { 
            for ($j=1; $j <=12 ; $j++) { 
              echo "[".$tabla[$i][$j]."]";
            }
            echo "<br>";
          }*/
      }

      return $p;
    }


    /*------------------------ Componentes -------------------------*/
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

            /*for ($i=1; $i <=2 ; $i++) { 
              for ($j=1; $j <=12 ; $j++) { 
                echo "[".$tabla[$i][$j]."]";
              }
              echo "<br>";
            }*/
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
          if($act==1){
            $actividad = $this->model_actividad->list_act_anual($rowp['prod_id']);
            $tabla=$this->actividades($rowp['prod_id']); 
              for ($i=1; $i <=12 ; $i++) { 
                $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['prod_ponderacion'])/100),2);
                $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['prod_ponderacion'])/100),2);
              }
              /*for ($i=1; $i <=2 ; $i++) { 
               for ($j=1; $j <=12 ; $j++) { 
                 echo "[[".$tabla[$i][$j]."]";
               }
               echo "<br>";
              }*/
          }
          else{
            $tabla=$this->temporalidad_productos($rowp['prod_id']);
              for ($i=1; $i <=12 ; $i++) { 
                $p[1][$i]=$p[1][$i]+$tabla[1][$i];
                $p[2][$i]=$p[2][$i]+$tabla[2][$i];
              }

              /*for ($i=1; $i <=2 ; $i++) { 
               for ($j=1; $j <=12 ; $j++) { 
                 echo "[[".$tabla[$i][$j]."]]";
               }
               echo "<br>";
              }*/
          }
        }
      }

      return $p;
    }

    /*---------------------------------- Actividades -----------------------------------*/
    public function actividades($prod_id){
      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $actividad = $this->model_actividad->list_act_anual($prod_id);
      if(count($actividad)!=0){
        foreach($actividad  as $row){
          if($row['act_ponderacion']!=0){
            //  echo "---------- ||| Actividad : ".$row['act_id']." : ".$row['act_actividad']." -> ".$row['act_ponderacion']."%<br>";
            $tabla=$this->temporalidad_actividades($row['act_id']);

            /*for ($i=1; $i <=2 ; $i++) { 
                 for ($j=1; $j <=12 ; $j++) { 
                   echo "[[".$tabla[$i][$j]."]";
                 }
                 echo "<br>";
               }*/

            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=$p[1][$i]+$tabla[1][$i];
              $p[2][$i]=$p[2][$i]+$tabla[2][$i];
            }
          }
        }
      }
      else{
        $tab=$this->temporalidad_productos_programado($prod_id);
        for ($i=1; $i <=12 ; $i++) { 
            $p[1][$i]=$p[1][$i]+$tab[3][$i];
            $p[2][$i]=$p[2][$i]+$tab[6][$i];
        }
      }
      
      return $p;
    }
    /*--------------------------------------------------------------------------------*/

    /*------------------- Evaluacion A nivel Programas -------------------*/
    public function nivel_programas(){
      $data['menu']=$this->menu(7); //// genera menu
      $data['prog']=$this->programas(); /// Programas

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/programas/eprogramas', $data);
    }

    /*------------------- Imprimir Evaluacion A nivel Programas -------------------*/
    public function print_nivel_programas(){
      $data['prog']=$this->imprimir_programas();

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/programas/imprimir_eprogramas', $data);
    }

    /*---------------------------------- Programas -------------------------------------------*/
    public function programas(){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
        $lista_aper_padres = $this->model_proyecto->list_prog();
      
        $tabla .='<div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                              <th style="width:1%;"><center>Nro</center></th>
                              <th style="width:5%;"><center>GESTI&Oacute;N</center></th>
                              <th style="width:10%;"><center>CAT. PROGRAMATICA</center></th>
                              <th style="width:10%;"><center>DESCRIPCI&Oacute;N</center></th>
                              <th style="width:6%;"><center>PONDERACI&Oacute;N</center></th>
                              <th style="width:3%;"></th>
                              <th style="width:65%;"><center>TEMPORALIDAD</center></th>
                            </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
        foreach($lista_aper_padres  as $rowa){
            $tab=$this->proyectos($rowa['aper_programa']);
            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
              if($tab[1][$i]!=0){
                $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
              }
            }
            $nro++;
            $tabla .='<tr>';
              $tabla .='<td>'.$nro.'';
                $tabla .= ' <center>
                              <a href="'.site_url("").'/rep/get_nprograma/'.$rowa['aper_programa'].'" title="REPORTE INSTITUCIONAL A NIVEL PROGRAMA : '.$rowa['aper_programa'].' '.$rowa['aper_proyecto'].' '.$rowa['aper_actividad'].'" id="myBtn1'.$rowa['aper_id'].'"><img src="' . base_url() . 'assets/ifinal/rep_graf.png" WIDTH="40" HEIGHT="40"/></a><br>
                              <img id="load1'.$rowa['aper_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                            </center><br>';
                $tabla .= ' <center>
                              <a href="'.site_url("").'/rep/eval_nproyecto/'.$rowa['aper_programa'].'" title="EVALUACI&Oacute;N A NIVEL DE UNIDADES EJECUTORAS" id="myBtn2'.$rowa['aper_id'].'"><img src="' . base_url() . 'assets/ifinal/carp_est.jpg" WIDTH="40" HEIGHT="40"/></a><br>AC. OP.
                              <img id="load2'.$rowa['aper_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                            </center>';
              $tabla .='</td>';
              $tabla .='<td>'.$rowa['aper_gestion'].'</td>';
              $tabla .='<td>'.$rowa['aper_programa'].' '.$rowa['aper_proyecto'].' '.$rowa['aper_actividad'].'</td>';
              $tabla .='<td>'.$rowa['aper_descripcion'].'</td>';
              $tabla .='<td>'.$rowa['aper_ponderacion'].' % </td>';
              $tabla .='<td align=center><a data-toggle="modal" data-target="#'.$rowa['aper_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" ><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="40" HEIGHT="40"/></a>
                          <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowa['aper_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">
                                    CATEGORIA PROGRAM&Aacute;TICA : '.$rowa['aper_programa'].' '.$rowa['aper_proyecto'].' '.$rowa['aper_actividad'].' - '.$rowa['aper_descripcion'].'
                                  </h4>
                                </div>
                                <div class="modal-body no-padding">
                                  <div class="well">
                                    <div id="graf_eficacia'.$rowa['aper_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                    <hr>
                                    <div id="container'.$rowa['aper_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
              </td>';
              $tabla .='<td>';
              $tabla .='<table class="table table table-bordered">
                        <thead>
                            <tr>
                              <th style="width:7%;"><center></center></th>
                              <th style="width:8%;"><center>ENE.</center></th>
                              <th style="width:8%;"><center>FEB.</center></th>
                              <th style="width:8%;"><center>MAR.</center></th>
                              <th style="width:8%;"><center>ABR.</center></th>
                              <th style="width:8%;"><center>MAY.</center></th>
                              <th style="width:8%;"><center>JUN.</center></th>
                              <th style="width:8%;"><center>JUL.</center></th>
                              <th style="width:8%;"><center>AGO.</center></th>
                              <th style="width:8%;"><center>SEPT.</center></th>
                              <th style="width:8%;"><center>OCT.</center></th>
                              <th style="width:8%;"><center>NOV.</center></th>
                              <th style="width:8%;"><center>DIC.</center></th>
                            </tr>
                        </thead>
                          <tbody>';
                            $tabla .='<tr>';
                              $tabla .='<td>%PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EFI</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                          $tabla .='
                          </tbody>
                        </table>';
              $tabla .='</td>';
              $tabla.='<script>
                        document.getElementById("myBtn1'.$rowa['aper_id'].'").addEventListener("click", function(){
                        document.getElementById("load1'.$rowa['aper_id'].'").style.display = "block";
                      });
                      document.getElementById("myBtn2'.$rowa['aper_id'].'").addEventListener("click", function(){
                        document.getElementById("load2'.$rowa['aper_id'].'").style.display = "block";
                      });
                      </script>';
                  ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                  <script type="text/javascript">
                  var chart;
                  $(document).ready(function() {
                    chart = new Highcharts.chart('graf_eficacia'+<?php echo $rowa['aper_id']; ?>, {
                              chart: {
                                  type: 'column'
                              },
                              title: {
                                  text: 'EFICACIA INSTITUCIONAL A NIVEL DE PROGRAMAS '
                              },
                              xAxis: {
                                  categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
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
                                  pointFormat: '{series.name}: <br/> TOTAL:   {point.y}'
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

                                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                                  data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

                              }, {
                                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                                  data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
                              }, {
                                  name: '<b style="color: green;">MAYOR A 91%</b>',
                                  data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
                              }]

                          });
                      });
                  </script>
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowa['aper_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DEL PROGRAMA'
                      },
                      subtitle: {
                        text: ''
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
                                data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                            },
                            {
                                name: 'EJECUCIÓN ACUMULADA EN %',
                                data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                            }
                        ]
                    });
                  });
                </script>
                <?php
            $tabla .='</tr>';
        }
        $tabla .='</tbody>
                </table>
              </div>';

      return $tabla;
    }

    /*---------------------------------- Imprimir  Programas -------------------------------------------*/
    public function imprimir_programas(){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
        $lista_aper_padres = $this->model_proyecto->list_prog();
        $tabla .='<div class="table-responsive" align=center>
                    <table class="change_order_items" style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                        <thead>
                            <tr>
                              <th colspan=7>
                                <table width="100%">
                                  <tr>
                                      <td width=20%; text-align:center;"">
                                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                      </td>
                                      <td width=60%; class="titulo_pdf">
                                          <FONT FACE="courier new" size="1">
                                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                          <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PROGRAMAS '.$this->gestion.'<br> 
                                          </FONT>
                                      </td>
                                      <td width=20%; text-align:center;"">
                                      </td>
                                  </tr>
                              </table>
                              </th>
                            </tr>
                            <tr class="even_row" bgcolor="#1c7368" align=center>
                              <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                              <th style="width:5%;"><font color="#ffffff">GESTI&Oacute;N</font></th>
                              <th style="width:10%;"><font color="#ffffff">CAT. PROGRAMATICA</font></th>
                              <th style="width:10%;"><font color="#ffffff">DESCRIPCI&Oacute;N</font></th>
                              <th style="width:6%;"><font color="#ffffff">PONDERACI&Oacute;N</font></th>
                              <th style="width:3%;"><font color="#ffffff">GRAFICO COMPARATIVO</font></th>
                              <th style="width:65%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                            </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
        foreach($lista_aper_padres  as $rowa){
            $tab=$this->proyectos($rowa['aper_programa']);
            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
              if($tab[1][$i]!=0){
                $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
              }
            }
            $nro++;
            $tabla .='<tr >';
              $tabla .='<td>'.$nro.'</td>';
              $tabla .='<td>'.$rowa['aper_gestion'].'</td>';
              $tabla .='<td>'.$rowa['aper_programa'].' '.$rowa['aper_proyecto'].' '.$rowa['aper_actividad'].'</td>';
              $tabla .='<td>'.$rowa['aper_descripcion'].'</td>';
              $tabla .='<td>'.$rowa['aper_ponderacion'].' % </td>';
              $tabla .='<td align=center>
                          <b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N F&Iacute;SICA DEL PROGRAMA</b>
                          <div id="container'.$rowa['aper_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div>
                        <td>';
              $tabla .='<table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>';
                            $tabla .='<tr>';
                              $tabla .='<td>%PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EFI</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                          $tabla .='
                          </tbody>
                        </table>';
              $tabla .='</td>';
                  ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                 
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowa['aper_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: ''
                      },
                      subtitle: {
                        text: ''
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
                                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                },
                                {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                }
                            ]
                    });
                  });
                </script>
                <?php
            $tabla .='</tr>';
        }
        $tabla .='</tbody>
                </table>
              </div>';

      return $tabla;
    }

    /*--------------------------------- GET PROGRAMAS ---------------------------------*/
    public function get_programa($aper_programa){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);

      $tab=$this->proyectos($aper_programa);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          if($tab[1][$i]!=0){
            $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
            if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
            if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
          }
        }

        $data['p']=$tab; /// Programado,Ejecutado
        $data['e']=$p; /// Eficacia

      $data['print_prog']=$this->get_print_programa($aper_programa);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/programas/get_programa', $data);
    }

    public function get_print_programa($aper_programa){
      $programa=$this->model_evalnacional->programa($aper_programa);
      $tab=$this->proyectos($aper_programa);
        for ($i=1; $i <=12 ; $i++) { 
          $e[1][$i]=0;$e[2][$i]=0;$e[3][$i]=0;$e[4][$i]=0;
          if($tab[1][$i]!=0){
            $e[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($e[1][$i]<=75){$e[2][$i] = $e[1][$i];}else{$e[2][$i] = 0;}
            if($e[1][$i] >= 76 && $e[1][$i] <= 90.9) {$e[3][$i] = $e[1][$i];}else{$e[3][$i] = 0;}
            if($e[1][$i] >= 91){$e[4][$i] = $e[1][$i];}else{$e[4][$i] = 0;}
          }
        }
      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="80%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DEL PROGRAMA : '.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].'<br> 
                        </FONT>
                    </td>
                  </tr>
                </table>

                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL A NIVEL DEL PROGRAMA<b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></FONT>
                      <div id="graf_eficacia_print" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$e[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></FONT>
                      <div id="regresion" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div>
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="80%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DEL PROGRAMA : '.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].'<br> 
                        </FONT>
                    </td>
                  </tr>
                </table>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL A NIVEL DEL PROGRAMA<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></FONT>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL A NIVEL DEL PROGRAMA<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></FONT>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $e[2][1]?>, color: 'red'},{y: <?php echo $e[2][2]?>, color: 'red'},{y: <?php echo $e[2][3]?>, color: 'red'},{y: <?php echo $e[2][4]?>, color: 'red'},{y: <?php echo $e[2][5]?>, color: 'red'},{y: <?php echo $e[2][6]?>, color: 'red'},{y: <?php echo $e[2][7]?>, color: 'red'},{y: <?php echo $e[2][8]?>, color: 'red'},{y: <?php echo $e[2][9]?>, color: 'red'},{y: <?php echo $e[2][10]?>, color: 'red'},{y: <?php echo $e[2][11]?>, color: 'red'},{y: <?php echo $e[2][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $e[3][1]?>, color: 'yellow'},{y: <?php echo $e[3][2]?>, color: 'yellow'},{y: <?php echo $e[3][3]?>, color: 'yellow'},{y: <?php echo $e[3][4]?>, color: 'yellow'},{y: <?php echo $e[3][5]?>, color: 'yellow'},{y: <?php echo $e[3][6]?>, color: 'yellow'},{y: <?php echo $e[3][7]?>, color: 'yellow'},{y: <?php echo $e[3][8]?>, color: 'yellow'},{y: <?php echo $e[3][9]?>, color: 'yellow'},{y: <?php echo $e[3][10]?>, color: 'yellow'},{y: <?php echo $e[3][11]?>, color: 'yellow'},{y: <?php echo $e[3][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $e[4][1]?>, color: 'green'},{y: <?php echo $e[4][2]?>, color: 'green'},{y: <?php echo $e[4][3]?>, color: 'green'},{y: <?php echo $e[4][4]?>, color: 'green'},{y: <?php echo $e[4][5]?>, color: 'green'},{y: <?php echo $e[4][6]?>, color: 'green'},{y: <?php echo $e[4][7]?>, color: 'green'},{y: <?php echo $e[4][8]?>, color: 'green'},{y: <?php echo $e[4][9]?>, color: 'green'},{y: <?php echo $e[4][10]?>, color: 'green'},{y: <?php echo $e[4][11]?>, color: 'green'},{y: <?php echo $e[4][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion',
            defaultSeriesType: 'line'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
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
                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                },
                {
                    name: 'EJECUCIÓN ACUMULADA EN %',
                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                }
            ]
        });
      });
    </script>
    <script type="text/javascript">
    var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Programación',
          data = [{ 
              y: <?php echo $tab[1][1]?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][2];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][3];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][4];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][5];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][6];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][7];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][8];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][9];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][10];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][11];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][12];?>,
              color: '#8f8fde',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_prog_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <script type="text/javascript">
      var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Ejecución',
          data = [{ 
              y: <?php echo $tab[2][1]?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][2];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][3];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][4];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][5];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][6];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][7];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][8];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][9];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][10];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][11];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][12];?>,
              color: '#61d1e4',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_ejec_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <?php

    return $tabla;
    }    
    /*---------------------------------------------------------------------------------*/

    /*------------------- Evaluacion A nivel de Proyectos -------------------*/
    public function nivel_proyecto($aper_programa){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      
      $data['ope_fun']=$this->proyecto($aper_programa,4);
      $data['ope_fort']=$this->proyecto($aper_programa,3);
      $data['proy_inv']=$this->proyecto($aper_programa,1);
      //$data['prog']=$this->programas(); /// Programas

    //  $data['print_proy']=$this->print_rep_proyecto($aper_programa);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/proyectos/eproyectos', $data);
    }

    /*------------------- Imprimir Evaluacion A nivel Proyecto -------------------*/
    public function print_nivel_proyecto($aper_programa){
      $data['proy']=$this->imprimir_proyecto($aper_programa);

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/proyectos/imprimir_eproyectos', $data);
    }

    /*----------------------- Lista de Proyectos ------------------------------*/
    public function proyecto($aper_programa,$tp_id){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
      $nro=0;
      $proyectos=$this->model_evalnacional->proyecto($aper_programa,$tp_id);
      foreach($proyectos  as $rowp){
        $nro++;
        $tab=$this->componentes($rowp['proy_id']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          if($tab[1][$i]!=0){
            $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
            if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
            if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
          }
        }

        $tabla .='<tr>';
          $tabla .='<td>'.$nro.'<br>';
          $tabla .= ' <center>
                        <a href="'.site_url("").'/rep/get_nproyecto/'.$aper_programa.'/'.$rowp['proy_id'].'" title="REPORTE INSTITUCIONAL A NIVEL ACCION OPERATIVA : '.$rowp['aper_programa'].' '.$rowp['aper_proyecto'].' '.$rowp['aper_actividad'].'" id="myBtn1'.$rowp['proy_id'].'"><img src="' . base_url() . 'assets/ifinal/rep_graf.png" WIDTH="40" HEIGHT="40"/></a><br>
                        <img id="load1'.$rowp['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                      </center><br><center>
                        <a href="'.site_url("").'/rep/eval_nproceso/'.$aper_programa.'/'.$rowp['proy_id'].'" title="EVALUACI&Oacute;N A NIVEL DE PROCESOS" id="myBtn2'.$rowp['proy_id'].'"><img src="' . base_url() . 'assets/ifinal/carp_est.jpg" WIDTH="40" HEIGHT="40"/></a><br>PROC.
                        <img id="load2'.$rowp['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                      </center>';
          $tabla .='</td>';
          $tabla .='<td>'.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'</td>';
          $tabla .='<td>'.$rowp['proy_nombre'].'</td>';
          $tabla .='<td>'.$rowp['tp_tipo'].'</td>';
          $tabla .='<td>'.$rowp['proy_sisin'].'</td>';
          $tabla .='<td>'.$rowp['fun_nombre'].' '.$rowp['fun_paterno'].' '.$rowp['fun_materno'].'</td>';
          $tabla .='<td>'.$rowp['proy_ponderacion'].'%</td>';
          $tabla .='<td align=center><a data-toggle="modal" data-target="#'.$rowp['proy_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" ><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="40" HEIGHT="40"/></a>
                      <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowp['proy_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                        <div class="modal-dialog modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                &times;
                              </button>
                              <h4 class="modal-title">
                                CATEGORIA PROGRAM&Aacute;TICA : '.$rowp['aper_programa'].' '.$rowp['aper_proyecto'].' '.$rowp['aper_actividad'].' - '.$rowp['proy_nombre'].'
                              </h4>
                            </div>
                            <div class="modal-body no-padding">
                              <div class="well">
                                <div id="graf_eficacia'.$rowp['proy_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                <hr>
                                <div id="container'.$rowp['proy_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>';
          $tabla .='<td>';
          $tabla .='<table class="table table table-bordered">
                      <thead>
                          <tr align=center>
                            <th style="width:7%;"></th>
                            <th style="width:8%;"><font color=#000>ENE.</font></th>
                            <th style="width:8%;"><font color=#000>FEB.</font></th>
                            <th style="width:8%;"><font color=#000>MAR.</font></th>
                            <th style="width:8%;"><font color=#000>ABR.</font></th>
                            <th style="width:8%;"><font color=#000>MAY.</font></th>
                            <th style="width:8%;"><font color=#000>JUN.</font></th>
                            <th style="width:8%;"><font color=#000>JUL.</font></th>
                            <th style="width:8%;"><font color=#000>AGO.</font></th>
                            <th style="width:8%;"><font color=#000>SEPT.</font></th>
                            <th style="width:8%;"><font color=#000>OCT.</font></th>
                            <th style="width:8%;"><font color=#000>NOV.</font></th>
                            <th style="width:8%;"><font color=#000>DIC.</font></th>
                          </tr>
                      </thead>
                        <tbody>';
                          $tabla .='<tr>';
                            $tabla .='<td>%PA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[1][$i].'%</td>';
                              }
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>%EA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[2][$i].'%</td>';
                              } 
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>%EFI</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$p[1][$i].'%</td>';
                              }
                            }
                          $tabla .='</tr>';
                        $tabla .='
                        </tbody>
                      </table>';
          $tabla .='</td>';
          $tabla.='<script>
                        document.getElementById("myBtn1'.$rowp['proy_id'].'").addEventListener("click", function(){
                        document.getElementById("load1'.$rowp['proy_id'].'").style.display = "block";
                      });
                      document.getElementById("myBtn2'.$rowp['proy_id'].'").addEventListener("click", function(){
                        document.getElementById("load2'.$rowp['proy_id'].'").style.display = "block";
                      });
                    </script>';
                ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                  <script type="text/javascript">
                  var chart;
                  $(document).ready(function() {
                    chart = new Highcharts.chart('graf_eficacia'+<?php echo $rowp['proy_id']; ?>, {
                              chart: {
                                  type: 'column'
                              },
                              title: {
                                  text: 'EFICACIA INSTITUCIONAL A NIVEL DE ACCIÓN OPERATIVA '
                              },
                              xAxis: {
                                  categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
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
                                  pointFormat: '{series.name}: <br/> TOTAL:   {point.y}'
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

                                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                                  data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

                              }, {
                                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                                  data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
                              }, {
                                  name: '<b style="color: green;">MAYOR A 91%</b>',
                                  data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][11]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
                              }]

                          });
                      });
                  </script>
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowp['proy_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE ACCI\u00D3N OPERATIVA'
                      },
                      subtitle: {
                        text: ''
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
                                data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                            },
                            {
                                name: 'EJECUCIÓN ACUMULADA EN %',
                                data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                            }
                        ]
                    });
                  });
                </script>
                <?php
        $tabla .='</tr>';
      }

      return $tabla;
    }

     /*----------------------------- Imprimir  Proyectos (Acciones Operativas) ----------------------------------*/
    public function imprimir_proyecto($aper_programa){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
        $programa=$this->model_evalnacional->programa($aper_programa); //// Programa
        $proyectos=$this->model_evalnacional->proyecto($aper_programa,4); /// Operacion de Funcionamiento
        if(count($proyectos)!=0){

           $tabla .='<div class="table-responsive" align=center>
                    <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                        <thead>
                            <tr>
                              <th colspan=7>
                                <table width="100%">
                                  <tr>
                                      <td width=20%; text-align:center;"">
                                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                      </td>
                                      <td width=60%; class="titulo_pdf">
                                          <FONT FACE="courier new" size="1">
                                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                          <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL NACIONAL DE ACCI&Oacute;NES OPERATIVAS<br> 
                                          <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                          <b>OPERACI&Oacute;N DE FUNCIONAMIENTO</b>
                                          </FONT>
                                      </td>
                                      <td width=20%; text-align:center;"">
                                      </td>
                                  </tr>
                              </table>
                              </th>
                            </tr>
                            <tr class="even_row" bgcolor="#1c7368" align=center>
                              <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                              <th style="width:5%;"><font color="#ffffff">CATEGORIA PROGRAM&Aacute;TICA</font></th>
                              <th style="width:10%;"><font color="#ffffff">ACCI&Oacute;N OPERATIVA</font></th>
                              <th style="width:7%;"><font color="#ffffff">TIPO DE OPE.</font></th>
                              <th style="width:6%;"><font color="#ffffff">%</font></th>
                              <th style="width:10%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                              <th style="width:60%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                            </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
          foreach($proyectos  as $rowp){
            $tab=$this->componentes($rowp['proy_id']);
            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
              if($tab[1][$i]!=0){
                $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
              }
            }
            $nro++;
            $tabla .='<tr >';
              $tabla .='<td>'.$nro.'</td>';
              $tabla .='<td>'.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'</td>';
              $tabla .='<td>'.$rowp['proy_nombre'].'</td>';
              $tabla .='<td>'.$rowp['tp_sigla'].'</td>';
              $tabla .='<td>'.$rowp['proy_ponderacion'].'%</td>';
              $tabla .='<td><div id="container'.$rowp['proy_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
              $tabla .='<td>';
              $tabla .='<table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>';
                            $tabla .='<tr>';
                              $tabla .='<td>%PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EFI</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                          $tabla .='
                          </tbody>
                        </table>';
              $tabla .='</td>';
                  ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                 
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowp['proy_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: ''
                      },
                      subtitle: {
                        text: ''
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
                                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                },
                                {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                }
                            ]
                    });
                  });
                </script>
                <?php
            $tabla .='</tr>';
        }
        $tabla .='</tbody>
                </table>
              </div>';
        }
        
        $proyectos=$this->model_evalnacional->proyecto($aper_programa,3); /// Operacion de Fortalecimiento
        if(count($proyectos)!=0){
        $tabla .='<div class="saltopagina"></div>';
        $tabla .='<div class="verde"></div>
                  <div class="blanco"></div>';
        $tabla .='<div class="table-responsive" align=center>
                    <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                        <thead>
                            <tr>
                              <th colspan=7>
                                <table width="100%">
                                  <tr>
                                      <td width=20%; text-align:center;"">
                                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                      </td>
                                      <td width=60%; class="titulo_pdf">
                                          <FONT FACE="courier new" size="1">
                                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                          <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE ACCI&Oacute;NES OPERATIVAS<br> 
                                          <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                          <b>OPERACI&Oacute;N DE FORTALECIMIENTO</b>
                                          </FONT>
                                      </td>
                                      <td width=20%; text-align:center;"">
                                      </td>
                                  </tr>
                              </table>
                              </th>
                            </tr>
                            <tr class="even_row" bgcolor="#1c7368" align=center>
                              <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                              <th style="width:5%;"><font color="#ffffff">CATEGORIA PROGRAM&Aacute;TICA</font></th>
                              <th style="width:10%;"><font color="#ffffff">ACCI&Oacute;N OPERATIVA</font></th>
                              <th style="width:7%;"><font color="#ffffff">TIPO DE OPE.</font></th>
                              <th style="width:6%;"><font color="#ffffff">%</font></th>
                              <th style="width:10%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                              <th style="width:60%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                            </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                foreach($proyectos  as $rowp){
                  $tab=$this->componentes($rowp['proy_id']);
                  for ($i=1; $i <=12 ; $i++) { 
                    $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                    if($tab[1][$i]!=0){
                      $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                      if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                      if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                      if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                    }
                  }
                  $nro++;
                  $tabla .='<tr >';
                    $tabla .='<td>'.$nro.'</td>';
                    $tabla .='<td>'.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'</td>';
                    $tabla .='<td>'.$rowp['proy_nombre'].'</td>';
                    $tabla .='<td>'.$rowp['tp_sigla'].'</td>';
                    $tabla .='<td>'.$rowp['proy_ponderacion'].'%</td>';
                    $tabla .='<td><div id="container'.$rowp['proy_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
                    $tabla .='<td>';
                    $tabla .='<table class="change_order_items" border=1>
                              <thead>
                                  <tr bgcolor="#1c7368" align=center>
                                    <th style="width:7%;"></th>
                                    <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                                  </tr>
                              </thead>
                                <tbody>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%PA</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$tab[1][$i].'%</td>';
                                      }
                                    }
                                  $tabla .='</tr>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%EA</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$tab[2][$i].'%</td>';
                                      } 
                                    }
                                  $tabla .='</tr>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%EFI</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$p[1][$i].'%</td>';
                                      }
                                    }
                                  $tabla .='</tr>';
                                $tabla .='
                                </tbody>
                              </table>';
                    $tabla .='</td>';
                  ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                 
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowp['proy_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: ''
                      },
                      subtitle: {
                        text: ''
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
                                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                },
                                {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                }
                            ]
                    });
                  });
                </script>
                <?php
            $tabla .='</tr>';
        }
        $tabla .='</tbody>
                </table>
              </div>';
        }
        
        $proyectos=$this->model_evalnacional->proyecto($aper_programa,1); /// Proyectos de Inversion
        if(count($proyectos)!=0){
        $tabla .='<div class="saltopagina"></div>';
        $tabla .='<div class="verde"></div>
                  <div class="blanco"></div>';
        $tabla .='<div class="table-responsive" align=center>
                    <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                        <thead>
                            <tr>
                              <th colspan=7>
                                <table width="100%">
                                  <tr>
                                      <td width=20%; text-align:center;"">
                                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                      </td>
                                      <td width=60%; class="titulo_pdf">
                                          <FONT FACE="courier new" size="1">
                                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                          <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE ACCI&Oacute;NES OPERATIVAS<br> 
                                          <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                          <b>PROYECTOS DE INVERSI&Oacute;N</b>
                                          </FONT>
                                      </td>
                                      <td width=20%; text-align:center;"">
                                      </td>
                                  </tr>
                              </table>
                              </th>
                            </tr>
                            <tr class="even_row" bgcolor="#1c7368" align=center>
                              <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                              <th style="width:5%;"><font color="#ffffff">CATEGORIA PROGRAM&Aacute;TICA</font></th>
                              <th style="width:10%;"><font color="#ffffff">ACCI&Oacute;N OPERATIVA</font></th>
                              <th style="width:7%;"><font color="#ffffff">TIPO DE OPE.</font></th>
                              <th style="width:6%;"><font color="#ffffff">%</font></th>
                              <th style="width:10%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                              <th style="width:60%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                            </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                foreach($proyectos  as $rowp){
                  $tab=$this->componentes($rowp['proy_id']);
                  for ($i=1; $i <=12 ; $i++) { 
                    $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                    if($tab[1][$i]!=0){
                      $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                      if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                      if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                      if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                    }
                  }
                  $nro++;
                  $tabla .='<tr >';
                    $tabla .='<td>'.$nro.'</td>';
                    $tabla .='<td>'.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'</td>';
                    $tabla .='<td>'.$rowp['proy_nombre'].'</td>';
                    $tabla .='<td>'.$rowp['tp_sigla'].'</td>';
                    $tabla .='<td>'.$rowp['proy_ponderacion'].'%</td>';
                    $tabla .='<td><div id="container'.$rowp['proy_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
                    $tabla .='<td>';
                    $tabla .='<table class="change_order_items" border=1>
                              <thead>
                                  <tr bgcolor="#1c7368" align=center>
                                    <th style="width:7%;"></th>
                                    <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                                    <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                                  </tr>
                              </thead>
                                <tbody>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%PA</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$tab[1][$i].'%</td>';
                                      }
                                    }
                                  $tabla .='</tr>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%EA</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$tab[2][$i].'%</td>';
                                      } 
                                    }
                                  $tabla .='</tr>';
                                  $tabla .='<tr>';
                                    $tabla .='<td>%EFI</td>';
                                    for ($i=1; $i <=12 ; $i++) {
                                      if($i>=$vi & $i<=$vf){
                                        $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                      }
                                      else{
                                        $tabla .='<td>'.$p[1][$i].'%</td>';
                                      }
                                    }
                                  $tabla .='</tr>';
                                $tabla .='
                                </tbody>
                              </table>';
                    $tabla .='</td>';
                  ?>
                  <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                  <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                 
                  <script type="text/javascript">
                  var chart1;
                  $(document).ready(function() {
                    chart1 = new Highcharts.Chart({
                      chart: {
                        renderTo: 'container'+<?php echo $rowp['proy_id']; ?>,
                        defaultSeriesType: 'line'
                      },
                      title: {
                        text: ''
                      },
                      subtitle: {
                        text: ''
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
                                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                },
                                {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                }
                            ]
                    });
                  });
                </script>
                <?php
            $tabla .='</tr>';
        }
        $tabla .='</tbody>
                </table>
              </div>';
        }
      return $tabla;
    }

    /*--------------------------------- Get Proyecto ---------------------------------*/
    public function get_proyecto($aper_programa,$proy_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); 
      $tab=$this->componentes($proy_id);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          if($tab[1][$i]!=0){
            $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
            if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
            if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
          }
        }

        $data['p']=$tab; /// Programado,Ejecutado
        $data['e']=$p; /// Eficacia

      $data['print_proy']=$this->get_print_proyecto($aper_programa,$proy_id);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/proyectos/get_proyecto', $data);
    }

    public function get_print_proyecto($aper_programa,$proy_id){
      $programa=$this->model_evalnacional->programa($aper_programa);
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

        $tab=$this->componentes($proy_id);
        for ($i=1; $i <=12 ; $i++) { 
          $e[1][$i]=0;$e[2][$i]=0;$e[3][$i]=0;$e[4][$i]=0;
          if($tab[1][$i]!=0){
            $e[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($e[1][$i]<=75){$e[2][$i] = $e[1][$i];}else{$e[2][$i] = 0;}
            if($e[1][$i] >= 76 && $e[1][$i] <= 90.9) {$e[3][$i] = $e[1][$i];}else{$e[3][$i] = 0;}
            if($e[1][$i] >= 91){$e[4][$i] = $e[1][$i];}else{$e[4][$i] = 0;}
          }
        }

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="80%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NCIONAL A NIVEL DE ACCI&Oacute;N OPERATIVA<br> 
                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'
                        </FONT>
                    </td>
                  </tr>
                </table>

                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL A NIVEL DE ACCI&Oacute;N OPERATIVA<b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</b></FONT>
                      <div id="graf_eficacia_print" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$e[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</b></FONT>
                      <div id="regresion" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div> 
                <table width="80%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL A NIVEL NACIONAL DE LA ACCI&Oacute;N OPERATIVA : '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['proy_nombre'].'<br> 
                        </FONT>
                    </td>
                  </tr>
                </table>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL A NIVEL NACIONAL DE ACCI&Oacute;N OPERATIVA<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['aper_descripcion'].'</b></FONT>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL A NIVEL DEL PROGRAMA<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></FONT>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $e[2][1]?>, color: 'red'},{y: <?php echo $e[2][2]?>, color: 'red'},{y: <?php echo $e[2][3]?>, color: 'red'},{y: <?php echo $e[2][4]?>, color: 'red'},{y: <?php echo $e[2][5]?>, color: 'red'},{y: <?php echo $e[2][6]?>, color: 'red'},{y: <?php echo $e[2][7]?>, color: 'red'},{y: <?php echo $e[2][8]?>, color: 'red'},{y: <?php echo $e[2][9]?>, color: 'red'},{y: <?php echo $e[2][10]?>, color: 'red'},{y: <?php echo $e[2][11]?>, color: 'red'},{y: <?php echo $e[2][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $e[3][1]?>, color: 'yellow'},{y: <?php echo $e[3][2]?>, color: 'yellow'},{y: <?php echo $e[3][3]?>, color: 'yellow'},{y: <?php echo $e[3][4]?>, color: 'yellow'},{y: <?php echo $e[3][5]?>, color: 'yellow'},{y: <?php echo $e[3][6]?>, color: 'yellow'},{y: <?php echo $e[3][7]?>, color: 'yellow'},{y: <?php echo $e[3][8]?>, color: 'yellow'},{y: <?php echo $e[3][9]?>, color: 'yellow'},{y: <?php echo $e[3][10]?>, color: 'yellow'},{y: <?php echo $e[3][11]?>, color: 'yellow'},{y: <?php echo $e[3][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $e[4][1]?>, color: 'green'},{y: <?php echo $e[4][2]?>, color: 'green'},{y: <?php echo $e[4][3]?>, color: 'green'},{y: <?php echo $e[4][4]?>, color: 'green'},{y: <?php echo $e[4][5]?>, color: 'green'},{y: <?php echo $e[4][6]?>, color: 'green'},{y: <?php echo $e[4][7]?>, color: 'green'},{y: <?php echo $e[4][8]?>, color: 'green'},{y: <?php echo $e[4][9]?>, color: 'green'},{y: <?php echo $e[4][10]?>, color: 'green'},{y: <?php echo $e[4][11]?>, color: 'green'},{y: <?php echo $e[4][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion',
            defaultSeriesType: 'line'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
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
                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                },
                {
                    name: 'EJECUCIÓN ACUMULADA EN %',
                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                }
            ]
        });
      });
    </script>
    <script type="text/javascript">
    var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Programación',
          data = [{ 
              y: <?php echo $tab[1][1]?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][2];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][3];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][4];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][5];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][6];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][7];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][8];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][9];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][10];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][11];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][12];?>,
              color: '#8f8fde',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_prog_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <script type="text/javascript">
      var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Ejecución',
          data = [{ 
              y: <?php echo $tab[2][1]?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][2];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][3];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][4];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][5];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][6];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][7];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][8];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][9];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][10];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][11];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][12];?>,
              color: '#61d1e4',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_ejec_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <?php

    return $tabla;
    }    
    /*---------------------------------------------------------------------------------*/

    /*------------------- Evaluacion A nivel Procesos -------------------*/
    public function nivel_proceso($aper_programa,$proy_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['proyecto']=$this->model_proyecto->get_id_proyecto($proy_id); //// Datos Proyectos

      $data['proc']=$this->procesos($aper_programa,$proy_id); /// Procesos

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/componentes/eprocesos', $data);
    }

    /*------------------- Imprimir Evaluacion A nivel Programas -------------------*/
    public function print_nivel_proceso($aper_programa,$proy_id){
      $data['proc']=$this->imprimir_procesos($aper_programa,$proy_id);

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/componentes/imprimir_eproceso', $data);
    }

    /*----------------------- Lista de Proyectos ------------------------------*/
    public function procesos($aper_programa,$proy_id){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id); //// Datos Proyectos
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos Fase Activa
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);

      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }
      $nro=0;
      $tabla ='';
      if(count($componente)!=0){
        $tabla .='
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width:1%;"><center>Nro</center></th>
                    <th style="width:15%;"><center>PROCESO / COMPONENTE</center></th>
                    <th style="width:10%;"><center>UNIDAD RESPONSABLE</center></th>
                    <th style="width:10%;"><center>RESPONSABLE</center></th>
                    <th style="width:6%;"><center>PONDERACI&Oacute;N</center></th>
                    <th style="width:3%;"></th>
                    <th style="width:65%;"><center>TEMPORALIDAD</center></th>
                  </tr>
                </thead>
                <tbody>';
                  foreach($componente as $rowc){
                  $nro++;
                  $productos = $this->model_producto->list_prod($rowc['com_id']);
                    if(count($productos)!=0){
                    $tab=$this->productos($rowc['com_id'],$proyectos[0]['proy_act']);

                    for ($i=1; $i <=12 ; $i++) { 
                      $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                      if($tab[1][$i]!=0){
                        $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                        if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                        if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                        if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                      }
                    }
                    $tabla .='<tr>';
                      $tabla .='<td>'.$nro.'<br>';
                      $tabla .= ' <center>
                                    <a href="'.site_url("").'/rep/get_nproceso/'.$aper_programa.'/'.$rowc['com_id'].'" title="REPORTE INSTITUCIONAL A NIVEL DEL PROCESO : '.$rowc['com_componente'].'" id="myBtn1'.$rowc['com_id'].'"><img src="' . base_url() . 'assets/ifinal/rep_graf.png" WIDTH="40" HEIGHT="40"/></a><br>
                                    <img id="load1'.$rowc['com_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                                  </center><br><center>
                                    <a href="'.site_url("").'/rep/eval_nproducto/'.$aper_programa.'/'.$rowc['com_id'].'" title="EVALUACI&Oacute;N A NIVEL DE PRODUCTOS" id="myBtn2'.$rowc['com_id'].'"><img src="' . base_url() . 'assets/ifinal/carp_est.jpg" WIDTH="40" HEIGHT="40"/></a><br>PROD.
                                    <img id="load2'.$rowc['com_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                                  </center>';
                      $tabla .='</td>';
                      $tabla .='<td>'.$rowc['com_componente'].'</td>';
                      $tabla .='<td>'.$rowc['uni_unidad'].'</td>';
                      $tabla .='<td>'.$rowc['fun_nombre'].' '.$rowc['fun_paterno'].' '.$rowc['fun_materno'].'</td>';
                      $tabla .='<td>'.$rowc['com_ponderacion'].'%</td>';
                      $tabla .='<td align=center><a data-toggle="modal" data-target="#'.$rowc['com_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" ><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="40" HEIGHT="40"/></a>
                                  <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowc['com_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                          </button>
                                          <h4 class="modal-title">
                                            '.$rowc['com_componente'].'
                                          </h4>
                                        </div>
                                        <div class="modal-body no-padding">
                                          <div class="well">
                                            <div id="graf_eficacia'.$rowc['com_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                            <hr>
                                            <div id="container'.$rowc['com_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </td>';
                      $tabla .='<td>';
                         $tabla .='<table class="table table table-bordered">
                            <thead>
                                <tr align=center>
                                  <th style="width:7%;"></th>
                                  <th style="width:8%;"><font color=#000>ENE.</font></th>
                                  <th style="width:8%;"><font color=#000>FEB.</font></th>
                                  <th style="width:8%;"><font color=#000>MAR.</font></th>
                                  <th style="width:8%;"><font color=#000>ABR.</font></th>
                                  <th style="width:8%;"><font color=#000>MAY.</font></th>
                                  <th style="width:8%;"><font color=#000>JUN.</font></th>
                                  <th style="width:8%;"><font color=#000>JUL.</font></th>
                                  <th style="width:8%;"><font color=#000>AGO.</font></th>
                                  <th style="width:8%;"><font color=#000>SEPT.</font></th>
                                  <th style="width:8%;"><font color=#000>OCT.</font></th>
                                  <th style="width:8%;"><font color=#000>NOV.</font></th>
                                  <th style="width:8%;"><font color=#000>DIC.</font></th>
                                </tr>
                            </thead>
                              <tbody>';
                                $tabla .='<tr>';
                                  $tabla .='<td>%PA</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$tab[1][$i].'%</td>';
                                    }
                                  }
                                $tabla .='</tr>';
                                $tabla .='<tr>';
                                  $tabla .='<td>%EA</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$tab[2][$i].'%</td>';
                                    } 
                                  }
                                $tabla .='</tr>';
                                $tabla .='<tr>';
                                  $tabla .='<td>%EFI</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$p[1][$i].'%</td>';
                                    }
                                  }
                                $tabla .='</tr>';
                              $tabla .='
                              </tbody>
                            </table>';
                      $tabla .='</td>';
                      $tabla.='<script>
                        document.getElementById("myBtn1'.$rowc['com_id'].'").addEventListener("click", function(){
                        document.getElementById("load1'.$rowc['com_id'].'").style.display = "block";
                      });
                      document.getElementById("myBtn2'.$rowc['com_id'].'").addEventListener("click", function(){
                        document.getElementById("load2'.$rowc['com_id'].'").style.display = "block";
                      });
                    </script>';
                    ?>
                    <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                    <script type="text/javascript">
                    var chart;
                    $(document).ready(function() {
                      chart = new Highcharts.chart('graf_eficacia'+<?php echo $rowc['com_id']; ?>, {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PROCESOS '
                            },
                            xAxis: {
                                categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
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
                                pointFormat: '{series.name}: <br/> TOTAL:   {point.y}'
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

                                name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                                data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

                            }, {
                                name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                                data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
                            }, {
                                name: '<b style="color: green;">MAYOR A 91%</b>',
                                data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][11]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
                            }]

                        });
                    });
                    </script>
                    <script type="text/javascript">
                    var chart1;
                    $(document).ready(function() {
                      chart1 = new Highcharts.Chart({
                        chart: {
                          renderTo: 'container'+<?php echo $rowc['com_id']; ?>,
                          defaultSeriesType: 'line'
                        },
                        title: {
                          text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE PROCESOS'
                        },
                        subtitle: {
                          text: ''
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
                                  data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                              },
                              {
                                  name: 'EJECUCIÓN ACUMULADA EN %',
                                  data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                              }
                          ]
                      });
                    });
                  </script>
                  <?php
                    $tabla .='</tr>';
                  }
                }
      $tabla .='</tbody>
              </table>
            </div>';
      }
      
      return $tabla;
    }

    /*----------------------------- Imprimir Procesos ----------------------------------*/
    public function imprimir_procesos($aper_programa,$proy_id){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
        $programa=$this->model_evalnacional->programa($aper_programa); //// Programa
        $proyecto=$this->model_proyecto->get_id_proyecto($proy_id); //// Datos Proyectos
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos Fase Activa
        $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']); /// Datos Componente
        if(count($componente)!=0){
           $tabla .='
            <div class="table-responsive" align=center>
                  <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                      <thead>
                          <tr>
                            <th colspan=7>
                              <table width="100%">
                                <tr>
                                    <td width=20%; text-align:center;"">
                                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                    </td>
                                    <td width=60%; class="titulo_pdf">
                                        <FONT FACE="courier new" size="1">
                                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PROCESOS<br> 
                                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br> 
                                        </FONT>
                                    </td>
                                    <td width=20%; text-align:center;"">
                                    </td>
                                </tr>
                            </table>
                            </th>
                          </tr>
                          <tr class="even_row" bgcolor="#1c7368" align=center>
                            <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                            <th style="width:10%;"><font color="#ffffff">PROCESO / COMPONENTE</font></th>
                            <th style="width:10%;"><font color="#ffffff">UNIDAD RESPONSABLE</font></th>
                            <th style="width:10%;"><font color="#ffffff">RESPONSABLE</font></th>
                            <th style="width:6%;"><font color="#ffffff">%</font></th>
                            <th style="width:20%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                            <th style="width:50%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                          </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($componente as $rowc){
                        $nro++;
                        $productos = $this->model_producto->list_prod($rowc['com_id']);
                          if(count($productos)!=0){
                          $tab=$this->productos($rowc['com_id'],$proyecto[0]['proy_act']);

                          for ($i=1; $i <=12 ; $i++) { 
                            $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                            if($tab[1][$i]!=0){
                              $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                              if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                              if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                              if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                            }
                          }
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td>'.$rowc['com_componente'].'</td>';
                            $tabla .='<td>'.$rowc['uni_unidad'].'</td>';
                            $tabla .='<td>'.$rowc['fun_nombre'].' '.$rowc['fun_paterno'].' '.$rowc['fun_materno'].'</td>';
                            $tabla .='<td>'.$rowc['com_ponderacion'].'%</td>';
                            $tabla .='<td><div id="container'.$rowc['com_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
                            $tabla .='<td>';
                            $tabla .='<table class="change_order_items" border=1>
                                      <thead>
                                          <tr bgcolor="#1c7368" align=center>
                                            <th style="width:7%;"></th>
                                            <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                                          </tr>
                                      </thead>
                                        <tbody>';
                                          $tabla .='<tr>';
                                            $tabla .='<td>%PA</td>';
                                            for ($i=1; $i <=12 ; $i++) {
                                              if($i>=$vi & $i<=$vf){
                                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                              }
                                              else{
                                                $tabla .='<td>'.$tab[1][$i].'%</td>';
                                              }
                                            }
                                          $tabla .='</tr>';
                                          $tabla .='<tr>';
                                            $tabla .='<td>%EA</td>';
                                            for ($i=1; $i <=12 ; $i++) {
                                              if($i>=$vi & $i<=$vf){
                                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                              }
                                              else{
                                                $tabla .='<td>'.$tab[2][$i].'%</td>';
                                              } 
                                            }
                                          $tabla .='</tr>';
                                          $tabla .='<tr>';
                                            $tabla .='<td>%EFI</td>';
                                            for ($i=1; $i <=12 ; $i++) {
                                              if($i>=$vi & $i<=$vf){
                                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                              }
                                              else{
                                                $tabla .='<td>'.$p[1][$i].'%</td>';
                                              }
                                            }
                                          $tabla .='</tr>';
                                        $tabla .='
                                        </tbody>
                                      </table>';
                            $tabla .='</td>';
                          ?>
                          <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                          <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                         
                          <script type="text/javascript">
                          var chart1;
                          $(document).ready(function() {
                            chart1 = new Highcharts.Chart({
                              chart: {
                                renderTo: 'container'+<?php echo $rowc['com_id']; ?>,
                                defaultSeriesType: 'line'
                              },
                              title: {
                                text: ''
                              },
                              subtitle: {
                                text: ''
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
                                            data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                        },
                                        {
                                            name: 'EJECUCIÓN ACUMULADA EN %',
                                            data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                        }
                                    ]
                            });
                          });
                        </script>
                        <?php
                          $tabla .='</tr>';
                          }
                        }
            $tabla .='</tbody>
                    </table>
                  </div>';
            }

      return $tabla;
    }

    /*--------------------------------- Get Proceso ---------------------------------*/
    public function get_proceso($aper_programa,$com_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['componente']=$this->model_evalnacional->vcomponente($com_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']); 
      
      $tab=$this->productos($com_id,$data['proyecto'][0]['proy_act']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          if($tab[1][$i]!=0){
            $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
            if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
            if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
          }
        }

        $data['p']=$tab; /// Programado,Ejecutado
        $data['e']=$p; /// Eficacia

      $data['print_proc']=$this->get_print_proceso($aper_programa,$com_id);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/componentes/get_proceso', $data);
    }

    public function get_print_proceso($aper_programa,$com_id){
      $programa=$this->model_evalnacional->programa($aper_programa);
      $componente=$this->model_evalnacional->vcomponente($com_id);
      $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);

        $tab=$this->productos($com_id,$proyecto[0]['proy_act']);
        for ($i=1; $i <=12 ; $i++) { 
          $e[1][$i]=0;$e[2][$i]=0;$e[3][$i]=0;$e[4][$i]=0;
          if($tab[1][$i]!=0){
            $e[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($e[1][$i]<=75){$e[2][$i] = $e[1][$i];}else{$e[2][$i] = 0;}
            if($e[1][$i] >= 76 && $e[1][$i] <= 90.9) {$e[3][$i] = $e[1][$i];}else{$e[3][$i] = 0;}
            if($e[1][$i] >= 91){$e[4][$i] = $e[1][$i];}else{$e[4][$i] = 0;}
          }
        }

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="90%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PROCESO<br> 
                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                        <b> PROCESO : </b>'.$componente[0]['com_componente'].'
                        </FONT>
                    </td>
                  </tr>
                </table>

                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DEL PROCESO<b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$componente[0]['com_componente'].'</b></FONT>
                      <div id="graf_eficacia_print" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$e[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT><br>
                      <FONT FACE="courier new" size="3"><b>'.$componente[0]['com_componente'].'</b></FONT>
                      <div id="regresion" style="width: 700px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div>
                <div class="verde"></div>
                <div class="blanco"></div> 
                <table width="90%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE PROCESOS<br> 
                            <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                            <b> PROCESO : </b>'.$componente[0]['com_componente'].'
                        </FONT>
                    </td>
                  </tr>
                </table>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DEL PROCESO<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$componente[0]['com_componente'].'</b></FONT>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DEL PROCESO<b/></FONT><br>
                      <FONT FACE="courier new" size="2"><b>'.$componente[0]['com_componente'].'</b></FONT>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $e[2][1]?>, color: 'red'},{y: <?php echo $e[2][2]?>, color: 'red'},{y: <?php echo $e[2][3]?>, color: 'red'},{y: <?php echo $e[2][4]?>, color: 'red'},{y: <?php echo $e[2][5]?>, color: 'red'},{y: <?php echo $e[2][6]?>, color: 'red'},{y: <?php echo $e[2][7]?>, color: 'red'},{y: <?php echo $e[2][8]?>, color: 'red'},{y: <?php echo $e[2][9]?>, color: 'red'},{y: <?php echo $e[2][10]?>, color: 'red'},{y: <?php echo $e[2][11]?>, color: 'red'},{y: <?php echo $e[2][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $e[3][1]?>, color: 'yellow'},{y: <?php echo $e[3][2]?>, color: 'yellow'},{y: <?php echo $e[3][3]?>, color: 'yellow'},{y: <?php echo $e[3][4]?>, color: 'yellow'},{y: <?php echo $e[3][5]?>, color: 'yellow'},{y: <?php echo $e[3][6]?>, color: 'yellow'},{y: <?php echo $e[3][7]?>, color: 'yellow'},{y: <?php echo $e[3][8]?>, color: 'yellow'},{y: <?php echo $e[3][9]?>, color: 'yellow'},{y: <?php echo $e[3][10]?>, color: 'yellow'},{y: <?php echo $e[3][11]?>, color: 'yellow'},{y: <?php echo $e[3][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $e[4][1]?>, color: 'green'},{y: <?php echo $e[4][2]?>, color: 'green'},{y: <?php echo $e[4][3]?>, color: 'green'},{y: <?php echo $e[4][4]?>, color: 'green'},{y: <?php echo $e[4][5]?>, color: 'green'},{y: <?php echo $e[4][6]?>, color: 'green'},{y: <?php echo $e[4][7]?>, color: 'green'},{y: <?php echo $e[4][8]?>, color: 'green'},{y: <?php echo $e[4][9]?>, color: 'green'},{y: <?php echo $e[4][10]?>, color: 'green'},{y: <?php echo $e[4][11]?>, color: 'green'},{y: <?php echo $e[4][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion',
            defaultSeriesType: 'line'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
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
                    data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                },
                {
                    name: 'EJECUCIÓN ACUMULADA EN %',
                    data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                }
            ]
        });
      });
    </script>
    <script type="text/javascript">
    var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Programación',
          data = [{ 
              y: <?php echo $tab[1][1]?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][2];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][3];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][4];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][5];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][6];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][7];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][8];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][9];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][10];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][11];?>,
              color: '#8f8fde',
            }, {
              y: <?php echo $tab[1][12];?>,
              color: '#8f8fde',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_prog_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <script type="text/javascript">
      var chart;
      $(document).ready(function() {
        var colors = Highcharts.getOptions().colors,
          categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
          name = 'Nivel de Ejecución',
          data = [{ 
              y: <?php echo $tab[2][1]?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][2];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][3];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][4];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][5];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][6];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][7];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][8];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][9];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][10];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][11];?>,
              color: '#61d1e4',
            }, {
              y: <?php echo $tab[2][12];?>,
              color: '#61d1e4',
            }];
        
        function setChart(name, categories, data, color) {
          chart.xAxis[0].setCategories(categories);
          chart.series[0].remove();
          chart.addSeries({
            name: name,
            data: data,
            color: color || 'white'
          });
        }
        
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'container_ejec_print', 
            type: 'column'
          },
          title: {
            text: ''
          },
          subtitle: {
            text: ''
          },
          xAxis: {
            categories: categories              
          },
          yAxis: {
            title: {
              text: ''
            }
          },
          plotOptions: {
            column: {
              cursor: 'pointer',
              point: {
                events: {
                  click: function() {
                    var drilldown = this.drilldown;
                    if (drilldown) { // drill down
                      setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                    } else { // restore
                      setChart(name, categories, data);
                    }
                  }
                }
              },
              dataLabels: {
                enabled: true,
                color: colors[1],
                style: {
                  fontWeight: 'bold'
                },
                formatter: function() {
                  return this.y +'%';
                }
              }         
            }
          },
          tooltip: {
            formatter: function() {
              var point = this.point,
                s = this.x +':<b>'+ this.y +'% </b><br/>';
              if (point.drilldown) {
                s += ''+ point.category +' ';
              } else {
                s += '';
              }
              return s;
            }
          },
          series:   [{
            name: name,
            data: data,
            color: 'white'
          }],
          exporting: {
            enabled: false
          }
        });
      });
    </script>
    <?php

    return $tabla;
    }    
    /*---------------------------------------------------------------------------------*/
    /*------------------- Evaluacion A nivel Productos -------------------*/
    public function nivel_producto($aper_programa,$com_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['componente']=$this->model_evalnacional->vcomponente($com_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']);


      $data['prod']=$this->list_productos($aper_programa,$com_id); /// Productos

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/productos/eproductos', $data);
    }

    /*------------------- Imprimir Evaluacion A nivel Programas -------------------*/
    public function print_nivel_producto($aper_programa,$com_id){
      $data['prod']=$this->imprimir_productos($aper_programa,$com_id);

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/productos/imprimir_eproductos', $data);
    }

    /*----------------------- Lista de Proyectos ------------------------------*/
    public function list_productos($aper_programa,$com_id){
      $componente=$this->model_evalnacional->vcomponente($com_id);
      $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);
      $productos = $this->model_producto->list_prod($com_id);
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }
      $nro=0;
      $tabla ='';

      if(count($productos)!=0){
          foreach($productos  as $rowp){
            if($proyecto[0]['proy_act']==1){
              $tab=$this->actividades($rowp['prod_id']);
               for ($i=1; $i <=12 ; $i++) { 
                  $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                  if($tab[1][$i]!=0){
                    $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                    if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                    if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                    if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                  }
                }
            }
            else{
              $tab=$this->temporalidad_productos_programado($rowp['prod_id']);
              /*-------------------------------------------------------*/
              for ($i=1; $i <=12 ; $i++) { 
                $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                
                if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
                if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
                if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
              }
              /*-------------------------------------------------------*/
            }
            $nro++;
            $tabla .='<tr bgcolor=#e1f9e1>';
            $tabla .='<td>'.$nro.'<br>';
            $tabla .= ' <center>
                          <a href="'.site_url("").'/rep/get_nproducto/'.$aper_programa.'/'.$rowp['prod_id'].'" title="REPORTE INSTITUCIONAL NACIONAL A NIVEL DEL PRODUCTO : '.$rowp['prod_producto'].'" id="myBtn1'.$rowp['prod_id'].'"><img src="' . base_url() . 'assets/ifinal/rep_graf.png" WIDTH="40" HEIGHT="40"/></a><br>
                          <img id="load1'.$rowp['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                        </center><br>';
                      if($proyecto[0]['proy_act']==1){
                        $tabla .='
                        <center>
                          <a href="'.site_url("").'/rep/eval_nactividad/'.$aper_programa.'/'.$rowp['prod_id'].'" title="EVALUACI&Oacute;N A NIVEL DE ACTIVIDADES" id="myBtn2'.$rowp['prod_id'].'"><img src="' . base_url() . 'assets/ifinal/carp_est.jpg" WIDTH="40" HEIGHT="40"/></a><br>ACT.
                          <img id="load2'.$rowp['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                        </center>';
                      }
            $tabla .='</td>';
            $tabla .='<td>'.$rowp['prod_producto'].'</td>';
            $tabla .='<td>'.$rowp['indi_descripcion'].'</td>';
            $tabla .='<td>'.$rowp['prod_indicador'].'</td>';
            $tabla .='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
            $tabla .='<td>'.$rowp['prod_ponderacion'].'</td>';
            $tabla .='<td align=center><a data-toggle="modal" data-target="#'.$rowp['prod_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" ><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="40" HEIGHT="40"/></a>
                        <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowp['prod_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                  &times;
                                </button>
                                <h4 class="modal-title">
                                  '.$rowp['prod_producto'].'
                                </h4>
                              </div>
                              <div class="modal-body no-padding">
                                <div class="well">
                                  <div id="graf_eficacia'.$rowp['prod_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                  <hr>
                                  <div id="container'.$rowp['prod_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>';
            $tabla .='<td>';
                 $tabla .='<table class="table table table-bordered">
                    <thead>
                        <tr align=center>
                          <th style="width:7%;"></th>
                          <th style="width:8%;"><font color=#000>ENE.</font></th>
                          <th style="width:8%;"><font color=#000>FEB.</font></th>
                          <th style="width:8%;"><font color=#000>MAR.</font></th>
                          <th style="width:8%;"><font color=#000>ABR.</font></th>
                          <th style="width:8%;"><font color=#000>MAY.</font></th>
                          <th style="width:8%;"><font color=#000>JUN.</font></th>
                          <th style="width:8%;"><font color=#000>JUL.</font></th>
                          <th style="width:8%;"><font color=#000>AGO.</font></th>
                          <th style="width:8%;"><font color=#000>SEPT.</font></th>
                          <th style="width:8%;"><font color=#000>OCT.</font></th>
                          <th style="width:8%;"><font color=#000>NOV.</font></th>
                          <th style="width:8%;"><font color=#000>DIC.</font></th>
                        </tr>
                    </thead>
                      <tbody>';
                        $actividad = $this->model_actividad->list_act_anual($rowp['prod_id']);

                        if($proyecto[0]['proy_act']==1){
                          /*---------------------------- Tiene Actividades --------------------------------*/
                          if(count($actividad)!=0){
                            $tabla .='<tr>';
                              $tabla .='<td>%PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EFI</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$p[1][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                          }
                          /*---------------------------- No Tiene Actividades --------------------------------*/
                          else{
                            $tab=$this->temporalidad_productos_programado($rowp['prod_id']);
                            $tabla .='<tr>';
                              $tabla .='<td>P</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[1][$i].'</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%PA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[3][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[3][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>E</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[4][$i].'</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[4][$i].'</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[5][$i].'</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[5][$i].'</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EA</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[6][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[6][$i].'%</td>';
                                } 
                              }
                            $tabla .='</tr>';
                            $tabla .='<tr>';
                              $tabla .='<td>%EFI</td>';
                              for ($i=1; $i <=12 ; $i++) {
                                if($i>=$vi & $i<=$vf){
                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[7][$i].'%</td>';
                                }
                                else{
                                  $tabla .='<td>'.$tab[7][$i].'%</td>';
                                }
                              }
                            $tabla .='</tr>';
                          }
                          
                        }
                        else{
                          $tabla .='<tr>';
                            $tabla .='<td>P</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[1][$i].'</td>';
                              }
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>PA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[2][$i].'%</td>';
                              }
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>%PA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[3][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[3][$i].'%</td>';
                              }
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>E</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[4][$i].'</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[4][$i].'</td>';
                              } 
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>EA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[5][$i].'</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[5][$i].'</td>';
                              } 
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>%EA</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[6][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[6][$i].'%</td>';
                              } 
                            }
                          $tabla .='</tr>';
                          $tabla .='<tr>';
                            $tabla .='<td>%EFI</td>';
                            for ($i=1; $i <=12 ; $i++) {
                              if($i>=$vi & $i<=$vf){
                                $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[7][$i].'%</td>';
                              }
                              else{
                                $tabla .='<td>'.$tab[7][$i].'%</td>';
                              }
                            }
                          $tabla .='</tr>';
                          }
                      $tabla .='
                      </tbody>
                    </table>';
              $tabla .='</td>';
              $tabla.='<script>
                        document.getElementById("myBtn1'.$rowp['prod_id'].'").addEventListener("click", function(){
                        document.getElementById("load1'.$rowp['prod_id'].'").style.display = "block";
                      });
                      document.getElementById("myBtn2'.$rowp['prod_id'].'").addEventListener("click", function(){
                        document.getElementById("load2'.$rowp['prod_id'].'").style.display = "block";
                      });
                    </script>';
                    ?>
                    <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                    <script type="text/javascript">
                    var chart;
                    $(document).ready(function() {
                      chart = new Highcharts.chart('graf_eficacia'+<?php echo $rowp['prod_id']; ?>, {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO '
                            },
                            xAxis: {
                                categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
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
                                pointFormat: '{series.name}: <br/> TOTAL:   {point.y}'
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

                                name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                                data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

                            }, {
                                name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                                data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
                            }, {
                                name: '<b style="color: green;">MAYOR A 91%</b>',
                                data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][11]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
                            }]

                        });
                    });
                    </script>
                    <?php 
                      if($proyecto[0]['proy_act']==1){
                        ?>
                        <script type="text/javascript">
                          var chart1;
                          $(document).ready(function() {
                            chart1 = new Highcharts.Chart({
                              chart: {
                                renderTo: 'container'+<?php echo $rowp['prod_id']; ?>,
                                defaultSeriesType: 'line'
                              },
                              title: {
                                text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE PRODUCTO'
                              },
                              subtitle: {
                                text: ''
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
                                      data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                    },
                                    {
                                      name: 'EJECUCIÓN ACUMULADA EN %',
                                      data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                    }
                                ]
                            });
                          });
                        </script>
                        <?php
                      }
                      else{
                        ?>
                        <script type="text/javascript">
                        var chart1;
                        $(document).ready(function() {
                          chart1 = new Highcharts.Chart({
                            chart: {
                              renderTo: 'container'+<?php echo $rowp['prod_id']; ?>,
                              defaultSeriesType: 'line'
                            },
                            title: {
                              text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE PRODUCTO'
                            },
                            subtitle: {
                              text: ''
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
                                    data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                                  },
                                  {
                                    name: 'EJECUCIÓN ACUMULADA EN %',
                                    data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                                  }
                              ]
                          });
                        });
                      </script>
                        <?php
                      }                 
            $tabla .='</tr>';
        }
      }
      return $tabla;
    }

     /*----------------------------- Imprimir Productos ----------------------------------*/
    public function imprimir_productos($aper_programa,$com_id){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
        $programa=$this->model_evalnacional->programa($aper_programa);
        $componente=$this->model_evalnacional->vcomponente($com_id);
        $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);
        $productos = $this->model_producto->list_prod($com_id);

        if(count($productos)!=0){
           $tabla .='
            <div class="table-responsive" align=center>
                  <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                      <thead>
                          <tr>
                            <th colspan=7>
                              <table width="100%">
                                <tr>
                                    <td width=20%; text-align:center;"">
                                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                    </td>
                                    <td width=60%; class="titulo_pdf">
                                        <FONT FACE="courier new" size="1">
                                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTOS<br> 
                                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br> 
                                        <b>PROCESO : </b>'.$componente[0]['com_componente'].'
                                        </FONT>
                                    </td>
                                    <td width=20%; text-align:center;"">
                                    </td>
                                </tr>
                            </table>
                            </th>
                          </tr>
                          <tr class="even_row" bgcolor="#1c7368" align=center>
                            <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                            <th style="width:10%;"><font color="#ffffff">PRODUCTO</font></th>
                            <th style="width:3%;"><font color="#ffffff">TIP. IND.</font></th>
                            <th style="width:5%;"><font color="#ffffff">INDICADOR</font></th>
                            <th style="width:3%;"><font color="#ffffff">%</font></th>
                            <th style="width:10%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                            <th style="width:60%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                          </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                      foreach($productos  as $rowp){
                        if($proyecto[0]['proy_act']==1){
                          $actividad = $this->model_actividad->list_act_anual($rowp['prod_id']);
                          if (count($actividad)!=0) {
                           $tab=$this->actividades($rowp['prod_id']);
                           for ($i=1; $i <=12 ; $i++) { 
                              $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                              if($tab[1][$i]!=0){
                                $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

                                if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
                                if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
                                if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
                              }
                            }
                          }
                        }
                        else{
                          $tab=$this->temporalidad_productos_programado($rowp['prod_id']);
                          /*-------------------------------------------------------*/
                          for ($i=1; $i <=12 ; $i++) { 
                            $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                            
                            if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
                            if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
                            if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
                          }
                          /*-------------------------------------------------------*/
                        }
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td>'.$rowp['prod_producto'].'</td>';
                            $tabla .='<td>'.$rowp['indi_descripcion'].'</td>';
                            $tabla .='<td>'.$rowp['prod_indicador'].'</td>';
                            $tabla .='<td>'.$rowp['prod_ponderacion'].'</td>';
                            $tabla .='<td><div id="container'.$rowp['prod_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
                            $tabla .='<td>';
                            $tabla .='<table class="change_order_items" border=1>
                                      <thead>
                                          <tr bgcolor="#1c7368" align=center>
                                            <th style="width:7%;"></th>
                                            <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                                          </tr>
                                      </thead>
                                        <tbody>';
                                          if($proyecto[0]['proy_act']==1){
                                              $tabla .='<tr>';
                                                $tabla .='<td>%PA</td>';
                                                for ($i=1; $i <=12 ; $i++) {
                                                  if($i>=$vi & $i<=$vf){
                                                    $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'%</td>';
                                                  }
                                                  else{
                                                    $tabla .='<td>'.$tab[1][$i].'%</td>';
                                                  }
                                                }
                                              $tabla .='</tr>';
                                              $tabla .='<tr>';
                                                $tabla .='<td>%EA</td>';
                                                for ($i=1; $i <=12 ; $i++) {
                                                  if($i>=$vi & $i<=$vf){
                                                    $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                                  }
                                                  else{
                                                    $tabla .='<td>'.$tab[2][$i].'%</td>';
                                                  } 
                                                }
                                              $tabla .='</tr>';
                                              $tabla .='<tr>';
                                                $tabla .='<td>%EFI</td>';
                                                for ($i=1; $i <=12 ; $i++) {
                                                  if($i>=$vi & $i<=$vf){
                                                    $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$p[1][$i].'%</td>';
                                                  }
                                                  else{
                                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                                  }
                                                }
                                              $tabla .='</tr>';
                                            }
                                            else{
                                              $tabla .='<tr>';
                                              $tabla .='<td>P</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[1][$i].'</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>PA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%PA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[3][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[3][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>E</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[4][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[4][$i].'</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>EA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[5][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[5][$i].'</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%EA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[6][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[6][$i].'%</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%EFI</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[7][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[7][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            }
                                        $tabla .='
                                        </tbody>
                                      </table>';
                            $tabla .='</td>';
                            ?>
                            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                            <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                            <?php 
                              if($proyecto[0]['proy_act']==1){
                                ?>
                                <script type="text/javascript">
                                  var chart1;
                                  $(document).ready(function() {
                                    chart1 = new Highcharts.Chart({
                                      chart: {
                                        renderTo: 'container'+<?php echo $rowp['prod_id']; ?>,
                                        defaultSeriesType: 'line'
                                      },
                                      title: {
                                        text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE PRODUCTO'
                                      },
                                      subtitle: {
                                        text: ''
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
                                              data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                                            },
                                            {
                                              name: 'EJECUCIÓN ACUMULADA EN %',
                                              data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                                            }
                                        ]
                                    });
                                  });
                                </script>
                                <?php
                              }
                              else{
                                ?>
                                <script type="text/javascript">
                                var chart1;
                                $(document).ready(function() {
                                  chart1 = new Highcharts.Chart({
                                    chart: {
                                      renderTo: 'container'+<?php echo $rowp['prod_id']; ?>,
                                      defaultSeriesType: 'line'
                                    },
                                    title: {
                                      text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE PRODUCTO'
                                    },
                                    subtitle: {
                                      text: ''
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
                                            data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                                          },
                                          {
                                            name: 'EJECUCIÓN ACUMULADA EN %',
                                            data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                                          }
                                      ]
                                  });
                                });
                              </script>
                          <?php
                        }  
                          $tabla .='</tr>';

                      }
            $tabla .='</tbody>
                    </table>
                  </div>';
            }
      return $tabla;
    }

    /*--------------------------------- Get Producto ---------------------------------*/
    public function get_producto($aper_programa,$prod_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['producto']=$this->model_evalnacional->vproducto($prod_id);
      $data['componente']=$this->model_evalnacional->vcomponente($data['producto'][0]['com_id']);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']); 
      
      if($data['proyecto'][0]['proy_act']==1){
        $tab=$this->actividades($prod_id);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          if($tab[1][$i]!=0){
            $p[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($p[1][$i]<=75){$p[2][$i] = $p[1][$i];}else{$p[2][$i] = 0;}
            if($p[1][$i] >= 76 && $p[1][$i] <= 90.9) {$p[3][$i] = $p[1][$i];}else{$p[3][$i] = 0;}
            if($p[1][$i] >= 91){$p[4][$i] = $p[1][$i];}else{$p[4][$i] = 0;}
          }
        }
      }
      else{
        $tab=$this->temporalidad_productos_programado($prod_id);
        /*-------------------------------------------------------*/
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          
          if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
          if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
          if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
        }
        /*-------------------------------------------------------*/
      }
      
        $data['p']=$tab; /// Programado,Ejecutado
        $data['e']=$p; /// Eficacia

      $data['print_prod']=$this->get_print_producto($aper_programa,$prod_id);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/productos/get_producto', $data);
    }

    public function get_print_producto($aper_programa,$prod_id){
      $programa=$this->model_evalnacional->programa($aper_programa);
      $producto=$this->model_evalnacional->vproducto($prod_id);
      $componente=$this->model_evalnacional->vcomponente($producto[0]['com_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); 

      if($proyecto[0]['proy_act']==1){
        $tab=$this->actividades($prod_id);
        for ($i=1; $i <=12 ; $i++) { 
          $e[1][$i]=0;$e[2][$i]=0;$e[3][$i]=0;$e[4][$i]=0;
          if($tab[1][$i]!=0){
            $e[1][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($e[1][$i]<=75){$e[2][$i] = $e[1][$i];}else{$e[2][$i] = 0;}
            if($e[1][$i] >= 76 && $e[1][$i] <= 90.9) {$e[3][$i] = $e[1][$i];}else{$e[3][$i] = 0;}
            if($e[1][$i] >= 91){$e[4][$i] = $e[1][$i];}else{$e[4][$i] = 0;}
          }
        }
      }
      else{
        $tab=$this->temporalidad_productos_programado($prod_id);
        /*-------------------------------------------------------*/
        for ($i=1; $i <=12 ; $i++) { 
          $e[1][$i]=0;$e[2][$i]=0;$e[3][$i]=0;$e[4][$i]=0;
          
          if($tab[7][$i]<=75){$e[2][$i] = $tab[7][$i];}else{$e[2][$i] = 0;}
          if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$e[3][$i] = $tab[7][$i];}else{$e[3][$i] = 0;}
          if($tab[7][$i]>=91){$e[4][$i] = $tab[7][$i];}else{$e[4][$i] = 0;}
        }
        /*-------------------------------------------------------*/
      }

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="100%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO<br> 
                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                        <b> PROCESO : </b>'.$componente[0]['com_componente'].'<br>
                        <b> PRODUCTO : </b>'.$producto[0]['prod_producto'].'
                        </FONT>
                    </td>
                  </tr>
                </table>

                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO<b/></FONT><br>
                      <div id="graf_eficacia_print" style="width: 700px; height: 270px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EFICACIA</td>';
                                if($proyecto[0]['proy_act']==1){
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$e[1][$i].'%</td>';
                                  }
                                }
                                else{
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[7][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT><br>
                      <div id="regresion" style="width: 700px; height: 270px; margin: 1 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                if($proyecto[0]['proy_act']==1){
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[1][$i].'%</td>';
                                  }
                                }
                                else{
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[3][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                if($proyecto[0]['proy_act']==1){
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[2][$i].'%</td>';
                                  }
                                }
                                else{
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[6][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div> 
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTOS<br> 
                            <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                            <b> PROCESO : </b>'.$componente[0]['com_componente'].'<br>
                            <b> PRODUCTO : </b>'.$producto[0]['prod_producto'].'
                        </FONT>
                    </td>
                  </tr>
                </table>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO<b/></FONT><br>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                if($proyecto[0]['proy_act']==1){
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[1][$i].'%</td>';
                                  }
                                }
                                else{
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[3][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO<b/></FONT><br>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>EJECUCI&Oacute;N ACUMULADA</td>';
                                if($proyecto[0]['proy_act']==1){
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[1][$i].'%</td>';
                                  }
                                }
                                else{
                                  for ($i=1; $i <=12 ; $i++) {
                                    $tabla .='<td>'.$tab[6][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $e[2][1]?>, color: 'red'},{y: <?php echo $e[2][2]?>, color: 'red'},{y: <?php echo $e[2][3]?>, color: 'red'},{y: <?php echo $e[2][4]?>, color: 'red'},{y: <?php echo $e[2][5]?>, color: 'red'},{y: <?php echo $e[2][6]?>, color: 'red'},{y: <?php echo $e[2][7]?>, color: 'red'},{y: <?php echo $e[2][8]?>, color: 'red'},{y: <?php echo $e[2][9]?>, color: 'red'},{y: <?php echo $e[2][10]?>, color: 'red'},{y: <?php echo $e[2][11]?>, color: 'red'},{y: <?php echo $e[2][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $e[3][1]?>, color: 'yellow'},{y: <?php echo $e[3][2]?>, color: 'yellow'},{y: <?php echo $e[3][3]?>, color: 'yellow'},{y: <?php echo $e[3][4]?>, color: 'yellow'},{y: <?php echo $e[3][5]?>, color: 'yellow'},{y: <?php echo $e[3][6]?>, color: 'yellow'},{y: <?php echo $e[3][7]?>, color: 'yellow'},{y: <?php echo $e[3][8]?>, color: 'yellow'},{y: <?php echo $e[3][9]?>, color: 'yellow'},{y: <?php echo $e[3][10]?>, color: 'yellow'},{y: <?php echo $e[3][11]?>, color: 'yellow'},{y: <?php echo $e[3][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $e[4][1]?>, color: 'green'},{y: <?php echo $e[4][2]?>, color: 'green'},{y: <?php echo $e[4][3]?>, color: 'green'},{y: <?php echo $e[4][4]?>, color: 'green'},{y: <?php echo $e[4][5]?>, color: 'green'},{y: <?php echo $e[4][6]?>, color: 'green'},{y: <?php echo $e[4][7]?>, color: 'green'},{y: <?php echo $e[4][8]?>, color: 'green'},{y: <?php echo $e[4][9]?>, color: 'green'},{y: <?php echo $e[4][10]?>, color: 'green'},{y: <?php echo $e[4][11]?>, color: 'green'},{y: <?php echo $e[4][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <?php
    if($proyecto[0]['proy_act']==1){
      ?>
      <script type="text/javascript">
        var chart1;
        $(document).ready(function() {
          chart1 = new Highcharts.Chart({
            chart: {
              renderTo: 'regresion',
              defaultSeriesType: 'line'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
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
                      data: [ <?php echo $tab[1][1];?>, <?php echo $tab[1][2];?>, <?php echo $tab[1][3];?>, <?php echo $tab[1][4];?>, <?php echo $tab[1][5];?>, <?php echo $tab[1][6];?>, <?php echo $tab[1][7];?>, <?php echo $tab[1][8];?>, <?php echo $tab[1][9];?>, <?php echo $tab[1][10];?>, <?php echo $tab[1][11];?>, <?php echo $tab[1][12];?>]
                  },
                  {
                      name: 'EJECUCIÓN ACUMULADA EN %',
                      data: [ <?php echo $tab[2][1];?>, <?php echo $tab[2][2];?>, <?php echo $tab[2][3];?>, <?php echo $tab[2][4];?>, <?php echo $tab[2][5];?>, <?php echo $tab[2][6];?>, <?php echo $tab[2][7];?>, <?php echo $tab[2][8];?>, <?php echo $tab[2][9];?>, <?php echo $tab[2][10];?>, <?php echo $tab[2][11];?>, <?php echo $tab[2][12];?>]
                  }
              ]
          });
        });
      </script>
      <script type="text/javascript">
        var chart;
          $(document).ready(function() {
            var colors = Highcharts.getOptions().colors,
              categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
              name = 'Nivel de Programación',
              data = [{ 
                  y: <?php echo $tab[1][1]?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][2];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][3];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][4];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][5];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][6];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][7];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][8];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][9];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][10];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][11];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[1][12];?>,
                  color: '#8f8fde',
                }];
            
            function setChart(name, categories, data, color) {
              chart.xAxis[0].setCategories(categories);
              chart.series[0].remove();
              chart.addSeries({
                name: name,
                data: data,
                color: color || 'white'
              });
            }
            
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_prog_print', 
                type: 'column'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: categories              
              },
              yAxis: {
                title: {
                  text: ''
                }
              },
              plotOptions: {
                column: {
                  cursor: 'pointer',
                  point: {
                    events: {
                      click: function() {
                        var drilldown = this.drilldown;
                        if (drilldown) { // drill down
                          setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                        } else { // restore
                          setChart(name, categories, data);
                        }
                      }
                    }
                  },
                  dataLabels: {
                    enabled: true,
                    color: colors[1],
                    style: {
                      fontWeight: 'bold'
                    },
                    formatter: function() {
                      return this.y +'%';
                    }
                  }         
                }
              },
              tooltip: {
                formatter: function() {
                  var point = this.point,
                    s = this.x +':<b>'+ this.y +'% </b><br/>';
                  if (point.drilldown) {
                    s += ''+ point.category +' ';
                  } else {
                    s += '';
                  }
                  return s;
                }
              },
              series:   [{
                name: name,
                data: data,
                color: 'white'
              }],
              exporting: {
                enabled: false
              }
            });
          });
        </script>
        <script type="text/javascript">
        var chart;
        $(document).ready(function() {
          var colors = Highcharts.getOptions().colors,
            categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
            name = 'Nivel de Ejecución',
            data = [{ 
                y: <?php echo $tab[2][1]?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][2];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][3];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][4];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][5];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][6];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][7];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][8];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][9];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][10];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][11];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[2][12];?>,
                color: '#61d1e4',
              }];
          
          function setChart(name, categories, data, color) {
            chart.xAxis[0].setCategories(categories);
            chart.series[0].remove();
            chart.addSeries({
              name: name,
              data: data,
              color: color || 'white'
            });
          }
          
          chart = new Highcharts.Chart({
            chart: {
              renderTo: 'container_ejec_print', 
              type: 'column'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
            },
            xAxis: {
              categories: categories              
            },
            yAxis: {
              title: {
                text: ''
              }
            },
            plotOptions: {
              column: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function() {
                      var drilldown = this.drilldown;
                      if (drilldown) { // drill down
                        setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                      } else { // restore
                        setChart(name, categories, data);
                      }
                    }
                  }
                },
                dataLabels: {
                  enabled: true,
                  color: colors[1],
                  style: {
                    fontWeight: 'bold'
                  },
                  formatter: function() {
                    return this.y +'%';
                  }
                }         
              }
            },
            tooltip: {
              formatter: function() {
                var point = this.point,
                  s = this.x +':<b>'+ this.y +'% </b><br/>';
                if (point.drilldown) {
                  s += ''+ point.category +' ';
                } else {
                  s += '';
                }
                return s;
              }
            },
            series:   [{
              name: name,
              data: data,
              color: 'white'
            }],
            exporting: {
              enabled: false
            }
          });
        });
      </script>
      <?php
    }
    else{
      ?>
      <script type="text/javascript">
        var chart1;
        $(document).ready(function() {
          chart1 = new Highcharts.Chart({
            chart: {
              renderTo: 'regresion',
              defaultSeriesType: 'line'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
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
                      data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                  },
                  {
                      name: 'EJECUCIÓN ACUMULADA EN %',
                      data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                  }
              ]
          });
        });
      </script>
      <script type="text/javascript">
        var chart;
          $(document).ready(function() {
            var colors = Highcharts.getOptions().colors,
              categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
              name = 'Nivel de Programación',
              data = [{ 
                  y: <?php echo $tab[3][1]?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][2];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][3];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][4];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][5];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][6];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][7];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][8];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][9];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][10];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][11];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][12];?>,
                  color: '#8f8fde',
                }];
            
            function setChart(name, categories, data, color) {
              chart.xAxis[0].setCategories(categories);
              chart.series[0].remove();
              chart.addSeries({
                name: name,
                data: data,
                color: color || 'white'
              });
            }
            
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_prog_print', 
                type: 'column'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: categories              
              },
              yAxis: {
                title: {
                  text: ''
                }
              },
              plotOptions: {
                column: {
                  cursor: 'pointer',
                  point: {
                    events: {
                      click: function() {
                        var drilldown = this.drilldown;
                        if (drilldown) { // drill down
                          setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                        } else { // restore
                          setChart(name, categories, data);
                        }
                      }
                    }
                  },
                  dataLabels: {
                    enabled: true,
                    color: colors[1],
                    style: {
                      fontWeight: 'bold'
                    },
                    formatter: function() {
                      return this.y +'%';
                    }
                  }         
                }
              },
              tooltip: {
                formatter: function() {
                  var point = this.point,
                    s = this.x +':<b>'+ this.y +'% </b><br/>';
                  if (point.drilldown) {
                    s += ''+ point.category +' ';
                  } else {
                    s += '';
                  }
                  return s;
                }
              },
              series:   [{
                name: name,
                data: data,
                color: 'white'
              }],
              exporting: {
                enabled: false
              }
            });
          });
        </script>
        <script type="text/javascript">
        var chart;
        $(document).ready(function() {
          var colors = Highcharts.getOptions().colors,
            categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
            name = 'Nivel de Ejecución',
            data = [{ 
                y: <?php echo $tab[6][1]?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][2];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][3];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][4];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][5];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][6];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][7];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][8];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][9];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][10];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][11];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][12];?>,
                color: '#61d1e4',
              }];
          
          function setChart(name, categories, data, color) {
            chart.xAxis[0].setCategories(categories);
            chart.series[0].remove();
            chart.addSeries({
              name: name,
              data: data,
              color: color || 'white'
            });
          }
          
          chart = new Highcharts.Chart({
            chart: {
              renderTo: 'container_ejec_print', 
              type: 'column'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
            },
            xAxis: {
              categories: categories              
            },
            yAxis: {
              title: {
                text: ''
              }
            },
            plotOptions: {
              column: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function() {
                      var drilldown = this.drilldown;
                      if (drilldown) { // drill down
                        setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                      } else { // restore
                        setChart(name, categories, data);
                      }
                    }
                  }
                },
                dataLabels: {
                  enabled: true,
                  color: colors[1],
                  style: {
                    fontWeight: 'bold'
                  },
                  formatter: function() {
                    return this.y +'%';
                  }
                }         
              }
            },
            tooltip: {
              formatter: function() {
                var point = this.point,
                  s = this.x +':<b>'+ this.y +'% </b><br/>';
                if (point.drilldown) {
                  s += ''+ point.category +' ';
                } else {
                  s += '';
                }
                return s;
              }
            },
            series:   [{
              name: name,
              data: data,
              color: 'white'
            }],
            exporting: {
              enabled: false
            }
          });
        });
      </script>
      <?php
    }

    return $tabla;
    }    
    /*---------------------------------------------------------------------------------*/
    /*------------------- Evaluacion A nivel Actividades -------------------*/
    public function nivel_actividad($aper_programa,$prod_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['programa']=$this->model_evalnacional->programa($aper_programa);
      $data['producto']=$this->model_evalnacional->vproducto($prod_id);
      $data['componente']=$this->model_evalnacional->vcomponente($data['producto'][0]['com_id']);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']);

      $data['act']=$this->list_actividades($aper_programa,$prod_id); /// Actividades

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/actividades/eactividades', $data);
    }

    /*------------------- Imprimir Evaluacion A nivel Actividades -------------------*/
    public function print_nivel_actividad($aper_programa,$prod_id){
      $data['act']=$this->imprimir_actividades($aper_programa,$prod_id);

      $this->load->view('admin/reportes_cns/eval_nacional/institucional/actividades/imprimir_eactividades', $data);
    }

    /*----------------------- Lista de Actividades ------------------------------*/
    public function list_actividades($aper_programa,$prod_id){
      $producto=$this->model_evalnacional->vproducto($prod_id);
      $componente=$this->model_evalnacional->vcomponente($producto[0]['com_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);
      $actividades = $this->model_actividad->list_act_anual($prod_id);
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }
      $nro=0;
      $tabla ='';

      if(count($actividades)!=0){
          foreach($actividades  as $rowa){
            $tab=$this->temporalizacion_actividades_programado($rowa['act_id']);
              /*-------------------------------------------------------*/
              for ($i=1; $i <=12 ; $i++) { 
                $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                
                if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
                if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
                if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
              }
              /*-------------------------------------------------------*/

            $nro++;
            $tabla .='<tr bgcolor=#eff1bf>';
            $tabla .='<td align=center>'.$nro.'<br>
                        <a href="'.site_url("").'/rep/get_nactividad/'.$aper_programa.'/'.$rowa['act_id'].'" title="REPORTE INSTITUCIONAL NACIONAL A NIVEL DEL ACTIVIDAD : '.$rowa['act_actividad'].'" id="myBtn1'.$rowa['act_id'].'"><img src="' . base_url() . 'assets/ifinal/rep_graf.png" WIDTH="40" HEIGHT="40"/></a><br>
                        <img id="load1'.$rowa['act_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">
                      </td>';
            $tabla .='<td>'.$rowa['act_actividad'].'</td>';
            $tabla .='<td>'.$rowa['indi_descripcion'].'</td>';
            $tabla .='<td>'.$rowa['act_indicador'].'</td>';
            $tabla .='<td>'.$rowa['act_fuente_verificacion'].'</td>';
            $tabla .='<td>'.$rowa['act_ponderacion'].'</td>';
            $tabla .='<td align=center><a data-toggle="modal" data-target="#'.$rowa['act_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" ><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="40" HEIGHT="40"/></a>
                        <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowa['act_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                  &times;
                                </button>
                                <h4 class="modal-title">
                                  '.$rowa['act_actividad'].'
                                </h4>
                              </div>
                              <div class="modal-body no-padding">
                                <div class="well">
                                  <div id="graf_eficacia'.$rowa['act_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                  <hr>
                                  <div id="container'.$rowa['act_id'].'" style="width: 800px; height: 300px; margin: 0 auto"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>';
            $tabla .='<td>';
                 $tabla .='<table class="table table table-bordered">
                    <thead>
                        <tr align=center>
                          <th style="width:7%;"></th>
                          <th style="width:8%;"><font color=#000>ENE.</font></th>
                          <th style="width:8%;"><font color=#000>FEB.</font></th>
                          <th style="width:8%;"><font color=#000>MAR.</font></th>
                          <th style="width:8%;"><font color=#000>ABR.</font></th>
                          <th style="width:8%;"><font color=#000>MAY.</font></th>
                          <th style="width:8%;"><font color=#000>JUN.</font></th>
                          <th style="width:8%;"><font color=#000>JUL.</font></th>
                          <th style="width:8%;"><font color=#000>AGO.</font></th>
                          <th style="width:8%;"><font color=#000>SEPT.</font></th>
                          <th style="width:8%;"><font color=#000>OCT.</font></th>
                          <th style="width:8%;"><font color=#000>NOV.</font></th>
                          <th style="width:8%;"><font color=#000>DIC.</font></th>
                        </tr>
                    </thead>
                      <tbody>';
                         $tabla .='<tr>';
                          $tabla .='<td>P</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[1][$i].'</td>';
                            }
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>PA</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[2][$i].'%</td>';
                            }
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>%PA</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[3][$i].'%</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[3][$i].'%</td>';
                            }
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>E</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[4][$i].'</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[4][$i].'</td>';
                            } 
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>EA</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[5][$i].'</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[5][$i].'</td>';
                            } 
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>%EA</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[6][$i].'%</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[6][$i].'%</td>';
                            } 
                          }
                        $tabla .='</tr>';
                        $tabla .='<tr>';
                          $tabla .='<td>%EFI</td>';
                          for ($i=1; $i <=12 ; $i++) {
                            if($i>=$vi & $i<=$vf){
                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[7][$i].'%</td>';
                            }
                            else{
                              $tabla .='<td>'.$tab[7][$i].'%</td>';
                            }
                          }
                        $tabla .='</tr>';
                      $tabla .='
                      </tbody>
                    </table>';
              $tabla .='</td>';
              $tabla.='<script>
                        document.getElementById("myBtn1'.$rowa['act_id'].'").addEventListener("click", function(){
                        document.getElementById("load1'.$rowa['act_id'].'").style.display = "block";
                      });
                      </script>';
                    ?>
                    <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                    <script type="text/javascript">
                    var chart;
                    $(document).ready(function() {
                      chart = new Highcharts.chart('graf_eficacia'+<?php echo $rowa['act_id']; ?>, {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE ACTIVIDAD '
                            },
                            xAxis: {
                                categories: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.']
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

                                name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                                data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

                            }, {
                                name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                                data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
                            }, {
                                name: '<b style="color: green;">MAYOR A 91%</b>',
                                data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][11]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
                            }]

                        });
                    });
                    </script>
                    <script type="text/javascript">
                        var chart1;
                        $(document).ready(function() {
                          chart1 = new Highcharts.Chart({
                            chart: {
                              renderTo: 'container'+<?php echo $rowa['act_id']; ?>,
                              defaultSeriesType: 'line'
                            },
                            title: {
                              text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE ACTIVIDAD'
                            },
                            subtitle: {
                              text: ''
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
                                  data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                                },
                                {
                                  name: 'EJECUCIÓN ACUMULADA EN %',
                                  data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                                }
                              ]
                          });
                        });
                      </script> 
                    <?php              
            $tabla .='</tr>';
        }
      }
      return $tabla;
    }

     /*----------------------------- Imprimir Actividades ----------------------------------*/
    public function imprimir_actividades($aper_programa,$prod_id){
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $tabla ='';
        $programa=$this->model_evalnacional->programa($aper_programa);
        $producto=$this->model_evalnacional->vproducto($prod_id);
        $componente=$this->model_evalnacional->vcomponente($producto[0]['com_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);
        $actividades = $this->model_actividad->list_act_anual($prod_id);

        if(count($actividades)!=0){
           $tabla .='
            <div class="table-responsive" align=center>
                  <table class="change_order_items" style="width: 100%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                      <thead>
                          <tr>
                            <th colspan=7>
                              <table width="100%">
                                <tr>
                                    <td width=20%; text-align:center;"">
                                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                                    </td>
                                    <td width=60%; class="titulo_pdf">
                                        <FONT FACE="courier new" size="1">
                                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTOS<br> 
                                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br> 
                                        <b>PROCESO : </b>'.$componente[0]['com_componente'].'<br>
                                        <b>PRODUCTO : </b>'.$producto[0]['prod_producto'].'
                                        </FONT>
                                    </td>
                                    <td width=20%; text-align:center;"">
                                    </td>
                                </tr>
                            </table>
                            </th>
                          </tr>
                          <tr class="even_row" bgcolor="#1c7368" align=center>
                            <th style="width:1%;"><font color="#ffffff">Nro</font></th>
                            <th style="width:10%;"><font color="#ffffff">ACTIVIDAD</font></th>
                            <th style="width:3%;"><font color="#ffffff">TIP. IND.</font></th>
                            <th style="width:5%;"><font color="#ffffff">INDICADOR</font></th>
                            <th style="width:3%;"><font color="#ffffff">%</font></th>
                            <th style="width:10%;"><font color="#ffffff">GRAFICO COMPARATIVO PROG. Vs EJEC.</font></th>
                            <th style="width:60%;"><font color="#ffffff">TEMPORALIDAD</font></th>
                          </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                      foreach($actividades  as $rowa){
                          $tab=$this->temporalizacion_actividades_programado($rowa['act_id']);
                          /*-------------------------------------------------------*/
                          for ($i=1; $i <=12 ; $i++) { 
                            $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                            
                            if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
                            if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
                            if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
                          }
                          /*-------------------------------------------------------*/
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td>'.$rowa['act_actividad'].'</td>';
                            $tabla .='<td>'.$rowa['indi_descripcion'].'</td>';
                            $tabla .='<td>'.$rowa['act_indicador'].'</td>';
                            $tabla .='<td>'.$rowa['act_ponderacion'].'</td>';
                            $tabla .='<td><div id="container'.$rowa['act_id'].'" style="width: 400px; height: 200px; margin: 0 auto"></div></td>';
                            $tabla .='<td>';
                            $tabla .='<table class="change_order_items" border=1>
                                      <thead>
                                          <tr bgcolor="#1c7368" align=center>
                                            <th style="width:7%;"></th>
                                            <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                                            <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                                          </tr>
                                      </thead>
                                        <tbody>';
                                           $tabla .='<tr>';
                                              $tabla .='<td>P</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[1][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[1][$i].'</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>PA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[2][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[2][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%PA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[3][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[3][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>E</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[4][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[4][$i].'</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>EA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[5][$i].'</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[5][$i].'</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%EA</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[6][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[6][$i].'%</td>';
                                                } 
                                              }
                                            $tabla .='</tr>';
                                            $tabla .='<tr>';
                                              $tabla .='<td>%EFI</td>';
                                              for ($i=1; $i <=12 ; $i++) {
                                                if($i>=$vi & $i<=$vf){
                                                  $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$tab[7][$i].'%</td>';
                                                }
                                                else{
                                                  $tabla .='<td>'.$tab[7][$i].'%</td>';
                                                }
                                              }
                                            $tabla .='</tr>';
                                        $tabla .='
                                        </tbody>
                                      </table>';
                            $tabla .='</td>';
                            ?>
                            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                            <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
                            <script type="text/javascript">
                                var chart1;
                                $(document).ready(function() {
                                  chart1 = new Highcharts.Chart({
                                    chart: {
                                      renderTo: 'container'+<?php echo $rowa['act_id']; ?>,
                                      defaultSeriesType: 'line'
                                    },
                                    title: {
                                      text: 'PROGRAMACI\u00D3N Y EJECUCI\u00D3N F\u00CDSICA DE ACTIVIDAD'
                                    },
                                    subtitle: {
                                      text: ''
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
                                            data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                                          },
                                          {
                                            name: 'EJECUCIÓN ACUMULADA EN %',
                                            data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                                          }
                                      ]
                                  });
                                });
                              </script>
                            <?php 
                             
                          $tabla .='</tr>';

                      }
            $tabla .='</tbody>
                    </table>
                  </div>';
            }
      return $tabla;
    }

    /*--------------------------------- Get Actividad ---------------------------------*/
    public function get_actividad($aper_programa,$act_id){
        $data['menu']=$this->menu(7); //// genera menu
        $data['programa']=$this->model_evalnacional->programa($aper_programa);
        $data['actividad']=$this->model_evalnacional->vactividad($act_id);
        $data['producto']=$this->model_evalnacional->vproducto($data['actividad'][0]['prod_id']);
        $data['componente']=$this->model_evalnacional->vcomponente($data['producto'][0]['com_id']);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['componente'][0]['proy_id']); 
      
        $tab=$this->temporalizacion_actividades_programado($act_id);
        /*-------------------------------------------------------*/
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          
          if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
          if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
          if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
        }
        /*-------------------------------------------------------*/
        $data['p']=$tab; /// Programado,Ejecutado
        $data['e']=$p; /// Eficacia

      $data['print_act']=$this->get_print_actividad($aper_programa,$act_id);
      $this->load->view('admin/reportes_cns/eval_nacional/institucional/actividades/get_actividad', $data);
    }

    public function get_print_actividad($aper_programa,$act_id){
      $programa=$this->model_evalnacional->programa($aper_programa);
      $actividad=$this->model_evalnacional->vactividad($act_id);
      $producto=$this->model_evalnacional->vproducto($actividad[0]['prod_id']);
      $componente=$this->model_evalnacional->vcomponente($producto[0]['com_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); 

      $tab=$this->temporalizacion_actividades_programado($act_id);
        /*-------------------------------------------------------*/
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
          
          if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
          if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
          if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
        }
        /*-------------------------------------------------------*/

      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="100%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                        <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                        <b>REPORTE : </b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE ACTIVIDAD<br> 
                        <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                        <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                        <b> PROCESO : </b>'.$componente[0]['com_componente'].'<br>
                        <b> PRODUCTO : </b>'.$producto[0]['prod_producto'].'<br>
                        <b> ACTIVIDAD : </b>'.$actividad[0]['act_actividad'].'
                        </FONT>
                    </td>
                  </tr>
                </table>

                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>EFICACIA INSTITUCIONAL NACIONAL A NIVEL DE ACTIVIDAD<b/></FONT><br>
                      <div id="graf_eficacia_print" style="width: 700px; height: 270px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[7][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="2"><b>CUADRO COMPARATIVO PROGRAMADO VS EJECUTADO <b/></FONT><br>
                      <div id="regresion" style="width: 700px; height: 270px; margin: 1 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                            <tr>
                              <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[3][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[6][$i].'%</td>';
                                }
                              $tabla .='
                              </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
                <div class="saltopagina"></div>
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%" align=center>
                  <tr>
                    <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=80%; class="titulo_pdf">
                        <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>PROGRAMACI&Oacute;N Y EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE ACTIVIDAD<br> 
                            <b>PROGRAMA : </b>'.$programa[0]['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br> 
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                            <b> PROCESO : </b>'.$componente[0]['com_componente'].'<br>
                            <b> PRODUCTO : </b>'.$producto[0]['prod_producto'].'<br>
                            <b> ACTIVIDAD : </b>'.$actividad[0]['act_actividad'].'
                        </FONT>
                    </td>
                  </tr>
                </table>
                <table class="change_order_items" align=center style="width: 80%;" border=1 style="float: center; margin-left: auto; margin-right: auto;"> 
                  <tr>
                    <td align=center >
                      <FONT FACE="courier new" size="1"><b>PROGRAMACI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE PRODUCTO<b/></FONT><br>
                      <div id="container_prog_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PROGRAMACI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[3][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align=center>
                      <FONT FACE="courier new" size="1"><b>EJECUCI&Oacute;N INSTITUCIONAL NACIONAL A NIVEL DE ACTIVIDAD<b/></FONT><br>
                      <div id="container_ejec_print" style="width: 650px; height: 260px; margin: 0 auto">
                      </div>
                    </td>
                  </tr>
                   <tr>
                    <td align=center>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:7%;"></th>
                              <th style="width:8%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:8%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:8%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:8%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:8%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:8%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:8%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:8%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%EJECUCI&Oacute;N ACUMULADA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  $tabla .='<td>'.$tab[6][$i].'%</td>';
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';
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
                  name: '<b style="color: #FF0000;">MENOR A 75%</b>',
                  data: [{y: <?php echo $p[2][1]?>, color: 'red'},{y: <?php echo $p[2][2]?>, color: 'red'},{y: <?php echo $p[2][3]?>, color: 'red'},{y: <?php echo $p[2][4]?>, color: 'red'},{y: <?php echo $p[2][5]?>, color: 'red'},{y: <?php echo $p[2][6]?>, color: 'red'},{y: <?php echo $p[2][7]?>, color: 'red'},{y: <?php echo $p[2][8]?>, color: 'red'},{y: <?php echo $p[2][9]?>, color: 'red'},{y: <?php echo $p[2][10]?>, color: 'red'},{y: <?php echo $p[2][11]?>, color: 'red'},{y: <?php echo $p[2][12]?>, color: 'red'}] 

              }, {
                  name: '<b style="color: #d6d21f;">ENTRE 76% Y 90%</b>',
                  data: [{y: <?php echo $p[3][1]?>, color: 'yellow'},{y: <?php echo $p[3][2]?>, color: 'yellow'},{y: <?php echo $p[3][3]?>, color: 'yellow'},{y: <?php echo $p[3][4]?>, color: 'yellow'},{y: <?php echo $p[3][5]?>, color: 'yellow'},{y: <?php echo $p[3][6]?>, color: 'yellow'},{y: <?php echo $p[3][7]?>, color: 'yellow'},{y: <?php echo $p[3][8]?>, color: 'yellow'},{y: <?php echo $p[3][9]?>, color: 'yellow'},{y: <?php echo $p[3][10]?>, color: 'yellow'},{y: <?php echo $p[3][11]?>, color: 'yellow'},{y: <?php echo $p[3][12]?>, color: 'yellow'}] 
              }, {
                  name: '<b style="color: green;">MAYOR A 91%</b>',
                  data: [{y: <?php echo $p[4][1]?>, color: 'green'},{y: <?php echo $p[4][2]?>, color: 'green'},{y: <?php echo $p[4][3]?>, color: 'green'},{y: <?php echo $p[4][4]?>, color: 'green'},{y: <?php echo $p[4][5]?>, color: 'green'},{y: <?php echo $p[4][6]?>, color: 'green'},{y: <?php echo $p[4][7]?>, color: 'green'},{y: <?php echo $p[4][8]?>, color: 'green'},{y: <?php echo $p[4][9]?>, color: 'green'},{y: <?php echo $p[4][10]?>, color: 'green'},{y: <?php echo $p[4][11]?>, color: 'green'},{y: <?php echo $p[4][12]?>, color: 'green'}] 
              }]

          });
      });
    </script>
    <script type="text/javascript">
        var chart1;
        $(document).ready(function() {
          chart1 = new Highcharts.Chart({
            chart: {
              renderTo: 'regresion',
              defaultSeriesType: 'line'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
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
                      data: [ <?php echo $tab[3][1];?>, <?php echo $tab[3][2];?>, <?php echo $tab[3][3];?>, <?php echo $tab[3][4];?>, <?php echo $tab[3][5];?>, <?php echo $tab[3][6];?>, <?php echo $tab[3][7];?>, <?php echo $tab[3][8];?>, <?php echo $tab[3][9];?>, <?php echo $tab[3][10];?>, <?php echo $tab[3][11];?>, <?php echo $tab[3][12];?>]
                  },
                  {
                      name: 'EJECUCIÓN ACUMULADA EN %',
                      data: [ <?php echo $tab[6][1];?>, <?php echo $tab[6][2];?>, <?php echo $tab[6][3];?>, <?php echo $tab[6][4];?>, <?php echo $tab[6][5];?>, <?php echo $tab[6][6];?>, <?php echo $tab[6][7];?>, <?php echo $tab[6][8];?>, <?php echo $tab[6][9];?>, <?php echo $tab[6][10];?>, <?php echo $tab[6][11];?>, <?php echo $tab[6][12];?>]
                  }
              ]
          });
        });
      </script>
      <script type="text/javascript">
        var chart;
          $(document).ready(function() {
            var colors = Highcharts.getOptions().colors,
              categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
              name = 'Nivel de Programación',
              data = [{ 
                  y: <?php echo $tab[3][1]?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][2];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][3];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][4];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][5];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][6];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][7];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][8];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][9];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][10];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][11];?>,
                  color: '#8f8fde',
                }, {
                  y: <?php echo $tab[3][12];?>,
                  color: '#8f8fde',
                }];
            
            function setChart(name, categories, data, color) {
              chart.xAxis[0].setCategories(categories);
              chart.series[0].remove();
              chart.addSeries({
                name: name,
                data: data,
                color: color || 'white'
              });
            }
            
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_prog_print', 
                type: 'column'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: categories              
              },
              yAxis: {
                title: {
                  text: ''
                }
              },
              plotOptions: {
                column: {
                  cursor: 'pointer',
                  point: {
                    events: {
                      click: function() {
                        var drilldown = this.drilldown;
                        if (drilldown) { // drill down
                          setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                        } else { // restore
                          setChart(name, categories, data);
                        }
                      }
                    }
                  },
                  dataLabels: {
                    enabled: true,
                    color: colors[1],
                    style: {
                      fontWeight: 'bold'
                    },
                    formatter: function() {
                      return this.y +'%';
                    }
                  }         
                }
              },
              tooltip: {
                formatter: function() {
                  var point = this.point,
                    s = this.x +':<b>'+ this.y +'% </b><br/>';
                  if (point.drilldown) {
                    s += ''+ point.category +' ';
                  } else {
                    s += '';
                  }
                  return s;
                }
              },
              series:   [{
                name: name,
                data: data,
                color: 'white'
              }],
              exporting: {
                enabled: false
              }
            });
          });
        </script>
        <script type="text/javascript">
        var chart;
        $(document).ready(function() {
          var colors = Highcharts.getOptions().colors,
            categories = ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sept.', 'Oct.', 'Nov.', 'Dic.'],
            name = 'Nivel de Ejecución',
            data = [{ 
                y: <?php echo $tab[6][1]?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][2];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][3];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][4];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][5];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][6];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][7];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][8];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][9];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][10];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][11];?>,
                color: '#61d1e4',
              }, {
                y: <?php echo $tab[6][12];?>,
                color: '#61d1e4',
              }];
          
          function setChart(name, categories, data, color) {
            chart.xAxis[0].setCategories(categories);
            chart.series[0].remove();
            chart.addSeries({
              name: name,
              data: data,
              color: color || 'white'
            });
          }
          
          chart = new Highcharts.Chart({
            chart: {
              renderTo: 'container_ejec_print', 
              type: 'column'
            },
            title: {
              text: ''
            },
            subtitle: {
              text: ''
            },
            xAxis: {
              categories: categories              
            },
            yAxis: {
              title: {
                text: ''
              }
            },
            plotOptions: {
              column: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function() {
                      var drilldown = this.drilldown;
                      if (drilldown) { // drill down
                        setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                      } else { // restore
                        setChart(name, categories, data);
                      }
                    }
                  }
                },
                dataLabels: {
                  enabled: true,
                  color: colors[1],
                  style: {
                    fontWeight: 'bold'
                  },
                  formatter: function() {
                    return this.y +'%';
                  }
                }         
              }
            },
            tooltip: {
              formatter: function() {
                var point = this.point,
                  s = this.x +':<b>'+ this.y +'% </b><br/>';
                if (point.drilldown) {
                  s += ''+ point.category +' ';
                } else {
                  s += '';
                }
                return s;
              }
            },
            series:   [{
              name: name,
              data: data,
              color: 'white'
            }],
            exporting: {
              enabled: false
            }
          });
        });
      </script>
      <?php

    return $tabla;
    }

    /*--------------------------- Sumatoria Temporalidad Actividades ----------------------------*/
    public function temporalidad_actividades($act_id){
      $actividad = $this->model_actividad->get_actividad_id($act_id);
      $act_prog= $this->model_actividad->actividad_programado($act_id,$this->gestion);//// Temporalidad Programado
      $act_ejec= $this->model_actividad->ejecutado_actividad($act_id,$this->gestion); //// Temporalidad ejecutado

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
      
      $pa=0; $ea=0;
      if(count($act_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $pa=$pa+$act_prog[0][$mp[$i]];
          if($actividad[0]['act_meta']!=0){
          //  $p=round(((($pa+$actividad[0]['act_linea_base'])/$actividad[0]['act_meta'])*100),2); // %pa
            $p=round((($pa/$actividad[0]['act_meta'])*100),2); // %pa
          }
          $matriz[1][$i]=round((($p*$actividad[0]['act_ponderacion'])/100),2); // %
        }
      }

      if(count($act_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $ea=$ea+$act_ejec[0][$mp[$i]];
          if($actividad[0]['act_meta']!=0){
            //$e=round(((($ea+$actividad[0]['act_linea_base'])/$actividad[0]['act_meta'])*100),2); // %ea
            $e=round((($ea/$actividad[0]['act_meta'])*100),2); // %ea
          }
          $matriz[2][$i]=round((($e*$actividad[0]['act_ponderacion'])/100),2); // %

        }
      }
      return $matriz;
    }

    public function temporalizacion_actividades_programado($act_id){
      $actividad = $this->model_actividad->get_actividad_id($act_id);
      $act_prog= $this->model_actividad->actividad_programado($act_id,$this->gestion);//// Temporalidad Programado
      $act_ejec= $this->model_actividad->ejecutado_actividad($act_id,$this->gestion); //// Temporalidad ejecutado

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
        $matriz[1][$i]=0; /// Programado
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($act_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$act_prog[0][$mp[$i]];
          $pa=$pa+$act_prog[0][$mp[$i]];
        //  $matriz[2][$i]=$pa+$actividad[0]['act_linea_base'];
          $matriz[2][$i]=$pa;
          if($actividad[0]['act_meta']!=0){
            $matriz[3][$i]=round((($matriz[2][$i]/$actividad[0]['act_meta'])*100),2);
          }
        }
      }

      if(count($act_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$act_ejec[0][$mp[$i]];
          $ea=$ea+$act_ejec[0][$mp[$i]];
        //  $matriz[5][$i]=$ea+$actividad[0]['act_linea_base'];
          $matriz[5][$i]=$ea;
          if($actividad[0]['act_meta']!=0){
            $matriz[6][$i]=round((($matriz[5][$i]/$actividad[0]['act_meta'])*100),2);
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }
      
      return $matriz;
    }

    /*---------------------------Sumatoria Temporalidad Productos ---------------------------*/
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
          $pa=$pa+$prod_prog[0][$mp[$i]];
          if($producto[0]['prod_meta']!=0){
            //$pm=round(((($pa+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %pa
            $pm=round((($pa/$producto[0]['prod_meta'])*100),2); // %pa
          }
          $matriz[1][$i]=round((($pm*$producto[0]['prod_ponderacion'])/100),2); // %
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $ea=$ea+$prod_ejec[0][$mp[$i]];
          if($producto[0]['prod_meta']!=0){
          //  $em=round(((($ea+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %ea
            $em=round((($ea/$producto[0]['prod_meta'])*100),2); // %ea
          }
          $matriz[2][$i]=round((($em*$producto[0]['prod_ponderacion'])/100),2); // %

        }
      }
      
      return $matriz;
    }

    public function temporalidad_productos_programado($prod_id){
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
        $matriz[1][$i]=0; /// Programado
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
          $pa=$pa+$prod_prog[0][$mp[$i]];
          //$matriz[2][$i]=$pa+$producto[0]['prod_linea_base'];
          $matriz[2][$i]=$pa;
          if($producto[0]['prod_meta']!=0){
            $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];
          $ea=$ea+$prod_ejec[0][$mp[$i]];
          //$matriz[5][$i]=$ea+$producto[0]['prod_linea_base'];
          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }

      return $matriz;
    }
    /*--------------------------------------------------------------------------------*/

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

    public function reporte_nivel_programas(){
    $html = ''.$this->imprimir_programas().'';
    //  $pag=redirect(site_url("").'/rep/eval_nacional');
    //  $html=file_get_contents('http://localhost/PLANIFICACIONNUEVO/index.php/rep/eval_nprogramas/');
      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','556M');
      ini_set('max_execution_time', 9000);
      $dompdf->render();
      $dompdf->stream("REPORTE CITES GENERADOS.pdf", array("Attachment" => false));
    }
}