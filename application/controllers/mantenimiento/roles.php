<?php
class roles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('mantenimiento/model_rol');
        $this->load->model('mantenimiento/model_funcionario');
        //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
    }
    public function html_titulo($nuevo, $o_id, $o_titulo,$n)
    {
        $name = '';
        $name = 'opciones'.$n;
        $html_titulo = '';
        if($nuevo == 0){
            $html_titulo.='<dt>&nbsp;<input name="'.$name.'[]"  type="checkbox" value="'.$o_id.'" ><label>&nbsp;'.$o_titulo.'</label></dt>';
        } else{
            $html_titulo.='<dt>&nbsp;<input name="'.$name.'[]"  type="checkbox" value="'.$o_id.'" checked><label>&nbsp;'.$o_titulo.'</label></dt>';
        }
        return $html_titulo;
    }
    public function contenido_checkbox($o_child, $r_id,$n)
    {
        $name = '';
        $name = 'op'.$n;
        $hijos = $this->menu_modelo->hijos($o_child, $r_id);
        $html_contenido = '';
        foreach ($hijos as $filas) {
            $html_contenido.='
                <div class="checkbox">';
            if($filas['nuevo1']==0){
                $html_contenido.='
                    <label>
                        <input name="'.$name.'[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                            '.$filas['o_titulo'].'
                    </label>';
            } else{
                $html_contenido.='
                    <label>
                        <input name="'.$name.'[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                            '.$filas['o_titulo'].'
                    </label>';
            }
            $html_contenido.='
                </div>';
        }
        return $html_contenido;
    }
    public function contenido_padres($padres, $r_id, $id_div, $n)
    {
        //padres
        $nbody=0;
        $html_padres = '';
        foreach ($padres as $fila){
            $nbody++;
            $id_caja = $id_div.'op'.$nbody;
            $html_padres .='
                <div class="panel-group smart-accordion-default" id="'.$id_div.'">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#'.$id_div.'" href="#'.$id_caja.'" class="collapsed" aria-expanded="false">
                                    <i class="fa fa-fw fa-plus-circle txt-color-green"></i>
                                    <i class="fa fa-fw fa-minus-circle txt-color-red"></i>
                                    '.$fila['o_titulo'].'
                                </a>
                            </h4>
                        </div>
                        <div id="'.$id_caja.'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                '.$this->html_titulo($fila['nuevo'],$fila['o_id'],$fila['o_titulo'],$n).'
                                '.$this->contenido_checkbox($fila['o_child'],$r_id,$n).'
                            </div>
                        </div>
                    </div>
                </div>';
        }
        //end padres
        return $html_padres;
    }
    public function opcion_menu($padres,$r_id,$n)
    {
        $id_div = 'menu'.$n.'';
        $html_opciones ='';
        $html_opciones .='
            <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false" role="widget">
                    <header role="heading">
                        <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                        <h2>Opciones</h2>
                    </header>
                    <div role="content">
                        <div class="widget-body no-padding">
                            <div class="panel-group smart-accordion-default" id="'.$id_div.'">
                                '.$this->contenido_padres($padres,$r_id,$id_div,$n).'
                            </div>
                        </div>  
                    </div>
                </div>
            </article>';
        return $html_opciones ;
    }
    public function opciones()
    {
        $r_id = $this->input->post('r_id');
        $data['listar_rol'] = $this->menu_modelo->roles_list($r_id);
        $filtro1 = 1;
        $filtro2 = 2;
        $filtro3 = 3;
        $filtro4 = 4;
        /*$filtro=5;*/
        $filtro6 = 6;
        $filtro7 = 7;
        /*$filtro=8;*/
        $filtro9 = 9;
        $padres1 = $this->menu_modelo->padres($filtro1,$r_id);
        $padres2 = $this->menu_modelo->padres($filtro2,$r_id);
        $padres3 = $this->menu_modelo->padres($filtro3,$r_id);
        $padres4 = $this->menu_modelo->padres($filtro4,$r_id);
        $padres6 = $this->menu_modelo->padres($filtro6,$r_id);
        $padres7 = $this->menu_modelo->padres($filtro7,$r_id);
        $padres9 = $this->menu_modelo->padres($filtro9,$r_id);
        //////////1///////////////////////////////////////
        $tabla1 = '';
        $tabla1 = $this->opcion_menu($padres1, $r_id, 1);
        //////////2///////////////////////////////////////
        $tabla2 = '';
        $tabla2 = $this->opcion_menu($padres2, $r_id, 2);
        //////////3///////////////////////////////////////
        $tabla3 = '';
        $tabla3 = $this->opcion_menu($padres3, $r_id, 3);
        //////////4///////////////////////////////////////
        $tabla4 = '';
        $tabla4 = $this->opcion_menu($padres4, $r_id, 4);
        //////////6///////////////////////////////////////
        $tabla6 = '';
        $tabla6 = $this->opcion_menu($padres6, $r_id, 6);
        //////////7///////////////////////////////////////
        $tabla7 = '';
        $tabla7 = $this->opcion_menu($padres7, $r_id, 7);
        //////////9///////////////////////////////////////
        $tabla9 = '';
        $tabla9 = $this->opcion_menu($padres9, $r_id, 9);
        //////////botton/////////////////////////////////
        $btn = '';
        $btn = '
            <center>
                <BUTTON class="btn btn-xs btn-primary" type="submit">
                    <div class="btn-hover-postion1">
                        Guardar Modificaciones
                    </div>
                </BUTTON>
            </center>
            <input  type="hidden" name="r_id" value="'.$r_id.'">';
        $data['rol1']=$tabla1;
        $data['rol2']=$tabla2;
        $data['rol3']=$tabla3;
        $data['rol4']=$tabla4;
        $data['rol6']=$tabla6;
        $data['rol7']=$tabla7;
        $data['rol9']=$tabla9;
        $data['botton']=$btn;
        $ruta = 'rol/rol_opciones';
        $this->construir_vista($ruta,$data);
    }
    public function roles_menu()
    {
        $lista_rol = $this->model_funcionario->get_add_rol();
        $tabla='';
        foreach ($lista_rol as $row) {
            $tabla.='
                <tr>
                    <td><h4>'.$row['r_nombre'].'</h4></td>
                    <td>
                        <form method="post" action="'.base_url().'index.php/rol_op">
                            <input  type="hidden" name="r_id" value="'.$row['r_id'].'">
                            <BUTTON class="btn btn-xs btn-primary">
                                <div class="btn-hover-postion1">
                                    Seleccionar
                                </div>
                            </BUTTON>
                        </form>
                    </td>
                </tr>';
        }
        $data['rol'] = $tabla;
        $ruta = 'rol/rol_menu';
        $this->construir_vista($ruta,$data);
    }
    public function opciones_h()
    {
        $r_id = $this->input->post('r_id');
        $data['listar_rol']=$this->menu_modelo->roles_list($r_id);
        $filtro1=1;
        $filtro2=2;
        $filtro3=3;
        $filtro4=4;
        /*$filtro=5;*/
        $filtro6=6;
        $filtro7=7;
        /*$filtro=8;*/
        $filtro9=9;
        $padres1=$this->menu_modelo->padres($filtro1,$r_id);
        $padres2=$this->menu_modelo->padres($filtro2,$r_id);
        $padres3=$this->menu_modelo->padres($filtro3,$r_id);
        $padres4=$this->menu_modelo->padres($filtro4,$r_id);
        $padres6=$this->menu_modelo->padres($filtro6,$r_id);
        $padres7=$this->menu_modelo->padres($filtro7,$r_id);
        $padres9=$this->menu_modelo->padres($filtro9,$r_id);
        ////////////////////////////////////////1/////////////////////////////////////////
        $tabla1='';
        $tabla1.='';
        $tabla1.='
        <form method="post" action="'.base_url().'index.php/mod_opc" style="font-size:18px;">
            <table>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                        //padres
                                        foreach ($padres1 as $fila){
                                            if($fila['nuevo']==0){
                                                $tabla1.='<dt>&nbsp;<input name="opciones1[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>';
                                            } else{
                                                $tabla1.='<dt>&nbsp;<input name="opciones1[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>';
                                            }
                                            $tabla1.='
                                                <dd>
                                                    <table>';
                                            $hijos = $this->menu_modelo->hijos($fila['o_child'], $r_id);
                                            //hijos
                                            foreach ($hijos as $filas) {
                                                    $tabla1.='
                                                        <tr>
                                                            <td>
                                                                <div class="checkbox">';
                                                                    if($filas['nuevo1']==0){
                                                                        $tabla1.='
                                                                            <label>
                                                                                <input name="op1[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                    '.$filas['o_titulo'].'
                                                                            </label>';
                                                                    } else{
                                                                        $tabla1.='
                                                                            <label>
                                                                                <input name="op1[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                    '.$filas['o_titulo'].'
                                                                            </label>';
                                                                    }
                                                                    $tabla1.='
                                                                </div>
                                                            </td>
                                                        </tr>';
                                            }
                                            //end hijos
                                            $tabla1.='
                                                    </table>
                                                </dd>';
                                        }
                                        //end padres
                                        $tabla1.='
                                    </dl>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';
        ///////////////////////////////////////////2/////////////////////////////////////////////////////
        $tabla2 = '';
        $tabla2.='
            <table style="font-size:18px;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                //padres
                                foreach ($padres2 as $fila){
                                    if($fila['nuevo']==0){
                                        $tabla2.='<dt>&nbsp;<input name="opciones2[]" class="opcion1"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>';
                                    }else{
                                        $tabla2.='<dt>&nbsp;<input name="opciones2[]" class="opcion1"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>';
                                    }
                                    $tabla2.='
                                        <dd>
                                            <table>';
                                    $hijos = $this->menu_modelo->hijos($fila['o_child'],$r_id);
                                    //hijos
                                    foreach ($hijos as $filas) {
                                        $tabla2.='
                                            <tr>
                                                <td>
                                                    <div class="checkbox">';
                                                        if($filas['nuevo1']==0){
                                                            $tabla2.='
                                                                <label>
                                                                    <input name="op2[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                    '.$filas['o_titulo'].'
                                                                </label>';
                                                        }else{
                                                            $tabla2.='
                                                                <label>
                                                                    <input name="op2[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                    '.$filas['o_titulo'].'
                                                                </label>';
                                                        }
                                                        $tabla2.='
                                                    </div>
                                                </td>
                                            </tr>';
                                    }
                                    //end hijos
                                    $tabla2.='
                                            </table>
                                        </dd>';
                                }
                                //end padres
                                $tabla2.='
                                    </dl>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';
        ////////////////////////////////////////////////////////////////3///////////////////////////////////////
        $tabla3='';
        $tabla3.='
            <table style="font-size:18px;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                foreach ($padres3 as $fila){//padres                                          
                                        if($fila['nuevo']==0){ 
                                                $tabla3.='<dt>&nbsp;<input name="opciones3[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                        }else{
                                                $tabla3.='<dt>&nbsp;<input name="opciones3[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                        }
                                                 $tabla3.=' <dd >
                                                                <table>';
                                        $hijos=$this->menu_modelo->hijos($fila['o_child'],$r_id);
                                            foreach ($hijos as $filas) {//hijos 
                                                                 $tabla3.='
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox">';
                                                                            if($filas['nuevo1']==0){ 
                                                                    $tabla3.='<label>
                                                                                <input name="op3[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }else{
                                                                    $tabla3.='<label>
                                                                                <input name="op3[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }
                                                                            $tabla3.='</div>
                                                                        </td>
                                                                    </tr>';
                                                                        }//end hijos
                                                                $tabla3.='</table>
                                                            </dd>';    
                                         }//end padres
                            $tabla3.='</dl>
                                <td>
                                </tr>
                        </table>
                    </td>
                    </tr></table>';
             ////////////////////////////////////////////////////////////////4///////////////////////////////////////
          $tabla4='';
          $tabla4.='
            <table style="font-size:18px;"><tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                foreach ($padres4 as $fila){//padres
                                    if($fila['nuevo']==0){
                                            $tabla4.='<dt>&nbsp;<input name="opciones4[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }else{
                                            $tabla4.='<dt>&nbsp;<input name="opciones4[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }
                                                 $tabla4.=' <dd >
                                                                <table>';
                                        $hijos=$this->menu_modelo->hijos($fila['o_child'],$r_id);
                                            foreach ($hijos as $filas) {//hijos 
                                                                 $tabla4.='
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox">';
                                                                            if($filas['nuevo1']==0){ 
                                                                    $tabla4.='<label>
                                                                                <input name="op4[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }else{
                                                                    $tabla4.='<label>
                                                                                <input name="op4[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }
                                                                            $tabla4.='</div>
                                                                        </td>
                                                                    </tr>';
                                                                        }//end hijos
                                                                $tabla4.='</table>
                                                            </dd>';    
                                         }//end padres
                            $tabla4.='</dl>
                                <td>
                                </tr>
                        </table>
                    </td>
                    </tr></table>';
                    ///////////////////////////////////////////////////////////////////////////6////////////////////////////////////
            $tabla6='';
        $tabla6.='';
           $tabla6.='<table style="font-size:18px;"><tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                foreach ($padres6 as $fila){//padres      
                                    if($fila['nuevo']==0){ 
                                            $tabla6.='<dt>&nbsp;<input name="opciones6[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }else{
                                            $tabla6.='<dt>&nbsp;<input name="opciones6[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }
                                                 $tabla6.=' <dd >
                                                                <table>';
                                        $hijos=$this->menu_modelo->hijos($fila['o_child'],$r_id);
                                            foreach ($hijos as $filas) {//hijos 
                                                                 $tabla6.='
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox">';
                                                                            if($filas['nuevo1']==0){ 
                                                                    $tabla6.='<label>
                                                                                <input name="op6[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }else{
                                                                    $tabla6.='<label>
                                                                                <input name="op6[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }
                                                                            $tabla6.='</div>
                                                                        </td>
                                                                    </tr>';
                                                                        }//end hijos
                                                                $tabla6.='</table>
                                                            </dd>';    
                                         }//end padres
                            $tabla6.='</dl>
                                <td>
                                </tr>
                        </table>
                    </td>
                    </tr></table>';
                    //////////////////////////////////////////////////////////////////7///////////////////////////////////////////
         $tabla7='';
        $tabla7.='';
           $tabla7.='<table style="font-size:18px;"><tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                foreach ($padres7 as $fila){//padres      
                                        if($fila['nuevo']==0){ 
                                                $tabla7.='<dt>&nbsp;<input name="opciones7[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                        }else{
                                                $tabla7.='<dt>&nbsp;<input name="opciones7[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                        }
                                                 $tabla7.=' <dd >
                                                                <table>';
                                        $hijos=$this->menu_modelo->hijos($fila['o_child'],$r_id);
                                            foreach ($hijos as $filas) {//hijos 
                                                                 $tabla7.='
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox">';
                                                                            if($filas['nuevo1']==0){ 
                                                                    $tabla7.='<label>
                                                                                <input name="op7[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }else{
                                                                    $tabla7.='<label>
                                                                                <input name="op7[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }
                                                                            $tabla6.='</div>
                                                                        </td>
                                                                    </tr>';
                                                                        }//end hijos
                                                                $tabla7.='</table>
                                                            </dd>';    
                                         }//end padres
                            $tabla7.='</dl>
                                <td>
                                </tr>
                        </table>
                    </td>
                    </tr></table>';
                    ///////////////////////////////////////////////////////////////9//////////////////////////////////////////////////////////
           $tabla9='';
        $tabla9.='';
           $tabla9.='<table style="font-size:18px;"><tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <dl>';
                                foreach ($padres9 as $fila){//padres  
                                    if($fila['nuevo']==0){ 
                                            $tabla9.='<dt>&nbsp;<input name="opciones9[]"  type="checkbox" value="'.$fila['o_id'].'" ><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }else{
                                            $tabla9.='<dt>&nbsp;<input name="opciones9[]"  type="checkbox" value="'.$fila['o_id'].'" checked><label>&nbsp;'.$fila['o_titulo'].'</label></dt>'; 
                                    }
                                                 $tabla9.=' <dd >
                                                                <table>';
                                        $hijos=$this->menu_modelo->hijos($fila['o_child'],$r_id);
                                            foreach ($hijos as $filas) {//hijos 
                                                                 $tabla9.='
                                                                    <tr>
                                                                        <td>
                                                                            <div class="checkbox">';
                                                                            if($filas['nuevo1']==0){ 
                                                                    $tabla9.='<label>
                                                                                <input name="op9[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" >
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }else{
                                                                    $tabla9.='<label>
                                                                                <input name="op9[]" id="rol" type="checkbox" value="'.$filas['o_id'].'" checked>
                                                                                '.$filas['o_titulo'].'
                                                                              </label>';
                                                                                }
                                                                            $tabla9.='</div>
                                                                        </td>
                                                                    </tr>';
                                                                        }//end hijos
                                                                $tabla9.='</table>
                                                            </dd>';    
                                         }//end padres
                            $tabla9.='</dl>
                                <td>
                                </tr>
                        </table>
                    </td>
                    </tr></table>';
                    //////////////////////////////////////////////////////////botton///////////////////////
                    $btn='';
                    $btn.='';
                    $btn.='<center><BUTTON class="btn btn-xs btn-primary">
                        <div class="btn-hover-postion1">
                           Guardar Modificaciones
                        </div>
                    </BUTTON></center> 
                    <input  type="hidden" name="r_id" value="'.$r_id.'">
                    </form>';
        $data['rol1']=$tabla1;
        $data['rol2']=$tabla2;
        $data['rol3']=$tabla3;
        $data['rol4']=$tabla4;
        $data['rol6']=$tabla6;
        $data['rol7']=$tabla7;
        $data['rol9']=$tabla9;
        $data['botton']=$btn;
        
        $ruta = 'rol/rol_opciones';
        $this->construir_vista($ruta,$data);
    }
    public function construir_vista($ruta,$data)
    {
        //----------------------------------- MENU-------------------------------
        $menu['enlaces'] = $this->menu->get_enlaces();
        $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        $menu['titulo'] = 'MANTENIMIENTO';
        //-----------------------------------------------------------------------
        //armar vista
        $this->load->view('includes/header');
        $this->load->view('includes/menu_lateral',$menu);
        $this->load->view($ruta,$data);//contenido
        //$this->load->view('admin/mantenimiento/vprueba');//contenido
        $this->load->view('includes/footer');
    }
    public function mod_rol()
    {
        $opciones1 = $this->input->post('opciones1');
        $op1 = $this->input->post('op1');
        $opciones2 = $this->input->post('opciones2');
        $op2 = $this->input->post('op2');
        $opciones3 = $this->input->post('opciones3');
        $op3 = $this->input->post('op3');
        $opciones4 = $this->input->post('opciones4');
        $op4 = $this->input->post('op4');
        $opciones7 = $this->input->post('opciones7');
        $op7 = $this->input->post('op7');
        $opciones9 = $this->input->post('opciones9');
        $op9 = $this->input->post('op9');
        //////////////////////////////////////////
        $r_id = $this->input->post('r_id');
        $this->model_rol->del_op_rol($r_id,$opciones1,$op1,$opciones2,$op2,$opciones3,$op3,$opciones4,$op4,$opciones7,$op7,$opciones9,$op9);           
    }
}