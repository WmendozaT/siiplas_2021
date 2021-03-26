<?php
class Crep_evalunidadpi extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
        }
        else{
            redirect('/','refresh');
        }
    }

    // Modulo Evaluacion POA - Proyecto de Inversión
    public function evaluacion_unidad_pinversion($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($data['proyecto'])!=0){
        $data['menu']=$this->menu(4); //// genera menu  
        $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        $data['tit_menu']='EVALUACI&oacute;N POA';

        $data['titulo']=
        '<h1><small>PROYECTO : </small><b>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</b></h1>
        <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>';
        $data['tit']='<li>Registro de Evaluaci&oacute;n</li><li>Evaluaci&oacute;n POA</li><li>Proyecto Inversi&oacute;n</li>';

        $data['tabla']=$this->eficacia_proyecto($proy_id);
        $data['tabla_acumulado']=$this->tabla_acumulado($data['tabla'],0);
        $data['calificacion']=$this->calificacion_eficacia($data['tabla'][3][(($this->tmes)*3)]); /// calificacion
        $data['print_tabla']=$this->print_proyecto_inversion($data['proyecto'],$data['tabla'],$data['calificacion']); /// impresion

        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_unidad_pi', $data);
      }
      else{
        redirect('eval/mis_operaciones');
      }
    }



    /*------ Imprime Evaluacion Consolidado Proyecto de Inversion ------*/
    public function print_proyecto_inversion($proyecto,$regresion,$calificacion){
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

    /*-------- Consolidado Proyecto de Inversión ----------*/
    public function eficacia_proyecto($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
        $m[1]='Ene.';
        $m[2]='Feb.';
        $m[3]='Mar.';
        $m[4]='Abr.';
        $m[5]='May.';
        $m[6]='Jun.';
        $m[7]='Jul.';
        $m[8]='Agos.';
        $m[9]='Sept.';
        $m[10]='Oct.';
        $m[11]='Nov.';
        $m[12]='Dic.';

      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0; // Prog. 
        $p[2][$i]=0; // Ejec. 
        $p[3][$i]=0; // Efi.  
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo
      }

      $tab=$this->componentes($proy_id);
        
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$tab[1][$i];
          $p[2][$i]=$tab[2][$i];
          if($tab[1][$i]!=0){
            $p[3][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);
            $p[4][$i]=$m[$i];

            if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
            if ($p[3][$i] > 75 & $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
            if($p[3][$i] > 90 & $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
            if($p[3][$i] > 99 & $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
          }
        }

      return $p;
    }

    /*------ Componentes ------*/
    public function componentes($proy_id){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id);;
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyectos[0]['tp_id']);

      for($i=1; $i <=12 ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      foreach($componente  as $rowc){
        if($rowc['com_ponderacion']!=0){
        //  echo "-- COMPONENTE : ".$rowc['com_id']." : ---".$rowc['com_componente']." -> ".$rowc['com_ponderacion']."%<br>";
          $productos = $this->model_producto->list_prod($rowc['com_id']);
          if(count($productos)!=0){
            $tabla=$this->productos($rowc['com_id'],$proyectos[0]['proy_act']);
            
            for ($i=1; $i <=12 ; $i++) { 
              $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowc['com_ponderacion'])/100),2);
              $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowc['com_ponderacion'])/100),2);
            }
          }
        }
      }

      return $p;
    }

    /*---------- Productos ---------*/
    public function productos($com_id,$act){
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

    /*------- Sumatoria Temporalidad Productos ---------*/
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

    /*
    /*================================= GENERAR MENU ====================================*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
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
    /*--------------------------------------------------------------------------------*/
    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }
    /*======================================================================================*/

}