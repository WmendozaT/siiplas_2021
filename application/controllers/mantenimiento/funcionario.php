<?php
class Funcionario extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->library('encrypt');
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('mantenimiento/model_funcionario');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->fun_id = $this->session->userData('fun_id');
        }else{
            redirect('/','refresh');
        }
    }
    
    /* --- GET LISTA ACTIVIDADES 2021 (SEGUIMIENTO POA )---*/
    public function get_unidades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dist_id = $this->security->xss_clean($post['dist_id']);
        
        $unidades=$this->model_funcionario->lista_unidad_pinversion_distrital($dist_id);
        $salida='';
        $salida.= "<option value='0'>SELECCIONE ....</option>";
        foreach ($unidades as $row){
            $salida.= "<option value='".$row['proy_id']."'>".$row['tipo']." ".strtoupper ($row['actividad'])." ".$row['abrev']."</option>";
        }
        
        $result = array(
          'respuesta' => 'correcto',
          'lista_actividad' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    public function roles_html($id){
        $html_roles_fun = '';
        $lista_rol = $this->model_funcionario->get_rol($id);
        foreach ($lista_rol as $fila) {
            $html_roles_fun .= '
                <li>'.$fila['r_nombre'].'</li>
            ';
        }
        return $html_roles_fun;
    }

    /*----------- Tipo de Responsable -------------*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='RESPONSABLE NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }

    /*----- Lista de Responsables ------*/
    public function list_usuarios(){
        if($this->adm==1){
            $data['menu']=$this->menu(9);
            $data['resp']=$this->session->userdata('funcionario');
            $data['res_dep']=$this->tp_resp();
            $data['administradores']=$this->list_funcionarios(0); /// Administradores Nacionales, Regionales, Diatritales
            $data['administradores_segpoa']=$this->list_funcionarios_seguimiento(1); /// Seguimiento POA
            $data['list_responsables']=$this->get_responsables_poa();
            $this->load->view('admin/mantenimiento/funcionario/vlist_fun', $data);
        
            /*--------- listado de unidades responsables de seguimiento poa -------*/
            /*$funcionarios=$this->model_funcionario->get_funcionarios_seguimiento_institucional($this->gestion);
            $tabla='';
            if(count($funcionarios)!=0){
                $tabla.='
               
                <table border=0.9 style="width:100%;" align=center>
                    <thead>
                        <tr style="font-size: 8px;" bgcolor="#d8d8d8">
                            <th style="width:2%; text-align: center;height:20px;">SERV ID</th>
                            <th style="width:20%; text-align: center;">ACTIVIDAD</th>
                            <th style="width:26%; text-align: center;">SUBACTIVIDAD</th>
                            <th style="width:13%; text-align: center;">RESPONSABLE</th>
                            <th style="width:5%; text-align: center;">ID</th>
                            <th style="width:5%; text-align: center;">COM ID '.$this->gestion.'</th>
                            <th style="width:5%; text-align: center;">COM ID 2024</th>
                            <th style="width:13%; text-align: center;">USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    foreach($funcionarios  as $row){
                        $get_serv=$this->model_componente->get_servicio_siguiente_gestion($row['serv_id'],$row['dist_id'],2024);
                        $nro++;
                     
                        $rol=$this->model_funcionario->verif_rol($row['id'],1);
                        $tabla .='<tr style="font-size: 7px;">';
                            $tabla .='<td style="width:2%;height:15px;" align="center">'.$row['serv_id'].'</td>';
                            $tabla .='<td style="width:20%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
                            $tabla .='<td style="width:26%;">'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
                            $tabla .='<td style="width:13%;">'.$row['fun_nombre'].' '.$row['fun_paterno'].'</td>';
                            $tabla .='<td style="width:13%;"><b>'.$row['id'].'</b></td>';
                            $tabla .='<td style="width:13%;"><b>'.$row['cm_id'].'</b></td>';
                            if(count($get_serv)!=0){
                                $tabla .='<td style="width:13%;">'.$get_serv[0]['com_id'].'</td>';
                            }
                            else{
                                $tabla .='<td style="width:13%;"></td>';
                            }
                            
                            $tabla .='<td style="width:13%;"><b>'.$row['fun_usuario'].'</b></td>';
                            
                        $tabla .='</tr>';
                    }

                $tabla.='</tbody>
                </table><br>';
            }
             echo $tabla;*/

            /*---------------*/

            /*------- update de usuarios seguimiento poa a una nueva gestion --------*/
            /*$funcionarios=$this->model_funcionario->get_funcionarios_seguimiento_institucional($this->gestion);
            if(count($funcionarios)!=0){
                foreach($funcionarios  as $row){
                    $get_serv=$this->model_componente->get_servicio_siguiente_gestion($row['serv_id'],$row['dist_id'],2025);
                    if(count($get_serv)!=0){
                        $update_resp = array(
                        'cm_id' => $get_serv[0]['com_id']
                        );
                        $this->db->where('fun_id', $row['id']);
                        $this->db->update('funcionario', $update_resp);
                    }
                }
            }*/
            /*---------------*/
        }
        else{
            redirect('admin/dashboard');
        }
    }

    public function new_funcionario(){
        $data['menu']=$this->menu(9);

        $data['list_dep']=$this->model_proyecto->list_departamentos(); /// lista de Departamentos
        $data['uni_org']=$this->model_funcionario->get_uni_o();
        $data['listas_rol'] =$this->model_funcionario->get_add_rol();
        $this->load->view('admin/mantenimiento/funcionario/vnew_fun', $data);
    }

    /*------------- FORMULARIO DE MODIFICACIONES --------------*/
    public function update_funcionario($fun_id){
        $data['menu']=$this->menu(9);
        $data['fun']=$this->model_funcionario->get_funcionario($fun_id); /// Get Usuario
        if(count($data['fun'])!=0){
            $data['list_dep']=$this->model_proyecto->list_departamentos(); /// lista de Departamentos
            $data['uni_org']=$this->model_funcionario->get_uni_o();
            $data['listas_rol'] =$this->model_funcionario->get_add_rol();
            $data['list_dist']=$this->model_proyecto->list_distritales($data['fun'][0]['dep_id']);
            $data['roles']=count($this->model_funcionario->roles_funcionario($fun_id));

            $data['edit_pass'] = $this->encrypt->decode($data['fun'][0]['fun_password']);
            $data['rol']=$this->model_funcionario->get_rol($fun_id);
            $data['componente']=$this->model_componente->get_componente($data['fun'][0]['cm_id'],$this->gestion);
           // echo $data['fun'][0]['cm_id'];
            $data['display']='style="display:none;"';
            $data['actividad']='';
            $data['subactividad']='';
            if($data['rol'][0]['r_id']==9){ // SEGUIMIENTO POA
                $data['display']='style="display:block;"';
                //echo $data['fun'][0]['fun_dist'].'---'.$data['componente'][0]['proy_id'];
                $data['actividad']=$this->list_actividades($data['fun'][0]['fun_dist'],$data['componente'][0]['proy_id']); /// Lista de Actividades
                $data['subactividad']=$this->list_subactividades($data['componente']); /// Lista de Subactividades
            }

            $this->load->view('admin/mantenimiento/funcionario/update_fun', $data);
        }
        else{
            redirect('admin/dashboard');
        }
    }

    
    /*-- LISTA ACTIVIDADES --*/
    public function list_actividades($dist_id,$proy_id){
        $unidades=$this->model_componente->lista_unidades($dist_id);

        $tabla='';
        $tabla.='
                  <option value="">Seleccione...</option>';
        foreach($unidades as $row){
            if($row['proy_id']==$proy_id){
                $tabla.='<option value='.$row['proy_id'].' selected>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</option>';
            }
            else{
                $tabla.='<option value='.$row['proy_id'].'>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</option>';    
            }
        }
        
        return $tabla;
    }



    /*-- REPORTE LISTA DE RESPONSABLES POA --*/
    public function get_responsables_poa(){
        $tabla='';
        $regionales=$this->model_proyecto->list_departamentos();

      $tabla.='<div class="btn-group">
                  <a class="btn btn-default" style="width: 90%;" >FORMULARIO SEGUIMIENTO POA</a>
                  <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li>
                        <a href="'.site_url("").'/mnt/rep_list_usu/0" target="_blank">RESPONSABLES POA</a>
                    </li>';

                    foreach($regionales as $row){
                        if($row['dep_id']!=0){
                            $tabla.='
                            <li>
                                <a href="'.site_url("").'/mnt/rep_list_usu/'.$row['dep_id'].'" target="_blank">RESP. SEG. POA '.strtoupper($row['dep_departamento']).'</a>
                            </li>';
                        }
                    }
                  $tabla.='
                  </ul>
                </div>';

      return $tabla;
    }




    /*-- LISTA SUBACTIVIDADES --*/
    public function list_subactividades($componente){
        $procesos=$this->model_componente->lista_subactividad($componente[0]['proy_id']);
        $tabla='';
        $tabla.='
                  <option value="">Seleccione...</option>';
        foreach($procesos as $row){
            if($row['com_id']==$componente[0]['com_id']){
                $tabla.='<option value='.$row['com_id'].' selected>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';
            }
            else{
                $tabla.='<option value='.$row['com_id'].'>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</option>';    
            } 
        }

        return $tabla;
    }


    /*----------- LISTA DE RESPONSABLES -------------------*/
    public function list_funcionarios($tp_usuario){
        $funcionarios=$this->model_funcionario->get_funcionarios($tp_usuario); /// 0 : Administradores, 1 : Seguimiento POA
        $tabla ='';

        $nro=0;
        foreach($funcionarios  as $row){
            $nro++;
            $rol=$this->model_funcionario->verif_rol($row['id'],1);
            $tabla .='<tr>';
                $tabla .='<td title="'.$row['id'].'">'.$nro.'</td>';
                $tabla .='<td>';
                    $tabla .= '<center><a href="'.site_url("admin").'/funcionario/update_fun/'.$row['id'].'" title="MODIFICAR DATOS DEL RESPONSABLE"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                    if(count($rol)==0){
                        $tabla .= '<center><a href="'.site_url("admin").'/funcionario/delete_fun/'.$row['id'].'" title="ELIMINAR DATOS DEL RESPONSABLE" onclick="return confirmar()"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                    }
                $tabla .='</td>';
                $tabla .='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                $tabla .='<td>'.$row['uni_unidad'].'</td>';
                $tabla .='<td>'.$row['fun_usuario'].'</td>';
                $tabla .='<td>'.$row['adm'].'</td>';
                $tabla .='<td>'.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='
                        <td>
                            <ul style="text-align:left; padding-left: 1;list-style-type:square; margin:2px;">
                                '.$this->roles_html($row['id']).'
                            </ul>
                        </td>';
            $tabla .='</tr>';
        }

        return $tabla;
    }

    /*----------- LISTA DE RESPONSABLES PARA EL SEGUIMIENTO POA -------------------*/
    public function list_funcionarios_seguimiento($tp_usuario){
        $funcionarios=$this->model_funcionario->get_funcionarios($tp_usuario); /// 0 : Administradores, 1 : Seguimiento POA
        $tabla ='';

        $nro=0;
        foreach($funcionarios  as $row){
            $subactividad=$this->model_componente->get_componente($row['cm_id'],$this->gestion);
            $nro++;
            $rol=$this->model_funcionario->verif_rol($row['id'],1);
            $tabla .='<tr>';
                $tabla .='<td title="'.$row['id'].'">'.$nro.'</td>';
                $tabla .='<td>';
                    $tabla .= '<center><a href="'.site_url("admin").'/funcionario/update_fun/'.$row['id'].'" title="MODIFICAR DATOS DEL RESPONSABLE"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                    if(count($rol)==0){
                        $tabla .= '<center><a href="'.site_url("admin").'/funcionario/delete_fun/'.$row['id'].'" title="ELIMINAR DATOS DEL RESPONSABLE" onclick="return confirmar()"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></center>';
                    }
                $tabla .='</td>';
                $tabla .='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                $tabla .='<td>'.$row['uni_unidad'].'</td>';
                $tabla .='<td>'.$row['fun_usuario'].'</td>';
                $tabla .='<td>'.$row['adm'].'</td>';
                $tabla .='<td>'.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='
                        <td>
                            <ul style="text-align:left; padding-left: 1;list-style-type:square; margin:2px;">
                                '.$this->roles_html($row['id']).'
                            </ul>
                        </td>';
                $tabla .='<td><b>'.$row['cm_id'].'->';
                    if(count($subactividad)!=0){
                        $tabla.=$subactividad[0]['serv_cod'].' '.$subactividad[0]['tipo_subactividad'].' '.$subactividad[0]['serv_descripcion'].' - '.$row['abrev'].'';
                    }
                $tabla.='</b></td>';
            $tabla .='</tr>';
        }

        return $tabla;
    }

    /*--- REPORTE FUNCIONARIOS Y SEGUIMIENTO POA ---*/
    public function reporte_list_usuarios($dep_id){
        /// dep_id 0 : Responsables POA
        /// dep_id 1,2,3,4,5,6,7,8,9 : Seguimiento POA
        $data['mes'] = $this->mes_nombre();
        $tabla='';
        $data['titulo']='';
        if($dep_id==0){ /// Responsables POA
            $funcionarios=$this->model_funcionario->get_funcionarios(0);
            $tabla='';
            $tabla.='
                <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                    <thead>
                     <tr style="font-size: 8px;" bgcolor="#d8d8d8">
                        <th style="width:1%;height:20px;">#</th>
                        <th style="width:25%;">NOMBRE COMPLETO</th> 
                        <th style="width:10%;">USUARIO</th> 
                        <th style="width:10%;">UNIDAD DEPENDIENTE</th>
                        <th style="width:10%;">DISTRITAL</th>   
                    </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    foreach($funcionarios  as $row){
                        if($row['fun_id']!=399){
                            $nro++;
                            $tabla.='
                                <tr style="font-size: 7px;">
                                    <td style="width:2%; text-align: center;height:15px;">'.$nro.'</td>
                                    <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                                    <td>'.$row['fun_usuario'].'</td>
                                    <td>'.$row['uni_unidad'].'</td>
                                    <td>';
                                    if($row['fun_adm']==1){
                                        $tabla.='ADMINISTRADOR NACIONAL';
                                    }
                                    else{
                                        $tabla.=strtoupper($row['dist_distrital']);   
                                    }
                                    $tabla.='
                                    </td>
                                </tr>';
                        }
                    }
                $tabla.='
                    </tbody>
                </table>';
        }
        else{
            $dep=$this->model_proyecto->get_departamento($dep_id);
            $data['titulo']=' REGIONAL - '.strtoupper($dep[0]['dep_departamento']);
            $funcionarios=$this->model_funcionario->get_funcionarios_seguimiento_regional($dep_id);
         
            if(count($funcionarios)!=0){
                $tabla.='
                <div style="height:25px;"><b>CLAVE DE ACCESO A UNIDADES ADMINISTRATIVAS</b></div>
                <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                    <thead>
                        <tr style="font-size: 8px;" bgcolor="#d8d8d8">
                            <th style="width:2%; text-align: center;height:20px;">#</th>
                            <th style="width:20%; text-align: center;">ACTIVIDAD</th>
                            <th style="width:26%; text-align: center;">SUBACTIVIDAD</th>
                            <th style="width:13%; text-align: center;">RESPONSABLE</th>
                            <th style="width:13%; text-align: center;">USUARIO</th>
                            <th style="width:13%; text-align: center;">CONTRASEÑA</th>
                            <th style="width:10%; text-align: center;">ROL ASIGNADO</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    foreach($funcionarios  as $row){
                        $nro++;
                     
                        $rol=$this->model_funcionario->verif_rol($row['id'],1);
                        $tabla .='<tr style="font-size: 7px;">';
                            $tabla .='<td style="width:2%;height:15px;" align="center">'.$nro.'</td>';
                            $tabla .='<td style="width:20%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
                            $tabla .='<td style="width:26%;">'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
                            $tabla .='<td style="width:13%;">'.$row['fun_nombre'].' '.$row['fun_paterno'].'</td>';
                            $tabla .='<td style="width:13%;"><b>'.$row['fun_usuario'].'</b></td>';
                            $tabla .='<td style="width:13%;"><b>'.$this->encrypt->decode($row['fun_password']).'</b></td>';
                            $tabla .='
                                    <td style="width:10%;">';
                                    $lista_rol = $this->model_funcionario->get_rol($row['id']);
                                    foreach ($lista_rol as $fila) {
                                        $tabla.='- '.$fila['r_nombre'].'<br>';
                                    }
                                       
                            $tabla.='</td>';
                        $tabla .='</tr>';
                    }

                $tabla.='</tbody>
                </table><br>';
            }

            $unidades=$this->model_estructura_org->get_unidades_regionales($dep_id); /// Lista de Establecimientos de salud
            if(count($unidades)!=0){
                 $tabla.='<div style="height:25px;"><b>CLAVE DE ACCESO A ESTABLECIMIENTOS DE SALUD</b></div>';
                      $tabla.=
                      '<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                        <thead>
                          <tr style="font-size: 8px;" bgcolor="#d8d8d8">
                            <th style="width:2%; text-align: center;height:20px;">#</th>
                            <th style="width:5%; text-align: center;">COD.</th>
                            <th style="width:25%; text-align: center;">ESTABLECIMIENTO</th>
                            <th style="width:15%; text-align: center;">DISTRITAL</th>
                            <th style="width:15%; text-align: center;">TIPO DE UBICACI&Oacute;N</th>
                            <th style="width:15%; text-align: center;">TIPO DE ESTABLECIMIENTO</th>
                            <th style="width:10%; text-align: center;">USUARIO</th>
                            <th style="width:10%; text-align: center;">CONTRASEÑA</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                          foreach ($unidades as $row){
                          $nro++;
                          $tabla.=
                          ' <tr style="font-size: 7px;">
                                <td style="width:2%;height:15px;">'.$nro.'</td>
                                <td style="width:5%;height:10px;">'.$row['act_cod'].'</td>
                                <td style="width:25%;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>
                                <td style="width:15%;">'.strtoupper($row['dist_distrital']).'</td>
                                <td style="width:15%;">'.$row['ubicacion'].'</td>
                                <td style="width:15%;">'.$row['establecimiento'].'</td>
                                <td style="width:10%;">'.$row['dato_ingreso'].'</td>
                                <td style="width:10%;">'.$row['clave'].'</td>
                            </tr>';
                        }
                        $tabla.='
                        </tbody>
                      </table>';
                }
            }
           

        $data['lista']=$tabla;
        $this->load->view('admin/mantenimiento/funcionario/reporte_responsables_poa', $data); 
    }

    /*--- NOMBRE MES ---*/
    function mes_nombre(){
        $mes[1] = 'ENE.';
        $mes[2] = 'FEB.';
        $mes[3] = 'MAR.';
        $mes[4] = 'ABR.';
        $mes[5] = 'MAY.';
        $mes[6] = 'JUN.';
        $mes[7] = 'JUL.';
        $mes[8] = 'AGOS.';
        $mes[9] = 'SEPT.';
        $mes[10] = 'OCT.';
        $mes[11] = 'NOV.';
        $mes[12] = 'DIC.';
        return $mes;
    }

    /*--------- VALIDA USUARIO --------*/
    public function add_funcionario(){
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
            $this->form_validation->set_rules('ap', 'Apellido Paterno', 'required|trim');
            $this->form_validation->set_rules('usuario', 'Usuario', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

            if ($this->form_validation->run()){
                if($this->input->post('adm')==1){
                    $dist=0;
                }
                elseif($this->input->post('adm')==2){
                    $dist=$this->input->post('dist_id');
                }

                $data_to_store = array( 
                    'uni_id' => 0,
                    'car_id' => 0,
                    'fun_nombre' => strtoupper($this->input->post('nombre')),
                    'fun_paterno' => strtoupper($this->input->post('ap')),
                    'fun_materno' => strtoupper($this->input->post('am')),
                    'fun_cargo' => strtoupper($this->input->post('crgo')),
                    'fun_ci' => $this->input->post('ci'),
                    'fun_domicilio' => strtoupper($this->input->post('domicilio')),
                    'fun_telefono' => $this->input->post('fono'),
                    'fun_usuario' => $this->input->post('usuario'),
                    'fun_password' => $this->encrypt->encode($this->input->post('password')),
                    'fun_adm' => $this->input->post('adm'),
                    'fun_dist' => $dist,
                    'cm_id' => $this->input->post('componente'),
                );
                $this->db->insert('funcionario', $this->security->xss_clean($data_to_store));
                $fun_id=$this->db->insert_id();

                $data_to_store2 = array( 
                    'fun_id' => $fun_id,
                    'r_id' => strtoupper($this->input->post('rol_id')),
                );
                $this->db->insert('fun_rol', $data_to_store2);

              
                $this->session->set_flashdata('success','LOS DATOS DEL RESPONSABLE SE REGISTRARON CORRECTAMENTE');
                redirect('admin/mnt/list_usu');

            }
            else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR DATOS, VERIFIQUE INFORMACION');
            redirect('admin/funcionario/new_fun');
            }
        }
        else{
            $this->session->set_flashdata('danger','ERROR EN EL SERVIDOR, Contactese con el Administrador');
            redirect('admin/funcionario/new_fun');
        }
    }

    /*--------------------- VALIDA UPDATE USUARIO -------------------------*/
    public function add_update_funcionario(){
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
            $this->form_validation->set_rules('ap', 'Apellido Paterno', 'required|trim');
            $this->form_validation->set_rules('usuario', 'Usuario', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

            if ($this->form_validation->run()) {
                if($this->input->post('adm')==1){
                    $dist=0;
                }
                elseif($this->input->post('adm')==2){
                    $dist=$this->input->post('dist_id');
                }
         
                $update_fun = array(
                    'uni_id' => $this->input->post('uni_id'),
                    'car_id' => 0,
                    'fun_nombre' => strtoupper($this->input->post('nombre')),
                    'fun_paterno' => strtoupper($this->input->post('ap')),
                    'fun_materno' => strtoupper($this->input->post('am')),
                    'fun_cargo' => strtoupper($this->input->post('crgo')),
                    'fun_ci' => $this->input->post('ci'),
                    'fun_domicilio' => strtoupper($this->input->post('domicilio')),
                    'fun_telefono' => $this->input->post('fono'),
                    'fun_usuario' => $this->input->post('usuario'),
                    'fun_password' => $this->encrypt->encode($this->input->post('password')),
                    'fun_adm' => $this->input->post('adm'),
                    'fun_dist' => $dist,
                    'cm_id' => $this->input->post('componente'),
                );
                $this->db->where('fun_id', $this->input->post('fun_id'));
                $this->db->update('funcionario', $this->security->xss_clean($update_fun));

                $this->model_funcionario->elimina_roles($this->input->post('fun_id'));
                
                $data_to_store2 = array( 
                    'fun_id' => $this->input->post('fun_id'),
                    'r_id' => strtoupper($this->input->post('rol_id')),
                );
                $this->db->insert('fun_rol', $data_to_store2);


                $this->session->set_flashdata('success','EL RESPONSABLE SE MODIFICO CORRECTAMENTE');
                redirect('admin/mnt/list_usu');
            }
            else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR DATOS, VERIFIQUE INFORMACION');
            redirect('admin/funcionario/new_fun');
            }
        }
        else{
            $this->session->set_flashdata('danger','ERROR EN EL SERVIDOR, Contactese con el Administrador');
            redirect('admin/funcionario/new_fun');
        }
    }


    public function verif_usuario(){
        if($this->input->is_ajax_request()){
            $post = $this->input->post();
            $user = $post['user'];
            $usuario=$this->model_funcionario->verificar_fun($user);
             if($usuario == 0){
             echo "true"; ///// no existe un CI registrado
             }
             else{
              echo "false"; //// existe el CI ya registrado
             } 
        }else{
            show_404();
        }
    }

    function verif_ci(){
        if($this->input->is_ajax_request()){
            $post = $this->input->post();
            $ci = $post['ci'];

            $variable= $this->model_funcionario->fun_ci($ci);
             if(count($variable)!=0){
             echo "false"; ///// Ya existe CI
             }
             else{
              echo "true"; //// No existe CI
             } 
        }else{
          show_404();
      }
    }

    function verif_user(){
        if($this->input->is_ajax_request()){
            $post = $this->input->post();
            $user = $post['user'];

            $variable= $this->model_funcionario->fun_usuario($user);
             if(count($variable)!=0){
             echo "false"; ///// Ya existe CI
             }
             else{
              echo "true"; //// No existe CI
             } 
        }else{
          show_404();
      }
    }

    public function add_funcionario2(){
           $fun_nombre      =strtoupper ($this->input->post('fun_nombre'));
           $fun_paterno     =strtoupper ( $this->input->post('fun_paterno'));
           $fun_materno     =strtoupper ( $this->input->post('fun_materno'));
           $fun_ci          =strtoupper ( $this->input->post('fun_ci'));
           $fun_telefono    =strtoupper ( $this->input->post('fun_telefono'));
           $fun_cargo       =strtoupper ( $this->input->post('fun_cargo'));
           $fun_domicilio   =strtoupper ( $this->input->post('fun_domicilio'));
           $fun_usuario     = strtoupper ($this->input->post('fun_usuario'));
           $fun_password    = $this->encrypt->encode($this->input->post('fun_password'));
           $uni_id          = strtoupper ($this->input->post('uni_id'));
           $car_id          = strtoupper ($this->input->post('car_id'));
           $roles           =strtoupper($this->input->post('rol[]'));
    /*  foreach($_POST['rol'] as $roles){
            echo $roles."<br>";
        };*/


        $usuario=$this->model_funcionario->verificar_fun($fun_usuario);
       
        if($usuario==0){
            $this->model_funcionario->add_fun($fun_nombre,$fun_paterno, $fun_materno,$fun_ci,$fun_telefono,$fun_cargo,
                                            $fun_domicilio,$fun_usuario,$fun_password,$uni_id,$car_id,$roles);

        }else{ 
            echo "<script>alert('usuario ya existente');
                </script>";
            redirect('admin/mnt/list_usu', 'refresh');
        }
    }


    public function del_fun($fun_id)
    {
        $fun=$this->model_funcionario->del_fun($fun_id);
        $this->session->set_flashdata('success','EL REGISTRO SE ELIMINO CORRECTAMENTE');
        redirect('admin/mnt/list_usu');
    }
    
    function nueva_contra(){
        $this->load->view('admin/mod_contrase');
    }

    function mod_cont(){
        $fun_id = $this->input->post('fun_id');
        $apassword = $this->input->post('apassword');
        $password = $this->input->post('password');
        $password = $this->encrypt->encode($password);
        $verifica = (($this->encrypt->decode($this->model_funcionario->verificar_password($fun_id))) == $apassword) ? true : false ;
        if($verifica){
            $this->model_funcionario->mod_password($fun_id,$password);
            echo "
                <script>
                    alert('Se Cambio la Contraseña Correctamente');
                </script>
            ";
            $this->vista();
        }
        else{
            echo "
                <script>
                    alert('La Contraseña Anterior No Coincide');
                </script>
            ";
            $this->nueva_contra();
        }
    }

    /*---------- MENU -----------*/
    function menu($mod){
            $enlaces=$this->menu_modelo->get_Modulos($mod);
            for($i=0;$i<count($enlaces);$i++)
            {
              $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
            }

            $tabla ='';
            for($i=0;$i<count($enlaces);$i++)
            {
                if(count($subenlaces[$enlaces[$i]['o_child']])>0)
                {
                    $tabla .='<li>';
                        $tabla .='<a href="#">';
                            $tabla .='<i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>';    
                            $tabla .='<ul>';    
                                foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
                                $tabla .='<li><a href="'.base_url($item['o_url']).'">'.$item['o_titulo'].'</a></li>';
                            }
                            $tabla .='</ul>';
                    $tabla .='</li>';
                }
            }

            return $tabla;
        }

        public function get_mes($mes_id)
        {
          $mes[1]='ENERO';
          $mes[2]='FEBRERO';
          $mes[3]='MARZO';
          $mes[4]='ABRIL';
          $mes[5]='MAYO';
          $mes[6]='JUNIO';
          $mes[7]='JULIO';
          $mes[8]='AGOSTO';
          $mes[9]='SEPTIEMBRE';
          $mes[10]='OCTUBRE';
          $mes[11]='NOVIEMBRE';
          $mes[12]='DICIEMBRE';

          $dias[1]='31';
          $dias[2]='28';
          $dias[3]='31';
          $dias[4]='30';
          $dias[5]='31';
          $dias[6]='30';
          $dias[7]='31';
          $dias[8]='31';
          $dias[9]='30';
          $dias[10]='31';
          $dias[11]='30';
          $dias[12]='31';

          $valor[1]=$mes[$mes_id];
          $valor[2]=$dias[$mes_id];

          return $valor;
        }
    }