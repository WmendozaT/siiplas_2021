<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Genera_informacion extends CI_Controller{

        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->model('programacion/model_componente');
            $this->load->model('menu_modelo');
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->verif_mes=$this->session->userData('mes_actual');
            $this->resolucion=$this->session->userdata('rd_poa');
    }


      /// ==== Presupuesto Por unidad/ Proyecto
      public function ppto_actividad($proyecto,$tp_id){
        $salida[1]=0;$salida[2]=0;$salida[3]=0;

        $ppto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto['aper_id'],1); /// Asignado
        if($tp_id==1){
          $ppto_prog=$this->model_ptto_sigep->suma_ptto_pinversion($proyecto['proy_id']); /// Programado Proyecto Inversion
        }
        else{
          $ppto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto['aper_id'],2); /// Programado Gasto Corriente
        }

        $monto_asignado=0;$monto_programado=0;$saldo=0;
        if(count($ppto_asig)!=0){
          $monto_asignado=$ppto_asig[0]['monto'];
        }

        if(count($ppto_prog)!=0){
          $monto_programado=$ppto_prog[0]['monto'];
        }

        $saldo=($monto_asignado-$monto_programado);
        $salida[1]=$monto_asignado; /// asignado
        $salida[2]=$monto_programado; /// Programado
        $salida[3]=$saldo; /// Saldo

        return $salida;
    }




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
}
?>