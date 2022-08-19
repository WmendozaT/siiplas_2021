<?php
class Model_mestrategico extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
   
   /*============================ OBJETIVOS ESTRATEGICOS ================================*/

    /*--- pdes -----*/
    public function lista_pdes_pilares(){
        $sql = 'select *
                from pdes
                where pdes_jerarquia=\'1\' and pdes_estado=\'1\' and pdes_depende=\'0\' and ('.$this->gestion.'>=pdes_gestion and '.$this->gestion.'<=pdes_gestion_final)
                order by pdes_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


   /*--------------------- OBJETIVOS ESTRATEGICOS ----------------------*/
    public function list_objetivos_estrategicos(){
        $sql = 'select *
                from _objetivos_estrategicos
                where (obj_gestion_inicio<='.$this->gestion.' and obj_gestion_fin>='.$this->gestion.') and obj_estado!=\'3\'
                order by obj_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET OBJETIVOS ESTRATEGICOS ----------------------*/
    public function get_objetivos_estrategicos($obj_id){
        $sql = 'select *
                from _objetivos_estrategicos
                where obj_id='.$obj_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
   /*====================================================================================*/

    /*============================ ACCIONES ESTRATEGICAS ================================*/
    public function list_acciones_estrategicas($obj_id){
        $sql = 'select *
                from _acciones_estrategicas
                where obj_id='.$obj_id.' and acc_estado!=\'3\' and ('.$this->gestion.'>=g_id_inicio and '.$this->gestion.'<=g_id_fin)
                order by acc_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET ACCION ESTRATEGICA ----------------------*/
    public function get_acciones_estrategicas($acc_id){
        $sql = 'select *
                from _acciones_estrategicas ae
                where ae.acc_id='.$acc_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
   /*====================================================================================*/


    /*============================ RESULTADOS MEDIANO PLAZO ================================*/
    public function list_resultados_mplazo($acc_id){
        $sql = 'select *
                from _resultado_mplazo rmp
                Inner Join indicador as i On i.indi_id=rmp.indi_id
                Inner Join funcionario as f On f.fun_id=rmp.resp_id
                where rmp.acc_id='.$acc_id.' and rmp.rm_estado!=\'3\' and (rmp.gestion_desde<='.$this->gestion.' and rmp.gestion_hasta>='.$this->gestion.')
                order by rmp.rm_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET RESULTADO MEDIANO PLAZO ----------------------*/
    public function get_resultado_mplazo($rm_id){
        $sql = 'select *
                from _resultado_mplazo
                where rm_id='.$rm_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET RESULTADO MEDIANO PLAZO PROGRAMACION FISICA --------------*/
    public function get_resultado_mplazo_programado($rm_id){
        $sql = 'select *
                from _resultado_mplazo_programado
                where rm_id='.$rm_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET RESULTADO MEDIANO PLAZO PROGRAMACION GESTION FISICA --------------*/
    public function get_resultado_mplazo_programado_gestion($rm_id){
        $sql = 'select *
                from _resultado_mplazo_programado
                where rm_id='.$rm_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- BORRAR PROGRAMADO RESULTADO---------------------------*/
    public function delete_prog_res($rm_id){ 
        $this->db->where('rm_id', $rm_id);
        $this->db->delete('_resultado_mplazo_programado'); 
    }

    /*------------- GET RESULTADO CORTO PLAZO PROGRAMACION FISICA --------------*/
    public function get_resultado_cplazo_programado($rmp_id){
        $sql = 'select *
                from v_programacion_rcplazo
                where rmp_id='.$rmp_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- BORRAR PROGRAMADO RESULTADO CORTO PLAZO---------------------------*/
    public function delete_prog_res_cplazo($rmp_id){ 
        $this->db->where('rmp_id', $rmp_id);
        $this->db->delete('_resultado_cplazo_programado'); 
    }

    /*============================ RESULTADOS DE CORTO PLAZO ================================*/
    public function list_resultados_cplazo($acc_id){
        $sql = 'select *
                from _resultado_mplazo rmp
                Inner Join indicador as i On i.indi_id=rmp.indi_id
                Inner Join funcionario as f On f.fun_id=rmp.resp_id
                Inner Join _resultado_mplazo_programado as rmprog On rmprog.rm_id=rmp.rm_id
                where rmp.acc_id='.$acc_id.' and rmp.rm_estado!=\'3\' and (rmp.gestion_desde<='.$this->gestion.' and rmp.gestion_hasta>='.$this->gestion.') and rmprog.g_id='.$this->gestion.'
                order by rmp.rm_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*============================ PRODUCTOS MEDIANO PLAZO ================================*/
    public function list_pterminal_mplazo($rm_id){
        $sql = 'select *
                from _pterminal_mplazo ptm
                Inner Join indicador as i On i.indi_id=ptm.indi_id
                Inner Join funcionario as f On f.fun_id=ptm.resp_id
                where ptm.rm_id='.$rm_id.' and ptm.ptm_estado!=\'3\' and (ptm.gestion_desde<='.$this->gestion.' and ptm.gestion_hasta>='.$this->gestion.')
                order by ptm.ptm_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

   /*============================ PRODUCTOS CORTO PLAZO ================================*/
    public function list_pterminal_cplazo($rm_id){
        $sql =  'select *
                from _pterminal_mplazo ptm
                Inner Join indicador as i On i.indi_id=ptm.indi_id
                Inner Join funcionario as f On f.fun_id=ptm.resp_id
                Inner Join _pterminal_mplazo_programado as ptprog On ptprog.ptm_id=ptm.ptm_id
                where ptm.rm_id='.$rm_id.' and ptm.ptm_estado!=\'3\' and (ptm.gestion_desde<='.$this->gestion.' and ptm.gestion_hasta>='.$this->gestion.') and ptprog.g_id='.$this->gestion.'
                order by ptm.ptm_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- GET PRODUCTO TERMINAL MEDIANO PLAZO ----------------------*/
    public function get_pterminal_mplazo($ptm_id){
        $sql = 'select *
                from _pterminal_mplazo
                where ptm_id='.$ptm_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET PRODUCTO TERMINAL PROGRAMACION FISICA --------------*/
    public function get_pterminal_mplazo_programado($ptm_id){
        $sql = 'select *
                from _pterminal_mplazo_programado
                where ptm_id='.$ptm_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET PRODUCTO TERMINAL MEDIANO PLAZO PROGRAMACION GESTION FISICA --------------*/
    public function get_pterminal_mplazo_programado_gestion($ptm_id){
        $sql = 'select *
                from _pterminal_mplazo_programado
                where ptm_id='.$ptm_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET PRODUCTO TERMINAL CORTO PLAZO PROGRAMACION FISICA --------------*/
    public function get_pterminal_cplazo_programado($ptmp_id){
        $sql = 'select *
                from v_programacion_ptcplazo
                where ptmp_id='.$ptmp_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET PRODUCTO TERMINAL CORTO PLAZO PROGRAMACION GESTION FISICA --------------*/
    public function get_pterminal_cplazo_programado_gestion($ptm_id){
        $sql = 'select *
                from _pterminal_mplazo_programado
                where ptm_id='.$ptm_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------- BORRAR PROGRAMADO PRODUCTO TERMINAL ---------------------------*/
    public function delete_prog_pt($ptm_id){ 
        $this->db->where('ptm_id', $ptm_id);
        $this->db->delete('_pterminal_mplazo_programado'); 
    }

    /*--------------------- GET RESPONSABLE ----------------------*/
    public function responsables(){
        $sql = 'select *
            from funcionario
            where fun_estado!=\'3\' order by fun_id  asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------------------- BORRAR PROGRAMADO PRODUCTO CORTO PLAZO---------------------------*/
    public function delete_prog_pt_cplazo($ptmp_id){ 
        $this->db->where('ptmp_id', $ptmp_id);
        $this->db->delete('_pterminal_cplazo_programado'); 
    }

   /*====================================================================================*/


   /*================================= VINCULACION A CARPETA POA =========================*/
       public function acciones_mediano_plazo(){
        $sql = 'select ae.acc_id,ae.acc_codigo,acc_descripcion,ae.pdes_id,oe.obj_id,oe.obj_descripcion, oe.obj_gestion_inicio,oe.obj_gestion_fin
                from _acciones_estrategicas ae
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                where ae.acc_estado!=\'3\' and ae.g_id='.$this->gestion.' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.')
                group by ae.acc_id,ae.acc_codigo,acc_descripcion,ae.pdes_id,oe.obj_id,oe.obj_descripcion,oe.obj_gestion_inicio,oe.obj_gestion_fin
                order by ae.acc_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================================= ACCIONES ESTRATEGICAS - RESULTADOS (REPORTES) =========================*/
       public function acciones_mediano_plazo_resultados(){
        $sql = 'select ae.acc_id,ae.acc_codigo,acc_descripcion,ae.pdes_id,oe.obj_id,oe.obj_descripcion, oe.obj_gestion_inicio,oe.obj_gestion_fin,rmp.rm_resultado,rmp.rm_indicador,rmp.rm_fuente_verificacion
                from _acciones_estrategicas ae
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                Inner Join _resultado_mplazo as rmp On rmp.acc_id=ae.acc_id
                where ae.acc_estado!=\'3\' and ae.g_id='.$this->gestion.' and oe.obj_estado!=\'3\' and (oe.obj_gestion_inicio<='.$this->gestion.' and oe.obj_gestion_fin>='.$this->gestion.')
                group by ae.acc_id,ae.acc_codigo,acc_descripcion,ae.pdes_id,oe.obj_id,oe.obj_descripcion,oe.obj_gestion_inicio,oe.obj_gestion_fin,rmp.rm_resultado,rmp.rm_indicador,rmp.rm_fuente_verificacion
                order by ae.acc_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*=================================PARA VINCULACION PEI =========================*/
       public function group_pterminal($pt_id){
        $sql = 'select proy_id
                from vista_producto vp
                where vp.pt_id='.$pt_id.'
                group by proy_id
                order by proy_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=================================LISTA VINCULACION PEI - LISTA =========================*/
       public function list_vin_pterminal($proy_id,$pt_id){
        $sql = 'select *
                from vista_producto vp
                Inner Join _proyectos as p On vp.proy_id=p.proy_id
                where vp.proy_id='.$proy_id.' and vp.pt_id='.$pt_id.'
                order by vp.proy_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*================== LINEACION POA-PEI, PRODUCTO/ACTIVIDAD =====================*/
       public function alineacion($tp,$id){
        // $tp = tp_id : proyecto
        if($tp==1){
            $sql = 'select *
                    from _actividades a
                    Inner Join _productos as pr On pr.prod_id=a.prod_id
                    Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                    Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                    where a.act_id='.$id.'';
        }
        else{
            $sql = 'select *
                    from _productos pr
                    Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                    Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                    where pr.prod_id='.$id.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

   /*-- RESULTADOS FINALES --*/
    /* get resultado final*/        
    public function get_resultado_final($rf_id){
        if($this->gestion<=2022){
            $sql = 'select *
                from vtemp_rfinal
                where rf_id='.$rf_id.' and rf_estado!=\'3\'';
        }
        else{
            $sql = 'select *
                from vtemp_rfinal2025
                where rf_id='.$rf_id.' and rf_estado!=\'3\'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* Lista Resultados finales*/        
    public function list_resultados_final($obj_id){
        if($this->gestion<=2022){
            $sql = 'select *
                from vtemp_rfinal
                where obj_id='.$obj_id.'';
        }
        else{
            $sql = 'select *
                from vtemp_rfinal2025
                where obj_id='.$obj_id.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- INDICADORES --*/
    /* get Indicadores por resultado intermedio*/        
    public function get_list_indicadores($rm_id){
        if($this->gestion<=2022){
            $sql = 'select *
                from vindicadores
                where rm_id='.$rm_id.'';
        }
        else{
            $sql = 'select *
                from vindicadores2025
                where rm_id='.$rm_id.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* get Indicador*/        
    public function get_indicador($ptm_id){
        if($this->gestion<=2022){
            $sql = 'select *
                from vindicadores
                where ptm_id='.$ptm_id.'';
        }
        else{
            $sql = 'select *
                from vindicadores2025
                where ptm_id='.$ptm_id.'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* Lista Indicadores del proceso (Producto terminal)*/        
    public function list_indicadores_pei($acc_id){
        $sql = 'select pt.*,rmp.*
               from _acciones_estrategicas ae
               Inner Join _resultado_mplazo as rmp On rmp.acc_id=ae.acc_id
               Inner Join _pterminal_mplazo as pt On pt.rm_id=rmp.rm_id
               where ae.acc_id='.$acc_id.' and rmp.rm_estado!=\'3\' and pt.ptm_estado!=\'3\'
               order by pt.ptm_codigo desc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* Lista Indicadores del proceso (Producto terminal)*/        
    public function list_indicadores_pei2($ae){
        $sql = 'select pt.*,rmp.*
               from _acciones_estrategicas ae
               Inner Join _resultado_mplazo as rmp On rmp.acc_id=ae.acc_id
               Inner Join _pterminal_mplazo as pt On pt.rm_id=rmp.rm_id
               where ae.ae='.$ae.' and rmp.rm_estado!=\'3\' and pt.ptm_estado!=\'3\'
               order by pt.ptm_codigo desc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /* get Indicadores del proceso (Producto terminal)*/        
    public function get_indicador_pterminal($codigo){
        $sql = ' select *
                from _pterminal_mplazo indi
                Inner Join _resultado_mplazo as rmp On indi.rm_id=rmp.rm_id
                Inner Join _acciones_estrategicas as acc On acc.acc_id=rmp.acc_id
                where indi.indi_codigo='.$codigo.' and indi.ptm_estado!=\'3\' and acc.acc_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}