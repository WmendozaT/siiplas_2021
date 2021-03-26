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
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\' and pr.estado!=\'3\'

                group by og.og_codigo,og.og_objetivo
                order by og.og_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /////// ACTIVIDAD ---- OBJETIVO REGIONAL - REGIONAL
    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR OBJETIVO DE GESTION ----*/
    public function resumen_actividad_objetivo_gestion_regional($dep_id){
        $sql = 'select og.og_codigo,og.og_objetivo, count(pr) actividades
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\' and pr.estado!=\'3\'

                group by og.og_codigo,og.og_objetivo
                order by og.og_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }






    /////// ACTIVIDAD ---- APERTURA PROGRAMATICA
    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR CATEGORIA PROGRAMATICA ----*/
    public function resumen_actividad_categoria_institucional(){
        $sql = 'select apg.aper_programa,aper.aper_descripcion,count(pr) actividades
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join aperturaprogramatica as aper On apg.aper_programa=aper.aper_programa
                
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and 
                apg.aper_estado!=\'3\' and pr.estado!=\'3\' and aper.aper_proyecto=\'0000\' and aper.aper_actividad=\'000\' and aper.aper_gestion='.$this->gestion.'
                group by apg.aper_programa, aper.aper_descripcion
                order by apg.aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET DATOS DE RESUMEN NRO DE ACTIVIDAD POR CATEGORIA PROGRAMATICA - REGIONAL ----*/
    public function resumen_actividad_categoria_regional($dep_id){
        $sql = 'select apg.aper_programa,aper.aper_descripcion,count(pr) actividades
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join aperturaprogramatica as aper On apg.aper_programa=aper.aper_programa
                
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and 
                apg.aper_estado!=\'3\' and pr.estado!=\'3\' and aper.aper_proyecto=\'0000\' and aper.aper_actividad=\'000\' and aper.aper_gestion='.$this->gestion.'
                group by apg.aper_programa, aper.aper_descripcion
                order by apg.aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


}