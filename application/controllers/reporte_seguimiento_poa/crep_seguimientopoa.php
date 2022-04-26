<?php
class Crep_seguimientopoa extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('mantenimiento/model_configuracion');

            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
        }
        else{
            redirect('/','refresh');
        }
    }

    //// TIPO DE RESPONSABLE
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

    //// CONSOLIDADO POA POR REGIONALES (2020-2021)
    public function list_regiones(){
      $data['menu']=$this->menu(7);
      $data['list']=$this->menu_nacional();

      $tabla='
          <input name="base" type="hidden" value="'.base_url().'">
          <div id="update_eval">
            <div class="jumbotron">
              <h1>Seguimiento POA mensual '.$this->gestion.'</h1>
              <p>
                Muestra el avance de ejecución de metas programas al mes de <b>'.$this->verif_mes[2].' / '.$this->gestion.'</b>, a nivel Nacional, Regional y Distrital.
              </p>
            </div>
          </div>';
     
      $data['titulo_modulo']=$tabla;

      $this->load->view('admin/reportes_cns/seguimiento_poa/menu_seguimiento_poa', $data);
    }


    //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $unidades=$this->model_estructura_org->list_unidades_apertura();
      $tabla.='
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>SEGUIMIENTO POA '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                        <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                        <option value="">SELECCIONE REGIONAL</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-3" id="ue">
                        <label class="label">UNIDAD EJECUTORA</label>
                        <select class="form-control" id="dist_id" name="dist_id" title="SELECCIONE DISTRITAL">
                        </select>
                      </section>

                      <section class="col col-3" id="tp">
                        <label class="label">TIPO DE GASTO</label>
                        <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO">
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
              </div>
            </article>';
    return $tabla;
  }


 /*-----  UNIDAD DISTRITALES 2020-2021 -----*/
    public function get_unidades_administrativas($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'distrital':
        $salida="";
          $dep_id=$_POST["elegido"];
          $dep=$this->model_proyecto->get_departamento($dep_id);
          $combog = pg_query('SELECT *
          from _distritales 
          where  dep_id='.$dep_id.' and dist_estado!=0
          order by dist_id asc');

          $salida.= "<option value=''>Seleccione UE ...</option>
            <option value='0'><b>000 .-CONSOLIDADO REGIONAL - ".strtoupper($dep[0]['dep_departamento'])."</b></option>";
          while($sql_p = pg_fetch_row($combog)){
            $salida.= "<option value='".$sql_p[0]."'>".$sql_p[5]." - ".strtoupper ($sql_p[2])."</option>";
          }

        echo $salida; 
        //return $salida;
        break;

        case 'tipo':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo ....</option>";
          $salida.= "<option value='4'>GASTO CORRIENTE</option>";
          $salida.= "<option value='1'>PROYECTO DE INVERSIÓN</option>";

        echo $salida; 
        //return $salida;
        break;
      }

    }


    /*--- GET LISTA DE SEGUIMIENTO POA (2021)---*/
    public function get_lista_gcorriente_pinversion(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $dist_id = $this->security->xss_clean($post['dist_id']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        $salida='';
        $salida=$this->lista_gastocorriente_pinversion_seguimiento($dep_id,$dist_id,$tp_id);

        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }




    /*--  LISTA DE UNIDADES/PROYECTOS DE INVERSIÓN (DISTRITAL/REGIONALES) --*/
    public function lista_gastocorriente_pinversion_seguimiento($dep_id,$dist_id,$tp_id){
      
      if($dep_id!=0){
        if($dist_id==0){
          $departamento=$this->model_proyecto->get_departamento($dep_id);
          $titulo_cabecera='CONSOLIDADO REGIONAL '.strtoupper($departamento[0]['dep_departamento']);
          $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
        }
        else{
          $departamento=$this->model_proyecto->dep_dist($dist_id);
          $titulo_cabecera=strtoupper($departamento[0]['dist_distrital']);
          $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_distrital($dist_id,$tp_id);
        }
      }
      else{
        $titulo_cabecera='CONSOLIDADO INSTITUCIONAL - CNS';
      }
      

        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
        <section id="widget-grid" class="well">
          <div align=right>
           '.$this->formularios_mensual($dep_id,$dist_id,$tp_id).'&nbsp;&nbsp;&nbsp;
          </div>
          <h1><b>SEGUIMIENTO POA al mes de '.$this->verif_mes[2].' de la Gesti&oacute;n '.$this->gestion.'</b></h1>
          <h2><b>'.$titulo_cabecera.' ('.$titulo.')</b></h2>
        </section>';

        if($dep_id!=0){
          $tabla.=$this->tabla_seguimiento($unidades,$titulo,0,$tp_id,$this->verif_mes[1]);
        }
        else{
          $tabla.=$this->tabla_nacional(0,$tp_id,$this->verif_mes[1]);
        }
        
      return $tabla;
    }



    /*-- LISTA DE FORMULARIOS REPORTE DE SEGUIMIENTO POA --*/
    public function formularios_mensual($dep_id,$dist_id,$tp_id){
      $tabla='';
      $meses = $this->model_configuracion->get_mes();

      $tabla.='
        <div class="btn-group">
          <a class="btn btn-default">FORMULARIO SEGUIMIENTO POA </a>
          <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
          <ul class="dropdown-menu">';
            foreach($meses as $rowm){
            if($rowm['m_id']<=$this->verif_mes[1]){
              $tabla.='
              <li>
                <a href="'.site_url("").'/rep/get_reporte_seguimientopoa/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'/'.$rowm['m_id'].'" target="_blank">REPORTE SEGUIMIENTO POA - '.$rowm['m_descripcion'].'</a>
              </li>';
            }                     
          }
          $tabla.='
          </ul>
        </div>';

      return $tabla;
    }






    /// ---- REGIONALES (SEGUIMIENTO CONSOLIDADO NACIONAL)
    public function tabla_nacional($tp_rep,$tp_id,$mes_id){
      $regionales=$this->model_seguimientopoa->get_meta_total_regionales();
      // tp : 0 (Vista)
      // tp : 1 (Reporte)
      $mes=$this->model_evaluacion->get_mes($mes_id);
      $tab='class="table table-bordered" align=center style="width:50%;"';
      if($tp_rep==1){
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:80%;" align=center';
      }
      $tabla='';
      $tabla.='<br>
      <table '.$tab.' align=center>
        <thead>
          <tr style="background-color: #f8f8f8">
            <th style="width:2%;height:20px;" align="center"></th>
            <th style="width:10%;" align="center">COD. DA.</th>
            <th style="width:30%;" align="center">DIRECCI&Oacute;N ADMINISTRATIVA</th>
            <th style="width:15%;" align="center">META PROGRAMADO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:15%;" align="center">META CUMPLIDO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:10%;" align="center">% CUMPLIMIENTO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:10%;" align="center"></th>
          </tr>
        </thead>
        <tbody id="bdi">';
        $nro=0;
        foreach ($regionales as $row){
          $meta=$this->get_meta_regional($row['dep_id'],$mes_id);
          $nro++;
          $tabla.='
          <tr>
            <td style="width:2%;height:15px;" align=center>'.$nro.'</td>
            <td style="width:10%;" align=center>'.$row['da'].'</td>
            <td style="width:30%;">'.strtoupper($row['dep_departamento']).'</td>
            <td style="width:15%;" align=right>'.$meta[1].'</td>
            <td style="width:15%;" align=right>'.$meta[2].'</td>
            <td style="width:10%;" align=right><b>'.$meta[4].'%</b></td>
            <td style="width:10%;" align=left>'.$meta[5].'</td>
          </tr>';
        }

        $tabla.='</tbody>
        </table>';

      return $tabla;
    }




    /// ---- UNIDADES DISTRITAL/REGIONAL
    public function tabla_seguimiento($unidades,$titulo,$tp_rep,$tp_id,$mes_id){
      // tp : 0 (Vista)
      // tp : 1 (Reporte)
      $mes=$this->model_evaluacion->get_mes($mes_id);
      $tab='id="dt_basic" class="table table-bordered" style="width:100%;" border=1';
      if($tp_rep==1){
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }
      $tabla='';
      $tabla.='<br>
      <table '.$tab.'>
        <thead>
          <tr style="background-color: #f8f8f8">
            <th style="width:2%;height:20px;" align="center"></th>
            <th style="width:5%;" align="center">COD. DA.</th>
            <th style="width:5%;" align="center">COD. UE.</th>
            <th style="width:5%;" align="center">COD. PROG.</th>
            <th style="width:5%;" align="center">COD. PROY.</th>
            <th style="width:5%;" align="center">COD. ACT.</th>
            <th style="width:30%;" align="center">'.$titulo.'</th>
            <th style="width:10%;" align="center">META PROGRAMADO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:10%;" align="center">META EJECUTADO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:6%;" align="center">% CUMPLIMIENTO AL MES DE '.$mes[0]['m_descripcion'].'</th>
            <th style="width:10%;" align="center"></th>
          </tr>
        </thead>
        <tbody id="bdi">';
        $nro=0;
        foreach ($unidades as $row){
          $meta=$this->get_meta_unidad($row['pfec_id'],$mes_id);
          $nro++;
          $tabla.='
          <tr title="'.$row['aper_id'].'">
            <td style="width:2%;" align=center>'.$nro.'</td>
            <td style="width:5%;height:12px;" align=center>'.$row['da'].'</td>
            <td style="width:5%;" align=center>'.$row['ue'].'</td>
            <td style="width:5%;" align=center>'.$row['prog'].'</td>
            <td style="width:5%;" align=center>'.$row['proy'].'</td>
            <td style="width:5%;" align=center>'.$row['act'].'</td>
            <td style="width:30%;">';
              if($tp_id==1){
              $tabla.='<b>'.$row['proyecto'].'</b>';
            }
            else{
              $tabla.='<b>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</b>';
            }
            $tabla.='</td>
            <td style="width:10%;" align=right>'.$meta[1].'</td>
            <td style="width:10%;" align=right>'.$meta[2].'</td>
            <td style="width:6%;" align=right><b>'.$meta[4].'%</b></td>
            <td style="width:10%;" align=left>';
              if($tp_rep==0){
                $tabla.='<a href="#" data-toggle="modal" data-target="#modal_operaciones" class="btn btn-default enlace" name="'.$row['proy_id'].'"  onclick="ver_operaciones('.$row['proy_id'].');" title="FORMULARIO POA">'.$meta[5].'</a>';
              }else{
                $tabla.=$meta[5];
              } 
              $tabla.='
            </td>
          </tr>';
        }
        $tabla.='</tbody>
        </table>';

      return $tabla;
    }

    


    /*-----REPORTE SEGUIMIENTO POA POR DISTRITAL,REGIONAL 2021-----*/
    public function reporte_seguimiento_poa_unidades($dep_id,$dist_id,$tp_id,$mes_id){
      $mes=$this->model_evaluacion->get_mes($mes_id);
      if($dep_id!=0){
        if($dist_id==0){
          $departamento=$this->model_proyecto->get_departamento($dep_id);
          $data['titulo']='CONSOLIDADO REGIONAL '.strtoupper($departamento[0]['dep_departamento']);
          $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
        }
        else{
          $departamento=$this->model_proyecto->dep_dist($dist_id);
          $data['titulo']=strtoupper($departamento[0]['dist_distrital']);
          $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_distrital($dist_id,$tp_id);
        }
        $tabla=$this->tabla_seguimiento($unidades,$data['titulo'],1,$tp_id,$mes_id);
      }
      else{
        $tabla=$this->tabla_nacional(1,$tp_id,$mes_id);
        $data['titulo']='CONSOLIDADO NACIONAL';
      //  $data['titulo_cabecera']='CONSOLIDADO INSTITUCIONAL C.N.S.';
      }

      

      $data['titulo_cabecera']='GASTO CORRIENTE';
      if($tp_id==1){
        $data['titulo_cabecera']='PROYECTO DE INVERSI&Oacute;N';
      }

      
      $data['tit']='<b>SEGUIMIENTO POA al mes de '.$mes[0]['m_descripcion'].' de la Gesti&oacute;n '.$this->gestion.'</b>';
      
      $data['mes'] = $this->mes_nombre();
      $data['lista']=$tabla;
      $this->load->view('admin/reportes_cns/seguimiento_poa/reporte_seguimiento_poa', $data);
    }



    /*--- GET LISTA DE OPERACIONES POR SUBACTIVIDAD (SEGUIMIENTO) ----*/
    public function get_operaciones_subactividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        
        $titulo=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $titulo=$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
        }
        $tabla=$this->seguimiento_operaciones_xsubactividad($proy_id);
        
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'titulo'=>$titulo,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*--- LISTA DE OPERACIONES POR SUBACTIVIDAD ----*/
    public function seguimiento_operaciones_xsubactividad($proy_id){
      $subactividad=$this->model_componente->lista_subactividad($proy_id);
      $tabla='';
      $tabla.='
        <article>
          <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <header>
              <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
              <h2>OPERACIONES PROGRAMADAS MES '.$this->verif_mes[2].' - '.$this->gestion.'</h2>
            </header>
            <div>
              <div class="jarviswidget-editbox">
              </div>
              <div class="widget-body">
                <div class="panel-group smart-accordion-default" id="accordion">';
                $nro=0;
                  foreach ($subactividad as $rowc) {
                  $operaciones=$this->model_producto->list_operaciones_subactividad($rowc['com_id']);
                  $nro++;
                  $tabla.='
                  <div class="panel panel-default">
                    <div class="panel-heading" align=left>
                      <h4 class="panel-title" title='.$rowc['com_id'].'><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne'.$nro.'"> 
                        <img src="'.base_url().'assets/Iconos/arrow_down.png" WIDTH="25" HEIGHT="15"/>'.$rowc['serv_cod'].' '.$rowc['tipo_subactividad'].' '.$rowc['serv_descripcion'].'</a> 
                        
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
                                    <th style="width:1%;"><a href="'.site_url("").'/seg/ver_reporte_evaluacionpoa_consolidado/'.$rowc['com_id'].'" target=_blank ><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="16" HEIGHT="16"/></a></th>
                                    <th style="width:15%;" align=center>OPERACI&Oacute;N</th>
                                    <th style="width:10%;" align=center>RESULTADO</th>
                                    <th style="width:10%;" align=center>MEDIO DE VERIFICACI&Oacute;N</th>
                                    <th style="width:3%;" align=center>META</th>
                                    <th style="width:3%;" align=center>ENE.</th>
                                    <th style="width:3%;" align=center>FEB.</th>
                                    <th style="width:3%;" align=center>MAR.</th>
                                    <th style="width:3%;" align=center>ABR.</th>
                                    <th style="width:3%;" align=center>MAY.</th>
                                    <th style="width:3%;" align=center>JUN.</th>
                                    <th style="width:3%;" align=center>JUL.</th>
                                    <th style="width:3%;" align=center>AGO.</th>
                                    <th style="width:3%;" align=center>SEPT.</th>
                                    <th style="width:3%;" align=center>OCT.</th>
                                    <th style="width:3%;" align=center>NOV.</th>
                                    <th style="width:3%;" align=center>DIC.</th>
                                  </tr>
                                </thead>
                                <tbody>';
                                $nro_ope=0;
                                foreach ($operaciones as $row) {
                                  $temp=$this->temporalizacion_productos($row['prod_id']);
                                  $nro_ope++;
                                  $tabla.='
                                  <tr>
                                    <td align=center>'.$nro_ope.'</td>
                                    <td><b>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</b></td>
                                    <td>'.$row['prod_resultado'].'</td>
                                    <td>'.$row['prod_fuente_verificacion'].'</td>
                                    <td>'.round($row['prod_meta'],2).'</td>';
                                      
                                      for ($i=1; $i <=12 ; $i++) {
                                        $color='';
                                        if($i<=$this->verif_mes[1]){
                                          $color='#e3f9f6';
                                        }
                                        $tabla.='
                                        <td bgcolor='.$color.'>
                                          <table class="table table-bordered" align=center>
                                            <tr><td style="width:50%;"><b>P:</b></td><td style="width:50%;">'.round($temp[1][$i],2).'</td></tr>
                                            <tr><td style="width:50%;"><b>E:</b></td><td style="width:50%;">'.round($temp[4][$i],2).'</td></tr>
                                          </table>
                                        </td>';
                                      }

                                    $tabla.='
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

    /*------ OBTIENE METAS PROGRAMAS Y EJECUTADAS  --------*/
    public function get_meta_unidad($pfec_id,$mes_id){
      $meta_parcial_programado=$this->model_seguimientopoa->get_meta_unidad(1,$pfec_id,$mes_id); /// Programado
      $meta_parcial_ejecutado=$this->model_seguimientopoa->get_meta_unidad(2,$pfec_id,$mes_id); /// Ejecutado
      for ($i=1; $i <=5 ; $i++) { 
        $meta[$i]=0;
      }

      $prog=0;$ejec=0;
      if(count($meta_parcial_programado)!=0){
        $prog=$meta_parcial_programado[0]['meta'];
      }

      if(count($meta_parcial_ejecutado)!=0){
        $ejec=$meta_parcial_ejecutado[0]['meta'];
      }

      $meta[1]=round($prog,2);
      $meta[2]=round($ejec,2);
      $meta[3]=($prog-$ejec);
      if($meta[1]!=0){
        $meta[4]=round((($ejec/$prog)*100),2);
      }

      if($meta[1]==0){
        $meta[5]='';
      }
      elseif ($meta[2]==$meta[1]) {
        $meta[5]='<font color="#1d7469"><b>CUMPLIDO</b></font>';
      }
      elseif ($meta[2]<$meta[1] & $meta[2]!=0) {
        $meta[5]='<font color="#ebe400"><b>EN PROCESO</b></font>';
      }elseif ($meta[1]!=0 & $meta[2]==0) {
        $meta[5]='<font color="#eb0000"><b>NO CUMPLIDO</b></font>';
      }
      
      return $meta;
    }

    /*------ OBTIENE METAS PROGRAMAS Y EJECUTADAS POR REGIONAL --------*/
    public function get_meta_regional($dep_id,$mes_id){
      $meta_parcial_programado=$this->model_seguimientopoa->get_meta_regional(1,$dep_id,$mes_id); /// Programado
      $meta_parcial_ejecutado=$this->model_seguimientopoa->get_meta_regional(2,$dep_id,$mes_id); /// Ejecutado
      for ($i=1; $i <=5 ; $i++) { 
        $meta[$i]=0;
      }

      $prog=0;$ejec=0;
      if(count($meta_parcial_programado)!=0){
        $prog=$meta_parcial_programado[0]['meta'];
      }

      if(count($meta_parcial_ejecutado)!=0){
        $ejec=$meta_parcial_ejecutado[0]['meta'];
      }

      $meta[1]=round($prog,2);
      $meta[2]=round($ejec,2);
      $meta[3]=($prog-$ejec);
      if($meta[1]!=0){
        $meta[4]=round((($ejec/$prog)*100),2);
      }

      if($meta[1]==0){
        $meta[5]='';
      }
      elseif ($meta[2]==$meta[1]) {
        $meta[5]='<font color="#1d7469"><b>CUMPLIDO</b></font>';
      }
      elseif ($meta[2]<$meta[1] & $meta[2]!=0) {
        $meta[5]='<font color="#ebe400"><b>EN PROCESO</b></font>';
      }elseif ($meta[1]!=0 & $meta[2]==0) {
        $meta[5]='<font color="#eb0000"><b>NO CUMPLIDO</b></font>';
      }
      
      return $meta;
    }


    /*--- TEMPORALIZACION DE PRODUCTOS (nose esta tomando encuenta lb) ---*/
    public function temporalizacion_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
          $pa=$pa+$prod_prog[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $matriz[2][$i]=$pa;
          }
          else{
            $matriz[2][$i]=$matriz[1][$i];
          }

          
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[3][$i]=round(((($matriz[2][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
          }
          else{
            $ea=$matriz[4][$i];
          }

          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[6][$i]=round(((($matriz[5][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }
      
      return $matriz;
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
    /*------ NOMBRE MES -------*/
    function mes_nombre_completo(){
        $mes[1] = 'ENERO';
        $mes[2] = 'FEBRERO';
        $mes[3] = 'MARZO';
        $mes[4] = 'ABRIL';
        $mes[5] = 'MAYO';
        $mes[6] = 'JUNIO';
        $mes[7] = 'JULIO';
        $mes[8] = 'AGOSTO';
        $mes[9] = 'SEPTIEMBRE';
        $mes[10] = 'OCTUBRE';
        $mes[11] = 'NOVIEMBRE';
        $mes[12] = 'DICIEMBRE';

      return $mes;
    }
    /*=============================================*/

}