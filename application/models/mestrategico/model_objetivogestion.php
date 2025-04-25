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

    /*---- GET PRESUPUESTO OBJERTIVO GESTION - GC/PI (NACIONAL) ----*/
    public function get_ppto_ogestion_gc($og_id){
        $sql = 'select og.og_id,SUM(i.ins_costo_total) presupuesto
                from objetivo_gestion og
                Inner Join objetivo_programado_mensual as ogm On ogm.og_id=og.og_id
                Inner Join objetivos_regionales as oreg On oreg.pog_id=ogm.pog_id
                Inner Join _productos as prod On prod.or_id=oreg.or_id

                Inner Join (
                select prod_id,ins_id
                from _insumoproducto
                group by prod_id,ins_id

                ) as ipr On ipr.prod_id=prod.prod_id
                Inner Join insumos as i On i.ins_id=ipr.ins_id
                
                where og.og_id='.$og_id.' and oreg.estado!=\'3\' and oreg.g_id='.$this->gestion.' and prod.estado!=\'3\' and i.aper_id!=\'0\' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.' and i.ins_tipo_modificacion=\'0\'
                group by og.og_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET PRESUPUESTO OGESTION - GASTO CORRIENTE (REGIONAL) ----*/
    public function get_ppto_form2_regional($or_id,$dep_id){
        $sql = 'select  prod.or_id, SUM(i.ins_costo_total) presupuesto
                from _productos prod
                Inner Join (
                select prod_id,ins_id
                from _insumoproducto
                group by prod_id,ins_id

                ) as ipr On ipr.prod_id=prod.prod_id
                Inner Join insumos as i On i.ins_id=ipr.ins_id
              
                where prod.or_id='.$or_id.' and prod.estado!=\'3\' and i.aper_id!=\'0\' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.' and i.ins_tipo_modificacion=\'0\'
                group by prod.or_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


   /*---- LIST OBJETIVOS DE GESTION GENERAL 2020-20021-2022-2023 ordenado por el codigo del Objetivo Estrategico ----*/
    public function list_objetivosgestion_general(){
        if($this->gestion>2023){ /// gestion 2024
             $sql = 'select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=og.oe_id
                where og.estado!=\'3\' and og.g_id='.$this->gestion.'
                order by og.og_codigo asc';
        }
        else{
             $sql = 'select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where og.estado!=\'3\' and og.g_id='.$this->gestion.'
                order by og.og_codigo,og.og_id asc';
        }
       
        $query = $this->db->query($sql);
        return $query->result_array();
    }

   /*---- LIST OBJETIVOS DE GESTION PARA FILTRAR LA SELECCION----*/
    public function list_form1_objetivosgestion($og_id){
        $sql = 'select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                where og.estado!=\'3\' and og.g_id='.$this->gestion.' and og.og_id!='.$og_id.'
                order by og.og_codigo asc';
       
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
        if($this->gestion>2023){
            $sql = '
                select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                where og.og_id='.$og_id.' and og.estado!=\'3\'';
        }
        else{
            $sql = '
            select *
                from objetivo_gestion og
                Inner Join indicador as tp On og.indi_id=tp.indi_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where og.og_id='.$og_id.' and og.estado!=\'3\'';
        }
        
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
                where opg.og_id='.$og_id.' and opg.dep_id='.$dep_id.' and oreg.or_meta!=\'0\'
                order by opg.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*=========== REPORTE OBJETIVO GESTION ===========*/
    /*---- lista Objetivo Regional,Gestion segun su regional (General)----*/
    public function get_list_ogestion_por_regional($dep_id){
         $sql = 'select opge.*,oge.*,oe.*,oreg.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=oge.oe_id
                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\'
                order by oge.og_codigo,oreg.or_codigo asc';
      
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// ------- lista Objetivo Regional,Gestion segun su regional (PRIORIZADO) 2025
    public function get_list_ogestion_por_regional_priorizado($dep_id){
        $sql = 'select opge.*,oge.*,oe.*,oreg.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=oge.oe_id
                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                order by oge.og_codigo,oreg.or_codigo asc';
      
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- Get Datos de Alineacion OR, OG, ACP ----*/
    public function get_alineacion_habilitado_oregional_a_form4($og_codigo,$or_codigo,$dep_id){
        if($this->gestion>2024){ /// 2025
            $sql = 'select opge.dep_id,ae.acc_codigo,oge.og_codigo,oreg.or_codigo,oreg.or_id,oreg.or_objetivo
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id
                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                where opge.dep_id='.$dep_id.' and oge.og_codigo='.$og_codigo.' and oreg.or_codigo='.$or_codigo.' and oge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                order by oge.og_codigo,oreg.or_codigo asc';
        }
        else{
            $sql = 'select opge.dep_id,ae.acc_codigo,oge.og_codigo,oreg.or_codigo,oreg.or_id,oreg.or_objetivo
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id
                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                where opge.dep_id='.$dep_id.' and oge.og_codigo='.$og_codigo.' and oreg.or_codigo='.$or_codigo.' and oge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\'
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
        $sql = 'select opge.*,tp.*,oge.*
                from objetivo_gestion oge
                Inner Join indicador as tp On oge.indi_id=tp.indi_id
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                where opge.dep_id='.$dep_id.' and oge.g_id='.$this->gestion.' and oge.estado!=\'3\'
                order by oge.og_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////LISTA CONSOLIDADO OPERACIONES INSTITUCIONAL
    /*---- lista Form 2 Operaciones institucional ya alineados (total a la gestion)----*/
    public function get_list_ogestion_por_regional_institucional(){
        if($this->gestion>2024){ /// 2025
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id 
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo, oreg.or_codigo asc';
        }
        else{
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id 
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo, oreg.or_codigo asc';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- lista Form 2 Operaciones institucional ya alineados (total al trimestre)----*/
    public function get_list_ogestion_por_regional_institucional_al_trimestre($trimestre){
        if($this->gestion>2024){ /// 2025
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and (temprog.trm_id>\'0\' and temprog.trm_id<='.$trimestre.') and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo, oreg.or_codigo asc';
        }
        else{
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and (temprog.trm_id>\'0\' and temprog.trm_id<='.$trimestre.') and oreg.or_meta!=\'0\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo, oreg.or_codigo asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    ////LISTA CONSOLIDADO ACP INSTITUCIONAL
    /*---- lista Form 1 Acciones de Corto Plazo institucional ya alineados a Operaciones para su evaluacion - Gestion ----*/
    public function get_list_acp_institucional_alineados_a_form2(){
        if($this->gestion>2024){ /// 2025
            $sql = 'select g_id,og_id,og_codigo,og_objetivo,og_indicador,og_producto,og_resultado,SUM(programado_total) programado_total
                from lista_form2_operaciones_alineados_a_form4_priorizados('.$this->gestion.')
                group by g_id,og_id,og_codigo,og_objetivo,og_indicador,og_producto,og_resultado
                order by og_codigo asc';
        }
        else{
            $sql = 'select g_id,og_id,og_codigo,og_objetivo,og_producto,og_resultado,SUM(programado_total) programado_total
                from lista_form2_operaciones_alineados_a_form4('.$this->gestion.')
                group by g_id,og_id,og_codigo,og_objetivo,og_producto,og_resultado
                order by og_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- lista Form 1 Acciones de Corto Plazo institucional ya alineados a Operaciones para su evaluacion - Trimestral ----*/
/*    public function get_list_acp_institucional_alineados_a_form2_trimestral($trimestre){
        $sql = 'select opge.g_id,og.og_id,og.og_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and (temprog.trm_id>\'0\' and temprog.trm_id<='.$trimestre.') 
                group by opge.g_id,og.og_id,og.og_codigo
                order by opge.g_id,og.og_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*---- lista form 2 (operaciones) segun Objetivo de Gestion Institucional al Trimestre----*/
    public function get_list_form2_x_ogestion_trimestral($og_id,$trimestre){
        if($this->gestion>2024){ /// gestion 2025
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where og.og_id='.$og_id.' and oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and (temprog.trm_id>\'0\' and temprog.trm_id<='.$trimestre.') and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo,oreg.or_codigo asc';
        }
        else{
            $sql = 'select opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo,SUM(temprog.pg_fis) programado_total
                from temp_trm_prog_objetivos_regionales temprog
                Inner Join objetivos_regionales as oreg on oreg.or_id = temprog.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where og.og_id='.$og_id.' and oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and (temprog.trm_id>\'0\' and temprog.trm_id<='.$trimestre.') and oreg.or_meta!=\'0\'
                group by opge.g_id,og.og_id,og.og_codigo,oreg.or_codigo
                order by opge.g_id,og.og_codigo,oreg.or_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- lista form 2 (operaciones) segun Objetivo de Gestion Institucional----*/
    public function get_list_form2_x_ogestion($og_id){
        if($this->gestion>2024){ /// gestion 2025
            $sql = 'select og_id,og_codigo, or_codigo,SUM(programado_total) programado_total
                from lista_form2_operaciones_alineados_a_form4_priorizados('.$this->gestion.')
                where og_id='.$og_id.'
                group by og_id,og_codigo, or_codigo
                order by og_codigo, or_codigo asc';
        }
        else{
            $sql = 'select og_id,og_codigo, or_codigo,SUM(programado_total) programado_total
                from lista_form2_operaciones_alineados_a_form4('.$this->gestion.')
                where og_id='.$og_id.'
                group by og_id,og_codigo, or_codigo
                order by og_codigo, or_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- Get valor Ejecutado ACP INSTITUCIONAL--*/
/*    public function get_ejec_acp_institucional_ejecutado($og_id){
        $sql = 'select opge.g_id,og.og_codigo, og.og_id, SUM(temejec.ejec_fis) ejecutado
                from temp_trm_ejec_objetivos_regionales temejec
                Inner Join objetivos_regionales as oreg on oreg.or_id = temejec.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and og.og_id='.$og_id.'
                group by opge.g_id,og.og_codigo, og.og_id
                order by opge.g_id,og.og_codigo, og.og_id asc';

        $query = $this->db->query($sql);

        return $query->result_array();
    }*/

    /*-- Get Suma total Programado Alineado Institucional--*/
    public function get_suma_total_programado_alineado_form1_institucional(){
        $sql = 'select g_id,SUM(programado_total) programado_total
                from lista_form2_operaciones_alineados_a_form4('.$this->gestion.')
                group by g_id';

        $query = $this->db->query($sql);

        return $query->result_array();
    }


    /*-- Get Suma total Ejecutado Alineado Institucional--*/
    public function get_suma_total_ejecutado_alineado_form1_institucional(){
        if($this->gestion>2024){ /// gestion 2025
            $sql = 'select opge.g_id,SUM(temejec.ejec_fis) ejecutado
                from temp_trm_ejec_objetivos_regionales temejec
                Inner Join objetivos_regionales as oreg on oreg.or_id = temejec.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\' and oreg.or_priorizado=\'1\'
                group by opge.g_id';
        }
        else{
            $sql = 'select opge.g_id,SUM(temejec.ejec_fis) ejecutado
                from temp_trm_ejec_objetivos_regionales temejec
                Inner Join objetivos_regionales as oreg on oreg.or_id = temejec.or_id
                Inner Join objetivo_programado_mensual as opge on opge.pog_id = oreg.pog_id
                Inner Join objetivo_gestion as og on og.og_id = opge.og_id
                where oreg.estado!=\'3\' and opge.g_id='.$this->gestion.' and oreg.or_meta!=\'0\'
                group by opge.g_id';
        }
        

        $query = $this->db->query($sql);

        return $query->result_array();
    }

}