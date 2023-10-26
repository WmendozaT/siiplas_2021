<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cptto_poa extends CI_Controller {
    public $rol = array('1' => '1');
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
                $this->load->library('pdf');
                $this->load->library('pdf2');
                $this->load->model('Users_model','',true);
                $this->load->model('menu_modelo');
                $this->load->model('mantenimiento/model_configuracion');
                $this->load->model('mantenimiento/model_ptto_sigep');
                $this->load->model('programacion/model_proyecto');
                $this->load->model('programacion/model_faseetapa');
                $this->load->model('programacion/insumos/minsumos');
                $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
                $this->load->model('mantenimiento/model_partidas');
                $this->load->model('reporte_eval/model_evalregional');
                $this->load->model('ejecucion/model_ejecucion');
                $this->load->library("security");
                $this->gestion = $this->session->userData('gestion');
                $this->rol = $this->session->userData('rol');
                $this->fun_id = $this->session->userData('fun_id');
                $this->tp_adm = $this->session->userData('tp_adm');
                //$this->ppto_poa = $this->session->userData('verif_ppto');
                $this->modulos = $this->session->userData('modulos');
                $this->verif_ppto = $this->session->userData('verif_ppto'); /// AnteProyecto Ptto POA : 0, Ptto Aprobado Sigep : 1
            }
            else{
                redirect('admin/dashboard');
            }
        }
        else{
                redirect('/','refresh');
        }
    }

    /*------------ Lista de Unidades Organizacionales -----------*/
    public function list_acciones_operativas(){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      
      //$data['mod2']=count($this->model_configuracion->verif_modulo(2));

      //$sw=$this->ppto_poa;
      

      //// Asignacion Poa Presupuesto Inicial
      if($this->verif_ppto==0){
        $data['proyectos']=$this->list_pinversion(1); /// Proyecto de Inversion Aprobado
        $data['operacion']=$this->list_unidades_es(1);  /// Gasto corriente Aprobado
        $this->load->view('admin/mantenimiento/ptto_sigep/vlist_ope', $data);
      }
      //// Re-Asignacion Poa Presupuesto Final (Aprobado)
      else{
        $data['proyectos']=$this->list_pinversion(4); /// Proyecto de Inversion Aprobado
        $data['operacion']=$this->list_unidades_es(4);  /// Gasto corriente Aprobado
        $data['regionales']=$this->model_ptto_sigep->list_regionales();
        $this->load->view('admin/mantenimiento/ptto_sigep/reajustado_ptto', $data);
      }
      
/*      $partidas=$this->model_insumo->lista_consolidado_ejecucion_partidas(10);
      $tabla='';
       $tabla.='
          <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:50%;">
            <thead>
              <tr style="height:50px;">
                <th style="width:1%;" bgcolor="#474544" title="">#</th>
                <th style="width:5%;" bgcolor="#474544" title="">PARTIDA</th>
                <th style="width:5%;" bgcolor="#474544" title="VER PPTO">PPTO. ASIGNADO POA</th>
                <th style="width:5%;" bgcolor="#474544" title="DIRECCION ADMINISTRATIVA">PPTO. CERTIFICADO</th>
                <th style="width:5%;" bgcolor="#474544" title="UNIDAD EJECUTORA">(%) EJECUCION POA</th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($partidas as $row){
              $nro++;
              $tabla.='
              <tr>
                <td>'.$nro.'</td>
                <td>'.$row['par_codigo'].'</td>
                <td>'.$row['programado'].'</td>
                <td>'.$row['certificado'].'</td>
                <td>'.$row['round'].' %</td>
              </tr>';
            }
            $tabla.='
            </body>
            </table>';*/

            //echo $tabla;

      //$data['regionales']=$this->model_ptto_sigep->list_regionales();
      //$this->load->view('admin/mantenimiento/ptto_sigep/reajustado_ptto', $data);

      /// CARGAR PPTO INICIAL
     // echo $this->verif_ppto;
      //$this->load->view('admin/mantenimiento/ptto_sigep/vlist_ope', $data);
    }



    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_unidades_es($estado_ppto){
      $unidades=$this->model_proyecto->list_gasto_corriente();
      $tabla='';
        
      $color='';  
      if($estado_ppto==1){
        $color='#e2f4f9';
      }

      $tabla.='
          <table id="dt_basic3" class="table1 table-bordered" style="width:100%;">
            <thead>
              <tr style="height:50px;">
                <th style="width:1%;" bgcolor="#474544" title="">#</th>
                <th style="width:5%;" bgcolor="#474544" title=""></th>
                <th style="width:5%;" bgcolor="#474544" title="VER PPTO">VER PARTIDAS</th>
                <th style="width:5%;" bgcolor="#474544" title="DIRECCION ADMINISTRATIVA">D.A.</th>
                <th style="width:5%;" bgcolor="#474544" title="UNIDAD EJECUTORA">U.E.</th>
                <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">PROGRAMA '.$this->gestion.'</th>
                <th style="width:25%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">DESCRIPCI&Oacute;N</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
              foreach($unidades as $row){
                $aper=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);
                $nro++;
                $tabla.='<tr bgcolor='.$color.'>';
                  $tabla.='<td style="height:30px;" align=center>'.$nro.'</td>';
                  $tabla.='<td>';
                    if($estado_ppto==0){
                      if(count($aper)!=0){
                          $tabla .='
                          <center><a data-toggle="modal" data-target="#'.$row['aper_id'].'" title="PARTIDAS ASIGNADAS" ><img src="'.base_url().'assets/img/select.png" WIDTH="35" HEIGHT="35"/></a></center>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$row['aper_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                    <h4 class="modal-title">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</h4>
                                  </div>
                                  <div class="modal-body no-padding">
                                    <div class="well">
                                    '.$this->partidas($row['aper_id'],1).'  
                                    </div>
                                  </div>
                                    <div class="modal-footer">
                                        <a href="javascript:abreVentana(\''.site_url("").'/mnt/rep_partidas/'.$row['aper_id'].'\');" class="btn btn-primary" title="IMPRIMIR PARTIDAS">IMPRIMIR PARTIDAS</a>
                                    </div>
                                </div>
                              </div>
                            </div>';
                      }
                    }
                  $tabla.='</td>';
                  $tabla.='<td>';
                    if($this->tp_adm==1){
                      if($estado_ppto==0){
                        if(count($aper)!=0){
                          $tabla .='<center><a href="'.site_url("").'/mnt/edit_ptto_asig/'.$row['proy_id'].'" title="MODIFICAR PRESUPUESTO ASIGNADO" class="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center>';
                        }
                      }
                      else{
                        $tabla.='<center><a href="'.site_url("").'/mnt/ver_ptto_asig_final/'.$row['proy_id'].'" id="myBtnn'.$row['proy_id'].'" title="VER PRESUPUESTO ASIGNADO INICIAL - PROGRAMADO - APROBADO" iclass="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center>';
                      }
                    }
                  $tabla.='</td>';
                  $tabla.='<td align=center><b>'.$row['da'].'</b></td>';
                  $tabla.='<td align=center><b>'.$row['ue'].'</b></td>';
                  $tabla.='<td><center>'.$row['prog'].' '.$row['proy'].' '.$row['act'].'</center></td>';
                  $tabla.='<td style="font-size: 8pt;"><b>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</b></td>';
                  $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
                  $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
                $tabla.='</tr>';
              }
            $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }


    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($estado_ppto){
      if($estado_ppto==1){
        $proyectos=$this->model_proyecto->list_poa_general(1);

      }
      else{
        $proyectos=$this->model_proyecto->list_proy_inversion();
      }

      $tabla='';

      $color='';  
      if($estado_ppto==1){
        $color='#e2f4f9';
      }

      $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
            <thead>
              <tr style="height:50px;">
                <th style="width:1%;" bgcolor="#474544" title="#">APER ID</th>
                <th style="width:5%;" bgcolor="#474544" title="VER PARTIDAS"></th>
                <th style="width:5%;" bgcolor="#474544" title="VER PARTIDAS">VER PARTIDAS</th>
                <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
                <th style="width:20%;" bgcolor="#474544" title="NOMBRE DEL PROYECTO DE INVERSI&Oacute;N">NOMBRE_PROYECTO_INVERSI&Oacute;N</th>
                <th style="width:10%;" bgcolor="#474544" title="C&Oacute;DIGO SISIN">C&Oacute;DIGO_SISIN</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD_ADMINISTRATIVA</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD_EJECUTORA</th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($proyectos as $row){

              if($estado_ppto==4){ // ppto final
                $nombre=$row['proyecto'];
                $codigo_sisin=$row['proy'];
              }
              else{ /// ppto inicial
                $nombre=$row['proy_nombre'];
                $codigo_sisin=$row['proy_sisin'];
              }

              $aper=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);
              $tabla.='<tr bgcolor='.$color.'>';
              $tabla.='<td style="height:30px;" align=center><b>'.$row['aper_id'].'</b></td>';
              $tabla.='<td align=center>';
                if($row['pfec_estado']==1){
                  if($estado_ppto==0){
                    if(count($aper)!=0){
                        $tabla .='
                        <center><a data-toggle="modal" data-target="#'.$row['aper_id'].'" title="PARTIDAS ASIGNADAS" ><img src="'.base_url().'assets/img/select.png" WIDTH="35" HEIGHT="35"/></a></center>
                          <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$row['aper_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">
                                  '.$row['proy_nombre'].'
                                  </h4>
                                </div>
                                <div class="modal-body no-padding">
                                  <div class="well">
                                  '.$this->partidas($row['aper_id'],1).'  
                                  </div>
                                </div>
                                  <div class="modal-footer">
                                      <a href="javascript:abreVentana(\''.site_url("").'/mnt/rep_partidas/'.$row['aper_id'].'\');" class="btn btn-primary" title="IMPRIMIR PARTIDAS">IMPRIMIR PARTIDAS</a>
                                  </div>
                              </div>
                            </div>
                          </div>';
                    }
                  }
                }
                else{
                  $tabla.='<b>FASE NO ACTIVA</b>';
                }
              $tabla.='</td>';
              $tabla.='<td>';
                if($this->tp_adm==1 & $row['pfec_estado']==1){

                  if($estado_ppto==0){
                    if(count($aper)!=0){
                      $tabla .='<center><a href="'.site_url("").'/mnt/edit_ptto_asig/'.$row['proy_id'].'" title="MODIFICAR PRESUPUESTO ASIGNADO" class="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center>';
                    }
                  }
                  else{
                    $tabla.='<center><a href="'.site_url("").'/mnt/ver_ptto_asig_final/'.$row['proy_id'].'" id="myBtnn'.$row['proy_id'].'" title="VER PRESUPUESTO ASIGNADO INICIAL - PROGRAMADO - APROBADO" iclass="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center>';
                  }
                }
              $tabla.='</td>';
              $tabla.='<td><center>'.$row['prog'].'</center></td>';
              $tabla.='<td>'.$nombre.'</td>';
              $tabla.='<td>'.$codigo_sisin.'</td>';
              $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
              $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
              $tabla.='</tr>';
            }
            $tabla.='
            </tbody>
          </table>';
      
      return $tabla;
    }


    /*------------ Modificar Partidas -----------*/
    public function edit_partidas($proy_id){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      if(count($data['proyecto'])!=0){
        $data['partidas']=$this->list_partidas($proy_id);
      }
      else{
        redirect('ptto_asig_poa');
      }

      $this->load->view('admin/mantenimiento/ptto_sigep/edit_partidas', $data);
    }


    /*------ Lista de Partidas a modificar -------*/
    function list_partidas($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $partidas=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']);
      $total=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      $tabla='';
      $nro=0;
      $tabla.='<tbody>';
      foreach($partidas  as $row){
        $nro++;
        $tabla .='<tr class="modo1">
                    <td align=center>'.$nro.'<input type="hidden" name="sp_id[]" value="'.$row['sp_id'].'"></td>
                    <td align=center>'.$row['partida'].'</td>
                    <td align=left>'.$row['par_nombre'].'</td>
                    <td align=center>'.$row['importe'].'</td>
                    <td align=center><input type="text" class="form-control" onkeyup="suma_monto();" name="monto[]" id="m'.$nro.'" value="'.$row['importe'].'" title="MODIFICAR MONTO"></td>
                    <td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR MONTO PARTIDA"  name="'.$row['sp_id'].'" id="'.$proy_id.'" ><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>
                  </tr>';
      }
      $tabla.='</tbody>
      <tr>
        <td colspan="3">TOTAL </td>
        <td align=center>'.$total[0]['monto'].'</td>
        <td align=center><input type="text" class="form-control" name="total" value="'.$total[0]['monto'].'" disabled="true"></td>
        <td align=center></td>
      </tr>';

      ?>
      <script type="text/javascript">
        function suma_monto(){ 
            var suma=0;
            for (var i = 1; i <= <?php echo count($partidas); ?>; i++) {
                suma=parseFloat(suma)+parseFloat($('[id="m'+i+'"]').val());
            }
     
            $('[name="total"]').val((suma).toFixed(2));
        }
        </script>
      <?php
      return $tabla;
    }


    /*-------- ELIMINAR MONTOS PARTIDA --------*/
    function delete_partida(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $sp_id = $this->security->xss_clean($post['sp_id']);
          $proy_id = $this->security->xss_clean($post['proy_id']);

          /*------------ ELIMINA ACTIVIDAD PROGRAMADO -----------*/
            $this->db->where('sp_id', $sp_id);
            $this->db->delete('ptto_partidas_sigep');
          
          $sp=$this->model_ptto_sigep->get_sp_id($sp_id);

          if(count($sp)==0){
            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
            'respuesta' => 'error'
           );
          }

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*------------------ UPDATE PARTIDAS (MANTENIMIENTO)-------------------*/
    public function valida_update_partidas(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 

          $nro=0;
          if (!empty($_POST["sp_id"]) && is_array($_POST["sp_id"]) ) {
          foreach ( array_keys($_POST["sp_id"]) as $como){
            //echo "SP ID : ".$_POST["sp_id"][$como]." -> MONTO : ".$_POST["monto"][$como]."<br>";
            $update_sigep= array(
              'importe' => $_POST["monto"][$como],
              'estado' => 2,
              'fun_id' => $this->fun_id
            );
            $this->db->where('sp_id', $_POST["sp_id"][$como]);
            $this->db->update('ptto_partidas_sigep', $this->security->xss_clean($update_sigep));          
          }

          $this->session->set_flashdata('success','SE ACTUALIZARON CORRECTAMENTE LOS MONTOS ASIGNADOS');
          redirect(site_url("").'/mnt/edit_ptto_asig/'.$proy_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL ACTUALIZAR MONTOS');
          redirect(site_url("").'/mnt/edit_ptto_asig/'.$proy_id);
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Operaciones</b></font>";
      }
    }


    /*--------------- Partidas ------------------*/
    function partidas($aper_id,$tp){
        $tabla ='';
        if($tp==1){
            $tb='class="table table-bordered"';
        }
        else{
            $tb='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;"';
        }
        $aper=$this->model_ptto_sigep->partidas_proyecto($aper_id);
        if(count($aper)!=0){
            $tabla .=' <table '.$tb.'>
                        <thead>
                            <tr class="modo1">
                              <th bgcolor="#1c7368">NRO.</th>
                              <th bgcolor="#1c7368">PARTIDA</th>
                              <th bgcolor="#1c7368">DETALLE PARTIDA</th>
                              <th bgcolor="#1c7368">IMPORTE</th>
                            </tr>
                            </thead>
                            <tbody>';
            $nro=0;
            $monto=0;
            foreach($aper  as $row){
                $nro++;
                $tabla .=' <tr class="modo1">
                              <td align=center>'.$nro.'</td>
                              <td align=center>'.$row['partida'].'</td>
                              <td align=left>'.$row['par_nombre'].'</td>
                              <td align=center>'.number_format($row['importe'], 2, ',', '.').'</td>
                            </tr>';
                $monto=$monto+$row['importe'];
            }
            $tabla .=' <tr class="modo1">
                          <td colspan=3>TOTAL</td>
                          <td align=center>'.number_format($monto, 2, ',', '.').'</td>
                        </tr>';
            $tabla .='</tbody>
                    </table>';  
        }
        
        return $tabla;
    }
    /*----------------------------------------------------------------------*/
    /*------------------------- Reporte Partidas ----------------------*/
    public function rep_partida($aper_id){
        $html = $this->partidas_ptto($aper_id);

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','700M');
        ini_set('max_execution_time', 9000000000);
        $dompdf->render();
        $dompdf->stream("REPORTE_PARTIDAS.pdf", array("Attachment" => false));
    }


    function partidas_ptto($aper_id){
        $gestion = $this->session->userdata('gestion');
        $apertura = $this->model_ptto_sigep->apertura_id($aper_id); //// Datos de la apertura
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
                            <b>REPORTE : </b> PARTIDAS ASIGNADAS POR ACCIONES OPERATIVAS<br>
                            <b>APERTURA PROGRAMATICA : </b>'.$apertura[0]['aper_programa'].''.$apertura[0]['aper_proyecto'].''.$apertura[0]['aper_actividad'].'<br>
                            <b>ACCI&Oacute;N OPERATIVA : </b>'.$apertura[0]['aper_descripcion'].'
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
             <p><div>'.$this->partidas($aper_id,2).'</div></p>
           </div>
         </body>
         </html>';
        return $html;
    }


    /*---- SUBIR ARCHIVO SIGEP APROBADO -----*/
    function importar_archivo_sigep2(){
        if ($this->input->post()) {
            $post = $this->input->post();
          
            $tipo = $_FILES['archivo']['type'];
            $tamanio = $_FILES['archivo']['size'];
            $archivotmp = $_FILES['archivo']['tmp_name'];

            $filename = $_FILES["archivo"]["name"];
            $file_basename = substr($filename, 0, strripos($filename, '.'));
            $file_ext = substr($filename, strripos($filename, '.'));
            $allowed_file_types = array('.csv');

            if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
                 
              /*--------------------------------------------------------------*/
                $i=0;
                $nro=0;$nroo=0;
                $lineas = file($archivotmp);
                foreach ($lineas as $linea_num => $linea){ 
                    if($i != 0){ 

                        $datos = explode(";",$linea);
                        //echo count($datos)."<br>";
                        if(count($datos)==7){

                            $da=$datos[0]; /// Da
                            $ue=$datos[1]; /// Ue
                            $prog=$datos[2]; /// Aper Programa
                            $proy=trim($datos[3]);
                            $act=trim($datos[4]);  /// Aper Actividad
                            $cod_part=trim($datos[5]); /// Partida
                            $importe=floatval(trim($datos[6])); /// Monto

                          //  echo $this->gestion."<br>";
                            //echo $prog.'- ('.strlen($prog).') -> '.$proy.' ('.strlen($proy).') -> '.$act.' ('.strlen(trim($act)).') ----'.$importe.'-- CODIGO PARTIDA '.is_numeric($cod_part).'<br>';
                            if(strlen(trim($act))==3 & $importe!=0 & is_numeric($cod_part)){
                              //  echo "INGRESA : ".$prog.'-'.$proy.'-'.$act.'..'.$importe."<br>";
                                $nroo++;
                             //   echo "string<br>";
                                $aper=$this->model_ptto_sigep->get_apertura($da,$ue,$prog,$proy,$act);
                                if(count($aper)!=0){
                                    $partida = $this->model_insumo ->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                                    $par_id=0;
                                    if(count($partida)!=0){
                                        $par_id=$partida[0]['par_id'];
                                    }

                                    $ptto=$this->model_ptto_sigep->get_ptto_sigep($da,$ue,$prog,$proy,$act,$cod_part);
                                    if(count($ptto)!=0){
                                      echo "UPDATES : ".$prog.'-'.$proy.'-'.$act.' cod '.$cod_part.'-- PAR ID : '.$par_id.' ->'.$importe."<br>";
                                       /*------------------- Update Datos ----------------------*/
                                        /*$query=$this->db->query('set datestyle to DMY');
                                        $update_ptto = array(
                                          'aper_id' => $aper[0]['aper_id'],
                                            'importe' => $importe,
                                            'fun_id' => $this->session->userdata("fun_id")
                                        );

                                        $this->db->where('sp_id', $ptto[0]['sp_id']);
                                        $this->db->update('ptto_partidas_sigep', $update_ptto);*/
                                       /*-------------------------------------------------------*/
                                    }
                                    else{
                                      echo "INSERTS : ".$nroo." -> ".$da.' '.$ue.'  '.$prog.'-'.$proy.'-'.$act.' cod '.$cod_part.'-- PAR ID : '.$par_id.' ->'.$importe."<br>";
                                       /*-------------------- Guardando Datos ------------------*/
                                        /*$query=$this->db->query('set datestyle to DMY');
                                        $data_to_store = array( 
                                            'aper_id' => $aper[0]['aper_id'],
                                            'da' => $da,
                                            'ue' => $ue,
                                            'aper_programa' => $prog,
                                            'aper_proyecto' => $proy,
                                            'aper_actividad' => $act,
                                            'par_id' => $par_id,
                                            'partida' => $cod_part,
                                            'importe' => $importe,
                                            'g_id' => $this->gestion,
                                            'fun_id' => $this->session->userdata("fun_id"),
                                        );
                                        $this->db->insert('ptto_partidas_sigep', $data_to_store);
                                        $sp_id=$this->db->insert_id();*/
                                        /*-------------------------------------------------------*/ 
                                    }
                                $nro++;
                                }
                                else{
                                  echo "NO INGRESA : ".$da.'-'.$ue.'- > '.$prog.'-'.$proy.'-'.$act.'..'.$importe."<br>";
                                     /* $partida = $this->minsumos->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                                      $par_id=0;
                                      if(count($partida)!=0){
                                          $par_id=$partida[0]['par_id'];
                                      }*/
                                     /*-------------------- Guardando Datos ------------------*/
                                     /* $query=$this->db->query('set datestyle to DMY');
                                      $data_to_store = array( 
                                          'aper_id' => 0,
                                          'da' => $da,
                                          'ue' => $ue,
                                          'aper_programa' => $prog,
                                          'aper_proyecto' => $proy,
                                          'aper_actividad' => $act,
                                          'par_id' => $par_id,
                                          'partida' => $cod_part,
                                          'importe' => $importe,
                                          'g_id' => $this->gestion,
                                          'fun_id' => $this->session->userdata("fun_id"),
                                      );
                                      $this->db->insert('ptto_partidas_sigep', $data_to_store);
                                      $sp_id=$this->db->insert_id();*/
                                      /*-------------------------------------------------------*/ 
                                }
                            }
                            else{
                              echo "WILMER ".$da.' '.$ue.'- > '.$prog.'-'.$proy.'-'.$act.'-'.$cod_part.' - '.$importe.'<br>';
                            }
                            //elseif(strlen($act)==3 & $importe==0){
                            //$ptto=$this->model_ptto_sigep->get_ptto_sigep($da,$ue,$prog,$proy,$act,$cod_part);
                              //if(count($ptto)!=0){
                                //echo "UPDATES 0->VALOR : ".$prog.'-'.$proy.'-'.$act.' cod '.$cod_part.'-- PAR ID : '.$par_id.' ->'.$importe."<br>";
                                 /*------------------- Update Datos ----------------------*/
                                  /*$query=$this->db->query('set datestyle to DMY');
                                  $update_ptto = array(
                                    'aper_id' => $aper[0]['aper_id'],
                                      'importe' => $importe,
                                      'fun_id' => $this->session->userdata("fun_id")
                                  );

                                  $this->db->where('sp_id', $ptto[0]['sp_id']);
                                  $this->db->update('ptto_partidas_sigep', $update_ptto);*/
                                 /*-------------------------------------------------------*/
                              //}
                            //}
                        }
                    }

                    $i++;
                }

              /*--------------------------------------------------------------*/
            } 
            elseif (empty($file_basename)) {
                echo "<script>alert('SELECCIONE ARCHIVO .CSV')</script>";
            } 
            elseif ($filesize > 100000000) {
                //redirect('');
            } 
            else {
                $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
                echo '<script>alert("' . $mensaje . '")</script>';
            }

        } else {
            show_404();
        }
    }


    /*-------- SUBIR ARCHIVO SIGEP -------*/
    function importar_archivo_sigep(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $tp = $this->security->xss_clean($post['tp_id']);
          $tp_id = $this->security->xss_clean($post['tp_id']);

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
               
            /*--------------------------------------------------------------*/
            if($this->verif_ppto==0){
              $lineas=$this->subir_archivo($archivotmp,$tp_id); /// Techo Inicial
            }
            else{
              $lineas=$this->subir_archivo_aprobado($archivotmp,$tp_id); /// Techo Aprobado
            }
            
            $this->session->set_flashdata('success','SE SUBIO CORRECTAMENTE EL ARCHIVO ('.$lineas.')');
            redirect(site_url("").'/ptto_asig_poa');
            /*--------------------------------------------------------------*/
          } 
          elseif (empty($file_basename)) {
            echo "<script>alert('SELECCIONE ARCHIVO .CSV')</script>";
          } 
          elseif ($filesize > 100000000) {
            //redirect('');
          } 
          else {
            $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
            echo '<script>alert("' . $mensaje . '")</script>';
          }

      } else {
          show_404();
      }
    }

    /*-------- Subir Archivo SIgep -----------*/
    public function subir_archivo($archivotmp,$tp_id){  
        $i=0;
        $nro=0;
        $lineas = file($archivotmp);

        if($tp_id==1){ /// Proyecto de Inversion
          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
              $datos = explode(";",$linea);
                if(count($datos)==4){
                  $aper_id = intval(trim($datos[0])); //// aper_id
                  $cod_sisin = utf8_encode(trim($datos[1])); //// Sisin
                  $cod_part = intval(trim($datos[2])); //// partida
                  $importe = floatval(trim($datos[3])); //// monto

                  if(count($this->model_proyecto->get_aper_programa($aper_id))!=0){ /// Datos ya almacenados
                      $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                      $par_id=0;
                        if(count($partida)!=0){
                          $par_id=$partida[0]['par_id'];
                        }

                      $ptto=$this->model_ptto_sigep->get_ptto_sigep_pi($aper_id,$cod_part);
                      if(count($ptto)!=0){
                        /*-------- Update Datos ---------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $update_ptto = array(
                          'aper_id' => $aper_id,
                          'importe' => $importe,
                          'fun_id' => $this->fun_id
                        );

                        $this->db->where('sp_id', $ptto[0]['sp_id']);
                        $this->db->update('ptto_partidas_sigep', $update_ptto);
                       /*---------------------------------*/
                      }
                      else{
                        // adicionando
                        /*------ Guardando Datos -----*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array( 
                          'aper_id' => $aper_id,
                          'da' => '0',
                          'ue' => '0',
                          'aper_programa' => 72,
                          'aper_proyecto' => $cod_sisin,
                          'aper_actividad' => '00',
                          'par_id' => $par_id,
                          'partida' => $cod_part,
                          'importe' => $importe,
                          'g_id' => $this->gestion,
                          'fun_id' => $this->fun_id,
                        );
                        $this->db->insert('ptto_partidas_sigep', $data_to_store);
                        $sp_id=$this->db->insert_id();
                        /*------------------------------*/ 
                      }

                    $nro++;
                  }
                  
                }
              }

              $i++;
            }
        }
        else{  /// Gasto Corriente
          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
                $datos = explode(";",$linea);
                if(count($datos)==7){
                    $da=$datos[0]; /// Da
                    $ue=$datos[1]; /// Ue
                    $prog=$datos[2]; /// Aper Programa
                    $proy=trim($datos[3]);
                    /*if(strlen($proy)==2){
                      $proy='00'.$proy; /// Aper Proyecto
                    }*/
                    $act=trim($datos[4]);  /// Aper Actividad
                    /*if(strlen($act)==2){
                      $act='0'.$act;
                    }*/

                    //$act='0'.trim($datos[4]);  /// Aper Actividad
                    $cod_part=trim($datos[5]); /// Partida
                    /*if(strlen($cod_part)==3){
                      $cod_part=$cod_part.'00';
                    }*/

                    $importe=floatval($datos[6]); /// Monto
                    //if(strlen($prog)==2 & strlen($proy)==4 & strlen($act)==3 & $importe!=0 & is_numeric($cod_part)){ //// gestion 2021/2022
                    if(strlen($act)==3 & $importe!=0 & is_numeric($cod_part)){ /// gestion 2024
                        $aper=$this->model_ptto_sigep->get_apertura($da,$ue,$prog,$proy,$act);
                        //$aper=$this->model_ptto_sigep->get_apertura($prog,$proy,$act);
                        if(count($aper)!=0){
                            $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                            $par_id=0;
                            if(count($partida)!=0){
                                $par_id=$partida[0]['par_id'];
                            }

                            //$ptto=$this->model_ptto_sigep->get_ptto_sigep($prog,$proy,$act,$cod_part);
                            $ptto=$this->model_ptto_sigep->get_ptto_sigep($da,$ue,$prog,$proy,$act,$cod_part);
                            if(count($ptto)!=0){
                               /*------------------- Update Datos ----------------------*/
                                $query=$this->db->query('set datestyle to DMY');
                                $update_ptto = array(
                                    'aper_id' => $aper[0]['aper_id'],
                                    'importe' => $importe,
                                    'fun_id' => $this->session->userdata("fun_id")
                                );

                                $this->db->where('sp_id', $ptto[0]['sp_id']);
                                $this->db->update('ptto_partidas_sigep', $update_ptto);
                               /*-------------------------------------------------------*/
                            }
                            else{
                               /*-------------------- Guardando Datos ------------------*/
                                $query=$this->db->query('set datestyle to DMY');
                                $data_to_store = array( 
                                    'aper_id' => $aper[0]['aper_id'],
                                    'da' => $da,
                                    'ue' => $ue,
                                    'aper_programa' => $prog,
                                    'aper_proyecto' => $proy,
                                    'aper_actividad' => $act,
                                    'par_id' => $par_id,
                                    'partida' => $cod_part,
                                    'importe' => $importe,
                                    'g_id' => $this->gestion,
                                    'fun_id' => $this->session->userdata("fun_id"),
                                );
                                $this->db->insert('ptto_partidas_sigep', $data_to_store);
                                $sp_id=$this->db->insert_id();
                                /*-------------------------------------------------------*/ 
                            }
                        $nro++;
                        }
                        else{
                              $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                              $par_id=0;
                              if(count($partida)!=0){
                                  $par_id=$partida[0]['par_id'];
                              }
                             /*-------------------- Guardando Datos ------------------*/
                              $query=$this->db->query('set datestyle to DMY');
                              $data_to_store = array( 
                                  'aper_id' => 0,
                                  'da' => $da,
                                  'ue' => $ue,
                                  'aper_programa' => $prog,
                                  'aper_proyecto' => $proy,
                                  'aper_actividad' => $act,
                                  'par_id' => $par_id,
                                  'partida' => $cod_part,
                                  'importe' => $importe,
                                  'g_id' => $this->gestion,
                                  'fun_id' => $this->session->userdata("fun_id"),
                              );
                              $this->db->insert('ptto_partidas_sigep', $data_to_store);
                              $sp_id=$this->db->insert_id();
                              /*-------------------------------------------------------*/ 
                          }
                    }
                    elseif(strlen($act)==3 & $importe==0){
                      $ptto=$this->model_ptto_sigep->get_ptto_sigep($prog,$proy,$act,$cod_part);
                      if(count($ptto)!=0){
                        //echo "UPDATES 0->VALOR : ".$prog.'-'.$proy.'-'.$act.' cod '.$cod_part.'-- PAR ID : '.$par_id.' ->'.$importe."<br>";
                        /*------------------- Update Datos ----------------------*/
                          $query=$this->db->query('set datestyle to DMY');
                          $update_ptto = array(
                            'aper_id' => $aper[0]['aper_id'],
                            'importe' => $importe,
                            'fun_id' => $this->session->userdata("fun_id")
                          );

                          $this->db->where('sp_id', $ptto[0]['sp_id']);
                          $this->db->update('ptto_partidas_sigep', $update_ptto);
                         /*-------------------------------------------------------*/
                      }
                    }
                }
            }

            $i++;
          }
        }

        return $nro;
     }



    /*--------- Subir Archivo SIgep Aprobado ----------*/
    public function subir_archivo_aprobado($archivotmp,$tp_id){  
      $i=0;
      $nro=0;
      $lineas = file($archivotmp);
      
      foreach ($lineas as $linea_num => $linea){ 
          if($i != 0){ 
              $datos = explode(";",$linea);
              if(count($datos)==7){
                  $da=trim($datos[0]); /// Da
                  $ue=trim($datos[1]); /// Ue
                  $prog=trim($datos[2]); /// Aper Programa
                  $proy=trim($datos[3]); /// proyecto
                  /*if(strlen($proy)==2){
                    $proy='00'.$proy; /// Aper Proyecto
                  }*/
                  $act=trim($datos[4]);  /// Aper Actividad
                  if(strlen($act)==2){
                    $act='0'.$act;
                  }

                  //$act='0'.trim($datos[4]);  /// Aper Actividad
                  $cod_part=trim($datos[5]); /// Partida
                  if(strlen($cod_part)==3){
                    $cod_part=$cod_part.'00';
                  }

                  $importe=(float)$datos[6]; /// Monto

                  if(strlen($da)==2 and strlen($ue)==3 and strlen($prog)==3 & strlen($proy)==2 & strlen($act)==3 & $importe!=0 & is_numeric($cod_part)){
                      $aper=$this->model_ptto_sigep->get_apertura($da,$ue,$prog,$proy,$act);
                      if(count($aper)!=0){
                          $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                          $par_id=0;
                          if(count($partida)!=0){
                            $par_id=$partida[0]['par_id'];
                          }

                          $ptto=$this->model_ptto_sigep->get_ptto_sigep_aprobado($prog,$proy,$act,$cod_part);
                          if(count($ptto)!=0){
                             /*------------------- Update Datos ----------------------*/
                              $query=$this->db->query('set datestyle to DMY');
                              $update_ptto = array(
                                'aper_id' => $aper[0]['aper_id'],
                                'importe' => $importe,
                                'fun_id' => $this->fun_id
                              );

                              $this->db->where('sp_id', $ptto[0]['sp_id']);
                              $this->db->update('ptto_partidas_sigep_aprobado', $update_ptto);
                             /*-------------------------------------------------------*/
                             $nro++;
                          }
                          else{
                             /*-------------------- Guardando Datos ------------------*/
                              $query=$this->db->query('set datestyle to DMY');
                              $data_to_store = array( 
                                  'aper_id' => $aper[0]['aper_id'],
                                  'aper_programa' => $prog,
                                  'aper_proyecto' => $proy,
                                  'aper_actividad' => $act,
                                  'par_id' => $par_id,
                                  'partida' => $cod_part,
                                  'importe' => $importe,
                                  'g_id' => $this->gestion,
                                  'fun_id' => $this->fun_id,
                              );
                              $this->db->insert('ptto_partidas_sigep_aprobado', $data_to_store);
                              $sp_id=$this->db->insert_id();
                              /*-------------------------------------------------------*/
                          }
                      $nro++;
                      }
                      else{
                            $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                            $par_id=0;
                            if(count($partida)!=0){
                                $par_id=$partida[0]['par_id'];
                            }
                           /*-------------------- Guardando Datos ------------------*/
                            $query=$this->db->query('set datestyle to DMY');
                            $data_to_store = array( 
                                'aper_id' => 0,
                                'aper_programa' => $prog,
                                'aper_proyecto' => $proy,
                                'aper_actividad' => $act,
                                'par_id' => $par_id,
                                'partida' => $cod_part,
                                'importe' => $importe,
                                'g_id' => $this->gestion,
                                'fun_id' => $this->fun_id,
                            );
                            $this->db->insert('ptto_partidas_sigep_aprobado', $data_to_store);
                            $sp_id=$this->db->insert_id();
                            /*-------------------------------------------------------*/ 
                            $nro++;
                        }
                  }
                  elseif(strlen($prog)==3 & strlen($proy)==2 & strlen($act)==3 & $importe==0){
                    $ptto=$this->model_ptto_sigep->get_ptto_sigep($prog,$proy,$act,$cod_part);
                    if(count($ptto)!=0){
                      //echo "UPDATES 0->VALOR : ".$prog.'-'.$proy.'-'.$act.' cod '.$cod_part.'-- PAR ID : '.$par_id.' ->'.$importe."<br>";
                      /*------------------- Update Datos ----------------------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $update_ptto = array(
                          'aper_id' => $aper[0]['aper_id'],
                          'importe' => $importe,
                          'fun_id' => $this->fun_id
                        );

                        $this->db->where('sp_id', $ptto[0]['sp_id']);
                        $this->db->update('ptto_partidas_sigep_aprobado', $update_ptto);
                       /*-------------------------------------------------------*/
                       $nro++;
                    }
                  }
                }
            }
            $i++;
        }
        return $nro;
     }





    /*---- LISTA DE OPERACIONES PARA LA REASIGNACION DE PRESUPUESTO FINAL ---*/
    public function list_ptto_poa_final($tp_id){
      $lista_aper_padres = $this->model_proyecto->list_prog();//lista de aperturas padres 
      $tabla ='';
      foreach($lista_aper_padres  as $rowa){
        $proyectos=$this->model_ptto_sigep->acciones_operativas($rowa['aper_programa'],$tp_id);
        if(count($proyectos)!=0){
          $tabla .='<tr bgcolor="#99DDF0" height="30">';
            $tabla .='<td></td>';
            if($this->tp_adm==1){
              $tabla .='<td></td>';
            }
            $tabla .='<td><center>'.$rowa['aper_programa'].''.$rowa['aper_proyecto'].''.$rowa['aper_actividad'].'</center></td>';
            $tabla .='<td>'.$rowa['aper_descripcion'].'</td>';
            $tabla .='<td>'.$rowa['aper_sisin'].'</td>';
            $tabla .='<td></td>';
            $tabla .='<td></td>';
            $tabla .='<td></td>';
            $tabla .='<td></td>';
            $tabla .='<td></td>';
          $tabla .='</tr>';
          $nro=0;
          foreach($proyectos  as $row){
            $nro++;
          //  $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $aper=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);
            $tabla .= '<tr height="50">';
              $tabla .= '<td align=center><center><img id="loadd'.$row['proy_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></center></td>';
              if($this->tp_adm==1){
                if(count($aper)!=0){
                  $tabla .='<td><center><a href="'.site_url("").'/mnt/ver_ptto_asig_final/'.$row['proy_id'].'" id="myBtnn'.$row['proy_id'].'" title="VER PRESUPUESTO ASIGNADO INICIAL - PROGRAMADO - APROBADO" iclass="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center></td>';
                }
                else{
                  $tabla .='<td></td>';
                }
              }
              $tabla .= '<td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>';
              $tabla .= '<td>'.$row['proy_id'].' | '.$row['proy_nombre'].'</td>';
              $tabla .= '<td>'.$row['tp_tipo'].'</td>';
              $tabla .= '<td>'.$row['proy_sisin'].'</td>';
              $tabla .= '<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                $tabla .='<td></td>';
                $tabla .='<td></td>';
                $tabla .='<td></td>';
            $tabla .= '</tr>';
            

          }
        }
      }
      return $tabla;
    }

    /*------------ Verificar Comparativo Partidas -----------*/
    public function ver_comparativo_partidas($proy_id){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
      if(count($data['proyecto'])!=0){
        $data['partidas']= $this->comparativo_partidas_ppto_final($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id'],1);
      //echo $data['partidas'];
        $this->load->view('admin/mantenimiento/ptto_sigep/comparativo_partidas', $data);
      }
      else{
        redirect('ptto_asig_poa');
      }
    }


    /*------ Ver Lista de Partidas Comparativos 2020 -------*/
    public function comparativo_partidas_ppto_final($dep_id,$aper_id,$tp_tab){ 
     // echo "DEP : ".$dep_id." aper_id : ".$aper_id."<br>";
      $tabla ='';
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Presupuesto Partidas asignado
      $partidas_aprobados=$this->model_ptto_sigep->list_ppto_final_aprobado($aper_id); // Presupuesto Partidas Asignado
      if($tp_tab==1){
        $tab='id="table" class="table table-bordered"';
      }
      else{
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }

      $tabla .='
        <table '.$tab.'>
          <thead>
            <tr style="font-size: 7px;" align=center>
              <th bgcolor="#1c7368" style="width:2%;color:#FFF;height:15px;">NRO. '.$aper_id.' -- '.$dep_id.'</th>
              <th bgcolor="#1c7368" style="width:5%;color:#FFF;" title="CODIGO PARTIDA">C&Oacute;DIGO</th>
              <th bgcolor="#1c7368" style="width:40%;color:#FFF;" title="DESCRIPCI&Oacute;N PARTIDA">DETALLE PARTIDA</th>
              <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO PRESUPUESTO PROGRAMADO">PPTO. PROGRAMADO POA (SIIPLAS)</th>
              <th bgcolor="#1c7368" style="width:5%;color:#FFF;" title="AJUSTAR"></th>
              <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO PRESUPUESTO FINAL APROBADO">PPTO. FINAL APROBADO (SIGEP)</th>
              <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO DIFERENCIA (INICIAL - FINAL)">MONTO DIFERENCIA (SIGEP-SIIPLAS)</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
          $monto_poa=0;
          $monto_final=0;
          $monto_diferencia=0;
          foreach($partidas_prog as $row){
            $boton='';
            $ppto=$this->model_ptto_sigep->get_ptto_aprobado($aper_id,$row['par_id']);
            $monto_final_partida=0;
            $dif_monto=0;
            if(count($ppto)!=0){
              $monto_final_partida=$ppto[0]['monto'];
            }
            $dif_monto=$monto_final_partida-$row['ppto_asignado'];
            $color='';$sig='';
            if($dif_monto<0){
              $color='#f7b1b0';
              $boton='
              <a href="#" data-toggle="modal" data-target="#modal_update_ff" class="btn btn-danger update_ff" title="AJUSTAR PRESUPUESTO"  name="'.$row['sp_id'].'" id="'.$row['codigo'].'">
                AJUSTAR PPTO.
              </a>';
            }
            elseif ($dif_monto>0) {
              $sig='+';
              $color='#dff0d8';
              $boton='
              <a href="#" data-toggle="modal" data-target="#modal_update_ff" class="btn btn-success update_ff" title="AJUSTAR PRESUPUESTO"  name="'.$row['sp_id'].'" id="'.$row['codigo'].'">
                AJUSTAR PPTO.
              </a>';
            }

            $nro++;
            $tabla.='
              <tr bgcolor='.$color.'>
                <td style="width:2%;height:12px;" align=center>'.$nro.'</td>
                <td style="width:5%;" align=center><b>'.$row['codigo'].'</b></td>
                <td style="width:30%;">'.$row['nombre'].'</td>
                <td style="width:10%;" align=right><input type="text" class="form-control" name="monto'.$row['sp_id'].'" id="monto'.$row['sp_id'].'" value="'.round($row['ppto_asignado'],2).'" title="MODIFICAR MONTO"></td>
                <td align=center>'.$boton.' <div id="load'.$row['sp_id'].'" style="display: none"><br><img src="'.base_url().'assets/img/loading.gif" width="25" height="25"/></div></td>
                <td style="width:12%;" align=right>'.number_format($monto_final_partida, 2, ',', '.').'</td>
                <td style="width:12%;" align=right>'.$sig.''.number_format($dif_monto, 2, ',', '.').'</td>
              </tr>';
            $monto_poa=$monto_poa+$row['ppto_asignado'];
            $monto_final=$monto_final+$monto_final_partida;
          }
          
          foreach($partidas_aprobados as $row){
            //$ppto=$this->model_ptto_sigep->get_partida_accion($aper_id,$row['par_id']); /// programado
            $ppto=$this->model_ptto_sigep->get_partida_asignado_sigep($aper_id,$row['par_id']); /// Asignado Anteproyecto

            if(count($ppto)==0){
              $dif_monto=$row['importe']-0;
              $nro++;
              $tabla.='
                <tr bgcolor="#f7e1b4">
                  <td style="width:2%;height:12px;" align=center title="'.$row['sp_id'].'">'.$nro.'</td>
                  <td style="width:5%;" align=center><b>'.$row['partida'].'</b></td>
                  <td style="width:30%;">'.$row['par_nombre'].'</td>
                  <td style="width:10%;" align=right>
                    <input type="text" class="form-control" name="monto'.$row['sp_id'].'" id="monto'.$row['sp_id'].'" value="'.round($row['importe'],2).'" title="MODIFICAR MONTO">
                  </td>
                  <td align=center>
                    <a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-warning add_ff" title="AGREGAR PRESUPUESTO"  name="'.$row['sp_id'].'" id="'.$row['par_codigo'].'">
                      AGREGAR PPTO.
                    </a>
                    <div id="loadd'.$row['sp_id'].'" style="display: none"><br><img src="'.base_url().'assets/img/loading.gif" width="25" height="25"/></div>
                  </td>
                  <td style="width:12%;" align=right>'.number_format($row['importe'], 2, ',', '.').'</td>
                  <td style="width:12%;" align=right>'.number_format($dif_monto, 2, ',', '.').'</td>
                </tr>';
                $monto_final=$monto_final+$row['importe'];
            }
          }
        $tabla.='
            <tr>
              <td colspan=3>TOTAL</td>
              <td style="height:12px;" align=right>'.number_format($monto_poa, 2, ',', '.').'</td>
              <td></td>
              <td align=right>'.number_format($monto_final, 2, ',', '.').'</td>
              <td align=right>'.number_format(($monto_final-$monto_poa), 2, ',', '.').'</td>
            </tr>
          </tbody>
        </table>';

        return $tabla;
    }


    /*------ ACTUALIZA PRESUPUESTO POR PARTIDA ------*/
    function update_ppto_aprobado(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $sp_id = $this->security->xss_clean($post['sp_id']); // sp id
          $monto_final = $this->security->xss_clean($post['ppto']); // monto
        
          /*--------- Update ppto Sigep ----------*/
          $update_ppto= array(
            'importe' => $monto_final,
            'estado' => 2,
            'fun_id' => $this->fun_id
          );
          $this->db->where('sp_id', $sp_id);
          $this->db->update('ptto_partidas_sigep', $this->security->xss_clean($update_ppto));
          /*----------------------------------------*/


          $result = array(
              'respuesta' => 'correcto'
          );

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }



    /*------ ADICIONA PRESUPUESTO POR PARTIDA ------*/
    function add_ppto_aprobado(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $sp_id = $this->security->xss_clean($post['sp_id']); // sp id
          $monto_final = $this->security->xss_clean($post['ppto']); // monto
          $ppto_aprobado=$this->model_ptto_sigep->get_ppto_aprobado($sp_id);

          if(count($ppto_aprobado)!=0){
          
            $data_to_store = array( 
              'aper_id' => $ppto_aprobado[0]['aper_id'],
              'aper_programa' => $ppto_aprobado[0]['aper_programa'],
              'aper_proyecto' => $ppto_aprobado[0]['aper_proyecto'],
              'aper_actividad' => $ppto_aprobado[0]['aper_actividad'],
              'par_id' => $ppto_aprobado[0]['par_id'],
              'partida' => $ppto_aprobado[0]['partida'],
              'importe' => $ppto_aprobado[0]['importe'],
              'g_id' => $ppto_aprobado[0]['g_id'],
              'estado' => 2,
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('ptto_partidas_sigep', $data_to_store);
          
            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }






    /*------ Ver Lista de Partidas Comparativos 2019 -------*/
    public function comparativo_partidas($dep_id,$aper_id,$tp_tab){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Presupuesto Asignado
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,2); // Presupuesto Programado
      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $monto_asig_final=0;
      if($tp_tab==1){
        $tab='id="table" class="table table-bordered"';
      }
      else{
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }

      $tabla .='<table '.$tab.'>
                  <thead>
                    <tr style="font-size: 7px;" align=center>
                      <th bgcolor="#1c7368" style="width:2%;color:#FFF;height:15px;">NRO.</th>
                      <th bgcolor="#1c7368" style="width:3%;color:#FFF;" title="CODIGO PARTIDA">C&Oacute;DIGO</th>
                      <th bgcolor="#1c7368" style="width:30%;color:#FFF;" title="DESCRIPCI&Oacute;N PARTIDA">DETALLE PARTIDA</th>
                      <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO PRESUPUESTO ASIGNADO INICIAL">PPTO. ASIGNADO INICIAL (AI)</th>
                      <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO PRESUPUESTO ASIGNADO APROBADO">PPTO. PROGRAMADO POA (PP)</th>
                      <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO DIFERENCIA (INICIAL - PROGRAMADO)">MONTO DIFERENCIA (PP-AI)</th>
                      <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO PRESUPUESTO INICIAL APROBADO">PPTO. ASIGNADO APROBADO (AF)</th>
                      <th bgcolor="#1c7368" style="width:12%;color:#FFF;" title="MONTO DIFERENCIA (INICIAL - FINAL)">MONTO DIFERENCIA (AF-AI)</th>
                    </tr>

                  </thead>
                  <tbody>';
        if(count($partidas_asig)>count($partidas_prog)){
            foreach($partidas_asig  as $row){
            $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']); //// Presupuesto Programado
            $m_aprob=$this->model_ptto_sigep->get_ptto_aprobado($aper_id,$row['par_id']);

              /*------ Asignado-programado -----*/
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }
              /*-------------------------------*/

              $monto_final=0; $color2='#cbf9f3';
              if(count($m_aprob)!=0){
                $monto_final=$m_aprob[0]['monto'];
                if($row['monto']!=$m_aprob[0]['monto']){
                  $color2='#f9cdcd';
                }
              }

              $nro++;
              $tabla .='<tr title="aper : '.$aper_id.'-- par : '.$row['par_id'].'">
                          <td align=center bgcolor='.$color.' style="width:2%;height:12px;">'.$nro.'</td>
                          <td align=center bgcolor='.$color.' style="width:3%;"><b>'.$row['codigo'].'</b></td>
                          <td align=left bgcolor='.$color.' style="width:30%;">'.$row['nombre'].'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($dif, 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color2.' style="width:12%;">'.number_format(($monto_final), 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color2.' style="width:12%;">'.number_format(($monto_final-$row['monto']), 2, ',', '.').'</td>
                          
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;

              $monto_asig_final=$monto_asig_final+$monto_final;
          }

        }
        else{
            foreach($partidas_prog  as $row){
              $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
              $m_aprob=$this->model_ptto_sigep->get_ptto_aprobado($aper_id,$row['par_id']);

              /*------ Asignado-programado -----*/
              $asig=0;
              if(count($part)!=0){
                $asig=$part[0]['monto'];
              }
              $dif=($asig-$row['monto']);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }
              /*-------------------------------*/

              $monto_final=0; $color2='#cbf9f3';
              if(count($m_aprob)!=0){
                $monto_final=$m_aprob[0]['monto'];
                if($asig!=$m_aprob[0]['monto']){
                  $color2='#f9cdcd';
                }
              }

              $nro++;
              $tabla .='<tr title="aper : '.$aper_id.'-- par : '.$row['par_id'].'"> 
                          <td align=center bgcolor='.$color.' style="width:1%;height:12px;">'.$nro.'</td>
                          <td align=center bgcolor='.$color.' style="width:5%;"><b>'.$row['codigo'].'</b></td>
                          <td align=left bgcolor='.$color.' style="width:30%;">'.$row['nombre'].'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($asig, 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color.' style="width:12%;">'.number_format($dif, 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color2.' style="width:12%;">'.number_format(($monto_final), 2, ',', '.').'</td>
                          <td align=right bgcolor='.$color2.' style="width:12%;">'.number_format(($monto_final-$asig), 2, ',', '.').'</td>
                          
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$asig;

              $monto_asig_final=$monto_asig_final+$monto_final;
          }

        }

      $tabla .='</tbody>
                  <tr>
                      <td colspan=3 style="height:12px;"><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_asig_final, 2, ',', '.').'</td>
                      <td align=right></td>
                    </tr>
                </table>';

      return $tabla;
    }

    /*-------- REPORTE COMPARATIVO POR UNIDAD (PDF) ---------*/
    public function reporte_comparativo_unidad($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);

      if(count($data['proyecto'])!=0){
        $data['mes'] = $this->mes_nombre();
        //$data['partidas']= $this->comparativo_partidas($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id'],2); //// Cuadro comparativo de partidas
        $data['partidas']= $this->comparativo_partidas_ppto_final($data['proyecto'][0]['dep_id'],$data['proyecto'][0]['aper_id'],2); //// Cuadro comparativo de partidas
        $this->load->view('admin/mantenimiento/ptto_sigep/reporte_comparativo_partidas', $data);
      }
      else{
        echo "ERROR";
      }
    }
    
    /*------------- REPORTE COMPARATIVO TODOS (EXCEL) -------------*/
    public function exportar_cuadro_comparativo($dep_id,$tp){
      if($tp==1 || $tp==4){
       // echo $this->cuadro_excel($dep_id,$tp);
        $dep=$this->model_proyecto->get_departamento($dep_id);
        $departamento=$dep[0]['dep_departamento'];
        $cuadro=$this->cuadro_excel($dep_id,$tp);
        date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=COMPARATIVO - ".$departamento."_$fecha.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "";
        echo "".$cuadro."";
      }
      else{
        echo "ERROR";
      }
    }

    public function cuadro_excel($dep_id,$tp){
      $tabla='';
      if($tp==1){ /// Proyecto de Inversion
        $unidades_proy=$this->model_proyecto->list_proy_inversion_regional($dep_id);
        $tit='PROYECTO DE INVERSIÓN';
      }
      else{ /// Gasto Corriente
        $unidades_proy=$this->model_proyecto->list_gasto_corriente_regional($dep_id);
        $tit='UNIDAD, ESTABLECIMIENTO';
      }

      $tabla .='
        <style>
          table{font-size: 9px;
            width: 80%;
            max-width:1550px;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
        </style>
        <table border="1" cellpadding="0" cellspacing="0" class="tabla">
        <thead>
        <tr class="modo1" style="height:50px;">
          <th>APERTURA PROGRAMATICA '.$this->gestion.'</th>
          <th>'.$tit.'</th>
          <th>CODIGO PARTIDA</th>
          <th>PRESUPUESTO POA (SIIPLAS)</th>
          <th>PRESUPUESTO APROBADO (SIGEP)</th>
          <th>MONTO DIFERENCIA (SIGEP-SIIPLAS)</th>
        </tr>
        </thead>
        <tbody>';
        
        foreach($unidades_proy as $rowp){
          $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$rowp['aper_id'],1); // Presupuesto Partidas Programado
          $partidas_aprobados=$this->model_ptto_sigep->list_ppto_final_aprobado($rowp['aper_id']); // Presupuesto Partidas Aprobado
          
          foreach($partidas_prog as $row){
            $ppto=$this->model_ptto_sigep->get_ptto_aprobado($rowp['aper_id'],$row['par_id']);
            $monto_final_partida=0;
            $dif_monto=0;
            if(count($ppto)!=0){
              $monto_final_partida=$ppto[0]['monto'];
            }
            $dif_monto=$monto_final_partida-$row['monto'];
            $color='';$sig='';
            if($dif_monto<0){
              $color='#f7b1b0';
            }
            elseif ($dif_monto>0) {
              $sig='+';
              $color='#dff0d8';
            }

     
            $tabla.='
              <tr bgcolor='.$color.'>
                <td style="width:5%;height:25px;" align=center>\''.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'\'</td>
                <td>';
                if($rowp['tp_id']==1){
                  $tabla.=''.mb_convert_encoding($rowp['proy_nombre'], 'cp1252', 'UTF-8').'';
                }
                else{
                  $tabla.=''.mb_convert_encoding($rowp['tipo'].' '.$rowp['proy_nombre'].' - '.$rowp['abrev'], 'cp1252', 'UTF-8').'';
                }
                $tabla.='
                </td>
                <td style="width:5%;" align=center><b>'.$row['codigo'].'</b></td>
                <td style="width:12%;" align=right>'.$row['monto'].'</td>
                <td style="width:12%;" align=right>'.$monto_final_partida.'</td>
                <td style="width:12%;" align=right>'.$dif_monto.'</td>
              </tr>';
          }

          foreach($partidas_aprobados as $row){
            //$ppto=$this->model_ptto_sigep->get_partida_accion($rowp['aper_id'],$row['par_id']);

            $ppto=$this->model_ptto_sigep->get_partida_asignado_sigep($rowp['aper_id'],$row['par_id']);
            if(count($ppto)==0){
              $dif_monto=$row['importe']-0;
              $tabla.='
                <tr bgcolor="#f7b1b0">
                  <td style="width:5%;height:25px;" align=center>\''.$rowp['aper_programa'].''.$rowp['aper_proyecto'].''.$rowp['aper_actividad'].'\'</td>
                  <td>';
                  if($rowp['tp_id']==1){
                    $tabla.=''.mb_convert_encoding($rowp['proy_nombre'], 'cp1252', 'UTF-8').'';
                  }
                  else{
                    $tabla.=''.mb_convert_encoding($rowp['tipo'].' '.$rowp['proy_nombre'].' - '.$rowp['abrev'], 'cp1252', 'UTF-8').'';
                  }
                  $tabla.='
                  </td>
                  <td style="width:5%;" align=center><b>'.$row['partida'].'</b></td>
                  <td style="width:12%;" align=right>0</td>
                  <td style="width:12%;" align=right>'.$row['importe'].'</td>
                  <td style="width:12%;" align=right>'.$dif_monto.'</td>
                </tr>';

            }
          }

        }
      $tabla.='
        </tbody>
      </table>';
      return $tabla;
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

    /*-------------------------- Menu ----------------------------*/
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

    /*------------------ Rol Funcionario ---------------------*/
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