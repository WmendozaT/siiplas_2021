<?php
class model_actividad extends CI_Model {
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
     
    /*------------ Relacion Insumo Actividad -------*/
    function insumo_actividad($act_id){
        if($this->gestion!=2020){
            $sql = 'select *
                from _insumoactividad ia 
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join insumo_gestion as ig On i.ins_id=ig.ins_id
                where act_id='.$act_id.' and i.ins_estado!=\'3\'  and ig.g_id='.$this->gestion.''; 
        }
        else{
            $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where act_id='.$act_id.' and i.ins_estado!=\'3\''; 
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_actividad_archivo($id){
        $sql = 'update _actividad_archivos set estado=0 WHERE id = '.$id.'';  
        $this->db->query($sql);
    }

    function get_actividad_archivo($id){
        $sql = 'select * from _actividad_archivos where id = '.$id.'';   
        $this->db->query($sql);
    }

    /*=========== SUMA PROGRAMADO (2019) ===========*/
    public function suma_programado($act_id,$gestion){
        $sql = 'select SUM(pg_fis) as suma
                from act_programado_mensual
                where act_id='.$act_id.' and g_id='.$gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=================================== LISTA DE PRODUCTOS ANUAL ====================================*/
    function list_act_anual($id_prod){
        $sql = 'SELECT a.*,tp.*
            from _actividades as a
            Inner Join indicador as tp On a."indi_id"=tp."indi_id"
            where a."prod_id"='.$id_prod.' and a."estado"!=\'3\' 
            ORDER BY a.act_id  asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA ACTIVIDADES PROGRAMADAS (2019) --------*/
    function list_actividad_gestion($prod_id,$gestion){
        $sql = 'select *
                from vista_actividad va
                Inner Join vista_actividades_temporalizacion_programado_dictamen as ap On ap.act_id=va.act_id 
                where va.prod_id='.$prod_id.' and ap.g_id='.$gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- GET ACTIVIDAD PROGRAMADO (2019) --------*/
    function get_actividad_gestion($act_id,$gestion){
        $sql = 'select *
                from vista_actividad va
                Inner Join vista_actividades_temporalizacion_programado_dictamen as ap On ap.act_id=va.act_id 
                where va.act_id='.$act_id.' and ap.g_id='.$gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function suma_ponderacion($prod_id){
        $sql = 'select SUM(act_ponderacion) as suma
                from _actividades
                where prod_id='.$prod_id.' and estado!=\'3\' '; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==============================================================================================================*/

    /*=================================== AGREGAR ACTIVIDAD  PROGRAMADO GESTION ====================================*/
    function add_act_gest($id_act,$gestion,$m_id,$pgfis,$pgfin){
        $data = array(
            'act_id' => $id_act,
            'm_id' => $m_id,
            'pg_fis' => $pgfis,
            'pg_fin' => $pgfin,
            'g_id' => $gestion,
        );
        $this->db->insert('act_programado_mensual',$data);
    }
    /*==============================================================================================================*/

    /*=================================== NRO DE ACTIVIDADES REGISTRADOS ====================================*/
    public function nro_actividades_gest($id_pr){
        $this->db->from('_actividades');
        $this->db->where('prod_id', $id_pr);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*==============================================================================================================*/
    /*=================================== LISTA DE ACTIVIDADESGESTION ANUAL ====================================*/
    function list_actgest_anual($id_act){
        $sql = 'SELECT *
            from act_programado_mensual
            where act_id='.$id_act.' and g_id='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==============================================================================================================*/
    /*=================================== GET DATOS  ACTIVIDAD  ====================================*/
    function get_actividad_id($id_act){
        $sql = '
            select *
            from _actividades as a
            Inner Join indicador as tp On a.indi_id=tp.indi_id
            Inner Join _productos as p On p.prod_id=a.prod_id
            where a.act_id='.$id_act.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==============================================================================================================*/
    /*=================================== META GESTION ACTIVIDAD ====================================*/
    public function meta_act_gest($id_act){
        $sql = 'SELECT SUM(pg_fis) as meta_gest
            from act_programado_mensual
            where act_id='.$id_act.' AND g_id='.$this->session->userdata("gestion").' '; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==============================================================================================================*/
    /*=================================== LISTA DE ACTIVIDADES PROGRAMADO GESTION  ====================================*/
    public function act_prog_mensual($id_act,$gest){
        $this->db->from('act_programado_mensual');
        $this->db->where('act_id', $id_act);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }

    function actividad_programado($act_id,$gestion){
        $sql = 'select *
                from vista_actividades_temporalizacion_programado_dictamen
                where act_id='.$act_id.' and g_id='.$gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /*==============================================================================================================*/
    /*=================================== LISTA DE ACTIVIDAD EJECUTADO GESTION  ====================================*/
    public function act_ejec_mensual($id_act,$gest){
        $this->db->from('act_ejecutado_mensual');
        $this->db->where('act_id', $id_act);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function nro_act_ejec_mensual($id_act,$gest){
        $this->db->from('act_ejecutado_mensual');
        $this->db->where('act_id', $id_act);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->num_rows();
    } 
    /*==============================================================================================================*/

    /*============================ BORRA DATOS DE LA ACTIVIDAD PROGRAMADO GESTION =================================*/
    public function delete_act_gest($id_act){ 
        $this->db->where('act_id', $id_act);
        $this->db->delete('act_programado_mensual'); 
    }
    /*=================================================================================================*/

     /*=================================== META GESTION ACTIVIDAD ====================================*/
    public function suma_monto_ponderado_total($id_prod){
        $sql = 'SELECT SUM(act_pres_p) as monto_total
            from _actividades
            where prod_id='.$id_prod.' and estado!=\'3\' '; 
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    /*==============================================================================================================*/
    /*================================= NRO DE ACTIVIDADES ======================================*/
    public function actividades_nro($id_p){
        $this->db->from('_actividades');
        $this->db->where('prod_id', $id_p);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*================================================================================================*/    



    /*=================================== PROGRAMADO ACTIVIDAD  POR GESTIONES ====================================*/
    public function programado_actividad($id_act,$gest) {
        $this->db->from('vista_actividades_temporalizacion_programado_dictamen');
        $this->db->where('act_id', $id_act);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==============================================================================================*/
    /*=================================== PROGRAMADO ACTIVIDAD  ====================================*/
    public function get_programado_actividad($id_act){
        $this->db->from('vista_actividades_temporalizacion_programado_dictamen');
        $this->db->where('act_id', $id_act);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==============================================================================================*/
    /*=================================== EJECUTADO ACTIVIDAD POR GESTIONES ====================================*/
    public function ejecutado_actividad($id_act,$gest){
        $this->db->from('vista_actividades_temporalizacion_ejecutado_dictamen');
        $this->db->where('act_id', $id_act);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==============================================================================================*/
    /*=================================== EJECUTADO ACTIVIDAD ====================================*/
    public function get_ejecutado_actividad($id_act){
        $this->db->from('vista_actividades_temporalizacion_ejecutado_dictamen');
        $this->db->where('act_id', $id_act);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==============================================================================================*/

    /*------------------ GET ACTIVIDAD PROGRAMADO -------------------*/
    public function programado_actividad_mensual($act_id){ 
        $sql = 'select *
                from act_programado_mensual
                where act_id='.$act_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- MONTO TOTAL ACTIVIDAD (2019) ------*/
    function monto_insumoactividad($act_id){
        if($this->gestion!=2020){
            $sql = 'select ia.act_id,SUM(i.ins_costo_total) as total
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where ia.act_id='.$act_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
                group by ia.act_id'; 
        }
        else{
            $sql = 'select ia.act_id,SUM(i.ins_costo_total) as total
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                where ia.act_id='.$act_id.' and i.ins_estado!=\'3\' and i.ins_gestion='.$this->gestion.'
                group by ia.act_id'; 
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
?>  
