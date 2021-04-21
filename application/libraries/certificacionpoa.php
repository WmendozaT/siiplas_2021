<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Certificacionpoa extends CI_Controller{
        public function __construct (){
            parent::__construct();
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->model('programacion/model_componente');
            $this->load->model('ejecucion/model_notificacion');
            $this->load->model('programacion/model_producto');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('mantenimiento/model_configuracion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
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
    }





/*------- LISTA DE REQUERIMIENTOS PRE LISTA ------*/
  public function list_requerimientos_prelista($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
          }

          if($monto_certificado!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                }
                else{
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                }
                $tabla.='
                <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>';
                if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                  for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                }
                else{
                  $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  for ($j=1; $j <=12 ; $j++) {
                    $bgcolor='';
                    if($temp[0]['mes'.$j.'']!=0){
                      $bgcolor='#d5f5f0';
                    }
                    $tabla.='
                    <td align="right" bgcolor='.$bgcolor.'>
                      '.number_format($temp[0]['mes'.$j.''], 2, ',', '.').'
                    </td>';
                  }
                }

                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }


/*------- LISTA DE REQUERIMIENTOS NORMAL ------*/
  public function list_requerimientos($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
            

            if($monto_certificado==$row['ins_costo_total']){
              $verif=1;
              $color_tr="#f7d6dc";
            }
            elseif($monto_certificado<$row['ins_costo_total']){
              $color_tr="#f6f7cb";
            }
          }

          if($monto_certificado!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr bgcolor="'.$color_tr.'" title='.$row['ins_id'].'>
                <td>'.$nro.'</td>
                <td>';
                  if($verif==0){
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>
                            <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">';
                  }
                $tabla.='
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>
                <td align=right bgcolor="#e7f5f3">'.number_format($monto_certificado, 2, ',', '.').'</td>';

                for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right >';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>
                        </tr>
                      </table>
                    </td>';
                  }
                
                $tabla.='
              </tr>';
          }

        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }






  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_segpoa($com_id){
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
              <a href="#" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
              </li>
              <li class="text-center">
                  <a href="#" title="REGISTRO DE SEGUIMIENTO, EVALUACIÓN Y CERTIFICACIÓN POA"> <span class="menu-item-parent">SEG. EVAL. POA</span></a>
              </li>
              <li>
                <a href="'.site_url("").'/seguimiento_poa"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Seg. y eval. POA</span></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Certificación POA</span></a>
                <ul>
                  <li>
                    <a href="'.site_url("").'/solicitar_certpoa/'.$com_id.'">Solicitar Certificación POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                  <li>
                    <a href="image-editor.html">Mis Certificaciones POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                </ul>
              </li>
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }
}
?>