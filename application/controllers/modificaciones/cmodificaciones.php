<?php
class Cmodificaciones extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mestrategico/model_mestrategico');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->adm = $this->session->userData('adm');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->conf_mod_ope = $this->session->userData('conf_mod_ope');
            $this->conf_mod_req = $this->session->userData('conf_mod_req');
            $this->load->library('modificacionpoa');

            }else{
                redirect('admin/dashboard');
            }
        }
        else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*--- Lista de Poas Aprobados ---*/
    public function list_poas_aprobados(){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyectos']='';
      $data['gasto_corriente']='';
      
      $data['proyectos']=$this->modificacionpoa->list_pinversion(4); // Aprobados
      $data['gasto_corriente']=$this->modificacionpoa->list_unidades_es(4); // Aprobados

      $this->load->view('admin/modificacion/list_poa_aprobados',$data);
    }


   


  /*--- LISTA DE CITES FORM 4-FORM 5 (2020-2021) ---*/
  public function lista_cites($proy_id){
    $data['menu']=$this->menu(3); //// genera menu
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
    if(count($data['proyecto'])!=0){
      $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id);
      $titulo='<h1> PROYECTO : <small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</small></h1>';
      if($data['proyecto'][0]['tp_id']==4){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $titulo='<h1> '.$proyecto[0]['tipo_adm'].' : <small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</small></h1>';
      }

      $data['titulo']=$titulo;
      $data['requerimientos']=$this->list_cites_generados($proy_id,1);
      $data['operaciones']=$this->list_cites_generados($proy_id,2);
      $data['techo']=$this->list_cites_generados($proy_id,3);
      
      if($data['fase'][0]['pfec_estado']==1){
        $this->load->view('admin/modificacion/list_cites', $data);
      }
      else{
        redirect(site_url("").'/mod/list_top');
      }
      
    }
    else{
      redirect(site_url("").'/mod/list_top');
    }
    
  }





    /*----- PONDERACION OPERACIONES ------*/
    function pondera_poa_operaciones($com_id){
      $productos=$this->model_producto->list_prod($com_id);
      $pcion=(100/count($productos));
      foreach($productos as $row){
        $update_prod = array(
          'prod_ponderacion' => $pcion
        );
        $this->db->where('prod_id', $row['prod_id']);
        $this->db->update('_productos', $update_prod);
      }
    }

    /*----- UPDATE ESTADO ACTIVO DE LA MODIFICACION ------*/
    function update_activo_modificacion($cite_id){
      $update_cite= array(
        'cite_activo' => 1
      );
      $this->db->where('cite_id', $cite_id);
      $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
    }

    /*--- LISTA DE MODIFCACIONES (REQUERIMIENTO-OPERACION-TECHO) 2020 ---*/
    public function list_cites_generados($proy_id,$tp){
      $tabla='';
      // === LIST CITES REQUERIMIENTOS 
      if($tp==1){
        $cites=$this->model_modrequerimiento->list_cites_requerimientos_proy($proy_id);
        if(count($cites)!=0){
          $nro=0;
          foreach($cites  as $cit){
            $color='';
            $codigo='<font color=blue><b>'.$cit['cite_codigo'].'</b></font>';
            if($cit['cite_estado']==0){
              $color='#fbdfdf';
              $codigo='<font color=red><b>SIN CÓDIGO</b></font>';
            }

              $nro++;
              $tabla .='<tr bgcolor='.$color.'>';
                $tabla .='<td align="center">'.$nro.'</td>';
                $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                $tabla .='<td>'.$codigo.'</td>';
                $tabla .='<td>'.$cit['com_componente'].'</td>';
                $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_financiera/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE REQUERIMIENTOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                $tabla .='<td align="center">';
                  if($this->conf_mod_ope==1 || $this->tp_adm==1){
                    $tabla .='<a href="'.base_url().'index.php/mod/update_cite/'.$cit['cite_id'].'" id="myBtn'.$cit['cite_id'].'" title="MODIFICAR CITE"><img src="'.base_url().'assets/ifinal/form1.jpg" width="30" height="30"/></a><br>
                            <img id="load'.$cit['cite_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="20" height="20" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">';
                  }
                $tabla .='</td>';

              $tabla .='</tr>';
              $tabla.='<script>
                          document.getElementById("myBtn'.$cit['cite_id'].'").addEventListener("click", function(){
                          this.disabled = true;
                          document.getElementById("load'.$cit['cite_id'].'").style.display = "block";
                        });
                      </script>';
            }
        }
      }
      // ----- LIST CITES OPERACIONES
      elseif($tp==2){
        $cites=$this->model_modfisica->list_cites_Operaciones_proy($proy_id);
          if(count($cites)!=0){
            $nro=0;
              foreach($cites  as $cit){
                $ca=$this->model_modfisica->operaciones_adicionados($cit['cite_id']);
                $cm=$this->model_modfisica->operaciones_modificados($cit['cite_id']);
                $cd=$this->model_modfisica->operaciones_eliminados($cit['cite_id']);

                if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                  $nro++;
                  $tabla .='<tr>';
                    $tabla .='<td align="center">'.$nro.'</td>';
                    $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                    $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                    $tabla .='<td></td>';
                    $tabla .='<td>'.$cit['com_componente'].'</td>';
                    $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/reporte_modfis/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                    $tabla .='<td align=center>';
                    if($this->conf_mod_ope==1 || $this->tp_adm==1){
                        $tabla .='<a href="'.base_url().'index.php/mod/lista_operaciones/'.$cit['cite_id'].'" id="myBtn'.$cit['cite_id'].'" title="MODIFICAR CITE"><img src="'.base_url().'assets/ifinal/form1.jpg" width="30" height="30"/></a><br>
                                <img id="load'.$cit['cite_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="20" height="20" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">';
                    }
                      /*if($this->tp_adm==1){
                      $tabla .='<a href="'.base_url().'index.php/mod/cites_mod_ope/'.$cit['ope_id'].'" id="myBtn'.$cit['ope_id'].'" title="MODIFICAR CITE"><img src="'.base_url().'assets/ifinal/form1.jpg" width="30" height="30"/></a><br>
                                <img id="load'.$cit['ope_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="20" height="20" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO..">';
                      }*/ 
                    $tabla .='</td>';
                  $tabla .='</tr>';
                  $tabla.=' <script>
                                document.getElementById("myBtn'.$cit['cite_id'].'").addEventListener("click", function(){
                                this.disabled = true;
                                document.getElementById("load'.$cit['cite_id'].'").style.display = "block";
                              });
                            </script>';
                }
              }
          }
      }
      // ----- LIST DE CITES TECHO PRESUPUESTARIO
      else{
        $cites=$this->model_modificacion->list_cites_techo($proy_id);
        if(count($cites)!=0){
            $nro=0;
              foreach($cites  as $cit){
                $nro++;
                $tabla .='<tr>';
                  $tabla .='<td align="center">'.$nro.'</td>';
                  $tabla .='<td><b>'.$cit['cppto_cite'].'</b></td>';
                  $tabla .='<td align="center"><b>'.date('d/m/Y',strtotime($cit['cppto_fecha'])).'</b></td>';
                  $tabla .='<td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_techo/'.$cit['cppto_id'].'\');" title="REPORTE CITES - MODIFICACIÓN TECHO PRESUPUESTARIO"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                  $tabla .='<td align=center>';
                    if($this->fun_id==399){
                      $tabla.='<a href="'.base_url().'index.php/mod/techo/'.$cit['cppto_id'].'" title="MODIFICAR TECHO PRESUPUESTARIO">MOD. TECHO</a>';
                    }
                  $tabla.='</td>';
                $tabla .='</tr>';
              }
          }
      }

      return $tabla;
    }


    /*------------------------- TEMPORALIDAD PRODUCTOS ----------------------------*/
    public function temporalizacion_prod($prod_id,$gestion){
        $prod=$this->model_producto->get_producto_id($prod_id); /// Producto Id
        $programado=$this->model_producto->producto_programado($prod_id,$gestion); /// Producto Programado

        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
          $prog[1][$i]=0;
          $prog[2][$i]=0;
          $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
              $prog[1][$i]=$programado[0][$m[$i]];
            } 
        }
        
        $tr_return = '';
        $tr_return .= '<table class="table table-bordered">
                        <thead>
                        <tr >
                            <th style="width:6%;" bgcolor="#6ec7bc"><font color=#fff></font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>ENE.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>FEB.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>MAR.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>ABR.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>MAY.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>JUN.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>JUL</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>AGO.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>SEPT.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>OCT.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>NOV.</font></th>
                            <th style="width:7%;" bgcolor="#6ec7bc"><font color=#fff>DIC.</font></th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr >
                          <td>P.</td>';
                          for($i = 1 ;$i<=12 ;$i++){
                            $tr_return .= '<td>'.$prog[1][$i].'</td>';
                          }
                          $tr_return .= '
                          </tr>
                        </tbody>
                    </table>';
        return $tr_return;
    }

    /*==============================================================================*/




    /*------ TEMPORALIZACION DE PRODUCTOS (nose esta tomando encuenta lb) ------*/
    public function temporalizacion_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
        }
      }

      return $matriz;
    }
    /*-------------------------------- GENERAR MENU -------------------------------------*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
        $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
      }

      $tabla ='';
      for($i=0;$i<count($enlaces);$i++){
          if(count($subenlaces[$enlaces[$i]['o_child']])>0){
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
    /*--------------------------------------------------------------------------------*/
    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }

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
}