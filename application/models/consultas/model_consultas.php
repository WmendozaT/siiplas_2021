<?php
class Model_consultas extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tmes = $this->session->userData('trimestre');
    }
    
    /*--------------------- DEPARTAMENTO - DISTRITAL ----------------------*/
    public function operaciones_regionales($dep_id){
         $sql = '
                select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,
                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.dep_id='.$dep_id.' and apg.aper_estado!=\'3\'
                ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id, p.tp_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------------- Lista de Responsables --------------*/
    public function list_responsables(){
        $sql = 'select *
                from funcionario f
                where f.fun_estado!=\'3\' and f.tp_adm!=\'1\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- verif id consultas internas ------*/
    public function verif_cons_int($fun_id){
        $sql = 'select *
                from fun_rol
                where fun_id='.$fun_id.' and r_id=\'10\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
