<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Programacionpoa extends CI_Controller{
        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_notificacion');
            $this->load->model('programacion/model_producto');
            $this->load->model('ejecucion/model_evaluacion');
          //  $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mestrategico/model_objetivoregion');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('menu_modelo');
         
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            //$this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            //$this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
           // $this->tp_adm = $this->session->userData('tp_adm');
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->resolucion=$this->session->userdata('rd_poa');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->mes = $this->mes_nombre();
            $this->conf_form4 = $this->session->userData('conf_form4');
            $this->conf_form5 = $this->session->userData('conf_form5');
            $this->conf_poa_estado = $this->session->userData('conf_poa_estado'); /// Ajuste POA 1: Inicial, 2 : Ajuste, 3 : aprobado
    }

  /// ----- APERTURAR NUEVO POA (UNIDAD)

  /*------------ FORMULACIÓN - ADICION - POA (2020) ----------*/
  public function formulacion_add_poa_adm(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $unidades=$this->model_estructura_org->list_unidades_apertura();
      $tabla.='
            <article class="col-sm-12">
            <div class="well">
              <form action="'.site_url("").'/programacion/proyecto/valida_poa_unidades'.'" id="form1" name="form1" class="smart-form" method="post">
                  <input type="hidden" name="tp" id="tp" value="1">
                  <header><b>FORMULACI&Oacute;N POA '.$this->gestion.'</b></header>
                  <input type="hidden" name="uni_id" id="uni_id" value="0">
                  <input type="hidden" name="prog" id="prog" value="0">
                  <input type="hidden" name="act" id="act" value="0">
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">REGIONAL</label>
                        <select class="select2" id="reg_id" name="reg_id" title="SELECCIONE REGIONAL">
                        <option value="">SELECCIONE REGIONAL</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>
                      
                    </div>
                    
                    <div id="uni" style="display:none;">
                      <hr><br>
                      <div class="row">
                        <section class="col col-3">
                          <label class="label">UNIDAD ORGANIZACIONAL</label>
                          <select class="select2" id="act_id" name="act_id" title="SELECCIONE UNIDAD ORGANIZACIONAL">
                          <option value="">SELECCIONE UNIDAD / ESTABLECIMIENTO</option>
                          </select>
                        </section>
                        <section class="col col-5">
                          <label class="label">VINCULACI&Oacute;N A OPERACIÓN REGIONAL</label>
                          <div id="oregional"></div>
                        </section>

                        <section class="col col-4">
                          <label class="label">UNIDADES RESPONSABLES DISPONIBLES</label>
                          <div id="servicios"></div>
                        </section>
                      </div>
                      <div class="row">
                        <div id="programa"></div>
                      </div>
                    </div>
                    

                  </fieldset>
                  <div id="programa"></div>
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_form1" id="subir_form1" class="btn btn-info">GUARDAR DATOS</button>
                      <a href="'.base_url().'index.php/admin/proy/list_proy" title="SALIR" class="btn btn-default">CANCELAR</a>
                    </footer>
                  </div>
              </form>
              </div>
            </article>';
    return $tabla;
  }

  /*------------ FORMULACIÓN - ADICION - POA (2020) ----------*/
    public function formulacion_add_poa(){
      $tabla='';
      $unidades=$this->model_estructura_org->list_unidades_apertura();
      $tabla.='
            <article class="col-sm-12">
            <div class="well">
              <form action="'.site_url("").'/programacion/proyecto/valida_poa_unidades'.'" id="form1" name="form1" class="smart-form" method="post">
                  <input type="hidden" name="tp" id="tp" value="1">
                  <header><b>FORMULACI&Oacute;N POA '.$this->gestion.'</b></header>
                  <input type="hidden" name="uni_id" id="uni_id" value="0">
                  <input type="hidden" name="prog" id="prog" value="0">
                  <input type="hidden" name="act" id="act" value="0">
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">UNIDAD ORGANIZACIONAL</label>
                        <select class="form-control" id="act_id" name="act_id" title="SELECCIONE UNIDAD ORGANIZACIONAL">
                        <option value="">SELECCIONE UNIDAD ORGANIZACIONAL</option>';
                        foreach($unidades as $row){
                          if(count($this->model_proyecto->get_uni_apertura_programatica($row['act_id']))==0){
                            $tabla.='<option value="'.$row['act_id'].'">'.$row['act_cod'].'.- '.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-5">
                        <label class="label">VINCULACI&Oacute;N A OPERACIÓN REGIONAL</label>
                        <div id="oregional"></div>
                      </section>

                      <section class="col col-4">
                        <label class="label">UNIDADES DISPONIBLES</label>
                        <div id="servicios"></div>
                      </section>
                    </div>
                  </fieldset>
                  <div id="programa"></div>
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_form1" id="subir_form1" class="btn btn-info">GUARDAR DATOS</button>
                      <a href="'.base_url().'index.php/admin/proy/list_proy" title="SALIR" class="btn btn-default">CANCELAR</a>
                    </footer>
                  </div>
              </form>
              </div>
            </article>';

      return $tabla;
    }



  /// ----- EDITAR DATOS POA (UNIDAD)
     /*------------ FORMULACIÓN - UPDATE - POA (2020) ----------*/
    public function formulacion_update_poa($proyecto){
      $tabla='';
      $actividad= $this->model_estructura_org->get_actividad($proyecto[0]['act_id']); /// get actividad
      $oregionales=$this->model_objetivoregion->get_unidad_pregional_programado($proyecto[0]['act_id']); /// Objetivos Regionales
      $servicios=$this->model_estructura_org->list_establecimiento_servicio($actividad[0]['te_id']); /// Servicios Habilitados
      $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']);
      $tabla.='
      <article class="col-sm-12">
      <div class="well">
        <form action="'.site_url("").'/programacion/proyecto/valida_update_poa_unidades'.'" id="form1" name="form1" class="smart-form" method="post">
            <input type="hidden" name="base" value="'.base_url().'">
            <input type="hidden" name="tp" id="tp" value="1">
            <input type="hidden" name="proy_id" id="proy_id" value="'.$proyecto[0]['proy_id'].'">
            <input type="hidden" name="nro_ope" id="nro_ope" value="'.count($oregionales).'">
            <header><b>FORMULACI&Oacute;N POA '.$this->gestion.'</b></header>
            <fieldset>          
              <div class="row">
                <section class="col col-3">
                  <label class="label">UNIDAD / ESTABLECIMIENTO DE SALUD '.$proyecto[0]['act_id'].'</label>
                  <select class="select2" id="act_id" name="act_id" title="SELECCIONE UNIDAD / ESTABLECIMIENTO DE SALUD" disabled>
                  <option value="'.$actividad[0]['act_id'].'">'.$actividad[0]['act_cod'].'.- ('.$actividad[0]['tipo'].') '.$actividad[0]['act_descripcion'].'</option></select>
                </section>

                <section class="col col-5">
                  <label class="label">VINCULACI&Oacute;N A OPERACIONES '.$this->gestion.'</label>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width:4%;"></th>
                        <th style="width:47.5%;">OPERACI&Oacute;N REGIONAL '.$this->gestion.'</th>
                        <th style="width:47.5%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
                        
                      </tr>
                    </thead>
                    <tbody>';
                    $cont = 0;
                    foreach($oregionales as $row){
                      $verif=$this->model_objetivoregion->get_proyecto_oregional($proyecto[0]['proy_id'],$row['por_id']);
                        $color='#f9eeee';
                        if($row['or_estado']!=0){
                          $color='#e2f9f6';
                        }
                      $cont++;
                      $tabla.='
                      <tr bgcolor='.$color.'>
                        <td style="width:4%;">';
                          if(count($verif)!=0){
                            $tabla.='<center><input type="checkbox" id="ope'.$cont.'" onclick="scheck'.$cont.'(this.checked,'.$row['por_id'].','.$proyecto[0]['proy_id'].');" title="OBJETIVO SELECCIONADO" checked/></center>';
                          }
                          else{
                            $tabla.='<center><input type="checkbox" id="ope'.$cont.'" onclick="scheck'.$cont.'(this.checked,'.$row['por_id'].','.$proyecto[0]['proy_id'].');" title="SELECCIONE OBJETIVO REGIONAL"/></center>';
                          }
                        $tabla.='
                        <td style="width:47.5%;"><b>'.$row['og_codigo'].'.'.$row['or_codigo'].'.</b>.- '.$row['or_objetivo'].'</td>
                        <td style="width:47.5%;"><b>'.$row['og_codigo'].'</b>.- '.$row['og_objetivo'].'</td>
                      </tr>';
                      ?>
                      <script>
                        function scheck<?php echo $cont;?>(estaChequeado,id,proy_id) {
                          valor=0;
                          titulo='DESACTIVAR OPERACIÓN REGIONAL';
                          if (estaChequeado == true) {
                            valor=1;
                            titulo='ACTIVAR OPERACIÓN REGIONAL';
                          }

                          alertify.confirm(titulo, function (a) {
                              if (a) {
                                  var url = "<?php echo site_url().'/programacion/proyecto/estado_oregional'?>";
                                  $.ajax({
                                      type: "post",
                                      url: url,
                                      data:{id:id,estado:valor,proy_id:proy_id},
                                      success: function (data) {
                                          window.location.reload(true);
                                      }
                                  });
                              } else {
                                  alertify.error("OPCI\u00D3N CANCELADA");
                              }
                          });
                        }
                      </script>
                      <?php
                    }
                    if($this->tp_adm==1){
                      $tabla.='
                      <tr>
                        <td><a href="javascript:deseleccionar_todo()" class="btn btn-default">Marcar ninguno</a></td>
                        <td><a href="javascript:seleccionar_todo()" class="btn btn-default">Marcar todos</a></td>
                        <td colspan=2></td>
                      </tr>';
                    }
                    $tabla.='
                    </tbody>
                  </table>
                </section>

                <section class="col col-4">
                  <label class="label">UNIDADES / SERVICIOS DISPONIBLES</label>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width:1%;">#</th>
                        <th style="width:4%;">C&Oacute;DIGO</th>
                        <th style="width:50%;">UNIDAD RESPONSABLE'.$this->gestion.'</th>
                        <th style="width:10%;"></th>
                        <th style="width:10%;">PROGRAMACIÓN POA</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $cont = 0;
                    foreach($servicios as $row){
                      $veri_cs=$this->model_proyecto->verif_componente_servicio($fase[0]['id'],$row['serv_id']);
                      $cont++;
                      $tabla.='
                      <tr>
                        <td align=center>'.$cont.'</td>
                        <td>'.$row['serv_cod'].'</td>
                        <td>'.$row['serv_descripcion'].'</td>';
                          if($row['serv_id']!=0){
                            if(count($veri_cs)!=0){
                            $tabla.='
                            <td align="center">';
                              if(count($this->model_producto->lista_operaciones($veri_cs[0]['com_id']))==0){
                                $tabla.='<input type="checkbox" onclick="scheckk'.$cont.'(this.checked,'.$row['serv_id'].','.$fase[0]['id'].');" title="SERVICIO ACTIVADO" checked/>';
                              }
                            $tabla.='
                            </td>
                            <td align="center">
                              <a href="'.site_url("admin").'/prog/list_prod/'.$veri_cs[0]['com_id'].'" title="PROGRAMAR ACTIVIDADES" class="btn btn-default"><img src="'.base_url().'assets/ifinal/archivo.png" WIDTH="35" HEIGHT="35"/></a>
                            </td>';
                            }
                            else{
                              $tabla.='
                              <td>
                                <input type="checkbox" onclick="scheckk'.$cont.'(this.checked,'.$row['serv_id'].','.$fase[0]['id'].');" title="SELECCIONAR SERVICIO"/>
                              </td>
                              <td>
                              </td>';
                            }
                          }
                          $tabla.='
                        </td>
                      </tr>';
                      ?>
                      <script>
                        function scheckk<?php echo $cont;?>(estaChequeado,id,pfec_id) {
                          valor=0;
                          titulo='DESACTIVAR UNIDAD RESPONSABLE';
                          if (estaChequeado == true) {
                            valor=1;
                            titulo='ACTIVAR UNIDAD RESPONSABLE';
                          }

                          alertify.confirm(titulo, function (a) {
                              if (a) {
                                  var url = "<?php echo site_url().'/programacion/proyecto/estado_servicios'?>";
                                  $.ajax({
                                      type: "post",
                                      url: url,
                                      data:{id:id,estado:valor,pfec_id:pfec_id},
                                      success: function (data) {
                                          window.location.reload(true);
                                      }
                                  });
                              } else {
                                  alertify.error("OPCI\u00D3N CANCELADA");
                              }
                          });
                        }
                      </script>
                      <?php
                    }
                    $tabla.='
                    </tbody>
                  </table>
                </section>
              </div>
            </fieldset>
            <center><div class="alert alert-warning alert-block"><h1>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'].'</h1></div></center>
        </form>
        </div>
      </article>';

      return $tabla;
    }



    /*------ GET POA -----*/
    public function mi_poa($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th >NRO.</th>
                    <th >UNIDAD RESPONSABLE </th>
                    <th >PONDERACI&Oacute;N</th>
                    <th >ACTIVIDADES<br>FORM. N 4</th>
                    <th >REQUERIMIENTOS<br>FORM. N 5</th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nroc=0; $nro_ppto=0;
                    $procesos=$this->model_componente->lista_subactividad($proy_id);
                    foreach($procesos  as $pr){
                      if(count($this->model_producto->list_prod($pr['com_id']))!=0){
                        $nroc++;
                        $tabla.=
                          '<tr>
                            <td>'.$nroc.'</td>
                            <td>'.$pr['serv_cod'].' '.$pr['tipo_subactividad'].' '.$pr['serv_descripcion'].'</td>
                            <td align=center>'.round($pr['com_ponderacion'],2).'%</td>
                            <td align=center>
                              <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$pr['com_id'].'\');" title="REPORTE FORM. 4"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                            </td>
                            <td align=center>';
                              if(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$pr['com_id'].'\');" title="REPORTE FORM. 5"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($nro_ppto>0){
                      $tabla.='
                      <tr>
                        <td colspan=4><b>CONSOLIDADO PROGRAMADO PRESUPUESTO TOTAL POR PARTIDAS </b></td>
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],1);
                      if(count($partidas_asig)!=0 & $proyecto[0]['proy_estado']==4){ //// POA APROBADO
                        $tabla.='
                      <tr bgcolor="#d6ecb3">
                        <td colspan=4><b>CONSOLIDADO PRESUPUESTO COMPARATIVO APROBADO TOTAL POR PARTIDAS </b></td> 
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                      }
                    }
                  $tabla.='
                  
                </table>';

      return $tabla;
    }




    /*------ GET POA PARA AJUSTE -----*/
    public function mi_poa_ajuste($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th bgcolor="#f5f5f5">NRO.</th>
                    <th bgcolor="#f5f5f5">UNIDAD / COMPONENTE </th>
                    <th bgcolor="#f5f5f5">PONDERACI&Oacute;N</th>
                    <th bgcolor="#f5f5f5">ACTIVIDADES</th>
                    <th bgcolor="#f5f5f5">FORM. N 4</th>
                    <th bgcolor="#f5f5f5">REQUERIMIENTOS</th>
                    <th bgcolor="#f5f5f5">REQUERIMIENTOS<br>FORM. N 5</th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nroc=0; $nro_ppto=0;
                    $procesos=$this->model_componente->proyecto_componente($proy_id);
                    foreach($procesos  as $pr){
                      if(count($this->model_producto->list_prod($pr['com_id']))!=0){
                        $nroc++;
                        $tabla.=
                          '<tr>
                            <td>'.$nroc.'</td>
                            <td>'.$pr['com_componente'].'</td>
                            <td align=center>'.round($pr['com_ponderacion'],2).'%</td>
                            <td align=center>
                              <center><a href="'.site_url("").'/admin/prog/list_prod/'.$pr['com_id'].'" title="MODIFICAR DATOS POA " class="btn btn-default" target="_blank"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="34" HEIGHT="30"/></a></center>
                            </td>
                            <td align=center>
                              <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$pr['com_id'].'\');" title="REPORTE FORM 4" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>
                            </td>
                            <td align=center>
                              <a href="'.site_url("").'/prog/list_requerimiento/'.$pr['com_id'].'" target="_blank" title="REQUERIMIENTOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="35" HEIGHT="35"/></a>
                            </td>
                            <td align=center>';
                              if(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$pr['com_id'].'\');" title="REPORTE FORM 5" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($nro_ppto>0){
                      $tabla.='
                      <tr>
                        <td colspan=6><b>CONSOLIDADO PROGRAMADO PRESUPUESTO TOTAL POR PARTIDAS </b></td>
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>
                      <tr bgcolor="#d6ecb3">
                        <td colspan=6><b>CONSOLIDADO PRESUPUESTO COMPARATIVO APROBADO TOTAL POR PARTIDAS </b></td> 
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                    }
                  $tabla.='
                  
                </table>';

      return $tabla;
    }




    /*--- TIPO DE RESPONSABLE ---*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='<h3>RESPONSABLE : '.$this->session->userdata('funcionario').' -> <small>RESPONSABLE NACIONAL</h3>';
      }
      elseif($this->adm==2){
        $titulo='<h3>RESPONSABLE : '.$this->session->userdata('funcionario').' -> <small>RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']).'</h3>';
      }

      return $titulo;
    }

    /*--- ESTILO ---*/
    public function estilo_tabla(){
      $tabla='';
      $tabla.='
        <style>
          .table1{
                display: inline-block;
                width:100%;
                max-width:1550px;
                overflow-x: scroll;
                }
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
                  width: 45% !important;
                }
                #mdialTamanio2{
                  width: 35% !important;
                }
          </style>';

      return $tabla;
    }

  ///// ============= FORMULARIO N° 4 

  /*--- ACTUALIZA CODIGO DE ACTIVIDAD (FORM 4) ----*/
  public function update_codigo_actividad($com_id){  
    $productos = $this->model_producto->lista_operaciones($com_id,$this->gestion); // Lista de productos
    $nro=0;
    foreach($productos as $row){
      $nro++;
      $update_prod= array(
        'prod_cod' => $nro,
        'fun_id' => $this->fun_id
      );
      $this->db->where('prod_id', $row['prod_id']);
      $this->db->update('_productos', $update_prod);
    }
  }

    /*--- BOTON REPORTE SEGUIMIENTO POA (MES VIGENTE)---*/
    function button_form4($nro){
      $tabla='';
      if($this->tp_adm==1 || $this->conf_form4==1){
        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_nuevo_form" class="btn btn-default nuevo_form" title="NUEVO REGISTRO FORM N 4" >
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="MODIFICAR REGISTRO" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR NUEVAS ACTIVIDADES.CSV
                  </a>';

        
      }
      if($this->tp_adm==1 || $this->conf_form5==1){
        if($nro!=0){
          $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="2" title="SUBIR ARCHIVO REQUERIMIENTO (GLOBAL)" >
                      <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS (GLOBAL)
                    </a>';
        }
      }

      $tabla.='<br><br>';
      
      return $tabla;
    }

    /*--- LISTA DE OBJETIVO REGIONAL (GASTO CORRIENTE )-----*/
    public function lista_oregional($proy_id){
      $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
      $tabla='';
      if(count($list_oregional)==1){
        $tabla.=' <section class="col col-3">
                    <label class="label"><b>OPERACIÓN REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                      <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                    </label>
                  </section>'; 
      }
      else{
          $tabla.='<section class="col col-6">
                  <label class="label"><b>ALINEACIÓN OPERACIÓN REGIONAL '.$this->gestion.'</b></label>
                    <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                      <option value="0">SELECCIONE ALINEACIÓN OPERACIÓN</option>';
                      foreach($list_oregional as $row){ 
                        $tabla.='<option value="'.$row['or_id'].'">'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
                      }
                    $tabla.='
                  </select>
                </section>'; 
      }
         
      return $tabla;
    }

    /*---- LISTA DE OBJETIVO REGIONAL (PROYECTO DE INVERSION)-----*/
    public function lista_oregional_pi($proy_id){
      $list_oregional= $this->model_objetivoregion->get_unidad_pregional_programado($proy_id);
      $tabla='';
      if(count($list_oregional)==1){
        $tabla.=' <section class="col col-6">
                    <label class="label"><b>OPERACIÓN REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                      <input type="text" value="'.$list_oregional[0]['og_codigo'].'.'.$list_oregional[0]['or_codigo'].'. .- '.$list_oregional[0]['or_objetivo'].'" disabled>
                    </label>
                  </section>'; 
      }
      else{
          $tabla.='<section class="col col-6">
                  <label class="label"><b>ALIENACIÓN OPERACIÓN REGIONAL '.$this->gestion.'</b></label>
                    <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                      <option value="0">SELECCIONE ALINEACIÓN OPERACIÓN</option>';
                      foreach($list_oregional as $row){ 
                        $tabla.='<option value="'.$row['or_id'].'">'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
                      }
                    $tabla.='
                  </select>
                </section>'; 
      }
         
      return $tabla;
    }

    /*----------- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
    public function verif_oregional($proy_id){
      $proyecto=$this->model_proyecto->get_id_proyecto($proy_id);
      $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
/*      if($proyecto[0]['tp_id']==1){
        $list_oregional=$this->model_objetivoregion->get_unidad_pregional_programado($proy_id);
      }
      else{
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
        $list_oregional=$this->model_objetivoregion->get_unidad_pregional_programado($proyecto[0]['act_id']); /// Objetivos Regionales
      }*/
      
      $tabla='';
      $nro=0;
      if(count($list_oregional)!=0){
        foreach($list_oregional as $row){
          $nro++;
          $tabla.='<h1 title='.$row['or_id'].'> '.$nro.'.- OPERACIÓN REGIONAL : <small> <b>'.$row['og_codigo'].'.|'.$row['or_codigo'].'.</b>.- '.$row['or_objetivo'].'</small></h1>';
        }
      }
      else{
        $tabla.='<h1><small><font color=red>NO ALINEADO A NINGUNA OPERACIÓN REGIONAL</font></small></h1>';
      }
      
      return $tabla;
    }




    /*--- ESTILO FORM 4---*/
    public function estilo_tabla_form4(){
      $tabla='';
      $tabla.='
      <style type="text/css">
        aside{background: #05678B;}
        #mdialTamanio{
            width: 80% !important;
        }
        #mdialTamanio2{
            width: 50% !important;
        }
        table{font-size: 10px;
              width: 100%;
              max-width:1550px;;
              overflow-x: scroll;
              }
        input[type="checkbox"] {
          display:inline-block;
          width:28px;
          height:28px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
        th {font-size: 10px; }

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


/// ===== FORMULARIO N5

    /*------- TIPO AJUSTE POA --------*/
    public function titulo_ajuste($proyecto,$componente){
      $tabla='';
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="well">
            <h2>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].' '.$proyecto[0]['abrev'].' / '.$componente[0]['serv_cod'].'.- '.$componente[0]['serv_descripcion'].'</h2>
            <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO">
              <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO (FORM. N 5)
            </a>
            <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" title="SUBIR ARCHIVO EXCEL">
              <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="25" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS.CSV 
            </a>
            <a href="#" data-toggle="modal" data-target="#modal_comparativo" name="'.$proyecto[0]['proy_id'].'" id="'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].' '.$proyecto[0]['abrev'].'" class="btn btn-default comparativo" title="MOSTRAR CUADRO COMPARATIVO PRESUPUESTARIA ASIGANDO-POA">
              <i class="fa fa-clipboard"></i> <b>COMPARATIVO PPTO.</b>
            </a>
            <a class="btn btn-danger" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS SELECCIONADOS">
              <i class="glyphicon glyphicon-trash"></i> ELIMINAR INSUMOS (SELECCIONADOS)
            </a>
          </div>
        </article>';

      return $tabla;
    } 


    /*--- DISTRIBUCION FINANCIERA ---*/
    function distribucion_financiera($insumo){
      $prog=$this->model_insumo->list_temporalidad_insumo($insumo[0]['ins_id']); /// Temporalidad Requerimiento 2020
        for ($i=0; $i <=12 ; $i++) { 
          if($i==0){
            $titulo[$i]='programado_total';  
          }
          else{
            $titulo[$i]='mes'.$i.''; 
          }

          $temporalidad[$i]=0;
        }

        if(count($prog)!=0){
          for ($i=0; $i <=12 ; $i++) { 
            $temporalidad[$i]= round($prog[0][$titulo[$i]],2);
          }
        }

      return $temporalidad;
    }

    /*--- PARTIDAS DEPENDIENTES ---*/
    function partidas_dependientes($insumo){
      $tabla='';
      $get_partida=$this->model_partidas->get_partida($insumo[0]['par_id']); /// datos de la partda
      $lista_partidas=$this->model_partidas->lista_par_hijos($get_partida[0]['par_depende']);
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

    /*--- LISTA DE UNIDADES DE MEDIDA ---*/
    function unidades_medida($insumo){
      $tabla='';
      $lista_umedida=$this->model_insumo->lista_umedida($insumo[0]['par_id']); /// Lista de Unidades de medida

      foreach ($lista_umedida as $row) {
        if($insumo[0]['ins_unidad_medida']==$row['um_descripcion']){
          $tabla.='<option value="'.$row['um_id'].'" selected>'.$row['um_descripcion'].'</option>';
        }
        else{
          $tabla.='<option value="'.$row['um_id'].'">'.$row['um_descripcion'].'</option>';
        }
      }

      return $tabla;
    }

        /*--- LISTA DE PRODUCTOS, ACTIVIDADES (MOD) ---*/
    function list_prod_actividad($com_id,$insumo){
      $tabla='';

        $operaciones=$this->model_producto->lista_operaciones($com_id);
        $tabla.='<option value="">Seleccione Actividad</option>';
        foreach($operaciones as $row){
          if($row['prod_id']==$insumo[0]['prod_id']){
            $tabla.='<option value="'.$row['prod_id'].'" selected>ACT. '.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
          else{
            $tabla.='<option value="'.$row['prod_id'].'">ACT. '.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
        } 

      return $tabla;
    }
    

    /*--- BOTON ESTADO FORM 5---*/
    function button_form5(){
      $tabla='';
      if($this->tp_adm==1 || $this->conf_form5==1){
        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff" title="NUEVO REGISTRO FORM N 5" class="btn btn-success">
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="IMPORTAR REQUERIMIENTOS" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS.CSV
                  </a>';
      }

      $tabla.='<br><br>';
      
      return $tabla;
    }

    /*--- ESTILO FORM 5---*/
    public function estilo_tabla_form5(){
      $tabla='';
      $tabla.='
      <style>
      aside{background: #05678B;}
      .table1{
            display: inline-block;
            width:100%;
            max-width:1550px;
            overflow-x: scroll;
            }
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
        #mdialTamanio2{
          width: 55% !important;
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






    //// ======== CABECERA Y PIE PARA LOS REPORTES POA 2022
  //// Cabecera Reporte form 3, 4 y 5
  public function cabecera($tp_id,$tp_rep,$proyecto,$com_id){
    /// tp_rep : 3 (Foda), 4 (Actividades), 5 (requerimientos), 0 (consolidado ppto)
    
    if($tp_rep==0){
      if($proyecto[0]['aper_proy_estado']==1){
        $titulo_rep='CONSOLIDADO PRESUPUESTO POA';
        $titulo_form='PPTO. ANTEPROYECTO';
      } 
      else{
        $titulo_rep='COMPARATIVO PRESUPUESTO POA - APROBADO';
        $titulo_form='PPTO. APROBADO - POA';
      }
      
      $comp='';
    }
    elseif($tp_rep==3){
      $titulo_rep='ANALISIS DE PROBLEMAS Y CAUSAS';
      $titulo_form='FORMULARIO SPO N° 3';
      $comp='';
    }
    else{
      $componente=$this->model_componente->get_componente($com_id,$this->gestion);
      $estado='';
      if($proyecto[0]['aper_proy_estado']==1){
        $estado='<b>(ANTEPROYECTO)</b>';
      } 

      if($tp_rep==4){
        $titulo_rep='ACTIVIDADES '.$estado;
        $titulo_form='FORMULARIO SPO N° 4';
      }
      elseif($tp_rep==5){
        $titulo_rep='REQUERIMIENTOS '.$estado;
        $titulo_form='FORMULARIO SPO N° 5';
      }
      $comp='
        <tr>
          <td style="width:20%;">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                  <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>UNIDAD REPONSABLE</b></td><td style="width:5%;"></td></tr>
              </table>
          </td>
          <td style="width:80%;">
              <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                  <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$componente[0]['serv_cod'].' '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</td></tr>
              </table>
          </td>
        </tr>';
    }

    
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 13px;font-family: Arial;">
                        <td style="width:40%;height: 20%;">&nbsp;&nbsp;<b> '.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                          <td style="height: 30%;"><b>PLAN OPERATIVO ANUAL GESTION - '.$this->gestion.'</b></td>
                      </tr>
                      <tr style="font-size: 20px;font-family: Arial;">
                        <td style="height: 5%;">'.$titulo_rep.'</td>
                      </tr>
                  </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
              <td style="width:70%;">
              </td>
              <td style="width:30%; height: 3%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                      <td align=center style="width:100%;height: 40%;"><b>'.$titulo_form.'</b></td>
                    </tr>
                </table>
              </td>
          </tr>
      </table>
      
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
         <tr>
            <td style="width:2%;"></td>
            <td style="width:96%;height: 1%;">
              <hr>
            </td>
            <td style="width:2%;"></td>
        </tr>
        <tr>
            <td style="width:2%;"></td>
            <td style="width:96%;height: 3%;">
             
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr>
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dep_cod'].' '.strtoupper ($proyecto[0]['dep_departamento']).'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dist_cod'].' '.strtoupper ($proyecto[0]['dist_distrital']).'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>';
                  if($tp_id==4){
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>'.$proyecto[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper ($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'].'</td></tr>
                        </table>
                    </td>';
                  }
                  else{
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>PROYECTO</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['proy_sisin'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper ($proyecto[0]['proy_nombre']).'</td></tr>
                        </table>
                    </td>';
                  }
                $tabla.='
                </tr>
                '.$comp.'
            </table>
          </td>
          <td style="width:2%;"></td>
        </tr>
        <tr>
          <td style="width:2%;"></td>
          <td style="width:96%;height: 1%;">
            <hr>
            <br><b style="font-size: 8px;font-family: Arial;">DETALLE : </b>
          </td>
          <td style="width:2%;"></td>
        </tr>
      </table>';
    return $tabla;
  }


  /*------ PIE FODA - REPORTE -----*/
  public function pie_foda(){
    $tabla='';
    $tabla.='    
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
          <td style="width: 50%;">
              <table border="0.3" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                  </tr>
                 
                  <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                      <td><b><br><br><br><br>FIRMA</b></td>
                  </tr>
              </table>
          </td>
          <td style="width: 50%;">
              <table border="0.3" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:100%;height:13px;"><b>APROBADO POR<br></b></td>
                  </tr>
                 
                  <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                      <td><b><br><br><br><br>FIRMA</b></td>
                  </tr>
              </table>
          </td>
        </tr>
        <tr>
          <td colspan="2"><br></td>
        </tr>
        <tr style="font-size: 7px;font-family: Arial;">
          <td style="text-align: left" >
            '.$this->session->userdata('sistema').'
          </td>
          <td style="width: 20%; text-align: right">
                pag. [[page_cu]]/[[page_nb]]
          </td>
        </tr>
        <tr>
            <td colspan="2"><br><br></td>
        </tr>
      </table>';

    return $tabla;
  }


  /*------ PIE FORM - REPORTE -----*/
  public function pie_form($proyecto){
    $tabla='';
    $tabla.='
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:98%;" align="center">
          <tr>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                        <td style="width:100%;height:12px;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                    </tr>
                    <tr>
                        <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td style="width:100%;height:12px;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                    </tr>
                    <tr>
                      <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33%;">
                <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td style="width:100%;height:12px;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
                    </tr>
                    <tr>
                      <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td style="width: 33%; text-align: left; height:20px;">';
              if($proyecto[0]['aper_proy_estado']==1){
                $tabla.='POA - '.$this->session->userdata('gestion');
              }
              else{
                $tabla.='POA - '.$this->session->userdata('gestion').' '.$this->session->userdata('rd_poa');
              } 
            $tabla.='
            </td>
            <td style="width: 33%; text-align: center">
              '.$this->session->userdata('sistema').'
            </td>
            <td style="width: 33%; text-align: right">
                pag. [[page_cu]]/[[page_nb]]
            </td>
          </tr>
      </table>';

    return $tabla;
  }



  /*----- REPORTE FORMULARIO 4 (2021 - Operaciones, Proyectos de Inversion) ----*/
  public function operaciones_form4($componente,$proyecto){
    $tabla='';
    
    if($proyecto[0]['tp_id']==1){ /// Proyectos de Inversion
      $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
              <thead>
                <tr style="font-size: 6.7px;" bgcolor=#eceaea align=center>
                  <th style="width:1%;height:15px;">#</th>
                  <th style="width:2%;">COD.<br>ACE.</th>
                  <th style="width:2%;">COD.<br>ACP.</th>
                  <th style="width:2%;">COD.<br>OPE.</th>
                  <th style="width:2%;">COD.<br>ACT.</th>
                  <th style="width:9%;">COMPONENTE</th>
                  <th style="width:11.5%;">ACTIVIDAD</th>
                  <th style="width:11%;">RESULTADO</th>
                  <th style="width:11%;">INDICADOR</th>
                  <th style="width:2%;">LB.</th>
                  <th style="width:2.5%;">META</th>
                  <th style="width:2.5%;">ENE.</th>
                  <th style="width:2.5%;">FEB.</th>
                  <th style="width:2.5%;">MAR.</th>
                  <th style="width:2.5%;">ABR.</th>
                  <th style="width:2.5%;">MAY.</th>
                  <th style="width:2.5%;">JUN.</th>
                  <th style="width:2.5%;">JUL.</th>
                  <th style="width:2.5%;">AGO.</th>
                  <th style="width:2.5%;">SEPT.</th>
                  <th style="width:2.5%;">OCT.</th>
                  <th style="width:2.5%;">NOV.</th>
                  <th style="width:2.5%;">DIC.</th>
                  <th style="width:8.5%;">VERIFICACI&Oacute;N</th> 
                  <th style="width:5%;">PPTO.</th>   
                </tr>
              </thead>
              <tbody>';
              $operaciones=$this->model_producto->list_operaciones_pi($componente[0]['com_id']);  /// 2020
              $nro=0;
              foreach($operaciones as $rowp){
                $nro++;
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                $tp='';
                if($rowp['indi_id']==2){
                  $tp='%';
                }

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $ptto=number_format(0, 2, '.', ',');
                if(count($monto)!=0){
                  $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                }

                $tabla.='
                <tr>
                  <td style="font-size: 6.5px; height:12px;">'.$nro.'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'>'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'>'.$rowp['og_codigo'].'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'><b>'.$rowp['or_codigo'].'</b></td>
                  <td style="width: 2%; text-align: center; font-size: 8px;"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 9%; text-align: left;">'.$componente[0]['com_componente'].'</td>
                  <td style="width: 11.5%; text-align: left;">'.$rowp['prod_producto'].'</td>
                  <td style="width: 11%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width:11%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                  <td style="width:2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                  <td style="width:2.5%; text-align: center;"><b>'.round($rowp['prod_meta'],2).'</b></td>
                  <td style="width:2.5%;" align=center>'.round($rowp['enero'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['febrero'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['marzo'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['abril'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['mayo'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['junio'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['julio'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['agosto'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['septiembre'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['octubre'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['noviembre'],2).''.$tp.'</td>
                  <td style="width:2.5%;" align=center>'.round($rowp['diciembre'],2).''.$tp.'</td>
                  <td style="width:8.5%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                  <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                </tr>';            
              }
        $tabla.='
              </tbody>
            </table>';

    }
    else{ //// Gasto Corriente

       $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
              <thead>
               <tr style="font-size: 6.7px;" bgcolor=#eceaea align=center>
                  <th style="width:1%;height:15px;">#</th>
                  <th style="width:2%;">COD.<br>ACE.</th>
                  <th style="width:2%;">COD.<br>ACP.</th>
                  <th style="width:2%;">COD.<br>OPE.</th>
                  <th style="width:2%;">COD.<br>ACT.</th> 
                  <th style="width:11.5%;">ACTIVIDAD</th>
                  <th style="width:11.5%;">RESULTADO</th>
                  <th style="width:7%;">UNIDAD RESPONSABLE</th>
                  <th style="width:11.5%;">INDICADOR</th>
                  <th style="width:2.5%;">LB.</th>
                  <th style="width:2.5%;">META</th>
                  <th style="width:2.5%;">ENE.</th>
                  <th style="width:2.5%;">FEB.</th>
                  <th style="width:2.5%;">MAR.</th>
                  <th style="width:2.5%;">ABR.</th>
                  <th style="width:2.5%;">MAY.</th>
                  <th style="width:2.5%;">JUN.</th>
                  <th style="width:2.5%;">JUL.</th>
                  <th style="width:2.5%;">AGO.</th>
                  <th style="width:2.5%;">SEPT.</th>
                  <th style="width:2.5%;">OCT.</th>
                  <th style="width:2.5%;">NOV.</th>
                  <th style="width:2.5%;">DIC.</th>
                  <th style="width:9%;">VERIFICACI&Oacute;N</th> 
                  <th style="width:5%;">PPTO.</th>   
              </tr>    
             
              </thead>
              <tbody>';
              $nro=0;
              $operaciones=$this->model_producto->lista_operaciones($componente[0]['com_id']);
              
              foreach($operaciones as $rowp){
                $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                $color=''; $tp='';
                if($rowp['indi_id']==1){
                  if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                    $color='#fbd5d5';
                  }
                }
                elseif ($rowp['indi_id']==2) {
                  $tp='%';
                  if($rowp['mt_id']==3){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                }

                $ptto=number_format(0, 2, '.', ',');
                if(count($monto)!=0){
                  $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                }

                $color_or='';
                if($rowp['or_id']==0){
                  $color_or='#fbd5d5';
                }

                $nro++;
                $tabla.=
                '<tr style="font-size: 6.5px;height:12px;" bgcolor="'.$color.'">
                  <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'>'.$rowp['acc_codigo'].'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'>'.$rowp['og_codigo'].'</td>
                  <td style="width: 2%; text-align: center;" bgcolor='.$color_or.'><b>'.$rowp['or_codigo'].'</b></td>
                  <td style="width: 2%; text-align: center; font-size: 8px;"><b>'.$rowp['prod_cod'].'</b></td>
                  <td style="width: 11.5%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                  <td style="width: 11.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                  <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                  <td style="width: 11.5%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                  <td style="width: 2.5%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                  <td style="width: 2.5%; text-align: center;"><b>'.round($rowp['prod_meta'],2).'</b></td>';

                  if(count($programado)!=0){
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                    $tabla.='<td style="width:2.5%;" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                  }
                  else{
                    for ($i=1; $i <=12 ; $i++) { 
                      $tabla.='<td style="width:2.5%;" bgcolor="#f5cace" align=center>0</td>';
                    }
                  }

                  $tabla.='
                  <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                  <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                </tr>';

              }
              $tabla.='
              </tbody>
            </table>';
    }
    return $tabla;
  }



  /*----- REPORTE - FORMULARIO 5 -----*/
    public function list_requerimientos_reporte($com_id,$tp_id){
      $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($com_id); /// Lista requerimientos
      $tabla='';
      $tabla.=' 
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
              <thead>
              <tr style="font-size: 7px;" bgcolor="#eceaea" align=center>
                <th style="width:1%;height:15px;">#</th>
                <th style="width:2%;">COD.<br>ACT.</th> 
                <th style="width:4%;">PARTIDA</th>
                <th style="width:18%;">DETALLE REQUERIMIENTO</th>
                <th style="width:5%;">UNIDAD</th>
                <th style="width:4%;">CANTIDAD</th>
                <th style="width:5%;">UNITARIO</th>
                <th style="width:5%;">TOTAL</th>
                <th style="width:4%;">ENE.</th>
                <th style="width:4%;">FEB.</th>
                <th style="width:4%;">MAR.</th>
                <th style="width:4%;">ABR.</th>
                <th style="width:4%;">MAY.</th>
                <th style="width:4%;">JUN.</th>
                <th style="width:4%;">JUL.</th>
                <th style="width:4%;">AGO.</th>
                <th style="width:4%;">SEPT.</th>
                <th style="width:4%;">OCT.</th>
                <th style="width:4%;">NOV.</th>
                <th style="width:4%;">DIC.</th>
                <th style="width:8%;">OBSERVACI&Oacute;N</th>
              </tr>
              </thead>
              <tbody>';
              $cont = 0; $total=0; 
              foreach ($lista_insumos as $row) {
              $cont++;
              $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
              $total=$total+$row['ins_costo_total'];
              $color='';
              if(count($prog)!=0){
                if(($row['ins_costo_total'])!=$prog[0]['programado_total']){
                  $color='#f5bfb6';
                }
              }

              $tabla.=
              '<tr style="font-size: 6.5px;" >
                  <td style="width: 1%; font-size: 4.5px; text-align: center;height:13px;">'.$cont.'</td>
                  <td style="width: 2%; text-align: center; font-size: 8px;"><b>'.$row['prod_cod'].'</b></td>
                  <td style="width: 4%; text-align: center;font-size: 8px;"><b>'.$row['par_codigo'].'</b></td>
                  <td style="width: 18%; text-align: left;font-size: 7.5px;">'.strtoupper($row['ins_detalle']).'</td>
                  <td style="width: 5%; text-align: left">'.strtoupper($row['ins_unidad_medida']).'</td>
                  <td style="width: 4%; text-align: right">'.round($row['ins_cant_requerida'],2).'</td>
                  <td style="width: 5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                  <td style="width: 5%; text-align: right;font-size: 7.5px;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>'; 
                  if(count($prog)!=0){ 
                  $tabla.=
                  '<td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                  <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                  }
                  else{
                  $tabla.=
                  '<td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>
                  <td style="width: 4%; text-align: right; color: red">0.00</td>';
                  }

              $tabla.='
                  <td style="width: 8%; text-align: left;">'.$row['ins_observacion'].'</td>
                  
              </tr>';
              }

          $tabla.='
              </tbody>
              <tr class="modo1" bgcolor="#eceaea">
                  <td colspan="6" style="height:10px;" ><b>TOTAL PROGRAMADO </b></td>
                  <td style="width: 4%; text-align: right; font-size: 7px;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                  <td colspan="14"></td>
              </tr>
          </table><br>';
      return $tabla;
    }














  /*-------- MENU -----*/
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
}
?>