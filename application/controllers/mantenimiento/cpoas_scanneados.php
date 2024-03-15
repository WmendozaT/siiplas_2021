<?php
class Cpoas_scanneados extends CI_Controller {
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

    /*-----  LISTA DE POAS ESCANNEDADOS -----*/
    public function list_poa_scanneados(){
      $data['menu']=$this->menu(9);
      $data['regional']=$this->regionales(); /// Lista de Regionales 
      $this->load->view('admin/mantenimiento/poa_scanneados/list_regionales', $data);
      /*if($this->gestion!=2020){ /// Gestion 2018-2019
        $data['regional']=$this->regionales(); /// Lista de Regionales 
        $this->load->view('admin/mantenimiento/poa_scanneados/list_regionales', $data);
      }
      else{
        $data['unidades']=$this->unidades(); /// Lista de Unidades,Establecimientos
        $this->load->view('admin/mantenimiento/poa_scanneados/list_unidades', $data);
      }*/
      
    }

   
    /*------------ CONFIGURAR REGIONALES ------------*/
    public function regionales(){ 
      $tabla='';
      $distritales=$this->model_configuracion->regionales_distritales();

      $tabla.=' 
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="jarviswidget jarviswidget-color-darken" >
            <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                <h2 class="font-md"><strong></strong></h2>  
            </header>
              <div>
                  <div class="widget-body no-padding">
                      <table id="dt_basic" class="table table-bordered" style="width:100%;">
                        <thead>
                          <tr>
                            <th scope="col" style="width:1%;">#</th>
                            <th scope="col" style="width:30%;">REGIONAL / DISTRITAL</th>
                            <th scope="col" style="width:10%;">VER POA</th>
                            <th scope="col" style="width:10%;">SUBIR</th>
                            <th scope="col" style="width:10%;">DESCARGAR</th>
                          </tr>
                        </thead>
                          <tbody>';
                          $nro=0;
                          foreach($distritales as $row){
                            $get_pdf=$this->model_estructura_org->get_poa_scanneado($row['dist_id']);
                            $nro++;
                            $dist=$row['dist_distrital'];
                            $color='';
                            if($row['dist_adm']==1){
                                $dist='<b>'.strtoupper($row['dist_distrital']).'</b>';
                                $color='#caf7f1';
                            }

                            $tabla.='<tr bgcolor='.$color.'>';
                                $tabla.='<td align=center>'.$nro.'</td>';
                                $tabla.='<td>'.strtoupper($dist).'</td>';
                                if(count($get_pdf)!=0){
                                  $tabla.='<td align=center><a href="#" class="btn btn-default enlace" name="'.$row['dist_id'].'" id="'.strtoupper($row['dist_distrital']).'" title="VER "><img src="'.base_url().'assets/ifinal/ver.jpg" WIDTH="36" HEIGHT="36"/></a></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                                $tabla.='<td align=center>
                                            <a href="#" data-toggle="modal" data-target="#modal_mod_file" class="btn btn-default mod_file" name="'.$row['dist_id'].'" id="'.strtoupper($row['dist_distrital']).'" title="SUBIR ARCHIVO PDF"><img src="'.base_url().'assets/ifinal/upload.jpg" WIDTH="36" HEIGHT="36"/></a>
                                        </td>';
                                if(count($get_pdf)!=0){
                                  $tabla.='<td align=center><a href="'.base_url().'scanneados/'.$get_pdf[0]['descripcion'].'" class="btn btn-default" target="_blank" title="DESCARGAR ARCHIVO PDF" download><img src="'.base_url().'assets/ifinal/download.jpg" WIDTH="36" HEIGHT="36"/></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                            $tabla.='</tr>';
                          }
                          $tabla.='
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
        </article>';
        return $tabla;
    }

    /*--- GET REPORTE ARCHIVO PDF REGIONAL ---*/
    public function get_pdf_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $get_pdf=$this->model_estructura_org->get_poa_scanneado($dist_id);
        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'scanneados/'.$get_pdf[0]['descripcion'].'"></iframe>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- GET REPORTE ARCHIVO PDF UNIDAD ---*/
    public function get_pdf_poa_unidad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']); // proy id

        $get_pdf=$this->model_estructura_org->get_poa_scanneado_unidad($proy_id);
        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'scanneados_uni/'.$get_pdf[0]['archivo_pdf'].'"></iframe>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*-- Unidades, Establecimientos, Proyectos de Inversion --*/
    public function unidades(){
      $data['menu']=$this->menu(9);
      $unidades=$this->model_proyecto->list_unidades(4,4); /// Unidad
      $pinversion=$this->model_proyecto->list_pinversion(1,4); /// Proyectos de Inversion
      $tabla='';
      $tabla.='
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div >
            <div id="tabs">
              <ul>
                <li>
                  <a href="#tabs-c">GASTO CORRIENTE</a>
                </li>
                <li>
                  <a href="#tabs-a">PROYECTO DE INVERSI&Oacute;N</a>
                </li>
              </ul>
              <div id="tabs-c">
                <div class="row">
                  <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="jarviswidget jarviswidget-color-darken">
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>OPERACI&Oacute;N FUNCIONAMIENTO</strong></h2>  
                    </header>
                    <div>
                      
                      <div class="widget-body no-padding">
                        <table id="dt_basic3" class="table1 table-bordered" style="width:100%;">
                          <thead>
                            <tr style="height:65px;">
                              <th scope="col" style="width:1%;">#</th>
                              <th scope="col" style="width:5%;">APERTURA PROGRAMATICA</th>
                              <th scope="col" style="width:20%;">UNIDAD / ESTABLECIMIENTO</th>
                              <th scope="col" style="width:5%;">VER ARCHIVO</th>
                              <th scope="col" style="width:5%;">SUBIR</th>
                              <th scope="col" style="width:5%;">DESCARGAR</th>
                            </tr>
                          </thead>
                          <tbody>';
                            $nro=0;
                            foreach($unidades as $row){
                              $get_pdf=$this->model_estructura_org->get_poa_scanneado_unidad($row['proy_id']);
                              $nro++;
                              $tabla.='
                              <tr style="height:30px;">
                                <td align=center title="'.$row['proy_id'].'">'.$nro.'</td>
                                <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                                <td><b>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</b></td>';
                                if($get_pdf[0]['archivo_pdf']!=''){
                                  $tabla.='<td align=center><a href="#" class="btn btn-default enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev']).'" title="VER ARCHIVO"><img src="'.base_url().'assets/ifinal/ver.jpg" WIDTH="36" HEIGHT="36"/></a></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                                $tabla.='
                                <td align=center>
                                  <a href="#" data-toggle="modal" data-target="#modal_mod_file" class="btn btn-default mod_file" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev']).'" title="SUBIR ARCHIVO PDF"><img src="'.base_url().'assets/ifinal/upload.jpg" WIDTH="36" HEIGHT="36"/></a>
                                </td>';
                                if($get_pdf[0]['archivo_pdf']!=''){
                                  $tabla.='<td align=center><a href="'.base_url().'scanneados_uni/'.$get_pdf[0]['archivo_pdf'].'" class="btn btn-default" target="_blank" title="DESCARGAR ARCHIVO PDF" download><img src="'.base_url().'assets/ifinal/download.jpg" WIDTH="36" HEIGHT="36"/></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                                $tabla.='
                              </tr>';
                            }
                          $tabla.='
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  </article>
                </div>
              </div>

              <div id="tabs-a">
                <div class="row">
                  <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>PROYECTOS DE INVERSI&Oacute;N PUBLICA </strong></h2>  
                    </header>
                    <div>
                      <div class="widget-body no-padding">
                        <table id="dt_basic" class="table table-bordered" style="width:100%;">
                          <thead>
                             <tr style="height:65px;">
                                <th scope="col" style="width:1%;">#</th>
                                <th scope="col" style="width:5%;">APERTURA PROGRAMATICA</th>
                                <th scope="col" style="width:20%;">PROYECTO DE INVERSI&Oacute;N</th>
                                <th scope="col" style="width:5%;">VER ARCHIVO</th>
                                <th scope="col" style="width:5%;">SUBIR</th>
                                <th scope="col" style="width:5%;">DESCARGAR</th>
                              </tr>
                          </thead>
                          <tbody>';
                            $nro=0;
                            foreach($pinversion as $row){
                              $get_pdf=$this->model_estructura_org->get_poa_scanneado_unidad($row['proy_id']);
                              $nro++;
                              $tabla.='
                              <tr style="height:30px;">
                                <td align=center title="'.$row['proy_id'].'">'.$nro.'</td>
                                <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                                <td><b>'.$row['proy_nombre'].'</b></td>';
                                if($get_pdf[0]['archivo_pdf']!=''){
                                  $tabla.='<td align=center><a href="#" class="btn btn-default enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'" title="VER ARCHIVO"><img src="'.base_url().'assets/ifinal/ver.jpg" WIDTH="36" HEIGHT="36"/></a></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                                $tabla.='
                                <td align=center>
                                  <a href="#" data-toggle="modal" data-target="#modal_mod_file" class="btn btn-default mod_file" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'" title="SUBIR ARCHIVO PDF"><img src="'.base_url().'assets/ifinal/upload.jpg" WIDTH="36" HEIGHT="36"/></a>
                                </td>';
                                if($get_pdf[0]['archivo_pdf']!=''){
                                  $tabla.='<td align=center><a href="'.base_url().'scanneados_uni/'.$get_pdf[0]['archivo_pdf'].'" class="btn btn-default" target="_blank" title="DESCARGAR ARCHIVO PDF" download><img src="'.base_url().'assets/ifinal/download.jpg" WIDTH="36" HEIGHT="36"/></td>';
                                }
                                else{
                                  $tabla.='<td></td>';  
                                }
                                $tabla.='
                              </tr>';
                            }
                          $tabla.='
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  </article>
                </div>
              </div>
              
            </div>
          </div>
        </article>';
      return $tabla;
    }

    /*---- SUBIR ARCHIVO PDF POR REGIONAL ----*/
    function importar_archivo_pdf(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $dist_id = $post['dist_id']; /// dist id

        $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
        $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
        $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
        $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo 

        $distrital=$this->model_proyecto->dep_dist($dist_id);
        $file_name = str_replace(' ', '', $distrital[0]['dist_distrital']);

        if($filename!='' & $filesize!=0){
          
          $get_pdf=$this->model_estructura_org->get_poa_scanneado($dist_id);
          if(count($get_pdf)!=0){
            unlink('scanneados/'.$get_pdf[0]['descripcion']);

          //$file_name = substr(md5(uniqid(rand())),0,5).$file_ext;
            

            move_uploaded_file($_FILES["file1"]["tmp_name"],"scanneados/" . $get_pdf[0]['descripcion']); // Guardando PDF

            /*-Update-*/
            $update_pdf = array(
              'descripcion' => $file_name
            );
            $this->db->where('pdf_id', $get_pdf[0]['pdf_id']);
            $this->db->update('pdf_poas', $update_pdf);
          }
          else{

            //$file_name = substr(md5(uniqid(rand())),0,5).$file_ext;
            move_uploaded_file($_FILES["file1"]["tmp_name"],"scanneados/" . $file_name); // Guardando PDF

            /*-Insert-*/
            $data_to_store = array(
              'descripcion' => $file_name,
              'g_id' => $this->gestion,
            );
            $this->db->insert('pdf_poas', $data_to_store);
            $pdf_id=$this->db->insert_id();

            /*---------*/

            $data_to_store2 = array(
              'dist_id' => $dist_id,
              'pdf_id' => $pdf_id,
            );
            $this->db->insert('reg_tiene_poas', $data_to_store2);
          }

          redirect('mis_poas_scanneados');
        }
        else{
          echo "Error 2";
          //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/1/0');  ///// nose selecciono archivo
        }

      }
      else{
        echo "error !!";
      }
    }



/*---- SUBIR ARCHIVO PDF POR REGIONAL ----*/
    function importar_archivo_pdf2(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $dist_id = $post['dist_id']; /// dist id

        $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
        $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
        $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
        $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo 

        if($filename!='' & $filesize!=0){
          
          $get_pdf=$this->model_estructura_org->get_poa_scanneado($dist_id);
          if(count($get_pdf)!=0){
            unlink('scanneados/'.$get_pdf[0]['descripcion']);

            $file_name = substr(md5(uniqid(rand())),0,5).$file_ext;
            move_uploaded_file($_FILES["file1"]["tmp_name"],"scanneados/" . $file_name); // Guardando PDF

            /*-Update-*/
            $update_pdf = array(
              'descripcion' => $file_name
            );
            $this->db->where('pdf_id', $get_pdf[0]['pdf_id']);
            $this->db->update('pdf_poas', $update_pdf);
          }
          else{

            $file_name = substr(md5(uniqid(rand())),0,5).$file_ext;
            move_uploaded_file($_FILES["file1"]["tmp_name"],"scanneados/" . $file_name); // Guardando PDF

            /*-Insert-*/
            $data_to_store = array(
              'descripcion' => $file_name,
              'g_id' => $this->gestion,
            );
            $this->db->insert('pdf_poas', $data_to_store);
            $pdf_id=$this->db->insert_id();

            /*---------*/

            $data_to_store2 = array(
              'dist_id' => $dist_id,
              'pdf_id' => $pdf_id,
            );
            $this->db->insert('reg_tiene_poas', $data_to_store2);
          }

          redirect('mis_poas_scanneados');
        }
        else{
          echo "Error 2";
          //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/1/0');  ///// nose selecciono archivo
        }

      }
      else{
        echo "error !!";
      }
    }
    /*---- SUBIR ARCHIVO PDF POR UNIDAD,ESTABLECIMIENTO,PROY INVERSION ----*/
    function importar_archivo_pdf_unidad(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $proy_id = $post['proy_id']; /// proy id

        $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
        $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
        $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
        $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo 

        if($filename!='' & $filesize!=0){

          $get_pdf=$this->model_estructura_org->get_poa_scanneado_unidad($proy_id);
          unlink('scanneados_uni/'.$get_pdf[0]['archivo_pdf']);

          $file_name = substr(md5(uniqid(rand())),0,5).$file_ext;
          move_uploaded_file($_FILES["file1"]["tmp_name"],"scanneados_uni/".$file_name); // Guardando PDF

          /*-Update-*/
          $update_pdf = array(
            'archivo_pdf' => $file_name
          );
          $this->db->where('aper_id', $get_pdf[0]['aper_id']);
          $this->db->update('aperturaprogramatica', $update_pdf);

          redirect('mis_poas_scanneados');
        }
        else{
          echo "Error 2";
          //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/1/0');  ///// nose selecciono archivo
        }

      }
      else{
        echo "error !!";
      }

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