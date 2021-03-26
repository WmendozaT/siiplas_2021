<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cprog_insumos extends CI_Controller{
    var $gestion;
    var $rol;
    var $fun_id;

    function __construct(){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('programacion/insumos/minsumos_delegado');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        }else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }

    function insumos($proy_id, $tipo, $valor){
        if($valor==1) ////// Programacion Normal de Insumos (DIRECTO)
        {
            if ($tipo == 1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $this->lista_productos($proy_id);
            } else {
                //PROGRAMACION DELEGADA, DELEGADA = 2
                $this->delegado($proy_id);
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $this->lista_procesos($proy_id);
        }
        
    }

    /*----- PROGRAMACION DE INSUMOS DELEGADO ------*/
    function delegado($proy_id){
        $data['menu']=$this->menu(2);
        $data['proy_id'] = $proy_id;
        $data['dato_proy'] = $this->minsumos->dato_proyecto($proy_id);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
        if(count($data['proyecto'])!=0){
            $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
            $componentes=$this->model_componente->componentes_id($data['fase'][0]['id'],$data['proyecto'][0]['tp_id']);  //// COMPONENTES DE LA FASE ACTIVA

            $data['titulo_proy'] = strtoupper($data['proyecto'][0]['tipo']);
            $data['tabla_fuentes'] = $this->fuentes_financiamientos($proy_id, $this->gestion,$data['fase'][0]['pfec_ejecucion'],$data['proyecto'][0]['proy_act']); //// Tabla lista de Activos del Componente
            /*---------------------------------------------------------------------------------------------------------*/
            $tabla = '';
            $cont = 1;
            foreach ($componentes AS $row){
                $tabla .= '<tr>';
                    $tabla .= '<td>' . $cont . '</td>';
                    $tabla .= '<td>
                                <a href="' . site_url("") . '/prog/ins_com/'.$row['com_id'].'">
                                    <center>
                                        <img src="'.base_url().'assets/ifinal/insumo.png" width="30" height="30" class="img-responsive "title="ASIGNAR INSUMOS">
                                    </center>
                                </a>
                            </td>';
                    $tabla .= '<td>'.$row['com_componente'].'</td>';
                    $tabla .= '<td>'.$row['com_ponderacion'].'</td>';
                $tabla .= '</tr>';
                $cont++;
            }
            /*---------------------------------------------------------------------------------------------------------*/
            $data['tabla']=$tabla;
            $this->load->view('admin/programacion/insumos/insumo_componente/list_componentes', $data);
        }
        else{
            redirect('admin/dashboard');
        }
    }

    /*----- PROGRAMACION DE INSUMOS DIRECTO - PRODUCTOS:ACTIVIDADES------*/
    public function lista_productos($proy_id){
        $data['menu']=$this->menu(2);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
        if(count($data['proyecto'])!=0){
            $data['titulo_proy'] = strtoupper($data['proyecto'][0]['tipo']);
            $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
            //$data['fuente']=$this->model_faseetapa->presupuesto_asignados($data['proyecto'][0]['proy_id'],$this->gestion);
            $data['monto_asig']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],1);
            $data['monto_prog']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],2);
            $monto_a=0;$monto_p=0;$monto_saldo=0;
            if(count($data['monto_asig'])!=0){
                $monto_a=$data['monto_asig'][0]['monto'];
            }
            if(count($data['monto_prog'])){
                $monto_p=$data['monto_prog'][0]['monto'];
            }

            $data['monto_a']=$monto_a;
            $data['monto_p']=$monto_p;
            $data['monto_saldo']=round(($monto_a-$monto_p),2);
            $data['lista_productos'] = $this->genera_tabla_prod_act($proy_id); /// Lista de Productos

            $this->load->view('admin/programacion/insumos/insumo_actividades/list_productos', $data);
        }
        else{
            redirect('admin/dashboard');
        }
    }

    //----------------PROGRAMACION DIRECTA PROCESOS:PRODUCTOS
    function lista_procesos($proy_id){
        $data['menu']=$this->menu(2);

        $data['proy_id'] = $proy_id;
        $data['dato_proy'] = $this->minsumos->dato_proyecto($proy_id);
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
        if(count($data['proyecto'])!=0){
            $data['titulo_proy'] = strtoupper($data['proyecto'][0]['tipo']);
            $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA
           
            $data['fuente']=$this->model_faseetapa->presupuesto_asignados($data['proyecto'][0]['proy_id'],$this->gestion);
            $data['monto_asig']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],1);
            $data['monto_prog']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],2);
            $monto_a=0;$monto_p=0;$monto_saldo=0;
            if(count($data['monto_asig'])!=0){
                $monto_a=$data['monto_asig'][0]['monto'];
            }
            if(count($data['monto_prog'])){
                $monto_p=$data['monto_prog'][0]['monto'];
            }

            $data['monto_a']=$monto_a;
            $data['monto_p']=$monto_p;
            $data['monto_saldo']=round(($monto_a-$monto_p),2);
            $data['lista_procesos'] = $this->genera_tabla_comp_prod($proy_id); /// Lista de Procesos
            
            $this->load->view('admin/programacion/insumos/insumo_productos/list_procesos', $data);
        }
        else{
            redirect('admin/dashboard');
        }
    }

    /*----------------- TABLA - FUENTES (2019) --------------*/
    public function asignacion_fuentes($proyecto,$tp){
      $fuente = $this->model_faseetapa->presupuesto_asignados($proyecto[0]['proy_id'],$this->gestion);
      $monto_prog=$this->minsumos->monto_total_programado($proyecto[0]['aper_id'],$this->gestion);
      $color='';
      if($tp==1){
        $tab='class="table table-bordered table-sm"'; 
      }
      elseif($tp==2){
        $tab='border="0" cellpadding="0" cellspacing="0" class="tabla" align="center"';
      }

      $monto=0;
      if(count($monto_prog)!=0){
        $monto=$monto_prog[0]['monto'];
      }
      if(($fuente[0]['presupuesto_asignado']+0.5)<$monto){
        $color='red';
      }
      
      $tabla='';
      $tabla.='<table '.$tab.'>
                  <thead>
                    <tr class="modo1" title="'.$proyecto[0]['aper_id'].'">
                      <th>FUENTE DE FINANCIAMIENTO</th>
                      <th>ORGANISMO FINANCIADOR</th>
                      <th>PRESUPUESTO ASIGNADO</th>
                      <th>PRESUPUESTO PROGRAMADO</th>
                      <th>SALDO POR PROGRAMAR</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="modo1">
                      <td>'.$fuente[0]['ff_codigo'].' - '.$fuente[0]['ff_descripcion'].'</td>
                      <td>'.$fuente[0]['of_codigo'].' - '.$fuente[0]['of_descripcion'].'</td>
                      <td>'.number_format($fuente[0]['presupuesto_asignado'], 2, ',', '.').'</td>
                      <td>'.number_format($monto, 2, ',', '.').'</td>
                      <td><font color='.$color.' size="1"><b>'.number_format(($fuente[0]['presupuesto_asignado']-$monto), 2, ',', '.').'</b></font></td>
                    </tr>
                  </tbody>
                </table>';
      return $tabla;
    }

     /// ELIMINAR TODOS LOS INSUMOS 
    function delete_insumo_total($proy_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $titulo_proy=strtoupper($proyecto[0]['tipo']);
        $data['titulo_proy'] = $titulo_proy;
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

        if($proyecto[0]['proy_act']==1) ////// Programacion Normal de Insumos (HASTA ACTIVIDADES 1)
        {
            if ($fase[0]['pfec_ejecucion']==1) {
            //PROGRAMACION DIRECTA, DIRECTO = 1
                $insumos=$this->minsumos->insumo_actividad($proy_id);
            } 
            else {
            //PROGRAMACION DELEGADA, DELEGADA = 2
                $insumos=$this->minsumos_delegado->insumo_componente($proy_id);
            }
        }
        else ///// Programacion Solo hasta productos (PROGRAMACION HASTA PRODUCTOS 0 )
        {
            $insumos=$this->minsumos->insumo_producto($proy_id);
        }

        foreach ($insumos as $rowi) {
            $ins_gestion = $this->minsumos->list_insumos_gestion($rowi['ins_id']);
            foreach ($ins_gestion as $row)
            {
               // echo $row['insg_id'].'--'.$row['ins_id'].'-'.$row['g_id'].'--'.$row['insg_monto_prog']."<br>";
                $ins_fin = $this->minsumos->list_insumo_financiamiento($row['insg_id']);
                if(count($ins_fin)!=0)
                {
                    /*----------------- ELIMINA IFIN PROG MES---------------*/
                    $this->db->where('ifin_id', $ins_fin[0]['ifin_id']);
                    $this->db->delete('ifin_prog_mes');
                    /*------------------------------------------------------*/

                    /*----------------- ELIMINA IFIN EJEC MES---------------*/
                    $this->db->where('ifin_id', $ins_fin[0]['ifin_id']);
                    $this->db->delete('ifin_ejec_mes');
                    /*------------------------------------------------------*/

                    /*----------------- ELIMINA IFIN PROG MES---------------*/
                    $this->db->where('ifin_id', $ins_fin[0]['ifin_id']);
                    $this->db->delete('insumo_financiamiento');
                    /*------------------------------------------------------*/

                  //  echo "-----insumo fin : ".$ins_fin[0]['ifin_id'].'-'.$ins_fin[0]['insg_id'].'-'.$ins_fin[0]['ifin_monto'];
                }
                /*----------------- ELIMINA INS GESTION---------------*/
                    $this->db->where('insg_id', $row['insg_id']);
                    $this->db->delete('insumo_gestion');
                /*------------------------------------------------------*/
               // 
            }
                
            if($proyecto[0]['proy_act']==1) ////// Programacion Normal de Insumos (HASTA ACTIVIDADES 1)
            {
                if($fase[0]['pfec_ejecucion']==1) //// directo
                {
                    /*----------------- ELIMINA INSUMO ACTIVIDAD ---------------*/
                    $this->db->where('ins_id', $rowi['ins_id']);
                    $this->db->delete('_insumoactividad');
                    /*----------------------------------------------------------*/
                }
                elseif ($fase[0]['pfec_ejecucion']==2) /// Delegado
                {
                   /*----------------- ELIMINA INSUMO COMPONENTE ---------------*/
                    $this->db->where('ins_id', $rowi['ins_id']);
                    $this->db->delete('insumocomponente');
                    /*-----------------------------------------------------------*/
                }
            }
            else{
                /*----------------- ELIMINA INSUMO PRODUCTO ---------------*/
                    $this->db->where('ins_id', $rowi['ins_id']);
                    $this->db->delete('_insumoproducto');
                /*----------------------------------------------------------*/
            }
                

                /*----------------- ELIMINA INS GESTION---------------*/
                    $this->db->where('ins_id', $rowi['ins_id']);
                    $this->db->delete('insumos');
                /*------------------------------------------------------*/
        }


        $this->session->set_flashdata('success','LOS INSUMOS SE ELIMINARON CORRECTAMENTE');
        redirect('prog/ins/'.$proy_id.'/'.$fase[0]['pfec_ejecucion'].'/'.$proyecto[0]['proy_act'].'/true');
    }

    ///// tabla de productos con sus respectivas actividades
    function genera_tabla_prod_act($proy_id){
        $lista_productos = $this->minsumos->lista_productos($proy_id, $this->gestion);
        $tabla = '';
        $cont_acordion = 0;
        foreach ($lista_productos as $row) {
            $cont_acordion++;
            $tabla .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $cont_acordion . '">
                                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i>' .
                $cont_acordion . ' - ' . $row['prod_producto'] . '
                                    </a>
                                </h4>
                            </div>';
            $tabla .= '<div id="collapse' . $cont_acordion . '" class="panel-collapse collapse">
                            <div class="panel-body no-padding table-responsive">
                                <table class="table table-bordered table-condensed">';
            $tabla .= '            <tbody>
                                      <tr>
                                          <td><b>NRO.</b></td>
                                          <td><b>ASIGNAR REQUERIMIENTOS</b></td>
                                          <td><b>DETALLE ACTIVIDAD</b></td>
                                          <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                          <td><b>PRESUPUESTO</b></td>
                                      </tr>';
            $lista_actividad = $this->minsumos->lista_actividades($row['prod_id'], $this->gestion);
            $cont = 1;
            foreach ($lista_actividad as $row_a) {
                $monto=$this->model_actividad->monto_insumoactividad($row_a['act_id']);
                $tabla .= '<tr>';
                $tabla .= '<td>'.$cont_acordion.'-'.$cont.'</td>';
                $tabla .= '<td>
                               <a href="'.site_url("").'/prog/ins_act/'.$row_a['act_id'].'" target="_blank">
                                    <center>
                                        <img src="' . base_url() . 'assets/ifinal/money.png" width="30" height="30"
                                        class="img-responsive "title="ASIGNAR INSUMOS">
                                    </center>
                               </a>
                          </td>';
                $tabla .= '<td>'.$row_a['act_actividad'].'</td>';
                $tabla .= '<td>'.$row_a['act_fuente_verificacion'].'</td>';
                $tabla .= '<td>';
                            if(count($monto)!=0){
                                $tabla.=''.number_format($monto[0]['total'], 2, ',', '.').' Bs.';
                            }
                            else{
                                $tabla.='0.00 Bs.';
                            }
                $tabla .= '</td>';
                $tabla .= '</tr>';
                $cont++;
            }
            $tabla .= '             </tbody>
                                </table>
                           </div>
                      </div>
                 </div>';
        }
        return $tabla;
    }

    /*------------- LISTA DE SUB ACTIVIDADES (2019) -----------------*/
    function genera_tabla_comp_prod($proy_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
        $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
        $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);

        $tabla = '';
        $cont_acordion = 0;
        foreach ($componentes as $row) {
            $requerimientos = $this->minsumos->list_requerimientos_operacion_procesos($row['com_id']);
            $cont_acordion++;
            $tabla .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $cont_acordion . '">
                                        <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i>';
                                            if($proyecto[0]['tp_id']==1){
                                                $tabla.=''.$row['com_componente'].'';
                                            }
                                            else{
                                                $tabla.=''.$row['serv_cod'].' - ' . $row['com_componente'] .'';
                                            }
                                        $tabla.='
                                    </a>
                                </h4>
                            </div>';
            $tabla .= '<div id="collapse' . $cont_acordion . '" class="panel-collapse collapse">
                            <div class="panel-body no-padding table-responsive">
                                <table class="table table-bordered table-condensed">';
            $tabla .= '            <tbody>
                                          <tr>
                                              <td></td>
                                              <td><b>ASIGNAR</b></td>
                                              <td><b>OPERACI&Oacute;N</b></td>
                                              <td><b>RESULTADO</b></td>
                                              <td><b>MONTO PROGRAMADO</b></td>
                                          </tr>';
            $lista_productos = $this->model_producto->list_prod($row['com_id']);
            $cont = 1;
            foreach ($lista_productos as $row_p) {
                $monto=$this->model_producto->monto_insumoproducto($row_p['prod_id']);
                $tabla .= '<tr>';
                $tabla .= '<td>'.$row_p['prod_cod'].'</td>';
                $tabla .= '<td>';
                    $tabla.='   <a href="' . site_url("") . '/prog/ins_prod/'.$row_p['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA OPERACI&Oacute;N" >
                                    <center>
                                        <img src="' . base_url() . 'assets/ifinal/money.png" width="30" height="30"
                                        class="img-responsive "title="ASIGNAR REQUERIMIENTOS A LA OPERACI&Oacute;N">
                                    </center>
                                </a>';
                $tabla.=' </td>';
                $tabla .= '<td>'.$row_p['prod_producto'].'</td>';
                $tabla .= '<td>'.$row_p['prod_resultado'].'</td>';
                $tabla .= '<td>';
                            if(count($monto)!=0){
                            $tabla.=''.number_format($monto[0]['total'], 2, ',', '.').' Bs.';
                            }
                            else{
                                $tabla.='0.00 Bs.';
                            }
                $tabla .= '</td>';
                $tabla .= '</tr>';
                $cont++;
            }
            $tabla .= '             </tbody>
                                </table>
                           </div>
                      </div>
                 </div>';
        }
        return $tabla;
    }

    /*----------- LISTA DE REUQUERIMIENTOS POR SUB ACTIVIDAD (2019)-------------*/
    public function operacion_requerimiento($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS FASE ACTIVA
      $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']); //// COMPONENTES
      $partidas=$this->minsumos->list_partidas(); //// PARTIDAS

        $tabla ='';
    //    $tabla.='ACTIVIDAD : '.$proyecto[0]['proy_act'].' - EJECUCI&Oacute;N : '.$fase[0]['pfec_ejecucion'].'<br>';
            foreach ($componentes as $rowc) {
                if($proyecto[0]['proy_act']==0){
                    $requerimientos=$this->minsumos->list_requerimientos_operacion_procesos($rowc['com_id']);
                }
                else{
                    if($fase[0]['pfec_ejecucion']==1){
                        $requerimientos=$this->minsumos->list_requerimientos_actividades_procesos($rowc['com_id']);
                    }
                    else{
                        $requerimientos=$this->minsumos->list_requerimientos_delegado($rowc['com_id']);
                    }
                } 
                
                if(count($requerimientos)!=0){
                    if($proyecto[0]['tp_id']==1){
                        $tabla.='<FONT FACE="courier new" size="1"><b>COMPONENTE : '.$rowc['com_componente'].'</b></FONT>';
                    }
                    else{
                       $tabla.='<FONT FACE="courier new" size="1"><b>SUB ACTIVIDAD : '.$rowc['serv_cod'].'.- '.$rowc['com_componente'].'</b></FONT>'; 
                    }
                    $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
                    $tabla.='<thead>';
                    $tabla.='
                        <tr class="modo1">
                            <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;"></th>
                            <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                            <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNIDAD</th>
                            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>
                            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNITARIO</th>
                            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">TOTAL</th>
                            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">PROG. TOTAL</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">ENE.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">FEB.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">MAR.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">ABR.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">MAY.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">JUN.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">JUL.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">AGO.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">SEPT.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">OCT.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">NOV.</th>
                            <th style="width:4%;" style="background-color: #1c7368;color: #FFFFFF">DIC.</th>
                            <th style="width:8%;" style="background-color: #1c7368;color: #FFFFFF">OBSERVACIONES</th>
                        </tr>';
                    $tabla.='</thead>';
                    $tabla.='<tbody>';
                    $cont=0;
                    foreach ($requerimientos as $row){
                        $prog = $this->minsumos->get_list_insumo_financiamiento($row['insg_id']);
                        $color='';
                          if(count($prog)!=0){
                            if($row['ins_costo_total']!=$prog[0]['programado_total']) {
                                $color='#f5bfb6';
                            }
                          }
                          else{
                            $color='#f5bfb6';
                          }
                        $cont++;
                        $tabla .= '<tr class="modo1" bgcolor="'.$color.'">';
                            $tabla .= '<td style="width: 1%; text-align: center;" style="height:11px;">'.$cont.'</td>';
                            $tabla .= '<td style="width: 3%; text-align: center;">'.$row['par_codigo'].'</td>'; /// partida
                            $tabla .= '<td style="width: 15%; text-align: left;">'.$row['ins_detalle'] .'</td>'; /// detalle requerimiento
                            $tabla .= '<td style="width: 5%; text-align: left;">'.$row['ins_unidad_medida'] .'</td>'; /// Unidad
                            $tabla .= '<td style="width: 5%; text-align: right;">'.$row['ins_cant_requerida'] .'</td>'; /// cantidad
                            $tabla .= '<td style="width: 5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.') .'</td>';
                            $tabla .= '<td style="width: 5%; text-align: right;">'.number_format($row['ins_costo_total'], 2, ',', '.') .'</td>';
                            if(count($prog)!=0){
                              $tabla.='
                              <td style="width: 5%; text-align: right;">'.number_format($prog[0]['programado_total'], 2, ',', '.').'</td> 
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                              <td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                            }
                            else{
                              $tabla.='
                                <td style="width: 5%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>
                                <td style="width: 4%; text-align: right; color: red">0.00</td>';
                            }
                            $tabla .= '<td style="width: 8%; text-align: left;">'.$row['ins_observacion'].'</td>';
                        $tabla.='</tr>';
                    }
                    $tabla.='</tbody>
                        </table><br>';
                }
                
            }   
      return $tabla;
    }

    /*------- REPORTE CONSOLIDADO REQUERIMIENTOS TOTAL DE LA ACTIVIDAD POR SUB ACTIVIDAD (2019)------*/
    public function reporte_proyecto_insumo($proy_id){
        $data['proyecto']=$this->model_proyecto->get_id_proyecto($proy_id);
        if(count($data['proyecto'])!=0){
            $data['mes'] = $this->mes_nombre();
            $data['componente_requerimientos']=$this->operacion_requerimiento($proy_id);
            $this->load->view('admin/programacion/insumos/reporte_requerimientos_componentes', $data);
        }
        else{
            echo "<center><b>ERROR!!!! AL GENERAR REPORTE</b></center>";
        }
    }

    /*----------- REPORTE REQUERIMIENTOS POR SUB ACTIVIDADES (2019)----------*/
    public function reporte_proyecto_insumo_proceso($proy_id,$com_id){
        $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
        if($this->gestion==2018){
            $html = $this->get_reporte_partidas_procesos($proy_id,$com_id);//Reporte Partida por procesos
            $dompdf = new DOMPDF();
            $dompdf->load_html($html);
            $dompdf->set_paper('letter', 'landscape');
            ini_set('memory_limit','700M');
            ini_set('max_execution_time', 9000000);
            $dompdf->render();
            $dompdf->stream("REQUERIMIENTOS : ".$componente[0]['com_componente'].".pdf", array("Attachment" => false));
        }
        else{
            $data['mes'] = $this->mes_nombre();
            $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
            $data['componente'] = $this->model_componente->get_componente_pi($com_id); /// COMPONENTE
            if($data['proyecto'][0]['tp_id']==1){
                $data['lista_insumos'] = $this->minsumos->list_requerimientos_actividades_procesos($com_id); /// Lista requerimientos
                $data['partidas'] = $this->minsumos->list_consolidado_partidas_act_componentes($com_id); /// List consolidado partidas
            }
            else{
                $data['componente'] = $this->model_componente->get_componente($com_id); /// COMPONENTE
                $data['lista_insumos'] = $this->minsumos->list_requerimientos_operacion_procesos($com_id); /// Lista requerimientos
                $data['partidas'] = $this->minsumos->list_consolidado_partidas_componentes($com_id); /// List consolidado partidas
            }
            
            $this->load->view('admin/programacion/insumos/reporte_requerimientos', $data);
        }
    }

    /*------ REPORTE CONSOLIDADO DE PARTIDAS POR PROYECTO (PROYECTO 2019) -----*/
    public function reporte_proyecto_total($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
        $data['partidas']=$this->model_ptto_sigep->partidas_accion($data['proyecto'][0]['aper_id'],2); /// lista de partidas programas
        $this->load->view('admin/programacion/insumos/reporte_consolidado', $data);
    }

    /*---------------- CABECERA REPORTE REQUERIMIENTOS POR SERVICIO (2018)------------------*/
    function get_reporte_partidas_procesos($proy_id,$com_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// Datos del Proyecto
        $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']); /// Datos de la fase
        $componente = $this->model_componente->get_componente($com_id); //// Datos Componentes

        if($proyecto[0]['proy_act']==0){
         $req=$this->operacion_requerimiento_procesos($proy_id,$com_id);
         $part=$this->consolidado_partidas_componente($com_id);
        }
        else{
            if($fase[0]['pfec_ejecucion']==1){
                $req=$this->actividad_requerimiento_procesos($proy_id,$com_id); 
                $part=$this->consolidado_partidas_act_componente($com_id);
            }
            else{
                $req=$this->delegado_requerimiento_procesos($proy_id,$com_id);
                $part=$this->consolidado_partidas_delegado($com_id);
            }
        }

        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -125px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, numeric); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"><</center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1"><b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                            <b>REPORTE : </b> REQUERIMIENTOS POR SUB ACTIVIDAD<br>
                            <b>ACTIVIDAD : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                            <b>SUB ACTIVIDAD : </b>'.$componente[0]['serv_cod'].' - '.$componente[0]['com_componente'].'<font><br>
                        </td>
                        <td width=20%; text-align:center;"">
                            FECH. IMP. : '.date('d/m/Y').'<br>
                            RESP. : '.$this->session->userdata('funcionario').'
                        </td>
                    </tr>
                </table><hr>
           </div>
           <div id="footer">
             <hr>
             <table>
                <tr>
                    <td width=33%;>
                      <table border=1>
                        <tr>
                          <td><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>JEFATURAS DE DEPARTAMENTOS / SERV. GENERALES REGIONAL </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>GERENCIA GENERAL / GERENCIA DE AREA </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                  <td><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td></td>
                  <td align=right><p class="page">Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p>
             <div style="page-break-after;">
              <br>'.$req.'<br>
              '.$part.'
             </div>
             </p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*---------------- CABECERA REPORTE REQUERIMIENTOS POR SERVICIO (2019)------------------*/
    function get_reporte_partidas_procesos_nuevo($proy_id,$com_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// Datos del Proyecto
        $fase = $this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']); /// Datos de la fase
        $componente = $this->model_componente->get_componente_pi($com_id); //// Datos Componentes
        $mes = $this->mes_nombre();

        if($proyecto[0]['tp_id']==1){
          $tit='
                <b>PROYECTO DE INVERSI&Oacute;N : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                <b>COMPONENTE : </b>'.$componente[0]['com_nro'].'-'.$componente[0]['com_componente'].'';
        }
        else{
            $componente = $this->model_componente->get_componente($com_id); //// Datos Componentes
            $tit='
                <b>ACTIVIDAD : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'].'<br>
                <b>SUB ACTIVIDAD : </b>'.$componente[0]['serv_cod'].'-'.$componente[0]['serv_descripcion'].'';
        }

        if($proyecto[0]['proy_act']==0){
         $req=$this->operacion_requerimiento_procesos($proy_id,$com_id);
        // $part=$this->consolidado_partidas_componente($com_id);
        }
        elseif($proyecto[0]['proy_act']==1){
          $req=$this->actividad_requerimiento_procesos($proy_id,$com_id); 
          $part=$this->consolidado_partidas_act_componente($com_id);
        }
        else{
            $req='..';
            $part='..';
        }

        $html = '
        <html>
          <head>' . $this->estilo_vertical() . '
           <style>
             @page { margin: 130px 20px; }
             #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
             #footer { position: fixed; left: 0px; bottom: -125px; right: 0px; height: 110px;}
             #footer .page:after { content: counter(page, numeric); }
           </style>
          <body>
           <div id="header">
                <div class="verde"></div>
                <div class="blanco"></div>
                <table width="100%">
                    <tr>
                        <td width=20%; text-align:center;>
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="47px"></center>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>'.$this->session->userdata('entidad').'</b><br>
                            <b>DIR. ADM. : </b> '.strtoupper($proyecto[0]['dep_departamento']).'<br>
                            '.$tit.'
                            </font>
                        </td>
                        <td width=20%; text-align:center;>
                        </td>
                    </tr>
                </table><hr><FONT FACE="courier new" size="2"><b>PLAN OPERATIVO ANUAL '.$this->gestion.' - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO </b></font>
           </div>
           <div id="footer">
             <hr>
             <table>
                <tr>
                    <td width=33%;>
                      <table border=1>
                        <tr>
                          <td><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>JEFATURAS DE DEPARTAMENTOS / SERV. GENERALES REGIONAL </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                    <td width=33%;>
                    <table border=1>
                        <tr>
                          <td><b>GERENCIA GENERAL / GERENCIA DE AREA </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><b>FIRMA</b></td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                  <td><p class="izq">POA - '.$this->gestion.', Aprobado mediante RD. Nro 116/18 de 05.09.2018</p></td>
                  <td></td>
                  <td align=right><p class="page">' .$mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario').' - Pagina </p></td>
                </tr>
            </table>
           </div>
           <div id="content">
             <p>
             <div style="page-break-after:always;">
              <br>'.$req.'<br>
              
             </div>
             </p>
           </div>
         </body>
         </html>';
        return $html;
    }

    /*----------- REQUERIMIENTOS POR SUB ACTIVIDADES A NIVEL DE OPERACIONES (2019)------------*/
    public function operacion_requerimiento_procesos($proy_id,$com_id){
      $componente = $this->model_componente->get_componente($com_id); //// DATOS COMPONENTE
        $tabla ='';
            $requerimientos = $this->minsumos->list_requerimientos_operacion_procesos($com_id);
            if(count($requerimientos)!=0){
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF">Nro.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>';
                  $tabla.='<th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>';
                  $tabla.='<th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNIDAD</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>';
                  $tabla.='<th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNITARIO</th>';
                  $tabla.='<th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COSTO TOTAL</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">ENE.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">FEB.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">MAR.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">ABR.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">MAY.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">JUN.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">JUL.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">AGO.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">SEP.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">OCT.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">NOV.</th>';
                  $tabla.='<th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">DIC.</th>';
                  $tabla.='<th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF">OBSERVACI&Oacute;N</th>';
                  $tabla.='<th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">COD. OPE.</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                /*---- RECUPERANDO DATOS DE LA TABLA AUXILIAR --------*/
                foreach ($requerimientos as $rows){
                        $prog = $this->minsumos->get_list_insumo_financiamiento($rows['insg_id']);
                        $nro++; $color='';
                        if(count($prog)!=0){
                            if(($rows['ins_costo_total'])!=$prog[0]['programado_total']){
                                $color='#f5bfb6';
                            }
                        }
                        
                        $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
                            $tabla.='<td style="width: 1%; text-align: center" style="height:14px;" bgcolor="'.$color.'">'.$nro.'</td>';
                            $tabla.='<td style="width: 4%; text-align: center" bgcolor="'.$color.'">'.$rows['par_codigo'].'</td>';
                          if($rows['ins_tipo']==6){
                            $tabla.='<td style="width: 15%; text-align: center" bgcolor="'.$color.'">'.$rows['ins_perfil'].'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 15%; text-align: center" bgcolor="'.$color.'">'.$rows['ins_detalle'].'</td>';
                          }
                          $tabla.='<td style="width: 5%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_unidad_medida'].'</td>';
                          $tabla.='<td style="width: 4%; text-align: right" bgcolor="'.$color.'">'.$rows['ins_cant_requerida'].'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_unitario'], 2, ',', '.').'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_total'], 2, ',', '.').'</td>';
                          if(count($prog)!=0){
                            $tabla.='
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 5%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>';
                          }
                        
                          $tabla.='<td style="width: 8%; text-align: left">'.$rows['ins_observacion'].'</td>';
                          $tabla.='<td style="width: 5%; text-align: center">'.$rows['prod_cod'].'</td>';
                        $tabla.='</tr>';
                        $sum_prog=$sum_prog+$rows['ins_costo_total'];
                    }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="6"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                            <td colspan="13"></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
    }

    /*----------- CONSOLIDADO DE PARTIDAS POR SUB ACTIVIDADES - OPERACIONES (2019)------------*/
    public function consolidado_partidas_componente($com_id){
        $tabla ='';
            $partidas = $this->minsumos->list_consolidado_partidas_componentes($com_id);
            if(count($partidas)!=0){
                $tabla.='
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                            <tr><td><FONT FACE="courier new" size="1">CONSOLIDADO POR PARTIDAS</FONT></td></tr>
                        </table>';
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro.</th>';
                  $tabla.='<th style="width:2%;">CODIGO</th>';
                  $tabla.='<th style="width:20%;">DETALLE PARTIDA</th>';
                  $tabla.='<th style="width:7%;">MONTO PROGRAMADO</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                foreach ($partidas as $row){
                    $nro++;
                    $tabla.='<tr class="modo1">';
                      $tabla.=' <td>'.$nro.'</td>
                                <td>'.$row['par_codigo'].'</td>
                                <td>'.$row['par_nombre'].'</td>
                                <td align="right">'.number_format($row['monto'], 2, ',', '.').'</td>';
                    $tabla.='</tr>';
                    $sum_prog=$sum_prog+$row['monto'];
                }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="3"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
    }

    /*----------- REQUERIMIENTOS POR SUB ACTIVIDADES A NIVEL DE COMPONENTES-DELEGADO (2018)------------*/
    public function delegado_requerimiento_procesos($proy_id,$com_id){
      $componente = $this->model_componente->get_componente($com_id); //// DATOS COMPONENTE
        
        $tabla ='';
            $requerimientos=$this->minsumos->list_requerimientos_delegado($com_id);
            if(count($requerimientos)!=0){
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro.</th>';
                  $tabla.='<th style="width:2%;">PARTIDA</th>';
                  $tabla.='<th style="width:20%;">DETALLE REQUERIMIENTO</th>';
                  $tabla.='<th style="width:7%;">UNIDAD</th>';
                  $tabla.='<th style="width:5%;">CANTIDAD</th>';
                  $tabla.='<th style="width:5%;">UNITARIO</th>';
                  $tabla.='<th style="width:5%;">COSTO TOTAL</th>';
                  $tabla.='<th style="width:5%;">ENE.</th>';
                  $tabla.='<th style="width:5%;">FEB.</th>';
                  $tabla.='<th style="width:5%;">MAR.</th>';
                  $tabla.='<th style="width:5%;">ABR.</th>';
                  $tabla.='<th style="width:5%;">MAY.</th>';
                  $tabla.='<th style="width:5%;">JUN.</th>';
                  $tabla.='<th style="width:5%;">JUL.</th>';
                  $tabla.='<th style="width:5%;">AGO.</th>';
                  $tabla.='<th style="width:5%;">SEP.</th>';
                  $tabla.='<th style="width:5%;">OCT.</th>';
                  $tabla.='<th style="width:5%;">NOV.</th>';
                  $tabla.='<th style="width:5%;">DIC.</th>';
                  $tabla.='<th style="width:13%;">OBSERVACI&Oacute;N</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                foreach ($requerimientos as $rows){
                        $prog = $this->minsumos->get_list_insumo_financiamiento($rows['insg_id']);
                        $nro++; $color='';
                        if(count($prog)!=0){
                            if(($rows['ins_costo_total'])!=$prog[0]['programado_total']){
                                $color='#f5bfb6';
                            }
                        }
                        
                        $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
                            $tabla.='<td style="width: 1%; text-align: center" style="height:14px;" bgcolor="'.$color.'">'.$nro.'</td>';
                            $tabla.='<td style="width: 4%; text-align: center" bgcolor="'.$color.'">'.$rows['par_codigo'].'</td>';
                          if($rows['ins_tipo']==6){
                            $tabla.='<td style="width: 15%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_perfil'].'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 15%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_detalle'].'</td>';
                          }
                          $tabla.='<td style="width: 5%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_unidad_medida'].'</td>';
                          $tabla.='<td style="width: 4%; text-align: right" bgcolor="'.$color.'">'.$rows['ins_cant_requerida'].'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_unitario'], 2, ',', '.').'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_total'], 2, ',', '.').'</td>';
                          if(count($prog)!=0){
                            $tabla.='
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 5%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>';
                          }
                        
                          $tabla.='<td style="width: 8%; text-align: left">'.$rows['ins_observacion'].'</td>';
                        $tabla.='</tr>';
                        $sum_prog=$sum_prog+$rows['ins_costo_total'];
                    }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="6"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                            <td colspan="13"></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
    }

    /*----------- CONSOLIDADO DE PARTIDAS POR SUB ACTIVIDADES - DELEGADO (2019)------------*/
    public function consolidado_partidas_delegado($com_id){
        $tabla ='';
            $partidas = $this->minsumos->list_consolidado_partidas_delegado($com_id);
            if(count($partidas)!=0){
                $tabla.='
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                            <tr><td><FONT FACE="courier new" size="1">CONSOLIDADO POR PARTIDAS</FONT></td></tr>
                        </table>';
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro.</th>';
                  $tabla.='<th style="width:2%;">CODIGO</th>';
                  $tabla.='<th style="width:20%;">DETALLE PARTIDA</th>';
                  $tabla.='<th style="width:7%;">MONTO PROGRAMADO</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                foreach ($partidas as $row){
                    $nro++;
                    $tabla.='<tr class="modo1">';
                      $tabla.=' <td>'.$nro.'</td>
                                <td>'.$row['par_codigo'].'</td>
                                <td>'.$row['par_nombre'].'</td>
                                <td align="right">'.number_format($row['monto'], 2, ',', '.').'</td>';
                    $tabla.='</tr>';
                    $sum_prog=$sum_prog+$row['monto'];
                }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="3"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
    }
    /*----------- REQUERIMIENTOS POR SUB ACTIVIDADES A NIVEL DE ACTIVIDADES (2019)------------*/
    public function actividad_requerimiento_procesos($proy_id,$com_id){
      $componente = $this->model_componente->get_componente($com_id); //// DATOS COMPONENTE
        
        $tabla ='';
            $requerimientos = $this->minsumos->list_requerimientos_actividades_procesos($com_id);
            if(count($requerimientos)!=0){
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro.</th>';
                  $tabla.='<th style="width:2%;">PARTIDA</th>';
                  $tabla.='<th style="width:20%;">DETALLE REQUERIMIENTO</th>';
                  $tabla.='<th style="width:7%;">UNIDAD</th>';
                  $tabla.='<th style="width:5%;">CANTIDAD</th>';
                  $tabla.='<th style="width:5%;">UNITARIO</th>';
                  $tabla.='<th style="width:5%;">COSTO TOTAL</th>';
                  $tabla.='<th style="width:5%;">ENE.</th>';
                  $tabla.='<th style="width:5%;">FEB.</th>';
                  $tabla.='<th style="width:5%;">MAR.</th>';
                  $tabla.='<th style="width:5%;">ABR.</th>';
                  $tabla.='<th style="width:5%;">MAY.</th>';
                  $tabla.='<th style="width:5%;">JUN.</th>';
                  $tabla.='<th style="width:5%;">JUL.</th>';
                  $tabla.='<th style="width:5%;">AGO.</th>';
                  $tabla.='<th style="width:5%;">SEP.</th>';
                  $tabla.='<th style="width:5%;">OCT.</th>';
                  $tabla.='<th style="width:5%;">NOV.</th>';
                  $tabla.='<th style="width:5%;">DIC.</th>';
                  $tabla.='<th style="width:13%;">OBSERVACI&Oacute;N</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                foreach ($requerimientos as $rows){
                        $prog = $this->minsumos->get_list_insumo_financiamiento($rows['insg_id']);
                        $nro++; $color='';
                        if(count($prog)!=0){
                            if(($rows['ins_costo_total'])!=$prog[0]['programado_total']){
                                $color='#f5bfb6';
                            }
                        }
                        
                        $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
                            $tabla.='<td style="width: 1%; text-align: center" style="height:14px;" bgcolor="'.$color.'">'.$nro.'</td>';
                            $tabla.='<td style="width: 4%; text-align: center" bgcolor="'.$color.'">'.$rows['par_codigo'].'</td>';
                          if($rows['ins_tipo']==6){
                            $tabla.='<td style="width: 15%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_perfil'].'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 15%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_detalle'].'</td>';
                          }
                          $tabla.='<td style="width: 5%; text-align: left" bgcolor="'.$color.'">'.$rows['ins_unidad_medida'].'</td>';
                          $tabla.='<td style="width: 4%; text-align: right" bgcolor="'.$color.'">'.$rows['ins_cant_requerida'].'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_unitario'], 2, ',', '.').'</td>';
                          $tabla.='<td style="width: 5%; text-align: right" bgcolor="'.$color.'">'.number_format($rows['ins_costo_total'], 2, ',', '.').'</td>';
                          if(count($prog)!=0){
                            $tabla.='
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes2'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes3'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes4'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes5'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes6'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes7'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes8'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes9'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes10'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes11'], 2, ',', '.').'</td>
                                    <td style="width: 4%; text-align: right">'.number_format($prog[0]['mes12'], 2, ',', '.').'</td>';
                          }
                          else{
                            $tabla.='<td style="width: 5%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>
                                    <td style="width: 4%; text-align: right; color: red">0.00</td>';
                          }
                        
                          $tabla.='<td style="width: 8%; text-align: left">'.$rows['ins_observacion'].'</td>';
                        $tabla.='</tr>';
                        $sum_prog=$sum_prog+$rows['ins_costo_total'];
                    }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="6"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                            <td colspan="13"></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
    }

     /*----------- CONSOLIDADO DE PARTIDAS POR SUB ACTIVIDADES - ACTIVIDADES (2019)------------*/
    public function consolidado_partidas_act_componente($com_id){
        $tabla ='';
            $partidas = $this->minsumos->list_consolidado_partidas_act_componentes($com_id);
            if(count($partidas)!=0){
                $tabla.='
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                            <tr><td><FONT FACE="courier new" size="1">CONSOLIDADO POR PARTIDAS</FONT></td></tr>
                        </table>';
                $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">';
                $tabla.='<thead>';
                $tabla.='<tr class="modo1">';
                  $tabla.='<th style="width:2%;">Nro.</th>';
                  $tabla.='<th style="width:2%;">CODIGO</th>';
                  $tabla.='<th style="width:20%;">DETALLE PARTIDA</th>';
                  $tabla.='<th style="width:7%;">MONTO PROGRAMADO</th>';
                $tabla.='</tr>';
                $tabla.='</thead>';
                $tabla.='<tbody>';
                $nro=0;
                $sum_prog=0;
                foreach ($partidas as $row){
                    $nro++;
                    $tabla.='<tr class="modo1">';
                      $tabla.=' <td>'.$nro.'</td>
                                <td>'.$row['par_codigo'].'</td>
                                <td>'.$row['par_nombre'].'</td>
                                <td align="right">'.number_format($row['monto'], 2, ',', '.').'</td>';
                    $tabla.='</tr>';
                    $sum_prog=$sum_prog+$row['monto'];
                }
                $tabla .='
                    </tbody>
                        <tr class="modo1">
                            <td colspan="3"><font color="blue"><b>TOTAL PROGRAMADO</b></font></td>
                            <td align="right"><font color="blue"><b>'.number_format($sum_prog, 2, ',', '.').'</b></font></td>
                        <tr>
                </table>';
            }
     
      return $tabla;
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

    function estilo_vertical(){
      $estilo_vertical = '<style>
      body{
          font-family: sans-serif;
          }
      table{
          font-size: 7px;
          width: 100%;
          background-color:#fff;
      }
      .mv{font-size:10px;}
      .verde{ width:100%; height:5px; background-color:#1c7368;}
      .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      .siipp{width:120px;}

      .titulo_pdf {
          text-align: left;
          font-size: 6px;
      }
      .tabla {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 7px;
      width: 100%;

      }
      .tabla th {
      padding: 2px;
      font-size: 7px;
      background-color: #1c7368;
      background-repeat: repeat-x;
      color: #FFFFFF;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #558FA6;
      border-bottom-color: #558FA6;
      text-transform: uppercase;
      }
      .tabla .modo1 {
      font-size: 7px;
      font-weight:bold;
     
      background-image: url(fondo_tr01.png);
      background-repeat: repeat-x;
      color: #34484E;
     
      }
      .tabla .modo1 td {
      padding: 1px;
      border-right-width: 1px;
      border-bottom-width: 1px;
      border-right-style: solid;
      border-bottom-style: solid;
      border-right-color: #A4C4D0;
      border-bottom-color: #A4C4D0;
      }
    </style>';
      return $estilo_vertical;
    }

}