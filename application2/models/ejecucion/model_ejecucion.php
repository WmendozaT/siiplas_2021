<?php
class Model_ejecucion extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
    
    /*----- VERIFICA SI UN REQUERIMIENTO ESTA CERTIFICADO ----*/
/*    public function verif_insumo_certificado($ins_id){
        $sql = ' select *
                 from certificacionpoadetalle
                 where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- DEPARTAMENTO - DISTRITAL ----------------------*/
   /* public function dep_dist($dist_id){
        $sql = 'select *
                from _distritales ds
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where ds.dist_id='.$dist_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- REQUERIMIENTOS COMPONENTES ----------------------*/
/*    public function requerimientos_componentes($com_id){
        $sql = 'select *
                from vproy_insumo_componente_programado
                where com_id='.$com_id.'
                order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- GET REQUERIMIENTO --------*/
/*    function get_requerimiento($ins_id){
        $sql = 'select *
                from insumos i
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where i.ins_id='.$ins_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------ LINEAMIENTO RED DE ACCIONES A COMPONENTES (2018) -------------*/
/*    public function get_red_com($com_id){
        $sql = 'select *
                from poa_accionmplazo pam
                Inner Join poa as poa On poa.poa_id=pam.poa_id
                Inner Join aperturaprogramatica as aper On poa.aper_id=aper.aper_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=pam.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _resultado_mplazo as re On re.acc_id=ae.acc_id
                Inner Join _pterminal_mplazo as pt On pt.rm_id=re.rm_id
                Inner Join _pterminal_mplazo_programado as ptp On ptp.ptm_id=pt.ptm_id
                Inner Join _productos as prod On prod.pt_id=pt.ptm_id
                where poa.poa_gestion='.$this->gestion.' and ae.acc_estado!=\'3\' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') 
                and ptp.g_id='.$this->gestion.' and prod.com_id='.$com_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- REQUERIMIENTOS PRODUCTOS (anterior) -----------*/
/*    public function requerimientos_productos2($prod_id){
        $sql = 'select *
                from vproy_insumo_producto_programado
                where prod_id='.$prod_id.'
                order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

/*    public function requerimientos_productos($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';
            $query = $this->db->query($sql);
        
        return $query->result_array();
    }*/
    /*---------------------------------------------------------*/

    /*----------- Items Seleccionados en la tabla temporal -----------*/
/*    public function get_temporal_cert($tpral_id){
        $sql = 'select *
                from temporal_cpoas t
                where t.tpral_id='.$tpral_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

/*    public function list_temporal_requerimientos_productos($temp_id,$prod_id){
        $sql = 'select *
                from temporal_cpoas t
                Inner Join insumos_temporal as ip On ip.tpral_id=t.tpral_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where t.tpral_id='.$temp_id.' and t.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/



    /*---------- LINEAMIENTO RED DE ACCIONES A PRODUCTOS (2018) -----------*/
/*    public function get_red_prod($prod_id){
        $sql = 'select *
                from poa_accionmplazo pam
                Inner Join poa as poa On poa.poa_id=pam.poa_id
                Inner Join aperturaprogramatica as aper On poa.aper_id=aper.aper_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=pam.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _resultado_mplazo as re On re.acc_id=ae.acc_id
                Inner Join _pterminal_mplazo as pt On pt.rm_id=re.rm_id
                Inner Join _pterminal_mplazo_programado as ptp On ptp.ptm_id=pt.ptm_id
                Inner Join _productos as prod On prod.pt_id=pt.ptm_id
                where poa.poa_gestion='.$this->gestion.' and ae.acc_estado!=\'3\' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') 
                and ptp.g_id='.$this->gestion.' and prod.prod_id='.$prod_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- REQUERIMIENTOS ACTIVIDAD ----------------------*/
/*    public function requerimientos_actividad($act_id){
        $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where ia.act_id='.$act_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- REQUERIMIENTOS ACTIVIDAD ----------------------*/
/*    public function requerimientos_actividad2($act_id){
        $sql = 'select *
                from vproy_insumo_directo_programado
                where act_id='.$act_id.'
                order by par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------------- LINEAMIENTO RED DE ACCIONES A ACTIVIDADES (2018) -------------*/
/*    public function get_red_act($act_id){
        $sql = 'select *
                from poa_accionmplazo pam
                Inner Join poa as poa On poa.poa_id=pam.poa_id
                Inner Join aperturaprogramatica as aper On poa.aper_id=aper.aper_id
                Inner Join _acciones_estrategicas as ae On ae.acc_id=pam.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _resultado_mplazo as re On re.acc_id=ae.acc_id
                Inner Join _pterminal_mplazo as pt On pt.rm_id=re.rm_id
                Inner Join _pterminal_mplazo_programado as ptp On ptp.ptm_id=pt.ptm_id
                Inner Join _productos as prod On prod.pt_id=pt.ptm_id
                Inner Join _actividades as act On act.prod_id=prod.prod_id
                where poa.poa_gestion='.$this->gestion.' and ae.acc_estado!=\'3\' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.') 
                and ptp.g_id='.$this->gestion.' and act.act_id='.$act_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET APERTURA PROGRAMATICA PADRE ----------------------*/
/*    public function get_apertura_programatica($aper_id){
        $sql = 'select *
                from aperturaprogramatica
                where aper_id='.$aper_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- LISTA CERTIFICADO ITEM SEGUN INSUMO ----------------------*/
/*    public function list_cert_item($ins_id){
        $sql = 'select *
                from cert_ifin_prog_mes cip
                Inner Join ifin_prog_mes as ip On ip.ipm_id=cip.ipm_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET CERTIFICADO POA ----------------------*/
/*    public function get_certificado_poa($cpoa_id){
        $sql = 'select *
                from certificacionpoa cp
                Inner Join funcionario as f On f.fun_id=cp.fun_id
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                where cp.cpoa_id='.$cpoa_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- GET ITEM Y MONTO CERTIFICADO POR INSUMO  ----------------------*/
/*    public function get_ins_certificado($ins_id,$ifin_id){
        $sql = 'select *
                from vins_certificado
                where ins_id='.$ins_id.' and ifin_id='.$ifin_id.' ';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_insumo_certificado_temporal($ins_id){
        $sql = 'select *
                from vins_certificado
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET ITEM CERTIFICADO ----------------------*/
/*    public function get_item($ifin_id,$mes_id){
        $sql = 'select *
                from cert_ifin_prog_mes cip
                Inner Join ifin_prog_mes as ip On cip.ipm_id=ip.ipm_id 
                where ifin_id='.$ifin_id.' and mes_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET ITEM CERTIFICADO POR MES ----------------------*/
/*    public function get_item_certificado($cpoa_id,$ifin_id,$mes_id){
        $sql = 'select *
                from certificacionpoadetalle cp
                Inner Join (select cip.cpoad_id,ip.ifin_id,ip.mes_id
                from cert_ifin_prog_mes cip
                Inner Join ifin_prog_mes as ip On ip.ipm_id=cip.ipm_id ) as cip On cip.cpoad_id=cp.cpoad_id 
                where cp.cpoa_id='.$cpoa_id.' and cip.ifin_id='.$ifin_id.' and cip.mes_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET ITEM CERTIFICADO (TODOS)----------------------*/
/*    public function list_item_certificado($cpoa_id,$ifin_id){
        $sql = 'select *
                from certificacionpoadetalle cp
                Inner Join (select cip.cpoad_id,ip.ifin_id,ip.mes_id
                from cert_ifin_prog_mes cip
                Inner Join ifin_prog_mes as ip On ip.ipm_id=cip.ipm_id ) as cip On cip.cpoad_id=cp.cpoad_id 
                where cp.cpoa_id='.$cpoa_id.' and cip.ifin_id='.$ifin_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- LISTA CERTIFICADO POA DETALLE (CAMBIADO)----------------------*/
/*    public function lista_certificado_poa_detalle($cpoa_id){
        $sql = 'select *
                from vcertificado_insumo
                where cpoa_id='.$cpoa_id.' and g_id='.$this->gestion.'
                order by ins_id, par_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET ITEM CERTIFICADO POA DETALLE----------------------*/
   /* public function get_certificado_item_detalle($cpoa_id,$ins_id){
        $sql = 'select *
                from vcertificado_insumo
                where ins_id='.$ins_id.' and cpoa_id='.$cpoa_id.' and g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET CERTIFICADO POA DETALLE----------------------*/
   /* public function get_certificado_poa_detalle($cpoa_id,$ins_id){
        $sql = 'select *
                from certificacionpoadetalle
                where ins_id='.$ins_id.' and cpoa_id='.$cpoa_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- LISTA CERTIFICADO POA DETALLE----------------------*/
   /* public function list_certificado_poa_detalle($cpoa_id){
        $sql = 'select *
                from certificacionpoadetalle cert
                Inner Join insumos as i On i.ins_id=cert.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where cert.cpoa_id='.$cpoa_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by i.ins_id, par.par_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- LISTA CERTIFICADO POA DETALLE ITEM----------------------*/
    /*public function list_certificado_poa_item($cpoad_id){
        $sql = 'select *
                from cert_ifin_prog_mes
                where cpoad_id='.$cpoad_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

   /* public function get_prog_mes_cert($cpoad_id,$ipm_id){
        $sql = 'select *
                from cert_ifin_prog_mes
                where cpoad_id='.$cpoad_id.' and ipm_id='.$ipm_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /* ------ SUMA CERTIFICADO POR ITEM -------*/
   /* public function suma_prog_item($cpoad_id){
        $sql = 'select SUM(ipr.ipm_fis) as monto
                from cert_ifin_prog_mes cip
                Inner Join ifin_prog_mes as ipr On ipr.ipm_id=cip.ipm_id
                where cip.cpoad_id='.$cpoad_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--- LISTA DE OPERACIONES APROBADAS PARA CERTIFICACION POA (2019) ---*/
   /* public function list_operaciones($prog,$est_proy,$tpf,$tp_id){
        $dep=$this->dep_dist($this->dist);
        if($this->adm==1){
            if($this->rol==1){
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.abrev,ds.dist_distrital,pfe.*,te.*,a.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                        Inner Join funcionario as f On f.fun_id=pf.fun_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                        Inner Join unidad_actividad as a On a.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                        where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.'
                        ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
            else{
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.dist_distrital,pfe.*,te.*,a.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                        Inner Join funcionario as f On f.fun_id=pf.fun_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                        Inner Join unidad_actividad as a On a.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                        where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.' and f.fun_id='.$this->fun_id.'
                        ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
        }  
        elseif($this->adm==2){
                if($this->rol==1){
                    if($this->dist_tp==1){
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.abrev,ds.dist_distrital,pfe.*,te.*,a.*
                                from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                                Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                                Inner Join funcionario as f On f.fun_id=pf.fun_id
                                Inner Join _departamentos as d On d.dep_id=p.dep_id
                                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                                Inner Join unidad_actividad as a On a.act_id=p.act_id
                                Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                                where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.' and d.dep_id='.$dep[0]['dep_id'].'
                                ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
                    elseif($this->dist_tp==0){
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.abrev,ds.dist_distrital,pfe.*,te.*,a.*
                                from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                                Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                                Inner Join funcionario as f On f.fun_id=pf.fun_id
                                Inner Join _departamentos as d On d.dep_id=p.dep_id
                                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                                Inner Join unidad_actividad as a On a.act_id=p.act_id
                                Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                                where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.' and d.dep_id='.$dep[0]['dep_id'].' and ds.dist_id='.$this->dist.'
                                ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
                    else{
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                                apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.abrev,ds.dist_distrital,pfe.*,te.*,a.*
                                from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                                Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                                Inner Join funcionario as f On f.fun_id=pf.fun_id
                                Inner Join _departamentos as d On d.dep_id=p.dep_id
                                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                                Inner Join unidad_actividad as a On a.act_id=p.act_id
                                Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                                where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.' and d.dep_id='.$dep[0]['dep_id'].' and ds.dist_id='.$this->dist.'
                                ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
            }
            else{

                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_id,ds.abrev,ds.dist_distrital,pfe.*,te.*,a.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                            Inner Join funcionario as f On f.fun_id=pf.fun_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id

                            Inner Join unidad_actividad as a On a.act_id=p.act_id
                            Inner Join v_tp_establecimiento as te On te.te_id=a.te_id

                            where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'and p.tp_id='.$tp_id.' and d.dep_id='.$dep[0]['dep_id'].' and f.fun_id='.$this->fun_id .'
                            ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- LISTA DE CERTIFICADOS ----------------------*/
   /* public function list_certificados($tp_cert){
        $dep=$this->dep_dist($this->dist);
        if($this->adm==1){
            if($this->rol==1){
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_estado='.$tp_cert.' and cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').'
                order by cpoa_id asc';
            }
            else{
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_estado='.$tp_cert.' and cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and fu.fun_id='.$this->fun_id.'
                order by cpoa_id asc';
            }
        }
        elseif($this->adm==2){
            if($this->rol==1){
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_estado='.$tp_cert.' and cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and p.dep_id='.$dep[0]['dep_id'].'
                order by cpoa_id asc';
            }
            else{
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_estado='.$tp_cert.' and cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and fu.fun_id='.$this->fun_id .' and dep_id='.$dep[0]['dep_id'].'
                order by cpoa_id asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- LISTA DE CERTIFICADOS POAS GENERADOS -------------------------*/
     /*public function list_certificados_generados(){
        $dep=$this->dep_dist($this->dist);
        if($this->adm==1){
            if($this->rol==1){
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and cp.cpoa_estado!=\'3\'
                order by cpoa_id asc';
            }
            else{
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and fu.fun_id='.$this->fun_id.' and cp.cpoa_estado!=\'3\'
                order by cpoa_id asc';
            }
        }
        elseif($this->adm==2){
            if($this->rol==1){
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and p.dep_id='.$dep[0]['dep_id'].' and cp.cpoa_estado!=\'3\'
                order by cpoa_id asc';
            }
            else{
                $sql = 'select *
                from certificacionpoa cp
                Inner Join _proyectos as p On p.proy_id=cp.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where cp.cpoa_gestion='.$this->session->userData('gestion').' and tap.aper_gestion='.$this->session->userData('gestion').' and fu.fun_id='.$this->fun_id .' and dep_id='.$dep[0]['dep_id'].' and cp.cpoa_estado!=\'3\'
                order by cpoa_id asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    
    /*--------------------- INSUMO PROGRAMADO MES ----------------------*/
/*    public function insumo_programado_mes($ifin_id){
        $sql = 'select *
                from ifin_prog_mes ipm
                Inner Join mes as m On ipm.mes_id=m.m_id
                where ipm.ifin_id='.$ifin_id.' and ipm_cpoa!=\'1\'
                order by mes_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------- GET INSUMO PROGRAMADO MES ----------------------*/
/*    public function get_insumo_programado_mes($ifin_id,$mes_id){
        $sql = 'select *
                from ifin_prog_mes ipm
                Inner Join mes as m On ipm.mes_id=m.m_id
                where ipm.ifin_id='.$ifin_id.' and mes_id='.$mes_id.' and ipm_cpoa!=\'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------------------- RECUPERA CERTIFICADOS PARA EDITADO ----------------------*/
/*    public function certificados_generados($cpoa_id){
        $sql = 'select cp.cpoa_id as id, Min(cpd.ins_id) as ins_id, aper_id
                from certificacionpoa cp
                Inner Join certificacionpoadetalle as cpd On cpd.cpoa_id=cp.cpoa_id
                where cp.cpoa_id='.$cpoa_id.'
                group by id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
*/

    /*-------------------- REQUERIMIENTOS SELECCIONADOS PARA LA MODIFICACION DE LA CERTIFICACION --------------------*/
   /* public function requerimientos_certificados($ins_id,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
               $sql = 'select *
                    from vproy_insumo_directo_programado
                    where ins_id='.$ins_id.'';
              
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select *
                    from vproy_insumo_componente_programado
                    where ins_id='.$ins_id.'';
                
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select *
                    from vproy_insumo_producto_programado
                    where ins_id='.$ins_id.'';

        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*=================================== LISTA DE PROYECTOS SEGUN SU TIPO PROY PARA EL ADMINISTRADOR ====================================*/
  /*  public function proyecto_n($tp,$gestion){
        $sql = 'select count(p.*)as nro, p.*,tp.*,fu.*,tap.*,f.*,fg.*,fu.*
            from _proyectos as p
            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
            Inner Join (select apg.aper_id ,apy.proy_id ,apg.aper_programa,apg.aper_gestion,apg.aper_proyecto,apg.aper_actividad from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
            Inner Join (select pf.fun_id,pf.proy_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_id,u.uni_unidad
            from _proyectofuncionario pf
            Inner Join funcionario as f On pf.fun_id=f.fun_id
            Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
            where pf.pfun_tp=1) as fu On fu.proy_id=p.proy_id
        Inner Join (select pfec_id,proy_id, pfec_fecha_inicio,pfec_fecha_fin,pfec_descripcion,pfec_ptto_fase from _proyectofaseetapacomponente where pfec_estado!=\'3\')as f On f.proy_id=p.proy_id
        Inner Join (select pfec_id,pfecg_ppto_total, pfecg_ppto_ejecutado from ptto_fase_gestion where g_id='.$gestion.')as fg On fg.pfec_id=f.pfec_id
        Inner Join _componentes as c On c.pfec_id=f.pfec_id
        Inner Join _productos as pr On pr.com_id=c.com_id
        Inner Join _actividades as ac On ac.prod_id=pr.prod_id
            where p.tp_id='.$tp.' and tap.aper_proy_estado=\'4\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' GROUP BY p.proy_id,tp.tp_id,tap.aper_id,tap.aper_gestion,tap.proy_id,tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,f.pfec_id,f.proy_id,
            f.pfec_fecha_inicio,f.pfec_fecha_fin,f.pfec_descripcion,f.pfec_ptto_fase,fg.pfec_id,fg.pfecg_ppto_total,fg.pfecg_ppto_ejecutado,fu.fun_id,fu.proy_id,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,
            fu.uni_id,fu.uni_unidad';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*============================================================================================== ===========================================*/
  
 /*======================================================= LISTA DE PROYECTOS SEGUN SU TIPO PROY PARA EL FUNCIONARIO ====================================*/
   /* public function proyecto_n_fun_id($tp,$prog,$fun_id,$gestion){
        $sql = 'SELECT count(p.*)as nro, p.*,tp.*,fu.*,tap.*,f.*,fg.*,fu.*
            from _proyectos as p
            Inner Join _tipoproyecto as tp On p."tp_id"=tp."tp_id"
            Inner Join (select apg."aper_id" ,apy."proy_id" ,apg."aper_programa",apg."aper_gestion",apg."aper_proyecto",apg."aper_actividad" from aperturaprogramatica as apg, aperturaproyectos as apy where apy."aper_id"=apg."aper_id") as tap On p."proy_id"=tap."proy_id"
            Inner Join (select pf."fun_id",pf."proy_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_id",u."uni_unidad"
            from _proyectofuncionario pf
            Inner Join funcionario as f On pf."fun_id"=f."fun_id"
            Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
            where pf."pfun_tp"=\'1\') as fu On fu."proy_id"=p."proy_id"
        Inner Join (select pfec_id,proy_id, pfec_fecha_inicio,pfec_fecha_fin,pfec_descripcion,pfec_ptto_fase from _proyectofaseetapacomponente where pfec_estado=\'1\')as f On f."proy_id"=p."proy_id"
        Inner Join (select pfec_id,pfecg_ppto_total, pfecg_ppto_ejecutado from ptto_fase_gestion where g_id='.$gestion.')as fg On fg.pfec_id=f.pfec_id
        Inner Join _componentes as c On c.pfec_id=f.pfec_id
        Inner Join _productos as pr On pr.com_id=c.com_id
        Inner Join _actividades as ac On ac.prod_id=pr.prod_id
            where p.tp_id='.$tp.' and tap.aper_programa=\''.$prog.'\' and fu.fun_id='.$fun_id.' and tap.aper_proy_estado=\'4\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' GROUP BY p.proy_id,tp.tp_id,tap.aper_id,tap.proy_id,tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,f.pfec_id,f.proy_id,
            f.pfec_fecha_inicio,f.pfec_fecha_fin,f.pfec_descripcion,f.pfec_ptto_fase,fg.pfec_id,fg.pfecg_ppto_total,fg.pfecg_ppto_ejecutado,fu.fun_id,fu.proy_id,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,
            fu.uni_id,fu.uni_unidad,tap.aper_gestion';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*============================================================================================== ===========================================*/

    /*================================ ESTADO DEL PROYECTO ==============================*/  
   /* public function proy_estado(){
        $sql = 'select *
                from _estadoproyecto
                order by ep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*=====================================================================================*/

    /*=================================== LOBJETIVO ESTRATEGICO ====================================*/
 /*   public function oe($obje_id)
    {
        $this->db->from('objetivosestrategicos');
        $this->db->where('obje_id', $obje_id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*=================================== LISTA DE PRODUCTOS EJECUTADO GESTION  ====================================*/
   /* public function oe_ejecutado($obje_id,$gestion)
    {
        $this->db->from('obje_ejec_gestion');
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*=================================== LISTA DE OBJETIVOS ESTRATEGICOS PROGRAMADO ====================================*/
   /* public function nro_oe_programado($obje_id,$gestion)
    {
        $this->db->from('obje_prog_gestion');
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gestion);
        $query = $this->db->get();
        return $query->num_rows();

    }*/
    /*==============================================================================================================*/ 
    /*=================================== LISTA DE PRODUCTOS EJECUTADO GESTION  ====================================*/
  /*  public function nro_oe_ejecutado($obje_id,$gestion)
    {
        $this->db->from('obje_ejec_gestion');
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gestion);
        $query = $this->db->get();
        return $query->num_rows();
    }*/

    /*============================ BORRA DATOS DE OBJETIVOS GESTION =================================*/
  /*  public function delete_oe_id($obje_id,$gest){ 
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gest);
        $this->db->delete('obje_ejec_gestion'); 
    }*/
    /*====================================================================================================*/
    /*=================================== AGREGAR OE  EJECUTADO GESTION ====================================*/
   /* public function add_oe_ejec($obje_id,$gestion,$e,$ea,$eb)
    {
        $data = array(
            'obje_id' => $obje_id,
            'oem_ejecutado' => $e,
            'oem_ejecutado_a' => $ea,
            'oem_ejecutado_b' => $eb,
            'g_id' => $gestion,
        );
        $this->db->insert('obje_ejec_gestion',$data);
    }*/
    /*==============================================================================================================*/
    /*================================= GET EJECUCION OE ======================================*/
   /* public function get_ejecucion_oe($obje_id)
    {
        $this->db->from('oe_ejecucion');
        $this->db->where('obje_id', $obje_id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*================================================================================================*/  
    /*================================= SUPERVISION Y EVALUACIO ======================================*/
  /*  public function oe_observacion($obje_id,$gestion)
    {
        $this->db->from('oe_observacion');
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*==============================================================================================*/    
    /*=========================== DATOS OBSERVACION EJECUCION ======================================*/
  /*  public function observaciones_id_oe($obs_id){
        $this->db->from('oe_observacion');
        $this->db->where('obs_id', $obs_id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*===============================================================================================*/ 
    /*================================= ARCHIVOS EJECUCION OE ======================================*/
  /*  public function archivos_oe($obje_id,$gest) {
        $this->db->from('oe_adjuntos');
        $this->db->where('obje_id', $obje_id);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*================================================================================================*/ 
    /*================================= ARCHIVO ID ======================================*/
  /*  public function archivos_id_oe($oe_id){
        $this->db->from('oe_adjuntos');
        $this->db->where('oe_id', $oe_id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*================================================================================================*/ 


    /*== LISTA DE ITEMS ANULADOS QUE FUERON REFORMULADOS ==*/
   /* public function lista_items_anulados($cpoaa_id){
        $sql = 'select *
                from insumos_certificados_anulado
                where cpoaa_id='.$cpoaa_id.' 
                order by cpoaad_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- lista de meses certificados anulados ---------*/
  /*  public function list_prog_anulado($cpoaad_id){
        $sql = 'select *
                from cert_prog_mes_anulados
                where cpoaad_id='.$cpoaad_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------- VERIF ITEM MODIFICADO ---------*/
   /* public function verif_item_mod($cpoaa_id,$ins_id){
        $sql = 'select *
          from _insumo_mod_cite ic
          Inner Join _insumo_modificado as im On im.insc_id=ic.insc_id
          where ic.cpoaa_id='.$cpoaa_id.' and im.ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- GET CITE MODIFCADO ---------*/
   /* public function get_cite_mod($cpoaa_id){
        $sql = 'select *
                from _insumo_mod_cite
                where cpoaa_id='.$cpoaa_id.' and insc_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- VERIF SI SE MODIFICO EN LA REFORMLACIO ---------*/
  /*  public function verif_item_reformulado($cpoaa_id){
        $sql = 'select *
          from _insumo_mod_cite ic
          Inner Join _insumo_modificado as im On im.insc_id=ic.insc_id
          where ic.cpoaa_id='.$cpoaa_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- LISTA DE ITEMS ANULADOS ---------*/
   /* public function list_item_anulados($cpoa_id){
        $sql = 'select *
                from vcertificado_insumo_anulado
                where cpoa_id='.$cpoa_id.'
                order by ins_id, par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*--------- id tipo prod/Act - Insumos (2019)---------*/
  /*  public function tipo_id_prod_act($tp,$ins_id){
        if($tp==1){
            $sql = 'select *
                from _insumoactividad ia
                Inner Join _actividades as a On a.act_id=ia.act_id
                Inner Join _productos as p On p.prod_id=a.prod_id
                Inner Join _componentes as c On c.com_id=p.com_id
                where ia.ins_id='.$ins_id.'';
        }
        else{
            $sql = 'select *
                    from _insumoproducto ip
                    Inner Join _productos as p On p.prod_id=ip.prod_id
                    Inner Join _componentes as c On c.com_id=p.com_id
                    where ip.ins_id='.$ins_id.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------- VERIF NRO DE CERT POAS ---------*/
  /*  public function verif_cpoas($dep_id){
        $sql = 'select *
                from cert_poa
                where dep_id='.$dep_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA DE REQERUMIENTOS MODIFICADOS POR CERTIFICACION POA PERO NO APARECEN----*/
   /* public function list_requerimientos_nocertificados($cpoaa_id){
        $sql = 'select *
              from _insumo_mod_cite ic
              Inner Join _insumo_modificado as im On im.insc_id=ic.insc_id
              
              Inner Join insumos as i On i.ins_id=im.ins_id
              Inner Join partidas as par On par.par_id=i.par_id
              Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
              Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
              where ic.cpoaa_id='.$cpoaa_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
            order by i.ins_id, par.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CONSOLIDADO DE CERTIFICACIONES POR REGIONAL----*/
  /*  public function list_edicion_cpoas(){
        $sql = 'select p.dep_id,dep.dep_departamento,count(p.dep_id) nro
                from certificacionpoa cpoa
                Inner Join _proyectos as p On p.proy_id=cpoa.proy_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                where cpoa.cpoa_estado!=\'3\' and cpoa.cpoa_gestion='.$this->gestion.'
                group by p.dep_id,dep.dep_departamento
                order by p.dep_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CPOAS DE UNIDADES ----*/
   /* public function get_list_unidades($dep_id){
        $sql = 'select p.proy_id,p.proy_nombre,tp.tp_tipo,count(p.proy_id) nro
                from certificacionpoa cpoa
                Inner Join _proyectos as p On p.proy_id=cpoa.proy_id
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                where p.dep_id='.$dep_id.' and cpoa.cpoa_estado!=\'3\' and cpoa.cpoa_gestion='.$this->gestion.'
                group by p.proy_id,p.proy_nombre,tp.tp_tipo
                order by p.proy_id,tp.tp_tipo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CONSOLIDADO DE CERTIFICACIONES POR UNIDAD,PROYECTO----*/
   /* public function list_edicion_cpoas_unidad($proy_id){
        $sql = 'select proy_id, count(proy_id) nro
                from certificacionpoa
                where proy_id='.$proy_id.' and cpoa_estado!=3 and cpoa_gestion='.$this->gestion.'
                group by proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA REGIONAL ----*/
  /*  public function list_regional(){
        $sql = 'select *
                from _departamentos
                where dep_id!=\'0\'
                order by dep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CERT EDITADOS POR MES POR REGIONAL ----*/
  /*  public function list_cert_editados_reg_mes($dep_id,$mes_id){
        $sql = 'select *
                from vlista_cert_editadas
                where dep_id='.$dep_id.' and mes='.$mes_id.' and cpoa_gestion='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CERT EDITADOS - REGIONAL ----*/
   /* public function list_cert_editados_reg($dep_id){
        $sql = 'select *
                from vlista_cert_editadas
                where dep_id='.$dep_id.' and cpoa_gestion='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA DE CERTIFICADOS POR UNIDAD  ----*/
   /* public function list_cert_editados_unidad($proy_id,$cpoa_ref){
        // 1: Reformulado
        // 0: Certificado
        $sql = 'select *
                from certificacionpoa
                where proy_id='.$proy_id.' and cpoa_gestion='.$this->gestion.' and cpoa_estado!=\'3\' and cpoa_ref='.$cpoa_ref.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
}
