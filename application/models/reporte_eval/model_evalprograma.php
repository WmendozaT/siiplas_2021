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

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2020 2022 - INSTITUCIONAL
    public function lista_apertura_programas_institucional($tp_id){
        
        if($tp_id==1){ /// proy inversion
            $sql = '
            select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion
                order by aper.aper_programa asc';
        }
        else{ /// gasto corriente
            if($this->gestion>2022){
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion
                order by aper.aper_programa asc';
            }
            else{
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion
                order by aper.aper_programa asc';
            }
            
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / APERTURA REGIONAL 2020 ------*/
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

        if($tp_id==1){
            $sql = 'select apg.aper_programa,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by apg.aper_programa';
            }
            else{
                $sql = 'select apg.aper_programa,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by apg.aper_programa';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA REGIONAL ---*/
    public function list_operaciones_evaluadas_institucional_trimestre($programa,$trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            else{
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2020 2022 - REGIONAL
    public function lista_apertura_programas_regional($dep_id,$tp_id){
        
        if($tp_id==1){
            $sql = '
            select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dep_id='.$dep_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id
                order by aper.aper_programa asc';
        }
        else{
            if($this->gestion>2022){
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dep_id='.$dep_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id
                order by aper.aper_programa asc';
            }
            else{
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dep_id='.$dep_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dep_id
                order by aper.aper_programa asc';
            }
            
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / APERTURA REGIONAL 2020 ------*/
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

        if($tp_id==1){
            $sql = 'select apg.aper_programa,p.dep_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa,p.dep_id';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,p.dep_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by apg.aper_programa,p.dep_id';
            }
            else{
                $sql = 'select apg.aper_programa,p.dep_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by apg.aper_programa,p.dep_id';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA REGIONAL ---*/
    public function list_operaciones_evaluadas_regional_trimestre($dep_id,$programa,$trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            else{
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


///// DISTRITAL

    //// ======= LISTA DE APERTURAS PROGRAMATICAS 2020 -2022 - DISTRITAL
    public function lista_apertura_programas_distrital($dist_id,$tp_id){
        
        if($tp_id==1){
            $sql = '
            select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dist_id='.$dist_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id
                order by aper.aper_programa asc';
        }
        else{
            if($this->gestion>2022){
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dist_id='.$dist_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id
                order by aper.aper_programa asc';
            }
            else{
                $sql = '
                select aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id,count(*) total_actividad
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join (
                select apg.aper_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion 
                from aperturaprogramatica apg
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'

                ) as aper On aper.aper_programa=apg.aper_programa
                        
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                where p.dist_id='.$dist_id.' and pf.pfec_estado=\'1\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by aper.aper_programa,aper.aper_proyecto,aper.aper_actividad,aper.aper_descripcion,p.dist_id
                order by aper.aper_programa asc';
            }
            
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NUMERO DE OPERACIONES PROGRAMADAS POR TRIMESTRE / APERTURA DISTRITAL 2020 ------*/
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

        if($tp_id==1){
            $sql = 'select apg.aper_programa,p.dist_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dep_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa,p.dist_id';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,p.dist_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                group by apg.aper_programa,p.dist_id';
            }
            else{
                $sql = 'select apg.aper_programa,p.dist_id,count(*) total
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join (
                        select prod_id
                        from prod_programado_mensual
                        where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                        group by prod_id
                    ) as pprog On pprog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and prod.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'
                group by apg.aper_programa,p.dist_id';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- LISTA OPERACIONES EVALUADAS (CUMPLIDAS-PROCESO-NO CUMPLIDAS) APERTURA DISTRITAL ---*/
    public function list_operaciones_evaluadas_distrital_trimestre($dist_id,$programa,$trimestre,$tipo_eval,$tp_id){
        if($tp_id==1){
            $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'1\'
                group by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval';
        }
        else{
            if($this->gestion>2022){
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' and (apg.aper_programa!=\'098\' and apg.aper_programa!=\'099\')
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            else{
                $sql = 'select apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,c.com_id,pt.prod_id,pt.trm_id,pt.tp_eval,pt.tmed_verif,pt.tprob,pt.tacciones,pt.prog,pt.eval,pt.activo
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join vista_componentes_dictamen as c On c.proy_id=p.proy_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join _productos_trimestral as pt On pr.prod_id=pt.prod_id
                where p.dist_id='.$dist_id.' and apg.aper_programa=\''.$programa.'\' and pt.testado!=\'3\' and pt.trm_id='.$trimestre.' and pt.tp_eval='.$tipo_eval.' and pt.g_id='.$this->gestion.' and apg.aper_gestion='.$this->gestion.' 
                and apg.aper_estado!=\'3\' and p.tp_id=\'4\' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.'';
            }
            
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
