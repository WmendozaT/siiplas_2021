<?php
class Model_analisis_situacion extends CI_Model {

    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }
    
    /*--------- Lista de Problemas ----------*/
    public function list_analisis_problemas($proy_id){
        $sql = 'select *
                from analisis_situacion_problemas
                where proy_id='.$proy_id.' and estado!=\'3\'
                order by prob_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de Problemas (Reporte) ----------*/
    public function list_analisis_problemas_reporte($proy_id){
        $sql = 'select *
                from analisis_situacion_problemas ap
                Inner Join analisis_causas_acciones as ca On ap.prob_id=ca.prob_id
                where ap.proy_id='.$proy_id.' and ap.estado!=\'3\'
                order by ap.prob_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- get problema ----------*/
    public function get_problema($prob_id){
        $sql = 'select *
                from analisis_situacion_problemas
                where prob_id='.$prob_id.' and estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de Causas ----------*/
    public function lista_causas_acciones($prob_id){
        $sql = 'select *
                from analisis_causas_acciones
                where prob_id='.$prob_id.' and estado!=\'3\'
                order by ca_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- get Causas-Acciones ----------*/
    public function get_causas_acciones($ca_id){
        $sql = 'select *
                from analisis_causas_acciones ca
                Inner Join analisis_situacion_problemas as ap On ap.prob_id=ca.prob_id
                where ca.ca_id='.$ca_id.' and ca.estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
