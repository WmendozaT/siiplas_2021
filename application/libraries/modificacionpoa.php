<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class Modificacionpoa extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('modificacion/model_modrequerimiento');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('programacion/model_producto');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mantenimiento/model_configuracion');

        $this->load->model('modificacion/model_modificacion');

        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('ejecucion/model_certificacion');

        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        //$this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        //$this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
       // $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->tp_adm = $this->session->userData('tp_adm');
    }



    //// CABECERA MODIFICACION POA
    public function cabecera_modpoa(){
      $tabla='';

      $tabla.='
      <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:91.8%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=14%; text-align:center;"">
                     
                          </td>
                          <td width=76%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:93%;" align="center">
                                <tr>
                                    <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b></b></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>DIR. ADM.</b></td>
                                    <td style="width:82.5%;">: </td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>UNI. EJEC.</b></td>
                                    <td style="width:82.5%;">:</td>
                                </tr>
                                <?php echo $titulo;?>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>CITE FORM. MOD. N°8</b></td>
                                    <td style="width:82.5%;">: </td>
                                </tr>
                            </table>
                          </td>
                          <td style="width:19%; font-size: 8.5px;" align="left">
                            <b style="font-size: 11px;">CÓDIGO N°: </b><br>
                            <b>FECHA DE IMP. : </b><br>
                            <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>';
      return $tabla;
    }



    /*------ NOMBRE MES -------*/
    public function mes_nombre_completo(){
        $mes[1] = 'ENERO';
        $mes[2] = 'FEBRERO';
        $mes[3] = 'MARZO';
        $mes[4] = 'ABRIL';
        $mes[5] = 'MAYO';
        $mes[6] = 'JUNIO';
        $mes[7] = 'JULIO';
        $mes[8] = 'AGOSTO';
        $mes[9] = 'SEPTIEMBRE';
        $mes[10] = 'OCTUBRE';
        $mes[11] = 'NOVIEMBRE';
        $mes[12] = 'DICIEMBRE';

      return $mes;
    }

    /*------ NOMBRE MES -------*/
    public function mes_nombre(){
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
}
?>