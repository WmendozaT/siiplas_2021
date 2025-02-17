<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Acortoplazo extends CI_Controller{
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
        $this->dep_id = $this->session->userData('dep_id');
        $this->conf_form4 = $this->session->userData('conf_form4');
        $this->conf_form5 = $this->session->userData('conf_form5');
    }

    /*------- TIPO --------*/
    public function titulo(){
      $tabla='';
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well">
              <h2>ACCIONES DE CORTO PLAZO - GESTI&Oacute;N '.$this->gestion.'</h2>
              <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO">
                <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO (A.C.P.)
              </a>
              <a href="javascript:abreVentana(\''.site_url("").'/me/rep_ogestion/0\');" title="IMPRIMIR A.C.P." class="btn btn-default">
                <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. A.C.P. (FORM N° 1)
              </a>
              <a href="javascript:abreVentana(\''.site_url("").'/me/rep_ogestion/1\');" title="IMPRIMIR A.C.P. CON PPTO." class="btn btn-default">
                <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. A.C.P. (FORM N° 1 - PPTO)
              </a>';
              if($this->fun_id==399){
                $tabla.='&nbsp;<a href="#" data-toggle="modal" data-target="#modal_importar" class="btn btn-default" title="SUBIR ARCHIVO - PDES" >SUBIR OPERACIONES REGIONALES</a><br><br>';
              }
            $tabla.='
            </div>
        </article>';

      return $tabla;
    } 

    /*---------- LISTA MIS OBJETIVOS DE GESTION (ACCION DE CORTO PLAZO)------------*/
    public function mis_ogestion_gral(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// ACCIONES DE CORTO PLAZO

      $tabla ='<input type="hidden" name="base" value="'.base_url().'">';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-darken" >
                  <header>
                    <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                    <h2 class="font-md"><strong></strong></h2>  
                  </header>
                <div>
                  
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th style="width:1%;">NRO</th>
                          <th style="width:1%;">M/E</th>
                          <th style="width:2%;">OPERACIONES '.$this->gestion.'</th>
                          <th style="width:2%;">REPORTE OPERACIONES</th>
                          <th style="width:2%;">REP. ALINEACIÓN</th>
                          <th style="width:2%;">COD. O.E.</th>
                          <th style="width:2%;">COD. A.C.P.</th>
                          <th style="width:10%;">ACCIÓN DE CORTO PLAZO '.$this->gestion.'</th>
                          <th style="width:10%;">PRODUCTO</th>
                          <th style="width:10%;">RESULTADO</th>
                          <th style="width:5%;">TP. INDI.</th>
                          <th style="width:7%;">INDICADOR</th>
                          <th style="width:4%;">LINEA BASE</th>
                          <th style="width:4%;">META</th>
                          <th style="width:4%;" title="CHUQUISACA">CH.</th>
                          <th style="width:4%;" title="LA PAZ">LPZ.</th>
                          <th style="width:4%;" title="COCHABAMBA">CBBA.</th>
                          <th style="width:4%;" title="ORURO">OR.</th>
                          <th style="width:4%;" title="POTOSI">POT.</th>
                          <th style="width:4%;" title="TARIJA">TJA.</th>
                          <th style="width:4%;" title="SANTA CRUZ">SCZ.</th>
                          <th style="width:4%;" title="BENI">BE.</th>
                          <th style="width:4%;" title="PANDO">PN</th>
                          <th style="width:4%;" title="OFICINA NACIONAL">OFN</th>
                          <th style="width:10%;">MEDIO VERIFICACI&Oacute;N</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($ogestion  as $row){
                          $nro++;
                          $tabla .='<tr title='.$row['og_id'].'>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff"  title="MODIFICAR ACP" name="'.$row['og_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR ACP"  name="'.$row['og_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a><br>';
                            $tabla .='</td>';
                            $tabla .='
                            <td bgcolor="#cef3ee" align=center><br>
                              <a href="'.site_url("").'/me/objetivos_regionales/'.$row['og_id'].'" class="btn btn-default" title="OBJETIVOS REGIONALES"><img src="'.base_url().'assets/img/folder.png" WIDTH="30" HEIGHT="30"/></a>
                            </td>';
                            $tabla .='<td bgcolor="#cef3ee" align=center><br><a href="javascript:abreVentana(\''.site_url("").'/me/rep_oregionales/'.$row['og_id'].'\');" title="GENERAR REPORTE PDF" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></a></td>';
                            $tabla .='<td align=center><br><a href="'.site_url("").'/me/alineacion_ope_acp/'.$row['og_id'].'" target="_blank" class="btn btn-default" title="VER ALINEACION ACP-FORM4"><img src="'.base_url().'assets/Iconos/application_double.png" WIDTH="30" HEIGHT="30"/></a></td>';
                            $tabla .='<td><b><font size=4><center>'.$row['obj_codigo'].'</center></font></b></td>';
                            $tabla .='<td><b><font color=blue size=4><center>'.$row['og_codigo'].'</center></font></b></td>';
                            $tabla .='<td>'.$row['og_objetivo'].'</td>';
                            $tabla .='<td>'.$row['og_producto'].'</td>';
                            $tabla .='<td>'.$row['og_resultado'].'</td>';
                            $tabla .='<td>'.strtoupper($row['indi_descripcion']).'</td>';
                            $tabla .='<td>'.$row['og_indicador'].'</td>';
                            $tabla .='<td>'.$row['og_linea_base'].'</td>';
                            $tabla .='<td>'.$row['og_meta'].'</td>';
                            
                            for ($i=1; $i <=10 ; $i++) { 
                              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
                              if(count($dep)!=0){
                                $tabla.='<td bgcolor="#e6f5e0"><b>'.round($dep[0]['prog_fis'],2).'</b></td>';
                              }
                              else{
                                $tabla.='<td bgcolor="#e6f5e0"><b>0</b></td>';
                              }
                            }
                            $tabla.='<td>'.$row['og_verificacion'].'</td>';
                          $tabla.='</tr>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';

      return $tabla;
    }


    /// Cabecera Reporte ACP
    public function cabecera_acp(){
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
                          <td style="height: 5%;">ACCIONES DE CORTO PLAZO</td>
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
                <td style="width:30%; height: 3%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                      <td align=center style="width:100%;height: 10%;"><b>FORMULARIO SPO N° 1 </b></td>
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
        </table>';

      return $tabla;
    }


//// Pie Acciones form 1
  public function pie_form1(){ 
    $tabla='';
    $tabla.='
        <table style="width:98%;font-size: 6px;font-family: Arial;" align="center" border="0">
        <tr>
            <td style="width:98%;"><b>COD. OE. : </b>C&oacute;digo Objetivo Estrategico Institucional</td>
        </tr>
        <tr>
            <td><b>COD. ACP. : </b>C&oacute;digo Acci&oacute;n de Corto Plazo</td>
        </tr>
    </table>
    <hr>
    <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:97%;" align="center">
      <tr>
        <td style="width: 30%;">
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr style="font-size: 10px;font-family: Arial;">
                    <td style="width:100%;height:15px;"><b>ELABORADO POR<br></b></td>
                </tr>
                <tr style="font-size: 8.5px;font-family: Arial;" align="center">
                    <td><b><br><br><br><br><br>FIRMA</b></td>
                </tr>
            </table>
        </td>
        <td style="width: 70%;">
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr style="font-size: 10px;font-family: Arial;">
                    <td style="width:100%; height:15px;"><b>APROBADO POR<br></b></td>
                </tr>
                <tr style="font-size: 8.5px;font-family: Arial;" align="center">
                    <td><b><br><br><br><br><br>FIRMA</b></td>
                </tr>
            </table>
        </td>
      </tr>
    </table>
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


    /// FORM 1 Distribucion Regional 2023
    public function distribucion_regional_2023(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// LISTA FORM 1 (ACP)
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <thead>
          <tr style="font-size: 6.5px;" bgcolor="#eceaea" align=center>
            <th style="width:0.5%;height:20px;">#</th>
            <th style="width:2%;">COD. O.E.</th>
            <th style="width:2%;">COD. ACP.</th>
            <th style="width:13%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
            <th style="width:12%;">PRODUCTO</th>
            <th style="width:12%;">RESULTADO</th>
            <th style="width:12%;">INDICADOR</th>
            <th style="width:3%;">LINEA BASE</th>
            <th style="width:3%;">META</th>
            <th style="width:3%;" title="CHUQUISACA">CH.</th>
            <th style="width:3%;" title="LA PAZ">LPZ.</th>
            <th style="width:3%;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3%;" title="ORURO">OR.</th>
            <th style="width:3%;" title="POTOSI">POT.</th>
            <th style="width:3%;" title="TARIJA">TJA.</th>
            <th style="width:3%;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3%;" title="BENI">BE.</th>
            <th style="width:3%;" title="PANDO">PAN.</th>
            <th style="width:3%;" title="OFICINA NACIONAL">OFN.</th>
            <th style="width:9%;">MEDIO VERIFICACI&Oacute;N</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
           $tabla .='<tr style="font-size: 6px;">';
            $tabla .='<td style="width:0.5%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center" align="center"><b>'.$row['obj_codigo'].'</b></td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:13%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:12%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:12%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:12%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:3%; text-align: right;">'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:3%; text-align: right;">'.round($row['og_meta'],2).''.$prc.'</td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3%; text-align: right;">'.round($dep[0]['prog_fis'],2).''.$prc.'</td>';
              }
              else{
                $tabla.='<td style="width:3%;" bgcolor="#f5f5f5" align=center>0</td>';
              }
            }
            $tabla.='<td style="width:10%;">'.$row['og_verificacion'].'</td>';
          $tabla.='</tr>';

        }
        $tabla.='
        </tbody>
       </table>';

      return $tabla;
    }

    /// FORM 1 Distribucion Regional 2024
    public function distribucion_regional_2024(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// LISTA FORM 1 (ACP)
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <thead>
          <tr style="font-size: 6.5px;" bgcolor="#eceaea" align=center>
            <th style="width:0.5%;height:20px;">#</th>
            <th style="width:2%;">COD. O.E.</th>
            <th style="width:2%;">COD. ACP.</th>
            <th style="width:12%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
            <th style="width:11%;">PRODUCTO</th>
            <th style="width:10.5%;">RESULTADO</th>
            <th style="width:10%;">INDICADOR</th>
            <th style="width:8%;">FORMULA</th>
            <th style="width:3%;">LINEA BASE</th>
            <th style="width:3%;">META</th>
            <th style="width:3%;" title="CHUQUISACA">CH.</th>
            <th style="width:3%;" title="LA PAZ">LPZ.</th>
            <th style="width:3%;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3%;" title="ORURO">OR.</th>
            <th style="width:3%;" title="POTOSI">POT.</th>
            <th style="width:3%;" title="TARIJA">TJA.</th>
            <th style="width:3%;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3%;" title="BENI">BE.</th>
            <th style="width:3%;" title="PANDO">PAN.</th>
            <th style="width:3%;" title="OFICINA NACIONAL">OFN.</th>
            <th style="width:8%;">MEDIO VERIFICACI&Oacute;N</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
          $tabla .='<tr style="font-size: 6px;">';
            $tabla .='<td style="width:0.5%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center" align="center"><b>'.$row['obj_codigo'].'</b></td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:10%;">'.$row['og_objetivo'].'</td>';
            
            $tabla .='<td style="width:5%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:8%;">'.$row['og_formula'].'</td>';
            $tabla .='<td style="width:2.5%; text-align: right;">'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:3%; text-align: right; font-size:9px;"><b>'.round($row['og_meta'],2).''.$prc.'</b></td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3%; text-align: right;">'.round($dep[0]['prog_fis'],2).''.$prc.'</td>';
              }
              else{
                $tabla.='<td style="width:3%;" bgcolor="#f5f5f5" align=center>0</td>';
              }
            }
            $tabla.='<td style="width:8%;">'.$row['og_verificacion'].'</td>';
          $tabla.='</tr>';

        }
        $tabla.='
        </tbody>
       </table>';

      return $tabla;
    }

    /// FORM 1 distribucion con presupuesto
    public function distribucion_regional_ppto(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// OBJETIVOS DE GESTION GENERAL
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <thead>
          <tr style="font-size: 6.5px;" bgcolor="#eceaea" align=center>
            <th style="width:0.5%;height:20px;">#</th>
            <th style="width:2%;">COD. O.E.</th>
            <th style="width:2%;">COD. ACP.</th>
            <th style="width:12%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
            <th style="width:11%;">PRODUCTO</th>
            <th style="width:10.5%;">RESULTADO</th>
            <th style="width:10%;">INDICADOR</th>
            <th style="width:3%;">LINEA BASE</th>
            <th style="width:3%;">META</th>
            <th style="width:3%;" title="CHUQUISACA">CH.</th>
            <th style="width:3%;" title="LA PAZ">LPZ.</th>
            <th style="width:3%;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3%;" title="ORURO">OR.</th>
            <th style="width:3%;" title="POTOSI">POT.</th>
            <th style="width:3%;" title="TARIJA">TJA.</th>
            <th style="width:3%;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3%;" title="BENI">BE.</th>
            <th style="width:3%;" title="PANDO">PAN.</th>
            <th style="width:3%;" title="OFICINA NACIONAL">OFN.</th>
            <th style="width:10%;">MEDIO VERIFICACI&Oacute;N</th>
            <th style="width:6%;">PPTO.<br>'.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
          $presupuesto=$this->model_objetivogestion->get_ppto_ogestion_gc($row['og_id']); // ppto Gasto Corriente + pI
          
            $ppto=0;
            if(count($presupuesto)!=0){
              $ppto=$presupuesto[0]['presupuesto'];
            }

            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
          $tabla .='<tr style="font-size: 6px;">';
            $tabla .='<td style="width:0.5%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center" align="center"><b>'.$row['obj_codigo'].'</b></td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:12%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:11%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:10.5%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:3%; text-align: right;">'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:3%; text-align: right;">'.round($row['og_meta'],2).''.$prc.'</td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3%; text-align: right;">'.round($dep[0]['prog_fis'],2).''.$prc.'</td>';
              }
              else{
                $tabla.='<td style="width:3%;" bgcolor="#f5f5f5" align=center>0</td>';
              }
            }
            $tabla.='<td style="width:10%;">'.$row['og_verificacion'].'</td>';
            $tabla.='<td style="width:6%; text-align: right;">'.number_format($ppto, 2, ',', '.').'</td>';
          $tabla.='</tr>';

          $monto_total=$monto_total+$ppto;
        }
        $tabla.='
        </tbody>
        <tr>
          <td style="height:11px; text-align: right;" colspan=20><b>PRESUPUESTO TOTAL : </b></td>
          <td style="text-align: right;">'.number_format($monto_total, 2, ',', '.').'</td>
        </tr>
       </table>';

      return $tabla;
    }

    /*----- Distribucion Mensual -----*/
    public function distribucion_mensual(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// OBJETIVOS DE GESTION GENERAL
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <thead>
          <tr style="font-size: 6.5px;" bgcolor="#eceaea" align=center>
            <th style="width:0.5%;height:20px;">#</th>
            <th style="width:2%;">COD. O.E.</th>
            <th style="width:2%;">COD. ACP.</th>
            <th style="width:10%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
            
            <th style="width:8%;">PRODUCTO</th>
            <th style="width:9%;">RESULTADO</th>
            <th style="width:9%;">INDICADOR</th>
            <th style="width:8%;">UNIDAD MEDIDA</th>
            <th style="width:9%;">FORMULA</th>
            <th style="width:2%;">LINEA BASE</th>
            <th style="width:2.5%;">META</th>
            <th style="width:2.5%;" title="">ENE.</th>
            <th style="width:2.5%;" title="">FEB.</th>
            <th style="width:2.5%;" title="">MAR.</th>
            <th style="width:2.5%;" title="">ABR.</th>
            <th style="width:2.5%;" title="">MAY.</th>
            <th style="width:2.5%;" title="">JUN.</th>
            <th style="width:2.5%;" title="">JUL.</th>
            <th style="width:2.5%;" title="">AGO.</th>
            <th style="width:2.5%;" title="">SEP.</th>
            <th style="width:2.5%;" title="">OCT.</th>
            <th style="width:2.5%;" title="">NOV.</th>
            <th style="width:2.5%;" title="">DIC.</th>
            <th style="width:8%;">MEDIO VERIFICACI&Oacute;N</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
          $tabla .='<tr style="font-size: 6px;">';
            $tabla .='<td style="width:0.5%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['obj_codigo'].'</b></td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:10%;">'.$row['og_objetivo'].'</td>';
            
            $tabla .='<td style="width:8%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:8%;">'.$row['og_observacion'].'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_formula'].'</td>';
            $tabla .='<td style="width:2%; text-align: right;">'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:2.5%; text-align: right; font-size:9px;"><b>'.round($row['og_meta'],2).''.$prc.'</b></td>';
            $prog_temp=$this->model_objetivogestion->get_objetivosgestion_temporalidad_mensual($row['og_id']);

            if(count($prog_temp)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width:2.5%; text-align: right;">'.round($prog_temp[0]['m'.$i],2).'</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width:2.5%; text-align: right;">0</td>';
              }
            }
            $tabla.='<td style="width:8%;">'.$row['og_verificacion'].'</td>';
          $tabla.='</tr>';
        }
        $tabla.='
        </tbody>
       </table>';

      return $tabla;
    }

    //// con presupuesto 
/* public function distribucion_mensual2(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// OBJETIVOS DE GESTION GENERAL
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;">
        <thead>
          <tr style="font-size: 6.5px;" bgcolor="#eceaea" align=center>
            <th style="width:1%;height:20px;">#</th>
            <th style="width:2%;">COD. O.E.</th>
            <th style="width:2%;">COD. ACE.</th>
            <th style="width:2%;">COD. ACP.</th>
            <th style="width:11%;">ACCI&Oacute;N DE CORTO PLAZO '.$this->gestion.'</th>
            <th style="width:10%;">PRODUCTO</th>
            <th style="width:10%;">RESULTADO</th>
            <th style="width:10%;">INDICADOR</th>
            <th style="width:2%;">LINEA BASE</th>
            <th style="width:3%;">META</th>
            <th style="width:2.5%;" title="">ENE.</th>
            <th style="width:2.5%;" title="">FEB.</th>
            <th style="width:2.5%;" title="">MAR.</th>
            <th style="width:2.5%;" title="">ABR.</th>
            <th style="width:2.5%;" title="">MAY.</th>
            <th style="width:2.5%;" title="">JUN.</th>
            <th style="width:2.5%;" title="">JUL.</th>
            <th style="width:2.5%;" title="">AGO.</th>
            <th style="width:2.5%;" title="">SEP.</th>
            <th style="width:2.5%;" title="">OCT.</th>
            <th style="width:2.5%;" title="">NOV.</th>
            <th style="width:2.5%;" title="">DIC.</th>
            <th style="width:10%;">MEDIO VERIFICACI&Oacute;N</th>
            <th style="width:6%;">PPTO.<br>'.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
          $presupuesto_gc=$this->model_objetivogestion->get_ppto_ogestion_gc($row['og_id']); // ppto Gasto Corriente
          
            $ppto_gc=0;$ppto_pi=0;
            if(count($presupuesto_gc)!=0){
              $ppto_gc=$presupuesto_gc[0]['presupuesto'];
            }

            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
          $tabla .='<tr style="font-size: 6px;">';
            $tabla .='<td style="width:1%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2%;" align="center">'.$row['obj_codigo'].'</td>';
            $tabla .='<td style="width:2%;" align="center"></td>';
            $tabla .='<td style="width:2%; font-size: 10px;" align="center"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:11%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:11%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:2%; text-align: right;">'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:3%; text-align: right;">'.round($row['og_meta'],2).''.$prc.'</td>';
            $prog_temp=$this->model_objetivogestion->get_objetivosgestion_temporalidad_mensual($row['og_id']);

            if(count($prog_temp)!=0){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width:2.5%; text-align: right;">'.round($prog_temp[0]['m'.$i],2).'</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td style="width:2.5%; text-align: right;">0</td>';
              }
            }
            $tabla.='<td style="width:10%;">'.$row['og_verificacion'].'</td>';
            $tabla.='<td style="width:6%; text-align: right;">'.number_format($ppto_gc, 2, ',', '.').'</td>';
          $tabla.='</tr>';

          $monto_total=$monto_total+$ppto_gc;
        }
        $tabla.='
        </tbody>
        <tr>
          <td style="height:10px; text-align: right;" colspan=23><b>PRESUPUESTO TOTAL : </b></td>
          <td style="text-align: right;">'.number_format($monto_total, 2, ',', '.').'</td>
        </tr>
       </table>';

      return $tabla;
    }*/
    /*-------- Cbecera Alineacion ACP - OPE - Form 4 ------*/
    public function cabecera_alineacion_acp($ogestion){
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
                            <td style="height: 35%;"><b>PLAN OPERATIVO ANUAL GESTI&Oacute;N - '.$this->gestion.'</b></td>
                        </tr>
                        <tr style="font-size: 20px;font-family: Arial;">
                          <td style="height: 2%;">ALINEACI&Oacute;N ACCI&Oacute;N DE CORTO PLAZO '.$ogestion[0]['og_codigo'].' - '.$this->gestion.'</td>
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
                                <tr><td style="width:100%;height: 50%;" bgcolor="#f9f9f9">&nbsp;<b></td></tr>
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


  //// Lista Alineacion ACP - OPE - Form 4 2022
  public function rep_lista_alineacion_poa($og_id,$dep_id){ 

    if($dep_id==0){
      $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades($og_id); /// ALINEACION ACP a ACT. (Todos)
    }
    else{
      $alienacion_acp =$this->model_objetivogestion->vinculacion_acp_actividades_regional($og_id,$dep_id); 
    }
    
    $tabla='';

    $tabla.='
    
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
      <thead>
        <tr style="font-size: 6.7px;" bgcolor="#eceaea" align=center>
          <th style="width:1%;height:20px;">#</th>
          <th style="width:8%;">REGIONAL</th>
          <th style="width:2.5%;"><b>COD. ACP.</b></th>
          <th style="width:2.5%;"><b>COD. OPE.</b></th>
          <th style="width:15%;">OPERACI&Oacute;N REGIONAL '.$this->gestion.'</th>
          <th style="width:10%;">INDICADOR</th>
          <th style="width:10%;">RESULTADO</th>
          <th style="width:3%;">META</th>
          <th style="width:3%;">COD. ACT.</th>
          <th style="width:15%;">ACTIVIDAD</th>
          <th style="width:12%;">INDICADOR</th>
          <th style="width:5%;">META</th>
          <th style="width:12%;">MEDIO DE VERIFICACI&Oacute;N</th>
        </tr>
      </thead>
      <tbody>';
    $nro=0;
    foreach($alienacion_acp  as $row){
      $por=''; $nro++; 
      if($row['indi_id']==2){
        $por='%';
      }
      $tabla.='
      <tr style="font-size: 6.5px;">
        <td style="width:1%; height:17px; text-align:center">'.$nro.'</td>
        <td style="width:8%;"><b>'.strtoupper($row['dep_departamento']).'</b></td>
        <td style="width:2.5%;font-size: 9px;" align=center><b>'.$row['og_codigo'].'</b></td>
        <td style="width:2.5%;font-size: 9px;" align=center><b>'.$row['or_codigo'].'</b></td>
        <td style="width:15%;">'.strtoupper($row['or_objetivo']).'</td>
        <td style="width:10%;">'.strtoupper($row['or_indicador']).'</td>
        <td style="width:10%;">'.strtoupper($row['or_resultado']).'</td>
        <td style="width:3%; font-size: 10px;"align=right>'.round($row['or_meta'],2).'</td>

        <td style="width:3%; font-size: 10px; text-align:center"><b>'.strtoupper($row['prod_cod']).'</b></td>
        <td style="width:15%;">'.strtoupper($row['prod_producto']).'</td>
        <td style="width:12%;">'.strtoupper($row['prod_indicador']).'</td>
        <td style="width:5%; font-size: 8px;" align=right><b>'.round($row['prod_meta'],2).' '.$por.'</b></td>
        <td style="width:12%;">'.strtoupper($row['prod_fuente_verificacion']).'</td>
      </tr>';
    }
    $tabla.='
    </tbody>
    </table>';

    return $tabla;
  }


    /*---------- Pie de Reporte -----------*/
    public function pie_rep_alineacion_acp(){
      $tabla='';
      $tabla.='
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr style="font-size: 7px;font-family: Arial;">
                <td style="text-align: left;height: 2.5%;width:50%;">
                    '.$this->session->userdata('sistema').'
                </td>
                <td style="width: 50%; text-align: right">
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