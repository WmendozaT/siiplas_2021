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





    /*----- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
    public function verif_oregional($proy_id){
        $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
        $tabla='';
        $nro=0;
        foreach($list_oregional as $row){
            $nro++;
            $tabla.='<h1> '.$nro.'.- OPERACIÓN REGIONAL : <small> '.$row['or_codigo'].'.- '.$row['or_objetivo'].'</small></h1>';
        }

        return $tabla;
    }

    

    /*----- REPORTE - CONSOLIDADO PARTIDAS X UNIDAD RESPONSABLE (2020 - 2022) -----*/
    public function consolidado_partida_reporte($partidas,$tp_id){
        $tabla='';
        //$partidas=$this->model_insumo->list_consolidado_partidas_componentes($com_id);

        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:70%;" align=center>
                <thead>
                    <tr style="font-size: 7px;height:12px;" bgcolor="#eceaea" align=center>
                        <th style="width:3%;"style="height:11px;">N°</th>
                        <th style="width:10%;">C&Oacute;DIGO</th>
                        <th style="width:50%;">DETALLE PARTIDA</th>
                        <th style="width:12%;">MONTO PROGRAMADO</th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0; $total=0;
                    foreach ($partidas as $row){ 
                        $nro++; $total=$total+$row['monto'];
                        $tabla.=
                        '<tr style="font-size: 7px;">
                            <td style="width: 3%; height:11px; text-align: center">'.$nro.'</td>
                            <td style="width: 10%; text-align: center;font-size: 8px;"><b>'.$row['par_codigo'].'</b></td>
                            <td style="width: 50%; text-align: left;">'.$row['par_nombre'].'</td>
                            <td style="width: 12%; text-align: right;">'.number_format($row['monto'], 2, ',', '.').'</td>
                        </tr>';
                    }
            $tabla.=
                '</tbody>
                    <tr style="font-size: 7px;" bgcolor="#eceaea">
                        <td style="width: 50%; height:10px; text-align: left;" colspan=3><b>TOTAL PROGRAMADO </b></td>
                        <td style="width: 12%; text-align: right;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                    </tr>
            </table>';

        return $tabla;
    }

    /*----- REPORTE - CONSOLIDADO TOTAL PARTIDAS POR UNIDAD/PROYECTO -----*/
    public function consolidado_ptto_reporte($proyecto){
        $partidas_prog=$this->model_ptto_sigep->partidas_accion_region($proyecto[0]['dep_id'],$proyecto[0]['aper_id'],2); // Prog
        $tabla='';

        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:80%;" align=center>
                <thead>
                    <tr style="font-size: 8px;" bgcolor="#eceaea" align=center>
                        <th style="width:5%;height:15px;">Nro</th>
                        <th style="width:15%;">C&Oacute;DIGO</th>
                        <th style="width:50%;">DETALLE PARTIDA</th>
                        <th style="width:15%;">MONTO PROGRAMADO</th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0; $total=0;
                    foreach ($partidas_prog as $row){ 
                        $nro++; $total=$total+$row['monto'];
                        $tabla.=
                        '<tr style="font-size: 7px;">
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


    //// REPORTE FORMULARIO POA N 4 PDF
    public function reporte_formulario4($com_id){
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        if(count($componente)!=0){
            $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); //// DATOS PROYECTO
            
            $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;

            $data['operaciones']=$this->programacionpoa->rep_formulario_N4_v1_pi($componente[0]['com_id'],$componente[0]['com_componente']); /// Reporte Gasto Corriente, Proyecto de Inversion 2022 //// PROYECTO DE INVERSION
            
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); /// PROYECTO
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            
                if($this->gestion>2023){
                    $data['operaciones']=$this->programacionpoa->rep_formulario_N4_v2($componente[0]['com_id'],$componente[0]['com_componente'],$proyecto); /// 2024
                }
                else{
                    $data['operaciones']=$this->programacionpoa->rep_formulario_N4_v1($componente[0]['com_id'],$componente[0]['com_componente'],$proyecto); /// 2023
                }
            }
            $data['cabecera']=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],4,$proyecto,$com_id);
            $data['pie']=$this->programacionpoa->pie_form($proyecto);
            $this->load->view('admin/programacion/reportes/reporte_form4', $data);
        }
        else{
            echo "Error !!!";
        }
    }



    //// REPORTE FORMULARIO POA N 4 - CONSOLIDADO
    public function reporte_formulario4_consolidado($proy_id){
        $tabla='';
        $data['mes'] = $this->mes_nombre();
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
        if(count($proyecto)!=0){
            $unidades_responsables=$this->model_componente->lista_subactividad($proy_id); /// Unidades Responsables
            $pie=$this->programacionpoa->pie_form($proyecto);
            
            if($proyecto[0]['tp_id']==4){ //// Gasto Corriente
                $tabla.=$this->programacionpoa->caratula_poa_gacorriente($proyecto);
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].' '.$proyecto[0]['abrev'].'-'.$this->gestion;
            }
            else{ /// Proyecto de Inversion
                $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS PROYECTO
                $tabla.=$this->programacionpoa->caratula_poa_pinversion($proyecto);
                $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$this->gestion;
            }
            
            foreach($unidades_responsables as $pr){
                if($this->model_producto->productos_nro($pr['com_id'])!=0){
                    $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],4,$proyecto,$pr['com_id']);
                    $formulario_N4=$this->programacionpoa->rep_formulario_N4_v1_pi($pr['com_id'],$pr['com_componente']); /// Reporte Form 4 Gasto Corriente
                    if($proyecto[0]['tp_id']==4){ /// gasto corriente
                        if($this->gestion>2023){
                            $formulario_N4=$this->programacionpoa->rep_formulario_N4_v2($pr['com_id'],$pr['com_componente'],$proyecto);
                        }
                        else{
                            $formulario_N4=$this->programacionpoa->rep_formulario_N4_v1($pr['com_id'],$pr['com_componente'],$proyecto);
                        }
                    }
                    
                    $cabecera_f5=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto,$pr['com_id']);
                    $requerimientos=$this->programacionpoa->list_requerimientos_reporte($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']));
                    
                    $lista_partidas=$this->model_insumo->list_consolidado_partidas_componentes($pr['com_id']);
                    $partidas=$this->consolidado_partida_reporte($lista_partidas,$proyecto[0]['tp_id']);

                    $tabla.='
                    <page orientation="paysage" backtop="75mm" backbottom="35.5mm" backleft="5mm" backright="5mm" pagegroup="new">
                        <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                        </page_header>
                        <page_footer>
                            '.$pie.'
                        </page_footer>
                        '.$formulario_N4.'
                    </page>';
                    if(count($this->model_insumo->list_requerimientos_operacion_procesos($pr['com_id']))!=0){
                        $tabla.='
                        <page backtop="75mm" backbottom="29mm" backleft="5mm" backright="5mm" pagegroup="new">
                            <page_header>
                                <br><div class="verde"></div>
                                '.$cabecera_f5.'
                            </page_header>
                            <page_footer>
                                '.$pie.'
                            </page_footer>
                            '.$requerimientos.'
                        </page>
                        <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                            <page_header>
                                <br><div class="verde"></div>
                                '.$cabecera_f5.'
                            </page_header>
                            <page_footer>
                                '.$pie.'
                            </page_footer>
                            '.$partidas.'
                        </page>';
                    }

                    $get_uniresp_progBolsa=$this->model_producto->verif_get_uni_resp_programaBolsa($pr['com_id']); // Verifica la Actividad de la Unidad Responsable del Programa Bolsa
                    if(count($get_uniresp_progBolsa)!=0){

                        foreach($get_uniresp_progBolsa as $bolsa){

                        $lista_insumos=$this->model_insumo->lista_requerimientos_inscritos_en_programas_bosas($bolsa['prod_id'],$bolsa['uni_resp']);
                        if(count($lista_insumos)!=0){
                            $requerimientos=$this->programacionpoa->list_requerimientos_reporte($lista_insumos);
                            $lista_partidas=$this->model_insumo->list_consolidado_partidas_programas_boLsas_uresponsable($bolsa['prod_id'],$bolsa['uni_resp']);
                            $partidas=$this->consolidado_partida_reporte($lista_partidas,4);
                        }
                        else{
                            $requerimientos='No se Tiene Informacion Registrado !!!';   
                            $partidas='Sin Informacion';
                        }

                        $componente = $this->model_componente->get_componente($bolsa['com_id'],$this->gestion);
                        $proyecto_bolsa = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); //// DATOS PROYECTO
                        $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto_bolsa,$bolsa['uni_resp']);

                        $tabla.='
                        <page orientation="paysage" backtop="75mm" backbottom="35.5mm" backleft="5mm" backright="5mm" pagegroup="new">
                            <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                            </page_header>
                            <page_footer>
                            '.$pie.'
                            </page_footer>
                            '.$requerimientos.'
                        </page>
                        <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                            <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                            </page_header>
                            <page_footer>
                            '.$pie.'
                            </page_footer>
                            '.$partidas.'
                        </page>';
                        }
                    }
                }
            }

            $data['lista']=$tabla;
      //echo $tabla;
            $this->load->view('admin/programacion/reportes/reporte_form4_consolidado', $data);
        }
        else{
            echo "Error !!!";
        }
    }


    //// REPORTE FORMULARIO POA N 5 (NORMAL)
    public function reporte_formulario5($com_id){
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        if(count($componente)!=0){
            $proyecto = $this->model_proyecto->get_id_proyecto($componente[0]['proy_id']); //// DATOS PROYECTO
            $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); /// PROYECTO
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            }

            $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto,$com_id);
            
            $lista_partidas=$this->model_insumo->list_consolidado_partidas_componentes($com_id);
            $partidas=$this->consolidado_partida_reporte($lista_partidas,$proyecto[0]['tp_id']);
            $pie=$this->programacionpoa->pie_form($proyecto);

            $requerimientos='<b>SIN REQUERIMIENTOS PROGRAMADOS .</b>';
            if(count($this->model_insumo->list_requerimientos_operacion_procesos($com_id))!=0){
                $requerimientos=$this->programacionpoa->list_requerimientos_reporte($this->model_insumo->list_requerimientos_operacion_procesos($com_id));
            }

            $tabla='';
            $tabla.='
                <page backtop="75mm" backbottom="22mm" backleft="5mm" backright="5mm" pagegroup="new">
                    <page_header>
                        <br><div class="verde"></div>
                        '.$cabecera.'
                    </page_header>
                    <page_footer>
                        '.$pie.'
                    </page_footer>
                    '.$requerimientos.'
                </page>

                <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                    <page_header>
                        <br><div class="verde"></div>
                        '.$cabecera.'
                    </page_header>
                    <page_footer>
                        '.$pie.'
                    </page_footer>
                    '.$partidas.'

                </page>';

            $data['informacion']=$tabla;

            $this->load->view('admin/programacion/reportes/reporte_form5', $data);
        }
        else{
            echo "Error !!!";
        }
    }



    //// REPORTE FORMULARIO POA N 5 (CONSOLIDADO CON PROGRAMAS BOLSAS)
    public function reporte_formulario5_consolidado($com_id){
        $componente=$this->model_componente->get_componente($com_id,$this->gestion);
        if(count($componente)!=0){
            $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); //// DATOS PROYECTO
            $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']); /// PROYECTO
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            }

            $tabla='';
            $lista_insumos=$this->model_insumo->list_requerimientos_operacion_procesos($com_id); /// Lista requerimientos
            $pie=$this->programacionpoa->pie_form($proyecto);

            //// ----- Requerimiento form 5 Poa Normal
            if(count($lista_insumos)!=0){
                $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto,$com_id);
                $requerimientos=$this->programacionpoa->list_requerimientos_reporte($lista_insumos);
                $lista_partidas=$this->model_insumo->list_consolidado_partidas_componentes($com_id);
                $partidas=$this->consolidado_partida_reporte($lista_partidas,$proyecto[0]['tp_id']);
                
                $tabla.='
                <page orientation="paysage" backtop="75mm" backbottom="22mm" backleft="5mm" backright="5mm" pagegroup="new">
                    <page_header>
                        <br><div class="verde"></div>
                        '.$cabecera.'
                    </page_header>
                    <page_footer>
                        '.$pie.'
                    </page_footer>
                    '.$requerimientos.'
                </page>

                <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                    <page_header>
                        <br><div class="verde"></div>
                        '.$cabecera.'
                    </page_header>
                    <page_footer>
                        '.$pie.'
                    </page_footer>
                    '.$partidas.'
                </page>';
            }
            /// -------------------------------------

            /// --------- Programas Bolsas form 5 ---
            $programas_bolsas=$this->model_producto->get_lista_form4_uniresp_prog_bolsas($com_id);
            foreach($programas_bolsas as $row){
                $componente = $this->model_componente->get_componente($row['uni_resp'],$this->gestion);
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($row['proy_id']); //// DATOS PROYECTO

                $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto,$row['uni_resp']);
                $lista_insumos=$this->model_insumo->lista_requerimientos_inscritos_en_programas_bosas($row['prod_id'],$row['uni_resp']);
                if(count($lista_insumos)!=0){
                    $requerimientos=$this->programacionpoa->list_requerimientos_reporte($lista_insumos); /// lista de requerimientos
                    $lista_partidas=$this->model_insumo->list_consolidado_partidas_programas_boLsas_uresponsable($row['prod_id'],$row['uni_resp']); /// consolidado por partidas
                    $partidas=$this->consolidado_partida_reporte($lista_partidas,$proyecto[0]['tp_id']);

                    $tabla.='
                    <page orientation="paysage" backtop="75mm" backbottom="22mm" backleft="5mm" backright="5mm" pagegroup="new">
                        <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                        </page_header>
                        <page_footer>
                            '.$pie.'
                        </page_footer>
                        '.$requerimientos.'
                    </page>

                    <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                        <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                        </page_header>
                        <page_footer>
                            '.$pie.'
                        </page_footer>
                        '.$partidas.'

                    </page>';
                }
            }


            $data['informacion']=$tabla;
            $data['pie_rep']=$proyecto[0]['proy_nombre'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
            $this->load->view('admin/programacion/reportes/reporte_form5', $data);
        }
        else{
            echo "Error !!!";
        }
    }



    //// REPORTE FORMULARIO POA N 5 PARA PROGRAMAS BOLSA 
    public function reporte_prog_bolsa_formulario5($aper_id,$com_id){
        $get_actividades_global=$this->model_producto->verif_get_uni_resp_programaBolsa_prog($aper_id,$com_id);
        $componente = $this->model_componente->get_componente($com_id,$this->gestion);
        $tabla='';

        if(count($componente)!=0){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($get_actividades_global[0]['proy_id']); /// PROYECTO
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'-'.$componente[0]['serv_descripcion'].' '.$this->gestion;
                $pie=$this->programacionpoa->pie_form($proyecto);

                foreach($get_actividades_global as $row){
                    $cabecera=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],5,$proyecto,$row['uni_resp']);
                    $lista_insumos=$this->model_insumo->lista_requerimientos_inscritos_en_programas_bosas($row['prod_id'],$row['uni_resp']);

                    if(count($lista_insumos)!=0){
                        $requerimientos=$this->programacionpoa->list_requerimientos_reporte($lista_insumos);
                        $lista_partidas=$this->model_insumo->list_consolidado_partidas_programas_boLsas_uresponsable($row['prod_id'],$row['uni_resp']);
                        $partidas=$this->consolidado_partida_reporte($lista_partidas,$proyecto[0]['tp_id']);
                    }
                    else{
                        $requerimientos='No se Tiene Informacion Registrado !!!';   
                        $partidas='Sin Informacion';
                    }
 

                    $tabla.='
                    <page orientation="paysage" backtop="75mm" backbottom="22mm" backleft="5mm" backright="5mm" pagegroup="new">
                        <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                        </page_header>
                        <page_footer>
                            '.$pie.'
                        </page_footer>
                        '.$requerimientos.'
                    </page>

                    <page orientation="portrait" backtop="80mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
                        <page_header>
                            <br><div class="verde"></div>
                            '.$cabecera.'
                        </page_header>
                        <page_footer>
                            '.$pie.'
                        </page_footer>
                        '.$partidas.'

                    </page>';
                }

                $data['informacion']=$tabla;
                $this->load->view('admin/programacion/reportes/reporte_form5', $data);
        }
        else{
            echo "Errowr en la Informacion del Fornulario N° 4";
        }

    }


    /*------- REPORTE CONSOLIDADO PRESUPUESTO POR PARTIDAS (2020) ------*/
    public function reporte_presupuesto_consolidado($proy_id){
        $data['mes'] = $this->mes_nombre();
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
        if(count($proyecto)!=0){
            $data['pie_rep']=$proyecto[0]['proy_nombre'].' '.$this->gestion;
            if($proyecto[0]['tp_id']==4){
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// PROYECTO
                $data['pie_rep']=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].' '.$this->gestion;
                $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);    
            }
            
            $data['cabecera']=$this->programacionpoa->cabecera($proyecto[0]['tp_id'],0,$proyecto,0);
            $data['consolidado']=$this->consolidado_ptto_reporte($proyecto);
            $data['pie']=$this->programacionpoa->pie_form($proyecto);
            $this->load->view('admin/programacion/reportes/reporte_consolidado_presupuesto', $data);
        }
        else{
            echo "<b>ERROR !!!!!</b>";
        }
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