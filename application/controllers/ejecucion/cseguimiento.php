<?php
class Cseguimiento extends CI_Controller {
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('reporte_eval/model_evalregional');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_id = $this->session->userData('dist');
        $this->dep_id = $this->session->userData('dep_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->mes = $this->mes_nombre();
        //$this->tmes = 3;
        $this->fun_id = $this->session->userData('fun_id');
        $this->tp_adm = $this->session->userData('tp_adm');
        
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->com_id=$this->session->userdata('com_id');
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema
       // $this->load->library('menu');
        $this->verif_mes=$this->session->userdata('mes_actual');
        $this->load->library('seguimientopoa');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }



    /*----- MIS POAS APROBADOS ------*/
    public function lista_poa(){
      $data['menu'] = $this->seguimientopoa->menu(4);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->seguimientopoa->tp_resp(); 

      $data['proyectos']='No disponible';
      $data['gasto_corriente']='No disponible';


      $data['proyectos']=$this->list_pinversion(4);
      $data['gasto_corriente']=$this->list_gasto_corriente(4);
      
      $data['titulo']=$this->seguimientopoa->aviso_seguimiento_evaluacion_poa();
      $this->load->view('admin/evaluacion/seguimiento_poa/list_poas_aprobados', $data);
    
      /*$insumos=$this->model_insumo->lista_insumos(2021);

      $tabla='';
      $tabla.='
      <table border="1" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                 <tr style="background-color: #66b2e8">
                    <th style="width:2%;height:40px;background-color: #eceaea;">REGIONAL</th>
                    <th style="width:2%;height:40px;background-color: #eceaea;">DISTRITAL</th>
                    <th style="width:2%;height:40px;background-color: #eceaea;">PROGRAMA</th>
                    <th style="width:2%;height:40px;background-color: #eceaea;">DESCRIPCION</th>
                    <th style="width:2%;height:40px;background-color: #eceaea;">GESTION</th>
                    <th style="width:2%;height:40px;background-color: #eceaea;">GASTO CORRIENTE / PROYECTO DE INVERSION</th>
                    
                    <th style="width:2%;background-color: #eceaea;">PARTIDA</th>
                    <th style="width:20%;background-color: #eceaea;">REQUERIMIENTO</th>
                    <th style="width:5%;background-color: #eceaea;">UNIDAD DE MEDIDA</th>
                    <th style="width:3%;background-color: #eceaea;">CANTIDAD</th>
                    <th style="width:5%;background-color: #eceaea;">PRECIO</th>
                    <th style="width:5%;background-color: #eceaea;">COSTO TOTAL</th>
                    <th style="width:5%;background-color: #eceaea;">OBSERVACI&Oacute;N</th>
                    <th style="width:5%;background-color: #eceaea;">GESTION</th>
                    <th style="width:5%;background-color: #eceaea;">FECHA</th>
                    <th style="width:5%;background-color: #eceaea;">INS. CODIGO</th>
                  </tr>
              </thead>
            <tbody>';
            foreach ($insumos as $row){
              $tabla.='
              <tr>
                <td>'.strtoupper($row['dep_departamento']).'</td>
                <td>'.strtoupper($row['dist_distrital']).'</td>
                <td>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                <td>'.strtoupper($row['aper_descripcion']).'</td>
                <td>'.$row['aper_gestion'].'</td>
                <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>

                <td>'.$row['par_codigo'].'</td>
                <td>'.strtoupper($row['ins_detalle']).'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td>'.round($row['ins_cant_requerida'],2).'</td>
                <td>'.round($row['ins_costo_unitario'],2).'</td>
                <td>'.round($row['ins_costo_total'],2).'</td>
                <td>'.strtoupper($row['ins_observacion']).'</td>
                <td>'.$row['ins_gestion'].'</td>
                <td>'.$row['ins_fecha_requerimiento'].'</td>
                <td>'.$row['ins_codigo'].'</td>
              </tr>';
            }
            $tabla.='
            </tbody>
          </table>';*/


/*header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=CONSOLIDADO_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";*/

     // echo $tabla;


    }


    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_gasto_corriente($proy_estado){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      $meses = $this->model_configuracion->get_mes();

      $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
      $tabla='';
      
      $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <table id="dt_basic" class="table table-bordered" style="width:100%;">
        <thead>
          <tr style="height:35px;">
            <th style="width:1%;" bgcolor="#474544">#</th>
            <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR">MIS UNIDADES</th>
            <th style="width:10%;" bgcolor="#474544" title="SELECCIONAR REPORTE SEGUIMIENTO">REPORTE SEGUIMIENTO MENSUAL</th>
            <th style="width:3%;" bgcolor="#474544" title="EVALUACION POA">EVALUACION POA</th>
            <th style="width:3%;" bgcolor="#474544" title="EJECUCION CERT. POA"></th>
            <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
            <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">UNIDAD / ESTABLECIMIENTO DE SALUD</th>
            <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
            <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
            <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
            <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
            <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
          </tr>
        </thead>
        <tbody>';
          $nro=0;
          foreach($unidades as $row){
            if($row['proy_estado']==4){
              $nro++;
              $tabla.='
              <tr style="height:45px;">
                <td align=center title='.$row['proy_id'].'><b>'.$nro.'</b></td>
                <td align=center bgcolor="#deebfb">
                  <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" style="font-size:10px;">
                    <i class="glyphicon glyphicon-list"></i> <b>UNIDADES OPERATIVAS</b>
                  </a>
                </td>
                <td align=center bgcolor="#deebfb">
                  <div class="btn-group">
                    <a class="btn btn-default" style="font-size:10px"><b>REP. MENSUAL</b></a>
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span></a>
                    <ul class="dropdown-menu">';
                      foreach($meses as $rowm){
                    //  if($rowm['m_id']<=ltrim(date("m"), "0")){
                      if($rowm['m_id']<=$this->verif_mes[1]){
                        $tabla.='
                        <li>
                          <a href="'.site_url("").'/seg/reporte_consolidado_seguimientopoa_mensual/'.$row['proy_id'].'/'.$rowm['m_id'].'" target="_blank">REPORTE SEG. '.$rowm['m_descripcion'].'</a>
                        </li>';
                      }                     
                    }
                    $tabla.='
                    </ul>
                  </div>
                </td>
                <td align=center bgcolor="#deebfb">
                  <a href="'.site_url("").'/eval/eval_unidad/'.$row['proy_id'].'" title="REPORTE DE EVALUACION POA POR UNIDAD" target="_blank" ><img src="'.base_url().'assets/img/ejecucion.png" WIDTH="35" HEIGHT="35"/></a>
                </td>
                <td align=center bgcolor="#deebfb">
                  <a href="#" data-toggle="modal" data-target="#modal_distribucion_mensual" class=" distribucion" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'">
                  <img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="35" HEIGHT="35"/></a>
                </td>
                <td align="center" style="font-size:13px"><center><b>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</b></center></td>
                <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                <td>'.$row['escalon'].'</td>
                <td>'.$row['nivel'].'</td>
                <td>'.$row['tipo_adm'].'</td>
                <td>'.strtoupper($row['dep_departamento']).'</td>
                <td>'.strtoupper($row['dist_distrital']).'</td>
              </tr>';
            }
          }
        $tabla.='
        </tbody>
          <tr>
            <td colspan="10" style="height:50px;"></td>
          </tr>
      </table>';
      return $tabla;
    }

    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($proy_estado){
      $proyectos=$this->model_proyecto->list_pinversion(1,$proy_estado);
      $tabla='';
      $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
        <table id="dt_basic2" class="table2 table-bordered" style="width:100%;">
          <thead>
            <tr style="height:50px;">
              <th style="width:1%;height:20%" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="MIS UNIDADES RESPONSABLES"></th>
              <th style="width:5%;" bgcolor="#474544" title="PROGRAMACION INICIAL">PROG. INICIAL '.$this->gestion.'</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:25%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:15%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $componentes=$this->model_componente->lista_subactividad($row['proy_id']);
              $temp_inicial=$this->model_insumo->temporalidad_inicial_total_unidad($row['proy_id']); /// temporalidad inicial
              $nro++;
              $tabla.='
                <tr style="height:50px;">
                  <td title='.$row['proy_id'].'><center>'.$nro.'</center></td>
                  <td align=center bgcolor="#deebfb">';
                  foreach($componentes as $rowc){
                  if(count($this->model_producto->list_prod($rowc['com_id']))!=0){
                    $tabla.='
                      <a href="'.site_url("").'/seg/formulario_seguimiento_poa/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-primary" title="REALIZAR SEGUIMIENTO">
                        REGISTRAR EJECUCIÓN '.$this->verif_mes[2].' / '.$this->gestion.'
                      </a>';
                    }
                  }
                  $tabla.='
                  </td>
                  <td bgcolor="#deebfb" align="center">';
                    if(count($temp_inicial)!=0){
                      $tabla.='
                      <a href="#" data-toggle="modal" data-target="#modal_distribucion_inicial" class=" distribucion_inicial" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                        <img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="35" HEIGHT="35"/>
                      </a>';
                    }
                    else{
                      $tabla.='-';
                    }
                  $tabla.='
                  </td>
                  <td align="center" style="font-size:13px"><b>'.$row['proy_sisin'].'</b></td>
                  <td>'.$row['proy_nombre'].'</td>
                  <td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>
                  <td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>
                  <td>'.strtoupper($row['pfec_descripcion']).'</td>
                </tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      
      return $tabla;
    }


    /*----- GET LISTA DE SUBACTIVIDADES -----*/
    public function get_subactividades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);

        $evaluacion='
          <a href="'.site_url("").'/eval/eval_unidad/'.$proy_id.'" title="REPORTE DE EVALUACION POA" target="_blank" class="btn btn-default"><img src="'.base_url().'assets/img/impresora.png" WIDTH="50" HEIGHT="50"/><br>VER EVALUACIÓN</a>';

        $tabla=$this->mis_unidadesresponsables($proy_id);
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'evaluacion'=>$evaluacion,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }




    /*------ GET UNIDADES REPONSABLES -----*/
    public function mis_unidadesresponsables($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $titulo='UNIDAD RESPONSABLE';
      //$titulo_boton='';
      $tabla='';

      $tabla.=' 
        <table class="table table-bordered">
          <thead>
          <tr>
            <th style="width:3%;" bgcolor="#474544"> COD.</th>
            <th style="width:50%;" bgcolor="#474544">UNIDAD RESPONSABLE</th>
            <th style="width:10%;" bgcolor="#474544">PONDERACI&Oacute;N</th>
            <th style="width:10%;" bgcolor="#474544"></th>
            <th style="width:1%;" bgcolor="#474544"></th>
          </tr>
          </thead>
          <tbody>';
          $nro_c=0;
            $componentes=$this->model_componente->lista_subactividad($proy_id);
            foreach($componentes as $rowc){
              if(count($this->model_producto->list_prod($rowc['com_id']))!=0){
                $verif=$this->model_seguimientopoa->get_seguimiento_poa_mes_subactividad($rowc['com_id'],$this->verif_mes[1]);
                $nro_c++;
                $tabla.='
                <tr>
                  <td><b>'.$rowc['serv_cod'].'</b></td>
                  <td><b>'.$rowc['tipo_subactividad'].' '.$rowc['serv_descripcion'].'</b></td>
                  <td>'.$rowc['com_ponderacion'].'%</td>
                  <td>';
                    if($proyecto[0]['tp_id']==1){
                      $tabla.='
                      <a href="'.site_url("").'/seg/formulario_seguimiento_poa/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-primary" title="REALIZAR SEGUIMIENTO">
                       EJECUCION POA '.$this->verif_mes[2].' / '.$this->gestion.'
                      </a>';
                    }
                    else{
                      $tabla.='
                      <a href="'.site_url("").'/seg/formulario_seguimiento_poa/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-primary" title="REALIZAR SEGUIMIENTO">
                      '.$this->btn_seguimiento_evaluacion_poa().'
                      </a>';
                    }
                    
                  $tabla.='
                  </td>
                  <td align=center><img id="load'.$rowc['com_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
                </tr>';
                $tabla.=' <script>
                            document.getElementById("myBtn'.$rowc['com_id'].'").addEventListener("click", function(){
                            this.disabled = true;
                            document.getElementById("load'.$rowc['com_id'].'").style.display = "block";
                            });
                          </script>';
              }
            }
          $tabla.='
          </tbody>
        </table>';

      return $tabla;
    }


  /*----- GET EJECUCION FORMULARIO 4 5 -----*/
  public function get_distribucion_mensual_certpoa(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// DATOS DEL PROYECTO
      $ins_programado = $this->model_insumo->get_mes_programado_insumo_unidad_menos10000($proyecto[0]['aper_id']); /// INSUMO PROGRAMADO CONSOLIDADO
      $form4_programado = $this->model_proyecto->temporalidad_prog_form4_unidad($proyecto[0]['aper_id']); /// FORM4 PROGRAMADO CONSOLIDADO

      $tabla='';
      if(count($ins_programado)!=0){
          $ins_certificado = $this->model_insumo->get_mes_certificado_insumo_unidad_menos10000($proyecto[0]['aper_id']); /// INSUMO CERTIFICADO        
          $form4_ejec = $this->model_proyecto->temporalidad_ejec_form4_unidad($proyecto[0]['aper_id']); /// FORM 4 EJECUTADO

            for ($i=0; $i <=12 ; $i++) { 
              if($i==0){
                $prog_vector[$i]=$ins_programado[0]['total_programado'];
              }
              else{
                $prog_vector[$i]=$ins_programado[0]['prog_mes'.$i]; 
              }
            }

            if(count($ins_certificado)!=0){
              for ($i=0; $i <=12 ; $i++) { 
                if($i==0){
                  $ejec_vector[$i]=$ins_certificado[0]['total_certificado'];
                }
                else{
                  $ejec_vector[$i]=$ins_certificado[0]['ejec_mes'.$i]; 
                }
              }
            }
            else{
              for ($i=0; $i <=12 ; $i++) { 
                $ejec_vector[$i]=0;
              }
            }

            //// ----- Ejecucion de Certificacion POA
            $matriz_ppto=$this->matriz_consolidado_mensual($prog_vector,$ejec_vector); /// genera matriz
            $tabla_normal=$this->genera_tabla_temporalidad_prog_ejec_unidad($matriz_ppto,0,$proyecto[0]['aper_id'],5); /// normal
            $tabla_impresion=$this->genera_tabla_temporalidad_prog_ejec_unidad($matriz_ppto,1,$proyecto[0]['aper_id'],5); /// impresion
            //// ------------------------------------

            $suma_total_meta=0;
            for ($i=1; $i <=12 ; $i++) { 
              $suma_total_meta=$suma_total_meta+$form4_programado[0]['prog_mes'.$i];
            }
            //--
            for ($i=0; $i <=12 ; $i++) { 
              if($i==0){
                $prog_vector_form4[$i]=round($suma_total_meta,2);
              }
              else{
                $prog_vector_form4[$i]=round($form4_programado[0]['prog_mes'.$i],2); 
              }
            }

            if(count($form4_ejec)!=0){
              for ($i=0; $i <=12 ; $i++) { 
                if($i==0){
                  $ejec_vector_form4[$i]=0;
                }
                else{
                  $ejec_vector_form4[$i]=round($form4_ejec[0]['ejec_mes'.$i],2); 
                }
              }
            }
            else{
              for ($i=0; $i <=12 ; $i++) { 
                $ejec_vector_form4[$i]=0;
              }
            }

            //// ----- Ejecucion de Certificacion POA
            $matriz_form4=$this->matriz_consolidado_mensual($prog_vector_form4,$ejec_vector_form4); /// genera matriz form 4
            $tabla_normal_form4=$this->genera_tabla_temporalidad_prog_ejec_unidad($matriz_form4,0,$proyecto[0]['aper_id'],4); /// normal
            $tabla_impresion_form4=$this->genera_tabla_temporalidad_prog_ejec_unidad($matriz_form4,1,$proyecto[0]['aper_id'],4); /// impresion
            //// ------------------------------------



            $tabla='
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                  <div id="cabecera_ejec" style="display: none">'.$this->cabecera_reporte_grafico($proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev']).'</div>
                  <div class="well">
                    <div id="grafico_form5">
                      <center><div id="graf_form5" style="width: 880px; height: 500px; margin: 0 auto; text-align:center"></div></center>
                    </div>
                    <hr>
                    '.$tabla_normal.'
                    <div id="tabla_impresion_form5" style="display: none">
                      '.$tabla_impresion.'
                    </div>
                    <div align="right">
                      <button  onClick="imprimir_form5()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  </div>
                  </div>
                </article>
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                  <div class="well">
                    <div id="grafico_form4">
                      <center><div id="graf_form4" style="width: 900px; height: 500px; margin: 0 auto; text-align:center"></div></center>
                    </div>
                    <hr>
                    '.$tabla_normal_form4.'
                    <div id="tabla_impresion_form4" style="display: none">
                      '.$tabla_impresion_form4.'
                    </div> 
                    <div align="right">
                      <button  onClick="imprimir_form4()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                  </div>
                </article>';

          $result = array(
            'respuesta' => 'correcto',
            'proyecto'=>$proyecto,
            'matriz_form5'=>$matriz_ppto,
            'matriz_form4'=>$matriz_form4,
            'tabla'=>$tabla,
            
          );
      }
      else{
        $result = array(
          'respuesta' => 'error',
          'tabla'=>'Sin Temporalidad',
        );
      }

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*---- CABECERA REPORTE OPERACIONES POR REGIONALES (GRAFICO)----*/
  function cabecera_reporte_grafico($titulo){
    $tabla='';

    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                        <td style="width:45%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                    </tr>
                </table>
            </td>
            <td style="width:30%; height: 2%; font-size: 8px;text-align:right;">
              '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px black; text-align: center;">
              <td style="width:10%; text-align:center;">
              </td>
              <td style="width:80%; height: 5%">
                <table align="center" border="0" style="width:100%;">
                  <tr style="font-size: 23px;font-family: Arial;">
                    <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }






  /*---- Matriz consolidado mensual ----*/
  public function matriz_consolidado_mensual($vector_prog,$vector_ejec){

    /// Matriz Vacia
    for ($i=0; $i <=12 ; $i++) { 
      for ($j=0; $j <=6 ; $j++) { 
        $matriz[$i][$j]=0;
      }
    }
    /// ----------

      $suma_acumulado_prog=0; /// acumulado prog
      $suma_acumulado_ejec=0; /// acumulado ejec
      for ($i=0; $i <=12 ; $i++) { 
        $matriz[0][$i]=$vector_prog[$i]; /// Programado
        $matriz[1][$i]=$vector_ejec[$i]; /// Ejecutado
        $matriz[2][$i]=0; /// % Ejecucion mes
        /*if($matriz[0][$i]!=0){
          $matriz[2][$i]=round((($matriz[1][$i]/$matriz[0][$i])*100),2); /// % Ejecucion Mes
        }*/
        
        if($i!=0){
          $suma_acumulado_prog=$suma_acumulado_prog+$matriz[0][$i];
          $suma_acumulado_ejec=$suma_acumulado_ejec+$matriz[1][$i];

          $matriz[2][$i]=$suma_acumulado_prog; /// Acumulado Mensual Programado
          $matriz[3][$i]=$suma_acumulado_ejec; /// Acumulado Mensual Ejecutado

          $matriz[4][$i]=0;

          if($matriz[2][$i]!=0){
            $matriz[4][$i]=round((($matriz[3][$i]/$matriz[2][$i])*100),2); /// % Cumplimiento Mes
          }


          $matriz[5][$i]=0;
          $matriz[6][$i]=0;

          if($matriz[0][0]!=0){
            $matriz[5][$i]=round((($matriz[2][$i]/$matriz[0][0])*100),2); /// % Acumulado Mensual Programado
            $matriz[6][$i]=round((($matriz[3][$i]/$matriz[0][0])*100),2); /// % Acumulado Mensual Ejecutado
          }
          
        }
      }

      return $matriz;
  }


  /*---- Genera Tabla (Vista e impresion), distribucion de meses prog. y cert. ----*/
  public function genera_tabla_temporalidad_prog_ejec_unidad($matriz,$tipo_reporte,$aper_id,$formulario){
    //// tipo_reporte : 0 normal
    //// tipo_reporte : 1 impresion

    $tit1='PROGRAMADO';
    $tit2='EJECUTADO';
    $tit3='PROG. ACUMULADO';
    $tit4='EJEC. ACUMULADO';
    $tit5='% CUMP. MENSUAL';
    $tit6='% PROG. ACUMULADO';
    $tit7='% EJEC. ACUMULADO';
    if($formulario==5){
      $tit1='PPTO. PROGRAMADO';
      $tit2='PPTO. CERTIFICADO';
      $tit3='PPTO. PROG. ACUMULADO';
      $tit4='PPTO. CERT. ACUMULADO';
      $tit5='% CUMP. MENSUAL';
      $tit6='% PROG. ACUMULADO';
      $tit7='% EJEC. ACUMULADO';
    }


    $tabla='';
    $class='class="table table-bordered" style="width:100%;"';
    if($tipo_reporte==1){
      $class='class="change_order_items" border=1 style="width:100%;"';
      
    }

    $tabla.='
      <center>
      <table '.$class.'>
        <thead>
        <tr>
          <th style="width:1%;" bgcolor="#474544">'.$aper_id.'</th>
          <th style="width:6%;" bgcolor="#474544">ENE..</th>
          <th style="width:6%;" bgcolor="#474544">FEB.</th>
          <th style="width:6%;" bgcolor="#474544">MAR.</th>
          <th style="width:6%;" bgcolor="#474544">ABR.</th>
          <th style="width:6%;" bgcolor="#474544">MAY.</th>
          <th style="width:6%;" bgcolor="#474544">JUN.</th>
          <th style="width:6%;" bgcolor="#474544">JUL.</th>
          <th style="width:6%;" bgcolor="#474544">AGO.</th>
          <th style="width:6%;" bgcolor="#474544">SEPT.</th>
          <th style="width:6%;" bgcolor="#474544">OCT.</th>
          <th style="width:6%;" bgcolor="#474544">NOV.</th>
          <th style="width:6%;" bgcolor="#474544">DIC.</th>
        </tr>
        </thead>
        <tbody>
          <tr>
          <td title="PROGRAMADO MENSUAL">'.$tit1.'</td>';
          for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[0][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr>
          <td title="EJECUTADO MENSUAL">'.$tit2.'</td>';
         for ($i=1; $i <=12 ; $i++) { 
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[1][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          
          <tr>
          <td title="PROGRAMADO ACUMULADO MENSUAL">'.$tit3.'</td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[2][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr>
          <td title="EJECUTADO ACUMULADO MENSUAL">'.$tit4.'</td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td align=right bgcolor='.$color.'>'.number_format($matriz[3][$i], 2, ',', '.').'</td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor="#fcfde9">
          <td title="(%) CUMPLIMIENTO MENSUAL"><b>'.$tit5.'</b></td>';
          for ($i=1; $i <=12 ; $i++) { 
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[4][$i].'%</b></td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor=#e7f7f6>
          <td title="(%) PROGRAMADO ACUMULADO"><b>'.$tit6.'</b></td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            }
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[5][$i].'%</b></td>';
          }
          $tabla.='
          </tr>
          <tr bgcolor=#e7f7f6>
          <td title="(%) EJECUTADO ACUMULADO"><b>'.$tit7.'</b></td>';
         for ($i=1; $i <=12 ; $i++) {
            $color='';
            if($i==$this->mes_sistema){
              $color='#a7e9e1';
            } 
            $tabla.='<td style="font-size: 12px;" align=right bgcolor='.$color.'><b>'.$matriz[6][$i].'%</b></td>';
          }
          $tabla.='
            </tr>
          <tbody>
        </table>';

      return $tabla;
  }










  /*----  Boton de Seguimiento / Evaluacion POA ----*/
  public function btn_seguimiento_evaluacion_poa(){
    $tabla='';
    $tabla='REALIZAR SEGUIMIENTO POA';
    $dia_actual=ltrim(date("d"), "0");
    $mes_actual=ltrim(date("m"), "0");

    $fecha_actual = date('Y-m-d');

    $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
    if(count($get_fecha_evaluacion)!=0){
        $configuracion=$this->model_configuracion->get_configuracion_session();
        $date_actual = strtotime($fecha_actual); //// fecha Actual
        $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
        $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

        if (($date_actual >= $date_inicio) && ($date_actual <= $date_final)){
          if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0){

            $tabla='REALIZAR EVALUACI&Oacute;N POA';
          }
        }
    }

    return $tabla;
  }


  ////// Probando Funcion Verificado Datos
  public function formulario_segpoa2($com_id){
        $post = $this->input->post();
        $prod_id = 57940; /// prod id
        $tp = 1; /// tp
        $ejec= 4;/// prod id
        $mes_id= 11;/// mes id
        $producto=$this->model_producto->get_producto_id($prod_id); /// datos del formulario N° 4

        if($producto[0]['indi_id']==2 & $producto[0]['mt_id']==1){ //// INDICADOR RECURRENTE
          $programado=$this->model_producto->get_mes_programado_form4($prod_id,$mes_id); /// Programado del mes

            if($ejec<=$programado[0]['pg_fis']){
              echo "true";
            }
            else{
              echo "false";
            }
        }
        else{ /// INDICADOR ABSOLUTO, RELATIVO
            $valor_ejecutado=0;
            $programado=$this->model_seguimientopoa->rango_programado_trimestral_productos($prod_id,$this->tmes); /// Programado
            $ejecutado=$this->model_seguimientopoa->rango_ejecutado_trimestral_productos($prod_id,$this->tmes); /// Ejecutado

            if(count($programado)!=0){
              if(count($ejecutado)!=0){
                $valor_ejecutado=$ejecutado[0]['trimestre'];
              }

                if($tp==0){ /// registro
                  if($valor_ejecutado<$programado[0]['trimestre']){
                    if(($ejec+$valor_ejecutado)<=$programado[0]['trimestre'] ){
                      echo 'true';
                    }
                    else{
                      echo 'false';
                    }
                    
                  }
                  else{
                    echo 'false';
                  }
                }
                else{ /// modificacion
                  $valor_ejec=$this->model_producto->verif_ope_evaluado_mes($prod_id,$mes_id);
                  if(count($valor_ejec)==0){ /// no existe valor registrado
                    echo "true";
                  }
                  else{
                    $valor_registrado=$valor_ejec[0]['pejec_fis'];
                    $valor_ejecutado=$valor_ejecutado-$valor_registrado;

                    echo 'valor registrado : '.$valor_registrado.' - Evalor ejecutado : '.$valor_ejecutado.' - mes : '.$this->tmes.'<br><br>';


                    if(($ejec+$valor_ejecutado)<=$programado[0]['trimestre'] ){
                      echo 'true true <br>'.$ejec.'---'.$valor_ejecutado.'<br>';
                      echo ($ejec+$valor_ejecutado).'--'.$programado[0]['trimestre'];
                    }
                    else{
                      echo 'false';
                    }
                  }
                }

            }
            else{
              echo 'false';
            }
        }

        echo "----------------------- <br>";
        $prod_id=57941;
        $mes=11;
        $producto=$this->model_producto->get_producto_id($prod_id);
      $diferencia[1]=0;$diferencia[2]=0;$diferencia[3]=0;
      $sum_prog=0;
      $sum_ejec=0;
      for ($i=1; $i <=$mes-1; $i++) { 
        $prog=$this->model_seguimientopoa->get_programado_poa_mes($prod_id,$i); /// Programado meses anteriores
        if(count($prog)!=0){
          $sum_prog=$sum_prog+$prog[0]['pg_fis'];
        }

        $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i); /// Ejecutado meses anteriores
        if(count($ejec)!=0){
          $sum_ejec=$sum_ejec+$ejec[0]['pejec_fis'];
        }
      }



      $prog=$this->model_seguimientopoa->get_programado_poa_mes($prod_id,$mes); /// Programado mes actual
      $diferencia[2]=0;
      if(count($prog)!=0){
        $diferencia[2]=round($prog[0]['pg_fis'],2);
      }

      $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$mes); /// Ejecutado mes actual
      $diferencia[3]=0;
      if(count($ejec)!=0){
        $diferencia[3]=round($ejec[0]['pejec_fis'],2);
      }

      $diferencia[1]=($sum_prog-$sum_ejec); /// no ejecutado en el mes anterior
      if($producto[0]['indi_id']==2 & $producto[0]['mt_id']==1){
        $diferencia[1]=0;
      }
      
      echo $sum_prog.' - '.$sum_ejec.' = '.$diferencia[1];
  }


  //// FORMULARIO DE SEGUIMIENTO POA 2022
  public function formulario_segpoa($com_id){
    $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
    $proyecto=$this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);

    if(count($componente)!=0){
      if($proyecto[0]['tp_id']==1){
        redirect('form_ejec_pinversion/'.$com_id);
      }
      else{
        redirect('seg/formulario_seguimiento_poa_gc/'.$com_id);
      }
    }
    else{
      echo "Error !!!";
    }
  }




  //// FORMULARIO DE SEGUIMIENTO GASTO CORRIENTE
  public function formulario_segpoa_gasto_corriente($com_id){
    $data['menu'] = $this->seguimientopoa->menu(4);
    //$data['base'] = $this->seguimientopoa->menu(4);
    $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE

      $s1=' <input type="hidden" name="mes_activo" value='.$this->verif_mes[1].'>';
      $s2='';
      $s4='';
      
      $data['cabecera_formulario']=$this->seguimientopoa->cabecera_formulario($componente);
      $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($com_id,$this->tmes); /// Tabla para el grafico al trimestre
      $data['calificacion']='
        <hr>
        <div id="calificacion" style="font-family: Arial;font-size: 10%;">'.$this->seguimientopoa->calificacion_eficacia($data['tabla'][5][$this->tmes],0).'</div></fieldset>';
      
      $s1.='
      <div class="row">
        <div style="font-size: 13pt;font-family:Verdana;"><b>FORMULARIO DE SEGUIMIENTO POA - '.$this->verif_mes[2].' '.$this->gestion.'</b></div>
        <hr>
            <div align="right">
              '.$this->seguimientopoa->button_rep_seguimientopoa($com_id).'
              '.$this->seguimientopoa->button_rep_evaluacion($com_id).'
            </div>
        <div class="jarviswidget jarviswidget-color-darken" >
          '.$this->seguimientopoa->lista_operaciones_programados($com_id,$this->verif_mes[1],$data['tabla']).'
        </div>
      </div>';

      $data['s1']=$s1;
      $data['update_eval']=$this->seguimientopoa->button_update_($com_id); /// para actualizar la Evaluacion POA

      $data['s2']='
      <div class="row" id="btn_generar">
        <center><button type="button" onclick="generar_cuadro_seguimiento_evalpoa('.$com_id.','.$this->verif_mes[1].','.$this->tmes.');" class="btn btn-default"><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="100" HEIGHT="100"/><br><b>GENERAR CUADRO DE SEGUIMIENTO Y EVALUACIÓN POA '.$this->model_evaluacion->trimestre()[0]['trm_descripcion'].' / '.$this->gestion.'</b></button></center>
      </div>

      <div id="loading_sepoa"></div>

      <div class="well" id="cuerpo_segpoa" style="display:none">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
            <div id="cabecera" style="display: none"></div>
            <hr>
            <table>
                <tr>
                    <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE SEGUIMIENTO POA AL MES DE '.$this->verif_mes[2].' / '.$this->gestion.'</b></td>
                </tr>
            </table>
            <hr>
                <div id="Seguimiento">
                    <div id="container" style="width: 700px; height: 400px; margin: 0 auto" align="center"></div>
                </div>
            <hr>
                <div class="table-responsive" id="tabla_componente_vista"></div>
                <div id="tabla_componente_impresion" style="display: none"></div>
            <hr>
            <div align="right">
                <button id="btnImprimir_seguimiento" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE SEGUIMIENTO MENSUAL POA</b></button>
            </div>
        </div>
      </div>';

      $data['s3']='
      <div class="well">
        <div id="loading_evalpoa"></div>

        <div class="row" id="cuerpo_evalpoa" style="display:none">
            <div class="well">
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <div id="cabecera2" style="display: none"></div>
                  <hr>
                  <table>
                      <tr>
                          <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE AVANCE EVALUACI&Oacute;N POA AL '.$this->model_evaluacion->trimestre()[0]['trm_descripcion'].' DE '.$this->gestion.'</b></td>
                      </tr>
                  </table>
                  <hr>
                  <div id="evaluacion_trimestre">
                      <div id="regresion" style="width: 600px; height: 390px; margin: 0 auto"></div>
                  </div>
                  <hr>
                  <div class="table-responsive" id="tabla_regresion_vista">
              
                  </div>
                  <div id="tabla_regresion_impresion" style="display: none">
                      
                  </div>
                  <hr>
                  <div align="right">
                      <button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="16" HEIGHT="16"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</b></button>
                  </div>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <div id="cabecera2" style="display: none"></div>
                  <hr>
                  <table>
                      <tr>
                          <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DETALLE EVALUACI&Oacute;N POA AL '.$this->model_evaluacion->trimestre()[0]['trm_descripcion'].' DE '.$this->gestion.'></b></td>
                      </tr>
                  </table>
                  <hr>
                  <div id="evaluacion_pastel">
                      <div id="pastel_todos" style="width: 600px; height: 420px; margin: 0 auto"></div>
                  </div>
                  <hr>
                  <div class="table-responsive" id="tabla_pastel_vista">
                     
                  </div>
                  <div id="tabla_pastel_impresion" style="display: none">
                    
                  </div>
                  <hr>
                  <div align="right">
                      <button id="btnImprimir_evaluacion_pastel" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="16" HEIGHT="16"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</b></button>
                  </div>
              </div>
          </div>
         </div>
      </div>';

      $data['s4']='
      <div class="well">
        <div id="loading_evalpoa2"></div>
        <div class="row" id="cuerpo_evalpoa2" style="display:none">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
              <div id="cabecera3" style="display: none"></div>
              <hr>
              <table>
                  <tr>
                      <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE EVALUACI&Oacute;N POA - GESTIÓN '.$this->gestion.'</b></td>
                  </tr>
              </table>
              <hr>
              <div id="evaluacion_gestion">
                <div id="regresion_gestion" style="width: 700px; height: 400px; margin: 0 auto"></div>
              </div>
              <hr>
              <div class="table-responsive" id="tabla_regresion_total_vista"></div>
              <div id="tabla_regresion_total_impresion" style="display: none"></div>
            <hr>
              <div align="right">
                  <button id="btnImprimir_evaluacion_gestion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="16" HEIGHT="16"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (GESTIÓN)</b></button>
              </div>
          </div>
        </div>
       </div>';

      $data['s5']='
      <div class="well">
        <div style="font-size: 13pt;font-family:Verdana;"><b>MIS ACTIVIDADES '.$componente[0]['serv_cod'].' '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].' - '.$this->gestion.'</b></div>
          <hr>
          <div class="row" id="list_form4_temporalidad"></div>
      </div>';
     
      $this->load->view('admin/evaluacion/seguimiento_poa/formulario_seguimiento', $data);
  }








  /*------ GET CUADRO DE SEGUIMIENTO POA MENSUAL-----*/
  public function get_cuadro_seguimientopoa(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $com_id = $this->security->xss_clean($post['com_id']);
      $mes_id = $this->security->xss_clean($post['mes_id']);
      $trm_id = $this->security->xss_clean($post['trm_id']);
      $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $matriz_temporalidad_subactividad=$this->seguimientopoa->temporalizacion_x_componente($com_id); /// matriz seguimiento
      $matriz_regresion=$this->seguimientopoa->tabla_regresion_lineal_servicio($com_id,$trm_id); /// matriz regresion
      $matriz_gestion=$this->seguimientopoa->tabla_regresion_lineal_servicio_total($com_id); /// Matriz para el grafico Total Gestion

      $result = array(
        'respuesta' => 'correcto',
        
        //// s2
        'matriz' => $matriz_temporalidad_subactividad,
        'cabecera1' => $this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($proyecto[0]['act_id']),$componente,1,$trm_id),
        'tabla_vista' => $this->seguimientopoa->tabla_temporalidad_componente($matriz_temporalidad_subactividad,1),
        'tabla_impresion' => $this->seguimientopoa->tabla_temporalidad_componente($matriz_temporalidad_subactividad,0),

        //// s3
        'matriz_regresion' => $matriz_regresion,
        'cabecera2' => $this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($proyecto[0]['act_id']),$componente,2,$trm_id),
        'tabla_regresion' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_regresion,$trm_id,2,1), /// Tabla que muestra el acumulado por trimestres Regresion Vista
        'tabla_regresion_impresion' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_regresion,$trm_id,2,0), /// Tabla que muestra el acumulado por trimestres Regresion Vista

        'tabla_pastel_todo' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_regresion,$trm_id,4,1), /// Tabla que muestra el acumulado por trimestres Pastel todo Vista
        'tabla_pastel_todo_impresion' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_regresion,$trm_id,4,0), /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

        /// s4
        'matriz_gestion' => $matriz_gestion,
        'cabecera3' => $this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($proyecto[0]['act_id']),$componente,3,$trm_id),
        'tabla_regresion_total' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_gestion,$trm_id,3,1), /// Tabla que muestra el acumulado Gestion Vista
        'tabla_regresion_total_impresion' => $this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_gestion,$trm_id,3,0), /// Tabla que muestra el acumulado Gestion Vista


        'form4_temporalidad' => $this->seguimientopoa->temporalidad_operacion($com_id),
      );
        
      echo json_encode($result);
    }else{
        show_404();
    }
  }


    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE --------*/
    public function update_evaluacion_trimestral(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']);
        $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        $this->seguimientopoa->update_evaluacion_operaciones($com_id);
        $tabla='';
        $tabla.='
              <hr><h3><b>&nbsp;&nbsp;'.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_cod'].' '.$componente[0]['serv_descripcion'].' '.$proyecto[0]['abrev'].'</b></h3><hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> EVALUACI&Oacute;N POA '.$trimestre[0]['trm_descripcion'].' '.$this->gestion.' ACTUALIZADO !!!</2> 
              </div>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


  /*-------- GET DATOS POA --------*/
  public function get_temporalidad(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $operacion=$this->model_producto->get_producto_id($prod_id); /// Actividad
      $tabla=$this->seguimientopoa->get_temporalidad_operacion($operacion);
      $calificacion_meta_mensual=$this->seguimientopoa->get_grado_cumplimiento_meta_mensual($operacion);

      $result = array(
        'respuesta' => 'correcto',
        'tabla'=>$tabla,
        'calificacion'=>$calificacion_meta_mensual,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


  

  /*---- VALIDA ADD SEGUIMIENTO POA ----*/
  public function guardar_seguimiento(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $ejec = $this->security->xss_clean($post['ejec']);
      $mv = $this->security->xss_clean($post['mv']);
      $obs = $this->security->xss_clean($post['obs']);
      $acc = $this->security->xss_clean($post['acc']);
      
      /// ----- Eliminando Registro --------
        $this->db->where('prod_id', $prod_id);
        $this->db->where('m_id', $this->verif_mes[1]);
        $this->db->where('g_id', $this->gestion);
        $this->db->delete('prod_ejecutado_mensual');
      /// -----------------------------------

      /// ----- Eliminando Registro --------
        $this->db->where('prod_id', $prod_id);
        $this->db->where('m_id', $this->verif_mes[1]);
        $this->db->where('g_id', $this->gestion);
        $this->db->delete('prod_no_ejecutado_mensual');
      /// -----------------------------------

        if($ejec!=0){
          $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$this->verif_mes[1],$ejec,$mv,$obs,$acc);
        }
        else{
          $no_ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($prod_id,$this->verif_mes[1]);
          if(count($no_ejec)!=0){
            if(($no_ejec[0]['medio_verificacion']!=$mv) || ($no_ejec[0]['observacion']!=$obs)){
              $this->model_producto->add_no_ejec_prod($prod_id,$this->verif_mes[1],$mv,$obs,$acc); 
            }
          }
          else{
            $this->model_producto->add_no_ejec_prod($prod_id,$this->verif_mes[1],$mv,$obs,$acc);  
          }
          
        }

        $producto=$this->model_producto->get_producto_id($prod_id);
        $diferencia=$this->seguimientopoa->verif_valor_no_ejecutado($prod_id,$this->verif_mes[1],$producto[0]['mt_id']);


        $result = array(
          'respuesta' => 'correcto',
          'prod_id' => $prod_id,
          'mes_id' => $this->verif_mes[1],
          'ejecucion' => round($ejec,2),
          'm_verificacion' => strtoupper($mv),
          'observacion' => strtoupper($obs),
          'acciones' => strtoupper($acc),
          'calif' => $this->seguimientopoa->calificacion_form4($prod_id,$diferencia),
        );

      echo json_encode($result);
    }else{
        show_404();
    }
  }




  /*------ ELIMINAR REGISTRO SEGUIMIENTO POA DEL MES ------*/
  function delete_seguimiento_operacion(){
    if ($this->input->is_ajax_request() && $this->input->post()) {
       $post = $this->input->post();
        $prod_id = $post['prod_id']; /// Producto Id
        $mes_id = $post['mes_id']; /// Mes Id
        $producto=$this->model_producto->get_producto_id($prod_id);


        /// Eliminar producto ejecutado en el mes
        $this->db->where('prod_id', $prod_id);
        $this->db->where('m_id', $mes_id);
        $this->db->delete('prod_ejecutado_mensual');

        /// Eliminar producto no ejecutado en el mes
        $this->db->where('prod_id', $prod_id);
        $this->db->where('m_id', $mes_id);
        $this->db->delete('prod_no_ejecutado_mensual');

        $this->seguimientopoa->update_evaluacion_operaciones($producto[0]['com_id']);

        $result = array(
          'respuesta' => 'correcto'
        );

        echo json_encode($result);
    }
  }


    /*----- REPORTE SEGUIMIENTO POA PDF 2021 MENSUAL POR SUBACTIVIDAD (MENSUAL)-------*/
    public function ver_reportesegpoa($com_id){
      
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      if(count($data['componente'])!=0){
        $data['mes'] = $this->seguimientopoa->mes_nombre();
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']); /// PROYECTO
        }
        $data['cabecera']=$this->seguimientopoa->cabecera($data['componente'],$data['proyecto']); /// Cabecera
        $data['verif_mes'] = $this->verif_mes;
        $data['titulo_formulario']='<b>FORMULARIO SEGUIMIENTO POA</b> - '.$this->verif_mes[2].' / '.$this->gestion.'';
        /// ----------------------------------------------------
        $tabla=$this->seguimientopoa->tabla_form_seguimientopoa_subactividad($com_id,$this->verif_mes[1]);
        /// -----------------------------------------------------

        $data['operaciones']=$tabla; /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }

    /*----- REPORTE EVALUACION POA PDF 2021 MENSUAL POR SUBACTIVIDAD (TRIMESTRAL)-------*/
    public function ver_reporteevalpoa($com_id,$trm_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      if(count($data['componente'])!=0){
       // $data['mes'] = $this->seguimientopoa->mes_nombre();
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
        $trimestre=$this->model_evaluacion->get_trimestre($trm_id); /// Datos del Trimestre
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']); /// PROYECTO
        }

        $data['cabecera']=$this->seguimientopoa->cabecera_evaluacion_trimestral($data['componente'],$data['proyecto'],$trm_id);
        $data['pie']=$this->seguimientopoa->pie_evaluacionpoa();
        /// ----------------------------------------------------
        if($this->tmes==$trm_id){
          $this->seguimientopoa->update_evaluacion_operaciones($com_id);
        }
        
        /// -----------------------------------------------------
        $data['evaluacion_form4']=$this->seguimientopoa->tabla_reporte_evaluacion_poa($com_id,$trm_id); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        //$data['ejecucion_ppto']=$this->seguimientopoa->ejecucion_presupuestaria_acumulado_total($com_id); /// Ejecucion ppto

        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_evaluacion_trimestral', $data);
      }
      else{
        echo "Error !!!";
      }
/*      $prod_id=65712;
      $trimestre=4;
        for ($i=1; $i <=4 ; $i++) { 
        $datos[$i]=0;
      }

      $mes_final=0;
      if($trimestre==1){$mes_final=3;}
      elseif ($trimestre==2) {$mes_final=6;}
      elseif ($trimestre==3) {$mes_final=9;}
      elseif ($trimestre==4) {$mes_final=12;}

      $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($trimestre,$prod_id); /// Trimestre Programado
      $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($trimestre,$prod_id); /// Trimestre Ejecutado

      $prog_trimestre=0; 
        if(count($trimestre_prog)!=0){
          $prog_trimestre=$trimestre_prog[0]['trimestre'];
        }
                
      $ejec_trimestre=0; 
        if(count($trimestre_ejec)!=0){
          $ejec_trimestre=$trimestre_ejec[0]['trimestre'];
        }


      $prog=$this->model_evaluacion->rango_programado_trimestral_productos($prod_id,$mes_final); /// meta programado al mes 
      $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($prod_id,$mes_final); /// meta ejecutado al mes

      $acu_prog=0;
      $acu_ejec=0;
      if(count($prog)!=0){
        $acu_prog=$prog[0]['trimestre'];
      }
      
      if(count($eval)!=0){
        $acu_ejec=$eval[0]['trimestre'];
      }

      ///------------------------------
      $datos[1]=$prog_trimestre; /// PROGRAMADO AL TRIMESTRE
      $datos[2]=$ejec_trimestre; /// EJECUTADO AL TRIMESTRE
      $datos[3]=($acu_prog-$acu_ejec); /// DIFERENCIA PROG-EJEC ACUMULADO


     // echo $datos[1].'--'.$datos[2].'--'.$datos[3];

      if($datos[3]==0){
        $datos[4]='TRIMESTRE CUMPLIDO';
      }
      elseif($acu_prog!=0 & $acu_ejec==0){
        $datos[4]='TRIMESTRE NO CUMPLIDO'; 
      }
      else{
        $datos[4]='TRIMESTRE EN PROCESO';
      }


      echo '<br>'.$datos[4];
*/

    }



    /*----- REPORTE SEGUIMIENTO POA PDF 2021 -------*/
    public function ver_reporteevalpoa_consolidado_temporalidad($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      if(count($data['componente'])!=0){
        $data['mes'] = $this->seguimientopoa->mes_nombre();
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']); /// PROYECTO
        }
        $data['cabecera']=$this->seguimientopoa->cabecera($data['componente'],$data['proyecto']); /// Cabecera
        $data['datos_mes'] = $this->verif_mes;

        /// ----------------------------------------------------
        $tabla=$this->seguimientopoa->tabla_reporte_consolidado_temporalidad($com_id);

        $data['operaciones']=$tabla; /// Reporte Gasto Corriente, Proyecto de Inversion 2020 (muestra la temporalidad)
        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa_temporalidad', $data);
      }
      else{
        echo "Error !!!";
      }
    }



    /*--- GET LISTA DE FORM4 MES (SEGUIMIENTO) ----*/
    public function get_form4_gc_mes(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dist_id = $this->security->xss_clean($post['dist_id']);

        $tabla=$this->seguimiento_form4_gc_mes($dist_id);
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- LISTA DE OPERACIONES A EJECUTAR EN EL MES ----*/
    public function seguimiento_form4_gc_mes($dist_id){
      if($this->dep_id==2){ /// Exclusivo La paz
        $unidades=$this->model_seguimientopoa->get_lista_unidad_operaciones_regional($this->dep_id,$this->verif_mes[1],$this->gestion);
      }
      else{
        $unidades=$this->model_seguimientopoa->get_lista_unidad_operaciones($dist_id,$this->verif_mes[1],$this->gestion);
      }

      $tabla='';
      $tabla.='
        <article>
          <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <header>
              <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
              <h2>ACTIVIDADES PROGRAMADAS MES '.$this->verif_mes[2].' - '.$this->gestion.'</h2>
            </header>
            <div>
              <div class="jarviswidget-editbox">
              </div>
              <div class="widget-body">
                <div class="panel-group smart-accordion-default" id="accordion">';
                $nro=0;
                  foreach ($unidades as $rowp) {
                    if($this->fun_id==592 || $this->fun_id==709){ /// Exclusivo la paz
                      $operaciones=$this->model_seguimientopoa->get_lista_operaciones_programados_regional($this->dep_id,$this->verif_mes[1],$this->gestion,$rowp['proy_id']);
                    }
                    else{
                      $operaciones=$this->model_seguimientopoa->get_lista_operaciones_programados($dist_id,$this->verif_mes[1],$this->gestion,$rowp['proy_id']);
                    }
                  
                  $nro++;
                  $tabla.='
                  <div class="panel panel-default">
                    <div class="panel-heading" align=left>
                      <h4 class="panel-title" title='.$rowp['proy_id'].'><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne'.$nro.'"> 
                        <img src="'.base_url().'assets/Iconos/arrow_down.png" WIDTH="25" HEIGHT="15"/>'.$rowp['tipo'].' '.$rowp['act_descripcion'].' '.$rowp['abrev'].'</a> ('.$rowp['operaciones'].') 
                        <a href="'.site_url("").'/seg/notificacion_operaciones_mensual/'.$rowp['proy_id'].'" target=_blank ><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="16" HEIGHT="16"/></a>
                      </h4>
                    </div>
                    <div id="collapseOne'.$nro.'" class="panel-collapse collapse">
                      <div class="panel-body">';
                        $tabla.='
                          <section class="col col-6" align=left>
                            <input id="searchTerm'.$nro.'" type="text" onkeyup="doSearch('.$nro.')" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
                          </section>
                            <table class="table table-bordered" border=1 style="width:100%;" id="datos'.$nro.'">
                                <thead>
                                  <tr align=center>
                                    <th style="width:1%;">#</th>
                                    <th style="width:10%;" align=center>UNIDAD ORGANIZACIONAL</th>
                                    <th style="width:10%;" align=center>UNIDAD RESPONSABLE</th>
                                    <th style="width:25%;" align=center>ACTIVIDAD</th>
                                    <th style="width:3%;" align=center>PROGRAMADO</th>
                                    <th style="width:3%;" align=center>EJECUTADO</th>
                                    <th style="width:1%;"></th>
                                  </tr>
                                </thead>
                                <tbody>';
                                $nro_ope=0;
                                foreach ($operaciones as $row) {
                                  $ejec=$this->model_producto->verif_ope_evaluado_mes($row['prod_id'],$this->verif_mes[1]);
                                  $evaluado=0;
                                  if(count($ejec)!=0){
                                    $evaluado=$ejec[0]['pejec_fis'];
                                  }
                                  $nro_ope++;
                                  $tabla.='
                                  <tr>
                                    <td align=center>'.$nro_ope.'</td>
                                    <td><b>'.$row['aper_actividad'].' '.$row['tipo'].' '.$row['act_descripcion'].'</b></td>
                                    <td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>
                                    <td bgcolor="#ddf8f5">'.$row['prod_cod'].' .- '.$row['prod_producto'].'</td>
                                    <td bgcolor="#ddf8f5">'.round($row['pg_fis'],2).'</td>
                                    <td bgcolor="#ddf8f5">'.round($evaluado,2).'</td>
                                    <td bgcolor="#ddf8f5">
                                      <a href="'.site_url("").'/seg/formulario_seguimiento_poa/'.$row['com_id'].'"  target="_blank" title="REALIZAR SEGUIMIENTO">
                                        <img src="'.base_url().'assets/Iconos/application_go.png" WIDTH="20" HEIGHT="20"/></b>
                                      </a>
                                    </td>
                                  </tr>';
                                }
                            $tabla.=
                          '</tbody>
                        </table>';
                      $tabla.='
                      </div>
                    </div>
                  </div>';
                  }
                $tabla.='
                </div>
              </div>
            </div>
          </div>
        </article>';
      return $tabla;
    }


    /*----- REPORTE CONSOLIDADO SEGUIMIENTO POA POR GASTO CORRIENTE 2021 -----*/
    public function reporte_consolidadopoa_operaciones_mensual($proy_id,$mes_id){
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
      if(count($data['proyecto'])!=0){
        $mes = $this->seguimientopoa->mes_nombre();
        $verif_mes=$this->seguimientopoa->update_mes_gestion($mes_id);
        $subactividades=$this->model_seguimientopoa->get_lista_subactividades_operaciones_programados($data['proyecto'][0]['dist_id'],$mes_id,$this->gestion,$proy_id);

      $tabla='';
        foreach ($subactividades as $row) {
          $componente = $this->model_componente->get_componente($row['com_id'],$this->gestion); ///// DATOS DEL COMPONENTE
          $tabla.='
          <page backtop="47mm" backbottom="35.5mm" backleft="5mm" backright="5mm" pagegroup="new">
          <page_header>
          <br><div class="verde"></div>
            <table class="page_header" border="0" style="width:100%;">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                            <tr style="border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td style="width:15%; text-align:center;">
                                
                              </td>
                              <td style="width:65%;" align=left>
                                '.$this->seguimientopoa->cabecera($componente,$data['proyecto']).'
                              </td>
                              <td style="width:15%;font-size: 8px;" align=left>
                              </td>
                            </tr>
                      </table>
                    </td>
                </tr>
            </table><br>
            <div align="center"><b>SEGUIMIENTO POA</b> - '.$verif_mes[2].' DE '.$this->gestion.' 
            </div><br>
          </page_header>
          <page_footer>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                                <td style="width:100%;height:12px;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                            </tr>
                            <tr>
                                <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:12px;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:12px;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br><br></td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: left">
                        POA - '.$this->gestion.' '.$this->resolucion.'
                    </td>
                    <td style="width: 33%; text-align: center">
                        '.$this->session->userdata('sistema').'  
                    </td>
                    <td style="width: 33%; text-align: right">
                        '.$mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
            </table>
        </page_footer>

        '.$this->seguimientopoa->tabla_form_seguimientopoa_subactividad($row['com_id'],$mes_id).'

        </page>';
        }
        $data['operaciones']=$tabla;
        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa_unidad', $data); 
      }
      else{
        echo "Error !!!";
      }
    }



     /*--- GET LISTA DE FORM5 P.I. MES (SEGUIMIENTO) ----*/
    public function get_form5_pi_mes(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dist_id = $this->security->xss_clean($post['dist_id']);

        $tabla=$this->seguimiento_form5_pi_mes($dist_id);
        //$tabla=$this->seguimiento_form4_gc_mes($dist_id);
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- LISTA DE REQUERIMIENTOS A EJECUTAR EN EL MES - PROYECTOS DE INVERSION ----*/
    public function seguimiento_form5_pi_mes($dist_id){
      $proyectos=$this->model_proyecto->list_proy_inversion_distrital($dist_id);
      $tabla='';
      $tabla.='
        <article>
          <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <header>
              <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
              <h2>PROYECTOS PROGRAMADAS MES '.$this->verif_mes[2].' - '.$this->gestion.'</h2>
            </header>
            <div>
              <div class="jarviswidget-editbox">
              </div>
              <div class="widget-body">
                <div class="panel-group smart-accordion-default" id="accordion">';
                $nro=0;
                  foreach ($proyectos as $rowp) {
                    $requerimientos=$this->model_notificacion->list_requerimiento_al_mes_unidad($rowp['proy_id'],$this->verif_mes[1]); /// items a ejecutar
                    if(count($requerimientos)!=0){
                        $nro++;
                        $tabla.='
                        <div class="panel panel-default">
                          <div class="panel-heading" align=left>
                            <h4 class="panel-title" title='.$rowp['proy_id'].'><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne'.$rowp['aper_id'].'"> 
                              <img src="'.base_url().'assets/Iconos/arrow_down.png" WIDTH="25" HEIGHT="15"/>'.$rowp['proy'].' - '.$rowp['proyecto'].'</a> 
                              <a href="'.site_url("").'/seg/notificacion_operaciones_mensual/'.$rowp['proy_id'].'" target=_blank ><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="16" HEIGHT="16"/></a>
                            </h4>
                          </div>
                          <div id="collapseOne'.$rowp['aper_id'].'" class="panel-collapse collapse">
                            <div class="panel-body">
                                <section class="col col-6" align=left>
                                  <input id="searchTerm'.$rowp['aper_id'].'" type="text" onkeyup="doSearch('.$rowp['aper_id'].')" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
                                </section>
                                <table class="table table-bordered" border=1 style="width:100%;" id="datos'.$rowp['aper_id'].'">
                                    <thead>
                                      <tr align=center>
                                        <th style="width:1%;">#</th>
                                        <th style="width:5%; text-align:center">COD. ACT.</th>
                                        <th style="width:5% text-align:center;">PARTIDA</th>
                                        <th style="width:30%; text-align:center">DETALLE REQUERIMIENTO</th>
                                        <th style="width:10%; text-align:center">UNIDAD DE MEDIDA</th>
                                        <th style="width:5%; text-align:center">CANTIDAD</th>
                                        <th style="width:10%; text-align:center">PRECIO UNITARIO</th>
                                        <th style="width:10%; text-align:center">PRECIO TOTAL</th>
                                        <th style="width:10%; text-align:center">PROG. MES <br>'.$this->verif_mes[2].'</th>
                                        <th style="width:15%; text-align:center">OBSERVACION</th>
                                      </tr>
                                    </thead>
                                    <tbody>';
                                    $nro_req=0;
                                      foreach ($requerimientos as $row) {
                                        $nro_req++;
                                        $tabla.= '
                                        <tr>
                                          <td align=center style="height:10px; width:1%;">'.$nro_req.'</td>
                                          <td align=center style="font-size: 13px; width:5%;"><b>'.$row['prod_cod'].'</b></td>
                                          <td align=center style="font-size: 13px; width:5%;"><b>'.$row['par_codigo'].'</b></td>
                                          <td style="width:30%;">'.$row['ins_detalle'].'</td>
                                          <td style="width:10%;">'.$row['ins_unidad_medida'].'</td>
                                          <td style="width:5%;" align=right>'.round($row['ins_cant_requerida'],2).'</td>
                                          <td style="width:10%;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                                          <td style="width:10%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                                          <td style="width:10%;" align=right><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
                                          <td style="width:15%;" align=left>'.$row['ins_observacion'].'</td>
                                        </tr>';
                                      }
                                  $tabla.=
                                  '</tbody>
                                </table>
                            </div>
                          </div>
                        </div>';
                    }
                  
                  }
                $tabla.='
                </div>
              </div>
            </div>
          </div>
        </article>';
      return $tabla;
    }

  /*----- REPORTE NOTIFICACION POA MENSUAL POR GASTO CORRIENTE 2021-2022-2023 POR UNIDAD -----*/
  public function reporte_notificacion_operaciones_mensual($proy_id){
    $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
    if(count($data['proyecto'])!=0){
      //$unidades_responsables=$this->model_seguimientopoa->get_lista_subactividades_operaciones_programados($data['proyecto'][0]['dist_id'],$this->verif_mes[1],$this->gestion,$proy_id);
      $unidades_responsables=$this->model_componente->lista_subactividad($proy_id);
      $data['verif_mes']=$this->verif_mes;
      $data['principal']='';
      if($data['proyecto'][0]['tp_id']==4){
        $data['principal']=$this->seguimientopoa->cuerpo_nota_notificacion($proy_id); /// Cuerpo Nota Principal
      }
      $data['cuerpo']=$this->seguimientopoa->lista_subactividades_a_notificar($unidades_responsables); /// listado de unidades a notificar
      //echo $data['proyecto'][0]['dist_id'];
      $this->load->view('admin/evaluacion/seguimiento_poa/reporte_notificacion_seguimiento', $data); 
    }
    else{
      echo "Error !!!";
    }
  }

  
  /*----- REPORTE NOTIFICACION POA MENSUAL POR GASTO CORRIENTE POR COMPONENTE -----*/
  public function reporte_notificacion_poa_mensual_componente($com_id){
    $componente=$this->model_componente->get_componente($com_id,$this->gestion);
    if(count($componente)!=0){
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $data['proyecto']=$this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
      $data['verif_mes']=$this->verif_mes;
      $data['principal']='';
      $data['cuerpo']=$this->seguimientopoa->get_notificacion_subactividad($com_id); /// unidad operativa

      $this->load->view('admin/evaluacion/seguimiento_poa/reporte_notificacion_seguimiento', $data); 
    }
    else{
      echo "Error !!!";
    }
  }


  /*------ GET CAMBIA MES ACTIVO -----*/
  public function get_update_mes(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $mes_id = $this->security->xss_clean($post['mes_id']);
  //    $mes=$this->verif_mes_gestion($mes_id);

      $data = array(
        'mes_actual'=>$this->seguimientopoa->update_mes_gestion($mes_id)
      );
      $this->session->set_userdata($data);

      $result = array(
        'respuesta' => 'correcto',
      );
        
      echo json_encode($result);
    }else{
        show_404();
    }
  }



    /*------ Formulario Unidad Responsable -----*/
    public function formulario_subactividad(){
      $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $data['menu'] = $this->seguimientopoa->menu_segpoa($this->com_id,1);
      $data['componente']=$this->model_componente->get_componente($this->com_id,$this->gestion);

      if(count($data['componente'])!=0){
        $data['com_id']=$this->com_id;
        $this->seguimientopoa->update_evaluacion_operaciones($this->com_id); /// Update datos de Evaluacion
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['componente'][0]['proy_id']); /// PROYECTO
        $data['titulo']=
        '
        <h1 title='.$data['proyecto'][0]['aper_id'].'><small>'.$data['proyecto'][0]['tipo_adm'].' : </small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].' - '.$data['proyecto'][0]['abrev'].'</h1>
        <h1><small>UNIDAD RESPONSABLE : </small> '.$data['componente'][0]['serv_cod'].' '.$data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion'].'</h1>
        <h1><small>FORMULARIO DE SEGUIMIENTO POA : </small> <b>'.$this->verif_mes[2].' / '.$this->gestion.'</b></h1>';
      
        //$data['cabecera1']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],1,$this->tmes);

        /*$data['matriz_temporalidad_subactividad']=$this->seguimientopoa->temporalizacion_x_componente($this->com_id); /// grafico
        $data['tabla_temporalidad_componente']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],1); /// Vista 
        $data['tabla_temporalidad_componente_impresion']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],0); /// Impresion */

        $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($this->com_id,$this->tmes); /// Tabla para el grafico al trimestre

        $data['calificacion']='<hr>
        <div id="calificacion" style="font-family: Arial;font-size: 10%;">'.$this->seguimientopoa->calificacion_eficacia($data['tabla'][5][$this->tmes],0).'</div></fieldset>';
        $data['formularios_seguimiento']=$this->seguimientopoa->formularios_mensual($this->com_id);
        $data['salir']='<a href="'.site_url("").'/dashboar_seguimiento_poa" title="SALIR" class="btn btn-default">
                          <img src="'.base_url().'assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="19"/>&nbsp; SALIR
                        </a>';

        $data['operaciones_programados']=$this->seguimientopoa->lista_operaciones_programados($this->com_id,$this->verif_mes[1],$data['tabla']); /// Lista de Operaciones programados en el mes
        $data['boton_reporte_seguimiento_poa']=$this->seguimientopoa->button_rep_seguimientopoa($this->com_id); /// Reporte Seguimiento (Mes vigente) POA
        $data['update_eval']=$this->seguimientopoa->button_update_sa($this->com_id);
        $this->load->view('admin/evaluacion/seguimiento_poa_subactividad/formulario_seguimiento_subact', $data);
      }
      else{
        $this->session->sess_destroy();
        redirect('/','refresh');
      }

    }


    /*----- REPORTE SEGUIMIENTO POA PDF 2021 MENSUAL POR SUBACTIVIDAD POR MES-------*/
    public function reporte_formulario_subactividad_mes($com_id,$mes_id){
      
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
      if(count($data['componente'])!=0){
        $data['verif_mes']=$this->seguimientopoa->update_mes_gestion($mes_id);
        $data['mes'] = $this->seguimientopoa->mes_nombre();
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']); /// PROYECTO
        }
        $data['cabecera']=$this->seguimientopoa->cabecera($data['componente'],$data['proyecto']); /// Cabecera
        $data['titulo_formulario']='<b>FORMULARIO SEGUIMIENTO POA</b> - '.$data['verif_mes'][2].' / '.$this->gestion.'';

        /// ----------------------------------------------------
        $tabla=$this->seguimientopoa->tabla_form_seguimientopoa_subactividad($com_id,$mes_id);
        /// -----------------------------------------------------

        $data['operaciones']=$tabla; /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        
        //echo $data['operaciones'];

        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }



    /*--- VERIFICANDO EL VALOR A EJECUTAR POR FORMULARIO N 4 ---*/
    function verif_valor_ejecutado_x_form4(){
      if($this->input->is_ajax_request()){
        /// tp 0 : registro
        /// tp 1 : modificacion

        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
        $tp = $this->security->xss_clean($post['tp']); /// tp
        $ejec= $this->security->xss_clean($post['ejec']);/// prod id
        $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
        $producto=$this->model_producto->get_producto_id($prod_id); /// datos del formulario N° 4

        if($producto[0]['indi_id']==2 & $producto[0]['mt_id']==1){ //// INDICADOR RECURRENTE
          $programado=$this->model_producto->get_mes_programado_form4($prod_id,$mes_id); /// Programado del mes

            if($ejec<=$programado[0]['pg_fis']){
              echo "true";
            }
            else{
              echo "false";
            }
        }
        else{ /// INDICADOR ABSOLUTO, RELATIVO
            $valor_ejecutado=0;
            $programado=$this->model_seguimientopoa->rango_programado_trimestral_productos($prod_id,$this->tmes); /// Programado
            $ejecutado=$this->model_seguimientopoa->rango_ejecutado_trimestral_productos($prod_id,$this->tmes); /// Ejecutado

            if(count($programado)!=0){
              if(count($ejecutado)!=0){
                $valor_ejecutado=$ejecutado[0]['trimestre'];
              }

                if($tp==0){ /// registro
                  if($valor_ejecutado<$programado[0]['trimestre']){
                    if(($ejec+$valor_ejecutado)<=$programado[0]['trimestre'] ){
                      echo 'true';
                    }
                    else{
                      echo 'false';
                    }
                    
                  }
                  else{
                    echo 'false';
                  }
                }
                else{ /// modificacion
                  $valor_ejec=$this->model_producto->verif_ope_evaluado_mes($prod_id,$mes_id);
                  if(count($valor_ejec)==0){ /// no existe valor registrado
                    echo "true";
                  }
                  else{
                    $valor_registrado=$valor_ejec[0]['pejec_fis'];
                    $valor_ejecutado=$valor_ejecutado-$valor_registrado;

                    if(($ejec+$valor_ejecutado)<=$programado[0]['trimestre'] ){
                      echo 'true';
                    }
                    else{
                      echo 'false';
                    }
                  }
                }

            }
            else{
              echo 'false';
            }
        }

      }else{
        show_404();
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
}