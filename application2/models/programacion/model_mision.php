<?php
class model_mision extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    function pei_mision_get()
    {
        $this->db->select("*");
        $this->db->from('configuracion');
        $this->db->where('conf_estado',1);
        $query = $this->db->get();
        return $query->result_array();   
    }
    public function edita_mision($nueva_mision)
    {
        $data = array(
            'conf_mision' => $nueva_mision
        );
        $this->db->WHERE('conf_estado',1);
        return $this->db->UPDATE('configuracion',$data);
    }
}
?>  
