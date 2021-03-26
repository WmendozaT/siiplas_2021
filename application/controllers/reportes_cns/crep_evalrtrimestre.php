<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evalrtrimestre extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('reporte_eval/model_evalnacional');
            $this->load->model('reporte_eval/model_evalregional');
            $this->load->model('mantenimiento/mapertura_programatica');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /*-------- GET EJECUTADO TRIMESTRE ------------*/
    public function get_ejecutado_trimestre(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tr=($this->tmes*3);
        $mf=0;
          if($this->tmes==1){ $mf = 3; }
          elseif ($this->tmes==2){ $mf = 6; }
          elseif ($this->tmes==3){ $mf = 9; }
          elseif ($this->tmes==4){ $mf = 12; }

        $reg= $this->proyectos_regional($dep_id,$mf);
        $tabla=$this->consolidado_regional_trimestre($reg,$mf);

        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*-------- CONSOLIDADO REGIONAL TRIMESTRE --------*/
    public function consolidado_regional_trimestre($reg,$mf){
        $trimestre=$this->model_evaluacion->trimestre();
        $tr=($this->tmes*3);
        
        if($this->tmes==1){
          $tamanio='style="width:40%;"';
        }
        elseif($this->tmes==2){
          $tamanio='style="width:60%;"';
        }
        elseif($this->tmes==3){
          $tamanio='style="width:80%;"';
        }
        else{
         $tamanio='style="width:100%;"'; 
        }

        $tabla='';
        $tabla.='<label class="label">EJECUCI&Oacute;N ACUMULADA A LA META TRIMESTRAL</label>';
        $tabla.='<table class="table table-bordered" '.$tamanio.'>
          <thead>
            <tr align=center>
                <th></th>';
                for ($i=1; $i <=$mf; $i++) {
                  $tabla.='<th>'.$reg[4][$i].'</th>';
                }
              $tabla.='
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>PROGRAMACI&Oacute;N ACUMULADA</td>';
                for ($i=1; $i <=$mf; $i++) {
                  if($i<=$tr){
                    $tabla.='<td bgcolor="#e9f9f8" title="Trimestre Evaluado"><b>'.$reg[1][$i].' %</b></td>';
                  }
                  else{
                    $tabla.='<td>'.$reg[1][$i].' %</td>';
                  }
                }
              $tabla.='
            </tr>
            <tr>
              <td>EJECUCI&Oacute;N ACUMULADA</td>';
                for ($i=1; $i <=$mf; $i++) {
                  if($i<=$tr){
                    $tabla.='<td bgcolor="#e9f9f8" title="Trimestre Evaluado"><b>'.$reg[2][$i].' %</b></td>';
                  }
                  else{
                    $tabla.='<td>'.$reg[2][$i].' %</td>';
                  }
                }
              $tabla.='
            </tr>
            <tr>
              <td>% EJEC.</td>';
                for ($i=1; $i <=$mf ; $i++) {
                  if($i<=$tr){
                    $tabla.='<td bgcolor="#e9f9f8" title="Trimestre Evaluado"><b>'.$reg[3][$i].' %</b></td>';
                  }
                  else{
                    $tabla.='<td>'.$reg[3][$i].' %</td>';
                  }
                }
              $tabla.='
            </tr>
          </tbody>
        </table><br>
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7" align=left>
          <h1 class="page-title txt-color-blueDark" style="background-color: #4e5050; height: 25px; padding: 4px; border-radius: 5px;"><font color=#fff>CALIFICACI&Oacute;N AL '.$trimestre[0]['trm_descripcion'].' META TRIMESTRAL &nbsp;&nbsp;:&nbsp;&nbsp;  
            <b>'.$reg[3][$tr].'%</b></font>
          </h1>
        </div>';

        return $tabla;
    }

    /*----------- Consolidado Regional cuadro de eficacia -----------------*/
    public function proyectos_regional($dep_id,$mf){
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

      for($i=0; $i <=$mf ; $i++) { 
        $p[1][$i]=0; // Prog. // Cumplidos
        $p[2][$i]=0; // Ejec. // En Proceso
        $p[3][$i]=0; // Efi.  // No cumplido 
        $p[4][$i]=0; // Mes.
      }

      $proyectos=$this->model_evalregional->list_consolidado_regional($dep_id);
      foreach($proyectos  as $rowp){
        $tabla=$this->componentes($rowp['proy_id'],$mf);
        for ($i=1; $i <=$mf ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['proy_pcion_reg'])/100),2);
          if(($p[1][$i]>=100 & $p[1][$i]<=102) || $p[1][$i]>=99.90){
            $p[1][$i]=100;
          }
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['proy_pcion_reg'])/100),2);
          if($p[1][$i]!=0){
            $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
          }
          $p[4][$i]=$m[$i];

        }
      }

      return $p;
    }

    /*----- Calcula Ponderacion Componentes ----*/    
    public function pcion_meta_componente_trimestre($componente,$mf){
      $nro=0; $pcion=0;
      foreach($componente  as $row){
        $nro_metas=$this->pcion_meta_trimestre($row['com_id'],$mf);

        if ($nro_metas!=0) {
          $nro++;
        }
      }

      if($nro!=0){
        $pcion=(100/$nro);
      }
      
      return $pcion;
    }

    /*--------------- Componentes --------------------*/
    public function componentes($proy_id,$mf){
      $proyectos=$this->model_proyecto->get_id_proyecto($proy_id);;
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyectos[0]['tp_id']);

      for($i=1; $i <=$mf ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $pcion=$this->pcion_meta_componente_trimestre($componente,$mf);
      foreach($componente  as $rowc){
        if($rowc['com_ponderacion']!=0){
        //  echo "-- COMPONENTE : ".$rowc['com_id']." : ---".$rowc['com_componente']." -> ".$rowc['com_ponderacion']."%<br>";
          $productos = $this->model_producto->list_prod($rowc['com_id']);
          $pcion_prod=$this->pcion_meta_trimestre($rowc['com_id'],$mf);
          if(count($productos)!=0 & $pcion_prod!=0){
            $tabla=$this->productos($rowc['com_id'],$mf);
            
            for ($i=1; $i <=$mf ; $i++) { 
              $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$pcion)/100),2);
              $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$pcion)/100),2);
            }
          }
        }
      }

      return $p;
    }

    /*----- Calcula Ponderacion de metas trimestrales ----*/    
    public function pcion_meta_trimestre($com_id,$mf){
      $productos = $this->model_producto->list_prod($com_id);
      $nro=0; $pcion=0;
      foreach($productos  as $rowp){
        $tmeta=$this->model_producto->suma_prog_trimestre($rowp['prod_id'],$mf);
        if (count($tmeta)!=0) {
            $nro++;
        }
      }

      if($nro!=0){
        $pcion=(100/$nro);
      }
      
      return $pcion;
    }

    /*------------------------ Productos -------------------------*/
    public function productos($com_id,$mf){
      for($i=1; $i <=$mf ; $i++) { 
        $p[1][$i]=0;$p[2][$i]=0;
      }

      $pcion=$this->pcion_meta_trimestre($com_id,$mf);
      $productos = $this->model_producto->list_prod($com_id);
      foreach($productos  as $rowp){
        if($rowp['prod_ponderacion']!=0){
        //  echo "---------- Productos : ".$rowp['prod_id']." : ".$rowp['prod_producto']." -> ".$rowp['prod_ponderacion']."%<br>";
          $tabla=$this->temporalidad_productos_efi($rowp['prod_id'],$pcion);
          for ($i=1; $i <=$mf ; $i++) { 
            $p[1][$i]=$p[1][$i]+$tabla[1][$i];
            $p[2][$i]=$p[2][$i]+$tabla[2][$i];
          }
        }
      }

    return $p;
    }

    /*---------------Sumatoria Temporalidad Productos anual------------------*/
     public function temporalidad_productos_efi($prod_id,$pcion){
      $producto = $this->model_producto->get_producto_id($prod_id);
      $prod_prog= $this->model_producto->producto_programado($prod_id,$this->gestion);//// Temporalidad Programado
      $prod_ejec= $this->model_producto->producto_ejecutado($prod_id,$this->gestion); //// Temporalidad ejecutado

      $mf=0;
      if($this->tmes==1){ $mf = 3; }
      elseif ($this->tmes==2){ $mf = 6; }
      elseif ($this->tmes==3){ $mf = 9; }
      elseif ($this->tmes==4){ $mf = 12; }

      $tmeta=$this->model_producto->suma_prog_trimestre($prod_id,$mf);

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

      for ($i=1; $i <=$mf ; $i++) { 
        $matriz[1][$i]=0; /// Programado Acumulado %
        $matriz[2][$i]=0; /// Ejecutado Acumulado %
        $matriz[3][$i]=0; /// Eficacia %
      }
      
      if(count($tmeta)!=0){
         $pa=0; $ea=0;$pm=0; $em=0;
          if(count($prod_prog)!=0){
            for ($i=1; $i <=$mf ; $i++) {
              $pa=$pa+$prod_prog[0][$mp[$i]];

              if($tmeta[0]['meta']!=0){
                if($producto[0]['tp_id']==1){
                  $pm=round(((($pa+$producto[0]['prod_linea_base'])/$tmeta[0]['meta'])*100),2); // %pa
                }
                else{
                  $pm=round((($pa/$tmeta[0]['meta'])*100),2); // %pa
                }
              }

              $matriz[1][$i]=round((($pm*$pcion)/100),2); // %
            }
          }

          if(count($prod_ejec)!=0){
            for ($i=1; $i <=12 ; $i++) { 
             // $ea=$ea+$prod_ejec[0][$mp[$i]];
              if($tmeta[0]['meta']!=0){

                $ea=$ea+$prod_ejec[0][$mp[$i]];
                if($producto[0]['tp_id']==1){
                  $em=round(((($ea+$producto[0]['prod_linea_base'])/$tmeta[0]['meta'])*100),2); // %ea
                }
                else{
                  $em=round((($ea/$tmeta[0]['meta'])*100),2); // %ea
                }
                
              }
              $matriz[2][$i]=round((($em*$pcion)/100),2); // %

            }
          }
      }

      return $matriz;
    }

    /*------------Sumatoria Temporalidad Productos ------------*/
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