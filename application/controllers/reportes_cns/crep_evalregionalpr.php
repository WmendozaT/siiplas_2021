<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalregionalpr extends CI_Controller {  
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

    /*-------- MENU CONSOLIDADO REGIONAL --------*/
    public function consolidado_regional_priorizados($dep_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dep']=$this->model_evalregional->get_dpto($dep_id);
      if(count($data['dep'])!=0){

        $data['tr']=($this->tmes*3);
        $data['trimestre']=$this->model_evaluacion->trimestre();
        
        /*---------- Operaciones con Prioridad ----------*/ 
        $data['matriz']=$this->matriz_consolidado_tp_operaciones($dep_id,1); /// matriz prioridad
        $data['ope_priori']=$this->consolidado_tp_operaciones($data['matriz'],$dep_id,1); /// prioridad 
        $data['print_ope_priori']=$this->print_consolidado_tp_operaciones($data['matriz'],$dep_id,1); /// print prioridad 

        /*---------- Operaciones sin Prioridad ----------*/ 
        $data['matriz_n']=$this->matriz_consolidado_tp_operaciones($dep_id,0); /// matriz Sin prioridad
        $data['ope_npriori']=$this->consolidado_tp_operaciones($data['matriz_n'],$dep_id,0); /// Sin prioridad 
        $data['print_ope_npriori']=$this->print_consolidado_tp_operaciones($data['matriz_n'],$dep_id,0); /// print sin prioridad 


        $this->load->view('admin/reportes_cns/eval_regional/reporte_eval/eval_consolidado_regional_prioridad', $data);
      }
      else{
        redirect('admin/dashboard');
      }
      
    }

    /*------------ TABLA LISTA DE OPERACIONES SEGUN EL TIPO DE PRIORIDAD -------------*/
    public function consolidado_tp_operaciones($matriz,$dep_id,$tp){
      $trimestre=$this->model_evaluacion->trimestre();
      $operaciones=$this->model_evaluacion->list_operaciones_tp_regional($dep_id,$tp);
      if($tp==1){
        $tab='id="dt_basic1" class="table1 table table-bordered" width="100%" border=1';
        $titulo='CON PRIORIDAD';
      }
      else{
        $tab='id="dt_basic" class="table table table-bordered" width="100%" border=1';
        $titulo='SIN PRIORIDAD';
      }

      /*----- PARA EL CUADRO ------*/
      $total_ope=$matriz[count($operaciones)+1][1];
      $cumplido=$matriz[count($operaciones)+1][2];
      $avance=$matriz[count($operaciones)+1][3];
      $no_cumplido=$matriz[count($operaciones)+1][4];
      
      $pcion=round((($cumplido/$total_ope)*100),2);
      $npcion=(100-$pcion);

      $graf_c=round((($cumplido/$total_ope*100)),2); // Cumplido Acumulado
      $graf_av=round((($avance/$total_ope*100)),2); // Avance Acumulado
      $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido Acumulado

      $tabla='';
      $tabla.=
        '
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
          <div class="jarviswidget jarviswidget-color-darken" >
              <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                <h2 class="font-md"><strong>'.count($operaciones).'.- OPERACIONES '.$titulo.'</strong></h2>  
              </header>
            <div>
              <div class="widget-body no-padding">
                <table '.$tab.'>
                  <thead>
                    <tr>
                      <th style="width: 1%"><center><b>#</b></center></th>
                      <th style="width: 5%"><center><b>PROGRAMA</b></center></th>
                      <th style="width: 2%"><center><b>TIPO</b></center></th>
                      <th style="width: 10%"><center><b>COMPONENTE</b></center></th>
                      <th style="width: 10%"><center><b>OPERACI&Oacute;N</b></center></th>
                      <th style="width: 5%"><center><b>TIPO</b></center></th>
                      <th style="width: 5%"><center><b>PROGRAMADO</b></center></th>
                      <th style="width: 5%"><center><b>EJECUTADO</b></center></th>
                      <th style="width: 5%"><center><b>EFICACIA</b></center></th>
                      <th style="width: 5%"><center><b></b></center></th>
                    </tr>
                  </thead>
                  <tbody>';
                    for ($i=1; $i <=count($operaciones) ; $i++) {
                      $tabla.='
                        <tr>
                          <td align=center style="height:15px;">'.$i.'</td>';
                        for ($j=1; $j <=9 ; $j++) {
                          $tabla.='<td bgcolor='.$matriz[$i][10].'>'.$matriz[$i][$j].'</td>';
                        }
                      $tabla.='</tr>';
                    }
              $tabla.='
                  </tbody>
                  </table>
              </div>
            </article>
            <article class="col-sm-12 col-md-12 col-lg-5">
              <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                  <span class="widget-icon"><i class="fa-bell-o"></i></span> 
                </header>
                  <div>
                    <div class="jarviswidget-editbox">
                    </div>
                  <div class="widget-body no-padding">
                    <header align="center"><b>CUADRO TOTAL DE OPERACIONES '.$titulo.' AL '.$trimestre[0]['trm_descripcion'].'</b></header>
                      <fieldset>
                        <div id="container'.$tp.'" style="width: 800px; height: 458px; margin: 0 auto"></div>
                        <hr>
                        <div class="table-responsive">
                          <table class="table table-bordered" align=center style="width:100%;">
                            <thead>
                              <tr bgcolor="#1c7368" align=center>
                                <th style="width:14%;">NRO DE OPERACIONES</th>
                                <th style="width:14%;">CUMPLIDOS</th>
                                <th style="width:14%;">EN AVANCE</th>
                                <th style="width:14%;">NO CUMPLIDO</th>
                                <th style="width:15%;">% CUMPLIDO</th>
                                <th style="width:15%;">% NO CUMPLIDO</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr align=center>
                                <td>'.$total_ope.'</td>
                                <td>'.$cumplido.'</td>
                                <td>'.$avance.'</td>
                                <td>'.$no_cumplido.'</td>
                                <td title="OPERACIONES CUMPLIDAS"><button type="button" style="width:100%;" class="btn btn-info">'.$pcion.'%</button></td>
                                <td title="OPERACIONES NO CUMPLIDAS"><button type="button" style="width:100%;" class="btn btn-danger">'.$npcion.'%</button></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </fieldset>
                  </div>
                </div>
              </div>
            </article>';
            ?>
            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container'+<?php echo $tp;?>, {
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
               Highcharts.chart('container_print'+<?php echo $tp;?>, {
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
            <?php
      return $tabla;
    }

    /*------------- MATRIZ OPERACIONES SEGUN SU TIPO DE PRIORIDAD -------------*/
    public function matriz_consolidado_tp_operaciones($dep_id,$tp){
      $operaciones=$this->model_evaluacion->list_operaciones_tp_regional($dep_id,$tp);

      for ($i=1; $i <=count($operaciones)+1 ; $i++) { 
        for ($j=1; $j <=10 ; $j++) { 
          $ope[$i][$j]='';
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

      $nro=0;
      $cont_cumplidos=0;
      $cont_avance=0;
      $cont_ncumplido=0;
      $sum_prog=0;
      $sum_ejec=0;
      foreach($operaciones as $row){
        $nro++;
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

        if($efi==100){
          $cont_cumplidos++;
          $titulo='CUMPLIDO';
          $bgcolor='#d1f9f4';
        }
        elseif ($efi==0) {
          $cont_ncumplido++;
          $titulo='NO CUMPLIDO';
          $bgcolor='#f7c9c4';
        }
        else{
          $cont_avance++;
          $titulo='EN AVANCE';
          $bgcolor='#f6f7c4';
        }

        $sum_prog=$sum_prog+$total_prog;
        $sum_ejec=$sum_ejec+$total_eval;
        $ope[$nro][1]=''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].''; // prog
        $ope[$nro][2]=$row['tp_sigla']; // Tipo de Proyecto
        $ope[$nro][3]=$row['com_componente']; // Componente / Servicio
        $ope[$nro][4]=$row['prod_producto']; // Producto
        $ope[$nro][5]=$row['indi_abreviacion']; // Tipo de Indicador
        $ope[$nro][6]=$total_prog; // Programado
        $ope[$nro][7]=$total_eval; // Ejecutado
        $ope[$nro][8]=$efi; // Eficacia

        $ope[$nro][9]=$titulo; // Titulo
        $ope[$nro][10]=$bgcolor; // Color
      }
      $nro++;

      $ope[$nro][1]=count($operaciones);
      $ope[$nro][2]=$cont_cumplidos;
      $ope[$nro][3]=$cont_avance;
      $ope[$nro][4]=$cont_ncumplido;

      $ope[$nro][6]=$sum_prog;
      $ope[$nro][7]=$sum_ejec;

      return $ope;
    }

    /*------------ PRINT OPERACIONES CON/SIN PRIORIDAD ------------*/
    public function print_consolidado_tp_operaciones($matriz,$dep_id,$tp){
      $dep=$this->model_evalregional->get_dpto($dep_id);
      $operaciones=$this->model_evaluacion->list_operaciones_tp_regional($dep_id,$tp);
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      if($tp==1){
        $titulo='CON PRIORIDAD';
      }
      else{
        $titulo='SIN PRIORIDAD';
      }

      /*----- PARA EL CUADRO ------*/
      $total_ope=$matriz[count($operaciones)+1][1];
      $cumplido=$matriz[count($operaciones)+1][2];
      $avance=$matriz[count($operaciones)+1][3];
      $no_cumplido=$matriz[count($operaciones)+1][4];
      
      $pcion=round((($cumplido/$total_ope)*100),2);
      $npcion=(100-$pcion);

      $graf_c=round((($cumplido/$total_ope*100)),2); // Cumplido Acumulado
      $graf_av=round((($avance/$total_ope*100)),2); // Avance Acumulado
      $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido Acumulado

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
                <hr>
                <center><FONT FACE="courier new" size="2"><b>CUADRO DE EVALUACI&Oacute;N DE OPERACIONES '.$titulo.' AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>
                <table class="change_order_items" border=1>
                    <tr>
                      <td>
                        <div id="container_print'.$tp.'" style="width: 600px; height: 300px; margin: 0 auto"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      <div class="table-responsive">
                        <table class="change_order_items" border=1>
                          <thead>
                              <tr align=center>
                                <th style="width:14%;">NRO DE OPERACIONES</th>
                                <th style="width:14%;">CUMPLIDOS</th>
                                <th style="width:14%;">EN AVANCE</th>
                                <th style="width:14%;">NO CUMPLIDO</th>
                                <th style="width:15%;">% CUMPLIDO</th>
                                <th style="width:15%;">% NO CUMPLIDO</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr align=center>
                                <td>'.$total_ope.'</td>
                                <td>'.$cumplido.'</td>
                                <td>'.$avance.'</td>
                                <td>'.$no_cumplido.'</td>
                                <td>'.$pcion.'%</td>
                                <td>'.$npcion.'%</td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      </td>
                    </tr>
                    </table>';
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