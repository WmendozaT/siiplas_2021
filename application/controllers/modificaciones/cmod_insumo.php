<?php
class Cmod_insumo extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $this->load->model('Users_model','',true);
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('ejecucion/model_certificacion'); /// Gestion 2020
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->conf_mod_ope = $this->session->userData('conf_mod_ope');
            $this->conf_mod_req = $this->session->userData('conf_mod_req');
            $this->fecha_entrada = strtotime("20-09-2021 00:00:00");
            $this->load->library('modificacionpoa');
        }
        else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*----- cite Servicios de Unidad  ------*/
    public function cite_servicios($proy_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($data['proyecto'])!=0){
        if($data['proyecto'][0]['tp_id']==1){
          $titulo='
          <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</small>';
        }
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $titulo='
          <h1> <b>'.$data['proyecto'][0]['tipo_adm'].' : </b><small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'].'</small>';
        }

        $data['titulo']=$titulo;
        $data['tabla']=$this->modificacionpoa->lista_servicio_componentes($data['proyecto']);
        $this->load->view('admin/modificacion/requerimientos/cite_servicio', $data); 
      }
      else{
        redirect('mod/list_top');
      }
    }


    //// Limpiar ITEMS ELIMINAODS
    public function limpiar_insumos_eliminados($com_id){
      $componente=$this->model_componente->get_componente($com_id,$this->gestion);
      $requerimientos_del = $this->model_modrequerimiento->lista_requerimientos_eliminados($com_id);
      foreach ($requerimientos_del as $row) {
          $get_insumo_add=$this->model_modrequerimiento->get_insumo_adicionado_id($row['ins_id']); /// Add
          $get_insumo_mod=$this->model_modrequerimiento->get_insumo_modificado_id($row['ins_id']); /// Update

          if(count($get_insumo_add)==0 & count($get_insumo_mod)==0){
              /*-------- DELETE INSUMO PROGRAMADO --------*/  
                $this->db->where('ins_id', $row['ins_id']);
                $this->db->delete('temporalidad_prog_insumo');
              /*------------------------------------------*/
              /*-------- DELETE INSUMO --------*/
                $this->db->where('prod_id', $row['prod_id']);
                $this->db->where('ins_id', $row['ins_id']);
                $this->db->delete('_insumoproducto');
              /*--------------------------------*/
              /*-------- DELETE INSUMO  --------*/  
                $this->db->where('ins_id', $row['ins_id']);
                $this->db->delete('insumos');
              /*--------------------------------*/
          }
      }

      redirect(site_url("").'/mod/procesos/'.$componente[0]['proy_id'].'');
    }





    /*------- Valida Cite Para Modificacion -------*/
    public function valida_cite_modificacion(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id 
          $cite = $this->security->xss_clean($post['cite']); /// Cite
          $fecha = $this->security->xss_clean($post['fm']); /// Fecha
          $com_id = $this->security->xss_clean($post['com_id']); /// Com id
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

          if($proy_id!='' & count($proyecto)!=0){
            /*--- GUARDANDO CITE MODIFICADO (con estado inactivo) ---*/
            $data_to_store = array(
              'cite_nota' => strtoupper($cite),
              'cite_fecha' => $fecha,
              'com_id' => $com_id,
              'fun_id' => $this->fun_id,
              'g_id' => $this->gestion,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            );
            $this->db->insert('cite_mod_requerimientos',$data_to_store);
            $cite_id=$this->db->insert_id();
            /*-------------------------------------------------------*/

            if(count($this->model_modrequerimiento->get_cite_insumo($cite_id))==1){
              redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
              redirect(site_url("").'/mod/cite_servicios/'.$proy_id.'');
            }
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
            redirect(site_url("").'/mod/cite_servicios/'.$proy_id.'');
          }

          
      } else {
          show_404();
      }
    }

    /*----- REQUERIMIENTOS 2020-2021-2022 ------*/
    public function mis_requerimientos($cite_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['cite'] = $this->model_modrequerimiento->get_cite_insumo($cite_id);

      if(count($data['cite'])!=0){
        $proyecto = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']); /// Proyecto de Inversion
        $data['cabecera']=$this->cabecera_formulario_mod5($data['cite'],$proyecto);
        $data['opciones']=$this->opciones_formulario_mod5($data['cite'],$proyecto);
        $data['style']=$this->style();
      
          if($this->gestion>2021){ /// Gestion 2022-2023
            if(count($this->model_modrequerimiento->lista_requerimientos($data['cite'][0]['com_id']))>50){
              $data['tabla']=$this->modificacionpoa->modificar_requerimientos_auxiliar($data['cite']);  /// 2023
            }
            else{
              $data['tabla']=$this->modificacionpoa->modificar_requerimientos($data['cite']);  /// 2022
            }
          }
          else{ /// Gestion 2020-2021
            if(count($this->model_modrequerimiento->lista_requerimientos($data['cite'][0]['com_id']))>1000){
              $data['tabla']=$this->lista_requerimientos_auxiliar($data['cite']); /// 2021  
            }
            else{
              $data['tabla']=$this->lista_requerimientos($data['cite']); /// LISTA DE REQUERIMIENTO 2021
            }
          }

          $data['part_padres'] = $this->model_modificacion->list_part_padres_asig($proyecto[0]['aper_id']);//partidas padres
          $data['lista']=$this->tipo_lista_ope_act($data['cite']); /// LINEADO A ACTIVIDAD

          $this->load->view('admin/modificacion/requerimientos/list_requerimientos', $data);
      }
      else{
        redirect('mod/list_top');
      }
    }



  /*----- CABECERA FORMULARIO ------*/
  public function cabecera_formulario_mod5($cite,$proyecto){
    $monto=$this->modificacionpoa->ppto($proyecto);
    $tabla='';
    $tabla.='
      <section id="widget-grid" class="well">
        <div>
          '.$this->modificacionpoa->datos_cite($cite).'
          '.$this->modificacionpoa->titulo_cabecera($cite).'';
          if($monto[3]>19){
            $tabla.='
            <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO">
              <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>NUEVO REGISTRO (FORM. N 5)</b>
            </a>
            <a href="#" data-toggle="modal" data-target="#modal_importar" class="btn btn-default importar_ff" title="SUBIR ARCHIVO EXCEL">
              <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="25" HEIGHT="20"/>&nbsp;<b>SUBIR REQUERIMIENTOS.CSV </b>
            </a>';
          }
          $tabla.='
          <a href="'.site_url("").'/rep/exportar_requerimientos_servicio/'.$cite[0]['com_id'].'" target=_blank class="btn btn-default" title="EXPORTAR FORM. N5"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>DESCARGAR INFORMACION (EXCEL)</b></a>
        </div>
      </section>';
    return $tabla;
  }

  /*----- OPCIONES FORMULARIO ------*/
  public function opciones_formulario_mod5($cite,$proyecto){
    $monto=$this->modificacionpoa->ppto($proyecto);
    $tabla='';

      $tabla.='
        <div class="well">';
          if($cite[0]['cite_activo']==1){
            if($cite[0]['cite_estado']==1){
              $tabla.='<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#modal_cerrar" title="MODIFICACION CERRADA"><i class="fa fa-save"></i><b>&nbsp;MODIFICACI&Oacute;N CONCLUIDA</b></button><br>';
            }
            else{
              $tabla.='<button type="button" class="btn btn-warning btn-sm btn-block" data-toggle="modal" data-target="#modal_cerrar" title="CONSLUIR MODIFICACION"><i class="fa fa-save"></i><b>&nbsp;CERRAR MODIFICACIÓN</b></button><br>';
              /*$tabla.='<a data-toggle="modal" data-target="#modal_cerrar" class="btn btn-warning cerrar" title="CERRAR MODIFICACION FINANCIERA"><i class="fa fa-save"></i> <b>CERRAR MOD.</b></a>   ';*/
            }

            $tabla.='
            <a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_financiera/'.$cite[0]['cite_id'].'\');" title="IMPRIMIR REPORTE DE MODIFICACION POA">
              <button class="btn btn-default btn-lg btn-block">
                <i class="fa fa-file-pdf-o"></i><b>&nbsp;IMPRIMIR MODIFICACIÓN POA</b>
              </button>
            </a><br>';
          }

          $tabla.='
          <button type="button" id="btsubmit" onclick="valida_eliminar()" class="btn btn-danger btn-sm btn-block">
            <i class="glyphicon glyphicon-trash"></i> &nbsp;DELETE INSUMOS (SELECCIONADOS)
          </button>
        </div>';

    return $tabla;
  }


    /// ---- STYLE -----
    public function style(){
      $tabla='';

      $tabla.='   
      <style>
        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
        }
        th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
        }
            #mdialTamanio{
            width: 80% !important;
        }
        #comparativo{
          width: 50% !important;
        }
        #csv{
          width: 30% !important;
        }
          input[type="checkbox"] {
          display:inline-block;
          width:25px;
          height:25px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
    </style>';

      return $tabla;
    }






    /*---- tipo lista : Operacion-Actividad ----*/
    public function tipo_lista_ope_act($cite){
      $tabla='';
      $operaciones=$this->model_producto->lista_operaciones($cite[0]['com_id']);
        $tabla.='
          <section class="col col-3">
            <label class="label"><b>ALINEACI&Oacute;N FORM 4 (ACTIVIDAD) '.$this->gestion.'</b></label>
            <label class="input">
              <select class="form-control" id="dato_id" name="dato_id" title="SELECCIONE ACTIVIDAD">
                <option value="">Seleccione Actividad</option>';
                foreach($operaciones as $row){ 
                  $tabla.='<option value="'.$row['prod_id'].'">'.$row['or_codigo'].'/'.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
                } 
                $tabla.='      
              </select>
            </label>
          </section>';

      return $tabla;
    }


    


    /*----- LISTA REQUERIMIENTOS AUXILIAR (2021) en casos de que sean muchos requerimientos ------*/
    public function lista_requerimientos_auxiliar($cite){
      $lista_insumos=$this->model_modrequerimiento->lista_requerimientos($cite[0]['com_id']);
   //   $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($cite[0]['com_id']); /// Lista requerimientos

      $tabla='';
      $total=0;
      $tabla.=' <input type="hidden" name="proy_id" value="'.$cite[0]['proy_id'].'">
                <input type="hidden" name="aper_id" value="'.$cite[0]['aper_id'].'">
                <input type="hidden" name="cite_id" value="'.$cite[0]['cite_id'].'">
                <input type="hidden" name="base" value="'.base_url().'">
                <table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                  <tr class="modo1">
                    <th style="width:2%;">#</th>
                    <th style="width:2%;">COD. ACT.</th>
                    <th style="width:2%;"></th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:15%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">UNITARIO</th>
                    <th style="width:5%;">TOTAL</th>
                    <th style="width:5%;">TOTAL CERT.</th>
                    <th style="width:5%;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th style="width:8%;">OBSERVACIONES</th>
                    <th style="width:2%;">DELETE</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $color_tr=''; $dis=''; $title='title="REQUERIMIENTO"';
                  //$prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  $cert= $this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
                  $monto_cert=0;$valor_mod=0; $valor_delete=0;
                  if(count($cert)!=0){
                    $monto_cert=$cert[0]['certificado'];
                    
                    if($monto_cert==$row['ins_costo_total']){
                      $color_tr='#f9d8e0';
                      $valor_mod=1;
                      $valor_delete=1;
                    }
                    elseif ($monto_cert<$row['ins_costo_total']) {
                      $valor_delete=1;
                    }
                  }
                  $cont++;
                    $tabla .='<tr bgcolor='.$color_tr.'>';
                    $tabla .='<td title='.$row['ins_id'].'>'.$cont.'</td>';
                    $tabla .='<td align=center bgcolor="#ecf9f7" title="CODIGO ACTIVIDAD"><font size=3 color=blue><br>'.$row['prod_cod'].'</font></td>';
                    $tabla .='<td align=center>';
                      if($valor_mod==0 & $valor_delete==0){
                        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>
                                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" >
                                    <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                                  </a>';
                      }
                      elseif($valor_mod==0 & $valor_delete==1){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a>';
                      }

                      $ins_certificado=$this->model_certificacion->verif_insumo_certificados($row['ins_id']);
                      if(count($ins_certificado)!=0){
                        $tabla.='<a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$ins_certificado[0]['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="40" HEIGHT="40"/><br>CERT. POA</a>';
                      } 
                    $tabla.='</td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;" bgcolor="#f1dfb9">'.number_format($monto_cert, 2, ',', '.').'</td>';
                    
                    $tabla.='
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>
                          <td>0</td>';
                    $tabla .= ' 
                      <td style="width:8%;">'.$row['ins_observacion'].'</td>
                      <td style="width:2%;" bgcolor="#f3cbcb">';
                        if($valor_mod==0 & $valor_delete==0){
                          $tabla.='<center><input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/></center>';
                        }
                      $tabla.='
                      </td>';
                        
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                  ?>
                  <script>
                    function scheck<?php echo $cont;?>(estaChequeado) {
                      val = parseInt($('[name="tot"]').val());
                      if (estaChequeado == true) {
                        val = val + 1;
                      } else {
                        val = val - 1;
                      }
                      $('[name="tot"]').val((val).toFixed(0));
                    }
                  </script>
                  <?php
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="8"> TOTAL </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="16"></td>
                  </tr>
              </table>';

      return $tabla;
    }

    /*----- LISTA REQUERIMIENTOS COMPLETO (2020) ------*/
    public function lista_requerimientos($cite){
      $lista_insumos=$this->model_modrequerimiento->lista_requerimientos($cite[0]['com_id']);

      $tabla='';
      $total=0;
      $tabla.=' <input type="hidden" name="proy_id" value="'.$cite[0]['proy_id'].'">
                <input type="hidden" name="aper_id" value="'.$cite[0]['aper_id'].'">
                <input type="hidden" name="cite_id" value="'.$cite[0]['cite_id'].'">
                <input type="hidden" name="base" value="'.base_url().'">
                <table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                  <tr class="modo1">
                    <th style="width:2%;">#</th>
                    <th style="width:2%;">COD. ACT.</th>
                    <th style="width:2%;"></th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:15%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">UNITARIO</th>
                    <th style="width:5%;">TOTAL</th>
                    <th style="width:5%;">TOTAL CERT.</th>
                    <th style="width:5%;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th style="width:8%;">OBSERVACIONES</th>
                    <th style="width:2%;">DELETE</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $color_tr=''; $dis=''; $title='title="REQUERIMIENTO"';
                  $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  $cert= $this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
                  $monto_cert=0;$valor_mod=0; $valor_delete=0;
                  if(count($cert)!=0){
                    $monto_cert=$cert[0]['certificado'];
                    
                      if($monto_cert==$prog[0]['programado_total']){
                        $color_tr='#f9d8e0';
                        $valor_mod=1;
                        $valor_delete=1;
                      }
                      elseif ($monto_cert<$row['ins_costo_total']) {
                        $valor_delete=1;
                      }
                  }
                  $cont++;
                    $tabla .='<tr bgcolor='.$color_tr.'>';
                    $tabla .='<td title='.$row['ins_id'].'>'.$cont.' --'.count($cert).'</td>';
                    $tabla .='<td align=center bgcolor="#ecf9f7" title="CODIGO ACTIVIDAD"><font size=3 color=blue><br>'.$row['prod_cod'].'</font></td>';
                    $tabla .='<td align=center>';
                      if($valor_mod==0 & $valor_delete==0){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>
                                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" >
                                    <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                                  </a>';
                      }
                      elseif($valor_mod==0 & $valor_delete==1){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a>';
                      }

                      $ins_certificado=$this->model_certificacion->verif_insumo_certificados($row['ins_id']);
                      if(count($ins_certificado)!=0){
                        $tabla.='<a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$ins_certificado[0]['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="40" HEIGHT="40"/><br>CERT. POA</a>';
                      }
                    $tabla.='</td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;" bgcolor="#f1dfb9">'.number_format($monto_cert, 2, ',', '.').'</td>';

                    if(count($prog)!=0){
                      $tabla.='
                      <td style="width:5%;">'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td>';
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td style="width:5%;" bgcolor="#eaf9f7">'.number_format($prog[0]['mes'.$i], 2, ',', '.').'</td>';
                      }
                    }
                    else{
                      $tabla.='
                      <td style="width:5%;">0</td>';
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td style="width:5%;" bgcolor="#ffeeeb">0</td>';
                      }
                    }
                    
                    $tabla .= ' 
                      <td style="width:8%;">'.$row['ins_observacion'].'</td>
                      <td style="width:2%;" bgcolor="#f3cbcb">';
                        if($valor_mod==0 & $valor_delete==0){
                          $tabla.='<center><input type="checkbox" name="ins[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/></center>';
                        }
                      $tabla.='
                      </td>';
                        
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                  ?>
                  <script>
                    function scheck<?php echo $cont;?>(estaChequeado) {
                      val = parseInt($('[name="tot"]').val());
                      if (estaChequeado == true) {
                        val = val + 1;
                      } else {
                        val = val - 1;
                      }
                      $('[name="tot"]').val((val).toFixed(0));
                    }
                  </script>
                  <?php
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="8"> TOTAL </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="16"></td>
                  </tr>
              </table>';

      return $tabla;
    }


    /*--- VALIDA ADD REQUERIMIENTO (2023) ---*/
    public function valida_add_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// cite id
        $detalle = $this->security->xss_clean($post['ins_detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['ins_cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['ins_costo_u']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costo']); /// costo Total
        $um_id = $this->security->xss_clean($post['ins_um']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['partida_id']); /// partida id
        $observacion = $this->security->xss_clean($post['ins_observacion']); /// Observacion
        $id = $this->security->xss_clean($post['dato_id']); /// Alineacion id Producto, Actividad
        $producto=$this->model_producto->get_producto_id($id); /// Get producto
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id);
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// DATOS DEL PROYECTO
        $umedida=$this->model_insumo->get_unidadmedida($um_id);

          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
          'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
          'ins_costo_unitario' => $costo_unitario, /// Costo Unitario
          'ins_costo_total' => $costo_total, /// Costo Total
          'ins_unidad_medida' => $umedida[0]['um_descripcion'], /// Insumo Unidad de Medida
          'ins_gestion' => $this->gestion, /// Insumo gestion
          'par_id' => $partida, /// Partidas
          'ins_tipo' => 1, /// Ins Tipo
          'ins_observacion' => strtoupper($observacion), /// Observacion
          'fun_id' => $this->fun_id, /// Funcionario
          'aper_id' => $proyecto[0]['aper_id'], /// aper id
          'com_id' => $producto[0]['com_id'], /// com id 
          'form4_cod' => $producto[0]['prod_cod'], /// aper id
          'ins_mod' => 2, /// mod
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          /*-----------------------------------------------*/
            $data_to_store2 = array( ///// Tabla InsumoProducto
              'prod_id' => $id, /// prod id
              'ins_id' => $ins_id, /// ins_id
            );
            $this->db->insert('_insumoproducto', $data_to_store2);
            /*---------------------------------------------*/

          /*------------ PARA LA GESTION 2020 ---------*/
          for ($i=1; $i <=12 ; $i++) {
            $pfin=$this->security->xss_clean($post['m'.$i]);
            if($pfin!=0){
                $data_to_store4 = array( 
                  'ins_id' => $ins_id, /// Id Insumo
                  'mes_id' => $i, /// Mes 
                  'ipm_fis' => $pfin, /// Valor mes
                  'g_id' => $this->gestion, /// Gestion 
                );
                $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
            }
          }
          /*------------------------------------------*/

          /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
          if($this->copia_insumo($cite_id,$ins_id,1)){ /// inserta historial reporte
            /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
              $this->update_activo_modificacion($cite_id);
              $this->session->set_flashdata('success','EL REQUERIMIENTO SE REGISTRO CORRECTAMENTE :)');
            /*--------------------------------------*/
          }
          else{
            $this->session->set_flashdata('danger','EL REQUERIMIENTO NOSE REGISTRO CORRECTAMENTE, VERIFIQUE DATOS :(');
          }

          redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
      }
      else{
        echo "Error en el Registro !!!";
      }
    }



    /*----- UPDATE ESTADO ACTIVO DE LA MODIFICACION ------*/
    function update_activo_modificacion($cite_id){
      $update_cite= array(
        'cite_activo' => 1,
        'tp_reporte' => 1, /// nuevo reporte
        'fun_id'=>$this->fun_id
      );
      $this->db->where('cite_id', $cite_id);
      $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
    }


     /*--- VALIDA UPDATE REQUERIMIENTO (2023) ---*/
     public function valida_update_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
        $cite_id = $this->security->xss_clean($post['cite_id']); /// cite id

        //$insumo = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL REQUERIMIENTO
        $insumo = $this->model_insumo->get_requerimiento($ins_id); //// DATOS DEL REQUERIMIENTO
        if(count($this->model_certificacion->get_insumo_monto_certificado($ins_id))!=0){ /// Cuando ya esta certificado
          $detalle = $insumo[0]['ins_detalle']; /// detalle
        //  $costo_unitario = $insumo[0]['ins_costo_unitario']; /// costo unitario
          $unidad = $insumo[0]['ins_unidad_medida']; /// Unidad de medida
          $partida = $insumo[0]['par_id']; /// costo unitario
          $observacion = $insumo[0]['ins_observacion']; /// Observacion
        }
        else{ /// Aun no esta certificado
          $detalle = $this->security->xss_clean($post['detalle']); /// detalle
        //  $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario
          $unidad = $this->security->xss_clean($post['umedida']); /// Unidad de medida
          $partida = $this->security->xss_clean($post['par_hijo']); /// costo unitario
          $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
        }
        
        $cantidad = $this->security->xss_clean($post['cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario /// temporal
        $costo_total = $this->security->xss_clean($post['costot']); /// costo Total
        $id = $this->security->xss_clean($post['id']); /// id : prod,act
        $producto=$this->model_producto->get_producto_id($id); /// Get producto

        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id);
        if($cite[0]['tp_id']==1){
          $actividades=$this->model_modrequerimiento->list_actividades_componente($cite[0]['com_id']);
          $id_anterior=$actividades[0]['act_id'];
        }
        else{
          $operaciones=$this->model_producto->lista_operaciones($cite[0]['com_id']);
          $id_anterior=$operaciones[0]['prod_id'];
        }

          if($this->registra_insumo_original($cite_id,$ins_id)){
            

            $update_ins= array(
              'ins_cant_requerida' => $cantidad,
              'ins_costo_unitario' => $costo_unitario,
              'ins_costo_total' => $costo_total,
              'ins_detalle' => $detalle,
              'par_id' => $partida, /// Partidas
              'ins_unidad_medida' => $unidad,
              'ins_observacion' => $observacion,
              'fun_id' => $this->fun_id,
              'com_id' => $cite[0]['com_id'], /// com id 
              'form4_cod' => $producto[0]['prod_cod'], /// aper id
              'ins_mod' => 2, /// mod
              'ins_estado'=> 2, /// mod
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
            );
            $this->db->where('ins_id', $ins_id);
            $this->db->update('insumos', $this->security->xss_clean($update_ins));

              for ($i=1; $i <=12 ; $i++) {
                if(count($this->model_certificacion->get_insumo_programado_certificado_mes($ins_id,$i))==0){
                  if(!is_null ($post['mm'.$i])){
                    $verif_mes=$this->model_modrequerimiento->get_mes_item($ins_id,$i);
                    if(count($verif_mes)!=0){
                      $pfin=$this->security->xss_clean($post['mm'.$i]);
                      if($pfin==0){
                        /*----------------- ELIMINA IFIN PROG MES---------------*/
                          $this->db->where('ins_id', $ins_id);
                          $this->db->where('mes_id', $i);
                          $this->db->delete('temporalidad_prog_insumo');
                        /*------------------------------------------------------*/
                      }
                      else{
                        /*----------------- UPDATE IFIN PROG MES---------------*/
                          $update_ifin = array(
                            'ipm_fis' => $pfin
                          );
                          $this->db->where('mes_id', $i);
                          $this->db->where('ins_id', $ins_id);
                          $this->db->update('temporalidad_prog_insumo', $update_ifin);
                        /*------------------------------------------------------*/
                      }

                    }
                    else{
                      $pfin=$this->security->xss_clean($post['mm'.$i]);
                      if($pfin!=0){
                          $data_to_store4 = array( 
                            'ins_id' => $ins_id, /// Id Insumo
                            'mes_id' => $i, /// Mes 
                            'g_id' => $this->gestion, /// gestion 
                            'ipm_fis' => $pfin, /// Valor mes
                            'g_id' => $this->gestion, /// Gestion 
                          );
                          $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                      }
                    }
                  }
                }
              }

              $update_proy = array(
                'prod_id' => $id,
              );
              $this->db->where('ins_id', $ins_id);
              $this->db->update('_insumoproducto', $update_proy);

              $this->copia_insumo($cite_id,$ins_id,2); /// historial de modificaciones para el reporte

              /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
              $this->update_activo_modificacion($cite_id);
              /*--------------------------------------*/

            $this->session->set_flashdata('success','EL REQUERIMIENTO SE MODIFICO CORRECTAMENTE :)');
            redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
        }
        else{
          echo "Error al Copiar Datos ...";
        }

      } else {
          show_404();
      }
    }


    /*--- VALIDA DATOS DEL REQUERIMIENTO CERTIFICADO (2020) ---*/
    public function valida_update_insumo_cpoa(){
      if ($this->input->post()) {
          $post = $this->input->post();

          $ins_id = $this->security->xss_clean($post['ins_id']); /// ins_id
          $cpoaa_id = $this->security->xss_clean($post['cpoaa_id']); /// cpoaa_id de la anulacion

          $cert_editado=$this->model_certificacion->get_cert_poa_editado($cpoaa_id); /// Datos de la Certificacion Anulado
          $cpoa=$this->model_certificacion->get_certificacion_poa($cert_editado[0]['cpoa_id']); /// Datos de la Certificacion POA
          $detalle_cert=$this->model_certificacion->get_certificado_poa_detalle($cpoa[0]['cpoa_id'],$ins_id); /// item certificado

          //$insumo= $this->minsumos->get_requerimiento($ins_id); /// Datos requerimientos 
          $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos 
          if($this->registra_insumo_original($cert_editado[0]['cite_id'],$ins_id)){
          //if($this->copia_insumo($cert_editado[0]['cite_id'],$ins_id,2)){
              ///------ cambiando de estado de certificacion poa la temporalidad
              $get_list_temp_prog=$this->model_certificacion->get_list_cert_temporalidad_prog_insumo($detalle_cert[0]['cpoad_id']);
            //  $suma_cert=0;

              foreach($get_list_temp_prog as $row){
                $datos_temp=$this->model_certificacion->get_id_insumo_programado_mes($row['tins_id']);
              //  $suma_cert=$suma_cert+$datos_temp[0]['ipm_fis'];

                $update_ins= array(
                  'ins_monto_certificado' => ($insumo[0]['ins_monto_certificado']-$datos_temp[0]['ipm_fis']),
                  'fun_id' => $this->fun_id
                );
                $this->db->where('ins_id', $ins_id);
                $this->db->update('insumos', $this->security->xss_clean($update_ins));

                /// Actualizando el estado de la temporalidad
                $update_temp = array(
                  'estado_cert' => 0
                );
                $this->db->where('tins_id', $row['tins_id']);
                $this->db->update('temporalidad_prog_insumo', $update_temp);
              }
              ///---------------------------------------------------------------

              /*-------- Elimina Los items certificados --------*/
              $this->db->where('cpoad_id', $detalle_cert[0]['cpoad_id']);
              $this->db->delete('cert_temporalidad_prog_insumo');
              /*------------------------------------------------*/

            if(count($this->model_certificacion->verif_insumo_certificado($ins_id))==1){
                $detalle = $this->security->xss_clean($post['detalle']); /// Detalle
                $unidad = $this->security->xss_clean($post['umedida']);  /// Unidad de Medida

                $update_ins= array(
                'ins_detalle' => $detalle,
                'ins_unidad_medida' => $unidad,
                'fun_id' => $this->fun_id,
                'ins_mod' => 2, /// mod
                'ins_estado'=> 2, /// mod
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
              $this->db->where('ins_id', $ins_id);
              $this->db->update('insumos', $this->security->xss_clean($update_ins));
            }


            for ($i=1; $i <=12 ; $i++) {
                if(count($this->model_certificacion->get_insumo_programado_certificado_mes($ins_id,$i))==0){
                  if(!is_null ($post['mm'.$i])){
                    $verif_mes=$this->model_modrequerimiento->get_mes_item($ins_id,$i);
                    if(count($verif_mes)!=0){
                      $pfin=$this->security->xss_clean($post['mm'.$i]);
                      if($pfin==0){
                        /*----------------- ELIMINA IFIN PROG MES---------------*/
                          $this->db->where('ins_id', $ins_id);
                          $this->db->where('mes_id', $i);
                          $this->db->delete('temporalidad_prog_insumo');
                        /*------------------------------------------------------*/
                      }
                      else{
                        /*----------------- UPDATE IFIN PROG MES---------------*/
                          $update_ifin = array(
                            'ipm_fis' => $pfin
                          );
                          $this->db->where('mes_id', $i);
                          $this->db->where('ins_id', $ins_id);
                          $this->db->update('temporalidad_prog_insumo', $update_ifin);
                        /*------------------------------------------------------*/
                      }

                    }
                    else{
                      $pfin=$this->security->xss_clean($post['mm'.$i]);
                      if($pfin!=0){
                          $data_to_store4 = array( 
                            'ins_id' => $ins_id, /// Id Insumo
                            'mes_id' => $i, /// Mes 
                            'ipm_fis' => $pfin, /// Valor mes
                            'g_id' => $this->gestion, /// Gestion 
                          );
                          $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                      }
                    }
                  }
                }
              }


              $this->copia_insumo($cert_editado[0]['cite_id'],$ins_id,2); /// historial de modificaciones para el reporte

              /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
              $this->update_activo_modificacion($cert_editado[0]['cite_id']);
              /*--------------------------------------*/


              $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE');
              redirect('cert/edit_certificacion/'.$cpoaa_id.'');

          }
          else{
            echo "Error al Copiar Datos ...";
          }

      } else {
          show_404();
      }
    }



    /*------ ELIMINAR REQUERIMIENTO ------*/
    function delete_requerimiento(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
         $post = $this->input->post();
          $cite_id = $post['cite_id']; /// Cite Id
          $ins_id = $post['ins_id']; /// Insumo Id
          $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id);
   
          if($this->copia_insumo($cite_id,$ins_id,3)){

            /*--- Update estado del Insumo ---*/
            $update_ins = array(
              'ins_estado' => 3, /// 3 : Eliminado
              'ins_mod' => 2, /// 2 : Modulo Modificaciones
              'aper_id' => 0, /// 2 : aper
              'com_id' => 0, /// 2 : com_id
              'form4_cod' => 0, /// 2 : cod. formulario n4
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->fun_id
              );
            $this->db->where('ins_id', $ins_id);
            $this->db->update('insumos', $update_ins);
            /*------------------------------- -*/

              /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
                $this->update_activo_modificacion($cite_id);
              /*--------------------------------------*/

              $result = array(
                'respuesta' => 'correcto'
              );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }

          echo json_encode($result);
      }
    }

    /*-- ELIMINAR VARIOS REQUERIMIENTOS SELECCIONADOS --*/
    public function delete_select_requerimientos(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']);
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id);
        $si=0;$no=0;
        if (!empty($_POST["ins"]) && is_array($_POST["ins"]) ) {
          foreach ( array_keys($_POST["ins"]) as $como){
            if($this->copia_insumo($cite_id,$_POST["ins"][$como],3)){

            /*--- Update estado del Insumo ---*/
            $update_ins = array(
              'ins_estado' => 3, /// 3 : Eliminado
              'ins_mod' => 2, /// 2 : Modulo Modificaciones
              'aper_id' => 0, /// 2 : aper
              'com_id' => 0, /// 2 : com_id
              'form4_cod' => 0, /// 2 : cod. formulario n4
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->fun_id
              );
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->update('insumos', $update_ins);
            /*------------------------------- -*/

            /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
              $this->update_activo_modificacion($cite_id);
            /*--------------------------------------*/

              if(count($this->model_insumo->get_requerimiento($_POST["ins"][$como]))==0){
                $si++;
              }
              else{
                $no++;
              }
            }
            else{
              $no++;
            }
          }
        }

        $this->session->set_flashdata('success','SE ELIMINARON : '.$si.' REQUERIMIENTOS');
        redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
      }
      else{
        echo "Error !!!";
      }
    }


    /*------------- REPORTE MODIFICACION DE REQUERIMIENTOS -------------*/
      public function reporte_modificacion_financiera($cite_id){
      $data['cite']=$this->model_modrequerimiento->get_cite_insumo($cite_id);
      if(count($data['cite'])!=0){ /// Nuevo formato de Reporte
        if($this->fecha_entrada<strtotime($data['cite'][0]['cite_fecha'])){
          $data['cabecera_modpoa']=$this->modificacionpoa->cabecera_modpoa($data['cite'],2);

          if($data['cite'][0]['tp_reporte']==0){ /// rep anterior
            $data['items_modificados']=$this->modificacionpoa->items_modificados_form5($cite_id); /// anterior reporte
          }
          else{
           $data['items_modificados']=$this->modificacionpoa->items_modificados_form5_historial($cite_id,1); //// Nuevo Reporte
          }
          
          $data['pie_mod']=$this->modificacionpoa->pie_modpoa($data['cite'],$data['cite'][0]['cite_codigo']);
          $data['pie_rep']='MOD_POA_FORM5_'.$data['cite'][0]['cite_nota'].' de '.date('d-m-Y',strtotime($data['cite'][0]['cite_fecha'])).' - '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].' - '.$data['cite'][0]['tipo_adm'].' '.$data['cite'][0]['act_descripcion'].' '.$data['cite'][0]['abrev'].'/'.$this->gestion.'';

          $this->load->view('admin/modificacion/moperaciones/reporte_modificacion_poa_form4', $data); 
        }
        else{ /// Formato Antiguo de Reporte 2020
          $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']); 
          if($data['proyecto'][0]['tp_id']==1){
            $titulo='
                    <tr style="font-size: 8pt;">
                      <td style="height: 1.2%"><b>PROYECTO</b></td>
                      <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="height: 1.2%"><b>UNIDAD RESPONSABLE</b></td>
                      <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                    </tr>';
          }
          else{
            $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);
            $titulo='       
                    <tr style="font-size: 8pt;">
                      <td style="height: 1.2%"><b>'.$data['proyecto'][0]['tipo_adm'].' </b></td>
                      <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' '.$data['proyecto'][0]['tipo'].'   '.strtoupper($data['proyecto'][0]['act_descripcion']).' '.$data['proyecto'][0]['abrev'].'</td>
                    </tr>
                    <tr style="font-size: 8pt;">
                        <td style="height: 1.2%"><b>SUBACTIVIDAD</b></td>
                        <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                     </tr>';
          }

          $data['titulo']=$titulo;
          $data['mes'] = $this->mes_nombre();
          $data['requerimientos']=$this->rep_requerimiento($cite_id); /// listado antiguo
          $this->load->view('admin/modificacion/requerimientos/reporte_modificacion_requerimientos', $data);
        }
      }
      else{
        echo "Error !!!";
      }
    }











    /*------- LISTA DE REQUERIMIENTOS MODIFICADOS (2020) -------*/
    public function rep_requerimiento($cite_id){
      $tabla ='';
      $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
      $requerimientos_add = $this->model_modrequerimiento->list_requerimientos_adicionados($cite_id);
      
      if(count($requerimientos_add)!=0){
        $tabla.='<div style="font-size: 12px;font-family: Arial;">ITEMS AGREGADOS ('.count($requerimientos_add).')</div>';
        $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" align="center">';
          $tabla.='<th style="width:1%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
          $tabla.='<th style="width:7.5%;background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_add as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1%; text-align: center;" style="height:11px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            if(count($prog)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width: 4.5%; text-align: right;" bgcolor=red>-</td>';
              }
            }
            $tabla.='<td style="width: 7.5%; text-align: left;">'.$row['ins_observacion'].'</td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:10px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table><br>';
      }
      

      $requerimientos_mod = $this->model_modrequerimiento->list_requerimientos_modificados($cite_id);
      if(count($requerimientos_mod)!=0){
        $tabla.='<div style="font-size: 12px;font-family: Arial;">ITEMS MODIFICADOS ('.count($requerimientos_mod).')</div>';
        $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" style="text-align: center;">';
          $tabla.='<th style="width:1%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
          $tabla.='<th style="width:7.5%;background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_mod as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1%; text-align: center;" style="height:11px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            if(count($prog)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width: 4.5%; text-align: right;" border=red>-</td>';
              }
            }
            $tabla.='<td style="width: 7.5%; text-align: left;">'.$row['ins_observacion'].'</td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:10px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table><br>';
      }
      
      $requerimientos_del = $this->model_modrequerimiento->list_requerimientos_eliminados($cite_id);
      if(count($requerimientos_del)!=0){
        $tabla.='<div style="font-size: 12px;font-family: Arial;">ITEMS ELIMINADOS ('.count($requerimientos_del).')</div>';
        $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" style="text-align: center;">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>OPE.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
          $tabla.='<th style="width:7.5%;background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_del as $row){
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1.3%; text-align: center;" style="height:11px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            for ($i=1; $i <=12 ; $i++) { 
              $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($row['mes'.$i], 2, ',', '.') . '</td>';
            }
          $tabla.='<td style="width: 7.5%; text-align: left;">'.$row['ins_observacion'].'</td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:10px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table><br>';
      }

      $tabla.='';
      $tabla.='<br>
      <div style="font-size: 8px;font-family: Arial;">
      En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

      &nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
      &nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
      &nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
      &nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
      </div>';
      return $tabla;
    }


    /*------- LISTA DE REQUERIMIENTOS MODIFICADOS (UPDATE)(2020-2021-2022) -------*/
    public function rep_requerimiento_update($cite_id){
      $tabla ='';
      $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
      $requerimientos_add = $this->model_modrequerimiento->list_requerimientos_adicionados($cite_id);
      
      if(count($requerimientos_add)!=0){

        $tabla.='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-darken">
                      <header>
                        <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                          <h2 class="font-md"><strong>ITEMS AGREGADOS ('.count($requerimientos_add).')</strong></h2>  
                      </header>
                    <div>
                    <div class="widget-body no-padding">';
        $tabla.='<table id="dt_basic1" class="table1 table-bordered" style="width:100%;" border="0.2">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" align="center">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_add as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1.3%; text-align: center;" style="height:11px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            if(count($prog)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width: 4.5%; text-align: right;" bgcolor=red>-</td>';
              }
            }
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:11px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table>
        </div>
      </div>
      </div>
      </article><br>';
      }
      

      $requerimientos_mod = $this->model_modrequerimiento->list_requerimientos_modificados($cite_id);
      if(count($requerimientos_mod)!=0){

        $tabla.='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget jarviswidget-color-darken">
                      <header>
                        <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                          <h2 class="font-md"><strong>ITEMS MODIFICADOS ('.count($requerimientos_mod).')</strong></h2>  
                      </header>
                    <div>
                    <div class="widget-body no-padding">';
        $tabla.='<table id="dt_basic" class="table table-bordered" style="width:100%;" border="0.2">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" style="text-align: center;">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_mod as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1.3%; text-align: center; height:15px;" title='.$row['ins_id'].'>'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            if(count($prog)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes'.$i], 2, ',', '.') . '</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width: 4.5%; text-align: right;" bgcolor=red>-</td>';
              }
            }
            $tabla.='
            <td style="width: 2%; text-align: left;">
              
            </td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:11px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table>
        </div>
      </div>
      </div>
      </article><br>';
      }
      
      $requerimientos_del = $this->model_modrequerimiento->list_requerimientos_eliminados($cite_id);
      if(count($requerimientos_del)!=0){

        $tabla.='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken">
              <header>
                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md"><strong>ITEMS ELIMINADOS ('.count($requerimientos_del).')</strong></h2>  
              </header>
            <div>
            <div class="widget-body no-padding">';
        $tabla.='<table id="dt_basic3" class="table1 table-bordered" style="width:100%;" border="0.2">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" style="text-align: center;">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF;height:45px;">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_del as $row){
          $nro++;
          $tabla.='<tr class="modo1">';
            $tabla.='<td style="width: 1.3%; text-align: center;height:8px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
            for ($i=1; $i <=12 ; $i++) { 
              $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($row['mes'.$i], 2, ',', '.') . '</td>';            
            }

          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:11px;" colspan=7></td>
            <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
            <td colspan=13></td>
          </tr>
        </table>
        </div>
      </div>
      </div>
      </article><br>';
      }

      return $tabla;
    }

    /*-------- GET CUADRO COMPARATIVO ASIGNADO-POA --------*/
    public function get_comparativo_ptto(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO

        $tabla='<hr><iframe id="ipdf" width="100%"  height="900px;" src="'.base_url().'index.php/proy/ptto_consolidado_comparativo/'.$proy_id.'"></iframe>';
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

     /*----- MIGRACION DE REQUERIMIENTOS A UNA OPERACIÓN (2019) -----*/
    function valida_add_requerimientos2(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $this->security->xss_clean($post['cite_id']);
          $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
          $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// DATOS DEL PROYECTO

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);

              $i=0;
              $nro=0;
              foreach ($lineas as $linea_num => $linea){ /// A
                if($i != 0){ /// B
                  $datos = explode(";",$linea);
                  if(count($datos)==20){ /// C
                      $cod_ope = (int)$datos[0]; //// Codigo Actividad
                      $cod_partida = (int)$datos[1]; //// Codigo partida
                      $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// Datos Partida

                      $detalle = utf8_encode(trim($datos[2])); //// Detalle Requerimiento
                      $unidad = utf8_encode(trim($datos[3])); //// Unidad de medida
                      $cantidad = (int)$datos[4]; //// Cantidad
                      $unitario = (float)$datos[5]; //// Costo Unitario
                      $total=round(($cantidad*$unitario),2); // Costo Total

                      $var=7; $sum_prog=0;
                      for ($i=1; $i <=12 ; $i++) {
                        $m[$i]=(float)$datos[$var]; //// Mes i
                        if($m[$i]==''){
                          $m[$i]=0;
                        }
                        $var++;
                        $sum_prog=$sum_prog+$m[$i];
                      }
                      $observacion = utf8_encode(trim($datos[19])); //// Observacion
                      $verif_operacion=$this->model_producto->verif_componente_operacion($cite[0]['com_id'],$cod_ope);
                     // echo count($par_id).'--'.$cod_partida.'-- '.($total==$sum_prog)." --".count($verif_operacion)."<br>";
                      if(count($par_id)!=0 & $cod_partida!=0 & ($total==$sum_prog) & count($verif_operacion)!=0){ /// D

                        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id[0]['par_id']); /// Ppto. Asignado
                        if(count($asig)!=0){ /// Verificando que haya presupuesto distinto a cero
                          $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id[0]['par_id']); /// Ppto. Programado
                          $monto_prog=0;
                          if(count($prog)!=0){
                            $monto_prog=$prog[0]['monto'];
                          }

                          $saldo_partida=$asig[0]['monto']-$monto_prog+$asig[0]['ppto_saldo_ncert'];

                          if($total<=$saldo_partida){ /// E
                            $nro++;
                            echo $detalle."<br>";
                            /*-------- Insert Insumos Nuevos -------*/
                            /*$query=$this->db->query('set datestyle to DMY');
                            $data_to_store = array( 
                            'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                            'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                            'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                            'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                            'ins_costo_unitario' => $unitario, /// Costo Unitario
                            'ins_costo_total' => $total, /// Costo Total
                            'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                            'par_id' => $par_id[0]['par_id'], /// Partidas
                            'ins_tipo' => 1, /// Ins Tipo
                            'ins_observacion' => strtoupper($observacion), /// Observacion
                            'fun_id' => $this->fun_id, /// Funcionario
                            'ins_gestion' => $this->gestion, /// Gestion
                            'aper_id' => $proyecto[0]['aper_id'], /// aper id
                            'num_ip' => $this->input->ip_address(), 
                            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                            'ins_mod' => 2,
                            );
                            $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                            $ins_id=$this->db->insert_id();*/
                            /*--------------------------------------*/
                            /*--------------------------------------*/
                            /*$data_to_store2 = array( ///// Tabla InsumoProducto
                              'prod_id' => $verif_operacion[0]['prod_id'], /// prod id
                              'ins_id' => $ins_id, /// ins_id
                            );
                            $this->db->insert('_insumoproducto', $data_to_store2);*/
                            //--------------------------------------*/

                            /*------ PARA LA GESTION 2020 ------*/
                            /*for ($p=1; $p <=12 ; $p++) { 
                              if($m[$p]!=0){
                               $data_to_store4 = array( 
                                  'ins_id' => $ins_id, /// Id Insumo
                                  'mes_id' => $p, /// Mes 
                                  'ipm_fis' => $m[$p], /// Valor mes
                                  'g_id' => $this->gestion, /// Gestion 
                                );
                                $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                              }
                            }*/
                            /*----------------------------------*/

                            /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
                             /* $data_to_store2 = array(
                                'ins_id' => $ins_id, /// ins_id
                                'cite_id' => $cite_id, /// cite_id
                                'num_ip' => $this->input->ip_address(), 
                                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                                'fun_id' => $this->session->userdata("fun_id"),
                                );
                              $this->db->insert('insumo_add', $data_to_store2);
                              $add_id=$this->db->insert_id();*/
                            /*---------------------------------------*/
                          } /// E
                        }
                      } /// D

                  } /// C
                } /// B
                $i++;
              } /// A

             // $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
             // redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
          }
          else{
            echo "Error !!!";
          }
      }
      else{
        echo "Error !!!!";
      }
    }

     /*----- MIGRACION DE REQUERIMIENTOS A UNA OPERACIÓN (2019) -----*/
    function valida_add_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $this->security->xss_clean($post['cite_id']);
          $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
          $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// DATOS DEL PROYECTO

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);

              $i=0;
              $nro=0;
              foreach ($lineas as $linea_num => $linea){ /// A
                if($i != 0){ /// B
                  $datos = explode(";",$linea);
                  if(count($datos)==20){ /// C
                      $cod_ope = intval(trim($datos[0])); //// Codigo Actividad
                      $cod_partida = intval(trim($datos[1])); //// Codigo partida
                      $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                      $detalle = strval(utf8_encode(trim($datos[2]))); //// descripcion form5
                      $unidad = strval(utf8_encode(trim($datos[3]))); //// Unidad
                      $cantidad = intval(trim($datos[4])); //// Cantidad
                      $unitario = floatval(trim($datos[5])); //// Costo Unitario
                      
                      $p_total=($cantidad*$unitario);
                      $total = floatval(trim($datos[6])); //// Costo Total

                      $var=7; $sum_prog=0;
                      for ($i=1; $i <=12 ; $i++) {
                        $m[$i]=floatval(trim($datos[$var])); //// Mes i
                        if($m[$i]==''){
                          $m[$i]=0;
                        }
                        $var++;
                        $sum_prog=$sum_prog+$m[$i];
                      }
                      $observacion = utf8_encode(trim($datos[19])); //// Observacion
                      $verif_operacion=$this->model_producto->verif_componente_operacion($cite[0]['com_id'],$cod_ope);

                      if(count($par_id)!=0 & $cod_partida!=0 & ($total==$sum_prog) & count($verif_operacion)!=0){ /// D
                        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id[0]['par_id']); /// Ppto. Asignado
                        if(count($asig)!=0){ /// Verificando que haya presupuesto distinto a cero
                          $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id[0]['par_id']); /// Ppto. Programado
                          $monto_prog=0;
                          if(count($prog)!=0){
                            $monto_prog=$prog[0]['monto'];
                          }

                          $saldo_partida=$asig[0]['monto']-$monto_prog+$asig[0]['ppto_saldo_ncert'];

                          if($total<=$saldo_partida){ /// E
                            
                            /*-------- Insert Insumos Nuevos -------*/
                            $query=$this->db->query('set datestyle to DMY');
                            $data_to_store = array( 
                            'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                            'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                            'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                            'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                            'ins_costo_unitario' => $unitario, /// Costo Unitario
                            'ins_costo_total' => $total, /// Costo Total
                            'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                            'par_id' => $par_id[0]['par_id'], /// Partidas
                            'ins_tipo' => 1, /// Ins Tipo
                            'ins_observacion' => strtoupper($observacion), /// Observacion
                            'fun_id' => $this->fun_id, /// Funcionario
                            'ins_gestion' => $this->gestion, /// Gestion
                            'aper_id' => $proyecto[0]['aper_id'], /// aper id
                            'com_id' => $cite[0]['com_id'], /// com id 
                            'form4_cod' => $cod_ope, /// cod act
                            'num_ip' => $this->input->ip_address(), 
                            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                            'ins_mod' => 2,
                            );
                            $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                            $ins_id=$this->db->insert_id();
                            /*--------------------------------------*/
                            /*--------------------------------------*/
                            $data_to_store2 = array( ///// Tabla InsumoProducto
                              'prod_id' => $verif_operacion[0]['prod_id'], /// prod id
                              'ins_id' => $ins_id, /// ins_id
                            );
                            $this->db->insert('_insumoproducto', $data_to_store2);
                            //--------------------------------------*/

                            /*------ PARA LA GESTION 2020 ------*/
                            for ($p=1; $p <=12 ; $p++) { 
                              if($m[$p]!=0){
                               $data_to_store4 = array( 
                                  'ins_id' => $ins_id, /// Id Insumo
                                  'mes_id' => $p, /// Mes 
                                  'ipm_fis' => $m[$p], /// Valor mes
                                  'g_id' => $this->gestion, /// Gestion 
                                );
                                $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                              }
                            }
                            /*----------------------------------*/

                            if($this->copia_insumo($cite_id,$ins_id,1)){ /// inserta historial reporte
                              $nro++;
                            }

                            /*---- iNSERT AUDI ADICIONAR INSUMOS ---*/
                              $this->update_activo_modificacion($cite_id);
                            /*--------------------------------------*/
                          } /// E
                        }
                      } /// D

                  } /// C
                } /// B
                $i++;
              } /// A

              $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
              redirect(site_url("").'/mod/list_requerimientos/'.$cite_id.'');
          }
          else{
            echo "Error !!!";
          }
      }
      else{
        echo "Error !!!!";
      }
    }

    /*--- CERRAR MODIFICACION FIN (2020) ---*/
     public function cerrar_modificacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite

        $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
        if(count($verificando)==0){ // Creando campo para la distrital
          $data_to_store2 = array(
            'dist_id' => $cite[0]['dist_id'], /// dist_id
            'g_id' => $this->gestion, /// gestion
            'mod_ope' => 0, 
            'mod_req' => 0,
            );
          $this->db->insert('conf_modificaciones_distrital', $data_to_store2);
          $mod_id=$this->db->insert_id();
        }

        if($cite[0]['cite_estado']==0){ /// Pendiente, Insert Codigo
          $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
          $nro_mod=$verificando[0]['mod_req']+1;
          $nro_cdep='';
          if($nro_mod<10){
            $nro_cdep='000';
          }
          elseif($nro_mod<100) {
            $nro_cdep='00';
          }
          elseif($nro_mod<1000){
            $nro_cdep='0';
          }

          /*--------------- Update cite ---------------*/
          $update_cite= array(
            'cite_codigo' => 'R_'.$cite[0]['adm'].'-'.$cite[0]['abrev'].'-'.$nro_cdep.''.$nro_mod,
            'cite_observacion' => strtoupper($observacion),
            'cite_estado' => 1,
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
          /*------------------------------------------*/

          /*----- Update Configuracion mod distrital -----*/
          $update_conf= array(
            'mod_req' => $nro_mod
          );
          $this->db->where('mod_id', $verificando[0]['mod_id']);
          $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
          /*----------------------------------------------*/
        }
        else{ /// Cerrado, Update Observacion
          $update_cite= array(
            'cite_observacion' => strtoupper($observacion),
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
        }

        /*----------- redireccionar -------*/
        $this->session->set_flashdata('success','SE CERRO CORRECTAMENTE LA MODIFICACIÓN FINANCIERA');
        redirect(site_url("").'/mod/ver_mod_poa/'.$cite_id.'');

      }
      else{
        echo "Error !!!";
      }
    }


    /*--- MODIFICAR CITE REQUERIMIENTO ---*/
    public function modificar_cite($cite_id){
      $data['cite'] = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
      if(count($data['cite'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['titulo']=$this->modificacionpoa->titulo_cabecera($data['cite']); /// CABECERA
        $data['datos_cite']=$this->modificacionpoa->datos_cite($data['cite']); /// DATOS CITE

        if($data['cite'][0]['tp_reporte']==1){
          $data['items_modificados']=$this->modificacionpoa->items_modificados_form5_historial($cite_id,0); /// listado de items modificados 2023 (historial)
        }
        else{
          $data['items_modificados']=$this->rep_requerimiento_update($cite_id); /// listado de items modificados
        }
        
        $this->load->view('admin/modificacion/requerimientos/update_cite', $data);
      }
      else{
        redirect(site_url("").'/mod/list_cites/'.$data['cite'][0]['proy_id'].'');
      }
    }


    /*--- VER MODIFICACION POA---*/
    public function ver_modificacion_poa($cite_id){
      $data['cite'] = $this->model_modrequerimiento->get_cite_insumo($cite_id); // Datos Cite
      if(count($data['cite'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['titulo']=$this->modificacionpoa->titulo_cabecera($data['cite']); /// CABECERA
        $data['datos_cite']=$this->modificacionpoa->datos_cite($data['cite']); /// DATOS CITE

        $this->load->view('admin/modificacion/requerimientos/ver_modificado_poa', $data);
      }
      else{
        redirect(site_url("").'/mod/list_cites/'.$data['cite'][0]['proy_id'].'');
      }
    }


    /* ======== FUNCIONES COMPLEMENTARIAS ======= */

    /*------- Quitar modificación del Cite ------*/
    function quitar_requerimiento_cite(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $id = $this->security->xss_clean($post['id']); /// insh_id 
         // $tp = $this->security->xss_clean($post['tp']); /// Tp Id : add,mod,del


          $update_mod = array(
            'historial_activo' => 0 /// item ocultado
          );
          $this->db->where('insh_id', $id);
          $this->db->update('insumos_historial', $update_mod);

          /*-------------------------------*/
          $result = array(
              'respuesta' => 'correcto'
            );
          /*-------------------------------*/

          echo json_encode($result);
      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*---- Funcion Copia Insumo a Historial para reportes----*/
    public function copia_insumo($cite_id,$ins_id,$tipo){
      $insumo = $this->model_insumo->get_requerimiento($ins_id); //// DATOS DEL REQUERIMIENTO
      //$insumo = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL REQUERIMIENTO
      
      if(count($insumo)!=0){
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); /// Datos Cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO

        $ins_rel=$this->minsumos->relacion_ins_ope($ins_id);
        $id=$ins_rel[0]['prod_id'];

        $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'ins_codigo' => $insumo[0]['ins_codigo'], /// Codigo Insumo
            'ins_fecha_requerimiento' => $insumo[0]['ins_fecha_requerimiento'], /// Fecha de Requerimiento
            'ins_detalle' => $insumo[0]['ins_detalle'], /// Insumo Detalle
            'ins_cant_requerida' => $insumo[0]['ins_cant_requerida'], /// Cantidad Requerida
            'ins_costo_unitario' => $insumo[0]['ins_costo_unitario'], /// Costo Unitario
            'ins_costo_total' => $insumo[0]['ins_costo_total'], /// Costo Total
            'ins_unidad_medida' => $insumo[0]['ins_unidad_medida'], /// Insumo Unidad de Medida
            'ins_tipo' => $insumo[0]['ins_tipo'], /// Ins Tipo
            'par_id' => $insumo[0]['par_id'], /// Partidas
            'ins_observacion' => $insumo[0]['ins_observacion'], /// Ins Observacion
            'fun_id' => $insumo[0]['fun_id'], /// Funcionario
            'aper_id' => $proyecto[0]['aper_id'], /// aper id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'id' => $id, ///prod id
            'tipo_mod' => $tipo, ///tipo de modificacion 1:adicion, 2:modificacion, 3: eliminacion
            'cite_id' => $cite_id, ///cite id
            'ins_id' => $ins_id, ///ins id
          );
          $this->db->insert('insumos_historial', $data_to_store); ///// Guardar en Tabla Insumos 
          $insh_id=$this->db->insert_id();

          $prog=$this->model_insumo->lista_prog_fin($ins_id);
            foreach ($prog as $rowp) {
              $data_to_store4 = array(
              'insh_id' => $insh_id, /// Insumo Id
              'mes_id' => $rowp['mes_id'], /// Mes
              'ipm_fis' => $rowp['ipm_fis'], /// Valor
              'g_id' => $rowp['g_id'], /// gestion
              );
            $this->db->insert('temporalidad_prog_insumo_historial', $data_to_store4);
            $tinsh_id =$this->db->insert_id();
          }

          return true;
      }
      else{
        return false;
      }
      
    }


     /*---- Funcion Copia Insumo a Historial para reportes----*/
    public function registra_insumo_original($cite_id,$ins_id){
      $insumo = $this->model_insumo->get_requerimiento($ins_id); //// DATOS DEL REQUERIMIENTO
      
      if(count($insumo)!=0){
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); /// Datos Cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO

        $ins_rel=$this->minsumos->relacion_ins_ope($ins_id);
        $prod_id=$ins_rel[0]['prod_id'];

        $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'cite_id' => $cite_id, ///cite id
            'ins_id' => $insumo[0]['ins_id'], ///ins id
            'ins_codigo' => $insumo[0]['ins_codigo'], /// Codigo Insumo
            'ins_detalle' => $insumo[0]['ins_detalle'], /// Insumo Detalle
            'ins_cant_requerida' => $insumo[0]['ins_cant_requerida'], /// Cantidad Requerida
            'ins_costo_unitario' => $insumo[0]['ins_costo_unitario'], /// Costo Unitario
            'ins_costo_total' => $insumo[0]['ins_costo_total'], /// Costo Total
            'ins_unidad_medida' => $insumo[0]['ins_unidad_medida'], /// Insumo Unidad de Medida
            'par_id' => $insumo[0]['par_id'], /// Partidas
            'ins_estado' => $insumo[0]['ins_estado'], /// Estado
            'ins_gestion' => $insumo[0]['ins_gestion'], /// gestion
            'ins_observacion' => $insumo[0]['ins_observacion'], /// Ins Observacion
            'ins_mod' => $insumo[0]['ins_mod'], /// Ins mod
            'fun_id' => $insumo[0]['fun_id'], /// Funcionario
            'aper_id' => $proyecto[0]['aper_id'], /// aper id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'prod_id' => $prod_id, ///prod id
          );
          $this->db->insert('insumo_original', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id_inicial=$this->db->insert_id();

          $prog=$this->model_insumo->lista_prog_fin($ins_id);
          foreach ($prog as $rowp) {
            $update_ins= array(
              'm'.$rowp['mes_id'] => $rowp['ipm_fis']
            );
            $this->db->where('ins_id_inicial', $ins_id_inicial);
            $this->db->update('insumo_original', $this->security->xss_clean($update_ins));
          }
          return true;
      }
      else{
        return false;
      }
      
    }


    /*---- GET DATOS REQUERIMIENTO ----*/
    public function get_requerimiento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']);
        $cite_id = $this->security->xss_clean($post['cite_id']);
        $cite = $this->model_modrequerimiento->get_cite_insumo($cite_id); /// Datos Cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); ////// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($cite[0]['proy_id']); /// FASE ACTIVA

        $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos productos

        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$insumo[0]['par_id']);
        if($proyecto[0]['tp_id']==1){
          $prog=$this->model_ptto_sigep->get_partida_programado_pi($proyecto[0]['proy_id'],$insumo[0]['par_id']);
        }
        else{
          $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$insumo[0]['par_id']);
        }

        $lista_partidas=$this->partidas_dependientes($insumo); /// Lista de Insumos dependientes
        $lista_prod_act=$this->list_operaciones($cite,$insumo); /// Lista de Productos, Actividades

          /// -------------------------
          $monto_prog=0;
          if(count($prog)!=0){
            $monto_prog=$prog[0]['monto'];
          }

          $monto_asig=0;
          if(count($asig)!=0){
            $monto_asig=($asig[0]['monto']+$asig[0]['ppto_saldo_ncert']);
          }
          $saldo=$monto_asig-$monto_prog;

          $par_padre=$this->model_partidas->get_partida_padre($insumo[0]['par_depende']); /// lista de partidas padres
          $prog=$this->model_insumo->list_temporalidad_insumo($insumo[0]['ins_id']); /// Temporalidad Requerimiento 2020

          if(count($prog)==0){
            $prog = array('programado_total' => '0','mes1' => '0','mes2' => '0','mes3' => '0','mes4' => '0','mes5' => '0','mes6' => '0','mes7' => '0','mes8' => '0','mes9' => '0','mes10' => '0','mes11' => '0','mes12' => '0');
          }

          $monto_certificado=0;
            $m_certificado=$this->model_certificacion->get_insumo_monto_certificado($insumo[0]['ins_id']);
            if (count($m_certificado)!=0) {
              $monto_certificado=$m_certificado[0]['certificado'];
            }

          $verf = array('verf_mes1' => '0','verf_mes2' => '0','verf_mes3' => '0','verf_mes4' => '0','verf_mes5' => '0','verf_mes6' => '0','verf_mes7' => '0','verf_mes8' => '0','verf_mes9' => '0','verf_mes10' => '0','verf_mes11' => '0','verf_mes12' => '0');
          for ($i=1; $i <=12 ; $i++) { 
              $mes_cert=$this->model_certificacion->get_insumo_programado_certificado_mes($insumo[0]['ins_id'],$i);
              if(count($mes_cert)!=0){
                $verf['verf_mes'.$i]=1;
              }
            }

            $verif_cert=0;
            if(count($this->model_certificacion->verif_insumo_certificado($ins_id))!=0){
              $verif_cert=1;
            }

          if(count($insumo)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'insumo' => $insumo,
              'lista_partidas'=> $lista_partidas,
              'lista_prod_act'=> $lista_prod_act,
              'monto_saldo' => $saldo+$insumo[0]['ins_costo_total'],
              'saldo_dif' => $saldo,
              'ppdre' => $par_padre,
              'prog' => $prog,
              'verif_mes' => $verf,
              'trimestre' => $verf,
              'monto_certificado'=>$monto_certificado,
              'verif_cert'=>$verif_cert,
            );
          }
          else{
            $result = array(
              'respuesta' => 'error',
            );
          }
          /// --------------------------------------
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- PARTIDAS DEPENDIENTES (MOD) ---*/
    function partidas_dependientes($insumo){
      $tabla='';
      $get_partida=$this->model_partidas->get_partida($insumo[0]['par_id']); /// datos de la partda

      $lista_partidas=$this->model_modrequerimiento->lista_partidas_dependientes($insumo[0]['aper_id'],$get_partida[0]['par_depende']);
      foreach ($lista_partidas as $row) {
        if($insumo[0]['par_id']==$row['par_id']){
          $tabla.='<option value="'.$row['par_id'].'" selected>'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
        else{
          $tabla.='<option value="'.$row['par_id'].'">'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
      }

      return $tabla;
    }

    /*--- LISTA DE PRODUCTOS, ACTIVIDADES (MOD) ---*/
    function list_operaciones($cite,$insumo){
      $tabla='';

        $operaciones=$this->model_producto->lista_operaciones($cite[0]['com_id']);
        $tabla.='<option value="">Seleccione Actividad</option>';
        foreach($operaciones as $row){
          if($row['prod_id']==$insumo[0]['prod_id']){
            $tabla.='<option value="'.$row['prod_id'].'" selected>'.$row['or_codigo'].'/'.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
          else{
            $tabla.='<option value="'.$row['prod_id'].'">'.$row['or_codigo'].'/'.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
        } 

      return $tabla;
    }

    /*---------- GET MONTO PARTIDA ------------*/
    public function get_monto_partida(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $par_id = $this->security->xss_clean($post['par_id']);
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id);  /// Asignado
        $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id); /// Programado
        $monto_prog=0;

        if(count($prog)!=0){
          $monto_prog=$prog[0]['monto'];
        }

        $monto=($asig[0]['monto']+$asig[0]['ppto_saldo_ncert'])-$monto_prog;

        $result = array(
          'respuesta' => 'correcto',
          'monto' => round($monto,2),
        );
  
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*------ ASIGNAR UNIDAD RESPONSABLE AL PROGRAMA 72 (BIENES Y SERVICIO) ---------*/
    function asignar_uresponsable(){
      if($this->input->is_ajax_request()){
          $this->form_validation->set_rules('com_id', 'id componente', 'required|trim');
          $post = $this->input->post();
          $com_id= $this->security->xss_clean($post['com_id']);
          $ins_id= $this->security->xss_clean($post['ins_id']);
           
          $update_insumo = array(
            'serv_id' => $com_id,
          );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('insumos', $update_insumo);
              
      }else{
          show_404();
      }
    }


    /*---- GENERAR MENU -----*/
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