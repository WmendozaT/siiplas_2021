<?php
class Model_objetivoregion extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
   
    /* ----- LISTA DE NIEVELES ------*/
    public function list_niveles(){
        $sql = 'select *
                from tipo_nivel
                order by tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* ----- LISTA DE UNIDADES,ESTABLECIMIENTOS POR DISTRITAL ------*/
    public function list_unidades($dist_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where ua.dist_id='.$dist_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                order by te.tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* ----- LISTA DE PROYECTOS DE INVERSION POR REGIONAL (2020) ------*/
    public function list_pinversion($dep_id){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and d.dep_id='.$dep_id.'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* ----- LISTA DE UNIDADES,ESTABLECIMIENTOS POR DISTRITAL Y NIVELES------*/
    public function list_unidades_distrital_niveles($dist_id,$tn_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join aper_establecimiento as aest On aest.te_id=te.te_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=aest.aper_id

                where ua.dist_id='.$dist_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and te.tn_id='.$tn_id.' and aest.g_id='.$this->gestion.'
                order by te.tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /* ----- LISTA TOTALDE DE UNIDADES,ESTABLECIMIENTOS HABILITADOS DE TODOS LOS NIVELES POR REGIONAL------*/
    public function list_unidades_habilitados_regional($dep_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join aper_establecimiento as aest On aest.te_id=te.te_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=aest.aper_id

                where dist.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and aest.g_id='.$this->gestion.'
                order by te.tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- LISTA DE UNIDADES TOTAL SEGUN REGIONAL --------------*/
    public function list_unidades_total($dep_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                where dist.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and te.te_id!=\'0\' and te.te_id!=\'21\'
                order by te.tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* ----- LISTA DE UNIDADES,ESTABLECIMIENTOS POR DISTRITAL SEGUN EL TIPO DE NIVEL------*/
    public function list_unidades_niveles($dep_id,$tn_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                where dist.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and te.tn_id='.$tn_id.'
                order by te.te_id,te.tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*---------- LISTA OBJETIVOS REGIONALES SEGUN EL OBJETIVO DE GESTION Y REGIONAL --------------*/
    public function list_oregional_regional($og_id,$dep_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join objetivo_gestion as og On og.og_id=opg.og_id
                Inner Join objetivos_regionales as oreg On opg.pog_id=oreg.pog_id
                where opg.og_id='.$og_id.' and opg.dep_id='.$dep_id.' and oreg.estado!=\'3\'
                order by oreg.or_codigo,oreg.or_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- LISTA TEMPORALIDAD OREGIONALES ------------*/
    public function get_temporalidad_oregional($or_id,$tn_id){
        $sql = 'select *
                from objetivo_regional_programado orp
                Inner Join unidad_actividad as ua On ua.act_id=orp.act_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join _distritales as dist On ua.dist_id=dist.dist_id
                where orp.or_id='.$or_id.' and orp.g_id='.$this->gestion.' and te.tn_id='.$tn_id.'
                order by te.tn_id,orp.por_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET OBJETIVO REGIONAL --------------*/
    public function get_objetivosregional($or_id){
        $sql = 'select 

                oreg.or_id,
                oreg.or_objetivo,
                oreg.indi_id,
                oreg.or_indicador,
                oreg.or_producto,
                oreg.or_resultado,
                oreg.or_linea_base,
                oreg.or_meta,
                oreg.or_verificacion,
                oreg.g_id,
                oreg.estado,
                oreg.or_codigo,
                oreg.or_meta,
                ogp.pog_id,
                ogp.og_id,
                ogp.dep_id,
                ogp.prog_fis,
                og.og_codigo,
                og.og_objetivo,
                og.og_indicador,
                og.og_producto,
                og.og_resultado,
                og.og_meta,
                og.og_verificacion,
                og.og_unidad,
                og.estado,
                ae.acc_id,
                ae.obj_id,
                ae.acc_descripcion

                from objetivos_regionales oreg
                Inner Join objetivo_programado_mensual as ogp On ogp.pog_id=oreg.pog_id
                Inner Join objetivo_gestion as og On og.og_id=ogp.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                where oreg.or_id='.$or_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- GET UNIDAD PROGRAMADO --------------*/
    public function get_unidad_programado($or_id,$act_id){
        $sql = 'select *
                from objetivo_regional_programado
                where or_id='.$or_id.' and act_id='.$act_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA METAS POR OBJETIVO DE GESTION Y REGIONAL -----*/
    public function sum_meta_oregional($og_id,$dep_id){
        $sql = 'select opg.og_id,opg.dep_id,SUM(oreg.or_meta) meta
                from objetivo_programado_mensual opg
                Inner Join objetivos_regionales as oreg On opg.pog_id=oreg.pog_id
                where opg.og_id='.$og_id.' and opg.dep_id='.$dep_id.' and oreg.estado!=\'3\'
                group by opg.og_id,opg.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA META OBJETIVO REGIONAL -----*/
    public function sum_oregional($or_id){
        $sql = 'select or_id,SUM(prog_fis) suma_meta
                from objetivo_regional_programado
                where or_id='.$or_id.'
                group by or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- LISTA DE ACTIVIDADES EN EL OBJETIVO REGIONAL -----*/
    public function list_actividades_oregional($or_id){
        $sql = 'select *
                from objetivo_regional_programado
                where or_id='.$or_id.'
                order by act_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET OBJETIVO REGIONAL PROGRAMADO -----*/
    public function get_pregional_programado($por_id){
        $sql = 'select *
                from objetivo_regional_programado orp
                Inner Join objetivos_regionales as oreg On oreg.or_id=orp.or_id
                Inner Join objetivo_programado_mensual as ogp On ogp.pog_id=oreg.pog_id
                Inner Join objetivo_gestion as og On og.og_id=ogp.og_id
                where orp.por_id='.$por_id.' and oreg.g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET UNIDAD/ESTABLECIMIENTO EN OBJETIVO REGIONAL (GASTO CORRIENTE) -----*/
    public function get_unidad_pregional_programado($act_id){
        $sql = 'select *
              from objetivo_regional_programado orp
              Inner Join objetivos_regionales as oreg On oreg.or_id=orp.or_id
              Inner Join objetivo_programado_mensual as ogp On ogp.pog_id=oreg.pog_id
              Inner Join objetivo_gestion as og On og.og_id=ogp.og_id
              where orp.act_id='.$act_id.' and oreg.g_id='.$this->gestion.'
              order by og.og_codigo,oreg.or_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET VINCULO PROYECTO - OBJETIVO REGIONAL -----*/
    public function get_proyecto_oregional($proy_id,$por_id){
        $sql = 'select *
                from proy_oregional
                where proy_id='.$proy_id.' and por_id='.$por_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- LISTA VINCULO PROYECTO - OBJETIVO REGIONAL -----*/
    public function list_proyecto_oregional($proy_id){
        $sql = 'select *
                from proy_oregional por
                Inner Join objetivo_regional_programado as orp On orp.por_id=por.por_id
                Inner Join objetivos_regionales as obr On obr.or_id=orp.or_id
                Inner Join objetivo_programado_mensual as opm On obr.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                where por.proy_id='.$proy_id.' and obr.estado!=\'3\' and og.g_id='.$this->gestion.'
                order by og.og_codigo,obr.or_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET VINCULO PROYECTO - OBJETIVO REGIONAL -----*/
    public function get_alineacion_proyecto_oregional($proy_id,$og_codigo,$or_codigo){
        $sql = 'select *
                from proy_oregional por
                Inner Join objetivo_regional_programado as orp On orp.por_id=por.por_id
                Inner Join objetivos_regionales as obr On obr.or_id=orp.or_id
                Inner Join objetivo_programado_mensual as opm On obr.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                where por.proy_id='.$proy_id.' and og.og_codigo='.$og_codigo.' and obr.or_codigo='.$or_codigo.' and obr.estado!=\'3\' and og.g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET DATOS OBJETIVO REGIONAL ATRAVES DE PROG. FIS DE OBJ. GESTION -----*/
     public function get_oregional_por_progfis($pog_id){
        $sql = 'select *
                from objetivo_programado_mensual opg
                Inner Join _departamentos as dep on dep.dep_id = opg.dep_id
                Inner Join objetivos_regionales as obr on obr.pog_id = opg.pog_id
                where opg.pog_id='.$pog_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*========= CONSOLIDADO POA (2020) =================*/
    /*----- GRUPO DE OBJETIVOS REGIONALES -----*/
    public function grupo_objetivos_regionales(){
        $sql = 'select or_codigo
                from objetivos_regionales
                where estado!=\'3\' and g_id='.$this->gestion.'
                group by or_codigo
                order by or_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




/////// EVALUACION DE OBJETIVOS REGIONALES (OPERACIONES) 2022

    /*---- lista Objetivo Regional General ----*/
    public function lista_objetivosregionales_general(){
        $sql = 'select opge.*,oge.*,ae.*,oe.*,oreg.*
                from objetivo_gestion oge
                Inner Join objetivo_programado_mensual as opge on opge.og_id = oge.og_id
                Inner Join objetivos_regionales as oreg on oreg.pog_id = opge.pog_id

                Inner Join _acciones_estrategicas as ae on ae.acc_id = oge.acc_id
                Inner Join _objetivos_estrategicos as oe on oe.obj_id = ae.obj_id

                where oge.g_id='.$this->gestion.'
                order by oge.og_codigo,oreg.or_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- Obtiene suma de metas priorizados (meta acumulado)-----*/
    public function get_suma_meta_form4_x_oregional($or_id){
        $sql = 'select * 
        from vista_suma_meta_form4_x_oregional
        where or_id='.$or_id.' and aper_gestion='.$this->gestion.'';

      //  $query = $this->db->query($sql);

        //// en caso de que no haya la vista
        /*$sql = '
        select p.or_id, SUM(p.prod_meta) as meta_prog_actividades, apg.aper_gestion
        from _productos p
        Inner Join _componentes as c On c.com_id=p.com_id
        Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        where p.or_id='.$or_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and p.prod_priori=\'1\' and apg.aper_estado!=\'3\'
        group by p.or_id,apg.aper_gestion';*/

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*----- Obtiene suma de metas priorizados (meta Recurrentes)-----*/
    public function get_suma_meta_form4_x_oregional_recurrentes($or_id){
        $sql = '
        select pr.or_id, SUM(pr.prod_meta) suma, COUNT(pr.or_id) nro, round((SUM(pr.prod_meta)/COUNT(pr.or_id)),2) meta_prog_actividades
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        where pr.or_id='.$or_id.' and pr.estado!=\'3\' and pr.prod_priori=\'1\' and pr.indi_id=\'2\' and pr.mt_id=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\'
        group by pr.or_id';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*-- verifica si existe registro de temporalidad de Objetivos Regionales --*/
    public function verif_temporalidad_oregional($or_id){
        $sql = 'select *
                from temp_trm_prog_objetivos_regionales
                where or_id='.$or_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*-- Get valor por trimestre Programado Objetivo Regional --*/
    public function get_trm_temporalidad_prog_oregional($or_id,$trm_id){
        $sql = 'select *
                from temp_trm_prog_objetivos_regionales
                where or_id='.$or_id.' and g_id='.$this->gestion.' and trm_id='.$trm_id.'';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /*-- Get valor por trimestre Ejecutado Objetivo Regional --*/
    public function get_trm_temporalidad_ejec_oregional($or_id,$trm_id){
        $sql = 'select *
                from temp_trm_ejec_objetivos_regionales
                where or_id='.$or_id.' and g_id='.$this->gestion.' and trm_id='.$trm_id.'';

        $query = $this->db->query($sql);

        return $query->result_array();
    }



    /*-- Obtiene suma total por trimestre para armar la tenporalidad por Objetivo Regional (Programacion) --*/
    public function get_suma_trimestre_para_oregional($or_id,$trimestre){
        $mes_ini=0; $mes_final=3;

        if($trimestre==2) {
          $mes_ini=3; $mes_final=6;
        }
        elseif ($trimestre==3) {
          $mes_ini=6; $mes_final=9;
        }
        elseif ($trimestre==4) {
          $mes_ini=9; $mes_final=12;
        }


        $sql = '
        select p.or_id,SUM(pprog.trm) trimestre
        from _productos p
        Inner Join _componentes as c On c.com_id=p.com_id
        Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        Inner Join (
            select prod_id, SUM(pg_fis) trm
            from prod_programado_mensual
            where g_id='.$this->gestion.' and (m_id>'.$mes_ini.' and m_id<='.$mes_final.') and pg_fis!=\'0\'
            group by prod_id
        ) as pprog On pprog.prod_id=p.prod_id
        where p.or_id='.$or_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and p.prod_priori=\'1\' and apg.aper_estado!=\'3\'
        group by p.or_id';

        $query = $this->db->query($sql);

        return $query->result_array();
    }


    /*-- Obtiene suma total por trimestre para armar la tenporalidad por Objetivo Regional (Ejecucion) --*/
    public function get_suma_trimestre_ejecucion_oregional($or_id,$trimestre){
        $mes_ini=0; $mes_final=3;

        if($trimestre==2) {
          $mes_ini=3; $mes_final=6;
        }
        elseif ($trimestre==3) {
          $mes_ini=6; $mes_final=9;
        }
        elseif ($trimestre==4) {
          $mes_ini=9; $mes_final=12;
        }


        $sql = '
        select p.or_id,SUM(pprog.trm) trimestre
        from _productos p
        Inner Join _componentes as c On c.com_id=p.com_id
        Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        Inner Join (
            select prod_id, SUM(pejec_fis) trm
            from prod_ejecutado_mensual
            where g_id='.$this->gestion.' and (m_id>'.$mes_ini.' and m_id<='.$mes_final.') and pejec_fis!=\'0\'
            group by prod_id
        ) as pprog On pprog.prod_id=p.prod_id
        where p.or_id='.$or_id.' and apg.aper_gestion='.$this->gestion.' and p.estado!=\'3\' and p.prod_priori=\'1\' and apg.aper_estado!=\'3\'
        group by p.or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- Get Lista de Actividades Priorizados --*/
    public function get_lista_form_priorizados_x_oregional($or_id){
        $sql = '
        select apg.*,p.*,ua.*,te.*,dist.*,tpsa.*,sa.*,pr.*
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
        Inner Join _proyectos as p On p.proy_id=pfe.proy_id
        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
        Inner Join _distritales as dist On dist.dist_id=p.dist_id
        Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
        Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
     
        where pr.or_id='.$or_id.' and apg.aper_gestion='.$this->gestion.' and pr.estado!=\'3\' and pr.prod_priori=\'1\' and apg.aper_estado!=\'3\' and pfe.pfec_estado=\'1\'
        order by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,sa.serv_cod,pr.prod_cod asc';

        $query = $this->db->query($sql);

        return $query->result_array();
    }



}