<?php
class Model_evaluacion extends CI_Model{
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
    

    /*------- DATOS MES --------*/
    public function get_mes($m_id){
        $sql = 'select *
                from mes
                where m_id='.$m_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- DEPARTAMENTO - DISTRITAL --------*/
    public function get_componente($com_id){
        $sql = 'select *
                from vista_componentes_dictamen
                where com_id='.$com_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- VERIF EVALUACION DE OPERACIONES NO CUMPLIDAS POR TRIMESTRE --*/
    public function verif_com_eval($com_id,$trimestre){
        $sql = 'select *
                from eval_comp
                where com_id='.$com_id.' and trm_id='.$trimestre.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- DEPARTAMENTO - DISTRITAL --------*/
    public function dep_dist($dist_id){
        $sql = 'select *
                from _distritales ds
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where ds.dist_id='.$dist_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- TRIMESTRE VIGENTE ----------------------*/
    public function trimestre(){
        $sql = 'select *
                from trimestre_mes
                where trm_id='.$this->tmes.' and estado!=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET TRIMESTRE VIGENTE ----------------------*/
    public function get_trimestre($trm_id){
        $sql = 'select *
                from trimestre_mes
                where trm_id='.$trm_id.' and estado!=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- PROGRAMACION TRIMESTRAL -------------------------*/
    public function programado_trimestral_productos($trimestre,$prod_id){
        $inicio=0;$final=0;
        if($trimestre==1){
            $inicio=0;$final=3;
        }
        elseif($trimestre==2){
            $inicio=3;$final=6;
        }
        elseif($trimestre==3){
            $inicio=6;$final=9;
        }
        elseif($trimestre==4){
            $inicio=9;$final=12;   
        }

    $sql = 'select prod_id,(CASE WHEN sum(pg_fis)!=0 THEN sum(pg_fis) ELSE 0 END) as trimestre, g_id
            from prod_programado_mensual
            where prod_id='.$prod_id.' and (m_id>'.$inicio.' and m_id<='.$final.') and g_id='.$this->gestion.'
            GROUP BY prod_id,g_id';
    $query = $this->db->query($sql);
    return $query->result_array();
    }

    /*-------------------- EJECUTADO TRIMESTRAL -------------------------*/
    public function ejecutado_trimestral_productos($trimestre,$prod_id){
        $inicio=0;$final=0;
        if($trimestre==1){
            $inicio=0;$final=3;
        }
        elseif($trimestre==2){
            $inicio=3;$final=6;
        }
        elseif($trimestre==3){
            $inicio=6;$final=9;
        }
        elseif($trimestre==4){
            $inicio=9;$final=12;   
        }

    $sql = 'select prod_id,(CASE WHEN sum(pejec_fis)!=0 THEN sum(pejec_fis) ELSE 0 END) as trimestre, g_id
            from prod_ejecutado_mensual
            where prod_id='.$prod_id.' and (m_id>'.$inicio.' and m_id<='.$final.') and g_id='.$this->gestion.'
            GROUP BY prod_id,g_id';
    $query = $this->db->query($sql);
    return $query->result_array();
    }

    /*------------- Rango Trimestre para los valores pendientes por trimestre -----------------*/
    public function rango_programado_trimestral_productos($prod_id,$rf){
        $sql = 'select prod_id,(CASE WHEN sum(pg_fis)!=0 THEN sum(pg_fis) ELSE 0 END) as trimestre, g_id
                from prod_programado_mensual
                where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$rf.') and g_id='.$this->gestion.'
                GROUP BY prod_id,g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- Rango Trimestre para los valores pendientes Ejecutado por trimestre -----------------*/
    public function rango_ejecutado_trimestral_productos($prod_id,$rf){
    $sql = 'select prod_id,(CASE WHEN sum(pejec_fis)!=0 THEN sum(pejec_fis) ELSE 0 END) as trimestre, g_id
            from prod_ejecutado_mensual
            where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$rf.') and g_id='.$this->gestion.'
            GROUP BY prod_id,g_id';
    $query = $this->db->query($sql);
    return $query->result_array();
    }


    /*--- Get Meta Mensual Programado Producto ---*/
    public function get_meta_mensual_programado_operacion($prod_id,$mes_id){
        $sql = 'select prod_id,pg_fis as meta_mensual, g_id
                from prod_programado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Get Meta Mensual Ejecutado Producto ---*/
    public function get_meta_mensual_ejecutado_operacion($prod_id,$mes_id){
        $sql = 'select prod_id,pejec_fis as meta_mensual, g_id,observacion,medio_verificacion,acciones
                from prod_ejecutado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Get Meta Mensual No Ejecutado Producto ---*/
    public function get_meta_mensual_no_ejecutado_operacion($prod_id,$mes_id){
        $sql = 'select *
                from prod_no_ejecutado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---------------------GET PRODUCTO TRIMESTRE ---------------------*/
    public function get_producto_trimestre($tprod_id,$gestion,$trimestre){
        $sql = ' select *
                 from _productos_trimestral ptr
                 Inner Join trimestre_mes as tmes On tmes.trm_id=ptr.trm_id
                where ptr.tprod_id='.$tprod_id.' and ptr.g_id='.$gestion.' and ptr.trm_id='.$trimestre.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET PRODUCTO TRIMESTRE EVALUADO ---------------------*/
    public function get_trimestral_prod($prod_id,$gestion,$trimestre){
        $sql = 'select ptr.*,te.*,fun.*
                from _productos_trimestral ptr
                Inner Join funcionario as fun On fun.fun_id=ptr.fun_id
                Inner Join trimestre_mes as tmes On tmes.trm_id=ptr.trm_id
                Inner Join tipo_evaluacion as te On te.tpeval_id=ptr.tp_eval
                where ptr.prod_id='.$prod_id.' and ptr.g_id='.$gestion.' and ptr.trm_id='.$trimestre.' and ptr.testado!=\'3\' and ptr.activo=\'1\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET PRODUCTO TRIMESTRE EVALUADO ULTIMO ---------------------*/
    public function get_trimestral_prod_ultimo($prod_id,$gestion,$trimestre){
        $sql = 'select *
                from _productos_trimestral ptr
                Inner Join trimestre_mes as tmes On tmes.trm_id=ptr.trm_id
                Inner Join tipo_evaluacion as te On te.tpeval_id=ptr.tp_eval
                where ptr.prod_id='.$prod_id.' and ptr.g_id='.$gestion.' and ptr.trm_id='.$trimestre.' and ptr.testado!=\'3\' and ptr.activo=\'0\'
                order by ptr.tprod_id desc limit \'1\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- DELETE TEMPORALIZACION EJECUTADO ----------------------*/
    public function delete_ejec_temporalizacion($vi,$vf,$prod_id,$gestion){
        for ($i=$vi; $i <=$vf ; $i++) { 
            $this->db->where('prod_id', $prod_id);
            $this->db->where('m_id', $i);
            $this->db->where('g_id', $gestion);
            $this->db->delete('prod_ejecutado_mensual');
        }
    }

    /*--------------------- DELETE PRODUCTO TRIMESTRE ----------------------*/
    public function delete_prod_trimestre($prod_id,$trimestre,$gestion){
        $this->db->where('prod_id', $prod_id);
        $this->db->where('trm_id', $trimestre);
        $this->db->where('g_id', $gestion);
        $this->db->delete('_productos_trimestral');
    }


    /*--------------------- MESES ----------------------*/
    public function mes(){
        $sql = 'select *
                from mes
                order by m_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

   /*================================== EVALUACION PEI ===============================*/
    /*------ Lista de Operaciones Alienados a una Accion Estrategica ------*/
    public function list_operaciones_alineados($acc_id){
        $sql = 'select *
                from _acciones_estrategicas ae
                Inner Join _productos as prod On prod.acc_id=ae.ae
                Inner Join indicador as tp On prod.indi_id=tp.indi_id
                Inner Join _componentes as com On com.com_id=prod.com_id
                Inner Join _proyectofaseetapacomponente as pfec On pfec.pfec_id=com.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfec.proy_id
                Inner Join _tipoproyecto as tproy On p.tp_id=tproy.tp_id
                Inner Join _departamentos as d On p.dep_id=d.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.acc_id='.$acc_id.' and ae.acc_estado!=\'3\' and prod.estado!=\'3\' and pfec.pfec_estado=\'1\' 
                and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\'
                order by prod.prod_priori,apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de Operaciones Alienados a una Accion Estrategica ------*/
    public function list_operaciones_alineados_con_prioridad($acc_id,$prioridad){
        $sql = 'select *
                from _acciones_estrategicas ae
                Inner Join _productos as prod On prod.acc_id=ae.ae
                Inner Join indicador as tp On prod.indi_id=tp.indi_id
                Inner Join _componentes as com On com.com_id=prod.com_id
                Inner Join _proyectofaseetapacomponente as pfec On pfec.pfec_id=com.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfec.proy_id
                Inner Join _departamentos as d On p.dep_id=d.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.acc_id='.$acc_id.' and ae.acc_estado!=\'3\' and prod.estado!=\'3\' and pfec.pfec_estado=\'1\' and p.estado!=\'3\' 
                and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and prod.prod_priori='.$prioridad.'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de Operaciones total de Prioridad y no prioridad por regional ------*/
    public function list_operaciones_total_regional($dep_id){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and pfe.pfec_ejecucion=\'1\' 
                and pfe.pfec_estado!=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_proyecto asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de Operaciones de Prioridad y no prioridad por regional ------*/
    public function list_operaciones_tp_regional($dep_id,$tp){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join indicador as indi On pr.indi_id=indi.indi_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and pfe.pfec_ejecucion=\'1\' 
                and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and pr.prod_priori='.$tp.' and pfe.pfec_estado=\'1\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_proyecto asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ Lista de Operaciones por regional -------*/
    public function list_operaciones_regional($tp_id,$dep_id){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join indicador as indi On pr.indi_id=indi.indi_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and pfe.pfec_ejecucion=\'1\' 
                and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and p.tp_id='.$tp_id.' and pfe.pfec_estado=\'1\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_proyecto asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================================== EVALUACION PEI - REGIONAL ===============================*/
    /*------ Lista de Operaciones Alienados a una Accion Estrategica por regional ------*/
    public function list_operaciones_alineados_regional($acc_id,$dep_id){
        $sql = 'select *
                from _acciones_estrategicas ae
                Inner Join _productos as prod On prod.acc_id=ae.ae
                Inner Join indicador as tp On prod.indi_id=tp.indi_id
                Inner Join _componentes as com On com.com_id=prod.com_id
                Inner Join _proyectofaseetapacomponente as pfec On pfec.pfec_id=com.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfec.proy_id
                Inner Join _tipoproyecto as tproy On p.tp_id=tproy.tp_id
                Inner Join _departamentos as d On p.dep_id=d.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.acc_id='.$acc_id.' and ae.acc_estado!=\'3\' and prod.estado!=\'3\' and pfec.pfec_estado=\'1\' and p.estado!=\'3\' 
                and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.dep_id='.$dep_id.'
                order by prod.prod_priori,apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de Operaciones Alienados a una Accion Estrategica por Regional ---*/
    public function list_operaciones_alineados_prioridad_regional($acc_id,$dep_id,$prioridad){
        $sql = 'select *
                from _acciones_estrategicas ae
                Inner Join _productos as prod On prod.acc_id=ae.ae
                Inner Join indicador as tp On prod.indi_id=tp.indi_id
                Inner Join _componentes as com On com.com_id=prod.com_id
                Inner Join _proyectofaseetapacomponente as pfec On pfec.pfec_id=com.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfec.proy_id
                Inner Join _departamentos as d On p.dep_id=d.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.acc_id='.$acc_id.' and ae.acc_estado!=\'3\' and prod.estado!=\'3\' and pfec.pfec_estado=\'1\' 
                and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and prod.prod_priori='.$prioridad.' and p.dep_id='.$dep_id.'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///  GESTION 2020
    /*--- LISTA OPERACIONES EVALUADAS ---*/
    public function list_operaciones_evaluadas_servicio_trimestre($com_id,$trimestre){
        $sql = 'select pt.*
                from _componentes c
                Inner Join _productos as p On p.com_id=c.com_id
                Inner Join _productos_trimestral as pt On p.prod_id=pt.prod_id
                where c.com_id='.$com_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.'
                order by tprod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS)---*/
    public function list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$trimestre,$tipo_eval){
        $sql = 'select pt.*
                from _componentes c
                Inner Join _productos as p On p.com_id=c.com_id
                Inner Join _productos_trimestral as pt On p.prod_id=pt.prod_id
                where c.com_id='.$com_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.'
                order by tprod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE ----------------------*/
    public function nro_operaciones_programadas($com_id,$trimestre){
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

        $sql = 'select c.com_id,count(*) total
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where c.com_id='.$com_id.' and prod.estado!=\'3\'
                group by c.com_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD PROGRAMADAS POR TRIMESTRE ----------------------*/
    public function suma_operaciones_programadas($com_id,$trimestre){
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

        $sql = 'select c.com_id,count(*) total, SUM(pprog.suma_programado) suma_programado
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pg_fis) suma_programado
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where c.com_id='.$com_id.' and prod.estado!=\'3\'
                group by c.com_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD EJECUTADA POR TRIMESTRE ----------------------*/
    public function suma_operaciones_ejecutadas($com_id,$trimestre){
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

        $sql = 'select c.com_id,count(*) total, SUM(pejec.suma_ejecutado) suma_evaluado
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pejec_fis) suma_ejecutado
                        from prod_ejecutado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pejec_fis!=\'0\'
                        group by prod_id
                    ) as pejec On pejec.prod_id=prod.prod_id
                where c.com_id='.$com_id.'
                group by c.com_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- SUMA MESES TEMPORALIDAD PROGRAMADO DE PRODUCTOS POR SERVICIO ---*/
    public function sum_temporalidad_prod_programado_servicio($com_id){
        $sql = 'select
                p.com_id,
                SUM(prog.enero) mes1, 
                SUM(prog.febrero) mes2, 
                SUM(prog.marzo) mes3, 
                SUM(prog.abril) mes4, 
                SUM(prog.mayo) mes5, 
                SUM(prog.junio) mes6,
                SUM(prog.julio) mes7,
                SUM(prog.agosto) mes8,
                SUM(prog.septiembre) mes9,
                SUM(prog.octubre) mes10,
                SUM(prog.noviembre) mes11,
                SUM(prog.diciembre) mes12,
                prog.g_id

                from _productos as p
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and prog.g_id='.$this->gestion.'
                group by p.com_id,prog.g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- SUMA MESES TEMPORALIDAD EJECUTADO DE PRODUCTOS POR SERVICIO ---*/
    public function sum_temporalidad_prod_ejecutado_servicio($com_id){
        $sql = 'select 
                p.com_id,
                SUM(ejec.enero) mes1, 
                SUM(ejec.febrero) mes2, 
                SUM(ejec.marzo) mes3, 
                SUM(ejec.abril) mes4, 
                SUM(ejec.mayo) mes5, 
                SUM(ejec.junio) mes6,
                SUM(ejec.julio) mes7,
                SUM(ejec.agosto) mes8,
                SUM(ejec.septiembre) mes9,
                SUM(ejec.octubre) mes10,
                SUM(ejec.noviembre) mes11,
                SUM(ejec.diciembre) mes12,
                ejec.g_id
                from _productos as p
                Inner Join vista_productos_temporalizacion_ejecutado_dictamen as ejec On ejec.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and ejec.g_id='.$this->gestion.'
                group by p.com_id,ejec.g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////// EVALUACION OBJETIVOS REGIONALES

    /*--- GET EVALUACION META TRIMESTRAL - OBJETIVO REGIONAL ---*/
    public function get_meta_oregional($pog_id,$trimestre){
        $sql = 'select *
                from objetivo_programado_gestion_evaluado ope
                Inner Join objetivo_programado_mensual as op On op.pog_id=ope.pog_id
                Inner Join trimestre_mes as tr On tr.trm_id=ope.trm_id
                Inner Join tipo_evaluacion as te On te.tpeval_id=ope.tp_eval
                where ope.pog_id='.$pog_id.' and ope.trm_id='.$trimestre.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- GET EVALUACION META TRIMESTRAL - OBJETIVO REGIONAL ---*/
    public function get_evaluacion_meta_oregional($epog_id){
        $sql = 'select *
                from objetivo_programado_gestion_evaluado ope
                Inner Join objetivo_programado_mensual as op On op.pog_id=ope.pog_id
                Inner Join trimestre_mes as tr On tr.trm_id=ope.trm_id
                Inner Join tipo_evaluacion as te On te.tpeval_id=ope.tp_eval
                where ope.epog_id='.$epog_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- GET ULTIMO REGISTRO DE EVALUACION ---*/
    public function get_ultimo_eval_oregional($pog_id){
        $sql = 'select * 
                from objetivo_programado_gestion_evaluado ope
                Inner Join trimestre_mes as tr On tr.trm_id=ope.trm_id
                Inner Join tipo_evaluacion as te On te.tpeval_id=ope.tp_eval
                where pog_id='.$pog_id.' 
                order by ope.trm_id DESC LIMIT 1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////// EJECUCION PRESUPUESTARIA POR SERVICIO
    
    /*----- Presupuesto Total por trimestre - Gasto Corriente ----*/
    public function suma_ppto_programado_trimestre($com_id){
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

        $sql = '
                select prod.com_id,SUM(t.ipm_fis) total_ppto
                from _productos prod
                Inner Join _insumoproducto as iprod On iprod.prod_id=prod.prod_id
                Inner Join insumos as i On iprod.ins_id=i.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id
                where prod.com_id='.$com_id.' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                group by prod.com_id';
        $query = $this->db->query($sql);

        return $query->result_array();
    }


    /*----- Suma monto certificado por servicio al trimestre vigente - Gasto Corriente ----*/
    public function suma_grupo_partida_programado($com_id,$grupo_partida){
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

        $sql = '
                select prod.com_id,SUM(t.ipm_fis) suma_partida
                from _productos prod
                Inner Join _insumoproducto as iprod On iprod.prod_id=prod.prod_id
                Inner Join insumos as i On iprod.ins_id=i.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join temporalidad_prog_insumo as t On t.ins_id=i.ins_id
                where prod.com_id='.$com_id.' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and  par.par_depende=\''.$grupo_partida.'\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                group by prod.com_id';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    
    /*----- Suma monto certificado por servicio al trimestre vigente - Gasto Corriente ----*/
    public function suma_monto_certificado_servicio($com_id){
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

        $sql = 'select cpoa.com_id,SUM(t.ipm_fis) ppto_certificado
                from certificacionpoa cpoa
                Inner Join certificacionpoadetalle as cpoad On cpoad.cpoa_id=cpoa.cpoa_id
                Inner Join insumos as i On i.ins_id=cpoad.ins_id
                Inner Join partidas as par On par.par_id=i.par_id

                Inner Join cert_temporalidad_prog_insumo as ct On ct.cpoad_id=cpoad.cpoad_id
                Inner Join temporalidad_prog_insumo as t On t.tins_id=ct.tins_id


                where cpoa.com_id='.$com_id.' and (t.mes_id>\'0\' and t.mes_id<='.$trimestre.') and t.g_id='.$this->gestion.' and cpoa.cpoa_estado!=\'3\' and par.par_depende!=\'10000\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                group by cpoa.com_id';
                
        $query = $this->db->query($sql);
        return $query->result_array();
    }









    ////// NUMERO DE CERTIFICACIONES REALIZADAS POR TRIMESTRE
    
    /*----- Numero de Certificaciones por trimestre - Gasto Corriente ----*/
    public function nro_certificaciones_trimestre($com_id,$trimestre){

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

        $sql = '
                select cpoa.com_id,count(cpoa.cpoa_id) numero_certificaciones
                from certificacionpoa cpoa
                Inner Join (

                select cpoa_id,extract(day from (cpoa_fecha))as dia, extract(month from (cpoa_fecha))as mes, extract(years from (cpoa_fecha))as gestion 
                from certificacionpoa
                where cpoa_estado!=3

                ) as fecha_cpoa On fecha_cpoa.cpoa_id=cpoa.cpoa_id
             where cpoa.com_id='.$com_id.' and cpoa.cpoa_estado!=\'3\' and (fecha_cpoa.mes>='.$vi.' and fecha_cpoa.mes<='.$vf.')
             group by cpoa.com_id';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

}
