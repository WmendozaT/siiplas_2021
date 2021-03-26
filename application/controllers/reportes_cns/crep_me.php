<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Crep_me extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('resultados/model_resultado');
            $this->load->model('mestrategico/model_mestrategico');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
            redirect('/','refresh');
        }
    }

    /*----- ACCIONES ESTRATEGICAS -----*/
    public function list_acciones_estrategicas(){
      $data['menu']=$this->menu(7);
      $data['acciones']=$this->mis_acciones_estrategicas(1);
      $this->load->view('admin/reportes_cns/marco_estrategico/acciones_estrategicas', $data);
    }

    /*------------------------- LISTA DE ACCIONES ESTRATEGICAS --------------------*/
    public function mis_acciones_estrategicas($tp){
      if($tp==1){
        $tit='ACCIONES ESTRAT&Eacute;GICAS DE MEDIANO PLAZO';
        $table='table id="dt_basic" class="table table table-bordered" width="100%"';
      }
      else{
        $tit='';
        $table='table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;"';
      }
      $acciones = $this->model_mestrategico->acciones_mediano_plazo_resultados(); /// ACCIONES ESTRATEGICAS - RESULTADOS
      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>'.$tit.'</strong></h2>  
                    </header>
                <div>
                  <div class="widget-body no-padding">
                    <'.$table.'>
                      <thead>
                        <tr class="modo1">
                          <th style="width:1%;">NRO</th>
                          <th style="width:2%;">C&Oacute;DIGO</th>
                          <th style="width:8%;">ACCI&Oacute;N ESTRATEGICA</th>
                          <th style="width:8%;">OBJETIVO ESTRAT&Eacute;GICO</th>
                          <th style="width:13%;">VINCULACI&Oacute;N AL PDES </th>
                          <th style="width:8%;">RESULTADO</th>
                          <th style="width:5%;">INDICADOR</th>
                          <th style="width:5%;">MEDIO DE VERIFICACI&Oacute;N</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($acciones as $row){
                          $pdes=$this->model_proyecto->datos_pedes($row['pdes_id']);
                          $nro++;
                          $tabla .='<tr class="modo1">';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td>'.$row['acc_codigo'].'</td>';
                            $tabla .='<td>'.$row['acc_descripcion'].'</td>';
                            $tabla .='<td>'.$row['obj_descripcion'].'</td>';
                            $tabla .='<td>';
                              $tabla.=' <b>PILAR :</b> '.$pdes[0]['pilar'].'<br>
                              <b>META :</b> '.$pdes[0]['meta'].'<br>
                              <b>RESULTADO :</b> '.$pdes[0]['resultado'].'<br>
                              <b>ACCI&Oacute;N :</b> '.$pdes[0]['accion'].'<br>';
                            $tabla .='</td>';
                            $tabla .='<td bgcolor="#c7f1eb">'.$row['rm_resultado'].'</td>';
                            $tabla .='<td bgcolor="#c7f1eb">'.$row['rm_indicador'].'</td>';
                            $tabla .='<td bgcolor="#c7f1eb">'.$row['rm_fuente_verificacion'].'</td>';
                          $tabla .='</tr>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';
      return $tabla;
    }

    /*----------------------------- REPORTE ACCIONES ESTRATEGICAS ---------------------------------*/
    public function rep_acciones_estrategicas(){
      $html = $this->acciones_estrategicas(); 

      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'landscape');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("ACCIONES_ESTRATEGICAS.pdf", array("Attachment" => false));
    }

    /*--------------------------- ACCIONES ESTRATEGICAS --------------------------------*/
    function acciones_estrategicas(){
      $html = '
      <html>
        <head>' . $this->estilo_vertical() . '
         <style>
           @page { margin: 130px 20px; }
           #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 20px; background-color: #fff; text-align: center; }
           #footer { position: fixed; left: 0px; bottom: -65px; right: 0px; height: 50px;}
           #footer .page:after { content: counter(page, numeric); }
         </style>
        <body>
         <div id="header">
              <div class="verde"></div>
              <div class="blanco"></div>
              <table width="100%">
                  <tr>
                      <td width=20%; text-align:center;"">
                          <center><img src="'.base_url().'assets/ifinal/cns_logo.JPG" alt="" width="60px"></center>
                      </td>
                      <td width=60%; class="titulo_pdf">
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                          <b>REPORTE : </b> ACCIONES ESTRATEGICAS DE CORTO PLAZO - ' . $this->gestion . '
                          </FONT>
                      </td>
                      <td width=20%; text-align:center;"">
                      </td>
                  </tr>
              </table><hr>
         </div>
          <div id="footer">
              <table border="1" cellpadding="0" cellspacing="0" class="tabla">
                <tr bgcolor=#DDDEDE>
                    <td width=33%;>FIRMA 1</td>
                    <td width=33%;>FIRMA 2</td>
                    <td width=33%;>FIRMA 3</td>
                </tr>
                <tr>
                    <td>
                    <b>ELABORADO POR :</b><br>
                    <b>NOMBRE : LUIS RIVAS MICHEL </b><br>
                    <b>CARGO : PLANIFICADOR ADMINISTRATIVO</b><br>
                    <b>FECHA : '.date('Y/n/j - H:i').'</b> 
                    </td>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                </tr>
              </table>
              <table>
                <tr>
                  <td colspan=2><p class="izq">'.$this->session->userdata('sistema_pie').'</p></td>
                  <td align="right"><p class="page">Pagina </p></td>
                </tr>
              </table>
           </div>
         <div id="content">
           <p><div>'.$this->mis_acciones_estrategicas(2).'</div></p>
         </div>
       </body>
       </html>';
      return $html;
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
          font-size: 8px;
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
      font-size: 8px;
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