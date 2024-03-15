<?php
class Crep_modificaciones extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
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
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->adm = $this->session->userData('adm');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /////======== CONSOLIDADO REQUERIMIENTOS EXCEL 2020 =======
    /*-------------- Requerimientos ---------------*/
    public function consolidado_xls_requerimientos($proy_id){
      echo "Trabajando";
    }
/*    public function consolidado_xls_requerimientos($proy_id){
     $tabla='';
     $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
     $titulo=''.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].'-'.$proyecto[0]['proy_nombre'].'';
     if($proyecto[0]['tp_id']==4){
      $unidad=$this->model_proyecto->get_datos_proyecto_unidad($proy_id);
      $titulo=''.$unidad[0]['aper_programa'].''.$unidad[0]['aper_proyecto'].''.$unidad[0]['aper_actividad'].'-'.$unidad[0]['tipo'].' '.$unidad[0]['act_descripcion'].' '.$unidad[0]['abrev'].'';
     }


     $requerimientos_add=$this->model_modrequerimiento->lista_requerimientos_modificados_unidad($proy_id,1); 
     $requerimientos_mod=$this->model_modrequerimiento->lista_requerimientos_modificados_unidad($proy_id,2); 

      $tabla .='
      <style>
        table{font-size: 9px;
          width: 100%;
          max-width:1550px;
          overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>';

      if(count($requerimientos_add)!=0){
        $tabla.='<div style="font-size: 12px;font-family: Arial;"><b>REQUERIMIENTOS AGREGADOS ('.count($requerimientos_add).')</b></div>';
        $tabla.='<table  border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">';
        $tabla.='<thead>';
        $tabla.='<tr class="modo1" align="center">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">SERVICIO</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD. MODIFICACI&Oacute;N</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
          $tabla.='<th style="width:7.5%;background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_add as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr>';
            $tabla.='<td style="width: 1.3%; text-align: center;" style="height:18px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: left;font-size: 12px;"><b>'.mb_convert_encoding($row['com_componente'], 'cp1252', 'UTF-8').'</b></td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['cite_codigo'].'</b></td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.mb_convert_encoding($row['ins_detalle'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.strtoupper($row['ins_unidad_medida']).'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.round($row['ins_costo_unitario'],2).'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.round($row['ins_costo_total'],2).'</td>';
            if(count($prog)!=0){
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes1'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes2'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes3'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes4'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes5'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes6'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes7'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes8'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes9'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes10'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes11'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes12'],2).'</td>';
            }
            else{
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
            }
            $tabla.='<td style="width: 7.5%; text-align: left;">'.mb_convert_encoding($row['ins_observacion'], 'cp1252', 'UTF-8').'</td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:10px;" colspan=9></td>
            <td style="text-align: right;" style="height:18px;">'.round($monto,2).'</td>
            <td colspan=13></td>
          </tr>
        </table><br>';
      }

      $tabla.='<hr>';
      //$tabla.='<table border=1><tr><td colspan=23></td></tr></table>';

      if(count($requerimientos_mod)!=0){
        $tabla.='<div style="font-size: 12px;font-family: Arial;"><b>REQUERIMIENTOS MODIFICADOS ('.count($requerimientos_mod).')</b></div>';
        $tabla.='<table  border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">';
        $tabla.='<thead>';
        $tabla.='<tr style="text-align: center;">';
          $tabla.='<th style="width:1.3%;background-color: #1c7368; color: #FFFFFF">#</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">SERVICIO</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD. MODIFICACI&Oacute;N</th>';
          $tabla.='<th style="width:2.5%;background-color: #1c7368; color: #FFFFFF">COD.<br>ACT.</th>';
          $tabla.='<th style="width:3.5%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
          $tabla.='<th style="width:12%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
          $tabla.='<th style="width:4%;background-color: #1c7368; color: #FFFFFF">UNIDAD<br>MEDIDA</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
          $tabla.='<th style="width:6%;background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ENE.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">FEB.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">ABR.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">MAY.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUN.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">JUL.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">AGO.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">SEPT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">OCT.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">NOV.</th>';
          $tabla.='<th style="width:4.5%;background-color: #1c7368; color: #FFFFFF">DIC.</th>';
          $tabla.='<th style="width:7.5%;background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>';
        $tabla.='</tr>';
        $tabla.='</thead>';
        $tabla.='<tbody>';
        $nro=0;
        $monto=0;
        foreach ($requerimientos_mod as $row){
          $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
          $nro++;
          $tabla.='<tr>';
            $tabla.='<td style="width: 1.3%; text-align: center;" style="height:17px;">'.$nro.'</td>';
            $tabla.='<td style="width: 2.5%; text-align: left;font-size: 12px;"><b>'.mb_convert_encoding($row['com_componente'], 'cp1252', 'UTF-8').'</b></td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['cite_codigo'].'</b></td>';
            $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
            $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
            $tabla.='<td style="width: 12%; text-align: left;">'.mb_convert_encoding($row['ins_detalle'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='<td style="width: 4%; text-align: left;">'.strtoupper($row['ins_unidad_medida']).'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
            $tabla.='<td style="width: 4.5%; text-align: right;">'.round($row['ins_costo_unitario'],2).'</td>';
            $tabla.='<td style="width: 6%; text-align: right;">'.round($row['ins_costo_total'],2).'</td>';
            if(count($prog)!=0){
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes1'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes2'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes3'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes4'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes5'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes6'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes7'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes8'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes9'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes10'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes11'],2).'</td>';
              $tabla .= '<td style="width: 4.5%; text-align: right;">'.round($prog[0]['mes12'],2).'</td>';
            }
            else{
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
              $tabla.='<td style="width: 4.5%; text-align: right;"></td>';
            }
            $tabla.='<td style="width: 7.5%; text-align: left;">'.mb_convert_encoding($row['ins_observacion'], 'cp1252', 'UTF-8').'</td>';
          $tabla.='</tr>';
          $monto=$monto+$row['ins_costo_total'];
        }
        $tabla.='</tbody>
          <tr class="modo1">
            <td style="height:10px;" colspan=9></td>
            <td style="text-align: right;" style="height:18px;">'.round($monto,2).'</td>
            <td colspan=13></td>
          </tr>
        </table><br>';
      }

      date_default_timezone_set('America/Lima');
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=$titulo.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo $tabla;
    }
*/


    /*---------- Lista de Operaciones Aprobadas -----------*/
    public function menu_modificaciones(){
      $data['menu']=$this->menu(3); //// genera menu
      $data['regionales']=$this->model_proyecto->list_departamentos();
      $data['meses']=$this->model_modificacion->list_meses();
      
      $this->load->view('admin/modificacion/moperaciones/reporte/menu_modificacion', $data);
    }


    /*----- Reporte Consolidado Modificaciones -----*/
/*    public function reporte_consolidado(){
      $regionales=$this->model_proyecto->list_departamentos();
      $tabla='';
        $nro=0;
        for ($j=1; $j <=12 ; $j++) { 
          $o[$j]=0;$r[$j]=0;$t[$j]=0;
        }
      $tabla .='      
      <style>
      table{font-size: 9px;
            width: 100%;
            }
          th{
              padding: 1.4px;
              text-align: center;
              font-size: 9px;
            }
      </style>

      <table width="100%" align=center>
                  <tr>
                    <td width=10%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                    </td>
                    <td width=90%; align=center>
                        <FONT FACE="courier new" size="2">
                        <b>NÚMERO DE MODIFICACIONES AL POA '.$this->gestion.' & CERTIFICACIONES</b><br>(Del 02/01/2019 al '.date("d/m/Y").')
                        </FONT>
                    </td>
                  </tr>
                </table>
                <hr>';

      $tabla.='
        <table border=1>
          <thead>
            <tr>
              <th style="width:5%;"></th>';
                for ($i=1; $i <=ltrim(date("m"), "0") ; $i++) { 
                  $mes=$this->get_mes($i);
                  $tabla.='<th style="width:8%;" colspan=3>'.$mes[1].'</th>';
                }
            $tabla.='
              <th style="width:8%;" colspan=3>TOTAL</th>
            </tr>';
              $tabla.='<tr>';
                $tabla.='<td align=center><b>DETALLE</b></td>';
                for ($i=1; $i <=ltrim(date("m"), "0") ; $i++) {
                  $tabla.='<td align=center><b>ACT.</b></td>';
                  $tabla.='<td align=center><b>REQ.</b></td>';
                  $tabla.='<td align=center><b>TOTAL</b></td>';
                }
              $tabla.='
                <td align=center><b>ACT.</b></td>
                <td align=center><b>REQ.</b></td>
                <td align=center><b>TOTAL</b></td>
              </tr>';
            $tabla.='
          </thead>
          <tbody>';
            $tsum_ope=0;$tsum_req=0;$tsum_total=0;
            foreach($regionales as $row){
              $tabla.='<tr>';
                $tabla.='<td>'.$row['dep_departamento'].'</td>';
                $sum_ope=0;$sum_req=0;$sum_total=0;
                for ($i=1; $i <=ltrim(date("m"), "0") ; $i++) {
                  $num_ope=$this->nro_mod_actividades($row['dep_id'],$i);
                  $num_req=$this->nro_mod_requerimientos($row['dep_id'],$i);

                  $tabla.='<td align=right>'.$num_ope.'</td>'; /// Actividades
                  $tabla.='<td align=right>'.$num_req.'</td>'; /// Requerimientos
                  $tabla.='<td align=right bgcolor="#c8eff5">'.($num_ope+$num_req).'</td>'; /// Total
                  
                  $sum_ope=$sum_ope+$num_ope;
                  $sum_req=$sum_req+$num_req;
                }

                $tsum_ope=$tsum_ope+$sum_ope;
                $tsum_req=$tsum_req+$sum_req;
                $tabla.='
                <td align=right bgcolor="#c8eff5"><b>'.$tsum_ope.'</b></td>
                <td align=right bgcolor="#c8eff5"><b>'.$tsum_req.'</b></td>
                <td align=right bgcolor="#c8eff5"><b>'.($tsum_ope+$tsum_req).'</b></td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      
      $data['tabla']=$tabla;
      echo $data['tabla'];
      //$this->load->view('admin/modificacion/requerimientos/reporte_modificacion_requerimientos', $data);
    }*/

    /*---------- Nro de Requerimientos modificados por regional -----------*/
    public function nro_mod_requerimientos($dep_id,$mes_id){
      $mes=$this->model_modrequerimiento->list_cites_generados_requerimientos($dep_id,$mes_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],1); /// Adicion
        $cm=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],2); /// Modificacion
        $cd=$this->model_modrequerimiento->numero_de_modificaciones_requerimientos($row['cite_id'],3); /// Eliminacion
          if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
            $nro++;
          }
        }
      return $nro;
    }

    /*---------- Nro de Actividades modificados por regional -----------*/
    public function nro_mod_actividades($dep_id,$mes_id){
      $mes=$this->model_modfisica->list_cites_generados_productos($dep_id,$mes_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modfisica->numero_de_modificaciones_productos($row['cite_id'],1); /// Adicion
        $cm=$this->model_modfisica->numero_de_modificaciones_productos($row['cite_id'],2); /// Modificacion
          if(count($ca)!=0 || count($cm)!=0){
            $nro++;
          }
        }
      return $nro;
    }



    /*---------- get mes ----------*/
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



    

    /*---------- Nro de Operaciones modificados por regional -----------*/
/*    public function nro_operaciones($dep_id,$mes_id){
      $mes=$this->model_modificacion->modificaciones_operaciones_regionales($dep_id,$mes_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modificacion->list_add_producto($row['ope_id']);
        $cm=$this->model_modificacion->productos_modificados($row['ope_id']);
        $cd=$this->model_modificacion->productos_eliminados($row['ope_id']);
          if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
            $nro++;
          }
        }
      return $nro;
    }*/


    /*----------- Nro de Requerimientos Modificados por regional -------------*/
/*    public function nro_requerimientos($dep_id,$mes_id){
      $mes=$this->model_modificacion->modificaciones_requerimientos_regionales($dep_id,$mes_id);
      $nro=0;
      foreach($mes as $row){
        $ca=$this->model_modificacion->cite_add($row['insc_id']);
        $cm=$this->model_modificacion->cite_mod($row['insc_id']);
        $cd=$this->model_modificacion->ins_del($row['insc_id']);
        if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
          $nro++;
        }
      }
      return $nro;
    }
*/

    /*------------------- Valida Modificaciones Nacional -------------------*/
/*    function valida_busqueda_nacional(){
      if($this->input->post()){
          $this->form_validation->set_rules('fi', 'Fecha Inicio', 'required|trim'); /// Fecha Inicio
          $this->form_validation->set_rules('ff', 'Fecha Final', 'required|trim'); /// Fecha Final

        if ($this->form_validation->run() ) {
            $post = $this->input->post();
            $fi = $this->security->xss_clean(date("d/m/Y", strtotime($post['fi']))); /// Fecha Inicio
            $ff = $this->security->xss_clean(date("d/m/Y", strtotime($post['ff']))); /// Fecha Final

            $data['menu']=$this->menu(2); //// genera menu
            $data['dep']=$this->model_proyecto->get_departamento(10); //// Departamento
            
        //  echo $fi.'-'.$ff;
            $data['mod']=$this->list_modificaciones_onacional($fi,$ff);
            $this->load->view('admin/modificacion/operaciones/reporte/modificaciones', $data);
        }
        else{
          $this->session->set_flashdata('danger','PROBLEMAS AL GENERAR REPORTES');
          redirect('mod/modificaciones'); 
        }
      }
      else{
        $this->session->set_flashdata('danger','PROBLEMAS AL GENERAR REPORTES - CONTACTESE CON EL ADMINISTRADOR');
        redirect('mod/modificaciones');
      }
    }*/

    /*------------------- Valida Modificaciones Regional-------------------*/
/*    function valida_busqueda_regional(){
      if($this->input->post()){
          $this->form_validation->set_rules('dep_id', 'Proyecto', 'required|trim');
          $this->form_validation->set_rules('mes_id', 'Mes', 'required|trim');

        if ($this->form_validation->run() ) {
            $post = $this->input->post();
            $dep_id = $this->security->xss_clean($post['dep_id']);
            $mes_id = $this->security->xss_clean($post['mes_id']);

            $data['menu']=$this->menu(3); //// genera menu
            $data['dep']=$this->model_proyecto->get_departamento($dep_id); //// Departamento
            if($mes_id==0){
              $data['mod']=$this->list_modificaciones_regionales_todos($dep_id,$mes_id); 
            }
            else{
              $data['mod']=$this->list_modificaciones_regionales($dep_id,$mes_id);
            }
            
            $this->load->view('admin/modificacion/operaciones/reporte/modificaciones', $data);
        }
        else{
          $this->session->set_flashdata('danger','PROBLEMAS AL GENERAR REPORTES');
          redirect('mod/modificaciones'); 
        }
      }
      else{
        $this->session->set_flashdata('danger','PROBLEMAS AL GENERAR REPORTES - CONTACTESE CON EL ADMINISTRADOR');
        redirect('mod/modificaciones');
      }
    }*/


    /*-------------------- Tabla Operaciones Of Nacional ---------------------*/
/*    public function tabla_operaciones_onacional($dep_id,$fecha1,$fecha2){
      $mes=$this->model_modificacion->modificaciones_operaciones_onacional($dep_id,$fecha1,$fecha2); 

      $tabla ='';
      $tabla .='<table id="dt_basic1" class="table table table-bordered" width="100%">
                  <thead>
                    <tr >
                      <th scope="col">#</th>
                      <th scope="col">CITE OPERACI&Oacute;N</th>
                      <th scope="col">CITE FECHA</th>
                      <th scope="col">ACCI&Oacute;N OPERATIVO</th>
                      <th scope="col">FECHA</th>
                      <th scope="col">REPONSABLE</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>';
                    $nro1=0;
                    foreach($mes as $row){
                      $nro1++;
                     $tabla .='<tr class="modo1">';
                      $tabla .='<td>'.$nro1.'</td>';
                      $tabla .='<td>'.$row['ope_cite'].'</td>';
                      $tabla .='<td>'.date('d/m/Y',strtotime($row['ope_fecha'])).'</td>';
                      $tabla .='<td>'.$row['proy_nombre'].'</td>';
                      $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                      $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                      $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/cites_mod/2/'.$row['ope_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                     $tabla .='</tr>';
                    }
                  $tabla .='
                  </tbody>
                  <tr class="modo1">
                    <td colspan="6">TOTAL MODIFICADOS</td>
                    <td>'.count($mes).'</td>
                  </tr>
                </table>';

      return $tabla;
    }*/

    /*---------- Tabla Operaciones Regionales -----------*/
  /*  public function tabla_operaciones_regional($dep_id,$mes_id){
      $tabla ='';

      if($this->gestion>2019){
        $mes=$this->model_modfisica->list_cites_modfis_regionales($dep_id,$mes_id);
        $tabla .='
              <table id="dt_basic'.$mes_id.'" class="table table-bordered" width="100%">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">CITE NOTA</th>
                    <th scope="col">CITE FECHA</th>
                    <th scope="col">C&Oacute;DIGO MODIFICACI&Oacute;N</th>
                    <th scope="col">SERVICIO / COMPONENTE</th>
                    <th scope="col">UNIDAD / PROYECTO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">REPONSABLE DE EVALUACI&Oacute;N</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>';
                $nro1=0;
                foreach($mes as $row){
                  $codigo='<font color=blue><b>'.$row['cite_codigo'].'</b></font>';
                  $color='';
                  if($row['cite_estado']==0){
                  $color='#fbdfdf';
                  $codigo='<font color=red><b>SIN CÓDIGO</b></font>';
                  }

                $ca=$this->model_modfisica->operaciones_adicionados($row['cite_id']);
                $cm=$this->model_modfisica->operaciones_modificados($row['cite_id']);
                $cd=$this->model_modfisica->operaciones_eliminados($row['cite_id']);
                  if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                    $nro1++;
                    $tabla .='<tr class="modo1" bgcolor='.$color.'>';
                    $tabla .='<td>'.$nro1.'</td>';
                    $tabla .='<td>'.$row['cite_nota'].'</td>';
                    $tabla .='<td>'.date('d/m/Y',strtotime($row['cite_fecha'])).'</td>';
                    $tabla .='<td>'.$row['com_componente'].'</td>';
                    $tabla .='<td>'.$codigo.'</td>';
                    if($row['tp_id']==1){
                      $tabla .='<td>'.$row['proy_nombre'].'</td>';
                    }
                    else{
                      $tabla .='<td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
                    }
                    
                    $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha_creacion'])).'</td>';
                    $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                    $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/reporte_modfis/'.$row['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                   $tabla .='</tr>';
                  }
                }
                $tabla .='
                </tbody>
                <tr class="modo1">
                  <td colspan="6">TOTAL MODIFICADOS</td>
                  <td>'.$nro1.'</td>
                </tr>
              </table>';
      }
      else{
        $mes=$this->model_modificacion->modificaciones_operaciones_regionales($dep_id,$mes_id);
        $tabla .='
            <table id="dt_basic'.$mes_id.'" class="table table-bordered" width="100%">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">CITE OPERACI&Oacute;N</th>
                  <th scope="col">CITE FECHA</th>
                  <th scope="col">ACCI&Oacute;N OPERATIVO</th>
                  <th scope="col">FECHA</th>
                  <th scope="col">REPONSABLE</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>';
                $nro1=0;
                foreach($mes as $row){
                $ca=$this->model_modificacion->list_add_producto($row['ope_id']);
                $cm=$this->model_modificacion->productos_modificados($row['ope_id']);
                $cd=$this->model_modificacion->productos_eliminados($row['ope_id']);
                  if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                    $nro1++;
                   $tabla .='<tr class="modo1">';
                    $tabla .='<td>'.$nro1.'</td>';
                    $tabla .='<td>'.$row['ope_cite'].'</td>';
                    $tabla .='<td>'.date('d/m/Y',strtotime($row['ope_fecha'])).'</td>';
                    $tabla .='<td>'.$row['proy_nombre'].'</td>';
                    $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                    $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                    $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/cites_mod/2/'.$row['ope_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                   $tabla .='</tr>';
                  }
                }
              $tabla .='
              </tbody>
              <tr class="modo1">
                <td colspan="6">TOTAL MODIFICADOS</td>
                <td>'.$nro1.'</td>
              </tr>
            </table>';
      }
      
      return $tabla;
    }*/

    /*-------------------- Tabla Requerimientos O Nacional ---------------------*/
/*    public function tabla_requerimientos_onacional($dep_id,$fecha1,$fecha2){
      $mes=$this->model_modificacion->modificaciones_requerimientos_onacional($dep_id,$fecha1,$fecha2);
      $tabla ='';
      $tabla .='<table id="dt_basic" class="table table table-bordered" width="100%">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">CITE REQUERIMIENTO</th>
                      <th scope="col">CITE FECHA</th>
                      <th scope="col">ACCI&Oacute;N OPERATIVO</th>
                      <th scope="col">FECHA</th>
                      <th scope="col">REPONSABLE</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>';
                    $nro=0;
                    foreach($mes as $row){
                      $ca=$this->model_modificacion->cite_add($row['insc_id']);
                      $cm=$this->model_modificacion->cite_mod($row['insc_id']);
                      $cd=$this->model_modificacion->ins_del($row['insc_id']);
                      if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                        $nro++;
                        $tabla .='<tr>';
                          $tabla .='<td>'.$nro.'</td>';
                          $tabla .='<td>'.$row['insc_cite'].'</td>';
                          $tabla .='<td>'.date('d/m/Y',strtotime($row['insc_fecha'])).'</td>';
                          $tabla .='<td>'.$row['proy_nombre'].'</td>';
                          $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                          $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                          $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/cites_mod/1/'.$row['insc_id'].'\');" title="REPORTE CITES - MODIFICACION DE REQUERIMIENTOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                        $tabla .='</tr>';
                      }
                    }
                  $tabla .='
                  </tbody>
                  <tr>
                    <td colspan="6">TOTAL MODIFICADOS</td>
                    <td>'.$nro.'</td>
                  </tr>
                </table>';

      return $tabla;
    }*/

    /*-------------------- Tabla Requerimientos Regional ---------------------*/
/*    public function tabla_requerimientos_regional($dep_id,$mes_id){
      $tabla ='';
      if($this->gestion>2019){
        $mes=$this->model_modrequerimiento->list_cites_modfin_regionales($dep_id,$mes_id);
        $tabla .='
          <table id="dt_basicc'.$mes_id.'" class="table table-bordered" width="100%">
            <thead >
              <tr>
                <th style="height: 28px;" scope="col">#</th>
                <th scope="col">CITE NOTA</th>
                <th scope="col">CITE FECHA</th>
                <th scope="col">COacute;MODIFICACION</th>
                <th scope="col">SERVICIO / COMPONENTE</th>
                <th scope="col">UNIDAD / PROYECTO</th>
                <th scope="col">FECHA</th>
                <th scope="col">REPONSABLE</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($mes as $cit){
              $color='';
              $codigo='<font color=blue><b>'.$cit['cite_codigo'].'</b></font>';
              if($cit['cite_estado']==0){
                $color='#fbdfdf';
                $codigo='<font color=red><b>SIN CÓDIGO</b></font>';
              }

              $ca=$this->model_modrequerimiento->list_requerimientos_adicionados($cit['cite_id']);
              $cm=$this->model_modrequerimiento->list_requerimientos_modificados($cit['cite_id']);
              $cd=$this->model_modrequerimiento->list_requerimientos_eliminados($cit['cite_id']);
              if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                $nro++;
                $tabla .='<tr bgcolor='.$color.'>';
                  $tabla .='<td align="center">'.$nro.'</td>';
                  $tabla .='<td><b>'.$cit['cite_nota'].'</b></td>';
                  $tabla .='<td align="center">'.date('d/m/Y',strtotime($cit['cite_fecha'])).'</td>';
                  $tabla .='<td>'.$codigo.'</td>';
                  $tabla .='<td>'.$cit['com_componente'].'</td>';
                  if($cit['tp_id']==1){
                    $tabla .='<td>'.$cit['proy_nombre'].'</td>';
                  }
                  else{
                    $tabla .='<td>'.$cit['tipo'].' '.$cit['act_descripcion'].' '.$cit['abrev'].'</td>';
                  }
                  $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($cit['fecha_creacion'])).'</td>';
                  $tabla .='<td bgcolor="#bcf0f7">'.$cit['fun_nombre'].' '.$cit['fun_paterno'].' '.$cit['fun_materno'].'</td>';
                  $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/rep_mod_financiera/'.$cit['cite_id'].'\');" title="REPORTE CITES - MODIFICACION DE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                 $tabla .='</tr>';
                }
              }
            $tabla .='
            </tbody>
            <tr>
              <td colspan="6">TOTAL MODIFICADOS</td>
              <td>'.$nro.'</td>
            </tr>
          </table>';
      }
      else{
      $mes=$this->model_modificacion->modificaciones_requerimientos_regionales($dep_id,$mes_id);
      $tabla .='
          <table id="dt_basicc'.$mes_id.'" class="table table-bordered" width="100%">
            <thead >
              <tr>
                <th scope="col">#</th>
                <th scope="col">CITE REQUERIMIENTO</th>
                <th scope="col">CITE FECHA</th>
                <th scope="col">ACCI&Oacute;N OPERATIVO</th>
                <th scope="col">FECHA</th>
                <th scope="col">REPONSABLE</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($mes as $row){
                $ca=$this->model_modificacion->cite_add($row['insc_id']);
                $cm=$this->model_modificacion->cite_mod($row['insc_id']);
                $cd=$this->model_modificacion->ins_del($row['insc_id']);
                if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                  $nro++;
                  $tabla .='<tr>';
                    $tabla .='<td>'.$nro.'</td>';
                    $tabla .='<td>'.$row['insc_cite'].'</td>';
                    $tabla .='<td>'.date('d/m/Y',strtotime($row['insc_fecha'])).'</td>';
                    $tabla .='<td>'.$row['proy_nombre'].'</td>';
                    $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                    $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                    $tabla .='<td><a href="javascript:abreVentana(\''.site_url("").'/mod/cites_mod/1/'.$row['insc_id'].'\');" title="REPORTE CITES - MODIFICACION DE REQUERIMIENTOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>';
                  $tabla .='</tr>';
                }
              }
            $tabla .='
            </tbody>
            <tr>
              <td colspan="6">TOTAL MODIFICADOS</td>
              <td>'.$nro.'</td>
            </tr>
          </table>';
      }


      return $tabla;
    }*/

    /*------------------ Lista de Modificaciones regionales mes ---------------*/
/*    public function list_modificaciones_regionales($dep_id,$mes_id){
      $dep=$this->model_proyecto->get_departamento($dep_id); //// Departamento
      $mes=$this->model_modificacion->get_mes($mes_id); //// Mes
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                  <section id="widget-grid" class="well">
                      <div class="">
                        <h1> MIS MODIFICACIONES POA : '.strtoupper($dep[0]['dep_departamento']).'<small> - '.$this->gestion.'</small></h1>
                      </div>
                  </section>
                </article>
                <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                  <section id="widget-grid" class="well">
                    <style type="text/css">#graf{font-size: 80px;}</style> 
                    <center>
                      <div class="dropdown">
                      <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:100%;" data-toggle="dropdown" aria-expanded="true">
                        OPCIONES
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana(\''.site_url("").'/mod/rep_modificaciones/'.$dep_id.'/'.$mes_id.'/1\');" title="REPORTE CUADRO DE MODIFICACI&Oacute;N DE OPERACIONES">MOD. OPERACIONES</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana(\''.site_url("").'/mod/rep_modificaciones/'.$dep_id.'/'.$mes_id.'/2\');" title="REPORTE CUADRO DE MODIFICACI&Oacute;N DE REQUERIMIENTOS">MOD. REQUERIMIENTOS</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="'.site_url("").'/mod/modificaciones">ATRAS</a></li>
                      </ul>
                    </div>
                    </center>
                  </section>
                </article>';
      
      $tabla .='
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <h2 class="alert alert-success" align="center">MIS MODIFICACIONES MES DE '.strtoupper($mes[0]['m_descripcion']).' '.$this->gestion.'</h2>
                </article>
                
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                  <div class="jarviswidget jarviswidget-color-darken" >
                      <header>
                        <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                        <h2 class="font-md"><strong>ACTIVIDADES '.$this->gestion.'</strong></h2>  
                      </header>
                    <div>
                      <div class="widget-body no-padding">';
                        $tabla .=''.$this->tabla_operaciones_regional($dep_id,$mes_id);
                        $tabla.='  
                      </div>
                    </div>
                  </div>
                </article>
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                  <div class="jarviswidget jarviswidget-color-darken" >
                      <header>
                        <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                        <h2 class="font-md"><strong>REQUERIMIENTOS '.$this->gestion.'</strong></h2>  
                      </header>
                    <div>
                      <div class="widget-body no-padding">';
                        $tabla .=''.$this->tabla_requerimientos_regional($dep_id,$mes_id);
                        $tabla.='  
                      </div>
                    </div>
                  </div>
                </article>';

      return $tabla;
    }*/

    /*------------------ Lista de Modificaciones regionales todos ---------------*/
   /* public function list_modificaciones_regionales_todos($dep_id,$mes_id){
      $dep=$this->model_proyecto->get_departamento($dep_id); //// Departamento
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                  <section id="widget-grid" class="well">
                      <div class="">
                        <h1> MIS MODIFICACIONES : '.strtoupper($dep[0]['dep_departamento']).'<small> - '.$this->gestion.'</small></h1>
                      </div>
                  </section>
                </article>
                <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                  <section id="widget-grid" class="well">
                    <style type="text/css">#graf{font-size: 80px;}</style> 
                    <center>
                      <div class="dropdown">
                      <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:100%;" data-toggle="dropdown" aria-expanded="true">
                        OPCIONES
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana(\''.site_url("").'/mod/rep_modificaciones/'.$dep_id.'/'.$mes_id.'/1\');" title="REPORTE CUADRO DE MODIFICACI&Oacute;N DE OPERACIONES">MOD. OPERACIONES</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana(\''.site_url("").'/mod/rep_modificaciones/'.$dep_id.'/'.$mes_id.'/2\');" title="REPORTE CUADRO DE MODIFICACI&Oacute;N DE REQUERIMIENTOS">MOD. REQUERIMIENTOS</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="'.site_url("").'/mod/modificaciones">ATRAS</a></li>
                      </ul>
                    </div>
                    </center>
                  </section>
                </article>';
      
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="well well-sm well-light">
                <div id="tabs">
                  <ul>
                    <li>
                      <a href="#tabs-a">ENERO</a>
                    </li>
                    <li>
                      <a href="#tabs-b">FEBRERO</a>
                    </li>
                    <li>
                      <a href="#tabs-c">MARZO</a>
                    </li>
                    <li>
                      <a href="#tabs-d">ABRIL</a>
                    </li>
                    <li>
                      <a href="#tabs-e">MAYO</a>
                    </li>
                    <li>
                      <a href="#tabs-f">JUNIO</a>
                    </li>
                    <li>
                      <a href="#tabs-g">JULIO</a>
                    </li>
                    <li>
                      <a href="#tabs-h">AGOSTO</a>
                    </li>
                    <li>
                      <a href="#tabs-i">SEPTIEMBRE</a>
                    </li>
                    <li>
                      <a href="#tabs-j">OCTUBRE</a>
                    </li>
                    <li>
                      <a href="#tabs-k">NOVIEMBRE</a>
                    </li>
                    <li>
                      <a href="#tabs-l">DICIEMBRE</a>
                    </li>
                  </ul>
                  <div id="tabs-a">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE ENERO</h2>
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-color-darken" >
                            <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES ENERO</strong></h2>  
                            </header>
                          <div>
                            <div class="widget-body no-padding">';
                              $tabla .=''.$this->tabla_operaciones_regional($dep_id,1);
                            $tabla .='  
                            </div>
                          </div>
                        </div>
                      </article>
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-color-darken" >
                            <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES ENERO</strong></h2>  
                            </header>
                          <div>
                            <div class="widget-body no-padding">';
                            $tabla .=''.$this->tabla_requerimientos_regional($dep_id,1);
                            $tabla.='  
                            </div>
                          </div>
                        </div>
                      </article>
                    </div>
                  </div>

                  <div id="tabs-b">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE FEBRERO</h2>
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-color-darken" >
                            <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES FEBRERO</strong></h2> 
                            </header>
                          <div>
                            <div class="widget-body no-padding">';
                              $tabla .=''.$this->tabla_operaciones_regional($dep_id,2);
                            $tabla .='  
                            </div>
                          </div>
                        </div>
                      </article>
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-color-darken" >
                            <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES FEBRERO</strong></h2>  
                            </header>
                          <div>
                            <div class="widget-body no-padding">';
                              $tabla .=''.$this->tabla_requerimientos_regional($dep_id,2);
                              $tabla.='  
                            </div>
                          </div>
                        </div>
                      </article>
                    </div>
                  </div>

                  <div id="tabs-c">
                    <div class="row">
                        <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE MARZO</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES MARZO</strong></h2> 
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,3);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES MARZO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,3);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-d">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE ABRIL</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES ABRIL</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,4);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES ABRIL</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,4);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-e">
                    <div class="row">
                        <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE MAYO</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES MAYO</strong></h2> 
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,5);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES MAYO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,5);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-f">
                    <div class="row">
                        <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE JUNIO</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES JUNIO</strong></h2> 
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,6);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES JUNIO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,6);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-g">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE JULIO</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES JULIO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,7);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES JULIO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,7);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-h">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE AGOSTO</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES AGOSTO</strong></h2> 
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,8);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES AGOSTO</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,8);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-i">
                    <div class="row">
                        <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE SEPTIEMBRE</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES SEPTIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,9);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES SEPTIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,9);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-j">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE OCTUBRE</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES OCTUBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,10);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES OCTUBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,10);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-k">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE NOVIEMBRE</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES NOVIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,11);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES NOVIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,11);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-l">
                    <div class="row">
                      <h2 class="alert alert-success" align="center">MODIFICACIONES MES DE DICIEMBRE</h2>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N ACTIVIDADES - MES DICIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_regional($dep_id,12);
                              $tabla .='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>MODIFICACI&Oacute;N REQUERIMIENTOS - MES DICIEMBRE</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_regional($dep_id,12);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>
                  
                </div>
              </div>
            </article>';

      return $tabla;
    }*/


    /*------------------ Lista de Modificaciones Oficina Nacional ---------------*/
   /* public function list_modificaciones_onacional($fecha_inicio,$fecha_final){
      $fecha_inicio = date("d/m/Y", strtotime($fecha_inicio));
      $fecha_final = date("d/m/Y", strtotime($fecha_final));
    //  $cadena_fecha_actual = date_format($fecha_inicio, 'Y-m-d H:i:s');

      
      $dep=$this->model_proyecto->get_departamento(10); //// Departamento
      
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                  <section id="widget-grid" class="well">
                      <div class="">
                        <h1> MIS MODIFICACIONES : <small> - '.$dep[0]['dep_departamento'].'</small></h1>
                      </div>
                  </section>
                </article>
                <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                  <section id="widget-grid" class="well">
                    <style type="text/css">#graf{font-size: 80px;}</style> 
                    <center>
                      <div class="dropdown">
                      <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:100%;" data-toggle="dropdown" aria-expanded="true">
                        OPCIONES
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="'.site_url("").'/mod/modificaciones">ATRAS</a></li>
                      </ul>
                    </div>
                    </center>
                  </section>
                </article>';
      
      $tabla .='
              <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="well well-sm well-light">
                <div id="tabs">
                  <ul>
                    <li>
                      <a href="#tabs-c">MODIFICACIONES</a>
                    </li>
                    <li>
                      <a href="#tabs-d">REPORTE MODIFICACIONES</a>
                    </li>
                  </ul>
                  <div id="tabs-c">
                    <div class="row">
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <h2 class="alert alert-success" align="center">MODIFICACIONES DE '.$fecha_inicio.' A '.$fecha_final.' DEL '.$this->gestion.'</h2>
                        </article>
                        
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>OPERACIONES</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_operaciones_onacional(10,$fecha_inicio,$fecha_final);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>REQUERIMIENTOS</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $tabla .=''.$this->tabla_requerimientos_onacional(10,$fecha_inicio,$fecha_final);
                                $tabla.='  
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>

                  <div id="tabs-d">
                    <div class="row">
                      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <h2 class="alert alert-success" align="center">MODIFICACIONES DE '.$fecha_inicio.' A '.$fecha_final.' DEL '.$this->gestion.'</h2>
                        </article>
                        
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>OPERACIONES</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">';
                                $dat=date("d/m/Y", (int)$fecha_inicio);
                                echo $dat;
                              $tabla.='
                              
                              </div>
                            </div>
                          </div>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                          <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>REQUERIMIENTOS</strong></h2>  
                              </header>
                            <div>
                              <div class="widget-body no-padding">
                            
                              </div>
                            </div>
                          </div>
                        </article>
                    </div>
                  </div>
                </div>
              </div>  
              </article>';

      return $tabla;
    }*/

    /*--------------------------- REPORTE MODIFICACIONES O NACIONAL -------------------------*/
/*    public function reportes_modificaciones_onacional($fecha_inicial,$fecha_final,$tp){
      $dep=$this->model_proyecto->get_departamento(10); //// Departamento
      $html = ''.$this->modificaciones_ope_req_onal($fecha_inicial,$fecha_final,$tp);
      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      
      $dompdf->set_paper('letter', 'portrait');
      //$dompdf->set_paper('letter', 'portrait');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 9000000);
      $dompdf->render();
      $dompdf->stream("REPORTE ".$dep[0]['dep_departamento'].".pdf", array("Attachment" => false));
    }*/

    
    /*-------- REPORTE MODIFICACIONES REGIONAL----------*/
/*    public function reportes_modificaciones_regionales($dep_id,$mes_id,$tp){
      // dep id : mes id
      // mes id : mes id
      // tp :1 Actividades , 0 Requerimientos

      if($this->gestion>2019){ // Gestion 2020

        $data['mes'] = $this->mes_nombre();
        echo "trabajando ...!!!"; 
        //$this->load->view('admin/modificacion/moperaciones/reporte/reporte_modificaciones_poa', $data);
      }
      else{ /// Gestion 2018-2019
        $dep=$this->model_proyecto->get_departamento($dep_id); //// Departamento
        $html = ''.$this->modificaciones_ope_req($dep_id,$mes_id,$tp);
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        
        $dompdf->set_paper('letter', 'portrait');
        ini_set('memory_limit','700M');
        ini_set('max_execution_time', 9000000);
        $dompdf->render();
        $dompdf->stream("REPORTE ".$dep[0]['dep_departamento'].".pdf", array("Attachment" => false));
      }

    }*/

    /*---------------------------- Modificaciones Of. Nacional-------------------------*/
    /*  function modificaciones_ope_req_onal($fecha_inicial,$fecha_final,$tp){
        $dep=$this->model_proyecto->get_departamento(10); //// Departamento

        $gestion = $this->session->userdata('gestion');
        if($tp==1){
          $tit_mod='MODIFICACI&Oacute;N DE OPERACIONES';
        }
        else{
          $tit_mod='MODIFICACI&Oacute;N DE REQUERIMIENTOS';
        }

        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 143px 15px; }
             #header { position: fixed; left: 0px; top: -127px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -195px; right: 0px; height: 100px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>

          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                 <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"><</center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                              <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                              <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                              <b>REPORTE : </b> '.$tit_mod.'<br>
                              <b>REGIONAL : </b> '.strtoupper($dep[0]['dep_departamento']).'<br>
                            </FONT>
                        </td>
                        <td width=20%; align=center>
                        </td>
                    </tr>
                </table>
           </div>
           <div id="footer">
              <hr>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
              </table>
           </div>
           <div id="content">
            <p>';
              if($tp==1){
                $html.=''.$this->rep_operaciones_onacional($fecha_inicial,$fecha_final);
              }
              elseif ($tp==2) {
                $html.=''.$this->rep_requerimiento_onacional($fecha_inicial,$fecha_final);
              }
              else{
                $html.='NO VALIDO';
              }
            $html.='
              
            </p>
           </div>
         </body>
         </html>';
        return $html;
    }*/

    /*---------------------------- Modificaciones Operaciones Regionales -------------------------*/
     /* function modificaciones_ope_req($dep_id,$mes_id,$tp){
        $dep=$this->model_proyecto->get_departamento($dep_id); //// Departamento
        $mes=$this->model_modificacion->get_mes($mes_id);
        $gestion = $this->session->userdata('gestion');
        if($tp==1){
          $tit_mod='MODIFICACI&Oacute;N DE OPERACIONES';
        }
        else{
          $tit_mod='MODIFICACI&Oacute;N DE REQUERIMIENTOS';
        }
        if($mes_id==0){
          $tmes ='<b>TODOS LOS MESES : ENERO - DICIEMBRE';
        }
        else{
          $tmes ='<b>MES : </b> '.strtoupper($mes[0]['m_descripcion']).'';
        }
        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 143px 15px; }
             #header { position: fixed; left: 0px; top: -127px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -195px; right: 0px; height: 100px;}
             #footer .page:after { content: counter(page, upper-roman); }
           </style>

          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                 <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"><</center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                              <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                              <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                              <b>REPORTE : </b> '.$tit_mod.'<br>
                              <b>REGIONAL : </b> '.strtoupper($dep[0]['dep_departamento']).'<br>
                              '.$tmes.'
                            </FONT>
                        </td>
                        <td width=20%; align=center>
                        </td>
                    </tr>
                </table>
           </div>
           <div id="footer">
              <hr>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
              </table>
           </div>
           <div id="content">
            <p>';
              if($tp==1){
                $html.=''.$this->rep_operaciones($dep_id,$mes_id,2);
              }
              elseif ($tp==2) {
                $html.=''.$this->rep_requerimiento($dep_id,$mes_id,2);
              }
              else{
                $html.='NO VALIDO';
              }
            $html.='
              
            </p>
           </div>
         </body>
         </html>';
        return $html;
    }*/

    /*--------------- Modificacion de operaciones Of. Nacional -----------------*/
   /* function rep_operaciones_onacional($fecha1,$fecha2){
      $tabla='';
      $meses=$this->model_modificacion->modificaciones_operaciones_onacional(10,$fecha1,$fecha2);
        if(count($meses)!=0){
          
          $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">
            <thead>
              <tr>
                <th style="width:1%;">#</th>
                <th style="width:10%;">MES</th>
                <th style="width:10%;">CITE OPERACI&Oacute;N</th>
                <th style="width:10%;">CITE FECHA</th>
                <th style="width:20%;">ACCI&Oacute;N OPERATIVO</th>
                <th style="width:10%;">FECHA</th>
                <th style="width:20%;">REPONSABLE</th>
              </tr>
            </thead>
            <tbody>';
              $nro1=0;
              foreach($meses as $row){
                $mes=$this->model_modificacion->get_mes($row['mes']);
                $nro1++;
               $tabla .='<tr class="modo1">';
                $tabla .='<td>'.$nro1.'</td>';
                $tabla .='<td>'.$mes['m_descripcion'].'</td>';
                $tabla .='<td>'.$row['ope_cite'].'</td>';
                $tabla .='<td>'.date('d/m/Y',strtotime($row['ope_fecha'])).'</td>';
                $tabla .='<td>'.$row['proy_nombre'].'</td>';
                $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                $tabla .='</tr>';
              }
            $tabla .='
            </tbody>
            <tr class="modo1">
              <td colspan="5">TOTAL MODIFICADOS</td>
              <td>'.count($meses).'</td>
            </tr>
          </table><br>';
        }
      return $tabla;
    }*/

     /*--------------- Modificacion de operaciones Regional -----------------*/
  /*  function rep_operaciones($dep_id,$mes_id,$tp_ope){
      $tabla='';
      
      if($mes_id==0){
        
        for ($i=1; $i <=12 ; $i++) {
          $meses=$this->model_modificacion->modificaciones_operaciones_regionales($dep_id,$i);
          if(count($meses)!=0){
            $mes=$this->model_modificacion->get_mes($i);
            $tabla .='<font face="courier new" size="2"><b>MES : '.$mes[0]['m_descripcion'].'</b></font>';
            $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">
              <thead>
                <tr>
                  <th style="width:1%;">#</th>
                  <th style="width:10%;">CITE OPERACI&Oacute;N</th>
                  <th style="width:10%;">CITE FECHA</th>
                  <th style="width:20%;">ACCI&Oacute;N OPERATIVO</th>
                  <th style="width:10%;">FECHA</th>
                  <th style="width:20%;">REPONSABLE</th>
                </tr>
              </thead>
              <tbody>';
                $nro1=0;
                foreach($meses as $row){
                  $nro1++;
                 $tabla .='<tr class="modo1">';
                  $tabla .='<td>'.$nro1.'</td>';
                  $tabla .='<td>'.$row['ope_cite'].'</td>';
                  $tabla .='<td>'.date('d/m/Y',strtotime($row['ope_fecha'])).'</td>';
                  $tabla .='<td>'.$row['proy_nombre'].'</td>';
                  $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                  $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                  $tabla .='</tr>';
                }
              $tabla .='
              </tbody>
              <tr class="modo1">
                <td colspan="5">TOTAL MODIFICADOS</td>
                <td>'.count($meses).'</td>
              </tr>
            </table><br>';
          }            
        }
      }
      else{
        $meses=$this->model_modificacion->modificaciones_operaciones_regionales($dep_id,$mes_id);
        if(count($meses)!=0){
          $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">
            <thead>
              <tr>
                <th style="width:1%;">#</th>
                <th style="width:10%;">CITE OPERACI&Oacute;N</th>
                <th style="width:10%;">CITE FECHA</th>
                <th style="width:20%;">ACCI&Oacute;N OPERATIVO</th>
                <th style="width:10%;">FECHA</th>
                <th style="width:20%;">REPONSABLE</th>
              </tr>
            </thead>
            <tbody>';
              $nro1=0;
              foreach($meses as $row){
                $nro1++;
               $tabla .='<tr class="modo1">';
                $tabla .='<td>'.$nro1.'</td>';
                $tabla .='<td>'.$row['ope_cite'].'</td>';
                $tabla .='<td>'.date('d/m/Y',strtotime($row['ope_fecha'])).'</td>';
                $tabla .='<td>'.$row['proy_nombre'].'</td>';
                $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                $tabla .='</tr>';
              }
            $tabla .='
            </tbody>
            <tr class="modo1">
              <td colspan="5">TOTAL MODIFICADOS</td>
              <td>'.count($meses).'</td>
            </tr>
          </table><br>';
        }
        else{
          $tabla .='<font face="courier new" size="1">Sin Modificaciones registrados</font>';
        } 
      }

      return $tabla;
    }*/

    /*--------------- Modificaciones de Requerimientos Regional --------------*/
   /* function rep_requerimiento($dep_id,$mes_id,$tp_ope){
      $tabla='';
      
      if($mes_id==0){
        
        for ($i=1; $i <=12 ; $i++) {
          $meses=$this->model_modificacion->modificaciones_requerimientos_regionales($dep_id,$i); 
          if(count($meses)!=0){
            $mes=$this->model_modificacion->get_mes($i);
            $tabla .='<font face="courier new" size="2"><b>MES : '.$mes[0]['m_descripcion'].'</b></font>';
            $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">
                  <thead class="thead-dark">
                    <tr>
                      <th style="width:1%;">#</th>
                      <th style="width:10%;">CITE REQUERIMIENTO</th>
                      <th style="width:10%;">CITE FECHA</th>
                      <th style="width:20%;">ACCI&Oacute;N OPERATIVO</th>
                      <th style="width:10%;">FECHA</th>
                      <th style="width:20%;">REPONSABLE</th>
                    </tr>
                  </thead>
                  <tbody>';
                    $nro=0;
                    foreach($meses as $row){
                      $ca=$this->model_modificacion->cite_add($row['insc_id']);
                      $cm=$this->model_modificacion->cite_mod($row['insc_id']);
                      $cd=$this->model_modificacion->ins_del($row['insc_id']);
                      if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                        $nro++;
                        $tabla .='<tr class="modo1">';
                          $tabla .='<td>'.$nro.'</td>';
                          $tabla .='<td>'.$row['insc_cite'].'</td>';
                          $tabla .='<td>'.date('d/m/Y',strtotime($row['insc_fecha'])).'</td>';
                          $tabla .='<td>'.$row['proy_nombre'].'</td>';
                          $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                          $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                        $tabla .='</tr>';
                      }
                    }
                  $tabla .='
                  </tbody>
                  <tr class="modo1">
                    <td colspan="5">TOTAL MODIFICADOS</td>
                    <td>'.$nro.'</td>
                  </tr>
                </table><br>';
          }            
        }
      }
      else{
        $meses=$this->model_modificacion->modificaciones_requerimientos_regionales($dep_id,$mes_id); 
        if(count($meses)!=0){
          $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">
                <thead class="thead-dark">
                  <tr>
                    <th style="width:1%;">#</th>
                    <th style="width:10%;">CITE REQUERIMIENTO</th>
                    <th style="width:10%;">CITE FECHA</th>
                    <th style="width:20%;">ACCI&Oacute;N OPERATIVO</th>
                    <th style="width:10%;">FECHA</th>
                    <th style="width:20%;">REPONSABLE</th>
                  </tr>
                </thead>
                <tbody>';
                  $nro=0;
                  foreach($meses as $row){
                    $ca=$this->model_modificacion->cite_add($row['insc_id']);
                    $cm=$this->model_modificacion->cite_mod($row['insc_id']);
                    $cd=$this->model_modificacion->ins_del($row['insc_id']);
                    if(count($ca)!=0 || count($cm)!=0 || count($cd)!=0){
                      $nro++;
                      $tabla .='<tr class="modo1">';
                        $tabla .='<td>'.$nro.'</td>';
                        $tabla .='<td>'.$row['insc_cite'].'</td>';
                        $tabla .='<td>'.date('d/m/Y',strtotime($row['insc_fecha'])).'</td>';
                        $tabla .='<td>'.$row['proy_nombre'].'</td>';
                        $tabla .='<td bgcolor="#bcf0f7">'.date('d/m/Y',strtotime($row['fecha'])).'</td>';
                        $tabla .='<td bgcolor="#bcf0f7">'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                      $tabla .='</tr>';
                    }
                  }
                $tabla .='
                </tbody>
                <tr class="modo1">
                  <td colspan="5">TOTAL MODIFICADOS</td>
                  <td>'.$nro.'</td>
                </tr>
              </table><br>';
        }
        else{
          $tabla .='<font face="courier new" size="1">Sin Modificaciones registrados</font>';
        } 
      }

      return $tabla;
    }*/

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