<?php
//// TECHO PRESUPUESTARIO 
class Cmod_requerimientos extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/insumos/minsumos');
            //$this->load->model('programacion/insumos/minsumos_delegado');
            $this->load->model('ejecucion/model_ejecucion');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }


    /*----- */
/*    public function ppto($proyecto){
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
    }*/


    /*----------------- GET REQUERIMIENTO -------------------*/
    public function get_requerimiento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']);
        $proy_id = $this->security->xss_clean($post['proy_id']);

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); /// FASE ACTIVA

        if($proyecto[0]['tp_id']==1){
          $insumo= $this->minsumos->get_requerimiento_actividad($ins_id); /// Datos requerimientos Directo
        }
        else{
          $insumo= $this->minsumos->get_requerimiento($ins_id); /// Datos requerimientos productos
        }

        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$insumo[0]['par_id']);
        $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$insumo[0]['par_id']);
        $lista_partidas=$this->partidas_dependientes($insumo); /// Lista de Insumos dependientes

        if($this->gestion!=2020){ /// 2019
          /// ----------------------------------------------------------------
            $monto_prog=0;
            if(count($prog)!=0){
              $monto_prog=$prog[0]['monto'];
            }

            $saldo=$asig[0]['monto']-$monto_prog;
            $prog=$this->minsumos->get_list_insumo_financiamiento($insumo[0]['insg_id']); /// Temporalidad Presupuestaria
            $par_padre=$this->model_partidas->get_partida_padre($insumo[0]['par_depende']); /// lista de partidas padres

            if(count($prog)==0){
              $prog = array('programado_total' => '0','mes1' => '0','mes2' => '0','mes3' => '0','mes4' => '0','mes5' => '0','mes6' => '0','mes7' => '0','mes8' => '0','mes9' => '0','mes10' => '0','mes11' => '0','mes12' => '0');
            }

            $monto_certificado=0;
            $m_certificado=$this->model_ejecucion->get_ins_certificado($insumo[0]['ins_id'],$insumo[0]['ifin_id']);
            if (count($m_certificado)!=0) {
              $monto_certificado=$m_certificado[0]['certificado'];
            }

            $verf = array('verf_mes1' => '0','verf_mes2' => '0','verf_mes3' => '0','verf_mes4' => '0','verf_mes5' => '0','verf_mes6' => '0','verf_mes7' => '0','verf_mes8' => '0','verf_mes9' => '0','verf_mes10' => '0','verf_mes11' => '0','verf_mes12' => '0');

            for ($i=1; $i <=12 ; $i++) { 
              $iprog=$this->model_modificacion->get_iprog($insumo[0]['ifin_id'],$i);
              if(count($iprog)!=0){
                if (count($this->model_modificacion->get_iprog_cert($iprog[0]['ipm_id']))!=0) {
                  $verf['verf_mes'.$i]=1;
                }
              }
            }

            $verif_cert=0;
            if(count($this->model_ejecucion->verif_insumo_certificado($ins_id))!=0){
              $verif_cert=1;
            }

            if(count($insumo)!=0){
              $result = array(
                'respuesta' => 'correcto',
                'insumo' => $insumo,
                'lista_partidas'=> $lista_partidas,
                'monto_saldo' => $saldo+$insumo[0]['ins_costo_total'],
                'saldo_dif' => $saldo,
                'ppdre' => $par_padre,
                'prog' => $prog,
                'verif_mes' => $verf,
                'monto_certificado'=>$monto_certificado,
                'verif_cert'=>$verif_cert,
              );
            }
            else{
              $result = array(
                'respuesta' => 'error',
              );
            }
          /// ---------------------------------------------------------------
        }
        else{ /// 2020
          /// ---------------------------------------------------------------
          $monto_prog=0;
          if(count($prog)!=0){
            $monto_prog=$prog[0]['monto'];
          }

          $saldo=$asig[0]['monto']-$monto_prog;
          $par_padre=$this->model_partidas->get_partida_padre($insumo[0]['par_depende']); /// lista de partidas padres
          $prog=$this->model_insumo->list_temporalidad_insumo($insumo[0]['ins_id']); /// Temporalidad Requerimiento 2020

          if(count($prog)==0){
            $prog = array('programado_total' => '0','mes1' => '0','mes2' => '0','mes3' => '0','mes4' => '0','mes5' => '0','mes6' => '0','mes7' => '0','mes8' => '0','mes9' => '0','mes10' => '0','mes11' => '0','mes12' => '0');
          }

          $monto_certificado=0;
          $verf = array('verf_mes1' => '0','verf_mes2' => '0','verf_mes3' => '0','verf_mes4' => '0','verf_mes5' => '0','verf_mes6' => '0','verf_mes7' => '0','verf_mes8' => '0','verf_mes9' => '0','verf_mes10' => '0','verf_mes11' => '0','verf_mes12' => '0');
          $verif_cert=0;
          if(count($insumo)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'insumo' => $insumo,
              'lista_partidas'=> $lista_partidas,
              'monto_saldo' => $saldo+$insumo[0]['ins_costo_total'],
              'saldo_dif' => $saldo,
              'ppdre' => $par_padre,
              'prog' => $prog,
              'verif_mes' => $verf,
              'monto_certificado'=>$monto_certificado,
              'verif_cert'=>$verif_cert,
            );
          }
          else{
            $result = array(
              'respuesta' => 'error',
            );
          }
          /// ---------------------------------------------------------------
        }
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- PARTIDAS DEPENDIENTES ---*/
    function partidas_dependientes($insumo){
      $tabla='';
      $get_partida=$this->model_partidas->get_partida($insumo[0]['par_id']); /// datos de la partda
      $lista_partidas=$this->model_partidas->lista_par_hijos($get_partida[0]['par_depende']);
      foreach ($lista_partidas as $row) {
        if($insumo[0]['par_id']==$row['par_id']){
          $tabla.='<option value="'.$row['par_id'].'" selected>'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
        else{
          $tabla.='<option value="'.$row['par_id'].'">'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
      }

      return $tabla;
    }


    /*---------- GET MONTO PARTIDA ------------*/
    public function get_monto_partida(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $par_id = $this->security->xss_clean($post['par_id']);
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

        $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id);  /// Asignado
        $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id); /// Prog
        $monto_prog=0;

        if(count($prog)!=0){
          $monto_prog=$prog[0]['monto'];
        }

        $monto=$asig[0]['monto']-$monto_prog;

        $result = array(
          'respuesta' => 'correcto',
          'monto' => round($monto,2),
        );
  
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*----------- Cite Techo Presupuestario -----------*/
    public function cite_techo($proy_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      $data['titulo_proy'] = strtoupper($data['proyecto'][0]['tipo']);

      $this->load->view('admin/modificacion/techo/cite_ppto', $data);
    }

    /*--------- VALIDA CITES (MODIFICACIONES)----------*/
    public function valida_cite(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
          $cite = $this->security->xss_clean($post['cite']);
          $fecha = $this->security->xss_clean($post['fm']);

          /*--------- GUARDANDO CITE PRESUPUESTO ---------*/
            $data_to_store = array(
              'proy_id' => $proy_id,
              'cppto_cite' => strtoupper($cite),
              'cppto_fecha' => $fecha,
              'fun_id' => $this->fun_id,
              );
            $this->db->insert('ppto_cite',$data_to_store);
            $cppto_id=$this->db->insert_id();
          /*----------------------------------------------*/

          if(count($this->model_ptto_sigep->get_cite_techo($cppto_id))!=0 ){
            redirect(site_url("").'/mod/techo/'.$cppto_id);
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL INGRESAR NRO CITE');
            redirect(site_url("").'/mod/cite_techo/'.$proy_id); 
          }
      }
    }


    /*------------------- Techo Presupuestario -------------------*/
    public function techo($cppto_id){
      $data['cite']=$this->model_ptto_sigep->get_cite_techo($cppto_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
      if(count($data['cite'])!=0 & count($data['proyecto'])!=0){
        $data['menu']=$this->menu(3); //// genera menu
        
        $titulo=' <h1> APERTURA PROGRAM&Aacute;TICA : <small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['proy_sisin'].'000 - '.$data['proyecto'][0]['proy_nombre'].'</small></h1>
                  <h1> CITE : <small>'.$data['cite'][0]['cppto_cite'].'</small> || FECHA : <small>'.date('d/m/Y',strtotime($data['cite'][0]['cppto_fecha'])).'</small></h1>
                  <h1> PRESUPUESTO ASIGNADO - '.$this->gestion.'</h1>';
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);

          $titulo=' <h1> APERTURA PROGRAM&Aacute;TICA : <small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</small></h1>
                  <h1> CITE : <small>'.$data['cite'][0]['cppto_cite'].'</small> || FECHA : <small>'.date('d/m/Y',strtotime($data['cite'][0]['cppto_fecha'])).'</small></h1>
                  <h1> PRESUPUESTO ASIGNADO - '.$this->gestion.'</h1>';
        }


        $data['titulo']=$titulo;
        $data['partidas_asig']=$this->list_partidas($data['proyecto'][0]['proy_id']); /// Partidas Asignadas
        $data['list_partidas']=$this->model_ptto_sigep->list_partidas_noasig($data['proyecto'][0]['aper_id']); /// Aper id

        $this->load->view('admin/modificacion/techo/edit_partidas', $data);
      }
      else{
        redirect(site_url("").'/mod/cite_techo/'.$data['cite'][0]['proy_id']); 
      }
      
    }

    /*------ Lista de Partidas a modificar (2019-220-2021) -------*/
    function list_partidas($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $partidas=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']);
      $total=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
      $monto_total=0;
      if(count($total)!=0){
        $monto_total=$total[0]['monto'];
      }
      $tabla='';
      $nro=0;

      $tabla.='<center>
                <table class="table table-bordered" style="width:80%;" align="center">
                  <tr title="'.$proyecto[0]['aper_id'].'">
                    <td style="width:15%;">
                      <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary nuevo_ff btn-lg" title="NUEVO REGISTRO PARTIDA" style="width:100%; height:100%;">NUEVO PARTIDA</a>
                    </td>
                    <td style="width:5%;">BUSCADOR</td>
                    <td style="width:80%;"><input type="text" class="form-control" id="kwd_search" value="" style="width:100%;"/></td>
                  </tr>
                </table><br>
                <table class="table table-bordered" id="table" style="width:80%;" align="center">
                  <thead>
                    <tr>
                      <th bgcolor="#1c7368"><font color="#ffffff">#</font></th>
                      <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">C&Oacute;DIGO PARTIDA</font></th>
                      <th style="width:15%;"bgcolor="#1c7368"><font color="#ffffff">DESCRIPCI&Oacute;N PARTIDA</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO ASIGNADO INICIAL</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO PROGRAMADO POA</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO A INCREMENTAR.</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO FINAL</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">ELIMINAR</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff" style="width:8%;">SALDO NO EJECUTADO</font></th>
                      <th style="width:20%;" bgcolor="#1c7368"><font color="#ffffff" style="width:10%;">OBSERVACIÓN</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff"></font></th>
                    </tr>
                  </thead>
                  <tbody>';
      foreach($partidas  as $row){
        $programado=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$row['par_id']);
        $monto_poa=0;

        if(count($programado)!=0){
          $monto_poa=$programado[0]['monto'];          
        }

        $nro++;
        $tabla .='
                <tr class="modo1">
                  <td align=center>'.$nro.'<input type="hidden" name="sp_id[]" value="'.$row['sp_id'].'"></td>
                  <td align=center>'.$row['partida'].'</td>
                  <td align=left>'.$row['par_nombre'].'</td>
                  <td align=right><input type="hidden" id="monto'.$nro.'" name="monto_inicial[]" value="'.$row['importe'].'">
                    <b>'.number_format($row['importe'], 2, ',', '.').'</b>
                  </td>
                  <td align=right>
                    <b>'.number_format($monto_poa, 2, ',', '.').'</b>
                  </td>
                  <td align=center><input type="number" class="form-control" onkeyup="suma_monto_partida('.$nro.');" name="monto_dif[]" id="dif'.$nro.'" value="0" title="MONTO A INCREMENTAR" pattern="^[0-9]" pattern="^[0-9]"  min="1" step="1"></td>
                  <td align=center>
                    <input type="text" class="form-control" id="mpartida'.$nro.'" value="'.$row['importe'].'" title="MONTO FINAL" disabled>
                    <input type="hidden" name="monto_partida[]" id="mpartida'.$nro.'" value="'.$row['importe'].'">
                  </td>
                  <td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR MONTO PARTIDA"  name="'.$row['sp_id'].'" id="'.$proy_id.'" ><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>
                  <td align="center" style="width:8%;">';
                    if($row['importe']!=0){
                      $tabla.='<input type="number" class="form-control" id="saldo'.$nro.'" name="saldo'.$nro.'" value="'.round($row['ppto_saldo_ncert'],2).'" onkeyup="verif_monto_saldo('.$row['ppto_saldo_ncert'].',this.value,'.$nro.');" title="SALDO NO EJECUTADO" pattern="^[0-9]" pattern="^[0-9]"  min="1" step="1">';
                    }
                  $tabla.='
                  </td>
                  <td><textarea name="obs_saldo'.$nro.'" id="obs_saldo'.$nro.'" rows="5" class="form-control" cols="25">'.$row['ppto_saldo_observacion'].'</textarea></td>
                  <td align="center">';
                    if($row['importe']!=0){
                      if($row['ppto_saldo_ncert']!=0){
                        $tabla.='<div id="but'.$nro.'" ><button type="button" name="'.$row['sp_id'].'" id="'.$nro.'" onclick="guardar('.$row['sp_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="32" HEIGHT="32"/><br>MODIFICAR</button></div>';
                      }
                      else{
                        $tabla.='<div id="but'.$nro.'" style="display:none;"><button type="button" name="'.$row['sp_id'].'" id="'.$nro.'" onclick="guardar('.$row['sp_id'].','.$nro.');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="32" HEIGHT="32"/><br>GUARDAR</button></div>';
                      }
                      
                    }
                  $tabla.='
                  </td>
                </tr>';
      }
      $tabla.='</tbody>
      <tr>
        <td colspan="3">TOTAL </td>
        <td align=center>'.$monto_total.'</td>
        <td align=center></td>
        <td align=center></td>
        <td align=center><input type="text" class="form-control" name="total" value="'.$monto_total.'" disabled="true"></td>
        <td align=center></td>
        <td align=center></td>
        <td align=center></td>
      </tr>
      
    </table>
    </center>';
      ?>
      <script type="text/javascript">
        function suma_monto_partida(nro){
            monto = parseFloat($('[id="monto'+nro+'"]').val());
            dif = parseFloat($('[id="dif'+nro+'"]').val());
            $('[id="mpartida'+nro+'"]').val((monto+dif).toFixed(2));

            monto_partida = parseFloat($('[id="mpartida'+nro+'"]').val());

            if(isNaN(monto_partida)){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }

            var suma=0;
            var suma_dif=0;
            for (var i = 1; i <= <?php echo count($partidas); ?>; i++) {
              suma=parseFloat(suma)+parseFloat($('[id="mpartida'+i+'"]').val());
            }
     
            $('[name="total"]').val((suma).toFixed(2));
        }

        function suma_monto(){ 
            var suma=0;
            for (var i = 1; i <= <?php echo count($partidas); ?>; i++) {
                suma=parseFloat(suma)+parseFloat($('[id="mpartida'+i+'"]').val());
            }
     
            $('[name="total"]').val((suma).toFixed(2));
        }

        function justNumbers(e){
          var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
               
            return /\d/.test(String.fromCharCode(keynum));
        }
        </script>
      <?php
      return $tabla;
    }


    /*---- VALIDA SALDO NO EJECUTADO ----*/
    public function guardar_saldo_ppto(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $sp_id = $this->security->xss_clean($post['sp_id']);
        $saldo = $this->security->xss_clean($post['saldo']);
        $observacion = $this->security->xss_clean($post['obs']);

          $update_saldo = array(
            'ppto_saldo_ncert' => $saldo,
            'ppto_saldo_observacion' => $observacion
          );
          $this->db->where('sp_id', $sp_id);
          $this->db->update('ptto_partidas_sigep', $update_saldo);
        

        $result = array(
          'respuesta' => 'correcto',
        );
  
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*------------ ADICIONA PARTIDAS (MODIFICACIONES)--------------*/
    public function valida_add_partida(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']);
        $par_id = $this->security->xss_clean($post['par_id']);
        $monto = $this->security->xss_clean($post['monto']);
        $cite=$this->model_ptto_sigep->get_cite_techo($cite_id);
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO
        $partida=$this->model_partidas->dato_par($par_id);

        /*-------- Insert ppto_adicionado ----------*/
        $data_to_store = array(
          'aper_id' => $proyecto[0]['aper_id'],
          'aper_programa' => $proyecto[0]['aper_programa'],
          'aper_proyecto' => $proyecto[0]['aper_proyecto'],
          'aper_actividad' => $proyecto[0]['aper_actividad'],
          'par_id' => $par_id,
          'partida' => $partida[0]['par_codigo'],
          'importe' => $monto,
          'g_id' => $this->gestion,
          'estado' => 1,
          'fun_id' => $this->fun_id,
        );
        $this->db->insert('ptto_partidas_sigep',$data_to_store);
        $sp_id=$this->db->insert_id();
        /*------------------------------------------*/

        /*-------- Insert ppto_modifcado ----------*/
          $data_to_store2 = array(
            'cppto_id' => $cite_id,
            'sp_id' => $sp_id,
            'ppto' => $monto,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('ppto_add',$data_to_store2);
          $appto_id=$this->db->insert_id();
        /*----------------------------------------*/

          $p_add=$this->model_ptto_sigep->get_add_presupuesto($appto_id);
          if(count($p_add)!=0){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE');
            redirect(site_url("").'/mod/techo/'.$cite_id);
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA NUEVA PARTIDA');
            redirect(site_url("").'/mod/techo/'.$cite_id);
          }

      }
      else{
        echo "<center><font color=red>Error al Registrar la Nueva Partida</font></center>";
      }
    }


    /*------------ UPDATE PARTIDAS (MODIFICACIONES)--------------*/
    public function valida_update_partidas_mod(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $this->security->xss_clean($post['cite_id']);
          $cite=$this->model_ptto_sigep->get_cite_techo($cite_id);

          $nro=0;
          if (!empty($_POST["sp_id"]) && is_array($_POST["sp_id"]) ) {
          foreach ( array_keys($_POST["sp_id"]) as $como){
            if($_POST["monto_dif"][$como]!=0){
               /*-------- Insert ppto_modifcado ----------*/
              $data_to_store2 = array(
                'cppto_id' => $cite_id,
                'sp_id' => $_POST["sp_id"][$como],
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'ppto_ini' => $_POST["monto_inicial"][$como],
                'monto_dif' => $_POST["monto_dif"][$como],
                'ppto_final' => $_POST["monto_partida"][$como],
              );
              $this->db->insert('ppto_mod',$data_to_store2);
              $cppto_id=$this->db->insert_id();
              /*----------------------------------------*/

              /*--------- Update ppto Sigep ----------*/
              $update_ppto= array(
                'importe' => $_POST["monto_partida"][$como],
                'estado' => 2,
                'fun_id' => $this->fun_id
              );
              $this->db->where('sp_id', $_POST["sp_id"][$como]);
              $this->db->update('ptto_partidas_sigep', $this->security->xss_clean($update_ppto));
              /*----------------------------------------*/
              $nro++;
            }
          }

          $this->session->set_flashdata('success','SE MODIFICARON '.$nro.' MONTO DE PARTIDAS');
          redirect(site_url("").'/mod/techo/'.$cite_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL MODIFICAR PARTIDAS');
          redirect(site_url("").'/mod/techo/'.$cite_id);
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Operaciones</b></font>";
      }
    }


    /*-------- ELIMINAR MONTOS PARTIDA --------*/
    function delete_partida(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $sp_id = $this->security->xss_clean($post['sp_id']);
          $cite_id = $this->security->xss_clean($post['cite_id']);

          /*-------- Delete ppto_eliminado ----------*/
            $data_to_store2 = array(
              'cppto_id' => $cite_id,
              'sp_id' => $sp_id,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            );
            $this->db->insert('ppto_del',$data_to_store2);
            $dppto_id=$this->db->insert_id();
            /*----------------------------------------*/

            /*--------- Update ppto Sigep ----------*/
            $update_ppto= array(
              'estado' => 3,
              'fun_id' => $this->fun_id
            );
            $this->db->where('sp_id', $sp_id);
            $this->db->update('ptto_partidas_sigep', $this->security->xss_clean($update_ppto));
            /*----------------------------------------*/

          $result = array(
            'respuesta' => 'correcto'
          );

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*------------- REPORTE CITE TECHO -------------*/
    public function reporte_techo($cppto_id){
      $data['cite']=$this->model_ptto_sigep->get_cite_techo($cppto_id);
      if(count($data['cite'])!=0){
        $data['proyecto']=$this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['mes'] = $this->mes_nombre();
        $data['lista']=$this->mis_modificaciones_techo($cppto_id);

        $this->load->view('admin/modificacion/techo/reporte_mod_techo', $data);
      }
      else{
        echo "ERROR";
      }

    }

    /*--------- REPORTE MODIFICACION TECHO ---------*/
    function mis_modificaciones_techo($cppto_id){
      $tabla='';
      $cite=$this->model_ptto_sigep->get_cite_techo($cppto_id);
      $proyecto=$this->model_proyecto->get_id_proyecto($cite[0]['proy_id']);
      $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);

      $add=$this->model_ptto_sigep->partida_add_techo($cppto_id); // add
      $mod=$this->model_ptto_sigep->partida_mod_techo($cppto_id); // mod
      $del=$this->model_ptto_sigep->partida_del_techo($cppto_id); // del

      if(count($add)!=0){
        $sum=0;
        $tabla.='
              <table border="0" style="width:81%;" align="center">
                <tr>
                  <td style="width:97%; font-size: 8pt;" text-align: left;">PARTIDAS AGREGADAS ('.count($add).')</td>
                </tr>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                <thead>
                  <tr class="modo1" align="center">
                    <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">C&Oacute;DIGO</th>
                    <th style="width:55%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO INICIAL</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO MODIFICADO</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO ACTUAL</th> 
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($add  as $row){
                  $sum=$sum+$row['ppto'];
                  $nro++;
                  $tabla.='
                  <tr class="modo1">
                    <td style="width: 2%; text-align: left;" style="height:11px;">'.$nro.'</td>
                    <td style="width: 10%; text-align: left;">'.$row['par_codigo'].'</td>
                    <td style="width: 55%; text-align: left;">'.$row['par_nombre'].'</td>
                    <td style="width: 15%; text-align: right;">0.00</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['ppto'], 2, ',', '.').'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['ppto'], 2, ',', '.').'</td>
                  </tr>';
                }
                $tabla.='
                  <tr>
                    <td colspan=3>MONTO TOTAL : </td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">'.number_format($sum, 2, ',', '.').'</td>
                  </tr>';
        $tabla.='</tbody>
              </table><br>';
      }

      if(count($mod)!=0){
        $sum=0;
        $tabla.='
              <table border="0" style="width:81%;" align="center">
                <tr>
                  <td style="width:97%; font-size: 8pt;" text-align: left;">PARTIDAS MODIFICADAS ('.count($mod).')</td>
                </tr>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                <thead>
                  <tr class="modo1" align="center">
                    <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">C&Oacute;DIGO</th>
                    <th style="width:55%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO INICIAL</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO MODIFICADO</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO ACTUAL</th> 
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($mod  as $row){
                  $sum=$sum+$row['importe'];
                  $nro++;
                  $tabla.='
                  <tr class="modo1">
                    <td style="width: 2%; text-align: left;" style="height:11px;">'.$nro.'</td>
                    <td style="width: 10%; text-align: left;">'.$row['par_codigo'].'</td>
                    <td style="width: 55%; text-align: left;">'.$row['par_nombre'].'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['ppto_ini'], 2, ',', '.').'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['monto_dif'], 2, ',', '.').'</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['ppto_final'], 2, ',', '.').'</td>
                  </tr>';
                }
                $tabla.='
                  <tr>
                    <td colspan=3>MONTO TOTAL : </td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">'.number_format($sum, 2, ',', '.').'</td>
                  </tr>';
        $tabla.='</tbody>
              </table><br>';
      }

      if(count($del)!=0){
        $sum=0;
        $tabla.='
              <table border="0" style="width:81%;" align="center">
                <tr>
                  <td style="width:97%; font-size: 8pt;" text-align: left;">PARTIDAS ELIMINADAS ('.count($del).')</td>
                </tr>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                <thead>
                  <tr class="modo1" align="center">
                    <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">C&Oacute;DIGO</th>
                    <th style="width:65%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO INICIAL</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO MODIFICADO</th> 
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">PPTO ACTUAL</th> 
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($del as $row){
                  $sum=$sum+$row['importe'];
                  $nro++;
                  $tabla.='
                  <tr class="modo1">
                    <td style="width: 2%; text-align: left;" style="height:11px;">'.$nro.'</td>
                    <td style="width: 10%; text-align: left;">'.$row['par_codigo'].'</td>
                    <td style="width: 55%; text-align: left;">'.$row['par_nombre'].'</td>
                    <td style="width: 15%; text-align: right;">0.00</td>
                    <td style="width: 15%; text-align: right;">0.00</td>
                    <td style="width: 15%; text-align: right;">'.number_format($row['importe'], 2, ',', '.').'</td>
                  </tr>';
                }
                $tabla.='
                  <tr>
                    <td colspan=3>MONTO TOTAL : </td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;">'.number_format($sum, 2, ',', '.').'</td>
                  </tr>';
        $tabla.='</tbody>
              </table><br>';
      }

      $tabla .='<table border="0" style="width:80%;" align="center">
                  <tr>
                    <td colspan=2><hr></td>
                  </tr>
                  <tr>
                    <td style="width:70%; font-size: 8pt;" text-align: left;">TOTAL TECHO PRESUPUESTARIO : </td>
                    <td style="width:30%; font-size: 8pt;"><div align="right">'.number_format($monto_asig[0]['monto'], 2, ',', '.').'</div></td>
                  </tr>
                </table>';
      return $tabla;
    }


    /*------------------------ Nuevo Insumo (2018) ----------------------*/
    function add_requerimiento($cite_id,$tipo_insumo, $proy_id, $tp_ins){
        $data['menu']=$this->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); //// Datos del Proyecto
        $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
        $data['titulo_proy']=strtoupper($data['proyecto'][0]['tipo']);
        $data['cite'] = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
        $data['titulo']=$this->model_modificacion->mis_op($data['proyecto'][0]['proy_act'],$data['fase'][0]['pfec_ejecucion'],$tp_ins);
        $data['gestiones'] = $data['fase'][0]['pfec_fecha_fin']-$data['fase'][0]['pfec_fecha_inicio']+1; //// Nro de Gestione en funcion a su fase activa
        $data['ins_titulo'] = 'NUEVO INSUMO' . $this->get_ins_titulo($tipo_insumo);
        $data['ins_tipo'] = $tipo_insumo;
        $data['gestion'] = $this->gestion;

          $data['techo'] = $this->minsumos->tabla_presupuesto($proy_id, $this->gestion);//techo presupuestario
          $data['saldo_total'] = $this->minsumos->saldo_total_fin($proy_id, $this->gestion);//SALDO TOTAL DEL TECHO PRESUPUESTARIO
          $data['lista_entidad'] = $this->model_entidad_tras->lista_entidad_tras();//entidad transferencia
          $data['lista_cargo'] = $this->minsumos->lista_cargo();
          $data['sumatorias'] = $this->suma_total_fuentes($data['fase'][0]['id'], $this->gestion,$data['fase'][0]['pfec_ejecucion'],$data['proyecto'][0]['proy_act']); //// Suma Total, Asignado, Programado, SaldoAsignado, Programado, Saldo
          $data['lista_partidas'] = $this->model_partidas->lista_padres();//partidas 

          if($tipo_insumo==1 || $tipo_insumo==2 || $tipo_insumo==3 || $tipo_insumo==4 || $tipo_insumo==5 || $tipo_insumo==6 || $tipo_insumo==7 || $tipo_insumo==8 || $tipo_insumo==9){
              $this->load->view('admin/modificacion/operaciones/requerimientos/add_requerimiento', $data);
          }
          else{ 
              redirect(site_url("") . '/prog/ins_prod/'.$proy_id.'/'.$com_id.'/'.$prod_id.'/false');
          }
    }

    /*---------- VALIDA ADD REQUERIMIENTO (2019)---------*/
     public function valida_add_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp_ins = $this->security->xss_clean($post['tp_ins']); /// prod id,act id, com id
        $cite_id = $this->security->xss_clean($post['cite_id']); /// cite id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $detalle = $this->security->xss_clean($post['ins_detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['ins_cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['ins_costo_u']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costo']); /// costo Total
        $unidad = $this->security->xss_clean($post['ins_u_medida']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['partida_id']); /// costo unitario
        $observacion = $this->security->xss_clean($post['ins_observacion']); /// Observacion

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
        $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
        $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

        if(count($fuentes)==1){

          $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
          'ins_codigo' => $this->session->userdata("name").'/REQ-PROD/'.$this->gestion, /// Codigo Insumo
          'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
          'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
          'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
          'ins_costo_unitario' => $costo_unitario, /// Costo Unitario
          'ins_costo_total' => $costo_total, /// Costo Total
          'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
          'par_id' => $partida, /// Partidas
          'ins_observacion' => strtoupper($observacion), /// Observacion
          'fun_id' => $this->fun_id, /// Funcionario
          'aper_id' => $proyecto[0]['aper_id'], /// aper id
          'ins_tipo' => 1, /// tipo
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
          $ins_id=$this->db->insert_id();

          if($proyecto[0]['tp_id']==1){
            /*--------------------------------------------------------*/
            $data_to_store2 = array( ///// Tabla _insumoactividad
              'act_id' => $tp_ins, /// act id
              'ins_id' => $ins_id, /// ins_id
            );
            $this->db->insert('_insumoactividad', $data_to_store2);
           /*----------------------------------------------------------*/
          }
          else{
            /*--------------------------------------------------------*/
            $data_to_store2 = array( ///// Tabla InsumoProducto
              'prod_id' => $tp_ins, /// prod id
              'ins_id' => $ins_id, /// ins_id
            );
            $this->db->insert('_insumoproducto', $data_to_store2);
           /*----------------------------------------------------------*/
          }

          $data_to_store = array( 
          'ins_id' => $ins_id, /// Id Insumo
          'g_id' => $this->gestion, /// Gestion
          'insg_monto_prog' => $costo_total, /// Monto programado
          );
          $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
          $insg_id=$this->db->insert_id();

          /*------------------- Guardando Fuente Financiamiento ------*/
          $query=$this->db->query('set datestyle to DMY');
          $data_to_store3 = array( 
          'insg_id' => $insg_id, /// Id Insumo gestion
          'ifin_monto' => $costo_total, /// Monto programado
          'ifin_gestion' => $this->gestion, /// Gestion
          'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
          'ff_id' => $fuentes[0]['ff_id'], /// ff id
          'of_id' => $fuentes[0]['of_id'], /// ff id
          'nro_if' => 1, /// Nro if
          );
          $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
          $ifin_id=$this->db->insert_id();

          for ($i=1; $i <=12 ; $i++) {
            $pfin=$this->security->xss_clean($post['m'.$i]);
            if($pfin!=0){
                $data_to_store4 = array( 
                  'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                  'mes_id' => $i, /// Mes 
                  'ipm_fis' => $pfin, /// Valor mes
                  );
                $this->db->insert('ifin_prog_mes', $data_to_store4);
            }
          }

          /*----------- iNSERT AUDI ADICIONAR INSUMOS ---------*/
            $data_to_store2 = array(
              'ins_id' => $ins_id, /// ins_id
              'insc_id' => $cite_id, /// cite_id
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->session->userdata("fun_id"),
              );
            $this->db->insert('_insumo_add', $data_to_store2);
            $insa_id=$this->db->insert_id();
          /*----------------------------------------------------*/
            
            $this->crea_actualiza_codigo($proyecto,$cite_id);

          /*-----------------------------------------------------------*/
            if(count($this->model_modificacion->get_add_insumo($insa_id))==1){
              $this->session->set_flashdata('success','EL REQUERIMIENTO SE REGISTRO CORRECTAMENTE :)');
            }
            else{
              $this->session->set_flashdata('danger','EL REQUERIMIENTO NOSE REGISTRO CORRECTAMENTE, VERIFIQUE DATOS :(');
            }
        }
        else{
          $this->session->set_flashdata('danger','ERROR !!, VERIFIQUE DATOS :(');
        }

        redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'');

            
      } else {
          show_404();
      }
    }

    /*--- CREA-ACTUALIZA CODIGO DE MODIFICACION  ---*/
    public function crea_actualiza_codigo($proyecto,$cite_id){
      $cite=$this->model_modificacion->get_cite_insumo($cite_id); /// CITE
      $cod=$this->genera_codigo($proyecto);
      if($this->verif_cite($cite_id)==1){
        if($cite[0]['insc_codigo']==''){
          /*-- Actualizando Codigo Modificacion --*/
          $certificacionpoa = array(
            'insc_codigo' => $cod[1],
          );
          $this->db->where('insc_id', $cite_id);
          $this->db->update('_insumo_mod_cite', $certificacionpoa);

        /*-- Actualizando Nro. mod x regional --*/
          $modificaciones = array(
            'mod_ope' => $cod[2],
          );
          $this->db->where('dep_id', $proyecto[0]['dep_id']);
          $this->db->where('g_id', $this->gestion);
          $this->db->update('mod_req_regionales', $modificaciones);

        /*-- Actualizando Configuracion ---*/
          $conf = array(
            'conf_mod_req' => $cod[3],
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $conf);
        }
      }
      else{
        /*-- Actualizando Codigo Modificacion --*/
          $certificacionpoa = array(
            'insc_codigo' => $cod[1],
          );
          $this->db->where('insc_id', $cite_id);
          $this->db->update('_insumo_mod_cite', $certificacionpoa);

        /*-- Actualizando Nro. mod x regional --*/
          $modificaciones = array(
            'mod_ope' => $cod[2],
          );
          $this->db->where('dep_id', $proyecto[0]['dep_id']);
          $this->db->where('g_id', $this->gestion);
          $this->db->update('mod_req_regionales', $modificaciones);

        /*-- Actualizando Configuracion ---*/
          $conf = array(
            'conf_mod_req' => $cod[3],
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $conf);
      }
    }

    /*--- GENERA CÓDIGO MODIFICACIÓN REQUERIMIENTOS ---*/
    public function genera_codigo($proyecto){
      $conf=$this->model_proyecto->configuracion_session(); /// Configuracion
      $cod_mod='';$nro_cdep='0'; $nro_cnal='0'; $nro=0;

      $v[1]=0;$v[2]=0;$v[3]=0;
      $verif_mod=$this->model_modificacion->verif_mod_req($proyecto[0]['dep_id']);
      if(count($verif_mod)!=0){
        if($verif_mod[0]['mod_ope']<100){
          $nro_cdep='00';
        }
        elseif($verif_mod[0]['mod_ope']<10) {
          $nro_cdep='0';
        }

        $nro=$verif_mod[0]['mod_ope']+1;
      }
      else{
        $nro=1;
        $data_to_store = array( 
          'dep_id' => $proyecto[0]['dep_id'],
          'g_id' => $this->gestion,
        );
        $this->db->insert('mod_req_regionales', $data_to_store);
      }
       $v[2]=$nro;
      
      if($conf[0]['conf_mod_req']<1000){
        $nro_cnal='000';
      }
      elseif($conf[0]['conf_mod_req']<100){
        $nro_cnal='00';
      }
      elseif($conf[0]['conf_mod_req']<10){
        $nro_cnal='0';
      }
      $v[3]=$conf[0]['conf_mod_req']+1;

      $cod_mod="REQ-".$proyecto[0]['dep_sigla']."-".$nro_cdep."".$v[2]."-".$nro_cnal."".$v[3];
      $v[1]=$cod_mod;

      return $v;
    }    

    /*--------- VERIFICA SI SE TIENE ALGUN REGISTRO (ABM) --------*/
    public function verif_cite($cite_id){
      $cite=$this->model_modificacion->get_cite_insumo($cite_id); /// CITE
      $proyecto=$this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// PROYECTO

      $ca=$this->model_modificacion->cite_add($cite_id);
      $cm=$this->model_modificacion->cite_mod($cite_id);
      $cd=$this->model_modificacion->ins_del($cite_id);

      $sw=0;
      if(count($ca)!=0 || count($cm) || count($cd)){
        $sw=1;
      }

      return $sw;
    }

   

    /*--- GUARDA REQUERIMIENTO A TABLA TEMPORAL (2020) ---*/
     public function valida_insumo_temporal(){
      if($this->input->post()) {
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $cite_id = $this->security->xss_clean($post['cite_id']); /// cite id
        $tp_ins = $this->security->xss_clean($post['tp_ins']); /// tp ins id
        $tp_mod = $this->security->xss_clean($post['tp_mod']); /// tp mod

        $insumo = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL REQUERIMIENTO

        $cite = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO

        $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'ins_codigo' => $insumo[0]['ins_codigo'], /// Codigo Insumo
            'ins_fecha_requerimiento' => $insumo[0]['ins_fecha_requerimiento'], /// Fecha de Requerimiento
            'ins_detalle' => $insumo[0]['ins_detalle'], /// Insumo Detalle
            'ins_cant_requerida' => $insumo[0]['ins_cant_requerida'], /// Cantidad Requerida
            'ins_costo_unitario' => $insumo[0]['ins_costo_unitario'], /// Costo Unitario
            'ins_costo_total' => $insumo[0]['ins_costo_total'], /// Costo Total
            'ins_unidad_medida' => $insumo[0]['ins_unidad_medida'], /// Insumo Unidad de Medida
            'ins_tipo' => $insumo[0]['ins_tipo'], /// Ins Tipo
            'par_id' => $insumo[0]['par_id'], /// Partidas
            'ins_observacion' => $insumo[0]['ins_observacion'], /// Ins Observacion
            'fun_id' => $insumo[0]['fun_id'], /// Funcionario
            'aper_id' => $proyecto[0]['aper_id'], /// aper id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos_historial', $data_to_store); ///// Guardar en Tabla Insumos 
          $insh_id=$this->db->insert_id();



      }
      else{
        echo "Error al guardar !!!!";
      }
    }

    /*--- VALIDA UPDATE REQUERIMIENTO (2018 - 2019 - 2020) ---*/
     public function valida_update_insumo(){
      if($this->input->post()) {
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']); /// Ins id
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id
        $cite_id = $this->security->xss_clean($post['cite_id']); /// cite id
        $tp_ins = $this->security->xss_clean($post['tp_ins']); /// tp ins id


        $detalle = $this->security->xss_clean($post['detalle']); /// detalle
        $cantidad = $this->security->xss_clean($post['cantidad']); /// cantidad
        $costo_unitario = $this->security->xss_clean($post['costou']); /// costo unitario
        $costo_total = $this->security->xss_clean($post['costot']); /// costo Total
        $unidad = $this->security->xss_clean($post['umedida']); /// Unidad de medida
        $partida = $this->security->xss_clean($post['par_hijo']); /// costo unitario
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); /// FASE ACTIVA

        if($this->gestion!=2020){ /// 2019
            $insumo_gestion=$this->minsumos->get_insumo_gestion($ins_id,$this->gestion); /// INSUMO GESTIÓN
            $insumo_fin=$this->minsumos->list_insumo_financiamiento($insumo_gestion[0]['insg_id']); /// INSUMO FINANCIAMIENTO

            if($this->copia_insumo($cite_id,$ins_id)){
              /*-------- UPDATE REQUERIMIENTO -------*/
              if(count($this->model_ejecucion->verif_insumo_certificado($ins_id))!=0){
                $update_ins= array(
                  'ins_cant_requerida' => $cantidad,
                  'fun_id' => $this->fun_id,
                  'ins_mod' => 2, /// mod
                  'ins_estado'=> 2, /// mod
                  'num_ip' => $this->input->ip_address(), 
                  'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
              }
              else{
                $update_ins= array(
                  'ins_cant_requerida' => $cantidad,
                  'ins_costo_unitario' => $costo_unitario,
                  'ins_costo_total' => $costo_total,
                  'ins_detalle' => $detalle,
                  'par_id' => $partida, /// Partidas
                  'ins_unidad_medida' => $unidad,
                  'ins_observacion' => $observacion,
                  'fun_id' => $this->fun_id,
                  'ins_mod' => 2, /// mod
                  'ins_estado'=> 2, /// mod
                  'num_ip' => $this->input->ip_address(), 
                  'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
              }
              $this->db->where('ins_id', $ins_id);
              $this->db->update('insumos', $this->security->xss_clean($update_ins));
            /*-----------------------------------------*/

            /*------ UPDATE REQUERIMIENTO GESTION -----*/
              $update_insg= array(
                'insg_monto_prog' => $costo_total,
                'insg_estado' => 2
              );
              $this->db->where('insg_id', $insumo_gestion[0]['insg_id']);
              $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
            

            /*------ UPDATE INSUMO FINANCIAMIENTO -----*/
              $update_infin= array(
                'ifin_monto' => $costo_total
              );
              $this->db->where('insg_id', $insumo_gestion[0]['insg_id']);
              $this->db->update('insumo_financiamiento', $this->security->xss_clean($update_infin));
            /*-----------------------------------------*/

            /*------------------- Temporalidad -------------------*/
            for ($i=1; $i <=12 ; $i++) {
                if(!is_null ($post['mm'.$i])){
                  $verif_mes=$this->model_modificacion->get_iprog($insumo_fin[0]['ifin_id'],$i);
                  if(count($verif_mes)!=0){
                    $pfin=$this->security->xss_clean($post['mm'.$i]);
                    if($pfin==0){
                      /*----------------- ELIMINA IFIN PROG MES---------------*/
                        $this->db->where('ifin_id', $insumo_fin[0]['ifin_id']);
                        $this->db->where('ipm_id', $verif_mes[0]['ipm_id']);
                        $this->db->where('mes_id', $i);
                        $this->db->delete('ifin_prog_mes');
                      /*------------------------------------------------------*/
                    }
                    else{
                      /*----------------- UPDATE IFIN PROG MES---------------*/
                        $update_ifin = array(
                          'ipm_fis' => $pfin
                        );
                        $this->db->where('mes_id', $i);
                        $this->db->where('ipm_id', $verif_mes[0]['ipm_id']);
                        $this->db->update('ifin_prog_mes', $update_ifin);
                      /*------------------------------------------------------*/
                    }
                  }
                  else{
                    $pfin=$this->security->xss_clean($post['mm'.$i]);
                    if($pfin!=0){
                        /*----------------- INSERT IFIN PROG MES---------------*/
                        $data_to_store2 = array( ///// Tabla ifin_prog_mes
                          'ifin_id' => $insumo_fin[0]['ifin_id'], /// Id Insumo Fin
                          'mes_id' => $i, /// Mes Id
                          'ipm_fis' => $pfin, /// Programado
                        );
                        $this->db->insert('ifin_prog_mes', $data_to_store2);
                        /*------------------------------------------------------*/
                    }
                    
                  }

                }
              }
              /*----------------------------------------------------------------*/
              
              $this->crea_actualiza_codigo($proyecto,$cite_id);

              $get_ins=$this->minsumos->get_requerimiento($ins_id);
              $this->session->set_flashdata('success','EL REQUERIMIENTO SE MODIFICO CORRECTAMENTE :)');
              redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$tp_ins.'');
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL MODIFICAR REQUERIMIENTO :(');
              redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$tp_ins.'');
            }
        }
        else{ /// 2020
          if($this->copia_insumo($cite_id,$ins_id)){

            $update_ins= array(
              'ins_cant_requerida' => $cantidad,
              'ins_costo_unitario' => $costo_unitario,
              'ins_costo_total' => $costo_total,
              'ins_detalle' => $detalle,
              'par_id' => $partida, /// Partidas
              'ins_unidad_medida' => $unidad,
              'ins_observacion' => $observacion,
              'fun_id' => $this->fun_id,
              'ins_mod' => 2, /// mod
              'ins_estado'=> 2, /// mod
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
            );
            $this->db->where('ins_id', $ins_id);
            $this->db->update('insumos', $this->security->xss_clean($update_ins));


            /*-------- DELETE INSUMO PROGRAMADO --------*/  
            $this->db->where('ins_id', $ins_id);
            $this->db->delete('temporalidad_prog_insumo');
            /*------------------------------------------*/ 

            for ($i=1; $i <=12 ; $i++) {
              $pfin=$this->security->xss_clean($post['mm'.$i]);
              if($pfin!=0){
                  $data_to_store4 = array( 
                    'ins_id' => $ins_id, /// Id Insumo
                    'mes_id' => $i, /// Mes 
                    'ipm_fis' => $pfin, /// Valor mes
                  );
                  $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
              }
            }

          }
        }



        
       

      } else {
          show_404();
      }
    }

    /*------- ELIMINA TODOS LOS REQUERIMIENTOS DEL PRODUCTO/ACTIVIDAD -------*/
    public function elimina_requerimientos_producto_actividad($cite_id,$tp_id){
      $cite = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
      $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']); /// Fase Activa

      if($proyecto[0]['tp_id']==1){
        $insumos=$this->model_actividad->insumo_actividad($tp_id);
      }
      else{
        $insumos=$this->model_producto->insumo_producto($tp_id);
      }

      $nro=0;
      foreach ($insumos as $row) {
        $nro++;
        /*------------ UPDATE REQUERIMIENTO -------*/
        $update_ins = array(
          'ins_estado' => 3, /// 3 : Eliminado
          'ins_mod' => 2, /// 2 : Modulo Modificaciones
          'aper_id' => 0, /// 2 : aper
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          'fun_id' => $this->fun_id
        );
        $this->db->where('ins_id', $row['ins_id']);
        $this->db->update('insumos', $update_ins);
        /*-----------------------------------------*/
        /*------------ Insumo Gestion -------------*/
        /*$update_insg= array(
          'insg_estado' => 3
        );
        $this->db->where('ins_id', $row['ins_id']);
        $this->db->update('insumo_gestion', $update_insg);*/

        /*---------- Insert Insumo Delete ----------*/
        $data_to_store = array( 
          'ins_id' => $row['ins_id'], /// Insumo Id
          'insc_id' => $cite_id, /// Cite Id
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          'fun_id' => $this->fun_id,
        );
        $this->db->insert('_insumo_delete', $data_to_store);
        $dlte_id=$this->db->insert_id();
      /*----------------------------------------------*/
      }
      
      $this->crea_actualiza_codigo($proyecto,$cite_id);

      $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE : '.$nro.' REQUERIMIENTOS');
      redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$cite[0]['proy_id'].'/'.$tp_id.''); 
    }

    /*------------------- Update Requerimiento (2018) -------------------*/
    public function update_requerimiento($cite_id,$proy_id,$tp_ins,$ins_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
      $data['gestiones'] = $data['fase'][0]['pfec_fecha_fin']-$data['fase'][0]['pfec_fecha_inicio']+1; //// Nro de Gestione en funcion a su fase activa
      $titulo_proy=strtoupper($data['proyecto'][0]['tipo']);
      $data['titulo_proy'] = $titulo_proy;
      $data['cite'] = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
      $data['titulo']=$this->model_modificacion->mis_op($data['proyecto'][0]['proy_act'],$data['fase'][0]['pfec_ejecucion'],$tp_ins);
      /*---------------------- Datos del Insumo Programado --------------------*/
      $data['insumo'] = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL INSUMO
      $data['lista_partidas'] = $this->model_partidas->lista_padres();// LISTA DE PARTIDAS
      $data['lista_cargo'] = $this->minsumos->lista_cargo();
      $data['insumo_programdo'] = $this->model_modificacion->insumo_programado_gestion($ins_id); //// TENPORALIDAD INSUMO PROGRAMADO
      $insumo_gestion_actual = $this->minsumos->get_insumo_gestion($ins_id,$this->gestion); //// GET INSUMO GESTION
      if(count($insumo_gestion_actual)!=0){
          $monto_programado=$insumo_gestion_actual[0]['insg_monto_prog'];
      }
      else{
          $monto_programado=0;
      }
      $data['monto_programado']=$monto_programado;      
      $sumatorias = $this->suma_total_fuentes($data['fase'][0]['id'], $this->gestion,$data['fase'][0]['pfec_ejecucion'],$data['proyecto'][0]['proy_act']); //// Suma Total, Asignado, Programado, SaldoAsignado, Programado, Saldo
      $insumo_gestion_programado = $this->minsumos->suma_dato_insumo_programado($ins_id,$this->gestion); //// DATOS DEL INSUMO PROGRAMADO

      $suma_programado=0;
      if(count($insumo_gestion_programado)!=0){$suma_programado=$insumo_gestion_programado[0]['suma'];}
        $data['saldo_por_programar']=$sumatorias[1]-$sumatorias[2]+$suma_programado;

      $data['detalle']=$this->tipo_requerimiento($data['insumo'][0]['ins_tipo']);
      $this->load->view('admin/modificacion/operaciones/requerimientos/update_requerimiento', $data);
    }

    /*----------------- Update Temporalidad ---------------------------*/


    /*----------------- Add Temporalidad ---------------------------*/
   /* public function add_temporalidad($cite_id,$proy_id,$tp_ins,$ins_id,$insg_id){
      $data['menu']=$this->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      
      $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
      $data['insumo'] = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL INSUMO
      $data['insumo_gest'] = $this->minsumos->get_dato_insumo_gestion($insg_id,$ins_id); //// DATOS DEL INSUMO GESTION
      $data['list_ig'] = $this->minsumos->list_insumos_gestion($ins_id); //// LISTA DE INSUMOS GESTIONES

      $titulo_proy=strtoupper($data['proyecto'][0]['tipo']);
      $data['cite'] = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
      $data['titulo_proy'] = $titulo_proy;   /// Titulo Tipo de la Operacion
      $data['titulo']=$this->model_modificacion->mis_op($data['proyecto'][0]['proy_act'],$data['fase'][0]['pfec_ejecucion'],$tp_ins);
      $data['gestion'] = $data['insumo_gest'][0]['g_id'];

      //----------------------------------------------------------------------------------------------------------------------------
        $data['lista_fuentes_techo'] = $this->minsumos_delegado->tabla_presupuesto($proy_id, $data['insumo_gest'][0]['g_id']);//lista de fuentes y su presupuesto asignado
        $data['saldo_total'] = $this->minsumos_delegado->saldo_total_fin($proy_id, $data['insumo_gest'][0]['g_id']);//SALDO TOTAL DEL TECHO PRESUPUESTARIO
        $data['lista_entidad'] = $this->model_entidad_tras->lista_entidad_tras();//entidad transferencia

        $fase_gest = $this->model_faseetapa->fase_etapa_gestion($data['fase'][0]['id'],$data['insumo_gest'][0]['g_id']); //// Lista de las gestiones de la Fase
        $data['list_fuentes'] = $this->model_faseetapa->fase_presupuesto_id($fase_gest[0]['ptofecg_id']); //// lista del presupuesto asignado

          $vmes[1]='mes1';
          $vmes[2]='mes2';
          $vmes[3]='mes3';
          $vmes[4]='mes4';
          $vmes[5]='mes5';
          $vmes[6]='mes6';
          $vmes[7]='mes7';
          $vmes[8]='mes8';
          $vmes[9]='mes9';
          $vmes[10]='mes10';
          $vmes[11]='mes11';
          $vmes[12]='mes12';

        $data['vmes']=$vmes;
        $data['atras']='';
        $this->load->view('admin/modificacion/operaciones/requerimientos/add_temporalizacion', $data);
    }*/

    /*------------------------- Valida Nuevo Requerimiento ------------------------*/
     function valida_add_requerimiento(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $cite_id = $post['cite_id'];//cite id
            $proy_id = $post['proy_id'];//proy id
            $tp_ins = $post['tp_ins'];//com id,prod id, act id
            $ins_tipo = $post['ins_tipo'];//tipo de insumo

            $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
            $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
            $conf=$this->model_proyecto->configuracion();
            
            $veriff = $this->verif_fecha($post['ins_fecha']); //// verifica de requerimiento
            
            if($ins_tipo>=1 && $ins_tipo<=9){
                if($veriff=='true'){
                  /*------------------------------------------------------------------------------------*/
                    if($ins_tipo==1){
                      $nro_ins=$conf[0]['conf_rrhhp']+1;

                      $update_conf = array('conf_rrhhp' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/RHP/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );

                  }
                  elseif($ins_tipo==2){
                      $nro_ins=$conf[0]['conf_servicios']+1;

                      $update_conf = array('conf_servicios' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/SERV/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );

                  }
                  elseif($ins_tipo==3){
                      $nro_ins=$conf[0]['conf_pasajes']+1;

                      $update_conf = array('conf_pasajes' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/PAS/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==4){
                      $nro_ins=$conf[0]['conf_viaticos']+1;

                      $update_conf = array('conf_viaticos' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/VIA/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==5){
                      $nro_ins=$conf[0]['conf_cons_producto']+1;

                      $update_conf = array('conf_cons_producto' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/CP/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_objetivo' => strtoupper($post['objetivo']), /// Insumo Objetivo
                      'ins_duracion' => $post['ins_duracion'], /// Insumo Duracion
                      'ins_fecha_inicio' => $post['ins_i'], /// Fecha de Inicio
                      'ins_fecha_conclusion' => $post['ins_f'], /// Fecha Final
                      'ins_productos' => strtoupper($post['ins_prod']), /// Insumo Producto
                      'ins_evaluador' => strtoupper($post['ins_eva']), /// Insumo Evaluacion
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => 'SERVICIO', /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==6){
                      $nro_ins=$conf[0]['conf_cons_linea']+1;

                      $update_conf = array('conf_cons_linea' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/CL/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_duracion' => $post['ins_duracion'], /// Insumo Duracion
                      'ins_fecha_inicio' => $post['ins_i'], /// Fecha de Inicio
                      'ins_fecha_conclusion' => $post['ins_f'], /// Fecha Final
                      'ins_detalle' => strtoupper($post['ins_act']), /// Insumo actividades del consultor
                      'ins_actividades' => strtoupper($post['ins_act']), /// Insumo actividades del consultor
                      'ins_perfil' => strtoupper($post['ins_perfil']), /// Insumo perfil del consultor
                      'ins_cargo' => strtoupper($post['ins_cargo']), /// Insumo cargo del consultor
                      'ins_evaluador' => strtoupper($post['ins_eva']), /// Insumo evaluador del consultor
                      'car_id' => $post['ins_car_id'], /// cargo id
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_unidad_medida' => 'SERVICIO', /// Insumo Unidad de Medida
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==7){
                      $nro_ins=$conf[0]['conf_materiales']+1;

                      $update_conf = array('conf_materiales' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/MAT/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==8){
                      $nro_ins=$conf[0]['conf_activos']+1;

                      $update_conf = array('conf_activos' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/AF/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                      
                  }
                  elseif($ins_tipo==9){
                      $nro_ins=$conf[0]['conf_otros_insumos']+1;

                      $update_conf = array('conf_otros_insumos' => $nro_ins);
                      $this->db->where('ide', $this->gestion);
                      $this->db->update('configuracion', $update_conf);

                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/INS/OI/'.$this->gestion.'/'.$nro_ins, /// Codigo Insumo
                      'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                      'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Insumo Unidad de Medida
                      'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                      'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                      'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                      'ins_tipo' => $post['ins_tipo'], /// Ins Tipo
                      'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                      'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'ins_tipo' => $ins_tipo, /// tipo insumo
                      'ins_mod' => 2, /// mod
                      'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                      );
                  }

                  $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                  $ins_id=$this->db->insert_id();
                  /*---------------------------------------------------------*/
                  /*----------------------------------------------------------*/
                  if($proyecto[0]['proy_act']==1){
                    if($fase[0]['pfec_ejecucion']==1){
                      $data_to_store2 = array( ///// Tabla InsumoActividad
                        'act_id' => $tp_ins, /// act_id
                        'ins_id' => $ins_id, /// ins_id
                        );
                      $this->db->insert('_insumoactividad', $data_to_store2);
                    }
                    else{
                      $data_to_store2 = array( ///// Tabla insumoscomponente
                          'com_id' => $tp_ins, /// com_id
                          'ins_id' => $ins_id, /// ins_id
                      );
                      $this->db->insert('insumocomponente', $data_to_store2);
                    }
                  }
                  else{
                    $data_to_store2 = array( ///// Tabla InsumoProducto
                      'prod_id' => $tp_ins, /// prod_id
                      'ins_id' => $ins_id, /// ins_id
                    );
                    $this->db->insert('_insumoproducto', $data_to_store2);
                  }
                  
                 /*----------------------------------------------------------*/
                  $gestion=$fase[0]['pfec_fecha_inicio'];
                  $insg=0;

                  if (!empty($_POST["gest"]) && is_array($_POST["gest"]) ){
                      foreach ( array_keys($_POST["gest"]) as $como){   
                          $data_to_store = array( 
                              'ins_id' => $ins_id, /// Id Insumo
                              'g_id' => $gestion, /// Gestion
                              'insg_monto_prog' => $_POST["gest"][$como], /// Monto programado
                          );
                          $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                          $insg_id=$this->db->insert_id();    
                             
                          if($gestion==$this->gestion){$ins_g=$insg_id;}
                          
                          $gestion++;
                      }
                  }

                  /*--------------------- iNSERT AUDI ADICIONAR INSUMOS -------------*/
                  $data_to_store2 = array(
                    'ins_id' => $ins_id, /// ins_id
                    'insc_id' => $cite_id, /// cite_id
                    'num_ip' => $this->input->ip_address(), 
                    'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    'fun_id' => $this->session->userdata("fun_id"),
                    );
                  $this->db->insert('_insumo_add', $data_to_store2);
                  $insa_id=$this->db->insert_id();

                  if(count($this->model_modificacion->get_add_insumo($insa_id))==1){
                    redirect(site_url("").'/mod/add_temporalidad/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id.'/'.$ins_g);
                  }
                  else{
                    redirect(site_url("").'/prog/nuevo_ins_p/'.$cite_id.'/'.$ins_tipo.'/'.$proy_id.'/'.$tp_ins.'/error_fecha');
                  }
                  /*------------------------------------------------------------------------------------*/
                }
                else{
                    redirect(site_url("").'/prog/nuevo_ins_p/'.$cite_id.'/'.$ins_tipo.'/'.$proy_id.'/'.$tp_ins.'/error_fecha');
                }
            }
            else{
                redirect(site_url("").'/prog/nuevo_ins_p/'.$cite_id.'/'.$ins_tipo.'/'.$proy_id.'/'.$tp_ins.'/false');
            }
            
        } else {
            show_404();
        }
    }
    
    /*---------- VALIDA UPDATE REQUERIMEINTOS (2018) ---------*/
    function valida_update_requerimiento(){
      if ($this->input->post()) {
          $post = $this->input->post(); 
          $cite_id = $post['cite_id'];  // Cite id
          $proy_id = $post['proy_id'];  // Proy id
          $tp_ins = $post['tp_ins'];    // Tipo de requerimiento : com id, prod id, act id
          $ins_id = $post['ins_id'];    // Insumo id
          $ins_tipo = $post['ins_tipo'];    // Tipo
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

          if($this->copia_insumo($cite_id,$ins_id)){
              $veriff = $this->verif_fecha($post['ins_fecha']); //// verifica de requerimiento
              if($ins_tipo>=1 && $ins_tipo<=9){
                  if($veriff=='true'){
                    /*------------------------------------------------------------------------------------*/
                      if($ins_tipo==1 || $ins_tipo==2 || $ins_tipo==3 || $ins_tipo==4 || $ins_tipo==7 || $ins_tipo==8 || $ins_tipo==9){
                        /*------------------------- Update Insumos ------------------------*/
                          $query=$this->db->query('set datestyle to DMY');
                          $update_ins = array(
                              'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                              'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                              'ins_unidad_medida' => strtoupper($post['ins_unidad']), /// Unidad de Medida
                              'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                              'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                              'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                              'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                              'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                              'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                              'ins_mod' => 2, /// mod
                              'ins_estado'=> 2, /// mod
                              'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                              );
                          /*--------------------------------------------------------------------*/
                      }
                      elseif($ins_tipo==5){
                         /*------------------------- Update Insumos ------------------------*/
                          $query=$this->db->query('set datestyle to DMY');
                          $update_ins = array(
                              'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                              'ins_detalle' => strtoupper($post['ins_detalle']), /// Insumo Detalle
                              'ins_objetivo' => strtoupper($post['objetivo']), /// Insumo Objetivo
                              'ins_duracion' => $post['ins_duracion'], /// Insumo Duracion
                              'ins_fecha_inicio' => $post['ins_i'], /// Fecha de Inicio
                              'ins_fecha_conclusion' => $post['ins_f'], /// Fecha Final
                              'ins_productos' => strtoupper($post['ins_prod']), /// Insumo Producto
                              'ins_evaluador' => strtoupper($post['ins_eva']), /// Insumo Evaluacion
                              'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                              'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                              'ins_unidad_medida' => 'SERVICIO', /// Insumo Unidad de Medida
                              'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                              'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                              'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                              'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                              'ins_mod' => 2, /// mod
                              'ins_estado'=> 2, /// mod
                              'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                              );
                          /*--------------------------------------------------------------------*/
                      }
                      elseif($ins_tipo==6){
                        /*------------------------- Update Insumos ------------------------*/
                          $query=$this->db->query('set datestyle to DMY');
                          $update_ins = array(
                              'ins_fecha_requerimiento' => $post['ins_fecha'], /// Fecha de Requerimiento
                              'ins_duracion' => $post['ins_duracion'], /// Insumo Duracion
                              'ins_objetivo' => strtoupper($post['objetivo']), /// Insumo Objetivo
                              'ins_duracion' => $post['ins_duracion'], /// Insumo Duracion
                              'ins_fecha_inicio' => $post['ins_i'], /// Fecha de Inicio
                              'ins_fecha_conclusion' => $post['ins_f'], /// Fecha Final
                              'ins_detalle' => strtoupper($post['ins_act']), /// Insumo actividades del consultor
                              'ins_actividades' => strtoupper($post['ins_act']), /// Insumo actividades del consultor
                              'ins_perfil' => strtoupper($post['ins_perfil']), /// Insumo perfil del consultor
                              'ins_cargo' => strtoupper($post['ins_cargo']), /// Insumo cargo del consultor
                              'ins_evaluador' => strtoupper($post['ins_eva']), /// Insumo evaluador del consultor
                              'ins_unidad_medida' => 'SERVICIO', /// Insumo Unidad de Medida
                              'car_id' => $post['ins_car_id'], /// cargo id
                              'ins_cant_requerida' => $post['ins_cantidad'], /// Cantidad Requerida
                              'ins_costo_unitario' => $post['ins_costo_unitario'], /// Costo Unitario
                              'ins_costo_total' => $post['ins_costo_total'], /// Costo Total
                              'par_id' => $post['ins_partidas_dependientes'], /// Partidas
                              'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                              'ins_observacion' => strtoupper($post['ins_obs']), /// Ins Observacion
                              'ins_mod' => 2, /// mod
                              'ins_estado'=> 2, /// mod
                              'aper_id' => $proyecto[0]['aper_id'], /// aper_id
                              );
                        /*--------------------------------------------------------------------*/
                      }
                      $this->db->where('ins_id', $ins_id);
                      $this->db->update('insumos', $update_ins);

                          $insumo_gestion = $this->minsumos->list_insumos_gestion($ins_id); //// DATOS DEL INSUMO GESTION
                          $gestion=$fase[0]['pfec_fecha_inicio'];
                          $ins_g=0;

                          if(count($insumo_gestion)!=0){
                              /*------------------ Update Insumo Gestion ---------------*/
                              if (!empty($_POST["gest"]) && is_array($_POST["gest"]) ){
                                  foreach ( array_keys($_POST["gest"]) as $como  ) {   
                                    $ig=$this->minsumos->get_insumo_gestion($ins_id,$gestion);
                                    if(count($ig)!=0){
                                      $update_insg = array(
                                        'ins_id' => $ins_id, /// Id Insumo
                                        'g_id' => $gestion, /// Gestion
                                        'insg_monto_prog' => $_POST["gest"][$como], /// Monto programado
                                        'insg_estado' => 2 /// Insumo GEstion Modificado
                                        );
                                      $this->db->where('insg_id', $ig[0]['insg_id']);
                                      $this->db->update('insumo_gestion', $update_insg);

                                      if($gestion==$this->gestion){$ins_g=$ig[0]['insg_id'];}
                                    }
                                    else{
                                      $data_to_store = array( 
                                        'ins_id' => $ins_id, /// Id Insumo
                                        'g_id' => $gestion, /// Gestion
                                        'insg_monto_prog' => $_POST["gest"][$como], /// Monto programado
                                      );
                                      $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                                      $insg_id=$this->db->insert_id();

                                      if($gestion==$this->gestion){$ins_g=$insg_id;}
                                    }

                                    $gestion++;
                                  }
                              }
                              /*----------------------------------------------------*/
                          }
                          
                    redirect(site_url("").'/mod/update_temporalizacion/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id.'/'.$ins_g);
                  }
                  else{
                    redirect('/','refresh');
                  }
              }
              else{
                redirect('/','refresh');
              }
          }
          else{
            $this->session->set_flashdata('danger','ERROR, NO SE PUDO MIGRAR LA INFORMACIÓN...');
            redirect(site_url("").'/mod/update_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id.'');
          }

      } else {
          show_404();
      }
    }

    /*---------------------- Valida Temporalizacion Presupuestaria-----------------------*/
    function valida_update_temporalizacion(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $post['cite_id']; /// cite_id
          $proy_id = $post['proy_id']; /// proy_id
          $tp_ins = $post['tp_ins']; /// instipo
          $ins_id = $post['ins_id']; /// ins_id
          $itp = $post['itp']; /// tp :1 -> nuevo, 2->modificado

          $insg_id = $post['insg_id']; /// insg_id
          $gestion = $post['gestion']; /// gestion

          $insumo = $this->minsumos->get_insumo($ins_id); //// DATOS DEL INSUMO
          $mes[1]="m1";$mes[2]="m2";$mes[3]="m3";$mes[4]="m4";$mes[5]="m5";$mes[6]="m6";$mes[7]="m7";$mes[8]="m8";$mes[9]="m9";$mes[10]="m10";$mes[11]="m11";$mes[12]="m12";
          $id[1]="id1";$id[2]="id2";$id[3]="id3";$id[4]="id4";$id[5]="id5";$id[6]="id6";$id[7]="id7";$id[8]="id8";$id[9]="id9";$id[10]="id10";$id[11]="id11";$id[12]="id12";
          
          /*$verif_insumo=$this->model_modificacion->verif_get_insumo_modificado($ins_id);

          if(count($verif_insumo)==0){
            $copia=$this->copia_insumo($cite_id,$ins_id);
          }*/
          $nro_if=1;
          if ( !empty($_POST["ff"]) && is_array($_POST["ff"])){
              foreach ( array_keys($_POST["ff"]) as $como){
              //  echo "Ins_id :".$ins_id." ff :".$_POST["ff"][$como]." of :".$_POST["of"][$como]." et :".$_POST["et"][$como]." monto asig :".$_POST["monto_asig"][$como]." Gestion :".$gestion."<br>";
              if($_POST["monto_asig"][$como]!=0 & $_POST["ifin_id"][$como]==0) /// No tiene monto y no tiene ifin_id (Inserta Datos) 
              {
                /*--------------------- GUARDA INSUMO FINANCIAMIENTO --------------------*/
                $data_to_store = array( ///// Tabla insumos
                  'insg_id' => $insg_id, /// Id Insumo Gestion
                  'ffofet_id' => $_POST["ffofet_id"][$como], /// Ffofet Id
                  'ff_id' => $_POST["ff"][$como], /// Fuente de Financiamiento
                  'of_id' => $_POST["of"][$como], /// Organismo Financiador
                  'et_id' => $_POST["et"][$como], /// Entidad de Transferencia
                  'ifin_monto' => $_POST["monto_asig"][$como], /// Monto Asignado
                  'ifin_gestion' => $gestion, /// Gestion
                  'nro_if' => $nro_if, /// nro if
                );
                $this->db->insert('insumo_financiamiento', $data_to_store); ///// Guardar en Tabla Insumo Financiamiento
                $ifin_id=$this->db->insert_id();

                for ($i=1; $i <=12 ; $i++) { 
                    if($_POST[$mes[$i]][$como]!=0){
                       /*--------------------- GUARDA INSUMO FINANCIAMIENTO --------------------*/
                        $data_to_store2 = array( ///// Tabla ifin_prog_mes
                            'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                            'mes_id' => $i, /// Mes Id
                            'ipm_fis' => $_POST[$mes[$i]][$como], /// Programado
                        );
                        $this->db->insert('ifin_prog_mes', $data_to_store2); ///// Guardar en Tabla Insumos 
                        /*-----------------------------------------------------------------------*/ 
                    }
                }

              }  
              elseif($_POST["monto_asig"][$como]==0 & $_POST["ifin_id"][$como]!=0) /// No tiene monto , pero tiene ifin_id (Elimina las dos tablas)
              {
                /*----------------- ELIMINA IFIN PROG MES---------------*/
                $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                $this->db->delete('ifin_prog_mes');
                /*------------------------------------------------------*/

                /*----------------- ELIMINA IFIN PROG MES---------------*/
                $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                $this->db->delete('insumo_financiamiento');
                /*------------------------------------------------------*/
              }
              elseif($_POST["monto_asig"][$como]!=0 & $_POST["ifin_id"][$como]!=0) /// tiene monto Asignado y tiene ifin_id (Actualizar las dos tablas)
              {
                /*----------------- ACTUALIZA DATOS INSUMOS FIN --------------------*/
                $update_if = array(
                        'ifin_monto' => $_POST["monto_asig"][$como], /// Monto Asignado
                        'et_id' => $_POST["et"][$como], /// Entidad de Transferencia
                        'ifin_gestion' => $gestion, /// Gestion;
                        'nro_if' => $nro_if
                        );
                $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                $this->db->update('insumo_financiamiento', $update_if);
                /*------------------------------------------------------------------*/
                /*----------------- INSERTA INSUMO PROGRAMADO MES --------------*/
                for ($i=1; $i <=12 ; $i++) { 
                  if($_POST[$id[$i]][$como]!=0){
                    if(count($this->model_modificacion->get_iprog_cert($_POST[$id[$i]][$como]))==0){
                      if($_POST[$id[$i]][$como]!=0 & $_POST[$mes[$i]][$como]==0){
                        /*----------------- ELIMINA IFIN PROG MES---------------*/
                        $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                        $this->db->where('ipm_id', $_POST[$id[$i]][$como]);
                        $this->db->where('mes_id', $i);
                        $this->db->delete('ifin_prog_mes');
                        /*------------------------------------------------------*/
                      }
                      else{
                        /*----------------- ACTUALIZA DATOS PROGRAMADOS --------------------*/
                        $update_if = array(
                          'ipm_fis' => $_POST[$mes[$i]][$como]
                        );
                        $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                        $this->db->where('ipm_id', $_POST[$id[$i]][$como]);
                        $this->db->where('mes_id', $i);
                        $this->db->update('ifin_prog_mes', $update_if);
                        /*------------------------------------------------------------------*/
                      }
                    }
                  }
                  elseif($_POST[$id[$i]][$como]==0 & $_POST[$mes[$i]][$como]!=0){
                    /*--------------------- GUARDA INSUMO FINANCIAMIENTO --------------------*/
                    $data_to_store2 = array( ///// Tabla ifin_prog_mes
                        'ifin_id' => $_POST["ifin_id"][$como], /// Id Insumo Financiamiento
                        'mes_id' => $i, /// Mes Id
                        'ipm_fis' => $_POST[$mes[$i]][$como], /// Programado
                    );
                    $this->db->insert('ifin_prog_mes', $data_to_store2);
                    /*-----------------------------------------------------------------------*/ 
                  }
                  elseif($_POST[$id[$i]][$como]!=0 & $_POST[$mes[$i]][$como]==0){
                    /*----------------- ELIMINA IFIN PROG MES---------------*/
                    $this->db->where('ifin_id', $_POST["ifin_id"][$como]);
                    $this->db->where('ipm_id', $_POST[$id[$i]][$como]);
                    $this->db->where('mes_id', $i);
                    $this->db->delete('ifin_prog_mes');
                    /*------------------------------------------------------*/
                  }

                }
              }
                  
                $nro_if++;
              }
          }
        
          if($itp==1){
            $this->session->set_flashdata('success','SE AGREGO CORRECTAMENTE EL REQUERIMIENTO');
          }
          elseif ($itp==2) {
            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE EL REQUERIMIENTO');
          }
          redirect(site_url("") . '/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id); 
        
      } else {
          show_404();
      }
    }
    /*----------- Funcion Copia Insumo a Historial -----------*/
    public function copia_insumo($cite_id,$ins_id){
      $insumo = $this->minsumos->get_dato_insumo($ins_id); //// DATOS DEL REQUERIMIENTO
      if(count($insumo)!=0){
        $cite = $this->model_modificacion->get_cite_insumo($cite_id); /// Cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO

        $query=$this->db->query('set datestyle to DMY');
          $data_to_store = array( 
            'ins_codigo' => $insumo[0]['ins_codigo'], /// Codigo Insumo
            'ins_fecha_requerimiento' => $insumo[0]['ins_fecha_requerimiento'], /// Fecha de Requerimiento
            'ins_detalle' => $insumo[0]['ins_detalle'], /// Insumo Detalle
            'ins_cant_requerida' => $insumo[0]['ins_cant_requerida'], /// Cantidad Requerida
            'ins_costo_unitario' => $insumo[0]['ins_costo_unitario'], /// Costo Unitario
            'ins_costo_total' => $insumo[0]['ins_costo_total'], /// Costo Total
            'ins_unidad_medida' => $insumo[0]['ins_unidad_medida'], /// Insumo Unidad de Medida
            'ins_tipo' => $insumo[0]['ins_tipo'], /// Ins Tipo
            'par_id' => $insumo[0]['par_id'], /// Partidas
            'ins_observacion' => $insumo[0]['ins_observacion'], /// Ins Observacion
            'fun_id' => $insumo[0]['fun_id'], /// Funcionario
            'aper_id' => $proyecto[0]['aper_id'], /// aper id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('insumos_historial', $data_to_store); ///// Guardar en Tabla Insumos 
          $insh_id=$this->db->insert_id();

          if($this->gestion!=2020){ /// 2019
            $this->datos_complementarios_registro_historial_insumo($ins_id,$insh_id);
          }
          else{ /// 2020
            $prog=$this->model_insumo->lista_prog_fin($ins_id);
            foreach ($prog as $rowp) {
              $data_to_store4 = array(
              'insh_id' => $insh_id, /// Insumo Id
              'mes_id' => $rowp['mes_id'], /// Mes
              'ipm_fis' => $rowp['ipm_fis'], /// Valor
              'g_id' => $rowp['g_id'], /// gestion
              );
              $this->db->insert('temporalidad_prog_insumo_historial', $data_to_store4);
              $tinsh_id =$this->db->insert_id();
            }
          }

          /*------- Insumo - Modificado ---------*/
          $data_to_store6 = array( 
            'ins_id' => $ins_id,
            'insh_id' => $insh_id,
            'insc_id' => $cite_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->session->userdata("fun_id"),
            );
            $this->db->insert('_insumo_modificado', $data_to_store6);
            $insm_id =$this->db->insert_id();
          /*------------------------------------------------------------------------------*/

          if(count($this->model_modificacion->get_insumo_modificado($insm_id))!=0){
            return true;
          }
          else{
            return false;
          }
      }
      else{
        return false;
      }
      
    }


    /*--- Registro de datos complementarios al insumo (2018-2019)-----*/
    public function datos_complementarios_registro_historial_insumo($ins_id,$insh_id){
      /*-------- Insumo Gestion Historial 2018-2019 --------*/
        $list_g=$this->minsumos->list_insumos_gestion($ins_id);
        foreach ($list_g as $rowg) {
          $data_to_store2 = array( 
          'insh_id' => $insh_id, /// Insumo id historial
          'g_id' => $rowg['g_id'], /// Gestion
          'insg_monto_prog' => $rowg['insg_monto_prog'], /// Monto programado Gestion
          );
          $this->db->insert('insumo_gestion_historial', $data_to_store2);
          $insgh_id =$this->db->insert_id();

          /*----------- Insumo Financiamiento -----------*/
          $list_if=$this->minsumos->list_insumo_financiamiento($rowg['insg_id']);
          foreach ($list_if as $rowi) {
            $data_to_store3 = array( 
            'insgh_id' => $insgh_id, /// Insumo gestion id historial
            'ifin_monto' => $rowi['ifin_monto'], /// Monto
            'ifin_gestion' => $rowi['ifin_gestion'], /// Gestion
            'nro_if' => $rowi['nro_if'], /// Nro
            'ffofet_id' => $rowi['ffofet_id'], /// Ffofet Id
            );
            $this->db->insert('insumo_financiamiento_historial', $data_to_store3);
            $ifinh_id =$this->db->insert_id();

            /*--------- Programado Insumo Mensual ------------*/
            $iprog=$this->minsumos->insumo_programado_mensual($rowi['ifin_id']);
            foreach ($iprog as $rowp) {
              $data_to_store4 = array( 
              'ifinh_id' => $ifinh_id, /// Insumo Financiamiento
              'mes_id' => $rowp['mes_id'], /// Mes
              'ipm_fis' => $rowp['ipm_fis'], /// Nro
              );
              $this->db->insert('ifin_prog_mes_historial', $data_to_store4);
              $ipmh_id =$this->db->insert_id();
            }
            /*-------------------------------------------------------------------*/
          }
        }

    }




    /*-------------------- pasar a temporalidad ------------------*/
    public function temporalizacion($cite_id,$proy_id,$tp_ins,$ins_id){
      $ins_g=$this->minsumos->get_dato_insumo_gestion_actual($ins_id);
      if(count($ins_g)!=0){
        redirect(site_url("").'/mod/update_temporalizacion/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id.'/'.$ins_g[0]['insg_id'].'');
      }
      else{
        $this->session->set_flashdata('danger','ERROR AL PASAR A LA TEMPORALIDAD');
        redirect(site_url("").'/mod/update_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$ins_id.'');
      }
    }

    /*-------------------- Tipo de Requerimiento ---------------------*/
    public function tipo_requerimiento($tipo){
      $titulo[1]='';$titulo[2]='';$titulo[3]='';$titulo[4]='';
      if($tipo==1 || $tipo==2 || $tipo==3){
        $titulo[1]='DETALLE';
        $titulo[2]='CANTIDAD';
        $titulo[3]='COSTO UNITARIO';
      }elseif ($tipo==2) {
        $titulo[1]='DETALLE DEL SERVICIO';
        $titulo[2]='CANTIDAD (Meses)';
        $titulo[3]='COSTO UNITARIO';
      }
      elseif($tipo==3){
        $titulo[1]='RUTA';
        $titulo[2]='CANTIDAD';
        $titulo[3]='COSTO UNITARIO';
      }
      elseif($tipo==4){
        $titulo[1]='CLASIFICACI&Oacute;N DE DESTINO';
        $titulo[2]='DIAS VIATICO';
        $titulo[3]='VIATICO DIARIO';
      }
      elseif($tipo==5){
        $titulo[1]='DESCRIPCI&Oacute;N DE LA CONSULTORIA';
        $titulo[2]='CANTIDAD REQUERIDA';
        $titulo[3]='COSTO UNITARIO';
      }
      elseif($tipo==6){
        $titulo[2]='CANTIDAD REQUERIDA';
        $titulo[3]='COSTO UNITARIO';
      }
      elseif($tipo==7 || $tipo==8){
        $titulo[1]='DESCRIPCI&Oacute;N';
        $titulo[2]='CANTIDAD REQUERIDA';
        $titulo[3]='COSTO UNITARIO';
      }

      return $titulo;
    }

    /*-------------- Mis Requerimientos (2018)--------*/
    public function requerimientos_operaciones($cite_id,$proy_id,$tp_ins){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
      $tabla_fuentes = $this->fuentes_financiamientos($proy_id, $this->gestion,$fase[0]['pfec_ejecucion'],$proyecto[0]['proy_act']); 
      $sumatorias = $this->suma_total_fuentes($fase[0]['id'], $this->gestion,$fase[0]['pfec_ejecucion'],$proyecto[0]['proy_act']); //// Suma Total, Asignado, Programado, Saldo
      $req=$this->model_modificacion->mis_requerimientos($proyecto[0]['proy_act'],$fase[0]['pfec_ejecucion'],$tp_ins);

      $tabla ='';
      $tabla .='
      <div class="well">
        <div class="table-responsive" align=center>
            <table class="table table-bordered" style="width:100%;">
              <thead>
                <tr colspan="5" style="text-align: right; background:#0aa699;">
                    <th rowspan="2"><b style="color:#fff;">NRO</b></th>
                    <th colspan="2"><b style="color:#fff;">FUENTE FINANCIAMIENTO</b></th>
                    <th colspan="2"><b style="color:#fff;">ORGANISMO FINANCIADOR</b></th>
                    <th rowspan="2"><b style="color:#fff;">PRESUPUESTO ASIGNADO '.$this->gestion.'</b></th>
                    <th rowspan="2"><b style="color:#fff;">PRESUPUESTO PROGRAMADO '.$this->gestion.'</b></th>
                    <th rowspan="2"><b style="color:#fff;">SALDO POR PROGRAMAR '.$this->gestion.'</b></th>
                </tr>
                <tr colspan="5" style="text-align: right; background:#0aa699;">
                    <th><b style="color:#fff;">C&Oacute;DIGO</b></th>
                    <th><b style="color:#fff;">DESCRIPCI&Oacute;N</b></th>
                    <th><b style="color:#fff;">C&Oacute;DIGO</b></th>
                    <th><b style="color:#fff;">DESCRIPCI&Oacute;N</b></th>
                </tr>
              </thead>
              <tbody id="presupuesto">
                '.$tabla_fuentes.'
              </tbody>
            </table>
        </div>  
        <div class="row text-align-right">
          <div class="col-md-12"><h1> TOTAL PROGRAMADO POA : <kbd class=" primary"> '.number_format($sumatorias[2], 2, ',', '.').' Bs.</kbd></h1></div>
        </div>
      </div>';
      if($this->session->flashdata('success')){
      $tabla .='<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';
      }
      elseif($this->session->flashdata('danger')){ 
      $tabla .='<div class="alert alert-danger">'.$this->session->flashdata('danger').'</div>';
      }

      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
                $attributes = array('id' => 'del_req','name' =>'del_req','enctype' => 'multipart/form-data');
                              echo validation_errors();
                              echo form_open('mod/delete_requerimientos', $attributes);
      $tabla .='  <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"><i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>MIS REQUERIMIENTOS</strong></h2>  
                    </header>
                    <div>
                    <div class="widget-body no-padding">
                      <form id="del_req" name="del_req" novalidate="novalidate" method="post">
                      <table id="dt_basic" class="table table table-bordered" width="100%">
                        <input type="hidden" name="cite_id" id="cite_id" value="'.$cite_id.'">
                        <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'">
                        <input type="hidden" name="tp_ins" id="tp_ins" value="'.$tp_ins.'">
                        <thead>
                          <tr>
                            <th style="width:1%;">#</th>
                            <th style="width:5%;">';
                            if($sumatorias[3]>10){
                              if($fase[0]['pfec_ejecucion']==1){
                                $tabla.= '<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-xs nuevo_ff" title="NUEVO DE REQUERIMIENTO"><img src="'.base_url().'assets/img/add_icon.png" WIDTH="35" HEIGHT="35"/><BR>NUEVO</a>';
                              }
                              else{
                                $tabla.= '<a href="'.base_url().'index.php/mod/add_requerimiento/'.$cite_id.'/8/'.$proy_id.'/'.$tp_ins.'" title="NUEVO REQUERIMIENTO"><img src="'.base_url().'assets/img/add_icon.png" width="35" height="35"/><br>NUEVO</a>';
                              }
                            }
                            $tabla.='
                            </th>
                            <th style="width:1%;"></th>
                            <th style="width:15%;">PARTIDA</th>
                            <th style="width:20%;">DETALLE DE REQUERIMIENTO</th>
                            <th style="width:10%;">TIPO DE REQUERIMIENTO</th>
                            <th style="width:5%;">FECHA DE REQUERIMIENTO</th>
                            <th style="width:5%;">CANTIDAD</th>
                            <th style="width:5%;">COSTO UNITARIO</th>
                            <th style="width:5%;">COSTO TOTAL</th>
                            <th style="width:60%;">TEMPORALIDAD</th>
                            <th style="width:10%;">OBSERVACI&Oacute;N</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $cont = 0;
                          foreach ($req as $row) {
                            $cont++;
                            $cert=$this->model_ejecucion->get_ins_certificado($row['ins_id'],$row['ifin_id']);
                            $insg = $this->minsumos->get_dato_insumo_gestion_actual($row['ins_id']);
                            $color_tr=''; $dis=''; $title=''; $img='';
                            $mod='<a href="'.base_url().'index.php/mod/update_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$row['ins_id'].'" id="myBtn'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO" '.$dis.'><img src="'.base_url().'assets/img/mod_icon.png" width="35" height="40"/></a><br>';
                            $del='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" id="'.$proy_id.'">
                                      <img src="'.base_url().'assets/img/delete.png" width="42" height="35"/>
                                  </a>';
                            $check='<center>
                                      <input type="checkbox" name="req[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
                                    </center>';
                            if(count($cert)!=0){
                              if($cert[0]['certificado']==$row['programado_total']){
                                $color_tr="#f1b5c6"; $dis='disabled'; $title='title="REQUERIMIENTO CERTIFICADO"';
                                $mod=''; $del='';
                              }
                            }
                            if($row['ins_id']==$this->uri->segment(5)){
                              $color_tr="#cbf5cb"; $title='title="REQUERIMIENTO MODIFICADO"'; $img='<img src="'.base_url().'assets/Iconos/accept.png" width="20" height="20"/>';
                            }
                            $tabla .= '<tr bgcolor='.$color_tr.' '.$title.'>';
                            $tabla .= '<td align=center>'.$cont.'<br>'.$check.'</td>';
                            $tabla .= '<td align=center>
                                        '.$mod.'
                                        '.$del.'
                                      </td>';
                            $tabla .= '<td><center><img id="load'.$row['ins_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></center></td>';
                            $tabla .= '<td>'.$row['par_codigo'].' - '.$row['par_nombre'].'</td>';
                            $tabla .= '<td>'.$row['ins_detalle'].'</td>';
                            $tabla .= '<td>'.$row['ti_nombre'].'</td>';
                            $tabla .= '<td>'.date('d/m/Y',strtotime($row['ins_fecha_requerimiento'])).'</td>';
                            $tabla .= '<td>'.$row['ins_cant_requerida'] .'</td>';
                            $tabla .= '<td>'.number_format($row['ins_costo_unitario'], 2, ',', '.') .'</td>';
                            $tabla .= '<td>'.number_format($row['ins_costo_total'], 2, ',', '.') .'</td>';
                            $tabla .= '<td>'.$this->get_tabla_ins_progmensual_directo($insg[0]['insg_id']).'</td>';
                            $tabla .= '<td>'.$row['ins_observacion'].'</td>';
                            $tabla .= '</tr>';
                            $tabla.='<script>
                                        document.getElementById("myBtn'.$row['ins_id'].'").addEventListener("click", function(){
                                        document.getElementById("load'.$row['ins_id'].'").style.display = "block";
                                      });
                                    </script>';
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
                          
              $tabla.=' </tbody>
                      </table>
                    </div>
                    </div>
                  </div>
                    <input type="hidden" name="tot" id="tot" value="0">';
                    if($this->rol_id==1){
                      $tabla .='<div class="alert alert-danger" align=right><input type="button" class="btn btn-danger btn-xs" value="ELIMINAR REQUERIMIENTOS" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS"></div>';        
                    }
                    $tabla .='
                  </form>
                </article>
                  <div id="wil" style="display: none">
                      <div align="center">
                        <img id="load" src="'.base_url().'/assets/img/loading.gif" width="40" height="40" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."><br>  <font color="red">ELIMINANDO REQUERIMIENTOS ....</font>
                      </div>
                  </div>';
      return $tabla;
    }


    /*-------------- Lista de  Requerimientos (2019)--------*/
    public function lista_requerimientos($cite_id,$proy_id,$tp_ins){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
      if($this->gestion!=2020){ /// 2019
        $lista_insumos=$this->model_modificacion->mis_requerimientos($proyecto[0]['proy_act'],$fase[0]['pfec_ejecucion'],$tp_ins);
      }
      else{ /// 2020
        if($proyecto[0]['tp_id']==1){ /// Proy de Inversion
          $lista_insumos = $this->minsumos->lista_insumos_act($tp_ins);
        }
        else{ /// Gasto Corriente
          $lista_insumos = $this->minsumos->lista_insumos_prod($tp_ins);
        }
      }

      
      $tabla='';
      $total=0;
      $tabla.='<table id="dt_basic" class="table table table-bordered" width="100%">
                <thead>
                  <tr class="modo1">
                    <th style="width:2%;">#</th>
                    <th style="width:5%;">PARTIDA</th>
                    <th style="width:15%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD</th>
                    <th style="width:5%;">CANTIDAD</th>
                    <th style="width:5%;">UNITARIO</th>
                    <th style="width:5%;">TOTAL</th>
                    <th style="width:5%;">TOTAL PROG.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ENE.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">FEB.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">ABR.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">MAY.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUN.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">JUL.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">AGO.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">SEPT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">OCT.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">NOV.</th>
                    <th style="width:5%;" style="background-color: #0AA699;color: #FFFFFF">DIC.</th>
                    <th style="width:8%;">OBSERVACIONES</th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach ($lista_insumos as $row) {
                  $color_tr=''; $dis=''; $title='title="REQUERIMIENTO"';

                  if($this->gestion!=2020){ /// 2019
                    $prog = $this->minsumos->get_list_insumo_financiamiento($row['insg_id']);
                    /*-------------------- 2019 ---------------------*/
                    $cert=$this->model_ejecucion->get_ins_certificado($row['ins_id'],$row['ifin_id']);
                    if(count($cert)!=0){
                        if($cert[0]['certificado']==$row['programado_total']){
                          $color_tr="#f1b5c6"; $dis='disabled'; $title='title="REQUERIMIENTO CERTIFICADO"';
                        }
                      }
           
                    $cont++;
                    $tabla .= '<tr class="modo1" bgcolor="'.$color_tr.'" '.$title.'>';
                      $tabla .= '<td align="center" style="width:2%;" title='.$row['ins_id'].'>';
                      if(count($cert)!=0){
                        if($cert[0]['certificado']<$row['programado_total']){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                        }
                      }
                      else{
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                      }

                      if(count($cert)==0){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" id="'.$proy_id.'">
                                    <img src="'.base_url().'assets/img/delete.png" width="35" height="35"/>
                                  </a>';
                      }
                      /*-----------------------------------------*/
                  }
                  else{ //// 2020
                    $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);

                    $tabla .='<tr>';
                    $tabla .='<td align=center>';
                      $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>
                                <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" id="'.$proy_id.'">
                                  <img src="'.base_url().'assets/img/delete.png" width="35" height="35"/>
                                </a>';
                  }

                  $cont++;
                    $tabla .='</td>';
                    $tabla .='<td style="width:5%;">'.$row['par_codigo'].'</td>'; /// partida
                    $tabla .= '<td style="width:15%;">'.$row['ins_detalle'].'</td>'; /// detalle requerimiento
                    $tabla .= '<td style="width:10%;">'.$row['ins_unidad_medida'].'</td>'; /// Unidad
                    $tabla .= '<td style="width:5%;">'.$row['ins_cant_requerida'].'</td>'; /// cantidad
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                    $tabla .= '<td style="width:5%;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';

                    if(count($prog)!=0){
                      $tabla.='
                      <td style="width:5%;">'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td> 
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                      <td style="width:5%;" bgcolor="#dcfbf8">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                    }
                    else{
                      $tabla.='
                      <td style="width:5%;">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>
                      <td style="width:5%;" bgcolor="#f9d4ce">0</td>';
                    }
                    
                    $tabla .= '<td style="width:8%;">'.$row['ins_observacion'].'</td>';
                  $tabla .= '</tr>';
                  $total=$total+$row['ins_costo_total'];
                }
                $tabla.='
                </tbody>
                  <tr class="modo1">
                    <td colspan="6"> TOTAL </td>
                    <td><font color="blue" size=1>'.number_format($total, 2, ',', '.') .'</font></td>
                    <td colspan="14"></td>
                  </tr>
              </table>';

      return $tabla;
    }

    public function lista_requerimientos2($cite_id,$proy_id,$tp_ins){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
    //  $tabla_fuentes = $this->fuentes_financiamientos($proy_id, $this->gestion,$fase[0]['pfec_ejecucion'],$proyecto[0]['proy_act']); 
      $sumatorias = $this->suma_total_fuentes($fase[0]['id'], $this->gestion,$fase[0]['pfec_ejecucion'],$proyecto[0]['proy_act']); //// Suma Total, Asignado, Programado, Saldo
      $req=$this->model_modificacion->mis_requerimientos($proyecto[0]['proy_act'],$fase[0]['pfec_ejecucion'],$tp_ins);

      $tabla ='';
      /*$tabla .='
      <div class="well">
        <div class="table-responsive" align=center>
            <table class="table table-bordered" style="width:100%;">
              <thead>
                <tr colspan="5" style="text-align: right; background:#0aa699;">
                    <th rowspan="2"><b style="color:#fff;">NRO</b></th>
                    <th colspan="2"><b style="color:#fff;">FUENTE FINANCIAMIENTO</b></th>
                    <th colspan="2"><b style="color:#fff;">ORGANISMO FINANCIADOR</b></th>
                    <th rowspan="2"><b style="color:#fff;">PRESUPUESTO ASIGNADO '.$this->gestion.'</b></th>
                    <th rowspan="2"><b style="color:#fff;">PRESUPUESTO PROGRAMADO '.$this->gestion.'</b></th>
                    <th rowspan="2"><b style="color:#fff;">SALDO POR PROGRAMAR '.$this->gestion.'</b></th>
                </tr>
                <tr colspan="5" style="text-align: right; background:#0aa699;">
                    <th><b style="color:#fff;">C&Oacute;DIGO</b></th>
                    <th><b style="color:#fff;">DESCRIPCI&Oacute;N</b></th>
                    <th><b style="color:#fff;">C&Oacute;DIGO</b></th>
                    <th><b style="color:#fff;">DESCRIPCI&Oacute;N</b></th>
                </tr>
              </thead>
              <tbody id="presupuesto">
                '.$tabla_fuentes.'
              </tbody>
            </table>
        </div>  
        <div class="row text-align-right">
          <div class="col-md-12"><h1> TOTAL PROGRAMADO POA : <kbd class=" primary"> '.number_format($sumatorias[2], 2, ',', '.').' Bs.</kbd></h1></div>
        </div>
      </div>';*/

                $attributes = array('id' => 'del_req','name' =>'del_req','enctype' => 'multipart/form-data');
                              echo validation_errors();
                              echo form_open('mod/delete_requerimientos', $attributes);
      $tabla .=' 
                      <form id="del_req" name="del_req" novalidate="novalidate" method="post">
                      <table id="dt_basic" class="table table table-bordered" width="100%">
                        <input type="hidden" name="cite_id" id="cite_id" value="'.$cite_id.'">
                        <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'">
                        <input type="hidden" name="tp_ins" id="tp_ins" value="'.$tp_ins.'">
                        <thead>
                          <tr>
                            <th style="width:1%;">#</th>
                            <th style="width:5%;">';
                            if($sumatorias[3]>10){
                              if($fase[0]['pfec_ejecucion']==1){
                                $tabla.= '<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-xs nuevo_ff" title="NUEVO DE REQUERIMIENTO"><img src="'.base_url().'assets/img/add_icon.png" WIDTH="35" HEIGHT="35"/><BR>NUEVO</a>';
                              }
                              else{
                                $tabla.= '<a href="'.base_url().'index.php/mod/add_requerimiento/'.$cite_id.'/8/'.$proy_id.'/'.$tp_ins.'" title="NUEVO REQUERIMIENTO"><img src="'.base_url().'assets/img/add_icon.png" width="35" height="35"/><br>NUEVO</a>';
                              }
                            }
                            $tabla.='
                            </th>
                            <th style="width:1%;"></th>
                            <th style="width:15%;">PARTIDA</th>
                            <th style="width:20%;">DETALLE DE REQUERIMIENTO</th>
                            <th style="width:10%;">TIPO DE REQUERIMIENTO</th>
                            <th style="width:5%;">FECHA DE REQUERIMIENTO</th>
                            <th style="width:5%;">CANTIDAD</th>
                            <th style="width:5%;">COSTO UNITARIO</th>
                            <th style="width:5%;">COSTO TOTAL</th>
                            <th style="width:60%;">TEMPORALIDAD</th>
                            <th style="width:10%;">OBSERVACI&Oacute;N</th>
                          </tr>
                        </thead>
                        <tbody>';
                        $cont = 0;
                          foreach ($req as $row) {
                            $cont++;
                            $cert=$this->model_ejecucion->get_ins_certificado($row['ins_id'],$row['ifin_id']);
                            $insg = $this->minsumos->get_dato_insumo_gestion_actual($row['ins_id']);
                            $color_tr=''; $dis=''; $title=''; $img='';
                            $mod='<a href="'.base_url().'index.php/mod/update_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/'.$row['ins_id'].'" id="myBtn'.$row['ins_id'].'" title="MODIFICAR REQUERIMIENTO" '.$dis.'><img src="'.base_url().'assets/img/mod_icon.png" width="35" height="40"/></a><br>';
                            $del='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$row['ins_id'].'" id="'.$proy_id.'">
                                      <img src="'.base_url().'assets/img/delete.png" width="42" height="35"/>
                                  </a>';
                            $check='<center>
                                      <input type="checkbox" name="req[]" value="'.$row['ins_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
                                    </center>';
                            if(count($cert)!=0){
                              if($cert[0]['certificado']==$row['programado_total']){
                                $color_tr="#f1b5c6"; $dis='disabled'; $title='title="REQUERIMIENTO CERTIFICADO"';
                                $mod=''; $del='';
                              }
                            }
                            if($row['ins_id']==$this->uri->segment(5)){
                              $color_tr="#cbf5cb"; $title='title="REQUERIMIENTO MODIFICADO"'; $img='<img src="'.base_url().'assets/Iconos/accept.png" width="20" height="20"/>';
                            }
                            $tabla .= '<tr bgcolor='.$color_tr.' '.$title.'>';
                            $tabla .= '<td align=center>'.$cont.'<br>'.$check.'</td>';
                            $tabla .= '<td align=center>
                                        '.$mod.'
                                        '.$del.'
                                      </td>';
                            $tabla .= '<td><center><img id="load'.$row['ins_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="25" height="25" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></center></td>';
                            $tabla .= '<td>'.$row['par_codigo'].' - '.$row['par_nombre'].'</td>';
                            $tabla .= '<td>'.$row['ins_detalle'].'</td>';
                            $tabla .= '<td>'.$row['ti_nombre'].'</td>';
                            $tabla .= '<td>'.date('d/m/Y',strtotime($row['ins_fecha_requerimiento'])).'</td>';
                            $tabla .= '<td>'.$row['ins_cant_requerida'] .'</td>';
                            $tabla .= '<td>'.number_format($row['ins_costo_unitario'], 2, ',', '.') .'</td>';
                            $tabla .= '<td>'.number_format($row['ins_costo_total'], 2, ',', '.') .'</td>';
                            $tabla .= '<td>'.$this->get_tabla_ins_progmensual_directo($insg[0]['insg_id']).'</td>';
                            $tabla .= '<td>'.$row['ins_observacion'].'</td>';
                            $tabla .= '</tr>';
                            $tabla.='<script>
                                        document.getElementById("myBtn'.$row['ins_id'].'").addEventListener("click", function(){
                                        document.getElementById("load'.$row['ins_id'].'").style.display = "block";
                                      });
                                    </script>';
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
                          
              $tabla.=' </tbody>
                      </table>
                    </div>
                    </div>
                  </div>
                    <input type="hidden" name="tot" id="tot" value="0">';
                    if($this->rol_id==1){
                      $tabla .='<div class="alert alert-danger" align=right><input type="button" class="btn btn-danger btn-xs" value="ELIMINAR REQUERIMIENTOS" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS"></div>';        
                    }
                    $tabla .='
                  </form>
             
                  <div id="wil" style="display: none">
                      <div align="center">
                        <img id="load" src="'.base_url().'/assets/img/loading.gif" width="40" height="40" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."><br>  <font color="red">ELIMINANDO REQUERIMIENTOS ....</font>
                      </div>
                  </div>';
      return $tabla;
    }

    /*------------- Mis Servicios-Productos-Actividades --------*/
    public function mis_procesos($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
      $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']); /// Componentes
      $tabla ='';


      if($proyecto[0]['tp_id']==1){ /// Proyecto de Inversion
          $tabla.='<h2 class="alert alert-success"><center>MODIFICAR REQUERIMIENTOS DE LA OPERACI&Oacute;N - EJECUCI&Oacute;N DIRECTA</center></h2>';
          $tabla.='
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
              <header>
                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                <h2>PRODUCTOS</h2>
              </header>
              <div>
                  <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false"
                      data-widget-editbutton="false" data-widget-togglebutton="false"
                      data-widget-deletebutton="false"
                      data-widget-fullscreenbutton="false" data-widget-custombutton="false"
                      data-widget-sortable="false">
                      <div >
                        <div class="panel-group smart-accordion-default" id="accordion">
                          '.$this->genera_tabla_prod_act($proy_id).'
                        </div>
                      </div>
                  </div>
              </div>
            </div>
            </article>';
      }
      else{ /// Gasto Corriente

        $tit='OPERACIONES';
        
        if($this->gestion>2019){
          $tit='ACTIVIDADES';
        }
        $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2 class="alert alert-success"><center><b style="font-size: 30px;font-family: Arial; height:65px;">MODIFICAR REQUERIMIENTOS A NIVEL DE '.$tit.' - '.$this->gestion.'</b></center></h2>
                    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
                    <header>
                      <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                      <h2>MIS SERVICIOS</h2>
                    </header>
                    <div>
                        <div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false"
                            data-widget-editbutton="false" data-widget-togglebutton="false"
                            data-widget-deletebutton="false"
                            data-widget-fullscreenbutton="false" data-widget-custombutton="false"
                            data-widget-sortable="false">
                              <div class="panel-group smart-accordion-default" id="accordion">
                                '.$this->genera_tabla_comp_prod($proy_id).'
                              </div>
                        </div>
                    </div>
                  </div>
                  </article>';
      }

      return $tabla;
    }

    /*------------------- Genera Tabla Productos Actividades -------------------*/
    function genera_tabla_prod_act($proy_id){
        $lista_productos = $this->minsumos->lista_productos($proy_id, $this->gestion);
        $tabla = '';
        $cont_acordion = 0;
        foreach ($lista_productos as $row) {
            $componente=$this->model_componente->get_componente($row['com_id'],$this->gestion);
            $lista_actividad = $this->minsumos->lista_actividades($row['prod_id'], $this->gestion);
            if(count($lista_actividad)!=0){
            $cont_acordion++;
            $tabla .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $cont_acordion . '">
                                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i>' .
                $cont_acordion . ' - <b><font color="blue" size=2>PROCESO : '.$componente[0]['com_componente'].' : </font></b><font size=2>' . $row['prod_id'].'-'.$row['prod_producto'] . '</font>
                                    </a>
                                </h4>
                            </div>';
            $tabla .= '<div id="collapse' . $cont_acordion . '" class="panel-collapse collapse">
                            <div class="panel-body no-padding table-responsive">
                                <table class="table table-bordered table-condensed">';
            $tabla .= '            <tbody>
                                          <tr>
                                              <th style="width:2%;"> Nro.</th>
                                              <th style="width:5%;"> Modificar Requerimiento</th>
                                              <th style="width:20%;">Actividad</th>
                                              <th style="width:5%;"> Tipo de Indicador</th>
                                              <th style="width:10%;">Indicador</th>
                                              <th style="width:10%;">Monto Programado</th>
                                          </tr>';
            
            $cont = 1;
            foreach ($lista_actividad as $row_a) {
              $monto=$this->model_actividad->monto_insumoactividad($row_a['act_id']);
              $tabla .= '<tr id="tr'.$row_a['act_id'].'">';
              $tabla .= '<td>' . $cont_acordion . '-' . $cont . '</td>';
              $tabla .= '<td>';
                        if(count($monto)!=0){
                          $tabla.='<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-xs nuevo_ff" title="NUEVO DE REQUERIMIENTO" name="'.$row_a['act_id'].'">
                                    <img src="'.base_url().'assets/ifinal/mod_money.png" width="35" height="35"/>
                                  </a>';
                        }
              $tabla .=' </td>';
              $tabla .= '<td>' . $row_a['act_actividad'].'</td>';
              $tabla .= '<td>' . $row_a['indicador'] . '</td>';
              $tabla .= '<td>' . $row_a['act_indicador'] . '</td>';
              $tabla .= '<td>';
                          if(count($monto)!=0){
                              $tabla.=''.number_format($monto[0]['total'], 2, ',', '.').' Bs.';
                          }
                          else{
                              $tabla.='0.00 Bs.';
                          }
              $tabla .= '</td>';
              $tabla .= '</tr>';
              $cont++;
            }
            $tabla .= '             </tbody>
                                </table>
                           </div>
                      </div>
                 </div>';
            }
        }
        return $tabla;
    }

    /*--------- Genera Tabla Procesos - Operaciones (2018-2019), Actividades (2020) ---------*/
      function genera_tabla_comp_prod($proy_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
        $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);

        $tit='Operaci&oacute;n';
        if($this->gestion>2019){
          $tit='Actividad';
        }

        $tabla = '';
        $cont_acordion = 0;
        foreach ($componentes as $row) {
          $lista_productos = $this->model_producto->list_prod($row['com_id']);
          if(count($lista_productos)!=0){
            $cont_acordion++;
            $tabla .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $cont_acordion . '">
                                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i>' .
                                        $cont_acordion . ' - <b>SERVICIO : </b>' .$row['com_componente'].'
                                    </a>
                                </h4>
                            </div>';
            $tabla .= '<div id="collapse' . $cont_acordion . '" class="panel-collapse collapse">
                            <div class="panel-body no-padding table-responsive">
                                <table class="table table-bordered table-condensed">';
            $tabla .= '            <tbody>
                                    <tr>
                                      <th style="width:2%;"> Nro.</th>
                                      <th style="width:5%;"> Modificar Requerimiento</th>
                                      <th style="width:20%;">'.$tit.'</th>
                                      <th style="width:20%;">Resultado</th>
                                      <th style="width:5%;"> Tipo de Indicador</th>
                                      <th style="width:10%;">Indicador</th>
                                      <th style="width:10%;">Monto Programado POA</th>
                                    </tr>';
            
            $cont = 1;
            foreach ($lista_productos as $row_p) {
                $monto=$this->model_producto->monto_insumoproducto($row_p['prod_id']);
                $tabla .= '<tr>';
                $tabla .= '<td>' . $cont_acordion . '-' . $cont . '</td>';
                $tabla .= '<td align="center">
                            <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-xs nuevo_ff" title="MODIFICAR REQUERIMIENTOS" name="'.$row_p['prod_id'].'" id="'.$row_p['com_id'].'">
                              <img src="'.base_url().'assets/ifinal/mod_money.png" width="35" height="35"/>
                            </a>
                          </td>';
                $tabla .= '<td><b>' . $row_p['prod_cod'] . '</b>.- ' . $row_p['prod_producto'] . '</td>';
                $tabla .= '<td>' . $row_p['prod_resultado'] . '</td>';
                $tabla .= '<td>' . $row_p['indi_descripcion'] . '</td>';
                $tabla .= '<td>' . $row_p['prod_indicador'] . '</td>';
                $tabla .= '<td align=right>';
                            if(count($monto)!=0){
                              $tabla.='<div style="color: blue;">
                                       <h5> '.number_format($monto[0]['total'], 2, ',', '.').' Bs.</h5>
                                      </div>';
                            }
                            else{
                              $tabla.='<span style="color: red;">0.00 Bs.</span>';
                            }
                $tabla .= '</td>';
                $tabla .= '</tr>';
                $cont++;
            }
            $tabla .= '             </tbody>
                                </table>
                           </div>
                      </div>
                 </div>';
          }
        }
        return $tabla;
    }

    /*----------------------------- Genera Tabla Procesos - Insumos-----------------------------*/
    function genera_tabla_comp_ins($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']); /// Componentes
      $tabla = '';
       
      $cont = 0;
        foreach ($componentes AS $row){
          $monto=$this->model_modificacion->suma_monto_requerimientos_componente($row['com_id']);
          $cont++;
          $tabla .= '<tr>';
              $tabla .= '<td>' . $cont . '</td>';
              $tabla .= '<td align=center>
                            <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-xs nuevo_ff" title="NUEVO DE REQUERIMIENTO" name="'.$row['com_id'].'">
                              <img src="'.base_url().'assets/ifinal/mod_money.png" width="35" height="35"/>
                            </a>
                          </td>';
              $tabla .= '<td>' . $row['com_componente'] . '</td>';
              $tabla .= '<td>' . $row['com_ponderacion'] . '</td>';
              $tabla .= '<td>' .$monto[0]['monto_programado']. ' Bs.</td>';
          $tabla .= '</tr>';
        }
      return $tabla;
    }

    /*---------------------------------- Eliminar Requerimiento -------------------------------*/
    function delete_requerimiento(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $cite_id = $post['cite_id']; /// Cite Id
          $ins_id = $post['ins_id']; /// Insumo Id
          $proy_id = $post['proy_id']; /// Proy Id

          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos del Proyecto
          $fase = $this->model_faseetapa->get_id_fase($proy_id); /// Fase Activa
          $insumo = $this->minsumos->get_dato_insumo($ins_id); //// Datos del Insumo

          $query=$this->db->query('set datestyle to DMY');
          /*----------------- Update estado del Insumo -------------*/
          $update_ins = array(
            'ins_estado' => 3, /// 3 : Eliminado
            'ins_mod' => 2, /// 2 : Modulo Modificaciones
            'aper_id' => 0, /// 2 : aper
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->session->userdata("fun_id")
            );
          $this->db->where('ins_id', $ins_id);
          $this->db->update('insumos', $update_ins);
          /*-------------------------------------------------------*/
          if($this->gestion==2018){
            $this->configuracion_requerimientos($insumo[0]['ins_tipo']);
          }

          /*---------------- Insert Insumo Delete -----------------*/
            $data_to_store = array( 
            'ins_id' => $ins_id, /// Insumo Id
            'insc_id' => $cite_id, /// Cite Id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->session->userdata("fun_id"),
            );
          $this->db->insert('_insumo_delete', $data_to_store);
          $dlte_id=$this->db->insert_id();
          /*-------------------------------------------------------*/
          
          $this->crea_actualiza_codigo($proyecto,$cite_id);

          if(count($this->model_modificacion->get_delete_insumo($dlte_id))==1){
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

    function get_tabla_ins_progmensual_directo($insg_id){
       $list_prog_mensual = $this->minsumos->get_list_insumo_financiamiento($insg_id);
       // $prog_mensual = $this->minsumos->lista_progmensual_ins($ins_id);
        $tabla = ' <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="background-color: #0AA699;color: #FFFFFF">MONTO</th>
                                    <th style="background-color: #0AA699;color: #FFFFFF">FF</th>
                                    <th style="background-color: #0AA699;color: #FFFFFF">OF</th>
                                    <th style="background-color: #0AA699;color: #FFFFFF">ET</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Enero</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Febrero</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Marzo</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Abril</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Mayo</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Junio</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Julio</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Agosto</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Septiembre</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Octubre</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Noviembre</th>
                                    <th  style="background-color: #0AA699;color: #FFFFFF">Diciembre</th>
                                </tr>
                            </thead>
                        <tbody>';
        //FINANCIAMIENTO PROGRAMADO
        if(count($list_prog_mensual)!=0)
        {
            foreach ($list_prog_mensual as $row) {
            $tabla .= '<tr>';
            $tabla .= '<td>' . $row['ifin_monto'] . '</td>';
            $tabla .= '<td>' . $row['ff_codigo'] . '</td>';
            $tabla .= '<td>' . $row['of_codigo'] . '</td>';
            $tabla .= '<td>' . $row['et_codigo'] . '</td>';
            $tabla .= '<td>' . number_format($row['mes1'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes2'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes3'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes4'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes5'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes6'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes7'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes8'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes9'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes10'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes11'], 2, ',', '.') . '</td>';
            $tabla .= '<td>' . number_format($row['mes12'], 2, ',', '.') . '</td>';
            $tabla .= '</tr>';
            }
        }
        
        $tabla .= '</tbody>
                 </table>
            </div>';
        return $tabla;
    }

    /*------------Lista de Fuentes de Financiamiento de la operacion ---------------------     */
    public function fuentes_financiamientos($proy_id,$gestion,$tp_ejec,$tp_act){
        $list_recursos = $this->model_faseetapa->presupuesto_asignados($proy_id,$gestion); //// lista de recursos asignados
        $tabla = '';
        $nro=1; $suma_costo_total=0; $monto_asignado=0; $monto_programado=0;
        foreach ($list_recursos as $row) {
            $suma_prog = $this->minsumos->suma_monto_prog_insumo($row['ffofet_id'],$gestion,$tp_ejec,$tp_act); //// Suma Programado Insumo
            $tabla .= '<tr>';
            $tabla .= '<td>' . $nro . '</td>';
            $tabla .= '<td>' . $row['ff_codigo'] . '</td>';
            $tabla .= '<td>' . $row['ff_descripcion'] . '</td>';
            $tabla .= '<td>' . $row['of_codigo'] . '</td>';
            $tabla .= '<td>' . $row['of_descripcion'] . '</td>';
            $tabla .= '<td style="text-align: right;">' . number_format($row['presupuesto_asignado'], 2, ',', '.') . '</td>';
            $tabla .= '<td style="text-align: right;">' . number_format($suma_prog[0]['programado'], 2, ',', '.') . '</td>';
            $tabla .= '<td style="text-align: right;">' . number_format(($row['presupuesto_asignado']-$suma_prog[0]['programado']), 2, ',', '.') . '</td>';
            $tabla .= '</tr>';
            $nro++;
            $monto_asignado=$monto_asignado+$row['presupuesto_asignado']; 
            $monto_programado=$monto_programado+$suma_prog[0]['programado'];
        }
        $tabla .= '<tr>';
        $tabla .= '<td colspan="5" style="text-align: right; background:#0aa699;">
                        <b style="color:#fff;"><center>T O T A L</center></b>
                   </td>';
        $tabla .= '<td style="text-align: right; background:#0aa699;"><b style="color:#fff;">' . number_format($monto_asignado, 2, ',', '.') . '</b></td>';
        $tabla .= '<td style="text-align: right; background:#0aa699;"><b style="color:#fff;">' . number_format($monto_programado, 2, ',', '.') . '</b></td>';
        $tabla .= '<td style="text-align: right; background:#0aa699;"><b style="color:#fff;">' . number_format(($monto_asignado-$monto_programado), 2, ',', '.') . '</b></td>';
        $tabla .= '</tr>';
        return $tabla;
    }
    /*==================================================================================================*/
    /*-------------------- ELIMINAR VARIOS REQUERIMIENTOS ----------------------.*/
    public function delete_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $post['cite_id']; /// Cite Id
          $proy_id = $post['proy_id']; /// Proy Id
          $tp_ins = $post['tp_ins']; /// Tp Ins

          $nro=0;
        if (!empty($_POST["req"]) && is_array($_POST["req"]) ) {
          foreach ( array_keys($_POST["req"]) as $como){
          
          $insumo = $this->minsumos->get_dato_insumo($_POST["req"][$como]); //// Datos del Insumo
          /*----------------- Update estado del Insumo -------------*/
          $update_ins = array(
            'ins_estado' => 3, /// 3 : Eliminado
            'ins_mod' => 2, /// 2 : Modulo Modificaciones
            'fun_id' => $this->session->userdata("fun_id")
          );
          $this->db->where('ins_id', $_POST["req"][$como]);
          $this->db->update('insumos', $update_ins);
          /*-------------------------------------------------------*/
           $this->configuracion_requerimientos($insumo[0]['ins_tipo']);
          /*---------------- Insert Insumo Delete -----------------*/
            $data_to_store = array( 
              'ins_id' => $_POST["req"][$como], /// Insumo Id
              'insc_id' => $cite_id, /// Cite Id
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->session->userdata("fun_id"),
              );
            $this->db->insert('_insumo_delete', $data_to_store);
            $dlte_id=$this->db->insert_id();
          /*-------------------------------------------------------*/
          $nro++;
          }
          $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE '.$nro.' REQUERIMIENTOS SELECCIONADOS');
          redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL ELIMINAR REQUERIMIENTOS');
          redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'');
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Requerimientos</b></font>";
      }
    }


    /*----------------------- GENERAR MENU --------------------*/
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

     /*----------------- TABLA - CONSOLIDADO OPERACION (PRODUCTO) --------------*/
    public function consolidado_partidas_operacion($prod_id,$tp){
      $tabla='';
      $partidas = $this->minsumos->consolidado_partidas_operacion($prod_id);
      if($tp==1){
        $tab='class="table table-bordered" style="width:70%;"';
      }
      elseif($tp==2){
        $tabla ='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center"';
      }
      
      $nro=0;
      $tabla.='<center>
      <table '.$tab.'>
        <thead>
          <tr class="modo1">
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">#</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">C&Oacute;DIGO PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">DESCRIPCI&Oacute;N PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">MONTO PROGRAMADO</font></th>
          </tr>
        </thead>
        <tbody>';
        $monto_total=0;
      foreach ($partidas as $row) {
        $monto_total=$monto_total+$row['total'];
        $nro++;
        $tabla.='<tr class="modo1">';

          $tabla.='<td>'.$nro.'</td>';
          $tabla.='<td>'.$row['par_codigo'].'</td>';
          
          if($tp==1){
            $tabla.='<td>'.$row['par_nombre'].'</td>';
            $tabla.='<td>' . number_format($row['total'], 2, ',', '.') . '</td>';
          }
          else{
            $tabla.='<td>'.mb_convert_encoding(''.$row['par_nombre'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='<td>'.$row['total'].'</td>';
          }
        $tabla.='</tr>';
      }
      $tabla.='<tr>';
          $tabla.='<td></td>';
          $tabla.='<td colspan="2">TOTAL</td>';
          if($tp==1){
            $tabla.='<td>'.number_format($monto_total, 2, ',', '.').'</td>';
          }
          else{
            $tabla.='<td>'.$monto_total.'</td>';
          }
          
        $tabla.='</tr>';
      $tabla.='</tbody>
      </table></center>';

      return $tabla;
    }

    /*----------------- TABLA - CONSOLIDADO OPERACION (DIRECTO) --------------*/
    public function consolidado_partidas_directo($act_id,$tp){
      $tabla='';
      $partidas = $this->minsumos->consolidado_partidas_directo($act_id);
      if($tp==1){
        $tab='class="table table-bordered" style="width:70%;"';
      }
      elseif($tp==2){
        $tabla ='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center"';
      }
      
      $nro=0;
      $tabla.='<center>
      <table '.$tab.'>
        <thead>
          <tr class="modo1">
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">#</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">C&Oacute;DIGO PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">DESCRIPCI&Oacute;N PARTIDA</font></th>
            <th scope="col" bgcolor="#1c7368"><font color="#ffffff">MONTO PROGRAMADO</font></th>
          </tr>
        </thead>
        <tbody>';
        $monto_total=0;
      foreach ($partidas as $row) {
        $monto_total=$monto_total+$row['total'];
        $nro++;
        $tabla.='<tr class="modo1">';

          $tabla.='<td>'.$nro.'</td>';
          $tabla.='<td>'.$row['par_codigo'].'</td>';
          
          if($tp==1){
            $tabla.='<td>'.$row['par_nombre'].'</td>';
            $tabla.='<td>' . number_format($row['total'], 2, ',', '.') . '</td>';
          }
          else{
            $tabla.='<td>'.mb_convert_encoding(''.$row['par_nombre'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='<td>'.$row['total'].'</td>';
          }
        $tabla.='</tr>';
      }
      $tabla.='<tr>';
          $tabla.='<td></td>';
          $tabla.='<td colspan="2">TOTAL</td>';
          if($tp==1){
            $tabla.='<td>'.number_format($monto_total, 2, ',', '.').'</td>';
          }
          else{
            $tabla.='<td>'.$monto_total.'</td>';
          }
          
        $tabla.='</tr>';
      $tabla.='</tbody>
      </table></center>';

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

    /*------------ Sumatoria de las Fuentes Reuqeridas de la Operacion --------------------------*/
    public function suma_total_fuentes($fase_id,$gestion,$tp_ejec,$tp_act){
      $fase_gest = $this->model_faseetapa->fase_etapa_gestion($fase_id,$gestion); //// Lista de las gestiones de la Fase
      $list_fuentes = $this->model_faseetapa->fase_presupuesto_id($fase_gest[0]['ptofecg_id']); //// lista del presupuesto asignado

      $nro=1; $suma_costo_total=0; $monto_asignado=0; $monto_programado=0;
      foreach ($list_fuentes as $row) {
        $suma_prog = $this->minsumos->suma_monto_prog_insumo($row['ffofet_id'],$gestion,$tp_ejec,$tp_act); //// Suma Programado Insumo
        $monto_asignado=$monto_asignado+$row['ffofet_monto']; 
        $monto_programado=$monto_programado+$suma_prog[0]['programado'];
      }
      $suma[1]=$monto_asignado; //// Monto Asigando
      $suma[2]=$monto_programado; /// Monto Programado
      $suma[3]=$monto_asignado-$monto_programado; //// Saldo Por Programar

      return $suma;
    }

    /*------------  Funcion para verificar fechas ---------------------     */
    public function verif_fecha($fecha_act){
        $fecha = $fecha_act;
        $valores = explode('/', $fecha);

        if(count($valores)==3){
            if(checkdate($valores[1],$valores[0],$valores[2])){
               return 'true';
            }
            else{
                return 'false';
            }
        }
        else{
            return 'false';
        }
    }
    /*-------------- Actualiza Nro de Requerimientos -------------------*/
    public function configuracion_requerimientos($ins_tipo){
      $conf=$this->model_proyecto->configuracion();
      if($ins_tipo==1){
        $nro_ins=$conf[0]['conf_rrhhp']-1;
        $update_conf = array('conf_rrhhp' => $nro_ins);
      }
      elseif($ins_tipo==2){
        $nro_ins=$conf[0]['conf_servicios']-1;
        $update_conf = array('conf_servicios' => $nro_ins);
      }
      elseif($ins_tipo==3){
        $nro_ins=$conf[0]['conf_pasajes']-1;
        $update_conf = array('conf_pasajes' => $nro_ins);
      }
      elseif($ins_tipo==4){
        $nro_ins=$conf[0]['conf_viaticos']-1;
        $update_conf = array('conf_viaticos' => $nro_ins);
      }
      elseif($ins_tipo==5){
        $nro_ins=$conf[0]['conf_cons_producto']-1;
        $update_conf = array('conf_cons_producto' => $nro_ins);
      }
      elseif($ins_tipo==6){
        $nro_ins=$conf[0]['conf_cons_linea']-1;
        $update_conf = array('conf_cons_linea' => $nro_ins);
      }
      elseif($ins_tipo==7){
        $nro_ins=$conf[0]['conf_materiales']-1;
        $update_conf = array('conf_materiales' => $nro_ins);
      }
      elseif($ins_tipo==8){
        $nro_ins=$conf[0]['conf_activos']-1;
        $update_conf = array('conf_activos' => $nro_ins);
      }
      elseif($ins_tipo==9){
        $nro_ins=$conf[0]['conf_otros_insumos']-1;
        $update_conf = array('conf_otros_insumos' => $nro_ins);
      }

      $this->db->where('ide', $this->gestion);
      $this->db->update('configuracion', $update_conf);
    }


    /*----- MIGRACION DE REQUERIMIENTOS A UNA OPERACIÓN (2019) -----*/
    function valida_add_requerimientoss(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $this->security->xss_clean($post['cite_id']);
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $tp_ins = $this->security->xss_clean($post['tp_ins']);

          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

        //  $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
        //  $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
        //  $saldo=round(($monto_asig[0]['monto']-$monto_prog[0]['monto']),2);

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);
            //  if($this->suma_monto_total($lineas)<=$saldo){
                /*------------------- Migrando ---------------*/
                $lineas = file($archivotmp);
                $i=0;
                $nro=0;
                foreach ($lineas as $linea_num => $linea){ 
                  if($i != 0){
                      $datos = explode(";",$linea);
                      
                      if(count($datos)==20){
                        $cod_ope = (int)$datos[0]; //// Codigo Operacion
                        $cod_partida = (int)$datos[1]; //// Codigo partida
                        $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// Datos Partida

                        $detalle = utf8_encode(trim($datos[2])); //// Detalle Requerimiento
                        $unidad = utf8_encode(trim($datos[3])); //// Unidad de medida
                        $cantidad = (int)$datos[4]; //// Cantidad
                        $unitario = (float)$datos[5]; //// Costo Unitario
                        $total=round(($cantidad*$unitario),2); // Costo Total

                        $var=7; $sum_prog=0;
                        for ($i=1; $i <=12 ; $i++) {
                          $m[$i]=(float)$datos[$var]; //// Mes i
                          if($m[$i]==''){
                            $m[$i]=0;
                          }
                          $var++;
                          $sum_prog=$sum_prog+$m[$i];
                        }
                        $observacion = utf8_encode(trim($datos[19])); //// Observacion
                      
                        if(count($par_id)!=0 & $cod_partida!=0 & ($total==$sum_prog)){
                          $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id[0]['par_id']);
                          if(count($asig)!=0){
                              $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id[0]['par_id']);
                              $monto_prog=0;
                              if(count($prog)!=0){
                                $monto_prog=$prog[0]['monto'];
                              }

                              $saldo_partida=$asig[0]['monto']-$monto_prog;
                              if($total<=$saldo_partida){
                                $nro++;
                                echo strtoupper($detalle)."<br>";
                                /*----- Subiendo Datos -----*/
                                  /*$query=$this->db->query('set datestyle to DMY');
                                  $data_to_store = array( 
                                  'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                                  'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                                  'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                                  'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                                  'ins_costo_unitario' => $unitario, /// Costo Unitario
                                  'ins_costo_total' => $total, /// Costo Total
                                  'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                                  'par_id' => $par_id[0]['par_id'], /// Partidas
                                  'ins_observacion' => strtoupper($observacion), /// Observacion
                                  'ins_tipo' => 1, /// Ins Tipo
                                  'fun_id' => $this->fun_id, /// Funcionario
                                  'aper_id' => $proyecto[0]['aper_id'], /// aper id
                                  'num_ip' => $this->input->ip_address(), 
                                  'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                                  'ins_mod' => 2,
                                  );
                                  $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                                  $ins_id=$this->db->insert_id();*/

                             
                                  

                                /*--------------------------*/
                              }
                              
                          } 
                        }
                        else{
                          echo "no ingresa : ".$cod_ope.".-".strtoupper($detalle)."-".$total."---".$sum_prog."<br>";
                        }
                      }
                      /*else{
                        echo "Tamaño mayor a 20<br>";
                      }*/
                    }
                    $i++;
                  }

                  /*$this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
                  redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/true');*/
                  /*--------------------------------------------*/
              /*}
              else{
                $this->session->set_flashdata('danger','COSTO PROGRAMADO A SUBIR ES MAYOR AL SALDO POR PROGRAMAR. VERIFIQUE PLANTILLA A MIGRAR');
                redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
              }*/
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          } 
          elseif ($filesize > 100000000) {
            $this->session->set_flashdata('danger','TAMAÑO DEL ARCHIVO');
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          } 
          else {
            $this->session->set_flashdata('danger',$mensaje);
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          }

      } else {
          show_404();
      }
    }

    /*----- MIGRACION DE REQUERIMIENTOS A UNA OPERACIÓN (2019) -----*/
    function valida_add_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_id = $this->security->xss_clean($post['cite_id']);
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $tp_ins = $this->security->xss_clean($post['tp_ins']);

          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

        //  $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
        //  $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
        //  $saldo=round(($monto_asig[0]['monto']-$monto_prog[0]['monto']),2);

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);
            //  if($this->suma_monto_total($lineas)<=$saldo){
                
                /*------------------- Migrando ---------------*/
                $lineas = file($archivotmp);
                $i=0;
                $nro=0;
                foreach ($lineas as $linea_num => $linea){ 
                  if($i != 0){
                      $datos = explode(";",$linea);
                      
                      if(count($datos)==20){
                        $cod_ope = (int)$datos[0]; //// Codigo Operacion
                        $cod_partida = (int)$datos[1]; //// Codigo partida
                        $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// Datos Partida

                        $detalle = utf8_encode(trim($datos[2])); //// Detalle Requerimiento
                        $unidad = utf8_encode(trim($datos[3])); //// Unidad de medida
                        $cantidad = (int)$datos[4]; //// Cantidad
                        $unitario = (float)$datos[5]; //// Costo Unitario
                        $total=round(($cantidad*$unitario),2); // Costo Total

                        $var=7; $sum_prog=0;
                        for ($i=1; $i <=12 ; $i++) {
                          $m[$i]=(float)$datos[$var]; //// Mes i
                          if($m[$i]==''){
                            $m[$i]=0;
                          }
                          $var++;
                          $sum_prog=$sum_prog+$m[$i];
                        }
                        $observacion = utf8_encode(trim($datos[19])); //// Observacion
                      
                        if(count($par_id)!=0 & $cod_partida!=0 & ($total==$sum_prog)){
                          $asig=$this->model_ptto_sigep->get_partida_asignado_sigep($proyecto[0]['aper_id'],$par_id[0]['par_id']);
                          if(count($asig)!=0){
                              $prog=$this->model_ptto_sigep->get_partida_accion($proyecto[0]['aper_id'],$par_id[0]['par_id']);
                              $monto_prog=0;
                              if(count($prog)!=0){
                                $monto_prog=$prog[0]['monto'];
                              }

                              $saldo_partida=$asig[0]['monto']-$monto_prog;
                              if($total<=$saldo_partida){
                                $nro++;
                                /*----- Subiendo Datos -----*/
                                  $query=$this->db->query('set datestyle to DMY');
                                  $data_to_store = array( 
                                  'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                                  'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                                  'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                                  'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                                  'ins_costo_unitario' => $unitario, /// Costo Unitario
                                  'ins_costo_total' => $total, /// Costo Total
                                  'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                                  'par_id' => $par_id[0]['par_id'], /// Partidas
                                  'ins_observacion' => strtoupper($observacion), /// Observacion
                                  'ins_tipo' => 1, /// Ins Tipo
                                  'fun_id' => $this->fun_id, /// Funcionario
                                  'aper_id' => $proyecto[0]['aper_id'], /// aper id
                                  'num_ip' => $this->input->ip_address(), 
                                  'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                                  'ins_mod' => 2,
                                  );
                                  $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                                  $ins_id=$this->db->insert_id();

                                  if($proyecto[0]['tp_id']==1){
                                    /*--------------------------------------------------------*/
                                    $data_to_store2 = array( ///// Tabla _insumoactividad
                                      'act_id' => $tp_ins, /// act id
                                      'ins_id' => $ins_id, /// ins_id
                                    );
                                    $this->db->insert('_insumoactividad', $data_to_store2);
                                   /*----------------------------------------------------------*/
                                  }
                                  else{
                                    /*--------------------------------------------------------*/
                                    $data_to_store2 = array( ///// Tabla InsumoProducto
                                      'prod_id' => $tp_ins, /// prod id
                                      'ins_id' => $ins_id, /// ins_id
                                    );
                                    $this->db->insert('_insumoproducto', $data_to_store2);
                                   /*----------------------------------------------------------*/
                                  }


                                  $data_to_store = array( 
                                  'ins_id' => $ins_id, /// Id Insumo
                                  'g_id' => $this->gestion, /// Gestion
                                  'insg_monto_prog' => $total, /// Monto programado
                                  );
                                  $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                                  $insg_id=$this->db->insert_id();

                                  $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
                                  $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);


                                  /*---------- Guardando Fuente Financiamiento ------*/
                                  $query=$this->db->query('set datestyle to DMY');
                                  $data_to_store3 = array( 
                                  'insg_id' => $insg_id, /// Id Insumo gestion
                                  'ifin_monto' => $total, /// Monto programado
                                  'ifin_gestion' => $this->gestion, /// Gestion
                                  'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
                                  'ff_id' => $fuentes[0]['ff_id'], /// ff id
                                  'of_id' => $fuentes[0]['of_id'], /// ff id
                                  'nro_if' => 1, /// Nro if
                                  );
                                  $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
                                  $ifin_id=$this->db->insert_id();


                                  for ($p=1; $p <=12 ; $p++) { 
                                      if($m[$p]!=0 & is_numeric($unitario)){
                                        $data_to_store4 = array( 
                                          'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                                          'mes_id' => $p, /// Mes 
                                          'ipm_fis' => $m[$p], /// Valor mes
                                        );
                                        $this->db->insert('ifin_prog_mes', $data_to_store4); ///// Guardar en Tabla Insumo Financiamiento Programado Mes
                                      }
                                    }

                                  /*--------------------- iNSERT AUDI ADICIONAR INSUMOS -------------*/
                                    $data_to_store2 = array(
                                      'ins_id' => $ins_id, /// ins_id
                                      'insc_id' => $cite_id, /// cite_id
                                      'num_ip' => $this->input->ip_address(), 
                                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                                      'fun_id' => $this->session->userdata("fun_id"),
                                      );
                                    $this->db->insert('_insumo_add', $data_to_store2);
                                    $insa_id=$this->db->insert_id();
                                  /*-----------------------------------------------------------------*/

                                /*--------------------------*/
                              }
                              
                          } 
                        }
                      }
                      /*else{
                        echo "Tamaño mayor a 20<br>";
                      }*/
                    }
                    $i++;
                  }

                  $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
                  redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/true');
                  /*--------------------------------------------*/
              /*}
              else{
                $this->session->set_flashdata('danger','COSTO PROGRAMADO A SUBIR ES MAYOR AL SALDO POR PROGRAMAR. VERIFIQUE PLANTILLA A MIGRAR');
                redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
              }*/
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          } 
          elseif ($filesize > 100000000) {
            $this->session->set_flashdata('danger','TAMAÑO DEL ARCHIVO');
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          } 
          else {
            $this->session->set_flashdata('danger',$mensaje);
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          }

      } else {
          show_404();
      }
    }
    /*----- SUMA TOTAL MONTO REQUERIMIENTOS A IMPORTAR -------*/
    function suma_monto_total($requerimientos){
      $i=0; $suma=0;
      foreach ($requerimientos as $linea_num => $linea){ 
        if($i != 0){
            $datos = explode(";",$linea);
            
            if(count($datos)==21){
                $suma=$suma+(float)$datos[7];
            }
          }

          $i++;
      }

      return $suma;
    } 


    /*----- MIGRACION DE REQUERIMIENTOS A UNA OPERACIÓN -------*/
    function valida_add_requerimientos2(){
      if ($this->input->post()) {
          $post = $this->input->post();

          $cite_id = $post['cite_id'];
          $proy_id = $post['proy_id'];
          $tp_ins = $post['tp_ins'];

         /* $proy_id = $post['proy_id']; /// proy id
          $prod_id = $post['prod_id']; /// pfec id
          $producto = $this->model_producto->get_producto_id($prod_id); ///// DATOS DEL PRODUCTO
          */
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
               
            /*------------------- Migrando ---------------*/
          $lineas = file($archivotmp);
          $i=0;
          $nro=0;
          //Recorremos el bucle para leer línea por línea
          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){
                $datos = explode(";",$linea);
                
                if(count($datos)==21){
                  $cod_ope = (int)$datos[0]; //// Codigo Operacion
                  $cod_partida = (int)$datos[1]; //// Codigo partida
                  $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                  $detalle = utf8_encode(trim($datos[3])); //// descripcion
                  $unidad = utf8_encode(trim($datos[4])); //// Unidad
                  $cantidad = (int)$datos[5]; //// Cantidad
                  $unitario = (float)$datos[6]; //// Costo Unitario
                  $total = (float)$datos[7]; //// Costo Total
                  if(!is_numeric($unitario)){
                    if($cantidad!=0){
                      $unitario=round(($total/$cantidad),2); 
                    }
                  }

                  $var=8;
                  for ($i=1; $i <=12 ; $i++) {
                    $m[$i]=(float)$datos[$var]; //// Mes i
                    if($m[$i]==''){
                      $m[$i]=0;
                    }
                    $var++;
                  }

                  $observacion = utf8_encode(trim($datos[20])); //// Observacion
                
                  if(count($par_id)!=0 & $cod_partida!=0){
                    
                    $nro++;
                     $query=$this->db->query('set datestyle to DMY');
                      $data_to_store = array( 
                      'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                      'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                      'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                      'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                      'ins_costo_unitario' => $unitario, /// Costo Unitario
                      'ins_costo_total' => $total, /// Costo Total
                      'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                      'par_id' => $par_id[0]['par_id'], /// Partidas
                      'ins_observacion' => strtoupper($observacion), /// Observacion
                      'ins_tipo' => 1, /// Ins Tipo
                      'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                      'aper_id' => $proyecto[0]['aper_id'], /// aper id
                      'num_ip' => $this->input->ip_address(), 
                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                      'ins_mod' => 2,
                      );
                      $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                      $ins_id=$this->db->insert_id();

                      if($proyecto[0]['tp_id']==1){
                        /*--------------------------------------------------------*/
                        $data_to_store2 = array( ///// Tabla _insumoactividad
                          'act_id' => $tp_ins, /// act id
                          'ins_id' => $ins_id, /// ins_id
                        );
                        $this->db->insert('_insumoactividad', $data_to_store2);
                       /*----------------------------------------------------------*/
                      }
                      else{
                        /*--------------------------------------------------------*/
                        $data_to_store2 = array( ///// Tabla InsumoProducto
                          'prod_id' => $tp_ins, /// prod id
                          'ins_id' => $ins_id, /// ins_id
                        );
                        $this->db->insert('_insumoproducto', $data_to_store2);
                       /*----------------------------------------------------------*/
                      }

                      $data_to_store = array( 
                      'ins_id' => $ins_id, /// Id Insumo
                      'g_id' => $this->gestion, /// Gestion
                      'insg_monto_prog' => $total, /// Monto programado
                      );
                      $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                      $insg_id=$this->db->insert_id();

                      $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion); //// DATOS DE LA FASE GESTION
                      $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                      /*------------------- Guardando Fuente Financiamiento ------*/
                      $query=$this->db->query('set datestyle to DMY');
                      $data_to_store3 = array( 
                      'insg_id' => $insg_id, /// Id Insumo gestion
                      'ifin_monto' => $total, /// Monto programado
                      'ifin_gestion' => $this->gestion, /// Gestion
                      'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
                      'ff_id' => $fuentes[0]['ff_id'], /// ff id
                      'of_id' => $fuentes[0]['of_id'], /// ff id
                      'nro_if' => 1, /// Nro if
                      );
                      $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
                      $ifin_id=$this->db->insert_id();


                      for ($p=1; $p <=12 ; $p++) { 
                              if($m[$p]!=0 & is_numeric($unitario)){
                                $data_to_store4 = array( 
                                  'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                                  'mes_id' => $p, /// Mes 
                                  'ipm_fis' => $m[$p], /// Valor mes
                                );
                                $this->db->insert('ifin_prog_mes', $data_to_store4); ///// Guardar en Tabla Insumo Financiamiento Programado Mes
                              }
                            }

                      /*--------------------- iNSERT AUDI ADICIONAR INSUMOS -------------*/
                        $data_to_store2 = array(
                          'ins_id' => $ins_id, /// ins_id
                          'insc_id' => $cite_id, /// cite_id
                          'num_ip' => $this->input->ip_address(), 
                          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                          'fun_id' => $this->session->userdata("fun_id"),
                          );
                        $this->db->insert('_insumo_add', $data_to_store2);
                        $insa_id=$this->db->insert_id();
                      /*-----------------------------------------------------------------*/

                  }

                }
                /*else{
                    echo "Tamaño mayor a 21<br>";
                  }*/
              }
              $i++;
            }

            $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');

            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/true');
            /*--------------------------------------------*/
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect(site_url("").'/mod/mod_requerimiento/'.$cite_id.'/'.$proy_id.'/'.$tp_ins.'/false');
          } 
          elseif ($filesize > 100000000) {
              //redirect('');
          } 
          else {
              $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
              echo '<script>alert("' . $mensaje . '")</script>';
          }

      } else {
          show_404();
      }
    }



    /*-------------------------------- CUADRO COMPARATIVO PARTIDAS --------------------------*/
    function cuadro_comparativo($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS FASE ACTIVA

      $data['partidas']= $this->cuadro_partidas($proy_id); //// Cuadro comparativo de partidas
      $this->load->view('admin/modificacion/operaciones/requerimientos/partidas', $data);
    }


    /*--------------- Comparativo Partidas A nivel De Acciones Operativas -------------------*/
    public function cuadro_partidas($proy_id){ 
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $comparativo= $this->comparativo_partidas_acciones($proyecto[0]['dep_id'],$proyecto[0]['aper_id']); //// Cuadro comparativo de partidas
      $tabla ='';
       $tabla .='
       <style>
        .table{font-size: 10px;
              width: 100%;
              max-width:1550px;;
        overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 9px;
        }
        td{
          padding: 1.4px;
          font-size: 9px;
        }
      </style>
        <div class="verde"></div>
        <div class="blanco"></div>
        <table width="100%">
          <tr>
            <td width=20%; text-align:center;"">
              <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
            </td>
            <td width=60%; class="titulo_pdf" align=left>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                      <td colspan="2" style="width:100%; height: 1.2%; font-size: 9pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:20%; height: 1.2%"><b>DIR. ADM.</b></td>
                      <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:20%; height: 1.2%"><b>UNI. EJEC.</b></td>
                      <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:20%; height: 1.2%"><b>APER. PROG.</b></td>
                      <td style="width:80%;">: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:20%; height: 1.2%"><b>REPORTE</b></td>
                      <td style="width:80%;">: CUADRO COMPARATIVO POR PARTIDAS</td>
                  </tr>
              </table>  
            </td>
            <td width=20%; text-align:center;"">
            </td>
          </tr>
        </table><hr>';
        
        $tabla.=''.$comparativo.'<hr>';

      return $tabla;
    }

    /*--------------- TABLA COMPARATIVO POR UNIDAD (2019) -------------------*/
    public function comparativo_partidas($dep_id,$aper_id){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Asig
      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $tabla .='<table id="dt_basic1" class="table table-bordered">
                  <thead>
                    <tr class="modo1" align=center>
                      <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                      <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                    </tr>
                  </thead>
                  <tbody>';
      
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';

      return $tabla;
    }


    /*--------------- TABLA REPORTE COMPARATIVO POR UNIDAD (2019) -------------------*/
    public function comparativo_partidas_acciones($dep_id,$aper_id){ 
      $tabla ='';
      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,1); // Asig
      $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,2); // Prog

      $nro=0;
      $monto_asig=0;
      $monto_prog=0;
      $tabla .='<table id="dt_basic1" class="table table-bordered">
                  <thead>
                    <tr class="modo1" align=center>
                      <th bgcolor="#1c7368" style="width:1%;"><font color=#fff>NRO.</font></th>
                      <th bgcolor="#1c7368" style="width:5%;"><font color=#fff>C&Oacute;DIGO PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:15%;"><font color=#fff>DETALLE PARTIDA</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>PRESUPUESTO PROGRAMADO</font></th>
                      <th bgcolor="#1c7368" style="width:10%;"><font color=#fff>MONTO DIFERENCIA</font></th>
                    </tr>
                  </thead>
                  <tbody>';
      if(count($partidas_asig)>count($partidas_prog)){
        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);
            $prog=0;
            if(count($part)!=0){
              $prog=$part[0]['monto'];
            }
            $dif=($row['monto']-$prog);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

            $nro++;
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
            $monto_asig=$monto_asig+$row['monto'];
            $monto_prog=$monto_prog+$prog;
        }

        foreach($partidas_prog  as $row){
          $nro++;
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
          if(count($part)==0){
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }
            $tabla .='<tr class="modo1" bgcolor='.$color.'>
                        <td align=center>'.$nro.'</td>
                        <td align=center>'.$row['codigo'].'</td>
                        <td align=left>'.$row['nombre'].'</td>
                        <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                        <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                        <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                      </tr>';
                      $monto_asig=$monto_asig+$asig;
                      $monto_prog=$monto_prog+$row['monto'];
          }
        }
      }
      else{
        foreach($partidas_prog  as $row){
          $part=$this->model_ptto_sigep->get_partida_asig_accion($dep_id,$aper_id,$row['par_id']);
            $asig=0;
            if(count($part)!=0){
              $asig=$part[0]['monto'];
            }
            $dif=($asig-$row['monto']);
            $color='#f1f1f1';
            if($dif<0){
              $color='#f9cdcd';
            }

          $nro++;
          $tabla .='<tr class="modo1" bgcolor='.$color.'>
                      <td align=center>'.$nro.'</td>
                      <td align=center>'.$row['codigo'].'</td>
                      <td align=left>'.$row['nombre'].'</td>
                      <td align=right>'.number_format($asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                      <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                    </tr>';
          $monto_asig=$monto_asig+$asig;
          $monto_prog=$monto_prog+$row['monto'];
        }

        foreach($partidas_asig  as $row){
          $part=$this->model_ptto_sigep->get_partida_accion_regional($dep_id,$aper_id,$row['par_id']);

          if(count($part)==0){
              $prog=0;
              if(count($part)!=0){
                $prog=$part[0]['monto'];
              }
              $dif=($row['monto']-$prog);
              $color='#f1f1f1';
              if($dif<0){
                $color='#f9cdcd';
              }

              $nro++;
              $tabla .='<tr class="modo1" bgcolor='.$color.'>
                          <td align=center>'.$nro.'</td>
                          <td align=center>'.$row['codigo'].'</td>
                          <td align=left>'.$row['nombre'].'</td>
                          <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                          <td align=right>'.number_format($prog, 2, ',', '.').'</td>
                          <td align=right>'.number_format($dif, 2, ',', '.').'</td>
                        </tr>';
              $monto_asig=$monto_asig+$row['monto'];
              $monto_prog=$monto_prog+$prog;
          }
            
        }

      }
      $tabla .='</tbody>
                  <tr class="modo1">
                      <td colspan=3><strong>TOTAL</strong></td>
                      <td align=right>'.number_format($monto_asig, 2, ',', '.').'</td>
                      <td align=right>'.number_format($monto_prog, 2, ',', '.').'</td>
                      <td align=right>'.number_format(($monto_asig-$monto_prog), 2, ',', '.').'</td>
                    </tr>
                </table>';

      return $tabla;
    }

    
    /*--- ACTUALIZAR DATOS - OPERACIONES - REQUERIMIENTOS (2019) ----*/
    function valida_update_datos_operacion_requerimientoss(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite_nota = $this->security->xss_clean($post['cite']);
          $tp = $this->security->xss_clean($post['tp']);
          
          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              $lineas = file($archivotmp);
              $nro_aciertos=0;$nro_naciertos=0;
              if($tp==1){
                $tipo='OPERACIONES';
                /// --------------------------------------------------
                $i=0; $sw=0; $var_aper=0;
                foreach ($lineas as $linea_num => $linea){ 
                  if($i != 0){
                    $datos = explode(";",$linea);
                    $regional = $datos[0]; //// Regional
                    $apertura = $datos[1]; //// apertura
                    $ae = $datos[2]; //// Cod Accion Estrategica
                    $prod_id = $datos[3]; //// Prod id
                    $prod_priori = $datos[4]; //// Prioridad
                    $cod_indicador = $datos[5]; //// Cod Indicador
                    $proy_id = $datos[6]; //// Proy id
                    

                  }
                  $i++;
                }
                /// --------------------------------------------------
                echo "Mod. Operaciones";
              }
              else{
                //// ---------------------------------------------------------
                  $lineas = file($archivotmp);
                  $tipo='REQUERIMIENTOS';

                  $i=0; $sw=0; $var_aper=0;
                  foreach ($lineas as $linea_num => $linea){ 
                    if($i != 0){
                      $datos = explode(";",$linea);
                      $regional = $datos[0]; //// Regional
                      $prod_id = $datos[1]; //// Prod id
                      $ins_id = $datos[2]; //// Ins id
                      $cantidad = $datos[3]; //// Cantidad
                      $cunitario = $datos[4]; //// Costo Unitario
                      $ctotal = $datos[5]; //// Costo Total
                      $proy_id = $datos[6]; //// Proy id

                      $var=7; $sum=0;
                      for ($k=1; $k <=12 ; $k++) {
                        $m[$k]=(float)$datos[$var]; //// Mes i
                        $sum=$sum+$m[$k];
                        $var++;
                      }

                      if($ctotal==$sum){

                        if($var_aper!=$proy_id){
                          /*--------- Actualiza Cite --------*/
                          $data_to_store = array(
                            'insc_cite' => strtoupper($cite_nota),
                            'insc_fecha' => date("d/m/Y H:i:s"),
                            'fun_id' => $this->fun_id,
                            'proy_id' => $proy_id,
                            'g_id' => $this->gestion,
                          );
                          $this->db->insert('_insumo_mod_cite',$data_to_store);
                          $cite_id=$this->db->insert_id();
                          /*---------------------------------*/
                          $var_aper=$proy_id;
                        }

                        if($this->copia_insumo($cite_id,$ins_id)){
                          
                          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
                          $this->crea_actualiza_codigo($proyecto,$cite_id);
                          /*--------- UPDATE INSUMO ------------*/
                          $update_ins = array(
                            'ins_cant_requerida' => $cantidad,
                            'ins_costo_unitario' => $cunitario,
                            'ins_costo_total' => $ctotal,
                            'ins_mod' => 2, /// mod
                            'ins_estado'=> 2, /// mod
                            'num_ip' => $this->input->ip_address(), 
                            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                          );
                          $this->db->where('ins_id', $ins_id);  
                          $this->db->update('insumos', $update_ins);
                          /*-----------------------------------*/

                          /*----------- UPDATE TEMPORALIDAD -----------*/
                          $insumo=$this->minsumos->get_insumo_fin($ins_id);
                          $temp=$this->minsumos->insumo_programado_mensual($insumo[0]['ifin_id']);
                          if(count($temp)!=0){
                            $var=7;
                            for ($p=1; $p <=12 ; $p++) {
                              $m[$p]=(float)$datos[$var]; //// Mes i
                              $mes=$this->minsumos->get_insumo_programado_mensual($insumo[0]['ifin_id'],$p);
                              if(count($mes)!=0){
                                /*--------- UPDATE INSUMO ------------*/
                                  $update_prog = array(
                                    'ipm_fis' => $m[$p]
                                  );
                                  $this->db->where('ifin_id', $insumo[0]['ifin_id']);
                                  $this->db->where('mes_id', $mes[0]['mes_id']);
                                  $this->db->update('ifin_prog_mes', $update_prog);
                                /*-----------------------------------*/
                                $nro_aciertos++;
                              //  echo "NRO.".$i." - MONTO TOTAL : ".$ctotal." - ".$sum."-> INS ID ".$ins_id." - MES : ".$mes[0]['mes_id']." --- MONTO : ".$mes[0]['ipm_fis']."<br>";
                              }
                              $var++;
                            }
                          }
                          /*-------------------------------------------*/
                        }
                      }
                      else{
                        $nro_naciertos++; 
                      }
                      
                    }
                    $i++;
                  }
                  
              }

          //  $this->session->set_flashdata('success',''.$tipo.' ACTUALIZADAS: '.$valores[1].'.-'.$tipo.' ACTUALIZADAS, '.$valores[2].'.-'.$tipo.' NO ACTUALIZADOS');
          //  redirect(site_url("").'/mod/list_top');

            /*$this->session->set_flashdata('success',''.$tipo.' ACTUALIZADAS: '.$nro_aciertos.', '.$tipo.' NO ACTUALIZADAS, '.$nro_naciertos.'');
            redirect(site_url("").'/mod/list_top');*/
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL SUBIR ARCHIVO');
            redirect(site_url("").'/mod/list_top');
          }

      } else {
          show_404();
      }
    }


    /*--------- UPDATE REQUERIMIENTOS --------------*/
    public function update_list_requerimientos($lineas,$cod_cite,$nro_aciertos,$nro_naciertos){ 
      /*--------------- REGISTRANDO CITES ----------------*/
/*      $data_to_store = array(
        'insc_cite' => strtoupper($cod_cite),
        'insc_fecha' => date("d/m/Y H:i:s"),
        'fun_id' => $this->fun_id,
        'proy_id' => $proy_id,
        'g_id' => $this->gestion,
      );
      $this->db->insert('_insumo_mod_cite',$data_to_store);
      $insc_id=$this->db->insert_id();*/
      /*---------------------------------------------------------------*/


      $i=0;
      foreach ($lineas as $linea_num => $linea){ 
        if($i != 0){
          $datos = explode(";",$linea);
          $regional = $datos[0]; //// Regional
          $prod_id = $datos[1]; //// Prod id
          $ins_id = $datos[2]; //// Ins id
          $cantidad = $datos[3]; //// Cantidad
          $cunitario = $datos[4]; //// Costo Unitario
          $ctotal = $datos[5]; //// Costo Total
          $mod = $datos[6]; //// mod

          $var=7; $sum=0;
          for ($k=1; $k <=12 ; $k++) {
            $m[$k]=(float)$datos[$var]; //// Mes i
            $sum=$sum+$m[$k];
            $var++;
          }

          if($ctotal==$sum){


            /*--------- UPDATE INSUMO ------------*/
            $update_ins = array(
              'ins_cant_requerida' => $cantidad,
              'ins_costo_unitario' => $cunitario,
              'ins_costo_total' => $ctotal
            );
            $this->db->where('ins_id', $ins_id);
            $this->db->update('insumos', $update_ins);
            /*-----------------------------------*/
            
            $insumo=$this->minsumos->get_insumo_fin($ins_id);
            $temp=$this->minsumos->insumo_programado_mensual($insumo[0]['ifin_id']);
            if(count($temp)!=0){
              $var=7;
              for ($p=1; $p <=12 ; $p++) {
                $m[$p]=(float)$datos[$var]; //// Mes i
                $mes=$this->minsumos->get_insumo_programado_mensual($insumo[0]['ifin_id'],$p);
                if(count($mes)!=0){
                  /*--------- UPDATE INSUMO ------------*/
                    $update_prog = array(
                      'ipm_fis' => $mes[0]['mes_id'],
                      'num_ip' => $this->input->ip_address(), 
                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                      'ins_mod' => 2
                    );
                    $this->db->where('ifin_prog_mes', $insumo[0]['ifin_id']);
                    $this->db->where('mes_id', $mes[0]['mes_id']);
                    $this->db->update('insumos', $update_prog);
                  /*-----------------------------------*/
                  $nro_aciertos++;
                //  echo "NRO.".$i." - MONTO TOTAL : ".$ctotal." - ".$sum."-> INS ID ".$ins_id." - MES : ".$mes[0]['mes_id']." --- MONTO : ".$mes[0]['ipm_fis']."<br>";
                }
                $var++;
              }
            }
          }
          else{
            $nro_naciertos++;
          }
          
        }
        $i++;
      }
    }

    /*----- ESTILOS PARA LOS RE´PORTES -----*/
    function estilo_vertical(){
        $estilo_vertical = '<style>
        body{
            font-family: sans-serif;
            }
        table{
            font-size: 8px;
            width: 100%;
            background-color:#fff;
        }
        .mv{font-size:10px;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}

        .titulo_pdf {
            text-align: left;
            font-size: 8px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 8px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 6px;
        background-color: #1c7368;
        background-repeat: repeat-x;
        color: #FFFFFF;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #558FA6;
        border-bottom-color: #558FA6;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 6px;
        font-weight:bold;
       
        background-image: url(fondo_tr01.png);
        background-repeat: repeat-x;
        color: #34484E;
       
        }
        .tabla .modo1 td {
        padding: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #A4C4D0;
        border-bottom-color: #A4C4D0;
        }
    </style>';
        return $estilo_vertical;
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

    public function get_ins_titulo($num) {
        switch ($num) {
            case 1:
                return (' - RECURSO HUMANO PERMANENTE');
                break;
            case 2:
                return (' - DETERMINACI&Oacute;N DE SERVICIOS');
                break;
            case 3:
                return (' - PASAJES');
                break;
            case 4:
                return (' - VIÁTICOS');
                break;
            case 5:
                return (' - CONSULTORÍA POR PRODUCTO');
                break;
            case 6:
                return (' - CONSULTORÍA EN LÍNEA');
                break;
            case 7:
                return (' - MATERIALES Y SUMINISTROS');
                break;
            case 8:
                return (' - ACTIVOS FIJOS');
                break;
            case 9:
                return (' - OTROS INSUMOS');
                break;
        }
    }

    /*======================================================================================*/
}