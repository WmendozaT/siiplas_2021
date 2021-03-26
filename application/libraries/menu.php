<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Menu extends CI_Controller{
    public $mod;
    //funciones que queremos implementar en Miclase.
    
    function const_menu($f){
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->mod = $f;
        }else{
            redirect('/','refresh');
        }
    }


    function genera_menu(){
        $enlaces=$this->menu_modelo->get_Modulos($this->mod);
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

}
?>