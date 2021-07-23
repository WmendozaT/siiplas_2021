<?php
class Model_componente extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
    }
    //lista de organismo financiador

    /*--  Lista de Unidades --*/
    function lista_unidades($dist_id){
        $sql = 'select * 
                    from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.')'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------------ Relacion Proyecto Componente -------*/
    function proyecto_componente($proy_id){
        $sql = 'select *
                from vista_componentes_dictamen
                where proy_id='.$proy_id.'
                ORDER BY com_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ suma Ponderacion -------*/
    function suma_ponderacion($pfec_id){
        $sql = 'select SUM(com_ponderacion) as suma
                from _componentes
                where pfec_id='.$pfec_id.' and estado!=\'3\''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Relacion Insumo Componente -------*/
/*    function imsumo_componente($com_id){
        $sql = 'select *
                from insumocomponente ic
                Inner Join insumos as i On i.ins_id=ic.ins_id
                Inner Join insumo_gestion as ig On i.ins_id=ig.ins_id
                where ic.com_id='.$com_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------ LISTA DE SERVICIOS Y COMPONENTES PARA LA EVALUACIÓN -------*/
    function list_servicios_operaciones($pfec_id){
        $sql = 'select c.com_id,c.pfec_id,c.com_componente,c.com_ponderacion
                from _componentes c
                Inner Join _productos as p On p.com_id=c.com_id
                where c.pfec_id='.$pfec_id.' and c.estado!=\'3\' and p.estado!=\'3\'
                group by c.com_id,c.pfec_id,c.com_componente,c.com_ponderacion
                order by c.com_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=========== LISTA DE COMPONENTES ================*/
    public function componentes_id($id_f, $tp_id){
        $sql = 'select c.*,f.fun_id as resp_id,f.fun_id as resp_id, f.fun_nombre,f.fun_paterno,f.fun_materno,u.*,sa.*,tpsa.*
                from _componentes as c
                Inner Join funcionario as f On f.fun_id=c.resp_id
                Inner Join unidadorganizacional as u On u.uni_id=c.uni_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                where c.pfec_id='.$id_f.' and c.estado!=\'3\' 
                ORDER BY serv_cod asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function componentes_fun_id($id_f,$fun_id){
        $sql = 'select c.*,f.fun_id as resp_id,f.fun_id as resp_id, f.fun_nombre,f.fun_paterno,f.fun_materno,u.*,sa.*,tpsa.*
                from _componentes as c
                Inner Join funcionario as f On f.fun_id=c.resp_id
                Inner Join unidadorganizacional as u On u.uni_id=c.uni_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                where c.pfec_id='.$id_f.' and resp_id='.$fun_id.' and c.estado!=\'3\' 
                order by c.com_id  asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/
    /*================================= LISTA DE COMPONENTES NRO ======================================*/
    public function componentes_verif_nro($id_f,$nro){
        $sql = 'select *
                from _componentes as c
                Inner Join unidadorganizacional as ur On c.uni_id=ur.uni_id
                where c."pfec_id"='.$id_f.' and  c.com_nro='.$nro.' and c."estado"!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*================================================================================================*/
    /*================================= NRO DE COMPONENTES ======================================*/
    public function componentes_nro($id_f){
        $this->db->from('_componentes');
        $this->db->where('pfec_id', $id_f);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*================================================================================================*/
    /*======================= COMPONENTE (Operaciones) =============================*/
    public function get_componente($com_id){
        $sql = 'select *
                from _componentes as c
            
                Inner Join unidadorganizacional as u On u.uni_id=c.uni_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                where c.com_id='.$com_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and pfe.pfec_estado=\'1\''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_datos_componente($com_id,$gestion){
        $sql = 'select *
                from _componentes as c
                Inner Join funcionario as f On f.fun_id=c.resp_id
                Inner Join unidadorganizacional as u On u.uni_id=c.uni_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                where c.com_id='.$com_id.' and apg.aper_gestion='.$gestion.' and apg.aper_estado!=\'3\''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*===================================================================*/

    /*================== COMPONENTE X (Proy Inversion) =================*/
    public function get_componente_pi($com_id){
        $sql = 'select *
                from _componentes as c
                Inner Join funcionario as f On f.fun_id=c.resp_id
                Inner Join unidadorganizacional as u On u.uni_id=c.uni_id
                where c.com_id='.$com_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=====================================================================*/
    /*============================ BORRA DATOS F/E PTTO =================================*/
    public function delete_comp($id_c){ 

        $this->db->where('com_id', $id_c);
        $this->db->delete('_componentes');
    }
    /*======================================================================================*/

    /*================================= FASE-COMPONENTE NRO ======================================*/
    public function get_fase_componente_nro($pfec_id,$cod,$tp_id){
        if($tp_id==1){
            $sql = 'select *
                from _componentes c
                where c.pfec_id='.$pfec_id.' and c.com_nro=\''.$cod.'\' and c.estado!=\'3\'';
        }
        else{
            $sql = 'select *
                from _componentes c
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                where c.pfec_id='.$pfec_id.' and sa.serv_cod =\''.$cod.'\' and c.estado!=\'3\'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*======================================================================================*/

    public function list_componentes_total($proy_id){
        $sql = 'select c.proy_id,c.pfec_id,c.com_id,c.com_componente,nc.prod, t.total
                from vista_componentes_dictamen c
                Inner Join (select com_id,count(com_id) as prod
                from vista_producto
                group by com_id) as nc On nc.com_id=c.com_id
                Inner Join (select proy_id,count(*) as total
                from vista_producto
                group by proy_id) as t On t.proy_id=c.proy_id
                where c.proy_id='.$proy_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }





    /*PROYECTO DE INVERSION*/
    /*----- Lista de Subactividades --------*/
    public function list_subactividades_pi(){
        $sql = 'select *
                from servicios_actividad
                where serv_tp=\'1\' and activo=\'1\'
                order by serv_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //// GESTION 2021 

    /*--- Tipo de Subactividad ---*/
    function tp_subactividad(){
        $sql = 'select *
                from tipo_subactividad
                order by tp_sact asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de Subactividad ---*/
    function lista_subactividad($proy_id){
        $sql = 'select *
                from vista_subactividades
                where proy_id='.$proy_id.' and aper_gestion='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista de Subactividad alineados a Actividad por Regional---*/
    function lista_poa_subactividad($dep_id){
        $sql = 'select *
                from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') p
                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id
                Inner Join vista_subactividades as sa On sa.proy_id=pfe.proy_id
                where pfe.pfec_estado=\'1\' and pfe.estado!=\'3\'
                order by ue,prog,proy,act asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Temporalidad Programado por componente ---*/
    function componente_temporalidad_programado($com_id){
        $sql = 'select *
                from vista_temporalidad_programado_componente
                where com_id='.$com_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Temporalidad Ejecutado por componente ---*/
    function componente_temporalidad_ejecutado($com_id){
        $sql = 'select *
                from vista_temporalidad_ejecutado_componente
                where com_id='.$com_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

///// 2021
    /*--- Presupuesto Total Programado por Componente ---*/
    function componente_ppto_total($com_id){
        $sql = 'select sum(i.ins_costo_total) total_ppto
                from _componentes c
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join _insumoproducto as iprod On iprod.prod_id=prod.prod_id
                Inner Join insumos as i On i.ins_id=iprod.ins_id
                where pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and c.com_id='.$com_id.' and prod.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}