<?php
class Creporte extends CI_Controller { 
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->library('pdf2');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/insumos/model_insumo');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('mestrategico/model_objetivoregion');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('menu_modelo');
            $this->load->library('security');
            $this->load->model('Users_model','',true);
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->dist = $this->session->userData('dist');
            $this->rol = $this->session->userData('rol_id');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->fun_id = $this->session->userdata("fun_id");
            $this->load->library('programacionpoa');

            }else{
                $this->session->sess_destroy();
                redirect('/','refresh');
            }
    }


    /*------ REPORTE - CARATULA ------*/
    public function presentacion_poa($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['oregional']=$this->verif_oregional($proy_id);
            $data['titulo']='Caratula POA';
            $data['contenido']='<hr><iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/proy/presentacion/'.$data['proyecto'][0]['proy_id'].'"></iframe>';
            $this->load->view('admin/programacion/reportes/reporte_poa', $data);
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }
    }

    /*------ REPORTE - IDENTIFICACION ------*/
    public function datos_generales($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['oregional']=$this->verif_oregional($proy_id);
            $data['titulo']='Datos Generales';
            $data['contenido']='<hr><iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/prog/rep_datos_unidad/'.$data['proyecto'][0]['act_id'].'"></iframe>';
            $this->load->view('admin/programacion/reportes/reporte_poa', $data);
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }
    }

    /*------ REPORTE - PROGRAMACION FISICA ------*/
    public function programacion_fisica($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['oregional']=$this->verif_oregional($proy_id);
            $data['titulo']='Programación Física';
            $data['contenido']=$this->mis_servicios_componentes($proy_id);
            
            $this->load->view('admin/programacion/reportes/reporte_poa', $data);
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }
    }

    /*------ MIS SERVICIOS - FUNCIONAMIENTO ------*/
    public function mis_servicios_componentes($proy_id){
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $fase = $this->model_faseetapa->get_id_fase($proy_id);
        $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);
        if($proyecto[0]['tp_id']==1){
            $titulo='MIS COMPONENTES';
            $titulo_sub='COMPONENTE';
        }
        else{
            $titulo='MIS SERVICIOS';
            $titulo_sub='SERVICIO';
        }

        $tabla='';
        $tabla.='<div class="row"><br>';
        $tabla.='<article class="col-sm-12 col-md-12 col-lg-3">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                        <header>
                            <span class="widget-icon"></span>
                            <h2>'.$titulo.'</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body">

                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr style="height:45px;">
                                        <th style="width:1%;">#</th>
                                        <th style="width:15%;">'.$titulo_sub.'</th>
                                        <th style="width:5%;">VER. ACTIVIDADES</th>
                                        <th style="width:5%;">VER REQUERIMIENTOS</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                $nro=0;
                                foreach($componente as $row){
                                    $nro++;
                                    $tabla.=
                                    '<tr>
                                        <td>'.$nro.'</td>
                                        <td>'.$row['com_componente'].'</td>
                                        <td><a href="#" class="btn btn-info enlace" name="'.$row['com_id'].'" id="'.strtoupper($row['com_componente']).'" id1="1" >Ver Actividades</a></td>
                                        <td><a href="#" class="btn btn-info enlace" name="'.$row['com_id'].'" id="'.strtoupper($row['com_componente']).'" id1="2" >Ver requerimientos</a></td>
                                    </tr>';
                                }
                                $tabla.='
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-sm-12 col-md-12 col-lg-9">
                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                        <header>
                            <span class="widget-icon"></span>
                            <h2><div id="tp_rep"></div></h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body">
                                <div id="content1"></div>
                            </div>
                        </div>
                    </div>
                </article>';
        $tabla.='</div>';
        return $tabla;
    }

    /*-------- GET REPORTES POAS ------------*/
    public function get_reportes_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']); // com id
        $tp = $this->security->xss_clean($post['tp']); // tp 1:ope, 2:req

        $componente = $this->model_componente->get_componente($com_id,$this->gestion);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);

       if($tp==1){
        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/prog/rep_operacion_componente/'.$com_id.'"></iframe>';
       }
       else{
        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/proy/orequerimiento_proceso/'.$fase[0]['proy_id'].'/'.$com_id.'"></iframe>';
       }

        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*------ REPORTE - PROGRAMACION FINANCIERA ------*/
    public function programacion_financiera($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['oregional']=$this->verif_oregional($proy_id);
            $data['titulo']='Programación Financiera';
            $data['contenido']='Contenido 2';
            $this->load->view('admin/programacion/reportes/reporte_poa', $data);
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }
    }


    /*------- REPORTE REQUERIMIENTOS POR SERVICIOS (2020) ------*/
    public function reporte_proyecto_insumo_proceso($proy_id,$com_id){
        $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
        $data['mes'] = $this->mes_nombre();
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
        $data['componente'] = $this->model_componente->get_componente_pi($com_id); /// COMPONENTE
        if($data['proyecto'][0]['tp_id']==4){
            $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
           
        }

        $data['componente']=$this->model_componente->get_componente($com_id,$this->gestion);
        $data['requerimientos']=$this->list_requerimientos_reporte($com_id,$data['proyecto'][0]['tp_id']);
        $data['partidas']=$this->consolidado_partida_reporte($com_id,$data['proyecto'][0]['tp_id']);

        if($this->gestion==2020){
            $this->load->view('admin/programacion/reportes/reporte_requerimientos2020', $data);
        }
        else{
            $data['cabecera']=$this->cabecera($data['componente'],$data['proyecto'],1); /// Cabecera
            $this->load->view('admin/programacion/reportes/reporte_form5', $data);
        }
    }



    /*------- REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS (2020) ------*/
    public function reporte_presupuesto_consolidado($proy_id){
        $data['mes'] = $this->mes_nombre();
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
        if(count($data['proyecto'])!=0){
            if($data['proyecto'][0]['tp_id']==1){
                echo "Trabajando !!!!";
            }
            else{
                $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
                $data['consolidado']=$this->consolidado_ptto_reporte($data['proyecto']);
                
                $this->load->view('admin/programacion/reportes/reporte_consolidado_presupuesto', $data);
            }
        }
        else{
            echo "<b>ERROR !!!!!</b>";
        }
    }

    /*----- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
    public function verif_oregional($proy_id){
        $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
        $tabla='';
        $nro=0;
        foreach($list_oregional as $row){
            $nro++;
            $tabla.='<h1> '.$nro.'.- OBJETIVO REGIONAL : <small> '.$row['or_codigo'].'.- '.$row['or_objetivo'].'</small></h1>';
        }

        return $tabla;
    }

    /*----- REPORTE - LISTA DE REQUERIMIENTOS (2020) -----*/
    public function list_requerimientos_reporte($com_id,$tp_id){
        $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($com_id); /// Lista requerimientos
        $titulo_alineacion='COD. OPE.';
        if($this->gestion<2021){
            $titulo_alineacion='COD. ACT.';
        }

        $tabla='';
        $tabla.=' 
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
                <thead>
                 <tr style="font-size: 7px;" bgcolor="#1c7368" align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:4%;color:#FFF;">PARTIDA</th>
                    <th style="width:16%;color:#FFF;">DETALLE REQUERIMIENTO</th>
                    <th style="width:5%;color:#FFF;">UNIDAD</th>
                    <th style="width:4%;color:#FFF;">CANTIDAD</th>
                    <th style="width:5%;color:#FFF;">UNITARIO</th>
                    <th style="width:5%;color:#FFF;">TOTAL</th>
                    <th style="width:4%;color:#FFF;">ENE.</th>
                    <th style="width:4%;color:#FFF;">FEB.</th>
                    <th style="width:4%;color:#FFF;">MAR.</th>
                    <th style="width:4%;color:#FFF;">ABR.</th>
                    <th style="width:4%;color:#FFF;">MAY.</th>
                    <th style="width:4%;color:#FFF;">JUN.</th>
                    <th style="width:4%;color:#FFF;">JUL.</th>
                    <th style="width:4%;color:#FFF;">AGO.</th>
                    <th style="width:4%;color:#FFF;">SEPT.</th>
                    <th style="width:4%;color:#FFF;">OCT.</th>
                    <th style="width:4%;color:#FFF;">NOV.</th>
                    <th style="width:4%;color:#FFF;">DIC.</th>
                    <th style="width:8%;color:#FFF;">OBSERVACI&Oacute;N</th> 
                    <th style="width:4%;color:#FFF;">'.$titulo_alineacion.'</th>   
                </tr>
                </thead>
                <tbody>';
                $cont = 0; $total=0; 
                foreach ($lista_insumos as $row) {
                $cont++;
                $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                $total=$total+$row['ins_costo_total'];
                $color='';
                if(count($prog)!=0){
                    if(($row['ins_costo_total'])!=$prog[0]['programado_total']){
                        $color='#f5bfb6';
                    }
                }

                $tabla.=
                '<tr style="font-size: 6.5px;" >
                    <td style="width: 1%; font-size: 4.5px; text-align: center;height:13px;">'.$cont.'</td>
                    <td style="width: 4%; text-align: center;font-size: 8px;" bgcolor="#eceaea"><b>'.$row['par_codigo'].'</b></td>
                    <td style="width: 15%; text-align: left;font-size: 7.5px;">'.$row['ins_detalle'].'</td>
                    <td style="width: 5%; text-align: left">'.$row['ins_unidad_medida'].'</td>
                    <td style="width: 4%; text-align: right">'.round($row['ins_cant_requerida'],2).'</td>
                    <td style="width: 5%; text-align: right;">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                    <td style="width: 5%; text-align: right;font-size: 7.5px;" bgcolor="#eceaea">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>'; 
                    if(count($prog)!=0){ 
                    $tabla.=
                    '<td style="width: 4%; text-align: right;">'.number_format($prog[0]['mes1'], 2, ',', '.').'</td>
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
                    $tabla.=
                    '<td style="width: 4%; text-align: right; color: red">0.00</td>
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

                $tabla.='
                    <td style="width: 8%; text-align: left;">'.$row['ins_observacion'].'</td>
                    <td style="width: 4%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$row['prod_cod'].'</b></td>
                </tr>';
                }

            $tabla.='
                </tbody>
                <tr class="modo1" bgcolor="#eceaea">
                    <td colspan="6" style="height:10px;" ><b>TOTAL PROGRAMADO </b></td>
                    <td style="width: 4%; text-align: right; font-size: 7px;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                    <td colspan="14"></td>
                </tr>
            </table><br>';
        return $tabla;
    }

    /*----- REPORTE - CONSOLIDADO PARTIDAS SERVICIO (2020) -----*/
    public function consolidado_partida_reporte($com_id,$tp_id){
        $partidas=$this->model_insumo->list_consolidado_partidas_componentes($com_id);

        $tabla='';
        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:70%;" align=center>
                <thead>
                    <tr style="font-size: 7px;height:12px;" bgcolor="#1c7368" align=center>
                        <th style="width:3%;"style="height:11px;color:#FFF;">N°</th>
                        <th style="width:10%;color:#FFF;">C&Oacute;DIGO</th>
                        <th style="width:50%;color:#FFF;">DETALLE PARTIDA</th>
                        <th style="width:9%;color:#FFF;">MONTO PROGRAMADO</th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0; $total=0;
                    foreach ($partidas as $row){ 
                        $nro++; $total=$total+$row['monto'];
                        $tabla.=
                        '<tr style="font-size: 7px;">
                            <td style="width: 3%; text-align: center" style="height:11px;">'.$nro.'</td>
                            <td style="width: 10%; text-align: center;font-size: 8px;"><b>'.$row['par_codigo'].'</b></td>
                            <td style="width: 50%; text-align: left;">'.$row['par_nombre'].'</td>
                            <td style="width: 9%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                        </tr>';
                    }
            $tabla.=
                '</tbody>
                    <tr style="font-size: 7px;" bgcolor="#eceaea">
                        <td style="width: 50%; height:10px; text-align: left;" colspan=3><b>TOTAL PROGRAMADO </b></td>
                        <td style="width: 9%; text-align: right;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                    </tr>
            </table>';
        return $tabla;
    }

    /*----- REPORTE - CONSOLIDADO TOTAL PARTIDAS (2020) -----*/
    public function consolidado_ptto_reporte($proyecto){
        $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],2); // Prog
        $tabla='';
        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:80%;" align=center>
                <thead>
                    <tr style="font-size: 8px;" bgcolor="#1c7368" align=center>
                        <th style="width:5%;height:11px;color:#FFF;">Nro</th>
                        <th style="width:15%;color:#FFF;">C&Oacute;DIGO</th>
                        <th style="width:50%;color:#FFF;">DETALLE PARTIDA</th>
                        <th style="width:15%;color:#FFF;">MONTO PROGRAMADO</th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0; $total=0;
                    foreach ($partidas_prog as $row){ 
                        $nro++; $total=$total+$row['monto'];
                        $tabla.=
                        '<tr style="font-size: 6.5px;">
                            <td style="width: 3%;height:10px; text-align: center">'.$nro.'</td>
                            <td style="width: 10%; text-align: center;font-size: 8px;"><b>'.$row['codigo'].'</b></td>
                            <td style="width: 50%; text-align: left;">'.$row['nombre'].'</td>
                            <td style="width: 9%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                        </tr>';
                    }
            $tabla.=
                '</tbody>
                    <tr style="font-size: 7px;" bgcolor="#eceaea">
                        <td style="width: 50%;height:11px; text-align: left;" colspan=3><b>TOTAL PROGRAMADO</b></td>
                        <td style="width: 9%; text-align: right;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                    </tr>
            </table>';
        return $tabla;
    }


    public function reporte_formulario4($com_id){
        $data['componente']=$this->model_componente->get_componente($com_id,$this->gestion);
        if(count($data['componente'])!=0){
            $data['mes'] = $this->mes_nombre();
            $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
            $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO
            $data['cabecera']=$this->cabecera($data['componente'],$data['proyecto'],1); /// Cabecera
            
            $data['operaciones']=$this->operaciones_form4($data['componente'],$data['proyecto']); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
       // echo $data['cabecera'];
           $this->load->view('admin/programacion/reportes/reporte_form4', $data);
        }
        else{
            echo "Error !!!";
        }
    }


    /*----- REPORTE - FORMULARIO 4 (2021) -----*/
/*    public function reporte_formulario4($com_id){
        $componente=$this->model_componente->get_componente($com_id);
        if(count($componente)!=0){
            $data['mes'] = $this->mes_nombre();
          //  $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
            $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); //// DATOS PROYECTO
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']);
            }

          //  $data['cabecera']=$this->cabecera($proyecto[0]['tp_id'],4,$proyecto); /// Cabecera
          //  $data['pie']='';
            $data['operaciones']=$this->operaciones_form4($componente,$proyecto); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
            $this->load->view('admin/programacion/reportes/reporte_form4', $data);
        }
        else{
            echo "Error !!!";
        }
    }*/

    /*----- TITULO DEL REPORTE tp:1 (pdf), 2:(Excel) 2021 -----*/
    public function cabecera($componente,$proyecto,$tp){
      $tabla='';
      $tabla.=' <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%;"><b>DIR. ADM.</b></td>
                      <td style="width:90%;">: '.$proyecto[0]['dep_cod'].' '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%;"><b>UNI. EJEC.</b></td>
                      <td style="width:90%;">: '.$proyecto[0]['dist_cod'].' '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                    </tr>
                    <tr style="font-size: 8pt;">';
                        if($proyecto[0]['tp_id']==1){ /// Proyecto de Inversion
                            $tabla.='
                            <td style="width:10%;"><b>PROY. INV.</b></td>
                            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'].'</td>';
                        }
                        else{ /// Gasto Corriente
                            $tabla.='
                            <td style="width:10%;"><b>ACTIVIDAD</b></td>
                            <td style="width:90%;">: '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                        }

                    $tabla.='
                    </tr>
                    <tr style="font-size: 8pt;">
                        <td style="height: 1.2%; width:10%;"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='UNI. RESP. ';
                          }
                          else{
                            $tabla.='SUBACT. ';
                          }
                        $tabla.='</b></td>
                        <td style="width:90%;">: '.strtoupper($componente[0]['serv_cod']).' '.strtoupper($componente[0]['tipo_subactividad']).' '.strtoupper($componente[0]['serv_descripcion']).'</td>
                    </tr>
                </table>';
      return $tabla;
    }

    /*----- REPORTE FORMULARIO 4 (2021 - Operaciones, Proyectos de Inversion) ----*/
    public function operaciones_form4($componente,$proyecto){
      //$obj_est=$this->model_producto->list_oestrategico($componente[0]['com_id']); /// Objetivos Estrategicos
      $tabla='';
      
      if($proyecto[0]['tp_id']==1){ /// Proyectos de Inversion
        $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OR.</th>
                    <th style="width:2%; color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:9%; color:#FFF;">COMPONENTE</th>
                    <th style="width:11%; color:#FFF;">OPERACI&Oacute;N</th>
                    <th style="width:11%; color:#FFF;">RESULTADO</th>
                    <th style="width:11%; color:#FFF;">INDICADOR</th>
                    <th style="width:2%; color:#FFF;">LB.</th>
                    <th style="width:2.5%; color:#FFF;">META</th>
                    <th style="width:2.5%; color:#FFF;">ENE.</th>
                    <th style="width:2.5%; color:#FFF;">FEB.</th>
                    <th style="width:2.5%; color:#FFF;">MAR.</th>
                    <th style="width:2.5%; color:#FFF;">ABR.</th>
                    <th style="width:2.5%; color:#FFF;">MAY.</th>
                    <th style="width:2.5%; color:#FFF;">JUN.</th>
                    <th style="width:2.5%; color:#FFF;">JUL.</th>
                    <th style="width:2.5%; color:#FFF;">AGO.</th>
                    <th style="width:2.5%; color:#FFF;">SEPT.</th>
                    <th style="width:2.5%; color:#FFF;">OCT.</th>
                    <th style="width:2.5%; color:#FFF;">NOV.</th>
                    <th style="width:2.5%; color:#FFF;">DIC.</th>
                    <th style="width:8.5%; color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%; color:#FFF;">PPTO.</th>   
                  </tr>
                </thead>
                <tbody>';
                $operaciones=$this->model_producto->list_operaciones_pi($componente[0]['com_id']);  /// 2020
                $nro=0;
                foreach($operaciones as $rowp){
                  $nro++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $tp='';
                  if($rowp['indi_id']==2){
                    $tp='%';
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }


                  $tabla.='
                  <tr>
                    <td style="height:12px;">'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 9%; text-align: left;">'.$componente[0]['com_componente'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width:11%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width:2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width:2.5%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['enero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['febrero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['marzo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['abril'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['mayo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['junio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['julio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['agosto'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['septiembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['octubre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['noviembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['diciembre'],2).''.$tp.'</td>
                    <td style="width:8.5%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';            
                }
          $tabla.='
                </tbody>
              </table>';

      }
      else{ //// Gasto Corriente

         $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OR.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th> 
                    <th style="width:10%;color:#FFF;">OPERACI&Oacute;N</th>
                    <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                    <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                    <th style="width:9%;color:#FFF;">INDICADOR</th>
                    <th style="width:2%;color:#FFF;">LB.</th>
                    <th style="width:3%;color:#FFF;">META</th>
                    <th style="width:3%;color:#FFF;">ENE.</th>
                    <th style="width:3%;color:#FFF;">FEB.</th>
                    <th style="width:3%;color:#FFF;">MAR.</th>
                    <th style="width:3%;color:#FFF;">ABR.</th>
                    <th style="width:3%;color:#FFF;">MAY.</th>
                    <th style="width:3%;color:#FFF;">JUN.</th>
                    <th style="width:3%;color:#FFF;">JUL.</th>
                    <th style="width:3%;color:#FFF;">AGO.</th>
                    <th style="width:3%;color:#FFF;">SEPT.</th>
                    <th style="width:3%;color:#FFF;">OCT.</th>
                    <th style="width:3%;color:#FFF;">NOV.</th>
                    <th style="width:3%;color:#FFF;">DIC.</th>
                    <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%;color:#FFF;">PPTO.</th>   
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                $operaciones=$this->model_producto->lista_operaciones($componente[0]['com_id']);
                
                foreach($operaciones as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
                $tabla.='
                </tbody>
              </table>';
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