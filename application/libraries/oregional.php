<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Oregional extends CI_Controller{
        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('resultados/model_resultado');
            $this->load->model('mestrategico/model_mestrategico');
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
            $this->conf_form4 = $this->session->userData('conf_form4');
            $this->conf_form5 = $this->session->userData('conf_form5');
    }

    /*-------- TIPO DE RESPONSABLE -------*/
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


    /*---------- LISTA REGIONALES SEGUN OBJETIVO DE GESTION ------------*/
    public function regionales_seleccionados($og_id){
      $regionales=$this->model_objetivogestion->list_temporalidad_regional($og_id);
      $tabla='';

      $sw=0;
      $tabla.='<ul>';
      foreach($regionales as $row){
        $tabla.=' <li>
                    <a href="#tabs-'.$row['dep_id'].'" style="width:100%;">'.strtoupper($row['dep_departamento']).'</a>
                  </li>';
      }
      $tabla.='</ul>';

      foreach($regionales as $row){
        $meta=$this->model_objetivoregion->sum_meta_oregional($og_id,$row['dep_id']);
        $valor_meta=0;
        if(count($meta)!=0){
          $valor_meta=$meta[0]['meta'];
        }
        //$tabla.=''.$row['estado'].'';
        $estil='alert alert-success';
        if($row['estado']==0){
          $estil='alert alert-danger';
        }
        $tabla.='
        <div id="tabs-'.$row['dep_id'].'">
          <div class="row">
            <div class="'.$estil.'" role="alert">
              <h1>OPERACI&Oacute;N REGIONAL : <small><font color="#000">'.strtoupper($row['dep_departamento']).'</font></small></h1>
              <h1>META : <small><font color="#000">'.round($row['prog_fis'],2).'</font></small></h1>
            </div>';
            if($row['estado']!=0){
            //  $tabla.='<br>'.$valor_meta.'-----'.$row['prog_fis'].'';
              if($valor_meta<$row['prog_fis']){
                $tabla.='<a href="'.site_url("").'/me/new_oregional/'.$row['dep_id'].'/'.$og_id.'" title="REGISTRO OBJETIVO REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_form_add.png" WIDTH="25" HEIGHT="25"/>&nbsp; REGISTRO - OBJETIVO REGIONAL</a>';
              }
            }
            else{
              if(count($meta)==0){
                $tabla.='<a href="'.site_url("").'/me/new_oregional/'.$row['dep_id'].'/'.$og_id.'" title="REGISTRO OBJETIVO REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/application_form_add.png" WIDTH="25" HEIGHT="25"/>&nbsp; REGISTRO - OBJETIVO REGIONAL</a>';
              }
            }

              $tabla.='<hr>';
              $oregional=$this->model_objetivoregion->list_oregional_regional($og_id,$row['dep_id']);
              $nro=0;
              foreach($oregional as $row_or){
                $tabla.='
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:1%;" bgcolor="#d2d2d2">#</th>
                      <th style="width:3%;" bgcolor="#d2d2d2">MOD.</th>
                      <th style="width:3%;" bgcolor="#d2d2d2">DEL.</th>
                      <th style="width:5%;" bgcolor="#d2d2d2">COD. ACP..</th>
                      <th style="width:5%;" bgcolor="#d2d2d2">COD. OPE.</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">OPERACIÓN '.$this->gestion.' / OPERACI&Oacute;N</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">PRODUCTO</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">RESULTADO (LOGROS)</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">INDICADOR</th>
                      <th style="width:5%;" bgcolor="#d2d2d2">LINEA BASE</th>
                      <th style="width:5%;" bgcolor="#d2d2d2">META</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">MEDIO DE VERIFICACI&Oacute;N</th>
                      <th style="width:12%;" bgcolor="#d2d2d2">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</th>
                    </tr>
                  </thead>
                <tbody>';
                $nro++;
                $tabla.='<tr>';
                  $tabla.='
                  <td><b>'.$nro.'</b></td>
                  <td><a href="'.site_url("").'/me/update_oregional/'.$row_or['or_id'].'" title="MODIFICAR DATOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a></td>
                  <td><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OBJETIVO REGIONAL"  name="'.$row_or['or_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></td>
                  <td align=center><b><font color=blue size=6>'.$row_or['og_codigo'].'</font></b></td>
                  <td align=center><b><font color=blue size=6><b>'.$row_or['or_codigo'].'</b></font></b></td>
                  <td>'.$row_or['or_objetivo'].'</td>
                  <td>'.$row_or['or_producto'].'</td>
                  <td>'.$row_or['or_resultado'].'</td>
                  <td>'.$row_or['or_indicador'].'</td>
                  <td align=right><b>'.round($row_or['or_linea_base'],2).'</b></td>
                  <td align=right><b>'.round($row_or['or_meta'],2).'</b></td>
                  <td>'.$row_or['or_verificacion'].'</td>
                  <td>'.$row_or['or_observacion'].'</td>';
                $tabla.='</tr>
                </tbody>
                </table>';
                $num=0;
                $distritales=$this->model_proyecto->list_distritales($row['dep_id']);
                foreach($distritales as $rowd){
                  $niveles=$this->model_objetivoregion->list_niveles();
                  $tabla.=
                  '<table class="table table-bordered">
                      <thead>
                      <tr>
                        <th colspan=4 bgcolor="#f5f5f5" title="'.$rowd['dist_id'].'">DISTRIBUCI&Oacute;N .- '.strtoupper($rowd['dist_distrital']).'</th>
                      </tr>
                      <tr>
                        <th style="width:25%;" bgcolor="#f5f5f5">REGIONAL / DISTRITAL</th>
                        <th style="width:25%;" bgcolor="#f5f5f5">PRIMER NIVEL</th>
                        <th style="width:25%;" bgcolor="#f5f5f5">SEGUNDO NIVEL</th>
                        <th style="width:25%;" bgcolor="#f5f5f5">TERCER NIVEL</th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr>';
                        foreach($niveles as $rown){
                          $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($rowd['dist_id'],$rown['tn_id']);
                          $tabla.='<td>'.$this->lista_unidades_distrital_nivel($nivel,$row_or['or_id']).'</td>';
                        }
                        $tabla.='
                        </tr>
                      </tbody>
                    </table><hr>';
                }
              }
              $tabla.='
          </div>
        </div>';
      }

      return $tabla;
    }

    /*---------- LISTA DE UNIDADES POR DISTRITAL ------------*/
    public function lista_unidades_distrital_nivel($unidades,$or_id){
      $tabla='';

      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th style="width:1%;">#</th>
                    <th style="width:10%;">CAT.</th>
                    <th style="width:50%;">UNIDAD / ESTABLECIMIENTO</th>
                    <th style="width:10%;">PROG.</th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  foreach($unidades as $rowu){
                    $uni=$this->model_objetivoregion->get_unidad_programado($or_id,$rowu['act_id']);
                    $color='';$valor_prog=0;
                    if(count($uni)!=0){
                      if($uni[0]['or_estado']==1){
                        $color='#cbf7cb';
                        
                      }
                      $valor_prog=$uni[0]['prog_fis'];
                    }
                    $nro++;
                    $tabla.=
                    '<tr bgcolor='.$color.'>
                      <td>'.$nro.'</td>
                      <td>'.$rowu['aper_programa'].'</td>
                      <td>'.$rowu['tipo'].' '.$rowu['act_descripcion'].'</td>
                      <td>'.round($valor_prog,2).'</td>
                    </tr>';
                  }
                  $tabla.='
                  </tbody>
                </table>';

      return $tabla;
    }


  ///// ADICION DE OBJ. REGIONAL
  /*------------ FORMULARIO DE REGISTRO ----------*/
  public function formulario_add($dep_id,$og_id){
    $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id);
    $indi= $this->model_proyecto->indicador(); /// indicador
    $get_meta_prog=$this->model_objetivogestion->get_temporalidad_regional($og_id,$dep_id);
    $suma_meta=$this->model_objetivoregion->sum_meta_oregional($og_id,$dep_id);
    $meta_ocupado=0;
    if (count($suma_meta)!=0) {
      $meta_ocupado=$suma_meta[0]['meta'];
    }

    $tabla='';
    $tabla.='
        <article class="col-sm-12">
            <form action="'.site_url("").'/mestrategico/cobjetivo_regional/add_ogestion'.'" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
              <input type="hidden" name="pog_id" id="pog_id" value="'.$get_meta_prog[0]['pog_id'].'">
              <input type="hidden" name="dep_id" id="dep_id" value="'.$dep_id.'">
              <input type="hidden" name="nro" id="nro" value="'.count($this->model_objetivoregion->list_unidades_total($dep_id)).'">
              <input type="hidden" name="meta_reg" id="meta_reg" value="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado),2).'">
              <input type="hidden" name="tp" id="tp" value="1">
              <div class="col-sm-12">
                <h2 class="alert alert-success"><center>DETALLE - OPERACIÓN</center></h2>
                <div class="well">
                <header><b>DECRIPCI&Oacute;N OPERACIÓN -  COD: '.$ogestion[0]['og_codigo'].'</b></header>
                  <fieldset>     
                    <div class="row">
                      <section class="col col-3">
                      <label class="label">CODIGO DE OPERACIÓN</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="codigo" id="codigo" title="LINEA BASE" value="0" onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }" onpaste="return false" required="true">
                        </label>
                      </section>
                    </div> 
                    <div class="row">
                    <section class="col col-3">
                      <label class="label">OPERACIÓN</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="oregional" id="oregional" title="REGISTRE OBJETIVO REGIONAL"></textarea>
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">PRODUCTO</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="producto" id="producto" title="REGISTRE PRODUCTO"></textarea>
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">RESULTADO</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="resultado" id="resultado" title="REGISTRE RESULTADO"></textarea>
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">INDICADOR</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="indicador" id="indicador" title="REGISTRE INDICADOR"></textarea>
                      </label>
                    </section>
                    </div>

                    <div class="row">
                      <section class="col col-3">
                      <label class="label">LINEA BASE</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="lbase" id="lbase" title="LINEA BASE" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" >
                        </label>
                      </section>
                      <section class="col col-3">
                      <label class="label">META</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="meta" id="meta" title="META" value="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado),2).'" onkeyup="verif_meta()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" >
                        </label>
                      </section>
                      <section class="col col-3">
                      <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="mverificacion" id="mverificacion" title="REGISTRE MEDIO DE VERIFICACION" ></textarea>
                        </label>
                      </section>
                      <section class="col col-3">
                      <label class="label">OBSERVACIONES</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="observaciones" id="observaciones" title="REGISTRE OBSERVACIONES"></textarea>
                      </label>
                      </section>
                    </div>
                    <div id="atit"></div>
                  </fieldset>
                </div>
                <br>
                <h2 class="alert alert-success"><center>DISTRIBUCI&Oacute;N</center></h2>';
                $num=0;
                $distritales=$this->model_proyecto->list_distritales($dep_id);
                foreach($distritales as $row){
                  $tabla.='
                  <div class="well">
                  <header><b>'.$row['dist_id'].'.- '.strtoupper($row['dist_distrital']).'</b></header>
                    <fieldset>          
                      <div class="row">';
                      $niveles=$this->model_objetivoregion->list_niveles();
                      foreach($niveles as $rown){
                        $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($row['dist_id'],$rown['tn_id']);
                        if(count($nivel)!=0){
                          $tabla.='
                            <table class="table table-bordered" style="width:100%;">
                              <thead>
                                <tr>
                                  <th style="width:100%;">'.$rown['descripcion'].'</th>
                                <tr>
                              </thead>
                              <tbody>
                              <tr>
                              <td>';
                              foreach($nivel as $rowu){
                                $num++;
                                $tabla.='
                                <section class="col col-2">
                                  <label class="label">(<b>'.$rowu['aper_programa'].'</b>) '.$rowu['tipo'].' '.$rowu['act_descripcion'].'</label>
                                  <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input type="hidden" name="act_id[]" value="'.$rowu['act_id'].'">
                                    <input type="text" name="uni_id[]" id="uni'.$num.'" title="UNIDAD" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                                  </label>
                                </section>';
                              }
                              $tabla.='
                              </td>
                              </tr>
                              </tbody>
                            </table><br>';
                        }
                      }
                }
                $tabla.='
                      </div>
                    </fieldset>
                  </div><br>
              </div> 
              <input type="hidden" name="total" id="total" value="0">
              <br><hr>
              <div class="row">
                <section class="col col-2">
                <label class="label"><font color=blue><b>META REGIONAL</b></font></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="text" name="met" id="met" title="META REGIONAL" value="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado),2).'" disabled=true>
                  </label>
                </section>
                <section class="col col-2">
                <label class="label"><font color=blue><b>SUMA TOTAL PROGRAMADO</b></font></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="text" name="sum" id="sum" title="LINEA BASE + PROGRAMADO" value="0" disabled=true>
                  </label>
                </section>
              </div>';
             /* $styl='style="display:none;"';
              if($get_meta_prog[0]['prog_fis']==0){
                $styl='';
              }*/

    $tabla.='<div id="but" style="display:none;">
                <footer>
                  <button type="button" name="subir_fregional" id="subir_fregional" class="btn btn-info" >GUARDAR DATOS OPERACIÓN</button>
                  <a href="'.base_url().'index.php/me/objetivos_regionales/'.$og_id.'" title="SALIR" class="btn btn-default">CANCELAR</a>
                </footer>
                <div id="loadp" style="display: none" align="center">
                  <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO OPERACI&Oacute;N</b>
                </div>
              </div>
            </form>
        </article>';

    return $tabla;
  }




  //// UPDATE OBJ. REGIONAL
     /*------------ FORMULARIO DE REGISTRO ----------*/
    public function formulario_update($oregion){
      $indi= $this->model_proyecto->indicador(); /// indicador
      $get_meta_prog=$this->model_objetivogestion->get_temporalidad_regional($oregion[0]['og_id'],$oregion[0]['dep_id']);
      
      $suma_meta=$this->model_objetivoregion->sum_meta_oregional($oregion[0]['og_id'],$oregion[0]['dep_id']);
      $meta_ocupado=0;
      if (count($suma_meta)!=0) {
        $meta_ocupado=$suma_meta[0]['meta'];
      }

      $tabla='';
      $tabla.='
            <article class="col-sm-12">
                <form action="'.site_url("").'/mestrategico/cobjetivo_regional/add_ogestion'.'" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="nro" id="nro" value="'.count($this->model_objetivoregion->list_unidades_total($oregion[0]['dep_id'])).'">
                  <input type="hidden" name="meta_reg" id="meta_reg" value="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado+$oregion[0]['or_meta']),2).'">
                  <input type="hidden" name="tp" id="tp" value="2">
                  <input type="hidden" name="or_id" id="or_id" value="'.$oregion[0]['or_id'].'">
                  <div class="col-sm-12">
                    <h2 class="alert alert-success"><center>DETALLE - OPERACIÓN</center></h2>
                    <div class="well">
                    <header><b>DECRIPCI&Oacute;N OPERACIÓN</b></header>
                      <fieldset>
                        <div class="row">
                          <section class="col col-3">
                          <label class="label">CODIGO DE OPERACIÓN</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="codigo" id="codigo" title="LINEA BASE" value='.$oregion[0]['or_codigo'].' onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }" onpaste="return false" required="true">
                            </label>
                          </section>
                        </div>        
                        <div class="row">
                          <section class="col col-3">
                            <label class="label">OPERACIÓN</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="oregional" id="oregional" title="REGISTRE OBJETIVO REGIONAL" >'.$oregion[0]['or_objetivo'].'</textarea>
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label">PRODUCTO</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="producto" id="producto" title="REGISTRE PRODUCTO">'.$oregion[0]['or_producto'].'</textarea>
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label">RESULTADO</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="resultado" id="resultado" title="REGISTRE RESULTADO">'.$oregion[0]['or_resultado'].'</textarea>
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label">INDICADOR</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="indicador" id="indicador" title="REGISTRE INDICADOR" >'.$oregion[0]['or_indicador'].'</textarea>
                            </label>
                          </section>
                        </div>

                        <div class="row">
                          <section class="col col-3">
                          <label class="label">LINEA BASE</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="lbase" id="lbase" title="LINEA BASE" value='.round($oregion[0]['or_linea_base'],2).' onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">META</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="meta" id="meta" title="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado+$oregion[0]['or_meta']),2).'" value='.round($oregion[0]['or_meta'],2).' onkeyup="verif_meta()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="mverificacion" id="mverificacion" title="REGISTRE MEDIO DE VERIFICACION">'.$oregion[0]['or_verificacion'].'</textarea>
                            </label>
                          </section>
                          <section class="col col-3">
                          <label class="label">OBSERVACIONES</label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="observaciones" id="observaciones" title="REGISTRE OBSERVACIONES" >'.$oregion[0]['or_observacion'].'</textarea>
                          </label>
                          </section>
                        </div>
                        <div id="atit"></div>
                      </fieldset>
                    </div>
                    <br>
                    <h2 class="alert alert-success"><center>DISTRIBUCI&Oacute;N</center></h2>';
                    $num=0;
                    $distritales=$this->model_proyecto->list_distritales($oregion[0]['dep_id']);
                    foreach($distritales as $row){
                      $tabla.='
                      <div class="well">
                      <header><b>'.$row['dist_id'].'.- '.strtoupper($row['dist_distrital']).'</b></header>
                        <fieldset>          
                          <div class="row">';
                          $niveles=$this->model_objetivoregion->list_niveles();
                          foreach($niveles as $rown){
                            $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($row['dist_id'],$rown['tn_id']);
                            if(count($nivel)!=0){
                              $tabla.='
                                <table class="table table-bordered" style="width:100%;">
                                  <thead>
                                    <tr>
                                      <th style="width:100%;">'.$rown['descripcion'].'</th>
                                    <tr>
                                  </thead>
                                  <tbody>
                                  <tr>
                                  <td>';
                                  foreach($nivel as $rowu){
                                    $num++;
                                    $tabla.='
                                    <section class="col col-2">
                                      <label class="label">(<b>'.$rowu['aper_programa'].'</b>) '.$rowu['tipo'].' '.$rowu['act_descripcion'].'</label>
                                      <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="hidden" name="act_id[]" value="'.$rowu['act_id'].'">
                                        <input type="hidden" name="tp_id[]" value="4">';
                                        $uni=$this->model_objetivoregion->get_unidad_programado($oregion[0]['or_id'],$rowu['act_id']);
                                        if(count($uni)!=0){
                                          $tabla.='<input type="text" name="uni_id[]" id="uni'.$num.'" title="UNIDAD" value="'.round($uni[0]['prog_fis'],2).'" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">';
                                        }
                                        else{
                                          $tabla.='<input type="text" name="uni_id[]" id="uni'.$num.'" title="UNIDAD" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">';
                                        }
                                        $tabla.='
                                      </label>
                                    </section>';
                                  }
                                  $tabla.='
                                  </td>
                                  </tr>
                                  </tbody>
                                </table><br>';
                            }
                          }
                    }
                    $tabla.='</div>
                        </fieldset>
                      </div><br>';

                      $proyectos=$this->model_objetivoregion->list_pinversion($oregion[0]['dep_id']);
                      foreach($proyectos as $row){
                        $num++;
                        $tabla.='
                          <input type="hidden" name="act_id[]" value="'.$row['proy_id'].'">
                          <input type="hidden" name="tp_id[]" value="1">
                          <input type="hidden" name="uni_id[]" id="uni'.$num.'" title="PROYECTO DE INVERSION" value="0">';
                      }

                    $sum_prog=$this->model_objetivoregion->sum_oregional($oregion[0]['or_id']);
                    $suma=0;
                    if(count($sum_prog)!=0){
                      $suma=$sum_prog[0]['suma_meta'];
                    }
                    $tabla.='
                  </div>
                  <hr>

                  <div class="row">
                    <section class="col col-2">
                    <label class="label"><font color=blue><b>META REGIONAL</b></font></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="met" id="met" title="META REGIONAL" value="'.round(($get_meta_prog[0]['prog_fis']-$meta_ocupado+$oregion[0]['or_meta']),2).'" disabled=true>
                      </label>
                    </section>
                    <section class="col col-2">
                    <label class="label"><font color=blue><b>SUMA TOTAL PROGRAMADO</b></font></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="sum" id="sum" title="LINEA BASE + PROGRAMADO" value="'.$suma.'" disabled=true>
                      </label>
                    </section>
                  </div>

                  <div id="but">
                    <footer>
                      <button type="button" name="subir_fregional" id="subir_fregional" class="btn btn-info" >GUARDAR DATOS OPERACIÓN</button>
                      <a href="'.base_url().'index.php/me/objetivos_regionales/'.$oregion[0]['og_id'].'" title="SALIR" class="btn btn-default">CANCELAR</a>
                    </footer>
                    <div id="loadp" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO OPERACI&Oacute;N</b>
                    </div>
                  </div>
                </form>
            </article>';

      return $tabla;
    }




  ///// ============== OPERACIONES
    /*-------- Cbecera Operacuiones ------*/
    public function cabecera_rep_operaciones($ogestion){
      $tabla='';

      $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
              <td style="width:70%;height: 2%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 15px;font-family: Arial;">
                          <td style="width:35%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
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
                            <td style="height: 35%;"><b>OPERACIONES </b></td>
                        </tr>
                        <tr style="font-size: 20px;font-family: Arial;">
                          <td style="height: 2%;">PLAN OPERATIVO ANUAL - '.$this->gestion.'</td>
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
                <td style="width:30%; height: 2%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 15px;font-family: Arial;">
                          <td align=center style="width:100%;height: 20%;"><b>FORMULARIO SPO N° 2 </b></td>
                      </tr>
                  </table>
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;">
                <hr>
              </td>
              <td style="width:2%;"></td>
          </tr>
          <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 3%;">
               
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                <tr><td style="width:95%;height: 50%;" bgcolor="#dcdbdb"><b>OBJETIVO ESTRATEGICO</b></td><td style="width:5%;"></td></tr>
                            </table>
                        </td>
                        <td style="width:80%;">
                            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                <tr><td style="width:100%;height: 50%;" bgcolor="#f9f9f9">&nbsp;<b>'.$ogestion[0]['obj_codigo'].'</b>.- '.$ogestion[0]['obj_descripcion'].'</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                <tr><td style="width:95%;height: 50%;" bgcolor="#dcdbdb"><b>ACCI&Oacute;N ESTRATEGICO</b></td><td style="width:5%;"></td></tr>
                            </table>
                        </td>
                        <td style="width:80%;">
                            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                <tr><td style="width:100%;height: 50%;" bgcolor="#f9f9f9">&nbsp;<b>'.$ogestion[0]['acc_codigo'].'</b>.- '.$ogestion[0]['acc_descripcion'].'</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                <tr><td style="width:95%;height: 50%;" bgcolor="#dcdbdb"><b>ACCI&Oacute;N DE CORTO PLAZO</b></td><td style="width:5%;"></td></tr>
                            </table>
                        </td>
                        <td style="width:80%;">
                            <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                <tr><td style="width:100%;height: 50%;" bgcolor="#f9f9f9">&nbsp;<b>'.$ogestion[0]['og_codigo'].'</b>.- '.$ogestion[0]['og_objetivo'].'</td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
              </td>
              <td style="width:2%;"></td>
          </tr>
          <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;">
                <hr>
              </td>
              <td style="width:2%;"></td>
          </tr>
        </table>';
      return $tabla;
    }

    /*---------- Pie de Reporte -----------*/
    public function pie_rep_operaciones($ogestion){
      $tabla='';
      $tabla.='
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33.3%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial; height:65px;">
                            <td style="width:100%;" colspan="2"><b>ELABORADO POR (Jefe Medico)<br></b></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>RESPONSABLE</b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>CARGO</b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33.3%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial; height:65px;">
                            <td style="width:100%;" colspan="2"><b>REVISADO POR (Jefe de Serv.)<br></b></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>NOMBRE :</b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>CARGO : </b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33.3%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial; height:65px;">
                            <td style="width:100%;" colspan="2"><b>APROBADO POR (Administrador)<br></b></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>NOMBRE :</b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><b>CARGO : </b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
            <tr style="font-size: 7px;font-family: Arial;">
                <td style="text-align: left;height: 2.5%;" colspan="2">
                    '.$this->session->userdata('sistema').'
                </td>
                <td style="width: 20%; text-align: right">
                    pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>';

      return $tabla;
    }

  /*-------- MENU -----*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
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
}
?>