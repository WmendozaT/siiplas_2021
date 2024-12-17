<?php
class Model_evento extends CI_Model {

    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }

    public function get_evento($id){
        $sql = ' select *
                 from eventosdnp
                 where even_id='.$id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// listado de eventos de la gestion
    public function lista_eventos(){
        $sql = '  select *
                 from eventosdnp
                 where g_id='.$this->gestion.'
                 order by even_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_participante($id){
        $sql = ' select *
                 from participantes
                 where ci_id='.$id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// listado de participantes
    public function lista_participantes($even_id){
        $sql = '  select *
                 from participantes
                 where even_id='.$even_id.'
                 order by ci_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

