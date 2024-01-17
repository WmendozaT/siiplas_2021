<?php
class Model_resumenactividad extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
   

    /////// ACTIVIDAD ---- OBJETIVO REGIONAL - INSTITUCIONAL
    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR OBJETIVO DE GESTION ----*/
    public function resumen_actividad_objetivo_gestion_institucional(){
        $sql = 'select og.og_codigo,og.og_objetivo, count(pr) actividades
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                where pr.estado!=\'3\'
                group by og.og_codigo,og.og_objetivo
                order by og.og_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /////// ACTIVIDAD ---- OBJETIVO REGIONAL - REGIONAL
    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR OBJETIVO DE GESTION ----*/
    public function resumen_actividad_objetivo_gestion_regional($dep_id){
        $sql = 'select og.og_codigo,og.og_objetivo, count(pr) actividades
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                where poa.dep_id='.$dep_id.' and pr.estado!=\'3\'
                group by og.og_codigo,og.og_objetivo
                order by og.og_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /////// ACTIVIDAD ---- APERTURA PROGRAMATICA
    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR CATEGORIA PROGRAMATICA ----*/
    public function resumen_actividad_categoria_institucional(){
        $sql = 'select *
                from alineacion_poa_programa_institucional('.$this->gestion.')';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR CATEGORIA PROGRAMATICA - REGIONAL ----*/
    public function resumen_actividad_categoria_regional($dep_id){
         $sql = '   select *
                    from alineacion_poa_programa_regional('.$this->gestion.','.$dep_id.')';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



}