<?php

class model_rol extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function inserta_opcion_rol($opcion_rol,$r_id)
    {
        if($opcion_rol != null){
            // $i = 1;
            foreach($opcion_rol as $opcion){
                $datos=array(
                    'o_id'=>$opcion,
                    'r_id'=>$r_id
                );
                // echo $i.'.- ('.$datos['o_id'].','.$datos['r_id'].')<br>';
                $this->db->INSERT('opcion_rol',$datos);
                // $i++;
            };
        }
    }

    public function del_op_rol($r_id,$opciones1,$op1,$opciones2,$op2,$opciones3,$op3,$opciones4,$op4,$opciones7,$op7,$opciones9,$op9)
    {
        $sql = 'DELETE FROM opcion_rol WHERE r_id='.$r_id.'';
        $query = $this->db->query($sql);
        //Para Menu 1
        $matriz[0][0] = $opciones1;
        $matriz[0][1] = $op1;
        //Para Menu 2
        $matriz[1][0] = $opciones2;
        $matriz[1][1] = $op2;
        //Para Menu 3
        $matriz[2][0] = $opciones3;
        $matriz[2][1] = $op3;
        //Para Menu 4
        $matriz[3][0] = $opciones4;
        $matriz[3][1] = $op4;
        //Para Menu 5
        $matriz[4][0] = $opciones7;
        $matriz[4][1] = $op7;
        //Para Menu 6
        $matriz[5][0] = $opciones9;
        $matriz[5][1] = $op9;
        //Matriz de 6x2 (Recorrer para Insertar Opciones)
        for ($k=0; $k < 6 ; $k++) {
            // echo 'Opciones = '.count($matriz[$k][0]).'  Op = '.count($matriz[$k][1]).'<br>';
            $opciones = $matriz[$k][0];
            $op = $matriz[$k][1];
            if($opciones != null){
                // echo "Insertado de Menus Padres<br>";
                $this->inserta_opcion_rol($opciones,$r_id);
            }
            if($op != null){
                // echo "Insertado de Menus Hijos<br>";
                $this->inserta_opcion_rol($op,$r_id);
            }
        }
        ///////////////insertar sesion y ayuda///////////
        $ses = 30060;
        $cambiar_ges = 30061;
        $cambiar_contr = 30062;
        $cerrar = 30063;
        $rol = 50071;
        if($r_id == 1) {
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$ses.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cambiar_ges.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cambiar_contr.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cerrar.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$rol.','.$r_id.')';
            $query = $this->db->query($sql);
        } else {
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$ses.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cambiar_ges.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cambiar_contr.','.$r_id.')';
            $query = $this->db->query($sql);
            $sql = 'INSERT INTO public.opcion_rol(o_id, r_id) VALUES ('.$cerrar.','.$r_id.')';
            $query = $this->db->query($sql);
        }
        /////////////////end insertar//////////////
        redirect('mantenimiento/roles/roles_menu');
    }
}
?>