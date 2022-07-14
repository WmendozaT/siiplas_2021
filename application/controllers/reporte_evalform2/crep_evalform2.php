<?php
/*controlador para evaluacion ACP GESTION 2022*/
class Crep_evalform2 extends CI_Controller {  
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
      }
      else{
          redirect('/','refresh');
      }
    }

  /// MENU EVALUACIÓN POA 
  public function menu_eval_form2(){
    $data['menu']=$this->menu(7); //// genera menu
    $data['titulo']=' <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <section id="widget-grid" class="well">
                            <div class="">
                                <h2>EVALUACIÓN CONSOLIDADO DE OPERACIONES '.$this->gestion.'</h2>
                            </div>
                        </section>
                      </article>';
    $data['nro']=count($this->model_proyecto->list_departamentos()); /// nro de regionales
    $data['matriz']=$this->matriz_eval_form2(); /// Matriz

    $data['trimestre']=$this->trimestre[0]['trm_descripcion'].' / '.$this->gestion;
    $data['cabecera']=$this->cabecera_reporte_grafico(); /// Cabecera Grafico
    $data['tabla_vista']=$this->tabla_eval_form2($data['matriz'],$data['nro'],0); /// Tabla Vista
    $data['tabla_impresion']=$this->tabla_eval_form2($data['matriz'],$data['nro'],1); /// Tabla Impresion

    //-------
    $data['matriz_form2_regresion']=$this->tabla_trimestral_acumulado_institucional();
    $data['tabla_vista_acumulado']=$this->get_tabla_cumplimiento_form2_priorizados_institucional(0);
    $data['tabla_vista_acumulado_impresion']=$this->get_tabla_cumplimiento_form2_priorizados_institucional(1);

    $this->load->view('admin/reportes_cns/repevaluacion_form2/rep_form2', $data);
  }


  /*-- GENERA TABLA PARA EVALUACION TRIMESTRAL INSTITUCIONAL --*/
  public function tabla_trimestral_acumulado_institucional(){

    for ($i=1; $i <=4 ; $i++) { 
      $valor=$this->calificacion_trimestral_acumulado_institucional($i);
      $matriz[1][$i]=$valor[1];  /// prog
      $matriz[2][$i]=$valor[2];  /// ejec
      $matriz[3][$i]=$valor[3];  /// % cumplimiento trimestral
      $matriz[4][$i]=(100-$valor[3]);  /// % no cumplido
    }

    $total=$matriz[1][4];

    if($total!=0){
      for ($i=1; $i <=4 ; $i++) { 
        $matriz[5][$i]=round((($matriz[1][$i]/$total)*100),2);  /// % Programado con respecto al total acumulado
        $matriz[6][$i]=round((($matriz[2][$i]/$total)*100),2);  /// % Ejecutado con respecto al total acumulado
      }
    }
    else{
      for ($i=1; $i <=4 ; $i++) { 
        $matriz[5][$i]=0;  /// % Programado con respecto al total acumulado
        $matriz[6][$i]=0;  /// % Ejecutado con respecto al total acumulado
      }
    }

    return $matriz;
  }


 /*-- CALIFICACION TRIMESTRAL INSTITUCIONAL --*/
  public function calificacion_trimestral_acumulado_institucional($trimestre){
    $valor = array( '1' => '0','2' => '0','3' => '0','4' => '0');

    if(count($this->model_objetivoregion->get_suma_total_prog_form2_institucional())!=0){
      $suma_total_prog=0; $suma_prog=0; $suma_ejec=0;
      //// Suma total programado por operacion
      $prog_total=$this->model_objetivoregion->get_suma_total_prog_form2_institucional();
      if(count($prog_total)!=0){
        $suma_total_prog=$prog_total[0]['programado_total'];
      }
      ///-----

      for ($i=1; $i <=$trimestre; $i++) {
        $get_trm=$this->model_objetivoregion->get_suma_trimestre_prog_form2_institucional($i); /// Temporalidad Programado
        $get_trm_ejec=$this->model_objetivoregion->get_suma_trimestre_ejec_form2_institucional($i); /// Temporalidad Ejecutado

        if(count($get_trm)!=0){
          $suma_prog=$suma_prog+$get_trm[0]['prog']; 
        }

        if(count($get_trm_ejec)!=0){
          $suma_ejec=$suma_ejec+$get_trm_ejec[0]['ejec'];
        }

        $ejecucion=0;
        if($suma_ejec!=0){
          $ejecucion=round((($suma_ejec/$suma_prog)*100),2);
        }

        $cumplimiento_gestion=0;
        if($suma_total_prog!=0){
          $cumplimiento_gestion=round((($suma_ejec/$suma_total_prog)*100),2);
        }
      }


      $valor[1]=$suma_prog; /// Programado Acumulado al trimestre
      $valor[2]=$suma_ejec; /// Ejecutado Acumulado al trimestre
      $valor[3]=$ejecucion; /// Cumplimiento al trimestre
      $valor[4]=$cumplimiento_gestion; /// Cumplimiento a la Gestion
    }

    return $valor; 
  }


  /*-- VISTA DE TABLA CUMPLIMUENTO PARA IMPRESION (ACUMULADO) --*/
  public function get_tabla_cumplimiento_form2_priorizados_institucional($tp_rep){
    /// tp_rep=0 normal
    /// tp_rep=1 Reporte

    $valor=$this->tabla_trimestral_acumulado_institucional();
    $tabla='';

    if($tp_rep==0){ /// VISTA NORMAL

      $tabla.='
      <table class="table table-bordered" border=0.2 style="width:100%;">
        <thead>
          <tr align=center>
            <th style="width:20%; height:30px; text-align:center">I TRIMESTRE</th>
            <th style="width:20%;text-align:center">II TRIMESTRE</th>
            <th style="width:20%;text-align:center">III TRIMESTRE</th>
            <th style="width:20%;text-align:center">IV TRIMESTRE</th>
          </tr>
        </thead>
        <tbody>
          <tr>';
          for ($i=1; $i <=4 ; $i++) {
            $tabla.='
            <td style="width:6%;" align=center>
              <table class="table table-bordered" border=0.2 style="width:80%;">
                <tr>
                  <td style="width:50%;"><b>(%) PROG.</b></td>
                  <td style="width:50%;font-size: 12px; color:blue" align=right><b>'.$valor[5][$i].'%</b></td>
                </tr>
                <tr>
                  <td><b>(%) CUMP.</b></td>
                  <td style="font-size: 12px; color:blue" align=right><b>'.$valor[6][$i].'%</b></td>
                </tr>
              </table>
            </td>';
          }
          $tabla.='
          </tr>  
        </tbody>
      </table>';
    }
    else{ /// VISTA PARA REPORTES
      $tabla.='
      <table class="change_order_items" border=1 style="width:100%;">
        <thead>
          <tr align=center>
            <th style="width:10%; text-align:center"></th>
            <th style="width:23%; height:20px; text-align:center">I TRIMESTRE</th>
            <th style="width:23%;text-align:center">II TRIMESTRE</th>
            <th style="width:23%;text-align:center">III TRIMESTRE</th>
            <th style="width:23%;text-align:center">IV TRIMESTRE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width:10%;"><b>(%) PROG.</b></td>';
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:20%; height:20px; color:blue" align=right><b>'.$valor[5][$i].'%</b></td>';
            }
          $tabla.='
          </tr> 
          <tr>
            <td style="width:10%;"><b>(%) CUMP.</b></td>';
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:20%; height:20px; color:blue" align=right><b>'.$valor[6][$i].'%</b></td>';
            }
          $tabla.='
          </tr>  
        </tbody>
      </table>';
    }

    return $tabla;
  }



  /// TABLA  EVALUACION DE FORMULARIO 2
  public function tabla_eval_form2($matriz,$nro,$tp_rep){
    $tabla='';
    // tp_rep : 0 normal
    // tp_rep : 1 impresion
    $tyle='class="table table-bordered" border=0.2 style="width:100%;"';
    if($tp_rep==1){
      $tyle='class="change_order_items" border=1 style="width:100%;"';
    }

    $tabla.='
        <table '.$tyle.'>
          <thead>
            <tr align=center>
              <th style="width:9.09%;"></th>';
              for ($i=1; $i<=$nro; $i++) { 
                $tabla.='<th style="width:9.09%;"><center>'.$matriz[$i][2].'</center></th>';
              }
              $tabla.='
              </tr>
            </thead>
          <tbody>
            <tr>
              <td style="height:20px;"><b>(%) CUMPLIMIENTO</b></td>';
              for ($i=1; $i<=$nro; $i++) { 
                $tabla.='<td style="width:9.09%;" align=right><b>'.$matriz[$i][6].' %</b></td>';
              }
              $tabla.='
            </tr>
          </tbody>
        </table>';

    return $tabla;
  }


  /// MATRIZ EVALUACION DE FORMULARIO 2
  public function matriz_eval_form2(){
    $regionales=$this->model_proyecto->list_departamentos();
    $nro=0;
    foreach($regionales as $row){
      $calificacion=$this->calificacion_total_form2_regional($row['dep_id']);
      $nro++;
      $mat[$nro][1]=$row['dep_id'];
      $mat[$nro][2]=strtoupper($row['dep_sigla']);
      $mat[$nro][3]=$calificacion[1]; /// programado trimestral
      $mat[$nro][4]=$calificacion[2]; /// ejecutado trimestral
      $mat[$nro][5]=$calificacion[3]; /// total programado Gestion
      $mat[$nro][6]=$calificacion[4]; /// % cumplimiento
    }

    return $mat;
  }


  /*--- PARAMETROS DE CALIFICACION OPERACIONES REGIONAL ---*/
  public function calificacion_total_form2_regional($dep_id){
    $prog_trimestre=0; $ejec_trimestre=0;$prog_total_form2=0;

    $prog_total=$this->model_objetivoregion->get_suma_total_prog_form2_regional($dep_id);
    if(count($prog_total)!=0){
      $prog_total_form2=$prog_total[0]['programado_total'];
    }

    for ($i=1; $i <=$this->tmes; $i++) { 
      $prog=$this->model_objetivoregion->get_suma_trimestre_prog_form2_regional($dep_id,$i);
      if(count($prog)!=0){
        $prog_trimestre=$prog_trimestre+$prog[0]['prog'];
      }

      $ejec=$this->model_objetivoregion->get_suma_trimestre_ejec_form2_regional($dep_id,$i);
      if(count($ejec)!=0){
        $ejec_trimestre=$ejec_trimestre+$ejec[0]['ejec'];
      }
    }

    $calif[1]=$prog_trimestre; /// programado trimestral
    $calif[2]=$ejec_trimestre; /// ejecutado trimestral
    $calif[3]=$prog_total_form2; /// total programado Gestion
    $calif[4]=0;

    if($prog_total_form2!=0){
      $calif[4]=round((($calif[2]/$prog_total_form2)*100),2);
    }

    return $calif;
  }


 /*---- CABECERA REPORTE OPERACIONES POR REGIONALES (GRAFICO)----*/
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
                    <td style="height: 5%; text-align:center">EVALUACIÓN DE OPERACIONES</td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }

  /*=== GENERAR MENU ===*/
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
}