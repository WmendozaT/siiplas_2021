<?php
class Model_insumo extends CI_Model{
    var $gestion;
    var $fun_id;

    public function __construct(){
        $this->load->database();
        
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
    }

    // ------ lista Programacion Financiera
    public function lista_prog_fin($ins_id){
        $sql = 'select *
                from temporalidad_prog_insumo
                where ins_id='.$ins_id.'
                order by mes_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ lista Programacion Insumos Certificados (Nuevo)
    public function lista_prog_fin_certificado($ins_id){
        $sql = 'select ins_id, SUM(ipm_fis) monto_certificado
                from temporalidad_prog_insumo
                where ins_id='.$ins_id.' and estado_cert=\'1\'
                group by ins_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_partida_codigo($par_codigo){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_codigo', $par_codigo);
        $query = $this->db->get();
        return $query->result_array();
    }

    //LISTA PARTIDAS DEPENDIENTES
    function lista_par_dependientes($par_codigo){
        $this->db->SELECT('*');
        $this->db->FROM('partidas');
        $this->db->WHERE('par_depende', $par_codigo);
        //$this->db->WHERE('par_gestion',$this->gestion);
        $this->db->ORDER_BY('par_codigo', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    // ------ Get temporalidad
/*    public function get_temporalidad_prog($tins_id,$ins_id){
        $sql = 'select *
                from temporalidad_prog_insumo
                where tins_id='.$tins_id.' and ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    // ------ lista Temporalidad Insumo
    public function list_temporalidad_insumo($ins_id){
        $sql = 'select *
            from vista_temporalidad_insumo
            where ins_id='.$ins_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ lista Unidad de Medida
    public function list_unidadmedida(){
        $sql = 'select *
                from insumo_unidadmedida
                where um_estado!=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ get Unidad de Medida
    public function get_unidadmedida($um_id){
        $sql = 'select *
                from insumo_unidadmedida
                where um_id='.$um_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Verifica Unidad de medida con respecto a partidas
    public function verif_partida_umedida($par_id,$um_id){
        $sql = 'select *
                from par_umedida
                where par_id='.$par_id.' and um_id='.$um_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Lista de Unidades de medida seleccionado
    public function lista_umedida($par_id){
        $sql = 'select *
                from par_umedida pum
                Inner Join insumo_unidadmedida as ium on ium.um_id = pum.um_id
                where pum.par_id='.$par_id.'
                order by ium.um_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // ------ Lista insumos por Unidad, Establecimiento, Proyecto de Inversion
    public function insumos_por_unidad($aper_id){
        $sql = 'select *
                from insumos
                where aper_id='.$aper_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // lista de requerimientos alineados a la operacion y a la subactividad
    function list_requerimientos_operacion_procesos($com_id){
        if($this->gestion>2021){
            $sql = 'select 
                c.com_id,
                c.pfec_id,
                i.form4_cod as prod_cod,
                i.ins_id,
                i.ins_codigo,
                i.ins_cant_requerida,
                i.ins_costo_unitario,
                i.ins_costo_total,
                i.ins_detalle,
                i.ins_unidad_medida,
                i.ins_gestion,
                i.ins_observacion,
                i.ins_monto_certificado,
                par.par_id,
                par.par_codigo,
                par.par_nombre
                from _componentes c
                Inner Join insumos as i On c.com_id=i.com_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                order by i.form4_cod,par.par_codigo,i.ins_id asc';
        }
        else{
            $sql = 'select *
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                order by p.prod_cod,par.par_codigo,i.ins_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- LISTA CONSOLIDADO DE PRODUCTOS PARTIDAS POR SUB ACTIVIDADES (COMPONENTES)-----*/
    function list_consolidado_partidas_componentes($com_id){
        if($this->gestion>2021){ /// 2022
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join insumos as i On i.com_id=c.com_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                group by c.com_id, c.pfec_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }
        else{
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and i.ins_gestion='.$this->gestion.'
                group by c.com_id, c.pfec_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET REQUERIMIENTO -----*/
    function get_requerimiento($ins_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and i.aper_id!=\'0\'';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function lista_insumos_prod($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join _productos as prod On prod.prod_id=ip.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////insumos con estado 3 (eliminados)
    function lista_insumos_prod_eliminados($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join _productos as prod On prod.prod_id=ip.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.'
                order by par.par_codigo,i.ins_id asc';

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_insumo_producto($ins_id){
        $sql = 'select *
                from insumos
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


/*    public function lista_productos($proy_id, $gestion){
        $this->db->SELECT('*');
        $this->db->FROM('vista_producto');
        $this->db->WHERE('proy_id', $proy_id);
        $this->db->WHERE('estado IN (1,2)');
        $this->db->ORDER_BY('com_id', 'ASC');
        //$this->db->WHERE("(cast(to_char(fecha,'yyyy')as integer))=" . $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }*/
}
?>