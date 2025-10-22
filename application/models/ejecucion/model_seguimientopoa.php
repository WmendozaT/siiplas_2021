<?php
class Model_seguimientopoa extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist_id = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dep_id = $this->session->userData('dep_id'); /// dist-> id de la regional
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tmes = $this->session->userData('trimestre');
        $this->verif_mes=$this->session->userData('mes_actual');
    }
    

    /*------- VERIFICANDO SI HUBO REGISTRO E IMPRESION DEL FORMULARIO  --------*/
    public function verif_llenado_impresion_seguimientpoa($com_id,$m_id){
        $sql = ' select *
                 from registro_seguimientopoa
                 where com_id='.$com_id.' and mes='.$m_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- LISTA DE REGIONALES CON SU META TOTAL  --*/
    public function get_meta_total_regionales(){
        /*$sql = 'select *
                from metatotal_operaciones_regionales('.$this->gestion.')';*/
                $sql = '
                select dep.dep_id ,dep.dep_cod,dep.dep_departamento, SUM(meta) meta
                from lista_poa_gastocorriente_nacional('.$this->gestion.') dep
                Inner Join (select c.pfec_id,SUM(prod_meta) meta
                from _componentes c
                Inner Join v_operaciones_subactividad as prod On prod.com_id=c.com_id
                where c.estado!=3
                group by c.pfec_id) as dat On dat.pfec_id=dep.pfec_id
                group by dep.dep_id,dep.dep_cod,dep.dep_departamento
                order by dep.dep_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*-- LISTA DE OPERACIONES (PRODUCTOS) LISTADO POR MES PROGRAMADO --*/
    public function operaciones_programados_x_mes($com_id,$mes_id){
        $sql = 'select *
                from v_seguimiento_operaciones_mensual
                where com_id='.$com_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET PROGRAMADO POA MENSUAL --*/
    public function get_programado_poa_mes($prod_id,$mes_id){
        $sql = 'select *
                from prod_programado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET SUMA PROGRAMADO Y EJECUTADO POA MENSUAL POR FORM 4--*/
    public function get_programado_ejecutado_al_mes($tp,$prod_id,$mes_id){
        /// tp (0) programado
        /// tp (1) ejecutado
        if($tp==0){
            $sql = 'select SUM(pg_fis) programado
                    from prod_programado_mensual
                    where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$mes_id.')
                    group by prod_id';
        }
        else{
            $sql = 'select SUM(pejec_fis) ejecutado
                    from prod_ejecutado_mensual
                    where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$mes_id.')
                    group by prod_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET SEGUIMIENTO (EJECUTADO) POA MENSUAL (Cumplidas, En proceso) --*/
    public function get_seguimiento_poa_mes($prod_id,$mes_id){
        $sql = 'select *
                from prod_ejecutado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- GET SEGUIMIENTO POA MENSUAL (No cumplido) --*/
    public function get_seguimiento_poa_mes_noejec($prod_id,$mes_id){
        $sql = 'select *
                from prod_no_ejecutado_mensual
                where prod_id='.$prod_id.' and m_id='.$mes_id.' and g_id='.$this->gestion.'
                order by ne_id desc  LIMIT 1';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*-- GET SEGUIMIENTO POA MENSUAL POR SUBACTIVIDAD --*/
    public function get_seguimiento_poa_mes_subactividad($com_id,$mes_id){
        $sql = 'select *
                from _productos p
                Inner Join prod_ejecutado_mensual as pe On pe.prod_id=p.prod_id
                where p.com_id='.$com_id.' and pe.m_id='.$mes_id.' and pe.g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- GET LISTA DE OPERACIONES MENSUAL POR DISTRITAL --*/
    public function get_seguimiento_poa_mes_distrital($dist_id,$mes_id,$gestion){
        $sql = 'select *
                from lista_seguimiento_operaciones_mensual_ue('.$dist_id.','.$mes_id.','.$gestion.')';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///// get nro de actividades a ser ejecutado por mes por distrital
    public function get_nro_poa_mes_distrital($dist_id,$mes_id,$gestion){
        $sql = 'select count(*) nro
                    from _proyectos p
                    Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                    Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                    Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                    Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join prod_programado_mensual as prog On prog.prod_id=prod.prod_id
                    where p.dist_id='.$dist_id.' and prog.m_id='.$mes_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and c.estado!=\'3\' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and prod.estado!=\'3\' and p.tp_id=\'4\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /*-- GET LISTA DE OPERACIONES MENSUAL POR REGIONAL --*/
    public function get_seguimiento_poa_mes_regional($dep_id,$mes_id,$gestion){
        $sql = 'select *
                from lista_seguimiento_operaciones_mensual_regional('.$dep_id.','.$mes_id.','.$gestion.')';
        

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ///// get nro de actividades a ser ejecutado por mes por regional
    public function get_nro_poa_mes_regional($dep_id,$mes_id,$gestion){
        $sql = 'select count(*) nro
                    from _proyectos p
                    Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                    Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                    Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                    Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                    Inner Join _productos as prod On prod.com_id=c.com_id
                    Inner Join prod_programado_mensual as prog On prog.prod_id=prod.prod_id
                    where p.dep_id='.$dep_id.' and prog.m_id='.$mes_id.' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and c.estado!=\'3\' and p.estado!=\'3\' and apg.aper_estado!=\'3\' and prod.estado!=\'3\' and p.tp_id=\'4\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET LISTA DE UNIDADES QUE TIENEN PROGRAMADO OPERACIONES EN EL MES (DISTRITAL) --*/
    public function get_lista_unidad_operaciones($dist_id,$mes_id,$gestion){
        $sql = '
                select aper_programa,aper_proyecto,aper_actividad,proy_id,tipo,act_descripcion, abrev, count(m_id) operaciones
                from lista_seguimiento_operaciones_mensual_ue('.$this->dist_id.','.$mes_id.','.$gestion.')
                where tp_id=\'4\'
                group by aper_programa, aper_proyecto,aper_actividad,proy_id,tipo,act_descripcion, abrev
                order by aper_programa, aper_proyecto,aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET LISTA DE UNIDADES QUE TIENEN PROGRAMADO OPERACIONES EN EL MES (REGIONAL) --*/
    public function get_lista_unidad_operaciones_regional($dep_id,$mes_id,$gestion){
        $sql = '
                select aper_programa,aper_proyecto,aper_actividad,proy_id,tipo,act_descripcion, abrev, count(m_id) operaciones
                from lista_seguimiento_operaciones_mensual_regional('.$this->dep_id.','.$mes_id.','.$gestion.')
                where tp_id=\'4\'
                group by aper_programa, aper_proyecto,aper_actividad,proy_id,tipo,act_descripcion, abrev
                order by aper_programa, aper_proyecto,aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }





    /*-- GET LISTA DE OPERACIONES PROGRAMADO EN EL MES (DISTRITAL)--*/
    public function get_lista_operaciones_programados($dist_id,$mes_id,$gestion,$proy_id){
        $sql = '
                select *
                from lista_seguimiento_operaciones_mensual_ue('.$dist_id.','.$mes_id.','.$gestion.')
                where proy_id='.$proy_id.'
                order by serv_cod, prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- GET LISTA DE OPERACIONES PROGRAMADO EN EL MES (REGIONAL)--*/
    public function get_lista_operaciones_programados_regional($dep_id,$mes_id,$gestion,$proy_id){
        $sql = '
                select *
                from lista_seguimiento_operaciones_mensual_regional('.$dep_id.','.$mes_id.','.$gestion.')
                where proy_id='.$proy_id.'
                order by serv_cod, prod_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET LISTA DE SUBACTIVIDADES QUE TIENEN PROGRAMADO OPERACIONES EN EL MES --*/
    public function get_lista_subactividades_operaciones_programados($dist_id,$mes_id,$gestion,$proy_id){
        $sql = '
                select proy_id,dist_id,com_id,tipo_subactividad,serv_cod,serv_descripcion,m_id,aper_gestion
                from lista_seguimiento_operaciones_mensual_ue('.$dist_id.','.$mes_id.','.$gestion.')
                where proy_id='.$proy_id.'
                group by proy_id,dist_id,com_id,tipo_subactividad,serv_cod,serv_descripcion,m_id,aper_gestion
                order by serv_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /// ============== SEGUIMIENTO POA (REPORTE)
    /*-- GET LISTA UNIDAD META TOTAL OPERACIONES 2021 --*/
    public function list_poa_gacorriente_pinversion_distrital($dist_id,$tp_id){
        if($tp_id==1){ /// Proyecto de Inversion
            $sql = 'select * from lista_poa_pinversion_distrital('.$dist_id.','.$this->gestion.')';
        }
        else{
            $sql = 'select * 
                    from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') unidades
                    Inner Join (select c.pfec_id,SUM(prod_meta) meta
                    from _componentes c
                    Inner Join v_operaciones_subactividad as prod On prod.com_id=c.com_id
                    where c.estado!=\'3\'
                    group by c.pfec_id) as dat On dat.pfec_id=unidades.pfec_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- GET LISTA UNIDAD META TOTAL OPERACIONES 2021 (REGIONAL)--*/
    public function list_poa_gacorriente_pinversion_regional($dep_id,$tp_id){
        if($tp_id==1){ /// Proyecto de Inversion
            $sql = 'select * from lista_poa_pinversion_regional('.$dep_id.','.$this->gestion.')';
        }
        else{
            $sql = 'select * 
                    from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') unidades
                    Inner Join (select c.pfec_id,SUM(prod_meta) meta
                    from _componentes c
                    Inner Join v_operaciones_subactividad as prod On prod.com_id=c.com_id
                    where c.estado!=\'3\'
                    group by c.pfec_id) as dat On dat.pfec_id=unidades.pfec_id
                    order by da,ue,prog,proy,act asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-- GET META PROGRAMADO Y EJECUTADO AL MES ACTIVO POR UNIDAD/PROYECTO --*/
    public function get_meta_unidad($tp,$pfec_id,$mes){
        // tp : 1 Programado
        // tp : 2 Ejecutado
        if($tp==1){ /// Meta Programado al mes
            $sql = '
                select c.pfec_id,SUM(pg_fis) meta
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join prod_programado_mensual as pprod On pprod.prod_id=prod.prod_id
                where c.pfec_id='.$pfec_id.' and c.estado!=\'3\' and prod.estado!=3 and (pprod.m_id>\'0\' and pprod.m_id<='.$mes.')
                group by c.pfec_id';
        }
        else{ /// Meta ejecutado al Mes 
            $sql = '
                select c.pfec_id,SUM(pejec_fis) meta
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join prod_ejecutado_mensual as eprod On eprod.prod_id=prod.prod_id
                where c.pfec_id='.$pfec_id.' and c.estado!=\'3\' and prod.estado!=\'3\' and (eprod.m_id>\'0\' and eprod.m_id<='.$mes.')
                group by c.pfec_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- GET META PROGRAMADO Y EJECUTADO AL MES ACTIVO POR REGIONAL --*/
    public function get_meta_regional($tp,$dep_id,$mes){
        // tp : 1 Programado
        // tp : 2 Ejecutado
        if($tp==1){ /// Meta Programado al mes
            $sql = '
                select unidades.dep_id,SUM(meta) meta
                from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') unidades
                Inner Join (

                select c.pfec_id,SUM(pg_fis) meta
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join prod_programado_mensual as pprod On pprod.prod_id=prod.prod_id
                where c.estado!=\'3\' and prod.estado!=\'3\' and (pprod.m_id>\'0\' and pprod.m_id<='.$mes.')
                group by c.pfec_id

                ) as meta On meta.pfec_id=unidades.pfec_id
                group by unidades.dep_id';
        }
        else{ /// Meta ejecutado al Mes 
            $sql = '
                select unidades.dep_id,SUM(meta) meta
                from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') unidades
                Inner Join (

                select c.pfec_id,SUM(pejec_fis) meta
                from _componentes c
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join prod_ejecutado_mensual as eprod On eprod.prod_id=prod.prod_id
                where c.estado!=\'3\' and prod.estado!=\'3\' and (eprod.m_id>\'0\' and eprod.m_id<='.$mes.')
                group by c.pfec_id

                ) as meta On meta.pfec_id=unidades.pfec_id
                group by unidades.dep_id';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////// SEGUIMIENTO A ESTABLECIMIENTOS DE SALUD (GASTO CORRIENTE)

    /*-- GET Lista de Actividad programado en la gestion para el seguimiento --*/
    public function get_unidad_programado_gestion($act_id){
        $sql = '
                select *
                from get_establecimiento_de_salud_gestion('.$act_id.','.$this->gestion.')';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// EVALUACION POA COMVINADO A SEGUIMIENTO POA
    /*------------- Rango Trimestre para los valores pendientes por trimestre 2020 -----------------*/
    public function rango_programado_trimestral_productos($prod_id,$trimestre){
      $vfinal=0;
      if($trimestre==0){$vfinal=0;}
      elseif($trimestre==1){$vfinal=3;}
      elseif ($trimestre==2) {$vfinal=6;}
      elseif ($trimestre==3) {$vfinal=9;}
      elseif ($trimestre==4) {$vfinal=12;}

        $sql = 'select prod_id,(CASE WHEN sum(pg_fis)!=0 THEN sum(pg_fis) ELSE 0 END) as trimestre, g_id
                from prod_programado_mensual
                where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$vfinal.') and g_id='.$this->gestion.'
                GROUP BY prod_id,g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- Rango Trimestre para los valores pendientes Ejecutado por trimestre -----------------*/
    public function rango_ejecutado_trimestral_productos($prod_id,$trimestre){
      $vfinal=0;
      if($trimestre==0){$vfinal=0;}
      elseif($trimestre==1){$vfinal=3;}
      elseif ($trimestre==2) {$vfinal=6;}
      elseif ($trimestre==3) {$vfinal=9;}
      elseif ($trimestre==4) {$vfinal=12;}

    $sql = 'select prod_id,(CASE WHEN sum(pejec_fis)!=0 THEN sum(pejec_fis) ELSE 0 END) as trimestre, g_id
            from prod_ejecutado_mensual
            where prod_id='.$prod_id.' and (m_id>\'0\' and m_id<='.$vfinal.') and g_id='.$this->gestion.'
            GROUP BY prod_id,g_id';
    $query = $this->db->query($sql);
    return $query->result_array();
    }



    //// EVALUACION POA COMVINADO A SEGUIMIENTO POA
    /*------------- Rango Trimestre actual programado -----------------*/
    public function rango_programado_trimestre_actual($prod_id,$trimestre){
      $vfinal=0;
      if($trimestre==0){$vinicial=0;$vfinal=0;}
      elseif($trimestre==1){$vinicial=0;$vfinal=3;}
      elseif ($trimestre==2) {$vinicial=3;$vfinal=6;}
      elseif ($trimestre==3) {$vinicial=6;$vfinal=9;}
      elseif ($trimestre==4) {$vinicial=9;$vfinal=12;}

        $sql = 'select prod_id,(CASE WHEN sum(pg_fis)!=0 THEN sum(pg_fis) ELSE 0 END) as trimestre, g_id
                from prod_programado_mensual
                where prod_id='.$prod_id.' and (m_id>'.$vinicial.' and m_id<='.$vfinal.') and g_id='.$this->gestion.'
                GROUP BY prod_id,g_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- Rango Trimestre actual ejecutado -----------------*/
    public function rango_ejecutado_trimestre_actual($prod_id,$trimestre){
      $vfinal=0;
      if($trimestre==0){$vinicial=0;$vfinal=0;}
      elseif($trimestre==1){$vinicial=0;$vfinal=3;}
      elseif ($trimestre==2) {$vinicial=3;$vfinal=6;}
      elseif ($trimestre==3) {$vinicial=6;$vfinal=9;}
      elseif ($trimestre==4) {$vinicial=9;$vfinal=12;}

    $sql = 'select prod_id,(CASE WHEN sum(pejec_fis)!=0 THEN sum(pejec_fis) ELSE 0 END) as trimestre, g_id
            from prod_ejecutado_mensual
            where prod_id='.$prod_id.' and (m_id>'.$vinicial.' and m_id<='.$vfinal.') and g_id='.$this->gestion.'
            GROUP BY prod_id,g_id';
    $query = $this->db->query($sql);
    return $query->result_array();
    }
}
