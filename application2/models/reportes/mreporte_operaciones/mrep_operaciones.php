<?php
class Mrep_operaciones extends CI_Model {
    public function __construct(){
        $this->load->database();
    }

    public function regiones(){
         $sql = 'select *
                    from _departamentos
                    where dep_id!=\'0\'
                    order by dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ Lista Establecimientos de Salud 2021 ------*/
    public function establecimientos_salud_distrital($dist_id){
         $sql = 'select *
                from vlista_establecimientos_salud es
                Inner Join _proyectos as p On p.act_id=es.act_id
                Inner Join aperturaproyectos as pp On pp.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pp.aper_id
                where es.dist_id='.$dist_id.' and es.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista Establecimientos de Salud 2021 ------*/
    public function establecimientos_salud_regional($dep_id){
         $sql = 'select *
                from vlista_establecimientos_salud es
                Inner Join _proyectos as p On p.act_id=es.act_id
                Inner Join aperturaproyectos as pp On pp.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pp.aper_id
                where es.dep_id='.$dep_id.' and es.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.'
                order by es.dist_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ LISTA DE UNIDADES/PROYECTOS POR DISTRITAL 2020-2021 ------*/
    public function list_unidades($dist_id,$tp_id){
        if($tp_id==1){ /// Proyecto de Inversion
            $sql = 'select * from lista_poa_pinversion_distrital('.$dist_id.','.$this->gestion.')';
        }
        else{
            $sql = 'select * from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ LISTA DE UNIDADES/PROYECTOS POR REGIONAL 2020-2021 ------*/
    public function list_poa_gacorriente_pinversion_regional($dep_id,$tp_id){
        if($tp_id==1){ /// Proyecto de Inversion
            $sql = 'select * from lista_poa_pinversion_regional('.$dep_id.','.$this->gestion.')';
        }
        else{
            $sql = 'select * from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ LISTA DE POA (GASTO CORRIENTE / PROYECTO DE INVERSION - NACIONAL) 2020-2021 ------*/
    public function list_poa_gastocorriente_pinversion($tp_id){
        if($tp_id==1){ /// Proyecto de Inversion
            $sql = 'select * from lista_poa_pinversion_nacional('.$this->gestion.')';
        }
        else{
            $sql = 'select * from lista_poa_gastocorriente_nacional('.$this->gestion.')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------- OPERACIONES POR SERVICIO --------*/
    public function operaciones_por_servicio($com_id){
         $sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
    
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where c.com_id='.$com_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\' 
                order by apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, p.tp_id, c.com_id, pr.prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- OPERACIONES POR UNIDADES --------*/
    public function operaciones_por_unidades($proy_id){
        $sql = '
                select *
                from lista_poa_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                where poa.proy='.$proy_id.' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by poa.da,poa.prog,poa.act, poa.proy_id,c.com_id,pr.prod_id asc';

        /*$sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where p.proy_id='.$proy_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\' 
                order by apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, p.tp_id, c.com_id, pr.prod_cod asc';*/

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- OPERACIONES POR DISTRITALES 2020-2021--------*/
    public function operaciones_por_distritales($dist_id,$tp_id){
        $sql = '
                select *
                from lista_poa_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                where poa.dist_id='.$dist_id.' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by poa.da,poa.prog,poa.act, poa.proy_id,c.com_id,pr.prod_id asc';

        /*$sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join servicios_actividad as serv On serv.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact

                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as vpt On vpt.prod_id=pr.prod_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
    
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where ds.dist_id='.$dist_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\' and p.tp_id='.$tp_id.' and vpt.g_id='.$this->gestion.'
                order by p.tp_id, apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, p.tp_id, c.com_id, pr.prod_cod asc';*/

        $query = $this->db->query($sql);
        return $query->result_array();
    }


        /*------ OPERACIONES (formulario N4) POR REGIONALES -------*/
        public function consolidado_operaciones_regionales($dep_id,$tp_id){
            $sql = '
                select *
                from lista_poa_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                where poa.dep_id='.$dep_id.' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by poa.da,poa.prog,poa.act, poa.proy_id,c.com_id,pr.prod_id asc';
            $query = $this->db->query($sql);
            return $query->result_array();

        /*$sql = '
                select d.*,ds.*,apg.*,p.*,ua.*,te.*,ser.*,tpsa.*,c.*,pr.*,vpt.*,ore.*,og.*,ae.*
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pf.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as vpt On vpt.prod_id=pr.prod_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
    
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and p.tp_id='.$tp_id.' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\'
                order by p.dep_id,p.dist_id,apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, ser.serv_cod, pr.prod_cod asc';
         
        */
    }


    /*---------------- OPERACIONES CONSOLIDADO NACIONAL ----------------*/
    public function formulario_N4_institucional(){
        $sql = '
                select *
                from lista_poa_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                where c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by poa.da,poa.prog,poa.act, poa.proy_id,c.com_id,pr.prod_id asc';
         
        $query = $this->db->query($sql);
        return $query->result_array();


/*        $sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join servicios_actividad as ser On c.serv_id=ser.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
    
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where p.estado!=\'3\' and apg.aper_estado!=\'3\' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by p.tp_id, apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, p.tp_id, c.com_id, pr.prod_cod asc';
         
        $query = $this->db->query($sql);
        return $query->result_array();*/
    }

    /*---------------- OPERACIONES CONSOLIDADO NACIONAL SEGUN TIPO ----------------*/
    public function operaciones_consolidado_nacional_tipo($tp_id){
        $sql = '
                select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _componentes as c On c.pfec_id=pf.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id

                Inner Join objetivos_regionales as ore On ore.or_id=pr.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
    
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                where p.estado!=\'3\' and apg.aper_estado!=\'3\' and p.tp_id='.$tp_id.' and pf.pfec_estado=\'1\' and c.estado!=\'3\' and pr.estado!=\'3\' and prog.g_id='.$this->gestion.'
                order by p.dist_id, apg.aper_programa,apg.aper_proyecto, apg.aper_actividad, c.com_id, pr.prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------------- NRO DE OPERACIONES POR ACCIONES ----------------*/
    public function nro_operaciones_acciones($acc_id,$proy_id){
         $sql = '
                select p.proy_id,pr.acc_id,count(*) total
                from _proyectos p
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where pr.acc_id='.$acc_id.' and p.proy_id='.$proy_id.' and p.estado!=\'3\' and p.tp_id!=\'1\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\'
                group by p.proy_id,pr.acc_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- NUEVO OBJETIVOS ESTRATEGICOS POR PROYECTOS total por objetivos------------------*/
        public function list_obj_vinculados($proy_id){
         $sql = '
                select p.proy_id,ae.obj_id,oe.obj_codigo,oe.obj_descripcion,count(ae.obj_id)
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\'
                group by p.proy_id,ae.obj_id,oe.obj_codigo,oe.obj_descripcion
                order by oe.obj_codigo';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

    /*-------------------- NUEVO LISTA OBJETIVOS ESTRATEGICOS POR PROYECTOS VINCULADOS A OPERACIONES------------------*/
        public function list_obj_vinculados_ope($obj_id,$proy_id){
         $sql = '
                select p.proy_id,ae.obj_id, ae.acc_id,ae.acc_codigo,ae.ae,ae.acc_descripcion,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\'
                group by p.proy_id,ae.obj_id, ae.acc_id,ae.acc_codigo,ae.ae,ae.acc_descripcion
                order by ae.obj_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-------------------- TOTAL OPERACIONES POR ACTIVIDAD------------------*/
        public function total_operaciones_act($proy_id){
         $sql = '
                select p.proy_id,count(ae.obj_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\'
                group by p.proy_id';

        $query = $this->db->query($sql);
        return $query->result_array();
        }


        /*-------------------- NUMERO DE OPERACIONES ALINEADOS POR OBJETIVOS ESTRATEGICOS ------------------*/
        public function total_ope_vinculados_oe_por_actividad($proy_id){
         $sql = '
                select p.proy_id,ae.obj_id,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\'
                group by p.proy_id,ae.obj_id
                order by ae.obj_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-------------------- NUMERO DE OPERACIONES ALINEADOS POR OBJETIVOS ESTRATEGICOS ------------------*/
        public function get_ope_vinculados_oe_por_actividad($proy_id,$obj_id){
         $sql = '
                select p.proy_id,ae.obj_id,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\'
                group by p.proy_id,ae.obj_id
                order by ae.obj_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*================ POR REGIONAL ==============*/

        /*-------------------- LISTA AGRUPADO POR OBJETIVOS ESTRATEGICOS DE REGIONALES ------------------*/
        public function list_nro_ope_oe_regional($dep_id){
         $sql = '
                select p.dep_id,ae.obj_id,oe.obj_codigo,oe.obj_descripcion,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and p.tp_id=\'4\' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\'
                group by p.dep_id,ae.obj_id,oe.obj_codigo,oe.obj_descripcion
                order by ae.obj_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        public function get_nro_ope_oe_regional($dep_id,$obj_id){
         $sql = '
                select p.dep_id,ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion ,ae.acc_id,ae.acc_codigo,ae.ae,ae.acc_descripcion,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join fn_pdes(ae.pdes_id) as pdes On pdes.id_pdes=ae.pdes_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and p.tp_id=\'4\' and ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\'
                group by p.dep_id,ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion,ae.acc_id,ae.acc_codigo,ae.ae,ae.acc_descripcion
                order by ae.acc_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-------- monto total de presupuesto por regional y objetivo estrategico ----*/
        public function get_monto_ope_oe_regional($dep_id,$obj_id){
         $sql = '
                select p.dep_id,ae.obj_id,SUM(i.ins_costo_total) as monto 
                from _productos pr
                Inner Join _insumoproducto as ip On ip.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'
                group by p.dep_id,ae.obj_id';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-- total operaciones vinculadas por regional*/
        public function total_ope_oe_regional($dep_id){
         $sql = '
                select p.dep_id,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'
                group by p.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-- total monto requerimientos por regional*/
        public function monto_total_ope_oe_regional($dep_id){
         $sql = '
                select p.dep_id,SUM(i.ins_costo_total) as monto 
                from _productos pr
                Inner Join _insumoproducto as ip On ip.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and i.ins_estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'
                group by p.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
        }
        /*--------------------------------------------------------------------------------------------------*/

        /*================ INTITUCIONAL ==============*/
        /*-------------------- LISTA AGRUPADO POR OBJETIVOS ESTRATEGICOS A NIVEL INSITUCIONAL ------------------*/
        public function list_nro_ope_oe_institucional(){
         $sql = '
                select ae.obj_id,oe.obj_codigo,oe.obj_descripcion,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where  pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' 
                and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'
                group by ae.obj_id,oe.obj_codigo,oe.obj_descripcion
                order by oe.obj_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*------------ GET AGRUPADO POR OBJETIVOS ESTRATEGICOS A NIVEL INSITUCIONAL ------------*/
        public function get_nro_ope_oe_institucional($obj_id){
         $sql = '
                select ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion,ae.acc_id,ae.acc_codigo,ae.ae,ae.pdes_id,ae.acc_descripcion,count(pr.prod_id) total, ae.rf_id
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join fn_pdes(ae.pdes_id) as pdes On pdes.id_pdes=ae.pdes_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' 
                and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\'
                group by ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion,ae.acc_id,ae.acc_codigo,ae.ae,ae.acc_descripcion, ae.rf_id
                order by ae.acc_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*------------ GET AGRUPADO POR OBJETIVOS ESTRATEGICOS A NIVEL INSITUCIONAL ------------*/
        public function get_nro_ope_poa_institucional($obj_id){
         $sql = '
                select ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion,ae.acc_id,ae.acc_codigo,ae.acc_descripcion,pt.rm_resultado,
                pt.ptm_indicador,pt.ptm_linea_base,pt.ptm_meta,pt.ptm_meta,pt.mes1,pt.mes2, pt.mes3, pt.mes4,pt.mes5,pt.ptm_id,pt.ptm_codigo, count(pr.prod_id) total_ope
                from _productos pr
                Inner Join vindicadores as pt On pr.indi_pei=pt.ptm_id

                Inner Join _acciones_estrategicas as ae On ae.acc_id=pt.acc_id
                Inner Join fn_pdes(ae.pdes_id) as pdes On pdes.id_pdes=ae.pdes_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                                                
                where ae.obj_id='.$obj_id.' and pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' 
                      and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\'
                group by ae.obj_id,pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pdes.meta,pdes.cod_resultado,pdes.resultado,pdes.cod_accion,pdes.accion,ae.acc_id,ae.acc_codigo,ae.acc_descripcion,pt.rm_resultado,
                pt.ptm_indicador,pt.ptm_linea_base,pt.ptm_meta,pt.ptm_meta,pt.mes1,pt.mes2, pt.mes3, pt.mes4,pt.mes5,pt.ptm_id,pt.ptm_codigo

                order by pdes.cod_pilar,pdes.pilar,pdes.cod_meta,pt.ptm_codigo  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*------------ SUMA PRESUPUESTO SEGUN INDICADOR PEI ------------*/
        public function suma_monto_indicador_poa($ptm_id){
         $sql = '
                select pr.indi_pei,SUM(ig.insg_monto_prog) monto
                from _productos pr
                Inner Join _insumoproducto as ip On ip.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where pr.indi_pei='.$ptm_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\' and i.aper_id!=\'0\'
                group by pr.indi_pei';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-------------------- MONTO AGRUPADO POR OBJETIVOS ESTRATEGICOS A NIVEL INSITUCIONAL ------------------*/
        public function get_monto_ope_oe_institucional($obj_id){
         $sql = '
                select ae.obj_id,SUM(i.ins_costo_total) as monto 
                from _productos pr
                Inner Join _insumoproducto as ip On ip.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ae.obj_id='.$this->gestion.' and pr.estado!=\'3\' and c.estado!=\'3\' and i.ins_estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'
                group by ae.obj_id';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*-------------------- TOTAL OBJETIVOS ESTRATEGICOS A NIVEL INSITUCIONAL ------------------*/
        public function total_nro_ope_oe_institucional(){
         $sql = '
                select count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' 
                and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and p.tp_id=\'4\'';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*=== GRUPO DE PARTIDAS ===*/
        /*--------- CUADRO COMPARATIVO POR GRUPO DE PARTIDAS -----*/
        public function list_grupo_partidas(){
         $sql = '
                select *
                from partidas
                where par_depende=\'0\' and par_id!=\'0\'
                order by par_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
        }

        /*--------- OBTIENE MONTO POR PARTIDA (REGIONAL) (Antiguo)-----*/
/*        public function get_monto_partida($dep_id,$grupo,$tp){
            if($tp==1){
                $sql = '
                    select p.dep_id,par.par_depende,SUM(pg.importe) as monto
                      from ptto_partidas_sigep pg
                      Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id
                      Inner Join _proyectos as p On p.proy_id=ap.proy_id
                      Inner Join partidas as par On par.par_id=pg.par_id
                      where p.dep_id='.$dep_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and par.par_depende='.$grupo.'
                      group by p.dep_id,par.par_depende
                      order by par.par_depende asc';
            }
            else{
                $sql = '
                    select p.dep_id,par.par_depende,SUM(ip.programado_total) as monto
                      from insumos i
                      Inner Join partidas as par On par.par_id=i.par_id
                      Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                      Inner Join _proyectos as p On p.proy_id=ap.proy_id
                      JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                      JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                      where p.dep_id='.$dep_id.' and ig.g_id='.$this->gestion.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and par.par_depende='.$grupo.'
                      group by p.dep_id,par.par_depende
                      order by par.par_depende asc';
            }

        $query = $this->db->query($sql);
        return $query->result_array();
        }
*/


    /*========= EVALUACION ==========*/
    /*------ Evaluacion por Unidades (Productos)-------*/
    public function evaluacion_proyecto($proy_id,$teval,$trimestre,$acc){
        $sql = 'select vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as total
                from vproducto vp
                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                from _productos_trimestral pt
                where testado!=\'3\'
                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                where vp.proy_id='.$proy_id.' and tprod.g_id='.$this->gestion.' and tprod.trm_id='.$trimestre.' and tprod.tp_eval='.$teval.' and vp.acc_id='.$acc.'
                group by vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Total Tareas Evaluacion por Unidades (Productos)-------*/
    public function total_programado_accion($proy_id,$trimestre,$acc){
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

        $sql = 'select vprod.proy_id,vprod.acc_id,count(pprog.prod_id) as total
               from vproducto vprod
               Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id
               
               where vprod.proy_id='.$proy_id.' and vprod.acc_id='.$acc.'
               group by vprod.proy_id,vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- Proyecto - tipo de evaluacion por proyecto trimestral (Actividades)---------*/
    public function evaluacion_proyecto_actividad($proy_id,$teval,$trimestre,$acc){
        $sql = 'select vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,vp.acc_id,count(*) as total
                from vproducto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
               
                Inner Join (select act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                from _actividad_trimestral pt
                where testado!=\'3\'
                group by act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.act_id=va.act_id
                where vp.proy_id='.$proy_id.' and tprod.g_id='.$this->gestion.' and tprod.trm_id='.$trimestre.' and tprod.tp_eval='.$teval.' and vp.acc_id='.$acc.'
                group by vp.proy_id,tprod.tp_eval,tprod.g_id,vp.acc_id,tprod.trm_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ programa - tipo de evaluacion por distrital trimestral (Actividades) ---------*/
    public function total_programado_accion_actividad($proy_id,$trimestre,$acc){
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

        $sql = 'select vprod.proy_id,vprod.acc_id,count(*) as total
               from vproducto vprod
               Inner Join vista_actividad as va On va.prod_id=vprod.prod_id
               Inner Join (
                    select act_id
                    from act_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by act_id
                ) as pprog On pprog.act_id=va.act_id
               
               where vprod.proy_id='.$proy_id.' and vprod.acc_id='.$acc.'
               group by vprod.proy_id,vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*======== EVALUACION A NIVEL DE OBJETIVOS ESTRATEGICOS =======*/
    /*----- Objetivo Estrategico - evaluacion acumulado trimestral (productos) -----*/
    public function list_obj_estrategicos_evaluados(){
        $sql = 'select ae.obj_id,oe.obj_codigo,oe.obj_descripcion,count(pr.prod_id) total
                from _productos pr
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join fn_pdes(ae.pdes_id) as pdes On pdes.id_pdes=ae.pdes_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _componentes as c On c.com_id=pr.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where pr.estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_ejecucion=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and p.tp_id=\'4\'
                and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and oe.obj_estado!=\'3\' and ae.acc_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\'
                group by ae.obj_id,oe.obj_codigo,oe.obj_descripcion
                order by ae.obj_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- Objetivo Estrategico - evaluacion acumulado trimestral (productos) -----*/
    public function evaluacion_oe_nacional($oe,$teval,$trimestre){
        $sql = 'select obj.obj_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vproducto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id
        
                Inner Join _acciones_estrategicas as ae On ae.ae=ev.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
        
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and p.dep_id!=\'0\' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ total programado por Obj. Estrategico Nacional (Producto) --------*/
    public function total_programado_oe_nacional($oe,$trimestre){
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

        $sql = 'select obj.obj_id,count(pprog.prod_id) total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id

                Inner Join _acciones_estrategicas as ae On ae.ae=vprod.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
        
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                and obj.obj_id='.$oe.' and p.dep_id!=\'0\' and p.dist_id!=\'0\'
                GROUP BY obj.obj_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Obj. Estrategico - tipo de evaluacion Nacional trimestral (Actividad)----------*/
    public function evaluacion_oe_nacional_actividad($oe,$teval,$trimestre){
        $sql = 'select obj.obj_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (
                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                from vproducto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
                Inner Join (
                    select act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    from _actividad_trimestral pt
                    where testado!=\'3\'
                    group by act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    ) as tprod On tprod.act_id=va.act_id
                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id
                ) as ev On ev.proy_id=p.proy_id

                Inner Join _acciones_estrategicas as ae On ae.ae=ev.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
           
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.'  and ev.tp_eval='.$teval.' and p.dep_id!=\'0\' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id,ev.tp_eval';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- total programado por Obj. Estrategico Nacional (Actividad) ----*/
    public function total_programado_oe_nacional_actividad($oe,$trimestre){
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

        $sql = 'select obj.obj_id,count(pprog.act_id) total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               
                Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join vista_actividad as vact On vprod.prod_id=vact.prod_id
                Inner Join (
                    select act_id
                    from act_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by act_id
                ) as pprog On pprog.act_id=vact.act_id

                Inner Join _acciones_estrategicas as ae On ae.ae=vprod.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
           
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                and p.dep_id!=\'0\' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*======= REGIONAL OBJ. EST. =======*/
    /*----- Objetivo Estrategico - evaluacion acumulado trimestral Regional (productos) -----*/
    public function evaluacion_oe_regional($oe,$dep_id,$teval,$trimestre){
        $sql = 'select obj.obj_id,p.dep_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vproducto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id
        
                Inner Join _acciones_estrategicas as ae On ae.ae=ev.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
        
                where p.estado!=\'3\' and p.tp_id=\'4\'  and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and p.dep_id='.$dep_id.' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id,p.dep_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ total programado por Obj. Estrategico Regional (Producto) --------*/
    public function total_programado_oe_regional($oe,$dep_id,$trimestre){
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

        $sql = 'select obj.obj_id,p.dep_id,count(pprog.prod_id) total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id

                Inner Join _acciones_estrategicas as ae On ae.ae=vprod.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
        
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                and obj.obj_id='.$oe.' and p.dep_id='.$dep_id.' and p.dist_id!=\'0\'
                GROUP BY obj.obj_id,p.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Obj. Estrategico - tipo de evaluacion Regional trimestral (Actividad)----------*/
    public function evaluacion_oe_regional_actividad($oe,$dep_id,$teval,$trimestre){
        $sql = 'select obj.obj_id,p.dep_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (
                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                from vproducto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
                Inner Join (
                    select act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    from _actividad_trimestral pt
                    where testado!=\'3\'
                    group by act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    ) as tprod On tprod.act_id=va.act_id
                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id
                ) as ev On ev.proy_id=p.proy_id

                Inner Join _acciones_estrategicas as ae On ae.ae=ev.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
           
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.'  and ev.tp_eval='.$teval.' and p.dep_id='.$dep_id.' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id,p.dep_id,ev.tp_eval';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- total programado por Obj. Estrategico Regional (Actividad) ----*/
    public function total_programado_oe_regional_actividad($oe,$dep_id,$trimestre){
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

        $sql = 'select obj.obj_id,p.dep_id,count(pprog.act_id) total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               
                Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join vista_actividad as vact On vprod.prod_id=vact.prod_id
                Inner Join (
                    select act_id
                    from act_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by act_id
                ) as pprog On pprog.act_id=vact.act_id

                Inner Join _acciones_estrategicas as ae On ae.ae=vprod.acc_id
                Inner Join _objetivos_estrategicos as obj On obj.obj_id=ae.obj_id
           
                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                and p.dep_id='.$dep_id.' and p.dist_id!=\'0\' and obj.obj_id='.$oe.'
                GROUP BY obj.obj_id,p.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*======== EVALUACION A NIVEL DE ACCION DE CORTO PLAZO =======*/
    /*------ A. Corto Plazo - tipo de evaluacion Nacional trimestral (Productos) --------*/
    public function evaluacion_acplazo_nacional($ae,$teval,$trimestre){
        $sql = 'select ev.acc_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vproducto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and p.dep_id!=\'0\' and p.dist_id!=\'0\' and ev.acc_id='.$ae.'
                GROUP BY ev.acc_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ total programado por A.Corto Plazo Nacional (Producto) --------*/
    public function total_programado_acplazo_nacional($ae,$trimestre){
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

        $sql = 'select vprod.acc_id,count(pprog.prod_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id
               where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and vprod.acc_id='.$ae.' and p.dep_id!=\'0\' and p.dist_id!=\'0\'
               GROUP BY vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- A.Corto Plazo - tipo de evaluacion Nacional trimestral (Actividad)----------*/
    public function evaluacion_acplazo_nacional_actividad($ae,$teval,$trimestre){
        $sql = 'select ev.acc_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (
                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                from vproducto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
                Inner Join (
                    select act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    from _actividad_trimestral pt
                    where testado!=\'3\'
                    group by act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    ) as tprod On tprod.act_id=va.act_id
                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id
                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.'  and ev.tp_eval='.$teval.' and p.dep_id!=\'0\' and p.dist_id!=\'0\' and ev.acc_id='.$ae.'
                GROUP BY ev.acc_id,ev.tp_eval';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- total programado por ACortoPlazo Nacional (Actividad) ----------------------*/
    public function total_programado_acplazo_nacional_nacional_actividad($ae,$trimestre){
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

        $sql = 'select vprod.acc_id,count(pprog.act_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               
               Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
               Inner Join vista_actividad as vact On vprod.prod_id=vact.prod_id
               Inner Join (
                    select act_id
                    from act_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by act_id
                ) as pprog On pprog.act_id=vact.act_id
                
               where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.'and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and p.dep_id!=\'0\' and p.dist_id!=\'0\' and vprod.acc_id='.$ae.'
               GROUP BY vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- A.cortoPlazo - tipo de evaluacion por Regional trimestral (Productos)-----------*/
    public function evaluacion_acplazo_regional($dep_id,$ae,$teval,$trimestre){
        $sql = 'select p.dep_id,ev.acc_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vproducto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and p.dep_id='.$dep_id.' and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and p.dist_id!=\'0\' and ev.acc_id='.$ae.'
                GROUP BY p.dep_id,ev.acc_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----------- total programado por A.CP por regional (Producto) --------------*/
    public function total_programado_acplazo_regional($dep_id,$ae,$trimestre){
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

        $sql = 'select p.dep_id,vprod.acc_id,count(pprog.prod_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id
               where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and p.dep_id='.$dep_id.' and p.tp_id=\'4\' and p.dist_id!=\'0\' and vprod.acc_id='.$ae.'
               GROUP BY p.dep_id,vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----------- Acplazo - tipo de evaluacion por regional trimestral (Actividad)---------------*/
    public function evaluacion_acplazo_regional_actividad($dep_id,$ae,$teval,$trimestre){
        $sql = 'select p.dep_id,ev.acc_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (
                select vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                from vproducto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
                Inner Join (
                    select act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    from _actividad_trimestral pt
                    where testado!=\'3\'
                    group by act_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                    ) as tprod On tprod.act_id=va.act_id
                group by vp.proy_id,vp.acc_id,tprod.tp_eval,tprod.g_id,tprod.trm_id
                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and p.dep_id='.$dep_id.' and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and ev.acc_id='.$ae.'
                GROUP BY p.dep_id,ev.acc_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- total programado por Acplazo por Regional (Actividad) ----------------------*/
    public function total_programado_acplazo_regional_actividad($dep_id,$ae,$trimestre){
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

        $sql = 'select p.dep_id,vprod.acc_id,count(pprog.act_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               
               Inner Join vproducto as vprod On vprod.proy_id=p.proy_id
               Inner Join vista_actividad as vact On vprod.prod_id=vact.prod_id
               Inner Join (
                    select act_id
                    from act_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by act_id
                ) as pprog On pprog.act_id=vact.act_id
                
               where p.estado!=\'3\' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and p.dep_id='.$dep_id.' and vprod.acc_id='.$ae.'
               GROUP BY p.dep_id,vprod.acc_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--- LISTA DE OPERACIONES PARA LISTA DE REQ. CERTIFICADOS POR REGIONAL ---*/
    public function list_req_cert_regional($dep_id,$tp_id){
        // tp_id=0 : Todos
        // tp_id=1 : Proy Inv, 2 : Gasto Corriente
        if($tp_id==0){
            $sql = '
                select *
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _distritales as dis On dis.dist_id=p.dist_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id = p.proy_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_proyecto, p.dist_id asc';
        }
        else{
            $sql = '
                select *
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _distritales as dis On dis.dist_id=p.dist_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id = p.proy_id
                Inner Join _componentes as c On c.pfec_id = pfe.pfec_id
                where p.tp_id='.$tp_id.' and p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and aper_estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_proyecto, p.dist_id,c.com_id asc';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ///// -- LISTA DE UNIDADES / PROYECTOS DE INVERSION 2020-2021
    public function lista_unidad_pinversion_regional_distrital($tp_regional,$id_regional,$tp_id){
        /// tp_regional -> 0 : Regional, 1 : Distrital
        /// id_regional -> dep_id, dist_id
        /// tp_id -> 1 : Proyecto de Inversion, 4 : Gasto Corriente

        if($tp_regional==0){
            $dep_id=$id_regional;
            if($tp_id==1){
                $sql = 'select * from lista_poa_pinversion_regional('.$dep_id.','.$this->gestion.')';
            }
            else{
                $sql = 'select * from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.')';
            }
        }
        else{
            $dist_id=$id_regional;
            if($tp_id==1){
                $sql = 'select * from lista_poa_pinversion_distrital('.$dist_id.','.$this->gestion.')';
            }
            else{
                $sql = 'select * from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.')';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ///// -- CONSOLIDADO FORMULARIO 5 (2023) LISTADO DIRECTO APER_ID = INS_ID (REPORTES POA VISTA)
    public function consolidado_requerimientos_regional_distrital_directo($tp_regional, $id, $tp_id){
        /// tp_regional -> 0 : Regional, 1 : Distrital
        /// id_regional -> dep_id, dist_id
        /// tp_id -> 1 : Proyecto de Inversion, 4 : Gasto Corriente

        if($tp_regional==0){ /// Regional
            $sql = '
            select *
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where dep_id='.$id.'';
        }
        else{ /// Distrital
            $sql = '
            select *
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where dist_id='.$id.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    ///// -- CONSOLIDADO COMPLETO FORMULARIO 5 (INSTITUCIONAL) 2023
    public function consolidado_poa_formulario5_institucional($tp_id){
        if($tp_id==1){
            $sql = 'select *
            from lista_requerimientos_institucional_pinversion('.$this->gestion.')';
        }
        else{
            $sql = '
            select *
            from lista_requerimientos_institucional_gcorriente('.$this->gestion.')';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///// -- CONSOLIDADO COMPLETO FORMULARIO 5 (REGIONAL) 2023
    public function consolidado_poa_formulario5_regional($dep_id,$tp_id){
        if($tp_id==1){
            $sql = 'select  *
            from lista_requerimientos_institucional_pinversion('.$this->gestion.')
            where dep_id='.$dep_id.'';
        }
        else{
            $sql = 'select  *
            from lista_requerimientos_institucional_gcorriente('.$this->gestion.')
            where dep_id='.$dep_id.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///// -- CONSOLIDADO COMPLETO FORMULARIO 5 (DISTRITAL) 2023
    public function consolidado_poa_formulario5_distrital($dist_id,$tp_id){
        if($tp_id==1){
            $sql = 'select  *
            from lista_requerimientos_institucional_pinversion('.$this->gestion.')
            where dist_id='.$dist_id.'';
        }
        else{
            $sql = '
            select  *
            from lista_requerimientos_institucional_gcorriente('.$this->gestion.')
            where dist_id='.$dist_id.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ///// -- LISTA INSUMO POR SUBACTIVIDAD (2020-2021)
    public function consolidado_poa_formulario5_componente($com_id,$tp_id){
        $sql = '
            select *
            from lista_requerimientos_institucional_gcorriente('.$this->gestion.')
            where com_id='.$com_id.'
            order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    ///// -- INSUMO CERTIFICADO (2020-2021)
    public function monto_certificado($ins_id){
         $sql = 'select *
                from vmonto_certificado_insumo
                where ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


}