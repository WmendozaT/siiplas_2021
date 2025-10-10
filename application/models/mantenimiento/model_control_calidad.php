<?php
class Model_control_calidad extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }
    
    /*--------- Lista de partidas agrupadas 2019 -----------*/
    public function list_partidas(){
        $sql = 'select par.par_id, par.par_codigo, par.par_nombre
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\'
                group by par.par_id, par.par_codigo, par.par_nombre
                order by par.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de regionales-----------*/
    public function regionales(){
        $sql = 'select *
                from _departamentos
                where dep_estado!=\'0\'
                order by dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

        /*--------- get Datos Insumo -----------*/
    public function get_insumo($ins_id){
        $sql = 'select d.dep_departamento,i.ins_unidad_medida,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre
                from insumos i
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                where i.ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos sin partidas, regionales todos-----------*/
    public function list_req_sin_partida_regionales_todos($unidad){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo,i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'%'.$unidad.'%\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos con partidas, regionales todos-----------*/
    public function list_req_con_partida_regionales_todos($unidad,$par_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'%'.$unidad.'%\' and i.par_id='.$par_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos con partidas, regionales seleccionado -----------*/
    public function list_req_con_partida_regionales_select($unidad,$par_id,$dep_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'%'.$unidad.'%\' and i.par_id='.$par_id.' and p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos con unidad , sin partidas, regionales seleccionado -----------*/
    public function list_req_sin_partida_regionales_select($unidad,$dep_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'%'.$unidad.'%\' and p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos sin unidad , con partidas, regionales todos-----------*/
    public function list_req_sin_unidad_con_partida_regionales_todos($par_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id,i.ins_monto_certificado
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.par_id='.$par_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos sin unidad, con partidas, regionales seleccionado -----------*/
    public function list_req_sin_unidad_con_partida_regionales_select($par_id,$dep_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id,i.ins_monto_certificado
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.par_id='.$par_id.' and p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*------------------------------ DETALLE DE CONCEPTO ----------------------------*/
    /*--------- Lista de requerimientos sin partidas, regionales todos-----------*/
    public function list_req_detalle_regionales_todos($concepto){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_detalle like \'%'.$concepto.'%\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de requerimientos con partidas, regionales seleccionado -----------*/
    public function list_req_detalle_regionales_select($concepto,$dep_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_detalle like \'%'.$concepto.'%\' and p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------- Lista de Todos los Requerimientos -----------*/
    public function lista_requerimiento_todos(){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_observacion,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo,i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de Todos las Operaciones -----------*/
    public function lista_operaciones_todos(){
        $sql = 'select dep.dep_departamento,p.proy_nombre,p.tp_id,pfe.pfec_ejecucion,p.proy_act,pr.*,mr.*,prog.*,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join meta_relativo as mr On mr.mt_id=pr.mt_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de requerimientos - unidades de medida nulos por regional -----*/
    public function list_requerimiento_umedida_nulos_regional($dep_id){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'\' and p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de requerimientos - unidades de medida nulos Todos -----*/
    public function list_requerimiento_umedida_nulos_todos(){
        $sql = 'select dep.dep_departamento, i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,par.par_codigo,par.par_nombre,p.proy_nombre,p.tp_id,tp.tp_tipo, i.ins_estado, apg.aper_gestion,i.aper_id,apg.aper_id
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_unidad_medida like \'\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\'
                order by dep.dep_id, apg.aper_programa, apg.aper_proyecto asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}