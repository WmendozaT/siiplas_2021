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

  /*------- TITULO --------*/
  public function formulario(){
    $regional=$this->model_proyecto->get_departamento($this->dep_id);
    $meses = $this->model_configuracion->get_mes();
    $tabla='';
    $tabla.='
    
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
    <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">
    <article class="col-sm-12">
      <div class="well">
        <form class="smart-form">
          <header><h2><b>FORMULARIO DE EJECUCI&Oacute;N PRESUPUESTARIA - </b> '.strtoupper($regional[0]['dep_departamento']).' '.$this->verif_mes[2].' / '.$this->gestion.'</h2></header>
          <fieldset>
            <section>
              <label class="label">Selecciones MES / '.$this->gestion.'</label>
               <select class="form-control" style="width:20%;" name="mes_id" id="mes_id">';
               foreach($meses as $row){
                if($row['m_id']<=ltrim(date("m"), "0")){
                  if($row['m_id']==$this->verif_mes[1]){
                    $tabla.='<option value="'.$row['m_id'].'" selected>'.$row['m_descripcion'].'</option>';
                  }
                  else{
                    $tabla.='<option value="'.$row['m_id'].'">'.$row['m_descripcion'].'</option>';
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
      </article>

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
            <th style="width:2%;" bgcolor="#fafafa" title="ACTUALIZAR INFORMACION">FORMULARIO<br>EJECUCIÓN</th>
            <th style="width:2%;" bgcolor="#fafafa" title="IMAGENES PROYECTO">SUBIR AVANCE (Imagen)</th>
            <th style="width:2%;" bgcolor="#fafafa" title="FICHA TECNICA">FICHA TÉCNICA</th>
            <th style="width:7%;" bgcolor="#fafafa" title="">REGIONAL</th>
            <th style="width:7%;" bgcolor="#fafafa" title="">DISTRITAL</th>
            <th style="width:10%;" bgcolor="#fafafa" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
            <th style="width:10%;" bgcolor="#fafafa" title="CODIGO SISIN">CODIGO SISIN</th>
            <th style="width:15%;" bgcolor="#fafafa" title="PROYECTO">PROYECTO DE INVERSI&Oacute;N</th>
            <th style="width:10%;" bgcolor="#fafafa" title="SISIN">COSTO TOTAL DE PROYECTO</th>
            <th style="width:15%;" bgcolor="#fafafa" title="FASE">FASE DEL PROYECTO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="FASE">AVANCE FÍSICO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="FASE">AVANCE FINANCIERO</th>
            <th style="width:5%;" bgcolor="#fafafa" title="">VER AVANCES DEL PROYECTO</th>
            <th style="width:5%;" bgcolor="#fafafa" title=""></th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($proyectos as $row){
          $img=$this->model_proyecto->get_img_ficha_tecnica($row['proy_id']);
          $bgcolor='';
          if(count($img)!=0){
            $bgcolor='#eff5f4';
          }
          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.'>
            <td style="width:1%; text-align:center" title='.$row['proy_id'].'>'.$nro.'</td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_ppto_pi" class="btn btn-default ejec_ppto_pi" name="'.$row['proy_id'].'" title="REGISTRO DE EJECUCION PRESUPUESTARIA"><img src="'.base_url().'assets/ifinal/faseetapa.png" WIDTH="40" HEIGHT="40"/></a>
            </td>
            <td style="text-align:center">
              <a href="#" data-toggle="modal" data-target="#modal_mod_fotos" class="btn btn-default fotos_pi" name="'.$row['proy_id'].'" id="'.$row['proyecto'].'" title="SUBIR AVANCES DEL PROYECTO"><img src="'.base_url().'assets/ifinal/subir_img.jpg" WIDTH="40" HEIGHT="40"/></a>
            </td>
            <td style="width:5%; text-align:center">
              <a href="javascript:abreVentana(\''.site_url("").'/reporte_ficha_tecnica_pi/'.$row['proy_id'].'\');" class="btn btn-default" title="REPORTE FICHA TECNICA"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="35" HEIGHT="35"/></a>
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


  /*------- DETALLE EJECUCION PI EXCEL (2)--------*/
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
              <th style="width:3%; font-size: 10px; text-align:center"><b>EJEC. FIS. PROY.</b></th>
              <th style="width:3%; font-size: 10px; text-align:center"><b>EJEC. FIN. PROY.</b></th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
              $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
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
                  if(count($get_partida_sigep)!=0){
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
                  <td align=center><b>'.$partida['partida'].'</b></td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[1], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[2], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right>'.number_format($monto_partida[3], 2, ',', '.').'</td>
                  <td style="font-size: 11px;font-family: Arial;" align=right><b>Bs. '.number_format($monto_ejecutado, 2, ',', '.').'</b></td>
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


 /*------- REPORTE 3 DETALLE POR PARTIDA EJECUCION FISICA Y FINANCIERA--------*/
  public function reporte3_pdf_excel($dep_id,$tipo_reporte){
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
              <td style="width:40%; font-size: 15px; text-align:center;height:50px" colspan=13><b>DETALLE PROYECTO</b></td>
              <td style="width:60%; font-size: 15px; text-align:center;" colspan=14><b>EJECUCION FINANCIERA</b></td>
              <td colspan=2></td>
            </tr>
            <tr bgcolor="#f4f4f4" border=1>
              <th style="width:1%; font-size: 12px; text-align:center;height:60px"><b>#</b></th>
              <th style="width:3%; font-size: 12px; text-align:center"><b>REGIONAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>DISTRITAL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CODIGO SISIN</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>CATEGORIA PROGRAMATICA</b></th>
              <th style="width:15%; font-size: 12px; text-align:center"><b>NOMBRE DEL PROYECTO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>COSTO TOTAL<br>PROYECTO (Bs.)</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>ESTADO<br>PROYECTO</b></th>
              <th style="width:10%; font-size: 12px; text-align:center"><b>FASE</b></th>
              <th style="width:2%; font-size: 12px; text-align:center"><b>PARTIDA</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>PPTO. INICIAL <br>'.$this->gestion.'</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>PPTO. MOD. <br>'.$this->gestion.'</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>PPTO. VIGENTE <br>'.$this->gestion.'</b></th>

              <th style="width:5%; font-size: 12px; text-align:center"><b>ENERO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>FEBRERO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>MARZO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>ABRIL</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>MAYO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>JUNIO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>JULIO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>AGOSTO</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>SEPTIEMBRE</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>OCTUBRE</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>NOVIEMBRE</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>DICIEMBRE</b></th>
              <th style="width:5%; font-size: 12px; text-align:center"><b>TOTAL EJECUTADO '.$this->gestion.'</b></th>
              <th style="width:8%; font-size: 12px; text-align:center"><b>ULTIMA OBSERVACION</b></th>
              <th style="width:8%; font-size: 12px; text-align:center"><b>EJEC. FIS. TOTAL</b></th>
              <th style="width:8%; font-size: 12px; text-align:center"><b>EJEC. FIN. TOTAL</b></th>
            </tr>
          </thead>
          <tbody>';
           $nro=0;
            foreach($proyectos as $row){
            //  $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
              $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
              $modificaciones_ppto_proyecto=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);
              $ppto_ejecutado_proyecto=$this->model_ptto_sigep->get_ppto_ejecutado_proyecto($row['proy_id']); /// ejecucion de presupuesto por proyecto
              $nro++;
              $tabla.='
              <tr bgcolor="#e7e741">
                <td style="width:1%;font-size: 12px;font-family: Arial; height:50px; text-align:center" title='.$row['aper_id'].'>'.$nro.'</td>
                <td style="width:3%;font-size: 12px;font-family: Arial;">'.strtoupper($row['dep_departamento']).'</td>
                <td style="width:5%;font-size: 12px;font-family: Arial;">'.strtoupper($row['dist_distrital']).'</td>
                <td style="width:5%;font-size: 12px;font-family: Arial;">'.$row['proy'].'</td>
                <td style="width:5%;font-size: 12px;font-family: Arial;">\''.$row['prog'].' '.$row['proy'].' 000\'</td>
                <td style="width:15%;font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                <td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.round($row['proy_ppto_total'],2).'</b></td>
                <td style="width:5%;font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['ep_descripcion']), 'cp1252', 'UTF-8').'</td>
                <td style="width:10%;font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($fase[0]['fase'].' - '.$fase[0]['descripcion']), 'cp1252', 'UTF-8').'</td>
                <td style="width:2%;font-size: 12px;font-family: Arial;"></td>
                <td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[1],2).'</b></td>
                <td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[2],2).'</b></td>
                <td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.round($modificaciones_ppto_proyecto[3],2).'</b></td>';
                $ppto_ejecutado=0;
                if(count($ppto_ejecutado_proyecto)!=0){
                  $ppto_ejecutado=round($ppto_ejecutado_proyecto[0]['ejecutado_total'],2);
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.round($ppto_ejecutado_proyecto[0]['m'.$i],2).'</b></td>';
                  }
                }
                else{
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td style="width:5%;font-size: 12px;font-family: Arial;" align=right>0</td>';
                  }
                }
                
                //// Avance Financiero (%)
                /*$avance_fin_total=0;
                if($row['proy_ppto_total']!=0){
                  $avance_fin_total=round(($ppto_ejecutado/$row['proy_ppto_total'])*100,2);
                }*/
                ///------

                $tabla.='
                <td style="width:5%;font-size: 12px;font-family: Arial;" align=right><b>'.$ppto_ejecutado.'</b></td>
                <td></td>
                <td style="width:8%;font-size: 12px;font-family: Arial;" align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
                <td style="width:8%;font-size: 12px;font-family: Arial;" align=right><b>'.round($row['avance_financiero'],2).' %</b></td>
              </tr>';

              $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($row['aper_id']); /// lista de partidas asignados por proyectos
              foreach($ppto_asig as $partida){
                $tabla.='
                <tr>
                  <td style="font-size: 12px;font-family: Arial; height:50px"></td>
                  <td style="font-size: 12px;font-family: Arial;">'.strtoupper($row['dep_departamento']).'</td>
                  <td style="font-size: 12px;font-family: Arial;">'.strtoupper($row['dist_distrital']).'</td>
                  <td style="font-size: 12px;font-family: Arial;">'.$row['proy'].'</td>
                  <td style="font-size: 12px;font-family: Arial;">\''.$row['prog'].' '.$row['proy'].' 000\'</td>
                  <td style="font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['proyecto']), 'cp1252', 'UTF-8').'</td>
                  <td style="font-size: 12px;font-family: Arial;" align=right><b>'.round($row['proy_ppto_total'],2).'</b></td>
                  <td style="font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($row['ep_descripcion']), 'cp1252', 'UTF-8').'</td>
                  <td style="font-size: 12px;font-family: Arial;">'.mb_convert_encoding(strtoupper($fase[0]['fase'].' - '.$fase[0]['descripcion']), 'cp1252', 'UTF-8').'</td>
                  <td style="font-size: 12px;font-family: Arial;" bgcolor="#c4efe9" align=center title='.$partida['sp_id'].'><b>'.$partida['partida'].'</b></td>
                  '.$this->detalle_temporalidad_ejecucion_x_partida($partida).'
                </tr>';
              }
            }
          $tabla.='
          </tbody>
        </table>
       ';

    }
    else{

      $tabla.='
        <div style="font-size: 10px; height:20px;">DETALLE EJECUCIÓN FISICO FINANCIERO</div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:95%;" align=center>
          <thead>
            <tr bgcolor="#e8e7e7" align=center>
              <th style="width:10%;">DISTRITAL</th>
              <th style="width:10%;">CÓDIGO SISIN</th>
              <th style="width:8%;">CATEGORIA PROGRAMATICA '.$this->gestion.'</th>
              <th style="width:20%;">PROYECTO DE INVERSIÓN</th>
              <th style="width:8%;">COSTO TOTAL PROYECTO</th>
              <th style="width:12%;">ESTADO DEL PROYECTO</th>
              <th style="width:7%;">PPTO. INICIAL</th>
              <th style="width:7%;">PPTO. MOD.</th>
              <th style="width:7%;">PPTO. VIGENTE</th>
              <th style="width:5%;">PPTO. EJECUTADO</th>
              <th style="width:5%;">EJEC. FÍS. TOTAL</th>
              <th style="width:5%;">EJEC. FIN. TOTAL</th>
            </tr>
          </thead>
          <tbody>';
          foreach($proyectos as $row){
            $ejec_fin=$this->avance_financiero_pi($row['aper_id'],$row['proy_ppto_total']); /// Ejecucion Presupuestaria PI
            $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
            $modificacion_partida=$this->detalle_modificacion_ppto_x_proyecto($row['aper_id']);

            $tabla.='
            <tr>
              <td style="font-size: 7px; height:12px;width:10%;">'.strtoupper($row['dist_distrital']).'</td>
              <td style="width:10%;">'.$row['proy'].'</td>
              <td style="width:8%;">'.$row['prog'].' '.$row['proy'].' 000</td>
              <td style="width:20%;">'.strtoupper($row['proyecto']).'</td>
              <td style="width:8%;" align=right>Bs. '.number_format($row['proy_ppto_total'], 2, ',', '.').'</td>
              <td style="width:10%;">'.strtoupper($row['ep_descripcion']).'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[1], 2, ',', '.').'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[2], 2, ',', '.').'</td>
              <td style="width:7%;" align=right>'.number_format($modificacion_partida[3], 2, ',', '.').'</td>
              <td style="width:5%;" align=right>'.number_format($ejec_fin[1], 2, ',', '.').'</td>


              <td style="width:5%;" align=right><b>'.round($row['avance_fisico'],2).' %</b></td>
              <td style="width:5%;" align=right><b>'.round($row['avance_financiero'],2).' %</b></td>
            </tr>';
          }
      $tabla.='
          </tbody>
        </table>';
    }

    return $tabla;
  }


  /// Tabla reporte Consolidado de Partidas Vista o Excel
  public function tabla_consolidado_de_partidas($matriz,$nro,$tp_reporte){
    //// tp_reporte : 0 (Vista normal)
    //// tp_reporte : 1 (Excel)
    //// tp_reporte : 2 (Grafico)
    if($tp_reporte==0){
      $class_table='class="table table-bordered" style="width:100%;"';
    }
    elseif($tp_reporte==1){
      $class_table='border="1" cellpadding="0" cellspacing="0" width:100%; class="tabla"';
    }
    else{
      $class_table='class="change_order_items" border=1 style="width:100%;"';
    }

    $tabla='';
   // $partidas=$this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id);
      $tabla.='
      <center>
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
      </style>
      <table '.$class_table.'>
        <thead>
          <tr>
            <th style="text-align:center;height:20px;">#</th>
            <th style="text-align:center">PARTIDA</th>
            <th style="text-align:center">DETALLE</th>
            <th style="text-align:center">PPTO. ASIGNADO</th>

            <th style="text-align:center">ENE.</th>
            <th style="text-align:center">FEB.</th>
            <th style="text-align:center">MAR.</th>
            <th style="text-align:center">ABR.</th>
            <th style="text-align:center">MAY.</th>
            <th style="text-align:center">JUN.</th>
            <th style="text-align:center">JUL.</th>
            <th style="text-align:center">AGO.</th>
            <th style="text-align:center">SEPT.</th>
            <th style="text-align:center">OCT.</th>
            <th style="text-align:center">NOV.</th>
            <th style="text-align:center">DIC.</th>
            <th style="text-align:center">PPTO. EJECUTADO</th>
            <th style="text-align:center">(%) EJECUCION</th>
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
            <td style="text-align:center;height:20px;">'.$nro_tr.'</td>
            <td style="text-align:center">'.$matriz[$i][2].'</td>';
            if($tp_reporte==0 || $tp_reporte==2){
              $tabla.='
              <td style="text-align:left">'.$matriz[$i][3].'</td>
              <td style="text-align:right">'.number_format($matriz[$i][4], 2, ',', '.').'</td>';
              for ($j=5; $j <=18 ; $j++) {
                $mes[$j]=$mes[$j]+$matriz[$i][$j];
                $tabla.='<td style="text-align:right">'.number_format($matriz[$i][$j], 2, ',', '.').'</td>';
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
      $tabla.='
          <tr>
            <td colspan=3>TOTAL</td>
            <td align=right>'.$ppto_total_asignado.'</td>';
            if($tp_reporte==0 || $tp_reporte==2){
              for ($i=5; $i <=17 ; $i++) { 
                $tabla.='<td align=right>'.number_format($mes[$i], 2, ',', '.').'</td>';
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
    </center>';
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

  /// detalle de la Ejecucion de la temporalidad de presupuesto por partida
  public function detalle_temporalidad_ejecucion_x_partida($partida){
    $tabla='';
    
    $monto_ejecutado=0;
    $porcentaje_avance_fin=0;
    $temporalidad_ejec=$this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($partida['sp_id']); /// temporalidad ejecucion de partida

    /// ------ Datos de Modifcacion de la partida
    $monto_partida=$this->detalle_modificacion_partida($partida);


    /// Observacion
    $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($partida['sp_id'],$this->verif_mes[1]); /// Observacion
    $observacion_ejecutado='';
    if(count($obs_ejec_mensual)!=0){
      $observacion_ejecutado=$obs_ejec_mensual[0]['observacion'];
    }

    $tabla.='
        <td style="font-size: 12px;font-family: Arial;" bgcolor="#c4efe9" align=right>'.round($monto_partida[1],2).'</td>
        <td style="font-size: 12px;font-family: Arial;" bgcolor="#c4efe9" align=right>'.round($monto_partida[2],2).'</td>
        <td style="font-size: 12px;font-family: Arial;" bgcolor="#c4efe9" align=right>'.round($monto_partida[3],2).'</td>';


      if(count($temporalidad_ejec)!=0){
        $monto_ejecutado=round($temporalidad_ejec[0]['ejecutado_total'],2);
          for ($i=1; $i <=12 ; $i++) { 
            $tabla.='<td style="font-size: 12px;font-family: Arial;" bgcolor="#e5f7f5" align=right><b>'.round($temporalidad_ejec[0]['m'.$i],2).'</b></td>';
          }
      }
      else{
        for ($i=1; $i <=12 ; $i++) { 
            $tabla.='<td style="font-size: 12px;font-family: Arial;" bgcolor="#e5f7f5" align=right><b>0</b></td>';
          }
      }
      $tabla.=' <td style="font-size: 12px;font-family: Arial;" bgcolor="#e5f7f5" align=right><b>'.round($monto_ejecutado,2).'</b></td>
                <td style="font-size: 12px;font-family: Arial;" bgcolor="#e5f7f5" align=right>'.mb_convert_encoding(strtoupper($observacion_ejecutado), 'cp1252', 'UTF-8').'</td>
                <td></td>
                <td style="font-size: 12px;font-family: Arial;" bgcolor="#e5f7f5" align=right><b>'.round((($monto_ejecutado/$monto_partida[3])*100),2).' %</b></td>';
    return $tabla;
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

  /// Vector Consolidado de presupuesto Acummulado Mensual nivel Inatitucional
  public function vector_consolidado_ppto_acumulado_mensual_institucional(){
    $ppto=$this->model_ptto_sigep->get_ppto_ejecutado_institucional();// lista ppto temporalidad ejecutado Institucional
    if(count($ppto)!=0){
      $j=0;$suma=0;
      for ($i=0; $i <=11 ; $i++) { 
        $j++;
        $suma=$suma+round($ppto[0]['m'.$j],2);
        $ppto_pi[$i]=$suma;
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

  /// Vector Consolidado de presupuesto Acummulado Mensual por REGIONAL
  public function vector_consolidado_ppto_acumulado_mensual_regional($dep_id){
    $ppto=$this->model_ptto_sigep->get_ppto_ejecutado_regional($dep_id);// lista ppto temporalidad ejecutado por regional
    if(count($ppto)!=0){
      $j=0;$suma=0;
      for ($i=0; $i <=11 ; $i++) { 
        $j++;
        $suma=$suma+round($ppto[0]['m'.$j],2);
        $ppto_pi[$i]=$suma;
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
  public function get_ejec_ppto_proyecto_gestion($proyecto){
    $ppto_asig=$this->model_ptto_sigep->get_ppto_asignado_proyecto_gestion($proyecto[0]['proy_id']); // asignado gestion
    $ppto_ejec=$this->model_ptto_sigep->get_ppto_ejecutado_proyecto($proyecto[0]['proy_id']); // ejecutado gestion

    $ejec_ppto=0;
    if(count($ppto_asig)!=0 & count($ppto_ejec)!=0){
       $ejec_ppto=round(($ppto_ejec[0]['ejecutado_total']/$ppto_asig[0]['ppto_asignado'])*100,2);
    }

    return $ejec_ppto;
  }



  ///// ============== FICHA TECNICA
  /// Datos Generales - Proyectos de Inversion
  public function datos_proyecto_inversion($proy_id){
    $proyecto=$this->model_proyecto->get_id_proyecto($proy_id);
    $imagen=$this->model_proyecto->get_img_ficha_tecnica($proy_id);
    $fase = $this->model_faseetapa->get_id_fase($proy_id);
    $ppto_asig=$this->model_ptto_sigep->partidas_proyecto($proyecto[0]['aper_id']); /// lista de partidas asignados por proyectos
    $avance_financiero_gestion=$this->get_ejec_ppto_proyecto_gestion($proyecto);

    if($proyecto[0]['fecha_observacion']!=''){
      $fecha_plazo=date('d/m/Y',strtotime($proyecto[0]['fecha_observacion']));
    }
    else{
      $fecha_plazo=date('d/m/Y');
    }

    $tabla='';
     $tabla.='
      <div style="height:20px;"><b>DATOS GENERALES</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr>
            <td style="width:25%; height:15px; font-family: Arial; font-size: 10px;height:20px;" bgcolor="#e8e7e7"><b>PROYECTO DE INVERSIÓN</b></td>
            <td style="width:45%; font-family: Arial; font-size: 9px;height:20px;">'.$proyecto[0]['proy_nombre'].'</td>
            <td rowspan=8 style="width:30%;text-align:center">';
            if(count($imagen)!=0){
              if($imagen[0]['tp']==1){
                $tabla.='<img src="'.base_url().'fotos_proyectos/'.$imagen[0]['imagen'].'" class="img-responsive" style="width:230px; height:170px;"/>';
              }
              else{
                $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
              }
            }
            else{
              $tabla.='<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:150px; height:110px;"/>';
            }
          $tabla.='
            </td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>C&Oacute;DIGO SISIN</b></td>
            <td style="width:45%; font-size: 9px;">'.$proyecto[0]['proy_sisin'].'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 9.5px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>CATEGORIA PROGRAMATICA</b></td>
            <td style="width:45%; font-size: 9px;">'.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' 000</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>REGIONAL</b></td>
            <td style="width:45%; font-size: 9px;">'.strtoupper($proyecto[0]['dep_departamento']).'</td>
          </tr>
          <tr style="font-family: Arial;font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>DISTRITAL</b></td>
            <td style="width:45%; font-size: 9px;">'.strtoupper($proyecto[0]['dist_distrital']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>COSTO TOTAL DEL PROYECTO</b></td>
            <td style="width:45%; font-size: 9px;">Bs. '.number_format($proyecto[0]['proy_ppto_total'], 2, ',', '.').'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FASE</b></td>
            <td style="width:45%; font-size: 9px;">'.strtoupper($fase[0]['descripcion']).'</td>
          </tr>
        </tbody>
       </table><br>
        <div style="height:20px;"><b>OBJETIVOS DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO GENERAL</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['obj_gral']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBJETIVO ESPECIFICO</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['obj_esp']).'</td>
          </tr>
        </tbody>
       </table><br>
       <div style="height:20px;"><b>DETALLE DEL PROYECTO</b></div>
       <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <tbody>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:20%; height:15px;" bgcolor="#e8e7e7"><b>ESTADO DEL PROYECTO</b></td>
            <td style="width:55%; font-size: 9px;">'.strtoupper($proyecto[0]['ep_descripcion']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FÍSICO PROYECTO</b></td>
            <td style="width:75%; font-size: 9px;">'.round($proyecto[0]['avance_fisico'],2).' % &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; de fecha : '.date('d',strtotime($proyecto[0]['fecha_avance_fis'])).' de '.$this->mes[ltrim(date('m',strtotime($proyecto[0]['fecha_avance_fis'])), "0")]  .' de '.date('Y',strtotime($proyecto[0]['fecha_avance_fis'])).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FINANCIERO TOTAL</b></td>
            <td style="width:75%; font-size: 9px;">'.round($proyecto[0]['avance_financiero'],2).' % &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; de fecha : '.date('d',strtotime($proyecto[0]['fecha_avance_fin'])).' de '.$this->mes[ltrim(date('m',strtotime($proyecto[0]['fecha_avance_fin'])), "0")]  .' de '.date('Y',strtotime($proyecto[0]['fecha_avance_fin'])).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>AVANCE FINANCIERO GESTIÓN '.$this->gestion.'</b></td>
            <td style="width:75%; font-size: 9px;">'.$avance_financiero_gestion.' %</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>FISCAL DE OBRA</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['fiscal_obra']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>OBSERVACIÓN / COMPROMISO</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['proy_observacion']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a fecha : '.date('d',strtotime($fecha_plazo)).' de '.$this->mes[ltrim(date('m',strtotime($fecha_plazo)), "0")]  .' de '.date('Y',strtotime($fecha_plazo)).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROBLEMA IDENTIFICADO</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['desc_prob']).'</td>
          </tr>
          <tr style="font-family: Arial; font-size: 10px;">
            <td style="width:25%; height:20px;" bgcolor="#e8e7e7"><b>PROPUESTA DE SOLUCIÓN</b></td>
            <td style="width:75%; font-size: 9px;">'.strtoupper($proyecto[0]['desc_sol']).'</td>
          </tr>
        </tbody>
       </table><br>
       <div style="height:20px;"><b>EJECUCION PRESUPUESTARIA '.$this->gestion.'</b></div>';
       foreach($ppto_asig as $partida){
        $temporalidad_ejec=$this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($partida['sp_id']); /// temporalidad ejec partida
        /// ------ Datos de Modifcacion de la partida
        $monto_partida=$this->detalle_modificacion_partida($partida);
        //// -----------------------------------------

        /// montos ejecutados por partidas
        $monto_total_ejecutado=$this->model_ptto_sigep->suma_monto_ppto_ejecutado_partida($partida['sp_id']); /// monto total ejecutado
        $monto_ejecutado=0;
        if(count($monto_total_ejecutado)!=0){
          $monto_ejecutado=$monto_total_ejecutado[0]['ejecutado'];
        }

        /// Porcentaje de Avance por partidas
        $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($partida['sp_id']); /// Get partida sigep
        $porcentaje_avance_fin=0;
        if(count($get_partida_sigep)!=0){
          $porcentaje_avance_fin=round((($monto_ejecutado/$get_partida_sigep[0]['importe'])*100),2);
        }

        $tabla.='
        <div style="font-family: Arial; height:18px;font-size: 11px;"><b>PARTIDA : '.$partida['partida'].' - '.strtoupper($partida['par_nombre']).'</b></div>
        <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
          <thead>
            <tr  bgcolor="#e8e7e7" align=center>
              <th style="width:6%;height:15px;">PPTO. INICIAL</th>
              <th style="width:6%;">PPTO. MOD.</th>
              <th style="width:6%;">PPTO. VIGENTE</th>
              <th style="width:5%;">ENE.</th>
              <th style="width:6%;">FEB.</th>
              <th style="width:6%;">MAR.</th>
              <th style="width:6%;">ABR.</th>
              <th style="width:6%;">MAY.</th>
              <th style="width:6%;">JUN.</th>
              <th style="width:6%;">JUL.</th>
              <th style="width:6%;">AGO.</th>
              <th style="width:6%;">SEPT.</th>
              <th style="width:6%;">OCT.</th>
              <th style="width:6%;">NOV.</th>
              <th style="width:6%;">DIC.</th>
              <th style="width:6%;">PPTO. EJECUTADO</th>
              <th style="width:5%;">(%) EJEC. FIN.</th>
            </tr>
          </thead>
          <tbody>
            <tr style="text-align:right;">
              <td style="height:12px;">'.number_format($monto_partida[1], 0, ',', '.').'</td>
              <td>'.number_format($monto_partida[2], 0, ',', '.').'</td>
              <td>'.number_format($monto_partida[3], 0, ',', '.').'</td>';
              if(count($temporalidad_ejec)!=0){
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td>'.number_format($temporalidad_ejec[0]['m'.$i], 0, ',', '.').'</td>';
                }
                $tabla.='<td>'.number_format($temporalidad_ejec[0]['ejecutado_total'], 0, ',', '.').'</td>';
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td>0.00</td>';
                }
                $tabla.='<td>0.00</td>';
              }
              $tabla.='
              <td>'.$porcentaje_avance_fin.'%</td>
            </tr>
          </tbody>
        </table><br>';
       }

    return $tabla;
  }

  /// Cabecera Reporte Ficha Tecnica
  public function cabecera_ficha_tecnica($titulo_reporte){
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
                        <td style="height: 32%;"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                      </tr>
                      <tr style="font-size: 20px;font-family: Arial;">
                        <td style="height: 5%;">'.$titulo_reporte.'</td>
                      </tr>
                  </table>
              </td>
              <td style="width:10%; text-align:center;">
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
  function cabecera_reporte_grafico($titulo){
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
                    <td style="height: 5%; text-align:center">'.$titulo.'</td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

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