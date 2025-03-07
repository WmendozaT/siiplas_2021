<?php
class Model_notificacion extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist_id = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tmes = $this->session->userData('trimestre');
        $this->dep_id = $this->session->userData('dep_id');
    }
    
    /*------- LISTA DE REQUERIMIENTOS POR MES (Unidad Responsable) --------*/
    public function list_requerimiento_mes($proy_id,$com_id,$mes_id){
        if($this->dep_id!=10){
            $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')
                where com_id='.$com_id.' and estado_cert=\'0\'';
        }
        else{
            $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')
                where com_id='.$com_id.' and estado_cert=\'0\' and (par_codigo!=31110 and par_codigo!=22600)';
        }
        

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- LISTA DE REQUERIMIENTOS POR MES (Unidad Responsable) --------*/
    public function list_requerimiento_mes_unidad($proy_id,$mes_id){
        if($this->dep_id!=10){
            $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')
                where estado_cert=\'0\'';
        }
        else{
            $sql = 'select *
                from lista_seguimiento_requerimientos_mensual_unidad('.$proy_id.','.$mes_id.','.$this->gestion.')
                where estado_cert=\'0\' and (par_codigo!=31110 and par_codigo!=22600)';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------- LISTA DE REQUERIMIENTOS POR (0 - MES FINAL) (Unidad Responsable) --------*/
    public function list_requerimiento_al_mes_unidad($proy_id,$mes_id){
        $sql = 'select *
                from lista_seguimiento_requerimientos_programado('.$mes_id.','.$this->gestion.')
                where proy_id='.$proy_id.' and estado_cert=\'0\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- LISTA DE REQUERIMIENTOS POR (0 - MES FINAL) x DISTRITAL--------*/
    public function list_requerimiento_pinversion_programado_al_mes_distrital($dist_id,$mes_id){
        $sql = 'select *
                from lista_poa_pinversion_nacional('.$this->gestion.') poa
                Inner Join lista_seguimiento_requerimientos_programado('.$mes_id.','.$this->gestion.') as ins On ins.proy_id=poa.proy_id
                where poa.dist_id='.$dist_id.' and estado_cert=\'0\'
                order by poa.dep_id, poa.dist_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- LISTA DE REQUERIMIENTOS POR MES (Unidad Responsable) PROGRAMA BOLSA--------*/
    public function list_requerimiento_mes_unidad_prog_bolsa($prod_id,$mes_id){
        $sql = 'select ip.prod_id,i.ins_id,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_unidad_medida,i.ins_observacion,i.par_id, par.par_codigo,temp.mes_id,temp.ipm_fis,temp.estado_cert
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On i.par_id=par.par_id
                Inner Join temporalidad_prog_insumo as temp On temp.ins_id=i.ins_id
                where ip.prod_id='.$prod_id.' and temp.mes_id='.$mes_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and par.par_depende!=\'10000\' and temp.estado_cert=\'0\'
                group by ip.prod_id,i.ins_id,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_unidad_medida,i.ins_observacion,i.par_id, par.par_codigo,temp.mes_id,temp.ipm_fis,temp.estado_cert
                order by par.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------- verif (Unidad Responsable) PROGRAMA BOLSA--------*/
    public function verif_requerimiento_mes_unidad_prog_bolsa($prod_id){
        $sql = 'select ip.prod_id,i.ins_id,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_unidad_medida,i.ins_observacion,i.par_id, par.par_codigo,temp.mes_id,temp.ipm_fis,temp.estado_cert
                from _insumoproducto ip
                Inner Join insumos as i On i.ins_id=ip.ins_id
                Inner Join partidas as par On i.par_id=par.par_id
                Inner Join temporalidad_prog_insumo as temp On temp.ins_id=i.ins_id
                where ip.prod_id='.$prod_id.' and i.ins_estado!=\'3\' and i.aper_id!=\'0\' and par.par_depende!=\'10000\' and temp.estado_cert=\'0\'
                group by ip.prod_id,i.ins_id,i.ins_detalle,i.ins_cant_requerida,i.ins_costo_unitario,i.ins_costo_total,i.ins_unidad_medida,i.ins_observacion,i.par_id, par.par_codigo,temp.mes_id,temp.ipm_fis,temp.estado_cert
                order by par.par_codigo asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }










    /*------- NRO DE REQUERIMIENTOS A CERTIFICAR POR DISTRITAL AL MES ACTUAL --------*/
    public function nro_requerimientos_acertificar_mensual_x_mes_distrital($dist_id,$mes_id){
        $sql = 'select *
                from seguimiento_numero_requerimientos_ppto_distrital_mensual('.$dist_id.', '.$mes_id.','.$this->gestion.') ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- NRO DE REQUERIMIENTOS A CERTIFICAR POR REGIONAL AL MES ACTUAL --------*/
    public function nro_requerimientos_acertificar_mensual_x_mes_regional($dep_id,$mes_id){
        $sql = 'select *
                from seguimiento_numero_requerimientos_ppto_regional_mensual('.$dep_id.', '.$mes_id.','.$this->gestion.') ';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---- GET MES -----*/
    function get_mes($mes_id){
        $sql = 'select *
                from mes
                where m_id='.$mes_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }




    /*------- LISTA DE USUARIOS APERTURADOS --------*/
    public function lista_usuario_a_unidades($dist_id,$tp){
        // tp: 0 Administrativo, 1 Establecimientos
        if($tp==0){
            $sql = 'select *
                from vlist_funcionario f
                Inner Join _componentes as c On c.com_id=f.cm_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                Inner Join tipo_subactividad as tpsa On tpsa.tp_sact=c.tp_sact
                Inner Join lista_poa_gastocorriente_nacional('.$this->gestion.') as proy On c.pfec_id=proy.pfec_id
                where proy.dist_id='.$dist_id.'';
        }
        else{
             $sql = '
                select proy.*,c.com_id,c.com_componente
                from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') proy
                Inner Join _componentes as c On c.pfec_id=proy.pfec_id
                where proy.tipo!=\'""\'';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------- GET VERIFICANDO SI LA UNIDAD REALIZO EL SEGUIMIENTO --------*/
    public function get_verif_registro_a_seguimiento($com_id,$mes_id){
        $sql = 'select *
                from registro_seguimientopoa seg
                Inner Join mes as m On seg.mes=m.m_id
                where com_id='.$com_id.' and seg.mes='.$mes_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
