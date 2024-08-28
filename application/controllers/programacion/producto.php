<?php
class Producto extends CI_Controller { 
  public $temp = array( '1' => 'enero','2' => 'febrero','3' => 'marzo','4' => 'abril','5' => 'mayo','6' => 'junio',
                        '7' => 'julio','8' => 'agosto','9' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre'); 

  public $prog_mes = array( '1' => 0,'2' => 0,'3' => 0,'4' => 0,'5' => 0,'6' => 0,
                        '7' => 0,'8' => 0,'9' => 0,'10' => 0,'11' => 0,'12' => 0); 

  public $prog_mes_eval = array( '1' => 0,'2' => 0,'3' => 0,'4' => 0,'5' => 0,'6' => 0,
                        '7' => 0,'8' => 0,'9' => 0,'10' => 0,'11' => 0,'12' => 0); 
  
  public function __construct (){ 
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userdata('mes_actual');
        $this->conf_form4 = $this->session->userData('conf_form4');
        $this->conf_form5 = $this->session->userData('conf_form5');
        $this->conf_poa_estado = $this->session->userData('conf_poa_estado'); /// Ajuste POA 1: Inicial, 2 : Ajuste, 3 : aprobado
        $this->load->library('programacionpoa');
      }else{
        $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }

  /*------- LISTA DE FORM 4 ----------*/
    public function lista_productos($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion);
      $data['stylo']=$this->programacionpoa->estilo_tabla_form4();

      if(count($data['componente'])!=0){
          $form4=$this->model_producto->lista_form4_x_unidadresponsable($com_id);
          $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
          $proy_id=$data['fase'][0]['proy_id'];
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          //$data['datos_proyecto']='<h1> '.$data['proyecto'][0]['establecimiento'].' : <small> '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</small></h1>';
          
          if($data['proyecto'][0]['tp_id']==1){
            //$data['datos_proyecto']='ddddd'.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['proy_nombre'].' </small></h1>';
            $list_oregional=$this->model_objetivoregion->get_unidad_pregional_programado($data['fase'][0]['proy_id']);
          }
          else{
            ///$data['datos_proyecto']='<h1> dfgf'.$data['proyecto'][0]['establecimiento'].' : <small> '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</small></h1>';
            $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
          }

          $data['indi'] = $this->model_proyecto->indicador(); /// indicador
          $data['unidades']=$this->model_producto->list_uresponsables_regional($data['proyecto'][0]['dist_id']);
          $data['metas'] = $this->model_producto->tp_metas(); /// tp metas
          $data['uni_resp']='';
          $data['alineacion']='';

          $data['alineacion']='
                  <section class="col col-4">
                    <label class="label"><b>ALINEACI&Oacute;N OPERACI&Oacute;N '.$this->gestion.'</b></label>
                      <select class="form-control" id="or_id" name="or_id" style="width:100%; font-size:13px; color:blue; background-color: #fafcd7;" title="SELECCIONE ALINEACION">
                        <option value="">SELECCIONE OPERACI&Oacute;N </option>';
                        foreach($list_oregional as $row){
                          $data['alineacion'].='<option value="'.$row['or_id'].'">'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
                        }
                      $data['alineacion'].='
                    </select>
                  </section>';

          if($data['proyecto'][0]['por_id']==1){
            $data['uni_resp'].='
            <section class="col col-3">
              <label class="label"><b>UNIDAD RESPONSABLE</b></label>
                <select class="form-control" id="uni_resp" name="uni_resp" title="SELECCIONE UNIDAD RESPONSABLE" style="width:100%; font-size:10.5px; color:blue; background-color: #d7fcfa;">
                  <option value="">Selec. Uni. Resp.</option>';
                  foreach($data['unidades'] as $row){
                    $data['uni_resp'].='<option value="'.$row['com_id'].'">'.$row['tipo'].' '.$row['actividad'].'-'.$row['abrev'].' -> '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
                  }       
                  $data['uni_resp'].='
                </select>
              </section>';
          }
          else{
            $data['uni_resp']='
            <section class="col col-3">
              <label class="label"><b>UNIDAD RESPONSABLE</b></label>
              <label class="textarea">
                <i class="icon-append fa fa-tag"></i>
                <textarea rows="3" name="uni_resp" id="uni_resp" title="REGISTRE UNIDAD RESPONSABLE" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"></textarea>
              </label>
            </section>';
          }

          $data['titulo']='';
          if($this->conf_poa_estado==1){ /// Ante proyecto
            $data['titulo'].='
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input type="hidden" name="base" value="'.base_url().'">
              <div class="well">';

              if($data['proyecto'][0]['tp_id']==1){
                $data['titulo'].='<h2>'.$data['proyecto'][0]['proy_sisin'].' - '.$data['proyecto'][0]['proy_nombre'].'</h2>';
              }
              else{
                $data['titulo'].='<h2>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'  / '.$data['componente'][0]['serv_cod'].' '.$data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion'].'</h2>';
              }

              $data['titulo'].='
                  <a href="#" data-toggle="modal" data-target="#modal_nuevo_form" class="btn btn-default nuevo_form" title="NUEVO REGISTRO FORM N 4" >
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>NUEVO REGISTRO (ACTIVIDAD)</b>
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="SUBIR ARCHIVO EXCEL" >
                    <img src="'.base_url().'assets/Iconos/arrow_down.png" WIDTH="30" HEIGHT="20"/>&nbsp;<b>SUBIR ARCHIVO (FORM 4.CSV)</b>
                  </a>';

                  if(count($form4)!=0){
                    $data['titulo'].='
                    <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="2" title="SUBIR ARCHIVO REQUERIMIENTO (GLOBAL)" >
                      <img src="'.base_url().'assets/Iconos/arrow_down.png" WIDTH="30" HEIGHT="20"/>&nbsp;<b>SUBIR ARCHIVO (FORM 5.CSV)</b>
                    </a>
                    <a href="#" data-toggle="modal" data-target="#modal_ver_form5" class="btn btn-default ver_requerimientos" name="'.$com_id.'" title="SUBIR ARCHIVO REQUERIMIENTO (GLOBAL)" >
                      <img src="'.base_url().'assets/Iconos/text_list_bullets.png" WIDTH="30" HEIGHT="20"/>&nbsp;<b>VER MIS REQUERIMIENTOS</b>
                    </a>
                    <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$com_id.'\');" class="btn btn-primary" title="REPORTE FORM. 4"> <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>REPORTE FORM N 4</a>
                    <a onclick="eliminar_form4_todos()" class="btn btn-danger"  title="Eliminar Actividades de la unidad (todos)"><img src="'.base_url().'assets/Iconos/application_delete.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>ELIMINAR FORM 4 (TODOS)</a>
                    <a onclick="eliminar_requerimientos_servicio()" class="btn btn-danger"  title="Eliminar Requerimientos de la unidad (todos)"><img src="'.base_url().'assets/Iconos/application_delete.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>ELIMINAR FORM 5 (TODOS)</a>';
                  }
                  $data['titulo'].='
              </div>
            </article>';

            $tabla='';
            $color_or='';
            $cont=0;
            
            foreach($form4  as $rowp){
              $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
              $disabled='disabled';
              $cont++;
              if($rowp['indi_id']==2){
                $disabled='';
              }

              $style='none';
              if($rowp['indi_id']==2){
                $style='block';
              }
              $tabla.='
              <tr>
                <td style="width: 4%; text-align: center; " >
                  <center><a href="'.site_url("").'/prog/requerimiento/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="30" HEIGHT="30"/></a></center>
                </td>
                <td style="width: 4%; text-align: center; " >';
                      if(count($this->model_producto->insumo_producto($rowp['prod_id']))==0){
                        if($this->tp_adm==1 || $this->conf_form4==1){
                          //$tabla.='<center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                          $tabla.='<center><a name="del_prod'.$rowp['prod_id'].'" id="del_prod'.$rowp['prod_id'].'" onclick="delete_form4('.$rowp['prod_id'].');" class="btn btn-default" title="ELIMINAR ACTIVIDAD"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                        }
                      }
                $tabla.='
                </td>
                <td style="width: 5%; text-align: center; " bgcolor="#eceaea" title='.$rowp['prod_id'].'>
                  <input name="prod_cod'.$rowp['prod_id'].'" id="prod_cod'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(0,1,'.$rowp['prod_id'].',\'prod_cod\');"  style="width:100%; font-size:12px; color:blue; background-color: #d7fcfa; text-align:center;" value="'.round($rowp['prod_cod'],2).'" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                </td>
                <td style="width: 10%; text-align: center; " title='.$rowp['prod_id'].'>
                  <section class="col col-2">
                    <label class="label"><b>ALINEACI&Oacute;N OPERACI&Oacute;N '.$this->gestion.'</b></label>
                      <select class="form-control" id="or_id'.$rowp['prod_id'].'" name="or_id'.$rowp['prod_id'].'" onchange="select_uresp_acp_indi(1,this.value,'.$rowp['prod_id'].');" style="width:100%; font-size:12px; color:blue; background-color: #fafcd7;" title="SELECCIONE ALINEACION">
                        <option value="">SELECCIONE OPERACI&Oacute;N </option>';
                        foreach($list_oregional as $row){
                          if($rowp['or_id']==$row['or_id']){
                            $tabla.='<option value="'.$row['or_id'].'" selected>'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
                          }
                          else{
                            $tabla.='<option value="'.$row['or_id'].'">'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
                          }
                        }
                      $tabla.='
                    </select>
                  </section>
                </td>
                
                <td style="width: 12%; text-align: left;">
                  <textarea rows="5" class="form-control" onkeyup="datos_form4(0,2,'.$rowp['prod_id'].',\'prod_form4\');" name="prod_form4'.$rowp['prod_id'].'" id="prod_form4'.$rowp['prod_id'].'" style="width:100%; font-size:10px; color:blue;background-color: #d7fcfa;" title="DETALLE ACTIVIDAD">'.$rowp['prod_producto'].'</textarea>
                </td>
                <td style="width: 12%; text-align: left;">
                  <textarea rows="5" class="form-control" onkeyup="datos_form4(0,3,'.$rowp['prod_id'].',\'prod_res\');"  style="width:100%; font-size:10px; color:blue;background-color: #d7fcfa;" name="prod_res'.$rowp['prod_id'].'" id="prod_res'.$rowp['prod_id'].'" title="DETALLE RESULTADO">'.$rowp['prod_resultado'].'</textarea>
                </td>';
                if($data['proyecto'][0]['por_id']==1){ /// programas bolsas / Unidad Responable
                  $tabla.='
                  <td style="width: 7%; text-align: left;">
                    <select class="form-control" id="u_resp'.$rowp['prod_id'].'" name="u_resp'.$rowp['prod_id'].'" onchange="select_uresp_acp_indi(2,this.value,'.$rowp['prod_id'].');" title="SELECCIONE UNIDAD RESPONSABLE" style="width:100%; font-size:10px; color:blue; background-color: #d7fcfa;">
                      <option value="">Selec. Uni. Resp.</option>';
                      foreach($data['unidades'] as $row){
                        if($rowp['uni_resp']==$row['com_id']){
                          $tabla.='<option value="'.$row['com_id'].'" selected>'.$row['tipo'].' '.$row['actividad'].'-'.$row['abrev'].' -> '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
                        }
                        else{
                          $tabla.='<option value="'.$row['com_id'].'">'.$row['tipo'].' '.$row['actividad'].'-'.$row['abrev'].' -> '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
                        }
                        
                      }       
                      $tabla.='
                    </select>
                  </td>';
                }
                else{ /// programas normales / Unidad Responable
                  $tabla.='
                  <td style="width: 7%; text-align: left;">
                    <textarea rows="5" class="form-control" onkeyup="datos_form4(0,4,'.$rowp['prod_id'].',\'prod_uni\');"  style="width:100%; font-size:10px; color:blue; background-color: #d7fcfa;" name="prod_uni'.$rowp['prod_id'].'" id="prod_uni'.$rowp['prod_id'].'" title="UNIDAD RESPONSABLE">'.$rowp['prod_unidades'].'</textarea>
                  </td>';
                }
                $tabla.='
                <td style="width: 5%; text-align: left;">
                    <select class="form-control" id="indi_id'.$rowp['prod_id'].'" name="indi_id'.$rowp['prod_id'].'" onchange="select_uresp_acp_indi(3,this.value,'.$rowp['prod_id'].');" style="width:100%; font-size:10px; color:blue; background-color: #d7fcfa;" title="SELECCIONE INDICADOR">
                      <option value="">SELECCIONE INDICADOR</option>';
                      foreach($data['indi'] as $row){
                        if($rowp['indi_id']==$row['indi_id']){
                          $tabla.='<option value="'.$row['indi_id'].'" selected>'.$row['indi_descripcion'].'</option>';    
                        }
                        else{
                          $tabla.='<option value="'.$row['indi_id'].'">'.$row['indi_descripcion'].'</option>';    
                        }
                      }
                    $tabla.='
                  </select>

                  <div id="tp_met'.$rowp['prod_id'].'" style="display:'.$style.';" >
                    <select class="form-control" id="tp_met" name="tp_met" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="SELECCIONE TIPO DE META" onchange="select_tp_meta(this.value,'.$rowp['prod_id'].');">
                      <option value="">Seleccione Tipo de Meta</option>';
                      foreach($data['metas'] as $row){ 
                        if($row['mt_id']==$rowp['mt_id']){
                          $tabla.='<option value="'.$row['mt_id'].'" selected>'.$row['mt_tipo'].'</option>';
                        }
                        else{
                          $tabla.='<option value="'.$row['mt_id'].'" >'.$row['mt_tipo'].'</option>';
                        }
                      }
                      $tabla.='
                    </select>
                  </div>
                 
                  
                </td>
                <td style="width: 8%; text-align: left;">
                  <textarea rows="5" class="form-control" onkeyup="datos_form4(0,5,'.$rowp['prod_id'].',\'prod_indi\');"  style="width:100%; font-size:10px; color:blue; background-color: #d7fcfa;" name="prod_indi'.$rowp['prod_id'].'" id="prod_indi'.$rowp['prod_id'].'" title="DETALLE INDICADOR">'.$rowp['prod_indicador'].'</textarea>
                </td>
                <td style="width: 8%; text-align: left;">
                  <textarea rows="5" class="form-control" onkeyup="datos_form4(0,6,'.$rowp['prod_id'].',\'prod_mverif\');"  style="width:100%; font-size:10px; color:blue;background-color: #d7fcfa;" name="prod_mverif'.$rowp['prod_id'].'" id="prod_mverif'.$rowp['prod_id'].'" title="DETALLE MEDIO DE VERIFICACION">'.$rowp['prod_fuente_verificacion'].'</textarea>
                </td>
                <td style="width: 5%; text-align: center;" >
                  <input name="meta'.$rowp['prod_id'].'" id="meta'.$rowp['prod_id'].'" onkeyup="datos_form4(0,7,'.$rowp['prod_id'].',\'meta\');"  class="form-control" type="text" '.$disabled.' style="width:100%; font-size:11.5px; text-align:center" value="'.round($rowp['prod_meta'],2).'" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                </td>';
                if(count($programado)!=0){
                  $tabla.=' <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m1'.$rowp['prod_id'].'" id="m1'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,8,'.$rowp['prod_id'].',1);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['enero'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m2'.$rowp['prod_id'].'" id="m2'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,9,'.$rowp['prod_id'].',2);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['febrero'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m3'.$rowp['prod_id'].'" id="m3'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,10,'.$rowp['prod_id'].',3);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['marzo'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m4'.$rowp['prod_id'].'" id="m4'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,11,'.$rowp['prod_id'].',4);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['abril'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m5'.$rowp['prod_id'].'" id="m5'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,12,'.$rowp['prod_id'].',5);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['mayo'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m6'.$rowp['prod_id'].'" id="m6'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,13,'.$rowp['prod_id'].',6);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['junio'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m7'.$rowp['prod_id'].'" id="m7'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,14,'.$rowp['prod_id'].',7);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['julio'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m8'.$rowp['prod_id'].'" id="m8'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,15,'.$rowp['prod_id'].',8);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['agosto'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m9'.$rowp['prod_id'].'" id="m9'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,16,'.$rowp['prod_id'].',9);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['septiembre'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m10'.$rowp['prod_id'].'" id="m10'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,17,'.$rowp['prod_id'].',10);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['octubre'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m11'.$rowp['prod_id'].'" id="m11'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,18,'.$rowp['prod_id'].',11);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['noviembre'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>
                            <td style="width:5%;" bgcolor="#e5fde5" >
                              <input name="m12'.$rowp['prod_id'].'" id="m12'.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,19,'.$rowp['prod_id'].',12);"  style="width:100%; font-size:10px; color:blue;" value="'.round($programado[0]['diciembre'],2).'" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>';
                    }
                else{
                  $nro_i=7;
                  for ($i=1; $i <=12 ; $i++) { 
                    $nro_i++;
                    $tabla.='<td style="width:3.5%;" bgcolor="#e5fde5" >
                              <input name="m'.$i.''.$rowp['prod_id'].'" id="m'.$i.''.$rowp['prod_id'].'" class="form-control" type="text" onkeyup="datos_form4(1,'.$nro_i.','.$rowp['prod_id'].','.$i.');"  style="width:100%; font-size:10px; color:blue;" value="0" onkeypress="if (this.value.length < 6) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                            </td>';
                  }
                }
                $tabla.='
              </tr>';
            }

            $data['tabla']=$tabla;
            $this->load->view('admin/programacion/producto/form_anteproyecto_form4', $data); /// Gasto Corriente
          
          }
          else{
            echo "final";
          }

      }
      else{
        redirect('prog/list_serv/'.$com_id);
      }

    }



/////
    /*---- UPDATE DATOS FORM 4 2025----*/
    public function update_datos_form4(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $tp = $this->security->xss_clean($post['tp']);
        $nro = $this->security->xss_clean($post['nro']);
        $name_input = $this->security->xss_clean($post['name_input']);
        $detalle = $this->security->xss_clean($post['detalle']);
        $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto

        if($tp==0){
          if($nro==1){ // cod act
            $campo='prod_cod';
          }
          elseif($nro==2){
            $campo='prod_producto';
          }
          elseif($nro==3){
            $campo='prod_resultado';
          }
          elseif($nro==4){
            $campo='prod_unidades';
          }
          elseif($nro==5){
            $campo='prod_indicador';
          }
          elseif($nro==6){
            $campo='prod_fuente_verificacion';
          }
          elseif($nro==7){
            $campo='prod_meta';
          }

          /////
          $update_form4 = array(
            $campo => $detalle,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_form4);
          ////

          $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto
          $informacion=$producto[0][$campo];

          if($producto[0]['indi_id']==1){
            $suma_temp=$this->model_producto->suma_programado_producto($prod_id,$this->gestion);
            $sum_meta=0;
            if(count($suma_temp)!=0){
              $sum_meta=round($suma_temp[0]['prog'],2);
            }
          }
          
        }
        else{ /// Temporalidad
          /// --- eliminando el item del mes
          $this->db->where('m_id', $name_input);
          $this->db->where('prod_id', $prod_id);
          $this->db->delete('prod_programado_mensual');
          /// ------------------------------

          if($detalle!=0){
            /// --- Add insert temp
              $data_to_store = array( 
                'prod_id' => $prod_id, /// prod id
                'm_id' => $name_input, /// mes id 
                'pg_fis' => $detalle,
                'g_id' => $this->gestion, /// Gestion
              );
              $this->db->insert('prod_programado_mensual', $data_to_store);
              $pg_id=$this->db->insert_id();
            /// -------------------
          }

          $temp_prod=$this->model_producto->get_mes_programado_form4($prod_id,$name_input);
          $informacion=0;
          if(count($temp_prod)!=0){
            $informacion=$temp_prod[0]['pg_fis'];
          }
          

          ////
          if($producto[0]['indi_id']==1){
            $suma_temp=$this->model_producto->suma_programado_producto($prod_id,$this->gestion);
            $sum_meta=0;
            if(count($suma_temp)!=0){
              $sum_meta=round($suma_temp[0]['prog'],2);
            }
          }

          //// update meta
          $update_tempform4 = array(
            'prod_meta' => $sum_meta,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_tempform4);

        }



        $result = array(
          'respuesta' => 'correcto',
          'update_informacion'=>$informacion,
          'update_meta'=>$sum_meta,
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }

    /*---- UPDATE DATOS FORM 4 - UNIDAD RESPONSABLE 2025----*/
    public function update_datos_form4_uresp(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $id = $this->security->xss_clean($post['id']);
        $tp = $this->security->xss_clean($post['tp']);
        $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto

          if($tp==1){
            $campo='or_id';
          }
          elseif($tp==2){
            $campo='uni_resp';
          }
          elseif($tp==3){
            $campo='indi_id';
           
            if($id==1){ /// absoluto
              $suma_temp=$this->model_producto->suma_programado_producto($prod_id,$this->gestion);
              
              if(count($suma_temp)!=0){
                $sum_meta=round($suma_temp[0]['prog'],2);
              }

              $update_prod = array(
                'prod_meta' => $sum_meta,
                'mt_id' => 3,
              );
              $this->db->where('prod_id', $prod_id);
              $this->db->update('_productos', $update_prod);

            }
            else{ /// relativo
              $update_prod = array(
                'prod_meta' => 0,
              );
              $this->db->where('prod_id', $prod_id);
              $this->db->update('_productos', $update_prod);
            }

          }

          $update_prod = array(
            $campo => $id,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_prod);


        $result = array(
          'respuesta' => 'correcto',
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }



    /*---- UPDATE DATOS FORM 4 TIPO DE META----*/
    public function update_datos_tpmeta(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $id = $this->security->xss_clean($post['id']);
        $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto

        if($producto[0]['indi_id']==2){
          $update_prod = array(
            'mt_id' => $id,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_prod);
        }
          
        $result = array(
          'respuesta' => 'correcto',
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }



//////

    /*---- GET DATOS PRODUCTO FORM 4 ----*/
    public function get_producto(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto
        $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);

        $temporalidad=$this->model_producto->producto_programado($prod_id,$this->gestion); /// Temporalidad
        
        $prioridad='';
        $prioridad.='<section class="col col-2">
                      <label class="label"><b style="color:blue">ACTIVIDAD CON PRIORIDAD ?</b></label>
                      <select class="form-control" id="priori" name="priori" title="ACTIVIDAD PRIORITARIO">';
                        if($producto[0]['prod_priori']==1){
                          $prioridad.='
                          <option value="1" selected>SI</option>
                          <option value="0">NO</option>';
                        }
                        else{
                          $prioridad.='
                          <option value="1">SI</option>
                          <option value="0" selected>NO</option>';
                        }
                      $prioridad.='      
                      </select>
                    </section>';


        $sum_temp=0;
        $sum=$this->model_producto->meta_prod_gest($prod_id);
        if(count($sum)!=0){
          $sum_temp=$sum[0]['meta_gest'];
        }

        if(count($temporalidad)!=0){
          for ($i=1; $i <=12 ; $i++) { 
            $this->prog_mes[$i]= $temporalidad[0][$this->temp[$i]];
          }
        }

        $uresponsable='';
        if($proyecto[0]['por_id']==1){
          $unidades=$this->model_producto->list_uresponsables_regional($proyecto[0]['dist_id']);
          $uresponsable.='
              <section class="col col-4">
                <label class="label"><b>UNIDAD RESPONSABLE</b></label>
                <select class="form-control" id="um_resp" name="um_resp" title="SELECCIONE UNIDAD RESPONSABLE">
                  <option value="">Seleccione Unidad Responsable</option>';
                  foreach($unidades as $row){
                    if($row['com_id']==$producto[0]['uni_resp']){
                      $uresponsable.='<option value="'.$row['com_id'].'" selected>'.$row['tipo'].' '.$row['actividad'].'-'.$row['abrev'].' -> '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
                    }
                    else{
                      $uresponsable.='<option value="'.$row['com_id'].'" >'.$row['tipo'].' '.$row['actividad'].'-'.$row['abrev'].' -> '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
                    }
                  }       
                $uresponsable.='
                </select>
              </section>';
        }
        else{
          $uresponsable.='
                <input type="text" name="um_resp" id="um_resp" value="0">
                <section class="col col-4">
                  <label class="label"><b>UNIDAD / SERVICIO RESPONSABLE</b></label>
                  <label class="textarea">
                    <i class="icon-append fa fa-tag"></i>
                    <textarea rows="2" name="munidad" id="munidad" title="REGISTRE UNIDAD RESPONSABLE">'.$producto[0]['prod_unidades'].'</textarea>
                  </label>
                </section>';
        }



        if(count($producto)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'producto'=>$producto,
            'uresponsable'=>$uresponsable,
            'temp'=>$this->prog_mes,
            'sum_temp'=>$sum_temp,
            'prioridad'=>$prioridad,
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



  /*--------- VALIDA FORM 4 (2025) -----------*/
  public function valida_producto(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('tipo_i', 'Tipo de Indicador', 'required|trim');
        $componente = $this->model_componente->get_componente($this->input->post('com_id'),$this->gestion);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        $form4 = $this->model_producto->list_prod($this->input->post('com_id'));
        $cod_nuv=0;
        if(count($form4)!=0){
          $cod_nuv=(count($form4)+1);
        }  

        if($proyecto[0]['por_id']==1){ /// BOLSA
          $campo='uni_resp';
        }
        else{
          $campo='prod_unidades';
        }

        if($this->input->post('tipo_i')==1){
          $tp_met=3;
        }
        else{
          $tp_met=$this->input->post('tp_met');
        }
    

        if ($this->form_validation->run()){
          /*-------- INSERT FORMULARIO N 4 ---------*/
          $data_to_store = array(
            'com_id' => $this->input->post('com_id'),
            'prod_cod' => $cod_nuv,
            'or_id' => strtoupper($this->input->post('or_id')),
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')), 
            'prod_meta' => $this->input->post('meta'),
            $campo => strtoupper($this->input->post('uni_resp')),
            'mt_id' => $tp_met,
            'fecha' => date("d/m/Y H:i:s"),
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('_productos', $data_to_store);
          $id_pr=$this->db->insert_id(); ////// id del producto
          /*------------------------------------------------------*/
          if($this->input->post('tipo_i')==1){
            for ($i=1; $i <=12 ; $i++) {
              if($this->input->post('m'.$i)!=0){
                $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('m'.$i));
              }
            }
          }
          if($this->input->post('tipo_i')==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($this->input->post('m'.$i)!=0){
                  $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('m'.$i));
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('meta'));
              }
            }
            elseif($tp_met==5){ /// trimestre recurrente
              for ($i=1; $i <=4 ; $i++) {
                $this->model_producto->add_prod_gest($id_pr,$this->gestion,($i*3),$this->input->post('meta'));
              }
            }
          }

          $producto=$this->model_producto->get_producto_id($id_pr);
          if(count($producto)==1){
            $this->session->set_flashdata('success','LA ACTIVIDAD SE REGISTRO CORRECTAMENTE :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR ACTIVIDAD, VUELVA REGISTRAR :(');
          }

          redirect('admin/prog/list_prod/'.$this->input->post('com_id').'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA ACTIVIDAD :(');
          redirect('admin/prog/list_prod/'.$this->input->post('com_id').'');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar la Actividad !!!!</font></center>";
    }
  }


    /*----- VALIDAR UPDATE MOD FORM4 ----*/
    // public function valida_update_form4(){
    //   if($this->input->post()) {
    //     $post = $this->input->post();
    //     $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
    //     $prod = $this->security->xss_clean($post['mprod']); /// detalle producto
    //     $resultado = $this->security->xss_clean($post['mresultado']); /// Resultado
    //     $indi_id = $this->security->xss_clean($post['mtipo_i']); /// Tipo de Indicador
    //     $indicador = $this->security->xss_clean($post['mindicador']); /// Indicador
    //     $mverificacion = $this->security->xss_clean($post['mverificacion']); /// Medio de Verificacion
    //     $unidad = $this->security->xss_clean($post['munidad']); /// Unidad Responsable
    //     $linea_base = $this->security->xss_clean($post['mlbase']); /// Linea Base
    //     $meta = $this->security->xss_clean($post['mmeta']); /// Meta
    //     $presupuesto = $this->security->xss_clean($post['mppto']); /// Presupuesto
    //     $or_id = $this->security->xss_clean($post['mor_id']); /// Objetivo Regional
    //     $tp_meta = $this->security->xss_clean($post['mtp_met']); /// Tipo de Meta
    //     $prioridad = $this->security->xss_clean($post['priori']); /// prioridad
    //     $uni_resp = $this->security->xss_clean($post['um_resp']); /// unidad responsable

    //     $relacion_operacion_obj_estrategico=$this->model_objetivoregion->get_objetivosregional($or_id);

    //     $ae=0;
    //     /*----- UPDATE FORMULARIO N4 ----*/
    //       $update_prod = array(
    //         'prod_producto' => strtoupper($prod),
    //         'prod_resultado' => strtoupper($resultado),
    //         'indi_id' => $indi_id,
    //         'prod_indicador' => strtoupper($indicador),
    //         'prod_linea_base' => $linea_base,
    //         'prod_meta' => $meta,
    //         'prod_unidades' => $unidad,
    //         'prod_fuente_verificacion' => strtoupper($mverificacion),
    //         'estado' => 2,
    //         'or_id' => $or_id,
    //         'uni_resp' => $uni_resp,
    //         'obj_id' => $relacion_operacion_obj_estrategico[0]['obj_id'],
    //         'acc_id' => $ae,
    //         'fecha' => date("d/m/Y H:i:s"),
    //         'mt_id' => $tp_meta,
    //         'fun_id' => $this->fun_id,
    //         'prod_ppto' => $presupuesto,
    //         'prod_priori' => $prioridad,
    //         'num_ip' => $this->input->ip_address(), 
    //         'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
    //         );
    //       $this->db->where('prod_id', $prod_id);
    //       $this->db->update('_productos', $update_prod);  
    //     /*----------------------------------------------*/

    //       /*------------ ANULAR TEMPORALIDAD -----------*/
    //       $this->model_producto->delete_prod_gest($prod_id);

    //       if($indi_id==1){
    //         for ($i=1; $i <=12 ; $i++) {
    //           if($this->security->xss_clean($post['mm'.$i])!=0){
    //             $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$this->security->xss_clean($post['mm'.$i]));
    //           }
    //         }
    //       }

    //       if($indi_id==2){
    //         if($tp_meta==3){
    //           for ($i=1; $i <=12 ; $i++) {
    //             if($this->security->xss_clean($post['mm'.$i])!=0){
    //               $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$this->security->xss_clean($post['mm'.$i]));
    //             }
    //           }
    //         }
    //         elseif($tp_meta==1){ /// recurrente
    //           for ($i=1; $i <=12 ; $i++) {
    //             $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$meta);
    //           }
    //         }
    //         elseif($tp_meta==5){ /// trimestre recurrente
    //           for ($i=1; $i <=4 ; $i++) {
    //             $this->model_producto->add_prod_gest($prod_id,$this->gestion,($i*3),$meta);
    //           }
    //         }
    //       }  
            
    //         $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
    //         $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE DATOS DE LA ACTIVIDAD :)');
    //         redirect('admin/prog/list_prod/'.$producto[0]['com_id'].'');


    //   } else {
    //       show_404();
    //   }
    // }



  /*-------- VALIDAR MODIFICACION OPERACIÓN-PRODUCTO (2020) --------*/
  // public function modificar_producto(){
  //   if ($this->input->server('REQUEST_METHOD') === 'POST'){
  //       $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
  //       $this->form_validation->set_rules('indicador', 'Indicador', 'required|trim');
  //       $this->form_validation->set_rules('met', 'Meta', 'required|trim');

  //       $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
  //       $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion);
  //       $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
  //       $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        
  //       $ae=0;
  //       $get_acc=$this->model_objetivoregion->get_objetivosregional($this->input->post('or_id'));
  //       if(count($get_acc)!=0){
  //         $ae=$get_acc[0]['ae'];
  //       }

  //       if($this->input->post('tipo_i')==1){
  //         $tp_met=3;
  //       }
  //       else{
  //         $tp_met=$this->input->post('tp_met');
  //       }

  //       if ($this->form_validation->run()){
  //         /*-------------- UPDATE OPERACION --------------*/
  //         $update_prod = array(
  //           'prod_producto' => strtoupper($this->input->post('prod')),
  //           'prod_resultado' => strtoupper($this->input->post('resultado')),
  //           'indi_id' => $this->input->post('tipo_i'),
  //           'prod_indicador' => strtoupper($this->input->post('indicador')),
  //           'prod_linea_base' => $this->input->post('lb'),
  //           'indi_pei' => $this->input->post('indi_pei'),
  //           'prod_meta' => $this->input->post('met'),
  //           'prod_unidades' => $this->input->post('unidad'),
  //           'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
  //           'estado' => 2,
  //           'or_id' => $this->input->post('or_id'),
  //           'acc_id' => $ae,
  //           'fecha' => date("d/m/Y H:i:s"),
  //           'mt_id' => $tp_met,
  //           'fun_id' => $this->fun_id,
  //           'prod_ppto' => $this->input->post('ppto'),
  //           'num_ip' => $this->input->ip_address(), 
  //           'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
  //           );
  //         $this->db->where('prod_id', $this->input->post('prod_id'));
  //         $this->db->update('_productos', $update_prod);
  //         /*----------------------------------------------*/

  //         $this->model_producto->delete_prod_gest($this->input->post('prod_id'));

  //         if($this->input->post('tipo_i')==1){
  //           for ($i=1; $i <=12 ; $i++) {
  //             if($this->input->post('m'.$i)!=0){
  //               $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
  //             }
  //           }
  //         }

  //         if($this->input->post('tipo_i')==2){
  //           if($tp_met==3){
  //             for ($i=1; $i <=12 ; $i++) {
  //               if($this->input->post('m'.$i)!=0){
  //                 $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
  //               }
  //             }
  //           }
  //           elseif($tp_met==1){
  //             for ($i=1; $i <=12 ; $i++) {
  //               $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('met'));
  //             }
  //           }
  //         }  
            
  //           $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
  //           $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE DATOS DE LA ACTIVIDAD :)');
  //           redirect('admin/prog/list_prod/'.$producto[0]['com_id'].'');
  //         }
  //         else {
  //           $this->session->set_flashdata('danger','ERROR AL MODIFICAR DATOS DE LA ACTIVIDAD :(');
  //           redirect('admin/prog/mod_prod/'.$this->input->post('prod_id').'/false');
  //         }
  //     }
  //  }



    /*------ LISTA FORMULARIO N° 4 (2020-2021-2022) ------*/
    // public function form4($proy_id,$com_id){
    //   //$proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
    //   $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);

    //   $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
    //   $productos = $this->model_producto->lista_form4_x_unidadresponsable($com_id,$this->gestion); // Lista de productos
      
    //   $tabla ='';
    //   $tabla .='
    //     <input type="hidden" name="com_id" id="com_id" value="'.$com_id.'">
    //     <input type="hidden" name="base" value="'.base_url().'">
    //     <div class="table-responsive">
    //       <table id="dt_basic" class="table table-bordered">
    //         <thead>
    //               <tr class="modo1">
    //                 <th style="width:2%; text-align=center">#</th>
    //                 <th style="width:1%; text-align=center"><b>E/B</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. ACP.</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. OPE.</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. ACT.</b></th>
    //                 <th style="width:15%; text-align=center"><b>ACTIVIDAD</b></th>
    //                 <th style="width:15%; text-align=center"><b>RESULTADO</b></th>
    //                 <th style="width:10%; text-align=center"><b>UNIDAD RESPONSABLE</b></th>
    //                 <th style="width:5%; text-align=center"><b>TIP. IND.</b></th>
    //                 <th style="width:10%; text-align=center"><b>INDICADOR</b></th>
    //                 <th style="width:1%; text-align=center"><b>L.B. '.($this->gestion-1).'</b></th>
    //                 <th style="width:1%; text-align=center"><b>META</b></th>
    //                 <th style="width:4%; text-align=center"><b>ENE.</b></th>
    //                 <th style="width:4%; text-align=center"><b>FEB.</b></th>
    //                 <th style="width:4%; text-align=center"><b>MAR.</b></th>
    //                 <th style="width:4%; text-align=center"><b>ABR.</b></th>
    //                 <th style="width:4%; text-align=center"><b>MAY.</b></th>
    //                 <th style="width:4%; text-align=center"><b>JUN.</b></th>
    //                 <th style="width:4%; text-align=center"><b>JUL.</b></th>
    //                 <th style="width:4%; text-align=center"><b>AGO.</b></th>
    //                 <th style="width:4%; text-align=center"><b>SEP.</b></th>
    //                 <th style="width:4%; text-align=center"><b>OCT.</b></th>
    //                 <th style="width:4%; text-align=center"><b>NOV.</b></th>
    //                 <th style="width:4%; text-align=center"><b>DIC.</b></th>
    //                 <th style="width:10%; text-align=center"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
    //                 <th style="width:7%; text-align=center"><b>ELIMINAR ACTIVIDAD</b></th>
                   
    //                 <th style="width:7%; text-align=center"><b>NRO. REQ.</b></th>
    //               </tr>
    //             </thead>
    //             <tbody>';
    //             $cont = 0;
    //             foreach($productos as $rowp){
    //               $cont++;
    //               $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
    //               //$monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
    //               $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);

    //               if($proyecto[0]['por_id']==0){
    //                 $uresp=strtoupper($rowp['prod_unidades']);
    //               }
    //               else{
    //                 $unidad=$this->model_componente->get_componente($rowp['uni_resp'],$this->gestion);
    //                 $proy = $this->model_proyecto->get_datos_proyecto_unidad($unidad[0]['proy_id']);
    //                 $uresp='';
    //                 if(count($unidad)!=0){
    //                   $uresp='<font color=blue size=1.5><b>'.$proy[0]['tipo'].' '.$proy[0]['act_descripcion'].' - '.$proy[0]['abrev'].' -> '.$unidad[0]['tipo_subactividad'].' '.$unidad[0]['serv_descripcion'].'</b></font>';
    //                 }
    //               }

                  
    //               /*$ptto=0;
    //               if(count($monto)!=0){
    //                 $ptto=$monto[0]['total'];
    //               }*/

    //               $color='#f1fdf1'; $titulo=''; $por=''; 
    //               if($proyecto[0]['tp_id']==1){
    //                 if($rowp['prod_meta']!=($sum[0]['meta_gest']+$rowp['prod_linea_base'])){
    //                   $color='#fbd5d5';
    //                 }
    //               }
    //               else{
    //                 if($rowp['indi_id']==2){ // Relativo
    //                   $por='%';
    //                   if($rowp['mt_id']==3){
    //                     if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
    //                       $color='#fbd5d5';
    //                       $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
    //                     }
    //                   }
    //                 }
    //                 else{ // Absoluto
    //                   if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
    //                     $color='#fbd5d5';
    //                     $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
    //                   }
    //                 }
    //               }
                  
                  
    //               $tabla .='<tr bgcolor="'.$color.'" class="modo1" title='.$titulo.'>';
    //                 $tabla.='<td align="center" title="'.$rowp['prod_id'].'">';
    //                   if($rowp['prod_priori']==1){
    //                     $tabla.='<br><img src="'.base_url().'assets/ifinal/ok.png" WIDTH="40" HEIGHT="33"/><br><font size=1 color=green><b>PRIORITARIO</b></font>';
    //                   }
    //                 $tabla.='</td>';
    //                 $tabla.='<td align="center" title='.$rowp['prod_id'].'>';
    //                 if($this->tp_adm==1 || $this->conf_form4==1){
    //                   $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$rowp['prod_id'].'" title="MODIFICAR ACTIVIDAD"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
    //                 }

    //                 if($rowp['prod_ppto']==1){
    //                   $tabla.='<a href="'.site_url("").'/prog/requerimiento/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a>';
    //                 }
    //                 $tabla.='</td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#d6eef7"><b><font size=5 color=blue>'.$rowp['og_codigo'].'</font></b></td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#d6eef7"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#f1fdf1"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.strtoupper($rowp['prod_producto']).'</td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.strtoupper($rowp['prod_resultado']).'</td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.$uresp.'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'"><b>'.strtoupper($rowp['indi_abreviacion']).'</b></td>';
    //                 $tabla.='<td style="width:10%;" bgcolor="'.$color.'">'.$rowp['prod_indicador'].'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'">'.round($rowp['prod_linea_base'],2).'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'" align=center><b><font size=3>'.round($rowp['prod_meta'],2).'</font></b></td>';
    //                 if(count($programado)!=0){
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['enero'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['febrero'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['marzo'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['abril'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['mayo'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['junio'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['julio'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['agosto'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['septiembre'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['octubre'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['noviembre'],2).' '.$por.'</td>';
    //                   $tabla.='<td style="width:4%;" bgcolor="'.$color.'">'.round($programado[0]['diciembre'],2).' '.$por.'</td>';
    //                 }
    //                 else{
    //                   for ($i=1; $i <=12 ; $i++) { 
    //                     $tabla.='<td style="width:4%;" bgcolor="#f1bac6">0</td>';
    //                   }
    //                 }
    //                 $tabla.='<td style="width:10%;" bgcolor="#e5fde5">'.$rowp['prod_fuente_verificacion'].'</td>';
    //                 $tabla.='<td style="width:7%;">';
    //                   if(count($this->model_producto->insumo_producto($rowp['prod_id']))==0){
    //                     if($this->tp_adm==1 || $this->conf_form4==1){
    //                       $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br><br>';
    //                       $tabla.=' <center>
    //                                   <input type="checkbox" name="req[]" value="'.$rowp['prod_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
    //                                 </center>';
    //                     }
    //                   }
    //                 $tabla.='</td>';
    //                 $tabla.='<td style="width:7%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
    //               $tabla .='</tr>';
    //               
    //             }
    //             $tabla.='</tbody>
    //           </table>
    //         </div>';

    //   return $tabla;
    // }


    /*------ LISTA FORMULARIO N° 4 para Programas GLOBALES ------*/
    // public function form4_prog_globales($proy_id,$com_id){
    //   //$proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
    //   $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);

    //   $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
    //   $productos = $this->model_producto->lista_form4_x_unidadresponsable($com_id,$this->gestion); // Lista de productos
      
    //   $tabla ='';
    //   $tabla .='
    //     <input type="hidden" name="com_id" id="com_id" value="'.$com_id.'">
    //     <input type="hidden" name="base" value="'.base_url().'">
    //     <div class="table-responsive">
    //       <table id="dt_basic" class="table table-bordered">
    //         <thead>
    //               <tr class="modo1">
    //                 <th style="width:2%; text-align=center">#</th>
    //                 <th style="width:1%; text-align=center"><b>E/B</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. ACP.</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. OPE.</b></th>
    //                 <th style="width:2%; text-align=center"><b>COD. ACT.</b></th>
    //                 <th style="width:15%; text-align=center"><b>ACTIVIDAD</b></th>
    //                 <th style="width:15%; text-align=center"><b>RESULTADO</b></th>
    //                 <th style="width:10%; text-align=center"><b>UNIDAD RESPONSABLE</b></th>
    //                 <th style="width:5%; text-align=center"><b>TIP. IND.</b></th>
    //                 <th style="width:10%; text-align=center"><b>INDICADOR</b></th>
    //                 <th style="width:1%; text-align=center"><b>L.B. '.($this->gestion-1).'</b></th>
    //                 <th style="width:1%; text-align=center"><b>META</b></th>
    //                 <th style="width:4%; text-align=center"><b>ENE.</b></th>
    //                 <th style="width:4%; text-align=center"><b>FEB.</b></th>
    //                 <th style="width:4%; text-align=center"><b>MAR.</b></th>
    //                 <th style="width:4%; text-align=center"><b>ABR.</b></th>
    //                 <th style="width:4%; text-align=center"><b>MAY.</b></th>
    //                 <th style="width:4%; text-align=center"><b>JUN.</b></th>
    //                 <th style="width:4%; text-align=center"><b>JUL.</b></th>
    //                 <th style="width:4%; text-align=center"><b>AGO.</b></th>
    //                 <th style="width:4%; text-align=center"><b>SEP.</b></th>
    //                 <th style="width:4%; text-align=center"><b>OCT.</b></th>
    //                 <th style="width:4%; text-align=center"><b>NOV.</b></th>
    //                 <th style="width:4%; text-align=center"><b>DIC.</b></th>
    //                 <th style="width:10%; text-align=center"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
    //                 <th style="width:7%; text-align=center"><b>ELIMINAR ACTIVIDAD</b></th>
    //                 <th style="width:7%; text-align=center"><b>PTTO.</b></th>
    //                 <th style="width:7%; text-align=center"><b>NRO. REQ.</b></th>
    //               </tr>
    //             </thead>
    //             <tbody>';
    //             $cont = 0;
    //             foreach($productos as $rowp){
    //               $cont++;
    //               $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
    //               //$monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
    //               //$programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);

    //               if($proyecto[0]['por_id']==0){
    //                 $uresp=strtoupper($rowp['prod_unidades']);
    //               }
    //               else{
    //                 $unidad=$this->model_componente->get_componente($rowp['uni_resp'],$this->gestion);
                    
    //                 $uresp='';
    //                 if(count($unidad)!=0){
    //                   $proy = $this->model_proyecto->get_datos_proyecto_unidad($unidad[0]['proy_id']);
    //                   $uresp='<font color=blue size=1.5><b>'.$proy[0]['tipo'].' '.$proy[0]['act_descripcion'].' - '.$proy[0]['abrev'].' -> '.$unidad[0]['tipo_subactividad'].' '.$unidad[0]['serv_descripcion'].'</b></font>';
    //                 }
    //               }

                  
    //               /*$ptto=0;
    //               if(count($monto)!=0){
    //                 $ptto=$monto[0]['total'];
    //               }*/

    //               $color='#f1fdf1'; $titulo=''; $por=''; 
    //               if($proyecto[0]['tp_id']==1){
    //                 if($rowp['prod_meta']!=($sum[0]['meta_gest']+$rowp['prod_linea_base'])){
    //                   $color='#fbd5d5';
    //                 }
    //               }
    //               else{
    //                 if($rowp['indi_id']==2){ // Relativo
    //                   $por='%';
    //                   if($rowp['mt_id']==3){
    //                     if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
    //                       $color='#fbd5d5';
    //                       $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
    //                     }
    //                   }
    //                 }
    //                 else{ // Absoluto
    //                   if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
    //                     $color='#fbd5d5';
    //                     $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
    //                   }
    //                 }
    //               }
                  
                  
    //               $tabla .='<tr bgcolor="'.$color.'" class="modo1" title='.$titulo.'>';
    //                 $tabla.='<td align="center">';
    //                   if($rowp['prod_priori']==1){
    //                     $tabla.='<br><img src="'.base_url().'assets/ifinal/ok.png" WIDTH="40" HEIGHT="33"/><br><font size=1 color=green><b>PRIORITARIO</b></font>';
    //                   }
    //                 $tabla.='</td>';
    //                 $tabla.='<td align="center" title='.$rowp['prod_id'].'>';
    //                 if($this->tp_adm==1 || $this->conf_form4==1){
    //                   $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$rowp['prod_id'].'" title="MODIFICAR ACTIVIDAD"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
    //                 }

    //                 if($rowp['prod_ppto']==1){
    //                   $tabla.='<a href="'.site_url("").'/prog/requerimiento/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a>';
    //                 }
    //                 $tabla.='</td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#d6eef7"><b><font size=5 color=blue>'.$rowp['og_codigo'].'</font></b></td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#d6eef7"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
    //                 $tabla.='<td style="width:2%;text-align=center" bgcolor="#f1fdf1"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.strtoupper($rowp['prod_producto']).'</td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.strtoupper($rowp['prod_resultado']).'</td>';
    //                 $tabla.='<td style="width:15%;" bgcolor="'.$color.'">'.$uresp.'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'"><b>'.strtoupper($rowp['indi_abreviacion']).'</b></td>';
    //                 $tabla.='<td style="width:10%;" bgcolor="'.$color.'">'.$rowp['prod_indicador'].'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'">'.round($rowp['prod_linea_base'],2).'</td>';
    //                 $tabla.='<td style="width:5%;" bgcolor="'.$color.'" align=center><b><font size=3>'.round($rowp['prod_meta'],2).'</font></b></td>';
    //                 for ($i=1; $i <=12 ; $i++) { 
    //                   $tabla.='<td style="width:4%;">0</td>';
    //                 }

    //                 $tabla.='<td style="width:10%;" bgcolor="#e5fde5">'.$rowp['prod_fuente_verificacion'].'</td>';
    //                 $tabla.='<td style="width:7%;">';
    //                   if($this->tp_adm==1 || $this->conf_form4==1){
    //                     $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br><br>';
    //                     $tabla.=' <center>
    //                                 <input type="checkbox" name="req[]" value="'.$rowp['prod_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
    //                               </center>';
    //                   }
    //                 $tabla.='</td>';
    //                 $tabla.='<td></td>';
    //                 $tabla.='<td style="width:7%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
    //               $tabla .='</tr>';
  
    //             }
    //             $tabla.='</tbody>
    //           </table>
    //         </div>';

    //   return $tabla;
    // }

    /*------ CAMBIA PRIORIDAD DE LA ACTIVIDAD---------*/
    function asignar_prioridad(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('prod_id', 'id producto', 'required|trim');
          $this->form_validation->set_message('required', 'El campo es es obligatorio');
        
          $post = $this->input->post();
          $prod_id= $this->security->xss_clean($post['prod_id']);
          $prioridad= $this->security->xss_clean($post['prioridad']);
           
          $update_prod = array(
            'prod_priori' => $prioridad,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_prod);
              
      }else{
          show_404();
      }
    }


    /*--- ELIMINAR TOD@S LAS ACTIVIDADES REQUERIMIENTOS DE LA UNIDAD (2025) ---*/
    public function delete_form4_form5($com_id){
      $form4=$this->model_producto->lista_form4_x_unidadresponsable($com_id); /// form 4
      foreach($form4 as $rowp){
        $insumos=$this->model_insumo->lista_insumos_prod($rowp['prod_id']); /// form 5
        foreach ($insumos as $rowi) {
          
          /*--------- delete temporalidad --------*/
           $this->db->where('ins_id', $rowi['ins_id']);
           $this->db->delete('temporalidad_prog_insumo');

           $this->db->where('ins_id', $rowi['ins_id']);
           $this->db->delete('_insumoproducto');

          /*--------- delete form 5 --------*/
           $this->db->where('ins_id', $rowi['ins_id']);
           $this->db->delete('insumos');

        }

        /*------ delete form 4 -----*/
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->delete('prod_programado_mensual');

        /*------ delete form 4 -----*/
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->delete('_productos');
      }

        ///-----------------
        $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE ');
        redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
    }


    /*--- ELIMINAR TOD@S LOS REQUERIMIENTOS DEL SERVICIO (SOLO REQUERIMIENTOS) (2025) ---*/
    public function delete_insumos_servicios($com_id){
    //  $productos = $this->model_producto->list_producto_programado($com_id,$this->gestion); // Lista de productos
      $productos=$this->model_producto->lista_form4_x_unidadresponsable($com_id);
      $nro=0;$nro_ins=0;
      //echo "eliminar productos";
      foreach($productos as $rowp){
        $insumos=$this->model_insumo->lista_insumos_prod($rowp['prod_id']);
        foreach ($insumos as $rowi) {
          /*--------- delete temporalidad --------*/
          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('temporalidad_prog_insumo');

          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('_insumoproducto');

          /*--------- delete Insumos --------*/
          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('insumos');

          if(count($this->model_insumo->get_insumo_producto($rowi['ins_id']))==0){
            $nro_ins++;
          }
        }
      }

      $update_prod= array(
        'fun_id' => $this->fun_id,
        'prod_ppto' => 1
      );
      $this->db->where('com_id', $com_id);
      $this->db->update('_productos', $update_prod);


      $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE '.$nro_ins.' REQUERIMIENTOS DE LA UNIDAD ');
      redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
    }






    /*--- ELIMINAR LISTA TOTAL DE REQUERIMEITNOS POR UNIDAD*/
    public function delete_list_requerimientos($aper_id){
      $insumos=$this->model_insumo->insumos_por_unidad($aper_id);
      $nro_ins=0;
      foreach ($insumos as $rowi) {
        /*--------- delete temporalidad --------*/
        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('temporalidad_prog_insumo');

        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('_insumoproducto');

        /*--------- delete Insumos --------*/
        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('insumos');

        if(count($this->model_insumo->get_insumo_producto($rowi['ins_id']))==0){
          $nro_ins++;
        }
      }

      return $nro_ins;
    }

    /*----------------- LISTA OPERACIONES PI (2020) ------------------*/
    // public function operaciones_pi($proy_id,$com_id){
    //   $tabla ='';
    //   $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
    //   $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
    //   $productos = $this->model_producto->list_operaciones_pi($com_id); // Lista de Operaciones
    //    $tabla .='<thead>
    //               <tr class="modo1">
    //                 <th style="width:1%; text-align=center"><b>#</b></th>
    //                 <th style="width:1%; text-align=center"><b>E/B</b></th>
    //                 <th style="width:2%;"><b>COD. OR.</b></th>
    //                 <th style="width:2%;"><b>COD. OPE.</b></th>
    //                 <th style="width:15%;"><b>OPERACI&Oacute;N</b></th>
    //                 <th style="width:15%;"><b>RESULTADO</b></th>
    //                 <th style="width:10%;"><b>TIP. IND.</b></th>
    //                 <th style="width:10%;"><b>INDICADOR</b></th>
    //                 <th style="width:1%;"><b>LINEA BASE '.($this->gestion-1).'</b></th>
    //                 <th style="width:1%;"><b>META</b></th>
    //                 <th style="width:4%;"><b>ENE.</b></th>
    //                 <th style="width:4%;"><b>FEB.</b></th>
    //                 <th style="width:4%;"><b>MAR.</b></th>
    //                 <th style="width:4%;"><b>ABR.</b></th>
    //                 <th style="width:4%;"><b>MAY.</b></th>
    //                 <th style="width:4%;"><b>JUN.</b></th>
    //                 <th style="width:4%;"><b>JUL.</b></th>
    //                 <th style="width:4%;"><b>AGO.</b></th>
    //                 <th style="width:4%;"><b>SEP.</b></th>
    //                 <th style="width:4%;"><b>OCT.</b></th>
    //                 <th style="width:4%;"><b>NOV.</b></th>
    //                 <th style="width:4%;"><b>DIC.</b></th>
    //                 <th style="width:10%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
    //               </tr>
    //             </thead>
    //             <tbody>';

    //     $cont = 0;
    //     foreach($productos as $rowp){
    //       $cont++;
    //       $tabla .='<tr class="modo1">';
    //         $tabla.='<td>'.$cont.'</td>';
            
    //         $tabla.='<td align="center">';
    //         $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a><br>
    //                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" ><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br>
    //                  <a href="'.site_url("").'/prog/requerimiento/'.$proy_id.'/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a><br>
    //                 </td>';
    //         $tabla.='<td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
    //         $tabla.='<td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
    //         $tabla.='<td>'.$rowp['prod_producto'].'</td>';
    //         $tabla.='<td>'.$rowp['prod_resultado'].'</td>';
    //         $tabla.='<td>'.$rowp['indi_descripcion'].'</td>';
    //         $tabla.='<td>'.$rowp['prod_indicador'].'</td>';
    //         $tabla.='<td>'.$rowp['prod_linea_base'].'</td>';
    //         $tabla.='<td>'.$rowp['prod_meta'].'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['enero'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['febrero'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['marzo'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['abril'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['mayo'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['junio'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['julio'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['agosto'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['septiembre'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['octubre'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['noviembre'],2).'</td>';
    //         $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['diciembre'],2).'</td>';
    //         $tabla.='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
    //       $tabla .='</tr>';
    //     }
    //     $tabla.='</tbody>';
    //   return $tabla;
    // }




    /*----- ELIMINAR VARIOS OPERACIONES SELECCIONADOS -----*/
    public function delete_operaciones(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']); /// com id
          $componente = $this->model_componente->get_componente($com_id,$this->gestion);
         // $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 

          $nro=0; $nro_ins=0;
          if (!empty($_POST["req"]) && is_array($_POST["req"]) ) {
          foreach ( array_keys($_POST["req"]) as $como){
            /*--------- delete Insumosproducto --------*/
            $insumos = $this->model_producto->insumo_producto($_POST["req"][$como]); /// Insumo del producto
            foreach ($insumos as $rowi) {
              /*--------- delete temporalidad --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('temporalidad_prog_insumo');

              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('_insumoproducto');

              /*--------- delete Insumos --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('insumos');

              if(count($this->model_insumo->get_insumo_producto($rowi['ins_id']))==0){
                $nro_ins++;
              }
            }

            /*------ delete Productos -----*/
              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->delete('prod_programado_mensual');

            /*------ delete Productos -----*/
              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->delete('_productos');
            
            
            $prod=$this->model_producto->get_producto_id($_POST["req"][$como]);
            if(count($prod)==0){
              $nro++;
            }
            
          }

          $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE '.$nro.' OPERACIONES SELECCIONADOS y '.$nro_ins.' REQUERIMIENTOS ');
          redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL ELIMINAR OPERACIONES');
          redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Operaciones</b></font>";
      }
    }
       



  /*------------- COMBO OBJETIVO ESTRATEGICO -----------------*/
    // public function combo_acciones_estrategicos(){
    //   $salida = "";
    //   $id_pais = $_POST["elegido"];
    //   // construimos el combo de ciudades deacuerdo al pais seleccionado
    //   $combog = pg_query('select *
    //                       from _acciones_estrategicas
    //                       where obj_id='.$id_pais.' and acc_estado!=3
    //                       order by acc_id asc');
    //   $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE ACCI&Oacute;N ESTRATEGICA', 'cp1252', 'UTF-8') . "</option>";
    //   while ($sql_p = pg_fetch_row($combog)) {
    //       $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[2].".- ".$sql_p[3] . "</option>";
    //   }
    //   echo $salida;
    // }



        /*------- GET ACCIONES ESTRATEGICAS -------*/
    // public function get_acciones_estrategicas(){
    //   if($this->input->is_ajax_request() && $this->input->post()){
    //     $post = $this->input->post();
    //     $obj_id = $this->security->xss_clean($post['obj_id']); /// Obj id

    //     $salida='';
    //     $combog = pg_query('select *
    //                       from _acciones_estrategicas
    //                       where obj_id='.$obj_id.' and acc_estado!=3
    //                       order by acc_id asc');
    //   $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE ACCI&Oacute;N ESTRATEGICA', 'cp1252', 'UTF-8') . "</option>";
    //   while ($sql_p = pg_fetch_row($combog)) {
    //       $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[2].".- ".$sql_p[3] . "</option>";
    //   }


    //     $result = array(
    //         'respuesta' => 'correcto',
    //         'salida' => $salida,
    //       );
          
    //     echo json_encode($result);
    //   }else{
    //       show_404();
    //   }
    // }





  /*--- ACTUALIZA CODIGO DE ACTIVIDAD (FORM 4) ----*/
  // public function update_codigo($com_id){  
  //   $this->programacionpoa->update_codigo_actividad($com_id);
  //   redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
  // }


  /*--- Verifica Codigo Operacion (vigente) ---*/ 
  // function verif_codigo(){
  //   if($this->input->is_ajax_request()){
  //       $post = $this->input->post();

  //       $codigo= $this->security->xss_clean($post['codigo']); /// Codigo
  //       $com_id= $this->security->xss_clean($post['com_id']); /// Componente id

  //       $verif_com_ope=$this->model_producto->verif_componente_operacion($com_id,$codigo);
  //       if(count($verif_com_ope)!=0){
  //         echo "true"; ///// no existe un CI registrado
  //       }
  //       else{
  //         echo "false"; //// existe el CI ya registrado
  //       } 
  //   }else{
  //     show_404();
  //   }
  // }

  
   
 /*------ ELIMINA EL PRODUCTO Y SUS REQUERIMIENTOS ------*/
    function desactiva_producto(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
          $insumos = $this->model_producto->insumo_producto($prod_id); /// Insumo del producto

          foreach ($insumos as $rowi) {
            /*--------- delete temporalidad --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('temporalidad_prog_insumo');

            $this->db->where('prod_id', $prod_id);
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('_insumoproducto');

            /*--------- delete Insumos --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('insumos');
          }

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('prod_programado_mensual');

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('_productos');

          $prod=$this->model_producto->get_producto_id($prod_id);
          if(count($prod)==0){
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
      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*------ REPORTE OPERACIONES POR COMPONENTE(2019 - 2020 - 2021) ----*/
    public function reporte_operacion_componente($com_id){
      $data['componente'] = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      if(count($data['componente'])!=0){
        if($this->gestion>2020){
          redirect(site_url("").'/prog/reporte_form4/'.$com_id.''); /// Reporte Form4 2021
        }
        else{
          $this->reporte_poa_2020($com_id); /// Reporte POA 2019-2020
        }
      }
      else{
        echo "Error !!!";
      }
    }


    /*------ Para reporte poa 2019-2020 -----*/
    public function reporte_poa_2020($com_id){
      $data['componente'] = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $data['mes'] = $this->mes_nombre();
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO

      $data['cabecera']=$this->cabecera_2020($com_id,1); /// Cabecera
      if($this->gestion==2019){ /// GESTION 2019
        if($data['proyecto'][0]['tp_id']==1){
          $data['operaciones']=$this->componente_operacion_pi_nuevo($com_id);
        }
        else{
          $data['operaciones']=$this->componente_operacion_nuevo($com_id);
        }
        
        $this->load->view('admin/programacion/producto/reporte_productos', $data);
      }
      else{ /// Para la gestion 2020
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']);
        }

        $data['operaciones']=$this->componente_operaciones($com_id); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        $this->load->view('admin/programacion/producto/reporte_productos2020', $data);
      }
    }


    /*----- TITULO SERVICIO OPERACION (2020 - Operaciones) tp:1 (pdf), 2:(Excel) -----*/
    public function cabecera_2020($com_id,$tp){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      //$proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $tabla='';
      $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%"><b>DIR. ADM.</b></td>
                      <td style="width:90%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%"><b>UNI. EJEC.</b></td>';
                      if($tp==1){  // pdf
                        $tabla.='<td style="width:90%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>';
                      }
                      else{ // excel
                        $tabla.='<td style="width:90%;">: '.mb_convert_encoding(strtoupper($proyecto[0]['dist_distrital']), 'cp1252', 'UTF-8').'</td>';
                      }
                      $tabla.='
                    </tr>
                    <tr style="font-size: 8pt;">';
                      if($this->gestion!=2020){
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='PROY. INV. ';
                          }
                          else{
                            $tabla.='ACTIVIDAD ';
                          }
                        $tabla.='</b></td>';
                        $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                      }
                      else{
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='PROY. INV. ';
                          }
                          else{
                            $tabla.=''.$proyecto[0]['tipo_adm'].' ';
                          }
                        $tabla.='</b></td>';
                        if($tp==1){ /// pdf
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                          }
                          else{
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                          }
                        }
                        else{ /// Excel
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(strtoupper($proyecto[0]['proy_nombre']), 'cp1252', 'UTF-8').'</td>';
                          }
                          else{
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.mb_convert_encoding(strtoupper($proyecto[0]['act_descripcion']), 'cp1252', 'UTF-8').'-'.$proyecto[0]['abrev'].'</td>';
                          }
                        }    
                    }
                    $tabla.='
                    </tr>
                    <tr style="font-size: 8pt;">';
                      if($this->gestion!=2020){
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='COMPONENTE ';
                          }
                          else{
                            $tabla.='SUB ACTIVIDAD ';
                          }
                        $tabla.='</b></td>';
                      }
                      else{
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='COMPONENTE ';
                          }
                          else{
                            $tabla.='SERVICIO ';
                          }
                        $tabla.='</b></td>';
                      }
                      if($tp==1){ // pdf
                        $tabla.='<td>: '.strtoupper($componente[0]['com_componente']).'</td>';
                      }
                      else{ // excel
                        $tabla.='<td>: '.mb_convert_encoding(strtoupper($componente[0]['com_componente']), 'cp1252', 'UTF-8').'</td>';
                      }
                      $tabla.='
                    </tr>
                </table>';
      return $tabla;
    }


    /*----- SERVICIO ACTIVIDAD (2020 - Operaciones, Proyectos de Inversion) - REPORTE ----*/
    public function componente_operaciones($com_id){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $mes = $this->mes_nombre();
      
      $tabla='';
      
      if($proyecto[0]['tp_id']==1){ /// Proyectos de Inversion
        $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%; color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:11%; color:#FFF;">ACTIVIDAD</th>
                    <th style="width:11%; color:#FFF;">RESULTADO</th>
                    <th style="width:11%; color:#FFF;">INDICADOR</th>
                    <th style="width:2%; color:#FFF;">LB.</th>
                    <th style="width:2.5%; color:#FFF;">META</th>
                    <th style="width:2.5%; color:#FFF;">ENE.</th>
                    <th style="width:2.5%; color:#FFF;">FEB.</th>
                    <th style="width:2.5%; color:#FFF;">MAR.</th>
                    <th style="width:2.5%; color:#FFF;">ABR.</th>
                    <th style="width:2.5%; color:#FFF;">MAY.</th>
                    <th style="width:2.5%; color:#FFF;">JUN.</th>
                    <th style="width:2.5%; color:#FFF;">JUL.</th>
                    <th style="width:2.5%; color:#FFF;">AGO.</th>
                    <th style="width:2.5%; color:#FFF;">SEPT.</th>
                    <th style="width:2.5%; color:#FFF;">OCT.</th>
                    <th style="width:2.5%; color:#FFF;">NOV.</th>
                    <th style="width:2.5%; color:#FFF;">DIC.</th>
                    <th style="width:8.5%; color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%; color:#FFF;">PPTO.</th>   
                  </tr>
                </thead>
                <tbody>';
                $operaciones=$this->model_producto->list_operaciones_pi($com_id);  /// 2020
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
                    <td style="height:12px;">'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width:11%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width:2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width:2.5%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>
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

         $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:10%;color:#FFF;">ACTIVIDAD</th>
                    <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                    <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                    <th style="width:9%;color:#FFF;">INDICADOR</th>
                    <th style="width:2%;color:#FFF;">LB.</th>
                    <th style="width:3%;color:#FFF;">META</th>
                    <th style="width:3%;color:#FFF;">ENE.</th>
                    <th style="width:3%;color:#FFF;">FEB.</th>
                    <th style="width:3%;color:#FFF;">MAR.</th>
                    <th style="width:3%;color:#FFF;">ABR.</th>
                    <th style="width:3%;color:#FFF;">MAY.</th>
                    <th style="width:3%;color:#FFF;">JUN.</th>
                    <th style="width:3%;color:#FFF;">JUL.</th>
                    <th style="width:3%;color:#FFF;">AGO.</th>
                    <th style="width:3%;color:#FFF;">SEPT.</th>
                    <th style="width:3%;color:#FFF;">OCT.</th>
                    <th style="width:3%;color:#FFF;">NOV.</th>
                    <th style="width:3%;color:#FFF;">DIC.</th>
                    <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%;color:#FFF;">PPTO.</th>   
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                $operaciones=$this->model_producto->lista_form4_x_unidadresponsable($com_id);
                
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
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
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




    /*--- MIGRACION DEL FORMULARIO N 4 (2020-2022) Y REQUERIMIENTOS (2025) ---*/
    function importar_operaciones_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']); /// com id
          $componente = $this->model_componente->get_componente_pi($com_id);
          $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
          $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($fase[0]['proy_id']); /// Lista de Objetivos Regionales
          $tp = $this->security->xss_clean($post['tp']); /// tipo de migracion

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
            /*------------------- Migrando ---------------*/
            $lineas = file($archivotmp);
            $i=0;
            $nro=0;
            $guardado=0;
            $no_guardado=0;
  
            if($tp==1){  /// Formulario N 4
              foreach ($lineas as $linea_num => $linea){ 
                if($i != 0){
                  $datos = explode(";",$linea);
                  if(count($datos)==22){

                    $cod_og = intval(trim($datos[0])); // Codigo ACP
                    $cod_or = intval(trim($datos[1])); // Codigo Operacion
                    $cod_form4 = intval(trim($datos[2])); // Codigo Form 4
                    $descripcion = strval(utf8_encode(trim($datos[3]))); //// descripcion form4
                    $resultado = strval(utf8_encode(trim($datos[4]))); //// descripcion Resultado
                    $unidad = strval(utf8_encode(trim($datos[5]))); //// Unidad responsable
                    //$unidad = intval(trim($datos[5])); //// id Unidad responsable PRG Bolsas
                    $indicador = strval(utf8_encode(trim($datos[6]))); //// descripcion Indicador
                    $lbase = intval(trim($datos[7])); //// Linea Base
                    $meta = intval(trim($datos[8])); //// Meta
                    $mverificacion = strval(utf8_encode(trim($datos[21]))); //// Medio de verificacion

                    $or_id=0;
                    if(count($list_oregional)!=0){
                      $get_acc=$this->model_objetivoregion->get_alineacion_proyecto_oregional($fase[0]['proy_id'],$cod_og,$cod_or);
                      if(count($get_acc)!=0){
                        $or_id=$get_acc[0]['or_id'];
                      }
                    }

                    if(strlen($descripcion)!=0 & strlen($resultado)!=0){
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array(
                          'com_id' => $com_id,
                          'prod_cod'=>$cod_form4,
                          'prod_producto' => strtoupper($descripcion),
                          'prod_resultado' => strtoupper($resultado),
                          'indi_id' => 1,
                          'prod_indicador' => strtoupper($indicador),
                          'prod_fuente_verificacion' => strtoupper($mverificacion), 
                          'prod_linea_base' => $lbase,
                          'prod_meta' => $meta,
                          'uni_resp' => 0, //// para prog bolsas
                          'prod_unidades' => $unidad,
                          'acc_id' => 0,
                          'prod_ppto' => 1,
                          'fecha' => date("d/m/Y H:i:s"),
                          'or_id'=>$or_id,
                          'fun_id' => $this->fun_id,
                          'num_ip' => $this->input->ip_address(), 
                          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                        );
                        $this->db->insert('_productos', $data_to_store);
                        $prod_id=$this->db->insert_id(); 


                        $var=9;
                        for ($i=1; $i <=12 ; $i++) {
                          $m[$i]=floatval(trim($datos[$var])); //// Mes i
                          if($m[$i]!=0){
                            if(strlen($m[$i])<=4){
                              $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$m[$i]);
                            }
                          }
                          
                          $var++;
                        }

                        $producto=$this->model_producto->get_producto_id($prod_id);
                        if(count($producto)!=0){
                          $guardado++;
                        }
                        else{
                          $no_guardado++;
                        }
                    }

                  }
                }
                $i++;
              }
              
              //// Actualizando Codigos
              $this->programacionpoa->update_codigo_actividad($com_id);
              $this->session->set_flashdata('success','SE REGISTRARON '.$guardado.' ACTIVIDADES');
            }
            else{ /// Requerimientos

            foreach ($lineas as $linea_num => $linea){
              if($i != 0){
                $datos = explode(";",$linea);
             
                if(count($datos)==20){
                    //echo count($datos).'<br>';
                    $prod_cod = intval(trim($datos[0])); //// Codigo Actividad
                    $cod_partida = intval(trim($datos[1])); //// Codigo partida
                    $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                    $detalle = strval(utf8_encode(trim($datos[2]))); //// descripcion form5
                    $unidad = strval(utf8_encode(trim($datos[3]))); //// Unidad
                    $cantidad = intval(trim($datos[4])); //// Cantidad
                    $unitario = floatval(trim($datos[5])); //// Costo Unitario
                    
                    $p_total=($cantidad*$unitario);
                    $total = floatval(trim($datos[6])); //// Costo Total

                    $var=7; $sum_temp=0;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=floatval(trim($datos[$var])); //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $var++;
                      $sum_temp=$sum_temp+$m[$i];
                    }

                    $observacion = strval(utf8_encode(trim($datos[19]))); //// Observacion
                    $verif_cod=$this->model_producto->verif_componente_operacion($com_id,$prod_cod);
                   // echo count($verif_cod).'--'.count($par_id).'--'.$cod_partida.'--'.round($sum_temp,2).'=='.round($total,2)."<br>";

                    if(count($verif_cod)!=0 & count($par_id)!=0 & $cod_partida!=0 & round($sum_temp,2)==round($total,2) & round($p_total,2)==round($total,2)){ /// Verificando si existe Codigo de Actividad, par id, Codigo producto
                        $producto=$this->model_producto->get_producto_id($verif_cod[0]['prod_id']); /// Get producto
                        $guardado++;
                        //echo $guardado.'---'.$detalle.'<br>';
                        /*-------- INSERTAR DATOS REQUERIMIENTO ---------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array( 
                        'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                        'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                        'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                        'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                        'ins_costo_unitario' => $unitario, /// Costo Unitario
                        'ins_costo_total' => $total, /// Costo Total
                        'ins_unidad_medida' => $unidad, /// Unidad de Medida
                        'ins_gestion' => $this->gestion, /// Insumo gestion
                        'par_id' => $par_id[0]['par_id'], /// Partidas
                        'ins_tipo' => 1, /// Ins Tipo
                        'ins_observacion' => strtoupper($observacion), /// Observacion
                        'fun_id' => $this->fun_id, /// Funcionario
                        'aper_id' => $proyecto[0]['aper_id'], /// aper id
                        'com_id' => $producto[0]['com_id'], /// com id 
                        'form4_cod' => $producto[0]['prod_cod'], /// aper id
                        'num_ip' => $this->input->ip_address(), 
                        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                        );
                        $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                        $ins_id=$this->db->insert_id();

                        /*--------------------------------------------------------*/
                          $data_to_store2 = array( ///// Tabla InsumoProducto
                            'prod_id' => $verif_cod[0]['prod_id'], /// prod id
                            'ins_id' => $ins_id, /// ins_id
                          );
                          $this->db->insert('_insumoproducto', $data_to_store2);
                        /*----------------------------------------------------------*/

                        for ($p=1; $p <=12 ; $p++) { 
                          if($m[$p]!=0 & is_numeric($unitario)){
                            $data_to_store4 = array(
                              'ins_id' => $ins_id, /// Id Insumo
                              'mes_id' => $p, /// Mes 
                              'ipm_fis' => $m[$p], /// Valor mes
                              'g_id' => $this->gestion, /// Gestion
                            );
                            $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                          }
                        }
                    }
               
                } /// end dimension (20)
              } /// i!=0

              $i++;

            }

              $this->session->set_flashdata('success','SE REGISTRARON '.$guardado.' REQUERIMIENTOS');
            } /// end else

            redirect('admin/prog/list_prod/'.$com_id.'');
          }
          else{
            $this->session->set_flashdata('danger','SELECCIONE ARCHIVO ');
            redirect('admin/prog/list_prod/'.$com_id.'');
          }
      }
      else{
        echo "Error !!";
      }
    }

    /*------ ACTUALIZA PRESUPUESTO EXISTENTE DE LAS OPERACIONES -------*/
    public function update_ptto_operaciones($com_id){
      //$operaciones=$this->model_producto->list_producto_programado($com_id,$this->gestion);
      $operaciones=$this->model_producto->lista_form4_x_unidadresponsable($com_id);
      foreach($operaciones as $rowp){
        $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
        if(count($monto)==0){
          $update_act= array(
            'prod_ppto' => 0,
            'fun_id' => $this->fun_id
          );
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->update('_productos', $update_act);
        }
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
    


/*    public function reporte_formulario4($com_id){
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        if(count($componente)!=0){
            $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); //// DATOS PROYECTO
            $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$componente[0]['serv_descripcion'];
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); /// PROYECTO
                $data['pie_rep']=$componente[0]['serv_descripcion'].'-'.$proyecto[0]['abrev'];
            }

            $data['cabecera']=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],4,$proyecto,$com_id);
            $data['operaciones']=$this->programacionpoa->operaciones_form4($componente,$proyecto); /// Reporte Gasto Corriente, Proyecto de Inversion 2022
           
            $data['pie']=$this->programacionpoa->pie_form($proyecto);
            $this->load->view('admin/programacion/reportes/reporte_form4', $data);
        }
        else{
            echo "Error !!!";
        }
    }*/



   /*------- VALIDA ADICIONAR PRODUCTO POR MODIFICACION -------*/
     public function valida_add_producto(){
      if ($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('proy_id', 'Proyecto Id', 'required|trim');
          $this->form_validation->set_rules('cite_id', 'Cite Id', 'required|trim');
          $this->form_validation->set_rules('com_id', 'Componente Id', 'required|trim');
          $this->form_validation->set_rules('prod', 'Producto', 'required|trim');

          $fase = $this->model_faseetapa->get_id_fase($this->input->post('proy_id'));
          for ($i=1; $i <=12 ; $i++) { 
            $m[$i]='m'.$i;
          }
          if ($this->form_validation->run()){

              /*---------------- Actualiza Ponderacion de productos -------------*/
                $list_prod=$this->model_producto->list_prod($this->input->post('com_id'));
                if(count($list_prod)!=0){
                  $pond=10;
                }
                else{
                 $pond=100; 
                }
                $ponderacion=$this->model_producto->suma_ponderacion($this->input->post('com_id'));
                foreach ($list_prod as $row) {
                  $update_prod = array(
                    'prod_ponderacion' => round((($row['prod_ponderacion']/100)*90),2), /// Ponderacion
                    'fun_id' => $this->session->userdata("fun_id")
                    );
                  $this->db->where('prod_id', $row['prod_id']);
                  $this->db->update('_productos', $update_prod);
                }
                /*-----------------------------------------------------------------*/

            /*------------------ Adiciona Producto -------------------*/
            $data_to_store = array(
              'com_id' => $this->input->post('com_id'),
              'prod_producto' => strtoupper($this->input->post('prod')),
              'indi_id' => $this->input->post('tipo_i'),
              'prod_indicador' => strtoupper($this->input->post('indicador')),
              'prod_formula' => strtoupper($this->input->post('formula')),
              'prod_linea_base' => $this->input->post('lb'),
              'prod_meta' => $this->input->post('met'),
              'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')), 
              'prod_supuestos' => strtoupper($this->input->post('supuestos')),
              'pt_id' => $this->input->post('p_t'),
              'prod_ponderacion' => $pond,
              'prod_total_casos' => strtoupper($this->input->post('c_a')),
              'prod_casos_favorables' => strtoupper($this->input->post('c_b')),
              'prod_denominador' => $this->input->post('den'),
              'fun_id' => $this->session->userdata("fun_id"),
              'prod_mod' => 2,
            );
              $this->db->insert('_productos', $data_to_store); 
            /*-------------------------------------------------------*/
            $prod_id=$this->db->insert_id();

            $gestion=$fase[0]['pfec_fecha_inicio'];
            if ( !empty($_POST["m1"]) && is_array($_POST["m1"]) ){
                foreach ( array_keys($_POST["m1"]) as $como ){
                  
                  for ($i=1; $i <=12 ; $i++) { 
                      if($_POST[$m[$i]][$como]!=0 || $_POST[$m[$i]][$como]!=''){
                        $this->model_producto->add_prod_gest($prod_id,$gestion,$i,$_POST[$m[$i]][$como]);
                      }
                  }
                $gestion++;        
              }
            }

            /*--------------------- iNSERT AUDI ADICIONAR PRODUCTOS -------------*/
              $data_to_store2 = array(
                'prod_id' => $prod_id, /// prod_id
                'ope_id' => $this->input->post('cite_id'), /// cite_id
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'fun_id' => $this->session->userdata("fun_id"),
                );
              $this->db->insert('_producto_add', $data_to_store2);
              $proda_id=$this->db->insert_id();

              if(count($this->model_modificacion->get_add_producto($proda_id))==1){
                $this->session->set_flashdata('success','EL PRODUCTO SE AGREGO CORRECTAMENTE');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
              else{
                $this->session->set_flashdata('danger','NO SE GUARDO CORRECTAMENTE, VERIFIQUE DATOS');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
          }
          else{
            redirect('admin/mod/add_producto/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id')."/".$this->input->post('com_id').'/error');
          }
      }
   }

    /*--------------- GENERA MENU -------------*/
    public function genera_menu($proy_id){
      $id_f = $this->model_faseetapa->get_id_fase($proy_id);
      $enlaces=$this->menu_modelo->get_Modulos_programacion(2);
      $tabla='';
      $tabla.='
          <nav>
            <ul>
                <li>
                    <a href='.site_url("admin").'/dashboard'.' title="MENU PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                </li>
                <li class="text-center">
                    <a href='.base_url().'index.php/admin/proy/mis_proyectos/1'.' title="PROGRAMACI&Oacute;N POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N POA</span></a>
                </li>';
                if(count($id_f)!=0){
                    for($i=0;$i<count($enlaces);$i++){ 
                        $tabla.='
                        <li>
                            <a href="#" >
                                <i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>
                            <ul >';
                            $submenu= $this->menu_modelo->get_Modulos_sub($enlaces[$i]['o_child']);
                            foreach($submenu as $row) {
                               $tabla.='<li><a href='.base_url($row['o_url'])."/".$id_f[0]['proy_id'].'>'.$row['o_titulo'].'</a></li>';
                            }
                        $tabla.='</ul>
                        </li>';
                    }
                }
            $tabla.='
            </ul>
          </nav>';

      return $tabla;
    }

}