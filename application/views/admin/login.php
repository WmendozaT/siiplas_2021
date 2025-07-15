
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex, nofollow">

    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Inicia sesión en Sistema de Planificacion</title>
    <link rel="icon" href="favicon.ico" />
    <link href='<?php echo base_url(); ?>assets/login_nuevo/css/style.min.css' rel="stylesheet" />

    <link href='<?php echo base_url(); ?>assets/login_nuevo/css/custom.min.css' rel="stylesheet" />
    <link href='<?php echo base_url(); ?>assets/login_nuevo/css/toastr.min.css' rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/login_nuevo/css/tooltipster.bundle.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/login_nuevo/css/tooltipster-sideTip-punk.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/login_nuevo/css/otp-siat.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/login_nuevo/css/login-siat.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/login_nuevo/css/all.min.css">
</head>

<style>

    .caja {
    font-family: sans-serif;
    font-size: 28px;
    font-weight: 100;
    color: #000000;
    background: #d1d9dc;
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
    #loading {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            z-index: 1000;
        }
</style>
<body class="">
<div class="">
    <div id="kc-header" class="hidden">
        <div id="kc-header-wrapper" class=""><div></div></div>
    </div>
    <div class="">
        <header class="">
            <h1 id="kc-page-title"></h1>
      </header>
      <div id="kc-content">
        <div id="kc-content-wrapper">
        <div class="background-siat-login overflow-hidden d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="container px-md-5 text-center text-lg-start my-5 ">
                <div class="row gx-lg-5 align-items-center mb-sm-0">
                    <div class="col-lg-6 mb-sm-0 mb-lg-0 text-center mt-lg-0" style="z-index: 10">
                        <div class="imgSiat">
                            <picture>
                                <source srcset="<?php echo base_url(); ?>assets/login_nuevo/img/logo_CNS_header.png" media="(min-width: 992px)" width="200px" height="auto">
                                <source srcset="/resources/etrf1/login/siatV2/img/svg/siat.svg" media="(min-width: 768px)" width="200px" height="auto">
                                <img class="img-fluid animateBolivia" src="/resources/etrf1/login/siatV2/img/svg/siat.svg" alt="logoSiatBolivia" width="200px" height="auto">
                            </picture>
                            
                            <h1 class="my-5 display-5 fw-bold ls-tight text-center titleSiat" style="color: hsl(218, 81%, 95%)">
                                Sistema de Planificaci&oacute;n y Seguimiento POA
                                <br/>
                                <span style="color: #FFFF">SISPLAS v2.0</span>
                            </h1>
                            
                            <div class="redesSocialesHeader">
                                <a href="https://www.facebook.com/CNS.Bolivia/" target="_blank"><img class="rrss mx-2" src="<?php echo base_url(); ?>assets/login_nuevo/img/facebook.svg"/ alt="rrssFacebook"></a>
                                <a href="https://www.instagram.com/cnsbolivia/" target="_blank"><img class="rrss mx-2" src="<?php echo base_url(); ?>assets/login_nuevo/img/instagram.svg"/ alt="rrssinstagram"></a>
                                <a href="https://www.youtube.com/channel/UCH8i2IHse60iSiyeYAihomg" target="_blank"><img class="rrss mx-2" src="<?php echo base_url(); ?>assets/login_nuevo/img/youtube.svg"/ alt="rrssYoutube"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-lg-0 position-relative">
                    <br/>
                        <div class="card bg-card">
                            <div class="card-body px-4 py-4 px-md-5">

                            <div id="loading"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
                                <form role="form" action="<?php echo base_url(); ?>index.php/admin/validate" method="post" id="form" class="login-form">
                                    <input type="hidden" name="tp" id="tp" value="0">
                                    <div class="text-center">
                                        <img class="img-fluid" src="dnp1.png" alt="logoSiat" width="100px" height="auto">
                                    </div>
                                    <h5 class="text-center fw-bold my-4 titleBienvenido">Bienvenido/a!</h5>

                                    <div class="row align-items-center">
                                        <div class="col">
                                        <div id="form-login-username" class="form-group">      
                                            <input type="radio" name="radio-inline" id="radio0" checked="checked">
                                            <i></i><b>Unidad Administrativa</b></label> &nbsp;&nbsp; 
                                            <input type="radio" name="radio-inline" id="radio1">
                                            <i></i><font color="#146f64"><b>Establecimiento de Salud</b></font></label>
                                        </div>
                                        </div>
                                    </div>

                                    <input id="deviceId" class="dOt" name="deviceId">

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="form-floating mb-2">
                                                <input tabindex="1" type="text" class="form-control form-input-bg" name="user_name" placeholder="USUARIO" minlength="5" maxlength="20" autocomplete="off" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                <label for="user_name">CLAVE DE ACCESO</label>
                                                <div id="usu" class="text-danger text-start" style="visibility: hidden;">
                                                    Este campo es requerido
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto pf-0">
                                            <img src="<?php echo base_url(); ?>assets/login_nuevo/img/help.svg" class="tootip" title="USUARIO: Acceso asignado por el Departamento Nacional de Planificación"/>
                                        </div>
                                    </div>

                                    <input id="deviceId" class="dOt" name="deviceId">

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="form-floating mb-2">
                                                <input tabindex="3" id="password" class="form-control form-input-bg" name="password" type="password" autocomplete="off" placeholder="CONTRASEÑA" minlength="6" maxlength="20"/>
                                                <label for="password">PASSWORD</label>
                                                <div id="pass" class="text-danger text-start" style="visibility: hidden;">
                                                    Este campo es requerido
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto pf-0">
                                            <img src="<?php echo base_url(); ?>assets/login_nuevo/img/help.svg" class="tootip" title="La contraseña debe de tener (Mayúscula)"/>
                                        </div>
                                    </div>

                                    <div class="text-center py-3">
                                        <p class="caja" id="refreshs" style="text-align:center"><b><?php echo $cod_captcha;?></b></p>
                                        <input type="hidden" name="captcha" id="captcha"  value="<?php echo $captcha;?>" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                    </div>

                                    <div class="mb-4">
                                        <input tabindex="4" id="dat_captcha" name="dat_captcha" type="text" class="form-control form-input-bg text-center" placeholder="Ingrese el texto de la imagen" autofocus minlength="4" maxlength="4" >
                                    </div>

                                    <div class="d-grid gap-2 mt-2">
                                        <input tabindex="4" class="btn btn-lg mdl-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" name="login" id="kc-login" type="submit" value="INGRESAR"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

        </div>
      </div>

    </div>
  </div>

<script src='<?php echo base_url(); ?>assets/login/js/jquery.min.js'></script>
<script>
    $(function(){
        $('#radio0').click(function(){
          $('[name="tp"]').val(0);
        });

        $('#radio1').click(function(){
          $('[name="tp"]').val(1);
        });
    })

    $(document).ready(function() {
        $('#form').on('submit', function(event) {
            event.preventDefault(); // Evitar el envío del formulario

            // Mostrar el loading
            $('#loading').show();

            // Validación de datos
            let valid = true;

            // Validar usuario
            const userName = $('input[name="user_name"]').val();
            if (userName.trim() === '') {
                $('#usu').css('visibility', 'visible');
                valid = false;
            } else {
                $('#usu').css('visibility', 'hidden');
            }

            // Validar contraseña
            const password = $('#password').val();
            if (password.trim() === '') {
                $('#pass').css('visibility', 'visible');
                valid = false;
            } else {
                $('#pass').css('visibility', 'hidden');
            }

            // Validar captcha
            const captcha = $('#dat_captcha').val();
            if (captcha.trim() === '') {
                alert('Por favor, ingresa el texto de la imagen.');
                valid = false;
            }

            if (valid) {
                // Simulación de envío de datos
                this.submit(); // Enviar el formulario si es válido
            } else {
                $('#loading').hide(); // Ocultar loading si hay un error
            }
        });
    });
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
</body>
</html>
