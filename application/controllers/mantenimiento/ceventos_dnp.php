<?php
class Ceventos_dnp extends CI_Controller {
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
                $this->load->model('mantenimiento/model_evento');
              /*  $this->load->model('programacion/model_proyecto');
                $this->load->model('programacion/model_producto');
                $this->load->model('mestrategico/model_objetivogestion');
                $this->load->model('reporte_eval/model_evalregional');
                $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
                $this->load->model('ejecucion/model_ejecucion');
                $this->load->model('modificacion/model_modificacion');*/
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

    /*------ Listado de Eventos -------*/
    public function menu_eventos(){
      $data['menu']=$this->menu(9);
      $listado_eventos=$this->model_evento->lista_eventos();
      $data['eventos']='';
      $data['eventos']='
          '.$this->style().'
          <style>
            #mdialTamanio1{
                width: 50% !important;
            }
          </style>
          <div class="row">
              <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <section id="widget-grid" class="well">
                      <div class="">
                        <h1><b>LISTADO DE EVENTOS / '.$this->gestion.'</b></h1>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_form1" class="btn btn-default nuevo_form1" title="NUEVO REGISTRO FORM N 4" >
                          <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>REGISTRAR EVENTO</b>
                        </a>
                      </div>
                  </section>
              </article>
          </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" >
              <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md">EVENTOS '.$this->gestion.'</strong></h2>  
              </header>
                <div>
                  <div class="widget-body no-padding">
                  <input type="hidden" name="base" value="'.base_url().'">
                    <table id="dt_basic" class="table table-bordered" style="width:100%;">
                        <thead>
                          <tr style="height:35px;" align="center">
                            <th style="width:1%;" align="center">#</th>
                            <th style="width:2%;" align="center">DEL</th>
                            <th style="width:2%;" align="center">CODIGO</th>
                            <th style="width:5%;" title="TIPO"><center>TIPO DE EVENTO</center></th>
                            <th style="width:20%;" title="EVENTO"><center>EVENTO</center></th>
                            <th style="width:20%;" title="ORGANIZADOR"><center>ORGANIZADOR</center></th>
                            <th style="width:15%;" title="TIPO"><center>DATOS DEL EVENTO</center></th>
                            <th style="width:10%;" title="FECHA DE IMPRESION"><center>FECHA DE IMPRESION</center></th>
                            <th style="width:2%;" title="LISTADO"><center>PARTICIPANTES</center></th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($listado_eventos as $rowp){
                          $nro++;
                          $data['eventos'].='
                          <tr>
                            <td>'.$nro.'</td>
                            <td></td>
                            <td><input type="text" class="form-control" onkeyup="update_evento(1,'.$rowp['even_id'].',\'cod_even\');" style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="cod_even'.$rowp['even_id'].'" id="cod_even'.$rowp['even_id'].'" title="CODIGO DE EVENTO" value="'.$rowp['cod_evento'].'"></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(2,'.$rowp['even_id'].',\'tp_even\');"  style="width:100%; font-size:12px; color:blue; background-color: #fcfcc3;" name="tp_even'.$rowp['even_id'].'" id="tp_even'.$rowp['even_id'].'" title="TIPO DE EVENTO">'.$rowp['tp_evento'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(3,'.$rowp['even_id'].',\'titulo_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="titulo_even'.$rowp['even_id'].'" id="titulo_even'.$rowp['even_id'].'" title="TITULO DEL EVENTO">'.$rowp['titulo_evento'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(4,'.$rowp['even_id'].',\'org_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="org_even'.$rowp['even_id'].'" id="org_even'.$rowp['even_id'].'" title="ORGANIZADOR">'.$rowp['organizador'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(5,'.$rowp['even_id'].',\'dat_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="dat_even'.$rowp['even_id'].'" id="dat_even'.$rowp['even_id'].'" title="DATOS DEL EVENTO">'.$rowp['fecha_evento'].'</textarea></td>
                            <td><input type="text" class="form-control" onkeyup="update_evento(6,'.$rowp['even_id'].',\'imp_even\');" style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="imp_even'.$rowp['even_id'].'" id="imp_even'.$rowp['even_id'].'" title="FECHA DE IMPRESION" value="'.$rowp['fecha_evento_impresion'].'"></td>
                            <td align=center>
                              <a href="'.site_url("").'/participantes_eventosDNP/'.$rowp['even_id'].'" title="LISTA DE PARTICIPANTES" class="btn btn-default"><img src="'.base_url().'assets/ifinal/select.png" WIDTH="38" HEIGHT="38"/></a>
                            </td>
                          </tr>';
                        }
                      $data['eventos'].='
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </article>';

      $this->load->view('admin/mantenimiento/eventosDNP/listado_eventos', $data);
    }



  /*--------- VALIDA FORM 4 (2025) -----------*/
  public function valida_evento(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('even_org', 'even_org', 'required|trim');
        $this->form_validation->set_rules('even_tp', 'even_tp', 'required|trim');
      
        if ($this->form_validation->run()){
          header('Content-Type: text/html; charset=utf-8');
          /*------ INSERT EVENTO -------*/
          $data_to_store = array(
            'organizador' => strtoupper($this->input->post('even_org')),
            'tp_evento' => $this->input->post('even_tp'),
            'cod_evento' => strtoupper($this->input->post('cod_evento')),
            'titulo_evento' => strtoupper($this->input->post('evento')),
            'fecha_evento' => strtoupper($this->input->post('fecha_even')),
            'g_id' => $this->gestion,
            'fecha_evento_impresion' => $this->input->post('even_fech_impresion'),
          );
          $this->db->insert('eventosdnp', $data_to_store);
          $id_event=$this->db->insert_id(); 
          /*-------------------------------------*/

          $evento=$this->model_evento->get_evento($id_event);
          if(count($evento)==1){
            $this->session->set_flashdata('success','CORRECTO !!');
          }
          else{
            $this->session->set_flashdata('danger','ERROR!!');
          }

          redirect('eventosDNP');
        }
        else{
          $this->session->set_flashdata('danger','ERROR!!! :(');
          redirect('eventosDNP');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar !!!!</font></center>";
    }
  }



    /*---- UPDATE DATOS FORM 4 2025----*/
    public function update_datos_evento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $even_id = $this->security->xss_clean($post['even_id']);
        $nro = $this->security->xss_clean($post['nro']);
        $name_input = $this->security->xss_clean($post['name_input']);
        $detalle = $this->security->xss_clean($post['detalle']); /// input


          if($nro==1){ // cod even
            $campo='cod_evento';
          }
          elseif($nro==2){ /// evento
            $campo='tp_evento';
          }
          elseif($nro==3){
            $campo='titulo_evento';
          }
          elseif($nro==4){
            $campo='organizador';
          }
          elseif($nro==5){
            $campo='fecha_evento';
          }
          elseif($nro==6){
            $campo='fecha_evento_impresion';
          }

          /////
          $update_even = array(
            $campo => $detalle,
          );
          $this->db->where('even_id', $even_id);
          $this->db->update('eventosdnp', $update_even);
          ////


        $result = array(
          'respuesta' => 'correcto',
          //'update_informacion'=>$informacion,
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }



 /*------ Listado de Participantes -------*/
    public function participantes($even_id){
      $data['menu']=$this->menu(9);
      $get_evento=$this->model_evento->get_evento($even_id);
      $data['input_id']='<input class="form-control"  type="text" name="even_id" id="even_id" value="'.$even_id.'">';
      $lista_participantes=$this->model_evento->lista_participantes($even_id);
      $data['eventos']='';
      $data['eventos']='
          '.$this->style().'
          <style>
            #mdialTamanio2{
                width: 40% !important;
            }
          </style>
          <div class="row">
              <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <section id="widget-grid" class="well">
                        <h1><b>EVENTO: </b>'.$get_evento[0]['cod_evento'].' - '.$get_evento[0]['titulo_evento'].'</h1>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_form2" class="btn btn-default nuevo_form2" title="REGISTRAR PARTICIPANTE" >
                          <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>REGISTRAR PARTICIPANTE</b>
                        </a>
                  </section>
              </article>
          </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="well well-sm well-light">
                  <div class="row">
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                      <div class="jarviswidget jarviswidget-color-darken">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                            <h2 class="font-md"><strong>LISTA DE PARTICIPANTES</strong></h2>  
                        </header>
                          <div>
                              <div class="widget-body no-padding">
                                   <input type="hidden" name="base" value="'.base_url().'">
                                    <table id="dt_basic" class="table table-bordered" style="width:100%;">
                                        <thead>
                                          <tr style="height:35px;" align="center">
                                            <th style="width:1%;" align="center">#</th>
                                            <th style="width:2%;" align="center">DEL</th>
                                            <th style="width:2%;" align="center">CI</th>
                                            <th style="width:5%;" title="TIPO"><center>TIPO DE CERTIFICADO</center></th>
                                            <th style="width:20%;" title="EVENTO"><center>NOMBRE COMPLETO</center></th>
                                            <th style="width:2%;" title="LISTADO"><center></center></th>
                                          </tr>
                                        </thead>
                                        <tbody>';
                                        $nro=0;
                                        foreach($lista_participantes as $rowp){
                                          $nro++;
                                          $data['eventos'].='
                                          <tr>
                                            <td>'.$nro.'</td>
                                            <td></td>
                                            <td>'.$rowp['ci'].'</td>
                                            <td>'.$rowp['tp_cert'].'</td>
                                            <td>'.$rowp['nombre_completo'].'</td>
                                            <td></td>
                                          </tr>';
                                        }
                                      $data['eventos'].='
                                        </tbody>
                                    </table>
                              </div>
                          </div>
                      </div>
                    
                      </article>
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                      <div class="jarviswidget jarviswidget-color-darken">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                            <h2 class="font-md"><strong>CERTIFICADO</strong></h2>  
                        </header>
                          <div>
                              <div class="widget-body no-padding">
                                
                              </div>
                           
                          </div>
                         
                      </div>
                     
                      </article>
                  </div>
              </div>
          </article>';

      $this->load->view('admin/mantenimiento/eventosDNP/listado_eventos', $data);
    }


  /*--------- VALIDA PARTICIPANTE -----------*/
  public function valida_participante(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('even_id', 'even_id', 'required|trim');
        $this->form_validation->set_rules('ci', 'ci', 'required|trim');
      
        if ($this->form_validation->run()){
          header('Content-Type: text/html; charset=utf-8');
          /*------ INSERT EVENTO -------*/
          $data_to_store = array(
            'even_id' => strtoupper($this->input->post('even_id')),
            'nombre_completo' => strtoupper($this->input->post('nombre')),
            'ci' => $this->input->post('ci'),
            'tp_cert' => $this->input->post('tp_cert'),
          );
          $this->db->insert('participantes', $data_to_store);
          $ci_id=$this->db->insert_id(); 
          /*-------------------------------------*/

          $participante=$this->model_evento->get_participante($ci_id);
          if(count($participante)==1){
            $this->session->set_flashdata('success','CORRECTO !!');
          }
          else{
            $this->session->set_flashdata('danger','ERROR!!');
          }

          redirect('participantes_eventosDNP/'.$this->input->post('even_id').'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR!!! :(');
          redirect('participantes_eventosDNP/'.$this->input->post('even_id').'');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar !!!!</font></center>";
    }
  }
















  public function style(){
    $tabla='
    <style>
            .table{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
            }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              font-size: 10px;
            }
            td{
              font-size: 10px;
            }
          </style>';
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