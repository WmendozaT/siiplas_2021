<?php
class Capertura_programatica extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('mantenimiento/mapertura_programatica');
        $this->load->model('mantenimiento/munidad_organizacional');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
        $this->gestion = $this->session->userData('gestion'); /// Gestion
        $this->fun_id = $this->session->userData('fun_id'); /// Fun id
        //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
    }

    /*--------- LISTA DE PROGRAMAS --------*/
    public function main_apertura_programatica_padres(){
        $data['menu'] = $this->menu->genera_menu();
        $data['lista']=$this->lista_programas();
      //  $this->load->view('admin/mantenimiento/programas/vlist_programas', $data);


        $requerimientos_del = $this->model_modrequerimiento->lista_requerimientos_eliminados(6016);
       // echo count($requerimientos_del);
        foreach ($requerimientos_del as $row) {
            echo $row['ins_id'].'--'.$row['ins_detalle'].'<br>';
        }








    /*
        $insumos=$this->model_ptto_sigep->get_lista_insumos_por_partida(15271,153);
        $tabla='';

        $tabla.='
        <table border=1 cellpadding="0" cellspacing="0" style="width:90%;" align=center>
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">PARTIDA</th>
                      <th scope="col">REQUERIMIENTO</th>
                      <th scope="col">CANTIDAD</th>
                      <th scope="col">COSTO UNITARIO</th>
                      <th scope="col">COSTO TOTAL</th>
                      <th scope="col">OBSERVACIÓN</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;$sum=0;
                  foreach ($insumos as $row) {
                    $nro++;
                    $tabla.='
                    <tr>
                        <th>'.$nro.'</th>
                        <th>'.$row['par_codigo'].'</th>
                        <td>'.$row['ins_detalle'].'</td>
                        <td>'.round($row['ins_cant_requerida'],2).'</td>
                        <td>'.round($row['ins_costo_unitario'],2).'</td>
                        <td>'.round($row['ins_costo_total'],2).'</td>
                        <td>'.$row['ins_observacion'].'</td>
                       
                    </tr>';
                    $sum=$sum+$row['ins_costo_total'];
                  }
                  $tabla.='
                  </tbody>
                  <tr>
                    <td colspan=5></td>
                    <td>'.$sum.'</td>
                    <td></td>
                  </tr>
                </table>';


                echo $tabla;*/
    }

    function lista_programas(){
        $programas=$this->mapertura_programatica->list_aperturas_programaticas();
        $tabla='';

        $tabla.='
                <br>
                <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success nuevo_ff" title="NUEVO REGISTRO - ACTIVIDAD" class="btn btn-success" style="width:15.5%;">NUEVO REGISTRO</a><br><br>
                <table class="table table-bordered" style="width:90%;" align=center>
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">PROGRAMA</th>
                      <th scope="col">DESCRIPCI&Oacute;N</th>
                      <th scope="col">GESTI&Oacute;N</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  foreach ($programas as $row) {
                    $nro++;
                    $tabla.='
                    <tr>
                        <th>'.$nro.'</th>
                        <td>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>
                        <td>'.$row['aper_descripcion'].'</td>
                        <td>'.$row['aper_gestion'].'</td>
                        <td align=center><a href="#" data-toggle="modal" data-target="#modal_mod_programa" class="btn-default mod_prog" name="'.$row['aper_id'].'" title="MODIFICAR PROGRAMA" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a></td>
                        <td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR PROGRAMA"  name="'.$row['aper_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>
                    </tr>';
                  }
                  $tabla.='
                  </tbody>
                </table>';

        return $tabla;
    }


    /*-------- GET DATOS POA --------*/
    public function get_dato_programa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $aper_id = $this->security->xss_clean($post['aper_id']);
        $programa=$this->mapertura_programatica->dato_apertura($aper_id);

        if(count($programa)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'programa'=>$programa,
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


    /*--- VALIDA ADD-UPDATE PROGRAMA ---*/
     public function valida_programa(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']);

        if($tp==1){
            $programa = $this->security->xss_clean($post['prog']); 
            $descripcion = $this->security->xss_clean($post['desc']);

            $data_to_store = array( 
                'aper_gestion' => $this->gestion,
                'aper_entidad' => '0',
                'aper_proyecto' => '0000',
                'aper_actividad' => '000',
                'aper_asignado' => 1,
                'aper_programa' => $programa,
                'aper_descripcion' => $descripcion,
                'fun_id' => $this->fun_id,
            );
            $this->db->insert('aperturaprogramatica', $data_to_store);

            $this->session->set_flashdata('success','SE GUARDO CORRECTAMENTE :)');
        }
        else{
            $aper_id = $this->security->xss_clean($post['aper_id']);
            $programa = $this->security->xss_clean($post['mprog']); 
            $descripcion = $this->security->xss_clean($post['mdesc']);

           /*--- UPDATE PROGRAMA ---*/
            $update_um= array(
                'aper_programa' => $programa,
                'aper_descripcion' => $descripcion
            );
            $this->db->where('aper_id', $aper_id);
            $this->db->update('aperturaprogramatica', $update_um);
            /*----------------------*/ 

            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE :)');
        }

        
        redirect(site_url("").'/mnt/prog_p');

      } else {
          show_404();
      }
    }


    /*-------- DELETE PROGRAMA --------*/
    public function delete_dato_programa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $aper_id = $this->security->xss_clean($post['aper_id']);

         /*--- UPDATE PROGRAMA ---*/
            $update_um= array(
                'aper_proyecto' => '',
                'aper_actividad' => '',
                'aper_estado' => 3,
                'aper_asignado' => 0
            );
            $this->db->where('aper_id', $aper_id);
            $this->db->update('aperturaprogramatica', $update_um);
            /*----------------------*/ 


            $result = array(
              'respuesta' => 'correcto',
            );

        echo json_encode($result);
      }else{
          show_404();
      }
    }
}