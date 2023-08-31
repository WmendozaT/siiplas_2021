<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Componente extends CI_Controller { 
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->library('pdf2');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('mestrategico/model_objetivoregion');
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

            }else{
                redirect('/','refresh');
            }
    }


    /*------------ DELETE COMPONENTE (PROYECTOS DE INVERSIÓN) --------------*/
    function elimina_operaciones_componente_pi(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']);
          $productos = $this->model_producto->list_prod($com_id);

            foreach ($productos as $rowp) {
            $update_prod= array(
                'fun_id' => $this->fun_id,
                'estado' => 3
            );
            $this->db->where('prod_id', $rowp['prod_id']);
            $this->db->update('_productos', $this->security->xss_clean($update_prod));

            $actividad=$this->model_actividad->list_act_anual($rowp['prod_id']);
            foreach ($actividad as $rowa) {
                /*---------------------------------------*/
                $insumos = $this->model_actividad->insumo_actividad($rowa['act_id']);
                foreach ($insumos as $rowi) {
                  $update_ins= array(
                    'fun_id' => $this->fun_id,
                    'aper_id' => 0,
                    'ins_estado' => 3,
                    'num_ip' => $this->input->ip_address(), 
                    'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                  );
                  $this->db->where('ins_id', $rowi['ins_id']);
                  $this->db->update('insumos', $this->security->xss_clean($update_ins));

                  $update_insg= array(
                  'insg_estado' => 3
                  );
                  $this->db->where('ins_id', $ins_id);
                  $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
                }
                    
                    /*------------ UPDATE ACTIVIDAD -------*/
                    $update_act= array(
                      'fun_id' => $this->fun_id,
                      'estado' => 3
                    );
                    $this->db->where('act_id', $rowa['act_id']);
                    $this->db->update('_actividades', $this->security->xss_clean($update_act));
                }
            }

            $productos = $this->model_producto->list_prod($com_id);
            if(count($productos)==0){
                $update_com= array(
                    'fun_id' => $this->fun_id,
                    'estado' => 3
                );
                $this->db->where('com_id', $com_id);
                $this->db->update('_componentes', $this->security->xss_clean($update_com));

                $result = array(
                    'respuesta' => 'correcto'
                );
            }
            else{
                $result = array(
                    'respuesta' => 'error'
                );
            }
           
          echo json_encode($result);
      } else {
          echo 'DATOS ERRONEOS';
      }
    }

   
 

    /*-------- VERIFICACION DE CODIGO COMPONENTE (PI) --------*/
    function verif_codigo_componente(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $pfec_id = $this->security->xss_clean($post['pfec_id']); /// pfec id
          $fase = $this->model_faseetapa->get_fase($pfec_id);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); 

          $variable= $this->model_componente->get_fase_componente_nro($pfec_id,$codigo,1);
          if(count($variable)==0){
            echo "true"; /// Codigo Habilitado
          }
          else{
            echo "false"; /// No Existe Registrado
          }
      }else{
        show_404();
      }
    }

    /*---- CONSOLIDADO DE OPERACIONES POR SUB ACTIVIDADES, COMPONENTES (2019)----*/
    public function reporte_consolidado_operaciones_componentes($proy_id){
        $data['proyecto']=$this->model_proyecto->get_id_proyecto($proy_id);
        if(count($data['proyecto'])!=0){
            $data['mes'] = $this->mes_nombre();
            $data['componente_operaciones']=$this->get_proceso_consolidado($proy_id);
            $this->load->view('admin/programacion/componente/reporte_operaciones_componentes', $data);
        }
        else{
            echo "<center><b>ERROR!!!! AL GENERAR REPORTE</b></center>";
        }
    }

    /*------- LISTA DE OPERACIONES POR SUB ACTIVIDADES (2019) ------*/
    public function get_proceso_consolidado($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// DATOS DEL PROYECTO
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS FASE ACTIVA
      $componentes=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']); /// COMPONENTES/PROCESOS  
        
        $tabla ='';
        if(count($componentes)!=0){
            foreach ($componentes as $rowc){
                $productos = $this->model_producto->list_prod($rowc['com_id']);
                if(count($productos)!=0){
                    $tabla .='
                    <table>
                        <tr><td><font size="1"> '.$rowc['serv_cod'].'.- '.$rowc['com_componente'].'</font></td></tr>
                    </table>';
                    $nro_p=0;
                    $tabla .='<table border="0" cellpadding="0" cellspacing="0" class="tabla">';
                        $tabla.='<thead>
                                <tr class="modo1" style="height:45px;">
                                <th style="width:1%;" bgcolor="#1c7368"><font color="#ffffff">#</font></th>';
                                if($this->gestion==2018){
                                  $tabla.='<th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">PRODUCTO</font></th>';
                                }
                                else{
                                  $tabla.='
                                      <th style="width:9%;" bgcolor="#1c7368"><font color="#ffffff">OBJETIVO ESTRATEGICO</font></th>
                                      <th style="width:9%;" bgcolor="#1c7368"><font color="#ffffff">ACCI&Oacute;N ESTRATEGICA</font></th>
                                      <th style="width:9%;" bgcolor="#1c7368"><font color="#ffffff">OPERACI&Oacute;N</font></th>
                                      <th style="width:9%;" bgcolor="#1c7368"><font color="#ffffff">RESULTADO</font></th>';
                                }
                                $tabla.='
                                <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">TIP.</font></th>
                                <th style="width:8%;" bgcolor="#1c7368"><font color="#ffffff">INDICADOR</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">LINEA BASE</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">META</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">ENE.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">FEB.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">MAR.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">ABR.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">MAY.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">JUN.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">JUL.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">AGO.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">SEP.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">OCT.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">NOV.</font></th>
                                <th style="width:3%;" bgcolor="#1c7368"><font color="#ffffff">DIC.</font></th>
                                <th style="width:8%;" bgcolor="#1c7368"><font color="#ffffff">VERIFICACI&Oacute;N</font></th>
                            </tr>
                            </thead>
                        <tbody>';
                        $nro=0;
                        foreach($productos as $rowp){
                          $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                          $color='';
                            if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta']){
                              $color='#fbd5d5';
                            }
                            $nro++;
                            $tabla.='<tr class="modo1" bgcolor="'.$color.'" style="height:45px;">';
                            $tabla.='<td style="width: 1%; text-align: center" style="height:14px;">'.$nro.'</td>';
                              if($this->gestion==2018){
                               $tabla.='<td style="width: 7%; text-align: left">'.mb_convert_encoding(''.$rowp['prod_producto'].'', 'cp1252', 'UTF-8').'</td>'; 
                              }
                              else{
                                if($rowp['acc_id']!=null){
                                  $alineacion=$this->model_producto->operacion_accion($rowp['acc_id']);
                                  if(count($alineacion)!=0){
                                    $tabla.=' <td style="width: 9%; text-align: left">'.$alineacion[0]['obj_codigo'].'-'.$alineacion[0]['obj_descripcion'].'</td>
                                              <td style="width: 9%; text-align: left">'.$alineacion[0]['acc_codigo'].'-'.$alineacion[0]['acc_descripcion'].'</td>';
                                  }
                                  else{
                                    $tabla.=' <td style="width: 9%; text-align: left"></td>
                                              <td style="width: 9%; text-align: left"><font color="red">'.$rowp['acc_id'].'</font></td>';
                                  }
                                }
                                else{
                                  $tabla.=' <td style="width: 9%; text-align: left"></td>
                                            <td style="width: 9%; text-align: left"><font color="red"></font></td>';
                                }
                                $tabla.='<td style="width: 9%; text-align: left">'.mb_convert_encoding(''.$rowp['prod_producto'].'', 'cp1252', 'UTF-8').'</td>
                                         <td style="width: 9%; text-align: left">'.mb_convert_encoding(''.$rowp['prod_resultado'].'', 'cp1252', 'UTF-8').'</td>';
                              }
                              
                              
                              $tabla.='
                                       <td style="width: 2%; text-align: left">'.mb_convert_encoding(''.$rowp['indi_abreviacion'].'', 'cp1252', 'UTF-8').'</td>
                                       <td style="width: 8%; text-align: left">'.mb_convert_encoding(''.$rowp['prod_indicador'].'', 'cp1252', 'UTF-8').'</td>
                                       <td style="width: 3%; text-align: left">'.$rowp['prod_linea_base'].'</td>
                                       <td style="width: 3%; text-align: left">'.$rowp['prod_meta'].'</td>';
                                       $tabla.=''.$this->temporalizacion_prod($rowp['prod_id'],$this->gestion).'';
                              $tabla .='<td style="width: 8%; text-align: left">'.mb_convert_encoding(''.$rowp['prod_fuente_verificacion'].'', 'cp1252', 'UTF-8').'</td>';         
                            $tabla.='</tr>';
                        }
                        $tabla.='
                        </tbody>
                    </table>'; 
                }
            }
        }

      return $tabla;
    }

     /*--------- TEMPORALIDAD PROGRAMACION FISICA (2019)---------*/
    public function temporalizacion_prod($prod_id,$gestion){
        $prod=$this->model_producto->get_producto_id($prod_id); /// Producto Id
        $programado=$this->model_producto->producto_programado($prod_id,$gestion); /// Producto Programado
        $tp='';
        if($prod[0]['indi_id']==2){$tp='%';};
        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=0;
            $prog[2][$i]=0;
            $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
                $prog[1][$i]=$programado[0][$m[$i]];
/*                $pa=$pa+$prog[1][$i];
                $prog[2][$i]=$pa+$prod[0]['prod_linea_base'];

              if($prod[0]['prod_meta']!=0){
                $prog[3][$i]=round(((($pa+$prod[0]['prod_linea_base'])/$prod[0]['prod_meta'])*100),1);
              } */ 
            } 
        }
        $tr_return = '';
          for($i = 1 ;$i<=12 ;$i++){
            $tr_return .= '<td bgcolor="#d2f5d2" style="width: 3%; text-align: right" title="'.$m[$i].'"><b>'.$prog[1][$i].''.$tp.'</b></td>';
          }
                                 
        return $tr_return;
    }

    public function actividades($prod_id){
       $actividad=$this->model_actividad->list_act_anual($prod_id); /// Actividad
       $tabla='';
       $nro_a=0;
       if(count($actividad)!=0){
            foreach ($actividad as $row){
                $nro_a++;
                $tabla.='<tr class="modo1" bgcolor="#e5f3f1">';
                    $tabla.='<td>'.$nro_a.'</td>';
                    $tabla.='<td></td>';
                    $tabla.='<td>'.$row['act_actividad'].'</td>';
                    $tabla.='<td>'.$row['indi_abreviacion'].'</td>';
                    $tabla.='<td>'.$row['act_indicador'].'</td>';
                    $tabla.='<td>'.round($row['act_linea_base'],2).'</td>';
                    $tabla.='<td>'.round($row['act_meta'],2).'</td>';
                    $tabla.='<td>'.$row['act_ponderacion'].' %</td>';
                    $tabla.='<td>'.$row['act_fuente_verificacion'].'</td>';
                    $tabla.='<td>'.$this->temporalizacion_act($row['act_id'],$this->session->userdata('gestion')).'</td>';
                $tabla.='</tr>';
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
    /*----------------------------------- ACTIVIDADES ----------------------------*/
    public function temporalizacion_act($act_id,$gestion){
        $act=$this->model_actividad->get_actividad_id($act_id); /// programado
        $programado=$this->model_actividad->actividad_programado($act_id,$gestion); /// Actividad Programado

        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=0;
            $prog[2][$i]=0;
            $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
                $prog[1][$i]=$programado[0][$m[$i]];
               /* $pa=$pa+$prog[1][$i];
                $prog[2][$i]=$pa+$act[0]['act_linea_base'];

              if($act[0]['act_meta']!=0){
                $prog[3][$i]=round(((($pa+$act[0]['act_linea_base'])/$act[0]['act_meta'])*100),2);
              }  */
            } 
        }
        
        $tr_return = '';
        $tr_return .= '<table>
                        <thead>
                        <tr>
                              <th style="width:6%;"></th>
                              <th style="width:7%;">Ene.</th>
                              <th style="width:7%;">Feb.</th>
                              <th style="width:7%;">Mar.</th>
                              <th style="width:7%;">Abr.</th>
                              <th style="width:7%;">May.</th>
                              <th style="width:7%;">Jun.</th>
                              <th style="width:7%;">Jul.</th>
                              <th style="width:7%;">Agos.</th>
                              <th style="width:7%;">Sept.</th>
                              <th style="width:7%;">Oct.</th>
                              <th style="width:7%;">Nov.</th>
                              <th style="width:7%;">Dic.</th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr>
                          <td>P.</td>';
                          for($i = 1 ;$i<=12 ;$i++)
                          {
                            $tr_return .= '<td>'.$prog[1][$i].'</td>';
                          }
                          $tr_return .= '
                          </tr>
                        </tbody>
                    </table>';
        return $tr_return;
    }

    function estilo_vertical(){
        $estilo_vertical = '<style>
        .saltopagina{page-break-after:always;}
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
            font-size: 7px;
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
        font-family: "Trebuchet MS", Arial;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 7px;
        font-weight:bold;
       
        background-image: url(fondo_tr01.png);
        background-repeat: repeat-x;
        color: #34484E;
        font-family: "Trebuchet MS", Arial;
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