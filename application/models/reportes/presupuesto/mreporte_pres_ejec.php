<?php

//reporte de presupusto programado
class Mreporte_pres_ejec extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //presupuesto programado y ejecutado del proyecto
    function proy_prog_ejec_directo($proy_id, $gestion)
    {
        $sql = "
        SELECT p.proy_id, p.par_codigo, p.par_nombre, p.ff_codigo, p.of_codigo, p.et_codigo, p.enero,p.febrero, p.marzo, p.abril, p.mayo, p.junio, p.julio, p.agosto,
        p.septiembre, p.octubre, p.noviembre, p.diciembre, e.devengado, e.ppto_inicial, e.ppto_vigente, e.modif_aprobadas, e.ejec1, e.ejec2, e.ejec3, e.ejec4,
        e.ejec5, e.ejec6, e.ejec7, e.ejec8, e.ejec9, e.ejec10, e.ejec11, e.ejec12
        FROM v_presupuesto_programado_directo p
        LEFT JOIN v_presupuesto_ejecutado_por_fin e ON p.proy_id = e.proy_id AND p.par_id = e.par_id AND p.ff_id = e.ff_id
        WHERE p.proy_id = ".$proy_id." AND p.ins_gestion = ".$gestion." AND p.ifin_gestion = ".$gestion."
        AND ( COALESCE(e.gestion ,0) = 0 OR COALESCE(e.gestion ,0) = ".$gestion." )
        ORDER BY p.par_codigo
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //presupuesto programado y ejecutado del proyecto
    function proy_prog_ejec_delegado($proy_id, $gestion)
    {
        $sql = "
        SELECT p.proy_id, p.par_codigo, p.par_nombre, p.ff_codigo, p.of_codigo, p.et_codigo, p.enero,p.febrero, p.marzo, p.abril, p.mayo, p.junio, p.julio, p.agosto,
        p.septiembre, p.octubre, p.noviembre, p.diciembre, e.devengado, e.ppto_inicial, e.ppto_vigente, e.modif_aprobadas, e.ejec1, e.ejec2, e.ejec3, e.ejec4,
        e.ejec5, e.ejec6, e.ejec7, e.ejec8, e.ejec9, e.ejec10, e.ejec11, e.ejec12
        FROM v_presupuesto_programado_delegado p
        LEFT JOIN v_presupuesto_ejecutado_por_fin e ON p.proy_id = e.proy_id AND p.par_id = e.par_id AND p.ff_id = e.ff_id
        WHERE p.proy_id = ".$proy_id." AND p.ins_gestion = ".$gestion." AND p.ifin_gestion = ".$gestion."
        AND ( COALESCE(e.gestion ,0) = 0 OR COALESCE(e.gestion ,0) = ".$gestion." )
        ORDER BY p.par_codigo
        ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}