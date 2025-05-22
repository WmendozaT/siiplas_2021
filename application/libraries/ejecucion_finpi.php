<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class ejecucion_finpi extends CI_Controller{

  public function __construct (){
    parent::__construct();
    $this->load->model('programacion/model_proyecto');
    $this->load->model('programacion/model_faseetapa');
    $this->load->model('mantenimiento/model_partidas');
    $this->load->model('mantenimiento/model_ptto_sigep');
    $this->load->model('programacion/model_componente');
    $this->load->model('mantenimiento/model_configuracion');
    $this->load->model('programacion/insumos/model_insumo');
    $this->load->model('programacion/model_producto');
    $this->load->model('programacion/model_componente');
    $this->load->model('ejecucion/model_evaluacion');
    $this->load->model('menu_modelo');
    $this->load->library('security');
    $this->gestion = $this->session->userData('gestion');
    $this->adm = $this->session->userData('adm');
    $this->dist = $this->session->userData('dist');
    $this->dep_id = $this->session->userData('dep_id');
    $this->dist_tp = $this->session->userData('dist_tp');
    $this->fun_id = $this->session->userdata("fun_id");
    $this->tmes = $this->session->userData('trimestre');
    $this->tp_adm = $this->session->userData('tp_adm');
    $this->mes = $this->mes_nombre();
    $this->ppto= $this->session->userData('verif_ppto');
    $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
    $this->mes_sistema=$this->session->userData('mes'); /// mes sistema

  }


  /*-- CALIFICACION EJECUCION POR REGIONAL/INSTITUCIONAL POR TRIMESTRE-*/
  public function cumplimiento_trimestre($dep_id,$tp){
    //// Asig - Ejec (al trimestre)
    if($dep_id==0){ /// Institucional
      $ppto_prog_trimestre=$this->model_ptto_sigep->ppto_poa_ejecutado_al_trimestre_institucional($this->tmes,1); /// prog
      $ppto_ejec_trimestre=$this->model_ptto_sigep->ppto_poa_ejecutado_al_trimestre_institucional($this->tmes,2); /// ejec
    }
    else{ /// Regional
      $ppto_prog_trimestre=$this->model_ptto_sigep->ppto_poa_ejecutado_al_trimestre_regional($dep_id,$this->tmes,1); /// prog
      $ppto_ejec_trimestre=$this->model_ptto_sigep->ppto_poa_ejecutado_al_trimestre_regional($dep_id,$this->tmes,2); /// ejec
    }

    $cumplimiento_trimestral=0;
    $ppto_ejec=0;
    if(count($ppto_ejec_trimestre)!=0){
      $ppto_ejec=$ppto_ejec_trimestre[0]['monto'];
      $cumplimiento_trimestral=round((($ppto_ejec/$ppto_prog_trimestre[0]['monto'])*100),2);
    }

    if($tp==0){ //// vista
      return $this->parametro_calificacion($cumplimiento_trimestral,1);
    }
    else{
      return $cumplimiento_trimestral;
    }
    
  }



  /*-- CALIFICACION EJECUCION POR REGIONAL/INSTITUCIONAL POR GESTION --*/
  public function cumplimiento_gestion($dep_id,$tp){
    //// Asig - Ejec (a Gestion)
    if($dep_id==0){ /// Institucional
      $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_institucional_pi_aprobados(1); /// monto total asignado poa
      $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep_institucional(); /// monto total ejecutado poa
    }
    else{ /// Regional
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_regional_pi_aprobados($dep_id,1); /// monto total asignado poa
      $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep_regional($dep_id); /// monto total ejecutado poa
    }

    $cumplimiento_trimestral=0;
    if(count($total_ppto_asignado)!=0 & count($total_ppto_ejecutado)!=0){
      $cumplimiento_trimestral=round((($total_ppto_ejecutado[0]['ejecutado_total']/$total_ppto_asignado[0]['asignado']))*100,2);
    }

    if($tp==0){ /// vista
      return $this->parametro_calificacion($cumplimiento_trimestral,2);
    }
    else{
      return $cumplimiento_trimestral;
    }
  }


  public function parametro_calificacion($cumplimiento,$tipo){
    $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
    // tp 1 : trimestral
    // tp 2 : gestion
    $det=' al '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion;
    if($tipo==2){
      $det=' GESTIÓN '.$this->gestion;
    }

    $titulo='';$tp='';
    if($cumplimiento<=50){$tp='danger';$titulo=$cumplimiento.'% (INSATISFACTORIO)';} /// Insatisfactorio - Rojo
    if($cumplimiento > 50 & $cumplimiento <= 75){$tp='warning';$titulo=$cumplimiento.'% (REGULAR)';} /// Regular - Amarillo
    if($cumplimiento > 75 & $cumplimiento <= 99){$tp='info';$titulo=$cumplimiento.'% (BUENO))';} /// Bueno - Azul
    if($cumplimiento > 99 & $cumplimiento <= 101){$tp='success';$titulo=$cumplimiento.'% (OPTIMO)';} /// Optimo - verde

    $tabla='
      <hr>
      <div class="alert alert-'.$tp.'" role="alert" align="center">
        EJECUCIÓN FINANCIERA'.$det.'<br><div style="font-size:28px;"><b>'.$titulo.'</b></div>
      </div>';

    return $tabla;
  }


  /*------- TITULO --------*/
  public function formulario(){
    $regional=$this->model_proyecto->get_departamento($this->dep_id);
    $meses = $this->model_configuracion->get_mes();
    $tabla='';
    $tabla.='
    
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
    <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">';
    
/*    $tabla.='
    <article class="col-sm-12">
      <div class="well">
        <form class="smart-form">
          <header><h2><b>FORMULARIO DE EJECUCI&Oacute;N PRESUPUESTARIA - </b> '.strtoupper($regional[0]['dep_departamento']).' '.$this->verif_mes[2].' / '.$this->gestion.'</h2></header>
          <fieldset>
            <section>
              <label class="label">Selecciones MES / '.$this->gestion.'</label>
               <select class="form-control" style="width:20%;" name="mes_id" id="mes_id">';
               if($this->gestion==2022){
                foreach($meses as $row){
                  if($row['m_id']<=12){
                    if($row['m_id']==$this->verif_mes[1]){
                      $tabla.='<option value="'.$row['m_id'].'" selected>'.$row['m_descripcion'].'</option>';
                    }
                    else{
                      $tabla.='<option value="'.$row['m_id'].'">'.$row['m_descripcion'].'</option>';
                    }
                  }
                 }
               }
               else{
                foreach($meses as $row){
                  if($row['m_id']<=$this->verif_mes[1]){
                    if($row['m_id']==$this->verif_mes[1]){
                      $tabla.='<option value="'.$row['m_id'].'" selected>'.$row['m_descripcion'].'</option>';
                    }
                    else{
                      $tabla.='<option value="'.$row['m_id'].'">'.$row['m_descripcion'].'</option>';
                    }
                  }
                 }
               }
               
               $tabla.='
                </select> <i></i> </label>
            </section>
          </fieldset>
        </form>
          <div align=center id=load></div>
        </div>
      </article>';*/

      $tabla.='
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-darken" >
          <header>
              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
              <h2 class="font-md"><strong>MIS PROYECTOS DE INVERSI&Oacute;N '.$this->gestion.'</strong></h2>  
          </header>
          <div>
            <div class="widget-body no-padding">
            '.$this->lista_proyectos($this->dep_id).'
            </div>
          </div>
        </div>
      </article>';
    return $tabla;
  } 

  /*-- LISTA DE PROYECTOS DE INVERSION APROBADOS --*/
  public function lista_proyectos($dep_id){
    if($this->tp_adm==1){
      $proyectos=$this->model_proyecto->list_proy_inversion();
    }
    else{
      $proyectos=$this->model_proyecto->list_proy_inversion_regional($this->dep_id);
    }

    $tabla='';
    $tabla.='
      '.$this->style().'
      <table id="dt_basic" class="table table-bordered" style="width:100%;">
        <thead>
          <tr>
            <th style="width:1%; height:40px;" bgcolor="#fafafa">#</th>
            <th style="width:2%;" bgcolor="#fafafa" title="ACTUALIZAR INFORMACION">FORMULARIO<br>EJECUCIÓN '.strtoupper($this->verif_mes[2]).'</th>
            <th style="width:2%;" bgcolor="#fafafa" title="FICHA TECNICA">FICHA TÉCNICA</th>
            <th style="width:2%;" bgcolor="#fafafa" title="POA">POA '.$this->gestion.'</th>
            <th style="width:7%;" bgcolor="#fafafa" title="">REGIONAL</th>
            <th style="width:7%;" bgcolor="#fafafa" title="">DISTRITAL</th>
            <th style="width:10%;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
            <th style="width:10%;" bgcolor="#fafafa" title="CODIGO SISIN">CODIGO SISIN</th>
            <th style="width:20%;" bgcolor="#fafafa" title="PROYECTO">PROYECTO DE INVERSI&Oacute;N</th>
            <th style="width:10%;" bgcolor="#fafafa" title="SISIN">COSTO TOTAL DE PROYECTO</th>
            <th style="width:10%;" bgcolor="#fafafa" title="FASE">FASE DEL PROYECTO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="FASE">AVANCE FÍSICO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="FASE">AVANCE FINANCIERO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="">VER ARCHIVOS ADJUNTOS</th>
            <th style="width:5%;" bgcolor="#fafafa" title="">VER AVANCES DEL PROYECTO</th>
            <th style="width:5%;" bgcolor="#fafafa" title=""></th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($proyectos as $row){
          $componentes=$this->model_componente->lista_subactividad($row['proy_id']);
          $img=$this->model_proyecto->get_img_ficha_tecnica($row['proy_id']);
          $bgcolor='';
          if(count($img)!=0){
            $bgcolor='#eff5f4';
          }
          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.'>
            <td style="width:1%; text-align:center" title='.$row['proy_id'].'>'.$nro.'</td>
            <td align=center bgcolor="#deebfb">';
            foreach($componentes as $rowc){
              if(count($this->model_producto->list_prod($rowc['com_id']))!=0){
                $tabla.='
                  <a href="'.site_url("").'/form_ejec_pinversion/'.$rowc['com_id'].'" id="myBtn'.$rowc['com_id'].'" class="btn btn-default" title="REALIZAR REGISTRO DE EJECUCION">
                     <img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="40" HEIGHT="40"/></a>
                  </a>';
                }
              }
            /*$tabla.='
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_ppto_pi" class="btn btn-default ejec_ppto_pi" name="'.$row['proy_id'].'" title="REGISTRO DE EJECUCION PRESUPUESTARIA"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="40" HEIGHT="40"/></a>
            </td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_fotos" class="btn btn-default fotos_pi" name="'.$row['proy_id'].'" id="'.$row['proyecto'].'" title="SUBIR AVANCES DEL PROYECTO"><img src="'.base_url().'assets/ifinal/subir_img.jpg" WIDTH="40" HEIGHT="40"/></a>
            </td>';*/

            $tabla.='
            </td>
            <td style="width:5%; text-align:center">
              <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$row['proy_id'].'\');" class="btn btn-default" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="35" HEIGHT="35"/></a>
            </td>
            <td style="width:5%; text-align:center">
              <a href="javascript:abreVentana(\''.site_url("").'/prog/reporte_form4_consolidado/'.$row['proy_id'].'\');" class="btn btn-default" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="35" HEIGHT="35"/></a>
            </td>
            <td>'.strtoupper($row['dep_departamento']).'</td>
            <td>'.strtoupper($row['dist_distrital']).'</td>
            <td style="text-align:center">'.$row['prog'].' '.$row['proy'].' 000</td>
            <td style="text-align:center">'.$row['proy'].'</td>
            <td>'.strtoupper($row['proyecto']).'</td>
            <td style="text-align:right">Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
            <td>'.strtoupper($row['pfec_descripcion']).'</td>
            <td style="text-align:right"><b>'.round($row['avance_fisico'],2).' %</b></td>
            <td style="text-align:right"><b>'.round($row['avance_financiero'],2).' %</b></td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_archivos" class="btn btn-default lista_archivos_adjuntos" name="'.$row['proy_id'].'" "title="VER ARCHIVOS ADJUNTOS A LA EJECUCION"><img src="'.base_url().'assets/ifinal/doc.JPG" WIDTH="40" HEIGHT="40"/></a>
            </td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_imagenes" class="btn btn-default lista_img_pi" name="'.$row['proy_id'].'" "title="AVANCES DEL PROYECTOS (IMAGENES)"><img src="'.base_url().'assets/img/folder3.png" WIDTH="40" HEIGHT="40"/></a>
            </td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_ejec_ppto" class="btn btn-default lista_ppto_pi" name="'.$row['proy_id'].'" "title="EJECUCION PRESUPUESTARIA"><img src="'.base_url().'assets/ifinal/grafico2.png" WIDTH="40" HEIGHT="40"/></a>
            </td>
          </tr>';
          }
      $tabla.='
          </tbody>
        </table>';

    return $tabla;
  }


  /*---- AVANCE FINANCIERA (%) PROY INV ----*/
  public function avance_financiero_pi($aper_id,$ppto_total){
    /// --- monto total ejecutado del proyecto
      $monto_ejec_total=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep($aper_id); // suma monto ejecutado de las partidas del proyecto
      $ppto_ejec_total=0;

      if(count($monto_ejec_total)!=0){
        $ppto_ejec_total=$monto_ejec_total[0]['ejecutado_total'];
      }

      ///---- Calculando Avance financiero
      $avance_fin_total=0;
      if($ppto_total!=0){
        $avance_fin_total=round(($ppto_ejec_total/$ppto_total)*100,2);
      }

      $result[1]=$ppto_ejec_total; /// Ejecutado total Gestion PI
      $result[2]=$avance_fin_total; // Avance Financiero de la Gestion con respecto al Total de Proyecto

    return $result;
  }


  ////// REPORTES

  /*---- LISTA DE OPCIONES REPORTES ----*/
  public function listado_opciones_reportes($dep_id){
  $tabla='';
    $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <input name="dep_id" id="dep_id" type="hidden" value="'.$dep_id.'">
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <header><b>EJECUCION PRESUPUESTARIA '.$this->verif_mes[2].' / '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-3">
                    <label class="label"><b>TIPO DE REPORTE</b></label>
                    <select class="form-control" id="rep_id" name="rep_id" title="SELECCIONE OPCION DE REPORTE">
                      <option value="0">Seleccione Opción ....</option>
                      <option value="1">1.- MIS PROYECTOS DE INVERSION.</option>
                      <option value="2">2.- EJECUCIÓN FISICO Y FINANCIERO.</option>
                      <option value="3">3.- DETALLE EJECUCIÓN FISICO Y FINANCIERO.</option>
                    </select>
                  </section>
                </div>
              </fieldset>
          </form>
        </div>
      </article>';
    return $tabla;
  }

  /*--- GET MATRIZ DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL ---*/
  public function matriz_detalle_proyectos_clasificado_regional(){
    $regionales=$this->model_ptto_sigep->list_regionales();
    $nro_proyectos_nacional=count($this->model_proyecto->list_proy_inversion()); /// Nro de proyectos aprobados NACIONAL
    $ppto_asignado_proyectos_nacional=$this->model_ptto_sigep->get_ppto_asignado_proyectos_inversion_aprobados(); /// Ppto Asignado de proyectos aprobados NACIONAL
    
    //// Armando Matriz Vacio
    for ($i=0; $i <=10 ; $i++) { 
      for ($j=0; $j <count($this->model_ptto_sigep->list_regionales()) ; $j++) { 
        $matriz[$i][$j]=0;
      }
    }
    //// -------------------


    $nro=0;
    foreach($regionales as $row){
      $nro_proy=0;
      if(count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']))!=0){
        $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']));
      }

      $modificacion_partida=$this->detalle_modificacion_ppto_x_regional($row['dep_id']); //// Modificacion de partidas

      $ejecucion=$this->model_ptto_sigep->get_ppto_ejecutado_regional($row['dep_id']); //// ejecucion de Presupuesto
      $ejec_ppto=0;
      if(count($ejecucion)!=0){
        $ejec_ppto=$ejecucion[0]['ejecutado_total'];
      }

      $avance_financiero=0; /// Avance Financiero
      if($modificacion_partida[3]!=0){
        $avance_financiero=round((($ejec_ppto/$modificacion_partida[3])*100),2);
      }
      
      $porcentaje_distribucion_proyectos=0; //// numero de proyectos
      if($nro_proyectos_nacional!=0){
        $porcentaje_distribucion_proyectos=round(($nro_proy/$nro_proyectos_nacional)*100,2);
      }


      $porcentaje_distribucion_ppto=0; /// porcentaje de ppto asignado por regional
      if(count($ppto_asignado_proyectos_nacional)!=0){
        $porcentaje_distribucion_ppto=round(($modificacion_partida[3]/$ppto_asignado_proyectos_nacional[0]['ppto_asignado_gestion'])*100,2);
      }

      $matriz[$nro][0]=$row['dep_id']; //// dep id
      $matriz[$nro][1]=strtoupper($row['dep_departamento']); /// regional
      $matriz[$nro][2]=$row['dep_sigla']; /// regional
      $matriz[$nro][3]=$nro_proy; /// nro de proyectos aprobados
      $matriz[$nro][4]=$porcentaje_distribucion_proyectos; /// porcentaje de distribucion de proyectos con respecto al total
      $matriz[$nro][5]=$modificacion_partida[1]; /// ppto inicial
      $matriz[$nro][6]=$modificacion_partida[2]; /// ppto modificado
      $matriz[$nro][7]=$modificacion_partida[3]; /// ppto vigente
      $matriz[$nro][8]=$ejec_ppto; /// ppto ejecutado
      $matriz[$nro][9]=$avance_financiero; /// avance financiero Gestion
      $matriz[$nro][10]=$porcentaje_distribucion_ppto; /// porcentaje de distribucion del presupuesto con respecto al total

      $nro++;
    }

    return $matriz;
  }

 /*--- MATRIZ PROYECTOS DE INVERSION PARA GRAFICO DE AVANCE ---*/
  public function matriz_proyectos_inversion_regional($dep_id){
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $nro=0;
    foreach($proyectos as $row){
      $modificacion_partida=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);
      $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
      
      $datos[$nro][0]=$row['dep_id'];
      $datos[$nro][1]=$row['dist_id'];
      $datos[$nro][2]=$row['proy_id'];
      $datos[$nro][3]=$row['aper_id'];
      $datos[$nro][4]=$row['dep_departamento'];
      $datos[$nro][5]=$row['dist_distrital'];
      $datos[$nro][6]=$row['abrev'];
      $datos[$nro][7]=$row['prog'];
      $datos[$nro][8]=$row['proy'];
      $datos[$nro][9]=$row['act'];
      $datos[$nro][10]=$row['proyecto'];
      $datos[$nro][11]=$modificacion_partida[1];
      $datos[$nro][12]=$modificacion_partida[2];
      $datos[$nro][13]=$modificacion_partida[3];
      $datos[$nro][14]=$ejec_fin[1];
      $datos[$nro][15]=0;
      if($datos[$nro][13]!=0){
        $datos[$nro][15]=round((($datos[$nro][14]/$datos[$nro][13])*100),2);
      }
      
      $nro++;
    }

    return $datos;
  }


  /*--- GET TABLA DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL IMPRESION ---*/
  public function tabla_detalle_proyectos_impresion($matriz,$nro,$tipo){
    /// tipo : 0 vista normal
    /// tipo : 1 vista para impresion

    $tabla='';
    if($tipo==0){
      $tab='class="table table-bordered" style="width:80%;"';
    }
    else{
      $tab='class="change_order_items" border=1 style="width:90%;"';
    }

    $tabla.='
      <center>
      <table '.$tab.'>
        <thead>
          <tr>
            <th style="width:2%;">#</th>
            <th style="width:10%;">CODIGO SISIN</th>
            <th style="width:40%;">PROYECTO DE INVERSIÓN</th>
            <th style="width:10%;">PRESUPUESTO<br>ASIGNADO '.$this->gestion.'</th>
            <th style="width:10%;">PRESUPUESTO<br>EJECUTADO '.$this->gestion.'</th>
            <th style="width:10%;">(%) EJECUCIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_asignado=0;
        $ppto_ejec=0;
        $nro_proy=0;
          for ($i=0; $i <$nro ; $i++) { 
            $nro_proy++;
            $tabla.='
              <tr>
                <td style="width:2%;" align=center>'.$nro_proy.'</td>
                <td style="width:10%;" align=center><b>'.$matriz[$i][8].'</b></td>
                <td style="width:40%;"><b>'.$matriz[$i][10].'</b></td>
                <td style="width:10%;" align=right>Bs. '.number_format($matriz[$i][13], 2, ',', '.').'</td>
                <td style="width:10%;" align=right>Bs. '.number_format($matriz[$i][14], 2, ',', '.').'</td>
                <td style="width:10%; font-size:12px" align=right><b>'.$matriz[$i][15].'%</b></td>
              </tr>';
              $ppto_asignado=$ppto_asignado+$matriz[$i][13];
              $ppto_ejec=$ppto_ejec+$matriz[$i][14];
          }
          $cum=0;
          if($ppto_asignado!=0){
            $cum=round((($ppto_ejec/$ppto_asignado)*100),2);
          }

          $tabla.='
        </tbody>
          <tr>
            <td colspan=3></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_asignado, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_ejec, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>'.$cum.' %</b></td>
          </tr>
      </table>
      </center>';

    return $tabla;
  } 



  /*---- REPORTE 1 LISTA DE PROYECTOS DE INVERSION ----*/
  public function proyectos_inversion($dep_id,$tp_rep){
    //$proyectos=$this->model_proyecto->list_pinversion(1,4);
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    $tabla.='
    <div class="table-responsive" align=center>
      <form class="smart-form" method="post">
        <table class="table table-bordered" style="width:80%;" id="datos">
          <thead>
            <tr>
              <th style="width:1%; font-size: 10px; text-align:center"><b>#</b></th>
              <th style="width:2%; font-size: 10px; text-align:center"></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:7%; font-size: 10px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
              <th style="width:25%; font-size: 10px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>COSTO TOTAL DEL PROYECTO (Bs.)</b></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
              <tr>
                <td align=center>'.$nro.'</td>
                <td align=center>
                  <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$row['proy_id'].'\');" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                </td>
                <td>'.strtoupper($row['dep_departamento']).'</td>
                <td>'.strtoupper($row['dist_distrital']).'</td>
                <td>'.$row['proy'].'</td>
                <td>'.$row['prog'].' '.$row['proy'].' 000</td>
                <td>'.$row['proyecto'].'</td>
                <td align=right><b>'.number_format($row['proy_ppto_total'], 2, ',', '.').'</b></td>
              </tr>';
            }
          $tabla.='
          </tbody>
        </table>
      </form>
    </div>';

    return $tabla;
  }


  /*------- DETALLE PI EXCEL--------*/
  public function reporte1_pdf_excel($dep_id,$tipo_reporte){
    /// tipo_reporte : 0 pdf
    /// tipo_reporte : 1 excel
    //$proyectos=$this->model_proyecto->list_pinversion(1,4);
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';
    if($tipo_reporte==0){
      $tabla.='
        <table border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla">
          <thead>
            <tr bgcolor="#f4f4f4">
              <th style="width:1%; font-size: 12px; text-align:center;height:60px"><b>#</b></th>
              <th style="width:3%; font-size: 12px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
              <th style="width:15%; font-size: 12px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>COSTO TOTAL<br>PROYECTO (Bs.)</b></th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
              <tr>
                <td align=center>'.$nro.'</td>
                <td style="font-size: 12px;font-family: Arial;height:50px">'.strtoupper($row['dep_departamento']).'</td>
                <td style="font-size: 12px;font-family: Arial;">'.strtoupper($row['dist_distrital']).'</td>
                <td style="font-size: 12px;font-family: Arial;">'.$row['proy'].'</td>
                <td style="font-size: 12px;font-family: Arial;">\''.$row['prog'].' '.$row['proy'].' 000\'</td>
                <td style="font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                <td style="font-size: 12px;font-family: Arial;" align=right><b>'.round($row['proy_ppto_total'],2).'</b></td>
              </tr>';
            }
      $tabla.='
        </tbody>
      </table>';
    }
    else{
      $tabla.='
        <div style="font-size: 10px; height:20px;">MIS PROYECTOS DE INVERSION</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:90%;" align=center>
          <thead>
            <tr  bgcolor="#e8e7e7" align=center>
              <th style="width:10%;height:15px;">REGIONAL</th>
              <th style="width:15%;">DISTRITAL</th>
              <th style="width:15%;">CÓDIGO SISIN</th>
              <th style="width:10%;">CATEGORIA PROGRAMATICA '.$this->gestion.'</th>
              <th style="width:45%;">PROYECTO DE INVERSIÓN</th>
              <th style="width:10%;">COSTO TOTAL PROYECTO</th>
            </tr>
          </thead>
          <tbody>';
          foreach($proyectos as $row){
            $tabla.='
            <tr>
              <td style="font-size: 7px; height:12px;width:10%;">'.strtoupper($row['dep_departamento']).'</td>
              <td style="width:15%;">'.strtoupper($row['dist_distrital']).'</td>
              <td style="width:15%;">'.$row['proy'].'</td>
              <td style="width:10%;">'.$row['prog'].' '.$row['proy'].' 000</td>
              <td style="width:45%;">'.strtoupper($row['proyecto']).'</td>
              <td style="width:10%;" align=right>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
            </tr>';
          }
      $tabla.='
          </tbody>
        </table>';
    }

     
    return $tabla;
  }



 /*------- REPORTE 2 EJECUCION FISICA Y FINANCIERA (VISTA) --------*/
  public function avance_fisico_financiero_pi($dep_id){
    //$proyectos=$this->model_proyecto->list_pinversion(1,4);
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    $tabla.='
      <div class="table-responsive" align=center>
        <form class="smart-form" method="post">
          <table class="table table-bordered" style="width:90%;" id="datos">
            <thead>
              <tr>
                <th style="width:1%; font-size: 10px; text-align:center"><b>#</b></th>
                <th style="width:2%; font-size: 10px; text-align:center"></th>
                <th style="width:5%; font-size: 10px; text-align:center"><b>REGIONAL</b></th>
                <th style="width:7%; font-size: 10px; text-align:center"><b>DISTRITAL</b></th>
                <th style="width:7%; font-size: 10px; text-align:center"><b>CODIGO SISIN</b></th>
                <th style="width:7%; font-size: 10px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
                <th style="width:20%; font-size: 10px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
                <th style="width:5%; font-size: 10px; text-align:center"><b>COSTO TOTAL DEL PROYECTO (Bs.)</b></th>
                <th style="width:5%; font-size: 10px; text-align:center"><b>ESTADO DEL PROYECTO</b></th>
                <th style="width:5%; font-size: 10px; text-align:center"><b>EJECUCIÓN FIS. TOTAL</b></th>
                <th style="width:5%; font-size: 10px; text-align:center"><b>EJECUCIÓN FIN. TOTAL</b></th>
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($proyectos as $row){
                $nro++;
                $tabla.='
                <tr>
                  <td align=center>'.$nro.'</td>
                  <td align=center>
                    <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$row['proy_id'].'\');" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                  </td>
                  <td>'.strtoupper($row['dep_departamento']).'</td>
                  <td>'.strtoupper($row['dist_distrital']).'</td>
                  <td>'.$row['proy'].'</td>
                  <td>'.$row['prog'].' '.$row['proy'].' 000</td>
                  <td>'.$row['proyecto'].'</td>
                  <td align=right><b>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</b></td>
                  <td>'.strtoupper($row['ep_descripcion']).'</td>
                  <td align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
                  <td align=right><b>'.round($row['avance_financiero'],2).' % %</b></td>
                </tr>';
              }
            $tabla.='
            </tbody>
          </table>
        </form>
      </div>';

    return $tabla;
  }


  /*------- (excel) DETALLE EJECUCION PI EXCEL (2)--------*/
  public function reporte2_pdf_excel($dep_id,$tipo_reporte){
    /// tipo_reporte : 1 pdf
    /// tipo_reporte : 0 excel

    //$proyectos=$this->model_proyecto->list_pinversion(1,4);
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';
    if($tipo_reporte==0){
       $tabla.='
        <table border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla">
          <thead>
            <tr bgcolor="#f4f4f4">
              <th style="width:1%; font-size: 12px; text-align:center;height:60px"><b>#</b></th>
              <th style="width:3%; font-size: 12px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
              <th style="width:15%; font-size: 12px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>COSTO TOTAL<br>PROYECTO (Bs.)</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>ESTADO DEL PROYECTO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>EJECUCIÓN FIS. TOTAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>EJECUCIÓN FIN. TOTAL</b></th>
            </tr>
          </thead>
          <tbody>';
          $nro=0;
            foreach($proyectos as $row){
             // $ejec_fin=$this->get_ejec_ppto_proyecto_gestion($this->model_proyecto->get_id_proyecto($row['proy_id']));
            //  $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']);
              $nro++;
              $tabla.='
              <tr>
                <td align=center>'.$nro.'</td>
                <td style="font-size: 12px;font-family: Arial;height:50px">'.strtoupper($row['dep_departamento']).'</td>
                <td style="font-size: 12px;font-family: Arial;">'.strtoupper($row['dist_distrital']).'</td>
                <td style="font-size: 12px;font-family: Arial;">'.$row['proy'].'</td>
                <td style="font-size: 12px;font-family: Arial;">\''.$row['prog'].' '.$row['proy'].' 000\'</td>
                <td style="font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                <td style="font-size: 12px;font-family: Arial;" align=right><b>'.round($row['proy_ppto_total'],2).'</b></td>
                <td>'.mb_convert_encoding(strtoupper($row['ep_descripcion']), 'cp1252', 'UTF-8').'</td>
                <td align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
                <td align=right><b>'.round($row['avance_financiero'],2).' %</b></td>
              </tr>';
            }
      $tabla.='
          </tbody>
        </table>';
    }
    else{
      $tabla.='
        <div style="font-size: 10px; height:20px;">DETALLE EJECUCIÓN FISICO FINANCIERO</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:95%;" align=center>
          <thead>
            <tr bgcolor="#e8e7e7" align=center>
              <th style="width:12%;">DISTRITAL</th>
              <th style="width:10%;">CÓDIGO SISIN</th>
              <th style="width:10%;">CATEGORIA PROGRAMATICA '.$this->gestion.'</th>
              <th style="width:40%;">PROYECTO DE INVERSIÓN</th>
              <th style="width:10%;">COSTO TOTAL PROYECTO</th>
              <th style="width:12%;">ESTADO DEL PROYECTO</th>
              <th style="width:8%;">EJEC. FÍSICA</th>
              <th style="width:8%;">ÉJEC. FINANCIERA</th>
            </tr>
          </thead>
          <tbody>';
          foreach($proyectos as $row){
           // $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']);
            $tabla.='
            <tr>
              <td style="font-size: 7px; height:12px;width:12%;">'.strtoupper($row['dist_distrital']).'</td>
              <td style="width:10%;">'.$row['proy'].'</td>
              <td style="width:10%;">'.$row['prog'].' '.$row['proy'].' 000</td>
              <td style="width:40%;">'.strtoupper($row['proyecto']).'</td>
              <td style="width:10%;" align=right>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
              <td style="width:12%;">'.strtoupper($row['ep_descripcion']).'</td>
              <td style="width:8%;" align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
              <td style="width:8%;" align=right><b>'.round($row['avance_financiero'],2).' %</b></td>
            </tr>';
          }
      $tabla.='
          </tbody>
        </table>';
    }

    return $tabla;
  }


 /*--- REPORTE 3 DETALLE POR PARTIDA EJECUCION FISICA Y FINANCIERA ---*/
  public function detalle_avance_fisico_financiero_pi($dep_id){
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    $tabla.='
      <div class="table-responsive" align=center>
        <table class="table table-bordered" style="width:90%;" id="datos">
          <thead>
            <tr>
              <th style="width:1%; font-size: 10px; text-align:center"><b>#</b></th>
              <th style="width:2%; font-size: 10px; text-align:center"></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>COSTO TOTAL DEL PROYECTO (Bs.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ESTADO DEL PROYECTO</b></th>
              <th style="width:2%; font-size: 10px; text-align:center"><b>PARTIDA</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>PPTO. INICIAL '.$this->gestion.'</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>PPTO. MOD. '.$this->gestion.'</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>PPTO. VIGENTE '.$this->gestion.'</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>PPTO. EJECUTADO '.$this->gestion.'</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>(%) EJECUTADO '.$this->gestion.'</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>EJEC. FIS. TOTAL</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>EJEC. FIN. TOTAL</b></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
              //$fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
              $modificacion_partida=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

              $nro++;
              $tabla.='
              <tr bgcolor="#e7e741">
                <td align=center>'.$nro.'</td>
                <td align=center>
                  <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$row['proy_id'].'\');" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                </td>
                <td>'.strtoupper($row['dep_departamento']).'</td>
                <td>'.strtoupper($row['dist_distrital']).'</td>
                <td>'.$row['proy'].'</td>
                <td>'.$row['prog'].' '.$row['proy'].' 000</td>
                <td>'.$row['proyecto'].'</td>
                <td align=right><b>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</b></td>
                <td>'.strtoupper($row['ep_descripcion']).'</td>
                <td></td>
                <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($modificacion_partida[1], 2, ',', '.').'</td>
                <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($modificacion_partida[2], 2, ',', '.').'</td>
                <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($modificacion_partida[3], 2, ',', '.').'</td>
                <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($ejec_fin[1], 2, ',', '.').'</td>
                <td style="font-size: 20px;font-family: Arial; color:green" align=right><b>'.round(($ejec_fin[1]/$modificacion_partida[3])*100,2).' %</b></td>
                <td style="font-size: 11px;font-family: Arial;" align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
                <td style="font-size: 11px;font-family: Arial;" align=right><b>'.round($row['avance_financiero'],2).' %</b></td>
              </tr>';
              $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']); /// lista de partidas asignados por proyectos
              foreach($ppto_asig as $partida){
                  /// ------ Datos de Modifcacion de la partida
                  $monto_partida=$this->detalle_modificacion_partida($partida);

                  /// montos ejecutados por partidas
                  $monto_total_ejecutado=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($partida['sp_id']); /// monto total ejecutado
                  $monto_ejecutado=0;
                  if(count($monto_total_ejecutado)!=0){
                    $monto_ejecutado=$monto_total_ejecutado[0]['ejecutado'];
                  }

                  /// Porcentaje de Avance por partidas
                  $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($partida['sp_id']); /// Get partida sigep
                  $porcentaje_avance_fin=0;
                  if(count($get_partida_sigep)!=0 & $get_partida_sigep[0]['importe']!=0){
                    $porcentaje_avance_fin=round((($monto_ejecutado/$get_partida_sigep[0]['importe'])*100),2);
                  }

                $tabla.='
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-size: 14px;font-family: Arial;" align=center><b>'.$partida['partida'].'</b></td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[1], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[2], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[3], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right><b>Bs. '.number_format($monto_ejecutado, 2, ',', '.').'</b></td>
                  <td style="font-size: 14px;font-family: Arial; color:blue" align=right><b>'.round(($monto_ejecutado/$monto_partida[3])*100,2).' %</b></td>
                  <td></td>
                  <td style="font-size: 11px;font-family: Arial;" align=right><b>'.$porcentaje_avance_fin.' %</b></td>
                </tr>';
              }
            }
          $tabla.='
          </tbody>
        </table>
      </div>';

    return $tabla;
  }


 /*------- REPORTE 3 DETALLE POR PARTIDA EJECUCION FISICA Y FINANCIERA A DETALLE --------*/
  public function reporte3_pdf_excel($dep_id,$tipo_reporte){
    /// tipo_reporte : 1 pdf
    /// tipo_reporte : 0 excel
    $calificacion=$this->cumplimiento_trimestre($dep_id,0); /// % CUMPLIMIENTO
    if($dep_id==0){ /// institucional
      $proyectos=$this->model_proyecto->list_proy_inversion();
    }
    else{ /// Regional
      $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);
    }
    
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    if($tipo_reporte==0){ /// excel

      $tabla.='
        <table border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla">
          <thead>
            <tr bgcolor="#f4f4f4">
              <td style="width:40%; font-size: 15px; text-align:center;height:50px" colspan=11><b>PROYECTO DE INVERSION</b></td>
              <td style="width:60%; font-size: 15px; text-align:center;" colspan=40><b>PROGRAMACION FISICO Y EJECUCION FINANCIERA</b></td>
            </tr>
            <tr bgcolor="#f4f4f4" border=1>
              <th style="width:1%; font-size: 10px; text-align:center;height:60px"><b>#</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>PROYECTO DE INVERSION</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>ESTADO<br>PROYECTO</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>FASE</b></th>
              <th style="width:2%; font-size: 10px; text-align:center"><b>PARTIDAS ASIGNADAS '.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. INICIAL <br>'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. MOD. <br>'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. VIGENTE <br>'.$this->gestion.'</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. EJECUTADO '.$this->verif_mes[2].'/'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>(%) CUMPLIMIENTO '.$this->verif_mes[2].'/'.$this->gestion.'</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CALIFICACION</b></th>
            </tr>
          </thead>
          <tbody>';
           $nro=0;
            foreach($proyectos as $row){
              $modificaciones_ppto_proyecto=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

                ///----- ppto ejecutado al mes
                $get_ppto_ejecutado_al_mes_pi=$this->model_ptto_sigep->get_suma_ppto_ejecutado_al_mes_x_unidad($row['aper_id'],$this->verif_mes[1]);
                $ppto_ejec_pi=0;
                if(count($get_ppto_ejecutado_al_mes_pi)!=0){
                  $ppto_ejec_pi=$get_ppto_ejecutado_al_mes_pi[0]['ppto_ejec'];
                }
                ///--------------------------

                ///----- cumplimiento (%)
                $cumplimiento_pi=0;
                if($modificaciones_ppto_proyecto[3]!=0){
                  $cumplimiento_pi=round((($ppto_ejec_pi/$modificaciones_ppto_proyecto[3])*100),2);
                }
                ///----------------------

                $calificacion=$this->calificacion_de_cumplimiento($cumplimiento_pi);
              $nro++;
              $tabla.='
              <tr bgcolor="#e7e741">
                <td style="width:1%;font-size: 9px;font-family: Arial; height:50px; text-align:center" title='.$row['aper_id'].'>'.$nro.'</td>
                <td style="width:3%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['dist_distrital']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.$row['proy'].'</td>
                <td style="width:15%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['ep_descripcion']), 'cp1252', 'UTF-8').'</td>
                <td style="width:10%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['fas_fase']), 'cp1252', 'UTF-8').'</td>
                <td style="width:2%;font-size: 9px;font-family: Arial;"></td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[1],2).'</b></td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[2],2).'</b></td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[3],2).'</b></td>';
                for ($i=1; $i <=12 ; $i++) { 
                  $datos_pi=$this->get_distribucion_prog_ejec_unidad($i,$row['aper_id']);
                    $color='';
                    $cump_color='#ddeaf7';
                    if($i<=$this->verif_mes[1]){
                      $color='#bad8f5';
                      $cump_color='#7cb5ec';
                    }

                  $tabla.='
                    <td style="font-size: 9px;font-family: Arial;text-align:right">'.round($datos_pi[1],2).'</td>
                    <td style="font-size: 9px;font-family: Arial;text-align:right">'.round($datos_pi[2],2).'</td>
                    <td style="font-size: 12px;font-family: Arial;text-align:right"><b>'.round($datos_pi[3],2).' %</b></td>';
                }
                $tabla.='
                <td style="font-size: 10px;font-family: Arial; text-align:right"><b>'.round($ppto_ejec_pi,2).'</b></td>
                <td style="font-size: 15px;font-family: Arial; text-align:right"><b>'.$cumplimiento_pi.' %</b></td>
                <td>'.$calificacion.'</td>
              </tr>';

              $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']); /// lista de partidas asignados por proyectos
              foreach($ppto_asig as $partida){
                $monto_partida=$this->detalle_modificacion_partida($partida); /// detalle me modificacion ppto
                
                ///----- ppto ejecutado al mes
                $get_ppto_ejecutado_al_mes=$this->model_ptto_sigep->get_suma_ppto_ejecutado_al_mes_x_partida_unidad($row['aper_id'],$partida['par_id'],$this->verif_mes[1]);
                $ppto_ejec=0;
                if(count($get_ppto_ejecutado_al_mes)!=0){
                  $ppto_ejec=$get_ppto_ejecutado_al_mes[0]['ppto_ejec'];
                }
                ///--------------------------

                ///----- cumplimiento (%)
                $cumplimiento=0;
                if($monto_partida[3]!=0){
                  $cumplimiento=round((($ppto_ejec/$monto_partida[3])*100),2);
                }
                ///----------------------

                $tabla.='
                <tr>
                  <td style="font-size: 9px;font-family: Arial; height:50px"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 9px;font-family: Arial;"></td>
                  <td style="font-size: 15px;font-family: Arial;" bgcolor="#c4efe9" align=center title='.$partida['sp_id'].'><b>'.$partida['partida'].'</b></td>
                  <td style="font-size: 9px;font-family: Arial; text-align:right">'.round($monto_partida[1],2).'</td>
                  <td style="font-size: 9px;font-family: Arial; text-align:right">'.round($monto_partida[2],2).'</td>
                  <td style="font-size: 9px;font-family: Arial; text-align:right">'.round($monto_partida[3],2).'</td>';

                  for ($i=1; $i <=12 ; $i++) { 
                    $datos=$this->get_distribucion_prog_ejec_partida($i,$row['aper_id'],$partida['par_id']);
                    $color='';
                    $cump_color='#ddeaf7';
                    if($i<=$this->verif_mes[1]){
                      $color='#bad8f5';
                      $cump_color='#7cb5ec';
                    }


                    $tabla.='
                    <td style="font-size: 9px;font-family: Arial; text-align:right" bgcolor='.$color.'>'.round($datos[1],2).'</td>
                    <td style="font-size: 9px;font-family: Arial; text-align:right" bgcolor='.$color.'>'.round($datos[2],2).'</td>
                    <td style="font-size: 12px;font-family: Arial; text-align:right" bgcolor='.$cump_color.'><b>'.round($datos[3],2).' %</b></td>';
                  }
                  $tabla.='
                    <td style="font-size: 9px;font-family: Arial; text-align:right">'.round($ppto_ejec,2).'</td>
                    <td style="font-size: 12px;font-family: Arial; text-align:right"><b>'.$cumplimiento.' %</b></td>
                    <td></td>
                </tr>';
              }
            }
          $tabla.='
          </tbody>
        </table>';

    }
    else{ /// Pdf

      $tabla.='

        '.$calificacion.'<br>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
          <thead>
            <tr bgcolor="#e8e7e7" align=center>
              <th style="width:10%;">DISTRITAL</th>
              <th style="width:10%;">CÓDIGO SISIN</th>
              <th style="width:25%;">PROYECTO DE INVERSIÓN</th>
              <th style="width:8%;">PPTO. TOTAL PROYECTO</th>
              <th style="width:12%;">ESTADO DEL PROYECTO</th>
              <th style="width:7%;">PPTO. INICIAL</th>
              <th style="width:7%;">PPTO. MOD.</th>
              <th style="width:7%;">PPTO. VIGENTE</th>
              <th style="width:7%;">PPTO. EJECUTADO</th>
              <th style="width:7%;">(%) CUMP.</th>
            </tr>
          </thead>
          <tbody>';
          foreach($proyectos as $row){
            $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $modificacion_partida=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

            $cumplimiento=0;
            if($modificacion_partida[3]!=0){
              $cumplimiento=round((($ejec_fin[1]/$modificacion_partida[3])*100),2);
            }
            $tabla.='
            <tr>
              <td style="font-size: 7px; height:12px;width:10%;">'.strtoupper($row['dist_distrital']).'</td>
              <td style="width:10%;">'.$row['proy'].'</td>
              <td style="width:20%;">'.strtoupper($row['proyecto']).'</td>
              <td style="width:8%;" align=right>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
              <td style="width:10%;">'.strtoupper($row['ep_descripcion']).'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[1], 2, ',', '.').'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[2], 2, ',', '.').'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[3], 2, ',', '.').'</td>
              <td style="width:5%;" align=right>'.number_format($ejec_fin[1], 2, ',', '.').'</td>
              <td style="width:5%;" align=right>'.$cumplimiento.' %</td>
            </tr>';
          }
      $tabla.='
          </tbody>
        </table>';
    }

    return $tabla;
  }


  /*------ Distribucion MENSUAL Prog y ejec por partida de Unidad/Proyecto -----*/
  public function get_distribucion_prog_ejec_partida($mes_id,$aper_id,$par_id){
    for ($i=1; $i <=3; $i++) { 
      $datos[$i]=0;
    }
    $get_mes_prog_partida=$this->model_insumo->get_list_temporalidad_insumo_partida_mes_programado($aper_id,$par_id,$mes_id);
    $get_mes_ejec_partida=$this->model_ptto_sigep->get_list_temporalidad_insumo_partida_mes_ejecutado($aper_id,$par_id,$mes_id);

  
    if(count($get_mes_prog_partida)!=0){
      $datos[1]=$get_mes_prog_partida[0]['ppto'];
    }

    if(count($get_mes_ejec_partida)!=0){
      $datos[2]=$get_mes_ejec_partida[0]['ppto'];
    }
    

    if($datos[1]!=0){
      $datos[3]=round((($datos[2]/$datos[1])*100),2);
    }

    return $datos;
  }


  /*------ Distribucion TOTAL MENSUAL Prog y ejec por Unidad/Proyecto -----*/
  public function get_distribucion_prog_ejec_unidad($mes_id,$aper_id){
    for ($i=1; $i <=3; $i++) { 
      $datos[$i]=0;
    }
    $get_mes_prog_partida=$this->model_insumo->get_list_temporalidad_insumo_mes_programado($aper_id,$mes_id);
    $get_mes_ejec_partida=$this->model_ptto_sigep->get_list_temporalidad_insumo_mes_ejecutado($aper_id,$mes_id);

  
    if(count($get_mes_prog_partida)!=0){
      $datos[1]=$get_mes_prog_partida[0]['ppto'];
    }

    if(count($get_mes_ejec_partida)!=0){
      $datos[2]=$get_mes_ejec_partida[0]['ppto'];
    }
    

    if($datos[1]!=0){
      $datos[3]=round((($datos[2]/$datos[1])*100),2);
    }

    return $datos;
  }


  /*------ Calificacion Cumplimiento obtenido-----*/
  public function calificacion_de_cumplimiento($valor){
    $calificacion='';

      if($valor<=50){$calificacion='<div style="color:red"><b>INSATISFACTORIO</b></div>';} /// Insatisfactorio - Rojo (1)
      if($valor > 50 & $valor <= 75){$calificacion='<div style="color:yellow"><b>REGULAR</b></div>';} /// Regular - Amarillo (2)
      if($valor > 75 & $valor <= 99){$calificacion='<div style="color:blue"><b>BUENO</b></div>';} /// Bueno - Azul (3)
      if($valor > 99 & $valor <= 100){$calificacion='<div style="color:green"><b>OPTIMO</b></div>';} /// Optimo - verde (4)

    return $calificacion;
  }



  /*-------(excel) REPORTE 3 DETALLE POR PARTIDA EJECUCION FISICA Y FINANCIERA A RESUMEN --------*/
  public function reporte_consolidado_institucional_resumen(){
    $tabla='';
    $proyectos=$this->model_proyecto->list_proy_inversion();
        $tabla.='
        <table border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla">
          <thead>
            <tr bgcolor="#f4f4f4">
              <td style="width:39%; font-size: 15px; text-align:center;height:50px" colspan=11><b>PROYECTO DE INVERSION</b></td>
              <td style="width:60%; font-size: 15px; text-align:center;" colspan=40><b>PROGRAMACION FISICO Y EJECUCION FINANCIERA</b></td>
            </tr>
            <tr bgcolor="#f4f4f4" border=1>
              <th style="width:1%; font-size: 10px; text-align:center;height:60px"><b>#</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>PROYECTO DE INVERSION</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>ESTADO<br>PROYECTO</b></th>
              <th style="width:10%; font-size: 10px; text-align:center"><b>FASE</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. INICIAL <br>'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. MOD. <br>'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. VIGENTE <br>'.$this->gestion.'</b></th>

             <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ENE. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>FEB. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAR. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>ABR. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>MAY. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUN. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>JUL. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>AGO. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>SEP. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>OCT. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>NOV. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (Prog.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (Ejec.)</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>DIC. (%)</b></th>

              <th style="width:4%; font-size: 10px; text-align:center"><b>PPTO. EJECUTADO '.$this->verif_mes[2].'/'.$this->gestion.'</b></th>
              <th style="width:4%; font-size: 10px; text-align:center"><b>(%) CUMPLIMIENTO '.$this->verif_mes[2].'/'.$this->gestion.'</b></th>
              <th style="width:5%; font-size: 10px; text-align:center"><b>CALIFICACION</b></th>
            </tr>
          </thead>
          <tbody>';
           $nro=0;
             foreach($proyectos as $row){
              $modificaciones_ppto_proyecto=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

                ///----- ppto ejecutado al mes
                $get_ppto_ejecutado_al_mes_pi=$this->model_ptto_sigep->get_suma_ppto_ejecutado_al_mes_x_unidad($row['aper_id'],$this->verif_mes[1]);
                $ppto_ejec_pi=0;
                if(count($get_ppto_ejecutado_al_mes_pi)!=0){
                  $ppto_ejec_pi=$get_ppto_ejecutado_al_mes_pi[0]['ppto_ejec'];
                }
                ///--------------------------

                ///----- cumplimiento (%)
                $cumplimiento_pi=0;
                if($modificaciones_ppto_proyecto[3]!=0){
                  $cumplimiento_pi=round((($ppto_ejec_pi/$modificaciones_ppto_proyecto[3])*100),2);
                }
                ///----------------------

                $calificacion=$this->calificacion_de_cumplimiento($cumplimiento_pi);
              $nro++;
              $tabla.='
              <tr >
                <td style="width:1%;font-size: 9px;font-family: Arial; height:50px; text-align:center" title='.$row['aper_id'].'>'.$nro.'</td>
                <td style="width:3%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['dep_departamento']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['dist_distrital']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.$row['proy'].'</td>
                <td style="width:15%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['ep_descripcion']), 'cp1252', 'UTF-8').'</td>
                <td style="width:10%;font-size: 9px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['fas_fase']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[1],2).'</b></td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[2],2).'</b></td>
                <td style="width:5%;font-size: 9px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[3],2).'</b></td>';
                for ($i=1; $i <=12 ; $i++) { 
                  $datos_pi=$this->get_distribucion_prog_ejec_unidad($i,$row['aper_id']);
                    $color='';
                    $cump_color='#ddeaf7';
                    if($i<=$this->verif_mes[1]){
                      $color='#bad8f5';
                      $cump_color='#7cb5ec';
                    }

                  $tabla.='
                    <td style="font-size: 9px;font-family: Arial;text-align:right" bgcolor='.$color.'>'.round($datos_pi[1],2).'</td>
                    <td style="font-size: 9px;font-family: Arial;text-align:right" bgcolor='.$color.'>'.round($datos_pi[2],2).'</td>
                    <td style="font-size: 12px;font-family: Arial;text-align:right" bgcolor='.$cump_color.'><b>'.round($datos_pi[3],2).' %</b></td>';
                }
                $tabla.='
                <td style="font-size: 10px;font-family: Arial; text-align:right"><b>'.round($ppto_ejec_pi,2).'</b></td>
                <td style="font-size: 15px;font-family: Arial; text-align:right"><b>'.$cumplimiento_pi.' %</b></td>
                <td>'.$calificacion.'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
        </table>';
    return $tabla;
  }


  //// ======== CUADRO COMPARATIVO (INICIAL - MODIFICADO - EJECUTADO)
  /*-- MATRIZ PARA CUADRO CONSOLIDADO DE EJECUCION --*/
  public function matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep){

    /// Temporalidad Inicial Programado
    if(count($ppto_programado_poa_inicial)!=0){
     for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_ini[$i]=$ppto_programado_poa_inicial[0]['programado_total'];;
        }
        else{
          $vect_ini[$i]=round($ppto_programado_poa_inicial[0]['mes'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_ini[$i]=0;
        }
        else{
          $vect_ini[$i]=0;
        }
      }
    }

    /// Temporalidad Programado Ajustado (actual)
    if(count($ppto_programado_poa)!=0){
     for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_p[$i]=$ppto_programado_poa[0]['programado_total'];;
        }
        else{
          $vect_p[$i]=round($ppto_programado_poa[0]['mes'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect_p[$i]=0;
        }
        else{
          $vect_p[$i]=0;
        }
      }
    }

    /// Temporalidad Ejecutado
    if(count($ppto_ejecutado_sigep)!=0){
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect[$i]=$ppto_ejecutado_sigep[0]['ejecutado_total'];;
        }
        else{
          $vect[$i]=round($ppto_ejecutado_sigep[0]['m'.$i],2);
        }
      }
    }
    else{
      for ($i=0; $i <=12 ; $i++) { 
        if($i==0){
          $vect[$i]=0;
        }
        else{
          $vect[$i]=0;
        }
      }
    }


    for ($i=0; $i <=7; $i++) { 
      for ($j=0; $j <=12; $j++) { 
        $matriz[$i][$j]=0;
      }
    }


    $matriz[0][1]='ENE.';
    $matriz[0][2]='FEB.';
    $matriz[0][3]='MAR.';
    $matriz[0][4]='ABR.';
    $matriz[0][5]='MAY.';
    $matriz[0][6]='JUN.';
    $matriz[0][7]='JUL.';
    $matriz[0][8]='AGO.';
    $matriz[0][9]='SEPT.';
    $matriz[0][10]='OCT.';
    $matriz[0][11]='NOV.';
    $matriz[0][12]='DIC.';


    $suma_prog_inicial=0;
    $suma_prog=0;
    $suma_ejec=0;
    for ($j=0; $j <=12 ; $j++) { 

      if($j==0){
        $matriz[1][$j]=$vect_ini[$j]; // total prog inicial
        $matriz[2][$j]=0;
        $matriz[3][$j]=$vect_p[$j]; // total prog actual
        $matriz[4][$j]=0;
        $matriz[5][$j]=$vect[$j]; // total ejecutado
        $matriz[6][$j]=0;
        $matriz[7][$j]=0;
      }
      else{
        $suma_prog_inicial=$suma_prog_inicial+$vect_ini[$j];
        $suma_prog=$suma_prog+$vect_p[$j];
        $suma_ejec=$suma_ejec+$vect[$j]; 

        $matriz[1][$j]=$vect_ini[$j];
        $matriz[2][$j]=$suma_prog_inicial; /// acumulado ini

        $matriz[3][$j]=$vect_p[$j];
        $matriz[4][$j]=$suma_prog; /// acumulado prog

        $matriz[5][$j]=$vect[$j];
        $matriz[6][$j]=$suma_ejec; /// acumulado ejec
      }
       
      if($matriz[4][$j]!=0){
        $matriz[7][$j]=round(($matriz[6][$j]/$matriz[4][$j])*100,2);
      }
    }

    return $matriz;
  }


  /*-- TABLA PARA CUADRO CONSOLIDADO VISTA --*/
  public function tabla_consolidado_ejecucion_pinversion($matriz,$tp_rep){
    if($tp_rep==0){
      $style='class="table table-bordered"';
      $size='font-size: 11px';
    }
    else{
      $style='border="0.01" cellpadding="0" cellspacing="0" class="tabla"';
      $size='font-size: 8px';
    }

    $tabla='';
    $tabla.='
    <div align=center>
    <table '.$style.' style="width:98%;">
      <thead>
        <tr>
          <th style="color:black;height:15px; width:15%;"></th>
          <th style="color:black;width:7%;">ENE.</th>
          <th style="color:black;width:7%;">FEB.</th>
          <th style="color:black;width:7%;">MAR.</th>
          <th style="color:black;width:7%;">ABR.</th>
          <th style="color:black;width:7%;">MAY.</th>
          <th style="color:black;width:7%;">JUN.</th>
          <th style="color:black;width:7%;">JUL.</th>
          <th style="color:black;width:7%;">AGO.</th>
          <th style="color:black;width:7%;">SEPT.</th>
          <th style="color:black;width:7%;">OCT.</th>
          <th style="color:black;width:7%;">NOV.</th>
          <th style="color:black;width:7%;">DIC.</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-family: Arial;'.$size.'; text-align:left; height:13px;"><b>PPTO. PROGRAMADO INICIAL '.$this->gestion.'</b></td>';
        for ($i=1; $i <=12 ; $i++) {
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'>'.number_format($matriz[1][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td bgcolor="#79aee1" align=left height=13px;><b style="font-family: Arial;'.$size.';color:white;">PPTO. PROGRAMADO INICIAL ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) {
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'><b>'.number_format($matriz[2][$i], 2, ',', '.').'</b></td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td align=left height=13px;><b style="font-family: Arial;'.$size.';">PPTO. RE-FORMULADO '.$this->gestion.'</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'>'.number_format($matriz[3][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td bgcolor="#515660" align=left height=13px;><b style="font-family: Arial;'.$size.'; color:white;">PPTO. RE-FORMULADO ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'><b>'.number_format($matriz[4][$i], 2, ',', '.').'</b></td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td align=left height=13px;><b style="font-family: Arial;'.$size.';">PPTO. EJECUTADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'>'.number_format($matriz[5][$i], 2, ',', '.').'</td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td bgcolor="#a8f199" align=left height=13px;><b style="font-family: Arial;'.$size.';">PPTO. EJECUTADO ACUMULADO</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="font-family: Arial;'.$size.';" '.$color.'><b>'.number_format($matriz[6][$i], 2, ',', '.').'</b></td>';
        }
      $tabla.='
        </tr>
        <tr>
          <td align=left height=13px;><b style="font-family: Arial;'.$size.';">(%) CUMPLIMIENTO MENSUAL</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#e0edf9"';
          }
          $tabla.='<td align="right" style="'.$size.';" '.$color.'><b>'.$matriz[7][$i].'%</b></td>';
        }
      $tabla.='
        </tr>
      </tbody>
    </table>
   </div>';

    return $tabla;
  }


  /*-- TABLA PARA CUADRO CONSOLIDADO --*/
  public function tabla_consolidado_ejecucion_pinversion_impresion($matriz){
    $tabla='';
    $tabla.='
    <center>
    <table class="change_order_items" border=1 style="width:100%;">
      <thead>
        <tr>
          <th style="width:16%;"></th>
          <th style="width:7%;">ENE.</th>
          <th style="width:7%;">FEB.</th>
          <th style="width:7%;">MAR.</th>
          <th style="width:7%;">ABR.</th>
          <th style="width:7%;">MAY.</th>
          <th style="width:7%;">JUN.</th>
          <th style="width:7%;">JUL.</th>
          <th style="width:7%;">AGO.</th>
          <th style="width:7%;">SEPT.</th>
          <th style="width:7%;">OCT.</th>
          <th style="width:7%;">NOV.</th>
          <th style="width:7%;">DIC.</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b>(%) CUMPLIMIENTO MENSUAL</b></td>';
        for ($i=1; $i <=12 ; $i++) { 
          $color='';
          if($this->verif_mes[1]==$i){
            $color='bgcolor="#cff2db"';
          }
          $tabla.='<td align="right" style="font-size:10px" '.$color.'>'.$matriz[7][$i].'%</td>';
        }
      $tabla.='
        </tr>
      </tbody>
    </table>
    </center>';

    return $tabla;
  }
  //// ==============================================================


  /// Tabla reporte Consolidado de Partidas Vista o Excel
  public function tabla_consolidado_de_partidas($matriz,$nro,$tp_reporte){
    //// tp_reporte : 0 (Vista normal)
    //// tp_reporte : 1 (Excel)
    //// tp_reporte : 2 (Grafico)
    //// tp_reporte : 3 (pdf)
    if($tp_reporte==0){
      $class_table='class="table table-bordered" style="width:100%;"';
    }
    elseif($tp_reporte==1){
      $class_table='border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla"';
    }
    elseif($tp_reporte==2){
      $class_table='class="change_order_items" border=1 style="width:100%;"';
    }
    else{
      $class_table='border="0.2" cellpadding="0" cellspacing="0" width:100%; class="tabla"';
    }

    $tabla='';
   // $partidas=$this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id);
      $tabla.='
      <div align="center">
      <table '.$class_table.'>
        <thead>
          <tr>
            <th colspan=18 style="text-align:left;height:25px;font-size:15px"><b>DETALLE EJECUCIÓN FINANCIERA POR PARTIDAS</b></th>
          </tr>
          <tr bgcolor="#f4f4f4">
            <th style="text-align:center;height:15px;width:1%">#</th>
            <th style="text-align:center;width:5%">PARTIDA</th>
            <th style="text-align:center;width:24%">DETALLE</th>
            <th style="text-align:center;width:6%">PPTO. ASIGNADO</th>

            <th style="text-align:center;width:4%">ENE.</th>
            <th style="text-align:center;width:4%">FEB.</th>
            <th style="text-align:center;width:4%">MAR.</th>
            <th style="text-align:center;width:4%">ABR.</th>
            <th style="text-align:center;width:4%">MAY.</th>
            <th style="text-align:center;width:4%">JUN.</th>
            <th style="text-align:center;width:4%">JUL.</th>
            <th style="text-align:center;width:4%">AGO.</th>
            <th style="text-align:center;width:4%">SEPT.</th>
            <th style="text-align:center;width:4%">OCT.</th>
            <th style="text-align:center;width:4%">NOV.</th>
            <th style="text-align:center;width:4%">DIC.</th>
            <th style="text-align:center;width:7%">PPTO. EJECUTADO</th>
            <th style="text-align:center;width:7%">(%) EJECUCION</th>
          </tr>
        </thead>
        <tbody>';
        $nro_tr=0;
        $ppto_total_asignado=0;
        for ($i=5; $i <=18 ; $i++) { 
          $mes[$i]=0;
        }

        for ($i=0; $i<$nro; $i++) { 
          $nro_tr++;
          $ppto_total_asignado=$ppto_total_asignado+$matriz[$i][4];
            $tabla.='
            <tr>
            <td style="text-align:center;height:20px;width:1%">'.$nro_tr.'</td>
            <td style="text-align:center; font-size:11px;width:5%"><b>'.$matriz[$i][2].'</b></td>';
            if($tp_reporte==0 || $tp_reporte==2 || $tp_reporte==3){
              $tabla.='
              <td style="text-align:left;width:24%"><b>'.$matriz[$i][3].'</b></td>
              <td style="text-align:right;width:6%">'.number_format($matriz[$i][4], 2, ',', '.').'</td>';
              for ($j=5; $j <=18 ; $j++) {
                $mes[$j]=$mes[$j]+$matriz[$i][$j];
                $tabla.='<td style="text-align:right;width:4%">'.number_format($matriz[$i][$j], 2, ',', '.').'</td>';
              }
            }
            else{
              $tabla.='
              <td style="text-align:left">'.mb_convert_encoding($matriz[$i][3], 'cp1252', 'UTF-8').'</td>
              <td style="text-align:right">'.$matriz[$i][4].'</td>';
              for ($j=5; $j <=18 ; $j++) {
                $mes[$j]=$mes[$j]+$matriz[$i][$j];
                $tabla.='<td style="text-align:right">'.$matriz[$i][$j].' %</td>';
              }
            }
            
          $tabla.='</tr>';
        }
        $cumplimiento=0;
      $tabla.='
          <tr>
            <td colspan=3 style="height:15px;font-size:12px; text-align:right"><b>TOTAL PPTO.</b></td>
            <td align=right style="font-size:12px"><b>'.number_format($ppto_total_asignado, 2, ',', '.').'</b></td>';
            if($tp_reporte==0 || $tp_reporte==2 || $tp_reporte==3){
              for ($i=5; $i <=17 ; $i++) { 
                $tabla.='<td align=right style="font-size:12px"><b>'.number_format($mes[$i], 2, ',', '.').'</b></td>';
              }
            }
            else{
              for ($i=5; $i <=17 ; $i++) { 
                $tabla.='<td align=right>'.$mes[$i].'</td>';
              }
            }
            $tabla.='
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>';
    return $tabla;
  }

 

  /// Matriz Consolidado de partidas a nivel INSTITUCIONAL
  public function matriz_consolidado_partidas_prog_ejec_institucional(){
    $partidas=$this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_institucional();

    /// matriz vacia---
    for ($i=0; $i <=count($partidas); $i++) { 
      for ($j=1; $j <=18 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }
    ///----------------

    $nro=0;
    foreach($partidas as $partida){
      $ejec=$this->model_ptto_sigep->get_partida_ejecutado_gestion_institucional($partida['par_id']);
      
      $matriz[$nro][1]=$partida['par_id']; /// par_id
      $matriz[$nro][2]=$partida['partida']; /// codigo Partida
      $matriz[$nro][3]=$partida['par_nombre']; /// descripcion partida
      $matriz[$nro][4]=round($partida['ppto_partida_asignado_gestion'],2); /// monto asignado Gestion
      
      if(count($ejec)!=0){
        $fila=5;
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[$nro][$fila]=round($ejec[0]['m'.$i],2); /// temporalidad Ejecucion
          $fila++;
        } 

        $matriz[$nro][17]=round($ejec[0]['ejecutado_total'],2); /// Ejecucion de partida
        $matriz[$nro][18]=round((($ejec[0]['ejecutado_total']/$matriz[$nro][4])*100),2); // (% de cumplimiento)
      }
      $nro++;
    }

    return $matriz;
  }


  /// Matriz Consolidado de partidas por REGIONAL
  public function matriz_consolidado_partidas_prog_ejec_regional($dep_id){
    $partidas=$this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id);

    /// matriz vacia---
    for ($i=0; $i <count($partidas); $i++) { 
      for ($j=1; $j <=18 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }
    ///----------------

    $nro=0;
    foreach($partidas as $partida){
      $ejec=$this->model_ptto_sigep->get_partida_ejecutado_gestion_regional($partida['dep_id'],$partida['par_id']);
      
      $matriz[$nro][1]=$partida['par_id']; /// par_id
      $matriz[$nro][2]=$partida['partida']; /// codigo Partida
      $matriz[$nro][3]=$partida['par_nombre']; /// descripcion partida
      $matriz[$nro][4]=round($partida['ppto_partida_asignado_gestion'],2); /// monto asignado Gestion
      
      if(count($ejec)!=0){
        $fila=5;
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[$nro][$fila]=round($ejec[0]['m'.$i],2); /// temporalidad Ejecucion
          $fila++;
        } 

        $matriz[$nro][17]=round($ejec[0]['ejecutado_total'],2); /// Ejecucion de partida
        $matriz[$nro][18]=round((($ejec[0]['ejecutado_total']/$matriz[$nro][4])*100),2); // (% de cumplimiento)
      }
      $nro++;
    }

    return $matriz;
  }



  /// detalle modificacion de presupuesto por Regional
  public function detalle_modificacion_ppto_institucional(){
    $regionales=$this->model_proyecto->list_departamentos();

    $suma_inicial=0;
    $suma_modificado=0;
    $suma_vigente=0;
    foreach($regionales as $reg){
      $monto_partida=$this->detalle_modificacion_ppto_x_regional($reg['dep_id']);
      $suma_inicial=$suma_inicial+$monto_partida[1];
      $suma_modificado=$suma_modificado+$monto_partida[2];
      $suma_vigente=$suma_vigente+$monto_partida[3];
    }

    $datos[1]=$suma_inicial;
    $datos[2]=$suma_modificado;
    $datos[3]=$suma_vigente;

    return $datos;
  }



  /// detalle modificacion de presupuesto por Regional
  public function detalle_modificacion_ppto_x_regional($dep_id){
    $proyectos=$this->model_proyecto->list_proy_inversion_regional($dep_id);

    $suma_inicial=0;
    $suma_modificado=0;
    $suma_vigente=0;
    foreach($proyectos as $proy){
      $monto_partida=$this->detalle_modificacion_ppto_x_proyecto($proy['aper_id']);
      $suma_inicial=$suma_inicial+$monto_partida[1];
      $suma_modificado=$suma_modificado+$monto_partida[2];
      $suma_vigente=$suma_vigente+$monto_partida[3];
    }

    $datos[1]=$suma_inicial;
    $datos[2]=$suma_modificado;
    $datos[3]=$suma_vigente;

    return $datos;
  }


  /// detalle modificacion de presupuesto por proyecto
  public function detalle_modificacion_ppto_x_proyecto($aper_id){
    $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($aper_id); /// lista de partidas asignados por proyectos
    for ($i=1; $i <=3 ; $i++) { 
      $datos[$i]=0;
    }

    $suma_inicial=0;
    $suma_modificado=0;
    $suma_vigente=0;
    foreach($ppto_asig as $partida){
      $monto_partida=$this->detalle_modificacion_partida($partida); /// Detalle modificacion partida

      $suma_inicial=$suma_inicial+$monto_partida[1];
      $suma_modificado=$suma_modificado+$monto_partida[2];
      $suma_vigente=$suma_vigente+$monto_partida[3];
    }

    $datos[1]=$suma_inicial;
    $datos[2]=$suma_modificado;
    $datos[3]=$suma_vigente;

    return $datos;
  }


  /// detalle de modifcacion de presupuesto por partida
  public function detalle_modificacion_partida($partida){

    $ppto_modificado=$this->model_ptto_sigep->monto_modificado_x_partida($partida['sp_id']); /// ppto modificado por partida
    $datos[1]=$partida['importe'];
    $datos[2]=0;
    $datos[3]=$partida['importe'];
    if(count($ppto_modificado)!=0){
      $datos[1]=$ppto_modificado[0]['ppto_ini']; //// ppto inicial
      $datos[2]=$ppto_modificado[0]['ppto_modificado']; //// ppto modificado
      $datos[3]=$ppto_modificado[0]['ppto_final']; //// ppto vigente
    }

    return $datos;
  }


//////================= PARA EL TERCER GRAFICO

  /// Vector Consolidado de presupuesto Mensual a nivel Institucional
  public function vector_consolidado_ppto_mensual_institucional(){
    $ppto=$this->model_ptto_sigep->get_ppto_ejecutado_institucional();// lista ppto temporalidad ejecutado Institucional
    if(count($ppto)!=0){
      $j=0;
      for ($i=0; $i <=11 ; $i++) { 
        $j++;
        $ppto_pi[$i]=round($ppto[0]['m'.$j],2);
      }
      $ppto_pi[12]=round($ppto[0]['ejecutado_total'],2);
    }
    else{
      $j=0;
      for ($i=0; $i <=12 ; $i++) {
        $j++;
        $ppto_pi[$i]=0;
      }
    }

    return $ppto_pi;
  }

    /// Vector Consolidado de presupuesto Mensual por REGIONAL
  public function vector_consolidado_ppto_mensual_regional($dep_id){
    $ppto=$this->model_ptto_sigep->get_ppto_ejecutado_regional($dep_id);// lista ppto temporalidad ejecutado por regional
    if(count($ppto)!=0){
      $j=0;
      for ($i=0; $i <=11 ; $i++) { 
        $j++;
        $ppto_pi[$i]=round($ppto[0]['m'.$j],2);
      }
      $ppto_pi[12]=round($ppto[0]['ejecutado_total'],2);
    }
    else{
      $j=0;
      for ($i=0; $i <=12 ; $i++) {
        $j++;
        $ppto_pi[$i]=0;
      }
    }

    return $ppto_pi;
  }


  /// detalle de la Ejecucion de la temporalidad de presupuesto por partida nivel Regional
  public function detalle_temporalidad_mensual_regional($ppto_pi,$dep_id){
    if($dep_id==0){ /// Institucional
      $detalle_modificacion_pi=$this->detalle_modificacion_ppto_institucional();
    }
    else{ /// regional
      $detalle_modificacion_pi=$this->detalle_modificacion_ppto_x_regional($dep_id);
    }

    $tabla='';

    $tabla.='
    '.$this->style().'
     <table class="table table-bordered" style="width:100%;">
        <thead>
          <tr>
            <th style="width:5%;">PPTO. INICIAL</th>
            <th style="width:5%;">PPTO. MODIFICADO</th>
            <th style="width:5%;">PPTO. VIGENTE</th>
            <th style="width:5%;">ENE.</th>
            <th style="width:5%;">FEB.</th>
            <th style="width:5%;">MAR.</th>
            <th style="width:5%;">ABR.</th>
            <th style="width:5%;">MAY.</th>
            <th style="width:5%;">JUN.</th>
            <th style="width:5%;">JUL.</th>
            <th style="width:5%;">AGO.</th>
            <th style="width:5%;">SEPT.</th>
            <th style="width:5%;">OCT.</th>
            <th style="width:5%;">NOV.</th>
            <th style="width:5%;">DIC.</th>
            <th style="width:5%;">PPTO. EJEC</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td align=right>'.number_format($detalle_modificacion_pi[1], 2, ',', '.').'</td>
            <td align=right>'.number_format($detalle_modificacion_pi[2], 2, ',', '.').'</td>
            <td align=right>'.number_format($detalle_modificacion_pi[3], 2, ',', '.').'</td>';
            for ($i=0; $i <=12 ; $i++) { 
              $tabla.='<td align=right>'.number_format($ppto_pi[$i], 2, ',', '.').'</td>';
            }
          $tabla.='
          </tr>
        </tbody>
      </table>';


    return $tabla;
  }


///// ================ END TERCER GRAFICO


  //// Get % de ejecucion financiera de Proyecto en la Gestion
/*  public function get_ejec_ppto_proyecto_gestion($proyecto){
    $ppto_asig=$this->model_ptto_sigep->get_ppto_asignado_proyecto_gestion($proyecto[0]['proy_id']); // asignado gestion
    $ppto_ejec=$this->model_ptto_sigep->get_ppto_ejecutado_proyecto($proyecto[0]['proy_id']); // ejecutado gestion

    $ejec_ppto=0;
    if(count($ppto_asig)!=0 & count($ppto_ejec)!=0){
       $ejec_ppto=round(($ppto_ejec[0]['ejecutado_total']/$ppto_asig[0]['ppto_asignado'])*100,2);
    }

    return $ejec_ppto;
  }*/

  /// % DE CUMPLIMIENTO POR PROYECTOS DE INVERSION
  public function cumplimiento_pi($proyecto){
    //// DATOS DE PPTO (ASIG-EJEC)
    $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1); /// monto total asignado poa
    $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep($proyecto[0]['aper_id']); /// monto total ejecutado poa
    
    $ppto_asig=0;$ppto_ejec=0;
    if(count($total_ppto_asignado)!=0){
      $ppto_asig=$total_ppto_asignado[0]['monto'];
    }

    if(count($total_ppto_ejecutado)!=0){
      $ppto_ejec=$total_ppto_ejecutado[0]['ejecutado_total'];
    }

    $cumplimiento_pi=0;
    if(count($total_ppto_asignado)!=0 & count($total_ppto_ejecutado)!=0){
      $cumplimiento_pi=round((($total_ppto_ejecutado[0]['ejecutado_total']/$total_ppto_asignado[0]['monto']))*100,2);
    }
    ////

    $cumplimiento[1]=$ppto_asig; /// ppto asignado
    $cumplimiento[2]=$ppto_ejec; /// ppto ejecutado
    $cumplimiento[3]=$cumplimiento_pi; /// % cumplimiento

    return $cumplimiento;
  }





  ///// ============== FICHA TECNICA
  /// Datos Generales - Proyectos de Inversion
  public function datos_proyecto_inversion($proyecto,$cumplimiento,$cumplimiento_trimestral){
    $imagen=$this->model_proyecto->get_img_ficha_tecnica($proyecto[0]['proy_id']);
    if($proyecto[0]['fecha_observacion']!=''){
      $fecha_plazo=date('d/m/Y',strtotime($proyecto[0]['fecha_observacion']));
    }
    else{
      $fecha_plazo=date('d/m/Y');
    }

    $tabla='';
     $tabla.='
      <div style="height:25px;"><b>DATOS GENERALES</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.001 style="width:100%;">
        <tbody>
          <tr>
            <td style="width:25%; height:15px; font-family: Arial; font-size: 10px;height:20px;" bgcolor="#e8e7e7"><b>PROYECTO DE INVERSIÓN</b></td>
            <td style="width:45%; font-family: Arial; font-size: 9px;height:20px;"><b>&nbsp;'.$proyecto[0]['proyecto'].'</b></td>
            <td rowspan=11 style="width:30%;text-align:center">';
            if(count($imagen)!=0){
              if($imagen[0]['tp']==1){
                //$tabla.='<img src="'.getcwd().'/fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
                $tabla.='<img src="'.getcwd().'/fotos_proyectos/'.$imagen[0]['imagen'].'" class="img-responsive" style="width:230px; height:170px;"/>';
              }
              else{
                $tabla.='<img src="'.getcwd().'/fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
              }
            }
            else{
              $tabla.='<img src="'.getcwd().'/fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
            }
          $tabla.='
            </td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>C&Oacute;DIGO SISIN</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.$proyecto[0]['proy'].'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>REGIONAL</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>DISTRITAL</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>MUNICIPIO</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['municipio']).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FECHA DE INICIO</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.date('d/m/Y',strtotime($proyecto[0]['fecha_inicio'])).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FECHA DE CONCLUCI&Oacute;N</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.date('d/m/Y',strtotime($proyecto[0]['fecha_final'])).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>COSTO TOTAL DEL PROYECTO</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;Bs. '.number_format($proyecto[0]['proy_ppto_total'], 2, ',', '.').'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FASE</b></td>
            <td style="width:45%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['fas_fase']).'</td>
          </tr>
        </tbody>
       </table><br>
        <div style="height:25px;"><b>OBJETIVOS DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.001 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;text-align: justify;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO GENERAL</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['proy_obj_general']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;text-align: justify;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO ESPECIFICO</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['proy_obj_especifico']).'</td>
          </tr>
        </tbody>
       </table><br>
       <div style="height:25px;"><b>DETALLE DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.001 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>ESTADO DEL PROYECTO</b></td>
            <td style="width:55%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['ep_descripcion']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:15px;" bgcolor="#e8e7e7"><b>AVANCE FÍSICO PROYECTO</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;
              <table style="width:100%;">
                <tr>
                  <td style="width:15%;">'.round($proyecto[0]['avance_fisico'],2).' %</td>
                  <td style="width:85%;">a fecha : '.date("d").'/'.date("m"). "/" . date("Y").'</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FINANCIERO TOTAL</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;
              <table style="width:100%;">
                <tr>
                  <td style="width:15%;">'.round($proyecto[0]['avance_financiero'],2).' %</td>
                  <td style="width:85%;">a fecha : '.date("d").'/'.date("m"). "/" . date("Y").'</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PPTO. ASIGNADO - GESTIÓN '.$this->gestion.'</b></td>
            <td style="width:75%; font-size: 9px;">Bs. '.number_format($cumplimiento[1], 2, ',', '.').'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PPTO. EJECUTADO - GESTIÓN '.$this->gestion.'</b></td>
            <td style="width:75%; font-size: 9px;">Bs. '.number_format($cumplimiento[2], 2, ',', '.').'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>(%) PPTO. EJECUTADO al TRIMESTRE</b></td>
            <td style="width:75%; font-size: 10px;">&nbsp;<b>'.$cumplimiento_trimestral.' %</b></td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>(%) PPTO. EJECUTADO GESTIÓN '.$this->gestion.'</b></td>
            <td style="width:75%; font-size: 10px;">&nbsp;<b>'.$cumplimiento[3].' %</b></td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FISCAL DE OBRA</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['fiscal_obra']).'</td>
          </tr>
          </tbody>
        </table>';
        if(strlen($proyecto[0]['proy_observacion'])>700){
          $tabla.='<br><b>OBSERVACIÓN / COMPROMISO</b>
          <hr>
            <span style="margin:-10px 10px 0px 10px;font-size:9px;text-align: justify;">
              <p>'.strtoupper($proyecto[0]['proy_observacion']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a fecha : '.$fecha_plazo.'</p>
            </span>
          <hr>';
        }
        else{
        $tabla.='
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.001 style="width:100%;">
          <tbody>
            <tr style="font-family: Arial; font-size: 10px;text-align: justify;">
              <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBSERVACIÓN / COMPROMISO</b></td>
              <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['proy_observacion']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a fecha : '.$fecha_plazo.'</td>
            </tr>
          </tbody>
         </table>';
        }


        $tabla.='
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.001 style="width:100%;">
          <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROBLEMA IDENTIFICADO</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['proy_desc_problema']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROPUESTA DE SOLUCIÓN</b></td>
            <td style="width:75%; font-size: 9px;">&nbsp;'.strtoupper($proyecto[0]['proy_desc_solucion']).'</td>
          </tr>
        </tbody>
       </table>';
        
    return $tabla;
  }


  /// Lista de Ejecucion Presupuestaria x Partida - Proyectos de Inversion
  public function detalle_ejecucion_presupuestaria_pi($proyecto){
    $tabla='';
    $lista_partidas_ppto_asig=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']); /// lista de partidas asignados por proyectos

    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr>
            <td style="width:20%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#e6e5e5"><b>&nbsp;REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                </table>
            </td>
            <td style="width:80%;">
                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                    <tr><td style="width:100%;height: 1.5%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dep_cod'].' '.strtoupper ($proyecto[0]['dep_departamento']).'</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:20%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#e6e5e5"><b>&nbsp;UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                </table>
            </td>
            <td style="width:80%;">
                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                    <tr><td style="width:100%;height: 1.5%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['dist_cod'].' '.strtoupper ($proyecto[0]['dist_distrital']).'</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:20%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#e6e5e5"><b>&nbsp;PROYECTO</b></td><td style="width:5%;"></td></tr>
                </table>
            </td>
            <td style="width:80%;">
                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                    <tr><td style="width:100%;height: 1.5%;" bgcolor="#f9f9f9">&nbsp;'.$proyecto[0]['prog'].' '.$proyecto[0]['proy'].' '.$proyecto[0]['act'].' - '.strtoupper ($proyecto[0]['proyecto']).'</td></tr>
                </table>
            </td>
        </tr>
      </table>

        <hr>
        <div style="height:25px;"><b>PROGRAMACIÓN y EJECUCIÓN FINANCIERA POR PARTIDA</b></div>';
       foreach($lista_partidas_ppto_asig as $partida){
        $temporalidad_ejec=$this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($partida['sp_id']); /// temporalidad ejec partida
        /// ------ Datos de Modifcacion de la partida
        $monto_partida=$this->detalle_modificacion_partida($partida);
        //// -----------------------------------------

        /// montos programados por partidas
        $partida_programado=$this->model_insumo->get_list_temporalidad_partida_programado($proyecto[0]['aper_id'],$partida['par_id']);


        /// montos ejecutados por partidas
        $monto_total_ejecutado=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($partida['sp_id']); /// monto total ejecutado
        $monto_ejecutado=0;
        if(count($monto_total_ejecutado)!=0){
          $monto_ejecutado=$monto_total_ejecutado[0]['ejecutado'];
        }

        /// Porcentaje de Avance por partidas
        $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($partida['sp_id']); /// Get partida sigep
        $porcentaje_avance_fin=0;
        if(count($get_partida_sigep)!=0 & $get_partida_sigep[0]['importe']!=0){
          $porcentaje_avance_fin=round((($monto_ejecutado/$get_partida_sigep[0]['importe'])*100),2);
        }

        $tabla.='
        <div style="font-family: Arial; height:18px;font-size: 10px;">PARTIDA : '.$partida['partida'].' - '.strtoupper($partida['par_nombre']).'</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
          <thead>
            <tr bgcolor="#e8e7e7" align=center>
              <th style="width:6%;height:15px;">PPTO. INICIAL</th>
              <th style="width:6%;">PPTO. MOD.</th>
              <th style="width:6%;">PPTO. VIGENTE</th>
              <th style="width:5%;"></th>
              <th style="width:5.4%;">ENE.</th>
              <th style="width:5.4%;">FEB.</th>
              <th style="width:5.4%;">MAR.</th>
              <th style="width:5.4%;">ABR.</th>
              <th style="width:5.4%;">MAY.</th>
              <th style="width:5.4%;">JUN.</th>
              <th style="width:5.4%;">JUL.</th>
              <th style="width:5.4%;">AGO.</th>
              <th style="width:5.4%;">SEPT.</th>
              <th style="width:5.4%;">OCT.</th>
              <th style="width:5.4%;">NOV.</th>
              <th style="width:5.4%;">DIC.</th>
              <th style="width:6%;">PPTO. POA - EJEC.</th>
              <th style="width:5%;">(%) CUMP.</th>
            </tr>
          </thead>
          <tbody>
            <tr style="text-align:right;">
              <td style="height:12px; font-size:9px;" title="'.$partida['sp_id'].'" rowspan="2"><b>'.number_format($monto_partida[1], 0, ',', '.').'</b></td>
              <td style="font-size:9px;" rowspan="2"><b>'.number_format($monto_partida[2], 0, ',', '.').'</b></td>
              <td style="font-size:9px;" rowspan="2"><b>'.number_format($monto_partida[3], 0, ',', '.').'</b></td>
              <td align=left  style="height:12px;"><b>PROGRAMADO :</b></td>';
              $suma_ppto_poa=0;
              if(count($partida_programado)!=0){
                for ($i=1; $i <=12 ; $i++) {
                  $suma_ppto_poa=$suma_ppto_poa+$partida_programado[0]['mes'.$i];
                  $tabla.='<td>'.number_format($partida_programado[0]['mes'.$i], 0, ',', '.').'</td>';
                }
              }
              else{
                $tabla.='<td>0</td>';
              }
              $tabla.='
              <td><b>'.number_format($suma_ppto_poa, 0, ',', '.').'</b></td>
              <td style="height:12px; font-size:11px;" rowspan="2"><b>'.$porcentaje_avance_fin.'%</b></td>
            </tr>
            <tr style="text-align:right;">
              <td align=left style="height:12px;"><b>EJECUTADO :</b></td>';
              if(count($temporalidad_ejec)!=0){
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td>'.number_format($temporalidad_ejec[0]['m'.$i], 0, ',', '.').'</td>';
                }
                $tabla.='<td><b>'.number_format($temporalidad_ejec[0]['ejecutado_total'], 0, ',', '.').'</b></td>';
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td>0.00</td>';
                }
                $tabla.='<td>0.00</td>';
              }
              $tabla.='
            </tr>
          </tbody>
        </table><br><br>';
       }

       $tabla.='<div style="height:25px;"><b>DETALLE CONSOLIDADO POA PRESUPUESTO '.$this->gestion.' </b></div>';
      // $tabla.=$cumplimiento;

    return $tabla;
  }



  /// Consolidado POA - Proyectos de Inversion
  public function consolidado_poa_inversion($proyecto){
    $ppto_programado_poa_inicial=$this->model_insumo->temporalidad_inicial_pinversion($proyecto[0]['aper_id']); /// ppto poa Inicial

      $ppto_programado_poa=$this->model_insumo->list_temporalidad_programado_unidad($proyecto[0]['aper_id']); /// ppto poa (Actual)
      $ppto_ejecutado_sigep=$this->model_ptto_sigep->get_ppto_ejecutado_pinversion($proyecto[0]['aper_id']); /// Ppto Ejecutado sigep

      $matriz=$this->matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep);

     return $this->tabla_consolidado_ejecucion_pinversion($matriz,1); /// tabla vista
  }







  /// Cabecera Reporte Ficha Tecnica
  public function cabecera_ficha_tecnica($titulo_reporte){
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px black; text-align: center;">
              <td style="width:23%; text-align:center;">
                <img src="'.getcwd().'/assets/ifinal/logo_cns.JPG" class="img-responsive" style="width:53px; height:53px;"/><br>
                <b style="font-size: 6.5px;font-family: Arial;">CAJA NACIONAL DE SALUD</b><br>
                DPTO. NAL. DE PLANIFICACIÓN
              </td>
              <td style="width:60%; height: 5%">
                  <table align="center" border="0" style="width:100%;">
                      <tr style="font-size: 20px;font-family: Arial;">
                        <td style="height: 32%;"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                      </tr>
                      <tr style="font-size: 22px;font-family: Arial;">
                        <td style="height: 5%;">'.$titulo_reporte.'</td>
                      </tr>
                  </table>
              </td>
              <td style="width:20%; text-align:center;">
              '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
      </table>';

    return $tabla;
  }

  //// Pie Ficha Tecnica
  public function pie_ficha_tecnica(){ 
    $tabla='';
    $tabla.='
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
      <tr>
        <td style="width: 33%; height:18px;text-align: left">
          POA - '.$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa').'
        </td>
        <td style="width: 33%; text-align: center">
          '.$this->session->userdata('sistema').'
        </td>
        <td style="width: 33%; text-align: right">
          '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
        </td>
      </tr>
    </table>';

    return $tabla;
  }



   /*---- CABECERA REPORTE OPERACIONES POR REGIONALES (GRAFICO)----*/
  function cabecera_reporte_grafico($titulo,$subtitulo){
    $tabla='';

    $tabla.='
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
      <tr style="border: solid 0px black; text-align: center;">
        <td style="width:23%; text-align:center;">
          <center>
            <img src="'.base_url().'assets/ifinal/logo_cns.JPG" class="img-responsive" style="width:45px; height:45px;"/><br>
            <b style="font-size: 6px;font-family: Arial;">CAJA NACIONAL DE SALUD</b><br>
            <div style="font-size: 7px;font-family: Arial;">DPTO. NAL. DE PLANIFICACIÓN</div>
          </center>
        </td>
        <td style="width:60%; height: 5%">
            <table align="center" border="0" style="width:100%;">
                <tr style="font-size: 18px;font-family: Arial;">
                  <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                </tr>
                <tr style="font-size: 18px;font-family: Arial;">
                  <td style="height: 5%; text-align:center">'.$titulo.'</td>
                </tr>
            </table>
        </td>
        <td style="width:20%; text-align:center;font-size: 8px;">
        '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
    </table>
    
    <div style="font-size: 12px;font-family: Arial; text-align:center"><b>'.$subtitulo.'</b></div>';

    return $tabla;
  }

//// =========== END FICHA TECNICA =========================


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
      #ejecucion_ppto{
        width: 60% !important;
      }
      #imagenes_pi{
        width: 80% !important;
      }
      #programacion{
        width: 50% !important;
      }
      #certificacion{
        width: 40% !important;
      }

  </style>';

    return $tabla;
  }

  ////// MENU EJECUCION PI
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
            <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Ejecución Financiera</span></a>
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

    //// Menu Administrador Normal
  public function menu($mod){
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
      $mes[1] = 'Enero';
      $mes[2] = 'Febrero';
      $mes[3] = 'Marzo';
      $mes[4] = 'Abril';
      $mes[5] = 'Mayo';
      $mes[6] = 'Junio';
      $mes[7] = 'Julio';
      $mes[8] = 'Agosto';
      $mes[9] = 'Septiembre';
      $mes[10] = 'Octubre';
      $mes[11] = 'Noviembre';
      $mes[12] = 'Diciembre';
      return $mes;
  }


}
?>