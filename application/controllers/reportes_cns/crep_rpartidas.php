<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_rpartidas extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('programacion/insumos/minsumos_delegado');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('reporte_eval/model_evalnacional');
            $this->load->model('mantenimiento/mapertura_programatica');
            $this->load->model('mantenimiento/model_ptto_sigep');

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

    /*-------- Partidas a nivel Regional -------*/
    public function partidas_regional($dep_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      if(count($data['regional'])!=0){
        
        $total_asignado=$this->model_ptto_sigep->total_partidas_regional($dep_id,1);
        $total_programado=$this->model_ptto_sigep->total_partidas_regional($dep_id,2);

        $monto_a=0;
        $monto_p=0;
        if(count($total_asignado)!=0){
          $monto_a=$total_asignado[0]['monto'];
        }

        if(count($total_programado)!=0){
          $monto_p=$total_programado[0]['monto'];
        }

        $data['total_asig']=$monto_a;
        $data['total_prog']=$monto_p;

        $data['comparativo']=$this->comparativo_partidas_regional($dep_id);
        $data['programas']=$this->programas($dep_id);
        $this->load->view('admin/reportes_cns/partidas_regional/regional/vpartidas_regional', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*--------------- Partidas A nivel Nacional -------------------*/
    public function partidas_nacional($dep_id,$tp,$tp_rep){ 
      $tabla ='';
      
      if($tp_rep==1){
        if($tp==1){
          $mon='IMPORTE'; $tb='id="dt_basic3" class="table table-bordered"';
        }
        else{
          $mon='MONTO PROG.';$tb='id="dt_basic4" class="table table-bordered"';
        }
      }
      elseif($tp_rep==2){
        if($tp==1){
          $mon='IMPORTE'; $tb='class="change_order_items" border=1';
        }
        else{
          $mon='MONTO PROG.';$tb='class="change_order_items" border=1';
        }
      }
      $partidas=$this->model_ptto_sigep->partidas_regional($dep_id,$tp);
      if(count($partidas)!=0){
        $tabla .=' <table '.$tb.'>
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>'.$mon.'</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto=0;
            foreach($partidas  as $row){
                $nro++;
                $tabla .='<tr class="modo1">
                            <td align=center>'.$nro.'</td>
                            <td align=center>'.$row['codigo'].'</td>
                            <td align=left>'.$row['nombre'].'</td>
                            <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          </tr>';
                $monto=$monto+$row['monto'];
            }
            $tabla .='</tbody>
                        <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto, 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*--------------- Comparativo Partidas A nivel Nacional -------------------*/
    public function comparativo_partidas_regional($dep_id){ 
      $partidas_asig=$this->model_ptto_sigep->partidas_regional($dep_id,1); /// asig
      $partidas_prog=$this->model_ptto_sigep->partidas_regional($dep_id,2); /// prog

      $tabla ='';
      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      //$tabla.=''.count($partidas_asig).'--'.count($partidas_prog).'<br>';
      $tabla .=' <table id="dt_basic" class="table table-bordered">
                  <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                      </tr>
                    </thead>
                    <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_regional($dep_id,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.' title="asig">
                        <td align=center>'.$row['par_id'].'--'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_regional($dep_id,$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }

      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_regional($dep_id,$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }


          $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.' title="Prog">
                        <td align=center>'.$row['par_id'].'--'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$asig;
            $monto_prog=$monto_prog+$row['monto'];
        }

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_regional($dep_id,$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.$row['nombre'].'</td>
                          <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }
      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';

      return $tabla;
    }

    /*-------------------------- Programas Institucionales ------------------*/
    public function programas($dep_id){ 
      $lista_aper_padres = $this->model_proyecto->list_prog();//lista de aperturas padres 
      $tabla ='';
      if(count($lista_aper_padres)!=0){
        $tabla .=' <table class="table table-bordered">
                    <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>PROGRAMA</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>DESCRIPCI&Oacute;N</font></th>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>';
            $nro=0;
            foreach($lista_aper_padres  as $row){
                $nro++;
                $tabla .='<tr class="modo1">
                            <td align=center>'.$nro.'</td>
                            <td align=center>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>
                            <td align=left>'.$row['aper_descripcion'].'</td>
                            <td align=center><a href="'.site_url("").'/rep/rprogramas/'.$dep_id.'/'.$row['aper_id'].'" title="INGRESAR A PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a></td>
                            <td align=center><img id="load'.$row['aper_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="30" height="30" title="CARGANDO PROGRAMA"></td>
                          </tr>
                          <script>
                            document.getElementById("myBtn'.$row['aper_id'].'").addEventListener("click", function(){
                            document.getElementById("load'.$row['aper_id'].'").style.display = "block";
                          });
                        </script>';
            }
            $tabla .='</tbody>
                    </table>';
      }

      return $tabla;
    }

    /*------------------------- Reporte Partidas ----------------------*/
    public function reporte_partidas_regional($dep_id){
        $html = $this->partidas_ptto_regional($dep_id);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 90000000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PARTIDAS.pdf", array("Attachment" => false));
    }

    function partidas_ptto_regional($dep_id){
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $gestion = $this->session->userdata('gestion');
        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -195px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL REGIONAL - '.strtoupper($regional[0]['dep_departamento']).'
                            </FONT>
                        </td>
                        <td width=20%; text-align:center;"">
                        </td>
                    </tr>
                </table>
           </div>
           <div id="footer">
            <hr>
             <table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p><div>'.$this->comparativo_partidas_regional($dep_id).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    public function reporte_excel_partidas_regional($dep_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_regional=$this->excel_partidas_regional($dep_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_NACIONAL_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_regional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Regional -------------------*/
    public function excel_partidas_regional($dep_id){
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $tabla ='';
      $tabla .='<style>
                table{
                  font-size: 9px;
                  width: 100%;
                  max-width:1550px;
                  overflow-x: scroll;
                }
                th{
                  padding: 1.4px;
                  text-align: center;
                  font-size: 10px;
                }
                </style>';

            $tabla .='<table border="1" cellpadding="0" cellspacing="0" class="tabla">';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1" bgcolor="#ddf1ee">';
                  $tabla.='<td colspan=6>';
                    $tabla.='
                      <b><FONT FACE="courier new" size="1">
                      <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                      <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                      <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL REGIONAL - '.strtoupper($regional[0]['dep_departamento']).'
                      </FONT></b>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';

      $partidas=$this->model_ptto_sigep->partidas_regional($dep_id,1);
      if(count($partidas)!=0){
        $tabla .=' <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368" style="width:1%;"><font color="#ffffff">NRO.</font></th>
                          <th bgcolor="#1c7368" style="width:5%;"><font color="#ffffff">PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:15%;"><font color="#ffffff">DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color="#ffffff">ASIGNADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color="#ffffff">PROGRAMADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color="#ffffff">DIF.</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto_asig=0;
            $monto_prog=0;
            foreach($partidas  as $row){
                $part=$this->model_ptto_sigep->get_partida_regional($dep_id,$row['par_id']);
                $prog=0;
                if(count($part)!=0){
                  $prog=$part[0]['monto'];
                }
                $dif=($row['monto']-$prog);
                $color='#f1f1f1';
                if($dif<0){
                  $color='#f9cdcd';
                }
                $nro++;
                $tabla .='<tr class="modo1" bgcolor='.$color.'>
                            <td align=center>'.$nro.'</td>
                            <td align=center>'.mb_convert_encoding($row['codigo'], 'cp1252', 'UTF-8').'</td>
                            <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                            <td align=right>'.$row['monto'].'</td>
                            <td align=right>'.$prog.'</td>
                            <td align=right>'.$dif.'</td>
                          </tr>';
                $monto_asig=$monto_asig+$row['monto'];
                $monto_prog=$monto_prog+$prog;
            }
            $tabla .='</tbody>
                      <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.$monto_asig.'</td>
                          <td align=right>'.$monto_prog.'</td>
                          <td align=right>'.($monto_asig-$monto_prog).'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*========= PARTIDAS A NIVEL DE PROGRAMAS =========*/
    public function partidas_programas($dep_id,$aper_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
      if(count($data['programa'])!=0){
        $data['regional']=$this->model_proyecto->get_departamento($dep_id);
        /*$data['partidas_asig']=$this->partidas_prog($dep_id,$data['programa'][0]['aper_programa'],1,1);
        $data['partidas_prog']=$this->partidas_prog($dep_id,$data['programa'][0]['aper_programa'],2,1);*/

        $data['total_asignado']=$this->model_ptto_sigep->Total_partidas_rprogramas($dep_id,$data['programa'][0]['aper_programa'],1);
        $data['total_programado']=$this->model_ptto_sigep->Total_partidas_rprogramas($dep_id,$data['programa'][0]['aper_programa'],2);

      // echo $data['total_asignado'][0]['monto'].'-'.$data['total_programado'][0]['monto'];
        $data['comparativo']=$this->comparativo_partidas_programas($dep_id,$data['programa'][0]['aper_programa']);
        $data['programas']=$this->get_programas($dep_id,$aper_id);
        $this->load->view('admin/reportes_cns/partidas_regional/programas/vpartidas_programas', $data);
      }
      else{
        redirect('admin/dashboard');
      }
      
    }

    /*--------------- Partidas A nivel Programas -------------------*/
    public function partidas_prog($dep_id,$prog,$tp,$tp_rep){ 
      $tabla ='';
      
      if($tp_rep==1){
        if($tp==1){
          $mon='IMPORTE'; $tb='id="dt_basic3" class="table table-bordered"';
        }
        else{
          $mon='MONTO PROG.';$tb='id="dt_basic4" class="table table-bordered"';
        }
      }
      elseif($tp_rep==2){
        if($tp==1){
          $mon='IMPORTE'; $tb='class="change_order_items" border=1';
        }
        else{
          $mon='MONTO PROG.';$tb='class="change_order_items" border=1';
        }
      }
      $partidas=$this->model_ptto_sigep->partidas_rprogramas($dep_id,$prog,$tp);
      if(count($partidas)!=0){
        $tabla .=' <table '.$tb.'>
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>'.$mon.'</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto=0;
            foreach($partidas  as $row){
                $nro++;
                $tabla .='<tr class="modo1">
                            <td align=center>'.$nro.'</td>
                            <td align=center>'.$row['codigo'].'</td>
                            <td align=left>'.$row['nombre'].'</td>
                            <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          </tr>';
                $monto=$monto+$row['monto'];
            }
            $tabla .='</tbody>
                        <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto, 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*--------------- Comparativo Partidas A nivel Programas -------------------*/
    public function comparativo_partidas_programas($dep_id,$aper_programa){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_rprogramas($dep_id,$aper_programa,1); // Asig
      $partidas_prog=$this->model_ptto_sigep->partidas_rprogramas($dep_id,$aper_programa,2); // Prog

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      //$tabla.=''.count($partidas_asig).'--'.count($partidas_prog).'<br>';
      $tabla .='<table id="dt_basic" class="table table-bordered">
                  <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                      </tr>
                    </thead>
                    <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_rprograma($dep_id,$aper_programa,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_rprograma($dep_id,$aper_programa,$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_rprograma($dep_id,$aper_programa,$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$asig;
            $monto_prog=$monto_prog+$row['monto'];
        }
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_rprograma($dep_id,$aper_programa,$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.$row['nombre'].'</td>
                          <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }
      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';
      return $tabla;
    }

    /*------------------------- Reporte Programas ----------------------*/
    public function reporte_partidas_programa($dep_id,$aper_id){
        $html = $this->partidas_ptto_programa($dep_id,$aper_id);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 90000000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PROGRAMAS_PARTIDAS.pdf", array("Attachment" => false));
    }

    function partidas_ptto_programa($dep_id,$aper_id){
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $programa=$this->model_ptto_sigep->apertura_id($aper_id);
        $gestion = $this->session->userdata('gestion');
        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -195px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                        </td>
                        <td width=90%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE PROGRAMAS - '.strtoupper($regional[0]['dep_departamento']).'<br>
                            <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'
                            </FONT>
                        </td>
                        <td width=10%; text-align:center;"">
                        </td>
                    </tr>
                </table>
           </div>
           <div id="footer">
            <hr>
             <table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p><div>'.$this->comparativo_partidas_programas($dep_id,$programa[0]['aper_programa']).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*-------------------------- Get Programas Institucionales ------------------*/
    public function get_programas($dep_id,$aper_id){ 
      $lista_aper_padres = $this->model_proyecto->list_prog();//lista de aperturas padres 
      $tabla ='';
      if(count($lista_aper_padres)!=0){
        $tabla .=' <table class="table table-bordered">
                    <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>PROGRAMA</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>DESCRIPCI&Oacute;N</font></th>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>';
            $nro=0;
            foreach($lista_aper_padres  as $row){
              $color='';
              $ahref='<a href="'.site_url("").'/rep/rprogramas/'.$dep_id.'/'.$row['aper_id'].'" title="INGRESAR A PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
              if($row['aper_id']==$aper_id){
                $color='#d5efd5';
                $ahref='';
              }
              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>
                          <td align=left>'.$row['aper_descripcion'].'</td>
                          <td align=center>'.$ahref.'</td>
                          <td align=center><img id="load'.$row['aper_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="30" height="30" title="CARGANDO PROGRAMA"></td>
                        </tr>
                        <script>
                            document.getElementById("myBtn'.$row['aper_id'].'").addEventListener("click", function(){
                            document.getElementById("load'.$row['aper_id'].'").style.display = "block";
                          });
                        </script>';
            }
            $tabla .='</tbody>
                    </table>';
      }

      return $tabla;
    }

    public function reporte_excel_partidas_programa($dep_id,$aper_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_regional=$this->excel_partidas_programa($dep_id,$aper_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_PROGRAMA_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_regional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Programa -------------------*/
    public function excel_partidas_programa($dep_id,$aper_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $tabla ='';
      $tabla .='<style>
                table{
                  font-size: 9px;
                  width: 100%;
                  max-width:1550px;
                  overflow-x: scroll;
                }
                th{
                  padding: 1.4px;
                  text-align: center;
                  font-size: 10px;
                }
                </style>';

            $tabla .='<table border="1" cellpadding="0" cellspacing="0" class="tabla">';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1" bgcolor="#ddf1ee">';
                  $tabla.='<td colspan=6>';
                    $tabla.='
                          <b><FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                          <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE PROGRAMAS - '.strtoupper($regional[0]['dep_departamento']).'<br>
                          <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.mb_convert_encoding($programa[0]['aper_descripcion'], 'cp1252', 'UTF-8').'
                          </FONT>
                      </b>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';

        $partidas_asig=$this->model_ptto_sigep->partidas_rprogramas($dep_id,$programa[0]['aper_programa'],1); // Asig
        $partidas_prog=$this->model_ptto_sigep->partidas_rprogramas($dep_id,$programa[0]['aper_programa'],2); // Prog

        $nro=0;
        $monto_asig=0;
        $monto_prog=0;
        //$tabla.=''.count($partidas_asig).'--'.count($partidas_prog).'<br>';
        $tabla .='<table id="dt_basic" class="table table-bordered">
                  <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                        <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                      </tr>
                    </thead>
                    <tbody>';

        if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_rprograma($dep_id,$programa[0]['aper_programa'],$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                        <td align=right>'.$row['monto'].'</td>
                        <td align=right>'.$prog.'</td>
                        <td align=right>'.$dif.'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_rprograma($dep_id,$programa[0]['aper_programa'],$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                        <td align=right>'.$asig.'</td>
                        <td align=right>'.$row['monto'].'</td>
                        <td align=right>'.$dif.'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_rprograma($dep_id,$programa[0]['aper_programa'],$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                        <td align=right>'.$asig.'</td>
                        <td align=right>'.$row['monto'].'</td>
                        <td align=right>'.$dif.'</td>
                      </tr>';
            $monto_asig=$monto_asig+$asig;
            $monto_prog=$monto_prog+$row['monto'];
        }
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_rprograma($dep_id,$programa[0]['aper_programa'],$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                          <td align=right>'.$row['monto'].'</td>
                          <td align=right>'.$prog.'</td>
                          <td align=right>'.$dif.'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }
      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.$monto_asig.'</td>
                      <td align=right>'.$monto_prog.'</td>
                      <td align=right>'.($monto_asig-$monto_prog).'</td>
                    </tr>
                </table>';

      return $tabla;
    }


    /*======= Partidas a nivel de Unidades Organizacionales ========*/
    public function list_acciones_operativas($dep_id,$aper_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
    
      $data['proyectos']=$this->list_programas_poa($dep_id,$aper_id,1); // proyecto de inversion
      $data['operacion']=$this->list_programas_poa($dep_id,$aper_id,4); // Operacion de funcionamiento

      $data['programas']=$this->get_programas_ue($dep_id,$aper_id); // lista de Programas

      $this->load->view('admin/reportes_cns/partidas_regional/acciones/vacciones_operativas', $data);
    }

    /*-------------------------- Lista proyectos POA ---------------------------*/
    public function list_programas_poa($dep_id,$aper_id,$tp_id){
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);

      $tabla ='';
        $unidades=$this->model_ptto_sigep->lista_unidades_region($dep_id,$programa[0]['aper_programa'],$tp_id); /// Lista de Unidades/ Proyectos de inversion
          $nro=0;
          foreach($unidades  as $row){
            $nro++;
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
           /* 
            $fase_gest = $this->model_faseetapa->fase_etapa_gestion($fase[0]['id'],$this->session->userdata("gestion"));
            $aper=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);*/
            $tabla .= '<tr height="70">';
              $tabla .= '<td align=center><img id="load'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="30" height="30" title="CARGANDO UE"></td>';
              $tabla .= '<td align=center><a href="'.site_url("").'/rep/raccion/'.$aper_id.'/'.$row['proy_id'].'" id="myBtn'.$row['proy_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a></td>';
              $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/rep/rep_raccion/'.$aper_id.'/'.$row['proy_id'].'\');" title="REPORTE CUADRO COMPARATIVO POR PARTIDAS"><img src="' . base_url() . 'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>';
              $tabla .= '<td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>';
             $tabla .= '</td>';
              if($this->gestion==2020){
                $tabla.='<td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
              }
              else{
                $tabla.='<td>'.$row['proy_nombre'].'</td>';
              }
              
              $tabla.='<td>'.$row['escalon'].'</td>';
              $tabla.='<td>'.$row['nivel'].'</td>';
              $tabla.='<td>'.$row['tipo_adm'].'</td>';
              $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
              $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
            $tabla .= '</tr>
                        <script>
                            document.getElementById("myBtn'.$row['proy_id'].'").addEventListener("click", function(){
                            document.getElementById("load'.$row['proy_id'].'").style.display = "block";
                          });
                        </script>';
          }

      return $tabla;
    }

    /*------------------ Unidad Organizacional --------------------*/
    public function partida_accion_operativa($aper_id,$proy_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $data['regional']=$this->model_proyecto->get_departamento($data['proyecto'][0]['dep_id']);
      
      $data['partidas_asig']=$this->partidas_ue($data['regional'][0]['dep_id'],$data['proyecto'][0]['aper_id'],1,1);
      $data['partidas_prog']=$this->partidas_ue($data['regional'][0]['dep_id'],$data['proyecto'][0]['aper_id'],2,1);
      $data['comparativo']=$this->comparativo_partidas_acciones($data['regional'][0]['dep_id'],$data['proyecto'][0]['aper_id']);

      $this->load->view('admin/reportes_cns/partidas_regional/acciones/vpartidas_acciones', $data);
    }

    /*--------------- Partidas A nivel Unidades Ejecutoras -------------------*/
    public function partidas_ue($dep_id,$aper_id,$tp,$tp_rep){ 
      $tabla ='';
      
      if($tp_rep==1){
        if($tp==1){
          $mon='IMPORTE'; $tb='id="dt_basic3" class="table table-bordered"';
        }
        else{
          $mon='MONTO PROG.';$tb='id="dt_basic4" class="table table-bordered"';
        }
      }
      elseif($tp_rep==2){
        if($tp==1){
          $mon='IMPORTE'; $tb='class="change_order_items" border=1';
        }
        else{
          $mon='MONTO PROG.';$tb='class="change_order_items" border=1';
        }
      }
      $partidas=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,$tp);
      if(count($partidas)!=0){
        $tabla .=' <table '.$tb.'>
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368"><font color=#fff>'.$mon.'</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto=0;
            foreach($partidas  as $row){
                $nro++;
                $tabla .='<tr class="modo1">
                            <td align=center>'.$nro.'</td>
                            <td align=center>'.$row['codigo'].'</td>
                            <td align=left>'.$row['nombre'].'</td>
                            <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          </tr>';
                $monto=$monto+$row['monto'];
            }
            $tabla .='</tbody>
                        <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto, 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*--------------- Comparativo Partidas A nivel De Acciones Operativas -------------------*/
    public function comparativo_partidas_acciones($dep_id,$aper_id){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Asig
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,2); // Prog

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $tabla .='<table id="dt_basic" class="table table-bordered">
                  <thead>
                    <tr class="modo1" align=center>
                      <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                      <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                    </tr>
                  </thead>
                  <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
          $tabla .='<tr class="modo1" bgcolor='.$color.'>
                      <td align=center>'.$nro.'</td>
                      <td align=center>'.$row['codigo'].'</td>
                      <td align=left>'.$row['nombre'].'</td>
                      <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                      <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                    </tr>';
          $monto_asig=$monto_asig+$asig;
          $monto_prog=$monto_prog+$row['monto'];
        }

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.$row['nombre'].'</td>
                          <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }

      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';

      return $tabla;
    }

    /*------------------------- Reporte Accion Operativa ----------------------*/
    public function reporte_partidas_accion($aper_id1,$proy_id){
        $html = $this->partidas_ptto_accion($aper_id1,$proy_id);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 900000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PARTIDAS_UE.pdf", array("Attachment" => false));
    }

    function partidas_ptto_accion($aper_id1,$proy_id){
        $programa=$this->model_ptto_sigep->apertura_id($aper_id1);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
        $regional=$this->model_proyecto->get_departamento($proyecto[0]['dep_id']);
        $gestion = $this->session->userdata('gestion');
        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -195px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                        </td>
                        <td width=90%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE UNIDAD ORGANIZACIONAL<br>
                            <b>REGIONAL : </b> '.strtoupper($regional[0]['dep_departamento']).'<br>
                            <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br>
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b> '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding($proyecto[0]['aper_descripcion'], 'cp1252', 'UTF-8').'
                            </FONT>
                        </td>
                        <td width=10%; text-align:center;"">
                        </td>
                    </tr>
                </table>
           </div>
           <div id="footer">
            <hr>
             <table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p><div>'.$this->comparativo_partidas_acciones($regional[0]['dep_id'],$proyecto[0]['aper_id']).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*-------------------------- Get Programas Institucionales ------------------*/
    public function get_programas_ue($dep_id,$aper_id){ 
      $lista_aper_padres = $this->model_proyecto->list_prog();//lista de aperturas padres 
      $tabla ='';
      if(count($lista_aper_padres)!=0){
        $tabla .=' <table class="table table-bordered">
                    <thead>
                      <tr class="modo1" align=center>
                        <th bgcolor="#1c7368"><font color=#fff>NRO.</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>PROGRAMA</font></th>
                        <th bgcolor="#1c7368"><font color=#fff>DESCRIPCI&Oacute;N</font></th>
                        <th bgcolor="#1c7368"></th>
                        <th bgcolor="#1c7368"></th>
                      </tr>
                    </thead>
                    <tbody>';
            $nro=0;
            foreach($lista_aper_padres  as $row){
              $color='';
              $ahref='<a href="'.site_url("").'/rep/racciones_operativas/'.$dep_id.'/'.$row['aper_id'].'" title="INGRESAR A ACCIONES OPERATIVAS DEL PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
              if($row['aper_id']==$aper_id){
                $color='#d5efd5';
                $ahref='<a href="'.site_url("").'/rep/rprogramas/'.$dep_id.'/'.$row['aper_id'].'" title="CUADRO COMPARATIVO POR PARTIDAS DEL PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
              }
              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>
                          <td align=left>'.$row['aper_descripcion'].'</td>
                          <td align=center>'.$ahref.'</td>
                          <td align=center><img id="load'.$row['aper_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="30" height="30" title="CARGANDO PROGRAMA"></td>
                        </tr>
                        <script>
                            document.getElementById("myBtn'.$row['aper_id'].'").addEventListener("click", function(){
                            document.getElementById("load'.$row['aper_id'].'").style.display = "block";
                          });
                        </script>';
            }
            $tabla .='</tbody>
                    </table>';
      }

      return $tabla;
    }

    public function reporte_excel_partidas_accion($aper_id,$proy_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_nacional=$this->excel_partidas_accion($aper_id,$proy_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_UNIDAD_ORGANIZACIONAL_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_nacional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Nacional -------------------*/
    public function excel_partidas_accion($aper_id,$proy_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $regional=$this->model_proyecto->get_departamento($proyecto[0]['dep_id']);
      $tabla ='';
      $tabla .='<style>
                table{
                  font-size: 9px;
                  width: 100%;
                  max-width:1550px;
                  overflow-x: scroll;
                }
                th{
                  padding: 1.4px;
                  text-align: center;
                  font-size: 10px;
                }
                </style>';

            $tabla .='<table border="1" cellpadding="0" cellspacing="0" class="tabla">';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1" bgcolor="#ddf1ee">';
                  $tabla.='<td colspan=6>';
                    $tabla.='
                          <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE UNIDAD ORGANIZACIONAL<br>
                            <b>REGIONAL : </b> '.strtoupper($regional[0]['dep_departamento']).'<br>
                            <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.mb_convert_encoding($programa[0]['aper_descripcion'], 'cp1252', 'UTF-8').'<br>
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b> '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding($proyecto[0]['aper_descripcion'], 'cp1252', 'UTF-8').'
                          </FONT>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';

      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($regional[0]['dep_id'],$proyecto[0]['aper_id'],1);
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($regional[0]['dep_id'],$proyecto[0]['aper_id'],2);

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $tabla .='<table id="dt_basic" class="table table-bordered">
                  <thead>
                    <tr class="modo1" align=center>
                      <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                      <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                    </tr>
                  </thead>
                  <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($regional[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                        <td align=right>'.$row['monto'].'</td>
                        <td align=right>'.$prog.'</td>
                        <td align=right>'.$dif.'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_accion($regional[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                        <td align=right>'.$asig.'</td>
                        <td align=right>'.$row['monto'].'</td>
                        <td align=right>'.$dif.'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_accion($regional[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
          $tabla .='<tr class="modo1" bgcolor='.$color.'>
                      <td align=center>'.$nro.'</td>
                      <td align=center>'.$row['codigo'].'</td>
                      <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                      <td align=right>'.$asig.'</td>
                      <td align=right>'.round($row['monto'],2).'</td>
                      <td align=right>'.round($dif,2).'</td>
                    </tr>';
          $monto_asig=$monto_asig+$asig;
          $monto_prog=$monto_prog+$row['monto'];
        }

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($regional[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                          <td align=right>'.round($row['monto'],2).'</td>
                          <td align=right>'.round($prog,2).'</td>
                          <td align=right>'.round($dif,2).'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }

      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.round($monto_asig,2).'</td>
                      <td align=right>'.round($monto_prog,2).'</td>
                      <td align=right>'.round(($monto_asig-$monto_prog),2).'</td>
                    </tr>
                </table>';

      return $tabla;
    }

    public function reporte_excel_partidas_ue($dep_id,$aper_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_uejec=$this->partidas_aope($dep_id,$aper_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_UE_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_uejec."";
    }

    /*------------------ Reporte Excel Partidas Unidad Ejecutora -------------------*/
    public function partidas_aope($dep_id,$aper_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);
      $ope_fort=$this->model_ptto_sigep->acciones_operativas_region($dep_id,$programa[0]['aper_programa'],4);
      $ope_fun=$this->model_ptto_sigep->acciones_operativas_region($dep_id,$programa[0]['aper_programa'],3);
      $proy_inv=$this->model_ptto_sigep->acciones_operativas_region($dep_id,$programa[0]['aper_programa'],1);

      $tabla ='';
      $tabla .='<style>
                table{
                  font-size: 9px;
                  width: 100%;
                  max-width:1550px;
                  overflow-x: scroll;
                }
                th{
                  padding: 1.4px;
                  text-align: center;
                  font-size: 10px;
                }
                </style>';

      $tabla .='<font size=3><center>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.mb_convert_encoding($programa[0]['aper_descripcion'], 'cp1252', 'UTF-8').'</center></font>';
      if(count($ope_fort)!=0){
        $nro_ofort=0;
        $tabla .='OPERACI&Oacute;N DE FORTALECIMIENTO<hr>';
        foreach($ope_fort  as $row){
          $nro_ofort++;
          $tabla .='<div>'.$nro_ofort.'.- '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.$row['proy_nombre'].'</div>';
          $partidas=$this->model_ptto_sigep->partidas_accion_region($dep_id,$row['aper_id'],2);
          if(count($partidas)!=0){
            $tabla .=' <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                        <thead>
                            <tr class="modo1" align=center>
                              <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                              <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:30%;" colspan="5"><font color=#fff>DETALLE PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>PROGRAMADO</font></th>
                            </tr>
                          </thead>
                          <tbody>';
                $nro=0;
                $monto=0;
                foreach($partidas  as $row){
                    $nro++;
                    $tabla .='<tr class="modo1">
                                <td align=center style="height:20%;">'.$nro.'</td>
                                <td align=center style="height:20%;">'.$row['codigo'].'</td>
                                <td align=left style="height:20%;" colspan="5">'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                                <td align=right style="height:20%;">'.$row['monto'].'</td>
                              </tr>';
                    $monto=$monto+$row['monto'];
                }
                $tabla .='</tbody>
                            <tr class="modo1">
                              <td colspan=7 style="height:20%;"><strong>TOTAL</strong></td>
                              <td align=right style="height:20%;">'.$monto.'</td>
                            </tr>
                        </table>';
          }
          $tabla .='<br>';
        }
      }

      if(count($ope_fun)!=0){
        $nro_ofun=0;
        $tabla .='OPERACI&Oacute;N DE FUNCIONAMIENTO<hr>';
        foreach($ope_fun  as $row){
          $nro_pi++;
          $tabla .='<div>'.$nro_ofun.'.- '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.mb_convert_encoding($row['proy_nombre'], 'cp1252', 'UTF-8').'</div>';
          $partidas=$this->model_ptto_sigep->partidas_accion_region($dep_id,$row['aper_id'],2);
          if(count($partidas)!=0){
            $tabla .=' <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                        <thead>
                            <tr class="modo1" align=center>
                              <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                              <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:30%;" colspan="5"><font color=#fff>DETALLE PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>PROGRAMADO</font></th>
                            </tr>
                          </thead>
                          <tbody>';
                $nro=0;
                $monto=0;
                foreach($partidas  as $row){
                    $nro++;
                    $tabla .='<tr class="modo1">
                                <td align=center style="height:20%;">'.$nro.'</td>
                                <td align=center style="height:20%;">'.$row['codigo'].'</td>
                                <td align=left style="height:20%;" colspan="5">'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                                <td align=right style="height:20%;">'.$row['monto'].'</td>
                              </tr>';
                    $monto=$monto+$row['monto'];
                }
                $tabla .='</tbody>
                            <tr class="modo1">
                              <td colspan=7 style="height:20%;"><strong>TOTAL</strong></td>
                              <td align=right style="height:20%;">'.$monto.'</td>
                            </tr>
                        </table>';
          }
          $tabla .='<br>';
        }
      }

      if(count($proy_inv)!=0){
        $nro_pi=0;
        $tabla .='PROYECTO DE INVERSI&Oacute;N<hr>';
        foreach($proy_inv  as $row){
          $nro_pi++;
          $tabla .='<div>'.$nro_pi.'.- '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.$row['proy_nombre'].'</div>';
          $partidas=$this->model_ptto_sigep->partidas_accion_region($dep_id,$row['aper_id'],2);
          if(count($partidas)!=0){
            $tabla .=' <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                        <thead>
                            <tr class="modo1" align=center>
                              <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                              <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:30%;" colspan="5"><font color=#fff>DETALLE PARTIDA</font></th>
                              <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>PROGRAMADO</font></th>
                            </tr>
                          </thead>
                          <tbody>';
                $nro=0;
                $monto=0;
                foreach($partidas  as $row){
                    $nro++;
                    $tabla .='<tr class="modo1">
                                <td align=center style="height:20%;">'.$nro.'</td>
                                <td align=center style="height:20%;">'.$row['codigo'].'</td>
                                <td align=left style="height:20%;" colspan="5">'.mb_convert_encoding($row['nombre'], 'cp1252', 'UTF-8').'</td>
                                <td align=right style="height:20%;">'.$row['monto'].'</td>
                              </tr>';
                    $monto=$monto+$row['monto'];
                }
                $tabla .='</tbody>
                            <tr class="modo1">
                              <td colspan=7 style="height:20%;"><strong>TOTAL</strong></td>
                              <td align=right style="height:20%;">'.$monto.'</td>
                            </tr>
                        </table>';
          }
          $tabla .='<br>';
        }
      }

      return $tabla;
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
        padding: 2px;
        border-right-width: 2px;
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