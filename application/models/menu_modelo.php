<?php
class Menu_modelo extends CI_Model {
	public function __construct(){
        $this->load->database();
    }

	public function get_Modulos($dato_menu){
		$fun_id = $this->session->userdata("fun_id");
		$query = "SELECT b.*
		FROM (select opr.o_id, count(*)
		from (select r_id
		from (select fun_id, uni_id, car_id, fun_cargo
			from funcionario
			where fun_id = $fun_id and fun_estado = 1) f left join fun_rol fr
		on f.fun_id = fr.fun_id where fr.r_estado!=3) tmp1 left join opcion_rol opr
		on tmp1.r_id = opr.r_id
		group by opr.o_id
		order by opr.o_id) a inner join (select o.*
		from opciones o
		where (o.o_activo = 1 and o.o_parent = 0 and o_filtro = $dato_menu) or (o.o_activo = 1 and o.o_parent = 0 and o_filtro = 30)) b
		ON a.o_id = b.o_id
		ORDER BY b.o_child";
		$query = $this->db->query($query);
		return $query->result_array();
	}

	public function get_Enlaces($sub_menus){
		$fun_id = $this->session->userdata('fun_id');
		$query = "SELECT b.*
		FROM (select opr.o_id, count(*)
		from (select r_id
		from (select fun_id, uni_id, car_id, fun_cargo
			from funcionario
			where fun_id = $fun_id and fun_estado = 1) f left join fun_rol fr
		on f.fun_id = fr.fun_id) tmp1 left join opcion_rol opr
		on tmp1.r_id = opr.r_id
		group by opr.o_id
		order by opr.o_id) a inner join (select o.*
		from opciones o
		where (o.o_activo = 1 and o.o_parent = $sub_menus)) b
		ON a.o_id = b.o_id
		ORDER BY b.o_child";
		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	function get_Modulos_h($dato_menu){
		$this->db->from ('funcionario f');
		$this->db->join('fun_rol fr','fr.fun_id=f.fun_id','right');
		$this->db->join('rol r','r.r_id=fr.r_id');
		$this->db->join('opcion_rol opr','opr.r_id=r.r_id');
		$this->db->join('opciones o', 'o.o_id=opr.o_id');
		$this->db->where('f.fun_id',$this->session->userdata("fun_id"));//funcionario
		$this->db->where('o.o_activo',1);
		$this->db->where('o.o_parent',0);
		$this->db->where("(o.o_filtro = ".$dato_menu." OR o.o_filtro = 30)");
		$this->db->order_by('o.o_child', 'ASC');
		$query=$this->db->get();
		return $query->result_array();
	}

	function get_Enlaces_h($sub_menus){
		$this->db->from ('funcionario f');
		$this->db->join('fun_rol fr','fr.fun_id=f.fun_id','right');
		$this->db->join('rol r','r.r_id=fr.r_id');
		$this->db->join('opcion_rol opr','opr.r_id=r.r_id');
		$this->db->join('opciones o', 'o.o_id=opr.o_id');
		$this->db->where('f.fun_id',$this->session->userdata("fun_id"));//funcionario
		$this->db->where('o.o_activo',1);
		$this->db->where('o.o_parent',$sub_menus);
		$this->db->order_by('o.o_child', 'ASC');
		$query=$this->db->get();
		return $query->result_array();
	}

	/*------------------------- nuevos modulos coponentes -wilmer- 22-09-2016*/
	function get_Modulos_componentes($dato_menu){
		//$this->db->where('username', $this->input->post('inputUsuario'));
		$this->db->where('idparent','0');
		$this->db->where('idmenu','50');
		$this->db->where('activo','1');
		$this->db->where("(filtro = ".$dato_menu." OR filtro = 50)");
		$this->db->order_by('idchild','ASC');
		$query = $this->db->get('opciones');
		return $query->result_array();	
	}

	function padres($filtro,$r_id){
		$sql = 'SELECT DISTINCT(COALESCE(opr.o_id,0)) nuevo,o.o_titulo,o.o_id,o.o_child 
		FROM opciones o LEFT JOIN opcion_rol opr 
		ON opr.o_id = o.o_id AND opr.r_id = '.$r_id.'
		WHERE o_filtro = '.$filtro.' AND o_parent = 0 and m_id=30';
        $query = $this->db->query($sql);
        return $query->result_array();
	}

	function hijos($o_child,$r_id){
		$sql = 'SELECT DISTINCT(COALESCE(opr.o_id,0)) nuevo1,o.o_titulo,o.o_id,o.o_child
		FROM opciones o LEFT JOIN opcion_rol opr ON opr.o_id=o.o_id and opr.r_id='.$r_id.'
		where  o_parent='.$o_child.' and m_id=30';
        $query = $this->db->query($sql);
        return $query->result_array();
	}

	function roles_list($r_id){
		$this->db->select('r_nombre,r_id');
		$this->db->from('rol');
		$this->db->where('r_id',$r_id);
		$query=$this->db->get();
		return $query->result_array();
		
	}

	function get_Modulos_programacion($dato_menu){
		$this->db->where('o_parent','0');
		$this->db->where('m_id','50');
		$this->db->where('o_activo','1');
		$this->db->where('o_filtro',$dato_menu);
		$this->db->order_by('o_child','ASC');
		$query = $this->db->get('opciones');
		return $query->result_array();
	}

	function get_Modulos_sub($o_child){
		$this->db->from ('opciones');
		$this->db->where('o_parent',$o_child);
		$this->db->where('o_activo','1');
		$this->db->order_by('o_id', 'ASC');
		$query=$this->db->get();
		return $query->result_array();
	}	
}