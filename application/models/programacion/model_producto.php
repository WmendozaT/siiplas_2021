<?php
class model_producto extends CI_Model {
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }

    function temporalidad_form4(){
        $sql = 'select *
                from vista_productos_temporalizacion_programado_dictamen
                where g_id='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function get_datos_alineacion_unidadResponsable_seleccionado($prod_id){
        $sql = 'select prod.prod_id,prod.prod_producto,tpsa.tipo_subactividad,sa.serv_descripcion,te.tipo,ua.act_descripcion
                from _productos prod
                Inner Join _componentes as c On c.com_id=prod.uni_resp
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On pfe.proy_id=p.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where prod_id='.$prod_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- LISTA DE FORMULARIO 4 (2022) para el SEguimiento POA -----*/
    function list_operaciones_subactividad($com_id){
        $sql = '
        select 
            p.prod_id,
            p.com_id,
            p.prod_cod,
            p.prod_producto,
            p.indi_id,
            mt.mt_id,
            p.prod_indicador,
            p.prod_linea_base,
            p.prod_meta,
            p.prod_fuente_verificacion,
            p.prod_unidades,
            p.prod_resultado,
            p.acc_id,
            p.prod_priori,
            ore.or_id,
            ore.or_codigo,
            ore.or_objetivo,
            ore.or_indicador,
            ore.or_producto,
            ore.or_resultado,
            ore.or_verificacion,
            mt.mt_tipo,
            mt.mt_descripcion
        
          from _productos as p
          Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
          Inner Join indicador as tp On p.indi_id=tp.indi_id
          Inner Join meta_relativo as mt On mt.mt_id=p.mt_id
          where p.estado!=\'3\' and p.com_id='.$com_id.'
          ORDER BY p.prod_cod asc'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- LISTA DE FORM 4 ELIMINADOS PARA LIMPIAR EN LA BS -----*/
    function list_form4_eliminados_gestion($proy_id){
        $sql = 'select c.*,prod.*,apg.aper_gestion
                from _proyectofaseetapacomponente pfe
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On c.com_id=prod.com_id
                where pfe.proy_id='.$proy_id.' and apg.aper_gestion='.$this->gestion.''; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- LISTA DE UNIDADES RESPONSABLES REGIONAL PARA FILTRAR AL PROG 770 (2023) -----*/
    function list_uresponsables_regional($dist_id){
            
        if($dist_id==3){
            $sql = '
            select poa.aper_id,poa.proy_id,poa.tipo, poa.actividad,poa.abrev,c.com_id,tpsa.tipo_subactividad,sa.serv_descripcion
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join _componentes as c On c.pfec_id=poa.pfec_id
            Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
            Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
            where dist_id='.$dist_id.' and c.estado!=\'3\'
            order by poa.prog,poa.proy,poa.act asc'; 
        }
        else{
            $sql = '
            select poa.aper_id,poa.proy_id,poa.tipo, poa.actividad,poa.abrev,c.com_id,tpsa.tipo_subactividad,sa.serv_descripcion
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join _componentes as c On c.pfec_id=poa.pfec_id
            Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
            Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
            where dist_id='.$dist_id.' and c.estado!=\'3\' and poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\' and poa.prog!=\'770\' and poa.prog!=\'960\' 
            order by poa.prog,poa.proy,poa.act asc'; 
        }
        

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- GET UNIDAD RESP POR ACTIVIDAD (PROG 770) -----*/
    function get_uni_resp_prog770($com_id,$uni_resp){
        $sql = '
        select *
                from _productos
                where com_id='.$com_id.' and uni_resp='.$uni_resp.' and estado!=\'3\''; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- GET UNIDAD RESPONSABLE POR ACTIVIDAD (PROG BOLSA) -----*/
    function verif_get_uni_resp_programaBolsa($com_id){
        $sql = '
            select prod.*,apg.*,prog.*
            from _productos prod
            Inner Join _componentes as c On prod.com_id=c.com_id
            Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
            Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                
            Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
            where prod.uni_resp='.$com_id.' and prog.g_id='.$this->gestion.'
            order by apg.aper_programa, prod.prod_cod asc'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }

        /*----- GET UNIDAD RESPONSABLE POR programa (PROG BOLSA) -----*/
    function verif_get_uni_resp_programaBolsa_prog($aper_id,$com_id){
        $sql = '
            select prod.*,apg.*,prog.*,pfe.*
            from _productos prod
            Inner Join _componentes as c On prod.com_id=c.com_id
            Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
            Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                
            Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
            where apg.aper_id='.$aper_id.' and prod.uni_resp='.$com_id.' and prog.g_id='.$this->gestion.'
            order by apg.aper_programa, prod.prod_cod asc'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- GET LISTA DE ACTIVIDADES ALINEADO A LA UNIDAD RESPONSABLE DE LOS PROGRAMAS BOLSA 2023 (REVISAR)-----*/
    function get_lista_form4_uniresp_prog_bolsas($com_id){
        $sql = 'select apg.aper_id,p.proy_id,apg.aper_gestion,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,prod.com_id,prod.prod_id,prod.prod_cod,prod.prod_producto, prod.prod_indicador, prod.prod_meta,prod.prod_fuente_verificacion,prod.uni_resp
                from _productos prod
                Inner Join _componentes as c On prod.com_id=c.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id

                where prod.uni_resp='.$com_id.' and apg.aper_gestion='.$this->gestion.' and c.estado!=\'3\' and prod.estado!=\'3\' and pfe.pfec_estado!=\'3\'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }





    /*----- GET LISTA DE ACTIVIDADES PROGRAMA NORMAL O BOLSAS 2024 (Gasto Corriente) -----*/
    function get_lista_form4_consolidado($com_id,$tp){
        /// tp: 0 (listado normal de actividades)
        /// tp: 1 (Listado de Actividades Bolsas por unidad responsable)

        if($tp==0){ /// listado normal POA (Actividades)
            $sql = '
                select apg.aper_programa,p.prod_id,p.com_id,p.prod_priori,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod,p.uni_resp,p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,i.indi_abreviacion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo,enero as m1,febrero as m2, marzo as m3, abril as m4,mayo as m5, junio as m6, julio as m7, agosto as m8, septiembre as m9, octubre as m10, noviembre as m11, diciembre as m12, prog.g_id
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=og.aper_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\'
                order by p.prod_cod asc'; 
        }
        else{ /// listado POa bolsas
            $sql = '
                select apg.aper_programa,p.prod_id,p.com_id,p.prod_priori,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod,p.uni_resp,p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,i.indi_abreviacion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo,enero as m1,febrero as m2, marzo as m3, abril as m4,mayo as m5, junio as m6, julio as m7, agosto as m8, septiembre as m9, octubre as m10, noviembre as m11, diciembre as m12, prog.g_id
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join _componentes as c On p.com_id=c.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p.uni_resp='.$com_id.' and prog.g_id='.$this->gestion.' and p.estado!=\'3\'
                order by apg.aper_programa,p.prod_cod asc'; 
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }





    /// Migracion de temporalidad form 4 
    function list_temporalidad_total_form4(){
        $sql = 'select *
                from prod_programado_mensual'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- RELACION INSUMO PRODUCTO (VIGENTE) -----*/
    function insumo_producto($prod_id){
        $sql = 'select ip.ins_id
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.' and i.aper_id!=\'30\'
                group by ip.ins_id'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------- ULTIMO PRODUCTO (2021-2022) ----------*/
    function ult_operacion($com_id){
        $sql = 'select p.*
                from _productos as p
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p."com_id"='.$com_id.' and p.estado!=\'3\' and prog.g_id='.$this->gestion.'
                ORDER BY p.prod_cod desc LIMIT 1'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function list_prod($com_id){
        $sql = 'select *
            from _productos as p
            Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
            Inner Join indicador as tp On p.indi_id=tp.indi_id
            Inner Join meta_relativo as mt On mt.mt_id=p.mt_id
            where p.com_id='.$com_id.' and p.estado!=\'3\' 
            ORDER BY p.prod_cod asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*========== META GESTION ACTUAL PRODUCTO ==========*/
    public function meta_prod_gest($id_prod){
        $sql = 'SELECT SUM(pg_fis) as meta_gest
            from prod_programado_mensual
            where prod_id='.$id_prod.' AND g_id='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*====================================================*/
    /*========== LISTA DE PRODUCTOS ANUAL ================*/
    function get_producto_id($id_prod){
        $sql = 'select *
                from _productos p
                Inner Join indicador as tp On p.indi_id=tp.indi_id
                Inner Join meta_relativo as mr On mr.mt_id=p.mt_id
                Inner Join _componentes as c On c.com_id=p.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _proyectos as proy On proy.proy_id=pfe.proy_id
                
                Inner Join _distritales as dist On dist.dist_id=proy.dist_id
                where p.prod_id='.$id_prod.' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    /*=====================================================*/



    /*=== LISTA DE OPERACIONES (2020 - 2022) REPORTE - GASTO CORRIENTE ajustando ===*/
    function lista_form4_x_unidadresponsable($com_id){
        $sql = 'select p.prod_id,p.com_id,p.prod_priori,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod,p.uni_resp,p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,i.indi_abreviacion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo,c.com_id,sa.serv_descripcion,tpsa.tipo_subactividad,ua.act_descripcion,ds.abrev,te.tipo
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id

                Inner Join _componentes as c On p.uni_resp=c.com_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as proy On pfe.proy_id=proy.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=proy.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as ds On ds.dist_id=proy.dist_id

                where p.com_id='.$com_id.' and p.estado!=\'3\'
                order by p.prod_cod asc'; 
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*=== LISTA DE OPERACIONES (2020) REPORTE - GASTO CORRIENTE ===*/
    function list_operaciones_pi($com_id){
        $sql = 'select p.prod_id,p.com_id,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod, p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo, oe.obj_codigo,pr.*
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as pr On pr.prod_id=p.prod_id
                
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
              
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=og.oe_id
                
                where p.com_id='.$com_id.' and p.estado!=\'3\' and pr.g_id='.$this->gestion.'
                order by p.prod_cod, oe.obj_codigo asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// Vigente
    function producto_programado($prod_id,$gestion){
        $sql = 'select *
                from vista_productos_temporalizacion_programado_dictamen
                where prod_id='.$prod_id.' and g_id='.$gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function producto_ejecutado($prod_id,$gestion){
        $sql = 'select *
                from vista_productos_temporalizacion_ejecutado_dictamen
                where prod_id='.$prod_id.' and g_id='.$gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- SUMA TOTAL EVALUADO -------*/
    function suma_total_evaluado($prod_id){
        $sql = 'select 
                SUM(pejec_fis) as suma_total
                from prod_ejecutado_mensual
                where prod_id='.$prod_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function prod_prog_mensual($id_pr,$gest){
        $this->db->from('prod_programado_mensual');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function nro_prod_prog_mensual($id_pr,$gest){
        $this->db->from('prod_programado_mensual');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->num_rows();
    } 
    /*==========================================================*/
    /*================= LISTA DE PRODUCTOS EJECUTADO GESTION  ==============*/
    public function prod_ejec_mensual($id_pr,$gest){
        $this->db->from('prod_ejecutado_mensual');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function nro_prod_ejec_mensual($id_pr,$gest){
        $this->db->from('prod_ejecutado_mensual');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*==========================================================*/

    /*============ LISTA DE PRODUCTOSGESTION ANUAL =====================*/
    function list_prodgest_anual($id_prod){
        $sql = 'SELECT *
            from prod_programado_mensual
            where prod_id='.$id_prod.' and g_id='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    /*===================================================*/
    /*======= AGREGAR PRODUCTO  PROGRAMADO GESTION =====*/
    function add_prod_gest($id_prod,$gestion,$m_id,$valor){
        $data = array(
            'prod_id' => $id_prod,
            'm_id' => $m_id,
            'pg_fis' => $valor,
            'g_id' => $gestion,
        );
        $this->db->insert('prod_programado_mensual',$data);
    }
    /*==================================================*/

    /*--------- VERIF MES EVALUADO-FORM 4 ---------*/
    public function verif_ope_evaluado_mes($prod_id,$mes_id){
        $sql = 'select *
                from prod_ejecutado_mensual
                where g_id='.$this->gestion.' and prod_id='.$prod_id.' and m_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*===== AGREGAR PRODUCTO  EJECUTADO GESTION (Cumplido-En proceso) =====*/
    function add_prod_ejec_gest($id_prod,$gestion,$m_id,$ejecutado,$mverificacion,$observacion,$acciones){
        $data = array(
            'prod_id' => $id_prod,
            'm_id' => $m_id,
            'pejec_fis' => $ejecutado,
            'g_id' => $gestion,
            'fun_id' => $this->fun_id,
            'medio_verificacion' => strtoupper($mverificacion),
            'observacion' => strtoupper($observacion),
            'acciones' => strtoupper($acciones),
        );
        $this->db->insert('prod_ejecutado_mensual',$data);
    }

    /*---------- Adiciona operaciones no cumplidas ---------*/
    function add_no_ejec_prod($prod_id,$mes_id,$mverificacion,$observacion,$acciones){
        $data = array(
            'prod_id' => $prod_id,
            'm_id' => $mes_id,
            'g_id' => $this->gestion,
            'medio_verificacion' => strtoupper($mverificacion),
            'observacion' => strtoupper($observacion),
            'acciones' => strtoupper($acciones),
        );
        $this->db->insert('prod_no_ejecutado_mensual',$data);
    }
    /*====================================================================*/
    /*=========== BORRA DATOS DE PRODUCTO PROGRAMADO GESTION =============*/
    public function delete_prod_gest($id_prod){ 
        $this->db->where('prod_id', $id_prod);
        $this->db->delete('prod_programado_mensual'); 
    }
    /*=====================================================================*/
    /*======= BORRA DATOS DE PRODUCTO PROGRAMADO GESTION ========*/
    public function delete_prod_ejec_gest($id_prod,$gest){ 
        $this->db->where('prod_id', $id_prod);
        $this->db->where('g_id', $gest);
        $this->db->delete('prod_ejecutado_mensual'); 

        $this->db->where('prod_id', $id_prod);
        $this->db->where('g_id', $gest);
        $this->db->delete('prod_ejecutado_mensual_relativo'); 
    }
    /*==========================================================*/

    /*======= NRO DE PRODUCTOS ===========*/
    public function productos_nro($id_c){
        $this->db->from('_productos');
        $this->db->where('com_id', $id_c);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*====================================*/    
    /*===== BORRA DATOS PRODUCTOS ========*/
    public function delete_producto_p($id_p){ 

        $this->db->where('prod_id', $id_p);
        $this->db->delete('prod_programado_mensual');
    }

    public function delete_producto_e($id_p){ 

        $this->db->where('prod_id', $id_p);
        $this->db->delete('prod_ejecutado_mensual');

        $this->db->where('prod_id', $id_p);
        $this->db->delete('_productos');
    }

    public function delete_producto($id_p){ 

        $this->db->where('prod_id', $id_p);
        $this->db->delete('_productos');
    }
    /*=============================================*/

    /*----------- GET PRODUCTO PROGRAMADO ---------*/
    public function programado_producto($prod_id){ 
        $sql = 'select *
                from prod_programado_mensual
                where prod_id='.$prod_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------------ GET PRODUCTO PROGRAMADO MES-------------------*/
    public function get_mes_programado_form4($prod_id,$mes_id){ 
        $sql = 'select *
                from prod_programado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------------ SUMA PRODUCTO PROGRAMADO -------------------*/
    public function suma_programado_producto($prod_id,$gestion){ 
        $sql = 'select prod_id,SUM(pg_fis) as prog
                from prod_programado_mensual
                where prod_id='.$prod_id.' and g_id='.$gestion.'
                GROUP BY prod_id'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ VERIF OPERACION REQUERIMIENTO -----*/
    public function verif_componente_operacion($com_id,$prod_cod){ 
        $sql = 'select *
                from _productos p
                Inner Join vista_productos_temporalizacion_programado_dictamen as pr On pr.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.prod_cod='.$prod_cod.' and p.estado!=\'3\' and pr.g_id='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA DE METAS - RELATIVO ----*/
    public function tp_metas(){
        $sql = 'select *
                from meta_relativo
                where estado!=\'0\'
                order by mt_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- SUMA PRODUCTO PROGRAMADO AL TRIMESTRE ACTUAL ----*/
    public function suma_prog_trimestre($prod_id,$fmes){ 
        $sql = 'select prod_id,sum(pg_fis) meta
                from prod_programado_mensual
                where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$fmes.')
                group by prod_id'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD EJECUTADO AL MES ACTUAL ----*/
    public function suma_ejec_trimestre($prod_id,$fmes){ 
        $sql = 'select prod_id,sum(pejec_fis) meta
                from prod_ejecutado_mensual
                where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$fmes.')
                group by prod_id'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// =============== PARA ACTUALIZAR LOS REQUERIMIENTOS DE PROYECTOS DE INVERSION A ISNUMOSPRODUCTO
    /*----- LISTA PRODUCTOS CON ID PROYECTO ----*/
    public function list_productos_proyecto($proy_id){ 
        $sql = 'select *
                from vista_producto
                where proy_id='.$proy_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Lista Insumos por Productos - Proyectos de Inversion 
    public function lista_insumos_por_producto($prod_id){
        $sql = 'select *
                from _actividades a
                Inner Join _insumoactividad as ia On ia.act_id=a.act_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                where a.prod_id='.$prod_id.' and a.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /// ======================================
}
?>  
