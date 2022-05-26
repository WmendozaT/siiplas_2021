<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class ejecucion_finpi extends CI_Controller{

    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('programacion/model_componente');
        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dep_id = $this->session->userData('dep_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tmes = $this->session->userData('trimestre');
        $this->ppto= $this->session->userData('verif_ppto');
        $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema

    }

    /*------- TITULO --------*/
    public function formulario(){
      $tabla='';
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="well">
            <h2>FORMULARIO DE EJECUCI&Oacute;N PRESUPUESTARIA - '.$this->verif_mes[2].' / '.$this->gestion.'</h2>
            <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO">
              <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO (A.C.P.)
            </a>
            <a href="javascript:abreVentana(\''.site_url("").'/me/rep_ogestion\');" title="IMPRIMIR ACP DISTRIBUCION REGIONAL" class="btn btn-default">
              <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;REP. A.C.P. (FORM N° 1)
            </a>

            <hr>

            '.$this->lista_proyectos($this->dep_id).'
          </div>
        </article>';

      return $tabla;
    } 


    /*------- TITULO --------*/
    public function lista_proyectos($dep_id){
      $proyectos=$this->model_proyecto->list_pinversion(1,4);
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';

      $tabla.=' 
      <div class="jarviswidget jarviswidget-color-darken" >
            <header>
              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
              <h2 class="font-md"><strong>'.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'</strong></h2>  
            </header>
        <div>
          
          <div class="widget-body no-padding">
            <table id="dt_basic" class="table table table-bordered" width="100%">
              <thead>
                <tr>
                  <th style="width:1%;">#</th>
                  <th style="width:7%;">DISTRITAL</th>
                  <th style="width:10%;">CODIGO SISIN</th>
                  <th style="width:15%;">NOMBRE DEL PROYECTO</th>
                  <th style="width:5%;">FASE</th>
                  <th style="width:5%;">PARTIDA</th>
                  <th style="width:10%;">PPTO. INICIAL '.$this->gestion.'</th>
                  <th style="width:10%;">PPTO. MODIFICADO '.$this->gestion.'</th>
                  <th style="width:10%;">PPTO. AJUSTADO FINAL '.$this->gestion.'</th>
                  <th style="width:5%;">CATEGORIA PROGRAMATICA</th>
                  <th style="width:7%;">COSTO TOTAL DEL PROYECTO (Bs.)</th>
                  <th style="width:7%;">EJECUCIÓN '.$this->verif_mes[2].' / '.$this->gestion.'</th>
                  <th style="width:7%;">TOTAL EJECUTADO (Bs.)</th>
                </tr>
              </thead>
              <tbody>';
                $nro=0;
                foreach($proyectos as $row){
                  $fase = $this->model_faseetapa->get_id_fase($row['proy_id']);
                  $nro++;
                  $tabla.='
                  <tr>
                    <td>'.$nro.'</td>
                    <td>'.strtoupper($row['dist_distrital']).'</td>
                    <td>'.$row['proy_sisin'].'</td>
                    <td>'.$row['proy_nombre'].'</td>
                    <td>'.$nro.'</td>
                    <td>'.$nro.'</td>
                    <td>'.$nro.'</td>
                    <td>'.$nro.'</td>';
                  $tabla.='</tr>';
                }
              $tabla.='
              </tbody>
            </table>
          </div>';

      return $tabla;
    }




    ////// LIBRERIAS PARA REPORTES GERENCIALES  /// Menu Seguimiento POA (Sub Actividad)
    public function menu_pi(){
      $tabla='';
      $tabla.='
      <aside id="left-panel">
        <div class="login-info">
          <span>
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
              <span>
                <i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;'.$this->session->userdata("user_name").'
              </span>
              <i class="fa fa-angle-down"></i>
            </a>
          </span>
        </div>
        <nav>
          <ul>
            <li class="">
            <a href="'.site_url("").'/admin/dashboard" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
            </li>
            <li class="text-center">
              <a href="#" title="EJECUCION PROYECTOS DE INVERSION"> <span class="menu-item-parent">EJECUCIÓN P.I.</span></a>
            </li>
            <li>
              <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Ejecución financiera</span></a>
              <ul>
                <li>
                  <a href="'.site_url("").'/solicitar_certpoa/">Registro Ejecución<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                </li>
                <li>
                  <a href="'.site_url("").'/mis_solicitudes_cpoa/">Reporte Financiero<span class="badge pull-right inbox-badge bg-color-yellow">nuevo</span></a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
      </aside>';

      return $tabla;
    }


        /// ---- STYLE -----
    public function style(){
      $tabla='';

      $tabla.='   
      <style>
        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
        }
        th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
        }
            #mdialTamanio{
            width: 80% !important;
        }
        #modificacion{
          width: 80% !important;
        }
        #programacion{
          width: 50% !important;
        }
        #certificacion{
          width: 40% !important;
        }
        #evaluacion{
          width: 80% !important;
        }
          input[type="checkbox"] {
          display:inline-block;
          width:25px;
          height:25px;
          margin:-1px 4px 0 0;
          vertical-align:middle;
          cursor:pointer;
        }
    </style>';

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

  /*=====================================================================*/
    public function get_mes($mes_id){
      $mes[1]='ENERO';
      $mes[2]='FEBRERO';
      $mes[3]='MARZO';
      $mes[4]='ABRIL';
      $mes[5]='MAYO';
      $mes[6]='JUNIO';
      $mes[7]='JULIO';
      $mes[8]='AGOSTO';
      $mes[9]='SEPTIEMBRE';
      $mes[10]='OCTUBRE';
      $mes[11]='NOVIEMBRE';
      $mes[12]='DICIEMBRE';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }

}
?>