<?php
class Cevaluacion_pi extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('reporte_eval/model_evalregional');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tp_adm = $this->session->userData('tp_adm');
        }else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

    /*------- TIPO DE RESPONSABLE ----------*/
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



    /*------ EVALUAR PROYECTOS DE INVERSIÓN 2020 ------*/
    public function evaluar_proyectoinversion($com_id){
      $data['componente'] = $this->model_componente->get_componente($com_id); ///// DATOS DEL COMPONENTE
      $data['menu']=$this->menu(4); //// genera menu
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); ////// DATOS DEL PROYECTO
      if(count($data['proyecto'])!=0){
        $titulo=
          '<h1><small>PROYECTO : </small>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</h1>
          <h1><small>COMPONENTE : </small> '.$data['componente'][0]['com_componente'].'</h1>';

        $tmes=$this->model_evaluacion->trimestre();
        $data['productos']='<div class="alert alert-danger alert-block">NO EXISTE TRIMESTRE SELECCIONADO POR EL ADMINISTRADOR NACIONAL, POR FAVOR CONTACTESE CON EL DPTO. NACIONAL DE PLANIFICACI&Oacute;N</div>';
        if(count($tmes)!=0){
          $this->pondera_poa_operaciones($com_id);
          $this->verif_update_tpmeta($com_id);
          $data['verif_eval_ncum']=$this->model_evaluacion->verif_com_eval($com_id,$this->tmes);
          $data['productos']=$this->lista_productos($data['proyecto'][0]['proy_id'],$com_id); /// Lista de Operaciones
          $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        }

        $data['titulo']=$titulo; /// Titulo de la cabecera
        $data['tr']=($this->tmes*3); /// datos del mes

        $data['tabla']=$this->tabla_eficacia_servicio($com_id);
        $data['tabla_acumulado']=$this->tabla_acumulado($data['tabla'],0);
        $data['calificacion']=$this->calificacion_eficacia($data['tabla'][3][(($this->tmes)*3)]); /// calificacion
        $data['print_tabla']=$this->print_proyectos_servicio($data['componente'],$data['tabla'],$data['calificacion']); /// imprimir Grafic

        $this->load->view('admin/evaluacion/operaciones/mis_productos_pi', $data);
      }
      else{
        echo "Error !!! ";
      }
    }


    /*------- Tabla Acumulado --------*/
    public function tabla_acumulado($matriz,$tp_rep){
      /// 0 : normal
      /// 1 : impresion
      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      if($tp_rep==0){ /// Normal
        $tab='class="table table-bordered" align=center style="width:80%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 style="width:100%;"';
      }

      $tabla='';
      $tabla.='
        <table '.$tab.'>
          <thead>
            <tr align=center>
              <td></td>';
              for ($i=1; $i <=12 ; $i++) { 
                $tabla.='<td>'.$matriz[4][$i].'</td>';
              }
          $tabla.='
            </tr>
          </thead>
          <tbody>
            <tr>
            <td>PROGRAMADO</td>';
          for ($i=1; $i <=12 ; $i++) { 
            if($i>=$vi & $i<=$vf){
              $tabla.='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE" align=right><b>'.$matriz[1][$i].'%</b></td>';
            }
            else{
              $tabla.='<td align=right><b>'.$matriz[1][$i].'%</b></td>';
            }
          }
        $tabla.='
            </tr>
            <tr>
            <td>EJECUTADO</td>';
          for ($i=1; $i <=12 ; $i++) { 
            if($i>=$vi & $i<=$vf){
              $tabla.='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE" align=right><b>'.$matriz[2][$i].'%</b></td>';
            }
            else{
              $tabla.='<td align=right><b>'.$matriz[2][$i].'%</b></td>';
            }
          }
        $tabla.='
            </tr>
            <tr>
            <td>EFICACIA</td>';
          for ($i=1; $i <=12 ; $i++) { 
            if($i>=$vi & $i<=$vf){
              $tabla.='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE" align=right><b>'.$matriz[3][$i].'%</b></td>';
            }
            else{
              $tabla.='<td align=right><b>'.$matriz[3][$i].'%</b></td>';
            }
          }
        $tabla.='
            </tr>
          </tbody>
        </table>';  

      return $tabla;
    }

    /*------- Grafico Eficacia Por Servicio --------*/
    public function tabla_eficacia_servicio($com_id){      
        $m[1]='ENE.';
        $m[2]='FEB.';
        $m[3]='MAR.';
        $m[4]='ABR.';
        $m[5]='MAY.';
        $m[6]='JUN.';
        $m[7]='JUL.';
        $m[8]='AGOS.';
        $m[9]='SEPT.';
        $m[10]='OCT.';
        $m[11]='NOV.';
        $m[12]='DIC.';

      for($i=0; $i <=12 ; $i++) { 
        $p[1][$i]=0; // Prog.
        $p[2][$i]=0; // Ejec.
        $p[3][$i]=0; // Efi.
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo
      }

      $tab=$this->acumulado_operaciones($com_id);

      for ($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=$tab[1][$i];
        $p[2][$i]=$tab[2][$i];
        if($p[1][$i]!=0){
          $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
        }
        $p[4][$i]=$m[$i];

        if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
        if($p[3][$i] > 75 && $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
        if($p[3][$i] > 90 && $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
        if($p[3][$i] > 99 && $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
      }
      

      return $p;
    }

    /*---------- Acumulado Operaciones ------------*/
    public function acumulado_operaciones($com_id){
      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $productos = $this->model_producto->list_prod($com_id);
      foreach($productos  as $rowp){
        if($rowp['prod_ponderacion']!=0){
        //  echo "---------- Productos : ".$rowp['prod_id']." : ".$rowp['prod_producto']." -> ".$rowp['prod_ponderacion']."%<br>";
          $tabla=$this->temporalidad_productos($rowp['prod_id']);
          for ($i=1; $i <=12 ; $i++) { 
            $p[1][$i]=$p[1][$i]+$tabla[1][$i];
            $p[2][$i]=$p[2][$i]+$tabla[2][$i];
          }
        }
      }

    return $p;
    }

    /*---------------Sumatoria Temporalidad Productos ------------------*/
     public function temporalidad_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado Acumulado %
        $matriz[2][$i]=0; /// Ejecutado Acumulado %
        $matriz[3][$i]=0; /// Eficacia %
      }
      
      $pa=0; $ea=0;$pm=0; $em=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) {

          if($producto[0]['mt_id']==3){
            $pa=$pa+$prod_prog[0][$mp[$i]];
          }
          else{
            $pa=$producto[0]['prod_meta'];
          }

          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $pm=round(((($pa+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %pa
            }
            else{
              $pm=round((($pa/$producto[0]['prod_meta'])*100),2); // %pa
            }
            
          }

          $matriz[1][$i]=round((($pm*$producto[0]['prod_ponderacion'])/100),2); // %
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
         // $ea=$ea+$prod_ejec[0][$mp[$i]];
          if($producto[0]['prod_meta']!=0){

            if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
            }
            else{
              $ea=$prod_ejec[0][$mp[$i]];
            }

            if($producto[0]['tp_id']==1){
              $em=round(((($ea+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2); // %ea
            }
            else{
              $em=round((($ea/$producto[0]['prod_meta'])*100),2); // %ea
            }
            
          }
          $matriz[2][$i]=round((($em*$producto[0]['prod_ponderacion'])/100),2); // %

        }
      }
      
      return $matriz;
    }


    /*------ Parametro de eficacia ------*/
    public function calificacion_eficacia($eficacia){
      $tabla='';

      if($eficacia<=75){$tp='danger';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> INSATISFACTORIO (0% - 75%)';} /// Insatisfactorio - Rojo
      if ($eficacia > 75 & $eficacia <= 90){$tp='warning';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> REGULAR (75% - 90%)';} /// Regular - Amarillo
      if($eficacia > 90 & $eficacia <= 99){$tp='info';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> BUENO (90% - 99%)';} /// Bueno - Azul
      if($eficacia > 99 & $eficacia <= 102){$tp='success';$titulo='NIVEL DE EFICACIA : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

      $tabla.='<h2 class="alert alert-'.$tp.'" align="center"><b>'.$titulo.'</b></h2>';

      return $tabla;
    }


    /*------- Tabla Ejecucion Presupuestaria --------*/
    public function ejecucion_presupuestaria_acumulado($com_id,$tp_rep){
      $tabla='';

      $monto_total=0;
      $ppto_total=$this->model_evaluacion->suma_ppto_programado_trimestre($com_id);
      if (count($ppto_total)!=0) {
        $monto_total=$ppto_total[0]['total_ppto'];
      }

      $monto_partida=0;
      $suma_partida=$this->model_evaluacion->suma_grupo_partida_programado($com_id,10000);
      if(count($suma_partida)!=0){
        $monto_partida=$suma_partida[0]['suma_partida'];
      }

      $monto_certificado=0;
      $suma_certificado=$this->model_evaluacion->suma_monto_certificado_servicio($com_id);
      if(count($suma_certificado)!=0){
        $monto_certificado=$suma_certificado[0]['ppto_certificado'];
      }

      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:50%;"';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 style="width:100%;"';
      }

      $tabla.='
        <div align=center>
        <table '.$tab.'>
          <thead>
          <tr>
            <th style="width:5%;"></th>';
            for ($i=1; $i <=$this->tmes ; $i++) {
              $trimestre=$this->model_evaluacion->get_trimestre($i);
              $tabla.='<th style="width:10%;">'.$trimestre[0]['trm_descripcion'].'</th>';
            }
          
        $tabla.='
            <th style="width:10%;">TOTAL ITEMS</th>
            <th style="width:10%;">MONTO EJECUTADO</th>
            <th style="width:5%;">% EJECUTADO</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td><b>ITEMS CERTIFICADOS</b></td>';
              $nro_total=0;
              for ($i=1; $i <= $this->tmes; $i++) {
                $nro=0;
                $cert=$this->model_evaluacion->nro_certificaciones_trimestre($com_id,$i);
                if(count($cert)!=0){
                  $nro=$cert[0]['numero_certificaciones'];
                  $nro_total=$nro_total+$nro;
                }
                
                $tabla.='<td align=right><b>'.$nro.'</b></td>';
              }
          $tabla.='
              <td align=right bgcolor="#d9f9f5"><b>'.$nro_total.'</b></td>
              <td align=right bgcolor="#d9f9f5"><b>'.number_format(($monto_partida+$monto_certificado), 2, ',', '.').'</b></td>
              <td align=right bgcolor="#d9f9f5"><b>'.(round(((($monto_partida+$monto_certificado)/$monto_total)*100),2)).' %</b></td>
            </tr>
          </tbody>
        </table>
        </div>';
      return $tabla;
    }

    /*---------------- Imprime Evaluacion Consolidado Servicio --------------*/
    public function print_proyectos_servicio($componente,$regresion,$calificacion){
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
      $tr=($this->tmes*3);
      $tabla_acumulado=$this->tabla_acumulado($regresion,1);
      //$dist=$this->model_evalregional->get_dist($dist_id);
      ?>
      <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
      <title><?php echo $this->session->userData('sistema');?></title>
      </head>
      <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        @page {size: letter;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
      </style>
      <?php
      $tabla ='';
      $tabla .='
              <div class="verde"></div>
              <div class="blanco"></div>
              <table class="page_header" border="0" style="width: 100%;>
              <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%;" text-align:center;>
                            <br><img src="'.base_url().'assets/ifinal/cns_logo.JPG'.'" alt="" style="width:50%;">
                          </td>
                          <td style="width:70%;" align=left>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>DIR. ADM.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>UNI. EJEC.</b></td>
                                <td style="width:80%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                              <td style="height: 2.5%"><b>PROY. INV.</b></td>
                              <td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>
                              </tr>
                              <tr style="font-size: 8pt;">
                                <td style="width:20%; height: 2.5%"><b>COMPONENTE</b></td>
                                <td style="width:80%;">: '.strtoupper($componente[0]['com_componente']).'</td>
                              </tr>
                          </table>
                         
                          </td>
                          <td style="width:15%; font-size: 4.5pt;" align=left >
                            &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                            &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><hr>
        '.$calificacion.'
          <table class="change_order_items" border=1 style="width:100%;">
            <tr>
              <td>
                <div id="regresion_lineal_pi_print" style="width: 550px; height: 350px; margin: 0 auto"></div>
              </td>
            </tr>
            <tr>
              <td>
                '.$tabla_acumulado.'
              </td>
            </tr>
            </table>';
        ?>
        </html>
      <?php
    return $tabla;
    }  
      

    /*----- VERIFICA-ACTUALIZA TIPO DE META ------*/
    function verif_update_tpmeta($com_id){
      $productos=$this->model_producto->list_prod($com_id);
      foreach($productos as $row){
        $suma=$this->model_producto->suma_programado_producto($row['prod_id'],$this->gestion);
        
        if(count($suma)!=0){
          if($suma[0]['prog']==1200 & $row['indi_id']==2){
            $update_prod = array(
            'mt_id' => 1
            );
            $this->db->where('prod_id', $row['prod_id']);
            $this->db->update('_productos', $update_prod);
          }
        }
        
      }
    }

    /*----- PONDERACION OPERACIONES ------*/
    function pondera_poa_operaciones($com_id){
      $productos=$this->model_producto->list_prod($com_id);
      $pcion=0;
      if(count($productos)!=0){
        $pcion=(100/count($productos));
        foreach($productos as $row){
          $update_prod = array(
            'prod_ponderacion' => $pcion
          );
          $this->db->where('prod_id', $row['prod_id']);
          $this->db->update('_productos', $update_prod);
        }
      }
    }


    /*------- LISTA DE  OPERACIONES 2020 ------*/
    function lista_productos($proy_id,$com_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); ////// DATOS DEL PROYECTO
      $productos=$this->model_producto->list_prod($com_id); /// lISTA DE ACTIVIDADES
      $tabla='';

      $vi=0; $vf=0;
      if($this->tmes==1){ $vi = 1;$vf = 3; }
      elseif ($this->tmes==2){ $vi = 4;$vf = 6; }
      elseif ($this->tmes==3){ $vi = 7;$vf = 9; }
      elseif ($this->tmes==4){ $vi = 10;$vf = 12; }

      $vfinal=0;
      if($this->tmes==1){$vfinal=3;}
      elseif ($this->tmes==2) {$vfinal=6;}
      elseif ($this->tmes==3) {$vfinal=9;}
      elseif ($this->tmes==4) {$vfinal=12;}

      $tabla.=' <div class="table-responsive">
                  <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>                             
                        <tr>
                          <th style="width:5%;"></th>
                          <th style="width:2%;"><b>COD. OR.</b></th>
                          <th style="width:2%;"><b>COD. ACT.</b></th>
                          <th style="width:15%;">ACTIVIDAD</th>
                          <th style="width:5%;">TIPO DE INDICADOR</th>
                          <th style="width:5%;">INDICADOR</th>
                          <th style="width:5%;">LINEA BASE</th>
                          <th style="width:5%;">META</th>
                          <th style="width:10%;">VERIFICACI&Oacute;N</th>
                          <th style="width:5%;">%</th>
                          <th style="width:5%;">PROG. TRIMESTRAL</th>
                          <th style="width:5%;">EJEC. TRIMESTRAL</th>
                          <th style="width:10%;">EVALUACI&Oacute;N</th>
                          <th style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</th>
                          <th style="width:10%;">PROBLEMAS PRESENTADOS</th>
                          <th style="width:10%;">ACCIONES REALIZADAS</th>
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                          <th style="width:1%;"></th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0; $pcion=0;
                      foreach($productos as $rowp){
                        $trimestre_prog = $this->model_evaluacion->programado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Programado
                        $trimestre_ejec = $this->model_evaluacion->ejecutado_trimestral_productos($this->tmes,$rowp['prod_id']); /// Trimestre Ejecutado

                        $trimestre=$this->model_evaluacion->get_trimestral_prod($rowp['prod_id'],$this->gestion,$this->tmes);
                        $prog_actual=0;  $pcion=$pcion+$rowp['prod_ponderacion'];
                        
                        if(count($trimestre_prog)!=0){
                          $prog_actual=$trimestre_prog[0]['trimestre'];
                        }
                        $ejec_actual=0; 
                        if(count($trimestre_ejec)!=0){
                          $ejec_actual=$trimestre_ejec[0]['trimestre'];
                        }

                        $prog=$this->model_evaluacion->rango_programado_trimestral_productos($rowp['prod_id'],$vfinal);
                        $eval=$this->model_evaluacion->rango_ejecutado_trimestral_productos($rowp['prod_id'],$vfinal);

                        $acu_prog=0;
                        $acu_ejec=0;
                        if(count($prog)!=0){
                          $acu_prog=$prog[0]['trimestre'];
                        }
                        if(count($eval)!=0){
                          $acu_ejec=$eval[0]['trimestre'];
                        }

                        $bg_color=''; $btn='';
                        if($rowp['prod_priori']==1){
                          $bg_color='#d9f3fd';
                          $btn='<button class="btn btn-primary btn-xs">Prioridad</button>';
                        }

                        //$tabla.=''.$nro.'.- '.$acu_prog.'-'.$acu_ejec.'----'.$rowp['prod_id'].' -> '.$rowp['prod_producto'].'<br>';
                        if(($prog_actual!=0 || $ejec_actual!=0) || ($prog_actual!=0 || ($acu_prog-$acu_ejec)!=0)){
                          $nro++;
                          $tabla .='<tr bgcolor='.$bg_color.'>';
                          $tabla .='<td align=center title='.$rowp['prod_id'].'>';
                            $tabla .=$nro.'<br>'.$btn.'<br>';
                            if($this->tp_adm==1){
                              $tabla.='<a href="'.site_url("").'/eval/reformular/'.$rowp['prod_id'].'" title="REFORMULAR EVALUACION" class="btn btn-default" target="_blank"><img src="'.base_url().'assets/img/ifinal/nodoc.png" WIDTH="30" HEIGHT="30"/></a>';
                            }
                          $tabla .='</td>';
                          $tabla.='<td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
                          $tabla.='<td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
                          $tabla .='<td>'.$rowp['prod_producto'].'</td>';
                          $tabla .='<td>'.$rowp['indi_descripcion'].'</td>';
                          $tabla .='<td>'.$rowp['prod_indicador'].'</td>';
                          $tabla .='<td>'.$rowp['prod_linea_base'].'</td>';
                          $tabla .='<td>'.$rowp['prod_meta'].'</td>';
                          $tabla .='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
                          $tabla .='<td>'.$rowp['prod_ponderacion'].'%</td>';
                          $tabla .='<td>'.$prog_actual.'</td>';
                          $tabla .='<td bgcolor="#daf3da">'.$ejec_actual.'</td>';
                          
                          if(count($trimestre)!=0){
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tpeval_descripcion'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tmed_verif'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tprob'].'</td>';
                            $tabla .='<td bgcolor="#e9f7e9">'.$trimestre[0]['tacciones'].'</td>';
                            $tabla .='<td align=center>';
                            $tabla.=$this->verif_btn_eval($this->fun_id,1,$rowp['prod_id'],$proy_id);
                           //   $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. ACT.</a>';
                              /*if($this->tp_adm==1 || $this->fun_id==721 || $this->fun_id==598 || $this->fun_id==689 || $this->fun_id==690 || $this->fun_id==719 || $this->fun_id==460){
                                $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. PROD.</a>';
                              }*/
                            $tabla .='</td>'; 
                          }
                          else{
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td bgcolor="#e9f7e9"></td>';
                            $tabla .='<td align="center">';
                             $tabla.=$this->verif_btn_eval($this->fun_id,0,$rowp['prod_id'],$proy_id);
                             // $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. ACT.</a>';
                              
                              /*if($this->tp_adm==1 || $this->fun_id==721 || $this->fun_id==598 || $this->fun_id==689 || $this->fun_id==690 || $this->fun_id==719 || $this->fun_id==460){
                                $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$rowp['prod_id'].'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. PROD.</a>';
                              }*/
                            $tabla .='</td>';
                          }
                          $temp=$this->temporalizacion_productos($rowp['prod_id']);
                          $tabla .='<td>
                          <center><a data-toggle="modal" data-target="#'.$rowp['prod_id'].'" title="TEMPORALIDAD PROGRAMADO-EJECUTADO" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/grafico4.png" WIDTH="38" HEIGHT="38"/></a></center>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="'.$rowp['prod_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_update">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                    <h4 class="modal-title">
                                        <b>ACTIVIDAD</b> : '.$rowp['prod_producto'].'
                                    </h4>
                                    <font color=blue><b>INDICADOR : '.$rowp['mt_tipo'].'</b></font>
                                  </div>
                                  <div class="modal-body no-padding">
                                    <div class="well">
                                      <div class="table-responsive">
                                      <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                          <th bgcolor="#1c7368"><font color="#fff">P/E</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">ENE.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">FEB.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">MAR.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">ABR.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">MAY.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">JUN.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">JUL.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">AGOS.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">SEPT.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">OCT.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">NOV.</font></th>
                                          <th bgcolor="#1c7368"><font color="#fff">DIC.</font></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                          <td title="PROGRAMADO">P.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[1][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[1][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="PROGRAMADO ACUMULADO">PA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[2][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[2][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="%PROGRAMADO ACUMULADO">%PA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[3][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[3][$i].'%</td>';
                                            } 
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="EJECUTADO">E.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[4][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[4][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="EJECUTADO ACUMULADO">EA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[5][$i].'</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[5][$i].'</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr>
                                          <td title="%EJECUTADO ACUMULADO">%EA.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[6][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[6][$i].'%</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        <tr bgcolor="#daf3da">
                                          <td title="EFICACIA">EFI.</td>';
                                          for ($i=1; $i <=12 ; $i++) {
                                            if($i>=$vi & $i<=$vf){
                                              $tabla .='<td bgcolor="#daf3ef" title="TRIMESTRE VIGENTE">'.$temp[7][$i].'%</td>';
                                            }
                                            else{
                                              $tabla .='<td>'.$temp[7][$i].'%</td>';
                                            }
                                          }
                                          $tabla .='
                                        </tr>
                                        </tbody>
                                      </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td>

                            <center><a data-toggle="modal" data-target="#p'.$rowp['prod_id'].'" title="HISTORIAL EVALUACIONES PRODUCTOS " class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/history.png" WIDTH="35" HEIGHT="35"/></a></center>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" id="p'.$rowp['prod_id'].'"  role="dialog" aria-labelledby="myLargeModalLabel">
                              <div class="modal-dialog modal-lg" id="mdialTamanio">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
                                      &times;
                                    </button>
                                    <h4 class="modal-title">
                                        <b>ACTIVIDAD : </b>'.$rowp['prod_cod'].'.- '.$rowp['prod_producto'].'
                                    </h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="row">
                                        '.$this->historial_operaciones($rowp['prod_id'],1).'
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </td>';
                          $tabla.='<td>';
                            //$tabla.='<a href="#" data-toggle="modal" data-target="#modal_update" class="btn btn-default btn-lg" name="'.$rowp['prod_id'].'" title="ARCHIVOS ADJUNTOS A LA OPERACI&Oacute;N"><img src="'.base_url().'assets/ifinal/update.png" WIDTH="35" HEIGHT="35"/></a>';
                          $tabla.='</td>';
                        $tabla .='</tr>';
                        }
                      }
                      $tabla.='
                      </tbody>
                      <tr>
                        <td colspan=9></td>
                        <td>'.round($pcion,0).'%</td>
                        <td colspan=9></td>
                      </tr>
                  </table>
                </div>';

      return $tabla;
    }


    /*-------- Verif Boton Evaluacion ---------*/
    function verif_btn_eval($fun_id,$tp,$prod_id,$proy_id){
      $tabla='';
      $dia_actual=ltrim(date("d"), "0");
      $mes_actual=ltrim(date("m"), "0");

      $fecha_actual = date('Y-m-d');

      $get_fecha_evaluacion=$this->model_configuracion->get_datos_fecha_evaluacion($this->gestion);
      if(count($get_fecha_evaluacion)!=0){
          $configuracion=$this->model_configuracion->get_configuracion_session();
          $date_actual = strtotime($fecha_actual); //// fecha Actual
          $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
          $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

          if (($date_actual >= $date_inicio) && ($date_actual <= $date_final) || $this->tp_adm==1){
            if(count($this->model_configuracion->get_responsables_evaluacion($this->fun_id))!=0 || $this->tp_adm==1){
                if($tp==0){ /// Evaluar Actividad
                  $tabla .='<a href="#" data-toggle="modal" data-target="#modal_add_ff" class="btn btn-xs add_ff" title="EVALUAR PRODUCTO ABSOLUTO" name="'.$prod_id.'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/eval.jpg" WIDTH="45" HEIGHT="45"/><br>EV. ACT.</a>';
                }
                else{ /// Modificar Evaluacion Actividad
                  $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff" title="MODIFICAR EVALUACI&Oacute;N PRODUCTO" name="'.$prod_id.'" id="'.$proy_id.'" class="btn btn-default btn-lg"><img src="'.base_url().'assets/ifinal/evalok.jpg" WIDTH="45" HEIGHT="45"/><br>MOD.EV. ACT.</a>';
                }
            }
          }
      }

      return $tabla;
    }


    /*-------- Historial Productos Evaluados ---------*/
    function historial_operaciones($prod_id,$tp){
      $tabla ='';
    //  $tabla .=''.$prod_id.'<br>';
      $temp=$this->temporalizacion_productos($prod_id);
      
      for ($i=1; $i <=4 ; $i++) {
        $ev=$this->model_evaluacion->get_trimestral_prod($prod_id,$this->gestion,$i);
        
        $tmes=$this->model_evaluacion->get_trimestre($i);
        $tabla .=' <div class="col-sm-3">
                    <div class="well">';
                      if (count($ev)!=0) {
                        $tabla .='<div class="alert alert-success" align="center">'.$tmes[0]['trm_descripcion'].' EVALUADO </div>';
                      }
                      else{
                        $tabla .='<div class="alert alert-danger" align="center">'.$tmes[0]['trm_descripcion'].' NO EVALUADO </div>';
                      }
                      if($i==1){
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">ENE.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">FEB.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">MAR.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][1].'</td>
                              <td>'.$temp[1][2].'</td>
                              <td>'.$temp[1][3].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][1].'</td>
                              <td>'.$temp[4][2].'</td>
                              <td>'.$temp[4][3].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }
                      elseif ($i==2) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">ABR.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">MAY.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">JUN.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][4].'</td>
                              <td>'.$temp[1][5].'</td>
                              <td>'.$temp[1][6].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][4].'</td>
                              <td>'.$temp[4][5].'</td>
                              <td>'.$temp[4][6].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }
                      elseif ($i==3) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">JUL.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">AGO.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">SEP.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][7].'</td>
                              <td>'.$temp[1][8].'</td>
                              <td>'.$temp[1][9].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][7].'</td>
                              <td>'.$temp[4][8].'</td>
                              <td>'.$temp[4][9].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }
                      elseif ($i==4) {
                        $tabla.='
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th bgcolor="#1c7368"></th>
                              <th bgcolor="#1c7368"><font color="#fff">OCT.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">NOV.</font></th>
                              <th bgcolor="#1c7368"><font color="#fff">DIC.</font></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th>PROG.</th>
                              <td>'.$temp[1][10].'</td>
                              <td>'.$temp[1][11].'</td>
                              <td>'.$temp[1][12].'</td>
                            </tr>
                            <tr>
                              <th>EVAL.</th>
                              <td>'.$temp[4][10].'</td>
                              <td>'.$temp[4][11].'</td>
                              <td>'.$temp[4][12].'</td>
                            </tr>
                          </tbody>
                        </table><hr>';
                      }

                      if (count($ev)!=0) {
                        if($ev[0]['tp_eval']==1){
                          $tabla.='<font color="#8dbd76"><b>EVALUACI&Oacute;N : CUMPLIDO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                      <td>'.$ev[0]['tmed_verif'].'</td>
                                    </tr>
                                    </table>';
                        }
                        elseif ($ev[0]['tp_eval']==2) {
                          $tabla.='<font color="#ece5a2"><b>EVALUACI&Oacute;N : EN PROCESO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>MEDIO DE VERIFICACI&Oacute;N</b></td>
                                      <td>'.$ev[0]['tmed_verif'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>PROBLEMAS PRESENTADOS</b></td>
                                      <td>'.$ev[0]['tprob'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>ACCIONES REALIZADAS</b></td>
                                      <td>'.$ev[0]['tacciones'].'</td>
                                    </tr>
                                    </table>';
                        }
                        elseif ($ev[0]['tp_eval']==3) {
                          $tabla.='<font color="#d24d4d"><b>EVALUACI&Oacute;N : NO CUMPLIDO</b></font>';
                          $tabla .='<table class="table table-bordered" border="1">
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>PROBLEMAS PRESENTADOS</b></td>
                                      <td>'.$ev[0]['tprob'].'</td>
                                    </tr>
                                    <tr bgcolor="#d6d5d5">
                                      <td><b>ACCIONES REALIZADAS</b></td>
                                      <td>'.$ev[0]['tacciones'].'</td>
                                    </tr>
                                    </table>';
                        }
                        
                      }
                    $tabla .='
                    
                    </div>
                  </div>';
              
      }
      return $tabla;        
    }



   


    /*--- TEMPORALIZACION DE PRODUCTOS (nose esta tomando encuenta lb) ---*/
    public function temporalizacion_productos($prod_id){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mp[1]='enero';
      $mp[2]='febrero';
      $mp[3]='marzo';
      $mp[4]='abril';
      $mp[5]='mayo';
      $mp[6]='junio';
      $mp[7]='julio';
      $mp[8]='agosto';
      $mp[9]='septiembre';
      $mp[10]='octubre';
      $mp[11]='noviembre';
      $mp[12]='diciembre';

      for ($i=1; $i <=12 ; $i++) { 
        $matriz[1][$i]=0; /// Programado
        $matriz[2][$i]=0; /// Programado Acumulado
        $matriz[3][$i]=0; /// Programado Acumulado %
        $matriz[4][$i]=0; /// Ejecutado
        $matriz[5][$i]=0; /// Ejecutado Acumulado
        $matriz[6][$i]=0; /// Ejecutado Acumulado %
        $matriz[7][$i]=0; /// Eficacia
      }
      
      $pa=0; $ea=0;
      if(count($prod_prog)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[1][$i]=$prod_prog[0][$mp[$i]];
          $pa=$pa+$prod_prog[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $matriz[2][$i]=$pa;
          }
          else{
            $matriz[2][$i]=$matriz[1][$i];
          }

          
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[3][$i]=round(((($matriz[2][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[3][$i]=round((($matriz[2][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }
        }
      }

      if(count($prod_ejec)!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $matriz[4][$i]=$prod_ejec[0][$mp[$i]];

          if($producto[0]['mt_id']==3){
            $ea=$ea+$prod_ejec[0][$mp[$i]];
          }
          else{
            $ea=$matriz[4][$i];
          }

          $matriz[5][$i]=$ea;
          if($producto[0]['prod_meta']!=0){
            if($producto[0]['tp_id']==1){
              $matriz[6][$i]=round(((($matriz[5][$i]+$producto[0]['prod_linea_base'])/$producto[0]['prod_meta'])*100),2);
            }
            else{
              $matriz[6][$i]=round((($matriz[5][$i]/$producto[0]['prod_meta'])*100),2);
            }
            
          }

          if($matriz[2][$i]!=0){
            $matriz[7][$i]=round((($matriz[5][$i]/$matriz[2][$i])*100),2);  
          }
          
        }
      }
      
      return $matriz;
    }





    /*------------------------------------- MENU -----------------------------------*/
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
    /*============================================================================*/
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