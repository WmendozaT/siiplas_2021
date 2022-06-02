<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class ejecucion_finpi extends CI_Controller{

    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/model_componente');
        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dep_id = $this->session->userData('dep_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tmes = $this->session->userData('trimestre');
        $this->ppto= $this->session->userData('verif_ppto');
        $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema

    }

    /*------- TITULO --------*/
    public function formulario(){
      $tabla='';
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="well">
            <h2>FORMULARIO DE EJECUCI&Oacute;N PRESUPUESTARIA - '.$this->verif_mes[2].' / '.$this->gestion.'</h2>
            '.$this->lista_proyectos($this->dep_id).'
          </div>
        </article>';

      return $tabla;
    } 



    /*-- LISTA DE PROYECTOS DE INVERSION --*/
    public function lista_proyectos($dep_id){
      $proyectos=$this->model_proyecto->list_pinversion(1,4);
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $estado_proyecto=$this->model_proyecto->proy_estado();

      $tabla='';

      $tabla.='
      <form class="smart-form" method="post">
      <input type="hidden" name="base" value="'.base_url().'">
      <div class="panel-group smart-accordion-default" id="accordion-2">';
        $nro=0;
        foreach($proyectos as $row){
          $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
          $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']); /// lista de partidas asignados por proyectos

          
          /// --- monto total ejecutado del proyecto
          $monto_ejec_total=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep($row['aper_id']);
          $ppto_ejec_total=0;

          if(count($monto_ejec_total)!=0){
            $ppto_ejec_total=$monto_ejec_total[0]['ejecutado_total'];
          }

          ///---- Calculando Avance financiero
          $avance_fin_total=0;
          if($row['proy_ppto_total']!=0){
            $avance_fin_total=round(($ppto_ejec_total/$row['proy_ppto_total'])*100,2);
          }


          $nroP=0;
          $nro++;
          $class='class="panel-collapse collapse"';
          $colapsed='class="collapsed"';
          
          if($nro==1){
            $class='class="panel-collapse collapse in"';
            $colapsed='';
          }
          $tabla.='
          <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion-2" href="#'.$nro.'" '.$colapsed.' title="'.$row['proy_id'].'"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i><b>'.$row['proy_sisin'].' - '.$row['proy_nombre'].'</b></a>
            </h4>
          </div>
          <div id="'.$nro.'" '.$class.'>
            <div class="panel-body">

              <div class="table-responsive" align=center>
              <br>
                <table class="table table-bordered" style="width:90%;">
                  <tr>
                    <td colspan=8 style="font-size: 18px;font-family: Arial;" align="left"><b>DATOS DEL PROYECTO</b></td>
                  </tr>
                  <tr bgcolor="#3f3f46" align="center">
                    <td style="font-size: 12px;font-family: Arial; width:7%; color:#fff"><b>DISTRITAL</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:10%; color:#fff"><b>FASE</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>CATEGORIA PROGRAMATICA</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>COSTO TOTAL PROYECTO</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>ESTADO PROYECTO</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>AVANCE FÍSICO</b></td>
                    <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>AVANCE FINANCIERO</b></td>
                    <td style="width:3%;"></td>
                    <td style="width:1%;"></td>
                  </tr>
                <tbody>
                  <tr id="tr_color'.$row['proy_id'].'">
                    <td style="font-size: 11px;font-family: Arial;height:25px;">'.strtoupper($row['dist_distrital']).'</td>
                    <td style="font-size: 11px;font-family: Arial"><b>'.strtoupper($fase[0]['fase']).'</b> - '.$fase[0]['descripcion'].'</td>
                    <td style="font-size: 11px;font-family: Arial">'.$row['aper_programa'].' '.$row['aper_proyecto'].' 000</td>
                    <td style="font-size: 11px;font-family: Arial" align=right>'.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
                    <td style="font-size: 11px;font-family: Arial">

                      <select class="form-control" id="est_proy'.$row['proy_id'].'" name="est_proy'.$row['proy_id'].'" title="SELECCIONE ESTADO DE PROYECTO">
                        <option value="0" selected>Seleccione Estado Proyecto</option>';
                        foreach($estado_proyecto as $est){
                          if($est['ep_id']==$row['proy_estado']){ 
                            $tabla.='<option value="'.$est['ep_id'].'" selected>'.strtoupper($est['ep_descripcion']).'</option>';
                          }
                          else{ 
                            $tabla.='<option value="'.$est['ep_id'].'" >'.strtoupper($est['ep_descripcion']).'</option>';
                          }  
                        }
                      $tabla.='
                      </select>

                    </td>
                    <td style="font-size: 11px;font-family: Arial">
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" id=efis_pi'.$row['proy_id'].' value="'.round($row['avance_fisico'],2).'" onkeyup="verif_pi_ejecfis('.$row['proy_id'].','.$row['avance_fisico'].',this.value);" onkeypress="if (this.value.length < 50) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      </label>
                    </td>
                    <td style="font-size: 18px;font-family: Arial;color:blue" align=center><div id="fin'.$row['proy_id'].'"><b>'.$avance_fin_total.' %</b></div></td>
                    <td style="font-size: 10px;font-family: Arial;" align=center>
                      <div id="but_pi'.$row['proy_id'].'"><button type="button" name="'.$row['proy_id'].'" id="'.$row['proy_id'].'" onclick="guardar_pi('.$row['proy_id'].');"  class="btn btn-default"><img src="'.base_url().'assets/Iconos/disk.png" WIDTH="45" HEIGHT="45"/><br>ACTUALIZAR<br>INFORMACIÓN</button></div>
                    </td>
                    <td align=center><div id="success'.$row['proy_id'].'"></div></td>
                  </tr>
                </tbody>
              </table>
              <br>

              <table class="table table-bordered" style="width:90%;">
                <tr title='.$row['aper_id'].'>
                  <td colspan=10 style="font-size: 18px;font-family: Arial;" align="left"><b>EJECUCIÓN PRESUPUESTARIA : '.$this->verif_mes[2].' / '.$this->gestion.'</b></td>
                </tr>
                <tr bgcolor="#3f3f46" align="center">
                  <td style="font-size: 12px;font-family: Arial; width:1%; color:#fff"><b>#</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:3%; color:#fff"><b>PARTIDA</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:3%; color:#fff"><b>PPTO. INICIAL</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:3%; color:#fff"><b>PPTO. MODIFICADO</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:3%; color:#fff"><b>PPTO. AJUSTADO FINAL</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>REGISTRO EJECUCIÓN '.$this->verif_mes[2].' / '.$this->gestion.'</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:15%; color:#fff"><b>OBSERVACI&Oacute;N</b></td>
                  <td style="width:2%;"></td>
                  <td style="font-size: 12px;font-family: Arial; width:5%; color:#fff"><b>TOTAL EJECUTADO (Bs.)</b></td>
                  <td style="font-size: 12px;font-family: Arial; width:3%; color:#fff"><b>(%) AVANCE FINANCIERO</b></td>
                  <td style="width:2%;"></td>
                  <td style="width:1%;"></td>
                </tr>
              <tbody>';
                  $suma_ppto_inicial=0;
                  $suma_ppto_modificado=0;
                  $suma_ppto_vigente=0;
                  $suma_ppto_ejecutado=0;
                  foreach($ppto_asig as $partida){
                    $nroP++;
                    $ppto_modificado=$this->model_ptto_sigep->monto_modificado_x_partida($partida['sp_id']); /// ppto modificado por partida
                    $ppto_ejecutado_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($partida['sp_id'],$this->verif_mes[1]); ///  monto ejecutado por partidas
                    $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($partida['sp_id'],$this->verif_mes[1]); /// Observacion

                    $monto_ini=$partida['importe'];
                    $monto_mod=0;
                    $monto_fin=$partida['importe'];
                    if(count($ppto_modificado)!=0){
                      $monto_ini=$ppto_modificado[0]['ppto_ini'];
                      $monto_mod=$ppto_modificado[0]['ppto_modificado'];
                      $monto_fin=$ppto_modificado[0]['ppto_final'];
                    }

                    $ppto_ejecutado=0;
                    $tipo_registro=0;
                    $display='style="display:none;"';
                    $titulo_boton='GUARDAR';
                    if(count($ppto_ejecutado_mensual)!=0){
                      $ppto_ejecutado=$ppto_ejecutado_mensual[0]['ppto_ejec'];
                      $tipo_registro=1;
                      $display='';
                      $titulo_boton='MODIFICAR';
                    }

                    $observacion_ejecutado='';
                    if(count($obs_ejec_mensual)!=0){
                      $observacion_ejecutado=$obs_ejec_mensual[0]['observacion'];
                    }

                    //// suma montos programados totales
                    $suma_ppto_inicial=$suma_ppto_inicial+$monto_ini;
                    $suma_ppto_modificado=$suma_ppto_modificado+$monto_mod;
                    $suma_ppto_vigente=$suma_ppto_vigente+$monto_fin;
                    ///

                    $suma_ppto_ejecutado=$suma_ppto_ejecutado+$ppto_ejecutado;


                    /// montos ejecutados por partidas
                    $monto_total_ejecutado=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($partida['sp_id']); /// monto total ejecutado
                    $monto_ejecutado=0;
                    if(count($monto_total_ejecutado)!=0){
                      $monto_ejecutado=$monto_total_ejecutado[0]['ejecutado'];
                    }

                    /// Porcentaje de Avance por partidas
                    $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($partida['sp_id']); /// Get partida sigep
                    $porcentaje_avance_fin=0;
                    if(count($get_partida_sigep)!=0){
                      $porcentaje_avance_fin=round((($monto_ejecutado/$get_partida_sigep[0]['importe'])*100),2);
                    }


                    $tabla.='
                    <tr id="tr_color_partida'.$partida['sp_id'].'">
                      <td align="center" title='.$partida['sp_id'].'>'.$nroP.'</td>
                      <td style="font-size: 11px;font-family: Arial;" align=center><b>'.$partida['partida'].'</b></td>
                      <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_ini, 2, ',', '.').'</td>
                      <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_mod, 2, ',', '.').'</td>
                      <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_fin, 2, ',', '.').'</td>
                      <td>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" id=ejec'.$partida['sp_id'].' value="'.round($ppto_ejecutado,2).'" onkeyup="verif_valor(this.value,'.$partida['sp_id'].','.$this->verif_mes[1].','.$row['aper_id'].');"  onkeypress="if (this.value.length < 50) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                        </label>
                      </td>
                      <td>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" id=obs'.$partida['sp_id'].' onkeyup="verif_observacion(this.value,'.$partida['sp_id'].');"  title="OBSERVACION">'.strtoupper($observacion_ejecutado).'</textarea>
                        </label>
                      </td>
                      <td align=center>
                        <div id="but'.$partida['sp_id'].'" '.$display.'><button type="button" name="'.$partida['sp_id'].'" id="'.$partida['sp_id'].'" onclick="guardar('.$partida['sp_id'].','.$row['aper_id'].');"  class="btn btn-default"><img src="'.base_url().'assets/ifinal/guardar.png" WIDTH="40" HEIGHT="40"/><br>'.$titulo_boton.'</button></div>
                      </td>
                      <td style="font-size: 15px;font-family: Arial; color:blue" align=right><b><div id="ppto_fin_partida'.$partida['sp_id'].'">Bs. '.number_format($monto_ejecutado, 2, ',', '.').'</div></b></td>
                      <td style="font-size: 18px;font-family: Arial; color:blue" align=center><b><div id="avance_fin'.$partida['sp_id'].'">'.$porcentaje_avance_fin.' %</div></b></td>
                      <td align=center>
                        <center>';
                          if(count($this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($partida['sp_id']))!=0){
                            $tabla.='<a href="#" data-toggle="modal" data-target="#modal_detalle_ejec" class="btn btn-default detalle_ejec_ppto_partidas" name="'.$partida['sp_id'].'" id="'.$row['proy_nombre'].' - '.$partida['partida'].' : '.strtoupper($partida['par_nombre']).'" title="VER DETALLE EJECUCION PRESUPUESTARIA"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="50" HEIGHT="50"/><br>VER DETALLE</a>';
                          }
                        $tabla.='
                        </center>
                      </td>
                      <td align=center><div id="success_partida'.$partida['sp_id'].'"></div></td>
                    </tr>';
                  }
              $tabla.='
              </tbody>
                <tr>
                  <td colspan=2></td>
                  <td align=right><b>'.number_format($suma_ppto_inicial, 2, ',', '.').'</b></td>
                  <td align=right><b>'.number_format($suma_ppto_modificado, 2, ',', '.').'</b></td>
                  <td align=right><b>'.number_format($suma_ppto_vigente, 2, ',', '.').'</b></td>
                  <td align=right><b><div id="ppto_ejec_mes'.$row['aper_id'].'">'.number_format($suma_ppto_ejecutado, 2, ',', '.').'</div></b></td>
                  <td colspan=2></td>
                  <td align=right><b><div id="ppto_ejec_total'.$row['aper_id'].'">Bs. '.number_format($ppto_ejec_total, 2, ',', '.').'</div></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            </table>
            <br>
            </div>
            </div>
          </div>
        </div>';
        }
      $tabla.='
      </div>
      </form>';

      return $tabla;
    }






    /*------- PARA EL EXCEL --------*/
    public function lista_proyectoss($dep_id){
      $proyectos=$this->model_proyecto->list_pinversion(1,4);
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';

      $tabla.=' 
        <form class="smart-form" method="post">
          <input type="hidden" name="base" value="'.base_url().'">
          <div class="row">
            <section class="col col-3">
              <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/>
            </section>

          <div class="table-responsive">
          <table class="table table-bordered" style="width:100%;" id="datos">
            <thead>
              <tr>
                <th style="width:1%;">#</th>
                <th style="width:5%;">DISTRITAL</th>
                <th style="width:5%;">CODIGO SISIN</th>
                <th style="width:15%;">NOMBRE DEL PROYECTO</th>
                <th style="width:10%;">FASE</th>
                <th style="width:3%;">PARTIDA</th>
                <th style="width:5%;">PPTO. INICIAL '.$this->gestion.'</th>
                <th style="width:5%;">PPTO. MODIFICADO '.$this->gestion.'</th>
                <th style="width:5%;">PPTO. AJUSTADO FINAL '.$this->gestion.'</th>
                <th style="width:5%;">CATEGORIA PROGRAMATICA</th>
                <th style="width:5%;">COSTO TOTAL DEL PROYECTO (Bs.)</th>
                <th style="width:5%;">REGISTRO EJECUCIÓN '.$this->verif_mes[2].' / '.$this->gestion.'</th>
                <th style="width:5%;"></th>
                <th style="width:7%;">TOTAL EJECUTADO (Bs.)</th>
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($proyectos as $row){
                $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
                $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']);
                $nro++;

                foreach($ppto_asig as $partida){
                  $ppto_modificado=$this->model_ptto_sigep->monto_modificado_x_partida($partida['sp_id']);
                  $monto_ini=$partida['importe'];
                  $monto_mod=0;
                  $monto_fin=$partida['importe'];
                  if(count($ppto_modificado)!=0){
                    $monto_ini=$ppto_modificado[0]['ppto_ini'];
                    $monto_mod=$ppto_modificado[0]['ppto_modificado'];
                    $monto_fin=$ppto_modificado[0]['ppto_final'];
                  }
                  $tabla.='
                  <tr>
                    <td>'.$nro.'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td style="font-size: 13px;font-family: Arial;"><b>'.$row['proy_nombre'].'</b></td>
                    <td>'.strtoupper($fase[0]['fase']).' - '.$fase[0]['descripcion'].'</td>
                    <td style="font-size: 18px;font-family: Arial;" align=center><b>'.$partida['partida'].'</b></td>
                    <td align=right>'.number_format($monto_ini, 2, ',', '.').'</td>
                    <td align=right>'.number_format($monto_mod, 2, ',', '.').'</td>
                    <td align=right>'.number_format($monto_fin, 2, ',', '.').'</td>
                    <td>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>';
                }
              }
            $tabla.='
            </tbody>
          </table>
        </div>
        </form>';

      return $tabla;
    }




    ////// LIBRERIAS PARA REPORTES GERENCIALES  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_pi(){
      $tabla='';
      $tabla.='
      <aside id="left-panel">
        <div class="login-info">
          <span>
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
              <span>
                <i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;'.$this->session->userdata("user_name").'
              </span>
              <i class="fa fa-angle-down"></i>
            </a>
          </span>
        </div>
        <nav>
          <ul>
            <li class="">
            <a href="'.site_url("").'/admin/dashboard" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
            </li>
            <li class="text-center">
              <a href="#" title="EJECUCION PROYECTOS DE INVERSION"> <span class="menu-item-parent">EJECUCIÓN P.I.</span></a>
            </li>
            <li>
              <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Ejecución financiera</span></a>
              <ul>
                <li>
                  <a href="'.site_url("").'/ejec_fin_pi">Registro Ejecución<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                </li>
                <li>
                  <a href="'.site_url("").'/rep_ejec_fin_pi/">Reporte Financiero<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }


        /// ---- STYLE -----
    public function style(){
      $tabla='';

      $tabla.='   
      <style>
        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
        }
        th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
        }
            #mdialTamanio{
            width: 80% !important;
        }
        #modificacion{
          width: 80% !important;
        }
        #programacion{
          width: 50% !important;
        }
        #certificacion{
          width: 40% !important;
        }
        #evaluacion{
          width: 80% !important;
        }
          input[type="checkbox"] {
          display:inline-block;
          width:25px;
          height:25px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
    </style>';

      return $tabla;
    }


     /*------ NOMBRE MES -------*/
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

  /*=====================================================================*/
    public function get_mes($mes_id){
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
?>