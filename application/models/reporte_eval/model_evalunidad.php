<?php
class Model_evalunidad extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tmes = $this->session->userData('trimestre');
    }

    ///  GESTION 2020
    /*--- LISTA OPERACIONES EVALUADAS ---*/
    public function list_operaciones_evaluadas_servicio_trimestre($proy_id,$trimestre){
        $sql = 'select c.*,pt.*
                from vista_componentes_dictamen c
                Inner Join _productos as p On p.com_id=c.com_id
                Inner Join _productos_trimestral as pt On p.prod_id=pt.prod_id
                where c.proy_id='.$proy_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.'
                order by tprod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS)---*/
    public function list_operaciones_evaluadas_unidad_trimestre_tipo($proy_id,$trimestre,$tipo_eval){
        $sql = 'select c.*,pt.*
                from vista_componentes_dictamen c
                Inner Join _productos as p On p.com_id=c.com_id
                Inner Join _productos_trimestral as pt On p.prod_id=pt.prod_id
                where c.proy_id='.$proy_id.' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.'
                order by tprod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE /UNIDAD----------------------*/
    public function nro_operaciones_programadas($proy_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select c.proy_id,count(*) total
                from vista_componentes_dictamen c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where c.proy_id='.$proy_id.' and prod.estado!=\'3\'
                group by c.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /// Verif Operaciones al trimestre por unidad
    public function nro_operaciones_programadas_acumulado($proy_id,$trimestre){
        if($trimestre==1){
            $vf=3;
        }
        elseif($trimestre==2){
            $vf=6;   
        }
        elseif($trimestre==3){
            $vf=9;   
        }
        elseif($trimestre==4){
            $vf=12;   
        }

        $sql = 'select c.proy_id,count(*) total
                from vista_componentes_dictamen c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>=\'0\' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where c.proy_id='.$proy_id.' and prod.estado!=\'3\'
                group by c.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD PROGRAMADAS POR TRIMESTRE ----------------------*/
    public function suma_operaciones_programadas($proy_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select c.proy_id,count(*) total, SUM(pprog.suma_programado) suma_programado
                from vista_componentes_dictamen c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pg_fis) suma_programado
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where c.proy_id='.$proy_id.' and prod.estado!=\'3\'
                group by c.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- SUMA TEMPORALIDAD EJECUTADA POR TRIMESTRE ----------------------*/
    public function suma_operaciones_ejecutadas($proy_id,$trimestre){
        if($trimestre==1){
            $vi=1;$vf=3;
        }
        elseif($trimestre==2){
            $vi=4;$vf=6;   
        }
        elseif($trimestre==3){
            $vi=7;$vf=9;   
        }
        elseif($trimestre==4){
            $vi=10;$vf=12;   
        }

        $sql = 'select c.proy_id,count(*) total, SUM(pejec.suma_ejecutado) suma_evaluado
                from vista_componentes_dictamen c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id, SUM(pejec_fis) suma_ejecutado
                        from prod_ejecutado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pejec_fis!=\'0\'
                        group by prod_id
                    ) as pejec On pejec.prod_id=prod.prod_id
                where c.proy_id='.$proy_id.'
                group by c.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*--- SUMA MESES TEMPORALIDAD PROGRAMADO DE PRODUCTOS POR SERVICIO ---*/
    public function sum_temporalidad_prod_programado_servicio($com_id){
        $sql = 'select
                p.com_id,
                SUM(prog.enero) mes1, 
                SUM(prog.febrero) mes2, 
                SUM(prog.marzo) mes3, 
                SUM(prog.abril) mes4, 
                SUM(prog.mayo) mes5, 
                SUM(prog.junio) mes6,
                SUM(prog.julio) mes7,
                SUM(prog.agosto) mes8,
                SUM(prog.septiembre) mes9,
                SUM(prog.octubre) mes10,
                SUM(prog.noviembre) mes11,
                SUM(prog.diciembre) mes12,
                prog.g_id

                from _productos as p
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and prog.g_id='.$this->gestion.'
                group by p.com_id,prog.g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- SUMA MESES TEMPORALIDAD EJECUTADO DE PRODUCTOS POR SERVICIO ---*/
    public function sum_temporalidad_prod_ejecutado_servicio($com_id){
        $sql = 'select 
                p.com_id,
                SUM(ejec.enero) mes1, 
                SUM(ejec.febrero) mes2, 
                SUM(ejec.marzo) mes3, 
                SUM(ejec.abril) mes4, 
                SUM(ejec.mayo) mes5, 
                SUM(ejec.junio) mes6,
                SUM(ejec.julio) mes7,
                SUM(ejec.agosto) mes8,
                SUM(ejec.septiembre) mes9,
                SUM(ejec.octubre) mes10,
                SUM(ejec.noviembre) mes11,
                SUM(ejec.diciembre) mes12,
                ejec.g_id
                from _productos as p
                Inner Join vista_productos_temporalizacion_ejecutado_dictamen as ejec On ejec.prod_id=p.prod_id
                where p.com_id='.$com_id.' and p.estado!=\'3\' and ejec.g_id='.$this->gestion.'
                group by p.com_id,ejec.g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
