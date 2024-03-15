<?php
class Model_configuracion extends CI_Model {
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }

    /*--------- Lista de responsables para evaluacion ----------*/
    public function get_list_responsables_evaluacion(){
        $sql = 'select * from vlist_funcionario
                where cm_id=\'0\''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- Lista de unidades responsables habilitados para evaluacion ----------*/
    public function get_list_uresponsables_evaluacion(){
        $sql = 'select * 
                from vlist_funcionario vf
                Inner Join _componentes as c On c.com_id=vf.cm_id
                Inner Join tipo_subactividad as tpa On tpa.tp_sact=c.tp_sact
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                where vf.cm_id!=\'0\' and apg.aper_gestion='.$this->gestion.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------- get responsable Seleccionado ----------*/
    public function get_responsables_evaluacion($fun_id){
        $sql = 'select *
                from resp_evaluacion
                where fun_id='.$fun_id.' and ide='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------- get datos de fecha de Evaluacion POA ----------*/
    public function get_datos_fecha_evaluacion($gestion){
        $sql = 'select extract(day from (eval_inicio))as dia_inicio, extract(month from (eval_inicio))as mes_inicio, extract(day from (eval_fin))as dia_final, extract(month from (eval_fin))as mes_final
                from configuracion
                where ide='.$gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_configuracion(){ /// gestion activado por bd
         $sql = 'select * 
         from configuracion c
         Inner Join mes as m On m.m_id=c.conf_mes
         where c.conf_estado=\'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_configuracion_session(){ /// gestion activado por session
         $sql = 'select * 
         from configuracion c
         Inner Join mes as m On m.m_id=c.conf_mes
         where c.ide='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_mes(){
         $sql = 'select * from mes';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function list_mes_trimestre($trimestre){
         $sql = '
         select * 
         from mes
         where trm_id='.$trimestre.'
         order by m_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_mes_trimestre(){
         $sql = 'select * 
                from trimestre_mes
                where estado!=\'0\'
                order by trm_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_gestion(){
         $sql = 'select *
                from gestion
                where g_id!=\'0\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function verifica_gestion($ide){
        $query = "SELECT count(*) n
        FROM gestion
        WHERE g_id = $ide";
        $query = $this->db->query($query);
        $query = $query->row();
        $bool = ($query->n >= 1) ? true : false ;
        return $bool;
    }

    public function activa_funcionario($ide){
        if(!$this->verifica_gestion($ide)){
            $this->db->insert('gestion', array(
                'g_id' => $ide,
                'g_descripcion' => $ide
            ));
        }
        $update = array(
            'estado' => 1
        );
        $this->db->where('ide', $ide);
        return $this->db->update('configuracion', $update);
    }

    public function desactiva_funcionario($ide){
        $update = array(
            'estado' => 0
        );
        $this->db->where('ide', $ide);
        return $this->db->update('configuracion', $update);
    }

    public function lista_gestion(){
        $sql = 'SELECT *FROM configuracion where estado = 1 order by ide';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_gestion_todo(){
        $sql = 'SELECT * from configuracion order by ide desc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function act_ges($ide){
        $q = "SELECT * FROM configuracion WHERE conf_estado = 1";
        $q = $this->db->query($q);
        $q = $q->row();
        $mes = $q->conf_mes;
        $sql = 'UPDATE public.configuracion
                SET conf_estado = 0';
        $query = $this->db->query($sql);
        $update = array(
            'conf_estado' => 1,
            'conf_mes' => $mes
        );
        $this->db->where('ide', $ide);
        $this->db->update('configuracion', $update);
        // $sql = 'UPDATE public.configuracion SET conf_estado = 1, conf_mes = $mes WHERE ide='.$ide.'';
        // $query = $this->db->query($sql);
        redirect('Configuracion');
    }

    public function act_ges_mes($conf_mes){
        // $sql = 'UPDATE public.configuracion SET conf_mes=0';
        // $query = $this->db->query($sql);
        $sql = 'UPDATE public.configuracion
               SET conf_mes='.$conf_mes.'
               WHERE conf_estado = 1';
        $query = $this->db->query($sql);
        redirect('Configuracion');
    }

    public function gestion_actual(){
       $sql = 'SELECT ide, conf_mes from configuracion
                where conf_estado=1';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*------- Lista de Modulos -------*/
    public function list_modulos(){
       $sql = ' select *
                from modulo
                where mod_estado!=\'0\'
                order by mod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*------- Configuración Modulos Habilitados -------*/
    public function modulos($ide){
       $sql = ' select *
                from confi_modulo
                where ide='.$ide.'';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*---- Get Modulo ---*/
    public function get_modulos($mod_id){
       $sql = ' select *
                from modulo
                where mod_id='.$mod_id.'';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*------- Verif Modulo -------*/
    public function verif_modulo($mod_id){
       $sql = ' select *
                from confi_modulo
                where ide='.$this->gestion.' and mod_id='.$mod_id.'';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*--- Verif Nro Modulo por gestion ---*/
    public function verif_nro_modulo(){
       $sql = ' select *
                from confi_modulo
                where ide='.$this->gestion.'
                order by mod_id';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*--- Elimina opciones seleccionadas ---*/
    public function delete_modulos(){ 
        $this->db->where('ide', $this->gestion);
        $this->db->delete('confi_modulo'); 
    }

    /*-- CONFIGURACION DE REGIONALES PARA LA CERTIFICACION --*/
    public function regionales_distritales(){
       $sql = ' select *
                from _distritales
                where dist_id!=\'0\' and dist_estado!=\'0\'
                order by dep_id,dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*------- Verif Certificacion -------*/
    public function verif_cert($dist_id){
       $sql = ' select *
                from confi_cert
                where ide='.$this->gestion.' and dist_id='.$dist_id.'';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*--- Verif regionaes habilitados por gestion ---*/
    public function verif_region_cert(){
       $sql = ' select *
                from confi_cert
                where ide='.$this->gestion.'
                order by conf_id';
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }

    /*--- Elimina opciones seleccionadas ---*/
    public function delete_opciones_cert(){ 
        $this->db->where('ide', $this->gestion);
        $this->db->delete('confi_cert'); 
    }
}
?>  
