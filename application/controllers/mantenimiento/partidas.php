<?php
class partidas extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('mantenimiento/model_escala_salarial');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
        $this->load->model('programacion/model_proyecto');
        $this->load->library("security");
        $this->fun_id = $this->session->userData('fun_id');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
         //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
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

    /*--------- LISTA DE PARTIDAS --------*/
    public function lista_partidas(){
        $data['menu'] = $this->menu->genera_menu();
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep'] = $this->tp_resp($this->dist);
        $data['list_par_padres'] = $this->model_partidas->lista_padres();
        $data['lista_p'] = $this->model_partidas->lista_partidas();
        $data['partidas']=$this->list_partidas();
        $data['umedidas']=$this->list_umedidas();
        $this->load->view('admin/mantenimiento/partidas/vlist_partidas', $data);
    }
    

    /*--------- LISTA PARTIDAS --------*/
    public function list_partidas(){
        $partidas=$this->model_partidas->lista_partidas();
        $tabla='';
        $tabla.='<table id="dt_basic" class="table table-bordered" style="width:100%;">
                    <thead>
                        <tr style="height:45px;">
                            <th style="width:5%;">#</th>
                            <th style="width:10%;">C&Oacute;DIGO</th>
                            <th style="width:15%;">DESCRIPCI&Oacute;N</th>
                            <th style="width:10%;">DEPENDE</th>
                            <th style="width:10%;">UNIDADES DE MEDIDA</th>
                            <th style="width:5%;"></th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody>';
                    $nro = 0;
                    foreach ($partidas as $row){
                        $nro++;
                        $tabla.='
                        <tr>
                            <td align=center>'.$nro.'</td>
                            <td align=center><b>'.$row['par_codigo'].'</b></td>
                            <td><b>'.strtoupper($row['par_nombre']).'</b></td>
                            <td align=center>'.$row['par_depende'].'</td>
                            <td align=center><a href="'.site_url("").'/umedidas/'.$row['par_id'].'" title="UNIDADES DE MEDIDA" class="btn btn-default">UNIDADES DE MEDIDA</a></td>
                            <td></td>
                            <td></td>
                        </tr>';
                    }
                    $tabla.='
                    </tbody>
                </table>';

        return $tabla;
    }

    /*--------- LISTA UNIDADES DE MEDIDA --------*/
    public function list_umedidas(){
        $lista_umedida=$this->model_insumo->list_unidadmedida();
        $tabla='';
        $tabla.='<table id="dt_basic1" class="table table-bordered" style="width:100%;">
                    <thead>
                        <tr style="height:45px;">
                            <th style="width:5%;">#</th>
                            <th style="width:30%;">UNIDAD DE MANEJO</th>
                            <th style="width:10%;">ABREV</th>
                            <th style="width:5%;"></th>
                            <th style="width:5%;"></th>
                        </tr>
                    </thead>
                    <tbody>';
                    $nro = 0;
                    foreach ($lista_umedida as $row){
                        $nro++;
                        $tabla.='
                        <tr>
                            <td align=center>'.$nro.'</td>
                            <td><b>'.$row['um_descripcion'].'</b></td>
                            <td>'.$row['um_abrev'].'</td>
                            <td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_umedida" class="btn-default mod_um" name="'.$row['um_id'].'" title="MODIFICAR UNIDAD DE MANEJO" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>
                            <td></td>
                        </tr>';
                    }
                    $tabla.='
                    </tbody>
                </table>';

        return $tabla;
    }

    /*-- GET UNIDAD DE MEDIDA --*/
    public function get_umedida(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $um_id = $this->security->xss_clean($post['um_id']);
        
        $unidad= $this->model_insumo->get_unidadmedida($um_id); /// Unidad de Medida        
        if(count($unidad)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'unidad' => $unidad,
          );
        }
        else{
          $result = array(
            'respuesta' => 'error',
          );
        }
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- VALIDA UPDATE UNIDAD DE MEDIDA ---*/
     public function valida_umedida(){
      if($this->input->post()) {
        $post = $this->input->post();
        $um_id = $this->security->xss_clean($post['um_id']);
        $umedida = $this->security->xss_clean($post['umedida']); 
        $abrev = $this->security->xss_clean($post['abrev']);
        $erp_id = $this->security->xss_clean($post['erp_id']); 

        /*------------ UPDATE UNIDAD DE MANEJO -------*/
          $update_um= array(
            'um_descripcion' => $umedida,
            'um_abrev' => $abrev,
            'erp_id' => $erp_id
          );
          $this->db->where('um_id', $um_id);
          $this->db->update('insumo_unidadmedida', $this->security->xss_clean($update_um));
        /*-----------------------------------------*/

        $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE :)');
        redirect(site_url("").'/partidas');

      } else {
          show_404();
      }
    }


    /*--------- LISTA DE UNIDADES DE MEDIDA --------*/
    public function umedidas($par_id){
        $data['menu']=$this->menu(9);
        $data['partida']=$this->model_partidas->get_partida($par_id);
        $data['unidades_medida']=$this->unidades_medida($par_id);
        $data['seleccionados']=$this->lista_seleccionado($par_id);

        $this->load->view('admin/mantenimiento/partidas/unidad_medida', $data);
    }

    /*---- UNIDADES SELECCIONADOS ----*/
    public function lista_seleccionado($par_id){
        $tabla='';
        $seleccionados=$this->model_insumo->lista_umedida($par_id);

        $tabla.='<table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">UNIDAD DE MEDIDA</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $cont = 0;
                  foreach ($seleccionados as $row){
                    $cont++;
                    $tabla.=
                    '<tr>
                        <td>'.$cont.'</td>
                        <td>'.$row['um_descripcion'].'</td>
                    </tr>';
                  }
                  $tabla.=' 
                  </tbody>
                </table>';
        return $tabla;
    }

    /*---- UNIDADES DE MEDIDA ----*/
    public function unidades_medida($par_id){
        $tabla='';
        $lista_umedida=$this->model_insumo->list_unidadmedida();
        $tabla.='BUSCAR SERVICIO : <input type="text" class="form-control" id="kwd_search" value="" style="width:50%;"/><br>';
        $tabla.='<table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">UNIDAD DE MEDIDA</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>';
                  $cont = 0;
                  foreach ($lista_umedida as $row){
                    $verif=$this->model_insumo->verif_partida_umedida($par_id,$row['um_id']);
                    $cont++;
                    $tabla.=
                    '<tr>
                        <td>'.$cont.'</td>
                        <td>'.$row['um_descripcion'].'</td>
                        <td>';
                        if(count($verif)!=0){
                            $tabla.='<center><input type="checkbox" onclick="scheck'.$cont.'(this.checked,'.$row['um_id'].','.$par_id.');" title="UNIDAD DE MEDIDA SELECCIONADO" checked/></center>';
                        }
                        else{
                            $tabla.='<center><input type="checkbox" onclick="scheck'.$cont.'(this.checked,'.$row['um_id'].','.$par_id.');" title="SELECCIONE UNIDAD DE MEDIDA"/></center>';
                        }
                        $tabla.='</td>
                    </tr>';
                    ?>
                    <script>
                      function scheck<?php echo $cont;?>(estaChequeado,id,par_id) {
                        valor=0;
                        titulo='QUITAR UNIDAD DE MEDIDA';
                        if (estaChequeado == true) {
                          valor=1;
                          titulo='SELECCIONAR UNIDAD DE MEDIDA';
                        }

                        alertify.confirm(titulo, function (a) {
                            if (a) {
                                var url = "<?php echo site_url().'/mantenimiento/partidas/estado_unimedida'?>";
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data:{id:id,estado:valor,par_id:par_id},
                                    success: function (data) {
                                        window.location.reload(true);
                                    }
                                });
                            } else {
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });
                      }
                    </script>
                    <?php
                  }
                  $tabla.=' 
                  </tbody>
                </table>';
        return $tabla;
    }


    /*--- ACTIVAR, DESACTIVAR OBJETIVO REGIONAL -----*/
    function estado_unimedida(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('id', 'id unidad', 'required|trim'); // por_id 
          $this->form_validation->set_rules('estado', 'estado', 'required|trim'); // Activo/Desactivo
          
          $post = $this->input->post();
          $id= $this->security->xss_clean($post['id']); 
          $estado_activo = $this->security->xss_clean($post['estado']);
          $par_id = $this->security->xss_clean($post['par_id']);
         
          if($estado_activo==1){ /// Activar unidad de medida
              $data_to_store3 = array(
                'par_id' => $par_id,
                'um_id' => $id,
              );
              $this->db->insert('par_umedida', $data_to_store3);
          }
          else{ /// Desactivar unidad de medida
            $this->db->where('um_id', $id);
            $this->db->delete('par_umedida');
          }
    
      }else{
          show_404();
      }
    }

    /*--- IMPRIMIR LISTA DE PARTIDAS -----*/
    function imprime_partidas(){
        $tabla='';
        $partidas=$this->model_partidas->lista_partida_dependientes();

        $tabla.='<table class="table table-bordered" border=1 style="width:80%;">
                  <thead>
                    <tr>
                      <th scope="col" style="width:7%;">CODIGO PARTIDA</th>
                      <th scope="col" style="width:13%;">PARTIDA PADRE</th>
                      <th scope="col" style="width:10%;">CODIGO PARTIDA</th>
                      <th scope="col" style="width:60%;">DESCRIPCI&Oacute;N PARTIDA</th>
                      <th scope="col" style="width:10%;">UNIDAD MEDIDA</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $cont = 0;
                  foreach ($partidas as $row){
                    $seleccionados=$this->model_insumo->lista_umedida($row['par_id']);
                    if(count($seleccionados)!=0){
                        foreach ($seleccionados as $rowdp){
                        $tabla.=
                        '<tr>
                            <td style="width:7%;">'.$row['cpadre'].'</td>
                            <td style="width:13%;">'.mb_convert_encoding($row['ppadre'], 'cp1252', 'UTF-8').'</td>
                            <td style="width:10%;">'.$row['chijo'].'</td>
                            <td style="width:60%;">'.mb_convert_encoding(strtoupper($row['phijo']), 'cp1252', 'UTF-8').'</td>
                            <td style="width:10%;">'.mb_convert_encoding($rowdp['um_descripcion'], 'cp1252', 'UTF-8').'</td>
                        </tr>';
                        }
                    }
                    else{
                        $tabla.=
                        '<tr>
                            <td style="width:7%;">'.$row['cpadre'].'</td>
                            <td style="width:13%;">'.mb_convert_encoding($row['ppadre'], 'cp1252', 'UTF-8').'</td>
                            <td style="width:10%;">'.$row['chijo'].'</td>
                            <td style="width:60%;">'.mb_convert_encoding(strtoupper($row['phijo']), 'cp1252', 'UTF-8').'</td>
                            <td style="width:10%;"><font color=red>SIN UNIDADES</font></td>
                        </tr>';
                    }
                  }
                  $tabla.='</tbody>
                </table>';

        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=lista_Partidas.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "";
        echo "".$tabla."";

    }


    function verificar_cod_par(){
        if($this->input->is_ajax_request() && $this->input->post('par_codigo'))
        {
            $post = $this->input->post();
            $cod = $post['par_codigo'];
            $gestion = $post['par_gestion'];
            $cod = $this->security->xss_clean($cod);
            $gestion = $this->security->xss_clean($gestion);
            //$gestion = $post['aper_gestion'];
            $data = $this->model_partidas->verificar_parcod($cod,$gestion);
            if(count($data)== 0){
                echo '1';
            }else{
                echo '0';
            }
        }else{
            show_404();
        }
    }
     function add_par(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $this->form_validation->set_rules('par_nombre', 'Nombre', 'required|trim');
            $this->form_validation->set_rules('par_codigo', 'codigo', 'required|trim|integer');
            $this->form_validation->set_rules('par_gestion', 'gestion', 'required|trim|integer');
            //=========================== mensajes =========================================
            $this->form_validation->set_message('required', 'El campo es es obligatorio');
            $this->form_validation->set_message('integer', 'El campo  debe poseer solo numeros enteros');
            if ($this->form_validation->run() ) {
                $par_nombre = $this->input->post('par_nombre');
                $par_gestion =  $this->input->post('par_gestion');
                $par_codigo =  $this->input->post('par_codigo');
                //=================enviar  evitar codigo malicioso ==========
                $par_nombre= $this->security->xss_clean(trim($par_nombre));
                $par_codigo = $this->security->xss_clean($par_codigo);
                $par_gestion = $this->security->xss_clean($par_gestion);
                //======================= MODIFICAR=
                if(isset($_REQUEST['modificar'])){
                    $par_id = $this->input->post('modificar');
                    $this->model_partidas->mod_par($par_id,$par_nombre,$par_gestion,$par_codigo);
                }else{
                    $this->form_validation->set_rules('dependiente', 'Seleccione una opcion', 'required|trim');
                    $this->form_validation->set_rules('padre', 'Seleccione una opcion', 'required|trim');
                    //=========================== mensajes =========================================
                    $this->form_validation->set_message('required', 'El campo es es obligatorio');
                    $padre =  $this->input->post('padre');
                    $codigo_padre =  $this->model_partidas->dato_par($padre);
                    if($padre ==2){
                        $this->model_partidas->add_par_independiente($par_nombre,$par_codigo,$par_gestion);
                    }else{
                        $this->model_partidas->add_par_dependiente($par_nombre,$codigo_padre[0]['par_codigo'],$par_codigo,$par_gestion);
                    }
                }
                echo 'true';
            } else {
                echo'DATOS ERRONEOS';
            }
        }else{
            show_404();
        }

    }
    function get_par(){
        if($this->input->is_ajax_request() && $this->input->post())
        {
            $post = $this->input->post();
            $cod = $post['id_par'];
            $id = $this->security->xss_clean($cod);
            $dato_par = $this->model_partidas->dato_par($id);
            foreach($dato_par as $row){
                $padre_par = $this->model_partidas->dato_par_codigo($row['par_depende']);
                foreach($padre_par as $fila){
                    $padre = $fila['par_nombre'];
                }
                $result = array(
                    'par_id' => $row['par_id'],
                    "par_nombre" =>$row['par_nombre'],
                    "par_codigo" =>$row['par_codigo'],
                    "par_gestion" =>$row['par_gestion'],
                    "padre" => $padre
                );
            }
            echo json_encode($result);
        }else{
            show_404();
        }
    }

    function del_par(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $postid = $post['postid'];
            $sql = 'DELETE FROM  partidas WHERE par_id ='.$postid.' AND par_depende != 0';
            if($this->db->query($sql)){
                echo $postid;
            }else{
                echo false;
            }
        }else{
            show_404();
        }

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

	}