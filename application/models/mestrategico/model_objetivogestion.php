<?php
class Model_objetivogestion extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
   

    /*---- GET CODIGO OBJETIVOS DE GESTION ----*/
    public function get_cod_objetivosgestion($cod){
        $sql = 'select *
                from objetivo_gestion
                where og_codigo='.$cod.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET PRESUPUESTO OGESTION - GASTO CORRIENTE (NACIONAL) ----*/
    public function get_ppto_ogestion_gc($og_id){
        $sql = 'select opm.og_id,SUM(ppto.ptto) presupuesto
                from _productos p
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join (

                select ipr.prod_id,SUM(ins_costo_total) ptto
                from insumos i
                Inner Join _insumoproducto as ipr On ipr.ins_id=i.ins_id
                group by ipr.prod_id

                ) as ppto On ppto.prod_id=p.prod_id
                                
                where opm.og_id='.$og_id.' and p.estado!=\'3\' and og.g_id='.$this->gestion.' and og.estado!=\'3\'
                group by opm.og_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET PRESUPUESTO OGESTION - GASTO CORRIENTE (REGIONAL) ----*/
    public function get_ppto_ogestion_gc_regional($or_id,$dep_id){
        $sql = 'select p.or_id,opm.dep_id,SUM(ppto.ptto) presupuesto
                from _productos p
                Inner Join _componentes as c On c.com_id=p.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                
                Inner Join (

                select ipr.prod_id,SUM(ins_costo_total) ptto
                from insumos i
                Inner Join _insumoproducto as ipr On ipr.ins_id=i.ins_id
                where i.ins_estado!=3 and i.aper_id!=0
                group by ipr.prod_id

                ) as ppto On ppto.prod_id=p.prod_id
                

                where p.or_id='.$or_id.' and opm.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and c.estado!=\'3\' and pfe.estado!=\'3\' and pfe.pfec_estado=\'1\'
                group by p.or_id,opm.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET PRESUPUESTO OGESTION - PROYECTO DE INVERSION (REGIONAL) ----*/
    public function get_ppto_ogestion_pi_regional($og_id,$dep_id){
        $sql = 'select opm.og_id,opm.dep_id,SUM(ppto.ptto) presupuesto
                from _productos p
                Inner Join _actividades as a On a.prod_id=p.prod_id
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
        
                Inner Join (
                   select ia.act_id,SUM(ins_costo_total) ptto
                   from insumos i
                   Inner Join _insumoactividad as ia On ia.ins_id=i.ins_id
                   group by ia.act_id

                   ) as ppto On ppto.act_id=a.act_id
                                
                where opm.og_id='.$og_id.' and opm.dep_id='.$dep_id.' and p.estado!=\'3\' and a.estado!=\'3\' and og.g_id='.$this->gestion.' and og.estado!=\'3\'
                group by opm.og_id,opm.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


   /*---- LIST OBJETIVOS DE GESTION GENERAL ----*/
    public function list_objetivosgestion_general(){
        $sql = 'select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where og.estado!=\'3\' and og.g_id='.$this->gestion.'
                order by og.og_codigo,og.og_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LIST OBJETIVOS DE GESTION SEGUN ACCION ESTRATEGICO ----*/
    public function list_objetivosgestion($acc_id){
        $sql = 'select *
                from objetivo_gestion oe
                Inner Join indicador as tp On oe.indi_id=tp.indi_id
                where oe.acc_id='.$acc_id.' and oe.g_id='.$this->gestion.' and oe.estado!=\'3\'
                order by oe.og_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET OBJETIVOS DE GESTION --------------*/
    public function get_objetivosgestion($og_id){
        $sql = 'select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where og.og_id='.$og_id.' and og.estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET OBJETIVOS DE GESTION PROGRAMADO--------------*/
    public function get_objetivosgestion_temporalidad($og_id){
        $sql = 'select *
                from v_ogestion_programado
                where og_id='.$og_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---------- GET OBJETIVOS DE GESTION PROGRAMADO MENSUAL --------------*/
    public function get_objetivosgestion_temporalidad_mensual($og_id){
        $sql = 'select *
                from v_ogestion_programado_temporalidad
                where og_id='.$og_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET DEPARTAMENTO --------------*/
    public function get_ogestion_regional($og_id,$dep_id){
        $sql = 'select *
                from objetivo_programado_mensual
                where og_id='.$og_id.' and dep_id='.$dep_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- SUMA PROG. FISICA  TENPORALIDAD--------------*/
    public function get_suma_temporalidad_ogestion($og_id){
        $sql = 'select SUM(prog_fis) meta_relativo
                from objetivo_programado_mensual
                where og_id='.$og_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- LIST TEMPORALIDAD --------------*/
    public function list_temporalidad_regional($og_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join _departamentos as dep On dep.dep_id=opg.dep_id
                where opg.og_id='.$og_id.'
                order by opg.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET TEMPORALIDAD PROGRAMADO REGIONAL --------------*/
    public function get_temporalidad_regional($og_id,$dep_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join _departamentos as dep On dep.dep_id=opg.dep_id
                where opg.og_id='.$og_id.' and opg.dep_id='.$dep_id.'
                order by opg.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET TEMPORALIDAD PROGRAMADO --------------*/
    public function get_objetivo_temporalidad($pog_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join _departamentos as dep On dep.dep_id=opg.dep_id
                Inner Join objetivo_gestion as og On og.og_id=opg.og_id
                where opg.pog_id='.$pog_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET TEMPORALIDAD YA PROGRAMADO OGESTION - OREGIONAL ----*/
    public function get_ogestion_oregional_temporalidad($og_id,$dep_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join _departamentos as dep On dep.dep_id=opg.dep_id
                Inner Join objetivos_regionales as oreg On oreg.pog_id=opg.pog_id
                where opg.og_id='.$og_id.' and opg.dep_id='.$dep_id.'
                order by opg.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*=========== REPORTE OBJETIVO GESTION ===========*/
    /*---- lista Objetivo Regional,Gestion segun su regional ----*/
    public function get_list_ogestion_por_regional($dep_id){
        if($this->gestion==2021){
            $sql = 'select opge.*,oreg.*,oge.*,ae.*,oe.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id

                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                Inner Join _objetivos_estrategicos as oe on oe.obj_id = ae.obj_id

                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and oreg.or_meta!=0
                order by oge.og_codigo,oreg.or_codigo asc';
        }
        else{
            /*$sql = 'select opge.*,oge.*,ae.*,oe.*,oreg.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id

                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                Inner Join _objetivos_estrategicos as oe on oe.obj_id = ae.obj_id

                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and opge.prog_fis!=\'0\' and oreg.or_meta!=0 
                order by oge.og_codigo,oreg.or_codigo asc';*/


                $sql = 'select opge.*,oge.*,ae.*,oe.*,oreg.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id

                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                Inner Join _objetivos_estrategicos as oe on oe.obj_id = ae.obj_id

                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.'
                order by oge.og_codigo,oreg.or_codigo asc';
        }
        
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- Get Objetivo Programado Gestion - Evaluado Trimestral ----*/
    public function get_objetivo_programado_evaluado_trimestral($trimestre,$pog_id){
        $sql = 'select *
                from objetivo_programado_gestion_evaluado
                where trm_id='.$trimestre.' and pog_id='.$pog_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- Get Objetivo Programado Gestion - Evaluado Trimestral Institucional ----*/
    public function get_objetivo_programado_evaluado_trimestral_institucional($trimestre,$og_id){
        $sql = 'select opg.og_id,opge.trm_id, SUM(opge.ejec_fis) evaluado
                from objetivo_programado_mensual opg
                Inner Join objetivo_programado_gestion_evaluado as opge On opg.pog_id=opge.pog_id
                where opge.trm_id='.$trimestre.' and opg.og_id='.$og_id.'
                group by opg.og_id,opge.trm_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*========= ALINEACION DE ACCIONES DE CORTO PLAZO A ACTIVIDADES 2022 =================*/
    /*----- Alineacion de Acciones de Corto Plazo a Actividades-----*/
    public function vinculacion_acp_actividades($og_id){
        $sql = 'select *
                from vista_alineacion_poa_acp
                where g_id='.$this->gestion.' and og_id='.$og_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- Alineacion de Acciones de Corto Plazo a Actividades-----*/
    public function vinculacion_acp_actividades_regional($og_id,$dep_id){
        $sql = 'select *
                from vista_alineacion_poa_acp
                where g_id='.$this->gestion.' and og_id='.$og_id.' and dep_id='.$dep_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- Alineacion de Acciones de Corto Plazo a Actividades Completo-----*/
    public function vinculacion_acp_actividades_nacional_completo($og_id){
        $sql = 'select *
                from vista_alineacion_poa_acp_completo
                where g_id='.$this->gestion.' and og_id='.$og_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- Alineacion de Acciones de Corto Plazo a Actividades completo Regional-----*/
    public function vinculacion_acp_actividades_regional_completo($og_id,$dep_id){
        $sql = 'select *
                from vista_alineacion_poa_acp_completo
                where g_id='.$this->gestion.' and og_id='.$og_id.' and dep_id='.$dep_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- Lista de ACP por regional (para evaluacion de ACP)-----*/
    public function lista_acp_x_regional($dep_id){
        $sql = 'select opge.*,oge.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and oge.estado!=\'3\'
                order by oge.og_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}