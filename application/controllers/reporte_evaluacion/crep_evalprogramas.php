<?php
class Crep_evalprogramas extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('evaluacionpoa_programas');
        }
        else{
            redirect('/','refresh');
        }
    }

    /// MENU EVALUACIÓN PROGRAMAS 
    public function menu_eval_programas(){
      if($this->gestion>2019){
        $data['menu']=$this->menu(7); //// genera menu
        $data['regional']=$this->regionales();
        $this->load->view('admin/reportes_cns/repevaluacion_programas/rep_menu', $data);
      }
      else{
        redirect('regionales'); // Rideccionando a Evaluacion anterior 2019
      }
    }


    //// LISTA DE REGIONALES
    public function regionales(){
      $regiones=$this->model_evalinstitucional->regiones();
      $nro=0;
      $tabla ='';
      $tabla.='
          <article class="col-sm-12 col-md-12 col-lg-2">
            <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
              <header>
                  <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                  <h2>Accordions </h2>
              </header>
              <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">

                      <div class="panel-group smart-accordion-default" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></a></h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body no-padding"><br>
                                      <table class="table table-bordered table-condensed">
                                          <tbody>
                                              <tr>
                                                  <td style="font-size: 10pt;">INSTITUCIONAL</td>
                                                  <td align=center><a href="#" class="btn btn-info enlace" name="0" id="2">VER</a></td>
                                              </tr>
                                          </tbody>
                                      </table><br>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>

            <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
              <header>
                <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                <h2><b>EVALUACI&Oacute;N POA '.$this->gestion.'</b></h2>
              </header>
              <div>

                <div class="widget-body no-padding">
                  <div class="panel-group smart-accordion-default" id="accordion-2">';
                  foreach($regiones as $rowd){
                    $tabla.='
                    <div class="panel panel-default">
                      <div class="panel-heading">';
                      if($rowd['dep_id']!=10){
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> REGIONAL '.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }
                      else{
                        $tabla.='<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapse'.$rowd['dep_id'].'" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>'.strtoupper($rowd['dep_departamento']).'</a></h4>';
                      }
                      $tabla.='
                      </div>
                      <div id="collapse'.$rowd['dep_id'].'" class="panel-collapse collapse">
                        <div class="panel-body">'.$this->list_distrital($rowd['dep_id']).'</div>
                      </div>
                    </div>';
                  }
                $tabla.='
                  </div>
      
                </div>
              </div>
            </div>
          </article>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <div id="content1"></div>
          </article>';
      return $tabla;
    }


    /* ---- Lista de Distritales ---*/
    public function list_distrital($dep_id){
      $tabla='';
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $distritales=$this->model_evalinstitucional->get_distritales($dep_id);

      $nro=1;
      $tabla.='<hr><table class="table table-bordered">
        <tr>
          <td>'.$nro.'</td>
          <td><b>CONSOLIDADO - '.strtoupper($departamento[0]['dep_departamento']).'</b></td>
          <td align=center><a href="#" class="btn btn-info enlace" name="'.$departamento[0]['dep_id'].'" id="0">VER</a></td>
          </tr>';
          if(count($distritales)>1){
            foreach($distritales as $row){
              $nro++;
              $tabla.='
              <tr>
                <td>'.$nro.'</td>
                <td>'.strtoupper($row['dist_distrital']).'</td>
                <td align=center><a href="#" class="btn btn-info enlace" name="'.$row['dist_id'].'" id="1">VER</a></td>
              </tr>';
            }
          }

      $tabla.='</table>';
      return $tabla;
    }


    /*-------- GET CUADRO EVALUACION POR PROGRAMAS --------*/
    public function get_cuadro_evaluacion_programas(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $id = $this->security->xss_clean($post['id']); // dep id, dist id , 0: Nacional
        $tp = $this->security->xss_clean($post['tp']); // 0 : Consolidado Regional, 1: distrital, 2 : Nacional

        $tabla='<iframe id="ipdf" width="100%" height="1000px;" src="'.base_url().'index.php/rep_eval_prog/evaluacion_programas/'.$id.'/'.$tp.'"></iframe>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }

    //// EVALUACIÓN POA - REGIONAL -DISTRITAL  - IFRAME
    public function evaluacion_programas($id,$tp){
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      
      if($tp==0){ //// CONSOLIDADO REGIONAL
        $dep_id=$id;
        $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($dep_id,4);
        $data['matriz_programas']=$this->evaluacionpoa_programas->matriz_programas_regional($lista_programas);
        $data['boton_reporte']=$this->evaluacionpoa_programas->button_rep_catprogramatica($dep_id,$tp);

        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO POR CATEGORIA PROGRAMÁTICA '.$this->gestion.'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].'</b></h2>
        <h2><b>CONSOLIDADO REGIONAL : '.strtoupper($data['departamento'][0]['dep_departamento']).'</b></h2>';

        $titulo_impresion='
          <tr style="font-size: 15pt;">
            <td style="width:100%;" align=center><b>CONSOLIDADO '.strtoupper($data['departamento'][0]['dep_departamento']).'</b></td>
          </tr>';
      }
      elseif($tp==1){ //// CONSOLIDADO DISTRITAL
        $dist_id=$id;
        $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($dist_id,4);
        $data['matriz_programas']=$this->evaluacionpoa_programas->matriz_programas_distrital($lista_programas);
        $data['boton_reporte']=$this->evaluacionpoa_programas->button_rep_catprogramatica($dist_id,$tp);

        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO POR CATEGORIA PROGRAMÁTICA '.$this->gestion.'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].'</b></h2>
        <h2><b>'.strtoupper($data['distrital'][0]['dist_distrital']).'</b></h2>';

        $titulo_impresion='
          <tr style="font-size: 15pt;">
            <td style="width:100%;" align=center><b>REGIONAL '.strtoupper($data['distrital'][0]['dep_departamento']).'</b></td>
          </tr>
          <tr style="font-size: 12pt;">
            <td style="width:100%;" align=center>'.strtoupper($data['distrital'][0]['dist_distrital']).'</td>
          </tr>';
      }
      else{ /// NACIONAL tp:2
        $data['titulo']=
        '<h1><b>EVALUACI&Oacute;N POA - CONSOLIDADO POR CATEGORIA PROGRAMÁTICA '.$this->gestion.'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['trimestre'][0]['trm_descripcion'].'</b></h2>
        <h2><b>CONSOLIDADO INSTITUCIONAL - CAJA NACIONAL DE SALUD</b></h2>';

        $titulo_impresion='
          <tr style="font-size: 15pt;">
            <td style="width:100%;" align=center><b>CONSOLIDADO INSTITUCIONAL</b></td>
          </tr>';

        $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional(4);
        $data['matriz_programas']=$this->evaluacionpoa_programas->matriz_programas_institucional($lista_programas);
        $data['boton_reporte']=$this->evaluacionpoa_programas->button_rep_catprogramatica(0,$tp);
      }

    
      $data['id']=$id;
      $data['tp']=$tp;

      $data['calificacion']=$this->evaluacionpoa_programas->calificacion_eficacia($data['matriz_programas'][(count($lista_programas)+1)][8],0); /// Parametros de Eficacia
      $data['tabla_programa']=$this->evaluacionpoa_programas->tabla_apertura_programatica($data['matriz_programas'],count($lista_programas),1);
      $data['matriz']=$this->evaluacionpoa_programas->matriz_parametros($data['matriz_programas'],count($lista_programas));
      $data['tabla_parametros']=$this->evaluacionpoa_programas->parametros_eficacia($data['matriz'],1);

   
      $this->load->view('admin/reportes_cns/repevaluacion_programas/reporte_grafico_eval_consolidado_regional_distrital', $data);

    }


    
    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_categoria_programatica($id,$tp){
      if($tp==0){
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($id,4);
        $matriz_programas=$this->evaluacionpoa_programas->matriz_programas_regional($lista_programas);
      }
      elseif($tp==1) {
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($id,4);
        $matriz_programas=$this->evaluacionpoa_programas->matriz_programas_distrital($lista_programas);
      }
      else{
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional(4);
        $matriz_programas=$this->evaluacionpoa_programas->matriz_programas_institucional($lista_programas);
      }


      $data['cabecera']=$this->evaluacionpoa_programas->cabecera_evaluacion_trimestral($id,$tp);
      $data['pie']=$this->evaluacionpoa_programas->pie_evaluacionpoa();
      $data['operaciones']=$this->evaluacionpoa_programas->tabla_apertura_programatica_reporte($matriz_programas,count($lista_programas));
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
      $this->load->view('admin/reportes_cns/repevaluacion_programas/reporte_evaluacion_consolidadoprogramas', $data);
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

    /*
    /*================================= GENERAR MENU ====================================*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
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
    /*--------------------------------------------------------------------------------*/
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
    /*======================================================================================*/

}