<?php
class Cmod_fisica extends CI_Controller {  
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
            $this->load->model('mestrategico/model_objetivoregion'); /// Gestion 2020
            $this->load->model('ejecucion/model_evaluacion'); /// Evaluacion POA
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->adm = $this->session->userData('adm');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->verif_mes=$this->session->userdata('mes_actual');
            $this->tmes = $this->session->userData('trimestre');
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


    /*--- LISTA SUBACTIVIDADES (2020-2021) ---*/
    public function mis_subactividades($proy_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($data['proyecto'])!=0){
        $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id);
        $titulo='
          <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</small>';
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $titulo='
          <h1> <b>'.$data['proyecto'][0]['tipo_adm'].' : </b><small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'].'</small>';
        }
        
        $componente=$this->model_componente->componentes_id($data['fase'][0]['id'],$data['proyecto'][0]['tp_id']);
        $tabla='';
        $tabla.='<table id="dt_basic4" class="table table table-bordered" width="100%">
                <thead>
                  <tr style="height:25px;">
                    <th style="width:1%;"></th>
                    <th style="width:5%;">Modificar Formulario</th>
                    <th style="width:15%;">UNIDAD RESPONSABLE</th>
                    <th style="width:10%;">RESPONSABLE</th>
                    <th style="width:5%;">PONDERACI&Oacute;N</th>
                    <th style="width:5%;">NRO. REGISTROS</th>
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($componente as $row){
                  $nro++;
                  $tabla.='<tr>';
                    $tabla.='<td>'.$nro.'</td>';
                    $tabla.='<td align=center>
                              <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff" title="MODIFICAR OPERACIONES" name="'.$row['com_id'].'">
                                <img src="'.base_url().'assets/ifinal/mod_money.png" width="35" height="35"/>
                              </a>
                            </td>';
                    $tabla.='<td>'.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';
                    $tabla.='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                    $tabla.='<td align=center>'.round($row['com_ponderacion'],2).' %</td>';
                    $tabla.='<td align=center bgcolor="#bee6e1"><font size=2 color=blue>'.count($this->model_producto->list_prod($row['com_id'])).'</font></td>';
                    
                  $tabla.='</tr>';
                }
        $tabla.='</tbody>
              </table>';        

        $data['componentes']=$tabla;
        $data['titulo_proy']=$titulo;
        $this->load->view('admin/modificacion/moperaciones/cite_modfis', $data);  
      }
      else{
        redirect(site_url("").'/mod/list_top');
      }
      
    }










    /*----- VALIDA CITE FISICA 2020 -----*/
    public function valida_cite_modificacion(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id 
        $com_id = $this->security->xss_clean($post['com_id']); /// Com id 
        $cite = $this->security->xss_clean($post['cite']); /// Cite
        $fecha = $this->security->xss_clean($post['fm']); /// Fecha
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos Proyecto

        if($proy_id!='' & count($proyecto)!=0){
          /*--- GUARDANDO CITE MODIFICADO - FISICA----*/
          $data_to_store = array(
            'cite_nota' => strtoupper($cite),
            'cite_fecha' => $fecha,
            'g_id' => $this->gestion,
            'fun_id' => $this->fun_id,
            'com_id' => $com_id,
            );
          $this->db->insert('cite_mod_fisica',$data_to_store);
          $cite_id=$this->db->insert_id();
          /*---------------------------------------------------------------*/

          if(count($this->model_modfisica->get_cite_fis($cite_id))==1){
            redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
            redirect(site_url("").'/mod/list_componentes/'.$proy_id.'');
          }
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
          redirect(site_url("").'/mod/list_componentes/'.$proy_id.'');
        }
          
      } else {
          show_404();
      }
    }


    /*------ LISTA DE OPERACIONES (2020 - 2021) -------*/
    public function list_operaciones($cite_id){
      $data['cite']=$this->model_modfisica->get_cite_fis($cite_id);
      if(count($data['cite'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['datos_cite']=$this->datos_cite($data['cite']); /// DATOS CITE
        $data['titulo']=$this->titulo_cabecera($data['cite']);
        $data['indi'] = $this->model_proyecto->indicador(); /// indicador
        $data['metas'] = $this->model_producto->tp_metas(); /// tp metas

        if($data['cite'][0]['tp_id']==1){
          $data['list_oregional']=$this->lista_oregional_pi($data['cite'][0]['proy_id']);
        }
        else{
          $data['list_oregional']=$this->lista_oregional($data['cite'][0]['proy_id']);
        }
        
        $data['productos'] = $this->model_producto->list_prod($data['cite'][0]['com_id']); // Lista de productos
        $data['verif_mod']=$this->verif_cite($cite_id); /// Verificando modulos
        $data['operaciones']=$this->mis_operaciones($data['cite']); /// Lista Operaciones
        $this->load->view('admin/modificacion/moperaciones/productos/list_productos', $data);

      }
      else{
        $this->session->set_flashdata('danger','ERROR AL INGRESAR');
        redirect(site_url("").'/mod/list_componentes/'.$cite[0]['proy_id'].'');
      }
    }


    /*------ LISTA OPERACIONES (2020) ------*/
    public function mis_operaciones($cite){
      $proy_id=$cite[0]['proy_id'];
      $productos = $this->model_producto->lista_operaciones($cite[0]['com_id'],$this->gestion); // Lista de Operaciones
      $tabla ='';
      $tabla .='<thead>
                  <tr class="modo1">
                    <th style="width:1%; text-align=center"><b>COD.</b></th>
                    <th style="width:1%; text-align=center"><b>E/B</b></th>
                    <th style="width:2%;"><b>COD. OR.</b></th>
                    <th style="width:2%;"><b>COD. OPE.</b></th>
                    <th style="width:15%;"><b>OPERACI&Oacute;N</b></th>
                    <th style="width:15%;"><b>RESULTADO</b></th>
                    <th style="width:10%;"><b>TIP. IND.</b></th>
                    <th style="width:10%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE '.($this->gestion-1).'</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:10%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                    <th style="width:7%;"><b>PTTO..</b></th>
                    <th style="width:7%;"><b>NRO. REQ.</b></th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach($productos as $rowp){
                  $cont++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $ptto=0;
                  if(count($monto)!=0){
                    $ptto=$monto[0]['total'];
                  }

                  $color=''; $titulo=''; $por='';
                  if($cite[0]['tp_id']==1){
                    if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta'] || $rowp['or_id']==0){
                      $color='#fbd5d5';
                      $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                    }
                  }
                  else{
                    if($rowp['indi_id']==2){ // Relativo
                      $por='%';
                      if($rowp['mt_id']==3){
                        if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                          $color='#fbd5d5';
                          $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                        }
                      }
                    }
                    else{ // Absoluto
                      if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                        $color='#fbd5d5';
                        $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                      }
                    }
                  }

                  $tabla .='
                    <tr bgcolor="'.$color.'" class="modo1" title='.$titulo.'>
                      <td align="center" title='.$rowp['prod_id'].'><font color="blue" size="2"><b>'.$rowp['prod_cod'].'</b></font></td>
                      <td align="center">
                        <a href="'.site_url("").'/mod/update_ope/'.$rowp['prod_id'].'/'.$cite[0]['cite_id'].'" title="MODIFICAR ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a><br>';
                        /*if(count($monto)==0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" id="'.$cite[0]['cite_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                        }*/
                        $tabla.='
                      </td>
                      <td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>
                      <td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>
                      <td style="width:15%;">'.$rowp['prod_producto'].'</td>
                      <td style="width:15%;">'.$rowp['prod_resultado'].'</td>
                      <td style="width:10%;">'.$rowp['indi_abreviacion'].'</td>
                      <td style="width:10%;">'.$rowp['prod_indicador'].'</td>
                      <td style="width:10%;">'.round($rowp['prod_linea_base'],2).'</td>
                      <td style="width:10%;">'.round($rowp['prod_meta'],2).'</td>';
                    if(count($programado)!=0){
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['enero'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['febrero'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['marzo'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['abril'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['mayo'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['junio'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['julio'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['agosto'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['septiembre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['octubre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['noviembre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['diciembre'],2).' '.$por.'</td>';
                    }
                    else{
                      $tabla.='<td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>';
                    }
                    $tabla.='<td style="width:10%;" bgcolor="#e5fde5">'.$rowp['prod_fuente_verificacion'].'</td>';
                    $tabla.='<td>'.number_format($ptto, 2, ',', '.').'</td>';
                    $tabla.='<td style="width:7%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
                  $tabla .='</tr>';
                  ?>
                  <script>
                    function scheck<?php echo $cont;?>(estaChequeado) {
                      val = parseInt($('[name="tot"]').val());
                      if (estaChequeado == true) {
                        val = val + 1;
                      } else {
                        val = val - 1;
                      }
                      $('[name="tot"]').val((val).toFixed(0));
                    }
                  </script>
                  <?php
                }
                $tabla.='</tbody>';

      return $tabla;
    }

  /*------ MODIFICAR PRODUCTO (2020 - 2021) -------*/
    public function update_operacion($prod_id,$cite_id){
      $data['producto']=$this->model_producto->get_producto_id($prod_id);
      $data['cite']=$this->model_modfisica->get_cite_fis($cite_id);
      if(count($data['producto'])!=0 & count($data['cite'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['datos_cite']=$this->datos_cite($data['cite']); /// DATOS CITE
        $data['titulo']=$this->titulo_cabecera($data['cite']);
        $data['indi']= $this->model_proyecto->indicador(); /// indicador
        $data['metas'] = $this->model_producto->tp_metas(); /// tp metas
        $data['indi_pei']=$this->model_mestrategico->list_indicadores_pei2($data['producto'][0]['acc_id']);
        $data['servicios']=$this->model_componente->proyecto_componente($data['cite'][0]['proy_id']);

        if($data['proyecto'][0]['tp_id']==1){
          $data['list_oregional']=$this->model_objetivoregion->get_unidad_pregional_programado($data['cite'][0]['proy_id']); /// Lista de Objetivos Regionales PI
        }
        else{
          $data['list_oregional']=$this->model_objetivoregion->list_proyecto_oregional($data['cite'][0]['proy_id']);/// Lista de Objetivos Regionales   
        }

        $data['programado']=$this->model_producto->producto_programado($data['producto'][0]['prod_id'],$this->gestion);
        $data['prog']=0;
        $programado=$this->model_producto->suma_programado_producto($prod_id,$this->gestion);
        $prog=0;
        if(count($programado)!=0){
          $prog=$programado[0]['prog'];
        }

        if($data['producto'][0]['mt_id']==1){
          $data['prog']=$data['producto'][0]['prod_meta'];
        }
        else{
          $data['prog']=$prog;
        }

        ///------- verif evaluacion trimestral
        if($this->tmes==1){
          for ($i=1; $i <=12 ; $i++) { 
              $eval[$i]=0;
              $disabled[$i]="";
            }
        }
        else{
          if($data['producto'][0]['indi_id']==2 & $data['producto'][0]['mt_id']==1){
            for ($i=1; $i <=12 ; $i++) { 
              $eval[$i]=1;
              $disabled[$i]="disabled='true'";
            }
          }
          else{
            for ($i=1; $i <=12 ; $i++) { 
              if($i<$this->verif_mes[1]){ /// Meses ejecutados
                $eval[$i]=1;
                $disabled[$i]="disabled='true'";
              }
              else{
                $eval[$i]=0;
                $disabled[$i]="";
              }
            }
          }
        }


        $data['disabled']=$disabled;
       
        $inptus='';
        for ($i=1; $i <=12 ; $i++) { 
          $inptus.='<input name="me'.$i.'" type="hidden" value="'.$eval[$i].'">';
        }

        $data['inputs']=$inptus;
//        echo $this->verif_mes[1];
        $this->load->view('admin/modificacion/moperaciones/productos/edit_prod', $data);
      }
      else{
        echo "Error !!!";
      }
  }

    /*--- VALIDA NUEVA ACTIVIDAD (2020) ---*/
    public function valida_operacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']);
        
        $codigo = $this->security->xss_clean($post['cod']); /// Codigo
        $producto = $this->security->xss_clean($post['prod']); /// Actividad
        $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
        $tipo_i = $this->security->xss_clean($post['tipo_i']); /// tipo indicador
        $ppto = $this->security->xss_clean($post['ppto']); /// Presupuesto
        $or_id = $this->security->xss_clean($post['or_id']); /// Objetivo Regional
        $indicador = $this->security->xss_clean($post['indicador']); /// indicador
        $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
        $meta = $this->security->xss_clean($post['meta']); /// met
        $verificacion = $this->security->xss_clean($post['verificacion']); /// verificacion
        $tp_met = $this->security->xss_clean($post['tp_met']); /// Tipo de Meta
        $lb = $this->security->xss_clean($post['lbase']); /// Linea Base

        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($or_id);
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if($tipo_i==1){
          $tp_met=3;
        }

          /*----- INSERT OPERACION ----*/
          $data_to_store = array(
            'com_id' => $cite[0]['com_id'],
            'prod_producto' => strtoupper($producto),
            'prod_resultado' => strtoupper($resultado),
            'indi_id' => $tipo_i,
            'prod_indicador' => strtoupper($indicador),
            'prod_linea_base' => $lb,
            'prod_meta' => $meta ,
            'prod_unidades' => strtoupper($unidad),
            'prod_fuente_verificacion' => strtoupper($verificacion), 
            'acc_id' => $ae,
            'mt_id' => $tp_met,
            'fecha' => date("d/m/Y H:i:s"),
            'prod_mod' => 2,
            'prod_cod'=>$codigo,
            'fun_id' => $this->fun_id,
            'or_id' => $or_id,
          );
          $this->db->insert('_productos', $data_to_store);
          $prod_id=$this->db->insert_id(); ////// id del producto
          /*---------------------------*/
          
          /*---------------- Temporalidad -------------------*/
          if($tipo_i==1){
            for ($i=1; $i <=12 ; $i++) {
              if($post['m'.$i]!=0){
                $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['m'.$i]);
              }
            }
          }
          if($tipo_i==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($post['m'.$i]!=0){
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['m'.$i]);
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['m'.$i]);
              }
            }
          }
          /*------------------------------------------------*/

          /*--------- iNSERT AUDI ADICIONAR OPERACION -------*/
          $data_to_store2 = array(
            'prod_id' => $prod_id, /// prod_id
            'cite_id' => $cite_id, /// cite_id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_producto_add', $data_to_store2);
          $proda_id=$this->db->insert_id();
          /*-----------------------------------------------*/

          if(count($this->model_modificacion->get_add_producto($proda_id))!=0 & $this->model_producto->get_producto_id($prod_id)!=0){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA ACTIVIDAD :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA ACTIVIDAD ...');
          }

          redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
      }
      else{
        echo "string";
      }
    }



        /*--- VALIDA UPDATE ACTIVIDAD (2020) ---*/
    public function valida_update_operacion2(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite

        $com_id = $this->security->xss_clean($post['com_id']); /// com id
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
        $producto = $this->security->xss_clean($post['prod']); /// Actividad
        $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
        $tipo_i = $this->security->xss_clean($post['tipo_i']); /// tipo indicador
        $ppto = $this->security->xss_clean($post['ppto']); /// Presupuesto
        $or_id = $this->security->xss_clean($post['or_id']); /// Objetivo Regional
        $indicador = $this->security->xss_clean($post['indicador']); /// indicador
        $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
        $lb = $this->security->xss_clean($post['lb']); /// Linea Base
        $meta = $this->security->xss_clean($post['met']); /// met
        $verificacion = $this->security->xss_clean($post['verificacion']); /// verificacion
        $tp_met = $this->security->xss_clean($post['tp_met']); /// Tipo de Meta
        $lb = $this->security->xss_clean($post['lb']); /// Linea Base

        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($or_id);
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if($tipo_i==1){
          $tp_met=3;
        }
/*
        for ($i=1; $i <=4 ; $i++) {
              echo $post['m'.$i]."<br>";
            }*/




        $mes=0;
        for ($i=1; $i <=4 ; $i++) { 
          if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i))==0){
          for ($j=1; $j <=3 ; $j++) { 
            $mes++;
              if($post['m'.$mes]!=''){
                echo $mes." --> ".$post['m'.$mes]."<br>";
              }
            }
          }
          else{
            $mes=$mes+3;
          }
        }

        ////--------
          $mes=0;

          if($tipo_i==1){
            for ($i=1; $i <=4 ; $i++) { 
              if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i))==0){
              for ($j=1; $j <=3 ; $j++) { 
                $mes++;
                  if($post['m'.$mes]!=0){
                    $this->model_producto->add_prod_gest($prod_id,$this->gestion,$mes,$post['m'.$mes]);
                  }
                }
              }
              else{
                $mes=$mes+3;
              }
            }
          }

/*          if($tipo_i==2){
            if($tp_met==3){
              for ($i=1; $i <=4 ; $i++) { 
                if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i))==0){
                for ($j=1; $j <=3 ; $j++) { 
                  $mes++;
                    if($post['m'.$mes]!=0){
                      $this->model_producto->add_prod_gest($prod_id,$this->gestion,$mes,$post['m'.$mes]);
                    }
                  }
                }
                else{
                  $mes=$mes+3;
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=4 ; $i++) { 
                if(count($this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i))==0){
                for ($j=1; $j <=3 ; $j++) { 
                  $mes++;
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$mes,$meta);
                  }
                }
                else{
                  $mes=$mes+3;
                }
              }
            }
          } */

      }
      else{
        echo "Error !!!";
      }
    }


    /*--- VALIDA UPDATE ACTIVIDAD (2020) ---*/
    public function valida_update_operacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite

        $com_id = $this->security->xss_clean($post['com_id']); /// com id
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
        $producto = $this->security->xss_clean($post['prod']); /// Actividad
        $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
        $tipo_i = $this->security->xss_clean($post['tipo_i']); /// tipo indicador
        $ppto = $this->security->xss_clean($post['ppto']); /// Presupuesto
        $or_id = $this->security->xss_clean($post['or_id']); /// Objetivo Regional
        $indicador = $this->security->xss_clean($post['indicador']); /// indicador
        $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
        $lb = $this->security->xss_clean($post['lb']); /// Linea Base
        $meta = $this->security->xss_clean($post['met']); /// met
        $verificacion = $this->security->xss_clean($post['verificacion']); /// verificacion
        $tp_met = $this->security->xss_clean($post['tp_met']); /// Tipo de Meta
        $lb = $this->security->xss_clean($post['lb']); /// Linea Base

        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($or_id);
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if($tipo_i==1){
          $tp_met=3;
        }

        if($this->copia_operacion($cite,$prod_id,2)){
          /*--------- Update Producto --------*/
          $update_prod = array(
            'com_id' => $com_id, // com id
            'prod_producto' => strtoupper($producto), // Producto
            'prod_resultado' => strtoupper($resultado),
            'indi_id' => $tipo_i,
            'prod_indicador' => strtoupper($indicador),
            'prod_unidades' => strtoupper($unidad),
            'prod_linea_base' => $lb,
            'prod_meta' => $meta,
            'prod_fuente_verificacion' => strtoupper($verificacion),
            'estado' => 2,
            'acc_id' => $ae,
            'fecha' => date("d/m/Y H:i:s"),
            'mt_id' => $tp_met,
            'prod_mod' => 2,
            'or_id' => $or_id,
            'fun_id' => $this->fun_id,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_prod);
          /*----------------------------------*/

          $mes=0;
          // $this->verif_mes[1]

          if($tipo_i==1){
            for ($i=$this->verif_mes[1]; $i <=12 ; $i++) { 
              if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
                $this->db->where('prod_id', $prod_id);
                $this->db->where('m_id', $i);
                $this->db->delete('prod_programado_mensual'); 

                if($post['m'.$i]!=0){
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['m'.$i]);
                }
              }
            }
          }

          if($tipo_i==2){
            if($tp_met==3){
              for ($i=$this->verif_mes[1]; $i <=12 ; $i++) { 
                if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
                  $this->db->where('prod_id', $prod_id);
                  $this->db->where('m_id', $i);
                  $this->db->delete('prod_programado_mensual'); 

                  if($post['m'.$i]!=0){
                    $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['m'.$i]);
                  }
                }
              }
            }
            elseif($tp_met==1){
              for ($i=$this->verif_mes[1]; $i <=12 ; $i++) { 
                if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
                  $this->db->where('prod_id', $prod_id);
                  $this->db->where('m_id', $i);
                  $this->db->delete('prod_programado_mensual'); 

                  if($post['m'.$i]!=0){
                    $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$meta);
                  }
                }
              }
            }
          } 

          /*-------------- Redireccionando a lista de Operaciones -------*/
          $this->session->set_flashdata('success','LA OPERACIÓN SE MODIFICO CORRECTAMENTE :)');
          redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
        }
      }
      else{
        echo "Error !!!";
      }
    }


    /*---- Eliminar Operacion-Producto ---*/
      function delete_operacion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $cite_id = $post['cite_id']; /// Cite Id
          $prod_id = $post['prod_id']; /// Prod Id
          $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
          $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Datos del Proyecto


          if($this->copia_operacion($cite,$prod_id,3)){
            $update_prod = array(
              'prod_mod' => 2,
              'estado' => 3,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->fun_id,
              );
            $this->db->where('prod_id', $prod_id);
            $this->db->update('_productos', $update_prod);

            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }

          echo json_encode($result);
      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*----- REPORTE CITE - MODIFICACION FISICA ----*/
    public function reporte_modificacion_fisica($cite_id){
      $data['cite']=$this->model_modfisica->get_cite_fis($cite_id);
      if(count($data['cite'])!=0){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']); 
        $data['titulo']='<tr style="font-size: 8pt;">
                              <td style="height: 1.2%"><b>PROYECTO</b></td>
                              <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</td>
                           </tr>
                           <tr style="font-size: 8pt;">
                              <td style="height: 1.2%"><b>UNIDAD RESP.</b></td>
                              <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                           </tr>';

        if($data['cite'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);
          $data['titulo']='
                          <tr style="font-size: 8pt;">
                            <td style="height: 1.2%"><b>ACTIVIDAD </b></td>
                            <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' '.$data['proyecto'][0]['tipo'].'   '.strtoupper($data['proyecto'][0]['act_descripcion']).' '.$data['proyecto'][0]['abrev'].'</td>
                          </tr>
                          <tr style="font-size: 8pt;">
                              <td style="height: 1.2%"><b>SUBACTIVIDAD</b></td>
                              <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                           </tr>';
        }

        $data['mes'] = $this->mes_nombre();
        $data['actividades']=$this->rep_actividades($cite_id);
        $this->load->view('admin/modificacion/moperaciones/reporte_modificacion_operaciones', $data);
      }
      else{
        echo "Error !!!";
      }
    }


    /*--------- REPORTE CITE OPERACION ----------*/
    public function rep_actividades($cite_id){
      $tabla ='';
      $cite=$this->model_modfisica->get_cite_fis($cite_id);
      $ope_adicionados=$this->model_modfisica->operaciones_adicionados($cite_id);
      if(count($ope_adicionados)!=0){
          $tabla.='<div style="font-size: 12px;font-family: Arial;">AGREGADOS ('.count($ope_adicionados).')</div>';
          $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
             <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                <th style="width:1%;height:15px;color:#FFF;">#</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                <th style="width:2%;color:#FFF;">COD.<br>O.R.</th>
                <th style="width:2%;color:#FFF;">COD.<br>OPE.</th> 
                <th style="width:10%;color:#FFF;">OPERACI&Oacute;N</th>
                <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                <th style="width:9%;color:#FFF;">INDICADOR</th>
                <th style="width:2%;color:#FFF;">LB.</th>
                <th style="width:3%;color:#FFF;">META</th>
                <th style="width:3%;color:#FFF;">ENE.</th>
                <th style="width:3%;color:#FFF;">FEB.</th>
                <th style="width:3%;color:#FFF;">MAR.</th>
                <th style="width:3%;color:#FFF;">ABR.</th>
                <th style="width:3%;color:#FFF;">MAY.</th>
                <th style="width:3%;color:#FFF;">JUN.</th>
                <th style="width:3%;color:#FFF;">JUL.</th>
                <th style="width:3%;color:#FFF;">AGO.</th>
                <th style="width:3%;color:#FFF;">SEPT.</th>
                <th style="width:3%;color:#FFF;">OCT.</th>
                <th style="width:3%;color:#FFF;">NOV.</th>
                <th style="width:3%;color:#FFF;">DIC.</th>
                <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                <th style="width:5%;color:#FFF;">PPTO.</th>   
            </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($ope_adicionados as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
          $tabla.='
            </tbody>
            </table><br>';
      }

      $ope_modificados=$this->model_modfisica->operaciones_modificados($cite_id);
      if(count($ope_modificados)!=0){
          $tabla.='<div style="font-size: 12px;font-family: Arial;">MODIFICADOS ('.count($ope_modificados).')</div>';
          $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
             <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                <th style="width:1%;height:15px;color:#FFF;">#</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                <th style="width:2%;color:#FFF;">COD.<br>O.R.</th>
                <th style="width:2%;color:#FFF;">COD.<br>OPE.</th> 
                <th style="width:10%;color:#FFF;">OPERACI&Oacute;N</th>
                <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                <th style="width:9%;color:#FFF;">INDICADOR</th>
                <th style="width:2%;color:#FFF;">LB.</th>
                <th style="width:3%;color:#FFF;">META</th>
                <th style="width:3%;color:#FFF;">ENE.</th>
                <th style="width:3%;color:#FFF;">FEB.</th>
                <th style="width:3%;color:#FFF;">MAR.</th>
                <th style="width:3%;color:#FFF;">ABR.</th>
                <th style="width:3%;color:#FFF;">MAY.</th>
                <th style="width:3%;color:#FFF;">JUN.</th>
                <th style="width:3%;color:#FFF;">JUL.</th>
                <th style="width:3%;color:#FFF;">AGO.</th>
                <th style="width:3%;color:#FFF;">SEPT.</th>
                <th style="width:3%;color:#FFF;">OCT.</th>
                <th style="width:3%;color:#FFF;">NOV.</th>
                <th style="width:3%;color:#FFF;">DIC.</th>
                <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                <th style="width:5%;color:#FFF;">PPTO.</th>   
            </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($ope_modificados as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
          $tabla.='
            </tbody>
            </table><br>';
      }

      $ope_eliminados=$this->model_modfisica->operaciones_eliminados($cite_id);
      if(count($ope_eliminados)!=0){
          $tabla.='<div style="font-size: 12px;font-family: Arial;">ELIMINADOS ('.count($ope_eliminados).')</div>';
          $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
            <thead>
             <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                <th style="width:1%;height:15px;color:#FFF;">#</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                <th style="width:2%;color:#FFF;">COD.<br>O.R.</th>
                <th style="width:2%;color:#FFF;">COD.<br>OPE.</th> 
                <th style="width:10%;color:#FFF;">OPERACI&Oacute;N</th>
                <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                <th style="width:9%;color:#FFF;">INDICADOR</th>
                <th style="width:2%;color:#FFF;">LB.</th>
                <th style="width:3%;color:#FFF;">META</th>
                <th style="width:3%;color:#FFF;">ENE.</th>
                <th style="width:3%;color:#FFF;">FEB.</th>
                <th style="width:3%;color:#FFF;">MAR.</th>
                <th style="width:3%;color:#FFF;">ABR.</th>
                <th style="width:3%;color:#FFF;">MAY.</th>
                <th style="width:3%;color:#FFF;">JUN.</th>
                <th style="width:3%;color:#FFF;">JUL.</th>
                <th style="width:3%;color:#FFF;">AGO.</th>
                <th style="width:3%;color:#FFF;">SEPT.</th>
                <th style="width:3%;color:#FFF;">OCT.</th>
                <th style="width:3%;color:#FFF;">NOV.</th>
                <th style="width:3%;color:#FFF;">DIC.</th>
                <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                <th style="width:5%;color:#FFF;">PPTO.</th>   
            </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($ope_eliminados as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
          $tabla.='
            </tbody>
            </table>';
      }

      $tabla.='<br>
      <div style="font-size: 8px;font-family: Arial;">
      En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

      &nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
      &nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
      &nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
      &nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
      </div>';

      return $tabla;
    }

    /*--- CERRAR MODIFICACION FIS (2020) ---*/
     public function cerrar_modificacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
        $cite = $this->model_modfisica->get_cite_fis($cite_id); // Datos Cite

        $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
        if(count($verificando)==0){ // Creando campo para la distrital
          $data_to_store2 = array(
            'dist_id' => $cite[0]['dist_id'], /// dist_id
            'g_id' => $this->gestion, /// gestion
            'mod_ope' => 0, 
            'mod_req' => 0,
            'cert_poa' => 0,
          );
          $this->db->insert('conf_modificaciones_distrital', $data_to_store2);
          $mod_id=$this->db->insert_id();
        }

        if($cite[0]['cite_estado']==0){ /// Pendiente, Insert Codigo
          $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
          $nro_mod=$verificando[0]['mod_ope']+1;
          $nro_cdep='';
          if($nro_mod<10){
            $nro_cdep='000';
          }
          elseif($nro_mod<100) {
            $nro_cdep='00';
          }
          elseif($nro_mod<1000){
            $nro_cdep='0';
          }

          /*--------------- Update cite ---------------*/
          $update_cite= array(
            'cite_codigo' => 'O_'.$cite[0]['adm'].'-'.$cite[0]['abrev'].'-'.$nro_cdep.''.$nro_mod,
            'cite_observacion' => strtoupper($observacion),
            'cite_estado' => 1,
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_fisica', $this->security->xss_clean($update_cite));
          /*------------------------------------------*/

          /*----- Update Configuracion mod distrital -----*/
          $update_conf= array(
            'mod_ope' => $nro_mod
          );
          $this->db->where('mod_id', $verificando[0]['mod_id']);
          $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
          /*----------------------------------------------*/
        }
        else{ /// Cerrado, Update Observacion
          $update_cite= array(
            'cite_observacion' => strtoupper($observacion),
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_fisica', $this->security->xss_clean($update_cite));
        }

        /*----------- redireccionar -------*/
        $this->session->set_flashdata('success','SE CERRO CORRECTAMENTE LA MODIFICACIÓN DE ACTIVIDADES');
        redirect(site_url("").'/mod/ver_mod_poa_fis/'.$cite_id.'');

      }
      else{
        echo "Error !!!";
      }
    }


    /*--- VER MODIFICACION POA---*/
    public function ver_modificacion_poa($cite_id){
      $data['cite'] = $this->model_modfisica->get_cite_fis($cite_id); // Datos Cite
      if(count($data['cite'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['titulo']=$this->titulo_cabecera($data['cite']); /// CABECERA
        $data['datos_cite']=$this->datos_cite($data['cite']); /// DATOS CITE
        $this->load->view('admin/modificacion/moperaciones/ver_modificado_poa', $data);
      }
      else{
        redirect(site_url("").'/mod/list_cites/'.$data['cite'][0]['proy_id'].'');
      }
    }





    /*======== FUNCIONES EXTRAS =========*/
    /*------ Funcion Copia Operacion -------*/
    public function copia_operacion($cite,$prod_id,$tip_mod){
      // tip_mod=2 /// modificado
      // tip_mod=3 /// eliminado
      $producto=$this->model_producto->get_producto_id($prod_id);

      $data_to_store = array(
        'prodh_producto' => $producto[0]['prod_producto'],
        'indi_id' => $producto[0]['indi_id'],
        'prodh_indicador' => $producto[0]['prod_indicador'],
        'prodh_formula' => $producto[0]['prod_formula'],
        'prodh_linea_base' => $producto[0]['prod_linea_base'],
        'prodh_meta' => $producto[0]['prod_meta'],
        'prod_fuente_verificacion' => $producto[0]['prod_fuente_verificacion'],
        'pt_id' => $producto[0]['pt_id'],
        'prod_resultado' => $producto[0]['prod_resultado'],
        'acc_id' => $producto[0]['acc_id'],
        'or_id' => $producto[0]['or_id'],
        'prod_cod' => $producto[0]['prod_cod'],
        'prod_observacion' => $producto[0]['prod_observacion'],
        'mt_id' => $producto[0]['mt_id'],
      );
      $this->db->insert('_producto_historial', $data_to_store);
      $prodh_id=$this->db->insert_id();
        
      $prog=$this->model_producto->programado_producto($prod_id);

      foreach ($prog as $row) {
        $data_to_store2 = array(
        'prodh_id' => $prodh_id,
        'm_id' => $row['m_id'],
        'pg_fis' => $row['pg_fis'],
        'g_id' => $row['g_id'],
        );
        $this->db->insert('prod_programado_mensual_historial', $data_to_store2);
      }

      if($tip_mod==2){
        $data_to_store3 = array(
          'prod_id' => $prod_id,
        //  'prodh_id' => $prodh_id,
          'cite_id' => $cite[0]['cite_id'],
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          'fun_id' => $this->session->userdata("fun_id"),
        );
        $this->db->insert('_producto_modificado', $data_to_store3);
        $prodm_id=$this->db->insert_id();

        if (count($this->model_modificacion->get_mod_producto($prodm_id))==1) {
          return true;
        }
        else{
          return false;
        }
      }
      else{
        /*---- Insert Producto Delete -----*/
          $data_to_store = array( 
            'prod_id' => $prod_id,
           // 'prodh_id' => $prodh_id,
            'cite_id' => $cite[0]['cite_id'], /// Cite Id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_producto_delete', $data_to_store);
          $dlte_id=$this->db->insert_id();
        /*----------------------------------*/

        if (count($this->model_modificacion->get_delete_producto($dlte_id))==1) {
          return true;
        }
        else{
          return false;
        }

      }

    }

    /*--- ACTUALIZA CODIGO DE ACTIVIDAD ----*/
    public function update_codigo($cite_id){
      $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
      $productos = $this->model_producto->lista_operaciones($cite[0]['com_id'],$this->gestion); // Lista de productos
      $nro=0;
      foreach($productos as $row){
        $nro++;
        $update_prod= array(
          'prod_cod' => $nro,
          'fun_id' => $this->fun_id
        );
        $this->db->where('prod_id', $row['prod_id']);
        $this->db->update('_productos', $update_prod);
      }

      $this->session->set_flashdata('success','LOS CÓDIGOS DE ACTIVIDAD SE ACTUALIZARON CORRECATMENTE :)');
      redirect('mod/lista_operaciones/'.$cite[0]['cite_id']);
    }



    /*--- VERIFICA SI SE TIENE ALGUN REGISTRO (ABM) ---*/
    public function verif_cite($cite_id){
      $cite=$this->model_modfisica->get_cite_fis($cite_id); // CITE
      $proyecto=$this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// PROYECTO

      $ca=$this->model_modfisica->operaciones_adicionados($cite_id);
      $cm=$this->model_modfisica->operaciones_modificados($cite_id);
      $cd=$this->model_modfisica->operaciones_eliminados($cite_id);

      $sw=0;
      if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
        $sw=1;
      }

      return $sw;
    }

    /*------ VERIFICANDO CODIGO DE MODIFICACION POA (2020)-----*/
    public function datos_cite($cite){
      $tabla='';

      if($cite[0]['cite_estado']!=0){
        $tit='<font color=blue><b>'.$cite[0]['cite_codigo'].'</b></font>';
      }
      else{
        $tit=' <font color=#a87830><b>DEBE CERRAR LA MODIFICACI&Oacute;N DEL REQUERIMIENTO !!</b></font>';
      }

      $tabla.='<h1><b> CITE Nro. : <small>'.$cite[0]['cite_nota'].'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;FECHA : <small>'.date('d/m/Y',strtotime($cite[0]['cite_fecha'])).'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;C&Oacute;DIGO : '.$tit.'</b></h1>';
      return $tabla;
    }


    /*------ TITULO CABECERA (2020)-----*/
    public function titulo_cabecera($cite){
      $tabla='';
      if($cite[0]['tp_id']==1){ /// Proyecto de Inversion
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Proyecto de Inversion
        $tabla.=' <h1> <b>PROYECTO : </b><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</small>
                  <h1> <b>SUBACTIVIDAD : </b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cite[0]['proy_id']);
        $tabla.=' <h1><b> ACTIVIDAD : <b><small>'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</small></h1>
                  <h1><b> SUBACTIVIDAD : <b><small>'.$cite[0]['serv_cod'].' '.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</small></h1>';
      }

      //// ------ Monto Presupuesto Programado-Asignado POA
        $monto=$this->ppto($proyecto);
        $tabla.='<h1><b> PPTO. ASIGNADO : <small>'.number_format($monto[1], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;PPTO PROGRAMADO : <small>'.number_format($monto[2], 2, ',', '.').'</small>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;SALDO : <small>'.number_format($monto[3], 2, ',', '.').'</small></b></h1>';
        
      return $tabla;
    }

    /*--- MONTO PRESUPUESTO (2020) ---*/
    public function ppto($proyecto){
      $monto_a=0;$monto_p=0;$monto_saldo=0;
      $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      
      if($proyecto[0]['tp_id']==1){
        $monto_prog=$this->model_ptto_sigep->suma_ptto_pinversion($proyecto[0]['proy_id']);
      }
      else{
        $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
      }

      if(count($monto_asig)!=0){
        $monto_a=$monto_asig[0]['monto'];
      }
      if(count($monto_prog)!=0){
        $monto_p=$monto_prog[0]['monto'];
      }

      $monto[1]=$monto_a; /// Monto Asignado
      $monto[2]=$monto_p; /// Monto Programado
      $monto[3]=($monto_a-$monto_p); /// Saldo

      return $monto;
    }

  /*--- LISTA DE OBJETIVO REGIONAL (GASTO CORRIENTE )-----*/
  public function lista_oregional($proy_id){
    $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
    $tabla='';
    if(count($list_oregional)==1){
      $tabla.=' <section class="col col-3">
                  <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                    <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                  </label>
                </section>'; 
    }
    else{
        $tabla.='<section class="col col-6">
                <label class="label"><b>OBJETIVO REGIONAL</b></label>
                  <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                    <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                    foreach($list_oregional as $row){ 
                      $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                    }
                  $tabla.='
                </select>
              </section>'; 
    }
       
    return $tabla;
  }

  /*---- LISTA DE OBJETIVO REGIONAL (PROYECTO DE INVERSION)-----*/
  public function lista_oregional_pi($proy_id){
    $list_oregional= $this->model_objetivoregion->get_unidad_pregional_programado($proy_id);
    $tabla='';
    if(count($list_oregional)==1){
      $tabla.=' <section class="col col-6">
                  <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                    <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                  </label>
                </section>'; 
    }
    else{
        $tabla.='<section class="col col-6">
                <label class="label"><b>OBJETIVO REGIONAL</b></label>
                  <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                    <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                    foreach($list_oregional as $row){ 
                      $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                    }
                  $tabla.='
                </select>
              </section>'; 
    }
       
    return $tabla;
  }
    /*------- GENERAR MENU --------*/
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