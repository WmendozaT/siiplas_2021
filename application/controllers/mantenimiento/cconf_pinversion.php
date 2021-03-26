<?php
class Cconf_pinversion extends CI_Controller {
    public $rol = array('1' => '1');
    public function __construct(){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
          $this->load->model('Users_model','',true);
          if($this->rolfun($this->rol)){ 
              $this->load->library('pdf');
              $this->load->library('pdf2');
              $this->load->model('Users_model','',true);
              $this->load->model('menu_modelo');
              $this->load->model('mantenimiento/model_configuracion');
              $this->load->model('mantenimiento/model_estructura_org');
              $this->load->model('programacion/model_componente');
              $this->load->model('programacion/model_faseetapa');
              $this->load->model('programacion/model_proyecto');
              $this->load->model('programacion/model_producto');
              $this->load->model('reporte_eval/model_evalregional');
              $this->load->model('ejecucion/model_certificacion');
              $this->load->model('programacion/insumos/model_insumo');
              $this->load->library("security");
              $this->gestion = $this->session->userData('gestion');
              $this->rol = $this->session->userData('rol');
              $this->fun_id = $this->session->userData('fun_id');
              $this->tmes = $this->session->userData('trimestre');
          }
          else{
              redirect('admin/dashboard');
          }
      }
      else{
              redirect('/','refresh');
      }
    }

    /*------- LISTA DE PROYECTOS DE INVERSIÓN --------*/
    public function list_proyectos(){ 
      $data['menu']=$this->menu(9);
      $data['proyectos']=$this->proyectos_inversion();
      $data['regionales']=$this->list_departamento();
      






      /////-------------------------------- Para generar listado de poas 
      $poa_subactividad=$this->model_componente->lista_poa_subactividad(1);
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
      $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <thead>
                <tr>
                  <th>DA</th>
                  <th>UE</th>
                  <th>PROG</th>
                  <th>PROY</th>
                  <th>ACT</th>
                  <th>ACTIVIDAD</th>
                  <th>COD SUBAC</th>
                  <th>SUBACTIVIDAD</th>
                </tr>
                </thead>
                <tbody>';
                foreach($poa_subactividad as $row){
                  $tabla.='<tr>';
                    $tabla.='<td>\''.$row['da'].'\'</td>';
                    $tabla.='<td>\''.$row['ue'].'\'</td>';
                    $tabla.='<td>\''.$row['prog'].'\'</td>';
                    $tabla.='<td>\''.$row['proy'].'\'</td>';
                    $tabla.='<td>\''.$row['act'].'\'</td>';
                    $tabla.='<td>'.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</td>';
                    $tabla.='<td>\''.$row['serv_cod'].'\'</td>';
                    $tabla.='<td>'.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>';

                  $tabla.='</tr>';
                }
      $tabla.='
                </tbody>
                </table>';

/*                date_default_timezone_set('America/Lima');
          header('Content-type: application/vnd.ms-excel');
          header("Content-Disposition: attachment; filename=Consolidado_operaciones.xls"); //Indica el nombre del archivo resultante
          header("Pragma: no-cache");
          header("Expires: 0");
          echo "";*/
      //    echo $tabla;

      $this->load->view('admin/mantenimiento/reportes_consolidados/vlist_consolidado', $data);
    }

    /*----------- LISTA DE REGIONALES -------------*/
    public function list_departamento(){
      $regiones=$this->model_proyecto->list_departamentos();
      $nro=0;
      $tabla ='';
      $tabla .='<table class="table table-bordered" style="width: 70%;" align=center>
                  <tr bgcolor="#efefef">
                    <td style="width: 1%;" rowspan=2>NRO.</td>
                    <td style="width: 10%;" rowspan=2><center>REGIONAL</center></td>

                    <td style="width: 10%;" colspan=2><center>EVALUACI&Oacute;N TRIMESTRAL POR UNIDADES</center></td>
                    <td style="width: 10%;" colspan=2><center>EVALUACI&Oacute;N TRIMESTRAL POR PROYECTOS DE INVERSI&Oacute;N</center></td>
                    <td style="width: 10%;"><center></center></td>
                  </tr>
                  <tr bgcolor="#efefef">
                    <td style="width: 10%;"><center>VER EVALUACION A DETALLE POR UNIDADES</center></td>
                    <td style="width: 10%;"><center>EVALUACI&Oacute;N TRIMESTRAL POR UNIDADES</center></td>
                    <td style="width: 10%;"><center>VER EVALUACION A DETALLE POR PROYECTOS DE INVERSI&Oacute;N</center></td>
                    <td style="width: 10%;"><center>EVALUACI&Oacute;N TRIMESTRAL POR PROYECTOS DE INVERSI&Oacute;N</center></td>
                    <td style="width: 10%;"><center></center></td>
                  </tr>
              
                <tbody>';
                foreach($regiones as $row){
                  if($row['dep_estado']!=0){
                    $nro++;
                    $tabla .='<tr>';
                    $tabla .='<td>'.$nro.'</td>';
                    $tabla .='<td>'.strtoupper($row['dep_departamento']).'</td>';
                    $tabla .='<td align=center bgcolor="#c5efe9"><a href="'.site_url("").'/ver_consolidado/4/'.$row['dep_id'].'" class="btn btn-success" title="UNIDADES ORGANIZACIONALES">UNIDADES</a></td>';
                    $tabla .='<td align=center bgcolor="#c5efe9"><a href="'.site_url("").'/eval/exportar_evaluacion/4/'.$row['dep_id'].'" class="btn btn-success" title="EVALUACI&Oacute;N TRIMESTRAL">UNIDADES</a></td>';
                    $tabla .='<td align=center bgcolor="#c5efe9"><a href="'.site_url("").'/ver_consolidado/1/'.$row['dep_id'].'" class="btn btn-success" title="PROYECTOS DE INVERSIÓN">PROY. INV.</a></td>';
                    $tabla .='<td align=center bgcolor="#c5efe9"><a href="'.site_url("").'/eval/exportar_evaluacion/1/'.$row['dep_id'].'" class="btn btn-success" title="EVALUACI&Oacute;N TRIMESTRAL">PROY. INV.</a></td>';
                    $tabla .='<td></td>';
                    $tabla .='</tr>';
                  }
                }
      $tabla .='
                <tr>
                  <td colspan=2>TOTAL CONSOLIDADO</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>';
      $tabla .='</table><br>
      <a href="'.site_url("").'/rep/ver_programado_poa_regional" class="btn btn-primary" title="VER DETALLE DE PARTIDAS POR ESTABLECIMIENTO-UNIDAD">PLAN OPERATIVO ANUAL 1</a><br>
      <a href="'.site_url("").'/rep/ver_programado_poa_unidad" class="btn btn-primary" title="VER DETALLE DE PARTIDAS POR ESTABLECIMIENTO-UNIDAD">PLAN OPERATIVO ANUAL 2</a>';

      return $tabla;
    }


    /*Consolidado Temporalidad por regional (EVALUACION)*/
    public function consolidado_temporalidad($tp_id,$dep_id){
    $tabla='';
    $dep=$this->model_evalregional->get_dpto($dep_id);
    $uni_proy=$this->model_proyecto->list_uni_proy($dep_id,$tp_id);

    $vi=0; $vf=0;
    if($this->tmes==1){ $vi = 1;$vf = 3; }
    elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
    elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
    elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

    $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr style="font-size: 11px;" class="modo1">
                    <th>#</th>
                    <th>PROGRAMA</th>
                    <th>UNIDAD / ESTABLECIMIENTO</th>
                    <th>SERVICIO/COMPONENTE</th>
                    <th>OPERACI&Oacute;N</th>
                    <th>RESULTADO</th>
                    <th>INDICADOR</th>
                    <th>META</th>
                    <th>ENE (P)</th>
                    <th>FEB (P)</th>
                    <th>MAR (P)</th>
                    <th>ABR (P)</th>
                    <th>MAY (P)</th>
                    <th>JUN (P)</th>
                    <th>JUL (P)</th>
                    <th>AGO (P)</th>
                    <th>SEPT (P)</th>
                    <th>OCT (P)</th>
                    <th>NOV (P)</th>
                    <th>DIC (P)</th>
                    <th>ENE (E)</th>
                    <th>FEB (E)</th>
                    <th>MAR (E)</th>
                    <th>ABR (E)</th>
                    <th>MAY (E)</th>
                    <th>JUN (E)</th>
                    <th>JUL (E)</th>
                    <th>AGO (E)</th>
                    <th>SEPT (E)</th>
                    <th>OCT (E)</th>
                    <th>NOV (E)</th>
                    <th>DIC (E)</th>
                </tr>';
    $nro=0;
    foreach($uni_proy as $row){
        $productos=$this->model_producto->list_ope_proy($row['proy_id']);
        $nro++;
        
        $nro_p=0;
        foreach($productos as $rowp){
            $temp=$this->temporalizacion_productos($rowp['prod_id']);
            $nro_p++;
            $tabla.='<tr style="font-size: 11px;" class="modo1">';
                $tabla.='<td style="width: 1%;">'.$nro_p.'</td>';
                $tabla.='<td style="width: 5%;">'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</td>';
                $tabla.='<td style="width: 10%;">'.$row['proy_nombre'].'</td>';
                $tabla.='<td style="width: 10%;">'.$rowp['com_componente'].'</td>';
                $tabla.='<td style="width: 10%;">'.$rowp['prod_producto'].'</td>';
                $tabla.='<td style="width: 10%;">'.$rowp['prod_indicador'].'</td>';
                $tabla.='<td style="width: 10%;">'.$rowp['prod_resultado'].'</td>';
                $tabla.='<td style="width: 3%;" align=right>'.$rowp['prod_meta'].'</td>';
                for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE" style="width: 3%;" align=right>'.$temp[1][$i].'</td>';
                                    }
                                    else{
                                      $tabla .='<td bgcolor="#e2e2e2" style="width: 3%;" align=right>'.$temp[1][$i].'</td>';
                                    }
                                  }

                for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE" style="width: 3%;" align=right>'.$temp[4][$i].'</td>';
                                    }
                                    else{
                                      $tabla .='<td style="width: 3%;" align=right>'.$temp[4][$i].'</td>';
                                    }
                                  }

            $tabla.='</tr>';
        }
          
    }
    $tabla.='</table>';
    echo $tabla;
    }


    /*Consolidado Temporalidad por regional (anterior formato)*/
    public function consolidado_temporalidad2($tp_id,$dep_id){
    $tabla='';
    $dep=$this->model_evalregional->get_dpto($dep_id);
    $uni_proy=$this->model_proyecto->list_uni_proy($dep_id,$tp_id);

    $vi=0; $vf=0;
    if($this->tmes==1){ $vi = 1;$vf = 3; }
    elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
    elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
    elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

    $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla"style="width:100%;">
                <tr class="modo1">
                    <th style="width:95%;font-size: 30px;" align=center colspan=7>Regional - '.$dep[0]['dep_departamento'].'<hr></th>
                </tr>
            </table><hr>';
    $nro=0;
    foreach($uni_proy as $row){
        $productos=$this->model_producto->list_ope_proy($row['proy_id']);
        $nro++;
        $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr class="modo1">
                    <th style="width:95%;" align=left colspan=7>'.$nro.'.- '.$row['proy_nombre'].'</th>
                </tr>
                <tr style="font-size: 11px;" class="modo1">
                    <th>#</th>
                    <th>SERVICIO/COMPONENTE</th>
                    <th>OPERACI&Oacute;N</th>
                    <th>LINEA BASE</th>
                    <th>META</th>
                    <th>PONDERACI&Oacute;N</th>
                    <th>TEMPORALIDAD '.$this->gestion.'</th>
                </tr>';
        $nro_p=0;
        foreach($productos as $rowp){
            $temp=$this->temporalizacion_productos($rowp['prod_id']);
            $nro_p++;
            $tabla.='<tr style="font-size: 11px;" class="modo1">';
                $tabla.='<td style="width: 1%;">'.$nro_p.'</td>';
                $tabla.='<td style="width: 15%;">'.$rowp['com_componente'].'</td>';
                $tabla.='<td style="width: 20%;">'.$rowp['prod_producto'].'</td>';
                $tabla.='<td style="width: 5%;" align=right>'.$rowp['prod_linea_base'].'</td>';
                $tabla.='<td style="width: 5%;" align=right>'.$rowp['prod_meta'].'</td>';
                $tabla.='<td style="width: 5%;" align=right>'.$rowp['prod_ponderacion'].'</td>';
                $tabla.='<td>';
                    $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width: 100%;">
                                <thead>
                                <tr style="font-size: 11px; color: #ffffff;" bgcolor="#1c7368" class="modo1">
                                  <th>P/E</th>
                                  <th>ENE.</th>
                                  <th>FEB.</th>
                                  <th>MAR.</th>
                                  <th>ABR.</th>
                                  <th>MAY.</th>
                                  <th>JUN.</th>
                                  <th>JUL.</th>
                                  <th>AGOS.</th>
                                  <th>SEPT.</th>
                                  <th>OCT.</th>
                                  <th>NOV.</th>
                                  <th>DIC.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="font-size: 11px;" class="modo1">
                                  <td title="PROGRAMADO">P.</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[1][$i].'</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$temp[1][$i].'</td>';
                                    }
                                  }
                                  $tabla .='
                                </tr>
                                <tr style="font-size: 11px;" class="modo1">
                                  <td title="EJECUTADO">E.</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[4][$i].'</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$temp[4][$i].'</td>';
                                    }
                                  }
                                  $tabla .='
                                </tr>
                                <tr style="font-size: 11px;" bgcolor="#daf3da" class="modo1">
                                  <td title="EFICACIA">EFI.</td>';
                                  for ($i=1; $i <=12 ; $i++) {
                                    if($i>=$vi & $i<=$vf){
                                      $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[7][$i].'%</td>';
                                    }
                                    else{
                                      $tabla .='<td>'.$temp[7][$i].'%</td>';
                                    }
                                  }
                                  $tabla .='
                                </tr>
                            </table>';
                $tabla.='</td>';
            $tabla.='</tr>';
        }
        $tabla.='</table><br>';  
    }

    echo $tabla;
    }


    /*------ TEMPORALIZACION DE PRODUCTOS (nose esta tomando encuenta lb) ------*/
    public function temporalizacion_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

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
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
          $pa=$pa+$prod_prog[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $matriz[2][$i]=$pa;
          }
          else{
            $matriz[2][$i]=$matriz[1][$i];
          }

          
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[3][$i]=round(((($matriz[2][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
          }
          else{
            $ea=$matriz[4][$i];
          }

          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[6][$i]=round(((($matriz[5][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }
      
      return $matriz;
    }

    /*Lista de Proyectos de Inversion-activar fases*/
    public function proyectos_inversion(){ 
        $tabla='';
        $proyectos = $this->model_proyecto->list_proyectos_inversion();//lista de proyectos de inversion
        $nro=0;
        foreach($proyectos  as $row){
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $nro++;
            $tabla.=
                '<tr style="height:25px;">
                    <td title='.$row['proy_id'].'>'.$nro.'</td>
                    <td align=center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>
                    <td>'.$row['proy_nombre'].'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                    <td>'.strtoupper($row['dep_departamento']).'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>';
                    if(count($fase)!=0){
                        $fases=$this->model_faseetapa->fase_etapa_proy($row['proy_id']);
                        $tabla.='
                        <td>'.strtoupper($fase[0]['fase']).'</td>
                        <td>'.strtoupper($fase[0]['descripcion']).'</td>
                        <td>'.number_format($fase[0]['pfec_ptto_fase'], 2, ',', '.').' Bs.</td>
                        <td>
                            <select class="form-control" onchange="doSelectAlert(event,this.value,'.$row['proy_id'].');" style="width:100%;">';
                                foreach($fases as $pr){
                                    if(($pr['fas_id']==$fase[0]['fas_id']) & ($pr['pfec_estado']==1)){
                                        $tabla .="<option value=".$pr['id']." selected>".$pr['fase']." : (".$pr['pfec_fecha_inicio']." - ".$pr['pfec_fecha_fin'].") - ".$pr['descripcion']."</option>";
                                    }
                                    else{
                                        $tabla .="<option value=".$pr['id'].">".$pr['fase']." : (".$pr['pfec_fecha_inicio']." - ".$pr['pfec_fecha_fin'].") - ".$pr['descripcion']."</option>"; 
                                    }  
                                }
                                $tabla.='
                            </select> 
                        </td>';
                    }
                    else{
                        $tabla.='<td></td>
                                <td></td>
                                <td></td>
                                <td></td>';
                    }
            $tabla.='
                </tr>';
        }
        return $tabla;
    }

    /*======= ACTIVAR FASE DEL PROYECTO =======*/

    function activar_fase(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $this->form_validation->set_rules('proy id', 'Proyecto Id', 'required|trim');
            $this->form_validation->set_rules('fase id', 'Fase Id', 'required|trim');
            $post = $this->input->post();

            $proy_id=$this->security->xss_clean($post['proy_id']);
            $pfec_id=$this->security->xss_clean($post['pfec_id']);
            
           // $this->model_faseetapa->encender_fase_etapa($pfec_id,$proy_id);

            $update_fase = array(
            'pfec_estado' => 0
            );
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase);

            $update_fase2 = array(
            'pfec_estado' => 1
            );
            $this->db->where('pfec_id', $pfec_id);
            $this->db->where('proy_id', $proy_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase2);


            $fase_activa=$this->model_faseetapa->get_id_fase($proy_id);
            if($fase_activa[0]['id']==$pfec_id){
                echo "true";
            }
            else{
                echo "false";
            }
                
        }else{
            show_404();
        }
           
    }

    /*---------- Menu --------------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++)
        {
            if(count($subenlaces[$enlaces[$i]['o_child']])>0)
            {
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

    /*----------- Rol Usuario --------------*/
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
}