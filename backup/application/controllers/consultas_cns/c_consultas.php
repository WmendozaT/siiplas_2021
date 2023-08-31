<?php
class C_consultas extends CI_Controller {  
    public $rol = array('1' => '1','2' => '10'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('consultas/model_consultas');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
          //  $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
          //  $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/insumos/model_insumo');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
            $this->load->model('mantenimiento/model_configuracion');
            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->ppto= $this->session->userData('verif_ppto'); 
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('genera_informacion');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }


    /*---------- TIPO DE RESPONSABLE ----------*/
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

    //// CONSULTA POA A OFICINA CENTRAL
    public function poa_oficina_central(){
      $data['menu']=$this->genera_informacion->menu(10);
      $data['style']=$this->genera_informacion->style();
      $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

      $data['formulario']='

      <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
          <header>
            <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
            <h2>CONSULTA POA</h2>
          </header>
          <div>
            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body">
              <form class="form-horizontal">
                <input type="hidden" name="base" value="'.base_url().'">
                <fieldset>
                  <legend>CONSULTA POA - OFICINA CENTRAL / '.$this->gestion.'</legend>
                  <div class="form-group">
                    <label class="control-label col-md-2"><b>GERENCIA DE AREA</b></label>
                    <div class="col-md-8">
                      <select class="form-control input-lg" id="proy_id" name="proy_id" title="SELECCIONE GERENCIA DE AREA">
                        <option value="0">Seleccione Gerencia de Area</option>
                        <option value="2848">000 00 002 - GERENCIA GENERAL</option>
                        <option value="2886">000 00 003 - GERENCIA ADMINISTRATIVA FINANCIERA</option>
                        <option value="2887">721 00 040 - GERENCIA DE SERVICIOS DE SALUD</option>
                        <option value="2979">730 00 010 - MEDICINA DEL TRABAJO</option>
                      </select>
                    </div>
                  </div>

                   <div class="form-group">
                    <label class="control-label col-md-2"><b>UNIDAD OPERATIVA</b></label>
                    <div class="col-md-8">
                      <select class="form-control input-lg" id="com_id" name="com_id">
                      </select>
                    </div>
                  </div>
                </fieldset>
              
                <hr>
                <div id="informacion_poa"></div>
              </div>
            </div>
          </div>
        </article>
      </form>';
      $this->load->view('admin/consultas_internas/menu_consultas_poa', $data);
    }


    //// CONSULTA POA A NIVEL NACIONAL
    public function consulta_poa_nacional(){
      $data['menu']=$this->genera_informacion->menu(10);
      //$data['list']=$this->menu_nacional();
      $data['style']=$this->genera_informacion->style();
      $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

      //$data['mensaje']='<div class="jumbotron"><h1>RESUMEN POA '.$this->gestion.'</h1><p>Reporte Resumen consolidado de Programación POA a nivel Regional, segun la siguiente Clasificación :</p><ol style="font-size:16px;"><li>Genera informacion de Programación, Modificacion, Evaluación y Certificacion POA, segun el tipo de Gasto</li><li>Genera Reporte Consolidado del Fornulario N° 4 (Actividades) por Regional.</li><li>Genera Reporte Consolidado del Fornulario N° 5 (requerimientos) por Regional.</li><li>Genera el listado de Certificaciones POA por Regional.</li><li>Genera Informacion sobre la Evaluación POA a nivel Regional</li></ol></div>';
      
      $data['formulario']='
      '.$this->menu_nacional().'
          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                <header>
                    <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                    <h2 class="font-md"><strong>RESUMEN POA - '.$this->gestion.'</strong></h2>  
                </header>
                  <div>
                      <div class="widget-body no-padding">
                         <div id="lista_consolidado">
                         <div class="jumbotron"><h1>RESUMEN POA '.$this->gestion.'</h1><p>Reporte Resumen consolidado de Programación POA a nivel Regional, segun la siguiente Clasificación :</p><ol style="font-size:16px;"><li>Genera informacion de Programación, Modificacion, Evaluación y Certificacion POA, segun el tipo de Gasto</li><li>Genera Reporte Consolidado del Fornulario N° 4 (Actividades) por Regional.</li><li>Genera Reporte Consolidado del Fornulario N° 5 (requerimientos) por Regional.</li><li>Genera el listado de Certificaciones POA por Regional.</li><li>Genera Informacion sobre la Evaluación POA a nivel Regional</li></ol></div>
                         </div>
                      </div>
                      <!-- end widget content -->
                  </div>
                  <!-- end widget div -->
              </div>
              <!-- end widget -->
          </article>';

      $this->load->view('admin/consultas_internas/menu_consultas_poa', $data);
    }

  /*-----  OPCIONES DE CONSULTA POA -----*/
    public function get_opciones($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {

        case 'reporte':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo Reporte....</option>";
          $salida.= "<option value='1'>1.- CONSOLIDADO POA SEGUN TIPO DE GASTO</option>";
          $salida.= "<option value='2'>2.- CONSOLIDADO FORMULARIO 4 (ACTIVIDADES)</option>";
          $salida.= "<option value='3'>3.- CONSOLIDADO FORMULARIO 5 (REQUERIMEINTOS)</option>";
          $salida.= "<option value='4'>4.- LISTA DE CERTIFICACIONES POA</option>";
          $salida.= "<option value='5'>5.- CUADRO DE EVALUACION POA REGIONAL</option>";

        echo $salida; 
        //return $salida;
        break;

        case 'tipo':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo ....</option>";
          $salida.= "<option value='4'>GASTO CORRIENTE</option>";
          $salida.= "<option value='1'>PROYECTO DE INVERSIÓN</option>";

        echo $salida; 


        case 'componentes':
        $salida="";
          $proy_id=trim($_POST["elegido"]);
          $unidades=$this->model_componente->lista_subactividad($proy_id);
          
          $salida.= "<option value='0'>Seleccione unidad operativa ....</option>";
          foreach($unidades as $pr){
            if(count($this->model_producto->list_prod($pr['com_id']))!=0){
              $salida.= "<option value=".$pr['com_id'].">".$pr['serv_cod']." ".$pr['tipo_subactividad']." ".$pr['serv_descripcion']."</option>";
            }
          }

        echo $salida; 

        break;
      }

    }

    //// MENU PRINCIPAL - SELECCION DE OPCIONES
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
      $tabla.='
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <input type="hidden" name="base" value="'.base_url().'">
              <header><b>PLAN OPERATIVO ANUAL - POA '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-3">
                    <label class="label"><b>REGIONAL</b></label>
                    <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                    <option value="">SELECCIONE REGIONAL</option> ';
                    foreach($regionales as $row){
                      if($row['dep_id']!=0){
                        $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                      }
                    }
                    $tabla.='
                    </select>
                  </section>

                  <section class="col col-3" id="tprep">
                    <label class="label"><b>TIPO DE REPORTE</b></label>
                    <select class="form-control" id="tp_rep" name="tp_rep" title="SELECCIONE TIPO DE REPORTE">
                    </select>
                  </section>

                  <section class="col col-3" id="tp">
                    <label class="label"><b>TIPO DE GASTO</b></label>
                    <select class="form-control" id="tipo" name="tipo" title="SELECCIONE TIPO DE GASTO">
                    </select>
                  </section>
                </div>
              </fieldset>
          </form>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:abreVentana(\''.site_url("").'/me/rep_ogestion\');" title="IMPRIMIR ACP DISTRIBUCION REGIONAL" class="btn btn-default">
              <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. A.C.P. (FORM N° 1)
            </a>
            &nbsp;
            <div class="btn-group">
              <a class="btn btn-default" href="javascript:void(0);"><img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. OPERACIONES (FORM N° 2)</a>
              <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/1\');" title="REPORTE FORM 2">
                    CHUQUISACA
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/2\');" title="REPORTE FORM 2">
                    LA PAZ
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/3\');" title="REPORTE FORM 2">
                    COCHABAMBA
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/4\');" title="REPORTE FORM 2">
                    ORURO
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/5\');" title="REPORTE FORM 2">
                    POTOSI
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/6\');" title="REPORTE FORM 2">
                    TARIJA
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/7\');" title="REPORTE FORM 2">
                    SANTA CRUZ
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/8\');" title="REPORTE FORM 2">
                    BENI
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/9\');" title="REPORTE FORM 2">
                    PANDO
                  </a>
                </li>
                <li>
                  <a href="javascript:abreVentana(\''.site_url("").'/me/rep_form2/10\');" title="REPORTE FORM 2">
                    OFICINA CENTRAL
                  </a>
                </li>

              </ul>
            </div>
          </div>
        </article>';
      return $tabla;
    }



    /*--- GET TIPO DE REPORTE POA OFICINA CENTRAL ---*/
    public function get_informacion_poa_ofc(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $unidad_responsable=$this->model_componente->get_componente($com_id,$this->gestion);
        $meses = $this->model_configuracion->get_mes();
        $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($com_id);
        
        $matriz=$this->genera_informacion->tabla_regresion_lineal_servicio($com_id,$this->tmes); /// Tabla para el grafico al trimestre

        $sw=0;
        $programas_bolsas=$this->model_producto->get_lista_form4_uniresp_prog_bolsas($com_id);
        foreach($programas_bolsas as $row){
          if(count($this->model_insumo->lista_requerimientos_inscritos_en_programas_bosas($row['prod_id'],$row['uni_resp']))!=0){
            $sw++;
          }
        }

        $salida='

              <div class="well">
                <div class="row">
                  <div class="collapse navbar-collapse navbar-inverse">
                    <ul class="nav navbar-nav">
                      <li class="active">
                        <a href="javascript:abreVentana(\''.site_url("").'/seg/notificacion_poa_componente_mensual/'.$com_id.'\');" style="color:white">&nbsp;&nbsp;<b>NOTIFICACIÓN POA '.$this->verif_mes[2].' / '.$this->gestion.'</b></a>
                      </li>
                      <li>
                        <a href="#" data-toggle="modal" data-target="#modal_certpoa" onclick="ver_certpoa_uresponsable('.$com_id.');" style="color:white">&nbsp;&nbsp;<b>MIS CERTIFICACIONES POA  / '.$this->gestion.'</b></a>
                      </li>
                      <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" style="color:white"><b>FORMULARIO DE SEGUIMIENTO Y EVALUACION POA </b><b class="caret"></b> </a>
                        <ul class="dropdown-menu">';
                        foreach($meses as $rowm){
                          if($rowm['m_id']<=$this->verif_mes[1]){
                            $salida.='
                            <li>
                              <a href="'.site_url("").'/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$rowm['m_id'].'" target="_blank">REPORTE SEGUIMIENTO POA - '.$rowm['m_descripcion'].' / '.$this->gestion.'</a>
                            </li>';
                          }                     
                        }

                        $salida.='
                        <hr>';
                          for ($i=1; $i <=$this->tmes; $i++) { 
                            $trimestre=$this->model_evaluacion->get_trimestre($i); /// Datos del Trimestre
                            $salida.='
                            <li>
                              <a href="javascript:abreVentana(\''.site_url("").'/seg/ver_reporte_evaluacionpoa/'.$com_id.'/'.$i.'\');" >REP. EVAL. POA - '.$trimestre[0]['trm_descripcion'].'</a>
                            </li>';
                          }
                        $salida.='
                        </ul>
                      </li>
                    </ul>
                  </div>
              

                  <hr>
                    <h4 class="alert-heading"><b>'.$unidad_responsable[0]['tipo_subactividad'].' '.$unidad_responsable[0]['serv_descripcion'].'</b></h4>
                    <br>
                    '.$this->genera_informacion->calificacion_eficacia($matriz[5][$this->tmes]).'
                  <hr>

                  <div class="col-sm-3">
                    <div class="well well-sm bg-color-teal txt-color-white text-center">
                      <h5><b>POA / FORMULARIO N° 4 (.Pdf)</b></h5>
                      <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$com_id.'\');" title="REPORTE FORM. 4">
                        <img src="'.base_url().'assets/ifinal/requerimiento.png" style="margin-left:0px; width: 150px; height:150px"/>
                      </a>
                      <br><b>ACTIVIDADES.PDF</b>
                    </div>
                  </div>
        
                  <div class="col-sm-3">
                    <div class="well well-sm bg-color-teal txt-color-white text-center">
                      <h5><b>POA / FORMULARIO N° 4 (.Xls)</b></h5>
                      <a href="'.site_url("").'/rep/exportar_form4_uresponsable/'.$com_id.'" target=_blank title="DESCARGAR FORM 4 (EXCEL)">
                        <img src="'.base_url().'assets/ifinal/export_excel.png" style="margin-left:0px; width: 150px; height:150px"/>
                      </a>
                      <br><b>ACTIVIDADES.XLS</b>
                    </div>
                  </div>';
                  
                  if(count($lista_insumos)!=0 || $sw!=0){
                    $salida.='
                    <div class="col-sm-3">
                      <div class="well well-sm bg-color-teal txt-color-white text-center">
                        <h5><b>POA / FORMULARIO N° 5 (.Pdf)</b></h5>
                        <a href="javascript:abreVentana(\''.site_url("").'/rep/rep_form5_consolidado/'.$com_id.'\');" title="REPORTE FORM. 5">
                          <img src="'.base_url().'assets/ifinal/requerimiento.png" style="margin-left:0px; width: 150px; height:150px"/>
                        </a>
                        <br><b>REQUERIMIENTOS.PDF</b>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="well well-sm bg-color-teal txt-color-white text-center">
                        <h5><b>POA / FORMULARIO N° 5 (.Xls)</b></h5>
                          <a href="'.site_url("").'/rep/consolidado_requerimientos_mas_programas_bolsas_unidad/'.$com_id.'" target=_blank title="DESCARGAR FORM 5 (EXCEL)">
                            <img src="'.base_url().'assets/ifinal/export_excel.png" style="margin-left:0px; width: 150px; height:150px"/>
                          </a>
                        <br><b>REQUERIMIENTOS.XLS</b>
                      </div>
                    </div>';
                  }

                  $salida.='
                </div>
              </div>';
        
        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- GET TIPO DE REPORTE POA NACIONAL ---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tp_rep = $this->security->xss_clean($post['tp_rep']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        
        $salida='';
        if($tp_rep==1){
          $salida=$this->genera_informacion->lista_gastocorriente_pinversion($dep_id,0,$tp_id);
        }
        elseif ($tp_rep==2) {
          $salida=$this->genera_informacion->consolidado_operaciones_distrital($dep_id,0,$tp_id); /// Consolidado Formulario N° 4 
        }
        elseif ($tp_rep==3) {
          $regional=$this->model_proyecto->get_departamento($dep_id);
          $requerimientos=$this->mrep_operaciones->consolidado_requerimientos_regional_distrital_directo(0, $dep_id, $tp_id); /// Consolidado Requerimientos 2020-2021 (Relacion Directa)
          $titulo_reporte='CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']);

          if(count($requerimientos)>7000){
            $salida='
              <hr>
              <div class="alert alert-warning " role="alert">
                <h4 class="alert-heading">Alerta !</h4>
                <hr>
                <p class="mb-0">'.$titulo_reporte.' (NO PUEDE SER GENERADO POR LA DIMESION DEL ARCHIVO, PARA OBTENER LA INFORMACION SOLICITADA LE SUGERIMOS DESCARGARLO EN FORMATO EXCEL.)</p>
              </div>
              
              <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$dep_id.'/0/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;DESCARGAR CONSOLIDADO FORM. N° 5</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <hr>';
          }
          else{
            $salida=$this->genera_informacion->genera_consolidado_form5_regional_distrital($titulo_reporte,$requerimientos,$dep_id,0,$tp_id); /// Consolidado formulario N° 5  
          }

          //$salida=$this->genera_informacion->consolidado_requerimientos_distrital($dep_id,0,$tp_id); /// Consolidado formulario N° 5
        }
        elseif ($tp_rep==4) {
          $salida=$this->genera_informacion->lista_certificaciones_poa($dep_id,$tp_id);
        }
        elseif ($tp_rep==5) {
          $salida='<hr><center><iframe id="ipdf" width="99%" height="1000px;" src="'.base_url().'index.php/rep_eval_poa/iframe_rep_evaluacionpoa/'.$dep_id.'/0/'.$tp_id.'"></iframe></center>';
        }

        //$lista=$this->lista_certificaciones_poa($dist_id,$tp_id);
        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*-------- GET DATOS MODIFICACION POA --------*/
    public function get_mpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }

        $tabla=$this->list_cites_generados($proy_id); /// Mis Modificaciones POA
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- LISTA DE MODIFCACIONES FORMULARIO 4 Y 5 ---*/
    public function list_cites_generados($proy_id){
      $tabla='';
      // === LIST CITES REQUERIMIENTOS 
        $cites_form5=$this->model_modrequerimiento->list_cites_requerimientos_proy($proy_id);
        $cites_form4=$this->model_modfisica->list_cites_Operaciones_proy($proy_id);

        $tabla.='
        <hr>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid" >
            <section class="col col-6">
              <input id="searchTerm_form4" type="text" onkeyup="doSearch_form4()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
            </section>
            <b>FORMULARIO N° 4 - ACTIVIDADES</b>
            <table class="table table-bordered" id="datos_form4">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CITE</th>
                  <th scope="col">FECHA CITE</th>
                  <th scope="col">COD. MOD. POA.</th>
                  <th scope="col">UNIDAD RESPONSABLE</th>
                  <th scope="col">REPORTE</th>
                </tr>
              </thead>
              <tbody>';
              $nro=0;
              foreach($cites_form4 as $cit){
                if($cit['tp_reporte']==0){
                    $ca=$this->model_modfisica->operaciones_adicionados($cit['cite_id']);
                    $cm=$this->model_modfisica->operaciones_modificados($cit['cite_id']);
                    $cd=$this->model_modfisica->operaciones_eliminados($cit['cite_id']);

                    if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                      $nro++;
                      $tabla .='<tr>';
                        $tabla .='<td align="center">'.$nro.'</td>';
                        $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                        $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                        $tabla .='<td></td>';
                        $tabla .='<td>'.$cit['com_componente'].'</td>';
                        $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/reporte_modfis/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE ACTIVIDADES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                      $tabla .='</tr>';
                    }
                }
                else{
                    $nro++;
                    $tabla .='<tr>';
                      $tabla .='<td align="center">'.$nro.'</td>';
                      $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                      $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                      $tabla .='<td></td>';
                      $tabla .='<td>'.$cit['com_componente'].'</td>';
                      $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/reporte_modfis/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                    $tabla .='</tr>';
                }
              }
              $tabla.='
              </tbody>
            </table>
          </section>
        </article>

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
          <section id="widget-grid">
            <section class="col col-6">
              <input id="searchTerm_form5" type="text" onkeyup="doSearch_form5()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
            </section>
            <b>FORMULARIO N° 5 - REQUERIMIENTOS</b>
            <table class="table table-bordered" id="datos_form5">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CITE</th>
                  <th scope="col">FECHA CITE</th>
                  <th scope="col">COD. MOD. POA.</th>
                  <th scope="col">UNIDAD RESPONSABLE</th>
                  <th scope="col">REPORTE</th>
                </tr>
              </thead>
              <tbody>';
               $nro=0;
                foreach($cites_form5 as $cit){
                  $color='';
                  $codigo='<font color=blue><b>'.$cit['cite_codigo'].'</b></font>';
                  if($cit['cite_estado']==0){
                    $color='#fbdfdf';
                    $codigo='<font color=red><b>SIN CÓDIGO</b></font>';
                  }

                    $nro++;
                    $tabla .='<tr bgcolor='.$color.'>';
                      $tabla .='<td align="center">'.$nro.'</td>';
                      $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                      $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                      $tabla .='<td>'.$codigo.'</td>';
                      $tabla .='<td>'.$cit['com_componente'].'</td>';
                      $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_financiera/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE REQUERIMIENTOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                    $tabla .='</tr>';
                  }
              $tabla.=' 
              </tbody>
            </table>
          </section>
        </article>';

      return $tabla;
    }



    /*--- GET DATOS CERTIFICACION POA POR UNIDAD 2022 ---*/
    public function get_certpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }

        $tabla=$this->genera_informacion->list_certificacionpoa($proy_id,$proyecto[0]['tp_id']); /// Mi evaluacion
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*--- GET DATOS CERTIFICACION POA POR COMPONENTE 2023 ---*/
    public function get_certpoa_uresponsable(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']); /// PROYECTO

        $titulo_poa=$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'];
        

        $tabla=$this->genera_informacion->list_certificacionpoa_componente($com_id); /// Mis Certificaciones
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*--- GET DATOS EVALUACION POA POR UNIDAD 2022 ---*/
    public function get_evalpoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }

        $evaluacion=$this->genera_informacion->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre

        $tabla=$this->genera_informacion->detalle_evaluacionpoa($evaluacion,$proy_id); /// Mi evaluacion
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
          'evaluacion'=>$evaluacion,

        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }





    //// cambiar gestion 
    public function cambiar_gestion(){
      $nueva_gestion = strtoupper($this->input->post('gestion_usu'));
      $this->session->set_userdata('gestion', $nueva_gestion);

      redirect('consulta/mis_operaciones','refresh');
    }


    /*----------------------------------------*/
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