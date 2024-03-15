<?php
class model_entidad_tras extends CI_Model {
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }
    public function lista_entidad_tras()
    {
        $sql = 'SELECT *from entidadtransferencia where et_estado=1';
        $query = $this->db->query($sql);
        return $query->result_array();
        redirect('partidas');
    } 
    function verificar_etcod($cod,$fecha){
        $this->db->trans_begin();
        $this->db->WHERE('et_codigo',$cod);
        $this->db->WHERE('et_estado',1);
        $this->db->WHERE('et_gestion',$fecha);
        $this->db->FROM('entidadtransferencia');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
    function mod_et($etid,$etdescripcion,$etsigla,$etgestion,$etcodigo){
        $etdescripcion = strtoupper($etdescripcion);
        $etsigla = strtoupper($etsigla);
        $sql = "UPDATE entidadtransferencia SET et_descripcion='".$etdescripcion."',et_sigla='".$etsigla."',et_gestion=".$etgestion." ,et_codigo=".$etcodigo." WHERE et_id=".$etid;
        $this->db->query($sql);
    }
    function add_et($etdescripcion,$etsigla,$etcodigo,$etgestion){
        $id_antes = $this->generar_id('entidadtransferencia','et_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'et_id' => $nuevo_id,
            'et_descripcion' => strtoupper( $etdescripcion),
            'et_sigla' => strtoupper($etsigla),
            'et_estado' => 1,
            'et_codigo' => $etcodigo,
            'et_gestion' => $etgestion,
        );
        $this->db->insert('entidadtransferencia',$data);
    }
    function dato_et($id)
    {
        $this->db->WHERE('et_id',$id);
        $this->db->from('entidadtransferencia');
        $query = $this->db->get();
        return $query->result_array();
    }
    function generar_id($tabla,$id){
        $query =$this->db->query('SELECT MAX('.$id.') AS id_antes FROM '.$tabla);
        return $query->result_array();
       // return $query->row_array();
    }
    
}
?>  
