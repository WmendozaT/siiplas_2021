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

    
    /*----- LISTA DE OPERACIONES (2021) para el SEguimiento POA -----*/
    function list_operaciones_subactividad($com_id){
        $sql = 'select *
                from v_operaciones_subactividad
                where com_id='.$com_id.''; 

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


    /// Migracion de temporalidad form 4 
    function list_temporalidad_total_form4(){
        $sql = 'select *
                from prod_programado_mensual'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- RELACION INSUMO PRODUCTO (2019) -----*/
    function insumo_producto($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.''; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- MONTO TOTAL OPERACION - INSUMOPRODUCTO (2019) ------*/
    function monto_insumoproducto($prod_id){
        $sql = 'select ip.prod_id,SUM(i.ins_costo_total) as total
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.' and i.aper_id!=\'0\'
                group by ip.prod_id'; 
 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- ULTIMO PRODUCTO (2019) ----------*/
    function ult_operacion($com_id){
        $sql = 'select p.*
                from _productos as p
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p."com_id"='.$com_id.' and p.estado!=\'3\' and prog.g_id='.$this->gestion.'
                ORDER BY p.prod_cod desc LIMIT 1'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=========== LISTA DE PRODUCTOS  ==============*/
    function list_prod2($com_id){
        $sql = 'select *
            from _productos as p
            Inner Join objetivos_regionales as ore On ore.or_id=p.or_id
            Inner Join indicador as tp On p.indi_id=tp.indi_id
            Inner Join meta_relativo as mt On mt.mt_id=p.mt_id
            Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
            where p.com_id='.$com_id.' and p.estado!=\'3\' and prog.g_id='.$this->gestion.'
            ORDER BY p.prod_cod asc'; 
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

/*    function suma_ponderacion($com_id){
        $sql = 'select SUM(prod_ponderacion) as suma
                from _productos
                where com_id='.$com_id.' and estado!=\'3\' '; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*==============================================================================================================*/

/*    function update_producto_archivo($id){
        $sql = 'update _productos_archivos set estado=0 WHERE id = '.$id.'';  
        $this->db->query($sql);
    }*/

/*    function get_producto_archivo($id){
        $sql = 'select * from _productos_archivos where id = '.$id.'';   
        $this->db->query($sql);
    }*/
/*------------------------------------------------------------------------------------------*/
/*=================================== META GESTION ACTUAL PRODUCTO ====================================*/
    public function meta_prod_gest($id_prod){
        $sql = 'SELECT SUM(pg_fis) as meta_gest
            from prod_programado_mensual
            where prod_id='.$id_prod.' AND g_id='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
/*==============================================================================================================*/
/*=================================== LISTA DE PRODUCTOS ANUAL ====================================*/
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
    /*===================================================================*/


    /*=== LISTA DE OPERACIONES (2020) REPORTE - GASTO CORRIENTE ===*/
    function lista_operaciones($com_id){
        $sql = 'select p.prod_id,p.com_id,p.prod_priori,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod, p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,i.indi_abreviacion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo, ae.acc_id,ae.acc_codigo,oe.obj_codigo
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                
                where p.com_id='.$com_id.' and p.estado!=\'3\'
                order by p.prod_cod, oe.obj_codigo, ae.ae asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*=== LISTA DE OPERACIONES (2020) REPORTE - GASTO CORRIENTE ===*/
    function list_operaciones_pi($com_id){
        $sql = 'select p.prod_id,p.com_id,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod, p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo, ae.acc_id,ae.acc_codigo,oe.obj_codigo,pr.*
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as pr On pr.prod_id=p.prod_id
                
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                
                where p.com_id='.$com_id.' and p.estado!=\'3\' and pr.g_id='.$this->gestion.'
                order by p.prod_cod, oe.obj_codigo, ae.ae asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function producto_programado($prod_id,$gestion){
        $sql = 'select *
                from vista_productos_temporalizacion_programado_dictamen
                where prod_id='.$prod_id.' and g_id='.$gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ lista de Objetivos Estrategicos por producto alineado 2016-2020 -----*/
    function list_oestrategico($com_id){
        $sql = 'select oe.obj_id,obj_codigo,obj_descripcion, count(p.prod_id),oe.obj_gestion_inicio gi,oe.obj_gestion_fin gf
                from _productos p
                Inner Join _acciones_estrategicas as ae On ae.ae=p.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and ('.$this->gestion.'>=ae.g_id_inicio and '.$this->gestion.'<=ae.g_id_fin)
                group by oe.obj_id,obj_codigo,obj_descripcion
                order by oe.obj_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ lista de productos alineado a un objetivo estrategico 2016-2020 -----*/
    function list_producto_programado_oestrategico($com_id,$gestion,$obj_id,$gi,$gf){
        $sql = 'select *
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                Inner Join _acciones_estrategicas as ae On ae.ae=p.acc_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as pr On pr.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and pr.g_id='.$gestion.' and ae.obj_id='.$obj_id.' and (pr.g_id>=ae.g_id_inicio and pr.g_id<=ae.g_id_fin)
                order by ae.acc_id asc'; 
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
    /*==============================================================================================================*/
    /*=================================== LISTA DE PRODUCTOS EJECUTADO GESTION  ====================================*/
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

    /*==============================================================================================================*/
    /*=================================== LISTA DE PRODUCTOS EJECUTADO RELATIVO GESTION  ====================================*/
    public function prod_ejecr_mensual($id_pr,$gest){
        $this->db->from('prod_ejecutado_mensual_relativo');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function nro_prod_ejecr_mensual($id_pr,$gest){
        $this->db->from('prod_ejecutado_mensual_relativo');
        $this->db->where('prod_id', $id_pr);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->num_rows();
    } 
    /*==============================================================================================================*/
    /*=================================== LISTA DE PRODUCTOSGESTION ANUAL ====================================*/
    function list_prodgest_anual($id_prod){
        $sql = 'SELECT *
            from prod_programado_mensual
            where prod_id='.$id_prod.' and g_id='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    /*================================================================================*/
    /*================== AGREGAR PRODUCTO  PROGRAMADO GESTION ========================*/
    function add_prod_gest($id_prod,$gestion,$m_id,$valor){
        $data = array(
            'prod_id' => $id_prod,
            'm_id' => $m_id,
            'pg_fis' => $valor,
            'g_id' => $gestion,
        );
        $this->db->insert('prod_programado_mensual',$data);
    }
    /*==============================================================================================================*/

    /*--------- VERIF MES EVALUADO-OPERACION ---------*/
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
    /*================= BORRA DATOS DE PRODUCTO PROGRAMADO GESTION ===========*/
    public function delete_prod_ejec_gest($id_prod,$gest){ 
        $this->db->where('prod_id', $id_prod);
        $this->db->where('g_id', $gest);
        $this->db->delete('prod_ejecutado_mensual'); 

        $this->db->where('prod_id', $id_prod);
        $this->db->where('g_id', $gest);
        $this->db->delete('prod_ejecutado_mensual_relativo'); 
    }
    /*=================================================================================================*/
    /*=================================== LISTA DE PRODUCTOS 2018 ====================================*/
    public function prod_terminal($prog){
        $sql = 'select *
                from poa_accionmplazo pam
                Inner Join poa as poa On poa.poa_id=pam.poa_id
                Inner Join aperturaprogramatica as aper On poa.aper_id=aper.aper_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=pam.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _resultado_mplazo as re On re.acc_id=ae.acc_id
                Inner Join _pterminal_mplazo as pt On pt.rm_id=re.rm_id
                Inner Join _pterminal_mplazo_programado as ptp On ptp.ptm_id=pt.ptm_id
                where aper.aper_programa=\''.$prog.'\' and aper.aper_proyecto=\'0000\' and aper.aper_actividad=\'000\' and poa.poa_gestion='.$this->gestion.' and ae.acc_estado!=\'3\' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') and ptp.g_id='.$this->gestion.'
                order by pt.ptm_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function prod_terminal2($prog){
        $sql = 'select pt.*
                from aperturaprogramatica as ap
                Inner Join resultado_corto_plazo as rc On rc.aper_id=ap.aper_id
                Inner Join _productoterminal as pt On pt.rc_id=rc.rc_id
                where ap.aper_gestion='.$this->session->userdata("gestion").' and ap.aper_programa=\''.$prog.'\' and ap.aper_proyecto=\'0000\' and ap.aper_actividad=\'000\' and pt.pt_gestion='.$this->session->userdata("gestion").' and pt.pt_estado!=\'3\'
                ORDER BY pt.pt_id  asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==============================================================================================================*/
    /*================================= NRO DE PRODUCTOS ======================================*/
    public function productos_nro($id_c){
        $this->db->from('_productos');
        $this->db->where('com_id', $id_c);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*================================================================================================*/    
    /*============================ BORRA DATOS PRODUCTOS =================================*/
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
    /*======================================================================================*/

    /*------------------ GET PRODUCTO PROGRAMADO -------------------*/
    public function programado_producto($prod_id){ 
        $sql = 'select *
                from prod_programado_mensual
                where prod_id='.$prod_id.''; 
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

    /*------------------ ALINEACION ACCION - OPERACION -------------------*/
    public function operacion_accion($ae){ 
        $sql = 'select *
            from _acciones_estrategicas ae
            Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id 
            where ae.ae='.$ae.''; 
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


    /*---- LISTA DE REQUERIMIENTOS POR COMPONENTES ----*/
    public function requerimientos_componentes($com_id){
        $sql = 'select *
                from _productos p
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On i.ins_id=ig.ins_id
                Inner Join vifin_prog_mes as pr On ig.insg_id=pr.insg_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.'
                order by p.prod_cod, i.ins_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA DE PRODUCTOS-OPERACIONES ----*/
    public function list_ope_proy($proy_id){
        $sql = 'select *
                from _proyectofaseetapacomponente pfe 
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join indicador as indi On pr.indi_id=indi.indi_id
                where pfe.proy_id='.$proy_id.' and pfe.pfec_ejecucion=\'1\' and pfe.pfec_estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\'
                order by c.com_id, pr.prod_id asc'; 
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
