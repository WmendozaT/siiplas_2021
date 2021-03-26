<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Ccontrol_calidad extends CI_Controller {
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
                $this->load->model('mantenimiento/model_control_calidad');
                $this->load->model('programacion/model_proyecto');
                $this->load->model('programacion/model_faseetapa');
                $this->load->model('programacion/insumos/minsumos');
                $this->load->model('mantenimiento/model_partidas');
                $this->load->model('programacion/model_producto');
                $this->load->model('ejecucion/model_ejecucion');
                $this->load->library("security");
                $this->gestion = $this->session->userData('gestion');
                $this->rol = $this->session->userData('rol');
                $this->fun_id = $this->session->userData('fun_id');
                $this->tp_adm = $this->session->userData('tp_adm');
            }
            else{
                redirect('admin/dashboard');
            }
        }
        else{
                redirect('/','refresh');
        }
    }

    /* --------------- menu - control de calidad -------------- */
    public function control_calidad(){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');

    //  $data['tabla']=$this->tabla_buscador_unidad_medida();
      $this->load->view('admin/mantenimiento/control_calidad/menu', $data);
    }

    /* ---------- control de calidad - Unidad de Medida -------------- */
    public function select_control_calidad($tp){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');

      if($tp==1){
    //  echo "string";
        $data['tabla']=$this->tabla_buscador_unidad_medida();
        $this->load->view('admin/mantenimiento/control_calidad/vprincipal_umedida', $data);
      }
      elseif ($tp==2) {
        $data['tabla']=$this->tabla_buscador_concepto();
        $this->load->view('admin/mantenimiento/control_calidad/vprincipal_detalle', $data);
      }
      else{
        $data['tabla']=$this->tabla_buscador_unidad_medida_vacias();
        $this->load->view('admin/mantenimiento/control_calidad/vprincipal_umedida_vacias', $data);
      }
      
    }


    /*--------- Tabla Concepto de requerimiento ------------*/
    public function tabla_buscador_concepto(){
      $partidas=$this->model_control_calidad->list_partidas();
      $regionales=$this->model_control_calidad->regionales();
      $tabla='';

      $tabla.='<article class="col-sm-12 col-md-12 col-lg-4">';
      $tabla.='<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                  <h2>Control de Calidad</h2>
        
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">
        
                    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
                      
                      <fieldset>
                        <div class="form-group">
                          <label>CONCEPTO REQUERIMIENTO</label>
                          <input class="form-control" name="concepto" name="concepto" placeholder="CONCEPTO REQUERIMIENTOO" type="text">
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="form-group">
                          <label>REGIONAL</label>
                            <select class="form-control" name="dep_id" id="dep_id">
                              <option value="0">Todos</option>';
                              foreach($regionales as $rowd){
                                $tabla.='<option value='.$rowd['dep_id'].'>'.$rowd['dep_departamento'].'</option>';
                              }
                            $tabla.='
                            </select>
                        </div>
                      </fieldset>
                      <div class="form-actions">
                      <a href="'.site_url("").'/control_calidad" title="SALIR" class="btn btn-default">SALIR A MENU</a>
                      <input type="submit" name="submit" value="BUSCAR REQUERIMIENTOS" class="btn btn-primary">
                      </div>
                    </form>
        
                  </div>
        
                </div>
              </div>';
      $tabla.='</article>';

      if(isset($_POST['submit'])){
        $tabla.='<article class="col-sm-12 col-md-12 col-lg-8">';
        if($this->session->flashdata('success')){
          $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
        }
        elseif($this->session->flashdata('danger')){
          $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
        }

        $concepto = trim($_POST['concepto']);
        $dep_id = $_POST['dep_id'];

        if($concepto!=""){
          /*--- uni:insertado | ninguna partida | regional:todos---*/
          if($concepto!="" & $dep_id==0){
            $lista=$this->model_control_calidad->list_req_detalle_regionales_todos($concepto);
            $titulo='<b>CONCEPTO : </b>'.$concepto.' | <b>REGIONAL : </b>Todas las Regionales';
          }
          /*--- uni:insertado | regional:seleccionado---*/
          elseif ($concepto!="" & $dep_id!=0) {
            $regional=$this->model_proyecto->get_departamento($dep_id);
            $lista=$this->model_control_calidad->list_req_detalle_regionales_select($concepto,$dep_id);
            $titulo='<b>CONCEPTO : </b>'.$concepto.' | <b>REGIONAL : </b>'.$regional[0]['dep_departamento'].'';
          }

          $tabla.=''.$this->requerimientos_concepto($lista,$titulo,$concepto).'';
        }
        else{
          $tabla.=' <div class="alert alert-danger" role="alert">
                      Registre Concepto de Requerimiento
                    </div>';
        }

        $tabla.='</article>';
      }
      
      return $tabla;
    }


    /*--------- Tabla Unidad de Medida ------------*/
    public function tabla_buscador_unidad_medida(){
      $partidas=$this->model_control_calidad->list_partidas();
      $regionales=$this->model_control_calidad->regionales();
      $tabla='';

      $tabla.='<article class="col-sm-12 col-md-12 col-lg-4">';
      $tabla.='<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                  <h2>Control de Calidad</h2>
        
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">
        
                    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
                      
                      <fieldset>
                        <div class="form-group">
                          <label>UNIDAD DE MEDIDA</label>
                          <input class="form-control" name="unidad" name="unidad" placeholder="UNIDAD DE MEDIDA" type="text">
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="form-group">
                          <label>PARTIDA</label>
                            <select class="form-control" name="par_id" id="par_id">
                              <option value="">Ninguno</option>';
                              foreach($partidas as $rowp){
                                $tabla.='<option value='.$rowp['par_id'].'>'.$rowp['par_codigo'].' - '.$rowp['par_nombre'].'</option>';
                              }
                            $tabla.='
                            </select>
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="form-group">
                          <label>REGIONAL</label>
                            <select class="form-control" name="dep_id" id="dep_id">
                              <option value="0">Todos</option>';
                              foreach($regionales as $rowd){
                                $tabla.='<option value='.$rowd['dep_id'].'>'.$rowd['dep_departamento'].'</option>';
                              }
                            $tabla.='
                            </select>
                        </div>
                      </fieldset>
                      <div class="form-actions">
                      <a href="'.site_url("").'/control_calidad" title="SALIR" class="btn btn-default">SALIR A MENU</a>
                      <input type="submit" name="submit" value="BUSCAR REQUERIMIENTOS" class="btn btn-primary">
                      </div>
                    </form>
        
                  </div>
        
                </div>
              </div>';
      $tabla.='</article>';

      if(isset($_POST['submit'])){
        $tabla.='<article class="col-sm-12 col-md-12 col-lg-8">';
        if($this->session->flashdata('success')){
          $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
        }
        elseif($this->session->flashdata('danger')){
          $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
        }

        $unidad = trim($_POST['unidad']);
        $par_id = $_POST['par_id'];
        $dep_id = $_POST['dep_id'];

        if($unidad!=""){
          /*--- uni:insertado | ninguna partida | regional:todos---*/
          if($unidad!="" & $par_id=="" & $dep_id==0){
            $lista=$this->model_control_calidad->list_req_sin_partida_regionales_todos($unidad);
            $titulo='<b>UNIDAD DE MEDIDA : </b>'.$unidad.' | <b>PARTIDA : </b>Ninguno | <b>REGIONAL : </b>Todas las Regionales';
          }
          /*--- uni:insertado | partida seleccionado | regional:todos---*/
          elseif ($unidad!="" & $par_id!="" & $dep_id==0) {
            $partida=$this->model_partidas->dato_par($par_id);
            $lista=$this->model_control_calidad->list_req_con_partida_regionales_todos($unidad,$partida[0]['par_id']);
            $titulo='<b>UNIDAD DE MEDIDA : </b>'.$unidad.' | <b>PARTIDA : '.$partida[0]['par_codigo'].'</b> | <b>REGIONAL : </b>Todas las Regionales';
          }
          /*--- uni:insertado | partida seleccionado | regional:seleccionado---*/
          elseif ($unidad!="" & $par_id!="" & $dep_id!=0) {
            $partida=$this->model_partidas->dato_par($par_id);
            $regional=$this->model_proyecto->get_departamento($dep_id);
            $lista=$this->model_control_calidad->list_req_con_partida_regionales_select($unidad,$partida[0]['par_id'],$dep_id);
            $titulo='<b>UNIDAD DE MEDIDA : </b>'.$unidad.' | <b>PARTIDA : '.$partida[0]['par_codigo'].'</b> | <b>REGIONAL : </b>'.$regional[0]['dep_departamento'].'';
          }
          elseif ($unidad!="" & $par_id=="" & $dep_id!=0) {
            $regional=$this->model_proyecto->get_departamento($dep_id);
            $lista=$this->model_control_calidad->list_req_sin_partida_regionales_select($unidad,$dep_id);
            $titulo='<b>UNIDAD DE MEDIDA : </b>'.$unidad.' | <b>PARTIDA : No seleccionado</b> | <b>REGIONAL : </b>'.$regional[0]['dep_departamento'].'';
          }
          else{
            $tabla.=''.$unidad.'-'.$par_id.'-'.$dep_id.'<br>';
          }

          $tabla.=''.$this->requerimientos_umedida($lista,$titulo,1,$unidad).'';
        }
        else{
          if($par_id==""){
            $tabla.='<div class="alert alert-danger" role="alert">
                      Seleccione Partida
                    </div>';
          }
          /*--- partida seleccionada | regional:todos ---*/
          elseif($par_id!="" & $dep_id==0){
            $partida=$this->model_partidas->dato_par($par_id);
            $lista=$this->model_control_calidad->list_req_sin_unidad_con_partida_regionales_todos($partida[0]['par_id']);
            $titulo='<b>UNIDAD DE MEDIDA : </b>No registrado | <b>PARTIDA : '.$partida[0]['par_codigo'].'</b> | <b>REGIONAL : </b>Todas las regionales';
            
            $tabla.=''.$this->requerimientos_umedida($lista,$titulo,2,$partida[0]['par_codigo']).'';
          }
          /*--- partida seleccionada | regional:seleccionado ---*/
          else{
            $partida=$this->model_partidas->dato_par($par_id);
            $regional=$this->model_proyecto->get_departamento($dep_id);
            $lista=$this->model_control_calidad->list_req_sin_unidad_con_partida_regionales_select($partida[0]['par_id'],$dep_id);
            $titulo='<b>UNIDAD DE MEDIDA : </b>No registrado | <b>PARTIDA : '.$partida[0]['par_codigo'].'</b> | <b>REGIONAL : </b>'.$regional[0]['dep_departamento'].'';
          
            $tabla.=''.$this->requerimientos_umedida($lista,$titulo,2,$partida[0]['par_codigo']).'';
          }
          
        }

        $tabla.='</article>';
      }
      

      return $tabla;
    }

    /*--------- Tabla Unidad de Medida - Nulas ------------*/
    public function tabla_buscador_unidad_medida_vacias(){
      $partidas=$this->model_control_calidad->list_partidas();
      $regionales=$this->model_control_calidad->regionales();
      $tabla='';

      $tabla.='<article class="col-sm-12 col-md-12 col-lg-3">';
      $tabla.='<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                  <h2>Control de Calidad - Unidad de Medida Nulas</h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">
        
                    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
                      <fieldset>
                        <div class="form-group">
                          <label>REGIONAL</label>
                            <select class="form-control" name="dep_id" id="dep_id">
                              <option value="0">Todos</option>';
                              foreach($regionales as $rowd){
                                $tabla.='<option value='.$rowd['dep_id'].'>'.$rowd['dep_departamento'].'</option>';
                              }
                            $tabla.='
                            </select>
                        </div>
                      </fieldset>
                      <div class="form-actions">
                      <a href="'.site_url("").'/control_calidad" title="SALIR" class="btn btn-default">SALIR A MENU</a>
                      <input type="submit" name="submit" value="BUSCAR" class="btn btn-primary">
                      </div>
                    </form>
        
                  </div>
        
                </div>
              </div>';
      $tabla.='</article>';

      if(isset($_POST['submit'])){
        $tabla.='<article class="col-sm-12 col-md-12 col-lg-9">';
        if($this->session->flashdata('success')){
          $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
        }
        elseif($this->session->flashdata('danger')){
          $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
        }

        $dep_id = $_POST['dep_id'];

        if($dep_id==0){
          $titulo='TODAS LAS REGIONALES';
          $lista=$this->model_control_calidad->list_requerimiento_umedida_nulos_todos();
        }
        else{
          $regional=$this->model_proyecto->get_departamento($dep_id);
          $titulo='REGIONAL : '.$regional[0]['dep_departamento'].'';
          $lista=$this->model_control_calidad->list_requerimiento_umedida_nulos_regional($dep_id);
        }
        
        $tabla.=''.$this->requerimientos_umedida_vacias($lista,$titulo).'';

        $tabla.='</article>';
      }
      

      return $tabla;
    }
    
    /*------------------ Lista de Requerimientos unidad de medida  ------------*/
    public function requerimientos_umedida($lista,$titulo,$tp,$unidad){
      if($tp==1){
        $color1='#d5f4f5';
        $color2='';
        $tit='UNIDAD DE MEDIDA BUSCADA';
      }
      else{
        $color1='';
        $color2='#d5f4f5';
        $tit='PARTIDA SELECCIONADA';
      }
      $tabla='';
      $tabla .='
                <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                  <h2>'.count($lista).' REQUERIMIENTOS ENCONTRADOS</h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body no-padding">

                    <form id="formulario" name="formulario" method="post" action="'.site_url("").'/mantenimiento/ccontrol_calidad/valida_update_unidad" class="smart-form">
                      <br><div class="alert alert-success alert-block">'.$titulo.'</div><hr>
                      <input type="text" class="form-control" id="kwd_search" style="width:50%;" placeholder="BUSQUEDA ...." /><br>
                      <table id="dt_basic" class="table table-bordered">
                        <thead>
                          <tr style="height:65px;">
                            <th style="width:1%;" bgcolor="#474544">#</th>
                            <th style="width:5%;" bgcolor="#474544" title="REGIONAL">REGIONAL</th>
                            <th style="width:5%;" bgcolor="#474544" title="UNIDAD DE MEDIDA">UNIDAD DE MEDIDA</th>
                            <th bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                            <th bgcolor="#474544" title="CANTIDAD">CANTIDAD</th>
                            <th bgcolor="#474544" title="COSTO UNITARIO">COSTO UNITARIO</th>
                            <th bgcolor="#474544" title="COSTO TOTAL">COSTO TOTAL</th>
                            <th bgcolor="#474544" title="PARTIDA">PARTIDA</th>
                            <th bgcolor="#474544" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                            <th bgcolor="#474544" title="TIPO DE OPERACION">TIPO DE OPERACI&Oacute;N</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($lista as $row){
                          $nro++;
                          $tabla.='<tr>
                                    <td title="'.$row['ins_id'].'"><input type="hidden" name="ins_id[]" value="'.$row['ins_id'].'">'.$nro.'-'.$row['ins_id'].'</td>
                                    <td>'.strtoupper($row['dep_departamento']).'</td>
                                    <td bgcolor="'.$color1.'">'.$row['ins_unidad_medida'].'</td>
                                    <td>'.$row['ins_detalle'].'</td>
                                    <td>'.$row['ins_cant_requerida'].'</td>
                                    <td>'.$row['ins_costo_unitario'].'</td>
                                    <td>'.$row['ins_costo_total'].'</td>
                                    <td bgcolor="'.$color2.'">'.$row['par_codigo'].'</td>
                                    <td>'.$row['proy_nombre'].'</td>
                                    <td>'.$row['tp_tipo'].'</td>
                                  </tr>';
                        }
                        $tabla.='
                        </tbody>
                      </table>

                        <fieldset>
                          <input type="hidden" name="nro" id="nro" value="'.count($lista).'">
                          <div class="row">
                            <section class="col col-6">
                              <label class="label">'.$tit.'</label>
                              <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input type="text" name="umedida1" id="umedida1" value="'.$unidad.'" disabled="true">
                              </label>
                            </section>
                            <section class="col col-6">
                              <label class="label">UNIDAD DE MEDIDA A MODIFICAR</label>
                              <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input type="text" name="umedida" id="umedida">
                              </label>
                            </section>
                          </div>
                        </fieldset>
                        
                        <footer>
                          <input type="button" value="MODIFICAR UNIDAD DE MEDIDA" id="btsubmit" class="btn btn-primary" title="MODIFICAR UNIDAD DE MEDIDA">
                        </footer>
                    </form>           
                    
                  </div>
                </div>
              </div>';

      return $tabla;
    }

    /*------------------ Lista de Requerimientos unidad de medida  ------------*/
    public function requerimientos_umedida_vacias($lista,$titulo){
      $tabla='';
      $tabla .='
                <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                  <h2>'.count($lista).' REQUERIMIENTOS ENCONTRADOS</h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body no-padding">

                    <form id="formulario" name="formulario" method="post" action="'.site_url("").'/mantenimiento/ccontrol_calidad/valida_update_unidad_vacias" class="smart-form">
                      <br><div class="alert alert-success alert-block">'.$titulo.'</div><hr>
                      <input type="text" class="form-control" id="kwd_search" style="width:50%;" placeholder="BUSQUEDA ...." /><br>
                      <table id="dt_basic" class="table table-bordered">
                        <thead>
                          <tr style="height:65px;">
                            <th style="width:1%;" bgcolor="#474544">#</th>
                            <th style="width:5%;" bgcolor="#474544" title="REGIONAL">REGIONAL</th>
                            <th style="width:20%;" bgcolor="#474544" title="UNIDAD DE MEDIDA">UNIDAD DE MEDIDA</th>
                            <th style="width:20%;" bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                            <th bgcolor="#474544" title="CANTIDAD">CANTIDAD</th>
                            <th bgcolor="#474544" title="COSTO UNITARIO">COSTO UNITARIO</th>
                            <th bgcolor="#474544" title="COSTO TOTAL">COSTO TOTAL</th>
                            <th bgcolor="#474544" title="PARTIDA">PARTIDA</th>
                            <th bgcolor="#474544" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                            <th bgcolor="#474544" title="TIPO DE OPERACION">TIPO DE OPERACI&Oacute;N</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($lista as $row){
                          $nro++;
                          $tabla.='<tr>
                                    <td title="'.$row['ins_id'].'"><input type="hidden" name="ins_id[]" value="'.$row['ins_id'].'">'.$nro.'</td>
                                    <td>'.strtoupper($row['dep_departamento']).'</td>
                                    <td bgcolor="#f5c9c9" style="width:20%;" >
                                      <input type="text" class="form-control"  name="umedida[]" value="'.$row['ins_unidad_medida'].'" placeholder="Null">
                                    </td>
                                    <td>'.$row['ins_detalle'].'</td>
                                    <td>'.$row['ins_cant_requerida'].'</td>
                                    <td>'.$row['ins_costo_unitario'].'</td>
                                    <td>'.$row['ins_costo_total'].'</td>
                                    <td bgcolor="#d5f4f5">'.$row['par_codigo'].'</td>
                                    <td>'.$row['proy_nombre'].'</td>
                                    <td>'.$row['tp_tipo'].'</td>
                                  </tr>';
                        }
                        $tabla.='
                        </tbody>
                      </table>
                        <footer>
                          <input type="button" value="ACTUALIZAR DATOS" id="btsubmit" class="btn btn-primary" title="MODIFICAR DATOS DE UNIDAD DE MEDIDA">
                        </footer>
                    </form>           
                    
                  </div>
                </div>
              </div>';

      return $tabla;
    }


    /*------------- Lista de Requerimientos concepto requerimiento  ---------------*/
    public function requerimientos_concepto($lista,$titulo,$concepto){
      $tabla='';
      $tabla .='<div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                  <h2>'.count($lista).' REQUERIMIENTOS ENCONTRADOS</h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body no-padding">

                    <form id="formulario" name="formulario" method="post" action="'.site_url("").'/mantenimiento/ccontrol_calidad/valida_update_concepto" class="smart-form">
                      <br><div class="alert alert-success alert-block">'.$titulo.'</div><hr>
                      <input type="text" class="form-control" id="kwd_search" style="width:50%;" placeholder="BUSQUEDA ...." /><br>
                      <table id="dt_basic" class="table table-bordered">
                        <thead>
                          <tr style="height:65px;">
                            <th style="width:1%;" bgcolor="#474544">#</th>
                            <th style="width:5%;" bgcolor="#474544" title="REGIONAL">REGIONAL</th>
                            <th style="width:5%;" bgcolor="#474544" title="UNIDAD DE MEDIDA">UNIDAD DE MEDIDA</th>
                            <th bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                            <th bgcolor="#474544" title="CANTIDAD">CANTIDAD</th>
                            <th bgcolor="#474544" title="COSTO UNITARIO">COSTO UNITARIO</th>
                            <th bgcolor="#474544" title="COSTO TOTAL">COSTO TOTAL</th>
                            <th bgcolor="#474544" title="PARTIDA">PARTIDA</th>
                            <th bgcolor="#474544" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                            <th bgcolor="#474544" title="TIPO DE OPERACI&Oacute;N">TIPO DE OPERACI&Oacute;N</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($lista as $row){
                          $nro++;
                          $tabla.='<tr>
                                    <td title="'.$row['ins_id'].'"><input type="hidden" name="ins_id[]" value="'.$row['ins_id'].'">'.$nro.'</td>
                                    <td>'.strtoupper($row['dep_departamento']).'</td>
                                    <td>'.$row['ins_unidad_medida'].'</td>
                                    <td bgcolor="#d5f4f5">'.$row['ins_detalle'].'</td>
                                    <td>'.$row['ins_cant_requerida'].'</td>
                                    <td>'.$row['ins_costo_unitario'].'</td>
                                    <td>'.$row['ins_costo_total'].'</td>
                                    <td>'.$row['par_codigo'].'</td>
                                    <td>'.$row['proy_nombre'].'</td>
                                    <td>'.$row['tp_tipo'].'</td>
                                  </tr>';
                        }
                        $tabla.='
                        </tbody>
                      </table>

                        <fieldset>
                          <input type="hidden" name="nro" id="nro" value="'.count($lista).'">
                          <div class="row">
                            <section class="col col-6">
                              <label class="label">CONCEPTO BUSCADO</label>
                              <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input type="text"  value="'.$concepto.'" disabled="true">
                              </label>
                            </section>
                            <section class="col col-6">
                              <label class="label">CONCEPTO A MODIFICAR</label>
                              <label class="input">
                                <i class="icon-append fa fa-tag"></i>
                                <input type="text" name="concepto" id="concepto">
                              </label>
                            </section>
                          </div>
                        </fieldset>
                        
                        <footer>
                          <input type="button" value="MODIFICAR CONCEPTO DE REQUERIMIENTO" id="btsubmit" class="btn btn-primary" title="MODIFICAR CONCEPTO DE REQUERIMIENTO">
                        </footer>
                    </form>           
                    
                  </div>
                </div>
              </div>';

      return $tabla;
    }

    /*--- VALIDA UPDATE UNIDAD DE MEDIDA ---*/
    function valida_update_unidad(){
    if ($this->input->post() & $this->input->server('REQUEST_METHOD') === 'POST') {
        $post = $this->input->post();
        $unidad = $this->security->xss_clean($post['umedida']); /// Unidad de medida
        $nro = $this->security->xss_clean($post['nro']); /// Nro

        //  echo "Nro : ".$nro." - Unidad : ".$unidad."<br>";
        $nro_mod=0;
        if (!empty($_POST["ins_id"]) && is_array($_POST["ins_id"]) ){
            foreach ( array_keys($_POST["ins_id"]) as $como ){
            $nro_mod++;
            $insumo_a= $this->model_control_calidad->get_insumo($_POST["ins_id"][$como]); /// Datos requerimientos Antes
            
            $tab[1][$nro_mod]=$nro_mod;
            $tab[2][$nro_mod]=strtoupper($insumo_a[0]['dep_departamento']);
            $tab[3][$nro_mod]=$insumo_a[0]['ins_unidad_medida'];
            $tab[4][$nro_mod]=$unidad;
            $tab[5][$nro_mod]=$insumo_a[0]['ins_detalle'];
            $tab[6][$nro_mod]=$insumo_a[0]['ins_cant_requerida'];
            $tab[7][$nro_mod]=$insumo_a[0]['ins_costo_unitario'];
            $tab[8][$nro_mod]=$insumo_a[0]['ins_costo_total'];
            $tab[9][$nro_mod]=$insumo_a[0]['par_codigo'];
            $tab[10][$nro_mod]=$insumo_a[0]['proy_nombre'];

            /*------------------------------------------------*/
            $update_ins = array(
              'ins_unidad_medida' => $unidad,
              'fun_id' => $this->fun_id,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
            );
            $this->db->where('ins_id', $_POST["ins_id"][$como]);
            $this->db->update('insumos', $update_ins);
            /*------------------------------------------------*/
          }
        }

        $data['menu']=$this->menu(9);
        $data['tp']=1;
        $data['comparativo']=$this->tabla_modificado($tab,$nro_mod,1);
        $this->load->view('admin/mantenimiento/control_calidad/comparativo', $data);
      }
    }

    /*--- VALIDA UPDATE UNIDAD DE MEDIDA VACIAS ---*/
/*        function valida_update_unidad_vacias(){
phpinfo ();
    }*/

    function valida_update_unidad_vacias(){
    if ($this->input->post() & $this->input->server('REQUEST_METHOD') === 'POST') {
        $post = $this->input->post();

        $nro_mod=0;
        if (!empty($_POST["ins_id"]) && is_array($_POST["ins_id"]) ){
          foreach ( array_keys($_POST["ins_id"]) as $como ){
            if(strlen($_POST["umedida"][$como])!=0){

              $nro_mod++;
              $insumo_a= $this->model_control_calidad->get_insumo($_POST["ins_id"][$como]); /// Datos requerimientos Antes
              
              $tab[1][$nro_mod]=$nro_mod;
              $tab[2][$nro_mod]=strtoupper($insumo_a[0]['dep_departamento']);
              $tab[3][$nro_mod]=$insumo_a[0]['ins_unidad_medida'];
              $tab[4][$nro_mod]=$_POST["umedida"][$como];
              $tab[5][$nro_mod]=$insumo_a[0]['ins_detalle'];
              $tab[6][$nro_mod]=$insumo_a[0]['ins_cant_requerida'];
              $tab[7][$nro_mod]=$insumo_a[0]['ins_costo_unitario'];
              $tab[8][$nro_mod]=$insumo_a[0]['ins_costo_total'];
              $tab[9][$nro_mod]=$insumo_a[0]['par_codigo'];
              $tab[10][$nro_mod]=$insumo_a[0]['proy_nombre'];

              /*------------------------------------------------*/
              $update_ins = array(
                'ins_unidad_medida' => strtoupper($_POST["umedida"][$como]),
                'fun_id' => $this->fun_id,
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
              );
              $this->db->where('ins_id', $_POST["ins_id"][$como]);
              $this->db->update('insumos', $update_ins);
              /*------------------------------------------------*/
            } 
          }
        }

        $data['menu']=$this->menu(9);
        $data['tp']=3;
        $data['comparativo']=$this->tabla_modificado($tab,$nro_mod,1);
        $this->load->view('admin/mantenimiento/control_calidad/comparativo', $data);
      }
    }

    /*--- VALIDA UPDATE CONCEPTO REQUERIMIENTO ---*/
    function valida_update_concepto(){
    if ($this->input->post() & $this->input->server('REQUEST_METHOD') === 'POST') {
        $post = $this->input->post();
        $concepto = $this->security->xss_clean($post['concepto']); /// Concepto Reuqerimiento
        $nro = $this->security->xss_clean($post['nro']); /// Nro

        //  echo "Nro : ".$nro." - Unidad : ".$unidad."<br>";
        $nro_mod=0;
        if (!empty($_POST["ins_id"]) && is_array($_POST["ins_id"]) ){
            foreach ( array_keys($_POST["ins_id"]) as $como ){
            $nro_mod++;
            $insumo_a= $this->model_control_calidad->get_insumo($_POST["ins_id"][$como]); /// Datos requerimientos Antes
            
            $tab[1][$nro_mod]=$nro_mod;
            $tab[2][$nro_mod]=strtoupper($insumo_a[0]['dep_departamento']);
            $tab[3][$nro_mod]=$insumo_a[0]['ins_unidad_medida'];
            $tab[4][$nro_mod]=$insumo_a[0]['ins_detalle'];
            $tab[5][$nro_mod]=$concepto;
            $tab[6][$nro_mod]=$insumo_a[0]['ins_cant_requerida'];
            $tab[7][$nro_mod]=$insumo_a[0]['ins_costo_unitario'];
            $tab[8][$nro_mod]=$insumo_a[0]['ins_costo_total'];
            $tab[9][$nro_mod]=$insumo_a[0]['par_codigo'];
            $tab[10][$nro_mod]=$insumo_a[0]['proy_nombre'];

            /*------------------------------------------------*/
            $update_ins = array(
              'ins_detalle' => $concepto,
              'fun_id' => $this->fun_id,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
            );
            $this->db->where('ins_id', $_POST["ins_id"][$como]);
            $this->db->update('insumos', $update_ins);
            /*------------------------------------------------*/
          }
        }

        $data['menu']=$this->menu(9);
        $data['tp']=2;
        $data['comparativo']=$this->tabla_modificado($tab,$nro_mod,2);
        $this->load->view('admin/mantenimiento/control_calidad/comparativo', $data);
      }
    }

    /*------- Tabla antes y despues del control de calidad --------*/
    public function tabla_modificado($tab,$nro,$tp){
      $tabla='';
      if($tp==1){
        $tabla.='
          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" >
                <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md"><strong>'.$nro.' REQUERIMIENTOS ACTUALIZADOS</strong></h2>  
                </header>
              <div>
                <div class="widget-body no-padding">
                  <table id="dt_basic1" class="table table-bordered" style="width:100%;" font-size: "7px";>
                      <thead>
                        <tr style="height:65px;">
                          <th style="width:1%;" bgcolor="#474544">#</th>
                          <th style="width:10%;" bgcolor="#474544" title="REGIONAL">REGIONAL</th>
                          <th style="width:10%;" bgcolor="#474544" title="UNIDAD DE MEDIDA ANTERIOR">UNIDAD DE MEDIDA ANTERIOR</th>
                          <th style="width:10%;" bgcolor="#474544" title="UNIDAD DE MEDIDA ANTERIOR">UNIDAD DE MEDIDA POSTERIOR</th>
                          <th style="width:20%;" bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                          <th bgcolor="#474544" title="CANTIDAD">CANTIDAD</th>
                          <th bgcolor="#474544" title="COSTO UNITARIO">COSTO UNITARIO</th>
                          <th bgcolor="#474544" title="COSTO TOTAL">COSTO TOTAL</th>
                          <th bgcolor="#474544" title="PARTIDA">PARTIDA</th>
                          <th bgcolor="#474544" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                        </tr>
                      </thead>
                    <tbody>';
                      for ($i=1; $i <=$nro ; $i++) { 
                        $tabla.=
                          '<tr>
                            <td>'.$tab[1][$i].'</td>
                            <td>'.$tab[2][$i].'</td>
                            <td bgcolor="#f9e3df">'.$tab[3][$i].'</td>
                            <td bgcolor="#d6f9d0">'.$tab[4][$i].'</td>
                            <td>'.$tab[5][$i].'</td>
                            <td>'.$tab[6][$i].'</td>
                            <td>'.$tab[7][$i].'</td>
                            <td>'.$tab[8][$i].'</td>
                            <td>'.$tab[9][$i].'</td>
                            <td>'.$tab[10][$i].'</td>
                           </tr>';
                      }
                    $tabla.='
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </article>';
      }
      else{
        $tabla.='
          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" >
                <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md"><strong>'.$nro.' REQUERIMIENTOS ACTUALIZADOS</strong></h2>  
                </header>
              <div>
                <div class="widget-body no-padding">
                  <table id="dt_basic1" class="table table-bordered" style="width:100%;" font-size: "7px";>
                      <thead>
                        <tr style="height:65px;">
                          <th style="width:1%;" bgcolor="#474544">#</th>
                          <th style="width:10%;" bgcolor="#474544" title="REGIONAL">REGIONAL</th>
                          <th style="width:10%;" bgcolor="#474544" title="UNIDAD DE MEDIDA ANTERIOR">UNIDAD DE MEDIDA ANTERIOR</th>
                          <th style="width:20%;" bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO ANTERIOR</th>
                          <th style="width:10%;" bgcolor="#474544" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO ACTUAL</th>
                          <th bgcolor="#474544" title="CANTIDAD">CANTIDAD</th>
                          <th bgcolor="#474544" title="COSTO UNITARIO">COSTO UNITARIO</th>
                          <th bgcolor="#474544" title="COSTO TOTAL">COSTO TOTAL</th>
                          <th bgcolor="#474544" title="PARTIDA">PARTIDA</th>
                          <th bgcolor="#474544" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                        </tr>
                      </thead>
                    <tbody>';
                      for ($i=1; $i <=$nro ; $i++) { 
                        $tabla.=
                          '<tr>
                            <td>'.$tab[1][$i].'</td>
                            <td>'.$tab[2][$i].'</td>
                            <td>'.$tab[3][$i].'</td>
                            <td bgcolor="#f9e3df">'.$tab[4][$i].'</td>
                            <td bgcolor="#d6f9d0">'.$tab[5][$i].'</td>
                            <td>'.$tab[6][$i].'</td>
                            <td>'.$tab[7][$i].'</td>
                            <td>'.$tab[8][$i].'</td>
                            <td>'.$tab[9][$i].'</td>
                            <td>'.$tab[10][$i].'</td>
                           </tr>';
                      }
                    $tabla.='
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </article>';
      }
      return $tabla;
    }
    
    /*--- LISTA REQUERIMIENTOS (TODOS) ---*/
    function list_requerimientos(){
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['requerimientos']=$this->requerimientos(1);
      $this->load->view('admin/mantenimiento/control_calidad/list_requerimientos', $data);
    }

    /*--- EXPORTAR REQUERIMIENTOS (2019) ---*/
    public function exportar_requerimientos(){
      date_default_timezone_set('America/Lima');
      $fecha = date("d-m-Y H:i:s");
      $requerimientos=$this->requerimientos(2); //// Lista de Requerimientos Total
      
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=REQUERIMIENTOS_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$requerimientos."";
    }



    /*--------- Reuquerimientos ------------*/
    function requerimientos($tp){
      $requerimientos=$this->model_control_calidad->lista_requerimiento_todos();
      $tabl='';
      if($tp==1){
        $tab='id="dt_basic" class="table table-bordered" style="width:100%;" font-size: "7px";';
      }
      else{
        $tabl .='<style>
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
        $tab='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

      $tabl.='
              <table '.$tab.'>
                  <thead>
                    <tr class="modo1">
                      <th style="width:1%; height:35px;" style="background-color: #1c7368; color: #FFFFFF">#</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="REGIONAL">REGIONAL</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="UNIDAD DE MEDIDA">UNIDAD DE MEDIDA</th>
                      <th style="width:20%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DETALLE REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="CANTIDAD">CANTIDAD</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="COSTO UNITARIO">COSTO UNITARIO</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="COSTO TOTAL">COSTO TOTAL</th>
                      <th style="width:5%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="PARTIDA">PARTIDA</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="UNIDAD ORGANIZACIONAL">UNIDAD_ORGANIZACIONAL</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="UNIDAD ORGANIZACIONAL">TIPO DE OPERACI&Oacute;N</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="OBSERVACI&Oacute;N">OBSERVACI&Oacute;N</th>
                    </tr>
                  </thead>
                <tbody>';
                $nro=0;
                foreach($requerimientos  as $row){
                  $nro++;
                  $tabl.='
                  <tr style="height:11px;">
                    <td style="width: 1%; text-align: center; height:30px;">'.$nro.'--'.$row['ins_id'].'</td>
                    <td style="width: 10%; text-align: left; height:30px;">'.strtoupper($row['dep_departamento']).'</td>
                    <td style="width: 10%; text-align: left; height:30px;">'.strtoupper($row['ins_unidad_medida']).'</td>
                    <td style="width: 20%; text-align: left; height:30px;">'.mb_convert_encoding(''.strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 10%; text-align: right; height:30px;;">'.$row['ins_cant_requerida'].'</td>
                    <td style="width: 10%; text-align: right; height:30px;;">'.$row['ins_costo_unitario'].'</td>
                    <td style="width: 10%; text-align: right; height:30px;;">'.$row['ins_costo_total'].'</td>
                    <td style="width: 5%; text-align: center; height:30px;">'.$row['par_codigo'].'</td>
                    <td style="width: 10%; text-align: left; height:30px;">'.mb_convert_encoding(''.strtoupper($row['proy_nombre']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 10%; text-align: left; height:30px;">'.mb_convert_encoding(''.strtoupper($row['tp_tipo']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 10%; text-align: left; height:30px;">'.mb_convert_encoding(''.strtoupper($row['ins_observacion']), 'cp1252', 'UTF-8').'</td>
                  </tr>';
                }
        $tabl.'</tbody>
              </table>';
      return $tabl;
    }

    /*--------- Operaciones ------------*/
    /*function operaciones($tp){
      $operaciones=$this->model_control_calidad->lista_operaciones_todos();
      $tabl='';
      if($tp==1){
        $tab='id="dt_basic" class="table table-bordered" style="width:100%;" font-size: "7px";';
      }
      else{
        $tabl .='<style>
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
        $tab='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

      $tabl.='
              <table '.$tab.'>
                  <thead>
                    <tr class="modo1">
                      <th colspan=14 style="height:30px;"></th>
                      <th colspan=12 style="height:30px;">PROGRAMACI&Oacute;N'.$this->gestion.'</th>
                      <th></th>
                      <th colspan=12>EVALUACI&Oacute;N '.$this->gestion.'</th>
                      <th></th>
                    </tr> 
                    <tr class="modo1">
                      <th style="width:1%; height:35px;" style="background-color: #1c7368; color: #FFFFFF">#</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="REGIONAL">REGIONAL</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="APERTURA PROGRAMATICA">APERTURA PROGRAMATICA</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="UNIDAD ORGANIZACIONAL">UNIDAD ORGANIZACIONAL</th>
                      <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="CÓDIGO OBJETIVO ESTRATEGICO">COD.OE.</th>
                      <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="CÓDIGO ACCIÓN ESTRATEGICA">COD. AE.</th>
                      <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ID OPE">ID</th>
                      <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ID OPE">PRIORIDAD</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="OPERACIÓN">OPERACI&Oacute;N - '.$this->gestion.'</th>
                      <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="RESULTADO">RESULTADO</th>
                      <th style="width:5%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="INDICADOR">INDICADOR</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="TIPO DE META">TIPO DE META</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="LINEA BASE">LINEA BASE</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="META">META</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ENERO">ENE.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="FEBRERO">FEB.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MARZO">MAR.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ABRIL">ABR.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MAYO">MAY.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JUNIO">JUN.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JULIO">JUL.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="AGOSTO">AGO.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="SEPTIEMBRE">SEP.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="OCTUBRE">OCT.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="NOVIEMBRE">NOV.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DICIEMBRE">DIC.</th>
                      <th style="width:8%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MEDIO DE VERIFICACI&Oacute;N">MEDIO DE VERIFICACI&Oacute;N</th>

                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ENERO">ENE.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="FEBRERO">FEB.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MARZO">MAR.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ABRIL">ABR.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MAYO">MAY.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JUNIO">JUN.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JULIO">JUL.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="AGOSTO">AGO.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="SEPTIEMBRE">SEP.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="OCTUBRE">OCT.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="NOVIEMBRE">NOV.</th>
                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DICIEMBRE">DIC.</th>

                      <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DICIEMBRE">PRESUPUESTO</th>
                    </tr>
                  </thead>
                <tbody>';
                $nro=0;
                foreach($operaciones  as $row){
                  $ev=$this->model_producto->producto_ejecutado($row['prod_id'],$this->gestion);
                  $dat_oe=' - '; $dat_ae=' - ';
                  if($this->gestion==2018){
                    $red=$this->model_ejecucion->get_red_prod($row['prod_id']);
                    if(count($red)!=0){
                      $dat_oe='OE.-'.$red[0]['obj_codigo'].''; $dat_ae='AE.-'.$red[0]['acc_codigo'].'';
                    }
                    else{
                      $dat_oe='OE.-'; $dat_ae='AE.-';
                    }
                  }
                  else{
                    if($row['acc_id']!=''){
                      $acc=$this->model_producto->operacion_accion($row['acc_id']);
                      if(count($acc)!=0){
                        $dat_oe='OE.-'.$acc[0]['obj_codigo'].''; $dat_ae='AE.-'.$acc[0]['acc_codigo'].'';
                      }
                      else{
                        $dat_oe='OE.-'; $dat_ae='AE.-';
                      }
                    }
                  }

                  $tp='';
                  if($row['indi_id']==2){
                    $tp='%';
                  }
                  $nro++;
                  $tabl.='
                  <tr style="height:11px;">
                    <td style="width: 1%; text-align: center; height:35px;">'.$nro.'</td>
                    <td style="width: 10%; text-align: left; height:35px;">'.mb_convert_encoding(''.strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 10%; text-align: left; height:35px;">\''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'\'</td>
                    <td style="width: 10%; text-align: left; height:35px;">'.mb_convert_encoding(''.strtoupper($row['proy_nombre']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 2%; text-align: left; height:35px;">'.$dat_oe.'</td>
                    <td style="width: 2%; text-align: left; height:35px;;">'.$dat_ae.'</td>
                    <td style="width: 2%; text-align: left; height:35px;;">'.$row['prod_id'].'</td>
                    <td style="width: 2%; text-align: left; height:35px;;">'.$row['prod_priori'].'</td>
                    <td style="width: 10%; text-align: left; height:35px;;">'.mb_convert_encoding(''.strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 10%; text-align: left; height:35px;;">'.mb_convert_encoding(''.strtoupper($row['prod_resultado']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 5%; text-align: left; height:35px;">'.mb_convert_encoding(''.strtoupper($row['prod_indicador']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.mb_convert_encoding(''.strtoupper($row['mt_tipo']), 'cp1252', 'UTF-8').'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['prod_linea_base'],2).'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['prod_meta'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['enero'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['febrero'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['marzo'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['abril'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['mayo'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['junio'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['julio'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['agosto'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['septiembre'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['octubre'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['noviembre'],2).''.$tp.'</td>
                    <td style="width: 4%; text-align: left; height:35px;">'.round($row['diciembre'],2).''.$tp.'</td>
                    <td style="width: 8%; text-align: left; height:35px;">'.mb_convert_encoding(''.strtoupper($row['prod_fuente_verificacion']), 'cp1252', 'UTF-8').'</td>';
                    
                    if(count($ev)!=0){
                      $tabl.='
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['enero'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['febrero'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['marzo'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['abril'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['mayo'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['junio'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['julio'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['agosto'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['septiembre'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['octubre'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['noviembre'],2).'</td>
                      <td style="width: 4%; text-align: left; height:35px;">'.round($ev[0]['diciembre'],2).'</td>';
                    }
                    else{
                      $tabl.='
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>
                      <td style="width: 4%; text-align: left; height:35px;">0.00</td>';
                    }

                    $presupuesto=0;
                    if($row['proy_act']==0){
                      $ppto=$this->model_producto->monto_insumoproducto($row['prod_id']);
                    }
                    elseif($row['pfec_ejecucion']==1){
                      $ppto=$this->model_producto->monto_producto_insumoactividad($row['prod_id']);
                    }
                    else{
                      $ppto=$this->model_producto->monto_producto_insumocomponente($row['prod_id']);
                    }
                    
                    if(count($ppto)!=0){
                      $presupuesto=$ppto[0]['total'];
                    }

                    $tabl.='<td style="width: 4%; text-align: left; height:35px;">'.$presupuesto.'</td>';

                   $tabl.=' 
                  </tr>';
                }
        $tabl.'</tbody>
              </table>';
      return $tabl;
    }*/

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
            font-size: 7px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 8px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 7px;
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
        font-size: 7px;
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