<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION FORM 1
class Eval_acp extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        //$this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        //$this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
       // $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->mes = $this->mes_nombre();
    }

    /*------- TITULO FORM 1 --------*/
    public function titulo(){
      $tabla='';
      $trimestre=$this->model_evaluacion->trimestre();
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="well">
              <h2>EVALUACI&Oacute;N DE A.C.P. (FORMULARIO N° 1) '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</h2>
            </div>
        </article>';

      if ($this->tp_adm==1) {
        $tabla.='
          <div id="row">
            <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <a href="#" data-toggle="modal" data-target="#modal_update_evaluacion" class="btn btn-default update_evaluacion" style="width:100%;" title="ACTUALIZAR EVALUACION OBJETIVO REGIONAL" ><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="30"/>&nbsp;UPDATE EVALUACIÓN DE OPERACIONES</a>    
            </article>
          </div>';
      }

      $tabla.='
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well" id="load_update_temp_general" style="display: none;" >
          <center><img src="'.base_url().'/assets/img_v1.1/preloader.gif" style="width:13%; height:2.5%"><br><b>ACTUALIZANDO EVALUACIÓN POA - OPERACIONES ...</b></center>
        </div>
      </article>';

      return $tabla;
    } 

    /*---- LISTA DE REGIONALES FORM 1 ----*/
    public function regionales(){
      $regionales=$this->model_proyecto->list_departamentos();
      $trimestre=$this->model_evaluacion->trimestre();
      $tabla='
      <input name="base" type="hidden" value="'.base_url().'">
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
            <header><h2><b>EVALUACI&Oacute;N DE A.C.P. (FORMULARIO N° 1) </b> - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</h2></header>
            <fieldset>          
              <div class="row">
                <section class="col col-2">
                  <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                  <select class="form-control" id="d_id" name="d_id" title="SELECCIONE REGIONAL">
                  <option value="0"><b>SELECCIONE REGIONAL</b></option>';
                  foreach($regionales as $row){
                    if($row['dep_id']!=0){
                      $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                    }
                  }
                  $tabla.='
                  </select>
                </section>
            </fieldset>
          </form>
          </div>
        </article>
        <article class="col-sm-12">
          <div id="titulo_lista"></div>
        </article>';

      return $tabla;
    }

  //// FORMULARIO 1 POR REGIONAL 2022
  public function formulario_n1_regional($dep_id){
    $tabla='';
    $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
    $departamento=$this->model_proyecto->get_departamento($dep_id);
    $trimestre=$this->model_evaluacion->trimestre();

    $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
          <h2><b>REGISTRO DE FORMULARIO N° 1</b></h2>
        </header>
        <div>

          <div class="widget-body">

            <div class="tabs-left">
              <ul class="nav nav-tabs tabs-left" id="demo-pill-nav">';
              $nro=0;
              foreach($acp_regional as $row){
                $nro++;
                $activo='';
                if($nro==1){
                  $activo='class="active"';
                }
                $tabla.='
                <li '.$activo.'>
                  <a href="#tab-r'.$nro.'" data-toggle="tab"><span class="badge bg-color-blue txt-color-white">'.$row['og_codigo'].' </span> A.C.P. <b>'.$row['og_codigo'].'</b></a>
                </li>';
              }
              $tabla.='
              </ul>

              <div class="tab-content">';
                $nro2=0;
                foreach($acp_regional as $oge){
                  $acp_eval_regional=$this->model_evaluacion->get_meta_oregional($oge['pog_id'],$this->tmes);/// datos de evaluacion al trimestre actual
                  $nro2++;
                  $active='class="tab-pane"';
                  if($nro2==1){
                    $active='class="tab-pane active"';
                  }

                  $tipo='';
                  if($oge['indi_id']==2){
                    $tipo='%';
                  }

                  /// Datos de evaluacion
                  $ejec=0;
                  $mverificacion='';
                  $tp=0;
                  $tit='GUARDAR DATOS DE EVALUACION';
                  if(count($acp_eval_regional)!=0){ /// Evaluado al Trimestre
                    $tp=1;
                    $ejec=$acp_eval_regional[0]['ejec_fis'];
                    $mverificacion=$acp_eval_regional[0]['tmed_verif'];
                    $tit='MODIFICAR DATOS DE EVALUACION';
                  }
                  ///-----

                  $tabla.='
                  <div '.$active.' id="tab-r'.$nro2.'">
                    <p>
                        <div class="modal-dialog" style="width:65%;">
                          <div class="modal-content">
                            <div class="modal-body">
                              <form method="post" id="form_eval'.$oge['pog_id'].'" class="form-horizontal">
                                <input type="hidden" name="tp" id="tp'.$oge['pog_id'].'" value='.$tp.'>
                                <fieldset>
                                  <legend>EVALUACIÓN A.C.P. - '.strtoupper($departamento[0]['dep_departamento']).'</b><br>'.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</legend>
                                  <div id="porcentaje'.$oge['pog_id'].'">
                                    '.$this->calificacion_acp_regional($oge['tp_indi_og'],round($this->get_suma_evaluado($oge['pog_id'],$this->tmes),2),round($ejec,2),round($oge['prog_fis'],2)).'
                                  </div>
                                  <br>
                                  <div class="form-group">
                                    <b>A.C.P. '.$oge['og_codigo'].'</b>.- '.$oge['og_objetivo'].'
                                  </div>

                                  <div class="form-group">
                                    <b>META</b> .- '.round($oge['prog_fis'],2).' '.$tipo.'
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">EJECUCIÓN META</label>
                                    <div class="col-md-10">
                                      <input class="form-control" type="text" name="ejec" id="ejec'.$oge['pog_id'].'" value="'.round($ejec,2).'" onkeyup="verif_valor_ejecucion('.$oge['pog_id'].',this.value);" onkeypress="if (this.value.length < 4) { return numerosDecimales(event);}else{return false; }">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">MEDIO DE VERIFICACIÓN</label>
                                    <div class="col-md-10">
                                      <textarea class="form-control" name="mverificacion"  id="mverificacion'.$oge['pog_id'].'" rows="5">'.$mverificacion.'</textarea>
                                    </div>
                                  </div>
                                
                                </fieldset>

                                <div class="form-actions">
                                  <div class="row">
                                  <div id="log'.$oge['pog_id'].'"></div>
                                    <div class="col-md-12">
                                      <div id="btn_eval'.$oge['pog_id'].'">
                                        <footer>
                                          <button type="button" id="subir_eval'.$oge['pog_id'].'" onclick="guardar_acp_regional('.$oge['pog_id'].');" class="btn btn-info">'.$tit.'</button>
                                        </footer>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                </form>
                            </div>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </p>
                  </div>';
                }
              $tabla.='
              </div>
            </div>
          </div>
        </div>
      </div>';

    return $tabla;
  }




  //// FORMULARIO 1 POR REGIONAL 2022
  public function formulario_n1_regional2($dep_id){
    $tabla='';
    $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
    $departamento=$this->model_proyecto->get_departamento($dep_id);
    $trimestre=$this->model_evaluacion->trimestre();
    
      $configuracion=$this->model_configuracion->get_configuracion_session();
      $date_actual = strtotime(date('Y-m-d')); //// fecha Actual
      $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
      $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final


    $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
          <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
          <h2>...</h2>
        </header>
        <div>
          <div class="jarviswidget-editbox">
          </div>
          <div class="widget-body">
            <p>
              <h2><b>EVALUACIÓN A.C.P. '.strtoupper($departamento[0]['dep_departamento']).'</b> - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</h2>
            </p>
            <hr class="simple">
            <ul id="myTab1" class="nav nav-tabs bordered">';
              $nro=0;
              foreach($acp_regional as $row){
                $nro++;
                $activo='';
                if($nro==1){
                  $activo='class="active"';
                }

                $tipo=''; $size='col col-5';
                if($oge['indi_id']==2){
                  $tipo='%';
                  $size='col col-7';
                }

                $tabla.='
                <li '.$activo.'>
                  <a href="#s'.$nro.'" data-toggle="tab"><b>A. C. P.</b><span class="badge bg-color-green txt-color-white">'.$row['og_codigo'].'</span></a>
                </li>';
              }
            $tabla.='
            </ul>

            <div id="myTabContent1" class="tab-content padding-10">';
            $nro2=0;
            foreach($acp_regional as $oge){
              $lista_ogestion=$this->model_objetivoregion->list_oregional_regional($oge['og_id'],$dep_id);
              $acp_eval_regional=$this->model_evaluacion->get_meta_oregional($oge['pog_id'],$this->tmes);/// datos de evaluacion al trimestre actual
              $nro2++;
              $active='class="tab-pane fade"';
              if($nro2==1){
                $active='class="tab-pane fade in active"';
              }

              $tipo=''; $size='col col-5';
              if($oge['indi_id']==2){
                $tipo='%';
                $size='col col-7';
              }

              $tabla.='
              <div '.$active.' id="s'.$nro2.'">
                <div class="row">
                <div class="widget-body">

                  <form action="'.site_url().'/ejecucion/cevaluacion_pei/valida_update_evaluacion_acp" method="post" id="form_eval'.$oge['pog_id'].'"  class="smart-form" style="background-color:#a4caeb;">
                    <legend><b>A.C.P. '.$oge['og_codigo'].'</b>.- '.$oge['og_objetivo'].'</legend>
                    <input type="hidden" name="pog_id" value='.$oge['pog_id'].'>
                    '.$oge['tp_indi_og'].'
                    <fieldset>
                      <div class="row">';
                        $ejec=0;
                        $mverificacion='';
                        $tp=0;
                        $tit='GUARDAR DATOS DE EVALUACION';
                        if(count($acp_eval_regional)!=0){ /// Evaluado al Trimestre
                          $tp=1;
                          $ejec=$acp_eval_regional[0]['ejec_fis'];
                          $mverificacion=$acp_eval_regional[0]['tmed_verif'];
                          $tit='MODIFICAR DATOS DE EVALUACION';
                        }

                      $tabla.='
                        <input type="hidden" name="tp" id="tp'.$oge['pog_id'].'" value='.$tp.'>
                        <section class="col col-1">
                          <label class="label"><b>(%) CUMPLIMIENTO</b></label>
                          <div id="porcentaje'.$oge['pog_id'].'">'.$this->calificacion_acp_regional($oge['tp_indi_og'],round($this->get_suma_evaluado($oge['pog_id'],$this->tmes),2),round($ejec,2),round($oge['prog_fis'],2)).'</div>
                        </section>
                        <section class="col col-2">
                          <label class="label"><b>META (A.C.P.)</b></label>
                          <label class="input"> <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="meta_prog" id="meta_prog" value="'.round($oge['prog_fis'],2).' '.$tipo.'" disabled=true>
                          </label>
                        </section>';

                        if($oge['tp_indi_og']==0){
                          $tabla.='
                          <section class="col col-2">
                            <label class="label"><b>EJECUCIÓN ACUMULADO</b></label>
                            <label class="input"> <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="ejec_registrado" id="ejec_registrado" value="'.round($this->get_suma_evaluado($oge['pog_id'],$this->tmes),2).'" disabled=true>
                            </label>
                          </section>';
                        }

                        $tabla.='
                        <section class="col col-2">
                          <label class="label" style="color:#0000ff;"><b>REGISTRO DE EJECUCIÓN (*)</b></label>
                          <label class="input"> <i class="icon-append fa fa-tag"></i>
                            <input type="text" name="ejec" id="ejec'.$oge['pog_id'].'" value="'.round($ejec,2).'" onkeyup="verif_valor_ejecucion('.$oge['pog_id'].',this.value);">
                          </label>
                        </section>
                        <section class="col col-5">
                          <label class="label" style="color:#0000ff;"><b>MEDIO DE VERIFICACIÓN (*)</b></label>
                        <label class="textarea"> <i class="icon-append fa fa-tag"></i><textarea rows="4" name="mverificacion"  id="mverificacion'.$oge['pog_id'].'">'.$mverificacion.'</textarea></label>
                        </section>
                      </div>
                    </fieldset>
                    <div id="log'.$oge['pog_id'].'"></div>
                    <div id="btn_eval'.$oge['pog_id'].'">
                      <footer>
                        <button type="button" id="subir_eval'.$oge['pog_id'].'" onclick="guardar_acp_regional('.$oge['pog_id'].');" class="btn btn-info">'.$tit.'</button>
                      </footer>
                    </div>
                  </form>
                </div>  
                <hr style="border-top: 3px double #8c8b8b">
               
              </div>
              </div>';
            }

            $tabla.='
            </div>
          </div>
        </div>
      </div>';

      return $tabla;
    }











  /*--- PARAMETRO DE CALIFICACION ACP REGIONAL FORM 1 ---*/
  public function calificacion_acp_regional($tp_indicador_og,$eval_acumulado,$ejec,$meta){
    /// $tp_indicador_og : 0 (parametro normal)
    /// $tp_indicador_og : 1  x<=meta (optimo) , x>meta (insatisfactorio)
    /// $tp_indicador_og : 2 x>=meta & x<=100 (optimo), x<meta (insatisfactorio)
    $calificacion='';$resp='';
    $valor=0;$color='';

    if($tp_indicador_og==0){
        if($meta!=0){
          $valor=round(((($eval_acumulado+$ejec)/$meta)*100),0);
        }

        if($valor>0 & $valor<=50){
          $resp='<b>INSATISFACTORIO</b>';
          $color='#f95b4f';
        }
        elseif($valor>50 & $valor<=75){
         $resp='<b>REGULAR</b>';
         $color='#edd094';
        }
        elseif($valor>75 & $valor<=99){
         $resp='<b>BUENO</b>';
         $color='#83bad1';
        }
        elseif($valor==100){
         $resp='<b>OPTIMO</b>';
         $color='#4caf50';
        }
    }
    elseif ($tp_indicador_og==1) {
      if(($eval_acumulado+$ejec)<=$meta){
        $valor=100;
        $resp='<b>OPTIMO</b>';
        $color='#4caf50';
      }
      else{
        $resp='<b>INSATISFACTORIO</b>';
        $color='#f95b4f';
      }
    }
    else{
      if(($eval_acumulado+$ejec)>=$meta & ($eval_acumulado+$ejec)<=100){
        $valor=100;
        $resp='<b>OPTIMO</b>';
        $color='#4caf50';
      }
      elseif(($eval_acumulado+$ejec)<$meta){
        $resp='<b>INSATISFACTORIO</b>';
        $color='#f95b4f';
      }
    }

    
    $calificacion.='<div style="color:white; background-color:'.$color.'"><center><font size=50>'.$valor.'%</font><br>'.$resp.'</center></div>';
    return $calificacion;
  }


    /*--- GET SUMA EVALUADO ANTES DEL TRIMESTRE ACTUAL FORM 1 ---*/
    public function get_suma_evaluado($pog_id,$trimestre){
      $sum=0;
      for ($i=1; $i <$trimestre ; $i++) { 
        $obj_gestion_evaluado=$this->model_evaluacion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
        if(count($obj_gestion_evaluado)!=0){
          $sum=$sum+$obj_gestion_evaluado[0]['ejec_fis'];
        }
      }

      return $sum;
    }












































































    //// LISTADO DE REGIONALES
    public function listado_regionales(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $tabla.='
          <input name="base" type="hidden" value="'.base_url().'">
          <input name="gestion" type="hidden" value="'.$this->gestion.'">
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>CONSOLIDADO EVALUACI&Oacute;N A.C.P. - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label"><b>DIRECCIÓN ADMINISTRATIVA</b></label>
                        <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                        <option value="">Seleccione Regional ....</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
            </div>
          </article>';
    return $tabla;
  }




 /*------- CABECERA REPORTE SEGUIMIENTO ACP (GRAFICO)------*/
  function cabecera_reporte_grafico(){
    $tabla='';

    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                        <td style="width:45%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
              <td style="width:10%; text-align:center;">
              </td>
              <td style="width:80%; height: 5%">
                <table align="center" border="0" style="width:100%;">
                  <tr style="font-size: 23px;font-family: Arial;">
                    <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                  </tr>
                  <tr style="font-size: 20px;font-family: Arial;">
                    <td style="height: 5%; text-align:center">EVALUACIÓN DE ACCIONES DE CORTO PLAZO</td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }


    ///==================== PRIMER CUADRO
    /*--- Tabla Evaluacion Meta a nivel Institucional ---*/
    public function tabla_evaluacion_meta_institucional(){
      $lista_ogestion=$this->model_objetivogestion->list_objetivosgestion_general();
      $nro=0;
      foreach($lista_ogestion as $row){
        $suma_mevaluado=$this->get_suma_total_evaluado_institucional($row['og_id']);
        $nro++;
        $tab[$nro][0]=$row['og_id'];
        $tab[$nro][1]='ACP. '.$row['og_codigo'];
        $tab[$nro][2]=$row['og_objetivo'];
        $tab[$nro][3]=$row['og_resultado'];
        $tab[$nro][4]=round($row['og_meta'],2);

        if($row['indi_id']==1){
          $tab[$nro][5]=round($suma_mevaluado,2);
          $tab[$nro][6]=round((($suma_mevaluado/$row['og_meta'])*100),2);
        }
        else{
          $sum_prog=$this->model_objetivogestion->get_suma_temporalidad_ogestion($row['og_id']);
          $tab[$nro][6]=round((($suma_mevaluado/$sum_prog[0]['meta_relativo'])*100),2);
          $tab[$nro][5]=round($tab[$nro][6],2);
        }
        
      }

      return $tab;
    }



    /*--- GET SUMA TOTAL EVALUADO INSTITUCIONAL ---*/
    public function get_suma_total_evaluado_institucional($og_id){
      $sum=0;
        for ($i=1; $i <=$this->tmes; $i++) { 
          $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral_institucional($i,$og_id);
          if(count($obj_gestion_evaluado)!=0){
            $sum=$sum+$obj_gestion_evaluado[0]['evaluado'];
          }
        }

      return $sum;
    }


/*--- MATRIZ EVALUACION DE METAS ACP REGIONAL 2022 ---*/
  public function matriz_evaluacion_meta_acp_regional($dep_id){
    $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
    $nro=0;
    foreach($acp_regional as $row){
      $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
      $nro++;
      $tab[$nro][0]=$row['pog_id']; /// pog_id
      $tab[$nro][1]='<b>ACP. '.$row['og_codigo'].'</b>'; /// codigo
      $tab[$nro][2]=$row['og_objetivo']; /// descripcion
      $tab[$nro][3]=$row['indi_id']; /// indi id
      $tab[$nro][4]=round($row['prog_fis'],2); /// meta acp regional
      $tab[$nro][5]=$suma_mevaluado; /// ejecutado

      $tab[$nro][6]=0; /// % cumplimiento
      if($tab[$nro][4]!=0){
        $tab[$nro][6]=round((($tab[$nro][5]/$tab[$nro][4])*100),2);
      }
    }

    return $tab;
  }


  //// Matriz Grado de Cumplimiento de acp por Regional
  public function matriz_gcumplimiento($acp,$nro){
    $cumplido=0;$proceso=0;$ncumplido=0;
    for ($i=1; $i <=$nro ; $i++) {
      if($acp[$i][4]==$acp[$i][5]){
        $cumplido++;
      }
      elseif(($acp[$i][5]<$acp[$i][4]) & $acp[$i][5]!=0){
        $proceso++;
      }
      elseif ($acp[$i][5]==0) {
        $ncumplido++;
      }
    }

    $matriz[1]=$nro;
    $matriz[2]=$cumplido;
    $matriz[3]=$proceso;
    $matriz[4]=$ncumplido;
    $matriz[5]=round((($cumplido/$nro)*100),2); // % cumplidos
    $matriz[6]=round((($proceso/$nro)*100),2); // % proceso
    $matriz[7]=round((($ncumplido/$nro)*100),2); // % no cumplido

    return $matriz;
  }


 /*--- GET SUMA TOTAL EVALUADO ---*/
    public function get_suma_total_evaluado($pog_id){
      $sum=0;
      for ($i=1; $i <=$this->tmes; $i++) { 
        $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
        if(count($obj_gestion_evaluado)!=0){
          $sum=$sum+$obj_gestion_evaluado[0]['ejec_fis'];
        }
      }

      return $sum;
    }

    /*--- DETALLE ACP REGIONAL ---*/
    public function detalle_acp($eval,$nro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// normal
        $font_size='style="font-size: 9px;"';
        $tab='class="table table-bordered" align=center style="width:90%;"';
      }
      else{ /// impresion
        $font_size='style="font-size: 8px;"';
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }

      $tabla.='
          <table '.$tab.'>
            <thead>
              <tr align=center bgcolor="#f1eeee">
                <th></th>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<th><b>'.$eval[$i][1].'</b></th>';
                }
                $tabla.='
                </tr>
            </thead>
            <tbody>
              <tr>
                <td align=left><b>META</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right>'.$eval[$i][4].'</td>';
                }
                $tabla.='
              </tr>
              <tr>
                <td align=left><b>EJEC.</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right>'.$eval[$i][5].'</td>';
                }
                $tabla.='
              </tr>
              <tr>
                <td align=left><b>% CUMP.</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right><b>'.$eval[$i][6].' %</b></td>';
                }
                $tabla.='
              </tr>
            </tbody>
        </table>';

      return $tabla;
    }


 /*--- Tabla cuadro de evaluacion ---*/
    public function tabla_gcumplimiento($matriz,$tp_cuadro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

      if($tp_cuadro==1){ /// Cuadro cumplido,no cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>Nro. A.C.P.</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.($matriz[3]+$matriz[4]).'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }
      else{ /// cuadro completo
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>Nro. A.C.P.</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>EN PROCESO</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.$matriz[3].'</b></td>
                <td><b>'.$matriz[4].'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }







  /*------ NOMBRE MES -------*/
  public function mes_nombre_completo(){
      $mes[1] = 'ENERO';
      $mes[2] = 'FEBRERO';
      $mes[3] = 'MARZO';
      $mes[4] = 'ABRIL';
      $mes[5] = 'MAYO';
      $mes[6] = 'JUNIO';
      $mes[7] = 'JULIO';
      $mes[8] = 'AGOSTO';
      $mes[9] = 'SEPTIEMBRE';
      $mes[10] = 'OCTUBRE';
      $mes[11] = 'NOVIEMBRE';
      $mes[12] = 'DICIEMBRE';

    return $mes;
  }

  /*------ NOMBRE MES -------*/
  public function mes_nombre(){
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


      /*================================= GENERAR MENU ====================================*/
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
    /*======================================================================================*/
}
?>