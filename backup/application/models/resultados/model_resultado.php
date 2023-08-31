<?php
class Model_resultado extends CI_Model{
    public function __construct(){
        $this->load->database();
    }
   
   /*============================ LISTA DE RESPONSABLES ================================*/
    public function responsables(){
        $sql = 'select *
            from funcionario
            where fun_estado!=\'3\' order by fun_id  asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------- CONFIGURACION  ------------------------*/
    public function configuracion(){
        $sql = 'select *
            from configuracion
            where ide='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------- CONFIGURACION SESSION ------------------------*/
    public function configuracion_session(){
        $sql = 'select *
            from configuracion
            where ide='.$this->session->userdata("gestion").''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------- GET RESULTADO ID ------------------------*/
    public function get_resultado($r_id){
        $sql = 'select *
                from resultado_mediano_plazo
                where r_id='.$r_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
   /*--------------------------- LISTA RESULTADOS ------------------------*/
    public function list_resultados($gestion_inicio,$gestion_final){
        $sql = 'select rmp.*,f.fun_nombre,f.fun_paterno,f.fun_materno, ur.uni_unidad
                from resultado_mediano_plazo rmp
                Inner Join funcionario as f On f.fun_id=rmp.resp_id
                Inner Join unidadorganizacional as ur On ur.uni_id=rmp.uni_id
                where rmp.gestion_desde='.$gestion_inicio.' and rmp.gestion_hasta='.$gestion_final.' and rmp.r_estado!=\'3\' order by r_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------------- LISTA RESULTADOS ------------------------*/
    public function list_indicadores($r_id){
        $sql = 'select *
                from indi_resultados ir
                Inner Join indicador as i On ir.indi_id=i.indi_id
                where ir.r_id='.$r_id.' and ir.in_estado!=\'3\' order by ir.in_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------------- GET INDICADOR ------------------------*/
    public function get_indicador($in_id){
        $sql = 'select *
                from indi_resultados ir
                Inner Join indicador as i On ir.indi_id=i.indi_id
                where ir.in_id='.$in_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------- RESULTADO PROGRAMADO ---------------------------*/
    public function resultado_programado($in_id){
         $sql = 'select *
                 from resultados_prog
                 where in_id='.$in_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------- BORRAR PROGRAMADO RESULTADO---------------------------*/
    public function delete_prog_res($in_id){ 
        $this->db->where('in_id', $in_id);
        $this->db->delete('resultados_prog'); 
    }
    
    public function get_dato_configuracion($gestion){
        $sql = "Select *
        from configuracion
        where ide = $gestion";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


   /*=================================== RESULTADOS DE CORTO PLAZO ==================================*/
    public function list_resultados_cp($r_id){
        $sql = 'select *
                from resultado_corto_plazo rcp
                Inner Join funcionario as f On f.fun_id=rcp.resp_id
                Inner Join unidadorganizacional as ur On ur.uni_id=rcp.uni_id
                where rcp.r_id='.$r_id.' and rcp.r_estado!=\'3\' order by rc_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--------------------------- GET RESULTADO CORTO PLAZO ID ------------------------*/
    public function get_resultado_cp($rc_id){
        $sql = 'select *
                from resultado_corto_plazo rcp
                Inner Join funcionario as f On f.fun_id=rcp.resp_id
                Inner Join unidadorganizacional as ur On ur.uni_id=rcp.uni_id
                where rc_id='.$rc_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------------- LISTA RESULTADOS ------------------------*/
    public function list_indicadores_cp($rc_id){
        $sql = 'select *
                from indi_resultados_cp ir
                Inner Join indicador as i On ir.indi_id=i.indi_id
                where ir.rc_id='.$rc_id.' and ir.in_estado!=\'3\' order by ir.incp_id asc'; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------------- GET INDICADOR ------------------------*/
    public function get_indicador_cp($incp_id){
        $sql = 'select *
                from indi_resultados_cp ir
                Inner Join indicador as i On ir.indi_id=i.indi_id
                where ir.incp_id='.$incp_id.''; 
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------- RESULTADO PROGRAMADO CORTO PLAZO ---------------------------*/
    public function resultado_programado_cp($incp_id){
         $sql = 'select *
                 from resultados_prog_cp
                 where incp_id='.$incp_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*--------------------- BORRAR PROGRAMADO RESULTADO CORTO PLAZO---------------------------*/
    public function delete_prog_res_cp($incp_id){ 
        $this->db->where('incp_id', $incp_id);
        $this->db->delete('resultados_prog_cp'); 
    }
    
}
