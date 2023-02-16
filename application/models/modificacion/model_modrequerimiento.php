<?php
class Model_modrequerimiento extends CI_Model{
    var $gestion;
    public function __construct(){
        $this->load->database();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }

    /*----- MONTO POA POR SERVICIO -----*/
    function prespuesto_servicio_componente($com_id,$tp_id){
        $sql = '
            select p.com_id,SUM(i.ins_costo_total) total
            from _productos p
            Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
            Inner Join insumos as i On i.ins_id=ip.ins_id
            where p.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\'
            group by p.com_id'; 

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET DATOS CITE----*/
    function get_cite_insumo($cite_id){
        $sql = 'select *
                from cite_mod_requerimientos ci
                Inner Join funcionario as f On ci.fun_id=f.fun_id
                Inner Join _componentes as c On ci.com_id=c.com_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as d On d.dist_id=p.dist_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                where ci.cite_id='.$cite_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and apg.aper_gestion='.$this->gestion.'' ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA DE REQUERIMIENTOS ----*/
    function lista_requerimientos($com_id){
        $sql = 'select *,i.form4_cod as prod_cod
                from insumos i
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=i.aper_id
                where i.com_id='.$com_id.' and i.ins_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and i.ins_activo=\'0\'
                order by i.form4_cod, par.par_codigo, i.ins_id asc';
        /*if($this->gestion>2021){
            $sql = 'select *,i.form4_cod as prod_cod
                from insumos i
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=i.aper_id
                where i.com_id='.$com_id.' and i.ins_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and i.ins_activo=\'0\'
                order by i.form4_cod, par.par_codigo, i.ins_id asc';
            
        }
        else{
            $sql = 'select p.com_id, p.prod_id,p.prod_cod,par.*,i.*
                from _productos p
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=i.aper_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and apg.aper_gestion='.$this->gestion.'
                order by p.prod_cod asc';
        }*/
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*---- LISTA DE REQUERIMIENTOS ELIMINADOS POR UNIDAD ----*/
    function lista_requerimientos_eliminados($com_id){
        $sql = 'select p.com_id, p.prod_id,p.prod_cod,par.*,i.*
                from _productos p
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado=\'3\' and i.ins_gestion='.$this->gestion.'
                order by p.prod_cod asc';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*---- LISTA DE PARTIDAS DEPENDIENTES (ASIGNADAS) ----*/
    function lista_partidas_dependientes($aper_id,$par_depende){
        $sql = 'select pg.par_id,pg.partida as par_codigo,p.par_nombre,p.par_depende,pg.importe
            from ptto_partidas_sigep pg
            Inner Join partidas as p On p.par_id=pg.par_id
            where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and p.par_depende='.$par_depende.'
            order by pg.partida asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ GET INSUMO ADICIONADO --------*/
    function get_insumo_adicionado($add_id){
        $sql = 'select *
            from insumo_add 
            where add_id='.$add_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ GET ID INSUMO ADICIONADO --------*/
    function get_insumo_adicionado_id($ins_id){
        $sql = 'select *
            from insumo_add 
            where ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ GET INSUMO MODIFICADO --------*/
    function get_insumo_modificado($update_id){
        $sql = 'select *
            from insumo_update 
            where update_id='.$update_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ GET VERIF INSUMO MODIFICADO --------*/
    function get_insumo_modificado_id($ins_id){
        $sql = 'select *
            from insumo_update 
            where ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ GET INSUMO ELIMINADO --------*/
    function get_insumo_eliminado($delete_id){
        $sql = 'select *
            from insumo_delete 
            where delete_id='.$delete_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA DE ACTIVIDADES SEGUN COMPONENTE----*/
    function list_actividades_componente($com_id){
        $sql = 'select p.prod_id,p.com_id,p.prod_producto,p.prod_ppto,p.indi_id,p.prod_indicador,p.prod_linea_base, p.prod_meta,p.prod_fuente_verificacion,p.prod_unidades,p.prod_ponderacion,p.estado,p.prod_mod,
                p.prod_resultado,p.acc_id,p.prod_cod, p.prod_observacion,p.mt_id,p.or_id,i.indi_descripcion,
                ore.or_id,ore.or_codigo,og.og_id,og.og_codigo, ae.acc_id,ae.acc_codigo,oe.obj_codigo,a.*
                from _productos p
                Inner Join indicador as i On i.indi_id=p.indi_id
                
                Inner Join objetivos_regionales as ore On ore.or_id=p.or_id

                Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                Inner Join objetivo_gestion as og On og.og_id=opm.og_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=og.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id

                Inner Join _actividades as a On a.prod_id=p.prod_id
                
                where p.com_id='.$com_id.' and p.estado!=\'3\'
                order by p.prod_cod, oe.obj_codigo, ae.ae asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*--- LISTA MODIFICADOS ITEMS 2023 (ADICION Y ELIMINADO)---*/
    public function list_form5_historial_modificados($cite_id,$tipo_mod){
        ///ih.historial_activo : 0 (no se muestra)
        ///ih.historial_activo : 1 (se muestra)

         $sql = 'select *
                from insumos_historial ih
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join _productos as p On p.prod_id=ih.id
              
                where ih.cite_id='.$cite_id.' and ih.tipo_mod='.$tipo_mod.' and ih.historial_activo!=\'0\'
                order by pa.par_codigo asc';
 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA MODIFICADOS ITEMS 2023 (MODIFICADOS)---*/
    public function get_list_form5_historial_modificados($cite_id,$tipo_mod){
        $sql = 'select ih.ins_id,ih.tipo_mod,ih.cite_id
                from insumos_historial ih
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join _productos as p On p.prod_id=ih.id
              
                where ih.cite_id='.$cite_id.' and ih.tipo_mod='.$tipo_mod.' and ih.historial_activo!=\'0\'
                group by ih.ins_id,ih.tipo_mod,ih.cite_id
                ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA MODIFICADOS ITEMS 2023 (MODIFICADOS)---*/
    public function get_item_insumo_modificado_ultimo($cite_id,$tipo_mod,$ins_id){
        $sql = 'select *
                from insumos_historial ih
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join _productos as p On p.prod_id=ih.id
              
                where ih.cite_id='.$cite_id.' and ih.tipo_mod='.$tipo_mod.' and ih.historial_activo!=\'0\' and ih.ins_id='.$ins_id.'
                order by ih.insh_id DESC LIMIT 1';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA HISTORIAL MODIFICADOS ITEMS POR CITE 2023 (COMPLETO)---*/
    public function get_historial_modificacion_cite($cite_id){
        $sql = 'select ih.*,pa.*,p.prod_cod,f.fun_nombre,f.fun_paterno,f.fun_materno
                from insumos_historial ih
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join _productos as p On p.prod_id=ih.id
                Inner Join funcionario as f On ih.fun_id=f.fun_id
                where ih.cite_id='.$cite_id.'
                order by ih.tipo_mod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

// ------ lista Temporalidad Insumo
    public function list_temporalidad_insumo_historial($insh_id){
        $sql = 'select *
            from vista_temporalidad_insumo_historial
            where insh_id='.$insh_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*--- LISTA DE REQUERIMIENTOS AGREGADOS SEGUN CITE (anterior vigente 2023)---*/
    public function list_requerimientos_adicionados($cite_id){
        $sql = 'select ia.add_id,ia.ins_id,i.ins_codigo,i.ins_costo_unitario, i.ins_costo_total,i.ins_cant_requerida,i.ins_detalle,i.ins_unidad_medida,i.ins_observacion,pa.par_codigo,pa.par_nombre,p.prod_cod,p.or_id
                from insumo_add ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as pa On pa.par_id=i.par_id
                Inner Join _insumoproducto as ip On ip.ins_id=i.ins_id
                Inner Join _productos as p On p.prod_id=ip.prod_id
                    
                where ia.cite_id='.$cite_id.' and p.estado!=\'3\' and ia.estado!=\'3\'
                order by p.prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA DE REQUERIMIENTOS MODIFICADOS SEGUN CITE (anterior vigente 2023)---*/
    public function list_requerimientos_modificados($cite_id){
        $sql = 'select ia.ins_id,i.ins_codigo,i.ins_costo_unitario,i.ins_cant_requerida,i.ins_costo_unitario, i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_observacion,pa.par_codigo,pa.par_nombre,p.prod_cod,p.or_id
                from insumo_update ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as pa On pa.par_id=i.par_id
                Inner Join _insumoproducto as ip On ip.ins_id=i.ins_id
                Inner Join _productos as p On p.prod_id=ip.prod_id
                where ia.cite_id='.$cite_id.' and p.estado!=\'3\' and ia.estado!=\'3\'  
                group by ia.ins_id,i.ins_codigo,i.ins_costo_unitario,i.ins_cant_requerida, i.ins_costo_total,i.ins_detalle,i.ins_unidad_medida,i.ins_observacion,pa.par_codigo,pa.par_nombre,p.prod_cod,p.or_id
                order by p.prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA DE REQUERIMIENTOS ELIMINADOS SEGUN CITE (anterior vigente 2023)---*/
    public function list_requerimientos_eliminados($cite_id){
        $sql = 'select *
                from insumo_delete id
                Inner Join insumos_historial as ih On ih.insh_id=id.insh_id
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join vista_temporalidad_insumo_historial as temp On temp.insh_id=ih.insh_id
                Inner Join _productos as p On p.prod_id=ih.id
                where id.cite_id='.$cite_id.' and p.estado!=\'3\' and id.estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==== LISTA DE CITES GENERADOS =====*/

    /*---- Get cite Proyecto - Requerimientos ----*/
    function list_cites_requerimientos_proy($proy_id){
        if($this->gestion==2020){
            $sql = 'select *
                from cite_mod_requerimientos ci
                Inner Join funcionario as f On ci.fun_id=f.fun_id
                Inner Join _componentes as c On ci.com_id=c.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.proy_id='.$proy_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and ci.cite_estado!=\'3\'
                order by ci.cite_id asc';
        }
        else{
            $sql = 'select *
                from lista_modificacion_requerimiento('.$proy_id.','.$this->gestion.')
                where cite_activo=\'1\'';
        }
    

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- VERIF NRO DE MOD REQUERIMIENTOS (2020) ---------*/
    public function verif_modificaciones_distrital($dist_id){
        $sql = 'select *
                from conf_modificaciones_distrital
                where dist_id='.$dist_id.' and g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- Get lista cite Generados a nivel de Regionales ----*/
    public function list_cites_modfin_regionales($dep_id,$mes_id){
        if($mes_id==0){ //// Nacional
            $sql = '
                select *
                FROM vlista_cite_modificacion_financiera
                WHERE dep_id='.$dep_id.' and g_id='.$this->gestion.'';
        }
        else{ //// Regional
            $sql = '
                select *
                FROM vlista_cite_modificacion_financiera
                WHERE dep_id='.$dep_id.' and mes='.$mes_id.' and g_id='.$this->gestion.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- GET MES REQUERIMEINTO PROGRAMADO ---*/
    public function get_mes_item($ins_id,$mes_id){
        $sql = 'select *
                from temporalidad_prog_insumo
                where ins_id='.$ins_id.' and mes_id='.$mes_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //////// REPORTE CONSOLIDADO DE MODIFICACIONES 
    ////// REQUERIMIENTOS
    
    /*--- Lista de Items Generados por regional y mes (Requerimientos) ---*/
    public function list_cites_generados_requerimientos($dep_id,$mes_id){
        $sql = 'select ci.cite_id,c.com_id, extract(day from (ci.fecha_creacion))as dia, extract(month from (ci.fecha_creacion))as mes, extract(years from (ci.fecha_creacion))as gestion,ci.g_id,p.proy_id,p.dep_id,p.dist_id
                from cite_mod_requerimientos ci
                Inner Join funcionario as f On ci.fun_id=f.fun_id
                Inner Join _componentes as c On ci.com_id=c.com_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                where p.dep_id='.$dep_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and ci.cite_estado!=\'3\' and extract(month from (ci.fecha_creacion))='.$mes_id.' and ci.g_id='.$this->gestion.'
                order by ci.cite_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de Items Generados por Distrital y mes (Requerimientos) ---*/
    public function list_cites_generados_requerimientos_distrital($dist_id,$mes_id,$tp_id){
        $sql = 'select ci.cite_id,ci.com_id
                from cite_mod_requerimientos ci
                Inner Join funcionario as f On ci.fun_id=f.fun_id
                Inner Join _componentes as c On ci.com_id=c.com_id
                 Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join lista_poa_gastocorriente_nacional(2022) as poa On poa.pfec_id=c.pfec_id

                where poa.dist_id='.$dist_id.' and ci.cite_estado!=\'3\' and c.estado!=\'3\' and ci.cite_activo=\'1\' and extract(month from (ci.fecha_creacion))='.$mes_id.'
                order by ci.cite_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

        /*--- Lista de Items Generados por Regional y mes (Requerimientos) ---*/
    public function list_cites_generados_requerimientos_regional($dep_id,$mes_id,$tp_id){
        $sql = 'select ci.cite_id,ci.com_id
                from cite_mod_requerimientos ci
                Inner Join funcionario as f On ci.fun_id=f.fun_id
                Inner Join _componentes as c On ci.com_id=c.com_id
                 Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join lista_poa_gastocorriente_nacional(2022) as poa On poa.pfec_id=c.pfec_id

                where poa.dist_id='.$dep_id.' and ci.cite_estado!=\'3\' and c.estado!=\'3\' and ci.cite_activo=\'1\' and extract(month from (ci.fecha_creacion))='.$mes_id.'
                order by ci.cite_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- tipos de acciones - requerimientos ---*/
    public function numero_de_modificaciones_requerimientos($cite_id,$tp_accion){
        //// 1 : Adicion
        //// 2 : Modificacion
        //// 3 : Eliminacion

        if($tp_accion==1){
            $sql = '
            select ia.cite_id
                from insumo_add ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                where ia.cite_id='.$cite_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and ia.estado!=\'3\'
                group by ia.cite_id';
        }
        elseif ($tp_accion==2) {
           $sql = '
            select ia.cite_id
                from insumo_update ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                where cite_id='.$cite_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and ia.estado!=\'3\'  
                group by ia.cite_id'; 
        }
        else{
            $sql = '
            select id.cite_id
                from insumo_delete id
                Inner Join insumos_historial as ih On ih.insh_id=id.insh_id
                Inner Join partidas as pa On pa.par_id=ih.par_id
                Inner Join vista_temporalidad_insumo_historial as temp On temp.insh_id=ih.insh_id
                Inner Join _productos as p On p.prod_id=ih.id
                where id.cite_id='.$cite_id.' and ih.ins_estado!=\'3\' and ih.aper_id!=\'0\' and p.estado!=\'3\' and id.estado!=\'3\'
                group by id.cite_id '; 
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*--- XLS LISTA DE REQUERIMIENTOS ADICIONADOS, MODIFICADOS, ELIMINADOS ---*/
    public function lista_requerimientos_modificados_unidad($proy_id,$tp_accion){
        //// 1 : Adicion
        //// 2 : Modificacion
        //// 3 : Eliminacion

        if($tp_accion==1){
            $sql = '
              select ia.*,cmi.*,i.*,pa.*,p.*,c.*
                from vista_componentes_dictamen c
                Inner Join cite_mod_requerimientos as cmi On cmi.com_id=c.com_id
                Inner Join insumo_add as ia On ia.cite_id=cmi.cite_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as pa On pa.par_id=i.par_id
                Inner Join _insumoproducto as ip On ip.ins_id=i.ins_id
                Inner Join _productos as p On p.prod_id=ip.prod_id
                
                where c.proy_id='.$proy_id.' and cmi.g_id='.$this->gestion.' and ia.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and p.estado!=\'3\'
                order by ia.add_id, p.prod_cod asc';
        }
        elseif ($tp_accion==2) {
           $sql = '
             select ia.*,cmi.*,i.*,pa.*,p.*,c.*
                from vista_componentes_dictamen c
                Inner Join cite_mod_requerimientos as cmi On cmi.com_id=c.com_id
                Inner Join insumo_update as ia On ia.cite_id=cmi.cite_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as pa On pa.par_id=i.par_id
                Inner Join _insumoproducto as ip On ip.ins_id=i.ins_id
                Inner Join _productos as p On p.prod_id=ip.prod_id
                
                where c.proy_id='.$proy_id.' and cmi.g_id='.$this->gestion.' and ia.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and p.estado!=\'3\'
                order by ia.update_id, p.prod_cod asc'; 
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /////============== MODIFICACION PRESUPUESTARIA 
    /*--- Lista de Modificaciones Presupuestarias ---*/
    public function list_cites_mod_presupuestaria(){
        $sql = 'select *
                from modificacion_presupuestaria mp
                Inner Join _distritales as ds On ds.dist_id=mp.dist_id
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where mp.g_id='.$this->gestion.'
                order by mp_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- GET Modificaciones Presupuestaria ---*/
    public function get_cites_mod_presupuestaria($mp_id){
        $sql = 'select *
                from modificacion_presupuestaria mp
                Inner Join _distritales as ds On ds.dist_id=mp.dist_id
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where mp_id='.$mp_id.' and mp.g_id='.$this->gestion.'
                order by mp_id asc ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de partidas Modificadas ---*/
    public function list_partidas_modificadas($mp_id){
        $sql = 'select *
                from partidas_presupuestarias_modificadas
                where mp_id='.$mp_id.'
                order by mpa_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de partidas tipo de Modificaciones Consolidado ---*/
    public function list_tipo_partidas_modificadas($mp_id,$tp){
        $sql = 'select *
                from partidas_presupuestarias_modificadas mp
                Inner Join partidas as par On par.par_id=mp.par_id
                Inner Join aperturaproyectos as ap On ap.aper_id=mp.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where mp.mp_id='.$mp_id.' and mp.tipo='.$tp.'
                order by mp.mpa_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de partidas tipo de Modificaciones clasificado por distrital ---*/
    public function list_tipo_partidas_modificadas_clasificado_distrital($mp_id,$tp,$dist_id){
        $sql = 'select *
                from partidas_presupuestarias_modificadas mp
                Inner Join partidas as par On par.par_id=mp.par_id
                Inner Join aperturaproyectos as ap On ap.aper_id=mp.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where mp.mp_id='.$mp_id.' and mp.tipo='.$tp.' and ds.dist_id='.$dist_id.'
                order by mp.mpa_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- GET Partida Modificado ---*/
    public function get_partida_mppto($mpa_id){
        $sql = 'select ppm.*,mp.*,ds.*,d.*,p.*
                from partidas_presupuestarias_modificadas ppm
                Inner Join modificacion_presupuestaria as mp On mp.mp_id=ppm.mp_id
                Inner Join aperturaproyectos as ap On ap.aper_id=ppm.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                where ppm.mpa_id='.$mpa_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- Verificando si existe registrado Cite Presupuesto --------*/
    public function verif_get_cite_modifcado($mp_id,$proy_id){
        $sql = 'select *
                from ppto_cite cit
                Inner Join funcionario as fun On fun.fun_id=cit.fun_id 
                where cit.mp_id='.$mp_id.' and cit.cppto_estado!=\'3\' and cit.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Lista de Unidades Reponsables para alinear a bienes y servicios --------*/
    public function list_uresponsables(){
        $sql = 'select c.*,tpsa.*,sa.*
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                where p.proy_id!=\'2647\' and p.proy_id!=\'2648\' and p.proy_id!=\'2650\' and p.proy_id!=\'2651\' and p.proy_id!=\'2653\' and p.proy_id!=\'2646\' and p.dist_id=\'22\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and apg.aper_proy_estado=\'4\' and apg.aper_estado!=\'3\' and c.estado!=\'3\'
                ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}