<?php
class Rep_operaciones extends CI_Controller { 
  public function __construct (){ 
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
        $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tmes = $this->session->userData('trimestre');
        $this->ppto= $this->session->userData('verif_ppto');
        $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema
        $this->load->library('genera_informacion');
        }else{
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
      $data['style']=$this->genera_informacion->style();
      $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $data['list']=$this->menu_nacional();
      $data['informacion_consolidado']='';
      if($this->fun_id==399){
        $data['informacion_consolidado']='
        <a href="'.site_url("").'/rep/exportar_requerimientos_institucional" target=_blank class="btn btn-default" title="EXPORTAR FORM. N5"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;EXPORTAR FORMULARIO 5 (INSTITUCIONAL)</a>
        <a href="'.site_url("").'/ptto_consolidado_comparativo_regional/0/4" target=_blank class="btn btn-default" title="CUADRO COMPARATIVO INSTITUCIONAL"><img src="'.base_url().'assets/Iconos/page_white_acrobat.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;PPTO. COMPARATIVO (INSTITUCIONAL)</a>
        <a href="'.site_url("").'/ptto_consolidado_distribucion_nacional" target=_blank class="btn btn-default" title="CUADRO COMPARATIVO PPTO. DISTRIBUCION NACIONAL"><img src="'.base_url().'assets/Iconos/page_white_acrobat.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;PPTO. DISTRIBUCION NACIONAL</a>
        <hr>';
      }
      $data['mensaje']='
      <div class="jumbotron"><h1>Consolidado Programación POA '.$this->gestion.'</h1><p>Reporte consolidado de Programación POA a nivel Regional y Distrital.</p><ol style="font-size:16px;"><li>Genera Reportes POA Formulario N° 4 y 5, Notificación POA Mensual por Unidad.</li><li>Genera Reporte Consolidado de Actividades por Regional y Distrital.</li><li>Genera Reporte Consolidado de Requerimientos por Regional y Distrital.</li><li>Genera Reporte de Ejecución Presupuestaria por Unidad Organizacional.</li><li>Genera el nro. de Actividades alineados a cada Acción Regional por Regional y Distrital.</li><li>Genera el nro. de Actividades alineados por cada Programa por Regional y Distrital.</li><li>Genera Reporte de nro. de Modificaciones POA realizados mensualmente por Regional y Distrital.</li><li>Genera Reporte de nro. de Certificaciones POA realizados mensualmente por Regional y Distrital.</li></ol></div>';
      $this->load->view('admin/reportes_cns/programacion_poa/menu_consolidado_poa', $data);

    }



    //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $unidades=$this->model_estructura_org->list_unidades_apertura();
      $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <input name="gestion" type="hidden" value="'.$this->gestion.'">
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <header><b>RESUMEN CONSOLIDADO PROGRAMACI&Oacute;N POA '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-2">
                    <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                    <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                    <option value="0">SELECCIONE REGIONAL</option>';
                    foreach($regionales as $row){
                      if($row['dep_id']!=0){
                        $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                      }
                    }
                    $tabla.='
                    </select>
                  </section>

                  <section class="col col-2">
                    <label class="label">UNIDAD EJECUTORA</label>
                    <select class="form-control" id="dist_id" name="dist_id" title="SELECCIONE DISTRITAL">
                    </select>
                  </section>

                  <section class="col col-2">
                    <label class="label">TIPO DE REPORTE</label>
                    <select class="form-control" id="rep_id" name="rep_id" title="SELECCIONE TIPO REPORTE">
                    </select>
                  </section>

                  <section class="col col-2">
                    <label class="label">TIPO DE GASTO</label>
                    <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO">
                    </select>
                  </section>

                  <div id="unidad" style="display:none;">
                    <section class="col col-2">
                      <label class="label">GASTO CORRIENTE / PROYECTO DE INVERSI&Oacute;N</label>
                      <select class="form-control" id="proy_id" name="proy_id" title="SELECCIONE GASTO CORRIENTE/PROYECTO DE INVERSION">
                        <option value="0">SELECCIONE GASTO CORRIENTE / PROYECTO</option>
                      </select>
                    </section>

                    <section class="col col-2">
                      <label class="label">UNIDAD RESPONSABLE</label>
                      <select class="form-control"id="sub_act" name="sub_act" title="SELECCIONE UNIDAD RESPONSABLE">
                      </select>
                    </section>
                  </div>

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
          $regional=$this->model_proyecto->get_departamento($dep_id);
          
          $combog = pg_query('SELECT *
          from _distritales 
          where  dep_id='.$dep_id.' and dist_estado!=0
          order by dist_id asc');

          $salida.= "
          <option value=''>Selecciones Unidad Ejecutora ...</option>
          <option value='0'><b>".$regional[0]['dep_cod']." - CONSOLIDADO REGIONAL ".strtoupper($regional[0]['dep_departamento'])."</b></option>";
          while($sql_p = pg_fetch_row($combog)){
            $salida.= "<option value='".$sql_p[0]."'>".$sql_p[5]." - ".strtoupper ($sql_p[2])."</option>";
          }

        echo $salida; 
        //return $salida;
        break;

        case 'tipo':
        $salida="";
          $dep_id=$_POST["elegido"];
          $salida.= "<option value='0'>Seleccione Tipo de Gasto</option>";
          $salida.= "<option value='4'>GASTO CORRIENTE</option>";
          $salida.= "<option value='1'>PROYECTO DE INVERSIÓN</option>";

        echo $salida; 
        //return $salida;
        break;

        case 'rep':
        $salida="";
          $salida.= "<option value='0'>Seleccione Tipo de Reporte</option>";
          $salida.= "<option value='1'>1-LISTA DE UNIDADES / PROY. INVERSIÓN</option>";
          $salida.= "<option value='2'>2- CONSOLIDADO ACTIVIDADES (FORM 4)</option>";
          $salida.= "<option value='3'>3- CONSOLIDADO REQUERIMIENTOS (FORM 5)</option>";
          $salida.= "<option value='4'>4- EJECUCI&Oacute;N CERTIFICACIÓN POA</option>";
          $salida.= "<option value='5'>5- ACTIVIDADES POR ACCION DE CORTO PLAZO</option>";
          $salida.= "<option value='6'>6- ACTIVIDADES POR CATEGORIA PROGRAMÁTICA</option>";
          $salida.= "<option value='7'>7- CONSOLIDADO NRO. MODIFICACIONES POA</option>";
          $salida.= "<option value='8'>8- CONSOLIDADO NRO. CERTIFICACIONES POA</option>";

        echo $salida; 
        //return $salida;
        break;

        case 'subactividades':
        $salida="";
          $proy_id=$_POST["elegido"];
          $combog = pg_query(
          'select c.com_id,ser.serv_cod,ser.serv_descripcion,tpsa.tipo_subactividad
            from _proyectofaseetapacomponente pfe
            Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
            Inner Join servicios_actividad as ser On ser.serv_id=c.serv_id
            Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
            where pfe.pfec_estado=1 and c.estado!=3 and pfe.proy_id='.$proy_id.'
            order by ser.serv_cod asc');

          $salida.= "<option value='0'>Seleccione Unidad Responsable</option>";
          while($sql_p = pg_fetch_row($combog)){
            $salida.= "<option value='".$sql_p[0]."'>".$sql_p[1]." ".strtoupper ($sql_p[3])." ".strtoupper ($sql_p[2])."</option>";
          }

        echo $salida; 
        //return $salida;
        break;
      }

    }

    /*--- GET LISTA DE CERTIFICACIONES (2020 - 2021)---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $dist_id = $this->security->xss_clean($post['dist_id']); /// Distrital
        $tp_id = $this->security->xss_clean($post['tp_id']); /// tipo de proyecto
        $tp_rep = $this->security->xss_clean($post['tp_rep']); /// tipo de Reporte
        
        $salida='';
        if($tp_rep==1){
          $salida=$this->genera_informacion->lista_gastocorriente_pinversion($dep_id,$dist_id,$tp_id);
        }
        elseif ($tp_rep==2) {
          $salida=$this->genera_informacion->consolidado_operaciones_distrital($dep_id,$dist_id,$tp_id); /// Consolidado Formulario N° 4 
        }
        elseif ($tp_rep==3) {

          ////----------------------------
          if($dist_id==0){
            $regional=$this->model_proyecto->get_departamento($dep_id);
            $requerimientos=$this->mrep_operaciones->consolidado_requerimientos_regional_distrital_directo(0, $dep_id, $tp_id); /// Consolidado Requerimientos 2020-2021 (Relacion Directa)
            $titulo_reporte='CONSOLIDADO '.strtoupper($regional[0]['dep_departamento']);
          }
          else{
            $distrital=$this->model_proyecto->dep_dist($dist_id);
            $requerimientos=$this->mrep_operaciones->consolidado_requerimientos_regional_distrital_directo(1, $dist_id, $tp_id); /// Consolidado Requerimientos 2020-2021 (Relacion Directa)
            $titulo_reporte=strtoupper($distrital[0]['dist_distrital']);
          }

          if(count($requerimientos)>7000){
            $salida='
              <hr>
              <div class="alert alert-warning " role="alert">
                <h4 class="alert-heading">Alerta !</h4>
                <hr>
                <p class="mb-0">'.$titulo_reporte.' (NO PUEDE SER GENERADO POR LA DIMESION DEL ARCHIVO, PARA OBTENER LA INFORMACION SOLICITADA LE SUGERIMOS DESCARGARLO EN FORMATO EXCEL.)</p>
              </div>
              
              <a href="'.site_url("").'/rep/exportar_requerimientos_distrital/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;DESCARGAR CONSOLIDADO FORM. N° 5</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <hr>';
          }
          else{
            $salida=$this->genera_informacion->genera_consolidado_form5_regional_distrital($titulo_reporte,$requerimientos,$dep_id,$dist_id,$tp_id); /// Consolidado formulario N° 5  
          }
          ////----------------------------
        
        }
        elseif ($tp_rep==4) {
          $salida='';
        }
        elseif ($tp_rep==5) {
          $salida=$this->consolidado_operaciones_oregional_distrital($dist_id,$tp_id);
        }
        elseif ($tp_rep==6) {
          $salida=$this->consolidado_operaciones_programa_distrital($dist_id,$tp_id);
        }
        elseif ($tp_rep==7) {
          $salida=$this->cuadro_modificaciones_poa_operaciones($dep_id,$dist_id,$tp_id);
        }
        elseif ($tp_rep==8) {
          $salida=$this->cuadro_certificaciones_poa($dist_id,$tp_id);
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


    /*-----REPORTE ESTABLECIMIENTOS DE SALUD (DISTRITAL) 2020-2021-----*/
    public function establecimientos_salud($dep_id,$dist_id){
     $this->genera_informacion->establecimientos_salud($dep_id,$dist_id);
    }

    

    /*-----REPORTE COMPARATIVO PRESUPUESTO ASIG-POA (DISTRITAL) 2020-2021-----*/
    public function comparativo_presupuesto_distrital($dep_id,$dist_id,$tp_id){
      $this->genera_informacion->comparativo_presupuesto_distrital($dep_id,$dist_id,$tp_id);
    }



    /////-------------------------------------------------------

    


    



    /* FORM 4 --- GET LISTA ACTIVIDADES / PROYECTOS DE INVERSION (2020 - 2021)---*/
    public function get_unidades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
       
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $dist_id = $this->security->xss_clean($post['dist_id']);
        $rep_id = $this->security->xss_clean($post['rep_id']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
       
        if($dist_id==0){
          $unidades=$this->mrep_operaciones->lista_unidad_pinversion_regional_distrital(0,$dep_id,$tp_id);
        }
        else{
          $unidades=$this->mrep_operaciones->lista_unidad_pinversion_regional_distrital(1,$dist_id,$tp_id);
        }
        
        $salida='';
        if($tp_id==1){
          foreach ($unidades as $row){
            $salida.= "<option value='".$row['proy_id']."'>".strtoupper ($row['proyecto'])."</option>";
          }
        }
        else{
          foreach ($unidades as $row){
            $salida.= "<option value='".$row['proy_id']."'>".$row['tipo']." ".strtoupper ($row['actividad'])." ".$row['abrev']."</option>";
          }
        }

        $result = array(
          'respuesta' => 'correcto',
          'lista_actividad' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- get lista de Requerimientos por Unidad Responsable (Componente) (2020 - 2021)---*/
    public function get_subactividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        
        $salida=$this->requerimientos_certificados_subactividad($componente);

        $result = array(
          'respuesta' => 'correcto',
          'lista_requerimientos_certificados' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /// ---- Lista de requerimientos por Unidad Responsable 2020-2021
    public function requerimientos_certificados_subactividad($componente){
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto=$this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);
      $requerimientos=$this->mrep_operaciones->consolidado_poa_formulario5_componente($componente[0]['com_id'],$proyecto[0]['tp_id']);
      
      $titulo='PROYECTO DE INVERIS&Oacute;N';
      $tit_proy=''.$proyecto[0]['aper_prog'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_act'].' '.$proyecto[0]['proy_nombre'];
      if($proyecto[0]['tp_id']==4){
        $tit_proy=''.$proyecto[0]['aper_prog'].' '.$proyecto[0]['aper_proy'].' '.$proyecto[0]['aper_act'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
      }

      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

      $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/rep/exportar_requerimientos_servicio/'.$componente[0]['com_id'].'" target=_blank class="btn btn-default" title="EJECUCION DE REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR EJECUCI&Oacute;N.XLS</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="'.site_url("").'/rep/rep_requerimientos_ejecucion_servicio/'.$componente[0]['com_id'].'" target=_blank class="btn btn-default" title="EJECUCION DE REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR EJECUCI&Oacute;N.PDF</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div class="alert alert-warning">
          <a href="#" class="alert-link" align=center><center><b>EJECUCI&Oacute;N FINANCIERA POA '.$this->gestion.'</b></center></a>
        </div>
        <table class="table table-bordered" style="width:50%;">
          <tr>
            <td style="width:10%;"><b>'.$titulo.'</b></td>
            <td>'.$tit_proy.'</td>
          </tr>
          <tr>
            <td style="width:30%;"><b>UNIDAD RESPONSABLE</b></td>
            <td>'.$componente[0]['serv_cod'].' '.$componente[0]['serv_descripcion'].'</td>
          </tr>
        </table>
        <hr>
        <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
          <thead>
            <tr style="background-color: #66b2e8">
              <th style="width:2%;">COD. ACT.</th>
              <th style="width:2%;">PARTIDA</th>
              <th style="width:20%;">REQUERIMIENTO</th>
              <th style="width:5%;">UNIDAD DE MEDIDA</th>
              <th style="width:3%;">CANTIDAD</th>
              <th style="width:5%;">PRECIO</th>
              <th style="width:5%;">COSTO TOTAL</th>
              <th style="width:5%;">TOTAL CERTIFICADO</th>
              <th style="width:4%;">P. ENE.</th>
              <th style="width:4%;">P. FEB.</th>
              <th style="width:4%;">P. MAR.</th>
              <th style="width:4%;">P. ABR.</th>
              <th style="width:4%;">P. MAY.</th>
              <th style="width:4%;">P. JUN.</th>
              <th style="width:4%;">P. JUL.</th>
              <th style="width:4%;">P. AGOS.</th>
              <th style="width:4%;">P. SEPT.</th>
              <th style="width:4%;">P. OCT.</th>
              <th style="width:4%;">P. NOV.</th>
              <th style="width:4%;">P. DIC.</th>
              <th style="width:5%;">OBSERVACI&Oacute;N</th>
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;$sum_programado=0;$sum_certificado=0;
          foreach ($requerimientos as $row){
            $nro++;
            $tabla.='<tr>';
              $tabla.='<td style="height:50px;" align=center><b>'.$row['prod_cod'].'</b></td>';
              $tabla.='<td>'.$row['par_codigo'].'</td>';
              $tabla.='<td>'.strtoupper($row['ins_detalle']).'</td>';
              $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
              $tabla.='<td>'.round($row['ins_cant_requerida'],2).'</td>';
              $tabla.='<td>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
              $tabla.='<td>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
              $tabla.='<td style="width:5%;" bgcolor="#c1f5ee" align=right><b>'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</b></td>';
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td>'.number_format($row['mes'.$i], 2, ',', '.').'</td>';
              }
              $tabla.='
                <td style="width:5%;">'.$row['ins_observacion'].'</td>
              </tr>';
             
              $sum_programado=$sum_programado+$row['ins_costo_total'];
              $sum_certificado=$sum_certificado+$row['ins_monto_certificado']; 
          }
          $tabla.='
          </tbody>
            <tr>
              <td colspan=6></td>
              <td align=right>'.number_format($sum_programado, 2, ',', '.').'</td>
              <td align=right>'.number_format($sum_certificado, 2, ',', '.').'</td>
              <td colspan=13></td>
            </tr>
        </table>';

      return $tabla;
    }
    /// --------------------------------------------------------

  /*-- REPORTE 5 (CONSOLIDADO OPERACIONES POR OBJETIVO REGIONAL) 2020-2021--*/
  public function consolidado_operaciones_oregional_distrital($dist_id,$tp_id){
    $distrital=$this->model_proyecto->dep_dist($dist_id);
    $unidades=$this->model_proyecto->lista_operaciones_oregional_distrital($dist_id,$tp_id);
    $tabla='';
    $sum_ope=0;

    $titulo='PROYECTOS DE INVERSI&Oacute;N';
    if($tp_id==4){
      $titulo='GASTO CORRIENTE';
    }

    $tabla.='
    <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

    $tabla.='
            <br>
            <div align=right>
              <a href="'.site_url("").'/rep/exportar_poa_oregional/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="RESUMEN ALINEACION POA - OBJETIVO REGIONAL"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR ALINEACION OPERACION-OBJETIVO REGIONAL</a>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <br>
            <div class="alert alert-warning">
              <a href="#" class="alert-link" align=center><center><b><b>CONSOLIDADO DE OPERACIONES POR OBJETIVO REGIONAL '.$titulo.' - '.$this->gestion.'<br>'.strtoupper($distrital[0]['dist_distrital']).'</b></center></a>
            </div>
            <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
              <thead>
                <tr >
                  <th style="width:1%; height:30px;">N°</th>
                  <th style="width:5%;">COD. OBJ. REGIONAL</th>
                  <th style="width:20%;">DESCRIPCI&Oacute;N OBJ. REGIONAL</th>
                  <th style="width:10%;">TIPO DE ADMINISTRACI&Oacute;N</th>
                  <th style="width:30%;">'.$titulo.'</th>
                  <th style="width:5%;">Nro. ACTIVIDAD</th>
                  <th style="width:10%;">PPTO. PROGRAMADO</th>
                </tr>
                <thead>
                <tbody id="bdi">';
                $nro=0;$suma_monto=0;
                foreach($unidades as $uni){
                  $ppto=$this->model_ptto_sigep->suma_ptto_accion($uni['aper_id'],2);
                  $monto=0;
                  if(count($ppto)!=0){
                    $monto=$ppto[0]['monto'];
                  }

                $nro++;
                $tabla.='<tr>
                          <td style="width:1%; height:25px;" align=center>'.$nro.'</td>
                          <td align=center><b>'.strtoupper($uni['or_codigo']).'<b></td>
                          <td><b>'.strtoupper($uni['or_objetivo']).'<b></td>
                          <td>'.$uni['dist_cod'].' .-'.strtoupper($uni['dist_distrital']).'</td>';
                            if($tp_id==1){
                              $tabla.='<td>'.$uni['aper_programa'].' '.$uni['proy_sisin'].' '.$uni['aper_proyecto'].' - '.$uni['proy_nombre'].'</td>';
                            }
                            else{
                              $tabla.='<td>'.strtoupper($uni['tipo'].' '.$uni['act_descripcion'].' '.$uni['abrev']).'</td>';  
                            }
                            
                          $tabla.='
                          <td align=right>'.$uni['operaciones'].'</td>
                          <td align=right>'.number_format($monto, 2, ',', '.').'</td>
                        </tr>';
                $sum_ope=$sum_ope+$uni['operaciones'];
                $suma_monto=$suma_monto+$monto;
                }

        $tabla.='
          </tbody>
          <tr>
            <td colspan=5 style="height:25px;"><b>TOTAL</b></td>
            <td align=right>'.$sum_ope.'</td>
            <td align=right>'.number_format($suma_monto, 2, ',', '.').'</td>
          </tr>
        </table>';

      return $tabla;
  }



  /*-- REPORTE 6 (CONSOLIDADO OPERACIONES POR PROGRAMA) 2020-2021--*/
 public function consolidado_operaciones_programa_distrital($dist_id,$tp_id){
    $distrital=$this->model_proyecto->dep_dist($dist_id);
    $unidades=$this->model_proyecto->lista_operaciones_unidades_apertura_distrital($dist_id,$tp_id);
    $tabla='';
    $sum_ope=0;

    $titulo='PROYECTOS DE INVERSI&Oacute;N';
    if($tp_id==4){
      $titulo='GASTO CORRIENTE';
    }

    $tabla.='
    <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

    $tabla.='
    <br>
    <div class="alert alert-warning">
      <a href="#" class="alert-link" align=center><center><b><b>CONSOLIDADO DE OPERACIONES POR CATEGORIA PROGRAMATICA '.$titulo.' - '.$this->gestion.'<br>'.strtoupper($distrital[0]['dist_distrital']).'</b></center></a>
    </div>
    <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
      <thead>
        <tr >
          <th style="width:1%; height:30px;">N°</th>
          <th style="width:5%;">PROGRAMA</th>
          <th style="width:10%;">ADMINISTRACI&Oacute;N</th>
          <th style="width:30%;">'.$titulo.'</th>
          <th style="width:5%;">Nro. ACTIVIDADES</th>
          <th style="width:10%;">PPTO. PROGRAMADO</th>
        </tr>
        <thead>
        <tbody id="bdi">';
        $nro=0;$suma_monto=0;
        foreach($unidades as $uni){
          $ppto=$this->model_ptto_sigep->suma_ptto_accion($uni['aper_id'],2);
          $monto=0;
          if(count($ppto)!=0){
            $monto=$ppto[0]['monto'];
          }

        $nro++;
        $tabla.='<tr>
                  <td style="width:1%; height:25px;" align=center>'.$nro.'</td>
                  <td align=center><b>'.strtoupper($uni['aper_programa']).'<b></td>
                  <td>'.$uni['dist_cod'].' .-'.strtoupper($uni['dist_distrital']).'</td>';
                    if($tp_id==1){
                      $tabla.='<td>'.$uni['aper_programa'].' '.$uni['proy_sisin'].' '.$uni['aper_proyecto'].' - '.$uni['proy_nombre'].'</td>';
                    }
                    else{
                      $tabla.='<td>'.strtoupper($uni['tipo'].' '.$uni['act_descripcion'].' '.$uni['abrev']).'</td>';  
                    }
                    
                  $tabla.='
                  <td align=right>'.$uni['operaciones'].'</td>
                  <td align=right>'.number_format($monto, 2, ',', '.').'</td>
                </tr>';
        $sum_ope=$sum_ope+$uni['operaciones'];
        $suma_monto=$suma_monto+$monto;
        }

        $tabla.='
          </tbody>
          <tr>
            <td colspan=4 style="height:25px;"><b>TOTAL</b></td>
            <td align=right>'.$sum_ope.'</td>
            <td align=right>'.number_format($suma_monto, 2, ',', '.').'</td>
          </tr>
        </table>';

      return $tabla;
  }
  /// ------------------------

  /*-- REPORTE 7 (CUADRO MODIFICACIONES POA) 2020-2021--*/
    public function cuadro_modificaciones_poa_operaciones($dep_id,$dist_id,$tp_id){
      $trimestre = array('1' => '3','2' => '6','3' => '9','4' => '12'); 

      if($dist_id!=0){ /// distrital
        $distrital=$this->model_proyecto->dep_dist($dist_id);
        $titulo1=strtoupper($distrital[0]['dist_distrital']);
      }
      else{ // consolidado regional
        $regional=$this->model_proyecto->get_departamento($dep_id);
        $titulo1=strtoupper($regional[0]['dep_departamento']);
      }
      
      $titulo='PROYECTO DE INVERSI&Oacute;N';
      if($tp_id==4){
        $titulo='GASTO CORRIENTE';
      }

      $tabla='';
      $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/rep/print_modificaciones_poa/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO MODIFICACIONES POA"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO MODIFICACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
          <div class="alert alert-warning">
            <a href="#" class="alert-link" align=center><center><b>CUADRO DE MODIFICACI&Oacute;N POA '.$this->gestion.' - '.$titulo1.' ('.$titulo.')</b></center></a>
          </div>
      <table class="table table-bordered" style="width:90%;" border=1 align=center>
        <thead>
          <tr>
            <th style="width:5%;"></th>';
              for ($i=1; $i <=$trimestre[$this->tmes]; $i++) { 
                  $mes=$this->get_mes($i);
                  $tabla.='<th style="width:8%;">'.$mes[1].'</th>';
              }
            $tabla.='
            <th style="width:6%;">TOTAL</th>
          </tr>
        </thead>
        <tbody>';
        $sum_ope=0;$sum_req=0;$sum_total=0;
        $tabla.='
          <tr>
            <td>FORMULARIO N° 4</td>';
            for ($i=1; $i <=$trimestre[$this->tmes] ; $i++) {
              if($dist_id!=0){
                $num_ope=count($this->model_modfisica->list_cites_generados_operaciones_distrital($dist_id,$i,$tp_id));
              }
              else{
                $num_ope=count($this->model_modfisica->list_cites_generados_operaciones_regional($dep_id,$i,$tp_id));
              }

              
              $tabla.='<td align=right>'.$num_ope.'</td>'; /// Operaciones
              $sum_ope=$sum_ope+$num_ope;
            }
            $tabla.='
            <td align=right bgcolor="#c8eff5"><b>'.$sum_ope.'</b></td>
          </tr>
          <tr>
            <td>FORMULARIO N° 5</td>';
            for ($i=1; $i <=$trimestre[$this->tmes] ; $i++) {
              if($dist_id!=0){
                $num_req=count($this->model_modrequerimiento->list_cites_generados_requerimientos_distrital($dist_id,$i,4));
              }
              else{
                $num_req=count($this->model_modrequerimiento->list_cites_generados_requerimientos_regional($dep_id,$i,4));
              }
              
              $tabla.='<td align=right>'.$num_req.'</td>'; /// Requerimientos
              $sum_req=$sum_req+$num_req;
            }
            $tabla.='
            <td align=right bgcolor="#c8eff5"><b>'.$sum_req.'</b></td>
          </tr>
          </tbody>
      </table>';

      return $tabla;
    }


    /*---REPORTE CUADRO MODIFICACION POA (DISTRITAL) 2020-2021-2022-2023---*/
    public function rep_cuadro_modificacion_poa($dep_id,$dist_id,$tp_id){
      $trimestre = array('1' => '3','2' => '6','3' => '9','4' => '12'); 
      $data['mes'] = $this->mes_nombre();
      //$unidades=$this->mrep_operaciones->list_unidades($dist_id,$tp_id);

      if($dist_id!=0){ /// distrital
        $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
        $data['titulo_rep']=strtoupper($distrital[0]['dist_distrital']);
      }
      else{ // consolidado regional
        $data['regional']=$this->model_proyecto->get_departamento($dep_id);
        $data['titulo_rep']=strtoupper($data['regional'][0]['dep_departamento']);
      }


      $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

      $tabla='';
      $sum_ope=0;$sum_req=0;$sum_total=0;
        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
              <thead>
                <tr>
                  <th style="width:7%; height:16px;" align=center></th>';
                    for ($i=1; $i <=$trimestre[$this->tmes]; $i++) { 
                        $mes=$this->get_mes($i);
                        $tabla.='<th style="width:7%;" align=center>'.$mes[1].'</th>';
                    }
                  $tabla.='
                  <th style="width:7%;" align=center>TOTAL</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="height:15px;">FORMULARIO N° 4</td>';
                  for ($i=1; $i <=$trimestre[$this->tmes] ; $i++) {
                    if($dist_id!=0){
                      $num_ope=count($this->model_modfisica->list_cites_generados_operaciones_distrital($dist_id,$i,$tp_id));
                    }
                    else{
                      $num_ope=count($this->model_modfisica->list_cites_generados_operaciones_regional($dep_id,$i,$tp_id));
                    }

                    $tabla.='<td align=right>'.$num_ope.'</td>'; /// Operaciones
                    $sum_ope=$sum_ope+$num_ope;
                  }
                  $tabla.='
                  <td align=right bgcolor="#c8eff5"><b>'.$sum_ope.'</b></td>
                </tr>
                <tr>
                  <td style="height:15px;">FORMULARIO N° 5</td>';
                  for ($i=1; $i <=$trimestre[$this->tmes] ; $i++) {
                    if($dist_id!=0){
                      $num_req=count($this->model_modrequerimiento->list_cites_generados_requerimientos_distrital($dist_id,$i,4));
                    }
                    else{
                      $num_req=count($this->model_modrequerimiento->list_cites_generados_requerimientos_regional($dep_id,$i,4));
                    }
                    
                    $tabla.='<td align=right>'.$num_req.'</td>'; /// Requerimientos
                    $sum_req=$sum_req+$num_req;
                  }
                  $tabla.='
                  <td align=right bgcolor="#c8eff5"><b>'.$sum_req.'</b></td>
                </tr>
              </tbody>
            </table>';

        $data['lista']=$tabla;
        $data['titulo_reporte']='CUADRO MODIFICACIONES POA '.$this->gestion.' (de 01/'.$this->gestion.' al '.$trimestre[$this->tmes].'/'.$this->gestion.')';
        $data['titulo_pie']='Modificaciones_POA_'.$data['titulo_rep'].'_'.$this->gestion.'';

        $this->load->view('admin/reportes_cns/resumen_operaciones/reporte_modificacion_poa', $data);
    }

    /*---------- Matriz cuadro Modificacion poa  -----------*/
    public function matriz_modificacion_poa($dist_id,$tp_id){
      $sum_ope=0;$sum_req=0;
      for ($i=1; $i <=ltrim(date("m"), "0") ; $i++) {
        $num_ope=$this->nro_mod_operaciones($dist_id,$i,$tp_id); /// Operaciones
        $matriz[1][$i]=$num_ope;
        $sum_ope=$sum_ope+$num_ope;

        $num_req=$this->nro_mod_requerimientos($dist_id,$i,$tp_id);
        $matriz[2][$i]=$num_req;
        $sum_req=$sum_req+$num_req;
      }

      $matriz[1][ltrim(date("m"), "0")+1]=$sum_ope;
      $matriz[2][ltrim(date("m"), "0")+1]=$sum_req;

      return $matriz;
    }


    /*---------- Nro de Requerimientos modificados por distrital -----------*/
/*    public function nro_mod_requerimientos($dist_id,$mes_id,$tp_id){
      $mes=$this->model_modrequerimiento->list_cites_generados_requerimientos_distrital($dist_id,$mes_id,$tp_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],1); /// Adicion
        $cm=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],2); /// Modificacion
        $cd=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],3); /// Eliminacion
          if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
            $nro++;
          }
        }
      return $nro;
    }*/

    /*---------- Nro de Actividades modificados por distrital -----------*/
/*    public function nro_mod_operaciones($dist_id,$mes_id,$tp_id){
      $mes=$this->model_modfisica->list_cites_generados_operaciones_distrital($dist_id,$mes_id,$tp_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modfisica->numero_de_modificaciones_productos($row['cite_id'],1); /// Adicion
        $cm=$this->model_modfisica->numero_de_modificaciones_productos($row['cite_id'],2); /// Modificacion
        //$cd=$this->model_modfisica->numero_de_modificaciones_productos($row['cite_id'],3); /// Eliminado
          if(count($ca)!=0 || count($cm)!=0){
            $nro++;
          }
        }
      return $nro;
    }*/
  ///------------------------------------------------------

  /*-- REPORTE 8 (CUADRO CERTIFICACIONES POA) 2020-2021--*/
    public function cuadro_certificaciones_poa($dist_id,$tp_id){
      $trimestre = array('1' => '3','2' => '6','3' => '9','4' => '12'); 
      $distrital=$this->model_proyecto->dep_dist($dist_id);
      $titulo='PROYECTO DE INVERSI&Oacute;N';
      if($tp_id==4){
        $titulo='GASTO CORRIENTE';
      }

      $tabla='';
      $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/rep/print_certificaciones_poa/'.$dist_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO CERTIFICACIONES POA"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO MODIFICACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
          <div class="alert alert-warning">
            <a href="#" class="alert-link" align=center><center><b><b>CUADRO DE CERTIFICACIONES POA '.$this->gestion.' - '.strtoupper($distrital[0]['dist_distrital']).' ('.$titulo.')</b></center></a>
          </div>
      <table class="table table-bordered" style="width:90%;" border=1 align=center>
        <thead>
          <tr>
            <th style="width:8%;"></th>';
              for ($i=1; $i <=$trimestre[$this->tmes]; $i++) { 
                  $mes=$this->get_mes($i);
                  $tabla.='<th style="width:7%;">'.$mes[1].'</th>';
              }
            $tabla.='
            <th style="width:6%;">TOTAL</th>
          </tr>
        </thead>
        <tbody>';
        $sum_cpoa=0;
        $tabla.='
          <tr>
            <td>CERTIFICACIONES POA</td>';
            for ($i=1; $i <=$trimestre[$this->tmes]; $i++) {
              $cert_poa=$this->model_certificacion->lista_certificaciones_distrital_mensual($dist_id,$tp_id,$this->gestion,$i);
              $num_certpoa=0;
              if(count($cert_poa)!=0){
                $num_certpoa=count($cert_poa);
              }
              $tabla.='<td align=right>'.$num_certpoa.'</td>'; /// Certificacion poa
              $sum_cpoa=$sum_cpoa+$num_certpoa;
            }
            $tabla.='
            <td align=right bgcolor="#c8eff5"><b>'.$sum_cpoa.'</b></td>
          </tr>
          </tbody>
      </table>';

      return $tabla;
    }

    /*-----REPORTE CUADRO CERTIFICACIONES POA (DISTRITAL) 2020-2021-----*/
    public function rep_cuadro_certificaciones_poa($dist_id,$tp_id){
      $trimestre = array('1' => '3','2' => '6','3' => '9','4' => '12');
      $data['distrital']=$this->model_proyecto->dep_dist($dist_id);
      $data['mes'] = $this->mes_nombre();
      $unidades=$this->mrep_operaciones->list_unidades($dist_id,$tp_id);
      if(count($data['distrital'])!=0){
          $titulo='GASTO CORRIENTE';
          if($tp_id==1){
            $titulo='PROYECTO DE INVERSI&Oacute;N';
          }

        $tabla='';
        $sum_cpoa=0;
          $tabla.='
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr>
                    <th style="width:7%; height:16px;" align=center></th>';
                      for ($i=1; $i <=$trimestre[$this->tmes]; $i++) { 
                          $mes=$this->get_mes($i);
                          $tabla.='<th style="width:7%;" align=center>'.$mes[1].'</th>';
                      }
                    $tabla.='
                    <th style="width:7%;" align=center>TOTAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width:7%; height:14px;">CERTIFICACIONES POA</td>';
                    for ($i=1; $i <=$trimestre[$this->tmes]; $i++) {
                      $cert_poa=$this->model_certificacion->lista_certificaciones_distrital_mensual($dist_id,$tp_id,$this->gestion,$i);
                      $num_certpoa=0;
                      if(count($cert_poa)!=0){
                        $num_certpoa=count($cert_poa);
                      }
                      $tabla.='<td align=right>'.$num_certpoa.'</td>'; /// Certificacion poa
                      $sum_cpoa=$sum_cpoa+$num_certpoa;
                    }
                    $tabla.='
                    <td align=right bgcolor="#c8eff5"><b>'.$sum_cpoa.'</b></td>
                  </tr>
                </tbody>
              </table>';

          $data['lista']=$tabla;
          $data['titulo_reporte']='CUADRO DE CERTIFICACIONES POA '.$this->gestion.' (de 01/'.$this->gestion.' al '.$trimestre[$this->tmes].'/'.$this->gestion.')';
          $data['titulo_pie']='Certificaciones_POA_'.$data['distrital'][0]['dist_distrital'].'_'.$this->gestion.'';
          $this->load->view('admin/reportes_cns/resumen_operaciones/reporte_modificacion_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }
    ////---------------------------------------------


    /*--------------------- MENU --------------------*/
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

  /*=========================================================================================================================*/
    public function get_mes($mes_id){
      $mes[1]='ENERO';
      $mes[2]='FEBRERO';
      $mes[3]='MARZO';
      $mes[4]='ABRIL';
      $mes[5]='MAYO';
      $mes[6]='JUNIO';
      $mes[7]='JULIO';
      $mes[8]='AGOSTO';
      $mes[9]='SEPTIEMBRE';
      $mes[10]='OCTUBRE';
      $mes[11]='NOVIEMBRE';
      $mes[12]='DICIEMBRE';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }
}