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

    /// get ID
    public function get_participante($id){
        $sql = ' select even.*,p.*,tp.*
                 from participantes p
                 Inner Join eventosdnp as even On even.even_id=p.even_id
                 Inner Join tp_certificado as tp On tp.tp_cert=p.tp_cert
                 where p.ci_id='.$id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// get CI y EVENTO
    public function get_ci_participante($ci,$even_id){
        $sql = ' select even.*,p.*,tp.*
                 from participantes p
                 Inner Join eventosdnp as even On even.even_id=p.even_id
                 Inner Join tp_certificado as tp On tp.tp_cert=p.tp_cert
                 where even.even_id='.$even_id.' and p.ci='.$ci.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// get CI y EVENTO (para buscar Certificado)
    public function get_ci_participante_habilitado($ci,$even_id){
        $sql = ' select even.*,p.*,tp.*
                 from participantes p
                 Inner Join eventosdnp as even On even.even_id=p.even_id
                 Inner Join tp_certificado as tp On tp.tp_cert=p.tp_cert
                 where even.even_id='.$even_id.' and p.ci='.$ci.' and p.estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// listado de participantes
    public function lista_participantes($even_id){
        $sql = ' select even.*,p.*,tp.*
                 from participantes p
                 Inner Join eventosdnp as even On even.even_id=p.even_id
                 Inner Join tp_certificado as tp On tp.tp_cert=p.tp_cert
                 where p.even_id='.$even_id.'
                 order by p.ci_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// lista de certificados
    public function tipo_cert(){
        $sql = '  select *
                 from tp_certificado
                 order by tp_cert asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

