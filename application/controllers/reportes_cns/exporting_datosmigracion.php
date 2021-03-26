<?php
  class Exporting_datosmigracion extends CI_Controller { 
  public function __construct (){ 
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tp_adm = $this->session->userData('tp_adm');
      }else{
          redirect('/','refresh');
      }
    }
    

    public function exportar_planilla_migracion_poa($dep_id,$tp_operacion){
     /// $tp_operacion :  1 -> Actividades
     ///                  2 -> Requerimientos
      $dep=$this->model_proyecto->get_departamento($dep_id);
      $departamento = strtoupper($dep[0]['dep_departamento']);

      $tabla='';
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

      if($tp_operacion==1){
      $operaciones=$this->mrep_operaciones->operaciones_por_regionales_migracion($dep_id); /// Actividades
      $titulo='FORMULARIO_4_ACTIVIDADES_'.$this->gestion.'';
      $tabla.='
      <table border="1" cellpadding="0" cellspacing="0" class="tabla">
        <thead>
          <tr class="modo1">
            <th style="width:1%; height:50px;" style="background-color: #1c7368; color: #FFFFFF">#</th>
            <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF">REGIONAL</th>
            <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF">TIPO DE ADMINISTRACI&Oacute;N</th>
            <th style="width:5%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="PROGRAMA">PROGRAMA '.$this->gestion.'</th>
            <th style="width:10%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="">UNIDAD / ESTABLECIMIENTO</th>

            <th style="width:10%; height:5px;" style="background-color: #1c7368; color: #FFFFFF" title="">COD SERV.</th>
            <th style="width:7%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="SERVICIO">SERVICIO / COMPONENTE</th>
            <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="">COD. OR.</th>
            <th style="width:2%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="">COD. ACT.</th>
            <th style="width:7%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="">DESCRIPCIÓN ACTIVIDAD</th>
            <th style="width:7%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="RESULTADO">RESULTADO</th>
            <th style="width:7%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="RESULTADO">UNIDAD RESPONSABLE</th>
            <th style="width:7%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="INDICADOR">INDICADOR</th>
            <th style="width:5%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="LINEA BASE">LINEA BASE '.($this->gestion-1).'</th>
            <th style="width:5%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="META">META '.$this->gestion.'</th>
            
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ENERO">ENE.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="FEBRERO">FEB.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MARZO">MAR.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="ABRIL">ABR.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MAYO">MAY.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JUNIO">JUN.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="JULIO">JUL.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="AGOSTO">AGOS.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="SEPTIEMBRE">SEPT.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="OCTUBRE">OCT.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="NOVIEMBRE">NOV.</th>
            <th style="width:4%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="DICIEMBRE">DIC.</th>
            <th style="width:8%; height:35px;" style="background-color: #1c7368; color: #FFFFFF" title="MEDIO DE VERIFICACI&Oacute;N">MEDIO DE VERIFICACI&Oacute;N</th>
          </tr>';
          $nro=0;
          foreach ($operaciones as $row){
            $programado=$this->model_producto->producto_programado($row['prod_id'],$this->gestion);
            $nro++;
            $tabla.='<tr class="modo1">';
              $tabla.='<td style="height:50px;" bgcolor="#cef7ce">'.$nro.'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.strtoupper($row['dep_departamento']).'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.strtoupper($row['dist_distrital']).'</td>';
              $tabla.='<td bgcolor="#cef7ce">\''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'\'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.$row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'].'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.strtoupper($row['serv_cod']).'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.strtoupper($row['com_componente']).'</td>';
              $tabla.='<td aling=center>'.$row['or_codigo'].'</td>';
              $tabla.='<td aling=center>'.$row['prod_cod'].'</td>';
              $tabla.='<td>'.strtoupper($row['prod_producto']).'</td>';
              $tabla.='<td>'.strtoupper($row['prod_resultado']).'</td>';
              $tabla.='<td>'.strtoupper($row['prod_unidades']).'</td>';
              $tabla.='<td>'.strtoupper($row['prod_indicador']).'</td>';
              $tabla.='<td>'.round($row['prod_linea_base'],2).'</td>';
              $tabla.='<td>'.round($row['prod_meta'],2).'</td>';

              if(count($programado)!=0){
                $tabla.='<td style="width:4%;">'.round($programado[0]['enero'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['febrero'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['marzo'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['abril'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['mayo'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['junio'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['julio'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['agosto'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['septiembre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['octubre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['noviembre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['diciembre'],2).'</td>';
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td bgcolor="#f5cace">0.00</td>';
                }
              }
              $tabla.='<td>'.strtoupper($row['prod_fuente_verificacion']).'</td>';
            $tabla.='</tr>';


/*            $tabla.='<tr class="modo1">';
              $tabla.='<td style="height:50px;" bgcolor="#cef7ce">'.$nro.'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.mb_convert_encoding(strtoupper($row['dist_distrital']), 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td bgcolor="#cef7ce">\''.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'\'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.mb_convert_encoding($row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td bgcolor="#cef7ce">'.mb_convert_encoding(''.$row['com_componente'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td aling=center>'.$row['or_codigo'].'</td>';
              $tabla.='<td aling=center>'.$row['prod_cod'].'</td>';
              $tabla.='<td>'.mb_convert_encoding($row['prod_producto'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td>'.mb_convert_encoding(''.$row['prod_resultado'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td>'.mb_convert_encoding(''.$row['prod_unidades'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td>'.mb_convert_encoding(''.$row['prod_indicador'], 'cp1252', 'UTF-8').'</td>';
              $tabla.='<td>'.round($row['prod_linea_base'],2).'</td>';
              $tabla.='<td>'.round($row['prod_meta'],2).'</td>';

              if(count($programado)!=0){
                $tabla.='<td style="width:4%;">'.round($programado[0]['enero'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['febrero'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['marzo'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['abril'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['mayo'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['junio'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['julio'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['agosto'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['septiembre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['octubre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['noviembre'],2).'</td>';
                $tabla.='<td style="width:4%;">'.round($programado[0]['diciembre'],2).'</td>';
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td bgcolor="#f5cace">0.00</td>';
                }
              }
              $tabla.='<td>'.mb_convert_encoding(''.$row['prod_fuente_verificacion'], 'cp1252', 'UTF-8').'</td>';
            $tabla.='</tr>';*/
          }

        $tabla.='
          </tbody>
        <table>';
      }
      elseif($tp_operacion==2){
        $unidades=$this->mrep_operaciones->unidades_proyectos($dep_id,1,4); /// Unidades por regional (2020)
        $titulo='FORMULARIO_5_REQUERIMIENTO';

           $tabla .='
           <table border="1" cellpadding="0" cellspacing="0" class="tabla">
            <thead>
                <tr class="modo1"> 
                  <th style="width:1%; height:50px;" style="background-color: #1c7368; color: #FFFFFF">#</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. D.A.</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. U.E.</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. PROG.</th>
                  <th style="width:10%; background-color: #1c7368; color: #FFFFFF">COD. PROY.</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. ACT.</th>
                  <th style="width:15%; background-color: #1c7368; color: #FFFFFF">DESCRIPCIÓN ACTIVIDAD / PROYECTO</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">COD. SUBACT.</th>
                  <th style="width:10%; background-color: #1c7368; color: #FFFFFF" title="">SUBACTIVIDAD </th>
                  <th style="width:2%; background-color: #1c7368; color: #FFFFFF" title="">COD. OPE.</th>
                  <th style="width:5%; background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                  <th style="width:17%; background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">UNIDAD DE MEDIDA</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">PRECIO UNITARIO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">PRESUPUESTO TOTAL</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">ENERO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">FEBRERO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">MARZO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">ABRIL</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">MAYO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">JUNIO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">JULIO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">AGOSTO</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">SEPTIEMBRE</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">OCTUBRE</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">NOVIEMBRE</th>
                  <th style="width:7%; background-color: #1c7368; color: #FFFFFF">DICIEMBRE</th>
                  <th style="width:15%; background-color: #1c7368; color: #FFFFFF">OBSERVACI&Oacute;N</th>
                </tr>
                </thead>
              <tbody>';
              $nro=0;
              foreach ($unidades as $row){
                $req=$this->mrep_operaciones->list_requerimientos_migracion($row['proy_id'],$row['tp_id']); /// Requerimientos 2020
                if(count($req)!=0){
                  $costo_total=0;
                  foreach ($req as $rowr){
                    $prog = $this->model_insumo->list_temporalidad_insumo($rowr['ins_id']); /// Temporalidad Programado
                    $color='';
                    $nro++;
                    $tabla.='<tr class="modo1" >
                      <td style="width:1%; height:50px;" bgcolor="#ddf7dd">'.$nro.'</td>
                      <td bgcolor="#ddf7dd">'.strtoupper($row['dep_cod']).'</td>
                      <td bgcolor="#ddf7dd">'.strtoupper($row['dist_cod']).'</td>
                      <td bgcolor="#ddf7dd">\''.$row['aper_programa'].'\'</td>
                      <td bgcolor="#ddf7dd">\''.$row['aper_proyecto'].'\'</td>
                      <td bgcolor="#ddf7dd">\''.$row['aper_actividad'].'\'</td>
                      <td bgcolor="#ddf7dd">'.$row['tipo'].' '.$row['proy_nombre'].' - '.$row['abrev'].'</td>
                      <td bgcolor="#ddf7dd">\''.$rowr['serv_cod'].'\'</td>
                      <td bgcolor="#ddf7dd">'.$rowr['serv_descripcion'].'</td>
                      <td>'.$rowr['prod_cod'].'</td>
                      <td align=center>'.$rowr['par_codigo'].'</td>
                      <td>'.strtoupper($rowr['ins_detalle']).'</td>
                      <td style="width:7%;">'.strtoupper($rowr['ins_unidad_medida']).'</td>
                      <td style="width:5%;">'.strtoupper($rowr['ins_cant_requerida']).'</td>
                      <td style="width:7%;">'.round($rowr['ins_costo_unitario'],2).'</td>
                      <td style="width:7%;">'.round($rowr['ins_costo_total'],2).'</td>';

                      if(count($prog)!=0){
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:7%;" align=right>'.round($prog[0]['mes'.$i],2).'</td>';
                        }
                      }
                      else{
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:7%;" align=right><font color=red><b>0</b></font></td>';
                        }
                      }
                $tabla.='
                      <td style="width:10%;">'.strtoupper($rowr['ins_observacion']).'</td>
                    </tr>';
                  }
                }
              }

              $proyectos=$this->mrep_operaciones->unidades_proyectos($dep_id,1,1); /// Unidades por regional (2020)
              foreach ($proyectos as $row){
                $req=$this->mrep_operaciones->list_requerimientos_migracion($row['proy_id'],$row['tp_id']); /// Requerimientos 2020
                if(count($req)!=0){
                  $costo_total=0;
                  foreach ($req as $rowr){
                    $prog = $this->model_insumo->list_temporalidad_insumo($rowr['ins_id']); /// Temporalidad Programado
                    $color='';
                    $nro++;
                    $tabla.='<tr class="modo1" >
                      <td style="width:1%; height:50px;" bgcolor="#ddf7dd">'.$nro.'</td>
                      <td bgcolor="#ddf7dd">'.strtoupper($row['dep_cod']).'</td>
                      <td bgcolor="#ddf7dd">'.strtoupper($row['dist_cod']).'</td>
                      <td bgcolor="#ddf7dd">\''.$row['aper_programa'].'\'</td>
                      <td bgcolor="#ddf7dd">\''.$row['proy_sisin'].'\'</td>
                      <td bgcolor="#ddf7dd">\''.$row['aper_actividad'].'\'</td>
                      <td bgcolor="#ddf7dd">'.$row['proy_nombre'].'</td>
                      <td bgcolor="#ddf7dd">\''.$rowr['serv_cod'].'\'</td>
                      <td bgcolor="#ddf7dd">'.$rowr['serv_descripcion'].'</td>
                      <td>'.$rowr['prod_cod'].'</td>
                      <td align=center>'.$rowr['par_codigo'].'</td>
                      <td>'.strtoupper($rowr['ins_detalle']).'</td>
                      <td style="width:7%;">'.strtoupper($rowr['ins_unidad_medida']).'</td>
                      <td style="width:5%;">'.strtoupper($rowr['ins_cant_requerida']).'</td>
                      <td style="width:7%;">'.round($rowr['ins_costo_unitario'],2).'</td>
                      <td style="width:7%;">'.round($rowr['ins_costo_total'],2).'</td>';

                      if(count($prog)!=0){
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:7%;" align=right>'.round($prog[0]['mes'.$i],2).'</td>';
                        }
                      }
                      else{
                        for ($i=1; $i<=12 ; $i++) {
                          $tabla.='<td style="width:7%;" align=right><font color=red><b>0</b></font></td>';
                        }
                      }
                $tabla.='
                      <td style="width:10%;">'.strtoupper($rowr['ins_observacion']).'</td>
                    </tr>';
                  }
                }
              }
              $tabla.='
            </tbody>
          </table>';
      }
     

/*        date_default_timezone_set('America/Lima');
        $fecha = date("d-m-Y H:i:s");
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=".$titulo."_".$departamento.""); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");*/
        echo $tabla;
    }

    /*------------ MENU -----------*/
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

}