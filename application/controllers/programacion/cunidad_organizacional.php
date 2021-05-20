<?php
class Cunidad_organizacional extends CI_Controller {  
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null){
    $this->load->model('menu_modelo');
    $this->load->model('Users_model','',true);
    $this->load->model('programacion/model_proyecto');
    $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
    $this->load->model('mantenimiento/model_estructura_org');
    $this->load->library('security');
    $this->gestion = $this->session->userData('gestion');
    $this->adm = $this->session->userData('adm'); // 1: Adm. Nacional, 2: Regional
    $this->dist = $this->session->userData('dist');
    $this->rol = $this->session->userData('rol_id');
    $this->dist_tp = $this->session->userData('dist_tp');
    $this->fun_id = $this->session->userdata("fun_id");
    $this->tp_adm = $this->session->userdata("tp_adm"); // 1: Privilegios, 0: sin Privilegios
    $this->load->library('genera_informacion');
    }else{
        redirect('/','refresh');
    }
  }

    /*------- Tipo de Responsable -------*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='<b>RESPONSABLE :</b> NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='<b>RESPONSABLE :</b> '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }

    /*--- Lista de Unidad - Regional ---*/
    public function list_unidad(){
      $data['menu']=$this->menu();
      $data['res_dep']=$this->tp_resp();
      if($this->adm==1){
        $data['regional']=$this->regionales(1);
        $this->load->view('admin/programacion/unidad_organizacional/regional', $data);
      }
      else{
        $ddep = $this->model_proyecto->dep_dist($this->dist);
        $data['unidades']=$this->lista_uo($ddep[0]['dep_id'],1);
        $this->load->view('admin/programacion/unidad_organizacional/lista_unidad', $data);
      }
    }

 
    /*----- Lista de regionales -----*/
    public function regionales($tp){
      $tabla='';
      $regiones=$this->mrep_operaciones->regiones();
      $tabla.='<table class="table table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th style="width:1%;">#</th>
                    <th style="width:30%;">REGIONAL</th>
                    <th style="width:5%;"></th>
                    <th style="width:5%;"></th>
                  </tr>
                </thead>
              <tbody>';
      $nro=0;
      foreach($regiones as $row){
        if($row['dep_id']!=10){
          $nro++;
          $tabla.='<tr>';
            $tabla.='<td>'.$nro.'</td>';
            $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
            if($tp==1){
              $tabla.='<td align=center><a href="#" class="btn btn-info enlace" name="'.$row['dep_id'].'" id="'.strtoupper($row['dep_departamento']).'">Ver Establecimientos</a></td>';
              $tabla.='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/prog/rep_list_establecimientos/'.$row['dep_id'].'\');" title="REPORTE PDF ESTABLECIMIENTOS DE '.strtoupper($row['dep_departamento']).'" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="30" HEIGHT="25"/>&nbsp;&nbsp;PRINT</a></td>';
            }
            else{
              $tabla.='<td align=center><a href="#" class="btn btn-info enlace" name="'.$row['dep_id'].'" id="'.strtoupper($row['dep_departamento']).'">Ver Compra de Servicios</a></td>';
              $tabla.='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/prog/rep_list_cservicio/'.$row['dep_id'].'\');" title="REPORTE PDF COMPRA DE SERVICIOS DE '.strtoupper($row['dep_departamento']).'" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="30" HEIGHT="25"/>&nbsp;&nbsp;PRINT</a></td>';
            }

          $tabla.='</tr>';
        }
      }
      $tabla.='</tbody>
        </table>';
      return $tabla;
    }

    /*-------- GET LISTA DE UNIDADES ------------*/
    public function get_unidades(){
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

    /*------- LISTA UNIDADES ORGANIZACIONALES -------*/
    public function lista_uo($dep_id,$tp){
      $tabla='';
      $unidades=$this->model_estructura_org->get_unidades_regionales($dep_id); /// Lista de Establecimientos de salud
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $head='';$foot='';
      $tab='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
      if($tp==1){
        $head='<script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
              <div class="jarviswidget jarviswidget-color-darken">
              <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                <h2 class="font-md">REGIONAL - '.strtoupper($dep[0]['dep_departamento']).'</strong></h2>  
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
                    <th style="width:1%; text-align: center;">#</th>
                    <th style="width:5%; text-align: center;"></th>
                    <th style="width:5%; text-align: center;"></th>
                    <th style="width:2%; text-align: center;">C&Oacute;DIGO</th>
                    <th style="width:20%; text-align: center;">ESTABLECIMIENTO</th>
                    <th style="width:10%; text-align: center;">DISTRITAL</th>
                    <th style="width:8.5%; text-align: center;">TIPO DE UBICACI&Oacute;N</th>
                    <th style="width:8.5%; text-align: center;">TIPO DE ESTABLECIMIENTO</th>
                    <th style="width:8.5%; text-align: center;">DIRECCI&Oacute;N</th>
                    <th style="width:8.5%; text-align: center;">ESTADO ACTUAL</th>
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach ($unidades as $row){
                  $nro++;
                  $tabla.='<tr class="modo1">';
                    $tabla.='
                            <td style="width: 1%; text-align: left;" style="height:11px;">'.$nro.'</td>
                            <td style="width: 5%; text-align: left;"><a href="'.site_url("").'/prog/datos_unidad/'.$row['act_id'].'" title="MODIFICAR DATOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="25"/></a></td>
                            <td style="width: 5%; text-align: left;"><a href="javascript:abreVentana(\''.site_url("").'/prog/rep_datos_unidad/'.$row['act_id'].'\');" title="REPORTE UNIDAD"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>
                            <td style="width: 2%; text-align: left;">'.$row['act_cod'].'</td>
                            <td style="width: 20%; text-align: left;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>
                            <td style="width: 10%; text-align: left;">'.$row['dist_distrital'].'</td>
                            <td style="width: 8%; text-align: left;">'.$row['ubicacion'].'</td>
                            <td style="width: 8.5%; text-align: left;">'.$row['establecimiento'].'</td>
                            <td style="width: 8.5%; text-align: left;">'.$row['direccion'].'</td>
                            <td style="width: 8.5%; text-align: left;">'.$row['descripcion'].'</td>
                          </tr>';
                }                                                     
        $tabla.='</tbody>
                </table>
        '.$foot.'';
      return $tabla;
    }


    /*----- Reporte Datos Unidad -----*/
    public function reporte_datos_unidad($uni_id){
      $data['unidad']= $this->model_estructura_org->get_actividad($uni_id);
      $data['mes'] = $this->mes_nombre();
      if(count($data['unidad'])!=0){
        $data['datos']=$this->datos($uni_id);
        $this->load->view('admin/programacion/unidad_organizacional/reporte_unidad', $data);      
      }
      else{
        echo "Error !!";
      }
    }

    /*----- Reporte Lista de Unidades, Establecimientos -----*/
    public function rep_list_establecimientos($dep_id){
      $tabla='';
      $data['dep']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      if(count($data['dep'])!=0){
        
        $data['lista']=$this->lista_establecimiento_reporte($dep_id);
        $this->load->view('admin/programacion/unidad_organizacional/reporte_lista_unidades', $data); 
      }
      else{
        echo "ERROR !!!";
      }
    }

    /*----- Reporte Lista consolidado de Unidades, Establecimientos (2020 - PDF)-----*/
    public function rep_consolidado_establecimientos(){
      $data['mes'] = $this->mes_nombre();
      $data['lista']=$this->lista_establecimiento_reporte_consolidado(1); // PDF
      $this->load->view('admin/programacion/unidad_organizacional/reporte_lista_consolidado_unidades', $data);
    }

        /*----- Reporte Lista consolidado de Unidades, Establecimientos (2020 - EXCEL)-----*/
    public function rep_consolidado_establecimientos_xls(){
      date_default_timezone_set('America/Lima');
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");
      $establecimientos=$this->lista_establecimiento_reporte_consolidado(2); // EXCEL
      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Consolidado_Establecimientos_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo "".$establecimientos."";
    }


    /*----- LISTA DE ESTABLECIMIENTOS (CONSOLIDADO)PARA EL REPORTE ------*/
    public function lista_establecimiento_reporte_consolidado($tp){
      $tabla='';
      $unidades=$this->model_estructura_org->get_unidades_regionales_consolidado(); /// Lista de Establecimientos de salud

      if($tp==1){ // (PDF)
        $tabla.=
        '<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
          <thead>
            <tr style="font-size: 8px;" bgcolor="#d8d8d8">
              <th style="width:2%; text-align: center;height:20px;">#</th>
              <th style="width:15%; text-align: center;">D.A.</th>
              <th style="width:20%; text-align: center;">U.E.</th>
              <th style="width:10%; text-align: center;">NIVEL</th>
              <th style="width:30%; text-align: center;">ESTABLECIMIENTO DE SALUD</th>
              <th style="width:10%; text-align: center;">PPTO. '.$this->gestion.'</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
            foreach ($unidades as $row){
            $ppto=$this->genera_informacion->ppto_actividad($row,4);
            $nro++;
            $tabla.=
            '<tr style="font-size: 7px;">
              <td style="width:2%;height:13px;" align=center>'.$nro.'</td>
              <td style="width:15%;">'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>
              <td style="width:20%;">'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>
              <td style="width:10%;">'.$row['nivel'].'</td>
              <td style="width:30%;font-size: 6pt;"><b>'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</b></td>
              <td style="width:10%;font-size: 6pt;" align=right><b>'.number_format($ppto[1], 2, ',', '.').'</b></td>
            </tr>';
          }
          $tabla.='
          </tbody>
        </table>';
      }
      else{ // (EXCEL)
        
         $tabla.=
          '<table border="1" cellpadding="0" cellspacing="0" class="tabla">
            <thead>
              <tr style="font-size: 9px;" bgcolor="#d8d8d8" class="modo1">
                <th style="width:2%; text-align: center;height:25px;">#</th>
                <th style="width:15%; text-align: center;">D.A.</th>
                <th style="width:20%; text-align: center;">U.E.</th>
                <th style="width:10%; text-align: center;">NIVEL</th>
                <th style="width:30%; text-align: center;">ESTABLECIMIENTO DE SALUD</th>
                <th style="width:10%; text-align: center;">PPTO. '.$this->gestion.'</th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
              foreach ($unidades as $row){
              $ppto=$this->genera_informacion->ppto_actividad($row,4);
              $nro++;
              $tabla.=
              '<tr style="font-size: 9px;" class="modo1">
                <td style="width:2%;height:22px;">'.$nro.'</td>
                <td style="width:15%;">'.$row['dep_cod'].' '.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
                <td style="width:20%;">'.$row['dist_cod'].' '.mb_convert_encoding(strtoupper($row['dist_distrital']), 'cp1252', 'UTF-8').'</td>
                <td style="width:10%;">'.$row['nivel'].'</td>
                <td style="width:30%;font-size: 10pt;"><b>'.$row['tipo'].' '.mb_convert_encoding($row['act_descripcion'], 'cp1252', 'UTF-8').' - '.$row['abrev'].'</b></td>
                <td style="width:10%;" align=right>'.round($ppto[1],2).'</td>
              </tr>';
            }
            $tabla.='
            </tbody>
          </table>';
      }

      return $tabla;
    }


    /*----- LISTA DE ESTABLECIMIENTOS PARA EL REPORTE ------*/
    public function lista_establecimiento_reporte($dep_id){
      $tabla='';
      $unidades=$this->model_estructura_org->get_unidades_regionales($dep_id); /// Lista de Establecimientos de salud
      $tabla.=
      '<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
        <thead>
          <tr style="font-size: 8px;" bgcolor="#d8d8d8">
            <th style="width:2%; text-align: center;height:20px;">#</th>
            <th style="width:5%; text-align: center;">COD.</th>
            <th style="width:25%; text-align: center;">ESTABLECIMIENTO</th>
            <th style="width:15%; text-align: center;">DISTRITAL</th>
            <th style="width:15%; text-align: center;">TIPO DE UBICACI&Oacute;N</th>
            <th style="width:15%; text-align: center;">TIPO DE ESTABLECIMIENTO</th>
            <th style="width:10%; text-align: center;">USUARIO</th>';
            if($this->tp_adm==1){
              $tabla.='<th style="width:10%; text-align: center;">PASSWORD</th>';
            }
            $tabla.='
          </tr>
        </thead>
        <tbody>';
        $nro=0;
          foreach ($unidades as $row){
          $nro++;
          $tabla.=
          '<tr style="font-size: 7px;">
            <td style="width:2%;height:15px;">'.$nro.'</td>
            <td style="width:5%;height:10px;">'.$row['act_cod'].'</td>
            <td style="width:25%;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>
            <td style="width:15%;">'.strtoupper($row['dist_distrital']).'</td>
            <td style="width:15%;">'.$row['ubicacion'].'</td>
            <td style="width:15%;">'.$row['establecimiento'].'</td>
            <td style="width:10%;">'.$row['dato_ingreso'].'</td>';
            if($this->tp_adm==1){
              $tabla.='<td style="width:10%;">'.$row['clave'].'</td>';
            }
            $tabla.='
          </tr>';
        }
        $tabla.='
        </tbody>
      </table>';

      return $tabla;
    }


    /*------ Formulario - Unidades, centros de salud -----*/
    public function formulario($uni_id){
      $data['unidad']= $this->model_estructura_org->get_actividad($uni_id);
      if(count($data['unidad'])!=0){
        $data['menu']=$this->menu();
        $data['res_dep']=$this->tp_resp();
        /*----------------------------------------------------*/
        $data['identificacion']=$this->identificacion($uni_id);
        /*----------------------------------------------------*/
        $data['datos_demograficos']=$this->datos_demograficos($uni_id);
        /*----------------------------------------------------*/
        $data['estado']=$this->model_estructura_org->estado_unidad(); //// Recursos Economicos
        /*----------------------------------------------------*/
        $data['referencia_pacientes']=$this->referencia_pacientes($uni_id);
        /*----------------------------------------------------*/
        $data['servicios']=$this->servicios($uni_id);
        /*----------------------------------------------------*/
        $data['galeria']=$this->galeria($uni_id);
        /*----------------------------------------------------*/
        $data['clave_seguimiento']=$this->clave_seguimiento($uni_id);

        $this->load->view('admin/programacion/unidad_organizacional/formulario', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    
    /*----------- CLAVE SEGUIMIENTO POA ----------*/
    public function clave_seguimiento($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $nivel=$this->model_estructura_org->tipo_nivel();
      $tabla='';

      if($unidad[0]['ta_id']==2){
          $tabla.='
          <article class="col-sm-1">
            </article>
            <article class="col-sm-10">';
              if($this->session->flashdata('success')){ 
                $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
              }
              elseif($this->session->flashdata('danger')){ 
                $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
              }

            $tabla.='
              <div class="well">
                <form action="'.site_url("").'/programacion/cunidad_organizacional/modificar_datos'.'" id="form7" name="form7" class="smart-form" method="post">
                    <input type="hidden" name="uni_id" id="uni_id" value="'.$uni_id.'">
                    <input type="hidden" name="tp" id="tp" value="7">
                    <header><b>CLAVE INGRESO A SEGUIMIENTO POA</b></header>
                    <fieldset>
                    <h2>'.$unidad[0]['tipo'].' '.$unidad[0]['act_descripcion'].' '.$unidad[0]['abrev'].'</h2><br>
                      <div class="row">
                        <section class="col col-3">
                          <label class="label">USUARIO DE ESTABLECIMIENTO</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>';
                              if($unidad[0]['dato_ingreso']!=''){
                                $tabla.='<input type="text" name="usuario" id="usuario" title="usuario Esteblecimiento" value="'.$unidad[0]['dato_ingreso'].'">';
                              }
                              else{
                                $tabla.='<input type="text" name="usuario" id="usuario" title="usuario Esteblecimiento" value="'.$unidad[0]['tipo'].'.'.$unidad[0]['act_cod'].'">';
                              }
                            $tabla.='
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">CLAVE DE INGRESO</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>';
                            if($unidad[0]['clave']!=''){
                                $tabla.='<input type="text" name="clave" id="clave" title="usuario Esteblecimiento" value="'.$unidad[0]['clave'].'">';
                              }
                            else{
                                $tabla.='<input type="text" name="clave" id="clave" title="usuario Esteblecimiento" value="'.$unidad[0]['abrev'].''.$unidad[0]['dist_cod'].'">';
                            }

                            $tabla.='
                          </label>
                        </section>
                      </div>
                    </fieldset>
                    <footer>';
                    if($unidad[0]['te_id']!=0){
                      $tabla.=' <button type="button" name="subir_form7" id="subir_form7" class="btn btn-info">GUARDAR DATOS</button>
                                <a href="'.base_url().'index.php/prog/unidad" title="SALIR" class="btn btn-default">CANCELAR</a>';
                    }
                    $tabla.='
                    </footer>
                </form>
              </div>
            </article>';
      }

      return $tabla;
    }


    /*----------- LISTA DE SERVICIOS ----------*/
    public function servicios($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $tabla='';
      if($unidad[0]['te_id']!=0){
        $servicios=$this->model_estructura_org->list_establecimiento_servicio($unidad[0]['te_id']);
      
          $tabla.='
            <article class="col-sm-3">
            </article>
            <article class="col-sm-6">
              <div class="well">';
                $tabla.='
                  <table class="table table-bordered"align="center">
                    <thead>
                     <tr class="modo1">
                        <th style="background-color: #1c7368; color: #FFFFFF" style="height:12px;width:5%;">#</th>
                        <th style="background-color: #1c7368; color: #FFFFFF; width:10%;">C&Oacute;DIGO</th>
                        <th style="background-color: #1c7368; color: #FFFFFF; width:85%;">SERVICIO / SUB ACTIVIDAD</th>  
                    </tr>
                    </thead>
                    <tbody>';
                    if(count($servicios)!=0){
                      $nro=0;
                      foreach ($servicios as $rows){
                        $nro++;
                        $tabla.='
                          <tr class="modo1">
                            <td style="width: 5%; text-align: left;" style="height:12px;">'.$nro.'</td>
                            <td style="width: 10%; text-align: left;" style="height:12px;">'.$rows['serv_cod'].'</td>
                            <td style="width: 85%; text-align: left;" style="height:12px;">'.$rows['serv_descripcion'].'</td>
                          </tr>';
                      }
                    }
                    else{
                      $tabla.='<tr class="modo1"><td colspan=3 style="width:100%;height:12px;">Sin Servicios</td></tr>';
                    }
                  $tabla.='
                    </tbody>
                  </table><br>';
                $tabla.='
              </div>
            </article>';
      }
      else{
        $tabla.='<div class="alert alert-danger" role="alert">
                  Registre tipo de Establecimiento
                </div>';
      }
      

      return $tabla;
    }

    /*----------- REFERENCIA DE PACIENTES ----------*/
    public function referencia_pacientes($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $nivel=$this->model_estructura_org->tipo_nivel();
      $tabla='';
      $tabla.='
            <article class="col-sm-1">
            </article>
            <article class="col-sm-10">';
              if($this->session->flashdata('success')){ 
                $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
              }
              elseif($this->session->flashdata('danger')){ 
                $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
              }
            $tabla.='
              <div class="well">
                <form action="'.site_url("").'/programacion/cunidad_organizacional/modificar_datos'.'" id="form4" name="form4" class="smart-form" method="post">
                    <input type="hidden" name="uni_id" id="uni_id" value="'.$uni_id.'">
                    <input type="hidden" name="tp" id="tp" value="4">
                    <header><b>REFERENCIA DE PACIENTES</b></header>
                    <fieldset>

                    <h2>'.$unidad[0]['act_descripcion'].'</h2><br>
                      <div class="row">
                      
                        <section class="col col-3">
                          <label class="label">NIVEL DE ATENCI&Oacute;N</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" title="NIVEL DE ATENCIÓN" value="'.$unidad[0]['escalon'].'" disabled>
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">DISTANCIA EN KILOMETROS</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="distancia" id="distancia" title="distancia" value="'.$unidad[0]['distancia'].'">
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">TIEMPO EN HORAS</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="tiempo_horas" id="tiempo_horas" title="TIEMPO EN HORAS" value="'.$unidad[0]['tiempo_horas'].'">
                          </label>
                        </section>
                        <section class="col col-3">
                          <label class="label">MEDIO DE TRANSPORTE</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="medio_transporte" id="medio_transporte" title="MEDIO DE TRANSPORTE" value="'.$unidad[0]['medio_transporte'].'">
                          </label>
                        </section>
                      </div>
                    </fieldset>
                    <footer>';
                    if($unidad[0]['te_id']!=0){
                      $tabla.=' <button type="button" name="subir_form4" id="subir_form4" class="btn btn-info">GUARDAR DATOS</button>
                                <a href="'.base_url().'index.php/prog/unidad" title="SALIR" class="btn btn-default">CANCELAR</a>';
                    }
                    $tabla.='
                    </footer>
                </form>
              </div>
            </article>';
      

      return $tabla;
    }


    /*----------- DATOS DEMOGRAFICOS ----------*/
    public function datos_demograficos($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $tabla='';
      $tabla.='
            <article class="col-sm-1">      
            </article>
            <article class="col-sm-10">';
              if($this->session->flashdata('success')){ 
                $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
              }
              elseif($this->session->flashdata('danger')){ 
                $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
              }
            $tabla.='
            <div class="well">
              <form action="'.site_url("").'/programacion/cunidad_organizacional/modificar_datos'.'" id="form2" name="form2" class="smart-form" method="post">
                  <input type="hidden" name="uni_id" id="uni_id" value="'.$uni_id.'">
                  <input type="hidden" name="tp" id="tp" value="2">
                  
                  <header><b>DATOS DEMOGRAFICOS</b></header>
                  <fieldset>          
                    <div class="row">
                      <table class="table table-bordered" style="width: 100%;">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 1%;">#</th>
                            <th scope="col" style="width: 79%;"></th>
                            <th scope="col" style="width: 20%;"><b>POBLACI&Oacute;N ASEGURADA</b><br>(Vigencia de derechos)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Poblaci&oacute;n total asignada al establecimiento (I Nivel)</td>
                            <td><input class="form-control" type="text" name="ptotal_asig_est" id="ptotal_asig_est" title="" value='.$unidad[0]['ptotal_asig_est'].' onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>N&uacute;mero de familias asignadas al establecimiento (I Nivel)</td>
                            <td><input class="form-control" type="text" name="num_fam_asig_est" id="num_fam_asig_est" title="" value='.$unidad[0]['num_fam_asig_est'].' onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td>Poblaci&oacute;n asignada a la Red (Para II y III Nivel)</td>
                            <td><input class="form-control" type="text" name="pob_asig_red" id="pob_asig_red" title="" value='.$unidad[0]['pob_asig_red'].' onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                          </tr>
                        </tbody>
                      </table><br>
                    </div>
                  </fieldset>
                  <header><b>MORBILIDAD CONSULTA EXTERNA</b></header>
                  <fieldset>          
                    <div class="row">
                      <table class="table table-bordered" style="width: 100%;">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 1%;">Nro.</th>
                            <th scope="col" style="width: 9%;">CIE-10</th>
                            <th scope="col" style="width: 60%;">DIAGNOSTICO</th>
                            <th scope="col" style="width: 20%;">FRECUENCIA</th>
                          </tr>
                        </thead>
                        <tbody>';
                          for ($i=1; $i <=10 ; $i++) {
                            $dato=$this->model_estructura_org->get_morbilidad_consulta_externa($uni_id,$i);
                            if(count($dato)!=0){
                              $tabla.='
                              <tr>
                                <td scope="row"><input type="hidden" name="num_mce'.$i.'" value="'.$i.'">'.$i.'</td>
                                <td><input class="form-control" type="text" name="cie_mce'.$i.'" title="CIE-10" value="'.$dato[0]['cie'].'"></td>
                                <td><input class="form-control" type="text" name="diag_mce'.$i.'" title="DIAGNOSTICO" value="'.$dato[0]['diagnostico'].'"></td>
                                <td><input class="form-control" type="text" name="fre_mce'.$i.'" title="FRECUENCIA" value="'.$dato[0]['frecuencia'].'" onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                              </tr>';
                            }
                            else{
                              $tabla.='
                              <tr>
                                <td scope="row"><input type="hidden" name="num_mce'.$i.'" value="'.$i.'">'.$i.'</td>
                                <td><input class="form-control" type="text" name="cie_mce'.$i.'" title="CIE-10" value="0"></td>
                                <td><input class="form-control" type="text" name="diag_mce'.$i.'" title="DIAGNOSTICO" value=""></td>
                                <td><input class="form-control" type="text" name="fre_mce'.$i.'" title="FRECUENCIA" value="0" onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                              </tr>';
                            }
                          }
                          $tabla.='
                        </tbody>
                      </table><br>
                    </div>
                  </fieldset>
                  <header><b>MORBILIDAD URGENCIAS/EMERGENCIAS</b></header>
                  <fieldset>          
                    <div class="row">
                      <table class="table table-bordered" style="width: 100%;">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 1%;">Nro.</th>
                            <th scope="col" style="width: 9%;">CIE-10</th>
                            <th scope="col" style="width: 60%;">DIAGNOSTICO</th>
                            <th scope="col" style="width: 20%;">FRECUENCIA</th>
                          </tr>
                        </thead>
                        <tbody>';
                          for ($i=1; $i <=10 ; $i++) {
                            $dato=$this->model_estructura_org->get_morbilidad_urgencias_emergencias($uni_id,$i);
                            if(count($dato)!=0){
                              $tabla.='
                              <tr>
                                <td scope="row"><input type="hidden" name="num_mce'.$i.'" value="'.$i.'">'.$i.'</td>
                                <td><input class="form-control" type="text" name="cie_mue'.$i.'" title="CIE-10" value="'.$dato[0]['cie'].'"></td>
                                <td><input class="form-control" type="text" name="diag_mue'.$i.'" title="DIAGNOSTICO" value="'.$dato[0]['diagnostico'].'"></td>
                                <td><input class="form-control" type="text" name="fre_mue'.$i.'" title="FRECUENCIA" value="'.$dato[0]['frecuencia'].'" onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                              </tr>';
                            }
                            else{
                              $tabla.='
                              <tr>
                                <td scope="row"><input type="hidden" name="num_mce'.$i.'" value="'.$i.'">'.$i.'</td>
                                <td><input class="form-control" type="text" name="cie_mue'.$i.'" title="CIE-10" value="0"></td>
                                <td><input class="form-control" type="text" name="diag_mue'.$i.'" title="DIAGNOSTICO" value=""></td>
                                <td><input class="form-control" type="text" name="fre_mue'.$i.'" title="FRECUENCIA" value="0" onkeypress="if (this.value.length < 20) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                              </tr>';
                            }
                          }
                          $tabla.='
                        </tbody>
                      </table><br>
                    </div>
                  </fieldset>
                  <div>
                    <footer>';
                    if($unidad[0]['te_id']!=0){
                      $tabla.=' <button type="button" name="subir_form2" id="subir_form2" class="btn btn-info">GUARDAR DATOS</button>
                                <a href="'.base_url().'index.php/prog/unidad" title="SALIR" class="btn btn-default">CANCELAR</a>';
                    }
                    $tabla.='
                    </footer>
                  </div>
              </form>
            </div>
            </article>';


      return $tabla;
    }

    /*------------ IDENTIFICACION ----------*/
    public function identificacion($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $tp_ubicacion= $this->model_estructura_org->list_tp_ubicacion();
      $tp_establecimiento=$this->model_estructura_org->list_tp_establecimiento();
      $list_provincia=$this->model_estructura_org->list_provincia($unidad[0]['dep_id']);
      
      $tabla='';
      $tabla.='
            <article class="col-sm-1">
            </article>
            <article class="col-sm-10">';
              if($this->session->flashdata('success')){ 
                $tabla.='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
              }
              elseif($this->session->flashdata('danger')){ 
                $tabla.='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
              }
            $tabla.='
            <div class="well">
              <form action="'.site_url("").'/programacion/cunidad_organizacional/modificar_datos'.'" id="form1" name="form1" class="smart-form" method="post">
                  <input type="hidden" name="uni_id" id="uni_id" value="'.$uni_id.'">
                  <input type="hidden" name="tp" id="tp" value="1">
                  <input type="hidden" name="unidad" id="unidad" value="'.$unidad[0]['act_descripcion'].'">
                  <input type="hidden" name="tp_est" id="tp_est" value="'.$unidad[0]['te_id'].'">
                  <header><b>DECRIPCI&Oacute;N</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-1">
                        <label class="label">GESTI&Oacute;N</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="cod" id="cod" title="GESTI&Oacute;N" value='.$this->gestion.' disabled>
                        </label>
                      </section>
                      <section class="col col-1">
                        <label class="label">C&Oacute;DIGO</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="cod" id="cod" title="CÓDIGO" value='.$unidad[0]['act_cod'].' disabled>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">UNIDAD / ESTABLECIMIENTO DE SALUD</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" title="UNIDAD / ESTABLECIMIENTO" disabled>'.$unidad[0]['act_descripcion'].'</textarea>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">TIPO DE UBICACI&Oacute;N</label>
                        <select class="form-control" id="tp_ubi" name="tp_ubi" title="SELECCIONE TIPO DE UBICACIÓN">';
                          foreach($tp_ubicacion as $row){
                            if($row['tu_id']==$unidad[0]['tu_id']){
                              $tabla.='<option value="'.$row['tu_id'].'" selected>'.$row['ubicacion'].'</option>';
                            }
                            else{
                              $tabla.='<option value="'.$row['tu_id'].'">'.$row['ubicacion'].'</option>';
                            }
                          }        
                        $tabla.='
                        </select>
                      </section>
                      <section class="col col-4">
                        <label class="label">TIPO DE ESTABLECIMIENTO</label>
                        <select class="form-control" id="tp_est" name="tp_est" title="SELECCIONE TIPO DE ESTABLECIMIENTO" disabled>';
                          foreach($tp_establecimiento as $row){
                            if($row['ta_id']==2){
                              if($row['te_id']==$unidad[0]['te_id']){
                                $tabla.='<option value="'.$row['te_id'].'" selected>'.$row['escalon'].'.-'.$row['establecimiento'].' ('.$row['tipo'].')</option>';
                              }
                              else{
                                $tabla.='<option value="'.$row['te_id'].'">'.$row['escalon'].'.-'.$row['establecimiento'].' ('.$row['tipo'].')</option>';
                              }
                            }
                          }        
                        $tabla.='
                        </select>
                      </section>
                    </div>
                  </fieldset>

                  <header><b>UBICACI&Oacute;N</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">REGIONAL</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" title="GESTI&Oacute;N" value="'.$unidad[0]['dep_departamento'].'" disabled>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">PROVINCIA</label>
                        <select class="form-control" id="prov_id" name="prov_id" title="SELECCIONE PROVINCIA">';
                          foreach($list_provincia as $row){
                            if($row['prov_id']==$unidad[0]['prov_id']){
                              $tabla.='<option value="'.$row['prov_id'].'" selected>'.$row['prov_provincia'].'</option>';
                            }
                            else{
                              $tabla.='<option value="'.$row['prov_id'].'">'.$row['prov_provincia'].'</option>';
                            }
                          }        
                        $tabla.='
                        </select>
                      </section>
                      <section class="col col-3">
                        <label class="label">MUNICIPIO</label>
                        <select class="form-control" id="muni_id" name="muni_id" title="SELECCIONE MUNICIPIO">';
                        if($unidad[0]['prov_id']!=''){
                          $list_municipio=$this->model_estructura_org->list_municipios($unidad[0]['prov_id']);
                          foreach($list_municipio as $row){
                            if($row['muni_id']==$unidad[0]['mun_id']){
                              $tabla.='<option value="'.$row['muni_id'].'" selected>'.$row['muni_municipio'].'</option>';
                            }
                            else{
                              $tabla.='<option value="'.$row['muni_id'].'">'.$row['muni_municipio'].'</option>';
                            }
                          }
                        }    
                      $tabla.='
                      </select> 
                      </section>
                      <section class="col col-3">
                        <label class="label">COMUNIDAD</label>
                        <select class="form-control" id="comu_id" name="comu_id" title="SELECCIONE COMUNIDAD">';
                        if($unidad[0]['mun_id']!=''){
                          $list_comunidad=$this->model_estructura_org->list_comunidad($unidad[0]['mun_id']);
                          foreach($list_comunidad as $row){
                            if($row['can_id']==$unidad[0]['can_id']){
                              $tabla.='<option value="'.$row['can_id'].'" selected>'.$row['can_canton'].'</option>';
                            }
                            else{
                              $tabla.='<option value="'.$row['can_id'].'">'.$row['can_canton'].'</option>';
                            }
                          }
                        }
                      $tabla.='
                        </select>
                      </section>
                      <section class="col col-2">
                        <label class="label">NUEVA COMUNIDAD</label>
                        <input class="form-control" type="text" name="comunidad" id="comunidad" title="COMUNIDAD" value="" disabled>
                      </section>
                    </div>
                  </fieldset>

                  <fieldset>
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">DIRECCI&Oacute;N</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="direccion" id="direccion" title="DIRECCI&Oacute;N" value='.$unidad[0]['direccion'].'>
                          </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">TELEFONO</label>
                          <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="number" name="fono" id="fono" title="TELEFONO" value='.$unidad[0]['fono'].'>
                          </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">EMAIL</label>
                         <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="email" name="email" id="email" title="EMAIL" value='.$unidad[0]['email'].'>
                          </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">FAX</label>
                         <label class="input">
                            <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="fax" id="fax" title="FAX" value='.$unidad[0]['fax'].'>
                          </label>
                      </section>
                    </div>
                  </fieldset>


                  <div id="but">
                    <footer>
                      <button type="button" name="subir_form1" id="subir_form1" class="btn btn-info">GUARDAR DATOS</button>
                      <a href="'.base_url().'index.php/prog/unidad" title="SALIR" class="btn btn-default">CANCELAR</a>
                    </footer>
                  </div>
              </form>
              </div>
            </article>';

      return $tabla;
    }

    /*---------- VALIDA UPDATE DATOS ---------*/
     public function modificar_datos(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// Tipo de Fomulario
        $uni_id = $this->security->xss_clean($post['uni_id']); /// unidad id

        if($tp==1){
          $unidad = $this->security->xss_clean($post['unidad']); /// unidad
          $tp_ubi = $this->security->xss_clean($post['tp_ubi']); /// tipo de ubicacion 
          $tp_est = $this->security->xss_clean($post['tp_est']); /// tipo de establecimiento
          $prov_id = $this->security->xss_clean($post['prov_id']); /// provincia
          $muni_id = $this->security->xss_clean($post['muni_id']); /// Municipio
          $direccion = $this->security->xss_clean($post['direccion']); /// direccion
          $comu_id = $this->security->xss_clean($post['comu_id']); /// comunidad
          
          $fono = $this->security->xss_clean($post['fono']); /// comunidad
          $email = $this->security->xss_clean($post['email']); /// comunidad
          $fax = $this->security->xss_clean($post['fax']); /// comunidad

          if($comu_id==0){
            $nombre_comunidad = $this->security->xss_clean($post['comunidad']); /// nombre comunidad
            $data_to_store = array(
              'muni_id' => $muni_id, /// muni_id
              'can_canton' => $nombre_comunidad, /// comunidad
              'fun_id' => $this->fun_id, /// fun id
            );
            $this->db->insert('_cantones', $data_to_store);
            $comu_id=$this->db->insert_id();
          }

          $update_dato= array(
            'act_descripcion' => $unidad,
            'fun_id' => $this->fun_id,
            'tu_id' => $tp_ubi,
            'te_id' => $tp_est,
            'prov_id' => $prov_id,
            'mun_id' => $muni_id,
            'can_id' => $comu_id,
            'direccion' => $direccion,
            'fono' => $fono,
            'fax' => $fax,
            'email' => $email
          );
          $this->db->where('act_id', $uni_id);
          $this->db->update('unidad_actividad', $update_dato);

          $this->session->set_flashdata('success','LOS DATOS SE GUARDARON CORRECTAMENTE....');
          redirect(site_url("").'/prog/datos_unidad/'.$uni_id.'#tabs-a');
        }
        elseif ($tp==2) {
          $ptotal_asig_est = $this->security->xss_clean($post['ptotal_asig_est']); /// ptotal_asig_est
          $num_fam_asig_est = $this->security->xss_clean($post['num_fam_asig_est']); /// num_fam_asig_est
          $pob_asig_red = $this->security->xss_clean($post['pob_asig_red']); /// pob_asig_red

          $update_dato= array(
            'ptotal_asig_est' => $ptotal_asig_est,
            'num_fam_asig_est' => $num_fam_asig_est,
            'pob_asig_red' => $pob_asig_red,
            'fun_id' => $this->fun_id,
          );
          $this->db->where('act_id', $uni_id);
          $this->db->update('unidad_actividad', $update_dato);


          $this->db->where('act_id', $uni_id);
          $this->db->delete('morbilidad_consulta_externa');

          for ($i=1; $i <=10 ; $i++) { 
              $data_to_store1 = array( ///// Tabla ptto_fase_gestion
                'act_id' => $uni_id,
                'cie' => $this->security->xss_clean($post['cie_mce'.$i]),
                'diagnostico' => strtoupper($this->security->xss_clean($post['diag_mce'.$i])),
                'frecuencia' => $this->security->xss_clean($post['fre_mce'.$i]),
                'num' => $i,
              );
              $this->db->insert('morbilidad_consulta_externa', $data_to_store1);
          }

          $this->db->where('act_id', $uni_id);
          $this->db->delete('morbilidad_urgencias_emergencias');


          for ($i=1; $i <=10 ; $i++) { 
              $data_to_store2 = array( ///// Tabla ptto_fase_gestion
                'act_id' => $uni_id,
                'cie' => $this->security->xss_clean($post['cie_mue'.$i]),
                'diagnostico' => strtoupper($this->security->xss_clean($post['diag_mue'.$i])),
                'frecuencia' => $this->security->xss_clean($post['fre_mue'.$i]),
                'num' => $i,
              );
              $this->db->insert('morbilidad_urgencias_emergencias', $data_to_store2);
          }

          $this->session->set_flashdata('success','LOS DATOS SE GUARDARON CORRECTAMENTE....');
          redirect(site_url("").'/prog/datos_unidad/'.$uni_id.'#tabs-b');

        }
        elseif ($tp==3) {
          $f_creacion = $this->security->xss_clean($post['f_creacion']); /// f_creacion
          $f_mantenimiento = $this->security->xss_clean($post['f_mantenimiento']); /// f_mantenimiento
          $eu_id = $this->security->xss_clean($post['eu_id']); /// eu_id
          $tp_tcia = $this->security->xss_clean($post['tp_tcia']); /// tp_tcia

          $update_dato= array(
            'fecha_creacion' => $f_creacion,
            'fecha_mantenimiento' => $f_mantenimiento,
            'eu_id' => $eu_id,
            'tp_tcia' => $tp_tcia,
            'fun_id' => $this->fun_id,
          );
          $this->db->where('act_id', $uni_id);
          $this->db->update('unidad_actividad', $update_dato);

          $this->session->set_flashdata('success','LOS DATOS SE GUARDARON CORRECTAMENTE....');
          redirect(site_url("").'/prog/datos_unidad/'.$uni_id.'#tabs-c');
        }
        elseif ($tp==4) {
          $tn_id = $this->security->xss_clean($post['tn_id']); /// tn_id
          $distancia = $this->security->xss_clean($post['distancia']); /// distancia
          $tiempo_horas = $this->security->xss_clean($post['tiempo_horas']); /// tiempo_horas
          $medio_transporte = $this->security->xss_clean($post['medio_transporte']); /// medio_transporte

          $update_dato= array(
            'tn_id' => $tn_id,
            'distancia' => $distancia,
            'tiempo_horas' => $tiempo_horas,
            'medio_transporte' => $medio_transporte,
            'fun_id' => $this->fun_id,
          );
          $this->db->where('act_id', $uni_id);
          $this->db->update('unidad_actividad', $update_dato);

          $this->session->set_flashdata('success','LOS DATOS SE GUARDARON CORRECTAMENTE....');
          redirect(site_url("").'/prog/datos_unidad/'.$uni_id.'#tabs-d');
        }

        elseif ($tp==7) {
          $usuario = $this->security->xss_clean($post['usuario']); /// usuario
          $clave = $this->security->xss_clean($post['clave']); /// clave

          $update_dato= array(
            'dato_ingreso' => $usuario,
            'clave' => $clave,
          );
          $this->db->where('act_id', $uni_id);
          $this->db->update('unidad_actividad', $update_dato);

          $this->session->set_flashdata('success','LOS DATOS DE USUARIO SE GUARDARON CORRECTAMENTE....');
          redirect(site_url("").'/prog/datos_unidad/'.$uni_id.'#tabs-g');
        }
   
      } else {
          show_404();
      }
    }


    /*----------- LISTA DE IMAGENES ----------*/
    public function galeria($uni_id){
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $tabla='';
      $tabla.=' <article class="col-sm-4">
                  <form  action="'.site_url().'/programacion/cunidad_organizacional/subir_archivos" id="formulario" name="formulario" novalidate="novalidate" method="post" enctype="multipart/form-data" >
                    <input type="hidden" name="id" id="id" value="'.$unidad[0]['act_id'].'">
                    <div class="jarviswidget jarviswidget-color-darken" >
                      <header>
                        <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                        <h2 class="font-md"><strong></strong></h2>       
                      </header>
                        
                      <div class="panel-body">
                        <div class="col-sm-12">
                          <label class="control-label">Seleccione Documento/Archivo <font color="blue">(Obligatorio)</font></label>
                          <input id="file1" name="file1" type="file" class="file" accept="image/png, .jpeg, .jpg, image/gif" multiple data-show-upload="false" data-show-caption="true" title="SELECCIONE EL ARCHIVO, DOCUMENTO">
                        </div>
                            <div class="col-sm-12"><hr></div>
                            <div class="col-sm-12">
                          <input type="button" name="Submit" value="SUBIR ARCHIVO" id="btsubmit" class="btn btn-success btn-lg" onclick="comprueba_extension()" style="width:100%;">
                            </div>
                            <div class="col-sm-12"><hr></div> 
                      </div>
                        </div>  
                  </form>
                </article>
                <article class="col-sm-8">
                  <div class="well" align=center>';
                      if($unidad[0]['img']!=''){
                        $tabla.='<img src="'.base_url().'fotos/'.$unidad[0]['img'].'" class="img-responsive" style="width:65%; height:65%;"/>';
                      }
                      else{
                        $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:50%; height:50%;"/>';
                      }
                    $tabla.='
                    <div class="h-30"></div>
                  </div>
                </article>';

      return $tabla;
    }

    /*------ COMBO UBICACIÓN -------*/
      public function combo_ubicacion($accion = ''){
        $salida = "";
        $accion = $_POST["accion"];

        switch ($accion) {
            case 'prov':
                $salida = "";
                $prov_id = $_POST["elegido"];

                $combog = 
                  pg_query(
                    'select *
                     from _municipios
                     where prov_id='.$prov_id.' and muni_estado!=\'0\''
                  );
                $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE MUNICIPIO', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='".$sql_p[0]."'>".$sql_p[3]."</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'muni':
                $salida = "";
                $muni_id = $_POST["elegido"];

                $combog = 
                  pg_query(
                    'select *
                     from _cantones
                     where muni_id='.$muni_id.' and can_estado!=\'0\''
                  );
                $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE COMUNIDAD', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='".$sql_p[0]."'>".$sql_p[3]."</option>";
                }
                $salida .= "<option value='0'>REGISTRAR NUEVA COMUNIDAD</option>";
                echo $salida;
                //return $salida;
                break;

        }
        /**/
    }


    /*------ DATOS GENERALES - REPORTE -----*/
    public function datos($uni_id){
      $tabla='';
      $unidad= $this->model_estructura_org->get_actividad($uni_id);
      $list_provincia=$this->model_estructura_org->list_provincia($unidad[0]['dep_id']);
      if(count($unidad)!=0){
       $tabla.='
       <b>IDENTIFICACI&Oacute;N</b><br><br>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
        <tbody>
          <tr style="font-size: 10px;">
            <td style="width:35%; height:15px;" bgcolor="#e8e7e7"><b>GESTI&Oacute;N</b></td>
            <td style="width:45%;">'.$this->gestion.'</td>
            <td style="width:20%;" rowspan=5 align="center">';
              if($unidad[0]['img']!=''){
                $tabla.='<img src="'.base_url().'fotos/'.$unidad[0]['img'].'" class="img-responsive" style="width:150px; height:110px;"/>';
              }
              else{
                $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
              }
            $tabla.='
            </td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:35%; height:15px;" bgcolor="#e8e7e7"><b>Nro. C&Oacute;DIGO</b></td>
            <td style="width:45%;">'.$unidad[0]['act_cod'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:35%; height:15px;" bgcolor="#e8e7e7"><b>NOMBRE DE UNIDAD / ESTABLECIMIENTO DE SALUD</b></td>
            <td style="width:45%;">'.$unidad[0]['act_descripcion'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:35%; height:15px;" bgcolor="#e8e7e7"><b>TIPO DE UBICACI&Oacute;N</b></td>
            <td style="width:45%;">'.$unidad[0]['ubicacion'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:35%; height:15px;" bgcolor="#e8e7e7"><b>TIPO DE ESTABLECIMIENTO</b></td>
            <td style="width:45%;">'.$unidad[0]['establecimiento'].'</td>
          </tr>
        </tbody>
       </table><br>

       <b>UBICACI&Oacute;N</b><br><br>
       <table cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" border=0.2>
        <tbody>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>DISTRITAL</b></td>
            <td style="width:80%;">'.strtoupper($unidad[0]['dist_distrital']).'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>PROVINCIA</b></td>';
              if($unidad[0]['prov_id']!=''){
                $provincia=$this->model_proyecto->get_provincia($unidad[0]['prov_id']);
                $tabla.='<td style="width:80%;">'.strtoupper($provincia[0]['prov_provincia']).'</td>';
              }
              else{
               $tabla.='<td style="width:80%;">No seleccionado</td>'; 
              }
            $tabla.='
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>MUNICIPIO</b></td>';
              if($unidad[0]['mun_id']!=''){
                $municipio=$this->model_proyecto->get_municipios($unidad[0]['mun_id']);
                $tabla.='<td style="width:80%;">'.strtoupper($municipio[0]['muni_municipio']).'</td>';
              }
              else{
               $tabla.='<td style="width:80%;">No seleccionado</td>'; 
              }
            $tabla.='
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>COMUNIDAD</b></td>';
              if($unidad[0]['can_id']!=''){
                $comunidad=$this->model_proyecto->get_comunidad($unidad[0]['can_id']);
                $tabla.='<td style="width:80%;">'.strtoupper($comunidad[0]['can_canton']).'</td>';
              }
              else{
               $tabla.='<td style="width:80%;">No seleccionado</td>'; 
              }
            $tabla.='
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>DIRECCI&Oacute;N</b></td>
            <td style="width:80%;">'.$unidad[0]['direccion'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>TELEFONO</b></td>
            <td style="width:80%;">'.$unidad[0]['fono'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>EMAIL</b></td>
            <td style="width:80%;">'.$unidad[0]['email'].'</td>
          </tr>
        </tbody>
       </table><br>

       <b>DATOS DEMOGRAFICOS</b><br><br>
       <table cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" border=0.2>
        <tbody>
          <tr style="font-size: 10px;">
            <td style="width:60%; height:15px;" bgcolor="#e8e7e7"><b>Poblaci&oacute;n total asignada al establecimiento (I Nivel)</b></td>
            <td style="width:40%;">'.$unidad[0]['ptotal_asig_est'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:60%; height:15px;" bgcolor="#e8e7e7"><b>Numero de familias asignadas al establecimiento (I Nivel)</b></td>
            <td style="width:40%;">'.$unidad[0]['num_fam_asig_est'].'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:60%; height:15px;" bgcolor="#e8e7e7"><b>Poblaci&oacute;n asignada a la Red (Para II y III Nivel)</b></td>
            <td style="width:40%;">'.$unidad[0]['pob_asig_red'].'</td>
          </tr>
        </tbody>
       </table><br>

       <b>PERFIL EPIDEMIOLOGICO</b><br><br>
       MORBILIDAD CONSULTA EXTERNA
       <table cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" border=0.9>
        <thead>
          <tr bgcolor="#e8e7e7">
            <th style="width:5%; height:15px;" align=center>Nro.</th>
            <th style="width:15%; height:15px;" align=center>CIE-10</th>
            <th style="width:60%; height:15px;" align=center>DIAGNOSTICO</th>
            <th style="width:20%; height:15px;" align=center>FRECUENCIA</th>
          </tr>
        </thead>
        <tbody>';
        $m1=$this->model_estructura_org->list_morbilidad_consulta_externa($uni_id);
        foreach($m1 as $row){
          $tabla.='
            <tr style="font-size: 10px;">
              <td style="width:5%; height:15px;">'.$row['num'].'</td>
              <td style="width:5%; height:15px;">'.$row['cie'].'</td>
              <td style="width:5%; height:15px;">'.$row['diagnostico'].'</td>
              <td style="width:5%; height:15px;">'.$row['frecuencia'].'</td>
            </tr>';
        }

        $tabla.='
        </tbody>
       </table><br>

        MORBILIDAD URGENCIAS/EMERGENCIAS
       <table cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" border=0.2>
        <thead>
          <tr bgcolor="#e8e7e7">
            <th style="width:5%; height:15px;" align=center>Nro.</th>
            <th style="width:15%; height:15px;" align=center>CIE-10</th>
            <th style="width:60%; height:15px;" align=center>DIAGNOSTICO</th>
            <th style="width:20%; height:15px;" align=center>FRECUENCIA</th>
          </tr>
        </thead>
        <tbody>';
        $m1=$this->model_estructura_org->list_morbilidad_urgencias_emergencias($uni_id);
        foreach($m1 as $row){
          $tabla.='
            <tr style="font-size: 10px;">
              <td style="width:5%; height:15px;">'.$row['num'].'</td>
              <td style="width:5%; height:15px;">'.$row['cie'].'</td>
              <td style="width:5%; height:15px;">'.$row['diagnostico'].'</td>
              <td style="width:5%; height:15px;">'.$row['frecuencia'].'</td>
            </tr>';
        }

        $tabla.='
        </tbody>
       </table><br>

       <b>ANTECEDENTES DE INFRAESTRUCTURA</b><br><br>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
        <tbody>
          <tr style="font-size: 10px;">
            <td style="width:30%; height:15px;" bgcolor="#e8e7e7"><b>Fecha de creaci&oacute;n</b></td>
            <td style="width:20%;">'.date('d/m/Y',strtotime($unidad[0]['fecha_creacion'])).'</td>
            <td style="width:30%;" bgcolor="#e8e7e7"><b>Fecha de Ultimo Mantenimiento</b></td>
            <td style="width:20%;">'.date('d/m/Y',strtotime($unidad[0]['fecha_mantenimiento'])).'</td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:30%; height:15px;"><b>Estado Actual</b></td>
            <td style="width:20%;">'.$unidad[0]['descripcion'].'</td>
            <td style="width:30%;"><b>Tipo de Tenencia</b></td>
            <td style="width:20%;">'.$unidad[0]['tp_tcia'].'</td>
          </tr>
        </tbody>
       </table><br>

       <b>REFERENCIA DE PACIENTES</b><br><br>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
        <tbody>
          <tr style="font-size: 10px;">
            <td style="width:25%; height:15px;" bgcolor="#e8e7e7"><b>NIVEL DE ATENCI&Oacute;N</b></td>
            <td style="width:25%;" bgcolor="#e8e7e7"><b>DISTANCIA EN KILOMETROS</b></td>
            <td style="width:25%;" bgcolor="#e8e7e7"><b>TIEMPO EN HORAS</b></td>
            <td style="width:25%;" bgcolor="#e8e7e7"><b>MEDIO DE TRANSPORTE</b></td>
          </tr>
          <tr style="font-size: 10px;">
            <td style="width:25%; height:15px;">'.$unidad[0]['escalon'].'</td>
            <td style="width:25%;">'.$unidad[0]['distancia'].'</td>
            <td style="width:25%;">'.$unidad[0]['tiempo_horas'].'</td>
            <td style="width:25%;">'.$unidad[0]['medio_transporte'].'</td>
          </tr>
        </tbody>
       </table><br>';
        if($unidad[0]['te_id']!=0){
          $servicios=$this->model_estructura_org->list_establecimiento_servicio($unidad[0]['te_id']);
          $tabla.='
            <b>OFERTA DE SERVICIOS</b><br><br>
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
              <thead>
                <tr>
                  <th style="width:2%; height:13px;">#</th>
                  <th style="width:5%;" bgcolor="#e8e7e7">C&Oacute;DIGO</th>
                  <th style="width:25%; height:15px;" bgcolor="#e8e7e7">SERVICIO / SUB ACTIVIDAD</th>  
                </tr>
              </thead>
              <tbody>';
              if(count($servicios)!=0){
                $nro=0;
                foreach ($servicios as $rows){
                  $nro++;
                  $tabla.='
                    <tr class="modo1">
                      <td style="width: 2%; text-align: center;" style="height:15px;">'.$nro.'</td>
                      <td style="width: 5%; text-align: left;"">'.$rows['serv_cod'].'</td>
                      <td style="width: 25%; text-align: left;"">'.$rows['serv_descripcion'].'</td>
                    </tr>';
                }
              }
              else{
                $tabla.='<tr class="modo1"><td colspan=3 style="width:100%;height:12px;">Sin Servicios</td></tr>';
              }
            $tabla.='
              </tbody>
            </table>';
        }
      }

      return $tabla;
    }

    /*---- SUBIR ARCHIVOS UNIDAD,ESTABLECIMIENTO ----*/
    function subir_archivos(){ //echo $this->input->post('file1');
      if ($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('id', 'Id Actividad', 'required|trim');

          if ($this->form_validation->run()) {
            $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
            $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
            $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
            $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo

            $unidad= $this->model_estructura_org->get_actividad($this->input->post('id')); // Datos de la Unidad

            if($filename!='' & $filesize!=0){
              $newfilename = ''.$this->input->post('id').'-'.substr(md5(uniqid(rand())),0,5).$file_ext;
              /*--------------------------------------------------*/
              $update_dato= array(
                'img' => $newfilename,
                'fun_id' => $this->fun_id,
              );
              $this->db->where('act_id', $unidad[0]['act_id']);
              $this->db->update('unidad_actividad', $update_dato);
              /*--------------------------------------------------*/
              
              move_uploaded_file($_FILES["file1"]["tmp_name"],"fotos/" . $newfilename); // Guardando la foto

              $this->session->set_flashdata('success','SE GUARDO CORRECTAMENTE LA FOTO DEL ESTABLECIMIENTO');
              redirect(site_url("").'/prog/datos_unidad/'.$this->input->post('id').'#tabs-f');
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL GUARDAR ARCHIVO');
              redirect(site_url("").'/prog/datos_unidad/'.$this->input->post('id').'#tabs-f');
            }

          }
          else{
            $this->session->set_flashdata('danger','ERROR !!!! ');
            redirect(site_url("").'/prog/datos_unidad/'.$this->input->post('id').'#tabs-f');
          }
             
        }
    }

    /*============= COMPRA DE SERVICIO =============*/

    /*--- Lista de servicio de Compras ---*/
    public function list_scompra(){
      $data['menu']=$this->menu();
      $data['res_dep']=$this->tp_resp();

      if($this->adm==1){
        $data['regional']=$this->regionales(2);
        $this->load->view('admin/programacion/compra_servicio/regional', $data);
      }
      else{
        $ddep = $this->model_proyecto->dep_dist($this->dist);
        $data['unidades']=$this->lista_cs($ddep[0]['dep_id'],1);
        $this->load->view('admin/programacion/compra_servicio/lista_unidad', $data);
      }
    }

    /*-------- GET LISTA DE COMPRA DE SERVICIOS ------------*/
    public function get_cservicio(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
       // $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $tabla=$this->lista_cs($dep_id,1);
        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------- LISTA DE COMPRA DE SERVICIOS -------*/
    public function lista_cs($dep_id,$tp){
      $tabla='';
      $compra_serv=$this->model_estructura_org->list_compra_servicio($dep_id); /// Lista de Compra de Servicios
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $head='';$foot='';
      $tab='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
      if($tp==1){
        $head='<script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
              <div class="jarviswidget jarviswidget-color-darken">
              <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                <h2 class="font-md">REGIONAL - '.strtoupper($dep[0]['dep_departamento']).'</strong></h2>  
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
                    <th style="width:2%; text-align: center;">#</th>
                    <th style="width:20%; text-align: center;">ESTABLECIMIENTO</th>
                    <th style="width:10%; text-align: center;">DISTRITAL</th>
                    <th style="width:8.5%; text-align: center;">REGIONAL</th>
                    <th style="width:8.5%; text-align: center;">TIPO DE ESTABLECIMIENTO</th>
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach ($compra_serv as $row){
                  $nro++;
                  $tabla.='
                    <tr class="modo1">
                      <td style="width: 2%; text-align: left;" style="height:11px;">'.$nro.'</td>
                      <td style="width: 20%; text-align: left;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>
                      <td style="width: 10%; text-align: left;">'.strtoupper($row['dist_distrital']).'</td>
                      <td style="width: 8%; text-align: left;">'.strtoupper($row['dep_departamento']).'</td>
                      <td style="width: 8.5%; text-align: left;">'.$row['establecimiento'].'</td>
                    </tr>';
                }                                                     
        $tabla.='</tbody>
                </table>
        '.$foot.'';
      return $tabla;
    }

    /*----- Reporte Lista de Servicio de Compra -----*/
    public function rep_list_cservicio($dep_id){
      $tabla='';
      $data['dep']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      if(count($data['dep'])!=0){
        $data['lista']=$this->lista_cservicio_reporte($dep_id);
        $this->load->view('admin/programacion/compra_servicio/reporte_lista_cservicios', $data); 
      }
      else{
        echo "ERROR !!!";
      }
    }

    /*----- LISTA DE COMPRA DE SERVICIOS PARA EL REPORTE ------*/
    public function lista_cservicio_reporte($dep_id){
      $tabla='';
      $compra_serv=$this->model_estructura_org->list_compra_servicio($dep_id); /// Lista de Compra de Servicios
      $tabla.=
      '<table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:80%;" align=center>
        <thead>
          <tr style="font-size: 8px;" bgcolor="#d8d8d8">
            <th style="width:4%; text-align: center;height:13px;">#</th>
            <th style="width:61%; text-align: center;">COMPRA DE SERVICIO</th>
            <th style="width:15%; text-align: center;">REGIONAL</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
          foreach ($compra_serv as $row){
          $nro++;
          $tabla.=
          '<tr style="font-size: 7px;">
            <td style="width:4%;height:9px;text-align: center"><b>'.$nro.'</b></td>
            <td style="width:61%;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>
            <td style="width:15%;">'.strtoupper($row['dep_departamento']).'</td>
          </tr>';
        }
        $tabla.='
        </tbody>
      </table>';

      return $tabla;
    }


    /*--- Reporte Lista consolidado de Compra de servicios (2020 - PDF) ---*/
    public function rep_consolidado_cservicio(){
      $data['mes'] = $this->mes_nombre();
      $data['lista']=$this->lista_cservicio_reporte(0); // PDF
      $this->load->view('admin/programacion/compra_servicio/reporte_lista_consolidado_cservicios', $data);
    }


    /*----- LISTA DE COMPRA DE SERVICIOS (CONSOLIDADO)PARA EL REPORTE XLS ------*/
    public function rep_consolidado_cservicio_xls(){
      $lista=$this->model_estructura_org->list_compra_servicio(0); /// Lista de Compra de Servicios // EXCEL
      $tabla='';
      $tabla .='
          <style>
            table{font-size: 9px;
              width: 40%;
              max-width:1550px;
              overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
          </style>';
      $tabla.=
          '<table border="1" cellpadding="0" cellspacing="0" class="tabla">
            <thead>
              <tr>
                <th style="text-align: center;height:25px;" colspan=3>COMPRA DE SERVICIO '.$this->gestion.'</th>
              </tr>
              <tr style="font-size: 9px;" class="modo1">
                <th style="width:3%; text-align: center; height:20px;" bgcolor="#d8d8d8">#</th>
                <th style="width:20%; text-align: center;" bgcolor="#d8d8d8">COMPRA DE SERVICIO</th>
                <th style="width:17%; text-align: center;" bgcolor="#d8d8d8">REGI&Oacute;N</th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
              foreach($lista as $row){
              $nro++;
              $tabla.=
              '<tr style="font-size: 9px;" class="modo1">
                <td style="width:2%;height:18px;">'.$nro.'</td>
                <td style="width:20%;">'.mb_convert_encoding(strtoupper($row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev']), 'cp1252', 'UTF-8').'</td>
                <td style="width:17%;">'.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
              </tr>';
            }
            $tabla.='
            </tbody>
          </table>';

        date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=Consolidado_compra_servicio_$fecha.xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "";
        echo "".$tabla."";    
    }

    /*--- NOMBRE MES ---*/
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

    /*------ MENU ------*/
    function menu(){
      $enlaces=$this->menu_modelo->get_Modulos(2);
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
   
}