<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalaccion extends CI_Controller {  
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

    public function graf_evaluacion_unidad($proy_id){
      echo "Hola Mundo";
    }
    
    /*--------------------------- TIPO DE RESPONSABLE ---------------------------*/
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

    /*----------- Menu Accion Operativa ---------------*/
    public function menu_accion($proy_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['trimestre']=$this->model_evaluacion->trimestre();
      if(count($data['proyecto'])!=0){
        $data['dist']=$this->model_evalregional->get_dist($data['proyecto'][0]['dist_id']);
        /*------- Eficacia Unidad Organizacional ------*/
        $data['tabla']=$this->eficacia_proyecto($proy_id);
        $data['print_tabla']=$this->print_proyectos_unidad($proy_id,$data['tabla']);
        /*-------- Eficacia Componentes/Productos ----------*/
        $data['componente']=$this->eficacia_procesos($proy_id);
        /*-------- evaluacion de operaciones por Componentes ---------- () */
        $data['eval_componente']=$this->evaluacion_procesos($proy_id,1);
        /*-------- Cuadro de Evaluacion de operaciones por Unidad (2) --------*/
        $data['evaluacion']=$this->evaluacion_accion($proy_id);
        $data['eval_unidad']=$this->get_print_cuadro_eval_unidad($proy_id); /// print Evaluacion distrital/Acumulado

        $data['tr']=($this->tmes*3);

        $puntaje=$data['tabla'][3][$this->tmes*3];
        $color='';
        if($puntaje<=75){$color='#f95b4f';} /// Insatisfactorio
        if ($puntaje > 75 & $puntaje <= 90){$color='#c79121';} /// Regular
        if($puntaje > 90 & $puntaje <= 99){$color='#57889c';} /// Bueno
        if($puntaje > 99 & $puntaje <= 102){$color='#6d966d';} /// Optimo

        $data['color']=$color;

        $this->load->view('admin/reportes_cns/eval_distrital/eval_consolidado_accion', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*-------- Consolidado Accion Operativa ----------*/
    public function eficacia_proyecto($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
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
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo
      }

      $tab=$this->componentes($proy_id);
        
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$tab[1][$i];
          $p[2][$i]=$tab[2][$i];
          if($tab[1][$i]!=0){
            $p[3][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);
            $p[4][$i]=$m[$i];

            if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
            if ($p[3][$i] > 75 & $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
            if($p[3][$i] > 90 & $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
            if($p[3][$i] > 99 & $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
          }
        }

      return $p;
    }


    /*--------- Imprime Evaluacion Consolidado De Opetraciones por Unidad ----------*/
    public function get_print_cuadro_eval_unidad($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $eval_acu=$this->matriz_evaluado_proyecto($proy_id,2); /// Evalucion Trimestral Acumulado
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();

      $graf_c_a=0;$graf_av_a=0;
      if($eval_acu[7]!=0){
        $graf_c_a=round((($eval_acu[1]/$eval_acu[7]*100)),2); // Cumplido Acumulado 
        $graf_av_a=round((($eval_acu[2]/$eval_acu[7]*100)),2); // Avance Acumulado 
      }
      
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
        $tabla .='<table width="100%" align=center border=0>
                  <tr>
                    <td width=15%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="50px"></center>
                    </td>
                    <td width=65%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <FONT FACE="courier new" size="1.5">
                            <b>'.$this->session->userdata('entidad').'</b><br>
                            <b>REGIONAL : </b>'.strtoupper($proyecto[0]['dep_departamento']).'<br>
                            <b>DISTRITAL : </b>'.strtoupper($proyecto[0]['dist_distrital']).'<br>
                            <b>UNIDAD / PROYECTO : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'
                            </FONT>
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
              <center><FONT FACE="courier new" size="2"><b>CUADRO DE EVALUACI&Oacute;N ACUMULADA DE OPERACIONES AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <table class="change_order_items" border=1>
                <tr>
                  <td>
                  <div id="container_acu1" style="width: 630px; height: 330px; margin: 0 auto"></div>
                  </td>
                </tr>
                <tr>
                  <td>
                  <div class="table-responsive">
                    <table class="change_order_items" border=1 align=center style="width:100%;">
                      <thead>
                        <tr bgcolor="#1c7368" align=center>
                          <th style="width:14%;"><font color="#ffffff">CUMPLIDO</font></th>
                          <th style="width:14%;"><font color="#ffffff">EN AVANCE</font></th>
                          <th style="width:14%;"><font color="#ffffff">NO CUMPLIDO</font></th>
                          <th style="width:14%;"><font color="#ffffff">TOTAL PROG.</font></th>
                          <th style="width:14%;"><font color="#ffffff">TOTAL EVAL.</font></th>
                          <th style="width:15%;"><font color="#ffffff">% CUMPLIDO</font></th>
                          <th style="width:15%;"><font color="#ffffff">% NO CUMPLIDO</font></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr align=center>
                          <td>'.$eval_acu[1].'</td>
                          <td>'.$eval_acu[2].'</td>
                          <td>'.$eval_acu[3].'</td>
                          <td>'.$eval_acu[7].'</td>
                          <td>'.$eval_acu[4].'</td>
                          <td title="OPERACIONES CUMPLIDOS" bgcolor="#e1f5d8">'.$eval_acu[5].' %</td>
                          <td title="OPERACIONES NO CUMPLIDOS" bgcolor="#f5dede">'.$eval_acu[6].' %</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  </td>
                </tr>
                </table>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <hr>
              </div>';
            ?>
            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container_acu1', {
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
      </html>
      <?php
      return $tabla;
    }
    /*---------------------------------------------------------------------------------*/

    /*---------- Reporte Lista de Componentes---------------*/
    public function reporte_evaluacion_componente($proy_id){
      $data['mes'] = $this->mes_nombre();
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);

      $data['trimestre']=$this->model_evaluacion->trimestre();
      $data['servicios']=$this->evaluacion_procesos($proy_id,2);
      $this->load->view('admin/reportes_cns/eval_distrital/reporte_eval_unidad', $data);
    }


    /*------------------- Imprime Evaluacion Consolidado Unidad -----------------------*/
    public function print_proyectos_unidad($proy_id,$p){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      //$dist=$this->model_evalregional->get_dist($dist_id);
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
      $tabla .='<table width="90%" align=center>
                  <tr>
                    <td width=22%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="50px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <FONT FACE="courier new" size="2">
                            <b>'.$this->session->userdata('entidad').'</b><br>
                            <b>REGIONAL : </b>'.strtoupper($proyecto[0]['dep_departamento']).'<br>
                            <b>DISTRITAL : </b>'.strtoupper($proyecto[0]['dist_distrital']).'<br>
                            <b>UNIDAD / PROYECTO : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'
                            </FONT>
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
                      <div id="regresion_lineal2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div id="g_efi2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan=2>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:10%;"></th>
                              <th style="width:7%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:7%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:7%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:7%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:7%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:7%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:7%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:7%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:7%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:7%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:7%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:7%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PA.</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4">'.$p[1][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EA.</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4">'.$p[2][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4"><b>'.$p[3][$i].'%</b></td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[3][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>';
        $tabla .='</table>';
    return $tabla;
    } 

    /*------ Eficiacia de Procesos-Componentes --------*/
    public function eficacia_procesos($proy_id){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id); //// Datos Proyectos
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos Fase Activa
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyectos[0]['tp_id']);

      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }
      $nroc=0;
      $tabla ='';
      if(count($componente)!=0){
          foreach($componente as $rowc){
          $nroc++;
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
            $tabla .='
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:1%;"><center>Nro</center></th>
                      <th style="width:15%;"><center>PROCESO / COMPONENTE</center></th>
                      <th style="width:15%;"><center>PRODUCTO</center></th>
                      <th style="width:5%;"><center>META</center></th>
                      <th style="width:5%;"><center>PONDERACI&Oacute;N</center></th>
                      <th style="width:49%;"><center>TEMPORALIDAD</center></th>
                    </tr>
                  </thead>
                  <tbody>';
            $tabla .='<tr bgcolor="#9bf39f" title="COMPONENTE DE LA ACCI&Oacute;N OPERATIVA ('.$nroc.')">';
              $tabla .='<td>'.$rowc['com_id'].'---'.$nroc.'</td>';
              $tabla .='<td>'.$rowc['com_componente'].'</td>';
              $tabla .='<td></td>';
              $tabla .='<td></td>';
              $tabla .='<td>'.$rowc['com_ponderacion'].'%</td>';
              
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
            $tabla .='</tr>';

            $tabla .=''.$this->eficacia_productos($rowc['com_id'],$nroc);

          }
          $tabla .='</tbody>
            </table><hr>
          </div>';
        }
      }
      
      return $tabla;
    }

    /*--------------- Evaluacion de Procesos-Componentes -----------------*/
    public function evaluacion_procesos($proy_id,$tp){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id); //// Datos Proyectos
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos Fase Activa
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyectos[0]['tp_id']);

      if($tp==1){
        $class='class="table table-bordered" width="100%"';
      }
      else{
        $class='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center"';
      }

      $nroc=0;
      $tabla =''; $nro_prog=0;$nro_eval=0;$nro_cum=0;$nro_ncum=0;$nro_porcentaje=0;
      if(count($componente)!=0){
          $tabla .='
              <div class="table-responsive">
                <table '.$class.'>
                  <thead>
                    <tr class="modo1" align=center>
                      <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF; height:12px;">#</th>
                      <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">SERVICIO / COMPONENTE</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">PONDERACI&Oacute;N</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACIONES PROGRAMADAS</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACIONES EVALUADAS</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACIONES CUMPLIDAS</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACIONES NO CUMPLIDAS</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">% CUMPLIDAS</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">EFICACIA</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">CALIFICACI&Oacute;N</th>
                    </tr>
                  </thead>
                  <tbody>';
          foreach($componente as $rowc){
          $eval=$this->matriz_evaluado_componente($rowc['com_id'],2); /// Evaluado
          $p=$this->eficacia_evaluacion($rowc['com_id'],$proyectos[0]['proy_act'],1); /// Eficacia
          $nroc++;
            $tabla .='<tr class="modo1">';
              $tabla .='<td style="width: 1%; text-align: center; height:12px;" title="COMPONENTE UNIDAD ORGANIZACIONAL ('.$nroc.') - '.$rowc['com_id'].'">'.$nroc.'</td>';
              $tabla .='<td style="width: 15%; text-align: left;">'.$rowc['com_componente'].'</td>';
              $tabla .='<td style="width: 10%; text-align: center;">'.$rowc['com_ponderacion'].'%</td>';
              $tabla .='<td style="width: 10%; text-align: center;" bgcolor="#d0fbd2">'.$eval[7].'</td>';
              $tabla .='<td style="width: 10%; text-align: center;" bgcolor="#d0fbd2">'.$eval[4].'</td>';
              $tabla .='<td style="width: 10%; text-align: center;" bgcolor="#d0fbd2">'.$eval[1].'</td>';
              $tabla .='<td style="width: 10%; text-align: center;" bgcolor="#d0fbd2">'.($eval[2]+$eval[3]).'</td>';
              $tabla .='<td style="width: 10%; text-align: center;" bgcolor="#d0fbd2">'.$eval[5].'%</td>';
              if($tp==1){
                $tabla .='<td style="width: 10%; title="AVANCE RESULTADO"><button type="button" style="width:100%;" class="btn btn-default">'.$p[3][$this->tr_id].' %</button></td>';
                $tabla .='<td style="width: 10%; text-align: center;" title="NIVEL DE CALIFICACI&Oacute;N">'.$p[5][$this->tr_id].'</td>';
              }
              else{
                $tabla .='<td style="width: 10%; text-align: center;" title="EFICACIA">'.$p[3][$this->tr_id].' %</td>';
                if($p[3][$this->tr_id]>0 & $p[3][$this->tr_id]<=75){
                  $color="#f5dcdb"; $titulo='INSATISFACTORIO';
                }
                elseif($p[3][$this->tr_id]>=75 & $p[3][$this->tr_id]<=90){
                  $color="#efe8b2"; $titulo='REGULAR';
                }
                elseif($p[3][$this->tr_id]>=90 & $p[3][$this->tr_id]<=99){
                  $color="#cbe8f5"; $titulo='BUENO';
                }
                elseif($p[3][$this->tr_id]>=99 & $p[3][$this->tr_id]<=102){
                  $color="#a6eaa9"; $titulo='OPTIMO';
                }
                else{
                  $color="#f5dcdb"; $titulo='INSATISFACTORIO';
                }
                $tabla .='<td style="width: 10%; text-align: center;" bgcolor='.$color.'>'.$titulo.'</td>';
              }
              
            $tabla .='</tr>';
            $nro_prog=$nro_prog+$eval[7];
            $nro_eval=$nro_eval+$eval[4];
            $nro_cum=$nro_cum+$eval[1];
            $nro_ncum=$nro_ncum+($eval[2]+$eval[3]);
        }
        $tabla .='</tbody>
            <tr class="modo1" align=center>
              <td colspan="3" style="height:12px;">TOTAL : </td>
              <td>'.$nro_prog.'</td>
              <td>'.$nro_eval.'</td>
              <td>'.$nro_cum.'</td>
              <td>'.$nro_ncum.'</td>
              <td colspan=3></td>
            </tr>
            </table>
          </div>';
      }
      
      return $tabla;
    }

    /*------ Eficacia Evaluacion - calificacion por componente ------*/
    public function eficacia_evaluacion($com_id,$proy_act,$tp){
      //$tab=$this->componentes($proy_id);
      $tab=$this->productos($com_id,$proy_act);

      for ($i=1; $i <=12 ; $i++) { 
        $ev[1][$i]=0;$ev[2][$i]=0;$ev[3][$i]=0;$ev[4][$i]=0;
        if($tp==1){
          //$ev[5][$i]='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
          $ev[5][$i]='<a title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
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
                $enlace='<a title="CALIFICACI&Oacute;N : INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
              }
              else{
                $enlace='INSATISFACTORIO';
              }
              $ev[4][$i] = 1;$ev[5][$i] = $enlace;$ev[6][$i]='#f5dcdb';
            }

            elseif($ev[3][$i]>=75 & $ev[3][$i]<=90){
              if($tp==1){
                $enlace='<a title="CALIFICACI&Oacute;N : REGULAR" target="_blank" style="width:100%;" class="btn btn-warning">REGULAR</a>';
              }
              else{
                $enlace='REGULAR';
              }

              $ev[4][$i] = 2;$ev[5][$i] = $enlace;$ev[6][$i]='#efe8b2';
            }
            
            elseif($ev[3][$i]>=90 & $ev[3][$i]<=99){
              if($tp==1){
                $enlace='<a title="CALIFICACI&Oacute;N : BUENO" target="_blank" style="width:100%;" class="btn btn-info">BUENO</a>';
              }
              else{
                $enlace='BUENO';
              }

              $ev[4][$i] = 3;$ev[5][$i] = $enlace;$ev[6][$i]='#cbe8f5';
            }

            elseif($ev[3][$i]>=99 & $ev[3][$i]<=102){
              if($tp==1){
                $enlace='<a title="CALIFICACI&Oacute;N : OPTIMO" target="_blank" style="width:100%;" class="btn btn-success">OPTIMO</a>';
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


    /*---------- Total programado - Evaluado por proyectos-----------*/
    public function matriz_evaluado_componente($com_id,$tp_eval){
      for ($i=1; $i <=7 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        if($tp_eval==1){
          /*------ Trimestral Productos -----------*/
          $cum=$this->model_evalregional->evaluacion_proyecto_componente($com_id,1,$this->tmes); // cumplido - prod
          if(count($cum)!=0){
            $nro_cum=$cum[0]['total'];
          }
          $proc=$this->model_evalregional->evaluacion_proyecto_componente($com_id,2,$this->tmes); // en proceso - prod
          if(count($proc)!=0){
            $nro_proc=$proc[0]['total'];
          }
          $ncum=$this->model_evalregional->evaluacion_proyecto_componente($com_id,3,$this->tmes); // no cumplido - prod
          if (count($ncum)!=0) {
            $nro_ncum=$ncum[0]['total'];
          }
          $total_prog=$this->model_evalregional->total_programado_componente($com_id,$this->tmes); // total programado - prod
          if(count($total_prog)!=0){
            $nro_total_prog=$total_prog[0]['total'];
          }
          /*------------------------------------------*/
        }
        else{
          for ($i=1; $i <=$this->tmes ; $i++) { 
            /*------ Trimestral Productos Acumulado -----------*/
            $cum=$this->model_evalregional->evaluacion_proyecto_componente($com_id,1,$i); // cumplido - prod
            if(count($cum)!=0){
              $nro_cum=$nro_cum+$cum[0]['total'];
            }

            $proc=$this->model_evalregional->evaluacion_proyecto_componente($com_id,2,$i); // en proceso - prod
            if(count($proc)!=0){
              $nro_proc=$nro_proc+$proc[0]['total'];
            }

            $ncum=$this->model_evalregional->evaluacion_proyecto_componente($com_id,3,$i); // no cumplido - prod
            if(count($ncum)!=0){
              $nro_ncum=$nro_ncum+$ncum[0]['total'];
            }

            $total_prog=$this->model_evalregional->total_programado_componente($com_id,$i); // total programado - prod
            if(count($total_prog)!=0){
              $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
            }
            /*--------------------------------------*/
          }
        }
        
        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;

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


    /*----------------------- Lista de Productos ------------------------------*/
    public function eficacia_productos($com_id,$nroc){
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
            $tab=$this->temporalidad_productos_programado($rowp['prod_id']);
              /*-------------------------------------------------------*/
              for ($i=1; $i <=12 ; $i++) { 
                $p[1][$i]=0;$p[2][$i]=0;$p[3][$i]=0;$p[4][$i]=0;
                
                if($tab[7][$i]<=75){$p[2][$i] = $tab[7][$i];}else{$p[2][$i] = 0;}
                if($tab[7][$i]>=76 && $tab[7][$i] <= 90.9) {$p[3][$i] = $tab[7][$i];}else{$p[3][$i] = 0;}
                if($tab[7][$i]>=91){$p[4][$i] = $tab[7][$i];}else{$p[4][$i] = 0;}
              }
            $nro++;
            $tabla .='<tr bgcolor=#bef7c0 title="PRODUCTO ('.$nroc.'.'.$nro.')">';
            $tabla .='<td>'.$nroc.'.'.$nro.' - '.$rowp['prod_id'].'</td>';
            $tabla .='<td></td>';
            $tabla .='<td>'.$rowp['prod_producto'].'</td>';
            $tabla .='<td>'.$rowp['prod_meta'].'</td>';
            $tabla .='<td>'.$rowp['prod_ponderacion'].'%</td>';
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
              $tabla .='</td>
              </tr>';
             
        }
      }
      return $tabla;
    }

    /*----------------------- Lista de Actividades ------------------------------*/
    public function eficacia_actividades($prod_id,$nroc,$nrop){
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
            $tabla .='<tr bgcolor="#daf7db" title="ACTIVIDADES DEL PRODUCTO ('.$nroc.'.'.$nrop.'.'.$nro.')">';
            $tabla .='<td align=center>'.$nroc.'.'.$nrop.'.'.$nro.'.</td>';
            $tabla .='<td></td>';
            $tabla .='<td></td>';
            $tabla .='<td>'.$rowa['act_actividad'].'</td>';
            $tabla .='<td>'.$rowa['act_meta'].'</td>';
            $tabla .='<td>'.$rowa['act_ponderacion'].'</td>';
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
                        $tabla .='<tr bgcolor="#daf3ef">';
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
                        $tabla .='<tr bgcolor="#daf3ef">';
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
              $tabla .='</td>
                  </tr>';
        }
      }
      return $tabla;
    }

    /*---- EVALUACION TRIMESTRAL Y CAUMULADO DE OPERACIONES POR UNIDAD ---*/ 
    public function evaluacion_accion($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $eval=$this->matriz_evaluado_proyecto($proy_id,1); /// Evaluacion Trimestral
      $eval_acu=$this->matriz_evaluado_proyecto($proy_id,2); /// Evalucion Trimestral Acumulado
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }

      $graf_c=0; $graf_av=0;$graf_c_a=0;$graf_av_a=0;

      if($eval[7]!=0){
        $graf_c=round((($eval[1]/$eval[7]*100)),2); // Cumplido 
        $graf_av=round((($eval[2]/$eval[7]*100)),2); // Avance 
      }
      
      $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido

      if($eval_acu[7]!=0){
        $graf_c_a=round((($eval_acu[1]/$eval_acu[7]*100)),2); // Cumplido Acumulado 
        $graf_av_a=round((($eval_acu[2]/$eval_acu[7]*100)),2); // Avance Acumulado 
      }
      
      $graf_nc_a=round((100-($graf_c_a+$graf_av_a)),2); // No cumplido Acumulado

      $tabla ='';
      $tabla .='
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h2 class="alert alert-success" align="center">'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

              <table class="change_order_items" border=1>
                <tr>
                  <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL</b></font><br>
                    <font FACE="courier new" size="1"><b>'.$trimestre.'</b></font>
                  </center>
                  <div id="container_tri" style="width: 600px; height: 300px; margin: 0 auto"></div>
                  </td>
                </tr>
                <tr>
                  <td>
                  <div class="table-responsive">
                    <table class="table table-bordered" align=center style="width:100%;">
                      <thead>
                      <tr bgcolor="#1c7368" align=center>
                        <th style="width:14%;">CUMPLIDO</th>
                        <th style="width:14%;">EN AVANCE</th>
                        <th style="width:14%;">NO CUMPLIDO</th>
                        <th style="width:15%;">TOTAL PROG.</th>
                        <th style="width:14%;">TOTAL EVAL.</th>
                        <th style="width:15%;">% CUMPLIDO</th>
                        <th style="width:15%;">% NO CUMPLIDO</th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr align=center>
                          <td>'.$eval[1].'</td>
                          <td>'.$eval[2].'</td>
                          <td>'.$eval[3].'</td>
                          <td>'.$eval[7].'</td>
                          <td>'.$eval[4].'</td>
                          <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[5].' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[6].' %</button></td>
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
                <div id="container_acu" style="width: 600px; height: 300px; margin: 0 auto"></div>
                </td>
              </tr>
              <tr>
                <td>
                <div class="table-responsive">
                  <table class="table table-bordered" align=center style="width:100%;">
                    <thead>
                      <tr bgcolor="#1c7368" align=center>
                        <th style="width:14%;">CUMPLIDO</th>
                        <th style="width:14%;">EN AVANCE</th>
                        <th style="width:14%;">NO CUMPLIDO</th>
                        <th style="width:14%;">TOTAL PROG.</th>
                        <th style="width:14%;">TOTAL EVAL.</th>
                        <th style="width:15%;">% CUMPLIDO</th>
                        <th style="width:15%;">% NO CUMPLIDO</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr align=center>
                        <td>'.$eval_acu[1].'</td>
                        <td>'.$eval_acu[2].'</td>
                        <td>'.$eval_acu[3].'</td>
                        <td>'.$eval_acu[7].'</td>
                        <td>'.$eval_acu[4].'</td>
                        <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval_acu[5].' %</button></td>
                        <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval_acu[6].' %</button></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </td>
              </tr>
              </table>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr>
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
    
    /*---------------------- Total programado - Evaluado por proyectos-------------------*/
    public function matriz_evaluado_proyecto($proy_id,$tp_eval){
      for ($i=1; $i <=7 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        if($tp_eval==1){
          /*------ Trimestral Productos -----------*/
          $cum=$this->model_evalregional->evaluacion_proyecto($proy_id,1,$this->tmes); // cumplido - prod
          if(count($cum)!=0){
            $nro_cum=$cum[0]['total'];
          }
          $proc=$this->model_evalregional->evaluacion_proyecto($proy_id,2,$this->tmes); // en proceso - prod
          if(count($proc)!=0){
            $nro_proc=$proc[0]['total'];
          }
          $ncum=$this->model_evalregional->evaluacion_proyecto($proy_id,3,$this->tmes); // no cumplido - prod
          if (count($ncum)!=0) {
            $nro_ncum=$ncum[0]['total'];
          }
          $total_prog=$this->model_evalregional->total_programado_accion($proy_id,$this->tmes); // total programado - prod
          if(count($total_prog)!=0){
            $nro_total_prog=$total_prog[0]['total'];
          }
          /*------------------------------------------*/
        }
        else{
          for ($i=1; $i <=$this->tmes ; $i++) { 
            /*------ Trimestral Productos Acumulado -----------*/
            $cum=$this->model_evalregional->evaluacion_proyecto($proy_id,1,$i); // cumplido - prod
            if(count($cum)!=0){
              $nro_cum=$nro_cum+$cum[0]['total'];
            }

            $proc=$this->model_evalregional->evaluacion_proyecto($proy_id,2,$i); // en proceso - prod
            if(count($proc)!=0){
              $nro_proc=$nro_proc+$proc[0]['total'];
            }

            $ncum=$this->model_evalregional->evaluacion_proyecto($proy_id,3,$i); // no cumplido - prod
            if(count($ncum)!=0){
              $nro_ncum=$nro_ncum+$ncum[0]['total'];
            }

            $total_prog=$this->model_evalregional->total_programado_accion($proy_id,$i); // total programado - prod
            if(count($total_prog)!=0){
              $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
            }
            /*--------------------------------------*/
          }
        }
        
        /*--Prod */
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
        $cat[4]=$total; // Total Evaluado
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*------------------------ Reporte Eficacia por Unidades Ejecutoras ---------------*/
    public function reporte_eficacia($dist_id){
      $html = $this->eficacia_acciones($dist_id); 

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("EVALUACION.pdf", array("Attachment" => false));
    }

    /*---------------------- Eficacia Acciones ---------------------------*/
        /*--------------------------- EVALUAR OPERACIONES --------------------------------*/
    function eficacia_acciones($dist_id){
      $gestion = $this->session->userdata('gestion');
      $dist=$this->model_evalregional->get_dist($dist_id);
      $tmes=$this->model_evaluacion->trimestre();
      $nro=$this->nro_list_distrital($dist_id);
      $html = '
      <html>
        <head>' . $this->estilo_vertical() . '
         <style>
           @page { margin: 130px 20px; }
           #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
           #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 50px;}
           #footer .page:after { content: counter(page, upper-roman); }
         </style>
         <style type="text/css">
            .circulo, .ovalo {
            border: 2px solid #888888;
            margin: 2%;
            height: 55px;
            border-radius: 60%;
          }
          .circulo {
            width: 100px;      
          }
          .ovalo {
            width: 150px;
          }

          .circulo1, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 40%;
            background:#f5c9c8;
          }
          .circulo2, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#ece396;
          }
          .circulo3, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#b4dff3;
          }
          .circulo4, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#a5efa8;
          }
        </style>
        <body>
         <div id="header">
              <div class="verde"></div>
              <div class="blanco"></div>
              <table width="100%" border=0>
                  <tr>
                      <td width=15%; text-align:center;"">
                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"></center>
                      </td>
                      <td width=60%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                          <b>REGIONAL : </b> '.strtoupper($dist[0]['dep_departamento']).'<br>
                          <b>DISTRITAL : </b> '.strtoupper($dist[0]['dist_distrital']).'<br>
                          <b>EVALUACI&Oacute;N DE OPERACIONES : </b> '.strtoupper($tmes[0]['trm_descripcion']).' 
                          </FONT>
                      </td>
                      <td width=25%; text-align:center;"">
                          <div class="circulo" style="width:100%;"><br>
                            &nbsp;  <b>EVALUACI&Oacute;N TRIMESTRAL DISTRITAL</b><br>
                            &nbsp;  <b>REGIONAL :</b> '.strtoupper($dist[0]['dep_sigla']).'-'.$this->gestion.'<br>
                            &nbsp;  <b>FECHA DE IMPRESI&Oacute;N : </b>'.date('d/m/Y').'<br>
                            &nbsp;  <b>RESPONSABLE :</b> '.$this->session->userdata('funcionario').'<br>
                          </div>
                      </td>
                  </tr>
              </table>
         </div>
          <div id="footer">
            <hr>
            <table border="0" >
                <tr>
                    <td colspan=3>
                      <table border="0">
                        <tr>
                          <td style="width:25%;">
                            <div class="circulo1" style="width:100%;"><br>
                              &nbsp;<b>INSATISFACTORIO (0 a 99)% BUENO : '.$nro[1].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo2" style="width:100%;"><br>
                              &nbsp;<b>REGULAR (75 a 90)% : '.$nro[2].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo3" style="width:100%;"><br>
                              &nbsp;<b>BUENO (90 a 99)% : '.$nro[3].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo4" style="width:100%;"><br>
                              &nbsp;<b>OPTIMO 100% : '.$nro[4].' Acciones Operativas</b>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
            </table>
         </div>
         <div id="content">
           <p></p>
         </div>
       </body>
       </html>';
      return $html;
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

    /*---------------Sumatoria Temporalidad Productos ------------------*/
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

          if($producto[0]['mt_id']==3){
            $matriz[2][$i]=$pa+$producto[0]['prod_linea_base'];
          }
          else{
            $matriz[2][$i]=$matriz[1][$i];
          }

         // $matriz[2][$i]=$pa;
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
    /*===================================================================================*/
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
    function estilo_vertical(){
        $estilo_vertical = '<style>
        body{
            font-family: sans-serif;
            }
        table{
            font-size: 7px;
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
        font-size: 7px;
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