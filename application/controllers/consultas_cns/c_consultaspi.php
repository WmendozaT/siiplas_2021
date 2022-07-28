<?php
class C_consultaspi extends CI_Controller {  
    public $rol = array('1' => '1','2' => '10'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_ptto_sigep');
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
            $this->load->library('ejecucion_finpi');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /// Menu Principal Ejecucion de Proyectos Inversion 
    public function ejecucion_proyectos(){
      $data['menu']=$this->menu(10);
      $data['style']=$this->style();

      $data['nro_reg']=count($this->model_ptto_sigep->list_regionales());
      $data['matriz_reg']=$this->ejecucion_finpi->matriz_detalle_proyectos_clasificado_regional();



      $detalle_ejecucion=[];
                for ($i = 0; $i < $data['nro_reg']; $i++) {
                    if($i+1==$data['nro_reg']){
                        $detalle_ejecucion[$i]= '{ name: '.$data['matriz_reg'][$i][1].', y: '.$data['matriz_reg'][$i][9].' }';
                    }
                    else{
                        $detalle_ejecucion[$i]= '{ name: '.$data['matriz_reg'][$i][1].', y: '.$data['matriz_reg'][$i][9].' },';
                    }
                    
                }

        $data['detalle_ejecucion']=$detalle_ejecucion;
              /*  for ($i=0; $i < $nro_reg; $i++) { 
                    echo $detalle_ejecucion[$i]."<br>";
                }*/


      $this->load->view('admin/reportes_cns/repejecucion_pi/menu_consultas_pi', $data);
    }

























        /// ---- STYLE -----
    public function style(){
      $tabla='';

      $tabla.='   
      <style>
        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            .
        }
        th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
        }
    </style>';

      return $tabla;
    }

    //// Genera Menu
    public function menu($mod){
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

}