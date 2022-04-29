<?php
/*controlador para evaluacion ACP GESTION 2022*/
class Crep_evalform2 extends CI_Controller {  
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
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->load->model('mestrategico/model_objetivogestion');
            $this->load->model('mestrategico/model_objetivoregion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->trimestre = $this->model_evaluacion->trimestre();
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
          //  $this->load->library('evaluacionacp');
        }
        else{
            redirect('/','refresh');
        }
    }

  /// MENU EVALUACIÓN POA 
  public function menu_eval_form2(){
    $data['menu']=$this->menu(7); //// genera menu
    $data['titulo']=' <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <section id="widget-grid" class="well">
                            <div class="">
                                <h2>EVALUACION CONSOLIDADO DE OPERACIONES '.$this->gestion.'</h2>
                            </div>
                        </section>
                      </article>';
    $data['matriz']=$this->matriz_eval_form2();


/*    for ($i=1; $i <=count($this->model_proyecto->list_departamentos()) ; $i++) { 
      for ($j=1; $j <=6 ; $j++) { 
        echo "[".$matriz[$i][$j]."]";
      }
      echo "<br>";
    }*/

/*      $data['regional']=$this->evaluacionacp->listado_regionales();
      $data['da']=$this->model_proyecto->list_departamentos();
      $tabla='';
      $tabla.='<div class="well">
                <div class="jumbotron">
                  <h1>Evaluaci&oacute;n A.C.P. '.$this->gestion.'</h1>
                    <p>
                      Reporte consolidado de evaluaci&oacute;n de Acciones de Corto Plazo acumulado al '.$this->trimestre[0]['trm_descripcion'].' de '.$this->gestion.' a nivel Institucional, Regional.
                    </p>
                </div>
              </div>';

      $data['titulo_modulo']=$tabla;*/

      $this->load->view('admin/reportes_cns/repevaluacion_form2/rep_form2', $data);
    }

          /// MENU EVALUACIÓN POA 
    public function matriz_eval_form2(){
      $regionales=$this->model_proyecto->list_departamentos();
      $nro=0;
      foreach($regionales as $row){
        $calificacion=$this->calificacion_total_form2_regional($row['dep_id']);
        $nro++;
        $mat[$nro][1]=$row['dep_id'];
        $mat[$nro][2]=strtoupper($row['dep_departamento']);
        $mat[$nro][3]=$calificacion[1]; /// programado trimestral
        $mat[$nro][4]=$calificacion[2]; /// ejecutado trimestral
        $mat[$nro][5]=$calificacion[3]; /// total programado Gestion
        $mat[$nro][6]=$calificacion[4]; /// % cumplimiento
      }

      return $mat;
    }




    /*--- PARAMETRO DE CALIFICACION OPERACIONES REGIONAL ---*/
    public function calificacion_total_form2_regional($dep_id){
      $prog_trimestre=0; $ejec_trimestre=0;$prog_total_form2=0;

      $prog_total=$this->model_objetivoregion->get_suma_total_prog_form2_regional($dep_id);
      if(count($prog_total)!=0){
        $prog_total_form2=$prog_total[0]['programado_total'];
      }

      for ($i=1; $i <=$this->tmes; $i++) { 
        $prog=$this->model_objetivoregion->get_suma_trimestre_prog_form2_regional($dep_id,$i);
        if(count($prog)!=0){
          $prog_trimestre=$prog_trimestre+$prog[0]['prog'];
        }

        $ejec=$this->model_objetivoregion->get_suma_trimestre_ejec_form2_regional($dep_id,$i);
        if(count($ejec)!=0){
          $ejec_trimestre=$ejec_trimestre+$ejec[0]['ejec'];
        }
      }

      $calif[1]=$prog_trimestre; /// programado trimestral
      $calif[2]=$ejec_trimestre; /// ejecutado trimestral
      $calif[3]=$prog_total_form2; /// total programado Gestion
      $calif[4]=0;

      if($prog_total_form2!=0){
        $calif[4]=round((($calif[2]/$prog_total_form2)*100),2);
      }

      return $calif;
    }



    /*-------- GET CUADRO EVALUACION A.CP.--------*/
/*    public function get_cuadro_evaluacion_objetivos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id, 0: Nacional
        
        $tabla='<center><iframe id="ipdf" width="100%" height="800px;" src="'.base_url().'index.php/rep_eval_obj/evaluacion_objetivos/'.$dep_id.'"></iframe></center>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }*/

    //// EVALUACIÓN ACP REGIONAL INSTITUCIONAL - IFRAME
   /* public function evaluacion_objetivos($id){
      $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $tabla='';
      $dep_id=$id;
      if($id!=0){ //// REGIONAL
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo_graf']=strtoupper($regional[0]['dep_departamento']);
        $data['cabecera']=$this->evaluacionacp->cabecera_reporte_grafico(); /// Cabecera Grafico
        $data['nro']=count($this->model_objetivogestion->lista_acp_x_regional($dep_id));
        $data['eval']=$this->evaluacionacp->matriz_evaluacion_meta_acp_regional($dep_id);
      }
      else{ ///// INSTITUCIONAL
        $data['titulo_graf']='INSTITUCIONAL';
        $data['cabecera']=$this->evaluacionacp->cabecera_reporte_grafico(); /// Cabecera Grafico
        $data['nro']=count($this->model_objetivogestion->list_objetivosgestion_general());
        $data['eval']=$this->evaluacionacp->tabla_evaluacion_meta_institucional();
      }
      
      $data['tabla']=$this->evaluacionacp->detalle_acp($data['eval'],$data['nro'],1);
      
      $tabla.='<div style="font-family: Arial;">DETALLE A.C.P. '.$data['titulo_graf'].' / '.$this->gestion.'</div>
                <ul>';
                for ($i=1; $i <=$data['nro'] ; $i++) { 
                  $tabla.='<li style="font-family: Arial;font-size: 11px;height: 1%;">'.$data['eval'][$i][1].'.- '.$data['eval'][$i][2].' - <b>'.$data['eval'][$i][6].' %</b></li>';
                }
                $tabla.='
                </ul>
              <hr>';
      $data['detalle_acp']=$tabla;

      $data['matriz_pastel']=$this->evaluacionacp->matriz_gcumplimiento($data['eval'],$data['nro']);
      $data['tabla_pastel']=$this->evaluacionacp->tabla_gcumplimiento($data['matriz_pastel'],1,1);
      $data['tabla_pastel_todo']=$this->evaluacionacp->tabla_gcumplimiento($data['matriz_pastel'],2,1);

      $this->load->view('admin/reportes_cns/repevaluacion_objetivos/reporte_grafico_eval_consolidado_regional_objetivos', $data);
    }*/



  /*======== GENERAR MENU ===========*/
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
}