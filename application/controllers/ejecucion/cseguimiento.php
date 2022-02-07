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
    }


    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_gasto_corriente($proy_estado){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      $meses = $this->model_configuracion->get_mes();

      $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
      $tabla='';
      
      $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
        <thead>
          <tr style="height:35px;">
            <th style="width:1%;" bgcolor="#474544">#</th>
            <th style="width:5%;" bgcolor="#474544">EVALUACI&Oacute;N POA<br>'.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</th>
            <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR">'.$this->verif_mes[1].'</th>
            <th style="width:10%;" bgcolor="#474544" title="SELECCIONAR REPORTE SEGUIMIENTO">REPORTE SEGUIMIENTO </th>
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
                <td align=center>';
                  if($this->tp_adm==1){
                    $tabla.='<a href="#" data-toggle="modal" data-target="#modal_update_eval_unidad" class="btn btn-info update_eval_unidad" style="width:95%;" name="'.$row['proy_id'].'" id="'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'" title="ACTUALIZAR EVALUACION POA"><i class="glyphicon glyphicon-retweet"></i> ACTUALIZAR</a></a>';
                  }
                $tabla.='
                </td>
                <td align=center>
                  <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'">
                  <i class="glyphicon glyphicon-list"></i> MIS UNIDADES RESPONSABLES</a>
                </td>
                <td align=center>
                  <div class="btn-group">
                    <a class="btn btn-default">FORMULARIO</a>
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
                <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
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
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="MIS UNIDADES RESPONSABLES"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:25%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:15%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                <tr style="height:35px;">
                  <td title='.$row['proy_id'].'><center>'.$nro.'</center></td>
                  <td align=center>';
                  if($row['pfec_estado']==1){
                    $tabla.='<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary enlace" name="'.$row['proy_id'].'" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                    <i class="glyphicon glyphicon-list"></i> MIS UNIDADES RESPONABLES</a>';
                  }
                  else{
                    $tabla.='SIN FASE ACTIVA';
                  }
                  $tabla.='
                  </td>
                <td><center>'.$row['aper_programa'].' '.$row['proy_sisin'].' '.$row['aper_actividad'].'</center></td>
                <td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla.='<td>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
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

        $tabla=$this->mis_subactividades($proy_id);
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

    /*------ GET SUBACTIVIDADES 2021 -----*/
    public function mis_subactividades($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $titulo='UNIDAD RESPONSABLE';
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
                  <td>
                    <a href="'.site_url("").'/seg/formulario_seguimiento_poa/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-primary" title="REALIZAR SEGUIMIENTO">
                      '.$this->btn_seguimiento_evaluacion_poa().'
                    </a>
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



  /*------ EVALUAR OPERACION (Gasto Corriente-Proyecto de Inversion) 2021 ------*/
  public function formulario_segpoa($com_id){
    $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
    $data['menu'] = $this->seguimientopoa->menu(4);
    $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
    $data['com_id']=$com_id;

    if(count($data['componente'])!=0){

      $data['datos_mes'] = $this->verif_mes;
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
      $titulo=
      '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>'.$data['proyecto'][0]['tipo_adm'].' : </small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].' - '.$data['proyecto'][0]['abrev'].'</h1>
      <h1><small>UNIDAD RESPONSABLE : </small> '.$data['componente'][0]['serv_cod'].' '.$data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion'].'</h1>
      <h1><small>TRIMESTRE VIGENTE : </small> '.$data['tmes'][0]['trm_descripcion'].'</h1>';

      if($data['proyecto'][0]['tp_id']==1){
        $titulo=
        '<h1 title='.$data['proyecto'][0]['aper_id'].'><small>PROYECTO : </small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].' - '.$data['proyecto'][0]['abrev'].'</h1>
        <h1><small>UNIDAD. RESP. : </small> '.$data['componente'][0]['serv_descripcion'].'</h1>
        <h1><small>TRIMESTRE VIGENTE : </small> '.$data['tmes'][0]['trm_descripcion'].'</h1>';
      }
      
      $data['cabecera1']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],1,$this->tmes);
      $data['seguimiento_operacion']=$this->seguimientopoa->temporalidad_operacion($com_id); /// temporalidad Programado-Ejecutado Subactividad
      $matriz_temporalidad_subactividad=$this->seguimientopoa->temporalizacion_x_componente($com_id); /// grafico
      $data['titulo']=$titulo; /// Titulo de la cabecera
      
      $data['matriz_temporalidad_subactividad']=$matriz_temporalidad_subactividad;
      $data['tabla_temporalidad_componente']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],1); /// Vista 
      $data['tabla_temporalidad_componente_impresion']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],0); /// Impresion 


      /*--- Regresion lineal trimestral ---*/
      $data['cabecera2']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],2,$this->tmes);
      $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($com_id,$this->tmes); /// Tabla para el grafico al trimestre
      $data['tabla_regresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$this->tmes,2,1); /// Tabla que muestra el acumulado por trimestres Regresion Vista
      $data['tabla_regresion_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$this->tmes,2,0); /// Tabla que muestra el acumulado por trimestres Regresion Impresion

      /*--- grafico Pastel trimestral ---*/
      $data['tabla_pastel_todo']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$this->tmes,4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo Vista
      $data['tabla_pastel_todo_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],$this->tmes,4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

      /*--- Regresion lineal Gestion */
      $data['cabecera3']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],3,$this->tmes);
      $data['tabla_gestion']=$this->seguimientopoa->tabla_regresion_lineal_servicio_total($com_id); /// Matriz para el grafico Total Gestion
      $data['tabla_regresion_total']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],$this->tmes,3,1); /// Tabla que muestra el acumulado Gestion Vista
      $data['tabla_regresion_total_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],$this->tmes,3,0); /// Tabla que muestra el acumulado Gestion Impresion

      $data['update_eval']=$this->seguimientopoa->button_update_($com_id);
      $data['boton_reporte_seguimiento_poa']=$this->seguimientopoa->button_rep_seguimientopoa($com_id); /// Reporte Seguimiento (Mes vigente) POA
      $data['boton_reporte_evaluacion_poa']=$this->seguimientopoa->button_rep_evaluacion($com_id); /// Reporte Evaluacion (Trimestre vigente) POA

    //  $this->seguimientopoa->update_evaluacion_operaciones($com_id);
      $data['operaciones_programados']=$this->seguimientopoa->lista_operaciones_programados($com_id,$this->verif_mes[1],$data['tabla']); /// Lista de Operaciones programados en el mes
      $data['formularios_poa']=$this->seguimientopoa->formularios_poa($com_id,$data['proyecto'][0]['proy_id']);
      $data['formularios_seguimiento']=$this->seguimientopoa->formularios_mensual($com_id);
      
   //  $temporalidad=$this->seguimientopoa->obtiene_suma_temporalidad_prog_ejec(47852);
   //  echo $temporalidad[1].' - '.$temporalidad[2].' - '.$temporalidad[3].' - '.$temporalidad[4];
  //echo $data['cabecera2'];
      $this->load->view('admin/evaluacion/seguimiento_poa/formulario_seguimiento', $data);
    }
    else{
      echo "Error !!!";
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

        $result = array(
          'respuesta' => 'correcto',
        );
  
        echo json_encode($result);
      }else{
          show_404();
      }
    }




    /*------ ELIMINAR SEGUIMIENTO POA DEL MES ------*/
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
        $data['operaciones']=$this->seguimientopoa->tabla_reporte_evaluacion_poa($com_id,$trm_id); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        $data['ejecucion_ppto']=$this->seguimientopoa->ejecucion_presupuestaria_acumulado_total($com_id); /// Ejecucion ppto

        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_evaluacion_trimestral', $data);
      }
      else{
        echo "Error !!!";
      }
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



    /*--- GET LISTA DE OPERACIONES MES (SEGUIMIENTO) ----*/
    public function get_operaciones_mes(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dist_id = $this->security->xss_clean($post['dist_id']);

        $tabla=$this->seguimiento_operaciones_mes($dist_id);
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
    public function seguimiento_operaciones_mes($dist_id){
      if($this->fun_id==592 || $this->fun_id==709){ /// Exclusivo La paz
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
                                <img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" style="width:50%;">
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
                    <td colspan="3"><br><br></td>
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


    /*----- REPORTE NOTIFICACION POA MENSUAL POR GASTO CORRIENTE 2021 -----*/
    public function reporte_notificacion_operaciones_mensual($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
      if(count($data['proyecto'])!=0){
        $subactividades=$this->model_seguimientopoa->get_lista_subactividades_operaciones_programados($data['proyecto'][0]['dist_id'],$this->verif_mes[1],$this->gestion,$proy_id);
        $data['verif_mes']=$this->verif_mes;
        $data['principal']=$this->seguimientopoa->cuerpo_nota_notificacion($proy_id); /// Cuerpo Nota Principal
        $data['cuerpo']=$this->seguimientopoa->lista_subactividades_a_notificar($subactividades);

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



    /*------ Formulario Subactividad-----*/
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
      
        $data['cabecera1']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],1,$this->tmes);
       // $data['seguimiento_operacion']=$this->seguimientopoa->temporalidad_operacion($this->com_id); /// temporalidad Programado-Ejecutado Subactividad
        
        $data['matriz_temporalidad_subactividad']=$this->seguimientopoa->temporalizacion_x_componente($this->com_id); /// grafico
        $data['tabla_temporalidad_componente']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],1); /// Vista 
        $data['tabla_temporalidad_componente_impresion']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],0); /// Impresion 

        $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($this->com_id,$this->tmes); /// Tabla para el grafico al trimestre

        /*--- Regresion lineal trimestral ---*/
        /*$data['cabecera2']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],2);
        
        $data['tabla_regresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion Vista
        $data['tabla_regresion_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion Impresion*/
        
        /*--- grafico Pastel trimestral ---*/
        /*$data['tabla_pastel_todo']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo Vista
        $data['tabla_pastel_todo_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion*/

        /*--- Regresion lineal Gestion */
       /* $data['cabecera3']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],3);
        $data['tabla_gestion']=$this->seguimientopoa->tabla_regresion_lineal_servicio_total($this->com_id); /// Matriz para el grafico Total Gestion
        $data['tabla_regresion_total']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion Vista
        $data['tabla_regresion_total_impresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion
        */

       // $data['formularios_poa']=$this->seguimientopoa->formularios_poa($this->com_id,$data['componente'][0]['proy_id']);
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
        
      //  echo $data['operaciones'];

        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }
    
}