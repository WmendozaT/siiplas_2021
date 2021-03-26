<?php
class Cseguimiento_establecimiento extends CI_Controller {
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('tp_usuario')==1){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('reporte_eval/model_evalregional');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        //$this->act = $this->session->userData('act_id');
        $this->tmes = $this->session->userData('trimestre');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->establecimiento=$this->model_seguimientopoa->get_unidad_programado_gestion($this->session->userData('act_id'));  
        $this->load->library('seguimientopoa');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*----- FORMULARIO SEGUIMIENTO POA ESTABLECIMIENTOS ------*/
    public function formulario_establecimiento(){
        $data['establecimiento']=$this->establecimiento;
        $data['gestiones']=$this->list_gestiones();
        $data['tmes']=$this->model_evaluacion->trimestre();
        $data['operaciones_programados']=$this->lista_operaciones_programados($this->establecimiento[0]['com_id'],$this->verif_mes[1]); /// Lista de Operaciones programados en el mes
        $data['nota']=$this->notificacion();

        $data['cabecera1']=$this->seguimientopoa->cabecera_seguimiento($data['establecimiento'],$this->model_componente->get_componente($this->establecimiento[0]['com_id']),1);
        $matriz_temporalidad_subactividad=$this->seguimientopoa->temporalizacion_x_componente($this->establecimiento[0]['com_id']); /// grafico
        $data['matriz_temporalidad_subactividad']=$matriz_temporalidad_subactividad;
        $data['tabla_temporalidad_componente']=$this->seguimientopoa->tabla_temporalidad_componente($data['matriz_temporalidad_subactividad'],0);

        /*--- Regresion lineal trimestral ---*/
        $data['cabecera2']=$this->seguimientopoa->cabecera_seguimiento($data['establecimiento'],$this->model_componente->get_componente($this->establecimiento[0]['com_id']),2);
        $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($this->establecimiento[0]['com_id']); /// Tabla para el grafico al trimestre
        $data['tabla_regresion']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion

        /*--- Regresion lineal Gestion */
        $data['cabecera3']=$this->seguimientopoa->cabecera_seguimiento($data['establecimiento'],$this->model_componente->get_componente($this->establecimiento[0]['com_id']),3);
        $data['tabla_gestion']=$this->seguimientopoa->tabla_regresion_lineal_servicio_total($this->establecimiento[0]['com_id']); /// Tabla para el grafico Total Gestion
        $data['tabla_regresion_total']=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion 


        $this->load->view('admin/evaluacion/seguimiento_establecimiento/menu_seguimiento_establecimiento', $data);
    }

    


    /*------- NOTIFICACION POA ------*/
    function notificacion(){
      $tabla='';
      $tabla.='<table border=0 style="width:95%;" align=center>
            <tr>
                <td style="width:95%;"><br><br></td>
            </tr>
            <tr>
                <td style="width:95%; font-size: 22px;font-family: Arial;" align=right><b>REF. NOTIFICACI&Oacute;N PARA SEGUIMIENTO POA '.$this->verif_mes[2].' '.$this->gestion.'</b></td>
            </tr>
            <tr>
                <td style="width:95%;"><br></td>
            </tr>
            <tr>
                <td style="width:95%;font-size: 15px;font-family: Arial;">
                El Departamento Nacional de Planificaci&oacute;n en el marco de sus competencias viene fortaleciendo las tareas de monitoreo y supervisi&oacute;n 
                a traves del Sistema de Planificaci&oacute;n <b>SIIPLAS</b>, en este sentido recordamos a usted efectuar el seguimiento al cumplimiento del POA <b>'.$this->verif_mes[2].' '.$this->gestion.'</b>, de la 
                <b>'.$this->establecimiento[0]['tipo'].' '.strtoupper($this->establecimiento[0]['act_descripcion']).' '.$this->establecimiento[0]['abrev'].'</b> a su cargo, haciendo enfasis en la programaci&oacute;n mensual y periodo de ejecuci&oacute;n de cada operaci&oacute;n.
                </td>
            </tr>
        </table>';

      return $tabla;
    }


    /*------- LISTA DE  OPERACIONES 2021 ------*/
    function lista_operaciones_programados($com_id,$mes_id){
      $operaciones=$this->model_producto->list_operaciones_subactividad($com_id); /// lISTA DE OPERACIONES
      $tabla='';
      $tabla.='
        <div class="table-responsive">
          <table class="table table-bordered" style="width:100%;">
             <thead>
                <tr>
                  <th colspan=4 style="width:40%;"><input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/></th>
                  <th colspan=8 style="width:60%;"></th>
                </tr> 
                </thead>
          </table>
          <table class="table table-bordered" style="width:100%;" id="datos">
             <thead>               
                <tr>
                  <th style="width:1%;"></th>
                  <th style="width:1%;"><b>COD. OR.</b></th>
                  <th style="width:1%;"><b>COD. OPE.</b></th>
                  <th style="width:12%;">OPERACI&Oacute;N</th>
                  <th style="width:10%;">INDICADOR</th>
                  <th style="width:3%;">META</th>
                  <th style="width:5%;">MES ANTERIOR NO EJECUTADO</th>
                  <th style="width:5%;">PROGRAMADO MES '.$this->verif_mes[2].'</th>
                  <th style="width:5%;">EJECUTADO MES '.$this->verif_mes[2].'</th>
                  <th style="width:13%;">MEDIO DE VERIFICACI&Oacute;N</th>
                  <th style="width:13%;">PROBLEMAS PRESENTADOS</th>
                  <th style="width:13%;">ACCIONES REALIZADOS</th>
                  <th style="width:2%;"></th>
                </tr>
              </thead>
              <tbody>';
              $nro=0;
              foreach($operaciones as $row){
                $indi_id='';
                if($row['indi_id']==2 & $row['mt_id']==1){
                  $indi_id='%';
                }
                $diferencia=$this->seguimientopoa->verif_valor_no_ejecutado($row['prod_id'],$mes_id);
                if($diferencia[1]!=0 || $diferencia[2]!=0){
                  $ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes($row['prod_id'],$mes_id);
                  $nro++;
                  $tabla.='
                  <tr>
                    <td style="width:1%;" align=center bgcolor="#f6fbf4">
                      '.$nro.'
                    </td>
                    <td style="width:1%;" align=center bgcolor="#f6fbf4"><b>'.$row['or_codigo'].'</b></td>
                    <td style="width:1%;" align=center bgcolor="#f6fbf4" title="'.$row['prod_id'].'"><b>'.$row['prod_cod'].'</b></td>
                    <td style="width:15%;" bgcolor="#f6fbf4">'.$row['prod_producto'].'</td>
                    <td style="width:10%;" bgcolor="#f6fbf4">'.$row['prod_indicador'].'</td>
                    <td style="width:3%;" align=right bgcolor="#f6fbf4"><b>'.round($row['prod_meta'],2).''.$indi_id.'</b></td>
                    <td style="width:5%;" align=center bgcolor="#f7e1e2">'.$diferencia[1].'</td>
                    <td style="width:5%;" align=center bgcolor="#f6fbf4">'.$diferencia[2].''.$indi_id.' <input type="hidden" name="pg_fis[]" value="'.($diferencia[1]+$diferencia[2]).'"></td>';
                    if(count($ejec)!=0){
                      $tabla.='
                      <td style="width:5%;">
                        <input type="text" id=ejec'.$nro.' class="form-control" value="'.round($ejec[0]['pejec_fis'],2).'" onkeyup="verif_valor('.($diferencia[1]+$diferencia[2]).',this.value,'.$nro.');" title="'.($diferencia[1]+$diferencia[2]).'" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </td>
                      <td style="width:10%;">
                        <textarea rows="3" id=mv'.$nro.' class="form-control" title="MEDIO DE VERIFICACIÓN">'.$ejec[0]['medio_verificacion'].'</textarea>
                      </td>
                      <td style="width:10%;">
                        <textarea rows="3" id=obs'.$nro.' class="form-control"  title="PROBLEMAS PRESENTADOS">'.$ejec[0]['observacion'].'</textarea>
                      </td>
                      <td style="width:10%;">
                        <textarea rows="3" id=acc'.$nro.' class="form-control"  title="ACCIONES REALIZADOS">'.$ejec[0]['acciones'].'</textarea>
                      </td>
                      <td style="width:2%;" align=center title="MODIFICAR SEGUIMIENTO POA">
                        <div id="but'.$nro.'"><button type="button" name="'.$row['prod_id'].'" id="'.$nro.'" onclick="guardar('.$row['prod_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/drive_disk.png" WIDTH="40" HEIGHT="40"/><br>MODIFICAR</button></div>
                      </td>';
                    }
                    else{
                      $tabla.='
                      <td>
                        <input type="text" id=ejec'.$nro.' value="0" class="form-control"  onkeyup="verif_valor('.($diferencia[1]+$diferencia[2]).',this.value,'.$nro.');" title="'.($diferencia[1]+$diferencia[2]).'"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </td>';
                      $no_ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($row['prod_id'],$mes_id);
                      if(count($no_ejec)!=0){
                        $tabla.='
                          <td style="width:10%;">
                            <textarea rows="3" id=mv'.$nro.' class="form-control" title="MEDIO DE VERIFICACIÓN">'.$no_ejec[0]['medio_verificacion'].'</textarea>
                          </td>
                          <td style="width:10%;">
                            <textarea rows="3" id=obs'.$nro.' class="form-control" title="PROBLEMAS PRESENTADOS">'.$no_ejec[0]['observacion'].'</textarea>
                          </td>
                          <td style="width:10%;">
                            <textarea rows="3" id=acc'.$nro.' class="form-control" title="ACCIONES REALIZADOS">'.$no_ejec[0]['acciones'].'</textarea>
                          </td>';
                      }
                      else{
                        $tabla.='
                        <td style="width:10%;">
                          <textarea rows="3" class="form-control" id=mv'.$nro.' title="MEDIO DE VERIFICACIÓN"></textarea>
                        </td>
                        <td style="width:10%;">
                          <textarea rows="3" class="form-control" id=obs'.$nro.' title="PROBLEMAS PRESENTADOS"></textarea>
                        </td>
                        <td style="width:10%;">
                          <textarea rows="3" class="form-control" id=acc'.$nro.' title="ACCIONES REALIZADOS"></textarea>
                        </td>';
                      }
                      $tabla.='
                      <td style="width:2%;" align=center title="GUARDAR SEGUIMIENTO POA">
                        <div id="but'.$nro.'" style="display:none;"><button type="button" name="'.$row['prod_id'].'" id="'.$nro.'" onclick="guardar('.$row['prod_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="37" HEIGHT="37"/><br>GUARDAR</button></div>
                      </td>';
                    }
                    $tabla.='
                    
                  </tr>';
                }
              }
              $tabla.='
              </tbody>
          </table>
          <div align=right>
            <a href="javascript:abreVentana(\''.site_url("").'/seg/ver_reporte_evaluacionpoa_es/'.$com_id.'\');" class="btn btn-default" title="IMPRIMIR SEGUIMIENTO POA">
              <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>IMPRIMIR SEGUIMIENTO PLAN OPERATIVO ANUAL POA</b>
            </a>
          </div>
        </div>';

      return $tabla;
    }


    /*----- REPORTE SEGUIMIENTO POA PDF 2021 MENSUAL POR SUBACTIVIDAD-------*/
    public function ver_reporte_seguimientopoa_esalud($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id); ///// DATOS DEL COMPONENTE
      if(count($data['componente'])!=0){
        $data['mes'] = $this->seguimientopoa->mes_nombre();
        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']); /// PROYECTO
        }
        $data['cabecera']=$this->seguimientopoa->cabecera($data['componente'],$data['proyecto']); /// Cabecera
        $data['titulo_formulario']='<b>FORMULARIO SEGUIMIENTO POA</b> - '.$this->verif_mes[2].' / '.$this->gestion.'';

        /// ----------------------------------------------------
        $tabla=$this->seguimientopoa->tabla_form_seguimientopoa_subactividad($com_id,$this->verif_mes[1]);
        /// -----------------------------------------------------
        $data['verif_mes'] = $this->verif_mes;
        $data['operaciones']=$tabla; /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        $this->load->view('admin/evaluacion/seguimiento_poa/reporte_seguimiento_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }

    /*---- VALIDA ADD SEGUIMIENTO POA ----*/
    public function guardar_seguimiento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $ejec = $this->security->xss_clean($post['ejec']);
        $mv = $this->security->xss_clean($post['mv']);
        $obs = $this->security->xss_clean($post['obs']);
        $acc = $this->security->xss_clean($post['acc']);
        
        /// ----- Eliminando Registro --------
          $this->db->where('prod_id', $prod_id);
          $this->db->where('m_id', $this->verif_mes[1]);
          $this->db->where('g_id', $this->gestion);
          $this->db->delete('prod_ejecutado_mensual');
        /// -----------------------------------

          if($ejec!=0){
            $this->model_producto->add_prod_ejec_gest($prod_id,$this->gestion,$this->verif_mes[1],$ejec,$mv,$obs,$acc);
          }
          else{
            $no_ejec=$this->model_seguimientopoa->get_seguimiento_poa_mes_noejec($prod_id,$this->verif_mes[1]);
            if(count($no_ejec)!=0){
              if(($no_ejec[0]['medio_verificacion']!=$mv) || ($no_ejec[0]['observacion']!=$obs)){
                $this->model_producto->add_no_ejec_prod($prod_id,$this->verif_mes[1],$mv,$obs,$acc); 
              }
            }
            else{
              $this->model_producto->add_no_ejec_prod($prod_id,$this->verif_mes[1],$mv,$obs,$acc);  
            }
            
          }

        $result = array(
          'respuesta' => 'correcto',
        );
  
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*----- Lista de Gestiones Disponibles ----*/
    public function list_gestiones(){
        $listar_gestion= $this->model_configuracion->lista_gestion();
        $tabla='';

        $tabla.='
                <input type="hidden" name="gest" id="gest" value="'.$this->gestion.'">
                <select name="gestion_usu" id="gestion_usu" class="form-control" required>
                <option value="0">seleccionar gestión</option>'; 
        foreach ($listar_gestion as $row) {
            if($row['ide']==$this->gestion){
                $tabla.='<option value="'.$row['ide'].'" select >'.$row['ide'].'</option>';
            }
            else{
                $tabla.='<option value="'.$row['ide'].'" >'.$row['ide'].'</option>';
            }
        };
        $tabla.='</select>';
        return $tabla;
    }

}