<?php
class Cprog_insumo extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;
   public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_entidad_tras');
      //  $this->load->model('programacion/insumos/minsumos_delegado');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tp_adm = $this->session->userData('tp_adm');
      }else{
          redirect('/','refresh');
      }
    }

    /*--- TIPO DE RESPONSABLE ---*/
    function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='RESPONSABLE NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }

    /*--- VERIFICAR PLANTILLAS DE MIGRACION ---*/
    function verificar_plantilla(){
      $data['menu']=$this->menu(2);
      $data['mod']=1;
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();

      $this->load->view('admin/programacion/proy_anual/top/menu_plantilla', $data);
    }

    /*--- MIGRACION DE FORM4 (2020-2022) Y FORM 5 ---*/
    function ver_operaciones_requerimientos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $tp = $this->security->xss_clean($post['tp']); /// tipo de migracion

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          $nro_ok=0; $nro_ncumple=0; $nro_npartida=0;
          $ncump=array();$naprob=array();$aprob=array();

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {   
            /*------------------- Migrando ---------------*/
            $lineas = file($archivotmp);
            $i=0;
            $nro=0;
            $guardado=0;
            $no_guardado=0;
            $nro_prod=1;

            if($tp==1){  /// Actividades

              foreach ($lineas as $linea_num => $linea){ 
                if($i != 0){
                  $datos = explode(";",$linea);

                    $cod_or = intval(trim($datos[0])); // Codigo Objetivo Regional
                    $cod_ope = intval($nro_prod); // Codigo Form 4
                    $descripcion = utf8_encode(trim($datos[2])); //// descripcion form 4
                    $resultado = utf8_encode(trim($datos[3])); //// descripcion Resultado

                    $unidad = utf8_encode(trim($datos[4])); //// Unidad
                    $indicador = utf8_encode(trim($datos[5])); //// descripcion Indicador
                    $lbase = intval(trim($datos[6])); //// Linea Base
                    if(intval(trim($datos[6]))==''){
                      $lbase = 0; //// Linea Base
                    }

                    $meta = intval(trim($datos[7])); //// Meta
                    if(intval(trim($datos[7]))==''){
                      $meta = 0; //// Meta
                    }

                    $var=8; $sum_temp=0;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=(float)$datos[$var]; //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $sum_temp=$sum_temp+$m[$i];
                      $var++;
                    }

                    $mverificacion = utf8_encode(trim($datos[20])); //// Medio de verificacion

                  if(count($datos)==21){
                    if($sum_temp==$meta){
                      $nro_ok++;
                      $aprob[1][$nro_ok]=$cod_ope; // cod. operacion
                      $aprob[2][$nro_ok]=strtoupper($descripcion); // Operacion
                      $aprob[3][$nro_ok]=strtoupper($resultado); // resultado
                      $aprob[4][$nro_ok]=strtoupper($indicador); // Indicador
                      $aprob[5][$nro_ok]=$lbase; // Linea Base
                      $aprob[6][$nro_ok]=$meta; // meta
                      $aprob[7][$nro_ok]=$unidad; // unidad responsable
                      $aprob[8][$nro_ok]=$sum_temp; // Suma total temporalidad
                      $aprob[9][$nro_ok]=$mverificacion; // Medio de verificacion

                      $vari=10;
                      for ($i=1; $i <=12 ; $i++) { 
                        $aprob[$vari][$nro_ok]=$m[$i];
                        $vari++;
                      }
                    }
                    else{
                      $nro_npartida++;
                      $naprob[1][$nro_npartida]=$cod_ope; // cod. operacion
                      $naprob[2][$nro_npartida]=strtoupper($descripcion); // Operacion
                      $naprob[3][$nro_npartida]=strtoupper($resultado); // resultado
                      $naprob[4][$nro_npartida]=strtoupper($indicador); // Indicador
                      $naprob[5][$nro_npartida]=$lbase; // Linea Base
                      $naprob[6][$nro_npartida]=$meta; // meta
                      $naprob[7][$nro_npartida]=$unidad; // unidad responsable
                      $naprob[8][$nro_npartida]=$sum_temp; // total
                      $naprob[9][$nro_npartida]=$mverificacion; // Medio de verificacion

                      $vari=10;
                      for ($i=1; $i <=12 ; $i++) { 
                        $naprob[$vari][$nro_npartida]=$m[$i];
                        $vari++;
                      }
                    }
                  }
                  else{
                    $nro_ncumple++;
                    $ncump[1][$nro_ncumple]=$cod_ope;
                    $ncump[2][$nro_ncumple]=strtoupper($descripcion);
                    $ncump[3][$nro_ncumple]=strtoupper($resultado);
                    $ncump[4][$nro_ncumple]=count($datos);
                  }
                }
                $i++;
              }

              $data['bien']=$this->vista_prev_actividades($aprob,$nro_ok,1);
              $data['regular']=$this->vista_prev_actividades($naprob,$nro_npartida,2);
              $data['mal']=$this->vista_prev_actividades_error($ncump,$nro_ncumple);
              $data['titulo']='ACTIVIDADES';
            }
            else{ /// Requerimientos

            /*$nro_ok=0; $nro_ncumple=0; $nro_npartida=0; $suma_monto=0;
            $ncump=array();$naprob=array();$aprob=array();*/
            //Recorremos el bucle para leer línea por línea
            $monto_aprob=0;$monto_corregir=0;
            foreach ($lineas as $linea_num => $linea){
              if($i != 0){
                  $datos = explode(";",$linea);
                    $numero = intval(trim($datos[0])); //// Numero correlativo o Cod act.
                    $cod_partida = intval(trim($datos[1])); //// Codigo partida
                    $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                    $detalle = utf8_encode(trim($datos[2])); //// descripcion
                  if(count($datos)==20){

                    $unidad = utf8_encode(trim($datos[3])); //// Unidad
                    $cantidad = intval(trim($datos[4])); //// Cantidad
                    $unitario = intval(trim($datos[5])); //// Costo Unitario
                    $total = intval(trim($datos[6])); //// Costo Total

                    $p_total=round(($cantidad*$unitario),2);

                    $var=7; $sum_temp=0;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=$datos[$var]; //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $sum_temp=$sum_temp+$m[$i];
                      $var++;
                    }

                    $observacion = utf8_encode(trim($datos[19])); //// Observacion
                    if(count($par_id)!=0 & $cod_partida!=0 & round($sum_temp,2)==round($total,2)){
                        $nro_ok++;
                        $aprob[1][$nro_ok]=$numero;
                        $aprob[2][$nro_ok]=$cod_partida;
                        $aprob[3][$nro_ok]=$detalle ;
                        $aprob[4][$nro_ok]=$unidad;
                        $aprob[5][$nro_ok]=$cantidad;
                        $aprob[6][$nro_ok]=$unitario;
                        $aprob[7][$nro_ok]=$total;
                        $aprob[8][$nro_ok]=$sum_temp;

                        $vari=9;
                        for ($i=1; $i <=12 ; $i++) { 
                          $aprob[$vari][$nro_ok]=$m[$i];
                          $vari++;
                        }
                        //echo $numero.". - INSUMO: ".$detalle." - MONTO : ".$total." SUMA : ".$aprob[8][$nro_ok]."<br>";
                        $monto_aprob=$monto_aprob+$total;
                    }
                    else{
                        //echo "NO CUMPLE PARTIDA Y CODIGO :".$cod_partida." - INSUMO: ".$detalle."<br>";
                        $nro_npartida++;
                        $naprob[1][$nro_npartida]=$numero;
                        $naprob[2][$nro_npartida]=$cod_partida;
                        $naprob[3][$nro_npartida]=$detalle ;
                        $naprob[4][$nro_npartida]=$unidad;
                        $naprob[5][$nro_npartida]=$cantidad;
                        $naprob[6][$nro_npartida]=$unitario;
                        $naprob[7][$nro_npartida]=$total;
                        $naprob[8][$nro_npartida]=$sum_temp;

                        $vari=9;
                        for ($i=1; $i <=12 ; $i++) { 
                          $naprob[$vari][$nro_npartida]=$m[$i];
                          $vari++;
                        }

                        $monto_corregir=$monto_corregir+$total;
                    }

                  }
                  else{
                    //echo "NO CUMPLE 21 CAMPOS : ".count($datos)." - ".$detalle."<br>";
                    $nro_ncumple++;
                    $ncump[1][$nro_ncumple]=$nro_ncumple;
                    $ncump[2][$nro_ncumple]=$cod_partida;
                    $ncump[3][$nro_ncumple]=$detalle ;
                    $ncump[4][$nro_ncumple]=count($datos);
                  }

                }
                $i++;
              }

              $data['bien']=$this->vista_prev_requerimientos($aprob,$nro_ok,1,$monto_aprob);
              $data['regular']=$this->vista_prev_requerimientos($naprob,$nro_npartida,2,$monto_corregir);
              $data['mal']=$this->vista_prev_requerimientos_error($ncump,$nro_ncumple);
              $data['titulo']='REQUERIMIENTOS';
              
            } /// end else

            $data['menu']=$this->menu(2);
            $data['resp']=$this->session->userdata('funcionario');
            $data['res_dep']=$this->tp_resp();

            $this->load->view('admin/programacion/proy_anual/top/vprevia_requerimientos', $data);
          }
          else{
            $this->session->set_flashdata('danger','SELECCIONE ARCHIVO ');
            redirect('proy/verif_plantillas');
          }
      }
      else{
        echo "Error !!";
      }
    }






    /*----- ELIMINAR VARIOS REQUERIMIENTOS SELECCIONADOS -----*/
    public function delete_requerimientos(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $id = $this->security->xss_clean($post['id']);

        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

        if (!empty($_POST["ins"]) && is_array($_POST["ins"]) ) {
          foreach ( array_keys($_POST["ins"]) as $como){
            /*-------- DELETE INSUMO PROGRAMADO --------*/  
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->delete('temporalidad_prog_insumo');
            /*------------------------------------------*/

            /*---- DELETE INSUMO PRODUCTO ----*/  
              $this->db->where('ins_id', $_POST["ins"][$como]);
              $this->db->where('prod_id', $id);
              $this->db->delete('_insumoproducto');
            /*--------------------------------*/

            /*-------- DELETE INSUMO  --------*/  
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->delete('insumos');
            /*--------------------------------*/
          }

          $this->session->set_flashdata('success','SE ELIMINARON CORRECTAMENTE');
          redirect(site_url("").'/prog/requerimiento/'.$proy_id.'/'.$id);

        }
        else{
          echo "Error !!!!";
        }
      }
      else{
        echo "Error !!!";
      }
    }


      /*--- MIGRACION DE REQUERIMIENTOS A UNA ACTIVIDAD (2020) ---*/
      function importar_requerimientos_a_una_actividad(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
          $producto = $this->model_producto->get_producto_id($prod_id); ///// DATOS DEL PRODUCTO 
          $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion);
          $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']);
                   
          $tipo = $_FILES['archivo_csv']['type'];
          $tamanio = $_FILES['archivo_csv']['size'];
          $archivotmp = $_FILES['archivo_csv']['tmp_name'];

          $filename = $_FILES["archivo_csv"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          /*----------------------------------------------------------------------*/
          $nro_ok=0; $nro_ncumple=0; $nro_npartida=0; $suma_monto=0;
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              /*------------------- Migrando ---------------*/
              $lineas = file($archivotmp);
              $i=0;
              $nro=0;
              //Recorremos el bucle para leer línea por línea
              foreach ($lineas as $linea_num => $linea){ 
                if($i != 0){
                    $datos = explode(";",$linea);
                    
                    if(count($datos)==20){
                      $prod_cod = intval(trim($datos[0])); //// Codigo Actividad
                      $cod_partida = intval(trim($datos[1])); //// Codigo partida
                      $par_id = $this->model_insumo->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                      $detalle = utf8_encode(trim($datos[2])); //// descripcion
                      $unidad = utf8_encode(trim($datos[3])); //// Unidad
                      $cantidad = intval(trim($datos[4])); //// Cantidad
                      $unitario = intval(trim($datos[5])); //// Costo Unitario
                      
                      $p_total=($cantidad*$unitario);
                      $total = intval(trim($datos[6])); //// Costo Total

                      $var=7; $sum_temp=0;
                      for ($i=1; $i <=12 ; $i++) {
                        $m[$i]=$datos[$var]; //// Mes i
                        if($m[$i]==''){
                          $m[$i]=0;
                        }
                        $var++;
                        $sum_temp=$sum_temp+$m[$i];
                      }

                      $observacion = utf8_encode(trim($datos[19])); //// Observacion

                      if(count($par_id)!=0 & $cod_partida!=0 & round($sum_temp,2)==round($total,2)){
                        $nro++;
                        /*-------- INSERTAR DATOS REQUERIMIENTO ---------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array( 
                        'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                        'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                        'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                        'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                        'ins_costo_unitario' => $unitario, /// Costo Unitario
                        'ins_costo_total' => $total, /// Costo Total
                        'ins_unidad_medida' => $unidad, /// Unidad de Medida
                        'ins_gestion' => $this->gestion, /// Insumo gestion
                        'par_id' => $par_id[0]['par_id'], /// Partidas
                        'ins_tipo' => 1, /// Ins Tipo
                        'ins_observacion' => strtoupper($observacion), /// Observacion
                        'fun_id' => $this->fun_id, /// Funcionario
                        'aper_id' => $proyecto[0]['aper_id'], /// aper id
                        'com_id' => $producto[0]['com_id'], /// com id 
                        'prod_cod' => $producto[0]['prod_cod'], /// aper id
                        'num_ip' => $this->input->ip_address(), 
                        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                        );
                        $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                        $ins_id=$this->db->insert_id();

                        /*--------------------------------------------------------*/
                          $data_to_store2 = array( ///// Tabla InsumoProducto
                            'prod_id' => $prod_id, /// prod id
                            'ins_id' => $ins_id, /// ins_id
                          );
                          $this->db->insert('_insumoproducto', $data_to_store2);
                        /*----------------------------------------------------------*/

                        /*------------ PARA LA GESTION 2020 ---------*/
                        for ($p=1; $p <=12 ; $p++) { 
                          if($m[$p]!=0 & is_numeric($unitario)){
                            $data_to_store4 = array(
                              'ins_id' => $ins_id, /// Id Insumo
                              'mes_id' => $p, /// Mes 
                              'ipm_fis' => $m[$p], /// Valor mes
                              'g_id' => $this->gestion, /// Gestion
                            );
                            $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                          }
                        }

                      }
                    }
                  }
                  $i++;
                }

                $this->session->set_flashdata('success','SE REGISTRARON '.$nro.' REQUERIMIENTOS');
                redirect('prog/requerimiento/'.$prod_id);
            /*--------------------------------------------*/
          }
          else {
            $this->session->set_flashdata('danger','ERROR');
            redirect('prog/requerimiento/'.$prod_id);
          } 

          /*----------------------------------------------------------------------*/
      }
      else{
        show_404();
      }
    }




    /*--- VISTA PREVIA DE FORM 4 (ACTIVIDADES - DATOS GENERALES) ---*/
    function vista_prev_actividades($matriz,$nro,$tp){
      $tabla='';
      $styl='';
      if($tp==1){
        $tabla.='
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading">'.$nro.'.- ACTIVIDADES LISTOS A SER MIGRADOS AL SISTEMA </h4>
              <p>La siguiente tabla muestra el listado de las Actividades que son aptos para subir al sistema</p>
              <hr>
            </div>';
            $bgcolor='#c9f5bc';
      }
      else{
        $tabla.='
            <div class="alert alert-warning" role="alert">
              <h4 class="alert-heading">'.$nro.'.- ACTIVIDADES A SER CORREGIDOS</h4>
              <p>La siguiente tabla muestra el listado de las Actividades a ser corregidos para poder subir al sistema</p>
              <hr>
            </div>';
            $bgcolor='#f5eed7';
      }

      $tabla.='
            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>

            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:2%;" style="height:35px;" title="NUMERO CORRELATIVO">#</th>
                      <th style="width:2%;" title="CÓDIGO ACTIVIDAD">C&Oacute;DIGO ACT.</th>
                      <th style="width:2%;" title="ACTIVIDAD">DESCRIPCI&Oacute;N ACTIVIDAD</th>
                      <th style="width:15%;" title="RESULTADO">RESULTADO</th>
                      <th style="width:7%;" title="INDICADOR">INDICADOR</th>
                      <th style="width:4.6%;" title="LINEA BASE">LINEA BASE</th>
                      <th style="width:4.6%;" title="META">META</th>
                      <th style="width:4.6%;" title="UNIDAD RESPONSABLE">UNIDAD RESPONSABLE</th>
                      <th style="width:4.6%;">ENE.</th>
                      <th style="width:4.6%;">FEB.</th>
                      <th style="width:4.6%;">MAR.</th>
                      <th style="width:4.6%;">ABR.</th>
                      <th style="width:4.6%;">MAY.</th>
                      <th style="width:4.6%;">JUN.</th>
                      <th style="width:4.6%;">JUL.</th>
                      <th style="width:4.6%;">AGO.</th>
                      <th style="width:4.6%;">SEPT.</th>
                      <th style="width:4.6%;">OCT.</th>
                      <th style="width:4.6%;">NOV.</th>
                      <th style="width:4.6%;">DIC.</th>
                    </tr>
                  </thead>
                  <tbody>';
                  for ($i=1; $i <=$nro; $i++) {
                    $color='';  
                    $tabla.='
                        <tr bgcolor="'.$bgcolor.'" class="modo1">
                          <td style="height:60px;">'.$i.'</td> 
                          <td align=center><b>'.$matriz[1][$i].'</b></td>
                          <td>'.$matriz[2][$i].'</td>
                          <td>'.$matriz[3][$i].'</td>
                          <td>'.$matriz[4][$i].'</td>
                          <td>'.$matriz[5][$i].'</td>
                          <td>'.$matriz[6][$i].'</td>
                          <td bgcolor='.$color.'>'.$matriz[7][$i].'</td>';
                          $valor=0;
                          for ($j=10; $j <=21 ; $j++) { 
                            $valor++;
                            $tabla.='<td>'.$matriz[$j][$i].'</td>';
                          }
                  $tabla.='
                        </tr>';
                  }

                  $tabla.='  
            </tbody>
          </table>
          </div>
        </fieldset>';

      return $tabla;
    }

    /*--- VISTA PREVIA DE FORM 4 CON ERROR DE DIMENSION (DATOS GENERALES) ---*/
    function vista_prev_actividades_error($matriz,$nro){
      $tabla='';
      $tabla.='
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">'.$nro.'.- CON ERROR DE DIMENSI&Oacute;N </h4>
              <p>La siguiente tabla muestra el listado de las Actividades que tienen errores con respecto a la dimensión </p>
            </div>

            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>
            
            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>      
                    <tr class="modo1">
                      <th style="width:1%;">#</th>
                      <th style="width:5%;">C&Oacute;DIGO</th>
                      <th style="width:7%;">ACTIVIDAD</th>
                      <th style="width:10%;">RESULTADO</th>
                      <th style="width:5%;">TAMAÑO COLUMNAS</th>
                    </tr>
                    </thead>
                    <tbody>';
                    for ($i=1; $i <=$nro ; $i++) { 
                      $tabla.='
                      <tr bgcolor="#ea9797" class="modo1">
                        <td>'.$i.'</td>
                        <td>'.$matriz[1][$i].'</td>
                        <td>'.$matriz[2][$i].'</td>
                        <td>'.$matriz[3][$i].'</td>
                        <td>'.$matriz[4][$i].'</td>
                      </tr>';
                    }
                  $tabla.='  
                  </tbody>
                </table>
              </div>
            </fieldset>';

      return $tabla;
    }

    /*--- VISTA PREVIA DE FORM 5 (DATOS GENERALES) ---*/
    function vista_prev_requerimientos($matriz,$nro,$tp,$monto){
      $tabla='';
      $styl='';
      if($tp==1){
        $tabla.='
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading">'.$nro.'.- REQUERIMIENTOS LISTOS A SER MIGRADOS AL SISTEMA - MONTO TOTAL : '.number_format($monto, 2, ',', '.').'</h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que son aptos para subir al sistema</p>
              <hr>
              
            </div>';
            $bgcolor='#c9f5bc';
      }
      else{
        $tabla.='
            <div class="alert alert-warning" role="alert">
              <h4 class="alert-heading">'.$nro.'.- REQUERIMIENTOS A SER CORREGIDOS - MONTO TOTAL : '.number_format($monto, 2, ',', '.').'</h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que ser corregidos para poder subir al sistema</p>
              <hr>
            
            </div>';
            $bgcolor='#f5eed7';
      }

      $tabla.='
            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>
            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:2%;" style="height:35px;" title="NUMERO CORRELATIVO">#</th>
                      <th style="width:2%;" title="NUMERO CORRELATIVO DEL ARCHIVO EXCEL">NRO.</th>
                      <th style="width:2%;" title="CODIGO DE PARTIDA">COD. PARTIDA</th>
                      <th style="width:15%;" title="DETALLE DEL REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                      <th style="width:7%;" title="UNIDAD DE MEDIDA">UNIDAD</th>
                      <th style="width:4.6%;" title="CANTIDAD">CANTIDAD</th>
                      <th style="width:4.6%;" title="PRECIO UNITARIO">PRECIO UNITARIO</th>
                      <th style="width:4.6%;" title="COSTO TOTAL PRESUPUESTADO">TOTAL PRESUPUESTADO</th>
                      <th style="width:4.6%;" title="SUMA TOTAL DE LA DISTRIBUCION">PROGRAMADO TOTAL</th>
                      <th style="width:4.6%;">ENE.</th>
                      <th style="width:4.6%;">FEB.</th>
                      <th style="width:4.6%;">MAR.</th>
                      <th style="width:4.6%;">ABR.</th>
                      <th style="width:4.6%;">MAY.</th>
                      <th style="width:4.6%;">JUN.</th>
                      <th style="width:4.6%;">JUL.</th>
                      <th style="width:4.6%;">AGO.</th>
                      <th style="width:4.6%;">SEPT.</th>
                      <th style="width:4.6%;">OCT.</th>
                      <th style="width:4.6%;">NOV.</th>
                      <th style="width:4.6%;">DIC.</th>
                    </tr>
                  </thead>
                  <tbody>';
                  for ($i=1; $i <=$nro; $i++) {
                    $color='';  
                    if(round($matriz[7][$i],2)!=round($matriz[8][$i],2)){
                      $color="#f9ab9c";
                    }
                    $tabla.='
                        <tr bgcolor="'.$bgcolor.'" class="modo1">
                          <td style="height:60px;">'.$i.'</td> 
                          <td>'.$matriz[1][$i].'</td>
                          <td>'.$matriz[2][$i].'</td>
                          <td>'.$matriz[3][$i].'</td>
                          <td>'.$matriz[4][$i].'</td>
                          <td>'.$matriz[5][$i].'</td>
                          <td>'.$matriz[6][$i].'</td>
                          <td bgcolor='.$color.'>'.$matriz[7][$i].'</td>
                          <td bgcolor='.$color.'>'.$matriz[8][$i].'</td>';
                          $valor=0;
                          for ($j=9; $j <=20 ; $j++) { 
                            $valor++;
                            $tabla.='<td>'.$matriz[$j][$i].'</td>';
                          }
                  $tabla.='
                        </tr>';
                  }

                  $tabla.='  
                  </tbody>
                </table>
              </div>
            </fieldset>';

      return $tabla;
    }

    /*--- VISTA PREVIA DE FORM 5 CON ERROR DE DIMENSION (DATOS GENERALES) ---*/
    function vista_prev_requerimientos_error($matriz,$nro){
      $tabla='';
      $tabla.='
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">'.$nro.'.- CON ERROR DE DIMENSI&Oacute;N </h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que son aptos para subir al sistema</p>
              <hr>
              <p class="mb-0">Los siguientes requerimientos listados no cumplen con el tamaño de columnas requeridas ..</p>
            </div>

            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>
            
            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>
                    
                    <tr class="modo1">
                      <th style="width:1%;">#</th>
                      <th style="width:5%;">NUMERO</th>
                      <th style="width:7%;">COD. PARTIDA</th>
                      <th style="width:10%;">DETALLE REQUERIMIENTO</th>
                      <th style="width:5%;">TAMAÑO COLUMNAS</th>
                    </tr>
                    </thead>
                    <tbody>';
                    for ($i=1; $i <=$nro ; $i++) { 
                      $tabla.='
                      <tr bgcolor="#ea9797" class="modo1">
                        <td>'.$i.'</td>
                        <td>'.$matriz[1][$i].'</td>
                        <td>'.$matriz[2][$i].'</td>
                        <td>'.$matriz[3][$i].'</td>
                        <td>'.$matriz[4][$i].'</td>
                      </tr>';
                    }

                  $tabla.='  
                  </tbody>
                </table>
              </div>
            </fieldset>';

      return $tabla;
    }


    /*--- VISTA PREVIA DE REQUERIMIENTOS A IMPORTAR (FORMULARIO 5)---*/
    function vista_requerimientos($matriz,$nro,$proy_id,$id,$tp){
      $tabla='';
      $styl='';
      if($tp==1){
        $tabla.='
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading">'.$nro.'.- REQUERIMIENTOS LISTOS A SER MIGRADOS AL SISTEMA </h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que son aptos para subir al sistema</p>
              <hr>
              
            </div>';
            $bgcolor='#c9f5bc';
      }
      else{
        $tabla.='
            <div class="alert alert-warning" role="alert">
              <h4 class="alert-heading">'.$nro.'.- REQUERIMIENTOS A SER CORREGIDOS</h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que ser corregidos para poder subir al sistema</p>
              <hr>
            
            </div>';
            $bgcolor='#f5eed7';
      }
      $tabla.='
            
            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>
            <form action="'.site_url("").'/insumos/cprog_insumo/valida_add_requerimientos'.'" id="form1" name="form1" class="smart-form" method="post">
            <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'">
            <input type="hidden" name="id" id="id" value="'.$id.'">
            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:2%;" style="height:35px;" title="NUMERO CORRELATIVO">#</th>
                      <th style="width:2%;" title="NUMERO CORRELATIVO DEL ARCHIVO EXCEL">NRO.</th>
                      <th style="width:2%;" title="CODIGO DE PARTIDA">COD. PARTIDA</th>
                      <th style="width:15%;" title="DETALLE DEL REQUERIMIENTO">DETALLE REQUERIMIENTO</th>
                      <th style="width:7%;" title="UNIDAD DE MEDIDA">UNIDAD</th>
                      <th style="width:4.6%;" title="CANTIDAD">CANTIDAD</th>
                      <th style="width:4.6%;" title="PRECIO UNITARIO">PRECIO UNITARIO</th>
                      <th style="width:4.6%;" title="COSTO TOTAL PRESUPUESTADO">TOTAL PRESUPUESTADO</th>
                      <th style="width:4.6%;" title="SUMA TOTAL DE LA DISTRIBUCION">PROGRAMADO TOTAL</th>
                      <th style="width:4.6%;">ENE.</th>
                      <th style="width:4.6%;">FEB.</th>
                      <th style="width:4.6%;">MAR.</th>
                      <th style="width:4.6%;">ABR.</th>
                      <th style="width:4.6%;">MAY.</th>
                      <th style="width:4.6%;">JUN.</th>
                      <th style="width:4.6%;">JUL.</th>
                      <th style="width:4.6%;">AGO.</th>
                      <th style="width:4.6%;">SEPT.</th>
                      <th style="width:4.6%;">OCT.</th>
                      <th style="width:4.6%;">NOV.</th>
                      <th style="width:4.6%;">DIC.</th>
                    </tr>
                  </thead>
                  <tbody>';
                  for ($i=1; $i <=$nro; $i++) {
                    $color='';  
                    if($matriz[7][$i]!=$matriz[8][$i]){
                      $color="#f9ab9c";
                    }
                    $tabla.='
                      <tr bgcolor="'.$bgcolor.'" class="modo1">
                        <td style="height:60px;"><input type="hidden" name="nro[]" id="nro[]" value='.$i.'>'.$i.'</td> 
                        <td>'.$matriz[1][$i].'</td>
                        <td><input type="hidden" name="par[]" id="par[]" value='.$matriz[2][$i].'>'.$matriz[2][$i].'</td>
                        <td>'.$matriz[3][$i].'</td>
                        <td><input type="hidden" class="form-control" name="unidad[]" id="unidad[]" title="MODIFICAR UNIDAD DE MEDIDA" value='.$matriz[4][$i].'>'.$matriz[4][$i].'</td>
                        <td><input type="hidden" name="cant[]" id="cant[]" value='.$matriz[5][$i].'>'.$matriz[5][$i].'</td>
                        <td><input type="hidden" name="punitario[]" id="punitario[]" value='.$matriz[6][$i].'>'.$matriz[6][$i].'</td>
                        <td bgcolor='.$color.'><input type="hidden" name="ptotal[]" id="ptotal[]" value='.$matriz[7][$i].'>'.$matriz[7][$i].'</td>
                        <td bgcolor='.$color.'>'.$matriz[8][$i].'</td>';
                        $valor=0;
                        for ($j=9; $j <=20 ; $j++) { 
                          $valor++;
                          $tabla.='<td><input type="hidden" name="mes'.$valor.'[]" id="mes'.$valor.'[]" value='.$matriz[$j][$i].'>'.$matriz[$j][$i].'</td>';
                        }

                  $tabla.='
                      </tr>';
                  }

                  $tabla.='  
                  </tbody>
                </table>
              </div>
            </fieldset>
          </form>';

      return $tabla;
    }

    /*--- VISTA PREVIA DE REQUERIMIENTOS CON ERROR DE CODIGO ---*/
    function vista_requerimientos_error($matriz,$nro,$proy_id,$id){
      $tabla='';

      $tabla.='
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">'.$nro.'.- CON ERROR DE DIMENSI&Oacute;N </h4>
              <p>La siguiente tabla muestra el listado de los requerimientos que son aptos para subir al sistema</p>
              <hr>
              <p class="mb-0">puede realizar algunas correcciones en los siguientes campos : Detalle Requerimientos, Unidad de Medida</p>
            </div>

            <table border=0 style="width:50%;">
              <tr>
                <td style="width:20%;"><b>BUSCAR CONTENIDO DE LA TABLA </b></td>
                <td style="width:80%;"><input class="form-control" id="searchTerm" type="text" onkeyup="doSearch()" /></td>
              </tr>
            </table><br>
            
            <fieldset>          
              <div class="row">
                <table id="datos" class="table table-bordered">
                  <thead>
                    
                    <tr class="modo1">
                      <th style="width:1%;">#</th>
                      <th style="width:5%;">NUMERO</th>
                      <th style="width:7%;">COD. PARTIDA</th>
                      <th style="width:10%;">DETALLE REQUERIMIENTO</th>
                      <th style="width:5%;">TAMAÑO COLUMNAS</th>
                    </tr>
                    </thead>
                    <tbody>';
                    for ($i=1; $i <=$nro ; $i++) { 
                      $tabla.='
                        <tr bgcolor="#ea9797" class="modo1">
                          <td>'.$i.'</td>
                          <td>'.$matriz[1][$i].'</td>
                          <td>'.$matriz[2][$i].'</td>
                          <td>'.$matriz[3][$i].'</td>
                          <td>'.$matriz[4][$i].'</td>
                        </tr>';
                    }

                  $tabla.='  
                  </tbody>
                </table>
                </fieldset>';

      return $tabla;
    }

    

    /*----- SUMA TOTAL MONTO REQUERIMIENTOS A IMPORTAR -------*/
/*    function suma_monto_total($requerimientos){
      $i=0; $suma=0;
      foreach ($requerimientos as $linea_num => $linea){ 
        if($i != 0){
            $datos = explode(";",$linea);
            
            if(count($datos)==21){
                $suma=$suma+(float)$datos[7];
            }
          }

          $i++;
      }

      return $suma;
    }      
*/

  

    


    /*----------- REPORTE REQUERIMIENTOS - OPERACION (html2) (PRODUCTO)--------------*/
/*    public function reporte_requerimientos_operacion($prod_id){
      $data['prod_id']=$prod_id;
      $data['producto'] = $this->model_producto->get_producto_id($prod_id); ///// DATOS DEL PRODUCTO
      $data['componente'] = $this->model_componente->get_componente($data['producto'][0]['com_id']); /// COMPONENTE 
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); /// PROYECTO

      $data['mes'] = $this->mes_nombre();
      $data['lista_insumos']= $this->minsumos->lista_insumos_prod($prod_id);
      $this->load->view('admin/programacion/insumos/insumo_productos/requerimientos_operacion', $data);
    }*/


    /*--------------- EXPORTAR REQUERIMIENTOS DE OPERACIONES --------------*/
    public function xcel_reporte_partida($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id); ///// DATOS DEL PRODUCTO
      $componente = $this->model_componente->get_componente($producto[0]['com_id'],$this->gestion); /// COMPONENTE 
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); /// PROYECTO
      $req=$this->consolidado_partidas_operacion($prod_id,2);
      date_default_timezone_set('America/Lima');
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");

      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel;charset=UTF-8');
      header("Content-Disposition: attachment; filename=Reporte_Operación_".$componente[0]['com_componente']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");

      echo '
      <table border="0" cellpadding="0" cellspacing="0" class="tabla">
        <tr class="modo1">
          <td colspan="4"></td>
        </tr>
        <tr class="modo1">
          <td colspan="4">
            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                            <b>REPORTE : </b> CONSOLIDADO PARTIDAS DE LA OPERACI&Oacute;N<br>
                            <b>ACTIVIDAD : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(''.$proyecto[0]['proy_nombre'], 'cp1252', 'UTF-8').'<br>
                            <b>SUB ACTIVIDAD : </b>'.$componente[0]['serv_cod'].'-'.mb_convert_encoding(''.$componente[0]['com_componente'], 'cp1252', 'UTF-8').'<br>
                            <b>OPERACI&Oacute;N : </b>'.mb_convert_encoding(''.$producto[0]['prod_producto'], 'cp1252', 'UTF-8').'
                            </font>
          </td>
        </tr>
      </table><br>';
      echo "".$req."";
    }
    /*=========================================================================================================================*/
    public function get_mes($mes_id){
      $mes[1]='Enero';
      $mes[2]='Febrero';
      $mes[3]='Marzo';
      $mes[4]='Abril';
      $mes[5]='Mayo';
      $mes[6]='Junio';
      $mes[7]='Julio';
      $mes[8]='Agosto';
      $mes[9]='Septiembre';
      $mes[10]='Octubre';
      $mes[11]='Noviembre';
      $mes[12]='Diciembre';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }

    /*---------- MENU -----------*/
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

    /*------------  Funcion para verificar fechas ---------------------     */
    public function verif_fecha($fecha_act){
        $fecha = $fecha_act;
        $valores = explode('/', $fecha);

        if(count($valores)==3){
            if(checkdate($valores[1],$valores[0],$valores[2])){
               return 'true';
            }
            else{
                return 'false';
            }
        }
        else{
            return 'false';
        }
              //  echo count($valores);
    }

    /*--------------- GENERA MENU -------------*/
    public function genera_menu($proy_id){
      $id_f = $this->model_faseetapa->get_id_fase($proy_id);
      $enlaces=$this->menu_modelo->get_Modulos_programacion(2);
      $tabla='';
      $tabla.='<nav>
              <ul>
                  <li>
                      <a href='.site_url("admin").'/dashboard'.' title="MENU PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                  </li>
                  <li class="text-center">
                      <a href='.base_url().'index.php/admin/proy/mis_proyectos/1'.' title="PROGRAMACI&Oacute;N POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N POA</span></a>
                  </li>';
                  if(count($id_f)!=0){
                      for($i=0;$i<count($enlaces);$i++){ 
                          $tabla.='
                          <li>
                              <a href="#" >
                                  <i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>
                              <ul >';
                              $submenu= $this->menu_modelo->get_Modulos_sub($enlaces[$i]['o_child']);
                              foreach($submenu as $row) {
                                 $tabla.='<li><a href='.base_url($row['o_url'])."/".$id_f[0]['proy_id'].'>'.$row['o_titulo'].'</a></li>';
                              }
                          $tabla.='</ul>
                          </li>';
                      }
                  }
              $tabla.='
              </ul>
          </nav>';

      return $tabla;
    }

}