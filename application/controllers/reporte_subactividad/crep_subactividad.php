<?php
class Crep_subactividad extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

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
            $this->load->library('seguimientopoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    //// REPORTE POR POR CADA SUBACTIVIDAD
    public function menu_reporte_subactividad($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id,$this->gestion);
      if(count($data['componente'])!=0){
        $data['menu'] = $this->seguimientopoa->menu_segpoa($com_id,3);
        $data['resp']=$this->session->userdata('funcionario');
        $data['select']=' 
        <div class="well">
          <form class="smart-form">
            <input name="base" type="hidden" value="'.base_url().'">
            <input name="com_id" type="hidden" value="'.$com_id.'">
            <header>
                <b><div style="font-size: x-large; font-variant: small-caps;">'.$data['componente'][0]['tipo_subactividad'].' '.$data['componente'][0]['serv_descripcion'].' - GESTI&Oacute;N '.$this->gestion.'</div></b>
            </header>
            <fieldset>          
              <div class="row">
                <section class="col col-2">
                  <label class="label"></label>
                  <select class="form-control" id="rep_id" name="rep_id" title="SELECCIONE TIPO DE REPORTE">
                    <option value="0">SELECCIONE TIPO DE REPORTE</option>
                    <option value="1">1.- Actividades - Formulario N° 4</option>
                    <option value="2">2.- Requerimientos - formulario N° 5</option>
                    <option value="3">3.- Ejecución Requerimientos - Formulario N° 5</option>
                    <option value="4">4.- Notificación POA - '.$this->verif_mes[2].'/'.$this->gestion.'</option>
                  </select>
                </section>
              </div>
            </fieldset>
          </form>
        </div>';

        $this->load->view('admin/reportes_cns/rep_subactividad/menu_principal', $data);
      }
      else{
        echo "Error !!";
      }
    }


    /*--- GET LISTA DE REPORTES ---*/
    public function get_lista_reportepoa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $rep_id = $this->security->xss_clean($post['rep_id']);
        $com_id = $this->security->xss_clean($post['com_id']);

        $salida='';
        if($rep_id==1){
          $salida='
          <form class="smart-form">
            <header><b>FORMULARIO N° 4 - ACTIVIDADES '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/prog/reporte_form4/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==2) {
          $salida='
          <form class="smart-form">
            <header><b>FORMULARIO N° 5 - REQUERIMIENTOS '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/proy/orequerimiento_proceso/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==3) {
          $salida='
          <form class="smart-form">
            <header><b>CERTIFICACIÓN POA, FORMULARIO N° 5 - REQUERIMIENTOS '.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/rep/rep_requerimientos_ejecucion_servicio/'.$com_id.'"></iframe>
          </form>';
        }
        elseif ($rep_id==4) {
          $salida='
          <form class="smart-form">
            <header><b>NOTIFICACIÓN POA - MES '.$this->verif_mes[2].'/'.$this->gestion.'</b></header>
            <iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/get_notificacion_subactividad_mensual/'.$com_id.'"></iframe>
          </form>';
        }


        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*----- REPORTE NOTIFICACION POA MENSUAL POR GASTO CORRIENTE 2021 -----*/
    public function get_notificacion_subactividad($com_id){
      $componente = $this->model_componente->get_componente($com_id,$this->gestion);
      if(count($componente)!=0){
        $data['cuerpo']=$this->seguimientopoa->get_notificacion_subactividad($com_id);
        $data['titulo_pie']='NOTIFICACION_POA_'.$this->verif_mes[2].'_'.$componente[0]['tipo_subactividad'].'_'.$componente[0]['serv_descripcion'];
        $this->load->view('admin/reportes_cns/rep_subactividad/reporte_notificacion_seguimiento_subactividad', $data); 
        
       // echo $data['cuerpo'];

      }
      else{
        echo "Error !!!";
      }

    }

}