<?php
class model_partidas extends CI_Model {
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct(){
        $this->load->database();
    }

    public function lista_partidas(){
        $sql = 'select *
                from partidas
                where par_id!=\'0\' and par_depende!=\'0\'
                order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
 

    function verificar_parcod($cod,$gestion){
        $this->db->trans_begin();
        $this->db->WHERE('par_codigo',$cod);
        $this->db->WHERE('par_gestion',$gestion);
        $this->db->FROM('partidas');
        $query = $this->db->get();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            return $query->result_array();
        }
    }
     function mod_par($par_id,$par_nombre,$par_gestion,$par_codigo){
        $par_nombre = strtoupper($par_nombre);
        $sql = "UPDATE partidas SET par_nombre='".$par_nombre."' ,par_gestion=".$par_gestion." ,par_codigo=".$par_codigo." WHERE par_id=".$par_id;
        $this->db->query($sql);
    }
    function dato_par_codigo($cod){
        $this->db->WHERE('par_codigo',$cod);
        $this->db->from('partidas');
        $query = $this->db->get();
        return $query->result_array();
    }
    function add_par_independiente($par_nombre,$par_codigo,$par_gestion){
        $id_antes = $this->generar_id('partidas','par_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'par_id' => $nuevo_id,
            'par_nombre' => strtoupper( $par_nombre),
            'par_depende' => 0,
            'par_codigo' => $par_codigo,
            'par_gestion' => $par_gestion,
        );
        $this->db->insert('partidas',$data);
    }
    function add_par_dependiente($par_nombre,$par_padre,$par_codigo,$par_gestion){
        $id_antes = $this->generar_id('partidas','par_id');
        $nuevo_id = $id_antes[0]['id_antes'];
        $nuevo_id++;
        $data = array(
            'par_id' => $nuevo_id,
            'par_nombre' => strtoupper( $par_nombre),
            'par_depende' => $par_padre,
            'par_codigo' => $par_codigo,
            'par_gestion' => $par_gestion,
        );
        $this->db->insert('partidas',$data);
    }

    function lista_padres(){
        $this->db->where('par_depende',0);
        $this->db->from('partidas');
        $this->db->order_by("par_id", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    function dato_par($id){
        $this->db->WHERE('par_id',$id);
        $this->db->from('partidas');
        $query = $this->db->get();
        return $query->result_array();
    }

    function generar_id($tabla,$id){
        $query =$this->db->query('SELECT MAX('.$id.') AS id_antes FROM '.$tabla);
        return $query->result_array();
       // return $query->row_array();
    }

    function lista_par_hijos($par_depende){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_depende',$par_depende);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_partida_padre($par_codigo){
        $sql = 'select *
                from partidas
                where par_codigo='.$par_codigo.' and par_depende=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /*--------- Get Partida ---------*/
    public function get_partida($par_id){
        $sql = 'select *
                from partidas
                where par_id='.$par_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista Partida y sus dependientes ---*/
    public function lista_partida_dependientes(){
        $sql = 'select p.par_codigo as cpadre, p.par_nombre as ppadre, pd.par_id, pd.par_codigo as chijo, pd.par_nombre as phijo
                from partidas p
                Inner Join partidas as pd On pd.par_depende=p.par_codigo
                where p.par_id!=\'0\'
                order by p.par_codigo, pd.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
?>  
