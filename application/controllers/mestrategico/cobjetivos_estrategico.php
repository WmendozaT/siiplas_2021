<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cobjetivos_estrategico extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');  
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
          $this->load->library('pdf');
          $this->load->library('pdf2');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('resultados/model_resultado');
          $this->load->model('mestrategico/model_mestrategico');
          $this->load->model('programacion/model_producto');
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->fun_id = $this->session->userData('fun_id');
        }else{
            redirect('/','refresh');
        }
    }

    /*------------------------- TIPO DE RESPONSABLE ---------------------*/
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
    


    /*================================ OBJETIVOS ESTRATEGICOS ========================================*/
    /*------------------------- LISTA OBJETIVOS ESTRATEGICOS ---------------------*/
    public function objetivos_estrategicos(){
      $data['menu']=$this->menu(1);
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $prog='';
      $programas=$this->model_proyecto->list_prog();
      $prog.='  <section>
                  <select class="form-control" id="aper_id" name="aper_id" title="SELECCIONE PROGRAMA">
                    <option value="0">SELECCIONE PROGRAMA</option>';
                      foreach($programas as $row){
                        $prog.='<option value='.$row['aper_id'].'>'.$row['aper_programa'].' '.$row['aper_descripcion'].'</option>';
                      }
                    $prog.='       
                  </select>
                </section>';

      $data['programa']=$prog;
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['obj_estrategicos']=$this->mis_objetivos_estrategicos();

     // echo $data['configuracion'][0]['conf_gestion_desde'].'---'.$data['configuracion'][0]['conf_gestion_hasta'];
      $this->load->view('admin/mestrategico/obj_estrategico/objetivos_estrategicos', $data);
    }

    /*------------------------- LISTA DE OBJETIVOS ESTRATEGICOS --------------------*/
    public function mis_objetivos_estrategicos(){
      $objetivos = $this->model_mestrategico->list_objetivos_estrategicos(); /// OBJETIVOS ESTRATEGICOS
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>OBJETIVOS ESTRATEGICOS</strong></h2>  
                    </header>
                <div>
                  <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - OBJETIVO ESTRATEGICO">NUEVO REGISTRO</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th style="width:1%;">NRO</th>
                          <th style="width:3%;">ACCI&Oacute;N ESTRATEGICA</th>
                          <th style="width:10%;">DESCRIPCI&Oacute;N OBJETIVO ESTRATEGICO</th>
                          <th style="width:5%;">GESTI&Oacute;N INICIO</th>
                          <th style="width:5%;">GESTI&Oacute;N FIN</th>
                          <th style="width:3%;">NUEVOS RESULTADOS</th>
                          <th style="width:50%;">RESULTADOS FINALES</th>
                          <th style="width:5%;">MODIFICAR</th>
                          <th style="width:5%;">ELIMINAR</th>
                          <th style="width:5%;" title="Alineacion PEI-POA">REPORTE</th>
                          <th style="width:5%;" title="Exportar Alineacion a formato Excel">EXP.</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($objetivos  as $row){
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td title='.$row['obj_id'].'>'.$nro.'</td>';
                            $tabla .='<td align="center"><a href="'.site_url("").'/me/acciones_estrategicas/'.$row['obj_id'].'" title="ACCIONES ESTRATEGICAS"><img src="' . base_url() . 'assets/img/folder.png"" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td>'.$row['obj_codigo'].'.- '.$row['obj_descripcion'].'</td>';
                            $tabla .='<td>'.$row['obj_gestion_inicio'].'</td>';
                            $tabla .='<td>'.$row['obj_gestion_fin'].'</td>';
                            $tabla .='<td align="center"><a href="#" data-toggle="modal" data-target="#modal_add_rf" class="btn btn-xs add_rf"  title="AGREGAR NUEVO RESULTADO FINAL" name="'.$row['obj_id'].'"><img src="'.base_url().'assets/ifinal/add.jpg" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td>';
                              $rfinal=$this->model_mestrategico->list_resultados_final($row['obj_id']);
                              if(count($rfinal)!=0){
                                $tabla.='<table class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width:1%;">NRO</th>
                                            <th style="width:1%;"></th>
                                            <th style="width:2%;">C&Oacute;DIGO</th>
                                            <th style="width:10%;">RESULTADOS FINALES</th>
                                            <th style="width:7%;">INDICADOR DE IMPACTO</th>
                                            <th style="width:3%;">META</th>
                                            <th style="width:3%;">2016</th>
                                            <th style="width:3%;">2017</th>
                                            <th style="width:3%;">2018</th>
                                            <th style="width:3%;">2019</th>
                                            <th style="width:3%;">2020</th>
                                          </tr>
                                        </thead>
                                        <tbody>';
                                        $nro_rf=0;
                                        foreach($rfinal  as $rowr){
                                          $nro_rf++;
                                          $tabla.='<tr>';
                                            $tabla.='
                                            <td title='.$rowr['rf_id'].'>'.$nro_rf.'</td>
                                            <td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_rf" class="btn btn-xs mod_rf"  title="MODIFICAR RESULTADO FINAL" name="'.$row['obj_id'].'" id="'.$rowr['rf_id'].'"><img src="'.base_url().'assets/img/mod_icon.png" WIDTH="35" HEIGHT="35"/></a></td>
                                            <td>'.$rowr['rf_cod'].'</td>
                                            <td>'.$rowr['rf_resultado'].'</td>
                                            <td>'.$rowr['rf_indicador'].'</td>
                                            <td>'.$rowr['rf_meta'].'</td>
                                            <td>'.$rowr['mes1'].'</td>
                                            <td>'.$rowr['mes2'].'</td>
                                            <td>'.$rowr['mes3'].'</td>
                                            <td>'.$rowr['mes4'].'</td>
                                            <td>'.$rowr['mes5'].'</td>';
                                          $tabla.='</tr>';
                                        }
                                        $tabla.='
                                        </tbody>
                                      </table>';
                              }
                            $tabla .='</td>';
                            $tabla .='<td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff"  title="MODIFICAR OBJETIVO ESTRATEGICO" name="'.$row['obj_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR OBJETIVO ESTRATEGICO"  name="'.$row['obj_id'].'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td align=center><a href="javascript:abreVentana_obj(\''.site_url("").'/me/reporte_obj/'.$row['obj_id'].'\');" title="REPORTE DE VINCULACION PEI"><img src="' . base_url() . 'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></td>';
                            $tabla .='<td align=center><a href="'.site_url("").'/me/exportar_alineacion/'.$row['obj_id'].'" title="EXPORTAR ALINEACION PEI-POA" id="myBtn'.$row['obj_id'].'"><img src="' . base_url() . 'assets/ifinal/excel.jpg" WIDTH="40"/></a></td>';
                            $tabla .='<td align="center"><img id="load'.$row['obj_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>';
                          $tabla .='</tr>';
                          $tabla.='<script>
                                      document.getElementById("myBtn'.$row['obj_id'].'").addEventListener("click", function(){
                                      document.getElementById("load'.$row['obj_id'].'").style.display = "block";
                                    });
                                  </script>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';

      return $tabla;

    }

    /*------------------------- Valida Objetivo Estrategico --------------------------*/
    public function valida_objetivos_estrategicos(){
      if ($this->input->post()) {
          $post = $this->input->post();

          $aper_id = $this->security->xss_clean($post['aper_id']);
          $codigo = $this->security->xss_clean($post['codigo']);
          $descripcion = $this->security->xss_clean($post['descripcion']);
          $configuracion=$this->model_proyecto->configuracion_session();

          /*--------------- GUARDANDO OBJETIVO ESTRATEGICO ----------------*/
          $data_to_store = array(
              'aper_id' => $aper_id,
              'obj_codigo' => strtoupper($codigo),
              'obj_descripcion' => strtoupper($descripcion),
              'obj_gestion_inicio' => $configuracion[0]['conf_gestion_desde'],
              'obj_gestion_fin' => $configuracion[0]['conf_gestion_hasta'],
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('_objetivos_estrategicos',$data_to_store);
            $obj_id=$this->db->insert_id();
          /*---------------------------------------------------------------*/

        if(count($this->model_mestrategico->get_objetivos_estrategicos($obj_id))==1){
          $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE');
          redirect(site_url("").'/me/objetivos_estrategicos');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR');
          redirect(site_url("").'/me/objetivos_estrategicos');
        }

      } else {
          show_404();
      }
    }

    /*-------------- Update Objetivo Estrategico ------------------*/
    public function update_objetivos_estrategicos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $obj_id = $this->security->xss_clean($post['mobj_id']);
          $codigo = $this->security->xss_clean($post['mcodigo']);
          $descripcion = $this->security->xss_clean($post['mdescripcion']);
          $aper_id = $this->security->xss_clean($post['aper']);

         $update_form= array(
            'aper_id' => $aper_id,
            'obj_codigo' => $codigo,
            'obj_descripcion' => $descripcion,
            'fun_id' => $this->fun_id,
            'obj_estado' => 2
          );

        $this->db->where('obj_id', $obj_id);
        $this->db->update('_objetivos_estrategicos', $this->security->xss_clean($update_form));

        $form=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);
        if($form[0]['obj_estado']==2){
          $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE');
          redirect(site_url("").'/me/objetivos_estrategicos');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL MODIFICAR');
          redirect(site_url("").'/me/objetivos_estrategicos');
        }

      } else {
          show_404();
      }
    }

    /*-------------------- DELETE OBJETIVO ESTRATEGICO -----------------------*/
    function delete_objetivos_estrategicos(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $obj_id = $this->security->xss_clean($post['obj_id']);
          
          $update_bj= array(
           'obj_estado' => 3,
           'fun_id' => $this->fun_id
          );
          $this->db->where('obj_id', $obj_id);
          $this->db->update('_objetivos_estrategicos', $this->security->xss_clean($update_bj));
          /*-----------------------------------------------------------------*/

          $acciones=$this->model_mestrategico->list_acciones_estrategicas($obj_id);
          foreach($acciones  as $rowa){
            /*---------------------- Acciones Estrategicas ---------------------*/
            $update_ae= array(
             'acc_estado' => 3,
             'fun_id' => $this->fun_id
            );
            $this->db->where('obj_id', $obj_id);
            $this->db->update('_acciones_estrategicas', $this->security->xss_clean($update_ae));
            /*-----------------------------------------------------------------*/

            $rmp=$this->model_mestrategico->list_resultados_mplazo($rowa['acc_id']);
            foreach($rmp  as $rowmp){
              /*---------------------- Resultado de Mediano Plazo ---------------------*/
              $update_rmp= array(
               'rm_estado' => 3,
               'fun_id' => $this->fun_id
              );
              $this->db->where('acc_id', $rowa['acc_id']);
              $this->db->update('_resultado_mplazo', $this->security->xss_clean($update_rmp));
              /*-----------------------------------------------------------------*/


              /*---------------------- Producto Terminal ---------------------*/
              $update_ptm= array(
               'ptm_estado' => 3,
               'fun_id' => $this->fun_id
              );
              $this->db->where('rm_id', $rowmp['rm_id']);
              $this->db->update('_pterminal_mplazo', $this->security->xss_clean($update_ptm));
              /*-----------------------------------------------------------------*/

              }
          }

          $obj=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);
          if($obj[0]['obj_estado']==3){
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
    /*-------------------- GET OBJETIVO ESTRATEGICO -----------------------*/
    public function get_objetivos_estrategicos(){
        if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $obj_id = $post['obj_id'];
          $obj_id = $this->security->xss_clean($obj_id);

          $dato_obj = $this->model_mestrategico->get_objetivos_estrategicos($obj_id);

          $programas=$this->model_proyecto->list_prog();
          $prog='';
          $prog.='
                <section>
                  <select class="form-control" id="aper" name="aper" title="SELECCIONE PROGRAMA">
                    <option value="0">SELECCIONE PROGRAMA</option>';
                      foreach($programas as $row){
                        if($row['aper_id']==$dato_obj[0]['aper_id']){
                          $prog.='<option value='.$row['aper_id'].' selected>'.$row['aper_programa'].' '.$row['aper_descripcion'].'</option>';
                        }
                        else{
                          $prog.='<option value='.$row['aper_id'].'>'.$row['aper_programa'].' '.$row['aper_descripcion'].'</option>';
                        }
                      }
                    $prog.='       
                  </select>
                </section>';


          //caso para modificar el codigo de proyecto y actividades
          $result = array(
            'codigo' => $dato_obj[0]['obj_codigo'],
            'programa' => $prog,
            "descripcion" =>$dato_obj[0]['obj_descripcion']
          );

          echo json_encode($result);
        }else{
            show_404();
        }
    }
  /*=======================================================================*/
    
    /*----------------------- REPORTE OBJETIVOS ESTRATEGICOS -----------------------------*/
    public function reporte_objetivos_estrategicos($tipo){
      $data['mes'] = $this->mes_nombre();
      $data['objetivos_estrategicos']=$this->rep_objetivos_estrategicos();
      $this->load->view('admin/mestrategico/obj_estrategico/reporte_objetivos_estrategicos', $data);


/*      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("OBJETIVOS ESTRATEGICOS.pdf", array("Attachment" => false));*/
    }

    /*----- LISTA DE OBJETIVOS ESTRATEGICOS (REPORTE)----*/
    public function rep_objetivos_estrategicos(){
      $tabla='';
      $objetivos = $this->model_mestrategico->list_objetivos_estrategicos(); /// OBJETIVOS ESTRATEGICOS
      $configuracion=$this->model_resultado->configuracion_session(); /// Configuracion

      $tabla .='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                  <th style="width:1%;height:15px;color:#FFF;">#</th>
                  <th style="width:15%;color:#FFF;">OBJETIVO ESTRATEGICO</th>
                  <th style="width:84%;color:#FFF;">ACCI&Oacute;N ESTRATEGICA</th>
                </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($objetivos as $rowo){
                  $nro++;
                  $acciones = $this->model_mestrategico->list_acciones_estrategicas($rowo['obj_id']); 
                  $tabla.='<tr>';
                    $tabla.='<td style="width:1%;;height:12px;" align=center>'.$nro.'</td>';
                    $tabla.='<td style="width:15%;">'.$rowo['obj_codigo'].'.- '.$rowo['obj_descripcion'].'</td>';
                    $tabla.='<td style="width:84%;">';
                    if(count($acciones)!=0){
                      $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
                                <thead>
                                  <tr style="font-size: 7px;" bgcolor=#d9d7d7 align=center>
                                    <th style="width:1%;height:15px;">#</th>
                                    <th style="width:37%;">DESCRIPCI&Oacute;N</th>
                                    <th style="width:60%;">VINCULACI&Oacute;N PDES</th>
                                  </tr>
                                </thead>
                                <tbody>';
                                $nra=0;
                                foreach($acciones as $rowa){
                                  $pdes=$this->model_proyecto->datos_pedes($rowa['pdes_id']);
                                  $nra++;
                                  $tabla.='<tr>';
                                    $tabla.='<td style="width:1%;">'.$nra.'</td>';
                                    $tabla.='<td style="width:37%;">'.$rowa['acc_codigo'].'.- '.$rowa['acc_descripcion'].'</td>';
                                    $tabla.='<td style="width:60%;">';
                                      $tabla.='<ul>
                                                <li type="circle"><b>PILAR : </b>'.$pdes[0]['pilar'].'</li>
                                                <li type="square"><b>META : </b>'.$pdes[0]['meta'].'</li>
                                                <li type="disc"><b>RESULTADO : </b>'.$pdes[0]['resultado'].'</li>
                                                <li type="disc"><b>ACCI&Oacute;N : </b>'.$pdes[0]['accion'].'</li>
                                                </ul>';
                                    $tabla.='</td>';
                                  $tabla.='</tr>';
                                }
                      $tabla.=' </tbody>
                              </table>';
                    }
                    $tabla.='</td>';
                  $tabla.='</tr>';
                }
      $tabla .='</tbody>
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






    /*------------------------------- OBJETIVOS ESTRATEGICOS ---------------------*/
    function objetivos_estrategicos_institucionales($tipo){
      $gestion = $this->session->userdata('gestion');
      $configuracion=$this->model_proyecto->configuracion_session();
      if($tipo==1){
        $titulo='PEI';
        $tabla=$this->genera_objetivo_estrategico_pei();
      }
      else{
        $titulo='PEI - POA';
        $tabla=$this->genera_objetivo_estrategico_poa_pei();
      }
      $html = '
     <html>
        <head>' . $this->estilo_vertical() . '
         <style>
           @page { margin: 130px 20px; }
           #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
           #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 65px;}
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
                          <b>REPORTE : </b>'.$titulo.' : '.$configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'].'<br>
                          </FONT>
                      </td>
                      <td width=20%; text-align:center;"">
                      </td>
                  </tr>
              </table>
         </div>
         <div id="footer">
           <table border="0" cellpadding="0" cellspacing="0" class="tabla">
              <tr>
                  <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td><p class="page">Pagina </p></td>
              </tr>
          </table>
         </div>
         <div id="content">
           <p><div style="page-break-after;">'.$tabla.'</div></p>
         </div>
       </body>
       </html>';
      return $html;
    }


    public function genera_objetivo_estrategico_pei(){
      $objetivos = $this->model_mestrategico->list_objetivos_estrategicos(); /// OBJETIVOS ESTRATEGICOS
      $configuracion=$this->model_resultado->configuracion_session(); /// Configuracion
      
      $tabla = '';
        $tabla .='<br>
            <div class="mv" style="text-align:justify">
              <b>MISI&Oacute;N INSTITUCIONAL: </b>'.$configuracion[0]['conf_mision'].'
            </div><br>
            <div class="mv" style="text-align:justify">
              <b>VISI&Oacute;N INSTITUCIONAL: </b>'.$configuracion[0]['conf_vision'].'
            </div><br><br>';
        if(count($objetivos)!=0){
            $nro_obj=0;
            foreach($objetivos as $rowo){
              $acciones = $this->model_mestrategico->list_acciones_estrategicas($rowo['obj_id']);
              $nro_obj++;
              $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla .='<thead>
                            <tr class="modo1" bgcolor="#656262" align="left">';
                    $tabla .='<th colspan=5><font color="#fff" size=1>OBJETIVO ESTRAT&Eacute;GICO '.$nro_obj.'.- '.$rowo['obj_descripcion'].'</font></th>';
                  $tabla .='</tr>
                          </thead>';
                if(count($acciones)!=0){
                    $nro_a=0;
                    foreach($acciones as $rowa){
                      $pdes=$this->model_proyecto->datos_pedes($rowa['pdes_id']);
                      $nro_a++;
                      $tabla .='<tbody>
                                <tr class="modo1">';
                                  $tabla .='<td style="width:20%;" bgcolor="#dbf7db">'.$nro_obj.'.'.$nro_a.'.- ACCI&Oacute;N ESTRATEGICA  '.$rowa['acc_descripcion'].'</td>';
                                  $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>PILAR :</b> '.$pdes[0]['pilar'].'</td>';
                                  $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>META :</b> '.$pdes[0]['meta'].'</td>';
                                  $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>RESULTADO :</b> '.$pdes[0]['resultado'].'</td>';
                                  $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'</td>
                                </tr>
                                <tr class="modo1">';
                        $tabla .='<td colspan=5 style="width:80%;">';
                          $resultados = $this->model_mestrategico->list_resultados_mplazo($rowa['acc_id']);
                          if(count($resultados)!=0){
                            $tabla .='<table>';
                              $tabla .='<tr class="modo1" bgcolor="#c3bebe">';
                                $tabla .='<td style="width:15%;">RESULTADO</td>';
                                $tabla .='<td style="width:10%;">INDICADOR</td>';
                                $tabla .='<td style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</td>';
                                $tabla .='<td style="width:15%;">TEMPORALIDAD</td>';
                                $tabla .='<td style="width:45%;" align=center>PRODUCTOS TERMINALES</td>';
                              $tabla .='</tr>';
                              foreach($resultados as $rowr){
                                $tabla .='<tr class="modo1" bgcolor="#f0f0f0">';
                                  $tabla .='<td>'.$rowr['rm_resultado'].'</td>';
                                  $tabla .='<td>'.$rowr['rm_indicador'].'</td>';
                                  $tabla .='<td>'.$rowr['rm_fuente_verificacion'].'</td>';
                                  $tabla .='<td>'.$this->tabla_temporalidad($this->temporalidad($rowr['rm_id'])).'</td>';
                                  $tabla .='<td>';
                                    $pterminal = $this->model_mestrategico->list_pterminal_mplazo($rowr['rm_id']);
                                    if(count($pterminal)!=0){
                                    $tabla .='<table class="tabla">';  
                                      $tabla .='<tr class="modo1">';
                                        $tabla .='<td style="width:20%;">PRODUCTO TERMINAL</td>';
                                        $tabla .='<td style="width:20%;">INDICADOR</td>';
                                        $tabla .='<td style="width:20%;">MEDIO DE VERIFICACI&Oacute;N</td>';
                                        $tabla .='<td style="width:40%;">TEMPORALIDAD</td>';
                                      $tabla .='</tr>';
                                      foreach($pterminal as $rowp){
                                        $tabla .='<tr class="modo1">';
                                          $tabla .='<td>'.$rowp['ptm_producto'].'</td>';
                                          $tabla .='<td>'.$rowp['ptm_indicador'].'</td>';
                                          $tabla .='<td>'.$rowp['ptm_fuente_verificacion'].'</td>';
                                          $tabla .='<td>'.$this->tabla_temporalidad_pt($this->temporalidad_pt($rowp['ptm_id'])).'</td>';
                                        $tabla .='</tr>';
                                      }
                                    $tabla .='</table>';
                                    }
                                  $tabla .='</td>';
                                $tabla .='</tr>';
                              }
                            $tabla .='</table>';
                          }
                        $tabla .='</td>';
                      $tabla .='</tr></tbody>';
                    }
                    
                }
              $tabla .='</table><br>';
            }
        } 
      return $tabla;
  }

    public function genera_objetivo_estrategico_poa_pei(){
      $objetivos = $this->model_mestrategico->list_objetivos_estrategicos(); /// OBJETIVOS ESTRATEGICOS
      $configuracion=$this->model_resultado->configuracion_session(); /// Configuracion
      
      $tabla = '';
      $tabla .='
            <div class="mv" style="text-align:justify">
              <b>MISI&Oacute;N INSTITUCIONAL: </b>'.$configuracion[0]['conf_mision'].'
            </div><br>
            <div class="mv" style="text-align:justify">
              <b>VISI&Oacute;N INSTITUCIONAL: </b>'.$configuracion[0]['conf_vision'].'
            </div><br>';
        if(count($objetivos)!=0){
            $nro_obj=0;
            foreach($objetivos as $rowo){
              $acciones = $this->model_mestrategico->list_acciones_estrategicas($rowo['obj_id']);
              $nro_obj++;
              $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla .='<thead><tr class="modo1" bgcolor="#656262" align="left">';
                  $tabla .='<th colspan=6><font color="#fff" size=1>OBJETIVO ESTRAT&Eacute;GICA '.$nro_obj.'.- '.$rowo['obj_descripcion'].'</font></th>';
                $tabla .='</tr></thead>';
                if(count($acciones)!=0){
                    $nro_a=0;
                    foreach($acciones as $rowa){
                      $pdes=$this->model_proyecto->datos_pedes($rowa['pdes_id']);
                      $nro_a++;
                      $tabla .='<tbody>
                                  <tr class="modo1">';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db">'.$nro_obj.'.'.$nro_a.'.- ACCI&Oacute;N ESTRATEGICA </td>';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db">'.$rowa['acc_descripcion'].'</td>';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>PILAR :</b> '.$pdes[0]['pilar'].'</td>';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>META :</b> '.$pdes[0]['meta'].'</td>';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>RESULTADO :</b> '.$pdes[0]['resultado'].'</td>';
                          $tabla .='<td style="width:20%;" bgcolor="#dbf7db"><b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'</td>
                                  </tr>
                                </tbody>';
                          $resultados = $this->model_mestrategico->list_resultados_mplazo($rowa['acc_id']);
                          if(count($resultados)!=0){
                                $tabla .='<tr class="modo1" bgcolor="#c3bebe">';
                                  $tabla .='<td style="width:10%;">RESULTADO</td>';
                                  $tabla .='<td style="width:10%;">PRODUCTO TERMINAL</td>';
                                  $tabla .='<td style="width:10%;">INDICADOR</td>';
                                  $tabla .='<td style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</td>';
                                  $tabla .='<td style="width:20%;">TEMPORALIDAD MEDIANO PLAZO</td>';
                                  $tabla .='<td style="width:40%;">TEMPORALIDAD CORTO PLAZO</td>';
                                $tabla .='</tr>';
                              foreach($resultados as $rowr){
                                $tabla .='<tr class="modo1">';
                                  $tabla .='<td>'.$rowr['rm_resultado'].'</td>';
                                  $tabla .='<td></td>';
                                  $tabla .='<td>'.$rowr['rm_indicador'].'</td>';
                                  $tabla .='<td>'.$rowr['rm_fuente_verificacion'].'</td>';
                                  $tabla .='<td>'.$this->tabla_temporalidad($this->temporalidad($rowr['rm_id'])).'</td>';
                                  $tabla .='<td>';
                                    $tabla .='<table class="tabla">';  
                                        $tabla .='<tr class="modo1" bgcolor="#c3bebe">';
                                          $tabla .='<td style="width:8%;"></td>';
                                          $tabla .='<td style="width:8%;">ENE.</td>';
                                          $tabla .='<td style="width:8%;">FEB.</td>';
                                          $tabla .='<td style="width:8%;">MAR.</td>';
                                          $tabla .='<td style="width:8%;">ABR.</td>';
                                          $tabla .='<td style="width:8%;">MAY.</td>';
                                          $tabla .='<td style="width:8%;">JUN.</td>';
                                          $tabla .='<td style="width:8%;">JUL.</td>';
                                          $tabla .='<td style="width:8%;">AGO.</td>';
                                          $tabla .='<td style="width:8%;">SEPT.</td>';
                                          $tabla .='<td style="width:8%;">OCT.</td>';
                                          $tabla .='<td style="width:8%;">NOV.</td>';
                                          $tabla .='<td style="width:8%;">DIC.</td>';
                                        $tabla .='</tr>';
                                        $temp_rcp=$this->temporalidad_rcp($rowr['rm_id']);
                                        $tabla .='<tr class="modo1">';
                                          $tabla .='<td bgcolor="#c3bebe">P.</td>';
                                          for ($j=1; $j <=12 ; $j++) { 
                                            $tabla .='<td bgcolor="#f0f0f0">'.$temp_rcp[1][$j].'</td>';
                                          }
                                        $tabla .='</tr>';
                                      $tabla .='</table>';
                                  $tabla .='</td>';
                                $tabla .='</tr>';

                                $pterminal = $this->model_mestrategico->list_pterminal_mplazo($rowr['rm_id']);
                                if(count($pterminal)!=0){
                                    foreach($pterminal as $rowp){
                                      $tabla .='<tr class="modo1">';
                                        $tabla .='<td></td>';
                                        $tabla .='<td>'.$rowp['ptm_producto'].'</td>';
                                        $tabla .='<td>'.$rowp['ptm_indicador'].'</td>';
                                        $tabla .='<td>'.$rowp['ptm_fuente_verificacion'].'</td>';
                                        $tabla .='<td>'.$this->tabla_temporalidad_pt($this->temporalidad_pt($rowp['ptm_id'])).'</td>';
                                        $tabla .='<td>';
                                          $tabla .='<table class="tabla">';  
                                              $tabla .='<tr class="modo1" bgcolor="#c3bebe">';
                                                $tabla .='<td style="width:8%;"></td>';
                                                $tabla .='<td style="width:8%;">ENE.</td>';
                                                $tabla .='<td style="width:8%;">FEB.</td>';
                                                $tabla .='<td style="width:8%;">MAR.</td>';
                                                $tabla .='<td style="width:8%;">ABR.</td>';
                                                $tabla .='<td style="width:8%;">MAY.</td>';
                                                $tabla .='<td style="width:8%;">JUN.</td>';
                                                $tabla .='<td style="width:8%;">JUL.</td>';
                                                $tabla .='<td style="width:8%;">AGO.</td>';
                                                $tabla .='<td style="width:8%;">SEPT.</td>';
                                                $tabla .='<td style="width:8%;">OCT.</td>';
                                                $tabla .='<td style="width:8%;">NOV.</td>';
                                                $tabla .='<td style="width:8%;">DIC.</td>';
                                              $tabla .='</tr>';
                                              $temp_ptcp=$this->temporalidad_ptcp($rowp['ptm_id']);
                                                $tabla .='<tr class="modo1">';
                                                  $tabla .='<td bgcolor="#c3bebe">P.</td>';
                                                  for ($j=1; $j <=12 ; $j++) { 
                                                    $tabla .='<td bgcolor="#f0f0f0">'.$temp_ptcp[$j].'</td>';
                                                  }
                                                $tabla .='</tr>';
                                            $tabla .='</table>';
                                        $tabla .='</td>';
                                      $tabla .='</tr>';
                                    }
                                }
                              }
                          }
                    }
                }
              $tabla .='</table>';
            }
        }
          
      return $tabla;
  }
  /*----------------------- TEMPORALIDAD RESULTADO DE MEDIANO PLAZO ---------------------*/
      function temporalidad($rm_id){
      $resultado=$this->model_mestrategico->get_resultado_mplazo($rm_id); 
      $programado=$this->model_mestrategico->get_resultado_mplazo_programado($rm_id);
      $configuracion=$this->model_proyecto->configuracion_session();
      $tabla = '';
      $nro=0;
      foreach($programado as $row) {
        $nro++;
        $matriz [1][$nro]=$row['g_id'];
        $matriz [2][$nro]=$row['rmp_prog'];
      }

      $g=$configuracion[0]['conf_gestion_desde'];
      for($j = 1; $j<=5; $j++){
        $matriz_r[1][$j]=$g;
        $matriz_r[2][$j]='0';  //// P
        $matriz_r[3][$j]='0';  //// PA
        $matriz_r[4][$j]='0';  //// %PA
        $g++;
      }

      for($i = 1 ;$i<=$nro ;$i++){
        for($j = 1 ;$j<=5 ;$j++){
          if($matriz[1][$i]==$matriz_r[1][$j]){
              $matriz_r[2][$j]=round($matriz[2][$i],2);
          }
        }
      }

      $pa=0;
      for($j = 1 ;$j<=5 ;$j++){
        $pa=$pa+$matriz_r[2][$j];
        $matriz_r[3][$j]=$pa+$resultado[0]['rm_linea_base'];
        if($resultado[0]['rm_meta']!=0){
          $matriz_r[4][$j]=round(((($pa+$resultado[0]['rm_linea_base'])/$resultado[0]['rm_meta'])*100),2);
        }
      }
      return $matriz_r;
    }

    function tabla_temporalidad($matriz){ 
      $tabla = '';
      $tabla .='<table>';
        $tabla .='<tr bgcolor=#1c7368 class="modo1">';
            $tabla .='<th></th>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<th><font color=white>'.$matriz[1][$j].'</font></th>';
          }
        $tabla .='</tr>';
        $tabla .='<tr class="modo1">';
            $tabla .='<td>P.</td>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<td>'.$matriz[2][$j].'</td>';
          }
        $tabla .='</tr>';
      $tabla .='</table>';

      return $tabla;
    }
    /*-------------------------- RESULTADO DE CORTO PLAZO -----------------------------*/
    function temporalidad_rcp($rm_id){
      for ($i=0; $i <=12 ; $i++) { 
        $mes[$i]='mes'.$i.'';
        $prog[1][$i]=0; /// p
        $prog[2][$i]=0; /// pa
        $prog[3][$i]=0; /// %pa
      }

      $resultado=$this->model_mestrategico->get_resultado_mplazo($rm_id); 
      $programado=$this->model_mestrategico->get_resultado_mplazo_programado_gestion($rm_id);

      
      if(count($programado)!=0){
        $programado_mensual=$this->model_mestrategico->get_resultado_cplazo_programado($programado[0]['rmp_id']);
        if(count($programado_mensual)!=0){

          $pa=0;
          for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=$programado_mensual[0][$mes[$i]];
            $pa=$pa+$prog[1][$i];
            $prog[2][$i]=$pa+$resultado[0]['rm_linea_base'];
            if($resultado[0]['rm_meta']!=0){
              $prog[3][$j]=round((($prog[2][$i]/$resultado[0]['rm_meta'])*100),2);
            }
          }
        
        }
      }
      return $prog;
    }

  /*-------------------------- PRODUCTO TERMINAL DE MEDIANO PLAZO ----------------------*/
    function temporalidad_pt($ptm_id){
      $pterminal=$this->model_mestrategico->get_pterminal_mplazo($ptm_id); 
      $programado=$this->model_mestrategico->get_pterminal_mplazo_programado($ptm_id);
      $configuracion=$this->model_proyecto->configuracion_session();
      $tabla = '';
      $nro=0;
      foreach($programado as $row) {
        $nro++;
        $matriz [1][$nro]=$row['g_id'];
        $matriz [2][$nro]=$row['ptmp_prog'];
      }

      $g=$configuracion[0]['conf_gestion_desde'];
      for($j = 1; $j<=5; $j++){
        $matriz_r[1][$j]=$g;
        $matriz_r[2][$j]='0';  //// P
        $matriz_r[3][$j]='0';  //// PA
        $matriz_r[4][$j]='0';  //// %PA
        $g++;
      }

      for($i = 1 ;$i<=$nro ;$i++){
        for($j = 1 ;$j<=5 ;$j++){
          if($matriz[1][$i]==$matriz_r[1][$j]){
              $matriz_r[2][$j]=round($matriz[2][$i],2);
          }
        }
      }

      $pa=0;
      for($j = 1 ;$j<=5 ;$j++){
        $pa=$pa+$matriz_r[2][$j];
        $matriz_r[3][$j]=$pa+$pterminal[0]['ptm_linea_base'];
        if($pterminal[0]['ptm_meta']!=0){
          $matriz_r[4][$j]=round(((($pa+$pterminal[0]['ptm_linea_base'])/$pterminal[0]['ptm_meta'])*100),2);
        }
      }
      return $matriz_r;
    }

    function tabla_temporalidad_pt($matriz){ 
      $tabla = '';
      $tabla .='<table class="table table-bordered">';
        $tabla .='<tr bgcolor=#1c7368 class="modo1">';
            $tabla .='<th></th>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<th><font color=white>'.$matriz[1][$j].'</font></th>';
          }
        $tabla .='</tr>';
        $tabla .='<tr class="modo1">';
            $tabla .='<td>P.</td>';
          for ($j=1; $j <=5 ; $j++) { 
            $tabla .='<td>'.$matriz[2][$j].'</td>';
          }
        $tabla .='</tr>';
      $tabla .='</table>';

      return $tabla;
    }

  /*---------------------------- PRODUCTO TERMINAL DE CORTO PLAZO ---------------------------*/
  function temporalidad_ptcp($ptm_id){
    for ($i=0; $i <=12 ; $i++) { 
        $mes[$i]='mes'.$i.'';
        $prog[$i]=0;
    }

    $pterminal=$this->model_mestrategico->get_pterminal_mplazo($ptm_id); 
    $programado=$this->model_mestrategico->get_pterminal_mplazo_programado_gestion($ptm_id);
    

    if(count($programado)!=0){
      $programado_mensual=$this->model_mestrategico->get_pterminal_cplazo_programado($programado[0]['ptmp_id']);
      if(count($programado_mensual)!=0){

        for ($i=1; $i <=12 ; $i++) { 
          $prog[$i]=$programado_mensual[0][$mes[$i]];
        }
        $prog[0]=$programado_mensual[0]['programado_total'];
      }
    }
      
    return $prog;
  }

  /*-------------------------------- REPORTE VINCULACION PEI ------------------------------*/
    public function reporte_vinculacion($obj_id){
      $data['vinculacion']=$this->vinculacion_pei_poa($obj_id);

      $this->load->view('admin/mestrategico/obj_estrategico/print_vinculacion', $data);
    }

    function vinculacion_pei_poa($obj_id){

      $tabla ='';
       $tabla .='
       <style>
        .table{font-size: 10px;
          width: 100%;
          max-width:1550px;;
          overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          font-size: 9px;
        }
        td{
          padding: 1.4px;
          font-size: 9px;
        }
      </style>
      '.$this->productos_vinculados($obj_id).'';
                
      return $tabla;
    }

    public function productos_vinculados($obj_id){

      $obj=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);
      $acciones=$this->model_mestrategico->list_acciones_estrategicas($obj_id);
      $tabla ='';
      $tabla .='<table id="dt_basic" class="table table-bordered" style="width:100%;" align="center">';
        $tabla .='<thead>';
        $tabla .='<tr>
                    <td colspan=6>
                      <table width="100%" border=0 style="width:100%;">
                        <tr>
                          <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"></center>
                          </td>
                          <td width=50%; class="titulo_pdf" align=left>
                              <FONT FACE="courier new" size="1">
                              <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                              <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                              <b>REPORTE : </b> VINCULACION PEI - POA<br>
                              <b>OBJETIVO ESTRATEGICO : </b>'.$obj[0]['obj_descripcion'].'
                              </FONT>
                          </td>
                          <td width=30%;" align=right>
                            <hr>
                            <table id="dt_basic" class="table table-bordered" style="width:100%;" align="center">
                                <tr>
                                  <td style="width:50%;"><font size=1>ACCI&Oacute;N ESTRATEGICO</font></td>
                                  <td style="width:50%;" bgcolor="#7dde7d"></td>
                                </tr>
                                <tr>
                                  <td><font size=1>RESULTADO CP</font></td>
                                  <td bgcolor="#d0f9d0"></td>
                                </tr>
                                <tr>
                                  <td><font size=1>PROD. TERMINAL CP</font></td>
                                  <td bgcolor="#ead6a0"></td>
                                </tr>
                                <tr>
                                  <td><font size=1>ACCI&Oacute;N OPE.</font></td>
                                  <td bgcolor="#ecd9d4"></td>
                                </tr>
                                <tr>
                                  <td><font size=1>PRODUCTO</font></td>
                                  <td bgcolor="#eff4f5"></td>
                                </tr>
                            </table>
                            <hr>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr class="modo1" align="left">
                    <td colspan=6><hr></td>
                  </tr>
                  <tr class="modo1" bgcolor="#1c7368" align="center">
                    <td style="width:10%;" height="40"><font color=#fff>ACCI&Oacute;N ESTRATEGICA</font></td>
                    <td style="width:10%;"><font color=#fff>RESULTADO CP</font></td>
                    <td style="width:10%;"><font color=#fff>PRODUCTO TERMINAL CP</font></td>
                    <td style="width:10%;"><font color=#fff>ACCI&Oacute;N OPERATIVA</font></td>
                    <td style="width:30%;"><font color=#fff>PRODUCTO</font></td>
                    <td style="width:30%;" align=center><font color=#fff>TEMPORALIDAD</font></td>
                  </tr>
                  </thead>
                    <tbody>';
      foreach($acciones as $rowa) {
        $tabla .='<tr class="modo1" bgcolor="#7dde7d">
                    <td colspan=6 height="25"><b>ACCI&Oacute;N ESTRAT&Eacute;GICA : '.$rowa['obj_id'].'.'.$rowa['acc_id'].'.-'.$rowa['acc_descripcion'].'</b></td>
                  </tr>';
        $resultado=$this->model_mestrategico->list_resultados_mplazo($rowa['acc_id']);
        if(count($resultado)!=0){
          foreach($resultado as $rowr) {
            $tabla .='  <tr class="modo1" bgcolor="#f1f1f1">
                          <td bgcolor="#7dde7d" height="25"></td>
                          <td colspan=5 bgcolor="#d0f9d0" height="25">'.$rowa['acc_id'].'.'.$rowr['rm_id'].'.-'.$rowr['rm_resultado'].'</td>
                        </tr>';
            $pterminal=$this->model_mestrategico->list_pterminal_cplazo($rowr['rm_id']);
            foreach($pterminal as $rowt) {
              $tabla.='<tr class="modo1" bgcolor="#f1f1f1"1>
                          <td bgcolor="#7dde7d" height="25"></td>
                          <td bgcolor="#d0f9d0" height="25"></td>
                          <td colspan=4 bgcolor="#ead6a0" height="25">'.$rowr['rm_id'].'.'.$rowt['ptm_id'].'.-'.$rowt['ptm_producto'].'</td>
                        </tr>';
              $group_productos=$this->model_mestrategico->group_pterminal($rowt['ptm_id']);
              foreach($group_productos as $rowpr) {
                $list_prod=$this->model_mestrategico->list_vin_pterminal($rowpr['proy_id'],$rowt['ptm_id']);
                $proyecto = $this->model_proyecto->get_id_proyecto($rowpr['proy_id']); ////// DATOS DEL PROYECTO
                $tabla .='<tr class="modo1" bgcolor="#f1f1f1">
                            <td bgcolor="#7dde7d" height="25"></td>
                            <td bgcolor="#d0f9d0" height="25"></td>
                            <td bgcolor="#ead6a0" height="25"></td>
                            <td colspan=3 bgcolor="#ecd9d4" height="25">'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</td>
                          </tr>';
                foreach($list_prod as $rowprod) {
                  $temp=$this->temporalizacion_productos($rowprod['prod_id']);
                  $tabla .='<tr class="modo1" bgcolor="#f1f1f1">
                              <td bgcolor="#7dde7d" height="25"></td>
                              <td bgcolor="#d0f9d0" height="25"></td>
                              <td bgcolor="#ead6a0" height="25"></td>
                              <td bgcolor="#ecd9d4" height="25"></td>
                              <td>'.$rowt['ptm_id'].'.'.$rowprod['prod_id'].'.-'.$rowprod['prod_producto'].'</td>
                              <td>
                                <table table id="dt_basic" class="table table-bordered" style="width:100%;" align="center">
                                        <thead>
                                        <tr class="modo1">
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>P/E</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>ENE.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>FEB.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>MAR.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>ABR.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>MAY.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>JUN.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>JUL.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>AGO.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>SEPT.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>OCT.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>NOV.</font></th>
                                          <th bgcolor="#a5a2a2"><font color="#fff" size=1>DIC.</font></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="modo1">
                                          <td title="PROGRAMADO">P.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            $tabla .='<td><font size=1>'.round($temp[1][$i],2).'</font></td>';
                                          }
                                          $tabla .='
                                        </tr>
                                        </tbody>
                                      </table>
                              </td>
                            </tr>';
                }
              }
            }
          }

          
        }
        
      }
      $tabla .='</tbody>
                  </table>';
      return $tabla;
    }

    /*------------------------- EXPORTAR ALINACION PEI - POA ---------------------------*/
    public function exportar_alineacion($obj_id){
      $obj=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);

      date_default_timezone_set('America/Lima');
     
      $fecha = date("d-m-Y H:i:s");
      $operaciones=$this->alineacion_pei_poa($obj_id);
      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=OBJETIVO_ESTRATEGICO".$obj_id."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$operaciones."";
    }

    public function alineacion_pei_poa($obj_id){

      $obj=$this->model_mestrategico->get_objetivos_estrategicos($obj_id);
      $acciones=$this->model_mestrategico->list_acciones_estrategicas($obj_id);
      $tabla ='';
      $tabla .='<style>
                  table{font-size: 9px;
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
      $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
        $tabla.='<tr class="modo1" bgcolor="#ddf1ee">
                  <td>
                      <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                          <tr>
                            <td style="width:50%;"><font size=1>ACCI&Oacute;N ESTRATEGICO</font></td>
                            <td style="width:50%;" bgcolor="#7dde7d"></td>
                          </tr>
                          <tr>
                            <td><font size=1>RESULTADO CP</font></td>
                            <td bgcolor="#d0f9d0"></td>
                          </tr>
                          <tr>
                            <td><font size=1>PROD. TERMINAL CP</font></td>
                            <td bgcolor="#ead6a0"></td>
                          </tr>
                          <tr>
                            <td><font size=1>ACCI&Oacute;N OPE.</font></td>
                            <td bgcolor="#ecd9d4"></td>
                          </tr>
                          <tr>
                            <td><font size=1>PRODUCTO</font></td>
                            <td bgcolor="#eff4f5"></td>
                          </tr>
                      </table>
                  </td>
                  <td colspan="3"><b><FONT FACE="courier new" size="2">  OBJETIVO ESTRATEGICO '.$obj_id.' : '.mb_convert_encoding(''.strtoupper($obj[0]['obj_descripcion']), 'cp1252', 'UTF-8').'</font></b></td>
                </tr>';
      $tabla.='</table>';
      $tabla .='<br>';
      $tabla .='<table border="1" cellpadding="0" cellspacing="0" class="tabla">';
        $tabla .='<thead>
                  <tr class="modo1" bgcolor="#1c7368" align="center">
                    <td style="width:10%;" height="40"><font color=#fff>ACCI&Oacute;N ESTRATEGICA</font></td>
                    <td style="width:10%;"><font color=#fff>RESULTADO CP</font></td>
                    <td style="width:10%;"><font color=#fff>PRODUCTO TERMINAL CP</font></td>
                    <td style="width:10%;"><font color=#fff>ACCI&Oacute;N OPERATIVA</font></td>
                    <td style="width:10%;"><font color=#fff>PRODUCTO</font></td>
                  </tr>
                  </thead>
                    <tbody>';
      foreach($acciones as $rowa) {
        $tabla .='<tr class="modo1" bgcolor="#7dde7d">
                    <td colspan=5 height="25"><b>ACCI&Oacute;N ESTRAT&Eacute;GICA : '.$rowa['obj_id'].'.'.$rowa['acc_id'].'.-'.mb_convert_encoding(''.strtoupper($rowa['acc_descripcion']), 'cp1252', 'UTF-8').'</b></td>
                  </tr>';
        $resultado=$this->model_mestrategico->list_resultados_mplazo($rowa['acc_id']);
        if(count($resultado)!=0){
          foreach($resultado as $rowr) {
            $tabla .='  <tr class="modo1" bgcolor="#f1f1f1">
                          <td bgcolor="#7dde7d" height="25"></td>
                          <td colspan=4 bgcolor="#d0f9d0" height="25">'.$rowa['acc_id'].'.'.$rowr['rm_id'].'.-'.mb_convert_encoding(''.strtoupper($rowr['rm_resultado']), 'cp1252', 'UTF-8').'</td>
                        </tr>';
            $pterminal=$this->model_mestrategico->list_pterminal_cplazo($rowr['rm_id']);
            foreach($pterminal as $rowt) {
              $tabla.='<tr class="modo1" bgcolor="#f1f1f1"1>
                          <td bgcolor="#7dde7d" height="25"></td>
                          <td bgcolor="#d0f9d0" height="25"></td>
                          <td colspan=3 bgcolor="#ead6a0" height="25">'.$rowr['rm_id'].'.'.$rowt['ptm_id'].'.-'.mb_convert_encoding(''.strtoupper($rowt['ptm_producto']), 'cp1252', 'UTF-8').'</td>
                        </tr>';
              $group_productos=$this->model_mestrategico->group_pterminal($rowt['ptm_id']);
              foreach($group_productos as $rowpr) {
                $list_prod=$this->model_mestrategico->list_vin_pterminal($rowpr['proy_id'],$rowt['ptm_id']);
                $proyecto = $this->model_proyecto->get_id_proyecto($rowpr['proy_id']); ////// DATOS DEL PROYECTO
                $tabla .='<tr class="modo1" bgcolor="#f1f1f1">
                            <td bgcolor="#7dde7d" height="25"></td>
                            <td bgcolor="#d0f9d0" height="25"></td>
                            <td bgcolor="#ead6a0" height="25"></td>
                            <td bgcolor="#ecd9d4" height="25">'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(''.strtoupper($proyecto[0]['proy_nombre']), 'cp1252', 'UTF-8').'</td>
                            <td bgcolor="#ecd9d4" height="25"></td>
                          </tr>';
                          foreach($list_prod as $rowprod) {
                          $tabla .='<tr class="modo1" bgcolor="#f1f1f1">
                                      <td bgcolor="#7dde7d" height="25"></td>
                                      <td bgcolor="#d0f9d0" height="25"></td>
                                      <td bgcolor="#ead6a0" height="25"></td>
                                      <td bgcolor="#ecd9d4" height="25"></td>
                                      <td>'.$rowt['ptm_id'].'.'.$rowprod['prod_id'].'.-'.mb_convert_encoding(''.strtoupper($rowprod['prod_producto']), 'cp1252', 'UTF-8').'</td>
                                    </tr>';
                        }
              }
            }
          }

          
        }
        
      }
      $tabla .='</tbody>
                  </table>';
      return $tabla;
    }

    /*------------------------- TEMPORALIZACION DE PRODUCTOS ---------------------------*/
    public function temporalizacion_productos($prod_id){
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
        }
      }
      
      return $matriz;
    }

   /*-- RESULTADO FINAL --*/
   /*--- Adicionar Resultado ----*/
    function valida_add_rfinal(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $obj_id = $this->security->xss_clean($post['obj_id']);
          $codigo = $this->security->xss_clean($post['cod']);
          $resultado = $this->security->xss_clean($post['resultado']);
          $indicador = $this->security->xss_clean($post['indicador']);
          $meta = $this->security->xss_clean($post['meta']);
          $tp_indi = $this->security->xss_clean($post['tipo_i']);
          $conf=$this->model_proyecto->configuracion_session();

          $data_to_store = array( 
            'obj_id' => $obj_id,
            'rf_cod' => $codigo,
            'rf_resultado' => strtoupper($resultado),
            'rf_indicador' => strtoupper($indicador),
            'rf_meta' => $meta,
            'indi_id' => $tp_indi,
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_resultados_finales', $data_to_store);
          $rf_id = $this->db->insert_id();

          for ($i=$conf[0]['conf_gestion_desde']; $i <=$conf[0]['conf_gestion_hasta'] ; $i++) { 
            if($this->security->xss_clean($post[$i])!=0){
              $data_to_store2 = array( 
                'rf_id' => $rf_id,
                'g_id' => $i,
                'temp_prog' => $this->security->xss_clean($post[$i]),
              );
              $this->db->insert('_resultados_finales_temporalidad', $data_to_store2);
            }
          }

          if(count($this->model_mestrategico->get_resultado_final($rf_id))==1){
              $this->session->set_flashdata('success','Se registro correctamente !!!'); 
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR'); 
          }
          redirect(site_url("").'/me/objetivos_estrategicos');
      } else {
          show_404();
      }
    }

    /*-------------------- GET RESULTADO FINAL -----------------------*/
    public function get_resultado_final(){
        if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $rf_id = $this->security->xss_clean($post['rf_id']);

          $dato_rfinal = $this->model_mestrategico->get_resultado_final($rf_id);
          
          $result = array(
            'resultado' => $dato_rfinal
          );

          echo json_encode($result);
        }else{
            show_404();
        }
    }

    /*--- Modificar Resultado ----*/
    function valida_update_rfinal(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $obj_id = $this->security->xss_clean($post['obj_id']);
          $rf_id = $this->security->xss_clean($post['rf_id']);
          $codigo = $this->security->xss_clean($post['mcod']);
          $resultado = $this->security->xss_clean($post['mresultado']);
          $indicador = $this->security->xss_clean($post['mindicador']);
          $meta = $this->security->xss_clean($post['mmeta']);
          $tp_indi = $this->security->xss_clean($post['mtipo_i']);
          $conf=$this->model_proyecto->configuracion_session();

          $update_rf = array( 
            'rf_cod' => $codigo,
            'rf_resultado' => strtoupper($resultado),
            'rf_indicador' => strtoupper($indicador),
            'rf_meta' => $meta,
            'indi_id' => $tp_indi,
            'fun_id' => $this->fun_id,
            'rf_estado' => 2
            );

          $this->db->where('rf_id', $rf_id);
          $this->db->update('_resultados_finales', $update_rf);

          $this->db->where('rf_id', $rf_id);
          $this->db->delete('_resultados_finales_temporalidad');

          for ($i=$conf[0]['conf_gestion_desde']; $i <=$conf[0]['conf_gestion_hasta'] ; $i++) { 
            if($this->security->xss_clean($post[$i])!=0){
              $data_to_store2 = array( 
                'rf_id' => $rf_id,
                'g_id' => $i,
                'temp_prog' => $this->security->xss_clean($post[$i]),
              );
              $this->db->insert('_resultados_finales_temporalidad', $data_to_store2);
            }
          }

          if(count($this->model_mestrategico->get_resultado_final($rf_id))==1){
              $this->session->set_flashdata('success','Se Modifico correctamente !!!'); 
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR'); 
          }
          redirect(site_url("").'/me/objetivos_estrategicos');

      } else {
          show_404();
      }
    }

  /*------------------------------------------------------------------------------------*/
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

  public function get_mes($mes_id)
  {
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

    function rolfunn($tp_rol){
      $valor=false;
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$tp_rol);
      if(count($data)!=0){
        $valor=true;
      }
      return $valor;
    }
}