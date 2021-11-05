<?php
class Model_ptto_sigep extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }
    
    public function list_regionales(){
        $sql = '
            select *
            from _departamentos 
            where dep_id!=\'0\'
            ORDER BY dep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_sp_id($sp_id){
        $sql = 'select *
                from ptto_partidas_sigep
                where sp_id='.$sp_id.' and estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function dep_dist($dist_id){
        $sql = 'select *
                from _distritales ds
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where ds.dist_id='.$dist_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Acciones Operativas Todas -------------*/
    public function list_acciones_operativas(){
        $sql = 'select *
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                where p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\'
                ORDER BY tap.aper_programa,tap.aper_proyecto,tap.aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Lista de Ope. Funcionamiento/pinversion por regional -------------*/
    public function list_operaciones_regional_tp($dep_id,$tp){
        $sql = 'select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,p.proy_nombre,p.dep_id,pg.par_id,pg.partida as codigo,par.par_nombre as nombre,SUM(pg.importe) as monto 
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                Inner Join ptto_partidas_sigep as pg On apg.aper_id=pg.aper_id
                Inner Join partidas as par On par.par_id=pg.par_id 
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and p.tp_id='.$tp.' and p.dep_id='.$dep_id.'
                GROUP BY apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,p.proy_nombre,p.dep_id,pg.par_id,pg.partida,par.par_nombre
                order by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Lista de Acciones Operativas -----------------------*/
    public function acciones_operativas($prog,$tp_id){
        $sql = 'select tap.*,p.*,tp.*,fu.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                    from _proyectofuncionario pf
                    Inner Join funcionario as f On pf.fun_id=f.fun_id
                    Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                    Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                ORDER BY tap.aper_proyecto,tap.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Apertura Programatica hijo ------------------------*/
    public function get_apertura($programa,$proyecto,$actividad){
        $sql = 'select *
                from aperturaprogramatica
                where aper_programa=\''.$programa.'\' and aper_proyecto=\''.$proyecto.'\' and aper_actividad=\''.$actividad.'\' and aper_gestion='.$this->gestion.' and aper_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- Presupuesto Sigep Inicial (Gasto Corriente) ----------*/
    public function get_ptto_sigep($programa,$proyecto,$actividad,$partida){
        $sql = 'select *
                from ptto_partidas_sigep
                where aper_programa=\''.$programa.'\' and aper_proyecto=\''.$proyecto.'\' and aper_actividad=\''.$actividad.'\' and partida=\''.$partida.'\' and g_id='.$this->gestion.' and estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- Presupuesto Sigep Inicial (Proyecto de Inversion) ----------*/
    public function get_ptto_sigep_pi($aper_id,$partida){
        $sql = 'select *
                from ptto_partidas_sigep
                where aper_id='.$aper_id.' and partida=\''.$partida.'\' and g_id='.$this->gestion.' and estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Presupuesto Sigep Aprobado ------------------------*/
    public function get_ptto_sigep_aprobado($programa,$proyecto,$actividad,$partida){
        $sql = 'select *
                from ptto_partidas_sigep_aprobado
                where aper_programa=\''.$programa.'\' and aper_proyecto=\''.$proyecto.'\' and aper_actividad=\''.$actividad.'\' and partida=\''.$partida.'\' and g_id='.$this->gestion.' and estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- Get Presupuesto Aprobado ------------------------*/
    public function get_ptto_aprobado($aper_id,$par_id){
        $sql = 'select aper_id,par_id,SUM(importe) monto
                from ptto_partidas_sigep_aprobado
                where aper_id='.$aper_id.' and par_id='.$par_id.' and g_id='.$this->gestion.' and estado!=\'3\'
                group by aper_id,par_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- Lista de Presupuesto de partidas (Final-Aprobado) ---*/
    public function list_ppto_final_aprobado($aper_id){
        $sql = 'select *
                from ptto_partidas_sigep_aprobado pa
                Inner Join partidas as p On p.par_id=pa.par_id
                where aper_id='.$aper_id.' and g_id='.$this->gestion.'
                order by partida asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Partidas por Apertura ------------------------*/
    public function partidas_proyecto($aper_id){
        $sql = ' select pg.sp_id, pg.par_id,pg.partida,p.par_nombre,pg.importe,pg.ppto_saldo_ncert,pg.ppto_saldo_observacion
                 from ptto_partidas_sigep pg
                 Inner Join partidas as p On p.par_id=pg.par_id
                 where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                 order by pg.partida';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- Get Partida Asignado -----*/
    public function get_partida_asignado_unidad($aper_id,$par_id){
        $sql = ' select pg.sp_id, pg.par_id,pg.partida,p.par_nombre,pg.importe,pg.ppto_saldo_ncert,pg.ppto_saldo_observacion
                 from ptto_partidas_sigep pg
                 Inner Join partidas as p On p.par_id=pg.par_id
                 where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and pg.par_id='.$par_id.'
                 order by pg.partida';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Get Apertura ------------------------*/
    public function apertura_id($aper_id){
        $sql = 'select *
                from aperturaprogramatica
                where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Partidas a Nivel Nacional ------------------------*/
/*    public function partidas_nacional($tp){
        if($tp==1){
            $sql = ' select pg.par_id,pg.partida as codigo ,p.par_nombre as nombre ,SUM(pg.importe) as monto
                     from ptto_partidas_sigep pg
                     Inner Join partidas as p On p.par_id=pg.par_id
                     where pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                     group by pg.par_id,pg.partida,p.par_nombre
                     order by pg.partida';
        }
        else{
            $sql = 'select i.par_id,i.par_codigo as codigo,i.par_nombre as nombre,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                    JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                    where ig.g_id='.$this->gestion.'
                    group by i.par_id,i.par_codigo,i.par_nombre
                    order by i.par_codigo';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

/*    public function suma_partidas_nacional($tp){
        if($tp==1){
            $sql = 'select SUM(pg.importe) as monto
                    from ptto_partidas_sigep pg
                    Inner Join partidas as p On p.par_id=pg.par_id
                    where pg.estado!=\'3\' and pg.g_id='.$this->gestion.'';
        }
        else{
            $sql = 'select SUM(ip.programado_total) as monto
                    from _proyectos as p
                    Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                    Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                    Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                    Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                    Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                    where  p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------------------- Get Partida Nacional ------------------------*/
/*    public function get_partida_nacional($par_id){
        $sql = 'select i.par_id,i.par_codigo as codigo,i.par_nombre as nombre ,SUM(ip.programado_total) as monto
                from vlista_insumos i
                JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                where ig.g_id='.$this->gestion.' and par_id='.$par_id.'
                group by i.par_id,i.par_codigo,i.par_nombre
                order by i.par_codigo';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------------------- Suma Ponderacion Programas ------------------------*/
    public function sum_ponderacion_programas(){
        $sql = 'select SUM(aper_ponderacion) as ponderacion
                from aperturaprogramatica
                where aper_gestion='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================================================== PROGRAMAS ===================================================*/
    /*-------------------- Partidas Programas ------------------------*/
/*    public function partidas_programas($prog,$tp){
        if($tp==1){
            $sql = 'select sig.aper_programa,sig.par_id,sig.partida as codigo,par.par_nombre as nombre,SUM(sig.importe) as monto
                from  ptto_partidas_sigep as sig 
                Inner Join partidas as par On par.par_id=sig.par_id
                where sig.aper_programa=\''.$prog.'\' and sig.estado!=3 and sig.g_id='.$this->gestion.'
                group by sig.aper_programa,sig.par_id,sig.partida,par.par_nombre
                order by sig.partida';
        }
        else{
            $sql = 'select apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.'
                group by apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre
                order by i.par_codigo';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

/*    public function suma_partidas_programas($prog,$tp){
        if($tp==1){
            $sql = 'select sig.aper_programa,SUM(sig.importe) as monto
                from  ptto_partidas_sigep as sig 
                Inner Join partidas as par On par.par_id=sig.par_id
                where sig.aper_programa=\''.$prog.'\' and sig.estado!=3 and sig.g_id='.$this->gestion.'
                group by sig.aper_programa';
        }
        else{
            $sql = 'select apg.aper_programa,SUM(ip.programado_total) as monto
                    from _proyectos as p
                    Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                    Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                    Inner Join insumos as i on i.aper_id = apg.aper_id
                    Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                    Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                    where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.'
                    group by apg.aper_programa';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

/*    public function suma_partidas_regiones($dep_id){
         $sql = 'select p.dep_id,SUM(ip.programado_total) as monto
                    from _proyectos as p
                    Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                    Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                    Inner Join insumos as i on i.aper_id = apg.aper_id
                    Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                    Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                    where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.' and p.dep_id='.$dep_id.'
                    group by p.dep_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*-------------------- Get Partida Programas ------------------------*/
/*    public function get_partida_programa($prog,$par_id){
        $sql = 'select apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.' and i.par_id='.$par_id.'
                group by apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*=================================== UNIDAD EJECUTORA ====================================*/
/*    public function partidas_accion($aper_id,$tp){
        if($tp==1){
            $sql = 'select pg.par_id,pg.partida as codigo,p.par_nombre as nombre,SUM(pg.importe) as monto 
                    from ptto_partidas_sigep pg
                    Inner Join partidas as p On p.par_id=pg.par_id
                    where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by pg.par_id,pg.partida,p.par_nombre,pg.importe
                    order by pg.partida';
        }
        else{
            $sql = 'select i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                    Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                    where i.aper_id='.$aper_id.' and ig.g_id='.$this->gestion.' and i.aper_id!=\'0\'
                    group by i.aper_id,i.par_id, i.par_codigo, i.par_nombre
                    order by i.par_codigo';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO (2019 - 2020 - 2021) -----*/
    public function suma_ptto_accion($aper_id,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select pg.aper_id,SUM(pg.importe) as monto,SUM(pg.ppto_saldo_ncert) saldo
                    from ptto_partidas_sigep pg
                    Inner Join partidas as p On p.par_id=pg.par_id
                    where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by pg.aper_id';
        }
        else{
            $sql = 'select i.aper_id, SUM(i.ins_costo_total) as monto
                    from vlista_insumos i
                    where i.aper_id='.$aper_id.'
                    group by i.aper_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- MONTO PROGRAMADO - PROYECTOS DE INVERSION -----*/
    public function suma_ptto_pinversion($proy_id){
        $sql = '
                select pfe.proy_id,SUM(i.ins_costo_total) as monto
                from _proyectofaseetapacomponente pfe
                Inner Join insumos as i On i.aper_id=pfe.aper_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                where pfe.proy_id='.$proy_id.' and pfe.estado!=\'3\' and i.ins_estado!=\'3\' and pfe.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.'
                group by pfe.proy_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR APERTURA PROGRAMADO - REGIONAL (2020) -----*/
    public function suma_ptto_apertura($aper_programa,$dep_id,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = '';
        }
        else{
            $sql = 'select apg.aper_programa ,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaprogramatica as apg on apg.aper_id = i.aper_id
                    Inner Join aperturaproyectos as ap on ap.aper_id = apg.aper_id
                    Inner Join _proyectos as p on p.proy_id = ap.proy_id
                    
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.dep_id='.$dep_id.' and apg.aper_programa=\''.$aper_programa.'\'
            
                    group by apg.aper_programa';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR APERTURA PROGRAMADO - REGIONAL por tipo de operacion (2020) -----*/
    public function suma_ptto_apertura_tp($aper_programa,$dep_id,$tp,$tp_id){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = '';
        }
        else{
            $sql = 'select apg.aper_programa ,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaprogramatica as apg on apg.aper_id = i.aper_id
                    Inner Join aperturaproyectos as ap on ap.aper_id = apg.aper_id
                    Inner Join _proyectos as p on p.proy_id = ap.proy_id
                    
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.dep_id='.$dep_id.' and apg.aper_programa=\''.$aper_programa.'\' and p.tp_id='.$tp_id.'
            
                    group by apg.aper_programa';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR APERTURA PROGRAMADO - NACIONAL (2020) -----*/
    public function suma_ptto_apertura_Nacional($aper_programa,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = '';
        }
        else{
            $sql = 'select apg.aper_programa ,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaprogramatica as apg on apg.aper_id = i.aper_id
                    Inner Join aperturaproyectos as ap on ap.aper_id = apg.aper_id
                    
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_programa=\''.$aper_programa.'\'
            
                    group by apg.aper_programa';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO (2020) -----*/
    public function suma_ptto_poa($aper_id,$tp){
        if($tp==1){
            $sql = 'select pg.aper_id,SUM(pg.importe) as monto 
                    from ptto_partidas_sigep pg
                    Inner Join partidas as p On p.par_id=pg.par_id
                    where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by pg.aper_id';
        }
        else{
            $sql = 'select i.aper_id, SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where i.aper_id='.$aper_id.' 
                    group by i.aper_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- Get Partida Programado - gasto corriente ---*/
    public function get_partida_accion($aper_id,$par_id){
        $sql = 'select i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from vlista_insumos i
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where i.aper_id='.$aper_id.' and i.par_id='.$par_id.'
                group by i.aper_id,i.par_id, i.par_codigo, i.par_nombre';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- Get Partida Programado - Proyecto de Inversion---*/
    public function get_partida_programado_pi($proy_id,$par_id){
        $sql = 'select pfe.proy_id,i.par_id, par.par_codigo as codigo, par.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectofaseetapacomponente pfe
                
                Inner Join _componentes as c On pfe.pfec_id=c.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _insumoproducto as ipr On ipr.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ipr.ins_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where pfe.proy_id='.$proy_id.' and i.par_id='.$par_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and apg.aper_gestion='.$this->gestion.'
                group by pfe.proy_id,i.par_id, par.par_codigo, par.par_nombre';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Get Partida Asignado ------------*/
    public function get_partida_asignado_sigep($aper_id,$par_id){
        $sql = '
                select pg.aper_id,pg.par_id, p.par_codigo as codigo, p.par_nombre as nombre, SUM(pg.importe) as monto,pg.ppto_saldo_ncert
                from ptto_partidas_sigep pg
                Inner Join partidas as p On p.par_id=pg.par_id
                where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and pg.par_id='.$par_id.'
                group by pg.aper_id,pg.par_id, p.par_codigo, p.par_nombre,pg.ppto_saldo_ncert';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*==== CONSOLIDADO PARTIDAS REGIONAL ====*/
    /*-------------------- Partidas a Nivel Regional ------------------------*/
    public function partidas_regional($dep_id,$tp){
        if($tp==1){
            $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo ,par.par_nombre as nombre ,SUM(pg.importe) as monto
                     from ptto_partidas_sigep pg
                     Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id
                     Inner Join _proyectos as p On p.proy_id=ap.proy_id
                     Inner Join partidas as par On par.par_id=pg.par_id
                     where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                     group by p.dep_id,pg.par_id,pg.partida,par.par_nombre
                     order by pg.partida';
        }
        else{
            if($this->gestion!=2020){
                $sql = 'select p.dep_id,i.par_id,i.par_codigo as codigo,i.par_nombre as nombre,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                    JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                    JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                    where p.dep_id='.$dep_id.' and ig.g_id='.$this->gestion.' and p.estado!=\'3\'
                    group by p.dep_id,i.par_id,i.par_codigo,i.par_nombre
                    order by i.par_codigo';
            }
            else{
                $sql = 'select p.dep_id,i.par_id,i.par_codigo as codigo,i.par_nombre as nombre,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where p.dep_id='.$dep_id.'  and p.estado!=\'3\'
                    group by p.dep_id,i.par_id,i.par_codigo,i.par_nombre
                    order by i.par_codigo';
            }
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------Total Partidas a Nivel Regional ------------------------*/
    public function total_partidas_regional($dep_id,$tp){
        if($tp==1){
            $sql = 'select p.dep_id,SUM(pg.importe) as monto
                     from ptto_partidas_sigep pg
                     Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id
                     Inner Join _proyectos as p On p.proy_id=ap.proy_id
                     Inner Join partidas as par On par.par_id=pg.par_id
                     where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                     group by p.dep_id';
        }
        else{

            if($this->gestion!=2020){ // 2019
                $sql = 'select p.dep_id,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                    JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                    JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                    where p.dep_id='.$dep_id.' and ig.g_id='.$this->gestion.' and p.estado!=\'3\'
                    group by p.dep_id';
            }
            else{
                 $sql = 'select p.dep_id,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where p.dep_id='.$dep_id.' and i.ins_gestion='.$this->gestion.'  and i.aper_id!=\'0\' and p.estado!=\'3\'
                    group by p.dep_id';
            }
            
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Get Partida Asignado Regional ------------------------*/
    public function get_partida_asig_regional($dep_id,$par_id){
        $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo ,par.par_nombre as nombre ,SUM(pg.importe) as monto
                     from ptto_partidas_sigep pg
                     Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id
                     Inner Join _proyectos as p On p.proy_id=ap.proy_id
                     Inner Join partidas as par On par.par_id=pg.par_id
                     where p.dep_id='.$dep_id.' and p.estado!=\'3\' and par.par_id='.$par_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                     group by p.dep_id,pg.par_id,pg.partida,par.par_nombre
                     order by pg.partida';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Get Partida Programado Regional ------------------------*/
    public function get_partida_regional($dep_id,$par_id){
        $sql = 'select p.dep_id,i.par_id,i.par_codigo as codigo,i.par_nombre as nombre,SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                     Inner Join _proyectos as p On p.proy_id=ap.proy_id
                    JOIN insumo_gestion ig ON ig.ins_id = i.ins_id
                    JOIN vifin_prog_mes ip ON ip.insg_id = ig.insg_id
                    where p.dep_id='.$dep_id.' and ig.g_id='.$this->gestion.' and par_id='.$par_id.' and p.estado!=\'3\'
                    group by p.dep_id,i.par_id,i.par_codigo,i.par_nombre
                    order by i.par_codigo';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*===== PROGRAMAS =====*/
    /*-------------------- Partidas Programas ------------------------*/
    public function partidas_rprogramas($dep_id,$prog,$tp){
        if($tp==1){
            $sql = 'select p.dep_id,sig.aper_programa,sig.par_id,sig.partida as codigo,par.par_nombre as nombre,SUM(sig.importe) as monto
                from  ptto_partidas_sigep as sig
                Inner Join aperturaproyectos as ap On ap.aper_id=sig.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join partidas as par On par.par_id=sig.par_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and sig.aper_programa=\''.$prog.'\' and sig.estado!=\'3\' and sig.g_id='.$this->gestion.'
                group by p.dep_id,sig.aper_programa,sig.par_id,sig.partida,par.par_nombre
                order by sig.partida';
        }
        else{
            if($this->gestion!=2020){
                $sql = 'select p.dep_id,apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.'
                group by p.dep_id,apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre
                order by i.par_codigo';
            }
            else{
                $sql = 'select p.dep_id,apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' 
                and i.ins_gestion='.$this->gestion.'  and i.aper_id!=\'0\' and pfe.pfec_estado=\'1\'
                group by p.dep_id,apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre
                order by i.par_codigo';
            }
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Total Partidas Programas ------------------------*/
    public function Total_partidas_rprogramas($dep_id,$prog,$tp){
        if($tp==1){
            $sql = 'select p.dep_id,SUM(sig.importe) as monto
                from  ptto_partidas_sigep as sig
                Inner Join aperturaproyectos as ap On ap.aper_id=sig.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join partidas as par On par.par_id=sig.par_id
                where p.dep_id='.$dep_id.' and sig.aper_programa=\''.$prog.'\' and sig.estado!=\'3\' and sig.g_id='.$this->gestion.' and p.estado!=\'3\'
                group by p.dep_id';
        }
        else{
            if($this->gestion!=2020){
                $sql = 'select p.dep_id,SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.'
                group by p.dep_id';
            }
            else{
                $sql = 'select p.dep_id,SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and i.ins_gestion='.$this->gestion.' and i.aper_id!=\'0\'
                group by p.dep_id';
            }
            
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Get Partida Programado programa regional ------------------------*/
    public function get_partida_rprograma($dep_id,$prog,$par_id){
        if($this->gestion!=2020){
            $sql = 'select p.dep_id,apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and ig.g_id='.$this->gestion.' and i.par_id='.$par_id.'
                group by p.dep_id,apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre';
        }
        else{
            $sql = 'select p.dep_id,apg.aper_programa,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id 
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vlista_insumos as i on i.aper_id = apg.aper_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and i.ins_gestion='.$this->gestion.' and i.par_id='.$par_id.'
                group by p.dep_id,apg.aper_programa,i.par_id, i.par_codigo, i.par_nombre';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Get Partida Asignado programa Regional ------------------------*/
    public function get_partida_asig_rprograma($dep_id,$prog,$par_id){
        $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo ,par.par_nombre as nombre ,SUM(pg.importe) as monto
                     from ptto_partidas_sigep pg
                     Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id
                     Inner Join _proyectos as p On p.proy_id=ap.proy_id
                     Inner Join partidas as par On par.par_id=pg.par_id
                     where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.aper_programa=\''.$prog.'\' and par.par_id='.$par_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                     group by p.dep_id,pg.par_id,pg.partida,par.par_nombre
                     order by pg.partida';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ LISTA DE UNIDADES, ESTABLECIMIENTOS, PROYECTOS DE INVERSION -----*/
    public function lista_unidades_region($dep_id,$prog,$tp_id){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,
                    apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id='.$tp_id.'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*========= SUMA DE CODIGO DE PARTIDA (ASIG, PROG)=========*/
    public function sum_codigos_partidas_asig_prog($aper_id,$tp){
        if($tp==1){
            $sql = 'select pg.aper_id,SUM(cast(pg.partida as int)) as sum_cod_partida 
                    from ptto_partidas_sigep pg 
                    Inner Join partidas as par On par.par_id=pg.par_id 
                    where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by pg.aper_id';
        }
        else{
            $sql = 'select cp.aper_id, SUM(cp.codigo)as sum_cod_partida 
                    from (

                        select i.aper_id,i.par_codigo as codigo
                        from vlista_insumos i
                        where i.aper_id!=\'0\'
                        group by i.aper_id, i.par_codigo, i.par_nombre
                        order by i.par_codigo
        
                    ) cp
                    where cp.aper_id='.$aper_id.'
                    group by cp.aper_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*========= SUMA DE CODIGO DE PARTIDA (PROG) pi =========*/
    public function sum_codigos_partidas_asig_prog_pi($proy_id){
        $sql = 'select cp.proy_id, SUM(cp.codigo)as sum_cod_partida 
                    from (

                        select pfe.proy_id,i.par_id, par.par_codigo as codigo
                        from _proyectofaseetapacomponente pfe
                        
                        Inner Join _componentes as c On pfe.pfec_id=c.pfec_id
                        Inner Join _productos as pr On pr.com_id=c.com_id
                        Inner Join _insumoproducto as ipr On ipr.prod_id=pr.prod_id
                        Inner Join insumos as i On i.ins_id=ipr.ins_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=i.aper_id
                        Inner Join partidas as par On par.par_id=i.par_id
                        Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                        where pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and apg.aper_gestion='.$this->gestion.'
                        group by pfe.proy_id,i.par_id, par.par_codigo
        
                    ) cp
                    where cp.proy_id='.$proy_id.'
                    group by cp.proy_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*============ PARTIDAS UNIDAD EJECUTORA POR REGIONAL ============*/
    public function partidas_accion_region($dep_id,$aper_id,$tp){
        if($tp==1){ /// asignado
            $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo,par.par_nombre as nombre,SUM(pg.importe) as monto ,pg.ppto_saldo_ncert as saldo
                    from ptto_partidas_sigep pg 
                    Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id 
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id 
                    Inner Join partidas as par On par.par_id=pg.par_id 
                    where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.aper_id='.$aper_id.'and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by p.dep_id,pg.par_id,pg.partida,par.par_nombre,pg.importe ,pg.ppto_saldo_ncert
                    order by pg.partida';
        }
        else{ /// programado

            $sql = 'select i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(i.ins_costo_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                  
                    where p.dep_id='.$dep_id.' and i.aper_id='.$aper_id.' and i.aper_id!=\'0\'
                    group by i.aper_id,i.par_id, i.par_codigo, i.par_nombre
                    order by i.par_codigo';


            /*$sql = 'select p.dep_id,i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                    from vlista_insumos i
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                   
                    Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                    where p.dep_id='.$dep_id.' and i.aper_id='.$aper_id.' and i.aper_id!=\'0\'
                    group by p.dep_id,i.aper_id,i.par_id, i.par_codigo, i.par_nombre
                    order by i.par_codigo';*/
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*============ PARTIDAS PROYECTO DE INVERSION POR REGIONAL ============*/
    public function partidas_pi_prog_region($dep_id,$proy_id){
        $sql = 'select p.dep_id,p.proy_id,par.par_id,par.par_codigo as codigo, par.par_nombre as nombre, SUM(ip.programado_total) as monto
                from _proyectos p
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On pfe.pfec_id=c.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _insumoproducto as ipr On ipr.prod_id=pr.prod_id
                Inner Join insumos as i On i.ins_id=ipr.ins_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=i.aper_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id
                where p.dep_id='.$dep_id.' and p.proy_id='.$proy_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and apg.aper_gestion='.$this->gestion.'
                group by p.dep_id,p.proy_id,par.par_id, par.par_codigo, par.par_nombre
                order by par.par_codigo';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- Get Partida Accion Regional programado ------------------------*/
    public function get_partida_accion_regional($dep_id,$aper_id,$par_id){
        $sql = 'select i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(i.ins_costo_total) as monto
                from vlista_insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                  
                where i.aper_id='.$aper_id.' and i.aper_id!=\'0\' and par_id='.$par_id.'
                group by i.aper_id,i.par_id, i.par_codigo, i.par_nombre
                order by i.par_codigo';

        /*$sql = 'select p.dep_id,i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(ip.programado_total) as monto
                from vlista_insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                Inner Join vista_temporalidad_insumo as ip on ip.ins_id = i.ins_id

                where p.dep_id='.$dep_id.' and i.aper_id='.$aper_id.' and i.aper_id!=\'0\' and par_id='.$par_id.'
                group by p.dep_id,i.aper_id,i.par_id, i.par_codigo, i.par_nombre';*/
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- Get Partida Accion Regional programado ------------------------*/
    public function get_partida_accion_regional2($dep_id,$aper_id,$par_id){
        $sql = 'select p.dep_id,i.aper_id,i.par_id,SUM(i.ins_costo_total) as monto
                from vlista_insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                where p.dep_id='.$dep_id.' and i.aper_id='.$aper_id.' and par_id='.$par_id.'
                group by p.dep_id,i.aper_id,i.par_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---------- Get Partida Accion Regional Asignado -------------*/
    public function get_partida_asig_accion($dep_id,$aper_id,$par_id){
        $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo,par.par_nombre as nombre,SUM(pg.importe) as monto ,pg.ppto_saldo_ncert as saldo
                    from ptto_partidas_sigep pg 
                    Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id 
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id 
                    Inner Join partidas as par On par.par_id=pg.par_id 
                    where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.aper_id='.$aper_id.' and par.par_id='.$par_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by p.dep_id,pg.par_id,pg.partida,par.par_nombre,pg.importe,pg.ppto_saldo_ncert
                    order by pg.partida';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


/*============ MODULO DE MODIFICACIONES =============*/

    /*-------- Get Cite Presupuesto --------*/
    public function get_cite_techo($cppto_id){
        $sql = 'select *
                from ppto_cite cit
                Inner Join funcionario as fun On fun.fun_id=cit.fun_id 
                where cit.cppto_id='.$cppto_id.' and cit.cppto_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de partidas no seleccionados en sigep --*/
    public function list_partidas_noasig($aper_id){
        $sql = '
        Select * 
        from partidas 
        where not exists (select * from ptto_partidas_sigep where partidas.par_id = ptto_partidas_sigep.par_id and ptto_partidas_sigep.aper_id='.$aper_id.' and ptto_partidas_sigep.estado!=\'3\' ) and partidas.par_depende!=\'0\'
        order by partidas.par_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get techo partida adicionado --------*/
    public function get_add_presupuesto($appto_id){
        $sql = 'select *
                from ppto_add
                where appto_id='.$appto_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- Techo Partida Adcionado --------*/
    public function partida_add_techo($cppto_id){
        $sql = 'select ptto.*,pa.*,par.*
                from ppto_add pa
                Inner Join ptto_partidas_sigep as ptto On ptto.sp_id=pa.sp_id 
                Inner Join partidas as par On par.par_id=ptto.par_id 
                where pa.cppto_id='.$cppto_id.'
                order by appto_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Techo Partida Adcionado --------*/
    public function partida_mod_techo($cppto_id){
        $sql = 'select ptto.*,pm.*,par.*
                from ppto_mod pm
                Inner Join ptto_partidas_sigep as ptto On ptto.sp_id=pm.sp_id
                Inner Join partidas as par On par.par_id=ptto.par_id 
                where pm.cppto_id='.$cppto_id.'
                order by mppto_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Techo Partida Adcionado --------*/
    public function partida_del_techo($cppto_id){
        $sql = 'select ptto.*,pd.*,par.*
                from ppto_del pd
                Inner Join ptto_partidas_sigep as ptto On ptto.sp_id=pd.sp_id
                Inner Join partidas as par On par.par_id=ptto.par_id 
                where pd.cppto_id='.$cppto_id.'
                order by pd.dppto_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
}