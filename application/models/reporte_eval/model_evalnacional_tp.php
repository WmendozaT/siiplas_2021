<?php
class Model_evalnacional_tp extends CI_Model{
    var $gestion;
    public function __construct(){
        $this->load->database();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->gestion = $this->session->userData('gestion');
        $this->fun_id = $this->session->userData('fun_id');
        $this->rol = $this->session->userData('rol_id');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
    }

    /*---------------------- Tipo de Proyecto 2019 --------------------------*/
/*    public function get_tp_proyecto($tp_id){
        $sql = 'select *
                from _tipoproyecto
                where tp_id='.$tp_id.' and tp_estado=\'1\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
*/
    /*---------------------- Lista de Acciones Operativas --------------------------*/
/*    public function list_acciones_tp($prog,$tp_id){
        $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                from _proyectos as p
                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                               from _proyectofuncionario pf
                               Inner Join funcionario as f On pf.fun_id=f.fun_id
                               Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                               Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                            where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------------------- Get Programa Padre ------------------------------------*/
/*    public function programa($prog){
        $sql = 'select *
                from aperturaprogramatica
                where aper_programa=\''.$prog.'\' and aper_proyecto=\'0000\' and aper_actividad=\'000\' and aper_gestion='.$this->gestion.' and aper_estado!=\'3\'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------------------- Proyectos ------------------------------------*/
/*    public function proyecto($prog,$tp_id){
        $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                from _proyectos as p
                    Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                    Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                            Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                            Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                               from _proyectofuncionario pf
                               Inner Join funcionario as f On pf.fun_id=f.fun_id
                               Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                               Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                            where pf.pfun_tp=1) as fu On fu.proy_id=p.proy_id
                            where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                            ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*------------------------- LISTA DE OPERACIONES PARA LA EVALUACION a borrar-------------------------------*/
/*    public function proyecto2($prog,$tp_id){
        $dep=$this->model_proyecto->dep_dist($this->dist);
        if($this->adm==1){
            if($this->rol==1){
            $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                    from _proyectos as p
                        Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                        Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                   from _proyectofuncionario pf
                                   Inner Join funcionario as f On pf.fun_id=f.fun_id
                                   Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                   Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
            }
            else{
                $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                        from _proyectos as p
                            Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                            Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                    Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                    Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                       from _proyectofuncionario pf
                                       Inner Join funcionario as f On pf.fun_id=f.fun_id
                                       Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                       Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                    where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                    where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and fu.fun_id='.$this->fun_id.' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                    ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
            }
        }
        elseif($this->adm==2){
            if($this->rol==1){
                if($this->dist_tp==1){
                    $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                            from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                        Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                           from _proyectofuncionario pf
                                           Inner Join funcionario as f On pf.fun_id=f.fun_id
                                           Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                           Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and dep_id='.$dep[0]['dep_id'].' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                }
                elseif($this->dist_tp==0){
                    $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                            from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                        Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                           from _proyectofuncionario pf
                                           Inner Join funcionario as f On pf.fun_id=f.fun_id
                                           Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                           Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and dep_id='.$dep[0]['dep_id'].' and dist_id='.$this->dist.' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                }
                else{
                    $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                            from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                        Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                           from _proyectofuncionario pf
                                           Inner Join funcionario as f On pf.fun_id=f.fun_id
                                           Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                           Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and dep_id='.$dep[0]['dep_id'].' and dist_id='.$this->dist.' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
                }

            }
            else{
                $sql = 'select tap.*,p.*,tp.*,fu.*,pf.*
                            from _proyectos as p
                                Inner Join _tipoproyecto as tp On p.tp_id=tp.tp_id
                                Inner Join _proyectofaseetapacomponente as pf On pf.proy_id=p.proy_id
                                        Inner Join (select apy.*,apg.* from aperturaprogramatica as apg, aperturaproyectos as apy where apy.aper_id=apg.aper_id) as tap On p.proy_id=tap.proy_id
                                        Inner Join (select pf.proy_id,f.fun_id,f.fun_nombre,f.fun_paterno,f.fun_materno,u.uni_unidad as ue,ur.uni_unidad as ur
                                           from _proyectofuncionario pf
                                           Inner Join funcionario as f On pf.fun_id=f.fun_id
                                           Inner Join unidadorganizacional as u On pf.uni_ejec=u.uni_id
                                           Inner Join unidadorganizacional as ur On pf.uni_resp=ur.uni_id
                                        where pf.pfun_tp=\'1\') as fu On fu.proy_id=p.proy_id
                                        where tap.aper_programa=\''.$prog.'\' and p.estado!=\'3\' and tap.aper_gestion='.$this->gestion.' and proy_estado=\'4\' and fu.fun_id='.$this->fun_id .' and dep_id='.$dep[0]['dep_id'].' and pf.pfec_estado=\'1\' and p.tp_id='.$tp_id.'
                                        ORDER BY tap.aper_proyecto,tap.aper_actividad  asc';
            }
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------------------- Get Componente ------------------------------------*/
/*    public function vcomponente($com_id){
        $sql = 'select *
                from vista_componentes_dictamen
                where com_id='.$com_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------------------- Get Producto ------------------------------------*/
/*    public function vproducto($prod_id){
        $sql = 'select *
                from vista_producto
                where prod_id='.$prod_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/

    /*--------------------------------- Get Actividad ------------------------------------*/
/*    public function vactividad($act_id){
        $sql = 'select *
                from vista_actividad
                where act_id='.$act_id.'';
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
}
