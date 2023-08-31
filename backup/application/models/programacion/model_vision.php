<?php
class model_vision extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }
   
    function pei_vision_get()
    {
        $this->db->select("*");
        $this->db->from('configuracion');
        $this->db->where('conf_estado',1);
        $query = $this->db->get();
        return $query->result_array();   
    }
    public function edita_vision($nueva_vision)
    {
        $data = array(
            'conf_vision' => $nueva_vision 
        );
        $this->db->WHERE('conf_estado',1);
        return $this->db->UPDATE('configuracion',$data);
    }
}
?>  
