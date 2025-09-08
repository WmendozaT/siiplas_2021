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
    

    /// lista ppto asignado 
    public function lista_ppto_asignado(){
        $sql = '
            select *
            from ptto_partidas_sigep
            where g_id='.$this->gestion.'
            order by da,ue, aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /// Get Proyecto
    public function get_proy($proy_id){
        $sql = '
            select *
            from lista_poa_gastocorriente_nacional('.$this->gestion.')
            where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// lista de Regionales incluyendo Oficina Nacional
    public function list_regionales(){
        $sql = '
            select *
            from _departamentos 
            where dep_id!=\'0\'
            ORDER BY dep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// lista de Departamentos menos Oficina Nacional
    public function list_departamentos(){
        $sql = '
            select *
            from _departamentos 
            where dep_id!=\'0\' and dep_id!=\'10\'
            ORDER BY dep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// Get datos partida - ppto_sigep
    public function get_sp_id($sp_id){
        $sql = 'select *
                from ptto_partidas_sigep pg
                Inner Join partidas as p On p.par_id=pg.par_id
                where pg.sp_id='.$sp_id.' and pg.estado!=\'3\'';

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

/////===== CUADRO COMPARATIVO REGIONAL ===
    /*------ Suma Ppto Asignado Sigep Regional 2023-----*/
    public function sum_ppto_asignado_regional($dep_id){
        if($dep_id==0){ //// Institucional
            $sql = 'select SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where partidas_asig.estado!=\'3\'';
        }
        else{ /// Regional
            $sql = 'select p.dep_id,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where p.dep_id='.$dep_id.' and partidas_asig.estado!=\'3\'
                    group by p.dep_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Suma Ppto Programado SIIPLAS  Regional 2023-----*/
    public function sum_ppto_programado_regional($dep_id,$tp_id){
        if($dep_id==0){ //// Institucional
            $sql = 'select SUM(programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')';
        }
        else{ /// Regional
            $sql = 'select dep_id,SUM(programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.')
                    where dep_id='.$dep_id.'
                    group by dep_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ Lista Consolidado detalle de Partidas (Regional, Distrital Asignadas) Institucional (Gasto Corriente / Proyecto de Inversion)-----*/
    public function lista_detalle_regional_distrital_consolidado_partidas_asignadas_nacional($tp_id){
        $sql = 'select poa.dep_id,poa.dep_departamento,poa.dist_id,poa.dist_distrital,poa.par_id,poa.partida, poa.par_nombre, SUM(poa.ppto_partida_asignado_gestion) as ppto_partida_asignado_gestion
                from lista_partidas_ppto_asignadas_gestion_nacional('.$this->gestion.') poa
                where poa.tp_id='.$tp_id.'
                group by poa.dep_id,poa.dep_departamento,poa.dist_id,poa.dist_distrital,poa.par_id,poa.partida, poa.par_nombre
                order by poa.dep_id,poa.dist_id,poa.partida asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista Consolidado Get detalle de Partidas (Proyecto de Inversion)-----*/
    public function get_detalle_regional_distrital_consolidado_partidas_asignadas_nacional_pi($dep_id,$dist_id,$par_id){
        $sql = 'select poa.dep_id,poa.dep_departamento,poa.dist_id,poa.dist_distrital,poa.par_id,poa.partida, poa.par_nombre, SUM(poa.ppto_partida_asignado_gestion) as ppto_partida_asignado_gestion
                from lista_partidas_ppto_asignadas_gestion_nacional('.$this->gestion.') poa
                where poa.tp_id=\'1\' and poa.dep_id='.$dep_id.' and poa.dist_id='.$dist_id.' and poa.par_id='.$par_id.'
                group by poa.dep_id,poa.dep_departamento,poa.dist_id,poa.dist_distrital,poa.par_id,poa.partida, poa.par_nombre
                order by poa.dep_id,poa.dist_id,poa.partida asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista Consolidado detalle de Partidas (Asignadas) Institucional (Gasto Corriente o Proyecto de Inversion)-----*/
    public function lista_detalle_consolidado_partidas_asignadas_nacional($tp_id){
        $sql = 'select poa.par_id,poa.partida, poa.par_nombre, SUM(poa.ppto_partida_asignado_gestion) as ppto_partida_asignado_gestion
                from lista_partidas_ppto_asignadas_gestion_nacional('.$this->gestion.') poa
      
                group by poa.par_id,poa.partida, poa.par_nombre
                order by poa.partida asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*------ Lista Consolidado detalle de Partidas (Asignadas) Institucional (Gasto Corriente o Proyecto de Inversion)-----*/
    public function lista_detalle_consolidado_partidas_asignadas_nacional2(){
        $sql = 'select poa.dep_departamento,poa.dist_distrital,poa.abrev,poa.da,poa.ue,poa.prog,poa.proy,poa.act,poa.aper_id,poa.proy_id,poa.proy_nombre,poa.tipo,poa.proy_sisin,poa.pfec_id,poa.tp_id,ppto.par_id,ppto.partida,ppto.ppto_inicial,ppto.importe,ppto.ppto_saldo_ncert
                from lista_poa_nacional('.$this->gestion.') poa
                Inner Join ptto_partidas_sigep as ppto On ppto.aper_id=poa.aper_id
                order by poa.dep_cod,poa.da,poa.ue,poa.prog,poa.act asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }





    /*------ Lista Consolidado detalle de Partidas (Asignadas) Por Unidad (Gasto Corriente / Proyecto de Inversion)-----*/
    public function lista_detalle_consolidado_partidas_asignadas_unidades($tp_id){
        $sql = 'select *
                from lista_partidas_ppto_asignadas_gestion_nacional('.$this->gestion.')
                where tp_id='.$tp_id.'
                order by dep_id,dist_id,prog,act,partida asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ Lista Partida Ppto Asignado Sigep Regional 2023-----*/
    public function lista_partidas_asignado_regional($dep_id,$tp_id){
        if($dep_id==0){ //// Institucional
            $sql = 'select partidas.par_id,partidas.par_codigo ,partidas.par_nombre ,SUM(partidas_asig.importe) as asignado, SUM(partidas_asig.ppto_saldo_ncert) saldo
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where partidas_asig.estado!=\'3\'
                    group by partidas.par_id,partidas.par_codigo,partidas.par_nombre
                    order by partidas.par_codigo';
        }
        else{ /// Regional
            $sql = 'select p.dep_id,partidas.par_id,partidas.par_codigo ,partidas.par_nombre ,SUM(partidas_asig.importe) as asignado,SUM(partidas_asig.ppto_saldo_ncert) saldo
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where p.dep_id='.$dep_id.' and partidas_asig.estado!=\'3\'
                    group by p.dep_id,partidas.par_id,partidas.par_codigo,partidas.par_nombre
                    order by partidas.par_codigo';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista partida Ppto Programado SIIPLAS  Regional 2023 (vigente)-----*/
    public function lista_partidas_programado_regional($dep_id,$tp_id){
        if($dep_id==0){ //// Institucional
            $sql = 'select p.par_id,p.par_codigo,p.par_nombre,SUM(p.programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.') p
                    group by p.par_id,p.par_codigo,p.par_nombre
                    order by p.par_codigo asc';
        }
        else{ /// Regional
            $sql = 'select p.dep_id,p.par_id,p.par_codigo,p.par_nombre,SUM(p.programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.') p
                    where p.dep_id='.$dep_id.'
                    group by p.dep_id,p.par_id,p.par_codigo,p.par_nombre
                    order by p.par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- Get Partida Asignado Regional (vigente)-------------*/
    public function get_partida_asig_regional($dep_id,$par_id){
        if($dep_id==0){ /// Institucional
            $sql = 'select partidas.par_id,partidas.par_codigo as codigo ,partidas.par_nombre as nombre ,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where partidas.par_id='.$par_id.' and partidas_asig.estado!=\'3\'
                    group by partidas.par_id,partidas.par_codigo,partidas.par_nombre
                    order by partidas.par_codigo';
        }
        else{ /// Regional
            $sql = 'select p.dep_id,partidas.par_id,partidas.par_codigo as codigo ,partidas.par_nombre as nombre ,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join partidas as partidas On partidas.par_id=partidas_asig.par_id
                    where p.dep_id='.$dep_id.' and partidas.par_id='.$par_id.' and partidas_asig.estado!=\'3\'
                    group by p.dep_id,partidas.par_id,partidas.par_codigo,partidas.par_nombre
                    order by partidas.par_codigo';
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Get partida Ppto Programado SIIPLAS  Regional 2023 (vigente)-----*/
    public function get_partidas_programado_regional($dep_id,$tp_id,$par_id){
        if($dep_id==0){ //// Institucional
            $sql = 'select p.par_id,p.par_codigo,p.par_nombre,SUM(p.programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.') p
                    where p.par_id='.$par_id.'
                    group by p.par_id,p.par_codigo,p.par_nombre
                    order by p.par_codigo asc';
        }
        else{ /// Regional
            $sql = 'select p.dep_id,p.par_id,p.par_codigo,p.par_nombre,SUM(p.programado_total) programado
                    from lista_requerimientos_institucional_directo('.$tp_id.','.$this->gestion.') p
                    where p.dep_id='.$dep_id.' and p.par_id='.$par_id.'
                    group by p.dep_id,p.par_id,p.par_codigo,p.par_nombre
                    order by p.par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ Lista de Ppto Asignado por Regional (total)-----*/
    public function lista_ppto_total_asignado_nacional(){
        $sql = 'select p.dep_id,dep.dep_departamento,dep.dep_sigla,SUM(partidas_asig.importe) as asignado,SUM(partidas_asig.ppto_saldo_ncert) saldo
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                    where  and partidas_asig.estado!=\'3\'
                    group by p.dep_id,dep.dep_departamento,dep.dep_sigla';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_ppto_total_programado_regional($dep_id){
        $sql = 'select p.dep_id,SUM(p.programado_total) programado
                from lista_requerimientos_institucional_directo(4,'.$this->gestion.') p
                where p.dep_id='.$dep_id.' 
                group by p.dep_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
//// =====================================

    /*------- Apertura Programatica hijo ----------*/
    public function get_apertura($da,$ue,$programa,$proyecto,$actividad){
        if($this->gestion>2023){ /// poa 2024
            $sql = 'select *
                from lista_poa_nacional('.$this->gestion.')
                where da=\''.$da.'\' and ue=\''.$ue.'\' and prog=\''.$programa.'\' and proy=\''.$proyecto.'\' and act=\''.$actividad.'\'  ';
        }
        else{ /// poa 2023
            $sql = 'select *
                from lista_poa_gastocorriente_nacional('.$this->gestion.')
                where dep_cod=\''.$da.'\' and dist_cod=\''.$ue.'\' and prog=\''.$programa.'\' and act=\''.$actividad.'\'  ';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- Presupuesto Sigep Inicial (Gasto Corriente) ----------*/
    public function get_ptto_sigep($da,$ue,$programa,$proyecto,$actividad,$partida){
        $sql = 'select *
                from ptto_partidas_sigep
                where da=\''.$da.'\' and ue=\''.$ue.'\' and aper_programa=\''.$programa.'\' and aper_proyecto=\''.$proyecto.'\' and aper_actividad=\''.$actividad.'\' and partida=\''.$partida.'\' and g_id='.$this->gestion.' and estado!=\'3\'';
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
    public function get_ptto_sigep_aprobado($da,$ue,$programa,$proyecto,$actividad,$partida){
        $sql = 'select *
                from ptto_partidas_sigep_aprobado
                where da=\''.$da.'\' and ue=\''.$ue.'\' and aper_programa=\''.$programa.'\' and aper_proyecto=\''.$proyecto.'\' and aper_actividad=\''.$actividad.'\' and partida=\''.$partida.'\' and g_id='.$this->gestion.' and estado!=\'3\'';
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

    /*-- Get Presupuesto Aprobado Final ---*/
    public function get_ppto_aprobado($sp_id){
        $sql = 'select *
                from ptto_partidas_sigep_aprobado pa
                where pa.sp_id='.$sp_id.' and pa.g_id='.$this->gestion.'';
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

    /*-------- Partidas por Apertura (VIGENTE mod techo) --------*/
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


    /*----- MONTO PROGRAMADO - PROYECTOS DE INVERSION (vigente)-----*/
    public function suma_ptto_pinversion($proy_id){
        $sql = 'select poa.proy_id,poa.aper_id,SUM(i.ins_costo_total) as monto
                from lista_poa_pinversion_nacional('.$this->gestion.') poa
                Inner Join insumos as i On i.aper_id=poa.aper_id
                where poa.proy_id='.$proy_id.' and i.ins_estado!=\'3\'
                group by poa.proy_id,poa.aper_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- MONTO ASIGNADO POR PROYECTO 2022 -----*/
    public function get_ppto_asignado_proyecto_gestion($proy_id){
        $sql = '
                select aper_id,proy_id,SUM(ppto_partida_asignado_gestion) ppto_asignado
                from lista_partidas_ppto_asignadas_gestion('.$this->gestion.')
                where proy_id='.$proy_id.'
                group by aper_id,proy_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- EJECUCION DE PRESUPUESTO POR PARTIDA PI (vigente) -----*/
    public function get_monto_ejecutado_ppto_sigep($sp_id,$mes_id){
        $sql = '
            select *
            from ejecucion_financiera_sigep ejec
            Inner Join mes as m On m.m_id=ejec.m_id
            where ejec.sp_id='.$sp_id.' and ejec.m_id='.$mes_id.'';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- GET EJECUCION PARTIDA x MES PI (vigente) -----*/
    public function get_datos_ejecucion_partidas($ejec_ppto_id){
        $sql = '
            select *
            from ejecucion_financiera_sigep ejec
            Inner Join ptto_partidas_sigep as part On part.sp_id=ejec.sp_id
            Inner Join mes as m On m.m_id=ejec.m_id
            Inner Join aperturaproyectos as ap On ap.aper_id=part.aper_id
            Inner Join _proyectos as p On ap.proy_id=p.proy_id
            where ejec.ejec_ppto_id='.$ejec_ppto_id.'';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- EJECUCION DE PRESUPUESTO POR PROYECTO MENSUAL (vigente)-----*/
    public function suma_monto_ejecutado_mes_ppto_sigep($aper_id,$mes_id){
        $sql = '
            select ppto.aper_id,ejec.m_id, SUM(ejec.ppto_ejec) ejecutado_mes
            from ptto_partidas_sigep ppto
            Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
            where ppto.aper_id='.$aper_id.' and ejec.m_id='.$mes_id.' and ppto.estado!=\'3\'
            group by ppto.aper_id,ejec.m_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*----- OBSERVACION A LA EJECUCION DE PRESUPUESTO POR PARTIDA (vigente) -----*/
    public function get_obs_ejecucion_financiera_sigep($sp_id,$mes_id){
        $sql = '
            select *
            from obs_ejecucion_financiera_sigep
            where sp_id='.$sp_id.' and m_id='.$mes_id.'
            order by obs_ejec_id DESC LIMIT 1';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- get Temporalidad Ejecucion Presupuestaria por partida (vigente)-----*/
    public function get_temporalidad_ejec_ppto_partida($sp_id){
        $sql = 'select *
                from v_ejec_ppto_partidas
                where sp_id='.$sp_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ GET suma ppto MES Ejecutado por partida por unidad/proyecto 
    public function get_list_temporalidad_insumo_mes_ejecutado($aper_id,$mes_id){
        $sql = '
                select asig.aper_id,SUM(ejec.ppto_ejec) ppto
                from ejecucion_financiera_sigep ejec
                Inner Join ptto_partidas_sigep as asig On asig.sp_id=ejec.sp_id
                where asig.aper_id='.$aper_id.' and ejec.m_id='.$mes_id.'  and asig.estado!=\'3\'
                group by asig.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




    // ------ GET suma ppto MES Ejecutado por partida por unidad/proyecto 
    public function get_list_temporalidad_insumo_partida_mes_ejecutado($aper_id,$par_id,$mes_id){
        $sql = '
                select asig.par_id,asig.aper_id,SUM(ejec.ppto_ejec) ppto
                from ejecucion_financiera_sigep ejec
                Inner Join ptto_partidas_sigep as asig On asig.sp_id=ejec.sp_id
                where asig.par_id='.$par_id.' and asig.aper_id='.$aper_id.' and ejec.m_id='.$mes_id.'
                group by asig.par_id,asig.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ GET suma ppto Ejecutado al MES por unidad/proyecto 
    public function get_suma_ppto_ejecutado_al_mes_x_unidad($aper_id,$mes_id){
        $sql = '
                select asig.aper_id,SUM(ejec.ppto_ejec) ppto_ejec
                from ejecucion_financiera_sigep ejec
                Inner Join ptto_partidas_sigep as asig On asig.sp_id=ejec.sp_id
                where asig.aper_id='.$aper_id.' and (ejec.m_id>\'0\' and ejec.m_id<='.$mes_id.')
                group by asig.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    // ------ GET suma ppto Ejecutado por partida al MES por unidad/proyecto 
    public function get_suma_ppto_ejecutado_al_mes_x_partida_unidad($aper_id,$par_id,$mes_id){
        $sql = '
                select asig.par_id,asig.aper_id,SUM(ejec.ppto_ejec) ppto_ejec
                from ejecucion_financiera_sigep ejec
                Inner Join ptto_partidas_sigep as asig On asig.sp_id=ejec.sp_id
                where asig.par_id='.$par_id.' and asig.aper_id='.$aper_id.' and (ejec.m_id>\'0\' and ejec.m_id<='.$mes_id.')
                group by asig.par_id,asig.aper_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA MONTO EJECUTADO DE PRESUPUESTO POR PARTIDA (vigente) -----*/
    public function suma_monto_ppto_ejecutado_partida($sp_id){
        $sql = '
            select ejec.sp_id,par.par_id,SUM(ejec.ppto_ejec) ejecutado
            from ejecucion_financiera_sigep ejec
            Inner Join ptto_partidas_sigep as ppto On ppto.sp_id=ejec.sp_id
            Inner Join partidas as par On par.par_id=ppto.par_id
            where ejec.sp_id='.$sp_id.' and ppto.estado!=\'3\'
            group by ejec.sp_id,par.par_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ////====== EJECUCION PPTO X PROYECTO, DISTRITAL, REGIONAL, INSTITUCIONAL
    /*----- EJECUCION DE PRESUPUESTO TOTAL POR PROYECTO/UNIDAD (vigente)-----*/
    public function suma_monto_ejecutado_total_ppto_sigep($aper_id){
        $sql = '
            select ppto.aper_id,SUM(ejec.ppto_ejec) ejecutado_total
            from ptto_partidas_sigep ppto
            Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
            where ppto.aper_id='.$aper_id.' and ppto.estado!=\'3\'
            group by ppto.aper_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- EJECUCION DE PRESUPUESTO TOTAL X REGIONAL PINVERSION(vigente)-----*/
    public function suma_monto_ejecutado_total_ppto_sigep_regional($dep_id){
        $sql = '
            select pi.dep_id,SUM(ejec.ppto_ejec) ejecutado_total
            from lista_poa_pinversion_nacional('.$this->gestion.') pi
            Inner Join ptto_partidas_sigep as ppto On ppto.aper_id=pi.aper_id
            Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
            where pi.dep_id='.$dep_id.' and ppto.estado!=\'3\'
            group by pi.dep_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- EJECUCION DE PRESUPUESTO AL TRIMESTRE X REGIONAL PINVERSION(vigente)-----*/
    public function ppto_ejecutado_inversion_regional_trimestre($or_id,$i){
        $sql = '
         select ppto_poa.or_id,SUM(ppto_ejec) ppto_ejec_trimestre
        from ptto_partidas_sigep ppto_asig
        Inner Join (

            select proy.aper_id,pr.or_id,ins.par_id,SUM(ins.ins_costo_total) total_poa
            from _productos pr
            Inner Join _componentes as c On c.com_id=pr.com_id
            Inner Join _insumoproducto as insp On insp.prod_id=pr.prod_id
            Inner Join insumos as ins On ins.ins_id=insp.ins_id
            Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
            where pr.or_id='.$or_id.' and pr.estado!=\'3\' and pr.prod_priori=\'1\'
            group by proy.aper_id,pr.or_id,ins.par_id
        
        ) as ppto_poa On ppto_asig.aper_id=ppto_poa.aper_id and ppto_asig.par_id=ppto_poa.par_id

        Inner Join ejecucion_financiera_sigep as ppto_ejec On ppto_ejec.sp_id=ppto_asig.sp_id
        where (ppto_ejec.m_id>\'0\' and ppto_ejec.m_id<='.($i*3).')
        group by ppto_poa.or_id';


        /*$sql = '
        select pr.or_id,SUM(ppto_ejec) ppto_ejec_trimestre
        from _productos pr
        Inner Join _componentes as c On c.com_id=pr.com_id
        Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as proy On proy.pfec_id=c.pfec_id
        Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=proy.aper_id
        Inner Join ejecucion_financiera_sigep as ppto_ejec On ppto_ejec.sp_id=partidas_asig.sp_id
        where pr.or_id='.$or_id.' and (ppto_ejec.m_id>\'0\' and ppto_ejec.m_id<='.($i*3).') and pr.estado!=\'3\' and pr.prod_priori=\'1\'
        group by pr.or_id';*/
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /* para borrar 2024
    public function ppto_ejecutado_inversion_regional_trimestre($dep_id,$i){
        $sql = '
            select p.dep_id,SUM(ppto_ejec) ppto_ejec_trimestre
            FROM lista_poa_pinversion_nacional('.$this->gestion.') p
            Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
            Inner Join ejecucion_financiera_sigep as ppto_ejec On ppto_ejec.sp_id=partidas_asig.sp_id
            where p.dep_id='.$dep_id.' and (ppto_ejec.m_id>\'0\' and ppto_ejec.m_id<='.($i*3).')
            group by p.dep_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*----- EJECUCION DE PRESUPUESTO TOTAL X DISTRITAL PINVERSION(vigente)-----*/
    public function suma_monto_ejecutado_total_ppto_sigep_distrital($dist_id){
        $sql = '
            select pi.dist_id,SUM(ejec.ppto_ejec) ejecutado_total
            from lista_poa_pinversion_nacional('.$this->gestion.') pi
            Inner Join ptto_partidas_sigep as ppto On ppto.aper_id=pi.aper_id
            Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
            where pi.dist_id='.$dist_id.' and ppto.estado!=\'3\'
            group by pi.dist_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- EJECUCION DE PRESUPUESTO TOTAL INSTITUCIONAL PINVERSION(vigente)-----*/
    public function suma_monto_ejecutado_total_ppto_sigep_institucional(){
        $sql = '
            select SUM(ejec.ppto_ejec) ejecutado_total
            from lista_poa_pinversion_nacional('.$this->gestion.') pi
            Inner Join ptto_partidas_sigep as ppto On ppto.aper_id=pi.aper_id
            Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
            where ppto.estado!=\'3\'';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /// ====================================================================








////// REPORTES DE EJECUCION PRESUPUESTARIA POR PARTIDAS 2022
    /*----- LISTA CONSOLIDADO REGIONAL DE PARTIDAS ASIGNADOS EN LA GESTION INSTITUCIONAL (INVERSION) -----*/
    public function lista_consolidado_partidas_ppto_asignado_gestion_institucional(){
        $sql = '
            select par_id,partida,par_nombre,SUM(ppto_partida_asignado_gestion) ppto_partida_asignado_gestion
            from lista_partidas_ppto_asignadas_gestion('.$this->gestion.')
            group by par_id,partida,par_nombre
            order by par_id asc';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// detalle de ejecucion por partidas a nivel Institucional
    public function get_partida_ejecutado_gestion_institucional($par_id){
        $sql = '
            select 
                par_id,
                partida,
                SUM(m1) m1,
                SUM(m2) m2,
                SUM(m3) m3,
                SUM(m4) m4,
                SUM(m5) m5,
                SUM(m6) m6,
                SUM(m7) m7,
                SUM(m8) m8,
                SUM(m9) m9,
                SUM(m10) m10,
                SUM(m11) m11,
                SUM(m12) m12,
                SUM(ejecutado_total) ejecutado_total

                from lista_partidas_ppto_ejecutado_gestion('.$this->gestion.')
                where par_id='.$par_id.'
                group by
                par_id,
                partida';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- LISTA CONSOLIDADO REGIONAL DE PARTIDAS ASIGNADOS EN LA GESTION POR REGIONAL  -----*/
    public function lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id){
        $sql = '
            select dep_id,par_id,partida,par_nombre,SUM(ppto_partida_asignado_gestion) ppto_partida_asignado_gestion
            from lista_partidas_ppto_asignadas_gestion('.$this->gestion.')
            where dep_id='.$dep_id.'
            group by dep_id,par_id,partida,par_nombre
            order by dep_id asc';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// detalle de ejecucion por partidas a nivel regional
    public function get_partida_ejecutado_gestion_regional($dep_id,$par_id){
        $sql = '
            select 
                dep_id,
                par_id,
                partida,
                SUM(m1) m1,
                SUM(m2) m2,
                SUM(m3) m3,
                SUM(m4) m4,
                SUM(m5) m5,
                SUM(m6) m6,
                SUM(m7) m7,
                SUM(m8) m8,
                SUM(m9) m9,
                SUM(m10) m10,
                SUM(m11) m11,
                SUM(m12) m12,
                SUM(ejecutado_total) ejecutado_total

                from lista_partidas_ppto_ejecutado_gestion('.$this->gestion.')
                where dep_id='.$dep_id.' and par_id='.$par_id.'
                group by 
                dep_id,
                par_id,
                partida';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// SUMA TOTAL DE PRESUPUESTO ASIGNADO PROYECTOS DE INVERSION (NACIONAL) 2022
    public function get_ppto_asignado_proyectos_inversion_aprobados(){
        $sql = '
            select SUM(ppto_asignado_gestion) ppto_asignado_gestion
            from lista_poa_pinversion_nacional('.$this->gestion.') p
            Inner Join vista_ppto_asignado_gestion_proyecto as asig On asig.aper_id=p.aper_id';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }
///////////////////////////////////////////////////////////

    /*----- MONTO PRESUPUESTO PROGRAMADO Y EJECUTADO POR UNIDAD/PROYECTO AL TRIMESTRE (2025) VIGENTE-----*/
    public function ppto_poa_ejecutado_al_trimestre($aper_id,$trimestre,$tp){
        // tp 1 : PTTO PROGRAMADO AL TRIMESTRE
        // tp 2 : PTTO EJECUTADO AL TRIMESTRE
        if($tp==1){
            $sql = 'select poa.aper_id,SUM(temp.ipm_fis) monto
                    from lista_poa_pinversion_nacional('.$this->gestion.') poa
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _insumoproducto as insp On insp.prod_id=prod.prod_id
                    Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
                    where poa.aper_id='.$aper_id.' and prod.estado!=\'3\' and (temp.mes_id>\'0\' and temp.mes_id<='.($trimestre*3).')
                    group by poa.aper_id';
        }
        else{
            $sql = 'select ppto.aper_id,SUM(ejec.ppto_ejec) monto
                    from ptto_partidas_sigep ppto
                    Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
                    where ppto.aper_id='.$aper_id.' and (ejec.m_id>\'0\' and ejec.m_id<='.($trimestre*3).')
                    group by ppto.aper_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO PROGRAMADO Y EJECUTADO POR REGIONAL AL TRIMESTRE (2025) VIGENTE-----*/
    public function ppto_poa_ejecutado_al_trimestre_regional($dep_id,$trimestre,$tp){
        // tp 1 : PTTO PROGRAMADO AL TRIMESTRE
        // tp 2 : PTTO EJECUTADO AL TRIMESTRE
        if($tp==1){
            $sql = 'select poa.dep_id,SUM(temp.ipm_fis) monto
                    from lista_poa_pinversion_nacional('.$this->gestion.') poa
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _insumoproducto as insp On insp.prod_id=prod.prod_id
                    Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
                    where poa.dep_id='.$dep_id.' and prod.estado!=\'3\' and (temp.mes_id>\'0\' and temp.mes_id<='.($trimestre*3).')
                    group by poa.dep_id';
        }
        else{
            $sql = 'select poa.dep_id,SUM(ejec.ppto_ejec) monto
                    from ptto_partidas_sigep ppto
                    Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as poa On poa.aper_id=ppto.aper_id
                    Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
                    where poa.dep_id='.$dep_id.' and (ejec.m_id>\'0\' and ejec.m_id<='.($trimestre*3).')
                    group by poa.dep_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO PROGRAMADO Y EJECUTADO INSTITUCIONAL AL TRIMESTRE (2025) VIGENTE-----*/
    public function ppto_poa_ejecutado_al_trimestre_institucional($trimestre,$tp){
        // tp 1 : PTTO PROGRAMADO AL TRIMESTRE
        // tp 2 : PTTO EJECUTADO AL TRIMESTRE
        if($tp==1){
            $sql = 'select SUM(temp.ipm_fis) monto
                    from lista_poa_pinversion_nacional('.$this->gestion.') poa
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _insumoproducto as insp On insp.prod_id=prod.prod_id
                    Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
                    where prod.estado!=\'3\' and (temp.mes_id>\'0\' and temp.mes_id<='.($trimestre*3).')';
        }
        else{
            $sql = 'select SUM(ejec.ppto_ejec) monto
                    from ptto_partidas_sigep ppto
                    Inner Join lista_poa_pinversion_nacional('.$this->gestion.') as poa On poa.aper_id=ppto.aper_id
                    Inner Join ejecucion_financiera_sigep as ejec On ppto.sp_id=ejec.sp_id
                    where (ejec.m_id>\'0\' and ejec.m_id<='.($trimestre*3).')';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR UNIDAD/PROYECTO (2025) VIGENTE-----*/
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
                    from insumos i
                    where i.aper_id='.$aper_id.' and i.ins_tipo_modificacion=\'0\'
                    group by i.aper_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO REVERTIDO ASIGNADO Y PROGRAMADO POR UNIDAD/PROYECTO (2023) VIGENTE-----*/
    public function suma_ptto_revertido_total_unidad($aper_id,$tp){
        // 1 : PTO ASIGNADO REVERTIDO
        // 2 : PTO PROGRAMADO REVERTIDO
        if($tp==1){
            $sql = 'select aper_id,SUM(presupuesto_revertido) ppto_revertido
                    from lista_partidas_revertidas('.$this->gestion.')
                    where aper_id='.$aper_id.'
                    group by aper_id';
        }
        else{
            $sql = 'select i.aper_id, SUM(i.ins_costo_total) as poa_revertido
                    from insumos i
                    where i.aper_id='.$aper_id.' and i.ins_tipo_modificacion=\'1\'
                    group by i.aper_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR DISTRITAL GASTO CORRIENTE (2023) VIGENTE-----*/
    public function suma_ptto_distrital($dist_id,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select p.dist_id,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    where p.dist_id='.$dist_id.' and partidas_asig.estado!=\'3\'
                    group by p.dist_id';
        }
        else{
            $sql = 'select p.dist_id, SUM(i.ins_costo_total) as programado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join insumos as i On i.aper_id=p.aper_id
                    where p.dist_id='.$dist_id.' and i.ins_tipo_modificacion=\'0\'
                    group by p.dist_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    

    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO INSTITUCIONAL PINVERSION (2023) VIGENTE-----*/
    public function suma_ptto_institucional_pi_aprobados($tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_pinversion_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                     and partidas_asig.estado!=\'3\'';
        }
        else{
           /* $sql = 'select SUM(temp.ipm_fis) programado
                    from lista_poa_pinversion_nacional('.$this->gestion.') poa
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _insumoproducto as insp On insp.prod_id=prod.prod_id
                    Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
                    where prod.estado!=\'3\'';*/

            $sql = 'select SUM(i.ins_costo_total) as programado
                    FROM lista_poa_pinversion_nacional('.$this->gestion.') p
                    Inner Join insumos as i On i.aper_id=p.aper_id
                    where i.ins_tipo_modificacion=\'0\'';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR REGIONAL PINVERSION (2023) VIGENTE-----*/
    public function suma_ptto_regional_pi_aprobados($dep_id,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select p.dep_id,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_pinversion_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    where p.dep_id='.$dep_id.' and partidas_asig.estado!=\'3\'
                    group by p.dep_id';
        }
        else{
            $sql = 'select p.dep_id, SUM(i.ins_costo_total) as programado
                    FROM lista_poa_pinversion_nacional('.$this->gestion.') p
                    Inner Join insumos as i On i.aper_id=p.aper_id
                    where p.dep_id='.$dep_id.' and i.ins_tipo_modificacion=\'0\'
                    group by p.dep_id';

            /*$sql = 'select poa.dep_id,SUM(temp.ipm_fis) programado
                    from lista_poa_pinversion_nacional('.$this->gestion.') poa
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _insumoproducto as insp On insp.prod_id=prod.prod_id
                    Inner Join temporalidad_prog_insumo as temp On temp.ins_id=insp.ins_id
                    where poa.dep_id='.$dep_id.' and prod.estado!=\'3\' 
                    group by poa.dep_id';*/
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO INSTITUCIONAL GASTO CORRIENTE (2023) VIGENTE-----*/
    public function suma_ptto_institucional($tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    and partidas_asig.estado!=\'3\'';
        }
        else{
            $sql = 'select SUM(i.ins_costo_total) as programado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join insumos as i On i.aper_id=p.aper_id
                    where i.ins_tipo_modificacion=\'0\'';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- MONTO PRESUPUESTO ASIGNADO Y PROGRAMADO POR REGIONAL GASTO CORRIENTE (2023) VIGENTE-----*/
    public function suma_ptto_regional($dep_id,$tp){
        // 1 : PTO ASIGNADO
        // 2 : PTO PROGRAMADO
        if($tp==1){
            $sql = 'select p.dep_id,SUM(partidas_asig.importe) as asignado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join ptto_partidas_sigep as partidas_asig On partidas_asig.aper_id=p.aper_id
                    where p.dep_id='.$dep_id.' and partidas_asig.estado!=\'3\'
                    group by p.dep_id';
        }
        else{
            $sql = 'select p.dep_id, SUM(i.ins_costo_total) as programado
                    FROM lista_poa_gastocorriente_nacional('.$this->gestion.') p
                    Inner Join insumos as i On i.aper_id=p.aper_id
                    where p.dep_id='.$dep_id.' and i.ins_estado!=\'3\' and i.ins_tipo_modificacion=\'0\'
                    group by p.dep_id';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- Get Partida Programado - gasto corriente (Partida -> Unidad)---*/
    public function get_partida_programado_poa($aper_id,$par_id){
        $sql = 'select i.aper_id,i.par_id,par.par_codigo as codigo,par.par_nombre, SUM(i.ins_costo_total) as ppto_programado
                from insumos i
                Inner Join partidas as par On par.par_id=i.par_id
                where i.aper_id='.$aper_id.' and i.par_id='.$par_id.' and i.aper_id!=\'0\' and i.ins_tipo_modificacion=\'0\'
                group by i.aper_id,i.par_id,par.par_codigo,par.par_nombre';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------- Get Partida Asignado (Partida -> Unidad)------------*/
    public function get_partida_asignado_sigep($aper_id,$par_id){
        $sql = '
                select pg.aper_id,pg.par_id, p.par_codigo as codigo, p.par_nombre as nombre, SUM(pg.importe) as ppto_asignado,pg.ppto_saldo_ncert
                from ptto_partidas_sigep pg
                Inner Join partidas as p On p.par_id=pg.par_id
                where pg.aper_id='.$aper_id.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.' and pg.par_id='.$par_id.'
                group by pg.aper_id,pg.par_id, p.par_codigo, p.par_nombre,pg.ppto_saldo_ncert';
        $query = $this->db->query($sql);
        return $query->result_array();
    }




 
    /*========= SUMA DE CODIGO DE PARTIDA (ASIG, PROG) uni org 2023 =========*/
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




    /*============ PARTIDAS UNIDAD EJECUTORA POR REGIONAL (vigente)============*/
    public function partidas_accion_region($dep_id,$aper_id,$tp){
        if($tp==1){ /// asignado
            $sql = 'select pg.sp_id,p.dep_id,pg.par_id,pg.partida as codigo,par.par_nombre as nombre,SUM(pg.importe) as ppto_asignado ,pg.ppto_saldo_ncert as ppto_revertido
                    from ptto_partidas_sigep pg 
                    Inner Join aperturaproyectos as ap On ap.aper_id=pg.aper_id 
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id 
                    Inner Join partidas as par On par.par_id=pg.par_id 
                    where p.dep_id='.$dep_id.' and p.estado!=\'3\' and pg.aper_id='.$aper_id.'and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'
                    group by pg.sp_id,p.dep_id,pg.par_id,pg.partida,par.par_nombre,pg.importe ,pg.ppto_saldo_ncert
                    order by pg.partida';
        }
        else{ /// programado POA

            $sql = 'select p.dep_id,p.dist_id,i.aper_id,i.par_id, par.par_codigo as codigo, par.par_nombre as nombre, SUM(i.ins_costo_total) as monto
                    from insumos i
                    Inner Join partidas as par On par.par_id=i.par_id
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                  
                    where p.dep_id='.$dep_id.' and i.aper_id='.$aper_id.' and i.aper_id!=\'0\' and i.ins_tipo_modificacion=\'0\'
                    group by p.dep_id,p.dist_id,i.aper_id,i.par_id, par.par_codigo, par.par_nombre
                    order by par.par_codigo asc';
        }
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get sumatoria ppto poa programado de los items que fueron registrados por REVERSION -------*/
    public function get_ppto_poa_partida_x_reversion($aper_id,$par_id){
        $sql = 'select p.dep_id,p.dist_id,i.aper_id,i.par_id, par.par_codigo as codigo, par.par_nombre as nombre, SUM(i.ins_costo_total) as monto_programado_revertido
                    from insumos i
                    Inner Join partidas as par On par.par_id=i.par_id
                    Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                    Inner Join _proyectos as p On p.proy_id=ap.proy_id
                  
                    where i.aper_id='.$aper_id.' and par.par_id='.$par_id.' and i.aper_id!=\'0\' and i.ins_tipo_modificacion=\'1\'
                    group by p.dep_id,p.dist_id,i.aper_id,i.par_id, par.par_codigo, par.par_nombre
                    order by par.par_codigo asc';
    
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get Partida insumos programados por partida -------*/
    public function get_lista_insumos_por_partida($aper_id,$par_id){
        $sql = 'select i.par_codigo,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_observacion
                    from vlista_insumos i
                    where i.aper_id='.$aper_id.' and i.aper_id!=\'0\' and i.par_id='.$par_id.'
                    order by i.par_codigo';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- Get Partida Accion Regional programado (a eliminar)------------------------*/
    public function get_partida_prog_unidad($dep_id,$aper_id,$par_id){
        $sql = 'select i.aper_id,i.par_id, i.par_codigo as codigo, i.par_nombre as nombre, SUM(i.ins_costo_total) as ppto_programado
                from vlista_insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id
                  
                where i.aper_id='.$aper_id.' and i.aper_id!=\'0\' and par_id='.$par_id.' 
                group by i.aper_id,i.par_id, i.par_codigo, i.par_nombre
                order by i.par_codigo';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---------- Get ppto asignado x Partida unidad (vigente)-------------*/
    public function get_ppto_partida_asig_unidad($dep_id,$aper_id,$par_id){
        $sql = 'select p.dep_id,pg.par_id,pg.partida as codigo,par.par_nombre as nombre,SUM(pg.importe) as ppto_asignado ,pg.ppto_saldo_ncert as ppto_revertido
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

    /*-------- monto modificado por partidas 2022--------*/
    public function monto_modificado_x_partida($sp_id){
        $sql = 'select ppto.sp_id, ppto_i.ppto_ini, (SUM(ppto.ppto_final)-SUM(ppto.ppto_ini)) ppto_modificado, ppto_f.ppto_final
                from ppto_mod ppto
                
                Inner Join (
                select sp_id,ppto_ini
                from ppto_mod
                where sp_id='.$sp_id.'
                order by mppto_id ASC LIMIT 1

                ) as ppto_i On ppto_i.sp_id=ppto.sp_id

                Inner Join (
                select sp_id,ppto_final
                from ppto_mod
                where sp_id='.$sp_id.'
                order by mppto_id DESC LIMIT 1

                ) as ppto_f On ppto_f.sp_id=ppto.sp_id

                
                where ppto.sp_id='.$sp_id.'
                group by ppto.sp_id, ppto_i.ppto_ini, ppto_f.ppto_final';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get Ptto Ejecutado x proyecto 2022 --------*/
    public function get_ppto_ejecutado_proyecto($proy_id){
        $sql = 'select *
                from lista_detalle_ejecucion_ppto_proyectos('.$this->gestion.')
                where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get Ptto Ejecutado x Proyecto de Inversion 2023 --------*/
    public function get_ppto_ejecutado_pinversion($aper_id){
        $sql = 'select *
                from lista_detalle_ejecucion_ppto_proyectos('.$this->gestion.')
                where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- Get Ptto Ejecutado a nivel Institucional 2023 --------*/
    public function get_ppto_ejecutado_institucional(){
        $sql = 'select 
                SUM(ppto_asignado_gestion) ppto_asignado_gestion,
                SUM(m1) m1,
                SUM(m2) m2,
                SUM(m3) m3,
                SUM(m4) m4,
                SUM(m5) m5,
                SUM(m6) m6,
                SUM(m7) m7,
                SUM(m8) m8,
                SUM(m9) m9,
                SUM(m10) m10,
                SUM(m11) m11,
                SUM(m12) m12,
                SUM(ejecutado_total) ejecutado_total
                from lista_detalle_ejecucion_ppto_proyectos('.$this->gestion.')';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get Ptto Ejecutado x Regional 2023 --------*/
    public function get_ppto_ejecutado_regional($dep_id){
        $sql = 'select 
                SUM(ppto_asignado_gestion) ppto_asignado_gestion,
                SUM(m1) m1,
                SUM(m2) m2,
                SUM(m3) m3,
                SUM(m4) m4,
                SUM(m5) m5,
                SUM(m6) m6,
                SUM(m7) m7,
                SUM(m8) m8,
                SUM(m9) m9,
                SUM(m10) m10,
                SUM(m11) m11,
                SUM(m12) m12,
                SUM(ejecutado_total) ejecutado_total
                from lista_detalle_ejecucion_ppto_proyectos('.$this->gestion.')
                where dep_id='.$dep_id.'
                group by dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Get Ptto Ejecutado x Distrital 2023 --------*/
    public function get_ppto_ejecutado_distrital($dist_id){
        $sql = 'select 
                SUM(ppto_asignado_gestion) ppto_asignado_gestion,
                SUM(m1) m1,
                SUM(m2) m2,
                SUM(m3) m3,
                SUM(m4) m4,
                SUM(m5) m5,
                SUM(m6) m6,
                SUM(m7) m7,
                SUM(m8) m8,
                SUM(m9) m9,
                SUM(m10) m10,
                SUM(m11) m11,
                SUM(m12) m12,
                SUM(ejecutado_total) ejecutado_total
                from lista_detalle_ejecucion_ppto_proyectos('.$this->gestion.')
                where dist_id='.$dist_id.'
                group by dist_id';
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
    

    /*-------- SUMA SALDOS REVERTIDOS POR PARTIDA Y CITE --------*/
    public function suma_saldo_revertido($sp_id){
        $sql = 'select SUM(monto_revertido) saldo
                from saldo_partida
                where sp_id='.$sp_id.' and saldo_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- LISTA DE SALDOS REVERTIDOS POR PARTIDA Y CITE --------*/
    public function lista_saldos_revertidos($sp_id){
        $sql = 'select saldo.*,mod.*
                from saldo_partida saldo
                Inner Join ppto_cite as mod On mod.cppto_id=saldo.cppto_id
                where saldo.sp_id='.$sp_id.' and saldo.saldo_estado!=\'3\'
                order by saldo.saldo_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

        /*-------- LISTA DE SALDOS PARTIDAS REVERTIDOS POR CITE --------*/
    public function lista_monto_partidas_revertidos($cppto_id){
        $sql = 'select *
                from lista_partidas_revertidas('.$this->gestion.')
                where cppto_id='.$cppto_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*-------- LISTA DE PARTIDAS PADRES (REVERTIDOS) --------*/
    public function lista_partidas_padres_revertidos($aper_id){
        $sql = 'select pr.aper_id,pr.proy_id,par_padre.par_depende,par_padre.par_codigo,par_padre.par_nombre
                from lista_partidas_revertidas('.$this->gestion.') pr
                Inner Join partidas as par On par.par_id=pr.par_id
                Inner Join partidas as par_padre On par_padre.par_codigo=par.par_depende
                where pr.aper_id='.$aper_id.'
                group by pr.aper_id,pr.proy_id,par_padre.par_depende,par_padre.par_codigo,par_padre.par_nombre
                order by par_padre.par_depende asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- LISTA DE PARTIDAS DEPENDIENTES (REVERTIDOS) --------*/
    public function lista_partidas_dependientes_revertidos($aper_id,$par_depende){
        $sql = 'select pr.aper_id,pr.proy_id,par.par_id,par.par_codigo,par.par_nombre,par.par_depende,SUM(pr.presupuesto_revertido) ppto_revertido
                from lista_partidas_revertidas('.$this->gestion.') pr
                Inner Join partidas as par On par.par_id=pr.par_id
                where pr.aper_id='.$aper_id.' and par.par_depende='.$par_depende.'
                group by pr.aper_id,pr.proy_id,par.par_id,par.par_codigo,par.par_nombre,par.par_depende
                order by par.par_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA DE SALDOS PARTIDAS REVERTIDOS POR UNIDAD --------*/
    public function lista_monto_partidas_revertidos_unidad($proy_id){
        $sql = 'select *
                from lista_partidas_revertidas('.$this->gestion.')
                where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- GET SALDO REVERTIDO PROGRAMADO POR PARTIDA - UNIDAD --------*/
    public function get_ppto_partida_revertido_unidad($par_id,$aper_id){
        $sql = 'select aper_id,par_id,SUM(presupuesto_revertido) monto_revertido
                from lista_partidas_revertidas('.$this->gestion.')
                where aper_id='.$aper_id.' and par_id='.$par_id.'
                group by aper_id,par_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

///// ====== DISTRIBUCION DE PPTO APROBADO POR DISTRITAL
    /*-------- LISTA DE UNIDADES QUE TIENEN PPTO DISPONIBLE A PROGRAMAR (DASHBOARD) A NIVEL DISTRITAL --------*/
    public function lista_unidades_con_saldo_a_distribuir($dep_id,$dist_id){
        if($dep_id==2){ /// Regional La paz
            $sql = 'select *
                from lista_ppto_poa_nacional('.$this->gestion.')
                where dep_id='.$dep_id.' and (saldo>\'2\' or saldo<\'0\') ';
        }
        else{
            $sql = 'select *
                from lista_ppto_poa_nacional('.$this->gestion.')
                where dist_id='.$dist_id.' and (saldo>\'2\' or saldo<\'0\') ';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}