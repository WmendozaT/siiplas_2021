<?php
class Crep_actprog extends CI_Controller {  
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null){
    $this->load->model('menu_modelo');
    $this->load->model('Users_model','',true);
    $this->load->model('mresumen_actividad/model_resumenactividad');
    $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
    $this->load->model('mantenimiento/model_estructura_org');
    $this->load->model('programacion/model_proyecto');

    $this->gestion = $this->session->userData('gestion');
    $this->adm = $this->session->userData('adm'); // 1: Adm. Nacional, 2: Regional
    $this->dist = $this->session->userData('dist');
    $this->rol = $this->session->userData('rol_id');
    $this->dist_tp = $this->session->userData('dist_tp');
    $this->fun_id = $this->session->userdata("fun_id");
    $this->tp_adm = $this->session->userdata("tp_adm"); // 1: Privilegios, 0: sin Privilegios
    }else{
        redirect('/','refresh');
    }
  }


    /*--- Lista Regional ---*/
    public function regional(){
      $data['menu']=$this->menu();
      $data['institucional_prog']=$this->alineacion_operacion_programa_institucional(0); /// Institucional Programa
      $data['institucional_og']=$this->alineacion_operacion_ogestion_institucional(0); /// Institucional Ogestion

      $data['ofn_prog']=$this->alineacion_operacion_programa_regional(10,0); /// Oficina Nacional Programa
      $data['ch_prog']=$this->alineacion_operacion_programa_regional(1,0); /// chuquisaca Programa
      $data['lpz_prog']=$this->alineacion_operacion_programa_regional(2,0); /// la paz Programa
      $data['cbb_prog']=$this->alineacion_operacion_programa_regional(3,0); /// cochabamba Programa
      $data['or_prog']=$this->alineacion_operacion_programa_regional(4,0); /// oruro Programa
      $data['pot_prog']=$this->alineacion_operacion_programa_regional(5,0); /// potosi Programa
      $data['tja_prog']=$this->alineacion_operacion_programa_regional(6,0); /// tarija Programa
      $data['scz_prog']=$this->alineacion_operacion_programa_regional(7,0); /// santa Programa
      $data['be_prog']=$this->alineacion_operacion_programa_regional(8,0); /// beni Programa
      $data['pa_prog']=$this->alineacion_operacion_programa_regional(9,0); /// pando Programa

      $data['ofn_og']=$this->alineacion_operacion_ogestion_regional(10,0); /// Oficina Nacional Programa
      $data['ch_og']=$this->alineacion_operacion_ogestion_regional(1,0); /// chuquisaca Programa
      $data['lpz_og']=$this->alineacion_operacion_ogestion_regional(2,0); /// la paz Programa
      $data['cbb_og']=$this->alineacion_operacion_ogestion_regional(3,0); /// cochabamba Programa
      $data['or_og']=$this->alineacion_operacion_ogestion_regional(4,0); /// oruro Programa
      $data['pot_og']=$this->alineacion_operacion_ogestion_regional(5,0); /// potosi Programa
      $data['tja_og']=$this->alineacion_operacion_ogestion_regional(6,0); /// tarija Programa
      $data['scz_og']=$this->alineacion_operacion_ogestion_regional(7,0); /// santa Programa
      $data['be_og']=$this->alineacion_operacion_ogestion_regional(8,0); /// beni Programa
      $data['pa_og']=$this->alineacion_operacion_ogestion_regional(9,0); /// pando Programa


      $this->load->view('admin/reportes_cns/resumen_actividad_programa/regional', $data);
    }

    ///// OPE-PROGRAMA : INSTITUCIONAL
    public function alineacion_operacion_programa_institucional($tp_rep){
      // tp_rep=0 (normal), tp_rep=1 (Reporte) 
      $act_programa=$this->model_resumenactividad->resumen_actividad_categoria_institucional();
      $tabla='';
      $style='class="table table-bordered" style="width:100%;"';
      if($tp_rep==1){
        $style='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }
      elseif($tp_rep==2){
        $style='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

       $tabla.='
              <table '.$style.'>
                <thead>
                  <tr>
                    <th style="height:20px;font-size:10px;" colspan=5>ALINEACI&Oacute;N DE ACTIVIDADES POR CATEGORIA PROGRAMÁTICA</th>
                  </tr>
                  <tr>
                    <th style="width:1%; height:20px;font-size:10px;" align=center>#</th>
                    <th style="width:10%;font-size:10px;" align=center>PROGRAMA</th>
                    <th style="width:15%;font-size:10px;" align=center>DESCRIPCI&Oacute;N</th>
                    <th style="width:5%;font-size:10px;" align=center>NRO DE ACT.</th>
                  </tr>
                </thead>
              <tbody>';
              $nro=0;$sum=0;$sum_ppto=0;
              foreach($act_programa as $row){
                $nro++;
                $sum=$sum+$row['actividades'];
                //$sum_ppto=$sum_ppto+$row['ppto'];
                $tabla.='
                <tr>
                  <td style="height:14px;text-align:center;font-size:8px;">'.$nro.'</td>
                  <td style="font-size:10px; text-align:center"><b>'.$row['aper_programa'].' 00 000</b></td>';
                  if($tp_rep==2){ /// excel
                    $tabla.='<td>'.mb_convert_encoding($row['aper_descripcion'], 'cp1252', 'UTF-8').'</td>';
                  }
                  else{ /// normal
                    $tabla.='<td style="font-size:9px;">'.$row['aper_descripcion'].'</td>';
                  }
                  $tabla.='
                  <td style="font-size:11px; text-align:right;"><b>'.$row['actividades'].'</b></td>';
/*                  if($tp_rep==2){ /// excel
                    $tabla.='<td style="font-size:11px; text-align:right;"><b>'.$row['ppto'].'</b></td>';
                  }
                  else{
                    $tabla.='<td style="font-size:11px; text-align:right;"><b>'.number_format($row['ppto'], 2, ',', '.').'</b></td>';
                  }*/
                  $tabla.='
                  
                </tr>';
              }
            $tabla.='
              </tbody>
                <tr>
                  <td colspan=3 style="height:14px; font-size:10px;"> TOTAL </td>
                  <td style="font-size:11px; text-align:right;"><b>'.$sum.'</b></td>';
/*                  if($tp_rep==2){ /// excel
                    $tabla.='<td style="font-size:11px; text-align:right;"><b>'.$sum_ppto.'</b></td>';
                  }
                  else{
                    $tabla.='<td style="font-size:11px; text-align:right;"><b>'.number_format($sum_ppto, 2, ',', '.').'</b></td>';
                  }*/
                  $tabla.='
                  
                </tr>
            </table>';
      return $tabla;
    }
    
      ///// OPE - APERTURA PROGRAMATICA : REGIONAL
      public function alineacion_operacion_programa_regional($dep_id,$tp_rep){
      // tp_rep=0 (normal), tp_rep=1 (Reporte) 
      $act_programa=$this->model_resumenactividad->resumen_actividad_categoria_regional($dep_id);
      $tabla='';
      $style='class="table table-bordered" style="width:100%;"';
      if($tp_rep==1){
        $style='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }
      elseif($tp_rep==2){
        $style='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

       $tabla.='<table '.$style.'>
                <thead>
                  <tr>
                    <th style="height:20px;font-size:10px;" colspan=5>ALINEACI&Oacute;N DE ACTIVIDADES POR CATEGORIA PROGRAMÁTICA</th>
                  </tr>
                  <tr>
                    <th style="width:1%;height:20px;font-size:10px;" align=center>#</th>
                    <th style="width:7%;font-size:10px;" align=center>PROGRAMA</th>
                    <th style="width:15%;font-size:9px;" align=center>DESCRIPCI&Oacute;N</th>
                    <th style="width:5%;font-size:9px;" align=center>NRO DE ACT.</th>
                  </tr>
                </thead>
              <tbody>';
              $nro=0;$sum=0;$sum_ppto=0;
              foreach($act_programa as $row){
                $nro++;
                $sum=$sum+$row['actividades'];
                //$sum_ppto=$sum_ppto+$row['ppto'];
                $tabla.='
                <tr>
                  <td style="height:14px;text-align:center;font-size:8px;">'.$nro.'</td>
                  <td style="font-size:10px; text-align:center"><b>'.$row['aper_programa'].' 00 000</b></td>';
                  if($tp_rep==2){ /// excel
                    $tabla.='<td>'.mb_convert_encoding($row['aper_descripcion'], 'cp1252', 'UTF-8').'</td>';
                  }
                  else{ /// normal
                    $tabla.='<td style="font-size:9px;">'.$row['aper_descripcion'].'</td>';
                  }
                  $tabla.='
                  <td style="font-size:11px; text-align:right;"><b>'.$row['actividades'].'</b></td>';
                 
                  $tabla.='
                  
                </tr>';
              }
        $tabla.='
              </tbody>
                <tr>
                  <td colspan=3 style="height:14px; font-size:10px;"> TOTAL </td>
                  <td style="font-size:11px; text-align:right;"><b>'.$sum.'</b></td>';
                  $tabla.='
                  
                </tr>
            </table>';
      return $tabla;
    }


    ///// OPE-OBJETIVO : INSTITUCIONAL
    public function alineacion_operacion_ogestion_institucional($tp_rep){
      // tp_rep=0 (normal), tp_rep=1 (Reporte) 
      $act_ogestion=$this->model_resumenactividad->resumen_actividad_objetivo_gestion_institucional();
      $tabla='';
      $style='class="table table-bordered" style="width:100%;"';
      if($tp_rep==1){
        $style='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }
      elseif($tp_rep==2){
        $style='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

       $tabla.='
              <table '.$style.'>
                <thead>
                  <tr>
                    <th style="height:20px;font-size:10px;" colspan=4>ALINEACI&Oacute;N DE ACTIVIDADES POR A.C.P.</th>
                  </tr>
                  <tr>
                    <th style="width:70%;height:20px;font-size:10px;" align=center>ACCION DE CORTO PLAZO '.$this->gestion.'</th>
                    <th style="width:5%;font-size:10px;" align=center>NRO DE ACT.</th>
                  </tr>
                </thead>
              <tbody>';
              $nro=0;$sum=0;
              foreach($act_ogestion as $row){
                $nro++;
                $sum=$sum+$row['actividades'];
                $tabla.='
                <tr>';
                  if($tp_rep==2){ /// excel
                    $tabla.='<td style="width:70%; height:10px;">'.mb_convert_encoding($row['og_codigo'].'.- '.$row['og_objetivo'], 'cp1252', 'UTF-8').'</td>';
                  }
                  else{ /// normal
                    $tabla.='<td style="width:70%; height:10px;font-size:10.5px;">&nbsp;<b> ACP '.$row['og_codigo'].' - </b>'.$row['og_objetivo'].'</td>';
                  }
                  $tabla.='
                  <td style="width:7%; text-align:right; font-size:11px;"><b>'.$row['actividades'].'</b></td>
                </tr>';
              }
        $tabla.='
              </tbody>
                <tr>
                  <td style="height:14px; font-size:10px;"> TOTAL </td>
                  <td style="text-align:right; font-size:10px;"><b>'.$sum.'</b></td>
                </tr>
            </table>';
      return $tabla;
    }

    ///// OPE-OBJETIVO : REGIONAL
    public function alineacion_operacion_ogestion_regional($dep_id,$tp_rep){
      $act_ogestion=$this->model_resumenactividad->resumen_actividad_objetivo_gestion_regional($dep_id);
      $tabla='';

      $style='class="table table-bordered" style="width:100%;"';
      if($tp_rep==1){
        $style='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }
      if($tp_rep==2){ /// excel
        $style='border="1" cellpadding="0" cellspacing="0" class="tabla"';
      }

       $tabla.='
              <table '.$style.'>
                <thead>
                  <tr>
                    <th style="height:20px; font-size: 10px;" colspan=2>ALINEACI&Oacute;N DE ACTIVIDADES POR A.C.P.</th>
                  </tr>
                  <tr>
                    <th style="width:70%;height:20px;font-size: 10px;" align=center>ACCION DE CORTO PLAZO '.$this->gestion.'</th>
                    <th style="width:8%;font-size: 10px;" align=center>NRO DE ACT.</th>
                
                  </tr>
                </thead>
              <tbody>';
              $nro=0;$sum=0;
              foreach($act_ogestion as $row){
                $nro++;
                $sum=$sum+$row['actividades'];
                $tabla.='
                <tr>';
                  if($tp_rep==2){ /// excel
                    $tabla.='<td style="width:70%; height:10px;">'.mb_convert_encoding($row['og_codigo'].'.- '.$row['og_objetivo'], 'cp1252', 'UTF-8').'</td>';
                  }
                  else{ /// normal
                    $tabla.='<td style="width:70%; height:10px;font-size:10.5px;">&nbsp;<b>ACP '.$row['og_codigo'].' - </b>'.$row['og_objetivo'].'</td>';
                  }
                  $tabla.='
                  <td style="width:8%; text-align:right; font-size:10px;"><b>'.$row['actividades'].'</b></td>
                
                </tr>';
              }
        $tabla.='
              </tbody>
                <tr>
                  <td style="height:14px; font-size:10px;"> TOTAL </td>
                  <td style="text-align:right; font-size:10px;"><b>'.$sum.'</b></td>
              
                </tr>
            </table>';
      return $tabla;
    }

    /*-----REPORTE ALINEACION POA (REGIONAL) 2020-2021-----*/
    public function reporte_alineacion_poa($dep_id){
      $tabla='';
      $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      if(count($data['departamento'])!=0){
        if($dep_id==0){
          $titulo='INSTITUCIONAL';
          $data['aling_prog']=$this->alineacion_operacion_programa_institucional(1); /// Institucional Programa
          $data['aling_og']=$this->alineacion_operacion_ogestion_institucional(1); /// Institucional Ogestion
        }
        else{
          $titulo=strtoupper($data['departamento'][0]['dep_departamento']);
          $data['aling_prog']=$this->alineacion_operacion_programa_regional($dep_id,1); /// Oficina Nacional Programa
          $data['aling_og']=$this->alineacion_operacion_ogestion_regional($dep_id,1); /// pando Programa
        }
        
        $data['cabecera']='
        <page_header>
          <br><div class="verde"></div>
          <table class="page_header" border="0">
              <tr>
                <td style="width: 100%; text-align: left">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                      
                      <td width=80%; align=left>
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                              <td style="width:100%; height: 1.2%; font-size: 25px;"><b>CAJA NACIONAL DE SALUD</b></td>
                          </tr>
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 15px;">CUADRO DE ALINEACION POA - ACP / '.$this->gestion.' - <b>'.$titulo.'</b></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
          </table>
        </page_header>';
        $this->load->view('admin/reportes_cns/resumen_actividad_programa/reporte_alineacion_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }

    /*----- CABECERA REPORTE -----*/
    public function cabecera_reporte($titulo){
      $tabla='';



      return $tabla;
    }



    /*----- EXPORTAR ALINEACION POA -----*/
    public function exportar_alineacion_poa($dep_id){
      $tabla='';
      $data['departamento']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      if(count($data['departamento'])!=0){
        if($dep_id==0){
          $data['titulo']='INSTITUCIONAL';
          $data['aling_prog']=$this->alineacion_operacion_programa_institucional(2); /// Institucional Programa
          $data['aling_og']=$this->alineacion_operacion_ogestion_institucional(2); /// Institucional Ogestion
        }
        else{
          $data['titulo']=strtoupper($data['departamento'][0]['dep_departamento']);
          $data['aling_prog']=$this->alineacion_operacion_programa_regional($dep_id,2); /// Oficina Nacional Programa
          $data['aling_og']=$this->alineacion_operacion_ogestion_regional($dep_id,2); /// pando Programa
        }
        
        date_default_timezone_set('America/Lima');
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=Alineacion_poa_or_".$data['departamento'][0]['dep_departamento'].".xls"); //Indica el nombre del archivo resultante
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "";
        $tabla.='ALINEACION '.$data['titulo'].' - '.$this->gestion.'<br>'.$data['aling_prog'].'<br>'.$data['aling_og'];
        echo $tabla;
         // $this->load->view('admin/reportes_cns/resumen_actividad_programa/reporte_alineacion_poa', $data);
      }
      else{
        echo "Error !!!";
      }
    }

    /*--- NOMBRE MES ---*/
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

    /*------ MENU ------*/
    function menu(){
      $enlaces=$this->menu_modelo->get_Modulos(7);
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