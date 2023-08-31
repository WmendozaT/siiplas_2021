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
            $this->mes = $this->mes_nombre();
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
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $data['datos_proyecto']='<h1> PROYECTO : <small> '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'</small></h1>';
      
      if($proyecto[0]['tp_id']==4){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $data['datos_proyecto']='<h1> '.$proyecto[0]['establecimiento'].' : <small> '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'].'</small></h1>';
      }

      $data['formulario']='
        <article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
          <div class="well">
            <h2 class="alert alert-info"><center>INGRESAR CITE </center></h2>
                <form action="'.site_url("").'/modificaciones/cmod_requerimientos/valida_cite" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'">
                <fieldset>
                <section>
                  <div class="row">
                    <label class="label col col-2">NRO CITE</label>
                    <div class="col col-10">
                      <label class="input"> <i class="icon-append fa fa-user"></i>
                        <input type="text" name="cite" id="cite" placeholder="XX-XX-XXX">
                      </label>
                    </div>
                  </div>
                </section>
                <section>
                  <div class="row">
                    <label class="label col col-2">FECHA CITE</label>
                    <div class="col col-10">
                      <label class="input"> <i class="icon-append fa fa-calendar"></i>
                      <input type="text" name="fm" id="fm" class="form-control datepicker" data-dateformat="dd/mm/yy" onKeyUp="this.value=formateafecha(this.value);" placeholder="dd/mm/YY">
                    </label>
                    </div>
                  </div>
                </section>
                <section>
                  <div class="inline-group">
                    <label class="radio">
                      <input type="radio" name="tp" onchange="radioChange(this);" id="ppto" value="0">
                      <i></i>TECHO PRESUPUESTARIO</label>
                    <label class="radio">
                      <input type="radio" name="tp" onchange="radioChange(this);" id="saldo" value="1">
                      <i></i>REVERSION DE SALDOS</label>
                  </div>
                </section>
                <div id="obs" style="display: none">
                  <section>
                    <div class="row">
                      <label class="label col col-2">OBSERVACIÓN</label>
                      <div class="col col-10">
                        <label class="textarea"> <i class="icon-append fa fa-comment"></i>                    
                          <textarea rows="5" name="observacion"></textarea> 
                        </label>
                      </div>
                    </div>
                  </section>
                </div>
              </fieldset>
                
              <div id="btn" style="display: none">
                <footer>
                  <button type="button" name="add_form" id="add_form" class="btn btn-primary">INGRESAR</button>
                  <a href="'.base_url().'index.php/mod/list_top" class="btn btn-default" title="Volver atras">CANCELAR</a>
                </footer>
              </div>
              <center><img id="load" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="35" height="35"></center></td>
            </form> 
          </div>
        </article> ';


      $this->load->view('admin/modificacion/techo/cite_ppto', $data);
    }

    /*--------- VALIDA CITES TECHO PRESUPUESTARIO (MODIFICACIONES)----------*/
    public function valida_cite(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
          $cite = $this->security->xss_clean($post['cite']);
          $fecha = $this->security->xss_clean($post['fm']);
          $tp = $this->security->xss_clean($post['tp']);
          
          $obs='';
          if($tp==1){ //// Reversion de Saldos
            $obs = $this->security->xss_clean($post['observacion']);
          }

        //  echo "proy ".$proy_id." - ".$cite." - ".$fecha." - ".$tp.' - '.$obs;
          /*--------- GUARDANDO CITE PRESUPUESTO ---------*/
            $data_to_store = array(
              'proy_id' => $proy_id,
              'cppto_cite' => strtoupper($cite),
              'cppto_fecha' => $fecha,
              'tp' => $tp,
              'observacion' => $obs,
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
        $data['partidas_asig']=$this->list_partidas($data['cite']); /// Partidas Asignadas
        $data['list_partidas']=$this->model_ptto_sigep->list_partidas_noasig($data['proyecto'][0]['aper_id']); /// Aper id

        $this->load->view('admin/modificacion/techo/edit_partidas', $data);
      }
      else{
        redirect(site_url("").'/mod/cite_techo/'.$data['cite'][0]['proy_id']); 
      }
      
    }

    /*------ Lista de Partidas a modificar (2019-220-2021) -------*/
    function list_partidas($cite){
      $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); //// DATOS DEL PROYECTO
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
                  <tr title="'.$proyecto[0]['aper_id'].'">';
                    if($cite[0]['tp']==0){
                      $tabla.='
                      <td style="width:15%;">
                      <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-primary nuevo_ff btn-lg" title="NUEVO REGISTRO PARTIDA" style="width:100%; height:100%;">NUEVO PARTIDA</a>
                    </td>';
                    }
                    $tabla.='
                    <td style="width:5%;">BUSCADOR</td>
                    <td style="width:80%;"><input type="text" class="form-control" id="kwd_search" value="" style="width:100%;"/></td>
                  </tr>
                </table><br>
                <table class="table table-bordered" id="table" style="width:80%;" align="center">
                  <thead>
                    <tr>
                      <th bgcolor="#1c7368"><font color="#ffffff">'.$proyecto[0]['aper_id'].'#</font></th>
                      <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">C&Oacute;DIGO PARTIDA</font></th>
                      <th style="width:15%;"bgcolor="#1c7368"><font color="#ffffff">DESCRIPCI&Oacute;N PARTIDA</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO ASIGNADO</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">MONTO PROGRAMADO POA</font></th>';
                      if($cite[0]['tp']==0){
                        $tabla.='<th bgcolor="#1c7368"><font color="#ffffff">MONTO A MODIFICAR.</font></th>';
                      }
                      $tabla.='
                      <th bgcolor="#1c7368"><font color="#ffffff">PRESUPUESTO FINAL</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff">ELIMINAR</font></th>
                      <th bgcolor="#1c7368"><font color="#ffffff" style="width:8%;">MONTO REVERTIDO</font></th>';
                      if($cite[0]['tp']==1){
                        $tabla.='
                        <th bgcolor="#1c7368"><font color="#ffffff">REGISTRAR SALDO</font></th>
                        <th bgcolor="#1c7368"></th>';
                      }
                      $tabla.='
                    </tr>
                  </thead>
                  <tbody>';
                  foreach($partidas  as $row){
                    $programado=$this->model_ptto_sigep->get_partida_programado_poa($proyecto[0]['aper_id'],$row['par_id']);
                    $monto_poa=0;

                    if(count($programado)!=0){
                      $monto_poa=$programado[0]['ppto_programado'];          
                    }

                    $nro++;
                    $tabla .='
                    <tr class="modo1">
                      <td align=center>'.$nro.'<input type="hidden" name="sp_id[]" value="'.$row['sp_id'].'"></td>
                      <td align=center><b>'.$row['partida'].'</b></td>
                      <td align=left><b>'.$row['par_nombre'].'</b></td>
                      <td align=right><input type="hidden" id="monto'.$nro.'" name="monto_inicial[]" value="'.$row['importe'].'">
                        <b>'.number_format($row['importe'], 2, ',', '.').'</b>
                      </td>
                      <td align=right>
                        <b>'.number_format($monto_poa, 2, ',', '.').'</b>
                      </td>';
                      if($cite[0]['tp']==0){
                        $tabla.='<td align=center><input type="number" class="form-control" onkeyup="suma_monto_partida('.$nro.');" name="monto_dif[]" id="dif'.$nro.'" value="0" title="MONTO A INCREMENTAR" pattern="^[0-9]" pattern="^[0-9]"  min="1" step="1"></td>';
                      }
                      $tabla.='
                      <td align=center>
                        <input type="text" class="form-control" id="mpartida'.$nro.'" value="'.$row['importe'].'" title="MONTO FINAL" disabled>
                        <input type="hidden" name="monto_partida[]" id="mpartida'.$nro.'" value="'.$row['importe'].'">
                      </td>
                      <td align=center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR MONTO PARTIDA"  name="'.$row['sp_id'].'" id="'.$cite[0]['proy_id'].'" ><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>
                      <td align="right" style="width:8%;"><b>'.number_format($row['ppto_saldo_ncert'], 2, '.', ',').'</b></td>';
                        if($cite[0]['tp']==1){
                          $tabla.='
                          <td align="center">
                            <a href="#" data-toggle="modal" data-target="#modal_add_saldo" class="btn btn-default add_saldo" name="'.$row['sp_id'].'" title="SALDO REVERTIDO"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a>
                          </td>
                          <td align="center">
                          </td>';
                        }
                      $tabla.='
                    </tr>';
                  }
                  $tabla.='</tbody>
                  <tr>
                    <td colspan="3">TOTAL </td>
                    <td align=center>'.$monto_total.'</td>
                    <td align=center></td>';
                    if($cite[0]['tp']==0){
                      $tabla.='<td align=center></td>';
                    }
                    $tabla.='
                    <td align=center><input type="text" class="form-control" name="total" value="'.$monto_total.'" disabled="true"></td>
                    <td align=center></td>';
                    if($cite[0]['tp']==1){
                      $tabla.='<td align=center colspan="2"></td>';
                    }
                    $tabla.='
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




    /*---- GET DATOS REQUERIMIENTO ----*/
    public function get_partida(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $sp_id = $this->security->xss_clean($post['sp_id']);
        $ppto_asignado=$this->model_ptto_sigep->get_sp_id($sp_id);

          if(count($ppto_asignado)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'ppto_asignado' => $ppto_asignado,
            );
          }
          else{
            $result = array(
              'respuesta' => 'error',
            );
          }
          /// --------------------------------------
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*---- VALIDA SALDO NO EJECUTADO ----*/
    public function guardar_saldo_ppto(){
      if($this->input->post()){
        $post = $this->input->post();
        $sp_id = $this->security->xss_clean($post['sp_id']);
        $cite_id = $this->security->xss_clean($post['cite_id']);
        $saldo = $this->security->xss_clean($post['saldo']);
        $partida_ppto=$this->model_ptto_sigep->get_sp_id($sp_id);

        /*-------- Insert historial de saldos -------*/
        $data_to_store = array(
          'sp_id' => $sp_id,
          'monto_revertido' => $saldo,
          'ppto_anterior' => $partida_ppto[0]['importe'],
          'cppto_id' => $cite_id,
        );
        $this->db->insert('saldo_partida',$data_to_store);
        /*------------------------------------------*/

        $saldo=$this->model_ptto_sigep->suma_saldo_revertido($sp_id);

        if(count($saldo)!=0){
          $update_saldo = array(
            'ppto_saldo_ncert' => $saldo[0]['saldo']
          );
          $this->db->where('sp_id', $sp_id);
          $this->db->update('ptto_partidas_sigep', $update_saldo);
        }

        redirect(site_url("").'/mod/techo/'.$cite_id);

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


    /*----- REPORTE CITE TECHO -------*/
    public function reporte_techo($cppto_id){
      $cite=$this->model_ptto_sigep->get_cite_techo($cppto_id);
      if(count($cite)!=0){
        $proyecto=$this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// PROY INVERSION
        if($proyecto[0]['tp_id']==4){
          $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cite[0]['proy_id']); /// GASTO CORRIENTE
        }

        $data['cabecera']=$this->cabecera($proyecto,$cite);  
        $data['mes'] = $this->mes_nombre();
        
        if($cite[0]['tp']==0){
          $data['consolidado']=$this->mis_modificaciones_techo($cppto_id); /// modificacion presupuestaria
        }
        else{
          $data['consolidado']=$this->reversion_saldos($cite); /// reversion de saldos
        }
        
        $data['pie_rep']=$cite[0]['cppto_cite'];
        //$data['lista']=$this->mis_modificaciones_techo($cppto_id);

        $this->load->view('admin/modificacion/techo/reporte_mod_techo', $data);
      }
      else{
        echo "ERROR";
      }
    }



  //// Cabecera modificacion presupuestaria
  public function cabecera($proyecto,$cite){
    /// tp_rep 0 : modificacion presupuestaria
    /// tp rep 1 : reversion de saldos
    
    $titulo_rep='MODIFICACION PRESUPUESTARIA';
    if($cite[0]['tp']==1){
      $titulo_rep='REVERSION DE SALDOS';
    }

    
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 13px;font-family: Arial;">
                        <td style="width:40%;height: 20%;">&nbsp;&nbsp;<b> '.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                    </tr>
                </table>
            </td>
            <td style="width:30%; height: 2%; font-size: 8px;text-align:right;">
              '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px black; text-align: center;">
              <td style="width:12%; text-align:center;">';
              if($proyecto[0]['proy_estado']==4 && $this->gestion>2021){
                $tabla.='<qrcode value="'.$this->session->userdata('rd_poa').'" style="border: none; width: 14mm; color: #1c7368"></qrcode><br><b>POA APROBADO</b>';
              }
              $tabla.='
              </td>
              <td style="width:80%; height: 5%">
                  <table align="center" border="0" style="width:100%;">
                      <tr style="font-size: 23px;font-family: Arial;">
                          <td style="height: 30%;"><b>PLAN OPERATIVO ANUAL GESTIÓN - '.$this->gestion.'</b></td>
                      </tr>
                      <tr style="font-size: 20px;font-family: Arial;">
                        <td style="height: 5%;">'.$titulo_rep.'</td>
                      </tr>
                  </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
              <td style="width:70%;">
              </td>
              <td style="width:30%; height: 3%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 13px;font-family: Arial;">
                      <td align=center style="width:100%;height: 40%;"><b>'.$cite[0]['cppto_cite'].'</b></td>
                    </tr>
                </table>
              </td>
          </tr>
      </table>
      
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
         <tr>
            <td style="width:1.5%;"></td>
            <td style="width:97%;height: 1%;">
              <hr>
            </td>
            <td style="width:1.5%;"></td>
        </tr>
        <tr>
            <td style="width:1.5%;"></td>
            <td style="width:97%;height: 3%;">
             
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr>
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dep_cod'].' '.strtoupper ($proyecto[0]['dep_departamento']).'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dist_cod'].' '.strtoupper ($proyecto[0]['dist_distrital']).'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>';
                  if($proyecto[0]['tp_id']==4){
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>'.$proyecto[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper ($proyecto[0]['proy_nombre']).' '.$proyecto[0]['abrev'].'</td></tr>
                        </table>
                    </td>';
                  }
                  else{
                    $tabla.='
                    <td style="width:20%;">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                            <tr><td style="width:95%;height: 40%;" bgcolor="#e6e5e5"><b>PROYECTO</b></td><td style="width:5%;"></td></tr>
                        </table>
                    </td>
                    <td style="width:80%;">
                        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                            <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['aper_programa'].''.$proyecto[0]['proy_sisin'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper ($proyecto[0]['proy_nombre']).'</td></tr>
                        </table>
                    </td>';
                  }
                $tabla.='
                </tr>
               
            </table>
          </td>
          <td style="width:1.5%;"></td>
        </tr>
        <tr>
          <td style="width:1.5%;"></td>
          <td style="width:97%;height: 1%;">
            <hr>
            <br><b style="font-size: 8px;font-family: Arial;">DETALLE : </b>
          </td>
          <td style="width:1.5%;"></td>
        </tr>
      </table>';
    return $tabla;
  }


  //// Reversion de saldos
  function reversion_saldos($cite){
      $saldos_revertidos_partidas=$this->model_ptto_sigep->lista_monto_partidas_revertidos($cite[0]['cppto_id']);
      $tabla='';

       $tabla.='
        <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
          <thead>
            <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
              <th style="width:2%;height:15px;color:#FFF;">#</th>
              <th style="width:15%;color:#FFF;">PARTIDA</th>
              <th style="width:20%;color:#FFF;">PPTO. VIGENTE</th>
              <th style="width:20%;color:#FFF;">PPTO. SALDO REVERTIDO</th>
              <th style="width:20%; color:#FFF;">PPTO. TOTAL</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;$suma=0;
            foreach($saldos_revertidos_partidas as $row){
              $suma=$suma+(($row['ppto_inicial']+$row['presupuesto_revertido']));
              $nro++;
              $tabla.='
              <tr>
                <td style="height:10px;" align="center">'.$nro.'</td>
                <td style="font-size:15px; text-align:center"><b>'.$row['partida'].'</b></td>
                <td style="text-align:right">'.number_format($row['ppto_inicial'], 2, ',', '.').'</td>
                <td style="text-align:right">'.number_format($row['presupuesto_revertido'], 2, ',', '.').'</td>
                <td style="text-align:right">'.number_format(($row['ppto_inicial']+$row['presupuesto_revertido']), 2, ',', '.').'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
            <tr>
              <td colspan=4></td>
              <td style="font-size:12px; text-align:right"><b>'.number_format($suma, 2, ',', '.').'</b></td>
            </tr>
          </table>';
      return $tabla;
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