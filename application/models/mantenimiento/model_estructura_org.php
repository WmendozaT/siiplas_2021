<?php
class model_estructura_org extends CI_Model {
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id'); /// rol->1 administrador, rol->3 TUE, rol->4 POA
        $this->adm = $this->session->userData('adm'); /// adm->1 Nacional, adm->2 Regional
        $this->dist = $this->session->userData('dist'); /// dist-> id de la distrital
        $this->dist_tp = $this->session->userData('dist_tp'); /// dist_tp->1 Regional, dist_tp->0 Distritales
        $this->tp_adm = $this->session->userdata("tp_adm");
    }

    public function password_decod($pass){
        $this->load->library('encrypt');
        $password = $this->encrypt->decode($pass);
        return $password;
    }

    /*------ Verif Dato de Ingreso --------*/
    public function verif_establecimiento_ingreso($dat_ingreso,$password,$gestion){
      //  $var = $this->password_decod($password);
        $data = array(
            'bool' => false,
            'act_id' => null  
        );

        $sql = '
            select *
            from unidad_actividad ua
            Inner Join uni_gestion as ug On ug.act_id=ua.act_id
            where ua.dato_ingreso = \''.$dat_ingreso.'\' and ua.act_estado!=\'3\' and ug.g_id='.$gestion.'';

        $query = $this->db->query($sql);

        $datos = $query->result_array();
        
        if(count($datos)){
            $pass= $datos[0]['clave'];
            //$pass= $this->password_decod($datos[0]['clave']);

            if($password==$pass){
                $data['bool'] = true;
                $data['act_id'] = $datos[0]['act_id'];
            }
        }
        
        return $data;
    }


    /*------ lista de imagenes --------*/
    public function list_img($uni_id){
        $sql = '
                select *
                from unidad_galeria
                where act_id='.$uni_id.' and img_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------ lista de Compra de Servicio Regional,Nacional--------*/
    public function list_compra_servicio($dep_id){
        if($dep_id!=0){
            $sql = '
                select *
                from unidad_actividad ua
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where ua.te_id=\'21\' and ug.g_id='.$this->gestion.' and dist.dep_id='.$dep_id.'
                order by ua.act_id asc';
        }
        else{
            $sql = '
                select *
                from unidad_actividad ua
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where ua.te_id=\'21\' and ug.g_id='.$this->gestion.'
                order by dep.dep_id,ua.act_id asc';
        }
        

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ lista de Actividades --------*/
    public function list_actividades($dist_id){
        $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where ua.dist_id='.$dist_id.' and ua.act_estado!=\'3\'
                order by act_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Tipo de Administracion -------------*/
    public function tp_administracion(){
        $sql = 'select *
                from tipo_adm
                order by ta_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Verif- unidad activa en la gestion -------------*/
    public function verif_uni_gestion($act_id){
        $sql = 'select *
                from uni_gestion
                where act_id='.$act_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ get lista de Unidades/Servicios consolidado -------------*/
    public function get_unidades_regionales_consolidado(){
        $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where ua.act_estado!=\'3\' and tu.estado!=\'0\' and te.estado!=\'0\' and te.ta_id=\'2\' and ug.g_id='.$this->gestion.' 
                order by dep.dep_id,dist.dist_id, te.te_id,ua.act_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ get lista de Unidades/Servicios por regional -------------*/
    public function get_unidades_regionales($dep_id){
        if($this->tp_adm==1){
            $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where dep.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and tu.estado!=\'0\' and te.estado!=\'0\' and eu.estado!=\'0\' and ug.g_id='.$this->gestion.' and te.ta_id=\'2\' and ua.te_id!=\'21\'
                order by dist.dist_id,te.te_id,ua.act_id asc';
        }
        else{
            $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join v_tp_establecimiento as te On ua.te_id=te.te_id
                Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where dep.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and tu.estado!=\'0\' and te.estado!=\'0\' and eu.estado!=\'0\' and ug.g_id='.$this->gestion.' and te.ta_id=\'2\' and ua.te_id!=\'21\'
                order by dist.dist_id,te.te_id,ua.act_id asc';
        }
        

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Relacion Establecimiento - Apertura Programatica (Padre) -------*/
    public function relacion_establecimiento_apertura($te_id){
        $sql = 'select *
                from aper_establecimiento
                where te_id='.$te_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Get Relacion Establecimiento - Apertura Programatica (Padre) -------*/
    public function get_relacion_establecimiento_apertura($te_id,$aper_id){
        $sql = 'select *
                from aper_establecimiento
                where te_id='.$te_id.' and aper_id='.$aper_id.' and g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ lista de Actividades -------------*/
    public function list_unidades($tp){
        if($tp==1){
            $sql = 'select *
                    from _distritales dist
                    Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                    where dist.dist_adm=\'1\' and dist.dist_estado!=\'3\'
                    order by dist.dist_id asc';
        }
        else{
            $sql = 'select *
                    from _distritales dist
                    Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                    where dist.dist_ue=\'1\' and dist.dist_estado!=\'3\'
                    order by dist.dist_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ lista de Actividades -------------*/
    public function list_unidades_adm_ue($tp,$dep_id){
        if($tp==1){
            $sql = 'select *
                    from _distritales dist
                    Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                    where dist.dist_adm=\'1\' and dist.dist_estado!=\'3\' and dist.dep_id='.$dep_id.'
                    order by dist.dist_id asc';
        }
        else{
            $sql = 'select *
                    from _distritales dist
                    Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                    where dist.dist_ue=\'1\' and dist.dist_estado!=\'3\' and dist.dep_id='.$dep_id.'
                    order by dist.dist_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Get Apertura Actividad Gestion -------------*/
    public function get_aper_act_gestion($aper_id,$cod){
        $sql = 'select *
                from actividad_apertura_gestion aag
                Inner Join aperturaprogramatica as apg On apg.aper_id=aag.aper_id
                Inner Join unidad_actividad as act On act.act_id=aag.act_id
                Inner Join _proyectos as p On p.proy_id=aag.proy_id
                where apg.aper_id='.$aper_id.' and p.proy_codigo=\''.$cod.'\' and aag.g_id='.$this->gestion.' and act.act_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Get Datos de Unidad Organizacional ---*/
    public function datos_unidad_organizacional($act_id,$gestion){
        $sql = '
            select *
            from unidad_actividad ua
            Inner Join _distritales as dist On dist.dist_id=ua.dist_id
            Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
            Inner Join uni_gestion as ug On ug.act_id=ua.act_id
            Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
            Inner Join tipo_establecimiento as te On ua.te_id=te.te_id
            Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
            where ua.act_id='.$act_id.' and ua.act_estado!=\'3\' and ug.g_id='.$gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Get UO - Actividad, Alineado a Programa ---*/
    public function get_actividad($act_id){
        $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join _departamentos as dep On dep.dep_id=dist.dep_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join aper_establecimiento as ae On ae.te_id=ua.te_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ae.aper_id
                Inner Join tipo_ubicacion as tu On ua.tu_id=tu.tu_id
                Inner Join tipo_establecimiento as te On ua.te_id=te.te_id
                Inner Join estado_unidad as eu On ua.eu_id=eu.eu_id
                where ua.act_id='.$act_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and ae.g_id='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ List Servicios - Actividad -------------*/
    public function list_servicios(){
        $sql = 'select *
                from servicios_actividad
                where activo!=\'0\'
                order by serv_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ List Servicios - Sub Actividad -------------*/
    public function list_tp_servicios($tp){
        $sql = 'select *
                from servicios_actividad
                where serv_estado!=\'3\' and serv_tp='.$tp.'
                order by serv_cod asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Get Servicio - Sub Actividad por codigo -------------*/
    public function get_servicio_actividad_cod($serv_cod){
        $sql = 'select *
                from servicios_actividad
                where serv_cod=\''.$serv_cod.'\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Get Servicio - Sub Actividad por id-------------*/
    public function get_servicio_actividad_id($serv_id){
        $sql = 'select *
                from servicios_actividad
                where serv_id='.$serv_id.' and serv_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Lista de Actividades Institucionales-------------*/
    public function list_actividades_institucionales($dist_id){
        $sql = '
                select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where ua.dist_id='.$dist_id.' and ua.act_estado!=\'3\'
                order by ua.te_id,ua.act_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Get Actividad -------------*/
    public function get_ue_codigo_actividad($ue_id,$cod){
        $sql = 'select *
                from unidad_actividad 
                where dist_id='.$ue_id.' and act_cod=\''.$cod.'\' and act_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------------ Total Operaciones por Sub Actividades -------------*/
    public function total_ope_subactividad(){
        $sql = 'select sa.serv_id, sa.serv_cod,sa.serv_descripcion,SUM(prod.nro_prod) total_ope
                from servicios_actividad sa
                Inner Join _componentes as c On c.serv_id=sa.serv_id

                Inner Join (
                select com_id,count(*) nro_prod
                from _productos
                where estado!=3
                group by com_id

                ) as prod On prod.com_id=c.com_id

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where sa.serv_estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id!=\'1\'

                group by sa.serv_id, sa.serv_descripcion
                order by serv_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*------------ Lista de Operaciones segun servicio por Regional -------------*/
    public function list_ope_sactividades($serv_id,$dep_id){
        if($this->gestion!=2020){
            $sql = 'select sa.serv_id, sa.serv_cod,sa.serv_descripcion,apg.*,p.*,prod.*, dep.*
                from servicios_actividad sa
                Inner Join _componentes as c On c.serv_id=sa.serv_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id

                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id

                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where sa.serv_id='.$serv_id.' and dep.dep_id='.$dep_id.' and sa.serv_estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id!=\'1\' and prod.estado!=\'3\'

                order by serv_id , dep.dep_id asc';
        }
        else{
            $sql = 'select sa.serv_id, sa.serv_cod,sa.serv_descripcion,apg.*,p.*,prod.*, dep.*,ua.*,te.*
                from servicios_actividad sa
                Inner Join _componentes as c On c.serv_id=sa.serv_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                 Inner Join objetivos_regionales as ore On ore.or_id=prod.or_id
                        Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                        Inner Join objetivo_gestion as og On og.og_id=opm.og_id
            
                        Inner Join _acciones_estrategicas as ae On ae.ae=prod.acc_id
                        Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                        

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
            
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where sa.serv_id='.$serv_id.' and dep.dep_id='.$dep_id.' and sa.serv_estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and prod.estado!=\'3\'

                order by serv_id , dep.dep_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------ Lista de Operaciones segun servicio por Regional -------------*/
    public function list_consolidado_ope_sactividades($serv_id){
        if($this->gestion!=2020){
            $sql = 'select sa.serv_id, sa.serv_cod,sa.serv_descripcion,apg.*,p.*,prod.*, dep.*,ds.*
                from servicios_actividad sa
                Inner Join _componentes as c On c.serv_id=sa.serv_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id

                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id

                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where sa.serv_id='.$serv_id.' and sa.serv_estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id!=\'1\' and prod.estado!=\'3\'

                order by serv_id , dep.dep_id asc';
        }
        else{
            $sql = 'select sa.serv_id, sa.serv_cod,sa.serv_descripcion,apg.*,p.*,prod.*, dep.*,ua.*,te.*,ds.*,ore.*,og.*,ae.*,oe.*
                from servicios_actividad sa
                Inner Join _componentes as c On c.serv_id=sa.serv_id
                Inner Join _productos as prod On prod.com_id=c.com_id

                 Inner Join objetivos_regionales as ore On ore.or_id=prod.or_id
                        Inner Join objetivo_programado_mensual as opm On ore.pog_id=opm.pog_id
                        Inner Join objetivo_gestion as og On og.og_id=opm.og_id
            
                        Inner Join _acciones_estrategicas as ae On ae.ae=prod.acc_id
                        Inner Join _objetivos_estrategicos as oe On oe.obj_id=ae.obj_id
                        

                Inner Join _proyectofaseetapacomponente as pfe On pfe.pfec_id=c.pfec_id
                Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
            
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where sa.serv_id='.$serv_id.' and sa.serv_estado!=\'3\' and c.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and prod.estado!=\'3\'

                order by serv_id , dep.dep_id asc';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /*------ Tipo de Ubicacion (2020) -------*/
    public function list_tp_ubicacion(){
        $sql = ' select *
                 from tipo_ubicacion
                 where estado!=\'0\'
                 order by tu_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Tipo de Establecimiento (2020) -------*/
    public function list_tp_establecimiento(){
        $sql = ' select *
                 from v_tp_establecimiento';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

      /*------ Lista de Provincia -------*/
    public function list_provincia($dep_id){
        $sql = 'select *
                from _provincias
                where dep_id='.$dep_id.' and prov_estado!=\'0\'
                order by prov_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de Municipios -------*/
    public function list_municipios($prov_id){
        $sql = 'select *
                from _municipios
                where prov_id='.$prov_id.' and muni_estado!=\'0\'
                order by muni_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista comunidad -------*/
    public function list_comunidad($mun_id){
        $sql = 'select *
                from _cantones
                where muni_id='.$mun_id.' and can_estado!=\'0\'
                order by can_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*------ Lista estado unidad -------*/
    public function estado_unidad(){
        $sql = 'select *
                from estado_unidad
                where estado!=\'0\'
                order by eu_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista tipo Nivel -------*/
    public function tipo_nivel(){
        $sql = 'select *
                from tipo_nivel
                where estado!=0
                order by tn_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Relacion Establecimiento - Servicio -------*/
    public function get_establecimiento_servicio($te_id,$serv_id){
        $sql = 'select *
                from establecimiento_servicio
                where te_id='.$te_id.' and serv_id='.$serv_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ lista Relacion Establecimiento - Servicio -------*/
    public function list_establecimiento_servicio($te_id){
        $sql = 'select *
                from establecimiento_servicio es
                Inner Join servicios_actividad as s On s.serv_id=es.serv_id
                where es.te_id='.$te_id.' and s.serv_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ get morbilidad consulta externa -------*/
    public function get_morbilidad_consulta_externa($uni_id,$numero){
        $sql = 'select *
                from morbilidad_consulta_externa
                where act_id='.$uni_id.' and num='.$numero.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de  morbilidad_consulta_externa -------*/
    public function list_morbilidad_consulta_externa($uni_id){
        $sql = 'select *
                from morbilidad_consulta_externa
                where act_id='.$uni_id.'
                order by num asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ get morbilidad Urgencias emergencias -------*/
    public function get_morbilidad_urgencias_emergencias($uni_id,$numero){
        $sql = 'select *
                from morbilidad_urgencias_emergencias
                where act_id='.$uni_id.' and num='.$numero.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ Lista de morbilidad_consulta_externa -------*/
    public function list_morbilidad_urgencias_emergencias($uni_id){
        $sql = 'select *
                from morbilidad_urgencias_emergencias
                where act_id='.$uni_id.'
                order by num asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //// DATOS GENERALES 
    /*--- Lista Unidades Establecimientos alineados a programa padre (2020) ---*/
    public function list_unidades_apertura(){
        $dep=$this->model_proyecto->dep_dist($this->dist);
        if($this->dist_tp==1){ /// Regional
            $sql = 'select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join aper_establecimiento as ae On ae.te_id=ua.te_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ae.aper_id
                where dist.dep_id='.$dep[0]['dep_id'].' and ua.act_estado!=\'3\' and ae.g_id='.$this->gestion.'
                order by ua.te_id,ua.act_id asc';
        }
        else{ /// Distritales
            $sql = 'select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                Inner Join aper_establecimiento as ae On ae.te_id=ua.te_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ae.aper_id
                where ua.dist_id='.$this->dist.' and ua.act_estado!=\'3\' and ae.g_id='.$this->gestion.'
                order by ua.te_id,ua.act_id asc';
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*--- Lista Unidades Establecimientos por regional (2020) ---*/
    public function list_unidades_de_regional($dep_id){
        $sql = 'select *
                from unidad_actividad ua
                Inner Join _distritales as dist On dist.dist_id=ua.dist_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                where dist.dep_id='.$dep_id.' and ua.act_estado!=\'3\' and ug.g_id='.$this->gestion.' and te.te_id!=\'21\'
                order by ua.te_id,ua.act_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*=== GET DISTRIAL POA SCANNEADO ===*/
    public function get_poa_scanneado($dist_id){
        $sql = 'select *
                from _distritales d
                Inner Join reg_tiene_poas as tp On tp.dist_id=d.dist_id
                Inner Join pdf_poas as pdf On pdf.pdf_id=tp.pdf_id
                where pdf.g_id='.$this->gestion.' and d.dist_id='.$dist_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==== GET UNIDAD, ESTABLECIMIENTOS POA SCANNEADO ====*/
    public function get_poa_scanneado_unidad($proy_id){
        $sql = 'select *
                from _proyectos as p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.proy_id='.$proy_id.' and apg.aper_gestion='.$this->gestion.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*==== LISTA DE UNIDADES Y SERVICIOS A NIVEL NACIONAL ====*/
    public function lista_unidad_servicio_poa(){
        $sql = 'select d.dep_id,ds.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad, ua.act_descripcion,sa.serv_cod, sa.serv_descripcion, d.dep_departamento, ds.dist_distrital, ds.abrev, te.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _departamentos as d On d.dep_id=p.dep_id
                Inner Join _distritales as ds On ds.dist_id=p.dist_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                Inner Join _proyectofaseetapacomponente as pfec On pfec.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfec.pfec_id
                Inner Join servicios_actividad as sa On sa.serv_id=c.serv_id
                where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\' and pfec.pfec_estado=\'1\' and c.estado!=\'3\'
                ORDER BY d.dep_id, ds.dist_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
?>  
