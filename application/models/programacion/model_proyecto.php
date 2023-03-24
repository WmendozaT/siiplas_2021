<?php
class Model_proyecto extends CI_Model{
    public function __construct(){
        $this->load->database();
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }
    
    public function dep_dist($dist_id){
        $sql = 'select *
                from _distritales ds
                Inner Join _departamentos as d On d.dep_id=ds.dep_id
                where ds.dist_id='.$dist_id.'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function verif_cod($cod){
        $consulta = $this->db->get_where('_proyectos',array('proy_codigo'=>$cod));
        if($consulta->num_rows()=='1')
        {return true;}
        else
        {return false;}
    }

    public function store_proyecto($data,$data1){
        $insert = $this->db->insert('_proyectos', $data);
        $insert = $this->db->insert('aperturaprogramatica', $data1);
        return $insert; 
    }

    /*--------------- GET APERTURA PROGRAMATICA ----------*/
    public function get_aper_programa($aper_id){
        $sql = '
            select *
            from aperturaprogramatica
            where aper_id='.$aper_id.' and aper_gestion='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function update_proyecto($data,$data1,$id,$cod,$id_aper){
        $this->db->where('proy_id', $id);
        $this->db->where('proy_codigo', $cod);
        $this->db->update('_proyectos', $data);

        $this->db->where('aper_id', $id_aper);
        $this->db->update('aperturaprogramatica', $data1);

        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if($report !== 0){
            return true;
        }else{
            return false;
        }
    }

    /*======= BORRA LA APERTURA ID =========*/
    public function delete_aper_id($aper_id){ 
        
        $this->db->where('aper_id', $aper_id);
        $this->db->delete('aperturaproyectos');

        $this->db->where('aper_id', $aper_id);
        $this->db->delete('aperturaprogramatica');
    }


    /*---------- LISTA UNIDADES (2019-2020) ----------*/
    public function list_unidades($tp_id,$est_proy){
        $dep=$this->dep_dist($this->dist);
        /// Administrador Nacional
        if($this->adm==1){
            $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id='.$tp_id.' and apg.aper_proy_estado='.$est_proy.'  and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';
        }
        /// Administrador Regional/Distrital
        else{
            if($this->dist_tp==1){ /// Regional
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id='.$tp_id.' and p.dep_id='.$dep[0]['dep_id'].' and apg.aper_proy_estado='.$est_proy.' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';
            }
            else{ /// Distrital
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id='.$tp_id.' and p.dep_id='.$dep[0]['dep_id'].' and p.dist_id='.$this->dist.' and apg.aper_proy_estado='.$est_proy.' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*---------- LISTA PROYECTOS DE INVERSION (2020) ----------*/
    public function list_pinversion($tp_id,$est_proy){
        $dep=$this->dep_dist($this->dist);
        // $est_proy=1 (Programacion) -> aper_gestion==$this->gestion and aper_proy_estado==1
        // $est_proy=4 (Aprobado) -> pfec_estado==1 & aper_gestion==$this->gestion & aper_proy_estado==4

        if($est_proy==1){ /// Proyectos en etapa Inicial (Programacion Inicial)
            /// Administrador Nacional
            if($this->adm==1){
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,p.proy_estado,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and apg.aper_proy_estado=\'1\' and apg.aper_estado!=\'3\'
                            ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
            }
            /// Administrador Regional/Distrital
            else{
                if($this->dist_tp==1){ /// Regional
                    $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and p.dep_id='.$dep[0]['dep_id'].' and apg.aper_proy_estado=\'1\' and apg.aper_estado!=\'3\'
                            ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
                }
                else{ /// Distrital
                    $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and p.dep_id='.$dep[0]['dep_id'].' and p.dist_id='.$this->dist.' and apg.aper_proy_estado=\'1\' and apg.aper_estado!=\'3\'
                            ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
                }
            }
        }
        else{ /// Proyectos Aprobados y con fase activa
            /// Administrador Nacional
            if($this->adm==1){
                $sql = 'select *
                        from _proyectofaseetapacomponente pfe
                        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                        Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                        Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        where apg.aper_gestion='.$this->gestion.' and pfe.estado!=\'3\' and p.estado!=\'3\' and p.tp_id=\'1\' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                        order by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
            }
            /// Administrador Regional/Distrital
            else{
                if($this->dist_tp==1){ /// Regional
                    $sql = 'select *
                        from _proyectofaseetapacomponente pfe
                        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                        Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                        Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        where p.dep_id='.$dep[0]['dep_id'].' and apg.aper_gestion='.$this->gestion.' and pfe.estado!=\'3\' and p.estado!=\'3\' and p.tp_id=\'1\' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                        order by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc ';
                }
                else{ /// Distrital
                    $sql = 'select *
                        from _proyectofaseetapacomponente pfe
                        Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                        Inner Join _proyectos as p On p.proy_id=pfe.proy_id
                        Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        where p.dep_id='.$dep[0]['dep_id'].' and p.dist_id='.$this->dist.' and apg.aper_gestion='.$this->gestion.' and pfe.estado!=\'3\' and p.estado!=\'3\' and p.tp_id=\'1\' and apg.aper_estado!=\'3\' and apg.aper_proy_estado=\'4\'
                        order by apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc ';
                }
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*==================== LISTA NUEVA DE OPERACIONES =============================*/
/*    public function list_proyectos($mod,$prog,$est_proy,$tpf){
        $dep=$this->dep_dist($this->dist);
        if($this->adm==1){
            if($this->rol==1){
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,p.proy_mod,tp.tp_tipo,apg.aper_id,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                        Inner Join funcionario as f On f.fun_id=pf.fun_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                        where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                        ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
            else{
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,p.proy_mod,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                        Inner Join funcionario as f On f.fun_id=pf.fun_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                        where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and f.fun_id='.$this->fun_id.' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                        ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
        }  
        elseif($this->adm==2){
                if($this->rol==1){
                    if($this->dist_tp==1){
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,p.proy_mod,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                            Inner Join funcionario as f On f.fun_id=pf.fun_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                            where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and p.dep_id='.$dep[0]['dep_id'].' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                            ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
                    elseif($this->dist_tp==0){
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,p.proy_mod,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                            Inner Join funcionario as f On f.fun_id=pf.fun_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                            where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and p.dep_id='.$dep[0]['dep_id'].' and p.dist_id='.$this->dist.' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                            ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
                    else{
                        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,p.proy_mod,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                            Inner Join funcionario as f On f.fun_id=pf.fun_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                            where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and p.dep_id='.$dep[0]['dep_id'].' and p.dist_id='.$this->dist.' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                            ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
                    }
            }
            else{
                $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,p.proy_mod,
                            apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.aper_proy_estado,apg.tp_obs,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital,pfe.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                            Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                            Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                            Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                            Inner Join funcionario as f On f.fun_id=pf.fun_id
                            Inner Join _departamentos as d On d.dep_id=p.dep_id
                            Inner Join _distritales as ds On ds.dist_id=p.dist_id
                            Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                            where apg.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\' and f.fun_id='.$this->fun_id .' and p.dep_id='.$dep[0]['dep_id'].' and apg.aper_proy_estado='.$est_proy.' and pf.pfun_tp='.$tpf.'
                            ORDER BY apg.aper_proyecto,apg.aper_actividad asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- LISTA DE OPERACIONES POR DEPARTAMENTOS ------------*/
    public function list_proyectos_departamentos($prog,$est_proy,$tpf,$dep_id,$tp_id){
        $sql = 'select tap.*,p.*,tp.*,fu.*
                    from _proyectos as p
                    Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                    Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                    Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp='.$tpf.') as fu On fu.proy_id=p.proy_id
                    where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->session->userdata("gestion").' and dep_id='.$dep_id.' and p.tp_id='.$tp_id.' and tap.aper_proy_estado='.$est_proy.'
                    ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function list_proyectos_poa($prog,$est_proy,$tp){
         $sql = 'select tap.*,p.*,tp.*,fu.*
                    from _proyectos as p
                    Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                    Inner Join _estadoproyecto as ep On ep.ep_id=p.proy_estado
                    Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                    Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                    where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and p.tp_id='.$tp.' and tap.aper_gestion='.$this->session->userdata("gestion").' and tap.aper_proy_estado='.$est_proy.' 
                    ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*---------- (NUEVO) LISTA DE PROYECTOS, PROGRAMAS  PARA RESPORTES FORMULARIOS POA (NACIONALES,REGIONALES)-------*/
    public function fpoa_operaciones($prog,$gestion){
        $dep=$this->dep_dist($this->dist);
        if($this->adm==1) {
            if($this->rol==1){
                $sql = 'select tap.*,p.*,tp.*,fu.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                        Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.'
                ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
            }
            else{
                if(count($this->verif_componente($this->session->userdata('fun_id'),$est_proy))!=0){
                     $sql = 'select tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,p.proy_id,p.proy_nombre,p.proy_sisin,p.tp_id,p.proy_pr,p.t_obs,p.proy_observacion,tp.tp_tipo,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,fu.ue,fu.ur,pfe.pfec_id
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                        Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                            from _proyectofuncionario pf
                                Inner Join funcionario as f On pf.fun_id=f.fun_id
                                Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                where pf.pfun_tp='.$tpf.') as fu On fu.proy_id=p.proy_id
                        Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                        Inner Join _componentes as com On com.pfec_id=pfe.pfec_id       
                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and tap.aper_proy_estado='.$est_proy.' and pfe.pfec_estado=\'1\' and com.resp_id='.$this->fun_id.' and dep_id='.$dep[0]['dep_id'].' and p.tp_id='.$tp_id.'
                        GROUP BY tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,p.proy_id,p.proy_nombre,p.proy_sisin,p.tp_id,p.proy_pr,p.t_obs,p.proy_observacion,tp.tp_tipo,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,fu.ue,fu.ur,pfe.pfec_id
                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                }
                else{
                    $sql = 'select tap.*,p.*,tp.*,fu.*
                    from _proyectos as p
                    Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                    Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                    Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                            from _proyectofuncionario pf
                            Inner Join funcionario as f On pf.fun_id=f.fun_id
                            Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                            Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                    where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and fu.fun_id='.$this->fun_id.' 
                    ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                }
                
            }
        }  
        elseif($this->adm==2){
                if($this->rol==1){
                    if($this->dist_tp==1){
                        $sql = 'select tap.*,p.*,tp.*,fu.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                        Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                                from _proyectofuncionario pf
                                Inner Join funcionario as f On pf.fun_id=f.fun_id
                                Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                                Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and dep_id='.$dep[0]['dep_id'].'
                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                    }
                    else{
                        if(count($this->verif_componente($this->session->userdata('fun_id'),$est_proy))!=0){
                             $sql = 'select tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,p.proy_id,p.proy_nombre,p.proy_sisin,p.tp_id,p.proy_pr,p.t_obs,p.proy_observacion,tp.tp_tipo,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,fu.ue,fu.ur,pfe.pfec_id
                                from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                    from _proyectofuncionario pf
                                        Inner Join funcionario as f On pf.fun_id=f.fun_id
                                        Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                        where pf.pfun_tp='.$tpf.') as fu On fu.proy_id=p.proy_id
                                Inner Join _proyectofaseetapacomponente as pfe On p.proy_id=pfe.proy_id 
                                Inner Join _componentes as com On com.pfec_id=pfe.pfec_id       
                                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and tap.aper_proy_estado='.$est_proy.' and pfe.pfec_estado=\'1\' and com.resp_id='.$this->fun_id.' and dep_id='.$dep[0]['dep_id'].' and p.tp_id='.$tp_id.'
                                GROUP BY tap.aper_programa,tap.aper_proyecto,tap.aper_actividad,p.proy_id,p.proy_nombre,p.proy_sisin,p.tp_id,p.proy_pr,p.t_obs,p.proy_observacion,tp.tp_tipo,fu.fun_nombre,fu.fun_paterno,fu.fun_materno,fu.ue,fu.ur,pfe.pfec_id
                                ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                        }
                        else{
                            $sql = 'select tap.*,p.*,tp.*,fu.*
                            from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                            Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                                    from _proyectofuncionario pf
                                    Inner Join funcionario as f On pf.fun_id=f.fun_id
                                    Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                                    Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                            where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                            where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and dep_id='.$dep[0]['dep_id'].' and dist_id='.$this->dist.'
                            ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                        }  
                    }
            }
            else{
                $sql = 'select tap.*,p.*,tp.*,fu.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                        Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                                from _proyectofuncionario pf
                                Inner Join funcionario as f On pf.fun_id=f.fun_id
                                Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                                Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$gestion.' and fu.fun_id='.$this->fun_id .' and dep_id='.$dep[0]['dep_id'].'
                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
            }
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*--------- LISTA PROYECTOS DE INVERSIÓN  --------*/
    public function list_proyectos_inversion(){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,
                        apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,d.dep_departamento,ds.dist_distrital
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _proyectofuncionario as pf On pf.proy_id=p.proy_id
                        Inner Join funcionario as f On f.fun_id=pf.fun_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'1\' and pf.pfun_tp=\'1\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*-----------------------------------------------------------------------------------------------------------*/

    /*==================================== NUMERO DE RESPONSABLES DE PROYECTOS================================*/
    public function nro_resp($proy_id){
        $this->db->from('_proyectofuncionario');
        $this->db->where('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    /*======================================================================================================*/
    /*============================================================ FUNCIONARIOS RESPONSABLES ===================================================*/
    public function responsable_proy($proy_id,$tp){
         $sql = 'SELECT pf."proy_id",pf."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",pf."uni_ejec",u1."uni_unidad" as uejec, pf."uni_resp",u2."uni_unidad" as uresp
                from _proyectofuncionario as pf
                Inner Join funcionario as f On pf."fun_id"=f."fun_id" 
                Inner Join unidadorganizacional as u1 On u1."uni_id"=pf."uni_ejec" 
                Inner Join unidadorganizacional as u2 On u2."uni_id"=pf."uni_resp"
                where pf."pfun_tp"='.$tp.' and pf.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*============================================================================================== ===========================================*/

    /*========== NRO DE PROYECTOS ==========*/
    public function nro_proyectos($tp){
        $this->db->from('_proyectos');
        $this->db->where('tp_id', $tp);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*============= FUNCIONARIO A ASIGNAR TOP, POA, FINANCIERO ===========*/  
     public function asig_responsables($tp,$dist_id){
        $sql = 'select f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno
                from funcionario f
                Inner Join fun_rol as fr On fr.fun_id=f.fun_id 
                Inner Join rol as r On r.r_id=fr.r_id
                Inner Join _distritales as dist On dist.dist_id=f.fun_dist
                where (r.r_id='.$tp.' or r.r_id=\'1\') and f.fun_estado!=\'3\'and f.fun_dist='.$dist_id.'
              group by f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function asig_responsables_proy($rol_id){
        $sql = 'SELECT f.*,fr.*,r.*
                from funcionario f
                Inner Join fun_rol as fr On fr."fun_id"=f."fun_id" 
                Inner Join rol as r On r."r_id"=fr."r_id" 
                where r."r_id"='.$rol_id.' and f.fun_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function asig_responsables_vpoa($tp_id){
        $sql = 'select f.*,fr.*,r.*
                from funcionario f
                Inner Join fun_rol as fr On fr."fun_id"=f."fun_id" 
                Inner Join rol as r On r."r_id"=fr."r_id" 
                where r.r_id=\'4\' and f.tp_id='.$tp_id.' and f.fun_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*=====================================================================*/ 
    /*============= APERTURA BUSCADA  ===========*/  
     public function aper_id($proy_id,$gestion){
        $sql = 'SELECT apy."proy_id", apg."aper_id" 
                from aperturaprogramatica as apg, aperturaproyectos as apy 
                where apy."aper_id"=apg."aper_id" and apg."aper_gestion"='.$gestion.' and apy.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*==================== VERIFICA APERTURA GESTION ===================*/
    public function verif_apertura_gestion($proy_id,$gestion){
         $sql = 'select *
                from aperturaproyectos ap
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where ap.proy_id='.$proy_id.' and apg.aper_gestion='.$gestion.' and apg.aper_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*======================================================================*/ 
    public function unidades_ejecu(){
        $sql = 'select *
                from unidadorganizacional
                where uni_ejecutora=\'1\' and uni_estado!=\'0\' order by uni_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function list_unidad_org(){
        $this->db->from('unidadorganizacional');
        $this->db->order_by("uni_id","asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /*=========================  DATOS UNIDAD X =================== ok */
    public function get_unidad($uni_id){
        $this->db->select("*");
        $this->db->from('unidadorganizacional');
        $this->db->where('uni_id',$uni_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_estado($ep_id){
        $this->db->select("*");
        $this->db->from('_estadoproyecto');
        $this->db->where('ep_id',$ep_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_eje_programatica($ptdi_id){
        $sql = 'select *
                from ptdi
                where ptdi_id='.$ptdi_id.' and ptdi_jerarquia=\'1\' and ptdi_gestion='.$this->session->userdata('gestion').'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_region($reg_id){
        $sql = ' select *
                 from _regiones
                 where reg_id='.$reg_id.' and reg_estado!=\'0\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_municipios($muni_id){
        $sql = 'select mun.*
                from _provincias as prov
                Inner Join _municipios as mun On prov.prov_id=mun.prov_id
                where mun.muni_id='.$muni_id.' and prov.prov_estado=\'1\' and mun.muni_estado=\'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_provincia($prov_id){
        $sql = ' select *
                 from _provincias as prov
                 where prov.prov_id='.$prov_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function tip_proy(){
        $sql = '
            select tp.*
            from _tipoproyecto as tp
            where tp.tp_estado!=\'0\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    /*==================================== ACTIVAR FASE ETAPA ================================================*/
    public function clear_usu($id_p){
      $update= pg_query("UPDATE _proyectofuncionario SET pfun_estado = '0' WHERE proy_id='".$id_p."'");
      
    }

    /*=========================  PARA LA APERTURA PROGRAMATICA ===================*/
    public function apertura_p($gest,$prog,$proy,$act){
        $this->db->from('aperturaprogramatica');
        $this->db->where('aper_gestion',$gest);
        $this->db->where('aper_programa',$prog);
        $this->db->where('aper_asignado',1);
        $this->db->where('aper_proyecto',$proy);
        $this->db->where('aper_actividad',$act);
        $this->db->where('aper_gestion',$this->session->userdata("gestion"));
        $this->db->order_by("aper_id","asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    /*=========================  METAS DEL PROYECTO =================== ok*/ 
    public function metas_p($id_p){
        $query=$this->db->query('SELECT * FROM _metas WHERE proy_id = '.$id_p.' AND (estado = \'1\' OR estado = \'2\') ORDER BY meta_rp ASC ');
        return $query->result_array(); 
    }
    /*========================= END  METAS DEL PROYECTO ===================*/

    /*=========================  DATOS DE LA META X =================== ok */
    public function metas_id($met_id){
        $this->db->select("*");
        $this->db->from('_metas');
        $this->db->where('meta_id',$met_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*========================= END DATOS DE LA META X  ===================*/

    /*=========================  PRIORIDAD X =================== ok */
    public function prioridad(){
        $this->db->from('prioridad');
        $query = $this->db->get();
        return $query->result_array();
    }
    /*========================= END DATOS DE LA META X  ===================*/

    /*----- GET DATOS PROYECTO - UNIDAD REGISTRADO (2020) -----*/
    public function get_datos_proyecto_unidad($proy_id){
        $sql = 'select p.*,dist.*,dep.*,ua.*,te.*,apg.*,pfe.*
                from _proyectos p
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join _departamentos as dep On dep.dep_id=p.dep_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=pfe.aper_id
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                where p.proy_id='.$proy_id.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and pfe.pfec_estado=\'1\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*====== DATOS DEL PROYECTO X INVERSION =======*/
    function get_id_proyecto($id_p){
        $query=$this->db->query('select p."proy_id", 
                                        p."proy_nombre",
                                        p."proy_codigo",
                                        p."proy_sisin",
                                        p."proy_estado",
                                        
                                        p."proy_gestion_inicio_ddmmaaaa" as f_inicial,    
                                        p."proy_gestion_fin_ddmmaaaa" as f_final,
                                        p."proy_gestion_impacto" as duracion,

                                        p."proy_desc_problema" as desc_prob,
                                        p."proy_desc_solucion" as desc_sol,
                                        p."proy_obj_general" as obj_gral,
                                        p."proy_obj_especifico" as obj_esp,
                                        p."proy_observacion",

                                        p."proy_gestion_fin" as fin,
                                        p."proy_gestion_inicio" as inicio,
                                        p."proy_act",
                                      
                                        p."proy_ponderacion",
                                        p."proy_pcion_reg",
                                        p."proy_ppto_total",
                                        p."avance_fisico",
                                        p."fecha_avance_fis",
                                        p."avance_financiero",
                                        p."fecha_avance_fin",
                                        p."fiscal_obra",
                                        p."fecha_observacion",
                                      
                                        p."proy_pr",
                                        p."dep_id",
                                        p."act_id",
                                        p."por_id",
                                        tp."tp_id",
                                        tp."tp_tipo" as tipo,

                                        tg."tg_id",
                                        tg."tg_descripcion",
                                        
                                        tap."aper_id",
                                        tap."aper_programa", 
                                        tap."aper_proyecto", 
                                        tap."aper_actividad", 
                                        tap."aper_descripcion",
                                        tap."aper_proy_estado",

                                        p."estado",
                                        ep."ep_descripcion",

                                        fu.*,
                                        dep.*,
                                        ds."dist_id",
                                        ds."dist_cod",
                                        ds."dist_distrital"
                                        
        FROM "public"."_proyectos" as p
        Inner Join "public"."_tipoproyecto" as tp On p."tp_id"=tp."tp_id"
        Inner Join "public"."_departamentos" as dep On dep."dep_id"=p."dep_id"
        Inner Join "public"."_estadoproyecto" as ep On ep."ep_id"=p."proy_estado"
        Inner Join "public"."_distritales" as ds On ds."dist_id"=p."dist_id"
        Inner Join "public"."tipo_gasto" as tg On p."tg_id"=tg."tg_id"
        Inner Join (select apy."proy_id", apg."aper_id",apg."aper_programa", apg."aper_proyecto", apg."aper_actividad",apg."aper_descripcion",apg."aper_proy_estado" from "public"."aperturaprogramatica" as apg, "public"."aperturaproyectos" as apy where apy."aper_id"=apg."aper_id" and apg."aper_gestion"='.$this->gestion.') as tap On p."proy_id"=tap."proy_id"
        Inner Join (select pf."proy_id",f."fun_id" as fun,f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad" as ue,ur."uni_unidad" as ur
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf."fun_id"=f."fun_id"
                        Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                        Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                        where pf."pfun_tp"=\'1\') as fu On fu."proy_id"=p."proy_id"
        where p."proy_id"='.$id_p.'');

        return $query->result_array();
    }

    /*-------------------------------------------- CONSULTA PARA BUSCAR PEDES X ---------------------------------------*/
    function datos_pedes($id_pd){
        $query=$this->db->query('SELECT p."pdes_id",
                                        pdes."id1",
                                        pdes."pilar",
                                        pdes."id2",
                                        pdes."meta",
                                        pdes."id3",
                                        pdes."resultado",
                                        pdes."id4",
                                        pdes."accion"
                                        
        FROM "public"."pdes" as p
        Inner Join (SELECT p1."pdes_codigo" as id1, p1."pdes_descripcion" as pilar ,p2."pdes_codigo" as id2, p2."pdes_descripcion" as meta, p3."pdes_codigo" as id3, p3."pdes_descripcion" as resultado, p4."pdes_codigo" as id4, p4."pdes_descripcion" as accion, p4."pdes_id"
        FROM "public"."pdes" as p3
        Inner Join (select pdes_depende,pdes_descripcion, pdes_codigo,pdes_id from pdes where pdes_jerarquia=\'4\' and pdes_estado=\'1\') as p4 On p3."pdes_codigo"=p4."pdes_depende"
        Inner Join (select pdes_depende,pdes_descripcion, pdes_codigo from pdes where pdes_jerarquia=\'2\' and pdes_estado=\'1\') as p2 On p2."pdes_codigo"=p3."pdes_depende"
        Inner Join (select pdes_depende,pdes_descripcion, pdes_codigo from pdes where pdes_jerarquia=\'1\' and pdes_estado=\'1\') as p1 On p1."pdes_codigo"=p2."pdes_depende"
        ) as pdes On p."pdes_id"=pdes."pdes_id"

        where p."pdes_id"='.$id_pd.'
        group by p.pdes_id,pdes.id1,pdes.pilar,pdes.id2,pdes.meta,pdes.id3,pdes.resultado,pdes.id4,pdes.accion');
        return $query->result_array();
    }

     /*-------------------------------------------- CONSULTA PARA BUSCAR PEDES X ---------------------------------------*/
    function datos_pei($pei_id){
        $query=$this->db->query('SELECT p."pei_id",
                                        pei."id1",
                                        pei."componente",
                                        pei."id2",
                                        pei."area",
                                        pei."id3",
                                        pei."programa"
                                        
        FROM "public"."pei" as p
        Inner Join (SELECT p1."pei_codigo" as id1, p1."pei_descripcion" as componente ,p2."pei_codigo" as id2, p2."pei_descripcion" as area, p3."pei_codigo" as id3, p3."pei_descripcion" as programa, p3."pei_id"
        FROM "public"."pei" as p3
        Inner Join (select pei_depende,pei_descripcion, pei_codigo from pei where pei_jerarquia=\'2\' and pei_estado=\'1\' and pei_gestion='.$this->session->userdata("gestion").') as p2 On p2."pei_codigo"=p3."pei_depende"
        Inner Join (select pei_depende,pei_descripcion, pei_codigo from pei where pei_jerarquia=\'1\' and pei_estado=\'1\' and pei_gestion='.$this->session->userdata("gestion").') as p1 On p1."pei_codigo"=p2."pei_depende"
        ) as pei On p."pei_id"=pei."pei_id"

        where p."pei_id"='.$pei_id.' and p.pei_gestion='.$this->session->userdata("gestion").'');
        return $query->result_array();
    }

    /*-------------------------------------------- CONSULTA PARA BUSCAR FINALIDAD FUNCION X ---------------------------------------*/
    function datos_finalidad($fifu_id){
        $query=$this->db->query('SELECT f1."fifu_id" as f1,f1."fifu_finalidad_funcion" as finalidad,f2."fifu_id" as f2,f2."fifu_finalidad_funcion" as funcion,f3."fifu_id" as f3,f3."fifu_finalidad_funcion" as accion
        from "public"."_finalidadfuncion" as f3
        Inner Join (select fifu_id,fifu_nivel,fifu_finalidad_funcion,fifu_depende from _finalidadfuncion)as f2 On f2."fifu_id"=f3."fifu_depende" 
        Inner Join (select fifu_id,fifu_nivel,fifu_finalidad_funcion,fifu_depende from _finalidadfuncion)as f1 On f1."fifu_id"=f2."fifu_depende" 
        where f3."fifu_id"='.$fifu_id.'');

        return $query->result_array();
    }
    /*-------------------------------------------- CLASIFICACION SECTORIAL ---------------------------------------*/
    function codigo_sectorial($codsec){   
        $n='3';
        $query=$this->db->query('SELECT cd3."codsectorial" as cod3, cd3."codsectorialduf" as actividad,cd3."descclasificadorsectorial" as desc3, cd2."codsectorial" as cod2,cd2."codsectorialduf" as subsector,cd2."descclasificadorsectorial" as desc2, cd1."codsectorial" as cod1,cd1."codsectorialduf" as sector,cd1."descclasificadorsectorial" as desc1
        from _clasificadorsectorial cd3
        Inner Join (SELECT codsectorial,codsectorialduf,descclasificadorsectorial,codsubsec from _clasificadorsectorial where nivel=\'2\') as cd2 On cd2."codsubsec"=cd3."codsubsec"
        Inner Join (SELECT codsectorial,codsectorialduf,descclasificadorsectorial,codsec from _clasificadorsectorial where nivel=\'1\') as cd1 On cd1."codsec"=cd3."codsec"
        where cd3."codsectorial"=\''.$codsec.'\' and cd3."nivel"='.$n.'');

        return $query->result_array();
    }

    /*=================================== AGREGAR APERTURA PROGRAMATICA ====================================*/
    public function add_apertura($ip_proy,$gestion,$prog,$proy,$act,$desc,$fun_id){
         $data_to_store1 = array(
            'aper_gestion' => $gestion,
            'aper_programa' => $prog,
            'aper_proyecto' => $proy,
            'aper_actividad' => $act,
            'aper_descripcion' => strtoupper($desc),
            'fun_id' => $fun_id,
            );
        $this->db->insert('aperturaprogramatica', $data_to_store1);
        $id_aper=$this->db->insert_id();

        $data_to_store2 = array(
            'aper_id' => $id_aper,
            'proy_id' => $ip_proy,
        );
        $this->db->insert('aperturaproyectos', $data_to_store2);
    }

    /*=================================== AGREGAR PRODUCTO  PROGRAMADO GESTION ====================================*/
    /*=================================== UPDATE APERTURA PROGRAMATICA ====================================*/
    public function update_apertura($ip_aper,$prog,$proy,$act,$desc,$fun_id){
        $update_aper = array(
            'aper_programa' => $prog,
            'aper_proyecto' => $proy,
            'aper_actividad' => $act,
            'aper_descripcion' => $desc,
            'fun_id' => $fun_id);

        $this->db->where('aper_id', $ip_aper);
        $this->db->update('aperturaprogramatica', $update_aper);
    }

    /*==================== AGREGAR PRODUCTO  PROGRAMADO GESTION =====================*/
    public function add_resp_proy($proy_id,$fid1,$fid2,$fid3,$uni,$ejec){
    /*==============  FUNCIONARIO PROYECTO TECNICO DE PLANIFICACION ================*/
      $data_to_store = array(
                  'proy_id' => $proy_id,
                  'fun_id' => $fid1,
                  'pfun_descripcion' => 'RESPONSABLE DE UNIDAD EJECUTORA',
                  'pfun_fecha' => date('d/m/Y h:i:s'),
                  'pfun_estado' => '1',
                  'uni_ejec' => $uni,
                  'uni_resp' => $ejec,
                  'pfun_tp' => '1',
              );
      $this->db->insert('_proyectofuncionario', $data_to_store);
    /*============= END  FUNCIONARIO PROYECTO TECNICO DE PLANIFICACION ================*/

      /*==============  FUNCIONARIO PROYECTO TECNICO VALIDADOR POA ================*/
      $data_to_store = array(
                  'proy_id' => $proy_id,
                  'fun_id' => $fid2,
                  'pfun_descripcion' => 'RESPONSABLE ANALISTA POA',
                  'pfun_fecha' => date('d/m/Y h:i:s'),
                  'uni_ejec' => $uni,
                  'uni_resp' => $ejec,
                  'pfun_estado' => '1',
                  'pfun_tp' => '2',
              );
      $this->db->insert('_proyectofuncionario', $data_to_store);
    /*============= END  FUNCIONARIO PROYECTO TECNICO VALIDADOR POA ================*/

    /*==============  FUNCIONARIO PROYECTO TECNICO VALIDADOR FINANCIERO ================*/
      $data_to_store = array(
                  'proy_id' => $proy_id,
                  'fun_id' => $fid3,
                  'pfun_descripcion' => 'RESPONSABLE ANALISTA FINANCIERO',
                  'pfun_fecha' => date('d/m/Y h:i:s'),
                  'uni_ejec' => $uni,
                  'uni_resp' => $ejec,
                  'pfun_estado' => '1',
                  'pfun_tp' => '3',
              );
      $this->db->insert('_proyectofuncionario', $data_to_store);
    }
    /*==============================================================================================================*/

    /*=================================== AGREGAR PRODUCTO  PROGRAMADO GESTION ====================================*/
    public function update_resp_proy($proy_id,$fid1,$fid2,$fid3,$uni,$ejec){
        /*==============  FUNCIONARIO PROYECTO TECNICO DE PLANIFICACION ================*/
        $update_top = array(
                          'fun_id' => $fid1,
                          'uni_ejec' => $uni,
                          'uni_resp' => $ejec,
                          'pfun_estado' => '2',
                      );
            $this->db->where('proy_id', $proy_id);
            $this->db->where('pfun_tp', 1);
            $this->db->update('_proyectofuncionario', $update_top);
        /*============= END  FUNCIONARIO PROYECTO TECNICO DE PLANIFICACION ================*/

        /*==============  FUNCIONARIO PROYECTO TECNICO VALIDADOR POA ================*/
            $update_poa = array(
                          'fun_id' => $fid2,
                          'uni_ejec' => $uni,
                          'uni_resp' => $ejec,
                          'pfun_estado' => '2',
                      );
              $this->db->where('proy_id', $proy_id);
              $this->db->where('pfun_tp', 2);
              $this->db->update('_proyectofuncionario', $update_poa);
        /*============= END  FUNCIONARIO PROYECTO TECNICO VALIDADOR POA ================*/

        /*==============  FUNCIONARIO PROYECTO TECNICO VALIDADOR FINANCIERO ================*/
              $update_fin = array(
                          'fun_id' => $fid3,
                          'uni_ejec' => $uni,
                          'uni_resp' => $ejec,
                          'pfun_estado' => '2',
                      );
              $this->db->where('proy_id', $proy_id);
              $this->db->where('pfun_tp', 3);
              $this->db->update('_proyectofuncionario', $update_fin);


         /*==============  FUNCIONARIO PROYECTO TECNICO VALIDADOR FINANCIERO ================*/
              $update_aper = array(
                          'fun_id' => $fid3,
                          'uni_ejec' => $uni,
                          'uni_resp' => $ejec,
                          'pfun_estado' => '2',
                      );
              $this->db->where('proy_id', $proy_id);
              $this->db->where('pfun_tp', 3);
              $this->db->update('_proyectofuncionario', $update_aper);
    }

    function fechas_proyecto($id_p){
        $this->db->select(' extract(years from (proy_gestion_inicio_ddmmaaaa))as inicio,
                            extract(years from (proy_gestion_fin_ddmmaaaa))as final');
        $this->db->from('_proyectos ');
        $this->db->where('proy_id', $id_p);
        $query = $this->db->get();
        return $query->result_array();
    }

    /*============================ END BORRA DATOS DE F/E GESTION =================================*/

    /*============================ BORRA LA META DEL PROYECTO=================================*/
    public function delete_meta($id_m){ 
        $this->db->where('meta_id', $id_m);
        $this->db->delete('_metas');

        $this->db->from('_metas');
        $this->db->where('meta_id', $id_m);
        $query = $this->db->get();
        return $query->num_rows(); 
    }
 
 /*======================================================= VERIFICANDO QUE EL PROYECTO TENGA DATOS HASTA ACTIVIDADES ========================*/
    public function verif_proy($proy_id){
        $sql = 'SELECT p.*
            from _proyectos as p
            Inner Join (select * from _proyectofaseetapacomponente where pfec_estado=\'1\')as f On f."proy_id"=p."proy_id"
        Inner Join (select * from ptto_fase_gestion where g_id='.$this->session->userdata("gestion").')as fg On fg.pfec_id=f.pfec_id
        Inner Join _componentes as c On c.pfec_id=f.pfec_id
        Inner Join _productos as pr On pr.com_id=c.com_id
        where p.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->num_rows(); 
    }
    /*============================================================================================== ===========================================*/
    /*======================================================= VERIFICANDO QUE EL PROYECTO TENGA DATOS HASTA PRODUCTOS ========================*/
    public function verif_proy_prod($proy_id){
        $sql = 'SELECT p.*
            from _proyectos as p
            Inner Join (select * from _proyectofaseetapacomponente where pfec_estado=\'1\')as f On f."proy_id"=p."proy_id"
        Inner Join (select * from ptto_fase_gestion where g_id='.$this->session->userdata("gestion").')as fg On fg.pfec_id=f.pfec_id
        Inner Join _componentes as c On c.pfec_id=f.pfec_id
        Inner Join _productos as pr On pr.com_id=c.com_id
        where p.proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->num_rows(); 
    }
    /*============================================================================================== ===========================================*/
    

    /*============================ END BORRA PROYECTO  INVERSION =================================*/
/*    public function datos_proyecto($id_p){
        $this->db->select('*');
        $this->db->from('_proyectos ');
        $this->db->where('proy_id', $id_p);
        $query = $this->db->get();
        return $query->result_array();
    }*/

    /*------ VERIF APERTURA PROGRAMATICA - GASTO CORRIENTE (2020) ----*/
    public function verif_programa_unidad($aper_programa,$aper_actividad){
        $sql = '
                select *
                from aperturaprogramatica
                where aper_programa=\''.$aper_programa.'\' and aper_actividad=\''.$aper_actividad.'\' and aper_gestion='.$this->gestion.' and aper_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ VERIF APERTURA PROGRAMATICA - PROYECTO DE INVERSION (2020) ----*/
    public function verif_programa_pi($aper_programa,$aper_proyecto){
        $sql = '
                select *
                from aperturaprogramatica
                where aper_programa=\''.$aper_programa.'\' and aper_proyecto=\''.$aper_proyecto.'\' and aper_gestion='.$this->gestion.' and aper_estado!=\'3\'';

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=========================== END VERIFICA SI SI LA FASE ESTA ENCENDIDO/APAGADO ======================*/
    public function nro_aperturas_hijos($prog,$proy,$act,$gest){
        $this->db->select("*");
        $this->db->from('aperturaprogramatica');
        $this->db->where('aper_programa', $prog);
        $this->db->where('aper_proyecto', $proy);
        $this->db->where('aper_actividad', $act);
        $this->db->where('aper_gestion', $gest);
        $this->db->where('aper_estado !=', 3);

        $consulta = $this->db->get();
        $cantidad_encontrados = $consulta->num_rows();

        if($cantidad_encontrados>='1')
        {return true;}
        else
        {return false;}
    }

    public function get_id_pdes($cod){
        $this->db->select("pdes_id");
        $this->db->from('pdes');
        $this->db->where('pdes_codigo', $cod);
        $query = $this->db->get();
        
        return $query->result_array();  
    }

    public function get_id_pei($cod){
        $this->db->select("pei_id");
        $this->db->from('pei');
        $this->db->where('pei_codigo', $cod);
        $query = $this->db->get();
        
        return $query->result_array();  
    }

    public function cod_proy(){
        $query=$this->db->query('SELECT proy_codigo FROM _proyectos ORDER BY proy_id DESC LIMIT 1');
        return $query->result_array(); 
    }

    public function get_indicador(){
        $this->db->select('*');
        $this->db->from('indicador ');
        $query = $this->db->get();
        return $query->result_array();
    }
    /*============== LISTA DE APERTURAS PROGRAMAS PADRES ============*/
    public function list_prog(){   
        if($this->gestion>2022){
            $sql = 'select *
                from aperturaprogramatica
                where aper_proyecto=\'00\' and aper_actividad=\'000\' and aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_gestion,aper_programa,aper_proyecto,aper_actividad asc';
        }
        else{
            $sql = 'select *
                from aperturaprogramatica
                where aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_gestion,aper_programa,aper_proyecto,aper_actividad asc';
        }

        
        $query = $this->db->query($sql);
        return $query->result_array(); 
    }
    /*==================================================================*/

    /*============== LISTA DE APERTURAS PROGRAMAS PADRES CONTROL SOCIAL ============*/
    public function list_prog2($gestion){   
        $proy='0000';
        $act='000';

        $this->db->from('aperturaprogramatica');
        $this->db->where('aper_proyecto', $proy);
        $this->db->where('aper_actividad', $act);
        $this->db->where('aper_gestion', $gestion);
        $this->db->where('aper_asignado', 1);
        $this->db->ORDER_BY ('aper_gestion,aper_programa,aper_proyecto,aper_actividad','ASC');
        $query = $this->db->get();
        
        return $query->result_array();  
    }
    /*==================================================================*/

    /*================ ESTADO DEL PROYECTO==============*/  
    public function proy_estado(){
        $this->db->from('_estadoproyecto');
        $query = $this->db->get();
        return $query->result_array();
    }

    /*================ ARCHIVO DEL PROYECTO X ==============*/  
/*    public function get_archivo_proy($id){
        $this->db->select("*");
        $this->db->from('_proyecto_adjuntos');
        $this->db->where('adj_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*================ ARCHIVO MES DEL PROYECTO X ==============*/  
/*    public function get_archivo_mes_proy($id){
        $this->db->select("*");
        $this->db->from('fase_ejecucion_adjuntos');
        $this->db->where('fa_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*================ ARCHIVO MENSUAL X ==============*/  
/*    public function get_archivo_mes($id){
        $this->db->select("*");
        $this->db->from('fase_ejecucion_adjuntos');
        $this->db->where('fa_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
  /*============================ NRO DE ARCHIVOS POR MESES =================================*/
/*    public function nro_arch_meses($id_pr,$gest){
        $this->db->from('fase_ejecucion_adjuntos');
        $this->db->where('proy_id', $id_pr);
        $this->db->where('ejec_gestion', $gest);
        $query = $this->db->get();
        return $query->num_rows();
    }*/

  /*============================= LOCALIZACION DEL PROYECTO =======================*/
    /*========== NRO DE DEPARATAMENTOS DEL PROYECTO X ==========*/
/*    public function nro_proy_dep($id){
        $this->db->from('_proyectosdepartamentos');
        $this->db->where('proy_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*================ DEPARTAMENTOS DEL PROYECTO X ==============*/  
/*    public function proy_dep($id){
        $this->db->select("
                pd.proy_id,
                pd.dep_id,
                dep.dep_departamento
                        ");
        $this->db->from('_proyectosdepartamentos pd');
        $this->db->join('_departamentos dep', 'pd.dep_id = dep.dep_id', 'left');
        $this->db->where('pd.proy_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*========== NRO DE PROVINCIAS DEL PROYECTO X ==========*/
/*    public function nro_proy_prov($id){
        $this->db->from('_proyectosprovincias');
        $this->db->where('proy_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*================ PROVINCIAS DEL PROYECTO X ==============*/  
/*    public function proy_prov($id){
        $this->db->select("
                pp.proy_id,
                pp.prov_id,
                prov.dep_id,
                prov.prov_provincia
                        ");
        $this->db->from('_proyectosprovincias pp');
        $this->db->join('_provincias prov', 'pp.prov_id = prov.prov_id', 'left');
        $this->db->where('pp.proy_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*========== NRO DE MUNICIPIOS DEL PROYECTO X ==========*/
/*    public function nro_proy_mun($id){
        $this->db->from('_proyectosmunicipios');
        $this->db->where('proy_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*================ MINICPIOS DEL PROYECTO X ==============*/  
/*    public function proy_mun($id){
        $this->db->select("
                pm.proy_id,
                pm.muni_id,
                pm.pm_pondera,
                rg.reg_region,
                mun.muni_municipio,
                mun.prov_id,
                mun.muni_poblacion_hombres,
                mun.muni_polacion_mujeres
        ");
        $this->db->from('_proyectosmunicipios pm');
        $this->db->join('_municipios mun', 'pm.muni_id = mun.muni_id', 'left');
        $this->db->join('_regiones rg', 'rg.reg_id = mun.reg_id', 'left');
        $this->db->where('pm.proy_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/
    /*========== NRO DE CANTONES DEL PROYECTO X ==========*/
/*    public function nro_proy_cant($id){
        $this->db->from('_proyectoscantones');
        $this->db->where('proy_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }*/
    /*================ MINICPIOS DEL PROYECTO X ==============*/  
/*    public function proy_cant($id){
        $this->db->select("
                pc.proy_id,
                pc.can_id,
                ct.muni_id,
                ct.can_canton
        ");
        $this->db->from('_proyectoscantones pc');
        $this->db->join('_cantones ct', 'pc.can_id = ct.can_id', 'left');
        $this->db->where('pc.proy_id',$id);
        $query = $this->db->get();
        return $query->result_array();
    }*/

    /*=== INDICADOR  ====*/
    public function indicador(){
        $this->db->from('indicador');
        $this->db->where('indi_estado', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*================================================================================================*/

    /*========== APERTURA DE PROGRAMAS (VIGENTE) ============*/
    public function mis_programas($proy_id){
        $this->db->from('aperturaproyectos');
        $this->db->where('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*================================================================================================*/
    /*============== MI UBICACION - REGION-PROVINCIA-MUNICIPIO===================*/
/*    public function ubicacion_proy($proy_id){
        $sql = '
        SELECT m.region,prov.prov_provincia as provincia,m.municipio
        FROM _proyectosprovincias as pp
        Inner Join _provincias as prov On pp.prov_id=prov.prov_id
        Inner Join (SELECT pm.proy_id,mun.muni_municipio as municipio, r.reg_region as region
        FROM _proyectosmunicipios as pm
        Inner Join _municipios as mun On pm.muni_id=mun.muni_id
        Inner Join _regiones as r On r.reg_id=mun.reg_id
        WHERE pm.proy_id='.$proy_id.' ORDER BY pm.pm_id ASC LIMIT \'1\') as m On m.proy_id=pp.proy_id
        WHERE pp.proy_id='.$proy_id.'
        ORDER BY pp.pp_id ASC LIMIT \'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*================================================================================================*/ 
    /*===================================================================== PROGRAMAS PROYECTO - REPORTES ==========================================*/
/*     public function programas_proyecto($prog,$gestion){

            $sql = 'SELECT p.*,tp.*,tap.*,fu.*
            from _proyectos as p
            Inner Join _tipoproyecto as tp On p."tp_id"=tp."tp_id"
            Inner Join (select apy."proy_id", apg."aper_id",apg."aper_programa", apg."aper_proyecto", apg."aper_actividad",apg."aper_descripcion",apg."aper_ponderacion" from aperturaprogramatica as apg, aperturaproyectos as apy where apy."aper_id"=apg."aper_id" and apg."aper_gestion"='.$gestion.') as tap On p."proy_id"=tap."proy_id"
            Inner Join (select pf."proy_id",f."fun_id",f."fun_nombre",f."fun_paterno",f."fun_materno",u."uni_unidad"
                        from _proyectofuncionario pf
                        Inner Join funcionario as f On pf."fun_id"=f."fun_id"
                        Inner Join unidadorganizacional as u On pf."uni_ejec"=u."uni_id"
                        where pf."pfun_tp"=\'1\') as fu On fu."proy_id"=p."proy_id"
            where tap.aper_programa=\''.$prog.'\' and estado!=\'3\'  ORDER BY tap.aper_proyecto  asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*============================================================================================== ===========================================*/
    /*======================================= TIPO DE GASTO ========================================*/
    public function tip_gasto(){
        $sql = '
            select tg.*
            from tipo_gasto as tg
            where tg.tg_estado!=\'0\' ORDER BY tg.tg_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function configuracion(){
        $this->db->from('configuracion');
        $this->db->where('ide', $this->gestion);
        $this->db->where('conf_estado', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function configuracion_session(){
        $this->db->from('configuracion');
        $this->db->where('ide', $this->gestion);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_configuracion($ide){
        $this->db->from('configuracion');
        $this->db->where('ide', $ide);
        $query = $this->db->get();
        return $query->result_array();
    }
    /*==============================================================================================*/
    /*============= LISTA DE LOCALICACION POR DEPARTAMENTO ================*/
/*    public function departamentos($proy_id){
        $sql = '
            select *
            from _proyectosdepartamentos pd
            Inner Join _departamentos as d On d.dep_id=pd.dep_id
            Inner Join _area_influencia as a On a.ar_id=pd.area_id
            where pd.proy_id='.$proy_id.' ORDER BY pd.pd_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    /*======== AREA DE INFLUENCIA =========*/
/*    public function area_influencia(){
        $sql = '
            select *
            from _area_influencia 
            where estado=\'1\'
            ORDER BY ar_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*======== LISTA DE DEPARTAMENTOS =========*/
    public function list_departamentos(){
        $sql = '
            select *
            from _departamentos
            where dep_id!=\'0\'
            ORDER BY dep_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=========== GET DEPARTAMENTO ============*/
    public function get_departamento($dep_id){
        $sql = '
            select *
            from _departamentos 
            where dep_id='.$dep_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*=========== GET COMUNIDAD ============*/
/*    public function get_comunidad($comu_id){
        $sql = '
            select *
                from _cantones
                where can_id='.$comu_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*=== LISTA DE DISTRITALES SEGUN DEPARTAMENTOS ===*/
    public function list_distritales($dep_id){
        $sql = '
            select *
            from _distritales
            where dep_id='.$dep_id.' and dist_estado!=\'0\'
            ORDER BY dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

/*    public function localizacion($proy_id){
        $this->db->from('vista_localizacion_dictamen');
        $this->db->where('proy_id', $proy_id);
        $query = $this->db->get();
        return $query->result_array();
    }
*/

    /*------------------- INSUMO ACTIVIDAD -----------------*/
/*    public function list_insumo_actividad($act_id,$gestion){
        $sql = '
            select *
            from vproy_insumo_actividad_programado
            where act_id='.$act_id.' and g_id='.$gestion.'
            ORDER BY act_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------------- INSUMO PRODUCTO -----------------*/
/*    public function list_insumo_producto($prod_id,$gestion){
        $sql = '
            select *
            from vproy_insumo_producto_programado
            where prod_id='.$prod_id.' and g_id='.$gestion.'
            ORDER BY prod_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*---------- VERIF COMPONENTE - SERVICIO VIGENTE-----------*/
    public function verif_componente_servicio($pfec_id,$serv_id){
        $sql = '
            select *
            from _componentes
            where estado!=\'3\' and pfec_id='.$pfec_id.' and serv_id='.$serv_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------ LISTA DE RESPONSABLES - UNIDAD EJECUTORA (VIGENTE)------------*/
    public function list_responsables_regionales($rol_id,$dep_id){
        $sql = '
            select f.*,fr.*,r.*,dist.*
            from funcionario f
            Inner Join fun_rol as fr On fr.fun_id=f.fun_id 
            Inner Join rol as r On r.r_id=fr.r_id
            Inner Join _distritales as dist On dist.dist_id=f.fun_dist
            where (r.r_id='.$rol_id.' or r.r_id=\'1\') and f.fun_estado!=\'3\' and dist.dep_id='.$dep_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*------------- GET PROGRAMA PADRE ----------------*/
    public function get_programa_padre($aper_programa){
        if($this->gestion>2022){ /// Gestion 2023
            $sql = '
            select *
            from aperturaprogramatica
            where aper_programa=\''.$aper_programa.'\' and aper_estado!=\'3\' and aper_gestion='.$this->gestion.' and aper_proyecto=\'00\' and aper_actividad=\'000\' and aper_asignado=\'1\'';
        }
        else{
            $sql = '
            select *
            from aperturaprogramatica
            where aper_programa=\''.$aper_programa.'\' and aper_estado!=\'3\' and aper_gestion='.$this->gestion.' and aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_asignado=\'1\'';
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    
    /*----- LISTA DE OPERACIONES-PROYECTOS , ACTUALIZAR ESTADOS POR APERTURA PROGRAMATICA*/
/*    public function list_estados(){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.estado!=\'3\' and p.tp_id=\'1\' and apg.aper_estado!=\'3\' and apg.aper_gestion='.$this->gestion.'
                order by p.tp_id,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*----- GET DATOS DEL PROYECTO SEGUN SU APERTURA PROGRAMATICA*/
/*    public function get_datos_proyecto($aper_programa,$aper_actividad){
        $sql = 'select *
                from aperturaprogramatica apg
                Inner Join aperturaproyectos as ap On apg.aper_id=ap.aper_id
                Inner Join _proyectos as p On p.proy_id=ap.proy_id
                where apg.aper_programa=\''.$aper_programa.'\' and apg.aper_actividad=\''.$aper_actividad.'\' and apg.aper_gestion='.$this->gestion.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*LISTA DE UNIDADES / PROYECTOS POR REGIONAL*/
    public function list_uni_proy($dep_id,$tp_id){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.tp_id='.$tp_id.'
                order by apg.aper_programa, apg.aper_proyecto, apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*OBTIENE DATOS DEL PROGRAMA REGISTRADO SEGUN LA ACTIVIDAD (2020)*/
    public function get_uni_apertura_programatica($act_id){
        $sql = 'select *
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                where p.act_id='.$act_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*============== REPORTE CONSOLIDADO PROGRAMACION POA (2020) =========*/
    /*-------- Lista de Regionales sin nacional--------*/
/*    public function lista_operaciones_regionales_sin_nacional(){
        $sql = 'select *
                from _departamentos dep
                Inner Join _distritales as dist On dist.dep_id=dep.dep_id
                
                Inner Join 
                (

                select p.dist_id,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by p.dist_id

                ) 
                as ope On ope.dist_id=dist.dist_id
                
                where dep.dep_id!=\'0\' and dep.dep_id!=\'10\'
                order by dep.dep_id, dist.dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- Numero de operaciones por unidades, establecimientos por Distrital --------*/
    public function lista_operaciones_unidades_distritales($dist_id){
        $sql = 'select p.proy_id,apg.aper_id,dist.dist_distrital,p.proy_nombre,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and p.tp_id=\'4\' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by p.proy_id,apg.aper_id,dist.dist_distrital,p.proy_nombre
                order by apg.aper_programa,apg.aper_programa,apg.aper_actividad asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    

    /*----- LISTA DE APERTURA PROGRAMATICA PADRES -----*/
/*    public function list_apertura_programatica_padre(){
        $sql = 'select *
                from aperturaprogramatica
                where aper_gestion='.$this->gestion.' and aper_asignado=\'1\' and aper_estado!=\'3\'
                order by aper_programa asc';

        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*-------- Numero de operaciones por unidades, establecimientos por Apertura Programatica por Regional (Gasto Corriente) --------*/
    public function lista_operaciones_unidades_apertura_distrital($dist_id,$tp_id){
        $sql = 'select p.proy_id,apg.aper_id,dist.dist_cod,dist.dist_distrital,apg.aper_programa,te.tipo,ua.act_descripcion,p.proy_nombre,p.proy_sisin,dist.dist_id, p.proy_id,dist.abrev,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.dist_id='.$dist_id.' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by p.proy_id,apg.aper_id,dist.dist_cod,dist.dist_distrital,apg.aper_programa,te.tipo,ua.act_descripcion,p.proy_nombre,p.proy_sisin,dist.dist_id, p.proy_id,dist.abrev
                order by apg.aper_programa,apg.aper_programa,apg.aper_actividad, dist.dist_id, p.proy_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero de operaciones por unidades, establecimientos, Proyectos de Inversion por Apertura Programatica por Regional (Consolidado) --------*/
    public function lista_operaciones_unidades_apertura_regional_consolidado($dep_id){
        $sql = 'select p.proy_id,p.tp_id,apg.aper_id,dist.dist_distrital,apg.aper_programa,te.tipo,ua.act_descripcion,p.proy_nombre,dist.dist_id, p.proy_id,dist.abrev,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by p.proy_id,apg.aper_id,dist.dist_distrital,apg.aper_programa,te.tipo,ua.act_descripcion,dist.dist_id, p.proy_id,dist.abrev
                order by apg.aper_programa,apg.aper_programa,apg.aper_actividad, dist.dist_id, p.proy_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero de operaciones por Apertura Programatica por Regional (Consolidado) --------*/
    public function lista_operaciones_apertura_regional_consolidado($dep_id){
        $sql = 'select apg.aper_programa,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by apg.aper_programa
                order by apg.aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero de operaciones por Apertura Programatica por Nacional (Consolidado) --------*/
    public function lista_operaciones_apertura_Nacional_consolidado(){
        $sql = 'select apg.aper_programa,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by apg.aper_programa
                order by apg.aper_programa asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- Numero de operaciones por Apertura Programatica por Regional --------*/
    public function get_operaciones_apertura_regional($dep_id,$aper_programa,$tp_id){
        $sql = 'select apg.aper_programa,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where p.dep_id='.$dep_id.' and apg.aper_programa=\''.$aper_programa.'\' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by apg.aper_programa';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero Total de operaciones por Apertura Programatica por Regional --------*/
    public function get_total_operaciones_apertura_regional($aper_programa,$tp_id){
        $sql = 'select apg.aper_programa,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                where apg.aper_programa=\''.$aper_programa.'\' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\'
                group by apg.aper_programa';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    /*-------- Numero de operaciones por unidades, establecimientos por ObjetivoRegional --------*/
    public function lista_operaciones_oregional_distrital($dist_id,$tp_id){
        $sql = 'select p.proy_id,apg.aper_id,dist_cod,dist.dist_distrital,apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,p.proy_nombre,proy_sisin,te.tipo,te.tn_id,ua.act_descripcion,dist.abrev,oreg.or_codigo,oreg.or_objetivo,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                Inner Join _distritales as dist On dist.dist_id=p.dist_id
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id
                Inner Join objetivos_regionales as oreg On oreg.or_id=prod.or_id

                where p.dist_id='.$dist_id.' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\' and oreg.estado!=\'3\'
                group by p.proy_id,apg.aper_id,dist_cod,dist.dist_id,dist.dist_distrital,te.tipo,te.tn_id,ua.act_descripcion,dist.abrev,oreg.or_codigo,oreg.or_objetivo
                order by oreg.or_codigo, dist.dist_id, te.tn_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero de operaciones por Objetivo Regional POR REGIONAL  --------*/
    public function get_operaciones_objetivo_regional($dep_id,$cod_or,$tp_id){
        $sql = 'select oreg.or_codigo,oreg.or_objetivo,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id

                Inner Join objetivos_regionales as oreg On oreg.or_id=prod.or_id

                where p.dep_id='.$dep_id.' and oreg.or_codigo='.$cod_or.' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\' and oreg.estado!=\'3\'
                group by oreg.or_codigo,oreg.or_objetivo';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- Numero total de Operaciones por Objetivo Regional  --------*/
    public function get_total_operaciones_objetivo_regional($cod_or,$tp_id){
        $sql = 'select oreg.or_codigo,count(prod.prod_id) as operaciones
                from _proyectos p
                Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                Inner Join _proyectofaseetapacomponente as pfe On pfe.proy_id=p.proy_id
                Inner Join _componentes as c On c.pfec_id=pfe.pfec_id
                Inner Join _productos as prod On prod.com_id=c.com_id
                Inner Join vista_productos_temporalizacion_programado_dictamen as prog On prog.prod_id=prod.prod_id

                Inner Join objetivos_regionales as oreg On oreg.or_id=prod.or_id

                where oreg.or_codigo='.$cod_or.' and p.tp_id='.$tp_id.' and apg.aper_gestion='.$this->gestion.' and apg.aper_estado!=\'3\' and p.estado!=\'3\' and pfe.pfec_estado=\'1\' and pfe.estado!=\'3\' and c.estado!=\'3\' and prod.estado!=\'3\' and oreg.estado!=\'3\'
                group by oreg.or_codigo';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA OPERACION DE FUNCIONAMIENTO - GASTO CORRIENTE  --------*/
    public function list_gasto_corriente(){
        $sql = 'select * from lista_poa_gastocorriente_nacional('.$this->gestion.')';

        /*$sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                    apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                        where p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';*/
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA OPERACION DE FUNCIONAMIENTO - GASTO CORRIENTE POR REGIONAL --------*/
    public function list_gasto_corriente_regional($dep_id){
        $sql = 'select p.proy_id,p.proy_codigo,p.proy_nombre,p.proy_estado,p.tp_id,p.proy_sisin,tp.tp_tipo,apg.aper_id,apg.archivo_pdf,
                    apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,apg.aper_descripcion,apg.tp_obs,aper_observacion,p.proy_pr,p.proy_act,d.dep_departamento,ds.dist_distrital,ds.abrev,ua.*,te.*
                        from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join aperturaproyectos as ap On ap.proy_id=p.proy_id
                        Inner Join aperturaprogramatica as apg On apg.aper_id=ap.aper_id
                        Inner Join _departamentos as d On d.dep_id=p.dep_id
                        Inner Join _distritales as ds On ds.dist_id=p.dist_id
                        Inner Join unidad_actividad as ua On ua.act_id=p.act_id
                        Inner Join v_tp_establecimiento as te On te.te_id=ua.te_id
                        Inner Join uni_gestion as ug On ua.act_id=ug.act_id
                        where d.dep_id='.$dep_id.' and p.estado!=\'3\' and apg.aper_gestion='.$this->gestion.' and p.tp_id=\'4\' and ug.g_id='.$this->gestion.' and apg.aper_estado!=\'3\'
                        ORDER BY apg.aper_programa,apg.aper_proyecto,apg.aper_actividad,te.tn_id, te.te_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA PROYECTOS DE INVERSION funcion INSTITUCIONAL--------*/
    public function list_proy_inversion(){ /// aprobados Institucional
        $sql = 'select *
                from lista_poa_pinversion_nacional('.$this->gestion.')
                order by dep_id, dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA PROYECTOS DE INVERSION funcion DISTRITAL--------*/
    public function list_proy_inversion_distrital($dist_id){ /// aprobados distrital
        $sql = 'select *
                from lista_poa_pinversion_nacional('.$this->gestion.')
                where dist_id='.$dist_id.'
                order by dep_id, dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- GET PROYECTOS DE INVERSION (Aprobado)--------*/
    public function get_proyecto_inversion($proy_id){ /// aprobados Institucional
        $sql = 'select *
                from lista_poa_pinversion_nacional('.$this->gestion.')
                where proy_id='.$proy_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- LISTA PROYECTOS DE INVERSION POR REGIONAL --------*/
    public function list_proy_inversion_regional($dep_id){ /// aprobados
        $sql = 'select *
                from lista_poa_pinversion_regional('.$dep_id.','.$this->gestion.')
                order by dep_id, dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- IMAGEN DEL PROYECTO (Ficha Tecnica) --------*/
    public function get_img_ficha_tecnica($proy_id){ /// get imagen ficha tecnica
        $sql = '
            select *
            from imagenes_proy_inversion
            where proy_id='.$proy_id.' and tp=\'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-------- GALERIA DE IMAGENES (Ficha Tecnica) --------*/
    public function lista_galeria_pinversion($proy_id){ /// get imagen ficha tecnica
        $sql = '
            select *
            from imagenes_proy_inversion
            where proy_id='.$proy_id.'
            order by img_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

/// CONSOLIDADO DE TEMPORALIDAD PROG Y EJEC DE FORMULARIO 4 POR UNIDAD / PROYECTO
    /*-- temporalidad prog form4 Unidad --*/
    public function temporalidad_prog_form4_unidad($aper_id){ /// 
        $sql = '
            select *
            from v_consolidado_temp_prog_form4_unidad
            where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- temporalidad ejec form4 Unidad --*/
    public function temporalidad_ejec_form4_unidad($aper_id){ /// 
        $sql = '
            select *
            from v_consolidado_temp_ejec_form4_unidad
            where aper_id='.$aper_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////------ por distrital
    /*-- temporalidad prog form4 DISTRITAL --*/
    public function temporalidad_prog_form4_distrital($dist_id){ /// 
        $sql = '
            select 
            poa.dist_id,
            SUM(form4.prog_mes1) prog_mes1,
            SUM(form4.prog_mes2) prog_mes2,
            SUM(form4.prog_mes3) prog_mes3,
            SUM(form4.prog_mes4) prog_mes4,
            SUM(form4.prog_mes5) prog_mes5,
            SUM(form4.prog_mes6) prog_mes6,
            SUM(form4.prog_mes7) prog_mes7,
            SUM(form4.prog_mes8) prog_mes8,
            SUM(form4.prog_mes9) prog_mes9,
            SUM(form4.prog_mes10) prog_mes10,
            SUM(form4.prog_mes11) prog_mes11,
            SUM(form4.prog_mes12) prog_mes12
            from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') poa
            Inner Join v_consolidado_temp_prog_form4_unidad as form4 On form4.aper_id=poa.aper_id
            group by poa.dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- temporalidad ejec form4 DISTRITAL --*/
    public function temporalidad_ejec_form4_distrital($dist_id){ /// 
        $sql = '
            select 
            poa.dist_id,
            SUM(form4.ejec_mes1) ejec_mes1,
            SUM(form4.ejec_mes2) ejec_mes2,
            SUM(form4.ejec_mes3) ejec_mes3,
            SUM(form4.ejec_mes4) ejec_mes4,
            SUM(form4.ejec_mes5) ejec_mes5,
            SUM(form4.ejec_mes6) ejec_mes6,
            SUM(form4.ejec_mes7) ejec_mes7,
            SUM(form4.ejec_mes8) ejec_mes8,
            SUM(form4.ejec_mes9) ejec_mes9,
            SUM(form4.ejec_mes10) ejec_mes10,
            SUM(form4.ejec_mes11) ejec_mes11,
            SUM(form4.ejec_mes12) ejec_mes12
            from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') poa
            Inner Join v_consolidado_temp_ejec_form4_unidad as form4 On form4.aper_id=poa.aper_id
            group by poa.dist_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    ////------ por Regional
    /*-- temporalidad prog form4 REGIONAL --*/
    public function temporalidad_prog_form4_regional($dep_id){ /// 
        $sql = '
            select 
            poa.dep_id,
            SUM(form4.prog_mes1) prog_mes1,
            SUM(form4.prog_mes2) prog_mes2,
            SUM(form4.prog_mes3) prog_mes3,
            SUM(form4.prog_mes4) prog_mes4,
            SUM(form4.prog_mes5) prog_mes5,
            SUM(form4.prog_mes6) prog_mes6,
            SUM(form4.prog_mes7) prog_mes7,
            SUM(form4.prog_mes8) prog_mes8,
            SUM(form4.prog_mes9) prog_mes9,
            SUM(form4.prog_mes10) prog_mes10,
            SUM(form4.prog_mes11) prog_mes11,
            SUM(form4.prog_mes12) prog_mes12
            from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') poa
            Inner Join v_consolidado_temp_prog_form4_unidad as form4 On form4.aper_id=poa.aper_id
            group by poa.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- temporalidad ejec form4 REGIONAL --*/
    public function temporalidad_ejec_form4_regional($dep_id){ /// 
        $sql = '
            select 
            poa.dep_id,
            SUM(form4.ejec_mes1) ejec_mes1,
            SUM(form4.ejec_mes2) ejec_mes2,
            SUM(form4.ejec_mes3) ejec_mes3,
            SUM(form4.ejec_mes4) ejec_mes4,
            SUM(form4.ejec_mes5) ejec_mes5,
            SUM(form4.ejec_mes6) ejec_mes6,
            SUM(form4.ejec_mes7) ejec_mes7,
            SUM(form4.ejec_mes8) ejec_mes8,
            SUM(form4.ejec_mes9) ejec_mes9,
            SUM(form4.ejec_mes10) ejec_mes10,
            SUM(form4.ejec_mes11) ejec_mes11,
            SUM(form4.ejec_mes12) ejec_mes12
            from lista_poa_gastocorriente_regional('.$dep_id.','.$this->gestion.') poa
            Inner Join v_consolidado_temp_ejec_form4_unidad as form4 On form4.aper_id=poa.aper_id
            group by poa.dep_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    ////------ Institucional
    /*-- temporalidad prog form4 INSTITUCIONAL --*/
    public function temporalidad_prog_form4_institucional(){ /// 
        $sql = '
            select 
            SUM(form4.prog_mes1) prog_mes1,
            SUM(form4.prog_mes2) prog_mes2,
            SUM(form4.prog_mes3) prog_mes3,
            SUM(form4.prog_mes4) prog_mes4,
            SUM(form4.prog_mes5) prog_mes5,
            SUM(form4.prog_mes6) prog_mes6,
            SUM(form4.prog_mes7) prog_mes7,
            SUM(form4.prog_mes8) prog_mes8,
            SUM(form4.prog_mes9) prog_mes9,
            SUM(form4.prog_mes10) prog_mes10,
            SUM(form4.prog_mes11) prog_mes11,
            SUM(form4.prog_mes12) prog_mes12
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join v_consolidado_temp_prog_form4_unidad as form4 On form4.aper_id=poa.aper_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /*-- temporalidad ejec form4 INSTITUCIONAL --*/
    public function temporalidad_ejec_form4_institucional(){ /// 
        $sql = '
            select 
            SUM(form4.ejec_mes1) ejec_mes1,
            SUM(form4.ejec_mes2) ejec_mes2,
            SUM(form4.ejec_mes3) ejec_mes3,
            SUM(form4.ejec_mes4) ejec_mes4,
            SUM(form4.ejec_mes5) ejec_mes5,
            SUM(form4.ejec_mes6) ejec_mes6,
            SUM(form4.ejec_mes7) ejec_mes7,
            SUM(form4.ejec_mes8) ejec_mes8,
            SUM(form4.ejec_mes9) ejec_mes9,
            SUM(form4.ejec_mes10) ejec_mes10,
            SUM(form4.ejec_mes11) ejec_mes11,
            SUM(form4.ejec_mes12) ejec_mes12
            from lista_poa_gastocorriente_nacional('.$this->gestion.') poa
            Inner Join v_consolidado_temp_ejec_form4_unidad as form4 On form4.aper_id=poa.aper_id';
        $query = $this->db->query($sql);
        return $query->result_array();
    }



    /////// ===== 2023
    /*-- Lista de Programas Bolsa por Distrital --*/
    public function lista_programas_bosas_distrital($dist_id){ /// 
        $sql = '
           select poa.*, p.por_id
                from lista_poa_gastocorriente_distrital('.$dist_id.','.$this->gestion.') poa
                Inner Join _proyectos as p On p.proy_id=poa.proy_id
                where p.por_id=\'1\'
                order by poa.prog asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


        /////// ===== 2023
    /*-- Lista de Programas Bolsa por Distrital --*/
    public function list_pi(){ /// 
        $sql = '
            select *
            from lista_poa_pinversion_nacional('.$this->gestion.') pi
            Inner Join vista_temporalidad_form5_unidad as temp On temp.aper_id=pi.aper_id 
            order by pi.dep_id,pi.dist_id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}