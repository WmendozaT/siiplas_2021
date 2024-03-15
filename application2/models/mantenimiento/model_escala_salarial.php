<?php
class model_escala_salarial extends CI_Model {
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }
    public function lista_escala_salarial()
    {
        $sql = 'SELECT  car_id,car_depende, car_cargo,car_sueldo from cargo';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('mantenimiento/vlista_escala_salarial');
    } 
   /* public function add_escala($car_nombre,$dependiente,$car_sueldo,$car_codigo)
    {

        $datos=array('car_id'=>$car_codigo,
                    'car_depende'=>$dependiente,
                    'car_cargo'=>$car_nombre,
                    'car_estado'=>1,
                    'car_jerarquia'=>1,
                    'car_sueldo'=>$car_sueldo
                    );
        $this->db->INSERT('cargo',$datos);
        redirect('escala_salarial');  
    }
    public function del_escala($del_car)
    {
       $sql = 'DELETE FROM public.cargo
                WHERE car_id='.$del_car.'';
        $query = $this->db->query($sql);
        
        redirect('escala_salarial'); 
    }*/

    function verificar_carcod($cod){
        $this->db->trans_begin();
        $this->db->WHERE('car_id',$cod);
        $this->db->FROM('cargo');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
    function mod_car_independiente($car_id,$car_nombre,$car_sueldo){
        $data = array(
            'car_cargo' => $car_nombre,
            'car_sueldo' => $car_sueldo,
            'car_depende' => 0,
        );
        $this->db->WHERE('car_id',$car_id);
        $this->db->UPDATE('cargo',$data);
    }
    function mod_car_dependiente($car_id,$car_nombre,$padre,$car_sueldo){
        $data = array(
            'car_cargo' => $car_nombre,
            'car_sueldo' => $car_sueldo,
            'car_depende' => $padre,
        );
        $this->db->WHERE('car_id',$car_id);
        $this->db->UPDATE('cargo',$data);
    }
     function add_car_independiente($id,$car_nombre,$car_sueldo){
        $data = array(
            'car_id' => $id,
            'car_cargo' => strtoupper( $car_nombre),
            'car_depende' => 0,
            'car_sueldo' => $car_sueldo,
        );
        $this->db->insert('cargo',$data);
    }
    function add_car_dependiente($car_codigo,$car_nombre,$padre,$car_sueldo){
        $data = array(
            'car_id' => $car_codigo,
            'car_cargo' => strtoupper($car_nombre),
            'car_depende' => $padre,
            'car_sueldo' => $car_sueldo,
        );
        $this->db->insert('cargo',$data);
    }
    public function get_car($id)
    {
        $this->db->select("*");
        $this->db->from('cargo');
        $this->db->where('car_estado',1);
        $this->db->where('car_id',$id);
        $query = $this->db->get();

        return $query->result_array();
    }
    public function list_car_padre()
    {
        $this->db->select("*");
        $this->db->from('cargo');
        $this->db->where('car_depende',0);
        $this->db->where('car_estado',1);
        $query = $this->db->get();

        return $query->result_array();
    }

}
?>  
