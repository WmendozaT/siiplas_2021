<?php

class Mapertura_programatica extends CI_Model
{
    var $gestion;
    public function __construct()
    {
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
    }

    //=======================================  APERTURA PROGRAMATICA  =================================================
    //LISTA DE APERTURAS PADRES
    function lista_aperturas_padres()
    {
        $this->db->SELECT('*');
        $this->db->FROM('aperturaprogramatica a');
        $this->db->JOIN('unidadorganizacional u', 'a.uni_id = u.uni_id', 'LEFT');
        $this->db->WHERE('(a.aper_estado = 1 OR  a.aper_estado = 2)');
        $this->db->WHERE('a.aper_proyecto','0000');
        $this->db->WHERE('a.aper_actividad','000');
        $this->db->WHERE('a.aper_gestion',$this->gestion);
        $this->db->ORDER_BY ('a.aper_gestion,a.aper_programa,a.aper_proyecto,a.aper_actividad','ASC');
        $query = $this->db->get();
        return $query->result_array();

        /*$sql = "SELECT * FROM aperturaprogramatica a, unidadorganizacional u
       WHERE a.uni_id = u.uni_id AND a.aper_estado = 1
       AND a.aper_proyecto = '0000'
       AND a.aper_actividad = '000'
       ORDER BY a.aper_gestion, a.aper_programa,a.aper_proyecto,a.aper_actividad ASC ";
        $query = $this->db->query($sql);
        return $query->result_array();*/
    }

    //verificar si el codigo de apertura existe
    function verificar_aper($cod, $gestion){
        $prog='0000'; $act='000';
        $this->db->WHERE('aper_programa', $cod);
        $this->db->WHERE('aper_proyecto', $prog);
        $this->db->WHERE('aper_actividad', $act);
        $this->db->WHERE('aper_gestion', $gestion);
        $this->db->WHERE("(aper_estado = 1 OR aper_estado = 2)");
        $this->db->FROM('aperturaprogramatica');
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    //guardar apertura
    function guardar_apertura($programa, $descripcion, $gestion, $unidad_o){
        $data = array(
            'aper_gestion' => $gestion,
            'aper_entidad' => '0',
            'aper_programa' => $programa,
            'aper_proyecto' => '0000',
            'aper_actividad' => '000',
            'aper_asignado' => 1,
            'uni_id' => $unidad_o,
            'fun_id' => $this->session->userData('id_usuario'),
            'aper_descripcion' => strtoupper(trim($descripcion))
        );
        return $this->db->insert('aperturaprogramatica', $data);
    }

    //obtener dato de la apertura programatica
    function dato_apertura($id){
        $this->db->SELECT('*');
        $this->db->FROM('aperturaprogramatica');
        $this->db->WHERE('aper_id',$id);
        $query = $this->db->get();
        return $query->result_array();

    }

    function modificar_apertura($id,$descripcion,$unidad){
        $data = array(
            'uni_id' => $unidad,
            'fun_id' => $this->session->userData('id_usuario'),
            'aper_estado' => 2,
            'aper_descripcion' => strtoupper(trim($descripcion))
        );
        $this->db->where('aper_id', $id);
        return $this->db->UPDATE('aperturaprogramatica', $data);
    }

    function eliminar_apertura($aper_id,$estado){
        $sql = 'UPDATE aperturaprogramatica SET aper_estado = '.$estado.',fecha_eliminacion= (SELECT current_timestamp) WHERE aper_id = '.$aper_id;
        return $this->db->query($sql);
    }

    //LISTA DE APERTURAS PROGRAMATICAS
    function lista_aperturas(){
        $sql = 'SELECT * FROM aperturaprogramatica a LEFT JOIN unidadorganizacional U
          ON a.uni_id = U.uni_id WHERE (a.aper_estado = 1 OR a.aper_estado = 2) AND a.aper_gestion ='.$this->gestion;
        /*$this->db->SELECT('*');
        $this->db->FROM('aperturaprogramatica a, unidadorganizacional u');
        $this->db->WHERE('a.uni_id = u.uni_id');
        //$this->db->WHERE('aper_estado',1);
        $this->db->where("(a.aper_estado = 1 OR a.aper_estado = 2)");
        $this->db->ORDER_BY('a.aper_gestion,a.aper_programa,a.aper_proyecto,a.aper_actividad ',' ASC ');
        $query = $this->db->get();*/
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    // APERTURAS PROGRAMATICAS PADRES NO ASIGNADOS A LA CARPETA POA
    function get_aper_noasignados(){
        $this->db->SELECT('*');
        $this->db->FROM('aperturaprogramatica');
        $this->db->WHERE('(aper_estado = 1 OR  aper_estado = 2)');
        $this->db->WHERE('aper_asignado',0);
        $this->db->WHERE('aper_proyecto','0000');
        $this->db->WHERE('aper_actividad','000');
        $this->db->WHERE('aper_gestion',$this->gestion);
        $this->db->ORDER_BY ('aper_programa,aper_proyecto,aper_actividad','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    //lista de aperturas programaticas hijas
    function lista_aper_hijas($programa,$gestion){
        $this->db->SELECT('*');
        $this->db->FROM('aperturaprogramatica a');
        $this->db->join('unidadorganizacional u', 'a.uni_id = u.uni_id', 'LEFT');
        $this->db->WHERE('(a.aper_estado = 1 OR  a.aper_estado = 2)');
        $this->db->WHERE('a.aper_gestion',$gestion);
        $this->db->WHERE('a.aper_programa',$programa);
        $this->db->WHERE("(a.aper_proyecto <> '0000' OR a.aper_actividad <> '000')");
        $this->db->ORDER_BY ('a.aper_programa,a.aper_proyecto,a.aper_actividad','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }


}