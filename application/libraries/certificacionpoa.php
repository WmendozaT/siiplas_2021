<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Certificacionpoa extends CI_Controller{
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
    }

//// ADMINISTRADOR 
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

  /*------ TITULO CABECERA (2020)-----*/
  public function titulo_cabecera($datos){
    $tabla='';
    if($datos[0]['tp_id']==1){ /// Proyecto de Inversion
      $tabla.=' <h1><b>PROYECTO INVERSIÓN: </b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['proy_sisin'].' - '.$datos[0]['proy_nombre'].'</small>
                <h1><b>UNIDAD RESPONSABLE : </b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
    }
    else{ /// Gasto Corriente
      $tabla.=' <h1><b>'.$datos[0]['tipo_adm'].' : <b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['aper_proyecto'].' '.$datos[0]['aper_actividad'].' - '.$datos[0]['tipo'].' '.$datos[0]['act_descripcion'].' '.$datos[0]['abrev'].'</small></h1>
                <h1><b>UNIDAD RESPONSABLE : <b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
    }


    return $tabla;
  }

  /*---- TIPO DE RESPONSABLE ---*/
  public function tp_resp(){
    $ddep = $this->model_proyecto->dep_dist($this->dist);
    if($this->adm==1){
      $titulo='RESPONSABLE NACIONAL';
    }
    elseif($this->adm==2){
      $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
    }

    return $titulo;
  }


  /*--- GENERAR CÓDIGO CERTIFICACION POA ---*/
  public function generar_certificacion_poa($cpoa_id){
    $get_cpoa=$this->model_certificacion->get_certificacion_poa($cpoa_id);
    $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
    if(count($verificando)==0){ // Creando campo para la distrital
      $data_to_store2 = array(
        'dist_id' => $get_cpoa[0]['dist_id'], /// dist_id
        'g_id' => $this->gestion, /// gestion
        'mod_ope' => 0, 
        'mod_req' => 0,
        'cert_poa' => 0,
      );
      $this->db->insert('conf_modificaciones_distrital', $data_to_store2);
      $mod_id=$this->db->insert_id();
    }

    if($get_cpoa[0]['cpoa_estado']==0){
        $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
        $nro_cpoa=$verificando[0]['cert_poa']+1;
        $nro_cdep='';
        if($nro_cpoa<10){
          $nro_cdep='000';
        }
        elseif($nro_cpoa<100) {
          $nro_cdep='00';
        }
        elseif($nro_cpoa<1000){
          $nro_cdep='0';
        }

        if($this->gestion>2021){
          $codigo='CPOA/'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'/'.$nro_cdep.''.$nro_cpoa;
        }
        else{
          $codigo='CPOA/'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
        }

        if(count($this->model_certificacion->get_codigo_certpoa($codigo))==0){
          /*---- Update Estado Certificacion POA ----*/
          $update_cpoa= array(
            'cpoa_codigo' => $codigo,
            'cpoa_estado' => 1,
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cpoa_id', $cpoa_id);
          $this->db->update('certificacionpoa', $this->security->xss_clean($update_cpoa));
          /*-----------------------------------------*/

          /*----- Update Configuracion Cert distrital -----*/
          $update_conf= array(
            'cert_poa' => $nro_cpoa
          );
          $this->db->where('mod_id', $verificando[0]['mod_id']);
          $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
          /*----------------------------------------------*/
        }
    }
  }


  
    /*---- Lista de Unidades / Establecimientos de Salud (2020-2021) -----*/
    public function list_unidades_es($proy_estado){
      $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
      $tabla='';
      
      if($this->gestion>2020){ /// 2021
         $tabla.='
          <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
            <thead>
              <tr style="height:35px;">
                <th style="width:1%;" bgcolor="#474544">#</th>
                <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
                <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">PROGRAMA '.$this->gestion.'</th>
                <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N ACTIVIDAD">GASTO CORRIENTE</th>
                <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
                <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
                <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>';
                if($this->fun_id==399){
                  $tabla.='<th style="width:5%;" bgcolor="#474544" title="UPDATE CERT. POA.">UPDATE CERT. POA</th>';
                }
                $tabla.='
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($unidades as $row){
                if($row['proy_estado']==4){
                  $nro++;
                  $tabla.='
                    <tr style="height:45px;">
                      <td align=center title="'.$row['proy_id'].'-'.$row['aper_id'].'"><b>'.$nro.'</b></td>
                      <td align=center>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" title="SELECCIONAR ACTIVIDAD"> 
                        <i class="glyphicon glyphicon-list"></i> SELECCIONAR ACTIVIDAD (FORM. N° 4)</a>
                      </td>
                      <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                      <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td>'.$row['escalon'].'</td>
                      <td>'.$row['nivel'].'</td>
                      <td>'.$row['tipo_adm'].'</td>
                      <td>'.strtoupper($row['dep_departamento']).'</td>
                      <td>'.strtoupper($row['dist_distrital']).'</td>';
                        if($this->fun_id==399){
                          $tabla.='<td><a href="'.site_url("").'/cert/update_certpoa_insumo/'.$row['proy_id'].'" title="UPDATE DATOS CERT. POA." class="btn btn-primary">UPDATE CPOA.</a></td>';
                        }
                      $tabla.='
                    </tr>';
                }
              }
            $tabla.='
            </tbody>
          </table>';
      }
      else{ /// 2020
         $tabla.='
          <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
            <thead>
              <tr style="height:35px;">
                <th style="width:1%;" bgcolor="#474544">#</th>
                <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
                <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
                <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">UNIDAD / ESTABLECIMIENTO DE SALUD</th>
                <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
                <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
                <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
                <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              </tr>
            </thead>
            <tbody>';
              $nro=0;
              foreach($unidades as $row){
                if($row['proy_estado']==4){
                  $nro++;
                  $tabla.='
                    <tr style="height:45px;">
                      <td align=center title='.$row['proy_id'].'><b>'.$nro.'</b></td>
                      <td align=center>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" title="SELECCIONAR ACTIVIDAD"> 
                        <i class="glyphicon glyphicon-list"></i> MIS ACTIVIDADES</a>
                      </td>
                      <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                      <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td>'.$row['escalon'].'</td>
                      <td>'.$row['nivel'].'</td>
                      <td>'.$row['tipo_adm'].'</td>
                      <td>'.strtoupper($row['dep_departamento']).'</td>
                      <td>'.strtoupper($row['dist_distrital']).'<br><a href="'.site_url("").'/cert/update_certpoa_insumo/'.$row['proy_id'].'" title="UPDATE DATOS CERT. POA." class="btn btn-primary">UPDATE CPOA.</a></td>
                    </tr>';
                }
              }
            $tabla.='
            </tbody>
          </table>';
        }

      return $tabla;
    }


    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($proy_estado){
      $proyectos=$this->model_proyecto->list_pinversion(1,$proy_estado);
      $tabla='';
      if($this->gestion>2020){ /// 2021
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">PROGRAMA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                  <tr style="height:35px;">
                    <td><center>'.$nro.'</center></td>
                    <td align=center>';
                      if($row['pfec_estado']==1){
                        $tabla.='
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                          <i class="glyphicon glyphicon-list"></i> SELECCIONAR ACTIVIDAD (FROM. N° 4)</a>';
                      }
                      else{
                        $tabla.='FASE NO ACTIVA';
                      }
                    $tabla.='
                    </td>
                <td><center>'.$row['aper_programa'].' '.$row['proy_sisin'].' 00</center></td>
                <td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='<td title='.$row['pfec_id'].'>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      }
      else{ /// 2020
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">FASE_ETAPA</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                  <tr style="height:35px;">
                    <td><center>'.$nro.'</center></td>
                    <td align=center>';
                      if($row['pfec_estado']==1){
                        $tabla.='
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                          <i class="glyphicon glyphicon-list"></i> MIS ACTIVIDADES</a>';
                      }
                      else{
                        $tabla.='FASE NO ACTIVA';
                      }
                    $tabla.='
                    </td>
                <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                <td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='<td title='.$row['pfec_id'].'>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      }

      return $tabla;
    }


  /*------ GET PRODUCTOS -----*/
    public function mis_productos($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      /*$titulo='UNIDAD RESPONSABLE';
      if($proyecto[0]['tp_id']==4){
        $titulo='SUBACTIVIDAD';
      }*/


      $productos = $this->model_certificacion->list_operaciones_x_subactividad_ppto($proy_id); /// PRODUCTOS
      $tabla='';
      if($this->gestion>2020){ /// 2021
      $tabla='          
          ';
      $tabla.='
      <form>
        <section class="col col-6">
          <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
        </section>
        <table class="table table-bordered" border=1 style="width:100%;" id="datos">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#3276b1" title="'.$proy_id.'">#</th>
              <th style="width:9%;" bgcolor="#3276b1" title="UNIDAD RESPONSABLE">UNIDAD RESPONSABLE</th>
              <th style="width:1%;" bgcolor="#3276b1" title="CÓDIGO">COD. ACT.</th>
              <th style="width:17%;" bgcolor="#3276b1" title="DESCRIPCION ACTIVIDAD">ACTIVIDAD (FORMULARIO N° 4)</th>
              <th style="width:17%;" bgcolor="#3276b1" title="RESULTADO">RESULTADO</th>
              <th style="width:3%;" bgcolor="#3276b1" title="MONTO PRESUPUESTO POA">PPTO. POA '.$this->gestion.'</th>
              <th style="width:3%;" bgcolor="#3276b1" title="ITEMS A CERTIFICAR"></th>
              <th style="width:1%;" bgcolor="#3276b1"></th>
            </tr>
          </thead>
          <tbody>
            <tbody>'; 
            $nro=0;
            foreach($productos as $row){
              $nro++;
              $tabla.=
              '<tr>
                <td align=center>'.$nro.'</td>
                <td><b>'.$row['tipo_subactividad'].' '.$row['serv_cod'].' '.$row['serv_descripcion'].'</b></td>
                <td align=center><span class="badge bg-color-blue txt-color-white">'.$row['prod_cod'].'</span></td>
                <td>'.$row['prod_producto'].'</td>
                <td>'.$row['prod_resultado'].'</td>
                <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                <td align=center><a class="btn btn-primary" href="'.site_url("").'/cert/form_items/'.$row['prod_id'].'" id="myBtn'.$row['prod_id'].'" title="INGRESAR A LISTA DE ITEMS A CERTIFICAR" style="width:100%;"><i class="fa fa-lg fa-fw fa-list-alt"></i> CERTIFICAR ITEMS</a></td>
                <td align=center><img id="load'.$row['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="35" height="35" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
              </tr>';

              $tabla.=' <script>
                          document.getElementById("myBtn'.$row['prod_id'].'").addEventListener("click", function(){
                            this.disabled = true;
                            document.getElementById("load'.$row['prod_id'].'").style.display = "block";
                            document.getElementById("mload").style.display = "block";
                          });
                        </script>';
            }
            $tabla.='
            </tbody>
          </table>
        </form>';
      }
      else{ /// 2020
      $tabla.='
        <table class="table table-bordered" border=1 style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#3276b1">#</th>
              <th style="width:9%;" bgcolor="#3276b1" title="SERVICIO / COMPONENTE">SERVICIO / COMPONENTE </th>
              <th style="width:1%;" bgcolor="#3276b1" title="CÓDIGO">COD. ACT.</th>
              <th style="width:17%;" bgcolor="#3276b1" title="ACTIVIDAD">ACTIVIDAD</th>
              <th style="width:17%;" bgcolor="#3276b1" title="RESULTADO">RESULTADO</th>
              <th style="width:3%;" bgcolor="#3276b1" title="MONTO PRESUPUESTO POA">PPTO. POA</th>
              <th style="width:3%;" bgcolor="#3276b1" title="ITEMS A CERTIFICAR"></th>
              <th style="width:1%;" bgcolor="#3276b1"></th>
            </tr>
          </thead>
          <tbody>
            <tbody>'; 
            $nro=0;
            foreach($productos as $row){
              $nro++;
              $tabla.=
              '<tr bgcolor=#eef3f9>
                <td align=center>'.$nro.'</td>
                <td><b>'.$row['com_componente'].'</b></td>
                <td align=center><span class="badge bg-color-blue txt-color-white">'.$row['prod_cod'].'</span></td>
                <td>'.$row['prod_producto'].'</td>
                <td>'.$row['prod_resultado'].'</td>
                <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                <td align=center><a class="btn btn-primary" href="'.site_url("").'/cert/form_items/'.$row['prod_id'].'" id="myBtn'.$row['prod_id'].'" title="INGRESAR A LISTA DE ITEMS A CERTIFICAR" style="width:100%;"><i class="fa fa-lg fa-fw fa-list-alt"></i> CERTIFICAR ITEMS</a></td>
                <td align=center><img id="load'.$row['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="35" height="35" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
              </tr>';

              $tabla.=' <script>
                          document.getElementById("myBtn'.$row['prod_id'].'").addEventListener("click", function(){
                            this.disabled = true;
                            document.getElementById("load'.$row['prod_id'].'").style.display = "block";
                            document.getElementById("mload").style.display = "block";
                          });
                        </script>';
            }
            $tabla.='
            </tbody>
          </table>';
      }

      return $tabla;
    }

  
  /*------- LISTA DE REQUERIMIENTOS CERTIFICADOS (REFORMULACION) ------*/
  public function list_requerimientos_certificadosss($cpoa_id){
    $tabla='<style>
            table{font-size: 9.5px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            font-family: Copperplate;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9.5px;
            }
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
            #mdialTamanio{
              width: 70% !important;
            }
          </style>';
    $requerimientos=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);
   //   $requerimientos=$this->model_certificacion->lista_items_certificados($cpoa_id);
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">'.$cpoa_id.'</th>
            <th style="width:2%;"></th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
        //  $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
         // $get_item_cert=$this->model_certificacion->get_item_certificados($row['ins_id'],$cpoa_id);
          $display='style="display: none"';
          $check='';
/*          if(count($get_item_cert)!=0){
            $display='';
            $check='checked="checked"';
            $monto_certificado=$get_item_cert[0]['monto_certificado'];
          }*/

          $bgcolor='#f2fded';
        /*  if(count($this->model_certificacion->get_insumo_monto_cpoa_certificado($row['ins_id'],$cpoa_id))==0){
            $bgcolor='#f59787';
          }*/
          $nro_mes=count($this->model_certificacion->verif_temporalidad_certificado($row['ins_id']));
          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.' title='.$row['ins_id'].' id="tr'.$nro.'" >
            <td>'.$nro.'</td>
            <td>
              <input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila_edit(this.value,'.$nro.','.$cpoa_id.',this.checked);" '.$check.'/><br>
              <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
            </td>
            <td>
              <a href="#" data-toggle="modal" data-target="#modal_mod_ins" class="btn-default mod_ins" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a>
            </td>
            <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
            <td>'.$row['ins_detalle'].'</td>
            <td>'.$row['ins_unidad_medida'].'</td>
            <td align=right>'.$row['ins_cant_requerida'].'</td>
            <td align=right>'.$row['ins_costo_unitario'].'</td>
            <td align=right>'.$row['ins_costo_total'].'</td>
            <td align=right bgcolor="#e7f5f3"><b>'.number_format($row['monto_certificado'], 2, ',', '.').'</b></td>';
            if($nro_mes==1){
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td align=right>'.$row['mes'.$i].'</td>';
              }
            }
            else{
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td align=right></td>';
              }
            }
            $tabla.='
          </tr>';
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }

 /*------- LISTA DE REQUERIMIENTOS CERTIFICADOS (REFORMULACION anterior) ------*/
  public function list_requerimientos_certificados($cpoa_id){
    $tabla='<style>
            table{font-size: 9.5px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            font-family: Copperplate;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9.5px;
            }
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
            #mdialTamanio{
              width: 70% !important;
            }
          </style>';
    $requerimientos=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">'.$cpoa_id.'</th>
            <th style="width:2%;"></th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
        //  $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
          $get_item_cert=$this->model_certificacion->get_item_certificados($row['ins_id'],$cpoa_id);
          $display='style="display: none"';
          $check='';
          if(count($get_item_cert)!=0){
            $display='';
            $check='checked="checked"';
            $monto_certificado=$get_item_cert[0]['monto_certificado'];
          }

          $bgcolor='#f2fded';
          if(count($this->model_certificacion->get_insumo_monto_cpoa_certificado($row['ins_id'],$cpoa_id))==0){
            $bgcolor='#f59787';
          }



          $mes=$this->model_insumo->lista_prog_fin($row['ins_id']);
          
          $verif_mes=0;
          if(count($mes)>1){
            $verif_mes=1;
          }
          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.' title='.$row['ins_id'].' id="tr'.$nro.'" >
            <td>'.$nro.'</td>
            <td>';
              if($verif_mes==0){
                $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila_cpoa(this.value,'.$nro.','.$cpoa_id.',this.checked);" '.$check.'/><br>';
              }
              else{
                $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila_edit(this.value,'.$nro.','.$cpoa_id.',this.checked);" '.$check.'/><br>';
              }
            $tabla.='
              
              <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
            </td>
            <td>
              <a href="#" data-toggle="modal" data-target="#modal_mod_ins" class="btn-default mod_ins" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a>
            </td>
            <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
            <td>'.$row['ins_detalle'].'</td>
            <td>'.$row['ins_unidad_medida'].'</td>
            <td align=right>'.$row['ins_cant_requerida'].'</td>
            <td align=right>'.$row['ins_costo_unitario'].'</td>
            <td align=right>'.$row['ins_costo_total'].'</td>
            <td align=right bgcolor="#e7f5f3"><b>'.number_format($monto_certificado, 2, ',', '.').'</b></td>';
            
            if($verif_mes==0){
              $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td align=right>'.$temp[0]['mes'.$i].'</td>';
              }
            }
            else{

              for ($i=1; $i <=12 ; $i++) {
              $color=''; 
                $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                $tabla.='
                <td align=right>
                  <table align=right>
                    <tr>
                      <td>
                        <div id="m'.$i.''.$row['ins_id'].'" '.$display.'>';
                        if(count($m)!=0){
                          if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                            $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad_edit(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);"/>';
                          }
                          elseif(count($this->model_certificacion->get_mes_certificado_cpoa($cpoa_id,$m[0]['tins_id']))==1){
                            $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad_edit(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);" checked="checked"/>';
                            $color='green';
                          }
                        }
                $tabla.='
                      </td>
                      <td align=right>';
                      if(count($m)!=0){
                        $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                      }
                      else{
                        $tabla.='0,00';
                      }
                $tabla.='
                      </td>
                    </tr>
                  </table>
                </td>';
              }
            }

            $tabla.='
          </tr>';
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }




 ///// SUBACTIVIDAD
    //// SELECCION DE FORMULARIO N 4 PARA SELECCIONAR REQUERIMIENTOS 
  public function select_mis_productos($com_id,$titulo,$tp){
    /// tp : 0 (Normal)
    /// tp : 1 (Prog 72 - Bienes y Servicios)

    if($tp==0){
      $productos=$this->model_certificacion->get_operaciones_x_subactividad_ppto($com_id);
    }
    else{
      $productos=$this->model_certificacion->get_operaciones_x_subactividad_ppto_bienes_servicios($com_id);
    }

    $tabla='';
    $tabla='
      <form class="form-horizontal">
        <input name="base" type="hidden" value="'.base_url().'">
        <input name="tp" type="hidden" value="'.$tp.'">
        <input name="com_id" type="hidden" value="'.$com_id.'">
        <fieldset>
          <legend><b>'.$titulo.'</b></legend>
          <span class="badge bg-color-green" style="font-size: 35px;">Paso 1)</span> <span class="badge bg-color-green" style="font-size: 25px;"> Seleccione la Actividad donde se encuentre alineado el items a Certificar</span><hr>
          <div class="form-group">
            <label class="col-md-2 control-label"><b>ACTIVIDAD (FORMULARIO N° 4)</b></label>
            <div class="col-md-6">
              <select class="form-control" name="prod_id" id="prod_id">
                <option value="0">Seleccione Actividad</option>';
               foreach($productos as $row){
                  $tabla.='<option value="'.$row['prod_id'].'">'.$row['prod_cod'].'.- '.$row['prod_producto'].'</option>';
                }
               $tabla.='
              </select> 
            </div>
          </div>
        </fieldset>
      </form>';
    return $tabla;
  }


/*------- LISTA DE REQUERIMIENTOS PRE LISTA 2021 ------*/
  public function list_requerimientos_prelista($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
    $tabla='<style>
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
          </style>';
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
          }

          if($monto_certificado!=$row['ins_costo_total']){
          //  if($row['ins_monto_certificado']!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if(count($this->model_certificacion->get_items_solicitado($row['ins_id']))==0){
                    if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                      $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                    }
                    else{
                      $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                    }
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="20" HEIGHT="20"/>';
                }
                $tabla.='
                <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>';
                if($this->model_certificacion->get_insumo_programado($row['ins_id'])>1){
                  for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                }
                else{
                  $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  for ($j=1; $j <=12 ; $j++) {
                    $bgcolor='';
                    if($temp[0]['mes'.$j.'']!=0){
                      $bgcolor='#d5f5f0';
                    }
                    $tabla.='
                    <td align="right" bgcolor='.$bgcolor.'>
                      '.number_format($temp[0]['mes'.$j.''], 2, ',', '.').'
                    </td>';
                  }
                }

                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }


/*------- LISTA DE REQUERIMIENTOS PRE LISTA (2022) ------*/
  public function list_requerimientos_2022($prod_id,$tp,$com_id){
    /// tp 0: lista de requerimientos por unidad responsable
    /// tp 1: lista de requerimientos del prog 72 BIENES Y SERVICIOS

    $tabla='';
    if($tp==0){ /// Filtrado normal
      $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
    }
    else{ /// filtrado para el programa 72 BIENES Y SERVICIO por unidad responsable
      $requerimientos=$this->model_certificacion->requerimientos_x_uresponsables_bienes_servicios($prod_id,$com_id);
    }

    $tabla='<style>
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
          </style>';
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';

          if($row['ins_monto_certificado']!=$row['ins_costo_total']){
              $temp=$this->model_certificacion->get_insumo_programado($row['ins_id']);
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if(count($this->model_certificacion->get_items_solicitado($row['ins_id']))==0){ /// EN CASO DE QUENO TENGA SOLICITUD
                  if($temp>1){
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                  }
                  else{
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                  }
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="20" HEIGHT="20"/>';
                }
                $tabla.='
                <input type="text" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 15px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td style="font-size: 10px;" >'.$row['ins_detalle'].'</td>
                <td style="font-size: 10px;">'.$row['ins_unidad_medida'].'</td>
                <td style="font-size: 10px;" align=right>'.$row['ins_cant_requerida'].'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                if($temp>1){
                  for ($i=1; $i <=12; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if($m[0]['estado_cert']==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td style="font-size: 10px;" align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                }
                else{
                  $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  for ($j=1; $j <=12 ; $j++) {
                    $bgcolor='';
                    if($temp[0]['mes'.$j.'']!=0){
                      $bgcolor='#d5f5f0';
                    }
                    $tabla.='
                    <td style="font-size: 10px;" align="right" bgcolor='.$bgcolor.'>
                      '.number_format($temp[0]['mes'.$j.''], 2, ',', '.').'
                    </td>';
                  }
                }
                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }









  /*------- LISTA DE REQUERIMIENTOS PRE LISTA (2022) ------*/
  public function list_requerimientos_temporalidad_unica($prod_id){
    /// tp 0: lista de requerimientos por unidad responsable
    /// tp 1: lista de requerimientos del prog 72 BIENES Y SERVICIOS

    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla='<style>
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
          </style>';
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $temp=$this->model_certificacion->get_insumo_programado($row['ins_id']);
          if($row['ins_monto_certificado']!=$row['ins_costo_total'] & $temp==1){
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if(count($this->model_certificacion->get_items_solicitado($row['ins_id']))==0){ /// EN CASO DE QUENO TENGA SOLICITUD
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="20" HEIGHT="20"/>';
                }
                $tabla.='
                <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 15px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td style="font-size: 10px;" >'.$row['ins_detalle'].'</td>
                <td style="font-size: 10px;">'.$row['ins_unidad_medida'].'</td>
                <td style="font-size: 10px;" align=right>'.$row['ins_cant_requerida'].'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                $temporalidad=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                for ($j=1; $j <=12 ; $j++) {
                  $bgcolor='';
                  if($temporalidad[0]['mes'.$j.'']!=0){
                    $bgcolor='#d5f5f0';
                  }
                  $tabla.='
                  <td style="font-size: 10px;" align="right" bgcolor='.$bgcolor.'>
                    '.number_format($temporalidad[0]['mes'.$j.''], 2, ',', '.').'
                  </td>';
                }
                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }












  /// Listado de Items con temporalidad Variada
  public function list_requerimientos_temporalidad_variada($prod_id){
  $tabla='';
  $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
   $tabla='<style>
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
          </style>';
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $temp=$this->model_certificacion->get_insumo_programado($row['ins_id']);

          if($row['ins_monto_certificado']!=$row['ins_costo_total'] & $temp>1){    
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if(count($this->model_certificacion->get_items_solicitado($row['ins_id']))==0){ /// EN CASO DE QUENO TENGA SOLICITUD
                  $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="20" HEIGHT="20"/>';
                }
                $tabla.='
                <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 15px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td style="font-size: 10px;" >'.$row['ins_detalle'].'</td>
                <td style="font-size: 10px;">'.$row['ins_unidad_medida'].'</td>
                <td style="font-size: 10px;" align=right>'.$row['ins_cant_requerida'].'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                for ($i=1; $i <=12; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if($m[0]['estado_cert']==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td style="font-size: 10px;" align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';







    return $tabla;
  }



/*------- LISTA DE REQUERIMIENTOS PRE LISTA (2022) ------*/
  public function list_requerimientos_2022_anterior($prod_id,$tp,$com_id){
    /// tp 0: lista de requerimientos por unidad responsable
    /// tp 1: lista de requerimientos del prog 72 BIENES Y SERVICIOS

    $tabla='';
    if($tp==0){ /// Filtrado normal
      $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
    }
    else{ /// filtrado para el programa 72 BIENES Y SERVICIO por unidad responsable
      $requerimientos=$this->model_certificacion->requerimientos_x_uresponsables_bienes_servicios($prod_id,$com_id);
    }

    $tabla='<style>
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
          </style>';
    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">PRECIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
            <th style="width:8%;">OBSERVACIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';

          if($row['ins_monto_certificado']!=$row['ins_costo_total']){
              $temp=$this->model_certificacion->get_insumo_programado($row['ins_id']);
              $nro++;
              $tabla.='
              <tr  title='.$row['ins_id'].' id="tr'.$nro.'" >
                <td>'.$nro.'</td>
                <td>';
                if(count($this->model_certificacion->get_items_solicitado($row['ins_id']))==0){ /// EN CASO DE QUENO TENGA SOLICITUD
                  if($temp>1){
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>';
                  }
                  else{
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFilacompleta(this.value,'.$nro.',this.checked);"/><br>';
                  }
                }
                else{
                  $tabla.='<img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="20" HEIGHT="20"/>';
                }
                $tabla.='
                <input type="text" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
                </td>
                <td style="font-size: 15px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td style="font-size: 10px;" >'.$row['ins_detalle'].'</td>
                <td style="font-size: 10px;">'.$row['ins_unidad_medida'].'</td>
                <td style="font-size: 10px;" align=right>'.$row['ins_cant_requerida'].'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="font-size: 10px;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                if($temp>1){
                  for ($i=1; $i <=12; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if($m[0]['estado_cert']==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td style="font-size: 10px;" align=right>';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>

                        </tr>
                      </table>
                    </td>';
                  }
                }
                else{
                  $temp=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                  for ($j=1; $j <=12 ; $j++) {
                    $bgcolor='';
                    if($temp[0]['mes'.$j.'']!=0){
                      $bgcolor='#d5f5f0';
                    }
                    $tabla.='
                    <td style="font-size: 10px;" align="right" bgcolor='.$bgcolor.'>
                      '.number_format($temp[0]['mes'.$j.''], 2, ',', '.').'
                    </td>';
                  }
                }
                $tabla.='
                <td>'.$row['ins_observacion'].'</td>
              </tr>';
          }
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }


/*------- LISTA DE REQUERIMIENTOS NORMAL ------*/
  public function list_requerimientos($prod_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);

          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
            

            if($monto_certificado==$row['ins_costo_total']){
              $verif=1;
              $color_tr="#f7d6dc";
            }
            elseif($monto_certificado<$row['ins_costo_total']){
              $color_tr="#f6f7cb";
            }
          }

          if($monto_certificado!=$row['ins_costo_total']){
              $nro++;
              $tabla.='
              <tr bgcolor="'.$color_tr.'" title='.$row['ins_id'].'>
                <td>'.$nro.'</td>
                <td>';
                  if($verif==0){
                    $tabla.='<input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value, this.checked);"/><br>
                            <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">';
                  }
                $tabla.='
                </td>
                <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
                <td>'.$row['ins_detalle'].'</td>
                <td>'.$row['ins_unidad_medida'].'</td>
                <td align=right>'.$row['ins_cant_requerida'].'</td>
                <td align=right>'.$row['ins_costo_unitario'].'</td>
                <td align=right>'.$row['ins_costo_total'].'</td>
                <td align=right bgcolor="#e7f5f3">'.number_format($monto_certificado, 2, ',', '.').'</td>';

                for ($i=1; $i <=12 ; $i++) {
                    $color=''; 
                    $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
                    $tabla.='
                    <td align=right>
                      <table align=right>
                        <tr>
                          <td>
                            <div id="m'.$i.''.$row['ins_id'].'" style="display: none;">';
                            if(count($m)!=0){
                              if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                                $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value, this.checked);"/>';
                              }
                              else{
                                $color='green';
                              }
                            }
                    $tabla.='
                          </td>
                          <td align=right >';
                          if(count($m)!=0){
                            $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                          }
                          else{
                            $tabla.='0,00';
                          }
                    $tabla.='
                          </td>
                        </tr>
                      </table>
                    </td>';
                  }
                
                $tabla.='
              </tr>';
          }

        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }

 //// ======== SOLICITUD DE CERTIFICACION POA ========
/*-- CABECERA (Solicitud Certificacion POa) --*/
  public function cabecera_solicitudpoa($solicitud){
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:70%;height: 2%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="font-size: 15px;font-family: Arial;">
                            <td style="width:50%;height: 30%;">&nbsp;&nbsp;<b>CAJA NACIONAL DE SALUD</b></td>
                        </tr>
                        <tr>
                            <td style="width:50%;height: 30%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                        </tr>
                    </table>
                </td>
                <td style="width:30%; height: 2%; font-size: 8px;text-align:center;">
                    SISTEMA DE PROGRAMACIÓN DE OPERACIONES
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px black; text-align: center;">
                <td style="width:20%; text-align:center;">
                </td>
                <td style="width:60%; height: 5%">
                    <table align="center" border="0" style="width:100%;">
                        <tr style="font-size: 23px;font-family: Arial;">
                            <td style="height: 40%;"><b>SOLICITUD DE CERTIFICACI&Oacute;N POA - '.$this->gestion.'</b></td>
                        </tr>
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="height: 10%;"><b>(DOCUMENTO NO VALIDO PARA PROCESOS DE EJECUCIÓN POA)</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:50%;">
                </td>
                <td style="width:50%; height: 3%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 13px;font-family: Arial;">
                          <td colspan="2" style="width:100%;height: 30%;text-align:right;"><b>FORMULARIO CERT. N° 10&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                      </tr>
                      <tr style="font-size: 10px;font-family: Arial;">
                          <td style="width:50%;height: 30%;"><b>CITE : </b>'.$solicitud[0]['cite'].'</td>
                          <td style="width:50%;height: 30%"><b>FECHA : </b>'.date('d-m-Y',strtotime($solicitud[0]['fecha'])).'</td>
                      </tr>
                  </table>
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
             <tr>
                <td style="width:1%;"></td>
                <td style="height: 3%;">
                    <div style="width:98%;font-size: 12px; font-family: Arial;">
                    Se solicita al Departamento Nacional de Planificación o Encargados del POA Regional/Distrital, la emisión de la <b>CERTIFICACIÓN POA GESTIÓN '.$this->gestion.'</b>, 
                    de los requerimientos programados a favor de la Unidad, mismos se encuentran articulados a los Objetivos de Gestión y Acción Estrategica detallada a continuación.
                    </div>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>';

    return $tabla;
  }

  /*-- I y II UNIDAD ORGANIZACIONAL SOLICITANTE y ARTICULACION --*/
  public function datos_unidad_organizacional($solicitud){
    $tabla='';
    $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>I. UNIDAD ORGANIZACIONAL SOLICITANTE</b></td>
                        </tr>
                    </table><br>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.strtoupper ($solicitud[0]['dep_departamento']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.strtoupper ($solicitud[0]['dist_distrital']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>'.$solicitud[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.$solicitud[0]['aper_programa'].' '.$solicitud[0]['aper_proyecto'].' '.$solicitud[0]['aper_actividad'].' '.strtoupper ($solicitud[0]['act_descripcion']).' '.$solicitud[0]['abrev'].'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>UNIDAD RESPONSABLE</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 1.5%;">&nbsp;'.$solicitud[0]['tipo_subactividad'].' '.$solicitud[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>        
        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>II. ARTICULACI&Oacute;N POA '.$this->gestion.' Y PEI</b></td>
                        </tr>
                    </table><br>
                    <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <thead>
                            <tr style="font-size: 8px; font-family: Arial;" align="center" >
                                <th style="width:4%;height: 1.5%;">COD. ACT.</th>
                                <th style="width:32%;">DESCRIPCIÓN ACTIVIDAD</th>
                                <th style="width:32%;">OPERACIÓN '.$this->gestion.'</th>
                                <th style="width:32%;">ACCIÓN DE CORTO PLAZO '.$this->gestion.'</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:4%;height: 4%;font-size: 15px;" align="center"><b>'.$solicitud[0]['prod_cod'].'</b></td>
                                <td style="width:32%;">'.$solicitud[0]['prod_producto'].'</td>
                                <td style="width:32%;"><b>'.$solicitud[0]['or_codigo'].'</b>.- '.$solicitud[0]['or_objetivo'].'</td>
                                <td style="width:32%;"><b>'.$solicitud[0]['og_codigo'].'</b>.- '.$solicitud[0]['og_objetivo'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>
        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>III. DETALLE DE ITEM PARA CERTIFICACIÓN POA DEL FORMULARIO POA N°5</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>';

    return $tabla;
  }



 //// ======== CERTIFICACION POA ========
/*-- CABECERA (Certificacion POa Aprobado) --*/
  public function cabecera_certpoa($certpoa,$codigo){
    $fechas='
      <td style="width:50%;height: 33%;"><b>CITE : </b> '.$certpoa[0]['cpoa_cite'].'</td>
      <td style="width:50%;height: 33%"><b>FECHA : </b>'.date('d-m-Y',strtotime($certpoa[0]['cite_fecha'])).'</td>';
    if($certpoa[0]['sol_id']!=0){
      $solicitud=$this->model_certificacion->get_solicitud_cpoa($certpoa[0]['sol_id']);
      $fechas='
      <td style="width:33%;height: 33%;"><b>CITE SOL.: </b> '.$solicitud[0]['cite'].'</td>
      <td style="width:33%;height: 33%"><b>FECHA SOL.: </b>'.date('d-m-Y',strtotime($solicitud[0]['fecha'])).'</td>
      <td style="width:33%;height: 33%"><b>FECHA APROB.: </b>'.date('d-m-Y',strtotime($certpoa[0]['cite_fecha'])).'</td>';
    }
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
              '.strtoupper($certpoa[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <td style="height: 30%;"><b>CERTIFICACI&Oacute;N DEL PLAN OPERATIVO ANUAL '.$this->gestion.'</b></td>
                        </tr>
                        <tr style="font-size: 20px;font-family: Arial;">
                          <td style="height: 5%;">'.$codigo.'</td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:40%;">
                </td>
                <td style="width:60%; height: 3%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 10px;font-family: Arial;">
                        '.$fechas.'
                      </tr>
                  </table>
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 3%;font-size: 11px; font-family: Arial;text-align: justify;">
                  El presente documento certifica que el item descrito se encuentra registrado en la Programación Físico Financiero, se relaciona y responde a las acciones
                  de corto plazo y Operaciones establecidas en el Plan Operativo Anual (POA) gestión '.$this->gestion.' de la Caja Nacional de Salud.
              </td>
              <td style="width:2%;"></td>
          </tr>
        </table>
       
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 3%;">
                 <div style="border: 1px groove #000;font-size: 9px;font-family: Arial;height:25px;" align="center">
                    <br><b>La presente CERTIFICACI&Oacute;N deber&aacute; ser utilizada para inicio de procesos de compra de bienes y/o contrataci&oacute;n de servicios a ser concretados a partir de la fecha de su emisi&oacute;n.</b>
                  </div>
              </td>
              <td style="width:2%;"></td>
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
               '.$this->datos_unidad_certpoa($certpoa).'
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
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;font-size: 10px;">
                <b>DESCRIPCI&Oacute;N DE LOS SOLICITADO: </b>
              </td>
              <td style="width:2%;"></td>
          </tr>
        </table>';

    return $tabla;
  }

  /*-- Datos generales Unidad --*/
  public function datos_unidad_certpoa($certpoa){
    $tabla='';
    $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($certpoa[0]['dep_departamento']).'</td></tr>
                  </table>
              </td>
          </tr>
          <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($certpoa[0]['dist_distrital']).'</td></tr>
                  </table>
              </td>
          </tr>';

            if($certpoa[0]['tp_id']==1){
              $tabla.='
              <tr>
                <td style="width:20%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                        <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>PROY. INVERSI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                    </table>
                </td>
                <td style="width:80%;">
                    <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                        <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['proy_sisin'].' '.strtoupper ($certpoa[0]['proy_nombre']).'</td></tr>
                    </table>
                </td>
              </tr>
              <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD RESPONSABLE</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr>
                          <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['tipo_subactividad'].' '.$certpoa[0]['serv_descripcion'].'</td>
                      </tr>
                  </table>
              </td>
          </tr>';
            }
            else{
              $tabla.='
              <tr>
                <td style="width:20%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                        <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>'.$certpoa[0]['tipo_adm'].'</b></td><td style="width:5%;"></td></tr>
                    </table>
                </td>
                <td style="width:80%;">
                    <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                        <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['aper_actividad'].' '.strtoupper ($certpoa[0]['act_descripcion']).' '.$certpoa[0]['abrev'].'</td></tr>
                    </table>
                </td>
              </tr>
              <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD RESPONSABLE</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr>
                          <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['tipo_subactividad'].' '.$certpoa[0]['serv_descripcion'].'</td>
                      </tr>
                  </table>
              </td>
          </tr>';
            }
          $tabla.='
          
          
          <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>OPERACI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr>
                          <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['obj_codigo'].' .-'.$certpoa[0]['obj_descripcion'].'</td>
                      </tr>
                  </table>
              </td>
          </tr>
          <tr>
              <td style="width:20%;">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                  </table>
              </td>
              <td style="width:80%;">
                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                      <tr>
                          <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$certpoa[0]['prod_cod'].' .- '.$certpoa[0]['prod_producto'].'</td>
                      </tr>
                  </table>
              </td>
          </tr>
      </table>';
    return $tabla;
  }

  
  /*-- Detalle Requerimientos Certificados Final --*/
  public function items_certificados($cpoa_id){
    $tabla='';
    $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
    $requerimientos=$this->model_certificacion->lista_items_certificados($cpoa_id); /// lista de items certificados  
    $tabla.='
        <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align=center>
          <thead>
              <tr style="font-size: 8px; font-family: Arial;" align="center" bgcolor="#efefef">
                <th style="width:3%;height: 4%;">N°</th>
                <th style="width:7%;">PARTIDA</th>
                <th style="width:35%;">DETALLE REQUERIMIENTO</th>
                <th style="width:15%;">UNIDAD DE MEDIDA</th>
                <th style="width:15%;">PRECIO TOTAL</th>
                <th style="width:15%;">PRESUPUESTO SOLICITADO</th>
                <th style="width:19%;">TEMPORALIDAD SELECCIONADO</th>
              </tr>
          </thead>
          <tbody>';
            $nro=0;$suma_monto=0;
            foreach($requerimientos as $row){
              $nro++;
              $suma_monto=$suma_monto+$row['monto_certificado'];
              $tabla.='
              <tr style="font-size: 8px; font-family: Arial;">
                <td style="width:3%;height: 3.5%;" align=center>'.$nro.'</td>
                <td style="width:7%;" align=center>'.$row['par_codigo'].'</td>
                <td style="width:35%;">'.$row['ins_detalle'].'</td>
                <td style="width:15%;">'.$row['ins_unidad_medida'].'</td>
                <td style="width:15%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                <td style="width:15%;" align=right>'.number_format($row['monto_certificado'], 2, ',', '.').'</td>
                <td style="width:19%;" align=center>'.$this->temporalidad_certificado_items($row['cpoad_id']).'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
            <tr>
              <td style="height: 3.5%;"></td>
              <td colspan=4 align=right><b>MONTO TOTAL CERTIFICADO : </b></td>
              <td style="font-size: 9px;" align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
              <td></td>
            </tr>
        </table>';

    return $tabla;
  }

  /*-- Temporalidad de items Certificado --*/
  public function temporalidad_certificado_items($cpoad_id){
    $tabla='';
    $temporalidad=$this->model_certificacion->get_meses_certificacion_items($cpoad_id);
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align=center>';
        foreach($temporalidad as $row){
          $tabla.='
          <tr>
            <td style="width:50%;height: 1%;" align=left>'.$row['m_descripcion'].' : </td>
            <td style="width:50%;" align=left><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
          </tr>';
        }
      $tabla.='
      </table>';

    return $tabla;
  }

  /*-- Pie de Reporte - Certificacion POa Aprobado --*/
  public function pie_certificacion_poa($certpoa,$tp){
    /// tp : 1 (Normal), 2 (Editado)
    $tabla='';

    $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
            <tr>
                <td style="width: 3%;"></td>
                <td style="width: 55%;">
                    <b>RECOMENDACIONES </b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#cae4fb">
                          <td style="width: 100%;height: 2%;">';
                            if($tp==1){
                              $tabla.=''.$certpoa[0]['cpoa_recomendacion'].'';
                            }
                            elseif($tp==2){
                              $cert_edit=$this->model_certificacion->get_datos_certificado_anulado($certpoa[0]['cpoa_id']);
                              if(count($cert_edit)!=0){
                                $tabla.='<b>EL PRESENTE DOCUMENTO YA NO PODRA SER MODIFICADO, DEBIDO A UNA RECIENTE EDICIÓN CON EL NRO. DE CITE : '.$cert_edit[0]['cite_edicion'].' EN FECHA '.date('d-m-Y',strtotime($cert_edit[0]['cite_fecha'])).' CON LA SIGUIENTE JUSTIFICACIÓN TECNICA : '.$cert_edit[0]['cite_justificacion'].'</b>';
                              }
                            }
                          $tabla.='
                          </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
          <tr>
            <td style="width: 40%;">';
              $color='';
              if($tp==1){
                  $cert_edit=$this->model_certificacion->get_datos_certificacion_poa_anulados($certpoa[0]['cpoa_id']);
                  if(count($cert_edit)!=0){
                    $tabla.=$this->pie_certificado($certpoa,$cert_edit[0]['marca_original']);
                  }
                  else{
                    $tabla.=$this->pie_certificado($certpoa,$certpoa[0]['cpoa_sello']);
                  }
              }
              elseif($tp==2){ /// si se ha editado 
                $tabla.=$this->pie_certificado($certpoa,$certpoa[0]['cpoa_sello']);
              }

            $tabla.='  
            </td>
            <td style="width: 40%;"></td>
            <td style="width: 20%;" align="center">
                <qrcode value="'.$certpoa[0]['cpoa_codigo'].' '.$certpoa[0]['cpoa_cite'].'" style="border: none; width: 18mm; '.$color.'"></qrcode>
            </td>
          </tr>
          <tr>
              <td colspan="3"><br></td>
          </tr>
          <tr style="font-size: 7px;font-family: Arial;">
              <td style="text-align: left" colspan="2">
                '.$this->session->userdata('sistema').'
              </td>
              <td style="width: 20%; text-align: right">
                '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
              </td>
          </tr>
          <tr>
              <td colspan="3"><br></td>
          </tr>
      </table>';
    return $tabla;
  }



  /*--- pie de reporte cert poa normal  */
  public function pie_certificado($certpoa,$cpoa_sello){
    $tabla='';
    $color='';
      if($cpoa_sello==1){
        $color='color: #3276b1';
        $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
          <tr style="font-size: 10px;font-family: Arial;">
            <td style="width:100%;height:20px;"><b>APROBADO POR</b></td>
          </tr>
          <tr style="font-size: 9px;font-family: Arial; height:65px;">
            <td align="center">
              <barcode  value="'.$certpoa[0]['fun_nombre'].' '.$certpoa[0]['fun_paterno'].' '.$certpoa[0]['fun_materno'].'" style="border: none; width: 80mm;color: #3276b1"></barcode>
            </td>
          </tr>
        </table>';
      }
      else{
        $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
          <tr style="font-size: 10px;font-family: Arial; height:65px;">
              <td style="width:100%;" colspan="2"><b>EMITIDO POR<br></b></td>
          </tr>
          <tr style="font-size: 9px;font-family: Arial; height:65px;">
              <td><b>RESPONSABLE</b></td>
              <td>'.$certpoa[0]['fun_nombre'].' '.$certpoa[0]['fun_paterno'].' '.$certpoa[0]['fun_materno'].'</td>
          </tr>
          <tr style="font-size: 9px;font-family: Arial; height:65px;">
              <td><b>CARGO</b></td>
              <td>'.$certpoa[0]['fun_cargo'].'</td>
          </tr>
          <tr style="font-size: 9px;font-family: Arial; height:65px;" align="center">
              <td colspan="2"><b><br><br>FIRMA</b></td>
          </tr>
        </table>';
      }
    return $tabla;
  }







  /*-- Detalle Requerimientos Certificados Editados (son los originales antes de su modificacion) --*/
  public function items_certificados_original_guardados($cpoa_id,$tp){
    $tabla='';
    $requerimientos=$this->model_certificacion->lista_items_certificados_anulados($cpoa_id); /// lista de items certificados Eliminados
     /// Items guardados
     $tit='MONTO TOTAL CERTIFICADO'; 
    if($tp==0){
      $tit='MONTO SOLICITADO A CERTIFICAR'; 
    }

    $tabla.='
        <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align=center>
          <thead>
              <tr style="font-size: 8px; font-family: Arial;" align="center" bgcolor="#efefef">
                <th style="width:3%;height: 4%;">N°</th>
                <th style="width:7%;">PARTIDA</th>
                <th style="width:35%;">DETALLE REQUERIMIENTO</th>
                <th style="width:15%;">UNIDAD DE MEDIDA</th>
                <th style="width:15%;">PRECIO TOTAL</th>
                <th style="width:15%;">PRESUPUESTO SOLICITADO</th>
                <th style="width:19%;">TEMPORALIDAD SELECCIONADO</th>
              </tr>
          </thead>
          <tbody>';
            $nro=0;$suma_monto=0;
            foreach($requerimientos as $row){
              $nro++;
              $suma_monto=$suma_monto+$row['monto_certificado'];
              $tabla.='
              <tr style="font-size: 8px; font-family: Arial;">
                <td style="width:3%;height: 3.5%;" align=center>'.$nro.'</td>
                <td style="width:7%;" align=center>'.$row['par_codigo'].'</td>
                <td style="width:35%;">'.$row['ins_detalle'].'</td>
                <td style="width:15%;">'.$row['ins_unidad_medida'].'</td>
                <td style="width:15%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                <td style="width:15%;" align=right>'.number_format($row['monto_certificado'], 2, ',', '.').'</td>
                <td style="width:19%;" align=center>'.$this->temporalidad_certificado_items_editados_original($row['cpoaad_id']).'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
            <tr>
              <td style="height: 3.5%;"></td>
              <td colspan=4 align=right><b>'.$tit.' : </b></td>
              <td style="font-size: 9px;" align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
              <td></td>
            </tr>
        </table>';

    return $tabla;
  }

  /*-- Temporalidad de items Certificado Editados Guardados--*/
  public function temporalidad_certificado_items_editados_original($cpoaad_id){
    $tabla='';
    $temporalidad=$this->model_certificacion->get_meses_certificacion_items_editados_guardados($cpoaad_id);
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align=center>';
        foreach($temporalidad as $row){
          $tabla.='
          <tr>
            <td style="width:50%;height: 1%;" align=left>'.$row['m_descripcion'].' : </td>
            <td style="width:50%;" align=left><b>'.number_format($row['ipma_fis'], 2, ',', '.').'</b></td>
          </tr>';
        }
      $tabla.='
      </table>';

    return $tabla;
  }

  /// ============================================================






  /*-- III DETALLE DE REQUERIMIENTOS A SOLICITUD --*/
  public function lista_solicitud_requerimientos($sol_id){
    $tabla='';

    $requerimientos=$this->model_certificacion->get_lista_requerimientos_solicitados($sol_id);
    $tabla.='
    <div class="table-responsive">
      <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <thead>
              <tr style="font-size: 8px; font-family: Arial;" align="center" >
                <th style="width:3%;height: 1.5%;">N°</th>
                <th style="width:7%;">PARTIDA</th>
                <th style="width:28.9%;">DETALLE REQUERIMIENTO</th>
                <th style="width:10%;">UNIDAD DE MEDIDA</th>
                <th style="width:9%;">CANTIDAD</th>
                <th style="width:9%;">PRECIO UNITARIO</th>
                <th style="width:9%;">PRECIO TOTAL</th>
                <th style="width:9%;">MONTO SOLICITADO</th>
                <th style="width:15%;">TEMPORALIDAD SELECCIONADO</th>
              </tr>
          </thead>
          <tbody>';
            $nro=0;$suma_monto=0;
            foreach($requerimientos as $row){
              $nro++;
              $suma_monto=$suma_monto+$row['monto_solicitado'];
              $tabla.='
              <tr style="font-size: 7.5px; font-family: Arial;">
                <td style="width:3%;height: 3%;" align=center>'.$nro.'</td>
                <td style="width:7%;" align=center>'.$row['par_codigo'].'</td>
                <td style="width:28.9%;">'.$row['ins_detalle'].'</td>
                <td style="width:10%;">'.$row['ins_unidad_medida'].'</td>
                <td style="width:9%;" align=right>'.round($row['ins_cant_requerida'],2).'</td>
                <td style="width:9%;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                <td style="width:9%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                <td style="width:9%;" align=right>'.number_format($row['monto_solicitado'], 2, ',', '.').'</td>
                <td style="width:15%;" align=center>'.$this->temporalidad_solicitado($row['req_id']).'</td>
              </tr>';
            }
          $tabla.='
          </tbody>
            <tr>
              <td style="height: 3%;"></td>
              <td colspan=6 align=right><b>MONTO SOLICITADO A CERTIFICAR : </b></td>
              <td style="font-size: 9px;" align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
              <td></td>
            </tr>
        </table>
      </div>';

    return $tabla;
  }

  /*-- TEMPORALIDAD REQUERIMIENTO SOLICITADO --*/
  public function temporalidad_solicitado($req_id){
    $tabla='';
    $temporalidad=$this->model_certificacion->get_lista_temporalidad_solicitados($req_id);
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align=center>';
        foreach($temporalidad as $row){
          $tabla.='
          <tr>
            <td style="width:50%;height: 0.60%;" align=left>'.$row['m_descripcion'].' : </td>
            <td style="width:50%;" align=left><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
          </tr>';
        }
      $tabla.='
      </table>';

    return $tabla;
  }


  /*-- IV CONFORMIDAD DE LA UNIDAD --*/
  public function conformidad_solicitud($solicitud){
    $tabla='';
    $tabla.='
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
      <tr style="border: solid 0px;">              
        <td style="width:1%;"></td>
        <td style="width:98%;">
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr>
              <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>IV. CONFORMIDAD DE LA SOLICITUD</b></td>
            </tr>
          </table>
          <br>
          <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="font-size: 10px; font-family: Arial;" align="center" >
              <th style="width:100%;height: 5%;">JEFATURA '.$solicitud[0]['tipo_subactividad'].' '.$solicitud[0]['serv_descripcion'].' - '.$solicitud[0]['abrev'].'</th>
            </tr>
          </table>
        </td>
        <td style="width:1%;"></td>
      </tr>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
      <tr style="border: solid 0px;">              
        <td style="width:1%;"></td>
        <td style="width:98%;">
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr>
              <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>V. ESTADO DE LA SOLICITUD</b></td>
            </tr>
          </table>
          <br>
          <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="font-size: 10px; font-family: Arial;" align="center">';
                if($solicitud[0]['estado']==0){
                  $tabla.='<td style="width:100%;height: 2%;color: red;"><b>LA SOLICITUD SE ENCUENTRA EN PROCESO DE APROBACIÓN</b></td>';
                }
                elseif($solicitud[0]['estado']==1){
                  $tabla.='<td style="width:100%;height: 2%;color: green;"><b>SOLICITUD APROBADO EN FECHA '.date('d-m-Y',strtotime($solicitud[0]['fecha_proceso'])).'</b></td>';
                }
                elseif($solicitud[0]['estado']==2){
                  $tabla.='<td style="width:100%;height: 2%;color: red;"><b>LA SOLICITUD FUE RECHAZADO por </b> '.$solicitud[0]['aclaracion'].'</td>';
                }
                else{
                 $tabla.='<td style="width:100%;height: 2%;color: red;"><b>LA SOLICITUD FUE ANULADA</b></td>'; 
                }
              $tabla.='
            </tr>
          </table>
        </td>
        <td style="width:1%;"></td>
      </tr>
    </table>';

    return $tabla;
  }
///// ============== END SOLICITUD DE CERTIFICACION POA




/*------ LISTA DE SOLICITUDES CERTIFICACION POA REALIZADAS POR REGIONAL -------*/
  public function lista_solicitudes_certificacionespoa_regional($dep_id){
    
    $tabla='';
    $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>
      <style>
        table{font-size: 10px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>

      <div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
          <h2></h2>
          <ul class="nav nav-tabs pull-right in">
            <li class="active">
              <a data-toggle="tab" href="#hb1"> <i class="fa fa-lg fa-arrow-circle-o-down"></i> <span class="hidden-mobile hidden-tablet"> SOLICITUDES POA EN PROCESO </span> </a>
            </li>
            <li>
              <a data-toggle="tab" href="#hb2"> <i class="fa fa-lg fa-arrow-circle-o-up"></i> <span class="hidden-mobile hidden-tablet"> SOLICITUDES POA APROBADOS </span> </a>
            </li>
          </ul>
        </header>
      <div>
    
      <div class="jarviswidget-editbox"></div>
          <div class="widget-body">
            <div class="tab-content">
            <input name="base" type="hidden" value="'.base_url().'">
              <div class="tab-pane active" id="hb1">
                <div class="table-responsive">  
                  <table id="dt_basic" class="table table-bordered" style="width:100%;">
                    <thead>
                      <tr style="height:35px;">
                        <th style="width:1%;">#</th>
                        <th style="width:5%;">CITE SOLICITUD</th>
                        <th style="width:5%;">FECHA SOLICITUD</th>
                        <th style="width:10%;">UNIDAD SOLICITANTE</th>
                        <th style="width:15%;">DESCRIPCIÓN ACTIVIDAD</th>
                        <th style="width:5%;">ESTADO</th>
                        <th style="width:20%;">OBSERVACIÓN</th>
                        <th style="width:10%;">SOLICITUD</th>
                        <th style="width:10%;">APROBAR SOLICITUD</th>
                        <th style="width:10%;">ANULAR SOLICITUD</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa_regional($dep_id);
                    foreach($solicitudes as $row){
                      $nro++;
                      $color='#d9f9f5';
                      $estado='APROBADO';
                      if($row['estado']==0){
                        $color='#fdeded';
                        $estado='EN REVISIÓN';
                      }
                      elseif($row['estado']==2){
                        $color='#fbecdb';
                        $estado='RECHAZADO';
                      }
                      $tabla.='
                      <tr bgcolor='.$color.'>
                        <td title="'.$row['sol_id'].'">'.$nro.'</td>
                        <td>'.$row['cite'].'</td>
                        <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                        <td>'.$row['serv_cod'].'.- '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].' '.$row['abrev'].'</td>
                        <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                        <td align=center><b>'.$estado.'</b></td>
                        <td><b>'.$row['aclaracion'].'</b></td>
                        <td align=center>
                          <a href="javascript:abreVentana_sol(\''.site_url("").'/reporte_solicitud_poa/'.$row['sol_id'].'\');" class="btn btn-default" style="width:50%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'" id="0">
                            <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                          </a>
                        </td>
                        <td align=center>';
                          if($row['estado']==0){
                            $tabla.='
                            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#modal_aprobar_solcert" onclick="aprobar_solicitud('.$row['sol_id'].');" style="width:50%;" title="APROBAR SOLICITUD CERTIFICACION POA">
                              <img src="'.base_url().'assets/img/ok1.jpg" width="22" height="22"/>
                            </a>';
                          }
                        $tabla.='
                          
                        </td>
                        <td align=center>';
                          if($row['estado']==0){
                            $tabla.='
                            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#modal_anular_solcert"  onclick="anular_solicitud('.$row['sol_id'].');" style="width:50%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
                              <img src="'.base_url().'assets/img/delete.png" width="22" height="22"/>
                            </a>';
                          }
                        $tabla.='
                        </td>
                      </tr>';
                    }
                    $tabla.='
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="hb2">
                <div class="table-responsive">
                  <table id="dt_basic2" class="table2 table-bordered" style="width:100%;">
                    <thead>
                      <tr style="height:40px;" bgcolor="#f6f6f6">
                        <th style="width:1%;">#</th>
                        <th style="width:12%;">UNIDAD SOLICITANTE</th>
                        <th style="width:8%;">CITE SOLICITUD</th>
                        <th style="width:8%;">FECHA SOLICITUD</th>
                        <th style="width:8%;">FECHA DE APROBACI&Oacute;N</th>
                        <th style="width:20%;">CODIGO CERTIFICACI&Oacute;N POA</th>
                        <th style="width:10%;">RESPONSABLE DE APROBACI&Oacute;N</th>
                        <th style="width:10%;">SUBACTIVIDAD</th>
                        <th style="width:10%;">REPORTE CERT. POA</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa_regional_aprobados($dep_id);
                    foreach($solicitudes as $row){
                      $nro++;
                      $tabla.='
                      <tr bgcolor="#f6fbf4">
                        <td title="'.$row['cpoa_id'].'" style="height:35px;">'.$nro.'</td>
                        <td>'.$row['serv_cod'].'.- '.$row['serv_descripcion'].' '.$row['abrev'].'</td>
                        <td>'.$row['cite'].'</td>
                        <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                        <td>'.date('d-m-Y',strtotime($row['cite_fecha'])).'</td>
                        <td><b>'.$row['cpoa_codigo'].'</b></td>
                        <td><b>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</b></td>
                        <td><b>'.$row['serv_cod'].' '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</b></td>
                        <td align=center>
                          <a href="javascript:abreVentana_sol(\''.site_url("").'/reporte_solicitud_poa_aprobado/'.$row['cpoa_id'].'\');" class="btn btn-default" style="width:50%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'" id="0">
                            <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                          </a>
                        </td>
                      </tr>';
                    }
                    $tabla.='
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>';

    return $tabla;
  }

/*------ LISTA DE SOLICITUDES CERTIFICACION POA REALIZADAS POR SUBACTIVIDAD -------*/
  public function lista_solicitudes_certificacionespoa($com_id){
    $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa($com_id);
    $tabla='';
    $tabla.=' 
      <style>
        table{font-size: 10px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>
      <div class="jarviswidget jarviswidget-color-darken" >
        <header>
            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
            <h2 class="font-md"><strong>MIS SOLICITUDES DE CERTIFICACIÓN POA</strong></h2>  
        </header>
          <div>
              <input name="base" type="hidden" value="'.base_url().'">
              <div class="widget-body no-padding">
                 <table id="dt_basic" class="table table-bordered" style="width:100%;">
                  <thead>
                    <tr style="height:35px;">
                      <th style="width:1%;">#</th>
                      <th style="width:5%;">CITE SOLICITUD</th>
                      <th style="width:5%;">FECHA SOLICTUD</th>
                      <th style="width:5%;">UNIDAD RESPONSABLE</th>
                      <th style="width:15%;">DETALLE ACTIVIDAD</th>
                      <th style="width:6%;">ESTADO</th>
                      <th style="width:8%;">CERTIFICACIÓN POA</th>
                      <th style="width:12%;">OBSERVACIÓN</th>
                      <th style="width:6%;">SOLICITUD</th>
                      <th style="width:6%;">ANULAR</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  foreach($solicitudes as $row){
                    $nro++;
                    $codigo_cpoa='';
                    $color='#f7cbcb';
                    $estado='EN REVISIÓN';

                    if($row['estado']==1){
                      $certpoa=$this->model_certificacion->get_solicitud_certificado($row['sol_id']);
                      $codigo_cpoa=$certpoa[0]['cpoa_codigo'];
                      $color='#d9f9f5';
                      $estado='APROBADO';
                    }
                    elseif($row['estado']==2){
                      $color='#fbecdb';
                      $estado='RECHAZADO';
                    }
                    $tabla.='
                    <tr bgcolor='.$color.'>
                      <td title="'.$row['sol_id'].'">'.$nro.'</td>
                      <td>'.$row['cite'].'</td>
                      <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                      <td><b>'.$row['tipo_subactividad'].'.- '.$row['serv_descripcion'].'</b></td>
                      <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                      <td align=center><b>'.$estado.'</b></td>
                      <td><b>'.$codigo_cpoa.'</b></td>
                      <td>'.$row['aclaracion'].'</td>
                      <td align=center>
                        <a href="#" class="btn btn-default ver_solicitud" style="width:100%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'">
                          <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                        </a>
                      </td>
                      <td align=center>';
                        if($row['estado']==0 || $row['estado']==2){
                          $tabla.='
                          <a href="#" class="btn btn-default del_solicitud" style="width:100%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
                            <img src="'.base_url().'assets/img/delete.png" width="22" height="22"/>
                          </a>';
                        }
                      $tabla.='
                      </td>
                    </tr>';
                  }

                  /*-----  Lista de Solicitudes filtrando a Bienes y servicios  -----*/
                  $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa_bienes_servicios($com_id);
                  foreach($solicitudes as $row){
                    $nro++;
                    $codigo_cpoa='';
                    $color='#f7cbcb';
                    $estado='EN REVISIÓN';

                    if($row['estado']==1){
                      $certpoa=$this->model_certificacion->get_solicitud_certificado($row['sol_id']);
                      $codigo_cpoa=$certpoa[0]['cpoa_codigo'];
                      $color='#d9f9f5';
                      $estado='APROBADO';
                    }
                    elseif($row['estado']==2){
                      $color='#fbecdb';
                      $estado='RECHAZADO';
                    }
                    $tabla.='
                    <tr bgcolor='.$color.'>
                      <td title="'.$row['sol_id'].'">'.$nro.'</td>
                      <td>'.$row['cite'].'</td>
                      <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                      <td><b>72 - BIENES Y SERVICIOS</b></td>
                      <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                      <td align=center><b>'.$estado.'</b></td>
                      <td><b>'.$codigo_cpoa.'</b></td>
                      <td>'.$row['aclaracion'].'</td>
                      <td align=center>
                        <a href="#" class="btn btn-default ver_solicitud" style="width:100%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'">
                          <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                        </a>
                      </td>
                      <td align=center>';
                        if($row['estado']==0 || $row['estado']==2){
                          $tabla.='
                          <a href="#" class="btn btn-default del_solicitud" style="width:100%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
                            <img src="'.base_url().'assets/img/delete.png" width="22" height="22"/>
                          </a>';
                        }
                      $tabla.='
                      </td>
                    </tr>';
                  }
                  $tabla.='
                  </tbody>
                </table>
              </div>
          </div>
      </div>';

    return $tabla;
  }

//// REPORTE MODIFICACION POA
  //// Cabecera Modifcacion poa
    public function cabecera_modpoa($cite){
      $tabla='';
      $codigo='Sin Codigo ... debbe cerra la modificación poa ';
      if($cite[0]['cite_codigo']!=''){
        $codigo=$cite[0]['cite_codigo'];
      }

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
                '.strtoupper($cite[0]['dist_distrital']).' '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <td style="height: 30%;"><b>MODIFICACIÓN POA '.$this->gestion.' - REQUERIMIENTOS</b></td>
                        </tr>
                        <tr style="font-size: 20px;font-family: Arial;">
                          <td style="height: 5%;">'.$codigo.'</td>
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:50%;">
                </td>
                <td style="width:50%; height: 3%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 15px;font-family: Arial;">
                          <td colspan=2 align=center style="width:100%;height: 40%;"><b>FORMULARIO MOD. N° 8 </b></td>
                      </tr>
                      <tr style="font-size: 10px;font-family: Arial;">
                          <td style="width:47%;height: 30%;"><b>CITE : '.$cite[0]['cite_nota'].'</b></td>
                          <td style="width:47%;height: 30%"><b>FECHA : '.date('d-m-Y',strtotime($cite[0]['cite_fecha'])).'</b></td>
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
          <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 3%;">
               
                      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($cite[0]['dep_departamento']).'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.strtoupper ($cite[0]['dist_distrital']).'</td></tr>
                                </table>
                            </td>
                        </tr>';

                          if($cite[0]['tp_id']==1){
                            $tabla.='
                            <tr>
                              <td style="width:20%;">
                                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>PROY. INVERSI&Oacute;N</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['proy_sisin'].' '.strtoupper ($cite[0]['proy_nombre']).'</td></tr>
                                  </table>
                              </td>
                            </tr>
                            <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>UNIDAD RESP.</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>';
                          }
                          else{
                            $tabla.='
                            <tr>
                              <td style="width:20%;">
                                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                      <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                  </table>
                              </td>
                              <td style="width:80%;">
                                  <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                      <tr><td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['aper_actividad'].' '.strtoupper ($cite[0]['act_descripcion']).' '.$cite[0]['abrev'].'</td></tr>
                                  </table>
                              </td>
                            </tr>
                            <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 40%;" bgcolor="#cae4fb"><b>SUBACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 40%;" bgcolor="#f9f9f9">&nbsp;'.$cite[0]['tipo_subactividad'].' '.$cite[0]['serv_descripcion'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>';
                          }
                        $tabla.='
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


  //// Lista de Items Modificados en la Edicion
  public function items_modificados_edicionpoa($cite_id){
    $tabla='';
      $tabla.='
      <table border=0 style="width:100%;">
        <tr>
          <td style="width:1%;"></td>
          <td style="width:98%;">';

            $requerimientos_mod = $this->model_modrequerimiento->list_requerimientos_modificados($cite_id);
            if(count($requerimientos_mod)!=0){
              $tabla.='<div style="font-size: 10px;height:16px;"><b>ITEMS MODIFICADOS ('.count($requerimientos_mod).')</b></div>';
              $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
              $tabla.='<thead>';
              $tabla.='<tr class="modo1" style="text-align: center;" bgcolor="#efefef">';
                $tabla.='<th style="width:1.3%;height:20px;">#</th>';
                $tabla.='<th style="width:2.5%;">COD.<br>OPE.</th>';
                $tabla.='<th style="width:3.5%;">PARTIDA</th>';
                $tabla.='<th style="width:12%;">DETALLE REQUERIMIENTO</th>';
                $tabla.='<th style="width:4%;">UNIDAD<br>MEDIDA</th>';
                $tabla.='<th style="width:4.5%;">CANTIDAD</th>';
                $tabla.='<th style="width:4.5%;">UNITARIO</th>';
                $tabla.='<th style="width:6%;">COSTO TOTAL</th>';
                $tabla.='<th style="width:4.5%;">ENE.</th>';
                $tabla.='<th style="width:4.5%;">FEB.</th>';
                $tabla.='<th style="width:4.5%;">MAR.</th>';
                $tabla.='<th style="width:4.5%;">ABR.</th>';
                $tabla.='<th style="width:4.5%;">MAY.</th>';
                $tabla.='<th style="width:4.5%;">JUN.</th>';
                $tabla.='<th style="width:4.5%;">JUL.</th>';
                $tabla.='<th style="width:4.5%;">AGO.</th>';
                $tabla.='<th style="width:4.5%;">SEPT.</th>';
                $tabla.='<th style="width:4.5%;">OCT.</th>';
                $tabla.='<th style="width:4.5%;">NOV.</th>';
                $tabla.='<th style="width:4.5%;">DIC.</th>';
                $tabla.='<th style="width:7.5%;">OBSERVACIONES</th>';
              $tabla.='</tr>';
              $tabla.='</thead>';
              $tabla.='<tbody>';
              $nro=0;
              $monto=0;
              foreach ($requerimientos_mod as $row){
                $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                $nro++;
                $tabla.='<tr class="modo1">';
                  $tabla.='<td style="width: 1.3%; text-align: center;" style="height:16px;">'.$nro.'</td>';
                  $tabla.='<td style="width: 2.5%; text-align: center;font-size: 12px;"><b>'.$row['prod_cod'].'</b></td>';
                  $tabla.='<td style="width: 3.5%; text-align: center;">'.$row['par_codigo'].'</td>';
                  $tabla.='<td style="width: 12%; text-align: left;">'.$row['ins_detalle'].'</td>';
                  $tabla.='<td style="width: 4%; text-align: left;">'.$row['ins_unidad_medida'].'</td>';
                  $tabla.='<td style="width: 4.5%; text-align: right;">'.$row['ins_cant_requerida'].'</td>';
                  $tabla.='<td style="width: 4.5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                  $tabla.='<td style="width: 6%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                  if(count($prog)!=0){
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes1'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes2'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes3'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes4'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes5'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes6'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes7'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes8'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes9'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes10'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes11'], 2, ',', '.') . '</td>';
                    $tabla .= '<td style="width: 4.5%; text-align: right;">' . number_format($prog[0]['mes12'], 2, ',', '.') . '</td>';
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
                  $tabla.='<td style="width: 7.5%; text-align: left;">'.$row['ins_observacion'].'</td>';
                $tabla.='</tr>';
                $monto=$monto+$row['ins_costo_total'];
              }
              $tabla.='</tbody>
                <tr class="modo1">
                  <td style="height:10px;" colspan=7></td>
                  <td style="text-align: right;">' . number_format($monto, 2, ',', '.') . '</td>
                  <td colspan=13></td>
                </tr>
              </table><br>';
            }
          $tabla.='
            <div style="font-size: 8px;font-family: Arial;">
              En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

              &nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
              &nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
              &nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
              &nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
            </div>
          </td>
          <td style="width:1%;"></td>
        </tr>
      </table>';
    
    return $tabla;
  }


  //// Pie de Modificacion POA
  public function pie_modpoa($cite,$codigo_certificacion){
    $tabla='';
    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
        <tr>
          <td style="width: 1%;"></td>
          <td style="width: 55%;">
              <b>OBSERVACIÓN</b><hr>
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr bgcolor="#cae4fb">
                  <td style="width: 100%;height: 2%;">
                    MODIFICACIÓN POA SEGUN NOTA CITE : '.$cite[0]['cite_nota'].' EN FECHA '.date('d-m-Y',strtotime($cite[0]['cite_fecha'])).' CON LA SIGUIENTE JUSTIFICACIÓN TECNICA : '.$cite[0]['cite_observacion'].', QUE CORRESPONDE A LA SIGUIENTE CERTIFICACIÓN POA <b>'.$codigo_certificacion.'</b>
                  </td>
                </tr>
              </table>
          </td>
        </tr>
      </table>
      <hr>
      <table border=0 style="width:100%;">
        <tr>
          <td style="width:1%;"></td>
          <td style="width:98%;">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>
                  <td style="width:45%;">
                       <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><br><br><br>
                                <table>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>RESPONSABLE : </b></td>
                                        <td>'.$cite[0]['fun_nombre'].' '.$cite[0]['fun_paterno'].' '.$cite[0]['fun_materno'].'</td>
                                    </tr>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>CARGO : </b></td>
                                        <td>'.$cite[0]['fun_cargo'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                  </td>
                  <td style="width:45%;">

                    <table border="0.5" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="width:100%;height:13px;"><b>FIRMA / SELLO DE RECEPCION DE LA UNIDAD SOLICITANTE (FECHA)<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td><b><br><br><br><br><br>FIRMA</b></td>
                        </tr>
                    </table>

                  </td>
                  <td style="width:10%;" align=center>
                    <qrcode value="'.$cite[0]['cite_codigo'].' '.$codigo_certificacion.'" style="border: none; width: 18mm;"></qrcode>
                  </td>
                </tr>
                <tr>
                  <td colspan=2 style="height:18px;">'.$this->session->userdata('sistema').'</td>
                  <td align=right>'.$cite[0]['fun_paterno'].' - pag. [[page_cu]]/[[page_nb]]</td>
                </tr>
              </table>
          </td>
          <td style="width:1%;"></td>
        </tr>
      </table>';

    return $tabla;
  }

  /// Menu Seguimiento POA (Sub Actividad)
   public function menu_segpoa($com_id,$tp){
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
            <a href="'.site_url("").'/dashboar_seguimiento_poa" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
            </li>';
              if($tp==1){
                $tabla.='
                <li class="text-center">
                  <a href="#" title="REGISTRO DE SEGUIMIENTO"> <span class="menu-item-parent">SEG. EVAL. POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Seg. y eval. POA</span></a>
                </li>';
              }
              elseif ($tp==2) {
                $tabla.='
                <li class="text-center">
                  <a href="#" title="SOLICITUD DE CERTIFICACION POA"> <span class="menu-item-parent">CERTIFICACIÓN POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Certificación POA</span></a>
                  <ul>
                    <li>
                      <a href="'.site_url("").'/solicitar_certpoa/'.$com_id.'">Solicitar Certificación POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                    </li>
                    <li>
                      <a href="'.site_url("").'/solicitar_certpoa_bservicios/'.$com_id.'">Solicitar Certificación POA (72 Bienes y Servicios)<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                    </li>
                    <li>
                      <a href="'.site_url("").'/mis_solicitudes_cpoa/'.$com_id.'">Mis Solicitudes POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                    </li>
                  </ul>
                </li>';
              }
              elseif ($tp==3) {
                $tabla.='
                <li class="text-center">
                  <a href="#" title="REPORTE POA"> <span class="menu-item-parent">REPORTES POA</span></a>
                </li>
                <li>
                  <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Reportes POA</span></a>
                </li>';
              }
            $tabla.='
            
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

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