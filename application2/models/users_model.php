<?php

class users_model extends CI_Model {
 
    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	public function __construct(){
        $this->load->database();
    }

	function validate($user_name, $password){
		$this->db->where('fun_usuario', $user_name);
		$this->db->where('fun_password', $password);
		$query = $this->db->get('funcionario');
		$query = $query->row();
		$fun_id = $query->fun_id;
		return $fun_id;
	}
	// FUNCION PARA CAPTURAR LA GESTIOIN DEL SISTEMA
	function obtener_gestion(){
		$this->db->where('conf_estado',1);
		$query = $this->db->get('configuracion');
		return $query->result_array();
	}
	/**
    * datos_usuario the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */

	function datos_usuario($user_name, $password){
		$this->db->select(" f.fun_id,
                            f.uni_id,
                            u.uni_unidad,
                            f.car_id,
                            f.fun_nombre,
                            f.fun_paterno,
                            f.fun_usuario,
                            fb.b_id,
                            r.r_id,
                           ");
        $this->db->from('funcionario f');
        $this->db->join('unidadorganizacional u', 'u.uni_id = f.uni_id', 'left');
        $this->db->join ('fun_btn fb','fb.fun_id=f.fun_id');
        $this->db->join ('fun_rol fr','fr.fun_id=f.fun_id');
        $this->db->join ('rol r','r.r_id=fr.r_id');
		$this->db->where('fun_usuario', $user_name);
        $this->db->where('fun_password', $password);
        $query = $this->db->get();
        return $query->result_array();
	}

	public function get_datos_usuario($fun_id){
        $sql = 'select tmp.fun_id,tmp.uni_id,tmp.car_id,tmp.fun_nombre,tmp.fun_ci,tmp.cm_id,tmp.fun_domicilio,tmp.fun_telefono,tmp.fun_estado,
		tmp.fun_usuario,tmp.fun_password,tmp.fun_estado,tmp.fun_paterno,tmp.fun_materno,tmp.fun_cargo,tmp.fecha_creacion,
		tmp.fun_adm,tmp.fun_dist,tmp.uni_unidad,MIN(tmp.r_id) as rol_id,ds.dist_id,ds.dep_id,ds.dist_distrital,ds.dist_estado,ds.dist_tp,tmp.tp_adm,tmp.r_estado
		from (select f.*, r.r_id,r.r_estado
		from fun_rol r right join (select f.*, u.uni_unidad
		from (select *
		from funcionario 
		where fun_estado = \'1\' or fun_estado = \'2\') f left join unidadorganizacional u
		on f.uni_id = u.uni_id) f
		on r.fun_id = f.fun_id
		order by f.fun_id) tmp
		Inner Join _distritales as ds On ds.dist_id=tmp.fun_dist
		where tmp.fun_id = '.$fun_id.'
		GROUP BY tmp.fun_id,tmp.uni_id,tmp.car_id,tmp.fun_nombre,tmp.fun_ci,tmp.cm_id,tmp.fun_domicilio,tmp.fun_telefono,tmp.fun_estado,
		tmp.fun_usuario,tmp.fun_password,tmp.fun_estado,tmp.fun_paterno,tmp.fun_materno,tmp.fun_cargo,tmp.fecha_creacion,
		tmp.fun_adm,tmp.fun_dist,tmp.uni_unidad,ds.dist_id,ds.dep_id,ds.dist_distrital,ds.dist_estado,ds.dist_tp,tmp.tp_adm,tmp.r_estado';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function get_datos_usuario2($fun_id){
        $sql = 'select tmp.fun_id,tmp.uni_id,tmp.car_id,tmp.fun_nombre,tmp.fun_ci,tmp.fun_domicilio,tmp.fun_telefono,
		tmp.fun_usuario,tmp.fun_password,tmp.fun_estado,tmp.fun_paterno,tmp.fun_materno,tmp.fun_cargo,tmp.fecha_creacion,
		tmp.fun_adm,tmp.fun_dist,tmp.uni_unidad,MIN(tmp.r_id) as rol_id,ds.dist_id,ds.dep_id,ds.dist_distrital,ds.dist_estado,ds.dist_tp,tmp.tp_adm
		from (select f.*, r.r_id
		from fun_rol r right join (select f.*, u.uni_unidad
		from (select *
		from funcionario 
		where fun_estado = \'1\' or fun_estado = \'2\') f left join unidadorganizacional u
		on f.uni_id = u.uni_id) f
		on r.fun_id = f.fun_id
		order by f.fun_id) tmp
		Inner Join _distritales as ds On ds.dist_id=tmp.fun_dist
		where tmp.fun_id = '.$fun_id.'
		GROUP BY tmp.fun_id,tmp.uni_id,tmp.car_id,tmp.fun_nombre,tmp.fun_ci,tmp.fun_domicilio,tmp.fun_telefono,
		tmp.fun_usuario,tmp.fun_password,tmp.fun_estado,tmp.fun_paterno,tmp.fun_materno,tmp.fun_cargo,tmp.fecha_creacion,
		tmp.fun_adm,tmp.fun_dist,tmp.uni_unidad,ds.dist_id,ds.dep_id,ds.dist_distrital,ds.dist_estado,ds.dist_tp,tmp.tp_adm';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data(){
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    /**
    * Store the new user's data into the database
    * @return boolean - check the insert
    */

    function get_datos_usuario_roles($fun_id,$rol){
		$sql = 'select *
				from (select f.*, r.r_id
				from fun_rol r right join (select f.*, u.uni_unidad
				from (select *
				from funcionario 
				where fun_estado = 1 or fun_estado = 2) f left join unidadorganizacional u
				on f.uni_id = u.uni_id) f
				on r.fun_id = f.fun_id
				order by f.fun_id) tmp
				where tmp.fun_id = '.$fun_id.' and (tmp.r_id='.$rol.' or tmp.r_id=\'1\')';
        $query = $this->db->query($sql);
        return $query->result_array();
	}

	function create_member(){

		$this->db->where('user_name', $this->input->post('username'));
		$query = $this->db->get('membership');

        if($query->num_rows > 0){
        	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">�</a><strong>';
			  echo "Username already taken";	
			echo '</strong></div>';
		}else{
			$new_member_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_addres' => $this->input->post('email_address'),			
				'user_name' => $this->input->post('username'),
				'pass_word' => md5($this->input->post('password'))						
			);
			$insert = $this->db->insert('membership', $new_member_insert_data);
		    return $insert;
		}
	      
	}//create_member
	
	function get_entidad($gestion){
		$query = "SELECT *
		FROM configuracion
		WHERE ide = $gestion AND conf_estado = 1";
		$query = $this->db->query($query);
		$query = $query->row();
		return $query;
	}

	public function verificar_menu($url){
		$fun_id = $this->session->userdata('fun_id');
		$query = "SELECT *  
		FROM (SELECT * FROM opciones o right join (Select * from opcion_rol where r_id not in (Select r_id from fun_rol where fun_id = $fun_id)) tmp ON o.o_id = tmp.o_id) a where a.o_url like '$url' ";
		$query = $this->db->query($query);
		$verificar = (count($query) > 0) ? true : false ;
		return $verificar;
	}

	public function verificar_menu_1($url){
		$fun_id = $this->session->userdata('fun_id');
		$query = "SELECT * 
		FROM (SELECT *
		FROM opciones o right join (Select *
		from opcion_rol
		where r_id in (Select r_id from fun_rol where fun_id = $fun_id)) tmp
		ON o.o_id = tmp.o_id) a
		where a.o_url like '$url'";
		$query = $this->db->query($query);
		$query = $query->result_array();
		$verificar = ( count($query) > 0 ) ? true : false ;
		return $verificar;
	}
}

