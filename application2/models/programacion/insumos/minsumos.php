<?php
class Minsumos extends CI_Model{
    var $gestion;
    var $fun_id;

    public function __construct(){
        $this->load->database();
        
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
    }


    /*------- Lista de componente-producto-actividad*/
    public function lista_com_prod_actividades($com_id, $gestion){
        $sql = 'select *
                from vista_producto vp
                Inner Join vista_actividad as va On va.prod_id=vp.prod_id
                where vp.com_id='.$com_id.'
                order by act_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function dato_proyecto($proy_id){
        $this->db->SELECT('*');
        $this->db->FROM('_proyectos');
        $this->db->WHERE('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->row();
    }

    //OBTENER PRESUPUESTO DEL PROYECTO
    public function tabla_presupuesto($proy_id, $gestion){
        $sql = 'SELECT * FROM fnfinanciamiento_proy(' . $proy_id . ',' . $gestion . ')';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function relacion_ins_ope($ins_id){
        $sql = 'select *
                from _insumoproducto
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    //DATO DE PRODUCTOS
    public function dato_producto($prod_id){
        $this->db->SELECT('*');
        $this->db->FROM('vista_producto');
        $this->db->WHERE('prod_id', $prod_id);
        $query = $this->db->get();
        return $query->row();
    }

    //DATO DE ACTIVIDADES
    public function dato_actividad($act_id){
        $this->db->SELECT('*');
        $this->db->FROM('vista_actividad');
        $this->db->WHERE('act_id', $act_id);
        $query = $this->db->get();
        return $query->row();
    }

    //GET PARTIDAS
    function get_partida($par_id){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_id', $par_id);
        // $this->db->WHERE('par_gestion',$this->gestion);
        $this->db->ORDER_BY('par_codigo', 'ASC');
        $query = $this->db->get();
        return $query->row();
    }


    //GUARDAR INSUMO
    function guardar_insumo($data_insumo, $post, $act_id, $cant_fin){
        $this->db->trans_begin();
        $ins_id = $this->guardar_tabla_insumo($data_insumo);// GUARDAR EN MI TABLA INSUMO
        $this->guardar_prog_ins($post, $ins_id, $cant_fin);//guardar la programacion mensual de insumo
        $this->add_insumo_actividad($act_id, $ins_id);//guardar relacion en la tabla insumos actividad
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            return $this->db->trans_commit();
        }
    }

    function  guardar_tabla_insumo($data_insumo){
        $data = $this->genera_codigo(($data_insumo['ins_tipo']));
        $data_insumo['ins_codigo'] = $data['codigo'];//guardar codigo
        $data_insumo['ins_gestion'] = $this->gestion;
        $data_insumo['fun_id'] = $this->fun_id;
        $this->db->insert('insumos', $data_insumo);
        $ins_id = $this->db->insert_id();
        $this->actualizar_conf_ins($data['cont'],($data_insumo['ins_tipo'])); //actualizar mi contador de codigo de insumos
        return $ins_id;
    }


    //GENERAR CODIGO DEL INSUMO
    function genera_codigo($tipo){
        $cont = $this->get_cont($tipo);
        $cont++;
        $codigo = 'SIIP/INS/' . $this->get_tipo($tipo) . '/' . $this->gestion . '/0' . $cont;
        $data['cont'] = $cont;
        $data['codigo'] = $codigo;
        return $data;
    }


    //LISTA DE CARGOS
    function lista_cargo(){
        $this->db->FROM('cargo');
        $this->db->WHERE('(car_estado = 1 OR car_estado = 1)');
        $query = $this->db->get();
        return $query->result_array();
    }


    /*------------ CONSOLIDADO PARTIDAS DE LA OPERACION (Productos)------------*/
    function consolidado_partidas_operacion($prod_id){
        if($this->gestion!=2020){
            $sql = 'select ip.prod_id, par.par_codigo, par.par_nombre,SUM(i.ins_costo_total) as total
            from _insumoproducto ip
            Inner Join insumos as i On i.ins_id=ip.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
            where ip.prod_id='.$prod_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
            group by ip.prod_id, par.par_codigo,par.par_nombre';
        }
        else{
            $sql = 'select ip.prod_id, par.par_codigo, par.par_nombre,SUM(i.ins_costo_total) as total
            from _insumoproducto ip
            Inner Join insumos as i On i.ins_id=ip.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\'
            group by ip.prod_id, par.par_codigo,par.par_nombre';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---- LISTA REQUERIMIENTOS - ACTIVIDADES POR SUB ACTIVIDADES (COMPONENTES)-----*/
    function list_requerimientos_actividades_procesos($com_id){
        if($this->gestion!=2020){
            $sql = 'select *
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _actividades as a On p.prod_id=a.prod_id
                Inner Join _insumoactividad as ia On ia.act_id=a.act_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and a.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';
        }
        else{
            $sql = 'select *
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _actividades as a On p.prod_id=a.prod_id
                Inner Join _insumoactividad as ia On ia.act_id=a.act_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and a.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'  and i.ins_gestion='.$this->gestion.'
                order by par.par_codigo,i.ins_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA CONSOLIDADO DE ACTIVIDAD PARTIDAS POR SUB ACTIVIDADES (COMPONENTES)-----*/
    function list_consolidado_partidas_act_componentes($com_id){
        if($this->gestion!=2020){
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _actividades as a On p.prod_id=a.prod_id
                Inner Join _insumoactividad as ia On ia.act_id=a.act_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and a.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                group by c.com_id, c.pfec_id, par.par_id, par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }
        else{
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _actividades as a On p.prod_id=a.prod_id
                Inner Join _insumoactividad as ia On ia.act_id=a.act_id
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'  and i.ins_gestion='.$this->gestion.'
                group by c.com_id, c.pfec_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA REQUERIMIENTOS (AUXILIAR)-----*/
    function list_requerimientos_auxiliar($com_id){
        $sql = 'select *
                from aux_requerimiento
                where com_id='.$com_id.'
                order by rep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
?>