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
        $this->com_id=$this->session->userdata('com_id');
        $this->load->library('seguimientopoa');

        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }


  /*----- FORMULARIO SEGUIMIENTO POA ESTABLECIMIENTOS ------*/
  public function formulario_establecimiento(){
    $com_id=$this->establecimiento[0]['com_id'];
    $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
    $data['menu'] = $this->seguimientopoa->menu_segpoa($com_id,1);
    $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
    $data['com_id']=$com_id;
    $data['proy_id']=$this->establecimiento[0]['proy_id'];
    if(count($data['componente'])!=0){

      $data['datos_mes'] = $this->verif_mes;
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $data['titulo']='
      <h2 title='.$data['proyecto'][0]['aper_id'].'>BIENVENIDO : '.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['proy_nombre'].' - '.$data['proyecto'][0]['abrev'].'</h2>
      <h1><small>TRIMESTRE VIGENTE : </small> '.$data['tmes'][0]['trm_descripcion'].'</h1>
      <h1><small>FORMULARIO DE SEGUIMIENTO MES : </small> '.$this->verif_mes[2].' / '.$this->gestion.'</h1>';

      //$data['cabecera1']=$this->seguimientopoa->cabecera_seguimiento($this->model_seguimientopoa->get_unidad_programado_gestion($data['proyecto'][0]['act_id']),$data['componente'],1,$this->tmes); /// cabecera reporte
      $data['tabla']=$this->seguimientopoa->tabla_regresion_lineal_servicio($com_id,$this->tmes); /// Tabla para el grafico al trimestre

      $data['calificacion']='<hr><div id="calificacion" style="font-family: Arial;font-size: 10%;">'.$this->seguimientopoa->calificacion_eficacia($data['tabla'][5][$this->tmes],0).'</div>';
      
      $data['update_eval']=$this->seguimientopoa->button_update_($com_id);
      $data['operaciones_programados']=$this->seguimientopoa->lista_operaciones_programados($com_id,$this->verif_mes[1],$data['tabla']); /// Lista de Operaciones programados en el mes
      
      $data['formularios_seguimiento']=$this->seguimientopoa->formularios_mensual($com_id);
      $data['salir']='<a href="'.site_url("").'/dashboar_seguimiento_poa" title="SALIR" class="btn btn-default"><img src="'.base_url().'assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="19"/>&nbsp; SALIR</a>';
      $this->load->view('admin/evaluacion/seguimiento_establecimiento/formulario_seguimiento_establecimiento', $data);
    }
    else{
      $this->session->sess_destroy();
      redirect('/','refresh');
    }
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



    /*----- REPORTE SEGUIMIENTO POA PDF 2021 MENSUAL POR SUBACTIVIDAD-------*/
    public function ver_reporte_seguimientopoa_esalud($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion); ///// DATOS DEL COMPONENTE
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