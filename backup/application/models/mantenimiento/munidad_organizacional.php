<?php

class Munidad_organizacional extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //======================================  UNIDAD ORGANIZACIONAL =================================================
    function lista_unidad_org()
    {
        $this->db->select('*');
        $this->db->from('unidadorganizacional');
        $this->db->where('uni_estado', 1);
        $this->db->WHERE('(uni_estado = 1 OR uni_estado = 2 )');
        $query = $this->db->get();
        return $query->result_array();
    }
    // obtener unidad organizacional, filtrado por uni_id
    function get_unidad_org($uni_id){
        $this->db->select('*');
        $this->db->from('unidadorganizacional');
        $this->db->where('uni_id', $uni_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    //ROY -> Obtener dato de la unidad
    function get_unidad($uni_id){
        $this->db->select('*');
        $this->db->from('unidadorganizacional');
        $this->db->where('uni_id', $uni_id);
        $query = $this->db->get();
        return $query->row();
    }



}