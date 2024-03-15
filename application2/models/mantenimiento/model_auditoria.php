<?php
class Model_auditoria extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    public function get_auditoria()
    {
        $query = "SELECT *
        FROM tbl_audi_proyecto
        WHERE char_length(old) <=  5000 and char_length(new) <= 5000
        LIMIT 50;";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function get_auditoria_id($pk_auditoria)
    {
        $query = "SELECT *
        FROM tbl_audi_proyecto
        WHERE pk_audi_proy = $pk_auditoria";
        $query = $this->db->query($query);
        $query = $query->row();
        return $query;
    }
    public function get_auditoria_id2($pk_auditoria)
    {
        $query = "SELECT *
        FROM tbl_audi_proyecto
        WHERE pk_audi_proy = $pk_auditoria";
        $query = $this->db->query($query);
        return $query->result_array();
    }
    public function get_datos_tabla($nombre_tabla)
    {
        $query = "SELECT column_name, data_type, udt_name
        FROM information_schema.columns
        WHERE table_schema = 'public' AND table_name like '$nombre_tabla'";
        $query = $this->db->query($query);
        return $query->result_array();
    }
}