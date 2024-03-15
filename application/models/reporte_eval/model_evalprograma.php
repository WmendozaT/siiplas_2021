<?php
class Model_evalprograma extends CI_Model{
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

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2024 - INSTITUCIONAL
    public function lista_apertura_programas_institucional($tp_id){
            if($this->gestion>2023){
                $sql = '
                select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(pr.prod_id) total_actividad
                from aperturaprogramatica apg
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as poa On poa.prog=apg.aper_programa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_asignado=\'1\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by apg.aper_programa asc';
            }
            else{
                $sql = '
                select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(*) total_actividad
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join aperturaprogramatica as apg On apg.aper_programa=poa.prog
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                where c.estado!=\'3\' and prod.estado!=\'3\' and apg.aper_asignado=\'1\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
                group by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by apg.aper_programa asc';
            }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE FORM 4 PROGRAMADAS POR TRIMESTRE / APERTURA REGIONAL 2020 ------*/
    public function nro_operaciones_programadas_institucional($programa,$trimestre,$tp_id){
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

        if($this->gestion>2023){
            $sql = '
                select poa.prog aper_programa,count(pr.prod_id) total
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                 Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=pr.prod_id
                
                where poa.prog=\''.$programa.'\' and c.estado!=\'3\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by poa.prog';
        }
        else{
            $sql = '
            select apg.aper_programa,count(*) total
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join aperturaprogramatica as apg On apg.aper_programa=poa.prog
            Inner Join _componentes as c On c.pfec_id=poa.pfec_id
            Inner Join _productos as prod On prod.com_id=c.com_id
             Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=prod.prod_id
            where apg.aper_programa=\''.$programa.'\' and c.estado!=\'3\' and prod.estado!=\'3\' and apg.aper_asignado=\'1\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
            group by apg.aper_programa';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA REGIONAL ---*/
    public function list_operaciones_evaluadas_institucional_trimestre($programa,$trimestre,$tipo_eval,$tp_id){
        if($this->gestion>2023){
            $sql = 'select poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')';
        }
        else{
            $sql = 'select poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2024 - REGIONAL
    public function lista_apertura_programas_regional($dep_id,$tp_id){
            if($this->gestion>2023){
                $sql = '
                select poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(pr.prod_id) total_actividad
                from aperturaprogramatica apg
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as poa On poa.prog=apg.aper_programa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where poa.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_asignado=\'1\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by poa.dep_id,apg.aper_programa asc';
            }
            else{
                $sql = '
                select poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(pr.prod_id) total_actividad
                from aperturaprogramatica apg
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as poa On poa.prog=apg.aper_programa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where poa.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_asignado=\'1\' and pr.estado!=\'3\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
                group by poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by poa.dep_id,apg.aper_programa asc';
            }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE FORM 4 PROGRAMADAS POR TRIMESTRE / APERTURA REGIONAL 2024 ------*/
    public function nro_operaciones_programadas_regional($dep_id,$programa,$trimestre,$tp_id){
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


        if($this->gestion>2023){
            $sql = '
                select poa.dep_id,poa.prog aper_programa,count(pr.prod_id) total
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                 Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=pr.prod_id
                
                where poa.dep_id='.$dep_id.' and poa.prog=\''.$programa.'\' and c.estado!=\'3\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by poa.dep_id,poa.prog';
        }
        else{
            $sql = '
                select poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(pr.prod_id) total
                from aperturaprogramatica apg
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as poa On poa.prog=apg.aper_programa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                 Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=pr.prod_id
                
                where poa.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and c.estado!=\'3\' and pr.estado!=\'3\' and apg.aper_asignado=\'1\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
                group by poa.dep_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by poa.dep_id,apg.aper_programa asc';
        }


        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA REGIONAL ---*/
    public function list_operaciones_evaluadas_regional_trimestre($dep_id,$programa,$trimestre,$tipo_eval,$tp_id){
        if($this->gestion>2023){
            $sql = 'select poa.dep_id,poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.dep_id='.$dep_id.' and poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')';
        }
        else{
            $sql = 'select poa.dep_id,poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.dep_id='.$dep_id.' and poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


///// DISTRITAL

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2024 - DISTRITAL
    public function lista_apertura_programas_distrital($dist_id,$tp_id){
            if($this->gestion>2023){
                $sql = '
                select poa.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(pr.prod_id) total_actividad
                from aperturaprogramatica apg
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as poa On poa.prog=apg.aper_programa
                Inner Join vista_componentes_dictamen as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                where poa.dist_id='.$dist_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_asignado=\'1\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by poa.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by poa.dist_id,apg.aper_programa asc';
            }
            else{
                $sql = '
                select poa.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,count(*) total_actividad
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join aperturaprogramatica as apg On apg.aper_programa=poa.prog
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                where poa.dist_id='.$dist_id.' and c.estado!=\'3\' and prod.estado!=\'3\' and apg.aper_asignado=\'1\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
                group by poa.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion
                order by apg.aper_programa asc';
            }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE FORM 4 PROGRAMADAS POR TRIMESTRE / APERTURA DISTRITAL 2020 ------*/
    public function nro_operaciones_programadas_distrital($dist_id,$programa,$trimestre,$tp_id){
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

        if($this->gestion>2023){
             $sql = '
                select poa.dist_id,poa.prog aper_programa,count(pr.prod_id) total
                from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
                Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                 Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=pr.prod_id
                
                where poa.dist_id='.$dist_id.' and poa.prog=\''.$programa.'\' and c.estado!=\'3\' and pr.estado!=\'3\' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')
                group by poa.dist_id,poa.prog';
        }
        else{
            $sql = '
            select poa.dist_id,apg.aper_programa,count(*) total
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join aperturaprogramatica as apg On apg.aper_programa=poa.prog
            Inner Join _componentes as c On c.pfec_id=poa.pfec_id
            Inner Join _productos as prod On prod.com_id=c.com_id
             Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=prod.prod_id
            where poa.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and c.estado!=\'3\' and prod.estado!=\'3\' and apg.aper_asignado=\'1\' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')
            group by poa.dist_id,apg.aper_programa';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA DISTRITAL ---*/
    public function list_operaciones_evaluadas_distrital_trimestre($dist_id,$programa,$trimestre,$tipo_eval,$tp_id){
        if($this->gestion>2023){
            $sql = 'select poa.dist_id,poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.dist_id='.$dist_id.' and poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'97\' and poa.prog!=\'98\' and poa.prog!=\'99\')';
        }
        else{
            $sql = 'select poa.dist_id,poa.prog aper_programa,poa.proy aper_proyecto,poa.act aper_actividad,poa.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                    from lista_poa_gastocorriente_nacional('.$this->gestion.') poa 
                    Inner Join _componentes as c On c.pfec_id=poa.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join _productos_trimestral as pt On prod.prod_id=pt.prod_id
                    where poa.dist_id='.$dist_id.' and poa.prog=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and (poa.prog!=\'098\' and poa.prog!=\'099\' and poa.prog!=\'720\')';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
