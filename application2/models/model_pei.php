<?php
class model_pei extends CI_Model {
  
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }
   
   

    /////FUNCION PARA EL REGISTRO DE LOS USUARIOS
    function pei_mision_get()
    {
        $query=$this->db->query('SELECT * FROM "public"."configuracion" AS BEN
                        WHERE BEN."conf_estado"=\'1\'
                           ');
        //$query=$query->row_array();
        return $query->row_array();
    }
    /////FUNCION PARA EL REGISTRO DE LOS USUARIOS
    function pei_mision_edit()
    {
         
       $opts = array(
            'conf_mision' => $this->input->post('mision'),
            );
        $this->db->where('conf_estado',1);
        $this->db->update('public.configuracion',$opts);

    }
    /////FUNCION PARA EL REGISTRO DE LOS USUARIOS
    function pei_vision_get()
    {
        $query=$this->db->query('SELECT * FROM "public"."configuracion" AS BEN
                        WHERE BEN."conf_estado"=\'1\'
                           ');
        //$query=$query->row_array();
        return $query->row_array();
    }
    /////FUNCION PARA EL REGISTRO DE LOS USUARIOS
    function pei_vision_edit()
    {
         
       $opts = array(
            'conf_vision' => $this->input->post('mision'),
            );
        $this->db->where('conf_estado',1);
        $this->db->update('public.configuracion',$opts);

    }
}
?>  
