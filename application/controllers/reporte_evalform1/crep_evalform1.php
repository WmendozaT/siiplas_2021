<?php
/*controlador para evaluacion ACP GESTION 2022*/
class Crep_evalform1 extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->load->model('mestrategico/model_objetivogestion');
            $this->load->model('mestrategico/model_objetivoregion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->trimestre = $this->model_evaluacion->trimestre();
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
             $this->mes = $this->mes_nombre();
            $this->load->library('eval_acp');
        }
        else{
            redirect('/','refresh');
        }
    }

  /// MENU EVALUACIÓN POA FORM 1
  public function menu_eval_acp(){
    $data['menu']=$this->menu(7); //// genera menu
    $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
    
    $matriz_trimestral=$this->matriz_cumplimiento_form1_institucional(0); /// trimestral
    $matriz_gestion=$this->matriz_cumplimiento_form1_institucional(1); /// acumulado Gestion


    $detalle_acp=$this->detalle_cumplimiento_form1_institucional($matriz_trimestral,$matriz_gestion,0); /// Detalle de Form1 Alineados Vista
    $detalle_acp_impresion=$this->detalle_cumplimiento_form1_institucional($matriz_trimestral,$matriz_gestion,1); /// Detalle de Form1 Alineados Impresion

    $data['matriz_trimestral']=$matriz_trimestral; /// matriz para el grafico de evaluacion ACP Trimestral
    $data['matriz_gestion']=$matriz_gestion; /// matriz para el grafico de evaluacion ACP Acumulado
    $data['nro']=count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); /// nro de ACP alineados
    
    $tabla='';

    $tabla.=' 
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">

        <div id="cabecera" style="display: none"></div>
        <div id="calificacion" style="display: none">'.$this->calificacion_form1_institucional(1,1).'</div>
        <form >
          <fieldset>   
            <hr>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div>'.$this->calificacion_form1_institucional(0,0).'</div>
                <center>
                  <div id="grafico_trimestral" style="width: 900px; height: 650px; margin: 10px auto; text-align:center"></div>
                </center>
                <br>
                <h4><b>DETALLE EJECUCION ACCIONES DE CORTO PLAZO '.$this->gestion.'</b></h4>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div>'.$this->calificacion_form1_institucional(0,1).'</div>
                <center>
                  <div id="grafico_gestion" style="width: 900px; height: 650px; margin: 10px auto; text-align:center"></div>
                </center>
              </div>             
              
                '.$detalle_acp.'
                <br>
                
                <div id="tabla_impresion_detalle" style="display: none">
                  '.$detalle_acp_impresion.'
                </div>

            </div>
          </fieldset>
        </form>';

      $data['informacion_trimestral']=$tabla;
    
      $this->load->view('admin/reportes_cns/repevaluacion_form1/rep_menu', $data);
/*    $matriz=$this->matriz_cumplimiento_form1_institucional(0);
     for ($i=0; $i < count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); $i++) { 
      for ($j=0; $j < 4; $j++) { 
        echo "[".$matriz[$i][$j]."]";
      }
      echo "<br>";
    }*/
  }


  //// Calificacion Cumplimiento ACP Institucional
  public function calificacion_form1_institucional($tp_rep,$tp){
    $lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2();

     $eficacia=0;
     foreach($lista_acp as $row){
      $cumplimiento=$this->get_cumplimiento_acp($row['og_id'],$tp);
        $eficacia=$eficacia+$cumplimiento; /// cumplimiento trimestral/Acumulado
     }

     if(count($lista_acp)!=0){
      $eficacia=round(($eficacia/count($lista_acp)),2);
     }


    $tp='danger';
    $titulo='ERROR EN LOS VALORES';
    if($eficacia<=50){$tp='danger';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> INSATISFACTORIO (0% - 50%)';} /// Insatisfactorio - Rojo
    if($eficacia > 50 & $eficacia <= 75){$tp='warning';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> REGULAR (51% - 75%)';} /// Regular - Amarillo
    if($eficacia > 75 & $eficacia <= 99){$tp='info';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> BUENO (76% - 99%)';} /// Bueno - Azul
    if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

    $tabla='<h5 class="alert alert-'.$tp.'" style="font-family: Arial;" align="center"><b>'.$titulo.'</b></h5>';

    return $tabla;
  }

  //// Detalle Ejecucion ACP Institucional 
  public function detalle_cumplimiento_form1_institucional($matriz,$matriz_gestion,$tp_rep){
    /// tp_rep : 0 (vista)
    /// tp_rep : 1 (impresion)
    $tabla='';

    if($tp_rep==0){
    $tabla.='
    <center>
        <style>
          table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            font-family: Arial;
          }
          th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
          }
        </style>
      <table class="table table-dark table-borderless" border=0.2 style="width:90%;">
        <thead>
          <tr>
            <th style="width:10%;"></th>
            <th style="width:60%;">DETALLE A.C.P. INSTITUCIONAL</th>
            <th style="width:10%;">(%) CUMP. TRIMESTRAL</th>
            <th style="width:10%;">(%) CUMP. ACUMULADO '.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
      $suma_trimestral=0;$suma_acumulado=0;
      for ($i=0; $i < count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); $i++) { 
        $tabla.='
          <tr>
            <td style="font-size:14px; text-align:center">'.$matriz[$i][1].'</td>
            <td style="font-size:10px;">'.$matriz[$i][2].'</td>
            <td style="font-size:14px; text-align:right" bgcolor="#ccf7f1"><b>'.$matriz[$i][3].' %</b></td>
            <td style="font-size:14px; text-align:right; color:white" bgcolor="#81aba5"><b>'.$matriz_gestion[$i][3].' %</b></td>
          </tr>';
          $suma_trimestral=$suma_trimestral+$matriz[$i][3];
          $suma_acumulado=$suma_acumulado+$matriz_gestion[$i][3];
      }
      $tabla.='
        </tbody>
        <tr>
            <td style="font-size:15px;text-align:right;" colspan=2><b>TOTAL CUMPLIMIENTO</b></td>
            <td style="font-size:16px; text-align:right" bgcolor="#ccf7f1"><b>'.round(($suma_trimestral/count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2())),2).' %</b></td>
            <td style="font-size:16px; text-align:right; color:white" bgcolor="#81aba5"><b>'.round(($suma_acumulado/count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2())),2).' %</b></td>
        </tr>
      </table>
    </center>';
    }
    else{
      $tabla.='
      <center>
          <style>
            table{font-size: 10px;
              font-family: Arial;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
          </style>
        <table cellpadding="0" cellspacing="0" class="tabla" border="0.5" style="width:100%;" align=center>
          <thead>
            <tr>
              <th style="width:10%;"></th>
              <th style="width:70%;">DETALLE A.C.P. INSTITUCIONAL</th>
              <th style="width:5%;">(%) CUMP. TRIMESTRAL</th>
              <th style="width:5%;">(%) CUMP. ACUMULADO</th>
            </tr>
          </thead>
          <tbody>';
          $suma_trimestral=0;$suma_acumulado=0;
        for ($i=0; $i < count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); $i++) { 
          $tabla.='
          <tr>
            <td style="font-size:12px; text-align:center">'.$matriz[$i][1].'</td>
            <td style="font-size:10px;">'.$matriz[$i][2].'</td>
            <td style="font-size:10px; text-align:right"><b>'.$matriz[$i][3].' %</b></td>
            <td style="font-size:10px; text-align:right"><b>'.$matriz_gestion[$i][3].' %</b></td>
          </tr>';
          $suma_trimestral=$suma_trimestral+$matriz[$i][3];
          $suma_acumulado=$suma_acumulado+$matriz_gestion[$i][3];
        }
        $tabla.='
          </tbody>
          <tr>
            <td style="font-size:15px;text-align:right;" colspan=2><b>TOTAL CUMPLIMIENTO </b></td>
            <td style="font-size:16px; text-align:right">'.round(($suma_trimestral/count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2())),2).' %</td>
            <td style="font-size:16px; text-align:right"><b>'.round(($suma_acumulado/count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2())),2).' %</b></td>
          </tr>
        </table>
      </center>';
    }

    return $tabla;
  }





  //// Matriz lista de cumplimiento de Form 1 Institucional 
  public function matriz_cumplimiento_form1_institucional($tp){
    $lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2();
     for ($i=0; $i <count($lista_acp); $i++) { 
      for ($j=0; $j <4 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }

     //// acumulado
     $nro=0;
     foreach($lista_acp as $row){
      $cumplimiento=$this->get_cumplimiento_acp($row['og_id'],$tp);

        $matriz[$nro][0]=$row['og_id']; /// cod OG
        $matriz[$nro][1]='<b>ACP.- '.$row['og_codigo'].'</b>'; /// cod OG
        $matriz[$nro][2]=$row['og_objetivo']; /// detalle OG
        $matriz[$nro][3]=$cumplimiento; /// cumplimiento trimestral/Acumulado
      $nro++;
     }

     return $matriz;
  }

  /// funcion para devolver el cumplimiento por ACP
  public function get_cumplimiento_acp($og_id,$tp){
    /// tp : 0 (trimestre)
    /// tp : 1 (Gestion)

      $list_form2_alineado_a_acp=$this->model_objetivogestion->get_list_form2_x_ogestion_trimestral($og_id,$this->tmes);
    if($tp==1){
      $list_form2_alineado_a_acp=$this->model_objetivogestion->get_list_form2_x_ogestion($og_id);
    }

    $cumplimiento_acp=0;
    foreach($list_form2_alineado_a_acp as $f2){
        $get_trm_ejec=$this->model_objetivoregion->get_ejec_form2_institucional_al_trimestre($f2['og_codigo'],$f2['or_codigo'],$this->tmes); /// Temporalidad Ejecutado trimestre
      if($tp==1){ 
        $get_trm_ejec=$this->model_objetivoregion->get_ejec_form2_institucional($f2['og_codigo'],$f2['or_codigo']); /// Temporalidad Ejecutado Gestion
      }

      $ejec_form2_institucional=0;
      if(count($get_trm_ejec)!=0){
        $ejec_form2_institucional=$get_trm_ejec[0]['ejecutado'];
      } 

      $ejecutado=0;
      if($f2['programado_total']!=0){
        $ejecutado=round((($ejec_form2_institucional/$f2['programado_total'])*100),2);
      }

      $cumplimiento_acp=$cumplimiento_acp+$ejecutado;
    }
    ///-----------------------
    
    if(count($list_form2_alineado_a_acp)!=0){
      $cumplimiento_acp=round(($cumplimiento_acp/count($list_form2_alineado_a_acp)),2);
    }
    
    return $cumplimiento_acp;
  }




  //// AYUDA MEMORIA DEL CUMPLIMIENTO DE LAS ACCIONES
  public function matriz_cumplimiento_form1_institucional2(){
    $lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2();
     for ($i=0; $i <count($lista_acp); $i++) { 
      for ($j=0; $j <4 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }

     //// acumulado

     foreach($lista_acp as $row){
      $form2=$this->model_objetivogestion->get_list_form2_x_ogestion($row['og_id']);

      echo 'ACP'.$row['og_codigo'].' - ('.$row['og_id'].')<br>';
      ///-----------------------
      $cumplimiento=0;
      foreach($form2 as $f2){
        $get_trm_ejec=$this->model_objetivoregion->get_ejec_form2_institucional($f2['og_codigo'],$f2['or_codigo']); /// Temporalidad Ejecutado
        $ejec_form2_institucional=0;
        if(count($get_trm_ejec)!=0){
          $ejec_form2_institucional=$get_trm_ejec[0]['ejecutado'];
        } 

        $ejecutado=0;
        if($f2['programado_total']!=0){
          $ejecutado=round((($ejec_form2_institucional/$f2['programado_total'])*100),2);
        }

        $cumplimiento=$cumplimiento+$ejecutado;

        echo ' ---- OPE'.$f2['og_codigo'].' - '.$f2['or_codigo'].' -> '.$f2['programado_total'].' ----> '.$ejecutado.'%<br>';
      }
      ///-----------------------
      
      if(count($form2)!=0){
        $cumplimiento=round(($cumplimiento/count($form2)),2);
      }
      echo "--------> ".$cumplimiento." %<br>";
     }

     echo "===========<br>";

     //// trimestral
     //$lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2_trimestral($this->tmes);
     foreach($lista_acp as $row){
      $form2=$this->model_objetivogestion->get_list_form2_x_ogestion_trimestral($row['og_id'],$this->tmes);

      echo 'ACP'.$row['og_codigo'].' - ('.$row['og_id'].')<br>';
      ///-----------------------
      foreach($form2 as $f2){
        $get_trm_ejec=$this->model_objetivoregion->get_ejec_form2_institucional_al_trimestre($f2['og_codigo'],$f2['or_codigo'],$this->tmes); /// Temporalidad Ejecutado
        $ejec_form2_institucional=0;
        if(count($get_trm_ejec)!=0){
          $ejec_form2_institucional=$get_trm_ejec[0]['ejecutado'];
        } 

        $ejecutado=0;
        if($f2['programado_total']!=0){
          $ejecutado=round((($ejec_form2_institucional/$f2['programado_total'])*100),2);
        }

        echo ' ---- OPE'.$f2['og_codigo'].' - '.$f2['or_codigo'].' -> '.$f2['programado_total'].' ----> '.$ejecutado.'%<br>';
      }
      ///-----------------------

     }
     //return $matriz;
  }




/*  public function matriz_cumplimiento_form1_institucional2(){
    $lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2();
     for ($i=0; $i <count($lista_acp); $i++) { 
      for ($j=0; $j <4 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }

     $nro=0;
     foreach($lista_acp as $row){
      $get_trm_ejec=$this->model_objetivogestion->get_ejec_acp_institucional_ejecutado($row['og_id']); /// Temporalidad Ejecutado
      $ejec_form1_institucional=0;
      if(count($get_trm_ejec)!=0){
        $ejec_form1_institucional=$get_trm_ejec[0]['ejecutado'];
      }  


        $matriz[$nro][0]='<b>ACP.- '.$row['og_codigo'].'</b>'; /// cod OG
        $matriz[$nro][1]=$row['og_objetivo']; /// detalle OG
        $matriz[$nro][2]=$row['programado_total']; /// Programado Total
        $matriz[$nro][3]=$ejec_form1_institucional; /// ejecutado Total
        $ejecutado=0;
        if($row['programado_total']!=0){
          $ejecutado=round((($ejec_form1_institucional/$row['programado_total'])*100),2);
        }
        $matriz[$nro][4]=$ejecutado; /// ejecutado Total %

        $nro++;
     }

     return $matriz;
  }*/

/*  public function matriz_eval_form2(){
    $regionales=$this->model_proyecto->list_departamentos();
    

    $nro=0;
    foreach($regionales as $reg){
        
        /// -----------------------------------------------------------------------------------------
        $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($reg['dep_id']);
        $nro_prog=count($lista_ogestion);
        $suma_cumplimiento_trimestral=0;
        $suma_cumplimiento_gestion=0;
        
        foreach($lista_ogestion as $row){
          $calificacion=$this->eval_oregional->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
          $suma_cumplimiento_trimestral=$suma_cumplimiento_trimestral+$calificacion[3];
          $suma_cumplimiento_gestion=$suma_cumplimiento_gestion+$calificacion[4];
        }

         $cumplimiento=0;
         if($nro_prog!=0){
            $cumplimiento= round(($suma_cumplimiento_gestion/$nro_prog),2); 
         }

        /// -----------------------------------------------------------------------------------------

      $mat[$nro][1]=$reg['dep_id'];
      $mat[$nro][2]=strtoupper($reg['dep_sigla']);
      $mat[$nro][3]=$cumplimiento; /// % cumplimiento
      $nro++;
    }

    return $mat;
  }*/


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
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
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


  /*======= GENERAR MENU ==========*/
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
  
}