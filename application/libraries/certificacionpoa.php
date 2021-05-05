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
    if($this->gestion>2020){ /// 2021
      if($datos[0]['tp_id']==1){ /// Proyecto de Inversion
        $tabla.=' <h1><b>PROYECTO : </b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['proy_sisin'].' 00 - '.$datos[0]['proy_nombre'].'</small>
                  <h1><b>UNIDAD RESPONSABLE : </b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                  <h1><b>OPERACI&Oacute;N : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $tabla.=' <h1><b>ACTIVIDAD : <b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['aper_proyecto'].' '.$datos[0]['aper_actividad'].' - '.$datos[0]['tipo'].' '.$datos[0]['act_descripcion'].' '.$datos[0]['abrev'].'</small></h1>
                  <h1><b>SUBACTIVIDAD : <b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                  <h1><b>OPERACI&Oacute;N : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
    }
    else{ /// 2020
      if($datos[0]['tp_id']==1){ /// Proyecto de Inversion
        $tabla.=' <h1><b>APERTURA PROGRAM&Aacute;TICA : </b><small>'.$datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' - '.$datos[0]['proy_nombre'].'</small>
                  <h1><b>COMPONENTE : </b><small>'.$datos[0]['com_componente'].'</small></h1>
                  <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $tabla.=' <h1><b> '.$datos[0]['tipo_adm'].' : <b><small>'.$datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' - '.$datos[0]['tipo'].' '.$datos[0]['act_descripcion'].' '.$datos[0]['abrev'].'</small></h1>
                  <h1><b> SERVICIO : <b><small>'.$datos[0]['com_componente'].'</small></h1>
                  <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
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

        if($this->gestion>2020){
          $codigo='CPOA/'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
        }
        else{
          $codigo='CPOA_'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
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
                  <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N ACTIVIDAD">ACTIVIDAD</th>
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
                        <td align=center title="'.$row['proy_id'].'-'.$row['aper_id'].'"><b>'.$nro.'</b></td>
                        <td align=center>
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" title="SELECCIONAR ACTIVIDAD"> 
                          <i class="glyphicon glyphicon-list"></i> SELECCIONAR OPERACION</a>
                        </td>
                        <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                        <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                        <td>'.$row['escalon'].'</td>
                        <td>'.$row['nivel'].'</td>
                        <td>'.$row['tipo_adm'].'</td>
                        <td>'.strtoupper($row['dep_departamento']).'</td>
                        <td>'.strtoupper($row['dist_distrital']).'</td>
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
                        <td align=center><b>'.$nro.'</b></td>
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
                        <td>'.strtoupper($row['dist_distrital']).'</td>
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
                          <i class="glyphicon glyphicon-list"></i> SELECCIONAR OPERACIÓN</a>';
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
      $titulo='UNIDAD RESPONSABLE';
      if($proyecto[0]['tp_id']==4){
        $titulo='SUBACTIVIDAD';
      }


      $productos = $this->model_certificacion->list_operaciones_x_subactividad_ppto($proy_id); /// PRODUCTOS
      $tabla='';
      if($this->gestion>2020){ /// 2021
      $tabla='          
          ';
      $tabla.='
      <form >
        <section class="col col-6">
          <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
        </section>
        <table class="table table-bordered" border=1 style="width:100%;" id="datos">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#3276b1">#</th>
              <th style="width:9%;" bgcolor="#3276b1" title="SUB ACTIVIDAD">'.$titulo.'</th>';
              if($proyecto[0]['tp_id']==1){
                $tabla.='<th style="width:10%;" bgcolor="#3276b1" title="COMPONENTE">COMPONENTE</th>';
              }
              $tabla.='
              <th style="width:1%;" bgcolor="#3276b1" title="CÓDIGO">COD. OPE.</th>
              <th style="width:17%;" bgcolor="#3276b1" title="OPERACIÓN">OPERACI&Oacute;N</th>
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
                <td><b>'.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</b></td>';
                if($proyecto[0]['tp_id']==1){
                  $tabla.='<td>'.$row['com_componente'].'</td>';
                }
                $tabla.='
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
  public function list_requerimientos_certificados($cpoa_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
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
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
          }

          $bgcolor='#f2fded';
          if(count($this->model_certificacion->get_insumo_monto_cpoa_certificado($row['ins_id'],$cpoa_id))==0){
            $bgcolor='#f59787';
          }

          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.' title='.$row['ins_id'].' id="tr'.$nro.'" >
            <td>'.$nro.'</td>
            <td>
              <input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value,'.$nro.','.$cpoa_id.',this.checked);" checked="checked"/><br>
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
            for ($i=1; $i <=12 ; $i++) {
              $color=''; 
              $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
              $tabla.='
              <td align=right>
                <table align=right>
                  <tr>
                    <td>
                      <div id="m'.$i.''.$row['ins_id'].'">';
                      if(count($m)!=0){
                        if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                          $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);"/>';
                        }
                        elseif(count($this->model_certificacion->get_mes_certificado_cpoa($cpoa_id,$m[0]['tins_id']))==1){
                          $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);" checked="checked"/>';
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
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }




 ///// SUBACTIVIDAD
    //// SELECCION DE OPERACIONES 
  public function select_mis_productos($com_id,$titulo){
    $productos=$this->model_certificacion->get_operaciones_x_subactividad_ppto($com_id);
    $tabla='';
    $tabla='
      <form class="form-horizontal">
        <input name="base" type="hidden" value="'.base_url().'">
        <fieldset>
          <legend><b>'.$titulo.'</b></legend>
          <div class="form-group">
            <label class="col-md-2 control-label">SELECCIONE OPERACI&Oacute;N</label>
            <div class="col-md-6">
              <select class="form-control" name="prod_id" id="prod_id">
                <option value="0">Seleccione Operación</option>';
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





/*------- LISTA DE REQUERIMIENTOS PRE LISTA ------*/
  public function list_requerimientos_prelista($prod_id){
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
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;'.$solicitud[0]['aper_actividad'].' '.strtoupper ($solicitud[0]['act_descripcion']).' '.$solicitud[0]['abrev'].'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>SUBACTIVIDAD</b></td><td style="width:5%;"></td></tr>
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
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>II. ARTICULACI&Oacute;N POA 2021 Y PEI 2016-2020</b></td>
                        </tr>
                    </table><br>
                    <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <thead>
                            <tr style="font-size: 8px; font-family: Arial;" align="center" >
                                <th style="width:5%;height: 1.5%;">COD. OPE.</th>
                                <th style="width:30%;">OPERACI&Oacute;N</th>
                                <th style="width:5%;">COD. OR.</th>
                                <th style="width:30%;">OBJETIVO REGIONAL</th>
                                <th style="width:30%;">ACCIÓN ESTRATEGICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:5%;height: 4%;font-size: 13px;" align="center"><b>'.$solicitud[0]['prod_cod'].'</b></td>
                                <td style="width:30%;">'.$solicitud[0]['prod_producto'].'</td>
                                <td style="width:5%;font-size: 13px;" align="center"><b>'.$solicitud[0]['or_codigo'].'</b></td>
                                <td style="width:30%;">'.$solicitud[0]['or_objetivo'].'</td>
                                <td style="width:30%;"><b>'.$solicitud[0]['acc_codigo'].'</b> '.$solicitud[0]['acc_descripcion'].'</td>
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




  /*-- III DETALLE DE REQUERIMIENTOS A SOLICITUD --*/
  public function lista_solicitud_requerimientos($sol_id){
    $tabla='';

    $requerimientos=$this->model_certificacion->get_lista_requerimientos_solicitados($sol_id);
    $tabla.='
      <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
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
              <tr style="font-size: 8px; font-family: Arial;">
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
              <td colspan=6 align=right><b>MONTO A CERTIFICAR : </b></td>
              <td style="font-size: 9px;" align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
              <td></td>
            </tr>
      </table>';

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
                  $tabla.='<td style="width:100%;height: 2%;color: green;"><b>SOLICITUD APROBADO</b></td>';
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

/*------ LISTA DE SOLICITUDES CERTIFICACION POA REALIZADAS POR REGIONAL -------*/
  public function lista_solicitudes_certificacionespoa_regionall($dep_id){
    $tabla='Hola Mundo';

    return $tabla;
  }

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
              <a data-toggle="tab" href="#hb2"> <i class="fa fa-lg fa-arrow-circle-o-up"></i> <span class="hidden-mobile hidden-tablet"> CERTIFICACIONES POA APROBADOS </span> </a>
            </li>
          </ul>
        </header>
      <div>
    
      <div class="jarviswidget-editbox"></div>
          <div class="widget-body">
            <div class="tab-content">
              <div class="tab-pane active" id="hb1">
                  
                  <table id="dt_basic" class="table table-bordered" style="width:100%;">
                    <thead>
                      <tr style="height:35px;">
                        <th style="width:1%;">#</th>
                        <th style="width:10%;">CITE SOLICITUD</th>
                        <th style="width:10%;">FECHA SOLICTUD</th>
                        <th style="width:20%;">OPERACIÓN</th>
                        <th style="width:10%;">ESTADO</th>
                        <th style="width:10%;">SOLICITUD</th>
                        <th style="width:10%;">CERTIFICACIÓN POA</th>
                        <th style="width:10%;">ANULAR</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa_regional($dep_id,0);
                    foreach($solicitudes as $row){
                      $nro++;
                      $color='#d9f9f5';
                      $estado='APROBADO';
                      if($row['estado']==0){
                        $color='#f7cbcb';
                        $estado='NO APROBADO';
                      }
                      $tabla.='
                      <tr bgcolor='.$color.'>
                        <td title="'.$row['sol_id'].'">'.$nro.'</td>
                        <td>'.$row['cite'].'</td>
                        <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                        <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                        <td align=center><b>'.$estado.'</b></td>
                        <td align=center>
                          <a href="javascript:abreVentana_sol(\''.site_url("").'/reporte_solicitud_poa/'.$row['sol_id'].'\');" class="btn btn-default" style="width:50%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'" id="0">
                            <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                          </a>
                        </td>
                        <td align=center>
                          <a href="#" class="btn btn-default" onclick="aprobar_solicitud('.$row['sol_id'].');" style="width:50%;" title="APROBAR SOLICITUD CERTIFICACION POA">
                            <img src="'.base_url().'assets/img/ok1.JPG" width="22" height="22"/>
                          </a>
                        </td>
                        <td align=center>';
                          if($row['estado']==0){
                            $tabla.='
                            <a href="#" class="btn btn-default del_solicitud" style="width:50%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
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
              <div class="tab-pane" id="hb2">

                  <table id="dt_basic2" class="table2 table-bordered" style="width:100%;">
                    <thead>
                      <tr style="height:35px;">
                        <th style="width:1%;">#</th>
                        <th style="width:10%;">CITE SOLICITUD</th>
                        <th style="width:10%;">FECHA SOLICTUD</th>
                        <th style="width:20%;">OPERACIÓN</th>
                        <th style="width:10%;">ESTADO</th>
                        <th style="width:10%;">SOLICITUD</th>
                        <th style="width:10%;">CERTIFICACIÓN POA</th>
                        <th style="width:10%;">ANULAR</th>
                      </tr>
                    </thead>
                    <tbody>';
                    $nro=0;
                    $solicitudes=$this->model_certificacion->lista_solicitudes_cpoa_regional($dep_id,1);
    /*                foreach($solicitudes as $row){
                      $nro++;
                      $color='#d9f9f5';
                      $estado='APROBADO';
                      if($row['estado']==0){
                        $color='#f7cbcb';
                        $estado='NO APROBADO';
                      }
                      $tabla.='
                      <tr bgcolor='.$color.'>
                        <td title="'.$row['sol_id'].'">'.$nro.'</td>
                        <td>'.$row['cite'].'</td>
                        <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                        <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                        <td align=center><b>'.$estado.'</b></td>
                        <td align=center>
                          <a href="#" class="btn btn-default ver_solicitud" style="width:50%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'" id="0">
                            <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                          </a>
                        </td>
                        <td align=center>';
                          if($row['estado']==1){
                            $tabla.='
                            <a href="#" class="btn btn-default ver_solicitud" style="width:50%;" title="VER CERTIFICACION POA" name="'.$row['sol_id'].'" id="1">
                              <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                            </a>';
                          }
                        $tabla.='
                        </td>
                        <td align=center>';
                          if($row['estado']==0){
                            $tabla.='
                            <a href="#" class="btn btn-default del_solicitud" style="width:50%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
                              <img src="'.base_url().'assets/img/delete.png" width="22" height="22"/>
                            </a>';
                          }
                        $tabla.='
                        </td>
                      </tr>';
                    }*/
                    $tabla.='
                    </tbody>
                  </table>
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
                      <th style="width:10%;">CITE SOLICITUD</th>
                      <th style="width:10%;">FECHA SOLICTUD</th>
                      <th style="width:20%;">OPERACIÓN</th>
                      <th style="width:10%;">ESTADO</th>
                      <th style="width:10%;">SOLICITUD</th>
                      <th style="width:10%;">CERTIFICACIÓN POA</th>
                      <th style="width:10%;">ANULAR</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  foreach($solicitudes as $row){
                    $nro++;
                    $color='#d9f9f5';
                    $estado='APROBADO';
                    if($row['estado']==0){
                      $color='#f7cbcb';
                      $estado='NO APROBADO';
                    }
                    $tabla.='
                    <tr bgcolor='.$color.'>
                      <td title="'.$row['sol_id'].'">'.$nro.'</td>
                      <td>'.$row['cite'].'</td>
                      <td>'.date('d-m-Y',strtotime($row['fecha'])).'</td>
                      <td>'.$row['prod_cod'].'.- '.$row['prod_producto'].'</td>
                      <td align=center><b>'.$estado.'</b></td>
                      <td align=center>
                        <a href="#" class="btn btn-default ver_solicitud" style="width:50%;" title="VER SOLICITUD DE CERTIFICACION POA" name="'.$row['sol_id'].'" id="0">
                          <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                        </a>
                      </td>
                      <td align=center>';
                        if($row['estado']==1){
                          $tabla.='
                          <a href="#" class="btn btn-default ver_solicitud" style="width:50%;" title="VER CERTIFICACION POA" name="'.$row['sol_id'].'" id="1">
                            <img src="'.base_url().'assets/ifinal/requerimiento.png" width="22" height="22"/>
                          </a>';
                        }
                      $tabla.='
                      </td>
                      <td align=center>';
                        if($row['estado']==0){
                          $tabla.='
                          <a href="#" class="btn btn-default del_solicitud" style="width:50%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$row['sol_id'].'">
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




  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_segpoa($com_id){
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
              <a href="#" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
              </li>
              <li class="text-center">
                  <a href="#" title="REGISTRO DE SEGUIMIENTO, EVALUACIÓN Y CERTIFICACIÓN POA"> <span class="menu-item-parent">SEG. EVAL. POA</span></a>
              </li>
              <li>
                <a href="'.site_url("").'/seguimiento_poa"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Seg. y eval. POA</span></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Certificación POA</span></a>
                <ul>
                  <li>
                    <a href="'.site_url("").'/solicitar_certpoa/'.$com_id.'">Solicitar Certificación POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                  <li>
                    <a href="'.site_url("").'/mis_solicitudes_cpoa/'.$com_id.'">Mis Solicitudes POA<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                  </li>
                </ul>
              </li>
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }
}
?>