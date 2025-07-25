<?php
class Model_insumo extends CI_Model{
    var $gestion;
    var $fun_id;

    public function __construct(){
        $this->load->database();
        
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
    }

    // ------ Lista Insumos Todos 
    public function lista_insumos($gestion){
        $sql = 'select apg.*,p.*,dep.*,dist.*,i.*,ua.*,te.*
                from vlista_insumos i

                Inner Join aperturaprogramatica as apg on apg.aper_id = i.aper_id
                Inner Join aperturaproyectos as ap on ap.aper_id = apg.aper_id
                Inner Join _proyectos as p on p.proy_id = ap.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _departamentos as dep on dep.dep_id = p.dep_id
                Inner Join _distritales as dist on dist.dist_id = p.dist_id
                where apg.aper_gestion='.$gestion.' and i.par_id=137
                order by p.dep_id, p.dist_id, apg.aper_programa,apg.aper_proyecto,apg.aper_actividad, i.ins_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ lista Programado ejecutado por partidas Regional
    public function lista_consolidado_ejecucion_partidas($dep_id){
        $sql = 'select dep_id,par_codigo,SUM(ins_costo_total) programado,SUM(ins_monto_certificado) certificado, round(((SUM(ins_monto_certificado)/SUM(ins_costo_total))*100),2) 
                from lista_requerimientos_institucional_directo(4,'.$this->gestion.')
                where dep_id='.$dep_id.'
                group by dep_id,par_codigo
                order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ lista Programacion Financiera
    public function lista_prog_fin($ins_id){
        $sql = 'select *
                from temporalidad_prog_insumo
                where ins_id='.$ins_id.'
                order by mes_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ lista Programacion Financiera INICIAL TOTAL - 2023 INVERSION
    public function temporalidad_inicial_total_unidad($proy_id){
        $sql = 'select *
                from temporalidad_inicial_total_insumo
                where proy_id='.$proy_id.'
                order by mes_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ lista Programacion Insumos Certificados (Nuevo)
    public function lista_prog_fin_certificado($ins_id){
        $sql = 'select ins_id, SUM(ipm_fis) monto_certificado
                from temporalidad_prog_insumo
                where ins_id='.$ins_id.' and estado_cert=\'1\'
                group by ins_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_partida_codigo($par_codigo){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_codigo', $par_codigo);
        $query = $this->db->get();
        return $query->result_array();
    }

    //LISTA PARTIDAS DEPENDIENTES
    function lista_par_dependientes($par_codigo){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_depende', $par_codigo);
        //$this->db->WHERE('par_gestion',$this->gestion);
        $this->db->ORDER_BY('par_codigo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }


    // ------ lista Temporalidad Insumo
    public function list_temporalidad_insumo($ins_id){
        $sql = 'select *
            from vista_temporalidad_insumo
            where ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ GET Temporalidad Insumo por partida por unidad/proyecto 
    public function get_list_temporalidad_insumo_partida_programado($aper_id,$par_id){
        $sql = 'select i.aper_id,i.ins_id,i.par_id,SUM(temp.mes1) mes1,SUM(temp.mes2) mes2,SUM(temp.mes3) mes3,SUM(temp.mes4) mes4,SUM(temp.mes5) mes5,SUM(temp.mes6) mes6,SUM(temp.mes7) mes7,SUM(temp.mes8) mes8,SUM(temp.mes9) mes9,SUM(temp.mes10) mes10,SUM(temp.mes11) mes11,SUM(temp.mes12) mes12
                from insumos i
                Inner Join vista_temporalidad_insumo as temp On temp.ins_id=i.ins_id
                where i.aper_id='.$aper_id.' and i.par_id='.$par_id.' 
                group by i.aper_id,i.ins_id,i.par_id
                order by i.ins_id,i.par_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ GET Temporalidad por partida por unidad/proyecto 
    public function get_list_temporalidad_partida_programado($aper_id,$par_id){
        $sql = 'select i.aper_id,i.par_id,SUM(temp.mes1) mes1,SUM(temp.mes2) mes2,SUM(temp.mes3) mes3,SUM(temp.mes4) mes4,SUM(temp.mes5) mes5,SUM(temp.mes6) mes6,SUM(temp.mes7) mes7,SUM(temp.mes8) mes8,SUM(temp.mes9) mes9,SUM(temp.mes10) mes10,SUM(temp.mes11) mes11,SUM(temp.mes12) mes12
                from insumos i
                Inner Join vista_temporalidad_insumo as temp On temp.ins_id=i.ins_id
                where i.aper_id='.$aper_id.' and i.par_id='.$par_id.' 
                group by i.aper_id,i.par_id
                order by i.par_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ GET Temporalidad Insumo por MES por unidad/proyecto 
    public function get_list_temporalidad_insumo_mes_programado($aper_id,$mes_id){
        $sql = 'select i.aper_id,SUM(ipm_fis) ppto
                from insumos i
                Inner Join temporalidad_prog_insumo as temp On temp.ins_id=i.ins_id
                where aper_id='.$aper_id.' and temp.mes_id='.$mes_id.' and i.ins_gestion='.$this->gestion.'
                group by i.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ GET Temporalidad Insumo por PARTIDA MES por unidad/proyecto 
    public function get_list_temporalidad_insumo_partida_mes_programado($aper_id,$par_id,$mes_id){
        $sql = 'select i.aper_id,i.par_id,SUM(ipm_fis) ppto
                from insumos i
                Inner Join temporalidad_prog_insumo as temp On temp.ins_id=i.ins_id
                where aper_id='.$aper_id.' and par_id='.$par_id.' and temp.mes_id='.$mes_id.' and i.ins_gestion='.$this->gestion.'
                group by i.aper_id,i.par_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///================= TEMPORALIDAD INSUMO
    // ------ lista Temporalidad Insumo (ppto actual) por UNIDAD / PROYECTO
    public function list_temporalidad_programado_unidad($aper_id){
        $sql = '
            select *
            from vista_temporalidad_form5_unidad
            where aper_id='.$aper_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad formulario 5 (ppto actual) DISTRITAL
    public function temporalidad_programado_form5_distrital($dist_id){
        $sql = '
            select pi.dist_id,SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_form5_unidad temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id
            where pi.dist_id='.$dist_id.'
            group by pi.dist_id
            order by pi.dist_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad formulario 5 (ppto actual) REGIONAL
    public function temporalidad_programado_form5_regional($dep_id){
        $sql = '
            select pi.dep_id,SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_form5_unidad temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id
            where pi.dep_id='.$dep_id.'
            group by pi.dep_id
            order by pi.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad formulario 5 (ppto actual) REGIONAL INSTITUCIONAL
    public function temporalidad_programado_form5_institucional(){
        $sql = '
            select SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_form5_unidad temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    ///=====================================








    ///=================== TEMPORALIDAD INICIAL======
    // ------ Temporalidad Inicial PROYECTO DE INVERSION
    public function temporalidad_inicial_pinversion($aper_id){
        $sql = '
            select *
            from vista_temporalidad_inicial_form5_x_proyecto
            where aper_id='.$aper_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad Inicial Proyectos de Inversion DISTRITAL
    public function temporalidad_inicial_pinversion_distrital($dist_id){
        $sql = '
            select pi.dist_id,SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_inicial_form5_x_proyecto temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id
            where pi.dist_id='.$dist_id.'
            group by pi.dist_id
            order by pi.dist_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad Inicial Proyectos de Inversion REGIONAL
    public function temporalidad_inicial_pinversion_regional($dep_id){
        $sql = '
            select pi.dep_id,SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_inicial_form5_x_proyecto temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id
            where pi.dep_id='.$dep_id.'
            group by pi.dep_id
            order by pi.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Temporalidad Inicial Proyectos de Inversion INSTITUCIONAL
    public function temporalidad_inicial_pinversion_institucional(){
        $sql = '
            select SUM(temp.programado_total) programado_total,SUM(mes1) mes1,SUM(mes2) mes2,SUM(mes3) mes3,SUM(mes4) mes4,SUM(mes5) mes5,SUM(mes6) mes6,SUM(mes7) mes7,SUM(mes8) mes8,SUM(mes9) mes9,SUM(mes10) mes10,SUM(mes11) mes11,SUM(mes12) mes12
            from vista_temporalidad_inicial_form5_x_proyecto temp
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as pi On pi.aper_id=temp.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ Techo presupuestario (ppto inicial) Inversion X REGIONAL
    public function techo_ppto_inicial_inversion_regional($or_id){
        $sql = '
        select pr.or_id,SUM(temp.temp_fis) techo_ppto_inicial
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
        Inner Join temporalidad_inicial_total_insumo as temp On temp.aper_id=proy.aper_id
        where pr.or_id='.$or_id.' and pr.estado!=\'3\' and pr.prod_priori=\'1\'
        group by pr.or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Techo presupuestario (ppto actual) Inversion X REGIONAL
    public function techo_ppto_actual_inversion_regional($or_id){
        $sql = '
        select pr.or_id,SUM(temp.ipm_fis) techo_ppto_actual
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join _insumoproducto as insp On insp.prod_id=pr.prod_id
        Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
        Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
        where pr.or_id='.$or_id.' and pr.estado!=\'3\' and pr.prod_priori=\'1\'
        group by pr.or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




    // ------ Ppto (inicial) Inversion X REGIONAL al Trimestre
    public function ppto_inicial_inversion_regional_trimestre($or_id,$i){
        $sql = '
        select pr.or_id,SUM(temp.temp_fis) ppto_inicial_trimestre
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
        Inner Join temporalidad_inicial_total_insumo as temp On temp.aper_id=proy.aper_id
        where pr.or_id='.$or_id.' and (temp.mes_id>\'0\' and temp.mes_id<='.($i*3).') and pr.estado!=\'3\' and pr.prod_priori=\'1\'
        group by pr.or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Ppto (Actual) Inversion X REGIONAL al Trimestre
    public function ppto_actual_inversion_regional_trimestre($or_id,$i){
        $sql = '
        select pr.or_id,SUM(temp.ipm_fis) ppto_actual_trimestre
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join _insumoproducto as insp On insp.prod_id=pr.prod_id
        Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
        Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
        where pr.or_id='.$or_id.' and (temp.mes_id>\'0\' and temp.mes_id<='.($i*3).') and pr.estado!=\'3\' and pr.prod_priori=\'1\'
        group by pr.or_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ///================================================

    // ------ lista Unidad de Medida
    public function list_unidadmedida(){
        $sql = 'select *
                from insumo_unidadmedida
                where um_estado!=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ get Unidad de Medida
    public function get_unidadmedida($um_id){
        $sql = 'select *
                from insumo_unidadmedida
                where um_id='.$um_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Verifica Unidad de medida con respecto a partidas
    public function verif_partida_umedida($par_id,$um_id){
        $sql = 'select *
                from par_umedida
                where par_id='.$par_id.' and um_id='.$um_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Lista de Unidades de medida seleccionado
    public function lista_umedida($par_id){
        $sql = 'select *
                from par_umedida pum
                Inner Join insumo_unidadmedida as ium on ium.um_id = pum.um_id
                where pum.par_id='.$par_id.'
                order by ium.um_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Lista insumos por Unidad, Establecimiento, Proyecto de Inversion
    public function insumos_por_unidad($aper_id){
        $sql = 'select *
                from insumos
                where aper_id='.$aper_id.' and ins_estado!=\'3\' and ins_gestion='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // lista de requerimientos alineados a la operacion y a la subactividad
    function list_requerimientos_operacion_procesos($com_id){
        $sql = 'select 
                c.com_id,
                c.pfec_id,
                i.form4_cod as prod_cod,
                i.ins_id,
                i.ins_codigo,
                i.ins_cant_requerida,
                i.ins_costo_unitario,
                i.ins_costo_total,
                i.ins_detalle,
                i.ins_unidad_medida,
                i.ins_gestion,
                i.ins_observacion,
                i.ins_monto_certificado,
                i.ins_tipo_modificacion,
                par.par_id,
                par.par_codigo,
                par.par_nombre
                from _componentes c
                Inner Join insumos as i On c.com_id=i.com_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.' 
                order by i.form4_cod,par.par_codigo,i.ins_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    // lista de requerimientos alineados a PROGRAMAS BOLSAS 
    function lista_requerimientos_inscritos_en_programas_bosas($prod_id,$com_id){
         $sql = 'select p.prod_id,p.uni_resp,p.prod_cod,i.ins_id,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_gestion,i.ins_estado,i.par_id,i.ins_observacion,i.aper_id,i.ins_monto_certificado,i.ins_tipo_modificacion,par.par_codigo,par.par_nombre
                from _productos p
                Inner Join _insumoproducto as ip On p.prod_id=ip.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where p.prod_id='.$prod_id.' and p.uni_resp='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                group by p.prod_id,p.uni_resp,p.prod_cod,i.ins_id,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_gestion,i.ins_estado,i.par_id,i.ins_observacion,i.aper_id,i.ins_monto_certificado,par.par_codigo,par.par_nombre
                order by par.par_codigo,i.ins_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // LISTA CONSOLIDADO POR PARTIDAS PROGRAMAS BOLSAS 
    function list_consolidado_partidas_programas_boLsas_uresponsable($prod_id,$com_id){
         $sql = 'select poa.prod_id,poa.par_id,poa.par_codigo,poa.par_nombre,SUM(poa.ins_costo_total) as monto
                from (

                    select p.prod_id,p.uni_resp,p.prod_cod,i.ins_id,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_gestion,i.ins_estado,i.par_id,i.ins_observacion,i.aper_id,i.ins_monto_certificado,par.par_codigo,par.par_nombre
                    from _productos p
                    Inner Join _insumoproducto as ip On p.prod_id=ip.prod_id
                    Inner Join insumos as i On i.ins_id=ip.ins_id
                    Inner Join partidas as par On par.par_id=i.par_id
                    where p.prod_id='.$prod_id.' and p.uni_resp='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                    group by p.prod_id,p.uni_resp,p.prod_cod,i.ins_id,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_gestion,i.ins_estado,i.par_id,i.ins_observacion,i.aper_id,i.ins_monto_certificado,par.par_codigo,par.par_nombre
                    order by par.par_codigo,i.ins_id asc


                ) poa
                group by poa.prod_id,poa.par_id,poa.par_codigo,poa.par_nombre
                order by poa.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- LISTA CONSOLIDADO DE PRODUCTOS PARTIDAS POR SUB ACTIVIDADES (COMPONENTES) 2022 -----*/
    function list_consolidado_partidas_componentes($com_id){
        if($this->gestion>2021){ /// 2022
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join insumos as i On i.com_id=c.com_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                group by c.com_id, c.pfec_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }
        else{
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                group by c.com_id, c.pfec_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /////===== CONSOLIDADO DE PARTIDAS 2023 - POR UNIDAD RESPONSABLE
    /*---- LISTA GET PROGRAMA CONSOLIDADO DE PARTIDAS POR UNIDAD RESPONSABLE 2023 -----*/
    function get_lista_clasificacion_x_programas_partidas_uresponsable($com_id){
        $sql = '     select com_id,aper_id_oe,aper_programa,aper_proyecto,aper_actividad,aper_descripcion,SUM(costo_total) monto
                     from vista_detalle_x_cat_programatica_partida_form5
                     where com_id='.$com_id.' and g_id='.$this->gestion.'
                     group by com_id,aper_id_oe,aper_programa,aper_proyecto,aper_actividad,aper_descripcion';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA GET MONTO PROGRAMADO POR PARTIDA Y PROGRAMA UNIDAD REPONSABLE 2023 -----*/
    function get_monto_programado_x_partida_programa_uresponsable($com_id,$par_id,$aper_id_oe){
                $sql = 'select com_id,par_id,par_codigo,par_nombre,obj_id,aper_id_oe,SUM(ins_costo_total) monto
                        from vista_get_detalle_x_cat_programatica_partida_form5
                        where com_id='.$com_id.' and g_id='.$this->gestion.' and par_id='.$par_id.' and aper_id_oe='.$aper_id_oe.'
                        group by com_id,par_id,par_codigo,par_nombre,obj_id,aper_id_oe';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /////===== END CONSOLIDADO DE PARTIDAS POR UNIDAD RESPONSABLE


    /////===== CONSOLIDADO DE PARTIDAS 2023 - POR PROYECTO / UNIDAD RESPONSABLE
    /*---- LISTA GET PROGRAMA CONSOLIDADO DE PARTIDAS POR UNIDAD / PROYECTO 2023 -----*/
    function get_lista_clasificacion_x_programas_partidas_unidad($proy_id){
        $sql = '     select proy_id,aper_id_oe,aper_programa,aper_proyecto,aper_actividad,aper_descripcion,SUM(costo_total) monto
                     from vista_detalle_x_cat_programatica_partida_form5
                     where proy_id='.$proy_id.' and g_id='.$this->gestion.'
                     group by proy_id,aper_id_oe,aper_programa,aper_proyecto,aper_actividad,aper_descripcion';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA GET MONTO PROGRAMADO POR PARTIDA Y PROGRAMA UNIDAD REPONSABLE 2023 -----*/
    function get_monto_programado_x_partida_programa_unidad($proy_id,$par_id,$aper_id_oe){
        $sql = 'select proy_id,par_id,par_codigo,par_nombre,obj_id,aper_id_oe,SUM(ins_costo_total) monto
                from vista_get_detalle_x_cat_programatica_partida_form5
                where proy_id='.$proy_id.' and g_id='.$this->gestion.' and par_id='.$par_id.' and aper_id_oe='.$aper_id_oe.'
                group by proy_id,par_id,par_codigo,par_nombre,obj_id,aper_id_oe';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /////===== END CONSOLIDADO DE PARTIDAS POR UNIDAD RESPONSABLE



    /*---- GET REQUERIMIENTO -----*/
    function get_requerimiento($ins_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and i.aper_id!=\'0\'';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// lista de requerimientos por cada formulario N° 4
    function lista_insumos_prod($prod_id){
        $sql = 'select prod.prod_id,prod.prod_cod,par.par_codigo,i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,ins_monto_certificado,ins_observacion,i.ins_tipo_modificacion,i.ins_ejec_cpoa
                from _productos prod
                Inner Join (
                select prod_id,ins_id,tp_ins
                from _insumoproducto
                group by prod_id,ins_id,tp_ins
                ) as ip On ip.prod_id=prod.prod_id
                
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                group by prod.prod_id,prod.prod_cod,par.par_codigo,i.ins_id,i.ins_detalle,i.ins_unidad_medida,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,ins_monto_certificado,ins_observacion
                order by par.par_codigo,i.ins_id asc';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////insumos con estado 3 (eliminados)
    function lista_insumos_prod_eliminados($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join _productos as prod On prod.prod_id=ip.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.'
                order by par.par_codigo,i.ins_id asc';

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_insumo_producto($ins_id){
        $sql = 'select *
                from insumos
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////// ------------------- EJECUCION DE CERTIFICACION POA FORM5

    //// ---- lista consolidado de meses programado insumo por UNIDAD menos la partida 10000 - 2022
    function get_mes_programado_insumo_unidad_menos10000($aper_id){
        $sql = 'select *
                from v_temporalidad_meses_prog_insumo_unidad_menos10000
                where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- lista consolidado de meses certificado insumo por UNIDAD menos la partida 10000 - 2022
    function get_mes_certificado_insumo_unidad_menos10000($aper_id){
        $sql = 'select *
                from v_temporalidad_meses_cert_insumo_unidad_menos10000
                where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// ---- lista consolidado de meses programado insumo por DISTRITAL menos la partida 10000 -2022
    function get_mes_programado_insumo_distrital_menos10000($dist_id){
        $sql = 'select 
                poa.dist_id,
                SUM(prog.costo_total) costo_total, 
                SUM(prog.monto_certificado) monto_certificado, 
                SUM(prog.total_programado) total_programado, 
                SUM(prog.prog_mes1) prog_mes1,
                SUM(prog.prog_mes2) prog_mes2,
                SUM(prog.prog_mes3) prog_mes3,
                SUM(prog.prog_mes4) prog_mes4,
                SUM(prog.prog_mes5) prog_mes5,
                SUM(prog.prog_mes6) prog_mes6,
                SUM(prog.prog_mes7) prog_mes7,
                SUM(prog.prog_mes8) prog_mes8,
                SUM(prog.prog_mes9) prog_mes9,
                SUM(prog.prog_mes10) prog_mes10,
                SUM(prog.prog_mes11) prog_mes11,
                SUM(prog.prog_mes12) prog_mes12

                from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') poa
                Inner Join v_temporalidad_meses_prog_insumo_unidad_menos10000 as prog On prog.aper_id=poa.aper_id
                group by dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// ---- lista consolidado de meses certificado insumo por DISTRITAL menos la partida 10000 - 2022
    function get_mes_certificado_insumo_distrital_menos10000($aper_id){
        $sql = 'select 
                poa.dist_id,
                SUM(ejec.monto_certificado) monto_certificado, 
                SUM(ejec.total_certificado) total_certificado,  
                SUM(ejec.ejec_mes1) ejec_mes1,
                SUM(ejec.ejec_mes2) ejec_mes2,
                SUM(ejec.ejec_mes3) ejec_mes3,
                SUM(ejec.ejec_mes4) ejec_mes4,
                SUM(ejec.ejec_mes5) ejec_mes5,
                SUM(ejec.ejec_mes6) ejec_mes6,
                SUM(ejec.ejec_mes7) ejec_mes7,
                SUM(ejec.ejec_mes8) ejec_mes8,
                SUM(ejec.ejec_mes9) ejec_mes9,
                SUM(ejec.ejec_mes10) ejec_mes10,
                SUM(ejec.ejec_mes11) ejec_mes11,
                SUM(ejec.ejec_mes12) ejec_mes12

                from lista_poa_gastocorriente_distrital('.$aper_id.','.$this->gestion.') poa
                Inner Join v_temporalidad_meses_cert_insumo_unidad_menos10000 as ejec On ejec.aper_id=poa.aper_id
                group by dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- lista consolidado de meses programado insumo por REGIONAL menos la partida 10000 -2022
    function get_mes_programado_insumo_regional_menos10000($dep_id){
        $sql = 'select 
                poa.dep_id,
                SUM(prog.costo_total) costo_total, 
                SUM(prog.monto_certificado) monto_certificado, 
                SUM(prog.total_programado) total_programado, 
                SUM(prog.prog_mes1) prog_mes1,
                SUM(prog.prog_mes2) prog_mes2,
                SUM(prog.prog_mes3) prog_mes3,
                SUM(prog.prog_mes4) prog_mes4,
                SUM(prog.prog_mes5) prog_mes5,
                SUM(prog.prog_mes6) prog_mes6,
                SUM(prog.prog_mes7) prog_mes7,
                SUM(prog.prog_mes8) prog_mes8,
                SUM(prog.prog_mes9) prog_mes9,
                SUM(prog.prog_mes10) prog_mes10,
                SUM(prog.prog_mes11) prog_mes11,
                SUM(prog.prog_mes12) prog_mes12

                from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') poa
                Inner Join v_temporalidad_meses_prog_insumo_unidad_menos10000 as prog On prog.aper_id=poa.aper_id
                group by dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// ---- lista consolidado de meses certificado insumo por REGIONAL menos la partida 10000 - 2022
    function get_mes_certificado_insumo_regional_menos10000($dep_id){
        $sql = 'select 
                poa.dep_id,
                SUM(ejec.monto_certificado) monto_certificado, 
                SUM(ejec.total_certificado) total_certificado,  
                SUM(ejec.ejec_mes1) ejec_mes1,
                SUM(ejec.ejec_mes2) ejec_mes2,
                SUM(ejec.ejec_mes3) ejec_mes3,
                SUM(ejec.ejec_mes4) ejec_mes4,
                SUM(ejec.ejec_mes5) ejec_mes5,
                SUM(ejec.ejec_mes6) ejec_mes6,
                SUM(ejec.ejec_mes7) ejec_mes7,
                SUM(ejec.ejec_mes8) ejec_mes8,
                SUM(ejec.ejec_mes9) ejec_mes9,
                SUM(ejec.ejec_mes10) ejec_mes10,
                SUM(ejec.ejec_mes11) ejec_mes11,
                SUM(ejec.ejec_mes12) ejec_mes12

                from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') poa
                Inner Join v_temporalidad_meses_cert_insumo_unidad_menos10000 as ejec On ejec.aper_id=poa.aper_id
                group by dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// Institucional
     //// ---- lista consolidado de meses programado insumo por INSTITUCIONAL menos la partida 10000 -2022
    function get_mes_programado_insumo_institucional_menos10000(){
        $sql = 'select 
                SUM(prog.costo_total) costo_total, 
                SUM(prog.monto_certificado) monto_certificado, 
                SUM(prog.total_programado) total_programado, 
                SUM(prog.prog_mes1) prog_mes1,
                SUM(prog.prog_mes2) prog_mes2,
                SUM(prog.prog_mes3) prog_mes3,
                SUM(prog.prog_mes4) prog_mes4,
                SUM(prog.prog_mes5) prog_mes5,
                SUM(prog.prog_mes6) prog_mes6,
                SUM(prog.prog_mes7) prog_mes7,
                SUM(prog.prog_mes8) prog_mes8,
                SUM(prog.prog_mes9) prog_mes9,
                SUM(prog.prog_mes10) prog_mes10,
                SUM(prog.prog_mes11) prog_mes11,
                SUM(prog.prog_mes12) prog_mes12

                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join v_temporalidad_meses_prog_insumo_unidad_menos10000 as prog On prog.aper_id=poa.aper_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// ---- lista consolidado de meses certificado insumo por REGIONAL menos la partida 10000 - 2022
    function get_mes_certificado_insumo_institucional_menos10000(){
        $sql = 'select 
                SUM(ejec.monto_certificado) monto_certificado, 
                SUM(ejec.total_certificado) total_certificado,  
                SUM(ejec.ejec_mes1) ejec_mes1,
                SUM(ejec.ejec_mes2) ejec_mes2,
                SUM(ejec.ejec_mes3) ejec_mes3,
                SUM(ejec.ejec_mes4) ejec_mes4,
                SUM(ejec.ejec_mes5) ejec_mes5,
                SUM(ejec.ejec_mes6) ejec_mes6,
                SUM(ejec.ejec_mes7) ejec_mes7,
                SUM(ejec.ejec_mes8) ejec_mes8,
                SUM(ejec.ejec_mes9) ejec_mes9,
                SUM(ejec.ejec_mes10) ejec_mes10,
                SUM(ejec.ejec_mes11) ejec_mes11,
                SUM(ejec.ejec_mes12) ejec_mes12

                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join v_temporalidad_meses_cert_insumo_unidad_menos10000 as ejec On ejec.aper_id=poa.aper_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    //// ---- LISTA DE REQUERIMIENTOS POR UNIDAD ORGANIZACIONAL
    function get_lista_requerimientos_unidad_partida($aper_id,$tp_id,$par_id,$dep_id){
        if($aper_id==0){ /// consolidado Regional
            $sql = 'select *
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                    where dep_id='.$dep_id.'';

        }
        else{ /// detallado por unidad programa


        }


        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// =================================== INSTITUCIONAL ============================================
    //// ---- CONSOLIDADO DE PRESUPUESTO POA (FORM 5) INSTITUCIONAL POR CATEGORIA PROGRAMATICA
    function consolidado_ppto_x_programas_institucional($tp_id){
        $sql = 'select aper_programa, SUM(ins_costo_total) ppto_poa, SUM(ins_monto_certificado) ppto_certificado
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                group by aper_programa
                order by aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// --- GET PPTO POA POR PARTIDAS INSTITUCIONAL, BUSQUEDA POR PROGRAMA
    function get_consolidado_partidas_ppto_x_programas_institucional($tp_id,$aper_programa){
        $sql = 'select aper_programa, par_id, par_codigo,SUM(ins_costo_total) ppto_poa, SUM(ins_monto_certificado) ppto_certificado
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where aper_programa=\''.$aper_programa.'\'
                group by aper_programa, par_id, par_codigo
                order by aper_programa, par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    //// =================================================================================================


        /// =================================== REGIONAL ============================================
    //// ---- CONSOLIDADO DE PRESUPUESTO POA (FORM 5) REGIONAL POR CATEGORIA PROGRAMATICA (TODOS) Regional
    function consolidado_ppto_x_programas_regional($tp_id,$dep_id){
        $sql = 'select dep_id,aper_programa, SUM(ins_costo_total) ppto_poa, SUM(ins_monto_certificado) ppto_certificado
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where dep_id='.$dep_id.'
                group by dep_id, aper_programa
                order by aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- GET LISTA DE UNIDADES PRESUPUESTO POA (FORM 5) REGIONAL POR CATEGORIA PROGRAMATICA
    function get_lista_unidad_ppto_programa($tp_id,$dep_id,$aper_programa){
        $sql = 'select dep_id, aper_programa, aper_id,proy_id,proy_nombre,tipo,act_descripcion,abrev,SUM(ins_costo_total) ppto_poa, SUM(ins_monto_certificado) ppto_certificado
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where dep_id='.$dep_id.' and aper_programa=\''.$aper_programa.'\'
                group by dep_id, aper_programa, aper_id,proy_id,proy_nombre,tipo,act_descripcion,abrev
                order by aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- GET LISTA DE PARTIDAS POR UNIDAD ORGANIZACIONAL
    function get_lista_partidas_unidad_organizacional($tp_id,$dep_id,$aper_programa,$aper_id){
        $sql = 'select dep_id, aper_programa, aper_id,par_id, par_codigo,SUM(ins_costo_total) ppto_poa, SUM(ins_monto_certificado) ppto_certificado
                from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                where dep_id='.$dep_id.' and aper_programa=\''.$aper_programa.'\' and aper_id='.$aper_id.'
                group by dep_id, aper_programa, aper_id,par_id, par_codigo
                order by aper_programa,aper_id,par_id, par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// ---- GET PROG Y CERT PARTIDAS POR REGIONAL Y DISTRITAL
    function get_partida_programado_certificado_regional_distrital($dep_id,$dist_id,$par_id){
        $sql = 'select p.dep_id,p.dist_id,SUM(i.ins_costo_total) ppto_programado,SUM(i.ins_monto_certificado) ppto_certificado
                from insumos i
                Inner Join aperturaproyectos as ap on ap.aper_id = i.aper_id
                Inner Join _proyectos as p on p.proy_id = ap.proy_id
                where p.dep_id='.$dep_id.' and p.dist_id='.$dist_id.' and i.par_id='.$par_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_tipo_modificacion=\'0\' and i.ins_gestion='.$this->gestion.'
                group by p.dep_id,p.dist_id,i.par_id
                order by p.dep_id,p.dist_id,i.par_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- GET PROG Y CERT PARTIDAS INSTITUCIONAL
    function get_partida_programado_certificado_institucional($par_id){
        $sql = 'select i.par_id,SUM(i.ins_costo_total) ppto_programado,SUM(i.ins_monto_certificado) ppto_certificado
                from insumos i
                where i.par_id='.$par_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_tipo_modificacion=\'0\' and i.ins_gestion='.$this->gestion.'
                group by i.par_id
                order by i.par_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ---- GET PROG Y CERT PARTIDAS X UNIDAD
    function get_partida_programado_certificado_unidad($aper_id,$par_id){
        $sql = 'select aper_id,par_id, SUM(ins_costo_total) ppto_programado,SUM(ins_monto_certificado) ppto_certificado
                from insumos
                where aper_id='.$aper_id.' and par_id='.$par_id.' and ins_estado!=\'3\' and ins_tipo_modificacion=\'0\' and aper_id!=\'0\' and ins_gestion='.$this->gestion.'
                group by aper_id,par_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// =================================================================================================
}
?>