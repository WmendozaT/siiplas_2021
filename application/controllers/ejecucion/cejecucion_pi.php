<?php
class Cejecucion_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11','3' => '10'); 
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
          $this->load->model('programacion/model_actividad');
          $this->load->model('programacion/model_producto');
          $this->load->model('programacion/model_componente');
          $this->load->model('mantenimiento/model_ptto_sigep');
          $this->load->model('programacion/insumos/model_insumo');
          $this->pcion = $this->session->userData('pcion');
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->dep_id = $this->session->userData('dep_id');
          $this->tmes = $this->session->userData('trimestre');
          $this->fun_id = $this->session->userData('fun_id');
          $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
          $this->ppto= $this->session->userData('verif_ppto'); 
          $this->verif_mes=$this->session->userData('mes_actual');
          $this->tp_adm = $this->session->userData('tp_adm');
          $this->load->library('ejecucion_finpi');
        }else{
            redirect('admin/dashboard');
        }
    }
    else{
        redirect('/','refresh');
    }
  }

  //// FORMULARIO DE EJECUCION PROYECTOS DE INVERSION
  /*------- Formulario de Ejecucion Proyectos de Inversion -------*/
  public function formulario_ejecucion_pinversion($com_id){
    $componente = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
    if(count($componente)!=0){
      $proyecto = $this->model_proyecto->get_proyecto_inversion($componente[0]['proy_id']);
      $regional=$this->model_proyecto->get_departamento($proyecto[0]['dep_id']);
      if(count($proyecto)!=0){
        $data['menu'] = $this->ejecucion_finpi->menu(4);
        $data['cabecera_formulario']=
        '<h2 title='.$proyecto[0]['aper_id'].'><small>PROYECTO : </small>'.$proyecto[0]['proy'].' - '.$proyecto[0]['proyecto'].'</h2>
         <h2><small>MES VIGENTE : </small> '.$this->verif_mes[2].' / '.$this->gestion.'</h2>
        
          <a href="javascript:abreVentana(\''.site_url("").'/prog/reporte_form4_consolidado/'.$proyecto[0]['proy_id'].'\');" class="btn btn-default" title="GENERAR REPORTE POA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="18" HEIGHT="18"/>&nbsp;<b>GENERAR POA '.$this->gestion.'</b></a>&nbsp;
          <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$proyecto[0]['proy_id'].'\');" class="btn btn-default" title="GENERAR FICHA TECNICA DE PROYECTO"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="18" HEIGHT="18"/>&nbsp;<b>GENERAR FICHA TECNICA</b></a>&nbsp;&nbsp;&nbsp;';

        
        $data['calificacion']=$this->calificacion_proyecto($proyecto);
        $data['reporte']='<a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$proyecto[0]['proy_id'].'\');" class="btn btn-default" title="REPORTE FORM. 4"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/><br><font size=1><b>FORM. N°4</b></font></a>';
        $data['formulario_datos_generales']=$this->tabla_datos_generales($proyecto,$com_id); /// Datos Generales
        $data['formulario_ejec_partidas']=$this->tabla_formulario_ejecucion_partidas($proyecto); /// Ejecucion Partidas
        

        //$matriz=$this->matriz_consolidado_ejecucion_pinversion($proyecto);
        //$data['cuadro_consolidado']=$this->tabla_consolidado_ejecucion_pinversion($matriz);

        $data['cuadro_consolidado']='
        <div class="row" id="btn_generar">
          <center><button type="button" onclick="generar_cuadro_consolidado_ejecucion_pi('.$proyecto[0]['proy_id'].');" class="btn btn-default"><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="100" HEIGHT="100"/><br><b>GENERAR CUADRO DE DE EJECUCIÓN POA</b></button></center>
        </div>
        <div id="loading_sepoa"></div>

        <div align="right" id="botton" style="display: none">
          <button onClick="imprimir_ejecucion_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO</b></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>

        <div id="cabecera" style="display: none">'.$this->ejecucion_finpi->cabecera_reporte_grafico('REGIONAL '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion,$proyecto[0]['proy'].' - '.$proyecto[0]['proyecto']).'</div>
        <hr>

        <div class="col-sm-6">
          <div id="grafico_ejec_pi">
            <div id="container" style="width: 950px; height: 500px; margin: 0 auto" align="center"></div>
            <div style="display: none"><div id="container_impresion"  style="width: 700px; height: 350px; margin: 0 auto"></div></div>
          </div>
        </div>

        <div class="col-sm-6">
          <div id="grafico_ejec_pi">
            <div id="proyectos" style="width: 950px; height: 450px; margin: 0 auto" align="center"></div>
            <div style="display: none"><div id="proyectos_impresion"  style="width: 700px; height: 350px; margin: 0 auto" align="center"></div></div>
          </div>
        </div>

        
        <div class="col-sm-12">
          <hr>
          <div class="table-responsive" id="cuadro_consolidado_vista"></div>
          <b>proy : '.$proyecto[0]['proy_id'].' - aper: '.$proyecto[0]['aper_id'].'</b>
          <div id="cuadro_consolidado_impresion" style="display: none"></div>
        </div>';
        
        $this->load->view('admin/ejecucion_pi/formulario_pinversion', $data);

      }
      else{
        redirect('seg/seguimiento_poa');
      }
      
    }
    else{
      echo "Error !!!";
    }
  }


  /*------ GET CUADRO CONSOLIDADO DE EJECUCION DE PI -----*/
  public function get_cuadro_ejecucion_pi(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']);
      $proyecto = $this->model_proyecto->get_proyecto_inversion($proy_id);

      $ppto_programado_poa_inicial=$this->model_insumo->temporalidad_inicial_pinversion($proyecto[0]['aper_id']); /// ppto poa Inicial

      $ppto_programado_poa=$this->model_insumo->list_temporalidad_programado_unidad($proyecto[0]['aper_id']); /// ppto poa (Actual)
      $ppto_ejecutado_sigep=$this->model_ptto_sigep->get_ppto_ejecutado_pinversion($proyecto[0]['aper_id']); /// Ppto Ejecutado sigep

      $matriz=$this->ejecucion_finpi->matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep);

      $cuadro_consolidado=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion($matriz); /// tabla vista
      $cuadro_consolidado_impresion=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion_impresion($matriz); /// tabla impresion

      $result = array(
        'respuesta' => 'correcto',
        'proyecto' => $proyecto,
        'datos_proyecto' => $proyecto[0]['proy'].' - '.$proyecto[0]['proyecto'],
        'mes' => $this->verif_mes[2].'/'.$this->gestion,
        'matriz' => $matriz,
        'cuadro_consolidado' => $cuadro_consolidado,
        'cuadro_consolidado_impresion' => $cuadro_consolidado_impresion,
      );
        
      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*-- TABLA PARA CUADRO CONSOLIDADO VISTA --*/
  /*public function tabla_consolidado_ejecucion_pinversion($matriz){
    $tabla='';
    $tabla.='
    <center>
    <table class="table table-bordered" style="width:98%;">
      <thead>
        <tr>
          <th></th>
          <th>ENE.</th>
          <th>FEB.</th>
          <th>MAR.</th>
          <th>ABR.</th>
          <th>MAY.</th>
          <th>JUN.</th>
          <th>JUL.</th>
          <th>AGO.</th>
          <th>SEPT.</th>
          <th>OCT.</th>
          <th>NOV.</th>
          <th>DIC.</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b>PPTO. PROGRAMADO INICIAL</b></td>';
        for ($i=1; $i <=12 ; $i++) {
          $tabla.='<td align="right" bgcolor="#cbedeb">'.number_format($matriz[1][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>PPTO. PROGRAMADO INICIAL ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) {
          $tabla.='<td align="right" bgcolor="#cbedeb">'.number_format($matriz[2][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>PPTO. RE-FORMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" '.$color.'>'.number_format($matriz[3][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>PPTO. RE-FORMULADO ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" '.$color.'>'.number_format($matriz[4][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>PPTO. EJECUTADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" '.$color.'>'.number_format($matriz[5][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>PPTO. EJECUTADO ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" '.$color.'>'.number_format($matriz[6][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td><b>(%) CUMPLIMIENTO MENSUAL</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" style="font-size:14px" '.$color.'><b>'.$matriz[7][$i].'%</b></td>';
        }
      $tabla.='
        </tr>
      </tbody>
    </table>
    </center>';

    return $tabla;
  }*/


  /*-- TABLA PARA CUADRO CONSOLIDADO --*/
 /* public function tabla_consolidado_ejecucion_pinversion_impresion($matriz){
    $tabla='';
    $tabla.='
    <center>
    <table class="change_order_items" border=1 style="width:100%;">
      <thead>
        <tr>
          <th style="width:16%;"></th>
          <th style="width:7%;">ENE.</th>
          <th style="width:7%;">FEB.</th>
          <th style="width:7%;">MAR.</th>
          <th style="width:7%;">ABR.</th>
          <th style="width:7%;">MAY.</th>
          <th style="width:7%;">JUN.</th>
          <th style="width:7%;">JUL.</th>
          <th style="width:7%;">AGO.</th>
          <th style="width:7%;">SEPT.</th>
          <th style="width:7%;">OCT.</th>
          <th style="width:7%;">NOV.</th>
          <th style="width:7%;">DIC.</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b>(%) CUMPLIMIENTO MENSUAL</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" style="font-size:10px" '.$color.'>'.$matriz[7][$i].'%</td>';
        }
      $tabla.='
        </tr>
      </tbody>
    </table>
    </center>';

    return $tabla;
  }*/


  /*-- MATRIZ PARA CUADRO CONSOLIDADO DE EJECUCION --*/
  /*public function matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep){

    /// Temporalidad Inicial Programado
    if(count($ppto_programado_poa_inicial)!=0){
     for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_ini[$i]=$ppto_programado_poa_inicial[0]['programado_total'];;
        }
        else{
          $vect_ini[$i]=round($ppto_programado_poa_inicial[0]['mes'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_ini[$i]=0;
        }
        else{
          $vect_ini[$i]=0;
        }
      }
    }

    /// Temporalidad Programado Ajustado (actual)
    if(count($ppto_programado_poa)!=0){
     for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_p[$i]=$ppto_programado_poa[0]['programado_total'];;
        }
        else{
          $vect_p[$i]=round($ppto_programado_poa[0]['mes'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_p[$i]=0;
        }
        else{
          $vect_p[$i]=0;
        }
      }
    }

    /// Temporalidad Ejecutado
    if(count($ppto_ejecutado_sigep)!=0){
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect[$i]=$ppto_ejecutado_sigep[0]['ejecutado_total'];;
        }
        else{
          $vect[$i]=round($ppto_ejecutado_sigep[0]['m'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect[$i]=0;
        }
        else{
          $vect[$i]=0;
        }
      }
    }


    for ($i=0; $i <=7; $i++) { 
      for ($j=0; $j <=12; $j++) { 
        $matriz[$i][$j]=0;
      }
    }


    $matriz[0][1]='ENE.';
    $matriz[0][2]='FEB.';
    $matriz[0][3]='MAR.';
    $matriz[0][4]='ABR.';
    $matriz[0][5]='MAY.';
    $matriz[0][6]='JUN.';
    $matriz[0][7]='JUL.';
    $matriz[0][8]='AGO.';
    $matriz[0][9]='SEPT.';
    $matriz[0][10]='OCT.';
    $matriz[0][11]='NOV.';
    $matriz[0][12]='DIC.';


    $suma_prog_inicial=0;
    $suma_prog=0;
    $suma_ejec=0;
    for ($j=0; $j <=12 ; $j++) { 

      if($j==0){
        $matriz[1][$j]=$vect_ini[$j]; // total prog inicial
        $matriz[2][$j]=0;
        $matriz[3][$j]=$vect_p[$j]; // total prog actual
        $matriz[4][$j]=0;
        $matriz[5][$j]=$vect[$j]; // total ejecutado
        $matriz[6][$j]=0;
        $matriz[7][$j]=0;
      }
      else{
        $suma_prog_inicial=$suma_prog_inicial+$vect_ini[$j];
        $suma_prog=$suma_prog+$vect_p[$j];
        $suma_ejec=$suma_ejec+$vect[$j]; 

        $matriz[1][$j]=$vect_ini[$j];
        $matriz[2][$j]=$suma_prog_inicial; /// acumulado ini

        $matriz[3][$j]=$vect_p[$j];
        $matriz[4][$j]=$suma_prog; /// acumulado prog

        $matriz[5][$j]=$vect[$j];
        $matriz[6][$j]=$suma_ejec; /// acumulado ejec
      }
       
      if($matriz[2][$j]!=0){
        $matriz[7][$j]=round(($matriz[6][$j]/$matriz[2][$j])*100,2);
      }
    }

    return $matriz;
  }*/


  /*-- CALIFICACION EJECUCION POR PROYECTO --*/
  public function calificacion_proyecto($proyecto){
    $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1); /// monto total asignado poa
    $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep($proyecto[0]['aper_id']); /// monto total ejecutado poa
    $eficacia=0;
    if(count($total_ppto_asignado)!=0 & count($total_ppto_ejecutado)!=0){
      $eficacia=round((($total_ppto_ejecutado[0]['ejecutado_total']/$total_ppto_asignado[0]['monto']))*100,2);
    }

    $titulo='';
    if($eficacia<=50){$tp='danger';$titulo='NIVEL DE CUMPLIMIENTO : '.$eficacia.'% -> INSATISFACTORIO (0% - 50%)';} /// Insatisfactorio - Rojo
    if($eficacia > 50 & $eficacia <= 75){$tp='warning';$titulo='NIVEL DE CUMPLIMIENTO : '.$eficacia.'% -> REGULAR (51% - 75%)';} /// Regular - Amarillo
    if($eficacia > 75 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE CUMPLIMIENTO : '.$eficacia.'% -> BUENO (76% - 99%)';} /// Bueno - Azul
    if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='NIVEL DE CUMPLIMIENTO : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

    $tabla='
      <hr>
      <div class="alert alert-'.$tp.'" role="alert" align="center">
        <h2><b>'.$titulo.'</b></h2>
      </div>';

    return $tabla;
  }

  /*-- FORMULARIO DATOS GENERALES DEL PROYECTO --*/
  public function tabla_datos_generales($proyecto,$com_id){
    $tabla='';
    $estado_proyecto=$this->model_proyecto->proy_estado();
    $fases=$this->model_faseetapa->fases();

    $tabla.='
      <form action="'.site_url("").'/ejecucion/cejecucion_pi/update_datos'.'" id="form1" name="form1" method="post" id="comment-form" class="smart-form">
          <input type="hidden" name="proy_id" value="'.$proyecto[0]['proy_id'].'">
          <input type="hidden" name="com_id" value="'.$com_id.'">
          <input type="hidden" name="pfec_id" value="'.$proyecto[0]['pfec_id'].'">

          <header>
              <b> DATOS GENERALES DEL PROYECTO</b>
          </header>

          <fieldset>
              <div class="row">
                  <section class="col col-2">
                      <label class="label">COSTO TOTAL PROYECTO</label>
                      <label class="input"> <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="costo" value='.$proyecto[0]['proy_ppto_total'].' onkeypress="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </label>
                  </section>
                  <section class="col col-2">
                    <label class="label">ESTADO DEL PROYECTO</label>
                      <select class="form-control" id="est_proy" name="est_proy" title="SELECCIONE ESTADO DE PROYECTO">';
                        foreach($estado_proyecto as $est){
                          if($est['ep_id']==$proyecto[0]['proy_estado']){ 
                            $tabla.='<option value="'.$est['ep_id'].'" selected>'.strtoupper($est['ep_descripcion']).'</option>';
                          }
                          else{ 
                            $tabla.='<option value="'.$est['ep_id'].'" >'.strtoupper($est['ep_descripcion']).'</option>';
                          }  
                        }
                        $tabla.='
                      </select>
                    </label>
                  </section>
                  <section class="col col-2">
                      <label class="label">MUNICIPIO</label>
                      <label class="input"> <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="municipio" value="'.$proyecto[0]['municipio'].'">
                      </label>
                  </section>
                  <section class="col col-2">
                      <label class="label">FASE</label>
                        <select class="form-control" id="fase_id" name="fase_id" title="SELECCIONE FASE">
                        <option value="0" selected>Seleccione Fase</option>';
                        foreach($fases as $fas){
                          if($fas['fas_id']==$proyecto[0]['fas_id']){ 
                            $tabla.='<option value="'.$fas['fas_id'].'" selected>'.strtoupper($fas['fas_fase']).'</option>';
                          }
                          else{ 
                            $tabla.='<option value="'.$fas['fas_id'].'" >'.strtoupper($fas['fas_fase']).'</option>';
                          }  
                        }
                        $tabla.='
                      </select>
                      </label>
                  </section>
                  <section class="col col-4">
                      <label class="label">FISCAL DE OBRA</label>
                      <label class="input"> <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="fiscal" value="'.$proyecto[0]['fiscal_obra'].'">
                      </label>
                  </section>
              </div>

              <div class="row">
                  <section class="col col-2">
                      <label class="label">AVANCE FÍSICO</label>
                      <label class="input"> <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="a_fisico" value='.round($proyecto[0]['avance_fisico'],2).' onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </label>
                  </section>
                  <section class="col col-2">
                      <label class="label">AVANCE FINANCIERO</label>
                      <label class="input"> <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="a_financiero" value='.round($proyecto[0]['avance_financiero'],2).' onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </label>
                  </section>
                  <section class="col col-6">
                      <label class="label">OBSERVACIÓN / COMPROMISO</label>
                      <label class="textarea"> <i class="icon-append fa fa-tag"></i>
                          <textarea rows="6" name="observacion">'.$proyecto[0]['proy_observacion'].'</textarea> </label>
                  </section>
                  <section class="col col-2">
                      <label class="label">PLAZO</label>
                      <label class="input"> <i class="icon-append fa fa-calendar"></i>
                      <input type="text" name="f_plazo" id="f_plazo"  placeholder="Seleccione Fecha Plazo" class="form-control datepicker" data-dateformat="dd/mm/yy" value="'.date('d/m/Y',strtotime($proyecto[0]['fecha_observacion'])).'">
                      </label>
                  </section>
              </div>

              <div class="row">
                <section class="col col-6">
                  <label class="label">PROBLEMA IDENTIFICADO</label>
                  <label class="textarea"> <i class="icon-append fa fa-tag"></i>
                      <textarea rows="6" name="problema">'.$proyecto[0]['proy_desc_problema'].'</textarea> </label>
                </section>
                <section class="col col-6">
                  <label class="label">PROPUESTA DE SOLUCIÓN</label>
                  <label class="textarea"> <i class="icon-append fa fa-tag"></i>
                      <textarea rows="6" name="solucion">'.$proyecto[0]['proy_desc_solucion'].'</textarea> </label>
                </section>
              </div>
          </fieldset>

          <footer>
            <button type="button" name="subir_form1" id="subir_form1" class="btn btn-info">GUARDAR DATOS</button>
            <a href="'.base_url().'index.php/seg/seguimiento_poa#tabs-a" title="SALIR" class="btn btn-default">SALIR</a>
          </footer>
      </form>';
    return $tabla;
  }


    /*--- VALIDA UPDATE DATOS - PROYECTO DE INVERSION ---*/
   public function update_datos(){
    if($this->input->post()) {
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']); /// proyecto id
      $pfec_id = $this->security->xss_clean($post['pfec_id']); /// pfec id
      $com_id = $this->security->xss_clean($post['com_id']); /// com id

        $ppto_total = $this->security->xss_clean($post['costo']); /// costo total proyecto
        $est_proy = $this->security->xss_clean($post['est_proy']); /// estado del proyecto
        $municipio = $this->security->xss_clean($post['municipio']); /// municipio
        $fase_id = $this->security->xss_clean($post['fase_id']); /// fase id
        $fiscal_obras = $this->security->xss_clean($post['fiscal']); /// fiscal
        $a_fisico = $this->security->xss_clean($post['a_fisico']); /// avance fisico
        $a_financiero = $this->security->xss_clean($post['a_financiero']); /// avance financiero
        $observacion = $this->security->xss_clean($post['observacion']); /// observacion
        $problema = $this->security->xss_clean($post['problema']); /// problema
        $solucion = $this->security->xss_clean($post['solucion']); /// solucion
        $fecha_plazo = $this->security->xss_clean($post['f_plazo']); /// fecha fase

        /// ----------------------
        $update_proyect = array(
          'fecha_observacion' => $fecha_plazo,
          'fiscal_obra' => $fiscal_obras,
          'avance_fisico' => $a_fisico,
          'avance_financiero' => $a_financiero,
          'proy_ppto_total' => $ppto_total,
          'ep_id' => $est_proy,
          'proy_observacion' => strtoupper($observacion),
          'municipio' => strtoupper($municipio),
          'proy_desc_problema' => strtoupper($problema),
          'proy_desc_solucion' => strtoupper($solucion)
        );
        $this->db->where('proy_id', $proy_id);
        $this->db->update('_proyectos', $update_proyect);

        /// ----------------------
        $update_fase = array(
          'fas_id' => $fase_id
        );
        $this->db->where('pfec_id', $pfec_id);
        $this->db->update('_proyectofaseetapacomponente', $update_fase);
        /// ---------------------

        $this->session->set_flashdata('success','LOS DATOS SE GUARDARON CORRECTAMENTE....');
        redirect(site_url("").'/form_ejec_pinversion/'.$com_id.'');
 
    } else {
        show_404();
    }
  }






  /*-- FORMULARIO EJECUCION DE PARTIDAS --*/
  public function tabla_formulario_ejecucion_partidas($proyecto){
    $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']); /// lista de partidas asignados por proyectos
    $tabla='';

      $tabla.='
      <article class="col-sm-12">
        <input type="hidden" name="base" value="'.base_url().'">';
        $nro=0;
        foreach($ppto_asig as $partida){
          $nro++;
          $temporalidad_ejec=$this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($partida['sp_id']); /// temporalidad ejec partida
          /// ------ Datos de Modifcacion de la partida
          $monto_partida=$this->ejecucion_finpi->detalle_modificacion_partida($partida);
          //// -----------------------------------------

          //// ----- Ejecutado por partida
          $total_ejecutado_partida=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($partida['sp_id']);
          $ppto_ejecutado=0;
          if(count($total_ejecutado_partida)!=0){
            $ppto_ejecutado=$total_ejecutado_partida[0]['ejecutado'];
          }

          $porcentaje_ejec=0;
          if($ppto_ejecutado!=0 & $monto_partida[3]!=0){
            $porcentaje_ejec=round((($ppto_ejecutado/$monto_partida[3])*100),2);
          }

          /// Observacion 
          $observacion_mes=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($partida['sp_id'],$this->verif_mes[1]);
          $detalle_observacion='';
          if(count($observacion_mes)!=0){
            $detalle_observacion=$observacion_mes[0]['observacion'];
          }


          $tabla.='
          <div style="font-size: 15px;font-family: Arial;"><b>PARTIDA : '.$partida['partida'].'</b> - '.strtoupper($partida['par_nombre']).'</div>
          
          <div class="table-responsive">
          <form class="smart-form">
          <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width:5%;">PPTO. INICIAL '.$this->gestion.'</th>
              <th style="width:5%;">PPTO. MODIFICADO '.$this->gestion.'</th>
              <th style="width:5%;">PPTO. VIGENTE '.$this->gestion.'</th>
              <th style="width:4.5%;">ENE.</th>
              <th style="width:4.5%;">FEB.</th>
              <th style="width:4.5%;">MAR.</th>
              <th style="width:4.5%;">ABR.</th>
              <th style="width:4.5%;">MAY.</th>
              <th style="width:4.5%;">JUN.</th>
              <th style="width:4.5%;">JUL.</th>
              <th style="width:4.5%;">AGO.</th>
              <th style="width:4.5%;">SEPT.</th>
              <th style="width:4.5%;">OCT.</th>
              <th style="width:4.5%;">NOV.</th>
              <th style="width:4.5%;">DIC.</th>
              <th style="width:15%;">OBSERVACIÓN</th>
              <th style="width:4.5%;"></th>
              <th style="width:4.5%;">PPTO. EJECUTADO '.$this->gestion.'</th>
              <th style="width:4.5%;">% CUMPLIMIENTO '.$this->gestion.'</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td style="height:12px;font-size:13px" align="right" title="'.$partida['sp_id'].'"><b>'.number_format($monto_partida[1], 0, ',', '.').'</b></td>
                <td align="right" style="font-size:13px"><b>'.number_format($monto_partida[2], 0, ',', '.').'</b></td>
                <td align="right" style="font-size:13px"><b>'.number_format($monto_partida[3], 0, ',', '.').'</b></td>';
                if(count($temporalidad_ejec)!=0){
                  for ($i=1; $i <=12 ; $i++) { 
                    
                    if($i==$this->verif_mes[1]){
                      $dato_mes_ejecutado=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($partida['sp_id'],$i);
                      $monto=0;
                      $tp=0;
                      $id_ejec=0;
                      if(count($dato_mes_ejecutado)!=0){
                        $monto=$dato_mes_ejecutado[0]['ppto_ejec'];
                        $tp=1;
                        $id_ejec=$dato_mes_ejecutado[0]['ejec_ppto_id'];
                      }
                      $tabla.='
                      <td align="right" bgcolor="#69a86d">
                        <label class="input">
                          <input type="text" value='.round($monto,2).' id="ejec'.$partida['sp_id'].'" onkeyup="verif_valor_pi('.$tp.',this.value,'.$partida['sp_id'].','.$i.');"  onkeypress="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                        </label>
                      </td>';
                    }
                    else{
                      $tabla.='<td align="right" style="font-size:13px"><b>'.number_format($temporalidad_ejec[0]['m'.$i], 0, ',', '.').'</b></td>';
                    }
                  }
                }
                else{
                  $monto=0;
                  $tp=0;
                  $id_ejec=0;
                  for ($i=1; $i <=12 ; $i++) {
                    if($i==$this->verif_mes[1]){
                      $tabla.='
                      <td align="right" bgcolor="#69a86d">
                        <label class="input">
                          <input type="text" value='.round($monto,2).' id="ejec'.$partida['sp_id'].'" onkeyup="verif_valor_pi('.$tp.',this.value,'.$partida['sp_id'].','.$i.');"  onkeypress="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                        </label>
                      </td>';
                    }
                    else{
                      $tabla.='<td align="right">0.00</td>';
                    }
                    
                  }
                }
                $tabla.='
                <td>
                  <label class="textarea textarea-resizable">                     
                    <textarea rows="3" id="obs_pi'.$partida['sp_id'].'" onkeyup="verif_valor_pi_obs(this.value,'.$partida['sp_id'].');">'.$detalle_observacion.'</textarea> 
                  </label>
                </td>
                <td>
                  <div id="but'.$partida['sp_id'].'" style="display:none;" align="center">
                    <button type="button" name="'.$partida['sp_id'].'" id="'.$nro.'" onclick="guardar_pi('.$proyecto[0]['proy_id'].','.$tp.','.$partida['sp_id'].','.$this->verif_mes[1].','.$id_ejec.','.$partida['partida'].');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="37" HEIGHT="37"/><br>GUARDAR</button>
                  </div>
                </td>
                <td align="right" style="font-size:20px"><b><div id="ppto'.$partida['sp_id'].'">'.number_format($ppto_ejecutado, 0, ',', '.').'</div></b></td>
                <td align="right" style="font-size:20px; color:blue"><b><div id="porcentaje'.$partida['sp_id'].'">'.$porcentaje_ejec.' %</div></b></td>
              </tr>
            </tbody>
          </table>
          </form>
          </div><br>';
        }

      $tabla.='</article>';
    return $tabla;
  }



/*---- VERIFICA EL MONTO A EJECUTAR POR PARTIDA ----*/
public function verif_valor_ejecutado_x_partida_form(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $tipo = $this->security->xss_clean($post['tipo']); /// tipo (0) nuevo, (1) modificado
    $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
    $ejec= $this->security->xss_clean($post['ejec']);/// valor a actualizar
    $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
    
    /// Datos - Programado y Ejecutado por partidas
    $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep


    $total_ejecutado_partida=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($sp_id);
    $ppto_ejecutado=0;
    if(count($total_ejecutado_partida)!=0){
      $ppto_ejecutado=$total_ejecutado_partida[0]['ejecutado'];
    }

    if($tipo==0){ /// Nuevo
      $ppto=$ejec+$ppto_ejecutado;
    }
    else{ /// modificado
      $dato_mes_ejecutado=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$mes_id); // valor registrado actualmente en el mes
      $ppto=$ejec+($ppto_ejecutado-$dato_mes_ejecutado[0]['ppto_ejec']);
    }

    $porcentaje=round((($ppto/$get_partida_sigep[0]['importe'])*100),2);

    ///
      if($ppto<=$get_partida_sigep[0]['importe']){
        $result = array(
          'respuesta' => 'correcto',
          'ejecucion_total_partida'=>number_format($ppto, 0, ',', '.'),
          'porcentaje_ejecucion_total_partida'=>$porcentaje,
          'dato_ejec'=>$ejec,
        );
      }
      else{
        $result = array(
          'respuesta' => 'error',
        );
      }
    //
    echo json_encode($result);
  }else{
      show_404();
  }
}


/*---- VERIFICA EL MONTO A EJECUTAR POR PARTIDA ----*/
public function guardar_datos_ejecucion_pinversion(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    // data: "sp_id="+id_partida+"&ejec="+ejec+"&obs="+obs+"&tp="+tp+"&mes_id="+mes_id+"&ejec_ppto_id="+id_ejec_mes

    $proy_id = $this->security->xss_clean($post['proy_id']); /// proy_id
    $tipo = $this->security->xss_clean($post['tp']); /// tipo (0) nuevo, (1) modificado
    $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
    $ejec= $this->security->xss_clean($post['ejec']);/// valor a actualizar
    $obs= $this->security->xss_clean($post['obs']);/// Observacion
    $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
    $ejec_ppto_id= $this->security->xss_clean($post['ejec_ppto_id']);/// mes id
    

      /// ----- Eliminando Registro de ejecucion --------
      $this->db->where('sp_id', $sp_id);
      $this->db->where('m_id', $mes_id);
      $this->db->delete('ejecucion_financiera_sigep');

      /// ----- Eliminando Registro de observacion --------
      $this->db->where('sp_id', $sp_id);
      $this->db->where('m_id', $mes_id);
      $this->db->delete('obs_ejecucion_financiera_sigep');

      if($ejec!=0){
        /// --- Registro de ejecucion ---
        $data_to_store = array(
          'sp_id' => $sp_id, /// Id sigep partida
          'm_id' => $mes_id, /// Mes 
          'ppto_ejec' => $ejec, /// Valor ejecutado
          'fun_id' => $this->fun_id, /// fun id
        );
        $this->db->insert('ejecucion_financiera_sigep', $data_to_store);
      }

      /// --- Registro de Observacion ---
      $data_to_store = array(
        'sp_id' => $sp_id, /// Id sigep partida
        'm_id' => $mes_id, /// Mes 
        'observacion' => strtoupper($obs), /// Valor ejecutado
        'fun_id' => $this->fun_id, /// fun id
      );
      $this->db->insert('obs_ejecucion_financiera_sigep', $data_to_store);
    

      /// Datos - Programado y Ejecutado por partidas
      $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep


      $total_ejecutado_partida=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($sp_id);
      $ppto_ejecutado=0;
      if(count($total_ejecutado_partida)!=0){
        $ppto_ejecutado=$total_ejecutado_partida[0]['ejecutado'];
      }

      $porcentaje=round((($ppto_ejecutado/$get_partida_sigep[0]['importe'])*100),2);

      ////
      $observacion_mes=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($sp_id,$mes_id);
      $detalle_observacion='';
      if(count($observacion_mes)!=0){
        $detalle_observacion=$observacion_mes[0]['observacion'];
      }
      ////

      $proyecto = $this->model_proyecto->get_proyecto_inversion($proy_id);
      $calificacion=$this->calificacion_proyecto($proyecto);


      //// CUADRO

      $ppto_programado_poa_inicial=$this->model_insumo->temporalidad_inicial_pinversion($proyecto[0]['aper_id']); /// ppto poa Inicial

      $ppto_programado_poa=$this->model_insumo->list_temporalidad_programado_unidad($proyecto[0]['aper_id']); /// ppto poa (Actual)
      $ppto_ejecutado_sigep=$this->model_ptto_sigep->get_ppto_ejecutado_pinversion($proyecto[0]['aper_id']); /// Ppto Ejecutado sigep

      $matriz=$this->matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep);
      $cuadro_consolidado=$this->tabla_consolidado_ejecucion_pinversion($matriz);
      $cuadro_consolidado_impresion=$this->tabla_consolidado_ejecucion_pinversion_impresion($matriz); /// tabla impresion

      $result = array(
        'respuesta' => 'correcto',
        'ejecucion_total_partida'=>number_format($ppto_ejecutado, 0, ',', '.'),
        'porcentaje_ejecucion_total_partida'=>$porcentaje,
        'dato_ejec'=>$ejec,
        'dato_obs'=>$detalle_observacion,
        'eficacia'=>$calificacion,

        'proyecto' => $proyecto,
        'datos_proyecto' => $proyecto[0]['proy'].' - '.$proyecto[0]['proyecto'],
        'mes' => $this->verif_mes[2].'/'.$this->gestion,
        'matriz' => $matriz,
        'cuadro_consolidado' => $cuadro_consolidado,
        'cuadro_consolidado_impresion' => $cuadro_consolidado_impresion,
      );

    echo json_encode($result);
  }else{
      show_404();
  }
}


















  //// EJECUCION DE PROYECTOS DE INVERSION (MODULO PARA REGIONALES)
  /*------- formulario ejecucion financiera MODULO PROYECTOS DE INVERSION-------*/
  public function formulario_ejecucion_ppto(){
    $data['menu']=$this->ejecucion_finpi->menu_pi();
    $data['style']=$this->ejecucion_finpi->style();
    $data['formulario']=$this->ejecucion_finpi->formulario();


    $this->load->view('admin/ejecucion_pi/form_ejec_fin_pi', $data);

  }


  /*---- GET DATOS DEL PROYECTO Y PARTIDAS ----*/
  public function get_formulario_proyecto_partidas(){
    if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $proy_id = $this->security->xss_clean($post['proy_id']); /// proyecto id
    $proyecto = $this->model_proyecto->get_proyecto_inversion($proy_id);
    $estado_proyecto=$this->model_proyecto->proy_estado();
    $mis_fases=$this->model_faseetapa->fases();
   // $ejec_fin=$this->ejecucion_finpi->avance_financiero_pi($proyecto[0]['aper_id'],$proyecto[0]['proy_ppto_total']);

    if(count($proyecto)!=0){
      $ppto_asignado=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']); /// lista de partidas asignados por proyectos
      $estado_proy='';
      $lista_partidas='';

      ///----------------------
      $estado_proy='
      <select class="form-control" id="est_proy" name="est_proy" title="SELECCIONE ESTADO DE PROYECTO">
        <option value="0" selected>Seleccione Estado Proyecto</option>';
        foreach($estado_proyecto as $est){
          if($est['ep_id']==$proyecto[0]['proy_estado']){ 
            $estado_proy.='<option value="'.$est['ep_id'].'" selected>'.strtoupper($est['ep_descripcion']).'</option>';
          }
          else{ 
            $estado_proy.='<option value="'.$est['ep_id'].'" >'.strtoupper($est['ep_descripcion']).'</option>';
          }  
        }
        $estado_proy.='
      </select>';
      /// --------------------

      ///----------------------
      $lista_fases='
      <select class="form-control" id="fas_id" name="fas_id" title="SELECCIONE FASE DEL PROYECTO">
        <option value="0" selected>Seleccione Fase</option>';
        foreach($mis_fases as $fas){
          if($fas['fas_id']==$proyecto[0]['fas_id']){ 
            $lista_fases.='<option value="'.$fas['fas_id'].'" selected>'.strtoupper($fas['fas_fase']).'</option>';
          }
          else{ 
            $lista_fases.='<option value="'.$fas['fas_id'].'" >'.strtoupper($fas['fas_fase']).'</option>';
          }  
        }
        $lista_fases.='
      </select>';
      /// --------------------

      
      ///----------------------
      $lista_partidas.='';
      $nro=0;
      foreach($ppto_asignado as $partida){
        $monto_partida=$this->ejecucion_finpi->detalle_modificacion_partida($partida); /// detalle modificacion de ppto partidas
        $ppto_ejecutado_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($partida['sp_id'],$this->verif_mes[1]); ///  monto ejecutado por partidas en el mes
        $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($partida['sp_id'],$this->verif_mes[1]); /// Observacion
        
        //// --- suma ppto de meses anteriores
        $suma_monto_ejecutado=0;
        for ($i=1; $i <$this->verif_mes[1] ; $i++) { 
            $ppto_mes=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($partida['sp_id'],$i); ///  monto ejecutado por partidas en el mes
            if(count($ppto_mes)!=0){
              $suma_monto_ejecutado=$suma_monto_ejecutado+$ppto_mes[0]['ppto_ejec'];
            }
        }
        //// -------------------------------


        $ppto_ejecutado=0;
        if(count($ppto_ejecutado_mensual)!=0){
          $ppto_ejecutado=$ppto_ejecutado_mensual[0]['ppto_ejec'];
        }

        $observacion_ejecutado='';
        if(count($obs_ejec_mensual)!=0){
          $observacion_ejecutado=$obs_ejec_mensual[0]['observacion'];
        }

        $nro++;
        $lista_partidas.='
        <center>

          <table class="table table-bordered" style="width:80%;">
           <thead>
            <tr>
              <th style="width:1%; font-size: 10px; text-align:center"><b>#</b></th>
              <th style="width:5%; font-size: 10px; text-align:center">PARTIDA</th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>PPTO. INICIAL</b></th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>PPTO.MODIFICADO</b></th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>PPTO. VIGENTE</b></th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>PPTO. EJECUTADO</b></th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>REGISTRO EJECUCION '.$this->verif_mes[2].'</b></th>
              <th style="width:20%; font-size: 10px; text-align:center"><b>OBSERVACIÓN '.$this->verif_mes[2].'</b></th>
            </tr>
          </thead>
          <tbody>
            <tr id="tr_color_partida'.$partida['sp_id'].'">
              <td style="text-align:center" title='.$partida['sp_id'].'>'.$nro.'</td>
              <td style="text-align:center"><b>'.$partida['partida'].'</b></td>
              <td style="text-align:right">'.number_format($monto_partida[1], 2, ',', '.').'</td>
              <td style="text-align:right">'.number_format($monto_partida[2], 2, ',', '.').'</td>
              <td style="text-align:right">'.number_format($monto_partida[3], 2, ',', '.').'</td>
              <td style="text-align:right">'.number_format($suma_monto_ejecutado, 2, ',', '.').'</td>
              <td>
                <input class="form-control" name="ejec_fin'.$partida['sp_id'].'" id="ejec_fin'.$partida['sp_id'].'" type="text"  value='.round($ppto_ejecutado,2).' onkeyup="verif_valor(this.value,'.$partida['sp_id'].','.$this->verif_mes[1].','.$proy_id.');" onkeypress="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
              </td>
              <td>
                <textarea class="form-control" name="observacion'.$partida['sp_id'].'" id="observacion'.$partida['sp_id'].'" rows="3">'.$observacion_ejecutado.'</textarea>
              </td>
            </tr>
          </tbody>
          </table>
        </center><br>';

        }
        /// --------------------

        if($proyecto[0]['fecha_observacion']!=''){
          $fecha_plazo=date('d/m/Y',strtotime($proyecto[0]['fecha_observacion']));
        }
        else{
          $fecha_plazo=date('d/m/Y');
        }

        $calificacion=$this->calificacion_proyecto($proyecto); /// cumplimiento de ppto de la gestion
        $result = array(
          'respuesta' => 'correcto',
          'proyecto' => $proyecto,
          'lista_fase' => $lista_fases,
          'estado' => $estado_proy,
          'partidas' => $lista_partidas,
          'calificacion' => $calificacion,
          'fecha_plazo' => $fecha_plazo,
        );
      }
      else{
        $result = array(
          'respuesta' => 'error',
        );
      }

      echo json_encode($result);
    }else{
        show_404();
    }
  }


 /*----- VALIDAR DATOS DEL PROYECTO Y EJECUCION FINANCIERA ----*/
  public function valida_update_pi(){
    if($this->input->post()) {
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']); /// proy id
      $proyecto=$this->model_proyecto->get_id_proyecto($proy_id); /// Datos de Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase
      $estado = $this->security->xss_clean($post['est_proy']); /// estado

      $fas_id = $this->security->xss_clean($post['fas_id']); /// fase
      $ppto_total = $this->security->xss_clean($post['ppto_total']); /// ppto total
      $fiscal_obras = $this->security->xss_clean($post['f_obras']); /// fiscal de obras
      $fecha_plazo = $this->security->xss_clean($post['mydate']); /// fecha plazo
      $avance_fisico = $this->security->xss_clean($post['ejec_fis']); /// Avance Fisico
      $avance_financiero = $this->security->xss_clean($post['ejec_fin']); /// Avance Financiero
      $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
      $problema = $this->security->xss_clean($post['problema']); /// Problema
      $solucion = $this->security->xss_clean($post['solucion']); /// solucion
      $ppto_asignado=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']); /// lista de partidas asignados por proyectos


      ///// Datos almacenados de un registro anterior
      $avance_fisico_anterior=$proyecto[0]['avance_fisico'];
      $avance_financiero_anterior=$proyecto[0]['avance_financiero'];

      $fecha_avance_fisico_anterior=strtotime($proyecto[0]['fecha_avance_fis']); 
      $fecha_avance_financiero_anterior=strtotime($proyecto[0]['fecha_avance_fin']); 

      $fecha_actual = strtotime(date("d/m/Y H:i:s"));
      ///// ----------------------------------------

      if($avance_fisico_anterior!=$avance_fisico){
        $update_proyect = array(
          'avance_fisico' => $avance_fisico,
          'fecha_avance_fis' => date("d/m/Y H:i:s")
        );
        $this->db->where('proy_id', $proy_id);
        $this->db->update('_proyectos', $update_proyect);
      }

      if($avance_financiero_anterior!=$avance_financiero){
        $update_proyect = array(
          'avance_financiero' => $avance_financiero,
          'fecha_avance_fin' => date("d/m/Y H:i:s")
        );
        $this->db->where('proy_id', $proy_id);
        $this->db->update('_proyectos', $update_proyect);
      }


      /// ------ Update proyecto
        $update_proyect = array(
          'fecha_observacion' => $fecha_plazo,
          'fiscal_obra' => $fiscal_obras,
          'proy_ppto_total' => $ppto_total,
          'proy_estado' => $estado,
          'proy_observacion' => strtoupper($observacion),
          'proy_desc_problema' => strtoupper($problema),
          'proy_desc_solucion' => strtoupper($solucion)
        );
        $this->db->where('proy_id', $proy_id);
        $this->db->update('_proyectos', $update_proyect);
      /// ------ End Update proyecto

        /// ------ Update fase
        $update_fase = array(
          'fas_id' => $fas_id
        );
        $this->db->where('pfec_id', $fase[0]['id']);
        $this->db->update('_proyectofaseetapacomponente', $update_fase);
      /// ------ End Update proyecto

      foreach($ppto_asignado as $partida){
        $ejec=$this->security->xss_clean($post['ejec_fin'.$partida['sp_id']]); /// ejecutado 
        $obs=$this->security->xss_clean($post['observacion'.$partida['sp_id']]); /// observacion

       

      /// ----- Eliminando Registro de ejecucion --------
        $this->db->where('sp_id', $partida['sp_id']);
        $this->db->where('m_id', $this->verif_mes[1]);
        $this->db->delete('ejecucion_financiera_sigep');

      /// -----------------------------------
        if($ejec!=0){
          /// ----- Registro de Ejecucion --------
          $data_to_store = array(
            'sp_id' => $partida['sp_id'], /// Id sigep partida
            'm_id' => $this->verif_mes[1], /// Mes 
            'ppto_ejec' => $ejec, /// Valor ejecutado
            'fun_id' => $this->fun_id, /// fun id
          );
          $this->db->insert('ejecucion_financiera_sigep', $data_to_store);
          /// -----------------------------------
        }

        $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($partida['sp_id'],$this->verif_mes[1]); /// Observacion
        $observacion_ejecutado='';
        if(count($obs_ejec_mensual)!=0){
          $observacion_ejecutado=$obs_ejec_mensual[0]['observacion'];
        }

        if(strtoupper($obs) != $observacion_ejecutado){
            /// ----- Registro de Observacion --------
            $data_to_store = array(
              'sp_id' => $partida['sp_id'], /// Id sigep partida
              'observacion' => strtoupper($obs), /// Observacion
              'm_id' => $this->verif_mes[1], /// Mes 
              'fun_id' => $this->fun_id, /// fun id
            );
            $this->db->insert('obs_ejecucion_financiera_sigep', $data_to_store);
            /// -----------------------------------
        }
      }

      
      /*-------------- Redireccionando a lista de Operaciones -------*/
        $this->session->set_flashdata('success','REGISTRO EXITOSO .. :)');
        redirect(site_url("").'/ejec_fin_pi');

    } else {
        show_404();
    }
  }

  ///// Adicionar Imgen del Proyecto
  function add_img(){
    if ($this->input->post()) {
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['p_id']);
        $descripcion = $this->security->xss_clean($post['detalle_imagen']);
        $tp = $this->security->xss_clean($post['tp_img']);

        $tipo = $_FILES['archivo']['type'];
        $tamanio = $_FILES['archivo']['size'];
        $archivotmp = $_FILES['archivo']['tmp_name'];

        $filename = $_FILES["archivo"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.'));
        $file_ext = substr($filename, strripos($filename, '.'));
        $allowed_file_types = array('.jpg');
        
        if ($tamanio < 90000000) {
            $newfilename = ''.$proy_id.'-'.$this->gestion.'-'.substr(md5(uniqid(rand())),0,5).$file_ext;
            
            if($tp==1){
                $update_img = array(
                  'tp' => 0
                );
                $this->db->where('proy_id', $proy_id);
                $this->db->update('imagenes_proy_inversion', $update_img);
            }

            /*--------------------------------------------------*/
              $data_to_store = array( 
                'imagen' => $newfilename,
                'proy_id' => $proy_id,
                'detalle' => $descripcion,
                'tp' => $tp,
                'fun_id' => $this->fun_id,
                );
              $this->db->insert('imagenes_proy_inversion', $data_to_store);
            /*--------------------------------------------------*/

          move_uploaded_file($_FILES["archivo"]["tmp_name"],"fotos_proyectos/" . $newfilename); // Guardando la foto

          $this->session->set_flashdata('success','REGISTRO EXITOSO ....');
        } 
        else{
          $this->session->set_flashdata('danger','ERROR AL SUBIR ARCHIVO ....');
        }

        redirect(site_url("").'/ejec_fin_pi');

    } else {
        show_404();
    }
  }

/*---- Galeria de Imagenes Proyectos de Inversion ----*/
public function get_galeria_imagenes_proyecto(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $proy_id = $this->security->xss_clean($post['proy_id']); /// proyecto id
    $proyecto=$this->model_proyecto->get_id_proyecto($proy_id); /// Datos de Proyecto
    $galeria=$this->model_proyecto->lista_galeria_pinversion($proy_id); /// Galeria

    $lista='';

    if(count($galeria)!=0){
      foreach($galeria as $row){
        $background='';
        if($row['tp']==1){
          $background='background:#ccf5f0';
        }

        $lista.='
        <div class="col-md-2">
          <table class="table table-bordered">
            <tr style="'.$background.'">
              <td >
                <center><img src="'.base_url().'fotos_proyectos/'.$row['imagen'].'" class="img-responsive" style="width:300px; height:250px;"/></center>
              </td>
            </tr>
            <tr style="'.$background.'">
              <td>'.strtoupper($row['detalle']).'</td>
            </tr>
          </table>
        </div>';
      }
    }
    else{
      $lista='<b>SIN REGISTRO ...</b>';
    }

    $result = array(
      'respuesta' => 'correcto',
      'proyecto'=>$proyecto,
      'lista_galeria'=>$lista,
    );

    echo json_encode($result);
  }else{
      show_404();
  }
}


/*---- Get Cambiar mes ----*/
public function get_cambiar_mes(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $mes_id = $this->security->xss_clean($post['mes_id']); /// mes id

  /* $data = array(
    'mes_actual'=>$this->verif_mes_gestion($mes_id),     
    );*/
    
    $data = array(
                'mes_actual'=>$this->verif_mes_gestion($mes_id)
               
            );
    $this->session->set_userdata($data);

    //        $this->session->set_userdata($data);

    /// -----------------------
    $result = array(
      'respuesta' => 'correcto',
    );

    echo json_encode($result);
  }else{
      show_404();
  }
}

    /*--- verifica datos del mes y año ---*/
    public function verif_mes_gestion($mes_sistema){
      $valor=$mes_sistema; // numero mes segun el sistema
      //$valor=ltrim(date("m"), "0"); // numero mes por defecto
      $mes=$this->mes_nombre_completo($valor);

      $datos[1]=$valor; // numero del mes
      $datos[2]=$mes[$valor]; // mes
      $datos[3]=$this->gestion; // Gestion

      return $datos;
    }

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
/*---- Detalle de Ejecucion Presupuestaria ----*/
public function get_ejecucion_presupuestaria_pi(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $proy_id = $this->security->xss_clean($post['proy_id']); /// proyecto id
    $proyecto=$this->model_proyecto->get_id_proyecto($proy_id); /// Datos de Proyecto
    $ppto=$this->model_ptto_sigep->get_ppto_ejecutado_proyecto($proy_id);// lista ppto temporalidad ejecutado
    $detalle_modificacion_pi=$this->ejecucion_finpi->detalle_modificacion_ppto_x_proyecto($proyecto[0]['aper_id']); // detalle de modificacion
    $ejec_fin=$this->ejecucion_finpi->avance_financiero_pi($proyecto[0]['aper_id'],$proyecto[0]['proy_ppto_total']);

    if(count($ppto)!=0){
      $j=0;
      for ($i=0; $i <=11 ; $i++) { 
        $j++;
        $ppto_pi[$i]=round($ppto[0]['m'.$j],2);
      }
      $ppto_pi[12]=round($ppto[0]['ejecutado_total'],2);
    }
    else{
      $j=0;
      for ($i=0; $i <=12 ; $i++) {
        $j++;
        $ppto_pi[$i]=0;
      }
    }
    
      $lista='';
      $lista.='
      <table class="table table-bordered" style="width:100%;">
        <thead>
          <tr>
            <th style="width:5%;">PPTO. INICIAL</th>
            <th style="width:5%;">PPTO. MODIFICADO</th>
            <th style="width:5%;">PPTO. VIGENTE</th>
            <th style="width:5%;">ENE.</th>
            <th style="width:5%;">FEB.</th>
            <th style="width:5%;">MAR.</th>
            <th style="width:5%;">ABR.</th>
            <th style="width:5%;">MAY.</th>
            <th style="width:5%;">JUN.</th>
            <th style="width:5%;">JUL.</th>
            <th style="width:5%;">AGO.</th>
            <th style="width:5%;">SEPT.</th>
            <th style="width:5%;">OCT.</th>
            <th style="width:5%;">NOV.</th>
            <th style="width:5%;">DIC.</th>
            <th style="width:5%;">PPTO. EJECUTADO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td align=right>'.number_format($detalle_modificacion_pi[1], 2, ',', '.').'</td>
            <td align=right>'.number_format($detalle_modificacion_pi[2], 2, ',', '.').'</td>
            <td align=right>'.number_format($detalle_modificacion_pi[3], 2, ',', '.').'</td>';
            for ($i=0; $i <=12 ; $i++) { 
              $lista.='<td align=right>'.number_format($ppto_pi[$i], 2, ',', '.').'</td>';
            }
          $lista.='
          </tr>
        </tbody>
      </table>';
    

    $result = array(
      'respuesta' => 'correcto',
      'proyecto'=>$proyecto,
      'detalle_ejecucion'=>$lista,
      'avance_financiero'=>$ejec_fin[2],
      'ppto'=>$ppto_pi,
    );

    echo json_encode($result);
  }else{
      show_404();
  }
}


///===============================================
/*---- VERIFICA MONTO A EJECUTAR POR PARTIDA ----*/
public function verif_valor_ejecutado_x_partida(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
   // $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
    $ejec= $this->security->xss_clean($post['ejec']);/// valor a actualizar
    $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
    
    /// Datos - Programado y Ejecutado por partidas
    $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep

    //// --- suma total del monto ejecutado antes del mes vigente
    $monto_total_ejec_partida=$this->get_monto_ejec_partida_hasta_mes_anterior($sp_id,$mes_id);
    //// ----------------------------------------------------------

    if(($ejec+$monto_total_ejec_partida)<=$get_partida_sigep[0]['importe']){
      $result = array(
        'respuesta' => 'correcto',
        'ejecucion_total_partida'=>round(($ejec+$monto_total_ejec_partida),2),
      );
    }
    else{
      $result = array(
        'respuesta' => 'error',
      );
    }

    echo json_encode($result);
  }else{
      show_404();
  }
}


  /*---- MONTO TOTAL EJECUTADO AL MES ANTERIOR POR PARTIDA----*/
  public function get_monto_ejec_partida_hasta_mes_anterior($sp_id,$mes_id){
    $suma_monto_ejecutado=0;
    for ($i=1; $i <$mes_id ; $i++) { 
      $ejec_mes=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$i);
      $monto_mes=0;
      if(count($ejec_mes)!=0){
        $monto_mes=$ejec_mes[0]['ppto_ejec'];
      }

      $suma_monto_ejecutado=$suma_monto_ejecutado+$monto_mes;
    }

    return $suma_monto_ejecutado;
  }

///===============================================


///// =============== REPORTES EJECUCION PI REGIONAL
/// Menu Reportes 
public function menu_rep_ejecucion_ppto(){
  $data['menu']=$this->ejecucion_finpi->menu_pi();
  $data['opciones']=$this->ejecucion_finpi->listado_opciones_reportes($this->dep_id);
  $regional=$this->model_proyecto->get_departamento($this->dep_id);
  $tabla='';
  $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
      <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
      <input name="gestion" type="hidden" value="'.$this->gestion.'">
      
      <div class="well">
        <div class="jumbotron">
          <h1>Regional '.strtoupper($regional[0]['dep_departamento']).' - '.$this->verif_mes[2].' / '.$this->gestion.'</h1>
            <p>
              Reporte consolidado de ejecución Presupuestaria de Proyectos de Inversion, gestión '.$this->gestion.' a nivel Regional.
            </p>
        </div>
      </div>';

  $data['titulo_modulo']=$tabla;

  $this->load->view('admin/ejecucion_pi/rep_menu', $data);
}


/*----  GET TIPO DE REPORTE ----*/
public function get_tp_reporte(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $rep_id = $this->security->xss_clean($post['rep_id']); /// tipo de reporte
    $dep_id = $this->security->xss_clean($post['dep_id']); /// regional
    $tabla='';

    $regional=$this->model_proyecto->get_departamento($dep_id);
    $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('REGIONAL '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion,'');

    if($rep_id==1){
      /// s1
      $titulo='MIS PROYECTOS DE INVERSIÓN - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->proyectos_inversion($dep_id,1); /// vista Lista de Proyectos
    }
    elseif ($rep_id==2) {
      /// s1
      $titulo='EJECUCIÓN FÍSICA Y FINANCIERA - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero
    }
    elseif ($rep_id==3) {
      /// s1
      $titulo='DETALLE EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->detalle_avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero
    }

      //// s2
      $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($dep_id)); /// nro de proyectos
      $matriz_proyectos=$this->ejecucion_finpi->matriz_proyectos_inversion_regional($dep_id); /// proyectos
      $tabla_detalle_ejec_impresion=$this->ejecucion_finpi->tabla_detalle_proyectos_impresion($matriz_proyectos,$nro_proy,1); /// Tabla Impresion tabla
      $grafico_avance_proyectos='<div id="graf_proyectos"><div id="proyectos" style="width: 1100px; height: 700px; margin: 0 auto"></div></div>';


      //// s3
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id));
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_regional($dep_id); /// Matriz consolidado de partidas
      $consolidado=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,0); /// Tabla Clasificacion de partidas asignados por regional
      $tabla_consolidado_grafico=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico
      $grafico_consolidado_partidas='<div id="graf_partida"><div id="partidas" style="width: 900px; height: 660px; margin: 0 auto"></div></div>';

      //// s4
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_regional($dep_id); /// ejecutado mensual
      $vector_meses_acumulado=$this->ejecucion_finpi->vector_consolidado_ppto_acumulado_mensual_regional($dep_id); /// ejecutado mensual Acumulado
      $tabla1=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,$dep_id);
      $grafico_mes='<div id="graf_ppto_mensual"><div id="ejec_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';
      $grafico_mes_acumulado='<div id="graf_ppto_mensual_acumulado"><div id="ejec_acumulado_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';

     $tabla='
       <div class="row">
          <article class="col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                    <h2>Ejecucion Presupuestaria</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <p>
                        <div style="font-size: 25px;font-family: Arial¨;"><b>'.$titulo.'</b></div>
                        <div id="cabecera" style="display: none">'.$cabecera_grafico.'</div>
                        </p>
                        <hr class="simple">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                          <li class="active">
                              <a href="#s1" data-toggle="tab"> Ejecución Proyectos</a>
                          </li>
                          <li>
                              <a href="#s2" data-toggle="tab"> Detalle Proyectos</a>
                          </li>
                          <li>
                              <a href="#s3" data-toggle="tab"> Consolidado por Partidas</a>
                          </li>
                          <li>
                              <a href="#s4" data-toggle="tab"> Consolidado por Meses</a>
                          </li>
                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                          <div class="tab-pane fade in active" id="s1">
                            <div class="row">
                              <article class="col-sm-12">
                                '.$grafico_avance_proyectos.'
                                <div id="tabla_impresion_ejecucion" style="display: none">
                                  '.$tabla_detalle_ejec_impresion.'
                                </div>
                                <div align="right">
                                    <button  onClick="imprimir_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  </div>
                              </article>
                            </div>
                          </div>

                          <div class="tab-pane fade" id="s2">
                              <div class="row">
                                <div class="table-responsive" align=center>
                                  <table style="width:90%;" border=0>
                                    <tr>
                                      <td align=right>
                                        <a href="javascript:abreVentana(\''.site_url("").'/reporte_detalle_ppto_pi/'.$dep_id.'/'.$rep_id.'\');" title="GENERAR REPORTE" class="btn btn-default">
                                          <img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="23" HEIGHT="24"/>&nbsp;GENERAR REPORTE (PDF)
                                        </a>
                                        <a href="'.site_url("").'/xls_rep_ejec_fin_pi/'.$dep_id.'/'.$rep_id.'" target=black title="EXPORTAR DETALLE" class="btn btn-default">
                                          <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;EXPORTAR DETALLE (EXCEL)
                                        </a>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><hr></td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <form class="smart-form" method="post">
                                          <section class="col col-3">
                                            <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/>
                                          </section>
                                        </form>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
                                '.$lista_detalle.'
                              </div>
                          </div>

                          <div class="tab-pane fade" id="s3">
                            <div class="row">
                              <article class="col-sm-12">
                                '.$grafico_consolidado_partidas.'
                                  <div align="right">
                                    <button  onClick="imprimir_partida()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  </div>
                                <hr>
                                '.$consolidado.'
                                <div id="tabla_impresion_partida" style="display: none">
                                  '.$tabla_consolidado_grafico.'
                                </div>
                              </article>

                            </div>
                          </div>

                          <div class="tab-pane fade" id="s4">
                            <div class="row">
                            <article class="col-sm-12 col-md-12 col-lg-6">
                              <div class="rows" align=center>
                              '.$grafico_mes.'
                              </div>
                            </article>
                            <article class="col-sm-12 col-md-12 col-lg-6">
                              <div class="rows" align=center>
                              '.$grafico_mes_acumulado.'
                              </div>
                            </article>
                            <div align="right">
                              <button  onClick="imprimir_ejecucion_mensual()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                             <hr>
                             '.$tabla1.'
                              <div id="tabla_impresion_ejecucion_mensual" style="display: none">
                                '.$tabla1.'
                              </div>
                            </div>
                          </div>

                        </div>
                    </div>
                  </div>
              </div>
          </article>
        </div>';

    $result = array(
      'respuesta' => 'correcto',
      'regional' => strtoupper($regional[0]['dep_departamento']),
      'tabla'=>$tabla,
      'nro'=>$nro,
      'matriz'=>$matriz_partidas,
      'vector_meses'=>$vector_meses,
      'vector_meses_acumulado'=>$vector_meses_acumulado,

      'nro_proy'=>$nro_proy,
      'matriz_proy'=>$matriz_proyectos,

     // 'btn_partidas'=>$boton_partida,
    );

    echo json_encode($result);
  }else{
      show_404();
  }
}


  /*--- REPORTE FICHA TECNICA PROY INVERSION ---*/
  public function ficha_tecnica_pi($proy_id){
    $regional=$this->model_proyecto->get_departamento($this->dep_id);
    $data['titulo_pie_rep']='Ficha_Tecnica_PI'.strtoupper($regional[0]['dep_departamento']).' '.$this->gestion;
    $titulo_reporte='FICHA TÉCNICA';

    ///---------
    $proyecto = $this->model_proyecto->get_proyecto_inversion($proy_id); // get proyecto
    $cumplimiento=$this->ejecucion_finpi->cumplimiento_pi($proyecto); // cumplimiento ppto gestion
    $parametro_cumplimiento=$this->calificacion_proyecto($proyecto);
    ///---------

    $data['cabecera']=$this->ejecucion_finpi->cabecera_ficha_tecnica($titulo_reporte);
    $data['pie']=$this->ejecucion_finpi->pie_ficha_tecnica();
    $data['datos_proyecto']=$this->ejecucion_finpi->datos_proyecto_inversion($proyecto,$cumplimiento); /// Datos Tecnicos
    $data['detalle_ejecucion']=$this->ejecucion_finpi->detalle_ejecucion_presupuestaria_pi($proyecto,$parametro_cumplimiento); /// Detalle Ejecucion

    $this->load->view('admin/ejecucion_pi/reporte_ficha_tecnica_pi', $data);
  }


    /*--- REPORTE DETALLE EJECUCION PRESUPUESTARIA PROY INVERSION ---*/
  public function reporte_detalle_ejec_ppto_pi($dep_id,$tipo_reporte){
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $data['titulo_pie_rep']='Ficha_Tecnica_PI'.strtoupper($regional[0]['dep_departamento']).' '.$this->gestion;
    $titulo_reporte='DETALLE EJECUCIÓN PRESUPUESTARIA - '.strtoupper($regional[0]['dep_departamento']).'';
    $data['cabecera']=$this->ejecucion_finpi->cabecera_ficha_tecnica($titulo_reporte);
    $data['pie']=$this->ejecucion_finpi->pie_ficha_tecnica();

    if($tipo_reporte==1){
      $data['datos_proyecto']=$this->ejecucion_finpi->reporte1_pdf_excel($dep_id,1);
    }
    elseif($tipo_reporte==2){
      $data['datos_proyecto']=$this->ejecucion_finpi->reporte2_pdf_excel($dep_id,1);
    }
    elseif($tipo_reporte==3){
      $data['datos_proyecto']=$this->ejecucion_finpi->reporte3_pdf_excel($dep_id,1);
    }

    $this->load->view('admin/ejecucion_pi/reporte_ficha_tecnica_pi', $data);
  }


  /*---- EXPORTAR A EXCEL REPORTE SEGUN EL TIPO (A DETALLE)----*/
  public function exportar_ejecucion_pi($dep_id,$tip){
    date_default_timezone_set('America/Lima');
    $fecha = date("d-m-Y H:i:s");
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    if($tip==1){
      $tabla=$this->ejecucion_finpi->reporte1_pdf_excel($dep_id,0);
    }
    elseif ($tip==2) {
      $tabla=$this->ejecucion_finpi->reporte2_pdf_excel($dep_id,0);
    }
    else{
      $tabla=$this->ejecucion_finpi->reporte3_pdf_excel($dep_id,0);
    }

    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Detalle_ejec_pi_".strtoupper($regional[0]['dep_departamento'])."/".$this->gestion."_$fecha.xls"); //Indica el nombre del archivo resultante
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "";
    ini_set('max_execution_time', 0); 
    ini_set('memory_limit','3072M');
    echo $tabla;
  }


    /*---- EXPORTAR A EXCEL REPORTE SEGUN EL TIPO (RESUMEN)----*/
  public function exportar_ejecucion_pi_resumen(){
    date_default_timezone_set('America/Lima');
    $fecha = date("d-m-Y H:i:s");
    
    $tabla='';
    $tabla=$this->ejecucion_finpi->reporte_consolidado_institucional_resumen();

    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Detalle_ejec_pi_RESUMEN_INSTITUCIONAL/".$this->gestion."_$fecha.xls"); //Indica el nombre del archivo resultante
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "";
    ini_set('max_execution_time', 0); 
    ini_set('memory_limit','3072M');
    echo $tabla;
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