<?php
class Cajustes extends CI_Controller {
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
                $this->load->model('mantenimiento/model_estructura_org');
                $this->load->model('programacion/model_faseetapa');
                $this->load->model('programacion/model_proyecto');
                $this->load->model('programacion/model_producto');
                $this->load->model('mestrategico/model_objetivogestion');
                $this->load->model('reporte_eval/model_evalregional');
                $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
                $this->load->model('ejecucion/model_ejecucion');
                $this->load->model('modificacion/model_modificacion');
                $this->load->library("security");
                $this->gestion = $this->session->userData('gestion');
                $this->rol = $this->session->userData('rol');
                $this->fun_id = $this->session->userData('fun_id');
                $this->tmes = $this->session->userData('trimestre');
            }
            else{
                redirect('admin/dashboard');
            }
        }
        else{
                redirect('/','refresh');
        }
    }

    /*--------- AJUSTES AL POA ------------*/
    public function menu_ajustes(){
      $data['menu']=$this->menu(9);
      $data['regional']=$this->regionales();


/*      $pi=$this->model_proyecto->list_pi();

      $tabla='';
      $tabla.='
      <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:20%;" align="center">';
      foreach ($pi as $row){
        $tabla.='
        <tr>
          <td>'.$row['aper_id'].'</td>
          <td>'.$row['proy_id'].'</td>
          <td>'.$row['dep_id'].'</td>
          <td>'.$row['dep_departamento'].'</td>
          <td>'.$row['dist_id'].'</td>
          <td>'.$row['dist_distrital'].'</td>
          <td>'.$row['proy'].'</td>
          <td>'.$row['proyecto'].'</td>
          <td>'.$row['programado_total'].'</td>';
          for ($i=1; $i <=12 ; $i++) { 
            $tabla.='<td>'.$row['mes'.$i].'</td>';
          }
        $tabla.='
        </tr>';
      }
      $tabla.='</table>';

      echo $tabla;*/
      $this->load->view('admin/mantenimiento/ajustes_siiplas/menu_ajustes', $data);
    }




  /*---- AJUSTAR FORM 4 ALINEACIONES ----*/
  function importar_archivo(){
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
          $i=0;
          $lineas = file($archivotmp);

          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
              $datos = explode(";",$linea);

                if(count($datos)==7){
                  $dep_id = intval(trim($datos[0])); //// dep_id
                  $prod_id = intval(trim($datos[1])); //// prod id
                  $codigo_og = intval(trim($datos[2])); //// cod objetivo ACP FORM 1
                  $codigo_or = intval(trim($datos[3])); //// cod objetivo regional FORM 2
                  $codigo_form4 = intval(trim($datos[4])); //// cod form 4
                  $prioridad = intval(trim($datos[5])); //// prioridad
                  $descripcion = strval(utf8_encode(trim($datos[6]))); //// descr act

                  $get_informacion_alineacion=$this->model_objetivogestion->get_alineacion_habilitado_oregional_a_form4($codigo_og,$codigo_or,$dep_id);

                  if(count($get_informacion_alineacion)!=0){
                    echo $prod_id.'---> '.$get_informacion_alineacion[0]['or_id'].'---'.$prioridad.'<br>';
                      /*$update_alineacion= array(
                        'or_id' => $get_informacion_alineacion[0]['or_id'],
                        'prod_priori' => $prioridad,
                        'prod_producto' => $descripcion
                      );
                      $this->db->where('prod_id', $prod_id);
                      $this->db->update('_productos', $update_alineacion);*/
                  }

                }
              }

              $i++;
            }

            //$this->session->set_flashdata('success','SE SUBIO CORRECTAMENTE EL ARCHIVO');
            //redirect(site_url("").'/ediciones');

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



  /*---- IMPORTAR ARCHIVO PARA EL AJUSTE DE APERTURA PROYECTOS----*/
  function importar_archivo3(){
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
          $i=0;
          $lineas = file($archivotmp);

          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
              $datos = explode(";",$linea);

                if(count($datos)==21){
                  $aper_id = intval(trim($datos[0])); //// aper_id
                  $proy_id = intval(trim($datos[1])); //// proy id

                  $nro=0;
                  for ($i=9; $i <=20 ; $i++) { 
                    $nro++;
                    if(trim($datos[$i])!=0){
                      $data_to_store = array( 
                        'proy_id' => $proy_id,
                        'aper_id' => $aper_id,
                        'mes_id' => $nro,
                        'temp_fis' => trim($datos[$i]), //// aper_id,
                        );
                      $this->db->insert('temporalidad_inicial_total_insumo', $data_to_store); 
                    }
                     
                  }
                }
              }

              $i++;
            }

            //$this->session->set_flashdata('success','SE SUBIO CORRECTAMENTE EL ARCHIVO');
            //redirect(site_url("").'/ediciones');

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

  /*---- IMPORTAR ARCHIVO PARA EL AJUSTE ----*/
  function importar_archivo2(){
    if ($this->input->post()) {
        $post = $this->input->post();
       // $tp = $this->security->xss_clean($post['tp_id']);
       // $tp_id = $this->security->xss_clean($post['tp_id']);

        $tipo = $_FILES['archivo']['type'];
        $tamanio = $_FILES['archivo']['size'];
        $archivotmp = $_FILES['archivo']['tmp_name'];

        $filename = $_FILES["archivo"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.'));
        $file_ext = substr($filename, strripos($filename, '.'));
        $allowed_file_types = array('.csv');
        if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
          $i=0;
          $lineas = file($archivotmp);

          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
              $datos = explode(";",$linea);
                if(count($datos)==2){
                  $id = intval(trim($datos[0])); //// aper_id
                  $com_id = intval(trim($datos[1])); //// com_id
                 
                  ///------------------
                 /* $update_ptto = array(
                    'cm_id' => $com_id
                  );

                  $this->db->where('fun_id', $id);
                  $this->db->update('funcionario', $update_ptto);*/
                  /// ------------------
                  
                }
              }

              $i++;
            }

            $this->session->set_flashdata('success','SE SUBIO CORRECTAMENTE EL ARCHIVO');
            redirect(site_url("").'/ediciones');

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















    /*-------- GET LISTA DE EDICIONES POR UNIDAD ------------*/
    public function get_edicion_unidad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
       // $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $tabla=$this->lista_uo($dep_id,1);
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*---------- LISTA UNIDADES ORGANIZACIONALES -----------*/
    public function lista_uo($dep_id,$tp){
      $tabla='';
      $operaciones=$this->mrep_operaciones->operaciones_por_regionales($dep_id);
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $head='';$foot='';
      $tab='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
      if($tp==1){
        $head='<script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
              <h3>REGIONAL '.strtoupper($dep[0]['dep_departamento']).' - <a href="'.site_url("").'/rep_ediciones/'.$dep_id.'" target="_blank" title="REPORTE PDF"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/></a></h3>
              <div class="jarviswidget jarviswidget-color-darken">
              <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                <h2 class="font-md"><strong>UNIDADES / PROYECTOS</strong></h2>  
              </header>
              <div>
                <div class="widget-body no-padding">';

        $foot='</div>
              </div>
            </div>';
        $tab='<table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>';
      }
      
      $nro=0;
        $tabla.='
            '.$head.'
            '.$tab.'
                 <thead>
                    <tr class="modo1">
                      <th style="width:1%; text-align: center;" colspan=4></th>
                      <th style="width:10%; text-align: center;" colspan=3>CERTIFICACIONES POA</th>
                      <th style="width:20%; text-align: center;" colspan=3>MODIFICACIONES POA</th>
                    </tr>
                    <tr class="modo1">
                      <th style="width:1%; text-align: center;">#</th>
                      <th style="width:10%; text-align: center;">APERTURA PROGRAM&Aacute;TICA</th>
                      <th style="width:20%; text-align: center;">UNIDAD / PROYECTO</th>
                      <th style="width:10%; text-align: center;">TIPO DE PROYECTO</th>
                      <th style="width:8.5%; text-align: center;">EDICI&Oacute;N</th>
                      <th style="width:8.5%; text-align: center;">CERT. POA</th>
                      <th style="width:8.5%; text-align: center;">CERT. TOTAL</th>
                      <th style="width:8.5%; text-align: center;">MOD. OPE.</th>
                      <th style="width:8.5%; text-align: center;">MOD. REQ.</th>
                      <th style="width:8.5%; text-align: center;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0;$sum_cpoa=0;$sum_mope=0;$sum_mreq=0; $sum_cert_normal=0; $sum_cert_edit=0;
                foreach ($operaciones as $row){
                  $cpoas_total=$this->model_ejecucion->list_edicion_cpoas_unidad($row['proy_id']); /// nro cert poas
                  $cpoas=$this->model_ejecucion->list_cert_editados_unidad($row['proy_id'],0); /// Certificados Normal
                  $cpoas_edit=$this->model_ejecucion->list_cert_editados_unidad($row['proy_id'],1); /// Certificados Editados
                  $mod=$this->list_modificaciones_uni($row['proy_id']); /// Modificaciones
                  $nro++;$nro_cpoa=0;
                  if(count($cpoas_total)!=0){
                    $nro_cpoa=$cpoas_total[0]['nro'];
                  }

                  $nro_cert_normal=0;
                  $nro_cert_edit=0;
                  if(count($cpoas)!=0){
                    $nro_cert_normal=count($cpoas);
                  }
                  if(count($cpoas_edit)!=0){
                    $nro_cert_edit=count($cpoas_edit);
                  }
                  $tabla.='<tr class="modo1">';
                    $tabla.='
                            <td style="width: 1%; text-align: left;" style="height:11px;">'.$nro.'</td>
                            <td style="width: 10%; text-align: center;">'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                            <td style="width: 20%; text-align: left;">'.$row['proy_nombre'].'</td>
                            <td style="width: 10%; text-align: left;">'.$row['tp_tipo'].'</td>
                            <td style="width: 8%; text-align: center;">'.$nro_cert_normal.'</td>
                            <td style="width: 8.5%; text-align: center;">'.$nro_cert_edit.'</td>
                            <td style="width: 8.5%; text-align: center;">'.$nro_cpoa.'</td>
                            <td style="width: 8.5%; text-align: center;" bgcolor="#dff0d8">'.$mod[1].'</td>
                            <td style="width: 8.5%; text-align: center;" bgcolor="#dff0d8">'.$mod[2].'</td>
                            <td style="width: 8.5%; text-align: center;" bgcolor="#dff0d8">'.$mod[3].'</td>
                          </tr>';
                  $sum_cpoa=$sum_cpoa+$nro_cpoa;
                  $sum_mope=$sum_mope+$mod[1];
                  $sum_mreq=$sum_mreq+$mod[2];
                  $sum_cert_normal=$sum_cert_normal+$nro_cert_normal;
                  $sum_cert_edit=$sum_cert_edit+$nro_cert_edit;
                }                                                     
        $tabla.='</tbody>
                  <tr class="modo1">
                    <td colspan=4>TOTAL</td>
                    <td style="text-align: center;">'.$sum_cert_normal.'</td>
                    <td style="text-align: center;">'.$sum_cert_edit.'</td>
                    <td style="text-align: center;">'.$sum_cpoa.'</td>
                    <td style="text-align: center;" bgcolor="#dff0d8">'.$sum_mope.'</td>
                    <td style="text-align: center;" bgcolor="#dff0d8">'.$sum_mreq.'</td>
                    <td style="text-align: center;" bgcolor="#dff0d8">'.($sum_mope+$sum_mreq).'</td>
                  </tr>
                </table>
              '.$foot.'';

      return $tabla;
    }

    /*----------- REPORTE EDICION POR UNIDAD -----------*/
    public function rep_ediciones($dep_id){
      $data['dep']=$this->model_proyecto->get_departamento($dep_id);
      if(count($data['dep'])!=0){
        $data['mes'] = $this->mes_nombre();
        $data['tabla']=$this->lista_uo($dep_id,2);
        $this->load->view('admin/mantenimiento/ediciones/reporte_ediciones_regional', $data);
      }
      else{
        echo "ERROR AL GENERAR EL REPORTE";
      }
     
    }

    /*----------- LISTA DE MODIFICACIONES -----------*/
    public function list_modificaciones_uni($proy_id){
      $total[1]=0;
      $total[2]=0;
      $total[3]=0;
      
      // ----- LIST CITES OPERACIONES
      $cites=$this->model_modificacion->list_cites_operaciones_proy($proy_id);
      $nro_ope=0;
      if(count($cites)!=0){
          foreach($cites  as $cit){
            $ca=$this->model_modificacion->list_add_producto($cit['ope_id']);
            $cm=$this->model_modificacion->productos_modificados($cit['ope_id']);
            $cd=$this->model_modificacion->productos_eliminados($cit['ope_id']);
            if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
              $nro_ope++;
            }
          }
      }

      // ----- LIST CITES REQUERIMIENTOS 
      $cites=$this->model_modificacion->list_cites_requerimientos_proy($proy_id);
      $nro_req=0;
      if(count($cites)!=0){
        foreach($cites  as $cit){
            $ca=$this->model_modificacion->cite_add($cit['insc_id']);
            $cm=$this->model_modificacion->cite_mod($cit['insc_id']);
            $cd=$this->model_modificacion->ins_del($cit['insc_id']);
            if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
              $nro_req++;
            }
          }
      }
      
      $total[1]=$nro_ope;
      $total[2]=$nro_req;
      $total[3]=($nro_ope+$nro_req);

      return $total;
    }


    /// ---- LISTA DE REGIONALES ----
    public function regionales(){
      $tabla='';
      $regiones=$this->mrep_operaciones->regiones();
      $tabla.='<table class="table table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th style="width:1%;">#</th>
                    <th style="width:50%;">REGIONAL</th>
                    <th style="width:5%;"></th>
                  </tr>
                </thead>
              <tbody>';
      $nro=0;
      foreach($regiones as $row){
        $nro++;
        $tabla.='<tr>';
          $tabla.='<td>'.$nro.'</td>';
          $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
          $tabla.='<td><a href="#" class="btn btn-info enlace" name="'.$row['dep_id'].'" id="'.strtoupper($row['dep_departamento']).'">Ver detalle</a></td>';
        $tabla.='</tr>';
      }
      $tabla.='</tbody>
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
    /*---------- Menu --------------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++)
        {
            if(count($subenlaces[$enlaces[$i]['o_child']])>0)
            {
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

    /*----------- Rol Usuario --------------*/
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
}