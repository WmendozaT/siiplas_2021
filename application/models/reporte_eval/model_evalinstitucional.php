<?php
class Model_evalinstitucional extends CI_Model{
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

    ///  GESTION 2020
    public function regiones(){
         $sql = 'select *
                    from _departamentos
                    where dep_id!=\'0\'
                    order by dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// GET DISTRITALES 
    public function get_distritales($dep_id){
         $sql = 'select *
                from _distritales
                where dep_id='.$dep_id.' and dist_estado!=\'0\'
                order by dist_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*====== NACIONAL ======*/
    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) NACIONAL ---*/
    public function list_operaciones_evaluadas_unidad_trimestre_tipo_nacional($trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\'
                order by pt.tprod_id asc';
        }
        else{
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id

                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                order by pt.tprod_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / REGIONAL ------*/
    public function nro_operaciones_programadas_nacional($trimestre,$tp_id){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }
        if($tp_id==1){
            $sql = 'select count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\'';
        }
        else{
            $sql = 'select count(*) total
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id

                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /// ====================

    /*====== REGIONAL ======*/
    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) REGIONAL ---*/
    public function list_operaciones_evaluadas_unidad_trimestre_tipo_regional($dep_id,$trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dep_id='.$dep_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                order by pt.tprod_id asc';
        }
        else{
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dep_id='.$dep_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                order by pt.tprod_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / REGIONAL ------*/
    public function nro_operaciones_programadas_regional($dep_id,$trimestre,$tp_id){
        //// $tp_id : 1 - Proyectos de Inversion
        //// $tp_id : 4 - Gasto Corriente

        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        if($tp_id==1){
            $sql = 'select p.dep_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by p.dep_id';
        }
        else{
            $sql = 'select p.dep_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dep_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD PROGRAMADAS POR TRIMESTRE ----------------------*/
    public function suma_operaciones_programadas_regional($dep_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select p.dep_id,count(*) total, SUM(pprog.suma_programado) suma_programado
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pg_fis) suma_programado
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD EJECUTADA POR TRIMESTRE ----------------------*/
    public function suma_operaciones_ejecutadas_regional($dep_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select p.dep_id,count(*) total, SUM(pejec.suma_ejecutado) suma_evaluado
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pejec_fis) suma_ejecutado
                        from prod_ejecutado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pejec_fis!=\'0\'
                        group by prod_id
                    ) as pejec On pejec.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// ====================

    /*====== DISTRITAL ======*/
    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) DISTRITAL ---*/
    public function list_operaciones_evaluadas_unidad_trimestre_tipo_distrital($dist_id,$trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dist_id='.$dist_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                order by pt.tprod_id asc';
        }
        else{
            $sql = 'select p.*,c.*,pt.*
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dist_id='.$dist_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                order by pt.tprod_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / DISTRITAL ------*/
    public function nro_operaciones_programadas_distrital($dist_id,$trimestre,$tp_id){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        if($tp_id==1){
            $sql = 'select p.dist_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by p.dist_id';
        }
        else{
            $sql = 'select p.dist_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dist_id';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD PROGRAMADAS POR TRIMESTRE ----------------------*/
    public function suma_operaciones_programadas_distrital($dist_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select p.dist_id,count(*) total, SUM(pprog.suma_programado) suma_programado
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pg_fis) suma_programado
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD EJECUTADA POR TRIMESTRE ----------------------*/
    public function suma_operaciones_ejecutadas_distrital($dist_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select p.dist_id,count(*) total, SUM(pejec.suma_ejecutado) suma_evaluado
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pejec_fis) suma_ejecutado
                        from prod_ejecutado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pejec_fis!=\'0\'
                        group by prod_id
                    ) as pejec On pejec.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by p.dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
/////=================













///// ===== Lista de Proyectos de Inversión (Proyecto de Inversion)
    public function list_proyecto_inversion($tp,$id){
        // tp 0: Regional
        // tp 1: Distrital
        if($tp==0){
            $sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where p.dep_id='.$id.' and tp.tp_id=\'1\' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\'
                order by apg.aper_programa,apg.aper_proyecto, apg.aper_actividad asc';
        }
        else{
            $sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where p.dist_id='.$id.' and tp.tp_id=\'1\' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\'
                order by apg.aper_programa,apg.aper_proyecto, apg.aper_actividad asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

///// ===== Lista de Unidades Organizacionales (Gasto Corriente)
    public function list_unidades_organizacionales($tp,$id){
        // tp 0: Regional
        // tp 1: Distrital
        if($tp==0){
            $sql = '
                select * 
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                where p.dep_id='.$id.' and ua.act_estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                ORDER BY p.dist_id,te.te_id,ua.act_id asc';
        }
        else{
            $sql = '
                select * 
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                where p.dist_id='.$id.' and ua.act_estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                ORDER BY p.dist_id,te.te_id,ua.act_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

///// ===== PRESUPUESTO ASIGNADO INSTITUCIONAL
    public function monto_total_programado_trimestre_institucional($tp_id){
        $trimestre=0;
        if($this->tmes==1){
            $trimestre=3;
        }
        elseif ($this->tmes==2) {
            $trimestre=6;
        }
        elseif($this->tmes==3){
            $trimestre=9;
        }
        else{   
            $trimestre=12;
        }
        // tp 1: Proyecto de Inversion
        // tp 4: Gasto Corriente
        if($tp_id==1){
            $sql = '
                select SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') 
                and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\'';

        }
        else{
            $sql = '
                select SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') 
                and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and p.tp_id=\'4\'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


///// ===== PRESUPUESTO ASIGNADO POR REGIONAL
    /*----- Presupuesto total asignado por trimestre por Regional ----*/
    public function monto_total_programado_trimestre_por_regional($tp_id,$dep_id){
        $trimestre=0;
        if($this->tmes==1){
            $trimestre=3;
        }
        elseif ($this->tmes==2) {
            $trimestre=6;
        }
        elseif($this->tmes==3){
            $trimestre=9;
        }
        else{   
            $trimestre=12;
        }

        if($tp_id==1){
            $sql = 'select p.dep_id, SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where p.dep_id='.$dep_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\'
                group by p.dep_id';
        }
        else{
            $sql = 'select p.dep_id, SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where p.dep_id='.$dep_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and p.tp_id=\'4\'
                group by p.dep_id';
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }


    ///// ===== PRESUPUESTO ASIGNADO POR DISTRITAL
    /*----- Presupuesto total asignado por trimestre por Distrital ----*/
    public function monto_total_programado_trimestre_por_distrital($tp_id,$dist_id){
        $trimestre=0;
        if($this->tmes==1){
            $trimestre=3;
        }
        elseif ($this->tmes==2) {
            $trimestre=6;
        }
        elseif($this->tmes==3){
            $trimestre=9;
        }
        else{   
            $trimestre=12;
        }

        if($tp_id==1){
            $sql = 'select p.dist_id, SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where p.dist_id='.$dist_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\'
                group by p.dist_id';
        }
        else{
            $sql = 'select p.dist_id, SUM(t.ipm_fis) ppto_programado
                from insumos i

                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                                
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id  
                where p.dist_id='.$dist_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and t.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and p.tp_id=\'4\'
                group by p.dist_id';
        }

        
        $query = $this->db->query($sql);

        return $query->result_array();
    }


/////// PROYECTOS DE INVERSIÓN

    /*----- Lista de Proyectos por Departamento ----*/
    public function list_proyectos_departamento($dep_id){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and p.dep_id='.$dep_id.' and apg.aper_proy_estado=\'4\' and apg.aper_estado!=\'3\'
                ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';        
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*----- Lista de Proyectos por Distrital ----*/
    public function list_proyectos_distrital($dist_id){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and p.dist_id='.$dist_id.' and apg.aper_proy_estado=\'4\' and apg.aper_estado!=\'3\'
                ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';        
        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
