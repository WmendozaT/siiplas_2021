<?php
class Model_notificacion extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist_id = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tmes = $this->session->userData('trimestre');
    }
    
    /*------- LISTA DE REQUERIMIENTOS POR MES (servicio) --------*/
    public function list_requerimiento_mes($proy_id,$com_id,$mes_id){
        $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')
                where com_id='.$com_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- LISTA DE REQUERIMIENTOS POR MES (servicio) --------*/
    public function list_requerimiento_mes_unidad($proy_id,$mes_id){
        $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- NRO DE REQUERIMIENTOS A CERTIFICAR POR DISTRITAL AL MES ACTUAL --------*/
    public function nro_requerimientos_acertificar_mensual_x_mes_distrital($dist_id,$mes_id){
        $sql = 'select *
                from seguimiento_numero_requerimientos_ppto_distrital_mensual('.$dist_id.', '.$mes_id.','.$this->gestion.') ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- NRO DE REQUERIMIENTOS A CERTIFICAR POR REGIONAL AL MES ACTUAL --------*/
    public function nro_requerimientos_acertificar_mensual_x_mes_regional($dep_id,$mes_id){
        $sql = 'select *
                from seguimiento_numero_requerimientos_ppto_regional_mensual('.$dep_id.', '.$mes_id.','.$this->gestion.') ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET MES -----*/
    function get_mes($mes_id){
        $sql = 'select *
                from mes
                where m_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
