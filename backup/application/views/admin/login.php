<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>LOGIN SIIPLAS</title>
  <script src="<?php echo base_url(); ?>assets/login/js/modernizr.js" type="text/javascript"></script>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/css/normalize.min.css">
  <link rel='stylesheet prefetch' href='<?php echo base_url(); ?>assets/login/css/gubja.css'>
  <link rel='stylesheet prefetch' href='<?php echo base_url(); ?>assets/login/css/yaozl.css'>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/css/style.css">
</head>
<style>
body {
    font-family: "Segoe UI", sans-serif;
    font-size:100%;
}
.caja {
font-family: sans-serif;
font-size: 28px;
font-weight: 100;
color: #000000;
background: #ced3df;
margin: 0 0 15px;
overflow: hidden;
padding: 3px;
}

.sidebar {
    position: fixed;
    height: 100%;
    width: 0;
    top: 0;
    left: 0;
    z-index: 1;
    background-color: #083e38;
    overflow-x: hidden;
    transition: 0.4s;
    padding: 1rem 0;
    box-sizing:border-box;
}

.sidebar .boton-cerrar {
    position: absolute;
    top: 0.5rem;
    right: 1rem;
    font-size: 2rem;
    display: block;
    padding: 0;
    line-height: 1.5rem;
    margin: 0;
    height: 32px;
    width: 32px;
    text-align: center;
    vertical-align: top;
}

.sidebar ul, .sidebar li{
    margin:0;
    padding:0;
    list-style:none inside;
}

.sidebar ul {
    margin: 4rem auto;
    display: block;
    width: 80%;
    min-width:200px;
}

.sidebar a {
    display: block;
    font-size: 100%;
    color: #eee;
    text-decoration: none;
    
}

.sidebar a:hover{
    color:#fff;
    background-color: #808282;

}

h1 {
    color:#eceff1;
    font-size:120%;
    font-weight:normal;
}
#contenido {
    transition: margin-left .4s;
    padding: 1rem;
}

.abrir-cerrar {
    color: #eceff1;
    font-size:1rem;   
}

#abrir {
    
}
#cerrar {
    display:none;
}
</style>
<body>

<div id="sidebar" class="sidebar">
  <a href="#" class="boton-cerrar" onclick="ocultar()">&times;</a>
  
  <ul>
    <center><h1 style="font-size:16px;">FORMULARIO ANTEPROYECTO POA 2024</h1></center>
    <li><a href="<?php echo base_url(); ?>assets/video/FORM_POA_N°4_ACTIVIDADES.xlsx" download  title="formulario N° 4 de Anteproyecto POA 2024" style="font-size:13px;">+ Formulario Poa N° 4 (Actividades)</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/FORM_POA_N°5_PROG FISICO FINANCIERA.xlsx" download  title="formulario N° 5 de Anteproyecto POA 2024" style="font-size:13px;">+ Formulario Poa N° 5 (Requerimientos)</a></li>
  </ul>

  <ul>
    <center><h1 style="font-size:18px;">RESOLUCIONES DE DIRECTORIO CNS</h1></center>
    <li><a href="<?php echo base_url(); ?>assets/video/RESOLUCIONES_DIRECTORIO.xlsx" download  title="RESOLUCION DE DIRECTORIO" style="font-size:13px;">+ Resolución de Directorio Gestión 2019 2020 y 2021 Ajustada</b></a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/FICHA_SEGUIMIENTO_RESOLUCIONES_CNS_DNPLAN2023.xlsx" download  title="FICHA DE SEGUIMIENTO" style="font-size:13px;">+ ficha de Seguimiento Resoluciones</b></a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/Matriz_Seguimiento_Resoluciones_CNS_DNPLAN.xlsx" download  title="MATRIZ SEGUIMIENTO" style="font-size:13px;">+ Matriz de Seguimiento Resoluciones</b></a></li>
  </ul>

  <ul>
    <center><h1 style="font-size:18px;">MATRICES GUIA ELABORACION ITCP</h1></center>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_ALTERNATIVAS_SOLUCION_CNS.xlsx" download  title="Matriz ITCP Alternativas Solucion" style="font-size:13px;">1).- Matriz ITCP Alternativas Solucion</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_ANALISIS_INVOLUCRADOS_DEL_PROYECTO.xlsx" download  title="Matriz ITCP Analisis Involucrados del Proyecto" style="font-size:13px;">2).- Matriz ITCP Analisis Involucrados del Proyecto</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_BENEFICIOS_CNS.xlsx" download  title="Matriz ITCP Beneficios" style="font-size:13px;">3).- Matriz ITCP Beneficios</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_COMPROMISO_SOCIAL_CNS.xlsx" download  title="Matriz ITCP Compromisos Social" style="font-size:13px;">4).- Matriz ITCP Compromisos Social</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_CONCLUSIONES_RECOMENDACIONES_CNS.xlsx" download  title="Matriz ITCP Conclusiones Recomendaciones" style="font-size:13px;">5).- Matriz ITCP Conclusiones Recomendaciones</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_DATOS_GENERALES_DEL_PROYECTO.xlsx" download  title="Matriz ITCP Datos Generales del Proyecto" style="font-size:13px;">6).- Matriz ITCP Datos Generales del Proyecto</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_IDENTIFICACION_POSIBLES_IMPACTOS_AMBIENTALES_RIEGOS_CNS.xlsx" download  title="Matriz ITCP Identificacion posibles impactos ambientales riesgos" style="font-size:13px;">7).- Matriz ITCP Identificacion posibles impactos ambientales riesgos</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_JUSTIFICACION_INCIATIVA_DEL_PROYECTO.xlsx" download  title="Matriz ITCP Justificacion Iniciativa del Proyecto" style="font-size:13px;">8).- Matriz ITCP Justificacion Iniciativa del Proyecto</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_OBJETIVOS_MEDIOS_FINES_CNS.xlsx" download  title="Matriz ITCP Objetivos Medios Fines" style="font-size:13px;">9).- Matriz ITCP Objetivos Medios Fines</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_PROBLEMAS_CAUSAS_EFECTOS_CNS.xlsx" download  title="Matriz ITCP Problemas Causas Efectos" style="font-size:13px;">10).- Matriz ITCP Problemas Causas Efectos</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_SITUACION_LEGAL_DERECHO_PROPIETARIO_PREDIOS_CNS.xlsx" download  title="Matriz ITCP Situacion Legal Derecho Propiestario Predios" style="font-size:13px;">11).- Matriz ITCP Situacion Legal Derecho Propiestario Predios</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_GUIA_ELABORACION_ITCP_CNS/MATRIZ_ITCP_TERMINOS_REFERNCIA_PRESUPUESTO_REFERENCIAL_CNS.xlsx" download  title="Matriz ITCP Terminos de Referencia Presupuesto referencial" style="font-size:13px;">12).- Matriz ITCP Terminos de Referencia Presupuesto referencial</a></li>
  </ul>

  <ul>
    <center><h1 style="font-size:18px;">MATRICES INFORMES VIABILIDAD ITCP - EDTP</h1></center>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_INFORMES_VIABILIDAD_ITCP_EDTP_CNS/MATRIZ_EDTP_INFORME_VIABILIDAD_CNTIP_CRTIP_CNS.xlsx" download  title="Matriz EDTP Informe Viabilidad CNTIP CRTIP" style="font-size:13px;">1).- Matriz EDTP Informe Viabilidad CNTIP CRTIP</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRICES_INFORMES_VIABILIDAD_ITCP_EDTP_CNS/MATRIZ_ITCP_INFORME_VIABILIDAD_CNTIP_CRTIP_CNS.xlsx" download  title="Matriz ITCP Informe Viabilidad CNTIP CRTIP" style="font-size:13px;">2).- Matriz ITCP Informe Viabilidad CNTIP CRTIP</a></li>
  </ul>

  <ul>
    <center><h1 style="font-size:18px;">MATRIZ PROYECTOS NUEVOS 2023</h1></center>
    <li><a href="<?php echo base_url(); ?>assets/video/MATRIZ_PROYECTOS_NUEVOS_2023_BOLSA/SEGUI_NUEVOS_20_CNS_02062023.xlsx" download  title="Matriz Proyectos de Inversion Nuevos 2023" style="font-size:13px;">1).- Matriz Proyectos de Inversion Nuevos 2023</a></li>
  </ul>

</div>

<!-- <div id="sidebar" class="sidebar">
  <a href="#" class="boton-cerrar" onclick="ocultar()">&times;</a>
  <center><h1>ARCHIVOS DE MIGRACI&Oacute;N POA 2022</h1></center>
  <ul class="menu">
    <li><a href="<?php echo base_url(); ?>assets/video/plantilla1_migracion_form4_unidad.xlsx" download  title="ARCHIVO DE MIGRACION - FORMULARIO N° 4 (POR UNIDAD RESPONSABLE)">1.- ARCHIVO DE MIGRACI&Oacute;N - FORMULARIO 4 <b>(POR UNIDAD RESPONSABLE)</b></a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/plantilla2_migracion_form4_general.xlsx" download  title="ARCHIVO DE MIGRACION - FORMULARIO N° 4 (GENERAL)">2.- ARCHIVO DE MIGRACI&Oacute;N - FORMULARIO 4 <b>(GLOBAL)</b></a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/plantilla3_migracion_form5_actividad.xlsx" download  title="ARCHIVO DE MIGRACION - REQUERIMIENTOS POR CADA ACTIVIDAD">3.- ARCHIVO DE MIGRACI&Oacute;N - REQUERIMIENTOS <b>(POR ACTIVIDAD)</b></a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/plantilla4_migracion_form5_global.xlsx" download  title="ARCHIVO DE MIGRACION - REQUERIMIENTOS DE MANERA GLOBAL">4.- ARCHIVO DE MIGRACI&Oacute;N - REQUERIMIENTOS <b>(GLOBAL)</b></a></li>
  </ul>
  <center><h1>FORMULARIOS EDICI&Oacute;N POA 2022</h1></center>
  <ul class="menu">
    <li><a href="<?php echo base_url(); ?>assets/video/FORM_SOL_POA_5_2022.xlsx" download  title="FORMULARIO DE CERTIFICACIÓN POA">1.- FORMATO DE SOLICITUD DE CERTIFICACI&Oacute;N POA</a></li>
    <li><a href="<?php echo base_url(); ?>assets/video/FORM_MOD_4_Y_5_2022.xlsx" download  title="FORMULARIO DE MODIFICACION POA - ACTIVIDADES Y REQUERIMIENTOS">2.- FORMATO DE SOLICITUD DE MODIFICACI&Oacute;N POA</a></li>
  </ul>
</div> -->



<div id="contenido">
<a id="abrir" class="abrir-cerrar" href="javascript:void(0)" onclick="mostrar()"><b>ABRIR LISTA ARCHIVOS</b></a><a id="cerrar" class="abrir-cerrar" onclick="ocultar()">CERRAR LISTA</a>
  <div class="container">
<div id="login" class="signin-card">
  <div class="logo-image">
  <img src="<?php echo base_url(); ?>assets/login/img/caja.png" alt="Logo" title="Logo" style="width:30%; height:30%;">
  </div>
  <h1 class="display1">SIIPLAS V2.0</h1>

    <?php
    if($this->session->flashdata('danger')){ ?>
        <p style="background-color:#ef8181; color: white"><b><?php echo $this->session->flashdata('danger');?></b></p>
    <?php 
      }
      elseif($this->session->flashdata('warning')){ ?>
        <p style="background-color:#e2ba77; color: white"><b><?php echo $this->session->flashdata('danger');?></b></p>
        <?php
      }
    ?>

    <!-- <center><font color="blue"><b>(POA 2020 - VERI&Oacute;N BETA)</b></font></center> -->
  <p class="subhead"><b>SISTEMA DE PLANIFICACI&Oacute;N DE SALUD</b></p>
    <form role="form" action="<?php echo base_url(); ?>index.php/admin/validate" method="post"  id="form" class="login-form">
      <input type="hidden" name="tp" id="tp" value="0">
      <div id="form-login-username" class="form-group">      
        <input type="radio" name="radio-inline" id="radio0" checked="checked">
        <i></i><b>Administrador</b></label> &nbsp;&nbsp; 
        <input type="radio" name="radio-inline" id="radio1">
        <i></i><font color="#146f64"><b>Seg. POA (E.S.)</b></font></label>
      </div>
      <div id="form-login-username" class="form-group">      
        <input id="username" class="form-control" name="user_name" id="usu" type="text" size="18" alt="login" required />
        <span class="form-highlight"></span>
        <span class="form-bar"></span>
        <label for="username" class="float-label"><div id="usu">Usuario : </div></label>
      </div>
      <div id="form-login-password" class="form-group">
        <input class="form-control" id="password" name="password" type="password" size="18" alt="password" required>
        <span class="form-highlight"></span>
        <span class="form-bar"></span>
        <label for="password" class="float-label"><div id="pw">Password : </div></label>
      </div>
      <div id="form-login" class="form-group">
        <input class="form-control" id="dat_captcha" name="dat_captcha" type="text" size="4" required autocomplete="off">
        <span class="form-highlight"></span>
        <span class="form-bar"></span>
        <label class="float-label"><div id="cp">C&oacute;digo de Seguridad : </div></label>
      </div>

      <p class="caja" id="refreshs"><b><?php echo $cod_captcha;?></b></p>

      <input type="hidden" name="captcha" id="captcha" value="<?php echo $captcha;?>">
      
      <div id="but2" class="form-group">
        <div class="checkbox checkbox-default">       
            <input id="remember" type="checkbox" value="yes" alt="Remember me" class="" name="remember">
            <label for="remember">Mantener el Acceso</label>      
        </div>
      </div>
      <div id="but">
        <button class="btn btn-lg btn-success" type="submit" name="Submit" id="sub" style="width:100%;">Ingresar</button>  
      </div>
      <div id="load" style="display: none" align="center">
        <img src="<?php echo base_url() ?>/assets/img/loading.gif" width="50">
      </div>
      </div>
    </form>
 
  </div>
</div>
</div>
  <script src='<?php echo base_url(); ?>assets/login/js/jquery.min.js'></script>
  <script src='<?php echo base_url(); ?>assets/login/js/gubja.js'></script>
  <script src='<?php echo base_url(); ?>assets/login/js/yaozl.js'></script>
  <script  src="<?php echo base_url(); ?>assets/login/js/index.js"></script>
  <script>
  function mostrar() {
      document.getElementById("sidebar").style.width = "300px";
      document.getElementById("contenido").style.marginLeft = "300px";
      document.getElementById("abrir").style.display = "none";
      document.getElementById("cerrar").style.display = "inline";
  }

  function ocultar() {
      document.getElementById("sidebar").style.width = "0";
      document.getElementById("contenido").style.marginLeft = "0";
      document.getElementById("abrir").style.display = "inline";
      document.getElementById("cerrar").style.display = "none";
  }
  </script>
    <script type="text/javascript">
        $(document).ready(function(e) {
          $('#refreshs').click(function(){
              
              var url = "<?php echo site_url("")?>/user/get_captcha";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json', 
              });

              request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                  $("#refreshs").html(response.cod_captcha);
                  document.getElementById("captcha").value = response.captcha;
                }
              }); 
          });
        });

        $("#sub").on("click", function (e) {
          document.getElementById("but").style.display = 'none';
          document.getElementById("but2").style.display = 'none';
          document.getElementById("load").style.display = 'block';
        });
    </script>

    <script type="text/javascript">
      $(function(){
        $('#radio0').click(function(){
          $('[name="tp"]').val(0);
          $('[name="user_name"]').val('');
          $('[name="password"]').val('');
          $('#usu').html('Usuario : ');
          $('#pw').html('Password : ');
          $('#cp').html('Código de Seguridad : ');
        });

        $('#radio1').click(function(){
          $('[name="tp"]').val(1);
          $('[name="user_name"]').val('');
          $('[name="password"]').val('');
          $('#usu').html('<font color="#146f64">Establecimiento de Salud : </font>');
          $('#pw').html('<font color="#146f64">Clave : </font>');
          $('#cp').html('<font color="#146f64">Código de Seguridad : </font>');
        });

      })
    </script>
</body>
</html>
