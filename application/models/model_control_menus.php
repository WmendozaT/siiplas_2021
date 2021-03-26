<?php

class Model_control_menus extends CI_Model{
    var $gestion;
    var $fun_id;

    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
    }

    public function lista_productos($proy_id, $gestion){
        $this->db->SELECT('*');
        $this->db->FROM('vista_producto');
        $this->db->WHERE('proy_id', $proy_id);
        $this->db->ORDER_BY('prod_id', 'ASC');
        //$this->db->WHERE("(cast(to_char(fecha,'yyyy')as integer))=" . $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function menu_segun_roles($fun_id){
        $query = " SELECT o.o_filtro
        FROM opciones o inner join (SELECT distinct(o.o_id) o_id
        FROM (Select distinct(fun_id), r_id,r_estado
        from fun_rol
        where fun_id = $fun_id and r_estado!=3) a left join opcion_rol o
        ON a.r_id = o.r_id
        order by o.o_id) tmp
        ON tmp.o_id = o.o_id and o.o_parent = 0 and (o.o_activo = 1 or o.o_activo = 2)
        GROUP BY o.o_filtro
        ORDER BY o.o_filtro";
        $query = $this->db->query($query);
        return $query->result_array();
    }


}
?>
