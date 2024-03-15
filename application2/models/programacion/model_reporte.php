<?php

class Model_reporte extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
   
    /*==================================== ACCIONES PARA LA EVALUACION ==================================*/
    public function list_acciones($prog,$gestion) ///// Lista de Acciones, para su evaluacion
    {
        $sql = 'select tap.*,p.*,fe.*,tp.*,fu.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _proyectofaseetapacomponente as fe On p.proy_id=fe.proy_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and fe.pfec_estado=\'1\' ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=======================================  COSTOS TOTAL DE LA ETAPA - REPORTES ======================================*/ 
    public function nro_costos_total_etapa($pfec_id) 
    {
       $sql = 'SELECT pa."pfec_ptto_fase1" as inicial, (pd."pfec_ptto_fase2"-pa."pfec_ptto_fase1") as diferencia, pd."pfec_ptto_fase2" as vigente
            from _ptto_audi as ptto
            Inner Join (select pfec_id,pfec_ptto_fase1 from _ptto_audi where pfec_id='.$pfec_id.' order by aptto_id asc limit 1) as pa On pa."pfec_id"=ptto."pfec_id"
            Inner Join (select pfec_id,pfec_ptto_fase2 from _ptto_audi where pfec_id='.$pfec_id.' order by aptto_id desc limit 1) as pd On pd."pfec_id"=ptto."pfec_id"
            where ptto.pfec_id='.$pfec_id.' group by pa.pfec_id,pa.pfec_ptto_fase1,pd.pfec_ptto_fase2';

        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function costos_total_etapa($pfec_id) 
    {
       $sql = 'SELECT pa."pfec_ptto_fase1" as inicial, (pd."pfec_ptto_fase2"-pa."pfec_ptto_fase1") as diferencia, pd."pfec_ptto_fase2" as vigente
            from _ptto_audi as ptto
            Inner Join (select pfec_id,pfec_ptto_fase1 from _ptto_audi where pfec_id='.$pfec_id.' order by aptto_id asc limit 1) as pa On pa."pfec_id"=ptto."pfec_id"
            Inner Join (select pfec_id,pfec_ptto_fase2 from _ptto_audi where pfec_id='.$pfec_id.' order by aptto_id desc limit 1) as pd On pd."pfec_id"=ptto."pfec_id"
            where ptto.pfec_id='.$pfec_id.' group by pa.pfec_id,pa.pfec_ptto_fase1,pd.pfec_ptto_fase2';

        $query = $this->db->query($sql);
        return $query->result_array();



    }
    /*=====================================================================================================================*/ 
    /*=======================================  EJECUCION PRESUPUESTARIA ======================================*/ 
    public function nro_ejecucion_presupuestaria($ptofecg_id) 
    {
       $sql = ' SELECT pa."ptto_inicial" as inicial,(pd."ptto_vigente"-pa."ptto_inicial")as diferencia,pd."ptto_vigente" as vigente
                from _ptto_techo_audi as ptto_audi
                Inner Join (select ptofecg_id,ptto_inicial from _ptto_techo_audi where ptofecg_id='.$ptofecg_id.' order by id asc limit 1) as pa On pa.ptofecg_id=ptto_audi.ptofecg_id
                Inner Join (select ptofecg_id,ptto_vigente from _ptto_techo_audi where ptofecg_id='.$ptofecg_id.' order by id desc limit 1) as pd On pd.ptofecg_id=ptto_audi.ptofecg_id
                where ptto_audi.ptofecg_id='.$ptofecg_id.' group by pa.ptofecg_id,pa.ptto_inicial,pd.ptto_vigente';

        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function ejecucion_presupuestaria($ptofecg_id) 
    {
       $sql = ' SELECT pa."ptto_inicial" as inicial,(pd."ptto_vigente"-pa."ptto_inicial")as diferencia,pd."ptto_vigente" as vigente
                from _ptto_techo_audi as ptto_audi
                Inner Join (select ptofecg_id,ptto_inicial from _ptto_techo_audi where ptofecg_id='.$ptofecg_id.' order by id asc limit 1) as pa On pa.ptofecg_id=ptto_audi.ptofecg_id
                Inner Join (select ptofecg_id,ptto_vigente from _ptto_techo_audi where ptofecg_id='.$ptofecg_id.' order by id desc limit 1) as pd On pd.ptofecg_id=ptto_audi.ptofecg_id
                where ptto_audi.ptofecg_id='.$ptofecg_id.' group by pa.ptofecg_id,pa.ptto_inicial,pd.ptto_vigente';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=====================================================================================================================*/ 

 /*======================================== EJECUCION FINANCIERA PROYECTOS MES ===========================================*/
    function nro_get_proy_ejec($proy_id, $gestion){
        $sql = ' SELECT *
                from v_proy_ejec_mes
                where proy_id='.$proy_id.' and gestion='.$gestion.'';

        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function get_proy_ejec_gestion($proy_id, $gestion) ///// Ejecucion Financiera por gestion x
    {
        $this->db->SELECT('*');
        $this->db->FROM('v_proy_ejec_mes');
        $this->db->WHERE('proy_id',$proy_id);
        $this->db->WHERE('gestion',$gestion);
        $query = $this->db->get();
        return $query->result_array();
    }

    function nro_suma_total_financiero($proy_id){
        $sql = ' SELECT *
                from v_proy_ejec_mes
                where proy_id='.$proy_id.'';

        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    function suma_total_financiero($proy_id) ///// Ejecucion Financiera del proyecto de todas sus gestiones
    {
        $this->db->SELECT('*');
        $this->db->FROM('v_proy_ejec_mes');
        $this->db->WHERE('proy_id',$proy_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*=========================================================================================================*/

    /*================================= DATOS OBSERVACION EJECUCION ======================================*/
    public function fase_observacion($id_f,$id_m,$gestion)
    {
        $sql = 'SELECT *
                from fase_observacion
                where pfec_id='.$id_f.' and m_id='.$id_m.' and g_id='.$gestion.' order by fo_id desc limit 1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/ 
    /*============== REPORTES GERENCIALES-REPORTE INSTITUCIONAL-REPORTE POR UNIDAD ===================*/
    public function reporte_por_unidad($uni_id,$gestion)
    {
        $sql = '
        select pf.*,f.*,tap.*,u.*,p.*
                from _proyectofuncionario as pf
                Inner Join funcionario as f On f.fun_id=pf.fun_id
                Inner Join unidadorganizacional as u On u.uni_id=pf.uni_ejec
                Inner Join _proyectos as p On pf.proy_id=p.proy_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                where pf.uni_ejec='.$uni_id.' and pfun_tp=\'1\' and tap.aper_gestion='.$gestion.' and (p.tp_id=\'1\' or p.tp_id=\'3\') and p.estado!=\'3\' ORDER BY p.tp_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/ 
    /*================================= LISTA DE PROYECTOS SEGUN SU TIPO ======================================*/
    public function tp_proy($tp,$gestion)
    {
        $sql = 'select p.*,u.*,f.*,tp.*,tap.*
                from _proyectos as p
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                Inner Join _tipoproyecto as tp On tp.tp_id=p.tp_id
                Inner Join unidadorganizacional as u On u.uni_id=pf.uni_ejec
                Inner Join funcionario as f On f.fun_id=pf.fun_id
                where p.estado!=\'3\' and p.tp_id='.$tp.' and pf.pfun_tp=\'1\' and tap.aper_programa!=\'\' and tap.aper_gestion='.$gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/
    /*================================= UNIDAD EJECUTORA ======================================*/
    public function unidades_ejecutoras($tp,$gestion)
    {
        $sql = 'select ue.uni_id,ue.uni_unidad, count(ue.uni_id)as nro
            from unidadorganizacional as ue
            Inner Join _proyectofuncionario as pf On pf.uni_ejec=ue.uni_id
            Inner Join _proyectos as p On p.proy_id=pf.proy_id
            Inner Join _proyectofaseetapacomponente as fe On p.proy_id=fe.proy_id
            Inner Join (select apy.*, apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id and apg.aper_gestion='.$gestion.') as tap On p.proy_id=tap.proy_id
            where ue.uni_ejecutora=\'1\' and pf.pfun_tp=\'1\' and p.estado!=\'3\' and fe.pfec_estado=\'1\' and p.tp_id='.$tp.'
            group by ue.uni_id
            order by ue.uni_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================================= PROYECTOS POR UNIDAD EJECUTORA ======================================*/
    public function proyecto_ue($uni_id,$gestion,$tp)
    {
        $sql = "SELECT p.*,CASE WHEN  fe.fas_id= 1 THEN 'PINV' WHEN fe.fas_id= 2 THEN 'INV' WHEN fe.fas_id= 3 THEN 'OPE' ELSE '' END AS fase,
                fe.pfec_ptto_fase as ptto, fe.pfec_fecha_inicio ,fe.pfec_id
                from unidadorganizacional as ue
                Inner Join _proyectofuncionario as pf On pf.uni_ejec=ue.uni_id
                Inner Join _proyectos as p On p.proy_id=pf.proy_id
                Inner Join _proyectofaseetapacomponente as fe On p.proy_id=fe.proy_id
                Inner Join (select apy.*, apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id and apg.aper_gestion=".$gestion.") as tap On p.proy_id=tap.proy_id
                where ue.uni_id=".$uni_id." and pf.pfun_tp=1 and p.estado!=3 and fe.pfec_estado=1 and p.tp_id=".$tp." ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==================================== LISTA EJECUCION FINANCIERA =========================================*/
    public function lista_proy_ejec($gestion, $mes_id, $proy_id)
    {
        
        $sql = 'select p.proy_id, p.proy_nombre, r.par_codigo, r.par_nombre, f.ff_codigo, f.ff_sigla, f.ff_descripcion,o.of_codigo, o.of_sigla, o.of_descripcion,
                e.pem_ppto_inicial, e.pem_modif_aprobadas, e.pem_ppto_vigente, e.pem_devengado
                from proy_ejec_mes as e 
                Inner Join _proyectos as p On e.proy_id = p.proy_id
                Inner Join partidas as r On e.par_id = r.par_id 
                Inner Join fuentefinanciamiento as f On e.ff_id = f.ff_id
                Inner Join organismofinanciador as o On e.of_id = o.of_id
                where e.mes_id='.$mes_id.' and e.gestion='.$gestion.' and p.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==================================== UNIDAD ORGANIZACIONAL =========================================*/
    public function get_unidad($uni_id)
    {
        $sql = 'select *
                from unidadorganizacional
                where uni_id='.$uni_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================================= GGET FASE EJECUCION ======================================*/
    public function fase_ejecucion($pfec_id)
    {
        $sql = 'select fe.*,e.*,e1.*
                from fase_ejecucion as fe
                Inner Join _estadoproyecto as e On e.ep_id = fe.estado
                Inner Join dp_estados as e1 On e.ep_id = e1.st_clase  
                where fe.pfec_id='.$pfec_id.' order by fe.m_id desc limit 1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/
    /*================================= VERIF EJECUCION FINANCIERA ======================================*/
    public function verif_ejecucion($id_mes,$gestion)
    {
        $valor='false';
        $sql = ' SELECT *
                from proy_ejec_mes
                where mes_id='.$id_mes.' and gestion='.$gestion.'';

        $query = $this->db->query($sql);
        if($query->num_rows()!=0)
        {
            $valor='true';
        }
        return $valor;
    }
    /*================================================================================================*/
    /*==================================== PROGRAMACION FINANCIERA DIRECTO =========================================*/
    public function programado_financiero_directo($gestion, $proy_id)
    {
        $sql = 'select i.proy_id, SUM(f.ifin_monto) AS monto, SUM(f.enero) AS enero, SUM(f.febrero) AS febrero, SUM(f.marzo) AS marzo, SUM(f.abril) AS abril,
                        SUM(f.mayo) AS mayo, SUM(f.junio) AS junio, SUM(f.julio) AS julio, SUM(f.agosto) AS agosto, SUM(f.septiembre) AS septiembre,
                        SUM(f.octubre) AS octubre, SUM(f.noviembre) AS noviembre, SUM(f.diciembre) AS diciembre
                from vrelacion_proy_prod_act_ins as i
                Inner Join v_ins_financiamiento_programado as f On i.ins_id=f.ins_id
                where i.proy_id='.$proy_id.' and f.ifin_gestion='.$gestion.' group by i.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==================================== PROGRAMACION FINANCIERA DELEGADO =========================================*/
    public function programado_financiero_delegado($gestion, $proy_id)
    {
        $sql = 'select i.proy_id, SUM(f.ifin_monto) AS monto, SUM(f.enero) AS enero, SUM(f.febrero) AS febrero, SUM(f.marzo) AS marzo, SUM(f.abril) AS abril,
                        SUM(f.mayo) AS mayo, SUM(f.junio) AS junio, SUM(f.julio) AS julio, SUM(f.agosto) AS agosto, SUM(f.septiembre) AS septiembre,
                        SUM(f.octubre) AS octubre, SUM(f.noviembre) AS noviembre, SUM(f.diciembre) AS diciembre
                from vrelacion_proy_com_ins as i
                Inner Join v_ins_financiamiento_programado as f On i.ins_id = f.ins_id
                where i.proy_id='.$proy_id.' and f.ifin_gestion='.$gestion.' group by i.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==================================== EJECUCION FINANCIERA =========================================*/
    public function ejecutado_financiero($proy_id, $gestion)
    {
        $this->db->SELECT('*');
        $this->db->FROM('v_proy_ejec_mes');
        $this->db->WHERE('proy_id', $proy_id);
        $this->db->WHERE('gestion', $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*==================================== AVANCE FISICO FINANCIERO =========================================*/
    public function list_programa_proyecto($aper_programa, $gestion)
    {
        $sql = 'select tap.*,p.*,tp.*,fu.*,fe.*
                from _proyectos as p
                 Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                 Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                 Inner Join _proyectofaseetapacomponente as fe On p.proy_id=fe.proy_id
                 Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad
                    from _proyectofuncionario pf
                    Inner Join funcionario as f On pf.fun_id=f.fun_id
                    Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                 where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                 where tap.aper_programa=\''.$aper_programa.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and fe.pfec_estado=\'1\' ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=======================PROYECTOS POR UNIDADES EJECUTORAS ======================================*/
    public function proyectos_unidad($uni_id,$gestion)
    {
        $sql = 'select tap.*,p.*,tp.*,fu.*,fe.*
                from _proyectos as p
                 Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                 Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                 Inner Join _proyectofaseetapacomponente as fe On p.proy_id=fe.proy_id
                 Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_id,u.uni_unidad
                    from _proyectofuncionario pf
                    Inner Join funcionario as f On pf.fun_id=f.fun_id
                    Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                 where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                 where fu.uni_id='.$uni_id.' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and (p.tp_id=\'1\' or p.tp_id=\'3\') and fe.pfec_estado=\'1\' ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=======================LISTA DE UNIDADES EJECUTORAS ======================================*/
    public function unidades_ejecu() 
    {
        $sql = 'select *
                from unidadorganizacional
                where uni_ejecutora=\'1\' and uni_estado!=\'0\' order by uni_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*======================= CONTRATOS ======================================*/
    public function contratos_fase($pfec_id) 
    {
        $sql = 'select ctos.*,cta.*
                from _contratos as ctos
                Inner Join _contratistas as cta On ctos.ctta_id = cta.ctta_id
                where ctos.pfec_id='.$pfec_id.' order by ctos.ctto_id desc limit 1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*======================= SUMA EJECUCION ======================================*/
    public function suma_ejecucion($pfec_id) 
    {
        $sql = ' select SUM(pfecg_ppto_ejecutado) as total
                 from ptto_fase_gestion
                 where pfec_id='.$pfec_id.' and estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}