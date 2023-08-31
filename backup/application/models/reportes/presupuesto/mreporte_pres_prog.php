<?php

//reporte de presupusto programado
class Mreporte_pres_prog extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    function lista_proyectos($programa, $gestion)
    {
        $sql = "
        SELECT p.proy_id, a.programatica, p.proy_nombre, p.uni_unidad, p.tp_tipo, p.tp_id, CASE WHEN coalesce( p.ejecucion ,'0') = 'DIRECTA' THEN 1
        WHEN coalesce( p.ejecucion ,'0') = 'DELEGADA' THEN 2 ELSE 0 END AS tipo_ejec
        FROM  vista_apertura_programatica_dictamen a
        INNER JOIN  vista_proyecto_dictamen p ON a.proy_id = p.proy_id
        WHERE a.programatica LIKE '" . $programa . "%' AND a.aper_gestion = " . $gestion . " AND p.aper_gestion = " . $gestion . "
        ORDER BY a.programatica
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function proy_prog_directo($proy_id, $gestion)
    {
        $this->db->SELECT('*');
        $this->db->FROM('v_presupuesto_programado_directo');
        $this->db->WHERE('ins_gestion', $gestion);
        $this->db->WHERE('ifin_gestion', $gestion);
        $this->db->WHERE('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function proy_prog_delegado($proy_id, $gestion)
    {
        $this->db->SELECT('*');
        $this->db->FROM('v_presupuesto_programado_delegado');
        $this->db->WHERE('ins_gestion', $gestion);
        $this->db->WHERE('ifin_gestion', $gestion);
        $this->db->WHERE('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->result_array();
    }
}