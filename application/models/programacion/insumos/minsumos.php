<?php
class Minsumos extends CI_Model{
    var $gestion;
    var $fun_id;

    public function __construct(){
        $this->load->database();
        
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
    }

    // ------ lista insumos productos por proyecto 
/*    public function list_requerimientos_productos($proy_id){
        $sql = ' select *
                from vproy_insumo_producto_programado
                where proy_id='.$proy_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*========== LISTA DE INSUMOS ACTIVIDAD ==========*/
/*    public function insumo_actividad($proy_id){
        $sql = 'select *
                from vrelacion_proy_prod_act_ins
                where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*========== LISTA DE INSUMOS PRODUCTO ==========*/
/*    public function insumo_producto($proy_id){
        $sql = 'select *
                from vrelacion_proy_proc_prod_ins
                where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*----------- INSUMO PROGRAMADO MENSUAL --------------*/
/*    public function insumo_programado_mensual($ifin_id){
        $sql = 'select *
                from ifin_prog_mes
                where ifin_id='.$ifin_id.'
                order by mes_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*----------- GET INSUMO PROGRAMADO MENSUAL --------------*/
/*    public function get_insumo_programado_mensual($ifin_id,$mes_id){
        $sql = 'select *
                from ifin_prog_mes
                where ifin_id='.$ifin_id.' and mes_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    public function lista_productos($proy_id, $gestion){
        $this->db->SELECT('*');
        $this->db->FROM('vista_producto');
        $this->db->WHERE('proy_id', $proy_id);
        $this->db->WHERE('estado IN (1,2)');
        $this->db->ORDER_BY('com_id', 'ASC');
        //$this->db->WHERE("(cast(to_char(fecha,'yyyy')as integer))=" . $gestion);
        $query = $this->db->get();
        return $query->result_array();
    }

    //LISTA DE ACTIVIDADES FILTRADO POR PRODUCTO
    public function lista_actividades($prod_id, $gestion){
        $this->db->SELECT('*');
        $this->db->FROM('vista_actividad');
        $this->db->WHERE('prod_id', $prod_id);
        $this->db->ORDER_BY('act_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
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

    //DATOS DEL INSUMO + PROGRAMADO (wilmer)
/*    function suma_dato_insumo_programado($ins_id,$gestion){
        $sql = '
            select SUM(infp.suma) as suma
            from insumo_gestion ig
            Inner Join (select * from insumo_financiamiento) as inf On inf.insg_id=ig.insg_id
            Inner Join (select SUM(ipm_fis) as suma, ifin_id
            from ifin_prog_mes
            GROUP BY ifin_id) as infp On infp.ifin_id=inf.ifin_id
            where ig.ins_id='.$ins_id.' and ig.g_id='.$gestion.' and ig.insg_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //DATOS DEL INSUMO + PROGRAMADO DEL PROYECTO  A NIVEL PRODUCTOS
/*    function proyecto_insumoprogramado_productos($proy_id){
        $sql = '
            select *
            from vrelacion_proy_proc_prod_ins ip
            Inner Join insumo_gestion as ig On ig.ins_id=ip.ins_id
            Inner Join vifin_prog_mes as iprog On iprog.insg_id=ig.insg_id
            where ip.proy_id='.$proy_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

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

    //guardar relacion insumo actividad
/*    function add_insumo_actividad($act_id, $ins_id){
        $data = array('act_id' => $act_id, 'ins_id' => $ins_id);
        $this->db->INSERT('_insumoactividad', $data);
    }*/

    //GUARDAR PROGRAMACION MENSUAL DE INSUMO
/*    function guardar_prog_ins($post, $ins_id, $cant_fin){
        for ($i = 1; $i <= $cant_fin; $i++) {
            $monto_asignado = $post[('ins_monto' . $i)];
            if ($monto_asignado != 0) {
                $data = array(//GUARDAR EN INSUMOFINANCIAMIENTO
                    'ins_id' => $ins_id,
                    'ff_id' => $post[('ff' . $i)],
                    'of_id' => $post[('of' . $i)],
                    'et_id' => $post[('ins_et' . $i)],
                    'ifin_monto' => $monto_asignado,
                    'ifin_gestion' => $this->gestion
                );
                $this->db->INSERT('insumofinanciamiento', $data);
                $ifin_id = $this->db->insert_id();
                for ($j = 1; $j <= 12; $j++) {
                    $mes = $post[('mes' . $i . $j)];
                    if ($mes != 0) {
                        $data = array(
                            'ifin_id' => $ifin_id,
                            'mes_id' => $j,
                            'ipm_fis' => $mes
                        );
                        $this->db->INSERT('ifin_prog_mes', $data);
                    }
                }
            }

        }
    }*/

    //GENERAR CODIGO DEL INSUMO
    function genera_codigo($tipo){
        $cont = $this->get_cont($tipo);
        $cont++;
        $codigo = 'SIIP/INS/' . $this->get_tipo($tipo) . '/' . $this->gestion . '/0' . $cont;
        $data['cont'] = $cont;
        $data['codigo'] = $codigo;
        return $data;
    }

    //OBTENER CONTADOR DE ID POR EL TIPO DE INSUMO
   /* function get_cont($tipo){
        switch ($tipo) {
            case 1:
                $this->db->SELECT('conf_rrhhp');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_rrhhp;
                break;
            case 2:
                $this->db->SELECT('conf_servicios');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_servicios;
                break;
            case 3:
                $this->db->SELECT('conf_pasajes');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_pasajes;
                break;
            case 4:
                $this->db->SELECT('conf_viaticos');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_viaticos;
                break;
            case 5:
                $this->db->SELECT('conf_cons_producto');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_cons_producto;
                break;
            case 6:
                $this->db->SELECT('conf_cons_linea');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_cons_linea;
                break;
            case 7:
                $this->db->SELECT('conf_materiales');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_materiales;
                break;
            case 8:
                $this->db->SELECT('conf_activos');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_activos;
                break;
            case 9:
                $this->db->SELECT('conf_otros_insumos');
                $this->db->FROM('configuracion');
                $this->db->WHERE('ide', $this->gestion);
                $query = $this->db->get();
                return $query->row()->conf_otros_insumos;
                break;
        }
    }*/


    //LISTA DE CARGOS
    function lista_cargo(){
        $this->db->FROM('cargo');
        $this->db->WHERE('(car_estado = 1 OR car_estado = 1)');
        $query = $this->db->get();
        return $query->result_array();
    }

    //LISTA DE INSUMOS  NIVEL DE ACTIVIDADES 
/*    function lista_insumos($act_id){
        $this->db->SELECT('*');
        $this->db->FROM('vrelacion_proy_prod_act_ins');
        $this->db->WHERE('act_id', $act_id);
      //  $this->db->WHERE('ins_gestion', $this->gestion);
        $this->db->ORDER_BY('ins_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }*/

    
/*    function relacion_com_ins($com_id){
        $sql = 'select *
                from insumocomponente
                where com_id='.$com_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    function relacion_prod_ins($prod_id){
        $sql = 'select *
                from _insumoproducto
                where prod_id='.$prod_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function relacion_act_ins($act_id){
        $sql = 'select *
                from _insumoactividad
                where act_id='.$act_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function relacion_ins_act($ins_id){
        $sql = 'select *
                from _insumoactividad
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    
    //LISTA DE INSUMOS A NIVEL DE PRODUCTOS (Wilmer 2019)
    function lista_insumos_prod($prod_id){
        if($this->gestion==2019){
            $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par_codigo,i.ins_id asc';
        }
        else{
            $sql = 'select *
                from _insumoproducto ip
                Inner Join _productos as prod On prod.prod_id=ip.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //LISTA DE INSUMOS A NIVEL DE ACTIVIDADES (Wilmer 2019)
/*    function lista_insumos_act($act_id){
        if($this->gestion!=2020){  //// 2019
            $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id

                where ia.act_id='.$act_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                order by par_codigo,i.ins_id asc';
        }
        else{ //// 2020
            $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where ia.act_id='.$act_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\'
                order by par_codigo,i.ins_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //LISTA DE INSUMOS A NIVEL DE COMPONENTES (DELEGADO) (Wilmer 2019)
/*    function lista_insumos_com($com_id){
        $sql = 'select *
                from insumocomponente ic
                Inner Join insumos as i On i.ins_id=ic.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where ic.com_id='.$com_id.' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par_codigo asc,i.ins_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //SUMA  DEL COSTO TOTAL DE INSUMOS
/*    function get_suma_costo_total($act_id){
        $this->db->FROM("fnsuma_costo_total_ins(".$act_id.")");
        $query = $this->db->get();
        return $query->row();
    }*/

    //SALDO TOTAL DE FINANCIAMIENTO
/*    function saldo_total_fin($proy_id,$gestion){
        $sql = 'SELECT * FROM fnsaldo_total_fin(' . $proy_id . ',' . $gestion . ')';
        $query = $this->db->query($sql);
        return $query->row();
    }*/

    //LISTA DE INSUMOS FILTRADO POR TIPO DE INSUMO (ACTIVIDADES)
/*    function lista_insumos_tipo($act_id,$tipo){
        $this->db->SELECT('*');
        $this->db->FROM('vproy_insumo_directo_programado');
        $this->db->WHERE('act_id', $act_id);
        $this->db->WHERE('ins_tipo', $tipo);
        $this->db->WHERE('g_id', $this->gestion);
        $this->db->ORDER_BY('ins_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }*/

    // RELACION ACTIVIDAD INSUMOS PROGRAMADOS COMPLETOS Y POR GESTION
/*    function relacion_act_insumo_programado($act_id){
        $sql = '  select *
                 from _insumoactividad ia
                 Inner Join insumos as i On i.ins_id=ia.ins_id
                 Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                 where ia.act_id='.$act_id.' and ig.g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //LISTA DE INSUMOS FILTRADO POR TIPO DE INSUMO (PRODUCTO)
/*    function lista_insumos_tipo_p($prod_id,$tipo){
        $this->db->SELECT('*');
        $this->db->FROM('vproy_insumo_producto_programado');
        $this->db->WHERE('prod_id', $prod_id);
        $this->db->WHERE('ins_tipo', $tipo);
        $this->db->WHERE('g_id', $this->gestion);
        $this->db->ORDER_BY('ins_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }*/

    //LISTA PROGRAMADO MENSUAL DEL INSUMO FINANCIAMIENTO
/*    function lista_progmensual_ins($ins_id){
        $this->db->SELECT('*');
        $this->db->FROM('v_ins_financiamiento_programado');
        $this->db->WHERE('ins_id', $ins_id);
       // $this->db->WHERE('ifin_gestion', $this->gestion);
        $this->db->ORDER_BY('ins_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }*/

    //DATOS DEL INSUMO
/*    function get_insumo($ins_id){
        $this->db->SELECT('*');
        $this->db->FROM('vlista_insumos');
        $this->db->WHERE('ins_id',$ins_id);
        $query = $this->db->get();
        return $query->row();
    }*/

    //DATOS DEL INSUMO (wilmer)
/*    function get_dato_insumo($ins_id){
        if($this->gestion==2018){
            $sql = '
            select *
                from insumos i
                Inner Join (select g.par_id as gid, g.par_codigo as cod1, g.par_nombre as grupo, p.par_id as pid, p.par_codigo as cod2, p.par_nombre as partida
                from partidas p
                Inner Join partidas as g On g.par_codigo=p.par_depende) as pr On pr.pid=i.par_id
                Inner Join cargo as c On c.car_id=i.car_id
                Inner Join tipo_insumo as ti On ti.ti_id=i.ins_tipo
                where ins_id='.$ins_id.' and ins_estado!=\'3\'';

        }
        else{
            $sql = '
            select *
                from insumos i
                Inner Join (select g.par_id as gid, g.par_codigo as cod1, g.par_nombre as grupo, p.par_id as pid, p.par_codigo as cod2, p.par_nombre as partida
                from partidas p
                Inner Join partidas as g On g.par_codigo=p.par_depende) as pr On pr.pid=i.par_id
                Inner Join cargo as c On c.car_id=i.car_id
                where i.ins_id='.$ins_id.' and i.ins_estado!=\'3\'';

        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //DATOS DEL INSUMO GESTION (wilmer)
/*    function get_dato_insumo_gestion($insg_id,$ins_id){
        $sql = 'select *
                from insumo_gestion
                where insg_id='.$insg_id.' and ins_id='.$ins_id.' and insg_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //DATOS DEL INSUMO GESTION ACTUAL (wilmer)
/*    function get_dato_insumo_gestion_actual($ins_id){
        $sql = 'select *
                from insumo_gestion
                where ins_id='.$ins_id.' and g_id='.$this->gestion.' and insg_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //LISTA DE INSUMOS GESTION (wilmer)
/*    function list_insumos_gestion($ins_id){
        $sql = 'select *
                from insumo_gestion
                where ins_id='.$ins_id.' and insg_estado!=\'3\' order by g_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //GET DE INSUMOS GESTION (wilmer)
/*    function get_insumo_gestion($ins_id,$gestion){
        $sql = 'select *
                from insumo_gestion
                where ins_id='.$ins_id.' and insg_estado!=\'3\' and g_id='.$gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*========== LISTA DE INSUMOS FINANCIAMIENTOS (Wilmer) ==========*/
/*    public function list_insumo_financiamiento($insg_id){
        $sql = 'select *
                from insumo_financiamiento 
                where insg_id='.$insg_id.' and ifin_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*========== GET INSUMO FINANCIAMIENTO PROGRAMADO (Wilmer) Para el registro de programacion ==========*/
/*    public function get_insumo_financiamiento($insg_id,$ffofet_id,$gestion,$nro){
        $sql = 'select *
                from vifin_prog_mes 
                where insg_id='.$insg_id.' and ffofet_id='.$ffofet_id.' and ifin_gestion='.$gestion.' and nro_if='.$nro.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
*/
    /*========== GET INSUMO FINANCIAMIENTO PROGRAMADO (Wilmer) Para el registro de programacion ==========*/

    /*==== TEMPORALIDAD FISICA - INSUMO =====*/
/*    public function get_list_insumo_financiamiento($insg_id){
        $sql = 'select *
                from vifin_prog_mes 
                where insg_id='.$insg_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

        /*==== TEMPORALIDAD FISICA (AUXILIAR 2019) =====*/
/*    public function get_temporalidad_2019($ins_id){
        $sql = 'select *
                from insumo_gestion ig
                Inner Join vifin_prog_mes as ipr On ipr.insg_id=ig.insg_id
                where ig.ins_id='.$ins_id.' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    //OBTENER LA PROGRAMACION MENSUAL DEL INSUMO (a borrar)
/*    public function ins_prog_mensual($ins_id,$ff_id,$of_id){
        $this->db->SELECT('*');
        $this->db->FROM('v_ins_financiamiento_programado');
        $this->db->WHERE('ins_id',$ins_id);
        $this->db->WHERE('ff_id',$ff_id);
        $this->db->WHERE('of_id',$of_id);
        $query = $this->db->get();
        return $query;
    }*/

    //LIMPIAR INSUMO FINANCIAMIENTO
/*    function limpiar_ins_financiamiento($ins_id){
        $lista_ins_fin = $this->get_ins_fin($ins_id);//LISTA DE INSUMOS DE FINANCIAMIENTOS
        foreach($lista_ins_fin AS $row){
            $this->del_ifin_prog_mes($row['ifin_id']);
        }
        $this->del_ins_fin($ins_id);
    }*/

    //ELIMINAR LA TABLA DE PROGRAMACION MENSUAL DE INSUMOS
/*    function del_ifin_prog_mes($ifin_id){
        $this->db->WHERE('ifin_id', $ifin_id);
        $this->db->DELETE('ifin_prog_mes');
    }
*/
    //OBTENER INSUMO FINANCIAMIENTO
/*    function get_ins_fin($ins_id){
        $this->db->SELECT('*');
        $this->db->FROM('insumofinanciamiento');
        $this->db->WHERE('ins_id',$ins_id);
        $query = $this->db->get();
        return $query->result_array();
    }*/

    /*================= PARA REPORTES INSUMOS (DIRECTOS) PRODUCTOS/ACTIVIDADES ==============*/

    /// ------ Lista de Partidas 
/*    public function list_partidas(){
        $sql = 'select *
                from partidas
                where par_depende=\'0\' and par_id!=\'0\'
                order by par_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*======== REPORTE PAC- LISTA DE REQUERIMIENTOS POR OPERACIONES (2019) ============*/
/*    public function proyecto_insumo_programado($proy_id,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
               $sql = 'select *
                        from vproy_insumo_actividad_programado i
                        Inner Join _productos as p On p.prod_id=i.prod_id
                        where i.proy_id='.$proy_id.' and i.g_id='.$this->gestion.'
                        order by i.com_id, p.prod_id, i.par_codigo,i.ins_id asc';
              
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select *
                    from vproy_insumo_componente_programado
                    where proy_id='.$proy_id.' and g_id='.$this->gestion.'';
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select *
                    from vproy_insumo_producto_programado i 
                    Inner Join _productos as p On p.prod_id=i.prod_id
                    where proy_id='.$proy_id.' and g_id='.$this->gestion.' 
                    order by i.com_id, p.prod_id,i.par_codigo,i.ins_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    ///// ------ Lista Presupuesto Por Partidas Componentes
/*    public function proyecto_partida_programado($proy_id,$com_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select *
                        from vpresupuesto_partidas_act
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'
                        order by par_codigo asc';
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select *
                        from vpresupuesto_partidas_com
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'
                        order by par_codigo asc';
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select *
                        from vpresupuesto_partidas_prod
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'
                        order by par_codigo asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    ///// ------ Suma Total Presupuesto Por Partidas Componentes
/*    public function suma_proyecto_partida_programado($proy_id,$com_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_act
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'';
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_com
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'';
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_prod
                        where proy_id='.$proy_id.' and com_id='.$com_id.' and ins_gestion='.$gestion.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    ///// ------ total por partidas 
/*    public function proyecto_insumo_programado_total($proy_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select proy_id,par_id,par_codigo,par_nombre,SUM(programado_total) as total
                    from vproy_insumo_actividad_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id,par_id,par_codigo,par_nombre
                    order by par_codigo';
              
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select proy_id,par_id,par_codigo,par_nombre,SUM(programado_total) as total
                    from vproy_insumo_componente_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id,par_id,par_codigo,par_nombre
                    order by par_codigo';
                
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select proy_id,par_id,par_codigo,par_nombre,SUM(programado_total) as total
                    from vproy_insumo_producto_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id,par_id,par_codigo,par_nombre
                    order by par_codigo';

        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

        ///// ------ total por partidas 
/*    public function proyecto_partida_total($proy_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select proy_id,SUM(programado_total) as total
                    from vproy_insumo_actividad_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id';
              
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select proy_id,SUM(programado_total) as total
                    from vproy_insumo_componente_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id';
                
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select proy_id,SUM(programado_total) as total
                    from vproy_insumo_producto_programado
                    where proy_id='.$proy_id.' and g_id='.$gestion.'
                    group by proy_id';

        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

  /*--------------------------------- REPORTE DE INSUMO POR PROCESOS -------------------------------*/
/*    public function proyecto_insumo_programado_procesos($gestion,$com_id,$tipo,$act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
               $sql = 'select *
                    from vproy_insumo_actividad_programado
                    where g_id='.$gestion.' and com_id='.$com_id.'
                    order by par_codigo asc';
              
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select *
                    from vproy_insumo_componente_programado
                    where g_id='.$gestion.' and com_id='.$com_id.'
                    order by par_codigo asc';
                
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            
            $sql = 'select *
                    from vproy_insumo_producto_programado
                    where g_id='.$gestion.' and com_id='.$com_id.'
                    order by par_codigo asc';

        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    ///// ------ Lista Presupuesto Por Partidas Componentes
/*    public function proyecto_partida_programado_procesos($com_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select *
                        from vpresupuesto_partidas_act
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select *
                        from vpresupuesto_partidas_com
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select *
                        from vpresupuesto_partidas_prod
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    ///// ------ Suma Total Presupuesto Por Partidas Componentes
/*    public function suma_proyecto_partida_programado_procesos($com_id,$gestion,$tipo, $act){
        if($act==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_act
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_com
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $sql = 'select SUM(total) as total
                        from vpresupuesto_partidas_prod
                        where com_id='.$com_id.' and ins_gestion='.$gestion.'';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

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

    /*------------ CONSOLIDADO PARTIDAS DE LA OPERACION (Actividad)------------*/
/*    function consolidado_partidas_directo($act_id){
        if($this->gestion!=2020){
            $sql = 'select ia.act_id, par.par_codigo, par.par_nombre,SUM(i.ins_costo_total) as total
            from _insumoactividad ia
            Inner Join insumos as i On i.ins_id=ia.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
            where ia.act_id='.$act_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
            group by ia.act_id, par.par_codigo,par.par_nombre';
        }
        else{
            $sql = 'select ia.act_id, par.par_codigo, par.par_nombre,SUM(i.ins_costo_total) as total
            from _insumoactividad ia
            Inner Join insumos as i On i.ins_id=ia.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            where ia.act_id='.$act_id.' and i.ins_estado!=\'3\'
            group by ia.act_id, par.par_codigo,par.par_nombre';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------ CONSOLIDADO PARTIDAS DE LA OPERACION (Delegado)------------*/
/*    function consolidado_partidas_delegado($com_id){
        $sql = 'select ic.com_id, par.par_codigo, par.par_nombre,SUM(i.ins_costo_total) as total
            from insumocomponente ic
            Inner Join insumos as i On i.ins_id=ic.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
            where ic.com_id='.$com_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
            group by ic.com_id, par.par_codigo,par.par_nombre';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------ TOTAL MONTO OPERACION------------*/
    /*function monto_total_operacion($prod_id){
        $sql = 'select ip.prod_id, SUM(i.ins_costo_total) as total
            from _insumoproducto ip
            Inner Join insumos as i On i.ins_id=ip.ins_id
            Inner Join partidas as par On par.par_id=i.par_id
            Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
            where ip.prod_id='.$prod_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
            group by ip.prod_id';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- GET REQUERIMIENTO PRODUCTOS --------*/
/*    function get_requerimiento($ins_id){
        if($this->gestion==2019){
            $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join insumo_financiamiento as if On if.insg_id=ig.insg_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\' and i.aper_id!=\'0\'';
        }
        else{
            $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and i.aper_id!=\'0\'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- GET REQUERIMIENTO ACTIVIDAD --------*/
/*    function get_requerimiento_actividad($ins_id){
        if($this->gestion!=2020){
            $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join insumo_financiamiento as if On if.insg_id=ig.insg_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\' and i.aper_id!=\'0\'';
        }
        else{
            $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and i.aper_id!=\'0\'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- GET REQUERIMIENTO COMPONENTES (Delegado) --------*/
/*    function get_requerimiento_delegado($ins_id){
        $sql = 'select *
                from insumocomponente ic
                Inner Join insumos as i On i.ins_id=ic.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join insumo_financiamiento as if On if.insg_id=ig.insg_id
                where i.ins_id='.$ins_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\' and i.aper_id!=\'0\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- SUMA MONTO PROGRAMADO POR UNIDAD, ESTABLECIMIENTO, PROYECTO DE INVERSION -----*/
/*    function monto_programado_actividad($aper_id){
        $sql = 'select i.aper_id, p.proy_id,SUM(i.ins_costo_total) total
                from insumos i
                Inner Join aperturaproyectos as ap On ap.aper_id=i.aper_id 
                Inner Join _proyectos as p On ap.proy_id=p.proy_id
                where i.aper_id='.$aper_id.' and ins_estado!=\'3\' and i.aper_id!=\'0\' 
                group by i.aper_id,p.proy_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- GET ID INSUMO PRODUCTO -----*/
    function get_insumo_producto($ins_id){
        $sql = 'select *
                from insumos
                where ins_id='.$ins_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- SUMA TOTAL MONTO PROGRAMADO POR ACTIVIDAD (PROYECTO) -----*/
/*    function monto_total_programado($aper_id,$gestion){
        $sql = 'select i.aper_id,SUM(ip.programado_total) as monto
                from vlista_insumos i
                Inner Join insumo_gestion as ig on ig.ins_id = i.ins_id
                Inner Join vifin_prog_mes as ip on ip.insg_id = ig.insg_id
                where i.aper_id='.$aper_id.' and ig.g_id='.$gestion.' and i.aper_id!=\'0\' 
                group by i.aper_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*---- LISTA REQUERIMIENTOS - EJECUCION DELEGADA-----*/
/*    function list_requerimientos_delegado($com_id){
        $sql = 'select *
                from _componentes c
                Inner Join insumocomponente as ic On ic.com_id=c.com_id
                Inner Join insumos as i On i.ins_id=ic.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and c.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                order by par.par_codigo,i.ins_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA CONSOLIDADO POR PARTIDAS - EJECUCION DELEGADA-----*/
/*    function list_consolidado_partidas_delegado($com_id){
        $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join insumocomponente as ic On ic.com_id=c.com_id
                Inner Join insumos as i On i.ins_id=ic.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and c.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\'
                group by c.com_id, c.pfec_id, i.ins_id,par.par_id,   par.par_codigo,par.par_nombre
                order by par.par_codigo asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*---- LISTA REQUERIMIENTOS - PRODUCTOS POR SUB ACTIVIDADES (COMPONENTES)-----*/
/*    function list_requerimientos_operacion_procesos($com_id){
        if($this->gestion==2019){
            $sql = 'select *
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\' 
                order by p.prod_cod,par.par_codigo,i.ins_id asc';
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
    }*/

    /*---- LISTA CONSOLIDADO DE PRODUCTOS PARTIDAS POR SUB ACTIVIDADES (COMPONENTES)-----*/
/*    function list_consolidado_partidas_componentes($com_id){
        if($this->gestion==2019){
            $sql = 'select c.com_id, c.pfec_id,par.par_id, par.par_codigo,par.par_nombre, SUM(i.ins_costo_total) as monto
                from _componentes c
                Inner Join _productos as p On c.com_id=p.com_id
                Inner Join _insumoproducto as ip On ip.prod_id=p.prod_id
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                where c.com_id='.$com_id.' and p.estado!=\'3\' and i.ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and ig.insg_estado!=\'3\' and i.aper_id!=\'0\' 
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
    }*/

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


    /*---- LISTA CONSOLIDADO DE REQUERIMIENTOS POR UNIDAD (2019) -----*/
   /* function list_consolidado_requerimientos($tp){
        if($tp==1){
            $sql = '
            select 
                dep.dep_departamento,
                dist.dist_distrital,

                apg.aper_programa,
                apg.aper_proyecto,
                apg.aper_actividad,
                
                p.proy_nombre,
                p.dep_id,
                p.dist_id,
                p.tp_id,
                tp.tp_tipo,

                c.com_componente,
                prod.prod_producto,
                prod.acc_id,
                
                i.ins_id,
                i.ins_detalle,
                i.ins_unidad_medida,
                i.ins_cant_requerida,
                i.ins_costo_unitario,
                i.ins_costo_total,
                i.ins_observacion,
                par.par_codigo,
                par.par_nombre,
                
                i.ins_estado,
                apg.aper_gestion,
                i.aper_id,
                apg.aper_id,
                ig.insg_id,

                ipr.*
                
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id

                Inner Join vifin_prog_mes as ipr on ipr.insg_id = ig.insg_id
                
                Inner Join _insumoactividad as ia On ia.ins_id=i.ins_id
                Inner Join _actividades as act On act.act_id=ia.act_id
                
                Inner Join _productos as prod On prod.prod_id=act.prod_id
                Inner Join _componentes as c On prod.com_id=c.com_id
                
                Inner Join partidas as par On par.par_id=i.par_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\' and act.estado!=\'3\' and prod.estado!=\'3\' and c.estado!=\'3\'
                order by dep.dep_id, p.proy_id, apg.aper_programa, apg.aper_proyecto,par.par_codigo, i.ins_id asc';

        }
        else{
             $sql = '
                select 
                dep.dep_departamento,
                dist.dist_distrital,

                apg.aper_programa,
                apg.aper_proyecto,
                apg.aper_actividad,
                
                p.proy_nombre,
                p.dep_id,
                p.dist_id,
                p.tp_id,
                tp.tp_tipo,

                c.com_componente,
                prod.prod_producto,
                prod.acc_id,
                
                i.ins_id,
                i.ins_detalle,
                i.ins_unidad_medida,
                i.ins_cant_requerida,
                i.ins_costo_unitario,
                i.ins_costo_total,
                i.ins_observacion,
                par.par_codigo,
                par.par_nombre,
                
                i.ins_estado,
                apg.aper_gestion,
                i.aper_id,
                apg.aper_id,
                ig.insg_id,

                ipr.*
                
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join insumos as i On i.aper_id=ap.aper_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id

                Inner Join vifin_prog_mes as ipr on ipr.insg_id = ig.insg_id
                
                Inner Join _insumoproducto as ip On ip.ins_id=i.ins_id
                Inner Join _productos as prod On prod.prod_id=ip.prod_id
                Inner Join _componentes as c On prod.com_id=c.com_id
                
                Inner Join partidas as par On par.par_id=i.par_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and i.ins_estado!=\'3\' and dep_estado!=\'0\' and prod.estado!=\'3\' and c.estado!=\'3\'
                order by dep.dep_id, p.proy_id, apg.aper_programa, apg.aper_proyecto,par.par_codigo, i.ins_id asc';
        }
       
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- LISTA DE UNIDADES - CONSOLIDADO POR PARTIDAS ASIGNADAS-----*/
   /* function list_consolidado_partidas($tp_id){
                $sql = 'select 
                p.dep_id,
                p.dist_id,
                dep.dep_departamento,
                dist.dist_distrital,

                apg.aper_programa,
                apg.aper_proyecto,
                apg.aper_actividad,

                p.proy_id,                
                p.proy_nombre,

                apg.aper_id,

                pg.par_id,
                pg.partida,

                SUM(pg.importe) as monto 
                from _proyectos p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id

                Inner Join ptto_partidas_sigep as pg On pg.aper_id=apg.aper_id
                Inner Join partidas as par On par.par_id=pg.par_id 

                where p.estado!=\'3\' and p.tp_id='.$tp_id.' and apg.aper_estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pg.estado!=\'3\' and pg.g_id='.$this->gestion.'

                group by p.dep_id,p.dist_id,dep.dep_departamento,dist.dist_distrital,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_id,p.proy_nombre,apg.aper_id,pg.par_id,pg.partida,pg.importe
                order by p.dep_id,p.dist_id,apg.aper_programa, apg.aper_proyecto, apg.aper_actividad,p.proy_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*---- LISTA DE OPERACIONES/PRODUCTOS -----*/
   /* function list_consolidado_operaciones($tp_id,$dep_id){

        if($tp_id==1){
            $sql = '
                select 
                    dep.dep_departamento,
                    dist.dist_distrital,
                    apg.aper_programa,
                    apg.aper_proyecto,
                    apg.aper_actividad,
                    
                    p.proy_id,
                    p.proy_nombre,
                    p.tp_id,

                    c.com_componente,
                    pr.prod_id,
                    pr.prod_cod,
                    pr.prod_producto,
                    pr.prod_indicador,
                    pr.prod_fuente_verificacion,
                    pr.prod_meta,
                    i.indi_descripcion,
                    mr.mt_tipo,
                    pr.acc_id,

                    prog.*,

                    ae.acc_codigo,
                    ae.acc_descripcion,

                    oe.obj_codigo,
                    oe.obj_descripcion,

                    a.act_id

                from _proyectos p
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join indicador as i On i.indi_id=pr.indi_id
                Inner Join meta_relativo as mr On mr.mt_id=pr.mt_id

                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id

                Inner Join _actividades as a On a.prod_id=pr.prod_id
                
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and p.tp_id='.$tp_id.' and a.estado!=\'3\' and p.dep_id='.$dep_id.' and prog.g_id='.$this->gestion.'
                order by dep.dep_id,dist.dist_id, p.proy_id,apg.aper_programa, apg.aper_proyecto,apg.aper_actividad asc';
        }
        else{
            $sql = '
                select 
                    dep.dep_departamento,
                    dist.dist_distrital,
                    apg.aper_programa,
                    apg.aper_proyecto,
                    apg.aper_actividad,
                    
                    p.proy_id,
                    p.proy_nombre,
                    p.tp_id,

                    c.com_componente,
                    pr.prod_id,
                    pr.prod_cod,
                    pr.prod_producto,
                    pr.prod_indicador,
                    pr.prod_fuente_verificacion,
                    pr.prod_meta,
                    i.indi_descripcion,
                    mr.mt_tipo,
                    pr.acc_id,

                    prog.*,

                    ae.acc_codigo,
                    ae.acc_descripcion,

                    oe.obj_codigo,
                    oe.obj_descripcion

                from _proyectos p
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as pr On pr.com_id=c.com_id
                Inner Join indicador as i On i.indi_id=pr.indi_id
                Inner Join meta_relativo as mr On mr.mt_id=pr.mt_id

                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=pr.prod_id
                
                Inner Join _acciones_estrategicas as ae On ae.ae=pr.acc_id
                Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and pr.estado!=\'3\' and p.tp_id='.$tp_id.' and p.dep_id='.$dep_id.' and prog.g_id='.$this->gestion.'
                order by dep.dep_id,dist.dist_id,p.proy_id,apg.aper_programa, apg.aper_proyecto,apg.aper_actividad asc';
        }
                
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- REQUERIMIENTOS PRODUCTOS-----*/
/*    function requerimientos_productos($prod_id){
        $sql = 'select *
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vtemp_fin_mes as ipr on ipr.insg_id = ig.insg_id
                where ip.prod_id='.$prod_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
                order by ip.prod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---- REQUERIMIENTOS ACTIVIDAD-----*/
/*    function requerimientos_actividades($act_id){
        $sql = 'select *
                from _insumoactividad ia
                Inner Join insumos as i On i.ins_id=ia.ins_id
                Inner Join partidas as par On par.par_id=i.par_id
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join vtemp_fin_mes as ipr on ipr.insg_id = ig.insg_id
                where ia.act_id='.$act_id.' and ins_estado!=\'3\' and ig.g_id='.$this->gestion.' and insg_estado!=\'3\'
                order by ia.act_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/


    /*---- GET INSUMO FINANCIAMIENTO -----*/
/*    function get_insumo_fin($ins_id){
        $sql = 'select *
                from insumos i
                Inner Join insumo_gestion as ig On ig.ins_id=i.ins_id
                Inner Join insumo_financiamiento as ifin On ifin.insg_id=ig.insg_id
                where i.ins_id='.$ins_id.' and ig.g_id='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

}
?>