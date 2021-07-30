<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Programacionpoa extends CI_Controller{
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
            $this->load->model('mestrategico/model_objetivoregion');
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
            $this->mes = $this->mes_nombre();
            $this->conf_form4 = $this->session->userData('conf_form4');
            $this->conf_form5 = $this->session->userData('conf_form5');
    }


     /*------ GET POA -----*/
    public function mi_poa($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th >NRO.</th>
                    <th >SERVICIO / COMPONENTE </th>
                    <th >PONDERACI&Oacute;N</th>';
                      if($this->gestion>2020){
                        $tabla.='<th >OPERACIONES<br>FORM. N 4</th>';
                      }
                      else{
                        $tabla.='<th >ACTIVIDADES<br>FORM. N 4</th>';
                      }
                    $tabla.='
                    <th >REQUERIMIENTOS<br>FORM. N 5</th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nroc=0; $nro_ppto=0;
                    $procesos=$this->model_componente->lista_subactividad($proy_id);
                    foreach($procesos  as $pr){
                      if(count($this->model_producto->list_prod($pr['com_id']))!=0){
                        $nroc++;
                        $tabla.=
                          '<tr>
                            <td>'.$nroc.'</td>
                            <td>'.$pr['serv_cod'].' '.$pr['tipo_subactividad'].' '.$pr['serv_descripcion'].'</td>
                            <td align=center>'.round($pr['com_ponderacion'],2).'%</td>
                            <td align=center>
                              <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$pr['com_id'].'\');" title="REPORTE OPERACIONES"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>
                            </td>
                            <td align=center>';
                              if(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($nro_ppto>0){
                      $tabla.='
                      <tr>
                        <td colspan=4><b>CONSOLIDADO PROGRAMADO PRESUPUESTO TOTAL POR PARTIDAS </b></td>
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                      $partidas_asig=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],1);
                      if(count($partidas_asig)!=0 & $proyecto[0]['proy_estado']==4){ //// POA APROBADO
                        $tabla.='
                      <tr bgcolor="#d6ecb3">
                        <td colspan=4><b>CONSOLIDADO PRESUPUESTO COMPARATIVO APROBADO TOTAL POR PARTIDAS </b></td> 
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                      }
                    }
                  $tabla.='
                  
                </table>';

      return $tabla;
    }




    /*------ GET POA PARA AJUSTE -----*/
    public function mi_poa_ajuste($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $tabla='';
      $tabla.=' <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th bgcolor="#f5f5f5">NRO.</th>
                    <th bgcolor="#f5f5f5">UNIDAD / COMPONENTE </th>
                    <th bgcolor="#f5f5f5">PONDERACI&Oacute;N</th>
                    <th bgcolor="#f5f5f5">ACTIVIDADES</th>
                    <th bgcolor="#f5f5f5">FORM. N 4</th>
                    <th bgcolor="#f5f5f5">REQUERIMIENTOS</th>
                    <th bgcolor="#f5f5f5">REQUERIMIENTOS<br>FORM. N 5</th>
                  </tr>
                  </thead>
                  <tbody>';
                  $nroc=0; $nro_ppto=0;
                    $procesos=$this->model_componente->proyecto_componente($proy_id);
                    foreach($procesos  as $pr){
                      if(count($this->model_producto->list_prod($pr['com_id']))!=0){
                        $nroc++;
                        $tabla.=
                          '<tr>
                            <td>'.$nroc.'</td>
                            <td>'.$pr['com_componente'].'</td>
                            <td align=center>'.round($pr['com_ponderacion'],2).'%</td>
                            <td align=center>
                              <center><a href="'.site_url("").'/admin/prog/list_prod/'.$pr['com_id'].'" title="MODIFICAR DATOS POA " class="btn btn-default" target="_blank"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="34" HEIGHT="30"/></a></center>
                            </td>
                            <td align=center>
                              <a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$pr['com_id'].'\');" title="REPORTE FORM 4" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>
                            </td>
                            <td align=center>
                              <a href="'.site_url("").'/prog/list_requerimiento/'.$pr['com_id'].'" target="_blank" title="REQUERIMIENTOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="35" HEIGHT="35"/></a>
                            </td>
                            <td align=center>';
                              if(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                                $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$proy_id.'/'.$pr['com_id'].'\');" title="REPORTE REQUERIMIENTOS POR PROCESOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="34" HEIGHT="30"/></a>';
                                $nro_ppto++;
                              } 
                            $tabla.='
                            </td>
                          </tr>';
                      }
                      
                    }
                  $tabla.='</tbody>';
                    if($nro_ppto>0){
                      $tabla.='
                      <tr>
                        <td colspan=6><b>CONSOLIDADO PROGRAMADO PRESUPUESTO TOTAL POR PARTIDAS </b></td>
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>
                      <tr bgcolor="#d6ecb3">
                        <td colspan=6><b>CONSOLIDADO PRESUPUESTO COMPARATIVO APROBADO TOTAL POR PARTIDAS </b></td> 
                        <td align=center><a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$proy_id.'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a></td>
                      </tr>';
                    }
                  $tabla.='
                  
                </table>';

      return $tabla;
    }




    /*--- TIPO DE RESPONSABLE ---*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='<h3>RESPONSABLE : '.$this->session->userdata('funcionario').' -> <small>RESPONSABLE NACIONAL</h3>';
      }
      elseif($this->adm==2){
        $titulo='<h3>RESPONSABLE : '.$this->session->userdata('funcionario').' -> <small>RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']).'</h3>';
      }

      return $titulo;
    }

    /*--- ESTILO ---*/
    public function estilo_tabla(){
      $tabla='';
      $tabla.='
        <style>
          .table1{
                display: inline-block;
                width:100%;
                max-width:1550px;
                overflow-x: scroll;
                }
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
                #mdialTamanio{
                  width: 45% !important;
                }
                #mdialTamanio2{
                  width: 35% !important;
                }
          </style>';

      return $tabla;
    }

  ///// ============= FORMULARIO N° 4 

  /*--- ACTUALIZA CODIGO DE ACTIVIDAD (FORM 4) ----*/
  public function update_codigo_actividad($com_id){  
    $productos = $this->model_producto->lista_operaciones($com_id,$this->gestion); // Lista de productos
    $nro=0;
    foreach($productos as $row){
      $nro++;
      $update_prod= array(
        'prod_cod' => $nro,
        'fun_id' => $this->fun_id
      );
      $this->db->where('prod_id', $row['prod_id']);
      $this->db->update('_productos', $update_prod);
    }
  }

    /*--- BOTON REPORTE SEGUIMIENTO POA (MES VIGENTE)---*/
    function button_form4($nro){
      $tabla='';
      if($this->tp_adm==1 || $this->conf_form4==1){
        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_nuevo_form" class="btn btn-default nuevo_form" title="NUEVO REGISTRO FORM N 4" class="btn btn-success">
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="MODIFICAR REGISTRO" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR NUEVAS ACTIVIDADES.CSV
                  </a>';

        if($nro!=0){
          $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="2" title="SUBIR ARCHIVO REQUERIMIENTO (GLOBAL)" >
                      <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS (GLOBAL)
                    </a>';
        }
      }

      $tabla.='<br><br>';
      
      return $tabla;
    }

    /*--- LISTA DE OBJETIVO REGIONAL (GASTO CORRIENTE )-----*/
    public function lista_oregional($proy_id){
      $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
      $tabla='';
      if(count($list_oregional)==1){
        $tabla.=' <section class="col col-3">
                    <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                      <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                    </label>
                  </section>'; 
      }
      else{
          $tabla.='<section class="col col-6">
                  <label class="label"><b>OBJETIVO REGIONAL</b></label>
                    <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                      <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                      foreach($list_oregional as $row){ 
                        $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                      }
                    $tabla.='
                  </select>
                </section>'; 
      }
         
      return $tabla;
    }

    /*---- LISTA DE OBJETIVO REGIONAL (PROYECTO DE INVERSION)-----*/
    public function lista_oregional_pi($proy_id){
      $list_oregional= $this->model_objetivoregion->get_unidad_pregional_programado($proy_id);
      $tabla='';
      if(count($list_oregional)==1){
        $tabla.=' <section class="col col-6">
                    <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                      <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                    </label>
                  </section>'; 
      }
      else{
          $tabla.='<section class="col col-6">
                  <label class="label"><b>OBJETIVO REGIONAL</b></label>
                    <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                      <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                      foreach($list_oregional as $row){ 
                        $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                      }
                    $tabla.='
                  </select>
                </section>'; 
      }
         
      return $tabla;
    }

    /*----------- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
    public function verif_oregional($proy_id){
      $list_oregional=$this->model_objetivoregion->get_unidad_pregional_programado($proy_id);

      $tabla='';
      $nro=0;
      if(count($list_oregional)!=0){
        foreach($list_oregional as $row){
          $nro++;
          $tabla.='<h1 title='.$row['or_id'].'> '.$nro.'.- OBJETIVO REGIONAL : <small> <b>'.$row['or_codigo'].'</b>.- '.$row['or_objetivo'].'</small></h1>';
        }
      }
      else{
        $tabla.='<h1><small><font color=red>NO ALINEADO A NINGUN OBJETIVO REGIONAL</font></small></h1>';
      }
      
      return $tabla;
    }

    /*--- ESTILO FORM 4---*/
    public function estilo_tabla_form4(){
      $tabla='';
      $tabla.='
      <style type="text/css">
        aside{background: #05678B;}
        #mdialTamanio{
            width: 80% !important;
        }
        #mdialTamanio2{
            width: 50% !important;
        }
        table{font-size: 10px;
              width: 100%;
              max-width:1550px;;
              overflow-x: scroll;
              }
        input[type="checkbox"] {
          display:inline-block;
          width:28px;
          height:28px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
        th {font-size: 10px; }

        input[type="checkbox"] {
          display:inline-block;
          width:25px;
          height:25px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
      </style>';

      return $tabla;
    }


/// ===== FORMULARIO N5


    /*--- DISTRIBUCION FINANCIERA ---*/
    function distribucion_financiera($insumo){
      $prog=$this->model_insumo->list_temporalidad_insumo($insumo[0]['ins_id']); /// Temporalidad Requerimiento 2020
        for ($i=0; $i <=12 ; $i++) { 
          if($i==0){
            $titulo[$i]='programado_total';  
          }
          else{
            $titulo[$i]='mes'.$i.''; 
          }

          $temporalidad[$i]=0;
        }

        if(count($prog)!=0){
          for ($i=0; $i <=12 ; $i++) { 
            $temporalidad[$i]= round($prog[0][$titulo[$i]],2);
          }
        }

      return $temporalidad;
    }

    /*--- PARTIDAS DEPENDIENTES ---*/
    function partidas_dependientes($insumo){
      $tabla='';
      $get_partida=$this->model_partidas->get_partida($insumo[0]['par_id']); /// datos de la partda
      $lista_partidas=$this->model_partidas->lista_par_hijos($get_partida[0]['par_depende']);
      foreach ($lista_partidas as $row) {
        if($insumo[0]['par_id']==$row['par_id']){
          $tabla.='<option value="'.$row['par_id'].'" selected>'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
        else{
          $tabla.='<option value="'.$row['par_id'].'">'.$row['par_codigo'].'.- '.$row['par_nombre'].'</option>';
        }
      }

      return $tabla;
    }

    /*--- LISTA DE UNIDADES DE MEDIDA ---*/
    function unidades_medida($insumo){
      $tabla='';
      $lista_umedida=$this->model_insumo->lista_umedida($insumo[0]['par_id']); /// Lista de Unidades de medida

      foreach ($lista_umedida as $row) {
        if($insumo[0]['ins_unidad_medida']==$row['um_descripcion']){
          $tabla.='<option value="'.$row['um_id'].'" selected>'.$row['um_descripcion'].'</option>';
        }
        else{
          $tabla.='<option value="'.$row['um_id'].'">'.$row['um_descripcion'].'</option>';
        }
      }

      return $tabla;
    }

        /*--- LISTA DE PRODUCTOS, ACTIVIDADES (MOD) ---*/
    function list_prod_actividad($com_id,$insumo){
      $tabla='';

        $operaciones=$this->model_producto->lista_operaciones($com_id);
        $tabla.='<option value="">Seleccione Actividad</option>';
        foreach($operaciones as $row){
          if($row['prod_id']==$insumo[0]['prod_id']){
            $tabla.='<option value="'.$row['prod_id'].'" selected>ACT. '.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
          else{
            $tabla.='<option value="'.$row['prod_id'].'">ACT. '.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
          }
        } 

      return $tabla;
    }
    

    /*--- BOTON ESTADO FORM 5---*/
    function button_form5(){
      $tabla='';
      if($this->tp_adm==1 || $this->conf_form5==1){
        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff" title="NUEVO REGISTRO FORM N 5" class="btn btn-success">
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="IMPORTAR REQUERIMIENTOS" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS.CSV
                  </a>';
      }

      $tabla.='<br><br>';
      
      return $tabla;
    }

    /*--- ESTILO FORM 5---*/
    public function estilo_tabla_form5(){
      $tabla='';
      $tabla.='
      <style>
      aside{background: #05678B;}
      .table1{
            display: inline-block;
            width:100%;
            max-width:1550px;
            overflow-x: scroll;
            }
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
            #mdialTamanio{
          width: 80% !important;
        }
        #mdialTamanio2{
          width: 55% !important;
        }
        input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
            }
      </style>';

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