<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_partidas extends CI_Controller {  
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

    /*------------------ Update Partidas a nivel Institucional --------------------*/
    public function partidas_institucional_upadte(){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $acciones=$this->model_ptto_sigep->list_acciones_operativas();
      $nro=0;
      foreach($acciones  as $row){
        $nro++;
        echo "".$nro.".- APER ID : ".$row['aper_id']." - PROY ID : ".$row['proy_id']." - PROY ACT : ".$row['proy_act']." - EJECUCION :".$row['pfec_ejecucion']."<br>";
        
        if($row['proy_act']==1){
          if ($row['pfec_ejecucion']== 1) {
              $req=$this->minsumos->insumo_actividad($row['proy_id']);
          }
          else{
              $req=$this->minsumos_delegado->insumo_componente($row['proy_id']);
          }
        }
        elseif ($row['proy_act']==0) {
              $req=$this->minsumos->insumo_producto($row['proy_id']);
        }


          foreach($req  as $rowr){
            $update_insumo = array(
              'aper_id' => $row['aper_id'],
            );
            $this->db->where('ins_id', $rowr['ins_id']);
            $this->db->update('insumos', $update_insumo);
          }
      }
    //  $this->load->view('admin/reportes_cns/partidas/nacional/vpartidas_nacional', $data);
    }

    /*------------------ Partidas a nivel Institucional --------------------*/
    public function partidas_institucional(){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['partidas_asig']=$this->partidas_nacional(1,1);
      $data['partidas_prog']=$this->partidas_nacional(2,1);
      $data['comparativo']=$this->comparativo_partidas_nacional();
      $data['programas']=$this->programas();
      $this->load->view('admin/reportes_cns/partidas/nacional/vpartidas_nacional', $data);
    }

    /*--------------- Partidas A nivel Nacional -------------------*/
    public function partidas_nacional($tp,$tp_rep){ 
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
      $partidas=$this->model_ptto_sigep->partidas_nacional($tp);
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
    public function comparativo_partidas_nacional(){ 
      $tabla ='';
      $partidas=$this->model_ptto_sigep->partidas_nacional(1);
      if(count($partidas)!=0){
        $tabla .=' <table id="dt_basic" class="table table-bordered">
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>ASIGNADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PROGRAMADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>DIF.</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto_asig=0;
            $monto_prog=0;
            foreach($partidas  as $row){
                $part=$this->model_ptto_sigep->get_partida_nacional($row['par_id']);
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
            $tabla .='</tbody>
                      <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                          <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*-------------------------- Programas Institucionales ------------------*/
    public function programas(){ 
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
                            <td align=center><a href="'.site_url("").'/rep/programas/'.$row['aper_id'].'" title="INGRESAR A PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a></td>
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
    public function reporte_partidas_institucional(){
        $html = $this->partidas_ptto_nacional();

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 90000000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PARTIDAS.pdf", array("Attachment" => false));
    }

    function partidas_ptto_nacional(){
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
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL INSTITUCIONAL NACIONAL
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
             <p><div>'.$this->comparativo_partidas_nacional().'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    public function reporte_excel_partidas_institucional(){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_nacional=$this->excel_partidas_nacional();

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_NACIONAL_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_nacional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Nacional -------------------*/
    public function excel_partidas_nacional(){ 
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
                      <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL INSTITUCIONAL NACIONAL
                      </FONT></b>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';

      $partidas=$this->model_ptto_sigep->partidas_nacional(1);
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
                $part=$this->model_ptto_sigep->get_partida_nacional($row['par_id']);
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

    /*============================== PARTIDAS A NIVEL DE PROGRAMAS ===========================*/
    public function partidas_programas($aper_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
      $data['partidas_asig']=$this->partidas_prog($data['programa'][0]['aper_programa'],1,1);
      $data['partidas_prog']=$this->partidas_prog($data['programa'][0]['aper_programa'],2,1);

      $data['comparativo']=$this->comparativo_partidas_programas($data['programa'][0]['aper_programa']);
      $data['programas']=$this->get_programas($aper_id);
      $this->load->view('admin/reportes_cns/partidas/programas/vpartidas_programas', $data);
    }

    /*--------------- Partidas A nivel Programas -------------------*/
    public function partidas_prog($prog,$tp,$tp_rep){ 
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
      $partidas=$this->model_ptto_sigep->partidas_programas($prog,$tp);
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
    public function comparativo_partidas_programas($aper_programa){ 
      $tabla ='';
      $partidas=$this->model_ptto_sigep->partidas_programas($aper_programa,1);
      if(count($partidas)!=0){
        $tabla .=' <table id="dt_basic" class="table table-bordered">
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>ASIGNADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PROGRAMADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>DIF.</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto_asig=0;
            $monto_prog=0;
            foreach($partidas  as $row){
                $part=$this->model_ptto_sigep->get_partida_programa($aper_programa,$row['par_id']);
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
            $tabla .='</tbody>
                        <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                          <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

      return $tabla;
    }

    /*------------------------- Reporte Programas ----------------------*/
    public function reporte_partidas_programa($aper_id){
        $html = $this->partidas_ptto_programa($aper_id);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 90000000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PARTIDAS.pdf", array("Attachment" => false));
    }

    function partidas_ptto_programa($aper_id){
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
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE PROGRAMAS<br>
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
             <p><div>'.$this->comparativo_partidas_programas($programa[0]['aper_programa']).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*-------------------------- Get Programas Institucionales ------------------*/
    public function get_programas($aper_id){ 
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
              $ahref='<a href="'.site_url("").'/rep/programas/'.$row['aper_id'].'" title="INGRESAR A PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
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

    public function reporte_excel_partidas_programa($aper_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_nacional=$this->excel_partidas_programa($aper_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_PROGRAMA_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_nacional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Programa -------------------*/
    public function excel_partidas_programa($aper_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id); 
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
                          <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE PROGRAMAS<br>
                          <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'
                          </FONT>
                      </b>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';

          $partidas=$this->model_ptto_sigep->partidas_programas($programa[0]['aper_programa'],1);
          if(count($partidas)!=0){
            $tabla .=' <table id="dt_basic" class="table table-bordered">
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
                    $part=$this->model_ptto_sigep->get_partida_programa($programa[0]['aper_programa'],$row['par_id']);
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

    /*========== Partidas a nivel de Unidades Ejecutoras ==========*/
    public function list_acciones_operativas($aper_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
    
      $data['proyectos']=$this->list_programas_poa($aper_id,1);
      $data['programas']=$this->list_programas_poa($aper_id,2);
      $data['fortalecimiento']=$this->list_programas_poa($aper_id,3);
      $data['operacion']=$this->list_programas_poa($aper_id,4);

      $data['programas']=$this->get_programas_ue($aper_id);

      $this->load->view('admin/reportes_cns/partidas/acciones/vacciones_operativas', $data);
    }

    /*-------------------------- Lista proyectos POA ---------------------------*/
    public function list_programas_poa($aper_id,$tp_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);

      $tabla ='';
        $proyectos=$this->model_ptto_sigep->acciones_operativas($programa[0]['aper_programa'],$tp_id);
          $nro=0;
          foreach($proyectos  as $row){
            $nro++;
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $fase_gest = $this->model_faseetapa->fase_etapa_gestion($fase[0]['id'],$this->session->userdata("gestion"));
            $aper=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);
            $tabla .= '<tr height="70">';
              $tabla .= '<td align=center><img id="load'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="30" height="30" title="CARGANDO UE"></td>';
              $tabla .= '<td align=center><a href="'.site_url("").'/rep/accion/'.$aper_id.'/'.$row['proy_id'].'" id="myBtn'.$row['proy_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a></td>';
              $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/rep/rep_accion/'.$aper_id.'/'.$row['proy_id'].'\');" title="REPORTE CUADRO COMPARATIVO POR PARTIDAS"><img src="' . base_url() . 'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>';
              $tabla .= '<td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>';
              $tabla .= '<td>'.$row['proy_nombre'].'</td>';
              $tabla .= '<td>'.$row['tp_tipo'].'</td>';
              $tabla .= '<td>'.$row['proy_sisin'].'</td>';
              $tabla .= '<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
              $tabla .= '<td>'.$row['ue'].'</td>';
              $tabla .= '<td>'.$row['ur'].'</td>';

                $nc=$this->model_faseetapa->calcula_nc($fase[0]['pfec_fecha_inicio']); //// calcula nuevo/continuo
                $ap=$this->model_faseetapa->calcula_ap($fase[0]['pfec_fecha_inicio'],$fase[0]['pfec_fecha_fin']);
                $nro_fg_act = $this->model_faseetapa->nro_fasegestion_actual($fase[0]['id'],$this->session->userdata('gestion'));
                $fase_gest = $this->model_faseetapa->fase_etapa_gestion($fase[0]['id'],$this->session->userdata("gestion"));
                $tabla .='<td>'.$fase[0]['descripcion'].'</td>';
                $tabla .='<td>* '.$fase[0]['fase'].'<br>* '.$fase[0]['etapa'].'</td>';
                $tabla .='<td>'.$nc.'</td>';
                $tabla .='<td>'.$ap.'</td>';
                $tabla .='<td>'.number_format($fase[0]['pfec_ptto_fase'], 2, ',', '.').' Bs.</td>';
                $tabla .='<td>';
                if($nro_fg_act!=0 && ($fase_gest[0]['estado']==1 || $fase_gest[0]['estado']==2)){
                  $tabla .=''.number_format($fase_gest[0]['pfecg_ppto_total'], 2, ',', '.').' Bs.';
                }
                elseif ($nro_fg_act==0) {
                  $tabla .= '<font color="red">la gestion no esta en curso</font>';
                }
                $tabla .='</td>';
                $tabla .='<td>';
                if($this->model_faseetapa->verif_fase_etapa_gestion($fase[0]['id'],$this->session->userdata("gestion"))!=0){ 
                  if($this->model_faseetapa->nro_ffofet($fase_gest[0]['ptofecg_id'])!=0){
                    $techo=$this->model_faseetapa->techo_presupuestario($fase_gest[0]['ptofecg_id']);
                    $tabla .= ''.number_format($techo[0]['suma_techo'], 2, ',', '.').' Bs.';
                  }
                  else{$tabla .= "<font color=red>S/T</font>";}
                }
                $tabla .='</td>';
            $tabla .= '</tr>
                        <script>
                            document.getElementById("myBtn'.$row['proy_id'].'").addEventListener("click", function(){
                            document.getElementById("load'.$row['proy_id'].'").style.display = "block";
                          });
                        </script>';
          }

      return $tabla;
    }

    /*------------------ Accion Operativa --------------------*/
    public function partida_accion_operativa($aper_id,$proy_id){ 
      $data['menu']=$this->menu(7);
      $data['resp']=$this->session->userdata('funcionario');
      $data['programa']=$this->model_ptto_sigep->apertura_id($aper_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      
      $data['partidas_asig']=$this->partidas_ue($data['proyecto'][0]['aper_id'],1,1);
      $data['partidas_prog']=$this->partidas_ue($data['proyecto'][0]['aper_id'],2,1);
      $data['comparativo']=$this->comparativo_partidas_acciones($data['proyecto'][0]['aper_id']);

      $this->load->view('admin/reportes_cns/partidas/acciones/vpartidas_acciones', $data);
    }

    /*--------------- Partidas A nivel Unidades Ejecutoras -------------------*/
    public function partidas_ue($aper_id,$tp,$tp_rep){ 
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
      $partidas=$this->model_ptto_sigep->partidas_accion($aper_id,$tp);
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
    public function comparativo_partidas_acciones($aper_id){ 
      $tabla ='';
      $partidas=$this->model_ptto_sigep->partidas_accion($aper_id,1);
      if(count($partidas)!=0){
        $tabla .=' <table id="dt_basic" class="table table-bordered">
                    <thead>
                        <tr class="modo1" align=center>
                          <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                          <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>ASIGNADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PROGRAMADO</font></th>
                          <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>DIF.</font></th>
                        </tr>
                      </thead>
                      <tbody>';
            $nro=0;
            $monto_asig=0;
            $monto_prog=0;
            foreach($partidas  as $row){
                $part=$this->model_ptto_sigep->get_partida_accion($aper_id,$row['par_id']);
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
            $tabla .='</tbody>
                        <tr class="modo1">
                          <td colspan=3><strong>TOTAL</strong></td>
                          <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                          <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                        </tr>
                    </table>';
      }

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
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE ACCI&Oacute;N OPERATIVA<br>
                            <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br>
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b> '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['aper_descripcion'].'
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
             <p><div>'.$this->comparativo_partidas_acciones($proyecto[0]['aper_id']).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*-------------------------- Get Programas Institucionales ------------------*/
    public function get_programas_ue($aper_id){ 
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
              $ahref='<a href="'.site_url("").'/rep/acciones_operativas/'.$row['aper_id'].'" title="INGRESAR A ACCIONES OPERATIVAS DEL PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
              if($row['aper_id']==$aper_id){
                $color='#d5efd5';
                $ahref='<a href="'.site_url("").'/rep/programas/'.$row['aper_id'].'" title="CUADRO COMPARATIVO POR PARTIDAS DEL PROGRAMA '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'" id="myBtn'.$row['aper_id'].'"><img src="' . base_url() . 'assets/img/folder3.png" WIDTH="35" HEIGHT="35"/></a>';
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
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_PROGRAMA_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_nacional."";
    }

    /*--------------- Excel Comparativo Partidas A nivel Nacional -------------------*/
    public function excel_partidas_accion($aper_id,$proy_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
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
                            <b>REPORTE : </b> CUADRO COMPARATIVO POR PARTIDAS A NIVEL DE ACCI&Oacute;N OPERATIVA<br>
                            <b>PROGRAMA : </b> '.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'<br>
                            <b>'.strtoupper($proyecto[0]['tipo']).' : </b> '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['aper_descripcion'].'
                          </FONT>';
                  $tabla.='</td>';
            $tabla.='</tr>';
            $tabla.='<tr class="modo1">';
                $tabla.='<td colspan=6></td>';
            $tabla.='</tr>';
            $tabla.='</table>';


      $partidas=$this->model_ptto_sigep->partidas_accion($proyecto[0]['aper_id'],1);
      if(count($partidas)!=0){
        $tabla .=' <table id="dt_basic" class="table table-bordered">
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
                $part=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$row['par_id']);
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

    public function reporte_excel_partidas_ue($aper_id){
      date_default_timezone_set('America/Lima');

      $fecha = date("d-m-Y H:i:s");
      $partida_uejec=$this->partidas_aope($aper_id);

      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Reporte_PARTIDAS_UE_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$partida_uejec."";
    }

    /*------------------ Reporte Excel Partidas Unidad Ejecutora -------------------*/
    public function partidas_aope($aper_id){
      $programa=$this->model_ptto_sigep->apertura_id($aper_id);
      $ope_fort=$this->model_ptto_sigep->acciones_operativas($programa[0]['aper_programa'],4);
      $ope_fun=$this->model_ptto_sigep->acciones_operativas($programa[0]['aper_programa'],3);
      $proy_inv=$this->model_ptto_sigep->acciones_operativas($programa[0]['aper_programa'],1);

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

      $tabla .='<font size=3><center>'.$programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</center></font>';
      if(count($ope_fort)!=0){
        $nro_ofort=0;
        $tabla .='OPERACI&Oacute;N DE FORTALECIMIENTO<hr>';
        foreach($ope_fort  as $row){
          $nro_pi++;
          $tabla .='<div>'.$nro_ofort.'.- '.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].' - '.$row['proy_nombre'].'</div>';
          $partidas=$this->model_ptto_sigep->partidas_accion($row['aper_id'],2);
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
          $partidas=$this->model_ptto_sigep->partidas_accion($row['aper_id'],2);
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
          $partidas=$this->model_ptto_sigep->partidas_accion($row['aper_id'],2);
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