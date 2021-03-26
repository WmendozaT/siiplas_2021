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
        $data['matriz_programas']=$this->matriz_programas_regional($lista_programas);

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
        $data['matriz_programas']=$this->matriz_programas_distrital($lista_programas);

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
        $data['matriz_programas']=$this->matriz_programas_institucional($lista_programas);
      }

    
      $data['id']=$id;
      $data['tp']=$tp;

      $data['calificacion']=$this->calificacion_eficacia($data['matriz_programas'][(count($lista_programas)+1)][8],0); /// Parametros de Eficacia
      $data['tabla_programa']=$this->tabla_apertura_programatica($data['matriz_programas'],count($lista_programas),1);
      $data['matriz']=$this->matriz_parametros($data['matriz_programas'],count($lista_programas));
      $data['tabla_parametros']=$this->parametros_eficacia($data['matriz'],1);

      $data['print_evaluacion_programas']=$this->print_evaluacion($titulo_impresion,$this->tabla_apertura_programatica($data['matriz_programas'],count($lista_programas),0),$this->calificacion_eficacia($data['matriz_programas'][(count($lista_programas)+1)][8],1),$this->parametros_eficacia($data['matriz'],0));
      $this->load->view('admin/reportes_cns/repevaluacion_programas/reporte_grafico_eval_consolidado_regional_distrital', $data);

    }

    //// ========= INSTITUCIONAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - INSTITUCIONAL ---*/
    public function matriz_programas_institucional($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_institucional($row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]=' TOTAL INSITUCIONAL : '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_institucional($aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_institucional($aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_institucional_trimestre($aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_institucional_trimestre($aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 

    //// ========= REGIONAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - REGIONAL ---*/
    public function matriz_programas_regional($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_regional($row['dep_id'],$row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]=' TOTAL REGIONAL : '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_regional($dep_id,$aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_regional($dep_id,$aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_regional_trimestre($dep_id,$aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_regional_trimestre($dep_id,$aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 


    //// ========= DISTRITAL 
    /*--- MATRIZ EVALUACION PROGRAMA AL TRIMESTRE - DISTRITAL ---*/
    public function matriz_programas_distrital($lista_programas){
      $nro_prog=count($lista_programas)+1;
      $nro=0;
      $total=0;$cumplido=0;$ncumplido=0;
      foreach($lista_programas as $row){
        $nro++;
        $tr[$nro][1]=$nro; /// nro
        $tr[$nro][2]=$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad']; /// Apertura Programatica
        $tr[$nro][3]=$row['aper_descripcion']; /// Descripcion
        $datos=$this->obtiene_datos_evaluacíon_programa_distrital($row['dist_id'],$row['aper_programa'],1);
        $tr[$nro][4]=$datos[1]; /// Total Total
        $tr[$nro][5]=$datos[1]; /// Total Evaluado
        $tr[$nro][6]=$datos[2]; /// Total Cumplidos
        $tr[$nro][7]=($datos[1]-$datos[2]); /// Total No Cumplido
        $tr[$nro][8]=0; /// Total Cumplido %
        if($tr[$nro][4]!=0){
          $tr[$nro][8]=round((($tr[$nro][6]/$tr[$nro][4])*100),2);
        }
        $tr[$nro][9]=(100-$tr[$nro][8]); /// Total No cumplido %

        $total=$total+$tr[$nro][4];
        $cumplido=$cumplido+$tr[$nro][6];
        $ncumplido=$ncumplido+$tr[$nro][7];
      }

        $tr[$nro_prog][1]=''; /// nro
        $tr[$nro_prog][2]=''; /// Apertura Programatica
        $tr[$nro_prog][3]='TOTAL DISTRITAL '; /// Descripcion
        $tr[$nro_prog][4]=$total; /// Total Total
        $tr[$nro_prog][5]=$total; /// Total Evaluado
        $tr[$nro_prog][6]=$cumplido; /// Total Cumplidos
        $tr[$nro_prog][7]=$ncumplido; /// Total No Cumplido
        $tr[$nro_prog][8]=0; /// Total Cumplido %
        if($tr[$nro_prog][4]!=0){
          $tr[$nro_prog][8]=round((($tr[$nro_prog][6]/$tr[$nro_prog][4])*100),2);
        }
        $tr[$nro_prog][9]=(100-$tr[$nro_prog][8]); /// Total No cumplido %

      return $tr;
    }

    /*--- OBTIENE DATOS DE EVALUACIÓN 2020 - APERTURA REGIONAL ---*/
    public function obtiene_datos_evaluacíon_programa_distrital($dist_id,$aper_programa,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$this->tmes; $i++) {
        $programadas=$this->model_evalprograma->nro_operaciones_programadas_distrital($dist_id,$aper_programa,$i,4);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalprograma->list_operaciones_evaluadas_distrital_trimestre($dist_id,$aper_programa,$i,$tipo_evaluacion,4))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalprograma->list_operaciones_evaluadas_distrital_trimestre($dist_id,$aper_programa,$i,$tipo_evaluacion,4));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }
    //// ========= END REGIONAL 


    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia,$tp_rep){
      //tp_rep : 0 -> Normal, 1 : Impresion
      $tabla='';

      if($eficacia<=75){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 75%)';} /// Insatisfactorio - Rojo
      if ($eficacia > 75 & $eficacia <= 90){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (75% - 90%)';} /// Regular - Amarillo
      if($eficacia > 90 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (90% - 99%)';} /// Bueno - Azul
      if($eficacia > 99 & $eficacia <= 102){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

      if($tp_rep==0){
        $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';
      }
      else{
        $tabla.='<center><font size=4.5><b>'.$titulo.'</b></font></center>';
      }
      
      return $tabla;
    }



    /*--- TABLA APERTURA PROGRAMATICA  ---*/
    public function tabla_apertura_programatica($matriz,$nro,$tip_rep){
      $tabla='';
      if($tip_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

      $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>#</th>
                <th>APERTURA PROGRAM&Aacute;TICA</th>
                <th>DESCRIPCI&Oacute;N</th>
                <th>TOTAL PROGRAMADAS</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>';
              for ($i=1; $i <=$nro+1; $i++) { 
                $tabla.='<tr>';
                if($i==$nro+1){
                  $tabla.='<tr bgcolor=#e5ecef>';
                }
                
                for ($j=1; $j <=9; $j++) {
                  if($j==8 || $j==9){
                    if($j==8){
                      $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[$i][$j].'%</b></button></td>';
                    }
                    else{
                      $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$matriz[$i][$j].'%</b></button></td>';
                    }
                    
                  }
                  elseif($j==1 || $j==2 || $j==3){
                    if($j==3){
                      $tabla.='<td><b>'.$matriz[$i][$j].'</b></td>';
                    }
                    else{
                      $tabla.='<td align=center><b>'.$matriz[$i][$j].'</b></td>';
                    }
                  }
                  else{
                    $tabla.='<td align=right><b>'.$matriz[$i][$j].'</b></td>';
                  }
                  
                }
                $tabla.='</tr>';
              }
          $tabla.='
            </tbody>
          </table>';

      return $tabla;
    }


    /////  PARAMETROS DE EFICACIA 
    /*---- Tabla Parametros -----*/ 
    public function matriz_parametros($matriz,$nro){

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      for ($i=1; $i <=$nro; $i++) { 
        $eficacia=$matriz[$i][8];
        
        if($eficacia<=75){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
        if($eficacia > 75 & $eficacia <= 90){$par[2][2]++;} /// Regular - Amarillo (2)
        if($eficacia > 90 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
        if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/$nro)*100),2);
      }

      return $par;
    }

    /*----- Parametros de Eficacia Concolidado por Unidad -----*/
    public function parametros_eficacia($matriz,$tp_rep){
      if($tp_rep==1){ //// Normal
        $class='class="table table-bordered" align=center style="width:60%;"';
        $div='<div id="parametro_efi" style="width: 600px; height: 400px; margin: 0 auto"></div>';

      }
      else{ /// Impresion
        $class='class="change_order_items" border=1 align=center style="width:100%;"';
        $div='<div id="parametro_efi_print" style="width: 650px; height: 330px; margin: 0 auto"></div>';
      }
     // $nro=$matriz;
      $tabla='';
      $tabla .='<table '.$class.'>
                  <tr>
                    <td>
                      '.$div.'
                    </td>
                  </tr>
                  <tr>
                  <td>
                      <table '.$class.'>
                        <thead>
                          <tr>
                            <th style="width: 33%"><center><b>TIPO DE CALIFICACI&Oacute;N</b></center></th>
                            <th style="width: 33%"><center><b>PARAMETRO</b></center></th>
                            <th style="width: 33%"><center><b>NRO DE UNIDADES</b></center></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>INSATISFACTORIO</td>
                            <td>0% a 75%</td>
                            <td align="center"><a class="btn btn-danger" style="width: 100%" align="left" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                          </tr>
                          <tr>
                            <td>REGULAR</td>
                            <td>75% a 90% </td>
                            <td align="center"><a class="btn btn-warning" style="width: 100%" align="left" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                          </tr>
                          <tr>
                            <td>BUENO</td>
                            <td>90% a 99%</td>
                            <td align="center"><a class="btn btn-info" style="width: 100%" align="left" title="'.$matriz[3][2].' Unidades/Proyectos">'.$matriz[3][2].'</a></td>
                          </tr>
                          <tr>
                            <td>OPTIMO </td>
                            <td>100%</td>
                            <td align="center"><a class="btn btn-success" style="width: 100%" align="left" title="'.$matriz[4][2].' Unidades/Proyectos">'.$matriz[4][2].'</a></td>
                          </tr>
                          <tr>
                            <td colspan=2 align="left"><b>TOTAL: </b></td>
                            <td align="center"><b>'.($matriz[1][2]+$matriz[2][2]+$matriz[3][2]+$matriz[4][2]).'</b></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>';

      return $tabla;
    }


    /*---- Imprime Evaluacion Consolidado Regional-Distrital -----*/
    public function print_evaluacion($titulo,$tabla_programas,$eficacia,$tabla_parametros){
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      $calificacion=$this->calificacion_eficacia($eficacia,1); /// Parametros de Eficacia
      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table class="page_header" border="0" style="width: 100%;>
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 19pt;" align=center><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              '.$titulo.'
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;">
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        <div align=center><b>CUADRO DE EVALUACI&Oacute;N POA POR CATEGORIA PROGRAMATICA '.$this->gestion.'</b></div>';
        $tabla.=$tabla_programas;
        $tabla .='<div class="saltopagina"></div>';

              $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table class="page_header" border="0" style="width: 100%;>
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 19pt;" align=center><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              '.$titulo.'
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4pt;">
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        <div align=center><b>CUADRO DE EVALUACI&Oacute;N POA POR CATEGORIA PROGRAMATICA '.$this->gestion.'</b></div>';


        $tabla.=$tabla_parametros;
        ?>
        </html>
      <?php
    return $tabla;
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