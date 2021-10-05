<?php
class Cconf_pinversion extends CI_Controller {
    public $rol = array('1' => '1');
    public function __construct(){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
          $this->load->model('Users_model','',true);
          if($this->rolfun($this->rol)){ 
              $this->load->library('pdf');
              $this->load->library('pdf2');
              $this->load->model('Users_model','',true);
              $this->load->model('menu_modelo');
              $this->load->model('mantenimiento/model_configuracion');
              $this->load->model('mantenimiento/model_estructura_org');
              $this->load->model('programacion/model_componente');
              $this->load->model('programacion/model_faseetapa');
              $this->load->model('programacion/model_proyecto');
              $this->load->model('programacion/model_producto');
              $this->load->model('reporte_eval/model_evalregional');
              $this->load->model('ejecucion/model_certificacion');
              $this->load->model('programacion/insumos/model_insumo');
              $this->load->library("security");
              $this->gestion = $this->session->userData('gestion');
              $this->rol = $this->session->userData('rol');
              $this->fun_id = $this->session->userData('fun_id');
              $this->tmes = $this->session->userData('trimestre');
          }
          else{
              redirect('admin/dashboard');
          }
      }
      else{
              redirect('/','refresh');
      }
    }

    /*------- LISTA DE PROYECTOS DE INVERSIÓN --------*/
    public function list_proyectos(){ 
      $data['menu']=$this->menu(9);
      $data['proyectos1']=$this->proyectos_inversion1(); /// Lista de Proyectos de Inversion
      $data['proyectos2']=$this->proyectos_inversion2(); /// Lista de Proyectos de Inversion


      $this->load->view('admin/mantenimiento/reportes_consolidados/vlist_consolidado', $data);
    }


    /*------- FASES DEL PROYECTO --------*/
    public function ver_fases($proy_id){ 
      $data['menu']=$this->menu(9);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
      if(count($proyecto)!=0){
        $data['titulo']='
            <section id="widget-grid" class="well">
                <div class="">
                  <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['proy_sisin'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</small></h1>
                </div>
            </section>';
        $this->load->view('admin/mantenimiento/reportes_consolidados/mis_fases', $data);
      }
      else{
        echo "Error !!!";
      }
    }


 /*Lista de Proyectos de Inversion-activar fases*/
    public function proyectos_inversion1(){ 
        $tabla='';
        $proyectos = $this->model_proyecto->list_proyectos_inversion();//lista de proyectos de inversion
        $nro=0;
        foreach($proyectos  as $row){
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $nro++;
            $tabla.=
                '<tr style="height:25px;">
                    <td title='.$row['proy_id'].' align=center>'.$nro.'</td>
                    <td align=center><a href="'.site_url("").'/proy_ver_fases/'.$row['proy_id'].'" title="VER FASES DEL PROYECTO" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="34" HEIGHT="30"/></a></td>
                    <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                    <td>'.$row['proy_nombre'].'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                    <td>'.strtoupper($row['dep_departamento']).'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>';
                   
            $tabla.='
                </tr>';
        }
        return $tabla;
    }

    /*Lista de Proyectos de Inversion-activar fases*/
    public function proyectos_inversion2(){ 
        $tabla='';
        $proyectos = $this->model_proyecto->list_proyectos_inversion();//lista de proyectos de inversion
        $nro=0;
        foreach($proyectos  as $row){
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $nro++;
            $tabla.=
                '<tr style="height:25px;">
                    <td title='.$row['proy_id'].'>'.$nro.'</td>
                    <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                    <td>'.$row['proy_nombre'].'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                    <td>'.strtoupper($row['dep_departamento']).'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>';
                    if(count($fase)!=0){
                        $fases=$this->model_faseetapa->fase_etapa_proy($row['proy_id']);
                        $tabla.='
                        <td>'.strtoupper($fase[0]['fase']).'</td>
                        <td>'.strtoupper($fase[0]['descripcion']).'</td>
                        <td>'.number_format($fase[0]['pfec_ptto_fase'], 2, ',', '.').' Bs.</td>
                        <td>
                            <select class="form-control" onchange="doSelectAlert(event,this.value,'.$row['proy_id'].');" style="width:100%;">';
                                foreach($fases as $pr){
                                    if(($pr['fas_id']==$fase[0]['fas_id']) & ($pr['pfec_estado']==1)){
                                        $tabla .="<option value=".$pr['id']." selected>".$pr['fase']." : (".$pr['pfec_fecha_inicio']." - ".$pr['pfec_fecha_fin'].") - ".$pr['descripcion']."</option>";
                                    }
                                    else{
                                        $tabla .="<option value=".$pr['id'].">".$pr['fase']." : (".$pr['pfec_fecha_inicio']." - ".$pr['pfec_fecha_fin'].") - ".$pr['descripcion']."</option>"; 
                                    }  
                                }
                                $tabla.='
                            </select> 
                        </td>';
                    }
                    else{
                        $tabla.='<td></td>
                                <td></td>
                                <td></td>
                                <td></td>';
                    }
            $tabla.='
                </tr>';
        }
        return $tabla;
    }

    /*======= ACTIVAR FASE DEL PROYECTO =======*/

    function activar_fase(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $this->form_validation->set_rules('proy id', 'Proyecto Id', 'required|trim');
            $this->form_validation->set_rules('fase id', 'Fase Id', 'required|trim');
            $post = $this->input->post();

            $proy_id=$this->security->xss_clean($post['proy_id']);
            $pfec_id=$this->security->xss_clean($post['pfec_id']);
            
           // $this->model_faseetapa->encender_fase_etapa($pfec_id,$proy_id);

            $update_fase = array(
            'pfec_estado' => 0
            );
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase);

            $update_fase2 = array(
            'pfec_estado' => 1
            );
            $this->db->where('pfec_id', $pfec_id);
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase2);


            $fase_activa=$this->model_faseetapa->get_id_fase($proy_id);
            if($fase_activa[0]['id']==$pfec_id){
                echo "true";
            }
            else{
                echo "false";
            }
                
        }else{
            show_404();
        }
           
    }

    /*---------- Menu --------------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++)
        {
            if(count($subenlaces[$enlaces[$i]['o_child']])>0)
            {
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

    /*----------- Rol Usuario --------------*/
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
}