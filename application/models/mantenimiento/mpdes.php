<?php
class Mpdes extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    //obtener mi lista de pilares
    public function lista_pilar($gestion)
    {
        $this->db->SELECT('*');
        $this->db->FROM('pdes');
        $this->db->WHERE('pdes_gestion', $gestion);
        $this->db->WHERE('pdes_depende', 0);
        $this->db->ORDER_BY('pdes_codigo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    // LISTA DE pdes FILTRADO POR DEPENDENCIA
    public function lista_combo($gestion,$id_depende){
        $this->db->SELECT('*');
        $this->db->FROM('pdes');
        $this->db->WHERE('pdes_gestion', $gestion);
        $this->db->WHERE('pdes_depende', $id_depende);
        $this->db->ORDER_BY('pdes_codigo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    //obtener mi lista de pdes
    public function lista_pdes($gestion)
    {
        $this->db->SELECT('*');
        $this->db->FROM('pdes');
        $this->db->WHERE('pdes_gestion', $gestion);
        $this->db->ORDER_BY('pdes_codigo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function listar_pedes_pilar()
    {
       
        $sql = 'SELECT *from pdes 
where pdes_jerarquia=1 and pdes_estado !=0';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('mantenimiento/vlista_pdes');
    }
     public function listar_pedes_meta()
    {
       
        $sql = 'SELECT *from pdes 
where pdes_jerarquia=2 and pdes_estado !=0';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('mantenimiento/vlista_pdes');
    }
     public function listar_pedes_resultado()
    {
       
        $sql = 'SELECT *from pdes 
where pdes_jerarquia=3 and pdes_estado !=0';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('mantenimiento/vlista_pdes');
    }
     public function listar_pedes_accion()
    {
       
        $sql = 'SELECT *from pdes 
where pdes_jerarquia=4 and pdes_estado !=0';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('mantenimiento/vlista_pdes');
    }

        function verificar_pdes($pdes_codigo)
        {
            $this->db->trans_begin();
                $this->db->WHERE('pdes_codigo',$pdes_codigo);
                $this->db->FROM('pdes');
                $query = $this->db->get();
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    return $query->result_array();
                }
        }

    function add_pilar_pdes($pdes_descripcion,$pdes_gestion,$pdes_codigo)
    {

    $data=array(
                'pdes_depende'=>0,
                'pdes_nivel'=>'Pilar',
                'pdes_descripcion'=>$pdes_descripcion,
                'pdes_jerarquia'=>1,
                'pdes_estado'=>1,
                'pdes_gestion'=>$pdes_gestion,
                'pdes_codigo'=>$pdes_codigo,
                );

            $this->db->INSERT('pdes',$data);
     }      

     function mod_pilar_pdes($pdes_id,$pdes_codigo,$pdes_gestion,$pdes_descripcion)
     {
         $data=array(
                'pdes_depende'=>0,
                'pdes_nivel'=>'Pilar',
                'pdes_descripcion'=>$pdes_descripcion,
                'pdes_jerarquia'=>1,
                'pdes_estado'=>1,
                'pdes_gestion'=>$pdes_gestion,
                'pdes_codigo'=>$pdes_codigo,
                );
         $this->db->where('pdes_id',$pdes_id);
         $this->db->UPDATE('pdes',$data);
         redirect('pdes');
     }
     function mostrar_pilar_pdes($pdes_id)
     {
        $sql = 'SELECT *from pdes
        where pdes_id='.$pdes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
       
     }
   

}