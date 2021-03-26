<?php
class Crep_ogestion extends CI_Controller { 
  public function __construct (){ 
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->model('programacion/model_proyecto');
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->pcion = $this->session->userData('pcion');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist'); /// Dist id
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
        $this->dep_id = $this->session->userData('dep_id'); /// Dep ID
        $this->tp_adm = $this->session->userData('tp_adm'); 

        }else{
            redirect('/','refresh');
        }
    }
    
    /*----- Tipo de Responsable ------*/
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

    /*-----  LISTA REP. OGESTION - FORM SPO 01 (2020) -----*/
    public function mis_ogestion($mod){
      $data['menu']=$this->menu($mod);
      $data['resp']=$this->session->userdata('funcionario');
      $session=$this->model_proyecto->configuracion_session();
      $data['res_dep']=$this->tp_resp();

      $titulo='PROGRAMACI&Oacute;N POA';
      $li='<li>Programaci&oacute;n POA</li><li>Mis Objetivos</li><li>Formulario POA - N° 1, '.$this->gestion.'</li>';

      if($mod==7){
        $titulo='REPORTES POA';
        $li='<li>Reportes POA</li><li>Marco Estrategico</li><li>Formulario POA - N° 1, '.$this->gestion.'</li>';
      }

      if($this->gestion>2019){
        //$data['contenido']='<iframe id="ipdf" width="90%"  height="900px;" src="'.base_url().'index.php/me/rep_ogestion"></iframe>';
        $data['contenido']='<iframe id="ipdf" width="90%"  height="900px;" src="'.base_url().'documentos/Form1_POA_'.$this->gestion.'.pdf"></iframe>';
      }
      else{
        $data['contenido']= '<div class="alert alert-danger" role="alert">
                              NO VALIDO PARA LA GESTI&Oacute;N '.$this->gestion.'
                            </div>';
      }
      
      $dep_id=$this->dep_id;
      if($this->tp_adm==1){
        $dep_id=10;
      }


      $data['caratula_poa']='<iframe id="ipdf" width="90%"  height="900px;" src="'.base_url().'index.php/mnt/caratula_poa/'.$dep_id.'"></iframe>';
      $data['caratula_pi']='<iframe id="ipdf" width="90%"  height="900px;" src="'.base_url().'index.php/mnt/caratula_pi/'.$dep_id.'"></iframe>';

      $data['li']=$li;
      $data['titulo']=$titulo;

      $this->load->view('admin/reportes_cns/programacion_pei/form_spo_01/ver_ogestion', $data);
    }


    /*-----  REGIONALES - FORM SPO 02 (2020) -----*/
    public function list_regionales_ogestion($mod){
      $data['menu']=$this->menu($mod);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();

      $titulo='PROGRAMACI&Oacute;N POA';
      $li='<li>Programaci&oacute;n POA</li><li>Mis Objetivos</li><li>Formulario POA - N° 2, '.$this->gestion.'</li>';

      if($mod==7){
        $titulo='REPORTES POA';
        $li='<li>Reportes POA</li><li>Marco Estrategico</li><li>Formulario POA - N° 2, '.$this->gestion.'</li>';
      }

      if($this->gestion>2019){
        $data['regiones']=$this->departamentos();
      }
      else{
        $data['regiones']= '<div class="alert alert-danger" role="alert">
                              NO VALIDO PARA LA GESTI&Oacute;N '.$this->gestion.'
                            </div>';
      }
      
      $data['titulo']=$titulo;
      $data['li']=$li;

      $this->load->view('admin/reportes_cns/programacion_pei/form_spo_02/regiones', $data);
    }


     //// DEPARTAMENTOS
    public function departamentos(){
      $regiones=$this->mrep_operaciones->regiones();
      $nro=0;
      $tabla ='';
      $tabla.='<article class="col-sm-12 col-md-3 col-lg-3">
                <div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                  <header>
                    <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                    <h2>REPORTE : FORM. POA - N° 2</h2>
                  </header>
                  <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
          
                      <form method="post" >
                        <fieldset>
                          <div class="form-group">
                            <label>REGIONAL</label>
                              <select class="form-control" name="dep_id" id="dep_id">
                                <option value="">Selecciones Regional</option>';
                                foreach($regiones as $rowp){
                                  $tabla.='<option value='.$rowp['dep_id'].'>'.$rowp['dep_departamento'].'</option>';
                                }
                              $tabla.='
                              </select>
                          </div>
                        </fieldset>
                      </form>
                    </div>
          
                  </div>
                </div>
                </article>

                <article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
                      <header>
                          <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                          <h2></h2>
                      </header>
                      <div>
                          <div class="jarviswidget-editbox">
                          </div>
                          <div class="widget-body">
                            <div id="tit"></div>
                            <div id="content1"></div>
                          </div>
                      </div>
                  </div>
                </article>';

      return $tabla;
    }


    //// REGIONAL ALINEADO A OBJETIVOS REGIONALES 
    public function ver_relacion_ogestion($dep_id){
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      $data['mes'] = $this->mes_nombre();
      $tabla='';
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);

      $tabla.='
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
        <thead>
        <tr style="font-size: 7px;" bgcolor="#1c7368" align=center>
          <th style="width:1%;height:20px;color:#FFF;">N°</th>
          <th style="width:2.5%;color:#FFF;"><b>COD. ACE.</b></th>
          <th style="width:2.5%;color:#FFF;"><b>COD. ACP.</b></th>
          <th style="width:2.5%;color:#FFF;"><b>COD. OR.</b></th>
          <th style="width:22%;color:#FFF;">OBJETIVO REGIONAL</th>
          <th style="width:20%;color:#FFF;">RESULTADO</th>
          <th style="width:18%;color:#FFF;">INDICADOR</th>
          <th style="width:4%;color:#FFF;">META</th>
          <th style="width:18%;color:#FFF;">MEDIO DE VERIFICACI&Oacute;N</th>
          <th style="width:7%;color:#FFF;">PPTO. '.$this->gestion.'</th>
        </tr>
        </thead>
        <tbody>';
      $nro=0;$monto_total=0;
      foreach($lista_ogestion as $row){
        $presupuesto_gc=$this->model_objetivogestion->get_ppto_ogestion_gc_regional($row['og_id'],$dep_id); // ppto Gasto Corriente
        $presupuesto_pi=$this->model_objetivogestion->get_ppto_ogestion_pi_regional($row['og_id'],$dep_id); // ppto Proyecto de Inversion
          $ppto_gc=0;$ppto_pi=0;
          if(count($presupuesto_gc)!=0){
            $ppto_gc=$presupuesto_gc[0]['presupuesto'];
          }
          if(count($presupuesto_pi)!=0){
            $ppto_pi=$presupuesto_pi[0]['presupuesto'];
          }
        $nro++;
        $tabla.='
        <tr style="font-size: 7px;">
          <td style="width:1%; height:15px;" align=center>'.$nro.'</td>
          <td style="width:2.5%;" align="center">'.$row['acc_codigo'].'</td>
          <td style="width:2.5%;" align="center">'.$row['og_codigo'].'</td>
          <td style="width:2.5%; font-size: 8px;" align="center" bgcolor="#f1eeee"><b>'.$row['or_codigo'].'</b></td>
          <td style="width:22%;">'.$row['or_objetivo'].'</td>
          <td style="width:20%;">'.$row['or_resultado'].'</td>
          <td style="width:18%;">'.$row['or_indicador'].'</td>
          <td style="width:4%; font-size: 8px;" align=center><b>'.round($row['or_meta'],2).'</b></td>
          <td style="width:18%;">'.$row['or_verificacion'].'</td>
          <td style="width:7%;text-align: right;">'.number_format(($ppto_gc+$ppto_pi), 2, ',', '.').'</td>
        </tr>';
        $monto_total=$monto_total+($ppto_gc+$ppto_pi);
      }
      $tabla.='
        </tbody>
        <tr>
          <td style="height:11px; text-align: right;" colspan=9><b>PRESUPUESTO TOTAL : </b></td>
          <td style="text-align: right;">'.number_format($monto_total, 2, ',', '.').'</td>
        </tr>
      </table>';
      
      $data['oregional']=$tabla;
      $this->load->view('admin/reportes_cns/programacion_pei/form_spo_02/reporte_region_ogestion', $data);
    }
    
    /*-------- GET REPORTES OREGIONAL ------------*/
    public function get_reportes_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $regional=$this->model_proyecto->get_departamento($dep_id);

        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/rep/ver_ogestion/'.$dep_id.'"></iframe>';
        $result = array(
          'respuesta' => 'correcto',
          'regional'=>$regional,
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*------------- MENU -------------*/
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
}