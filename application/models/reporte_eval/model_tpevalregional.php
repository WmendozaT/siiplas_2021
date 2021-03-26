<?php
class Model_tpevalregional extends CI_Model{
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
        $this->trimestre = $this->session->userData('trimestre');
    }

    /*--------------------------------- Get Departamento 2019 ------------------------------------*/
    public function get_dpto($dep_id){
        $sql = 'select *
                from _departamentos
                where dep_id='.$dep_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------------- List Distrital ------------------------------------*/
    public function get_distrital($dep_id){
        $sql = 'select *
                from _distritales
                where dep_id='.$dep_id.' and dist_estado!=\'0\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------------- Get Distrital ------------------------------------*/
    public function get_dist($dist_id){
        $sql = 'select *
                from _distritales dist
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                where dist.dist_id='.$dist_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- list Regional total solo operacion de funcionamiento ----------------------*/
    public function list_consolidado_regional($dep_id,$tp_id){
        $sql = 'select *
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                where (apg.aper_programa!=\'97\' and apg.aper_programa!=\'98\') and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.dep_id='.$dep_id.' and p.tp_id='.$tp_id.'
                ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- Numero de Evaluaciones de productos por Regional Trimestral ----------------------*/
    public function evaluacion_productos_regional($dep_id,$teval,$tp_id){
        $sql = 'select p.dep_id,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join (

                select vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vista_producto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                where vp.estado!=\'3\'
                                group by vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id
                
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\' 
                and p.dep_id='.$dep_id.' and ev.g_id='.$this->gestion.' and ev.tp_eval='.$teval.' and ev.trm_id='.$this->trimestre.' and p.tp_id='.$tp_id.'
                GROUP BY p.dep_id,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*----- NACIONAL -----*/

    /*-------------------- (Eficacia) list Acciones - institucional ----------------------*/
    public function list_consolidado_institucional(){
        $sql = 'select apg.aper_programa,prog.aper_descripcion, p.proy_id,p.proy_nombre
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join (

                select *
                from aperturaprogramatica
                where aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_gestion=2018 and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_gestion,aper_programa,aper_proyecto,aper_actividad asc

                ) as prog On prog.aper_programa=apg.aper_programa
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                GROUP BY apg.aper_programa,prog.aper_descripcion,p.proy_id,p.proy_nombre
                ORDER BY apg.aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- nro de acciones por programas por Nacional ----------------------*/
    public function categorias_programaticas_nacional(){
        $sql = 'select apg.aper_programa,prog.aper_descripcion,count(*) as acciones
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join (

                select *
                from aperturaprogramatica
                where aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_gestion,aper_programa,aper_proyecto,aper_actividad asc

                ) as prog On prog.aper_programa=apg.aper_programa
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.dep_id!=\'0\' and p.dist_id!=\'0\'
                GROUP BY apg.aper_programa,prog.aper_descripcion
                ORDER BY apg.aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------------------- programa - tipo de evaluacion Nacional trimestral (Productos)----------------------*/
    public function evaluacion_programas_nacional($aper_programa,$teval,$trimestre){
        $sql = 'select apg.aper_programa,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vista_producto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                where vp.estado!=\'3\'
                                group by vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and apg.aper_programa=\''.$aper_programa.'\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\'
                and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and ev.tp_eval='.$teval.' and p.dep_id!=\'0\' and p.dist_id!=\'0\'
                GROUP BY apg.aper_programa,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- total programado por programas Nacional (Producto) ----------------------*/
    public function total_programado_programas_nacional($aper_programa,$trimestre){
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

        $sql = 'select apg.aper_programa,count(pprog.prod_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               Inner Join vista_producto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id
               where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and apg.aper_programa=\''.$aper_programa.'\' and p.dep_id!=\'0\' and p.dist_id!=\'0\' and vprod.estado!=\'3\'
               GROUP BY apg.aper_programa';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*----- REGIONAL -----*/
    /*-------------------- nro de acciones por programas por Regional ----------------------*/
    public function categorias_programaticas_regional($dep_id,$tp_id){
        $sql = 'select apg.aper_programa,prog.aper_descripcion,count(*) as acciones
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join (

                select *
                from aperturaprogramatica
                where aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_gestion,aper_programa,aper_proyecto,aper_actividad asc

                ) as prog On prog.aper_programa=apg.aper_programa
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.dep_id='.$dep_id.' and p.dist_id!=\'0\' and p.tp_id='.$tp_id.'
                GROUP BY apg.aper_programa,prog.aper_descripcion
                ORDER BY apg.aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- programa - tipo de evaluacion por Regional trimestral (Productos)----------------------*/
    public function evaluacion_programas_regional($dep_id,$aper_programa,$teval,$trimestre){
        $sql = 'select p.dep_id,apg.aper_programa,ev.tp_eval,SUM(ev.nro) as total
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id

                Inner Join (

                select vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id,count(*) as nro
                                from vista_producto vp
                                Inner Join (select prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id
                                from _productos_trimestral pt
                                where testado!=\'3\'
                                group by prod_id,trm_id,tp_eval,tmed_verif,tprob,tacciones,g_id) as tprod On tprod.prod_id=vp.prod_id
                                where vp.estado!=\'3\'
                                group by vp.proy_id,tprod.tp_eval,tprod.g_id,tprod.trm_id

                ) as ev On ev.proy_id=p.proy_id

                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\' and pf.pfec_estado=\'1\' 
                and p.dep_id='.$dep_id.' and ev.g_id='.$this->gestion.' and ev.trm_id='.$trimestre.' and apg.aper_programa=\''.$aper_programa.'\' and ev.tp_eval='.$teval.' and p.dist_id!=\'0\'
                GROUP BY p.dep_id,apg.aper_programa,ev.tp_eval';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------------------- total programado por programas por regional (Producto) ----------------------*/
    public function total_programado_programas_regional($dep_id,$aper_programa,$trimestre){
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

        $sql = 'select p.dep_id,apg.aper_programa,count(pprog.prod_id) total
               from _proyectos as p
               Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
               Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
               Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
               Inner Join vista_producto as vprod On vprod.proy_id=p.proy_id
                Inner Join (
                    select prod_id
                    from prod_programado_mensual
                    where g_id='.$this->gestion.' and (m_id>='.$vi.' and m_id<='.$vf.') and pg_fis!=\'0\'
                    group by prod_id
                ) as pprog On pprog.prod_id=vprod.prod_id
               where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
               and p.dep_id='.$dep_id.' and apg.aper_programa=\''.$aper_programa.'\' and p.dist_id!=\'0\' and vprod.estado!=\'3\'
               GROUP BY p.dep_id,apg.aper_programa';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
