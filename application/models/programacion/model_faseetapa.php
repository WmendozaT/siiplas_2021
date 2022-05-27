<?php
class Model_faseetapa extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion'); /// Gestion
    }
    //lista de organismo financiador

    /*============================ LISTA UNIDAD EJECUTORA NUEVO =============================*/
/*    public function list_fases_gestiones($pfec_id){
        $sql = 'select *
                from ptto_fase_gestion
                where pfec_id='.$pfec_id.' and estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------- fechas - gestiones , fase etapa componente ---------------*/
    function fechas_fase($pfec_id){
        $this->db->select(' extract(years from (pfec_fecha_inicio_ddmmaaa))as inicio,
                            extract(years from (pfec_fecha_fin_ddmmaaa))as final');
        $this->db->from('_proyectofaseetapacomponente ');
        $this->db->where('pfec_id', $pfec_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*-------------- Get Fase ------------------------*/
    public function get_fase($pfec_id){
        $sql = 'select *
                from _proyectofaseetapacomponente
                where pfec_id='.$pfec_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=================== FASE GESTION VIGENTE ===============*/
/*    public function fase_gestion($id_f,$gest){
        $this->db->from('ptto_fase_gestion');
        $this->db->where('pfec_id', $id_f);
        $this->db->where('g_id', $gest);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*======================================================*/

    /*==================== VERIF FUENTE DE FINANCIAMIENTO ======================*/
/*    public function nro_fuentes($ptofecg_id){
        $this->db->from('_ffofet');
        $this->db->where('ptofecg_id', $ptofecg_id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*====================================================================================*/

    /*==================== SQL QUE VERIFICA EN INSUMOS ======================*/
/*    public function fuente_insumo($ffofet_id){
        $this->db->from('insumo_financiamiento');
        $this->db->where('ffofet_id', $ffofet_id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*==========================================================================*/

    function datos_fase_etapa($id_f,$id_p) ////// para calcular las fechas - tabla _proyectofaseetapacomponente
    {
        $this->db->select(' extract(years from (pfec_fecha_inicio_ddmmaaa))as actual,
                            extract(years from (pfec_fecha_fin_ddmmaaa))as final');
        $this->db->from('_proyectofaseetapacomponente ');
        $this->db->where('pfec_id', $id_f);
        $this->db->where('proy_id', $id_p);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*==================== NRO DE FASES REGISTRADOS DEL PROYECTO X ======================*/
    public function nro_fase($id_proy){
        $this->db->from('_proyectofaseetapacomponente');
        $this->db->where('proy_id', $id_proy);
        $this->db->where('pfec_estado', 1); 
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*====================================================================================*/

    /*===== FASE ACTIVA DEL PROYECTO X =========*/
    function get_id_fase($proy_id){
        $sql = 'select 
                pfe.pfec_id as id,
                pfe.proy_id,
                pfe.fas_id,
                ep.fas_fase as fase,
                pfe.eta_id,
                et.eta_etapa as etapa,
                pfe.pfec_descripcion as descripcion,
                pfe.pfec_fecha_inicio_ddmmaaa as inicio,
                pfe.pfec_fecha_inicio,
                pfe.pfec_fecha_fin_ddmmaaa as final,
                pfe.pfec_fecha_fin,
                pfe.pfec_ejecucion,
                pfe.pfec_estado,
                pfe.pfec_ptto_fase,
                pfe.pfec_ptto_fase_e,
                pfe.pfec_eficacia,
                pfe.pfec_eficiencia,
                pfe.pfec_eficiencia_pe,
                pfe.pfec_eficiencia_fi,
                apg.aper_id,
                apg.aper_gestion,
                apg.aper_estado
                 
                from _proyectofaseetapacomponente pfe
                Inner Join _fases as ep On pfe.fas_id=ep.fas_id
                Inner Join _etapas as et On pfe.eta_id=et.eta_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                where pfe.proy_id='.$proy_id.' and pfe.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_estado!=\'3\'
                order by pfe.pfec_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


/*    public function get_id_fase($id_proy){
        $this->db->select(" p.pfec_id as id,
                            p.proy_id,
                            p.fas_id,
                            ep.fas_fase as fase,
                            p.eta_id,
                            et.eta_etapa as etapa,
                            p.pfec_descripcion as descripcion,
                            p.pfec_fecha_inicio_ddmmaaa as inicio,
                            p.pfec_fecha_inicio,
                            p.pfec_fecha_fin_ddmmaaa as final,
                            p.pfec_fecha_fin,
                            p.pfec_ejecucion,
                            (CASE WHEN p.pfec_ejecucion='1' THEN 'DIRECTA'
                                  WHEN p.pfec_ejecucion='2' THEN 'DELEGADA'
                                  ELSE 'NULL'
                            END) ejec,
                            p.pfec_estado,
                            p.pfec_ptto_fase,
                            p.pfec_ptto_fase_e,
                            p.pfec_eficacia,
                            p.pfec_eficiencia,
                            p.pfec_eficiencia_pe,
                            p.pfec_eficiencia_fi,
                            uo.uni_unidad");
        $this->db->from('_proyectofaseetapacomponente p');
        $this->db->join('_fases ep', 'p.fas_id = ep.fas_id', 'left');
        $this->db->join('_etapas et', 'p.eta_id = et.eta_id', 'left');
        $this->db->join('unidadorganizacional uo', 'uo.uni_id = p.unidad_ejec', 'left');
        $this->db->where('p.proy_id', $id_proy);
        $this->db->where('p.pfec_estado', 1);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*=========================== ================================ ============================*/
    /*============================== LISTA PRESUPUESTO ASIGNADO =====================*/
/*    public function fase_presupuesto_id($id_fg){
        $query=$this->db->query('
        select fg.*,ff.*,of.*                                
        from "public"."_ffofet" as fg
        Inner Join "public"."fuentefinanciamiento" as ff On ff."ff_id" = fg."ff_id"
        Inner Join "public"."organismofinanciador" as of On of."of_id" = fg."of_id"
        where fg."ptofecg_id"='.$id_fg.' 
        ORDER BY fg."ffofet_id"  asc');

        return $query->result_array();
    }*/

    public function presupuesto_asignados($proy_id,$gestion){
         $sql = 'select *
                from fnpresupuesto_asignado_proy('.$proy_id.','.$gestion.')';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=====================================================================================*/
    /*=========================== VERIFICA SI SI LA FASE ESTA ENCENDIDO/APAGADO ======================*/
    public function verif_off($id_f,$id_p)  //////////// lista de fases y etapa del proyecto x 
    {
        $this->db->select('pfec_estado');
        $this->db->from('_proyectofaseetapacomponente ');
        $this->db->where('pfec_id', $id_f);
        $this->db->where('proy_id', $id_p);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==================== NRO DE FASESGESTION REGISTRADOS DE LA FASE X ======================*/
    public function nro_fasegestion($id_fase){
        $this->db->from('ptto_fase_gestion');
        $this->db->where('pfec_id', $id_fase); 
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*====================================================================================*/
    /*==================== NRO DE FASESGESTION ACTUAL REGISTRADOS DE LA FASE X ======================*/
    public function nro_fasegestion_actual($id_fase,$gest){
        $this->db->from('ptto_fase_gestion');
        $this->db->where('pfec_id', $id_fase); 
        $this->db->where('g_id', $gest); 
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*====================================================================================*/
    /*============================ BORRA DATOS DE F/E GESTION PTTO=================================*/
/*    public function delete_fechas_faseetapa($p_id){ 

        $this->db->where('pfec_id', $p_id);
        $this->db->delete('ptto_fase_gestion'); 
    }*/
    /*============================ END BORRA DATOS DE F/E GESTION =================================*/
    /*=========================== LISTA DE FASES DEL  PROYECTO X ============================*/
    public function fase_etapa_proy($proy_id){
        $this->db->select("         p.pfec_id as id,
                                    p.proy_id,
                                    p.fas_id,
                                    ep.fas_fase as fase,
                                    et.eta_etapa as etapa,
                                    p.pfec_descripcion as descripcion,
                                    p.pfec_fecha_inicio_ddmmaaa as inicio,
                                    p.pfec_fecha_fin_ddmmaaa as final,
                                    (CASE WHEN p.pfec_ejecucion='1' THEN 'DIRECTA'
                                          WHEN p.pfec_ejecucion='2' THEN 'DELEGADA'
                                          ELSE 'NULL'
                                    END) ejec,
                                    p.pfec_estado,
                                    p.pfec_fecha_inicio,
                                    p.pfec_fecha_fin,
                                    p.pfec_ptto_fase,
                                    p.pfec_ptto_fase_e,
                                    p.unidad_ejec,
                                    ue.uni_unidad,
                                    apg.aper_gestion,
                                    apg.aper_proy_estado,
                                    apg.aper_id
                                    ");
        $this->db->from('_proyectofaseetapacomponente p');
        $this->db->join('_fases ep', 'p.fas_id = ep.fas_id', 'left');
        $this->db->join('_etapas et', 'p.eta_id = et.eta_id', 'left');
        $this->db->join('unidadorganizacional ue', 'p.unidad_ejec = ue.uni_id', 'left');
        $this->db->join('aperturaprogramatica apg', 'apg.aper_id = p.aper_id', 'left');
        $this->db->where('proy_id', $proy_id);
        $this->db->where('estado IN (1,2)');
        //$this->db->where('estado', '1' OR 'estado', '2');
       // $this->db->or_where('estado', '2');
        $this->db->order_by("pfec_id","asc");
        $query = $this->db->get();
        return $query->result_array();
    }
/*=====================================================================================================*/
    
/*===========================================(EDITADO) FASE X DEL PROYECTO Y =========================================*/
    public function fase_etapa($id_fase,$id_proy){  
        $this->db->select("         p.pfec_id as id,
                                    p.proy_id,
                                    p.fas_id,
                                    ep.fas_fase as fase,
                                    p.eta_id,
                                    et.eta_etapa as etapa,
                                    p.pfec_descripcion as descripcion,
                                    p.pfec_fecha_inicio_ddmmaaa as inicio,
                                    p.pfec_fecha_inicio,
                                    p.pfec_fecha_fin_ddmmaaa as final,
                                    p.pfec_fecha_fin,
                                    p.pfec_ejecucion,
                                    (CASE WHEN p.pfec_ejecucion='1' THEN 'DIRECTA'
                                          WHEN p.pfec_ejecucion='2' THEN 'DELEGADA'
                                          ELSE 'NULL'
                                    END) ejec,
                                    p.pfec_estado,
                                    p.pfec_ptto_fase,
                                    p.pfec_ptto_fase_e,
                                    p.unidad_ejec,
                                    p.pfec_eficacia,
                                    p.pfec_eficiencia,
                                    p.pfec_eficiencia_pe,
                                    p.pfec_eficiencia_fi,
                                    ue.uni_unidad
                                    ");
        $this->db->from('_proyectofaseetapacomponente p');
        $this->db->join('_fases ep', 'p.fas_id = ep.fas_id', 'left');
        $this->db->join('_etapas et', 'p.eta_id = et.eta_id', 'left');
        $this->db->join('unidadorganizacional ue', 'p.unidad_ejec = ue.uni_id', 'left');
        $this->db->where('pfec_id', $id_fase);
        $this->db->where('proy_id', $id_proy);
        $query = $this->db->get();
        return $query->result_array();
    }
/*======================================================================================================*/

/*========================================= CALCULA NUEVO/CONTINUO ===================================*/
    public function calcula_nc($gest_inicio){
        $resp='';
        if($gest_inicio==$this->session->userdata('gestion')){
            $resp='NUEVO';
        }
        elseif ($gest_inicio!=$this->session->userdata('gestion')) {
            $resp='CONTINUIDAD';
        }
        return $resp;
    }

    public function calcula_nc2($gest_inicio){
        $resp='';
        if($gest_inicio==$this->session->userdata('gestion')){
            $resp='NUEVO';
        }
        elseif ($gest_inicio!=$this->session->userdata('gestion')) {
            $resp='CONT.';
        }
        return $resp;
    }
/*======================================================================================================*/
/*========================================= CALCULA ANUAL/PLURIANUAL ===================================*/
    public function calcula_ap($gest_inicio,$gest_final){
        $resp='';
        if(($gest_final-$gest_inicio)==0){
            $resp='ANUAL';
        }
        elseif(($gest_final-$gest_inicio)!=0) {
            $resp='PLURI-ANUAL';
        }
        return $resp;
    }
/*======================================================================================================*/
/*================================ FASE ETAPA PRESUPUESTO GESTIONES========================================*/
    public function fase_etapa_gestion($id_f,$gest){
        $query=$this->db->query('SELECT * FROM ptto_fase_gestion WHERE pfec_id = '.$id_f.' AND g_id = '.$gest.' AND (estado = \'1\' OR estado = \'2\')');
        return $query->result_array(); 
    }
/*======================================================================================================*/
/*================================ FASE ETAPA PRESUPUESTO GESTIONES========================================*/
    public function verif_fase_etapa_gestion($id_f,$gest){
        $query=$this->db->query('SELECT * FROM ptto_fase_gestion WHERE pfec_id = '.$id_f.' AND g_id = '.$gest.' AND (estado = \'1\' OR estado = \'2\')');
        return $query->num_rows();
    }

    public function verif_fase_etapa_gestion2($id_f,$gest){
        $query=$this->db->query('SELECT * FROM ptto_fase_gestion WHERE pfec_id = '.$id_f.' AND g_id = '.$gest.'');
        return $query->num_rows();
    }
/*======================================================================================================*/

/*================================ AGREGA INDICADORES ========================================*/
    function add_indicador_fase($fase_id,$eficacia,$financiera,$ejecucion,$fisica){
        $data = array(
            'pfec_eficacia' => $eficacia,
            'pfec_eficiencia' => $financiera,
            'pfec_eficiencia_pe' => $ejecucion,
            'pfec_eficiencia_fi' => $fisica,
        );
        $this->db->WHERE('pfec_id', $fase_id);
        return $this->db->UPDATE('_proyectofaseetapacomponente', $data);
    }
/*======================================================================================================*/

    public function fase_etapa_gestiones($id_f){  ////// para el editado de las fases y etapas, y el listado para el componente 
        $this->db->select("*");
        $this->db->from('_proyectofaseetapacomponentegestion');
        $this->db->where('pfec_id', $id_f);
        $this->db->order_by("pfec_id","asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    /*==================================== ACTIVAR FASE ETAPA ================================================*/
    public function encender_fase_etapa($id_f,$id_p){
      $update= pg_query("UPDATE _proyectofaseetapacomponente SET pfec_estado = '0' WHERE proy_id='".$id_p."'");
      $update= pg_query("UPDATE _proyectofaseetapacomponente SET pfec_estado = '1' WHERE pfec_id='".$id_f."' AND proy_id='".$id_p."'");
      $update= pg_query("UPDATE ptto_fase_gestion SET estado = '2' WHERE pfec_id='".$id_f."'");
      
    }
    // /*=============================== VERIFICANDO PARA EL MONTO DE TOPE ASIGNADO==========================*/
    public function nro_ffofet($fg_id){
        $this->db->from('_ffofet');
        $this->db->where('ptofecg_id', $fg_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*============================================================ SUMA TECHO PRESUPUESTO ===================================================*/
    public function techo_presupuestario($ptofecg_id){
        
         $sql = 'SELECT Sum(ffofet_monto)as suma_techo
                from _ffofet
                where ptofecg_id='.$ptofecg_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*============================================================================================== ===========================================*/


    /*========================== IMAGENES DE EJECUCION DE LA FASE DEL  PROYECTO =============================*/
/*    public function get_img_ejec($id_fase){
        $this->db->from('fase_ejecucion_adjuntos');
        $this->db->where('pfec_id',$id_fase);
        $this->db->where('tip_doc','1');
        $query = $this->db->get();
        return $query->result_array();
    }*/

    /*================================= FASE ETAPA ====================================*/
    public function fases (){
        $sql = 'select *
                from _fases
                where fas_estado!=\'0\'
                order by fas_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function etapas (){
        $this->db->from('_etapas');
        $this->db->where('eta_estado',1);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*=============================================================================*/
    /*============================== INSERTA ASIGNACION DE PRESUPUESTOS =====================*/
/*    public function store_asig($dat1,$dat2,$dat3,$dat4,$nro){
            $data_to_store = array( ///// Tabla _ffofet
                'ptofecg_id' => $dat1,
                'ff_id' => $dat2,
                'of_id' => $dat3,
                'ffofet_monto' => $dat4,
                'nro' => $nro,
                );
            $this->db->insert('_ffofet', $data_to_store);
    }*/
    /*=====================================================================================*/
    /*============================== LISTA PRESUPUESTO ASIGNADO =====================*/
/*    public function _ffofet($id_fg)
    {
        $query=$this->db->query('SELECT fg.*,ff.*,of.*
                                        
        FROM "public"."_ffofet" as fg
        Inner Join "public"."fuentefinanciamiento" as ff On ff."ff_id" = fg."ff_id"
        Inner Join "public"."organismofinanciador" as of On of."of_id" = fg."of_id"
        where fg."ptofecg_id"='.$id_fg.' ORDER BY fg."ptofecg_id"  asc');

        return $query->result_array();
    }*/
    /*=====================================================================================*/
    /*============================================================ SUMA TECHO PRESUPUESTO ===================================================*/
/*    public function get_techo_id($ffofet_id){
        
         $sql = 'SELECT ff.*,ffi.*,fof.*
                from _ffofet as ff
                Inner Join fuentefinanciamiento as ffi On ffi."ff_id" = ff."ff_id"
                Inner Join organismofinanciador as fof On fof."of_id" = ff."of_id"
                where ff."ffofet_id"='.$ffofet_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*============================================================================================== ===========================================*/
    /*============================== NRO DE PRESUPUESTO ASIGNADOS =====================*/
/*    public function verif_presupuesto_activo($id_fg){
        $this->db->from('_ffofet');
        $this->db->where('ptofecg_id', $id_fg);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*=====================================================================================*/

    /*============================== FUENTE FINANCIAMIENTO =====================*/
/*    public function fuentefinanciamiento(){
        $this->db->from('fuentefinanciamiento');
        $this->db->where('ff_estado', 1);
    //    $this->db->where('ff_gestion', $this->session->userdata('gestion'));
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*=====================================================================================*/
    /*============================== ORGANISMO FINANCIADOR =====================*/
/*    public function organismofinanciador(){
        $this->db->from('organismofinanciador');
        $this->db->where('of_estado', 1);
    //    $this->db->where('of_gestion', $this->session->userdata('gestion'));
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*=====================================================================================*/
    /*============================ BORRA DATOS F/E=================================*/
    public function delete_fe_ptto($id_f){ 

        $this->db->where('pfec_id', $id_f);
        $this->db->delete('ptto_fase_gestion');

    }
    /*======================================================================================*/
    /*============================ BORRA DATOS F/E PTTO =================================*/
    public function delete_fe($id_f){ 

        $this->db->where('pfec_id', $id_f);
        $this->db->delete('_proyectofaseetapacomponente');
    }
    /*======================================================================================*/

    /*============================ PTTO GESTION FASE =================================*/
    public function ptto_fase($id_f){
        $sql = 'SELECT *
                from ptto_fase_gestion
                where pfec_id='.$id_f.' and estado!=\'3\'
                order by ptofecg_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function sum_ptto_fase($id_f){
        $sql = 'SELECT SUM(pfecg_ppto_total) as programado, SUM(pfecg_ppto_ejecutado) as ejecutado
                from ptto_fase_gestion
                where pfec_id='.$id_f.' and estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*======================================================================================*/

    /*-------- SUMA MONTO ASIGNADO POR GESTIONES DE LA FASE --------*/
    function suma_ptto_asignado_fuente($ptofecg_id){
        $sql = 'select ffofet_id, SUM(ffofet_monto)as asignado
                from _ffofet
                where ptofecg_id='.$ptofecg_id.'
                group by ffofet_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- SUMA MONTO PROGRAMADO POR GESTIONES DE LA FASE --------*/
    function suma_ptto_programado_fuente($ptofecg_id){
        $sql = 'select i.aper_id, p.proy_id,SUM(i.ins_costo_total) programado
                from insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id 
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                where i.aper_id='.$ptofecg_id.' and ins_estado!=\'3\'
                group by i.aper_id,p.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- TOTAL DE OPERACIONES POR FASE--------*/
    function total_operaciones_fase($pfec_id){
        $sql = 'select pfe.pfec_id,count(p.prod_id) as total
                from _proyectofaseetapacomponente pfe
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as p On p.com_id=c.com_id
                where pfe.pfec_id='.$pfec_id.' and c.estado!=\'3\' and p.estado!=\'3\'
                group by pfe.pfec_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}