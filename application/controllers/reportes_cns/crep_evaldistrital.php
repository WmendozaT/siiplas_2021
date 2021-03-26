<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_evaldistrital extends CI_Controller {  
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

    /*----------- MENU DISTRITAL --------------*/
    public function menu_distrital($dist_id){
      $data['menu']=$this->menu(7); //// genera menu
      $data['dist']=$this->model_evalregional->get_dist($dist_id);
      $data['trimestre']=$this->model_evaluacion->trimestre();
      if(count($data['dist'])!=0){
        
        $data['tabla']=$this->proyectos_distrital($data['dist'][0]['dep_id'],$dist_id); /// Eficacia Institucional
        $data['print_tabla']=$this->print_proyectos_distrital($dist_id,$data['tabla']); /// print Eficacia Institucional
      
        $data['distrital']=$this->tabla_programas_distrital($dist_id); /// Evaluacion por Programas
        $data['eval_distrital']=$this->get_print_cuadro_eval_distrital($dist_id); /// print Evaluacion distrital/Acumulado
        
        $data['list_acciones']=$this->list_distrital($dist_id,1); //// Lista de Opreaciones-Proyectos de Inversion
        $data['pie']=$this->pie_distrital($dist_id); //// Parametros de eficacia

         $puntaje=$data['tabla'][3][$this->tmes*3];
        $color='';
        if($puntaje<=75){$color='#f95b4f';} /// Insatisfactorio
        if ($puntaje > 75 & $puntaje <= 90){$color='#c79121';} /// Regular
        if($puntaje > 90 & $puntaje <= 99){$color='#57889c';} /// Bueno
        if($puntaje > 99 & $puntaje <= 102){$color='#6d966d';} /// Optimo

        $data['color']=$color;

        /*$data['nro']=$this->nro_list_distrital($dist_id);
        $data['eficacia']=$this->eficacia_distrital($dist_id,1); //// Grafico de Parametros de eficacia
        $data['print_eficacia']=$this->print_eficacia_distrital($data['nro'],$this->eficacia_distrital($dist_id,2),$dist_id); //// Grafico de Parametros de eficacia print*/
        
        $data['tr']=($this->tmes*3);

        $this->load->view('admin/reportes_cns/eval_distrital/eval_consolidado_distrital', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*----------------------- Consolidado Distrital -----------------------------*/
    public function proyectos_distrital($dep_id,$dist_id){
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
        $p[1][$i]=0; // Prog. // Cumplidos
        $p[2][$i]=0; // Ejec. // En Proceso
        $p[3][$i]=0; // Efi.  // No cumplido 
        $p[4][$i]=0; // Mes.
        $p[5][$i]=0; // Insatisfactorio
        $p[6][$i]=0; // Regular
        $p[7][$i]=0; // Bueno
        $p[8][$i]=0; // Optimo
      }

      $proyectos=$this->model_evalregional->list_consolidado_distrital($dep_id,$dist_id);
      foreach($proyectos  as $rowp){
        $tabla=$this->componentes($rowp['proy_id']);
        for ($i=1; $i <=12 ; $i++) { 
          $p[1][$i]=$p[1][$i]+round((($tabla[1][$i]*$rowp['proy_pcion_reg'])/100),2);
          if($p[1][$i]>=100 & $p[1][$i]<=102){
            $p[1][$i]=100;
          }
          $p[2][$i]=$p[2][$i]+round((($tabla[2][$i]*$rowp['proy_pcion_reg'])/100),2);
          if($p[1][$i]!=0){
            $p[3][$i]=round((($p[2][$i]/$p[1][$i])*100),2);
          }
          $p[4][$i]=$m[$i];

          if($p[3][$i]<=75){$p[5][$i] = $p[3][$i];}else{$p[5][$i] = 0;} /// Insatisfactorio
          if ($p[3][$i] > 75 & $p[3][$i] <= 90) {$p[6][$i] = $p[3][$i];}else{$p[6][$i] = 0;} /// Regular
          if($p[3][$i] > 90 & $p[3][$i] <= 99){$p[7][$i] = $p[3][$i];}else{$p[7][$i] = 0;} /// Bueno
          if($p[3][$i] > 99 & $p[3][$i] <= 102){$p[8][$i] = $p[3][$i];}else{$p[8][$i] = 0;} /// Optimo
        }
      }
      return $p;
    }

    /*-------------- Total programado - evaluado  programas a nivel distrital------------------*/
    public function matriz_programas_distrital($dist_id,$aper_programa,$tp_eval){
      for ($i=1; $i <=6 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        if($tp_eval==1){
          /*------ Trimestral Productos -----------*/
          $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$this->tmes); // cumplido prod
          if(count($cum)!=0){
            $nro_cum=$cum[0]['total'];
          }
          $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$this->tmes); // en proceso prod
          if(count($proc)!=0){
            $nro_proc=$proc[0]['total'];
          }
          $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$this->tmes); // no cumplido prod
          if (count($ncum)!=0) {
            $nro_ncum=$ncum[0]['total'];
          }
          $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$this->tmes); // total programado prod
          if(count($total_prog)!=0){
            $nro_total_prog=$total_prog[0]['total'];
          }
          /*------------------------------------------*/
        }
        else{
          for ($i=1; $i <=$this->tmes ; $i++) { 
            /*------ Trimestral Productos Acumulado -----------*/
            $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$i); // cumplido prod
            if(count($cum)!=0){
              $nro_cum=$nro_cum+$cum[0]['total'];
            }

            $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$i); // en proceso prod
            if(count($proc)!=0){
              $nro_proc=$nro_proc+$proc[0]['total'];
            }

            $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$i); // no cumplido prod
            if(count($ncum)!=0){
              $nro_ncum=$nro_ncum+$ncum[0]['total'];
            }

            $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$i); // total programado prod
            if(count($total_prog)!=0){
              $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
            }
            /*--------------------------------------*/
          }

        }
        
        /*--Prod */
        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
        
        $cat[1]=$nro_1; // cumplidos
        $cat[2]=$nro_2; // en proceso
        $cat[3]=$nro_3; // no cumplido
        $cat[4]=$total; // Total Evaluacion
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    public function matriz_programas_distrital2($dist_id,$aper_programa,$tp_eval){
      for ($i=1; $i <=6 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        if($tp_eval==1){
          /*------ Trimestral Productos -----------*/
          $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$this->tmes); // cumplido prod
          if(count($cum)!=0){
            $nro_cum=$cum[0]['total'];
          }
          $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$this->tmes); // en proceso prod
          if(count($proc)!=0){
            $nro_proc=$proc[0]['total'];
          }
          $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$this->tmes); // no cumplido prod
          if (count($ncum)!=0) {
            $nro_ncum=$ncum[0]['total'];
          }
          $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$this->tmes); // total programado prod
          if(count($total_prog)!=0){
            $nro_total_prog=$total_prog[0]['total'];
          }
          /*------------------------------------------*/
        }
        else{
          for ($i=1; $i <=$this->tmes ; $i++) { 
            /*------ Trimestral Productos Acumulado -----------*/
            $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$i); // cumplido prod
            if(count($cum)!=0){
              $nro_cum=$nro_cum+$cum[0]['total'];
            }

            $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$i); // en proceso prod
            if(count($proc)!=0){
              $nro_proc=$nro_proc+$proc[0]['total'];
            }

            $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$i); // no cumplido prod
            if(count($ncum)!=0){
              $nro_ncum=$nro_ncum+$ncum[0]['total'];
            }

            $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$i); // total programado prod
            if(count($total_prog)!=0){
              $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
            }
            /*--------------------------------------*/
          }

        }
        
        /*--Prod */
        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
        
        $cat[1]=$nro_1; // cumplidos
        $cat[2]=$nro_2; // en proceso
        $cat[3]=$nro_3; // no cumplido
        $cat[4]=$total; // Total Evaluacion
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACTUAL --------------*/
    public function matriz_evaluacion_trimestre($dist_id,$aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        
        $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$this->tmes);
        $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$this->tmes);
        $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$this->tmes);
        $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$this->tmes); // total programado prod
        /*------------- Acumulado Producto -------*/
        if(count($cum)!=0){
          $nro_cum=$nro_cum+$cum[0]['total'];
        }
        if(count($proc)!=0){
          $nro_proc=$nro_proc+$proc[0]['total'];
        }
        if(count($ncum)!=0){
          $nro_ncum=$nro_ncum+$ncum[0]['total'];
        }
        if(count($total_prog)!=0){
          $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
        }

        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;

        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
         // $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }

      $cat[1]=$nro_1; // cumplidos
      $cat[2]=$nro_2; // en proceso
      $cat[3]=$nro_3; // no cumplido
      $cat[4]=$total; // Total Evaluacion
      $cat[5]=$pcion; // % cumplido
      $cat[6]=$npcion; // % no cumplido
      $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*---------------- MATRIZ DE EVALUACION TRIMESTRAL ACUMULADO --------------*/
    public function matriz_evaluacion_Acumulado($dist_id,$aper_programa){
      $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,1,$i);
        $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,2,$i);
        $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$aper_programa,3,$i);
        $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$aper_programa,$i); // total programado prod

        /*------------- Acumulado Producto -------*/
        if(count($cum)!=0){
          $nro_cum=$nro_cum+$cum[0]['total'];
        }
        if(count($proc)!=0){
          $nro_proc=$nro_proc+$proc[0]['total'];
        }
        if(count($ncum)!=0){
          $nro_ncum=$nro_ncum+$ncum[0]['total'];
        }
        if(count($total_prog)!=0){
          $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
        }

        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
      }

      $cat[1]=$nro_1; // cumplidos
      $cat[2]=$nro_2; // en proceso
      $cat[3]=$nro_3; // no cumplido
      $cat[4]=$total; // Total Evaluacion
      $cat[5]=$pcion; // % cumplido
      $cat[6]=$npcion; // % no cumplido
      $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    /*----------------------- DE PRUEBA PARA SUMAR EVALUACIONES ---------------*/
    public function tabla_programas_distrital2($dist_id){
      $cat_prog = $this->model_evalregional->categorias_programaticas_distrital($dist_id); /// trimestral
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }

      $tabla =''; $nro=0;
      foreach($cat_prog  as $row){
       $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
      $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
      
      for ($i=1; $i <=$this->tmes ; $i++) { 
        
        $cum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$row['aper_programa'],1,$i);
        $proc=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$row['aper_programa'],2,$i);
        $ncum=$this->model_evalregional->evaluacion_programas_distrital($dist_id,$row['aper_programa'],3,$i);
        $total_prog=$this->model_evalregional->total_programado_programas_distrital($dist_id,$row['aper_programa'],$i); // total programado prod


        echo "----------------------------------------------------------<br>";
        echo "PROGRAMA : ".$row['aper_programa']." -> TRIMESTRE : ".$i."<br>";
        /*------------- Acumulado Producto -------*/
        if(count($cum)!=0){
          $nro_cum=$nro_cum+$cum[0]['total'];
          echo "CUMPLIDOS  PROD : ".$cum[0]['total']."<br>";
        }
        if(count($proc)!=0){
          $nro_proc=$nro_proc+$proc[0]['total'];
          echo "PROCESO  PROD : ".$proc[0]['total']."<br>";
        }
        if(count($ncum)!=0){
          $nro_ncum=$nro_ncum+$ncum[0]['total'];
          echo "NO CUMPLIDOS  PROD : ".$ncum[0]['total']."<br>";
        }
        if(count($total_prog)!=0){
          $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
          echo "TOTAL  PROD : ".$total_prog[0]['total']."<br>";
        }



        /*$nro_1=$nro_cum+$nro_cum_a;
        $nro_2=$nro_proc+$nro_proc_a;
        $nro_3=$nro_ncum+$nro_ncum_a;
        $total_programado=$nro_total_prog+$nro_total_prog_a;


        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }

        $cat[1]=$nro_1; // cumplidos
        $cat[2]=$nro_2; // en proceso
        $cat[3]=$nro_3; // no cumplido
        $cat[4]=$total; // Total Evaluacion
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

        echo "-------------------------<br>
        PROGRAMA : ".$row['aper_programa']." -> TRIMESTRE : ".$i."<br>";

        echo "CUMPLIDOS : ".$cat[1]." - EN PROCESO : ".$cat[2]." - NO CUMPLIDOS : ".$cat[3]." - TOTAL PROG. ".$cat[7]." - TOTAL EVAL.".$cat[4]."<br>";
        echo "----------------------------<br>";*/

      }


      }

      return $tabla;
    }

      ///------- EVALUACION POR PROGRAMAS ---------------
     public function tabla_programas_distrital($dist_id){
      $cat_prog = $this->model_evalregional->categorias_programaticas_distrital($dist_id); /// trimestral
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }

      $tabla =''; $nro=0;
      foreach($cat_prog  as $row){
        if($row['aper_programa']!='97' & $row['aper_programa']!='98'){

        $eval=$this->matriz_evaluacion_trimestre($dist_id,$row['aper_programa']); /// Evaluacion Trimestral
        $eval_acu=$this->matriz_evaluacion_Acumulado($dist_id,$row['aper_programa']); /// Evalucion Trimestral Acumulado

        $graf_c=round((($eval[1]/$eval[7]*100)),2); // Cumplido 
        $graf_av=round((($eval[2]/$eval[7]*100)),2); // Avance 
        $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido

        $graf_c_a=round((($eval_acu[1]/$eval[7]*100)),2); // Cumplido Acumulado
        $graf_av_a=round((($eval[2]/$eval[7]*100)),2); // Avance Acumulado
        $graf_nc_a=round((100-($graf_c_a+$graf_av_a)),2); // No cumplido Acumulado

        $nro++;
        $tabla .='
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h2 class="alert alert-success" align="center">PROGRAMA : '.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <table class="change_order_items" border=1>
                <tr>
                  <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL</b></font><br>
                    <font FACE="courier new" size="1"><b>'.$trimestre.'</b></font>
                  </center>
                  <div id="container'.$nro.'" style="width: 600px; height: 300px; margin: 0 auto"></div>
                  </td>
                </tr>
                <tr>
                  <td>
                  <div class="table-responsive">
                    <table class="table table-bordered" align=center style="width:100%;">
                      <thead>
                      <tr bgcolor="#1c7368" align=center>
                        <th style="width:14%;" title="Nro. de Operaciones por Unidad"> U. ORG.</th>
                        <th style="width:14%;">CUMPLIDO</th>
                        <th style="width:14%;">EN AVANCE</th>
                        <th style="width:14%;">NO CUMPLIDO</th>
                        <th style="width:15%;">TOTAL PROG.</th>
                        <th style="width:14%;">TOTAL EVAL.</th>
                        <th style="width:15%;">% CUMPLIDO</th>
                        <th style="width:15%;">% NO CUMPLIDO</th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr align=center>
                          <td title="Operaciones del programa : '.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'">'.$row['acciones'].'</td>
                          <td>'.$eval[1].'</td>
                          <td>'.$eval[2].'</td>
                          <td>'.$eval[3].'</td>
                          <td>'.$eval[7].'</td>
                          <td>'.$eval[4].'</td>
                          <td title="OPERACIONES CUMPLIDAS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval[5].' %</button></td>
                          <td title="OPERACIONES NO CUMPLIDAS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval[6].' %</button></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
              <table class="change_order_items" border=1>
              <tr>
                <td>
                  <center>
                    <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL ACUMULADA</b></font><br>
                    <font FACE="courier new" size="1"><b> AL '.$trimestre.'</b></font>
                  </center>
                <div id="container_acu'.$nro.'" style="width: 600px; height: 300px; margin: 0 auto"></div>
                </td>
              </tr>
              <tr>
                <td>
                <div class="table-responsive">
                  <table class="table table-bordered" align=center style="width:100%;">
                    <thead>
                      <tr bgcolor="#1c7368" align=center>
                        <th style="width:14%;">U. EJEC.</th>
                        <th style="width:14%;">CUMPLIDO</th>
                        <th style="width:14%;">EN AVANCE</th>
                        <th style="width:14%;">NO CUMPLIDO</th>
                        <th style="width:14%;">TOTAL PROG.</th>
                        <th style="width:14%;">TOTAL EVAL.</th>
                        <th style="width:15%;">% CUMPLIDO</th>
                        <th style="width:15%;">% NO CUMPLIDO</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr align=center>
                        <td title="Operaciones del programa : '.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'">'.$row['acciones'].'</td>
                        <td>'.$eval_acu[1].'</td>
                        <td>'.$eval_acu[2].'</td>
                        <td>'.$eval_acu[3].'</td>
                        <td>'.$eval_acu[7].'</td>
                        <td>'.$eval_acu[4].'</td>
                        <td title="OPERACIONES CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-info">'.$eval_acu[5].' %</button></td>
                        <td title="OPERACIONES NO CUMPLIDOS"><button type="button" style="width:100%;" class="btn btn-danger">'.$eval_acu[6].' %</button></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </td>
              </tr>
              </table>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr>
            </div>';
            ?>
            <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container'+<?php echo $nro;?>, {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $graf_nc;?>%',
                          y: <?php echo $graf_nc;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av;?>%',
                          y: <?php echo $graf_av;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c; ?>%',
                          y: <?php echo $graf_c;?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
          </script>
          <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('container_acu'+<?php echo $nro;?>, {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $graf_nc_a;?>%',
                          y: <?php echo $graf_nc;?>,
                          color: '#f44336',
                        },

                        {
                          name: 'EN AVANCE : <?php echo $graf_av_a;?>%',
                          y: <?php echo $graf_av;?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $graf_c_a; ?>%',
                          y: <?php echo $graf_c;?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
          </script>
          <?php

        }
      }

      return $tabla;
    }

    /*------------- Lista de Acciones Operativas a nivel Distrital ---------------*/
    public function list_distrital($dist_id,$tp){
      $dist=$this->model_evalregional->get_dist($dist_id);
      $acciones=$this->model_evalregional->list_consolidado_distrital($dist[0]['dep_id'],$dist_id);
      
      if($tp==1){
        $class='id="dt_basic" class="table table table-bordered" width="100%"';
      }
      else{
        $class='border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center"';
      }

      $tabla =''; 
      $tabla .='<table '.$class.'>
                  <thead>                             
                    <tr class="modo1" align=center>
                      <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF; height:12px;">#</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">APERTURA PROGRAM&Aacute;TICA</th>
                      <th style="width:20%;" style="background-color: #1c7368; color: #FFFFFF">DESCRIPCI&Oacute;N</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">PONDERACI&Oacute;N</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">OPE. PROG.</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">OPE. EVAL.</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">OPE. CUMPLIDAS</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">OPE. EN AVANCE</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">OPE. NO CUMPLIDAS</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">% CUMP.</th>
                      <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">EFICACIA</th>
                      <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">CALIFICACI&Oacute;N</th>';
                        if($tp==1){
                          $tabla.='<th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF"></th>';
                        }
                      $tabla.='
                      
                    </tr>
                  </thead>
                  <tbody>';
      $nro=0;$sum_prog=0;$sum_eval=0;$sum_cum=0;$sum_avance=0;$sum_ncum=0;
      foreach($acciones  as $row){
        $p=$this->eficacia_evaluacion($row['proy_id'],$tp); /// Eficacia
        $eval=$this->matriz_evaluado_proyecto($row['proy_id'],2); /// Evaluado 
        $nro++;
        if($this->tmes==1){
          if($eval[7]!=0){
            $tabla .='<tr class="modo1" bgcolor='.$p[6][$this->tr_id].'>';
          }
          else{
            $tabla .='<tr class="modo1" bgcolor="#f1f1f1" title="NO PROGRAMADO">';
          }
        }
        else{
          $tabla .='<tr class="modo1" bgcolor='.$p[6][$this->tr_id].'>';
        }
        
          $tabla .='<td style="width: 2%; text-align: center; height:12px;">'.$nro.'</td>';
          $tabla .='<td style="width: 10%; text-align: center;">'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</td>';
          $tabla .='<td style="width: 20%; text-align: left;">'.$row['proy_nombre'].'</td>';
          $tabla .='<td style="width: 5%; text-align: right;">'.$row['proy_pcion_reg'].'%</td>';
          $tabla .='<td style="width: 5%; text-align: center;" bgcolor="#fff">'.$eval[7].'</td>';
          $tabla .='<td style="width: 5%; text-align: center;" bgcolor="#fff">'.$eval[4].'</td>';
          $tabla .='<td style="width: 5%; text-align: center;" bgcolor="#fff">'.$eval[1].'</td>';
          $tabla .='<td style="width: 5%; text-align: center;" bgcolor="#fff">'.$eval[2].'</td>';
          $tabla .='<td style="width: 5%; text-align: center;" bgcolor="#fff">'.$eval[3].'</td>';
          $tabla .='<td style="width: 5%; text-align: right;" bgcolor="#fff">'.$eval[5].'%</td>';
          if($tp==1){
            $tabla .='<td style="width: 5%; text-align: right;" title="EFICACIA"><button type="button" style="width:100%;" class="btn btn-default">'.$p[3][$this->tr_id].' %</button></td>';
            if($this->tmes==1){
              if($eval[7]!=0){
                $tabla .='<td style="width: 10%; text-align: right;" title="NIVEL DE CALIFICACI&Oacute;N">'.$p[5][$this->tr_id].'</td>';
              }
              else{
                $tabla .='<td style="width: 10%; text-align: right;" title="NIVEL DE CALIFICACI&Oacute;N"><center>NO PROGRAMADO</center></td>';
              }
            }
            else{
              $tabla .='<td style="width: 10%; text-align: right;" title="NIVEL DE CALIFICACI&Oacute;N">'.$p[5][$this->tr_id].'</td>';
            }

            
            $tabla .='<td style="width: 2%; text-align: center;" bgcolor="#fff" title="VER DETALLE DE EVALUACION DE LA UNIDAD"><img src="'.base_url().'assets/Iconos/arrow_left.png" alt="" width="30px"></td>';
          }
          else{
            $tabla .='<td style="width: 5%; text-align: right;" title="EFICACIA">'.$p[3][$this->tr_id].' %</td>';
            $tabla .='<td style="width: 10%; text-align: center;" title="NIVEL DE CALIFICACI&Oacute;N">'.$p[5][$this->tr_id].'</td>';
          }
          
        $tabla .='</tr>';
        $sum_prog=$sum_prog+$eval[7]; /// Total
        $sum_eval=$sum_eval+$eval[4]; /// Eval
        $sum_cum=$sum_cum+$eval[1]; /// Cumplidos
        $sum_avance=$sum_avance+$eval[2]; // En avance
        $sum_ncum=$sum_ncum+$eval[3]; /// No Cumplidos
      }
      $tabla.='</tbody>
                <tr class="modo1">
                  <td colspan=4 style="height:12px;">TOTAL : </td>
                  <td align="center">'.$sum_prog.'</td>
                  <td align="center">'.$sum_eval.'</td>
                  <td align="center">'.$sum_cum.'</td>
                  <td align="center">'.$sum_avance.'</td>
                  <td align="center">'.$sum_ncum.'</td>
                  <td colspan=3></td>
                </tr>
              </table>';

      return $tabla;
    }
    
    /*---------------------- Total programado - Evaluado por proyectos-------------------*/
    public function matriz_evaluado_proyecto($proy_id,$tp_eval){
      for ($i=1; $i <=7 ; $i++) { 
        $cat[$i]=0;
      }

        $nro_1=0;$nro_2=0;$nro_3=0;$total=0;$pcion=0;$npcion=0;
        $nro_cum=0;$nro_proc=0;$nro_ncum=0;$nro_total_prog=0; $nro_cum_a=0;$nro_proc_a=0;$nro_ncum_a=0;$nro_total_prog_a=0;
        if($tp_eval==1){
          /*------ Trimestral Productos -----------*/
          $cum=$this->model_evalregional->evaluacion_proyecto($proy_id,1,$this->tmes); // cumplido - prod
          if(count($cum)!=0){
            $nro_cum=$cum[0]['total'];
          }
          $proc=$this->model_evalregional->evaluacion_proyecto($proy_id,2,$this->tmes); // en proceso - prod
          if(count($proc)!=0){
            $nro_proc=$proc[0]['total'];
          }
          $ncum=$this->model_evalregional->evaluacion_proyecto($proy_id,3,$this->tmes); // no cumplido - prod
          if (count($ncum)!=0) {
            $nro_ncum=$ncum[0]['total'];
          }
          $total_prog=$this->model_evalregional->total_programado_accion($proy_id,$this->tmes); // total programado - prod
          if(count($total_prog)!=0){
            $nro_total_prog=$total_prog[0]['total'];
          }
          /*------------------------------------------*/
        }
        else{
          for ($i=1; $i <=$this->tmes ; $i++) { 
            /*------ Trimestral Productos Acumulado -----------*/
            $cum=$this->model_evalregional->evaluacion_proyecto($proy_id,1,$i); // cumplido - prod
            if(count($cum)!=0){
              $nro_cum=$nro_cum+$cum[0]['total'];
            }

            $proc=$this->model_evalregional->evaluacion_proyecto($proy_id,2,$i); // en proceso - prod
            if(count($proc)!=0){
              $nro_proc=$nro_proc+$proc[0]['total'];
            }

            $ncum=$this->model_evalregional->evaluacion_proyecto($proy_id,3,$i); // no cumplido - prod
            if(count($ncum)!=0){
              $nro_ncum=$nro_ncum+$ncum[0]['total'];
            }

            $total_prog=$this->model_evalregional->total_programado_accion($proy_id,$i); // total programado - prod
            if(count($total_prog)!=0){
              $nro_total_prog=$nro_total_prog+$total_prog[0]['total'];
            }
            /*--------------------------------------*/
          }
        }
        
        $nro_1=$nro_cum;
        $nro_2=$nro_proc;
        $nro_3=$nro_ncum;
        $total_programado=$nro_total_prog;

        if($total_programado!=0){
          $total=$nro_1+$nro_2+$nro_3;
          $pcion= round((($nro_1/$total_programado)*100),2);
          $npcion=(100-$pcion);
          //$npcion=round(((($nro_2+$nro_3)/$total_programado)*100),2);
        }
        
        if($total==0){
          $npcion=100;
        }
        
        $cat[1]=$nro_1; // cumplidos
        $cat[2]=$nro_2; // en proceso
        $cat[3]=$nro_3; // no cumplido
        $cat[4]=$total; // Total Evaluado
        $cat[5]=$pcion; // % cumplido
        $cat[6]=$npcion; // % no cumplido
        $cat[7]=$total_programado; // Total Programado

      return $cat;
    }

    
    public function pie_distrital($dist_id){
        $nro=$this->nro_list_distrital($dist_id);
        $tabla='';
        $tabla .='<style type="text/css">
                    #estilo1{color:#FFF;background:#f95b4f;}
                    #estilo2{color:#FFF;background:#f3d375;}
                    #estilo3{color:#FFF;background:#8bc9e4;}
                    #estilo4{color:#FFF;background:#4caf50;}
                  </style>';
        $tabla .='<div class="row">
                    <div class="col-sm-3" id="estilo1" style="height:3%;"><b>(0<=75)% INSATISFACTORIO : '.$nro[1].'</b></div>
                    <div class="col-sm-3" id="estilo2" style="height:3%;"><b>(75<=90)% REGULAR : '.$nro[2].'</b></div>
                    <div class="col-sm-3" id="estilo3" style="height:3%;"><b>(90<=99)% BUENO : '.$nro[3].'</b></div>
                    <div class="col-sm-3" id="estilo4" style="height:3%;"><b>100% OPTIMO : '.$nro[4].'</b></div>
                  </div>';
        
        return $tabla;
    }

    /*---------------------- Parametros de Eficacia Distrital ----------------------*/
    public function eficacia_distrital($dist_id,$tp){
        if($tp==1){
          $class='class="table table-bordered" align=center style="width:60%;"';
          $div='<div id="parametro_efi" style="width: 650px; height: 330px; margin: 0 auto"></div>';
        }
        else{
          $class='';
          $div='<div id="parametro_efi_print" style="width: 650px; height: 330px; margin: 0 auto"></div>';
        }
        $nro=$this->nro_list_distrital($dist_id);
        $tabla='';
        $tabla .='<table '.$class.'>
                    <tr>
                      <td>
                        '.$div.'
                      </td>
                    </tr>
                  </table>';
        $tabla .='<center>
                    <table '.$class.'>
                      <thead>
                        <tr>
                          <th><b>EFICACIA</b></th>
                          <th><b>PARAMETRO</b></th>
                          <th><b>NRO DE ACCIONES</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>INSATISFACTORIO</td>
                          <td>0% a 75%</td>
                          <td bgcolor="#f7c5c1"><b>'.$nro[1].'</b></td>
                        </tr>
                        <tr>
                          <td>REGULAR</td>
                          <td>75% a 90% </td>
                          <td bgcolor="#f5e5b9"><b>'.$nro[2].'</b></td>
                        </tr>
                        <tr>
                          <td>BUENO</td>
                          <td>90% a 99%</td>
                          <td bgcolor="#b1e0f5"><b>'.$nro[3].'</b></td>
                        </tr>
                        <tr>
                          <td>OPTIMO </td>
                          <td>100%</td>
                          <td bgcolor="#b2f1b4"><b>'.$nro[4].'</b></td>
                        </tr>
                        <tr>
                          <td colspan=2><b>TOTAL</b></td>
                          <td><b>'.$nro[5].'</b></td>
                        </tr>
                      </tbody>
                    </table>
                  </center>';
        
        return $tabla;
    }

    public function eficacia_evaluacion($proy_id,$tp){
      $tab=$this->componentes($proy_id);
      for ($i=1; $i <=12 ; $i++) { 
        $ev[1][$i]=0;$ev[2][$i]=0;$ev[3][$i]=0;$ev[4][$i]=0;
        if($tp==1){
          $ev[5][$i]='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
        }
        else{
          $ev[5][$i]='INSATISFACTORIO';
        }
        $ev[6][$i]='#f5dcdb';
      }

        for ($i=1; $i <=12 ; $i++) { 
            $ev[1][$i]=$tab[1][$i]; // Programado Acumulado
            $ev[2][$i]=$tab[2][$i]; // Ejecutado Acumulado
          if($tab[1][$i]!=0){
            $ev[3][$i]=round((($tab[2][$i]/$tab[1][$i])*100),2);

            if($ev[3][$i]>=0 & $ev[3][$i]<=75){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA INSATISFACTORIO" target="_blank" style="width:100%;" class="btn btn-danger">INSATISFACTORIO</a>';
              }
              else{
                $enlace='INSATISFACTORIO';
              }
              $ev[4][$i] = 1;$ev[5][$i] = $enlace;$ev[6][$i]='#f5dcdb';
            }

            elseif($ev[3][$i]>=75 & $ev[3][$i]<=90){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA REGULAR" target="_blank" style="width:100%;" class="btn btn-warning">REGULAR</a>';
              }
              else{
                $enlace='REGULAR';
              }

              $ev[4][$i] = 2;$ev[5][$i] = $enlace;$ev[6][$i]='#efe8b2';
            }
            
            elseif($ev[3][$i]>=90 & $ev[3][$i]<=99){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA BUENO" target="_blank" style="width:100%;" class="btn btn-info">BUENO</a>';
              }
              else{
                $enlace='BUENO';
              }

              $ev[4][$i] = 3;$ev[5][$i] = $enlace;$ev[6][$i]='#cbe8f5';
            }

            elseif($ev[3][$i]>=99 & $ev[3][$i]<=102){
              if($tp==1){
                $enlace='<a href="'.site_url("").'/eval_dproyecto/'.$proy_id.'" title="EFICACIA OPTIMO" target="_blank" style="width:100%;" class="btn btn-success">OPTIMO</a>';
              }
              else{
                $enlace='OPTIMO';
              }

              $ev[4][$i] = 4;$ev[5][$i] = $enlace; $ev[6][$i]='#a6eaa9';
            }
            else{
              $ev[4][$i] = 1;$ev[5][$i] = 'INSATISFACTORIO';
            }
          }
        }

        return $ev;
    }

    /*------------------------- Lista de nro Acciones Operativas a nivel Distrital ----------------------*/
    public function nro_list_distrital($dist_id){
      $dist=$this->model_evalregional->get_dist($dist_id);
      $acciones=$this->model_evalregional->list_consolidado_distrital($dist[0]['dep_id'],$dist_id);
      
      for ($i=1; $i <=9 ; $i++) { 
        $nro[$i]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($acciones  as $row){
        $p=$this->eficacia_evaluacion($row['proy_id'],1); /// Eficacia
        if($p[4][$this->tr_id]==1 || $p[4][$this->tr_id]==0){$nro[1]++;}
        elseif($p[4][$this->tr_id]==2 ){$nro[2]++;}
        elseif($p[4][$this->tr_id]==3 ){$nro[3]++;}
        elseif($p[4][$this->tr_id]==4 ){$nro[4]++;}
      }

      $nro_acciones=count($acciones);
      if($nro_acciones!=0){
        $nro[5]=$nro_acciones;
        $nro[6]=round((($nro[1]/$nro_acciones)*100),2); /// % insatisfactorio
        $nro[7]=round((($nro[2]/$nro_acciones)*100),2); /// % regular
        $nro[8]=round((($nro[3]/$nro_acciones)*100),2); /// % Bueno
        $nro[9]=round((($nro[4]/$nro_acciones)*100),2); /// % optimo
      }

      return $nro;
    }

    /*--------- Imprime Evaluacion Consolidado Distrital ---------*/
    public function print_proyectos_distrital($dist_id,$p){
      $dist=$this->model_evalregional->get_dist($dist_id);
      $tr=($this->tmes*3);
      $mes = $this->mes_nombre();
      $trimestre=$this->model_evaluacion->trimestre();
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
        .circulo, .ovalo {
          border: 2px solid #888888;
          margin: 2%;
          height: 42px;
          border-radius: 11px;
        }
        .circulo {
          width: 100px;      
        }
        .ovalo {
          width: 150px;
        }
      </style>
      <?php
      $tabla ='';
      $tabla .='<div class="verde"></div>
                <div class="blanco"></div>';
      $tabla .='<table width="100%" align=center>
                  <tr>
                    <td width=22%; text-align:center;"">
                        <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="50px"></center>
                    </td>
                    <td width=56%; class="titulo_pdf">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                          <tr>
                            <FONT FACE="courier new" size="1.5">
                            <b>'.$this->session->userdata('entidad').'</b><br>
                            <b>REGIONAL : </b>'.strtoupper($dist[0]['dep_departamento']).'<br>
                            <b>DISTRITAL : </b>'.strtoupper($dist[0]['dist_distrital']).'
                            </FONT>
                          </tr>
                        </table>
                    </td>
                    <td width=22%; align=left style="font-size: 8px;">
                      <div class="circulo" style="width:99%;"><br>
                      &nbsp; <b>RESP. </b>'.$this->session->userdata('funcionario').'<br>
                      &nbsp; <b>FECHA DE IMP. : '.date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y").'<br>
                      </div>
                    </td>
                  </tr>
                </table>
                <hr>';
        $tabla .='<center><FONT FACE="courier new" size="2"><b>CUADRO DE EJECUCI&Oacute;N DE RESULTADOS AL '.$trimestre[0]['trm_descripcion'].'<b/></FONT></center>';
        $tabla .='<table class="change_order_items" border=1 style="width:100%;">
                  <tr>
                    <td>
                      <div id="regresion_lineal2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div id="g_efi2" style="width: 700px; height: 300px; margin: 0 auto"></div>
                    </td>
                  </tr>

                  <tr>
                    <td colspan=2>
                      <table class="change_order_items" border=1>
                        <thead>
                            <tr bgcolor="#1c7368" align=center>
                              <th style="width:10%;"></th>
                              <th style="width:7%;"><font color="#ffffff">ENE.</font></th>
                              <th style="width:7%;"><font color="#ffffff">FEB.</font></th>
                              <th style="width:7%;"><font color="#ffffff">MAR.</font></th>
                              <th style="width:7%;"><font color="#ffffff">ABR.</font></th>
                              <th style="width:7%;"><font color="#ffffff">MAY.</font></th>
                              <th style="width:7%;"><font color="#ffffff">JUN.</font></th>
                              <th style="width:7%;"><font color="#ffffff">JUL.</font></th>
                              <th style="width:7%;"><font color="#ffffff">AGO.</font></th>
                              <th style="width:7%;"><font color="#ffffff">SEPT.</font></th>
                              <th style="width:7%;"><font color="#ffffff">OCT.</font></th>
                              <th style="width:7%;"><font color="#ffffff">NOV.</font></th>
                              <th style="width:7%;"><font color="#ffffff">DIC.</font></th>
                            </tr>
                        </thead>
                          <tbody>
                              <tr>
                                <td>%PA.</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4">'.$p[1][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EA.</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4">'.$p[2][$i].'%</td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[1][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                              </tr>
                              <tr>
                                <td>%EFICACIA</td>';
                                for ($i=1; $i <=12 ; $i++) {
                                  if($i<=$tr){
                                    $tabla .='<td bgcolor="#eaf7e4"><b>'.$p[3][$i].'%</b></td>';
                                  }
                                  else{
                                    $tabla .='<td>'.$p[3][$i].'%</td>';
                                  }
                                }
                              $tabla .='
                            </tr>
                          </tbody>
                      </table>
                    </td>
                  </tr>';
        $tabla .='</table>';
    return $tabla;
    } 

    /*------------------- Imprime Evaluacion Consolidado Distrital -----------------------*/
    public function get_print_cuadro_eval_distrital($dist_id){
      $cat_prog = $this->model_evalregional->categorias_programaticas_distrital($dist_id); /// trimestral
      $distrital= $this->model_evalregional->get_dist($dist_id);
      $tmes=$this->model_evaluacion->trimestre();
      $trimestre='TRIMESTRE NO DEFINIDO';
      if(count($tmes)!=0){
        $tmes=$this->model_evaluacion->trimestre();
        $trimestre=$tmes[0]['trm_descripcion'];
      }

      $tmes=$this->model_evaluacion->trimestre();
      $trim='TRIMESTRE NO DEFINIDO';
        if(count($tmes)!=0){
          $trim=$this->model_evaluacion->trimestre();
        }
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
          $nro=0;
          foreach($cat_prog  as $row){
            if($row['aper_programa']!='97' & $row['aper_programa']!='98'){
            $eval=$this->matriz_programas_distrital($dist_id,$row['aper_programa'],1); /// Evaluacion Trimestral
            $eval_acu=$this->matriz_programas_distrital($dist_id,$row['aper_programa'],2); /// Evalucion Trimestral Acumulado
            
            $graf_c=round((($eval[1]/$eval[7]*100)),2); // Cumplido 
            $graf_av=round((($eval[2]/$eval[7]*100)),2); // Avance 
            $graf_nc=round((100-($graf_c+$graf_av)),2); // No cumplido

            $graf_c_a=round((($eval_acu[1]/$eval[7]*100)),2); // Cumplido Acumulado
            $graf_av_a=round((($eval[2]/$eval[7]*100)),2); // Avance Acumulado
            $graf_nc_a=round((100-($graf_c_a+$graf_av_a)),2); // No cumplido Acumulado

            $nro++;
              $tabla .='
                    <div class="verde"></div>
                    <div class="blanco"></div>
                    <table width="90%" align=center>
                      <tr>
                        <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="65px"></center>
                        </td>
                        <td width=80%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>CUADRO DE EFICACIA DE OPERACIONES TRIMESTRAL Y ACUMULADO POR PROGRAMAS<br>
                            <b>REGIONAL : </b>'.strtoupper($distrital[0]['dep_departamento']).'<br>
                            <b>DISTRITAL : </b>'.strtoupper($distrital[0]['dist_distrital']).'
                            </FONT>
                        </td>
                      </tr>
                    </table>
                <hr>';
            $tabla .='
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <h4 class="alert alert-success" align="center">'.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'</h4>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <table class="change_order_items" border=1>
                    <tr>
                      <td>
                      <center>
                        <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL</b></font><br>
                        <font FACE="courier new" size="1"><b>'.$trimestre.'</b></font>
                      </center>
                      <div id="pcontainer'.$nro.'" style="width: 540px; height: 220px; margin: 0 auto"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      <div class="table-responsive">
                        <table class="change_order_items" border=1 align=center style="width:100%;">
                          <thead>
                          <tr bgcolor="#1c7368" align=center>
                            <th style="width:14%;"><font color="#ffffff">U. EJEC.</font></th>
                            <th style="width:14%;"><font color="#ffffff">CUMPLIDO</font></th>
                            <th style="width:14%;"><font color="#ffffff">EN PROCESO</font></th>
                            <th style="width:14%;"><font color="#ffffff">NO CUMPLIDO</font></th>
                            <th style="width:14%;"><font color="#ffffff">TOTAL PROG.</font></th>
                            <th style="width:14%;"><font color="#ffffff">TOTAL EVAL.</font></th>
                            <th style="width:15%;"><font color="#ffffff">% CUMPLIDO</font></th>
                            <th style="width:15%;"><font color="#ffffff">% NO CUMPLIDO</font></th>
                          </tr>
                          </thead>
                          <tbody>
                            <tr align=center>
                              <td title="Acciones Operativas del programa : '.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'">'.$row['acciones'].'</td>
                              <td>'.$eval[1].'</td>
                              <td>'.$eval[2].'</td>
                              <td>'.$eval[3].'</td>
                              <td>'.$eval[7].'</td>
                              <td>'.$eval[4].'</td>
                              <td title="OPERACIONES CUMPLIDOS" bgcolor="#b9ecf3">'.$eval[5].' %</td>
                              <td title="OPERACIONES NO CUMPLIDOS" bgcolor="#f7cbc8">'.$eval[6].' %</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <table class="change_order_items" border=1>
                  <tr>
                    <td>
                      <center>
                        <font FACE="courier new" size="4"><b>EVALUACI&Oacute;N TRIMESTRAL ACUMULADO</b></font><br>
                        <font FACE="courier new" size="1"><b> AL '.$trimestre.'</b></font>
                      </center>
                    <div id="pcontainer_acu'.$nro.'" style="width: 540px; height: 220px; margin: 0 auto"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                    <div class="table-responsive">
                      <table class="change_order_items" border=1 align=center style="width:100%;">
                        <thead>
                        <tr bgcolor="#1c7368" align=center>
                          <th style="width:14%;"><font color="#ffffff">U. EJEC.</font></th>
                          <th style="width:14%;"><font color="#ffffff">CUMPLIDO</font></th>
                          <th style="width:14%;"><font color="#ffffff">EN PROCESO</font></th>
                          <th style="width:14%;"><font color="#ffffff">NO CUMPLIDO</font></th>
                          <th style="width:14%;"><font color="#ffffff">TOTAL PROG.</font></th>
                          <th style="width:14%;"><font color="#ffffff">TOTAL EVAL.</font></th>
                          <th style="width:15%;"><font color="#ffffff">% CUMPLIDO</font></th>
                          <th style="width:15%;"><font color="#ffffff">% NO CUMPLIDO</font></th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr align=center>
                            <td title="Acciones Operativas del programa : '.$row['aper_programa'].' 0000 000'.' - '.$row['aper_descripcion'].'">'.$row['acciones'].'</td>
                            <td>'.$eval_acu[1].'</td>
                            <td>'.$eval_acu[2].'</td>
                            <td>'.$eval_acu[3].'</td>
                            <td>'.$eval_acu[7].'</td>
                            <td>'.$eval_acu[4].'</td>
                            <td title="OPERACIONES CUMPLIDOS" bgcolor="#b9ecf3">'.$eval_acu[5].' %</td>
                            <td title="OPERACIONES NO CUMPLIDOS" bgcolor="#f7cbc8">'.$eval_acu[6].' %</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    </td>
                  </tr>
                  </table>
                </div>';
                ?>
                <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
                <script type="text/javascript">
                $(document).ready(function() {  
                   Highcharts.chart('pcontainer'+<?php echo $nro;?>, {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}'
                            }
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Operaciones',
                        data: [
                            {
                              name: 'NO CUMPLIDO : <?php echo $graf_nc;?>%',
                              y: <?php echo $graf_nc;?>,
                              color: '#f44336',
                            },

                            {
                              name: 'EN AVANCE : <?php echo $graf_av;?>%',
                              y: <?php echo $graf_av;?>,
                              color: '#f5eea3',
                            },

                            {
                              name: 'CUMPLIDO : <?php echo $graf_c; ?>%',
                              y: <?php echo $graf_c;?>,
                              color: '#2CC8DC',
                              sliced: true,
                              selected: true
                            }
                        ]
                    }]
                  });
                });
              </script>
              <script type="text/javascript">
                $(document).ready(function() {  
                   Highcharts.chart('pcontainer_acu'+<?php echo $nro;?>, {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}'
                            }
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Operaciones',
                        data: [
                            {
                              name: 'NO CUMPLIDO : <?php echo $graf_nc_a;?>%',
                              y: <?php echo $graf_nc;?>,
                              color: '#f44336',
                            },

                            {
                              name: 'EN AVANCE : <?php echo $graf_av_a;?>%',
                              y: <?php echo $graf_av;?>,
                              color: '#f5eea3',
                            },

                            {
                              name: 'CUMPLIDO : <?php echo $graf_c_a; ?>%',
                              y: <?php echo $graf_c;?>,
                              color: '#2CC8DC',
                              sliced: true,
                              selected: true
                            }
                        ]
                    }]
                  });
                });
              </script>
              <?php

              $tabla .='<div class="saltopagina"></div>';

            }
          }
      ?>
      </html>
      <?php
      return $tabla;
    }
    /*---------------------------------------------------------------------------------*/

    /*---------------------- Imprime Parametros de eficacia distrital -----------------*/
    public function print_eficacia_distrital($nro,$eficacia,$dist_id){
      $distrital= $this->model_evalregional->get_dist($dist_id);
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
                    <table width="90%" align=center>
                      <tr>
                        <td width=20%; text-align:center;"">
                            <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="70px"></center>
                        </td>
                        <td width=80%; class="titulo_pdf">
                            <FONT FACE="courier new" size="1">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br> 
                            <b>REPORTE : </b>CUADRO DE EVALUACI&Oacute;N DE OPERACIONES '.$this->gestion.'<br>
                            <b>REGIONAL : </b>'.strtoupper($distrital[0]['dep_departamento']).'<br>
                            <b>DISTRITAL : </b>'.strtoupper($distrital[0]['dist_distrital']).'
                            </FONT>
                        </td>
                      </tr>
                    </table>
                <hr>';
            $tabla .=''.$eficacia.'';
          ?>
          <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
          <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('parametro_efi_print', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Eficacia',
                    data: [
                        {
                          name: 'INSATISFACTORIO : <?php echo $nro[6];?>%',
                          y: <?php echo $nro[1];?>,
                          color: '#f95b4f',
                        },
                        {
                          name: 'REGULAR : <?php echo $nro[7];?>%',
                          y: <?php echo $nro[2];?>,
                          color: '#f3d375',
                        },
                        {
                          name: 'BUENO : <?php echo $nro[8];?>%',
                          y: <?php echo $nro[3];?>,
                          color: '#8bc9e4',
                        },
                        {
                          name: 'OPTIMO : <?php echo $nro[9];?>%',
                          y: <?php echo $nro[4];?>,
                          color: '#4caf50',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
        </script>
      </html>
      <?php
      return $tabla;
    }   
    /*---------------------------------------------------------------------------------*/
    /*------------------------ Reporte Eficacia por Unidades Ejecutoras ---------------*/
    public function reporte_eficacia($dist_id){
      if($this->gestion==2018){
        $html = $this->eficacia_acciones($dist_id); 
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'landscape');
        ini_set('memory_limit','700M');
        ini_set('max_execution_time', 900000);
        $dompdf->render();
        $dompdf->stream("EVALUACION.pdf", array("Attachment" => false));
      }
      else{
        $data['mes'] = $this->mes_nombre();
        $data['dist']=$this->model_evalregional->get_dist($dist_id);
        $data['nro']=$this->nro_list_distrital($dist_id);
        $data['trimestre']=$this->model_evaluacion->trimestre();
        $data['unidades']=$this->list_distrital($dist_id,2);
        $this->load->view('admin/reportes_cns/eval_distrital/reporte_eval_distrital', $data);
      }
    }

    /*---------------------- Eficacia Acciones ---------------------------*/
        /*--------------------------- EVALUAR OPERACIONES --------------------------------*/
    function eficacia_acciones($dist_id){
      $gestion = $this->session->userdata('gestion');
      $dist=$this->model_evalregional->get_dist($dist_id);
      $tmes=$this->model_evaluacion->trimestre();
      $nro=$this->nro_list_distrital($dist_id);
      $html = '
      <html>
        <head>' . $this->estilo_vertical() . '
         <style>
           @page { margin: 130px 20px; }
           #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
           #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 50px;}
           #footer .page:after { content: counter(page, upper-roman); }
         </style>
         <style type="text/css">
            .circulo, .ovalo {
            border: 2px solid #888888;
            margin: 2%;
            height: 55px;
            border-radius: 60%;
          }
          .circulo {
            width: 100px;      
          }
          .ovalo {
            width: 150px;
          }

          .circulo1, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 40%;
            background:#f5c9c8;
          }
          .circulo2, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#ece396;
          }
          .circulo3, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#b4dff3;
          }
          .circulo4, .ovalo {
            border: 2px solid #000;
            margin: 2%;
            height: 30px;
            border-radius: 60%;
            background:#a5efa8;
          }
        </style>
        <body>
         <div id="header">
              <div class="verde"></div>
              <div class="blanco"></div>
              <table width="100%" border=0>
                  <tr>
                      <td width=15%; text-align:center;"">
                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"></center>
                      </td>
                      <td width=60%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $gestion . '<br>
                          <b>REGIONAL : </b> '.strtoupper($dist[0]['dep_departamento']).'<br>
                          <b>DISTRITAL : </b> '.strtoupper($dist[0]['dist_distrital']).'<br>
                          <b>EVALUACI&Oacute;N DE OPERACIONES : </b> '.strtoupper($tmes[0]['trm_descripcion']).' 
                          </FONT>
                      </td>
                      <td width=25%; text-align:center;"">
                          <div class="circulo" style="width:100%;"><br>
                            &nbsp;  <b>EVALUACI&Oacute;N TRIMESTRAL DISTRITAL</b><br>
                            &nbsp;  <b>REGIONAL :</b> '.strtoupper($dist[0]['dep_sigla']).'-'.$this->gestion.'<br>
                            &nbsp;  <b>FECHA DE IMPRESI&Oacute;N : </b>'.date('d/m/Y').'<br>
                            &nbsp;  <b>RESPONSABLE :</b> '.$this->session->userdata('funcionario').'<br>
                          </div>
                      </td>
                  </tr>
              </table>
         </div>
          <div id="footer">
            <hr>
            <table border="0" >
                <tr>
                    <td colspan=3>
                      <table border="0">
                        <tr>
                          <td style="width:25%;">
                            <div class="circulo1" style="width:100%;"><br>
                              &nbsp;<b>INSATISFACTORIO (0 a 99)% BUENO : '.$nro[1].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo2" style="width:100%;"><br>
                              &nbsp;<b>REGULAR (75 a 90)% : '.$nro[2].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo3" style="width:100%;"><br>
                              &nbsp;<b>BUENO (90 a 99)% : '.$nro[3].' Acciones Operativas</b>
                            </div>
                          </td>
                          <td style="width:25%;">
                            <div class="circulo4" style="width:100%;"><br>
                              &nbsp;<b>OPTIMO 100% : '.$nro[4].' Acciones Operativas</b>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </td>
                </tr>
                <tr>
                    <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                    <td><p class="page">Pagina </p></td>
                </tr>
            </table>
         </div>
         <div id="content">
           <p>'.$this->list_distrital($dist_id,2).'</p>
         </div>
       </body>
       </html>';
      return $html;
    }

    /*------------------------ Componentes -------------------------*/
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

    /*------------------------ Productos -------------------------*/
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

    /*-------------Sumatoria Temporalidad Productos ----------------*/
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
            font-size: 8px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 7px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 6px;
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
        font-size: 6px;
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