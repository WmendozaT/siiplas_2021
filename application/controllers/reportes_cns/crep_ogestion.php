<?php
///// formularios de Gestion 
class Crep_ogestion extends CI_Controller { 
  public function __construct (){ 
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
      $this->load->model('programacion/model_proyecto');
      $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
      $this->load->model('mestrategico/model_mestrategico');
      $this->load->model('mestrategico/model_objetivogestion');
      $this->load->model('mestrategico/model_objetivoregion');
      $this->load->model('mantenimiento/model_configuracion');
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
      $data['res_dep']=$this->tp_resp();
      $configuracion=$this->model_configuracion->get_configuracion();
      $lista_acp='';

      if($configuracion[0]['ide']==$this->gestion){
        $lista_acp.='
          <div class="btn-group" >
            <a class="btn btn-default">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FORMULARIOS A.C.P. &nbsp;&nbsp;&nbsp;&nbsp;</a>
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" ><span class="caret"></span></a>
            <ul class="dropdown-menu">';

              for ($i=2021; $i <$this->gestion ; $i++) { 
                $lista_acp.='
                <li>
                  <a href="javascript:abreVentana(\''.base_url().'CumplimientoACP/EVALUACION_ACP_'.$i.'.pdf\');" >CUMPLIMIENTO ACP / '.$i.'</a>
                </li>';
              }
          $lista_acp.='
            </ul>
          </div>';
      }

      $data['lista_acp']=$lista_acp;      
      $data['contenido']='<iframe id="ipdf" width="100%"  height="900px;" src="'.base_url().'documentos/Form1_POA_'.$this->gestion.'.pdf"></iframe>';

      $this->load->view('admin/reportes_cns/programacion_pei/form_spo_01/ver_ogestion', $data);
    }


    /*-----  REGIONALES - FORM SPO 02 (2020) -----*/
    public function list_regionales_ogestion($mod){
      $data['menu']=$this->menu($mod);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['regiones']=$this->departamentos();

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

    /*-------- GET REPORTES OREGIONAL ------------*/
    public function get_reportes_oregional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $regional=$this->model_proyecto->get_departamento($dep_id);

        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/me/rep_form2/'.$dep_id.'"></iframe>';
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