<?php
class C_consultas extends CI_Controller {  
    public $rol = array('1' => '1','2' => '10'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('consultas/model_consultas');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_certificacion');
/*            $this->load->model('reporte_eval/model_evalnacional');
            $this->load->model('reporte_eval/model_evalregional');
            $this->load->model('mantenimiento/mapertura_programatica');*/
          //  $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
          //  $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->ppto= $this->session->userData('verif_ppto'); 
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('genera_informacion');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }


    /*---------- TIPO DE RESPONSABLE ----------*/
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
    public function mis_operaciones(){
      $data['menu']=$this->menu(7);
      $data['list']=$this->menu_nacional();

      $this->load->view('admin/consultas_internas/menu_consultas_poa', $data);
    }


    //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
      $tabla.='
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <header><b>SEGUIMIENTO POA '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-3">
                    <label class="label"><b>DIRECCIÓN ADMINISTRATIVA</b></label>
                    <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                    <option value="">SELECCIONE REGIONAL</option>';
                    foreach($regionales as $row){
                      if($row['dep_id']!=0){
                        $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                      }
                    }
                    $tabla.='
                    </select>
                  </section>

                  <section class="col col-3" id="tprep">
                    <label class="label"><b>TIPO DE REPORTE</b></label>
                    <select class="form-control" id="tp_rep" name="tp_rep" title="SELECCIONE TIPO DE REPORTE">
                    </select>
                  </section>

                  <section class="col col-3" id="tp">
                    <label class="label"><b>TIPO DE GASTO</b></label>
                    <select class="form-control" id="tipo" name="tipo" title="SELECCIONE TIPO DE GASTO">
                    </select>
                  </section>
                </div>
              </fieldset>
          </form>
          </div>
        </article>';
      return $tabla;
    }


    /*--- GET TIPO DE REPORTE (2020 - 2021)---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tp_rep = $this->security->xss_clean($post['tp_rep']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        
        $salida='';
        if($tp_rep==1){
          $salida=$this->lista_gastocorriente_pinversion($dep_id,$tp_id);
        }
        elseif ($tp_rep==2) {
          $salida=$this->consolidado_operaciones_regional($dep_id,$tp_id);
        }
        elseif ($tp_rep==3) {
          $salida=$this->consolidado_requerimientos_regional($dep_id,$tp_id);
        }
        elseif ($tp_rep==4) {
          $salida=$this->lista_certificaciones_poa($dep_id,$tp_id);
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

    /*-- REPORTE 1 (LISTA DE UNIDADES/PROYECTOS DE INVERSIÓN)--*/
    public function lista_gastocorriente_pinversion($dep_id,$tp_id){
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $unidades=$this->mrep_operaciones->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

        $titulo_ppto='PPTO. ASIGNADO '.$this->gestion.'';
        if($this->ppto==1){
          $titulo_ppto='PPTO. APROBADO '.$this->gestion.'';
        }

      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
      <br>
        <div align=right>
          <a href="'.site_url("").'/rep/comparativo_unidad_ppto_regional/'.$dep_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO OPERACIONES"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR UNIDADES / PROYECTOS DE INVERSI&Oacute;N</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
      <div class="alert alert-warning">
        <a href="#" class="alert-link" align=center><center><b>LISTA DE '.$titulo.' '.$this->gestion.' - '.strtoupper($dep[0]['dep_departamento']).'</b></center></a>
      </div>
      <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
        <thead>
          <tr style="background-color: #66b2e8">
            <th style="width:1%;"></th>
            <th style="width:5%;"></th>
            <th style="width:5%;"></th>
            <th style="width:5%;">COD. DA.</th>
            <th style="width:5%;">COD. UE.</th>
            <th style="width:5%;">COD. PROG.</th>
            <th style="width:5%;">COD. PROY.</th>
            <th style="width:5%;">COD. ACT.</th>
            <th style="width:20%;">'.$titulo.'</th>
            <th style="width:10%;"></th>
            <th style="width:10%;" title="">UNIDAD ADMINISTRATIVA</th>
            <th style="width:10%;" title="">UNIDAD EJECUTORA</th>
            <th style="width:8%;" title="">'.$titulo_ppto.'</th>
            <th style="width:8%;" title="">PPTO. POA '.$this->gestion.'</th>
            <th style="width:8%;" title="">SALDO</th>
          </tr>
        </thead>
        <tbody id="bdi">';
        $nro=0;
        foreach ($unidades as $row){
          $ppto=$this->genera_informacion->ppto_actividad($row,$tp_id);
          $color='';
          if($ppto[3]<0){
            $color='#f3d8d7';
          }
          elseif($ppto[3]>0){
            $color='#e4f7f4'; 
          }
          
          $nro++;
          $tabla.='
          <tr style="height:35px;" bgcolor="'.$color.'" title="'.$row['aper_id'].'">
            <td>'.$nro.'</td>
            <td align=center>';
            if($row['pfec_estado']==1){
              $tabla.='<a href="#" data-toggle="modal" data-target="#modal_poa" class="btn btn-default enlace" name="'.$row['proy_id'].'"  onclick="ver_poa('.$row['proy_id'].');" title="FORMULARIO POA">FORMULARIOS POA</a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='
            </td>
            <td align=center>';
            if($row['pfec_estado']==1){

              $tabla.=' <a href="'.site_url("").'/seg/notificacion_operaciones_mensual/'.$row['proy_id'].'" class="btn btn-default" target="_blank" title="NOTIFICACIÓN POA">
                          NOTIFICACI&Oacute;N POA '.$this->verif_mes[2].'
                        </a>';
            }
            else{
              $tabla.='<b>FASE NO ACTIVA</b>';
            }
            $tabla.='
            </td>
            <td align=center>'.$row['da'].'</td>
            <td align=center>'.$row['ue'].'</td>
            <td align=center>'.$row['prog'].'</td>
            <td align=center>'.$row['proy'].'</td>
            <td align=center>'.$row['act'].'</td>
            <td>';
              if($tp_id==1){
              $tabla.='<b>'.$row['proyecto'].'</b>';
            }
            else{
              $tabla.='<b>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</b>';
            }
            $tabla.='</td>
            <td>'.$titulo.'</td>
            <td>'.strtoupper($row['dep_departamento']).'</td>
            <td>'.strtoupper($row['dist_distrital']).'</td>
            <td align=right>'.number_format($ppto[1], 2, ',', '.').'</td>
            <td align=right>'.number_format($ppto[2], 2, ',', '.').'</td>
            <td align=right>'.number_format($ppto[3], 2, ',', '.').'</td>
          </tr>';
        }
        $tabla.='</tbody>
        </table>';
      return $tabla;
    }


    /*-----REPORTE COMPARATIVO PRESUPUESTO ASIG-POA (REGIONAL) 2020-2021-----*/
    public function comparativo_presupuesto_regional($dep_id,$tp_id){
      $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      if($dep_id==0){ // Nacional
        $data['titulo']='GASTO CORRIENTE '.$this->gestion.'';
        if($tp_id==1){
          $data['titulo']='PROYECTO DE INVERSI&Oacute;N '.$this->gestion.'';
        }

        $unidades=$this->mrep_operaciones->list_poa_gastocorriente_pinversion($tp_id);
      }
      else{ /// Distrital
        $data['titulo']='REGIONAL - '.strtoupper($data['departamento'][0]['dep_departamento']).'';
        $unidades=$this->mrep_operaciones->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);
      }

      
      if(count($data['departamento'])!=0){
          $titulo='GASTO CORRIENTE';
          if($tp_id==1){
            $titulo='PROYECTO DE INVERSI&Oacute;N';
          }

        $titulo_ppto='PPTO. ASIGNADO '.$this->gestion.'';
        if($this->ppto==1){
          $titulo_ppto='PPTO. APROBADO '.$this->gestion.'';
        }

        $tabla='';
        $tabla.='
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" align=center bgcolor="#f5f7f8">
                    <th style="width:1%;">#</th>
                    <th style="width:5%;">COD. DA.</th>
                    <th style="width:5%;">COD. UE.</th>
                    <th style="width:5%;">COD. PROG.</th>
                    <th style="width:10%;">COD. PROY.</th>
                    <th style="width:5%;">COD. ACT.</th>
                    <th style="width:30%;">'.$titulo.'</th>
                    <th style="width:8%;" title="">'.$titulo_ppto.'</th>
                    <th style="width:8%;" title="">PPTO. POA '.$this->gestion.'</th>
                    <th style="width:8%;" title="">SALDO</th>
                  </tr>
                </thead>
                <tbody>';
                 $nro=0;
                  foreach ($unidades as $row){
                    $ppto=$this->genera_informacion->ppto_actividad($row,$tp_id);
                    $color='';
                    if($ppto[3]<0){
                      $color='#f3d8d7';
                    }
                    elseif($ppto[3]>0){
                      $color='#e4f7f4'; 
                    }
                    
                    $nro++;
                    $tabla.='
                    <tr bgcolor="'.$color.'" title="'.$row['aper_id'].'">
                      <td style="height:1px;" align=center>'.$nro.'</td>
                      <td style="height:15px;" align=center>'.$row['da'].'</td>
                      <td style="width:5%;" align=center>'.$row['ue'].'</td>
                      <td style="width:5%;" align=center>'.$row['prog'].'</td>
                      <td style="width:10%;" align=center>';
                      if($tp_id==1){
                        $tabla.=''.$row['proy'].'';
                      }
                      else{
                        $tabla.='0000';
                      }
                      $tabla.='
                      </td>
                      <td style="width:5%;" align=center>'.$row['act'].'</td>
                      <td style="width:30%;">';
                        if($tp_id==1){
                        $tabla.=''.$row['proyecto'].'';
                      }
                      else{
                        $tabla.=''.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'';
                      }
                      $tabla.='</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[1], 2, ',', '.').'</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[2], 2, ',', '.').'</td>
                      <td style="width:8%;" align=right>'.number_format($ppto[3], 2, ',', '.').'</td>
                    </tr>';
                  }
                  $tabla.='
                </tbody>
              </table>';

          $data['titulo_ppto']=$titulo_ppto;
          
          $data['lista']=$tabla;
          $this->load->view('admin/consultas_internas/reporte_comparativo_regional', $data);
      }
      else{
        echo "Error !!!";
      }
    }


    /*-- REPORTE 2 (CONSOLIDADO OPERACIONES REGIONALES) 2020-2021--*/
    public function consolidado_operaciones_regional($dep_id,$tp_id){
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

      if($this->gestion==2019){
        $tabla='No disponible';
      }
      else{
        $operaciones=$this->mrep_operaciones->consolidado_operaciones_regionales($dep_id,$tp_id); /// Operaciones a Nivel Regionales
        $titulo='GASTO CORRIENTE';
        $titulo_sub='SUBACTIVIDAD';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
          $titulo_sub='UNIDAD RESPONSABLE';
        }

        $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/rep/exportar_operaciones_regional/'.$dep_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO OPERACIONES"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR CONSOLIDADO OPERACIONES</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div class="alert alert-warning">
          <a href="#" class="alert-link" align=center><center><b>CONSOLIDADO DE OPERACIONES '.$this->gestion.' - '.strtoupper($dep[0]['dep_departamento']).' ('.$titulo.')</b></center></a>
        </div>
        <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
          <thead>
            <tr style="background-color: #66b2e8">
              <th style="width:3%;">COD. DA.</th>
              <th style="width:3%;">COD. UE.</th>
              <th style="width:3%;">COD. PROG.</th>
              <th style="width:10%;">COD. PROY.</th>
              <th style="width:3%;">COD. ACT.</th>
              <th style="width:35%;">'.$titulo.'</th>
              <th style="width:3%;">COD. SUBACT.</th>
              <th style="width:15%;">'.$titulo_sub.'</th>';
              if($tp_id==1){
                $tabla.='<th style="width:10%;">COMPONENTE</th>';
              }
              $tabla.='
              <th style="width:3%;">COD. ACE.</th>
              <th style="width:3%;">COD. ACP.</th>
              <th style="width:3%;">COD. OR.</th>
              <th style="width:3%;">COD. OPE.</th>
              <th style="width:25%;">OPERACI&Oacute;N</th>
              <th style="width:15%;">RESULTADO</th>
              <th style="width:15%;">INDICADOR</th>
              <th style="width:5%;">LINEA BASE</th>
              <th style="width:5%;">META</th>
              <th style="width:15%;">MEDIO DE VERIFICACIÓN</th>
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
              <th style="width:6%;">PRESUPUESTO POA</th>
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;
          foreach ($operaciones as $row){
            $monto=$this->model_producto->monto_insumoproducto($row['prod_id']);
              
            $ptto=0;
            if(count($monto)!=0){
              $ptto=$monto[0]['total'];
            }

            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.strtoupper($row['dep_cod']).'</td>';
                $tabla.='<td>'.strtoupper($row['dist_cod']).'</td>';
                $tabla.='<td>'.$row['aper_prog'].'</td>';
                $tabla.='<td>';
                if($tp_id==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.=''.$row['aper_proy'].'';
                }
                $tabla.='</td>';
                $tabla.='<td>'.$row['aper_act'].'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.$row['proy_nombre'].'';
                  }
                  else{
                    $tabla.=''.$row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'].'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['serv_cod'].'</td>';
                $tabla.='<td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                if($tp_id==1){
                  $tabla.='<td>'.$row['com_componente'].'</td>';
                }
                $tabla.='<td>'.$row['acc_codigo'].'</td>';
                $tabla.='<td>'.$row['og_codigo'].'</td>';
                $tabla.='<td>'.$row['or_codigo'].'</td>';
                $tabla.='<td>'.$row['prod_cod'].'</td>';
                $tabla.='<td>'.$row['prod_producto'].'</td>';
                $tabla.='<td>'.$row['prod_resultado'].'</td>';
                $tabla.='<td>'.$row['prod_indicador'].'</td>';
                $tabla.='<td>'.$row['prod_linea_base'].'</td>';
                $tabla.='<td>'.$row['prod_meta'].'</td>';
                $tabla.='<td>'.$row['prod_fuente_verificacion'].'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['enero'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['febrero'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['marzo'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['abril'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['mayo'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['junio'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['julio'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['agosto'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['septiembre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['octubre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['noviembre'],2).'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($row['diciembre'],2).'</td>';
                $tabla.='<td style="width: 5%; text-align: right;">'.round($ptto,2).'</td>';
            $tabla.='</tr>';
          }
          $tabla.='
          </tbody>
        </table>';
      }

      return $tabla;
    }



    /*-- REPORTE 3 (CONSOLIDADO REQUERIMIENTOS REGIONAL) 2020-2021--*/
    public function consolidado_requerimientos_regional($dep_id,$tp_id){
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

      if($this->gestion==2019){
        $tabla='No disponible';
      }
      else{
        $requerimientos=$this->mrep_operaciones->consolidado_directo_requerimientos_regional($dep_id, $tp_id); /// Consolidado Requerimientos 2020-2021

        $titulo='GASTO CORRIENTE';
        if($tp_id==1){
          $titulo='PROYECTO DE INVERSI&Oacute;N';
        }

        $tabla.='
        <br>
        <div align=right>
          <a href="'.site_url("").'/rep/exportar_requerimientos_regional/'.$dep_id.'/'.$tp_id.'" target=_blank class="btn btn-default" title="CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR CONSOLIDADO REQUERIMIENTOS</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div class="alert alert-warning">
          <a href="#" class="alert-link" align=center><center><b>CONSOLIDADO DE REQUERIMIENTOS '.$this->gestion.' - '.strtoupper($dep[0]['dep_departamento']).' ('.$titulo.')</b></center></a>
        </div>
        <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
          <thead>
            <tr style="background-color: #66b2e8">
              <th style="width:3%;">COD. DA.</th>
              <th style="width:3%;">COD. UE.</th>
              <th style="width:3%;">COD. PROG.</th>
              <th style="width:10%;">COD. PROY.</th>
              <th style="width:3%;">COD. ACT.</th>
              <th style="width:35%;">'.$titulo.'</th>
             
              <th style="width:15%;">PARTIDA</th>
              <th style="width:25%;">REQUERIMIENTO</th>
              <th style="width:10%;">UNIDAD DE MEDIDA</th>
              <th style="width:5%;">CANTIDAD</th>
              <th style="width:5%;">PRECIO</th>
              <th style="width:15%;">COSTO TOTAL</th>
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
              <th style="width:10%;">OBSERVACI&Oacute;N</th>
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;
          foreach ($requerimientos as $row){
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                $tabla.='<td>'.$row['dist_cod'].'</td>';
                $tabla.='<td>'.$row['aper_programa'].'</td>';
                $tabla.='<td>';
                if($tp_id==1){
                  $tabla.=''.$row['proy_sisin'].'';
                }
                else{
                  $tabla.=''.$row['aper_proyecto'].'';
                }
                $tabla.='</td>';
                $tabla.='<td>'.$row['aper_actividad'].'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.$row['proy_nombre'].'';
                  }
                  else{
                    $tabla.=''.$row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'].'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['par_codigo'].'</td>';
                $tabla.='<td>'.strtoupper($row['ins_detalle']).'</td>';
                $tabla.='<td>'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td>'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                $tabla.='<td>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';

                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes1'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes2'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes3'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes4'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes5'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes6'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes7'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes8'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes9'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes10'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes11'], 2, ',', '.').'</td>';
                $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.number_format($row['mes12'], 2, ',', '.').'</td>';
                $tabla.='<td>'.strtoupper($row['ins_observacion']).'</td>';
            $tabla.='</tr>';
          }
          $tabla.='
          </tbody>
        </table>';
      }

      return $tabla;
    }



    /*-- FORMULARIO 4 LISTA DE CERTIFICACIONES POAS 2020 --*/
    public function lista_certificaciones_poa($dep_id,$tp_id){
      $tabla='';
          $certificados = $this->model_certificacion->lista_certificaciones_regional($dep_id,$tp_id,$this->gestion);
          $tabla.='
            <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
            <table id="dt_basic" class="table table-bordered" style="width:100%;" border=1>
            <thead>
              <tr style="background-color: #66b2e8">
                  <th style="width:1%;"></th>
                  <th style="width:15%;">C&Oacute;DIGO</th>
                  <th style="width:5%;">FECHA</th>
                  <th style="width:10%;">UNIDAD EJECUTORA</th>
                  <th style="width:10%;">APERTURA PROGRAM&Aacute;TICA</th>
                  <th style="width:30%;">UNIDAD, ESTABLECIMIENTO, PROYECTO DE INVERSI&Oacute;N</th>
                  <th style="width:15%;">SUBACTIVIDAD / UNIDAD RESPONSABLE</th>';
                  if($tp_id==1){
                    $tabla.='<th style="width:10%;">COMPONENTE</th>';
                  }
                  $tabla.='
                  <th style="width:5%;">GESTI&Oacute;N</th>
                  <th style="width:5%;">VER CERTIFICADO POA</th>
              </tr>
            </thead>
            <tbody id="bdi">';
            $nro=0;
            foreach ($certificados as $row){
              $nro++; $color='';$codigo=$row['cpoa_codigo'];
              if($row['cpoa_estado']==0){
                $color='#fddddd';
                $codigo='<font color=red>SIN CÓDIGO</font>';
              }
              elseif($row['cpoa_ref']){
                $color='#dcf7f3';
              }

              $tabla .='<tr bgcolor='.$color.'>';
                $tabla .='<td title='.$row['cpoa_id'].'>'.$nro.'</td>';
                $tabla .='<td>'.$codigo.'</td>';
                $tabla .='<td>'.date('d-m-Y',strtotime($row['cpoa_fecha'])).'</td>';
                $tabla .='<td>'.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='<td>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>';
                $tabla .='<td>';
                  if($row['tp_id']==1){
                    $tabla.=$row['proy_nombre'];
                  }
                  else{
                    $tabla.=$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'];
                  }
                $tabla .='</td>';
                $tabla .='<td>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
                if($tp_id==1){
                  $tabla .='<td>'.$row['com_componente'].'</td>';
                }
                $tabla .='<td align=center><b>'.$row['cpoa_gestion'].'</b></td>';
                $tabla .='<td align=center><a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$row['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="40" HEIGHT="40"/></a></td>';
              $tabla .='</tr>';
            }
            
            $tabla.='
            </tbody>
        </table>';

      return $tabla;
    }





  /*-----  OPCIONES 2020-2021 -----*/
    public function get_opciones($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {

        case 'reporte':
        $salida="";
          $salida.= "<option value='0'>Seleccione tipo Reporte....</option>";
          $salida.= "<option value='1'>LISTA DE UNIDADES / PROYECTOS DE INVERSIÓN</option>";
          $salida.= "<option value='2'>CONSOLIDADO FORMULARIO 4 (OPERACIONES)</option>";
          $salida.= "<option value='3'>CONSOLIDADO FORMULARIO 5 (REQUERIMEINTOS)</option>";
          $salida.= "<option value='4'>CERTIFICACIONES POA</option>";

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





    public function cambiar_gestion(){
        $nueva_gestion = strtoupper($this->input->post('gestion_usu'));
        $this->session->set_userdata('gestion', $nueva_gestion);

        redirect('consulta/mis_operaciones','refresh');
    }

    /*============= GENERAR MENU ===============*/
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

  /*=========================*/
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