<?php

//reporte de presupusto programado
class Model_pinversion extends CI_Model{
    public function __construct(){
        $this->load->database();
    }

    /*=========== GET DEPARTAMENTO ============*/
    public function get_departamento($dep_id){
        $sql = '
            select *
            from _departamentos 
            where dep_id='.$dep_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA PROYECTOS DE INVERSION POR REGIONAL --------*/
    public function list_proy_inversion_regional($dep_id,$gestion){ /// aprobados
        $sql = 'select *
                from lista_poa_pinversion_regional('.$dep_id.','.$gestion.')
                order by dep_id, dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET PROYECTO ----*/
    public function get_pinversion($proy_id,$gestion){
        $sql = '
            select *
            from lista_poa_pinversion_nacional('.$gestion.') 
            where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}