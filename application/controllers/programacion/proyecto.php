<?php
class Proyecto extends CI_Controller {  
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
      $this->load->library('pdf2');
      $this->load->model('menu_modelo');
      $this->load->model('Users_model','',true);
      $this->load->model('programacion/model_faseetapa');
      $this->load->model('programacion/model_proyecto');
      $this->load->model('programacion/model_componente');
      $this->load->model('programacion/model_producto');
      $this->load->model('programacion/model_actividad');
      $this->load->model('mantenimiento/mapertura_programatica');
      $this->load->model('mantenimiento/munidad_organizacional');
      $this->load->model('mantenimiento/model_estructura_org');
      $this->load->model('programacion/insumos/minsumos');
      $this->load->model('programacion/insumos/model_insumo');
      $this->load->model('mestrategico/model_objetivoregion');
      $this->load->model('mantenimiento/model_ptto_sigep');
      $this->load->library('security');
      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm'); // 1: Nacional, 2: Regional, Distrital
      $this->dist = $this->session->userData('dist');
      $this->rol = $this->session->userData('rol_id');
      $this->fun_id = $this->session->userdata("fun_id");
      $this->tp_adm = $this->session->userdata("tp_adm");
      $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
      $this->verif_ppto = $this->session->userData('verif_ppto'); /// AnteProyecto Ptto POA : 0, Ptto Aprobado Sigep : 1
      }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }

    /*--- TIPO DE RESPONSABLE ---*/
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

  /*=== LISTA DE PROYECTOS TECNICO DE UE (2020) ===*/  
    public function list_proyectos(){
      $data['menu']=$this->menu(2);
      $data['mod']=1;
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();

      $data['operacion']=$this->list_unidades_es(1); /// Gasto Corriente
      $data['proyectos']=$this->list_pinversion(1); /// Proyecto de Inversion

      $this->load->view('admin/programacion/proy_anual/top/list_proy', $data);
    }

    /*---- Lista de Unidades / Establecimientos de Salud (2020) -----*/
    public function list_unidades_es($proy_estado){
      if($this->gestion==2018){
        $unidades=$this->model_proyecto->list_unidades_2018(4,$proy_estado);
      }
      else{
        $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
      }

      $tabla='';
      
      if($proy_estado==1){
        foreach($unidades as $row){
          if($row['tp_obs']==2){$obs='POA';}
          elseif($row['tp_obs']==3){$obs='FINANCIERO';}
          elseif($row['tp_obs']==4){$obs='TECNICO OPERATIVO';}
          $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
          $tabla.='<tr style="height:35px;">';
            $tabla .= '<td>';
              if(count($fase)!=0){
                $tabla .= '<center><a href="'.site_url("").'/prog/list_serv/'.$row['proy_id'].'" title="PROGRAMACION F&Iacute;SICA - FINANCIERA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/bien.png" WIDTH="30" HEIGHT="30"/></a></center>';
              }
            $tabla .= '</td>';
           $tabla .='<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff2" class="btn btn-default enlace2" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' - '.strtoupper($row['abrev']).'"><img src="'.base_url().'assets/img/ajuste_ppto.jpg" WIDTH="45" HEIGHT="45" title="AJUSTAR POA '.$this->gestion.'"/></a></center></td>';
           // $tabla .='<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' - '.strtoupper($row['abrev']).'">VER POA</a></center></td>';

            if($this->verif_ppto==1){
              $tabla .='<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff2" class="btn btn-default enlace2" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' - '.strtoupper($row['abrev']).'"><img src="'.base_url().'assets/img/ajuste_ppto.jpg" WIDTH="45" HEIGHT="45" title="AJUSTAR POA '.$this->gestion.'"/></a></center></td>';
            }

            $tabla .= '<td aling="center">';
              if($this->tp_adm==1){
                $tabla .= '<center><a href="'.site_url("").'/proy/update_unidad/'.$row['proy_id'].'" title="MODIFICAR DATOS DE LA UNIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="34" HEIGHT="30"/></a></center>';
              }
              /*---------------------------------------------*/
              if($this->session->userdata('rol_id')==1 & $this->session->userdata('tp_adm')==1){
                $tabla .= '<center><a href="'.site_url("admin").'/proy/delete/1/'.$row['proy_id'].'" title="ELIMINAR" onclick="return confirmar()" class="btn btn-default"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="34" HEIGHT="30"/></a></center>';
              }                              
            $tabla .= '</td>';
            $tabla .= '<td style="font-size: 9pt;">';
            if($row['aper_proyecto']!=''){
              $tabla .= '<center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center>';
            }
            else{
              $tabla .= '<center><font color="red"><b>Re asignar apertura Programatica</b></font></center>';
            }
            if($row['tp_obs']!=0 & $row['tp_obs']!=1){
              $tabla .= '<br><center><a data-toggle="modal" data-target="#modal_mod_aper2" class="btn btn-xs mod_aper2" name="'.$row['aper_observacion'].'" id="'.$row['tp_obs'].'"><img src="'.base_url().'assets/ifinal/1.png" WIDTH="35" HEIGHT="35"/></a><font color="red" size=1>'.$obs.'</font></center>';
            }
            
            $tabla .= '</td>';
            if($this->gestion>=2020){
              $tabla.='<td style="font-size: 9pt;"><b>'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</b></td>';
            }
            else{
              $tabla.='<td style="font-size: 9pt;"><b>'.$row['proy_nombre'].'</b></td>';
            }
            
            $tabla.='<td>'.$row['nivel'].'</td>';
            $tabla.='<td>'.$row['tipo_adm'].'</td>';
            $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td>
                      <center>
                        <a href="#" data-toggle="modal" data-target="#modal_verif_poa" class="btn btn-default verif_poa" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' - '.strtoupper($row['abrev']).'" title="VALIDAR POA" ><img src="'.base_url().'assets/img/ok1.jpg" WIDTH="35" HEIGHT="35"/></a>
                      </center>
                      </td>';
          $tabla.='</tr>';
        }
      }
      else{
        $nro=0;
        foreach($unidades as $row){
          $color='#f5e9ce';
          $estado='REVISI&Oacute;N';
          if($row['proy_estado']==4){
            $color='#ccefcc';
            $estado='APROBADO';
          }

          $nro++;
          $tabla.='<tr style="height:35px;" bgcolor="'.$color.'">';
            $tabla .= '<td title="POA '.$estado.'"><center>'.$nro.'</center></td>';
            $tabla .= '<td title="REPORTE FORMULARIO 3">';
              if($row['te_id']!=14 & $row['te_id']!=17 & $row['te_id']!=18 & $row['te_id']!=20){
                $tabla .= '<center><a href="javascript:abreVentana(\''.site_url("").'/as/rep_list_foda/'.$row['proy_id'].'\');" title="REPORTE FORMULARIO N 3" class="btn btn-success">FORM N 3</a></center>';
              }
            $tabla .= '</td>';
            $tabla .= '<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['tipo']).' '.strtoupper($row['proy_nombre']).' '.strtoupper($row['abrev']).'">VER POA</a></center></td>';
            
            if($this->adm==1){ 
              $tabla .= '<td><center><a href="#" data-toggle="modal" data-target="#modal_neg_ff" class="btn btn-danger neg_ff" title="OBSERVAR POA"  name="'.$row['proy_id'].'" ><img src="'.base_url().'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></a></center></td>';
                $tabla .= '<td>
                            <center>';
                            if($row['proy_estado']!=4){
                              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_aprobar_poa" class="btn btn-success aprobar_poa" title="APROBAR POA"  name="'.$row['proy_id'].'" ><img src="'.base_url().'assets/img/ok1.jpg" WIDTH="35" HEIGHT="35"/></a>';
                            }
                            $tabla.='
                            </center>
                          </td>';
            }
           
            $tabla .= '<td>';
            if($row['aper_proyecto']!=''){
              $tabla .= '<center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center>';
            }
            else{
              $tabla .= '<center><font color="red"><b>Re asignar apertura Programatica</b></font></center>';
            }
            $tabla .= '</td>';
            if($this->gestion>=2020){
              $tabla.='<td title='.$row['proy_id'].'>'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>';
            }
            else{
              $tabla.='<td>'.$row['proy_nombre'].'</td>';
            }
            $tabla.='<td>'.$row['escalon'].'</td>';
            $tabla.='<td>'.$row['nivel'].'</td>';
            $tabla.='<td>'.$row['tipo_adm'].'</td>';
            $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td><center><b>'.$estado.'</b></center></td>';
          $tabla.='</tr>';
        }
      }
      return $tabla;
    }

    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($proy_estado){
      $proyectos=$this->model_proyecto->list_pinversion(1,$proy_estado);
      $tabla='';
      if($proy_estado==1){
        foreach($proyectos as $row){
          $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
          $tabla.='<tr style="height:35px;">';
            $tabla .= '<td title='.$row['proy_id'].'>';
              if(count($fase)!=0){
                if($this->adm==1){ 
                  $tabla .= '<center><a href="'.site_url("").'/prog/list_serv/'.$row['proy_id'].'" title="PROGRAMACION FÍSICA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/bien.png" WIDTH="30" HEIGHT="30"/></a></center>';
                }
              }
            $tabla .= '</td>';

            $tabla .='<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">VER POA</a></center></td>';

            $tabla .= '<td aling="center">';
              $tabla .= '<center><a href="'.site_url("admin").'/proy/edit/'.$row['proy_id'].'" title="MODIFICAR OPERACION" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="34" HEIGHT="30"/></a></center>';
              $tabla .='<center><a href="'.site_url("admin").'/proy/fase_etapa/'.$row['proy_id'].'" title="FASE ETAPA DEL PROYECTO" class="btn btn-default"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="34" HEIGHT="34"/></a></center>';
              /*---------------------------------------------*/
              if($this->session->userdata('rol_id')==1 & $this->session->userdata('tp_adm')==1){
                $tabla .= '<center><a href="'.site_url("admin").'/proy/delete/1/'.$row['proy_id'].'" title="ELIMINAR" onclick="return confirmar()" class="btn btn-default"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="34" HEIGHT="30"/></a></center>';
              }                              
            $tabla .= '</td>';
            $tabla .= '<td><center>'.$row['aper_programa'].''.$row['proy_sisin'].''.$row['aper_actividad'].'</center></td>';
            $tabla.='<td>'.$row['proy_nombre'].'</td>';
            $tabla.='<td>'.$row['proy_sisin'].'</td>';
            $tabla.='<td>'.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
            if(count($fase)!=0){
              $nc=$this->model_faseetapa->calcula_nc($fase[0]['pfec_fecha_inicio']); //// calcula nuevo/continuo
              $ap=$this->model_faseetapa->calcula_ap($fase[0]['pfec_fecha_inicio'],$fase[0]['pfec_fecha_fin']);
              $tabla .='<td>* '.$fase[0]['fase'].'<br>* '.$fase[0]['etapa'].'</td>';
              $tabla .='<td>'.$nc.'</td>';
              $tabla .='<td>'.$ap.'</td>';
            }
            else{
              $tabla .='<td bgcolor=#efb0b0><font color=red>Sin Fase</font></td>';
              $tabla .='<td bgcolor=#efb0b0><font color=red>Sin Fase</font></td>';
              $tabla .='<td bgcolor=#efb0b0><font color=red>Sin Fase</font></td>';
            }
            $tabla .='<td title="'.$row['aper_id'].'">';
              if(count($this->model_ptto_sigep->suma_ptto_pinversion($row['proy_id']))!=0){
                $tabla .= '<center><a href="#" data-toggle="modal" data-target="#modal_neg_ff" class="btn btn-default neg_ff" title="VALIDAR PROYECTO POA" name="'.$row['proy_id'].'" ><img src="'.base_url().'assets/img/ok1.jpg" WIDTH="35" HEIGHT="35"/></a></center><br>';
              }
              /*if($this->tp_adm==1 & $this->gestion!=2019){
                $tabla .= '<center><a href="'.site_url("").'/prog/update_insumos/'.$row['proy_id'].'" title="ACTUALIZA REQUERIMIENTOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/rever.png" WIDTH="30" HEIGHT="30"/></a></center>';
              }*/
            $tabla .='</td>';
          $tabla.='</tr>';
        }
      }
      else{
        $nro=0;
        foreach($proyectos as $row){
          $nro++;
          $tabla.='<tr style="height:35px;">';
           $tabla .= '<td title='.$row['proy_id'].'><center>'.$nro.'</center></td>';
            $tabla .= '<td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).')">VER POA</a></center></td>';
            $tabla .= '<td>';
              if($this->adm==1){ 
                $tabla.='<center><a href="#" data-toggle="modal" data-target="#modal_neg_ff" class="btn btn-default neg_ff" title="OBSERVAR PROYECTO"  name="'.$row['proy_id'].'" ><img src="'.base_url().'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></center>';
              }
            $tabla .= '</td>';
            $tabla .= '<td><center>'.$row['aper_programa'].''.$row['proy_sisin'].''.$row['aper_actividad'].'</center></td>';
            $tabla .= '</td>';
            $tabla.='<td>'.$row['proy_nombre'].'</td>';
            $tabla.='<td>'.$row['proy_sisin'].'</td>';
            $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
            $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
            $tabla .='<td title='.$row['pfec_id'].'>'.strtoupper($row['pfec_descripcion']).'</td>';
            if($row['pfec_estado']==0){
              $tabla.='<td bgcolor="#f5d2d2">FASE NO ACTIVA PARA LA GESTI&Oacute;N '.$this->gestion.'</td>';
            }
            else{
              $tabla.='<td >FASE ACTIVA PARA LA GESTI&Oacute;N '.$this->gestion.'</td>';
            }        
          $tabla.='</tr>';
        }
      }
      
      return $tabla;
    }


    /*-------- GET DATOS POA --------*/
    public function get_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO

        $caratula_poa='';
        $titulo_poa=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
        if($proyecto[0]['tp_id']==4){
          $titulo_poa=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'];
          $caratula_poa='
            <a href="javascript:abreVentana(\''.site_url("").'/proy/presentacion/'.$proy_id.'\');" title="CARATULA POA"  class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="45" HEIGHT="45"/><br>CARATULA POA</a>';
        }

        $tabla=$this->mi_poa($proy_id); /// Mis Subactividades
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
          'proyecto'=>$proyecto,
          'titulo_poa'=>$titulo_poa,
          'caratula'=>$caratula_poa,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ GET POA -----*/
    public function mi_poa($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th >NRO.</th>
                    <th >SERVICIO / COMPONENTE </th>
                    <th >PONDERACI&Oacute;N</th>';
                      if($this->gestion>2020){
                        $tabla.='<th >OPERACIONES<br>FORM. N 4</th>';
                      }
                      else{
                        $tabla.='<th >ACTIVIDADES<br>FORM. N 4</th>';
                      }
                    $tabla.='
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
                              <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$pr['com_id'].'\');" title="REPORTE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                            </td>
                            <td align=center>';
                              if($proyecto[0]['tp_id']==1){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>';
                              }
                              elseif(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($this->gestion>2019 & $nro_ppto>0){
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
                     /* else{ /// POA ANTE PROYECTO
                        $tabla.='
                      <tr bgcolor="#d6ecb3">
                        <td colspan=4><b>CONSOLIDADO PRESUPUESTO COMPARATIVO ANTEPROYECTO POR PARTIDAS </b></td>
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                      }*/
                    }
                  $tabla.='
                  
                </table>';

      return $tabla;
    }

    /*-------- GET AJUSTE DATOS POA --------*/
    public function get_poa_ajuste(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO

        $tabla=$this->mi_poa_ajuste($proy_id);
        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ GET POA PARA AJUSTE -----*/
    public function mi_poa_ajuste($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th bgcolor="#1c7368">NRO.</th>
                    <th bgcolor="#1c7368">SERVICIO / COMPONENTE </th>
                    <th bgcolor="#1c7368">PONDERACI&Oacute;N</th>';
                      if($this->gestion==2019){
                        $tabla.='<th bgcolor="#1c7368">OPERACIONES</th>';
                      }
                      else{
                        $tabla.='<th bgcolor="#1c7368">ACTIVIDADES</th>';
                      }
                    $tabla.='
                    <th bgcolor="#1c7368">FORM. N 4</th>
                    <th bgcolor="#1c7368">REQUERIMIENTOS</th>
                    <th bgcolor="#1c7368">REQUERIMIENTOS<br>FORM. N 5</th>
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
                              if($proyecto[0]['tp_id']==1){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>';
                              }
                              elseif(count($this->minsumos->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($this->gestion>2019 & $nro_ppto>0){
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

    /*-------- GET VERIF POA --------*/
    public function verif_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
        $tabla='';
        
        if(count($this->model_insumo->insumos_por_unidad($proyecto[0]['aper_id']))!=0){
          $nro_dif=$this->verif_cuadro_comparativo($proyecto);

          if($nro_dif==0){
           $tabla.='
              <hr><h3><b>&nbsp;&nbsp;'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'].'</b></h3><hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> POA-PRESUPUESTO '.$this->gestion.' LISTO PARA SER VALIDADO</2> 
              </div>';
            }
            else{
               $tabla.='
                <hr><h3><b>&nbsp;&nbsp;'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'].'</b></h3><hr>
                <div class="alert alert-danger alert-block" align=center>
                    <h2>POR CORREGIR PRESUPUESTO POA '.$this->gestion.'</h2>
                </div>';
            }
        }
        else{
          $nro_dif=-1;
          $tabla.='
              <hr><h3><b>&nbsp;&nbsp;'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'].'</b></h3><hr>
              <div class="alert alert-danger alert-block" align=center>
                  <h2>SIN PROGRAMACI&Oacute;N FINANCIERA '.$this->gestion.'</h2>
              </div>';
        }

        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
            'proyecto'=>$proyecto,
            'valor'=>$nro_dif,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------ CUADRO COMPARATIVO PTTO. ASIG - PROG (Para aprobar)------*/
    public function verif_cuadro_comparativo($proyecto){
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],1); // Asig
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],2); // Prog

      $nro_dif=0;
      foreach($partidas_asig  as $row){
        $part=$this->model_ptto_sigep->get_partida_accion_regional($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
        $prog=0;
        
        if(count($part)!=0){
          $prog=$part[0]['monto'];
        }

        $dif=($row['monto']-$prog);
        
        if($dif!=0){
          $nro_dif++;
          break;
        }
      }

      foreach($partidas_prog as $row){
        $part=$this->model_ptto_sigep->get_partida_asig_accion($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],$row['par_id']);
         if(count($part)==0){ 
          $asig=0;
          if(count($part)!=0){
            $asig=$part[0]['monto'];
          }
          $dif=($asig-$row['monto']);

          if($dif!=0){
            $nro_dif++;
            break;
          }

        }  
      }

      return $nro_dif;
    }


    /*------ POA APROBADO (2020) ------*/
    public function list_proyectos_aprobados(){
      $data['menu']=$this->menu(2);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();

      $data['proyectos']=$this->list_pinversion(4); /// Proyectos de Inversion
      $data['operacion']=$this->list_unidades_es(4); /// Gasto Corriente

      $this->load->view('admin/programacion/proy_anual/aprobados/list_proy', $data);
    }


  /*----- FORMULARIO DE REGISTRO DE UNIDADES/ESTABLECIMIENTOS (2020) -----*/
  function form_poa_unidades(){
    $data['menu']=$this->menu(2);
    $data['res_dep']=$this->tp_resp();
    if($this->tp_adm==1){
      $data['form']=$this->formulacion_add_poa_adm();
    }
    else{
      $data['form']=$this->formulacion_add_poa();
    }

    $this->load->view('admin/programacion/prog_operaciones/formularios/form_add_of', $data);
  }


  /*--- FORMULARIO DE MODIFICACIÓN DE UNIDADES/ESTABLECIMIENTOS (2020) ---*/
  function form_update_poa_unidades($proy_id){
    $data['menu']=$this->menu(2);
    $data['res_dep']=$this->tp_resp();
    $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
    if(count($data['proyecto'])!=0){
      $data['form']=$this->formulacion_update_poa($data['proyecto']);

      $this->load->view('admin/programacion/prog_operaciones/formularios/form_edit_of', $data);
    }
    else{
       $this->session->set_flashdata('danger','ERROR AL REGISTRAR');
      redirect('admin/proy/list_proy'); ///// Lista de Unidades/ Establecimientos
    }
  }

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
                          <label class="label">UNIDAD / ESTABLECIMIENTO DE SALUD</label>
                          <select class="select2" id="act_id" name="act_id" title="SELECCIONE UNIDAD / ESTABLECIMIENTO DE SALUD">
                          <option value="">SELECCIONE UNIDAD / ESTABLECIMIENTO</option>
                          </select>
                        </section>
                        <section class="col col-5">
                          <label class="label">VINCULACI&Oacute;N A OBJETIVO REGIONAL</label>
                          <div id="oregional"></div>
                        </section>

                        <section class="col col-4">
                          <label class="label">SERVICIOS DISPONIBLES</label>
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
                        <label class="label">UNIDAD / ESTABLECIMIENTO DE SALUD</label>
                        <select  id="act_id" name="act_id" title="SELECCIONE UNIDAD / ESTABLECIMIENTO DE SALUD">
                        <option value="">SELECCIONES UNIDAD / ESTABLECIMIENTO</option>';
                        foreach($unidades as $row){
                          if(count($this->model_proyecto->get_uni_apertura_programatica($row['act_id']))==0){
                            $tabla.='<option value="'.$row['act_id'].'">'.$row['act_cod'].'.- '.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>

                      <section class="col col-5">
                        <label class="label">VINCULACI&Oacute;N A OBJETIVO REGIONAL</label>
                        <div id="oregional"></div>
                      </section>

                      <section class="col col-4">
                        <label class="label">SERVICIOS DISPONIBLES</label>
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
                  <input type="hidden" name="tp" id="tp" value="1">
                  <header><b>FORMULACI&Oacute;N POA '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label">UNIDAD / ESTABLECIMIENTO DE SALUD '.$proyecto[0]['act_id'].'</label>
                        <select class="select2" id="act_id" name="act_id" title="SELECCIONE UNIDAD / ESTABLECIMIENTO DE SALUD" disabled>
                        <option value="'.$actividad[0]['act_id'].'">'.$actividad[0]['act_cod'].'.- ('.$actividad[0]['tipo'].') '.$actividad[0]['act_descripcion'].'</option></select>
                      </section>

                      <section class="col col-5">
                        <label class="label">VINCULACI&Oacute;N A OBJETIVO REGIONAL</label>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">OBJETIVO REGIONAL '.$this->gestion.'</th>
                              <th scope="col">OBJETIVO DE GESTI&Oacute;N '.$this->gestion.'</th>
                              <th scope="col"></th>
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
                              <td>'.$cont.'</td>
                              <td>'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</td>
                              <td>'.$row['og_codigo'].'.- '.$row['og_objetivo'].'</td>
                              <td>';
                              if(count($verif)!=0){
                                $tabla.='<center><input type="checkbox" onclick="scheck'.$cont.'(this.checked,'.$row['por_id'].','.$proyecto[0]['proy_id'].');" title="OBJETIVO SELECCIONADO" checked/></center>';
                              }
                              else{
                                $tabla.='<center><input type="checkbox" onclick="scheck'.$cont.'(this.checked,'.$row['por_id'].','.$proyecto[0]['proy_id'].');" title="SELECCIONE OBJETIVO REGIONAL"/></center>';
                              }
                              $tabla.='
                              </td>
                            </tr>';
                            ?>
                            <script>
                              function scheck<?php echo $cont;?>(estaChequeado,id,proy_id) {
                                valor=0;
                                titulo='DESACTIVAR OBJETIVO REGIONAL';
                                if (estaChequeado == true) {
                                  valor=1;
                                  titulo='ACTIVAR OBJETIVO REGIONAL';
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
                          $tabla.='
                          </tbody>
                        </table>
                      </section>

                      <section class="col col-4">
                        <label class="label">SERVICIOS DISPONIBLES</label>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">C&Oacute;DIGO</th>
                              <th scope="col">SERVICIO '.$this->gestion.'</th>
                              <th scope="col"></th>
                              <th scope="col">PROG.</th>
                            </tr>
                          </thead>
                          <tbody>';
                          $cont = 0;
                          foreach($servicios as $row){
                            $veri_cs=$this->model_proyecto->verif_componente_servicio($fase[0]['id'],$row['serv_id']);
                            $cont++;
                            $tabla.='
                            <tr>
                              <td>'.$cont.'</td>
                              <td>'.$row['serv_cod'].'</td>
                              <td>'.$row['serv_descripcion'].'</td>';
                                if($row['serv_id']!=0){
                                  if(count($veri_cs)!=0){
                                  $tabla.='
                                  <td align="center">';
                                    if(count($this->model_producto->list_producto_programado($veri_cs[0]['com_id'],$this->gestion))==0){
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
                                titulo='DESACTIVAR SERVICIO';
                                if (estaChequeado == true) {
                                  valor=1;
                                  titulo='ACTIVAR SERVICIO';
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

    /*--- ACTIVAR, DESACTIVAR OBJETIVO REGIONAL -----*/
    function estado_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('id', 'id unidad', 'required|trim'); // por_id 
          $this->form_validation->set_rules('estado', 'estado', 'required|trim'); // Activo/Desactivo
          
          $post = $this->input->post();
          $id= $this->security->xss_clean($post['id']); 
          $estado_activo = $this->security->xss_clean($post['estado']);
          $proy_id = $this->security->xss_clean($post['proy_id']);
         
          if($estado_activo==1){ /// Activar unidad a la gestion
              $data_to_store3 = array(
                'proy_id' => $proy_id,
                'por_id' => $id,
              );
              $this->db->insert('proy_oregional', $data_to_store3);
          }
          else{ /// Desactivar unidad a la gestion
            $this->db->where('por_id', $id);
            $this->db->delete('proy_oregional');
          }
    
      }else{
          show_404();
      }
    }

    /*--- ACTIVAR, DESACTIVAR SERVICIOS / COMPONENTES -----*/
    function estado_servicios(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('id', 'id unidad', 'required|trim'); // serv_id 
          $this->form_validation->set_rules('estado', 'estado', 'required|trim'); // Activo/Desactivo
          
          $post = $this->input->post();
          $id= $this->security->xss_clean($post['id']); 
          $estado_activo = $this->security->xss_clean($post['estado']);
          $pfec_id = $this->security->xss_clean($post['pfec_id']);
          $servicio=$this->model_estructura_org->get_servicio_actividad_id($id);

          if($estado_activo==1){ /// Activar Servicio
              $data_to_store = array( 
                'pfec_id' => $pfec_id,
                'com_componente' => $servicio[0]['serv_descripcion'],
                'resp_id' => $this->fun_id,
                'serv_id' => $id,
                'fun_id' => $this->fun_id,
                );
              $this->db->insert('_componentes', $data_to_store);
          }
          else{ /// Desactivar Servicio
            $this->db->where('serv_id', $id);
            $this->db->where('pfec_id', $pfec_id);
            $this->db->delete('_componentes');
          }
    
      }else{
          show_404();
      }
    }

    /*---------  DIRECCION ADMINISTRATIVA --------------*/
    public function combo_da($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'distrital':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT *
          from _distritales 
          where  dep_id='.$id_pais.' and dist_adm=1');
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[5]." - ".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }

    /*---COMBO DE UNIDADES / ESTABLECIMIENTOS SEGUN SU REGIONAL (2020)---*/
    public function como_unidad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $reg_id = $this->security->xss_clean($post['reg_id']);
        
        $tabla=$this->list_unidades_de_regional($reg_id);
        $result = array(
          'respuesta' => 'correcto',
          'unidades' => $tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- LISTA DE UNIDADES SEGUN LA REGIONAL ----*/
    public function list_unidades_de_regional($dep_id){
      $tabla='';
      $unidades=$this->model_estructura_org->list_unidades_de_regional($dep_id);
      $tabla.='<option value="">SELECCIONE UNIDAD / ESTABLECIMIENTO</option>';
      foreach($unidades as $row){
        if(count($this->model_proyecto->get_uni_apertura_programatica($row['act_id']))==0){
          $tabla.='<option value='.$row['act_id'].'>'.$row['act_cod'].'.- '.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</option>';
        }
      }
      return $tabla;
    }
    

    /*---------- VALIDA POA DATOS (2020) ---------*/
    public function valida_poa_unidades(){
      if($this->input->post()) {
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']); /// unidad id
        $cod_act = $this->security->xss_clean($post['act']); /// codigo actividad
        $actividad= $this->model_estructura_org->get_actividad($act_id); /// get actividad
        
          /*--- Insert a la tabla proyectos ---*/
          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'proy_codigo' => $actividad[0]['act_cod'],
            'proy_nombre' => strtoupper($actividad[0]['act_descripcion']),
            'tp_id' => 4,
            'proy_gestion_inicio_ddmmaaaa' => '01/01/'.$this->gestion.'',
            'proy_gestion_fin_ddmmaaaa' => '31/12/'.$this->gestion.'',
            'dep_id' => $actividad[0]['dep_id'],
            'dist_id' => $actividad[0]['dist_id'],
            'proy_fecha_registro' => date("d/m/Y H:i:s"),
            'fun_id' => $this->fun_id,
            'act_id' => $act_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('_proyectos', $data_to_store);
          $proy_id=$this->db->insert_id();
          /*---------------------------------*/
          /*--------- RESPONSABLES DE LA OPERACION --------*/
          $this->model_proyecto->add_resp_proy($proy_id,$this->fun_id,$this->fun_id,0,0,0);
          
          /*--------- FECHAS INICIAL-FINAL : OPERACION ----------*/
          $fechas = $this->model_proyecto->fechas_proyecto($proy_id);  // devuelve las fechas del proyecto inicio-conclusion

          /*--------- UPDATE DATOS OPERACION ------------*/
          $update_proyect = array(
            'proy_gestion_inicio' => $fechas[0]['inicio'],
            'proy_gestion_fin' => $fechas[0]['final'],
            'proy_gestion_impacto' => ($fechas[0]['final']-$fechas[0]['inicio'])+1
          );
          $this->db->where('proy_id', $proy_id);
          $this->db->update('_proyectos', $update_proyect);

          /*--------- ADD APERTURA PROGRAMATICA ---------*/
          $this->model_proyecto->add_apertura($proy_id,$actividad[0]['aper_gestion'],$actividad[0]['aper_programa'],$actividad[0]['aper_proyecto'],$cod_act,$actividad[0]['act_descripcion'],$this->fun_id);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

           /*--------- INSERT FASE ETAPA COMPONENTE --------*/ 
           $query=$this->db->query('set datestyle to DMY');
           $data_to_store3 = array(
            'proy_id' => $proy_id,
            'pfec_fecha_inicio_ddmmaaa' => '01/01/'.$this->gestion.'',
            'pfec_fecha_fin_ddmmaaa' => '31/12/'.$this->gestion.'',
            'pfec_fecha_registro' => date('d/m/Y h:i:s'),
            'pfec_fecha_inicio' => $fechas[0]['inicio'],
            'pfec_fecha_fin' => $fechas[0]['final'],
            'pfec_ptto_fase' => 0,
            'pfec_ejecucion' => 1,
            'fun_id' => $this->fun_id,
            'aper_id' => $proyecto[0]['aper_id'],
            );
            $this->db->insert('_proyectofaseetapacomponente', $data_to_store3);
            $pfec_id=$this->db->insert_id();

        $nro=0;
        if (!empty($_POST["por_id"]) && is_array($_POST["por_id"]) ) {
          foreach ( array_keys($_POST["por_id"]) as $como){

            $data_to_store3 = array(
            'proy_id' => $proy_id,
            'por_id' => $_POST["por_id"][$como],
            );
            $this->db->insert('proy_oregional', $data_to_store3);
            $nro++;
          }
        }

        $nro_serv=0;
        if (!empty($_POST["serv"]) && is_array($_POST["serv"]) ) {
          foreach ( array_keys($_POST["serv"]) as $como){
            $servicio=$this->model_estructura_org->get_servicio_actividad_id($_POST["serv"][$como]);
            $veri_cs=$this->model_proyecto->verif_componente_servicio($pfec_id,$_POST["serv"][$como]);
            if(count($veri_cs)==0){

              $data_to_store = array( 
                'pfec_id' => $pfec_id,
                'com_componente' => $servicio[0]['serv_descripcion'],
                //'resp_id' => $_POST["resp_id"][$como],
                'resp_id' => $this->fun_id,
                'serv_id' => $_POST["serv"][$como],
                'fun_id' => $this->fun_id,
                );
              $this->db->insert('_componentes', $data_to_store);
            }
            else{

              $update_com = array(
                'com_componente' => $servicio[0]['serv_descripcion'],
                'resp_id' => $this->fun_id,
                'serv_id' => $_POST["serv"][$como],
                'fun_id' => $this->fun_id
              );
              $this->db->where('com_id', $veri_cs[0]['com_id']);
              $this->db->update('_componentes', $update_com);
            }
            $nro_serv++;
          }
         
        }

        redirect('proy/update_unidad/'.$proy_id.''); ///// Formulario de registro-operaciones
      }
    }

   
    /*-- GET ACTIVIDAD UNIDAD - ESTABLECIMIENTO (2020) --*/
    public function get_actividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']);
        $actividad= $this->model_estructura_org->get_actividad($act_id);

        $tabla=$this->list_objetivos_regionales($act_id);
        
        if(count($actividad)!=0){
          $servicios=$this->list_servicios_habilitados($actividad[0]['te_id']);
          $result = array(
            'respuesta' => 'correcto',
            'actividad' => $actividad,
            'oregional' => $tabla,
            'servicios' => $servicios,
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


    /*--- LISTA DE OBJETIVOS REGIONALES VICNULADOS A UNA UNIDAD (2020)*/
    public function list_objetivos_regionales($act_id){
      $oregionales=$this->model_objetivoregion->get_unidad_pregional_programado($act_id);
      $tabla='';
      $tabla.='
      <table class="table table-bordered">
        <thead>
          <tr title="act '.$act_id.'">
            <th scope="col">#</th>
            <th scope="col">OBJETIVO REGIONAL '.$this->gestion.'</th>
            <th scope="col">OBJETIVO DE GESTI&Oacute;N '.$this->gestion.'</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>';
        $cont = 0;
        foreach($oregionales as $row){
          $tit='RELACIÓN INDIRECTA';
          $color='#f9eeee';
          if($row['or_estado']!=0){
            $color='#e2f9f6';
            $tit='RELACIÓN DIRECTA';
          }
          $cont++;
          $tabla.='
          <tr bgcolor='.$color.'>
            <td>'.$cont.'</td>
            <td><b style="font-size: 10pt;">'.$row['or_codigo'].'.-</b> '.$row['or_objetivo'].'</td>
            <td>'.$row['og_codigo'].'.- '.$row['og_objetivo'].'</td>
            <td><center><input type="checkbox" name="por_id[]" value="'.$row['por_id'].'" title="SELECCIONE OBJETIVO REGIONAL" checked/></center></td>
          </tr>';
        }
        $tabla.='
        </tbody>
      </table>';
      return $tabla;
    }

    /*--- LISTA DE SERVICIOS (2020)*/
    public function list_servicios_habilitados($te_id){
      $servicios=$this->model_estructura_org->list_establecimiento_servicio($te_id);
      $tabla='';
      $tabla.='
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">C&Oacute;DIGO</th>
            <th scope="col">SERVICIO '.$this->gestion.'</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>';
        $cont = 0;
        foreach($servicios as $row){
          $cont++;
          $tabla.='
          <tr>
            <td>'.$cont.'</td>
            <td>'.$row['serv_cod'].'</td>
            <td>'.$row['serv_descripcion'].'</td>
            <td><center><input type="checkbox" name="serv[]" value="'.$row['serv_id'].'" title="SELECCIONE SERVICIO"/></center></td>
          </tr>';
        }
        $tabla.='
        </tbody>
      </table>';
      return $tabla;
    }

  /*----- FORMULARIO DE REGISTRO PROYECTOS DE INVERSIÓN -----*/
  function form_proy_inv(){
    $data['menu']=$this->menu(2);
    $cod=count($this->model_proyecto->cod_proy());
    $data['codigo']=$cod[0]['proy_codigo']+1;
    $data['tp_proy']=$this->model_proyecto->tip_proy();
    $data['tp_gasto']=$this->model_proyecto->tip_gasto();
    $data['list_dep']=$this->model_proyecto->list_departamentos();
    $data['programas'] = $this->model_proyecto->list_prog($this->gestion); ///// lista aperturas padres

    $data['unidad']=$this->model_proyecto->unidades_ejecu(); ////// Unidad Ejecutora
    $data['unidad2']=$this->model_proyecto->list_unidad_org(); ////// Unidad responsables

    $data['titulo']='';
    $data['tp_id']=1;

    $this->load->view('admin/programacion/prog_operaciones/formularios/form_add_pi', $data);
    
  }



  /*----- EDIT DATOS UNIDAD, ESTABLECIMIENTO  -----*/
  public  function edit_operacion($proy_id){
    $data['menu']=$this->menu(2);
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
    if(count($data['proyecto'])!=0){
      $data['nro_f'] = $this->model_faseetapa->nro_fase($proy_id);
      $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); ///// datos fase encendida

      $data['tp_proy']=$this->model_proyecto->tip_proy();
      //$data['tp_gasto']=$this->model_proyecto->tip_gasto();
      $data['oregional_prog']=$this->model_objetivoregion->get_pregional_programado($data['proyecto'][0]['por_id']); /// get objetivo regional programado 
      $data['list_dep']=$this->model_proyecto->list_departamentos();
      $data['list_dist']=$this->model_estructura_org->list_unidades_adm_ue(1,$data['proyecto'][0]['dep_id']); /// Unidades Ejecutoras
      $data['unidad_ejec'] = $this->model_estructura_org->list_unidades_adm_ue(2,$data['proyecto'][0]['dep_id']); /// Unidades Ejecutoras
      $data['list_actividades'] = $this->model_estructura_org->list_actividades_institucionales($data['proyecto'][0]['dist_id']); /// Actividades Institucionales
    //  $data['actividad'] = $this->model_estructura_org->get_actividad($data['proyecto'][0]['act_id']);
      
      $data['programas'] = $this->model_proyecto->list_prog($this->gestion); ///// lista aperturas padres
      $data['prog_padre']=$this->model_proyecto->get_programa_padre($data['proyecto'][0]['aper_programa']);
      /*--- Responsables Asignados a la operacion ---*/
      $data['resp1']=$this->model_proyecto->responsable_proy($proy_id,1);
      $data['resp2']=$this->model_proyecto->responsable_proy($proy_id,2);

      /*--- Responsables ---*/
      $data['fun1']=$this->model_proyecto->list_responsables_regionales(3,$data['proyecto'][0]['dep_id']); ////// tecnico OPERATIVO
      $data['fun2']=$this->model_proyecto->asig_responsables_vpoa($data['proyecto'][0]['tp_id']); ////// validador POA

      if($data['proyecto'][0]['tp_id']==1){
        $this->load->view('admin/programacion/prog_operaciones/formularios/form_update_pi', $data);
      }
      else{
        $data['actividad'] = $this->model_estructura_org->get_actividad($data['proyecto'][0]['act_id']);
        $data['servicios']=$this->list_sub_actividades($proy_id,$data['actividad']);
        $this->load->view('admin/programacion/prog_operaciones/formularios/form_update_of', $data);
      }
      
    }
    else{
      redirect('/','refresh');
    }
     
  }


  /*----- PRESENTACION POA (2020) -----*/
  public  function presentacion_poa($proy_id){
    $data['menu']=$this->menu(2);
    $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
    if(count($data['proyecto'])!=0){
      $data['mes'] = $this->mes_nombre();
      $this->load->view('admin/programacion/reportes/presentacion_poa', $data);
    }
    else{
      echo "ERROR !!!!";
    }
  }

  /*----- DATOS GENERALES POA (2020) -----*/
  public  function datos_generales_pi($proy_id){
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
    if(count($data['proyecto'])!=0){
      $data['mes'] = $this->mes_nombre();
      $data['fase']=$this->model_faseetapa->get_id_fase($proy_id);
      $this->load->view('admin/programacion/reportes/reporte_datos_generales', $data);
    }
    else{
      echo "ERROR !!!!";
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

  /*================= PROYECTO DE INVERSION (2019) ===================*/
  /*---------- FORMULARIO RESUMEN TECNICO DEL PROYECTO ----------*/
  public  function form_operacion_resumen($proy_id){
    $data['menu']=$this->menu(2);
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
    if(count($data['proyecto'])!=0){

      $this->load->view('admin/programacion/prog_operaciones/formularios/form_add_pi2', $data);
    }
    else{
      redirect('/','refresh');
    }
     
  }
    /*---------- VALIDA PROYECTO DE INVERSION ---------*/
    function valida_proyecto(){
    if ($this->input->post()) {
        $post = $this->input->post();
        $form = $this->security->xss_clean($post['form']); /// Formulario

        if($form==1){
          $tipo = $this->security->xss_clean($post['tp_id']); /// Tipo de Operacion
          $nombre = $this->security->xss_clean($post['nombre']); /// Nombre 
          $fi = $this->security->xss_clean(date("d/m/Y", strtotime($post['ini']))); /// Fecha Inicio
          $ff = $this->security->xss_clean(date("d/m/Y", strtotime($post['fin']))); /// Fecha Final
          $proy_act = 1; /// Programacion hasta actividades
          $dep_id = $this->security->xss_clean($post['dep_id']); /// Departamento id
          $dist_id = $this->security->xss_clean($post['ue_id']); /// Distrito id
          $cod_sisin = $this->security->xss_clean($post['cod_sisin']); /// Codigo SISIN
          $ue = 0; /// UE
          $ur = 0; /// RESP
          $prog = $this->security->xss_clean($post['prog']); /// PROGRAMA
          $proy= $this->security->xss_clean($post['proy']); /// PROYECTO
          $act = '000'; /// ACTIVIDAD
          $aper=$this->model_proyecto->get_aper_programa($prog);

          //echo "tipo : ".$tipo." - NOMNRE : ".$nombre." - FECHA : ".$fi."-".$ff." - DEP ID : ".$dep_id."";

          /*------------ Insert Proyectos --------------*/
          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'proy_codigo' => 0,
            'proy_nombre' => strtoupper($nombre),
            'tp_id' => $tipo,
            'proy_gestion_inicio_ddmmaaaa' => $fi,
            'proy_gestion_fin_ddmmaaaa' => $ff,
            'proy_act' => $proy_act,
            'dep_id' => $dep_id,
            'dist_id' => $dist_id,
            'proy_fecha_registro' => date("d/m/Y H:i:s"),
            'fun_id' => $this->fun_id,
            'act_id' => 0,
          );
          $this->db->insert('_proyectos', $data_to_store);
          $id_p=$this->db->insert_id();
          /*-------------------------------------------*/
          /*----------- RESPONSABLES DEL PROYECTO ----------*/
          $this->model_proyecto->add_resp_proy($id_p,583,583,583,$ue,$ur);
          /*-----------------------------------------------*/
          /*--------- FECHAS INICIAL-FINAL : PROYECTO ----------*/
          $fechas = $this->model_proyecto->fechas_proyecto($id_p);

          /*--------- UPDATE DATOS OPERACION ------------*/
          $update_proyect = array(
              'proy_gestion_inicio' => $fechas[0]['inicio'],
              'proy_gestion_fin' => $fechas[0]['final'],
              'proy_gestion_impacto' => ($fechas[0]['final']-$fechas[0]['inicio'])+1
          );
          $this->db->where('proy_id', $id_p);
          $this->db->update('_proyectos', $update_proyect);
          /*---------------------------------------------*/

          $gestiones=$fechas[0]['final']-$fechas[0]['inicio'];
          $gestion=$fechas[0]['inicio'];

          /*------------- APERTURA PROGRAMATICA -------------*/
          for($i=0;$i<=$gestiones;$i++){
            if($this->gestion==$gestion){
              $this->model_proyecto->add_apertura($id_p,$gestion,$aper[0]['aper_programa'],$proy,$act,$actividad[0]['act_descripcion'],$this->fun_id);
            }
            else{
              $this->model_proyecto->add_apertura($id_p,$gestion,$aper[0]['aper_programa'],'','',$actividad[0]['act_descripcion'],$this->fun_id);
            }
            $gestion++;
          }
          /*--------------------------------------------------*/
            $proyecto = $this->model_proyecto->get_id_proyecto($id_p);
            if(count($proyecto)!=0){
              $this->session->set_flashdata('success','LOS DATOS DEL PROYECTO SE REGISTRARON CORRECTAMENTE');
              redirect('admin/proy/proyecto_pi/'.$id_p.''); ///// Formulario de REsumen Tecnico
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL REGISTRAR DATOS GENERALES DEL PROYECTOS');
              redirect('admin/proy/proyecto/'.$tipo.'/false'); ///// Formulario de registro-operaciones
            }
        }
        /*---------- Objetivos - Problemas --------*/
        else{
          $proy_id = $this->security->xss_clean($post['proy_id']); /// proyecto Id
          $desc_prob = $this->security->xss_clean($post['desc_prob']); /// Descripcion del problema
          $desc_sol = $this->security->xss_clean($post['desc_sol']); /// Descripcion de la solucion
          $obj_gral = $this->security->xss_clean($post['obj_gral']); /// Descripcion Objetivo General
          $obj_esp = $this->security->xss_clean($post['obj_esp']); /// Descripcion Objetivo Especifico

          /*--------- UPDATE DATOS OPERACION ------------*/
          $update_proy = array(
            'proy_desc_problema' => $desc_prob,
            'proy_desc_solucion' => $desc_sol,
            'proy_obj_general' => $obj_gral,
            'proy_obj_especifico' => $obj_esp,
            'estado' => 2,
            'proy_fecha_registro' => date("d/m/Y H:i:s"),
            'fun_id' => $this->fun_id
          );
          $this->db->where('proy_id', $proy_id);
          $this->db->update('_proyectos', $update_proy);
          /*---------------------------------------------*/

            $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
            if($proyecto[0]['estado']==2){
              $this->session->set_flashdata('success','EL PROYECTO DE INVERSI&Oacute;N SE REGISTRO CORRECTAMENTE');
              redirect('admin/proy/fase_etapa/'.$proy_id.'/true'); ///// Formulario Problemas y Objetivos
            //  redirect('admin/proy/list_proy#tabs-a'); ///// Lista de Proyectos de Inversion
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL REGISTRAR PROBLEMAS - OBJETIVOS');
              redirect('admin/proy/proyecto_pi/'.$proy_id.'/false'); ///// Formulario Problemas y Objetivos
            }
        }

    }
    else{
      echo "<center><font color='red'>Error!!!!</font></center>";
    }
  }

  /*--- VALIDA UPDATE PROYECTO DE INVERSION ---*/
  function valida_update_proyecto(){
    if ($this->input->post() & $this->input->server('REQUEST_METHOD') === 'POST') {
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proyecto Id
        $form = $this->security->xss_clean($post['form']); /// Formulario

        if($form==1){
          $fi = $this->security->xss_clean($post['ini']); /// Fecha Inicio
          $ff = $this->security->xss_clean($post['final']); /// Fecha Final

          $dep_id = $this->security->xss_clean($post['dep_id']); /// Departamento id
          $dist_id = $this->security->xss_clean($post['ue_id']); /// Distrito id

          $nombre = $this->security->xss_clean($post['nombre']); /// Nombre del Proyecto
          $cod_sisin = $this->security->xss_clean($post['cod_sisin']); /// Codigo SISIN

          $tue = 583; /// Tue
          $poa = 583; /// POA
          $fin = 583; /// FIn

          $ue = 0; /// UE
          $ur = 0; /// RESP

          $gi = $this->security->xss_clean($post['gi']); /// Gestion Inicio
          $gf = $this->security->xss_clean($post['gf']); /// Gestion Final

          $aper_prog = $this->security->xss_clean($post['aper_id']); /// Aper Id anterior
          $aper=$this->model_proyecto->get_aper_programa($post['prog']); /// Aper Id Nuevo actual

          $ap_proy = $this->security->xss_clean($post['aper_proy']); /// cod proyecto anterior
          $proy=$this->security->xss_clean($post['proy']); /// cod proyecto nuevo

          $act='000'; /// cod proyecto nuevo

          $fechas = $this->model_proyecto->fechas_proyecto($proy_id);  // devuelve las fechas del proyecto inicio-conclusion
          
        //  $aper=$this->model_proyecto->get_aper_programa($prog); /// Aper Id Nuevo
        //  echo "PROGRAMA ANTERIOR : ".$aper_prog." - PROGRAMA NUEVO : ".$prog[0]['aper_id']."";
          /*------- update apertura programatica ------*/
          if($aper_prog!=$aper[0]['aper_programa'] || $ap_proy!=$proy){
            $aper_proy=$this->model_proyecto->mis_programas($proy_id);
              foreach ($aper_proy as $rowa){
                $this->model_proyecto->update_apertura($rowa['aper_id'],$aper[0]['aper_programa'],$proy,$act,$nombre,$this->fun_id);
              }
          }

          $query=$this->db->query('set datestyle to DMY');
          $update_proy = array(
            'proy_nombre' => $nombre,
            'proy_sisin' => $cod_sisin,
            'proy_gestion_inicio_ddmmaaaa' => $fi,
            'proy_gestion_fin_ddmmaaaa' => $ff,
            'dep_id' => $dep_id,
            'dist_id' => $dist_id,
            'proy_fecha_registro' => date('d/m/Y h:i:s'),
            'fun_id' => $this->fun_id,
            'estado' => 2
          );
          $this->db->where('proy_id', $proy_id);
          $this->db->update('_proyectos', $update_proy);

          $fechas = $this->model_proyecto->fechas_proyecto($proy_id);  // devuelve las fechas del proyecto inicio-conclusion
          $query=$this->db->query('set datestyle to DMY');
          $update_proyect = array(
            'proy_gestion_inicio' => $fechas[0]['inicio'],
            'proy_gestion_fin' => $fechas[0]['final'],
            'proy_gestion_impacto' => ($fechas[0]['final']-$fechas[0]['inicio'])+1);
          $this->db->where('proy_id', $proy_id);
          $this->db->update('_proyectos', $update_proyect);

          $this->model_proyecto->update_resp_proy($proy_id,$tue,$poa,0,0,0);

          /*---------------- en caso de que la fecha inicial se adelante ---------------*/
            if($fechas[0]['inicio']<$gi){
              $fecha=$fechas[0]['inicio'];
              $nro=$this->input->post('gi')-$fechas[0]['inicio'];
              for($i=1;$i<=$nro;$i++){
                $aper_gestion=$this->model_proyecto->verif_apertura_gestion($proy_id,$fecha);
                if(count($aper_gestion)==0){
                  $this->model_proyecto->add_apertura($proy_id,$fecha,$aper[0]['aper_programa'],'','',$nombre,$this->fun_id);
                }
                $fecha++;
              }
            }

          /*---------------- en caso en que la fecha inicial se reduzca ---------------*/
            if($fechas[0]['inicio']>$gi){
              $fecha=$gi;
              $nro=$fechas[0]['inicio']-$gi;
              for($i=1;$i<=$nro;$i++){
                $aper = $this->model_proyecto->aper_id($proy_id,$fecha); //// aper_id buscado
                $this->model_proyecto->delete_aper_id($aper[0]['aper_id']); //// elimando apertura programatica
                $fecha++;
              }
            }

            /*---------------- en caso de que la fecha final se amplie ---------------*/
            if($fechas[0]['final']>$gf){ 
              $fecha=$gf+1;
              $nro=$fechas[0]['final']-$gf;
              for($i=1;$i<=$nro;$i++){
                $aper_gestion=$this->model_proyecto->verif_apertura_gestion($proy_id,$fecha);
                if(count($aper_gestion)==0){
                  $this->model_proyecto->add_apertura($proy_id,$fecha,$aper[0]['aper_programa'],'','',$nombre,$this->fun_id);
                }
                $fecha++;
              }
            }

            elseif($fechas[0]['final']<$gf){
              $fecha=$gf;
              $nro=$gf-$fechas[0]['final'];

              for($i=1;$i<=$nro;$i++){
               $apertura = $this->model_proyecto->aper_id($proy_id,$fecha); //// aper_id buscado
               $this->model_proyecto->delete_aper_id($apertura[0]['aper_id']); //// elimando apertura programatica
                $fecha--;
              }
            }

            $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
            if($proyecto[0]['estado']==2){
              $this->session->set_flashdata('success','LOS DATOS DEL PROYECTO SE MODIFICARON CORRECTAMENTE');
              redirect('admin/proy/proyecto_pi/'.$proy_id.''); ///// Formulario de Objetivos y problemas
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL MODIFICAR DATOS GENERALES');
              redirect('admin/proy/edit/'.$proy_id.''); ///// Formulario de registro-Proyectos
            }

        }
        
    }
    else{
        echo "<font color='red'><b>ERROR - SISTEMA!!!!</b></font>";
    }
  }


  /*========= OBTIENE LOS DATOS DE LOS RESPONSABLES  =======*/
    public function get_responsables(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $id = $post['id_p']; /// id proyecto
            $tp = $post['tp']; /// tipo de responsable
            $id = $this->security->xss_clean($id);
            $tp = $this->security->xss_clean($tp);
            $dato_resp = $this->model_proyecto->responsable_proy($id,$tp);
            //caso para modificar el codigo de proyecto y actividades
            foreach($dato_resp as $row){
                $result = array(
                    'proy_id' => $row['proy_id'],
                    "fun_id" =>$row['fun_id'],
                    "responsable" =>$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']
                );
            }
            echo json_encode($result);
        }else{
            show_404();
        }
    }
  /*================================================================*/

    /*---- OBSERVAR UNIDAD/ESTABLECIMIENTO/PROYECTO ------*/
    public function observar_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 

        /*--------- UPDATE ESTADO APERTURA ----------*/
          $update_aper = array(
            'aper_proy_estado' => 1,
            'fun_id' => $this->fun_id
          );
          $this->db->where('aper_id', $proyecto[0]['aper_id']);
          $this->db->update('aperturaprogramatica', $update_aper);

          $update_proy = array(
            'proy_estado' => 1,
            'fun_id' => $this->fun_id
          );
          $this->db->where('proy_id', $proyecto[0]['proy_id']);
          $this->db->update('_proyectos', $update_proy);


          $result = array(
            'respuesta' => 'correcto',
          
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*---- VALIDAR POA PARA SU APROBACION ------*/
    public function validar_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 

        /*--- UPDATE ESTADO APERTURA ---*/
          $update_aper = array(
            'aper_proy_estado' => 4,
            'fun_id' => $this->fun_id
          );
          $this->db->where('aper_id', $proyecto[0]['aper_id']);
          $this->db->update('aperturaprogramatica', $update_aper);


          $result = array(
            'respuesta' => 'correcto',
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*---- APROBAR POA ------*/
    public function aprobar_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

        if($proyecto[0]['tp_id']==1){ /// Proyecto de Inversion
          /*--- UPDATE ESTADO APERTURA ---*/
          $update_aper = array(
            'aper_proy_estado' => 4,
            'fun_id' => $this->fun_id
          );
          $this->db->where('aper_id', $proyecto[0]['aper_id']);
          $this->db->update('aperturaprogramatica', $update_aper);

          /*--- UPDATE ESTADO POA ---*/
/*          $update_proy = array(
            'proy_estado' => 4,
            'fun_id' => $this->fun_id
          );
          $this->db->where('proy_id', $proyecto[0]['proy_id']);
          $this->db->update('_proyectos', $update_proy);*/
        }
        else{ /// Gasto Corriente
          /*--- UPDATE ESTADO POA ---*/
          $update_proy = array(
            'proy_estado' => 4,
            'fun_id' => $this->fun_id
          );
          $this->db->where('proy_id', $proyecto[0]['proy_id']);
          $this->db->update('_proyectos', $update_proy);
        }

          $result = array(
            'respuesta' => 'correcto',
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*=========== ELIMINAR PROYECTO ==============*/
    public function delete_proyecto($tp,$proy_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $aperturas = $this->model_proyecto->mis_programas($proy_id);

          foreach($aperturas as $row){
            $update_prog = array(
              'aper_estado' => '3',
              'fun_id' => $this->fun_id);
            $this->db->where('aper_id', $row['aper_id']);
            $this->db->update('aperturaprogramatica', $update_prog);
          }

          /*--------- ACTUALIZANDO ESTADO DEL PROYECTO ---------*/
            $update_proy = array(
              'estado' => '3',
              'fun_id' => $this->fun_id);
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectos', $update_proy);
          /*----------------------------------------------------*/

          /*------ ACTUALIZANDO ESTADO FASEETAPACOMPONENTE -----*/
            $update_fase = array(
              'estado' => '3',
              'fun_id' => $this->fun_id);
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase);
          /*----------------------------------------------------*/

          /*----------- Anulando requerimientos ------------*/
            $update_ins= array(
              'fun_id' => $this->fun_id,
              'aper_id' => 0,
              'ins_estado' => 3,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
            );
            $this->db->where('aper_id', $proyecto[0]['aper_id']);
            $this->db->update('insumos', $this->security->xss_clean($update_ins));
          /*-----------------------------------------------*/

            $this->session->set_flashdata('success','EL PROYECTO SE ELIMINO CORRECTAMENTE');
            if($tp==1){
              redirect('admin/proy/list_proy');
            }
            elseif ($tp==2) {
              redirect('admin/proy/list_proy_poa');
            }
            else{
              echo "<font color=red><center>ERROR AL ELIMINAR</center></font>";
            }
              
    }

    /*------------------------ Get Presupuesto Operacion ----------------------*/
    public function obtiene_presupuesto(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos Proyecto
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos de la Fase
          $fase_gestion=$this->model_faseetapa->list_fases_gestiones($fase[0]['id']); /// Lista de Fases Gestiones
          
          if(count($proyecto)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'proyecto' => $proyecto,
              'fase' => $fase,
              'fase_gestion' => $fase_gestion
            );
          }
          else{
            $result = array(
                'respuesta' => 'error'
            );
          }
          echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*================ OBTIENE LOS DATOS DEL PROYECTO ================*/
    public function get_proyecto(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $cod = $post['id_proy'];
            $id = $this->security->xss_clean($cod);
            $dato_proy = $this->model_proyecto->get_id_proyecto($id);
            //caso para modificar el codigo de proyecto y actividades
            foreach($dato_proy as $row){
                $result = array(
                  'proy_id' => $row['proy_id'],
                  "proy_nombre" =>$row['proy_nombre']
                );
            }
            echo json_encode($result);
        }else{
            show_404();
        }
    }


/*====================================================================================================================*/
    /*--- VERIFICANDO APERTURA PROGRAMATICA GASTO CORRIENTE---*/
    function verif(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $cod1 = $post['prog'];
          $cod2= $post['proy'];
          $cod3 = $post['act'];

          $variable= $this->model_proyecto->verif_programa_unidad($cod1,$cod3);
          if(count($variable)==0){
            echo "true"; ///// no existe un CI registrado
          }
          else{
            echo "false"; //// existe el CI ya registrado
          }
 
      }else{
        show_404();
      }
    }

    /*--- VERIFICANDO APERTURA PROGRAMATICA PROYECTO DE INVERSION ---*/
    function verif_apg_pi(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $aper_id = $post['prog']; /// aper id
          $proyecto= $post['proy']; /// cod. proyecto
          $actividad = $post['act']; /// cod Actividad

          $get_programa=$this->model_proyecto->get_aper_programa($aper_id);
          $variable= $this->model_proyecto->verif_programa_pi($get_programa[0]['aper_programa'],$proyecto);

          if(count($variable)==1){
            echo "true"; /////  existe Apertura registrado
          }
          else{
            echo "false"; //// no existe Apertura
          }
 
      }else{
        show_404();
      }
    }


    /*---------  UNIDAD DISTRITALES ---------*/
    public function combo_distrital($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'distrital':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT *
          from _distritales 
          where  dep_id='.$id_pais.'');
          $salida.= "<option value='0'>SELECCIONE DISTRITAL</option>";
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[5]." - ".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }

    /*---------  UNIDAD EJECUTORA -----------*/
    public function combo_ue($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'distrital':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT *
          from _distritales 
          where  dep_id='.$id_pais.' and dist_ue=1');
          $salida.= "<option value=''>Seleccione Unidad Ejecutora</option>";
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[5]." - ".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }
    /*------------------------------------- MENU -----------------------------------*/
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

}