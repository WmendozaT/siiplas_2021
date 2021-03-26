<?php

class cambiar_gestion extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('mantenimiento/mpdes');
        $this->load->model('mantenimiento/model_configuracion');
         //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
    }

    public function cambia_mes_sesion(){
        $nuevo_mes = $this->input->post('mes_sesion');
        $desc_nuevo_mes = $this->mes_texto($nuevo_mes);
        $this->session->set_userdata('mes', $nuevo_mes);
        $this->session->set_userdata('desc_mes', $desc_nuevo_mes);
    //    $this->session->set_userdata('gestion', $gestion_usu);
    //    $this->session->set_userdata('fun_id', $this->fun_id);
        echo "
            <script>
                alert('La Operacion se realizo con exito');
            </script>
        ";
        redirect('cambiar_gestion','refresh');
    }

    public function mes_texto($mes) {
        switch ($mes) {
            case '1':
                $texto = 'Enero';
                break;
            case '2':
                $texto = 'Febrero';
                break;
            case '3':
                $texto = 'Marzo';
                break;
            case '4':
                $texto = 'Abril';
                break;
            case '5':
                $texto = 'Mayo';
                break;
            case '6':
                $texto = 'Junio';
                break;
            case '7':
                $texto = 'Julio';
                break;
            case '8':
                $texto = 'Agosto';
                break;
            case '9':
                $texto = 'Septiembre';
                break;
            case '10':
                $texto = 'Octubre';
                break;
            case '11':
                $texto = 'Noviembre';
                break;
            case '12':
                $texto = 'Diciembre';
                break;
            default:
                $texto = 'Sin Mes asignado';
                break;
        }
        return $texto;
    }
   
    public function listar_c_gestion(){
        $listar_gestion= $this->model_configuracion->lista_gestion();
        $tabla='';
        $tabla.='';
        $tabla.='<form   method="post" action="'.base_url().'index.php/cambiar">
        <input class="form-control" type="hidden" name="fun_id" value="'.$this->session->userdata("fun_id").'">
                <select name="gestion_usu" class="form-control" required>
                 <option value="2017">seleccionar gestion</option>'; 
        foreach ($listar_gestion as $row) {
        $tabla.='<option value="'.$row['ide'].'" >'.$row['ide'].'</option>';
              };
        $tabla.='  </select><br>
                    <BUTTON class="btn btn-xs btn-primary">
                        <div class="btn-hover-postion1">
                           Cambiar Gesti&oacute;n
                        </div>
                    </BUTTON>
            </form>';
        $data['gestion']=$tabla;
        $data['mes'] = $this->session->userdata('mes');
        $data['mes_texto'] = $this->mes_texto($this->session->userdata('mes'));
        $ruta = 'mantenimiento/vlista_cambiargestion';
        $this->construir_vista($ruta,$data);
    }
    function construir_vista($ruta,$data){
        //----------------------------------- MENU-------------------------------
        $menu['enlaces'] = $this->menu->get_enlaces();
        $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        $menu['titulo'] = 'MANTENIMIENTO';
        //-----------------------------------------------------------------------
        //armar vista
        $this->load->view('includes/header');
        $this->load->view('includes/menu_lateral',$menu);
        $this->load->view($ruta,$data);//contenido
        //$this->load->view('admin/mantenimiento/vprueba');//contenido
        $this->load->view('includes/footer');
    }
}